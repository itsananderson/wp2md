<?php

class Cli_Controller {
	public static function is_cli_request() {
		return defined( 'PHP_SAPI' ) && PHP_SAPI === 'cli';
	}

	public static function handle_cli_request() {
		// TODO
	}
}