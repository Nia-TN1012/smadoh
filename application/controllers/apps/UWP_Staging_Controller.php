<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UWP版ステージングアプリリストビュー
 */
class UWP_Staging_Controller extends UWPAppListBase_Controller {

	const PLATFORM = "uwp";
	const ENVIRONMENT = "staging";

	public function index() {
		if( !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404();
			return;
		}
		parent::index();
	}
}

?>