<?php

/**
 * Pins the editor group taxonomy, fields, membership lookups, list scoping, service line inheritance and
 * backfill screen (class.fad-editor-groups.php).
 *
 * The capability mapping has its own cases in EditorGroupCapabilityTest; the cases here cover the branches
 * that file leaves out. The membership lookups cache per id for the life of the process, so every test uses
 * ids no other test uses.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class EditorGroupsTest extends UnitTestCase
{
    /** @var array<string, mixed> */
    private array $get_before = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->get_before = $_GET;

        Functions\when('add_action')->justReturn(true);
        Functions\when('add_filter')->justReturn(true);
        require_once dirname(__DIR__, 2).'/includes/class.fad-editor-groups.php';

        // A marker string stands in for a WP_Error, which the unit process does not load.
        Functions\when('is_wp_error')->alias(static fn (mixed $value): bool => $value === 'WP_ERROR');
    }

    protected function tearDown(): void
    {
        $_GET = $this->get_before;

        parent::tearDown();
    }

    /**
     * @param  array<string, mixed>  $values  Term meta by key
     */
    private function term_meta(array $values): void
    {
        Functions\when('get_term_meta')->alias(static fn (int $term, string $key): mixed => $values[$key] ?? '');
    }

    public function test_the_post_types_map_to_their_service_line_fields(): void
    {
        $this->assertSame(
            ['provider' => 'physician_service_line', 'location' => 'location_service_line'],
            \uamswp_fad_editor_group_post_types()
        );
        $this->assertSame('editor_group', UAMSWP_FAD_EDITOR_GROUP_TAX);
    }

    public function test_the_taxonomy_is_a_private_hierarchical_one_for_providers_and_locations(): void
    {
        $captured = [];
        Functions\when('register_taxonomy')->alias(static function (string $taxonomy, mixed $types, array $args) use (&$captured): void {
            $captured = [$taxonomy, $types, $args];
        });

        \uamswp_fad_register_editor_group_taxonomy();

        [$taxonomy, $types, $args] = $captured;
        $this->assertSame('editor_group', $taxonomy);
        $this->assertSame(['provider', 'location'], $types);

        $labels = $args['labels'];
        unset($args['labels']);
        $this->assertSame('Editor Groups', $labels['name']);
        $this->assertSame('Editor Group', $labels['singular_name']);
        $this->assertSame('Editor Group', $labels['name_admin_bar']);
        $this->assertCount(15, $labels);
        $this->assertSame([
            'description' => 'Which Doc Profile Editors may edit these providers and locations.',
            'public' => false,
            'publicly_queryable' => false,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
            'show_in_quick_edit' => true,
            'show_admin_column' => true,
            'show_in_rest' => false,
            'query_var' => false,
            'rewrite' => false,
            'capabilities' => [
                'manage_terms' => 'manage_options',
                'edit_terms' => 'manage_options',
                'delete_terms' => 'manage_options',
                'assign_terms' => 'edit_others_physicians',
            ],
        ], $args);
    }

    public function test_the_fields_are_added_only_once_acf_can_add_them(): void
    {
        // No other test defines the ACF function, so this runs while it is still absent.
        $this->assertFalse(function_exists('acf_add_local_field_group'));
        \uamswp_fad_register_editor_group_fields();

        $groups = [];
        Functions\when('acf_add_local_field_group')->alias(static function (array $group) use (&$groups): void {
            $groups[] = $group;
        });

        \uamswp_fad_register_editor_group_fields();

        $this->assertSame(['group_uamswp_editor_group', 'group_uamswp_service_line_editor_group'], array_column($groups, 'key'));

        [$members, $service_line] = $groups;
        $this->assertSame('Editor Group Members', $members['title']);
        $this->assertSame(
            ['editor_group_primary_editor' => 0, 'editor_group_editors' => 1],
            array_column($members['fields'], 'multiple', 'name')
        );
        foreach ($members['fields'] as $field) {
            $this->assertSame('user', $field['type']);
            $this->assertSame(['doc_editor', 'doc_admin'], $field['role']);
            $this->assertSame('id', $field['return_format']);
            $this->assertSame(1, $field['allow_null']);
        }
        $this->assertSame([[['param' => 'taxonomy', 'operator' => '==', 'value' => 'editor_group']]], $members['location']);
        $this->assertSame('normal', $members['position']);

        $this->assertSame('Editor Group', $service_line['title']);
        $field = $service_line['fields'][0];
        $this->assertSame('service_line_editor_group', $field['name']);
        $this->assertSame('taxonomy', $field['type']);
        $this->assertSame('editor_group', $field['taxonomy']);
        $this->assertSame('select', $field['field_type']);
        $this->assertSame([1, 0, 0, 0, 'id'], [$field['allow_null'], $field['add_term'], $field['save_terms'], $field['load_terms'], $field['return_format']]);
        $this->assertSame([[['param' => 'taxonomy', 'operator' => '==', 'value' => 'service_line']]], $service_line['location']);
        $this->assertSame('normal', $service_line['position']);
    }

    public function test_the_members_are_the_primary_editor_and_the_editors_without_repeats_or_bad_ids(): void
    {
        $this->term_meta(['editor_group_primary_editor' => '301', 'editor_group_editors' => [301, '302', 0, -4, 'x', 303]]);

        $this->assertSame([301, 302, 303], \uamswp_fad_editor_group_members(701));
    }

    public function test_a_group_without_meta_has_no_members_and_a_numeric_string_id_is_cast(): void
    {
        $this->term_meta([]);

        $this->assertSame([], \uamswp_fad_editor_group_members('702'));
    }

    public function test_a_groups_members_are_read_once(): void
    {
        Functions\expect('get_term_meta')->twice()->andReturn(7);

        $this->assertSame([7], \uamswp_fad_editor_group_members(703));
        $this->assertSame([7], \uamswp_fad_editor_group_members(703));
    }

    public function test_a_user_of_zero_belongs_to_no_group(): void
    {
        Functions\expect('get_terms')->never();

        $this->assertSame([], \uamswp_fad_user_editor_groups(0));
        $this->assertSame([], \uamswp_fad_user_editor_groups('0'));
    }

    public function test_a_user_belongs_to_the_groups_that_list_them(): void
    {
        Functions\expect('get_terms')->twice()->with([
            'taxonomy' => 'editor_group',
            'hide_empty' => false,
            'fields' => 'ids',
        ])->andReturn([711, '712', 713]);
        Functions\when('get_term_meta')->alias(static fn (int $term, string $key): mixed => match (true) {
            $term === 711 && $key === 'editor_group_editors' => [801, 802],
            $term === 712 && $key === 'editor_group_primary_editor' => 802,
            default => '',
        });

        $this->assertSame([711, 712], \uamswp_fad_user_editor_groups(802));
        $this->assertSame([711, 712], \uamswp_fad_user_editor_groups('802'), 'the second lookup is cached');
        $this->assertSame([711], \uamswp_fad_user_editor_groups(801));
    }

    public function test_a_failed_group_lookup_leaves_the_user_in_no_group(): void
    {
        Functions\when('get_terms')->justReturn('WP_ERROR');

        $this->assertSame([], \uamswp_fad_user_editor_groups(803));
    }

    public function test_a_user_in_no_group_is_not_in_a_posts_group_and_the_post_is_not_read(): void
    {
        Functions\when('get_terms')->justReturn([]);
        Functions\expect('wp_get_object_terms')->never();

        $this->assertFalse(\uamswp_fad_user_in_post_editor_group(804, 900));
    }

    public function test_a_post_in_none_of_the_groups_or_a_failed_read_is_not_in_the_users_group(): void
    {
        Functions\when('get_terms')->justReturn([721]);
        $this->term_meta(['editor_group_editors' => [805]]);

        Functions\when('wp_get_object_terms')->justReturn('WP_ERROR');
        $this->assertFalse(\uamswp_fad_user_in_post_editor_group(805, 900));

        Functions\when('wp_get_object_terms')->justReturn([]);
        $this->assertFalse(\uamswp_fad_user_in_post_editor_group(805, 900));

        Functions\when('wp_get_object_terms')->justReturn([722]);
        $this->assertFalse(\uamswp_fad_user_in_post_editor_group(805, 900));
    }

    public function test_a_post_in_one_of_the_users_groups_is_in_the_users_group(): void
    {
        Functions\when('get_terms')->justReturn([731]);
        $this->term_meta(['editor_group_editors' => [806]]);
        Functions\expect('wp_get_object_terms')->once()->with(900, 'editor_group', ['fields' => 'ids'])->andReturn(['731', 99]);

        $this->assertTrue(\uamswp_fad_user_in_post_editor_group(806, '900'));
    }

    private function map_world(string $post_type = 'provider', bool $has_type = true, bool $in_group = true): void
    {
        Functions\when('get_post')->justReturn((object) ['ID' => 900, 'post_type' => $post_type]);
        Functions\when('get_post_type_object')->justReturn($has_type ? (object) ['cap' => (object) ['edit_posts' => 'edit_physicians', 'edit_others_posts' => 'edit_others_physicians']] : null);
        // The member lists are cached per group, so a group that excludes the user is a different group.
        $group = $in_group ? 741 : 742;
        Functions\when('get_terms')->justReturn([$group]);
        $this->term_meta(['editor_group_editors' => $in_group ? [807] : [808]]);
        Functions\when('wp_get_object_terms')->justReturn([$group]);
    }

    public function test_the_capability_map_leaves_other_capabilities_and_missing_posts_alone(): void
    {
        $this->map_world();
        $caps = ['edit_others_physicians'];

        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'delete_post', 807, [900]));
        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'edit_post', 807, []));
        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'edit_post', 807, [0]));

        Functions\when('get_post')->justReturn(null);
        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'edit_post', 807, [900]));
    }

    public function test_the_capability_map_leaves_other_post_types_and_unknown_types_alone(): void
    {
        $caps = ['edit_others_physicians'];

        $this->map_world('page');
        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'edit_post', 807, [900]));

        $this->map_world('provider', false);
        $this->assertSame($caps, \uamswp_fad_editor_group_map_meta_cap($caps, 'edit_post', 807, [900]));
    }

    public function test_the_capability_map_changes_nothing_without_the_others_requirement_or_the_group(): void
    {
        $this->map_world();
        $this->assertSame(['edit_physicians'], \uamswp_fad_editor_group_map_meta_cap(['edit_physicians'], 'edit_post', 807, [900]));

        $this->map_world('provider', true, false);
        $this->assertSame(['edit_others_physicians'], \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians'], 'edit_post', 820, [900]));
    }

    public function test_the_capability_map_swaps_the_others_requirement_and_drops_a_repeat(): void
    {
        $this->map_world();

        $this->assertSame(
            ['edit_physicians', 'edit_published_physicians'],
            \uamswp_fad_editor_group_map_meta_cap(['edit_others_physicians', 'edit_physicians', 'edit_published_physicians'], 'edit_post', '807', ['900'])
        );
    }

    /**
     * @param  array<string, mixed>  $values
     */
    private function query(array $values, bool $main = true): object
    {
        return new class($values, $main)
        {
            /** @var array<string, mixed> */
            public array $set = [];

            /**
             * @param  array<string, mixed>  $values
             */
            public function __construct(private readonly array $values, private readonly bool $main) {}

            public function is_main_query(): bool
            {
                return $this->main;
            }

            public function get(string $name): mixed
            {
                return $this->values[$name] ?? '';
            }

            public function set(string $name, mixed $value): void
            {
                $this->set[$name] = $value;
            }
        };
    }

    private function list_world(bool $can_edit_others = false, mixed $tt_ids = [51, '52'], int $user = 809): void
    {
        Functions\when('is_admin')->justReturn(true);
        Functions\when('get_post_type_object')->justReturn((object) ['cap' => (object) ['edit_others_posts' => 'edit_others_physicians']]);
        Functions\when('current_user_can')->justReturn($can_edit_others);
        Functions\when('get_current_user_id')->justReturn($user);
        Functions\when('get_terms')->alias(static fn (array $args): mixed => ($args['fields'] ?? '') === 'tt_ids' ? $tt_ids : [751]);
        Functions\when('get_term_meta')->alias(static fn (int $term, string $key): mixed => $key === 'editor_group_editors' ? [$user] : '');
    }

    public function test_the_admin_list_is_scoped_to_the_groups_of_an_editor_who_cannot_edit_others(): void
    {
        $this->list_world();
        $query = $this->query(['post_type' => 'provider']);

        \uamswp_fad_editor_group_admin_list_scope($query);

        $this->assertSame(['author' => '', 'uamswp_editor_group_tt_ids' => [51, 52], 'uamswp_editor_group_user' => 809], $query->set);
    }

    public function test_the_admin_list_is_left_alone_outside_the_admin_and_the_main_query(): void
    {
        $this->list_world();

        Functions\when('is_admin')->justReturn(false);
        $query = $this->query(['post_type' => 'provider']);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);

        Functions\when('is_admin')->justReturn(true);
        $query = $this->query(['post_type' => 'provider'], false);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);
    }

    public function test_the_admin_list_is_left_alone_for_other_post_types(): void
    {
        $this->list_world();

        foreach (['page', ['provider'], '', null] as $post_type) {
            $query = $this->query(['post_type' => $post_type]);
            \uamswp_fad_editor_group_admin_list_scope($query);
            $this->assertSame([], $query->set);
        }
    }

    public function test_the_admin_list_is_left_alone_for_a_missing_type_or_a_user_who_can_edit_others(): void
    {
        $this->list_world();
        Functions\when('get_post_type_object')->justReturn(null);
        $query = $this->query(['post_type' => 'provider']);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);

        $this->list_world(true);
        Functions\expect('current_user_can')->never();
        $query = $this->query(['post_type' => 'provider']);
        Functions\when('current_user_can')->justReturn(true);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);
    }

    public function test_the_admin_list_is_left_alone_for_a_user_in_no_group_or_with_no_term_taxonomy_ids(): void
    {
        $this->list_world();
        Functions\when('get_terms')->justReturn([]);
        $query = $this->query(['post_type' => 'provider']);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);

        $this->list_world(false, 'WP_ERROR', 810);
        $query = $this->query(['post_type' => 'provider']);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);

        $this->list_world(false, [], 811);
        $query = $this->query(['post_type' => 'provider']);
        \uamswp_fad_editor_group_admin_list_scope($query);
        $this->assertSame([], $query->set);
    }

    private function database(): object
    {
        return new class
        {
            public string $posts = 'wp_posts';

            public string $term_relationships = 'wp_term_relationships';

            public function prepare(string $query, int $user): string
            {
                return str_replace('%d', (string) $user, $query);
            }
        };
    }

    public function test_the_where_clause_adds_the_authors_own_posts_and_the_groups_posts(): void
    {
        global $wpdb;
        $previous = $wpdb;
        $wpdb = $this->database();

        try {
            $where = \uamswp_fad_editor_group_admin_list_where(' AND 1=1', $this->query([
                'uamswp_editor_group_tt_ids' => [51, '52'],
                'uamswp_editor_group_user' => '812',
            ]));
        } finally {
            $wpdb = $previous;
        }

        $this->assertSame(
            ' AND 1=1 AND ( wp_posts.post_author = 812 OR wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id IN ( 51,52 ) ) )',
            $where
        );
    }

    public function test_the_where_clause_is_untouched_without_the_scope_values(): void
    {
        global $wpdb;
        $previous = $wpdb;
        $wpdb = $this->database();

        try {
            $this->assertSame(' AND 1=1', \uamswp_fad_editor_group_admin_list_where(' AND 1=1', $this->query([])));
            $this->assertSame(' AND 1=1', \uamswp_fad_editor_group_admin_list_where(' AND 1=1', $this->query(['uamswp_editor_group_tt_ids' => [51]])));
            $this->assertSame(' AND 1=1', \uamswp_fad_editor_group_admin_list_where(' AND 1=1', $this->query(['uamswp_editor_group_user' => 812])));
        } finally {
            $wpdb = $previous;
        }
    }

    public function test_only_providers_and_locations_inherit_a_group_from_a_service_line(): void
    {
        Functions\when('get_post_type')->justReturn('page');
        Functions\expect('get_field')->never();

        $this->assertSame([], \uamswp_fad_editor_groups_from_service_line(900));
    }

    public function test_the_groups_come_from_each_service_line_that_maps_to_a_real_group(): void
    {
        Functions\when('get_post_type')->justReturn('location');
        Functions\expect('get_field')->once()->with('location_service_line', 900)->andReturn([
            (object) ['term_id' => 61],
            (object) [],
            '62',
            63,
            0,
        ]);
        Functions\when('get_term_meta')->alias(static fn (int $term, string $key): mixed => match ($term) {
            61 => [71, '72', 0],
            62 => 71,
            63 => 73,
            default => '',
        });
        Functions\when('term_exists')->alias(static fn (int $group, string $taxonomy): mixed => $taxonomy === 'editor_group' && $group !== 72 ? ['term_id' => $group] : null);

        $this->assertSame([71, 73], \uamswp_fad_editor_groups_from_service_line(900));
    }

    public function test_a_post_with_no_service_line_inherits_nothing(): void
    {
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_field')->justReturn(false);

        $this->assertSame([], \uamswp_fad_editor_groups_from_service_line(900));
    }

    /**
     * @param  list<int>  $current  Groups the post is in
     * @param  mixed  $previous  The remembered derived groups
     * @param  mixed  $service_line_field  The service line field value
     */
    private function inherit_world(mixed $current, mixed $previous, mixed $service_line_field): void
    {
        Functions\when('wp_get_object_terms')->justReturn($current);
        Functions\when('get_post_meta')->justReturn($previous);
        Functions\when('get_post_type')->justReturn('provider');
        Functions\when('get_field')->justReturn($service_line_field);
        Functions\when('get_term_meta')->justReturn(81);
        Functions\when('term_exists')->justReturn(['term_id' => 81]);
    }

    public function test_a_failed_read_of_the_posts_groups_changes_nothing(): void
    {
        $this->inherit_world('WP_ERROR', [], [5]);
        Functions\expect('wp_set_object_terms')->never();
        Functions\expect('update_post_meta')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group(900));
    }

    public function test_a_post_already_in_the_wanted_groups_is_left_alone(): void
    {
        $this->inherit_world(['81'], [81], [5]);
        Functions\expect('wp_set_object_terms')->never();
        Functions\expect('update_post_meta')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group('900'));
    }

    public function test_the_remembered_groups_are_refreshed_when_only_they_are_stale(): void
    {
        $this->inherit_world([81], '', [5]);
        Functions\expect('update_post_meta')->once()->with(900, '_uamswp_editor_groups_derived', [81]);
        Functions\expect('wp_set_object_terms')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group(900));
    }

    public function test_a_moved_service_line_replaces_the_derived_group_and_keeps_a_manual_one(): void
    {
        $this->inherit_world([95, 40], [95], [5]);
        Functions\expect('wp_set_object_terms')->once()->with(900, [40, 81], 'editor_group', false)->andReturn([40, 81]);
        Functions\expect('update_post_meta')->once()->with(900, '_uamswp_editor_groups_derived', [81]);

        $this->assertTrue(\uamswp_fad_inherit_editor_group(900));
    }

    public function test_a_failed_group_write_remembers_nothing_and_reports_no_change(): void
    {
        $this->inherit_world([], [], [5]);
        Functions\when('wp_set_object_terms')->justReturn('WP_ERROR');
        Functions\expect('update_post_meta')->never();

        $this->assertFalse(\uamswp_fad_inherit_editor_group(900));
    }

    public function test_saving_skips_autosaves_revisions_and_other_post_types(): void
    {
        Functions\expect('wp_get_object_terms')->never();

        Functions\when('wp_is_post_autosave')->justReturn(true);
        Functions\when('wp_is_post_revision')->justReturn(false);
        \uamswp_fad_inherit_editor_group_on_save(900, (object) ['post_type' => 'provider']);

        Functions\when('wp_is_post_autosave')->justReturn(false);
        Functions\when('wp_is_post_revision')->justReturn(true);
        \uamswp_fad_inherit_editor_group_on_save(900, (object) ['post_type' => 'provider']);

        Functions\when('wp_is_post_revision')->justReturn(false);
        \uamswp_fad_inherit_editor_group_on_save(900, (object) ['post_type' => 'page']);

        $this->addToAssertionCount(1);
    }

    public function test_saving_a_provider_brings_its_groups_in_step_with_its_service_line(): void
    {
        Functions\when('wp_is_post_autosave')->justReturn(false);
        Functions\when('wp_is_post_revision')->justReturn(false);
        $this->inherit_world([], [], [5]);
        Functions\expect('wp_set_object_terms')->once()->with(901, [81], 'editor_group', false)->andReturn([81]);
        Functions\when('update_post_meta')->justReturn(true);

        \uamswp_fad_inherit_editor_group_on_save('901', (object) ['post_type' => 'provider']);
    }

    public function test_the_sync_link_is_a_nonce_link_to_the_admin_post_action(): void
    {
        Functions\expect('admin_url')->once()->with('admin-post.php?action=uamswp_fad_sync_editor_groups')->andReturn('https://x/admin-post.php?action=uamswp_fad_sync_editor_groups');
        Functions\expect('wp_nonce_url')->once()->with('https://x/admin-post.php?action=uamswp_fad_sync_editor_groups', 'uamswp_fad_sync_editor_groups')->andReturn('https://x/signed');

        $this->assertSame('https://x/signed', \uamswp_fad_editor_group_sync_url());
    }

    private function notice_world(bool $can): void
    {
        Functions\when('current_user_can')->alias(static fn (string $cap): bool => $can && $cap === 'manage_options');
        Functions\when('esc_html')->returnArg();
        Functions\when('esc_url')->returnArg();
        Functions\when('absint')->alias(static fn (mixed $value): int => abs((int) $value));
        Functions\when('admin_url')->returnArg();
        Functions\when('wp_nonce_url')->justReturn('https://x/signed');
    }

    public function test_the_sync_notice_offers_the_mapping_to_an_administrator(): void
    {
        $this->notice_world(true);
        unset($_GET['uamswp_synced']);

        $this->expectOutputString(
            '<div class="notice notice-info"><p>Each provider and location picks up its service line\'s editor group when it is saved; groups added by hand are kept. To apply the mapping to every record now: <a class="button" href="https://x/signed">Apply service line mapping</a></p></div>'
        );

        \uamswp_fad_editor_group_sync_notice();
    }

    public function test_the_sync_notice_reports_how_many_records_were_updated(): void
    {
        $this->notice_world(true);
        $_GET['uamswp_synced'] = '-3';

        ob_start();
        \uamswp_fad_editor_group_sync_notice();
        $html = (string) ob_get_clean();

        $this->assertStringStartsWith('<div class="notice notice-success"><p>3 providers and locations had their service line editor group updated.</p></div><div class="notice notice-info">', $html);
    }

    public function test_the_sync_notice_is_hidden_from_users_who_cannot_manage_options(): void
    {
        $this->notice_world(false);
        $_GET['uamswp_synced'] = '3';

        $this->expectOutputString('');

        \uamswp_fad_editor_group_sync_notice();
    }

    public function test_the_sync_action_refuses_a_user_who_cannot_manage_options(): void
    {
        Functions\when('current_user_can')->justReturn(false);
        Functions\expect('wp_die')->once()->with('You are not allowed to do that.', '', ['response' => 403])->andThrow(new \RuntimeException('died'));
        Functions\expect('check_admin_referer')->never();
        Functions\expect('get_posts')->never();

        $this->expectExceptionMessage('died');

        \uamswp_fad_sync_editor_groups();
    }
}
