<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#2595C7"><i class="fas fa-address-card"></i> サイドロードアプリ用証明書の管理</h1>
    <br class="container mt-5" />
    <?php if( $error ): ?>
    <div class="alert alert-danger" role="alert"><?= $message ?></div>
    <?php else: ?>
    <h2><i class="fas fa-list"></i> 証明書一覧</h2>
    <div class="float-right">
        <button type="button" class="m-1 btn btn-primary" data-toggle="modal" data-target="#upload_modal"><i class="fas fa-upload"></i> 新しい証明書ファイルのアップロード</button>
    </div>
    <table class="table table-hover shadow-sm">
        <thead>
            <tr>
                <th>種別</th>
                <th>SHA256ハッシュ値</th>
                <th>メモ</th>
                <th>アップロード日</th>
                <th>作成された日</th>
                <th>有効期限</th>
                <th>ステータス</th>
                <th>無効化</th>
            </tr>
        </thead>
        <tbody>
            <?php if( isset( $cert_list ) ): ?>
            <?php foreach( $cert_list as $row ): ?>
            <tr>
                <td class="align-middle"><?= h( $row['environment_name'] ) ?></td>
                <td class="align-middle"><?= h( $row['hash_value'] ) ?></td>
                <td class="align-middle"><?= h( $row['memo'] ) ?></td>
                <td class="align-middle"><?= h( $row['upload_time'] ) ?></td>
                <td class="align-middle"><?= h( $row['create_time'] ) ?></td>
                <td class="align-middle"><?= h( $row['expire_time'] ) ?></td>
                <td class="align-middle">
                    <?php switch( $row['status'] ) {
                        case UWPCertModel::UWP_CERT_AVAILABLE:
                            echo '<span class="badge badge-success"><i class="far fa-circle"></i> 利用可能</span>';
                            break;
                        case UWPCertModel::UWP_CERT_EXPIRED:
                            echo '<span class="badge badge-danger"><i class="fas fa-times"></i> 有効期限切れ</span>';
                            break;
                    } ?>
                </td>
                <td>
                    <?php if( $row['status'] == UWPCertModel::UWP_CERT_AVAILABLE ): ?>
                    <button type="button" class="btn btn-sm btn-danger" id="disable_cert_<?= h( $row['environment'] ) ?>"><i id="disable_icon_<?= h( $row['environment'] ) ?>" class="fas fa-ban"></i></button>
                    <?php endif ?>
                </td>
            <?php endforeach ?>
            </tr>
            <?php endif ?>
        </tbody>
    </table>
    <?php endif ?>

    <br class="container mt-5" />
	<div class="card">
        <div class="card-header bg-info text-white">
			<i class="fas fa-info-circle"></i> サイドロード用証明書のハッシュ値を確認する方法
        </div>
        <div class="card-body">
            <p>証明書ファイルに含まれるModulusの値（16進数）に対するSHA-256のハッシュ値を求めます。</p>
            Mac / Linuxの場合
			<pre class="bg-dark text-white p-2">$ openssl x509 -modulus -noout -inform der -in {証明書ファイル名}.cer | awk -F '=' '{print $2}' | tr '[A-F]' '[a-f]' | tr -d '\n' | openssl dgst -sha256</pre>
		</div>
	</div>
</div>

<div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="upload_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">証明書のアップロード</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
            <div id="response_panel" class="alert alert-success" role="alert"></div>
			<form id="uploadform">
				<div class="modal-body">
					<div class="m-2">
                        <label class="col-form-label">種別</label>
						<span class="badge badge-warning">選択</span>
                        <select id="target_type" name="target_type" class="form-control">
                            <option value="<?= UWPCertModel::ENVIRONMENT_DEVELOP ?>"><?= UWPCertModel::ENVIRONMENT_NAME_DEVELOP ?></option>
                            <option value="<?= UWPCertModel::ENVIRONMENT_STAGING ?>"><?= UWPCertModel::ENVIRONMENT_NAME_STAGING ?></option>
                            <option value="<?= UWPCertModel::ENVIRONMENT_PRODUCTION ?>"><?= UWPCertModel::ENVIRONMENT_NAME_PRODUCTION ?></option>
                        </select>
                        <hr/>
						<label class="col-form-label">証明書ファイル（.cer）</label>
						<span class="badge badge-danger">必須</span>
						<input id="upload_cert_path" name="upload_cert_path" class="py-5 form-control" type="file"/>
                        <div class="m-1 p-2 alert alert-info"><i class="fas fa-info-circle"></i> ↑ここに証明書ファイルをドロップすることもできます。</div>
                        <hr/>
                        <label class="col-form-label">メモ</label>
						<span class="badge badge-info">任意</span>
						<input type="text" class="form-control" id="cert_memo" name="cert_memo" placeholder="MEMO">
					</div>
				</div>
				<div class="modal-footer">
                    <button type="submit" id="upload_btn" class="btn btn-primary"><i id="upload_btn_icon" class="fas fa-upload"></i> <span id="upload_btn_text">アップロード</span></button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> 閉じる</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$( document ).ready( function() {
        $( '#response_panel' ).hide();

		<?php if( UserModel::is_manager() ): ?>
		$( '#uploadform' ).submit( function( e ) {
            e.preventDefault();
            $( '#upload_btn' ).prop( 'disabled', true );
			$( '#upload_btn_icon' ).removeClass( "fa-upload" ).addClass( "fa-spinner fa-spin" );
			$( '#upload_btn_text' ).text( "アップロード中" );
			var formData = new FormData();
			formData.append( 'cert_file', $( '#upload_cert_path' ).prop( 'files' )[0] );
			formData.append( 'target_type', $( '#target_type' ).val() );
            formData.append( 'cert_memo', $( '#cert_memo' ).val() );
            
            $.ajax({
                type: "POST",
                url: "<?= site_url( "apps/uwp/manage-certificate/upload-cert" ) ?>",
                dataType: "json",
                data: formData,
				processData: false,
				contentType: false
            }).done( function( response ) {
				if( response.error ) {
					$( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
					$( '#response_panel' ).html( '<i class="fas fa-times"></i> ' + response.message );
					$( '#upload_btn_text' ).text( "アップロード" );
					$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-upload" );
					$( '#upload_btn' ).prop( 'disabled', false );
				}
				else {
					$( '#response_panel' ).removeClass( 'alert-danger' ).addClass( 'alert-success' );
					$( '#response_panel' ).html( '<i class="far fa-circle"></i> ' + response.message );
					$( '#upload_btn_text' ).text( "アップロード完了" );
					$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-check" );
					window.setTimeout( function() {
						window.location.reload( true );
					}, 1000 );
				}
            }).fail( function( response ) {
				$( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
				$( '#response_panel' ).html( '<i class="fas fa-times"></i> エラー: 証明書ファイルのアップロードに失敗しました。' );
				$( '#upload_btn_text' ).text( "アップロード" );
				$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-upload" );
				$( '#upload_btn' ).prop( 'disabled', false );
			}).always( function() {
				$( '#response_panel' ).show();
			});
        });

        $( '[id ^= disable_cert_]' ).on( 'click', function() {
            var environment = $( this ).attr( 'id' ).replace( "disable_cert_", "" );
            $( this ).prop( 'disabled', true );
			$( '#disable_icon_' + environment ).removeClass( "fa-ban" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "選択したサイドロード用証明書を無効化してよろしいですか？\n（※再度有効にしたい時は、新しい証明書をアップロードします。）" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "apps/uwp/manage-certificate/disable-cert" ) ?>",
                    dataType: "json",
                    data: { 
                        environment: environment
                    }
                }).done( function( response ){
					alert( response.message );
					if( !response.error ) {
						window.location.reload( true );
					}
				}).fail( function( response ) {
					alert( "エラー: 選択したサイドロード用証明書の無効化に失敗しました。" );
				}).always( function() {
                    $( '#disable_icon_' + environment ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-ban" );
                    $( '#disable_cert_' + environment ).prop( 'disabled', false );
                });
            }
            else {
                $( '#disable_icon_' + environment ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-ban" );
                $( '#disable_cert_' + environment ).prop( 'disabled', false );
            }
        });
		<?php endif ?>
    });
</script>