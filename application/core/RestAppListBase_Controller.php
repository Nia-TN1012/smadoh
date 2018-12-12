<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class RestAppListBase_Controller extends MY_Controller {

    const PLATFORM = "ios";
    const ENVIRONMENT = "develop";

    public function __construct() {
        parent::__construct();
        $this->load->model( 'usertokenmodel' );
        $this->load->model( 'appdatalist' );
        $this->load->model( 'feedmodel' );
    }

    public function list() {
        $header = $this->input->request_headers();
        $token = $header['token'];
        log_message( 'debug', $token );
        if( !isset( $token ) ) {
            $res['status_code'] = 401;
            $res['response'] = "Error: No API token specified.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }
        else if( ( $user_id = $this->usertokenmodel->get_user_id_by_token( $token ) ) === null ) {
            $res['status_code'] = 401;
            $res['response'] = "Error: Invalid API token.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
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