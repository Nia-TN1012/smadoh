<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2 text-white" style="background-color:#4B64A1"><i class="fas fa-users"></i> ユーザー管理</h1>
    <br class="container mt-5" />
    <h2><i class="fas fa-list"></i> ユーザー一覧 <span class="mx-2 px-1 text-white" style="background-color:#4B64A1"><?= $user_num ?></span></h2>
    <?php if( UserModel::is_admin() ): ?>
    <div id="response_panel" class="alert alert-default" role="alert"></div>
    <div class="float-right">
        <button type="button" class="m-1 btn btn-primary" onClick="window.location.href='/user/new';"><i class="fas fa-user-plus"></i> ユーザー作成</button>
    </div>
    <?php endif ?>
    <table class="table table-hover shadow-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>ユーザーID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>ロール</th>
                <th>登録日</th>
                <?php if( UserModel::is_admin() ) { ?>
                <th>削除</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if( isset( $user_list ) ): ?>
            <?php foreach( $user_list as $row ): ?>
            <tr class="<?= $row['id'] === $_SESSION['login_user_data']['id'] ? "table-info" : "" ?>">
                <td>
                    <img src="https://www.gravatar.com/avatar/<?= md5( strtolower( trim( !empty( $row['email'] ) ? $row['email'] : $row['user_id']."-".$this->config->item( 'home_title' ) ) ) ) ?>?d=identicon&s=24" />
                    &nbsp;<span class="align-middle"><?= h( $row['id'] ) ?></span>
                </td>
                <td class="align-middle"><?= h( $row['user_id'] ) ?></td>
                <td class="align-middle"><?= h( $row['display_user_name'] ) ?></td>
                <td class="align-middle"><?= h( $row['email'] ) ?></td>
                <td>
                    <?php if( UserModel::is_admin() && $row['id'] != $_SESSION['login_user_data']['id'] ): ?>
                    <select id="role_select_<?= $row['id'] ?>" name="role_select_<?= $row['id'] ?>" class="form-control form-control-sm">
                        <option value="1" <?= $row['role'] == UserModel::ROLE_ADMIN ? "selected" : "" ?>>システム管理者</option>
                        <option value="2" <?= $row['role'] == UserModel::ROLE_MANAGER ? "selected" : "" ?>>アプリ管理者</option>
                        <option value="3" <?= $row['role'] == UserModel::ROLE_USER ? "selected" : "" ?>>ユーザー</option>
                    </select>
                    <?php else: ?>
                    <?= h( $row['role_name'] ) ?>
                    <?php endif ?>
                </td>
                <td class="align-middle"><?= h( $row['register_time'] ) ?></td>
                <?php if( UserModel::is_admin() ): ?>
                <td>
                    <?php if( $row['id'] != $_SESSION['login_user_data']['id'] ): ?>
                    <button type="button" class="btn btn-sm btn-danger" name="<?= $row['display_user_name'] ?>" id="remove_btn_<?= $row['id'] ?>"><i id="remove_icon_<?= $row['id'] ?>" class="fas fa-trash-alt"></i></button>
                    <?php endif ?>
                </td>
                <?php endif ?>
            <?php endforeach ?>
            </tr>
            <?php endif ?>
        </tbody>
    </table>
    <?= $this->pagination->create_links(); ?>

    <?php if( UserModel::is_admin() ): ?>
    <br class="container mt-5" />
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> 備考
        </div>
        <div class="card-body">
            <ul>
                <li>ロール列のドロップダウンで、他のユーザーの権限を変更することができます。</li>
                <li>他のユーザーを削除する時は、ゴミ箱ボタンを押します。</li>
            </ul>
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#collapseRole" aria-expanded="false" aria-controls="collapseRole">
                    <i class="fas fa-expand-arrows-alt"></i> <i class="fas fa-shield-alt"></i> ロールの権限範囲
                </div>
                <div class="collapse" id="collapseRole">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>操作</th>
                                    <th>システム管理者</th>
                                    <th>アプリ管理者</th>
                                    <th>ユーザー</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>アプリリスト閲覧、ダウンロード</th>
                                    <td><i class="far fa-circle"></i></td>
                                    <td><i class="far fa-circle"></i></td>
                                    <td><i class="far fa-circle"></i></td>
                                </tr>
                                <tr>
                                    <th>アプリの追加・削除</th>
                                    <td><i class="far fa-circle"></i></td>
                                    <td><i class="far fa-circle"></i></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>（UWP版のみ）証明書の管理</th>
                                    <td><i class="far fa-circle"></i></td>
                                    <td><i class="far fa-circle"></i></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>ユーザーの管理</th>
                                    <td><i class="far fa-circle"></i></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th><a href="/user/token">REST API</a></th>
                                    <td><i class="far fa-circle"></i>（全て対応）</td>
                                    <td><i class="far fa-circle"></i>（全て対応）</td>
                                    <td><i class="far fa-circle"></i>（一部対応）</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <div>
    <?php endif ?>
</div>

<script type="text/javascript">
    $( document).ready( function() {

        <?php if( UserModel::is_admin() ): ?>
        // 権限変更イベント
        $( '[id ^= role_select_]' ).change( function() {
            var user_id = $( this ).attr( 'id' ).replace( "role_select_", "" );
            $( this ).prop( 'disabled', true );
            $( '#remove_btn_' + user_id ).prop( 'disabled', true );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/set-role" ) ?>",
                dataType: "json",
                data: { 
                    id: user_id,
                    role: $( this ).val()
                },
            }).done( function( response ) {
                $( '#response_panel' ).html( response.message );
                if( response.error ) {
                    $( '#response_panel' ).removeClass( "alert-success" ).addClass( "alert-danger" ).show();
                }
                else {
                    $( '#response_panel' ).removeClass( "alert-danger" ).addClass( "alert-success" ).show();
                }
                window.setTimeout( function() { $( '#response_panel' ).hide(); }, 5000 );
            }).fail( function( response ) {
                $( '#response_panel' ).html( "エラー: ユーザーの権限変更に失敗しました。" );
                $( '#response_panel' ).removeClass( "alert-success" ).addClass( "alert-danger" ).show();
                window.setTimeout( function() { $( '#response_panel' ).hide(); }, 5000 );
            }).always( function() {
                $( '#remove_btn_' + user_id ).prop( 'disabled', false );
                $( '#role_select_' + user_id ).prop( 'disabled', false );
            });
        });

        // 削除ボタン
        $( '[id ^= remove_btn_]' ).on( 'click', function() {
            var user_id = $( this ).attr( 'id' ).replace( "remove_btn_", "" );
            var user_name = $( this ).attr( 'name' );
            $( this ).prop( 'disabled', true );
            $( '#role_select_' + user_id ).prop( 'disabled', true );
			$( '#remove_icon_' + user_id ).removeClass( "fa-trash-alt" ).addClass( "fa-spinner fa-spin" );
            if( confirm( "ユーザー: '" + user_name + "'を削除してよろしいですか？" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "user/remove" ) ?>",
                    dataType: "json",
                    data: { 
                        id: user_id
                    }
                }).done( function( response ){
                    alert( response.message );
                    if( !response.error ) {
                        window.location.reload( true );
                    }
                }).fail( function( response ) {
					alert( "エラー: ユーザー: '" + user_name + "' の削除に失敗しました。" );
				}).always( function() {
                    $( '#remove_icon_' + user_id ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                    $( '#role_select_' + user_id ).prop( 'disabled', false );
                    $( '#remove_btn_' + user_id ).prop( 'disabled', false );
                });
            }
            else {
                $( '#remove_icon_' + user_id ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-trash-alt" );
                $( '#role_select_' + user_id ).prop( 'disabled', false );
                $( '#remove_btn_' + user_id ).prop( 'disabled', false );
            }
        });
        <?php endif ?>

        $( '#response_panel' ).hide();
    });
</script>