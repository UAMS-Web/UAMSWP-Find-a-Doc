<?php

/**
 * Helpers for the tests of the provider profile template (templates/single-physicians.php).
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/acf-functions.php';
require_once __DIR__.'/templates.php';

/**
 * Run the profile template for provider 5 and return the variables it set.
 *
 * The main loop finds no post, so only the code before the markup runs. The provider has the clinical title
 * `Cardiologist` unless the fields say otherwise.
 *
 * @param  array<string, mixed>  $fields  The ACF fields of the provider by name.
 * @param  array<string, mixed>  $options  The stubs: `terms` (id => name), `status` (id => post status), `titles`
 *                                         (id => title), `rules` (the ontology rules of the options page) and
 *                                         `term_fields` (term id => its ACF fields).
 * @return array<string, mixed>
 */
function single_physician(array $fields = [], array $options = []): array
{
    // The region rule helper lives in the ACF functions file.
    uamswp_test_acf_functions();
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
    require_once UAMS_FAD_PATH.'includes/class.fad-template-helpers.php';

    $fields += ['physician_title' => 10];
    $terms = ($options['terms'] ?? []) + [10 => 'Cardiologist'];
    $statuses = $options['status'] ?? [];
    $titles = $options['titles'] ?? [];
    $rules = $options['rules'] ?? [];
    $term_fields = $options['term_fields'] ?? [];
    $rows = ['remove_ontology_criteria' => $rules] + ($options['rows'] ?? []);
    WP_Query::$queue = $options['queries'] ?? [];
    WP_Query::$queries = [];
    $row = new stdClass;
    $row->cursor = [];
    $row->name = '';

    $location_fields = $options['location_fields'] ?? [];

    when('get_field')->alias(static function (string $name, mixed $id = null) use ($fields, $term_fields, $location_fields): mixed {
        if (is_object($id)) {
            return $term_fields[$id->term_id][$name] ?? null;
        }

        if (is_int($id) && isset($location_fields[$id])) {
            return $location_fields[$id][$name] ?? null;
        }

        return $fields[$name] ?? null;
    });
    when('get_term')->alias(static fn (mixed $id, string $taxonomy = ''): ?object => isset($terms[$id]) ? (object) ['name' => $terms[$id], 'term_id' => $id, 'slug' => $options['slugs'][$id] ?? 'slug-'.$id] : null);
    when('get_term_by')->alias(static fn (string $field, mixed $id, string $taxonomy): object|false => isset($terms[$id]) ? (object) ['name' => $terms[$id]] : false);
    when('get_post_status')->alias(static fn (mixed $id): string|false => $statuses[$id] ?? false);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Title '.$id);
    when('get_the_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('sanitize_title_with_dashes')->alias(static fn (string $title): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-'));
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
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
    when('format_phone_dash')->alias(static fn (mixed $phone): string => $phone ? 'dash:'.$phone : '');
    when('wp_get_post_parent_id')->justReturn(0);
    when('get_the_excerpt')->justReturn('An excerpt.');
    when('get_the_content')->justReturn('Content.');
    when('the_post_thumbnail')->alias(static function (): void {
        echo '<img class="thumb" />';
    });
    when('get_the_post_thumbnail')->justReturn('<img class="photo" />');
    when('wp_oembed_get')->alias(static fn (string $url): string => '<iframe src="'.$url.'"></iframe>');
    when('lyte_parse')->alias(static fn (string $url): string => 'lyte:'.$url);
    when('do_shortcode')->alias(static fn (string $code): string => 'shortcode:'.$code);
    when('esc_js')->returnArg();
    when('wp_reset_postdata')->justReturn();
    // Other tests define these functions for the rest of the process, so they are always defined here to keep the branches the same in any order.
    when('bis_get_attachment_image')->justReturn('');
    when('lyte_preparse')->justReturn();
    when('image_sizer')->alias(static fn (mixed $id, int $width, int $height): string => 'https://example.test/photo-'.$width.'x'.$height.'.jpg');
    when('get_header')->justReturn();
    when('get_footer')->justReturn();
    uamswp_test_main_loop([5]);
    when('has_post_thumbnail')->justReturn($options['thumbnail'] ?? false);
    when('get_post_thumbnail_id')->justReturn(77);
    when('get_the_date')->justReturn((string) ($options['image_year'] ?? date('Y')));
    when('get_post_meta')->justReturn($options['ratings'] ?? '');
    when('get_the_ID')->justReturn(5);
    when('esc_url')->returnArg();
    when('esc_attr')->returnArg();
    when('esc_html')->returnArg();
    when('uamswp_provider_name_html')->returnArg();
    when('wp_json_encode')->alias(static fn (mixed $value): string|false => json_encode($value));
    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');
    when('get_post')->alias(static fn (mixed $id): ?object => $options['posts'][$id] ?? null);
    when('get_field_object')->alias(static fn (string $name, mixed $id = null): mixed => $options['objects'][$name] ?? false);

    $post = new WP_Post;
    $post->ID = 5;

    // A render promotes its variables to globals, so the ones an earlier render left are cleared first.
    static $baseline = null;
    $baseline ??= array_keys($GLOBALS);

    foreach (array_keys($GLOBALS) as $name) {
        if (! in_array($name, $baseline, true)) {
            unset($GLOBALS[$name]);
        }
    }

    $GLOBALS['single_physician_warnings'] = [];
    set_error_handler(static function (int $level, string $message): bool {
        $GLOBALS['single_physician_warnings'][] = $message;

        return true;
    });

    try {
        $output = uamswp_test_render_template('single-physicians', ['post' => $post]);
    } finally {
        restore_error_handler();
    }

    $GLOBALS['single_physician_output'] = $output;

    return $GLOBALS;
}

/**
 * A rating response from the syndicated data, as the template reads it.
 */
function single_physician_rating(string $code = '200', int $count = 30, int $comments = 4, float $value = 4.5): object
{
    return (object) [
        'status' => (object) ['code' => $code],
        'data' => (object) ['entities' => [(object) [
            'totalRatingCount' => $count,
            'totalCommentCount' => $comments,
            'overallRating' => (object) ['value' => $value, 'questionRatings' => [
                (object) ['name' => 'Likelihood of recommending this provider', 'responseCount' => 3, 'value' => 4.5],
            ]],
            'comments' => [],
        ]]],
    ];
}
