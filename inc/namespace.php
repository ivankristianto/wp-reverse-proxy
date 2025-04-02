<?php
/**
 * Bootstrap the plugin.
 */

declare( strict_types = 1 );

namespace WPReverseProxy;

/**
 * Connect namespace methods to hooks and filters.
 *
 * @return void
 */
function bootstrap(): void {
	add_filter( 'query_vars', add_custom_query_vars( ... ) );
	add_action( 'init', add_rewrite_rules( ... ) );
	add_action( 'template_redirect', handle_proxy_request( ... ) );
}

/**
 * Add custom query vars.
 *
 * @param array $vars The existing query vars.
 *
 * @return array The modified query vars.
 */
function add_custom_query_vars( array $vars ): array {
	$vars[] = 'wp-reverse-proxy';
	$vars[] = 'wp-reverse-proxy-path';

	return $vars;
}

/**
 * Add rewrite rules.
 *
 * @return void
 */
function add_rewrite_rules(): void {
	$rules = array(
		'courses/(.*)' => 'index.php?wp-reverse-proxy=1&wp-reverse-proxy-path=$matches[1]',
	);

	foreach ( $rules as $rule => $query ) {
		add_rewrite_rule( $rule, $query, 'top' );
	}
}

/**
 * Handle proxy requests.
 *
 * @return void
 */
function handle_proxy_request(): void {
	// Check if the request is for the proxy endpoint.
	if ( ! get_query_var( 'wp-reverse-proxy' ) ) {
		return;
	}

	// Get the requested path.
	$path = get_query_var( 'wp-reverse-proxy-path', '/' );

	// Validate the path.
	// if ( ! preg_match( '/^\/[a-zA-Z0-9\/\-_\.]+$/', $path ) ) {
	//  // Send a 404 response if the path is invalid.
	//  header( 'HTTP/1.0 404 Not Found' );
	//  echo '404 Not Found';
	//  exit;
	// }

	// @TODO: This will come from the database or configuration.
	$prefix_path = '';
	$target_ip   = '';
	$target_port = 443;
	$target_path = trailingslashit( $prefix_path ) . trailingslashit( $path );
	$target_url  = sprintf( 'https://%s:%d%s', $target_ip, $target_port, $target_path );
	$host        = '';

	$headers = array(
		'Host'              => $host,
		'User-Agent'        => $_SERVER['HTTP_USER_AGENT'],
		'Accept'            => $_SERVER['HTTP_ACCEPT'],
		'Accept-Language'   => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
		'follow-redirects'  => 'false',
		'X-Forwarded-For'   => $_SERVER['REMOTE_ADDR'],
		'X-Forwarded-Proto' => 'https',
		'X-Forwarded-Host'  => $host,
		'X-Forwarded-Port'  => $target_port,
		'X-Forwarded-Path'  => $target_path,
	);
	$headers = array_merge( $headers, $_SERVER );
	$headers = array_filter(
		$headers,
		function ( $key ) {
			return ! in_array( $key, array( 'HTTP_COOKIE', 'HTTP_ACCEPT_ENCODING' ), true );
		},
		ARRAY_FILTER_USE_KEY
	);

	// Send the request to the target URL.
	$response = wp_remote_get(
		$target_url,
		array(
			'headers' => $headers,
		)
	);

	if ( is_wp_error( $response ) ) {
		// Handle error.
		header( 'HTTP/1.0 500 Internal Server Error' );
		echo '500 Internal Server Error';
		echo $response->get_error_message();
		exit;
	}

	// Send the response back to the client.
	$body    = wp_remote_retrieve_body( $response );
	$code    = wp_remote_retrieve_response_code( $response );
	$headers = wp_remote_retrieve_headers( $response );
	$cookies = wp_remote_retrieve_cookies( $response );
	$headers = array_merge( $headers, $cookies );

	foreach ( $headers as $key => $value ) {
		if ( ! in_array( $key, array( 'Content-Encoding', 'Transfer-Encoding' ), true ) ) {
			header( sprintf( '%s: %s', $key, $value ) );
		}
	}
	header( sprintf( 'HTTP/1.0 %d %s', $code, get_status_header_desc( $code ) ) );
	echo $body;
	exit;
}
