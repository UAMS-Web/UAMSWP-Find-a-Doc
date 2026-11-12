<?php

/**
 * The page title filters moved out of the templates (includes/class.fad-template-titles.php), and the
 * archive, excerpt, breadcrumb and keyword callbacks moved beside the other shared helpers.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../../includes/class.fad-template-helpers.php';
require_once __DIR__.'/../../includes/class.fad-template-titles.php';

beforeEach(function (): void {
    when('get_bloginfo')->justReturn('UAMS Health');
});

afterEach(function (): void {
    foreach (['condition_title', 'treatment_title', 'page_title_attr', 'condition_title_attr', 'resource_archive_title_attr', 'expertise_single_name_attr', 'treatment_title_attr', 'location_city', 'full_name_attr', 'provider_occupation_title_attr', 'primary_appointment_city_attr', 'expertise_primary_name', 'full_name', 'keywords', 'post'] as $name) {
        unset($GLOBALS[$name]);
    }
});

test('a fixed archive title is the label and the site name', function (string $function, string $expected): void {
    expect($function('Original'))->toBe($expected);
})->with([
    'clinical resources' => ['uamswp_fad_title_archive_clinical_resource', 'Clinical Resources | UAMS Health'],
    'areas of expertise' => ['uamswp_fad_title_archive_expertise', 'Areas of Expertise | UAMS Health'],
    'locations' => ['uamswp_fad_title_archive_locations', 'Locations | UAMS Health'],
    'providers' => ['uamswp_fad_title_archive_physicians', 'Providers | UAMS Health'],
]);

test('the condition and treatment archive titles use the title the template set', function (): void {
    $GLOBALS['condition_title'] = 'Conditions A-Z';
    $GLOBALS['treatment_title'] = 'Treatments A-Z';

    expect(uamswp_fad_title_archive_condition('x'))->toBe('Conditions A-Z | UAMS Health')
        ->and(uamswp_fad_title_archive_treatments('x'))->toBe('Treatments A-Z | UAMS Health');
});

test('the taxonomy archive titles fall back to a label when no title was set', function (): void {
    $GLOBALS['condition_title'] = '';
    $GLOBALS['treatment_title'] = '';

    expect(uamswp_fad_title_archive_taxonomy_conditions('x'))->toBe('Conditions | UAMS Health')
        ->and(uamswp_fad_title_archive_taxonomy_treatments('x'))->toBe('Treatments &amp; Procedures | UAMS Health');

    $GLOBALS['condition_title'] = 'Heart';
    $GLOBALS['treatment_title'] = 'Surgery';

    expect(uamswp_fad_title_archive_taxonomy_conditions('x'))->toBe('Heart | UAMS Health')
        ->and(uamswp_fad_title_archive_taxonomy_treatments('x'))->toBe('Surgery | UAMS Health');
});

test('a single page title adds the second part only while it fits in 60 characters', function (string $function, string $global): void {
    $GLOBALS['page_title_attr'] = 'Asthma';
    $GLOBALS[$global] = 'Conditions';

    expect($function('x'))->toBe('Asthma | Conditions | UAMS Health');

    $GLOBALS[$global] = str_repeat('c', 60);

    expect($function('x'))->toBe('Asthma | UAMS Health');
})->with([
    'clinical resource' => ['uamswp_fad_title_single_clinical_resource', 'resource_archive_title_attr'],
    'condition' => ['uamswp_fad_title_single_condition', 'condition_title_attr'],
    'expertise' => ['uamswp_fad_title_single_expertise', 'expertise_single_name_attr'],
    'location' => ['uamswp_fad_title_single_locations', 'location_city'],
    'treatment' => ['uamswp_fad_title_single_treatment', 'treatment_title_attr'],
]);

test('a single page title keeps the second part at exactly 60 characters', function (): void {
    $GLOBALS['page_title_attr'] = 'A';
    // "A | " + x + " | UAMS Health" is 4 + x + 14 characters.
    $GLOBALS['condition_title_attr'] = str_repeat('c', 42);

    expect(strlen(uamswp_fad_title_single_condition('x')))->toBe(60);

    $GLOBALS['condition_title_attr'] = str_repeat('c', 43);

    expect(uamswp_fad_title_single_condition('x'))->toBe('A | UAMS Health');
});

test('a provider title grows from the name through the clinical title, city and area of expertise while it fits', function (): void {
    $GLOBALS['full_name_attr'] = 'Jo Smith';
    $GLOBALS['provider_occupation_title_attr'] = 'Cardiologist';
    $GLOBALS['primary_appointment_city_attr'] = 'Little Rock';
    $GLOBALS['expertise_primary_name'] = '';

    expect(uamswp_fad_title_single_physicians('x'))->toBe('Jo Smith | Cardiologist | Little Rock | UAMS Health');

    $GLOBALS['expertise_primary_name'] = 'Heart';

    expect(uamswp_fad_title_single_physicians('x'))->toBe('Jo Smith | Cardiologist | Heart | Little Rock | UAMS Health');

    $GLOBALS['expertise_primary_name'] = 'Heart and vascular medicine';

    expect(uamswp_fad_title_single_physicians('x'))->toBe('Jo Smith | Cardiologist | Little Rock | UAMS Health');

    $GLOBALS['primary_appointment_city_attr'] = '';

    expect(uamswp_fad_title_single_physicians('x'))->toBe('Jo Smith | Cardiologist | UAMS Health');

    $GLOBALS['provider_occupation_title_attr'] = str_repeat('t', 50);

    expect(uamswp_fad_title_single_physicians('x'))->toBe('Jo Smith | UAMS Health');
});

test('the taxonomy titles add the label only for a short term name', function (): void {
    when('single_cat_title')->justReturn('Asthma');

    expect(uamswp_fad_title_taxonomy_condition('x'))->toBe('Asthma | Conditions | UAMS Health')
        ->and(uamswp_fad_title_taxonomy_treatment_procedure('x'))->toBe('Asthma | Treatments & Procedures | UAMS Health');

    when('single_cat_title')->justReturn(str_repeat('a', 34));

    expect(uamswp_fad_title_taxonomy_condition('x'))->toBe(str_repeat('a', 34).' | UAMS Health');

    when('single_cat_title')->justReturn(str_repeat('a', 33));

    expect(uamswp_fad_title_taxonomy_condition('x'))->toBe(str_repeat('a', 33).' | Conditions | UAMS Health');

    when('single_cat_title')->justReturn(str_repeat('a', 21));

    expect(uamswp_fad_title_taxonomy_treatment_procedure('x'))->toBe(str_repeat('a', 21).' | UAMS Health');

    when('single_cat_title')->justReturn(str_repeat('a', 20));

    expect(uamswp_fad_title_taxonomy_treatment_procedure('x'))->toBe(str_repeat('a', 20).' | Treatments & Procedures | UAMS Health');
});

test('the archive excerpt length is 35 words', function (): void {
    expect(wpdocs_custom_excerpt_length(55))->toBe(35);
});

test('the custom field excerpt trims the field text and runs the excerpt filters', function (): void {
    when('get_field')->justReturn('Some [code] text');
    when('strip_shortcodes')->alias(static fn (string $text): string => str_replace('[code] ', '', $text));
    when('apply_filters')->alias(static fn (string $tag, mixed $value): mixed => $tag === 'excerpt_more' ? ' (more)' : '<'.$tag.'>'.$value);
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => $text.'|'.$words.'|'.$more);

    expect(custom_field_excerpt('field'))->toBe('<the_excerpt><the_content>Some text|35| (more)');
});

test('the custom field excerpt of an empty field is passed through the excerpt filter untrimmed', function (): void {
    when('get_field')->justReturn('');
    when('apply_filters')->alias(static fn (string $tag, mixed $value): mixed => $tag.':'.$value);

    expect(custom_field_excerpt('field'))->toBe('the_excerpt:');
});

test('the provider breadcrumb drops the last crumb for the provider name', function (): void {
    $GLOBALS['full_name'] = 'Jo <b>Smith</b>';
    when('uamswp_provider_name_html')->alias(static fn (string $name): string => htmlspecialchars($name));

    expect(be_remove_title_from_single_crumb('Home > Providers > Old', ['sep' => ' > ']))
        ->toBe('Home > Providers > Jo &lt;b&gt;Smith&lt;/b&gt;');
});

test('the SEOPress breadcrumbs end with the provider name and permalink', function (): void {
    $GLOBALS['full_name'] = 'Jo Smith';
    when('get_permalink')->justReturn('https://example.test/provider/jo/');

    expect(sp_change_title_from_provider_crumb([['Home', '/'], ['Old', '/old/']]))
        ->toBe([['Home', '/'], ['Jo Smith', 'https://example.test/provider/jo/']]);
});

test('the treatment procedure keywords function prints nothing because it never reads the global', function (): void {
    $GLOBALS['keywords'] = [['text' => 'heart']];

    ob_start();
    $warnings = uamswp_test_capture_warnings(static function (): void {
        uamswp_keyword_hook_header_taxonomy_treatment_procedure();
    });
    $output = ob_get_clean();

    expect($output)->toBe('')
        ->and($warnings)->toBe(['Undefined variable $keywords']);
});
