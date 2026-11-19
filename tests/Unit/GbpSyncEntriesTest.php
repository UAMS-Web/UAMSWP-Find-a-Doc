<?php

/**
 * The log entry the Business Profile sync (includes/class.fad-gbp-sync.php) writes for each outcome, pinned
 * whole: every key, every reason and every number in it. The decisions themselves are in
 * tests/Unit/GbpSyncTest.php.
 */

declare(strict_types=1);

/**
 * A stand-in for the Business Profile client that records each call and fails where told to.
 */
final class GbpSyncEntriesClient
{
    /** @var array<int, array<int, mixed>> */
    public array $calls = [];

    /** @var array<string, bool> */
    public array $live_attributes = [];

    public ?RuntimeException $fail_attributes_read = null;

    public ?RuntimeException $fail_attributes_write = null;

    public ?RuntimeException $fail_patch = null;

    public ?RuntimeException $fail_create = null;

    /** @var array<string, RuntimeException> Photo address => the error its upload raises. */
    public array $fail_photos = [];

    /** @var array<string, mixed> */
    public array $created = ['name' => 'locations/new'];

    /**
     * @return array<string, bool>
     */
    public function attributes(string $location): array
    {
        $this->calls[] = ['attributes', $location];

        if ($this->fail_attributes_read instanceof RuntimeException) {
            throw $this->fail_attributes_read;
        }

        return $this->live_attributes;
    }

    /**
     * @param  array<string, bool>  $values
     * @return array<string, mixed>
     */
    public function update_attributes(string $location, array $values): array
    {
        $this->calls[] = ['update_attributes', $location, $values];

        if ($this->fail_attributes_write instanceof RuntimeException) {
            throw $this->fail_attributes_write;
        }

        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function upload_photo(string $account, string $location, string $category, string $url): array
    {
        $this->calls[] = ['upload_photo', $account, $location, $category, $url];

        if (isset($this->fail_photos[$url])) {
            throw $this->fail_photos[$url];
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $fields
     * @return array<string, mixed>
     */
    public function patch(string $location, array $fields, string $mask): array
    {
        $this->calls[] = ['patch', $location, $fields, $mask];

        if ($this->fail_patch instanceof RuntimeException) {
            throw $this->fail_patch;
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $fields
     * @return array<string, mixed>
     */
    public function create(string $account, array $fields, string $store_code): array
    {
        $this->calls[] = ['create', $account, $fields, $store_code];

        if ($this->fail_create instanceof RuntimeException) {
            throw $this->fail_create;
        }

        return $this->created;
    }
}

/** Two silos with an account group for each kind. */
const GBP_ENTRIES_SILOS = [
    'uams' => ['brand' => null, 'accounts' => ['provider' => 'accounts/up', 'location' => 'accounts/ul']],
    'baptist-health' => ['brand' => 'baptist-health', 'accounts' => ['provider' => 'accounts/bp', 'location' => 'accounts/bl']],
];

/** The fields a create needs. */
const GBP_ENTRIES_FIELDS = ['title' => 'Dr. Jo Smith', 'storefrontAddress' => ['addressLines' => ['1 Main St']], 'categories' => ['primaryCategory' => ['name' => 'categories/gcid:doctor']]];

/**
 * A sync over the given listings, with both silos live on production unless told otherwise.
 *
 * @param  array<string, mixed>  $config  Overrides of the configuration.
 * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings
 * @return array{0: UAMSWP_FAD_GBP_Sync, 1: ArrayObject<int, array<string, mixed>>}
 */
function gbp_entries_sync(GbpSyncEntriesClient $client, array $config = [], array $listings = []): array
{
    /** @var ArrayObject<int, array<string, mixed>> $log */
    $log = new ArrayObject;

    $sync = new UAMSWP_FAD_GBP_Sync(
        $client,
        $config + ['silos' => GBP_ENTRIES_SILOS, 'modes' => ['uams' => 'live', 'baptist-health' => 'live'], 'is_production' => true, 'now' => 1700000000, 'clock' => static fn (): int => 1700000000],
        UAMSWP_FAD_GBP_Sync::build_index($listings),
        static function (array $entry) use ($log): void {
            $log[] = $entry;
        }
    );

    return [$sync, $log];
}

/**
 * A sync item for provider P-1 in the UAMS silo.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function gbp_entries_item(array $overrides = []): array
{
    return $overrides + ['store_code' => 'P-1', 'silo' => 'uams', 'kind' => 'provider', 'fields' => GBP_ENTRIES_FIELDS, 'unresolved' => ['Podiatry']];
}

/**
 * The entry every outcome starts from, for an item of gbp_entries_item().
 *
 * @param  array<string, mixed>  $changes
 * @return array<string, mixed>
 */
function gbp_entries_base(array $changes = []): array
{
    return array_replace(['store_code' => 'P-1', 'silo' => 'uams', 'kind' => 'provider', 'mode' => 'live', 'action' => 'skip', 'reason' => '', 'fields' => [], 'attributes' => [], 'unresolved_categories' => ['Podiatry']], $changes);
}

/**
 * Run one item and return its entry.
 *
 * @param  array<string, mixed>  $item
 * @return array<string, mixed>
 */
function gbp_entries_one(UAMSWP_FAD_GBP_Sync $sync, array $item): array
{
    return $sync->run([$item])[0];
}

test('an excluded provider is skipped with its reason and every field of the entry', function (): void {
    [$sync, $log] = gbp_entries_sync(new GbpSyncEntriesClient);

    expect(gbp_entries_one($sync, gbp_entries_item(['excluded' => true])))->toBe(['reason' => 'Providers at this location are excluded from Google Business Profiles'] + gbp_entries_base(['mode' => 'off']))
        ->and($log->count())->toBe(1);
});

test('an item with no silo, or a silo the configuration does not have, is skipped as having no silo', function (?string $silo): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient);

    expect(gbp_entries_one($sync, gbp_entries_item(['silo' => $silo])))->toBe(['reason' => "No silo for this location's brand"] + gbp_entries_base(['silo' => $silo, 'mode' => 'off']));
})->with(['none' => [null], 'unknown' => ['unknown']]);

test('an item of a silo that is off is skipped and not logged', function (): void {
    [$sync, $log] = gbp_entries_sync(new GbpSyncEntriesClient, ['modes' => ['uams' => 'off']]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe(['reason' => 'Silo uams is off', 'unlogged' => true] + gbp_entries_base(['mode' => 'off']))
        ->and($log->count())->toBe(0);
});

test('a store code over 64 characters is sent for review with its length', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient);
    $code = str_repeat('é', 65);

    expect(gbp_entries_one($sync, gbp_entries_item(['store_code' => $code])))->toBe([
        'action' => 'review',
        'reason' => 'Store code '.$code." is 65 characters, over Google's limit of 64; nothing was sent",
    ] + gbp_entries_base(['store_code' => $code]))
        ->and(UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX)->toBe(64);
});

test('a store code of exactly 64 characters is not held back', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient);

    expect(gbp_entries_one($sync, gbp_entries_item(['store_code' => str_repeat('a', 64)]))['action'])->toBe('create');
});

test('a silo with no account group for the kind skips the item', function (array $accounts): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, ['silos' => ['uams' => ['brand' => null, 'accounts' => $accounts]]]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe(['reason' => 'Silo uams has no provider account group'] + gbp_entries_base());
})->with(['only a location group' => [['location' => 'accounts/ul']], 'an empty group name' => [['provider' => '']]]);

test('a create in a live silo sends the fields under the store code and logs the new listing', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'reason' => 'Created',
        'listing_name' => 'locations/new',
        'photos_sent_now' => [],
        'action' => 'create',
        'fields' => ['title', 'storefrontAddress', 'categories'],
        'attributes' => [],
        'photos' => [],
    ] + gbp_entries_base())
        ->and($client->calls)->toBe([['create', 'accounts/up', GBP_ENTRIES_FIELDS, 'P-1']])
        ->and($sync->creates_made())->toBe(1)
        ->and($sync->create_times())->toBe([1700000000])
        ->and($sync->created())->toBe([['account' => 'accounts/up', 'listing' => ['name' => 'locations/new', 'storeCode' => 'P-1'] + GBP_ENTRIES_FIELDS]]);
});

test('a create that lacks a required field names each missing one', function (array $fields, string $missing): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient);

    expect(gbp_entries_one($sync, gbp_entries_item(['fields' => $fields])))->toBe(['reason' => 'Cannot create, missing '.$missing] + gbp_entries_base());
})->with([
    'the title and categories' => [['storefrontAddress' => []], 'title, categories'],
    'the address' => [['title' => 'A', 'categories' => []], 'storefrontAddress'],
]);

test('a create in a dry run names what it would send and sends nothing', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, ['modes' => ['uams' => 'dry-run']]);

    expect(gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true], 'photos' => [['category' => 'LOGO', 'url' => 'https://x/logo.jpg']]])))->toBe([
        'reason' => 'Dry run, no listing created',
        'action' => 'create',
        'fields' => ['title', 'storefrontAddress', 'categories'],
        'attributes' => ['a'],
        'photos' => ['https://x/logo.jpg'],
    ] + gbp_entries_base(['mode' => 'dry-run']))
        ->and($client->calls)->toBe([]);
});

test('a create sets the attributes and sends every photo of the new listing', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client);
    $photos = [['category' => 'LOGO', 'url' => 'https://x/logo.jpg'], ['category' => 'COVER', 'url' => 'https://x/cover.jpg']];

    $entry = gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true], 'photos' => $photos]));

    expect($entry['reason'])->toBe('Created')
        ->and($entry['photos_sent_now'])->toBe(['https://x/logo.jpg', 'https://x/cover.jpg'])
        ->and(array_slice($client->calls, 1))->toBe([
            ['update_attributes', 'locations/new', ['a' => true]],
            ['upload_photo', 'accounts/up', 'locations/new', 'LOGO', 'https://x/logo.jpg'],
            ['upload_photo', 'accounts/up', 'locations/new', 'COVER', 'https://x/cover.jpg'],
        ]);
});

test('a create whose listing comes back unnamed sets no attributes and sends no photo', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->created = [];
    [$sync] = gbp_entries_sync($client);

    $entry = gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true], 'photos' => [['category' => 'LOGO', 'url' => 'https://x/logo.jpg']]]));

    expect($entry['listing_name'])->toBe('')
        ->and($entry['photos_sent_now'])->toBe([])
        ->and(array_column($client->calls, 0))->toBe(['create'])
        ->and($sync->created()[0]['listing']['name'])->toBe('');
});

test('a create of a store code already created in this run updates it instead of creating it again', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client);

    $entries = $sync->run([gbp_entries_item(), gbp_entries_item(['stored_name' => 'locations/new'])]);

    expect(array_column($entries, 'action'))->toBe(['create', 'noop'])
        ->and(array_column($client->calls, 0))->toBe(['create']);
});

test('a create that fails is logged as an error, and as retryable only for a rate limit', function (RuntimeException $error, bool $retryable): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_create = $error;
    [$sync] = gbp_entries_sync($client);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'action' => 'error',
        'reason' => $error->getMessage(),
        'retryable' => $retryable,
        'fields' => ['title', 'storefrontAddress', 'categories'],
        'attributes' => [],
        'photos' => [],
    ] + gbp_entries_base())
        ->and($sync->creates_made())->toBe(0);
})->with([
    'a failure' => [new RuntimeException('Google API POST failed (400): bad'), false],
    'a minute rate limit' => [new UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): slow'), true],
]);

test('a create refused for the day pauses every later create until the Pacific midnight', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_create = new UAMSWP_FAD_GBP_Rate_Limited('Google API POST failed (429): per day', true);
    [$sync] = gbp_entries_sync($client, ['clock' => static fn (): string => '1700000000']);

    $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset(1700000000);
    $entries = $sync->run([gbp_entries_item(), gbp_entries_item(['store_code' => 'P-2'])]);

    expect($entries[0]['reason'])->toBe('Google daily create limit reached, creates paused until '.UAMSWP_FAD_GBP_Logic::reset_label($reset))
        ->and($entries[0]['defer_until'])->toBe($reset)
        ->and($entries[1]['reason'])->toBe('Creates paused until '.UAMSWP_FAD_GBP_Logic::reset_label($reset).' (daily Google limit)')
        ->and($entries[1]['defer_until'])->toBe($reset)
        ->and($sync->creates_paused_until())->toBe($reset)
        ->and(array_column($client->calls, 0))->toBe(['create']);
});

test('a pause still in force holds a create back, and one that ends now does not', function (int $paused, bool $held): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, ['creates_paused_until' => (string) $paused, 'now' => '1700000000']);

    $entry = gbp_entries_one($sync, gbp_entries_item());

    expect(array_key_exists('defer_until', $entry))->toBe($held)
        ->and(count($client->calls))->toBe($held ? 0 : 1);
})->with([
    'a second from now' => [1700000001, true],
    'now' => [1700000000, false],
]);

test('a pause in force holds the create back until it ends', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, ['creates_paused_until' => 1700003600]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'reason' => 'Creates paused until '.UAMSWP_FAD_GBP_Logic::reset_label(1700003600).' (daily Google limit)',
        'defer_until' => 1700003600,
        'action' => 'create',
        'fields' => ['title', 'storefrontAddress', 'categories'],
        'attributes' => [],
        'photos' => [],
    ] + gbp_entries_base());
});

test('a spent create budget holds creates back until the next Pacific midnight', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, ['creates_left' => '1', 'clock' => static fn (): string => '1700000000']);
    $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset(1700000000);

    $entries = $sync->run([gbp_entries_item(), gbp_entries_item(['store_code' => 'P-2'])]);

    expect($entries[0]['action'])->toBe('create')
        ->and($entries[1]['reason'])->toBe('Daily create budget used, creates resume '.UAMSWP_FAD_GBP_Logic::reset_label($reset))
        ->and($entries[1]['defer_until'])->toBe($reset)
        ->and($sync->create_times())->toBe([1700000000]);
});

test('a negative create budget is no budget left', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, ['creates_left' => -3]);

    expect(gbp_entries_one($sync, gbp_entries_item())['reason'])->toStartWith('Daily create budget used');
});

test('setting the attributes of a new listing that fails reports the error with the listing', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_attributes_write = new RuntimeException('Google API PATCH failed (400): attr');
    [$sync] = gbp_entries_sync($client);

    expect(gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true]])))->toBe([
        'action' => 'error',
        'reason' => 'Google API PATCH failed (400): attr',
        'retryable' => false,
        'listing_name' => 'locations/new',
        'fields' => ['title', 'storefrontAddress', 'categories'],
        'attributes' => ['a'],
        'photos' => [],
    ] + gbp_entries_base());
});

test('a refused photo is reported with the ones that were sent, naming the first refusal', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_photos = ['https://x/a.jpg' => new RuntimeException('first'), 'https://x/c.jpg' => new RuntimeException('second')];
    [$sync] = gbp_entries_sync($client);
    $photos = [['category' => 'LOGO', 'url' => 'https://x/a.jpg'], ['category' => 'COVER', 'url' => 'https://x/b.jpg'], ['category' => 'ADDITIONAL', 'url' => 'https://x/c.jpg']];

    $entry = gbp_entries_one($sync, gbp_entries_item(['photos' => $photos]));

    expect($entry['action'])->toBe('error')
        ->and($entry['reason'])->toBe('https://x/a.jpg: first')
        ->and($entry['retryable'])->toBeFalse()
        ->and($entry['photos_sent_now'])->toBe(['https://x/b.jpg'])
        ->and($entry['listing_name'])->toBe('locations/new');
});

test('a rate limit on a photo stops the sending at once', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_photos = ['https://x/a.jpg' => new UAMSWP_FAD_GBP_Rate_Limited('slow')];
    [$sync] = gbp_entries_sync($client);

    $entry = gbp_entries_one($sync, gbp_entries_item(['photos' => [['category' => 'LOGO', 'url' => 'https://x/a.jpg'], ['category' => 'COVER', 'url' => 'https://x/b.jpg']]]));

    expect($entry['reason'])->toBe('slow')
        ->and($entry['retryable'])->toBeTrue()
        ->and($entry['photos_sent_now'])->toBe([])
        ->and(count(array_filter($client->calls, static fn (array $call): bool => $call[0] === 'upload_photo')))->toBe(1);
});

test('an item whose attributes or photos are not lists treats them as a list of that value', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, ['modes' => ['uams' => 'dry-run']]);

    expect(fn () => gbp_entries_one($sync, gbp_entries_item(['attributes' => 'a', 'photos' => []])))->not->toThrow(TypeError::class);
});

/**
 * A listing of store code P-1 in an account group.
 *
 * @param  array<string, mixed>  $listing
 * @return array{account: string, listing: array<string, mixed>}
 */
function gbp_entries_listing(string $account = 'accounts/up', array $listing = []): array
{
    return ['account' => $account, 'listing' => $listing + ['name' => 'locations/1', 'storeCode' => 'P-1'] + GBP_ENTRIES_FIELDS];
}

test('a listing that already matches is a no-op named with the listing', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, [], [gbp_entries_listing()]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe(['action' => 'noop', 'reason' => 'Listing already matches', 'listing_name' => 'locations/1'] + gbp_entries_base());
});

test('an update in a live silo patches the changed fields and logs them', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing(listing: ['title' => 'Old'])]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'reason' => 'Updated',
        'photos_sent_now' => [],
        'action' => 'update',
        'fields' => ['title'],
        'attributes' => [],
        'photos' => [],
        'listing_name' => 'locations/1',
    ] + gbp_entries_base())
        ->and($client->calls)->toBe([['patch', 'locations/1', ['title' => 'Dr. Jo Smith'], 'title']]);
});

test('an update in a dry run names the changes and writes nothing', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, ['modes' => ['uams' => 'dry-run']], [gbp_entries_listing(listing: ['title' => 'Old'])]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'reason' => 'Dry run, no write made',
        'action' => 'update',
        'fields' => ['title'],
        'attributes' => [],
        'photos' => [],
        'listing_name' => 'locations/1',
    ] + gbp_entries_base(['mode' => 'dry-run']))
        ->and($client->calls)->toBe([]);
});

test('an update with only new photos sends them and says so', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing()]);

    $entry = gbp_entries_one($sync, gbp_entries_item(['photos' => [['category' => 'LOGO', 'url' => 'https://x/new.jpg'], ['category' => 'COVER', 'url' => 'https://x/old.jpg']], 'photos_sent' => ['https://x/old.jpg']]));

    expect($entry['reason'])->toBe('Photos sent')
        ->and($entry['photos'])->toBe(['https://x/new.jpg'])
        ->and($entry['photos_sent_now'])->toBe(['https://x/new.jpg'])
        ->and($client->calls)->toBe([['upload_photo', 'accounts/up', 'locations/1', 'LOGO', 'https://x/new.jpg']]);
});

test("an update writes only the attributes that differ from the listing's", function (): void {
    $client = new GbpSyncEntriesClient;
    $client->live_attributes = ['a' => true, 'b' => true];
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing()]);

    $entry = gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true, 'b' => false]]));

    expect($entry['reason'])->toBe('Updated')
        ->and($entry['attributes'])->toBe(['b'])
        ->and($client->calls)->toBe([['attributes', 'locations/1'], ['update_attributes', 'locations/1', ['b' => false]]]);
});

test('an update whose live attributes cannot be read is an error', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_attributes_read = new RuntimeException('Google API GET failed (403): denied');
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing()]);

    expect(gbp_entries_one($sync, gbp_entries_item(['attributes' => ['a' => true]])))->toBe([
        'action' => 'error',
        'reason' => 'Google API GET failed (403): denied',
        'retryable' => false,
        'listing_name' => 'locations/1',
    ] + gbp_entries_base());
});

test('an update whose write fails is an error that keeps the changes', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_patch = new RuntimeException('Google API PATCH failed (500): boom');
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing(listing: ['title' => 'Old'])]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe([
        'action' => 'error',
        'reason' => 'Google API PATCH failed (500): boom',
        'retryable' => false,
        'fields' => ['title'],
        'attributes' => [],
        'photos' => [],
        'listing_name' => 'locations/1',
    ] + gbp_entries_base());
});

test('an update whose photo is refused reports it with the photos sent', function (): void {
    $client = new GbpSyncEntriesClient;
    $client->fail_photos = ['https://x/b.jpg' => new RuntimeException('no')];
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing()]);

    $entry = gbp_entries_one($sync, gbp_entries_item(['photos' => [['category' => 'LOGO', 'url' => 'https://x/a.jpg'], ['category' => 'COVER', 'url' => 'https://x/b.jpg']]]));

    expect($entry)->toBe([
        'action' => 'error',
        'reason' => 'https://x/b.jpg: no',
        'retryable' => false,
        'photos_sent_now' => ['https://x/a.jpg'],
        'fields' => [],
        'attributes' => [],
        'photos' => ['https://x/a.jpg', 'https://x/b.jpg'],
        'listing_name' => 'locations/1',
    ] + gbp_entries_base());
});

test('a store code on more than one listing is sent for review with the count', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, [], [gbp_entries_listing(), gbp_entries_listing('accounts/up', ['name' => 'locations/2'])]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe(['action' => 'review', 'reason' => 'Store code is on 2 listings'] + gbp_entries_base());
});

test('a stored listing ID is matched first, and another listing with the store code is sent for review', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, [], [gbp_entries_listing(), gbp_entries_listing('accounts/up', ['name' => 'locations/2'])]);

    expect(gbp_entries_one($sync, gbp_entries_item(['stored_name' => 'locations/1'])))->toBe(['action' => 'review', 'reason' => 'Store code P-1 is already on another listing than the stored locations/1'] + gbp_entries_base());
});

test('a stored listing ID that is the only listing with the store code is updated', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, [], [gbp_entries_listing()]);

    expect(gbp_entries_one($sync, gbp_entries_item(['stored_name' => 'locations/1']))['action'])->toBe('noop');
});

test('a listing of the store code without a name is another listing than the stored one', function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, [], [gbp_entries_listing(), ['account' => 'accounts/up', 'listing' => ['storeCode' => 'P-1']]]);

    expect(gbp_entries_one($sync, gbp_entries_item(['stored_name' => 'locations/1']))['action'])->toBe('review');
});

test('a listing in another group is sent for review naming where it is', function (string $account, array $silos, string $reason): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, ['silos' => $silos], [gbp_entries_listing($account)]);

    expect(gbp_entries_one($sync, gbp_entries_item()))->toBe(['action' => 'review', 'reason' => $reason] + gbp_entries_base());
})->with([
    "this silo's other group" => ['accounts/ul', GBP_ENTRIES_SILOS, "Listing is in this silo's other group (accounts/ul)"],
    "another silo's group" => ['accounts/bp', GBP_ENTRIES_SILOS, "Listing is in another silo's group (baptist-health)"],
    'a group no silo owns' => ['accounts/zz', GBP_ENTRIES_SILOS, 'Listing is parked in accounts/zz'],
    'a silo named with digits' => ['accounts/7p', GBP_ENTRIES_SILOS + ['7' => ['accounts' => ['provider' => 'accounts/7p']]], "Listing is in another silo's group (7)"],
    'a silo whose groups are not a list' => ['accounts/zz', GBP_ENTRIES_SILOS + ['odd' => ['accounts' => 'accounts/zz']], "Listing is in another silo's group (odd)"],
]);

test('the index keeps only listings with a store code', function (): void {
    expect(UAMSWP_FAD_GBP_Sync::build_index([gbp_entries_listing(), ['account' => 'accounts/up', 'listing' => ['name' => 'locations/9']], ['account' => 'accounts/up', 'listing' => ['name' => 'locations/8', 'storeCode' => '']]]))
        ->toBe(['P-1' => [gbp_entries_listing()]]);
});

test('an item with an empty store code is not matched to listings that have none', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, [], [['account' => 'accounts/up', 'listing' => ['name' => 'locations/9']]]);

    expect(gbp_entries_one($sync, gbp_entries_item(['store_code' => '']))['action'])->toBe('create');
});

test("the configuration's numbers are read as integers whatever type they come in", function (): void {
    [$sync] = gbp_entries_sync(new GbpSyncEntriesClient, ['creates_paused_until' => '5', 'creates_left' => '2', 'now' => '9']);

    expect($sync->creates_paused_until())->toBe(5)
        ->and($sync->creates_made())->toBe(0);
});

test('photos sent recorded as one address, not a list, still keep that photo from being sent again', function (): void {
    $client = new GbpSyncEntriesClient;
    [$sync] = gbp_entries_sync($client, [], [gbp_entries_listing()]);

    $entry = gbp_entries_one($sync, gbp_entries_item(['photos' => [['category' => 'LOGO', 'url' => 'https://x/old.jpg']], 'photos_sent' => 'https://x/old.jpg']));

    expect($entry['action'])->toBe('noop')
        ->and($client->calls)->toBe([]);
});
