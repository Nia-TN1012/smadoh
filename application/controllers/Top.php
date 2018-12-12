<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
}

?>