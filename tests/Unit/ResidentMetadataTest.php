<?php

/**
 * Provider metadata formatting and optional-field contracts.
 *
 * Nine approved equivalent mutations are ignored in get_provider_meta():
 * three specialty initializers are overwritten before use or never read;
 * the false-chief label is never selected; the location counter is
 * unread before its reset (two initializer changes and one increment); and
 * the board and association separators compare a string accumulator, so
 * loose and strict inequality agree. Other mutations on those expressions
 * remain enabled.
 *
 * @command  vendor/bin/pest --ci tests/Unit/ResidentMetadataTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/query-posts.php';

beforeEach(function (): void {
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

    when('get_post_meta')->justReturn('');
    when('get_post_thumbnail_id')->justReturn(0);
    when('image_sizer')->justReturn();
    when('wp_trim_words')->justReturn('');
    when('esc_js')->returnArg();

    // Leave unrelated fields empty while keeping optional groups well-shaped.
    $this->providerFields = [
        'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 0,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 0,
        ],
        'physician_locations' => [],
    ];
    when('get_field')->alias(fn (string $field, mixed $id): mixed => $id === 101
        ? ($this->providerFields[$field] ?? false) : false);
    when('get_term')->justReturn();
    when('is_wp_error')->justReturn(false);

    // Start each test with no queued or recorded relationship queries.
    reset_query_stand_in();
});

test('provider residency metadata resolves term objects and preserves chief selection', function (bool $resident, bool $chief, string $term_result, string $expected): void {
    $error = (object) ['errors' => ['invalid_taxonomy' => ['Invalid taxonomy']]];
    when('is_wp_error')->alias(fn ($value): bool => $value === $error);
    when('get_term')->alias(fn ($id, string $taxonomy) => match ([$id, $taxonomy]) {
        [17, 'academic_department'] => match ($term_result) {
            'missing', 'missing department' => null,
            'error' => $error,
            default => (object) ['name' => 'Internal Medicine'],
        },
        [29, 'residency_year'] => match ($term_result) {
            'missing', 'missing year' => null,
            'error' => $error,
            default => (object) ['name' => 'PGY-2'],
        },
        default => null,
    });
    when('get_field')->alias(fn (string $field, $id): bool|array|string => match ($field) {
        'physician_resident' => $resident,
        'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 17,
            'physician_resident_academic_chief' => $chief,
            'physician_resident_academic_year' => 29,
        ],
        'physician_locations' => [],
        default => '',
    });

    $data = get_provider_meta(['id' => 101]);

    expect($data['physician_residency_program'])->toBe($expected);
})->with([
    'non-chief resident' => [true, false, 'valid', 'Internal Medicine, PGY-2'],
    'chief resident' => [true, true, 'valid', 'Internal Medicine, Chief Resident'],
    'missing terms' => [true, false, 'missing', ', '],
    'missing department' => [true, false, 'missing department', ', PGY-2'],
    'missing year' => [true, false, 'missing year', 'Internal Medicine, '],
    'term lookup errors' => [true, false, 'error', ', '],
    'non-resident' => [false, false, 'valid', ''],
]);

test('provider portal metadata resolves the term name without changing its fields', function (string $result): void {
    $error = (object) ['errors' => ['invalid_taxonomy' => ['Invalid taxonomy']]];
    $portal = match ($result) {
        'valid' => (object) ['name' => 'MyChart Patient Portal'],
        'error' => $error,
        default => null,
    };
    when('is_wp_error')->alias(fn ($value): bool => $value === $error);
    when('get_term')->alias(fn ($id, string $taxonomy) => [$id, $taxonomy] === [41, 'portal'] ? $portal : null);
    when('get_field')->alias(fn (string $field, $id): array|int|string => match (true) {
        $field === 'physician_portal' && $id === 101 => 41,
        $field === 'portal_content' && $id === $portal => 'Sign in to your account',
        $field === 'portal_url' && $id === $portal => 'https://example.test/portal',
        $field === 'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 0,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 0,
        ],
        $field === 'physician_locations' => [],
        default => '',
    });

    $data = get_provider_meta(['id' => 101]);

    expect($data['physician_portal'])->toBe([
        'name' => $result === 'valid' ? 'MyChart Patient Portal' : '',
        'content' => 'Sign in to your account',
        'url' => 'https://example.test/portal',
    ]);
})->with(['valid', 'missing', 'error']);

test('provider publications preserve row order and one-based keys', function (array|false $publications, array $expected): void {
    when('get_term')->justReturn();
    when('is_wp_error')->justReturn(false);
    when('get_field')->alias(fn (string $field, $id): array|bool|string => match ($field) {
        'physician_select_publications' => $publications,
        'physician_resident_profile_group' => [
            'physician_resident_academic_department' => 0,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 0,
        ],
        'physician_locations' => [],
        default => '',
    });

    $data = get_provider_meta(['id' => 101]);

    if ($expected === []) {
        expect($data)->not->toHaveKey('physician_pubmed');
    } else {
        expect($data['physician_pubmed'])->toBe($expected);
    }
})->with([
    'absent selection' => [false, []],
    'empty selection' => [[], []],
    'selected rows' => [[
        ['pubmed_id_pmid' => '10000001', 'pubmed_information' => 'Zulu publication'],
        ['pubmed_id_pmid' => '10000002', 'pubmed_information' => 'Alpha publication'],
        ['pubmed_id_pmid' => '10000003', 'pubmed_information' => ''],
    ], [
        1 => ['information' => 'Zulu publication'],
        2 => ['information' => 'Alpha publication'],
        3 => ['information' => ''],
    ]],
]);

test('provider names preserve optional middle names, pedigrees, and degree punctuation', function (
    string $middle,
    string $pedigree,
    array $degrees,
    string $expected,
): void {
    // Keep every component distinct so removal or reordering changes the name.
    $this->providerFields += [
        'physician_first_name' => 'Ada',
        'physician_middle_name' => $middle,
        'physician_last_name' => 'Byron',
        'physician_pedigree' => $pedigree,
        'physician_degree' => $degrees,
    ];
    when('get_term')->alias(fn (mixed $id, string $taxonomy): ?object => match ([$id, $taxonomy]) {
        [17, 'degree'] => (object) ['name' => 'M.D.'],
        [29, 'degree'] => (object) ['name' => 'Ph.D.'],
        default => null,
    });

    // Pin the exported name rather than the concatenation's intermediate values.
    expect(get_provider_meta(['id' => 101])['physician_full_name'])->toBe($expected);
})->with([
    'all components' => ['Augusta', 'Jr.', [17, 29], 'Ada Augusta Byron&nbsp;Jr., M.D., Ph.D.'],
    'middle name only' => ['Augusta', '', [], 'Ada Augusta Byron'],
    'pedigree only' => ['', 'Jr.', [], 'Ada Byron&nbsp;Jr.'],
    'one degree only' => ['', '', [29], 'Ada Byron, Ph.D.'],
    'no optional components' => ['', '', [], 'Ada Byron'],
]);

test('unselected provider lists and specialty fields export empty strings', function (): void {
    // Require empty output, not merely a present key, for every optional list.
    $data = get_provider_meta(['id' => 101]);
    foreach ([
        'physician_title', 'physician_service_line', 'physician_patient_types',
        'physician_languages', 'physician_boards', 'physician_associations',
        'physician_conditions_list', 'physician_treatments_list', 'physician_expertise_list',
    ] as $field) {
        expect($data[$field])->toBe('');
    }
});

test('provider occupation and appointment eligibility distinguish residents and specialty fallbacks', function (
    bool $resident,
    int|false $specialty,
    string $occupation,
    string $expected,
    int $eligible,
): void {
    // Supply a nonzero eligibility and a distinct term label and occupation.
    $this->providerFields += [
        'physician_resident' => $resident,
        'physician_title' => $specialty,
        'physician_eligible_appointments' => 37,
        'physician_service_line' => 29,
    ];
    $specialty_term = (object) ['name' => 'Cardiology'];
    when('get_term')->alias(fn (mixed $id, string $taxonomy): ?object => match ([$id, $taxonomy]) {
        [17, 'clinical_title'] => $specialty_term,
        [29, 'service_line'] => (object) ['name' => 'Heart Care'],
        default => null,
    });
    when('get_field')->alias(fn (string $field, mixed $id): mixed => $field === 'clinical_specialization_title' && $id === $specialty_term
        ? $occupation : ($id === 101 ? ($this->providerFields[$field] ?? false) : false));

    // Observe the resident override and both specialty fallback paths publicly.
    $data = get_provider_meta(['id' => 101]);
    expect($data['physician_title'])->toBe($expected)
        ->and($data['physician_eligible_appointments'])->toBe($eligible)
        ->and($data['physician_service_line'])->toBe('Heart Care');
})->with([
    'resident overrides selected specialty' => [true, 17, 'Cardiologist', 'Resident Physician', 0],
    'explicit occupation' => [false, 17, 'Cardiologist', 'Cardiologist', 37],
    'term-name fallback' => [false, 17, '', 'Cardiology', 37],
    'no specialty' => [false, false, '', '', 37],
    'missing specialty term' => [false, 83, '', '', 37],
]);

test('provider short biography uses its explicit value or the thirty-word fallback', function (
    string $short,
    string $expected,
): void {
    // Place distinct words on both sides of the thirty-word truncation boundary.
    $this->providerFields += [
        'physician_short_clinical_bio' => $short,
        'physician_clinical_bio' => str_repeat('Care ', 29).'Boundary Beyond',
    ];
    when('wp_trim_words')->alias(fn (string $text, int $limit, string $more): string => implode(
        ' ',
        \array_slice(explode(' ', $text), 0, $limit),
    ).$more);

    // Preserve both the selected source and the fallback length and suffix.
    expect(get_provider_meta(['id' => 101])['physician_short_clinical_bio'])->toBe($expected);
})->with([
    'explicit biography' => ['Focused biography', 'Focused biography'],
    'generated biography' => ['', str_repeat('Care ', 29).'Boundary &hellip;'],
]);

test('provider accepting-patient metadata is the single stored value', function (): void {
    // Model WordPress's different return shape when single-value mode is absent.
    when('get_post_meta')->alias(fn (int $id, string $key, bool $single = false): mixed => match ([$id, $key]) {
        [101, 'physician_accepting_patients'] => $single ? '0' : ['0'],
        default => $single ? '' : [],
    });

    expect(get_provider_meta(['id' => 101])['physician_accepting_new_patients'])->toBe('0');
});

test('provider portraits preserve source selection, dimensions, and crop settings', function (): void {
    // Distinguish the requested provider thumbnail from the current global photo.
    when('get_post_thumbnail_id')->alias(fn (?int $id = null): int => $id === 101 ? 17 : 29);
    when('image_sizer')->alias(fn (
        int $id,
        int $width,
        int $height,
        string $horizontal,
        string $vertical,
        string $crop,
    ): string => "{$id}:{$width}x{$height}:{$horizontal}:{$vertical}:{$crop}");

    // Inspect the returned image payloads rather than mock call order.
    $data = get_provider_meta(['id' => 101]);
    expect($data['physician_thumbnail'])->toBe('17:253x337:center:center:portrait-3-4')
        ->and($data['physician_photo'])->toBe('29:778x1038:center:center:portrait-3-4');
});

test('provider podcast retains its script, escaped name placement, and widget container', function (): void {
    // Keep the escaped name visibly distinct from its unescaped source.
    $this->providerFields['physician_podcast_name'] = 'Ada & Podcast';
    when('esc_js')->alias(fn (string $value): string => str_replace('&', '&amp;', $value));

    // Require all three segments in their rendered order, not just substrings.
    $widget = get_provider_meta(['id' => 101])['provider_podcast'];
    expect($widget)->toStartWith('<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">')
        ->toContain('radiomd_embedded_filtered_doctor("uams","radiomd-embedded-filtered-doctor",303,1837,"Ada &amp; Podcast");')
        ->toEndWith('<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>');
});

test('provider PubMed count preserves an explicit value and its legacy fallback', function (
    mixed $count,
    mixed $expected,
): void {
    // Keep the input's type because the metadata callback does not normalize it.
    $this->providerFields['physician_author_number'] = $count;

    expect(get_provider_meta(['id' => 101])['physician_pubmed_count'])->toBe($expected);
})->with([
    'explicit text count' => ['7', '7'],
    'explicit numeric count' => [11, 11],
    'missing count' => [false, '3'],
    'empty count' => ['', '3'],
    'zero count uses fallback' => ['0', '3'],
]);

test('provider taxonomy lists join term names in selection order and keep a missing term as an empty entry', function (): void {
    // Place a missing term between two resolved terms in every list.
    $this->providerFields += [
        'physician_first_name' => 'Ada',
        'physician_last_name' => 'Byron',
        'physician_degree' => [17, 83, 29],
        'physician_patient_types' => [31, 83, 32],
        'physician_languages' => [41, 83, 42],
        'physician_boards' => [51, 83, 52],
        'physician_associations' => [61, 83, 62],
        'physician_service_line' => 83,
    ];
    when('get_term')->alias(fn (mixed $id, string $taxonomy): ?object => match ([$id, $taxonomy]) {
        [17, 'degree'] => (object) ['name' => 'M.D.'],
        [29, 'degree'] => (object) ['name' => 'Ph.D.'],
        [31, 'patient_type'] => (object) ['name' => 'Adults'],
        [32, 'patient_type'] => (object) ['name' => 'Children'],
        [41, 'language'] => (object) ['name' => 'English'],
        [42, 'language'] => (object) ['name' => 'Spanish'],
        [51, 'board'] => (object) ['name' => 'Internal Medicine'],
        [52, 'board'] => (object) ['name' => 'Cardiology'],
        [61, 'association'] => (object) ['name' => 'American Heart Association'],
        [62, 'association'] => (object) ['name' => 'American College of Physicians'],
        default => null,
    });

    // Pin each joined list, including its separator and the empty entry.
    $data = get_provider_meta(['id' => 101]);
    expect([
        $data['physician_full_name'], $data['physician_patient_types'], $data['physician_languages'],
        $data['physician_boards'], $data['physician_associations'], $data['physician_service_line'],
    ])->toBe([
        'Ada Byron, M.D., , Ph.D.',
        'Adults, , Children',
        'English, , Spanish',
        'Internal Medicine,,Cardiology',
        'American Heart Association,,American College of Physicians',
        '',
    ]);
});

test('provider occupation is empty when the specialty term lookup returns an error', function (): void {
    // A term lookup error is an object without a name or a specialization title.
    $error = (object) ['errors' => ['invalid_taxonomy' => ['Invalid taxonomy']]];
    $this->providerFields['physician_title'] = 17;
    when('get_term')->alias(fn (mixed $id, string $taxonomy): ?object => [$id, $taxonomy] === [17, 'clinical_title'] ? $error : null);

    expect(get_provider_meta(['id' => 101])['physician_title'])->toBe('');
});

test('provider education and academic roles keep row order with zero-based keys and empty missing terms', function (): void {
    // Give every taxonomy its own term so a crossed field or taxonomy cannot pass.
    $this->providerFields += [
        'physician_education' => [
            ['education_type' => 11, 'school' => 12, 'description' => 'Internal medicine residency'],
            ['education_type' => 83, 'school' => 83, 'description' => ''],
        ],
        'physician_academic_appointment' => [
            ['academic_title' => 'Text title', 'academic_title_tax' => 21, 'department' => 22],
            ['academic_title' => '', 'academic_title_tax' => 83, 'department' => 83],
        ],
        'physician_academic_admin_title' => [
            ['academic_admin_title_tax' => 31, 'department' => 32],
            ['academic_admin_title_tax' => 83, 'department' => 83],
        ],
    ];
    when('get_term')->alias(fn (mixed $id, string $taxonomy): ?object => match ([$id, $taxonomy]) {
        [11, 'educationtype'] => (object) ['name' => 'Residency'],
        [12, 'school'] => (object) ['name' => 'College of Medicine'],
        [21, 'academic_title'] => (object) ['name' => 'Professor'],
        [22, 'academic_department'] => (object) ['name' => 'Surgery'],
        [31, 'academic_admin_title'] => (object) ['name' => 'Chair'],
        [32, 'academic_department'] => (object) ['name' => 'Pediatrics'],
        default => null,
    });

    // Compare complete rows rather than individual names.
    $data = get_provider_meta(['id' => 101]);
    expect($data['physician_education'])->toBe([
        ['type' => 'Residency', 'school' => 'College of Medicine', 'description' => 'Internal medicine residency'],
        ['type' => '', 'school' => '', 'description' => ''],
    ])->and($data['physician_faculty_appointment'])->toBe([
        ['department' => 'Surgery', 'title' => 'Professor'],
        ['department' => '', 'title' => ''],
    ])->and($data['physician_academic_admin_role'])->toBe([
        ['department' => 'Pediatrics', 'title' => 'Chair'],
        ['department' => '', 'title' => ''],
    ]);
});

test('provider repeater sections are omitted when no rows are selected', function (array|false $rows): void {
    foreach (['physician_education', 'physician_academic_appointment', 'physician_academic_admin_title'] as $field) {
        $this->providerFields[$field] = $rows;
    }

    expect(get_provider_meta(['id' => 101]))->not->toHaveKeys([
        'physician_education', 'physician_faculty_appointment', 'physician_academic_admin_role',
    ]);
})->with(['absent' => [false], 'empty' => [[]]]);

test('provider relationship lists join published query results in their returned order', function (
    string $field,
    string $type,
    string $key,
): void {
    // Return the posts in an order that differs from the selection.
    $this->providerFields[$field] = [302, 301];
    WP_Query::$queue = [[query_post(301, 'Alpha', 'alpha'), query_post(302, 'Beta', 'beta')]];
    when('get_permalink')->alias(fn (int $id): string => 'https://example.test/content-'.$id.'/');

    // Pin the query, every exported item, and the joined list.
    $data = get_provider_meta(['id' => 101]);
    expect(WP_Query::$queries)->toBe([[
        'post_type' => $type,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
        'post__in' => [302, 301],
    ]])->and($data['physician_'.$key])->toBe([
        301 => ['link' => 'https://example.test/content-301/', 'title' => 'Alpha', 'slug' => 'alpha'],
        302 => ['link' => 'https://example.test/content-302/', 'title' => 'Beta', 'slug' => 'beta'],
    ])->and($data['physician_'.$key.'_list'])->toBe('Alpha, Beta');
})->with([
    'conditions' => ['physician_conditions_cpt', 'condition', 'conditions'],
    'treatments' => ['physician_treatments_cpt', 'treatment', 'treatments'],
    'expertise' => ['physician_expertise', 'expertise', 'expertise'],
]);

test('provider relationship lists stay empty when no selected post is published', function (): void {
    // Each query finds nothing although every field has a selection.
    foreach (['physician_conditions_cpt', 'physician_treatments_cpt', 'physician_expertise'] as $field) {
        $this->providerFields[$field] = [301];
    }

    $data = get_provider_meta(['id' => 101]);
    expect(WP_Query::$queries)->toHaveCount(3)
        ->and($data)->not->toHaveKeys(['physician_conditions', 'physician_treatments', 'physician_expertise'])
        ->and([$data['physician_conditions_list'], $data['physician_treatments_list'], $data['physician_expertise_list']])->toBe(['', '', '']);
});
