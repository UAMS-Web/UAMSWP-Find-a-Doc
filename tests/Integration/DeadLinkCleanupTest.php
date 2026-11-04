<?php

/**
 * The dead link cleanup: the dry run, the removal, the undo, and who may start them.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;
use WPDieException;

require_once __DIR__.'/../Support/acf-post-meta.php';

/**
 * Dead IDs are numbers no post has. A link field is stored as post meta in ACF's shape (see
 * tests/Support/acf-post-meta.php), and a provider's or location's link to a record is also
 * seeded in the custom table the cleanup reads, the way ACF Custom Database Tables keeps both.
 * The custom tables are temporary tables created per test.
 */
final class DeadLinkCleanupTest extends IntegrationTestCase
{
    private const DEAD = 8000001;

    private const DEAD_TWO = 8000002;

    private const DEAD_ROW = 8000003;

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
            $wpdb->query("CREATE TABLE {$table} (post_id bigint(20) unsigned NOT NULL, physician_locations longtext, physician_first_name varchar(50) DEFAULT NULL, PRIMARY KEY (post_id))");
        }

        delete_option(UAMSWP_DEAD_LINKS_RUN_OPTION);
        delete_option(UAMSWP_DEAD_LINKS_ROWS_OPTION);
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

    public function test_the_dry_run_lists_every_dead_id_and_orphan_row_and_changes_nothing(): void
    {
        $world = $this->world();

        $run = uamswp_dead_links_scan();

        $this->assertSame('scanned', $run['status']);
        $this->assertSame(4, $run['counts']['links'], 'Two on the condition, one on the treatment, one on the location.');
        $this->assertSame(3, $run['counts']['records']);
        $this->assertSame(2, $run['counts']['dead_ids'], 'The same deleted ID is listed on three records.');
        $this->assertSame([$this->providers_table => 1], $this->prefixed($run['counts']['rows']));

        $this->assertSame([(string) $world['provider'], (string) self::DEAD, (string) self::DEAD_TWO], get_field('condition_physicians', $world['condition']));
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW));
    }

    public function test_the_removal_takes_only_dead_ids_and_orphan_rows_and_a_second_dry_run_finds_none(): void
    {
        $world = $this->world();

        $run = uamswp_dead_links_apply_step(uamswp_dead_links_scan());

        $this->assertSame('applied', $run['status']);
        $this->assertSame([$world['provider']], $this->ids(get_field('condition_physicians', $world['condition'])));
        $this->assertSame([], $this->ids(get_field('treatment_procedure_locations', $world['treatment'])));
        $this->assertSame([$world['provider']], $this->ids(get_field('physician_locations', $world['location'])));
        $this->assertSame(0, $this->rowCount($this->providers_table, self::DEAD_ROW), 'The orphan row is gone.');
        $this->assertSame(1, $this->rowCount($this->providers_table, $world['trashed']), 'A row whose post is in the trash stays.');
        $this->assertSame(1, $this->rowCount($this->providers_table, $world['provider']), 'A live provider keeps its row.');

        $again = uamswp_dead_links_scan(false);
        $this->assertSame(0, $again['counts']['links']);
        $this->assertSame([], $again['counts']['rows']);
    }

    public function test_the_removal_runs_in_batches(): void
    {
        $world = $this->world();

        $run = uamswp_dead_links_scan();
        $steps = 0;

        while ($run['status'] !== 'applied') {
            $run = uamswp_dead_links_apply_step($run, 1);
            $steps++;
        }

        $this->assertSame(count($run['items']), $steps);
        $this->assertSame([$world['provider']], $this->ids(get_field('condition_physicians', $world['condition'])));
        $this->assertSame(0, $this->rowCount($this->providers_table, self::DEAD_ROW));
    }

    public function test_an_id_or_row_that_is_back_by_the_removal_is_left_alone(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_scan();

        // The post a dead ID names is created, and the orphan row's post is created, after the dry run.
        wp_insert_post(['import_id' => self::DEAD, 'post_type' => 'provider', 'post_status' => 'publish', 'post_title' => 'Back']);
        wp_insert_post(['import_id' => self::DEAD_ROW, 'post_type' => 'provider', 'post_status' => 'publish', 'post_title' => 'Row back']);

        $run = uamswp_dead_links_apply_step($run);

        $this->assertSame([$world['provider'], self::DEAD], array_map('intval', (array) get_field('condition_physicians', $world['condition'])), 'Only the ID that is still missing is removed.');
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW), 'A row whose post exists again stays.');
        $this->assertContains('skipped', array_column($run['items'], 'state'));
    }

    public function test_the_undo_puts_back_the_ids_and_the_row(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_apply_step(uamswp_dead_links_scan());

        $run = uamswp_dead_links_undo_step($run);

        $this->assertSame('undone', $run['status']);
        $this->assertSame([$world['provider'], self::DEAD, self::DEAD_TWO], array_map('intval', (array) get_field('condition_physicians', $world['condition'])));
        $this->assertSame([self::DEAD], array_map('intval', (array) get_field('treatment_procedure_locations', $world['treatment'])));
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW));
        $this->assertSame('Orphan', $this->firstName($this->providers_table, self::DEAD_ROW), 'The whole row comes back.');
    }

    public function test_the_undo_leaves_a_field_edited_since_the_removal(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_apply_step(uamswp_dead_links_scan());

        $other = self::factory()->post->create(['post_type' => 'provider']);
        update_field('condition_physicians', [$world['provider'], $other], $world['condition']);

        $run = uamswp_dead_links_undo_step($run);

        $this->assertSame([(string) $world['provider'], (string) $other], get_field('condition_physicians', $world['condition']), 'The later edit is kept.');
        $this->assertContains('kept', array_column($run['items'], 'state'));
    }

    public function test_only_an_administrator_may_start_a_step(): void
    {
        $this->world();
        wp_set_current_user(self::factory()->user->create(['role' => 'subscriber']));

        $handlers = [
            'uamswp_dead_links_scan' => 'uamswp_dead_links_handle_scan',
            'uamswp_dead_links_apply' => 'uamswp_dead_links_handle_apply',
            'uamswp_dead_links_undo' => 'uamswp_dead_links_handle_undo',
        ];

        foreach ($handlers as $action => $handler) {
            // A valid nonce, so only the capability check can refuse the step.
            $_REQUEST['_wpnonce'] = wp_create_nonce($action);

            try {
                $handler();
                $this->fail("{$handler} ran for a subscriber.");
            } catch (WPDieException) {
                $this->assertNull(uamswp_dead_links_run(), 'Nothing was stored.');
            }
        }

        unset($_REQUEST['_wpnonce']);
    }

    public function test_a_step_without_its_nonce_is_refused(): void
    {
        wp_set_current_user(self::factory()->user->create(['role' => 'administrator']));

        $this->expectException(WPDieException::class);

        uamswp_dead_links_handle_scan();
    }

    public function test_a_check_after_the_removal_keeps_the_undo(): void
    {
        $world = $this->world();
        $stored = uamswp_dead_links_apply_step(uamswp_dead_links_scan());

        $this->actAs('administrator');
        $_REQUEST['_wpnonce'] = wp_create_nonce('uamswp_dead_links_scan');

        $location = $this->redirectOf('uamswp_dead_links_handle_scan');

        $this->assertStringContainsString('notice=checked', $location);
        $this->assertSame('applied', uamswp_dead_links_run()['status'], 'The run is not replaced.');
        $this->assertSame(0, get_transient('uamswp_fad_dead_links_check')['links']);

        uamswp_dead_links_undo_step(uamswp_dead_links_run());

        $this->assertSame([$world['provider'], self::DEAD, self::DEAD_TWO], $this->ids(get_field('condition_physicians', $world['condition'])));
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW), 'The row backup survived the check.');
    }

    public function test_a_step_that_ended_after_recording_its_items_finishes_without_losing_the_undo(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_apply_step(uamswp_dead_links_scan());

        // As if the request died after the fields were written: the items are recorded but still pending, at the start.
        foreach ($run['items'] as $i => $item) {
            $run['items'][$i]['state'] = 'pending';
        }
        $run['cursor'] = 0;
        $run['status'] = 'applying';

        $run = uamswp_dead_links_apply_step($run);

        $this->assertSame(['done'], array_values(array_unique(array_column($run['items'], 'state'))), 'Written items are recognized as done, not skipped.');

        $run = uamswp_dead_links_undo_step($run);

        $this->assertSame([$world['provider'], self::DEAD, self::DEAD_TWO], $this->ids(get_field('condition_physicians', $world['condition'])));
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW));
    }

    public function test_a_single_post_object_field_is_listed_cleared_and_restored(): void
    {
        $spotlight = self::factory()->post->create(['post_type' => 'clinical-resource']);
        update_field('clinical_resource_spotlight_provider', (string) self::DEAD, $spotlight);

        $run = uamswp_dead_links_scan();

        $this->assertSame(1, $run['counts']['links']);
        $this->assertSame('single', $run['items'][0]['shape']);

        $run = uamswp_dead_links_apply_step($run);

        $this->assertSame([], $this->ids(get_field('clinical_resource_spotlight_provider', $spotlight)));

        uamswp_dead_links_undo_step($run);

        $this->assertSame([self::DEAD], $this->ids(get_field('clinical_resource_spotlight_provider', $spotlight)));
    }

    public function test_an_orphan_row_that_carries_links_is_one_row_and_no_links(): void
    {
        $this->insertRow($this->providers_table, self::DEAD_ROW, [self::DEAD_TWO], 'Orphan');

        $run = uamswp_dead_links_scan();

        $this->assertSame(0, $run['counts']['links']);
        $this->assertSame([$this->providers_table => 1], $this->prefixed($run['counts']['rows']));
        $this->assertSame(['row'], array_column($run['items'], 'kind'));
    }

    public function test_nothing_is_written_when_the_backup_cannot_be_saved(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_scan();

        // The run record is refused: the option keeps its old value, as a write that failed would.
        add_filter('pre_update_option_'.UAMSWP_DEAD_LINKS_RUN_OPTION, static fn ($value, $old) => $old, 10, 2);

        $result = uamswp_dead_links_apply_step($run);

        $this->assertSame('failed', $result['status']);
        $this->assertSame([$world['provider'], self::DEAD, self::DEAD_TWO], $this->ids(get_field('condition_physicians', $world['condition'])));
        $this->assertSame(1, $this->rowCount($this->providers_table, self::DEAD_ROW));
    }

    public function test_a_step_is_refused_while_another_holds_the_lock(): void
    {
        $this->world();
        uamswp_dead_links_scan();
        $this->actAs('administrator');

        $this->assertTrue(uamswp_dead_links_lock());
        $this->assertFalse(uamswp_dead_links_lock(), 'A second step cannot take it.');

        $_REQUEST['_wpnonce'] = wp_create_nonce('uamswp_dead_links_apply');
        $_GET['run'] = (string) uamswp_dead_links_run()['scanned_at'];

        $this->assertStringContainsString('notice=busy', $this->redirectOf('uamswp_dead_links_handle_apply'));
        $this->assertSame('scanned', uamswp_dead_links_run()['status']);

        uamswp_dead_links_unlock();
        $this->assertTrue(uamswp_dead_links_lock());
        uamswp_dead_links_unlock();
    }

    public function test_a_step_from_an_earlier_dry_run_is_refused(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_scan();
        $this->actAs('administrator');

        $_REQUEST['_wpnonce'] = wp_create_nonce('uamswp_dead_links_apply');
        $_GET['run'] = (string) ($run['scanned_at'] - 60);

        $this->assertStringContainsString('notice=stale', $this->redirectOf('uamswp_dead_links_handle_apply'));
        $this->assertSame([$world['provider'], self::DEAD, self::DEAD_TWO], $this->ids(get_field('condition_physicians', $world['condition'])));
    }

    public function test_the_removal_through_its_handler_continues_until_the_list_is_done(): void
    {
        $world = $this->world();
        $run = uamswp_dead_links_scan();
        $this->actAs('administrator');

        $steps = 0;

        do {
            $_REQUEST['_wpnonce'] = wp_create_nonce('uamswp_dead_links_apply');
            $_GET['run'] = (string) $run['scanned_at'];
            $location = $this->redirectOf('uamswp_dead_links_handle_apply');
            $steps++;
        } while (str_contains($location, 'admin-post.php') && $steps < 50);

        $this->assertStringContainsString('notice=applied', $location);
        $this->assertSame('applied', uamswp_dead_links_run()['status']);
        $this->assertSame([$world['provider']], $this->ids(get_field('condition_physicians', $world['condition'])));
    }

    public function test_a_stored_run_of_the_wrong_shape_is_not_used(): void
    {
        update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, ['status' => 'scanned']);

        $this->assertNull(uamswp_dead_links_run());

        update_option(UAMSWP_DEAD_LINKS_RUN_OPTION, 'garbage');

        $this->assertNull(uamswp_dead_links_run());
    }

    public function test_the_screen_escapes_what_the_records_contain(): void
    {
        $condition = self::factory()->post->create(['post_type' => 'condition', 'post_title' => '<b>Bold</b>']);
        update_field('condition_physicians', [self::DEAD], $condition);
        uamswp_dead_links_scan();
        $this->actAs('administrator');

        ob_start();
        uamswp_dead_links_screen();
        $html = (string) ob_get_clean();

        $this->assertStringContainsString('&lt;b&gt;Bold&lt;/b&gt;', $html);
        $this->assertStringNotContainsString('<b>Bold</b>', $html);
        $this->assertStringContainsString((string) self::DEAD, $html);
    }

    public function test_the_screen_is_refused_to_a_subscriber(): void
    {
        wp_set_current_user(self::factory()->user->create(['role' => 'subscriber']));

        $this->expectException(WPDieException::class);

        uamswp_dead_links_screen();
    }

    /**
     * A live provider, a condition, a treatment and a location with dead IDs, and two rows: one orphan, one whose post is in the trash.
     *
     * @return array{provider: int, condition: int, treatment: int, location: int, trashed: int}
     */
    private function world(): array
    {
        $provider = self::factory()->post->create(['post_type' => 'provider']);
        $condition = self::factory()->post->create(['post_type' => 'condition']);
        $treatment = self::factory()->post->create(['post_type' => 'treatment']);
        $location = self::factory()->post->create(['post_type' => 'location']);
        $trashed = self::factory()->post->create(['post_type' => 'provider', 'post_status' => 'trash']);

        update_field('condition_physicians', [$provider, self::DEAD, self::DEAD_TWO], $condition);
        update_field('treatment_procedure_locations', [self::DEAD], $treatment);

        // The location's providers: in its field and in its custom-table row.
        update_field('physician_locations', [$provider, self::DEAD], $location);
        $this->insertRow($this->locations_table, $location, [$provider, self::DEAD], 'Place');

        $this->insertRow($this->providers_table, $provider, [], 'Live');
        $this->insertRow($this->providers_table, $trashed, [], 'Trashed');
        $this->insertRow($this->providers_table, self::DEAD_ROW, [], 'Orphan');

        return compact('provider', 'condition', 'treatment', 'location', 'trashed');
    }

    private function actAs(string $role): void
    {
        wp_set_current_user(self::factory()->user->create(['role' => $role]));
    }

    /**
     * The address a handler redirects to. A filter on the redirect throws, so the handler's exit is never reached.
     */
    private function redirectOf(string $handler): string
    {
        $catch = static function (string $location): never {
            throw new RedirectCaught($location);
        };
        add_filter('wp_redirect', $catch);

        try {
            $handler();
        } catch (RedirectCaught $caught) {
            return $caught->getMessage();
        } finally {
            remove_filter('wp_redirect', $catch);
            unset($_REQUEST['_wpnonce'], $_GET['run']);
        }

        $this->fail("{$handler} did not redirect.");
    }

    /**
     * A stored link field as integers: ACF keeps the IDs as strings, and an emptied field as false.
     *
     * @return array<int, int>
     */
    private function ids(mixed $value): array
    {
        return array_map(intval(...), (array) ($value ?: []));
    }

    /**
     * @param  array<int, int>  $linked
     */
    private function insertRow(string $table, int $post_id, array $linked, string $first_name): void
    {
        global $wpdb;

        $wpdb->insert($table, ['post_id' => $post_id, 'physician_locations' => (string) wp_json_encode($linked), 'physician_first_name' => $first_name]);
    }

    private function rowCount(string $table, int $post_id): int
    {
        global $wpdb;

        return (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE post_id = %d", $post_id));
    }

    private function firstName(string $table, int $post_id): ?string
    {
        global $wpdb;

        return $wpdb->get_var($wpdb->prepare("SELECT physician_first_name FROM {$table} WHERE post_id = %d", $post_id));
    }

    /**
     * Row counts keyed by the table name with its prefix, as the tests name them.
     *
     * @param  array<string, int>  $rows
     * @return array<string, int>
     */
    private function prefixed(array $rows): array
    {
        global $wpdb;

        $prefixed = [];

        foreach ($rows as $table => $count) {
            $prefixed[$wpdb->prefix.$table] = $count;
        }

        return $prefixed;
    }
}

/**
 * Thrown by a redirect filter in place of the redirect.
 */
final class RedirectCaught extends \RuntimeException {}

// cspell:ignore uamswp
