<?php

/**
 * The markup the location profile template (templates/single-locations.php) prints: the header, the hours, the
 * photos and the jump links.
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/single-location.php';

/**
 * Render the location profile and return its output with the whitespace between tags removed.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 */
function single_location_markup(array $fields = [], array $options = []): string
{
    $output = single_location($fields, $options)['single_location_output'];

    return preg_replace('/\s*(<[^>]+>)\s*/', '$1', preg_replace('/\s+/', ' ', $output));
}

/**
 * An hours group with the typical hours given.
 *
 * @param  list<array<string, mixed>>  $hours
 * @param  array<string, mixed>  $group  More values of the hours group.
 * @return array<string, mixed>
 */
function single_location_hours(array $hours, array $group = []): array
{
    return ['location_hours_group' => $group + ['location_hours' => $hours]];
}

/**
 * One row of typical hours.
 *
 * @return array<string, mixed>
 */
function single_location_day(string $day = 'Mon - Fri', string $open = '08:00:00', string $close = '17:00:00', bool $closed = false, string $comment = ''): array
{
    return ['day' => $day, 'open' => $open, 'close' => $close, 'closed' => $closed, 'comment' => $comment];
}

test('the page opens with the title of the location', function (): void {
    $output = single_location_markup([], ['titles' => [0 => 'Heart Clinic']]);

    expect($output)->toStartWith('<div class="content-sidebar-wrap"><main class="location-item" id="genesis-content">')
        ->and($output)->toContain('<h1 class="page-title">Heart Clinic</h1>');
});

test('a child location names its parent under the title', function (): void {
    $options = ['posts' => [90 => (object) ['ID' => 90, 'post_title' => 'Medical Center']], 'fields_by_id' => [90 => ['location_prepend_the' => true]]];
    $output = single_location_markup(['location_parent' => true, 'location_parent_id' => 90], $options);

    expect($output)->toContain('<h1 class="page-title">Clinic<span class="subtitle"><span class="sr-only">(</span>Part of the<a href="https://example.test/p/90/">Medical Center</a><span class="sr-only">)</span></span></h1>');
});

test('a closing location warns about the closing date', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2099'];
    $output = single_location_markup($fields);

    expect($output)->toContain('<div class="alert alert-warning" role="alert"><p>This location will be closing permanently on January 1, 2099.</p></div>');
});

test('a location that has closed says so', function (string $length, string $word): void {
    $output = single_location_markup(['location_closing' => true, 'location_closing_length' => $length, 'location_closing_date' => 'January 1, 2020']);

    expect($output)->toContain('This location is '.$word.' closed.');
})->with([
    'permanently' => ['permanent', 'permanently'],
    'temporarily' => ['temporary', 'temporarily'],
]);

test('a temporary closing says when it begins', function (): void {
    $output = single_location_markup(['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2099']);

    expect($output)->toContain('This location will be closing temporarily beginning on January 1, 2099.');
});

test('a temporary closing says when it reopens', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020', 'location_reopen_known' => 'date'];

    expect(single_location_markup($fields + ['location_reopen_date' => 'January 9, 2099']))->toContain('It is scheduled to reopen on January 9, 2099.')
        ->and(single_location_markup($fields + ['location_reopen_date' => 'December 1, 2019']))->not->toContain('scheduled to reopen')
        ->and(single_location_markup($fields))->not->toContain('scheduled to reopen');
});

test('a temporary closing with no date says it lasts until further notice', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020'];

    expect(single_location_markup($fields + ['location_reopen_known' => 'tbd']))->toContain('It will remain closed until further notice.')
        ->and(single_location_markup($fields + ['location_reopen_known' => 'date', 'location_reopen_date' => 'January 9, 2099']))->not->toContain('until further notice');
});

test('a closing with information links to it', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2020'];

    expect(single_location_markup($fields + ['location_closing_info' => 'Moving.']))->toContain('<a href="#closing-info" class="alert-link no-break" aria-label="Learn more information about the closure of this location">Learn more</a>.')
        ->and(single_location_markup($fields))->not->toContain('href="#closing-info" class="alert-link');
});

test('a closing location that keeps telemedicine says so', function (): void {
    // Only a temporary closing reads the telemedicine answer.
    $fields = ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020', 'location_reopen_date' => 'January 1, 2099'];

    expect(single_location_markup($fields + ['location_closing_telemed' => 'yes']))->toContain('<p>Telemedicine will still be available.<a href="#telemedicine-info" class="alert-link no-break" aria-label="Learn more information about telemedicine at this location">Learn more</a>.</p>')
        ->and(single_location_markup($fields))->not->toContain('Telemedicine will still be available');
});

test('a location that is not closing has no closing warning', function (): void {
    expect(single_location_markup())->not->toContain('alert-warning');
});

test('the address prints on lines with the city, state and zip', function (): void {
    $output = single_location_markup(['location_address_1' => '1 Main St', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205']);

    expect($output)->toContain('<h2 class="sr-only">Address</h2><p>1 Main St<br/>Little Rock, AR 72205</p>');
});

test('the old second address line prints when there is no building, floor or suite', function (): void {
    $fields = ['location_address_1' => '1 Main St', 'location_address_2' => 'Room 5', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205'];

    expect(single_location_markup($fields))->toContain('<p>1 Main St<br/>Room 5<br/>Little Rock, AR 72205</p>');
});

test('the directions button links to the map position', function (): void {
    $output = single_location_markup(['location_map' => ['lat' => 34.7, 'lng' => -92.3]], ['titles' => [0 => 'Heart Clinic']]);

    expect($output)->toContain('<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/34.7,-92.3" target="_blank" aria-label="Get directions to Heart Clinic" data-typetitle="Get directions to the clinic">Get Directions</a>');
});

test('the parking button shows only with parking information', function (): void {
    expect(single_location_markup(['location_parking' => 'Garage.']))->toContain('<a class="btn btn-outline-primary" href="#parking-info" aria-label="Parking instructions for Clinic" data-typetitle="Parking instructions for the clinic">Parking Instructions</a>')
        ->and(single_location_markup())->not->toContain('Parking Instructions');
});

test('the location website prints as a button with the name', function (): void {
    $output = single_location_markup(['location_web_name' => 'Clinic site', 'location_url' => ['url' => 'https://clinic.test/']]);

    expect($output)->toContain('<p><a class="btn btn-secondary" href="https://clinic.test/" target="_blank" data-categorytitle="External Link">Clinic site<span class="far fa-external-link-alt"></span></span></a></p>')
        ->and(single_location_markup(['location_web_name' => 'Clinic site']))->not->toContain('btn-secondary');
});

test('hours that vary print the information instead of the hours', function (): void {
    $output = single_location_markup(single_location_hours([single_location_day()], ['location_hours_variable' => true, 'location_hours_variable_info' => '<p>Call first.</p>']));

    expect($output)->toContain('<h2>Hours Vary</h2><p>Call first.</p>')
        ->and($output)->not->toContain('<dl class="hours">');
});

test('typical hours print each day with its times and comment', function (): void {
    $hours = [single_location_day('Mon - Fri', '08:00:00', '17:00:00', false, 'By appointment'), single_location_day('Sat', '09:00:00', '12:00:00'), single_location_day('Sun', '00:00:00', '00:00:00', true)];
    $output = single_location_markup(single_location_hours($hours));

    expect($output)->toContain('<h2>Hours</h2><dl class="hours"><dt>Mon - Fri</dt><dd>8 a.m. &ndash; 5 p.m.<br /><span class="subtitle">By appointment</span></dd><dt>Sat</dt><dd>9 a.m. &ndash; Noon</dd><dt>Sun</dt><dd>Closed</dd></dl>');
});

test('a day with several rows prints its name once', function (): void {
    $output = single_location_markup(single_location_hours([single_location_day('Mon', '08:00:00', '12:00:00'), single_location_day('Mon', '13:00:00', '17:00:00')]));

    expect(substr_count($output, '<dt>Mon</dt>'))->toBe(1)
        ->and($output)->toContain('<dd>8 a.m. &ndash; Noon</dd><dd>1 &ndash; 5 p.m.</dd>');
});

test('hours open around the clock print as 24/7', function (): void {
    $output = single_location_markup(single_location_hours([], ['location_24_7' => true]));

    expect($output)->toContain('<h2>Hours</h2><strong>Open 24/7</strong>')
        ->and($output)->not->toContain('<h2>After Hours</h2>')
        ->and($output)->toContain('"openingHours": "Mo-Su",');
});

test('a location with no hours prints no hours section', function (): void {
    expect(single_location_markup())->not->toContain('<h2>Hours</h2>');
});

test('the after hours text is the location text or else the system text', function (): void {
    $own = single_location_markup(single_location_hours([single_location_day()], ['location_after_hours' => '<p>Call us.</p>']));
    $system = single_location_markup(single_location_hours([single_location_day()]), ['option' => ['location_afterhours_descr_system' => '<p>System.</p>']]);

    expect($own)->toContain('<h2>After Hours</h2><p>Call us.</p>')
        ->and($system)->toContain('<h2>After Hours</h2><p>System.</p>');
});

test('the structured data lists the typical hours', function (): void {
    $output = single_location_markup(single_location_hours([single_location_day('Mon - Fri'), single_location_day('Sat', '09:00:00', '12:00:00'), single_location_day('Sun', '00:00:00', '00:00:00', true)]));

    expect($output)->toContain('"openingHours": ["Mo-Fr 08:00-17:00", "Sa 09:00-12:00"],');
});

test('a single image prints at each size', function (): void {
    $output = single_location_markup(['location_wayfinding_photo' => 501], ['meta' => ['_wp_attachment_image_alt' => 'The clinic']]);

    expect($output)->toContain('<div class="col-12 col-md image"><div class="content-width"><picture>')
        ->and($output)->toContain('<source srcset="https://example.test/photo-630x473.jpg" media="(min-width: 1350px)">')
        ->and($output)->toContain('<source srcset="https://example.test/photo-576x324.jpg" media="(min-width: 1px)">')
        ->and($output)->toContain('<img src="https://example.test/photo-630x473.jpg" alt="The clinic" class="single-image" />')
        ->and($output)->not->toContain('carousel');
});

test('several images print as a carousel with thumbnails', function (): void {
    $output = single_location_markup(['location_wayfinding_photo' => 501, 'location_photo_gallery' => [502, 503]]);

    expect($output)->toContain('<div class="carousel slide carousel-thumbnails" id="location-info-carousel"')
        ->and(substr_count($output, 'class="carousel-item'))->toBe(3)
        ->and($output)->toContain('<div class="carousel-item active">')
        ->and($output)->toContain('<li data-target="#location-info-carousel" data-slide-to="0" class="active"><img src="https://example.test/photo-60x45.jpg" alt="" /></li>')
        ->and($output)->toContain('<li data-target="#location-info-carousel" data-slide-to="2" ><img');
});

test('a location with no images prints no image column', function (): void {
    expect(single_location_markup())->not->toContain('class="col-12 col-md image"');
});

test('holiday hours within thirty days print after the typical hours', function (): void {
    $date = date('m/d/Y', strtotime('+10 days'));
    $far = date('m/d/Y', strtotime('+90 days'));
    $past = date('m/d/Y', strtotime('-10 days'));
    $output = single_location_markup(single_location_hours([single_location_day()]) + ['location_holiday_hours' => [
        ['date' => $far, 'label' => 'Far', 'closed' => true, 'open' => '', 'close' => ''],
        ['date' => $date, 'label' => 'Thanksgiving', 'closed' => true, 'open' => '', 'close' => ''],
        ['date' => $past, 'label' => 'Past', 'closed' => true, 'open' => '', 'close' => ''],
    ]]);

    expect($output)->toContain('<h3>Upcoming Holiday Hours</h3><dl class="hours"><dt>Thanksgiving<br />'.$date.'<br/></dt><dd>Closed</dd></dl>')
        ->and($output)->not->toContain('Far<br />')
        ->and($output)->not->toContain('Past<br />');
});

test('holiday hours with times print the times', function (): void {
    $date = date('m/d/Y', strtotime('+10 days'));
    $output = single_location_markup(single_location_hours([single_location_day()]) + ['location_holiday_hours' => [
        ['date' => $date, 'label' => 'Half day', 'closed' => false, 'open' => '09:00:00', 'close' => '13:00:00'],
    ]]);

    expect($output)->toContain('<dt>Half day<br />'.$date.'<br/></dt><dd>9 a.m. &ndash; 1 p.m.</dd></dl>');
});

test('holiday hours that are all far away or past print no heading', function (): void {
    $output = single_location_markup(single_location_hours([single_location_day()]) + ['location_holiday_hours' => [
        ['date' => date('m/d/Y', strtotime('+90 days')), 'label' => 'Far', 'closed' => true, 'open' => '', 'close' => ''],
    ]]);

    expect($output)->not->toContain('Upcoming Holiday Hours');
});

/**
 * An hours group with modified hours that started yesterday.
 *
 * @param  list<array<string, mixed>>  $groups  The rows of modified hours.
 * @param  array<string, mixed>  $group  More values of the hours group.
 * @return array<string, mixed>
 */
function single_location_modified(array $groups, array $group = []): array
{
    return single_location_hours([single_location_day()], $group + [
        'location_modified_hours' => true,
        'location_modified_hours_reason' => '<p>Renovation.</p>',
        'location_modified_hours_start_date' => date('F j, Y', strtotime('-1 day')),
        'location_modified_hours_end' => true,
        'location_modified_hours_end_date' => date('F j, Y', strtotime('+10 days')),
        'location_modified_hours_group' => $groups,
    ]);
}

/**
 * One row of modified hours times.
 *
 * @return array<string, mixed>
 */
function single_location_modified_time(string $day = 'Mon', string $open = '10:00:00', string $close = '14:00:00', bool $closed = false, string $comment = ''): array
{
    return ['location_modified_hours_day' => $day, 'location_modified_hours_open' => $open, 'location_modified_hours_close' => $close, 'location_modified_hours_closed' => $closed, 'location_modified_hours_comment' => $comment];
}

test('modified hours print the reason, the dates and each group', function (): void {
    $groups = [['location_modified_hours_title' => 'Main desk', 'location_modified_hours_information' => '<p>Use the side door.</p>', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => [single_location_modified_time('Mon', '10:00:00', '14:00:00', false, 'Walk-ins'), single_location_modified_time('Tue', '00:00:00', '00:00:00', true)]]];
    $group = single_location_modified($groups);
    $output = single_location_markup($group);
    $start = $group['location_hours_group']['location_modified_hours_start_date'];
    $end = $group['location_hours_group']['location_modified_hours_end_date'];

    expect($output)->toContain('<h2>Modified Hours</h2><p>Renovation.</p><p class="small font-italic">These modified hours start on '.$start.', and are scheduled to end after '.$end.'.</p>')
        ->and($output)->toContain('<h3 class="h4">Main desk</h3><p>Use the side door.</p><dl class="hours"><dt>Mon</dt><dd>10 a.m. &ndash; 2 p.m.<br /><span class="subtitle">Walk-ins</span></dd><dt>Tue</dt><dd>Closed</dd></dl>')
        ->and($output)->toContain('<h2>Typical Hours</h2>');
});

test('modified hours in effect for more than thirty days replace the typical hours', function (): void {
    // The window is only known from the groups of modified hours.
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => [single_location_modified_time()]]];
    $far = single_location_markup(single_location_modified($groups, ['location_modified_hours_end_date' => date('F j, Y', strtotime('+90 days'))]));
    $open = single_location_markup(single_location_modified($groups, ['location_modified_hours_end' => false, 'location_modified_hours_end_date' => '']));

    expect($far)->toContain('<h2>Modified Hours</h2>')
        ->and($far)->not->toContain('Typical Hours')
        ->and($far)->not->toContain('<dt>Mon - Fri</dt>')
        ->and($open)->toContain('<h2>Modified Hours</h2>')
        ->and($open)->not->toContain('Typical Hours');
});

test('modified hours without an end date stay in effect until further notice', function (): void {
    $output = single_location_markup(single_location_modified([], ['location_modified_hours_end' => false, 'location_modified_hours_end_date' => '']));

    expect($output)->toContain('and will remain in effect until further notice.</p>');
});

test('modified hours open around the clock print as 24/7', function (): void {
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => true, 'location_modified_hours_times' => []]];
    $group = single_location_modified($groups);
    $output = single_location_markup($group);
    $start = date('Y-m-d', strtotime($group['location_hours_group']['location_modified_hours_start_date']));
    $end = date('Y-m-d', strtotime($group['location_hours_group']['location_modified_hours_end_date']));

    expect($output)->toContain('<strong>Open 24/7</strong>')
        ->and($output)->toContain('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "'.$start.'", "validThrough": "'.$end.'", "dayOfWeek": [ "Monday",');
});

test('modified hours that start more than thirty days away are not printed', function (): void {
    $output = single_location_markup(single_location_modified([], ['location_modified_hours_start_date' => date('F j, Y', strtotime('+60 days')), 'location_modified_hours_end_date' => date('F j, Y', strtotime('+70 days'))]));

    expect($output)->not->toContain('Modified Hours')
        ->and($output)->toContain('<h2>Hours</h2>');
});

test('modified hours that have ended are not printed', function (): void {
    $output = single_location_markup(single_location_modified([], ['location_modified_hours_start_date' => date('F j, Y', strtotime('-20 days')), 'location_modified_hours_end_date' => date('F j, Y', strtotime('-10 days'))]));

    expect($output)->not->toContain('Modified Hours');
});

test('upcoming modified hours are listed beside the typical hours', function (): void {
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => [single_location_modified_time()]]];
    $output = single_location_markup(single_location_modified($groups, ['location_modified_hours_start_date' => date('F j, Y', strtotime('+10 days')), 'location_modified_hours_end_date' => date('F j, Y', strtotime('+20 days'))]));

    expect($output)->toContain('<h2>Modified Hours</h2>')
        ->and($output)->toContain('<h2>Typical Hours</h2>');
});

test('the structured data describes the modified hours', function (): void {
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => [single_location_modified_time('Mon - Fri', '10:00:00', '14:00:00'), single_location_modified_time('Sat', '00:00:00', '00:00:00', true)]]];
    $group = single_location_modified($groups);
    $output = single_location_markup($group);
    $start = date('Y-m-d', strtotime($group['location_hours_group']['location_modified_hours_start_date']));
    $end = date('Y-m-d', strtotime($group['location_hours_group']['location_modified_hours_end_date']));

    expect($output)->toContain('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "'.$start.'", "validThrough": "'.$end.'", "dayOfWeek": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday" ], "opens": "10:00", "closes": "14:00" },')
        ->and($output)->toContain('"dayOfWeek": "Sat", "opens": "00:00", "closes": "00:00" } ],');
});

test('the jump links list the sections that have content in page order', function (): void {
    $fields = ['location_parking' => 'x', 'location_appointment' => 'y', 'location_about' => 'z', 'location_hours_group' => ['location_telemed_query' => true]];
    $output = single_location_markup($fields, ['option' => ['location_alert_heading_system' => 'Notice']]);

    expect($output)->toContain('<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links"><h2>Contents</h2>')
        ->and(preg_match_all('/<a class="nav-link" href="(#[^"]*)"/', $output, $matches))->toBeGreaterThan(0)
        ->and($matches[1])->toBe(['#location-alert', '#description', '#parking-info', '#appointment-info', '#telemedicine-info']);
});

test('the alert link in the jump links is named by the alert title', function (): void {
    $titled = single_location_markup(['location_parking' => 'x'], ['option' => ['location_alert_heading_system' => 'Road work']]);
    $untitled = single_location_markup(['location_parking' => 'x'], ['option' => ['location_alert_body_system' => 'Text']]);

    expect($titled)->toContain('title="Jump to the section of this page with the alert regarding this location">Road work</a>')
        ->and($untitled)->toContain('title="Jump to the section of this page with the alert regarding this location">Alert</a>');
});

test('the closing information has a jump link', function (): void {
    $fields = ['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2020', 'location_closing_info' => 'Moving.', 'location_parking' => 'x'];

    expect(single_location_markup($fields))->toContain('<a class="nav-link" href="#closing-info" title="Jump to the section of this page with the closing information">Closing Information</a>');
});

test('the about link has a submenu for the affiliation and prescription sections', function (): void {
    $fields = ['location_about' => 'x', 'location_affiliation' => 'UAMS', 'location_prescription_query' => true, 'location_prescription_type' => 'custom', 'location_prescription' => 'Rx', 'location_parking' => 'x'];
    $output = single_location_markup($fields);

    expect($output)->toContain('<li class="nav-item dropdown"><a class="nav-link" href="#description"')
        ->and($output)->toContain('<ul class="dropdown-menu"><li class="nav-item"><a class="nav-link" href="#affiliation" title="Jump to the section of this page about Affiliation">Affiliation</a></li><li class="Prescription Information"><a class="nav-link" href="#prescription-info" title="Jump to the section of this page about Prescription Information">Prescription Information</a></li></ul>');
});

test('the jump links name the scheduling, portal and ontology sections', function (): void {
    $fields = ['location_scheduling_query' => true, 'location_portal' => 60, 'physician_locations' => [7], 'location_conditions_cpt' => [1], 'location_treatments_cpt' => [2], 'location_expertise' => [3], 'location_clinical_resources' => [4]];
    $options = ['option' => ['mychart_scheduling_query_system' => true], 'terms' => [60 => 'Portal'], 'pages' => [1], 'queries' => [[4], [7], [1], [2], [3], [11]], 'fields_by_id' => []];
    $output = single_location_markup($fields, $options);
    preg_match_all('/<a class="nav-link" href="(#[^"]*)"[^>]*>([^<]*)<\/a>/', $output, $matches);

    expect(array_combine($matches[1], $matches[2]))->toBe([
        '#scheduling' => 'Schedule an Appointment Online',
        '#portal-info' => 'Patient Portal',
        '#providers' => 'Providers',
        '#conditions' => 'Conditions',
        '#treatments' => 'Treatments &amp; Procedures',
        '#expertise' => 'Areas of Expertise',
        '#sub-clinics' => 'Clinics Within This Location',
        '#related-resources' => 'Resources',
    ]);
});

test('the appointment section prints the text with what to bring and what to expect', function (): void {
    $output = single_location_markup(['location_appointment' => '<p>Call ahead.</p>', 'location_appointment_bring' => '<p>ID.</p>', 'location_appointment_expect' => '<p>Wait.</p>']);

    expect($output)->toContain('<section class="uams-module bg-auto" id="appointment-info">')
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Appointment Information</span></h2><div class="module-body"><p>Call ahead.</p><h3>What to Bring to Your Appointment</h3><p>ID.</p><h3>What to Expect at Your Appointment</h3><p>Wait.</p></div>');
});

test('the appointment section prints only what is filled in', function (): void {
    $text = single_location_markup(['location_appointment' => '<p>Call ahead.</p>']);
    $both = single_location_markup(['location_appointment_bring' => '<p>ID.</p>', 'location_appointment_expect' => '<p>Wait.</p>']);
    $bring = single_location_markup(['location_appointment_bring' => '<p>ID.</p>']);
    $expect = single_location_markup(['location_appointment_expect' => '<p>Wait.</p>']);

    expect($text)->toContain('<span class="title">Appointment Information</span></h2><div class="module-body"><p>Call ahead.</p></div>')
        ->and($both)->toContain('<span class="title">Appointment Information</span></h2><div class="module-body"><h3>What to Bring to Your Appointment</h3><p>ID.</p><h3>What to Expect at Your Appointment</h3><p>Wait.</p></div>')
        ->and($bring)->toContain('<span class="title">What to Bring to Your Appointment</span></h2><div class="module-body"><p>ID.</p></div>')
        ->and($expect)->toContain('<span class="title">What to Expect at Your Appointment</span></h2><div class="module-body"><p>Wait.</p></div>');
});

test('the appointment text can come with only one of the other two', function (): void {
    $bring = single_location_markup(['location_appointment' => '<p>Call.</p>', 'location_appointment_bring' => '<p>ID.</p>']);
    $expect = single_location_markup(['location_appointment' => '<p>Call.</p>', 'location_appointment_expect' => '<p>Wait.</p>']);

    expect($bring)->toContain('<p>Call.</p><h3>What to Bring to Your Appointment</h3><p>ID.</p></div>')
        ->and($bring)->not->toContain('What to Expect')
        ->and($expect)->toContain('<p>Call.</p><h3>What to Expect at Your Appointment</h3><p>Wait.</p></div>')
        ->and($expect)->not->toContain('What to Bring');
});

test('one scheduling widget embeds the MyChart frame', function (): void {
    $fields = ['location_scheduling_query' => true, 'location_scheduling_options' => [['location_scheduling_ser' => 'S1', 'location_scheduling_dep' => 'D2', 'location_scheduling_vt' => 'V3', 'location_scheduling_fallback' => '<p>Or call.</p>']]];
    $option = ['option' => ['mychart_scheduling_query_system' => true, 'mychart_scheduling_domain' => 'chart.test', 'mychart_scheduling_instance' => 'prod']];
    $output = single_location_markup($fields, $option);

    expect($output)->toContain('<section class="uams-module mychart-scheduling-module bg-auto" id="scheduling">')
        ->and($output)->toContain('<h2 class="module-title"><span class="title">Schedule an Appointment Online</span></h2><p class="note">Use your UAMS Health MyChart account')
        ->and($output)->toContain('<iframe id="openSchedulingFrame" title="MyChart Scheduling" class="widgetframe" scrolling="no" src="https://chart.test/prod/SignupAndSchedule/EmbeddedSchedule?id=S1&dept=D2&vt=V3&linksource=uamshealth.com"></iframe>')
        ->and($output)->toContain('<script src="https://chart.test/prod/Content/EmbeddedWidgetController.js" type="text/javascript"></script>')
        ->and($output)->toContain("'hostname': \"https:\\/\\/chart.test\",")
        ->and($output)->toContain('<div class="more"><p>Or call.</p></div>')
        ->and($output)->not->toContain('mychart-scheduling-select');
});

test('a scheduling widget with no fallback text prints no extra block', function (): void {
    $fields = ['location_scheduling_query' => true, 'location_scheduling_options' => [['location_scheduling_ser' => 'S1']]];
    $output = single_location_markup($fields, ['option' => ['mychart_scheduling_query_system' => true]]);

    expect($output)->toContain('id="openSchedulingFrame"')
        ->and($output)->not->toContain('<div class="more">');
});

test('several scheduling widgets let the patient pick a service', function (): void {
    $fields = ['location_scheduling_query' => true, 'location_scheduling_options' => [['location_scheduling_item_title_nested' => 'Primary care'], ['location_scheduling_item_title_nested' => '']]];
    $output = single_location_markup($fields, ['option' => ['mychart_scheduling_query_system' => true]]);

    expect($output)->toContain('<form action="" method="get" class="mychart-scheduling-select">')
        ->and($output)->toContain('<option value="">Select an option</option><option value="0">Primary care</option><option value="1">Schedule an Appointment Online</option></select><input type="hidden" id="pid" name="pid" value="')
        ->and($output)->toContain('<div class="mychart-scheduling"></div>')
        ->and($output)->not->toContain('openSchedulingFrame');
});

test('a closing location says telemedicine is not available', function (): void {
    $group = ['location_hours_group' => ['location_telemed_query' => true]];
    $closed = single_location_markup($group + ['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2020']);
    $closing = single_location_markup($group + ['location_closing' => true, 'location_closing_length' => 'permanent', 'location_closing_date' => 'January 1, 2099']);
    $temporary = single_location_markup($group + ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020', 'location_reopen_date' => 'January 1, 2099']);
    $future = single_location_markup($group + ['location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2099', 'location_reopen_date' => 'January 2, 2099']);

    expect($closed)->toContain('<p class="text-center"><strong>Telemedicine is not available while this location is permanently closed.</strong></p>')
        ->and($closing)->toContain('Telemedicine will not be available after this location closes permanently on January 1, 2099.')
        ->and($temporary)->toContain('Telemedicine is not available while this location is temporarily closed.')
        ->and($future)->toContain('after this location closes temporarily beginning on January 1, 2099.');
});

test('a closing location that keeps telemedicine prints the telemedicine details', function (): void {
    $fields = ['location_hours_group' => ['location_telemed_query' => true], 'location_closing' => true, 'location_closing_length' => 'temporary', 'location_closing_date' => 'January 1, 2020', 'location_reopen_date' => 'January 1, 2099', 'location_closing_telemed' => true];

    expect(single_location_markup($fields))->toContain('<div class="row content-split-lg">')
        ->and(single_location_markup($fields))->not->toContain('Telemedicine is not available');
});

test('the telemedicine section prints the system text and who can use the service', function (string $patients, string $sentence): void {
    $fields = ['location_hours_group' => ['location_telemed_query' => true, 'location_telemed_patients' => $patients]];
    $output = single_location_markup($fields, ['option' => ['location_telemed_descr_system' => '<p>About telemedicine.</p>']]);

    expect($output)->toContain('<section class="uams-module bg-auto" aria-label="Telemedicine Information" id="telemedicine-info">')
        ->and($output)->toContain('<span class="title">Telemedicine Information</span></h2><div class="row content-split-lg"><div class="col-xs-12 col-lg-7"><div class="content-width"><p>About telemedicine.</p><p>'.$sentence)
        ->and($output)->toContain('Patients should call<a href="tel:');
})->with([
    'all patients' => ['all', 'This service is available to both new and existing patients.'],
    'new patients' => ['new', 'This service is available to new patients only.'],
    'existing patients' => ['existing', 'This service is available to existing patients only.'],
]);

test('the telemedicine section names the phone number to call', function (): void {
    $group = ['location_telemed_query' => true, 'location_telemed_patients' => 'all'];
    $general = single_location_markup(['location_hours_group' => $group, 'location_phone' => '501-1']);
    $one = single_location_markup(['location_hours_group' => $group, 'location_phone' => '501-1', 'location_clinic_phone_query' => true, 'location_new_appointments_phone' => '501-2']);
    $two = single_location_markup(['location_hours_group' => $group, 'location_phone' => '501-1', 'location_clinic_phone_query' => true, 'field_location_appointment_phone_query' => true, 'location_new_appointments_phone' => '501-2', 'location_return_appointments_phone' => '501-3']);

    expect($general)->toContain('Patients should call<a href="tel:dash:501-1"')
        ->and($one)->toContain('Patients should call<a href="tel:dash:501-2"')
        ->and($two)->toContain('New patients should call<a href="tel:dash:501-2"')
        ->and($two)->toContain('while existing patients should call<a href="tel:dash:501-3"');
});

test('with two appointment numbers each kind of patient gets its own number', function (): void {
    $fields = ['location_clinic_phone_query' => true, 'field_location_appointment_phone_query' => true, 'location_new_appointments_phone' => '501-2', 'location_return_appointments_phone' => '501-3'];
    $new = single_location_markup($fields + ['location_hours_group' => ['location_telemed_query' => true, 'location_telemed_patients' => 'new']]);
    $existing = single_location_markup($fields + ['location_hours_group' => ['location_telemed_query' => true, 'location_telemed_patients' => 'existing']]);

    expect($new)->toContain('Patients should call<a href="tel:dash:501-2"')
        ->and($new)->not->toContain('existing patients should call')
        ->and($existing)->toContain('Patients should call<a href="tel:dash:501-3"');
});

test('the telemedicine section prints typical telemedicine hours', function (): void {
    $hours = [single_location_day('Mon - Fri', '08:00:00', '17:00:00', false, 'Online'), single_location_day('Sat', '09:00:00', '12:00:00'), single_location_day('Sun', '00:00:00', '00:00:00', true)];
    $output = single_location_markup(['location_hours_group' => ['location_telemed_query' => true, 'location_telemed_hours' => $hours]]);

    expect($output)->toContain('<h3>Hours</h3><dl class="hours"><dt>Mon - Fri</dt><dd>8 a.m. &ndash; 5 p.m.<br /><span class="subtitle">Online</span></dd><dt>Sat</dt><dd>9 a.m. &ndash; Noon</dd><dt>Sun</dt><dd>Closed</dd></dl>');
});

test('telemedicine hours open around the clock print as 24/7', function (): void {
    $output = single_location_markup(['location_hours_group' => ['location_telemed_query' => true, 'location_telemed_24_7' => true]]);

    expect($output)->toContain('<h3>Hours</h3><strong>Open 24/7</strong>');
});

test('a telemedicine section with no hours prints none', function (): void {
    expect(single_location_markup(['location_hours_group' => ['location_telemed_query' => true]]))->not->toContain('<h3>Hours</h3>');
});

/**
 * An hours group for telemedicine with modified hours that started yesterday.
 *
 * @param  list<array<string, mixed>>  $times  The rows of modified times.
 * @param  array<string, mixed>  $group  More values of the hours group.
 * @return array<string, mixed>
 */
function single_location_telemed_modified(array $times, array $group = []): array
{
    return ['location_hours_group' => $group + [
        'location_telemed_query' => true,
        'location_telemed_hours' => [single_location_day()],
        'location_telemed_modified_hours_query' => true,
        'location_telemed_modified_hours_reason' => '<p>Maintenance.</p>',
        'location_telemed_modified_hours_start_date' => date('F j, Y', strtotime('-1 day')),
        'location_telemed_modified_hours_end' => true,
        'location_telemed_modified_hours_end_date' => date('F j, Y', strtotime('+10 days')),
        'location_telemed_modified_hours_times' => $times,
    ]];
}

/**
 * One row of modified telemedicine times.
 *
 * @return array<string, mixed>
 */
function single_location_telemed_time(string $day = 'Mon', string $open = '10:00:00', string $close = '14:00:00', bool $closed = false, string $comment = ''): array
{
    return ['location_telemed_modified_hours_day' => $day, 'location_telemed_modified_hours_open' => $open, 'location_telemed_modified_hours_close' => $close, 'location_telemed_modified_hours_closed' => $closed, 'location_telemed_modified_hours_comment' => $comment];
}

test('modified telemedicine hours print the reason, the dates and the times', function (): void {
    $fields = single_location_telemed_modified([single_location_telemed_time('Mon', '10:00:00', '14:00:00', false, 'Phone only'), single_location_telemed_time('Tue', '00:00:00', '00:00:00', true)]);
    $output = single_location_markup($fields);
    $start = $fields['location_hours_group']['location_telemed_modified_hours_start_date'];
    $end = $fields['location_hours_group']['location_telemed_modified_hours_end_date'];

    expect($output)->toContain('<h3>Modified Hours</h3><p>Maintenance.</p><p class="small font-italic">These modified hours start on '.$start.', and are scheduled to end after '.$end.'.</p><dl class="hours"><dt>Mon</dt><dd>10 a.m. &ndash; 2 p.m.<br /><span class="subtitle">Phone only</span></dd><dt>Tue</dt><dd>Closed</dd></dl>')
        ->and($output)->toContain('<h3>Typical Hours</h3>');
});

test('modified telemedicine hours with no end date stay in effect until further notice', function (): void {
    $output = single_location_markup(single_location_telemed_modified([single_location_telemed_time()], ['location_telemed_modified_hours_end' => false, 'location_telemed_modified_hours_end_date' => '']));

    expect($output)->toContain('and will remain in effect until further notice.</p>')
        ->and($output)->not->toContain('Typical Hours')
        ->and($output)->not->toContain('<h3>Hours</h3>');
});

test('modified telemedicine hours open around the clock print as 24/7', function (): void {
    $output = single_location_markup(single_location_telemed_modified([], ['location_telemed_modified_hours_24_7' => true]));

    expect($output)->toContain('<h3>Modified Hours</h3><p>Maintenance.</p>')
        ->and($output)->toContain('<strong>Open 24/7</strong>');
});

test('modified telemedicine hours that start more than thirty days away are not printed', function (): void {
    $output = single_location_markup(single_location_telemed_modified([single_location_telemed_time()], ['location_telemed_modified_hours_start_date' => date('F j, Y', strtotime('+60 days')), 'location_telemed_modified_hours_end_date' => date('F j, Y', strtotime('+70 days'))]));

    expect($output)->not->toContain('<h3>Modified Hours</h3>')
        ->and($output)->toContain('<h3>Hours</h3>');
});

test('the portal section prints the text and a login button', function (): void {
    $fields = ['location_portal' => 60, 'portal_content' => '<p>Manage your care.</p>', 'portal_url' => ['url' => 'https://portal.test/', 'title' => 'Open the portal']];
    $output = single_location_markup($fields, ['terms' => [60 => 'MyChart']]);

    expect($output)->toContain('<section class="uams-module cta-bar cta-bar-weighted bg-blue" aria-label="Patient Portal" id="portal-info">')
        ->and($output)->toContain('<div class="cta-heading"><h2>MyChart</h2></div><div class="cta-body"><div class="text-container"><p>Manage your care.</p></div><div class="btn-container"><a href="https://portal.test/" aria-label="Log in to MyChart to view your patient information and medical records" class="btn btn-white" target="_blank" data-moduletitle="MyChart">Open the portal</a></div></div>');
});

test('the portal button falls back to a title made from the portal name', function (): void {
    $fields = ['location_portal' => 60, 'portal_content' => '<p>Manage.</p>', 'portal_url' => ['url' => 'https://portal.test/', 'title' => '']];

    expect(single_location_markup($fields, ['terms' => [60 => 'MyChart']]))->toContain('>Log in to MyChart</a>');
});

test('a portal with a link but no text prints the button', function (): void {
    $fields = ['location_portal' => 60, 'portal_url' => ['url' => 'https://portal.test/', 'title' => 'Open']];
    $output = single_location_markup($fields, ['terms' => [60 => 'MyChart']]);

    expect($output)->toContain('<div class="cta-heading"><h2>MyChart</h2></div><div class="cta-body"><div class="btn-container">')
        ->and($output)->toContain('href="https://portal.test/"')
        ->and($output)->toContain('btn-white')
        ->and($output)->not->toContain('text-container');
});

test('a portal with neither text nor a link prints only its heading', function (): void {
    $output = single_location_markup(['location_portal' => 60], ['terms' => [60 => 'MyChart']]);

    expect($output)->toContain('<div class="cta-heading"><h2>MyChart</h2></div></div>')
        ->and($output)->not->toContain('cta-body');
});

test('the address names the building, floor and suite', function (): void {
    $fields = ['location_address_1' => '1 Main St', 'location_building' => 70, 'location_suite' => 'Suite 5', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205'];
    $objects = ['location_building_floor' => ['value' => '2', 'choices' => ['2' => 'Second Floor']]];
    $output = single_location_markup($fields, ['terms' => [70 => 'Tower'], 'objects' => $objects]);

    expect($output)->toContain('<p>1 Main St<br/>Tower<br />Second Floor, Suite 5<br/>Little Rock, AR 72205</p>');
});

test('a child location can use its own featured image for the schema', function (): void {
    $fields = ['location_parent' => true, 'location_parent_id' => 90, 'location_image_override_parent' => true, 'location_image_override_parent_featured' => true, 'location_wayfinding_photo' => 501];
    $options = ['posts' => [90 => (object) ['ID' => 90, 'post_title' => 'Parent']], 'thumbnail' => 900];

    expect(single_location($fields, $options)['schema_image'])->toBe(900)
        ->and(single_location($fields, $options)['featured_image'])->toBe(900);
});

/**
 * The structured data a render printed, decoded.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 * @return array<string, mixed>
 */
function single_location_schema(array $fields = [], array $options = []): array
{
    $output = single_location($fields, $options)['single_location_output'];

    expect(preg_match("#<script type='application/ld\+json'>(.*?)</script>#s", $output, $match))->toBe(1);

    $schema = json_decode($match[1], true);

    expect(json_last_error_msg())->toBe('No error');

    return $schema;
}

test('the structured data parses for a location with typical hours', function (): void {
    $schema = single_location_schema(single_location_hours([single_location_day(), single_location_day('Sat', '09:00:00', '12:00:00')]));

    expect($schema['@type'])->toBe('MedicalClinic')
        ->and($schema['openingHours'])->toBe(['Mo-Fr 08:00-17:00', 'Sa 09:00-12:00'])
        ->and($schema)->toHaveKey('logo');
});

test('the structured data parses with telephone numbers beside the hours', function (): void {
    $fields = single_location_hours([single_location_day()]) + ['location_phone' => '501-1', 'location_clinic_phone_query' => true, 'location_new_appointments_phone' => '501-2'];
    $schema = single_location_schema($fields);

    expect($schema['telephone'])->toBe(['dash:501-1', 'dash:501-2'])
        ->and($schema['openingHours'])->toBe(['Mo-Fr 08:00-17:00']);
});

test('the structured data parses with the modified hours beside the typical hours', function (): void {
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => [single_location_modified_time('Mon - Fri', '10:00:00', '14:00:00'), single_location_modified_time('Sat', '00:00:00', '00:00:00', true)]]];
    $schema = single_location_schema(single_location_modified($groups));

    expect($schema['openingHoursSpecification'])->toHaveCount(2)
        ->and($schema['openingHoursSpecification'][0]['opens'])->toBe('10:00')
        ->and($schema['openingHours'])->toBe(['Mo-Fr 08:00-17:00']);
});

test('the structured data parses with modified hours open around the clock', function (): void {
    $groups = [['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => true, 'location_modified_hours_times' => []]];
    $schema = single_location_schema(single_location_modified($groups));

    expect($schema['openingHoursSpecification'][0]['closes'])->toBe('23:59')
        ->and($schema['openingHoursSpecification'][0]['dayOfWeek'])->toHaveCount(7);
});

test('the structured data parses without hours, with hours around the clock, and with no telephone', function (array $fields): void {
    $schema = single_location_schema($fields);

    expect($schema['@type'])->toBe('MedicalClinic');
})->with([
    'no hours' => [[]],
    'around the clock' => [single_location_hours([], ['location_24_7' => true])],
    'a general number only' => [['location_phone' => '501-1']],
    'additional numbers only' => [['field_location_phone_numbers' => [['location_appointments_text' => 'Billing', 'location_appointments_phone' => '501-6', 'location_appointments_additional_text' => '']]]],
]);

test('a location has no directions button without coordinates and no warning for it', function (): void {
    $without = single_location();
    $with = single_location(['location_map' => ['lat' => 34.7, 'lng' => -92.3]]);

    expect($without['single_location_output'])->not->toContain('Get Directions')
        ->and($without['single_location_warnings'])->toBe([])
        ->and($with['single_location_output'])->toContain('/dir/Current+Location/34.7,-92.3')
        ->and($with['single_location_warnings'])->toBe([]);
});

test('the template no longer carries the news block that could never run', function (): void {
    expect(file_get_contents(UAMS_FAD_PATH.'templates/single-locations.php'))->not->toContain('true == false');
});

test('a holiday row reads its own opening time, not the last row of typical hours', function (): void {
    $date = date('m/d/Y', strtotime('+10 days'));
    $output = single_location_markup(single_location_hours([single_location_day('Sun', '00:00:00', '00:00:00', true)]) + ['location_holiday_hours' => [
        ['date' => $date, 'label' => 'Half day', 'closed' => false, 'open' => '09:00:00', 'close' => '13:00:00'],
    ]]);

    expect($output)->toContain('<dd>9 a.m. &ndash; 1 p.m.</dd>');
});

test('a closed holiday row and one that has no times each close their description', function (): void {
    $date = date('m/d/Y', strtotime('+10 days'));
    $output = single_location_markup(single_location_hours([single_location_day()]) + ['location_holiday_hours' => [
        ['date' => $date, 'label' => 'Closed day', 'closed' => true, 'open' => '', 'close' => ''],
        ['date' => $date, 'label' => 'Unknown day', 'closed' => false, 'open' => '', 'close' => ''],
    ]]);

    expect($output)->toContain('<dt>Closed day<br />'.$date.'<br/></dt><dd>Closed</dd>')
        ->and($output)->toContain('<dt>Unknown day<br />'.$date.'<br/></dt><dd></dd>')
        ->and($output)->toContain('</dd></dl>');
});

/**
 * The prepared modified hours text and structured data for one group, over a fixed window, with the whitespace collapsed.
 *
 * @param  list<array<string, mixed>>  $times  The rows of modified hours times.
 * @param  array<string, mixed>  $row  More values of the group.
 * @param  array<string, mixed>  $group  More values of the hours group.
 * @return array{text: string, schema: string, start: mixed, end: mixed}
 */
function single_location_modified_golden(array $times, array $row = [], array $group = []): array
{
    $row += ['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => $times];
    $fields = single_location_modified([$row], $group + ['location_modified_hours_reason' => '', 'location_modified_hours_start_date' => 'January 1, 2020', 'location_modified_hours_end_date' => 'January 1, 2099']);
    $vars = single_location($fields);
    $squash = static fn (mixed $value): string => trim((string) preg_replace('/\s+/', ' ', (string) $value));

    return ['text' => $squash($vars['modified_text']), 'schema' => $squash($vars['modified_hours_schema']), 'start' => $vars['active_start'], 'end' => $vars['active_end']];
}

test('modified hours print every kind of row exactly', function (): void {
    $times = [
        single_location_modified_time('Mon - Fri', '08:00:00', '17:00:00', false, 'Lunch'),
        single_location_modified_time('Mon - Fri', '18:00:00', '20:00:00'),
        single_location_modified_time('Saturday', '', '', true),
        single_location_modified_time('Sunday', '00:00:00', '00:00:00'),
    ];
    $golden = single_location_modified_golden($times, ['location_modified_hours_title' => 'Week', 'location_modified_hours_information' => '<p>Info</p>'], ['location_modified_hours_reason' => '<p>Why</p>']);

    expect($golden['text'])->toBe('<p>Why</p><p class="small font-italic">These modified hours start on January 1, 2020, and are scheduled to end after January 1, 2099.</p><h3 class="h4">Week</h3><p>Info</p><dl class="hours"><dt>Mon - Fri</dt> <dd>8 a.m. &ndash; 5 p.m. <br /><span class="subtitle">Lunch</span></dd><dd>6 &ndash; 8 p.m.</dd><dt>Saturday</dt> <dd>Closed </dd><dt>Sunday</dt> <dd></dd></dl>')
        ->and($golden['schema'])->toBe('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday" ], "opens": "08:00", "closes": "17:00" }, { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday" ], "opens": "18:00", "closes": "20:00" }, { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": "Saturday", "opens": "00:00", "closes": "00:00" }, { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": "Sunday", "opens": "00:00", "closes": "00:00" } ],')
        ->and($golden['start'])->toBe(1577836800)
        ->and($golden['end'])->toBe(4070908800);
});

test('a closed Monday to Friday row names the single day label in the structured data', function (): void {
    $golden = single_location_modified_golden([single_location_modified_time('Mon - Fri', '', '', true)]);

    expect($golden['schema'])->toBe('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": "Mon - Fri", "opens": "00:00", "closes": "00:00" } ],');
});

test('modified hours with no end run until further notice and have no end in the structured data', function (): void {
    $golden = single_location_modified_golden([single_location_modified_time('Monday', '08:00:00', '17:00:00')], [], ['location_modified_hours_end' => false, 'location_modified_hours_end_date' => '']);

    expect($golden['text'])->toBe('<p class="small font-italic">These modified hours start on January 1, 2020, and will remain in effect until further notice.</p><dl class="hours"><dt>Monday</dt> <dd>8 a.m. &ndash; 5 p.m.</dd></dl>')
        ->and($golden['schema'])->toBe('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "dayOfWeek": "Monday", "opens": "08:00", "closes": "17:00" } ],')
        ->and($golden['end'])->toBe('TBD');
});

test('an end date that is switched on but empty is treated as no end', function (): void {
    $golden = single_location_modified_golden([single_location_modified_time('Monday', '08:00:00', '17:00:00')], [], ['location_modified_hours_end_date' => '']);

    expect($golden['text'])->toBe('')
        ->and($golden['schema'])->toBe('');
});

test('modified hours open around the clock print the group heading and the whole week', function (): void {
    $golden = single_location_modified_golden([], ['location_modified_hours_24_7' => true, 'location_modified_hours_title' => 'Always', 'location_modified_hours_information' => '<p>I</p>']);
    $open = single_location_modified_golden([], ['location_modified_hours_24_7' => true], ['location_modified_hours_end' => false, 'location_modified_hours_end_date' => '']);

    expect($golden['text'])->toBe('<p class="small font-italic">These modified hours start on January 1, 2020, and are scheduled to end after January 1, 2099.</p><h3 class="h4">Always</h3><p>I</p><strong>Open 24/7</strong>')
        ->and($golden['schema'])->toBe('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "validThrough": "2099-01-01", "dayOfWeek": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ], "opens": "00:00", "closes": "23:59" } ],')
        ->and($open['text'])->toBe('<p class="small font-italic">These modified hours start on January 1, 2020, and will remain in effect until further notice.</p><strong>Open 24/7</strong>')
        ->and($open['schema'])->toBe('"openingHoursSpecification": [ { "@type": "OpeningHoursSpecification", "validFrom": "2020-01-01", "dayOfWeek": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" ], "opens": "00:00", "closes": "23:59" } ],');
});

test('a group whose times are not a list prints only the dates', function (): void {
    $golden = single_location_modified_golden([], ['location_modified_hours_times' => 'x']);

    expect($golden['text'])->toBe('<p class="small font-italic">These modified hours start on January 1, 2020, and are scheduled to end after January 1, 2099.</p>')
        ->and($golden['schema'])->toBe('');
});

test('typical hours stay hidden only while modified hours cover today and run past thirty days', function (): void {
    $times = [single_location_modified_time('Monday', '08:00:00', '17:00:00')];
    $fields = static fn (string $start, string $end, bool $ends = true): array => single_location_modified([['location_modified_hours_title' => '', 'location_modified_hours_information' => '', 'location_modified_hours_24_7' => false, 'location_modified_hours_times' => $times]], ['location_modified_hours_start_date' => $start, 'location_modified_hours_end' => $ends, 'location_modified_hours_end_date' => $end]);
    $today = date('F j, Y');
    $typical = static fn (array $f): bool => str_contains(single_location_markup($f), 'Typical Hours');

    // Starting today with no end hides them, as does an end far away; starting tomorrow or ending soon does not.
    expect($typical($fields($today, '', false)))->toBeFalse()
        ->and($typical($fields($today, date('F j, Y', strtotime('+60 days')))))->toBeFalse()
        ->and($typical($fields(date('F j, Y', strtotime('+1 day')), '', false)))->toBeTrue()
        ->and($typical($fields($today, date('F j, Y', strtotime('+10 days')))))->toBeTrue()
        ->and($typical($fields(date('F j, Y', strtotime('-5 days')), $today)))->toBeTrue();
});
