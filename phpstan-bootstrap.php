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
