<?php

/**
 * The output of the single-expertise callbacks in includes/class.fad-single-expertise.php that read ACF fields
 * and the page globals: the title, the call to action bars, the alternate names and the header keywords.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';
require_once __DIR__.'/../../includes/class.fad-helper-functions.php';
require_once __DIR__.'/../../includes/class.fad-single-expertise.php';

/**
 * Run a callback with the given ACF fields and return what it printed.
 *
 * @param  array<string, mixed>  $fields  The ACF fields by name.
 */
function expertise_callback_output(string $callback, array $fields = []): string
{
    when('get_field')->alias(static fn (string $name): mixed => $fields[$name] ?? null);
    when('esc_url')->returnArg();
    when('esc_attr')->returnArg();
    when('esc_html')->returnArg();

    ob_start();
    $callback();

    return (string) ob_get_clean();
}

/**
 * A call to action row as ACF returns it.
 *
 * @param  array<string, mixed>  $row  The fields that differ from the defaults.
 * @return array<string, mixed>
 */
function expertise_cta_row(array $row = []): array
{
    return $row + [
        'cta_bar_heading' => 'Get Help',
        'cta_bar_body' => '<p>Body.</p>',
        'cta_bar_action_type' => 'none',
        'cta_bar_button_text' => 'Go',
        'cta_bar_button_url' => ['url' => 'https://example.test/go/', 'target' => ''],
        'cta_bar_button_description' => 'Go there',
        'cta_bar_phone_prepend' => '',
        'cta_bar_phone' => '5015551234',
    ];
}

test('the title shows the single name, the page title and the parent area', function (): void {
    $GLOBALS['page_title'] = 'Eye Care';
    $GLOBALS['expertise_single_name'] = 'Area of Expertise';
    $GLOBALS['parent_expertise'] = true;
    $GLOBALS['parent_title'] = 'Medicine';
    $GLOBALS['parent_title_attr'] = 'Medicine';
    $GLOBALS['parent_url'] = 'https://example.test/medicine/';

    $output = expertise_callback_output('uamswp_expertise_post_title');

    expect($output)->toBe('<h1 class="entry-title" itemprop="headline"><span class="supertitle">Area of Expertise</span><span class="sr-only">:</span> Eye Care<span class="subtitle"><span class="sr-only">(</span>Part of <a href="https://example.test/medicine/" aria-label="Go to Area of Expertise page for Medicine" data-categorytitle="Parent Name">Medicine</a><span class="sr-only">)</span></span></h1>');
});

/**
 * The markup of one call to action section around the given action markup.
 */
function expertise_cta_section(string $classes, int $number, string $heading, string $action): string
{
    return '<section class="uams-module cta-bar'.$classes.'" id="cta-bar-'.$number.'" aria-label="'.$heading.'">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="inner-container">
                                <div class="cta-heading">
                                    <h2>'.$heading.'</h2>
                                </div>
                                <div class="cta-body">
                                    <div class="text-container">
                                        <p>Body.</p>
                                    </div>'.$action.'</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';
}

test('a call to action bar with no link has the no-link class and no button', function (): void {
    $output = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row()]]);

    expect($output)->toBe(expertise_cta_section(' cta-bar-centered bg-auto no-link', 1, 'Get Help', ''));
});

test('a call to action bar with a link has a button that opens where the link says', function (): void {
    $row = expertise_cta_row(['cta_bar_action_type' => 'url', 'cta_bar_button_url' => ['url' => 'https://example.test/go/', 'target' => '_blank']]);
    $output = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [$row]]);
    $button = '<div class="btn-container">
                                        <a href="https://example.test/go/" aria-label="Go there" class=" btn btn-primary" target="_blank" data-moduletitle="Get Help">Go</a>
                                    </div>';

    expect($output)->toBe(expertise_cta_section(' cta-bar-centered bg-auto', 1, 'Get Help', $button));
});

test('a call to action link with no target has no target attribute, and an empty link field gives an empty address', function (): void {
    $with_url = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row(['cta_bar_action_type' => 'url'])]]);
    $without_url = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row(['cta_bar_action_type' => 'url', 'cta_bar_button_url' => null])]]);

    expect($with_url)->not->toContain('target=')
        ->and($with_url)->toContain('<a href="https://example.test/go/" aria-label="Go there" class=" btn btn-primary" data-moduletitle="Get Help">Go</a>')
        ->and($without_url)->toContain('<a href="" aria-label="Go there" class=" btn btn-primary" data-moduletitle="Get Help">Go</a>');
});

test('a call to action bar with a phone number has a call link that says Call by default', function (): void {
    $dialed = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row(['cta_bar_action_type' => 'phone', 'cta_bar_phone_prepend' => 'Dial'])]]);
    $default = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row(['cta_bar_action_type' => 'phone'])]]);
    $call = static fn (string $words): string => '<div class="btn-container">
                                        <a href="tel:5015551234" data-moduletitle="Get Help">'.$words.' <span class="no-break">5015551234</span></a>
                                    </div>';

    expect($dialed)->toBe(expertise_cta_section(' cta-bar-centered bg-auto', 1, 'Get Help', $call('Dial')))
        ->and($default)->toBe(expertise_cta_section(' cta-bar-centered bg-auto', 1, 'Get Help', $call('Call')));
});

test('each call to action bar is numbered in turn and nothing prints without any', function (): void {
    $output = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [expertise_cta_row(), expertise_cta_row(['cta_bar_heading' => 'More'])]]);

    expect($output)->toContain('id="cta-bar-1" aria-label="Get Help"')
        ->and($output)->toContain('id="cta-bar-2" aria-label="More"')
        ->and(expertise_callback_output('uamswp_expertise_cta'))->toBe('');
});

test('a call to action row with no fields prints an empty bar, and a link or phone bar with none prints empty values', function (): void {
    $empty = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [[]]]);
    $link = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [['cta_bar_action_type' => 'url']]]);
    $phone = expertise_callback_output('uamswp_expertise_cta', ['expertise_cta' => [['cta_bar_action_type' => 'phone']]]);

    expect($empty)->toBe(str_replace('<p>Body.</p>', '', expertise_cta_section(' cta-bar-centered bg-auto', 1, '', '')))
        ->and($link)->toContain('<a href="" aria-label="" class=" btn btn-primary" data-moduletitle=""></a>')
        ->and($phone)->toContain('<a href="tel:" data-moduletitle="">Call <span class="no-break"></span></a>');
});

test('the alternate names are listed after Also called, joined by semicolons', function (): void {
    $output = expertise_callback_output('uamswp_expertise_keywords', ['expertise_alternate_names' => [['text' => 'Ophthalmology'], ['text' => 'Vision']]]);

    expect($output)->toBe('<p class="text-callout text-callout-info">Also called: Ophthalmology; Vision</p>')
        ->and(expertise_callback_output('uamswp_expertise_keywords', ['expertise_alternate_names' => [[]]]))->toBe('<p class="text-callout text-callout-info">Also called: </p>')
        ->and(expertise_callback_output('uamswp_expertise_keywords'))->toBe('');
});

test('the header keywords drop commas from each alternate name and join them with commas', function (): void {
    $output = expertise_callback_output('uamswp_expertise_header_metadata', ['expertise_alternate_names' => [['text' => 'Eye, ear'], ['text' => 'Vision']]]);

    expect($output)->toBe('<meta name="keywords" content="Eye ear, Vision" />')
        ->and(expertise_callback_output('uamswp_expertise_header_metadata', ['expertise_alternate_names' => [[]]]))->toBe('<meta name="keywords" content="" />')
        ->and(expertise_callback_output('uamswp_expertise_header_metadata'))->toBe('');
});
