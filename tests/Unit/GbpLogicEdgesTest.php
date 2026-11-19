<?php

/**
 * Unit tests for the edges of the Business Profile sync's pure decisions (UAMSWP_FAD_GBP_Logic).
 *
 * Each test pins one boundary the other Logic tests leave open: a column read from the right place, a value that
 * normalizes to the same thing, a default for a missing key, a limit, or the shape of a returned list.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UAMSWP_FAD_GBP_Logic;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpLogicEdgesTest extends UnitTestCase
{
    private const CATEGORIES = ['Doctor' => 'categories/gcid:doctor', 'Dentist' => 'categories/gcid:dentist'];

    /**
     * A table row with a distinct value in every column the fields read.
     *
     * @param  array<int, string>  $changes  Column => value to put in its place.
     * @return array<int, string>
     */
    private static function row(array $changes = []): array
    {
        $row = [
            0 => 'main-clinic',
            1 => ' Main &amp; Clinic ',
            2 => 'L2',
            3 => 'L3',
            4 => 'L4',
            5 => 'L5',
            6 => 'L6',
            7 => 'Little Rock',
            8 => 'AR',
            9 => 'CA',
            10 => '72205',
            11 => '34.7',
            12 => '-92.3',
            13 => '501-555-1234',
            14 => 'https://x.test',
            15 => 'Doctor',
            16 => 'Dentist, Doctor, Unknown',
            17 => 'About us',
        ];

        foreach ($changes as $column => $value) {
            $row[$column] = $value;
        }

        return $row;
    }

    // Silos, titles and store codes.

    public function test_a_silo_named_by_a_number_is_returned_as_a_string(): void
    {
        $this->assertSame('5', UAMSWP_FAD_GBP_Logic::resolve_silo('1', 'five', [5 => ['brand' => 'five']]));
    }

    public function test_a_listing_title_and_a_location_name_accept_numbers(): void
    {
        $this->assertSame('UAMS Health - 123 - 456', UAMSWP_FAD_GBP_Logic::provider_listing_title(123, 456));
        $this->assertSame('42', UAMSWP_FAD_GBP_Logic::location_name_without_place(42));
    }

    public function test_a_store_code_with_an_empty_location_part_or_given_as_a_number_is_malformed(): void
    {
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code('jane_'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code(5));
    }

    // Fields from a table row.

    public function test_every_field_is_read_from_its_own_column(): void
    {
        $this->assertSame([
            'title' => 'Main & Clinic',
            'storefrontAddress' => [
                'regionCode' => 'CA',
                'languageCode' => 'en',
                'postalCode' => '72205',
                'administrativeArea' => 'AR',
                'locality' => 'Little Rock',
                'addressLines' => ['L2', 'L3', 'L4', 'L5', 'L6'],
            ],
            'latlng' => ['latitude' => 34.7, 'longitude' => -92.3],
            'phoneNumbers' => ['primaryPhone' => '501-555-1234'],
            'websiteUri' => 'https://x.test',
            'categories' => ['primaryCategory' => ['name' => 'categories/gcid:doctor'], 'additionalCategories' => [['name' => 'categories/gcid:dentist']]],
            'profile' => ['description' => 'About us'],
        ], UAMSWP_FAD_GBP_Logic::fields_from_row(self::row(), self::CATEGORIES));
    }

    public function test_a_row_with_no_region_is_in_the_us(): void
    {
        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row(self::row([9 => '']), self::CATEGORIES);

        $this->assertSame('US', $fields['storefrontAddress']['regionCode']);
    }

    /**
     * @return array<string, array{0: array<int, string>, 1: string}>
     */
    public static function rows_missing_a_part(): array
    {
        return [
            'no address lines' => [[2 => '', 3 => '', 4 => '', 5 => '', 6 => ''], 'storefrontAddress'],
            'no city' => [[7 => ''], 'storefrontAddress'],
            'no state' => [[8 => ''], 'storefrontAddress'],
            'no postal code' => [[10 => ''], 'storefrontAddress'],
            'no latitude' => [[11 => 'north'], 'latlng'],
            'no longitude' => [[12 => 'west'], 'latlng'],
            'no phone' => [[13 => ''], 'phoneNumbers'],
            'no website' => [[14 => ''], 'websiteUri'],
            'no description' => [[17 => ''], 'profile'],
            'no title' => [[1 => ''], 'title'],
        ];
    }

    /**
     * @param  array<int, string>  $changes
     */
    #[DataProvider('rows_missing_a_part')]
    public function test_a_row_missing_a_part_leaves_its_field_out(array $changes, string $field): void
    {
        $fields = UAMSWP_FAD_GBP_Logic::fields_from_row(self::row($changes), self::CATEGORIES);

        $this->assertArrayNotHasKey($field, $fields);
        $this->assertCount(6, $fields, 'only that field is left out');
    }

    public function test_the_unresolved_categories_are_decoded_and_a_row_without_them_has_none(): void
    {
        $this->assertSame(['Ear & Nose'], UAMSWP_FAD_GBP_Logic::unresolved_categories([15 => 'Ear &amp; Nose'], self::CATEGORIES));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::unresolved_categories([], self::CATEGORIES));
    }

    // Which desired fields differ from the live listing.

    /**
     * Whether a field counts as changed.
     */
    private static function changed(string $field, mixed $desired, mixed $live): bool
    {
        return UAMSWP_FAD_GBP_Logic::changed_fields([$field => $desired], $live === null ? [] : [$field => $live]) !== [];
    }

    /**
     * @return array<string, array{0: string, 1: mixed, 2: mixed, 3: bool}>
     */
    public static function comparisons(): array
    {
        $address = ['postalCode' => '72205', 'administrativeArea' => 'AR', 'locality' => 'Little Rock', 'addressLines' => ['L1']];
        $categories = ['primaryCategory' => ['name' => 'p'], 'additionalCategories' => [['name' => 'a'], ['name' => 'b']]];

        return [
            'coordinates equal to five places' => ['latlng', ['latitude' => 34.123456, 'longitude' => -92.0], ['latitude' => 34.123459, 'longitude' => -92.0], false],
            'a latitude different at the fifth place' => ['latlng', ['latitude' => 34.12341, 'longitude' => -92.0], ['latitude' => 34.12344, 'longitude' => -92.0], true],
            'a longitude different at the fifth place' => ['latlng', ['latitude' => 34.0, 'longitude' => -92.12341], ['latitude' => 34.0, 'longitude' => -92.12344], true],
            'longitudes equal to five places' => ['latlng', ['latitude' => 34.0, 'longitude' => -92.123456], ['latitude' => 34.0, 'longitude' => -92.123459], false],
            'a different latitude only' => ['latlng', ['latitude' => 35.0, 'longitude' => -92.0], ['latitude' => 34.0, 'longitude' => -92.0], true],
            'a missing live latitude reads as 0' => ['latlng', ['latitude' => 0.0, 'longitude' => 5.0], ['longitude' => 5.0], false],
            'a missing live longitude reads as 0' => ['latlng', ['latitude' => 5.0, 'longitude' => 0.0], ['latitude' => 5.0], false],
            'a different postal code' => ['storefrontAddress', ['postalCode' => '72206'] + $address, $address, true],
            'a different state' => ['storefrontAddress', ['administrativeArea' => 'TX'] + $address, $address, true],
            'a different city' => ['storefrontAddress', ['locality' => 'Conway'] + $address, $address, true],
            'different address lines' => ['storefrontAddress', ['addressLines' => ['L9']] + $address, $address, true],
            'address parts with spaces around them' => ['storefrontAddress', ['postalCode' => ' 72205 ', 'administrativeArea' => ' AR ', 'locality' => ' Little Rock ', 'addressLines' => [' L1 ']], $address, false],
            'address parts missing on one side and empty on the other' => ['storefrontAddress', ['addressLines' => ['L1']], ['postalCode' => '', 'administrativeArea' => '', 'locality' => '', 'addressLines' => ['L1']], false],
            'one address line given as text' => ['storefrontAddress', ['addressLines' => 'L1'] + $address, $address, false],
            'address lines under other keys' => ['storefrontAddress', ['addressLines' => [3 => 'L1']] + $address, $address, false],
            'an address line given as a number' => ['storefrontAddress', ['addressLines' => [5]] + $address, ['addressLines' => ['5']] + $address, false],
            'a different additional category' => ['categories', ['additionalCategories' => [['name' => 'c'], ['name' => 'b']]] + $categories, $categories, true],
            'additional categories in another order' => ['categories', ['additionalCategories' => [['name' => 'b'], ['name' => 'a']]] + $categories, $categories, false],
            'additional categories given as text' => ['categories', ['primaryCategory' => ['name' => 'p'], 'additionalCategories' => 'x'], ['primaryCategory' => ['name' => 'p'], 'additionalCategories' => [['name' => '']]], false],
            'an additional category with no name' => ['categories', ['primaryCategory' => ['name' => 'p'], 'additionalCategories' => [[]]], ['primaryCategory' => ['name' => 'p'], 'additionalCategories' => [['name' => '']]], false],
            'a different primary category' => ['categories', ['primaryCategory' => ['name' => 'q']] + $categories, $categories, true],
            'no primary category on one side and an empty one on the other' => ['categories', ['additionalCategories' => []], ['primaryCategory' => ['name' => ''], 'additionalCategories' => []], false],
            'a different description' => ['profile', ['description' => 'New'], ['description' => 'Old'], true],
            'a description with spaces around it' => ['profile', ['description' => ' Old '], ['description' => 'Old'], false],
            'no description on one side and an empty one on the other' => ['profile', [], ['description' => ''], false],
            'a title with spaces around it' => ['title', ' Main ', 'Main', false],
            'a title given as a number' => ['title', 5, 5, false],
            'a phone missing on one side and empty on the other' => ['phoneNumbers', [], ['primaryPhone' => ''], false],
        ];
    }

    #[DataProvider('comparisons')]
    public function test_a_field_is_changed_only_when_its_normalized_values_differ(string $field, mixed $desired, mixed $live, bool $changed): void
    {
        $this->assertSame($changed, self::changed($field, $desired, $live));
    }

    public function test_a_fingerprint_of_items_that_cannot_be_encoded_is_the_hash_of_nothing(): void
    {
        $this->assertSame(sha1(''), UAMSWP_FAD_GBP_Logic::fingerprint([["\xB1\x31"]], [], true, '1'));
    }

    // Photos.

    public function test_the_logo_and_cover_are_trimmed_and_a_missing_column_is_no_photo(): void
    {
        $this->assertSame([['category' => 'LOGO', 'url' => 'https://x.test/logo.jpg']], UAMSWP_FAD_GBP_Logic::photos_from_row([18 => ' https://x.test/logo.jpg ']));
        $this->assertSame([['category' => 'COVER', 'url' => 'https://x.test/cover.jpg']], UAMSWP_FAD_GBP_Logic::photos_from_row([19 => 'https://x.test/cover.jpg']));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::photos_from_row([]));
    }

    public function test_the_other_photos_are_trimmed(): void
    {
        $this->assertSame([
            ['category' => 'ADDITIONAL', 'url' => 'https://x.test/a.jpg'],
            ['category' => 'ADDITIONAL', 'url' => 'https://x.test/b.jpg'],
        ], UAMSWP_FAD_GBP_Logic::photos_from_row([20 => ' https://x.test/a.jpg , https://x.test/b.jpg ']));
    }

    // Closing and orphans.

    public function test_a_temporarily_closed_listing_counts_as_closed(): void
    {
        $this->assertTrue(UAMSWP_FAD_GBP_Logic::is_closed(['openInfo' => ['status' => 'CLOSED_TEMPORARILY']]));
        $this->assertFalse(UAMSWP_FAD_GBP_Logic::is_closed(['openInfo' => ['status' => 'OPEN']]));
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: int, 3: bool}>
     */
    public static function orphan_ages(): array
    {
        return [
            'fourteen days' => ['2026-11-05', '2026-11-19', 0, true],
            'fifteen days' => ['2026-11-04', '2026-11-19', 0, true],
            'thirteen days' => ['2026-11-06', '2026-11-19', 1, false],
            'a date after today' => ['2026-11-20', '2026-11-19', 14, false],
            'a today that does not parse, with a date long ago' => ['1960-01-01', 'not a date', 14, false],
            'a date that does not parse' => ['not a date', '2026-11-19', 14, false],
        ];
    }

    #[DataProvider('orphan_ages')]
    public function test_an_orphan_counts_whole_days_in_utc(string $since, string $today, int $days_left, bool $due): void
    {
        $previous = date_default_timezone_get();

        // A zone east of UTC moves a local midnight to the day before, and one west of it to later the same day.
        foreach (['Asia/Tokyo', 'America/Chicago'] as $zone) {
            date_default_timezone_set($zone);

            try {
                $report = UAMSWP_FAD_GBP_Logic::orphan_report([['name' => 'locations/1', 'cause' => 'x', 'since' => $since, 'label' => 'x']], $today);
            } finally {
                date_default_timezone_set($previous);
            }

            $this->assertSame([$days_left, $due], [$report[0]['days_left'], $report[0]['due']], $zone);
        }
    }

    public function test_a_remembered_listing_keeps_photos_stored_as_text_as_a_list(): void
    {
        $this->assertSame([55 => ['id' => 'locations/1', 'orphaned_since' => null, 'photos' => ['x']]], UAMSWP_FAD_GBP_Logic::remember_listing([55 => ['id' => 'locations/1', 'orphaned_since' => null, 'photos' => 'x']], 55, 'locations/1'));
    }

    public function test_a_map_key_that_reads_as_location_0_is_kept_current_by_location_0(): void
    {
        $this->assertSame(['x' => ['id' => 'locations/1', 'orphaned_since' => null]], UAMSWP_FAD_GBP_Logic::mark_orphans(['x' => ['id' => 'locations/1', 'orphaned_since' => '2026-11-01']], [0], '2026-11-19'));
    }

    // CIDs.

    public function test_cids_are_listed_once_in_order_from_every_kind_of_value(): void
    {
        $this->assertSame(['1111111111', '2222222222'], UAMSWP_FAD_GBP_Logic::cids_from_value([['1111111111'], '1111111111 1111111111 2222222222']));
        $this->assertSame(['12345678901'], UAMSWP_FAD_GBP_Logic::cids_from_value(12345678901));
        $this->assertSame(['12345678901'], UAMSWP_FAD_GBP_Logic::cids_from_value(12345678901.0));
        $this->assertSame([], UAMSWP_FAD_GBP_Logic::cids_from_value(new \stdClass));
    }

    // The backfill plan.

    /**
     * @param  array<string, mixed>  $listing
     * @return array{account: string, listing: array<string, mixed>}
     */
    private static function listing(array $listing): array
    {
        return ['account' => 'accounts/up', 'listing' => $listing];
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private static function item(array $extra = []): array
    {
        return $extra + ['store_code' => 'main-clinic', 'kind' => 'location', 'post_id' => 55, 'location_id' => 55, 'stored_name' => ''];
    }

    public function test_a_listing_with_no_name_is_matched_by_its_store_code_as_an_empty_name_and_nothing_is_already_stored(): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan([self::item()], [self::listing(['storeCode' => 'main-clinic'])], []);

        $this->assertSame(['already' => [], 'by_store_code' => [['item' => self::item(), 'name' => '']], 'by_cid' => [], 'unmatched' => []], $plan);
    }

    public function test_a_listing_with_no_store_code_or_an_empty_one_matches_no_store_code(): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan([self::item(['store_code' => ''])], [self::listing(['name' => 'locations/1']), self::listing(['name' => 'locations/2', 'storeCode' => ''])], []);

        $this->assertSame(['already' => [], 'by_store_code' => [], 'by_cid' => [], 'unmatched' => [self::item(['store_code' => ''])]], $plan);
    }

    public function test_a_stored_name_no_listing_has_is_not_already_stored(): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan([self::item(['stored_name' => 'locations/9', 'store_code' => 'other'])], [self::listing(['name' => 'locations/1', 'storeCode' => 'main-clinic'])], []);

        $this->assertSame([], $plan['already']);
    }

    /**
     * @return array<string, array{0: array<string, mixed>}>
     */
    public static function items_with_no_post_or_location(): array
    {
        return [
            'post 0' => [['post_id' => 0]],
            'a provider at location 0' => [['kind' => 'provider', 'location_id' => 0]],
        ];
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    #[DataProvider('items_with_no_post_or_location')]
    public function test_an_item_with_no_post_or_location_is_unmatched(array $extra): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan([self::item($extra)], [self::listing(['name' => 'locations/1', 'storeCode' => 'main-clinic'])], []);

        $this->assertSame([self::item($extra)], $plan['unmatched']);
    }

    public function test_a_cid_does_not_match_an_item_whose_store_code_is_on_several_listings(): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan(
            [self::item()],
            [
                self::listing(['name' => 'locations/1', 'storeCode' => 'main-clinic']),
                self::listing(['name' => 'locations/2', 'storeCode' => 'main-clinic', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=1111111111']]),
            ],
            [55 => ['1111111111']]
        );

        $this->assertSame([[], [self::item()]], [$plan['by_cid'], $plan['unmatched']]);
    }

    public function test_two_cids_on_one_listing_match_it_once(): void
    {
        $plan = UAMSWP_FAD_GBP_Logic::backfill_plan(
            [self::item(['store_code' => 'gone'])],
            [
                self::listing(['name' => 'locations/1', 'storeCode' => 'main-clinic', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=1111111111']]),
                self::listing(['name' => 'locations/1', 'storeCode' => 'main-clinic', 'metadata' => ['mapsUri' => 'https://maps.google.com/?cid=2222222222']]),
            ],
            [55 => ['1111111111', '2222222222']]
        );

        $this->assertSame([['item' => self::item(['store_code' => 'gone']), 'name' => 'locations/1']], $plan['by_cid']);
    }

    // The CID report.

    public function test_a_listing_with_no_cid_is_named_by_its_store_code_or_its_name(): void
    {
        $report = UAMSWP_FAD_GBP_Logic::cid_report([], [], [self::listing(['name' => 'locations/1']), self::listing([])]);

        $this->assertSame(['locations/1', ''], $report['listings_without_cid']);
    }

    // The attention list and the log.

    public function test_an_entry_with_no_store_code_is_kept_under_its_reason(): void
    {
        $this->assertSame('~HTTP 500', UAMSWP_FAD_GBP_Logic::attention_key(['reason' => 'HTTP 500']));
        $this->assertSame('~', UAMSWP_FAD_GBP_Logic::attention_key([]));
    }

    public function test_numeric_store_codes_keep_their_keys_in_the_attention_list(): void
    {
        $attention = UAMSWP_FAD_GBP_Logic::next_attention([], ['store_code' => '101', 'action' => 'error'], 10);
        $attention = UAMSWP_FAD_GBP_Logic::next_attention($attention, ['store_code' => '102', 'action' => 'review'], 10);

        $this->assertSame([102, 101], array_keys($attention));
    }

    public function test_filtered_entries_are_a_list_and_an_empty_filter_matches_entries_without_the_key(): void
    {
        $entries = [['action' => 'update', 'silo' => 'uams'], ['silo' => 'baptist-health'], ['action' => 'error']];

        $this->assertSame([['silo' => 'baptist-health']], UAMSWP_FAD_GBP_Logic::filter_entries($entries, '', null));
        $this->assertSame([['action' => 'error']], UAMSWP_FAD_GBP_Logic::filter_entries($entries, null, ''));
    }

    // The create history.

    public function test_the_history_keeps_the_latest_fourteen_days_by_default(): void
    {
        $history = [];
        foreach (range(1, 15) as $day) {
            $history[sprintf('2026-11-%02d', $day)] = 1;
        }

        $kept = UAMSWP_FAD_GBP_Logic::add_to_history($history, '2026-11-16', 1);

        $this->assertSame(array_map(fn (int $day): string => sprintf('2026-11-%02d', $day), range(3, 16)), array_keys($kept));
    }

    public function test_the_history_reads_counts_as_numbers_never_below_zero(): void
    {
        $this->assertSame(['2026-11-01' => 3, '2026-11-02' => 0, '2026-11-03' => 0, '2026-11-04' => 0], UAMSWP_FAD_GBP_Logic::add_to_history(['2026-11-01' => '3', '2026-11-02' => -2, '2026-11-03' => 0], '2026-11-04', 0));
        $this->assertSame(['2026-11-04' => 0], UAMSWP_FAD_GBP_Logic::add_to_history([], '2026-11-04', -3));
    }

    public function test_the_history_is_sorted_before_the_oldest_days_are_dropped(): void
    {
        $this->assertSame(['2026-11-02' => 1, '2026-11-03' => 1], UAMSWP_FAD_GBP_Logic::add_to_history(['2026-11-03' => 1, '2026-11-01' => 1], '2026-11-02', 1, 2));
    }

    public function test_a_history_kept_for_no_days_still_keeps_one(): void
    {
        $this->assertSame(['2026-11-02' => 1], UAMSWP_FAD_GBP_Logic::add_to_history(['2026-11-01' => 1], '2026-11-02', 1, 0));
    }

    // The dashboard.

    public function test_the_dashboard_status_while_running(): void
    {
        $this->assertSame(['key' => 'running', 'label' => 'Running'], UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], true, 0, 0, 100));
    }

    public function test_a_pause_or_backoff_ending_now_is_over(): void
    {
        $this->assertSame('idle', UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, 100, 0, 100)['key']);
        $this->assertSame('idle', UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, 0, 100, 100)['key']);
    }

    public function test_the_backoff_label_names_the_end_and_the_time_left(): void
    {
        $now = 1_700_000_000;

        $this->assertSame(
            ['key' => 'backoff', 'label' => 'Backed off until '.UAMSWP_FAD_GBP_Logic::reset_label($now + 600).' (rate limit), 10 min left'],
            UAMSWP_FAD_GBP_Logic::dashboard_status(['uams' => 'live'], false, 0, $now + 600, $now)
        );
    }

    /**
     * @return array<string, array{0: int, 1: string}>
     */
    public static function remaining_times(): array
    {
        return [
            'under a minute' => [59, 'under 1 min'],
            'a minute' => [60, '1 min'],
            'fifty-nine minutes' => [59 * 60, '59 min'],
            'an hour' => [3600, '1 h 0 min'],
            'two hours and a minute' => [7260, '2 h 1 min'],
            'a time already past' => [-120, 'under 1 min'],
        ];
    }

    #[DataProvider('remaining_times')]
    public function test_the_remaining_label(int $seconds, string $label): void
    {
        $this->assertSame($label, UAMSWP_FAD_GBP_Logic::remaining_label($seconds));
    }

    public function test_a_pause_ending_now_leaves_nothing_waiting(): void
    {
        $summary = UAMSWP_FAD_GBP_Logic::queue_summary([100], 100, 100);

        $this->assertSame([1, 0], [$summary['due'], $summary['waiting']]);
    }

    public function test_the_outcome_counts_cover_exactly_the_last_day(): void
    {
        $now = 1_700_000_000;
        $log = [
            ['time' => $now - 86401, 'action' => 'error', 'reason' => 'old'],
            ['time' => $now - 86400, 'action' => 'error', 'reason' => 'a day ago'],
            ['time' => $now, 'action' => 'review', 'reason' => 'now'],
            ['time' => $now, 'action' => 'create', 'reason' => 'Failed'],
            ['time' => $now, 'action' => 'update', 'reason' => 'Photos sent'],
        ];

        $this->assertSame(['create' => 0, 'update' => 0, 'review' => 1, 'error' => 1], UAMSWP_FAD_GBP_Logic::outcome_counts($log, $now));
    }

    // A location's item and attributes.

    public function test_a_row_with_only_a_store_code_is_still_an_item(): void
    {
        $this->assertSame('main-clinic', UAMSWP_FAD_GBP_Logic::location_item(['main-clinic'], [], null)['store_code'] ?? null);
    }

    public function test_attribute_answers_are_read_without_spaces_or_case(): void
    {
        $this->assertSame(['has_restroom' => true, 'requires_appointments' => false], UAMSWP_FAD_GBP_Logic::attributes_from_row([25 => ' Yes ', 26 => ' NO ']));
    }
}
