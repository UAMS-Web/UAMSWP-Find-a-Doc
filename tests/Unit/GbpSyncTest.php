<?php

/**
 * Unit tests for the Business Profile sync's update, create and skip decisions.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use RuntimeException;
use UAMSWP_FAD_GBP_Sync;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpSyncTest extends UnitTestCase
{
    private const SILOS = [
        'uams' => ['brand' => null, 'accounts' => ['provider' => 'accounts/up', 'location' => 'accounts/ul']],
        'baptist-health' => ['brand' => 'baptist-health', 'accounts' => ['provider' => 'accounts/bp', 'location' => 'accounts/bl']],
    ];

    /** @var array<int, array<string, mixed>> */
    private $log = [];

    /** @var object */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->log = [];
        $this->client = new class
        {
            /** @var array<int, array<string, mixed>> */
            public $calls = [];

            /** @var bool */
            public $fail = false;

            /** @var bool */
            public $limited = false;

            /** @var ?RuntimeException */
            public $fail_create;

            /** @var array<string, bool> */
            public $live_attributes = [];

            /** @var ?RuntimeException */
            public $fail_attributes;

            public function attributes(string $location): array
            {
                $this->calls[] = ['attributes', $location];

                return $this->live_attributes;
            }

            public function update_attributes(string $location, array $values): array
            {
                $this->calls[] = ['update_attributes', $location, $values];
                if ($this->fail_attributes !== null) {
                    throw $this->fail_attributes;
                }

                return [];
            }

            /** @var ?RuntimeException */
            public $fail_photo;

            public function upload_photo(string $account, string $location, string $category, string $url): array
            {
                $this->calls[] = ['upload_photo', $account, $location, $category, $url];
                if ($this->fail_photo !== null && $url === 'https://x/fail.jpg') {
                    throw $this->fail_photo;
                }

                return [];
            }

            public function patch(string $location, array $fields, string $mask, bool $validate_only = false): array
            {
                $this->calls[] = ['patch', $location, $fields, $mask];
                if ($this->fail) {
                    throw $this->limited ? new \UAMSWP_FAD_GBP_Rate_Limited('Google API PATCH failed (429): slow') : new RuntimeException('Google API PATCH failed (500): boom');
                }

                return [];
            }

            public function create(string $account, array $fields, string $store_code, bool $validate_only = false): array
            {
                $this->calls[] = ['create', $account, $fields, $store_code];
                if ($this->fail_create !== null) {
                    throw $this->fail_create;
                }

                return ['name' => 'locations/new-'.count($this->calls)];
            }
        };
    }

    /**
     * @param  array<string, string>  $modes
     * @param  array<string, int|null>  $gate  The daily create quota: `creates_paused_until`, `creates_left`, `now`.
     */
    private function sync(array $modes = ['uams' => 'live', 'baptist-health' => 'live'], bool $production = true, array $listings = [], array $gate = []): UAMSWP_FAD_GBP_Sync
    {
        return new UAMSWP_FAD_GBP_Sync(
            $this->client,
            ['silos' => self::SILOS, 'modes' => $modes, 'is_production' => $production] + $gate,
            UAMSWP_FAD_GBP_Sync::build_index($listings),
            function (array $entry): void {
                $this->log[] = $entry;
            }
        );
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function listing(string $account, string $code, array $extra = []): array
    {
        return ['account' => $account, 'listing' => ['name' => 'locations/'.$code, 'storeCode' => $code] + $extra];
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function item(array $overrides = []): array
    {
        return $overrides + [
            'store_code' => 'jane_clinic',
            'kind' => 'provider',
            'silo' => 'uams',
            'fields' => [
                'title' => 'UAMS Health - Jane',
                'storefrontAddress' => ['postalCode' => '72205', 'administrativeArea' => 'AR', 'locality' => 'Little Rock', 'addressLines' => ['1 Main']],
                'phoneNumbers' => ['primaryPhone' => '501-686-6067'],
                'categories' => ['primaryCategory' => ['name' => 'categories/gcid:doctor']],
            ],
            'unresolved' => [],
        ];
    }

    private function actions(): array
    {
        return array_column($this->log, 'action');
    }

    public function test_an_item_with_no_silo_is_skipped(): void
    {
        $this->sync()->run([$this->item(['silo' => null])]);

        $this->assertSame(['skip'], $this->actions());
        $this->assertSame([], $this->client->calls);
    }

    public function test_an_item_at_an_excluded_location_is_skipped_even_when_a_listing_exists(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item(['excluded' => true])]);

        $this->assertSame(['skip'], $this->actions());
        $this->assertStringContainsString('excluded', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_an_excluded_location_is_never_created_for(): void
    {
        $this->sync(['uams' => 'live'])->run([$this->item(['excluded' => true])]);

        $this->assertSame([], $this->client->calls);
    }

    public function test_an_off_silo_is_skipped_without_a_call(): void
    {
        $this->sync(['uams' => 'off'])->run([$this->item()]);

        $this->assertSame(['skip'], $this->actions());
        $this->assertStringContainsString('off', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_silo_with_no_configured_mode_is_off(): void
    {
        $this->sync([])->run([$this->item()]);

        $this->assertSame([], $this->client->calls);
    }

    public function test_live_update_patches_only_the_changed_fields_of_the_matched_listing(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'UAMS Health - Jane', 'phoneNumbers' => ['primaryPhone' => '501-000-0000']]);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame(['update'], $this->actions());
        $this->assertCount(1, $this->client->calls);
        [$verb, $location, $fields, $mask] = $this->client->calls[0];
        $this->assertSame(['patch', 'locations/jane_clinic'], [$verb, $location]);
        $this->assertSame('storefrontAddress,phoneNumbers,categories', $mask);
        $this->assertArrayNotHasKey('title', $fields);
    }

    public function test_an_existing_listing_is_found_only_by_its_store_code(): void
    {
        $other = $this->listing('accounts/up', 'other_clinic', ['title' => 'UAMS Health - Other']);
        $match = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live'], true, [$other, $match])->run([$this->item()]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame('locations/jane_clinic', $this->client->calls[0][1], 'the listing with the same store code is patched, and no other');
        $this->assertCount(1, $this->client->calls);
    }

    public function test_a_changed_slug_makes_a_new_store_code_that_finds_nothing_and_the_old_listing_is_left_alone(): void
    {
        $old = $this->listing('accounts/up', 'jane_clinic', ['title' => 'UAMS Health - Jane']);

        // The provider's slug changed, so the same person now produces a different store code.
        $this->sync(['uams' => 'live'], true, [$old])->run([$this->item(['store_code' => 'jane-q_clinic'])]);

        $this->assertSame(['create'], $this->actions());
        $this->assertSame('jane-q_clinic', $this->client->calls[0][3], 'a second listing is created under the new code');
        $this->assertSame([], array_filter($this->client->calls, fn (array $call): bool => in_array($call[0], ['patch', 'update_attributes'], true)), 'nothing is written to the listing under the old code');
    }

    public function test_dry_run_update_logs_the_fields_and_makes_no_call(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'dry-run'], true, [$listing])->run([$this->item()]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame('dry-run', $this->log[0]['mode']);
        $this->assertContains('title', $this->log[0]['fields']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_live_off_production_behaves_as_dry_run(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live'], false, [$listing])->run([$this->item()]);

        $this->assertSame('dry-run', $this->log[0]['mode']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_listing_that_already_matches_is_a_no_op(): void
    {
        $item = $this->item();
        $listing = $this->listing('accounts/up', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['noop'], $this->actions());
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_listing_in_another_silos_group_is_never_written(): void
    {
        $listing = $this->listing('accounts/bp', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live', 'baptist-health' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame(['review'], $this->actions());
        $this->assertStringContainsString('another silo', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_provider_item_does_not_match_a_listing_in_the_location_group(): void
    {
        $listing = $this->listing('accounts/ul', 'jane_clinic');

        $this->sync()->run([$this->item()]);
        $this->assertSame(['create'], $this->actions(), 'precondition: no listing, so it creates');

        $this->log = [];
        $this->client->calls = [];
        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame(['review'], $this->actions());
        $this->assertStringContainsString('this silo', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_listing_parked_in_an_unmapped_group_is_not_recreated_or_written(): void
    {
        $listing = $this->listing('accounts/closed', 'jane_clinic');

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame(['review'], $this->actions());
        $this->assertStringContainsString('accounts/closed', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_store_code_found_twice_is_sent_to_review(): void
    {
        $listings = [$this->listing('accounts/up', 'jane_clinic'), $this->listing('accounts/closed', 'jane_clinic')];

        $this->sync(['uams' => 'live'], true, $listings)->run([$this->item()]);

        $this->assertSame(['review'], $this->actions());
        $this->assertSame([], $this->client->calls);
    }

    public function test_live_create_goes_to_the_silos_group_for_the_kind(): void
    {
        $this->sync()->run([$this->item()]);
        $this->sync()->run([$this->item(['kind' => 'location', 'store_code' => 'some-clinic'])]);

        $this->assertSame(['create', 'create'], $this->actions());
        $this->assertSame(['create', 'accounts/up', 'jane_clinic'], [$this->client->calls[0][0], $this->client->calls[0][1], $this->client->calls[0][3]]);
        $this->assertSame('accounts/ul', $this->client->calls[1][1]);
    }

    public function test_a_store_code_of_exactly_64_characters_is_sent_to_create_unchanged(): void
    {
        $code = str_repeat('a', 32).'_'.str_repeat('b', 31);
        $this->assertSame(64, strlen($code));

        $this->sync()->run([$this->item(['store_code' => $code])]);

        $this->assertSame(['create'], $this->actions());
        $this->assertSame($code, $this->client->calls[0][3]);
        $this->assertSame('Created', $this->log[0]['reason']);
    }

    public function test_a_store_code_of_65_characters_is_a_review_that_names_the_code_and_sends_nothing(): void
    {
        $code = str_repeat('a', 33).'_'.str_repeat('b', 31);
        $this->assertSame(65, strlen($code));

        $this->sync()->run([$this->item(['store_code' => $code])]);

        $this->assertSame(['review'], $this->actions());
        $this->assertSame([], $this->client->calls, 'no Google call');
        $this->assertStringContainsString($code, $this->log[0]['reason']);
        $this->assertStringContainsString('65 characters', $this->log[0]['reason']);
        $this->assertStringContainsString('limit of 64', $this->log[0]['reason']);
        $this->assertSame($code, $this->log[0]['store_code']);
    }

    public function test_the_length_check_covers_dry_run_and_an_existing_listing(): void
    {
        $code = str_repeat('c', 70);
        $listing = $this->listing('accounts/up', $code, ['title' => 'Old']);

        $this->sync(['uams' => 'dry-run'])->run([$this->item(['store_code' => $code])]);
        $this->sync(listings: [$listing])->run([$this->item(['store_code' => $code])]);

        $this->assertSame(['review', 'review'], $this->actions());
        $this->assertSame('dry-run', $this->log[0]['mode']);
        $this->assertSame([], $this->client->calls, 'an over-length code is not updated either');
    }

    public function test_an_over_length_review_stays_on_the_attention_list_until_it_is_settled(): void
    {
        $code = str_repeat('d', 66);
        $this->sync()->run([$this->item(['store_code' => $code])]);

        $attention = \UAMSWP_FAD_GBP_Logic::next_attention([], ['time' => 1] + $this->log[0], 1000);

        $this->assertSame([$code], array_keys($attention));
        $this->assertFalse(\UAMSWP_FAD_GBP_Logic::is_settled($this->log), 'a review is not settled, so the post is not remembered as done');
    }

    public function test_a_daily_limit_on_create_pauses_every_later_create_until_the_pacific_reset(): void
    {
        $now = 1793732400; // 2026-11-03 19:00 UTC, 11:00 in Los Angeles
        $reset = 1793779200; // 2026-11-04 08:00 UTC, midnight in Los Angeles
        $this->client->fail_create = new \UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): Quota exceeded ... per day', true);

        $sync = $this->sync(gate: ['now' => $now]);
        $entries = $sync->run([$this->item(['store_code' => 'a_clinic']), $this->item(['store_code' => 'b_clinic'])]);

        $this->assertSame(1, count($this->client->calls), 'only the first create reached Google');
        $this->assertSame([$reset, $reset], array_column($entries, 'defer_until'));
        $this->assertSame($reset, $sync->creates_paused_until());
        $this->assertEmpty(array_filter(array_column($entries, 'retryable')), 'a daily limit is not a retry');
        $this->assertSame(['create', 'create'], array_column($entries, 'action'));
        $this->assertFalse(\UAMSWP_FAD_GBP_Logic::is_settled($entries), 'a deferred create is not settled');
        $this->assertSame(0, $sync->creates_made());
    }

    public function test_a_per_minute_limit_on_create_is_still_a_retry_and_pauses_nothing(): void
    {
        $this->client->fail_create = new \UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): slow down');

        $sync = $this->sync(gate: ['now' => 1793732400]);
        $entries = $sync->run([$this->item()]);

        $this->assertSame('error', $entries[0]['action']);
        $this->assertTrue($entries[0]['retryable']);
        $this->assertArrayNotHasKey('defer_until', $entries[0]);
        $this->assertSame(0, $sync->creates_paused_until());
    }

    public function test_a_pause_in_force_holds_creates_without_a_call_and_leaves_updates_running(): void
    {
        $now = 1793732400;
        $until = $now + 3600;
        $listing = $this->listing('accounts/up', 'old_clinic', ['title' => 'Old']);

        $entries = $this->sync(listings: [$listing], gate: ['creates_paused_until' => $until, 'now' => $now])
            ->run([$this->item(['store_code' => 'new_clinic']), $this->item(['store_code' => 'old_clinic'])]);

        $this->assertSame([$until], array_column($entries, 'defer_until'));
        $this->assertStringContainsString('Creates paused until', $entries[0]['reason']);
        $this->assertSame('update', $entries[1]['action']);
        $this->assertSame('Updated', $entries[1]['reason']);
        $this->assertSame(['patch'], array_column($this->client->calls, 0));
    }

    public function test_a_pause_that_has_passed_holds_nothing(): void
    {
        $now = 1793732400;

        $entries = $this->sync(gate: ['creates_paused_until' => $now - 1, 'now' => $now])->run([$this->item()]);

        $this->assertSame('Created', $entries[0]['reason']);
    }

    public function test_the_daily_budget_stops_creates_without_a_refusal_and_counts_each_one(): void
    {
        $now = 1793732400;
        $sync = $this->sync(gate: ['creates_left' => 1, 'now' => $now]);

        $entries = $sync->run([$this->item(['store_code' => 'a_clinic']), $this->item(['store_code' => 'b_clinic'])]);

        $this->assertSame(['Created'], [$entries[0]['reason']]);
        $this->assertSame(1793779200, $entries[1]['defer_until']);
        $this->assertStringContainsString('Daily create budget used', $entries[1]['reason']);
        $this->assertSame(1, $sync->creates_made());
        $this->assertSame(['create'], array_column($this->client->calls, 0), 'the second create never reached Google');
        $this->assertSame(0, $sync->creates_paused_until(), 'a spent budget is not a pause');
    }

    public function test_a_run_without_a_budget_creates_without_limit(): void
    {
        $sync = $this->sync();

        $entries = $sync->run([$this->item(['store_code' => 'a_clinic']), $this->item(['store_code' => 'b_clinic'])]);

        $this->assertSame(['Created', 'Created'], array_column($entries, 'reason'));
        $this->assertSame(2, $sync->creates_made());
    }

    public function test_each_created_listing_is_handed_back_for_the_cached_index(): void
    {
        $sync = $this->sync();
        $sync->run([$this->item(['store_code' => 'a_clinic'])]);

        $created = $sync->created();

        $this->assertCount(1, $created);
        $this->assertSame('accounts/up', $created[0]['account']);
        $this->assertSame('a_clinic', $created[0]['listing']['storeCode']);
    }

    public function test_a_partner_silo_creates_in_its_own_group(): void
    {
        $this->sync()->run([$this->item(['silo' => 'baptist-health'])]);

        $this->assertSame('accounts/bp', $this->client->calls[0][1]);
    }

    public function test_dry_run_create_logs_and_makes_no_call(): void
    {
        $this->sync(['uams' => 'dry-run'])->run([$this->item()]);

        $this->assertSame(['create'], $this->actions());
        $this->assertSame('dry-run', $this->log[0]['mode']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_create_without_a_category_is_skipped_as_incomplete(): void
    {
        $item = $this->item();
        unset($item['fields']['categories']);

        $this->sync()->run([$item]);

        $this->assertSame(['skip'], $this->actions());
        $this->assertStringContainsString('categories', $this->log[0]['reason']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_the_same_store_code_twice_in_one_run_is_created_once(): void
    {
        $this->sync()->run([$this->item(), $this->item()]);

        $this->assertCount(1, $this->client->calls);
        $this->assertSame(['create', 'noop'], $this->actions());
    }

    public function test_an_api_failure_is_logged_and_the_run_continues(): void
    {
        $this->client->fail = true;
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item(), $this->item(['store_code' => 'new_clinic'])]);

        $this->assertSame(['error', 'create'], $this->actions());
        $this->assertStringContainsString('boom', $this->log[0]['reason']);
    }

    public function test_only_a_rate_limit_failure_is_marked_retryable(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', ['title' => 'Old']);

        $this->client->fail = true;
        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->client->limited = true;
        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame([false, true], array_column($this->log, 'retryable'));
    }

    public function test_a_failed_create_is_retryable_only_when_rate_limited(): void
    {
        $this->client->fail_create = new RuntimeException('Google API POST failed (400): bad');
        $this->sync(['uams' => 'live'])->run([$this->item()]);

        $this->client->fail_create = new \UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): slow');
        $this->sync(['uams' => 'live'])->run([$this->item()]);

        $this->assertSame([false, true], array_column($this->log, 'retryable'));
    }

    public function test_unresolved_categories_are_logged(): void
    {
        $this->sync(['uams' => 'dry-run'])->run([$this->item(['unresolved' => ['Not A Category']])]);

        $this->assertContains('Not A Category', $this->log[0]['unresolved_categories']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function location_item(array $overrides = []): array
    {
        return $overrides + $this->item(['kind' => 'location', 'attributes' => ['has_restroom' => true, 'requires_appointments' => false]]);
    }

    private function calls_of(string $kind): array
    {
        return array_values(array_filter($this->client->calls, function (array $call) use ($kind): bool {
            return $call[0] === $kind;
        }));
    }

    public function test_only_the_attributes_that_differ_are_written_when_the_fields_already_match(): void
    {
        $this->client->live_attributes = ['has_restroom' => true, 'requires_appointments' => true];
        $item = $this->location_item();
        $listing = $this->listing('accounts/ul', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame(['requires_appointments'], $this->log[0]['attributes']);
        $this->assertSame([], $this->log[0]['fields']);
        $this->assertSame([], $this->calls_of('patch'));
        $this->assertSame([['update_attributes', 'locations/jane_clinic', ['requires_appointments' => false]]], $this->calls_of('update_attributes'));
    }

    public function test_fields_and_attributes_are_both_written_when_both_differ(): void
    {
        $listing = $this->listing('accounts/ul', 'jane_clinic', ['title' => 'Old']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->location_item()]);

        $this->assertCount(1, $this->calls_of('patch'));
        $this->assertSame([['update_attributes', 'locations/jane_clinic', ['has_restroom' => true, 'requires_appointments' => false]]], $this->calls_of('update_attributes'));
        $this->assertSame(['has_restroom', 'requires_appointments'], $this->log[0]['attributes']);
    }

    public function test_a_listing_whose_fields_and_attributes_match_is_a_no_op(): void
    {
        $this->client->live_attributes = ['has_restroom' => true, 'requires_appointments' => false];
        $item = $this->location_item();
        $listing = $this->listing('accounts/ul', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['noop'], $this->actions());
        $this->assertSame([], $this->calls_of('update_attributes'));
    }

    public function test_dry_run_reports_the_attribute_changes_and_writes_nothing(): void
    {
        $item = $this->location_item();
        $listing = $this->listing('accounts/ul', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'dry-run'], true, [$listing])->run([$item]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame(['has_restroom', 'requires_appointments'], $this->log[0]['attributes']);
        $this->assertSame([], $this->calls_of('update_attributes'));
        $this->assertSame([], $this->calls_of('patch'));
    }

    public function test_a_created_location_gets_its_attributes_set_on_the_new_listing(): void
    {
        $this->sync(['uams' => 'live'])->run([$this->location_item()]);

        $created = $this->calls_of('create');
        $this->assertSame('accounts/ul', $created[0][1]);
        $this->assertSame([['update_attributes', 'locations/new-1', ['has_restroom' => true, 'requires_appointments' => false]]], $this->calls_of('update_attributes'));
        $this->assertSame(['create'], $this->actions());
        $this->assertSame('Created', $this->log[0]['reason']);
    }

    public function test_a_dry_run_create_reports_the_attributes_and_makes_no_call(): void
    {
        $this->sync(['uams' => 'dry-run'])->run([$this->location_item()]);

        $this->assertSame(['has_restroom', 'requires_appointments'], $this->log[0]['attributes']);
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_failed_attribute_write_is_an_error_and_only_a_rate_limit_is_retryable(): void
    {
        $item = $this->location_item();
        $listing = $this->listing('accounts/ul', 'jane_clinic', $item['fields']);

        $this->client->fail_attributes = new RuntimeException('Google API PATCH failed (400): bad');
        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->client->fail_attributes = new \UAMSWP_FAD_GBP_Rate_Limited('Google API PATCH failed (429): slow');
        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['error', 'error'], $this->actions());
        $this->assertSame([false, true], array_column($this->log, 'retryable'));
    }

    public function test_an_item_without_attributes_never_reads_or_writes_them(): void
    {
        $listing = $this->listing('accounts/up', 'jane_clinic', $this->item()['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$this->item()]);

        $this->assertSame([], $this->calls_of('attributes'));
        $this->assertSame([], $this->calls_of('update_attributes'));
    }

    public function test_a_changed_store_code_still_finds_the_listing_by_the_stored_name_and_creates_nothing(): void
    {
        $item = $this->item(['store_code' => 'jane_renamed-clinic', 'stored_name' => 'locations/jane_clinic']);
        $old = $this->listing('accounts/up', 'jane_clinic', ['phoneNumbers' => ['primaryPhone' => '501-000-0000']] + $item['fields']);

        $this->sync(['uams' => 'live'], true, [$old])->run([$item]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame([], $this->calls_of('create'));
        $this->assertSame('locations/jane_clinic', $this->calls_of('patch')[0][1]);
        $this->assertSame('locations/jane_clinic', $this->log[0]['listing_name']);
    }

    public function test_without_the_stored_name_the_same_rename_creates_a_second_listing(): void
    {
        $item = $this->item(['store_code' => 'jane_renamed-clinic']);
        $old = $this->listing('accounts/up', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$old])->run([$item]);

        $this->assertSame(['create'], $this->actions());
    }

    public function test_a_stored_name_no_listing_has_any_more_falls_back_to_the_store_code(): void
    {
        $item = $this->item(['stored_name' => 'locations/deleted']);
        $listing = $this->listing('accounts/up', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['noop'], $this->actions());
        $this->assertSame('locations/jane_clinic', $this->log[0]['listing_name']);
    }

    public function test_a_new_store_code_held_by_another_listing_is_a_review_not_a_write(): void
    {
        $item = $this->item(['store_code' => 'taken', 'stored_name' => 'locations/jane_clinic']);
        $listings = [$this->listing('accounts/up', 'jane_clinic', $item['fields']), $this->listing('accounts/up', 'taken', $item['fields'])];

        $this->sync(['uams' => 'live'], true, $listings)->run([$item]);

        $this->assertSame(['review'], $this->actions());
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_stored_name_in_another_group_is_still_a_review(): void
    {
        $item = $this->item(['store_code' => 'renamed', 'stored_name' => 'locations/jane_clinic']);

        $this->sync(['uams' => 'live'], true, [$this->listing('accounts/bp', 'jane_clinic', $item['fields'])])->run([$item]);

        $this->assertSame(['review'], $this->actions());
        $this->assertSame([], $this->client->calls);
    }

    public function test_a_created_listing_is_named_on_its_entry_and_found_by_name_later_in_the_run(): void
    {
        $sync = $this->sync(['uams' => 'live']);
        $sync->run([$this->item()]);

        $this->assertSame('locations/new-1', $this->log[0]['listing_name']);

        $sync->run([$this->item(['store_code' => 'jane_other', 'stored_name' => 'locations/new-1'])]);

        $this->assertSame(['create', 'noop'], $this->actions());
    }

    public function test_a_listing_created_but_failing_its_attributes_is_still_named_on_the_entry(): void
    {
        $item = $this->location_item();
        $this->client->fail_attributes = new RuntimeException('Google API PATCH failed (400): bad');

        $this->sync(['uams' => 'live'])->run([$item]);

        $this->assertSame(['error'], $this->actions());
        $this->assertStringStartsWith('locations/new-', $this->log[0]['listing_name']);
    }

    /**
     * @return array<int, array{category: string, url: string}>
     */
    private function photos(): array
    {
        return [
            ['category' => 'LOGO', 'url' => 'https://x/logo.png'],
            ['category' => 'COVER', 'url' => 'https://x/cover.jpg'],
            ['category' => 'ADDITIONAL', 'url' => 'https://x/other.jpg'],
        ];
    }

    public function test_a_created_listing_gets_every_photo_with_its_category_and_they_are_reported_as_sent(): void
    {
        $this->sync(['uams' => 'live'])->run([$this->item(['photos' => $this->photos()])]);

        $this->assertSame([['accounts/up', 'locations/new-1', 'LOGO', 'https://x/logo.png'], ['accounts/up', 'locations/new-1', 'COVER', 'https://x/cover.jpg'], ['accounts/up', 'locations/new-1', 'ADDITIONAL', 'https://x/other.jpg']], array_map(fn (array $call): array => array_slice($call, 1), $this->calls_of('upload_photo')));
        $this->assertSame(['https://x/logo.png', 'https://x/cover.jpg', 'https://x/other.jpg'], $this->log[0]['photos_sent_now']);
    }

    public function test_an_update_with_unchanged_photos_makes_no_media_call_and_is_a_noop(): void
    {
        $item = $this->item(['photos' => $this->photos(), 'photos_sent' => ['https://x/logo.png', 'https://x/cover.jpg', 'https://x/other.jpg']]);
        $listing = $this->listing('accounts/up', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['noop'], $this->actions());
        $this->assertSame([], $this->calls_of('upload_photo'));
    }

    public function test_an_update_sends_only_the_photo_whose_image_changed(): void
    {
        $photos = $this->photos();
        $photos[1]['url'] = 'https://x/cover-new.jpg';
        $item = $this->item(['photos' => $photos, 'photos_sent' => ['https://x/logo.png', 'https://x/cover.jpg', 'https://x/other.jpg']]);
        $listing = $this->listing('accounts/up', 'jane_clinic', $item['fields']);

        $this->sync(['uams' => 'live'], true, [$listing])->run([$item]);

        $this->assertSame(['update'], $this->actions());
        $this->assertSame(['Photos sent'], array_column($this->log, 'reason'));
        $this->assertSame([['COVER', 'https://x/cover-new.jpg']], array_map(fn (array $call): array => array_slice($call, 3), $this->calls_of('upload_photo')));
        $this->assertSame([], $this->calls_of('patch'), 'no field changed, so no field write');
    }

    public function test_a_dry_run_silo_reports_the_photos_it_would_send_and_sends_none(): void
    {
        $this->sync(['uams' => 'dry-run'])->run([$this->item(['photos' => $this->photos()])]);

        $this->assertSame(['create'], $this->actions());
        $this->assertSame(['https://x/logo.png', 'https://x/cover.jpg', 'https://x/other.jpg'], $this->log[0]['photos']);
        $this->assertSame([], $this->client->calls);

        $this->log = [];
        $item = $this->item(['photos' => $this->photos()]);
        $this->sync(['uams' => 'dry-run'], true, [$this->listing('accounts/up', 'jane_clinic', $item['fields'])])->run([$item]);

        $this->assertSame(['https://x/logo.png', 'https://x/cover.jpg', 'https://x/other.jpg'], $this->log[0]['photos']);
        $this->assertSame([], $this->calls_of('upload_photo'));
    }

    public function test_a_refused_photo_is_an_error_that_names_the_ones_that_went(): void
    {
        $this->client->fail_photo = new RuntimeException('Google API POST failed (403): no write access');
        $photos = [['category' => 'LOGO', 'url' => 'https://x/logo.png'], ['category' => 'COVER', 'url' => 'https://x/fail.jpg'], ['category' => 'ADDITIONAL', 'url' => 'https://x/other.jpg']];

        $this->sync(['uams' => 'live'])->run([$this->item(['photos' => $photos])]);

        $this->assertSame(['error'], $this->actions());
        $this->assertSame(['https://x/logo.png'], $this->log[0]['photos_sent_now']);
        $this->assertCount(2, $this->calls_of('upload_photo'), 'it stops at the refusal');
        $this->assertStringContainsString('403', $this->log[0]['reason']);
    }
}
