<?php

/**
 * A provider preview reads the saved provider's fields, not its latest revision's.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

/**
 * ACF swaps the post ID for the latest revision's ID during a preview, and a revision has no row in the
 * provider custom table (class.fad-acf-functions.php turns off the copy into core post meta). The filter
 * undoes the swap for a provider or a location; see includes/class.fad-provider-preview.php.
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
        $_GET['p'] = '89';
    }

    protected function tearDown(): void
    {
        unset($_GET['preview'], $_GET['p'], $_GET['preview_id'], $_GET['page_id']);
        parent::tearDown();
    }

    public function test_a_providers_revision_id_becomes_the_saved_providers_id(): void
    {
        when('wp_is_post_revision')->justReturn(89);
        when('get_post_type')->justReturn('provider');

        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412, 89));
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id('412', 89), 'ACF can hand the ID over as a string');
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412), 'the original ID is not needed');
    }

    #[DataProvider('listed_post_types')]
    public function test_a_listed_post_types_revision_id_becomes_the_saved_posts_id(string $post_type): void
    {
        when('wp_is_post_revision')->justReturn(89);
        when('get_post_type')->justReturn($post_type);

        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412, 89));
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id('412', 89), 'ACF can hand the ID over as a string');
    }

    /**
     * @return array<string, array{string}>
     */
    public static function listed_post_types(): array
    {
        return [
            'provider' => ['provider'],
            'location' => ['location'],
            'area of expertise' => ['expertise'],
            'condition' => ['condition'],
            'treatment' => ['treatment'],
            'clinical resource' => ['clinical-resource'],
        ];
    }

    public function test_the_listed_post_types_are_the_custom_post_types_with_revisions(): void
    {
        $this->assertSame(['provider', 'location', 'expertise', 'condition', 'treatment', 'clinical-resource'], uamswp_fad_preview_saved_post_types());
    }

    public function test_the_previewed_post_is_named_by_preview_id_then_p_then_page_id(): void
    {
        when('wp_is_post_revision')->justReturn(89);
        when('get_post_type')->justReturn('provider');

        $_GET['preview_id'] = '89';
        unset($_GET['p']);
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412));

        unset($_GET['preview_id']);
        $_GET['page_id'] = '89';
        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(412));
    }

    public function test_a_revision_of_another_post_is_not_remapped_even_on_a_preview_request(): void
    {
        when('wp_is_post_revision')->justReturn(55);
        when('get_post_type')->justReturn('provider');

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412, 55), 'the previewed post is 89, not 55');
    }

    public function test_the_saved_post_is_left_alone(): void
    {
        when('wp_is_post_revision')->justReturn(false);
        expect('get_post_type')->never();

        $this->assertSame(89, uamswp_fad_preview_keep_saved_post_id(89, 89));
    }

    public function test_another_post_types_revision_is_left_to_acf(): void
    {
        when('wp_is_post_revision')->justReturn(55);
        when('get_post_type')->justReturn('page');

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412, 55));
    }

    public function test_an_id_that_is_not_a_post_id_passes_through(): void
    {
        expect('wp_is_post_revision')->never();

        $this->assertSame('option', uamswp_fad_preview_keep_saved_post_id('option', 'option'));
        $this->assertSame('term_3', uamswp_fad_preview_keep_saved_post_id('term_3', 'term_3'));
    }

    public function test_a_request_that_is_not_a_preview_is_not_looked_up(): void
    {
        unset($_GET['preview']);
        expect('wp_is_post_revision')->never();

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412, 89));
    }

    public function test_the_filter_is_registered_after_acfs_own_handler(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-acf-functions.php');

        $this->assertStringContainsString("add_filter('acf/validate_post_id', 'uamswp_fad_preview_keep_saved_post_id', 20, 2);", $source, 'ACF registers its handler at priority 10');
        // The registration itself, and its priority, are checked against real WordPress in
        // tests/Integration/ProviderPreviewRevisionTest.php.
    }

    public function test_the_values_are_kept_out_of_core_post_meta_so_a_revision_cannot_carry_them(): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/includes/class.fad-acf-functions.php');

        $this->assertStringContainsString("add_filter('acfcdt/settings/store_acf_values_in_core_meta', '__return_false');", $source);
    }

    public function test_the_revision_is_looked_up_by_the_number_of_an_id_given_as_a_string(): void
    {
        $asked = [];
        when('wp_is_post_revision')->alias(static function ($id) use (&$asked): int {
            $asked[] = $id;

            return 89;
        });
        when('get_post_type')->justReturn('provider');

        uamswp_fad_preview_keep_saved_post_id('412', 89);

        $this->assertSame([412], $asked);
    }

    public function test_a_preview_with_no_post_named_in_the_request_keeps_the_id_even_for_post_one(): void
    {
        unset($_GET['p']);
        when('wp_is_post_revision')->justReturn(1);
        when('get_post_type')->justReturn('provider');

        $this->assertSame(412, uamswp_fad_preview_keep_saved_post_id(412));
    }
}
