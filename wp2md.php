<?php

define( 'WP2MD_ROOT', dirname( __FILE__ ) );

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

require 'includes/md-converter.php';
require 'controllers/cli-controller.php';
require 'controllers/web-controller.php';

WordPress_2_Markdown::start();