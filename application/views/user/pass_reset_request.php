<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#4B64A1">パスワードリセット</h1>
    <br class="container mt-5" />
	<div class="p-5 border shadow-sm">
        <div id="response_panel" class="alert alert-success" role="alert"></div>
        <form id="loginform">
            <div class="form-group">
                <label for="user_id" class="form-label"><i class="fas fa-user"></i> ユーザー名</label>
                <input type="text" class="col-lg-5 form-control" id="user_id" name="user_id" placeholder="User Name">
            </div>
            <div class="form-group">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> メールアドレス</label>
                <input type="text" class="col-lg-5 form-control" id="email" name="email" placeholder="Email Address">
            </div>
            <br class="container mt-5" />
            <div>
                <button type="submit" id="submit_btn" class="btn btn-primary"><i id="submit_btn_icon" class="fas fa-envelope"></i> <span id="submit_btn_text">パスワードリセットのメールを送信</span></button>
                <button type="button" class="btn btn-secondary" onClick="window.location.href='/user/manage'"><i class="fas fa-undo-alt"></i> 戻る</button>
            </div>
        </form>
	</div>
    <br class="container mt-5" />
    <div class="card">
        <div class="card-header bg-warning">
            <i class="fas fa-exclamation-triangle"></i> 注意
        </div>
        <div class="card-body">
            <ul>
                <li>(ドメイン名)からのメールを受信できるようにしてください。</li>
                <li>パスワードリセットのメールの有効期限は、送信から24時間です。</li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        $( '#response_panel' ).hide();

        $( '#loginform' ).submit( function( e ) {
            e.preventDefault();
            $( '#submit_btn' ).prop( 'disabled', true );
			$( '#submit_btn_icon' ).removeClass( "fa-envelope" ).addClass( "fa-spinner fa-spin" );
			$( '#submit_btn_text' ).text( "メール送信中" );
            $.ajax({
                type: "POST",
                url: "<?= site_url( "user/resetpass/request/send" ) ?>",
                dataType: "json",
                data: { 
                    user_id: $( '#user_id' ).val(),
                    user_mail: $( '#email' ).val(),
                }
            }).done( function( response ) {
                if( response.error ) {
                    $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                    $( '#response_panel' ).html( '<i class="fas fa-times"></i> ' + response.message );
                    $( '#submit_btn_text' ).text( "パスワードリセットのメールを送信" );
                    $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-envelope" );
                    $( '#submit_btn' ).prop( 'disabled', false );
                }
                else {
                    $( '#response_panel' ).removeClass( 'alert-danger' ).addClass( 'alert-success' );
                    $( '#response_panel' ).html( '<i class="far fa-circle"></i> ' + response.message );
                    $( '#submit_btn_text' ).text( 'メールを送信完了' );
                    $( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-check" );
                    window.setTimeout( function() {
                        window.location.href = "<?= site_url( '/login' ) ?>";
                    }, 5000 );
                }
            }).fail( function( response ) {
                $( '#response_panel' ).removeClass( 'alert-success' ).addClass( 'alert-danger' );
                $( '#response_panel' ).html( "エラー: メール送信に失敗しました。" );
                $( '#submit_btn_text' ).text( "パスワードリセットのメールを送信" );
				$( '#submit_btn_icon' ).removeClass( "fa-spinner fa-spin" ).addClass( "fa-envelope" );
				$( '#submit_btn' ).prop( 'disabled', false );
            }).always( function() {
				$( '#response_panel' ).show();
			});
        });
    });
</script>