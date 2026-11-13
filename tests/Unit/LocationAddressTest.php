<?php

/**
 * Pins how address line 2 of a location renders: building, floor and suite.
 *
 * The expected strings were worked out by hand from the inline expressions
 * that the location templates carried before they called the helpers, and
 * include the cases the address-line change (#854) later alters.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class LocationAddressTest extends UnitTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        require_once \dirname(__DIR__, 2).'/includes/class.fad-location-address.php';
    }

    /**
     * The floor field object as get_field_object() returns it for a chosen floor.
     *
     * @return array<string, mixed>
     */
    private function floor(string $value = '2'): array
    {
        return ['value' => $value, 'choices' => ['2' => 'Second Floor']];
    }

    /**
     * The page and card display: '<br />' after the building, ', ' after the floor.
     *
     * @return array<string, array{0: bool, 1: mixed, 2: string, 3: string, 4: string, 5: string}>
     */
    public static function displayCases(): array
    {
        $floor = ['value' => '2', 'choices' => ['2' => 'Second Floor']];
        $ground = ['value' => '0', 'choices' => ['0' => 'Ground']];
        $blank = ['value' => '', 'choices' => []];

        return [
            'building, floor and suite' => [true, $floor, '2', 'Second Floor', 'Suite 200', 'Bldg A<br />Second Floor, Suite 200'],
            'building and floor' => [true, $floor, '2', 'Second Floor', '', 'Bldg A<br />Second Floor'],
            'building only' => [true, false, '', '', '', 'Bldg A'],
            'building and suite' => [true, false, '', '', 'Suite 200', 'Bldg A<br />Suite 200'],
            'floor and suite, no building' => [false, $floor, '2', 'Second Floor', 'Suite 200', 'Second Floor, Suite 200'],
            'floor only, no building' => [false, $floor, '2', 'Second Floor', '', 'Second Floor'],
            'suite only' => [false, false, '', '', 'Suite 200', 'Suite 200'],
            'nothing' => [false, false, '', '', '', ''],
            'floor "0" is not printed, no suite' => [true, $ground, '0', 'Ground', '', 'Bldg A'],
            'floor "0" is not printed, with suite' => [true, $ground, '0', 'Ground', 'Suite 200', 'Bldg A<br />Suite 200'],
            'floor field with no value is not printed' => [true, $blank, '', '', 'Suite 200', 'Bldg A<br />Suite 200'],
            'floor value with no label, with suite' => [true, $floor, '3', '', 'Suite 200', 'Bldg A<br />Suite 200'],
            'floor value with no label, no suite' => [true, $floor, '3', '', '', 'Bldg A'],
        ];
    }

    #[DataProvider('displayCases')]
    public function test_display_line(bool $has_building, bool|array $floor, string $floor_value, string $floor_label, string $suite, string $expected): void
    {
        $this->assertSame(
            $expected,
            \uamswp_location_address_2($has_building, 'Bldg A', $floor, $floor_value, $floor_label, $suite, '<br />', ', ')
        );
    }

    /**
     * The schema markup separates with spaces.
     */
    public function test_schema_line(): void
    {
        $floor = $this->floor();

        $this->assertSame('Bldg A Second Floor Suite 200', \uamswp_location_address_2(true, 'Bldg A', $floor, '2', 'Second Floor', 'Suite 200', ' ', ' '));
        $this->assertSame('Bldg A Second Floor', \uamswp_location_address_2(true, 'Bldg A', $floor, '2', 'Second Floor', '', ' ', ' '));
        $this->assertSame('Suite 200', \uamswp_location_address_2(false, '', false, '', '', 'Suite 200', ' ', ' '));
        $this->assertSame('', \uamswp_location_address_2(false, '', false, '', '', '', ' ', ' '));
    }

    /**
     * Schema markup skips a floor field whose value is empty, as the display
     * does, so a suite after the building gets one space and not two.
     */
    public function test_schema_line_skips_a_floor_field_with_no_value(): void
    {
        $blank = ['value' => '', 'choices' => []];

        $this->assertSame('Bldg A Suite 200', \uamswp_location_address_2(true, 'Bldg A', $blank, '', '', 'Suite 200', ' ', ' '));
        $this->assertSame('Bldg A', \uamswp_location_address_2(true, 'Bldg A', $blank, '', '', '', ' ', ' '));
    }

    /**
     * Schema markup does not print floor "0" either.
     */
    public function test_schema_line_skips_floor_zero(): void
    {
        $ground = ['value' => '0', 'choices' => ['0' => 'Ground']];

        $this->assertSame('Bldg A Suite 200', \uamswp_location_address_2(true, 'Bldg A', $ground, '0', 'Ground', 'Suite 200', ' ', ' '));
    }

    /**
     * The Doximity export separates the building, floor and suite with line breaks.
     */
    public function test_doximity_line(): void
    {
        $floor = $this->floor();

        $this->assertSame('Bldg A<br />Second Floor<br />Suite 200', \uamswp_location_address_2(true, 'Bldg A', $floor, '2', 'Second Floor', 'Suite 200', '<br />', '<br />'));
        $this->assertSame('Second Floor', \uamswp_location_address_2(false, '', $floor, '2', 'Second Floor', '', '<br />', '<br />'));
    }

    /**
     * The separators default to the display values.
     */
    public function test_defaults_are_the_display_values(): void
    {
        $this->assertSame(
            'Bldg A<br />Second Floor, Suite 200',
            \uamswp_location_address_2(true, 'Bldg A', $this->floor(), '2', 'Second Floor', 'Suite 200')
        );
    }

    public function test_parts_in_order(): void
    {
        $this->assertSame(
            ['Bldg A', 'Second Floor', 'Suite 200'],
            \uamswp_location_address_parts(true, 'Bldg A', $this->floor(), '2', 'Second Floor', 'Suite 200')
        );
    }

    public function test_parts_leave_out_what_is_missing(): void
    {
        $ground = ['value' => '0', 'choices' => ['0' => 'Ground']];
        $blank = ['value' => '', 'choices' => []];

        $this->assertSame([], \uamswp_location_address_parts(false, '', false, '', '', ''));
        $this->assertSame(['Suite 200'], \uamswp_location_address_parts(false, '', false, '', '', 'Suite 200'));
        $this->assertSame(['Bldg A', 'Suite 200'], \uamswp_location_address_parts(true, 'Bldg A', $ground, '0', 'Ground', 'Suite 200'));
        $this->assertSame(['Bldg A'], \uamswp_location_address_parts(true, 'Bldg A', $blank, '', '', ''));
        $this->assertSame(['Second Floor'], \uamswp_location_address_parts(false, '', $this->floor(), '2', 'Second Floor', ''));
    }

    /**
     * A floor value with no label adds no entry, so there is no empty line
     * between the facility and the city.
     */
    public function test_parts_skip_a_floor_with_no_label(): void
    {
        $this->assertSame(
            ['Bldg A', 'Suite 200'],
            \uamswp_location_address_parts(true, 'Bldg A', $this->floor('3'), '3', '', 'Suite 200')
        );
        $this->assertSame(['Bldg A'], \uamswp_location_address_parts(true, 'Bldg A', $this->floor('3'), '3', '', ''));
    }
}
