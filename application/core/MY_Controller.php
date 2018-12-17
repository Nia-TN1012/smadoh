<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'usermodel' );
		$this->load->library( 'user_agent' );
		$this->reject_robot_access();
	}
	
	/**
	 * ログインしていない場合、リダイレクトURLを設定したログインページにリダイレクトします。
	 */
	protected function redirect_if_not_login( $redirect_to ) {
		if( !UserModel::is_login() ) {
            redirect( "/login?redirect={$redirect_to}" );
        }
	}

	/**
	 * ロボットによるアクセスの時、HTTP403を返してリジェクトします。
	 */
	protected function reject_robot_access() {
		if( $this->agent->is_robot() ) {
			set_status_header( 403 );
			exit();
		}
	}

	/**
	 * カスタム404エラーページにリダイレクトします。
	 */
	protected function show_error_404( $additional_info = null ) {
		$this->output->set_status_header( "404" );
        $data['page_title'] = "エラー";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$data_body['additional_info'] = $additional_info;
		$this->load->view( 'errors/custom/error_404', $data_body );
		$this->load->view( 'common/footer' );
	}

	/**
	 * カスタムエラーページにリダイレクトします。
	 */
	protected function show_error( $error_message = "エラーが発生しました。", $code = 500, $additional_info = null ) {
		$this->output->set_status_header( $code );
        $data['page_title'] = "エラー";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$data_body['error_code'] = $code;
		$data_body['error_code_name'] = $this->get_error_code_name( $code );
		$data_body['error_message'] = $error_message;
		$data_body['additional_info'] = $additional_info;

		$this->load->view( 'errors/custom/error', $data_body );
		$this->load->view( 'common/footer' );
	}

	private function get_error_code_name( $code ) {
		$name = "ERROR";
		switch( $code ) {
			case 400: $name = "Bad Request";			break;
			case 401: $name = "Unauthorized";			break;
			case 403: $name = "Forbidden";				break;
			case 404: $name = "Not Found";				break;
			case 500: $name = "Internal Server Error"; 	break;
		}
		return $name;
	}
}

require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'AppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'iOSAppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'AndroidAppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'UWPAppListBase_Controller.php';

require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'RestAppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'RestiOSAppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'RestAndroidAppListBase_Controller.php';
require_once dirname( __FILE__ ).DIRECTORY_SEPARATOR.'RestUWPAppListBase_Controller.php';

?>