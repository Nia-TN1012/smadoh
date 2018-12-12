<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_User_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'usertokenmodel' );
    }

    public function info() {
        
        $header = $this->input->request_headers();
        $token = $header['token'];
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

        if( ( $user_info = $this->usermodel->get_user_data_by_user_id( $user_id ) ) === null ) {
            $res['status_code'] = 500;
            $res['response'] = "Error: Failed to get user data.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }
        
        $res['status_code'] = 200;
        $res['response'] = $user_info;
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    public function list() {
        
        $header = $this->input->request_headers();
        $token = $header['token'];
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