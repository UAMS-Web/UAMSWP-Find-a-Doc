<?php

/**
 * The output of the single-clinical-resource callbacks in includes/class.fad-single-clinical-resource.php: the
 * canonical link, the title, each resource type's body, the related sections, the jump links, the appointment
 * band and the Provider Spotlight.
 *
 * The template sets the page globals and registers each callback on its hook (tests/Unit/SingleClinicalResourceTest.php
 * tests that). Here each callback runs on its own, with the globals it reads set by the test.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/functions-file.php';
require_once __DIR__.'/../Support/wp-classes.php';
require_once __DIR__.'/../../includes/class.fad-helper-functions.php';
require_once __DIR__.'/../../includes/class.fad-single-clinical-resource.php';

/** The page globals the callbacks read, cleared before each test so one test's value cannot reach the next. */
const RESOURCE_CALLBACK_GLOBALS = [
    'syndicated', 'syndication_url', 'resource_archive_title', 'resource_type_label', 'is_provider_spotlight',
    'resource_type_value', 'show_providers_section', 'postsCountClass', 'physicians_query', 'postsPerPage',
    'physicians', 'page_title', 'show_conditions_section', 'conditions_cpt_query', 'show_treatments_section',
    'treatments_cpt_query', 'show_locations_section', 'location_query', 'show_related_resource_section',
    'resources', 'resource_query', 'resource_postsPerPage', 'show_aoe_section', 'expertise_query',
    'show_jump_links_section', 'show_appointment_section', 'spotlight_provider',
];

beforeEach(function (): void {
    foreach (RESOURCE_CALLBACK_GLOBALS as $name) {
        unset($GLOBALS[$name]);
    }
});

afterEach(function (): void {
    foreach (RESOURCE_CALLBACK_GLOBALS as $name) {
        unset($GLOBALS[$name]);
    }
});

/**
 * Run a callback with the given ACF fields and page globals, and return what it printed.
 *
 * @param  array<string, mixed>  $fields  The ACF fields by name.
 * @param  array<string, mixed>  $globals  The page globals by name.
 */
function resource_callback_output(string $callback, array $fields = [], array $globals = []): string
{
    foreach ($globals as $name => $value) {
        $GLOBALS[$name] = $value;
    }

    when('get_field')->alias(static fn (string $name): mixed => $fields[$name] ?? null);
    when('esc_url')->alias(static fn (string $url): string => 'url('.$url.')');
    when('esc_attr')->alias(static fn (string $text): string => 'attr('.$text.')');
    when('esc_html')->alias(static fn (string $text): string => 'html('.$text.')');
    when('wp_strip_all_tags')->alias(static fn (string $text): string => strip_tags($text));

    ob_start();

    try {
        $callback();
    } finally {
        $output = (string) ob_get_clean();
    }

    return $output;
}

/**
 * Collapse the whitespace of printed markup and drop it between tags, so a test reads the markup and not its
 * indentation.
 */
function resource_callback_squeeze(string $html): string
{
    return trim((string) preg_replace(['/\s+/', '/>\s+</'], [' ', '><'], $html));
}

test('the canonical link points at the syndication URL of a syndicated resource, decoded and escaped', function (): void {
    $GLOBALS['syndicated'] = true;
    $GLOBALS['syndication_url'] = 'https%3A%2F%2Fnews.example.test%2Fa%3Fx%3D1%26y%3D%222%22';

    expect(uamswp_fad_canonical('<link rel="canonical" href="https://example.test/own/" />'))
        ->toBe('<link rel="canonical" href="https://news.example.test/a?x=1&amp;y=&quot;2&quot;" />');
});

test('the canonical link is left alone for a resource that is not syndicated or has no syndication URL', function (mixed $syndicated, mixed $url): void {
    $GLOBALS['syndicated'] = $syndicated;
    $GLOBALS['syndication_url'] = $url;

    expect(uamswp_fad_canonical('<link rel="canonical" href="https://example.test/own/" />'))->toBe('<link rel="canonical" href="https://example.test/own/" />');
})->with([
    'not syndicated' => [false, 'https://news.example.test/a'],
    'no URL' => [true, ''],
    'neither' => [null, null],
]);

test('the title names the archive above the resource title, with any markup in the title removed', function (): void {
    when('get_the_title')->justReturn('Flu <em>Facts</em> &#8217;24');

    expect(resource_callback_output('uamswp_resource_post_title', [], ['resource_archive_title' => 'Clinical Resource', 'resource_type_label' => 'Provider Spotlight', 'is_provider_spotlight' => false]))
        ->toBe('<h1 class="entry-title" itemprop="headline"><span class="supertitle">Clinical Resource</span><span class="sr-only">:</span> Flu Facts &#8217;24</h1>');
});

test('the title of a Provider Spotlight names its type above the title', function (): void {
    when('get_the_title')->justReturn('Meet Dr. Smith');

    expect(resource_callback_output('uamswp_resource_post_title', [], ['resource_archive_title' => 'Clinical Resource', 'resource_type_label' => 'Provider Spotlight', 'is_provider_spotlight' => true]))
        ->toBe('<h1 class="entry-title" itemprop="headline"><span class="supertitle">Provider Spotlight</span><span class="sr-only">:</span> Meet Dr. Smith</h1>');
});

test('an article prints its text, or the NCI embed when it is an NCI query, and nothing for another type', function (string $type, array $fields, string $expected): void {
    expect(resource_callback_output('uamswp_resource_text', $fields, ['resource_type_value' => $type]))->toBe($expected);
})->with([
    'the text' => ['text', ['clinical_resource_text' => '<p>Text.</p>'], '<p>Text.</p>'],
    'the NCI embed' => ['text', ['clinical_resource_text' => '<p>Text.</p>', 'clinical_resource_text_nci_query' => true, 'clinical_resource_nci_embed' => '<div>NCI</div>'], '<div>NCI</div>'],
    'an NCI query with no embed' => ['text', ['clinical_resource_text' => '<p>Text.</p>', 'clinical_resource_text_nci_query' => true], ''],
    'no text' => ['text', [], ''],
    'the embed without the NCI query' => ['text', ['clinical_resource_nci_embed' => '<div>NCI</div>'], ''],
    'another type' => ['video', ['clinical_resource_text' => '<p>Text.</p>', 'clinical_resource_text_nci_query' => true, 'clinical_resource_nci_embed' => '<div>NCI</div>'], ''],
    'another type with only text' => ['video', ['clinical_resource_text' => '<p>Text.</p>'], ''],
]);

test('an infographic prints its description, the wide image and its transcript', function (): void {
    when('wp_get_attachment_image')->alias(static fn (mixed $id, string $size): string => '[image '.var_export($id, true).' '.$size.']');

    expect(resource_callback_output('uamswp_resource_infographic', ['clinical_resource_infographic' => '41', 'clinical_resource_infographic_descr' => '<p>About.</p>', 'clinical_resource_infographic_transcript' => '<p>Words.</p>'], ['resource_type_value' => 'infographic']))
        ->toBe('<h2 class="sr-only">Description</h2><p>About.</p><h2 class="sr-only">Infographic</h2><div class="alignwide">[image 41 content-image-wide]</div><h2>Transcript</h2><p>Words.</p>');
});

test('an infographic without a description or transcript prints only the image, and nothing without an image or for another type', function (): void {
    when('wp_get_attachment_image')->alias(static fn (mixed $id, string $size): string => '[image '.var_export($id, true).' '.$size.']');

    expect(resource_callback_output('uamswp_resource_infographic', ['clinical_resource_infographic' => 41], ['resource_type_value' => 'infographic']))
        ->toBe('<h2 class="sr-only">Infographic</h2><div class="alignwide">[image 41 content-image-wide]</div>')
        ->and(resource_callback_output('uamswp_resource_infographic', ['clinical_resource_infographic_descr' => '<p>About.</p>'], ['resource_type_value' => 'infographic']))->toBe('')
        ->and(resource_callback_output('uamswp_resource_infographic', ['clinical_resource_infographic' => 41], ['resource_type_value' => 'text']))->toBe('');
});

/**
 * Stub ACF's repeater loop over the given rows of one field.
 *
 * @param  array<int, array<string, mixed>>  $rows
 */
function resource_callback_rows(string $field, array $rows): void
{
    $state = new ArrayObject(['row' => -1, 'checks' => 0]);

    when('have_rows')->alias(static function (string $name) use ($field, $rows, $state): bool {
        // A loop whose the_row() call is gone would never advance; cap the checks so it ends and fails its test.
        if ($name !== $field || ++$state['checks'] > 100) {
            return false;
        }

        if (count($rows) > $state['row'] + 1) {
            return true;
        }

        // Like ACF, the loop rewinds once it has run to its end, so the next loop starts again.
        $state['row'] = -1;

        return false;
    });
    when('the_row')->alias(static function () use ($state): array {
        $state['row']++;

        return [];
    });
    when('get_sub_field')->alias(static fn (string $name): mixed => $rows[$state['row']][$name] ?? null);
}

test('a document lists each attachment with the icon of its file type', function (): void {
    $files = ['a.pdf', 'b.doc', 'c.docx', 'd.ppt', 'e.pptx', 'f.xls', 'g.xlsx', 'h.jpg', 'i.jpeg', 'j.gif', 'k.png', 'l.bmp'];
    resource_callback_rows('clinical_resource_document', array_map(static fn (string $file): array => ['document_title' => 'File '.$file, 'document_file' => ['url' => 'https://example.test/files/'.$file]], $files));

    $output = resource_callback_output('uamswp_resource_document', ['clinical_resource_document_descr' => '<p>Forms.</p>'], ['resource_type_value' => 'doc']);
    $icons = ['pdf', 'word', 'word', 'powerpoint', 'powerpoint', 'excel', 'excel', 'image', 'image', 'image', 'image', 'image'];
    $items = '';

    foreach ($files as $i => $file) {
        $items .= '<li><a class="attachment-link" href="url(https://example.test/files/'.$file.')" title="attr(File '.$file.')" target="_blank"><span class="far fa-file-'.$icons[$i].' fa-fw"></span><span class="attachment-label">html(File '.$file.')</span></a></li>';
    }

    expect(resource_callback_squeeze($output))->toBe(resource_callback_squeeze('<p>Forms.</p><hr /><h2>Attachments</h2><ul class="attachments">'.$items.'</ul>'));
});

test('each image type gets the image icon after a file of another type', function (): void {
    $files = ['a.pdf', 'b.jpg', 'c.pdf', 'd.jpeg', 'e.pdf', 'f.gif', 'g.pdf', 'h.png', 'i.pdf', 'j.bmp'];
    resource_callback_rows('clinical_resource_document', array_map(static fn (string $file): array => ['document_title' => $file, 'document_file' => ['url' => 'https://example.test/'.$file]], $files));

    $output = resource_callback_output('uamswp_resource_document', [], ['resource_type_value' => 'doc']);
    preg_match_all('/<span class="far (fa-file[a-z-]*) fa-fw">/', $output, $icons);

    expect($icons[1])->toBe(['fa-file-pdf', 'fa-file-image', 'fa-file-pdf', 'fa-file-image', 'fa-file-pdf', 'fa-file-image', 'fa-file-pdf', 'fa-file-image', 'fa-file-pdf', 'fa-file-image']);
});

test("each newer Office extension gets its program's icon after a file of another type", function (): void {
    $files = ['a.pdf', 'b.docx', 'c.pdf', 'd.pptx', 'e.pdf', 'f.xlsx'];
    resource_callback_rows('clinical_resource_document', array_map(static fn (string $file): array => ['document_title' => $file, 'document_file' => ['url' => 'https://example.test/'.$file]], $files));

    $output = resource_callback_output('uamswp_resource_document', [], ['resource_type_value' => 'doc']);
    preg_match_all('/<span class="far (fa-file[a-z-]*) fa-fw">/', $output, $icons);

    expect($icons[1])->toBe(['fa-file-pdf', 'fa-file-word', 'fa-file-pdf', 'fa-file-powerpoint', 'fa-file-pdf', 'fa-file-excel']);
});

test('an attachment of another file type keeps the icon of the one before it, starting from the plain file icon', function (): void {
    resource_callback_rows('clinical_resource_document', [
        ['document_title' => 'Notes', 'document_file' => ['url' => 'https://example.test/notes.txt']],
        ['document_title' => 'Form', 'document_file' => ['url' => 'https://example.test/form.pdf']],
        ['document_title' => 'Page', 'document_file' => []],
    ]);

    $output = resource_callback_output('uamswp_resource_document', [], ['resource_type_value' => 'doc']);

    expect($output)->toContain('<span class="far fa-file fa-fw"></span><span class="attachment-label">html(Notes)</span>')
        ->toContain('<span class="far fa-file-pdf fa-fw"></span><span class="attachment-label">html(Form)</span>')
        ->toContain('href="url()" title="attr(Page)" target="_blank"><span class="far fa-file-pdf fa-fw"></span>');
});

test('a document prints nothing without attachments or for another type', function (): void {
    resource_callback_rows('clinical_resource_document', []);
    expect(resource_callback_output('uamswp_resource_document', ['clinical_resource_document_descr' => '<p>Forms.</p>'], ['resource_type_value' => 'doc']))->toBe('');

    resource_callback_rows('clinical_resource_document', [['document_title' => 'Form', 'document_file' => ['url' => 'https://example.test/form.pdf']]]);
    expect(resource_callback_output('uamswp_resource_document', ['clinical_resource_document_descr' => '<p>Forms.</p>'], ['resource_type_value' => 'text']))->toBe('');
});

test('a document reads its attachment field', function (): void {
    $read = [];
    resource_callback_rows('clinical_resource_document', []);
    when('get_field')->alias(static function (string $name) use (&$read): mixed {
        $read[] = $name;

        return null;
    });

    ob_start();
    uamswp_resource_document();
    ob_end_clean();

    expect($read)->toBe(['clinical_resource_document_descr', 'clinical_resource_document']);
});

/**
 * Stub the video plugins: Lyte is present for every video test, so whether it is installed never depends on
 * which test ran first, and the embed is what wp_oembed_get() would return.
 *
 * Defining lyte_preparse() keeps it defined for the rest of the process, and SingleConditionTest's test of a page
 * without Lyte skips once it is. This file is named to run after that one for that reason.
 */
function resource_callback_video_stubs(): void
{
    when('lyte_preparse')->justReturn('');
    when('lyte_parse')->alias(static fn (string $url): string => '[lyte '.$url.']');
    when('wp_oembed_get')->alias(static fn (string $url): string => '[oembed '.$url.']');
}

test('a YouTube video plays through Lyte, with its description and transcript', function (string $url, string $lyte): void {
    resource_callback_video_stubs();

    expect(trim(resource_callback_output('uamswp_resource_video', ['clinical_resource_video' => $url, 'clinical_resource_video_descr' => '<p>About.</p>', 'clinical_resource_video_transcript' => '<p>Words.</p>'], ['resource_type_value' => 'video'])))
        ->toBe('<h2 class="sr-only">Description</h2><p>About.</p><h2 class="sr-only">Video Player</h2><div class="alignwide">[lyte '.$lyte.']</div><h2>Transcript</h2><p>Words.</p>');
})->with([
    'youtube.com over https' => ['https://www.youtube.com/watch?v=abc', 'httpv://www.youtube.com/watch?v=abc'],
    'youtu.be over http' => ['http://youtu.be/abc', 'httpv://youtu.be/abc'],
]);

test('a video from elsewhere is embedded, and one without a description or transcript prints only the player', function (): void {
    resource_callback_video_stubs();

    expect(trim(resource_callback_output('uamswp_resource_video', ['clinical_resource_video' => 'https://vimeo.com/123'], ['resource_type_value' => 'video'])))
        ->toBe('<h2 class="sr-only">Video Player</h2><div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">[oembed https://vimeo.com/123]</div>');
});

test('a video prints nothing without a link or for another type', function (): void {
    resource_callback_video_stubs();

    expect(resource_callback_output('uamswp_resource_video', ['clinical_resource_video_descr' => '<p>About.</p>'], ['resource_type_value' => 'video']))->toBe('')
        ->and(resource_callback_output('uamswp_resource_video', ['clinical_resource_video' => 'https://vimeo.com/123'], ['resource_type_value' => 'text']))->toBe('');
});

test('the jump links list each section that shows, in page order', function (): void {
    $all = ['show_jump_links_section' => true, 'show_related_resource_section' => true, 'show_conditions_section' => true, 'show_treatments_section' => true, 'show_providers_section' => true, 'show_locations_section' => true, 'show_aoe_section' => true, 'show_appointment_section' => true];

    $output = resource_callback_squeeze(resource_callback_output('uamswp_resource_jump_links', [], $all));

    expect($output)->toBe(resource_callback_squeeze('<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
            <h2>Related Content</h2>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                <ul class="nav navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#related-resources" title="Jump to the section of this page about Related Resources">Resources</a></li>
                    <li class="nav-item"><a class="nav-link" href="#conditions" title="Jump to the section of this page about Related Conditions">Conditions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#treatments" title="Jump to the section of this page about Medical Treatments and Procedures Performed">Treatments &amp; Procedures</a></li>
                    <li class="nav-item"><a class="nav-link" href="#providers" title="Jump to the section of this page about Providers">Providers</a></li>
                    <li class="nav-item"><a class="nav-link" href="#locations" title="Jump to the section of this page about Locations">Locations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#expertise" title="Jump to the section of this page about Related Areas of Expertise">Areas of Expertise</a></li>
                    <li class="nav-item"><a class="nav-link" href="#appointment-info" title="Jump to the section of this page about making an appointment">Make an Appointment</a></li>
                </ul>
            </div>
        </nav>'));
});

test('each jump link shows only with its section', function (string $flag, string $anchor): void {
    $output = resource_callback_output('uamswp_resource_jump_links', [], ['show_jump_links_section' => true, $flag => true]);

    expect(substr_count($output, 'class="nav-item"'))->toBe(1)
        ->and($output)->toContain('href="#'.$anchor.'"');
})->with([
    'resources' => ['show_related_resource_section', 'related-resources'],
    'conditions' => ['show_conditions_section', 'conditions'],
    'treatments' => ['show_treatments_section', 'treatments'],
    'providers' => ['show_providers_section', 'providers'],
    'locations' => ['show_locations_section', 'locations'],
    'areas of expertise' => ['show_aoe_section', 'expertise'],
    'appointment' => ['show_appointment_section', 'appointment-info'],
]);

test('the jump links print nothing when they do not show', function (): void {
    expect(resource_callback_output('uamswp_resource_jump_links', [], ['show_jump_links_section' => false, 'show_conditions_section' => true]))->toBe('');
});

test('the appointment band links to the location list and the appointment line', function (): void {
    expect(resource_callback_squeeze(resource_callback_output('uamswp_resource_appointment', [], ['show_appointment_section' => true, 'is_provider_spotlight' => false, 'spotlight_provider' => 12])))
        ->toBe(resource_callback_squeeze('<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Make an Appointment</h2>
                        <p>Request an appointment by <a href="/location/" data-itemtitle="Contact a clinic directly">contacting a clinic directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
                    </div>
                </div>
            </div>
        </section>'));
});

test("a Provider Spotlight replaces the appointment band with its provider's call to action", function (): void {
    when('uamswp_spotlight_appointment_cta')->alias(static fn (mixed $provider): string => '[cta '.var_export($provider, true).']');

    expect(resource_callback_output('uamswp_resource_appointment', [], ['show_appointment_section' => true, 'is_provider_spotlight' => true, 'spotlight_provider' => 12]))->toBe('[cta 12]');
});

test('a Provider Spotlight with no provider gets the plain appointment band, and nothing prints when the band does not show', function (): void {
    when('uamswp_spotlight_appointment_cta')->justReturn('[cta]');

    expect(resource_callback_output('uamswp_resource_appointment', [], ['show_appointment_section' => true, 'is_provider_spotlight' => true, 'spotlight_provider' => false]))->toContain('<h2>Make an Appointment</h2>')
        ->and(resource_callback_output('uamswp_resource_appointment', [], ['show_appointment_section' => false, 'is_provider_spotlight' => true, 'spotlight_provider' => 12]))->toBe('');
});

/**
 * Stub what the Provider Spotlight reads about its provider, and record the image filters it adds and removes.
 *
 * @return ArrayObject<int, array{0: string, 1: mixed}> The filter calls, in order.
 */
function resource_callback_spotlight_stubs(int $portrait, string $intro = '<p>Meet Jo.</p>'): ArrayObject
{
    /** @var ArrayObject<int, array{0: string, 1: mixed}> $filters */
    $filters = new ArrayObject;

    when('uamswp_provider_names')->alias(static fn (mixed $id): array => ['medium_attr' => 'Jo Smith, MD ('.var_export($id, true).')']);
    when('uamswp_spotlight_default_intro')->alias(static fn (mixed $id): string => $intro);
    when('get_post_thumbnail_id')->alias(static fn (mixed $id): int => $id === 12 ? $portrait : 99);
    when('add_filter')->alias(static function (string $hook, mixed $callback) use ($filters): bool {
        $filters[] = ['add '.$hook, $callback];

        return true;
    });
    when('remove_filter')->alias(static function (string $hook, mixed $callback) use ($filters): bool {
        $filters[] = ['remove '.$hook, $callback];

        return true;
    });
    when('wp_get_attachment_image')->alias(static function (int $id, string $size, bool $icon, array $attr) use ($filters): string {
        // The itemprop is added by the filter, which is in place only while the image is printed.
        $added = $filters->count() > 0 ? $filters[$filters->count() - 1] : null;
        $attr = $added !== null && str_starts_with($added[0], 'add ') ? ($added[1])($attr) : $attr;

        return '[image '.$id.' '.$size.' '.var_export($icon, true).' '.json_encode($attr).']';
    });

    return $filters;
}

test("a Provider Spotlight prints its introduction, then each answered question beside the provider's portrait", function (): void {
    $filters = resource_callback_spotlight_stubs(55);
    resource_callback_rows('clinical_resource_spotlight_qa', [
        ['spotlight_qa_question' => 'Why "medicine"?', 'spotlight_qa_answer' => '<p>To help.</p>'],
        ['spotlight_qa_question' => '', 'spotlight_qa_answer' => '<p>Skipped.</p>'],
        ['spotlight_qa_question' => 'Hobbies?', 'spotlight_qa_answer' => '<p>Hiking.</p>'],
    ]);

    $output = resource_callback_output('uamswp_resource_provider_spotlight', [], ['is_provider_spotlight' => true, 'spotlight_provider' => 12]);

    expect(resource_callback_squeeze($output))->toBe(resource_callback_squeeze('<h2 class="sr-only">Description</h2><p>Meet Jo.</p>
        <h2 class="sr-only">Questions and Answers</h2>
        <div class="rule-top-lg d-flow-root">
            <figure class="alignright"> [image 55 content-image-side false {"alt":"Jo Smith, MD (12)","itemprop":"image"}] </figure>
            <h3>html(Why "medicine"?)</h3><div class="answer"><p>To help.</p></div>
            <h3>html(Hobbies?)</h3><div class="answer"><p>Hiking.</p></div>
        </div>'))
        ->and(array_column($filters->getArrayCopy(), 0))->toBe(['add wp_get_attachment_image_attributes', 'remove wp_get_attachment_image_attributes'])
        ->and($filters[0][1])->toBe($filters[1][1])
        ->and(($filters[0][1])('not an array'))->toBe(['itemprop' => 'image']);
});

test('a Provider Spotlight with no portrait, introduction or questions prints only what it has', function (): void {
    $filters = resource_callback_spotlight_stubs(0, '');
    resource_callback_rows('clinical_resource_spotlight_qa', [['spotlight_qa_question' => 'Hobbies?', 'spotlight_qa_answer' => '<p>Hiking.</p>']]);

    expect(resource_callback_squeeze(resource_callback_output('uamswp_resource_provider_spotlight', [], ['is_provider_spotlight' => true, 'spotlight_provider' => 12])))
        ->toBe('<h2 class="sr-only">Questions and Answers</h2><div class="rule-top-lg d-flow-root"><h3>html(Hobbies?)</h3><div class="answer"><p>Hiking.</p></div></div>')
        ->and($filters->count())->toBe(0);

    resource_callback_spotlight_stubs(55);
    resource_callback_rows('clinical_resource_spotlight_qa', []);

    expect(resource_callback_output('uamswp_resource_provider_spotlight', [], ['is_provider_spotlight' => true, 'spotlight_provider' => 12]))->toBe('<h2 class="sr-only">Description</h2><p>Meet Jo.</p>');
});

test('a resource that is not a Provider Spotlight, or has no provider, prints no spotlight', function (bool $spotlight, mixed $provider): void {
    resource_callback_spotlight_stubs(55);
    resource_callback_rows('clinical_resource_spotlight_qa', [['spotlight_qa_question' => 'Hobbies?', 'spotlight_qa_answer' => '<p>Hiking.</p>']]);

    expect(resource_callback_output('uamswp_resource_provider_spotlight', [], ['is_provider_spotlight' => $spotlight, 'spotlight_provider' => $provider]))->toBe('');
})->with([
    'not a spotlight' => [false, 12],
    'no provider' => [true, false],
]);

/**
 * A query over the given posts, as the template built it.
 *
 * @param  array<int, mixed>  $posts
 */
function resource_callback_query(array $posts, int $found = 0): WP_Query
{
    $query = new WP_Query;
    $query->posts = $posts;
    $query->found_posts = $found;

    return $query;
}

/**
 * Stub the loop calls, and make the first call each card partial makes stop the card with a marker naming it
 * and its post, so a test can tell which card was included for which post without rendering the card.
 *
 * @return ArrayObject<int, string> The calls to wp_reset_postdata(), one entry each.
 */
function resource_callback_cards(): ArrayObject
{
    /** @var ArrayObject<int, string> $resets */
    $resets = new ArrayObject;

    // The location card's first call is a plugin function; load it first so the stub redefines it rather than
    // declaring a function the file would declare again.
    uamswp_test_functions_file();

    when('get_the_ID')->alias(static fn (): int => WP_Query::$current);
    when('wp_reset_postdata')->alias(static function () use ($resets): void {
        $resets[] = 'reset';
    });
    when('get_field')->alias(static fn (string $name, mixed $id = null): never => throw new RuntimeException('physician card '.var_export($id, true)));
    when('uamswp_fad_location_source_ids')->alias(static fn (int $id): never => throw new RuntimeException('location card '.$id));
    when('get_the_title')->alias(static fn (mixed $id = null): never => throw new RuntimeException('expertise card '.var_export($id, true)));

    return $resets;
}

/**
 * Run a section callback with the given globals and stubbed cards, and return what it printed.
 *
 * @param  array<string, mixed>  $globals
 */
function resource_callback_section(string $callback, array $globals): string
{
    foreach ($globals as $name => $value) {
        $GLOBALS[$name] = $value;
    }

    ob_start();

    try {
        $callback();
    } finally {
        $output = (string) ob_get_clean();
    }

    return $output;
}

test('the providers section prints its heading, an empty list of cards and the load all button, and resets the post data', function (int $per_page, string $comment): void {
    $resets = resource_callback_cards();

    $output = resource_callback_section('uamswp_resource_physicians', ['show_providers_section' => true, 'physicians_query' => resource_callback_query([], 7), 'postsPerPage' => $per_page, 'physicians' => [21, 22]]);

    expect(resource_callback_squeeze($output))->toBe(resource_callback_squeeze('<section class="uams-module bg-auto" id="providers">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Providers</span></h2>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors">
                            </div>
                        </div>
                        <!-- '.$comment.' -->
                        <div class="ajax-filter-load-more">
                            <button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>'))
        ->and($resets->count())->toBe(1);
})->with([
    'every provider on one page' => [-1, ''],
    'a page of ten' => [10, '<div class="more"><button class="loadmore btn btn-primary" data-posttype="post" data-postids="21,22" data-ppp="10" data-postcount="7" aria-label="Load more providers">Load More</button></div>'],
]);

test('the providers section includes a provider card for each provider of the query', function (): void {
    resource_callback_cards();

    expect(fn () => resource_callback_section('uamswp_resource_physicians', ['show_providers_section' => true, 'physicians_query' => resource_callback_query([21]), 'postsPerPage' => -1, 'physicians' => [21]]))
        ->toThrow(RuntimeException::class, 'physician card 21');
});

test('the locations section prints its heading around the location cards, and includes a card for each location', function (): void {
    $resets = resource_callback_cards();

    expect(resource_callback_squeeze(resource_callback_section('uamswp_resource_locations', ['show_locations_section' => true, 'location_query' => resource_callback_query([])])))
        ->toBe(resource_callback_squeeze('<section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Related Locations</span></h2>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                        </div>
                    </div>
                </div>
            </div>
        </section>'))
        ->and($resets->count())->toBe(1)
        ->and(fn () => resource_callback_section('uamswp_resource_locations', ['show_locations_section' => true, 'location_query' => resource_callback_query([31])]))->toThrow(RuntimeException::class, 'location card 31');
});

// The section opens five divs and closes four, as it does today: #1471.
test('the areas of expertise section prints its heading around the expertise cards, and includes a card for each area', function (): void {
    $resets = resource_callback_cards();

    expect(resource_callback_squeeze(resource_callback_section('uamswp_resource_expertise', ['show_aoe_section' => true, 'expertise_query' => resource_callback_query([])])))
        ->toBe(resource_callback_squeeze('<section class="uams-module expertise-list bg-auto" id="expertise" aria-labelledby="areas-of-expertise-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title" id="areas-of-expertise-title"><span class="title">Related Areas of Expertise</span></h2>
                        <div class="card-list-container">
                            <div class="card-list card-list-expertise">
                            </div>
                        </div>
                    </div>
                </div>
        </section>'))
        ->and($resets->count())->toBe(1)
        ->and(fn () => resource_callback_section('uamswp_resource_expertise', ['show_aoe_section' => true, 'expertise_query' => resource_callback_query([61])]))->toThrow(RuntimeException::class, 'expertise card 61');
});

test('the related sections print nothing when they do not show', function (string $callback, string $flag): void {
    resource_callback_cards();

    expect(resource_callback_section($callback, [$flag => false, 'physicians_query' => resource_callback_query([21]), 'location_query' => resource_callback_query([31]), 'expertise_query' => resource_callback_query([61])]))->toBe('');
})->with([
    'providers' => ['uamswp_resource_physicians', 'show_providers_section'],
    'locations' => ['uamswp_resource_locations', 'show_locations_section'],
    'areas of expertise' => ['uamswp_resource_expertise', 'show_aoe_section'],
    'conditions' => ['uamswp_resource_conditions_cpt', 'show_conditions_section'],
    'treatments' => ['uamswp_resource_treatments_cpt', 'show_treatments_section'],
    'resources' => ['uamswp_resource_associated', 'show_related_resource_section'],
]);

test('the related conditions and treatments lists are headed for a clinical resource', function (string $callback, string $flag, string $query, string $heading): void {
    when('get_the_permalink')->alias(static fn (int $id): string => 'https://example.test/p/'.$id.'/');
    when('esc_attr')->returnArg();

    $output = resource_callback_section($callback, [$flag => true, $query => (object) ['posts' => [(object) ['ID' => 5, 'post_title' => 'Asthma']]], 'page_title' => 'Flu Facts']);

    expect($output)->toContain('<span class="title">'.$heading.'</span>')
        ->toContain('href="https://example.test/p/5/"')
        ->not->toContain('may not be listed below');
})->with([
    'conditions' => ['uamswp_resource_conditions_cpt', 'show_conditions_section', 'conditions_cpt_query', 'Related Conditions'],
    'treatments' => ['uamswp_resource_treatments_cpt', 'show_treatments_section', 'treatments_cpt_query', 'Related Medical Treatments and Procedures'],
]);

test('the related resources are headed Related Resources and offer no link to the full list', function (): void {
    $resets = resource_callback_cards();

    $output = resource_callback_section('uamswp_resource_associated', ['show_related_resource_section' => true, 'resources' => [], 'resource_query' => resource_callback_query([]), 'resource_postsPerPage' => 4, 'page_title' => 'Flu Facts']);

    expect($output)->toContain('<section class="uams-module stacked-image-text bg-auto" id="related-resources"')
        ->toContain('<span class="title">Related Resources</span>')
        ->not->toContain('Related to')
        ->not->toContain('View the Full List')
        ->and($resets->count())->toBe(1);
});
