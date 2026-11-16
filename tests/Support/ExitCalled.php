<?php

/**
 * Thrown by the Unit suite's default stand-in for `uamswp_fad_exit()`.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Support;

use RuntimeException;

use function Brain\Monkey\Functions\when;

/**
 * A test reached the plugin's `uamswp_fad_exit()`.
 *
 * The real function ends the process, which ends the test run with no report. In the Unit suite it throws this
 * instead, so a test that reaches it unexpectedly fails, and a test that expects it catches it.
 */
final class ExitCalled extends RuntimeException
{
    /**
     * The stand-in every Unit test starts with: `uamswp_fad_exit()` throws this exception with the message it got.
     */
    public static function stubExit(): void
    {
        when('uamswp_fad_exit')->alias(static function (string $message = ''): never {
            throw new self($message);
        });
    }
}
