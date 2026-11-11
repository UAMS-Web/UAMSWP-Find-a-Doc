<?php

/**
 * Pins which templates build address line 2 with the shared helpers, so the
 * inline floor expressions do not come back in one file and drift.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocationAddressCallSitesTest extends TestCase
{
    /**
     * Files whose floor handling calls a helper.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function callers(): array
    {
        return [
            'single location page' => ['templates/single-locations.php', 'uamswp_location_address_2('],
            'single provider page' => ['templates/single-physicians.php', 'uamswp_location_address_2('],
            'location card' => ['templates/loops/location-card.php', 'uamswp_location_address_2('],
            'Doximity list' => ['templates/doximity-list.php', 'uamswp_location_address_2('],
            'GMB location export' => ['includes/class.fad-gmb-settings-page.php', 'uamswp_location_address_parts('],
        ];
    }

    /**
     */
    #[DataProvider('callers')]
    public function test_file_calls_the_helper(string $file, string $call): void
    {
        $this->assertStringContainsString($call, $this->code($file));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function allFiles(): array
    {
        return [
            'single location page' => ['templates/single-locations.php', 'location'],
            'single provider page' => ['templates/single-physicians.php', 'location'],
            'location card' => ['templates/loops/location-card.php', 'location'],
            'Doximity list' => ['templates/doximity-list.php', 'primary_appointment'],
            'GMB export' => ['includes/class.fad-gmb-settings-page.php', 'location'],
        ];
    }

    /**
     * No file outside the helper tests the floor value inline. The pattern
     * allows a space after the "!", as the settings page spells it.
     *
     */
    #[DataProvider('allFiles')]
    public function test_file_has_no_inline_floor_test(string $file, string $prefix): void
    {
        $variable = '\$'.$prefix.'_floor_value';

        $this->assertSame(
            0,
            preg_match('/!\s*empty\(\s*'.$variable.'\s*\)|'.$variable.'\s*!=\s*["\']0["\']/', $this->code($file))
        );
    }

    /**
     * The file's code with whole-line comments removed.
     */
    private function code(string $file): string
    {
        $source = (string) file_get_contents(\dirname(__DIR__, 2).'/'.$file);
        $lines = preg_split('/\R/', $source) ?: [];

        return implode("\n", array_filter($lines, static function (string $line): bool {
            return 0 !== strpos(ltrim($line), '//');
        }));
    }
}
