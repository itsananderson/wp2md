<?php


class WordPress_2_Markdown {
	public static function start() {
		if ( Cli_Controller::is_cli_request() ) {
			Cli_Controller::handle_cli_request();
		}
		
		if ( Web_Controller::is_web_request() ) {
			Web_Controller::handle_web_request();
		}
	}
}

require 'constants.php';

// Includes
require WP2MD_ROOT . '/includes/md-converter.php';
require WP2MD_ROOT . '/includes/readme-fetcher.php';

// Controllers
require WP2MD_ROOT . '/controllers/cli-controller.php';
require WP2MD_ROOT . '/controllers/web-controller.php';

if ( WP2MD_AUTORUN ) {
	WordPress_2_Markdown::start();
}