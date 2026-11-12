<?php

/**
 * The related conditions and treatments lists, the location phone table and the area of expertise card
 * (templates/loops, templates/fwp), pinned by the HTML each prints for the variables the including template sets.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * A stand-in for a post query that holds the given posts.
 *
 * @param  list<object>  $posts
 */
function loop_partials_query(array $posts): object
{
    return (object) ['posts' => $posts];
}

/**
 * The heading and disclaimer each context gives the related conditions or treatments list.
 *
 * @return array<string, array{string, string, bool}>
 */
function loop_partials_contexts(string $kind): array
{
    if ($kind === 'conditions') {
        return [
            'single-provider' => ['single-provider', 'Conditions Diagnosed or Treated by Dr. Smith', true],
            'single-location' => ['single-location', 'Conditions Diagnosed or Treated at Clinic', true],
            'single-condition' => ['single-condition', 'Conditions Related to Name', false],
            'single-treatment' => ['single-treatment', 'Related Conditions', false],
            'single-expertise' => ['single-expertise', 'Related Conditions', true],
            'single-resource' => ['single-resource', 'Related Conditions', false],
            'unknown' => ['other', 'Conditions', false],
        ];
    }

    return [
        'single-provider' => ['single-provider', 'Medical Treatments and Procedures Performed or Prescribed by Dr. Smith', true],
        'single-location' => ['single-location', 'Medical Treatments and Procedures Performed or Prescribed at Clinic', true],
        'single-condition' => ['single-condition', 'Medical Treatments and Procedures Related to Name', false],
        'single-treatment' => ['single-treatment', 'Related Medical Treatments and Procedures', true],
        'single-expertise' => ['single-expertise', 'Related Medical Treatments and Procedures', true],
        'single-resource' => ['single-resource', 'Related Medical Treatments and Procedures', false],
        'unknown' => ['other', 'Medical Treatments and Procedures', false],
    ];
}

test('the related conditions list heads itself for the page it is on', function (string $context, string $heading, bool $disclaimer): void {
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://example.test/c/'.$id.'/');
    $posts = [(object) ['ID' => 5, 'post_title' => 'Asthma'], (object) ['ID' => 6, 'post_title' => 'Gout']];
    $heading_name = $context === 'single-provider' ? 'Dr. Smith' : ($context === 'single-location' ? 'Clinic' : 'Name');

    $output = uamswp_test_include_partial('loops/conditions-cpt-loop', [
        'condition_context' => $context,
        'condition_heading_related_name' => $heading_name,
        'conditions_cpt_query' => loop_partials_query($posts),
    ]);

    expect($output)->toContain('<span class="title">'.$heading.'</span>')
        ->and(str_contains($output, '<p class="note">UAMS Health providers care for a broad range of conditions, some of which may not be listed below.</p>'))->toBe($disclaimer)
        ->and($output)->toContain('href="https://example.test/c/5/" aria-label="Go to Condition page for Asthma"')
        ->and(substr_count($output, '<li>'))->toBe(2);
})->with(array_values(loop_partials_contexts('conditions')));

test('the related treatments list heads itself for the page it is on', function (string $context, string $heading, bool $disclaimer): void {
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://example.test/t/'.$id.'/');
    $posts = [(object) ['ID' => 8, 'post_title' => 'Bypass']];
    $heading_name = $context === 'single-provider' ? 'Dr. Smith' : ($context === 'single-location' ? 'Clinic' : 'Name');

    $output = uamswp_test_include_partial('loops/treatments-cpt-loop', [
        'treatment_context' => $context,
        'treatment_heading_related_name' => $heading_name,
        'treatments_cpt_query' => loop_partials_query($posts),
    ]);

    expect($output)->toContain('<span class="title">'.$heading.'</span>')
        ->and(str_contains($output, '<p class="note">UAMS Health providers perform and prescribe a broad range of treatments and procedures, some of which may not be listed below.</p>'))->toBe($disclaimer)
        ->and($output)->toContain('<a href="https://example.test/t/8/" aria-label="Go to Treatment page for Bypass" class="btn btn-outline-primary">Bypass</a>');
})->with(array_values(loop_partials_contexts('treatments')));

test('the related lists print no items without posts', function (string $partial, string $variable): void {
    $output = uamswp_test_include_partial($partial, [
        'condition_context' => 'single-resource',
        'treatment_context' => 'single-resource',
        $variable => loop_partials_query([]),
    ]);

    expect($output)->not->toContain('<li>');
})->with([
    'conditions' => ['loops/conditions-cpt-loop', 'conditions_cpt_query'],
    'treatments' => ['loops/treatments-cpt-loop', 'treatments_cpt_query'],
]);

/**
 * Stub the post loop and the phone fields for the location table, with one location per entry of $locations.
 *
 * @param  list<array<string, mixed>>  $locations  The ACF fields of each location, with `title` and `link`.
 */
function loop_partials_locations(array $locations): void
{
    $position = -1;
    $current = [];
    when('have_posts')->alias(static function () use (&$position, $locations): bool {
        return $position + 1 < count($locations);
    });
    when('the_post')->alias(static function () use (&$position, $locations, &$current): void {
        $position++;
        $current = $locations[$position];
    });
    when('get_the_ID')->alias(static function () use (&$position): int {
        return $position + 100;
    });
    when('get_permalink')->alias(static function () use (&$current): string {
        return (string) $current['link'];
    });
    when('get_the_title')->alias(static function () use (&$current): string {
        return (string) $current['title'];
    });
    when('get_field')->alias(static function (string $name) use (&$current): mixed {
        return $current[$name] ?? null;
    });
    when('format_phone_dash')->alias(static fn (string $phone): string => 'dash:'.$phone);
    when('format_phone_us')->alias(static fn (string $phone): string => 'us:'.$phone);
}

test('the location table says so when no location matched', function (): void {
    when('have_posts')->justReturn(false);
    when('_e')->alias(static function (string $text): void {
        echo $text;
    });

    $output = uamswp_test_include_partial('fwp/location-list');

    expect($output)->toContain('<p>Sorry, no locations matched your criteria.</p>')
        ->and($output)->not->toContain('<table');
});

test('the location table lists the name and the phone numbers of each location', function (): void {
    loop_partials_locations([
        ['title' => 'Clinic A', 'link' => 'https://example.test/a/', 'location_phone' => '5015551111'],
        ['title' => 'Clinic B', 'link' => 'https://example.test/b/'],
    ]);

    $output = uamswp_test_include_partial('fwp/location-list');

    expect($output)->toContain('<th scope="row"><a href="https://example.test/a/">Clinic A</a></th>')
        ->and($output)->toContain('<th scope="row"><a href="https://example.test/b/">Clinic B</a></th>')
        ->and($output)->toContain('<a href="tel:dash:5015551111" class="icon-phone">us:5015551111</a>')
        ->and($output)->toContain('<dt>New and Returning Patients</dt>')
        ->and(substr_count($output, '&nbsp;'))->toBe(2)
        ->and(substr_count($output, '<tr>'))->toBe(3);
});

test('the location table shows the new patient number, and the returning one only when the query asks for it', function (): void {
    loop_partials_locations([[
        'title' => 'Clinic',
        'link' => 'https://example.test/a/',
        'location_new_appointments_phone' => '5012222222',
        'location_clinic_phone_query' => true,
        'location_return_appointments_phone' => '5013333333',
        'field_location_appointment_phone_query' => true,
    ]]);

    $output = uamswp_test_include_partial('fwp/location-list');

    expect($output)->toContain('<dt>New Patients</dt>')
        ->and($output)->toContain('<dd><a href="tel:dash:5012222222" class="icon-phone">us:5012222222</a></dd>')
        ->and($output)->toContain('<dt>Returning Patients</dt>')
        ->and($output)->toContain('<dd><a href="tel:dash:5013333333" class="icon-phone">us:5013333333</a></dd>');
});

test('the location table labels the new appointment number for everyone when the query is off', function (): void {
    loop_partials_locations([[
        'title' => 'Clinic',
        'link' => 'https://example.test/a/',
        'location_new_appointments_phone' => '5012222222',
        'location_clinic_phone_query' => true,
        'location_return_appointments_phone' => '5013333333',
    ]]);

    $output = uamswp_test_include_partial('fwp/location-list');

    expect($output)->toContain('<dt>New and Returning Patients</dt>')
        ->and($output)->not->toContain('<dt>Returning Patients</dt>')
        ->and($output)->not->toContain('us:5013333333');
});

test('the location table falls back to the main number when the appointment query is missing', function (): void {
    loop_partials_locations([[
        'title' => 'Clinic',
        'link' => 'https://example.test/a/',
        'location_phone' => '5014444444',
        'location_new_appointments_phone' => '5012222222',
    ]]);

    $output = uamswp_test_include_partial('fwp/location-list');

    expect(substr_count($output, 'us:5014444444'))->toBe(2)
        ->and($output)->not->toContain('us:5012222222');
});

/**
 * Stub the functions the area of expertise card reads.
 *
 * @param  array<int, int>  $parents  The parent id by post id.
 * @param  array<int, object>  $posts  The posts by id.
 */
function loop_partials_expertise(string $title, string $excerpt, array $parents = [], array $posts = [], bool $thumbnail = false): void
{
    when('get_the_title')->alias(static fn (int $id): string => $id === 1 ? $title : ($posts[$id]->post_title ?? ''));
    when('wp_get_post_parent_id')->alias(static fn (int $id): int => $parents[$id] ?? 0);
    when('get_post')->alias(static fn (int $id): ?object => $posts[$id] ?? null);
    when('get_permalink')->alias(static fn (int $id): string => 'https://example.test/e/'.$id.'/');
    when('get_the_excerpt')->justReturn($excerpt);
    when('get_the_content')->justReturn('<i>Body text</i>');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => $words.' words'.$more);
    when('has_post_thumbnail')->justReturn($thumbnail);
    when('get_the_post_thumbnail')->alias(static fn (int $id, string $size, array $attributes): string => '<img data-size="'.$size.'" data-class="'.$attributes['class'].'">');
    when('esc_url')->alias(static fn (string $url): string => $url);
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
}

test('the area of expertise card shows the post thumbnail and links to the page', function (): void {
    loop_partials_expertise('Cardiology', 'Heart care.', thumbnail: true);

    $output = uamswp_test_include_partial('loops/expertise-card', ['id' => 1]);

    expect($output)->toContain('<img data-size="aspect-16-9-small" data-class="card-img-top">')
        ->and($output)->not->toContain('no-image_16-9')
        ->and($output)->toContain('<span class="name"><a href="https://example.test/e/1/" target="_self" aria-label="View Area of Expertise page forCardiology" data-categorytitle="Name" data-itemtitle="Cardiology">Cardiology</a></span>')
        ->and($output)->toContain('<p class="card-text">Heart care.</p>')
        ->and($output)->not->toContain('Part of');
});

test('the area of expertise card uses the placeholder image without a thumbnail', function (): void {
    loop_partials_expertise('Cardiology', 'Heart care.');

    $output = uamswp_test_include_partial('loops/expertise-card', ['id' => 1]);

    expect($output)->toContain('no-image_16-9.svg')
        ->and($output)->toContain('no-image_16-9.jpg')
        ->and($output)->not->toContain('data-size=');
});

test('the area of expertise card names its parent unless it is listed under that parent', function (): void {
    $parent = (object) ['ID' => 2, 'post_title' => 'Mom&#8217;s "Parent"'];
    loop_partials_expertise('Child', 'Text.', [1 => 2], [2 => $parent]);

    $output = uamswp_test_include_partial('loops/expertise-card', ['id' => 1]);

    expect($output)->toContain('Part of <a href="https://example.test/e/2/" aria-label="Go to Area of Expertise page for Mom&#039;s &#039;Parent&#039;" data-categorytitle="Parent Name" data-itemtitle="Child">Mom&#8217;s "Parent"</a>');

    $listed = uamswp_test_include_partial('loops/expertise-card', ['id' => 1, 'child_expertise_list' => true]);

    expect($listed)->not->toContain('Part of');
});

test('the area of expertise card has no parent line when the parent post cannot be found', function (): void {
    loop_partials_expertise('Child', 'Text.', [1 => 2], []);

    expect(uamswp_test_include_partial('loops/expertise-card', ['id' => 1]))->not->toContain('Part of');
});

test('the area of expertise card cleans the title for the attributes', function (): void {
    loop_partials_expertise('Mom&#8217;s "best" care', 'Text.');

    $output = uamswp_test_include_partial('loops/expertise-card', ['id' => 1]);

    expect($output)->toContain('data-itemtitle="Mom\'s \'best\' care"')
        ->and($output)->toContain('>Mom&#8217;s "best" care</a></span>');
});

test('the area of expertise card takes the text of the content when there is no excerpt, and trims a long one', function (): void {
    loop_partials_expertise('Title', '');

    expect(uamswp_test_include_partial('loops/expertise-card', ['id' => 1]))->toContain('<p class="card-text">Body text</p>');

    loop_partials_expertise('Title', str_repeat('a', 160));

    expect(uamswp_test_include_partial('loops/expertise-card', ['id' => 1]))->toContain('<p class="card-text">'.str_repeat('a', 160).'</p>');

    loop_partials_expertise('Title', str_repeat('a', 161));

    expect(uamswp_test_include_partial('loops/expertise-card', ['id' => 1]))->toContain('<p class="card-text">23 words &hellip;</p>');
});

test('the area of expertise loop prints a card for each post, or says nothing matched', function (): void {
    loop_partials_expertise('Cardiology', 'Heart care.');
    $remaining = 2;
    when('have_posts')->alias(static function () use (&$remaining): bool {
        return $remaining > 0;
    });
    when('the_post')->alias(static function () use (&$remaining): void {
        $remaining--;
    });
    when('get_the_ID')->justReturn(1);

    $output = uamswp_test_include_partial('fwp/expertise-loop');

    expect(substr_count($output, '<div class="card">'))->toBe(2);

    when('have_posts')->justReturn(false);
    when('_e')->alias(static function (string $text): void {
        echo $text;
    });

    expect(uamswp_test_include_partial('fwp/expertise-loop'))->toContain('<p>Sorry, no locations matched your criteria.</p>');
});
