<?php

/**
 * The dead link cleanup (includes/class.fad-dead-link-cleanup.php): the dry run, the removal and its undo, the
 * handlers, which redirect and then end the request through `uamswp_fad_exit()`, and the screen.
 *
 * The scan, removal and undo read and write the database. Here they run against `DeadLinksDb`, an in-memory
 * database that answers only the statements the file sends; the Integration suite runs them against MySQL.
 * The file registers its hooks as it loads, so it is loaded once per process from the first test that asks for
 * it, and the registrations are asserted there.
 */

declare(strict_types=1);

use UamswpFindADoc\Tests\Support\DeadLinksDb;
use UamswpFindADoc\Tests\Support\ExitCalled;

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    $this->get_backup = $_GET;
});

afterEach(function (): void {
    $_GET = $this->get_backup;
    unset($GLOBALS['wpdb']);
});

/**
 * Load the dead link cleanup file once, and return the hooks it registered as it loaded.
 *
 * @return list<array<int, mixed>>
 */
function dead_links_load(): array
{
    /** @var list<array<int, mixed>>|null $registered */
    static $registered = null;

    if ($registered !== null) {
        return $registered;
    }

    $registered = [];
    when('add_action')->alias(static function (mixed ...$arguments) use (&$registered): bool {
        $registered[] = $arguments;

        return true;
    });

    require_once UAMS_FAD_PATH.'includes/class.fad-dead-link-cleanup.php';

    return $registered;
}

/**
 * Stub the WordPress calls a handler makes, and record each redirect it sends.
 *
 * @param  array<string, mixed>|null  $run  The stored run, or null for none.
 * @return ArrayObject<int, string> The addresses redirected to, in order.
 */
function dead_links_world(?array $run = null): ArrayObject
{
    /** @var ArrayObject<int, string> $redirects */
    $redirects = new ArrayObject;

    when('current_user_can')->justReturn(true);
    when('check_admin_referer')->justReturn(1);
    when('admin_url')->alias(static fn (string $path = ''): string => 'https://example.test/wp-admin/'.$path);
    when('add_query_arg')->alias(static function (string|array $key, mixed ...$rest): string {
        if (is_array($key)) {
            return $rest[0].(str_contains((string) $rest[0], '?') ? '&' : '?').http_build_query($key);
        }

        return $rest[1].(str_contains((string) $rest[1], '?') ? '&' : '?').$key.'='.$rest[0];
    });
    when('wp_nonce_url')->alias(static fn (string $url, string $action): string => $url.'&_wpnonce='.$action);
    when('wp_safe_redirect')->alias(static function (string $location) use ($redirects): bool {
        $redirects[] = $location;

        return true;
    });
    when('get_option')->alias(static fn (string $name, mixed $default = false): mixed => $name === UAMSWP_DEAD_LINKS_RUN_OPTION ? $run : $default);
    when('add_option')->justReturn(true);
    when('delete_option')->justReturn(true);
    // The steps check that ACF is active.
    when('get_field')->justReturn();
    when('update_field')->justReturn(true);

    return $redirects;
}

/**
 * A stored run with the given status.
 *
 * @return array<string, mixed>
 */
function dead_links_stored_run(string $status): array
{
    return ['status' => $status, 'cursor' => 0, 'scanned_at' => 1700000000, 'items' => [], 'counts' => ['rows' => []]];
}

test('loading the file hooks the screen on admin_menu at priority 106 and the three steps on admin_post', function (): void {
    // The constants are read here too: this test loads the file, so it is the one a mutation run picks for them.
    expect(dead_links_load())->toBe([
        ['admin_menu', 'uamswp_dead_links_menu', 106],
        ['admin_post_uamswp_dead_links_scan', 'uamswp_dead_links_handle_scan'],
        ['admin_post_uamswp_dead_links_apply', 'uamswp_dead_links_handle_apply'],
        ['admin_post_uamswp_dead_links_undo', 'uamswp_dead_links_handle_undo'],
    ])
        ->and(UAMSWP_DEAD_LINKS_BATCH)->toBe(25)
        ->and(UAMSWP_DEAD_LINKS_RUN_OPTION)->toBe('uamswp_fad_dead_links_run')
        ->and(UAMSWP_DEAD_LINKS_ROWS_OPTION)->toBe('uamswp_fad_dead_links_rows');
});

test('going back to the screen redirects there with the notice and ends the request', function (string $notice, string $expected): void {
    dead_links_load();
    $redirects = dead_links_world();

    expect(fn () => uamswp_dead_links_back($notice))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe([$expected]);
})->with([
    'a notice' => ['stale', 'https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=stale'],
    'no notice' => ['', 'https://example.test/wp-admin/admin.php?page=uamswp-dead-links'],
]);

test('a step that leaves the run unfinished redirects to the next step and ends the request', function (string $action, string $from, string $status): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_stored_run($from));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step($action, [$from], static fn (array $run): array => ['status' => $status] + $run, 'done'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin-post.php?action='.$action.'&run=1700000000&_wpnonce='.$action]);
})->with([
    'a removal' => ['uamswp_dead_links_apply', 'scanned', 'applying'],
    'an undo' => ['uamswp_dead_links_undo', 'applied', 'undoing'],
]);

test('a step that finishes or fails the run goes back to the screen instead of the next step', function (string $status, string $notice): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_stored_run('scanned'));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned'], static fn (array $run): array => ['status' => $status] + $run, 'applied'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice='.$notice]);
})->with([
    'finished' => ['applied', 'applied'],
    'failed' => ['failed', 'failed'],
    'any other status' => ['scanned', 'applied'],
]);

/**
 * Whether a value is serialized data, by the shape test WordPress' `is_serialized()` makes in strict mode.
 */
function dead_links_is_serialized(mixed $data): bool
{
    if (! is_string($data)) {
        return false;
    }

    $data = trim($data);

    if ($data === 'N;') {
        return true;
    }

    if (strlen($data) < 4 || $data[1] !== ':' || ! in_array(substr($data, -1), [';', '}'], true)) {
        return false;
    }

    return match ($data[0]) {
        's' => $data[strlen($data) - 2] === '"' && preg_match('/^s:\d+:/', $data) === 1,
        'a', 'O', 'E' => preg_match('/^'.$data[0].':\d+:/', $data) === 1,
        'b', 'i', 'd' => preg_match('/^'.$data[0].':[0-9.E+-]+;$/', $data) === 1,
        default => false,
    };
}

/**
 * Load the file and give it an in-memory database, with the WordPress and ACF calls it makes stubbed over it.
 *
 * Both custom tables exist and are empty. `get_field()` reads a field's post meta row and `update_field()`
 * replaces it, serializing a list the way ACF does; each call is recorded in `$db->calls`.
 */
function dead_links_db(): DeadLinksDb
{
    dead_links_load();
    require_once UAMS_FAD_PATH.'tests/Support/wp-classes.php';
    defined('ARRAY_A') || define('ARRAY_A', 'ARRAY_A');

    $db = new DeadLinksDb;
    $db->tables = ['uamswp_physicians' => [], 'uamswp_locations' => []];
    $GLOBALS['wpdb'] = $db;

    $unserialize = static fn (string $value): mixed => dead_links_is_serialized($value) ? unserialize($value) : $value;
    $serialize = static fn (mixed $value): string => is_array($value) || is_object($value) ? serialize($value) : (string) $value;

    when('is_serialized')->alias(dead_links_is_serialized(...));
    when('maybe_serialize')->alias(static fn (mixed $value): mixed => is_array($value) || is_object($value) ? serialize($value) : $value);
    // As in WordPress: a missing option is remembered in the request's notoptions, and add_option() skips its
    // check for an existing row when notoptions lists the name, then overwrites whatever row is there.
    $notoptions = new ArrayObject;
    when('get_option')->alias(static function (string $name, mixed $default = false) use ($db, $unserialize, $notoptions): mixed {
        if (isset($db->option_values[$name])) {
            return $unserialize($db->option_values[$name]);
        }

        $notoptions[$name] = true;

        return $default;
    });
    when('update_option')->alias(static function (string $name, mixed $value, mixed $autoload = null) use ($db, $serialize): bool {
        $db->calls[] = ['update_option', $name, $autoload];
        $db->store_option($name, $serialize($value));

        return true;
    });
    when('add_option')->alias(static function (string $name, mixed $value = '', string $deprecated = '', mixed $autoload = 'yes') use ($db, $serialize, $notoptions): bool {
        $db->calls[] = ['add_option', $name, $value, $deprecated, $autoload];

        if ($db->before !== null) {
            ($db->before)('add_option '.$name);
        }

        if (! isset($notoptions[$name]) && isset($db->option_values[$name])) {
            return false;
        }

        unset($notoptions[$name]);
        $db->store_option($name, $serialize($value));

        return true;
    });
    when('delete_option')->alias(static function (string $name) use ($db): bool {
        $db->calls[] = ['delete_option', $name];

        if ($db->before !== null) {
            ($db->before)('delete_option '.$name);
        }

        unset($db->option_values[$name]);

        return true;
    });
    when('get_post')->alias(static function (int $id) use ($db): ?WP_Post {
        if (! isset($db->post_types[$id])) {
            return null;
        }

        $post = new WP_Post;
        $post->ID = $id;

        return $post;
    });
    when('get_field')->alias(static function (string $field, int $post, bool $format = true) use ($db, $unserialize): mixed {
        $db->calls[] = ['get_field', $field, $post, $format];

        foreach ($db->meta_of($post) as [$key, $value]) {
            if ($key === $field) {
                return $unserialize($value);
            }
        }

        return null;
    });
    when('update_field')->alias(static function (string $field, mixed $value, int $post) use ($db, $serialize): bool {
        $db->calls[] = ['update_field', $field, $value, $post];

        // In place, keeping the row's position, as update_post_meta() keeps its meta_id.
        foreach ($db->meta as $i => $row) {
            if ($row['post'] === $post && $row['key'] === $field) {
                $db->meta[$i]['value'] = $serialize($value);

                return true;
            }
        }

        $db->add_meta($post, $field, $serialize($value));

        return true;
    });
    when('wp_cache_delete')->alias(static function (int|string $key, string $group) use ($db): bool {
        $db->calls[] = ['wp_cache_delete', $key, $group];

        return true;
    });

    return $db;
}

/**
 * A link item as a dry run lists it.
 *
 * @param  array<int, int>  $dead
 * @param  array<string, mixed>  $more
 * @return array<string, mixed>
 */
function dead_links_link(int $post, string $field, string $shape, array $dead, string $state = 'pending', array $more = []): array
{
    return ['kind' => 'link', 'post' => $post, 'field' => $field, 'shape' => $shape, 'dead' => $dead, 'state' => $state] + $more;
}

/**
 * A row item as a dry run lists it.
 *
 * @param  array<string, mixed>  $more
 * @return array<string, mixed>
 */
function dead_links_row(string $table, int $post, string $state = 'pending', array $more = []): array
{
    return ['kind' => 'row', 'table' => $table, 'post' => $post, 'state' => $state] + $more;
}

/**
 * A run over the given items.
 *
 * @param  array<int, array<string, mixed>>  $items
 * @return array<string, mixed>
 */
function dead_links_make_run(array $items, string $status = 'scanned', int $cursor = 0): array
{
    return ['status' => $status, 'scanned_at' => 1700000000, 'cursor' => $cursor, 'items' => $items, 'counts' => uamswp_dead_links_counts(['items' => $items])];
}

test('the sources are every link field of the delete cleanup map once, by post type, and the two custom tables', function (): void {
    dead_links_load();

    expect(uamswp_dead_links_sources())->toBe([
        'meta' => [
            ['clinical-resource', 'clinical_resource_providers', 'list'],
            ['clinical-resource', 'clinical_resource_spotlight_provider', 'single'],
            ['condition', 'condition_physicians', 'list'],
            ['treatment', 'treatment_procedure_physicians', 'list'],
            ['expertise', 'physician_expertise', 'list'],
            ['clinical-resource', 'clinical_resource_locations', 'list'],
            ['condition', 'condition_locations', 'list'],
            ['treatment', 'treatment_procedure_locations', 'list'],
            ['expertise', 'location_expertise', 'list'],
        ],
        'tables' => [
            ['uamswp_locations', 'physician_locations'],
            ['uamswp_physicians', 'physician_locations'],
        ],
        'own' => ['uamswp_physicians', 'uamswp_locations'],
    ]);
});

test('a field or table named twice in the map is a source once', function (): void {
    dead_links_load();
    $twice = [
        'meta' => ['condition' => [['condition_physicians', 'list']]],
        'tables' => [['uamswp_locations', 'physician_locations', 'location']],
        'own_table' => 'uamswp_physicians',
    ];
    when('uamswp_delete_cleanup_map')->justReturn(['provider' => $twice, 'again' => $twice]);

    expect(uamswp_dead_links_sources())->toBe([
        'meta' => [['condition', 'condition_physicians', 'list']],
        'tables' => [['uamswp_locations', 'physician_locations']],
        'own' => ['uamswp_physicians'],
    ]);
});

test('a custom table exists when SHOW TABLES names it with the prefix', function (): void {
    $db = dead_links_db();

    expect(uamswp_dead_links_table_exists('uamswp_locations'))->toBeTrue()
        ->and(uamswp_dead_links_table_exists('uamswp_gone'))->toBeFalse()
        ->and($db->queries)->toBe(["SHOW TABLES LIKE 'wp\\\\_uamswp\\\\_locations'", "SHOW TABLES LIKE 'wp\\\\_uamswp\\\\_gone'"]);
});

test('the missing IDs are the positive IDs that name no post, each once, in the order given', function (): void {
    $db = dead_links_db();
    $db->post(5, 'provider');
    $db->post(7, 'location');

    expect(uamswp_dead_links_missing([9, 5, 0, -3, 8, 9, 7]))->toBe([9, 8])
        ->and($db->queries)->toBe(['SELECT ID FROM wp_posts WHERE ID IN (9,5,8,7)']);
});

test('no IDs need no query', function (): void {
    $db = dead_links_db();

    expect(uamswp_dead_links_missing([0, -1]))->toBe([])
        ->and($db->queries)->toBe([]);
});

test('the posts are looked up 500 IDs at a time', function (): void {
    $db = dead_links_db();
    $db->post(501, 'provider');

    expect(uamswp_dead_links_missing(range(1, 501)))->toBe(range(1, 500))
        ->and($db->queries)->toHaveCount(2)
        ->and($db->queries[0])->toBe('SELECT ID FROM wp_posts WHERE ID IN ('.implode(',', range(1, 500)).')')
        ->and($db->queries[1])->toBe('SELECT ID FROM wp_posts WHERE ID IN (501)');
});

test('a stored link value is read whichever way it is stored', function (string $value, array $ids): void {
    dead_links_db();

    expect(uamswp_dead_links_stored_ids($value))->toBe($ids);
})->with([
    'empty' => ['', []],
    'a serialized list of strings' => [serialize(['12', '0', '13']), [12, 13]],
    'a serialized list of integers' => [serialize([12, 13]), [12, 13]],
    'a JSON list' => ['[14,"15"]', [14, 15]],
    'a JSON object' => ['{"a":16}', [16]],
    'one ID' => ['17', [17]],
    'a JSON string' => ['"18"', []],
]);

test('a serialized object in a link value is not built', function (): void {
    dead_links_db();
    $post = new stdClass;
    $post->ID = 19;

    // The incomplete object it is read as raises a warning when its ID is looked for; the value is what is tested.
    set_error_handler(static fn (): bool => true, E_WARNING);

    try {
        $ids = uamswp_dead_links_stored_ids(serialize([$post]));
    } finally {
        restore_error_handler();
    }

    expect($ids)->toBe([]);
});

test('saving an option writes it without autoload and is true when the database holds the same value', function (): void {
    $db = dead_links_db();

    expect(uamswp_dead_links_save('uamswp_fad_test', ['a' => 1]))->toBeTrue()
        ->and($db->option_values['uamswp_fad_test'])->toBe(serialize(['a' => 1]))
        ->and($db->calls)->toBe([['update_option', 'uamswp_fad_test', false]])
        ->and($db->queries)->toBe(["SELECT MD5(option_value) FROM wp_options WHERE option_name = 'uamswp_fad_test'"]);
});

test('saving an option is false when the database does not hold the value written', function (): void {
    $db = dead_links_db();
    $db->option_values['uamswp_fad_test'] = serialize(['old']);
    $db->lose['uamswp_fad_test'] = true;

    expect(uamswp_dead_links_save('uamswp_fad_test', ['new']))->toBeFalse();

    $db->option_values = [];

    expect(uamswp_dead_links_save('uamswp_fad_test', ['new']))->toBeFalse();
});

test('the lock is taken when nobody holds it, by inserting its row with the time and no autoload', function (): void {
    // Repeated until the clock did not tick during the call, so the time written is the time read.
    do {
        $db = dead_links_db();
        $now = time();
        $taken = uamswp_dead_links_lock();
    } while (time() !== $now);

    expect($taken)->toBeTrue()
        ->and($db->option_values)->toBe(['uamswp_fad_dead_links_lock' => (string) $now])
        ->and($db->queries)->toBe([
            "SELECT option_value FROM wp_options WHERE option_name = 'uamswp_fad_dead_links_lock'",
            "INSERT IGNORE INTO wp_options (option_name, option_value, autoload) VALUES ('uamswp_fad_dead_links_lock', '".$now."', 'no')",
        ])
        ->and($db->calls)->toBe([['wp_cache_delete', 'uamswp_fad_dead_links_lock', 'options'], ['wp_cache_delete', 'notoptions', 'options']]);
});

test('a lock held for less than five minutes is not taken', function (int $age): void {
    // Repeated until the clock did not tick during the call, so a boundary age is read in the second it was set.
    do {
        $db = dead_links_db();
        $now = time();
        $db->option_values['uamswp_fad_dead_links_lock'] = (string) ($now - $age);
        $taken = uamswp_dead_links_lock();
    } while (time() !== $now);

    expect($taken)->toBeFalse()
        ->and($db->option_values)->toBe(['uamswp_fad_dead_links_lock' => (string) ($now - $age)])
        ->and($db->queries)->toHaveCount(1)
        ->and($db->calls)->toBe([]);
})->with(['just taken' => [0], 'four minutes' => [240], 'a second short of five minutes' => [299]]);

test('a lock held for five minutes or more is taken over', function (int $age): void {
    do {
        $db = dead_links_db();
        $now = time();
        $db->option_values['uamswp_fad_dead_links_lock'] = (string) ($now - $age);
        $taken = uamswp_dead_links_lock();
    } while (time() !== $now);

    expect($taken)->toBeTrue()
        ->and($db->option_values)->toBe(['uamswp_fad_dead_links_lock' => (string) $now])
        ->and($db->queries[1])->toBe("DELETE FROM wp_options WHERE option_name = 'uamswp_fad_dead_links_lock' AND option_value = '".($now - $age)."'");
})->with(['five minutes' => [300], 'an hour' => [3600]]);

test('a lock another request takes between this one reading it and adding it is not taken over', function (): void {
    $db = dead_links_db();
    // The other request adds its row once this one has read that there is none, just before this one adds its own.
    $db->before = static function (string $sql) use ($db): void {
        if (str_starts_with($sql, 'INSERT IGNORE') || str_starts_with($sql, 'add_option')) {
            $db->option_values['uamswp_fad_dead_links_lock'] = 'the other request';
        }
    };

    expect(uamswp_dead_links_lock())->toBeFalse()
        ->and($db->option_values)->toBe(['uamswp_fad_dead_links_lock' => 'the other request']);
});

test('of two requests taking over the same stale lock, the one that deletes it second does not take it', function (): void {
    $db = dead_links_db();
    $stale = (string) (time() - 600);
    $db->option_values['uamswp_fad_dead_links_lock'] = $stale;
    // The other request read the same stale lock and took it over first.
    $db->before = static function (string $sql) use ($db, $stale): void {
        if ((str_starts_with($sql, 'DELETE') || str_starts_with($sql, 'delete_option')) && ($db->option_values['uamswp_fad_dead_links_lock'] ?? null) === $stale) {
            $db->option_values['uamswp_fad_dead_links_lock'] = 'the other request';
        }
    };

    expect(uamswp_dead_links_lock())->toBeFalse()
        ->and($db->option_values)->toBe(['uamswp_fad_dead_links_lock' => 'the other request']);
});

test('the lock is not taken when its insert fails', function (): void {
    $db = dead_links_db();
    $db->before = static function (string $sql): void {
        if (str_starts_with($sql, 'INSERT IGNORE')) {
            throw new LogicException('the insert failed');
        }
    };

    expect(fn () => uamswp_dead_links_lock())->toThrow(LogicException::class, 'the insert failed');
});

test('unlocking deletes the lock', function (): void {
    $db = dead_links_db();
    $db->option_values['uamswp_fad_dead_links_lock'] = '1';

    uamswp_dead_links_unlock();

    expect($db->calls)->toBe([['delete_option', 'uamswp_fad_dead_links_lock']])
        ->and($db->option_values)->toBe([]);
});

test('the counts total the links, records, dead IDs, rows by table and the states', function (): void {
    dead_links_load();

    expect(uamswp_dead_links_counts(['items' => [
        dead_links_link(10, 'condition_physicians', 'list', [99, 98], 'done'),
        dead_links_link(10, 'condition_locations', 'list', [98], 'skipped'),
        dead_links_link(11, 'physician_expertise', 'list', [97], 'failed'),
        dead_links_link(12, 'physician_expertise', 'list', [99], 'pending'),
        dead_links_row('uamswp_physicians', 96, 'done'),
        dead_links_row('uamswp_physicians', 95, 'skipped'),
        dead_links_row('uamswp_locations', 94, 'restored'),
    ]]))->toBe(['links' => 5, 'rows' => ['uamswp_physicians' => 2, 'uamswp_locations' => 1], 'done' => 2, 'skipped' => 2, 'failed' => 1, 'records' => 3, 'dead_ids' => 3]);
});

test('an empty list counts nothing', function (): void {
    dead_links_load();

    expect(uamswp_dead_links_counts(['items' => []]))->toBe(['links' => 0, 'rows' => [], 'done' => 0, 'skipped' => 0, 'failed' => 0, 'records' => 0, 'dead_ids' => 0]);
});

test('a link field is read through ACF unformatted, as IDs', function (): void {
    $db = dead_links_db();
    $db->add_meta(10, 'condition_physicians', serialize(['20', '99']));

    expect(uamswp_dead_links_read('condition_physicians', 10))->toBe([20, 99])
        ->and($db->calls)->toBe([['get_field', 'condition_physicians', 10, false]]);
});

test('a link field is written through ACF, a list as ID strings and a cleared single field as an empty string', function (): void {
    $db = dead_links_db();

    uamswp_dead_links_write('condition_physicians', [20, 21], 10);
    uamswp_dead_links_write('clinical_resource_spotlight_provider', '', 12);

    expect($db->calls)->toBe([
        ['update_field', 'condition_physicians', ['20', '21'], 10],
        ['update_field', 'clinical_resource_spotlight_provider', '', 12],
    ]);
});

/**
 * A database with dead IDs in post meta fields of each shape, in both custom tables, and an orphan row in each.
 *
 * 94 to 99 name no post.
 */
function dead_links_scan_world(): DeadLinksDb
{
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->post(11, 'expertise');
    $db->post(12, 'clinical-resource');
    $db->post(20, 'provider');
    $db->post(30, 'location');

    $db->add_meta(12, 'clinical_resource_spotlight_provider', '98');
    $db->add_meta(12, 'clinical_resource_providers', serialize(['20']));
    $db->add_meta(10, 'condition_physicians', serialize(['20', '99']));
    $db->add_meta(10, 'condition_locations', '');
    // A key of another post type's field is not that field.
    $db->add_meta(10, 'physician_expertise', serialize(['99']));
    $db->add_meta(11, 'physician_expertise', '[97,20]');

    $db->tables['uamswp_locations'] = [
        ['post_id' => '30', 'physician_locations' => serialize(['99', '20'])],
        ['post_id' => '31', 'physician_locations' => ''],
        ['post_id' => '94', 'physician_locations' => serialize(['20'])],
    ];
    $db->tables['uamswp_physicians'] = [
        ['post_id' => '20', 'physician_locations' => serialize(['96'])],
        ['post_id' => '30', 'physician_locations' => serialize(['95', '99'])],
        ['post_id' => '96', 'physician_locations' => null],
    ];
    $db->post(31, 'location');

    return $db;
}

test('a dry run lists each field holding a dead ID once, and each orphan row, and keeps the run and the rows', function (): void {
    $db = dead_links_scan_world();
    $before = time();

    $run = uamswp_dead_links_scan();

    $items = [
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98]),
        dead_links_link(10, 'condition_physicians', 'list', [99]),
        dead_links_link(11, 'physician_expertise', 'list', [97]),
        dead_links_link(30, 'physician_locations', 'list', [99, 95]),
        dead_links_link(20, 'physician_locations', 'list', [96]),
        dead_links_row('uamswp_physicians', 96),
        dead_links_row('uamswp_locations', 94),
    ];

    expect($run['scanned_at'])->toBeGreaterThanOrEqual($before)->toBeLessThanOrEqual(time())
        ->and($run)->toBe([
            'status' => 'scanned',
            'scanned_at' => $run['scanned_at'],
            'cursor' => 0,
            'items' => $items,
            'counts' => ['links' => 6, 'rows' => ['uamswp_physicians' => 1, 'uamswp_locations' => 1], 'done' => 0, 'skipped' => 0, 'failed' => 0, 'records' => 5, 'dead_ids' => 5],
        ])
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION]))->toBe($run)
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION]))->toBe([
            'uamswp_physicians|96' => ['post_id' => '96', 'physician_locations' => null],
            'uamswp_locations|94' => ['post_id' => '94', 'physician_locations' => serialize(['20'])],
        ])
        ->and(array_column($db->calls, 1))->toBe([UAMSWP_DEAD_LINKS_ROWS_OPTION, UAMSWP_DEAD_LINKS_RUN_OPTION]);
});

test('a dry run that is only a check keeps nothing', function (): void {
    $db = dead_links_scan_world();

    $run = uamswp_dead_links_scan(false);

    expect($run['counts']['links'])->toBe(6)
        ->and($db->option_values)->toBe([])
        ->and($db->calls)->toBe([]);
});

test('a dry run skips a custom table that does not exist', function (): void {
    $db = dead_links_scan_world();
    unset($db->tables['uamswp_physicians']);

    $run = uamswp_dead_links_scan(false);

    expect($run['items'])->toBe([
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98]),
        dead_links_link(10, 'condition_physicians', 'list', [99]),
        dead_links_link(11, 'physician_expertise', 'list', [97]),
        dead_links_link(30, 'physician_locations', 'list', [99]),
        dead_links_row('uamswp_locations', 94),
    ]);
});

test('a dry run of a clean site lists nothing', function (): void {
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->add_meta(10, 'condition_physicians', serialize(['10']));

    expect(uamswp_dead_links_scan(false)['items'])->toBe([]);
});

test('the storage of a field is its meta rows in order and its cell in each custom table that has it', function (): void {
    $db = dead_links_db();
    $db->add_meta(30, '_physician_locations', 'field_1');
    $db->add_meta(30, 'other', 'x');
    $db->add_meta(30, 'physician_locations', serialize([1 => '20', 3 => '']));
    $db->add_meta(31, 'physician_locations', 'not this post');
    $db->tables['uamswp_locations'] = [['post_id' => '30', 'physician_locations' => '{"a":"20"}']];
    $db->tables['uamswp_physicians'] = [['post_id' => '30', 'physician_locations' => null]];

    expect(uamswp_dead_links_capture('physician_locations', 30))->toBe([
        'meta' => [['_physician_locations', 'field_1'], ['physician_locations', serialize([1 => '20', 3 => ''])]],
        'cells' => ['uamswp_locations' => '{"a":"20"}', 'uamswp_physicians' => null],
    ])->and($db->queries[0])->toBe("SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = 30 AND meta_key IN ('physician_locations', '_physician_locations') ORDER BY meta_id");
});

test('the storage of a field has no cell for a table without a row for the post, a missing table or a field no table has', function (): void {
    $db = dead_links_db();
    $db->tables['uamswp_physicians'] = [['post_id' => '31', 'physician_locations' => '1']];
    unset($db->tables['uamswp_locations']);
    $db->add_meta(10, 'condition_physicians', serialize(['20']));

    expect(uamswp_dead_links_capture('physician_locations', 30))->toBe(['meta' => [], 'cells' => []])
        ->and(uamswp_dead_links_capture('condition_physicians', 10))->toBe(['meta' => [['condition_physicians', serialize(['20'])]], 'cells' => []]);
});

test('restoring a field deletes its meta rows, writes the saved ones back, clears the meta cache and sets each cell', function (): void {
    $db = dead_links_db();
    $db->add_meta(30, 'physician_locations', serialize(['20']));
    $db->add_meta(30, '_physician_locations', 'field_1');
    $db->add_meta(30, 'other', 'kept');
    $db->add_meta(31, 'physician_locations', 'other post');
    $db->tables['uamswp_locations'] = [['post_id' => '30', 'physician_locations' => serialize(['20'])], ['post_id' => '31', 'physician_locations' => 'x']];

    uamswp_dead_links_restore_storage('physician_locations', [
        'meta' => [['_physician_locations', 'field_1'], ['physician_locations', serialize(['20', '99'])]],
        'cells' => ['uamswp_locations' => serialize(['20', '99'])],
    ], 30);

    expect($db->meta_of(30))->toBe([['other', 'kept'], ['_physician_locations', 'field_1'], ['physician_locations', serialize(['20', '99'])]])
        ->and($db->meta_of(31))->toBe([['physician_locations', 'other post']])
        ->and($db->tables['uamswp_locations'])->toBe([['post_id' => '30', 'physician_locations' => serialize(['20', '99'])], ['post_id' => '31', 'physician_locations' => 'x']])
        ->and($db->calls)->toBe([['wp_cache_delete', 30, 'post_meta']])
        ->and(array_slice($db->writes, 0, 2))->toBe([
            ['delete', 'wp_postmeta', [['post_id' => 30, 'meta_key' => 'physician_locations'], ['%d', '%s']]],
            ['delete', 'wp_postmeta', [['post_id' => 30, 'meta_key' => '_physician_locations'], ['%d', '%s']]],
        ]);
});

test('restoring a field with no saved meta rows leaves it with none, and a cell can be put back to null', function (): void {
    $db = dead_links_db();
    $db->add_meta(30, 'physician_locations', serialize([]));
    $db->add_meta(30, '_physician_locations', 'field_1');
    $db->tables['uamswp_physicians'] = [['post_id' => '30', 'physician_locations' => 'a:0:{}']];

    uamswp_dead_links_restore_storage('physician_locations', ['meta' => [], 'cells' => ['uamswp_physicians' => null]], 30);

    expect($db->meta_of(30))->toBe([])
        ->and($db->tables['uamswp_physicians'])->toBe([['post_id' => '30', 'physician_locations' => null]]);
});

test('a post exists when get_post() finds it, and an ID with no post does not', function (): void {
    $db = dead_links_db();
    $db->post(5, 'provider');

    expect(uamswp_dead_links_post_exists(5))->toBeTrue()
        ->and(uamswp_dead_links_post_exists(6))->toBeFalse();
});

test('the stored run is returned when it has every part', function (): void {
    $db = dead_links_db();
    $run = dead_links_make_run([dead_links_row('uamswp_locations', 94)]);
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize($run);

    expect(uamswp_dead_links_run())->toBe($run);
});

test('what is stored is not a run when a part is missing or of the wrong kind', function (mixed $stored): void {
    $db = dead_links_db();

    if ($stored !== null) {
        $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize($stored);
    }

    expect(uamswp_dead_links_run())->toBeNull();
})->with([
    'nothing' => [null],
    'not an array' => ['scanned'],
    'no status' => [['cursor' => 0, 'scanned_at' => 1, 'items' => [], 'counts' => ['rows' => []]]],
    'no cursor' => [['status' => 'scanned', 'scanned_at' => 1, 'items' => [], 'counts' => ['rows' => []]]],
    'no time' => [['status' => 'scanned', 'cursor' => 0, 'items' => [], 'counts' => ['rows' => []]]],
    'no items' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'counts' => ['rows' => []]]],
    'no counts' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'items' => []]],
    'items not a list' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'items' => 'x', 'counts' => ['rows' => []]]],
    'counts not an array' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'items' => [], 'counts' => 'x']],
    'no row counts' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'items' => [], 'counts' => []]],
    'row counts not an array' => [['status' => 'scanned', 'cursor' => 0, 'scanned_at' => 1, 'items' => [], 'counts' => ['rows' => 1]]],
]);

test('the run and its row backup are kept in two options, and a step handles 25 items', function (): void {
    dead_links_load();

    expect(UAMSWP_DEAD_LINKS_RUN_OPTION)->toBe('uamswp_fad_dead_links_run')
        ->and(UAMSWP_DEAD_LINKS_ROWS_OPTION)->toBe('uamswp_fad_dead_links_rows')
        ->and(UAMSWP_DEAD_LINKS_BATCH)->toBe(25);
});

/**
 * A database and a run with an item for every way a removal can treat one.
 *
 * @return array{0: DeadLinksDb, 1: array<string, mixed>}
 */
function dead_links_apply_world(): array
{
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->post(12, 'clinical-resource');
    $db->post(20, 'provider');
    $db->post(93, 'location');
    $db->post(94, 'location');

    $db->add_meta(10, '_condition_physicians', 'field_cp');
    $db->add_meta(10, 'condition_physicians', serialize(['20', '99']));
    $db->add_meta(12, 'clinical_resource_spotlight_provider', '98');
    $db->add_meta(10, 'condition_locations', serialize(['93']));
    $db->tables['uamswp_physicians'] = [['post_id' => '96', 'physician_locations' => serialize(['20'])]];
    $db->tables['uamswp_locations'] = [['post_id' => '94', 'physician_locations' => '']];
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize(['uamswp_locations|94' => ['post_id' => '94']]);

    return [$db, dead_links_make_run([
        dead_links_link(10, 'condition_physicians', 'list', [99]),
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98]),
        // The record is gone.
        dead_links_link(11, 'physician_expertise', 'list', [97]),
        // The dead ID names a post again.
        dead_links_link(10, 'condition_locations', 'list', [93]),
        dead_links_row('uamswp_physicians', 96),
        // The post is back.
        dead_links_row('uamswp_locations', 94),
        // The row is gone and was never saved.
        dead_links_row('uamswp_locations', 92),
        dead_links_link(10, 'treatment_procedure_physicians', 'list', [91], 'done'),
    ])];
}

test('a removal saves what it replaces, then removes the dead IDs and orphan rows that are still dead', function (): void {
    [$db, $run] = dead_links_apply_world();

    $result = uamswp_dead_links_apply_step($run);

    $items = [
        dead_links_link(10, 'condition_physicians', 'list', [99], 'done', ['before' => [20, 99], 'remove' => [99], 'after' => [20], 'saved' => true]),
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'done', ['before' => [98], 'remove' => [98], 'after' => [], 'saved' => true]),
        dead_links_link(11, 'physician_expertise', 'list', [97], 'skipped'),
        dead_links_link(10, 'condition_locations', 'list', [93], 'skipped', ['before' => [93], 'remove' => [], 'after' => [93]]),
        dead_links_row('uamswp_physicians', 96, 'done', ['saved' => true]),
        dead_links_row('uamswp_locations', 94, 'skipped'),
        dead_links_row('uamswp_locations', 92, 'skipped'),
        dead_links_link(10, 'treatment_procedure_physicians', 'list', [91], 'done'),
    ];

    expect($result)->toBe(['status' => 'applied', 'scanned_at' => 1700000000, 'cursor' => 8, 'items' => $items, 'counts' => uamswp_dead_links_counts(['items' => $items])])
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION]))->toBe($result)
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION]))->toBe([
            'uamswp_locations|94' => ['post_id' => '94'],
            'link|10|condition_physicians' => ['meta' => [['_condition_physicians', 'field_cp'], ['condition_physicians', serialize(['20', '99'])]], 'cells' => []],
            'link|12|clinical_resource_spotlight_provider' => ['meta' => [['clinical_resource_spotlight_provider', '98']], 'cells' => []],
            'uamswp_physicians|96' => ['post_id' => '96', 'physician_locations' => serialize(['20'])],
        ])
        ->and($db->meta_of(10))->toBe([['_condition_physicians', 'field_cp'], ['condition_physicians', serialize(['20'])], ['condition_locations', serialize(['93'])]])
        ->and($db->meta_of(12))->toBe([['clinical_resource_spotlight_provider', '']])
        ->and($db->tables['uamswp_physicians'])->toBe([])
        ->and($db->tables['uamswp_locations'])->toBe([['post_id' => '94', 'physician_locations' => '']])
        ->and($db->writes[0])->toBe(['delete', 'wp_uamswp_physicians', [['post_id' => 96], ['%d']]])
        // The backup and the run are saved before the first write.
        ->and(array_map(static fn (array $call): string => $call[0].' '.$call[1], $db->calls))->toBe([
            'get_field condition_physicians',
            'get_field clinical_resource_spotlight_provider',
            'get_field condition_locations',
            'update_option '.UAMSWP_DEAD_LINKS_ROWS_OPTION,
            'update_option '.UAMSWP_DEAD_LINKS_RUN_OPTION,
            'update_field condition_physicians',
            'update_field clinical_resource_spotlight_provider',
            'update_option '.UAMSWP_DEAD_LINKS_RUN_OPTION,
        ]);
});

test('a removal handles one batch from the cursor and leaves the rest for the next step', function (): void {
    $db = dead_links_db();
    $run = dead_links_make_run([dead_links_row('uamswp_locations', 91), dead_links_row('uamswp_locations', 92), dead_links_row('uamswp_locations', 93)]);

    $first = uamswp_dead_links_apply_step($run, 2);
    $second = uamswp_dead_links_apply_step($first, 2);

    expect($first['status'])->toBe('applying')
        ->and($first['cursor'])->toBe(2)
        ->and(array_column($first['items'], 'state'))->toBe(['skipped', 'skipped', 'pending'])
        ->and($first['counts']['skipped'])->toBe(2)
        ->and($second['status'])->toBe('applied')
        ->and($second['cursor'])->toBe(3)
        ->and(array_column($second['items'], 'state'))->toBe(['skipped', 'skipped', 'skipped']);
});

test('a removal handles 25 items a step by default', function (): void {
    dead_links_db();
    $items = array_map(static fn (int $post): array => dead_links_row('uamswp_locations', $post), range(1, 26));

    $result = uamswp_dead_links_apply_step(dead_links_make_run($items));

    expect($result['cursor'])->toBe(25)
        ->and($result['status'])->toBe('applying')
        ->and($result['items'][24]['state'])->toBe('skipped')
        ->and($result['items'][25]['state'])->toBe('pending');
});

test('a removal that ended after saving an item finishes it, or leaves it when it changed since', function (): void {
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->add_meta(10, 'condition_physicians', serialize(['20']));
    $db->add_meta(10, 'condition_locations', serialize(['20', '99']));
    $db->add_meta(10, 'treatment_procedure_physicians', serialize(['21']));

    $saved = ['before' => [20, 99], 'remove' => [99], 'after' => [20], 'saved' => true];

    $result = uamswp_dead_links_apply_step(dead_links_make_run([
        dead_links_link(10, 'condition_physicians', 'list', [99], 'pending', $saved),
        dead_links_link(10, 'condition_locations', 'list', [99], 'pending', $saved),
        dead_links_link(10, 'treatment_procedure_physicians', 'list', [99], 'pending', $saved),
    ], 'applying'));

    expect(array_column($result['items'], 'state'))->toBe(['done', 'done', 'skipped'])
        ->and($db->meta_of(10))->toBe([['condition_physicians', serialize(['20'])], ['condition_locations', serialize(['20'])], ['treatment_procedure_physicians', serialize(['21'])]])
        ->and(array_column(array_filter($db->calls, static fn (array $call): bool => $call[0] === 'update_field'), 1))->toBe(['condition_locations']);
});

test('a resumed single field is cleared, and one recorded with no after is done once empty', function (): void {
    $db = dead_links_db();
    $db->post(12, 'clinical-resource');
    $db->add_meta(12, 'clinical_resource_spotlight_provider', '98');
    $db->post(13, 'clinical-resource');

    $result = uamswp_dead_links_apply_step(dead_links_make_run([
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'pending', ['before' => [98], 'remove' => [98], 'after' => [], 'saved' => true]),
        dead_links_link(13, 'clinical_resource_spotlight_provider', 'single', [98], 'pending', ['before' => [98]]),
    ], 'applying'));

    expect(array_column($result['items'], 'state'))->toBe(['done', 'done'])
        ->and($db->meta_of(12))->toBe([['clinical_resource_spotlight_provider', '']]);
});

test('a resumed row that is gone is done when it was saved and backed up, and skipped otherwise', function (): void {
    $db = dead_links_db();
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize(['uamswp_locations|91' => ['post_id' => '91']]);

    $result = uamswp_dead_links_apply_step(dead_links_make_run([
        dead_links_row('uamswp_locations', 91, 'pending', ['saved' => true]),
        dead_links_row('uamswp_locations', 92, 'pending', ['saved' => true]),
        dead_links_row('uamswp_locations', 91),
    ], 'applying'));

    expect(array_column($result['items'], 'state'))->toBe(['done', 'skipped', 'skipped']);
});

test('a row whose delete fails is marked failed', function (): void {
    $db = dead_links_db();
    $db->tables['uamswp_locations'] = [['post_id' => '91']];
    $db->refuse['uamswp_locations'] = true;

    $result = uamswp_dead_links_apply_step(dead_links_make_run([dead_links_row('uamswp_locations', 91)]));

    expect($result['items'][0]['state'])->toBe('failed')
        ->and($result['counts']['failed'])->toBe(1)
        ->and($result['status'])->toBe('applied');
});

test('a removal whose backup cannot be saved writes nothing and fails', function (string $lost): void {
    [$db, $run] = dead_links_apply_world();
    $db->lose[$lost] = true;

    $result = uamswp_dead_links_apply_step($run);

    expect($result['status'])->toBe('failed')
        ->and($result['cursor'])->toBe(0)
        ->and($result['items'][0]['state'])->toBe('pending')
        ->and($result['items'][0]['saved'])->toBeTrue()
        ->and($db->writes)->toBe([])
        ->and(array_column($db->calls, 0))->not->toContain('update_field');
})->with([
    'the row backup' => ['uamswp_fad_dead_links_rows'],
    'the run' => ['uamswp_fad_dead_links_run'],
]);

test('a removal that cannot save the run after writing fails', function (): void {
    [$db, $run] = dead_links_apply_world();
    $db->keep_writes[UAMSWP_DEAD_LINKS_RUN_OPTION] = 1;

    $result = uamswp_dead_links_apply_step($run);

    expect($result['status'])->toBe('failed')
        ->and($result['cursor'])->toBe(8)
        ->and($result['items'][0]['state'])->toBe('done');
});

/**
 * A database and a finished removal with an item for every way an undo can treat one.
 *
 * @return array{0: DeadLinksDb, 1: array<string, mixed>}
 */
function dead_links_undo_world(): array
{
    $db = dead_links_db();

    foreach ([10, 12, 13, 14, 15, 16, 93, 20] as $post) {
        $db->post($post, 'condition');
    }

    $removed = ['before' => [20, 99], 'remove' => [99], 'after' => [20]];

    // What the removal wrote, or what has happened since.
    $db->add_meta(10, 'condition_physicians', serialize(['20']));
    $db->add_meta(12, 'clinical_resource_spotlight_provider', '');
    $db->add_meta(13, 'condition_physicians', serialize(['20']));
    $db->add_meta(14, 'condition_physicians', serialize(['21']));
    $db->add_meta(15, 'condition_physicians', serialize(['20', '99']));
    $db->add_meta(16, 'condition_physicians', serialize(['20']));
    $db->tables['uamswp_locations'] = [['post_id' => '92', 'physician_locations' => 'new']];

    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize([
        'link|10|condition_physicians' => ['meta' => [['_condition_physicians', 'field_cp'], ['condition_physicians', serialize([0 => '20', 2 => '99'])]], 'cells' => []],
        'uamswp_physicians|96' => ['post_id' => '96', 'physician_locations' => serialize(['20'])],
        'uamswp_locations|93' => ['post_id' => '93'],
        'uamswp_locations|92' => ['post_id' => '92', 'physician_locations' => 'old'],
    ]);

    return [$db, dead_links_make_run([
        dead_links_link(10, 'condition_physicians', 'list', [99], 'done', $removed + ['saved' => true]),
        // Removed by a run that predates the saved storage.
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'done', ['before' => [98], 'remove' => [98], 'after' => []]),
        dead_links_link(13, 'condition_physicians', 'list', [99], 'done', $removed),
        // Edited since.
        dead_links_link(14, 'condition_physicians', 'list', [99], 'done', $removed),
        // Put back by an earlier undo step.
        dead_links_link(15, 'condition_physicians', 'list', [99], 'done', $removed),
        // The record is gone.
        dead_links_link(11, 'condition_physicians', 'list', [99], 'done', $removed),
        // The removal changed nothing.
        dead_links_link(16, 'condition_physicians', 'list', [99], 'done', ['before' => [20], 'remove' => [], 'after' => [20]]),
        dead_links_row('uamswp_physicians', 96, 'done', ['saved' => true]),
        // No backup.
        dead_links_row('uamswp_locations', 94, 'done', ['saved' => true]),
        // The post is back.
        dead_links_row('uamswp_locations', 93, 'done', ['saved' => true]),
        // A row has taken its place.
        dead_links_row('uamswp_locations', 92, 'done', ['saved' => true]),
        dead_links_row('uamswp_locations', 91, 'skipped'),
    ], 'applied', 12)];
}

test('an undo puts back each field that still holds what the removal wrote, and each row that is still gone', function (): void {
    [$db, $run] = dead_links_undo_world();

    $result = uamswp_dead_links_undo_step($run);

    expect(array_column($result['items'], 'state'))->toBe(['restored', 'restored', 'restored', 'kept', 'restored', 'kept', 'restored', 'restored', 'kept', 'kept', 'kept', 'skipped'])
        ->and($result['status'])->toBe('undone')
        ->and($result['cursor'])->toBe(12)
        ->and($result['counts'])->toBe(uamswp_dead_links_counts(['items' => $result['items']]))
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION]))->toBe($result)
        // The saved storage comes back exactly, keys and reference row included.
        ->and($db->meta_of(10))->toBe([['_condition_physicians', 'field_cp'], ['condition_physicians', serialize([0 => '20', 2 => '99'])]])
        ->and($db->meta_of(12))->toBe([['clinical_resource_spotlight_provider', '98']])
        ->and($db->meta_of(13))->toBe([['condition_physicians', serialize(['20', '99'])]])
        ->and($db->meta_of(14))->toBe([['condition_physicians', serialize(['21'])]])
        ->and($db->meta_of(16))->toBe([['condition_physicians', serialize(['20'])]])
        ->and($db->tables['uamswp_physicians'])->toBe([['post_id' => '96', 'physician_locations' => serialize(['20'])]])
        ->and($db->tables['uamswp_locations'])->toBe([['post_id' => '92', 'physician_locations' => 'new']])
        ->and(array_values(array_filter($db->calls, static fn (array $call): bool => $call[0] === 'update_field')))->toBe([
            ['update_field', 'clinical_resource_spotlight_provider', '98', 12],
            ['update_field', 'condition_physicians', ['20', '99'], 13],
        ])
        ->and(array_column($db->calls, 0))->toContain('wp_cache_delete')
        ->and(array_filter($db->writes, static fn (array $write): bool => $write[0] === 'insert' && $write[1] !== 'wp_postmeta'))->toBe([
            array_search(['insert', 'wp_uamswp_physicians', ['post_id' => '96', 'physician_locations' => serialize(['20'])]], $db->writes, true) => ['insert', 'wp_uamswp_physicians', ['post_id' => '96', 'physician_locations' => serialize(['20'])]],
        ])
        ->and(array_values(array_filter($db->queries, static fn (string $sql): bool => str_starts_with($sql, 'SELECT COUNT'))))->toBe([
            'SELECT COUNT(*) FROM wp_uamswp_physicians WHERE post_id = 96',
            'SELECT COUNT(*) FROM wp_uamswp_locations WHERE post_id = 94',
            'SELECT COUNT(*) FROM wp_uamswp_locations WHERE post_id = 93',
            'SELECT COUNT(*) FROM wp_uamswp_locations WHERE post_id = 92',
        ]);
});

test('a field that already holds what it held before the removal is restored without a write, a missing part read as empty', function (): void {
    $db = dead_links_db();
    $db->post(12, 'clinical-resource');

    $result = uamswp_dead_links_undo_step(dead_links_make_run([
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'done', ['before' => [], 'remove' => [98], 'after' => [7]]),
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'done', ['remove' => [98], 'after' => []]),
    ], 'applied'));

    expect(array_column($result['items'], 'state'))->toBe(['restored', 'restored'])
        ->and(array_values(array_filter($db->calls, static fn (array $call): bool => $call[0] === 'update_field')))->toBe([]);
});

test('a single field with saved state but no saved storage is put back through ACF', function (): void {
    $db = dead_links_db();
    $db->post(12, 'clinical-resource');
    $db->add_meta(12, 'clinical_resource_spotlight_provider', '');

    $result = uamswp_dead_links_undo_step(dead_links_make_run([
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98], 'done', ['before' => [98], 'remove' => [98], 'after' => [], 'saved' => true]),
    ], 'applied'));

    expect($result['items'][0]['state'])->toBe('restored')
        ->and($db->meta_of(12))->toBe([['clinical_resource_spotlight_provider', '98']]);
});

test('an undo continues from the cursor unless the removal had just finished', function (): void {
    dead_links_db();
    $items = [dead_links_row('uamswp_locations', 91, 'done'), dead_links_row('uamswp_locations', 92, 'done'), dead_links_row('uamswp_locations', 93, 'done')];

    $continued = uamswp_dead_links_undo_step(dead_links_make_run($items, 'undoing', 1), 1);
    $started = uamswp_dead_links_undo_step(dead_links_make_run($items, 'applied', 2), 2);

    expect(array_column($continued['items'], 'state'))->toBe(['done', 'kept', 'done'])
        ->and($continued['cursor'])->toBe(2)
        ->and($continued['status'])->toBe('undoing')
        ->and(array_column($started['items'], 'state'))->toBe(['kept', 'kept', 'done'])
        ->and($started['cursor'])->toBe(2)
        ->and($started['status'])->toBe('undoing');
});

test('an undo handles 25 items a step by default', function (): void {
    dead_links_db();
    $items = array_map(static fn (int $post): array => dead_links_row('uamswp_locations', $post, 'done'), range(1, 26));

    $result = uamswp_dead_links_undo_step(dead_links_make_run($items, 'applied'));

    expect($result['cursor'])->toBe(25)
        ->and($result['status'])->toBe('undoing')
        ->and($result['items'][24]['state'])->toBe('kept')
        ->and($result['items'][25]['state'])->toBe('done');
});

test('a row whose insert fails is marked failed', function (): void {
    $db = dead_links_db();
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize(['uamswp_locations|91' => ['post_id' => '91']]);
    $db->refuse['uamswp_locations'] = true;

    $result = uamswp_dead_links_undo_step(dead_links_make_run([dead_links_row('uamswp_locations', 91, 'done')], 'applied'));

    expect($result['items'][0]['state'])->toBe('failed')
        ->and($result['status'])->toBe('undone');
});

test('an undo that cannot save the run fails', function (): void {
    $db = dead_links_db();
    $db->lose[UAMSWP_DEAD_LINKS_RUN_OPTION] = true;

    $result = uamswp_dead_links_undo_step(dead_links_make_run([dead_links_row('uamswp_locations', 91, 'done')], 'applied'));

    expect($result['status'])->toBe('failed')
        ->and($result['cursor'])->toBe(1);
});

/**
 * The admin calls a handler or the screen makes, over the in-memory database.
 *
 * @return array{0: DeadLinksDb, 1: ArrayObject<int, string>}
 */
function dead_links_admin(): array
{
    $redirects = dead_links_world();
    $db = dead_links_db();

    when('check_admin_referer')->alias(static function (string $action) use ($db): int {
        $db->calls[] = ['check_admin_referer', $action];

        return 1;
    });
    when('esc_html')->alias(static fn (string $text): string => htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
    when('esc_html__')->alias(static fn (string $text, string $domain): string => htmlspecialchars($text.($domain === 'uamswp-find-a-doc' ? '' : ' [wrong domain]'), ENT_QUOTES, 'UTF-8'));
    when('esc_url')->alias(static fn (string $url): string => htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));
    when('wp_die')->alias(static function (string $message, string $title = '', array $args = []): never {
        throw new RuntimeException('wp_die: '.$message.' | '.$title.' | '.json_encode($args));
    });

    return [$db, $redirects];
}

test('the screen is added under Find-a-Doc Settings for administrators', function (): void {
    dead_links_load();
    $added = [];
    when('add_submenu_page')->alias(static function (mixed ...$arguments) use (&$added): string {
        $added[] = $arguments;

        return 'hook';
    });

    uamswp_dead_links_menu();

    expect($added)->toBe([['fad-settings', 'Dead Link Cleanup', 'Dead Link Cleanup', 'manage_options', 'uamswp-dead-links', 'uamswp_dead_links_screen', 95]]);
});

test('a step URL carries its action and nonce, and the dry run it was started from', function (): void {
    dead_links_admin();

    expect(uamswp_dead_links_step_url('uamswp_dead_links_scan'))->toBe('https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_scan&_wpnonce=uamswp_dead_links_scan')
        ->and(uamswp_dead_links_step_url('uamswp_dead_links_apply', dead_links_make_run([])))->toBe('https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_apply&run=1700000000&_wpnonce=uamswp_dead_links_apply');
});

test('a request from a user who cannot manage options is refused with a 403', function (): void {
    [$db] = dead_links_admin();
    when('current_user_can')->alias(static fn (string $capability): bool => $capability !== 'manage_options');

    expect(fn () => uamswp_dead_links_guard('uamswp_dead_links_scan'))->toThrow(RuntimeException::class, 'wp_die: You do not have permission to do this. |  | {"response":403}')
        ->and($db->calls)->toBe([]);
});

test("an administrator's request has its nonce checked for the action", function (): void {
    [$db] = dead_links_admin();

    uamswp_dead_links_guard('uamswp_dead_links_apply');

    expect($db->calls)->toBe([['check_admin_referer', 'uamswp_dead_links_apply']]);
});

test('a dry run replaces the run and goes back to the screen', function (): void {
    [$db, $redirects] = dead_links_admin();
    $db->post(91, 'location');
    $db->tables['uamswp_locations'] = [['post_id' => '90']];

    expect(fn () => uamswp_dead_links_handle_scan())->toThrow(ExitCalled::class)
        ->and($db->calls[0])->toBe(['check_admin_referer', 'uamswp_dead_links_scan'])
        ->and(uamswp_dead_links_run()['items'])->toBe([dead_links_row('uamswp_locations', 90)])
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links']);
});

test('a dry run that is not kept reports that it failed', function (bool $earlier): void {
    [$db, $redirects] = dead_links_admin();

    if ($earlier) {
        $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([], 'undone'));
    }

    $db->lose[UAMSWP_DEAD_LINKS_RUN_OPTION] = true;

    expect(fn () => uamswp_dead_links_handle_scan())->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=failed']);
})->with(['no earlier run' => [false], 'an earlier run left in place' => [true]]);

test('a dry run once a removal has started only checks what is left, and keeps the run', function (string $status): void {
    [$db, $redirects] = dead_links_admin();
    $stored = serialize(dead_links_make_run([dead_links_row('uamswp_locations', 89, 'done')], $status));
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = $stored;
    $db->tables['uamswp_locations'] = [['post_id' => '90'], ['post_id' => '91']];
    $db->tables['uamswp_physicians'] = [['post_id' => '92']];
    $transients = [];
    when('set_transient')->alias(static function (mixed ...$arguments) use (&$transients): bool {
        $transients[] = $arguments;

        return true;
    });
    $before = time();

    expect(fn () => uamswp_dead_links_handle_scan())->toThrow(ExitCalled::class)
        ->and($db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION])->toBe($stored)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=checked'])
        ->and($transients)->toHaveCount(1)
        ->and($transients[0][0])->toBe('uamswp_fad_dead_links_check')
        ->and($transients[0][1]['at'])->toBeGreaterThanOrEqual($before)->toBeLessThanOrEqual(time())
        ->and(array_slice($transients[0][1], 1))->toBe(['links' => 0, 'rows' => 3])
        ->and($transients[0][2])->toBe(86400);
})->with(['applying', 'applied', 'undoing']);

test('a dry run after a finished or undone removal starts a new list', function (string $status): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([], $status));
    $db->tables['uamswp_locations'] = [['post_id' => '90']];

    expect(fn () => uamswp_dead_links_handle_scan())->toThrow(ExitCalled::class)
        ->and(uamswp_dead_links_run()['items'])->toBe([dead_links_row('uamswp_locations', 90)])
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links']);
})->with(['scanned', 'undone', 'failed']);

test('a step with no run, or a run in another state, has nothing to do', function (?string $status): void {
    [$db, $redirects] = dead_links_admin();

    if ($status !== null) {
        $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([], $status));
    }

    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned', 'applying'], static fn (array $run): array => throw new LogicException('ran'), 'applied'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=nothing'])
        ->and($db->calls[0])->toBe(['check_admin_referer', 'uamswp_dead_links_apply']);
})->with(['no run' => [null], 'applied' => ['applied']]);

test('a step from a page showing another dry run is refused as out of date', function (?string $shown): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([]));

    if ($shown !== null) {
        $_GET['run'] = $shown;
    }

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned'], static fn (array $run): array => throw new LogicException('ran'), 'applied'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=stale']);
})->with(['no run named' => [null], 'an older run' => ['1699999999']]);

test('a step while another holds the lock is refused as busy', function (): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([]));
    $db->option_values['uamswp_fad_dead_links_lock'] = (string) time();
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned'], static fn (array $run): array => throw new LogicException('ran'), 'applied'))->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=busy']);
});

test('a step releases the lock when it finishes, and when it throws', function (bool $throws): void {
    [$db] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([]));
    $_GET['run'] = '1700000000';
    $step = static function (array $run) use ($db, $throws): array {
        $db->calls[] = ['step', isset($db->option_values['uamswp_fad_dead_links_lock'])];

        return $throws ? throw new LogicException('step failed') : ['status' => 'applied'] + $run;
    };

    expect(fn () => uamswp_dead_links_run_step('uamswp_dead_links_apply', ['scanned'], $step, 'applied'))->toThrow($throws ? LogicException::class : ExitCalled::class)
        ->and(array_values(array_filter($db->calls, static fn (array $call): bool => in_array($call[0], ['step', 'delete_option'], true))))->toBe([['step', true], ['delete_option', 'uamswp_fad_dead_links_lock']])
        ->and($db->option_values)->not->toHaveKey('uamswp_fad_dead_links_lock');
})->with(['finishes' => [false], 'throws' => [true]]);

test('removing goes from a dry run or an unfinished removal through the removal step', function (string $status): void {
    [$db, $redirects] = dead_links_admin();
    $db->tables['uamswp_locations'] = [['post_id' => '90']];
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([dead_links_row('uamswp_locations', 90)], $status));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_handle_apply())->toThrow(ExitCalled::class)
        ->and($db->calls[0])->toBe(['check_admin_referer', 'uamswp_dead_links_apply'])
        ->and($db->tables['uamswp_locations'])->toBe([])
        ->and(uamswp_dead_links_run()['status'])->toBe('applied')
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=applied']);
})->with(['scanned', 'applying']);

test('removing does nothing once the removal has finished', function (): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([], 'applied'));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_handle_apply())->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=nothing']);
});

test('undoing goes from a finished or unfinished removal through the undo step', function (string $status): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize(['uamswp_locations|90' => ['post_id' => '90']]);
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([dead_links_row('uamswp_locations', 90, 'done')], $status));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_handle_undo())->toThrow(ExitCalled::class)
        ->and($db->calls[0])->toBe(['check_admin_referer', 'uamswp_dead_links_undo'])
        ->and($db->tables['uamswp_locations'])->toBe([['post_id' => '90']])
        ->and(uamswp_dead_links_run()['status'])->toBe('undone')
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=undone']);
})->with(['applied', 'undoing']);

test('undoing does nothing before a removal', function (): void {
    [$db, $redirects] = dead_links_admin();
    $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize(dead_links_make_run([], 'scanned'));
    $_GET['run'] = '1700000000';

    expect(fn () => uamswp_dead_links_handle_undo())->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links&notice=nothing']);
});

test('a post is labeled with its ID and title, linked to its edit screen when it has one', function (string $title, ?string $edit, string $label): void {
    dead_links_admin();
    when('get_the_title')->alias(static fn (int $post): string => $post === 10 ? $title : 'wrong post');
    when('get_edit_post_link')->alias(static fn (int $post): ?string => $post === 10 ? $edit : 'wrong post');

    expect(uamswp_dead_links_post_label(10))->toBe($label);
})->with([
    'a title and a link' => ['Asthma & you', 'https://example.test/wp-admin/post.php?post=10&action=edit', '<a href="https://example.test/wp-admin/post.php?post=10&amp;action=edit">#10 Asthma &amp; you</a>'],
    'no title' => ['', 'https://example.test/edit', '<a href="https://example.test/edit">#10</a>'],
    'no edit link' => ['Asthma', null, '#10 Asthma'],
    'an empty edit link' => ['Asthma', '', '#10 Asthma'],
]);

/**
 * Render the screen with a stored run (or none), a notice in the query and a stored check (or none).
 *
 * @param  array<string, mixed>|null  $run
 * @param  array<string, int>|null  $check
 */
function dead_links_screen_html(?array $run, ?string $notice = null, ?array $check = null): string
{
    [$db] = dead_links_admin();

    if ($run !== null) {
        $db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION] = serialize($run);
    }

    if ($notice !== null) {
        $_GET['notice'] = $notice;
    }

    when('wp_unslash')->alias(static fn (string $value): string => stripslashes($value));
    when('sanitize_key')->alias(static fn (string $key): string => (string) preg_replace('/[^a-z0-9_\-]/', '', strtolower($key)));
    when('wp_date')->alias(static fn (string $format, int $time): string => $format.'@'.$time);
    when('get_transient')->alias(static fn (string $name): mixed => $name === 'uamswp_fad_dead_links_check' ? ($check ?? false) : 'wrong transient');
    when('get_the_title')->alias(static fn (int $post): string => 'Post '.$post);
    when('get_edit_post_link')->alias(static fn (int $post): string => 'https://example.test/edit/'.$post);

    ob_start();

    try {
        uamswp_dead_links_screen();
    } finally {
        $html = (string) ob_get_clean();
    }

    return $html;
}

/**
 * Three items, one in each state the screen shows in its list.
 *
 * @return array<int, array<string, mixed>>
 */
function dead_links_screen_items(): array
{
    return [
        dead_links_link(10, 'condition_physicians', 'list', [99, 98], 'done'),
        dead_links_row('uamswp_locations', 94, 'skipped'),
        dead_links_row('uamswp_physicians', 93, 'failed'),
    ];
}

test('the screen refuses a user who cannot manage options', function (): void {
    dead_links_admin();
    when('current_user_can')->alias(static fn (string $capability): bool => $capability !== 'manage_options');

    ob_start();

    try {
        expect(fn () => uamswp_dead_links_screen())->toThrow(RuntimeException::class, 'wp_die: You do not have permission to do this. |  | {"response":403}');
    } finally {
        $printed = (string) ob_get_clean();
    }

    expect($printed)->toBe('');
});

test('before the first dry run the screen offers only the dry run', function (): void {
    expect(dead_links_screen_html(null))->toBe('<div class="wrap"><h1>Dead Link Cleanup</h1><p>Providers and locations deleted before the delete cleanup existed left their IDs in the relationship fields of the records linked to them, and their rows in the custom tables. This screen finds them, removes them, and can put them back.</p><p><strong>Take a database backup first.</strong> The undo covers what this screen removed; it is not a database backup.</p><p><a class="button" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_scan&amp;_wpnonce=uamswp_dead_links_scan">Run the dry run</a> </p></div>');
});

test('after a dry run the screen shows the notice, the totals, every item and the removal button', function (): void {
    expect(dead_links_screen_html(dead_links_make_run(dead_links_screen_items()), 'Applied'))->toBe('<div class="wrap"><h1>Dead Link Cleanup</h1><div class="notice notice-success"><p>The dead links and orphan rows in the list were removed. Use Check what is left to confirm none remain.</p></div><p>Providers and locations deleted before the delete cleanup existed left their IDs in the relationship fields of the records linked to them, and their rows in the custom tables. This screen finds them, removes them, and can put them back.</p><p><strong>Take a database backup first.</strong> The undo covers what this screen removed; it is not a database backup.</p><p><a class="button" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_scan&amp;_wpnonce=uamswp_dead_links_scan">Run the dry run again</a> <a class="button button-primary" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_apply&amp;run=1700000000&amp;_wpnonce=uamswp_dead_links_apply">Remove everything in this list</a> </p><h2>Last run</h2><p>Dry run done; nothing has been changed. Dry run of F j, Y g:i a@1700000000.</p><ul><li>2 dead links on 1 records (2 deleted IDs)</li><li>1 orphan rows in <code>uamswp_locations</code></li><li>1 orphan rows in <code>uamswp_physicians</code></li><li>1 removed, 1 skipped because they were already fixed or their post had come back, 1 failed</li></ul><h2>Every dead link and orphan row</h2><table class="widefat striped"><thead><tr><th>Record</th><th>Field or table</th><th>Missing IDs</th><th>State</th></tr></thead><tbody><tr><td><a href="https://example.test/edit/10">#10 Post 10</a></td><td><code>condition_physicians</code></td><td>99, 98</td><td>done</td></tr><tr><td>#94 (deleted)</td><td>row in <code>uamswp_locations</code></td><td>#94</td><td>skipped</td></tr><tr><td>#93 (deleted)</td><td>row in <code>uamswp_physicians</code></td><td>#93</td><td>failed</td></tr></tbody></table></div>');
});

test('the screen names each notice, as an error or a success, and ignores one it does not know', function (string $notice, ?string $shown): void {
    $html = dead_links_screen_html(null, $notice);

    if ($shown === null) {
        expect($html)->not->toContain('class="notice');
    } else {
        expect($html)->toContain('<h1>Dead Link Cleanup</h1>'.$shown.'<p>Providers');
    }
})->with([
    'applied' => ['applied', '<div class="notice notice-success"><p>The dead links and orphan rows in the list were removed. Use Check what is left to confirm none remain.</p></div>'],
    'undone' => ['undone', '<div class="notice notice-success"><p>The removal was undone.</p></div>'],
    'checked' => ['checked', '<div class="notice notice-success"><p>Checked. The result is under Last run; the removal and its undo are unchanged.</p></div>'],
    'nothing' => ['nothing', '<div class="notice notice-success"><p>There was nothing to do for that step.</p></div>'],
    'stale' => ['stale', '<div class="notice notice-error"><p>That page was out of date: the list changed after it was shown. Review the list below and start again from it.</p></div>'],
    'busy' => ['busy', '<div class="notice notice-error"><p>Another step is running. Wait for it to finish.</p></div>'],
    'noacf' => ['noacf', '<div class="notice notice-error"><p>Advanced Custom Fields is not available, so nothing can be removed or restored.</p></div>'],
    'failed' => ['failed', '<div class="notice notice-error"><p>The backup could not be saved, so nothing was changed by that step. Check the database and try again.</p></div>'],
    'a backslash in the query' => ['bu\\sy', '<div class="notice notice-error"><p>Another step is running. Wait for it to finish.</p></div>'],
    'unknown' => ['other', null],
    'none' => ['', null],
]);

test('the screen offers the step that comes next for the state of the run', function (string $status, string $buttons, string $state): void {
    $html = dead_links_screen_html(dead_links_make_run(dead_links_screen_items(), $status));

    expect($html)->toContain('<p><a class="button" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_scan&amp;_wpnonce=uamswp_dead_links_scan">'.$buttons.'</p><h2>Last run</h2><p>'.$state.' Dry run of');
})->with([
    'a removal in progress' => ['applying', 'Check what is left</a> <a class="button button-primary" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_apply&amp;run=1700000000&amp;_wpnonce=uamswp_dead_links_apply">Continue the removal</a> ', 'Removal in progress.'],
    'a finished removal' => ['applied', 'Check what is left</a> <a class="button" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_undo&amp;run=1700000000&amp;_wpnonce=uamswp_dead_links_undo">Undo the removal</a>', 'Removed.'],
    'an undo in progress' => ['undoing', 'Check what is left</a> <a class="button" href="https://example.test/wp-admin/admin-post.php?action=uamswp_dead_links_undo&amp;run=1700000000&amp;_wpnonce=uamswp_dead_links_undo">Continue the undo</a>', 'Undo in progress.'],
    'an undone removal' => ['undone', 'Run the dry run again</a> ', 'Removal undone.'],
    'a status it does not name' => ['failed', 'Run the dry run again</a> ', 'failed'],
]);

test('a dry run with nothing in it offers no removal, and one with only orphan rows does', function (): void {
    expect(dead_links_screen_html(dead_links_make_run([])))->toContain('Run the dry run again</a> </p><h2>Last run</h2>')
        ->toContain('<ul><li>0 dead links on 0 records (0 deleted IDs)</li><li>0 removed, 0 skipped because they were already fixed or their post had come back, 0 failed</li></ul>')
        ->toContain('<tbody></tbody>')
        ->and(dead_links_screen_html(dead_links_make_run([dead_links_row('uamswp_locations', 94)])))->toContain('>Remove everything in this list</a>');
});

test('the screen shows the last check made after a removal started', function (): void {
    expect(dead_links_screen_html(dead_links_make_run(dead_links_screen_items(), 'applied'), null, ['at' => 1700000100, 'links' => 2, 'rows' => 1]))
        ->toContain('failed</li></ul><p><strong>Check made F j, Y g:i a@1700000100:</strong> 2 dead links and 1 orphan rows are left.</p><h2>Every dead link and orphan row</h2>');
});

test('an empty delete cleanup map gives three empty lists of sources', function (): void {
    dead_links_load();
    when('uamswp_delete_cleanup_map')->justReturn([]);

    expect(uamswp_dead_links_sources())->toBe(['meta' => [], 'tables' => [], 'own' => []]);
});

test('a dry run whose reads fail lists nothing instead of failing', function (): void {
    $db = dead_links_scan_world();
    $db->fail_results = true;

    expect(uamswp_dead_links_scan(false)['items'])->toBe([])
        ->and(uamswp_dead_links_capture('physician_locations', 30))->toBe(['meta' => [], 'cells' => ['uamswp_locations' => serialize(['99', '20']), 'uamswp_physicians' => serialize(['95', '99'])]]);
});

test('a dry run with the first custom table missing still reads the second', function (): void {
    $db = dead_links_scan_world();
    unset($db->tables['uamswp_locations']);

    expect(uamswp_dead_links_scan(false)['items'])->toBe([
        dead_links_link(12, 'clinical_resource_spotlight_provider', 'single', [98]),
        dead_links_link(10, 'condition_physicians', 'list', [99]),
        dead_links_link(11, 'physician_expertise', 'list', [97]),
        dead_links_link(20, 'physician_locations', 'list', [96]),
        dead_links_link(30, 'physician_locations', 'list', [95, 99]),
        dead_links_row('uamswp_physicians', 96),
    ]);
});

test('a field found twice lists each dead ID once, and later reads are still listed', function (): void {
    $db = dead_links_db();
    $db->post(30, 'location');
    $db->post(31, 'location');
    $db->tables['uamswp_locations'] = [['post_id' => '30', 'physician_locations' => serialize(['99'])]];
    $db->tables['uamswp_physicians'] = [
        ['post_id' => '30', 'physician_locations' => serialize(['99', '95'])],
        ['post_id' => '31', 'physician_locations' => serialize(['94'])],
    ];

    expect(uamswp_dead_links_scan(false)['items'])->toBe([
        dead_links_link(30, 'physician_locations', 'list', [99, 95]),
        dead_links_link(31, 'physician_locations', 'list', [94]),
    ]);
});

test('the storage of a field reads the second custom table when the first is missing', function (): void {
    $db = dead_links_db();
    unset($db->tables['uamswp_locations']);
    $db->tables['uamswp_physicians'] = [['post_id' => '30', 'physician_locations' => 'x']];

    expect(uamswp_dead_links_capture('physician_locations', 30))->toBe(['meta' => [], 'cells' => ['uamswp_physicians' => 'x']]);
});

test('a removal removes only the dead IDs it listed, keeps the order of the rest, and passes over handled items', function (): void {
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->post(20, 'provider');
    // 88 also names no post, but the dry run did not list it.
    $db->add_meta(10, 'condition_physicians', serialize(['99', '20', '88']));

    $result = uamswp_dead_links_apply_step(dead_links_make_run([
        dead_links_row('uamswp_locations', 91, 'done'),
        dead_links_link(10, 'condition_physicians', 'list', [99]),
    ]));

    expect($result['items'][1])->toBe(dead_links_link(10, 'condition_physicians', 'list', [99], 'done', ['before' => [99, 20, 88], 'remove' => [99], 'after' => [20, 88], 'saved' => true]))
        ->and($result['items'][0]['state'])->toBe('done')
        ->and($db->meta_of(10))->toBe([['condition_physicians', serialize(['20', '88'])]]);
});

test('the run saved before a removal writes is marked as applying', function (): void {
    [$db, $run] = dead_links_apply_world();
    $db->keep_writes[UAMSWP_DEAD_LINKS_RUN_OPTION] = 1;

    uamswp_dead_links_apply_step($run);
    $saved = unserialize($db->option_values[UAMSWP_DEAD_LINKS_RUN_OPTION]);

    expect($saved['status'])->toBe('applying')
        ->and($saved['cursor'])->toBe(0)
        ->and($saved['items'][0]['saved'])->toBeTrue()
        ->and($saved['items'][0]['state'])->toBe('pending');
});

test('an undo passes over items it did not remove and still handles the ones after them', function (): void {
    $db = dead_links_db();
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = serialize(['uamswp_locations|91' => ['post_id' => '91']]);

    $result = uamswp_dead_links_undo_step(dead_links_make_run([
        dead_links_row('uamswp_locations', 90, 'skipped'),
        dead_links_row('uamswp_locations', 91, 'done'),
    ], 'applied'));

    expect(array_column($result['items'], 'state'))->toBe(['skipped', 'restored'])
        ->and($db->tables['uamswp_locations'])->toBe([['post_id' => '91']]);
});

test('a field of two post types, and two fields of one table, are each their own source', function (): void {
    dead_links_load();
    when('uamswp_delete_cleanup_map')->justReturn([
        'one' => [
            'meta' => ['condition' => [['shared_field', 'list']], 'treatment' => [['shared_field', 'list']]],
            'tables' => [['uamswp_locations', 'first'], ['uamswp_locations', 'second']],
            'own_table' => 'uamswp_locations',
        ],
    ]);

    expect(uamswp_dead_links_sources())->toBe([
        'meta' => [['condition', 'shared_field', 'list'], ['treatment', 'shared_field', 'list']],
        'tables' => [['uamswp_locations', 'first'], ['uamswp_locations', 'second']],
        'own' => ['uamswp_locations'],
    ]);
});

test('two fields of one record with dead IDs are two items', function (): void {
    $db = dead_links_db();
    $db->post(10, 'condition');
    $db->add_meta(10, 'condition_physicians', serialize(['99']));
    $db->add_meta(10, 'condition_locations', serialize(['98']));

    expect(uamswp_dead_links_scan(false)['items'])->toBe([
        dead_links_link(10, 'condition_physicians', 'list', [99]),
        dead_links_link(10, 'condition_locations', 'list', [98]),
    ]);
});

test('a removal over a row backup that is not a list keeps it as one and still saves the new rows', function (): void {
    $db = dead_links_db();
    $db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION] = 'not a list';
    $db->tables['uamswp_locations'] = [['post_id' => '91']];

    $result = uamswp_dead_links_apply_step(dead_links_make_run([dead_links_row('uamswp_locations', 91)]));

    expect($result['status'])->toBe('applied')
        ->and(unserialize($db->option_values[UAMSWP_DEAD_LINKS_ROWS_OPTION]))->toBe([0 => 'not a list', 'uamswp_locations|91' => ['post_id' => '91']]);
});

test('going back to the screen with no notice named sends no notice', function (): void {
    dead_links_load();
    $redirects = dead_links_world();

    expect(fn () => uamswp_dead_links_back())->toThrow(ExitCalled::class)
        ->and($redirects->getArrayCopy())->toBe(['https://example.test/wp-admin/admin.php?page=uamswp-dead-links']);
});
