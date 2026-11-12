<?php

/**
 * The block partials under templates/blocks that print a module for a post: the provider appointment block and
 * the clinical resources block.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';
require_once __DIR__.'/../Support/acf-blocks.php';

/**
 * Stub the functions the provider appointment block reads.
 *
 * @param  array<string, mixed>|null  $portal  The portal term fields (`slug`, `name`, `url`), or null for none.
 */
function block_partials_appointment_stubs(bool $mainLine, ?array $portal): void
{
    uamswp_test_acf_blocks_file();

    when('esc_url')->returnArg();
    when('esc_attr')->returnArg();
    when('get_term')->alias(static fn (): object => (object) ['slug' => $portal['slug'] ?? '', 'name' => $portal['name'] ?? '']);
    when('get_field')->alias(static function (string $name) use ($mainLine, $portal): mixed {
        return match ($name) {
            'provider_mainline' => $mainLine,
            'portal_url' => isset($portal['url']) ? ['url' => $portal['url'], 'title' => 'Portal'] : null,
            default => null,
        };
    });
}

/**
 * Render the provider appointment block and collapse its whitespace.
 *
 * Without a portal the template never sets the attribute-safe name of the appointment line and warns when it
 * reads it, so the warnings are kept for the test to assert (see block_partials_warnings()).
 *
 * @param  array<string, mixed>  $variables  Overrides of the variables the profile template sets.
 */
function block_partials_appointment(array $variables): string
{
    $output = '';
    $warnings = uamswp_test_capture_warnings(static function () use (&$output, $variables): void {
        $output = uamswp_test_include_partial('blocks/appointment-provider', $variables + [
            'physician_portal' => 0,
            'location_valid' => true,
            'location_count' => 2,
            'primary_appointment_url' => 'https://example.test/clinic/',
            'primary_appointment_title' => 'Clinic',
            'appointment_block_instance' => 1,
            'short_name' => 'Dr. Smith',
            'refer_req' => false,
            'accept_new' => true,
        ]);
    });
    block_partials_warnings($warnings);

    return trim((string) preg_replace('/\s+/', ' ', $output));
}

/**
 * Keep, or return, the warnings of the last render.
 *
 * @param  array<int, string>|null  $set  The warnings to keep, or null to read them.
 * @return array<int, string>
 */
function block_partials_warnings(?array $set = null): array
{
    static $kept = [];

    if ($set !== null) {
        $kept = $set;
    }

    return $kept;
}

test('the provider appointment block names the provider and the module instance', function (): void {
    block_partials_appointment_stubs(false, null);

    $output = block_partials_appointment(['appointment_block_instance' => 3]);

    expect($output)->toContain('id="appointment-info-3"')
        ->and($output)->toContain('<h2>Make an Appointment With Dr. Smith</h2>');
});

test('the provider appointment block words each combination of location, referral, new patients and portal', function (bool $locationValid, bool $referral, bool $acceptNew, bool $portal, bool $mainLine, string $expected): void {
    block_partials_appointment_stubs($mainLine, $portal ? ['slug' => 'mychart', 'name' => 'MyChart', 'url' => 'https://portal.example.test/'] : null);

    $output = block_partials_appointment([
        'physician_portal' => $portal ? 5 : 0,
        'location_valid' => $locationValid,
        'refer_req' => $referral,
        'accept_new' => $acceptNew,
    ]);

    $text = trim((string) preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($output), ENT_QUOTES, 'UTF-8')));
    $text = str_replace("\u{00A0}", ' ', $text);

    expect($text)->toContain($expected)
        ->and(block_partials_warnings())->toBe($portal ? [] : ['Undefined variable $appointment_phone_name_attr']);
})->with([
    'referral, new patients, portal, main line' => [true, true, true, true, true, 'Appointments for new patients are by referral only. Existing patients can make an appointment by requesting an appointment online through MyChart, by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000.'],
    'referral, new patients, portal, no main line' => [true, true, true, true, false, 'Existing patients can make an appointment by requesting an appointment online through MyChart or by contacting the clinic directly.'],
    'referral, new patients, no valid location' => [false, true, true, false, false, 'Appointments for new patients are by referral only. Existing patients can make an appointment by calling the UAMS Health appointment line at (501) 686-8000.'],
    'referral, new patients, no portal, main line' => [true, true, true, false, true, 'Existing patients can make an appointment by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000.'],
    'referral, new patients, no portal, no main line' => [true, true, true, false, false, 'Existing patients can make an appointment by contacting the clinic directly.'],
    'no referral, new patients, portal, main line' => [true, false, true, true, true, 'New patients can make an appointment by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000. Existing patients also have the option of requesting an appointment online through MyChart.'],
    'no referral, new patients, portal, no main line' => [true, false, true, true, false, 'New patients can make an appointment by contacting the clinic directly. Existing patients also have the option of requesting an appointment online through MyChart.'],
    'no referral, new patients, no valid location' => [false, false, true, false, false, 'New and existing patients can make an appointment by calling the UAMS Health appointment line at (501) 686-8000.'],
    'no referral, new patients, no portal, main line' => [true, false, true, false, true, 'New and existing patients can make an appointment by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000.'],
    'no referral, new patients, no portal, no main line' => [true, false, true, false, false, 'New and existing patients can make an appointment by contacting the clinic directly.'],
    'not accepting, portal, main line' => [true, false, false, true, true, 'This provider is not currently accepting new patients. Existing patients can make an appointment by requesting an appointment online through MyChart, by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000.'],
    'not accepting, portal, no main line' => [true, false, false, true, false, 'Existing patients can make an appointment by requesting an appointment online through MyChart or by contacting the clinic directly.'],
    'not accepting, no valid location' => [false, false, false, false, false, 'This provider is not currently accepting new patients. Existing patients can make an appointment by calling the UAMS Health appointment line at (501) 686-8000.'],
    'not accepting, no portal, main line' => [true, false, false, false, true, 'This provider is not currently accepting new patients. Existing patients can make an appointment by contacting the clinic directly or by calling the UAMS Health appointment line at (501) 686-8000.'],
    'not accepting, no portal, no main line' => [true, false, false, false, false, 'This provider is not currently accepting new patients. Existing patients can make an appointment by contacting the clinic directly.'],
]);

test('the provider appointment block links to the one location, or to the list of locations', function (): void {
    block_partials_appointment_stubs(false, null);

    $single = block_partials_appointment(['location_count' => 1]);

    expect($single)->toContain('<a href="https://example.test/clinic/" data-categorytitle="Make an Appointment | Block 1" data-typetitle="Contact the Clinic Directly | Direct Link | Clinic">');

    $several = block_partials_appointment(['location_count' => 2]);

    expect($several)->toContain('<a href="#locations" data-categorytitle="Make an Appointment | Block 1" data-typetitle="Contact the Clinic Directly | Anchor Link">');
});

test('the provider appointment block offers the portal with a tracking label and not without a valid location', function (): void {
    block_partials_appointment_stubs(false, ['slug' => 'mychart', 'name' => 'My "Chart"', 'url' => 'https://portal.example.test/']);

    $output = block_partials_appointment(['physician_portal' => 5]);

    expect($output)->toContain('<a href="https://portal.example.test/" target="_blank" data-categorytitle="Make an Appointment | Block 1" data-typetitle="Request an Appointment Online | My \'Chart\'">requesting an appointment online</a> through My "Chart"');

    $noLocation = block_partials_appointment(['physician_portal' => 5, 'location_valid' => false]);

    expect($noLocation)->not->toContain('portal.example.test');
});

test('the provider appointment block does not offer a portal that has no link or is the none portal', function (): void {
    block_partials_appointment_stubs(false, ['slug' => 'mychart', 'name' => 'MyChart']);

    expect(block_partials_appointment(['physician_portal' => 5]))->not->toContain('requesting an appointment online');

    block_partials_appointment_stubs(false, ['slug' => '_none', 'name' => 'None', 'url' => 'https://portal.example.test/']);

    expect(block_partials_appointment(['physician_portal' => 5]))->not->toContain('portal.example.test');
});

test('the provider appointment block names the hospital line for the portals of the children and the veterans system', function (string $slug, string $name, string $tel, string $text): void {
    block_partials_appointment_stubs(true, ['slug' => $slug, 'name' => 'Portal', 'url' => 'https://portal.example.test/']);

    $output = block_partials_appointment(['physician_portal' => 5]);

    expect($output)->toContain('by calling '.$name.' at <a href="tel:'.$tel.'"')
        ->and($output)->toContain('>'.$text.'</a>');
})->with([
    'children' => ['ach-mychart', "the main Arkansas Children's Hospital appointment line", '501-364-4000', '(501) 364-4000'],
    'veterans' => ['my-healthevet', 'the main Central Arkansas Veterans Healthcare System appointment line', '501-257-3999', '(501) 257-3999'],
]);

test('the provider appointment block labels the phone link with the line name for tracking', function (): void {
    block_partials_appointment_stubs(true, ['slug' => 'ach-mychart', 'name' => 'Portal', 'url' => 'https://portal.example.test/']);

    $output = block_partials_appointment(['physician_portal' => 5]);

    expect($output)->toContain('class="no-break" data-categorytitle="Make an Appointment | Block 1" data-typetitle="Main Appointment Line | the main Arkansas Children\'s Hospital appointment line"');
});
