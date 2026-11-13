<?php

/**
 * The PHP type `get_field()` returns for each Advanced Custom Fields field, read from the field group JSON.
 */

declare(strict_types=1);

namespace UamswpFindADoc\PhpStan;

use JsonException;
use RuntimeException;

/**
 * Builds the map that `AcfReturnTypeExtension` answers from.
 *
 * Types follow what `get_field()` gives back with formatting on: text-like fields are strings, a relationship is a
 * list of IDs, a repeater a list of rows. Every type except a boolean also allows `false` and `null`, which is what
 * ACF returns for a field that was never saved. A name that two field groups declare with different types is left
 * out, so a read of it stays `mixed` instead of being given one of the two.
 */
final class AcfFieldTypes
{
    /**
     * @param  array<int, mixed>  $groups  Decoded field group files.
     * @return array<string, string> Field name to PHPStan type string, sorted by name.
     */
    public static function fromGroups(array $groups): array
    {
        /** @var array<string, array<string, true>> $found */
        $found = [];

        foreach ($groups as $group) {
            if (is_array($group)) {
                self::collect(is_array($group['fields'] ?? null) ? $group['fields'] : [], $found);
            }
        }

        $types = [];

        foreach ($found as $name => $candidates) {
            if (count($candidates) === 1) {
                $types[$name] = (string) array_key_first($candidates);
            }
        }

        ksort($types);

        return $types;
    }

    /**
     * Read every `group_*.json` file in a directory.
     *
     * @return array<string, string>
     */
    public static function fromDirectory(string $directory): array
    {
        $files = glob(rtrim($directory, '/').'/group_*.json');

        if ($files === false || $files === []) {
            throw new RuntimeException('No field group files in '.$directory);
        }

        sort($files);
        $groups = [];

        foreach ($files as $file) {
            try {
                $groups[] = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $jsonException) {
                throw new RuntimeException('Cannot read '.$file.': '.$jsonException->getMessage(), 0, $jsonException);
            }
        }

        return self::fromGroups($groups);
    }

    /**
     * The type of one field as `get_field()` returns it, or null when the field type is not mapped.
     *
     * @param  array<mixed>  $field
     */
    public static function typeOf(array $field): ?string
    {
        $type = $field['type'] ?? null;
        $format = $field['return_format'] ?? null;
        $multiple = ! empty($field['multiple']);

        $base = match ($type) {
            'text', 'textarea', 'wysiwyg', 'url', 'email', 'password', 'maskfield', 'oembed', 'date_picker' => 'string',
            'select', 'radio', 'button_group' => $format === 'array' ? 'array{value: string, label: string}' : ($multiple ? 'array<int, string>' : 'string'),
            'true_false' => 'bool',
            'number' => 'int|float|string',
            'relationship' => 'array<int, int|string>',
            'post_object' => $multiple ? 'array<int, int|string>' : 'int|string',
            'taxonomy' => in_array($field['field_type'] ?? null, ['radio', 'select'], true) ? 'int|string' : 'array<int, int|string>',
            'image' => 'int|string',
            'link' => 'array{title: string, url: string, target: string}',
            'repeater', 'gallery' => 'array<int, mixed>',
            'group', 'google_map' => 'array<string, mixed>',
            default => null,
        };

        if ($base === null) {
            return null;
        }

        return $base === 'bool' ? 'bool|null' : $base.'|false|null';
    }

    /**
     * @param  array<mixed>  $fields
     * @param  array<string, array<string, true>>  $found
     */
    private static function collect(array $fields, array &$found): void
    {
        foreach ($fields as $field) {
            if (! is_array($field)) {
                continue;
            }

            $name = $field['name'] ?? '';
            $type = self::typeOf($field);

            if (is_string($name) && $name !== '' && $type !== null) {
                $found[$name][$type] = true;
            }

            self::collect(is_array($field['sub_fields'] ?? null) ? $field['sub_fields'] : [], $found);

            foreach (is_array($field['layouts'] ?? null) ? $field['layouts'] : [] as $layout) {
                self::collect(is_array($layout) && is_array($layout['sub_fields'] ?? null) ? $layout['sub_fields'] : [], $found);
            }
        }
    }
}
