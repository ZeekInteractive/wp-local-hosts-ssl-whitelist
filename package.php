<?php

namespace Zeek\WP_Local_Hosts_SSL_Whitelist;

function is_enabled() : bool {
	if ( isset( $_ENV['LOCAL_HOSTS_SSL_WHITELIST'] ) && true === $_ENV['LOCAL_HOSTS_SSL_WHITELIST'] ) {
		return true;
	}

	if ( defined( 'LOCAL_HOSTS_SSL_WHITELIST' ) && true === LOCAL_HOSTS_SSL_WHITELIST ) {
		return true;
	}

	return false;
}

/**
 * Disable WordPress's certificate verification selectively against local hosts that exist in /etc/hosts
 *
 * This is disabled by default, to utilize add 'LOCAL_HOSTS_SSL_WHITELIST' => true in .env.php
 */
add_filter( 'https_ssl_verify', function( $verify, $url ) {

	if ( empty( $url ) ) {
		return $verif;
	}
	
	if ( true !== is_enabled() ) {
		return $verify;
	}

	$local_hosts = get_local_hosts();

	// check if the url we're working with is a local host
	$parsed_url = parse_url( $url );
	$host = $parsed_url['host'];

	if ( ! in_array( $host, $local_hosts ) ) {
		return $verify;
	}

	return false;
}, 100, 2 );

/**
 * Get a usable array of hosts from the /etc/hosts file on the server
 *
 * @return array
 */
function get_local_hosts() : array {
	$local_hosts = [];

	$hosts_file = file( '/etc/hosts' );

	foreach ( $hosts_file as $line ) {
		$parts = preg_split( '/\s+/', $line, '-1', PREG_SPLIT_NO_EMPTY );

		// Do not include the IP (first part of the line)
		array_shift( $parts );

		$local_hosts = array_merge( $local_hosts, $parts );
	}

	return $local_hosts;
}
