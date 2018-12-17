<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 404ページのオーバーライド用
 */
class Error_Controller extends MY_Controller {
    function error_404() {
		$this->output->set_status_header( "404" );
        $data['page_title'] = "エラー";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$this->load->view( 'errors/custom/error_404' );
		$this->load->view( 'common/footer' );
    }
}

?>