<?php

class WP2MD_Tests {

	const INPUT_DIR = 'inputs';
	const EXPECTED_DIR = 'expected';

	/**
	 * @var $converter MD_Converter
	 */
	static $converter;

	public static function start() {
		self::$converter = new MD_Converter();

		if ( Web_Controller::is_web_request() ) {
			include WP2MD_ROOT . '/views/header.php';
			echo '<table class="test-results"><tr><th>Pass/Fail</th><th>Test Name</th><th>Message</th></tr>';
		}

		$tests = scandir(self::INPUT_DIR);
		foreach($tests as $test) {
			if ( '.' != $test && '..' != $test ) {
				self::run_test( $test );
			}
		}

		if ( Web_Controller::is_web_request() ) {
			echo '</table>';
			include WP2MD_ROOT . '/views/footer.php';
		}
	}

	private static function run_test( $test ) {

		$input_path = self::INPUT_DIR . '/' . $test;
		$expected_path = self::EXPECTED_DIR . '/' . $test;

		if ( !file_exists( $input_path ) ) {
			self::fail( $test, "Input file $input_path does not exist" );
			return;
		}

		if ( !file_exists( $expected_path ) ) {
			self::fail( $test, "Expected output file $expected_path does not exist" );
			return;
		}

		$input = file_get_contents( $input_path );
		$expected_output = str_replace( "\r\n", "\n", file_get_contents( $expected_path ) );
		$output = self::$converter->convert( $input );

		if ( self::assert_equal( $test, $expected_output, $output ) ) {
			self::output_results( $test, true );
		}
	}

	private static function fail( $test, $message = '' ) {
		self::output_results( $test, false, $message );
	}

	private static function assert_equal( $test, $expected, $actual, $message = '' ) {
		if ( $expected != $actual ) {
			$message = "Expected:\n'$expected'\nActual:\n'$actual'; $message";
			header( 'content-type', 'text/plain' );
			die( $message );
			self::output_results( $test, false, $message );
			return false;
		}
		return true;
	}

	private static function output_results( $test, $pass, $message = '' ) {
		$test_status = $pass ? 'pass' : 'fail';
		$message = $pass ? '' : $message;
		if ( Cli_Controller::is_cli_request() ) {
			echo "$test_status: $test $message";
		} else {
			echo "<tr class=\"$test_status\"><td>$test_status</td><td>$test</td><td><pre>" . esc_html( $message ) . "</pre></td></tr>";
		}
	}
}

define( 'WP2MD_ROOT_URL', '../');

include '../wp2md.php';

WP2MD_Tests::start();

