<?php

namespace UAMS\Find_a_Doc;

add_action( 'plugins_loaded', 'UAMS\Find_a_Doc\bootstrap' );

/**
 * 	Loads the UAMSWP Find-a-Doc
 *
 * 	@since 1.0.0
 */
function bootstrap() {
	include_once (__DIR__ . '/class.fad-physicians.php' );
	include_once (__DIR__ . '/class.fad-custom-post.php' );
	include_once (__DIR__ . '/class.fad-helper-functions.php' );
	include_once (__DIR__ . '/class.fad-settings.php' );
	// include_once (__DIR__ . '/class.fad-expertise-attributes-meta-box.php' ); // Not used in 2020
	include_once (__DIR__ . '/class.fad-expertise-rework.php' ); // Creates fake subpages for the Area of Expertise content type
	include_once (__DIR__ . '/class.fad-templates.php' );
	include_once (__DIR__ . '/class.fad-acf-functions.php' ); // ACF specific functions
	include_once (__DIR__ . '/class.fad-fwp-functions.php' ); // FacetWP specific functions
	include_once (__DIR__ . '/class.fad-functions.php' );
	include_once (__DIR__ . '/class.fad-settings-pages.php' );
	include_once (__DIR__ . '/class.fad-acf-blocks.php' );
	include_once (__DIR__ . '/class.fad-seopress-functions.php' ); // SEOPress specific functions
	include_once (__DIR__ . '/class.fad-schema.php' ); // Schema data functions
	include_once (__DIR__ . '/class.fad-gmb-settings-page.php' );
}