<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout_Controller extends MY_Controller {

	public function index() {
		if( !UserModel::is_login() ) {
			redirect( "/login" );   
		}
		else {
			unset( $_SESSION['login_user_data'] );

			$data['page_title'] = "ログアウト";

			$this->load->view( 'common/header', $data );
			$this->load->view( 'common/navigation' );
			$this->load->view( 'user/logout' );
			$this->load->view( 'common/footer' );
		}
	}
}

?>