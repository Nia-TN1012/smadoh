<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Android_Develop_Controller extends AndroidAppListBase_Controller {

	const PLATFORM = "android";
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