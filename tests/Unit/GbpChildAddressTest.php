<?php

/**
 * A child location's address line drops the parent's name it ends with; the listing title does not.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpChildAddressTest extends UnitTestCase
{
    private const PARENT = 'Orthopaedic and Spine Hospital';

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function pattern_cases(): array
    {
        return [
            'in the' => ['Specialty Clinic in the Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'in' => ['Specialty Clinic in Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'at the' => ['Specialty Clinic at the Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'at' => ['Specialty Clinic at Orthopaedic and Spine Hospital', 'Specialty Clinic'],
            'in the, upper case' => ['SPECIALTY CLINIC IN THE ORTHOPAEDIC AND SPINE HOSPITAL', 'SPECIALTY CLINIC'],
            'at, lower case' => ['specialty clinic at orthopaedic and spine hospital', 'specialty clinic'],
            'mixed case joiner and parent' => ['Specialty Clinic In The orthopaedic AND spine Hospital', 'Specialty Clinic'],
            'extra space before the joiner' => ['Specialty Clinic   in the Orthopaedic and Spine Hospital', 'Specialty Clinic'],
        ];
    }

    /**
     * @dataProvider pattern_cases
     */
    public function test_each_pattern_is_removed_in_any_letter_case(string $child, string $expected): void
    {
        $this->assertSame($expected, UAMSWP_FAD_GBP_Logic::child_name_without_parent($child, self::PARENT));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function unchanged_cases(): array
    {
        return [
            'does not end with the parent' => ['Specialty Clinic', self::PARENT],
            'parent in the middle' => ['Clinic in the Orthopaedic and Spine Hospital Annex', self::PARENT],
            'another joiner' => ['Specialty Clinic near the Orthopaedic and Spine Hospital', self::PARENT],
            'parent with no joiner' => ['Specialty Clinic Orthopaedic and Spine Hospital', self::PARENT],
            'joiner without a space before it' => ['Clinicin the Orthopaedic and Spine Hospital', self::PARENT],
            'a different parent' => ['Specialty Clinic in the Children\'s Hospital', self::PARENT],
            'child is the parent name' => [self::PARENT, self::PARENT],
            'nothing before the joiner' => [' in the Orthopaedic and Spine Hospital', self::PARENT],
            'no parent' => ['Specialty Clinic in the Orthopaedic and Spine Hospital', ''],
            'blank parent' => ['Specialty Clinic in the Orthopaedic and Spine Hospital', '   '],
        ];
    }

    /**
     * @dataProvider unchanged_cases
     */
    public function test_any_other_name_is_left_as_it_is(string $child, string $parent): void
    {
        $this->assertSame(trim($child), UAMSWP_FAD_GBP_Logic::child_name_without_parent($child, $parent));
    }

    public function test_the_address_lines_after_the_street_are_the_parent_and_the_short_child(): void
    {
        $child = 'Specialty Clinic in the Orthopaedic and Spine Hospital';
        $row = [
            0 => 'kendall-m-masada_specialty-clinic-ortho',
            1 => UAMSWP_FAD_GBP_Logic::provider_listing_title('Kendall M. Masada, M.D.', $child),
            2 => '801 Cottage Dr',
            3 => self::PARENT,
            4 => UAMSWP_FAD_GBP_Logic::child_name_without_parent($child, self::PARENT),
            7 => 'Little Rock',
            8 => 'AR',
            10 => '72205',
        ];

        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($row, []);

        $this->assertSame(['801 Cottage Dr', 'Orthopaedic and Spine Hospital', 'Specialty Clinic'], $fields['storefrontAddress']['addressLines']);
        $this->assertSame('UAMS Health - Kendall M. Masada, M.D. - Specialty Clinic in the Orthopaedic and Spine Hospital', $fields['title'], 'the title keeps the full child name');
    }

    public function test_the_provider_table_shortens_only_the_child_address_line(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-gmb-settings-page.php');

        $start = strpos($source, 'function uamswp_fad_gmb_provider_table(');
        $end = strpos($source, "\nfunction ", $start + 1);
        $table = substr($source, $start, $end === false ? null : $end - $start);

        $this->assertStringContainsString('$location_addresses[] = UAMSWP_FAD_GBP_Logic::child_name_without_parent($location_title, get_the_title($location_parent_location));', $table);
        $this->assertSame(
            1,
            preg_match('/if \(! \$location_has_parent\) \{\s*\$location_addresses\[\] = \$location_title;\s*\} else \{/', $table),
            'the name with no parent keeps its own name'
        );
        $this->assertStringContainsString('provider_listing_title(html_entity_decode($full_name), html_entity_decode(get_the_title($location)))', $table, 'the title is built from the full child name');
    }
}
