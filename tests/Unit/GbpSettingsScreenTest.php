<?php

/**
 * The Business Profile sync settings screen (includes/class.fad-gbp-settings.php): the page, the activity
 * tables, the menu entry and the hooks, and the parts of the save and queue handlers the class-based tests in
 * tests/Unit/GbpSettingsTest.php leave open.
 */

declare(strict_types=1);

use UamswpFindADoc\Tests\Support\ExitCalled;

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    $this->request = [$_GET, $_POST];
});

afterEach(function (): void {
    [$_GET, $_POST] = $this->request;

    foreach (uamswp_fad_gbp_credential_names() as $name) {
        putenv($name);
    }
});

/**
 * Stub what the settings page reads and prints with.
 *
 * @param  array<string, mixed>  $options  Stored options by name.
 */
function gbp_screen_world(array $options = [], bool $production = true, array $modes = ['uams' => 'live', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'off']): void
{
    when('current_user_can')->alias(static fn (string $capability): bool => $capability === 'manage_options');
    when('uamswp_fad_gbp_modes')->justReturn($modes);
    when('uamswp_fad_gbp_is_production')->justReturn($production);
    when('get_option')->alias(static fn (string $name, mixed $default = false): mixed => $options[$name] ?? $default);
    when('esc_html')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('esc_attr')->alias(static fn (mixed $text): string => htmlspecialchars((string) $text));
    when('esc_url')->alias(static fn (string $url): string => 'url('.$url.')');
    when('admin_url')->alias(static fn (string $path = ''): string => 'https://example.test/wp-admin/'.$path);
    when('wp_nonce_field')->alias(static function (string $action): void {
        echo '[nonce '.$action.']';
    });
    when('selected')->alias(static function (mixed $selected, mixed $current): void {
        echo (string) $selected === (string) $current ? "selected='selected'" : '';
    });
    when('submit_button')->alias(static function (mixed ...$arguments): void {
        echo '[button '.json_encode($arguments).']';
    });
    when('wp_date')->alias(static fn (string $format, int $time): string => $format.'@'.$time);
}

/**
 * Render the settings page.
 */
function gbp_screen(): string
{
    ob_start();

    try {
        uamswp_fad_gbp_render_settings_page();
    } finally {
        $html = (string) ob_get_clean();
    }

    return $html;
}

/**
 * Collapse the whitespace of printed markup and drop it between tags.
 */
function gbp_screen_squeeze(string $html): string
{
    return trim((string) preg_replace(['/\s+/', '/>\s+</'], [' ', '><'], $html));
}

/**
 * A logged entry with every field the table reads.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function gbp_screen_entry(array $overrides = []): array
{
    return $overrides + ['time' => 1700000000, 'store_code' => 'P-1', 'silo' => 'uams', 'mode' => 'live', 'action' => 'update', 'reason' => 'Updated', 'fields' => ['title', 'phoneNumbers'], 'attributes' => ['a'], 'unresolved_categories' => ['Podiatry']];
}

test('the credentials are named, never their values, and each says whether it is set', function (): void {
    gbp_screen_world();
    putenv('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET=the-secret');

    $html = gbp_screen_squeeze(gbp_screen());

    expect(uamswp_fad_gbp_credential_names())->toBe(['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'])
        ->and($html)->toContain('<tbody><tr><td><code>GOOGLE_BUSINESS_PROFILE_CLIENT_ID</code></td><td><strong>Missing</strong></td></tr><tr><td><code>GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET</code></td><td>Set</td></tr><tr><td><code>GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN</code></td><td><strong>Missing</strong></td></tr></tbody>')
        ->not->toContain('the-secret');
});

test('the page heads its sections and posts each form to its own action with its nonce', function (): void {
    gbp_screen_world();

    $html = gbp_screen_squeeze(gbp_screen());

    expect($html)->toStartWith('<div class="wrap"><h1>Business Profile Sync</h1><p>Updates Google Business Profile listings')
        ->toContain('<form method="post" action="url(https://example.test/wp-admin/admin-post.php)"><input type="hidden" name="action" value="uamswp_fad_gbp_save_modes"> [nonce uamswp_fad_gbp_modes]')
        ->toContain('[button ["Save silo modes"]]')
        ->toContain('<input type="hidden" name="action" value="uamswp_fad_gbp_queue_all"> [nonce uamswp_fad_gbp_queue_all] [button ["Queue a sync for all providers and locations","secondary"]]')
        ->toContain('<form method="get" action="url(https://example.test/wp-admin/admin.php)"><input type="hidden" name="page" value="uamswp-fad-gbp">')
        ->toContain('[button ["Filter","secondary","",false]]')
        ->toEndWith('</div>');
});

test('each silo gets a mode list with its saved mode selected, named for its silo', function (): void {
    gbp_screen_world();

    $html = gbp_screen_squeeze(gbp_screen());

    expect($html)->toContain('<tr><th scope="row"><label for="gbp-mode-uams">UAMS</label></th><td><select id="gbp-mode-uams" name="modes[uams]"><option value="off" >Off</option><option value="dry-run" >Dry run</option><option value="live" selected=\'selected\'>Live</option></select></td></tr>')
        ->toContain('<label for="gbp-mode-arkansas-childrens">Arkansas Children&#039;s</label>')
        ->toContain('<option value="dry-run" selected=\'selected\'>Dry run</option>')
        ->toContain('<label for="gbp-mode-baptist-health">Baptist Health</label>');
});

test('a silo without a label is named by its key', function (): void {
    gbp_screen_world(modes: ['new-silo' => 'off']);

    expect(gbp_screen())->toContain('<label for="gbp-mode-new-silo">new-silo</label>');
});

test('the page says whether the site is production, and that a live silo runs dry elsewhere', function (): void {
    gbp_screen_world();
    $production = gbp_screen_squeeze(gbp_screen());
    gbp_screen_world(production: false);
    $elsewhere = gbp_screen_squeeze(gbp_screen());

    expect($production)->toContain('This site is <strong>production</strong>. A dry run logs what would change and writes nothing.')
        ->and($elsewhere)->toContain('This site is <strong>not production</strong>. A silo set to Live runs as a dry run here. A dry run logs');
});

test('the page confirms a save, and a queue with how many were queued', function (): void {
    gbp_screen_world();
    $_GET = ['saved' => '1', 'queued' => '12 posts'];

    $html = gbp_screen_squeeze(gbp_screen());

    expect($html)->toContain('<h1>Business Profile Sync</h1><div class="notice notice-success is-dismissible"><p>Settings saved.</p></div><div class="notice notice-success is-dismissible"><p>Queued a sync for 12 providers and locations.</p></div>');

    $_GET = [];

    expect(gbp_screen())->not->toContain('notice');
});

test('the activity filters offer each action and silo, with the filtered ones selected', function (): void {
    gbp_screen_world();
    $_GET = ['log_action' => 'error', 'log_silo' => 'baptist-health'];

    $html = gbp_screen_squeeze(gbp_screen());

    expect($html)->toContain('<select name="log_action"><option value="">All</option><option value="review" >review</option><option value="error" selected=\'selected\'>error</option><option value="create" >create</option><option value="update" >update</option><option value="skip" >skip</option></select>')
        ->toContain('<select name="log_silo"><option value="">All</option><option value="uams" >UAMS</option><option value="arkansas-childrens" >Arkansas Children&#039;s</option><option value="baptist-health" selected=\'selected\'>Baptist Health</option></select>');
});

test("the activity table prints each entry's time, store code, silo, mode, action and detail", function (): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => [gbp_screen_entry()]]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<h3>Recent activity</h3><table class="widefat striped"><thead><tr><th>Time</th><th>Store code</th><th>Silo</th><th>Mode</th><th>Action</th><th>Detail</th></tr></thead><tbody><tr><td>Y-m-d H:i@1700000000</td><td><code>P-1</code></td><td>uams</td><td>live</td><td>update</td><td> Updated <br><small>title, phoneNumbers</small><br><small>Attributes: a</small><br><small>No Google category for: Podiatry</small></td></tr></tbody></table></div>');
});

test('an entry with nothing but an action prints empty cells and no detail lines', function (): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => [['action' => 'skip']]]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<tbody><tr><td>Y-m-d H:i@0</td><td><code></code></td><td></td><td></td><td>skip</td><td></td></tr></tbody>');
});

test('detail lists that are not lists print as one value', function (): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => [gbp_screen_entry(['fields' => 'title', 'attributes' => 'a', 'unresolved_categories' => 'Podiatry', 'time' => '1700000000'])]]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<td>Y-m-d H:i@1700000000</td>')
        ->toContain('<br><small>title</small><br><small>Attributes: a</small><br><small>No Google category for: Podiatry</small>');
});

test('the recent activity shows the newest 100 entries', function (): void {
    $log = array_map(static fn (int $i): array => gbp_screen_entry(['store_code' => 'P-'.$i]), range(1, 101));
    gbp_screen_world(['uamswp_fad_gbp_log' => $log]);

    $html = gbp_screen();

    expect(substr_count($html, '<tr>') - 3 - 3 - 1)->toBe(100)
        ->and($html)->toContain('<code>P-1</code>')
        ->toContain('<code>P-100</code>')
        ->not->toContain('<code>P-101</code>');
});

test('the needs attention list and the recent activity each say so when they are empty, or when what is stored is not a list', function (mixed $stored): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => $stored, 'uamswp_fad_gbp_attention' => $stored]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<h3>Needs attention</h3><p>Listings the sync left for a person (review) or could not write (error). An entry stays until a later run for the same store code settles, and routine activity does not push it out.</p><p>Nothing needs attention.</p><h3>Recent activity</h3><p>Nothing logged yet.</p></div>');
})->with(['nothing' => [[]], 'not a list' => ['broken']]);

test('the needs attention list prints its entries, filtered like the activity', function (): void {
    gbp_screen_world(['uamswp_fad_gbp_attention' => [gbp_screen_entry(['action' => 'review', 'store_code' => 'R-1']), gbp_screen_entry(['action' => 'error', 'store_code' => 'E-1'])]]);
    $_GET = ['log_action' => 'error'];

    $html = gbp_screen_squeeze(gbp_screen());

    expect($html)->toContain('push it out.</p><table class="widefat striped">')
        ->toContain('<code>E-1</code>')
        ->not->toContain('<code>R-1</code>')
        ->not->toContain('Nothing needs attention.');
});

test('the page prints nothing for a user who cannot manage options', function (): void {
    gbp_screen_world();
    when('current_user_can')->justReturn(false);

    expect(gbp_screen())->toBe('');
});

test('the screen is added under Find-a-Doc Settings for administrators, and its handlers on admin_post', function (): void {
    $added = [];
    when('add_submenu_page')->alias(static function (mixed ...$arguments) use (&$added): string {
        $added[] = $arguments;

        return 'hook';
    });
    $hooks = [];
    when('add_action')->alias(static function (mixed ...$arguments) use (&$hooks): bool {
        $hooks[] = $arguments;

        return true;
    });

    uamswp_fad_gbp_add_settings_page();
    uamswp_fad_gbp_register_settings_hooks();

    expect($added)->toBe([['fad-settings', 'Business Profile Sync', 'Business Profile Sync', 'manage_options', 'uamswp-fad-gbp', 'uamswp_fad_gbp_render_settings_page', 95]])
        ->and($hooks)->toBe([
            ['admin_menu', 'uamswp_fad_gbp_add_settings_page', 110],
            ['admin_post_uamswp_fad_gbp_save_modes', 'uamswp_fad_gbp_save_modes'],
            ['admin_post_uamswp_fad_gbp_queue_all', 'uamswp_fad_gbp_queue_all_action'],
        ]);
});

test('a save or a queue from a user who cannot manage options dies with a 403 naming what was refused', function (string $handler, string $message): void {
    when('current_user_can')->justReturn(false);
    when('esc_html__')->alias(static fn (string $text, string $domain): string => $text.' ['.$domain.']');
    when('wp_die')->alias(static function (mixed ...$arguments): never {
        throw new RuntimeException(json_encode($arguments));
    });

    expect(fn () => $handler())->toThrow(RuntimeException::class, json_encode([$message.' [uamswp-find-a-doc]', '', ['response' => 403]]));
})->with([
    'save' => ['uamswp_fad_gbp_save_modes', 'You do not have permission to change these settings.'],
    'queue' => ['uamswp_fad_gbp_queue_all_action', 'You do not have permission to run this sync.'],
]);

test('a save with no modes posted puts every silo in dry-run, and both handlers end the request by default', function (): void {
    $saved = [];
    when('current_user_can')->justReturn(true);
    when('check_admin_referer')->justReturn(1);
    when('update_option')->alias(static function (string $name, mixed $value) use (&$saved): bool {
        $saved[$name] = $value;

        return true;
    });
    when('wp_unslash')->returnArg();
    when('admin_url')->alias(static fn (string $path = ''): string => 'https://example.test/wp-admin/'.$path);
    when('add_query_arg')->alias(static fn (string $key, mixed $value, string $url): string => $url.'&'.$key.'='.$value);
    when('wp_safe_redirect')->justReturn(true);
    when('uamswp_fad_gbp_queue_all')->justReturn(4);

    $_POST = [];

    expect(fn () => uamswp_fad_gbp_save_modes())->toThrow(ExitCalled::class)
        ->and($saved['uamswp_fad_gbp_modes'])->toBe(['uams' => 'dry-run', 'arkansas-childrens' => 'dry-run', 'baptist-health' => 'dry-run'])
        ->and(fn () => uamswp_fad_gbp_queue_all_action())->toThrow(ExitCalled::class);
});

test('the activity filter accepts each known action', function (string $action): void {
    expect(uamswp_fad_gbp_log_filters(['log_action' => $action])['action'])->toBe($action);
})->with(['review', 'error', 'create', 'update', 'skip']);

test('an entry with no fields at all prints an empty row', function (): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => [[]]]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<tbody><tr><td>Y-m-d H:i@0</td><td><code></code></td><td></td><td></td><td></td><td></td></tr></tbody>');
});

test('a detail list that holds a list prints it as empty rather than as Array', function (): void {
    gbp_screen_world(['uamswp_fad_gbp_log' => [gbp_screen_entry(['fields' => ['title', ['nested']], 'attributes' => [['nested']], 'unresolved_categories' => [['nested'], 'Podiatry']])]]);

    expect(gbp_screen_squeeze(gbp_screen()))->toContain('<br><small>title, </small><br><small>Attributes: </small><br><small>No Google category for: , Podiatry</small>');
});
