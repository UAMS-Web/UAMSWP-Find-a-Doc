<?php

/**
 * A location name in an address line drops the place it is "in" or "at"; the listing title does not.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpChildAddressTest extends UnitTestCase
{
    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function shortened_cases(): array
    {
        return [
            'in the a hospital' => ['Specialty Clinic in the Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'in a hospital' => ['Specialty Clinic in Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'at the a hospital' => ['Specialty Clinic at the Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'at a hospital' => ['Specialty Clinic at Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'a hyphenated city' => ['Family Medical Center in Helena-West Helena', 'Family Medical Center'],
            'a city' => ['Family Medical Center in Fayetteville', 'Family Medical Center'],
            'a neighborhood' => ['Pediatric Clinic at the Heights', 'Pediatric Clinic'],
            'a street and a city' => ['Walk-in Clinic on Cottage in Little Rock', 'Walk-in Clinic'],
            'on a street' => ['Specialty Clinic on Cottage Street', 'Specialty Clinic'],
            'on the' => ['Pediatric Clinic on the Square', 'Pediatric Clinic'],
            'a facility and a city, the first joiner wins' => ["Cardiology at Arkansas Children's in Little Rock", 'Cardiology'],
            'upper case' => ['SPECIALTY CLINIC IN THE ORTHOPAEDIC AND SPINE HOSPITAL', 'SPECIALTY CLINIC'],
            'lower case' => ['specialty clinic at orthopaedic and spine hospital', 'specialty clinic'],
            'mixed case' => ['Specialty Clinic In The orthopaedic AND spine Hospital', 'Specialty Clinic'],
            'extra space before and after the joiner' => ['Specialty Clinic   in   the   Hospital', 'Specialty Clinic'],
            'surrounding space' => ['  Specialty Clinic in Fayetteville  ', 'Specialty Clinic'],
        ];
    }

    #[DataProvider('shortened_cases')]
    public function test_the_place_after_in_or_at_is_removed_in_any_letter_case(string $name, string $expected): void
    {
        $this->assertSame($expected, UAMSWP_FAD_GBP_Logic::location_name_without_place($name));
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function unchanged_cases(): array
    {
        return [
            'no joiner' => ['Specialty Clinic'],
            'a longer word that starts with a joiner' => ['Specialty Clinic Inland Center'],
            'a joiner inside a word' => ['Main Street Clinic, Latin Wing'],
            'a hyphenated joiner' => ['Walk-in Clinic'],
            'a word that starts with a joiner' => ['Onsite Clinic Inland'],
            'another joiner' => ['Specialty Clinic near the Hospital'],
            'nothing before the joiner' => [' in the Hospital'],
            'nothing after the joiner' => ['Specialty Clinic in'],
            'a plain name' => ['UAMS Health Northwest'],
            'empty' => [''],
        ];
    }

    #[DataProvider('unchanged_cases')]
    public function test_any_other_name_is_left_as_it_is(string $name): void
    {
        $this->assertSame(trim($name), UAMSWP_FAD_GBP_Logic::location_name_without_place($name));
    }

    public function test_a_joiner_in_a_word_is_not_a_joiner_even_when_something_follows(): void
    {
        $this->assertSame('Inland Center', UAMSWP_FAD_GBP_Logic::location_name_without_place('Inland Center'));
        $this->assertSame('Atlas Clinic', UAMSWP_FAD_GBP_Logic::location_name_without_place('Atlas Clinic'));
    }

    public function test_the_in_theater_name_loses_only_what_follows_in(): void
    {
        $this->assertSame('Specialty Clinic', UAMSWP_FAD_GBP_Logic::location_name_without_place('Specialty Clinic in theater district'));
    }

    public function test_the_address_lines_after_the_street_are_the_parent_and_the_short_child(): void
    {
        $child = 'Specialty Clinic in the Orthopaedic and Spine Hospital';
        $row = [
            0 => 'kendall-m-masada_specialty-clinic-ortho',
            1 => UAMSWP_FAD_GBP_Logic::provider_listing_title('Kendall M. Masada, M.D.', $child),
            2 => '801 Cottage Dr',
            3 => 'Orthopaedic and Spine Hospital',
            4 => UAMSWP_FAD_GBP_Logic::location_name_without_place($child),
            7 => 'Little Rock',
            8 => 'AR',
            10 => '72205',
        ];

        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($row, []);

        $this->assertSame(['801 Cottage Dr', 'Orthopaedic and Spine Hospital', 'Specialty Clinic'], $fields['storefrontAddress']['addressLines']);
        $this->assertSame('UAMS Health - Kendall M. Masada, M.D. - Specialty Clinic in the Orthopaedic and Spine Hospital', $fields['title'], 'the title keeps the full child name');
    }

    public function test_the_provider_table_shortens_every_location_name_in_the_address_lines(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-gmb-settings-page.php');

        $start = strpos($source, 'function uamswp_fad_gmb_provider_table(');
        $end = strpos($source, "\nfunction ", $start + 1);
        $table = substr($source, $start, $end === false ? null : $end - $start);

        $this->assertStringContainsString('$location_addresses[] = UAMSWP_FAD_GBP_Logic::location_name_without_place($location_parent_title);', $table, 'the parent');
        $this->assertSame(2, substr_count($table, '$location_addresses[] = UAMSWP_FAD_GBP_Logic::location_name_without_place($location_title);'), 'a name with no parent, and a child');
        $this->assertStringNotContainsString('$location_addresses[] = $location_title;', $table, 'no location name goes in unshortened');
        $this->assertStringContainsString('provider_listing_title(html_entity_decode($full_name), html_entity_decode(get_the_title(uamswp_fad_int($location))))', $table, 'the title is built from the full name');
    }
}
