<?php

declare(strict_types=1);
/*
Plugin Name: UAMSWP Find-a-doc
Plugin URI: https://github.com/UAMS-Web/UAMSWP-Find-a-Doc
Description: Find-a-doc plugin for uamshealth.com
Author: uams, Todd McKee, MEd
Author URI: https://www.uams.edu/
Version: 2.4.0
Requires PHP: 8.2
*/

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

// This plugin requires PHP 8.2 or greater. The check stays in the file for a site whose PHP is
// downgraded underneath an already-active plugin: it shows a notice instead of a fatal error.
if (version_compare(PHP_VERSION, '8.2', '<')) {
    add_action(
        'admin_notices',
        function (): void {
            echo '<div class="error"><p>'.esc_html__('UAMSWP Find-a-doc requires PHP 8.2 to function properly. Please upgrade PHP or deactivate the plugin.', 'uamswp-find-a-doc').'</p></div>';
        }
    );

    return;
}

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
