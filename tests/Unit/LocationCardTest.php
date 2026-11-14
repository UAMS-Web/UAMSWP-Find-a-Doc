<?php

/**
 * The location card (templates/loops/location-card.php) and the locations block (templates/blocks/locations.php)
 * that prints one for each location it finds.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-blocks.php';

/**
 * The next location id. The source ids of a location are cached for the process, so each render gets its own.
 */
function location_card_next_id(): int
{
    static $id = 5000;

    return ++$id;
}

/**
 * The date of a day from today, in the format of the ACF date fields.
 */
function location_card_date(string $relative): string
{
    return date('F j, Y', (int) strtotime($relative));
}

/**
 * Stub the functions the location card reads.
 *
 * @param  array<string, mixed>  $fields  ACF fields by name; `name:id` keys win for one post.
 * @param  array<string, mixed>  $options  `titles` (id => title), `terms` (id => [slug, name]), `thumbnails` (ids with a featured image).
 */
function location_card_world(array $fields, array $options = []): void
{
    uamswp_test_functions_file();
    require_once UAMS_FAD_PATH.'includes/class.fad-location-address.php';
    uamswp_test_acf_blocks_file();

    $titles = $options['titles'] ?? [];
    $terms = $options['terms'] ?? [];
    $thumbnails = $options['thumbnails'] ?? [];

    when('get_field')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name.':'.$id] ?? $fields[$name] ?? null);
    when('get_field_object')->alias(static fn (string $name, mixed $id = null): mixed => $fields[$name.':'.$id] ?? $fields[$name] ?? null);
    when('get_the_title')->alias(static fn (mixed $id = 0): string => $titles[$id] ?? 'Location '.$id);
    when('get_permalink')->alias(static fn (mixed $id = 0): string => 'https://example.test/location/'.$id.'/');
    when('get_post')->alias(static fn (mixed $id): object => (object) ['ID' => (int) $id, 'post_title' => $titles[$id] ?? 'Location '.$id]);
    when('get_term')->alias(static fn (mixed $id): object|false => isset($terms[$id]) ? (object) ['slug' => $terms[$id][0], 'name' => $terms[$id][1]] : false);
    when('has_post_thumbnail')->alias(static fn (mixed $id = 0): bool => in_array($id, $thumbnails, true));
    when('get_the_post_thumbnail')->alias(static fn (mixed $id, string $size, array $attributes): string => '[thumbnail '.$id.' '.$size.' '.$attributes['data-itemtitle'].']');
    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('__')->returnArg();
    when('esc_html__')->returnArg();
    when('esc_url')->returnArg();
    when('esc_attr')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('esc_html')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('sanitize_title_with_dashes')->alias(static fn (string $text): string => strtolower(str_replace(' ', '-', $text)));
    when('wp_reset_postdata')->justReturn();
    when('format_phone_dash')->alias(static fn (mixed $phone): string => $phone ? 'dash:'.$phone : '');
    when('uamswp_custom_table_query')->alias(static fn (string $table, string $field, mixed $values): array => $fields['__table:'.$field] ?? []);

    WP_Query::$queue = [];
    WP_Query::$queries = [];
    WP_Query::$current = 0;
}

/**
 * The hours group with every key the card reads.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function location_card_hours(array $overrides = []): array
{
    return $overrides + [
        'location_modified_hours' => false,
        'location_modified_hours_start_date' => '',
        'location_modified_hours_end' => false,
        'location_modified_hours_end_date' => '',
        'location_telemed_modified_hours_query' => false,
        'location_telemed_modified_hours_start_date' => '',
        'location_telemed_modified_hours_end' => false,
        'location_telemed_modified_hours_end_date' => '',
    ];
}

/**
 * Render the location card.
 *
 * @param  array<string, mixed>  $fields  The location's fields by name (suffixed with the new id), or `name:id` for another post.
 * @param  array<string, mixed>  $options  See location_card_world(); `variables` sets more variables for the partial.
 * @param  int  $id  Receives the location id the card was rendered for.
 */
function location_card(array $fields = [], array $options = [], ?int &$id = null): string
{
    $id = location_card_next_id();
    $named = [];

    foreach ($fields + ['location_address_1' => '1 Main St', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205', 'location_hours_group' => location_card_hours()] as $name => $value) {
        $named[str_contains($name, ':') ? str_replace(':ID', ':'.$id, $name) : $name] = $value;
    }

    $options['titles'][$id] ??= 'Clinic A';
    location_card_world($named, $options);

    return uamswp_test_include_partial('loops/location-card', ['id' => $id] + ($options['variables'] ?? []));
}

test('the location card prints the title, the address and the buttons', function (): void {
    $output = location_card();

    expect($output)->toContain('<span class="name"><a href="https://example.test/location/')
        ->and($output)->toContain('data-itemtitle="Clinic A">Clinic A</a></span>')
        ->and($output)->toContain('<p class="card-text">1 Main St<br/>')
        ->and($output)->toContain('Little Rock, AR 72205')
        ->and($output)->toContain('class="btn btn-primary" aria-label="View location page for Clinic A" data-categorytitle="View Location"')
        ->and($output)->not->toContain('Get Directions')
        ->and($output)->not->toContain('class="alert');
});

test('the location card prints the placeholder image without a featured image', function (): void {
    $output = location_card();

    expect($output)->toContain('<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="Clinic A" loading="lazy" />');
});

test('the location card prints its own featured image', function (): void {
    $id = null;
    $output = location_card([], ['thumbnails' => [5001]], $id);

    expect($output)->not->toContain('[thumbnail');

    $output = (function (): string {
        $next = location_card_next_id();
        // The card reads the thumbnail of the id it renders, so the id is allowed in before the render.
        location_card_world(['location_hours_group' => location_card_hours()], ['thumbnails' => [$next + 1], 'titles' => [$next + 1 => 'Clinic A']]);

        return uamswp_test_include_partial('loops/location-card', ['id' => $next + 1]);
    })();

    expect($output)->toContain('[thumbnail ')
        ->and($output)->toContain(' aspect-16-9-small Clinic A]')
        ->and($output)->not->toContain('no-image_16-9');
});

test('the location card names the quotes and entities in the title safely in its labels', function (): void {
    $output = location_card([], ['titles' => [location_card_next_id() + 1 => 'Mom&#8217;s "Clinic"']]);

    expect($output)->toContain('aria-label="View location page for Mom\'s \'Clinic\'"');

    // The entity text is replaced before the title is encoded, so the label carries a plain space.
    expect(location_card([], ['titles' => [location_card_next_id() + 1 => 'A&nbsp;B']]))->toContain('aria-label="View location page for A B"');
});

test('the location card shows the parent as the subtitle and uses its image', function (): void {
    $id = null;
    $parent = 7001;
    $output = location_card(
        ['location_parent' => true, 'location_parent_id' => $parent, 'location_prepend_the:'.$parent => true],
        ['titles' => [$parent => 'Main "Hospital"']],
        $id,
    );

    expect($output)->toContain('<span class="subtitle"><span class="sr-only">(</span>Part of the <a href="https://example.test/location/7001/" data-categorytitle="Parent Name" data-itemtitle="Clinic A">Main "Hospital"</a><span class="sr-only">)</span></span>')
        ->and($output)->toContain('[thumbnail 7001 aspect-16-9-small Clinic A]');
});

test('the location card names the parent without the article by default and not at all in a list of child locations', function (): void {
    $output = location_card(['location_parent' => true, 'location_parent_id' => 7002], ['titles' => [7002 => 'Main']]);

    expect($output)->toContain('Part of <a href="https://example.test/location/7002/"');

    $child = location_card(['location_parent' => true, 'location_parent_id' => 7002], ['titles' => [7002 => 'Main'], 'variables' => ['child_location_list' => true]]);

    expect($child)->not->toContain('Part of');
});

test('the location card needs the parent flag and the parent id to treat a location as a child', function (): void {
    expect(location_card(['location_parent' => true]))->not->toContain('Part of')
        ->and(location_card(['location_parent_id' => 7003]))->not->toContain('Part of');
});

test('the location card uses its own image over the parent image only when both overrides are on', function (): void {
    $id = null;
    $both = location_card(['location_parent' => true, 'location_parent_id' => 7004, 'location_image_override_parent:ID' => true, 'location_image_override_parent_featured:ID' => true], [], $id);

    expect($both)->toContain('[thumbnail '.$id.' aspect-16-9-small')
        ->and($both)->not->toContain('[thumbnail 7004');

    $one = location_card(['location_parent' => true, 'location_parent_id' => 7004, 'location_image_override_parent:ID' => true]);

    expect($one)->toContain('[thumbnail 7004');

    $other = location_card(['location_parent' => true, 'location_parent_id' => 7004, 'location_image_override_parent_featured:ID' => true]);

    expect($other)->toContain('[thumbnail 7004');
});

test('the location card marks the primary location', function (): void {
    expect(location_card([], ['variables' => ['l' => 1]]))->toContain('<span class="subtitle"><span class="sr-only">, </span>Primary Location</span>')
        ->and(location_card([], ['variables' => ['l' => 2]]))->not->toContain('Primary Location')
        ->and(location_card())->not->toContain('Primary Location');
});

test('the location card reads the address from the parent unless the child overrides it', function (): void {
    $fields = [
        'location_parent' => true,
        'location_parent_id' => 7010,
        'location_address_1:7010' => '9 Parent Ave',
        'location_city:7010' => 'Conway',
    ];

    $inherited = location_card($fields, ['titles' => [7010 => 'Main']]);

    expect($inherited)->toContain('9 Parent Ave<br/>')
        ->and($inherited)->toContain('Conway, AR 72205');

    $overridden = location_card($fields + ['location_address_override_parent:ID' => true, 'location_address_override_parent_street:ID' => true, 'location_address_1:ID' => '3 Own St'], ['titles' => [7010 => 'Main']]);

    expect($overridden)->toContain('3 Own St<br/>');
});

test('the location card prints the building, floor and suite lines', function (): void {
    $fields = [
        'location_building' => 4,
        'location_building_floor' => ['value' => '2', 'choices' => ['2' => 'Second Floor']],
        'location_suite' => 'Suite 210',
    ];

    $output = location_card($fields, ['terms' => [4 => ['west-tower', 'West Tower']]]);

    expect($output)->toContain('1 Main St<br/>')
        ->and($output)->toContain('West Tower<br />Second Floor, Suite 210<br/>');
});

test('the location card leaves out the building when it is the none building', function (): void {
    $output = location_card(['location_building' => 4, 'location_suite' => 'Suite 210'], ['terms' => [4 => ['_none', 'None']]]);

    expect($output)->toContain('Suite 210<br/>')
        ->and($output)->not->toContain('None');
});

test('the location card falls back to the older second address line', function (): void {
    $output = location_card(['location_address_2' => 'Old Wing']);

    expect($output)->toContain('Old Wing<br/>');

    $newer = location_card(['location_address_2' => 'Old Wing', 'location_suite' => 'Suite 5']);

    expect($newer)->toContain('Suite 5<br/>')->not->toContain('Old Wing');
});

test('the location card leaves the floor label empty for a floor it has no choice for', function (): void {
    $output = location_card(['location_building_floor' => ['value' => '9', 'choices' => ['2' => 'Second Floor']], 'location_suite' => 'Suite 1']);

    expect($output)->toContain('Suite 1<br/>')->not->toContain('Second Floor');
});

test('the location card links to directions from the map coordinates', function (): void {
    $output = location_card(['location_map' => ['lat' => 34.7, 'lng' => -92.3]]);

    expect($output)->toContain('href="https://www.google.com/maps/dir/Current+Location/34.7,-92.3" target="_blank" aria-label="Get Directions to Clinic A" data-categorytitle="Get Directions" data-itemtitle="Clinic A">Get Directions</a>');
});

test('the location card prints the phone numbers of the location', function (): void {
    $output = location_card(['location_phone' => '501-1']);

    expect($output)->toContain('<dt>Appointment Phone Number</dt>')
        ->and($output)->toContain('data-itemtitle="Clinic A" data-typetitle="Appointment Phone Number for New and Returning Patients">dash:501-1</a>');
});

test('the location card warns about a closure that has passed, is coming, or is temporary', function (string $length, string $date, string $reopen, string $expected): void {
    $id = null;
    $output = location_card([
        'location_closing:ID' => true,
        'location_closing_length:ID' => $length,
        'location_closing_date:ID' => $date,
        'location_reopen_date:ID' => $reopen,
    ], [], $id);

    $text = trim((string) preg_replace('/\s+/', ' ', strip_tags($output)));

    expect($output)->toContain('<div class="alert alert-warning" role="alert">')
        ->and($text)->toContain($expected)
        ->and($output)->toContain('aria-label="Learn more about the closure of Clinic A." class="alert-link" data-categorytitle="Alert" data-itemtitle="Clinic A">Learn more</a>');
})->with(fn () => [
    'permanent and past' => ['permanent', location_card_date('-10 days'), '', 'This location is permanently closed.'],
    'permanent and coming' => ['permanent', location_card_date('+10 days'), '', 'This location will be closing permanently on '.location_card_date('+10 days').'.'],
    'temporary, past and not reopened' => ['temporary', location_card_date('-10 days'), location_card_date('+20 days'), 'This location is temporarily closed.'],
    'temporary and coming' => ['temporary', location_card_date('+10 days'), location_card_date('+20 days'), 'This location will be closing temporarily beginning on '.location_card_date('+10 days').'.'],
]);

test('the location card does not warn about a closure without the flag, with a temporary closure that has ended, or with an unknown length', function (array $fields): void {
    $output = location_card($fields);

    expect($output)->not->toContain('class="alert');
})->with(fn () => [
    'no flag' => [['location_closing_length:ID' => 'permanent', 'location_closing_date:ID' => location_card_date('-10 days')]],
    'ended' => [['location_closing:ID' => true, 'location_closing_length:ID' => 'temporary', 'location_closing_date:ID' => location_card_date('-20 days'), 'location_reopen_date:ID' => location_card_date('-1 days')]],
    'unknown length' => [['location_closing:ID' => true, 'location_closing_length:ID' => 'other', 'location_closing_date:ID' => location_card_date('-20 days')]],
]);

test('the location card warns about modified hours that are under way or coming', function (array $hours, string $expected): void {
    $output = location_card(['location_hours_group' => location_card_hours($hours)]);
    $text = trim((string) preg_replace('/\s+/', ' ', strip_tags($output)));

    expect($text)->toContain($expected)
        ->and($output)->toContain('aria-label="Learn more about the modified hours." class="alert-link"');
})->with(fn () => [
    'under way with an end in the future' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-5 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('+5 days')], "This location's hours have been temporarily modified."],
    'under way with no known end' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-5 days')], "This location's hours have been temporarily modified."],
    'ending today' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-5 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('today')], "This location's hours have been temporarily modified."],
    'coming within 30 days' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+10 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('+20 days')], "This location's hours will be temporarily modified beginning on ".location_card_date('+10 days').'.'],
    'telemedicine under way' => [['location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('-3 days'), 'location_telemed_modified_hours_end' => true, 'location_telemed_modified_hours_end_date' => location_card_date('+3 days')], "This location's hours have been temporarily modified."],
    'telemedicine coming' => [['location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+4 days')], "This location's hours will be temporarily modified beginning on ".location_card_date('+4 days').'.'],
]);

test('the location card does not warn about modified hours that are over or more than 30 days away', function (array $hours): void {
    $output = location_card(['location_hours_group' => location_card_hours($hours)]);

    expect($output)->not->toContain('class="alert');
})->with(fn () => [
    'over' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-10 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('-2 days')]],
    'too far away' => [['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+40 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('+50 days')]],
    'telemedicine over' => [['location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('-10 days'), 'location_telemed_modified_hours_end' => true, 'location_telemed_modified_hours_end_date' => location_card_date('-2 days')]],
    'telemedicine too far away' => [['location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+40 days')]],
    'not modified' => [[]],
]);

test('the location card starts the modified hours at the earlier of the clinic and telemedicine dates', function (): void {
    $clinicFirst = location_card(['location_hours_group' => location_card_hours([
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+5 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+9 days'),
    ])]);

    expect($clinicFirst)->toContain('beginning on '.location_card_date('+5 days').'.');

    $telemedFirst = location_card(['location_hours_group' => location_card_hours([
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+9 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+5 days'),
    ])]);

    expect($telemedFirst)->toContain('beginning on '.location_card_date('+5 days').'.');

    // The same day written two ways is not earlier, so the clinic date is kept.
    $sameDay = location_card(['location_hours_group' => location_card_hours([
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+5 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => date('Y-m-d', strtotime('+5 days')),
    ])]);

    expect($sameDay)->toContain('beginning on '.location_card_date('+5 days').'.');
});

test('the location card names the start date of the source that is in its window', function (array $hours, string $expected): void {
    $output = location_card(['location_hours_group' => location_card_hours($hours)]);

    expect($output)->toContain($expected);
})->with([
    'clinic over, telemedicine coming' => [[
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-10 days'), 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('-2 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+5 days'),
    ], 'hours will be temporarily modified beginning on '.location_card_date('+5 days').'.'],
    'clinic too far away, telemedicine under way' => [[
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+40 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('-3 days'), 'location_telemed_modified_hours_end' => true, 'location_telemed_modified_hours_end_date' => location_card_date('+3 days'),
    ], "This location's hours have been temporarily modified."],
    'telemedicine over, clinic coming' => [[
        'location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+7 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('-10 days'), 'location_telemed_modified_hours_end' => true, 'location_telemed_modified_hours_end_date' => location_card_date('-2 days'),
    ], 'hours will be temporarily modified beginning on '.location_card_date('+7 days').'.'],
]);

test('the location card does not warn about modified hours flagged without a start date', function (array $hours): void {
    // ACF requires the start date once the flag is on, so only data saved some other way has none.
    $output = location_card(['location_hours_group' => location_card_hours($hours)]);

    expect($output)->not->toContain('class="alert');
})->with(fn () => [
    'clinic' => [['location_modified_hours' => true]],
    'clinic with a future end' => [['location_modified_hours' => true, 'location_modified_hours_end' => true, 'location_modified_hours_end_date' => location_card_date('+5 days')]],
    'telemedicine' => [['location_telemed_modified_hours_query' => true]],
    'both' => [['location_modified_hours' => true, 'location_telemed_modified_hours_query' => true]],
]);

test('the location card starts the modified hours at the flagged date, not at a date left behind by an unflagged one', function (): void {
    $output = location_card(['location_hours_group' => location_card_hours([
        'location_modified_hours' => false, 'location_modified_hours_start_date' => location_card_date('+2 days'),
        'location_telemed_modified_hours_query' => true, 'location_telemed_modified_hours_start_date' => location_card_date('+7 days'),
    ])]);

    expect($output)->toContain('beginning on '.location_card_date('+7 days').'.')
        ->and($output)->not->toContain(location_card_date('+2 days'));
});

test('the location card does not print the start date of the card before it', function (): void {
    // The card runs in the scope of the file that includes it, so a loop renders every card in one scope.
    $first = location_card_next_id();
    $second = location_card_next_id();
    location_card_world([
        'location_hours_group:'.$first => location_card_hours(['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('+3 days')]),
        'location_hours_group:'.$second => location_card_hours(['location_modified_hours' => true]),
    ]);

    $outputs = (static function (array $ids): array {
        $outputs = [];

        foreach ($ids as $id) {
            ob_start();
            include UAMS_FAD_PATH.'templates/loops/location-card.php';
            $outputs[] = (string) ob_get_clean();
        }

        return $outputs;
    })([$first, $second]);

    expect($outputs[0])->toContain('beginning on '.location_card_date('+3 days').'.')
        ->and($outputs[1])->not->toContain('class="alert')
        ->and($outputs[1])->not->toContain(location_card_date('+3 days'));
});

test('the location card puts the closure before the modified hours', function (): void {
    $output = location_card([
        'location_closing:ID' => true,
        'location_closing_length:ID' => 'permanent',
        'location_closing_date:ID' => location_card_date('+10 days'),
        'location_hours_group' => location_card_hours(['location_modified_hours' => true, 'location_modified_hours_start_date' => location_card_date('-5 days')]),
    ]);

    expect($output)->toContain('This location will be closing permanently')
        ->and($output)->not->toContain('hours have been temporarily modified')
        ->and($output)->toContain('Learn more about the closure of Clinic A.');
});

/**
 * Render the locations block.
 *
 * @param  array<string, mixed>  $fields  The block's ACF fields by name.
 * @param  list<list<int>>  $queries  The location ids each query finds, in the order the template builds them.
 * @param  array<string, mixed>  $variables  The variables the block wrapper sets.
 */
function locations_block(array $fields, array $queries, array $variables = []): string
{
    location_card_world($fields + ['location_hours_group' => location_card_hours()], ['titles' => [61 => 'Clinic One', 62 => 'Clinic Two']]);
    WP_Query::$queue = $queries;

    return uamswp_test_include_partial('blocks/locations', $variables);
}

test('the locations block prints nothing without a filter', function (): void {
    expect(locations_block(['block_fad_locations_heading' => 'Our Locations'], []))->toBe('')
        ->and(WP_Query::$queries)->toBe([]);
});

test('the locations block prints a card for each location of the ids it was given', function (): void {
    $output = locations_block([
        'block_fad_locations_heading' => 'Our Locations',
        'block_fad_locations_description' => 'Find us.',
        'block_fad_locations_filter_ids' => [61, 62],
    ], [[61, 62]], ['block' => ['id' => 'abc']]);

    expect($output)->toContain('<section class="uams-module location-list alignfull bg-auto" id="locations-abc">')
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Our Locations</span></h2>')
        ->and($output)->toContain('<div class="module-description"><p>Find us.</p></div>')
        ->and($output)->toContain('card-list-container location-card-list-container')
        ->and(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('>Clinic Two</a></span>')
        ->and($output)->not->toContain('class="more"')
        ->and(WP_Query::$queries)->toHaveCount(1)
        ->and(WP_Query::$queries[0])->toMatchArray(['post_type' => 'location', 'order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_status' => 'publish', 'post__in' => [61, 62]]);
});

test('the locations block takes the section id from the block id and ignores the module and index variables', function (): void {
    $fields = ['block_fad_locations_filter_ids' => [61]];
    $withBlock = locations_block($fields, [[61]], ['block' => ['id' => 'block_abc']]);
    $withoutBlock = locations_block($fields, [[61]], ['module' => ['anchor_id' => 'My Anchor'], 'i' => 4]);

    expect($withBlock)->toContain('id="locations-block_abc"')
        ->and($withoutBlock)->toContain('id="locations-"')
        ->and($withoutBlock)->not->toContain('my-anchor')
        ->and($withoutBlock)->not->toContain('module-5');
});

test('the locations block prints nothing when the query finds no location', function (): void {
    expect(locations_block(['block_fad_locations_filter_ids' => [61]], [[]], ['block' => ['id' => 'abc']]))->toBe('');
});

test('the locations block takes the values from the variables the wrapper sets before the fields', function (): void {
    $output = locations_block(
        ['block_fad_locations_heading' => 'Field heading', 'block_fad_locations_filter_ids' => [61]],
        [[61]],
        ['block' => ['id' => 'abc'], 'heading' => 'Set heading', 'content_block' => 'Set text', 'background_color' => 'bg-white'],
    );

    expect($output)->toContain('<span class="title">Set heading</span>')
        ->and($output)->toContain('<p>Set text</p>')
        ->and($output)->toContain('location-list alignfull bg-white');
});

test('the locations block filters by type, region and area of expertise, then adds the specific ids', function (): void {
    $output = locations_block([
        'block_fad_locations_filter_ids' => [61],
        'block_fad_locations_filter_type' => [3],
        'block_fad_locations_filter_region' => [7],
        'block_fad_locations_filter_aoe' => [4],
        '__table:location_expertise' => [62, 63],
    ], [[62], [61, 62]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0])->toMatchArray(['fields' => 'ids', 'post_type' => 'location', 'post__in' => [62, 63]])
        ->and(WP_Query::$queries[0]['tax_query'])->toBe([['relation' => 'AND'], ['taxonomy' => 'location_type', 'terms' => [3]], ['taxonomy' => 'region', 'terms' => [7]]])
        ->and(array_values(WP_Query::$queries[1]['post__in']))->toBe([61, 62])
        ->and(substr_count($output, '<div class="card">'))->toBe(2);
});

test('the locations block uses one taxonomy filter without the relation', function (): void {
    locations_block(['block_fad_locations_filter_type' => [3]], [[61], [61]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0]['tax_query'])->toBe([['taxonomy' => 'location_type', 'terms' => [3]]])
        ->and(WP_Query::$queries[0]['post__in'])->toBe([]);

    locations_block(['block_fad_locations_filter_region' => [7]], [[61], [61]], ['block' => ['id' => 'abc']]);

    expect(WP_Query::$queries[0]['tax_query'])->toBe([['taxonomy' => 'region', 'terms' => [7]]]);
});

test('the locations block ends with a link button when it has one', function (string $background, string $color): void {
    $fields = [
        'block_fad_locations_filter_ids' => [61],
        'block_fad_locations_more' => true,
        'block_fad_locations_more_text' => 'Want more?',
        'block_fad_locations_more_button_text' => 'See all',
        'block_fad_locations_more_button_url' => ['url' => 'https://example.test/locations/', 'target' => '_blank'],
        'block_fad_locations_more_button_description' => 'See all "locations"',
        'block_fad_locations_background_color' => $background,
    ];

    $output = locations_block($fields, [[61]], ['block' => ['id' => 'abc']]);

    expect($output)->toContain('<p class="lead">Want more?</p>')
        ->and($output)->toContain('<a href="https://example.test/locations/" class="btn btn-outline-'.$color.'" aria-label="See all &#039;locations&#039;" target="_blank" rel="noopener">See all</a>');
})->with([
    'white' => ['bg-white', 'primary'],
    'gray' => ['bg-gray', 'primary'],
    'other' => ['bg-primary', 'white'],
    'none' => ['', 'white'],
]);

test('the locations block takes the button values from the variables and keeps a target the wrapper set', function (): void {
    $output = locations_block(
        ['block_fad_locations_filter_ids' => [61], 'block_fad_locations_more' => true],
        [[61]],
        [
            'block' => ['id' => 'abc'],
            'more_text' => 'Set more',
            'more_button_text' => 'Set button',
            'more_button_url' => ['url' => 'https://set.example.test/', 'target' => '_blank'],
            'more_button_target' => '_top',
            'more_button_description' => 'Set description',
            'more_button_color' => 'secondary',
            'background_color' => 'bg-white',
        ],
    );

    expect($output)->toContain('<p class="lead">Set more</p>')
        ->and($output)->toContain('href="https://set.example.test/"')
        ->and($output)->toContain('aria-label="Set description" target="_top">Set button</a>')
        ->and($output)->toContain('btn btn-outline-white');
});

test('the locations block prints only the placeholder for a new block in the editor', function (): void {
    $output = locations_block(['block_fad_locations_filter_ids' => [61]], [[61]], ['block' => ['id' => 'abc', 'title' => 'Locations', 'data' => []], 'is_preview' => true]);

    expect($output)->toContain('uams-block-placeholder')
        ->and($output)->not->toContain('location-list');
});

test('the location loop prints a card for each post of the main loop, or a message', function (): void {
    location_card_world(['location_hours_group' => location_card_hours()], ['titles' => [71 => 'Clinic One', 72 => 'Clinic Two']]);
    uamswp_test_main_loop([71, 72]);

    $output = uamswp_test_include_partial('fwp/location-loop');

    expect(substr_count($output, '<div class="card">'))->toBe(2)
        ->and($output)->toContain('>Clinic One</a></span>')
        ->and($output)->toContain('>Clinic Two</a></span>')
        ->and($output)->not->toContain('Sorry');

    when('_e')->alias(static function (string $text): void {
        echo $text;
    });
    uamswp_test_main_loop([]);

    expect(trim(uamswp_test_include_partial('fwp/location-loop')))->toBe('<p>Sorry, no locations matched your criteria.</p>');
});
