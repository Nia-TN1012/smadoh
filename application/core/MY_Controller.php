<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'usermodel' );
	}
	
	protected function redirect_if_not_login( $redirect_to ) {
		if( !UserModel::is_login() ) {
            redirect( "/login?redirect=apps/{$redirect_to}" );
        }
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