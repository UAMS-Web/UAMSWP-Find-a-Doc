<?php

/**
 * A stand-in for the `WP_CLI` class, for the tests of the Business Profile command handlers.
 *
 * WP-CLI is not installed in the Unit suite. This class records what a handler prints and registers, and ends a
 * handler the way WP-CLI does: `halt()` and `error()` stop it, here by throwing, so a test can read how it ended.
 * The `WP_CLI` constant is never defined, so code that checks it still sees a run that is not under WP-CLI.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Support {
    use RuntimeException;

    /**
     * A handler ended through `WP_CLI::halt()` or `WP_CLI::error()`.
     */
    final class WpCliEnded extends RuntimeException
    {
        /**
         * @param  int|null  $status  The status `halt()` got, or null for `error()`.
         */
        public function __construct(public readonly ?int $status, string $message = '')
        {
            parent::__construct($message);
        }
    }
}

namespace {
    use UamswpFindADoc\Tests\Support\WpCliEnded;

    if (! class_exists('WP_CLI', false)) {
        final class WP_CLI
        {
            /** @var list<string> */
            public static array $lines = [];

            /** @var list<array{string, mixed}> */
            public static array $commands = [];

            public static function reset(): void
            {
                self::$lines = [];
                self::$commands = [];
            }

            public static function line(string $line): void
            {
                self::$lines[] = $line;
            }

            public static function halt(int $status): never
            {
                throw new WpCliEnded($status);
            }

            public static function error(string $message): never
            {
                throw new WpCliEnded(null, $message);
            }

            public static function add_command(string $name, mixed $callable): void
            {
                self::$commands[] = [$name, $callable];
            }
        }
    }
}
