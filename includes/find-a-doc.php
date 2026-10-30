<?php

declare(strict_types=1);

namespace UAMS\Find_a_Doc;

add_action('plugins_loaded', 'UAMS\Find_a_Doc\bootstrap');

/**
 * Loads the UAMSWP Find-a-Doc
 *
 * @since 1.0.0
 */
function bootstrap(): void
{
    include_once __DIR__.'/class.fad-physicians.php';
    include_once __DIR__.'/class.fad-custom-post.php';
    include_once __DIR__.'/class.fad-helper-functions.php';
    include_once __DIR__.'/class.fad-settings.php';
    // include_once (__DIR__ . '/class.fad-expertise-attributes-meta-box.php' ); // Not used in 2020
    include_once __DIR__.'/class.fad-templates.php';
    include_once __DIR__.'/class.fad-acf-functions.php'; // ACF specific functions
    include_once __DIR__.'/class.fad-fwp-functions.php'; // FacetWP specific functions
    include_once __DIR__.'/class.fad-functions.php';
    include_once __DIR__.'/class.fad-settings-pages.php';
    include_once __DIR__.'/class.fad-acf-blocks.php';
    include_once __DIR__.'/class.fad-gmb-settings-page.php';
    include_once __DIR__.'/class.fad-gbp-logic.php'; // Business Profile sync: field mapping, diff, silos
    include_once __DIR__.'/class.fad-gbp-client.php'; // Business Profile sync: Google API client
    include_once __DIR__.'/class.fad-gbp-sync.php'; // Business Profile sync: update, create and skip decisions
    include_once __DIR__.'/class.fad-gbp-runtime.php'; // Business Profile sync: WordPress wiring
    include_once __DIR__.'/class.fad-gbp-settings.php'; // Business Profile sync: settings screen
    \uamswp_fad_gbp_register_hooks();
    \uamswp_fad_gbp_register_settings_hooks();
}
