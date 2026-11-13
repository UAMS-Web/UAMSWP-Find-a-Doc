<?php

/**
 * The small ACF filters in includes/class.fad-acf-functions.php: the JSON load point, the portal and region
 * defaults, the taxonomy ordering, the FacetWP re-index, the settings messages, the parent-page query, the
 * image aspect-ratio setting and validation, the PubMed value, the relationship exclusion, the MyChart
 * scheduling condition and the brand organization choices.
 */

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/wp-classes.php';

/**
 * Stub get_field() from a map of option field names.
 *
 * @param  array<string, mixed>  $options
 */
function acf_filters_options(array $options): void
{
    when('get_field')->alias(static fn (string $name, mixed $post = false): mixed => $options[$name] ?? null);
}

test('the load point appends the plugin json folder after the existing paths', function (): void {
    uamswp_test_acf_functions();

    $paths = uamswp_fad_json_load_point(['/theme/acf-json']);

    expect($paths)->toHaveCount(2)
        ->and($paths[0])->toBe('/theme/acf-json')
        ->and($paths[1])->toBe(WP_PLUGIN_DIR.'/'.basename(rtrim(UAMS_FAD_PATH, '/\\')).'/assets/json/acf-json');
});

test('the portal default is the MyChart term when no value is set', function (mixed $empty): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->once()->with('slug', 'uams-mychart', 'portal')->andReturn((object) ['term_id' => 9]);

    expect(set_default_portal(['key' => 'field_physician_portal', 'value' => $empty])['value'])->toBe([9]);
})->with([
    'null' => [null],
    'empty list' => [[]],
    'empty string' => [''],
]);

test('the portal default is term id zero when the MyChart term is missing', function (): void {
    uamswp_test_acf_functions();
    when('get_term_by')->justReturn(false);

    expect(set_default_portal([])['value'])->toBe([0]);
});

test('a portal that already has a value is left alone', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->never();

    $field = ['value' => [4]];

    expect(set_default_portal($field))->toBe($field);
});

test('the region default is the central term only when the value is null', function (): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->once()->with('slug', 'central', 'region')->andReturn((object) ['term_id' => 3]);

    expect(set_default_region(null, 10, []))->toBe([3]);
});

test('the region default is term id zero when the central term is missing', function (): void {
    uamswp_test_acf_functions();
    when('get_term_by')->justReturn(false);

    expect(set_default_region(null, 10, []))->toBe([0]);
});

test('a region value that is not null is returned untouched', function (mixed $value): void {
    uamswp_test_acf_functions();
    Functions\expect('get_term_by')->never();

    expect(set_default_region($value, 10, []))->toBe($value);
})->with([
    'an empty list' => [[]],
    'a list' => [[2]],
    'an empty string' => [''],
    'zero' => [0],
]);

test('the taxonomy query orders by slug ascending and keeps the other arguments', function (): void {
    uamswp_test_acf_functions();

    expect(my_taxonomy_query(['hide_empty' => false, 'orderby' => 'name', 'order' => 'DESC'], []))
        ->toBe(['hide_empty' => false, 'orderby' => 'slug', 'order' => 'ASC']);
});

test('saving a post asks FacetWP to index it when FacetWP is active', function (): void {
    uamswp_test_acf_functions();
    $indexed = [];
    $indexer = new class($indexed)
    {
        public function __construct(private array &$indexed) {}

        public function index(mixed $id): void
        {
            $this->indexed[] = $id;
        }
    };
    when('FWP')->justReturn((object) ['indexer' => $indexer]);

    update_facetwp_index(77);

    expect($indexed)->toBe([77]);
});

test('the location alert message shows the title, body and color', function (): void {
    uamswp_test_acf_functions();
    acf_filters_options([
        'location_alert_heading_system' => 'Closed',
        'location_alert_body_system' => '<p>Snow day</p>',
        'location_alert_color_system' => 'alert-warning',
    ]);

    ob_start();
    location_current_alert_message();
    $html = (string) ob_get_clean();

    expect($html)->toBe(
        '<blockquote class="notice notice-warning"><h3 class="notice-title">Closed</h3><p>Snow day</p><hr /><p><strong>Alert color:</strong> Warning</p></blockquote>'
    );
});

test('the location alert message says none is active unless both a title and a body are set', function (string $title, string $body): void {
    uamswp_test_acf_functions();
    acf_filters_options(['location_alert_heading_system' => $title, 'location_alert_body_system' => $body]);

    ob_start();
    location_current_alert_message();

    expect((string) ob_get_clean())->toBe('None active');
})->with([
    'no title' => ['', 'Body'],
    'no body' => ['Title', ''],
    'neither' => ['', ''],
]);

test('the prescription messages show the system text or say none is active', function (string $function, string $option): void {
    uamswp_test_acf_functions();

    acf_filters_options([$option => 'Call the clinic']);
    ob_start();
    $function();
    $shown = (string) ob_get_clean();

    acf_filters_options([$option => '']);
    ob_start();
    $function();
    $none = (string) ob_get_clean();

    expect($shown)->toBe('<blockquote class="notice notice-info">Call the clinic</blockquote>')
        ->and($none)->toBe('None active');
})->with([
    'clinic' => ['location_current_prescription_clinic_message', 'location_prescription_clinic_system'],
    'pharmacy' => ['location_current_prescription_pharm_message', 'location_prescription_pharm_system'],
]);

test('the parent page query is limited to published top-level pages', function (): void {
    uamswp_test_acf_functions();

    expect(limit_post_top_level(['s' => 'x', 'post_status' => 'any'], [], 5))
        ->toBe(['s' => 'x', 'post_status' => 'publish', 'post_parent' => 0]);
});

test('the aspect ratio settings render width, height and margin number fields', function (): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();
    $rendered = [];
    when('acf_render_field_setting')->alias(static function (array $field, array $args) use (&$rendered): void {
        $rendered[] = [$field, $args];
    });

    acf_image_aspect_ratio_settings(['key' => 'field_x']);

    expect($rendered)->toHaveCount(3)
        ->and(array_column(array_column($rendered, 1), 'name'))->toBe(['ratio_width', 'ratio_height', 'ratio_margin'])
        ->and($rendered[0][0])->toBe(['key' => 'field_x'])
        ->and($rendered[0][1]['label'])->toBe('Aspect Ratio')
        ->and($rendered[0][1]['prepend'])->toBe('Width')
        ->and($rendered[0][1]['step'])->toBe(1)
        ->and($rendered[1][1]['label'])->toBe('')
        ->and($rendered[1][1]['prepend'])->toBe('Height')
        ->and($rendered[1][1]['wrapper']['data-append'])->toBe('ratio_width')
        ->and($rendered[2][1]['step'])->toBe(.5)
        ->and($rendered[2][1]['append'])->toBe('%')
        ->and($rendered[2][1]['wrapper']['data-append'])->toBe('ratio_width');
});

test('the aspect ratio validation passes when a ratio or a dimension is missing or zero', function (array $field, array $file): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();

    expect(acf_image_aspect_ratio_validate(['kept' => 'yes'], $file, [], $field, ''))->toBe(['kept' => 'yes']);
})->with([
    'no ratio width' => [['ratio_height' => 3], ['width' => 400, 'height' => 100]],
    'no ratio height' => [['ratio_width' => 4], ['width' => 400, 'height' => 100]],
    'ratio width zero' => [['ratio_width' => 0, 'ratio_height' => 3], ['width' => 400, 'height' => 100]],
    'ratio width text' => [['ratio_width' => 'abc', 'ratio_height' => 3], ['width' => 400, 'height' => 100]],
    'ratio height text' => [['ratio_width' => 4, 'ratio_height' => 'abc'], ['width' => 400, 'height' => 100]],
    'no file width' => [['ratio_width' => 4, 'ratio_height' => 3], ['height' => 100]],
    'no file height' => [['ratio_width' => 4, 'ratio_height' => 3], ['width' => 400]],
]);

test('the aspect ratio validation accepts an image of exactly the ratio', function (): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();

    expect(acf_image_aspect_ratio_validate([], ['width' => 800, 'height' => 600], [], ['ratio_width' => 4, 'ratio_height' => 3], ''))->toBe([]);
});

test('the aspect ratio validation rejects an image outside the ratio with a message naming the ratio', function (): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();

    $errors = acf_image_aspect_ratio_validate(['other' => 'e'], ['width' => 800, 'height' => 700], [], ['ratio_width' => 4, 'ratio_height' => 3], '');

    expect($errors)->toBe(['other' => 'e', 'aspect_ratio' => 'Image does not meet Aspect Ratio Requirements of 4:3 (±0%)']);
});

test('the margin widens the allowed height on both sides, rounded to whole pixels', function (int $height, bool $accepted): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();
    $field = ['ratio_width' => 4, 'ratio_height' => 3, 'ratio_margin' => 1];

    $errors = acf_image_aspect_ratio_validate([], ['width' => 800, 'height' => $height], [], $field, '');

    expect(isset($errors['aspect_ratio']))->toBe(! $accepted);
})->with([
    'below the minimum' => [593, false],
    'the minimum' => [594, true],
    'the exact ratio' => [600, true],
    'the maximum' => [606, true],
    'above the maximum' => [607, false],
]);

test('the aspect ratio message carries the margin percentage', function (): void {
    uamswp_test_acf_functions();
    stubTranslationFunctions();
    $field = ['ratio_width' => 1, 'ratio_height' => 1, 'ratio_margin' => 10];

    $errors = acf_image_aspect_ratio_validate([], ['width' => 100, 'height' => 50], [], $field, '');

    expect($errors['aspect_ratio'])->toBe('Image does not meet Aspect Ratio Requirements of 1:1 (±10%)');
});

test('the PubMed value is decoded from HTML entities', function (): void {
    uamswp_test_acf_functions();

    expect(pubmed_information_format_value('&lt;b&gt;Smith &amp; Jones&lt;/b&gt;', 1, []))->toBe('<b>Smith & Jones</b>')
        ->and(pubmed_information_format_value(null, 1, []))->toBe('');
});

test('a relationship query excludes the current post', function (): void {
    uamswp_test_acf_functions();

    expect(relationship_exclude_id(['s' => 'a'], [], 12))->toBe(['s' => 'a', 'post__not_in' => [12]]);
});

test('the MyChart scheduling tab shows only when the system option is on', function (): void {
    uamswp_test_acf_functions();
    $field = ['key' => 'field_location_scheduling_tab'];

    acf_filters_options(['mychart_scheduling_query_system' => 1]);
    $on = uamswp_mychart_scheduling_query($field);

    acf_filters_options(['mychart_scheduling_query_system' => 0]);
    $off = uamswp_mychart_scheduling_query($field);

    expect($on)->toBe($field)
        ->and($off)->toBeNull();
});

test('the brand organization loaders add each term as a choice keyed by slug', function (string $function, string $taxonomy): void {
    uamswp_test_acf_functions();
    $term = static fn (string $slug, string $name): object => (object) ['slug' => $slug, 'name' => $name];
    Functions\expect('get_terms')->once()->with(['taxonomy' => $taxonomy, 'hide_empty' => false])->andReturn([$term('uams', 'UAMS'), $term('uams-health', 'UAMS Health')]);
    when('is_wp_error')->justReturn(false);

    expect($function(['choices' => ['existing' => 'Existing']])['choices'])
        ->toBe(['existing' => 'Existing', 'uams' => 'UAMS', 'uams-health' => 'UAMS Health']);
})->with([
    'uams' => ['uamswp_fad_acf_load_brandorg_uams', 'brand_organization_uams'],
    'third party' => ['uamswp_fad_acf_load_brandorg_3p', 'brand_organization'],
]);

test('the brand organization loaders start the choices when the field has none', function (string $function): void {
    uamswp_test_acf_functions();
    when('get_terms')->justReturn([(object) ['slug' => 'a', 'name' => 'A']]);
    when('is_wp_error')->justReturn(false);

    expect($function(['key' => 'k'])['choices'])->toBe(['a' => 'A']);
})->with([
    'uams' => ['uamswp_fad_acf_load_brandorg_uams'],
    'third party' => ['uamswp_fad_acf_load_brandorg_3p'],
]);

test('the brand organization loaders keep the existing choices when the terms cannot be read', function (string $function): void {
    uamswp_test_acf_functions();
    when('get_terms')->justReturn(new WP_Error('bad', 'bad'));
    when('is_wp_error')->alias(static fn (mixed $value): bool => $value instanceof WP_Error);

    expect($function(['choices' => ['x' => 'X']])['choices'])->toBe(['x' => 'X']);
})->with([
    'uams' => ['uamswp_fad_acf_load_brandorg_uams'],
    'third party' => ['uamswp_fad_acf_load_brandorg_3p'],
]);

/**
 * Run a block callback and return the HTML it printed.
 *
 * @param  array<string, mixed>  $block
 */
function acf_block_html(string $callback, array $block = []): string
{
    ob_start();
    $callback($block);

    return (string) ob_get_clean();
}

test('the FacetWP cards block prints the heading, template and class names', function (): void {
    uamswp_test_acf_functions();
    acf_filters_options([
        'facetwp_heading' => 'Our Clinics',
        'facetwp_template_name' => 'clinic_cards',
        'facetwp_background_color' => 'bg-light',
    ]);
    when('facetwp_display')->alias(static fn (string $type, string $name): string => "[$type:$name]");

    $html = acf_block_html('fad_facetwp_cards_callback', ['className' => 'extra', 'align' => 'full']);

    expect($html)->toContain('<section class="uams-module container-fluid p-8 p-sm-10 facetwp-cards extra alignfull bg-light">')
        ->and($html)->toContain('<h2 class="module-title">Our Clinics</h2>')
        ->and($html)->toContain('<div class="card-list-container">')
        ->and($html)->toContain('[template:clinic_cards]');
});

test('the FacetWP cards block falls back to its default heading and background', function (): void {
    uamswp_test_acf_functions();
    acf_filters_options([]);
    when('facetwp_display')->justReturn('');

    $html = acf_block_html('fad_facetwp_cards_callback');

    expect($html)->toContain('<section class="uams-module container-fluid p-8 p-sm-10 facetwp-cards bg-white">')
        ->and($html)->toContain('<h2 class="module-title">Cards List</h2>');
});

test('the FacetWP block prints its facets around the template and an optional pager', function (): void {
    uamswp_test_acf_functions();
    acf_filters_options([
        'facetwp_block_heading' => 'Find a Clinic',
        'facetwp_block_hide_heading' => '1',
        'facetwp_block_pre_template_facets' => [['alignment' => 'left', 'facet_name' => 'region']],
        'facetwp_block_facet_template' => 'clinic_cards',
        'facetwp_block_post_template_facets' => [['alignment' => 'right', 'facet_name' => 'pager_facet']],
        'facetwp_block_include_pager' => true,
        'facetwp_block_background_color' => 'bg-dark',
    ]);
    when('facetwp_display')->alias(static fn (string $type, ?string $name = null): string => '['.$type.($name === null ? '' : ':'.$name).']');

    $html = acf_block_html('fad_facetwp_blocks_callback', ['className' => 'wide', 'align' => 'wide']);

    expect($html)->toContain('<section class="uams-module facetwp-blocks wide alignwide bg-dark">')
        ->and($html)->toContain('<h2 class="module-title sr-only" >Find a Clinic</h2>')
        ->and($html)->toContain('<div class="text-left">[facet:region]</div>')
        ->and($html)->toContain('[template:clinic_cards]')
        ->and($html)->toContain('<div class="text-right">[facet:pager_facet]</div>')
        ->and($html)->toContain('[pager]')
        ->and(strpos($html, '[facet:region]'))->toBeLessThan(strpos($html, '[template:clinic_cards]'))
        ->and(strpos($html, '[template:clinic_cards]'))->toBeLessThan(strpos($html, '[facet:pager_facet]'));
});

test('the FacetWP block shows its heading, and no facets or pager, by default', function (): void {
    uamswp_test_acf_functions();
    acf_filters_options(['facetwp_block_facet_template' => 'clinic_cards']);
    when('facetwp_display')->alias(static fn (string $type, ?string $name = null): string => '['.$type.($name === null ? '' : ':'.$name).']');

    $html = acf_block_html('fad_facetwp_blocks_callback');

    expect($html)->toContain('<section class="uams-module facetwp-blocks bg-white">')
        ->and($html)->toContain('<h2 class="module-title" >Cards List</h2>')
        ->and($html)->not->toContain('[pager]')
        ->and($html)->not->toContain('[facet:')
        ->and($html)->toContain('[template:clinic_cards]');
});

test('the file registers the FacetWP cards and block blocks when ACF can register blocks', function (): void {
    $blocks = uamswp_test_acf_functions()['blocks'];

    expect(array_column($blocks, 'name'))->toBe(['uamswp_fad_facetwp_cards', 'uamswp_fad_facetwp_blocks'])
        ->and(array_column($blocks, 'title'))->toBe(['FacetWP Cards', 'FacetWP Block'])
        ->and(array_column($blocks, 'render_callback'))->toBe(['fad_facetwp_cards_callback', 'fad_facetwp_blocks_callback'])
        ->and(array_column($blocks, 'icon'))->toBe(['id', 'list-view'])
        ->and(array_column($blocks, 'acf_block_version'))->toBe([3, 3])
        ->and($blocks[0]['keywords'])->toBe(['provider', 'location', 'facetwp'])
        ->and($blocks[1]['keywords'])->toBe(['facetwp', 'shortcode'])
        ->and($blocks[0]['supports'])->toBe(['align' => ['full'], 'mode' => true, 'multiple' => true])
        ->and($blocks[1]['supports'])->toBe(['align' => true, 'mode' => true, 'multiple' => true]);
});

test('the image field settings render the aspect ratio width, height and margin inputs', function (): void {
    uamswp_test_acf_functions();
    when('__')->returnArg();

    $rendered = [];
    when('acf_render_field_setting')->alias(function (array $field, array $args) use (&$rendered): void {
        $rendered[] = [$field, $args];
    });

    $field = ['type' => 'image'];
    acf_image_aspect_ratio_settings($field);

    $wrapper = ['data-append' => 'ratio_width', 'width' => '', 'class' => '', 'id' => ''];

    expect($rendered)->toBe([
        [$field, [
            'name' => 'ratio_width',
            'type' => 'number',
            'label' => 'Aspect Ratio',
            'instructions' => 'Restrict which images can be uploaded',
            'default_value' => 0,
            'min' => 0,
            'step' => 1,
            'prepend' => 'Width',
        ]],
        [$field, [
            'name' => 'ratio_height',
            'type' => 'number',
            'label' => '',
            'default_value' => 0,
            'min' => 0,
            'step' => 1,
            'prepend' => 'Height',
            'wrapper' => $wrapper,
        ]],
        [$field, [
            'name' => 'ratio_margin',
            'type' => 'number',
            'label' => '',
            'default_value' => 0,
            'min' => 0,
            'step' => .5,
            'prepend' => '&plusmn;',
            'append' => '%',
            'wrapper' => $wrapper,
        ]],
    ]);
});

test('the allowed image height is rounded to the nearest pixel at both ends', function (int $height, bool $fitsShort, bool $fitsTall): void {
    uamswp_test_acf_functions();
    when('__')->returnArg();

    // A 100 pixel wide image at 3:1 may be 33.33 pixels tall, and at 3:2 66.67 pixels tall, with no margin.
    $short = acf_image_aspect_ratio_validate([], ['width' => 100, 'height' => $height], [], ['ratio_width' => 3, 'ratio_height' => 1], '');
    $tall = acf_image_aspect_ratio_validate([], ['width' => 100, 'height' => $height], [], ['ratio_width' => 3, 'ratio_height' => 2], '');

    expect($short === [])->toBe($fitsShort)
        ->and($tall === [])->toBe($fitsTall);
})->with([
    'one under the short ratio' => [32, false, false],
    'the short ratio rounded down' => [33, true, false],
    'one over the short ratio' => [34, false, false],
    'one under the tall ratio' => [66, false, false],
    'the tall ratio rounded up' => [67, false, true],
    'one over the tall ratio' => [68, false, false],
]);

test('the FacetWP block hides its heading for any true value ACF stores, and prints nothing else without a pager', function (mixed $hide, string $heading_class): void {
    uamswp_test_acf_functions();
    acf_filters_options(['facetwp_block_hide_heading' => $hide, 'facetwp_block_facet_template' => 'clinic_cards']);
    when('facetwp_display')->alias(static fn (string $type, ?string $name = null): string => '['.$type.($name === null ? '' : ':'.$name).']');

    $html = acf_block_html('fad_facetwp_blocks_callback');

    expect($html)->toContain('<h2 class="module-title'.$heading_class.'" >Cards List</h2>')
        ->and(trim((string) preg_replace('/\s+/', ' ', strip_tags($html))))->toBe('Cards List [template:clinic_cards]');
})->with([
    'the string one' => ['1', ' sr-only'],
    'the integer one' => [1, ' sr-only'],
    'true' => [true, ' sr-only'],
    'false' => [false, ''],
    'the string zero' => ['0', ''],
]);
