<?php

declare(strict_types=1);

/**
 * Report `@pest-mutate-ignore` annotations that do not do what they say.
 *
 * The plugin reads the marker in `Pest\Mutate\Support\NodeVisitor::enterNode()` with
 * `preg_match('/@pest-mutate-ignore(.*)/', ...)`. `.` does not cross a newline, so the
 * captured tail is the rest of the marker's own line. An empty tail stops the traversal
 * of the node and everything beneath it, which suppresses every mutator. Any other tail
 * is split on `,`, each part is trimmed of spaces, asterisks, slashes and colons, and each is compared with `===`
 * against a mutator NAME. Prose is never a place the parser looks, and the failure is
 * silent: the mutant keeps surviving, which reads as a wrong reason rather than as an
 * annotation that never applied.
 *
 * PARSING MODEL, and the revision it was measured against
 *     pestphp/pest-plugin-mutate v5.0.2, reference d0c87110416b699413cc073b8dcc92e440d81931
 *     src/Support/NodeVisitor.php        sha256 26157ab3378cc9d1673019cd9a916dbdafd601a2d987c6fdd68792784dface2f
 *     src/Support/MutationGenerator.php  sha256 591052dcd1deb47d235a2cebfc4ee5371d95dd52479ae619998cbc3c2ad9a1df
 * The model was read from that source and confirmed on a four-mutant probe (the table in
 * the `adversarial-review` rule). A run first prints the installed version and the
 * NodeVisitor hash beside these, and says so when they differ: a different revision is
 * not covered by this model, and a clean exit then proves less. The check does not fail
 * on a difference, because the parser may be unchanged.
 *
 * Flagged, each with file and line:
 *   bare       The marker ends its line. The tail is empty, so the node and its whole
 *              subtree are skipped for every mutator. A blanket suppression raises the
 *              apparent score and leaves no survivor to explain. A marker followed only
 *              by whitespace is the same shape, and on v5.0.2 it suppresses NOTHING (the
 *              tail is a space, which is not empty and names no mutator), so a
 *              formatter that strips trailing whitespace flips its meaning.
 *   inert      A tail that names no known mutator: prose, or a separator with nothing
 *              after it (`:`, `,`, `: ,`). It suppresses nothing.
 *   prose      A comma list in which some element is not a mutator name. The known names
 *              still apply; the rest is dead text. `: Name because x` is the inert form,
 *              while `: Name, because x` happens to work, so the defect is
 *              punctuation-sensitive.
 *   trailing   The marker does not begin its line, on a runtime where the plugin's line
 *              map is dead. php-parser attaches a comment to the node that FOLLOWS it, so
 *              a trailing annotation protects the next statement and leaves its own.
 *              Reported only where `explode(PHP_EOL, ...)` cannot split these files, which
 *              is a CRLF runtime meeting LF files. `lineMapIsLive()` reads the installed
 *              plugin to decide, so an upstream fix retires the finding.
 *
 * BOUND. This checks the ANNOTATION, never the node beneath it. It cannot see a mutator
 * that targets the annotated node itself where the line map is dead, and it does not map
 * php-parser node types to each mutator's `nodesToHandle()`. It is sound on what it flags
 * and not complete on what it passes: a clean exit means no annotation is inert for the
 * reasons above, never that every annotation is effective. Deciding which mutants may be
 * annotated is the `adversarial-review` rule's, and out of scope here.
 *
 * Only comment tokens are inspected (`token_get_all`), so a heredoc or string that
 * contains the marker is not a finding. A comment is an annotation only when the marker
 * opens its content; a sentence that merely mentions the marker is prose about it.
 *
 * USAGE
 *     php check-mutate-ignore.php [path ...]
 *
 *     Paths default to those of `app`, `src` and `tests` that exist under the project
 *     root. The root is the working directory; MUTATE_IGNORE_ROOT overrides it.
 *     MUTATE_IGNORE_PLUGIN_DIR points at the plugin, default
 *     `<root>/vendor/pestphp/pest-plugin-mutate`. MUTATE_IGNORE_EOL (`\n` or `\r\n`)
 *     overrides the runtime separator so each branch of the line-map test can be
 *     exercised on either platform.
 *
 * EXIT
 *     0  no flagged annotation, and the controls below passed
 *     1  at least one flagged annotation
 *     2  cannot run: plugin missing, no mutator names found, a path that is not a
 *        directory, an unreadable file, or a control that failed
 *
 * The denominator (files scanned, annotations seen, mutator names known) is printed
 * BEFORE any finding, so a run that scanned nothing cannot read as a clean one. Two
 * controls run on every invocation: a planted bare marker, a planted inert tail and a
 * planted valid annotation must be classified as bare, inert and clean. A classifier that
 * cannot tell them apart exits 2 before it reads a single project file.
 */
const MEASURED_VERSION = 'v5.0.2';
const MEASURED_NODE_VISITOR_SHA256 = '26157ab3378cc9d1673019cd9a916dbdafd601a2d987c6fdd68792784dface2f';
const MARKER = '@pest-mutate-ignore';

/**
 * Read the comma list a marker names, as the plugin does.
 *
 * @return list<string>
 */
function ignoredNames(string $tail): array
{
    return array_map(static fn (string $name): string => trim($name, ' */:'), explode(',', $tail));
}

/**
 * Classify one comment's text against the known mutator names.
 *
 * @param  array<string, true>  $known
 * @return array{kind: string, detail: string}|null Null when the annotation is sound or the
 *                                                  comment is not an annotation at all.
 */
function classifyAnnotation(string $text, array $known): ?array
{
    if (preg_match('/'.preg_quote(MARKER, '/').'(.*)/', $text, $matches) !== 1) {
        return null;
    }

    $tail = $matches[1];

    if ($tail === '') {
        return ['kind' => 'bare', 'detail' => 'bare marker: skips the node and its whole subtree for every mutator'];
    }

    if (trim($tail) === '') {
        return ['kind' => 'bare', 'detail' => 'bare marker with trailing whitespace: suppresses NOTHING on v5.0.2, and everything once the space is stripped'];
    }

    $names = ignoredNames($tail);
    $matched = array_values(array_filter($names, static fn (string $name): bool => $name !== '' && isset($known[$name])));
    $unknown = array_values(array_filter($names, static fn (string $name): bool => ! ($name === '' || isset($known[$name]))));

    if ($matched === []) {
        $shown = $unknown === [] ? 'no name' : 'unmatched: '.implode(' | ', $unknown);

        return ['kind' => 'inert', 'detail' => 'tail names no known mutator, so it suppresses nothing ('.$shown.')'];
    }

    if ($unknown !== []) {
        return ['kind' => 'prose', 'detail' => 'known: '.implode(', ', $matched).'; not a mutator name: '.implode(' | ', $unknown)];
    }

    return null;
}

/**
 * Whether the plugin's per-line ignore map can reach files with this separator.
 *
 * `MutationGenerator` builds it with `explode(PHP_EOL, $contents)`. On a runtime whose
 * separator is CRLF, LF files collapse to one element and only php-parser's comment
 * attachment remains, which is when a trailing annotation is inert.
 */
function lineMapIsLive(string $pluginDir): bool
{
    $generator = $pluginDir.'/src/Support/MutationGenerator.php';

    if (! is_file($generator)) {
        return false;
    }

    $source = file_get_contents($generator);

    if (! is_string($source)) {
        return false;
    }

    $eol = getenv('MUTATE_IGNORE_EOL');
    $eol = $eol === false || $eol === '' ? PHP_EOL : str_replace(['\\r', '\\n'], ["\r", "\n"], $eol);

    if (str_contains($source, 'explode(PHP_EOL,')) {
        return $eol === "\n";
    }

    return true;
}

/**
 * The installed plugin version, read from Composer's record.
 */
function installedVersion(string $root): string
{
    $installed = $root.'/vendor/composer/installed.json';
    $json = is_file($installed) ? file_get_contents($installed) : false;
    $data = is_string($json) ? json_decode($json, true) : null;
    $packages = is_array($data) ? ($data['packages'] ?? $data) : [];

    foreach (is_array($packages) ? $packages : [] as $package) {
        if (is_array($package) && ($package['name'] ?? '') === 'pestphp/pest-plugin-mutate') {
            return (string) ($package['version'] ?? 'unknown');
        }
    }

    return 'unknown';
}

/**
 * Collect the mutator class names the plugin ships.
 *
 * @return array<string, true>
 */
function knownMutators(string $mutatorDir): array
{
    $known = [];
    $skip = ['Abstract', 'Concerns', 'Sets'];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($mutatorDir, FilesystemIterator::SKIP_DOTS));

    foreach ($files as $file) {
        if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $relative = substr($file->getPathname(), strlen($mutatorDir) + 1);

        if (in_array(explode(DIRECTORY_SEPARATOR, $relative)[0], $skip, true)) {
            continue;
        }

        $known[$file->getBasename('.php')] = true;
    }

    return $known;
}

/**
 * Run the classifier against planted inputs before it reads any project file.
 *
 * @param  array<string, true>  $known
 */
function controlsPass(array $known): bool
{
    $name = array_key_first($known);

    if ($name === null) {
        return false;
    }

    $cases = [
        ['// '.MARKER, 'bare'],
        ['// '.MARKER.' ', 'bare'],
        ['// '.MARKER.': '.$name.' because the guard is required', 'inert'],
        ['// '.MARKER.': ,', 'inert'],
        ['// '.MARKER.': '.$name.', because the guard is required', 'prose'],
        ['// '.MARKER.': '.$name, null],
        ['// an ordinary comment', null],
    ];

    foreach ($cases as [$text, $expected]) {
        $found = classifyAnnotation($text, $known);

        if (($found['kind'] ?? null) !== $expected) {
            return false;
        }
    }

    return true;
}

/**
 * Scan one source string and return its findings and annotation count.
 *
 * @param  array<string, true>  $known
 * @return array{findings: list<array{0: string, 1: string, 2: string}>, annotations: int}
 */
function scanSource(string $source, string $label, array $known, bool $lineMapLive): array
{
    $findings = [];
    $annotations = 0;
    $lines = preg_split('/\R/', $source) ?: [];

    foreach (token_get_all($source) as $token) {
        if (! is_array($token) || ($token[0] !== T_COMMENT && $token[0] !== T_DOC_COMMENT)) {
            continue;
        }

        [, $text, $line] = $token;
        $at = strpos($text, MARKER);

        if ($at === false) {
            continue;
        }

        $body = preg_replace('{^(/\*\*?|//|#)}', '', $text, 1) ?? $text;
        $lead = substr($body, 0, (int) strpos($body, MARKER));

        if (trim($lead, " \t\r\n*") !== '') {
            continue;
        }

        $annotations++;
        $where = $label.':'.$line;
        $first = trim(explode("\n", $text)[0]);

        $thisLine = $lines[$line - 1] ?? '';
        $markerAt = strpos($thisLine, MARKER);
        $before = $markerAt === false ? '' : substr($thisLine, 0, $markerAt);

        if (trim($before, " \t/*#") !== '' && ! $lineMapLive) {
            $findings[] = [$where, 'trailing: does not begin its line, so it attaches to the NEXT node', $first];

            continue;
        }

        $found = classifyAnnotation($text, $known);

        if ($found !== null) {
            $findings[] = [$where, $found['kind'].': '.$found['detail'], $first];
        }
    }

    return ['findings' => $findings, 'annotations' => $annotations];
}

/**
 * @param  list<string>  $argv
 */
function checkMutateIgnore(array $argv): int
{
    $root = getenv('MUTATE_IGNORE_ROOT') ?: (string) getcwd();
    $pluginDir = getenv('MUTATE_IGNORE_PLUGIN_DIR') ?: $root.'/vendor/pestphp/pest-plugin-mutate';
    $mutatorDir = $pluginDir.'/src/Mutators';

    if (! is_dir($mutatorDir)) {
        fwrite(STDERR, "check-mutate-ignore: pest-plugin-mutate is not installed at {$pluginDir}\n");

        return 2;
    }

    $known = knownMutators($mutatorDir);

    if ($known === []) {
        fwrite(STDERR, "check-mutate-ignore: no mutator names found under {$mutatorDir}\n");

        return 2;
    }

    if (! controlsPass($known)) {
        fwrite(STDERR, "check-mutate-ignore: the classifier failed its planted controls; refusing to read project files\n");

        return 2;
    }

    $roots = array_slice($argv, 1);

    if ($roots === []) {
        foreach (['app', 'src', 'tests'] as $default) {
            if (is_dir($root.'/'.$default)) {
                $roots[] = $root.'/'.$default;
            }
        }
    }

    if ($roots === []) {
        fwrite(STDERR, "check-mutate-ignore: no path given and none of app, src or tests exists under {$root}\n");

        return 2;
    }

    $visitor = $pluginDir.'/src/Support/NodeVisitor.php';
    $hash = is_file($visitor) ? (string) hash_file('sha256', $visitor) : 'missing';
    $version = installedVersion($root);
    $lineMapLive = lineMapIsLive($pluginDir);

    echo 'check-mutate-ignore: parsing model measured against pest-plugin-mutate '.MEASURED_VERSION.' (NodeVisitor sha256 '.MEASURED_NODE_VISITOR_SHA256.")\n";
    echo "check-mutate-ignore: installed {$version} (NodeVisitor sha256 {$hash})\n";

    if ($version !== MEASURED_VERSION || $hash !== MEASURED_NODE_VISITOR_SHA256) {
        echo "check-mutate-ignore: NOTE the installed plugin differs from the measured revision; a clean result is weaker evidence\n";
    }

    $findings = [];
    $files = 0;
    $annotations = 0;

    foreach ($roots as $path) {
        if (! is_dir($path)) {
            fwrite(STDERR, "check-mutate-ignore: not a directory: {$path}\n");

            return 2;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $source = file_get_contents($file->getPathname());

            if ($source === false) {
                fwrite(STDERR, "check-mutate-ignore: cannot read {$file->getPathname()}\n");

                return 2;
            }

            $files++;
            $result = scanSource($source, $file->getPathname(), $known, $lineMapLive);
            $annotations += $result['annotations'];
            array_push($findings, ...$result['findings']);
        }
    }

    echo 'check-mutate-ignore: scanned '.$files.' file(s), '.$annotations.' annotation(s), '.count($known)." known mutator name(s)\n";

    if ($files === 0) {
        fwrite(STDERR, "check-mutate-ignore: no PHP file under the given paths; an empty scan is not a clean one\n");

        return 2;
    }

    if ($findings === []) {
        echo "check-mutate-ignore: no flagged annotation (see the docblock for what this cannot see)\n";

        return 0;
    }

    foreach ($findings as [$where, $why, $line]) {
        echo "{$where}\n  {$why}\n  {$line}\n\n";
    }

    echo 'check-mutate-ignore: '.count($findings)." flagged annotation(s)\n";

    return 1;
}

// Run only when invoked as a script, so a test can include the file and call a function.
if (isset($argv[0]) && basename($argv[0]) === basename(__FILE__)) {
    exit(checkMutateIgnore($argv));
}
