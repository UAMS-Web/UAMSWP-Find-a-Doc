<?php

/**
 * Point git at the committed `.githooks/` directory, so the pre-commit hook runs
 * in every clone without each developer having to run the config command by hand.
 *
 * Copy this file to `scripts/setup-git-hooks.php` and run it from Composer:
 *
 *     "post-install-cmd": ["@php scripts/setup-git-hooks.php"],
 *     "post-update-cmd":  ["@php scripts/setup-git-hooks.php"]
 *
 * It runs `git config core.hooksPath .githooks`, the command and the path the local CI
 * runner's warning names, and it acts under the same condition the warning does: only
 * when `.githooks/pre-commit` is tracked. A clone it wired is a clone the warning leaves
 * silent.
 *
 * It never fails a Composer run. Every refusal below is a no-op or a one-line warning on
 * stderr, and the exit status is always 0. It does nothing:
 *
 *   - outside a git work tree (a dist or tarball install);
 *   - when the project is a subdirectory of another repository, because the setting would
 *     land in that other repository's configuration;
 *   - when `.githooks/pre-commit` is not tracked, since there is no hook to run;
 *   - under GitHub Actions, where nobody commits from the checkout.
 *
 * It is idempotent and quiet when the setting is already in place. When `core.hooksPath`
 * holds some other value it replaces it and says what it replaced.
 *
 * The syntax stays valid on PHP 7.4, the floor the code-quality skill allows a plugin.
 */

declare(strict_types=1);

const HOOK_FILE = '.githooks/pre-commit';
const HOOKS_PATH = '.githooks';

/**
 * Run git in a directory and return its exit status and trimmed stdout.
 *
 * @param  list<string>  $args
 * @return array{0: int, 1: string}
 */
function runGit(string $directory, array $args): array
{
    $descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $process = @proc_open(array_merge(['git', '-C', $directory], $args), $descriptors, $pipes);

    if (! is_resource($process)) {
        return [127, ''];
    }

    $stdout = (string) stream_get_contents($pipes[1]);
    stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return [proc_close($process), trim($stdout)];
}

/**
 * Wire `core.hooksPath` for the project in `$root`, returning the line to print on
 * stdout (empty for nothing) and the line to print on stderr (empty for nothing).
 *
 * @return array{0: string, 1: string}
 */
function setupGitHooks(string $root): array
{
    if (getenv('GITHUB_ACTIONS') !== false && getenv('GITHUB_ACTIONS') !== '') {
        return ['', ''];
    }

    [$status, $toplevel] = runGit($root, ['rev-parse', '--show-toplevel']);

    if ($status !== 0 || $toplevel === '') {
        return ['', ''];
    }

    $realRoot = realpath($root);
    $realToplevel = realpath($toplevel);

    if ($realRoot === false || $realToplevel === false || $realRoot !== $realToplevel) {
        return ['', ''];
    }

    [$status, $tracked] = runGit($root, ['ls-files', '--', HOOK_FILE]);

    if ($status !== 0 || $tracked === '') {
        return ['', ''];
    }

    [$status, $current] = runGit($root, ['config', '--get', 'core.hooksPath']);

    if ($status === 0 && $current === HOOKS_PATH) {
        return ['', ''];
    }

    [$status] = runGit($root, ['config', 'core.hooksPath', HOOKS_PATH]);

    if ($status !== 0) {
        return ['', "Warning: could not set core.hooksPath; run 'git config core.hooksPath ".HOOKS_PATH."' manually."];
    }

    $replaced = $current === '' ? '' : " (it was {$current})";

    return ['Git hooks installed (core.hooksPath -> '.HOOKS_PATH.')'.$replaced.'.', ''];
}

// Run only when invoked as a script, so a test can include the file and call the function.
if (isset($argv[0]) && basename($argv[0]) === basename(__FILE__)) {
    [$out, $err] = setupGitHooks(dirname(__DIR__));

    if ($out !== '') {
        fwrite(STDOUT, $out."\n");
    }

    if ($err !== '') {
        fwrite(STDERR, $err."\n");
    }
}
