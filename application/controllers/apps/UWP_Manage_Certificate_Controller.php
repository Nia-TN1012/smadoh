<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UWP_Manage_Certificate_Controller extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'uwpcertmodel' );
    }
    
    public function index() {
		if( !$this->config->item( 'uwp_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

		$this->redirect_if_not_login( "apps/uwp/manage-certificate" );

        if( !UserModel::is_manager() ) {
            $data_body['error'] = true;
            $data_body['message'] = "エラー: このページには、一般ユーザーはアクセスできません。";
        }
        else {
            $data_body['error'] = false;
        }

        $data_head['page_title'] = "サイドロード用証明書の管理";

        $data_body['cert_list'] = $this->get_cert_list();

        $this->load->view( 'common/header', $data_head );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'apps/uwp_manage_cert', $data_body );
		$this->load->view( 'common/footer' );
    }

    private function get_cert_list() {
        $cert_list = $this->uwpcertmodel->get_cert_list();
        $cert_view_list = [];
        $now_date = date( "Y-m-d H:i:s" );
		foreach( $cert_list as $row ) {
			$cert_view_list[] = [
				'environment' 		    => $row['environment'],
				'environment_name' 	=> UWPCertModel::get_environment_name( $row['environment'] ),
				'hash_value' 		=> $row['hash_value'],
				'memo' 		        => $row['memo'],
				'upload_time' 		=> $row['upload_time'],
				'create_time' 		=> $row['create_time'],
				'expire_time'		=> $row['expire_time'],
				'status'  	        => $now_date <= $row['expire_time'] ? UWPCertModel::UWP_CERT_AVAILABLE : UWPCertModel::UWP_CERT_EXPIRED
			];
		}
		
		return $cert_view_list;
	}
	
	public function upload_cert() {
		if( !$this->config->item( 'uwp_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

		$this->redirect_if_not_login( "apps/uwp/manage-certificate", true );
		
		if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
		}

		if( !isset( $_FILES['cert_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: 証明書ファイルが指定されていません。";
		}
		else if( !isset( $_POST['target_type'] ) || UWPCertModel::get_environment_name( $_POST['target_type'] ) == "不明" ) {
			$res['error'] = true;
			$res['message'] = "エラー: 不正なリクエストです。";
		}
		else if( !is_uploaded_file( $_FILES['cert_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: 証明書ファイルのアップロードに失敗しました。";
		}
		else {
			$target_type = $_POST['target_type'];
			$upload_dest_path = "uploads/certificate/uwp/{$target_type}";

			$cert_der = file_get_contents( $_FILES['cert_file']['tmp_name'] );
			$cert_pem = strpos( $cert_der, "BEGIN CERTIFICATE" ) !== false ? $cert_der : der_to_pem( $cert_der );
			$cert_res = openssl_x509_read( $cert_pem );
			if( $cert_res === false ) {
				$res['error'] = true;
				$res['message'] = "エラー: 証明書ファイルの形式が正しくありません。";
			}
			else {
				$cert_data = openssl_x509_parse( $cert_res );
				$hash_value = hash( 'sha256', bin2hex( openssl_pkey_get_details( openssl_pkey_get_public( $cert_pem ) )['rsa']['n'] ) );
				openssl_x509_free( $cert_res );
				$create_time = date( 'Y-m-d H:i:s', $cert_data['validFrom_time_t'] );
				$expire_time = date( 'Y-m-d H:i:s', $cert_data['validTo_time_t'] );
				$cert_memo = @$_POST['cert_memo'] ?: "";

				if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
					!move_uploaded_file( $_FILES['cert_file']['tmp_name'], $upload_dest_path."/uwp_{$target_type}_sideload.cer" ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: 証明書ファイルのアップロードに失敗しました。";
				}
				else if( !$this->uwpcertmodel->update_cert( $target_type, $hash_value, $cert_memo, $create_time, $expire_time ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: 証明書ファイルのアップロードに失敗しました。";
				}
				else {
					$res['error'] = false;
					$res['message'] = "証明書ファイルのアップロードに成功しました。";
				}
			}
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
	}

	public function disable_cert() {
		if( !$this->config->item( 'uwp_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}
		
		$this->redirect_if_not_login( "apps/uwp/manage-certificate", true );
		
		if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

		$environment = $this->input->post( "environment" );
		if( !isset( $environment ) || UWPCertModel::get_environment_name( $environment ) == "不明" || !$this->uwpcertmodel->has_valid_cert( $environment ) ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
		}
        else if( $this->uwpcertmodel->disable_cert( $environment ) ) {
            $res['error'] = false;
            $res['message'] = "選択したサイドロード用証明書を無効化しました。";
        }
        else {
            $res['error'] = true;
            $res['message'] = "エラー: サイドロード用証明書の無効化に失敗しました。";
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
	}
}

?>