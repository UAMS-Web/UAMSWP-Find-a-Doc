<?php

/**
 * Pins how an editor group changes what a Doc Profile Editor needs to edit a provider or location, before #890
 * and #889 change the roles beside it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/editor-groups.php';

final class EditorGroupCapabilityTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The file registers its hooks at file scope, so the hook functions must exist while it loads; Brain
        // Monkey provides them only inside a test. It loads once per process (require_once).
        \uamswp_test_load_editor_groups();
        require_once dirname(__DIR__, 2).'/includes/class.fad-helper-functions.php';
    }

    /**
     * Stub the post, its post type and the groups. The membership and user lookups are cached per id inside
     * the plugin, so each test uses its own user and group ids.
     *
     * @param  array<int, int>  $group_members  Users in the group (term meta)
     * @param  array<int, int>  $post_groups  Groups the post is in
     */
    private function stub_world(string $post_type, int $group_id, array $group_members, array $post_groups): void
    {
        when('get_post')->justReturn((object) ['ID' => 900, 'post_type' => $post_type]);
        when('get_post_type_object')->justReturn((object) [
            'cap' => (object) [
                'edit_posts' => $post_type === 'provider' ? 'edit_physicians' : 'edit_locations',
                'edit_others_posts' => $post_type === 'provider' ? 'edit_others_physicians' : 'edit_others_locations',
            ],
        ]);
        when('get_terms')->justReturn([$group_id]);
        when('get_term_meta')->alias(fn ($term, $key) => $key === 'editor_group_editors' ? $group_members : []);
        when('wp_get_object_terms')->justReturn($post_groups);
        when('is_wp_error')->justReturn(false);
    }

    public function test_a_group_member_needs_edit_physicians_instead_of_edit_others_on_a_provider(): void
    {
        $this->stub_world('provider', 11, [201], [11]);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians', 'edit_published_physicians'], 'edit_post', 201, [900]);

        $this->assertSame(['edit_physicians', 'edit_published_physicians'], $caps);
    }

    public function test_a_group_member_needs_edit_locations_instead_of_edit_others_on_a_location(): void
    {
        $this->stub_world('location', 12, [202], [12]);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_others_locations', 'edit_published_locations'], 'edit_post', 202, [900]);

        $this->assertSame(['edit_locations', 'edit_published_locations'], $caps);
    }

    public function test_a_user_outside_the_posts_groups_still_needs_edit_others(): void
    {
        $this->stub_world('provider', 13, [203], [99]);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians', 'edit_published_physicians'], 'edit_post', 203, [900]);

        $this->assertSame(['edit_others_physicians', 'edit_published_physicians'], $caps);
    }

    public function test_a_post_with_no_groups_leaves_the_requirements_alone(): void
    {
        $this->stub_world('provider', 14, [204], []);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians'], 'edit_post', 204, [900]);

        $this->assertSame(['edit_others_physicians'], $caps);
    }

    public function test_the_authors_own_post_needs_no_change(): void
    {
        $this->stub_world('provider', 15, [205], [15]);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_physicians', 'edit_published_physicians'], 'edit_post', 205, [900]);

        $this->assertSame(['edit_physicians', 'edit_published_physicians'], $caps);
    }

    public function test_delete_and_publish_are_never_mapped_for_a_group_member(): void
    {
        $this->stub_world('provider', 16, [206], [16]);

        $this->assertSame(['delete_others_physicians'], \uamswp_fad_editor_group_map_meta_cap(['delete_others_physicians'], 'delete_post', 206, [900]));
        $this->assertSame(['publish_physicians'], \uamswp_fad_editor_group_map_meta_cap(['publish_physicians'], 'publish_post', 206, [900]));
    }

    public function test_a_post_type_without_editor_groups_is_left_alone(): void
    {
        $this->stub_world('expertise', 17, [207], [17]);

        $caps = \uamswp_fad_editor_group_map_meta_cap(['edit_others_expertises'], 'edit_post', 207, [900]);

        $this->assertSame(['edit_others_expertises'], $caps);
    }

    public function test_the_editor_group_post_types_are_providers_and_locations(): void
    {
        $this->assertSame(['provider' => 'physician_service_line', 'location' => 'location_service_line'], \uamswp_fad_editor_group_post_types());
    }
}
