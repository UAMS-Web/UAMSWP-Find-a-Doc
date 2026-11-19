<?php

declare(strict_types=1);

// cspell:ignore NULLSAFE PAAMAYIM NEKUDOTAYIM VARARG LNUMBER DNUMBER

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
 *   unattached A standalone marker whose next code, on a later line, starts no node: an
 *              infix operator (`&&`, `||`, `and`, `or`, `xor`, `??`, `.`, `?`, `:`, the
 *              arithmetic, bitwise and comparison operators, `instanceof`), an object or
 *              scope operator (`->`, `?->`, `::`), `=>`, `,`, `)` or `]`. php-parser
 *              attaches the comment to no node and no node starts on the marker's line,
 *              so neither mechanism reaches anything (UAMS-Web/uams-claude-skills#846).
 *              The usual shape is a long condition with the operator leading each
 *              continuation line, as Pint leaves it: wrap the operand in parentheses and
 *              put the marker directly above it. `+`, `-`, `&` and `?` are flagged only
 *              after an operand, because as a prefix (unary minus, a reference, a
 *              nullable type) they start a node and the marker attaches to it. After a
 *              `}` they are not judged (see `OPERAND_ENDS`), so a marker above `- 1`
 *              after a `match` or `new class` expression passes unflagged.
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
 *     A path is a directory searched for `*.php`, or a single file of any extension, so a
 *     project whose annotated code sits at its root (a theme's `functions.php`) can name
 *     those files. A search does not enter a `vendor/` or `node_modules/` directory below
 *     the path searched, at any depth, so `.` reads the project and not its dependencies;
 *     a path named on the command line is read even when it is such a directory, or is
 *     inside one. A file reached twice (named and also under a named directory) is read
 *     once. Paths default to those of `app`, `src` and `tests` that exist under the
 *     project root. The root is the working directory; MUTATE_IGNORE_ROOT overrides it.
 *     MUTATE_IGNORE_PLUGIN_DIR points at the plugin, default
 *     `<root>/vendor/pestphp/pest-plugin-mutate`. MUTATE_IGNORE_EOL (`\n` or `\r\n`)
 *     overrides the runtime separator so each branch of the line-map test can be
 *     exercised on either platform.
 *
 * EXIT
 *     0  no flagged annotation, and the controls below passed
 *     1  at least one flagged annotation
 *     2  cannot run: plugin missing, no mutator names found, a path that is neither a
 *        file nor a directory, an unreadable file, or a control that failed
 *
 * The denominator (files scanned, annotations seen, mutator names known) is printed
 * BEFORE any finding, so a run that scanned nothing cannot read as a clean one. Two
 * controls run on every invocation: a planted bare marker, a planted inert tail and a
 * planted valid annotation must be classified as bare, inert and clean, and planted
 * markers above a leading `&&`, `||` and infix `-` (and a block marker above `&&`) must
 * be flagged while the same marker above the parenthesized operand, above a unary
 * minus, or after a braceless `if` is not. A classifier that cannot tell
 * them apart exits 2 before it reads a single project file.
 */
const MEASURED_VERSION = 'v5.0.2';
const MEASURED_NODE_VISITOR_SHA256 = '26157ab3378cc9d1673019cd9a916dbdafd601a2d987c6fdd68792784dface2f';
const MARKER = '@pest-mutate-ignore';

/**
 * Directories a search does not enter below a path it walks: an installed dependency tree
 * holds a dependency's markers rather than this project's. The same list as the breadth
 * check's `BREADTH_SKIPPED_DIRECTORIES`.
 */
const SKIPPED_DIRECTORIES = ['vendor', 'node_modules'];

/**
 * The files a run reads: a named file as given, and a directory's PHP files with any
 * nested `vendor/` or `node_modules/` left out. Each file appears once. No declared
 * return type: the scripts in this directory parse on PHP 7.4, which has no union types.
 *
 * @param  list<string>  $paths
 * @return list<string>|string The file paths, or the path that is neither a file nor a directory.
 */
function filesToScan(array $paths)
{
    $files = [];

    foreach ($paths as $path) {
        if (is_file($path)) {
            $files[realpath($path) ?: $path] = $path;

            continue;
        }

        if (! is_dir($path)) {
            return $path;
        }

        // Below the searched path, skip installed dependency trees at any depth
        $walk = new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            static fn (SplFileInfo $entry): bool => ! ($entry->isDir() && in_array($entry->getFilename(), SKIPPED_DIRECTORIES, true)),
        );

        foreach (new RecursiveIteratorIterator($walk) as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                $files[$file->getRealPath() ?: $file->getPathname()] ??= $file->getPathname();
            }
        }
    }

    return array_values($files);
}

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

    // The attachment check: a marker above a leading `&&` or `||`, a block marker on its own
    // line above `&&`, and a marker above an infix `-` are unattached; the same marker above
    // the parenthesized operand, above a unary minus, or after a braceless `if` attaches
    $marker = '    // '.MARKER.': '.$name."\n";
    $block = '    /* '.MARKER.': '.$name." */\n";
    $layouts = [
        ["<?php\nif (\n    \$a\n".$marker."    && \$b !== ''\n) {}\n", 1],
        ["<?php\n\$x = \$a\n".$marker."    || \$b;\n", 1],
        ["<?php\n\$x = \$a\n".$block."    && \$b;\n", 1],
        ["<?php\n\$x = \$a\n".$marker."    - 1;\n", 1],
        ["<?php\nif (\n    \$a\n    && (\n".$marker."        \$b !== ''\n    )\n) {}\n", 0],
        ["<?php\n\$x = [\n".$marker."    -1,\n];\n", 0],
        ["<?php\nif (\$a)\n".$marker."    -\$b;\n", 0],
    ];

    foreach ($layouts as [$planted, $expected]) {
        if (count(scanSource($planted, 'control', $known, true)['findings']) !== $expected) {
            return false;
        }
    }

    return true;
}

/**
 * Tokens that, as the first code after a standalone marker on a later line, leave the
 * marker attached to no node. php-parser attaches a comment to the next node that STARTS
 * after it, and an infix operator, an object or scope operator, a separator or a closer
 * starts none: the expression it belongs to began on an earlier line. Measured with a
 * visitor recording which node carries the comment, php-parser v5.9.0
 * (9e33da9553fe): every token here left the comment on no node. Names are compared as
 * `token_name()` strings, so PHP 8 tokens need no constant on PHP 7.4.
 */
const UNATTACHED_AFTER = [
    'T_BOOLEAN_AND', 'T_BOOLEAN_OR', 'T_LOGICAL_AND', 'T_LOGICAL_OR', 'T_LOGICAL_XOR',
    'T_COALESCE', 'T_IS_EQUAL', 'T_IS_NOT_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL',
    'T_IS_SMALLER_OR_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_SPACESHIP', 'T_POW', 'T_SL', 'T_SR',
    'T_INSTANCEOF', 'T_OBJECT_OPERATOR', 'T_NULLSAFE_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
    'T_PAAMAYIM_NEKUDOTAYIM', 'T_DOUBLE_ARROW',
    '.', ':', '*', '/', '%', '<', '>', '|', '^', ')', ']', ',',
];

/**
 * Tokens that are an infix operator after an operand and a prefix (unary minus, a
 * reference, a nullable type) anywhere else. As a prefix they start a node, and the
 * marker attaches to it (measured: `ArrayItem`, `Arg`, `Param`, `Expr_UnaryMinus`), so
 * these are flagged only when the code before the marker ends an operand.
 */
const UNATTACHED_AFTER_OPERAND = [
    '+', '-', '&', '?', 'T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG', 'T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG',
];

/**
 * Tokens an operand can end with, so that a following `+`, `-`, `&` or `?` is infix. A
 * `)` or `]` counts only when it does not close a control structure's condition or an
 * attribute (see `closesOperand()`), and any name after `::` counts, keywords included
 * (`Foo::class`). A `}` does not count: after a block it starts a new statement, which
 * the marker attaches to, and a token check cannot tell that from the end of a `match`
 * or `new class` expression, so a marker after those is not judged.
 */
const OPERAND_ENDS = [
    'T_VARIABLE', 'T_STRING', 'T_NAME_QUALIFIED', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE',
    'T_LNUMBER', 'T_DNUMBER', 'T_CONSTANT_ENCAPSED_STRING', 'T_END_HEREDOC', 'T_INC', 'T_DEC',
    'T_LINE', 'T_FILE', 'T_DIR', 'T_CLASS_C', 'T_FUNC_C', 'T_METHOD_C', 'T_NS_C', 'T_TRAIT_C',
    ')', ']', '"',
];

/** Keywords whose parenthesized condition a braceless body follows: `if ($a) -$b;`. */
const CONDITION_KEYWORDS = ['T_IF', 'T_ELSEIF', 'T_WHILE', 'T_FOR', 'T_FOREACH', 'T_DECLARE', 'T_SWITCH'];

/**
 * The index of the nearest code token before `$index`, or -1.
 *
 * @param  list<array{0: int, 1: string, 2: int}|string>  $tokens
 */
function previousCode(array $tokens, int $index): int
{
    $index--;

    while ($index >= 0 && isTrivia($tokens[$index])) {
        $index--;
    }

    return $index;
}

/**
 * Whether the code token at `$index` ends an operand, so that a following `+`, `-`, `&`
 * or `?` is an infix operator.
 *
 * @param  list<array{0: int, 1: string, 2: int}|string>  $tokens
 */
function closesOperand(array $tokens, int $index): bool
{
    if ($index < 0) {
        return false;
    }

    $kind = tokenKind($tokens[$index]);
    $before = previousCode($tokens, $index);

    if ($before >= 0 && in_array(tokenKind($tokens[$before]), ['T_DOUBLE_COLON', 'T_PAAMAYIM_NEKUDOTAYIM'], true)) {
        return true;
    }

    if (! in_array($kind, OPERAND_ENDS, true)) {
        return false;
    }

    if ($kind !== ')' && $kind !== ']') {
        return true;
    }

    // Find the opener this closer matches; an attribute's `#[` is one token on PHP 8
    $depth = 0;

    for ($at = $index; $at >= 0; $at--) {
        $here = tokenKind($tokens[$at]);

        if ($here === ')' || $here === ']') {
            $depth++;
        } elseif ($here === '(' || $here === '[' || $here === 'T_ATTRIBUTE') {
            $depth--;
        }

        if ($depth === 0) {
            if ($here === 'T_ATTRIBUTE') {
                return false;
            }

            $keyword = previousCode($tokens, $at);

            return ! ($here === '(' && $keyword >= 0 && in_array(tokenKind($tokens[$keyword]), CONDITION_KEYWORDS, true));
        }
    }

    return true;
}

/**
 * A token's kind as one string: the character itself, or its `token_name()`.
 *
 * @param  array{0: int, 1: string, 2: int}|string  $token
 */
function tokenKind($token): string
{
    return is_array($token) ? token_name($token[0]) : $token;
}

/**
 * Whether a token is whitespace or a comment, which the attachment search steps over.
 *
 * @param  array{0: int, 1: string, 2: int}|string  $token
 */
function isTrivia($token): bool
{
    return is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true);
}

/**
 * The reason a standalone marker attaches to no node, or null when it attaches.
 *
 * Only a marker whose next code starts a LATER line than the marker itself is judged: a
 * node starting on the marker's own line is still reached by the plugin's line map. The
 * lines are counted from the token texts, so a `//` comment holding its newline (PHP 7.4)
 * and one leaving it to the whitespace (PHP 8) count the same.
 *
 * @param  list<array{0: int, 1: string, 2: int}|string>  $tokens
 */
function unattachedReason(array $tokens, int $at): ?string
{
    $text = $tokens[$at][1];
    $gap = '';
    $next = $at + 1;

    while (isset($tokens[$next]) && isTrivia($tokens[$next])) {
        $gap .= $tokens[$next][1];
        $next++;
    }

    $markerLine = $tokens[$at][2] + substr_count(substr($text, 0, (int) strpos($text, MARKER)), "\n");
    $line = $tokens[$at][2] + substr_count($text.$gap, "\n");

    if (! isset($tokens[$next]) || $line <= $markerLine) {
        return null;
    }

    $kind = tokenKind($tokens[$next]);

    if (! in_array($kind, UNATTACHED_AFTER, true)) {
        if (! in_array($kind, UNATTACHED_AFTER_OPERAND, true) || ! closesOperand($tokens, previousCode($tokens, $at))) {
            return null;
        }
    }

    $shown = is_array($tokens[$next]) ? $tokens[$next][1] : $tokens[$next];

    return 'unattached: the next code is `'.$shown.'` on line '.$line.', which starts no node, so the marker attaches to no node and suppresses nothing; put it directly above the operand, wrapping an operand after a leading operator in parentheses';
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
    $tokens = token_get_all($source);

    foreach ($tokens as $index => $token) {
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

        $standalone = trim($before, " \t/*#") === '';

        if (! $standalone && ! $lineMapLive) {
            $findings[] = [$where, 'trailing: does not begin its line, so it attaches to the NEXT node', $first];

            continue;
        }

        $unattached = $standalone ? unattachedReason($tokens, $index) : null;

        if ($unattached !== null) {
            $findings[] = [$where, $unattached, $first];

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

    $paths = filesToScan($roots);

    if (is_string($paths)) {
        fwrite(STDERR, "check-mutate-ignore: not a file or directory: {$paths}\n");

        return 2;
    }

    foreach ($paths as $path) {
        $source = file_get_contents($path);

        if ($source === false) {
            fwrite(STDERR, "check-mutate-ignore: cannot read {$path}\n");

            return 2;
        }

        $files++;
        $result = scanSource($source, $path, $known, $lineMapLive);
        $annotations += $result['annotations'];
        array_push($findings, ...$result['findings']);
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
