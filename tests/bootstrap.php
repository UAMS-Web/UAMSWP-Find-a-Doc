<?php

/**
 * Test bootstrap (PHPUnit path, PHP 7.4-safe).
 *
 * The Unit suite runs with no WordPress: WordPress functions are mocked per
 * test with Brain Monkey. The Integration suite boots a real WordPress through
 * wp-phpunit (tests/Integration/bootstrap.php). Each suite runs in its own
 * process, and this file decides which one it is in.
 */

declare(strict_types=1);

/*
 * Runs at most once. phpunit.xml's `bootstrap` loads this file.
 */
if (defined('UAMSWP_FIND_A_DOC_TESTS_BOOTSTRAPPED')) {
    return;
}

define('UAMSWP_FIND_A_DOC_TESTS_BOOTSTRAPPED', true);

require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * Whether this process runs the Integration suite.
 *
 * True for `--testsuite=Integration` (either spelling) and for a run naming a
 * path under tests/Integration. Anything else is the Unit suite.
 *
 * @param  array  $argv
 * @return bool
 */
function uamswp_find_a_doc_tests_want_wordpress(array $argv)
{
    foreach ($argv as $index => $argument) {
        if (! is_string($argument)) {
            continue;
        }

        if ($argument === '--testsuite=Integration') {
            return true;
        }

        if ($argument === '--testsuite' && ($argv[$index + 1] ?? null) === 'Integration') {
            return true;
        }

        if (strpos(str_replace('\\', '/', $argument), 'tests/Integration') !== false) {
            return true;
        }
    }

    return false;
}

$argv = $_SERVER['argv'] ?? [];

if (uamswp_find_a_doc_tests_want_wordpress(is_array($argv) ? $argv : [])) {
    require __DIR__.'/Integration/bootstrap.php';

    return;
}

/*
 * The Unit suite.
 *
 * Patchwork first: it can only redefine functions declared in files loaded
 * after it, so loading it before the plugin is what lets a test mock one of the
 * plugin's own functions, not only WordPress'.
 */
require_once dirname(__DIR__).'/vendor/antecedent/patchwork/Patchwork.php';

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
 * Load the files that DEFINE functions and classes, never the main plugin file:
 * it registers hooks at file scope, and Brain Monkey's add_action() and
 * add_filter() exist only inside a test. Hook registration is tested in the
 * Integration suite, or in a unit test that calls a registering function.
 * Composer-autoloaded classes need no line here.
 *
 * When the main file must load in the Unit suite (definitions and registrations
 * still mixed), wrap that require in a Brain Monkey session and stub every
 * WordPress function it calls at file scope; see the tests skill.
 */

/*
 * Definitions and registrations still mixed in the main file: load it inside
 * one Brain Monkey session with load-time WordPress calls stubbed. Functions
 * the file defines outlive the session; hooks it registered are discarded by
 * tearDown(). Do not define permanent WordPress function stubs in this file —
 * Patchwork treats this bootstrap as too early and Brain Monkey cannot
 * redefine them in tests (DefinedTooEarly).
 */
Brain\Monkey\setUp();

Brain\Monkey\Functions\when('plugin_dir_path')->justReturn(dirname(__DIR__).'/');
Brain\Monkey\Functions\when('plugin_dir_url')->justReturn('https://example.test/wp-content/plugins/'.basename(dirname(__DIR__)).'/');
Brain\Monkey\Functions\when('plugin_basename')->alias(static function ($file) {
    return basename(dirname($file)).'/'.basename($file);
});
Brain\Monkey\Functions\when('is_multisite')->justReturn(false);
Brain\Monkey\Functions\when('get_site_option')->alias(static function ($key, $default = false) {
    return $default;
});
Brain\Monkey\Functions\when('get_option')->alias(static function ($key, $default = false) {
    return $default;
});
Brain\Monkey\Functions\when('update_option')->justReturn(null);
Brain\Monkey\Functions\when('register_activation_hook')->justReturn(null);
Brain\Monkey\Functions\when('register_deactivation_hook')->justReturn(null);
Brain\Monkey\Functions\when('register_uninstall_hook')->justReturn(null);
Brain\Monkey\Functions\when('load_plugin_textdomain')->justReturn(null);
Brain\Monkey\Functions\when('is_admin')->justReturn(false);
Brain\Monkey\Functions\when('wp_enqueue_script')->justReturn(null);
Brain\Monkey\Functions\when('wp_enqueue_style')->justReturn(null);
Brain\Monkey\Functions\when('wp_register_script')->justReturn(null);
Brain\Monkey\Functions\when('wp_register_style')->justReturn(null);
Brain\Monkey\Functions\when('wp_localize_script')->justReturn(null);
Brain\Monkey\Functions\when('esc_html')->returnArg();
Brain\Monkey\Functions\when('esc_attr')->returnArg();
Brain\Monkey\Functions\when('esc_url')->returnArg();
Brain\Monkey\Functions\when('__')->returnArg();
Brain\Monkey\Functions\when('_e')->justReturn(null);
Brain\Monkey\Functions\when('esc_html__')->returnArg();
Brain\Monkey\Functions\when('esc_attr__')->returnArg();
Brain\Monkey\Functions\when('get_file_data')->justReturn(array());

if (is_readable(dirname(__DIR__).'/uamswp-find-a-doc.php')) {
    require_once dirname(__DIR__).'/uamswp-find-a-doc.php';
}

Brain\Monkey\tearDown();


// cspell:ignore ABSPATH autoloaded
