<?php

/**
 * Create the getallheaders() function if it's not available.
 *
 * Ref: https://www.php.net/manual/en/function.apache-request-headers.php
 * Ref: https://stackoverflow.com/questions/541430/how-do-i-read-any-request-header-in-php
 */
if ( ! function_exists( 'getallheaders' ) ) {
	function getallheaders() {
		$headers = array();
		foreach ( $_SERVER as $key => $value ) {
			if ( substr( $key, 0, 5 ) <> 'HTTP_' ) {
				continue;
			}
			$header             = str_replace( ' ', '-',
				ucwords( str_replace( '_', ' ', strtolower( substr( $key, 5 ) ) ) ) );
			$headers[ $header ] = $value;
		}

		return $headers;
	}
}