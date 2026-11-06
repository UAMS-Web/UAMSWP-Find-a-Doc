<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';
require_once __DIR__.'/../Support/theme-images.php';
require_once __DIR__.'/../Support/theme-phones.php';

final class RestMetadataTest extends IntegrationTestCase
{
    /**
     * @return array<string, array{string, array<int, array{string, string, string, string}>}>
     */
    public static function relationships(): array
    {
        return [
            'condition' => ['get_condition_meta', [
                ['expertise', 'condition_expertise', 'condition_expertise', 'condition_expertise_list'],
                ['treatment', 'condition_treatments', 'condition_treatments', 'condition_treatments_list'],
                ['provider', 'condition_physicians', 'condition_provider', 'condition_provider_list'],
                ['location', 'condition_locations', 'condition_location', 'condition_location_list'],
            ]],
            'treatment' => ['get_treatment_meta', [
                ['expertise', 'treatment_procedure_expertise', 'treatment_expertise', 'treatment_expertise_list'],
                ['condition', 'treatment_conditions', 'treatment_conditions', 'treatment_conditions_list'],
                ['provider', 'treatment_procedure_physicians', 'treatment_provider', 'treatment_provider_list'],
                ['location', 'treatment_procedure_locations', 'treatment_location', 'treatment_location_list'],
            ]],
        ];
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_absent_relationships_return_empty_lists_without_item_collections(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $expected[$list] = '';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_selected_published_relationships_are_alphabetized_and_keep_their_public_fields(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $zulu = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Zulu', 'post_name' => 'zulu']);
            $alpha = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Alpha', 'post_name' => 'alpha']);
            self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Not selected']);
            update_post_meta($post_id, $field, [$zulu, $alpha]);
            update_post_meta($alpha, 'physician_full_name', 'Doctor First');
            update_post_meta($zulu, 'physician_full_name', 'Doctor Last');

            $expected[$items] = [
                $alpha => ['link' => get_permalink($alpha), 'title' => $type === 'provider' ? 'Doctor First' : 'Alpha', 'slug' => 'alpha'],
                $zulu => ['link' => get_permalink($zulu), 'title' => $type === 'provider' ? 'Doctor Last' : 'Zulu', 'slug' => 'zulu'],
            ];
            $expected[$list] = $type === 'provider' ? 'Doctor First | Doctor Last' : 'Alpha, Zulu';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }

    /**
     * @dataProvider relationships
     *
     * @param  array<int, array{string, string, string, string}>  $relationships
     */
    public function test_unpublished_and_wrong_type_relationships_do_not_leak_into_public_metadata(string $callback, array $relationships): void
    {
        $post_id = (int) self::factory()->post->create();
        $expected = [];
        foreach ($relationships as [$type, $field, $items, $list]) {
            $draft = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'draft']);
            $private = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'private']);
            $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'publish']);
            update_post_meta($post_id, $field, [$draft, $private, $page]);
            $expected[$list] = '';
        }

        $this->assertSame($expected, $callback(['id' => $post_id]));
    }

    /**
     * Enumerate every metadata list that counts selected IDs for separators.
     *
     * @return array<string, array{string, string, string, string, string, int}>
     */
    public static function mixedRelationships(): array
    {
        // Reuse the condition and treatment contracts, then add the other roots.
        $groups = self::relationships() + [
            'provider' => ['get_provider_meta', [
                ['condition', 'physician_conditions_cpt', 'physician_conditions', 'physician_conditions_list'],
                ['treatment', 'physician_treatments_cpt', 'physician_treatments', 'physician_treatments_list'],
                ['expertise', 'physician_expertise', 'physician_expertise', 'physician_expertise_list'],
            ]],
            'location' => ['get_location_meta', [
                ['provider', 'physician_locations', 'location_provider', 'location_provider_list'],
                ['expertise', 'location_expertise', 'location_expertise', 'location_expertise_list'],
                ['condition', 'location_conditions_cpt', 'location_conditions', 'location_conditions_list'],
                ['treatment', 'location_treatments_cpt', 'location_treatments', 'location_treatments_list'],
            ]],
            'expertise' => ['get_expertise_meta', [
                ['condition', 'expertise_conditions_cpt', 'expertise_conditions', 'expertise_conditions_list'],
                ['treatment', 'expertise_treatments_cpt', 'expertise_treatments', 'expertise_treatments_list'],
                ['provider', 'physician_expertise', 'expertise_provider', 'expertise_provider_list'],
                ['location', 'location_expertise', 'expertise_location', 'expertise_location_list'],
            ]],
        ];

        // Give each relationship its own failure rather than stopping at the first list.
        $cases = [];
        foreach ($groups as [$callback, $relationships]) {
            foreach ($relationships as [$type, $field, $items, $list]) {
                foreach ([1, 2] as $survivors) {
                    $cases[$list.' with '.$survivors.' returned'] = [$callback, $type, $field, $items, $list, $survivors];
                }
            }
        }

        return $cases;
    }

    /**
     * Exclude rejected selections from delimiter counts without losing order.
     *
     * @dataProvider mixedRelationships
     */
    public function test_mixed_relationship_selections_separate_only_returned_posts(
        string $callback,
        string $type,
        string $field,
        string $items,
        string $list,
        int $survivors,
    ): void {
        // Supply getter-visible groups and valid term selectors for each root.
        $post_id = (int) self::factory()->post->create();
        $portal = (int) self::factory()->term->create(['taxonomy' => 'portal']);
        update_post_meta($post_id, 'physician_portal', $portal);
        update_post_meta($post_id, 'location_portal', $portal);
        update_post_meta($post_id, 'physician_locations', []);
        update_post_meta($post_id, 'physician_resident_profile_group', [
            'physician_resident_academic_department' => 0,
            'physician_resident_academic_chief' => false,
            'physician_resident_academic_year' => 0,
        ]);
        update_post_meta($post_id, 'location_region', self::factory()->term->create(['taxonomy' => 'region']));
        update_post_meta($post_id, 'location_hours_group', array_fill_keys([
            'location_hours_variable', 'location_hours_variable_info', 'location_24_7',
            'location_modified_hours', 'location_modified_hours_reason',
            'location_modified_hours_start_date', 'location_modified_hours_end',
            'location_modified_hours_end_date', 'location_modified_hours_group',
            'location_hours', 'location_after_hours', 'location_telemed_query',
            'location_telemed_patients', 'location_telemed_24_7', 'location_telemed_hours',
            'location_telemed_modified_hours_query', 'location_telemed_modified_hours_reason',
            'location_telemed_modified_hours_start_date', 'location_telemed_modified_hours_end',
            'location_telemed_modified_hours_end_date', 'location_telemed_modified_hours_24_7',
        ], false));

        // Mix published records with draft, private, and wrong-type selections.
        $alpha = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Alpha']);
        $zulu = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Zulu']);
        $draft = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'draft']);
        $private = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'private']);
        $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'publish']);
        update_post_meta($alpha, 'physician_full_name', 'Doctor Z');
        update_post_meta($zulu, 'physician_full_name', 'Doctor A');

        // Check both a single survivor and multiple survivors selected in reverse order.
        $selected = $survivors === 1 ? [$alpha, $draft, $private, $page] : [$zulu, $draft, $alpha, $private, $page];
        $expected_ids = $survivors === 1 ? [$alpha] : [$alpha, $zulu];
        $titles = $survivors === 1 ? 'Alpha' : 'Alpha, Zulu';
        $names = $survivors === 1 ? 'Doctor Z' : 'Doctor Z | Doctor A';
        update_post_meta($post_id, $field, $selected);
        $data = $callback(['id' => $post_id]);
        $this->assertSame($expected_ids, array_keys($data[$items]));
        $this->assertSame($type === 'provider' ? $names : $titles, $data[$list]);
    }

    /** @return array<string, array{string}> */
    public static function podcastNames(): array
    {
        return ['absent name' => [''], 'selected name' => ['Heart Health']];
    }

    /**
     * Pin the complete expertise baseline and the widget even without a podcast name.
     *
     * @dataProvider podcastNames
     */
    public function test_expertise_metadata_keeps_the_podcast_widget(string $name): void
    {
        $id = (int) self::factory()->post->create(['post_type' => 'expertise', 'post_title' => 'Heart care']);
        if ($name !== '') {
            update_post_meta($id, 'expertise_podcast_name', $name);
        }

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
        $this->assertSame([
            'expertise_title' => 'Heart care',
            'expertise_link' => get_permalink($id),
            'expertise_podcast' => $widget,
            'expertise_conditions_list' => '',
            'expertise_treatments_list' => '',
            'expertise_provider_list' => '',
            'expertise_location_list' => '',
        ], get_expertise_meta(['id' => $id]));
    }

    /** Create a resource with the getter-visible ACF radio-field shape. */
    private function createResource(): int
    {
        $id = (int) self::factory()->post->create(['post_type' => 'clinical-resource', 'post_title' => 'Resource title']);
        update_post_meta($id, 'clinical_resource_type', ['value' => 'text', 'label' => 'Article']);

        return $id;
    }

    /** Pin absent fields and distinct populated values without invoking ACF formatting. */
    public function test_resource_scalar_fields_preserve_getter_values(): void
    {
        // Pin the complete baseline, including omitted optional collections.
        $id = $this->createResource();
        $fields = [
            'clinical_resource_excerpt' => 'Short excerpt',
            'clinical_resource_text' => '<p>Resource &amp; text</p>',
            'clinical_resource_text_nci_query' => 'yes',
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
        $expected = [
            'clinical_resource_title' => 'Resource title',
            'clinical_resource_link' => get_permalink($id),
            'clinical_resource_excerpt' => false,
            'clinical_resource_image' => false,
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
        $this->assertSame($expected, get_resource_meta(['id' => $id]));

        // Distinguish crossed fields and verify that ordinary media values pass through.
        foreach ($fields as $field => $value) {
            update_post_meta($id, $field, $value);
            $expected[$field] = $value;
        }

        $this->assertSame($expected, get_resource_meta(['id' => $id]));
    }

    /** @return array<string, array{string, string, string, string}> */
    public static function resourceRelationships(): array
    {
        return [
            'conditions' => ['condition', 'clinical_resource_conditions', 'clinical_resource_conditions', 'clinical_resource_conditions_list'],
            'treatments' => ['treatment', 'clinical_resource_treatments', 'clinical_resource_treatments', 'clinical_resource_treatments_list'],
            'providers' => ['provider', 'clinical_resource_providers', 'clinical_resource_provider', 'clinical_resource_provider_list'],
            'locations' => ['location', 'clinical_resource_locations', 'clinical_resource_location', 'clinical_resource_location_list'],
            'expertise' => ['expertise', 'clinical_resource_aoe', 'clinical_resource_expertise', 'clinical_resource_expertise_list'],
        ];
    }

    /**
     * Pin resource selection, public filtering, title ordering, and delimiters.
     *
     * @dataProvider resourceRelationships
     */
    public function test_resource_relationships_export_only_selected_public_records(
        string $type,
        string $field,
        string $items,
        string $list,
    ): void {
        // Make title order disagree with both selection and provider display-name order.
        $id = $this->createResource();
        $zulu = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Zulu', 'post_name' => 'zulu']);
        $alpha = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Alpha', 'post_name' => 'alpha']);
        $draft = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'draft']);
        $private = (int) self::factory()->post->create(['post_type' => $type, 'post_status' => 'private']);
        $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'publish']);
        self::factory()->post->create(['post_type' => $type, 'post_status' => 'publish', 'post_title' => 'Not selected']);
        update_post_meta($alpha, 'physician_full_name', 'Doctor Z');
        update_post_meta($zulu, 'physician_full_name', 'Doctor A');
        $expected = [
            $alpha => ['link' => get_permalink($alpha), 'title' => $type === 'provider' ? 'Doctor Z' : 'Alpha', 'slug' => 'alpha'],
            $zulu => ['link' => get_permalink($zulu), 'title' => $type === 'provider' ? 'Doctor A' : 'Zulu', 'slug' => 'zulu'],
        ];

        // Cover absent, rejected-only, single, multiple, and mixed selections.
        foreach ([[], [$draft, $private, $page], [$alpha], [$zulu, $alpha], [$zulu, $draft, $alpha, $private, $page], [$alpha, $draft, $private, $page]] as $index => $selected) {
            update_post_meta($id, $field, $selected);
            $data = get_resource_meta(['id' => $id]);
            if ($index < 2) {
                $this->assertArrayNotHasKey($items, $data);
                $this->assertSame('', $data[$list]);
            } else {
                $single_result = $index === 2 || $index === 5;
                $this->assertSame($single_result ? [$alpha => $expected[$alpha]] : $expected, $data[$items]);
                $this->assertSame($type === 'provider' ? ($single_result ? 'Doctor Z' : 'Doctor Z | Doctor A') : ($single_result ? 'Alpha' : 'Alpha, Zulu'), $data[$list]);
            }
        }
    }

    /** Pin related-resource date order separately from ordinary relationship title order. */
    public function test_related_resources_use_descending_dates_and_exclude_rejected_records(): void
    {
        $id = $this->createResource();
        $new = (int) self::factory()->post->create(['post_type' => 'clinical-resource', 'post_status' => 'publish', 'post_title' => 'Alpha', 'post_name' => 'alpha', 'post_date' => '2021-01-01 10:00:00']);
        $old = (int) self::factory()->post->create(['post_type' => 'clinical-resource', 'post_status' => 'publish', 'post_title' => 'Zulu', 'post_name' => 'zulu', 'post_date' => '2020-01-01 10:00:00']);
        $draft = (int) self::factory()->post->create(['post_type' => 'clinical-resource', 'post_status' => 'draft']);
        $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'publish']);
        self::factory()->post->create(['post_type' => 'clinical-resource', 'post_status' => 'publish', 'post_title' => 'Unselected']);

        // Keep the associated collection absent when every selection is rejected.
        update_post_meta($id, 'clinical_resource_related', [$draft, $page]);
        $this->assertArrayNotHasKey('clinical_resource_associated', get_resource_meta(['id' => $id]));

        // Select oldest first so selection or title ordering would fail.
        update_post_meta($id, 'clinical_resource_related', [$old, $draft, $new, $page]);
        $data = get_resource_meta(['id' => $id]);
        $this->assertSame([
            $new => ['link' => get_permalink($new), 'title' => 'Alpha', 'slug' => 'alpha'],
            $old => ['link' => get_permalink($old), 'title' => 'Zulu', 'slug' => 'zulu'],
        ], $data['clinical_resource_associated']);
    }

    /** @return array<string, array{string, string}> */
    public static function documentPermalinks(): array
    {
        return ['with slash' => ['/%postname%/', '/'], 'without slash' => ['/%postname%', '']];
    }

    /**
     * Preserve sparse document keys and the existing permalink-style URL normalization.
     *
     * @dataProvider documentPermalinks
     */
    public function test_resource_documents_require_a_title_and_file_array(
        string $structure,
        string $suffix,
    ): void {
        global $wp_rewrite;

        // Restore the rewrite object's state as well as the test database's options.
        $previous = $wp_rewrite->permalink_structure;
        $this->set_permalink_structure($structure);
        try {
            $id = $this->createResource();
            $invalid = [
                ['document_title' => '', 'document_file' => ['url' => 'https://example.test/skip.pdf']],
                ['document_title' => 'Missing', 'document_file' => false],
                ['document_title' => 'ID only', 'document_file' => 123],
                ['document_title' => 'URL only', 'document_file' => 'https://example.test/raw.pdf'],
            ];
            update_post_meta($id, 'clinical_resource_document', $invalid);
            $this->assertArrayNotHasKey('clinical_resource_document', get_resource_meta(['id' => $id]));

            // Put valid rows at nonconsecutive indexes to expose accidental reindexing.
            $rows = [$invalid[0], ['document_title' => 'Guide', 'document_file' => ['url' => 'https://example.test/guide.pdf']], ...array_slice($invalid, 1), ['document_title' => 'Second', 'document_file' => ['url' => 'https://example.test/second.pdf/']]];
            update_post_meta($id, 'clinical_resource_document', $rows);
            $data = get_resource_meta(['id' => $id]);
            $this->assertSame([
                1 => ['title' => 'Guide', 'url' => 'https://example.test/guide.pdf'.$suffix],
                5 => ['title' => 'Second', 'url' => 'https://example.test/second.pdf'.$suffix],
            ], $data['clinical_resource_document']);
        } finally {
            $this->set_permalink_structure($previous);
        }
    }

    /** Pin actual WordPress large-rendition selection rather than the theme image stand-in. */
    public function test_resource_image_uses_the_large_featured_image(): void
    {
        $id = $this->createResource();
        try {
            $attachment = self::factory()->attachment->create_upload_object(DIR_TESTDATA.'/images/canola.jpg', $id);
            set_post_thumbnail($id, $attachment);
            $metadata = wp_get_attachment_metadata($attachment);
            $metadata['sizes']['large'] = ['file' => 'canola-large.jpg', 'width' => 900, 'height' => 675, 'mime-type' => 'image/jpeg'];
            wp_update_attachment_metadata($attachment, $metadata);
            $expected = dirname(wp_get_attachment_url($attachment)).'/canola-large.jpg';

            $this->assertSame($expected, get_resource_meta(['id' => $id])['clinical_resource_image']);
        } finally {
            $this->remove_added_uploads();
        }
    }

    /** @return array<string, array{string, string, bool}> */
    public static function spotlightStates(): array
    {
        return [
            'ordinary resource' => ['text', 'publish', false],
            'no provider' => ['provider_spotlight', 'missing', false],
            'draft provider' => ['provider_spotlight', 'draft', false],
            'private provider' => ['provider_spotlight', 'private', false],
            'trashed provider' => ['provider_spotlight', 'trash', false],
            'published provider' => ['provider_spotlight', 'publish', true],
        ];
    }

    /**
     * Pin public spotlight gating, generated introduction, and sparse Q&A rows.
     *
     * @dataProvider spotlightStates
     */
    public function test_resource_spotlight_requires_a_published_selection(
        string $type,
        string $status,
        bool $visible,
    ): void {
        // Keep all provider fields distinct from the resource's stored introduction.
        $id = $this->createResource();
        $provider = $status === 'missing' ? 0 : (int) self::factory()->post->create(['post_type' => 'provider', 'post_status' => $status, 'post_name' => 'ada']);
        update_post_meta($provider, 'physician_first_name', 'Ada');
        update_post_meta($provider, 'physician_last_name', 'Lovelace');
        update_post_meta($provider, 'physician_full_name', 'Ada Lovelace, M.D.');
        update_post_meta($id, 'clinical_resource_type', ['value' => $type, 'label' => 'Selected label']);
        update_post_meta($id, 'clinical_resource_spotlight_provider', $provider);
        update_post_meta($id, 'clinical_resource_spotlight_intro', 'Do not export this stored introduction');
        if (! $visible) {
            update_post_meta($id, 'clinical_resource_spotlight_image_wide', 321);
            update_post_meta($id, 'clinical_resource_spotlight_phone', '5015550123');
            update_post_meta($id, 'clinical_resource_spotlight_qa', [['spotlight_qa_question' => 'Private question', 'spotlight_qa_answer' => 'Do not export']]);
        }

        $data = get_resource_meta(['id' => $id]);

        // Reject all spotlight fields together when the selection is not public/eligible.
        $keys = ['clinical_resource_spotlight_provider', 'clinical_resource_spotlight_intro', 'clinical_resource_spotlight_image_wide', 'clinical_resource_spotlight_phone', 'clinical_resource_spotlight_qa'];
        if (! $visible) {
            foreach ($keys as $key) {
                $this->assertArrayNotHasKey($key, $data);
            }

            return;
        }

        $this->assertSame(['id' => $provider, 'link' => get_permalink($provider), 'title' => 'Ada Lovelace, M.D.', 'slug' => 'ada'], $data['clinical_resource_spotlight_provider']);
        $this->assertSame('<p>Get to know Ada Lovelace of UAMS Health. In the Q&amp;A below, Ada Lovelace shares what inspired their work in healthcare, their life outside of medicine, and more.</p>', $data['clinical_resource_spotlight_intro']);
        $this->assertFalse($data['clinical_resource_spotlight_image_wide']);
        $this->assertFalse($data['clinical_resource_spotlight_phone']);
        $this->assertArrayNotHasKey('clinical_resource_spotlight_qa', $data);

        // Skip blank questions without reindexing or discarding false answers.
        update_post_meta($id, 'clinical_resource_spotlight_image_wide', 321);
        update_post_meta($id, 'clinical_resource_spotlight_phone', '5015550123');
        update_post_meta($id, 'clinical_resource_spotlight_qa', [
            ['spotlight_qa_question' => '', 'spotlight_qa_answer' => 'Skip'],
            ['spotlight_qa_question' => 'Why medicine?', 'spotlight_qa_answer' => '<p>First answer</p>'],
            ['spotlight_qa_question' => '', 'spotlight_qa_answer' => false],
            ['spotlight_qa_question' => 'What next?', 'spotlight_qa_answer' => false],
        ]);
        $data = get_resource_meta(['id' => $id]);
        $this->assertSame([1 => ['question' => 'Why medicine?', 'answer' => '<p>First answer</p>'], 3 => ['question' => 'What next?', 'answer' => false]], $data['clinical_resource_spotlight_qa']);
        $this->assertSame('321', $data['clinical_resource_spotlight_image_wide']);
        $this->assertSame('5015550123', $data['clinical_resource_spotlight_phone']);

        // Exercise the real term selector used by the generated occupation title.
        $specialty = (int) self::factory()->term->create(['taxonomy' => 'clinical_title', 'name' => 'Cardiology']);
        update_post_meta($provider, 'physician_title', $specialty);
        update_term_meta($specialty, 'clinical_specialization_title', 'Cardiologist');
        $this->assertSame('<p>Get to know Ada Lovelace, a cardiologist at UAMS Health. In the Q&amp;A below, Ada Lovelace shares what inspired their work in healthcare, their life outside of medicine, and more.</p>', get_resource_meta(['id' => $id])['clinical_resource_spotlight_intro']);
    }
}
