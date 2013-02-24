<?php

class Web_Controller {

	public static $form_errors = array();

	public static function is_web_request() {
		return !defined( 'PHP_SAPI' ) || PHP_SAPI !== 'cli';
	}

	public static function handle_web_request() {
		$readme_txt = self::check_for_readme_txt();

		if ( $readme_txt ) {
			$md_converter = new MD_Converter();
			$converted = $md_converter->convert( $readme_txt );

			self::load_view( 'conversion-result.php', array( 'converted' => $converted ) );
		} else {
			self::load_view( 'submit-readme.php' );
		}
	}

	public static function check_for_readme_txt() {
		if ( isset( $_REQUEST['submit-type'] ) ) {
			$submit_type = $_REQUEST['submit-type'];
			if ( 'url' == $submit_type ) {
				if ( empty( $_REQUEST['readme-url'] ) ) {
					self::$form_errors[] = 'You need to enter a URL for this to work';
					return false;
				}

				$url = $_REQUEST['readme-url'];

				if ( false == filter_var( $url, FILTER_VALIDATE_URL ) ) {
					self::$form_errors[] = 'You need to enter the URL of a valid README.txt file for this to work';
					return false;
				}

				$response = file_get_contents( $url );

				if ( empty( $response ) ) {
					self::$form_errors[] = 'The URL you entered is either empty, or does not exist';
					return false;
				}

				return $response;
			} elseif ( 'file' == $submit_type ) {
				if ( isset( $_FILES['readme-file'] ) && !empty( $_FILES['readme-file']['tmp_name'] ) ) {
					$content = file_get_contents( $_FILES['readme-file']['tmp_name'] );
					if ( $content ) {
						return $content;
					} else {
						self::$form_errors[] = 'Uploaded file seems to be empty';
					}
				}
				self::$form_errors[] = "You need to select a README.txt file to upload";
				return false;
			} elseif ( 'text' == $submit_type ) {
				if ( isset( $_REQUEST['readme-txt'] ) ) {
					$content = $_REQUEST['readme-txt'];
					if ( !empty( $content ) ) {
						return $content;
					}
					self::$form_errors[] = 'You need to paste the contents of a README.txt file to convert';
				}
				return false;
			}
		}
		return false;
	}

	public static function load_view( $view, $data = array() ) {
		extract( $data );

		require( WP2MD_ROOT . '/views/' . $view );
	}
}