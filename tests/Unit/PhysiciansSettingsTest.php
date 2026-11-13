<?php

/**
 * Pins the general-settings field that stores the Ajax Search Pro ID (class.fad-settings.php).
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class PhysiciansSettingsTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: array{0: object, 1: string}}> */
    private static array $actions = [];

    private static bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (self::$loaded) {
            return;
        }

        // The file builds its object at file scope, so the hook function must exist while it loads.
        when('add_action')->alias(static function (string $hook, array $callback): void {
            self::$actions[] = [$hook, $callback];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-settings.php';
        self::$loaded = true;
    }

    private function settings(): \UAMSPhysicians_Settings
    {
        $object = self::$actions[0][1][0];
        $this->assertInstanceOf(\UAMSPhysicians_Settings::class, $object);

        return $object;
    }

    public function test_the_options_are_set_up_on_admin_init_and_nothing_is_hooked_to_admin_menu(): void
    {
        $this->assertSame(
            [['admin_init', 'setup_options']],
            array_map(static fn (array $action): array => [$action[0], $action[1][1]], self::$actions)
        );
    }

    public function test_setting_up_the_options_registers_the_search_id_on_the_general_page(): void
    {
        expect('register_setting')->once()->with('general', 'ajax_search_pro_id');
        expect('add_settings_field')->once()->with(
            'ajax_search_pro_id',
            'Ajax Search Pro ID for Providers:',
            [$this->settings(), 'ajax_search_pro_id_callback'],
            'general'
        );

        $this->settings()->setup_options();
    }

    public function test_the_field_shows_the_stored_id(): void
    {
        when('get_option')->alias(static fn (string $name): string => $name === 'ajax_search_pro_id' ? '42' : '');

        $this->expectOutputString("<input name='ajax_search_pro_id' type='text' size='20' value='42' />");

        $this->settings()->ajax_search_pro_id_callback();
    }

    public function test_the_field_is_empty_when_nothing_is_stored(): void
    {
        when('get_option')->justReturn(false);

        $this->expectOutputString("<input name='ajax_search_pro_id' type='text' size='20' value='' />");

        $this->settings()->ajax_search_pro_id_callback();
    }
}
