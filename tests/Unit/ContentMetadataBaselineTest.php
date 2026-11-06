<?php

/**
 * Empty relationship and scalar metadata contracts for non-provider content.
 *
 * These tests model getter results only. Published selection, query ordering,
 * and WordPress persistence remain covered by Integration/RestMetadataTest.php.
 *
 * @command  vendor/bin/pest --ci tests/Unit/ContentMetadataBaselineTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    // Load metadata callbacks without registering WordPress hooks.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

    // Keep source IDs observable and ordinary absent ACF fields false.
    $this->fields = [101 => ['clinical_resource_type' => ['value' => 'text', 'label' => 'Article']]];
    when('get_field')->alias(fn (
        string $field,
        mixed $id,
    ): mixed => $this->fields[$id][$field] ?? false);
    when('get_the_title')->alias(fn (int $id): string => 'Content '.$id);
    when('get_permalink')->alias(fn (int $id): string => 'https://example.test/content-'.$id);
    when('get_the_post_thumbnail_url')->alias(fn (
        int $id,
        string $size,
    ): string => 'https://example.test/'.$id.'-'.$size.'.jpg');
});

test('condition and treatment metadata omit absent item collections and retain empty lists', function (
    string $callback,
    array $expected,
): void {
    expect($callback(['id' => 101]))->toBe($expected);
})->with([
    'condition' => ['get_condition_meta', [
        'condition_expertise_list' => '',
        'condition_treatments_list' => '',
        'condition_provider_list' => '',
        'condition_location_list' => '',
    ]],
    'treatment' => ['get_treatment_meta', [
        'treatment_expertise_list' => '',
        'treatment_conditions_list' => '',
        'treatment_provider_list' => '',
        'treatment_location_list' => '',
    ]],
]);

test('expertise metadata retains the podcast widget with selected or absent names', function (string|false $name): void {
    // Use a literal widget contract independent of the callback under test.
    $this->fields[101]['expertise_podcast_name'] = $name;
    $widget = '<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
			</script>
			<script type="text/javascript">
				radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"'.$name.'");
			</script>
			<style type="text/css">
				#radiomd-embedded-filtered-tag iframe {
				width: 100%;
				border: none;
			}
			</style>
			<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>';

    // Assert the entire payload, including optional collections remaining absent.
    expect(get_expertise_meta(['id' => 101]))->toBe([
        'expertise_title' => 'Content 101',
        'expertise_link' => 'https://example.test/content-101',
        'expertise_podcast' => $widget,
        'expertise_conditions_list' => '',
        'expertise_treatments_list' => '',
        'expertise_provider_list' => '',
        'expertise_location_list' => '',
    ]);
})->with(['absent' => [false], 'selected' => ['Heart Health']]);

test('clinical resource metadata preserves distinct scalar fields and absent collections', function (bool $populated): void {
    // Give each getter a distinct value so crossed fields cannot pass.
    $fields = [
        'clinical_resource_excerpt' => 'Short excerpt',
        'clinical_resource_text' => '<p>Resource &amp; text</p>',
        'clinical_resource_text_nci_query' => '1',
        'clinical_resource_nci_embed' => '<div>NCI content</div>',
        'clinical_resource_infographic' => '123',
        'clinical_resource_infographic_descr' => 'Image description',
        'clinical_resource_infographic_transcript' => 'Image transcript',
        'clinical_resource_video' => 'https://example.test/video',
        'clinical_resource_video_descr' => 'Video description',
        'clinical_resource_video_transcript' => 'Video transcript',
        'clinical_resource_title_short' => 'Short title',
        'clinical_resource_document_descr' => 'Document description',
    ];
    if ($populated) {
        $this->fields[101] += $fields;
    }

    $expected = [
        'clinical_resource_title' => 'Content 101',
        'clinical_resource_link' => 'https://example.test/content-101',
        'clinical_resource_excerpt' => false,
        'clinical_resource_image' => 'https://example.test/101-large.jpg',
        'clinical_resource_type' => 'Article',
        'clinical_resource_text' => false,
        'clinical_resource_text_nci_query' => false,
        'clinical_resource_nci_embed' => false,
        'clinical_resource_infographic' => false,
        'clinical_resource_infographic_descr' => false,
        'clinical_resource_infographic_transcript' => false,
        'clinical_resource_video' => false,
        'clinical_resource_video_descr' => false,
        'clinical_resource_video_transcript' => false,
        'clinical_resource_title_short' => false,
        'clinical_resource_document_descr' => false,
        'clinical_resource_conditions_list' => '',
        'clinical_resource_treatments_list' => '',
        'clinical_resource_provider_list' => '',
        'clinical_resource_location_list' => '',
        'clinical_resource_expertise_list' => '',
    ];
    if ($populated) {
        $expected = array_replace($expected, $fields);
    }

    // Pin public values without simulating queries or optional repeater internals.
    expect(get_resource_meta(['id' => 101]))->toBe($expected);
})->with(['absent' => [false], 'populated' => [true]]);
