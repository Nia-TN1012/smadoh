<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestUWPAppListBase_Controller extends MY_Controller {

    const PLATFORM = "uwp";
	const ENVIRONMENT = "develop";

    public function __construct() {
        parent::__construct();
        $this->load->model( 'uwpcertmodel' );
    }

    public function register() {
        if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$res['status_code'] = 404;
            $res['response'] = "Error: End-point not found (disabled by app_config).";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
			return;
        }

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

        $user_data = $this->usermodel->get_user_data_by_user_id( $user_id );
        if( is_null( $user_data ) || $user_data['role'] > UserModel::ROLE_MANAGER ) {
            $res['status_code'] = 403;
            $res['response'] = "Error: You must have at least the authority of the application manager ( ROLE_MANAGER ) to execute this request.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }

        if( !isset( $_FILES['appx_file']['tmp_name'] ) ) {
			$res['status_code'] = 400;
			$res['response'] = "Error: No appxbundle file.";
        }
        else if( !isset( $header['app_ver'] ) || empty( $header['app_ver'] ) ) {
			$res['status_code'] = 400;
			$res['message'] = "Error: No app version.";
		}
		else if( !is_uploaded_file( $_FILES['appx_file']['tmp_name'] ) ) {
			$res['status_code'] = 500;
			$res['response'] = "Error: Failed to upload appxbundle file.";
		}
		else {
			$new_distrib_id = intval( $this->appdatalist->get_latest_ditrib_id( static::PLATFORM, static::ENVIRONMENT ) ) + 1;
            $upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$new_distrib_id}";
            $app_ver = $header['app_ver'];
			if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
				!move_uploaded_file( $_FILES['appx_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_appx_name' ).".appxbundle" ) ) ) {
				$res['status_code'] = 500;
				$res['response'] = "Error: Failed to upload appxbundle file.";
			}
			else if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver ) ) {
                $res['status_code'] = 500;
                $res['response'] = "Error: Failed to upload appxbundle file.";
            }
            else {
                $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $new_distrib_id );
                $this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $user_data['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$new_distrib_id} をアップロードしました。" );
                $res['status_code'] = 200;
                $res['response'] = $app_data;
            }
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}
?>