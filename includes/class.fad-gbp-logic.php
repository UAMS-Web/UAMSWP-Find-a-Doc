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
    public static function resolve_silo($brand_query, $brand_org, array $silos)
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
                    'addressLines' => array_values(array_map('trim', (array) ($value['addressLines'] ?? []))),
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
                || (in_array($action, ['create', 'update'], true) && in_array($reason, ['Created', 'Updated'], true));

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
     * @return ?array{store_code: string, kind: string, silo: ?string, excluded: bool, fields: array<string, mixed>, unresolved: array<int, string>, attributes: array<string, bool>}
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
