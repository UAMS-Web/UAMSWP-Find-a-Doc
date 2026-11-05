<?php

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

    when('get_post_meta')->justReturn('');
    when('get_post_thumbnail_id')->justReturn(0);
    when('image_sizer')->justReturn();
    when('wp_trim_words')->justReturn('');
    when('esc_js')->returnArg();
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
