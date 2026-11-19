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
 *                                         `excerpt`, `content`, `prefixes` (provider id => prefix), `page` and `partial` (the partial to include),
 *                                         `status_log` (an ArrayObject that collects the id of every status read), and
 *                                         `id` (the resource, 7 by default).
 */
function resource_card(array $fields = [], array $options = []): string
{
    $fields += ['clinical_resource_type' => ['value' => 'text', 'label' => 'Article']];
    $titles = $options['titles'] ?? [];
    $statuses = $options['statuses'] ?? [];
    $alts = $options['alts'] ?? [];
    $prefixes = $options['prefixes'] ?? [];
    $status_log = $options['status_log'] ?? new ArrayObject;

    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name.':'.$id] ?? $fields[$name] ?? null);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Title '.$id);
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/p/'.$id.'/');
    when('get_post_status')->alias(static function (mixed $id) use ($statuses, $status_log): string {
        $status_log[] = $id;

        return $statuses[$id] ?? 'publish';
    });
    when('get_the_excerpt')->justReturn($options['excerpt'] ?? 'An excerpt.');
    when('get_the_content')->alias(static function (mixed ...$arguments) use ($options): string {
        $GLOBALS['resource_card_content_arguments'] = $arguments;

        return $options['content'] ?? '';
    });
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('wp_trim_words')->alias(static fn (string $text, int $words, string $more): string => 'TRIMMED'.($words === 23 ? '' : ' to '.$words.' words').$more);
    when('esc_attr')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text));
    when('get_post_thumbnail_id')->justReturn($options['thumbnail'] ?? 0);
    // An attachment's alt text is one value read by an integer ID; any other read gets something that shows.
    when('get_post_meta')->alias(static fn (mixed $id, string $key = '', bool $single = false): mixed => match (true) {
        ! is_int($id) => 'not an integer ID',
        $key !== '_wp_attachment_image_alt' => 'another key',
        ! $single => [$alts[$id] ?? ''],
        default => $alts[$id] ?? '',
    });
    when('has_post_thumbnail')->justReturn(($options['thumbnail'] ?? 0) > 0);
    when('the_post_thumbnail')->alias(static function (string $size, mixed $attributes = null): void {
        echo '[thumbnail '.$size.' '.json_encode($attributes).']';
    });
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $image, int $width, int $height, string $x, string $y, string $class): string => 'sized-'.$image.'-'.$width.'x'.$height.'-'.$class);
    when('uamswp_provider_names')->alias(static fn (mixed $id): array => ['prefix' => $prefixes[$id] ?? '']);

    return uamswp_test_include_partial($options['partial'] ?? 'loops/resource-card', ['id' => $options['id'] ?? 7] + (isset($options['page']) ? ['resource_page' => $options['page']] : []));
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

test('the resource loop prints an archive card for each post of the main loop, or a message', function (): void {
    uamswp_test_main_loop([7, 8]);

    $output = resource_card([], ['partial' => 'fwp/clinical-resource-loop']);

    expect(substr_count($output, '<div class="col item-container">'))->toBe(2)
        ->and($output)->toContain('<span class="name">Title 7</span>')
        ->and($output)->toContain('<span class="name">Title 8</span>')
        ->and($output)->not->toContain('Sorry');

    when('__')->returnArg();
    when('_e')->alias(static function (string $text): void {
        echo $text;
    });
    uamswp_test_main_loop([]);

    expect(trim(resource_card([], ['partial' => 'fwp/clinical-resource-loop'])))->toBe('<p>Sorry, no resources matched your criteria.</p>');
});

test('the card stops looking for a published related post at the first one', function (string $field, array $ids): void {
    $log = new ArrayObject;

    resource_card([$field => $ids], ['status_log' => $log]);

    // One read to find the first published post, then one read for each post to count them.
    expect(array_count_values($log->getArrayCopy()))->toBe([$ids[0] => 2, $ids[1] => 1, $ids[2] => 1]);
})->with([
    'providers' => ['clinical_resource_providers', [21, 22, 23]],
    'locations' => ['clinical_resource_locations', [31, 32, 33]],
    'conditions' => ['clinical_resource_conditions', [41, 42, 43]],
    'treatments' => ['clinical_resource_treatments', [51, 52, 53]],
    'areas of expertise' => ['clinical_resource_aoe', [61, 62, 63]],
]);

test('the card reads past the unpublished related posts to find a published one', function (): void {
    $log = new ArrayObject;

    resource_card(['clinical_resource_conditions' => [41, 42, 43]], ['status_log' => $log, 'statuses' => [41 => 'draft', 42 => 'draft']]);

    expect(array_count_values($log->getArrayCopy()))->toBe([41 => 2, 42 => 2, 43 => 2]);
});

test('the card reads the content of its own resource when there is no excerpt', function (): void {
    $GLOBALS['resource_card_content_arguments'] = null;

    // The post is the third argument of get_the_content(); the card once passed it as the first, the "more" link text.
    expect(resource_card([], ['excerpt' => '', 'content' => '<b>From content</b>']))->toContain('From content')
        ->and($GLOBALS['resource_card_content_arguments'])->toBe([null, false, 7]);
});

test('the card prints no type label for a resource without a type', function (): void {
    expect(resource_card(['clinical_resource_type' => null]))->toContain('<span class="sr-only">(</span><span class="sr-only">)</span>');
});

/**
 * The related field of each kind, and how the archive card names one of its posts.
 *
 * @return array<string, array{0: string, 1: string, 2: string}>
 */
function resource_card_kinds(): array
{
    return [
        'providers' => ['clinical_resource_providers', 'Provider', 'Providers'],
        'locations' => ['clinical_resource_locations', 'Location', 'Locations'],
        'conditions' => ['clinical_resource_conditions', 'Condition', 'Conditions'],
        'treatments' => ['clinical_resource_treatments', 'Treatment/Procedure', 'Treatments/Procedures'],
        'areas of expertise' => ['clinical_resource_aoe', 'Area of Expertise', 'Areas of Expertise'],
    ];
}

/**
 * Render the archive card with related posts of one kind, each named "Title <suffix>".
 *
 * A provider is named from its name fields (first name "Title", last name the suffix); any other post by its title.
 *
 * @param  array<int, int>  $ids
 * @param  array<int, string>  $suffixes  Post ID to the suffix of its name; the ID by default.
 * @param  array<int, string>  $statuses
 */
function resource_card_related(string $field, array $ids, array $suffixes = [], array $statuses = []): string
{
    $fields = [$field => $ids];
    $titles = [];

    foreach ($ids as $id) {
        $suffix = $suffixes[$id] ?? (string) $id;
        $titles[$id] = 'Title '.$suffix;
        $fields['physician_first_name:'.$id] = 'Title';
        $fields['physician_last_name:'.$id] = $suffix;
    }

    return resource_card($fields, ['page' => 'archive', 'titles' => $titles, 'statuses' => $statuses]);
}

/**
 * The text of the first related list in an archive card, with its whitespace collapsed.
 */
function resource_card_dd_text(string $output): string
{
    $start = (int) strpos($output, '<dd>');

    return trim((string) preg_replace('/\s+/', ' ', strip_tags(substr($output, $start, (int) strpos($output, '</dd>') - $start))));
}

dataset('resource card kinds', resource_card_kinds());

test('each kind of related post is joined with commas, and and, and more', function (string $field): void {
    $cases = [
        [[11], [], 'Title 11'],
        [[11, 12], [], 'Title 11 and Title 12'],
        [[11, 12, 13], [], 'Title 11, Title 12 and Title 13'],
        [[11, 12, 13, 14], [], 'Title 11, Title 12, Title 13 and more'],
        [[11, 12, 13, 14, 15, 16], [], 'Title 11, Title 12, Title 13 and more'],
        // Unpublished posts are left out of the list and of the count.
        [[11, 12, 13, 14, 15], [12 => 'draft'], 'Title 11, Title 13, Title 14 and more'],
        [[11, 12, 13, 14], [14 => 'draft'], 'Title 11, Title 12 and Title 13'],
        [[11, 12, 13], [11 => 'draft'], 'Title 12 and Title 13'],
        [[11, 12], [12 => 'private'], 'Title 11'],
    ];

    foreach ($cases as [$ids, $statuses, $expected]) {
        expect(resource_card_dd_text(resource_card_related($field, $ids, [], $statuses)))->toBe($expected);
    }
})->with('resource card kinds');

test('each kind of related post is linked with its name, and the resource it belongs to', function (string $field): void {
    $output = resource_card_related($field, [11, 12]);

    expect($output)->toContain('<a href="https://example.test/p/11/" data-categorytitle="Related Location" data-typetitle="Title 11" data-itemtitle="Title 7">Title 11</a> and <a href="https://example.test/p/12/" data-categorytitle="Related Location" data-typetitle="Title 12" data-itemtitle="Title 7">Title 12</a>');
})->with('resource card kinds');

test('each kind of related post is labeled by how many are published', function (string $field, string $singular, string $plural): void {
    expect(resource_card_related($field, [11]))->toContain('<dt>'.$singular.'</dt>')
        ->and(resource_card_related($field, [11, 12]))->toContain('<dt>'.$plural.'</dt>')
        ->and(resource_card_related($field, [11, 12], [], [12 => 'draft']))->toContain('<dt>'.$singular.'</dt>');
})->with('resource card kinds');

test('a kind with no published post prints no heading', function (string $field): void {
    $output = resource_card_related($field, [11, 12], [], [11 => 'draft', 12 => 'trash']);

    expect($output)->not->toContain('<dt>')
        ->and($output)->not->toContain('Title 11');
})->with('resource card kinds');

/**
 * A name with each thing the attribute cleaning changes: quotes, a typographic apostrophe, the text of a
 * non-breaking space, a literal non-breaking space, an encoded ampersand and an accented letter.
 */
function resource_card_tricky_name(): string
{
    return 'Mom&#8217;s "café" A&nbsp;B C&amp;D'."\u{00A0}".'E';
}

test('the name of each kind of related post is cleaned for its attribute and printed as it is', function (string $field): void {
    $output = resource_card_related($field, [11], [11 => resource_card_tricky_name()]);

    expect($output)->toContain('data-typetitle="Title Mom\'s \'café\' A B C&amp;D E" data-itemtitle="Title 7">Title '.resource_card_tricky_name().'</a>');
})->with('resource card kinds');

test('the resource title is cleaned for the labels and attributes, and printed escaped', function (): void {
    $output = resource_card([], ['titles' => [7 => resource_card_tricky_name()]]);
    $clean = htmlspecialchars("Mom's 'café' A B C&amp;D E");

    expect($output)->toContain('aria-label="Read the Article, '.$clean.'" data-itemtitle="'.$clean.'">Read the Article</a>')
        ->and($output)->toContain('<h3 class="card-title h5">'.htmlspecialchars(resource_card_tricky_name()).' <span class="subtitle">');
});

test('the button text of a Q&A is decoded again before it goes into the label', function (): void {
    expect(resource_card(['clinical_resource_type' => ['value' => 'provider_spotlight', 'label' => 'Q&A']]))
        ->toContain('aria-label="Read the Q&amp;A, Title 7"');
});

test('a resource ID that is not an integer is read as one', function (): void {
    $output = resource_card([], ['id' => false]);

    expect($output)->toContain('<a href="https://example.test/p/0/" class="btn btn-primary stretched-link"');
});

test('an excerpt over 160 characters is trimmed to 23 words', function (): void {
    expect(resource_card([], ['excerpt' => str_repeat('a', 161)]))->toContain('<p class="card-text">TRIMMED &hellip;</p>');
});

test('the single card prints each source at its size', function (): void {
    $output = resource_card(['clinical_resource_image_square' => 44], ['thumbnail' => 31]);
    preg_match_all('/(?:srcset|src)="(sized-[^"]+)"/', $output, $sources);

    expect($sources[1])->toBe([
        'sized-31-455x256-aspect-16-9-small',
        'sized-31-433x244-aspect-16-9-small',
        'sized-31-455x256-aspect-16-9-small',
        'sized-31-433x244-aspect-16-9-small',
        'sized-31-455x256-aspect-16-9-small',
        'sized-31-455x256-aspect-16-9-small',
    ]);
});

test('the archive card prints each source at its size, square or wide', function (): void {
    $output = resource_card(['clinical_resource_image_square' => 44], ['thumbnail' => 31, 'page' => 'archive']);
    preg_match_all('/(?:srcset|src)="(sized-[^"]+)"/', $output, $sources);

    expect($sources[1])->toBe([
        'sized-44-243x243-aspect-1-1',
        'sized-44-184x184-thumbnail',
        'sized-44-243x243-aspect-1-1',
        'sized-44-184x184-aspect-1-1',
        'sized-31-580x326-aspect-16-9-small',
        'sized-44-95x95-thumbnail',
        'sized-31-510x286-aspect-16-9-small',
        'sized-31-510x286-aspect-16-9-small',
    ]);
});

test('an empty square image falls back to the featured image', function (): void {
    $output = resource_card(['clinical_resource_image_square' => ''], ['thumbnail' => 31, 'page' => 'archive']);

    expect($output)->toContain('srcset="sized-31-243x243-aspect-1-1"');
});

test('the featured image keeps its own alt text when the square image has another', function (): void {
    $output = resource_card(['clinical_resource_image_square' => 44], ['thumbnail' => 31, 'alts' => [31 => 'Wide alt', 44 => 'Square alt']]);

    expect($output)->toContain('alt="Wide alt"')
        ->and($output)->not->toContain('Square alt');
});

test('the alt text of a square image stored as text is read by its integer ID', function (): void {
    $output = resource_card(['clinical_resource_image_square' => '44'], ['thumbnail' => 31, 'alts' => [44 => 'Square alt']]);

    expect($output)->toContain('alt="Square alt"');
});
