<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class AppListBase_Controller extends MY_Controller {

    const PLATFORM = "unknown";
	const ENVIRONMENT = "develop";

	public function __construct() {
		parent::__construct();
		$this->load->model( 'appdatalist' );
		$this->load->model( 'feedmodel' );
    }

    public function index() {
		// ログインしていなかったら、ログインページにリダイレクト
		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

        $data_head['page_title'] = $this->config->item( static::PLATFORM."_".static::ENVIRONMENT."_name" );
        $data_body['page_title'] = $data_head['page_title'];
        $data_body['platform'] = static::PLATFORM;
        $data_body['environment'] = static::ENVIRONMENT;

		$this->load->library( 'pagination' );
        $config['base_url'] = "/apps/".static::PLATFORM."/".static::ENVIRONMENT;
        $item_num = $this->appdatalist->get_app_list_num( static::PLATFORM, static::ENVIRONMENT );
		$config['total_rows'] = $item_num;
		$config['per_page'] = 10;
        $offset = isset( $_GET['p'] ) && $_GET['p'] > 0 ? ( $_GET['p'] - 1 ) * $config['per_page'] : 0;

        $data_body['app_view_list'] = $this->get_app_list( $config['per_page'], $offset );
		$data_body['item_num'] = $item_num;

		$data_body['latest_app_data'] = $this->get_latest_app_data();

		$this->pagination->initialize( $config ); 
		
		$this->load->view( 'common/header', $data_head );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'apps/'.static::PLATFORM.'_applist', $data_body );
		$this->load->view( 'common/footer' );
    }
    
    abstract protected function get_app_list( $num, $offset );
    abstract protected function get_latest_app_data();
    abstract public function download_app();
}

?>