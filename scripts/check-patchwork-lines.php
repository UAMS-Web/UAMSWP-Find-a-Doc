<?php

/**
 * Fail when Patchwork changes the line count of a file the mutation run covers.
 *
 *   php scripts/check-patchwork-lines.php              check the files `composer mutate` covers
 *   php scripts/check-patchwork-lines.php <file> ...   check only the files named
 *
 * The Unit suite loads Patchwork, which rewrites each first-party file as it is loaded. When the rewrite joins two
 * lines, every executed line after the join is credited to the line above it, so PCOV reports the mutants on the
 * lines it no longer sees as uncovered. A call to `array_filter()` whose first argument starts on the next line is
 * one shape that does this: in `includes/class.fad-gbp-runtime.php` it hid 282 mutants until #1458 put the call on
 * one line (#1459). This check runs Patchwork's own rewrite over each file and compares the newline counts.
 *
 * Before reading any file it checks that the rewrite can be seen: a snippet with the joining shape must lose a line
 * and the same call on one line must not. A run that cannot show both exits 2, so a Patchwork release that stops
 * joining lines (or changes how it is called) is not read as a clean result.
 *
 * Exits 0 when no file changes, 1 naming each file that does, and 2 when the check could not run.
 */

declare(strict_types=1);

use function Patchwork\CodeManipulation\transformString;

$root = dirname(__DIR__);
$patchwork = $root.'/vendor/antecedent/patchwork/Patchwork.php';

if (! is_file($patchwork)) {
    fwrite(STDERR, "check-patchwork-lines: Patchwork is not installed at vendor/antecedent/patchwork. Run composer install.\n");
    exit(2);
}

// Loading Patchwork from here also reads this repository's patchwork.json, as the Unit suite's bootstrap does.
require $patchwork;

/**
 * The number of lines Patchwork's rewrite removes from (or adds to) the code: 0 when it keeps them all.
 */
function uamswp_patchwork_line_change(string $code): int
{
    return substr_count($code, "\n") - substr_count(transformString($code), "\n");
}

// The controls: the shape that joins lines, then the same call on one line.
$joined = "<?php\n\$ids = array_values(array_filter(\n    \$items,\n    fn (\$item): bool => \$item > 0\n));\n";
$single = "<?php\n\$ids = array_values(array_filter(\$items, fn (\$item): bool => \$item > 0));\n";

if (uamswp_patchwork_line_change($joined) === 0 || uamswp_patchwork_line_change($single) !== 0) {
    fwrite(STDERR, "check-patchwork-lines: the controls did not behave: the split array_filter() call must lose a line and the one-line call must keep its lines. The check cannot tell a clean file from one it cannot see.\n");
    exit(2);
}

$files = array_slice($argv, 1);

if ($files === []) {
    // The files `composer mutate` mutates: includes and templates, the two root files, and not includes/_unused.
    foreach (['includes', 'templates'] as $directory) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/'.$directory, FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            $path = substr((string) $file, strlen($root) + 1);

            if (str_ends_with($path, '.php') && ! str_starts_with(str_replace('\\', '/', $path), 'includes/_unused/')) {
                $files[] = $path;
            }
        }
    }

    $files[] = 'uamswp-find-a-doc.php';
    $files[] = 'required-plugins.php';
    sort($files);
}

// The count comes first, so a run that reads nothing is not mistaken for a clean one.
echo 'check-patchwork-lines: '.count($files)." file(s) to check\n";

if ($files === []) {
    fwrite(STDERR, "check-patchwork-lines: no files to check.\n");
    exit(2);
}

$changed = 0;

foreach ($files as $path) {
    $full = str_starts_with($path, '/') ? $path : $root.'/'.$path;
    $code = is_file($full) ? file_get_contents($full) : false;

    if ($code === false) {
        fwrite(STDERR, "check-patchwork-lines: cannot read {$path}.\n");
        exit(2);
    }

    $change = uamswp_patchwork_line_change($code);

    if ($change !== 0) {
        $changed++;
        printf("%s: Patchwork's rewrite changes the line count by %+d, so coverage after the change is credited to the wrong lines\n", $path, -$change);
    }
}

if ($changed > 0) {
    echo "check-patchwork-lines: {$changed} file(s) change length under Patchwork. Put the call it joins on one line (#1459).\n";
    exit(1);
}

echo "check-patchwork-lines: every file keeps its line count\n";
exit(0);
