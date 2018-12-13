<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#4B64A1;color:#fff"><i class="fas fa-code"></i> APIトークン管理</h1>
    <br class="container mt-5" />
    <h2><i class="fas fa-list"></i> APIトークン一覧</h2>
    <div class="float-right">
        <button type="button" class="m-1 btn btn-primary" id="create_token"><i id="create_token_btn_icon" class="fas fa-plus"></i> <span id="create_token_btn_text">新しいトークン作成<span></button>
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
                <td><?= h( $row['token'] ) ?></td>
                <td><?= h( $row['create_time'] ) ?></td>
                <td><?= h( $row['expire_time'] ) ?></td>
                <td>
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
            <i class="fas fa-info-circle"></i> APIトークンでできること
        </div>
        <div class="card-body">
            <ul>
                <li>APIトークンを利用することで、REST APIを使って、アプリの登録などを行うことができます。</li>
                <li>APIトークンの有効期限は、生成から12ヶ月です。有効期限切れのAPIトークンは使用できません。（認証エラーになります。）</li>
                <li>APIトークンは1ユーザーにつき、3つまで作成できます。4つ目以降を作成したい場合、不要なAPIトークンを削除してから行ってください。</li>
                <li>作成したAPIトークンは盗難されないようにご注意ください。</li>
            </ul>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>操作</th>
                        <th>メソッド</th>
                        <th>リクエストURL</th>
                        <th>URLパラメータ / リクエストボディ</th>
                        <th>必要な権限</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>APIトークンに紐づいたユーザー情報の取得</th>
                        <td>GET</td>
                        <td><?= site_url( 'api/v1/user/info' ) ?></td>
                        <td>なし</td>
                        <td>ユーザー以上</td>
                    </tr>
                    <tr>
                        <th>ユーザーリスト</th>
                        <td>GET</td>
                        <td><?= site_url( 'api/v1/user/list' ) ?></td>
                        <td>
                            <ul>
                                <li>num = 最大取得数（デフォルトは10）</li>
                                <li>offset = オフセット（デフォルトは0）</li>
                            </ul>
                        </td>
                        <td>全権限</td>
                    </tr>
                    <tr>
                        <th>アプリのリスト</th>
                        <td>GET</td>
                        <td><?= site_url( 'api/v1/app/${platform}/${environment}/list' ) ?></td>
                        <td>
                            <ul>
                                <li>num = 最大取得数（デフォルトは10）</li>
                                <li>offset = オフセット（デフォルトは0）</li>
                            </ul>
                        </td>
                        <td>全権限</td>
                    </tr>
                    <tr>
                        <th>アプリの登録（iOS版）</th>
                        <td>POST</td>
                        <td><?= site_url( 'api/v1/app/ios/${environment}/register' ) ?></td>
                        <td>
                            <ul>
                                <li>ipa_file: アップロードするipaファイル</li>
                                <li>app_ver: アプリのバージョン（X.XX.XX）</li>
                            </ul>
                        </td>
                        <td>アプリ管理者以上</td>
                    </tr>
                    <tr>
                        <th>アプリの登録（Android版）</th>
                        <td>POST</td>
                        <td><?= site_url( 'api/v1/app/android/${environment}/register' ) ?></td>
                        <td>
                            <ul>
                                <li>apk_file: アップロードするapkファイル</li>
                                <li>app_ver: アプリのバージョン（X.XX.XX）</li>
                            </ul>
                        </td>
                        <td>アプリ管理者以上</td>
                    </tr>
                    <tr>
                        <th>アプリの登録（UWP版）</th>
                        <td>POST</td>
                        <td><?= site_url( 'api/v1/app/uwp/${environment}/register' ) ?></td>
                        <td>
                            <ul>
                                <li>appx_file: アップロードするappxbundleファイル</li>
                                <li>app_ver: アプリのバージョン（X.XX.XX）</li>
                            </ul>
                        </td>
                        <td>アプリ管理者以上</td>
                    </tr>
                    <tr>
                        <th>サイドロード用証明書情報の取得（UWP版）</th>
                        <td>GET</td>
                        <td><?= site_url( 'api/v1/app/uwp/${environment}/current-cert' ) ?></td>
                        <td>
                            なし
                        </td>
                        <td>全権限</td>
                    </tr>
                    <tr>
                        <th>サイドロード用証明書の更新（UWP版）</th>
                        <td>POST</td>
                        <td><?= site_url( 'api/v1/app/uwp/${environment}/update-cert' ) ?></td>
                        <td>
                            <ul>
                                <li>cert_file: アップロードするサイドロード用証明書ファイル（.cer）</li>
                            </ul>
                        </td>
                        <td>アプリ管理者以上</td>
                    </tr>
                </tbody>
            </table>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> 備考
                </div>
                <div class="card-body">
                    <ul>
                        <li><code>${platform}</code>: プラットフォーム（ios / android / uwp）</li>
                        <li><code>${environment}</code>: 環境（develop / staging / production）</li>
                    </ul>
                </div>
            <div>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="fas fa-code"></i> <?= $this->config->item( 'ios_develop_name' ) ?> に、ipaファイルをアップロード
                </div>
                <div class="card-body">
                    <code><?= h( '$ curl -X POST -H "token: ${APIトークン}" -H "app_ver: 1.0.0" -F "ipa_file=@test.ipa;type=application/octet-stream" '.site_url( 'api/v1/apps/ios/develop/register' ) ) ?></code>
                </div>
            <div>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="fas fa-code"></i> ステータスコード（<code>status_code</code>）
                </div>
                <div class="card-body">
                    正常に処理をした場合は <b>200</b>、リクエストに誤りがある場合は <b>400</b>、認証エラーの場合は <b>401</b>、必要な権限がない場合 <b>403</b>、サーバーエラーの場合は <b>500</b> が返ります。
                </div>
            <div>
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