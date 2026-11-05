<?php

/**
 * Unit bootstrap.
 *
 * The Unit suite runs with no WordPress: WordPress functions are mocked per
 * test with Brain Monkey. The Integration suite does not come through here; it
 * has its own Composer setup and bootstrap under tests/Integration.
 */

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

/*
 * Patchwork first: it can only redefine functions declared in files loaded
 * after it, so loading it before the plugin is what lets a test mock one of the
 * plugin's own functions, not only WordPress'.
 */
require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

/*
 * Plugin files open with `defined('ABSPATH') || exit;`. Define it so they load,
 * pointing at a directory that is not a WordPress install: code that requires a
 * core file through ABSPATH fails loudly here, which is the sign it belongs in
 * the Integration suite.
 */
if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/uamswp_find_a_doc-tests-no-wordpress/');
}

/*
 * The main plugin file opens with `if (! defined('WPINC')) { exit; }`. Left undefined, it
 * exits silently during the bootstrap: the run then prints nothing, runs no test and
 * exits 0, which reads as a pass. The main file also requires required-plugins.php, which
 * pulls in WordPress admin classes through ABSPATH, so it never loads here. Define the
 * constants it would set; anything that needs the main file belongs in the Integration suite.
 */
if (! defined('WPINC')) {
    define('WPINC', 'wp-includes');
}

define('UAMS_FAD_ROOT_URL', 'https://example.test/wp-content/plugins/'.basename(dirname(__DIR__)).'/');
define('UAMS_FAD_PATH', dirname(__DIR__).'/');
define('UAMS_FAD_VERSION', '0.0.0-tests');

/*
 * Load the files that DEFINE functions and classes, never the main plugin file:
 * it registers hooks at file scope, and Brain Monkey's add_action() and
 * add_filter() exist only inside a test. Hook registration is tested in the
 * Integration suite, or in a unit test that calls a registering function.
 * Composer-autoloaded classes need no line here.
 */

defined('DAY_IN_SECONDS') || define('DAY_IN_SECONDS', 86400);

require_once dirname(__DIR__).'/includes/class.fad-gbp-logic.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-client.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-sync.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-runtime.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-settings.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-cli.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-dashboard.php';
require_once dirname(__DIR__).'/includes/class.fad-gbp-orphans.php';

/*
 * The delete cleanup registers its hooks as it loads. Load it here, inside Brain Monkey's
 * set-up and tear-down, so those calls are made while add_action() exists and are forgotten
 * before the first test; loaded from inside a test, they would count as that test's
 * expectations. The tests call the registering function again to read what it registers.
 */
Brain\Monkey\setUp();

require_once dirname(__DIR__).'/includes/class.fad-delete-cleanup.php';

Brain\Monkey\tearDown();

// cspell:ignore ABSPATH autoloaded
