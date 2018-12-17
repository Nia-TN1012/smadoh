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
	 * 404エラーページにリダイレクトします。
	 */
	protected function show_error_404() {
		$this->output->set_status_header( "404" );
        $data['page_title'] = "エラー";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$this->load->view( 'errors/html/error_404' );
		$this->load->view( 'common/footer' );
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