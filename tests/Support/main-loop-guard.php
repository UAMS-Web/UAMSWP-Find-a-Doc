<?php

/**
 * A main loop that fails instead of spinning.
 *
 * The same stand-in as uamswp_test_main_loop() in templates.php, except that have_posts() throws once it has been
 * asked more often than a loop over the posts can ask it. A template that stops calling the_post() then fails its
 * test at once, where the plain stand-in would loop until the mutation run's timeout and score a timeout, not a kill.
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

/**
 * Stub the main loop (have_posts(), the_post() and get_the_ID()) over the given post ids, failing on a loop that
 * never moves on.
 *
 * @param  list<int>  $ids
 */
function uamswp_test_guarded_main_loop(array $ids): void
{
    $position = new stdClass;
    $position->index = 0;
    $position->current = 0;
    $position->asked = 0;

    when('have_posts')->alias(static function () use ($position, $ids): bool {
        // A loop asks once per post and once more to end, and a template may ask once before its loop.
        if (++$position->asked > 2 * count($ids) + 4) {
            throw new RuntimeException('have_posts() asked again without the_post()');
        }

        return $position->index < count($ids);
    });
    when('the_post')->alias(static function () use ($position, $ids): void {
        $position->current = $ids[$position->index];
        $position->index++;
    });
    when('get_the_ID')->alias(static fn (): int => $position->current);
}
