<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'usermodel' );
	}

    public function index() {
		if( UserModel::is_login() ) {
            redirect( "/" );
		}
		
		$data['page_title'] = "ログイン";
		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/login' );
		$this->load->view( 'common/footer' );
	}

	public function signin() {
		if( UserModel::is_login() ) {
            redirect( "/" );
		}

		$user_id = $this->input->post( "user_id" );
		$user_pass = $this->input->post( "user_pass" );
		if( !isset( $user_id ) || !isset( $user_pass ) ) {
			log_message( 'error', "No login data." );
		}

		if( ( $id = $this->usermodel->get_login( $user_id, $user_pass ) ) > 0 ) {
			$res['error'] = false;
			$user_data = $this->usermodel->get_user_data( $id );
			$session_data['login_user_data'] = [
				'id'					=> $user_data['id'],
				'user_id'		 		=> $user_data['user_id'],
				'display_user_name'	 	=> $user_data['display_user_name'],
				'email'					=> $user_data['email'],
				'role' 					=> $user_data['role']
			];
			$this->session->set_userdata( $session_data );
		}
		else {
			$res['error'] = true;
			$res['message'] = "エラー: ユーザー名またはパスワードが違います。";
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
	}
}

?>