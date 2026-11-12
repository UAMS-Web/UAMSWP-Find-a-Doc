<?php

/**
 * The clinical resource card (templates/loops/resource-card.php): the single layout used in the card lists and
 * the archive layout with the related content of the resource.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

/**
 * Render the resource card for resource 7.
 *
 * @param  array<string, mixed>  $fields  The ACF fields by name; `name:id` keys win for one post.
 * @param  array<string, mixed>  $options  `titles` (id => title), `statuses` (id => status), `thumbnail` (the
 *                                         featured image id, 0 for none), `alts` (image id => alt text),
 *                                         `excerpt`, `content`, `prefixes` (provider id => prefix) and `page`.
 */
function resource_card(array $fields = [], array $options = []): string
{
    $fields += ['clinical_resource_type' => ['value' => 'text', 'label' => 'Article']];
    $titles = $options['titles'] ?? [];
    $statuses = $options['statuses'] ?? [];
    $alts = $options['alts'] ?? [];
    $prefixes = $options['prefixes'] ?? [];

    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name.':'.$id] ?? $fields[$name] ?? null);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Title '.$id);
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('get_post_status')->alias(static fn (mixed $id): string => $statuses[$id] ?? 'publish');
    when('get_the_excerpt')->justReturn($options['excerpt'] ?? 'An excerpt.');
    when('get_the_content')->justReturn($options['content'] ?? '');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => 'TRIMMED'.$more);
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('get_post_thumbnail_id')->justReturn($options['thumbnail'] ?? 0);
    when('get_post_meta')->alias(static fn (mixed $id): string => $alts[$id] ?? '');
    when('has_post_thumbnail')->justReturn(($options['thumbnail'] ?? 0) > 0);
    when('the_post_thumbnail')->alias(static function (string $size): void {
        echo '[thumbnail '.$size.']';
    });
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $image, int $width, int $height, string $x, string $y, string $class): string => 'sized-'.$image.'-'.$width.'x'.$height.'-'.$class);
    when('uamswp_provider_names')->alias(static fn (mixed $id): array => ['prefix' => $prefixes[$id] ?? '']);

    return uamswp_test_include_partial('loops/resource-card', ['id' => 7] + (isset($options['page']) ? ['resource_page' => $options['page']] : []));
}

test('the single card uses the short title and prints the type beside it', function (): void {
    $output = resource_card(['clinical_resource_title_short' => 'Short', 'clinical_resource_type' => ['value' => 'text', 'label' => 'Article']], ['titles' => [7 => 'Full title']]);

    expect($output)->toContain('<h3 class="card-title h5">Short <span class="subtitle"><span class="sr-only">(</span>Article<span class="sr-only">)</span></span></h3>')
        ->and($output)->toContain('<p class="card-text">An excerpt.</p>')
        ->and($output)->toContain('<a href="https://example.test/p/7/" class="btn btn-primary stretched-link" aria-label="Read the Article, Short" data-itemtitle="Short">Read the Article</a>');
});

test('the card falls back to the full title and cleans it for the label', function (): void {
    $output = resource_card([], ['titles' => [7 => 'Mom&#8217;s "best" guide']]);

    expect($output)->toContain('aria-label="Read the Article, Mom&#039;s &#039;best&#039; guide"')
        ->and($output)->toContain('>Mom&amp;#8217;s &quot;best&quot; guide <span class="subtitle">');
});

test('the card words its button for the resource type', function (string $type, string $button): void {
    $output = resource_card(['clinical_resource_type' => ['value' => $type, 'label' => 'Label']]);

    expect($output)->toContain('>'.$button.'</a>');
})->with([
    'text' => ['text', 'Read the Article'],
    'infographic' => ['infographic', 'View the Infographic'],
    'video' => ['video', 'Watch the Video'],
    'document' => ['doc', 'Read the Document'],
    'spotlight' => ['provider_spotlight', 'Read the Q&A'],
    'unknown' => ['other', 'View the Clinical Resource'],
]);

test('the card takes the excerpt from the content when there is none, and trims one over 160 characters', function (): void {
    expect(resource_card([], ['excerpt' => '', 'content' => '<b>From content</b>']))->toContain('<p class="card-text">From content</p>')
        ->and(resource_card([], ['excerpt' => str_repeat('a', 160)]))->toContain('<p class="card-text">'.str_repeat('a', 160).'</p>')
        ->and(resource_card([], ['excerpt' => str_repeat('a', 161)]))->toContain('<p class="card-text">TRIMMED &hellip;</p>');
});

test('the single card prints the sized wide image with the alt text of the featured image', function (): void {
    $output = resource_card([], ['thumbnail' => 31, 'alts' => [31 => 'A wide alt']]);

    expect($output)->toContain('<source srcset="sized-31-455x256-aspect-16-9-small"'."\n".'                                media="(min-width: 1921px)">')
        ->and($output)->toContain('media="(min-width: 1500px)"')
        ->and($output)->toContain('<img src="sized-31-455x256-aspect-16-9-small" alt="A wide alt" role="presentation" />');
});

test('the single card takes the alt text from the square image when the featured image has none', function (): void {
    $output = resource_card(['clinical_resource_image_square' => 44], ['thumbnail' => 31, 'alts' => [44 => 'A square alt']]);

    expect($output)->toContain('alt="A square alt"');
});

test('the single card prints the placeholder without a featured image', function (): void {
    $output = resource_card();

    expect($output)->toContain('no-image_16-9.svg')
        ->and($output)->toContain('<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" />')
        ->and($output)->not->toContain('sized-');
});

test('the archive card prints the sized images and the type, title and button', function (): void {
    $output = resource_card(['clinical_resource_image_square' => 44], ['thumbnail' => 31, 'page' => 'archive']);

    expect($output)->toContain('<div class="col item-container">')
        ->and($output)->toContain('<source srcset="sized-44-243x243-aspect-1-1"'."\n".'                                        media="(min-width: 2054px)">')
        ->and($output)->toContain('<source srcset="sized-44-184x184-thumbnail"')
        ->and($output)->toContain('<source srcset="sized-31-580x326-aspect-16-9-small"')
        ->and($output)->toContain('<img src="sized-31-510x286-aspect-16-9-small" alt="" role="presentation" />')
        ->and($output)->toContain('<span class="name">Title 7</span>')
        ->and($output)->toContain('<span class="subtitle"><span class="sr-only"> (</span>Article<span class="sr-only">)</span></span>')
        ->and($output)->toContain('<p>An excerpt.</p>')
        ->and(substr_count($output, 'class="btn btn-primary"'))->toBe(2);
});

test('the archive card prints the placeholder without a featured image', function (): void {
    expect(resource_card([], ['page' => 'archive']))->toContain('no-image_16-9.svg');
});

test('the archive card prints no related content without any', function (): void {
    $output = resource_card([], ['page' => 'archive']);

    expect($output)->toContain('<h4 class="h5">Related Content</h4>')
        ->and($output)->not->toContain('<dt>');
});

test('the archive card lists the related posts of each kind under a label', function (string $field, string $singular, string $plural): void {
    $one = resource_card([$field => [11]], ['page' => 'archive']);

    expect($one)->toContain('<dt>'.$singular.'</dt>')
        ->and($one)->toContain('<a href="https://example.test/p/11/" data-categorytitle="Related Location" data-typetitle="Title 11" data-itemtitle="Title 7">Title 11</a>');

    $two = resource_card([$field => [11, 12]], ['page' => 'archive']);

    expect($two)->toContain('<dt>'.$plural.'</dt>');
})->with([
    'conditions' => ['clinical_resource_conditions', 'Condition', 'Conditions'],
    'treatments' => ['clinical_resource_treatments', 'Treatment/Procedure', 'Treatments/Procedures'],
    'areas of expertise' => ['clinical_resource_aoe', 'Area of Expertise', 'Areas of Expertise'],
    'locations' => ['clinical_resource_locations', 'Location', 'Locations'],
]);

test('the archive card joins the related posts with commas, and and, and more', function (array $ids, string $expected): void {
    $output = resource_card(['clinical_resource_conditions' => $ids], ['page' => 'archive']);
    $text = trim(strip_tags(substr($output, (int) strpos($output, '<dd>'), (int) strpos($output, '</dd>') - (int) strpos($output, '<dd>'))));

    expect(preg_replace('/\s+/', ' ', $text))->toBe($expected);
})->with([
    'one' => [[11], 'Title 11'],
    'two' => [[11, 12], 'Title 11 and Title 12'],
    'three' => [[11, 12, 13], 'Title 11, Title 12 and Title 13'],
    'four' => [[11, 12, 13, 14], 'Title 11, Title 12, Title 13 and more'],
    'five' => [[11, 12, 13, 14, 15], 'Title 11, Title 12, Title 13 and more'],
]);

test('the archive card leaves out the related posts that are not published', function (): void {
    $output = resource_card(['clinical_resource_conditions' => [11, 12, 13]], ['page' => 'archive', 'statuses' => [12 => 'draft']]);

    expect($output)->toContain('Title 11')
        ->and($output)->not->toContain('Title 12')
        ->and($output)->toContain('Title 11</a> and <a href="https://example.test/p/13/"')
        ->and($output)->toContain('<dt>Conditions</dt>');

    $none = resource_card(['clinical_resource_conditions' => [12]], ['page' => 'archive', 'statuses' => [12 => 'draft']]);

    expect($none)->not->toContain('<dt>');
});

test('the archive card names the related providers with their prefix and name parts', function (): void {
    $fields = [
        'clinical_resource_providers' => [21, 22],
        'physician_first_name:21' => 'Jo',
        'physician_middle_name:21' => 'Q',
        'physician_last_name:21' => 'Smith',
        'physician_pedigree:21' => 'Jr.',
        'physician_first_name:22' => 'Al',
        'physician_last_name:22' => 'Jones',
    ];

    $output = resource_card($fields, ['page' => 'archive', 'prefixes' => [21 => 'Dr.']]);

    expect($output)->toContain('<dt>Providers</dt>')
        ->and($output)->toContain('data-typetitle="Dr. Jo Q Smith Jr." data-itemtitle="Title 7">Dr. Jo Q Smith Jr.</a>')
        ->and($output)->toContain('>Al Jones</a>');
});

test('the archive card says Provider for one published provider among several', function (): void {
    $fields = ['clinical_resource_providers' => [21, 22], 'physician_first_name:21' => 'Jo', 'physician_last_name:21' => 'Smith'];

    $output = resource_card($fields, ['page' => 'archive', 'statuses' => [22 => 'draft']]);

    expect($output)->toContain('<dt>Provider</dt>');
});

test('a page value other than archive gives the single card', function (): void {
    expect(resource_card([], ['page' => 'single']))->toContain('<div class="card">')
        ->and(resource_card([], ['page' => '']))->toContain('<div class="card">');
});
