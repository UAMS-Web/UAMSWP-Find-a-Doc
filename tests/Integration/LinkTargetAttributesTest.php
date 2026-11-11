<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * The "more" button target attributes in the providers, locations and clinical resources blocks.
 *
 * @command  composer test:integration -- --filter LinkTargetAttributesTest
 */
final class LinkTargetAttributesTest extends IntegrationTestCase
{
    public function test_blank_target_prints_target_and_noopener(): void
    {
        self::assertSame(' target="_blank" rel="noopener"', uams_fad_link_target_attributes('_blank'));
    }

    public function test_other_target_prints_target_without_rel(): void
    {
        self::assertSame(' target="_self"', uams_fad_link_target_attributes('_self'));
    }

    public function test_unset_empty_or_non_string_target_prints_nothing(): void
    {
        foreach ([null, '', false, [], 0] as $target) {
            self::assertSame('', uams_fad_link_target_attributes($target), var_export($target, true));
        }
    }

    public function test_target_value_is_escaped(): void
    {
        self::assertSame(' target="&quot; onclick=&quot;x"', uams_fad_link_target_attributes('" onclick="x'));
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function blockTemplates(): array
    {
        return [
            'providers' => ['providers'],
            'locations' => ['locations'],
            'clinical resources' => ['clinical-resources'],
        ];
    }

    /**
     * @dataProvider blockTemplates
     */
    public function test_more_button_anchor_echoes_the_target_attributes(string $block): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2)."/templates/blocks/{$block}.php");

        // The anchor must print the helper's result, not discard it.
        self::assertMatchesRegularExpression(
            '/aria-label="<\?php echo esc_attr\(\$more_button_description_attr\); \?>"<\?php echo uams_fad_link_target_attributes\(\$more_button_target \?\? null\); \?>>/',
            $source,
        );
    }
}
