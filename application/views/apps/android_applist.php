<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="content" class="container-fluid">
	<h1 class="p-2 text-white" style="background-color:#6B8E23"><i class="fab fa-android"></i> <?= $page_title ?></h1>
	<div class="m-2">
		<?php if( !is_null( $latest_app_data ) ): ?>
		<div class="p-3 float-left border rounded shadow-sm">
			<div class="row">
				<div class="mx-3 my-2">
					<h2><i class="far fa-star"></i> 最新のアプリデータ</h2>
					<div class="p-2">
						配布ID: #<?= h( $latest_app_data['distrib_id'] ) ?><br/>
						バージョン: <?= h( $latest_app_data['app_version'] ) ?><br/>
						アップロード日: <?= h( $latest_app_data['upload_time'] ) ?>
					</div>
				</div>
				<div class="mx-3 text-center">
					<div class="m-2 col">
						<a class="btn btn-light text-primary p-3" href="<?= $latest_app_data['apk_link'] ?>" download>
							<i class="fas fa-download fa-5x"></i><br/>
							<?= $this->config->item( $platform.'_'.$environment.'_apk_name' ) ?>.apkを<br/>
							ダウンロード
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php endif ?>
		<br class="container mt-5" />
		
		<h2><i class="fas fa-list"></i> アプリデータ一覧 <span class="mx-2 px-1 text-white" style="background-color:#6B8E23"><?= $item_num ?></span></h2>
		<div class="float-right">
			<?php if( UserModel::is_manager() ): ?>
			<button type="button" class="m-1 btn btn-primary" data-toggle="modal" data-target="#upload_modal"><i class="fas fa-upload"></i> apkファイルのアップロード</button>
			<?php endif ?>
		</div>
		<table class="table table-hover shadow-sm">
			<thead>
				<tr>
					<th>配布ID</th>
					<th>アプリバージョン</th>
					<th>apkファイルダウンロード</th>
					<th>アップロード日</th>
					<?php if( UserModel::is_manager() ): ?>
					<th>削除</th>
					<?php endif ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $app_view_list as $row ): ?>
				<tr>
					<td class="align-middle"><?= h( $row['distrib_id'] ) ?></td>
					<td class="align-middle"><?= h( $row['app_version'] ) ?></td>
					<td><a class="btn btn-light text-primary" href="<?= $row['apk_link'] ?>" download><i class="fas fa-download"></i> <?= $this->config->item( $platform.'_'.$environment.'_apk_name' ) ?>.apk</a></td>
					<td class="align-middle"><?= h( $row['upload_time'] ) ?></td>
					<?php if( UserModel::is_manager() ): ?>
					<td><button type="button" class="btn btn-danger" id="delete_apk_<?= h( $row['distrib_id'] ) ?>"><i id="delete_icon_<?= h( $row['distrib_id'] ) ?>" class="fas fa-trash-alt"></i></button></td>
					<?php endif ?>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?= $this->pagination->create_links(); ?>
	</div>
	<br class="container mt-5" />
	<div class="m-2">
		<div class="card">
			<div class="card-header bg-info text-white">
				<i class="fas fa-info-circle"></i> 使い方
			</div>
			<div class="card-body">
				<div class="card-deck mb-2">
					<div class="card">
						<div class="card-header">
							<i class="fas fa-laptop"></i><i class="fab fa-android"></i> Android SDKを使って、PCからAndroid端末にインストール
						</div>
						<div class="card-body">
							<ol>
								<li>Android端末をPCに接続します。</li>
								<li>apkファイルをダウンロードします。</li>
								<li>
                                    Android SDKのadbコマンドで、Android端末にインストールします。<br/>
                                    <pre class="bg-dark text-white p-2">adb install -r <?= $this->config->item( $platform.'_'.$environment.'_apk_name' ) ?>.apk</pre>
                                </li>
							</ol>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<i class="fas fa-mobile-alt"></i> Androidデバイスに直接インストール
						</div>
						<div class="card-body">
							<ol>
								<li>
									Androidのブラウザでこのページにアクセスします。
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
								<li>apkファイルをダウンロードし、アプリをインストールします。</li>
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
					<li>Android 7.1以前では、あらかじめAndroidデバイスの「設定」→「セキュリティ」で「提供現不明のアプリ」にチェックを入れておく必要があります。</li>
					<li>Android 8.0以降では、<?= $this->config->item( $platform.'_'.$environment.'_app_name' ) ?>のアプリ情報で「不明なアプリのインストール」を許可しておく必要があります。</li>
				</ul>
			</div>
		</div>
		<br class="container mt-5" />
		<div class="card">
			<div class="card-header bg-info text-white">
				<i class="fas fa-rss-square"></i> 最近の情報
			</div>
			<div class="card-body">
				<?php if( !empty( $feed_data ) ): ?>
				<table class="table table-borderless table-sm table-responsive">
					<tbody>
						<?php foreach( $feed_data as $feed ): ?>
							<tr>
								<td>&middot;</td>
								<td><?= $feed['create_time'] ?></td>
								<td><span class="badge badge-info"><?= $this->config->item( $feed['category'] ) ?></span></td>
								<td><?= h( $feed['content'] ) ?></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<?php else: ?>
					<p>最近の情報はありません。</p>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="upload_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">apkファイルのアップロード</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="response_panel" class="alert alert-success" role="alert"></div>
			<form id="uploadform">
				<div class="modal-body">
					<div class="m-2">
						<label class="col-form-label">apkファイル</label>
						<span class="badge badge-danger">必須</span>
						<input id="upload_apk_path" name="upload_apk_path" class="py-5 form-control" type="file"/>
						<div class="m-1 p-2 alert alert-info"><i class="fas fa-info-circle"></i> ↑ここにapkファイルをドロップすることもできます。</div>
						<hr/>
						<label class="col-form-label">バージョン</label>
						<span class="badge badge-danger">必須</span>
						<input type="text" class="form-control" id="upload_apk_ver" name="upload_apk_ver" placeholder="X.XX.XX">
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

	$( document ).ready( function() {
		$( '#response_panel' ).hide();

		$( '#qrcode' ).qrcode( { width: 120, height: 120, text: "<?= site_url( "apps/{$platform}/{$environment}" ) ?>" } );
		
		<?php if( UserModel::is_manager() ) { ?>
		$( '#uploadform' ).submit( function( e ) {
            e.preventDefault();
			$( '#upload_btn' ).prop( 'disabled', true );
			$( '#upload_btn_icon' ).removeClass( "fa-upload" ).addClass( "fa-spinner fa-spin" );
			$( '#upload_btn_text' ).text( "アップロード中" );
			var formData = new FormData();
			formData.append( 'app_file', $( '#upload_apk_path' ).prop( 'files' )[0] );
			formData.append( 'app_version', $( '#upload_apk_ver' ).val() );

            $.ajax({
                type: "POST",
                url: "<?= site_url( "apps/{$platform}/{$environment}/app/upload" ) ?>",
                dataType: "json",
                data: formData,
				processData: false,
				contentType: false,
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
				$( '#response_panel' ).html( '<i class="fas fa-times"></i> エラー: apkファイルのアップロードに失敗しました。' );
				$( '#upload_btn_text' ).text( "アップロード" );
				$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-upload" );
				$( '#upload_btn' ).prop( 'disabled', false );
			}).always( function() {
				$( '#response_panel' ).show();
			});
        });

		$( '[id ^= delete_apk_]' ).on( 'click', function() {
            var dstid = $( this ).attr( 'id' ).replace( "delete_apk_", "" );
			$( this ).prop( 'disabled', true );
			$( '#delete_icon_' + dstid ).removeClass( "fa-trash-alt" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "配布ID: #" + dstid + " を一覧から削除してよろしいですか？\n（注: 削除したアプリデータは復元できません。）" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "apps/{$platform}/{$environment}/app/delete" ) ?>",
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
                	$( '#delete_apk_' + dstid ).prop( 'disabled', false );
				});
            }
			else {
				$( '#delete_icon_' + dstid ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                $( '#delete_apk_' + dstid ).prop( 'disabled', false );
			}
        });
		<?php } ?>
    });
</script>