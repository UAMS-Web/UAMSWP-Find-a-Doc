<?php

/**
 * `uamswp_fad_exit()`: the real function ends the request, and every Unit test starts with it replaced.
 *
 * The real function cannot be called in the test process, since it would end the run, so it is run in a PHP
 * process of its own (UAMS-Web/UAMSWP-Find-a-Doc#1416).
 */

declare(strict_types=1);

use UamswpFindADoc\Tests\Support\ExitCalled;

/**
 * Run the real `uamswp_fad_exit()` in its own PHP process, followed by an echo it should never reach.
 *
 * @return array{0: string, 1: int} The output and the exit status.
 */
function exit_function_child(string $call): array
{
    $script = 'require '.var_export(dirname(__DIR__, 2).'/includes/class.fad-helper-functions.php', true).'; '.$call.' echo "after";';
    $process = proc_open([PHP_BINARY, '-r', $script], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $output = (string) stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return [$output, proc_close($process)];
}

test('the real exit function ends the request without printing', function (): void {
    expect(exit_function_child('uamswp_fad_exit();'))->toBe(['', 0]);
});

test('the real exit function prints its message and ends the request', function (): void {
    expect(exit_function_child("uamswp_fad_exit('Security check error');"))->toBe(['Security check error', 0]);
});

test('every Unit test starts with the exit function replaced by one that throws', function (): void {
    expect(fn () => uamswp_fad_exit('bye'))->toThrow(ExitCalled::class, 'bye');
});
