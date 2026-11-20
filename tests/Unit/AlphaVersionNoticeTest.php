<?php

/**
 * The admin notice for a missing or outdated FacetWP - Alpha for UAMS plugin: reading the version,
 * the 1.3.5.2 floor, and who sees it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;
use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

final class AlphaVersionNoticeTest extends UnitTestCase
{
    public function test_the_notice_registers_on_admin_notices(): void
    {
        expectAdded('admin_notices')->once()->with('uamswp_fad_alpha_version_notice');

        uamswp_fad_alpha_version_register();
    }

    public function test_the_floor_is_1_3_5_2(): void
    {
        $this->assertSame('1.3.5.2', UAMSWP_FAD_ALPHA_MIN_VERSION);
    }

    public function test_the_version_is_read_from_the_plugins_constant_by_default(): void
    {
        $this->assertFalse(defined('FACETWP_ALPHA_VERSION'), 'The unit process must not define the plugin constant.');
        $this->assertNull(uamswp_fad_alpha_installed_version());
    }

    public function test_an_undefined_constant_is_no_version(): void
    {
        $this->assertNull(uamswp_fad_alpha_installed_version('UAMSWP_FAD_TEST_ALPHA_UNDEFINED'));
    }

    public function test_a_defined_constant_is_the_installed_version(): void
    {
        define('UAMSWP_FAD_TEST_ALPHA_VERSION', '1.3.5');

        $this->assertSame('1.3.5', uamswp_fad_alpha_installed_version('UAMSWP_FAD_TEST_ALPHA_VERSION'));
    }

    public function test_an_empty_or_non_string_constant_is_no_version(): void
    {
        define('UAMSWP_FAD_TEST_ALPHA_EMPTY', '');
        define('UAMSWP_FAD_TEST_ALPHA_NUMBER', 135);

        $this->assertNull(uamswp_fad_alpha_installed_version('UAMSWP_FAD_TEST_ALPHA_EMPTY'));
        $this->assertNull(uamswp_fad_alpha_installed_version('UAMSWP_FAD_TEST_ALPHA_NUMBER'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function recent_enough(): array
    {
        return [
            'the floor' => ['1.3.5.2'],
            'a later patch' => ['1.3.5.10'],
            'a later minor' => ['1.4'],
            'a later major' => ['2.0.0'],
        ];
    }

    #[DataProvider('recent_enough')]
    public function test_the_floor_or_later_is_no_problem(string $installed): void
    {
        stubTranslationFunctions();

        $this->assertNull(uamswp_fad_alpha_version_problem($installed));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function too_old(): array
    {
        return [
            'the last release without the refresh' => ['1.3.5'],
            'production today' => ['1.3.1'],
            'one patch short in the fourth place' => ['1.3.5.1'],
        ];
    }

    #[DataProvider('too_old')]
    public function test_an_older_version_is_named_with_the_floor(string $installed): void
    {
        stubTranslationFunctions();

        $this->assertSame(
            'FacetWP - Alpha for UAMS '.$installed.' is installed; Find-a-Doc needs 1.3.5.2 or later.',
            uamswp_fad_alpha_version_problem($installed)
        );
    }

    public function test_no_version_says_missing_or_older_than_the_floor(): void
    {
        stubTranslationFunctions();

        $this->assertSame(
            'FacetWP - Alpha for UAMS is not active, or is older than 1.3.5.2.',
            uamswp_fad_alpha_version_problem(null)
        );
    }

    /**
     * Run the notice for an installed version and a user who may or may not activate plugins; return what it printed.
     */
    private function notice(?string $installed, bool $can_activate): string
    {
        stubEscapeFunctions();
        stubTranslationFunctions();
        when('uamswp_fad_alpha_installed_version')->justReturn($installed);
        expect('current_user_can')->once()->with('activate_plugins')->andReturn($can_activate);

        ob_start();
        uamswp_fad_alpha_version_notice();

        return (string) ob_get_clean();
    }

    public function test_a_missing_plugin_shows_the_notice(): void
    {
        $html = $this->notice(null, true);

        $this->assertStringStartsWith('<div class="notice notice-error">', $html);
        $this->assertStringContainsString('<strong>FacetWP - Alpha for UAMS is not active, or is older than 1.3.5.2.</strong>', $html);
        $this->assertStringContainsString('through UAMSWP-Pusher from the UAMS-Web/facetwp-alpha-uams repository', $html);
    }

    public function test_an_older_plugin_shows_the_notice_naming_its_version(): void
    {
        $html = $this->notice('1.3.5', true);

        $this->assertStringContainsString('<strong>FacetWP - Alpha for UAMS 1.3.5 is installed; Find-a-Doc needs 1.3.5.2 or later.</strong>', $html);
    }

    public function test_a_current_plugin_shows_nothing(): void
    {
        $this->assertSame('', $this->notice('1.3.5.2', true));
    }

    public function test_a_user_who_cannot_activate_plugins_sees_nothing_even_when_the_plugin_is_missing(): void
    {
        $this->assertSame('', $this->notice(null, false));
    }

    public function test_the_notice_prints_the_whole_message(): void
    {
        $this->assertSame(
            '<div class="notice notice-error"><p><strong>FacetWP - Alpha for UAMS 1.3.5 is installed; Find-a-Doc needs 1.3.5.2 or later.</strong> The alphabet filter on the provider and location lists needs it. Install or update it through UAMSWP-Pusher from the UAMS-Web/facetwp-alpha-uams repository.</p></div>',
            $this->notice('1.3.5', true)
        );
    }
}
