<?php

/**
 * Unit tests for the delete cleanup: what a permanent delete removes, and what it leaves alone.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class DeleteCleanupTest extends UnitTestCase
{
    private object $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = new class
        {
            public $prefix = 'wp_';

            public $posts = 'wp_posts';

            public $postmeta = 'wp_postmeta';

            /** @var array<int, string> */
            public $queries = [];

            /** @var array<int, mixed> */
            public $col = [];

            /** @var array<int, array<string, string>> */
            public $rows = [];

            /** @var array<int, array{0: string, 1: array<string, int>, 2: array<int, string>}> */
            public $deleted = [];

            public function esc_like($text): string
            {
                return addcslashes((string) $text, '_%\\');
            }

            public function prepare($sql, ...$args)
            {
                foreach ($args as $arg) {
                    $sql = preg_replace('/%[sd]/', is_int($arg) ? (string) $arg : "'".$arg."'", $sql, 1);
                }

                return $sql;
            }

            public function get_col($sql)
            {
                $this->queries[] = $sql;

                return $this->col;
            }

            public function get_results($sql, $output = null)
            {
                $this->queries[] = $sql;

                return $this->rows;
            }

            public function delete($table, $where, $format): int
            {
                $this->deleted[] = [$table, $where, $format];

                return 1;
            }
        };

        // WordPress defines this; the Unit suite has no WordPress.
        defined('ARRAY_A') || define('ARRAY_A', 'ARRAY_A');

        $GLOBALS['wpdb'] = $this->db;
    }

    protected function tearDown(): void
    {
        foreach ([12, 13] as $id) {
            uamswp_delete_cleanup_pending($id);
        }

        unset($GLOBALS['wpdb']);

        parent::tearDown();
    }

    /**
     * @param  array<string, array<int, int>>  $meta  Linked post IDs by meta key.
     * @param  array<string, array<int, int>>  $tables  Linked post IDs by table.
     */
    private function links(array $meta, array $tables = []): void
    {
        when('uamswp_delete_cleanup_meta_post_ids')->alias(fn (string $type, string $key): array => $meta[$key] ?? []);
        when('uamswp_delete_cleanup_table_post_ids')->alias(fn (string $table): array => $tables[$table] ?? []);
    }

    public function test_the_hooks_fire_on_a_permanent_delete_and_never_on_the_trash(): void
    {
        expectAdded('before_delete_post')->once()->with('uamswp_delete_cleanup_before_delete');
        expectAdded('deleted_post')->once()->with('uamswp_delete_cleanup_after_delete');
        expectAdded('wp_trash_post')->never();
        expectAdded('trashed_post')->never();
        expectAdded('transition_post_status')->never();

        uamswp_delete_cleanup_register();
    }

    public function test_a_deleted_provider_leaves_the_lists_that_named_it(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links(['condition_physicians' => [10, 11]]);
        when('get_field')->alias(fn (string $field, int $post_id) => $post_id === 10 ? ['5', '12', '7'] : ['5', '7']);
        expect('update_field')->once()->with('condition_physicians', [5, 7], 10);

        uamswp_delete_cleanup_before_delete(12);
    }

    public function test_a_deleted_provider_is_cleared_from_a_single_post_object_field(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links(['clinical_resource_spotlight_provider' => [20]]);
        when('get_field')->justReturn('12');
        expect('update_field')->once()->with('clinical_resource_spotlight_provider', '', 20);

        uamswp_delete_cleanup_before_delete(12);
    }

    public function test_a_deleted_location_leaves_provider_lists_kept_in_the_custom_table(): void
    {
        when('get_post_type')->justReturn('location');
        $this->links([], ['uamswp_physicians' => [30]]);
        when('get_field')->justReturn([12, 40]);
        expect('update_field')->once()->with('physician_locations', [40], 30);

        uamswp_delete_cleanup_before_delete(12);
    }

    public function test_a_post_type_that_is_not_a_provider_or_location_touches_nothing(): void
    {
        when('get_post_type')->justReturn('condition');
        expect('uamswp_delete_cleanup_meta_post_ids')->never();
        expect('uamswp_delete_cleanup_table_post_ids')->never();
        expect('update_field')->never();

        uamswp_delete_cleanup_before_delete(12);

        $this->assertSame([], uamswp_delete_cleanup_pending());
    }

    public function test_a_record_that_nothing_links_to_completes_without_a_write(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links([]);
        expect('update_field')->never();
        expect('uamswp_delete_cleanup_log')->never();

        uamswp_delete_cleanup_before_delete(12);
    }

    public function test_a_field_that_no_longer_holds_the_id_is_not_rewritten(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links(['condition_physicians' => [10]]);
        when('get_field')->justReturn(['5', '7']);
        expect('update_field')->never();

        uamswp_delete_cleanup_before_delete(12);
    }

    public function test_one_failing_record_is_logged_and_the_others_are_still_cleaned(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links(['condition_physicians' => [10, 11]]);
        when('get_field')->justReturn(['12', '7']);
        when('update_field')->alias(function (string $field, $value, int $post_id) {
            if ($post_id === 10) {
                throw new \RuntimeException('write failed');
            }

            return true;
        });
        expect('uamswp_delete_cleanup_log')->once()->with(\Mockery::pattern('/post 10: write failed/'));

        uamswp_delete_cleanup_before_delete(12);

        $this->assertTrue(true);
    }

    public function test_the_post_s_own_row_is_deleted_after_it_is_gone_and_not_before(): void
    {
        when('get_post_type')->justReturn('provider');
        $this->links([]);

        uamswp_delete_cleanup_before_delete(12);

        $this->assertSame([], $this->db->deleted, 'Nothing is deleted while the post still exists');

        uamswp_delete_cleanup_after_delete(12);

        $this->assertSame([['wp_uamswp_physicians', ['post_id' => 12], ['%d']]], $this->db->deleted);
    }

    public function test_a_location_s_own_row_is_in_the_locations_table(): void
    {
        when('get_post_type')->justReturn('location');
        $this->links([]);

        uamswp_delete_cleanup_before_delete(13);
        uamswp_delete_cleanup_after_delete(13);

        $this->assertSame([['wp_uamswp_locations', ['post_id' => 13], ['%d']]], $this->db->deleted);
    }

    public function test_a_deleted_post_that_was_never_seen_before_deleting_has_no_row_removed(): void
    {
        uamswp_delete_cleanup_after_delete(99);

        $this->assertSame([], $this->db->deleted);
    }

    public function test_the_meta_query_matches_a_quoted_or_terminated_id_and_never_a_longer_number(): void
    {
        $this->db->col = ['10', '11'];

        $ids = uamswp_delete_cleanup_meta_post_ids('condition', 'condition_physicians', 'list', 12);

        $this->assertSame([10, 11], $ids);
        $this->assertStringContainsString("LIKE '%\"12\"%'", $this->db->queries[0]);
        $this->assertStringContainsString("LIKE '%i:12;%'", $this->db->queries[0]);
        $this->assertStringContainsString("meta_key = 'condition_physicians'", $this->db->queries[0]);
    }

    public function test_a_single_field_is_matched_exactly(): void
    {
        $this->db->col = ['20'];

        uamswp_delete_cleanup_meta_post_ids('clinical-resource', 'clinical_resource_spotlight_provider', 'single', 12);

        $this->assertStringContainsString("meta_value = '12'", $this->db->queries[0]);
        $this->assertStringNotContainsString('LIKE', $this->db->queries[0]);
    }

    public function test_the_table_query_keeps_only_rows_that_really_list_the_id(): void
    {
        $this->db->rows = [
            ['post_id' => '5', 'links' => '[12,99]'],
            ['post_id' => '6', 'links' => '[123,45]'],
            ['post_id' => '7', 'links' => '[412]'],
            ['post_id' => '8', 'links' => 'not json 12'],
            ['post_id' => '9', 'links' => '[1,12]'],
        ];

        $this->assertSame([5, 9], uamswp_delete_cleanup_table_post_ids('uamswp_physicians', 'physician_locations', 12));
        $this->assertStringContainsString('wp_uamswp_physicians', $this->db->queries[0]);
    }

    public function test_ids_are_read_from_strings_integers_objects_and_empties(): void
    {
        $this->assertSame([5, 7], uamswp_delete_cleanup_ids(['5', 7, 'x', 0, -3]));
        $this->assertSame([9], uamswp_delete_cleanup_ids([(object) ['ID' => 9]]));
        $this->assertSame([12], uamswp_delete_cleanup_ids('12'));
        $this->assertSame([], uamswp_delete_cleanup_ids(''));
        $this->assertSame([], uamswp_delete_cleanup_ids(false));
        $this->assertSame([], uamswp_delete_cleanup_ids(null));
    }
}
