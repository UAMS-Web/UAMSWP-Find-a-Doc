<?php

declare(strict_types=1);

// cspell:ignore redefinable usort

/**
 * Fail when Patchwork changes the line count of a file the mutation run covers.
 *
 *   php .claude/skills/pcov-setup/scripts/check-patchwork-lines.php                    the files `<source>` in phpunit.xml names
 *   php .claude/skills/pcov-setup/scripts/check-patchwork-lines.php <path> ...         only these files, and every .php under these directories
 *   php .claude/skills/pcov-setup/scripts/check-patchwork-lines.php --root=<dir> ...   a repository root other than the one above this script
 *
 * WHAT IT CATCHES. A Unit suite whose bootstrap loads Patchwork (the `wordpress-plugin` template's does,
 * for Brain Monkey) has Patchwork rewrite each first-party file as it is loaded. When the repository's
 * `patchwork.json` lists any `redefinable-internals`, the rewrite drops the line break before the first
 * argument of a call to a callback-taking internal (`array_filter`, `array_map`, `usort` and the rest of
 * Patchwork's default list). The file PHP loads is then shorter than the source, so PCOV credits every
 * later line to the line above it, and a mutation run reports the mutants on the lines it no longer sees
 * as uncovered. Nothing else fails: UAMSWP-Find-a-Doc#1459 lost 282 mutants in one file this way. The
 * defect is reported upstream as antecedent/patchwork#230. This check runs Patchwork's own rewrite over
 * each file and compares newline counts, so it needs no coverage driver and no test run.
 *
 * WHEN A REPOSITORY RUNS IT. Every repository whose Unit suite loads Patchwork with a non-empty
 * `redefinable-internals` list, as a plain CI job beside the mutation job: the exposure is the pair, so a
 * repository that lists no internal, or does not load Patchwork, has nothing for it to read (its control
 * then exits 2, below). It stays in CI until antecedent/patchwork#230 is fixed in a release the
 * repository installs; the control is what says so, because it exits 2 once the rewrite stops joining
 * lines, and that is the signal to remove the job.
 *
 * THE CONTROL, run before any file is read: a snippet with the joining shape (`array_values(array_filter(`
 * with the first argument on the next line) must lose a line under the rewrite, and the same call on one
 * line must not. A run that cannot show both exits 2, so a Patchwork release that stops joining lines,
 * changes how it is called, or a repository whose `patchwork.json` lists no internal is never read as a
 * clean result. The message says what `patchwork.json` lists, because that is the usual cause.
 *
 * It loads Patchwork from the repository's `vendor/antecedent/patchwork/Patchwork.php`, which also reads
 * the repository's own `patchwork.json`, as the Unit suite's bootstrap does. It finds the root by walking
 * up from its own path for that file, or takes `--root=`.
 *
 * Measured against antecedent/patchwork 2.2.3 on Windows 11 and PHP 8.4 in a scratch project with
 * `redefinable-internals: ["array_filter"]` (UAMS-Web/uams-claude-skills#859); other platforms and
 * Patchwork releases are not measured here.
 *
 * Exits 0 when no file changes length, 1 naming each file that does, and 2 when the check could not run.
 */
$rootOverride = null;
$paths = [];

foreach (array_slice($argv, 1) as $argument) {
    if (strpos($argument, '--root=') === 0) {
        $rootOverride = substr($argument, strlen('--root='));
    } elseif (strpos($argument, '--') === 0) {
        fwrite(STDERR, "check-patchwork-lines: unknown option {$argument}\n");

        exit(2);
    } else {
        $paths[] = $argument;
    }
}

$root = $rootOverride !== null ? (is_dir($rootOverride) ? (realpath($rootOverride) ?: $rootOverride) : null) : findPatchworkRoot(__DIR__);

if ($root === null) {
    fwrite(STDERR, "check-patchwork-lines: vendor/antecedent/patchwork/Patchwork.php not found above this script or under --root. Run composer install in the repository root.\n");

    exit(2);
}

$patchwork = $root.'/vendor/antecedent/patchwork/Patchwork.php';

if (! is_file($patchwork)) {
    fwrite(STDERR, "check-patchwork-lines: Patchwork is not installed at {$patchwork}. Run composer install.\n");

    exit(2);
}

// Loading Patchwork from here also reads the repository's patchwork.json, as the Unit suite's bootstrap does.
require $patchwork;

if (! function_exists('Patchwork\\CodeManipulation\\transformString')) {
    fwrite(STDERR, "check-patchwork-lines: Patchwork\\CodeManipulation\\transformString() does not exist in the installed Patchwork, so its rewrite cannot be run. The check cannot tell a clean file from one it cannot see.\n");

    exit(2);
}

// The controls: the shape that joins lines, then the same call on one line.
$joined = "<?php\n\$ids = array_values(array_filter(\n    \$items,\n    fn (\$item): bool => \$item > 0\n));\n";
$single = "<?php\n\$ids = array_values(array_filter(\$items, fn (\$item): bool => \$item > 0));\n";

if (patchworkLineChange($joined) === 0 || patchworkLineChange($single) !== 0) {
    fwrite(STDERR, 'check-patchwork-lines: the controls did not behave: the split array_filter() call must lose a line and the one-line call must keep its lines. '.describePatchworkConfig($root)." The check cannot tell a clean file from one it cannot see.\n");

    exit(2);
}

$files = $paths === [] ? listSourceFiles($root) : expandPaths($root, $paths);

if ($files === null) {
    exit(2);
}

// The count comes first, so a run that reads nothing is not mistaken for a clean one.
echo 'check-patchwork-lines: '.count($files)." file(s) to check\n";

if ($files === []) {
    fwrite(STDERR, "check-patchwork-lines: no files to check.\n");

    exit(2);
}

$changed = 0;

foreach ($files as $path) {
    $full = isAbsolutePath($path) ? $path : $root.'/'.$path;
    $code = is_file($full) ? file_get_contents($full) : false;

    if ($code === false) {
        fwrite(STDERR, "check-patchwork-lines: cannot read {$path}.\n");

        exit(2);
    }

    $change = patchworkLineChange($code);

    if ($change !== 0) {
        $changed++;
        printf("%s: Patchwork's rewrite changes the line count by %+d, so coverage after the change is credited to the wrong lines\n", $path, -$change);
    }
}

if ($changed > 0) {
    echo "check-patchwork-lines: {$changed} file(s) change length under Patchwork. Put the call it joins on one line (antecedent/patchwork#230).\n";

    exit(1);
}

echo "check-patchwork-lines: every file keeps its line count\n";

exit(0);

/**
 * The number of lines Patchwork's rewrite removes from (or adds to) the code: 0 when it keeps them all.
 */
function patchworkLineChange(string $code): int
{
    return substr_count($code, "\n") - substr_count(\Patchwork\CodeManipulation\transformString($code), "\n");
}

/**
 * What the repository's patchwork.json says about redefinable internals, for the control's failure message.
 */
function describePatchworkConfig(string $root): string
{
    $file = $root.'/patchwork.json';

    if (! is_file($file)) {
        return 'The repository has no patchwork.json, so no internal is listed in redefinable-internals and nothing joins lines.';
    }

    $config = json_decode((string) file_get_contents($file), true);

    if (! is_array($config)) {
        return 'patchwork.json is not valid JSON.';
    }

    $internals = $config['redefinable-internals'] ?? [];

    if (! is_array($internals) || $internals === []) {
        return 'patchwork.json lists no redefinable-internals, so nothing joins lines and this repository is not exposed.';
    }

    return 'patchwork.json lists redefinable-internals ('.implode(', ', array_map('strval', $internals)).'), so a Patchwork release that no longer joins lines is the likely cause: antecedent/patchwork#230 may be fixed and this check can be removed.';
}

/**
 * Walk up from `$start` until a directory holding `vendor/antecedent/patchwork/Patchwork.php` is found.
 */
function findPatchworkRoot(string $start): ?string
{
    $dir = $start;

    while (true) {
        if (is_file($dir.'/vendor/antecedent/patchwork/Patchwork.php')) {
            return $dir;
        }

        $parent = dirname($dir);

        if ($parent === $dir) {
            return null;
        }

        $dir = $parent;
    }
}

function isAbsolutePath(string $path): bool
{
    return $path !== '' && ($path[0] === '/' || $path[0] === '\\' || preg_match('#^[A-Za-z]:[\\\\/]#', $path) === 1);
}

/**
 * Turn the paths named on the command line into repo-relative .php files: a file is itself, a directory is every .php under it.
 *
 * @param  list<string>  $paths
 * @return list<string>|null null, after saying why on stderr, when a path does not exist
 */
function expandPaths(string $root, array $paths): ?array
{
    $files = [];

    foreach ($paths as $path) {
        $full = isAbsolutePath($path) ? $path : $root.'/'.$path;

        if (is_file($full)) {
            $files[$path] = true;
        } elseif (is_dir($full)) {
            foreach (phpFilesUnder($full) as $found) {
                $files[relativeTo($root, $found)] = true;
            }
        } else {
            fwrite(STDERR, "check-patchwork-lines: {$path} is neither a file nor a directory.\n");

            return null;
        }
    }

    $list = array_keys($files);
    sort($list);

    return $list;
}

/**
 * The files `<source>` in phpunit.xml (or phpunit.xml.dist) names, less its excludes, as sorted repo-relative paths.
 *
 * @return list<string>|null null, after saying why on stderr, when the files cannot be read from the configuration
 */
function listSourceFiles(string $root): ?array
{
    $config = null;

    foreach (['phpunit.xml', 'phpunit.xml.dist'] as $candidate) {
        if (is_file($root.'/'.$candidate)) {
            $config = $root.'/'.$candidate;
            break;
        }
    }

    if ($config === null) {
        fwrite(STDERR, "check-patchwork-lines: no path was named and the repository has no phpunit.xml or phpunit.xml.dist to read <source> from. Name the files or directories the mutation run covers.\n");

        return null;
    }

    $xml = new DOMDocument;

    if (! @$xml->load($config)) {
        fwrite(STDERR, "check-patchwork-lines: could not parse {$config}.\n");

        return null;
    }

    $xpath = new DOMXPath($xml);
    $files = [];
    $includes = 0;

    foreach ($xpath->query('//source/include/directory') ?: [] as $node) {
        $includes++;
        $directory = $root.'/'.normalizeConfigPath($node->textContent);
        $suffix = $node instanceof DOMElement && $node->hasAttribute('suffix') ? $node->getAttribute('suffix') : '.php';

        foreach (is_dir($directory) ? phpFilesUnder($directory, $suffix) : [] as $found) {
            $files[relativeTo($root, $found)] = true;
        }
    }

    foreach ($xpath->query('//source/include/file') ?: [] as $node) {
        $includes++;
        $file = $root.'/'.normalizeConfigPath($node->textContent);

        if (is_file($file)) {
            $files[relativeTo($root, $file)] = true;
        }
    }

    if ($includes === 0) {
        fwrite(STDERR, "check-patchwork-lines: no <source><include> entries in {$config}. Name the files or directories the mutation run covers.\n");

        return null;
    }

    $excluded = [];

    foreach ($xpath->query('//source/exclude/directory | //source/exclude/file') ?: [] as $node) {
        $excluded[] = rtrim(normalizeConfigPath($node->textContent), '/');
    }

    foreach (array_keys($files) as $relative) {
        foreach ($excluded as $prefix) {
            if ($relative === $prefix || strpos($relative, $prefix.'/') === 0) {
                unset($files[$relative]);
                break;
            }
        }
    }

    $list = array_keys($files);
    sort($list);

    return $list;
}

function normalizeConfigPath(string $path): string
{
    return preg_replace('#^\./#', '', str_replace('\\', '/', trim($path))) ?? $path;
}

function relativeTo(string $root, string $absolute): string
{
    $prefix = rtrim(str_replace('\\', '/', $root), '/').'/';
    $path = str_replace('\\', '/', $absolute);

    return strpos($path, $prefix) === 0 ? substr($path, strlen($prefix)) : $path;
}

/**
 * Every file under a directory whose name ends with the suffix.
 *
 * @return list<string>
 */
function phpFilesUnder(string $directory, string $suffix = '.php'): array
{
    $found = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        $name = $file->getFilename();

        if ($file->isFile() && ($suffix === '' || substr($name, -strlen($suffix)) === $suffix)) {
            $found[] = $file->getPathname();
        }
    }

    return $found;
}
