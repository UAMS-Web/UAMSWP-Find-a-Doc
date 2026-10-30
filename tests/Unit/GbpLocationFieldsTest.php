<?php

/**
 * The location field group carries the two Google Business Profiles exclusion fields.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpLocationFieldsTest extends UnitTestCase
{
    /**
     * @return array<int, array<string, mixed>>
     */
    private function fields(): array
    {
        $group = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/assets/json/acf-json/group_uamswp_locations.json'), true);

        return $group['fields'];
    }

    /**
     * @return array<string, mixed>
     */
    private function field(string $name): array
    {
        foreach ($this->fields() as $field) {
            if (($field['name'] ?? '') === $name) {
                return $field;
            }
        }

        $this->fail('The location field group has no '.$name.' field.');
    }

    public function test_the_provider_exclusion_field_exists_with_the_same_shape_as_the_location_one(): void
    {
        $location = $this->field('location_gmb_exclude');
        $providers = $this->field('location_gmb_exclude_providers');

        $this->assertSame('field_location_gmb_exclude_providers', $providers['key']);
        $this->assertSame('Should providers at this location be excluded from any bulk imports and updates in Google Business Profiles?', $providers['label']);
        foreach (['type', 'choices', 'default_value', 'layout', 'return_format', 'required'] as $property) {
            $this->assertSame($location[$property], $providers[$property], $property);
        }
        $this->assertSame('0', $providers['default_value'], 'existing locations must keep today\'s behavior');
    }

    public function test_the_provider_exclusion_field_sits_right_after_the_location_one(): void
    {
        $names = array_column($this->fields(), 'name');

        $this->assertSame(array_search('location_gmb_exclude', $names, true) + 1, array_search('location_gmb_exclude_providers', $names, true));
    }

    public function test_field_keys_stay_unique(): void
    {
        $keys = array_column($this->fields(), 'key');

        $this->assertSame($keys, array_values(array_unique($keys)));
    }

    public function test_the_location_export_no_longer_compares_the_exclusion_flag_to_a_boolean(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-gmb-settings-page.php');

        $this->assertStringNotContainsString('$location_gmb_exclude !== true', $source);
        $this->assertStringContainsString('UAMSWP_FAD_GBP_Logic::is_yes($location_gmb_exclude)', $source);
    }
}
