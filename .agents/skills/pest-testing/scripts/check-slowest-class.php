<?php

declare(strict_types=1);

/**
 * Slowest-class cost guard: fail a test run whose costliest test class takes too large
 * a share of the run's summed class time, or exceeds an absolute backstop.
 *
 * A gate reports PASS on any duration, so nothing pins what a suite costs, and a change
 * that doubles per-test cost, or lets one class grow until it binds a parallel run,
 * passes in silence. Both have happened without anything going red.
 *
 * WHAT THE NUMBER IS. A JUnit `time` attribute is the wall-clock time each test took,
 * not the CPU time it used, so a class's summed `time` moves with machine load exactly
 * as the run's wall time does: on an oversubscribed host every class inflates together.
 * An absolute ceiling on that number is a flaky wall-time assertion one level down.
 *
 * WHY A SHARE. The costliest class's share of the summed class time is dimensionless,
 * so inflation that hits every class alike cancels out of it. It is also the quantity
 * that decides whether a class binds a parallel run: paratest distributes by class, so
 * no scheduler finishes sooner than its largest single unit, and a class starts
 * costing wall-clock once its share passes 1/workers.
 *
 * WHY AN ABSOLUTE BACKSTOP AS WELL. A share cannot see growth that hits every class at
 * once, such as a per-test boot cost, which raises every class together and leaves
 * every share where it was. The backstop catches that, and it is set far enough above
 * contended readings that load alone does not reach it.
 *
 * WHY A FLOOR. A suite that sums to a fraction of a second has no parallel run worth
 * balancing, and with one booting class it takes most of the summed time however it is
 * split, so a share ceiling fails it with nothing to fix.
 * Below `--share-floor` seconds of summed class time the share check is skipped and says
 * so; the backstop is still checked.
 *
 * KNOWN LIMIT. A share cancels inflation spread across the run, not a load spike
 * confined to the window in which the costliest class runs. In a serial run that window
 * holds one class, so the share is less stable there and wants a looser ceiling.
 *
 * The signal costs nothing extra: a run already logging `--log-junit` carries a `time`
 * attribute per `<testcase>`.
 *
 * Written in PHP rather than Node so that a CI job which sets up PHP and nothing else
 * can run it without a new toolchain pin.
 *
 * USAGE
 *     php check-slowest-class.php --report=<junit.xml> [--band=<name>]
 *                                 [--share-ceiling=<percent>] [--ceiling=<seconds>]
 *                                 [--share-floor=<seconds>]
 *
 *     --report         JUnit report written by `pest --log-junit=<path>`
 *     --band           A label for the output only. Defaults to `suite`.
 *     --share-ceiling  The largest share of summed class time one class may take, in
 *                      percent. Defaults to 25.
 *     --ceiling        The largest time one class may take, in seconds. Defaults to 900.
 *     --share-floor    The summed class time, in seconds, below which the share check is
 *                      skipped. Defaults to 0, which never skips it.
 *
 * THRESHOLDS
 *     The defaults are deliberately loose starting points, not measurements of any
 *     suite. Replace them with values derived from the suite being guarded: measure the
 *     costliest class's share and time on several quiet and several busy runs, then set
 *     the share ceiling a margin above the worst observed share and below 100 divided
 *     by the number of parallel workers (the point at which a class starts binding the
 *     run), and the backstop several times the worst quiet reading. Pass them as flags
 *     from the CI step so the numbers are reviewable beside the command that uses them.
 *     Raising either is a deliberate act: a class near its ceiling is closing on the
 *     point where it bounds the run while other workers idle, and splitting it is
 *     usually better than moving the number.
 *
 * EXIT
 *     0  the costliest class is within both its share ceiling and the backstop, or the
 *        summed class time is under the share floor and the class is within the backstop
 *     1  it is over either one, or the report is missing, cannot be parsed, or carries no
 *        durations. A run that produced no usable report has not been verified, and
 *        passing it would reinstate the silence this guard exists to remove.
 */
const DEFAULT_SHARE_CEILING_PERCENT = 25.0;
const DEFAULT_BACKSTOP_SECONDS = 900.0;
const DEFAULT_SHARE_FLOOR_SECONDS = 0.0;

/**
 * Sum each test class's `time` across every `<testcase>` in a JUnit report.
 *
 * Parsed with DOM rather than by regex because a test name is free text that reaches
 * the report as an attribute value, and libxml does not escape `>` there. An
 * attribute-scanning pattern ends its match on that `>` and re-enters the document
 * mid-element, shifting every case boundary after it.
 *
 * Aggregated from `<testcase>` rather than read off `<testsuite time="...">` because
 * paratest merges per-process reports: one class can appear as several `<testsuite>`
 * elements, and taking any single one under-reports the class. Summing the leaves is
 * correct under either shape.
 *
 * @param  string  $xml  The JUnit report contents
 * @return array<string, float> Total seconds per class, highest first
 *
 * @throws RuntimeException When the report is not parseable XML, or carries no timings
 */
function slowestClassTotals(string $xml): array
{
    if (trim($xml) === '') {
        throw new RuntimeException('The JUnit report is empty, so the band cannot be verified.');
    }

    $document = new DOMDocument;

    // A truncated report (a run killed mid-write) must fail, never read as zero.
    if (! @$document->loadXML($xml)) {
        throw new RuntimeException('The JUnit report is not parseable XML, so the band cannot be verified.');
    }

    $totals = [];

    /** @var DOMNodeList<DOMElement> $cases */
    $cases = (new DOMXPath($document))->query('//testcase');

    foreach ($cases as $case) {
        // paratest's merger writes `class`; PHPUnit's own logger writes `classname`.
        // A case carrying neither is still counted, under a name that says so:
        // dropping it would be the silent under-report this guard exists to remove.
        $class = $case->getAttribute('class') !== ''
            ? $case->getAttribute('class')
            : ($case->getAttribute('classname') !== '' ? $case->getAttribute('classname') : '(unattributed)');

        $totals[$class] = ($totals[$class] ?? 0.0) + (float) $case->getAttribute('time');
    }

    if ($totals === []) {
        throw new RuntimeException('The JUnit report holds no <testcase> elements, so the band cannot be verified.');
    }

    // Every case timed at zero means the report carries no usable durations, so a PASS
    // here would assert nothing at all. That is a setup failure, not a pass.
    if (array_sum($totals) <= 0.0) {
        throw new RuntimeException('The JUnit report carries no test durations, so the band cannot be verified.');
    }

    arsort($totals);

    return $totals;
}

/**
 * Judge the costliest class against its share ceiling and the absolute backstop.
 *
 * @param  array<string, float>  $totals  Total seconds per class, highest first
 * @param  float  $shareCeiling  The share ceiling, in percent of summed class time
 * @param  float  $backstop  The absolute per-class backstop in seconds
 * @param  float  $shareFloor  The summed class time in seconds below which the share check is skipped
 * @return array{ok: bool, class: string, seconds: float, summed: float, share: float, shareCeiling: float, shareOk: bool, shareSkipped: bool, shareFloor: float, backstop: float, backstopOk: bool}
 */
function evaluateSlowestClass(array $totals, float $shareCeiling, float $backstop, float $shareFloor = DEFAULT_SHARE_FLOOR_SECONDS): array
{
    $class = (string) array_key_first($totals);
    $seconds = $totals[$class];
    $summed = array_sum($totals);
    $share = 100.0 * $seconds / $summed;

    // A suite under the floor has no parallel run worth balancing, so its share is not judged.
    $shareSkipped = $summed < $shareFloor;

    // Compared without dividing: `100 * x / x` can round above 100 (x = 0.007), which would
    // fail a one-class suite at --share-ceiling=100. Multiplied out, a class that is the
    // whole suite compares identical products.
    $shareOk = $shareSkipped || 100.0 * $seconds <= $shareCeiling * $summed;
    $backstopOk = $seconds <= $backstop;

    return [
        'ok' => $shareOk && $backstopOk,
        'class' => $class,
        'seconds' => $seconds,
        'summed' => $summed,
        'share' => $share,
        'shareCeiling' => $shareCeiling,
        'shareOk' => $shareOk,
        'shareSkipped' => $shareSkipped,
        'shareFloor' => $shareFloor,
        'backstop' => $backstop,
        'backstopOk' => $backstopOk,
    ];
}

$options = getopt('', ['report:', 'band::', 'share-ceiling::', 'ceiling::', 'share-floor::']);
$report = $options['report'] ?? null;
$band = is_string($options['band'] ?? null) ? $options['band'] : 'suite';

if (! is_string($report)) {
    fwrite(STDERR, "Usage: php check-slowest-class.php --report=<junit.xml> [--band=<name>] [--share-ceiling=<percent>] [--ceiling=<seconds>] [--share-floor=<seconds>]\n");
    exit(1);
}

// An override that does not parse must fail rather than fall back to the default,
// since judging the band against a ceiling nobody asked for is a verdict on the wrong
// question. Read from argv rather than from getopt, which reports `--share-ceiling 5`
// (a space, not `=`) as a flag with no value and drops an empty `--ceiling=` altogether.
foreach (array_slice($argv, 1) as $argument) {
    if (preg_match('/^--(share-ceiling|ceiling)(?:=(.*))?$/', (string) $argument, $flag) === 1) {
        $value = $flag[2] ?? '';

        if (! is_numeric($value) || (float) $value <= 0.0) {
            fwrite(STDERR, "Slowest class: --{$flag[1]} needs a positive number, written as --{$flag[1]}=<value>.\n");
            exit(1);
        }
    }

    // The floor may be 0, which turns the skip off.
    if (preg_match('/^--share-floor(?:=(.*))?$/', (string) $argument, $flag) === 1) {
        $value = $flag[1] ?? '';

        if (! is_numeric($value) || ! is_finite((float) $value) || (float) $value < 0.0) {
            fwrite(STDERR, "Slowest class: --share-floor needs a number of seconds, 0 or more, written as --share-floor=<value>.\n");
            exit(1);
        }
    }
}

$shareCeiling = is_string($options['share-ceiling'] ?? null)
    ? (float) $options['share-ceiling']
    : DEFAULT_SHARE_CEILING_PERCENT;

$backstop = is_string($options['ceiling'] ?? null)
    ? (float) $options['ceiling']
    : DEFAULT_BACKSTOP_SECONDS;

// Printed as given, so a floor never reads the same as a summed time it is above.
$shareFloorText = is_string($options['share-floor'] ?? null) ? trim($options['share-floor']) : '0';
$shareFloor = (float) $shareFloorText;

if (! is_file($report)) {
    fwrite(STDERR, "Slowest class: no JUnit report at {$report}, so the {$band} band cannot be verified.\n");
    exit(1);
}

// A misconfigured guard must read as a failure, not as a stack trace.
try {
    $totals = slowestClassTotals((string) file_get_contents($report));
} catch (RuntimeException $failure) {
    fwrite(STDERR, "Slowest class: {$failure->getMessage()}\n");
    exit(1);
}

$result = evaluateSlowestClass($totals, $shareCeiling, $backstop, $shareFloor);

$skipped = sprintf(
    "share check skipped: %s band's summed class time %.3fs is under the share floor %ss. Its costliest class is %s at %.3fs, %.2f%% of the summed time (share ceiling %.2f%%, not applied; backstop %.1fs, applied).\n",
    $band,
    $result['summed'],
    $shareFloorText,
    $result['class'],
    $result['seconds'],
    $result['share'],
    $result['shareCeiling'],
    $result['backstop'],
);

if ($result['ok'] && $result['shareSkipped']) {
    echo 'Slowest class OK, '.$skipped;
    exit(0);
}

if ($result['shareSkipped']) {
    fwrite(STDERR, 'Slowest class: '.$skipped);
}

if ($result['ok']) {
    printf(
        "Slowest class OK: %s band's costliest class is %s at %.1fs, %.2f%% of %.1fs summed class time (share ceiling %.2f%%, backstop %.1fs).\n",
        $band,
        $result['class'],
        $result['seconds'],
        $result['share'],
        $result['summed'],
        $result['shareCeiling'],
        $result['backstop'],
    );
    exit(0);
}

if (! $result['shareOk']) {
    fwrite(STDERR, sprintf(
        "Slowest class EXCEEDED: %s band's %s is %.2f%% of %.1fs summed class time, share ceiling %.2f%% (over by %.2f points).\n",
        $band,
        $result['class'],
        $result['share'],
        $result['summed'],
        $result['shareCeiling'],
        $result['share'] - $result['shareCeiling'],
    ));
}

if (! $result['backstopOk']) {
    fwrite(STDERR, sprintf(
        "Slowest class EXCEEDED: %s band's %s cost %.1fs, backstop %.1fs (over by %.1fs).\n",
        $band,
        $result['class'],
        $result['seconds'],
        $result['backstop'],
        $result['seconds'] - $result['backstop'],
    ));
}

fwrite(STDERR, "Costliest classes in this band:\n");

foreach (array_slice($totals, 0, 5, true) as $class => $seconds) {
    fwrite(STDERR, sprintf("  %8.1fs  %6.2f%%  %s\n", $seconds, 100.0 * $seconds / $result['summed'], $class));
}

fwrite(
    STDERR,
    "\nparatest distributes by CLASS, so a run cannot finish sooner than its largest "
    .'one: a class this size bounds the whole run while the other workers idle. Split the '
    .'class rather than raising a ceiling. A share is load-invariant, so a share failure '
    .'is a property of the change, not of a busy host. `vendor/bin/pest --update-shards` '
    .'writes a per-class profile to diagnose it, but it runs the suite functionally at '
    ."roughly 4x the CPU, so it is a follow-up diagnostic and never a band time.\n"
);

exit(1);
