<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UWP版開発アプリリストビュー
 */
class UWP_Develop_Controller extends UWPAppListBase_Controller {

	const PLATFORM = "uwp";
	const ENVIRONMENT = "develop";

	public function index() {
		if( !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404();
			return;
		}
		parent::index();
	}
}

?>