<?php

/**
 * Pins how an unanswered provider field reaches the custom table: an empty
 * string becomes null for the seven integer-column fields, and nothing else
 * changes.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProviderNullableFieldsTest extends TestCase
{
    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        require_once \dirname(__DIR__, 2).'/includes/class.fad-provider-nullable-fields.php';
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function nullableFields(): array
    {
        $names = [
            'physician_eligible_appointments',
            'physician_primary_care',
            'physician_referral_required',
            'physician_accepting_patients',
            'physician_second_opinion',
            'physician_pid',
            'physician_npi',
        ];

        return array_combine($names, array_map(static function (string $name): array {
            return [$name];
        }, $names));
    }

    #[DataProvider('nullableFields')]
    public function test_empty_string_becomes_null(string $name): void
    {
        $this->assertNull(\uamswp_provider_null_empty_value('', 26, ['name' => $name]));
    }

    /**
     * A real answer, including "No" (0) and a real number, is stored as given.
     */
    #[DataProvider('nullableFields')]
    public function test_a_given_value_is_kept(string $name): void
    {
        foreach ([0, '0', 1, '1', 1234567890, '1234567890', 'x'] as $value) {
            $this->assertSame($value, \uamswp_provider_null_empty_value($value, 26, ['name' => $name]));
        }
    }

    #[DataProvider('nullableFields')]
    public function test_null_and_empty_array_are_left_alone(string $name): void
    {
        $this->assertNull(\uamswp_provider_null_empty_value(null, 26, ['name' => $name]));
        $this->assertSame([], \uamswp_provider_null_empty_value([], 26, ['name' => $name]));
    }

    /**
     * Other fields keep an empty string, as before.
     *
     * @return void
     */
    public function test_other_fields_keep_an_empty_string(): void
    {
        foreach (['physician_first_name', 'physician_gender', 'physician_searchable', 'physician_author_number', 'location_facility', ''] as $name) {
            $this->assertSame('', \uamswp_provider_null_empty_value('', 26, ['name' => $name]), $name);
        }
    }

    /**
     * @return void
     */
    public function test_a_field_with_no_name_is_left_alone(): void
    {
        $this->assertSame('', \uamswp_provider_null_empty_value('', 26, []));
        $this->assertSame('', \uamswp_provider_null_empty_value('', 26, ['key' => 'field_physician_npi']));
        $this->assertSame('', \uamswp_provider_null_empty_value('', 26, null));
        $this->assertSame('', \uamswp_provider_null_empty_value('', 26, 'physician_npi'));
        $this->assertSame('', \uamswp_provider_null_empty_value(''));
    }

    /**
     * The list is the seven fields of the ticket that are stored in an integer
     * column of the provider table.
     *
     * @return void
     */
    public function test_the_list_matches_the_integer_columns(): void
    {
        $this->assertSame(
            [
                'physician_eligible_appointments',
                'physician_primary_care',
                'physician_referral_required',
                'physician_accepting_patients',
                'physician_second_opinion',
                'physician_pid',
                'physician_npi',
            ],
            \uamswp_provider_nullable_field_names()
        );

        $table = json_decode((string) file_get_contents(\dirname(__DIR__, 2).'/assets/json/acf-tables/table_uamswp_physicians.json'), true);
        $this->assertIsArray($table);

        $integer_columns = [];
        foreach ($table['columns'] as $column) {
            if (($column['format'] ?? '') === '%d') {
                $integer_columns[] = $column['name'];
            }
        }

        foreach (\uamswp_provider_nullable_field_names() as $name) {
            $this->assertContains($name, $integer_columns, "{$name} is an integer column");
        }
    }
}
