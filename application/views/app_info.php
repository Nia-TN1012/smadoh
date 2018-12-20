<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid">
	<h1 class="p-2 text-white" style="background-color:#4B64A1"><i class="fas fa-info-circle"></i> システム・アプリ設定情報</h1>
	<br class="container mt-5" />
	<?php if( UserModel::is_admin() ): ?>
	<ul id="aboutTab" class="nav nav-tabs" role="tablist">
		<li class="nav-item">
            <a href="#appconfig" id="appconfig-tab" class="nav-link active" role="tab" data-toggle="tab" aria-controls="appconfig" aria-selected="true"><i class="fas fa-wrench"></i> アプリ設定（app_config）情報</a>
        </li>
        <li class="nav-item">
            <a href="#sysinfo" id="sysinfo-tab" class="nav-link" role="tab" data-toggle="tab" aria-controls="sysinfo" aria-selected="false"><i class="fas fa-server"></i> システム情報</a>
        </li>
    </ul>

    <div id="aboutTabContent" class="tab-content mt-3">
		<div id="appconfig" class="tab-pane active" role="tabpanel" aria-labelledby="appconfig-tab">
			<div class="card">
                <div class="card-header bg-info text-white">
					<i class="fas fa-wrench"></i> アプリ全体の設定
                </div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered">
						<tbody>
							<tr>
								<th>タイトル名</th>
								<td><?= $this->config->item( 'home_title' ) ?></td>
							</tr>
							<tr>
								<th>APIトークンのスロット数</th>
								<td><?= $this->config->item( 'token_slot_num' ) ?></td>
							</tr>
							<tr>
								<th>APIトークンの有効期限（単位: 月）</th>
								<td><?= $this->config->item( 'token_period' ) > 0 ? $this->config->item( 'token_period' ) : "無期限" ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>

			<div class="card">
                <div class="card-header bg-info text-white">
					<i class="fab fa-apple"></i> iOS版の設定
                </div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered">
						<tbody>
							<tr>
								<th>iOS版を使用する</th>
								<td>
									<?php if( $this->config->item( 'ios_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'ios_root_name' ) ?></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-sm table-striped table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>開発環境</th>
								<th>ステージング環境</th>
								<th>本番環境</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>使用する</th>
								<td>
									<?php if( $this->config->item( 'ios_use' ) && $this->config->item( 'ios_develop_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'ios_develop_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'ios_use' ) && $this->config->item( 'ios_staging_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'ios_staging_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'ios_use' ) && $this->config->item( 'ios_production_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'ios_production_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'ios_develop_name' ) ?></td>
								<td><?= $this->config->item( 'ios_staging_name' ) ?></td>
								<td><?= $this->config->item( 'ios_production_name' ) ?></td>
							</tr>
							<tr>
								<th>アプリの名前</th>
								<td><?= $this->config->item( 'ios_develop_app_name' ) ?></td>
								<td><?= $this->config->item( 'ios_staging_app_name' ) ?></td>
								<td><?= $this->config->item( 'ios_production_app_name' ) ?></td>
							</tr>
							<tr>
								<th>バンドルID</th>
								<td><?= $this->config->item( 'ios_develop_bundle_id' ) ?></td>
								<td><?= $this->config->item( 'ios_staging_bundle_id' ) ?></td>
								<td><?= $this->config->item( 'ios_production_bundle_id' ) ?></td>
							</tr>
							<tr>
								<th>アプリのパッケージ名</th>
								<td><?= $this->config->item( 'ios_develop_ipa_name' ) ?></td>
								<td><?= $this->config->item( 'ios_staging_ipa_name' ) ?></td>
								<td><?= $this->config->item( 'ios_production_ipa_name' ) ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
			<div class="card">
                <div class="card-header bg-info text-white">
					<i class="fab fa-android"></i> Android版の設定
                </div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered">
						<tbody>
							<tr>
								<th>Android版を使用する</th>
								<td>
									<?php if( $this->config->item( 'android_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'android_root_name' ) ?></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-sm table-striped table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>開発環境</th>
								<th>ステージング環境</th>
								<th>本番環境</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>使用する</th>
								<td>
									<?php if( $this->config->item( 'android_use' ) && $this->config->item( 'android_develop_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'android_develop_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'android_use' ) && $this->config->item( 'android_staging_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'android_staging_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'android_use' ) && $this->config->item( 'android_production_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'android_production_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'android_develop_name' ) ?></td>
								<td><?= $this->config->item( 'android_staging_name' ) ?></td>
								<td><?= $this->config->item( 'android_production_name' ) ?></td>
							</tr>
							<tr>
								<th>アプリの名前</th>
								<td><?= $this->config->item( 'android_develop_app_name' ) ?></td>
								<td><?= $this->config->item( 'android_staging_app_name' ) ?></td>
								<td><?= $this->config->item( 'android_production_app_name' ) ?></td>
							</tr>
							<tr>
								<th>アプリのパッケージ名</th>
								<td><?= $this->config->item( 'android_develop_apk_name' ) ?></td>
								<td><?= $this->config->item( 'android_staging_apk_name' ) ?></td>
								<td><?= $this->config->item( 'android_production_apk_name' ) ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
			<div class="card">
                <div class="card-header bg-info text-white">
					<i class="fab fa-windows"></i> UWP版の設定
                </div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered">
						<tbody>
							<tr>
								<th>UWP版を使用する</th>
								<td>
									<?php if( $this->config->item( 'uwp_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'uwp_root_name' ) ?></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-sm table-striped table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>開発環境</th>
								<th>ステージング環境</th>
								<th>本番環境</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>使用する</th>
								<td>
									<?php if( $this->config->item( 'uwp_use' ) && $this->config->item( 'uwp_develop_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'uwp_develop_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'uwp_use' ) && $this->config->item( 'uwp_staging_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'uwp_staging_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
								<td>
									<?php if( $this->config->item( 'uwp_use' ) && $this->config->item( 'uwp_production_use' ) ): ?>
									<span class="badge badge-success">ENABLED</span>
									<?php elseif( $this->config->item( 'uwp_production_use' ) ): ?>
									<span class="badge badge-warning">DISABLED by Global</span>
									<?php else: ?>
									<span class="badge badge-danger">DISABLED</span>
									<?php endif ?>
								</td>
							</tr>
							<tr>
								<th>表示名</th>
								<td><?= $this->config->item( 'uwp_develop_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_staging_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_production_name' ) ?></td>
							</tr>
							<tr>
								<th>アプリの名前</th>
								<td><?= $this->config->item( 'uwp_develop_app_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_staging_app_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_production_app_name' ) ?></td>
							</tr>
							<tr>
								<th>アプリのパッケージ名</th>
								<td><?= $this->config->item( 'uwp_develop_appx_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_staging_appx_name' ) ?></td>
								<td><?= $this->config->item( 'uwp_production_appx_name' ) ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
        </div>
        <div id="sysinfo" class="tab-pane" role="tabpanel" aria-labelledby="sysinfo-tab">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-server"></i> システム概要
                </div>
				<div class="card-body">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th><i class="fas fa-server"></i> OS</th>
								<td><?= php_uname( "s" )." ".php_uname( "r" )." ".php_uname( "v" )." ".php_uname( "m" ) ?></td>
							</tr>
							<tr>
								<th><i class="fab fa-php"></i> PHP</th>
								<td>PHP <?= phpversion() ?></td>
							</tr>
							<tr>
								<th><i class="fas fa-database"></i> データベース</th>
								<td><?= $this->db->platform()." ".$this->db->version() ?></td>
							</tr>
						</tbody>
					</table>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-expand-arrows-alt"></i> 
                    <a class="text-white" href="#collapsePHPInfo" role="tab" data-toggle="collapse" data-target="#collapsePHPInfo" aria-expanded="false" aria-controls="collapsePHPInfo">
                        <i class="fab fa-php"></i> PHPの情報
                    </a>
                </div>
                <div class="collapse" id="collapsePHPInfo">
                    <div class="card-body">
						<style type='text/css'>
							#phpinfo {}
							#phpinfo pre {margin: 0; font-family: monospace;}
							#phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
							#phpinfo a:hover {text-decoration: underline;}
							#phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
							#phpinfo .center {text-align: center;}
							#phpinfo .center table {margin: 1em auto; text-align: left;}
							#phpinfo .center th {text-align: center !important;}
							#phpinfo td, #phpinfo th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
							#phpinfo h1 {font-size: 150%;}
							#phpinfo h2 {font-size: 125%;}
							#phpinfo .p {text-align: left;}
							#phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
							#phpinfo .h {background-color: #99c; font-weight: bold;}
							#phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
							#phpinfo .v i {color: #999;}
							#phpinfo img {float: right; border: 0;}
							#phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
						</style>
                        <div id="phpinfo">
                            <?php
                                ob_start();
                                phpinfo();
                                $phpinfo = ob_get_contents();
                                ob_end_clean();
                                $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
                                echo $phpinfo;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php else: ?>
	<div class="alert alert-danger" role="alert"><i class="fas fa-times"></i> エラー: このページを表示する権限がありません。</div>
	<?php endif ?>
</div>