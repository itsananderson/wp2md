<?php

class Readme_Fetcher {

	/**
	 * @param $url string Address of the URL to load
	 *
	 * @return string|array Contents of
	 */
	public static function fetch_url( $url ) {
		$errors = array();
		$response = '';

		if ( false == filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$errors[] = 'You need to enter the URL of a valid README.txt file for this to work';
		} else {
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

			$response = curl_exec( $curl );

			$curl_error = curl_error( $curl );
			$http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

			if ( !empty( $curl_error ) ) {
				$errors[] = 'Error fetching URL ' . htmlentities( $url ) . " - $curl_error";
			} elseif ( $http_code != 200 ) {
				$errors[] = 'Fetching URL ' . htmlentities( $url ) . " returned error HTTP code $http_code";
			} elseif( empty( $response ) ) {
				$errors[] = 'The URL you entered empty';
			}

			curl_close( $curl );
		}

		if ( empty( $errors ) ) {
			return $response;
		} else {
			return $errors;
		}
	}

	public static function fetch_file( $path ) {
		$errors = array();
		$content = '';

		if ( !file_exists( $path ) ) {
			$errors[] = "Path $path does not exist";
		} else {
			$content = file_get_contents( $path );
			if ( empty( $content ) ) {
				$errors[] = 'Uploaded file seems to be empty';
			}
		}

		if ( !empty( $errors ) ) {
			return $errors;
		} else {
			return $content;
		}
	}
}
