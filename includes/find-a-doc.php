<?php

namespace UAMS\Find_a_Doc;

add_action( 'plugins_loaded', 'UAMS\Find_a_Doc\bootstrap' );

/**
 * Loads the UAMSWP Find-a-Doc
 *
 * @since 1.0.0
 */
function bootstrap() {
    //include_once (__DIR__ . '/class-uams-syndicate-shortcode-base.php');
    include_once (__DIR__ . '/class.fad-physicians.php' );
    include_once (__DIR__ . '/class.fad-custom-post.php' );
    // include_once (__DIR__ . '/class.fad-locations-mb.php' );
    include_once (__DIR__ . '/class.fad-locations-acf.php' );
    // include_once (__DIR__ . '/class.fad-physician-mb.php' );
    include_once (__DIR__ . '/class.fad-physician-acf.php' );
    // include_once (__DIR__ . '/class.fad-services-mb.php' );
    include_once (__DIR__ . '/class.fad-services-acf.php' );
    // include_once (__DIR__ . '/class.fad-taxonomies-mb.php' );
    include_once (__DIR__ . '/class.fad-taxonomies-acf.php' );
    include_once (__DIR__ . '/class.fad-settings.php' );
    include_once (__DIR__ . '/class.fad-service-attributes-meta-box.php' );
    include_once (__DIR__ . '/class.fad-templates.php' );
    include_once (__DIR__ . '/class.fad-functions.php' );
    
	//add_action( 'init', 'UAMS\Content_Syndicate\activate_shortcodes' );
}

/**
 * Activates the shortcodes built in with UAMSWP Content Syndicate.
 *
 * @since 1.0.0
 */
// function activate_shortcodes() {
// 	include_once dirname( __FILE__ ) . '/class-uams-syndicate-shortcode-news.php';

// 	// Add the [uamswp_json] shortcode to pull standard post content.
// 	new \UAMS_Syndicate_Shortcode_News();

// 	do_action( 'uamswp_content_syndicate_shortcodes' );
// }

/**
 * Clear the last changed cache for local results whenever
 * a post is saved.
 *
 * @since 1.4.0
 */
// function clear_local_content_cache() {
// 	wp_cache_set( 'last_changed', microtime(), 'uamswp-content' );
// }
