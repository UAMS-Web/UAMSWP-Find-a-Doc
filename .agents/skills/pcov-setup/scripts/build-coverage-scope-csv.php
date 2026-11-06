<?php

declare(strict_types=1);

// cspell:ignore coveredstatements

/**
 * Build or refresh a three-pass coverage tracker CSV for a Pest repository.
 *
 * Inventory comes from `<source>` include/exclude in `phpunit.xml` /
 * `phpunit.xml.dist`. The CSV is the tracker:
 *
 * - `file`: repo-relative path in the coverage population
 * - `pass1_touched`: yes/no/unknown: class FQCN appears in `tests/` (static;
 *   run once for the whole inventory with `--pass1`)
 * - `pass2_line_pct`: line coverage % from Clover, for `pass1=yes` rows.
 *   Prefer `--pass2 --batch=10` so each run covers the next 10 pending files
 *   via their referencing tests (avoids a multi-hour full-suite wait).
 * - `pass3_mutation_clean`: `yes` / `no (…)` / `n/a (…)` from class-scoped
 *   `pest --mutate` for rows with pass2 ≥ `--pass2-min` (required with
 *   `--pass3`; this repository's own pass 2 floor, with no built-in value)
 *
 * Usage:
 *   php build-coverage-scope-csv.php --pass1
 *   php build-coverage-scope-csv.php --pass2 --batch=10
 *   php build-coverage-scope-csv.php --pass2 --clover=storage/app/coverage-clover.xml
 *   php build-coverage-scope-csv.php --pass3 --batch=1 --pass2-min=<N>
 * Exit: 0 = wrote (or counted), 1 = bad args / missing inputs / --pass3 without --pass2-min, 2 = root not found,
 *       3 = batch pest run failed (pass2 without clover / pass3 launch failure).
 */
$arguments = array_slice($argv, 1);
$rootOverride = null;
$phpunitPath = null;
$outPath = null;
$cloverPath = null;
$runPass1 = false;
$runPass2 = false;
$runPass3 = false;
$skeletonOnly = false;
$countOnly = false;
$passFlagSeen = false;
$batchSize = null;
$pass2Min = null;

foreach ($arguments as $argument) {
    if (str_starts_with($argument, '--root=')) {
        $rootOverride = substr($argument, strlen('--root='));
    } elseif (str_starts_with($argument, '--phpunit=')) {
        $phpunitPath = substr($argument, strlen('--phpunit='));
    } elseif (str_starts_with($argument, '--out=')) {
        $outPath = substr($argument, strlen('--out='));
    } elseif (str_starts_with($argument, '--clover=')) {
        $cloverPath = substr($argument, strlen('--clover='));
    } elseif (str_starts_with($argument, '--batch=')) {
        $batchSize = max(1, (int) substr($argument, strlen('--batch=')));
    } elseif (str_starts_with($argument, '--pass2-min=')) {
        $pass2MinValue = substr($argument, strlen('--pass2-min='));
        $pass2Min = is_numeric($pass2MinValue) ? (float) $pass2MinValue : false;
    } elseif ($argument === '--pass1') {
        $runPass1 = true;
        $passFlagSeen = true;
    } elseif ($argument === '--pass2') {
        $runPass2 = true;
        $passFlagSeen = true;
    } elseif ($argument === '--pass3') {
        $runPass3 = true;
        $passFlagSeen = true;
    } elseif ($argument === '--skeleton-only') {
        $skeletonOnly = true;
        $passFlagSeen = true;
    } elseif ($argument === '--count') {
        $countOnly = true;
    } elseif ($argument === '--help' || $argument === '-h') {
        fwrite(STDOUT, "Usage: php build-coverage-scope-csv.php [--root=<path>] [--phpunit=<path>] [--out=<csv>] [--pass1] [--pass2] [--pass3] [--batch=N] [--pass2-min=<N>] [--clover=<file>] [--skeleton-only] [--count]\n");

        exit(0);
    } else {
        fwrite(STDERR, "build-coverage-scope-csv: unknown argument {$argument}\n");

        exit(1);
    }
}

if (! $passFlagSeen && ! $countOnly) {
    $runPass1 = true;
}

if ($skeletonOnly) {
    $runPass1 = false;
    $runPass2 = false;
    $runPass3 = false;
}

if ($runPass2 && $runPass3) {
    fwrite(STDERR, "build-coverage-scope-csv: use only one of --pass2 / --pass3 per run\n");

    exit(1);
}

if ($batchSize !== null && ! $runPass2 && ! $runPass3) {
    fwrite(STDERR, "build-coverage-scope-csv: --batch=N requires --pass2 or --pass3\n");

    exit(1);
}

if ($pass2Min === false) {
    fwrite(STDERR, "build-coverage-scope-csv: --pass2-min=<N> must be a number\n");

    exit(1);
}

if ($runPass3 && $pass2Min === null) {
    fwrite(STDERR, "build-coverage-scope-csv: --pass3 requires --pass2-min=<N>: it is this repository's own pass 2 floor, and no other repository's value stands in for it\n");

    exit(1);
}

if ($runPass3 && $batchSize === null) {
    $batchSize = 1;
}

$root = $rootOverride !== null && is_dir($rootOverride)
    ? realpath($rootOverride) ?: $rootOverride
    : findRepositoryRoot(__DIR__);

if ($root === null || $root === false) {
    fwrite(STDERR, "build-coverage-scope-csv: repository root not found above this script (pass --root=).\n");

    exit(2);
}

$root = str_replace('\\', '/', $root);
$outPath ??= defaultOutPath($root);

$files = listCoveragePopulation($root, $phpunitPath);
if ($files === null) {
    exit(1);
}

if ($countOnly) {
    echo count($files)."\n";

    exit(0);
}

$prior = readPriorCsv($outPath);

$haystack = '';
$testPaths = [];
/** @var array<string, string> $testContents path => file body */
$testContents = [];
$needTestIndex = $runPass1 || ($runPass2 && $batchSize !== null);

if ($needTestIndex) {
    $testsDir = $root.'/tests';
    if (! is_dir($testsDir)) {
        fwrite(STDERR, "build-coverage-scope-csv: no tests/ under {$root}\n");

        exit(1);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $testsDir,
            FilesystemIterator::SKIP_DOTS
        )
    );

    foreach ($iterator as $fileInfo) {
        if (! $fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
            continue;
        }

        $relative = str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($root) + 1));
        $testPaths[] = $relative;
        $contents = file_get_contents($fileInfo->getPathname());
        if ($contents === false) {
            continue;
        }

        $testContents[$relative] = $contents;
        // Haystack is only for pass1 FQCN presence across the suite.
        $haystack .= "\n".$contents;
    }
}

$linePercents = [];
$batchFiles = [];

if ($runPass2 && $batchSize !== null) {
    foreach ($files as $relative) {
        $existing = $prior[$relative] ?? ['', '', ''];
        if (($existing[0] ?? '') !== 'yes') {
            continue;
        }

        if (($existing[1] ?? '') !== '') {
            continue;
        }

        $batchFiles[] = $relative;
        if (count($batchFiles) >= $batchSize) {
            break;
        }
    }

    if ($batchFiles === []) {
        fwrite(STDOUT, "pass2 batch: nothing pending (all pass1=yes rows already have pass2).\n");

        exit(0);
    }

    fwrite(STDOUT, 'pass2 batch ('.count($batchFiles)."):\n");
    foreach ($batchFiles as $relative) {
        fwrite(STDOUT, "  {$relative}\n");
    }

    $coveringTests = coveringTestsForSources($root, $batchFiles, $testContents);
    $cloverPath ??= defaultBatchCloverPath($root);

    if ($coveringTests === []) {
        fwrite(STDOUT, "pass2 batch: no covering tests found; recording 0 for the batch.\n");
        foreach ($batchFiles as $relative) {
            $linePercents[$relative] = 0.0;
        }
    } else {
        fwrite(STDOUT, 'pass2 batch: running '.count($coveringTests)." test file(s) with coverage…\n");
        $exit = runPestCoverageClover($root, $cloverPath, $coveringTests);
        // Pest can exit non-zero on assertion failures and still write Clover;
        // keep the walk moving when a report is on disk.
        if ($exit !== 0 && ! is_file($cloverPath)) {
            fwrite(STDERR, "build-coverage-scope-csv: pest coverage exit {$exit} and no clover at {$cloverPath}\n");

            exit(3);
        }

        if ($exit !== 0) {
            fwrite(
                STDERR,
                "build-coverage-scope-csv: pest coverage exit {$exit}; importing clover anyway\n"
            );
        }

        $mapped = readCloverLinePercents($root, $cloverPath);
        if ($mapped === null) {
            exit(1);
        }

        foreach ($batchFiles as $relative) {
            $linePercents[$relative] = $mapped[$relative] ?? 0.0;
        }
    }
} elseif ($runPass2) {
    $cloverPath ??= defaultCloverPath($root);
    if (! is_file($cloverPath)) {
        fwrite(STDERR, "build-coverage-scope-csv: clover report missing at {$cloverPath}\n");
        fwrite(STDERR, "Prefer: php build-coverage-scope-csv.php --pass2 --batch=10\n");
        fwrite(STDERR, "Or: php -d pcov.enabled=1 vendor/bin/pest --coverage-clover={$cloverPath}\n");

        exit(1);
    }

    $linePercents = readCloverLinePercents($root, $cloverPath);
    if ($linePercents === null) {
        exit(1);
    }
}

/** @var array<string, string> $mutationResults relative => pass3 cell */
$mutationResults = [];
$pass3BatchFiles = [];

if ($runPass3) {
    // Smallest eligible files first: large actions (e.g. MergePeople) otherwise
    // monopolize the first batch while the suite has no covers()/mutates().
    $eligible = [];
    foreach ($files as $relative) {
        $existing = $prior[$relative] ?? ['', '', ''];
        $pass2 = $existing[1] ?? '';
        $pass3 = $existing[2] ?? '';

        if ($pass2 === '' || $pass3 !== '') {
            continue;
        }

        if ((float) $pass2 < $pass2Min) {
            continue;
        }

        $absolute = $root.'/'.$relative;
        $eligible[] = [
            'file' => $relative,
            'size' => is_file($absolute) ? filesize($absolute) : PHP_INT_MAX,
        ];
    }

    usort(
        $eligible,
        static fn (array $a, array $b): int => $a['size'] <=> $b['size']
    );

    foreach ($eligible as $row) {
        $pass3BatchFiles[] = $row['file'];
        if (count($pass3BatchFiles) >= $batchSize) {
            break;
        }
    }

    if ($pass3BatchFiles === []) {
        fwrite(STDOUT, "pass3 batch: nothing pending (no eligible empty pass3 cells).\n");

        exit(0);
    }

    fwrite(STDOUT, 'pass3 batch ('.count($pass3BatchFiles).") pass2-min={$pass2Min}:\n");
    foreach ($pass3BatchFiles as $relative) {
        fwrite(STDOUT, "  {$relative}\n");
    }

    foreach ($pass3BatchFiles as $relative) {
        $fqcn = fqcnFromFile($root.'/'.$relative);
        if ($fqcn === null) {
            $mutationResults[$relative] = 'n/a (no fqcn)';
            fwrite(STDOUT, "pass3: {$relative} → n/a (no fqcn)\n");

            continue;
        }

        // Same shape as `composer mutate -- --class=… --covered-only` (bakes
        // --path=app). Invoke php + backslash pest path so Windows mutant
        // children launch (#2322); do not shell out to composer.bat (proc_open
        // argv form cannot start .bat). Never pass a test file / --filter
        // (false 100% via child timeouts) or --parallel (false DB kills).
        fwrite(STDOUT, "pass3: php vendor\\bin\\pest --mutate --path=app --class={$fqcn} --covered-only\n");
        [$exit, $output] = runPestMutate($root, $fqcn);
        $cell = interpretMutateOutput($output, $exit);
        $mutationResults[$relative] = $cell;
        fwrite(STDOUT, "pass3: {$relative} → {$cell}\n");
    }
}

$outDir = dirname($outPath);
if (! is_dir($outDir) && ! mkdir($outDir, 0777, true) && ! is_dir($outDir)) {
    fwrite(STDERR, "build-coverage-scope-csv: cannot create {$outDir}\n");

    exit(1);
}

$handle = fopen($outPath, 'wb');
if ($handle === false) {
    fwrite(STDERR, "build-coverage-scope-csv: cannot write {$outPath}\n");

    exit(1);
}

fputcsv(
    $handle,
    [
        'file',
        'pass1_touched',
        'pass2_line_pct',
        'pass3_mutation_clean',
    ],
    ',',
    '"',
    '\\'
);

$touched = 0;
$untouched = 0;
$unknown = 0;
$pass2Filled = 0;
$pass2Skipped = 0;
$pass3Filled = 0;
$batchSet = array_fill_keys($batchFiles, true);

foreach ($files as $relative) {
    $existing = $prior[$relative] ?? ['', '', ''];
    $pass1 = $existing[0];
    $pass2 = $existing[1];
    $pass3 = $existing[2];

    if ($runPass1) {
        $fqcn = fqcnFromFile($root.'/'.$relative);
        if ($fqcn === null) {
            $pass1 = 'unknown';
            $unknown++;
        } elseif (str_contains($haystack, $fqcn)) {
            $pass1 = 'yes';
            $touched++;
        } elseif (
            pairedTestMentionsClass(
                $testContents,
                $relative,
                shortName($fqcn)
            )
        ) {
            $pass1 = 'yes';
            $touched++;
        } else {
            $pass1 = 'no';
            $untouched++;
        }
    }

    if ($runPass2) {
        $updateThis = $batchSize === null
            ? $pass1 === 'yes'
            : isset($batchSet[$relative]);

        if ($updateThis && $pass1 === 'yes') {
            $pass2 = array_key_exists($relative, $linePercents)
                ? formatPercent($linePercents[$relative])
                : '0';
            $pass2Filled++;
        } elseif ($batchSize === null && $pass1 !== 'yes') {
            $pass2 = '';
            $pass2Skipped++;
        }
    }

    if ($runPass3 && array_key_exists($relative, $mutationResults)) {
        $pass3 = $mutationResults[$relative];
        $pass3Filled++;
    }

    fputcsv(
        $handle,
        [$relative, $pass1, $pass2, $pass3],
        ',',
        '"',
        '\\'
    );
}

fclose($handle);

$pendingPass2 = 0;
$pendingPass3 = 0;
$written = readPriorCsv($outPath);
foreach ($written as $cols) {
    if (($cols[0] ?? '') === 'yes' && ($cols[1] ?? '') === '') {
        $pendingPass2++;
    }

    $pass2Cell = $cols[1] ?? '';
    $pass3Cell = $cols[2] ?? '';
    if (
        $pass2Cell !== ''
        && $pass3Cell === ''
        && $pass2Min !== null
        && (float) $pass2Cell >= $pass2Min
    ) {
        $pendingPass3++;
    }
}

fwrite(STDOUT, "Wrote {$outPath}\n");
fwrite(STDOUT, 'files: '.count($files)."\n");
if ($runPass1) {
    fwrite(STDOUT, "pass1 touched: {$touched}\n");
    fwrite(STDOUT, "pass1 untouched: {$untouched}\n");
    fwrite(STDOUT, "pass1 unknown: {$unknown}\n");
}
if ($runPass2) {
    fwrite(STDOUT, "pass2 filled this run: {$pass2Filled}\n");
    if ($batchSize === null) {
        fwrite(STDOUT, "pass2 skipped (not pass1=yes): {$pass2Skipped}\n");
        fwrite(STDOUT, 'clover files mapped: '.count($linePercents)."\n");
    }
    fwrite(STDOUT, "pass2 still pending: {$pendingPass2}\n");
}
if ($runPass3) {
    fwrite(STDOUT, "pass3 filled this run: {$pass3Filled}\n");
    fwrite(STDOUT, "pass3 still pending (pass2≥{$pass2Min}): {$pendingPass3}\n");
}

exit(0);

/**
 * Prefer storage/app when that tree exists (Laravel), else repository root.
 */
function defaultOutPath(string $root): string
{
    if (is_dir($root.'/storage/app')) {
        return $root.'/storage/app/coverage-scope.csv';
    }

    return $root.'/coverage-scope.csv';
}

/**
 * Default Clover path for a full-suite pass-2 import.
 */
function defaultCloverPath(string $root): string
{
    if (is_dir($root.'/storage/app')) {
        return $root.'/storage/app/coverage-clover.xml';
    }

    return $root.'/coverage-clover.xml';
}

/**
 * Per-batch Clover path (overwritten each `--pass2 --batch=` run).
 */
function defaultBatchCloverPath(string $root): string
{
    if (is_dir($root.'/storage/app')) {
        return $root.'/storage/app/coverage-clover-batch.xml';
    }

    return $root.'/coverage-clover-batch.xml';
}

/**
 * Test files that mention any batch source's FQCN (or a paired *Test.php).
 *
 * @param  list<string>  $sources
 * @param  array<string, string>  $testContents
 * @return list<string>
 */
function coveringTestsForSources(
    string $root,
    array $sources,
    array $testContents
): array {
    $found = [];

    foreach ($sources as $source) {
        $fqcn = fqcnFromFile($root.'/'.$source);
        if ($fqcn === null) {
            continue;
        }

        $short = shortName($fqcn);
        $base = basename($source, '.php');

        foreach ($testContents as $testPath => $chunk) {
            // Browser smoke needs Playwright ports; pass 2 is line coverage only.
            if (str_starts_with($testPath, 'tests/Browser/')) {
                continue;
            }

            if (str_contains($chunk, $fqcn)) {
                $found[$testPath] = true;

                continue;
            }

            if (
                str_ends_with($testPath, $base.'Test.php')
                && (
                    str_contains($chunk, $short.'::')
                    || str_contains($chunk, 'new '.$short)
                    || str_contains($chunk, $short.'::class')
                    || preg_match('/\buse\s+[A-Za-z0-9_\\\\]*\\\\'.preg_quote($short, '/').';/', $chunk) === 1
                )
            ) {
                $found[$testPath] = true;
            }
        }
    }

    $list = array_keys($found);
    sort($list);

    return $list;
}

/**
 * Run class-scoped mutation testing (same args as `composer mutate -- …`).
 *
 * Uses `php` + `vendor\bin\pest` (platform separators) so Windows mutant
 * children launch (#2322). Equivalent to the composer script's
 * `@php vendor/bin/pest --mutate --path=app` plus `--class` / `--covered-only`.
 *
 * @return array{0: int, 1: string} exit code and combined stdout/stderr
 */
function runPestMutate(string $root, string $fqcn): array
{
    $pest = $root.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'pest';
    if (! is_file($pest)) {
        return [1, "missing {$pest}"];
    }

    $cmd = [
        PHP_BINARY,
        $pest,
        '--mutate',
        '--path=app',
        '--class='.$fqcn,
        '--covered-only',
        '--compact',
    ];

    $descriptor = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $env = [];
    foreach ($_SERVER as $key => $value) {
        if (is_string($key) && is_string($value)) {
            $env[$key] = $value;
        }
    }
    $env['PCOV_DIRECTORY'] = 'app';

    $process = proc_open(
        $cmd,
        $descriptor,
        $pipes,
        $root,
        $env
    );

    if (! is_resource($process)) {
        return [1, 'failed to start pest --mutate'];
    }

    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]) ?: '';
    $stderr = stream_get_contents($pipes[2]) ?: '';
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit = proc_close($process);
    $combined = $stdout.($stderr !== '' ? "\n".$stderr : '');

    if ($combined !== '') {
        fwrite(STDOUT, $combined);
    }

    return [$exit, $combined];
}

/**
 * Map mutate console output to a pass3 CSV cell.
 *
 * Mutation-clean = at least one mutant created and zero UNTESTED / uncovered.
 */
function interpretMutateOutput(string $output, int $exit): string
{
    $plain = preg_replace('/\e\[[0-9;]*m/', '', $output) ?? $output;

    if (
        str_contains($plain, 'No mutations created')
        || preg_match('/0 Mutations for 0 Files created/', $plain) === 1
    ) {
        return 'n/a (0 mutations)';
    }

    $created = null;
    if (preg_match('/(\d+) Mutations for (\d+) Files created/', $plain, $m) === 1) {
        $created = (int) $m[1];
    }

    $untested = 0;
    if (preg_match('/(\d+) untested/', $plain, $m) === 1) {
        $untested = (int) $m[1];
    }

    $uncovered = 0;
    if (preg_match('/(\d+) uncovered/', $plain, $m) === 1) {
        $uncovered = (int) $m[1];
    }

    $tested = null;
    if (preg_match('/(\d+) tested/', $plain, $m) === 1) {
        $tested = (int) $m[1];
    }

    if ($created === 0) {
        return 'n/a (0 mutations)';
    }

    if ($untested > 0) {
        return 'no ('.$untested.' untested)';
    }

    if ($uncovered > 0) {
        return 'no ('.$uncovered.' uncovered)';
    }

    if ($tested !== null && $tested > 0) {
        return 'yes';
    }

    if ($created !== null && $created > 0 && preg_match('/Score:\s*100(?:\.0+)?%/', $plain) === 1) {
        return 'yes';
    }

    if ($exit !== 0) {
        return 'error (exit '.$exit.')';
    }

    return 'error (unparsed)';
}

/**
 * Run Pest with PCOV over the given test files and write a Clover report.
 *
 * @param  list<string>  $testFiles
 */
function runPestCoverageClover(string $root, string $cloverPath, array $testFiles): int
{
    $php = PHP_BINARY;
    $pest = $root.'/vendor/bin/pest';
    if (! is_file($pest)) {
        fwrite(STDERR, "build-coverage-scope-csv: missing {$pest}\n");

        return 1;
    }

    $cmd = [
        $php,
        '-d',
        'pcov.enabled=1',
        $pest,
        '--coverage-clover='.$cloverPath,
        '--compact',
        ...$testFiles,
    ];

    $descriptor = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    // Merge into the current environment: a replacement array drops PATH and
    // breaks Pest's browser plugin port probe even for non-browser Feature tests.
    $env = [];
    foreach ($_SERVER as $key => $value) {
        if (is_string($key) && is_string($value)) {
            $env[$key] = $value;
        }
    }
    $env['PCOV_DIRECTORY'] = 'app';

    $process = proc_open(
        $cmd,
        $descriptor,
        $pipes,
        $root,
        $env
    );

    if (! is_resource($process)) {
        fwrite(STDERR, "build-coverage-scope-csv: failed to start pest\n");

        return 1;
    }

    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]) ?: '';
    $stderr = stream_get_contents($pipes[2]) ?: '';
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit = proc_close($process);

    if ($stdout !== '') {
        fwrite(STDOUT, $stdout);
    }
    if ($stderr !== '') {
        fwrite(STDERR, $stderr);
    }

    return $exit;
}

/**
 * Map repo-relative paths to line-coverage percentages from a Clover XML report.
 *
 * @return array<string, float>|null
 */
function readCloverLinePercents(string $root, string $cloverPath): ?array
{
    $xml = new DOMDocument;

    if (! @$xml->load($cloverPath)) {
        fwrite(STDERR, "build-coverage-scope-csv: could not parse clover at {$cloverPath}\n");

        return null;
    }

    $xpath = new DOMXPath($xml);
    $percents = [];

    foreach ($xpath->query('//file') ?: [] as $fileNode) {
        if (! $fileNode instanceof DOMElement) {
            continue;
        }

        $name = $fileNode->getAttribute('name');
        if ($name === '') {
            continue;
        }

        $relative = normalizeRel($root, $name);
        $metrics = null;

        foreach ($fileNode->childNodes as $child) {
            if ($child instanceof DOMElement && $child->tagName === 'metrics') {
                $metrics = $child;
                break;
            }
        }

        if ($metrics === null) {
            continue;
        }

        $statements = (int) $metrics->getAttribute('statements');
        $covered = (int) $metrics->getAttribute('coveredstatements');

        if ($statements <= 0) {
            $percents[$relative] = 100.0;

            continue;
        }

        $percents[$relative] = round(($covered / $statements) * 100, 1);
    }

    return $percents;
}

/**
 * Format a percentage for the CSV (trim trailing .0).
 */
function formatPercent(float $value): string
{
    if (abs($value - round($value)) < 0.05) {
        return (string) (int) round($value);
    }

    return number_format($value, 1, '.', '');
}

/**
 * Read `<source>` from phpunit.xml and return sorted repo-relative paths.
 *
 * @return list<string>|null
 */
function listCoveragePopulation(string $root, ?string $phpunitPath): ?array
{
    if ($phpunitPath === null) {
        foreach (['phpunit.xml', 'phpunit.xml.dist'] as $candidate) {
            $try = $root.'/'.$candidate;

            if (is_file($try)) {
                $phpunitPath = $try;
                break;
            }
        }
    } elseif (! is_file($phpunitPath)) {
        $try = $root.'/'.str_replace('\\', '/', $phpunitPath);

        if (is_file($try)) {
            $phpunitPath = $try;
        }
    }

    if ($phpunitPath === null || ! is_file($phpunitPath)) {
        fwrite(STDERR, "build-coverage-scope-csv: phpunit.xml (or phpunit.xml.dist) not found at the repository root.\n");

        return null;
    }

    $xml = new DOMDocument;

    if (! @$xml->load($phpunitPath)) {
        fwrite(STDERR, "build-coverage-scope-csv: could not parse {$phpunitPath}\n");

        return null;
    }

    $xpath = new DOMXPath($xml);
    $includes = [];
    $excludes = [];

    foreach ($xpath->query('//source/include/directory') ?: [] as $node) {
        $includes[] = [
            'type' => 'directory',
            'path' => trim($node->textContent),
            'suffix' => $node->attributes?->getNamedItem('suffix')?->nodeValue ?? '.php',
        ];
    }

    foreach ($xpath->query('//source/include/file') ?: [] as $node) {
        $includes[] = [
            'type' => 'file',
            'path' => trim($node->textContent),
            'suffix' => null,
        ];
    }

    foreach ($xpath->query('//source/exclude/directory') ?: [] as $node) {
        $excludes[] = [
            'type' => 'directory',
            'path' => trim($node->textContent),
        ];
    }

    foreach ($xpath->query('//source/exclude/file') ?: [] as $node) {
        $excludes[] = [
            'type' => 'file',
            'path' => trim($node->textContent),
        ];
    }

    if ($includes === []) {
        fwrite(STDERR, "build-coverage-scope-csv: no <source><include> entries in {$phpunitPath}\n");

        return null;
    }

    $files = [];

    foreach ($includes as $include) {
        $absolute = resolveAgainstRoot($root, $include['path']);

        if ($include['type'] === 'file') {
            if (is_file($absolute)) {
                $files[normalizeRel($root, $absolute)] = true;
            }

            continue;
        }

        if (! is_dir($absolute)) {
            continue;
        }

        $suffix = $include['suffix'] ?? '.php';
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $absolute,
                FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $fileInfo) {
            if (! $fileInfo->isFile()) {
                continue;
            }

            $name = $fileInfo->getFilename();

            if ($suffix !== '' && ! str_ends_with($name, $suffix)) {
                continue;
            }

            $files[normalizeRel($root, $fileInfo->getPathname())] = true;
        }
    }

    foreach (array_keys($files) as $relative) {
        if (isExcluded($relative, $excludes)) {
            unset($files[$relative]);
        }
    }

    $listed = array_keys($files);
    sort($listed);

    return $listed;
}

/**
 * Resolve a phpunit.xml path (often `./app`) against the repository root.
 */
function resolveAgainstRoot(string $root, string $path): string
{
    $trimmed = preg_replace('#^\./#', '', str_replace('\\', '/', $path)) ?? $path;

    return $root.'/'.$trimmed;
}

/**
 * Turn an absolute path into a forward-slash path relative to the repository root.
 */
function normalizeRel(string $root, string $absolute): string
{
    $rootNorm = rtrim(str_replace('\\', '/', $root), '/').'/';
    $absNorm = str_replace('\\', '/', $absolute);

    if (str_starts_with($absNorm, $rootNorm)) {
        return substr($absNorm, strlen($rootNorm));
    }

    return $absNorm;
}

/**
 * Whether `$relative` matches a `<source><exclude>` entry.
 *
 * @param  array<int, array{type: string, path: string}>  $excludes
 */
function isExcluded(string $relative, array $excludes): bool
{
    $relative = str_replace('\\', '/', $relative);

    foreach ($excludes as $exclude) {
        $rule = preg_replace('#^\./#', '', str_replace('\\', '/', $exclude['path'])) ?? $exclude['path'];
        $rule = rtrim($rule, '/');

        if ($exclude['type'] === 'file' && $relative === $rule) {
            return true;
        }

        if ($exclude['type'] === 'directory' && ($relative === $rule || str_starts_with($relative, $rule.'/'))) {
            return true;
        }
    }

    return false;
}

/**
 * @return array<string, array{0: string, 1: string, 2: string}>
 */
function readPriorCsv(string $path): array
{
    if (! is_file($path)) {
        return [];
    }

    $handle = fopen($path, 'rb');
    if ($handle === false) {
        return [];
    }

    $header = fgetcsv($handle, null, ',', '"', '\\');
    if ($header === false) {
        fclose($handle);

        return [];
    }

    $prior = [];
    while (($row = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
        if ($row === [null] || ($row[0] ?? '') === '') {
            continue;
        }

        $prior[(string) $row[0]] = [
            (string) ($row[1] ?? ''),
            (string) ($row[2] ?? ''),
            (string) ($row[3] ?? ''),
        ];
    }

    fclose($handle);

    return $prior;
}

/**
 * @return class-string|null
 */
function fqcnFromFile(string $absolutePath): ?string
{
    if (! is_file($absolutePath)) {
        return null;
    }

    $code = file_get_contents($absolutePath);
    if ($code === false) {
        return null;
    }

    if (! preg_match('/^namespace\s+([^;]+);/m', $code, $namespaceMatch)) {
        return null;
    }

    if (! preg_match('/^(?:abstract\s+|final\s+|readonly\s+)*(?:class|interface|enum|trait)\s+(\w+)/m', $code, $classMatch)) {
        return null;
    }

    return $namespaceMatch[1].'\\'.$classMatch[1];
}

function shortName(string $fqcn): string
{
    $pos = strrpos($fqcn, '\\');

    return $pos === false ? $fqcn : substr($fqcn, $pos + 1);
}

/**
 * @param  array<string, string>  $testContents
 */
function pairedTestMentionsClass(
    array $testContents,
    string $sourceRelative,
    string $short
): bool {
    $base = basename($sourceRelative, '.php');

    foreach ($testContents as $testPath => $chunk) {
        if (! str_ends_with($testPath, $base.'Test.php')) {
            continue;
        }

        if (
            str_contains($chunk, $short.'::')
            || str_contains($chunk, 'new '.$short)
            || str_contains($chunk, $short.'::class')
            || preg_match('/\buse\s+[A-Za-z0-9_\\\\]*\\\\'.preg_quote($short, '/').';/', $chunk) === 1
        ) {
            return true;
        }
    }

    return false;
}

/**
 * Walk up from `$start` until a directory holding `vendor/autoload.php` or a
 * phpunit config is found.
 */
function findRepositoryRoot(string $start): ?string
{
    $dir = realpath($start) ?: $start;

    while (true) {
        if (
            is_file($dir.'/vendor/autoload.php')
            || is_file($dir.'/phpunit.xml')
            || is_file($dir.'/phpunit.xml.dist')
        ) {
            return $dir;
        }

        $parent = dirname($dir);
        if ($parent === $dir) {
            return null;
        }

        $dir = $parent;
    }
}
