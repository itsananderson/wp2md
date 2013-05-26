<?php

class Web_Controller {

	public static $form_errors = array();

	const README_URL = 'readme-url';
	const README_TXT = 'readme-txt';
	const README_FILE = 'readme-file';

	const TMP_NAME = 'tmp_name';

	const SUBMIT_TYPE = 'submit-type';
	const SUBMIT_TYPE_URL = 'url';
	const SUBMIT_TYPE_TEXT = 'text';
	const SUBMIT_TYPE_FILE = 'file';

	const RAW = 'output-raw';

	public static function is_web_request() {
		return !defined( 'PHP_SAPI' ) || PHP_SAPI !== 'cli';
	}

	public static function handle_web_request() {
		$readme_txt = self::check_for_readme_txt();

		if ( $readme_txt ) {
			$md_converter = new MD_Converter();
			$converted = $md_converter->convert( $readme_txt );

			if ( isset( $_REQUEST[self::RAW] ) ) {
				header('Content-Type: text/plain');
				echo $converted;
			} else {
				self::load_view( 'conversion-result.php', array( 'converted' => $converted ) );
			}
		} else {
			self::load_view( 'submit-readme.php' );
		}
	}

	public static function check_for_readme_txt() {
		if ( isset( $_REQUEST[self::SUBMIT_TYPE] ) ) {
			$submit_type = $_REQUEST[self::SUBMIT_TYPE];
			if ( self::SUBMIT_TYPE_URL == $submit_type ) {
				if ( empty( $_REQUEST[self::README_URL] ) ) {
					self::$form_errors[] = 'You need to enter a URL for this to work';
					return false;
				}

				$url = $_REQUEST[self::README_URL];

				$response = Readme_Fetcher::fetch_url( $url );

				if ( is_array( $response ) ) {
					self::$form_errors = array_merge( self::$form_errors, $response );
					return false;
				}

				return $response;

			} elseif ( self::SUBMIT_TYPE_FILE == $submit_type ) {
				if ( isset( $_FILES[self::README_FILE] ) && !empty( $_FILES[self::README_FILE][self::TMP_NAME] ) ) {
					$contents = Readme_Fetcher::fetch_file( $_FILES[self::README_FILE][self::TMP_NAME] );
					if ( is_array( $contents ) ) {
						self::$form_errors = array_merge( self::$form_errors, $contents );
					} else {
						return $contents;
					}
				} else {
 				    self::$form_errors[] = "You need to select a README.txt file to upload";
				}
				return false;
			} elseif ( self::SUBMIT_TYPE_TEXT == $submit_type ) {
				if ( isset( $_REQUEST[self::README_TXT] ) ) {
					$content = $_REQUEST[self::README_TXT];
					if ( !empty( $content ) ) {
						if ( get_magic_quotes_gpc() ) {
							$content = stripslashes( $_REQUEST[self::README_TXT] );
						}
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