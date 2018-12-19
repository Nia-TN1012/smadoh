<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#4B64A1"><i class="fas fa-user-cog"></i> ユーザー設定</h1>
    <br class="container mt-5" />
    <?php if( $error ): ?>
        <div class="alert alert-danger" role="alert"><?= $message ?></div>
    <?php else: ?>
        <div class="p-5 border shadow-sm">
            <div id="response_panel" class="alert alert-danger" role="alert"></div>
            <div class="row">
                <div class="col-sm-7">
                    <form id="updateform">
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="login_user_id" class="col-form-label">ユーザーID</label>
                                <span class="badge badge-danger">必須</span>
                            </div>
                            <input type="text" class="col-sm-7 form-control" id="login_user_id" name="login_user_id" placeholder="User ID" value="<?= $user_data['user_id'] ?>">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="display_user_name" class="col-form-label">名前</label>
                                <span class="badge badge-danger">必須</span>
                            </div>
                            <input type="text" class="col-sm-7 form-control" id="display_user_name" name="display_user_name" placeholder="Display User Name" value="<?= $user_data['display_user_name'] ?>">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="old_login_pass" class="col-form-label">現在のパスワード</label>
                                <span class="badge badge-warning">パスワードを変更する時は必須</span>
                            </div>
                            <input type="password" class="col-sm-7 form-control" id="old_login_pass" name="old_login_pass" placeholder="Old Password">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="new_login_pass" class="col-form-label">新しいパスワード</label>
                                <span class="badge badge-warning">パスワードを変更する時は必須</span>
                            </div>
                            <input type="password" class="col-sm-7 form-control" id="new_login_pass" name="new_login_pass" placeholder="New Password">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="new_login_pass_cfm" class="col-form-label">新しいパスワード確認</label>
                                <span class="badge badge-warning">パスワードを変更する時は必須</span>
                            </div>
                            <input type="password" class="col-sm-7 form-control" id="new_login_pass_cfm" name="new_login_pass_cfm" placeholder="New Password (Confirm)">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <label for="email" class="col-form-label">Email</label>
                                <span class="badge badge-info">任意</span>
                            </div>
                            <input type="text" class="col-sm-7 form-control" id="email" name="email" placeholder="Email Address" value="<?= $user_data['email'] ?>">
                        </div>
                        <br class="container mt-5" />
                        <div>
                            <button type="submit" id="submit_btn" class="btn btn-primary"><i id="submit_btn_icon" class="fas fa-user-cog"></i> <span id="submit_btn_text">ユーザーデータ更新</span></button>
                            <button type="button" class="btn btn-secondary" onClick="window.location.href='/user/manage'"><i class="fas fa-undo-alt"></i> 戻る</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-4 offset-sm-1">
                    <h3>アバター画像</h3>
                    <div class="m-1">
                        <img id="avatar_preview" class="img-thumbnail" src="https://www.gravatar.com/avatar/<?= md5( strtolower( trim( !empty( $user_data['email'] ) ? $user_data['email'] : $user_data['user_id']."-".$this->config->item( 'home_title' ) ) ) ) ?>?d=identicon&s=250" />
                    </div>
                </div>
            </div>
        </div>

        <br class="container mt-5" />
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> 備考
                </div>
                <div class="card-body">
                    <ul>
                        <li>ユーザーIDには、英数字とアンダーバー（_）、ハイフン（-）が利用できます。また、他ユーザーが使用しているユーザーIDは使用できません。</li>
                        <li>アバター画像は<a href="https://ja.gravatar.com/">Gravatar</a>で設定できます。（Emailが未指定 or そのEmailに対応するGravatarが未指定の場合、Identiconが表示されます。）</li>
                    </ul>
                </div>
            <div>
    <?php endif ?>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        $( '#updateform' ).submit( function( e ) {
            e.preventDefault();
            $( '#submit_btn' ).prop( 'disabled', true );
			$( '#submit_btn_icon' ).removeClass( "fa-user-cog" ).addClass( "fa-spinner fa-spin" );
			$( '#submit_btn_text' ).text( "ユーザーデータ更新中" );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/update" ) ?>",
                dataType: "json",
                data: { 
                    user_id: $( '#login_user_id' ).val(),
                    display_user_name: $( '#display_user_name' ).val(),
                    old_user_pass: $( '#old_login_pass' ).val(),
                    new_user_pass: $( '#new_login_pass' ).val(),
                    new_user_pass_cfm: $( '#new_login_pass_cfm' ).val(),
                    email: $( '#email' ).val()
                }
            }).done( function( response ) {
                    if( response.error ) {
                        $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                        $( '#response_panel' ).html( '<i class="fas fa-times"></i> ' + response.message );
                        $( '#submit_btn_text' ).text( "ユーザーデータ更新" );
                        $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-user-cog" );
                        $( '#submit_btn' ).prop( 'disabled', false );
                    }
                    else {
                        $( '#response_panel' ).removeClass( 'alert-danger' ).addClass( 'alert-success' );
                        $( '#response_panel' ).html( '<i class="far fa-circle"></i> ' + response.message );
                        $( '#submit_btn_text' ).text( "ユーザーデータ更新" );
                        $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-user-check" );
                        window.setTimeout( function() {
                            window.location.href = "<?= site_url( "user/manage" ) ?>";
                        }, 1000 );
                    }
                }).fail( function( response ) {
                    $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                    $( '#response_panel' ).html( '<i class="fas fa-times"></i> エラー: ユーザーデータの更新に失敗しました。' );
                    $( '#submit_btn_text' ).text( "ユーザーデータ更新" );
                    $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-user-cog" );
                    $( '#submit_btn' ).prop( 'disabled', false );
                }).always( function() {
                    $( '#response_panel' ).show();
                });
        });

        $( '#response_panel' ).hide();
    });
</script>