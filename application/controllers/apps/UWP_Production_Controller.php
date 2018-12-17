<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UWP版本番アプリリストビュー
 */
class UWP_Production_Controller extends UWPAppListBase_Controller {

	const PLATFORM = "uwp";
	const ENVIRONMENT = "production";

	public function index() {
		if( !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404();
			return;
		}
		parent::index();
	}
}

?>