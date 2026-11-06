<?php

declare(strict_types=1);

use Symfony\Component\Process\Process;

/**
 * Report whether `pest --mutate` on this machine can actually execute a mutant.
 *
 * A mutation run reports a score whether or not it ran anything, and the two outcomes are
 * byte-identical apart from the numbers. `Pest\Mutate\MutationTest::start()` spawns each
 * mutant in a child process built from `$_SERVER['argv']`, reusing **argv[0] verbatim as the
 * executable**; `hasFinished()` then reads any non-zero exit as "the test failed", i.e. the
 * mutant was killed. A child that could not be launched at all exits non-zero, so it is
 * recorded as a kill. Every mutant is then "killed", the run prints 100.00%, and it exits 0.
 *
 * PHP sets argv[0] to the script path AS TYPED, so on Windows both `vendor/bin/pest` and
 * `php vendor/bin/pest` hand the child a forward-slashed relative path that cmd.exe cannot
 * launch. Measured on Windows 11 Pro 10.0.26200 / PHP 8.4.20 / pest-plugin-mutate v5.0.2,
 * one class, one tree, cache cleared between arms (uams-statamic-archive#2322):
 *
 *   vendor/bin/pest        (forward)   20 tested,  0 untested   100.00%   fabricated
 *   php vendor/bin/pest    (forward)   20 tested,  0 untested   100.00%   fabricated
 *   php vendor\bin\pest    (separator) 14 tested,  6 untested    70.00%   executed
 *   composer mutate        (separator) 14 tested,  6 untested    70.00%   executed
 *
 * THE CRITERION IS DISAGREEMENT, NOT SPEED. A run reporting zero survivors on a class nobody
 * has hardened is a NULL result, not a clean one: a run that executes nothing cannot disagree
 * with itself, so surviving mutants are positive evidence of execution in a way that duration
 * is not. Per-mutant wall time is a weaker secondary signal only -- fast has innocent
 * explanations, which is why a stale result cache had to be excluded separately before speed
 * meant anything. This script supplies the mechanical half of that control: it proves the
 * child can be launched, which is what fabricates the kills when it cannot.
 *
 * WHAT IT CHECKS
 *
 *   1. The launch probe. Spawns each candidate argv[0] with `--version` through the same
 *      Symfony `Process` the plugin uses, and reports which forms can start a child. This
 *      MEASURES whether a child starts rather than branching on the operating system, so
 *      correct on a platform where forward slashes are fine.
 *   2. Whether the installed plugin carries a launch patch that normalizes argv[0] and
 *      aborts when the child still cannot start (uams-statamic-archive#2322's `canLaunchMutantProcess`).
 *      `composer install` does not re-patch an already-installed package, so a `vendor/`
 *      predating the patch is unguarded while reporting the same version.
 *
 *   3. The default-invocation probe. Launching is not reaching a test. A Unit suite whose bootstrap
 *      loads Patchwork (the `wordpress-plugin` template's does, for Brain Monkey) loses every
 *      mutant child that scans a directory for tests: Pest's mutation stream wrapper and
 *      Patchwork's both replace `file://`, and a directory read through the pair dies with
 *      `readdir(): Argument #1 ($dir_handle) must be of type resource or null, true given`. A
 *      child that crashes exits non-zero, so it is scored as a kill. Naming the test file avoids
 *      the scan, which is why a run can be honest with the file named and fabricated without
 *      it (UAMS-Web/uams-claude-skills#131). The probe builds an
 *      identical copy of one source file, sets the two environment variables the plugin sets
 *      for a child, and runs the child with `--list-tests` through the first form that
 *      launched, plus any `--arg=` given. Test discovery happens before `--list-tests` lists
 *      anything, so the child reaches the same directory read a real child reaches, without
 *      executing a test. Its control is the same command without the two variables, which must
 *      list tests; a control that fails means the probe measured nothing, and exits 2.
 *
 *      Measured on Windows 11 / PHP 8.4.20 / Pest 5.3.0 / pest-plugin-mutate 5.0.2 /
 *      Patchwork 2.2.3 / Brain Monkey 2.7.0 (a one-class scratch project, not a UAMS-Web
 *      repository), the same weakened test file:
 *
 *        pest --mutate --covered-only --path=src/Settings.php                  3 tested  100.00%  fabricated
 *        pest tests/Unit/SettingsTest.php --mutate ... (file named)            1 tested, 2 untested  33.33%
 *        child by hand, no test named, mutation variables set                   exit 255, readdir() error
 *        child by hand, test file named                                         exit 0
 *        same child without the mutation variables (the control)               exit 0, lists the test
 *
 *      Not measured on macOS or Linux, where the original report was made (macOS, Pest 5.3.0).
 *
 * BOUND, stated because it decides how to read a clean run. A child that starts proves the
 * GROSS failure is absent. It does not prove every verdict is honest: a genuine run can still
 * misreport a single mutant as killed, and a false kill leaves NO SURVIVOR TO EXPLAIN, so the
 * "no unexplained survivors" discipline cannot see it. A clean exit here means a score is
 * worth reading, never that it is correct.
 *
 * Delivered under `.claude/skills/pcov-setup/scripts/` (and the `.agents/skills/` copy) to
 * every repository with the `pest` profile. Finds the repository root by walking up for
 * `vendor/autoload.php`.
 *
 * Usage:  php .claude/skills/pcov-setup/scripts/check-mutation-instrument.php [--root=<path>] [--runner=<path>] [--source=<path>] [--arg=<value> ...] [argv0 ...]
 *         (default argv0: the repo's pest binary; default runner: the installed plugin; default
 *         source: the first PHP file under the directory phpunit.xml's source or coverage
 *         include names, else src/, includes/ or app/; --arg adds an argument to the probe
 *         child, for example the test file a run names, or a script path when argv0 is php)
 * Exit:   0 = a mutant child can be launched and reaches test discovery (or the probe could not
 *         be set up and says NOT MEASURED), 1 = it cannot launch or dies before a test, so
 *         scores would be fabricated, 2 = the instrument could not be located, or the probe's
 *         control failed, so nothing was measured.
 *
 * `--runner` exists so this check's OWN failing branch can be exercised against a copy of the
 * unpatched plugin. A guard whose red path nobody has watched fire is the false green this
 * script is about, so the seam is deliberate rather than a convenience.
 */
// cspell:ignore argv0

$arguments = array_slice($argv, 1);
$runnerOverride = null;
$rootOverride = null;

$sourceOverride = null;
$probeArguments = [];

foreach ($arguments as $index => $argument) {
    if (str_starts_with($argument, '--runner=')) {
        $runnerOverride = substr($argument, strlen('--runner='));
        unset($arguments[$index]);
    } elseif (str_starts_with($argument, '--source=')) {
        $sourceOverride = substr($argument, strlen('--source='));
        unset($arguments[$index]);
    } elseif (str_starts_with($argument, '--arg=')) {
        $probeArguments[] = substr($argument, strlen('--arg='));
        unset($arguments[$index]);
    } elseif (str_starts_with($argument, '--root=')) {
        $rootOverride = substr($argument, strlen('--root='));
        unset($arguments[$index]);
    }
}

$arguments = array_values($arguments);

$root = $rootOverride !== null && is_dir($rootOverride)
    ? (realpath($rootOverride) ?: $rootOverride)
    : findRepositoryRoot(__DIR__);

if ($root === null || $root === false) {
    fwrite(STDERR, "check-mutation-instrument: vendor/autoload.php not found above this script; run composer install in the repository root (or pass --root=).\n");

    exit(2);
}

$autoload = $root.'/vendor/autoload.php';

if (! is_file($autoload)) {
    fwrite(STDERR, "check-mutation-instrument: vendor/autoload.php is missing under {$root}; run composer install.\n");

    exit(2);
}

require $autoload;

$runnerPath = $runnerOverride ?? $root.'/vendor/pestphp/pest-plugin-mutate/src/Tester/MutationTestRunner.php';

if (! is_file($runnerPath)) {
    fwrite(STDERR, "check-mutation-instrument: pest-plugin-mutate is not installed; nothing measured.\n");

    exit(2);
}

$runnerSource = (string) file_get_contents($runnerPath);
$guarded = str_contains($runnerSource, 'canLaunchMutantProcess');

$candidates = $arguments;

if ($candidates === []) {
    $candidates = ['vendor/bin/pest'];

    if (DIRECTORY_SEPARATOR !== '/') {
        $candidates[] = str_replace('/', DIRECTORY_SEPARATOR, 'vendor/bin/pest');
    }
}

$started = [];

foreach ($candidates as $candidate) {
    $process = new Process([$candidate, '--version'], cwd: $root, timeout: 60);
    $process->run();

    $ok = $process->isSuccessful();
    $started[$candidate] = $ok;

    printf(
        "%-9s %s%s\n",
        $ok ? 'launches' : 'CANNOT',
        $candidate,
        $ok ? '' : '  -- '.trim(explode("\n", $process->getErrorOutput())[0] ?? 'no error output')
    );
}

$anyFails = in_array(false, $started, true);

echo "\n";
printf("launch patch (canLaunchMutantProcess) in the installed plugin: %s\n", $guarded ? 'present' : 'ABSENT');

$launchFabricates = $anyFails && ! $guarded;

if ($launchFabricates) {
    echo "check-mutation-instrument: FABRICATION LIKELY.\n";
    echo "A form above cannot launch a child, and the installed plugin has no guard, so every mutant\n";
    echo "reached through that form is scored KILLED without being executed and the run exits 0.\n";
    echo "Re-apply any launch patch (composer install into a cold vendor/), or invoke via a form that launches.\n";
} elseif ($anyFails) {
    echo "launch: a form above cannot launch, but the installed plugin normalizes argv[0] and aborts\n";
    echo "rather than scoring a child that never started as a kill.\n";
} else {
    echo "launch: a mutant child can be launched from every form measured.\n";
}

echo "\n";

$launcher = array_search(true, $started, true);
$probe = $launcher === false
    ? ['status' => 'not measured', 'detail' => 'no form launched, so there is no child to probe']
    : probeDefaultInvocation($root, (string) $launcher, $probeArguments, $sourceOverride);

if ($probe['status'] === 'reaches') {
    echo "default invocation: OK -- {$probe['detail']}\n";
} elseif ($probe['status'] === 'dies') {
    echo "default invocation: FABRICATION LIKELY -- {$probe['detail']}\n";
    echo "A child that dies before any test exits non-zero, which the plugin scores as a KILL, so a run\n";
    echo "through this invocation reports kills nothing earned. If the cause is Patchwork's stream\n";
    echo "wrapper, name the test file(s) in the --mutate run (pest <test file> --mutate ...), which avoids\n";
    echo "the directory scan, and pass the same file here with --arg=<test file> to confirm.\n";
} else {
    echo "default invocation: NOT MEASURED -- {$probe['detail']}\n";
}

if ($probe['status'] === 'control failed') {
    exit(2);
}

if ($launchFabricates || $probe['status'] === 'dies') {
    exit(1);
}

if ($probe['status'] === 'not measured') {
    echo "check-mutation-instrument: OK for launching, but the default-invocation probe did not run, so\n";
    echo "whether a child reaches a test is unmeasured. Pass --source=<a source file> to run it.\n";
} else {
    echo "check-mutation-instrument: OK -- a mutant child launches and reaches test discovery.\n";
}

echo "Still read the score itself for SURVIVORS: zero survivors on an unhardened class is a null result.\n";

exit(0);

/**
 * Walk up from `$start` until a directory holding `vendor/autoload.php` is found.
 *
 * @param  string  $start  Directory to begin walking from
 * @return string|null Absolute repository root, or null when none is found
 */
function findRepositoryRoot(string $start): ?string
{
    $dir = $start;

    while (true) {
        if (is_file($dir.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php')) {
            return $dir;
        }

        $parent = dirname($dir);

        if ($parent === $dir) {
            return null;
        }

        $dir = $parent;
    }
}

/**
 * Run an identical-copy mutant child through test discovery and report whether it gets there.
 *
 * @param  string  $root  Repository root
 * @param  string  $launcher  The first argv[0] form that launched
 * @param  array<int, string>  $extra  Arguments the probe child takes before `--list-tests`
 * @param  string|null  $sourceOverride  A source file to copy, instead of the discovered one
 * @return array{status: string, detail: string} status is reaches, dies, control failed or not measured
 */
function probeDefaultInvocation(string $root, string $launcher, array $extra, ?string $sourceOverride): array
{
    $source = $sourceOverride !== null ? (realpath($sourceOverride) ?: null) : findMutationSource($root);

    if ($source === null || ! is_file($source)) {
        return ['status' => 'not measured', 'detail' => 'no source file found to copy'];
    }

    $copy = tempnam(sys_get_temp_dir(), 'mutant-copy-');

    if ($copy === false || file_put_contents($copy, (string) file_get_contents($source)) === false) {
        return ['status' => 'not measured', 'detail' => 'could not write the identical copy'];
    }

    try {
        $command = [$launcher, ...$extra, '--list-tests'];

        $control = new Process($command, cwd: $root, timeout: 120);
        $control->run();

        if (! $control->isSuccessful()) {
            return ['status' => 'control failed', 'detail' => 'the same child without the mutation variables exited '
                .$control->getExitCode().' ('.firstLine($control->getOutput().$control->getErrorOutput()).'), so the probe measured nothing'];
        }

        $child = new Process($command, cwd: $root, env: [
            'PEST_MUTATION_TESTING' => $source,
            'PEST_MUTATION_FILE' => $copy,
        ], timeout: 120);
        $child->run();

        if ($child->isSuccessful()) {
            return ['status' => 'reaches', 'detail' => 'an identical-copy child listed tests (the control listed them too).'];
        }

        $output = $child->getOutput().$child->getErrorOutput();
        $cause = preg_match('/readdir\(\)|Patchwork/i', $output) === 1
            ? "Patchwork and Pest's mutation stream wrapper cannot read a directory together"
            : 'the child exited before listing tests';

        return ['status' => 'dies', 'detail' => $cause.' (exit '.$child->getExitCode().': '.firstLine($output).'); the control without the variables listed tests'];
    } finally {
        @unlink($copy);
    }
}

/**
 * Find a PHP source file to copy: the first under a directory phpunit.xml's source or coverage
 * include names, else under src/, includes/ or app/.
 *
 * @param  string  $root  Repository root
 * @return string|null Absolute path, or null when none is found
 */
function findMutationSource(string $root): ?string
{
    $directories = [];

    foreach (['phpunit.xml', 'phpunit.xml.dist'] as $name) {
        $path = $root.'/'.$name;

        if (! is_file($path)) {
            continue;
        }

        $xml = @simplexml_load_file($path);

        if ($xml === false) {
            continue;
        }

        foreach (['source', 'coverage'] as $section) {
            foreach ($xml->xpath("//{$section}/include/directory") ?: [] as $directory) {
                $directories[] = trim((string) $directory);
            }
        }

        break;
    }

    array_push($directories, 'src', 'includes', 'app');

    foreach ($directories as $directory) {
        $full = $root.'/'.$directory;

        if (! is_dir($full)) {
            continue;
        }

        $files = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($full, FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        if ($files !== []) {
            return realpath($files[0]) ?: $files[0];
        }
    }

    return null;
}

/**
 * The first non-empty line of some output, without color codes, cut to a readable length.
 *
 * @param  string  $output  Process output
 * @return string The line, or a note that there was none
 */
function firstLine(string $output): string
{
    $clean = (string) preg_replace('/\e\[[0-9;]*m/', '', $output);

    foreach (explode("\n", $clean) as $line) {
        if (trim($line) !== '') {
            return substr(trim($line), 0, 160);
        }
    }

    return 'no output';
}
