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
    /** @var object */
    private $client;

    /** @var array{silos: array<string, array<string, mixed>>, modes: array<string, string>, is_production: bool} */
    private array $config;

    /** @var array<string, array<int, array{account: string, listing: array<string, mixed>}>> */
    private array $index;

    /** @var callable(array<string, mixed>): void */
    private $logger;

    /**
     * @param  object  $client  Provides patch() and create(), as UAMSWP_FAD_GBP_Client does.
     * @param  array{silos: array<string, array<string, mixed>>, modes: array<string, string>, is_production: bool}  $config
     * @param  array<string, array<int, array{account: string, listing: array<string, mixed>}>>  $index  From self::build_index().
     * @param  callable(array<string, mixed>): void  $logger  Receives one entry per item.
     */
    public function __construct($client, array $config, array $index, callable $logger)
    {
        $this->client = $client;
        $this->config = $config;
        $this->index = $index;
        $this->logger = $logger;
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
     * @param  array<int, array{store_code: string, kind: string, silo: ?string, fields: array<string, mixed>, unresolved: array<int, string>, excluded?: bool}>  $items
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
     * @param  array{store_code: string, kind: string, silo: ?string, fields: array<string, mixed>, unresolved: array<int, string>, excluded?: bool}  $item
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

        $account = (string) ($this->config['silos'][$silo]['accounts'][$item['kind']] ?? '');
        if ($account === '') {
            return ['reason' => 'Silo '.$silo.' has no '.$item['kind'].' account group'] + $entry;
        }

        $found = $this->index[$item['store_code']] ?? [];

        if (count($found) > 1) {
            return ['action' => 'review', 'reason' => 'Store code is on '.count($found).' listings'] + $entry;
        }

        if ($found === []) {
            return $this->create($item, $entry, $account, $mode);
        }

        $match = $found[0];

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

        return $this->update($item, $entry, $match['listing'], $mode);
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $entry
     * @param  array<string, mixed>  $listing
     * @return array<string, mixed>
     */
    private function update(array $item, array $entry, array $listing, string $mode): array
    {
        $changed = UAMSWP_FAD_GBP_Logic::changed_fields($item['fields'], $listing);

        if ($changed === []) {
            return ['action' => 'noop', 'reason' => 'Listing already matches'] + $entry;
        }

        $entry = ['action' => 'update', 'fields' => array_keys($changed)] + $entry;

        if ($mode !== 'live') {
            return ['reason' => 'Dry run, no write made'] + $entry;
        }

        try {
            $this->client->patch((string) $listing['name'], $changed, UAMSWP_FAD_GBP_Logic::update_mask($changed));
        } catch (RuntimeException $runtimeException) {
            return ['action' => 'error', 'reason' => $runtimeException->getMessage()] + $entry;
        }

        return ['reason' => 'Updated'] + $entry;
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

        $entry = ['action' => 'create', 'fields' => array_keys($item['fields'])] + $entry;

        if ($mode !== 'live') {
            return ['reason' => 'Dry run, no listing created'] + $entry;
        }

        try {
            $created = $this->client->create($account, $item['fields'], $item['store_code']);
        } catch (RuntimeException $runtimeException) {
            return ['action' => 'error', 'reason' => $runtimeException->getMessage()] + $entry;
        }

        // Remember it, so the same store code later in this run is not created a second time.
        $this->index[$item['store_code']][] = [
            'account' => $account,
            'listing' => ['name' => (string) ($created['name'] ?? ''), 'storeCode' => $item['store_code']] + $item['fields'],
        ];

        return ['reason' => 'Created'] + $entry;
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
