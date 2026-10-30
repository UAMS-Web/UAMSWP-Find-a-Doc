<?php

/**
 * The location inputs Google has no field for are gone, and the ones it still has are kept.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpLocationDeadInputsTest extends UnitTestCase
{
    private const DEAD = [
        'has_wheelchair_accessible_elevator',
        'requires_temperature_check_customers',
        'requires_masks_customers',
        'requires_temperature_check_staff',
        'requires_masks_staff',
        'is_sanitizing_between_customers',
    ];

    private const KEPT = [
        'has_wheelchair_accessible_entrance',
        'has_wheelchair_accessible_restroom',
        'has_restroom',
        'requires_appointments',
    ];

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

    /**
     * @return array<int, string>
     */
    private function field_names(): array
    {
        $group = json_decode((string) file_get_contents($this->root().'/assets/json/acf-json/group_uamswp_locations.json'), true);

        return array_column($group['fields'], 'name');
    }

    /**
     * @return array<int, string>
     */
    private function table_columns(): array
    {
        $table = json_decode((string) file_get_contents($this->root().'/assets/json/acf-tables/table_uamswp_locations.json'), true);

        return array_column($table['columns'], 'name');
    }

    public function test_the_dead_inputs_are_not_in_the_field_group_or_the_table_definition(): void
    {
        foreach (self::DEAD as $name) {
            $this->assertNotContains($name, $this->field_names(), $name.' is still a field');
            $this->assertNotContains($name, $this->table_columns(), $name.' is still a table column');
        }
    }

    public function test_the_inputs_google_still_has_are_kept(): void
    {
        foreach (self::KEPT as $name) {
            $this->assertContains($name, $this->field_names(), $name);
            $this->assertContains($name, $this->table_columns(), $name);
        }
    }

    public function test_no_code_reads_a_dead_input_or_the_covid_columns(): void
    {
        foreach ($this->php_files() as $file) {
            $source = (string) file_get_contents($file);

            foreach (array_merge(self::DEAD, ['url_covid_19_info_page', 'url_facility_telemedicine_page', '$covid19']) as $needle) {
                $this->assertStringNotContainsString($needle, $source, basename($file).' still mentions '.$needle);
            }
        }
    }

    public function test_the_unused_location_list_template_is_gone(): void
    {
        $this->assertFileDoesNotExist($this->root().'/templates/gmb-list-location.php');

        foreach ($this->php_files() as $file) {
            $this->assertStringNotContainsString('gmb-list-location', (string) file_get_contents($file), basename($file));
        }
    }

    public function test_the_telemedicine_input_that_drives_the_public_pages_is_kept(): void
    {
        $source = (string) file_get_contents($this->root().'/includes/class.fad-gmb-settings-page.php');

        $this->assertStringContainsString("location_hours_group['location_telemed_query']", $source);
    }
}
