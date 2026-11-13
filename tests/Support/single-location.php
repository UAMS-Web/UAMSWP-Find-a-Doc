<?php

/**
 * Helpers for the tests of the location profile template (templates/single-locations.php).
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/acf-functions.php';
require_once __DIR__.'/templates.php';

/**
 * Run the profile template for a new location and return the variables it set plus its output.
 *
 * @param  array<string, mixed>  $fields  The ACF fields of the location by name.
 * @param  array<string, mixed>  $options  The stubs: `fields_by_id` (post id => its ACF fields), `terms` (id => name),
 *                                         `titles` (id => title), `posts` (id => post object), `rows` (repeater
 *                                         name => rows), `queries` (the post lists the template's queries find, in
 *                                         order), `objects` (field name => field object), `slugs` (building id => slug), `thumbnail` (and `parent_thumbnail`, read through the source id), `pages`
 *                                         (the child pages found), `option` (the ACF fields of the options page) and
 *                                         `meta` (the post meta by key).
 * @return array<string, mixed>
 */
function single_location(array $fields = [], array $options = []): array
{
    // The address helpers live in the includes the page templates rely on.
    uamswp_test_acf_functions();
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
    require_once UAMS_FAD_PATH.'includes/class.fad-template-helpers.php';

    // The location source ids are cached per post id for the process, so every render is a new location.
    static $next_id = 100;
    $location_id = $options['id'] ?? $next_id++;

    $terms = $options['terms'] ?? [];
    $titles = $options['titles'] ?? [];
    $fields_by_id = $options['fields_by_id'] ?? [];
    $rows = $options['rows'] ?? [];
    $meta = $options['meta'] ?? [];
    WP_Query::$queue = $options['queries'] ?? [];
    WP_Query::$queries = [];
    $row = new stdClass;
    $row->cursor = [];
    $row->name = '';

    $option_fields = $options['option'] ?? [];

    when('get_field')->alias(static function (string $name, mixed $id = null) use ($fields, $fields_by_id, $option_fields): mixed {
        if ($id === 'option') {
            return $option_fields[$name] ?? null;
        }

        if (is_int($id) && isset($fields_by_id[$id])) {
            return $fields_by_id[$id][$name] ?? null;
        }

        return $fields[$name] ?? null;
    });
    when('get_field_object')->alias(static fn (string $name, mixed $id = null): mixed => $options['objects'][$name] ?? false);
    when('get_term')->alias(static function (mixed $id, string $taxonomy = '') use ($terms, $options): ?object {
        if (! isset($terms[$id])) {
            return null;
        }

        $term = new WP_Term;
        $term->name = $terms[$id];
        $term->term_id = $id;
        $term->slug = $options['slugs'][$id] ?? 'slug-'.$id;

        return $term;
    });
    when('get_post')->alias(static fn (mixed $id): ?object => $options['posts'][$id] ?? null);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Clinic');
    when('the_permalink')->alias(static function (): void {
        echo 'https://example.test/p/current/';
    });
    when('has_post_thumbnail')->justReturn(false);
    when('get_post_status')->justReturn('publish');
    when('get_the_ID')->justReturn($location_id);
    when('get_the_id')->justReturn($location_id);
    when('get_the_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    // Like WordPress, a read that is not single returns a list.
    when('get_post_meta')->alias(static fn (mixed $id, string $key = '', bool $single = false): mixed => $id === null ? '' : ($single ? ($meta[$key] ?? '') : (isset($meta[$key]) ? [$meta[$key]] : [])));
    // Called with no id it is the featured image of the location itself; called with the source id it is the parent's.
    when('get_post_thumbnail_id')->alias(static fn (mixed $id = null): mixed => $id === null ? ($options['thumbnail'] ?? 0) : ($options['parent_thumbnail'] ?? $options['thumbnail'] ?? 0));
    when('wp_get_attachment_image_src')->alias(static fn (mixed $id, string $size = ''): array|false => $id ? ['https://example.test/img-'.$id.'.jpg', 640, 480] : false);
    when('wp_get_attachment_image_url')->alias(static fn (mixed $id, string $size = ''): string => 'https://example.test/img-'.$id.'-'.$size.'.jpg');
    when('image_sizer')->alias(static fn (mixed $id, int $width, int $height): string => 'https://example.test/photo-'.$id.'-'.$width.'x'.$height.'.jpg');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_json_encode')->alias(static fn (mixed $value): string|false => json_encode($value));
    when('wp_oembed_get')->alias(static fn (string $url): string => '<iframe src="'.$url.'"></iframe>');
    when('wp_reset_postdata')->alias(static function (): void {
        $GLOBALS['single_location_resets'] = ($GLOBALS['single_location_resets'] ?? 0) + 1;
    });
    when('get_pages')->alias(static function (array $args) use ($options): array {
        $GLOBALS['single_location_pages_args'] = $args;

        return $options['pages'] ?? [];
    });
    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');
    when('format_phone_dash')->alias(static fn (mixed $phone): string => $phone ? 'dash:'.$phone : '');
    when('esc_url')->returnArg();
    when('esc_attr')->returnArg();
    when('esc_html')->returnArg();
    when('esc_js')->returnArg();
    when('uamswp_provider_name_html')->returnArg();
    when('wp_get_post_parent_id')->justReturn(0);
    when('get_the_excerpt')->justReturn('An excerpt.');
    when('get_the_content')->justReturn('Content.');
    when('the_post_thumbnail')->alias(static function (): void {
        echo '<img class="thumb" />';
    });
    when('get_the_post_thumbnail')->justReturn('<img class="photo" />');
    // Other tests define these functions for the rest of the process, so they are always defined here to keep the branches the same in any order.
    when('bis_get_attachment_image')->justReturn('');
    when('lyte_preparse')->justReturn();
    when('lyte_parse')->alias(static fn (string $url): string => 'lyte:'.$url);
    when('do_shortcode')->alias(static fn (string $code): string => 'shortcode:'.$code);
    when('get_header')->alias(static function (): void {
        $GLOBALS['single_location_headers'] = ($GLOBALS['single_location_headers'] ?? 0) + 1;
    });
    when('get_footer')->alias(static function (): void {
        $GLOBALS['single_location_footers'] = ($GLOBALS['single_location_footers'] ?? 0) + 1;
    });
    when('have_rows')->alias(static function (string $name) use ($row, $rows): bool {
        $row->name = $name;
        $row->cursor[$name] ??= -1;

        if (count($rows[$name] ?? []) > $row->cursor[$name] + 1) {
            return true;
        }

        // Like ACF, a finished loop starts again the next time it is asked.
        $row->cursor[$name] = -1;

        return false;
    });
    when('the_row')->alias(static function () use ($row): void {
        $row->cursor[$row->name]++;
    });
    when('get_sub_field')->alias(static fn (string $name): mixed => $rows[$row->name][$row->cursor[$row->name]][$name] ?? null);
    uamswp_test_main_loop([$location_id]);

    $post = new WP_Post;
    $post->ID = $location_id;
    $post->post_name = 'clinic-slug';

    // A render promotes its variables to globals, so the ones an earlier render left are cleared first.
    static $baseline = null;
    $baseline ??= array_keys($GLOBALS);

    foreach (array_keys($GLOBALS) as $name) {
        if (! in_array($name, $baseline, true)) {
            unset($GLOBALS[$name]);
        }
    }

    $GLOBALS['single_location_warnings'] = [];
    set_error_handler(static function (int $level, string $message): bool {
        $GLOBALS['single_location_warnings'][] = $message;

        return true;
    });

    try {
        $output = uamswp_test_render_template('single-locations', ['post' => $post]);
    } finally {
        restore_error_handler();
    }

    $GLOBALS['single_location_output'] = $output;
    $GLOBALS['single_location_id'] = $location_id;
    $GLOBALS['single_location_rows'] = $row->cursor;

    return $GLOBALS;
}
