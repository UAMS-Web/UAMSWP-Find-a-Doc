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

            public function patch(string $location, array $fields, string $mask, bool $validate_only = false): array
            {
                $this->calls[] = ['patch', $location, $fields, $mask];
                if ($this->fail) {
                    throw new RuntimeException('Google API PATCH failed (500): boom');
                }

                return [];
            }

            public function create(string $account, array $fields, string $store_code, bool $validate_only = false): array
            {
                $this->calls[] = ['create', $account, $fields, $store_code];

                return ['name' => 'locations/new-'.count($this->calls)];
            }
        };
    }

    /**
     * @param  array<string, string>  $modes
     */
    private function sync(array $modes = ['uams' => 'live', 'baptist-health' => 'live'], bool $production = true, array $listings = []): UAMSWP_FAD_GBP_Sync
    {
        return new UAMSWP_FAD_GBP_Sync(
            $this->client,
            ['silos' => self::SILOS, 'modes' => $modes, 'is_production' => $production],
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

    public function test_unresolved_categories_are_logged(): void
    {
        $this->sync(['uams' => 'dry-run'])->run([$this->item(['unresolved' => ['Not A Category']])]);

        $this->assertContains('Not A Category', $this->log[0]['unresolved_categories']);
    }
}
