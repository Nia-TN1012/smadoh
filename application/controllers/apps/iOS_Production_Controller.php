<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class iOS_Production_Controller extends iOSAppListBase_Controller {

	const PLATFORM = "ios";
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