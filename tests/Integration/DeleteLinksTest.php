<?php

/**
 * Permanently deleting a provider or a location removes it from the records linked to it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';

/**
 * A permanent delete removes the deleted ID from the relationship lists and custom-table link
 * columns of the records that link to it, and deletes its own row in the custom tables. Before
 * the cleanup, the lists and the row stayed, and the spotlight sync was the only plugin hook on
 * a delete; the first version of this test file pinned that.
 *
 * A custom table's link column is ACF Custom Database Tables' copy of a field's value, written
 * when `update_field()` runs. The stand-in `update_field()` does not write it, so the tests seed
 * the column the cleanup searches by and read back the field it mirrors; a table is read for the
 * record's own row only.
 *
 * Linked records hold the lists as post meta in ACF's storage shape (see
 * tests/Support/acf-post-meta.php). The custom tables are created per test as temporary
 * tables with the columns the plugin's link fields use.
 */
final class DeleteLinksTest extends IntegrationTestCase
{
    private string $providers_table;

    private string $locations_table;

    public function set_up(): void
    {
        parent::set_up();

        global $wpdb;

        $this->providers_table = $wpdb->prefix.'uamswp_physicians';
        $this->locations_table = $wpdb->prefix.'uamswp_locations';

        // WordPress' test case turns CREATE TABLE into CREATE TEMPORARY TABLE for the length of a test.
        foreach ([$this->providers_table, $this->locations_table] as $table) {
            $wpdb->query("CREATE TABLE {$table} (post_id bigint(20) unsigned NOT NULL, physician_locations longtext, PRIMARY KEY (post_id))");
        }
    }

    public function tear_down(): void
    {
        global $wpdb;

        // Before the parent removes the filter that makes DROP TABLE drop the temporary table only.
        foreach ([$this->providers_table, $this->locations_table] as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$table}");
        }

        parent::tear_down();
    }

    public function test_deleting_a_provider_removes_its_id_from_the_lists_that_link_to_it(): void
    {
        $provider = self::factory()->post->create(['post_type' => 'provider']);
        $condition = self::factory()->post->create(['post_type' => 'condition']);
        $location = self::factory()->post->create(['post_type' => 'location']);

        update_field('condition_physicians', [$provider], $condition);
        update_field('physician_locations', [$provider], $location);
        $this->insertRow($this->locations_table, $location, [$provider]);

        wp_delete_post($provider, true);

        $this->assertNull(get_post($provider), 'The provider is deleted.');
        $this->assertSame([], get_field('condition_physicians', $condition));
        $this->assertSame([], get_field('physician_locations', $location));
    }

    public function test_deleting_a_location_removes_its_id_from_the_lists_that_link_to_it(): void
    {
        $location = self::factory()->post->create(['post_type' => 'location']);
        $treatment = self::factory()->post->create(['post_type' => 'treatment']);
        $provider = self::factory()->post->create(['post_type' => 'provider']);

        update_field('treatment_procedure_locations', [$location], $treatment);
        update_field('physician_locations', [$location], $provider);
        $this->insertRow($this->providers_table, $provider, [$location]);

        wp_delete_post($location, true);

        $this->assertNull(get_post($location), 'The location is deleted.');
        $this->assertSame([], get_field('treatment_procedure_locations', $treatment));
        $this->assertSame([], get_field('physician_locations', $provider));
    }

    public function test_deleting_a_provider_deletes_its_own_custom_table_row(): void
    {
        $provider = self::factory()->post->create(['post_type' => 'provider']);
        $this->insertRow($this->providers_table, $provider, []);

        wp_delete_post($provider, true);

        $this->assertSame(0, $this->rowCount($this->providers_table, $provider));
    }

    public function test_deleting_a_location_deletes_its_own_custom_table_row(): void
    {
        $location = self::factory()->post->create(['post_type' => 'location']);
        $this->insertRow($this->locations_table, $location, []);

        wp_delete_post($location, true);

        $this->assertSame(0, $this->rowCount($this->locations_table, $location));
    }

    public function test_deleting_a_provider_spotlight_releases_the_featured_provider_link(): void
    {
        $provider = self::factory()->post->create(['post_type' => 'provider']);
        $spotlight = self::factory()->post->create(['post_type' => 'clinical-resource']);

        update_field('clinical_resource_type', 'provider_spotlight', $spotlight);
        update_field('clinical_resource_spotlight_provider', (string) $provider, $spotlight);
        update_field('clinical_resource_providers', [$provider], $spotlight);
        update_field('physician_clinical_resources', [$spotlight], $provider);

        $this->assertNotFalse(has_action('before_delete_post', 'uamswp_spotlight_delete_sync'), 'The sync is hooked.');

        wp_delete_post($spotlight, true);

        $this->assertSame([], get_field('physician_clinical_resources', $provider));
    }

    public function test_the_plugin_delete_hooks_are_the_spotlight_sync_and_the_cleanup(): void
    {
        $this->assertSame(['uamswp_spotlight_delete_sync', 'uamswp_delete_cleanup_before_delete'], $this->pluginCallbacks('before_delete_post'));
        $this->assertSame(['uamswp_delete_cleanup_after_delete'], $this->pluginCallbacks('deleted_post'));
    }

    /**
     * The names of the plugin's own functions hooked to an action, in priority order.
     *
     * @return array<int, string>
     */
    private function pluginCallbacks(string $hook): array
    {
        global $wp_filter;

        $names = [];

        foreach ($wp_filter[$hook]->callbacks ?? [] as $callbacks) {
            foreach ($callbacks as $callback) {
                if (is_string($callback['function']) && str_starts_with($callback['function'], 'uamswp_')) {
                    $names[] = $callback['function'];
                }
            }
        }

        return $names;
    }

    /**
     * @param  array<int, int>  $linked
     */
    private function insertRow(string $table, int $post_id, array $linked): void
    {
        global $wpdb;

        $wpdb->insert($table, ['post_id' => $post_id, 'physician_locations' => (string) wp_json_encode($linked)]);
    }

    private function rowCount(string $table, int $post_id): int
    {
        global $wpdb;

        return (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE post_id = %d", $post_id));
    }
}

// cspell:ignore uamswp
