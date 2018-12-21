<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password_Reset_Controller extends MY_Controller {

    public function index() {
        if( UserModel::is_login() ) {
            redirect( "/" );
		}
		
		$data['page_title'] = "パスワードリセットのリクエスト";
		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/pass_reset_request' );
		$this->load->view( 'common/footer' );
    }

    public function send() {
        if( UserModel::is_login() ) {
            redirect( "/" );
		}
		
		$user_id = $this->input->post( "user_id" );
		$email = $this->input->post( "email" );
        
        $res['error'] = false;
		$res['message'] = "パスワードリセットのメールを送信しました。メール本文中のリンクより、パスワードリセットの手続きを行ってください。なお、メールの有効期限は送信より24時間です。";

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}