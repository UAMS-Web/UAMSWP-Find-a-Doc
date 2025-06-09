<?php
/*
Plugin Name: UAMSWP Find-a-doc
Plugin URI: -
Description: Find-a-doc plugin for uamshealth.com
Author: uams, Todd McKee, MEd
Author URI: http://www.uams.edu/
Version: 2.2.10
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// This plugin uses namespaces and requires PHP 5.3 or greater.
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '', // phpcs:ignore WordPress.PHP.RestrictedPHPFunctions.create_function_create_function
	"echo '<div class=\"error\"><p>" . __( 'UAMSWP Find-a-doc requires PHP 5.3 to function properly. Please upgrade PHP or deactivate the plugin.', 'uamswp-find-a-doc' ) . "</p></div>';" ) );
	return;
} else {
	define('UAMS_FAD_ROOT_URL', plugin_dir_url(__FILE__));
	define('UAMS_FAD_PATH', plugin_dir_path(__FILE__));

	$plugin_header  = get_file_data(
		__FILE__,
		array(
			'version' => 'Version',
		)
	);
	$plugin_version = $plugin_header['version'];

	define('UAMS_FAD_VERSION', $plugin_version);

	require_once __DIR__ . '/required-plugins.php';
	include_once __DIR__ . '/includes/find-a-doc.php';
}
