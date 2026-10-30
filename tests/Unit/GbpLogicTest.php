<?php

/**
 * Unit tests for the Business Profile sync's pure decisions.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpLogicTest extends UnitTestCase
{
    private const SILOS = [
        'uams' => ['brand' => null],
        'arkansas-childrens' => ['brand' => 'arkansas-childrens'],
        'baptist-health' => ['brand' => 'baptist-health'],
    ];

    private const CATEGORIES = [
        'Doctor' => 'categories/gcid:physician',
        'Dentist' => 'categories/gcid:dentist',
    ];

    /**
     * @return array<string, array{0: mixed, 1: mixed, 2: string|null}>
     */
    public function silo_cases(): array
    {
        return [
            'query zero is the default brand' => ['0', '', 'uams'],
            'query never saved is the default brand' => ['', '', 'uams'],
            'a stale org with query zero is still the default' => ['0', 'baptist-health', 'uams'],
            'baptist' => ['1', 'baptist-health', 'baptist-health'],
            'integer query one' => [1, 'arkansas-childrens', 'arkansas-childrens'],
            'a third-party brand with no silo is denied' => ['1', 'dhs', null],
            'replacement chosen but no brand picked is denied' => ['1', '', null],
        ];
    }

    /**
     * @dataProvider silo_cases
     *
     * @param  mixed  $query
     * @param  mixed  $org
     */
    public function test_resolve_silo($query, $org, ?string $expected): void
    {
        $this->assertSame($expected, UAMSWP_FAD_GBP_Logic::resolve_silo($query, $org, self::SILOS));
    }

    /**
     * @return array<string, array{0: string, 1: bool, 2: string}>
     */
    public function mode_cases(): array
    {
        return [
            'live in production stays live' => ['live', true, 'live'],
            'live off production is capped at dry-run' => ['live', false, 'dry-run'],
            'dry-run is never raised' => ['dry-run', true, 'dry-run'],
            'off stays off on production' => ['off', true, 'off'],
            'off stays off elsewhere' => ['off', false, 'off'],
            'an unknown mode is off' => ['enabled', true, 'off'],
            'an empty mode is off' => ['', true, 'off'],
        ];
    }

    /**
     * @dataProvider mode_cases
     */
    public function test_effective_mode(string $configured, bool $production, string $expected): void
    {
        $this->assertSame($expected, UAMSWP_FAD_GBP_Logic::effective_mode($configured, $production));
    }

    public function test_split_provider_store_code(): void
    {
        $this->assertSame(['ryan-k-dare', 'infectious-diseases-clinic'], UAMSWP_FAD_GBP_Logic::split_provider_store_code('ryan-k-dare_infectious-diseases-clinic'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code('no-separator'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code('a_b_c'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code('_leading'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code(''));
    }

    /**
     * @return array<int, mixed>
     */
    private function row(): array
    {
        return [
            0 => 'jane-doe_clinic',
            1 => 'UAMS Health - Jane Doe &amp; Co',
            2 => '4301 W Markham St',
            3 => 'Suite 100',
            7 => 'Little Rock',
            8 => 'AR',
            9 => 'US',
            10 => '72205',
            11 => '34.7465',
            12 => '-92.3196',
            13 => '501-686-6067',
            14 => 'https://uamshealth.com/provider/jane-doe/',
            15 => 'Doctor',
            16 => 'Dentist, Doctor, Unmapped',
            17 => 'A short bio.',
        ];
    }

    public function test_fields_from_row_maps_every_owned_field(): void
    {
        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($this->row(), self::CATEGORIES);

        $this->assertSame('UAMS Health - Jane Doe & Co', $fields['title']);
        $this->assertSame(['4301 W Markham St', 'Suite 100'], $fields['storefrontAddress']['addressLines']);
        $this->assertSame('72205', $fields['storefrontAddress']['postalCode']);
        $this->assertSame(['latitude' => 34.7465, 'longitude' => -92.3196], $fields['latlng']);
        $this->assertSame(['primaryPhone' => '501-686-6067'], $fields['phoneNumbers']);
        $this->assertSame('https://uamshealth.com/provider/jane-doe/', $fields['websiteUri']);
        $this->assertSame(['description' => 'A short bio.'], $fields['profile']);
        $this->assertEqualsCanonicalizing(UAMSWP_FAD_GBP_Logic::OWNED_FIELDS, array_keys($fields));
    }

    public function test_categories_exclude_the_primary_and_unmapped_names(): void
    {
        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($this->row(), self::CATEGORIES);

        $this->assertSame('categories/gcid:physician', $fields['categories']['primaryCategory']['name']);
        $this->assertSame([['name' => 'categories/gcid:dentist']], $fields['categories']['additionalCategories']);
    }

    public function test_an_unmapped_primary_category_leaves_categories_out(): void
    {
        $row = $this->row();
        $row[15] = 'Not A Google Category';

        $this->assertArrayNotHasKey('categories', UAMSWP_FAD_GBP_Logic::fields_from_row($row, self::CATEGORIES));
    }

    public function test_category_names_match_regardless_of_case(): void
    {
        $row = $this->row();
        $row[15] = 'ORTHOPEDIC SURGEON';
        $row[16] = 'surgeon, dentist';

        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row($row, ['Orthopedic surgeon' => 'categories/gcid:orthopedic_surgeon', 'Surgeon' => 'categories/gcid:surgeon', 'Dentist' => 'categories/gcid:dentist']);

        $this->assertSame('categories/gcid:orthopedic_surgeon', $fields['categories']['primaryCategory']['name']);
        $this->assertSame([['name' => 'categories/gcid:surgeon'], ['name' => 'categories/gcid:dentist']], $fields['categories']['additionalCategories']);
    }

    public function test_unresolved_category_names_are_reported(): void
    {
        $row = $this->row();
        $row[15] = 'Not A Google Category';
        $row[16] = 'Dentist, Also Missing, Doctor';

        $this->assertSame(['Not A Google Category', 'Also Missing'], UAMSWP_FAD_GBP_Logic::unresolved_categories($row, self::CATEGORIES));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::unresolved_categories($this->row(), self::CATEGORIES + ['Unmapped' => 'categories/gcid:x']));
    }

    public function test_an_incomplete_address_is_left_out(): void
    {
        $row = $this->row();
        $row[10] = '';

        $this->assertArrayNotHasKey('storefrontAddress', UAMSWP_FAD_GBP_Logic::fields_from_row($row, self::CATEGORIES));
    }

    public function test_blank_cells_are_never_written(): void
    {
        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row([0 => 'x_y', 1 => 'Only A Name'], self::CATEGORIES);

        $this->assertSame(['title'], array_keys($fields));
    }

    /**
     * @return array<string, mixed>
     */
    private function live(): array
    {
        return [
            'title' => 'UAMS Health - Jane Doe & Co',
            'storefrontAddress' => ['postalCode' => '72205', 'administrativeArea' => 'AR', 'locality' => 'Little Rock', 'addressLines' => ['4301 W Markham St', 'Suite 100']],
            'latlng' => ['latitude' => 34.746500001, 'longitude' => -92.3196],
            'phoneNumbers' => ['primaryPhone' => '(501) 686-6067'],
            'websiteUri' => 'https://uamshealth.com/provider/jane-doe/',
            'categories' => ['primaryCategory' => ['name' => 'categories/gcid:physician'], 'additionalCategories' => [['name' => 'categories/gcid:dentist']]],
            'profile' => ['description' => 'A short bio.'],
        ];
    }

    public function test_formatting_differences_are_not_changes(): void
    {
        $desired = UAMSWP_FAD_GBP_Logic::fields_from_row($this->row(), self::CATEGORIES);

        $this->assertSame([], UAMSWP_FAD_GBP_Logic::changed_fields($desired, $this->live()));
    }

    public function test_only_the_differing_field_is_reported(): void
    {
        $live = $this->live();
        $live['phoneNumbers'] = ['primaryPhone' => '501-555-0100'];
        $desired = UAMSWP_FAD_GBP_Logic::fields_from_row($this->row(), self::CATEGORIES);

        $changed = UAMSWP_FAD_GBP_Logic::changed_fields($desired, $live);

        $this->assertSame(['phoneNumbers'], array_keys($changed));
        $this->assertSame('phoneNumbers', UAMSWP_FAD_GBP_Logic::update_mask($changed));
    }

    public function test_a_field_missing_from_the_live_listing_is_a_change(): void
    {
        $live = $this->live();
        unset($live['websiteUri'], $live['profile']);
        $desired = UAMSWP_FAD_GBP_Logic::fields_from_row($this->row(), self::CATEGORIES);

        $this->assertSame('websiteUri,profile', UAMSWP_FAD_GBP_Logic::update_mask(UAMSWP_FAD_GBP_Logic::changed_fields($desired, $live)));
    }

    public function test_additional_category_order_is_not_a_change(): void
    {
        $live = $this->live();
        $live['categories']['additionalCategories'] = [['name' => 'categories/gcid:dentist']];
        $desired = ['categories' => ['primaryCategory' => ['name' => 'categories/gcid:physician'], 'additionalCategories' => [['name' => 'categories/gcid:dentist']]]];

        $this->assertSame([], UAMSWP_FAD_GBP_Logic::changed_fields($desired, $live));
    }

    public function test_a_field_the_row_does_not_carry_is_never_touched(): void
    {
        $live = $this->live();
        $live['title'] = 'Something a co-owner typed';

        $this->assertSame([], UAMSWP_FAD_GBP_Logic::changed_fields(['websiteUri' => $live['websiteUri']], $live));
    }

    public function test_the_mask_never_names_a_field_the_sync_does_not_own(): void
    {
        $this->assertSame('title,websiteUri', UAMSWP_FAD_GBP_Logic::update_mask(['websiteUri' => 'x', 'regularHours' => ['y'], 'title' => 'z']));
    }
}
