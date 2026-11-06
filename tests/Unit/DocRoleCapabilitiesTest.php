<?php

/**
 * Pins what the Doc Profile Editor and Doc Profile Admin roles can do with providers and locations,
 * and the capability maps of the two post types, including the capabilities that decide whether Edit Flow shows the
 * status control on a published record (#890 removed the delete rights; #889 added the control).
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\when;

final class DocRoleCapabilitiesTest extends UnitTestCase
{
    /** The ten primitive capabilities a post type's `capabilities` map names for read, edit, publish and delete. */
    private const MAP_KEYS = [
        'read_private_posts',
        'edit_posts', 'edit_published_posts', 'edit_others_posts', 'edit_private_posts',
        'publish_posts',
        'delete_posts', 'delete_published_posts', 'delete_others_posts', 'delete_private_posts',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // The file registers its post types on `init` at file scope, so the hook functions must exist while
        // it loads; Brain Monkey provides them only inside a test. It loads once per process (require_once).
        when('add_action')->justReturn(true);
        when('add_filter')->justReturn(true);
        require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';
    }

    /**
     * The roles as add_role() receives them, then as uamswp_fad_sync_doc_role_caps() leaves them.
     *
     * @return array<string, array<int, string>> role => granted capabilities, sorted
     */
    private function roles_after_sync(): array
    {
        $created = [];
        when('add_role')->alias(function ($role, $name, $caps) use (&$created): void {
            $created[$role] = $caps;
        });
        \add_roles_on_plugin_activation();

        $objects = [];
        foreach ($created as $name => $caps) {
            $objects[$name] = new class($caps)
            {
                public function __construct(public $capabilities) {}

                public function add_cap($cap, $grant = true): void
                {
                    $this->capabilities[$cap] = $grant;
                }

                public function remove_cap($cap): void
                {
                    unset($this->capabilities[$cap]);
                }
            };
        }

        when('get_role')->alias(fn ($name): ?object => $objects[$name] ?? null);
        \uamswp_fad_sync_doc_role_caps();

        $granted = [];
        foreach ($objects as $name => $object) {
            $granted[$name] = array_keys(array_filter($object->capabilities));
            sort($granted[$name]);
        }

        return $granted;
    }

    /**
     * @return array<string, array<string, string>> post type => its `capabilities` map
     */
    private function post_type_maps(): array
    {
        $maps = [];
        when('plugin_dir_url')->justReturn('');
        when('register_post_type')->alias(function ($type, $args) use (&$maps): void {
            $maps[$type] = $args['capabilities'];
        });
        \providers();
        \locations();

        return $maps;
    }

    /**
     * @param  array<int, string>  $values
     * @return array<int, string>
     */
    private function sorted(array $values): array
    {
        sort($values);

        return $values;
    }

    public function test_the_two_doc_roles_are_registered_with_these_names(): void
    {
        $names = [];
        when('add_role')->alias(function ($role, $name) use (&$names): void {
            $names[$role] = $name;
        });
        \add_roles_on_plugin_activation();

        $this->assertSame(['doc_editor' => 'Doc Profile Editor', 'doc_admin' => 'Doc Profile Admin'], $names);
    }

    /**
     * Preserve initial grants and explicit denials before synchronization masks them.
     *
     * A missing capability and an explicit false have different stored role maps.
     */
    public function test_new_doc_roles_retain_initial_grants_and_explicit_denials(): void
    {
        // Capture the role maps before the later init callback can modify them.
        $created = [];
        when('add_role')->alias(function ($role, $name, $caps) use (&$created): void {
            $created[$role] = $caps;
        });
        \add_roles_on_plugin_activation();

        // Distinguish explicit stored denials from absent entries for both roles.
        foreach ([
            'doc_editor' => [
                'delete_published_physicians', 'edit_others_physicians', 'publish_physicians',
                'delete_published_locations', 'edit_others_locations', 'publish_locations',
            ],
            'doc_admin' => [
                'delete_physicians', 'delete_others_physicians', 'delete_published_physicians', 'delete_private_physicians',
                'delete_locations', 'delete_others_locations', 'delete_published_locations', 'delete_private_locations',
                'publish_physicians', 'publish_locations',
            ],
        ] as $role => $capabilities) {
            foreach ($capabilities as $capability) {
                $this->assertArrayHasKey($capability, $created[$role]);
                $this->assertFalse($created[$role][$capability], "{$role}: {$capability}");
            }
        }

        // Pin private editing before synchronization can restore a missing grant.
        foreach (['edit_private_physicians', 'edit_private_locations'] as $capability) {
            $this->assertArrayHasKey($capability, $created['doc_admin']);
            $this->assertTrue($created['doc_admin'][$capability], $capability);
        }
    }

    public function test_doc_editor_holds_exactly_these_capabilities(): void
    {
        $this->assertSame($this->sorted([
            'read', 'level_1', 'upload_files', 'edit_files',
            'read_physician', 'edit_physician', 'delete_physician', 'edit_physicians', 'edit_published_physicians', 'read_private_physicians',
            'read_location', 'edit_location', 'delete_location', 'edit_locations', 'edit_published_locations', 'read_private_locations',
        ]), $this->roles_after_sync()['doc_editor']);
    }

    public function test_doc_admin_holds_exactly_these_capabilities(): void
    {
        $this->assertSame($this->sorted([
            'read', 'level_1', 'upload_files', 'edit_files',
            'read_physician', 'edit_physician',
            'edit_physicians', 'edit_published_physicians', 'edit_private_physicians', 'edit_others_physicians', 'read_private_physicians',
            'read_location', 'edit_location',
            'edit_locations', 'edit_published_locations', 'edit_private_locations', 'edit_others_locations', 'read_private_locations',
        ]), $this->roles_after_sync()['doc_admin']);
    }

    public function test_neither_role_can_publish_a_provider_or_a_location(): void
    {
        foreach ($this->roles_after_sync() as $role => $caps) {
            $this->assertNotContains('publish_physicians', $caps, $role);
            $this->assertNotContains('publish_locations', $caps, $role);
        }
    }

    public function test_the_provider_capability_map(): void
    {
        $this->assertSame([
            'edit_post' => 'edit_physician',
            'read_post' => 'read_physician',
            'delete_post' => 'delete_physician',
            'edit_posts' => 'edit_physicians',
            'edit_others_posts' => 'edit_others_physicians',
            'publish_posts' => 'publish_physicians',
            'read_private_posts' => 'read_private_physicians',
            'read' => 'read',
            'delete_posts' => 'delete_physicians',
            'delete_private_posts' => 'delete_private_physicians',
            'delete_published_posts' => 'delete_published_physicians',
            'delete_others_posts' => 'delete_others_physicians',
            'edit_private_posts' => 'edit_private_physicians',
            'edit_published_posts' => 'edit_published_physicians',
        ], $this->post_type_maps()['provider']);
    }

    public function test_the_location_capability_map(): void
    {
        $this->assertSame([
            'edit_post' => 'edit_location',
            'read_post' => 'read_location',
            'delete_post' => 'delete_location',
            'edit_posts' => 'edit_locations',
            'edit_others_posts' => 'edit_others_locations',
            'publish_posts' => 'publish_locations',
            'read_private_posts' => 'read_private_locations',
            'read' => 'read',
            'delete_posts' => 'delete_locations',
            'delete_private_posts' => 'delete_private_locations',
            'delete_published_posts' => 'delete_published_locations',
            'delete_others_posts' => 'delete_others_locations',
            'edit_private_posts' => 'edit_private_locations',
            'edit_published_posts' => 'edit_published_locations',
        ], $this->post_type_maps()['location']);
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: array<int, string>}>
     */
    public static function rights_cases(): array
    {
        $editor = ['read_private_posts', 'edit_posts', 'edit_published_posts'];
        $admin = ['read_private_posts', 'edit_posts', 'edit_published_posts', 'edit_others_posts', 'edit_private_posts'];

        return [
            'doc_editor on providers' => ['doc_editor', 'provider', $editor],
            'doc_editor on locations' => ['doc_editor', 'location', $editor],
            'doc_admin on providers' => ['doc_admin', 'provider', $admin],
            'doc_admin on locations' => ['doc_admin', 'location', $admin],
        ];
    }

    /**
     * Which of read, edit, publish and delete (as WordPress checks them for a post type with map_meta_cap on)
     * each role holds.
     *
     * @param  array<int, string>  $expected  The `capabilities` map keys the role holds
     */
    #[DataProvider('rights_cases')]
    public function test_each_role_holds_these_rights_on_each_post_type(string $role, string $type, array $expected): void
    {
        $map = $this->post_type_maps()[$type];
        $held = $this->roles_after_sync()[$role];

        $granted = array_values(array_filter(self::MAP_KEYS, fn (string $key): bool => in_array($map[$key], $held, true)));

        $this->assertSame($expected, $granted, "{$role} on {$type}");
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function status_cases(): array
    {
        return [
            'doc_editor on providers' => ['doc_editor', 'provider'],
            'doc_editor on locations' => ['doc_editor', 'location'],
            'doc_admin on providers' => ['doc_admin', 'provider'],
            'doc_admin on locations' => ['doc_admin', 'location'],
        ];
    }

    /**
     * Edit Flow decides whether to show the Status dropdown on a published record from the publish and
     * edit-published capabilities of the post type (its script reads current_user_can_publish_posts and
     * current_user_can_edit_published_posts). Both roles hold edit_published_* and neither holds publish_*,
     * so Edit Flow hides the dropdown on the edit screen of a published provider or location for both.
     */
    #[DataProvider('status_cases')]
    public function test_the_capabilities_that_decide_the_status_control_on_a_published_record(string $role, string $type): void
    {
        $map = $this->post_type_maps()[$type];
        $held = $this->roles_after_sync()[$role];

        $this->assertNotContains($map['publish_posts'], $held, "{$role} cannot publish {$type}");
        $this->assertContains($map['edit_published_posts'], $held, "{$role} can edit a published {$type}");
    }
}
