<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid">
	<h1 class="p-2 text-white" style="background-color:#4B64A1"><i class="fas fa-info-circle"></i> アプリ情報</h1>
	<br class="container mt-5" />
	<ul id="aboutTab" class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a href="#appinfo" id="appinfo-tab" class="nav-link active" role="tab" data-toggle="tab" aria-controls="appinfo" aria-selected="true"><?= $this->config->item( 'app_short_name' ) ?>について</a>
        </li>
        <li class="nav-item">
            <a href="#sysinfo" id="sysinfo-tab" class="nav-link" role="tab" data-toggle="tab" aria-controls="sysinfo" aria-selected="false">システム情報</a>
        </li>
        <li class="nav-item">
            <a href="#appconfig" id="appconfig-tab" class="nav-link" role="tab" data-toggle="tab" aria-controls="appconfig" aria-selected="false">アプリ設定情報</a>
        </li>
    </ul>

    <div id="aboutTabContent" class="tab-content mt-3">
        <div id="appinfo" class="tab-pane active" role="tabpanel" aria-labelledby="appinfo-tab">
			<div class="card">
                <div class="card-header bg-info text-white">
					<i class="fab fa-rev"></i> バージョン情報
                </div>
				<div class="card-body">
				</div>
			</div>
			<div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-expand-arrows-alt"></i> 
                    <a class="text-white" href="#collapseLibInfo" role="tab" data-toggle="collapse" data-target="#collapseLibInfo" aria-expanded="false" aria-controls="collapseLibInfo">
						<i class="fas fa-plug"></i> ライブラリ情報
                    </a>
                </div>
                <div class="collapse" id="collapseLibInfo">
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
        <div id="sysinfo" class="tab-pane" role="tabpanel" aria-labelledby="sysinfo-tab">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-expand-arrows-alt"></i> 
                    <a class="text-white" href="#collapseSysInfo" role="tab" data-toggle="collapse" data-target="#collapseSysInfo" aria-expanded="false" aria-controls="collapseSysInfo">
                        <i class="fas fa-server"></i> システム概要
                    </a>
                </div>
                <div class="collapse" id="collapseSysInfo">
                    <div class="card-body">
                    </div>
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
                            #phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
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
        <div id="appconfig" class="tab-pane" role="tabpanel" aria-labelledby="appconfig-tab">
            C
        </div>
    </div>
</div>