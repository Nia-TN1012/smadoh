<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * iOS版開発アプリリストビュー
 */
class iOS_Develop_Controller extends iOSApplistBase_Controller {

	const PLATFORM = "ios";
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