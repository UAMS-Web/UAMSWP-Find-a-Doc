<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;
use WP_Term;

require_once __DIR__.'/../Support/acf-post-meta.php';
require_once __DIR__.'/../Support/theme-images.php';
require_once __DIR__.'/../Support/theme-phones.php';

/**
 * Location metadata contracts using getter-visible ACF values in WordPress.
 *
 * @command  composer test:integration -- --filter LocationMetadataTest
 */
final class LocationMetadataTest extends IntegrationTestCase
{
    /**
     * Supply getter-visible required fields without loading ACF or the theme.
     */
    private function createLocation(): int
    {
        // Supply a location with the getter-visible hours group and valid region.
        $location = (int) self::factory()->post->create(['post_type' => 'location']);
        update_post_meta($location, 'location_region', self::factory()->term->create(['taxonomy' => 'region']));
        update_post_meta($location, 'location_portal', self::factory()->term->create(['taxonomy' => 'portal']));
        update_post_meta($location, 'location_hours_group', array_fill_keys([
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

        return $location;
    }

    /**
     * Export a real portal term's name together with its existing content and URL.
     */
    public function test_portal_metadata_includes_the_selected_term_name(): void
    {
        $location = $this->createLocation();

        // Change the selected term to distinguish stale data and constant names.
        foreach ([
            ['name' => 'MyChart Patient Portal', 'content' => 'Sign in to your account', 'url' => 'https://example.test/portal'],
            ['name' => 'Specialty Care Access', 'content' => 'View specialty appointments', 'url' => 'https://example.test/specialty'],
        ] as $expected) {
            // Store the taxonomy ID shape declared by the location's ACF portal field.
            $portal = (int) self::factory()->term->create(['taxonomy' => 'portal', 'name' => $expected['name']]);
            update_post_meta($location, 'location_portal', $portal);
            update_term_meta($portal, 'portal_content', $expected['content']);
            update_term_meta($portal, 'portal_url', $expected['url']);
            $this->assertInstanceOf(WP_Term::class, get_term($portal, 'portal'));

            // Compare literal expected fields without deriving them from lookup.
            $data = get_location_meta(['id' => $location]);
            $this->assertSame($expected, $data['location_portal']);
        }
    }

    /**
     * Resolve parent and child address components through the real WordPress reader.
     */
    public function test_address_fields_use_the_actual_resolved_parent_components(): void
    {
        // Configure the inheritance switches before the resolver's first cached read.
        $parent = $this->createLocation();
        $child = $this->createLocation();
        $building = self::factory()->term->create(['taxonomy' => 'building', 'name' => 'Child Tower']);
        foreach ([
            'location_parent' => true, 'location_parent_id' => $parent,
            'location_address_override_parent' => true,
            'location_address_override_parent_street' => false,
            'location_address_override_parent_facility' => true,
            'location_address_override_parent_unit' => false,
            'location_address_override_parent_map' => true,
            'location_parking_override_parent' => true,
            'location_parking_override_parent_directions' => true,
            'location_address_1' => 'Unused child street', 'location_city' => 'Unused child city',
            'location_building' => $building, 'location_suite' => 'Unused child suite',
            'location_map' => ['lat' => 34.71, 'lng' => -92.32],
            'location_direction' => '<p>Child directions</p>',
        ] as $field => $value) {
            update_post_meta($child, $field, $value);
        }

        foreach ([
            'location_address_1' => 'Parent street', 'location_address_2' => 'Parent entrance',
            'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205',
            'location_suite' => 'Parent suite', 'location_map' => ['lat' => 35.8, 'lng' => -93.4],
            'location_direction' => '<p>Unused parent directions</p>',
        ] as $field => $value) {
            update_post_meta($parent, $field, $value);
        }

        // Check the public callback, not just the resolver's intermediate IDs.
        $data = get_location_meta(['id' => $child]);
        foreach ([
            'location_address_1' => 'Parent street', 'location_address_2' => 'Parent entrance<br/>',
            'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205',
            'location_suite' => 'Parent suite', 'location_building' => 'Child Tower',
            'location_lat' => 34.71, 'location_lng' => -92.32,
            'location_direction' => '<p>Child directions</p>',
        ] as $key => $expected) {
            $this->assertSame($expected, $data[$key], $key);
        }

        $this->assertSame((string) $parent, $data['location_parent']['id']);
        $this->assertSame(get_the_title($parent), $data['location_parent']['title']);
        $this->assertSame(user_trailingslashit(get_permalink($parent)), $data['location_parent']['url']);
    }

    /**
     * Ignore enabled component overrides when their master switch is disabled.
     */
    public function test_disabled_master_switches_keep_parent_map_and_directions(): void
    {
        // Use fresh IDs because component sources are cached for the request.
        $parent = $this->createLocation();
        $child = $this->createLocation();
        foreach ([
            'location_parent' => true, 'location_parent_id' => $parent,
            'location_address_override_parent' => false,
            'location_address_override_parent_map' => true,
            'location_parking_override_parent' => false,
            'location_parking_override_parent_directions' => true,
            'location_map' => ['lat' => 34.71, 'lng' => -92.32],
            'location_direction' => '<p>Unused child directions</p>',
        ] as $field => $value) {
            update_post_meta($child, $field, $value);
        }

        update_post_meta($parent, 'location_map', ['lat' => 35.8, 'lng' => -93.4]);
        update_post_meta($parent, 'location_direction', '<p>Parent directions</p>');

        // Require both master switches, not just the per-component toggles.
        $data = get_location_meta(['id' => $child]);
        $this->assertSame([35.8, -93.4], [$data['location_lat'], $data['location_lng']]);
        $this->assertSame('<p>Parent directions</p>', $data['location_direction']);
    }

    /**
     * Read hours repeaters through WordPress post meta and retain public row shapes.
     */
    public function test_hours_keep_distinct_rows_and_reindex_sparse_getter_values(): void
    {
        // Store complete getter-visible rows rather than the ACF storage schema.
        $location = $this->createLocation();
        $group = get_post_meta($location, 'location_hours_group', true);
        $group['location_hours'] = [
            4 => ['day' => 'Friday', 'closed' => false, 'open' => '09:00', 'close' => '16:30', 'comment' => '<b>Call first</b>'],
            7 => ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => '', 'comment' => false],
        ];
        $group['location_modified_hours_group'] = [
            6 => ['location_modified_hours_title' => 'Holiday', 'location_modified_hours_information' => '<p>Emergency only</p>', 'location_modified_hours_times' => '', 'location_modified_hours_24_7' => true],
            9 => ['location_modified_hours_title' => 'Maintenance', 'location_modified_hours_information' => false, 'location_modified_hours_times' => '12:00-15:00', 'location_modified_hours_24_7' => false],
        ];
        update_post_meta($location, 'location_hours_group', $group);

        // Compare literal output rows, including false values and zero-based indexes.
        $data = get_location_meta(['id' => $location]);
        $this->assertSame([
            ['day' => 'Friday', 'closed' => false, 'open' => '09:00', 'close' => '16:30', 'comment' => '<b>Call first</b>'],
            ['day' => 'Monday', 'closed' => true, 'open' => '', 'close' => '', 'comment' => false],
        ], $data['location_hours']);
        $this->assertSame([
            ['title' => 'Holiday', 'information' => '<p>Emergency only</p>', 'times' => '', '24_7' => true],
            ['title' => 'Maintenance', 'information' => false, 'times' => '12:00-15:00', '24_7' => false],
        ], $data['location_modified_hours']);
    }
}
