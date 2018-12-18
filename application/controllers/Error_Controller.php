<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 404ページのオーバーライド用
 */
class Error_Controller extends MY_Controller {
    function error_404() {
		$this->output->set_status_header( "404" );

		// /api/...（REST API）へのアクセスの時は、JSON形式で返します。
		if( strpos( uri_string(), "api/" ) === 0 ) {
			$res['status_code'] = 404;
            $res['response'] = "Error: End-point Not Found.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
			return;
		}

        $data['page_title'] = "エラー";

		$this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );

		$this->load->view( 'errors/custom/error_404' );
		$this->load->view( 'common/footer' );
    }
}

?>