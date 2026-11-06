<?php

declare(strict_types=1);

/**
 * Namespace-less test file guard: fail a Pest suite whose namespace-less files redeclare a
 * global symbol or import a class that is already global.
 *
 * Pest test files carry no namespace, so their top-level `const` and `function` declarations
 * live in the global namespace, and a bare `use X;` in them names a class that is already
 * global. Two defects follow, and neither shows when a file is run alone:
 *
 *   Rule C  The same global `const` or `function` name is declared in more than one file.
 *           PHP raises "Constant X already defined" or "Cannot redeclare X()" as a fatal
 *           the moment both files are loaded into one process. Under `pest --parallel`
 *           that depends on how the runner splits files across workers, so the suite
 *           passes on one run and fatals on the next. A function name is compared
 *           case-insensitively, as PHP does, so `helper()` in one file and `Helper()` in
 *           another collide; a constant name is case-sensitive. A name declared in exactly
 *           one file is always fine, and so is anything inside a named namespace, a class,
 *           a method or a closure. An unnamed `namespace { }` block is the global namespace,
 *           so its top-level declarations count.
 *   Rule D  A top-level single-segment `use X;` in a file with no named namespace. The
 *           import is a no-op, and PHP raises `E_WARNING` "The use statement with
 *           non-compound name 'X' has no effect" at compile time. A framework error
 *           handler that promotes warnings to exceptions (Laravel's does) aborts the
 *           worker before its first test, and the whole parallel band with it. The file
 *           passes alone, and the formatters and static analyzers do not flag it.
 *
 * Both are read from PHP tokens, so a match inside a comment or a string literal is ignored
 * and a declaration wrapped across lines is still one declaration.
 *
 * These are the two rules of the `uams-statamic` parallel-safety guard that hold outside
 * that application. Its rules about `Storage::fake()`, hardcoded `framework/testing` paths
 * and `assert()` narrowing depend on Laravel's layout or on that repository's own helpers
 * and stay there; `SKILL.md` records the classification.
 *
 * Written in PHP rather than Node so that a CI job which sets up PHP and nothing else can
 * run it. Dependency-free: no Composer autoload.
 *
 * USAGE
 *     php check-global-test-symbols.php [path ...]
 *
 *     Each path is a directory (scanned recursively for `*.php`) or a single file. The
 *     default is `tests`. Rule C compares declarations across every file scanned in one
 *     run, so pass the whole suite in one invocation.
 *
 * EXIT
 *     0  clean
 *     1  one or more findings
 *     2  the run could not see: a path is missing, or no PHP file was found
 *
 * The count of files and namespace-less files read is printed before any finding.
 */

/**
 * @param  list<string>  $argv
 * @return list<string>
 */
function scanPaths(array $argv): array
{
    $paths = array_slice($argv, 1);

    return $paths === [] ? ['tests'] : array_values($paths);
}

/**
 * PHP files under the given paths, sorted so output is stable.
 *
 * @param  list<string>  $paths
 * @return list<string>|null null when a path does not exist
 */
function collectFiles(array $paths): ?array
{
    $files = [];

    foreach ($paths as $path) {
        if (is_file($path)) {
            $files[] = $path;

            continue;
        }

        if (! is_dir($path)) {
            fwrite(STDERR, "check-global-test-symbols: path not found: {$path}\n");

            return null;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $info) {
            if ($info instanceof SplFileInfo && $info->isFile() && $info->getExtension() === 'php') {
                $files[] = $info->getPathname();
            }
        }
    }

    $files = array_values(array_unique($files));
    sort($files);

    return $files;
}

/**
 * Index of the next token that is not whitespace or a comment, or null at the end.
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 */
function nextMeaningful(array $tokens, int $index): ?int
{
    $count = count($tokens);

    for (; $index < $count; $index++) {
        $token = $tokens[$index];

        if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            continue;
        }

        return $index;
    }

    return null;
}

/**
 * The source text of a token (its literal for a single-character token).
 *
 * @param  array{0: int, 1: string, 2: int}|string  $token
 */
function tokenText(array|string $token): string
{
    return is_array($token) ? $token[1] : $token;
}

/**
 * Whether the file declares a named namespace. An unnamed `namespace { }` block is global.
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 */
function fileHasNamedNamespace(array $tokens): bool
{
    foreach ($tokens as $index => $token) {
        if (is_array($token) && $token[0] === T_NAMESPACE) {
            $next = nextMeaningful($tokens, $index + 1);

            return $next !== null
                && is_array($tokens[$next])
                && in_array($tokens[$next][0], [T_STRING, T_NAME_QUALIFIED], true);
        }
    }

    return false;
}

/**
 * Brace depth at each token, where the braces of an unnamed `namespace { }` block do not
 * count: that block is the global namespace, so its top-level declarations are depth 0.
 * Every other brace, and the opening tokens of string interpolation, add one level.
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 * @return array<int, int> depth in force when the token at that index is reached
 */
function braceDepths(array $tokens): array
{
    $depths = [];
    $stack = [];
    $unnamedBlockOpens = false;

    foreach ($tokens as $index => $token) {
        $depths[$index] = count(array_filter($stack));

        if (is_array($token) && $token[0] === T_NAMESPACE) {
            $next = nextMeaningful($tokens, $index + 1);
            $unnamedBlockOpens = $next !== null && $tokens[$next] === '{';

            continue;
        }

        if ($token === '{') {
            $stack[] = ! $unnamedBlockOpens;
            $unnamedBlockOpens = false;

            continue;
        }

        if ($token === '}') {
            array_pop($stack);

            continue;
        }

        // String-interpolation braces open with these tokens and close with a plain `}`.
        if (is_array($token) && in_array($token[0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true)) {
            $stack[] = true;
        }
    }

    return $depths;
}

/**
 * The declared constant names in a `const` statement, from just past the `const` keyword to
 * the terminating `;`. A comma inside an array or call value does not start a new name.
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 * @return list<array{name: string, line: int}>
 */
function constDeclarationNames(array $tokens, int $index): array
{
    $count = count($tokens);
    $expectName = true;
    $depth = 0;
    $names = [];

    for ($i = $index; $i < $count; $i++) {
        $token = $tokens[$i];

        if ($token === ';' && $depth === 0) {
            break;
        }

        if ($token === '[' || $token === '(' || $token === '{') {
            $depth++;

            continue;
        }

        if ($token === ']' || $token === ')' || $token === '}') {
            $depth--;

            continue;
        }

        if ($token === ',' && $depth === 0) {
            $expectName = true;

            continue;
        }

        if ($token === '=' && $depth === 0) {
            $expectName = false;

            continue;
        }

        if ($expectName && $depth === 0 && is_array($token) && $token[0] === T_STRING) {
            $names[] = ['name' => $token[1], 'line' => $token[2]];
            $expectName = false;
        }
    }

    return $names;
}

/**
 * Top-level `const` and `function` declarations of a namespace-less file (Rule C).
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 * @return list<array{kind: string, name: string, line: int}>
 */
function globalSymbols(array $tokens): array
{
    $count = count($tokens);
    $depths = braceDepths($tokens);
    $symbols = [];

    for ($i = 0; $i < $count; $i++) {
        $token = $tokens[$i];

        if (! is_array($token) || $depths[$i] !== 0) {
            continue;
        }

        if ($token[0] === T_FUNCTION) {
            // A named function: the next meaningful token, past an optional by-reference `&`,
            // is the name. An anonymous function is followed by `(` or `use` and is skipped.
            // PHP 8.1 tokenizes `&` as an array token, so it is matched by its text.
            $next = nextMeaningful($tokens, $i + 1);

            if ($next !== null && tokenText($tokens[$next]) === '&') {
                $next = nextMeaningful($tokens, $next + 1);
            }

            if ($next !== null && is_array($tokens[$next]) && $tokens[$next][0] === T_STRING) {
                $symbols[] = ['kind' => 'function', 'name' => $tokens[$next][1], 'line' => $token[2]];
            }

            continue;
        }

        if ($token[0] === T_CONST) {
            foreach (constDeclarationNames($tokens, $i + 1) as $const) {
                $symbols[] = ['kind' => 'const', 'name' => $const['name'], 'line' => $const['line']];
            }
        }
    }

    return $symbols;
}

/**
 * Top-level single-segment imports of a namespace-less file (Rule D). Only depth-0 `use`
 * counts: a trait `use` inside a class body and a closure's `use ($x)` are different
 * constructs. `use function foo;` and `use const BAR;` warn identically and are included.
 *
 * @param  array<int, array{0: int, 1: string, 2: int}|string>  $tokens
 * @return list<array{name: string, line: int}>
 */
function singleSegmentImports(array $tokens): array
{
    $count = count($tokens);
    $depths = braceDepths($tokens);
    $imports = [];

    for ($i = 0; $i < $count; $i++) {
        $token = $tokens[$i];

        if ($depths[$i] !== 0 || ! is_array($token) || $token[0] !== T_USE) {
            continue;
        }

        $next = nextMeaningful($tokens, $i + 1);

        if ($next !== null && is_array($tokens[$next]) && in_array($tokens[$next][0], [T_FUNCTION, T_CONST], true)) {
            $next = nextMeaningful($tokens, $next + 1);
        }

        // A qualified name is compound and meaningful, and a group use is qualified. Only a
        // bare name ended by `;` is the no-op shape.
        if ($next === null || ! is_array($tokens[$next]) || $tokens[$next][0] !== T_STRING) {
            continue;
        }

        $terminator = nextMeaningful($tokens, $next + 1);

        if ($terminator !== null && $tokens[$terminator] === ';') {
            $imports[] = ['name' => $tokens[$next][1], 'line' => $tokens[$next][2]];
        }
    }

    return $imports;
}

/**
 * Run both rules over the files.
 *
 * @param  list<string>  $files
 * @return array{withoutNamespace: int, importFindings: list<array{file: string, line: int, name: string}>, duplicates: array<string, list<array{file: string, line: int, name: string}>>}
 */
function analyze(array $files): array
{
    $withoutNamespace = 0;
    $importFindings = [];
    $declared = [];

    foreach ($files as $file) {
        $tokens = token_get_all((string) file_get_contents($file));

        if (fileHasNamedNamespace($tokens)) {
            continue;
        }

        $withoutNamespace++;

        foreach (singleSegmentImports($tokens) as $import) {
            $importFindings[] = ['file' => $file, 'line' => $import['line'], 'name' => $import['name']];
        }

        foreach (globalSymbols($tokens) as $symbol) {
            // PHP function names are case-insensitive, so `helper()` and `Helper()` are one
            // function and collide. Constant names are case-sensitive and keep their spelling.
            $key = $symbol['kind'] === 'function' ? strtolower($symbol['name']) : $symbol['name'];
            $declared[$symbol['kind'].' '.$key][] = ['file' => $file, 'line' => $symbol['line'], 'name' => $symbol['name']];
        }
    }

    $duplicates = [];

    foreach ($declared as $key => $occurrences) {
        if (count(array_unique(array_column($occurrences, 'file'))) > 1) {
            $duplicates[$key] = $occurrences;
        }
    }

    ksort($duplicates);

    return ['withoutNamespace' => $withoutNamespace, 'importFindings' => $importFindings, 'duplicates' => $duplicates];
}

$files = collectFiles(scanPaths($argv));

if ($files === null) {
    exit(2);
}

if ($files === []) {
    fwrite(STDERR, "check-global-test-symbols: no PHP file found under the given path(s); a run that read nothing has not checked anything.\n");
    exit(2);
}

$result = analyze($files);

echo sprintf("check-global-test-symbols: read %d file(s), %d without a named namespace\n", count($files), $result['withoutNamespace']);

if ($result['importFindings'] === [] && $result['duplicates'] === []) {
    echo "check-global-test-symbols: no redeclared global symbol and no no-op import.\n";
    exit(0);
}

foreach ($result['importFindings'] as $finding) {
    echo sprintf(
        "Rule D  %s:%d  `use %s;` names a class that is already global; delete it (the warning is fatal under --parallel).\n",
        $finding['file'],
        $finding['line'],
        $finding['name'],
    );
}

foreach ($result['duplicates'] as $symbol => $occurrences) {
    // Name each spelling only when they differ, so a case-only difference (`helper` and
    // `Helper`) is visible and the common same-spelling message stays short.
    $spellings = count(array_unique(array_column($occurrences, 'name'))) > 1;
    $where = implode(', ', array_map(fn (array $o): string => $o['file'].':'.$o['line'].($spellings ? ' ('.$o['name'].')' : ''), $occurrences));
    echo sprintf("Rule C  %s is declared in more than one file: %s. Rename one or give the file a namespace.\n", $symbol, $where);
}

exit(1);
