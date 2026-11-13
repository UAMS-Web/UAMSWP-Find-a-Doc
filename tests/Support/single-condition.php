<?php

/**
 * Helpers for the tests of the condition template (templates/single-condition.php).
 *
 * The treatment template (templates/single-treatment.php) has the same shape with other field names, so the
 * render takes the template name and every field by its full name.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/acf-blocks.php';
require_once __DIR__.'/templates.php';

/**
 * Run a condition or treatment template and return the variables it set plus its output.
 *
 * The template builds its queries in this order, and `queries` lists what each one finds: the clinical
 * resources, the locations, the locations again in the region filter (only with published locations and a
 * region filter), the treatments (the conditions on the treatment template), the providers (only with
 * providers set), the areas of expertise, and the providers again in the region filter (only with providers
 * found and a region filter).
 *
 * @param  string  $template  The template under templates/, without the extension.
 * @param  array<string, mixed>  $fields  The ACF fields of the page by name.
 * @param  array<string, mixed>  $options  `title` (the page title), `excerpt` and `content` (of the page),
 *                                         `post_name`, `option` (the ACF fields of the options page),
 *                                         `fields_by_id` (post id => its ACF fields), `titles` (post id =>
 *                                         title), `status` (post id => status, default publish), `regions`
 *                                         (region term id => slug), `queries` (see above), `get` and `cookie`
 *                                         (the request's query and cookies), and `lyte` (whether the lite
 *                                         YouTube plugin is active).
 * @return array<string, mixed>
 */
function uamswp_test_single_ontology(string $template, array $fields = [], array $options = []): array
{
    // The CTA button helper lives in the ACF blocks file, which must load before anything stubs its functions.
    uamswp_test_acf_blocks_file();
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';

    $option_fields = $options['option'] ?? [];
    $fields_by_id = $options['fields_by_id'] ?? [];
    $titles = [0 => $options['title'] ?? 'Asthma'] + ($options['titles'] ?? []);
    $status = $options['status'] ?? [];
    $regions = $options['regions'] ?? [];
    $content = $options['content'] ?? '';
    WP_Query::$queue = $options['queries'] ?? [];
    WP_Query::$found = [];
    WP_Query::$queries = [];
    WP_Query::$current = 0;
    $calls = new stdClass;
    $calls->status = [];
    $calls->headers = 0;
    $calls->footers = 0;
    $calls->resets = 0;

    when('get_field')->alias(static function (string $name, mixed $id = null) use ($fields, $fields_by_id, $option_fields): mixed {
        if ($id === 'option') {
            return $option_fields[$name] ?? null;
        }

        if ($id === null) {
            return $fields[$name] ?? null;
        }

        return is_int($id) ? ($fields_by_id[$id][$name] ?? null) : null;
    });
    when('get_field_object')->justReturn(false);
    when('have_rows')->justReturn(false);
    when('get_sub_field')->justReturn();
    when('get_term')->justReturn();
    when('get_term_by')->alias(static fn (string $field, mixed $id, string $taxonomy = ''): object => (object) ['term_id' => $id, 'slug' => $regions[$id] ?? 'region-'.$id]);
    when('get_post')->justReturn();
    when('wp_get_post_parent_id')->justReturn(0);
    when('get_the_title')->alias(static fn (mixed $id = null): string => $titles[$id ?? WP_Query::$current] ?? 'Post '.$id);
    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('get_post_status')->alias(static function (mixed $id) use ($calls, $status): string {
        $calls->status[] = $id;

        return $status[$id] ?? 'publish';
    });
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('the_permalink')->alias(static function (mixed $id = 0): void {
        echo 'https://example.test/p/'.$id.'/';
    });
    when('has_post_thumbnail')->justReturn(false);
    when('get_post_thumbnail_id')->justReturn(0);
    when('get_the_post_thumbnail')->justReturn('');
    when('get_post_meta')->justReturn('');
    when('get_the_excerpt')->justReturn($options['excerpt'] ?? '');
    when('get_the_content')->justReturn($content);
    when('the_content')->alias(static function () use ($content): void {
        echo $content;
    });
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->returnArg();
    when('wp_oembed_get')->alias(static fn (string $url): string => '<iframe src="'.$url.'"></iframe>');
    when('wp_reset_postdata')->alias(static function () use ($calls): void {
        $calls->resets++;
    });
    when('do_shortcode')->returnArg();
    when('esc_url')->returnArg();
    when('esc_attr')->returnArg();
    when('esc_html')->returnArg();
    when('esc_js')->alias(static fn (string $text): string => addslashes($text));
    when('format_phone_dash')->alias(static fn (mixed $phone): string => $phone ? 'dash:'.$phone : '');
    when('format_phone_us')->alias(static fn (mixed $phone): string => $phone ? 'us:'.$phone : '');
    when('display_medline_api_data')->alias(static function (string $code, string $type): void {
        echo '<medline code="'.$code.'" type="'.$type.'"/>';
    });
    when('lyte_parse')->alias(static fn (string $url): string => 'lyte:'.$url);
    if ($options['lyte'] ?? false) {
        when('lyte_preparse')->justReturn();
    }

    when('get_header')->alias(static function () use ($calls): void {
        $calls->headers++;
    });
    when('get_footer')->alias(static function () use ($calls): void {
        $calls->footers++;
    });

    // A render promotes its variables to globals, so the ones an earlier render left are cleared first.
    static $baseline = null;
    $baseline ??= array_keys($GLOBALS);

    foreach (array_keys($GLOBALS) as $name) {
        if (! in_array($name, $baseline, true)) {
            unset($GLOBALS[$name]);
        }
    }

    $saved = [$_GET, $_COOKIE];
    $_GET = $options['get'] ?? [];
    $_COOKIE = $options['cookie'] ?? [];

    // The warnings each file raises, with where it raised them, so a test can tell the template's from a partial's.
    $warnings = [];
    set_error_handler(static function (int $level, string $message, string $file = '', int $line = 0) use (&$warnings): bool {
        $warnings[] = [basename($file), $line, $message];

        return true;
    });

    $post = (object) ['ID' => 7, 'post_name' => $options['post_name'] ?? 'asthma'];

    try {
        $output = uamswp_test_render_template($template, ['post' => $post]);
    } finally {
        restore_error_handler();
        [$_GET, $_COOKIE] = $saved;
    }

    $GLOBALS['single_ontology_output'] = $output;
    $GLOBALS['single_ontology_warnings'] = $warnings;
    $GLOBALS['single_ontology_status_calls'] = $calls->status;
    $GLOBALS['single_ontology_headers'] = $calls->headers;
    $GLOBALS['single_ontology_footers'] = $calls->footers;
    $GLOBALS['single_ontology_resets'] = $calls->resets;
    $GLOBALS['single_ontology_queries'] = WP_Query::$queries;

    return $GLOBALS;
}

/**
 * The warnings a render raised in the given file.
 *
 * @param  array<string, mixed>  $vars  What uamswp_test_single_ontology() returned.
 * @return list<string> The message of each warning, in order.
 */
function uamswp_test_single_ontology_warnings(array $vars, string $file): array
{
    $warnings = [];

    foreach ($vars['single_ontology_warnings'] as [$name, , $message]) {
        if ($name === $file) {
            $warnings[] = $message;
        }
    }

    return $warnings;
}

/**
 * Collapse the whitespace of rendered markup: runs of whitespace become one space, and none is left next to a tag.
 */
function uamswp_test_single_ontology_markup(string $output): string
{
    return (string) preg_replace('/\s*(<[^>]+>)\s*/', '$1', (string) preg_replace('/\s+/', ' ', $output));
}
