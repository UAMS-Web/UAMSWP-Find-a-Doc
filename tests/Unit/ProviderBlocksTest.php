<?php

/**
 * The provider block partials: the provider card (templates/loops/physician-card.php), the recognition list
 * block and the providers block (templates/blocks).
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-blocks.php';

/**
 * Stub the functions the provider partials read.
 *
 * @param  array<string, mixed>  $fields  The ACF fields by name; `name:id` keys win for one post.
 * @param  array<string, string>  $terms  Term names by `taxonomy:id`; a term that is not listed is not found.
 * @param  array<string, mixed>  $options  `thumbnail` (whether the post has a featured image).
 */
function provider_blocks_world(array $fields = [], array $terms = [], array $options = []): void
{
    uamswp_test_acf_blocks_file();

    when('get_field')->alias(static function (string $name, mixed $id = null) use ($fields): mixed {
        if ($id instanceof stdClass) {
            return $fields[$name.':term:'.$id->term_id] ?? null;
        }

        return $fields[$name.':'.$id] ?? $fields[$name] ?? null;
    });
    when('get_term')->alias(static fn (mixed $id, string $taxonomy): object|false => isset($terms[$taxonomy.':'.$id]) ? (object) ['name' => $terms[$taxonomy.':'.$id], 'term_id' => $id] : false);
    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('get_permalink')->alias(static fn (mixed $id = null): string => 'https://example.test/provider/'.($id ?? WP_Query::$current).'/');
    when('the_permalink')->alias(static function (mixed $id = null): void {
        echo 'https://example.test/provider/'.($id ?? WP_Query::$current).'/';
    });
    when('has_post_thumbnail')->justReturn($options['thumbnail'] ?? false);
    when('get_post_thumbnail_id')->justReturn(31);
    when('bis_get_attachment_image')->justReturn('');
    when('image_sizer')->alias(static fn (mixed $image, int $width, int $height, string $x, string $y, string $class): string => 'sized-'.$image.'-'.$width.'x'.$height.'-'.$class);
    when('__')->returnArg();
    when('esc_html__')->returnArg();
    when('esc_url')->returnArg();
    when('esc_attr')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('esc_html')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('sanitize_title_with_dashes')->alias(static fn (string $text): string => strtolower(str_replace(' ', '-', $text)));
    when('wp_reset_postdata')->justReturn();
    when('uamswp_custom_table_query')->alias(static fn (string $table, string $field, mixed $values): array => $fields['__table:'.$field] ?? []);

    WP_Query::$queue = [];
    WP_Query::$queries = [];
    WP_Query::$current = 0;
}

/**
 * Render the provider card for provider 12.
 *
 * @param  array<string, mixed>  $fields  Overrides of the provider's fields (without the `:12` suffix).
 * @param  array<string, string>  $terms
 */
function provider_card(array $fields = [], array $terms = [], bool $thumbnail = false): string
{
    $named = [];

    foreach ($fields + ['physician_first_name' => 'Jo', 'physician_last_name' => 'Smith'] as $name => $value) {
        // A key that names a term keeps its own suffix.
        $named[str_contains($name, ':term:') ? $name : $name.':12'] = $value;
    }

    provider_blocks_world($named, $terms, ['thumbnail' => $thumbnail]);

    return uamswp_test_include_partial('loops/physician-card', ['id' => 12]);
}

test('the provider card names the provider with the middle name, pedigree and degrees', function (): void {
    $output = provider_card(
        ['physician_middle_name' => 'Q', 'physician_pedigree' => 'Jr.', 'physician_degree' => [1, 2]],
        ['degree:1' => 'M.D.', 'degree:2' => 'Ph.D.'],
    );

    expect($output)->toContain('<span class="name">Jo Q Smith&nbsp;Jr., M.D., Ph.D.</span>')
        ->and($output)->toContain('aria-label="View profile for Jo Q Smith Jr., M.D., Ph.D." data-itemtitle="Jo Q Smith Jr., M.D., Ph.D."')
        ->and($output)->toContain('<a href="https://example.test/provider/12/" class="btn btn-primary stretched-link"');
});

test('the provider card skips a degree that cannot be found, and keeps the separator of the one before it', function (): void {
    $middle = provider_card(['physician_degree' => [1, 2, 3]], ['degree:1' => 'M.D.', 'degree:3' => 'Ph.D.']);

    expect($middle)->toContain('<span class="name">Jo Smith, M.D., Ph.D.</span>');

    // The separator follows every degree but the last in the list, found or not, so a missing last one leaves it.
    $last = provider_card(['physician_degree' => [1, 2]], ['degree:1' => 'M.D.']);

    expect($last)->toContain('<span class="name">Jo Smith, M.D., </span>');
});

test('the provider card leaves out the degrees when there are none', function (): void {
    expect(provider_card())->toContain('<span class="name">Jo Smith</span>');
});

test('the provider card cleans quotes and the right single quote entity in the name for the label', function (): void {
    $output = provider_card(['physician_first_name' => 'Jo "J"', 'physician_last_name' => 'O&#8217;Neil']);

    expect($output)->toContain('aria-label="View profile for Jo \'J\' O\'Neil"');
});

test('the provider card prints the occupation title of a resident, or of the clinical title', function (): void {
    expect(provider_card(['physician_resident' => true, 'physician_title' => 9], ['clinical_title:9' => 'Cardiologist']))
        ->toContain('<span class="subtitle">Resident Physician</span>');

    expect(provider_card(['physician_title' => 9], ['clinical_title:9' => 'Cardiologist']))
        ->toContain('<span class="subtitle">Cardiologist</span>');
});

test('the provider card prefers the specialization title of the clinical title term', function (): void {
    $output = provider_card(['physician_title' => 9, 'clinical_specialization_title:term:9' => 'Heart Surgeon'], ['clinical_title:9' => 'Cardiologist']);

    expect($output)->toContain('<span class="subtitle">Heart Surgeon</span>');
});

test('the provider card prints no occupation title without a resident flag or a clinical title it can find', function (): void {
    expect(provider_card())->not->toContain('subtitle')
        ->and(provider_card(['physician_title' => 9]))->not->toContain('subtitle');
});

test('the provider card prints the sized portrait with a featured image, or the placeholder', function (): void {
    $with = provider_card([], [], true);

    expect($with)->toContain('<source srcset="sized-31-253x337-portrait-3-4"')
        ->and($with)->toContain('<img src="sized-31-253x337-portrait-3-4" itemprop="image" class="card-img-top" alt="Jo Smith" loading="lazy" />')
        ->and($with)->not->toContain('no-image_3-4');

    $without = provider_card();

    expect($without)->toContain('no-image_3-4.svg')
        ->and($without)->toContain('<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />');
});

/**
 * Render the recognition list block.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, string>  $terms
 * @param  list<int>  $found  The provider ids the query finds.
 * @param  array<string, mixed>  $variables  The variables the block wrapper sets.
 */
function recognition_block(array $fields, array $terms, array $found, array $variables = []): string
{
    provider_blocks_world($fields, $terms);
    WP_Query::$queue = [$found];

    return uamswp_test_include_partial('blocks/recognitions', $variables);
}

test('the recognition list prints a row of name and title for each provider found', function (): void {
    $fields = [
        'field_select_recognition_list' => [5],
        'physician_first_name:21' => 'Jo', 'physician_last_name:21' => 'Smith', 'physician_degree:21' => [1, 2], 'physician_pedigree:21' => 'Jr.', 'physician_middle_name:21' => 'Q',
        'physician_title:21' => 9, 'clinical_specialization_title:term:9' => 'Heart Surgeon',
        'physician_first_name:22' => 'Al', 'physician_last_name:22' => 'Jones', 'physician_resident:22' => true,
        'physician_first_name:23' => 'Bo', 'physician_last_name:23' => 'Lee', 'physician_title:23' => 8,
    ];
    $terms = ['degree:1' => 'M.D.', 'degree:2' => 'Ph.D.', 'clinical_title:9' => 'Cardiologist', 'clinical_title:8' => 'Surgeon'];

    $output = recognition_block($fields, $terms, [21, 22, 23]);

    expect($output)->toContain('<th scope="col" class="col-6">Name</th>')
        ->and($output)->toContain('<tr><td><a href="https://example.test/provider/21/" title="Jo Q Smith&nbsp;Jr., M.D., Ph.D.">Jo Q Smith&nbsp;Jr., M.D., Ph.D.</a></td><td>Heart Surgeon</td></tr>')
        ->and($output)->toContain('<tr><td><a href="https://example.test/provider/22/" title="Al Jones">Al Jones</a></td><td>Resident Physician</td></tr>')
        ->and($output)->toContain('<td>Surgeon</td></tr>')
        ->and(WP_Query::$queries[0]['tax_query'])->toBe([['taxonomy' => 'recognition', 'field' => 'term_id', 'terms' => [5]]]);
});

test('the recognition list leaves the title cell empty without an occupation title', function (): void {
    $output = recognition_block(['physician_first_name:21' => 'Jo', 'physician_last_name:21' => 'Smith'], [], [21]);

    expect($output)->toContain('Jo Smith</a></td><td>&nbsp;</td></tr>');
});

test('the recognition list takes the list from the variable the block sets, before the field', function (): void {
    recognition_block(['field_select_recognition_list' => [5]], [], [], ['recognition_id' => [7]]);

    expect(WP_Query::$queries[0]['tax_query'][0]['terms'])->toBe([7]);
});

test('the recognition list prints nothing without providers', function (): void {
    expect(recognition_block([], [], []))->toBe('');
});

test('the recognition list prints only the placeholder for a new block in the editor', function (): void {
    $output = recognition_block([], [], [21], ['block' => ['id' => 'b1', 'title' => 'Recognitions', 'data' => ['field_x' => '']], 'is_preview' => true]);

    expect($output)->toContain('uams-block-placeholder')
        ->and($output)->not->toContain('<table');
});

test('the recognition list reads no module or index variable for its anchor', function (): void {
    recognition_block([], [], [], ['module' => ['anchor_id' => 'My Anchor'], 'i' => 3]);

    expect(WP_Query::$queries)->toHaveCount(1);
});

/**
 * Render the providers block.
 *
 * @param  array<string, mixed>  $fields  The block's ACF fields by name; `__table:<field>` keys give the ids the
 *                                        custom table query returns for a field.
 * @param  list<list<int>>  $queries  The provider ids each query finds, in the order the template builds them.
 * @param  array<string, mixed>  $variables  The variables the block wrapper sets.
 */
function providers_block(array $fields, array $queries, array $variables = []): string
{
    provider_blocks_world($fields + [
        'physician_first_name:41' => 'Jo', 'physician_last_name:41' => 'Smith',
        'physician_first_name:42' => 'Al', 'physician_last_name:42' => 'Jones',
        'physician_first_name:43' => 'Bo', 'physician_last_name:43' => 'Lee',
    ]);
    WP_Query::$queue = $queries;

    return uamswp_test_include_partial('blocks/providers', $variables);
}

test('the providers block prints nothing without a filter', function (): void {
    expect(providers_block(['block_fad_providers_heading' => 'Our Providers'], []))->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
});

test('the providers block prints a card for each provider of the ids it was given', function (): void {
    $output = providers_block([
        'block_fad_providers_heading' => 'Our Providers',
        'block_fad_providers_description' => 'Meet them.',
        'block_fad_providers_count' => 3,
        'block_fad_providers_filter_ids' => [41, 42],
    ], [[41, 42]], ['block' => ['id' => 'abc']]);

    expect($output)->toContain('<section class="uams-module provider-list  alignfull bg-auto" id="providers-abc">')
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Our Providers</span></h2>')
        ->and($output)->toContain('<div class="module-description"><p>Meet them.</p></div>')
        ->and($output)->toContain('card-list card-list-doctors card-list-doctors-count-3')
        ->and(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('<span class="name">Jo Smith</span>')
        ->and($output)->not->toContain('class="more"')
        ->and(WP_Query::$queries)->toHaveCount(1)
        ->and(WP_Query::$queries[0]['post__in'])->toBe([41, 42])
        ->and(WP_Query::$queries[0])->toMatchArray(['post_type' => 'provider', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish']);
});

test('the providers block takes the section id from the block id and ignores the module and index variables', function (): void {
    $fields = ['block_fad_providers_filter_ids' => [41]];
    $withBlock = providers_block($fields, [[41]], ['block' => ['id' => 'block_abc']]);
    $withoutBlock = providers_block($fields, [[41]], ['module' => ['anchor_id' => 'My Anchor'], 'i' => 4]);

    expect($withBlock)->toContain('id="providers-block_abc"')
        ->and($withoutBlock)->toContain('id="providers-"')
        ->and($withoutBlock)->not->toContain('my-anchor')
        ->and($withoutBlock)->not->toContain('module-5');
});

test('the providers block adds the block classes to nothing it prints but keeps the background class', function (): void {
    $output = providers_block(['block_fad_providers_filter_ids' => [41], 'block_fad_providers_background_color' => 'bg-gray'], [[41]], ['block' => ['id' => 'abc', 'className' => 'extra', 'align' => 'full']]);

    expect($output)->toContain('provider-list  alignfull bg-gray"');
});

test('the providers block prints nothing when the query finds no provider', function (): void {
    expect(providers_block(['block_fad_providers_filter_ids' => [41]], [[]], ['block' => ['id' => 'abc']]))->toBe('');
});

test('the providers block takes the values from the variables the wrapper sets before the fields', function (): void {
    $output = providers_block(
        ['block_fad_providers_heading' => 'Field heading', 'block_fad_providers_filter_ids' => [41], 'block_fad_providers_count' => 9],
        [[41]],
        ['block' => ['id' => 'abc'], 'heading' => 'Set heading', 'content_block' => 'Set text', 'background_color' => 'bg-white', 'count' => 2],
    );

    expect($output)->toContain('<span class="title">Set heading</span>')
        ->and($output)->toContain('<p>Set text</p>')
        ->and($output)->toContain('provider-list  alignfull bg-white')
        ->and($output)->toContain('count-2');
});

test('the providers block filters by region, location and area of expertise, then adds the specific ids', function (): void {
    $output = providers_block([
        'block_fad_providers_filter_ids' => [41],
        'block_fad_providers_filter_region' => [7],
        'block_fad_providers_filter_location' => [3],
        'block_fad_providers_filter_aoe' => [4],
        '__table:physician_locations' => [41, 42, 43],
        '__table:physician_expertise' => [42, 43, 44],
    ], [[42, 43, 41], [41, 42, 43]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0])->toMatchArray(['fields' => 'ids', 'post_status' => 'publish', 'post_type' => 'provider', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1])
        ->and(array_values(WP_Query::$queries[0]['post__in']))->toBe([42, 43])
        ->and(WP_Query::$queries[0]['tax_query'])->toBe([['taxonomy' => 'region', 'terms' => [7]]])
        ->and(array_values(WP_Query::$queries[1]['post__in']))->toBe([41, 42, 43])
        ->and(substr_count($output, '<div class="card">'))->toBe(3);
});

test('the providers block combines the location and expertise ids when only one of them filters', function (): void {
    providers_block([
        'block_fad_providers_filter_location' => [3],
        '__table:physician_locations' => [41, 42],
    ], [[41, 42], [41, 42]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0]['post__in'])->toBe([41, 42])
        ->and(WP_Query::$queries[0]['tax_query'])->toBe([]);

    providers_block([
        'block_fad_providers_filter_aoe' => [4],
        '__table:physician_expertise' => [43],
    ], [[43], [43]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0]['post__in'])->toBe([43]);
});

test('the providers block builds no id list from an empty location or expertise filter', function (): void {
    providers_block(['block_fad_providers_filter_region' => [7]], [[41], [41]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0]['post__in'])->toBe([]);
});

test('the providers block ends with a link button when it has one', function (): void {
    $fields = [
        'block_fad_providers_filter_ids' => [41],
        'block_fad_providers_more' => true,
        'block_fad_providers_more_text' => 'Want more?',
        'block_fad_providers_more_button_text' => 'See all',
        'block_fad_providers_more_button_url' => ['url' => 'https://example.test/providers/', 'target' => '_blank'],
        'block_fad_providers_more_button_description' => 'See all "providers"',
        'block_fad_providers_background_color' => 'bg-gray',
    ];

    $output = providers_block($fields, [[41]], ['block' => ['id' => 'abc']]);

    expect($output)->toContain('<p class="lead">Want more?</p>')
        ->and($output)->toContain('<a href="https://example.test/providers/" class="btn btn-outline-primary" aria-label="See all &#039;providers&#039;" target="_blank" rel="noopener">See all</a>');
});

test('the providers block uses the white button on any background other than white or gray', function (string $background, string $color): void {
    $fields = [
        'block_fad_providers_filter_ids' => [41],
        'block_fad_providers_more' => true,
        'block_fad_providers_more_button_url' => 'https://example.test/providers/',
        'block_fad_providers_more_button_description' => 'See all',
        'block_fad_providers_background_color' => $background,
    ];

    expect(providers_block($fields, [[41]], ['block' => ['id' => 'abc']]))->toContain('btn btn-outline-'.$color.'"');
})->with([
    'white' => ['bg-white', 'primary'],
    'gray' => ['bg-gray', 'primary'],
    'other' => ['bg-primary', 'white'],
    'none' => ['', 'white'],
]);

test('the providers block takes the button values from the variables and the target from the link array', function (): void {
    $output = providers_block(
        ['block_fad_providers_filter_ids' => [41], 'block_fad_providers_more' => true],
        [[41]],
        [
            'block' => ['id' => 'abc'],
            'more_text' => 'Set more',
            'more_button_text' => 'Set button',
            'more_button_url' => ['url' => 'https://set.example.test/', 'target' => '_self'],
            'more_button_description' => 'Set description',
            'more_button_color' => 'secondary',
            'background_color' => 'bg-white',
        ],
    );

    expect($output)->toContain('<p class="lead">Set more</p>')
        ->and($output)->toContain('href="https://set.example.test/"')
        ->and($output)->toContain('aria-label="Set description" target="_self">Set button</a>')
        ->and($output)->toContain('btn btn-outline-white');
});

test('the providers block keeps a button target the wrapper set over the one in the link array', function (): void {
    $output = providers_block(
        ['block_fad_providers_filter_ids' => [41], 'block_fad_providers_more' => true],
        [[41]],
        ['block' => ['id' => 'abc'], 'more_button_target' => '_top', 'more_button_description' => 'See all', 'more_button_url' => ['url' => 'https://example.test/x/', 'target' => '_blank']],
    );

    expect($output)->toContain('target="_top">')->not->toContain('target="_blank"');
});

test('the providers block prints only the placeholder for a new block in the editor', function (): void {
    $output = providers_block(['block_fad_providers_filter_ids' => [41]], [[41]], ['block' => ['id' => 'abc', 'title' => 'Providers', 'data' => []], 'is_preview' => true]);

    expect($output)->toContain('uams-block-placeholder')
        ->and($output)->not->toContain('provider-list');
});

test('the provider card loop prints a card for each post of the main loop, and nothing without posts', function (): void {
    provider_blocks_world(['physician_first_name:81' => 'Jo', 'physician_last_name:81' => 'Smith', 'physician_first_name:82' => 'Al', 'physician_last_name:82' => 'Jones']);
    uamswp_test_main_loop([81, 82]);

    $output = uamswp_test_include_partial('fwp/physician-loop-card');

    expect(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('<span class="name">Jo Smith</span>')
        ->and($output)->toContain('<span class="name">Al Jones</span>');

    uamswp_test_main_loop([]);

    expect(trim(uamswp_test_include_partial('fwp/physician-loop-card')))->toBe('');
});
