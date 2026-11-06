<?php

declare(strict_types=1);

/**
 * Mutation-run guard: read the output of a Pest `--mutate` run and fail unless the run
 * created mutants and every one of them was tested.
 *
 * Pest exits 0 on a run that mutated nothing unless a minimum score is passed, and a
 * zero-mutant run prints `0 Mutations for 0 Files created` and a score that reads as a
 * verdict. That output is a setup failure (no coverage driver, or a path the finder could
 * not resolve), not a pass, and a CI job that trusts the exit status alone cannot tell the
 * two apart. This script reads what the run reported instead.
 *
 * Written in PHP so a CI job that sets up PHP and nothing else can run it.
 *
 * USAGE
 *     composer mutate -- --min=100 2>&1 | tee mutate.log
 *     php check-mutation-run.php mutate.log [--min=<score>]
 *
 *     --min  The lowest acceptable score, a percentage. Defaults to 100. Written
 *            `--min=<value>`; any other flag or a second path fails.
 *
 * WHAT FAILS
 *     - no `N Mutations for M Files created` line (the log holds no mutation run)
 *     - N or M is 0 (nothing was mutated)
 *     - any mutant reported untested, uncovered, pending or timed out. A timeout is
 *       unverified, never a kill.
 *     - no mutant tested, or no `Score:` line, or a score under the minimum
 *
 * EXIT
 *     0  every mutant was tested and the score meets the minimum
 *     1  a finding above
 *     2  the log, or an option, could not be read
 */
const ANSI_PATTERN = '/\x1b\[[0-9;]*m/';

/** The run's own counts, read from its summary lines. Null where a line is absent. */
function parseRun(string $log): array
{
    $text = preg_replace(ANSI_PATTERN, '', $log) ?? $log;
    $run = ['total' => null, 'files' => null, 'counts' => null, 'score' => null, 'lines' => []];

    if (preg_match('/^\s*(\d+) Mutations for (\d+) Files created\s*$/m', $text, $m) === 1) {
        $run['total'] = (int) $m[1];
        $run['files'] = (int) $m[2];
    }

    if (preg_match('/^\s*Mutations:\s*(.+?)\s*$/m', $text, $m) === 1) {
        $counts = [];
        foreach (explode(',', $m[1]) as $part) {
            if (preg_match('/^\s*(\d+) (untested|uncovered|pending|timeout|tested)\s*$/', $part, $p) === 1) {
                $counts[$p[2]] = (int) $p[1];
            }
        }
        $run['counts'] = $counts;
    }

    if (preg_match('/^\s*Score:\s*([0-9.]+)%\s*$/m', $text, $m) === 1) {
        $run['score'] = (float) $m[1];
    }

    if (preg_match_all('/^\s*(UNTESTED|UNCOVERED|TIMEOUT|PENDING)\s+(\S.*?)\s*$/m', $text, $all, PREG_SET_ORDER) > 0) {
        foreach ($all as $hit) {
            $run['lines'][] = preg_replace('/\s+/', ' ', $hit[1].' '.$hit[2]);
        }
    }

    return $run;
}

/** Findings for a parsed run, empty when the run is clean. */
function evaluateRun(array $run, float $min): array
{
    if ($run['total'] === null) {
        return ['the log holds no `N Mutations for M Files created` line: no mutation run was read'];
    }

    if ($run['total'] === 0 || $run['files'] === 0) {
        return ['the run created no mutants ('.$run['total'].' mutations, '.$run['files'].' files): a setup failure, usually a missing coverage driver or a path the finder could not resolve, never a pass'];
    }

    $findings = [];
    $counts = $run['counts'];

    if ($counts === null || $counts === []) {
        $findings[] = 'the log holds no readable `Mutations:` line';
    } else {
        foreach (['untested' => 'survived', 'uncovered' => 'uncovered by any test', 'pending' => 'did not run', 'timeout' => 'timed out (unverified, not a kill)'] as $key => $meaning) {
            if (($counts[$key] ?? 0) > 0) {
                $findings[] = $counts[$key].' mutant(s) '.$meaning;
            }
        }
        if (($counts['tested'] ?? 0) === 0) {
            $findings[] = 'no mutant was tested';
        }
    }

    if ($run['score'] === null) {
        $findings[] = 'the log holds no `Score:` line';
    } elseif ($run['score'] < $min) {
        $findings[] = sprintf('score %.2f%% is under the minimum %.2f%%', $run['score'], $min);
    }

    return $findings;
}

/** @return array{0: string, 1: float}|string The log path and minimum, or a message naming the bad option. */
function parseArguments(array $argv): array|string
{
    $path = null;
    $min = 100.0;

    foreach (array_slice($argv, 1) as $arg) {
        if (preg_match('/^--min=([0-9]+(?:\.[0-9]+)?)$/', $arg, $m) === 1) {
            $min = (float) $m[1];
        } elseif (str_starts_with($arg, '-')) {
            return 'unrecognized option `'.$arg.'`; the only option is `--min=<score>`';
        } elseif ($path === null) {
            $path = $arg;
        } else {
            return 'one log path only, got a second: `'.$arg.'`';
        }
    }

    return $path === null ? 'pass the path of the mutation run log' : [$path, $min];
}

function main(array $argv): int
{
    $parsed = parseArguments($argv);
    if (is_string($parsed)) {
        fwrite(STDERR, 'check-mutation-run: '.$parsed.PHP_EOL);

        return 2;
    }

    [$path, $min] = $parsed;
    $log = is_file($path) ? file_get_contents($path) : false;
    if ($log === false) {
        fwrite(STDERR, 'check-mutation-run: cannot read `'.$path.'`'.PHP_EOL);

        return 2;
    }

    $run = parseRun($log);

    // The denominator comes before any finding, so a run that read nothing is visible.
    echo sprintf(
        'check-mutation-run: mutations=%s files=%s tested=%s score=%s minimum=%.2f',
        $run['total'] ?? 'none',
        $run['files'] ?? 'none',
        $run['counts']['tested'] ?? 'none',
        $run['score'] === null ? 'none' : sprintf('%.2f', $run['score']),
        $min,
    ).PHP_EOL;

    $findings = evaluateRun($run, $min);
    foreach ($findings as $finding) {
        echo 'FAIL: '.$finding.PHP_EOL;
    }
    foreach ($run['lines'] as $line) {
        echo '  '.$line.PHP_EOL;
    }

    return $findings === [] ? 0 : 1;
}

if (PHP_SAPI === 'cli' && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
    exit(main($argv));
}
