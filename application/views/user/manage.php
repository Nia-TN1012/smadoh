<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#4B64A1;color:#fff"><i class="fas fa-users"></i> ユーザー管理</h1>
    <br class="container mt-5" />
    <h2><i class="fas fa-list"></i> ユーザー一覧</h2>
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
                    &nbsp;<?= h( $row['id'] ) ?>
                </td>
                <td><?= h( $row['user_id'] ) ?></td>
                <td><?= h( $row['display_user_name'] ) ?></td>
                <td><?= h( $row['email'] ) ?></td>
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
                <td><?= h( $row['register_time'] ) ?></td>
                <?php if( UserModel::is_admin() ): ?>
                <td>
                    <?php if( $row['id'] != $_SESSION['login_user_data']['id'] ): ?>
                    <button type="button" class="btn btn-sm btn-danger" name="<?= $row['display_user_name'] ?>" id="remove_<?= $row['id'] ?>"><i class="fas fa-trash-alt"></i></button>
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
                <div class="card-header">
                    <i class="fas fa-shield-alt"></i> ロールの権限範囲
                </div>
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
                                <td><i class="fas fa-check"></i></td>
                                <td><i class="fas fa-check"></i></td>
                                <td><i class="fas fa-check"></i></td>
                            </tr>
                            <tr>
                                <th>アプリの追加・削除</th>
                                <td><i class="fas fa-check"></i></td>
                                <td><i class="fas fa-check"></i></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>ユーザーの管理</th>
                                <td><i class="fas fa-check"></i></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>REST API</th>
                                <td><i class="fas fa-check"></i></td>
                                <td><i class="fas fa-check"></i></td>
                                <td><i class="fas fa-check"></i></td>
                            </tr>
                        </tbody>
                    </table>
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
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/set-role" ) ?>",
                dataType: "json",
                data: { 
                    id: $( this ).attr( 'id' ).replace( "role_select_", "" ),
                    role: $( this ).val()
                },
                success: function( response ){
                    $( '#response_panel' ).html( response.message );
                    if( response.error ) {
                        $( '#response_panel' ).removeClass( "alert-success" ).addClass( "alert-danger" ).show();
                    }
                    else {
                        $( '#response_panel' ).removeClass( "alert-danger" ).addClass( "alert-success" ).show();
                    }
                    window.setTimeout( function() { $( '#response_panel' ).hide(); }, 5000 );
                }
            });
        });

        // 削除ボタン
        $( '[id ^= remove_]' ).on( 'click', function() {
            var user_name = $( this ).attr( 'name' ).replace( "remove_", "" );
            if( confirm( "ユーザー: '" + user_name + "'を削除してよろしいですか？" ) ) {
                $.ajax({
                    type: "POST",
                    url: "<?= site_url( "user/remove" ) ?>",
                    dataType: "json",
                    data: { 
                        id: $( this ).attr( 'id' ).replace( "remove_", "" )
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

        $( '#response_panel' ).hide();
    });
</script>