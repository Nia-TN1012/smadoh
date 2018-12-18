<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestUWPAppListBase_Controller extends RestAppListBase_Controller {

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
        $auth_res = $this->authenticate( $header['token'] );
        if( $auth_res['status_code'] !== 200 ) {
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $auth_res ) );
            return;
        }
        $user_data = $auth_res['response'];
        if( is_null( $user_data ) || $user_data['role'] > UserModel::ROLE_MANAGER ) {
            $res['status_code'] = 403;
            $res['response'] = "Error: You must have at least the authority of the application manager ( ROLE_MANAGER ) to execute this request.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }

        if( !isset( $_FILES['app_file']['tmp_name'] ) ) {
			$res['status_code'] = 400;
			$res['response'] = "Error: No appxbundle file.";
        }
        else if( !isset( $header['app_ver'] ) || empty( $header['app_ver'] ) ) {
			$res['status_code'] = 400;
			$res['message'] = "Error: No app version.";
		}
		else if( !is_uploaded_file( $_FILES['app_file']['tmp_name'] ) ) {
			$res['status_code'] = 500;
			$res['response'] = "Error: Failed to upload appxbundle file.";
		}
		else {
            $dir_hash = AppDataList::generate_dir_hash( static::PLATFORM, static::ENVIRONMENT, $user_data['user_id'] );
            $app_ver = $header['app_ver'];
            if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver, $dir_hash ) ) {
                $res['status_code'] = 500;
                $res['response'] = "Error: Failed to upload appxbundle file.";
            }
            else {
                $app_data = $this->appdatalist->get_app_data_by_dir_hash( static::PLATFORM, static::ENVIRONMENT, $dir_hash );
                $upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$dir_hash}";
                if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
                    !move_uploaded_file( $_FILES['app_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_appx_name' ).".appxbundle" ) ) ) {
                    $res['status_code'] = 500;
                    $res['response'] = "Error: Failed to upload appxbundle file.";
                }
                else {
                    $this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $user_data['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$app_data['distrib_id']} をアップロードしました。" );
                    $res['status_code'] = 200;
                    $res['response'] = $app_data;
                }
            }
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}
?>