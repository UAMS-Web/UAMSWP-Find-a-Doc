<?php

/**
 * A provider's Business Profile listing is titled with the provider and the location it is for.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpProviderTitleTest extends UnitTestCase
{
    private const MASADA = 'Kendall M. Masada, M.D.';

    private function provider_table_source(): string
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-gmb-settings-page.php');

        $start = strpos($source, 'function uamswp_fad_gmb_provider_table(');
        $this->assertNotFalse($start, 'the provider table builder exists');

        $end = strpos($source, "\nfunction ", $start + 1);

        return substr($source, $start, $end === false ? null : $end - $start);
    }

    public function test_the_title_names_the_provider_and_the_location(): void
    {
        $this->assertSame(
            'UAMS Health - Kendall M. Masada, M.D. - Specialty Clinic in the Orthopaedic and Spine Hospital',
            UAMSWP_FAD_GBP_Logic::provider_listing_title(self::MASADA, 'Specialty Clinic in the Orthopaedic and Spine Hospital')
        );
    }

    public function test_a_provider_at_two_locations_gets_two_different_titles(): void
    {
        $first = UAMSWP_FAD_GBP_Logic::provider_listing_title(self::MASADA, 'Specialty Clinic in the Orthopaedic and Spine Hospital');
        $second = UAMSWP_FAD_GBP_Logic::provider_listing_title(self::MASADA, 'Orthopaedic and Spine Clinic - Little Rock');

        $this->assertNotSame($first, $second);
        $this->assertStringStartsWith('UAMS Health - Kendall M. Masada, M.D. - ', $second);
    }

    public function test_surrounding_space_is_trimmed(): void
    {
        $this->assertSame(
            'UAMS Health - Pat Lee, M.D. - Clinic',
            UAMSWP_FAD_GBP_Logic::provider_listing_title(' Pat Lee, M.D. ', ' Clinic ')
        );
    }

    public function test_a_blank_location_name_leaves_no_trailing_separator(): void
    {
        $this->assertSame('UAMS Health - Pat Lee, M.D.', UAMSWP_FAD_GBP_Logic::provider_listing_title('Pat Lee, M.D.', ''));
        $this->assertSame('UAMS Health - Pat Lee, M.D.', UAMSWP_FAD_GBP_Logic::provider_listing_title('Pat Lee, M.D.', '   '));
    }

    public function test_the_row_title_reaches_the_listing_title_field(): void
    {
        $row = [1 => UAMSWP_FAD_GBP_Logic::provider_listing_title(self::MASADA, 'Specialty Clinic in the Orthopaedic and Spine Hospital')];

        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($row, []);

        $this->assertSame('UAMS Health - Kendall M. Masada, M.D. - Specialty Clinic in the Orthopaedic and Spine Hospital', $fields['title']);
    }

    public function test_the_provider_table_titles_each_row_from_the_location_in_its_store_code(): void
    {
        $source = $this->provider_table_source();

        $this->assertStringContainsString('$row[1] = UAMSWP_FAD_GBP_Logic::provider_listing_title(', $source);
        $this->assertStringContainsString('html_entity_decode(get_the_title(uamswp_fad_int($location)))', $source);
        $this->assertStringContainsString('$store_code = $profile_slug.\'_\'.$location_slug;', $source);
        $this->assertStringNotContainsString("'UAMS Health - '.html_entity_decode(\$full_name)", $source, 'the provider-only title is gone');
    }
}
