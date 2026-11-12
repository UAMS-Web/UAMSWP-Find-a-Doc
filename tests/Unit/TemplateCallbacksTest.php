<?php

/**
 * The callbacks of the single-expertise and single-clinical-resource templates now live in includes/. The
 * templates still register them, on the hooks and priorities they used before the move, and declare no
 * function themselves.
 */

declare(strict_types=1);

require_once __DIR__.'/../../includes/class.fad-single-expertise.php';
require_once __DIR__.'/../../includes/class.fad-single-clinical-resource.php';

/**
 * The registrations each template makes with one of its own callbacks: hook, function, priority.
 *
 * @return array<string, list<array{string, string, int|null}>>
 */
function template_callback_registrations(): array
{
    return [
        'single-expertise' => [
            ['genesis_entry_header', 'uamswp_expertise_post_title', null],
            ['genesis_entry_content', 'uamswp_expertise_keywords', 8],
            ['genesis_entry_content', 'uamswp_expertise_youtube', 12],
            ['genesis_after_entry', 'uamswp_expertise_cta', 6],
            ['genesis_after_entry', 'uamswp_expertise_jump_links', 8],
            ['genesis_after_entry', 'uamswp_expertise_podcast', 10],
            ['genesis_after_entry', 'uamswp_list_child_expertise', 12],
            ['genesis_after_entry', 'uamswp_expertise_resource', 14],
            ['genesis_after_entry', 'uamswp_expertise_conditions_cpt', 16],
            ['genesis_after_entry', 'uamswp_expertise_treatments_cpt', 18],
            ['genesis_after_entry', 'uamswp_expertise_physicians', 20],
            ['genesis_after_entry', 'uamswp_expertise_locations', 22],
            ['genesis_after_entry', 'uamswp_expertise_associated', 24],
            ['genesis_after_entry', 'uamswp_expertise_appointment', 26],
            ['wp_head', 'uamswp_expertise_header_metadata', null],
        ],
        'single-clinical-resource' => [
            ['seopress_titles_canonical', 'uamswp_fad_canonical', null],
            ['genesis_entry_header', 'uamswp_resource_post_title', null],
            ['genesis_entry_content', 'uamswp_resource_text', 8],
            ['genesis_entry_content', 'uamswp_resource_provider_spotlight', 9],
            ['genesis_entry_content', 'uamswp_resource_infographic', 10],
            ['genesis_entry_content', 'uamswp_resource_video', 12],
            ['genesis_entry_content', 'uamswp_resource_document', 14],
            ['genesis_after_entry', 'uamswp_resource_jump_links', 8],
            ['genesis_after_entry', 'uamswp_resource_associated', 10],
            ['genesis_after_entry', 'uamswp_resource_conditions_cpt', 12],
            ['genesis_after_entry', 'uamswp_resource_treatments_cpt', 14],
            ['genesis_after_entry', 'uamswp_resource_physicians', 16],
            ['genesis_after_entry', 'uamswp_resource_locations', 18],
            ['genesis_after_entry', 'uamswp_resource_expertise', 20],
            ['genesis_after_entry', 'uamswp_resource_appointment', 22],
        ],
    ];
}

/**
 * The add_action and add_filter calls in a template that name a callback by string, as hook, function and priority.
 *
 * @return list<array{string, string, int|null}>
 */
function template_string_registrations(string $name): array
{
    $source = (string) file_get_contents(dirname(__DIR__, 2).'/templates/'.$name.'.php');
    preg_match_all('/^add_(?:action|filter)\(\'(\w+)\', \'(\w+)\'(?:, (\d+))?\);/m', $source, $matches, PREG_SET_ORDER);

    $registrations = [];

    foreach ($matches as $match) {
        $registrations[] = [$match[1], $match[2], isset($match[3]) ? (int) $match[3] : null];
    }

    return $registrations;
}

test('a template registers each of its callbacks on the hook and priority it used before the move', function (string $template): void {
    $own = array_values(array_filter(
        template_string_registrations($template),
        static fn (array $registration): bool => str_starts_with($registration[1], 'uamswp_') && ! str_starts_with($registration[1], 'uamswp_add_') && ! str_starts_with($registration[1], 'uamswp_fad_title_'),
    ));

    expect($own)->toBe(template_callback_registrations()[$template]);
})->with(['single-expertise', 'single-clinical-resource']);

test('every callback a template registers is declared in includes', function (string $template): void {
    foreach (template_callback_registrations()[$template] as [, $function]) {
        expect(function_exists($function))->toBeTrue($function.' is declared');
    }
})->with(['single-expertise', 'single-clinical-resource']);

test('these templates declare no function', function (string $template): void {
    $source = (string) file_get_contents(dirname(__DIR__, 2).'/templates/'.$template.'.php');

    expect(preg_match('/^\s*function\s+\w+\s*\(/m', $source))->toBe(0);
})->with(['single-expertise', 'single-clinical-resource']);

test('the callback files declare only the registered callbacks', function (string $template, string $file): void {
    preg_match_all('/^function (\w+)\(/m', (string) file_get_contents(dirname(__DIR__, 2).'/includes/'.$file), $matches);
    $declared = $matches[1];
    $registered = array_column(template_callback_registrations()[$template], 1);

    sort($declared);
    sort($registered);

    expect($declared)->toBe($registered);
})->with([
    ['single-expertise', 'class.fad-single-expertise.php'],
    ['single-clinical-resource', 'class.fad-single-clinical-resource.php'],
]);
