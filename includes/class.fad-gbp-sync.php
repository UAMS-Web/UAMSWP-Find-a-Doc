<?php

/**
 * Business Profile sync: the update, create and skip decisions.
 *
 * Takes the desired fields for each store code, finds the matching listing in an index of
 * every listing the credentials can see, and decides what to do. It writes only to a listing
 * in the group its silo owns, never creates a listing whose store code already exists
 * anywhere, and runs a silo in dry-run unless that silo is live on production. The client,
 * the configuration, the index and the logger are all injected, so none of it needs
 * WordPress or the network.
 */

declare(strict_types=1);

final class UAMSWP_FAD_GBP_Sync
{
    /** The longest store code Google accepts, in characters. */
    public const STORE_CODE_MAX = 64;

    /** @var callable(array<string, mixed>): void */
    private $logger;

    /** The moment creates are paused until, or 0 when they are not. */
    private int $paused_until;

    /** How many creates today's budget still allows, or null for no budget. */
    private ?int $creates_left;

    private int $now;

    /** How many creates this run has made. */
    private int $creates_made = 0;

    /** @var array<int, int> The moment each create of this run was sent. */
    private array $create_times = [];

    /** @var callable(): int The clock for quota resets and create stamps. */
    private $clock;

    /** @var array<int, array{account: string, listing: array<string, mixed>}> */
    private array $created = [];

    /** @var array<string, array{account: string, listing: array<string, mixed>}> Every listing of the index by its Google name (`locations/123`). */
    private array $by_name = [];

    /**
     * @param  object  $client  Provides patch() and create(), as UAMSWP_FAD_GBP_Client does.
     * @param  array{silos: array<string, array<string, mixed>>, modes: array<string, string>, is_production: bool, creates_paused_until?: int, creates_left?: ?int, now?: int, clock?: callable(): int}  $config  `creates_paused_until`, `creates_left`, `now` and `clock`
     *                                                                                                                                                                                                             describe Google's daily create quota: a pause in force, the creates still allowed today, and the clock.
     *                                                                                                                                                                                                             `now` is the run's start; `clock` dates quota resets and stamps each create (default: the current time).
     * @param  array<string, array<int, array{account: string, listing: array<string, mixed>}>>  $index  From self::build_index().
     * @param  callable(array<string, mixed>): void  $logger  Receives one entry per item.
     */
    public function __construct(private $client, private array $config, private array $index, callable $logger)
    {
        $this->logger = $logger;
        $this->paused_until = (int) ($config['creates_paused_until'] ?? 0);
        $this->creates_left = isset($config['creates_left']) ? max(0, (int) $config['creates_left']) : null;
        $this->now = (int) ($config['now'] ?? time());
        $this->clock = $config['clock'] ?? time(...);

        foreach ($index as $listings) {
            foreach ($listings as $listing) {
                $name = (string) ($listing['listing']['name'] ?? '');

                if ($name !== '') {
                    $this->by_name[$name] = $listing;
                }
            }
        }
    }

    /**
     * The moment creates are paused until after this run: unchanged unless Google refused a create
     * for the day's quota.
     */
    public function creates_paused_until(): int
    {
        return $this->paused_until;
    }

    /**
     * How many listings this run created.
     */
    public function creates_made(): int
    {
        return $this->creates_made;
    }

    /**
     * The moment each create of this run was sent, so each can be counted on the Pacific day Google counts it on.
     *
     * @return array<int, int>
     */
    public function create_times(): array
    {
        return $this->create_times;
    }

    /**
     * The listings this run created, in the shape of the index.
     *
     * @return array<int, array{account: string, listing: array<string, mixed>}>
     */
    public function created(): array
    {
        return $this->created;
    }

    /**
     * Index listings by store code.
     *
     * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings  Each listing with the account group it was found in.
     * @return array<string, array<int, array{account: string, listing: array<string, mixed>}>>
     */
    public static function build_index(array $listings): array
    {
        $index = [];

        foreach ($listings as $entry) {
            $code = (string) ($entry['listing']['storeCode'] ?? '');
            if ($code !== '') {
                $index[$code][] = $entry;
            }
        }

        return $index;
    }

    /**
     * Decide and, where the silo is live, carry out one action per item.
     *
     * @param  array<int, GbpSyncItem>  $items
     * @return array<int, array<string, mixed>> The log entries, in item order.
     */
    public function run(array $items): array
    {
        $entries = [];

        foreach ($items as $item) {
            $entry = $this->handle($item);
            $entries[] = $entry;
            ($this->logger)($entry);
        }

        return $entries;
    }

    /**
     * @param  GbpSyncItem  $item
     * @return array<string, mixed>
     */
    private function handle(array $item): array
    {
        $silo = $item['silo'];
        $entry = [
            'store_code' => $item['store_code'],
            'silo' => $silo,
            'kind' => $item['kind'],
            'mode' => 'off',
            'action' => 'skip',
            'reason' => '',
            'fields' => [],
            'attributes' => [],
            'unresolved_categories' => $item['unresolved'],
        ];

        if (! empty($item['excluded'])) {
            return ['reason' => 'Providers at this location are excluded from Google Business Profiles'] + $entry;
        }

        if ($silo === null || ! isset($this->config['silos'][$silo])) {
            return ['reason' => "No silo for this location's brand"] + $entry;
        }

        $mode = UAMSWP_FAD_GBP_Logic::effective_mode($this->config['modes'][$silo] ?? 'off', $this->config['is_production']);
        $entry['mode'] = $mode;

        if ($mode === 'off') {
            return ['reason' => 'Silo '.$silo.' is off'] + $entry;
        }

        // Google rejects a longer code on every attempt with an error that does not name the cause.
        // Checked before any create or update, so dry-run, the queue and the command all report it.
        $length = mb_strlen($item['store_code']);

        if ($length > self::STORE_CODE_MAX) {
            return [
                'action' => 'review',
                'reason' => sprintf("Store code %s is %d characters, over Google's limit of %d; nothing was sent", $item['store_code'], $length, self::STORE_CODE_MAX),
            ] + $entry;
        }

        $account = (string) ($this->config['silos'][$silo]['accounts'][$item['kind']] ?? '');
        if ($account === '') {
            return ['reason' => 'Silo '.$silo.' has no '.$item['kind'].' account group'] + $entry;
        }

        $found = $this->index[$item['store_code']] ?? [];

        // The Google location ID stored on the post comes first: it survives a slug change, which
        // changes the store code. A stored ID no listing has any more falls back to the store code.
        $stored = $item['stored_name'] ?? '';
        $match = $stored !== '' ? ($this->by_name[$stored] ?? null) : null;

        if ($match !== null) {
            $others = array_filter($found, fn (array $listing): bool => (string) ($listing['listing']['name'] ?? '') !== $stored);

            if ($others !== []) {
                return ['action' => 'review', 'reason' => sprintf('Store code %s is already on another listing than the stored %s', $item['store_code'], $stored)] + $entry;
            }
        } else {
            if (count($found) > 1) {
                return ['action' => 'review', 'reason' => 'Store code is on '.count($found).' listings'] + $entry;
            }

            if ($found === []) {
                return $this->create($item, $entry, $account, $mode);
            }

            $match = $found[0];
        }

        if ($match['account'] !== $account) {
            $owner = $this->owning_silo($match['account']);

            if ($owner === $silo) {
                $reason = "Listing is in this silo's other group (".$match['account'].')';
            } elseif ($owner !== null) {
                $reason = "Listing is in another silo's group (".$owner.')';
            } else {
                $reason = 'Listing is parked in '.$match['account'];
            }

            return ['action' => 'review', 'reason' => $reason] + $entry;
        }

        return $this->update($item, $entry, $match['listing'], $mode, $account);
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $entry
     * @param  array<string, mixed>  $listing
     * @return array<string, mixed>
     */
    private function update(array $item, array $entry, array $listing, string $mode, string $account): array
    {
        // Named on every outcome, so the caller can store the ID of the listing that was matched.
        $entry = ['listing_name' => (string) ($listing['name'] ?? '')] + $entry;
        $changed = UAMSWP_FAD_GBP_Logic::changed_fields($item['fields'], $listing);

        try {
            $attributes = $this->changed_attributes($item, (string) $listing['name']);
        } catch (RuntimeException $runtimeException) {
            return $this->failure($runtimeException) + $entry;
        }

        // A photo goes only when its address is new for this listing: an unchanged image makes no call.
        $photos = UAMSWP_FAD_GBP_Logic::photos_to_send((array) ($item['photos'] ?? []), (array) ($item['photos_sent'] ?? []));

        if ($changed === [] && $attributes === [] && $photos === []) {
            return ['action' => 'noop', 'reason' => 'Listing already matches'] + $entry;
        }

        $entry = ['action' => 'update', 'fields' => array_keys($changed), 'attributes' => array_keys($attributes), 'photos' => array_column($photos, 'url')] + $entry;

        if ($mode !== 'live') {
            return ['reason' => 'Dry run, no write made'] + $entry;
        }

        try {
            if ($changed !== []) {
                $this->client->patch((string) $listing['name'], $changed, UAMSWP_FAD_GBP_Logic::update_mask($changed));
            }

            if ($attributes !== []) {
                $this->client->update_attributes((string) $listing['name'], $attributes);
            }
        } catch (RuntimeException $runtimeException) {
            return $this->failure($runtimeException) + $entry;
        }

        $sent = [];

        try {
            $this->send_photos($account, (string) $listing['name'], $photos, $sent);
        } catch (RuntimeException $runtimeException) {
            return $this->failure($runtimeException) + ['photos_sent_now' => $sent] + $entry;
        }

        return ['reason' => $changed === [] && $attributes === [] ? 'Photos sent' : 'Updated', 'photos_sent_now' => $sent] + $entry;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $entry
     * @return array<string, mixed>
     */
    private function create(array $item, array $entry, string $account, string $mode): array
    {
        $missing = array_values(array_diff(['title', 'storefrontAddress', 'categories'], array_keys($item['fields'])));

        if ($missing !== []) {
            return ['reason' => 'Cannot create, missing '.implode(', ', $missing)] + $entry;
        }

        $attributes = (array) ($item['attributes'] ?? []);
        $photos = UAMSWP_FAD_GBP_Logic::photos_to_send((array) ($item['photos'] ?? []), []);
        $entry = ['action' => 'create', 'fields' => array_keys($item['fields']), 'attributes' => array_keys($attributes), 'photos' => array_column($photos, 'url')] + $entry;

        if ($mode !== 'live') {
            return ['reason' => 'Dry run, no listing created'] + $entry;
        }

        // A pause or a spent budget holds the create back until the next reset. Updates are not held.
        if ($this->paused_until > $this->now) {
            return $this->deferred('Creates paused until '.UAMSWP_FAD_GBP_Logic::reset_label($this->paused_until).' (daily Google limit)', $this->paused_until) + $entry;
        }

        if ($this->creates_left !== null && $this->creates_made >= $this->creates_left) {
            $reset = UAMSWP_FAD_GBP_Logic::next_pacific_reset((int) ($this->clock)());

            return $this->deferred('Daily create budget used, creates resume '.UAMSWP_FAD_GBP_Logic::reset_label($reset), $reset) + $entry;
        }

        try {
            $created = $this->client->create($account, $item['fields'], $item['store_code']);
        } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
            if (! $rateLimited->daily) {
                return $this->failure($rateLimited) + $entry;
            }

            // The day's quota is gone: hold every later create until Google resets it.
            $this->paused_until = UAMSWP_FAD_GBP_Logic::next_pacific_reset((int) ($this->clock)());

            return $this->deferred('Google daily create limit reached, creates paused until '.UAMSWP_FAD_GBP_Logic::reset_label($this->paused_until), $this->paused_until) + $entry;
        } catch (RuntimeException $runtimeException) {
            return $this->failure($runtimeException) + $entry;
        }

        $this->creates_made++;
        $this->create_times[] = (int) ($this->clock)();

        // Remember it, so the same store code later in this run is not created a second time.
        $listing = [
            'account' => $account,
            'listing' => ['name' => (string) ($created['name'] ?? ''), 'storeCode' => $item['store_code']] + $item['fields'],
        ];
        $this->index[$item['store_code']][] = $listing;
        $this->created[] = $listing;

        if ($listing['listing']['name'] !== '') {
            $this->by_name[$listing['listing']['name']] = $listing;
        }

        // The listing exists now. A failure setting attributes is reported, and the next run
        // finds the listing by its store code and sets them as an update. Its name is on the entry
        // either way, so the caller stores the ID.
        $entry = ['listing_name' => (string) ($created['name'] ?? '')] + $entry;

        if ($attributes !== [] && ! empty($created['name'])) {
            try {
                $this->client->update_attributes((string) $created['name'], $attributes);
            } catch (RuntimeException $runtimeException) {
                return $this->failure($runtimeException) + $entry;
            }
        }

        // A new listing gets every photo. A failure sending one is reported with the ones that went.
        $sent = [];

        if ($photos !== [] && ! empty($created['name'])) {
            try {
                $this->send_photos($account, (string) $created['name'], $photos, $sent);
            } catch (RuntimeException $runtimeException) {
                return $this->failure($runtimeException) + ['photos_sent_now' => $sent] + $entry;
            }
        }

        return ['reason' => 'Created', 'listing_name' => (string) ($created['name'] ?? ''), 'photos_sent_now' => $sent] + $entry;
    }

    /**
     * Send photos to a listing one at a time, noting each address as it goes.
     *
     * @param  array<int, array{category: string, url: string}>  $photos
     * @param  array<int, string>  $sent  Receives the address of each photo Google accepted.
     *
     * @throws RuntimeException After every photo was tried, naming each refused one; a rate limit ends the sending at once.
     */
    private function send_photos(string $account, string $listing_name, array $photos, array &$sent): void
    {
        $refused = null;

        foreach ($photos as $photo) {
            try {
                $this->client->upload_photo($account, $listing_name, $photo['category'], $photo['url']);
                $sent[] = $photo['url'];
            } catch (UAMSWP_FAD_GBP_Rate_Limited $rateLimited) {
                throw $rateLimited;
            } catch (RuntimeException $runtimeException) {
                // One refused photo must not block the ones after it; the first refusal is the one reported.
                $refused ??= new RuntimeException($photo['url'].': '.$runtimeException->getMessage(), 0, $runtimeException);
            }
        }

        if ($refused instanceof RuntimeException) {
            throw $refused;
        }
    }

    /**
     * The desired attributes that differ from the listing's live ones.
     *
     * A listing is only read when the item states attributes, so provider items cost no call.
     *
     * @param  array<string, mixed>  $item
     * @return array<string, bool>
     *
     * @throws RuntimeException When the live attributes cannot be read.
     */
    private function changed_attributes(array $item, string $listing_name): array
    {
        $desired = (array) ($item['attributes'] ?? []);

        if ($desired === []) {
            return [];
        }

        return UAMSWP_FAD_GBP_Logic::changed_attributes($desired, $this->client->attributes($listing_name));
    }

    /**
     * The log fields for a create held back until a later moment.
     *
     * Not an error and not retryable: the run does not count an attempt, and the entry is not settled,
     * so the post runs again at `defer_until`.
     *
     * @return array<string, mixed>
     */
    private function deferred(string $reason, int $until): array
    {
        return ['reason' => $reason, 'defer_until' => $until];
    }

    /**
     * The log fields for a failed call.
     *
     * @return array<string, mixed>
     */
    private function failure(RuntimeException $runtimeException): array
    {
        return ['action' => 'error', 'reason' => $runtimeException->getMessage(), 'retryable' => $runtimeException instanceof UAMSWP_FAD_GBP_Rate_Limited];
    }

    /**
     * The silo that owns an account group, or null for a group no silo owns.
     */
    private function owning_silo(string $account): ?string
    {
        foreach ($this->config['silos'] as $name => $silo) {
            if (in_array($account, (array) ($silo['accounts'] ?? []), true)) {
                return (string) $name;
            }
        }

        return null;
    }
}
