<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeforeFilter {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    /**
	 * ロボットによるアクセスの時、HTTP403を返してリジェクトします。
	 */
    function reject_robot_access() {
        if( $this->CI->input->is_cli_request() ) {
            return;
        }
        if( $this->CI->agent->is_robot() ) {
            set_status_header( 403 );
			exit();
		}
    }

}

?>