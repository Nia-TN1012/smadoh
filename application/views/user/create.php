<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#4B64A1;color:#fff"><i class="fas fa-user-plus"></i> ユーザー作成</h1>
    <br class="container mt-5" />
    <?php if( $error ): ?>
        <div class="alert alert-danger" role="alert"><?= $message ?></div>
    <?php else: ?>
        <div class="p-5 border shadow-sm">
            <div id="error_panel" class="alert alert-danger" role="alert"></div>
            <form id="createform">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="login_user_id" class="col-form-label">ユーザーID</label>
                        <span class="badge badge-danger">必須</span>
                    </div>
                    <input type="text" class="col-sm-5 form-control" id="login_user_id" name="login_user_id" placeholder="User ID">
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="display_user_name" class="col-form-label">名前</label>
                        <span class="badge badge-danger">必須</span>
                    </div>
                    <input type="text" class="col-sm-5 form-control" id="display_user_name" name="display_user_name" placeholder="Display User Name">
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="login_user_pass" class="col-form-label">パスワード</label>
                        <span class="badge badge-danger">必須</span>
                    </div>
                    <input type="password" class="col-sm-5 form-control" id="login_user_pass" name="login_user_pass" placeholder="Password">
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="login_user_pass_cfm" class="col-form-label">パスワード確認</label>
                        <span class="badge badge-danger">必須</span>
                    </div>
                    <input type="password" class="col-sm-5 form-control" id="login_user_pass_cfm" name="login_user_pass_cfm" placeholder="Password">
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="role" class="col-form-label">ロール</label>
                        <span class="badge badge-warning">選択</span>
                    </div>
                    <select class="col-sm-5 form-control" id="role" name="role">
                        <option value="1">システム管理者</option>
                        <option value="2">アプリ管理者</option>
                        <option value="3">ユーザー</option>
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="email" class="col-form-label">Email</label>
                        <span class="badge badge-info">任意</span>
                    </div>
                    <input type="text" class="col-sm-5 form-control" id="email" name="email" placeholder="Email">
                </div>
                <br class="container mt-5" />
                <div>
                    <button type="submit" id="submit_btn" class="btn btn-primary"><i id="submit_btn_icon" class="fas fa-user-plus"></i> <span id="submit_btn_text">ユーザー作成</span></button>
                    <button type="button" class="btn btn-secondary" onClick="window.location.href='/user/manage'"><i class="fas fa-undo-alt"></i> 戻る</button>
                </div>
            </form>
        </div>
        <br class="container mt-5" />
        <div class="card">
			<div class="card-header bg-info text-white">
				<i class="fas fa-info-circle"></i> 備考
			</div>
			<div class="card-body">
				<ul>
                    <li>ユーザーIDには、英数字とアンダーバー（_）、ハイフン（-）が利用できます。また、他ユーザーが使用しているユーザーIDは使用できません。</li>
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
                                    <th>REST API ( for Jenkins )</th>
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
    $( document ).ready( function() {
        $( '#error_panel' ).hide();
        
        $( '#createform' ).submit( function( e ) {
            e.preventDefault();
            $( '#submit_btn' ).prop( 'disabled', true );
			$( '#submit_btn_icon' ).removeClass( "fa-user-plus" ).addClass( "fa-spinner fa-spin" );
			$( '#submit_btn_text' ).text( "ユーザー作成中" );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/create" ) ?>",
                dataType: "json",
                data: { 
                    user_id: $( '#login_user_id' ).val(),
                    display_user_name: $( '#display_user_name' ).val(),
                    user_pass: $( '#login_user_pass' ).val(),
                    user_pass_cfm: $( '#login_user_pass_cfm' ).val(),
                    role: $( '#role' ).val(),
                    email: $( '#email' ).val()
                }
            }).done( function( response ) {
                if( response.error ) {
                    $( '#error_panel' ).show();
                    $( '#error_panel' ).html( response.message );
                }
                else {
                    $( '#error_panel' ).hide();
                    window.location.href = "<?= site_url( "user/manage" ) ?>";
                }
            }).fail( function( response ) {
                $( '#error_panel' ).show();
                $( '#error_panel' ).html( "エラー: ユーザーの作成に失敗しました。" );
            }).always( function() {
                $( '#submit_btn_text' ).text( "ユーザー作成" );
				$( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-user-plus" );
				$( '#submit_btn' ).prop( 'disabled', false );
            });
        });
    });
</script>