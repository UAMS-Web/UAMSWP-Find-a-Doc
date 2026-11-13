<?php

/**
 * Pins the (empty) script and style hooks of the physicians class (class.fad-physicians.php).
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class PhysiciansThemeTest extends UnitTestCase
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

        when('add_action')->alias(static function (string $hook, array $callback): void {
            self::$actions[] = [$hook, $callback];
        });

        require_once dirname(__DIR__, 2).'/includes/class.fad-physicians.php';
        self::$loaded = true;
    }

    public function test_the_scripts_and_the_styles_are_hooked_to_the_front_end_enqueue(): void
    {
        $this->assertSame(
            [['wp_enqueue_scripts', 'scripts'], ['wp_enqueue_scripts', 'styles']],
            array_map(static fn (array $action): array => [$action[0], $action[1][1]], self::$actions)
        );
        $this->assertInstanceOf(\UAMSPhysicians::class, self::$actions[0][1][0]);
        $this->assertSame(self::$actions[0][1][0], self::$actions[1][1][0]);
    }

    public function test_the_version_is_pinned(): void
    {
        $this->assertSame(0.1, \UAMSPhysicians::VERSION);
    }

    public function test_the_script_and_style_callbacks_register_and_enqueue_nothing(): void
    {
        foreach (['wp_register_script', 'wp_enqueue_script', 'wp_register_style', 'wp_enqueue_style'] as $function) {
            expect($function)->never();
        }

        $object = self::$actions[0][1][0];
        $object->scripts();
        $object->styles();

        $this->addToAssertionCount(1);
    }
}
