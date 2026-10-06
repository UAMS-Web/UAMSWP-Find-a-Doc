<?php

declare(strict_types=1);
/*
Plugin Name: UAMSWP Find-a-doc
Plugin URI: -
Description: Find-a-doc plugin for uamshealth.com
Author: uams, Todd McKee, MEd
Author URI: https://www.uams.edu/
Version: 2.3.0
*/

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

// This plugin uses namespaces and requires PHP 5.3 or greater.
define('UAMS_FAD_ROOT_URL', plugin_dir_url(__FILE__));
define('UAMS_FAD_PATH', plugin_dir_path(__FILE__));
$plugin_header = get_file_data(
    __FILE__,
    [
        'version' => 'Version',
    ]
);
$plugin_version = $plugin_header['version'];
define('UAMS_FAD_VERSION', $plugin_version);
require_once __DIR__.'/required-plugins.php';
include_once __DIR__.'/includes/find-a-doc.php';
