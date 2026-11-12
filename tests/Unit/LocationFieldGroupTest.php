<?php

/**
 * Pins the fields of the locations ACF field group that other code reads by
 * key, name and type.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocationFieldGroupTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function fields(): array
    {
        return [
            'street address' => ['location_address_1', 'field_location_address_1', 'text'],
            'facility' => ['location_building', 'field_location_building', 'taxonomy'],
            'building floor' => ['location_building_floor', 'field_location_building_floor', 'select'],
            'suite' => ['location_suite', 'field_location_suite', 'text'],
            'city' => ['location_city', 'field_location_city', 'text'],
            'state' => ['location_state', 'field_location_state', 'select'],
            'ZIP code' => ['location_zip', 'field_location_zip', 'maskfield'],
            'NPI' => ['location_npi_item', 'field_location_npi_item', 'number'],
        ];
    }

    #[DataProvider('fields')]
    public function test_field_keeps_its_key_and_type(string $name, string $key, string $type): void
    {
        $found = $this->findAll($name);

        $this->assertCount(1, $found, "field {$name} appears once in the group");
        $this->assertSame($key, $found[0]['key']);
        $this->assertSame($type, $found[0]['type']);
    }

    public function test_group_loads(): void
    {
        $group = $this->group();

        $this->assertSame('group_uamswp_locations', $group['key']);
        $this->assertNotEmpty($group['fields']);
    }

    /**
     * @return array<string, mixed>
     */
    private function group(): array
    {
        $path = \dirname(__DIR__, 2).'/assets/json/acf-json/group_uamswp_locations.json';
        $decoded = json_decode((string) file_get_contents($path), true);
        $this->assertIsArray($decoded);

        return $decoded;
    }

    /**
     * Every field named $name, at any depth.
     *
     * @return array<int, array<string, mixed>>
     */
    private function findAll(string $name): array
    {
        $found = [];
        $walk = static function (array $node) use (&$walk, &$found, $name): void {
            if (($node['name'] ?? null) === $name && isset($node['key'], $node['type'])) {
                $found[] = $node;
            }

            foreach ($node as $child) {
                if (\is_array($child)) {
                    $walk($child);
                }
            }
        };
        $walk($this->group());

        return $found;
    }
}
