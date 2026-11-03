<?php

/**
 * Against real WordPress: a provider's revision resolves to the saved provider.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * The unit test fakes `wp_is_post_revision()` and `get_post_type()`; this one lets WordPress answer them
 * for a real provider and the revision it saves.
 */
final class ProviderPreviewRevisionTest extends IntegrationTestCase
{
    public function set_up(): void
    {
        parent::set_up();
        $_GET['preview'] = 'true';
    }

    private function preview(int $provider): void
    {
        $_GET['p'] = (string) $provider;
    }

    public function tear_down(): void
    {
        unset($_GET['preview'], $_GET['p']);
        parent::tear_down();
    }

    public function test_a_real_providers_latest_revision_resolves_to_the_provider(): void
    {
        $provider = (int) self::factory()->post->create(['post_type' => 'provider', 'post_status' => 'draft', 'post_title' => 'Alpha']);
        $revision = (int) _wp_put_post_revision($provider);

        $this->preview($provider);

        $this->assertGreaterThan(0, $revision, 'a revision was saved');
        $this->assertNotSame($provider, $revision);
        $this->assertSame($provider, uamswp_fad_preview_keep_saved_post_id($revision, $provider));
        $this->assertSame($provider, uamswp_fad_preview_keep_saved_post_id($provider, $provider));
    }

    public function test_the_filter_is_registered_on_acfs_hook_after_acfs_own_handler_and_resolves_through_it(): void
    {
        $provider = (int) self::factory()->post->create(['post_type' => 'provider', 'post_status' => 'draft']);
        $revision = (int) _wp_put_post_revision($provider);
        $this->preview($provider);

        $this->assertSame(20, has_filter('acf/validate_post_id', 'uamswp_fad_preview_keep_saved_post_id'), 'ACF registers its own handler at priority 10');
        $this->assertSame($provider, apply_filters('acf/validate_post_id', $revision, $provider));
    }

    public function test_a_pages_revision_is_not_changed(): void
    {
        $page = (int) self::factory()->post->create(['post_type' => 'page', 'post_status' => 'draft']);
        $revision = (int) _wp_put_post_revision($page);
        $this->preview($page);

        $this->assertSame($revision, uamswp_fad_preview_keep_saved_post_id($revision, $page));
    }
}
