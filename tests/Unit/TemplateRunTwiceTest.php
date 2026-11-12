<?php

/**
 * A rendering template can run more than once in a process. Its functions used to be declared in the template
 * file, so the second run died with "Cannot redeclare"; they now live in includes/.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/templates.php';

test('the clinical resources archive renders twice in one process with the same output', function (): void {
    when('get_header')->justReturn(null);
    when('get_footer')->justReturn(null);
    when('get_field')->alias(static fn (string $field): string => $field === 'clinical_resource_archive_headline' ? 'Resource Library' : '');
    when('do_shortcode')->justReturn('[search]');
    when('facetwp_display')->alias(static fn (string $type, string $name = ''): string => '{'.$type.':'.$name.'}');

    $first = uamswp_test_render_template('archive-clinical-resource');
    $second = uamswp_test_render_template('archive-clinical-resource');

    expect($first)->toContain('Resource Library')
        ->and($first)->toContain('{template:clinical_resources}')
        ->and($second)->toBe($first);
});

test('the single clinical resource template runs twice, and its moved callbacks read what it set', function (): void {
    require_once __DIR__.'/../../includes/class.fad-single-clinical-resource.php';

    when('get_the_ID')->justReturn(7);
    when('get_the_title')->justReturn('Flu "Facts"');
    when('get_post_status')->justReturn('publish');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));
    when('get_field')->alias(static fn (string $field): mixed => match ($field) {
        'clinical_resource_type' => ['value' => 'article', 'label' => 'Article'],
        'clinical_resource_archive_headline' => 'Resources',
        default => false,
    });

    $first = uamswp_test_render_template('single-clinical-resource');
    $second = uamswp_test_render_template('single-clinical-resource');

    ob_start();
    uamswp_resource_post_title();
    $title = (string) ob_get_clean();

    expect($first)->toBe('')
        ->and($second)->toBe('')
        ->and($GLOBALS['page_title_attr'])->toBe("Flu 'Facts'")
        ->and($title)->toContain('<span class="supertitle">Resources</span>')
        ->and($title)->toContain('Flu "Facts"');
});
