<?php

/**
 * Pins the plugin's entry files: the main file's PHP floor, constants and includes, the loader that pulls in every
 * part of the plugin, the required-plugins registration and the Alpha add-on's facet registration.
 *
 * The files declare functions that can only exist once per process, so the first test includes the main file and
 * the later tests reuse what it declared. The main file's `return` for an old PHP is reached by a second include.
 * TGM Plugin Activation needs the WordPress admin classes, so the classes it checks for are declared here and its
 * helper is mocked.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Mockery;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

final class RootFilesTest extends UnitTestCase
{
    /** @var list<array{0: string, 1: mixed}> */
    private array $defined = [];

    /** @var list<array{0: string, 1: mixed, 2: int}> */
    private array $actions = [];

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    private function recordHooks(): void
    {
        when('add_action')->alias(function (string $hook, mixed $callback, int $priority = 10): void {
            $this->actions[] = [$hook, $callback, $priority];
        });
    }

    private function stubWordPress(): void
    {
        $this->recordHooks();
        when('define')->alias(function (string $name, mixed $value): bool {
            $this->defined[] = [$name, $value];

            return true;
        });
        when('plugin_dir_url')->alias(static fn (string $file): string => 'url:'.basename($file));
        when('plugin_dir_path')->alias(static fn (string $file): string => 'path:'.basename($file));
        when('get_file_data')->alias(static fn (string $file, array $headers): array => ['version' => 'v:'.basename($file).':'.implode(',', array_keys($headers)).':'.implode(',', $headers)]);
    }

    private function declareTgmClasses(): void
    {
        if (! class_exists('TGM_Plugin_Activation', false)) {
            eval('class TGM_Plugin_Activation {}');
        }

        if (! class_exists('WP_List_Table', false)) {
            eval('class WP_List_Table {}');
        }
    }

    /**
     * @return list<string>
     */
    private function hooks(): array
    {
        return array_map(static fn (array $action): string => $action[0].'@'.$action[2], $this->actions);
    }

    public function test_the_main_file_defines_the_constants_and_loads_the_required_plugins_and_the_loader(): void
    {
        $this->stubWordPress();
        $this->declareTgmClasses();

        include $this->root().'/uamswp-find-a-doc.php';

        $this->assertSame([
            ['UAMS_FAD_ROOT_URL', 'url:uamswp-find-a-doc.php'],
            ['UAMS_FAD_PATH', 'path:uamswp-find-a-doc.php'],
            ['UAMS_FAD_VERSION', 'v:uamswp-find-a-doc.php:version:Version'],
        ], $this->defined);
        $this->assertTrue(function_exists('uamswp_register_required_plugins'));
        $this->assertTrue(function_exists('UAMS\Find_a_Doc\bootstrap'));
        $this->assertContains('tgmpa_register@10', $this->hooks());
        $this->assertContains('plugins_loaded@10', $this->hooks());
        $this->assertNotContains('admin_notices@10', $this->hooks());
    }

    public function test_an_old_php_shows_a_notice_and_defines_nothing(): void
    {
        $this->stubWordPress();
        when('version_compare')->alias(static fn (string $a, string $b, string $operator): bool => [PHP_VERSION, '8.2', '<'] === [$a, $b, $operator]);
        when('esc_html__')->alias(static fn (string $text, string $domain): string => '['.$domain.'] '.$text);

        include $this->root().'/uamswp-find-a-doc.php';

        $this->assertSame([], $this->defined);
        $this->assertSame(['admin_notices@10'], $this->hooks());
        ob_start();
        ($this->actions[0][1])();
        $this->assertSame(
            '<div class="error"><p>[uamswp-find-a-doc] UAMSWP Find-a-doc requires PHP 8.2 to function properly. Please upgrade PHP or deactivate the plugin.</p></div>',
            ob_get_clean()
        );
    }

    public function test_a_supported_php_does_not_show_the_notice(): void
    {
        $this->stubWordPress();
        $this->declareTgmClasses();
        when('version_compare')->alias(static fn (string $a, string $b, string $operator): bool => false);

        include $this->root().'/uamswp-find-a-doc.php';

        $this->assertCount(3, $this->defined);
    }

    public function test_the_required_plugins_are_handed_to_tgm_with_this_plugins_menu(): void
    {
        $this->stubWordPress();
        $this->declareTgmClasses();
        include $this->root().'/uamswp-find-a-doc.php';

        $root = $this->root();
        expect('tgmpa')->once()->with(Mockery::on(function (mixed $plugins) use ($root): bool {
            $this->assertSame([
                ['name' => 'Advanced Custom Fields PRO', 'slug' => 'advanced-custom-fields-pro', 'source' => $root.'/plugins/advanced-custom-fields-pro.zip', 'required' => true],
                ['name' => 'Advanced Custom Fields: Mask Field', 'slug' => 'acf-maskfield', 'source' => $root.'/plugins/acf-maskfield.zip', 'required' => true],
                ['name' => 'Advanced Custom Fields: Extended', 'slug' => 'acf-extended', 'required' => true],
                ['name' => 'FacetWP', 'slug' => 'facetwp', 'source' => $root.'/plugins/facetwp.zip', 'required' => true],
                ['name' => 'FacetWP - Alpha for UAMS', 'slug' => 'facetwp-alpha-uams', 'source' => $root.'/plugins/facetwp-alpha-uams.zip', 'required' => true],
                ['name' => 'FacetWP - Cache', 'slug' => 'facetwp-cache', 'source' => $root.'/plugins/facetwp-cache.zip', 'required' => false],
                ['name' => 'Ajax Search Pro', 'slug' => 'ajax-search-pro', 'source' => $root.'/plugins/ajax-search-pro.zip', 'required' => false],
            ], $plugins);

            return true;
        }), [
            'id' => 'uamswp',
            'default_path' => '',
            'menu' => 'tgmpa-install-plugins',
            'parent_slug' => 'plugins.php',
            'capability' => 'manage_options',
            'has_notices' => true,
            'dismissable' => true,
            'dismiss_msg' => '',
            'is_automatic' => false,
            'message' => '',
        ]);

        uamswp_register_required_plugins();
    }

    /**
     * The loader runs only when WordPress fires plugins_loaded, and it pulls in every class file, so it cannot be
     * executed in this process without loading each of them over the files the other Unit tests load. The
     * Integration suite executes it (PluginLoadTest). This pins what it includes and registers.
     */
    public function test_the_loader_includes_every_part_of_the_plugin_and_registers_the_business_profile_hooks(): void
    {
        $source = (string) file_get_contents($this->root().'/includes/find-a-doc.php');

        preg_match_all("/^    include_once __DIR__\\.'\\/(class\\.fad-[a-z-]+\\.php)';/m", $source, $match);
        $included = $match[1];
        $onDisk = array_map(basename(...), glob($this->root().'/includes/class.fad-*.php') ?: []);
        sort($included);
        sort($onDisk);

        $this->assertSame(31, count($included));
        $this->assertSame(array_values(array_unique($included)), $included, 'no file is included twice');
        $this->assertSame(
            ['class.fad-acf-table-map-check.php', 'class.fad-expertise-attributes-meta-box.php', 'class.fad-set-session.php'],
            array_values(array_diff($onDisk, $included)),
            'the table map check is required by its notice; the other two are not loaded by the plugin'
        );
        $this->assertSame(
            ['uamswp_fad_gbp_register_hooks', 'uamswp_fad_gbp_register_settings_hooks', 'uamswp_fad_gbp_register_dashboard_hooks', 'uamswp_fad_gbp_register_orphan_hooks', 'uamswp_fad_gbp_register_cli'],
            $this->registrations($source)
        );
    }

    /**
     * @return list<string>
     */
    private function registrations(string $source): array
    {
        preg_match_all('/^    \\\\(uamswp_fad_gbp_register_[a-z_]+)\\(\\);/m', $source, $match);

        return $match[1];
    }
}
