<?php

declare(strict_types=1);

/**
 * Measure how many mutants each `@pest-mutate-ignore` annotation removes.
 *
 * `check-mutate-ignore.php`, beside this file, reads the marker's TEXT, so it finds an
 * annotation that cannot apply. It cannot find one that applies too widely, or one whose
 * spelling and placement are both right and that still removes nothing, because breadth is
 * a property of the AST rather than of the line. This script measures it with the
 * installed plugin. It began as `scripts/check-mutate-ignore-breadth.php` in
 * `uams-statamic` (UAMS-Web/uams-statamic-archive#3160) and is shared from
 * UAMS-Web/uams-claude-skills#360.
 *
 * HOW IT COUNTS. For each annotation, count the mutants of each mutator it names, then
 * count again with that one marker made inert; the difference is what the annotation
 * removes. A bare marker names every mutator. The counting loop is
 * `Pest\Mutate\Support\MutationGenerator::generate()` with its cache call removed, since
 * that cache needs a Pest container binding only a real run provides. Everything that
 * decides suppression stays the installed plugin's: `PhpParserFactory`,
 * `NodeTraverserFactory`, `NodeVisitor` and the mutator classes. The per-line ignore map is
 * built the way the generator builds it: split on `PHP_EOL`, and with the generator's
 * `$mutatorsToIgnore` carried from one marker line to the next, so a bare marker below a
 * named one is read as the named one, as the plugin reads it. A run therefore reports what
 * the plugin on this runtime would do.
 *
 * WHY THE MARKER IS MADE INERT RATHER THAN THE LINE DELETED. Deleting a line renumbers
 * every line after it, which moves the other annotations' entries in the plugin's line
 * map, and can change which node a docblock attaches to. Renaming the marker on that one
 * line keeps the comment, its node and every line number where they were, so the only
 * difference between the two counts is the marker.
 *
 * Flagged:
 *
 *   1. A named mutator the annotation removes more than one mutant of. One mutant per
 *      named mutator is what a reason written for one expression covers; more means the
 *      annotation reached operands its reason never named.
 *   2. An annotation that removes no mutant at all: a marker naming a mutator that does
 *      not apply to the node, or one the plugin's exclusions stop from firing.
 *   3. A mention of the marker that removes mutants. An annotation is a marker that opens a
 *      comment's content; any other line holding the marker text (a trailing comment that
 *      mentions it, a string) is still honored by the plugin's line map, which keys on the
 *      text alone. Such a line is measured across every mutator and flagged when it removes
 *      any mutant; one that removes none is documentation and is not reported.
 *
 * A bare marker removes every mutant in the node and its children, so it is reported with
 * its total and flagged only when that total is zero. `check-mutate-ignore.php` flags every
 * bare marker on its own account.
 *
 * CONTROLS. Before reading a project file the script measures six planted sources with the
 * installed plugin's `GreaterToGreaterOrEqual` and `PlusToMinus`: standalone annotations
 * that must remove one, two and no mutants (comment attachment), a trailing annotation that
 * must remove two (the line map), a bare trailing marker that must remove none because it
 * inherits the name above it (the generator's carried `$mutatorsToIgnore`), and a mention
 * that must remove one. If any reading differs, or either mutator does not resolve, it exits
 * 2 without reading the project, because a counter that cannot tell those apart reports
 * nothing about the files.
 *
 * BASELINE. Findings that predate this check are listed in a committed baseline, keyed by
 * path, the marker line's text and the measured counts rather than by line number, so an
 * edit elsewhere in a file does not move them. The baseline is
 * `tests/mutate-ignore-breadth.baseline.json` when that file exists, or the file named by
 * `--baseline=<file>`, which must exist. A finding in the baseline is reported as
 * accounted for and does not fail the run; a finding that is not in it fails, and so does a
 * baseline entry that no longer occurs in a scanned file, or that names a file that no
 * longer exists, so the baseline can only shrink. Paths are compared relative to the root,
 * however they were spelled on the command line. `--write-baseline` records the current findings to the default path, or to
 * `--write-baseline=<file>`.
 *
 * USAGE
 *     php .claude/skills/pest-testing/scripts/check-mutate-ignore-breadth.php [--baseline=<file>] [path ...]
 *     php .claude/skills/pest-testing/scripts/check-mutate-ignore-breadth.php --write-baseline[=<file>] [path ...]
 *
 *     Run from the project root. A path is a directory searched for `*.php`, or a single
 *     file of any extension; paths are reported relative to the root as given. The default
 *     is those of `app`, `src`, `includes` and `tests` that exist. A search does not enter a
 *     `vendor/` or `node_modules/` directory below the path searched, at any depth, so a
 *     second Composer install such as `tests/Integration/vendor/` is not read as this
 *     project's code. A path named on the command line is read even when it is such a
 *     directory, or is inside one. MUTATE_IGNORE_ROOT
 *     overrides the root and MUTATE_IGNORE_VENDOR_DIR the vendor directory, default
 *     `<root>/vendor`, whose `autoload.php` and `pestphp/pest-plugin-mutate` are read.
 *
 * EXIT
 *     0  no annotation flagged outside the baseline, and the controls passed
 *     1  findings, or stale baseline entries
 *     2  could not run: the plugin or its autoloader missing, no mutator classes resolved,
 *        a control that failed, a file that cannot be read, a file holding the marker that
 *        does not parse, a path that is missing, no PHP file under the paths, an unreadable
 *        or malformed baseline
 *
 * The denominator (mutator classes resolved, then annotations and files measured) is
 * printed before any finding, so a run that measured nothing cannot read as a clean one.
 */

use Pest\Mutate\Contracts\Mutator;
use Pest\Mutate\Factories\NodeTraverserFactory;
use Pest\Mutate\Support\NodeVisitor;
use Pest\Mutate\Support\PhpParserFactory;
use PhpParser\Error;
use PhpParser\Parser;

const BREADTH_MARKER = '@pest-mutate-ignore';
const BREADTH_INERT = '@pest-mutate-inert';
const BREADTH_DEFAULT_BASELINE = 'tests/mutate-ignore-breadth.baseline.json';

/**
 * Directories the walk does not enter below a path it searches: another Composer or npm
 * install, such as a second setup's `tests/Integration/vendor/`, holds a dependency's
 * markers rather than this project's.
 */
const BREADTH_SKIPPED_DIRECTORIES = ['vendor', 'node_modules'];
const BREADTH_CONTROL_MUTATOR = 'GreaterToGreaterOrEqual';
const BREADTH_CONTROL_OTHER = 'PlusToMinus';

/**
 * Stop with exit code 2, which every caller reads as "could not run" rather than clean.
 *
 * @param  string  $message  What prevented the run.
 */
function breadthFail(string $message): never
{
    fwrite(STDERR, "check-mutate-ignore-breadth: {$message}\n");

    exit(2);
}

/**
 * The part of a string after the first occurrence of a search string, or the whole
 * string when it does not occur: `Pest\Support\Str::after()`, which the generator calls.
 *
 * @param  string  $subject  The string to search.
 * @param  string  $search  What to search for.
 * @return string The remainder.
 */
function breadthAfter(string $subject, string $search): string
{
    $at = strpos($subject, $search);

    return $at === false ? $subject : substr($subject, $at + strlen($search));
}

/**
 * Resolve every concrete mutator class the installed plugin ships, grouped by its name.
 *
 * A name can belong to more than one class: `BitwiseOrToBitwiseAnd` is both an `Arithmetic`
 * mutator for `|` and an `Assignment` mutator for `|=`. The plugin's `NodeVisitor` matches an
 * ignore entry against the name, so one annotation covers every class that carries it, and a
 * map holding one class per name would measure whichever class the directory walk returned
 * last.
 *
 * @param  string  $root  The plugin's `src/Mutators` directory.
 * @return array<string, list<class-string<Mutator>>> Mutator name to the classes carrying it.
 */
function breadthMutators(string $root): array
{
    $mutators = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

    foreach ($files as $file) {
        if (! $file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        // Build the class name from the path under the plugin's PSR-4 root
        $relative = substr($file->getPathname(), strlen($root) + 1, -4);
        $class = 'Pest\\Mutate\\Mutators\\'.str_replace(['/', '\\'], '\\', $relative);

        if (! class_exists($class)) {
            continue;
        }

        // Keep only classes a run could actually instantiate as a mutator
        $reflection = new ReflectionClass($class);

        if ($reflection->isAbstract() || ! $reflection->implementsInterface(Mutator::class)) {
            continue;
        }

        /** @var class-string<Mutator> $class */
        $mutators[$class::name()][] = $class;
    }

    ksort($mutators);

    return $mutators;
}

/**
 * Build the plugin's per-line ignore map exactly as `MutationGenerator::generate()` does,
 * including its `$mutatorsToIgnore`, which is never reset between marker lines.
 *
 * @param  string  $contents  The file's source.
 * @return array<int, array<int, string>> Line number to the mutator names ignored there.
 */
function breadthIgnoreMap(string $contents): array
{
    $map = [];

    foreach (explode(PHP_EOL, $contents) as $lineNumber => $line) {
        if (! str_contains($line, BREADTH_MARKER)) {
            continue;
        }

        if (breadthAfter($line, BREADTH_MARKER.':') !== $line) {
            $mutatorsToIgnore = array_map(trim(...), explode(',', breadthAfter($line, BREADTH_MARKER.':')));
        }

        $map[$lineNumber + 1] = $mutatorsToIgnore ?? ['all'];
    }

    return $map;
}

/**
 * Count the mutants one mutator produces in a source, with the generator's own loop.
 *
 * @param  Parser  $parser  The plugin's parser.
 * @param  string  $contents  The source to mutate.
 * @param  class-string<Mutator>  $mutator  The mutator to count.
 * @return int How many mutants the generator would create.
 */
function breadthCount(Parser $parser, string $contents, string $mutator): int
{
    $ignoreMap = breadthIgnoreMap($contents);
    $offset = 0;
    $count = 0;

    while (true) {
        $mutated = false;

        // Traverse once per mutant, resuming after the last mutated node, as the generator does
        $traverser = NodeTraverserFactory::create();
        $traverser->addVisitor(new NodeVisitor(
            mutator: $mutator,
            offset: $offset,
            linesToMutate: [],
            mutatorsToIgnoreByLine: $ignoreMap,
            hasAlreadyMutated: function () use (&$mutated): bool {
                return $mutated;
            },
            trackMutation: function (int $nodeCount) use (&$mutated, &$offset): void {
                $mutated = true;
                $offset = $nodeCount;
            },
        ));

        $statements = $parser->parse($contents);

        if ($statements === null) {
            return $count;
        }

        $traverser->traverse($statements);

        if (! $mutated) {
            return $count;
        }

        $count++;
    }
}

/**
 * Count the mutants every class carrying one mutator name produces in a source.
 *
 * @param  Parser  $parser  The plugin's parser.
 * @param  string  $contents  The source to mutate.
 * @param  list<class-string<Mutator>>  $classes  The classes that share the name.
 * @return int The sum of how many mutants each class would create.
 */
function breadthCountNamed(Parser $parser, string $contents, array $classes): int
{
    return array_sum(array_map(static fn (string $class): int => breadthCount($parser, $contents, $class), $classes));
}

/**
 * Spell a path the one way the baseline keys it: forward slashes, relative to the root
 * when it lies under it, with no `./` segment and no repeated slash.
 *
 * @param  string  $path  The path as given or found.
 * @param  string  $root  The project root.
 * @return string The normalized path.
 */
function breadthNormalizePath(string $path, string $root): string
{
    // Resolve both through the filesystem first, so a symlinked spelling of the root (on
    // macOS, `/tmp` against `/private/tmp`) is recognized as the root
    $realPath = realpath($path);
    $realRoot = realpath($root);

    if ($realPath !== false && $realRoot !== false) {
        [$path, $root] = [$realPath, $realRoot];
    }

    $path = (string) preg_replace('#/+#', '/', str_replace('\\', '/', $path));
    $root = rtrim((string) preg_replace('#/+#', '/', str_replace('\\', '/', $root)), '/');

    if ($root !== '' && str_starts_with($path, $root.'/')) {
        $path = substr($path, strlen($root) + 1);
    }

    $path = (string) preg_replace('#(^|/)\./#', '$1', $path);

    while (str_starts_with($path, './')) {
        $path = substr($path, 2);
    }

    return $path;
}

/**
 * Split a source into its lines and the separators between them, so a line can be changed
 * and the source rebuilt byte for byte. Only `\r\n`, `\r` and `\n` end a line, as they do
 * for the tokenizer and php-parser: a bare `\R` would also split inside a UTF-8 character
 * whose bytes include `0x85`.
 *
 * @param  string  $contents  The file's source.
 * @return array{lines: list<string>, separators: list<string>} The lines, and the separator after each.
 */
function breadthSplit(string $contents): array
{
    $parts = preg_split('/(\r\n|\r|\n)/', $contents, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$contents];
    $lines = [];
    $separators = [];

    foreach ($parts as $index => $part) {
        if ($index % 2 === 0) {
            $lines[] = $part;
            $separators[] = '';
        } else {
            $separators[count($separators) - 1] = $part;
        }
    }

    return ['lines' => $lines, 'separators' => $separators];
}

/**
 * Find the line of every marker that opens a comment's content: an annotation.
 *
 * The marker must open the line's comment content, as `check-mutate-ignore.php` requires.
 * Any other line holding the marker text is a mention, which `breadthMeasure()` measures
 * separately, because the plugin's line map honors the text wherever it appears on a line.
 *
 * @param  string  $contents  The file's source.
 * @return list<int> The line numbers, ascending.
 */
function breadthAnnotationLines(string $contents): array
{
    $lines = [];

    foreach (token_get_all($contents) as $token) {
        if (! is_array($token) || ($token[0] !== T_COMMENT && $token[0] !== T_DOC_COMMENT)) {
            continue;
        }

        // A comment token can span several lines, and each marker line counts on its own
        foreach (preg_split('/\r\n|\r|\n/', $token[1]) ?: [] as $offset => $text) {
            $content = preg_replace('{^\s*(/\*\*?|\*|//|#)?\s*}', '', $text, 1) ?? $text;

            if (str_starts_with($content, BREADTH_MARKER)) {
                $lines[] = $token[2] + $offset;
            }
        }
    }

    $lines = array_values(array_unique($lines));
    sort($lines);

    return $lines;
}

/**
 * Read which mutator names a marker line carries, the way `NodeVisitor` reads it.
 *
 * @param  string  $line  The marker's line.
 * @return list<string>|null The names, or null for a bare marker.
 */
function breadthNamedMutators(string $line): ?array
{
    preg_match('/'.preg_quote(BREADTH_MARKER, '/').'(.*)/', $line, $matches);

    $tail = trim($matches[1] ?? '', " \t\n\r*/:");

    if ($tail === '') {
        return null;
    }

    return array_values(array_filter(
        array_map(static fn (string $name): string => trim($name, ' */:'), explode(',', $tail)),
        static fn (string $name): bool => $name !== '',
    ));
}

/**
 * Measure every line holding the marker and return the ones that are flagged.
 *
 * An annotation (a marker opening a comment's content) is flagged when it removes no
 * mutant, or more than one of a mutator it names. Any other line holding the marker text,
 * such as a trailing comment that mentions it or a string, is a mention: the plugin's line
 * map still honors it, so it is measured across every mutator and flagged when it removes
 * any mutant at all. A mention that removes nothing is documentation and is not reported.
 *
 * @param  Parser  $parser  The plugin's parser.
 * @param  string  $contents  The source.
 * @param  string  $path  The path the findings are reported under.
 * @param  array<string, list<class-string<Mutator>>>  $mutators  The resolved mutators.
 * @return array{annotations: int, findings: list<array{where: string, why: string, detail: string, path: string, marker: string}>}
 */
function breadthMeasure(Parser $parser, string $contents, string $path, array $mutators): array
{
    ['lines' => $sourceLines, 'separators' => $separators] = breadthSplit($contents);
    $annotationLines = array_flip(breadthAnnotationLines($contents));
    $unannotated = [];
    $annotations = 0;
    $findings = [];

    foreach ($sourceLines as $index => $line) {
        if (! str_contains($line, BREADTH_MARKER)) {
            continue;
        }

        $lineNumber = $index + 1;
        $isAnnotation = isset($annotationLines[$lineNumber]);
        $named = $isAnnotation ? breadthNamedMutators($line) : null;
        $known = $named === null ? array_keys($mutators) : array_values(array_filter($named, static fn (string $name): bool => isset($mutators[$name])));

        // Make this one line's marker inert, keeping the comment and every line where it was
        $without = $sourceLines;
        $without[$index] = str_replace(BREADTH_MARKER, BREADTH_INERT, $line);
        $withoutContents = implode('', array_map(static fn (string $text, string $separator): string => $text.$separator, $without, $separators));

        $removed = [];

        foreach ($known as $name) {
            $unannotated[$name] ??= breadthCountNamed($parser, $contents, $mutators[$name]);
            $removed[$name] = breadthCountNamed($parser, $withoutContents, $mutators[$name]) - $unannotated[$name];
        }

        $where = "{$path}:{$lineNumber}";
        $marker = trim($line);
        $total = array_sum($removed);

        if (! $isAnnotation) {
            if ($total > 0) {
                $findings[] = ['where' => $where, 'why' => 'a MENTION of the marker outside an annotation removes mutants', 'detail' => "removes {$total} across all mutators", 'path' => $path, 'marker' => $marker];
            }

            continue;
        }

        $annotations++;
        $detail = $named === null
            ? "bare marker, removes {$total} across all mutators"
            : implode(', ', array_map(static fn (string $name, int $count): string => "{$name}={$count}", array_keys($removed), $removed));

        if ($total === 0) {
            $findings[] = ['where' => $where, 'why' => 'removes NO mutant', 'detail' => $detail === '' ? 'names no known mutator' : $detail, 'path' => $path, 'marker' => $marker];

            continue;
        }

        if ($named !== null && array_filter($removed, static fn (int $count): bool => $count > 1) !== []) {
            $findings[] = ['where' => $where, 'why' => 'removes MORE than one mutant of a named mutator', 'detail' => $detail, 'path' => $path, 'marker' => $marker];
        }
    }

    return ['annotations' => $annotations, 'findings' => $findings];
}

/**
 * Measure planted sources whose answers are known, before any project file is read.
 *
 * @param  Parser  $parser  The plugin's parser.
 * @param  array<string, list<class-string<Mutator>>>  $mutators  The resolved mutators.
 * @return string|null Why the controls failed, or null when every one gave its answer.
 */
function breadthControlsFail(Parser $parser, array $mutators): ?string
{
    $name = BREADTH_CONTROL_MUTATOR;

    foreach ([$name, BREADTH_CONTROL_OTHER] as $required) {
        if (! isset($mutators[$required])) {
            return "the control mutator {$required} did not resolve from the installed plugin";
        }
    }

    $subset = [$name => $mutators[$name], BREADTH_CONTROL_OTHER => $mutators[BREADTH_CONTROL_OTHER]];
    $marker = '// '.BREADTH_MARKER.": {$name}";

    // Each case: the source's lines, the annotations it holds, and the finding expected.
    // The lines are joined with PHP_EOL so the plugin's line map is live on any runtime:
    // the trailing, inherited and mention cases are decided by that map alone.
    $cases = [
        'one mutant through comment attachment' => [['<?php', 'if ($a > $b) {', "    {$marker}", '    $c = $a > $b;', '}'], 1, null],
        'two mutants through comment attachment' => [['<?php', $marker, '$c = $a > $b && $d > $e;'], 1, 'removes MORE than one mutant of a named mutator'],
        'no mutant through comment attachment' => [['<?php', $marker, '$c = $a + $b;'], 1, 'removes NO mutant'],
        'two mutants through the line map' => [['<?php', "\$c = \$a > \$b && \$d > \$e; {$marker}"], 1, 'removes MORE than one mutant of a named mutator'],
        'no mutant through an inherited name' => [['<?php', $marker, '$x = $a > $b;', '$z = $a + $b; // '.BREADTH_MARKER], 2, 'removes NO mutant'],
        'one mutant through a mention' => [['<?php', '$c = $a > $b; // see '.BREADTH_MARKER], 0, 'a MENTION of the marker outside an annotation removes mutants'],
    ];

    foreach ($cases as $label => [$lines, $annotations, $expected]) {
        $result = breadthMeasure($parser, implode(PHP_EOL, $lines).PHP_EOL, 'control', $subset);
        $found = $result['findings'][0]['why'] ?? null;

        if ($result['annotations'] !== $annotations || count($result['findings']) > 1 || $found !== $expected) {
            return "the planted case of {$label} was read as ".($found ?? 'clean');
        }
    }

    return null;
}

// Split the options from the paths
$baselinePath = null;
$writeBaselinePath = null;
$roots = [];

foreach (array_slice($argv, 1) as $argument) {
    if (str_starts_with($argument, '--baseline=')) {
        $baselinePath = substr($argument, strlen('--baseline='));
    } elseif ($argument === '--write-baseline') {
        $writeBaselinePath = BREADTH_DEFAULT_BASELINE;
    } elseif (str_starts_with($argument, '--write-baseline=')) {
        $writeBaselinePath = substr($argument, strlen('--write-baseline='));
    } elseif (str_starts_with($argument, '--')) {
        breadthFail("unknown option: {$argument}");
    } else {
        $roots[] = $argument;
    }
}

$root = getenv('MUTATE_IGNORE_ROOT') ?: (string) getcwd();
$vendor = getenv('MUTATE_IGNORE_VENDOR_DIR') ?: $root.'/vendor';
$mutatorDir = $vendor.'/pestphp/pest-plugin-mutate/src/Mutators';

if (! is_file($vendor.'/autoload.php') || ! is_dir($mutatorDir)) {
    breadthFail("pest-plugin-mutate is not installed under {$vendor}");
}

require $vendor.'/autoload.php';

if (! chdir($root)) {
    breadthFail("cannot enter {$root}");
}

$mutators = breadthMutators($mutatorDir);

if ($mutators === []) {
    breadthFail("no mutator classes resolved from {$mutatorDir}");
}

echo 'check-mutate-ignore-breadth: '.array_sum(array_map(count(...), $mutators))." mutator classes resolved from the installed plugin\n";

$parser = PhpParserFactory::make();
$controlFailure = breadthControlsFail($parser, $mutators);

if ($controlFailure !== null) {
    breadthFail("{$controlFailure}; refusing to read project files");
}

if ($roots === []) {
    foreach (['app', 'src', 'includes', 'tests'] as $default) {
        if (is_dir($default)) {
            $roots[] = $default;
        }
    }

    if ($roots === []) {
        breadthFail("no path given and none of app, src, includes or tests exists under {$root}");
    }
}

// Collect the files: a directory contributes its PHP files, a named file is read as given
$files = [];

foreach ($roots as $path) {
    if (is_file($path)) {
        $files[] = breadthNormalizePath($path, $root);

        continue;
    }

    if (! is_dir($path)) {
        breadthFail("not a file or directory: {$path}");
    }

    // Below the searched path, skip installed dependency trees at any depth
    $walk = new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        static fn (SplFileInfo $entry): bool => ! ($entry->isDir() && in_array($entry->getFilename(), BREADTH_SKIPPED_DIRECTORIES, true)),
    );

    foreach (new RecursiveIteratorIterator($walk) as $file) {
        if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
            $files[] = breadthNormalizePath($file->getPathname(), $root);
        }
    }
}

if ($files === []) {
    breadthFail('no PHP file under the given paths; an empty scan is not a clean one');
}

$files = array_values(array_unique($files));
sort($files);

$annotations = 0;
$findings = [];

foreach ($files as $path) {
    $contents = file_get_contents($path);

    if ($contents === false) {
        breadthFail("cannot read {$path}");
    }

    // Cheap skip for the common case before any tokenizing or parsing
    if (! str_contains($contents, BREADTH_MARKER)) {
        continue;
    }

    try {
        $parser->parse($contents);
    } catch (Error $error) {
        breadthFail("cannot parse {$path}: {$error->getMessage()}");
    }

    $result = breadthMeasure($parser, $contents, $path, $mutators);
    $annotations += $result['annotations'];
    array_push($findings, ...$result['findings']);
}

echo "check-mutate-ignore-breadth: {$annotations} annotation(s) measured in ".count($files)." file(s)\n";

// Record the current findings as the baseline when asked to, and stop there
if ($writeBaselinePath !== null) {
    $entries = array_map(static fn (array $finding): array => [
        'path' => $finding['path'],
        'marker' => $finding['marker'],
        'finding' => $finding['why'],
        'detail' => $finding['detail'],
    ], $findings);

    $json = json_encode(['findings' => $entries], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

    $directory = dirname($writeBaselinePath);

    if ((! is_dir($directory) && ! mkdir($directory, 0777, true)) || file_put_contents($writeBaselinePath, $json."\n") === false) {
        breadthFail("cannot write {$writeBaselinePath}");
    }

    echo 'check-mutate-ignore-breadth: '.count($entries)." finding(s) written to {$writeBaselinePath}\n";

    exit(0);
}

// The default baseline is read when it exists; a named one must exist
if ($baselinePath === null && is_file(BREADTH_DEFAULT_BASELINE)) {
    $baselinePath = BREADTH_DEFAULT_BASELINE;
}

// Load the baseline as counted keys, since one file can repeat a marker line
$inBaseline = [];

if ($baselinePath !== null) {
    $raw = is_file($baselinePath) ? file_get_contents($baselinePath) : false;
    $decoded = $raw === false ? null : json_decode($raw, true);

    if (! is_array($decoded) || ! is_array($decoded['findings'] ?? null)) {
        breadthFail("cannot read baseline {$baselinePath}");
    }

    foreach ($decoded['findings'] as $entry) {
        if (! is_array($entry) || ! is_string($entry['path'] ?? null) || ! is_string($entry['marker'] ?? null) || ! is_string($entry['detail'] ?? null)) {
            breadthFail("malformed entry in baseline {$baselinePath}");
        }

        $key = "{$entry['path']}\t{$entry['marker']}\t{$entry['detail']}";
        $inBaseline[$key] = ($inBaseline[$key] ?? 0) + 1;
    }
}

// Set aside every finding the baseline accounts for
$new = [];
$matched = 0;

foreach ($findings as $finding) {
    $key = "{$finding['path']}\t{$finding['marker']}\t{$finding['detail']}";

    if (($inBaseline[$key] ?? 0) > 0) {
        $inBaseline[$key]--;
        $matched++;

        continue;
    }

    $new[] = $finding;
}

// A leftover entry for a file this run scanned, or for a file that no longer exists, no
// longer occurs, so the baseline is stale
$scanned = array_flip($files);
$stale = [];

foreach ($inBaseline as $key => $remaining) {
    [$path, $marker, $detail] = explode("\t", $key, 3);

    if ($remaining > 0 && (isset($scanned[$path]) || ! is_file($path))) {
        $stale[] = "{$path}\n  {$marker}\n  {$detail}";
    }
}

if ($baselinePath !== null) {
    echo "check-mutate-ignore-breadth: {$matched} finding(s) accounted for by {$baselinePath}\n";
}

if ($new === [] && $stale === []) {
    echo match (true) {
        $matched > 0 => "check-mutate-ignore-breadth: no annotation flagged outside the baseline\n",
        $annotations === 0 => "check-mutate-ignore-breadth: no annotation to measure, and no mention of the marker that removes a mutant\n",
        default => "check-mutate-ignore-breadth: every annotation removes exactly one mutant per named mutator\n",
    };

    exit(0);
}

foreach ($new as $finding) {
    echo "{$finding['where']}\n  {$finding['why']}\n  {$finding['detail']}\n\n";
}

foreach ($stale as $entry) {
    echo "{$entry}\n  baseline entry no longer occurs: remove it from the baseline\n\n";
}

echo 'check-mutate-ignore-breadth: '.count($new).' annotation(s) flagged, '.count($stale)." stale baseline entry(ies)\n";

exit(1);
