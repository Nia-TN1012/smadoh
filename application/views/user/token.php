<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#4B64A1;color:#fff"><i class="fas fa-code"></i> APIトークン管理</h1>
    <br class="container mt-5" />
    <h2><i class="fas fa-list"></i> APIトークン一覧</h2>
    <div class="float-right">
        <button type="button" class="m-1 btn btn-primary" id="create_token"><i id="create_token_btn_icon" class="fas fa-plus"></i> <span id="create_token_btn_text">新しいAPIトークン発行<span></button>
    </div>
    <table class="table table-hover shadow-sm">
        <thead>
            <tr>
                <th>APIトークン</th>
                <th>生成日</th>
                <th>有効期限</th>
                <th>ステータス</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            <?php if( isset( $user_token_list ) ): ?>
            <?php foreach( $user_token_list as $row ): ?>
            <tr>
                <td class="align-middle"><?= h( $row['token'] ) ?></td>
                <td class="align-middle"><?= h( $row['create_time'] ) ?></td>
                <td class="align-middle"><?= h( $row['expire_time'] ) ?></td>
                <td class="align-middle">
                    <?php switch( $row['status'] ) {
                        case UserTokenModel::API_TOKEN_AVAILABLE:
                            echo '<sapn class="badge badge-success"><i class="far fa-circle"></i> 利用可能</span>';
                            break;
                        case UserTokenModel::API_TOKEN_EXPIRED:
                            echo '<sapn class="badge badge-danger"><i class="fas fa-times"></i> 有効期限切れ</span>';
                            break;
                    } ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" id="delete_btn_<?= h( $row['token'] ) ?>"><i id="delete_icon_<?= h( $row['token'] ) ?>" class="fas fa-trash-alt"></i></button>
                </td>
            <?php endforeach ?>
            </tr>
            <?php endif ?>
        </tbody>
    </table>

    <br class="container mt-5" />
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> 使い方
        </div>
        <div class="card-body">
            <ul>
                <li>APIトークンを利用することで、REST APIを使って、アプリデータの登録などを行うことができます。</li>
                <?php if( $this->config->item( 'token_period' ) > 0 ): ?>
                <li>APIトークンの有効期限は、発行から<?= $this->config->item( 'token_period' ) ?>ヶ月です。有効期限切れのAPIトークンは使用できません。（認証エラーになります。）</li>
                <?php endif ?>
                <li>APIトークンは1ユーザーにつき、<?= $this->config->item( 'token_slot_num' ) ?>個まで発行できます。<?= $this->config->item( 'token_slot_num' ) + 1 ?>個目以降を発行したい場合、不要なAPIトークンを削除してから行ってください。</li>
                <li>発行したAPIトークンは盗難されないようにご注意ください。</li>
            </ul>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> 利用可能なREST API
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header bg-light">
                            <i class="fas fa-expand-arrows-alt"></i> <span class="badge badge-info">GET</span> 
                            <a class="text-body" href="#collapseUserInfo" role="tab" data-toggle="collapse" data-target="#collapseUserInfo" aria-expanded="false" aria-controls="collapseUserInfo">
                                <b>/user/info</b>
                            </a>
                            <div class="float-right">APIトークンに紐づいたユーザーデータを取得します。</div>
                        </div>
                        <div class="collapse" id="collapseUserInfo">
                            <div class="card-body">
                                リクエスト
                                <pre class="bg-dark text-white p-2">curl -X GET -H "token: ${APIトークン}" <?= site_url( 'api/v1/user/info' ) ?></pre>
                                レスポンス
                                <pre class="bg-dark text-white p-2">{
    "status_code": 200,
    "response": {
        "id": "1",
        "user_id": "admin",
        "display_user_name": "Admin",
        "email": "admin@example.com",
        "role": "1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-light">
                            <i class="fas fa-expand-arrows-alt"></i> <span class="badge badge-info">GET</span> 
                            <a class="text-body" href="#collapseUserList" role="tab" data-toggle="collapse" data-target="#collapseUserList" aria-expanded="false" aria-controls="collapseUserList">
                                <b>/user/list</b>
                            </a>
                            <div class="float-right"><?= $this->config->item( 'home_title' ) ?>に登録されているユーザーリストを取得します。</div>
                        </div>
                        <div class="collapse" id="collapseUserList">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>パラメーター</th>
                                            <th>型</th>
                                            <th>概要</th>
                                            <th>備考</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>num</th>
                                            <td><span class="badge badge-info">Integer</span></td>
                                            <td>取得する人数</td>
                                            <td>未指定の場合のデフォルト値は 10 です。</td>
                                        </tr>
                                        <tr>
                                            <th>offset</th>
                                            <td><span class="badge badge-info">Integer</span></td>
                                            <td>オフセット値</td>
                                            <td>未指定の場合のデフォルト値は 0 です。</td>
                                        </tr>
                                    </tbody>
                                </table>
                                リクエスト
                                <pre class="bg-dark text-white p-2">curl -X GET -H "token: ${APIトークン}" <?= site_url( 'api/v1/user/list?num=10&offset=0' ) ?></pre>
                                レスポンス
                                <pre class="bg-dark text-white p-2">{
    "status_code": 200,
    "response": [
        {
            "id": "1",
            "user_id": "admin",
            "display_user_name": "Admin",
            "email": "admin@example.com",
            "role": "1",
            "register_time": "2018-12-12 21:44:19"
        },
        {
            "id": "4",
            "user_id": "user",
            "display_user_name": "ユーザー",
            "email": "",
            "role": "3",
            "register_time": "2018-12-13 20:50:13"
        }
    ],
}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-light">
							<i class="fas fa-expand-arrows-alt"></i> <span class="badge badge-info">GET</span> 
                            <a class="text-body" href="#collapseApplist" role="tab" data-toggle="collapse" data-target="#collapseApplist" aria-expanded="false" aria-controls="collapseApplist">
                                <b>/apps/{platform}/{environment}/list</b>
                            </a>
                            <div class="float-right">プラットフォームと環境を指定して、<?= $this->config->item( 'home_title' ) ?>に登録されているアプリデータリストを取得します。</div>
                        </div>
                        <div class="collapse" id="collapseApplist">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>パラメーター</th>
                                            <th>型</th>
                                            <th>概要</th>
                                            <th>備考</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{platform}</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>プラットフォーム（ios, android, uwp）</td>
                                            <td>app_configで指定したプラットフォームが無効化されている場合、status_codeは404となります。</td>
                                        </tr>
                                        <tr>
                                            <th>{environment}</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>環境（develop, staging, production）</td>
                                            <td>app_configで指定した環境が無効化されている場合、status_codeは404となります。</td>
                                        </tr>
                                        <tr>
                                            <th>num</th>
                                            <td><span class="badge badge-info">Integer</span></td>
                                            <td>取得する人数</td>
                                            <td>未指定の場合のデフォルト値は 10 です。</td>
                                        </tr>
                                        <tr>
                                            <th>offset</th>
                                            <td><span class="badge badge-info">Integer</span></td>
                                            <td>オフセット値</td>
                                            <td>未指定の場合のデフォルト値は 0 です。</td>
                                        </tr>
                                    </tbody>
                                </table>
                                リクエスト
                                <pre class="bg-dark text-white p-2">curl -X GET -H "token: ${APIトークン}" <?= site_url( 'api/v1/apps/ios/develop/list?num=10&offset=0' ) ?></pre>
                                レスポンス
                                <pre class="bg-dark text-white p-2">{
    "status_code": 200,
    "response": [
        {
            "distrib_id": "15",
            "app_version": "1.0.15",
            "dir_hash": "0000000000000000000000000000000000000000000000000000000000000015",
            "upload_time": "2018-12-13 20:34:10"
        },
        {
            "distrib_id": "14",
            "app_version": "1.0.14",
            "dir_hash": "0000000000000000000000000000000000000000000000000000000000000014",
            "upload_time": "2018-12-13 14:40:54"
        },
        ...
        {
            "distrib_id": "6",
            "app_version": "1.0.6",
            "dir_hash": "0000000000000000000000000000000000000000000000000000000000000006",
            "upload_time": "2018-12-01 10:28:50"
        }
    ]
}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-light">
							<i class="fas fa-expand-arrows-alt"></i> <span class="badge badge-success">POST</span> 
                            <a class="text-body" href="#collapseAppRegister" role="tab" data-toggle="collapse" data-target="#collapseAppRegister" aria-expanded="false" aria-controls="collapseAppRegister">
                                <b>/apps/{platform}/{environment}/register</b>
                            </a>
                            <div class="float-right"><span class="badge badge-warning">アプリ管理者以上の権限必要</span> プラットフォームと環境を指定して、<?= $this->config->item( 'home_title' ) ?>にアプリパッケージをアップロードし、データを登録します。</div>
                        </div>
                        <div class="collapse" id="collapseAppRegister">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>パラメーター / リクエストボディ</th>
                                            <th>型</th>
                                            <th>概要</th>
                                            <th>備考</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{platform}</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>プラットフォーム（ios, android, uwp）</td>
                                            <td>app_configで指定したプラットフォームが無効化されている場合、status_codeは404となります。</td>
                                        </tr>
                                        <tr>
                                            <th>{environment}</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>環境（develop, staging, production）</td>
                                            <td>app_configで指定した環境が無効化されている場合、status_codeは404となります。</td>
                                        </tr>
                                        <tr>
                                            <th>app_ver</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>アプリのバージョン</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>app_file</th>
                                            <td><span class="badge badge-info">File</span> <span class="badge badge-danger">必須</span></td>
                                            <td>アップロードするアプリパッケージファイル</td>
                                            <td>
                                                <code>type</code>に指定するMIMEタイプは以下の通りです。
                                                <ul>
                                                    <li>iOS（ipaファイル）: <code>application/octet-stream</code></li>
                                                    <li>Android（apkファイル）: <code>application/vnd.android.package-archive</code></li>
                                                    <li>UWP（appxbundleファイル）: <code>application/appxbundle</code></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                リクエスト
                                <pre class="bg-dark text-white p-2">curl -X POST -H "token: ${APIトークン}" -H "app_ver: 1.0.0" -F "app_file=@<?= $this->config->item( 'ios_develop_ipa_name' ) ?>.ipa;type=application/octet-stream" <?= site_url( 'api/v1/apps/ios/develop/register' ) ?></pre>
                                レスポンス
                                <pre class="bg-dark text-white p-2">{
    "status_code": 200,
    "response": {
        "distrib_id": "1",
        "app_version": "1.0.0",
        "dir_hash": "0000000000000000000000000000000000000000000000000000000000000001",
        "upload_time": "2018-12-01 10:28:50"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-light">
							<i class="fas fa-expand-arrows-alt"></i> <span class="badge badge-success">POST</span> 
                            <a class="text-body" href="#collapseUWPUpdateCert" role="tab" data-toggle="collapse" data-target="#collapseUWPUpdateCert" aria-expanded="false" aria-controls="collapseUWPUpdateCert">
                                <b>/apps/uwp/certificate/update</b>
                            </a>
                            <div class="float-right"><span class="badge badge-warning">アプリ管理者以上の権限必要</span> 環境を指定して、UWP版のサイドロード用証明書を更新します。</div>
                        </div>
                        <div class="collapse" id="collapseUWPUpdateCert">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>リクエストボディ</th>
                                            <th>型</th>
                                            <th>概要</th>
                                            <th>備考</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>environment</th>
                                            <td><span class="badge badge-info">String</span> <span class="badge badge-danger">必須</span></td>
                                            <td>環境（develop, staging, production）</td>
                                            <td>app_configで指定した環境が無効化されている場合、status_codeは404となります。</td>
                                        </tr>
                                        <tr>
                                            <th>cert_file</th>
                                            <td><span class="badge badge-info">File</span> <span class="badge badge-danger">必須</span></td>
                                            <td>アップロードする証明書ファイル（.cer）</td>
                                            <td><code>type</code>に指定するMIMEタイプは<code>application/pkix-cert</code>です。</td>
                                        </tr>
                                        <tr>
                                            <th>cert_file</th>
                                            <td><span class="badge badge-info">String</span></td>
                                            <td>メモ</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                リクエスト
                                <pre class="bg-dark text-white p-2">curl -X POST -H "token: ${APIトークン}" -H "environment: develop" -H "memo: Develop cert" -F "cert_file=@uwp_develop_cert.cer;type=application/pkix-cert" <?= site_url( 'api/v1/apps/uwp/certificate/update' ) ?></pre>
                                レスポンス
                                <pre class="bg-dark text-white p-2">{
    "status_code": 200,
    "response": {
        "envronment": "develop",
        "hash_value": "0000000000000000000000000000000000000000000000000000000000000000",
        "memo": "Develop cert",
        "create_time": "2018-12-11 10:30:48",
        "expire_time": "2019-12-11 16:30:48",
        "upload_time": "2018-12-18 19:30:05"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                    <br class="container mt-5" />
                    <div class="card">
                        <div class="card-header bg-light">
							<i class="fas fa-expand-arrows-alt"></i> 
                            <a class="text-body" href="#collapseStatusCode" role="tab" data-toggle="collapse" data-target="#collapseStatusCode" aria-expanded="false" aria-controls="collapseStatusCode">
                                <i class="fas fa-code"></i> <b>ステータスコード</b>
                            </a>
                        </div>
                        <div class="collapse" id="collapseStatusCode">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><code>status_code</code></th>
                                            <th>概要</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>200</th>
                                            <td>OK: リクエストは正常に処理されました。</td>
                                        </tr>
                                        <tr>
                                            <th>400</th>
                                            <td>Bad Request: リクエストに誤りがあります。パラメーターやリクエストボディなどに必要な値がないか、無効な値が指定された時に返します。</td>
                                        </tr>
                                        <tr>
                                            <th>401</th>
                                            <td>Unauthorized: 認証エラーです。トークンが無効な時に返されます。</td>
                                        </tr>
                                        <tr>
                                            <th>403</th>
                                            <td>Frobidden: そのAPIを実行するのに必要な権限がありません。例えば <span class="badge badge-warning">アプリ管理者以上の権限必要</span> のバッジがあるAPIは「アプリ管理者」または「システム管理者」権限を持っている必要があります。</td>
                                        </tr>
                                        <tr>
                                            <th>404</th>
                                            <td>Not Found: 指定したエンドポイントが存在しない、もしくは、指定したプラットフォーム及び環境がapp_configによって無効化されています。</td>
                                        </tr>
                                        <tr>
                                            <th>500</th>
                                            <td>Internal Server Error: リクエストの処理中にサーバーエラーが発生しました。</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <div>
                </div>
            </div>
        </div>
    <div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        $( '#create_token' ).on( 'click', function() {
            $( '#create_token' ).prop( 'disabled', true );
			$( '#create_token_btn_icon' ).removeClass( "fa-plus" ).addClass( "fa-spinner fa-spin" );
			$( '#create_token_btn_text' ).text( "新しいトークンを作成中" );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/token/create" ) ?>",
                dataType: "json"
            }).done( function( response ){
                alert( response.message );
                if( !response.error ) {
                    window.location.reload( true );
                }
            }).fail( function( response ) {
				alert( "エラー: 新しいトークンの作成に失敗しました。" );
            }).always( function() {
                $( '#create_token_btn_text' ).text( "新しいトークン作成" );
				$( '#create_token_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-plus" );
				$( '#create_token' ).prop( 'disabled', false );
            });
        });

        // 削除ボタン
        $( '[id ^= delete_btn_]' ).on( 'click', function() {
            var selected_token = $( this ).attr( 'id' ).replace( "delete_btn_", "" );
            $( this ).prop( 'disabled', true );
			$( '#delete_icon_' + selected_token ).removeClass( "fa-trash-alt" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "トークンを削除してよろしいですか？" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "user/token/delete" ) ?>",
                    dataType: "json",
                    data: { 
                        token: selected_token
                    }
                }).done( function( response ) {
                    alert( response.message );
                    if( !response.error ) {
                        window.location.reload( true );
                    }
                }).fail( function( response ) {
					alert( "エラー: トークンの削除に失敗しました。" );
                }).always( function() {
                    $( '#delete_icon_' + selected_token ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                    $( '#delete_btn_' + selected_token ).prop( 'disabled', false );
                });
            }
            else {
                $( '#delete_icon_' + selected_token ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                $( '#delete_btn_' + selected_token ).prop( 'disabled', false );
            }
        });
    });
</script>