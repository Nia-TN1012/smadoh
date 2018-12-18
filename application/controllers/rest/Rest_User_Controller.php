<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_User_Controller extends RestBase_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'usertokenmodel' );
    }

    public function info() {
        
        $header = $this->input->request_headers();
        $res = $this->authenticate( $header['token'] );
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    public function list() {
        
        $header = $this->input->request_headers();

        $auth_res = $this->authenticate( $header['token'] );
        if( $auth_res['status_code'] !== 200 ) {
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $auth_res ) );
            return;
        }

        $num = @$header['num'] ?: 10;
        $offset = @$header['offset'] ?: 0;

        if( ( $user_list = $this->usermodel->get_user_list( $num, 0 ) ) === null ) {
            $res['status_code'] = 500;
            $res['response'] = "Error: Failed to get user list.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }
        
        $res['status_code'] = 200;
        $res['response'] = $user_list;
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
    
}

?>