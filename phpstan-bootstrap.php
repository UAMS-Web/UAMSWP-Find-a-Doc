<?php

/**
 * Constants the plugin defines from a function call, which PHPStan does not evaluate.
 *
 * `uamswp-find-a-doc.php` defines the first three from `plugin_dir_url()`, `plugin_dir_path()` and the
 * plugin header; FacetWP defines the last. Plain stand-ins give PHPStan the names and string types.
 */

declare(strict_types=1);

define('UAMS_FAD_ROOT_URL', 'https://example.test/wp-content/plugins/UAMSWP-Find-a-Doc/');
define('UAMS_FAD_PATH', __DIR__.'/');
define('UAMS_FAD_VERSION', '0.0.0');
define('FACETWP_URL', 'https://example.test/wp-content/plugins/facetwp');

/**
 * Stand-ins for the FacetWP objects the plugin reaches through `FWP()`, which the FacetWP plugin defines at run time.
 */
if (! class_exists('FacetWP_Stub_Helper')) {
    class FacetWP_Stub_Helper
    {
        public function get_uri(): string
        {
            return '';
        }
    }

    class FacetWP_Stub_Indexer
    {
        public function index(mixed $post_id = false): void {}
    }

    class FacetWP_Stub
    {
        public FacetWP_Stub_Helper $helper;

        public FacetWP_Stub_Indexer $indexer;
    }
}

if (! function_exists('FWP')) {
    function FWP(): FacetWP_Stub
    {
        return new FacetWP_Stub;
    }
}
