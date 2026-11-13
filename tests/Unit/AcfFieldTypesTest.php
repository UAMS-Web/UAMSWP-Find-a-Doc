<?php

/**
 * The PHPStan type map for `get_field()` (phpstan/AcfFieldTypes.php, phpstan/acf-field-types.php) and the extension
 * that answers from it (phpstan/AcfReturnTypeExtension.php).
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use UamswpFindADoc\PhpStan\AcfFieldTypes;

final class AcfFieldTypesTest extends TestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: string|null}>
     */
    public static function fieldTypes(): array
    {
        return [
            'text' => [['type' => 'text'], 'string|false|null'],
            'wysiwyg' => [['type' => 'wysiwyg'], 'string|false|null'],
            'a single select' => [['type' => 'select', 'return_format' => 'value'], 'string|false|null'],
            'a multiple select' => [['type' => 'select', 'multiple' => 1], 'array<int, string>|false|null'],
            'a select returning label and value' => [['type' => 'select', 'return_format' => 'array'], 'array{value: string, label: string}|false|null'],
            'true or false' => [['type' => 'true_false'], 'bool|null'],
            'a number' => [['type' => 'number'], 'int|float|string|false|null'],
            'a relationship' => [['type' => 'relationship'], 'array<int, int|string>|false|null'],
            'a single post object' => [['type' => 'post_object', 'multiple' => 0], 'int|string|false|null'],
            'a multiple post object' => [['type' => 'post_object', 'multiple' => 1], 'array<int, int|string>|false|null'],
            'a radio taxonomy' => [['type' => 'taxonomy', 'field_type' => 'radio'], 'int|string|false|null'],
            'a select taxonomy' => [['type' => 'taxonomy', 'field_type' => 'select'], 'int|string|false|null'],
            'a checkbox taxonomy' => [['type' => 'taxonomy', 'field_type' => 'checkbox'], 'array<int, int|string>|false|null'],
            'a multi select taxonomy' => [['type' => 'taxonomy', 'field_type' => 'multi_select'], 'array<int, int|string>|false|null'],
            'an image' => [['type' => 'image'], 'int|string|false|null'],
            'a link' => [['type' => 'link'], 'array{title: string, url: string, target: string}|false|null'],
            'a repeater' => [['type' => 'repeater'], 'array<int, mixed>|false|null'],
            'a gallery' => [['type' => 'gallery'], 'array<int, mixed>|false|null'],
            'a group' => [['type' => 'group'], 'array<string, mixed>|false|null'],
            'a map' => [['type' => 'google_map'], 'array<string, mixed>|false|null'],
            'a field type nothing maps' => [['type' => 'tab'], null],
        ];
    }

    /**
     * @param  array<string, mixed>  $field
     */
    #[DataProvider('fieldTypes')]
    public function test_each_field_type_maps_to_what_get_field_returns(array $field, ?string $expected): void
    {
        $this->assertSame($expected, AcfFieldTypes::typeOf($field));
    }

    public function test_fields_are_read_at_every_depth_and_a_name_with_two_types_is_left_out(): void
    {
        $groups = [
            ['fields' => [
                ['name' => 'plain', 'type' => 'text'],
                ['name' => 'rows', 'type' => 'repeater', 'sub_fields' => [['name' => 'inner', 'type' => 'number']]],
                ['name' => 'blocks', 'type' => 'flexible_content', 'layouts' => ['a' => ['sub_fields' => [['name' => 'in_layout', 'type' => 'url']]]]],
                ['name' => 'same', 'type' => 'text'],
                ['name' => 'clash', 'type' => 'text'],
                ['name' => 'tab_only', 'type' => 'tab'],
                ['name' => '', 'type' => 'text'],
            ]],
            ['fields' => [
                ['name' => 'same', 'type' => 'text'],
                ['name' => 'clash', 'type' => 'number'],
            ]],
            'not a group',
        ];

        $this->assertSame([
            'in_layout' => 'string|false|null',
            'inner' => 'int|float|string|false|null',
            'plain' => 'string|false|null',
            'rows' => 'array<int, mixed>|false|null',
            'same' => 'string|false|null',
        ], AcfFieldTypes::fromGroups($groups));
    }

    public function test_a_directory_without_field_groups_is_an_error(): void
    {
        $this->expectException(\RuntimeException::class);

        AcfFieldTypes::fromDirectory(sys_get_temp_dir().'/uamswp-no-such-field-groups');
    }

    public function test_the_committed_map_is_what_the_field_group_files_produce(): void
    {
        $committed = require $this->root().'/phpstan/acf-field-types.php';

        $this->assertIsArray($committed);
        $this->assertSame(
            AcfFieldTypes::fromDirectory($this->root().'/assets/json/acf-json'),
            $committed,
            'Run: php scripts/generate-acf-field-types.php'
        );
    }

    public function test_the_generator_check_passes_for_the_committed_map(): void
    {
        exec(escapeshellarg(PHP_BINARY).' '.escapeshellarg($this->root().'/scripts/generate-acf-field-types.php').' --check 2>&1', $output, $status);

        $this->assertSame(0, $status, implode("\n", $output));
    }

    public function test_phpstan_types_a_get_field_read_from_the_map_and_leaves_the_rest_mixed(): void
    {
        $directory = sys_get_temp_dir().'/uamswp-acf-types-'.bin2hex(random_bytes(4));
        mkdir($directory);

        try {
            file_put_contents($directory.'/probe.php', <<<'PHP_WRAP'
            <?php
            function get_field(mixed $selector, mixed $post_id = false, bool $format_value = true): mixed {}
            $formatted = get_field('physician_degree');
            \PHPStan\dumpType($formatted);
            $text = get_field('physician_first_name', 12);
            \PHPStan\dumpType($text);
            $raw = get_field('physician_degree', 12, false);
            \PHPStan\dumpType($raw);
            $unknown = get_field('no_such_field_zzz');
            \PHPStan\dumpType($unknown);
            $name = 'physician_degree';
            $byConstantVariable = get_field($name);
            \PHPStan\dumpType($byConstantVariable);
            $dynamic = (string) ($_GET['field'] ?? '');
            $byDynamicName = get_field($dynamic);
            \PHPStan\dumpType($byDynamicName);
            PHP_WRAP);
            file_put_contents($directory.'/phpstan.neon', "parameters:\n    level: 10\nservices:\n    -\n        class: UamswpFindADoc\\PhpStan\\AcfReturnTypeExtension\n        tags:\n            - phpstan.broker.dynamicFunctionReturnTypeExtension\n");

            $working_directory = getcwd();
            chdir($this->root());
            exec(escapeshellarg(PHP_BINARY).' '.escapeshellarg($this->root().'/vendor/phpstan/phpstan/phpstan').' analyse --no-progress --memory-limit=1G --error-format=json -c '.escapeshellarg($directory.'/phpstan.neon').' '.escapeshellarg($directory.'/probe.php').' 2>&1', $output, $status);
            chdir($working_directory === false ? $this->root() : $working_directory);

            $json = strstr(implode("\n", $output), '{"totals"');
            $report = json_decode($json === false ? '' : $json, true);
            $this->assertIsArray($report, implode("\n", $output));
            $types = [];

            foreach ($report['files'] as $file) {
                foreach ($file['messages'] as $message) {
                    if ($message['identifier'] === 'phpstan.dumpType') {
                        $types[$message['line']] = str_replace('Dumped type: ', '', $message['message']);
                    }
                }
            }

            $this->assertSame([
                4 => 'array<int, int|string>|false|null',
                6 => 'string|false|null',
                8 => 'mixed',
                10 => 'mixed',
                13 => 'array<int, int|string>|false|null',
                16 => 'mixed',
            ], $types);
        } finally {
            array_map(unlink(...), glob($directory.'/*') ?: []);
            rmdir($directory);
        }
    }
}
