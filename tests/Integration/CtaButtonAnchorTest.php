<?php

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * The call-to-action button on a condition page prints the target stored on its link field.
 *
 * @command  composer test:integration -- --filter CtaButtonAnchorTest
 */
final class CtaButtonAnchorTest extends IntegrationTestCase
{
    public function test_blank_target_prints_target_and_noopener(): void
    {
        $html = uams_fad_cta_button_anchor(['url' => 'https://example.org/a', 'target' => '_blank'], 'Go', 'Go to A', 'primary', 'normal', 'Heading');

        self::assertSame(
            '<a href="https://example.org/a" aria-label="Go to A" class=" btn btn-primary" target="_blank" rel="noopener" data-moduletitle="Heading">Go</a>',
            $html,
        );
    }

    public function test_link_without_a_target_prints_no_target_attribute(): void
    {
        foreach ([['url' => 'https://example.org/a'], ['url' => 'https://example.org/a', 'target' => '']] as $link) {
            $html = uams_fad_cta_button_anchor($link, 'Go', 'Go to A', 'primary', 'normal', 'Heading');

            self::assertStringNotContainsString('target=', $html);
            self::assertStringNotContainsString('rel=', $html);
        }
    }

    public function test_large_size_adds_the_large_button_class(): void
    {
        $html = uams_fad_cta_button_anchor(['url' => 'https://example.org/a'], 'Go', 'Go to A', 'primary', 'large', 'Heading');

        self::assertStringContainsString('class=" btn btn-primary btn-lg"', $html);
    }

    public function test_an_unset_link_renders_an_empty_href_without_a_warning(): void
    {
        foreach ([false, null, ''] as $link) {
            $html = uams_fad_cta_button_anchor($link, 'Go', 'Go to A', 'primary', 'normal', 'Heading');

            self::assertStringContainsString('href=""', $html, var_export($link, true));
        }
    }

    public function test_the_condition_template_builds_its_button_from_the_link_field(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/templates/single-condition.php');

        self::assertStringContainsString('uams_fad_cta_button_anchor($cta_button_url,', $source);
        self::assertStringNotContainsString('$button_url[', $source, 'the undefined $button_url must not be read');
    }
}
