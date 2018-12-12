<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2" style="background-color:#4B64A1;color:#fff">ログイン</h1>
    <br class="container mt-5" />
	<div class="p-5 border shadow-sm">
        <div id="errorpanel" class="alert alert-danger" role="alert">ログインしてください。</div>
        <form id="loginform">
            <div class="form-group row">
                <label for="lonin_user_id" class="col-sm-1 col-form-label">ユーザー名</label>
                <input type="text" class="col-sm-5 form-control" id="login_user_id" name="login_user_id" placeholder="User Name">
            </div>
            <div class="form-group row">
                <label for="lonin_user_pass" class="col-sm-1 col-form-label">パスワード</label>
                <input type="password" class="col-sm-5 form-control" id="login_user_pass" name="login_user_pass" placeholder="Password">
            </div>

            <div>
                <button type="submit" class="btn btn-primary">ログイン <i class="fas fa-sign-in-alt"></i></button>
            </div>
        </form>
	</div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        $( '#loginform' ).submit( function( e ) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= site_url( "login/signin" ) ?>",
                dataType: "json",
                data: { 
                    user_id: $( '#login_user_id' ).val(),
                    user_pass: $( '#login_user_pass' ).val(),
                },
                success: function( response ){
                    if( response.error ) {
                        $( '#errorpanel' ).show();
                        $( '#errorpanel' ).html( response.message );
                    }
                    else {
                        $( '#errorpanel' ).hide();
                        window.location.href = "<?= site_url( @$_GET['redirect'] ?: "" ) ?>";
                    }
                }
            });
        });

        $( '#errorpanel' ).hide();
    });
</script>