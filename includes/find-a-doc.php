<?php

namespace UAMS\Find_a_Doc;

add_action( 'plugins_loaded', 'UAMS\Find_a_Doc\bootstrap' );

/**
 * Loads the UAMSWP Find-a-Doc
 *
 * @since 1.0.0
 */
function bootstrap() {
    include_once (__DIR__ . '/class.fad-physicians.php' );
    include_once (__DIR__ . '/class.fad-custom-post.php' );
    // include_once (__DIR__ . '/class.fad-locations-acf.php' );
    // include_once (__DIR__ . '/class.fad-physician-acf.php' );
    // include_once (__DIR__ . '/class.fad-services-acf.php' );
    // include_once (__DIR__ . '/class.fad-taxonomies-acf.php' );
    include_once (__DIR__ . '/class.fad-helper-functions.php' );
    include_once (__DIR__ . '/class.fad-settings.php' );
    include_once (__DIR__ . '/class.fad-service-attributes-meta-box.php' );
    include_once (__DIR__ . '/class.fad-templates.php' );
    include_once (__DIR__ . '/class.fad-functions.php' );
    include_once (__DIR__ . '/class.fad-settings-pages.php' );
    
}