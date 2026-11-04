<?php

/**
 * The first Integration test: the plugin's content types exist once WordPress has booted.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * Proves the Integration suite end to end: the bootstrap boots WordPress, loads the
 * plugin on `muplugins_loaded`, and `init` has run by the time a test starts.
 */
final class PostTypesTest extends IntegrationTestCase
{
    /**
     * The post types the plugin registers at load.
     *
     * @return array<string, array{string}>
     */
    public static function registeredPostTypes(): array
    {
        return [
            'provider' => ['provider'],
            'location' => ['location'],
        ];
    }

    /**
     * @dataProvider registeredPostTypes
     */
    public function test_the_plugin_registers_its_post_type_at_load(string $post_type): void
    {
        $this->assertTrue(post_type_exists($post_type), "The {$post_type} post type is not registered.");
    }

    public function test_a_post_type_the_plugin_does_not_register_is_reported_missing(): void
    {
        $this->assertFalse(post_type_exists('uamswp-find-a-doc-not-a-post-type'));
    }
}

// cspell:ignore uamswp
