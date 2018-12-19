<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#4B64A1">ログイン</h1>
    <br class="container mt-5" />
	<div class="p-5 border shadow-sm">
        <div id="response_panel" class="alert alert-success" role="alert"></div>
        <form id="loginform">
            <div class="form-group row">
                <label for="lonin_user_id" class="col-sm-1 col-form-label"><i class="fas fa-user"></i> ユーザー名</label>
                <input type="text" class="col-sm-5 form-control" id="login_user_id" name="login_user_id" placeholder="User Name">
            </div>
            <div class="form-group row">
                <label for="lonin_user_pass" class="col-sm-1 col-form-label"><i class="fas fa-key"></i> パスワード</label>
                <input type="password" class="col-sm-5 form-control" id="login_user_pass" name="login_user_pass" placeholder="Password">
            </div>

            <div>
                <button type="submit" id="submit_btn" class="btn btn-primary"><span id="submit_btn_text">ログイン</span> <i id="submit_btn_icon" class="fas fa-sign-in-alt"></i></button>
            </div>
        </form>
	</div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        $( '#response_panel' ).hide();

        $( '#loginform' ).submit( function( e ) {
            e.preventDefault();
            $( '#submit_btn' ).prop( 'disabled', true );
			$( '#submit_btn_icon' ).removeClass( "fa-sign-in-alt" ).addClass( "fa-spinner fa-spin" );
			$( '#submit_btn_text' ).text( "ログイン中" );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "login/signin" ) ?>",
                dataType: "json",
                data: { 
                    user_id: $( '#login_user_id' ).val(),
                    user_pass: $( '#login_user_pass' ).val(),
                }
            }).done( function( response ) {
                if( response.error ) {
                    $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                    $( '#response_panel' ).html( '<i class="fas fa-times"></i> ' + response.message );
                    $( '#submit_btn_text' ).text( "ログイン" );
                    $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-sign-in-alt" );
                    $( '#submit_btn' ).prop( 'disabled', false );
                }
                else {
                    $( '#response_panel' ).removeClass( 'alert-danger' ).addClass( 'alert-success' );
                    $( '#response_panel' ).html( '<i class="far fa-circle"></i> ログインしました' );
                    $( '#submit_btn_text' ).text( 'ログインしました' );
                    $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-check" );
                    window.setTimeout( function() {
                        window.location.href = "<?= site_url( @$_GET['redirect'] ?: "" ) ?>";
                    }, 1000 );
                }
            }).fail( function( response ) {
                $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                $( '#response_panel' ).html( "エラー: ログインに失敗しました。" );
                $( '#submit_btn_text' ).text( "ログイン" );
				$( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-sign-in-alt" );
				$( '#submit_btn' ).prop( 'disabled', false );
            }).always( function() {
				$( '#response_panel' ).show();
			});
        });
    });
</script>