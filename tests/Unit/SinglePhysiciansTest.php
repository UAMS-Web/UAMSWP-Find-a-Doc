<?php

/**
 * The provider profile template (templates/single-physicians.php): the name variants, the language list, the
 * clinical title and its indefinite article, the location and expertise checks, the meta description and the
 * hooks it registers before the markup.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/templates.php';

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

    $fields += ['physician_title' => 10];
    $terms = ($options['terms'] ?? []) + [10 => 'Cardiologist'];
    $statuses = $options['status'] ?? [];
    $titles = $options['titles'] ?? [];
    $rules = $options['rules'] ?? [];
    $term_fields = $options['term_fields'] ?? [];
    $row = new stdClass;
    $row->index = -1;

    when('get_field')->alias(static function (string $name, mixed $id = null) use ($fields, $term_fields): mixed {
        if (is_object($id)) {
            return $term_fields[$id->term_id][$name] ?? null;
        }

        return $fields[$name] ?? null;
    });
    when('get_term')->alias(static fn (mixed $id, string $taxonomy = ''): ?object => isset($terms[$id]) ? (object) ['name' => $terms[$id], 'term_id' => $id] : null);
    when('get_term_by')->alias(static fn (string $field, mixed $id, string $taxonomy): object|false => isset($terms[$id]) ? (object) ['name' => $terms[$id]] : false);
    when('get_post_status')->alias(static fn (mixed $id): string|false => $statuses[$id] ?? false);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Title '.$id);
    when('get_the_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('sanitize_title_with_dashes')->alias(static fn (string $title): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-'));
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('have_rows')->alias(static fn (): bool => $row->index + 1 < count($rules));
    when('the_row')->alias(static function () use ($row): void {
        $row->index++;
    });
    when('get_sub_field')->alias(static fn (string $name): mixed => $rules[$row->index][$name] ?? null);
    when('get_header')->justReturn(null);
    when('get_footer')->justReturn(null);
    when('have_posts')->justReturn(false);

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
        uamswp_test_render_template('single-physicians', ['post' => $post]);
    } finally {
        restore_error_handler();
    }

    return $GLOBALS;
}

test('the name variants of a physician with every part of the name', function (): void {
    $vars = single_physician([
        'physician_first_name' => 'Leonard',
        'physician_middle_name' => 'H.',
        'physician_last_name' => 'McCoy',
        'physician_pedigree' => 'Jr.',
        'physician_degree' => [1, 2],
    ], ['terms' => [1 => 'M.D.', 2 => 'Ph.D.']]);

    expect($vars['degree_list'])->toBe('M.D., Ph.D.')
        ->and($vars['degree_list_attr'])->toBe('M.D., Ph.D.')
        ->and($vars['degree_count'])->toBe(2)
        ->and($vars['prefix'])->toBe('Dr.')
        ->and($vars['prefix_attr'])->toBe('Dr.')
        ->and($vars['full_name'])->toBe('Leonard H. McCoy&nbsp;Jr., M.D., Ph.D.')
        ->and($vars['full_name_attr'])->toBe('Leonard H. McCoy Jr., M.D., Ph.D.')
        ->and($vars['medium_name'])->toBe('Dr. Leonard H. McCoy')
        ->and($vars['medium_name_attr'])->toBe('Dr. Leonard H. McCoy')
        ->and($vars['short_name'])->toBe('Dr.&nbsp;McCoy')
        ->and($vars['short_name_attr'])->toBe('Dr. McCoy')
        ->and($vars['short_name_possessive'])->toBe("Dr.&nbsp;McCoy's")
        ->and($vars['sort_name'])->toBe('McCoy, Leonard H.')
        ->and($vars['sort_name_param_value'])->toBe('mccoy-leonard-h');
});

test('a physician with a doctorate that earns no prefix is named by the full name', function (): void {
    $vars = single_physician([
        'physician_first_name' => 'Leonard',
        'physician_last_name' => 'McCoy',
        'physician_pedigree' => 'Jr.',
        'physician_degree' => [2],
    ], ['terms' => [2 => 'Ph.D.']]);

    expect($vars['prefix'])->toBe('')
        ->and($vars['prefix_attr'])->toBe('')
        ->and($vars['medium_name'])->toBe('Leonard McCoy')
        ->and($vars['short_name'])->toBe('Leonard McCoy&nbsp;Jr.')
        ->and($vars['short_name_possessive'])->toBe("Leonard McCoy&nbsp;Jr.'s")
        ->and($vars['sort_name'])->toBe('McCoy, Leonard ');
});

test('the osteopathic degree also earns the prefix', function (): void {
    $vars = single_physician(['physician_first_name' => 'A', 'physician_last_name' => 'B', 'physician_degree' => [3]], ['terms' => [3 => 'D.O.']]);

    expect($vars['prefix'])->toBe('Dr.');
});

test('a physician with no degrees has no list and no prefix', function (): void {
    $vars = single_physician(['physician_first_name' => 'A', 'physician_last_name' => 'B']);

    expect($vars['degree_count'])->toBe(0)
        ->and($vars['degree_list'])->toBe('')
        ->and($vars['degree_list_attr'])->toBe('')
        ->and($vars['degree_attr_array'])->toBe([])
        ->and($vars['prefix'])->toBe('')
        ->and($vars['full_name'])->toBe('A B');
});

test('the degree list drops empty and repeated values before it is built', function (): void {
    $vars = single_physician(
        ['physician_first_name' => 'A', 'physician_last_name' => 'B', 'physician_degree' => [1, 0, 1, '', 2]],
        ['terms' => [1 => 'M.D.', 2 => 'Ph.D.']],
    );

    expect($vars['degrees'])->toBe([1, 2])
        ->and($vars['degree_count'])->toBe(2)
        ->and($vars['degree_list'])->toBe('M.D., Ph.D.')
        ->and($vars['degree_attr_array'])->toBe(['M.D.', 'Ph.D.']);
});

test('a degree whose term is missing is skipped and leaves its separator behind', function (): void {
    $vars = single_physician(
        ['physician_first_name' => 'A', 'physician_last_name' => 'B', 'physician_degree' => [1, 9, 2]],
        ['terms' => [1 => 'M.D.', 2 => 'Ph.D.']],
    );

    expect($vars['degree_list'])->toBe('M.D., Ph.D., ')
        ->and($vars['degree_list_attr'])->toBe('M.D., Ph.D., ');
});

test('a name that ends in an s takes an apostrophe alone in the possessive', function (): void {
    $vars = single_physician(['physician_first_name' => 'Ann', 'physician_last_name' => 'Adams']);

    expect($vars['short_name'])->toBe('Ann Adams')
        ->and($vars['short_name_possessive'])->toBe("Ann Adams'");
});

test('the languages are listed by name and a language without a term is left out', function (): void {
    $vars = single_physician(['physician_languages' => [1, 9, 2]], ['terms' => [1 => 'Spanish', 2 => 'French']]);

    expect($vars['language_count'])->toBe(3)
        ->and($vars['language_list'])->toBe('Spanish, French');
});

test('a physician without languages has an empty list', function (): void {
    $vars = single_physician();

    expect($vars['language_count'])->toBe(0)
        ->and($vars['language_list'])->toBe('');
});

test('the clinical title comes from the specialization field of its term', function (): void {
    $vars = single_physician([], ['term_fields' => [10 => ['clinical_specialization_title' => 'Heart Doctor']]]);

    expect($vars['provider_specialty'])->toBe(10)
        ->and($vars['provider_specialty_name'])->toBe('Cardiologist')
        ->and($vars['provider_occupation_title'])->toBe('Heart Doctor')
        ->and($vars['provider_occupation_title_attr'])->toBe('Heart Doctor');
});

test('the clinical title falls back to the name of its term', function (): void {
    $vars = single_physician();

    expect($vars['provider_occupation_title'])->toBe('Cardiologist')
        ->and($vars['provider_occupation_title_attr'])->toBe('Cardiologist');
});

test('a resident has the resident title and no clinical specialty', function (): void {
    $vars = single_physician(['physician_resident' => true, 'physician_eligible_appointments' => true]);

    expect($vars['provider_occupation_title'])->toBe('Resident Physician')
        ->and($vars['provider_specialty'])->toBe('')
        ->and($vars['eligible_appt'])->toBe(0);
});

test('a physician who is not a resident keeps the appointment setting', function (): void {
    expect(single_physician(['physician_eligible_appointments' => true])['eligible_appt'])->toBeTrue();
});

test('the indefinite article follows the first letter of the title', function (string $title, string $article): void {
    $vars = single_physician([], ['term_fields' => [10 => ['clinical_specialization_title' => $title]]]);

    expect($vars['provider_occupation_title_indef_article'])->toBe($article);
})->with([
    'a vowel' => ['Endocrinologist', 'an'],
    'a capital vowel' => ['Internist', 'an'],
    'a consonant' => ['Pediatrician', 'a'],
    'the u of urology' => ['Urologist', 'a'],
    'the u of uveitis' => ['Uveitis Specialist', 'a'],
    'a title that starts like an exception key' => ['SNF Physician', 'an'],
    'a vowel title that only shares a letter with a key' => ['Surgeon', 'a'],
    'a title shorter than a key' => ['Uro', 'an'],
]);

test('the locations count only the published ones', function (): void {
    $vars = single_physician(
        ['physician_locations' => [20, 21, 22]],
        ['status' => [20 => 'draft', 21 => 'publish', 22 => 'publish'], 'titles' => [21 => "Mom's Clinic"]],
    );

    expect($vars['location_valid'])->toBeTrue()
        ->and($vars['location_count'])->toBe(2);
});

test('no published location leaves the locations invalid', function (): void {
    $vars = single_physician(['physician_locations' => [20]], ['status' => [20 => 'draft']]);

    expect($vars['location_valid'])->toBeFalse()
        ->and($vars['location_count'])->toBe(0);
});

test('a physician without locations has none', function (): void {
    $vars = single_physician();

    expect($vars['location_valid'])->toBeFalse()
        ->and($vars['location_count'])->toBe(0);
});

test('the primary appointment location is the first published one', function (): void {
    $vars = single_physician(
        ['physician_locations' => [20, 21, 22], 'location_city' => 'Little "Rock"'],
        ['status' => [20 => 'draft', 21 => 'publish', 22 => 'publish'], 'titles' => [21 => 'Clinic &amp; Co']],
    );

    expect($vars['primary_appointment_title'])->toBe('Clinic &amp; Co')
        ->and($vars['primary_appointment_title_attr'])->toBe('Clinic &amp; Co')
        ->and($vars['primary_appointment_url'])->toBe('https://example.test/p/21/')
        ->and($vars['primary_appointment_city'])->toBe('Little "Rock"')
        ->and($vars['primary_appointment_city_attr'])->toBe("Little 'Rock'");
});

test('the primary area of expertise is the first published one', function (): void {
    $vars = single_physician(
        ['physician_expertise' => [30, 31, 32]],
        ['status' => [30 => 'draft', 31 => 'publish', 32 => 'publish'], 'titles' => [31 => 'Heart Care', 32 => 'Lung Care']],
    );

    expect($vars['expertise_primary_name'])->toBe('Heart Care');
});

test('a physician with no published area of expertise has no primary name', function (): void {
    $vars = single_physician(['physician_expertise' => [30]], ['status' => [30 => 'draft']]);

    expect(isset($vars['expertise_primary_name']))->toBeFalse();
});

test('the medical ontology stays visible when no rule applies', function (): void {
    expect(single_physician()['hide_medical_ontology'])->toBeFalse();
});

test('the medical ontology is hidden for a region rule that matches', function (): void {
    $vars = single_physician(['physician_region' => ['north']], ['rules' => [['remove_regions' => ['north'], 'remove_service_lines' => null]]]);

    expect($vars['hide_medical_ontology'])->toBeTrue();
});

test('a region rule that does not match hides nothing', function (): void {
    $vars = single_physician(['physician_region' => ['south']], ['rules' => [['remove_regions' => ['north'], 'remove_service_lines' => null]]]);

    expect($vars['hide_medical_ontology'])->toBeFalse();
});

test('the medical ontology is hidden for a service line rule that matches', function (): void {
    $vars = single_physician(['physician_service_line' => 7], ['rules' => [['remove_regions' => null, 'remove_service_lines' => [7, 8]]]]);

    expect($vars['hide_medical_ontology'])->toBeTrue();
});

test('a service line rule that does not match hides nothing', function (): void {
    $vars = single_physician(['physician_service_line' => 9], ['rules' => [['remove_regions' => null, 'remove_service_lines' => [7, 8]]]]);

    expect($vars['hide_medical_ontology'])->toBeFalse();
});

test('a rule with both a region and a service line needs both to match', function (): void {
    $rule = ['remove_regions' => ['north'], 'remove_service_lines' => [7]];

    expect(single_physician(['physician_region' => ['north'], 'physician_service_line' => 7], ['rules' => [$rule]])['hide_medical_ontology'])->toBeTrue()
        ->and(single_physician(['physician_region' => ['north'], 'physician_service_line' => 9], ['rules' => [$rule]])['hide_medical_ontology'])->toBeFalse()
        ->and(single_physician(['physician_region' => ['south'], 'physician_service_line' => 7], ['rules' => [$rule]])['hide_medical_ontology'])->toBeFalse();
});

test('a later rule can hide the ontology after an earlier one did not match', function (): void {
    $rules = [
        ['remove_regions' => ['east'], 'remove_service_lines' => null],
        ['remove_regions' => ['north'], 'remove_service_lines' => null],
    ];

    expect(single_physician(['physician_region' => ['north']], ['rules' => $rules])['hide_medical_ontology'])->toBeTrue();
});

test('the meta description is the short clinical bio when there is one', function (): void {
    $vars = single_physician(['physician_short_clinical_bio' => 'Short bio.', 'physician_clinical_bio' => '<p>Long bio.</p>']);

    expect($vars['excerpt'])->toBe('Short bio.')
        ->and($vars['schema_description'])->toBe('Short bio.');
});

test('the meta description is the start of the clinical bio without its markup', function (): void {
    $vars = single_physician(['physician_clinical_bio' => '<p>'.str_repeat('x', 200).'</p>']);

    expect($vars['excerpt'])->toBe(str_repeat('x', 152).'...')
        ->and(mb_strlen($vars['excerpt']))->toBe(155);
});

test('a clinical bio of exactly the limit is not shortened', function (): void {
    $vars = single_physician(['physician_clinical_bio' => '<b>'.str_repeat('y', 155).'</b>']);

    expect($vars['excerpt'])->toBe(str_repeat('y', 155));
});

test('the meta description falls back to a sentence about the provider', function (): void {
    $vars = single_physician(
        ['physician_first_name' => 'Leonard', 'physician_last_name' => 'McCoy', 'physician_locations' => [21]],
        ['status' => [21 => 'publish'], 'titles' => [21 => 'Heart Clinic']],
    );

    expect($vars['excerpt'])->toBe('Leonard McCoy is a cardiologist at Heart Clinic employed by UAMS Health.')
        ->and($vars['fallback_desc'])->toBe($vars['excerpt']);
});

test('the fallback description uses the article of the title and leaves out a missing location', function (): void {
    $vars = single_physician(
        ['physician_first_name' => 'Ann', 'physician_last_name' => 'Adams'],
        ['term_fields' => [10 => ['clinical_specialization_title' => 'Orthopedic Surgeon']]],
    );

    expect($vars['excerpt'])->toBe('Ann Adams is an orthopedic surgeon employed by UAMS Health.');
});

test('the fallback description names a health care provider when there is no title', function (): void {
    $vars = single_physician(['physician_first_name' => 'Ann', 'physician_last_name' => 'Adams', 'physician_title' => null]);

    expect($vars['excerpt'])->toBe('Ann Adams is a health care provider employed by UAMS Health.')
        ->and($vars['provider_occupation_title_indef_article'])->toBe('a');
});

test('a provider with no clinical title raises an uninitialized string offset warning', function (): void {
    $vars = single_physician(['physician_title' => null, 'physician_short_clinical_bio' => 'Bio.']);

    expect($vars['single_physician_warnings'])->toBe(['Uninitialized string offset 0']);
});

test('the fallback description is cut at the limit', function (): void {
    $vars = single_physician(['physician_first_name' => str_repeat('N', 200), 'physician_last_name' => 'L']);

    expect(mb_strlen($vars['excerpt']))->toBe(155)
        ->and($vars['excerpt'])->toEndWith('...');
});

test('the template overrides the theme titles and breadcrumbs', function (): void {
    single_physician();

    expect(uamswp_test_render_hooks()['add_filter'])->toBe([
        ['seopress_titles_desc', 'sp_titles_desc'],
        ['seopress_titles_title', 'uamswp_fad_title_single_physicians', 15, 2],
        ['genesis_single_crumb', 'be_remove_title_from_single_crumb', 10, 2],
        ['seopress_pro_breadcrumbs_crumbs', 'sp_change_title_from_provider_crumb', 20],
    ]);
});

test('a provider with no bio and no published location raises an undefined variable warning in the fallback description', function (): void {
    $vars = single_physician(['physician_first_name' => 'Ann', 'physician_last_name' => 'Adams']);

    expect($vars['single_physician_warnings'])->toBe(['Undefined variable $primary_appointment_title_attr']);
});

test('a provider with a bio or a published location raises no warning', function (): void {
    expect(single_physician(['physician_short_clinical_bio' => 'Bio.'])['single_physician_warnings'])->toBe([])
        ->and(single_physician(['physician_locations' => [21]], ['status' => [21 => 'publish']])['single_physician_warnings'])->toBe([]);
});
