<?php

declare(strict_types=1);

// cspell:ignore nowarn

/**
 * Check every patch `patches.lock.json` records against `vendor/` by content, and reinstall any
 * package that lacks one, so an unpatched `vendor/` cannot survive `composer install`.
 *
 * WHY. `cweagans/composer-patches` 2.0.0 applies a package's patches only from its
 * `POST_PACKAGE_INSTALL` and `POST_PACKAGE_UPDATE` handlers (`src/Plugin/Patches.php`, sha256
 * 20c18508fed47879955da8ad2f158dc7e29c72a09305c507db7fa911f6d8f7f6), and records nothing about
 * what it applied. A commit that changes the patch set while the patched package's version stays
 * put (a `uams-web/patches-*` release that adds or edits a patch, or a first `patches.lock.json`)
 * therefore reaches a working copy as an install of the patches package alone: the target package
 * is never reinstalled, its new patches are never applied, and `composer install` exits 0. Every
 * later install says "Nothing to install, update or remove" (UAMS-Web/uams-claude-skills#850).
 *
 * WHAT IT DOES. For each package `patches.lock.json` names that is installed, it copies the files
 * that package's patches touch into a scratch directory and reverse-applies the patches there,
 * last first, with `git apply --reverse` (whitespace compared exactly, as the plugin's own
 * `git apply` compares it). A patch that reverses is applied. One that does not reverse, and shares
 * no file with a later patch that failed, is missing. One that shares a file with a later failed
 * patch is reported undetermined, because the later patch's text is still in the scratch copy and
 * can block it. `vendor/` is never written by the check. When a patch is missing or undetermined, it runs
 * `composer reinstall <packages>`, which fires the plugin's install handler and applies the locked
 * set, then checks again and fails when a patch is still missing. `composer patches-repatch` is not
 * used: it returns 0 whatever its inner install returned.
 *
 * WIRING, in the consumer's composer.json:
 *     "scripts": {
 *         "post-install-cmd": ["@php .claude/skills/upstream-patch-lifecycle/scripts/ensure-patches-applied.php"],
 *         "post-update-cmd": ["@php .claude/skills/upstream-patch-lifecycle/scripts/ensure-patches-applied.php"]
 *     }
 *
 * USAGE
 *     php ensure-patches-applied.php [--check] [--root <dir>]
 *
 *     --check   Report only: exit 1 when a patch is missing, and never reinstall.
 *     --root    The project root (default: the working directory).
 *
 * EXIT
 *     0   Every checked patch is in vendor/ (after a reinstall, if one was needed), or there is no
 *         patches.lock.json. Also 0, with a warning, when git is unavailable outside --check, so an
 *         install on a machine without git is not broken by a check that cannot run there.
 *     1   A patch is still missing, the reinstall failed, or a locked patch file is absent or does
 *         not match its recorded sha256 (a reinstall cannot fix either).
 *     2   The check could not answer: its controls failed, a lock is not JSON, or (with --check)
 *         git is unavailable.
 *
 * BOUNDS. A patch whose url is remote (http or https) is not checked, and says so: its text is in
 * the plugin's download cache, which this does not read. A package the lock names but that is not
 * installed (a `--no-dev` install) is skipped and counted. `composer install --no-scripts` runs no
 * script, so it bypasses this. The reinstall runs with ENSURE_PATCHES_APPLIED_REENTRY=1, because
 * `composer reinstall` dispatches post-install-cmd itself, and a run that sees that variable does
 * nothing. Measured on macOS with Composer 2.11.1 and `cweagans/composer-patches` 2.0.0; Windows
 * is not yet measured.
 */
const LOCK_FILE = 'patches.lock.json';
const REENTRY = 'ENSURE_PATCHES_APPLIED_REENTRY';

/**
 * Run a command, each argument shell-escaped, with stderr folded into stdout, returning its exit
 * status and combined output.
 *
 * @param  list<string>  $command
 * @param  array<string, string>|null  $env
 * @return array{0: int, 1: string}
 */
function runCommand(array $command, ?string $cwd = null, ?array $env = null): array
{
    $line = implode(' ', array_map('escapeshellarg', $command));
    $process = @proc_open($line.' 2>&1', [1 => ['pipe', 'w']], $pipes, $cwd, $env);

    if (! is_resource($process)) {
        return [127, 'could not start '.$command[0]];
    }

    $output = (string) stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    return [proc_close($process), $output];
}

function gitAvailable(): bool
{
    [$status] = runCommand(['git', '--version']);

    return $status === 0;
}

/**
 * A path as a diff header writes it: git quotes a path with unusual characters and escapes them
 * C-style (`"a/caf\303\251.php"`).
 */
function unquotePath(string $path): string
{
    $path = trim($path);

    if (strlen($path) >= 2 && $path[0] === '"' && substr($path, -1) === '"') {
        return stripcslashes(substr($path, 1, -1));
    }

    return $path;
}

/**
 * The files a diff may touch, relative to the package root. It reads the `---` and `+++` lines
 * and the `diff --git` line, which is the only place a patch whose operation is in git's extended
 * headers alone (a rename, a mode change, an empty new file) names its files. Copying a file the
 * patch does not touch is harmless, so where a `diff --git` line cannot be split unambiguously
 * (an unquoted path with a space) every candidate split is listed. `$depth` leading components
 * are stripped from each path.
 *
 * @return list<string>
 */
function touchedFiles(string $patchText, int $depth): array
{
    $files = [];
    $add = function (string $path, int $strip) use (&$files): void {
        $path = unquotePath($path);

        if ($path === '/dev/null' || $path === '') {
            return;
        }

        $kept = array_slice(explode('/', $path), $strip);

        if ($kept === [] || in_array('..', $kept, true)) {
            return;
        }

        $files[implode('/', $kept)] = true;
    };

    foreach (preg_split('/\R/', $patchText) ?: [] as $line) {
        if (strpos($line, '--- ') === 0 || strpos($line, '+++ ') === 0) {
            $add(explode("\t", substr($line, 4))[0], $depth);
        } elseif (strpos($line, 'diff --git ') === 0) {
            $rest = substr($line, strlen('diff --git '));

            if (preg_match('/^("(?:[^"\\\\]|\\\\.)*"|\S+) ("(?:[^"\\\\]|\\\\.)*"|\S+)$/', $rest, $match) === 1) {
                $add($match[1], $depth);
                $add($match[2], $depth);
            } else {
                $offset = 0;

                while (($at = strpos($rest, ' ', $offset)) !== false) {
                    $add(substr($rest, 0, $at), $depth);
                    $add(substr($rest, $at + 1), $depth);
                    $offset = $at + 1;
                }
            }
        }
    }

    return array_keys($files);
}

function makeScratchDir(string $prefix): string
{
    $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.$prefix.bin2hex(random_bytes(6));

    if (! mkdir($dir, 0700, true)) {
        throw new RuntimeException('could not create a scratch directory under '.sys_get_temp_dir());
    }

    return $dir;
}

function removeTree(string $dir): void
{
    if (! is_dir($dir)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        $item->isDir() && ! $item->isLink() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }

    rmdir($dir);
}

/**
 * Which of one package's patches are not applied in `$installPath`. Each entry of `$patches` is
 * `['label' => string, 'text' => string, 'depth' => int]`, in lock order. Only the files the
 * patches touch are copied to a scratch directory (with their modes), and the patches are
 * reverse-applied there, last first, so a patch that overlaps a later one reverses once the later
 * one is out. Whitespace is compared exactly, as the plugin's `git apply` compares it, so a
 * whitespace-only patch is not mistaken for applied; `--whitespace=nowarn` keeps a user's
 * `apply.whitespace` setting from refusing a patch the plugin would apply.
 *
 * A patch that does not reverse is `missing` when no later failed patch touched any of its files,
 * and `undetermined` when one did: the later patch is still in the scratch copy and may be what
 * blocks it.
 *
 * @param  list<array{label: string, text: string, depth: int}>  $patches
 * @return array{missing: list<string>, undetermined: list<string>} labels, in lock order
 */
function missingPatches(string $installPath, array $patches): array
{
    $scratch = makeScratchDir('ensure-patches-');
    $filesOf = [];

    try {
        foreach ($patches as $index => $patch) {
            $filesOf[$index] = touchedFiles($patch['text'], $patch['depth']);

            foreach ($filesOf[$index] as $relative) {
                $source = $installPath.DIRECTORY_SEPARATOR.$relative;
                $target = $scratch.DIRECTORY_SEPARATOR.$relative;

                if (! is_file($source) || is_file($target)) {
                    continue;
                }

                if (! is_dir(dirname($target))) {
                    mkdir(dirname($target), 0700, true);
                }

                copy($source, $target);
                chmod($target, fileperms($source) & 0777);
            }
        }

        $missing = [];
        $undetermined = [];
        $blocked = [];

        for ($index = count($patches) - 1; $index >= 0; $index--) {
            $patch = $patches[$index];
            $file = $scratch.DIRECTORY_SEPARATOR.'.ensure-patches-'.$index.'.diff';
            file_put_contents($file, $patch['text']);
            [$status] = runCommand(
                ['git', 'apply', '--reverse', '--whitespace=nowarn', '-p'.$patch['depth'], $file],
                $scratch
            );
            unlink($file);

            if ($status === 0) {
                continue;
            }

            if (array_intersect($filesOf[$index], array_keys($blocked)) !== []) {
                $undetermined[] = $patch['label'];
            } else {
                $missing[] = $patch['label'];
            }

            foreach ($filesOf[$index] as $relative) {
                $blocked[$relative] = true;
            }
        }

        return ['missing' => array_reverse($missing), 'undetermined' => array_reverse($undetermined)];
    } finally {
        removeTree($scratch);
    }
}

/**
 * The planted cases the check must tell apart before its answer about vendor/ is read: a tree
 * with both patches passes, a tree with only the first reports the second missing, an untouched
 * tree reports the second missing and the first undetermined, and a whitespace-only patch is
 * reported missing on a tree that lacks it and applied on one that has it.
 */
function controlsPass(): bool
{
    $original = "one\ntwo\nthree\nfour\nfive\nsix\nseven\n";
    $withA = str_replace("two\n", "two A\n", $original);
    $withAB = str_replace("six\n", "six B\n", $withA);
    $patchA = "--- a/lib.txt\n+++ b/lib.txt\n@@ -1,3 +1,3 @@\n one\n-two\n+two A\n three\n";
    $patchB = "--- a/lib.txt\n+++ b/lib.txt\n@@ -5,3 +5,3 @@\n five\n-six\n+six B\n seven\n";
    $patchW = "--- a/lib.txt\n+++ b/lib.txt\n@@ -1,3 +1,3 @@\n one\n-  two\n+    two\n three\n";
    $ab = [
        ['label' => 'A', 'text' => $patchA, 'depth' => 1],
        ['label' => 'B', 'text' => $patchB, 'depth' => 1],
    ];
    $w = [['label' => 'W', 'text' => $patchW, 'depth' => 1]];

    $cases = [
        [$withAB, $ab, [], []],
        [$withA, $ab, ['B'], []],
        [$original, $ab, ['B'], ['A']],
        [str_replace("two\n", "  two\n", $original), $w, ['W'], []],
        [str_replace("two\n", "    two\n", $original), $w, [], []],
    ];

    foreach ($cases as [$content, $patches, $missing, $undetermined]) {
        $tree = makeScratchDir('ensure-patches-control-');

        try {
            file_put_contents($tree.DIRECTORY_SEPARATOR.'lib.txt', $content);

            if (missingPatches($tree, $patches) !== ['missing' => $missing, 'undetermined' => $undetermined]) {
                return false;
            }
        } finally {
            removeTree($tree);
        }
    }

    return true;
}

/**
 * @return array<string, mixed>
 */
function readJson(string $path): array
{
    $decoded = json_decode((string) file_get_contents($path), true);

    if (! is_array($decoded)) {
        throw new UnexpectedValueException($path.' is not valid JSON');
    }

    return $decoded;
}

/**
 * Installed package name to absolute install path, from vendor/composer/installed.json.
 *
 * @return array<string, string>
 */
function installedPaths(string $vendorDir): array
{
    $file = $vendorDir.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'installed.json';

    if (! is_file($file)) {
        return [];
    }

    $installed = readJson($file);
    $packages = $installed['packages'] ?? $installed;
    $paths = [];

    foreach (is_array($packages) ? $packages : [] as $package) {
        if (! is_array($package) || ! isset($package['name'])) {
            continue;
        }

        // Composer writes install-path relative to vendor/composer, or absolute when the two share
        // no ancestor below the filesystem root (or sit on different Windows drives).
        $relative = (string) ($package['install-path'] ?? ('../'.$package['name']));
        $paths[(string) $package['name']] = isAbsolutePath($relative)
            ? $relative
            : $vendorDir.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.$relative;
    }

    return $paths;
}

function isAbsolutePath(string $path): bool
{
    return preg_match('#^([A-Za-z]:)?[\\\\/]#', $path) === 1;
}

/**
 * The vendor directory as Composer resolves it: COMPOSER_VENDOR_DIR, then `config.vendor-dir`
 * in composer.json, then `vendor`.
 */
function vendorDir(string $root): string
{
    $composerJson = $root.DIRECTORY_SEPARATOR.'composer.json';
    $dir = 'vendor';
    $fromEnv = getenv('COMPOSER_VENDOR_DIR');

    if (is_string($fromEnv) && $fromEnv !== '') {
        $dir = $fromEnv;
    } elseif (is_file($composerJson)) {
        $config = readJson($composerJson)['config'] ?? [];

        if (is_array($config) && isset($config['vendor-dir']) && is_string($config['vendor-dir'])) {
            $dir = $config['vendor-dir'];
        }
    }

    return isAbsolutePath($dir) ? $dir : $root.DIRECTORY_SEPARATOR.$dir;
}

/**
 * Check every installed package the lock names.
 *
 * @return array{missing: array<string, list<string>>, broken: list<string>, notes: list<string>, checked: int}
 */
function checkProject(string $root): array
{
    $lock = readJson($root.DIRECTORY_SEPARATOR.LOCK_FILE);
    $byPackage = $lock['patches'] ?? [];
    $installed = installedPaths(vendorDir($root));
    $result = ['missing' => [], 'broken' => [], 'notes' => [], 'checked' => 0];

    foreach (is_array($byPackage) ? $byPackage : [] as $package => $entries) {
        $package = (string) $package;

        if (! is_array($entries) || $entries === []) {
            continue;
        }

        if (! isset($installed[$package]) || ! is_dir($installed[$package])) {
            $result['notes'][] = $package.' is not installed, so its '.count($entries).' locked patch(es) were not checked';

            continue;
        }

        $patches = [];

        foreach ($entries as $entry) {
            $url = (string) ($entry['url'] ?? '');
            $label = basename($url).(isset($entry['description']) ? ' ('.$entry['description'].')' : '');

            if (preg_match('#^https?://#i', $url) === 1) {
                $result['notes'][] = $package.': '.$label.' is a remote patch and was not checked';

                continue;
            }

            $file = isAbsolutePath($url) ? $url : $root.DIRECTORY_SEPARATOR.$url;

            if ($url === '' || ! is_file($file)) {
                $result['broken'][] = $package.': '.$label.' is not at '.($url === '' ? 'a recorded path' : $url);

                continue;
            }

            $text = (string) file_get_contents($file);
            $sha = (string) ($entry['sha256'] ?? '');

            if ($sha !== '' && hash('sha256', $text) !== $sha) {
                $result['broken'][] = $package.': '.$label.' does not match the sha256 '.LOCK_FILE.' records; relock with `composer patches-relock`';

                continue;
            }

            $patches[] = ['label' => $label, 'text' => $text, 'depth' => is_int($entry['depth'] ?? null) ? $entry['depth'] : 1];
        }

        if ($patches === []) {
            continue;
        }

        $result['checked'] += count($patches);
        $found = missingPatches($installed[$package], $patches);
        $problems = [];

        foreach ($found['missing'] as $label) {
            $problems[] = $label.' is not applied in vendor/';
        }

        foreach ($found['undetermined'] as $label) {
            $problems[] = $label.' could not be checked, because a later patch on the same file is not applied';
        }

        if ($problems !== []) {
            $result['missing'][$package] = $problems;
        }
    }

    return $result;
}

/**
 * @param  array{missing: array<string, list<string>>, broken: list<string>, notes: list<string>, checked: int}  $result
 */
function report(array $result): void
{
    foreach ($result['notes'] as $note) {
        fwrite(STDOUT, '  note: '.$note.PHP_EOL);
    }

    foreach ($result['broken'] as $problem) {
        fwrite(STDERR, '  '.$problem.PHP_EOL);
    }

    foreach ($result['missing'] as $package => $labels) {
        foreach ($labels as $problem) {
            fwrite(STDERR, '  '.$package.': '.$problem.PHP_EOL);
        }
    }
}

/**
 * @param  list<string>  $argv
 */
function main(array $argv): int
{
    if (getenv(REENTRY) === '1') {
        return 0;
    }

    $check = false;
    $root = getcwd() ?: '.';

    for ($i = 1; $i < count($argv); $i++) {
        if ($argv[$i] === '--check') {
            $check = true;
        } elseif ($argv[$i] === '--root' && isset($argv[$i + 1])) {
            $root = $argv[++$i];
        } else {
            fwrite(STDERR, 'Unknown argument '.$argv[$i].'. Usage: php ensure-patches-applied.php [--check] [--root <dir>]'.PHP_EOL);

            return 2;
        }
    }

    if (! is_file($root.DIRECTORY_SEPARATOR.LOCK_FILE)) {
        fwrite(STDOUT, 'Patches: no '.LOCK_FILE.', nothing to check.'.PHP_EOL);

        return 0;
    }

    if (! gitAvailable()) {
        fwrite(STDERR, 'Patches: git is not available, so the patches in vendor/ were NOT checked.'.PHP_EOL);

        return $check ? 2 : 0;
    }

    if (! controlsPass()) {
        fwrite(STDERR, 'Patches: the planted controls failed, so this check cannot tell an applied patch from a missing one. Nothing was checked.'.PHP_EOL);

        return 2;
    }

    try {
        $result = checkProject($root);
    } catch (UnexpectedValueException $error) {
        fwrite(STDERR, 'Patches: '.$error->getMessage().'. Nothing was checked.'.PHP_EOL);

        return 2;
    }

    report($result);

    if ($result['broken'] !== []) {
        fwrite(STDERR, 'Patches: '.count($result['broken']).' locked patch(es) cannot be checked or applied; a reinstall would not fix them.'.PHP_EOL);

        return 1;
    }

    if ($result['missing'] === [] && $result['checked'] === 0) {
        fwrite(STDOUT, 'Patches: no locked patch was checked (see the notes above).'.PHP_EOL);

        return 0;
    }

    if ($result['missing'] === []) {
        fwrite(STDOUT, 'Patches: all '.$result['checked'].' checked patch(es) are applied in vendor/.'.PHP_EOL);

        return 0;
    }

    $packages = array_keys($result['missing']);

    if ($check) {
        fwrite(STDERR, 'Patches: vendor/ lacks locked patches. Run `composer reinstall '.implode(' ', $packages).'`.'.PHP_EOL);

        return 1;
    }

    fwrite(STDOUT, 'Patches: vendor/ lacks locked patches; reinstalling '.implode(' ', $packages).PHP_EOL);
    $composer = getenv('COMPOSER_BINARY');
    $command = is_string($composer) && $composer !== '' && is_file($composer)
        ? [PHP_BINARY, $composer]
        : ['composer'];
    $env = getenv();
    $env[REENTRY] = '1';
    [$status, $output] = runCommand(array_merge($command, ['reinstall', '--no-interaction'], $packages), $root, $env);
    fwrite(STDOUT, $output);

    if ($status !== 0) {
        fwrite(STDERR, 'Patches: `composer reinstall '.implode(' ', $packages).'` failed (exit '.$status.'); vendor/ may still lack its patches.'.PHP_EOL);

        return 1;
    }

    $after = checkProject($root);

    if ($after['missing'] !== [] || $after['broken'] !== []) {
        report($after);
        fwrite(STDERR, 'Patches: the reinstall finished and vendor/ still lacks locked patches.'.PHP_EOL);

        return 1;
    }

    fwrite(STDOUT, 'Patches: re-applied; all '.$after['checked'].' checked patch(es) are now in vendor/.'.PHP_EOL);

    return 0;
}

exit(main($argv));
