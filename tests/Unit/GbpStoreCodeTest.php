<?php

/**
 * How a Business Profile store code is built. The 64-character limit is enforced by the sync, not here.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use UamswpFindADoc\Tests\Support\UnitTestCase;

/**
 * The two table builders assemble a store code inline, inside functions that need a full WordPress
 * to run, so these tests read the source of each builder, as `GbpProviderTitleTest` does. Where a
 * running pin is possible, `GbpSyncTest` holds it: a code over 64 characters is a review and sends nothing.
 */
final class GbpStoreCodeTest extends UnitTestCase
{
    private function builder_source(string $function): string
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-gmb-settings-page.php');

        $start = strpos($source, 'function '.$function.'(');
        $this->assertNotFalse($start, "{$function} exists");

        $end = strpos($source, "\nfunction ", $start + 1);

        return substr($source, $start, $end === false ? null : $end - $start);
    }

    public function test_a_provider_store_code_is_the_provider_slug_then_the_location_slug(): void
    {
        $source = $this->builder_source('uamswp_fad_gmb_provider_table');

        $this->assertStringContainsString('$store_code = UAMSWP_FAD_GBP_Logic::provider_store_code($profile_slug.\'_\'.$location_slug, $post_id, uamswp_fad_int($location));', $source);
        $this->assertStringContainsString('$row[0] = $store_code;', $source);
    }

    public function test_a_child_location_store_code_is_the_parent_slug_then_the_child_slug(): void
    {
        $source = $this->builder_source('uamswp_fad_gmb_location_table');

        $this->assertStringContainsString("\$store_code = UAMSWP_FAD_GBP_Logic::location_store_code((\$location_parent_location ? get_post_field('post_name', \$location_post_id).'_' : '').get_post_field('post_name', \$location_child_id), uamswp_fad_int(\$location_child_id));", $source);
        $this->assertStringContainsString('$row[0] = $store_code;', $source);
    }

    public function test_neither_builder_measures_the_store_code_itself_because_the_logic_class_does(): void
    {
        foreach (['uamswp_fad_gmb_provider_table', 'uamswp_fad_gmb_location_table'] as $function) {
            $source = $this->builder_source($function);

            $this->assertDoesNotMatchRegularExpression('/(?:mb_)?strlen\(\s*\$store_code\b/', $source, "{$function} sends the code as built");
            $this->assertDoesNotMatchRegularExpression('/(?:mb_)?substr\(\s*\$store_code\b/', $source, "{$function} does not truncate the code");
        }
    }
}
