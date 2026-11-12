<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * The CSV export menu page, its hook and the permission guards of the two exports.
 *
 * The exports end in `exit` after streaming headers, so only the guards that return first run here.
 *
 * @command  composer test:integration -- --filter GmbExportPageTest
 */
final class GmbExportPageTest extends IntegrationTestCase
{
    /** @var array<string, mixed>|null */
    private ?array $submenu_before = null;

    private mixed $title_before = null;

    public function set_up(): void
    {
        parent::set_up();

        global $submenu;
        $this->submenu_before = $submenu['fad-settings'] ?? null;
        unset($submenu['fad-settings']);
        $this->title_before = $GLOBALS['title'] ?? null;
    }

    public function tear_down(): void
    {
        global $submenu;
        unset($submenu['fad-settings']);
        if ($this->submenu_before !== null) {
            $submenu['fad-settings'] = $this->submenu_before;
        }

        $GLOBALS['title'] = $this->title_before;

        parent::tear_down();
    }

    private function administrator(): void
    {
        wp_set_current_user((int) self::factory()->user->create(['role' => 'administrator']));
    }

    public function test_the_menu_page_is_added_on_the_admin_menu_hook_at_priority_105(): void
    {
        self::assertSame(105, has_action('admin_menu', 'uamswp_add_gmb_export_page'));
    }

    public function test_the_menu_page_hangs_under_the_find_a_doc_settings_page(): void
    {
        $this->administrator();

        uamswp_add_gmb_export_page();

        global $submenu;
        self::assertSame(
            [['CSV Export', 'manage_options', 'uamswp-gmb-export', 'UAMSWP CSV Export']],
            $submenu['fad-settings'] ?? [],
        );
    }

    public function test_the_page_offers_both_exports_with_a_nonce_for_each(): void
    {
        $this->administrator();
        $GLOBALS['title'] = 'CSV Export';

        ob_start();
        uamswp_fad_gmb_export_page();
        $html = (string) ob_get_clean();

        $base = admin_url('admin.php?page=uamswp-gmb-export');
        self::assertStringContainsString('<h1>CSV Export</h1>', $html);
        self::assertStringContainsString('href="'.$base.'&action=download_doximity_csv&_wpnonce='.wp_create_nonce('download_doximity_csv').'"', $html);
        self::assertStringContainsString('href="'.$base.'&action=download_mychart_csv&_wpnonce='.wp_create_nonce('download_mychart_csv').'"', $html);
        self::assertSame(2, substr_count($html, 'Export to CSV'));
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function exports(): array
    {
        return ['Doximity' => ['doximity_csv_export'], 'MyChart' => ['mychart_csv_export']];
    }

    /**
     * @dataProvider exports
     */
    public function test_an_export_refuses_a_user_who_cannot_manage_options(string $export): void
    {
        wp_set_current_user((int) self::factory()->user->create(['role' => 'subscriber']));
        set_current_screen('dashboard');

        self::assertFalse($export());
    }

    /**
     * @dataProvider exports
     */
    public function test_an_export_refuses_a_request_outside_the_admin(string $export): void
    {
        $this->administrator();
        set_current_screen('front');

        self::assertFalse($export());
    }
}
