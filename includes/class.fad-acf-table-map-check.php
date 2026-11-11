<?php

declare(strict_types=1);
/**
 * Does the ACF Custom Database Tables cache still agree with the table definition files?
 *
 * The plugin reads `.cache/_table_map.php` at runtime, not the `table_*.json` files it was built
 * from. When the two disagree, a deploy can leave the plugin reading a table that no longer
 * matches the definition, until someone runs Create/Update Tables.
 *
 * The comparison is on content, never on file times: git sets every file's time to checkout time,
 * so after a deploy both files carry the same stamp in an arbitrary order, and a time check would
 * always pass.
 *
 * Scope: this covers one link only, the cache against the table JSON. It cannot see the ACF field
 * groups, so it does not report a table definition that has fallen behind the fields a group
 * declares. A passing result says the cache is current, not that the tables are complete.
 *
 * No WordPress is needed. The cache file opens with `defined('WPINC') or die(...)` and returns an
 * array, so a caller outside WordPress defines `WPINC` and requires this file.
 */
if (! defined('WPINC')) {
    exit;
}

/**
 * Compare the table map cache with the table definition files in one directory.
 *
 * Reads `table_*.json` files in `$dir` and `$dir/.cache/_table_map.php`, and compares each
 * table's post type and column names. Nothing is written.
 *
 * `readable` is false when the cache cannot be loaded as an array, and then `problems` holds the
 * reason. A table file that is not valid JSON, or names no table, is reported as a problem of its
 * own rather than read as an empty table.
 *
 * @param  string  $dir  Directory that holds the table JSON files and the `.cache` folder.
 * @return array{readable: bool, tables: int, problems: list<string>}
 */
function uamswp_fad_acf_table_map_check(string $dir): array
{
    $cache_file = rtrim($dir, '/\\').'/.cache/_table_map.php';

    if (! is_dir($dir) || ! is_file($cache_file)) {
        return ['readable' => false, 'tables' => 0, 'problems' => ["Cannot read the table map cache at {$cache_file}"]];
    }

    try {
        $map = require $cache_file;
    } catch (Throwable $throwable) {
        return ['readable' => false, 'tables' => 0, 'problems' => ["Cannot load the table map cache at {$cache_file}: {$throwable->getMessage()}"]];
    }

    if (! is_array($map)) {
        return ['readable' => false, 'tables' => 0, 'problems' => ["The table map cache at {$cache_file} does not return an array"]];
    }

    $problems = [];
    $declared = [];
    $files = glob(rtrim($dir, '/\\').'/table_*.json');

    foreach ($files === false ? [] : $files as $file) {
        $json = json_decode((string) file_get_contents($file), true);

        if (! is_array($json) || ! isset($json['name']) || ! is_string($json['name'])) {
            $problems[] = basename($file).' is not valid JSON with a table name';

            continue;
        }

        if (! is_array($json['columns'] ?? null) || ! is_array($json['relationship'] ?? null)) {
            $problems[] = basename($file).' has no `columns` list or `relationship` object';

            continue;
        }

        $declared[$json['name']] = [
            'post_type' => $json['relationship']['post_type'] ?? null,
            'columns' => array_column($json['columns'], 'name'),
        ];
    }

    $cached = [];

    foreach ((array) ($map['tables'] ?? []) as $name => $table) {
        if (! is_array($table) || ! is_array($table['columns'] ?? null) || ! is_array($table['relationship'] ?? null)) {
            $problems[] = "table `{$name}` in the cache has no `columns` list or `relationship` object";

            continue;
        }

        $cached[$name] = [
            'post_type' => $table['relationship']['post_type'] ?? null,
            'columns' => array_column($table['columns'], 'name'),
        ];
    }

    foreach ($declared as $name => $want) {
        if (! isset($cached[$name])) {
            $problems[] = "table `{$name}` is declared in JSON but absent from the cache";

            continue;
        }

        $have = $cached[$name];

        if ($want['post_type'] !== $have['post_type']) {
            $problems[] = sprintf('table `%s`: JSON says post_type `%s`, cache says `%s`', $name, uamswp_fad_str($want['post_type'] ?? 'null'), uamswp_fad_str($have['post_type'] ?? 'null'));
        }

        $missing = array_values(array_diff($want['columns'], $have['columns']));
        $extra = array_values(array_diff($have['columns'], $want['columns']));

        if ($missing !== []) {
            $problems[] = sprintf('table `%s`: %d column(s) in JSON but not the cache (e.g. %s)', $name, count($missing), implode(', ', array_slice($missing, 0, 3)));
        }

        if ($extra !== []) {
            $problems[] = sprintf('table `%s`: %d column(s) in the cache but not the JSON (e.g. %s)', $name, count($extra), implode(', ', array_slice($extra, 0, 3)));
        }
    }

    foreach (array_diff(array_keys($cached), array_keys($declared)) as $orphan) {
        $problems[] = "table `{$orphan}` is in the cache but has no JSON file";
    }

    return ['readable' => true, 'tables' => count($declared), 'problems' => $problems];
}
