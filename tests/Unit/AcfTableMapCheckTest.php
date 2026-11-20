<?php

/**
 * The ACF table map check: cache against table JSON, the command line script and the admin notice.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;
use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

final class AcfTableMapCheckTest extends UnitTestCase
{
    /** @var list<string> */
    private array $dirs = [];

    protected function tearDown(): void
    {
        foreach ($this->dirs as $dir) {
            $entries = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($entries as $entry) {
                $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname());
            }

            rmdir($dir);
        }

        $this->dirs = [];

        parent::tearDown();
    }

    /**
     * Build a table directory with one table JSON file per entry and a cache holding `$cached`.
     *
     * @param  array<string, array{post_type: string, columns: list<string>}>  $declared
     * @param  array<string, array{post_type: string, columns: list<string>}>  $cached
     */
    private function fixture(array $declared, array $cached): string
    {
        $dir = sys_get_temp_dir().'/uamswp_fad_table_map_'.bin2hex(random_bytes(6));
        mkdir($dir.'/.cache', 0777, true);
        $this->dirs[] = $dir;

        foreach ($declared as $name => $table) {
            file_put_contents($dir.'/table_'.$name.'.json', json_encode($this->definition($name, $table)));
        }

        $map = ['tables' => []];

        foreach ($cached as $name => $table) {
            $map['tables'][$name] = $this->definition($name, $table);
        }

        file_put_contents($dir.'/.cache/_table_map.php', "<?php defined('WPINC') or die('x'); return ".var_export($map, true).';');

        return $dir;
    }

    /**
     * @param  array{post_type: string, columns: list<string>}  $table
     * @return array<string, mixed>
     */
    private function definition(string $name, array $table): array
    {
        return [
            'name' => $name,
            'relationship' => ['type' => 'post', 'post_type' => $table['post_type']],
            'columns' => array_map(fn (string $column) => ['name' => $column], $table['columns']),
        ];
    }

    /**
     * @return array{post_type: string, columns: list<string>}
     */
    private function locations(string ...$columns): array
    {
        return ['post_type' => 'location', 'columns' => $columns ?: ['location_phone', 'location_city']];
    }

    public function test_a_cache_that_agrees_with_the_json_has_no_problems(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);

        $this->assertSame(['readable' => true, 'tables' => 1, 'problems' => []], uamswp_fad_acf_table_map_check($dir));
    }

    public function test_a_column_the_json_has_and_the_cache_lacks_is_named(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')],
            ['uamswp_locations' => $this->locations()]
        );

        $problems = uamswp_fad_acf_table_map_check($dir)['problems'];

        $this->assertSame(['table `uamswp_locations`: 1 column(s) in JSON but not the cache (e.g. location_suite)'], $problems);
    }

    public function test_a_column_the_cache_has_and_the_json_lacks_is_named(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations()],
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'requires_masks_staff')]
        );

        $problems = uamswp_fad_acf_table_map_check($dir)['problems'];

        $this->assertSame(['table `uamswp_locations`: 1 column(s) in the cache but not the JSON (e.g. requires_masks_staff)'], $problems);
    }

    public function test_a_post_type_that_differs_is_reported(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations()],
            ['uamswp_locations' => ['post_type' => 'locations', 'columns' => ['location_phone', 'location_city']]]
        );

        $this->assertSame(
            ['table `uamswp_locations`: JSON says post_type `location`, cache says `locations`'],
            uamswp_fad_acf_table_map_check($dir)['problems']
        );
    }

    public function test_a_table_in_only_one_place_is_reported_both_ways(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations()],
            ['uamswp_physicians' => ['post_type' => 'provider', 'columns' => ['physician_npi']]]
        );

        $this->assertSame(
            [
                'table `uamswp_locations` is declared in JSON but absent from the cache',
                'table `uamswp_physicians` is in the cache but has no JSON file',
            ],
            uamswp_fad_acf_table_map_check($dir)['problems']
        );
    }

    public function test_file_times_are_not_read_so_identical_times_still_show_a_difference(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')],
            ['uamswp_locations' => $this->locations()]
        );

        // The state of a fresh clone: every file carries the same time.
        foreach ([$dir.'/table_uamswp_locations.json', $dir.'/.cache/_table_map.php'] as $file) {
            touch($file, 1700000000);
        }

        $this->assertNotSame([], uamswp_fad_acf_table_map_check($dir)['problems']);
    }

    public function test_file_times_are_not_read_so_an_older_or_newer_cache_that_agrees_passes(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);

        touch($dir.'/.cache/_table_map.php', 1700000000);
        touch($dir.'/table_uamswp_locations.json', 1800000000);
        $cache_older = uamswp_fad_acf_table_map_check($dir)['problems'];

        touch($dir.'/.cache/_table_map.php', 1800000000);
        touch($dir.'/table_uamswp_locations.json', 1700000000);
        $cache_newer = uamswp_fad_acf_table_map_check($dir)['problems'];

        $this->assertSame([[], []], [$cache_older, $cache_newer]);
    }

    public function test_a_missing_cache_is_unreadable_not_empty(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        unlink($dir.'/.cache/_table_map.php');

        $check = uamswp_fad_acf_table_map_check($dir);

        $this->assertFalse($check['readable']);
        $this->assertStringContainsString('Cannot read the table map cache', $check['problems'][0]);
    }

    public function test_a_cache_that_does_not_return_an_array_is_unreadable(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        file_put_contents($dir.'/.cache/_table_map.php', "<?php return 'nope';");

        $this->assertFalse(uamswp_fad_acf_table_map_check($dir)['readable']);
    }

    public function test_a_cache_with_a_parse_error_is_unreadable(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        file_put_contents($dir.'/.cache/_table_map.php', '<?php return [;');

        $check = uamswp_fad_acf_table_map_check($dir);

        $this->assertFalse($check['readable']);
        $this->assertStringContainsString('Cannot load the table map cache', $check['problems'][0]);
    }

    public function test_a_table_file_that_is_not_json_is_a_problem_not_an_empty_table(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);
        file_put_contents($dir.'/table_broken.json', '{not json');

        $problems = uamswp_fad_acf_table_map_check($dir)['problems'];

        $this->assertSame(['table_broken.json is not valid JSON with a table name'], $problems);
    }

    public function test_a_table_file_without_a_columns_list_is_a_problem_not_a_crash(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);
        file_put_contents($dir.'/table_odd.json', json_encode(['name' => 'odd', 'columns' => 'none', 'relationship' => []]));

        $problems = uamswp_fad_acf_table_map_check($dir)['problems'];

        $this->assertSame(['table_odd.json has no `columns` list or `relationship` object'], $problems);
    }

    public function test_the_committed_cache_agrees_with_the_committed_table_json(): void
    {
        $check = uamswp_fad_acf_table_map_check(dirname(__DIR__, 2).'/assets/json/acf-tables');

        $this->assertTrue($check['readable']);
        $this->assertSame(2, $check['tables']);
        $this->assertSame([], $check['problems']);
    }

    /**
     * Run the command line script and return its exit status and both streams.
     *
     * @return array{int, string, string}
     */
    private function run_cli(string $dir): array
    {
        $process = proc_open(
            [PHP_BINARY, dirname(__DIR__, 2).'/bin/check-table-map.php', $dir],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        $out = (string) stream_get_contents($pipes[1]);
        $err = (string) stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $out, $err];
    }

    public function test_the_script_exits_0_when_the_cache_agrees_and_says_what_it_did_not_check(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);

        [$status, $out] = $this->run_cli($dir);

        $this->assertSame(0, $status);
        $this->assertStringContainsString('1 table(s) agree', $out);
        $this->assertStringContainsString('the field groups are not checked', $out);
    }

    public function test_the_script_exits_1_and_names_each_difference_when_they_disagree(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')],
            ['uamswp_locations' => $this->locations()]
        );

        [$status, , $err] = $this->run_cli($dir);

        $this->assertSame(1, $status);
        $this->assertStringContainsString('location_suite', $err);
        $this->assertStringContainsString('Run Create/Update Tables', $err);
    }

    public function test_the_script_exits_2_when_the_cache_cannot_be_read(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        unlink($dir.'/.cache/_table_map.php');

        [$status, , $err] = $this->run_cli($dir);

        $this->assertSame(2, $status);
        $this->assertStringContainsString('Cannot read the table map cache', $err);
    }

    public function test_the_notice_registers_on_admin_notices(): void
    {
        expectAdded('admin_notices')->once()->with('uamswp_fad_acf_table_map_notice');

        uamswp_fad_acf_table_map_register();
    }

    public function test_the_directory_is_the_plugin_table_folder_unless_the_custom_tables_plugin_is_pointed_elsewhere(): void
    {
        if (defined('ACFCDT_JSON_DIR')) {
            $this->assertSame(ACFCDT_JSON_DIR, uamswp_fad_acf_table_map_dir());

            return;
        }

        $this->assertSame(UAMS_FAD_PATH.'assets/json/acf-tables', uamswp_fad_acf_table_map_dir());
    }

    /**
     * Run the notice against `$dir` for a user who may or may not manage options; return what it printed.
     */
    private function notice(string $dir, bool $can_manage): string
    {
        stubEscapeFunctions();
        stubTranslationFunctions();
        when('uamswp_fad_acf_table_map_dir')->justReturn($dir);
        when('admin_url')->alias(fn (string $path = '') => 'https://example.test/wp-admin/'.$path);
        expect('current_user_can')->once()->with('manage_options')->andReturn($can_manage);

        ob_start();
        uamswp_fad_acf_table_map_notice();

        return (string) ob_get_clean();
    }

    public function test_the_notice_names_the_screen_and_the_differences_for_a_user_who_can_act(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')],
            ['uamswp_locations' => $this->locations()]
        );

        $html = $this->notice($dir, true);

        $this->assertStringContainsString('Create/Update Tables', $html);
        $this->assertStringContainsString('Custom Fields &gt; Database Tables', $html);
        $this->assertStringContainsString('page=acf-custom-database-tables', $html);
        $this->assertStringContainsString('location_suite', $html);
        $this->assertStringContainsString('does not compare the ACF field groups', $html);
    }

    public function test_the_notice_prints_nothing_when_the_cache_agrees(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);

        $this->assertSame('', $this->notice($dir, true));
    }

    public function test_the_notice_prints_nothing_to_a_user_who_cannot_open_the_screen_even_when_the_cache_is_out_of_date(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')],
            ['uamswp_locations' => $this->locations()]
        );

        $this->assertSame('', $this->notice($dir, false));
    }

    public function test_the_notice_reports_a_cache_that_cannot_be_read(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        unlink($dir.'/.cache/_table_map.php');

        $this->assertStringContainsString('Cannot read the table map cache', $this->notice($dir, true));
    }

    public function test_the_notice_lists_five_differences_and_counts_the_rest(): void
    {
        $tables = [];

        foreach (range(1, 7) as $i) {
            $tables['table_'.$i] = $this->locations();
        }

        $html = $this->notice($this->fixture($tables, []), true);

        $this->assertSame(5, substr_count($html, 'is declared in JSON but absent from the cache'));
        $this->assertStringContainsString('and 2 more', $html);
    }

    public function test_an_unreadable_cache_is_reported_whole_with_its_path_and_no_tables(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        unlink($dir.'/.cache/_table_map.php');

        $this->assertSame(['readable' => false, 'tables' => 0, 'problems' => ["Cannot read the table map cache at {$dir}/.cache/_table_map.php"]], uamswp_fad_acf_table_map_check($dir.'/'));

        file_put_contents($dir.'/.cache/_table_map.php', '<?php return [;');
        $parse = uamswp_fad_acf_table_map_check($dir);

        $this->assertSame([false, 0], [$parse['readable'], $parse['tables']]);
        $this->assertCount(1, $parse['problems']);
        $this->assertStringStartsWith("Cannot load the table map cache at {$dir}/.cache/_table_map.php: ", $parse['problems'][0]);

        file_put_contents($dir.'/.cache/_table_map.php', "<?php return 'nope';");

        $this->assertSame(['readable' => false, 'tables' => 0, 'problems' => ["The table map cache at {$dir}/.cache/_table_map.php does not return an array"]], uamswp_fad_acf_table_map_check($dir));
    }

    public function test_a_directory_named_with_a_trailing_slash_reads_the_same_files(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')], ['uamswp_locations' => $this->locations()]);

        $this->assertSame(uamswp_fad_acf_table_map_check($dir), uamswp_fad_acf_table_map_check($dir.'/'));
        $this->assertSame(1, uamswp_fad_acf_table_map_check($dir.'/')['tables']);
    }

    public function test_a_table_file_that_cannot_be_read_is_a_problem_not_a_crash(): void
    {
        if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
            $this->markTestSkipped('root reads a file whatever its mode');
        }

        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);
        file_put_contents($dir.'/table_locked.json', '{}');
        chmod($dir.'/table_locked.json', 0);

        $warnings = [];
        set_error_handler(static function (int $level, string $message) use (&$warnings): bool {
            $warnings[] = $message;

            return true;
        });

        try {
            $problems = uamswp_fad_acf_table_map_check($dir)['problems'];
        } finally {
            restore_error_handler();
            chmod($dir.'/table_locked.json', 0644);
        }

        $this->assertSame(['table_locked.json is not valid JSON with a table name'], $problems);
        $this->assertNotSame([], $warnings, 'the failed read warns, and the check carries on');
    }

    public function test_a_table_file_without_a_name_or_with_a_name_that_is_not_text_is_a_problem(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);
        file_put_contents($dir.'/table_nameless.json', json_encode(['columns' => [], 'relationship' => []]));
        file_put_contents($dir.'/table_numbered.json', json_encode(['name' => 5, 'columns' => [], 'relationship' => []]));

        $this->assertSame(
            ['table_nameless.json is not valid JSON with a table name', 'table_numbered.json is not valid JSON with a table name'],
            uamswp_fad_acf_table_map_check($dir)['problems']
        );
    }

    public function test_a_table_file_without_a_relationship_is_a_problem(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], ['uamswp_locations' => $this->locations()]);
        file_put_contents($dir.'/table_loose.json', json_encode(['name' => 'loose', 'columns' => []]));

        $this->assertSame(['table_loose.json has no `columns` list or `relationship` object'], uamswp_fad_acf_table_map_check($dir)['problems']);
    }

    public function test_a_cache_entry_without_columns_or_relationship_is_a_problem_and_the_next_entry_is_still_read(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        $map = ['tables' => [
            'no_columns' => ['relationship' => ['post_type' => 'x']],
            'no_relationship' => ['columns' => []],
            'not_a_table' => 'x',
            'uamswp_locations' => $this->definition('uamswp_locations', $this->locations()),
        ]];
        file_put_contents($dir.'/.cache/_table_map.php', '<?php return '.var_export($map, true).';');

        $this->assertSame([
            'table `no_columns` in the cache has no `columns` list or `relationship` object',
            'table `no_relationship` in the cache has no `columns` list or `relationship` object',
            'table `not_a_table` in the cache has no `columns` list or `relationship` object',
        ], uamswp_fad_acf_table_map_check($dir)['problems']);
    }

    public function test_a_cache_whose_tables_are_not_a_list_reads_as_one_entry_that_is_not_a_table(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        file_put_contents($dir.'/.cache/_table_map.php', "<?php return ['tables' => 'x'];");

        $this->assertSame([
            'table `0` in the cache has no `columns` list or `relationship` object',
            'table `uamswp_locations` is declared in JSON but absent from the cache',
        ], uamswp_fad_acf_table_map_check($dir)['problems']);
    }

    public function test_column_names_that_are_not_text_compare_as_text_and_a_list_of_names_is_left_out(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations()], []);
        $json = $this->definition('uamswp_locations', $this->locations());
        $json['columns'] = [['name' => 5], ['name' => ['nested']], ['name' => 'location_city']];
        file_put_contents($dir.'/table_uamswp_locations.json', json_encode($json));
        $cached = $json;
        $cached['columns'] = [['name' => '5'], ['name' => ['other']], ['name' => 'location_city']];
        file_put_contents($dir.'/.cache/_table_map.php', '<?php return '.var_export(['tables' => ['uamswp_locations' => $cached]], true).';');

        $errors = [];
        set_error_handler(static function (int $level, string $message) use (&$errors): bool {
            $errors[] = $message;

            return true;
        });

        try {
            $check = uamswp_fad_acf_table_map_check($dir);
        } finally {
            restore_error_handler();
        }

        $this->assertSame(['readable' => true, 'tables' => 1, 'problems' => []], $check);
        $this->assertSame([], $errors);
    }

    public function test_a_long_list_of_columns_is_counted_and_sampled_by_its_first_three(): void
    {
        $dir = $this->fixture(
            ['uamswp_locations' => $this->locations('a', 'b', 'c', 'd', 'location_city')],
            ['uamswp_locations' => $this->locations('location_city', 'w', 'x', 'y', 'z')]
        );

        $this->assertSame([
            'table `uamswp_locations`: 4 column(s) in JSON but not the cache (e.g. a, b, c)',
            'table `uamswp_locations`: 4 column(s) in the cache but not the JSON (e.g. w, x, y)',
        ], uamswp_fad_acf_table_map_check($dir)['problems']);
    }

    public function test_the_notice_prints_the_whole_message(): void
    {
        $dir = $this->fixture(['uamswp_locations' => $this->locations('location_phone', 'location_city', 'location_suite')], ['uamswp_locations' => $this->locations()]);

        $this->assertSame(
            '<div class="notice notice-error"><p><strong>The ACF custom table map is out of date.</strong> The plugin reads a cached copy of the table definitions, and the cache no longer matches them. Open'
            .' <a href="https://example.test/wp-admin/edit.php?post_type=acf-field-group&page=acf-custom-database-tables">Custom Fields &gt; Database Tables</a> '
            .'and run Create/Update Tables.</p><ul><li>table `uamswp_locations`: 1 column(s) in JSON but not the cache (e.g. location_suite)</li></ul>'
            .'<p><em>This check compares the cache with the table definition files only. It does not compare the ACF field groups with the table definitions.</em></p></div>',
            $this->notice($dir, true)
        );
    }

    public function test_the_notice_lists_exactly_five_differences_with_no_count_of_more(): void
    {
        $tables = [];

        foreach (range(1, 5) as $i) {
            $tables['table_'.$i] = $this->locations();
        }

        $html = $this->notice($this->fixture($tables, []), true);

        $this->assertSame(5, substr_count($html, '<li>'));
        $this->assertStringNotContainsString('more', $html);
    }

    public function test_the_notice_counts_the_differences_past_five_in_a_list_item(): void
    {
        $tables = [];

        foreach (range(1, 7) as $i) {
            $tables['table_'.$i] = $this->locations();
        }

        $this->assertStringContainsString('absent from the cache</li><li>and 2 more</li></ul>', $this->notice($this->fixture($tables, []), true));
    }
}
