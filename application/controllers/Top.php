<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * トップページ用
 */
class Top extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model( 'feedmodel' );
	}

	public function index()
	{
		$data['page_title'] = "トップページ";
		if( UserModel::is_login() ) {
			$data_body['feed_data'] = $this->feedmodel->get_feed_list( 20 );
		}
		else {
			$data_body['fedd_data'] = [];
		}

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$this->load->view( 'top', $data_body );
		$this->load->view( 'common/footer' );
	}

	public function about() {
		$this->redirect_if_not_login( "about", true );

		$this->load->database();
		
		$data['page_title'] = "アプリ情報";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$this->load->view( 'app_info' );
		$this->load->view( 'common/footer' );
	}
}

?>