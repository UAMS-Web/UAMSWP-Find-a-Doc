<?php

/**
 * The Google Business Profile provider CSV export is gone; the sync replaces it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpProviderExportRetiredTest extends UnitTestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @return array<int, string>
     */
    private function php_files(): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root().'/includes', \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    public function test_no_code_refers_to_the_provider_export_action_nonce_or_handler(): void
    {
        foreach ($this->php_files() as $file) {
            $source = (string) file_get_contents($file);

            foreach (['download_gmb_provider_csv', 'gmb_provider_csv_export', 'csv-header-gmb-providers'] as $needle) {
                $this->assertStringNotContainsString($needle, $source, basename($file).' still mentions '.$needle);
            }
        }
    }

    public function test_the_unused_provider_list_template_is_gone(): void
    {
        $this->assertFileDoesNotExist($this->root().'/templates/gmb-list-provider.php');

        foreach ($this->php_files() as $file) {
            $this->assertStringNotContainsString('gmb-list-provider', (string) file_get_contents($file), basename($file));
        }
    }

    public function test_the_other_exports_and_the_shared_table_builder_remain(): void
    {
        $source = (string) file_get_contents($this->root().'/includes/class.fad-gmb-settings-page.php');

        foreach (['doximity_csv_export', 'mychart_csv_export', 'uamswp_fad_gmb_provider_table', 'uamswp_fad_gmb_location_table'] as $kept) {
            $this->assertStringContainsString('function '.$kept.'(', $source, $kept.' should still exist');
        }
    }
}
