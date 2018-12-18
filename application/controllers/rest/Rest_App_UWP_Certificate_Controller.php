<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_App_UWP_Certificate_Controller extends RestBase_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->model( 'uwpcertmodel' );
    }
    
    public function update_cert() {
        if( !$this->config->item( 'uwp_use' ) ) {
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

        if( !isset( $_FILES['cert_file']['tmp_name'] ) ) {
			$res['status_code'] = 400;
			$res['response'] = "Error: No certificate file.";
		}
		else if( !isset( $header['environment'] ) || UWPCertModel::get_type_key_name( $header['environment'] ) == "不明" ) {
			$res['status_code'] = 400;
			$res['response'] = "Error: Invaild environment parameter. Please specify 'develop', 'staging' or 'production'.";
		}
		else if( !is_uploaded_file( $_FILES['cert_file']['tmp_name'] ) ) {
			$res['status_code'] = 500;
			$res['response'] = "Error: Failed to update certificate file.";
        }
        else {
            $environment = $header['environment'];
            $upload_dest_path = "uploads/certificate/uwp/{$environment}";

			$cert_der = file_get_contents( $_FILES['cert_file']['tmp_name'] );
			$cert_pem = strpos( $cert_der, "BEGIN CERTIFICATE" ) !== false ? $cert_der : der_to_pem( $cert_der );
			$cert_res = openssl_x509_read( $cert_pem );
			if( $cert_res === false ) {
				$res['status_code'] = 400;
				$res['response'] = "Error: Invalid certificate format. Please specify 'DER' format certificate file.";
			}
			else {
				$cert_data = openssl_x509_parse( $cert_res );
				$hash_value = hash( 'sha256', bin2hex( openssl_pkey_get_details( openssl_pkey_get_public( $cert_pem ) )['rsa']['n'] ) );
				openssl_x509_free( $cert_res );
				$create_time = date( 'Y-m-d H:i:s', $cert_data['validFrom_time_t'] );
				$expire_time = date( 'Y-m-d H:i:s', $cert_data['validTo_time_t'] );
				$cert_memo = @$header['memo'] ?: "";

				if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
					!move_uploaded_file( $_FILES['cert_file']['tmp_name'], $upload_dest_path."/uwp_{$environment}_sideload.cer" ) ) {
					$res['status_code'] = 500;
					$res['response'] = "Error: Failed to update certificate file.";
				}
				else if( !$this->uwpcertmodel->update_cert( $environment, $hash_value, $cert_memo, $create_time, $expire_time ) ) {
					$res['status_code'] = 500;
					$res['response'] = "Error: Failed to update certificate file.";
				}
				else {
					$res['status_code'] = 200;
					$res['response'] = $this->uwpcertmodel->get_cert_data( $environment );
				}
			}
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}
?>