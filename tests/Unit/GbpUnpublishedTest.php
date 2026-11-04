<?php

/**
 * Unit tests pinning that the Business Profile cron job skips a post that is not published.
 *
 * A provider or location that is not published is dropped before any decision is made, so its
 * existing listings stay live and unchanged and nothing is logged. Matching a listing by a stored
 * ID and closing the listings of unpublished content both change this path.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use Brain\Monkey\Functions;
use PHPUnit\Framework\Attributes\DataProvider;
use UamswpFindADoc\Tests\Support\UnitTestCase;

final class GbpUnpublishedTest extends UnitTestCase
{
    private const CREDENTIAL_NAMES = ['GOOGLE_BUSINESS_PROFILE_CLIENT_ID', 'GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', 'GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN'];

    /** @var array<string, string|false> */
    private $saved_env = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Real credentials, so the status check is the only reason the job can return early.
        foreach (self::CREDENTIAL_NAMES as $name) {
            $this->saved_env[$name] = getenv($name);
            putenv($name.'=test-value');
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->saved_env as $name => $value) {
            putenv($value === false ? $name : $name.'='.$value);
        }

        parent::tearDown();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function not_published(): array
    {
        return [
            'draft' => ['draft'],
            'pending' => ['pending'],
            'private' => ['private'],
            'future' => ['future'],
            'trash' => ['trash'],
            'a post that no longer exists' => [''],
        ];
    }

    #[DataProvider('not_published')]
    public function test_a_post_that_is_not_published_is_skipped_with_no_lock_no_write_and_no_log_entry(string $status): void
    {
        Functions\when('get_post_status')->justReturn($status === '' ? false : $status);
        Functions\expect('get_transient')->never();
        Functions\expect('set_transient')->never();
        Functions\expect('wp_schedule_single_event')->never();
        Functions\expect('update_option')->never();
        Functions\expect('update_post_meta')->never();
        Functions\expect('get_posts')->never();

        uamswp_fad_gbp_run_post(100);

        $this->addToAssertionCount(1);
    }

    public function test_a_published_post_gets_past_the_status_check(): void
    {
        // The control for the tests above: the same stubs with a published post do reach the lock.
        Functions\when('get_post_status')->justReturn('publish');
        Functions\expect('get_transient')->once()->with('uamswp_fad_gbp_lock')->andReturn(1);
        Functions\expect('wp_schedule_single_event')->once()->andReturn(true);

        uamswp_fad_gbp_run_post(100);

        $this->addToAssertionCount(1);
    }
}
