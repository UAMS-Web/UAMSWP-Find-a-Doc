<?php

/**
 * Loader and world builder for the tests of includes/class.fad-gmb-settings-page.php.
 *
 * The file registers one hook as it loads, so it is loaded once from the first test that asks for it, with
 * add_action() stubbed. The table builders read posts, fields, terms and a WP_Query; gmb_world() stubs all
 * of them from one description so a test states the data and not the functions.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\ExitCalled;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/acf-functions.php';
require_once __DIR__.'/functions-file.php';
require_once __DIR__.'/wp-classes.php';

/**
 * Load the settings page file and the files its table builders call into, once.
 */
function uamswp_test_gmb_page(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    // The functions file supplies the location source ids and the name helpers.
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';

    // What the file hooks as it loads, for the test that loads it to assert.
    $GLOBALS['uamswp_test_gmb_page_actions'] = [];
    when('add_action')->alias(static function (string $hook, string $callback, int $priority = 10): void {
        $GLOBALS['uamswp_test_gmb_page_actions'][] = [$hook, $callback, $priority];
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-gmb-settings-page.php';

    // Whichever test loads the file first is the one a mutation run credits with the hook the file adds as it
    // loads, so the load itself fails when that hook is missing. GmbExportHooksTest pins the hook exactly.
    if (! in_array(['admin_menu', 'uamswp_add_gmb_export_page', 105], $GLOBALS['uamswp_test_gmb_page_actions'], true)) {
        throw new RuntimeException('Loading includes/class.fad-gmb-settings-page.php did not hook the export page on admin_menu.');
    }

    $loaded = true;
}

/**
 * The next post id for a test, so the per-request location source cache never carries one test's data to another.
 */
function gmb_next_id(): int
{
    static $id = 7000;

    return ++$id;
}

/**
 * Stub the WordPress reads the table builders make from one description of the world.
 *
 * @param  array{posts?: array<int, array<string, mixed>>, fields?: array<string, mixed>, objects?: array<string, mixed>, terms?: array<string, string>, thumbs?: array<int, int>}  $world
 *                                                                                                                                                                                          posts: id => slug, title, status (default publish), parent_title.
 *                                                                                                                                                                                          fields: "name|id" or "name" => value, read by get_field().
 *                                                                                                                                                                                          objects: "name|id" => value, read by get_field_object().
 *                                                                                                                                                                                          terms: "taxonomy:id" => name (a slug is the name in lower case with dashes).
 *                                                                                                                                                                                          thumbs: post id => thumbnail id.
 */
function gmb_world(array $world): void
{
    $posts = $world['posts'] ?? [];
    $fields = $world['fields'] ?? [];
    $objects = $world['objects'] ?? [];
    $terms = $world['terms'] ?? [];
    $thumbs = $world['thumbs'] ?? [];

    when('get_field')->alias(static fn (string $name, mixed $post = false): mixed => $fields[$name.'|'.(is_scalar($post) ? (string) $post : '')] ?? $fields[$name] ?? null);
    when('get_field_object')->alias(static fn (string $name, mixed $post = false): mixed => $objects[$name.'|'.(is_scalar($post) ? (string) $post : '')] ?? false);
    when('get_post_field')->alias(static fn (string $field, int $id): string => (string) ($posts[$id]['slug'] ?? ''));
    when('get_the_title')->alias(static fn (int $id = 0): string => (string) ($posts[$id]['title'] ?? ''));
    when('get_post_status')->alias(static fn (int $id): string|false => isset($posts[$id]) ? (string) ($posts[$id]['status'] ?? 'publish') : false);
    when('get_post')->alias(static fn (int $id): ?object => isset($posts[$id]) ? (object) ['ID' => $id, 'post_title' => (string) ($posts[$id]['title'] ?? '')] : null);
    when('get_post_thumbnail_id')->alias(static fn (int $id): int => $thumbs[$id] ?? 0);
    when('get_term')->alias(static function (int $id, string $taxonomy) use ($terms): ?object {
        $name = $terms[$taxonomy.':'.$id] ?? null;

        return $name === null ? null : (object) ['name' => $name, 'slug' => strtolower(str_replace(' ', '-', $name))];
    });
    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('wp_reset_postdata')->justReturn();
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('absint')->alias(static fn (mixed $value): int => abs((int) $value));
    when('wp_get_attachment_image_url')->alias(static fn (int $id, string $size): string|false => $id ? 'https://img.test/'.$id.'-'.$size.'.jpg' : false);
    when('sanitize_title_with_dashes')->alias(static fn (string $text): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-'));
}

/**
 * Run a table builder over posts the query will find, and return its result.
 *
 * @param  array<int, int>  $ids
 * @return array{head: array<int, mixed>, body: array<int, array<int, mixed>>}
 */
function gmb_build(string $builder, array $ids): array
{
    WP_Query::$queue = [$ids];
    WP_Query::$queries = [];

    return $builder();
}

/**
 * Run an export as an administrator in WP-Admin with the given nonce, and return how it ended.
 *
 * @param  list<int>  $providers  The provider ids the export query finds.
 * @return array{exit: string|null, output: string, headers: list<string>} The message the request ended with
 *                                                                         (null when it did not end), what it
 *                                                                         printed and the headers it sent.
 */
function gmb_export_run(string $export, string $nonce, array $providers = []): array
{
    uamswp_test_gmb_page();
    $_GET = ['_wpnonce' => $nonce];
    when('current_user_can')->justReturn(true);
    when('is_admin')->justReturn(true);
    when('wp_verify_nonce')->alias(static fn (string $given, string $action): int|false => $given === 'valid-'.$action ? 1 : false);

    $headers = [];
    when('header')->alias(static function (string $header) use (&$headers): void {
        $headers[] = $header;
    });
    WP_Query::$queue = [$providers];
    WP_Query::$queries = [];
    $exit = null;

    // Any error raised while the export runs fails the test: this handler replaces PHPUnit's for as long as it is
    // set, so it reports them itself.
    set_error_handler(static function (int $level, string $message, string $file = '', int $line = 0): bool {
        throw new ErrorException($message, 0, $level, $file, $line);
    });
    ob_start();
    try {
        $export();
    } catch (ExitCalled $exitCalled) {
        $exit = $exitCalled->getMessage();
    } finally {
        $output = (string) ob_get_clean();
        restore_error_handler();
    }

    return ['exit' => $exit, 'output' => $output, 'headers' => $headers];
}
