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
}
