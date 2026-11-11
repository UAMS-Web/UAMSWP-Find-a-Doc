<?php

declare(strict_types=1);
/**
 * Business Profile sync: the decisions that need no WordPress and no network.
 *
 * Mapping a table row onto Business Profile fields, deciding which fields differ from a
 * live listing, resolving a location's silo, and capping a silo's mode by environment all
 * live here as static functions so they can be tested on their own.
 */
class UAMSWP_FAD_GBP_Logic
{
    /** The Business Profile fields the sync owns. Photos, posts, hours and Q&A are never written. */
    const OWNED_FIELDS = ['title', 'storefrontAddress', 'latlng', 'phoneNumbers', 'websiteUri', 'categories', 'profile'];

    /** Modes a silo can be in, from least to most permissive. */
    const MODES = ['off', 'dry-run', 'live'];

    /**
     * Resolve the silo a location belongs to from its brand fields.
     *
     * @param  mixed  $brand_query  The `schema_brandorg_query` value. '1' replaces the default brand.
     * @param  mixed  $brand_org  The `schema_brandorg` value, a `brand_organization` term slug.
     * @param  array<string, array>  $silos  Silo definitions keyed by silo name, each with a `brand` slug or null.
     * @return string|null The silo name, or null when the brand has no silo (default-deny).
     */
    public static function resolve_silo($brand_query, $brand_org, array $silos): null|int|string
    {

        if ((string) $brand_query !== '1') {
            return isset($silos['uams']) ? 'uams' : null;
        }

        foreach ($silos as $name => $silo) {
            if (! empty($silo['brand']) && (string) $silo['brand'] === (string) $brand_org) {
                return $name;
            }
        }

        return null;

    }

    /**
     * The mode a silo actually runs in: the configured mode, capped at dry-run off production.
     *
     * @param  string  $configured  The silo's configured mode. Anything unrecognized is treated as off.
     * @param  bool  $is_production  Whether this site is production.
     * @return string One of self::MODES.
     */
    public static function effective_mode($configured, $is_production): string
    {

        if (! in_array($configured, self::MODES, true)) {
            return 'off';
        }

        if ($configured === 'live' && ! $is_production) {
            return 'dry-run';
        }

        return $configured;

    }

    /**
     * The title of a provider's Business Profile listing for one location.
     *
     * A provider at several locations has one listing per location, so the title names the
     * location the listing is for; otherwise the listings could not be told apart in Google.
     * A blank location name leaves the provider name alone rather than a trailing separator.
     *
     * @param  string  $provider_name  The provider's full name, already decoded.
     * @param  string  $location_title  The title of the location in the listing's store code, already decoded.
     * @return string `UAMS Health - <provider name> - <location name>`.
     */
    public static function provider_listing_title($provider_name, $location_title): string
    {

        $title = 'UAMS Health - '.trim((string) $provider_name);
        $location_title = trim((string) $location_title);

        return $location_title === '' ? $title : $title.' - '.$location_title;

    }

    /**
     * A location name without the place it is "in", "at" or "on", for an address line.
     *
     * The CSV export had the parent's name on one address line and the child's on the next,
     * and whoever uploaded it removed the place from the end of each name by hand. The place
     * is whatever follows the first ` in`, ` at` or ` on`, each with or without ` the`, in any letter
     * case: the parent, but also a facility, a city, a neighborhood or a street. Everything
     * from that joiner on goes. A name with no joiner, or with nothing before or after one, is
     * left as it is. The listing title keeps the full name.
     *
     * @param  string  $name  A location's name.
     */
    public static function location_name_without_place($name): string
    {

        $name = trim((string) $name);

        if (preg_match('/^(.*?\S)\s+(?:in|at|on)(?:\s+the)?\s+\S/iu', $name, $matches) === 1) {
            return $matches[1];
        }

        return $name;

    }

    /**
     * Split a store code into its provider and location slugs.
     *
     * Provider store codes are `{provider_slug}_{location_slug}`. Slugs use hyphens, so the one
     * underscore is the separator.
     *
     * @param  string  $store_code  A provider store code.
     * @return array{0: string, 1: string}|null The provider and location slugs, or null if malformed.
     */
    public static function split_provider_store_code($store_code): ?array
    {

        $parts = explode('_', (string) $store_code);

        if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
            return null;
        }

        return [$parts[0], $parts[1]];

    }

    /**
     * Turn one table row into the Business Profile fields the sync owns.
     *
     * Column numbers match the shared table builders (0 store code through 17 description).
     *
     * @param  array  $row  A row from uamswp_fad_gmb_provider_table() or the location builder.
     * @param  array<string,string>  $category_ids  Category display name => `categories/gcid:...` resource name.
     * @return array The desired fields. A field with no value in the row is left out.
     */
    public static function fields_from_row(array $row, array $category_ids): array
    {

        $cell = (fn ($i): string => isset($row[$i]) ? trim(html_entity_decode((string) $row[$i], ENT_QUOTES, 'UTF-8')) : '');

        $fields = [];

        if ($cell(1) !== '') {
            $fields['title'] = $cell(1);
        }

        $lines = [];
        foreach (range(2, 6) as $i) {
            if ($cell($i) !== '') {
                $lines[] = $cell($i);
            }
        }

        if ($lines && $cell(7) !== '' && $cell(8) !== '' && $cell(10) !== '') {
            $fields['storefrontAddress'] = [
                'regionCode' => $cell(9) !== '' ? $cell(9) : 'US',
                'languageCode' => 'en',
                'postalCode' => $cell(10),
                'administrativeArea' => $cell(8),
                'locality' => $cell(7),
                'addressLines' => $lines,
            ];
        }

        if (is_numeric($cell(11)) && is_numeric($cell(12))) {
            $fields['latlng'] = [
                'latitude' => (float) $cell(11),
                'longitude' => (float) $cell(12),
            ];
        }

        if ($cell(13) !== '') {
            $fields['phoneNumbers'] = ['primaryPhone' => $cell(13)];
        }

        if ($cell(14) !== '') {
            $fields['websiteUri'] = $cell(14);
        }

        $lookup = self::category_lookup($category_ids);
        $primary = $lookup[strtolower($cell(15))] ?? '';
        if ($primary !== '') {
            $categories = ['primaryCategory' => ['name' => $primary]];
            $additional = [];
            foreach (explode(',', $cell(16)) as $name) {
                $resolved = $lookup[strtolower(trim($name))] ?? '';
                if ($resolved !== '' && $resolved !== $primary) {
                    $additional[] = ['name' => $resolved];
                }
            }

            if ($additional !== []) {
                $categories['additionalCategories'] = $additional;
            }

            $fields['categories'] = $categories;
        }

        if ($cell(17) !== '') {
            $fields['profile'] = ['description' => $cell(17)];
        }

        return $fields;

    }

    /**
     * The category names in a row that Google has no category for.
     *
     * Matching ignores case, since the site's taxonomy and Google's catalog differ in
     * capitalization ("Orthopedic Surgeon" against "Orthopedic surgeon"). A name that still
     * does not match is dropped from the listing, so the sync logs it for an editor to fix.
     *
     * @param  array<int, mixed>  $row  A table row.
     * @param  array<string, string>  $category_ids  Category display name => resource name.
     * @return array<int, string> The unmatched names, in row order.
     */
    public static function unresolved_categories(array $row, array $category_ids): array
    {
        $lookup = self::category_lookup($category_ids);
        $names = array_merge([(string) ($row[15] ?? '')], explode(',', (string) ($row[16] ?? '')));
        $unresolved = [];

        foreach ($names as $name) {
            $name = trim(html_entity_decode($name, ENT_QUOTES, 'UTF-8'));
            if ($name !== '' && ! isset($lookup[strtolower($name)])) {
                $unresolved[] = $name;
            }
        }

        return $unresolved;
    }

    /**
     * Category display names lowercased, for case-insensitive matching.
     *
     * @param  array<string, string>  $category_ids
     * @return array<string, string>
     */
    private static function category_lookup(array $category_ids): array
    {
        return array_change_key_case($category_ids, CASE_LOWER);
    }

    /**
     * The desired fields that differ from the live listing.
     *
     * Values are compared after normalization (phone digits only, coordinates to five places,
     * categories by resource name), so formatting differences do not count as changes.
     *
     * @param  array  $desired  Fields from self::fields_from_row().
     * @param  array  $live  The live listing as the API returns it.
     * @return array The subset of $desired that differs. Empty when nothing needs writing.
     */
    public static function changed_fields(array $desired, array $live): array
    {

        $changed = [];

        foreach (self::OWNED_FIELDS as $field) {

            if (! isset($desired[$field])) {
                continue;
            }

            $live_value = $live[$field] ?? null;

            if (self::normalize($field, $desired[$field]) !== self::normalize($field, $live_value)) {
                $changed[$field] = $desired[$field];
            }
        }

        return $changed;

    }

    /**
     * The `updateMask` for a set of changed fields.
     *
     * @param  array  $changed  Fields from self::changed_fields().
     * @return string Comma-separated field names, restricted to the fields the sync owns.
     */
    public static function update_mask(array $changed): string
    {

        return implode(',', array_values(array_intersect(self::OWNED_FIELDS, array_keys($changed))));

    }

    /**
     * Reduce a field value to a form that compares equal across harmless formatting differences.
     *
     * @param  string  $field  A field name from self::OWNED_FIELDS.
     * @param  mixed  $value  The field value, or null when the live listing has none.
     * @return mixed
     */
    private static function normalize(string $field, $value)
    {

        if ($value === null) {
            return null;
        }

        switch ($field) {

            case 'phoneNumbers':
                return ['primaryPhone' => preg_replace('/\D+/', '', (string) ($value['primaryPhone'] ?? ''))];

            case 'latlng':
                return [
                    round((float) ($value['latitude'] ?? 0), 5),
                    round((float) ($value['longitude'] ?? 0), 5),
                ];

            case 'storefrontAddress':
                return [
                    'postalCode' => trim((string) ($value['postalCode'] ?? '')),
                    'administrativeArea' => trim((string) ($value['administrativeArea'] ?? '')),
                    'locality' => trim((string) ($value['locality'] ?? '')),
                    'addressLines' => array_values(array_map(trim(...), (array) ($value['addressLines'] ?? []))),
                ];

            case 'categories':
                $additional = [];
                foreach ((array) ($value['additionalCategories'] ?? []) as $category) {
                    $additional[] = (string) ($category['name'] ?? '');
                }

                sort($additional);

                return [
                    'primary' => (string) ($value['primaryCategory']['name'] ?? ''),
                    'additional' => $additional,
                ];

            case 'profile':
                return ['description' => trim((string) ($value['description'] ?? ''))];

            default:
                return is_string($value) ? trim($value) : $value;
        }

    }

    /**
     * A fingerprint of everything that decides what the sync does for one provider.
     *
     * Equal fingerprints mean the same desired fields, silos, exclusions, modes and code, so a
     * job whose last settled run had this fingerprint has nothing new to say to Google.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<string, string>  $modes
     */
    public static function fingerprint(array $items, array $modes, bool $production, string $version): string
    {
        ksort($modes);

        return sha1((string) json_encode([$items, $modes, $production, $version]));
    }

    /**
     * The photos a table row carries: the logo (column 18), the cover (19) and, for a location, the other photos (20).
     *
     * Column 20 holds the other photos' addresses joined by commas. A photo with no address is left out. A provider's
     * column 20 is not photos, so a provider row is read without them.
     *
     * @param  array  $row  A row from uamswp_fad_gmb_provider_table() or the location builder.
     * @return array<int, array{category: string, url: string}>
     */
    public static function photos_from_row(array $row, bool $with_others = true): array
    {
        $photos = [];

        foreach ([[18, 'LOGO'], [19, 'COVER']] as [$column, $category]) {
            $url = trim((string) ($row[$column] ?? ''));

            if ($url !== '') {
                $photos[] = ['category' => $category, 'url' => $url];
            }
        }

        foreach ($with_others ? explode(',', (string) ($row[20] ?? '')) : [] as $url) {
            $url = trim($url);

            if ($url !== '') {
                $photos[] = ['category' => 'ADDITIONAL', 'url' => $url];
            }
        }

        return $photos;
    }

    /**
     * The photos not yet sent: those whose address is not among the addresses already sent for this listing.
     *
     * A photo is identified by its address, so an image that changed is a new address and is sent again; an
     * unchanged one is not. The same address under two categories is sent once.
     *
     * @param  array<int, array{category: string, url: string}>  $photos
     * @param  array<int, string>  $sent
     * @return array<int, array{category: string, url: string}>
     */
    public static function photos_to_send(array $photos, array $sent): array
    {
        $pending = [];
        $seen = array_flip($sent);

        foreach ($photos as $photo) {
            if (! isset($seen[$photo['url']])) {
                $pending[] = $photo;
                $seen[$photo['url']] = true;
            }
        }

        return $pending;
    }

    /** How many continuous days a listing stays orphaned before the sync closes it. */
    const ORPHAN_GRACE_DAYS = 14;

    /**
     * The fields written to close a listing, and the mask naming them.
     *
     * The listing is marked permanently closed and its website, description, phone, labels and additional
     * categories are cleared. The primary category, address and title stay. Nothing here deletes anything.
     *
     * @return array{fields: array<string, mixed>, mask: string}
     */
    public static function close_patch(): array
    {
        return [
            'fields' => [
                'openInfo' => ['status' => 'CLOSED_PERMANENTLY'],
                'websiteUri' => '',
                'profile' => ['description' => ''],
                'phoneNumbers' => new stdClass,
                'labels' => [],
                'categories' => ['additionalCategories' => []],
            ],
            'mask' => 'openInfo.status,websiteUri,profile.description,phoneNumbers,labels,categories.additionalCategories',
        ];
    }

    /**
     * Whether a live listing is already marked closed.
     *
     * @param  array<string, mixed>  $listing
     */
    public static function is_closed(array $listing): bool
    {
        return in_array($listing['openInfo']['status'] ?? '', ['CLOSED_PERMANENTLY', 'CLOSED_TEMPORARILY'], true);
    }

    /**
     * Put days remaining and a due flag on each orphan candidate.
     *
     * The period counts whole days from the date the listing was first seen orphaned and is due on the
     * fourteenth. A candidate with no valid date is not due and shows the full period.
     *
     * @param  array<int, array{name: string, cause: string, since: string, label: string}>  $candidates
     * @return array<int, array{name: string, cause: string, since: string, label: string, days_left: int, due: bool}>
     */
    public static function orphan_report(array $candidates, string $today): array
    {
        $report = [];
        $end = strtotime($today.' 00:00:00 UTC');

        foreach ($candidates as $candidate) {
            $start = strtotime($candidate['since'].' 00:00:00 UTC');
            $elapsed = $start === false || $end === false ? 0 : max(0, intdiv($end - $start, 86400));
            $valid = $start !== false && $end !== false;

            $report[] = $candidate + [
                'days_left' => max(0, self::ORPHAN_GRACE_DAYS - $elapsed),
                'due' => $valid && $elapsed >= self::ORPHAN_GRACE_DAYS,
            ];
        }

        return $report;
    }

    /**
     * Put a listing's Google name in a provider's map of listings, one entry per location post.
     *
     * Seeing the listing again clears an orphan date.
     *
     * @param  array<int|string, array{id: string, orphaned_since: ?string}>  $map
     * @return array<int|string, array{id: string, orphaned_since: ?string}>
     */
    public static function remember_listing(array $map, int $location_id, string $name): array
    {
        $kept = ($map[$location_id]['id'] ?? '') === $name ? (array) ($map[$location_id]['photos'] ?? []) : [];
        $map[$location_id] = ['id' => $name, 'orphaned_since' => null] + ($kept === [] ? [] : ['photos' => $kept]);

        return $map;
    }

    /**
     * Date the entries of a provider's map whose location the provider no longer has.
     *
     * Only an entry that is not already dated gets a date, so the date stays the day it was first seen. An
     * entry for a location the provider still has loses any date. No entry is removed.
     *
     * @param  array<int|string, array{id: string, orphaned_since: ?string}>  $map
     * @param  array<int, int>  $current_location_ids  The location posts the provider is at now.
     * @return array<int|string, array{id: string, orphaned_since: ?string}>
     */
    public static function mark_orphans(array $map, array $current_location_ids, string $date): array
    {
        foreach ($map as $location_id => $entry) {
            if (in_array((int) $location_id, $current_location_ids, true)) {
                $map[$location_id]['orphaned_since'] = null;
            } elseif ($entry['orphaned_since'] === null) {
                $map[$location_id]['orphaned_since'] = $date;
            }
        }

        return $map;
    }

    /**
     * Every Google CID in a field value: the digit runs of ten or more, whatever the nesting.
     *
     * @return array<int, string>
     */
    public static function cids_from_value(mixed $value): array
    {
        if (is_array($value)) {
            $found = [];

            foreach ($value as $part) {
                $found = array_merge($found, self::cids_from_value($part));
            }

            return array_values(array_unique($found));
        }

        if (! is_scalar($value)) {
            return [];
        }

        // A number field can come back as a float, which would print as 1.2345E+19.
        if (is_float($value)) {
            $value = number_format($value, 0, '', '');
        }

        preg_match_all('/\d{10,}/', (string) $value, $matches);

        return array_values(array_unique($matches[0]));
    }

    /**
     * The CID a listing's Maps link carries, or null.
     */
    public static function cid_from_maps_uri(string $uri): ?string
    {
        return preg_match('/[?&]cid=(\d+)/', $uri, $matches) === 1 ? $matches[1] : null;
    }

    /**
     * Decide which stored ID each item would get, without writing anything.
     *
     * An item that has a stored ID still held by a listing is left alone. Otherwise its store code is tried, then
     * the CIDs on its post: a CID counts only when exactly one listing carries it, and a provider's CID only when the
     * provider has a single item, since a CID does not say which of several locations it is for.
     *
     * @param  array<int, array<string, mixed>>  $items  Items with `post_id`, `kind`, `store_code` and `stored_name`.
     * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings
     * @param  array<int, array<int, string>>  $cids_by_post  Post ID => the CIDs on that post.
     * @return array{already: array<int, array<string, mixed>>, by_store_code: array<int, array{item: array<string, mixed>, name: string}>, by_cid: array<int, array{item: array<string, mixed>, name: string}>, unmatched: array<int, array<string, mixed>>}
     */
    public static function backfill_plan(array $items, array $listings, array $cids_by_post): array
    {
        $by_name = [];
        $by_code = [];
        $by_cid = [];

        foreach ($listings as $entry) {
            $listing = $entry['listing'];
            $name = (string) ($listing['name'] ?? '');
            $by_name[$name] = true;

            if (($listing['storeCode'] ?? '') !== '') {
                $by_code[(string) $listing['storeCode']][] = $name;
            }

            $cid = self::cid_from_maps_uri((string) ($listing['metadata']['mapsUri'] ?? ''));

            if ($cid !== null) {
                $by_cid[$cid][] = $name;
            }
        }

        $per_post = [];

        foreach ($items as $item) {
            $per_post[(int) ($item['post_id'] ?? 0)] = ($per_post[(int) ($item['post_id'] ?? 0)] ?? 0) + 1;
        }

        $plan = ['already' => [], 'by_store_code' => [], 'by_cid' => [], 'unmatched' => []];
        $claimed = [];

        // Names already stored are taken, so no route hands the same listing to a second post.
        foreach ($items as $position => $item) {
            $stored = (string) ($item['stored_name'] ?? '');

            if ($stored !== '' && isset($by_name[$stored])) {
                $plan['already'][] = $item;
                $claimed[$stored] = true;
                unset($items[$position]);
            }
        }

        // An item with no resolved post or location cannot be stored under the right key.
        foreach ($items as $position => $item) {
            if ((int) ($item['post_id'] ?? 0) === 0 || ($item['kind'] === 'provider' && (int) ($item['location_id'] ?? 0) === 0)) {
                $plan['unmatched'][] = $item;
                unset($items[$position]);
            }
        }

        // Store code first for every item, then CID for what is left.
        foreach ($items as $position => $item) {
            $codes = $by_code[$item['store_code']] ?? [];

            if (count($codes) === 1 && ! isset($claimed[$codes[0]])) {
                $plan['by_store_code'][] = ['item' => $item, 'name' => $codes[0]];
                $claimed[$codes[0]] = true;
                unset($items[$position]);
            }
        }

        foreach ($items as $item) {
            $codes = $by_code[$item['store_code']] ?? [];
            $post_id = (int) ($item['post_id'] ?? 0);
            $eligible = $item['kind'] === 'location' || ($per_post[$post_id] ?? 0) === 1;
            $names = [];

            foreach ($eligible ? ($cids_by_post[$post_id] ?? []) : [] as $cid) {
                $names = array_merge($names, $by_cid[$cid] ?? []);
            }

            $names = array_values(array_unique($names));

            if (count($names) === 1 && $codes === [] && ! isset($claimed[$names[0]])) {
                $plan['by_cid'][] = ['item' => $item, 'name' => $names[0]];
                $claimed[$names[0]] = true;

                continue;
            }

            $plan['unmatched'][] = $item;
        }

        return $plan;
    }

    /**
     * Compare the CIDs the provider and location posts carry with the listings Google has.
     *
     * @param  array<int, array{id: int, label: string, cids: array<int, string>}>  $providers  Providers whose "listings on Google" field says Yes.
     * @param  array<int, array{id: int, label: string, cids: array<int, string>}>  $posts  Every provider and location post with the CIDs it carries.
     * @param  array<int, array{account: string, listing: array<string, mixed>}>  $listings
     * @return array{providers_without_listing: array<int, string>, listings_without_cid: array<int, string>}
     */
    public static function cid_report(array $providers, array $posts, array $listings): array
    {
        $listing_cids = [];
        $known = [];

        foreach ($listings as $entry) {
            $cid = self::cid_from_maps_uri((string) ($entry['listing']['metadata']['mapsUri'] ?? ''));

            if ($cid !== null) {
                $listing_cids[$cid] = true;
            }
        }

        foreach ($posts as $post) {
            foreach ($post['cids'] as $cid) {
                $known[$cid] = true;
            }
        }

        $without_listing = [];

        foreach ($providers as $provider) {
            if (array_intersect_key(array_flip($provider['cids']), $listing_cids) === []) {
                $without_listing[] = $provider['label'];
            }
        }

        $without_cid = [];

        foreach ($listings as $entry) {
            $cid = self::cid_from_maps_uri((string) ($entry['listing']['metadata']['mapsUri'] ?? ''));

            if ($cid === null || ! isset($known[$cid])) {
                $without_cid[] = (string) ($entry['listing']['storeCode'] ?? $entry['listing']['name'] ?? '');
            }
        }

        return ['providers_without_listing' => $without_listing, 'listings_without_cid' => $without_cid];
    }

    /**
     * The key a review or error entry is kept under: its store code, or its reason when it has none.
     *
     * @param  array<string, mixed>  $entry
     */
    public static function attention_key(array $entry): string
    {
        $code = (string) ($entry['store_code'] ?? '');

        return $code !== '' ? $code : '~'.($entry['reason'] ?? '');
    }

    /**
     * The list of entries that need a person, after one more log entry.
     *
     * A review or error entry is kept, replacing any earlier one for the same store code, so a
     * run of routine entries cannot push it out. A later entry for that store code that settles
     * (nothing to change, skipped, or really created or updated) removes it. A dry-run entry
     * settles nothing, so it leaves the list alone.
     *
     * @param  array<string, array<string, mixed>>  $attention  Newest first, by key.
     * @param  array<string, mixed>  $entry  A log entry, with its time.
     * @return array<string, array<string, mixed>> Newest first, at most $cap entries.
     */
    public static function next_attention(array $attention, array $entry, int $cap): array
    {
        $key = self::attention_key($entry);
        $action = (string) ($entry['action'] ?? '');

        if (in_array($action, ['review', 'error'], true)) {
            // The union puts the new entry first and drops the older one for the same key.
            return array_slice([$key => $entry] + $attention, 0, $cap, true);
        }

        if (self::is_settled([$entry])) {
            unset($attention[$key]);
        }

        return $attention;
    }

    /**
     * Entries with the given action and silo. A null filter keeps everything.
     *
     * @param  array<int|string, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    public static function filter_entries(array $entries, ?string $action, ?string $silo): array
    {
        return array_values(array_filter($entries, fn (array $entry): bool => ($action === null || ($entry['action'] ?? '') === $action)
            && ($silo === null || ($entry['silo'] ?? '') === $silo)));
    }

    /**
     * The next midnight in Pacific time after a moment, as a Unix time.
     *
     * Google's daily quotas reset at midnight Pacific. The zone is named rather than offset, so the
     * answer is right on either side of the daylight saving change.
     */
    public static function next_pacific_reset(int $now): int
    {
        return (new DateTimeImmutable('@'.$now))
            ->setTimezone(new DateTimeZone('America/Los_Angeles'))
            ->modify('tomorrow')
            ->getTimestamp();
    }

    /**
     * The Pacific calendar day a moment falls on, as `Y-m-d`.
     */
    public static function pacific_day(int $now): string
    {
        return (new DateTimeImmutable('@'.$now))
            ->setTimezone(new DateTimeZone('America/Los_Angeles'))
            ->format('Y-m-d');
    }

    /**
     * A moment written for a person reading the log or the command line, in Pacific time.
     */
    public static function reset_label(int $moment): string
    {
        return (new DateTimeImmutable('@'.$moment))
            ->setTimezone(new DateTimeZone('America/Los_Angeles'))
            ->format('Y-m-d H:i T');
    }

    /**
     * Add a run's creates to the per-day history, keeping the most recent days.
     *
     * @param  array<mixed>  $history  Pacific day (`Y-m-d`) to creates made that day.
     * @return array<string, int>
     */
    public static function add_to_history(array $history, string $day, int $made, int $keep = 14): array
    {
        $clean = [];

        foreach ($history as $key => $count) {
            if (is_string($key) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $key) === 1 && is_numeric($count)) {
                $clean[$key] = max(0, (int) $count);
            }
        }

        $clean[$day] = ($clean[$day] ?? 0) + max(0, $made);
        ksort($clean);

        return array_slice($clean, -max(1, $keep), null, true);
    }

    /**
     * The creates made on each of the last Pacific days, oldest first and ending today.
     *
     * A day with no record is 0, so the list always has $days entries.
     *
     * @param  array<mixed>  $history  From self::add_to_history().
     * @return array<int, array{day: string, count: int}>
     */
    public static function last_days(array $history, int $now, int $days = 7): array
    {
        $zone = new DateTimeZone('America/Los_Angeles');
        $today = (new DateTimeImmutable('@'.$now))->setTimezone($zone)->setTime(12, 0);
        $out = [];

        for ($back = $days - 1; $back >= 0; $back--) {
            $day = $today->modify('-'.$back.' days')->format('Y-m-d');
            $out[] = ['day' => $day, 'count' => (int) ($history[$day] ?? 0)];
        }

        return $out;
    }

    /**
     * What the sync is doing, as one status for the dashboard.
     *
     * The first that applies wins: no silo in use is Off; missing credentials is Not configured; a run holding the lock is Running; a pause on
     * creates is Paused; a per-minute backoff is Backed off; every silo in use being a dry run is
     * Dry run; anything else is Idle.
     *
     * @param  array<string, string>  $effective_modes  Each silo's effective mode.
     * @param  bool  $configured  Whether the Google credentials are present. Without them a queued job does nothing.
     * @return array{key: string, label: string}
     */
    public static function dashboard_status(array $effective_modes, bool $running, int $paused_until, int $backoff_until, int $now, bool $configured = true): array
    {
        $in_use = array_values(array_diff(array_values($effective_modes), ['off']));

        if ($in_use === []) {
            return ['key' => 'off', 'label' => 'Off'];
        }

        if (! $configured) {
            return ['key' => 'unconfigured', 'label' => 'Not configured: the Google credentials are missing, so queued syncs do nothing'];
        }

        if ($running) {
            return ['key' => 'running', 'label' => 'Running'];
        }

        if ($paused_until > $now) {
            return ['key' => 'paused', 'label' => 'Creates paused until '.self::reset_label($paused_until).' (resets at Pacific midnight), '.self::remaining_label($paused_until - $now).' left'];
        }

        if ($backoff_until > $now) {
            return ['key' => 'backoff', 'label' => 'Backed off until '.self::reset_label($backoff_until).' (rate limit), '.self::remaining_label($backoff_until - $now).' left'];
        }

        if (array_diff($in_use, ['dry-run']) === []) {
            return ['key' => 'dry-run', 'label' => 'Dry run'];
        }

        return ['key' => 'idle', 'label' => 'Idle'];
    }

    /**
     * A span of seconds written for a person: `2 h 5 min`, `45 min` or `under 1 min`.
     */
    public static function remaining_label(int $seconds): string
    {
        $minutes = intdiv(max(0, $seconds), 60);

        if ($minutes < 1) {
            return 'under 1 min';
        }

        $hours = intdiv($minutes, 60);

        return $hours > 0 ? $hours.' h '.($minutes % 60).' min' : $minutes.' min';
    }

    /**
     * Sort the scheduled sync events into due now, waiting for the create reset, and the next 24 hours.
     *
     * An event at or after a pause's end, while the pause is in force, is waiting for the reset; one
     * before it, or any event when nothing is paused, is due now if its time has passed.
     *
     * @param  array<int, int>  $times  The scheduled time of each event.
     * @return array{total: int, due: int, waiting: int, hours: array<int, int>} `hours[0]` is the next hour.
     */
    public static function queue_summary(array $times, int $now, int $paused_until): array
    {
        $summary = ['total' => 0, 'due' => 0, 'waiting' => 0, 'hours' => array_fill(0, 24, 0)];

        foreach ($times as $time) {
            $summary['total']++;

            if ($paused_until > $now && $time >= $paused_until) {
                $summary['waiting']++;

                continue;
            }

            if ($time <= $now) {
                $summary['due']++;

                continue;
            }

            $hour = intdiv($time - $now, 3600);

            if ($hour < 24) {
                $summary['hours'][$hour]++;
            }
        }

        return $summary;
    }

    /**
     * Count the log entries from the last 24 hours by outcome.
     *
     * A create or update counts only when it was carried out (`Created` or `Updated`); a dry run does not.
     *
     * @param  array<int, array<string, mixed>>  $log
     * @return array{create: int, update: int, review: int, error: int}
     */
    public static function outcome_counts(array $log, int $now): array
    {
        $counts = ['create' => 0, 'update' => 0, 'review' => 0, 'error' => 0];

        foreach ($log as $entry) {
            if ((int) ($entry['time'] ?? 0) < $now - 86400) {
                continue;
            }

            $action = (string) ($entry['action'] ?? '');
            $reason = (string) ($entry['reason'] ?? '');

            if (($action === 'create' && $reason === 'Created') || ($action === 'update' && $reason === 'Updated') || $action === 'review' || $action === 'error') {
                $counts[$action]++;
            }
        }

        return $counts;
    }

    /**
     * Whether a run's outcome can be remembered as final for its fingerprint.
     *
     * A listing that matched, a skip decided from the items alone, and a write that succeeded
     * are final. A review (it depends on Google's state), an error, and a dry-run create or
     * update (nothing was written) are not, so the next save must run again.
     *
     * @param  array<int, array<string, mixed>>  $entries
     */
    public static function is_settled(array $entries): bool
    {
        foreach ($entries as $entry) {
            $action = $entry['action'] ?? '';
            $reason = $entry['reason'] ?? '';

            $settled = in_array($action, ['noop', 'skip'], true)
                || (in_array($action, ['create', 'update'], true) && in_array($reason, ['Created', 'Updated', 'Photos sent'], true));

            if (! $settled) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether a Yes/No field holds Yes.
     *
     * `get_field()` returns the stored string `'1'` for a button group's Yes, so a strict
     * comparison with `true` never matches. Anything but a stored Yes, including a field never
     * saved, is No.
     *
     * @param  mixed  $value
     */
    public static function is_yes($value): bool
    {
        return in_array($value, [true, 1, '1'], true);
    }

    /**
     * Turn one location table row into a sync item, or null when the row has no store code.
     *
     * A location whose own exclusion flag is Yes gets no row from the builder, so an item here
     * is never an excluded one.
     *
     * @param  array<int, mixed>  $row  A row from uamswp_fad_gmb_location_table().
     * @param  array<string, string>  $category_ids  Category display name => resource name.
     * @param  ?string  $silo  The location's own silo, or null when its brand has none.
     * @return ?array{store_code: string, kind: string, silo: ?string, excluded: bool, fields: array<string, mixed>, unresolved: array<int, string>, attributes: array<string, bool>, photos: array<int, array{category: string, url: string}>}
     */
    public static function location_item(array $row, array $category_ids, ?string $silo): ?array
    {
        $store_code = isset($row[0]) ? trim((string) $row[0]) : '';

        if ($store_code === '') {
            return null;
        }

        return [
            'store_code' => $store_code,
            'kind' => 'location',
            'silo' => $silo,
            'excluded' => false,
            'fields' => self::fields_from_row($row, $category_ids),
            'unresolved' => self::unresolved_categories($row, $category_ids),
            'attributes' => self::attributes_from_row($row),
            'photos' => self::photos_from_row($row),
        ];
    }

    /**
     * Location table columns that carry a Business Profile attribute, by column number.
     *
     * Every one is a true/false attribute, and each cell holds Yes, No, or something else
     * (empty, "[NOT APPLICABLE]") that means the site has no answer to give.
     */
    private const ATTRIBUTE_COLUMNS = [
        23 => 'has_wheelchair_accessible_entrance',
        24 => 'has_wheelchair_accessible_restroom',
        25 => 'has_restroom',
        26 => 'requires_appointments',
        52 => 'has_video_visits',
    ];

    /**
     * The attributes a location row states: true for Yes, false for No, and nothing for any
     * other cell, so the sync never turns an attribute off for want of an answer.
     *
     * @param  array<int, mixed>  $row  A row from uamswp_fad_gmb_location_table().
     * @return array<string, bool> Attribute name (without the `attributes/` prefix) => value.
     */
    public static function attributes_from_row(array $row): array
    {
        $attributes = [];

        foreach (self::ATTRIBUTE_COLUMNS as $column => $name) {
            $cell = isset($row[$column]) ? strtolower(trim((string) $row[$column])) : '';

            if ($cell === 'yes') {
                $attributes[$name] = true;
            } elseif ($cell === 'no') {
                $attributes[$name] = false;
            }
        }

        return $attributes;
    }

    /**
     * The desired attributes that are missing from the listing or hold another value.
     *
     * @param  array<string, bool>  $desired
     * @param  array<string, bool>  $live  From UAMSWP_FAD_GBP_Client::attributes().
     * @return array<string, bool>
     */
    public static function changed_attributes(array $desired, array $live): array
    {
        return array_filter($desired, static fn (bool $value, string $name): bool => ! array_key_exists($name, $live) || $live[$name] !== $value, ARRAY_FILTER_USE_BOTH);
    }
}
