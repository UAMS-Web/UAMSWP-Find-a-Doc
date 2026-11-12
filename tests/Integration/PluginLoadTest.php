<?php

/**
 * What loading the plugin file does once WordPress has booted: the constants it defines, the loader hooked to
 * plugins_loaded and every include it pulls in, the Business Profile sync hooks, and the required plugins it
 * registers with TGM Plugin Activation (uamswp-find-a-doc.php, includes/find-a-doc.php, required-plugins.php).
 *
 * The branch that refuses to load below PHP 8.2 cannot run on the PHP this suite needs.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * @command  composer test:integration -- --filter PluginLoadTest
 */
final class PluginLoadTest extends IntegrationTestCase
{
    private function pluginRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    public function test_the_path_constant_is_the_plugin_folder_with_a_trailing_slash(): void
    {
        $this->assertSame(wp_normalize_path($this->pluginRoot()).'/', wp_normalize_path(UAMS_FAD_PATH));
    }

    public function test_the_url_constant_is_the_plugin_folder_url_with_a_trailing_slash(): void
    {
        $this->assertSame(trailingslashit(plugins_url('', $this->pluginRoot().'/uamswp-find-a-doc.php')), UAMS_FAD_ROOT_URL);
        $this->assertStringEndsWith('/'.basename($this->pluginRoot()).'/', UAMS_FAD_ROOT_URL);
    }

    public function test_the_version_constant_is_the_version_in_the_plugin_header(): void
    {
        preg_match('/^Version:[ \t]*(.*?)\s*$/m', (string) file_get_contents($this->pluginRoot().'/uamswp-find-a-doc.php'), $match);

        $this->assertNotEmpty($match[1]);
        $this->assertSame($match[1], UAMS_FAD_VERSION);
    }

    public function test_the_loader_runs_when_plugins_are_loaded(): void
    {
        $this->assertSame(10, has_action('plugins_loaded', 'UAMS\Find_a_Doc\bootstrap'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function includes(): array
    {
        $files = [
            'class.fad-physicians.php', 'class.fad-custom-post.php', 'class.fad-helper-functions.php', 'class.fad-location-address.php',
            'class.fad-settings.php', 'class.fad-templates.php', 'class.fad-acf-functions.php', 'class.fad-acf-table-map-notice.php',
            'class.fad-provider-nullable-fields.php', 'class.fad-provider-preview.php', 'class.fad-delete-cleanup.php',
            'class.fad-dead-link-cleanup.php', 'class.fad-fwp-functions.php', 'class.fad-functions.php', 'class.fad-settings-pages.php',
            'class.fad-acf-blocks.php', 'class.fad-gmb-settings-page.php', 'class.fad-editor-groups.php', 'class.fad-edit-flow-status.php',
            'class.fad-gbp-logic.php', 'class.fad-gbp-client.php', 'class.fad-gbp-sync.php', 'class.fad-gbp-runtime.php',
            'class.fad-gbp-settings.php', 'class.fad-gbp-cli.php', 'class.fad-gbp-dashboard.php', 'class.fad-gbp-orphans.php',
        ];

        return array_combine($files, array_map(static fn (string $file): array => [$file], $files));
    }

    /**
     * @dataProvider includes
     */
    public function test_the_loader_includes_each_part_of_the_plugin(string $file): void
    {
        $included = array_map(static fn (string $path): string => wp_normalize_path($path), get_included_files());

        $this->assertContains(wp_normalize_path($this->pluginRoot()).'/includes/'.$file, $included);
    }

    public function test_the_loader_registers_the_business_profile_sync_hooks(): void
    {
        $this->assertSame(30, has_action('acf/save_post', 'uamswp_fad_gbp_queue_on_save'));
        $this->assertSame(10, has_action('transition_post_status', 'uamswp_fad_gbp_queue_on_publish'));
        $this->assertSame(10, has_action(UAMSWP_FAD_GBP_SYNC_HOOK, 'uamswp_fad_gbp_run_post'));
        $this->assertSame(110, has_action('admin_menu', 'uamswp_fad_gbp_add_settings_page'));
        $this->assertSame(10, has_action('admin_post_uamswp_fad_gbp_save_modes', 'uamswp_fad_gbp_save_modes'));
        $this->assertSame(10, has_action('admin_post_uamswp_fad_gbp_queue_all', 'uamswp_fad_gbp_queue_all_action'));
        $this->assertSame(10, has_action('wp_dashboard_setup', 'uamswp_fad_gbp_register_dashboard_widget'));
        $this->assertSame(10, has_action('transition_post_status', 'uamswp_fad_gbp_track_unpublish'));
        $this->assertSame(10, has_action(UAMSWP_FAD_GBP_ORPHAN_HOOK, 'uamswp_fad_gbp_run_orphan_scan'));
    }

    public function test_the_required_plugins_are_registered_when_activation_checks_run(): void
    {
        $this->assertSame(10, has_action('tgmpa_register', 'uamswp_register_required_plugins'));
    }

    public function test_the_required_plugins_are_registered_with_their_sources_and_flags(): void
    {
        uamswp_register_required_plugins();

        $plugins = \TGM_Plugin_Activation::get_instance()->plugins;
        $root = $this->pluginRoot();

        $expected = [
            'advanced-custom-fields-pro' => ['Advanced Custom Fields PRO', true, $root.'/plugins/advanced-custom-fields-pro.zip'],
            'acf-maskfield' => ['Advanced Custom Fields: Mask Field', true, $root.'/plugins/acf-maskfield.zip'],
            'acf-extended' => ['Advanced Custom Fields: Extended', true, 'repo'],
            'facetwp' => ['FacetWP', true, $root.'/plugins/facetwp.zip'],
            'facetwp-alpha-uams' => ['FacetWP - Alpha for UAMS', true, $root.'/plugins/facetwp-alpha-uams.zip'],
            'facetwp-cache' => ['FacetWP - Cache', false, $root.'/plugins/facetwp-cache.zip'],
            'ajax-search-pro' => ['Ajax Search Pro', false, $root.'/plugins/ajax-search-pro.zip'],
        ];

        $this->assertSame(array_keys($expected), array_keys($plugins));

        foreach ($expected as $slug => [$name, $required, $source]) {
            $this->assertSame($name, $plugins[$slug]['name'], $slug);
            $this->assertSame($required, $plugins[$slug]['required'], $slug);
            $this->assertSame(wp_normalize_path($source), wp_normalize_path($plugins[$slug]['source']), $slug);
        }
    }

    public function test_the_activation_notices_use_this_plugins_id_and_menu(): void
    {
        uamswp_register_required_plugins();

        $tgmpa = \TGM_Plugin_Activation::get_instance();

        $this->assertSame('uamswp', $tgmpa->id);
        $this->assertSame('tgmpa-install-plugins', $tgmpa->menu);
        $this->assertSame('plugins.php', $tgmpa->parent_slug);
        $this->assertSame('manage_options', $tgmpa->capability);
        $this->assertTrue($tgmpa->has_notices);
        $this->assertTrue($tgmpa->dismissable);
        $this->assertFalse($tgmpa->is_automatic);
    }
}
