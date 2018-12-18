<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#2595C7;color:#fff"><i class="fab fa-windows"></i> <?= $page_title ?></h1>
	<div class="m-2">
		<?php if( !is_null( $latest_app_data ) ): ?>
        <div class="p-3 col-lg-12 col-xl-9 border rounded shadow-sm">
			<div class="row">
				<div class="col-md-11 col-lg-4">
					<h2><i class="far fa-star"></i> 最新のビルド</h2>
					<div class="p-2">
						配布ID: #<?= h( $latest_app_data['distrib_id'] ) ?><br/>
						バージョン: <?= h( $latest_app_data['app_version'] ) ?><br/>
						アップロード日: <?= h( $latest_app_data['upload_time'] ) ?>
					</div>
				</div>
                <div class="col-md-11 col-lg-8 row text-center">
					<div class="col">
                        <a class="btn btn-light text-primary p-3" href="<?= $latest_app_data['appx_link'] ?>" download>
							<i class="fas fa-download fa-5x"></i><br/>
							<?= $this->config->item( $platform.'_'.$environment.'_appx_name' ) ?>.appxbundleを<br/>
							ダウンロード
						</a>
                    </div>
                    <div class="col">
						<a class="btn btn-light text-primary p-3" href="<?= $latest_app_data['appx_direct_link'] ?>" download>
							<i class="fas fa-plane fa-5x"></i><br/>
							Windows 10デバイスに<br/>ダイレクトインストール
						</a>
					</div>
					<div class="col">
						<?php if( $has_valid_cert ): ?>
						<a class="btn btn-light text-primary p-3" href="/download/uwp/<?= $environment ?>/cert" download>
                            <i class="far fa-address-card fa-5x"></i><br/>
							サイドロードアプリ用<br/>証明書をインストール
						</a>
						<?php else: ?>
						<div class="btn btn-light disabled p-3" data-toggle="tooltip" data-placement="right" data-html="true" title="<i class='fas fa-exclamation-triangle'></i> サイドロードアプリ用<br/>証明書の有効期限が切れています。">
							<i class="far fa-address-card fa-5x"></i><br/>
							サイドロードアプリ用<br/>証明書をインストール<br/>
							（<i class="fas fa-ban"></i> 利用不可）
						</div>
						<?php if( UserModel::is_manager() ): ?>
						<br/><a class="text-primary" href="<?= "/apps/{$platform}/manage-certificate" ?>"><i class="fas fa-sync-alt"></i> 証明書を更新する</a>
						<?php endif ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif ?>
		<br class="container mt-5" />
		
		<h2><i class="fas fa-list"></i> ビルド一覧</h2>
		<div class="float-right">
			<?php if( UserModel::is_manager() ) : ?>
			<button type="button" class="m-1 btn btn-primary" data-toggle="modal" data-target="#upload_modal"><i class="fas fa-upload"></i> appxbundleファイルのアップロード</button>
			<?php endif ?>
		</div>
		<table class="table table-hover shadow-sm">
			<thead>
				<tr>
					<th>配布ID</th>
					<th>アプリバージョン</th>
                    <th>appxbundleファイルのダウンロード</th>
                    <th>ダイレクトインストール</th>
					<th>アップロード日</th>
					<?php if( UserModel::is_manager() ): ?>
					<th>削除</th>
					<?php endif ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $app_view_list as $row ): ?>
				<tr>
					<td><?= h( $row['distrib_id'] ) ?></td>
					<td><?= h( $row['app_version'] ) ?></td>
                    <td><a class="btn btn-light text-primary" href="<?= $row['appx_link'] ?>" download><i class="fas fa-download"></i> <?= $this->config->item( $platform.'_'.$environment.'_appx_name' ) ?>.appxbundle</a></td>
                    <td><a class="btn btn-light text-primary" href="<?= $row['appx_direct_link'] ?>" download><i class="fas fa-plane"></i> ダイレクトインストール</a></td>
					<td><?= h( $row['upload_time'] ) ?></td>
					<?php if( UserModel::is_manager() ): ?>
					<td><button type="button" class="btn btn-danger" id="delete_appx_<?= h( $row['distrib_id'] ) ?>"><i id="delete_icon_<?= h( $row['distrib_id'] ) ?>" class="fas fa-trash-alt"></i></button></td>
					<?php endif ?>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?= $this->pagination->create_links(); ?>
	</div>
	<br class="container mt-5" />
	<div class="m-10">
		<div class="card">
			<div class="card-header bg-info text-white">
				<i class="fas fa-info-circle"></i> 使い方
			</div>
			<div class="card-body">
				<div class="card-deck mb-2">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-desktop"></i> <i class="fas fa-laptop"></i> appxbundleファイルをダウンロードして、Windowsデバイスにインストール
                        </div>
                        <div class="card-body">
                            <ol>
								<li>
                                    Windows 10のブラウザでこのページにアクセスします。
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">URL</span>
                                        </div>
                                        <input type="text" id="thisPageURL" class="form-control" value="<?= site_url( "apps/{$platform}/{$environment}" ) ?>" readonly />
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()"><i class="fas fa-copy"></i></button>
                                        </div>
                                    </div>
                                </li>
                                <li>appxbundleファイルのダウンロードリンクをクリックし、appxbundleをダウンロードします。</li>
                                <li>appxbundleファイルをダブルクリックし、アプリをインストールします。</li>
                            </ol>
                        </div>
                    </div>
					<div class="card">
						<div class="card-header">
                            <i class="fas fa-tablet-alt"></i> <i class="fas fa-mobile-alt"></i> Windows 10デバイスに直接インストール
						</div>
						<div class="card-body">
							<ol>
								<li>
									Windows 10のブラウザでこのページにアクセスします。
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">URL</span>
										</div>
										<input type="text" id="thisPageURL" class="form-control" value="<?= site_url( "apps/{$platform}/{$environment}" ) ?>" readonly />
										<div class="input-group-append">
											<button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()"><i class="fas fa-copy"></i></button>
										</div>
									</div>
									<div id="qrcode" class="p-2"></div>
								</li>
								<li>ダイレクトインストール列にあるリンクをタップし、アプリをインストールします。</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		<div>
		<div class="card">
			<div class="card-header bg-warning">
				<i class="fas fa-exclamation-triangle"></i> 注意
			</div>
			<div class="card-body">
				<ul>
					<li>ダイレクトインストール機能は、Windows 10 Fall Creators Update以降に対応しています。</li>
					<li>インストールするPCはあらかじめ「開発者モード」もしくは「サイドローディングモード」を有効にし、証明書をインストールする必要があります。</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="upload_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">appxbundleファイルのアップロード</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="uploadform">
				<div class="modal-body">
					<div class="m-2">
						<label class="col-form-label">appxbundleファイル</label>
						<span class="badge badge-danger">必須</span>
						<input id="upload_appx_path" name="upload_appx_path" class="py-5 form-control" type="file"/>
						<div class="m-1 p-2 alert alert-info"><i class="fas fa-info-circle"></i> ↑ここにappxbundleファイルをドロップすることもできます。</div>
						<hr/>
						<label class="col-form-label">バージョン</label>
						<span class="badge badge-danger">必須</span>
						<input type="text" class="form-control" id="upload_app_ver" name="upload_app_ver" placeholder="X.XX.XX">
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

<script type="text/javascript" src="<?= site_url( "js/qrcode/jquery.qrcode.min.js" ) ?>"></script>
<script>
	function copyToClipboard() {
		var copyTarget = document.getElementById( "thisPageURL" );
		copyTarget.select();
		document.execCommand( "Copy" );
	}

	$( function () {
		$( '[data-toggle = "tooltip"]' ).tooltip();
	})

	$( document ).ready( function() {
		$( '#qrcode' ).qrcode( { width: 120, height: 120, text: "<?= site_url( "apps/{$platform}/{$environment}" ) ?>" } );
		
		<?php if( UserModel::is_manager() ): ?>
		$( '#uploadform' ).submit( function( e ) {
            e.preventDefault();
			$( '#upload_btn' ).prop( 'disabled', true );
			$( '#upload_btn_icon' ).removeClass( "fa-upload" ).addClass( "fa-spinner fa-spin" );
			$( '#upload_btn_text' ).text( "アップロード中" );
			var formData = new FormData();
			formData.append( 'app_file', $( '#upload_appx_path' ).prop( 'files' )[0] );
			formData.append( 'app_version', $( '#upload_app_ver' ).val() );
            
            $.ajax({
                type: "POST",
                url: "<?= site_url( "apps/{$platform}/{$environment}/upload-appx" ) ?>",
                dataType: "json",
                data: formData,
				processData: false,
				contentType: false
            }).done( function( response ) {
				alert( response.message );
				if( !response.error ) {
					window.location.reload();
				}
            }).fail( function( response ) {
				alert( "エラー: appxbundleファイルのアップロードに失敗しました。" );
			}).always( function() {
				$( '#upload_btn_text' ).text( "アップロード" );
				$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-upload" );
				$( '#upload_btn' ).prop( 'disabled', false );
			});
        });

		$( '[id ^= delete_appx_]' ).on( 'click', function() {
            var dstid = $( this ).attr( 'id' ).replace( "delete_appx_", "" );
			$( this ).prop( 'disabled', true );
			$( '#delete_icon_' + dstid ).removeClass( "fa-trash-alt" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "配布ID: #" + dstid + " をビルド一覧から削除してよろしいですか？" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "apps/{$platform}/{$environment}/delete-appx" ) ?>",
                    dataType: "json",
                    data: { 
                        id: dstid
                    }
                }).done( function( response ){
					alert( response.message );
					if( !response.error ) {
						window.location.reload( true );
					}
				}).fail( function( response ) {
					alert( "エラー: 配布ID: #" + dstid + " の削除に失敗しました。" );
				}).always( function() {
                    $( '#delete_icon_' + dstid ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                    $( '#delete_appx_' + dstid ).prop( 'disabled', false );
                });
            }
            else {
                $( '#delete_icon_' + dstid ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                $( '#delete_appx_' + dstid ).prop( 'disabled', false );
            }
        });
		<?php endif ?>
    });
</script>