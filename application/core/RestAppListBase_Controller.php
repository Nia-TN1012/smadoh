<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class RestAppListBase_Controller extends RestBase_Controller {

    const PLATFORM = "unknown";			// プラットフォーム
	const ENVIRONMENT = "unknown";		// 環境

    public function __construct() {
        parent::__construct();
        $this->load->model( 'appdatalist' );
        $this->load->model( 'feedmodel' );
    }

    public function list() {
        if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$res['status_code'] = 404;
            $res['response'] = "Error: End-point not found (disabled by app_config).";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
			return;
        }
        
        $header = $this->input->request_headers();

        $auth_res = $this->authenticate( $header['token'] );
        if( $auth_res['status_code'] !== 200 ) {
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $auth_res ) );
            return;
        }

        $num = @$header['num'] ?: 10;
        $offset = @$header['offset'] ?: 0;

        if( ( $app_list = $this->appdatalist->get_app_data_list( static::PLATFORM, static::ENVIRONMENT, $num, $offset ) ) === null ) {
            $res['status_code'] = 500;
            $res['response'] = "Error: Failed to get app list.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }
        
        $res['status_code'] = 200;
        $res['response'] = $app_list;
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    abstract public function register();
}
?>