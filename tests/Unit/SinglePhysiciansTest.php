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
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('sanitize_title_with_dashes')->alias(static fn (string $title): string => trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-'));
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('have_rows')->alias(static function (string $name) use ($row, $rows): bool {
        $row->name = $name;
        $row->cursor[$name] ??= -1;

        if ($row->cursor[$name] + 1 < count($rows[$name] ?? [])) {
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
    when('wp_reset_postdata')->justReturn(null);
    // Whether bis_get_attachment_image() exists depends on the other tests in the process, so both photo branches are stubbed.
    when('image_sizer')->alias(static fn (mixed $id, int $width, int $height): string => 'https://example.test/photo-'.$width.'x'.$height.'.jpg');
    when('get_header')->justReturn(null);
    when('get_footer')->justReturn(null);
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

    expect($vars['single_physician_warnings'])->toContain('Uninitialized string offset 0');
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

    expect($vars['single_physician_warnings'])->toContain('Undefined variable $primary_appointment_title_attr');
});

test('a provider with a bio or a published location raises no warning', function (): void {
    expect(single_physician(['physician_short_clinical_bio' => 'Bio.'])['single_physician_warnings'])->not->toContain('Undefined variable $primary_appointment_title_attr')
        ->and(single_physician(['physician_locations' => [21]], ['status' => [21 => 'publish']])['single_physician_warnings'])->not->toContain('Undefined variable $primary_appointment_title_attr');
});

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

test('the page classes name each part of the profile that has content', function (array $fields, array $options, string $classes): void {
    expect(single_physician($fields, $options)['provider_field_classes'])->toBe($classes);
})->with([
    'nothing' => [[], [], ''],
    'a degree' => [['physician_degree' => [2]], ['terms' => [2 => 'Ph.D.']], ' has-degrees'],
    'a degree with the prefix' => [['physician_degree' => [3]], ['terms' => [3 => 'M.D.']], ' has-degrees has-prefix'],
    'a photo' => [[], ['thumbnail' => true], ' has-image'],
    'a service line' => [['physician_service_line' => 4], ['terms' => [4 => 'Heart']], ' has-service-line'],
    'an npi' => [['physician_npi' => '123'], [], ' has-npi'],
    'a clinical bio' => [['physician_clinical_bio' => 'Bio.'], [], ' has-clinical-bio'],
    'a short clinical bio' => [['physician_short_clinical_bio' => 'Bio.'], [], ' has-short-clinical-bio'],
    'a video' => [['physician_youtube_link' => 'https://example.test/v'], [], ' has-video'],
    'a condition' => [['physician_conditions_cpt' => [1]], [], ' has-condition'],
    'a treatment' => [['physician_treatments_cpt' => [1]], [], ' has-treatment'],
    'a published location' => [['physician_locations' => [21]], ['status' => [21 => 'publish']], ' has-location'],
    'an unpublished location' => [['physician_locations' => [21]], ['status' => [21 => 'draft']], ''],
    'an affiliation' => [['physician_affiliation' => 'UAMS'], [], ' has-affiliation'],
    'a published area of expertise' => [['physician_expertise' => [30]], ['status' => [30 => 'publish']], ' has-expertise'],
    'hidden' => [['physician_hidden' => true], [], ' has-hidden'],
    'a college affiliation' => [['physician_academic_college' => 'College'], [], ' has-college-affiliation'],
    'a position' => [['physician_academic_position' => 'Professor'], [], ' has-position'],
    'an academic bio' => [['physician_academic_bio' => 'Bio.'], [], ' has-academic-bio'],
    'a short academic bio' => [['physician_academic_short_bio' => 'Bio.'], [], ' has-short-academic-bio'],
    'an office location' => [['physician_academic_office' => 'Room 1'], [], ' has-office-location'],
    'an office building' => [['physician_academic_map' => 'Tower'], [], ' has-office-building'],
    'education' => [['physician_education' => [['school' => 'Med']]], [], ' has-education'],
    'boards' => [['physician_boards' => [1]], [], ' has-boards'],
    'associations' => [['physician_associations' => [1]], [], ' has-associations'],
    'a research bio' => [['physician_research_bio' => 'Bio.'], [], ' has-research-bio'],
    'research interests' => [['physician_research_interests' => 'Interests.'], [], ' has-research-interests'],
    'a research profile' => [['physician_research_profiles_link' => 'https://example.test/r'], [], ' has-research-profile'],
    'a PubMed id' => [['physician_pubmed_author_id' => '99'], [], ' has-pubmed-id'],
    'additional information' => [['physician_additional_info' => 'More.'], [], ' has-additional-info'],
    'a resident' => [['physician_resident' => true], [], ' is-resident'],
]);

test('the page classes follow the order of the template', function (): void {
    $vars = single_physician(
        ['physician_npi' => '1', 'physician_clinical_bio' => 'Bio.', 'physician_hidden' => true, 'physician_additional_info' => 'x'],
        ['thumbnail' => true],
    );

    expect($vars['provider_field_classes'])->toBe(' has-image has-npi has-clinical-bio has-hidden has-additional-info');
});

test('the age of the photo is counted in years from its upload', function (int $years, string $age): void {
    $vars = single_physician([], ['thumbnail' => true, 'image_year' => (int) date('Y') - $years]);

    expect((string) $vars['image_age'])->toBe($age);
})->with([
    'new' => [0, '0'],
    'a year' => [1, '1'],
    'one under the limit' => [9, '9'],
    'at the limit' => [10, '10+'],
    'over the limit' => [14, '10+'],
]);

test('the age of the photo is printed in the main element', function (): void {
    $vars = single_physician([], ['thumbnail' => true, 'image_year' => (int) date('Y') - 12]);

    expect($vars['single_physician_output'])->toContain('data-image-age="10+"');
});

test('the queries ask for the conditions, treatments and clinical resources of the provider', function (): void {
    single_physician(['physician_conditions_cpt' => [1, 2], 'physician_treatments_cpt' => [3], 'physician_clinical_resources' => [4]]);

    expect(array_slice(WP_Query::$queries, 0, 3))->toBe([
        ['post_type' => 'condition', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [1, 2]],
        ['post_type' => 'treatment', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1, 'post__in' => [3]],
        ['post_type' => 'clinical-resource', 'order' => 'DESC', 'orderby' => 'post_date', 'posts_per_page' => 4, 'post_status' => 'publish', 'post__in' => [4]],
    ]);
});

test('a resident profile reads the department, chief and year terms', function (): void {
    $vars = single_physician(
        ['physician_resident' => true, 'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 40,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 41,
        ]],
        ['terms' => [40 => 'Surgery', 41 => 'PGY-3']],
    );

    expect($vars['resident_academic_department_name'])->toBe('Surgery')
        ->and($vars['resident_academic_chief_name'])->toBe('')
        ->and($vars['resident_academic_year_name'])->toBe('PGY-3')
        ->and($vars['resident_academic_name'])->toBe('PGY-3');
});

test('a chief resident is named by the chief title rather than the year', function (): void {
    $vars = single_physician(
        ['physician_resident' => true, 'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 40,
            'physician_resident_academic_chief' => true,
            'physician_resident_academic_year' => 41,
        ]],
        ['terms' => [40 => 'Surgery', 41 => 'PGY-3']],
    );

    expect($vars['resident_academic_chief_name'])->toBe('Chief Resident')
        ->and($vars['resident_academic_year_name'])->toBe('PGY-3')
        ->and($vars['resident_academic_name'])->toBe('Chief Resident');
});

test('a resident without a department or year has empty names', function (): void {
    $vars = single_physician(['physician_resident' => true, 'physician_resident_profile_group' => [
        'physician_resident_academic_department' => null,
        'physician_resident_academic_chief' => null,
        'physician_resident_academic_year' => null,
    ]]);

    expect($vars['resident_academic_department_name'])->toBe('')
        ->and($vars['resident_academic_year_name'])->toBe('')
        ->and($vars['resident_academic_name'])->toBe('');
});

test('a resident with no profile group has no academic values', function (): void {
    $vars = single_physician(['physician_resident' => true]);

    expect($vars['resident_profile_group'])->toBeNull()
        ->and($vars['resident_academic_name'])->toBe('');
});

test('a provider who is not a resident ignores the profile group', function (): void {
    $vars = single_physician(['physician_resident_profile_group' => ['physician_resident_academic_chief' => true]]);

    expect($vars['resident_profile_group'])->toBe('')
        ->and($vars['resident_academic_chief_name'])->toBe('');
});

test('the rating is valid at thirty ratings and not below', function (int $count, bool $valid): void {
    $vars = single_physician([], ['ratings' => single_physician_rating('200', $count, 6)]);

    expect($vars['pg_rating_valid'])->toBe($valid)
        ->and($vars['pg_total_comment_count'])->toBe(6)
        ->and(str_contains($vars['provider_field_classes'], ' has-ratings'))->toBe($valid);
})->with([
    'below the limit' => [29, false],
    'at the limit' => [30, true],
    'above the limit' => [31, true],
]);

test('a rating response that is not a success is ignored', function (): void {
    $vars = single_physician([], ['ratings' => single_physician_rating('500', 99)]);

    expect($vars['pg_rating_valid'])->toBe('')
        ->and($vars['pg_total_comment_count'])->toBe('');
});

test('a provider without rating data has no rating', function (): void {
    $vars = single_physician();

    expect($vars['pg_rating_valid'])->toBe('')
        ->and($vars['pg_rating_data'])->toBe('');
});

test('the appointment section shows only for a provider who takes appointments', function (): void {
    expect(single_physician(['physician_eligible_appointments' => true])['show_appointment_section'])->toBeTrue()
        ->and(single_physician()['show_appointment_section'])->toBeFalse();
});

test('the clinical bio section needs a bio or a video', function (): void {
    expect(single_physician(['physician_clinical_bio' => 'Bio.'])['show_clinical_bio_section'])->toBeTrue()
        ->and(single_physician(['physician_youtube_link' => 'https://example.test/v'])['show_clinical_bio_section'])->toBeTrue()
        ->and(single_physician()['show_clinical_bio_section'])->toBeFalse();
});

test('the academic section shows for any one of its fields', function (string $field): void {
    expect(single_physician([$field => [1]])['show_academic_section'])->toBeTrue();
})->with([
    'resident' => ['physician_resident'],
    'academic bio' => ['physician_academic_bio'],
    'academic appointment' => ['physician_academic_appointment'],
    'academic admin title' => ['physician_academic_admin_title'],
    'education' => ['physician_education'],
    'boards' => ['physician_boards'],
]);

test('the academic section stays hidden without its fields', function (): void {
    expect(single_physician()['show_academic_section'])->toBeFalse();
});

test('the podcast section needs a podcast name', function (): void {
    expect(single_physician(['physician_podcast_name' => 'Pod'])['show_podcast_section'])->toBeTrue()
        ->and(single_physician()['show_podcast_section'])->toBeFalse();
});

test('the clinical resources section needs resources that the query finds', function (): void {
    expect(single_physician(['physician_clinical_resources' => [4]], ['queries' => [[], [], [4]]])['show_related_resource_section'])->toBeTrue()
        ->and(single_physician(['physician_clinical_resources' => [4]])['show_related_resource_section'])->toBeFalse()
        ->and(single_physician([], ['queries' => [[], [], [4]]])['show_related_resource_section'])->toBeFalse();
});

test('the research section shows for any one of its fields', function (string $field): void {
    expect(single_physician([$field => 'x'])['show_research_section'])->toBeTrue();
})->with([
    'research bio' => ['physician_research_bio'],
    'research interests' => ['physician_research_interests'],
    'publications' => ['physician_select_publications'],
    'PubMed author id' => ['physician_pubmed_author_id'],
    'research profiles link' => ['physician_research_profiles_link'],
]);

test('the research section stays hidden without its fields', function (): void {
    expect(single_physician()['show_research_section'])->toBeFalse();
});

test('the conditions section needs conditions that the query finds and a visible ontology', function (): void {
    $fields = ['physician_conditions_cpt' => [1]];
    $hidden = ['queries' => [[1]], 'rules' => [['remove_regions' => null, 'remove_service_lines' => [7]]]];

    expect(single_physician($fields, ['queries' => [[1]]])['show_conditions_section'])->toBeTrue()
        ->and(single_physician($fields)['show_conditions_section'])->toBeFalse()
        ->and(single_physician([], ['queries' => [[1]]])['show_conditions_section'])->toBeFalse()
        ->and(single_physician($fields + ['physician_service_line' => 7], $hidden)['show_conditions_section'])->toBeFalse();
});

test('the treatments section needs treatments that the query finds and a visible ontology', function (): void {
    $fields = ['physician_treatments_cpt' => [1]];
    $hidden = ['queries' => [[], [1]], 'rules' => [['remove_regions' => null, 'remove_service_lines' => [7]]]];

    expect(single_physician($fields, ['queries' => [[], [1]]])['show_treatments_section'])->toBeTrue()
        ->and(single_physician($fields)['show_treatments_section'])->toBeFalse()
        ->and(single_physician([], ['queries' => [[], [1]]])['show_treatments_section'])->toBeFalse()
        ->and(single_physician($fields + ['physician_service_line' => 7], $hidden)['show_treatments_section'])->toBeFalse();
});

test('the areas of expertise section needs a published area and a visible ontology', function (): void {
    $fields = ['physician_expertise' => [30, 31]];
    $options = ['status' => [30 => 'draft', 31 => 'publish']];
    $hidden = $options + ['rules' => [['remove_regions' => null, 'remove_service_lines' => [7]]]];

    expect(single_physician($fields, $options)['expertise_valid'])->toBeTrue()
        ->and(single_physician($fields, $options)['show_aoe_section'])->toBeTrue()
        ->and(single_physician($fields, ['status' => [30 => 'draft']])['show_aoe_section'])->toBeFalse()
        ->and(single_physician($fields + ['physician_service_line' => 7], $hidden)['show_aoe_section'])->toBeFalse();
});

test('the locations section needs a published location', function (): void {
    expect(single_physician(['physician_locations' => [21]], ['status' => [21 => 'publish']])['show_locations_section'])->toBeTrue()
        ->and(single_physician(['physician_locations' => [21]], ['status' => [21 => 'draft']])['show_locations_section'])->toBeFalse()
        ->and(single_physician()['show_locations_section'])->toBeFalse();
});

test('the ratings section needs a valid rating', function (): void {
    expect(single_physician([], ['ratings' => single_physician_rating()])['show_ratings_section'])->toBeTrue()
        ->and(single_physician()['show_ratings_section'])->toBeFalse();
});

test('the jump links need at least two sections', function (): void {
    expect(single_physician()['jump_link_count'])->toBe(0)
        ->and(single_physician()['show_jump_links_section'])->toBeFalse()
        ->and(single_physician(['physician_podcast_name' => 'Pod'])['jump_link_count'])->toBe(1)
        ->and(single_physician(['physician_podcast_name' => 'Pod'])['show_jump_links_section'])->toBeFalse()
        ->and(single_physician(['physician_podcast_name' => 'Pod', 'physician_eligible_appointments' => true])['jump_link_count'])->toBe(2)
        ->and(single_physician(['physician_podcast_name' => 'Pod', 'physician_eligible_appointments' => true])['show_jump_links_section'])->toBeTrue();
});

test('every section with content adds one jump link', function (): void {
    $vars = single_physician(
        [
            'physician_eligible_appointments' => true,
            'physician_boards' => [1],
            'physician_podcast_name' => 'Pod',
            'physician_clinical_resources' => [4],
            'physician_research_bio' => 'x',
            'physician_conditions_cpt' => [1],
            'physician_treatments_cpt' => [1],
            'physician_expertise' => [30],
            'physician_locations' => [21],
        ],
        ['status' => [21 => 'publish', 30 => 'publish'], 'queries' => [[1], [1], [4]], 'ratings' => single_physician_rating()],
    );

    expect($vars['jump_link_count'])->toBe(10)
        ->and($vars['title_append'])->toBe(' by '.$vars['short_name']);
});
