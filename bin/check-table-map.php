<?php

declare(strict_types=1);
/**
 * Command line check: does `.cache/_table_map.php` agree with the `table_*.json` files?
 *
 * Usage: php bin/check-table-map.php [directory]
 *
 * The directory defaults to assets/json/acf-tables. Exit 0 when they agree, 1 when they differ
 * (each difference is printed), 2 when the cache cannot be read.
 *
 * Scope: the cache against the table JSON only. The ACF field groups are not read, so a green
 * result does not say the table definitions cover every field a group declares.
 */
define('WPINC', 'wp-includes');

require_once dirname(__DIR__).'/includes/class.fad-acf-table-map-check.php';

$dir = $argv[1] ?? dirname(__DIR__).'/assets/json/acf-tables';
$check = uamswp_fad_acf_table_map_check($dir);

if (! $check['readable']) {
    fwrite(STDERR, $check['problems'][0]."\n");

    exit(2);
}

if ($check['problems'] === []) {
    echo "Table map is current: {$check['tables']} table(s) agree with their JSON (cache against table JSON only; the field groups are not checked).\n";

    exit(0);
}

fwrite(STDERR, "\nThe ACF table map cache is out of date. Run Create/Update Tables, then commit .cache/_table_map.php\n\n");

foreach ($check['problems'] as $problem) {
    fwrite(STDERR, "  - {$problem}\n");
}

fwrite(STDERR, "\n");

exit(1);
