<?php

/**
 * Pins the details of the editor groups file (class.fad-editor-groups.php) that the other two test files leave
 * to chance: the hooks it registers, the text of the fields it adds, the exact arguments of the lookups it makes,
 * and the order and type handling of the groups it compares.
 *
 * The membership lookups cache per id for the life of the process, so every test uses ids no other test uses.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

require_once __DIR__.'/../Support/acf-functions.php';
require_once __DIR__.'/../Support/editor-groups.php';

final class EditorGroupDetailsTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \uamswp_test_acf_functions();
        \uamswp_test_load_editor_groups();

        when('is_wp_error')->alias(static fn (mixed $value): bool => $value === 'WP_ERROR');
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function query(array $values): object
    {
        return new class($values)
        {
            /**
             * @param  array<string, mixed>  $values
             */
            public function __construct(private readonly array $values) {}

            public function is_main_query(): bool
            {
                return true;
            }

            public function get(string $name): mixed
            {
                return $this->values[$name] ?? '';
            }

            public function set(string $name, mixed $value): void {}
        };
    }

    public function test_the_hooks_are_registered_with_their_priorities_and_argument_counts(): void
    {
        $this->assertSame([
            ['add_action', 'init', 'uamswp_fad_register_editor_group_taxonomy', 5],
            ['add_action', 'acf/init', 'uamswp_fad_register_editor_group_fields'],
            ['add_filter', 'map_meta_cap', 'uamswp_fad_editor_group_map_meta_cap', 10, 4],
            ['add_action', 'pre_get_posts', 'uamswp_fad_editor_group_admin_list_scope'],
            ['add_filter', 'posts_where', 'uamswp_fad_editor_group_admin_list_where', 10, 2],
            ['add_action', 'save_post', 'uamswp_fad_inherit_editor_group_on_save', 20, 2],
            ['add_action', 'editor_group_pre_add_form', 'uamswp_fad_editor_group_sync_notice'],
            ['add_action', 'admin_post_uamswp_fad_sync_editor_groups', 'uamswp_fad_sync_editor_groups'],
        ], \uamswp_test_load_editor_groups());
    }

    public function test_the_fields_carry_their_keys_labels_and_instructions(): void
    {
        $groups = [];
        when('acf_add_local_field_group')->alias(static function (array $group) use (&$groups): void {
            $groups[] = $group;
        });

        \uamswp_fad_register_editor_group_fields();

        [$members, $service_line] = $groups;
        $this->assertSame(['field_uamswp_editor_group_primary_editor', 'field_uamswp_editor_group_editors'], array_column($members['fields'], 'key'));
        $this->assertSame(['Primary editor', 'Editors'], array_column($members['fields'], 'label'));
        $this->assertSame(
            [
                'The main contact for this group. Has the same editing rights as the other editors.',
                'Everyone listed here may edit the providers and locations in this group.',
            ],
            array_column($members['fields'], 'instructions')
        );
        $this->assertSame('field_uamswp_service_line_editor_group', $service_line['fields'][0]['key']);
        $this->assertSame('Editor group', $service_line['fields'][0]['label']);
        $this->assertSame(
            'Providers and locations in this service line that have no editor group of their own are placed in this group when they are saved.',
            $service_line['fields'][0]['instructions']
        );
    }

    public function test_the_members_are_read_from_term_meta_by_integer_id_as_single_values(): void
    {
        $calls = [];
        when('get_term_meta')->alias(static function (mixed $term, mixed $key, mixed $single) use (&$calls): mixed {
            $calls[] = [$term, $key, $single];

            return $key === 'editor_group_primary_editor' ? '1' : '';
        });

        $this->assertSame([1], \uamswp_fad_editor_group_members('9101'));
        $this->assertSame([[9101, 'editor_group_primary_editor', true], [9101, 'editor_group_editors', true]], $calls);
    }

    public function test_a_user_given_as_a_string_is_matched_to_the_groups_by_integer(): void
    {
        when('get_terms')->justReturn([9110]);
        when('get_term_meta')->alias(static fn (mixed $term, string $key): mixed => $key === 'editor_group_editors' ? [9102] : '');

        $this->assertSame([9110], \uamswp_fad_user_editor_groups('9102'));
    }

    public function test_the_post_groups_are_read_by_integer_post_id_and_compared_by_integer(): void
    {
        $calls = [];
        when('get_terms')->justReturn([7]);
        when('get_term_meta')->alias(static fn (mixed $term, string $key): mixed => $key === 'editor_group_editors' ? [9103] : '');
        when('wp_get_object_terms')->alias(static function (mixed $post, string $taxonomy, array $args) use (&$calls): array {
            $calls[] = [$post, $taxonomy, $args];

            return ['07'];
        });

        $this->assertTrue(\uamswp_fad_user_in_post_editor_group(9103, '9120'));
        $this->assertSame([[9120, 'editor_group', ['fields' => 'ids']]], $calls);
    }

    private function map_world(): void
    {
        when('get_post')->justReturn((object) ['ID' => 900, 'post_type' => 'provider']);
        when('get_post_type_object')->justReturn((object) ['cap' => (object) ['edit_posts' => 'edit_physicians', 'edit_others_posts' => 'edit_others_physicians']]);
        when('get_terms')->justReturn([9141]);
        when('get_term_meta')->alias(static fn (mixed $term, string $key): mixed => $key === 'editor_group_editors' ? [9104] : '');
        when('wp_get_object_terms')->justReturn([9141]);
    }

    public function test_a_group_member_keeps_the_capabilities_of_a_post_that_needs_no_others_requirement(): void
    {
        $this->map_world();

        $this->assertSame(
            ['edit_published_physicians'],
            \uamswp_fad_editor_group_map_meta_cap(['edit_published_physicians'], 'edit_post', 9104, [900])
        );
    }

    public function test_the_mapped_capabilities_are_strings(): void
    {
        $this->map_world();

        $this->assertSame(
            ['edit_physicians', '7'],
            \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians', 7], 'edit_post', 9104, [900])
        );
    }

    public function test_the_admin_list_asks_for_the_term_taxonomy_ids_of_the_users_groups(): void
    {
        $lookups = [];
        when('is_admin')->justReturn(true);
        when('get_post_type_object')->justReturn((object) ['cap' => (object) ['edit_others_posts' => 'edit_others_physicians']]);
        when('current_user_can')->justReturn(false);
        when('get_current_user_id')->justReturn(9105);
        when('get_terms')->alias(static function (array $args) use (&$lookups): array {
            $lookups[] = $args;

            return ($args['fields'] ?? '') === 'tt_ids' ? [51] : [9150];
        });
        when('get_term_meta')->alias(static fn (mixed $term, string $key): mixed => $key === 'editor_group_editors' ? [9105] : '');

        \uamswp_fad_editor_group_admin_list_scope($this->query(['post_type' => 'provider']));

        $this->assertSame(
            ['taxonomy' => 'editor_group', 'include' => [9150], 'hide_empty' => false, 'fields' => 'tt_ids'],
            $lookups[1]
        );
    }

    private function where(mixed $tt_ids): string
    {
        global $wpdb;
        $previous = $wpdb;
        $wpdb = new class
        {
            public string $posts = 'wp_posts';

            public string $term_relationships = 'wp_term_relationships';

            public function prepare(string $query, int $user): string
            {
                return str_replace('%d', (string) $user, $query);
            }
        };

        try {
            return \uamswp_fad_editor_group_admin_list_where('', $this->query(['uamswp_editor_group_tt_ids' => $tt_ids, 'uamswp_editor_group_user' => 9106]));
        } finally {
            $wpdb = $previous;
        }
    }

    public function test_the_where_clause_lists_a_single_id_and_turns_every_id_into_an_integer(): void
    {
        $this->assertStringContainsString('term_taxonomy_id IN ( 51 )', $this->where('51'));
        $this->assertStringContainsString('term_taxonomy_id IN ( 3,4 )', $this->where(['3', '4) OR 1=1']));
    }

    public function test_a_service_line_term_without_an_id_is_skipped_and_the_group_is_read_by_integer_id(): void
    {
        $calls = [];
        when('get_post_type')->justReturn('provider');
        when('get_field')->justReturn([(object) [], (object) ['term_id' => 9150]]);
        when('get_term_meta')->alias(static function (mixed $term, mixed $key, mixed $single) use (&$calls): mixed {
            $calls[] = [$term, $key, $single];

            return 1;
        });
        when('term_exists')->justReturn(['term_id' => 1]);

        $this->assertSame([1], \uamswp_fad_editor_groups_from_service_line(900));
        $this->assertSame([[9150, 'service_line_editor_group', true]], $calls);
    }

    /**
     * @param  list<array<int, mixed>>  $calls
     */
    private function inherit_world(mixed $current, mixed $previous, array &$calls): void
    {
        when('wp_get_object_terms')->alias(static function (mixed $post, string $taxonomy, array $args) use ($current, &$calls): mixed {
            $calls[] = ['terms', $post, $taxonomy, $args];

            return $current;
        });
        when('get_post_meta')->alias(static function (mixed $post, string $key, mixed $single) use ($previous, &$calls): mixed {
            $calls[] = ['meta', $post, $key, $single];

            return $previous;
        });
        when('get_post_type')->justReturn('provider');
        when('get_field')->justReturn([5]);
        when('get_term_meta')->justReturn(81);
        when('term_exists')->justReturn(['term_id' => 81]);
    }

    public function test_the_inheritance_reads_the_groups_and_the_remembered_groups_by_integer_post_id(): void
    {
        $calls = [];
        $this->inherit_world([81], [81], $calls);
        expect('update_post_meta')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group('9160'));
        $this->assertSame(
            [['terms', 9160, 'editor_group', ['fields' => 'ids']], ['meta', 9160, '_uamswp_editor_groups_derived', true]],
            $calls
        );
    }

    public function test_remembered_groups_stored_as_strings_count_as_unchanged(): void
    {
        $calls = [];
        $this->inherit_world([81], ['81'], $calls);
        expect('update_post_meta')->never();
        expect('wp_set_object_terms')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group(9161));
    }

    public function test_the_same_groups_in_another_order_are_not_a_change(): void
    {
        $calls = [];
        $this->inherit_world([50, 40], [], $calls);
        when('get_term_meta')->justReturn(40);
        when('term_exists')->justReturn(['term_id' => 40]);

        expect('wp_set_object_terms')->never();
        when('update_post_meta')->justReturn(true);

        $this->assertFalse(\uamswp_fad_inherit_editor_group(9162));
    }
}
