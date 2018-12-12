<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#FF8F22;color:#fff"><i class="fab fa-apple"></i> <?= $page_title ?></h1>
	<div class="m-2">
		<?php if( !is_null( $latest_app_data ) ): ?>
		<div class="p-3 col-sm-12 col-lg-9 col-xl-7 border rounded shadow-sm">
			<div class="row">
				<div class="col-sm-11 col-md">
					<h2><i class="far fa-star"></i> 最新のビルド</h2>
					<div class="p-2">
						配布ID: #<?= h( $latest_app_data['distrib_id'] ) ?><br/>
						バージョン: <?= h( $latest_app_data['app_version'] ) ?><br/>
						アップロード日: <?= h( $latest_app_data['upload_time'] ) ?>
					</div>
				</div>
				<div class="col-sm-11 col-md row text-center">
					<div class="col">
						<a class="text-primary" href="<?= $latest_app_data['ipa_link'] ?>" download>
							<i class="fab fa-itunes fa-5x"></i>
							<p><?= $this->config->item( $platform.'_'.$environment.'_ipa_name' ) ?>.ipaを<br/>ダウンロード</p>
						</a>
					</div>
					<div class="col">
						<a class="text-primary" href="<?= $latest_app_data['ota_plist_link'] ?>" download>
							<i class="fas fa-plane fa-5x"></i>
							<p>Over-The-Air<br/>インストール</p>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php endif ?>
		<br class="container mt-5" />

		<h2><i class="fas fa-list"></i> ビルド一覧</h2>
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
					<td><?= h( $row['distrib_id'] ) ?></td>
					<td><?= h( $row['app_version'] ) ?></td>
					<td><a href="<?= $row['ipa_link'] ?>" download><i class="fab fa-itunes"></i> <?= $this->config->item( $platform.'_'.$environment.'_ipa_name' ) ?>.ipa</a></td>
					<td><a href="<?= $row['ota_plist_link'] ?>" download><i class="fas fa-plane"></i> Over-The-Airインストール</a></td>
					<td><?= h( $row['upload_time'] ) ?></td>
					<?php if( UserModel::is_manager() ): ?>
					<td><button type="button" class="btn btn-danger" id="delete_ipa_<?= h( $row['distrib_id'] ) ?>"><i class="fas fa-trash-alt"></i></button></td>
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
				</ul>
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
					<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> アップロード</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> 閉じる</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=base_url()?>js/qrcode/jquery.qrcode.min.js"></script>
<script>
	function copyToClipboard() {
		var copyTarget = document.getElementById( "thisPageURL" );
		copyTarget.select();
		document.execCommand( "Copy" );
	}

	$( document).ready( function() {
		$( '#qrcode' ).qrcode( { width: 120, height: 120, text: "<?= site_url( "apps/{$platform}/{$environment}" ) ?>" } );

		<?php if( UserModel::is_manager() ): ?>
		$( '#uploadform' ).submit( function( e ) {
            e.preventDefault();
			var formData = new FormData();
			formData.append( 'ipa_file', $( '#upload_ipa_path' ).prop( 'files' )[0] );
			formData.append( 'app_version', $( '#upload_app_ver' ).val() );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "apps/{$platform}/{$environment}/upload-ipa" ) ?>",
                dataType: "json",
                data: formData,
				processData: false,
				contentType: false,
                success: function( response ){
					alert( response.message );
                    if( !response.error ) {
                        window.location.reload();
                    }
                }
            });
        });

		$( '[id ^= delete_ipa_]' ).on( 'click', function() {
            var dstid = $( this ).attr( 'id' ).replace( "delete_ipa_", "" )
            if( confirm( "配布ID: #" + dstid + " をビルド一覧から削除してよろしいですか？" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "apps/{$platform}/{$environment}/delete-ipa" ) ?>",
                    dataType: "json",
                    data: { 
                        id: dstid
                    },
                    success: function( response ){
                        alert( response.message );
                        if( !response.error ) {
                            window.location.reload( true );
                        }
                    }
                });
            }
        });
		<?php endif ?>
    });
</script>