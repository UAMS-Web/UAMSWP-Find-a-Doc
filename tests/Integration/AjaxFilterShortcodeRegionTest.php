<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/acf-post-meta.php';

/**
 * Region dropdown of the provider and location filter shortcodes when posts carry no region.
 *
 * @command  composer test:integration -- --filter AjaxFilterShortcodeRegionTest
 */
final class AjaxFilterShortcodeRegionTest extends IntegrationTestCase
{
    /**
     * Create a region term and return its ID and slug.
     *
     * @return array{0: int, 1: string}
     */
    private function region(string $name): array
    {
        $id = (int) self::factory()->term->create(['taxonomy' => 'region', 'name' => $name]);

        return [$id, (string) get_term($id)->slug];
    }

    /**
     * Whether the dropdown option for a region slug carries the disabled attribute.
     */
    private function optionDisabled(string $html, string $slug): bool
    {
        preg_match('/<option value="'.preg_quote($slug, '/').'"([^>]*)>/', $html, $m);
        self::assertNotEmpty($m, "No option rendered for {$slug}");

        return str_contains($m[1], 'disabled');
    }

    public function test_provider_shortcode_renders_when_no_listed_id_is_a_published_provider(): void
    {
        [, $slug] = $this->region('Central');

        $html = do_shortcode('[uamswp_provider_ajax_filter providers="999999991,999999992"]');

        self::assertStringContainsString('provider-ajax-filter', $html);
        self::assertTrue($this->optionDisabled($html, $slug));
    }

    public function test_provider_with_no_region_value_contributes_none(): void
    {
        [, $slug] = $this->region('North');
        $provider = (int) self::factory()->post->create(['post_type' => 'provider']);

        $html = do_shortcode("[uamswp_provider_ajax_filter providers=\"{$provider}\"]");

        self::assertTrue($this->optionDisabled($html, $slug));
    }

    public function test_provider_region_still_enables_its_option(): void
    {
        [$id, $slug] = $this->region('South');
        [, $other] = $this->region('West');
        $provider = (int) self::factory()->post->create(['post_type' => 'provider']);
        update_field('physician_region', [(string) $id], $provider);

        $html = do_shortcode("[uamswp_provider_ajax_filter providers=\"{$provider}\"]");

        self::assertFalse($this->optionDisabled($html, $slug));
        self::assertTrue($this->optionDisabled($html, $other));
    }

    public function test_location_shortcode_renders_when_no_listed_id_is_a_published_location(): void
    {
        [, $slug] = $this->region('Central');

        $html = do_shortcode('[uamswp_location_ajax_filter locations="999999991,999999992"]');

        self::assertStringContainsString('location-ajax-filter', $html);
        self::assertTrue($this->optionDisabled($html, $slug));
    }

    public function test_location_with_no_region_value_contributes_none(): void
    {
        [, $slug] = $this->region('North');
        $location = (int) self::factory()->post->create(['post_type' => 'location']);

        $html = do_shortcode("[uamswp_location_ajax_filter locations=\"{$location}\"]");

        self::assertTrue($this->optionDisabled($html, $slug));
    }

    public function test_location_region_still_enables_its_option(): void
    {
        [$id, $slug] = $this->region('South');
        [, $other] = $this->region('West');
        $location = (int) self::factory()->post->create(['post_type' => 'location']);
        update_post_meta($location, 'location_region', $id);

        $html = do_shortcode("[uamswp_location_ajax_filter locations=\"{$location}\"]");

        self::assertFalse($this->optionDisabled($html, $slug));
        self::assertTrue($this->optionDisabled($html, $other));
    }
}
