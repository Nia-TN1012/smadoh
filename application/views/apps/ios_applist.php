<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2 text-white" style="background-color:#FF8F22"><i class="fab fa-apple"></i> <?= $page_title ?></h1>
	<div class="m-2">
		<?php if( !is_null( $latest_app_data ) ): ?>
		<div class="p-3 float-left border rounded shadow-sm">
			<div class="row">
				<div class="mx-3 my-2">
					<h2><i class="far fa-star"></i> 最新のビルド</h2>
					<div class="p-2">
						配布ID: #<?= h( $latest_app_data['distrib_id'] ) ?><br/>
						バージョン: <?= h( $latest_app_data['app_version'] ) ?><br/>
						アップロード日: <?= h( $latest_app_data['upload_time'] ) ?>
					</div>
				</div>
				<div class="mx-3 row text-center">
					<div class="m-2 col">
						<a class="btn btn-light text-primary p-3" href="<?= $latest_app_data['ipa_link'] ?>" download>
							<i class="fas fa-download fa-5x"></i><br/>
							<?= $this->config->item( $platform.'_'.$environment.'_ipa_name' ) ?>.ipaを<br/>
							ダウンロード
						</a>
					</div>
					<div class="m-2 col">
						<?php if( strpos( base_url(), "https://" ) === 0 ): ?>
						<a class="btn btn-light text-primary p-3" href="<?= $latest_app_data['ota_plist_link'] ?>" download>
							<i class="fas fa-plane fa-5x"></i><br/>
							Over-The-Air<br/>インストール
						</a>
						<?php else: ?>
						<div class="btn btn-light p-3 disabled" data-toggle="tooltip" data-placement="right" data-html="true" title="<i class='fas fa-exclamation-triangle'></i> HTTPS通信に非対応のため、Over-The-Airインストール機能を利用できません。">
							<i class="fas fa-plane fa-5x"></i><br/>
							Over-The-Air<br/>インストール<br/>
							（<i class="fas fa-ban"></i> 利用不可）
						</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php endif ?>
		<br class="container mt-5" />

		<h2><i class="fas fa-list"></i> ビルド一覧 <span class="mx-2 px-1 text-white" style="background-color:#FF8F22"><?= $item_num ?></span></h2>
		<div class="float-right">
			<?php if( UserModel::is_manager() ) { ?>
			<button type="button" class="m-1 btn btn-primary" data-toggle="modal" data-target="#upload_modal"><i class="fas fa-upload"></i> ipaファイルをアップロード</button>
			<?php } ?>
		</div>
		<table class="table table-hover shadow-sm">
			<thead>
				<tr>
					<th>配布ID</th>
					<th>アプリバージョン</th>
					<th>ipaファイルをダウンロード</th>
					<th>Over-The-Airインストール</th>
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
					<td><a class="btn btn-light text-primary" href="<?= $row['ipa_link'] ?>" download><i class="fas fa-download"></i> <?= $this->config->item( $platform.'_'.$environment.'_ipa_name' ) ?>.ipa</a></td>
					<?php if( strpos( base_url(), "https://" ) === 0 ): ?>
					<td><a class="btn btn-light text-primary" href="<?= $row['ota_plist_link'] ?>" download><i class="fas fa-plane"></i> Over-The-Airインストール</a></td>
					<?php else: ?>
					<td data-toggle="tooltip" data-html="true" title="<i class='fas fa-exclamation-triangle'></i> HTTPS通信に非対応のため、Over-The-Airインストール機能を利用できません。"><span class="btn btn-light disabled"><i class="fas fa-plane"></i> Over-The-Airインストール（<i class="fas fa-ban"></i> 利用不可）</span></td>
					<?php endif?>
					<td class="align-middle"><?= h( $row['upload_time'] ) ?></td>
					<?php if( UserModel::is_manager() ): ?>
					<td><button type="button" class="btn btn-danger" id="delete_ipa_<?= h( $row['distrib_id'] ) ?>"><i id="delete_icon_<?= h( $row['distrib_id'] ) ?>" class="fas fa-trash-alt"></i></button></td>
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
							<i class="fas fa-laptop"></i> <i class="fab fa-itunes"></i> 手元のiTunesなどを使ってiOSデバイスにインストール
						</div>
						<div class="card-body">
							<ol>
								<li>iOSデバイスをPCに接続します。</li>
								<li>ipaファイルをダウンロードします。</li>
								<li>Windowsの場合、iTunesを起動し、ダウンロードしたipaファイルをインポートして、iOSデバイスにインストールします。</li>
								<li>Macの場合、<a href="https://itunes.apple.com/jp/app/apple-configurator-2/id1037126344" target="_blank">Apple Configurator 2</a>を起動し、ダウンロードしたipaファイルをインポートして、iOSデバイスにインストールします。</li>
							</ol>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<i class="fas fa-mobile-alt"></i> iOSデバイスに直接インストール
						</div>
						<div class="card-body">
							<ol>
								<li>
									Safariでこのページにアクセスします。
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
								<li>Over-The-Airインストール列にある、<b>Over-The-Airインストール</b>のリンクをクリックします。</li>
								<li>アプリをインストールします。</li>
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
					<li>Ad-Hoc形式のiOSアプリをインストールするためには、iOSデバイスのUUIDがプロビジョニングファイルに登録されている必要があります。</li>
					<li>Over-The-Airインストール機能を利用するには、<?= $this->config->item( 'home_title' ) ?> にHTTPS通信でアクセスできる必要があります。</li>
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
				<h5 class="modal-title" id="exampleModalLabel">ipaファイルのアップロード</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="uploadform">
				<div class="modal-body">
					<div class="m-2">
						<label class="col-form-label">ipaファイル</label>
						<span class="badge badge-danger">必須</span>
						<input id="upload_ipa_path" name="upload_ipa_path" class="py-5 form-control" type="file"/>
						<div class="m-1 p-2 alert alert-info"><i class="fas fa-info-circle"></i> ↑ここにipaファイルをドロップすることもできます。</div>
						<hr/>
						<label class="col-form-label">バージョン</label>
						<span class="badge badge-danger">必須</span>
						<input type="text" class="form-control" id="upload_app_ver" name="upload_app_ver" placeholder="X.XX.XX">
						<div class="m-1 p-2 alert alert-info"><i class="fas fa-info-circle"></i> Over-The-Air配信用の manifest.plist は自動作成されます。</div>
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
			formData.append( 'app_file', $( '#upload_ipa_path' ).prop( 'files' )[0] );
			formData.append( 'app_version', $( '#upload_app_ver' ).val() );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "apps/{$platform}/{$environment}/app/upload" ) ?>",
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
				alert( "エラー: ipaファイルのアップロードに失敗しました。" );
			}).always( function() {
				$( '#upload_btn_text' ).text( "アップロード" );
				$( '#upload_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-upload" );
				$( '#upload_btn' ).prop( 'disabled', false );
			});
        });

		$( '[id ^= delete_ipa_]' ).on( 'click', function() {
            var dstid = $( this ).attr( 'id' ).replace( "delete_ipa_", "" );
			$( this ).prop( 'disabled', true );
			$( '#delete_icon_' + dstid ).removeClass( "fa-trash-alt" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "配布ID: #" + dstid + " をビルド一覧から削除してよろしいですか？" ) ) {
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
                    $( '#delete_ipa_' + dstid ).prop( 'disabled', false );
                });
            }
            else {
                $( '#delete_icon_' + dstid ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                $( '#delete_ipa_' + dstid ).prop( 'disabled', false );
            }
        });
		<?php endif ?>
    });
</script>