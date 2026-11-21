<?php

/**
 * The store code of a listing whose slug code is over Google's limit: built from post IDs instead.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use UAMSWP_FAD_GBP_Logic;
use UAMSWP_FAD_GBP_Sync;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpIdStoreCodeTest extends UnitTestCase
{
    /**
     * @return array<string, array{0: int}>
     */
    public static function lengths_within_the_limit(): array
    {
        return ['three characters' => [3], 'exactly the limit' => [UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX]];
    }

    #[DataProvider('lengths_within_the_limit')]
    public function test_a_provider_slug_code_within_the_limit_is_kept_byte_for_byte(int $length): void
    {
        $code = str_repeat('a', $length - 2).'_b';
        $this->assertSame($length, strlen($code));

        $this->assertSame($code, UAMSWP_FAD_GBP_Logic::provider_store_code($code, 101, 202));
    }

    public function test_a_provider_slug_code_one_over_the_limit_becomes_the_id_code(): void
    {
        $code = str_repeat('a', UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX - 1).'_b';
        $this->assertSame(UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX + 1, strlen($code));

        $this->assertSame('id--p101-l202', UAMSWP_FAD_GBP_Logic::provider_store_code($code, 101, 202));
    }

    #[DataProvider('lengths_within_the_limit')]
    public function test_a_location_slug_code_within_the_limit_is_kept_byte_for_byte(int $length): void
    {
        $code = str_repeat('a', $length - 2).'_b';

        $this->assertSame($code, UAMSWP_FAD_GBP_Logic::location_store_code($code, 303));
    }

    public function test_a_child_location_slug_code_over_the_limit_becomes_the_id_code_of_the_child(): void
    {
        $code = str_repeat('p', 40).'_'.str_repeat('c', 40);

        $this->assertSame('id--l303', UAMSWP_FAD_GBP_Logic::location_store_code($code, 303));
    }

    public function test_an_id_code_is_within_the_limit_even_for_the_largest_ids(): void
    {
        $code = UAMSWP_FAD_GBP_Logic::provider_store_code(str_repeat('a', 80), PHP_INT_MAX, PHP_INT_MAX);

        $this->assertLessThanOrEqual(UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX, strlen($code));
        $this->assertLessThanOrEqual(UAMSWP_FAD_GBP_Sync::STORE_CODE_MAX, strlen(UAMSWP_FAD_GBP_Logic::location_store_code(str_repeat('a', 80), PHP_INT_MAX)));
    }

    public function test_each_provider_and_location_pair_gets_its_own_id_code(): void
    {
        $long = str_repeat('a', 80);
        $codes = [];

        foreach ([[1, 23], [12, 3], [123, 4], [4, 123], [1, 2]] as [$provider, $location]) {
            $codes[] = UAMSWP_FAD_GBP_Logic::provider_store_code($long, $provider, $location);
        }

        $this->assertCount(5, array_unique($codes), 'a joined digit run cannot be read two ways');
        $this->assertNotSame(UAMSWP_FAD_GBP_Logic::provider_store_code($long, 5, 5), UAMSWP_FAD_GBP_Logic::location_store_code($long, 5));
    }

    public function test_no_id_code_equals_a_slug_code(): void
    {
        // WordPress collapses each run of hyphens in a slug to one, so a slug code (slugs joined by an underscore) has no double hyphen.
        $slugs = ['a', 'dr-jane-doe', 'uams-baptist-health-ear-nose-and-throat-clinic', 'x-1', 'p1', 'id', 'l1-l2', 'id-p1-l2'];
        $slug_codes = [];

        foreach ($slugs as $provider) {
            foreach ($slugs as $location) {
                $slug_codes[] = $provider.'_'.$location;
            }
        }

        $slug_codes = array_merge($slug_codes, $slugs);

        foreach ($slug_codes as $slug_code) {
            $this->assertStringNotContainsString('--', $slug_code);
        }

        $id_codes = [UAMSWP_FAD_GBP_Logic::provider_store_code(str_repeat('a', 80), 1, 2), UAMSWP_FAD_GBP_Logic::location_store_code(str_repeat('a', 80), 3)];

        foreach ($id_codes as $id_code) {
            $this->assertStringContainsString('--', $id_code);
            $this->assertNotContains($id_code, $slug_codes);
        }
    }

    public function test_an_id_provider_code_splits_back_into_its_two_post_ids(): void
    {
        $code = UAMSWP_FAD_GBP_Logic::provider_store_code(str_repeat('a', 80), 4821, 17);

        $this->assertSame([4821, 17], UAMSWP_FAD_GBP_Logic::split_id_provider_store_code($code));
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function not_id_provider_codes(): array
    {
        return [
            'a slug code' => ['dr-jane_main-clinic'],
            'an empty string' => [''],
            'a number' => [5],
            'a location id code' => ['id--l5'],
            'zero provider id' => ['id--p0-l5'],
            'zero location id' => ['id--p5-l0'],
            'leading zero' => ['id--p05-l5'],
            'no location' => ['id--p5-l'],
            'trailing text' => ['id--p5-l5x'],
            'leading text' => ['xid--p5-l5'],
            'upper case' => ['ID--p5-l5'],
        ];
    }

    #[DataProvider('not_id_provider_codes')]
    public function test_a_code_not_of_the_id_provider_form_does_not_split(mixed $code): void
    {
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_id_provider_store_code($code));
    }

    public function test_a_review_for_an_id_store_code_stays_under_needs_attention_until_a_later_entry_for_it_settles(): void
    {
        $code = UAMSWP_FAD_GBP_Logic::provider_store_code(str_repeat('a', 80), 4821, 17);
        $this->assertSame('id--p4821-l17', $code, 'the code a builder would give a provider over the limit');
        $review = ['store_code' => $code, 'silo' => 'uams', 'action' => 'review', 'reason' => 'Store code is on 2 listings'];

        $attention = UAMSWP_FAD_GBP_Logic::next_attention([], $review, 10);

        $this->assertSame([$code], array_column($attention, 'store_code'));

        $other = UAMSWP_FAD_GBP_Logic::next_attention($attention, ['store_code' => 'id--p1-l2', 'silo' => 'uams', 'action' => 'skip'], 10);
        $this->assertSame([$code], array_column($other, 'store_code'), 'an entry for another code leaves it');

        $settled = UAMSWP_FAD_GBP_Logic::next_attention($attention, ['store_code' => $code, 'silo' => 'uams', 'action' => 'skip'], 10);
        $this->assertSame([], $settled);
    }

    public function test_a_slug_provider_code_never_reads_as_an_id_code_and_an_id_code_never_as_a_slug_code(): void
    {
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_id_provider_store_code('dr-jane_main-clinic'));
        $this->assertNull(UAMSWP_FAD_GBP_Logic::split_provider_store_code('id--p5-l9'), 'no underscore, so the slug split rejects it');
    }
}
