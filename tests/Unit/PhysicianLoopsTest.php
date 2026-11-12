<?php

/**
 * The provider listing loops FacetWP prints: the provider loop (templates/fwp/physician-loop.php) and the older
 * Meta Box provider loop (templates/fwp/_physician-loop-mb.php).
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-functions.php';

/**
 * Stub the functions the provider loops read.
 *
 * @param  array<string, mixed>  $fields  Field values by name, for ACF and Meta Box alike.
 * @param  array<string, mixed>  $options  `terms` (`taxonomy:id` => name), `rating` (the syndicated rating data as JSON),
 *                                         `thumbnail` (whether the post has a featured image), `published` (the ids of
 *                                         the published locations; all others are drafts).
 */
function physician_loops_world(array $fields, array $options = []): void
{
    $terms = $options['terms'] ?? [];
    $published = $options['published'] ?? null;

    when('get_field')->alias(static fn (string $name, mixed $term = null): mixed => $term instanceof stdClass ? ($fields[$name.':term'] ?? null) : ($fields[$name] ?? null));
    when('rwmb_meta')->alias(static fn (string $name): mixed => $fields[$name] ?? null);
    when('get_term')->alias(static fn (mixed $id, string $taxonomy): object|false => isset($terms[$taxonomy.':'.$id]) ? (object) ['name' => $terms[$taxonomy.':'.$id]] : false);
    when('get_term_link')->alias(static fn (mixed $term): string => 'https://example.test/specialty/'.$term.'/');
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('get_the_title')->alias(static fn (mixed $id = 0): string => 'Location '.$id);
    when('get_post_status')->alias(static fn (mixed $id): string => $published === null || in_array($id, $published, true) ? 'publish' : 'draft');
    when('has_post_thumbnail')->justReturn($options['thumbnail'] ?? false);
    when('get_post_thumbnail_id')->justReturn(31);
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $image, int $width, int $height, string $x, string $y, string $class): string => 'sized-'.$image.'-'.$width.'x'.$height);
    when('the_post_thumbnail')->alias(static function (string $size): void {
        echo '[thumbnail '.$size.']';
    });
    when('get_post_meta')->alias(static fn (mixed $id, string $key): mixed => isset($options['rating']) ? ($options['rating_object'] ?? false ? json_decode($options['rating']) : $options['rating']) : '');
    when('wp_trim_words')->alias(static fn (mixed $text, int $words, string $more): string => 'TRIMMED('.$text.')'.$more);
    when('esc_url')->returnArg();
    when('__')->returnArg();
    when('_e')->alias(static function (string $text): void {
        echo $text;
    });
    when('wp_reset_postdata')->justReturn(null);

    WP_Query::$queue = [];
    WP_Query::$queries = [];
}

/**
 * Render the provider loop over the given posts and collapse the whitespace.
 *
 * @param  list<int>  $ids
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 */
function physician_loop(array $ids, array $fields = [], array $options = []): string
{
    physician_loops_world($fields + ['physician_first_name' => 'Jo', 'physician_last_name' => 'Smith', 'physician_locations' => []], $options + ['rating_object' => true]);
    uamswp_test_main_loop($ids);

    $output = uamswp_test_include_partial('fwp/physician-loop', ['post' => (object) ['ID' => 99]]);

    return trim((string) preg_replace('/\s+/', ' ', $output));
}

test('the provider loop prints a message without providers', function (): void {
    expect(physician_loop([]))->toBe('<p>Sorry, no providers matched your criteria.</p>');
});

test('the provider loop prints an entry for each provider and the ratings stylesheet once', function (): void {
    $output = physician_loop([5, 6]);

    expect(substr_count($output, '<div class="col item-container">'))->toBe(2)
        ->and(substr_count($output, 'docscores-lotw'))->toBe(1)
        ->and($output)->toContain('<span class="name">Jo Smith</span>')
        ->and($output)->toContain('<a href="https://example.test/p/0/" aria-label="Full profile for Jo Smith" class="stretched-link"')
        ->and($output)->not->toContain('Sorry');
});

test('the provider loop names the provider with the middle name, pedigree and degrees', function (): void {
    $output = physician_loop([5], [
        'physician_middle_name' => 'Q',
        'physician_pedigree' => 'Jr.',
        'physician_degree' => [1, 2],
    ], ['terms' => ['degree:1' => 'M.D.', 'degree:2' => 'Ph.D.']]);

    expect($output)->toContain('<span class="name">Jo Q Smith&nbsp;Jr., M.D., Ph.D.</span>')
        ->and($output)->toContain('aria-label="Full profile for Jo Q Smith Jr., M.D., Ph.D."')
        ->and($output)->toContain('data-itemtitle="Jo Q Smith Jr., M.D., Ph.D."');
});

test('the provider loop cleans quotes and the right single quote entity in the name for the labels', function (): void {
    $output = physician_loop([5], ['physician_first_name' => 'Jo "J"', 'physician_last_name' => 'O&#8217;Neil']);

    expect($output)->toContain('aria-label="Full profile for Jo \'J\' O\'Neil"');
});

test('the provider loop prints the occupation title of a resident, or of the clinical title', function (): void {
    expect(physician_loop([5], ['physician_resident' => true, 'physician_title' => 9], ['terms' => ['clinical_title:9' => 'Cardiologist']]))
        ->toContain('<span class="subtitle">Resident Physician</span>');

    expect(physician_loop([5], ['physician_title' => 9], ['terms' => ['clinical_title:9' => 'Cardiologist']]))
        ->toContain('<span class="subtitle">Cardiologist</span>');

    expect(physician_loop([5], ['physician_title' => 9, 'clinical_specialization_title:term' => 'Heart Surgeon'], ['terms' => ['clinical_title:9' => 'Cardiologist']]))
        ->toContain('<span class="subtitle">Heart Surgeon</span>');
});

test('the provider loop prints no occupation title without a resident flag or a clinical title it can find', function (): void {
    expect(physician_loop([5]))->not->toContain('subtitle')
        ->and(physician_loop([5], ['physician_title' => 9]))->not->toContain('subtitle');
});

test('the provider loop prints the sized portrait with a featured image, or the placeholder', function (): void {
    $with = physician_loop([5], [], ['thumbnail' => true]);

    expect($with)->toContain('<source srcset="sized-31-243x324" media="(min-width: 2054px)">')
        ->and($with)->toContain('<source srcset="sized-31-95x127" media="(min-width: 576px)">')
        ->and($with)->toContain('<img src="sized-31-184x245" alt="Jo Smith" />')
        ->and($with)->not->toContain('no-image_3-4');

    $without = physician_loop([5]);

    expect($without)->toContain('no-image_3-4.svg')
        ->and($without)->toContain('<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" />');
});

test('the provider loop says ratings are not available without an NPI', function (): void {
    $output = physician_loop([5]);

    expect($output)->toContain('<p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal"')
        ->and($output)->toContain('data-itemtitle="Jo Smith" aria-label="Learn why ratings are not available for this provider"');
});

test('the provider loop prints nothing about ratings when the NPI has no rating data', function (): void {
    $output = physician_loop([5], ['physician_npi' => '123']);

    expect($output)->not->toContain('Patient ratings are not available')
        ->and($output)->not->toContain('class="rating"');
});

test('the provider loop prints the rating for a provider with at least 30 ratings', function (): void {
    $rating = (string) json_encode(['data' => ['entities' => [['totalRatingCount' => 30, 'totalCommentCount' => 12, 'overallRating' => ['value' => 4.5]]]]]);

    $output = physician_loop([5], ['physician_npi' => '123'], ['rating' => $rating]);

    expect($output)->toContain('<div class="rating"><div class="star-ratings-sprite" title="4.5 out of 5"><div class="star-ratings-sprite-percentage" style="width: 90%;"></div></div><div class="ratings-count">30 Ratings</div></div>')
        ->and($output)->not->toContain('Patient ratings are not available');
});

test('the provider loop says ratings are not available for a provider with fewer than 30 ratings or none', function (int $count): void {
    $rating = (string) json_encode(['data' => ['entities' => [['totalRatingCount' => $count, 'totalCommentCount' => 1, 'overallRating' => ['value' => 4.5]]]]]);

    $output = physician_loop([5], ['physician_npi' => '123'], ['rating' => $rating]);

    expect($output)->toContain('Patient ratings are not available for this provider.')
        ->and($output)->not->toContain('class="rating"');
})->with([
    'a few' => [29],
    'none' => [0],
]);

test('the provider loop prints the short bio, or a trimmed clinical bio', function (): void {
    expect(physician_loop([5], ['physician_short_clinical_bio' => 'Short bio.', 'physician_clinical_bio' => 'Long bio.']))->toContain('<p>Short bio.</p>');

    expect(physician_loop([5], ['physician_clinical_bio' => 'Long bio.']))->toContain('<p>TRIMMED(Long bio.) &hellip;</p>');
});

test('the provider loop links the profile with the id of the current post', function (): void {
    $output = physician_loop([5]);

    expect($output)->toContain('<a class="btn btn-primary" href="https://example.test/p/99/" aria-label="Full profile for Jo Smith" data-categorytitle="View Full Profile" data-itemtitle="Jo Smith">Full Profile</a>');
});

test('the provider loop lists the published locations', function (): void {
    $output = physician_loop([5], ['physician_locations' => [11, 12, 13]], ['published' => [11, 13]]);

    expect($output)->toContain('<h4 class="h5">Locations</h4>')
        ->and($output)->toContain('<a href="https://example.test/p/11/" data-categorytitle="Related Location" data-typetitle="Location 11" data-itemtitle="Jo Smith"> Location 11 </a>')
        ->and($output)->toContain('Location 13')
        ->and($output)->not->toContain('data-typetitle="Location 12"')
        ->and(substr_count($output, '>Full Profile</a>'))->toBe(2);
});

test('the provider loop prints no locations column without a published location', function (): void {
    expect(physician_loop([5], ['physician_locations' => [12]], ['published' => []]))->not->toContain('Locations</h4>')
        ->and(physician_loop([5], ['physician_locations' => []]))->not->toContain('Locations</h4>');
});

test('the provider loop warns about a provider with no locations value', function (): void {
    $output = '';
    $warnings = uamswp_test_capture_warnings(static function () use (&$output): void {
        $output = physician_loop([5], ['physician_locations' => null]);
    });

    expect($warnings)->toBe(['foreach() argument must be of type array|object, null given'])
        ->and($output)->not->toContain('Locations</h4>');
});

/**
 * Render the Meta Box provider loop over the given posts and collapse the whitespace.
 *
 * @param  list<int>  $ids
 * @param  array<string, mixed>  $fields
 * @param  list<list<int>>  $locations  The location ids each provider's relationship query finds, in order.
 * @param  array<string, mixed>  $options
 */
function physician_loop_mb(array $ids, array $fields = [], array $locations = [], array $options = []): string
{
    physician_loops_world($fields + ['physician_first_name' => 'Jo', 'physician_last_name' => 'Smith'], $options + ['rating_object' => false]);
    uamswp_test_main_loop($ids);
    WP_Query::$queue = $locations;

    $output = '';
    $warnings = uamswp_test_capture_warnings(static function () use (&$output): void {
        $output = uamswp_test_include_partial('fwp/_physician-loop-mb', ['post' => (object) ['ID' => 99], 'id' => 5]);
    });
    physician_loops_warnings($warnings);

    return trim((string) preg_replace('/\s+/', ' ', $output));
}

/**
 * Keep, or return, the warnings of the last Meta Box loop render.
 *
 * @param  array<int, string>|null  $set
 * @return array<int, string>
 */
function physician_loops_warnings(?array $set = null): array
{
    static $kept = [];

    if ($set !== null) {
        $kept = $set;
    }

    return $kept;
}

test('the Meta Box provider loop prints a message without providers', function (): void {
    expect(physician_loop_mb([]))->toBe('<p>Sorry, no physicians matched your criteria.</p>');
});

test('the Meta Box provider loop alternates the row background and names the provider', function (): void {
    $output = physician_loop_mb([5, 6], [
        'physician_middle_name' => 'Q',
        'physician_pedigree' => 'Jr.',
        'physician_degree' => 'M.D.',
    ]);

    expect($output)->toContain('<div class="whiteBackground archive-box">')
        ->and($output)->toContain('<div class="grayBackground archive-box">')
        ->and($output)->toContain('itemprop="name">Jo Q Smith&nbsp;Jr., M.D.</h2>')
        ->and($output)->toContain('<a href="https://example.test/p/99/"><h2')
        ->and(substr_count($output, 'docscores-lotw'))->toBe(1);
});

test('the Meta Box provider loop prints the clinical title and department', function (): void {
    $both = physician_loop_mb([5], ['physician_clinical_title' => (object) ['name' => 'Cardiologist'], 'physician_department' => (object) ['name' => 'Medicine']]);

    expect($both)->toContain('<h4>Cardiologist, Medicine</h4>');

    $titleOnly = physician_loop_mb([5], ['physician_clinical_title' => (object) ['name' => 'Cardiologist']]);

    expect($titleOnly)->toContain('<h4>Cardiologist</h4>');

    $departmentOnly = physician_loop_mb([5], ['physician_department' => (object) ['name' => 'Medicine']]);

    expect($departmentOnly)->toContain('<h4>Medicine</h4>')
        ->and(physician_loop_mb([5]))->not->toContain('<h4>');
});

test('the Meta Box provider loop prints the thumbnail link', function (): void {
    expect(physician_loop_mb([5]))->toContain('<span><a href="https://example.test/p/99/" target="_self">[thumbnail medium]</a></span>');
});

test('the Meta Box provider loop says there are no reviews without an NPI', function (): void {
    $output = physician_loop_mb([5]);

    expect($output)->toContain('<div class="small bold">No Patient Satisfaction Reviews</div> <div><a href="#why_not_modal"')
        ->and($output)->not->toContain('<span class="ds-ratingcount">');
});

test('the Meta Box provider loop prints the rating for a provider with at least 30 ratings', function (): void {
    $rating = (string) json_encode(['data' => ['entities' => [['totalRatingCount' => 30, 'totalCommentCount' => 12, 'overallRating' => ['value' => 4.5]]]]]);

    $output = physician_loop_mb([5], ['physician_npi' => '123'], [], ['rating' => $rating]);

    expect($output)->toContain('<div class="text-center"><div class="ds-title">Patient Rating</div><div><span class="ds-stars ds-stars4.5"></span></div><div class="ds-xofy"><span class="ds-average">4.5</span><span class="ds-average-max">out of 5</span></div><div class="ds-ratings"><span class="ds-ratingcount">30</span> Ratings</div><div class="ds-comments"><span class="ds-commentcount">12</span> Comments</div></div>');
});

test('the Meta Box provider loop says there are no reviews for fewer than 30 ratings or no data', function (): void {
    $few = (string) json_encode(['data' => ['entities' => [['totalRatingCount' => 29, 'totalCommentCount' => 1, 'overallRating' => ['value' => 4.5]]]]]);

    expect(physician_loop_mb([5], ['physician_npi' => '123'], [], ['rating' => $few]))->toContain('js-modal')
        ->and(physician_loop_mb([5], ['physician_npi' => '123']))->toContain('js-modal');
});

test('the Meta Box provider loop prints the short bio, a trimmed clinical bio and the video link', function (): void {
    expect(physician_loop_mb([5], ['physician_short_clinical_bio' => 'Short bio.']))->toContain('<p>Short bio.</p>')
        ->and(physician_loop_mb([5], ['physician_clinical_bio' => 'Long bio.']))->toContain('<p>TRIMMED(Long bio.) &hellip;</p>');

    $video = physician_loop_mb([5], ['physician_youtube_link' => 'https://video.example.test/v']);

    expect($video)->toContain('href="https://video.example.test/v&rel=0" data-lity>View Video</a>')
        ->and(physician_loop_mb([5]))->not->toContain('View Video');
});

test('the Meta Box provider loop lists the specialties and the clinics of the provider', function (): void {
    $output = physician_loop_mb([5], ['medical_specialties' => [3]], [[41, 42]]);

    expect($output)->toContain('<h3>Specialties</h3>')
        ->and($output)->toContain('<a href="https://example.test/specialty/3/">')
        ->and($output)->toContain('Clinic(s)</h3>')
        ->and(substr_count($output, '<li> <a href="https://example.test/p/0/"> Location 0 </a> </li>'))->toBe(2)
        ->and(WP_Query::$queries[0])->toBe(['relationship' => ['id' => 'physicians_to_locations', 'from' => 5], 'nopaging' => true]);
});

test('the Meta Box provider loop leaves out the specialties without any', function (): void {
    expect(physician_loop_mb([5]))->not->toContain('Specialties');
});

test('the provider loop stops looking for a published location at the first one', function (): void {
    $reads = [];
    $output = physician_loop([5], ['physician_locations' => [11, 12, 13]]);
    physician_loops_world(['physician_first_name' => 'Jo', 'physician_last_name' => 'Smith', 'physician_locations' => [11, 12, 13]], ['rating_object' => true]);
    when('get_post_status')->alias(static function (mixed $id) use (&$reads): string {
        $reads[] = $id;

        return 'publish';
    });
    uamswp_test_main_loop([5]);

    $second = uamswp_test_include_partial('fwp/physician-loop', ['post' => (object) ['ID' => 99]]);

    // One read to find the first published location, then one read for each location while it prints them.
    expect(array_count_values($reads))->toBe([11 => 2, 12 => 1, 13 => 1])
        ->and(trim((string) preg_replace('/\s+/', ' ', $second)))->toBe($output);
});
