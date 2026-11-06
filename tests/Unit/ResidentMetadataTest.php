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
