<?php

/**
 * A provider preview reads the saved provider's fields, not its latest revision's.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use UamswpFindADoc\Tests\Support\UnitTestCase;

/**
 * ACF swaps the post ID for the latest revision's ID during a preview, and a revision has no row in the
 * provider custom table (class.fad-acf-functions.php turns off the copy into core post meta). The filter
 * undoes the swap for a provider; see includes/class.fad-provider-preview.php.
 */
final class ProviderPreviewTest extends UnitTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        require_once dirname(__DIR__, 2).'/includes/class.fad-provider-preview.php';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $_GET['preview'] = 'true';
    }

    protected function tearDown(): void
    {
        unset($_GET['preview']);
        parent::tearDown();
    }

    public function test_a_providers_revision_id_becomes_the_saved_providers_id(): void
    {
        Functions\when('wp_is_post_revision')->justReturn(89);
        Functions\when('get_post_type')->justReturn('provider');

        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412, 89));
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id('412', 89), 'ACF can hand the ID over as a string');
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412), 'the original ID is not needed');
    }

    public function test_the_saved_post_is_left_alone(): void
    {
        Functions\when('wp_is_post_revision')->justReturn(false);
        Functions\expect('get_post_type')->never();

        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(89, 89));
    }

    public function test_another_post_types_revision_is_left_to_acf(): void
    {
        Functions\when('wp_is_post_revision')->justReturn(55);
        Functions\when('get_post_type')->justReturn('page');

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412, 55));
    }

    public function test_an_id_that_is_not_a_post_id_passes_through(): void
    {
        Functions\expect('wp_is_post_revision')->never();

        $this->assertSame('option', uamswp_fad_preview_keep_saved_post_id('option', 'option'));
        $this->assertSame('term_3', uamswp_fad_preview_keep_saved_post_id('term_3', 'term_3'));
    }

    public function test_a_request_that_is_not_a_preview_is_not_looked_up(): void
    {
        unset($_GET['preview']);
        Functions\expect('wp_is_post_revision')->never();

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412, 89));
    }

    public function test_the_filter_runs_after_acfs_own_handler(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-acf-functions.php');

        $this->assertStringContainsString("add_filter('acf/validate_post_id', 'uamswp_fad_preview_keep_saved_post_id', 20, 2);", $source, 'ACF registers its handler at priority 10');
    }

    public function test_the_values_are_kept_out_of_core_post_meta_so_a_revision_cannot_carry_them(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-acf-functions.php');

        $this->assertStringContainsString("add_filter('acfcdt/settings/store_acf_values_in_core_meta', '__return_false');", $source);
    }
}
