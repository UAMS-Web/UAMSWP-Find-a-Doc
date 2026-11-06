<?php

declare(strict_types=1);

/**
 * Skip-baseline guard: fail a test run whose skipped-test count exceeds the count
 * recorded for it.
 *
 * A gate that treats "zero failures" as success reports PASS on a run that silently
 * skipped tests, having tested less than it should have. Skipping is a legitimate way
 * for a suite to degrade when an input it needs (a submodule, a fixture, a service) is
 * unavailable, and the guard does not forbid it. What it removes is the invisibility:
 * the coverage loss is indistinguishable, in the output, from a healthy run.
 *
 * Written in PHP rather than Node so that a CI job which sets up PHP and nothing else
 * can run it without a new toolchain pin.
 *
 * USAGE
 *     php check-skip-baseline.php --report=<junit.xml> --baseline=<baseline.json>
 *                                 [--band=<name>]
 *
 *     --report    JUnit report written by `pest --log-junit=<path>` (or paratest's merge)
 *     --baseline  The baseline document; see "BASELINE FORMAT"
 *     --band      Which entry of the baseline applies. Defaults to `suite`. Use one name
 *                 per independently-run group of tests (for example `parallel` and
 *                 `serial`), each with its own report.
 *
 *     Every option is written `--name=<value>`. The space form (`--band serial`), a bare
 *     or empty option and an unrecognized flag all fail, since each would otherwise be
 *     dropped and the band judged against `suite`.
 *
 * BASELINE FORMAT
 *     {
 *         "bands": {
 *             "suite": { "expected": 3, "why": "Three cases skip when X is unavailable." }
 *         }
 *     }
 *
 *     `expected` is a non-negative integer, the number of skipped cases the band is
 *     known to produce. `why` is free text and is not read by the script; it is the
 *     reviewer's record of what the skips are. A top-level `_comment` is ignored.
 *     Raising `expected` is a reviewable change to this file, and the pull request
 *     should say why each new skip is correct.
 *
 * EXIT
 *     0  at or under the baseline
 *     1  above the baseline, or the report, the baseline or the band entry is missing,
 *        unreadable or malformed. A band that produced no usable report has not been
 *        verified, and treating that as a pass would reinstate the silence this guard
 *        exists to remove.
 */

/**
 * Extract every skipped `<testcase>` from a PHPUnit or paratest JUnit report.
 *
 * The discriminator is structure, not a tag count: a passing case is written
 * self-closing (`<testcase ... />`), a skipped one wraps a `<skipped/>` child. A
 * parser keying on `<testcase` reports every test in the suite as skipped.
 *
 * Parsed with DOM rather than by regex because a test name is free text that reaches
 * the report as an attribute value, and libxml does not escape `>` there. An
 * attribute-scanning pattern ends its match on that `>` and re-enters the document
 * mid-element, so case boundaries shift: a regex parser counted the single skip of a
 * report whose passing test was named `it prefers a > b`, but reported it as
 * `(unnamed)`, losing the identity this guard exists to print.
 *
 * The skip reason is deliberately not extracted: PHPUnit writes a bare `<skipped/>`
 * with no attributes, and paratest's merger does the same, so the report holds no
 * reason to read. The test identity is what the report can prove, and it is enough to
 * go and look.
 *
 * @param  string  $xml  The JUnit report contents
 * @return list<array{name: string, class: string}>
 *
 * @throws RuntimeException When the report is empty or not parseable XML
 */
function parseSkippedCases(string $xml): array
{
    if (trim($xml) === '') {
        throw new RuntimeException('The JUnit report is empty, so the band cannot be verified.');
    }

    $document = new DOMDocument;

    // A truncated report (a run killed mid-write) must fail, never read as zero.
    if (! @$document->loadXML($xml)) {
        throw new RuntimeException('The JUnit report is not parseable XML, so the band cannot be verified.');
    }

    $skipped = [];

    /** @var DOMNodeList<DOMElement> $cases */
    $cases = (new DOMXPath($document))->query('//testcase[skipped]');

    foreach ($cases as $case) {
        // paratest's merger writes `class`; PHPUnit's own logger writes `classname`.
        $class = $case->getAttribute('class') !== ''
            ? $case->getAttribute('class')
            : $case->getAttribute('classname');

        $skipped[] = [
            'name' => $case->getAttribute('name') !== '' ? $case->getAttribute('name') : '(unnamed)',
            'class' => $class,
        ];
    }

    return $skipped;
}

/**
 * Compare a band's skips against its baseline.
 *
 * Under baseline never fails: fewer skips means more tests ran, and failing there would
 * punish the fix for a skip.
 *
 * @param  list<array{name: string, class: string}>  $skippedCases
 * @param  int  $expected  The recorded baseline for this band
 * @return array{ok: bool, actual: int, expected: int, excess: int, under: bool}
 */
function evaluateSkipBaseline(array $skippedCases, int $expected): array
{
    $actual = count($skippedCases);
    $excess = $actual - $expected;

    return [
        'ok' => $excess <= 0,
        'actual' => $actual,
        'expected' => $expected,
        'excess' => $excess,
        'under' => $excess < 0,
    ];
}

/**
 * Read a band's expected skip count out of the baseline document.
 *
 * @param  string  $baselineRaw  The baseline JSON
 * @param  string  $band  The band key
 * @return int The expected skip count
 *
 * @throws RuntimeException When the document is not JSON, or the band has no valid entry
 */
function expectedSkipsForBand(string $baselineRaw, string $band): int
{
    /** @var array{bands?: array<string, array{expected?: mixed}>}|null $parsed */
    $parsed = json_decode($baselineRaw, true);

    if (! is_array($parsed)) {
        throw new RuntimeException('The baseline document is not valid JSON.');
    }

    $expected = $parsed['bands'][$band]['expected'] ?? null;

    if (! is_int($expected) || $expected < 0) {
        throw new RuntimeException("No skip baseline recorded for band \"{$band}\": expected a non-negative integer at bands.{$band}.expected.");
    }

    return $expected;
}

$options = getopt('', ['report:', 'baseline:']);
$report = $options['report'] ?? null;
$baselinePath = $options['baseline'] ?? null;

// --band selects which baseline entry applies, so a value that is not read must fail
// rather than fall back to `suite`: judging a band against another band's entry reports
// a pass for the wrong question. getopt cannot be trusted with it. An optional-value
// option accepts only `--band=<name>`; `--band serial` (a space) reads as a flag with no
// value, and a misspelled flag is dropped without a word. Read every argument from argv,
// and refuse anything that is not one of the three documented `--name=value` forms.
$band = 'suite';

foreach (array_slice($argv, 1) as $argument) {
    if (preg_match('/^--(report|baseline|band)=(.+)$/s', (string) $argument, $flag) !== 1) {
        fwrite(STDERR, "Skip baseline: unrecognized or valueless argument \"{$argument}\"; every option is written --name=<value> (--report, --baseline, --band).\n");
        exit(1);
    }

    if ($flag[1] === 'band') {
        $band = $flag[2];
    }
}

if (! is_string($report) || ! is_string($baselinePath)) {
    fwrite(STDERR, "Usage: php check-skip-baseline.php --report=<junit.xml> --baseline=<baseline.json> [--band=<name>]\n");
    exit(1);
}

if (! is_file($report)) {
    fwrite(STDERR, "Skip baseline: no JUnit report at {$report}, so the {$band} band cannot be verified.\n");
    exit(1);
}

if (! is_file($baselinePath)) {
    fwrite(STDERR, "Skip baseline: no baseline document at {$baselinePath}.\n");
    exit(1);
}

// A misconfigured guard must read as a failure, not as a stack trace.
try {
    $expected = expectedSkipsForBand((string) file_get_contents($baselinePath), $band);
    $skipped = parseSkippedCases((string) file_get_contents($report));
} catch (RuntimeException $failure) {
    fwrite(STDERR, "Skip baseline: {$failure->getMessage()}\n");
    exit(1);
}

$result = evaluateSkipBaseline($skipped, $expected);

if ($result['ok']) {
    $note = $result['under'] ? ' (below baseline: more tests ran than expected)' : '';
    echo "Skip baseline OK: {$band} band skipped {$result['actual']}, baseline {$result['expected']}{$note}.\n";
    exit(0);
}

fwrite(STDERR, "Skip baseline EXCEEDED: {$band} band skipped {$result['actual']}, baseline {$result['expected']} (+{$result['excess']}).\n");
fwrite(STDERR, "Tests skipped in this band:\n");

foreach ($skipped as $case) {
    fwrite(STDERR, "  - {$case['class']}::{$case['name']}\n");
}

fwrite(
    STDERR,
    "\nA skip above baseline means the band tested LESS than it should have and would "
    .'otherwise have reported PASS. Find why the test stopped running (an unavailable '
    .'submodule, fixture or service, or a guard firing on an input that should have '
    ."resolved) before touching {$baselinePath}.\n"
);

exit(1);
