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
     * Export a real portal term's name together with its existing content and URL.
     */
    public function test_portal_metadata_includes_the_selected_term_name(): void
    {
        // Supply a location with the getter-visible hours group and valid region.
        $location = (int) self::factory()->post->create(['post_type' => 'location']);
        update_post_meta($location, 'location_region', self::factory()->term->create(['taxonomy' => 'region']));
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
}
