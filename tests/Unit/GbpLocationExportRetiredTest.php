<?php

/**
 * The Google Business Profile location CSV export is gone; the sync replaces it.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpLocationExportRetiredTest extends UnitTestCase
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

    public function test_no_code_refers_to_the_location_export_action_nonce_handler_or_file_name(): void
    {
        foreach ($this->php_files() as $file) {
            $source = (string) file_get_contents($file);

            foreach (['download_gmb_location_csv', 'gmb_location_csv_export', 'csv-header-gmb-locations', 'GMB_Locations_'] as $needle) {
                $this->assertStringNotContainsString($needle, $source, basename($file).' still mentions '.$needle);
            }
        }
    }

    public function test_the_doximity_and_mychart_exports_and_both_table_builders_remain(): void
    {
        $source = (string) file_get_contents($this->root().'/includes/class.fad-gmb-settings-page.php');

        foreach (['doximity_csv_export', 'mychart_csv_export', 'uamswp_fad_gmb_provider_table', 'uamswp_fad_gmb_location_table'] as $kept) {
            $this->assertStringContainsString('function '.$kept.'(', $source, $kept.' should still exist');
        }

        foreach (['download_doximity_csv', 'download_mychart_csv', 'csv-header-doximity', 'csv-header-mychart'] as $kept) {
            $this->assertStringContainsString($kept, $source, $kept.' should still be on the export page');
        }
    }

    public function test_the_sync_still_reads_the_location_table(): void
    {
        $source = (string) file_get_contents($this->root().'/includes/class.fad-gbp-runtime.php');

        $this->assertStringContainsString('uamswp_fad_gmb_location_table(', $source);
    }
}
