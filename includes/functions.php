<?php

/*
 * Set of utility functions
 * Wrapped in function_exists checks so wp2md can be embedded in WordPress
 */

if ( !function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( str_replace( '&', '&amp;', $text ), ENT_NOQUOTES, WP2MD_CHARSET );
	}
}

if ( !function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, WP2MD_CHARSET );
	}
}

if ( !function_exists( 'esc_textarea' ) ) {
	function esc_textarea( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, WP2MD_CHARSET );
	}
}

if ( !function_exists( 'esc_url' ) ) {
	// TODO
}