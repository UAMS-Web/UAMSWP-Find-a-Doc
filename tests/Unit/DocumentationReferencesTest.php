<?php

/**
 * Pins the commands and paths the contributing documentation names, so a guide cannot point at ones that do not exist.
 *
 * Each document is read for two kinds of reference and every one must resolve in this checkout:
 *
 * - a Composer script: `composer <name>`, `composer run <name>` or `composer run-script <name>` in a code block or a
 *   code span, which must be defined under `scripts` in composer.json (Composer's own commands are not scripts);
 * - a path: the target of a Markdown link, or a code span that has a directory separator and either an extension or a
 *   trailing slash (`tests/build/app.golden.css`, `.github/workflows/ci.yml`, `docs/`), which must exist.
 *
 * Not checked: paths that live in another repository (EXTERNAL_PATHS), a bare file name in a code span (`composer.json` has no separator, and `CLAUDE.md` is named in
 * AGENTS.md as absent), anything with a placeholder (`<name>`, `*`), paths under vendor/ and node_modules/ (installed
 * on demand, not tracked), HTML comments, and links to other sites or repositories.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DocumentationReferencesTest extends TestCase
{
    /** Documents whose references are checked. A document added later is added here. */
    private const DOCUMENTS = [
        'README.md',
        'CONTRIBUTING.md',
        'AGENTS.md',
        '.github/pull_request_template.md',
    ];

    /** Commands Composer itself provides; these are not scripts defined in composer.json. */
    private const COMPOSER_COMMANDS = [
        'about', 'archive', 'audit', 'browse', 'bump', 'check-platform-reqs', 'clear-cache', 'clearcache', 'config',
        'create-project', 'depends', 'diagnose', 'dump-autoload', 'dumpautoload', 'exec', 'fund', 'global', 'help',
        'home', 'i', 'info', 'init', 'install', 'licenses', 'list', 'normalize', 'outdated', 'prohibits', 'reinstall',
        'remove', 'require', 'run', 'run-script', 'search', 'self-update', 'selfupdate', 'show', 'status',
        'suggests', 'u', 'update', 'upgrade', 'validate', 'why', 'why-not',
    ];

    /**
     * Paths the documents name that live in another repository, so they are not looked up here.
     * AGENTS.md's generated section says the shared files are edited in `shared/` of UAMS-Web/uams-claude-skills.
     */
    private const EXTERNAL_PATHS = ['shared/'];

    private static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @return string[] Names of the scripts composer.json defines.
     */
    private static function definedScripts(): array
    {
        $json = file_get_contents(self::root().'/composer.json');
        self::assertIsString($json, 'composer.json is readable');

        $decoded = json_decode($json, true);
        self::assertIsArray($decoded, 'composer.json is valid JSON');

        return array_keys($decoded['scripts'] ?? []);
    }

    /**
     * The Markdown with fenced blocks and HTML comments split out.
     *
     * @return array{prose: string, fenced: string[]}
     */
    private static function split(string $markdown): array
    {
        $markdown = preg_replace('/<!--.*?-->/s', '', $markdown) ?? '';
        $fenced = [];

        $prose = preg_replace_callback('/^```[^\n]*\n(.*?)^```[ \t]*$/ms', function (array $match) use (&$fenced): string {
            foreach (explode("\n", $match[1]) as $line) {
                $fenced[] = $line;
            }

            return '';
        }, $markdown) ?? '';

        return ['prose' => $prose, 'fenced' => $fenced];
    }

    /**
     * @return string[] Code span contents in the prose.
     */
    private static function codeSpans(string $prose): array
    {
        preg_match_all('/`([^`\n]+)`/', $prose, $matches);

        return $matches[1];
    }

    /**
     * @return string[] Composer script names the document invokes.
     */
    public static function scriptsNamedIn(string $markdown): array
    {
        $parts = self::split($markdown);
        $snippets = array_merge($parts['fenced'], self::codeSpans($parts['prose']));
        $names = [];

        foreach ($snippets as $snippet) {
            if (preg_match_all('/(?:^|\s)composer\s+(?:run(?:-script)?\s+)?([A-Za-z][\w:.-]*)/', $snippet, $matches)) {
                foreach ($matches[1] as $name) {
                    if (! in_array($name, self::COMPOSER_COMMANDS, true)) {
                        $names[] = $name;
                    }
                }
            }
        }

        return array_values(array_unique($names));
    }

    /**
     * @return string[] Repository paths the document names.
     */
    public static function pathsNamedIn(string $markdown): array
    {
        $parts = self::split($markdown);
        $paths = [];

        if (preg_match_all('/\]\(([^)\s]+)\)/', $parts['prose'], $matches)) {
            foreach ($matches[1] as $target) {
                if (preg_match('#^(?:[a-z][a-z0-9+.-]*:|//|\#)#i', $target) === 1) {
                    continue;
                }

                $target = preg_replace('/[#?].*$/', '', $target) ?? '';

                if ($target !== '' && preg_match('/[<>{}*]/', $target) !== 1) {
                    $paths[] = $target;
                }
            }
        }

        foreach (self::codeSpans($parts['prose']) as $span) {
            $isPath = preg_match('#^\.?[\w.-]+(?:/[\w.-]+)*/?$#', $span) === 1
                && str_contains($span, '/')
                && (str_ends_with($span, '/') || preg_match('#\.\w+$#', $span) === 1);

            if ($isPath) {
                $paths[] = $span;
            }
        }

        $paths = array_filter($paths, static fn (string $path): bool => preg_match('#^(?:\./)?(?:vendor|node_modules)(?:/|$)#', $path) !== 1);

        return array_values(array_unique($paths));
    }

    /**
     * The references in one document that do not resolve, each naming the document.
     *
     * @param  string[]  $scripts  Scripts composer.json defines.
     * @return string[]
     */
    public static function problemsIn(string $file, string $markdown, array $scripts, string $root): array
    {
        $problems = [];

        foreach (self::scriptsNamedIn($markdown) as $name) {
            if (! in_array($name, $scripts, true)) {
                $problems[] = sprintf('%s names the Composer script "%s", which composer.json does not define', $file, $name);
            }
        }

        $directory = dirname($file);

        foreach (self::pathsNamedIn($markdown) as $path) {
            $path = ltrim($path, '/');

            if (in_array($path, self::EXTERNAL_PATHS, true)) {
                continue;
            }

            $candidates = [$root.'/'.$path, $root.'/'.($directory === '.' ? '' : $directory.'/').$path];

            if (array_filter($candidates, file_exists(...)) === []) {
                $problems[] = sprintf('%s names the path "%s", which does not exist', $file, $path);
            }
        }

        return $problems;
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function documents(): array
    {
        $cases = [];

        foreach (self::DOCUMENTS as $document) {
            $cases[$document] = [$document];
        }

        return $cases;
    }

    #[DataProvider('documents')]
    public function test_every_composer_script_and_path_a_document_names_exists(string $document): void
    {
        $markdown = file_get_contents(self::root().'/'.$document);
        $this->assertIsString($markdown, $document.' exists and is readable');

        $this->assertSame([], self::problemsIn($document, $markdown, self::definedScripts(), self::root()));
    }

    public function test_the_documents_name_references_for_the_check_to_resolve(): void
    {
        // A reader that finds nothing would pass every document. The documents that exist today name both kinds.
        $scripts = [];
        $paths = [];

        foreach (self::DOCUMENTS as $document) {
            $markdown = (string) file_get_contents(self::root().'/'.$document);
            $scripts = array_merge($scripts, self::scriptsNamedIn($markdown));
            $paths = array_merge($paths, self::pathsNamedIn($markdown));
        }

        $this->assertContains('test:unit', $scripts);
        $this->assertContains('.github/workflows/ci.yml', $paths);
        $this->assertContains('tests/build/app.golden.css', $paths);
    }

    public function test_a_document_naming_an_undefined_composer_script_is_reported_with_its_name(): void
    {
        $markdown = "Run it:\n\n```bash\ncomposer install\ncomposer test:missing\n```\n\nOr `composer run lint:everything` or `composer run-script test:unit`.\n";

        $this->assertSame([
            'GUIDE.md names the Composer script "test:missing", which composer.json does not define',
            'GUIDE.md names the Composer script "lint:everything", which composer.json does not define',
        ], self::problemsIn('GUIDE.md', $markdown, ['test:unit'], self::root()));
    }

    public function test_composer_commands_and_defined_scripts_are_not_reported(): void
    {
        $markdown = "`composer install`, `composer update`, `composer run-script`, `composer test:unit`, `composer run test:unit`\n";

        $this->assertSame([], self::problemsIn('GUIDE.md', $markdown, ['test:unit'], self::root()));
    }

    public function test_a_document_naming_a_path_that_does_not_exist_is_reported_with_its_name(): void
    {
        $markdown = "See [the flow](docs/missing-flow.md) and `.github/workflows/gone.yml` and `includes/gone/`.\n"
            ."Present: [the readme](README.md#tests) and `tests/build/app.golden.css`.\n";

        $this->assertSame([
            'GUIDE.md names the path "docs/missing-flow.md", which does not exist',
            'GUIDE.md names the path ".github/workflows/gone.yml", which does not exist',
            'GUIDE.md names the path "includes/gone/", which does not exist',
        ], self::problemsIn('GUIDE.md', $markdown, [], self::root()));
    }

    public function test_a_link_resolves_relative_to_the_document_or_the_repository_root(): void
    {
        $this->assertSame([], self::problemsIn('.github/pull_request_template.md', '[flow](../CONTRIBUTING.md)', [], self::root()));
        $this->assertSame([], self::problemsIn('.github/pull_request_template.md', '[flow](CONTRIBUTING.md)', [], self::root()));
        $this->assertSame(
            ['.github/pull_request_template.md names the path "../nowhere.md", which does not exist'],
            self::problemsIn('.github/pull_request_template.md', '[flow](../nowhere.md)', [], self::root())
        );
    }

    public function test_references_that_are_not_repository_paths_are_left_alone(): void
    {
        $markdown = <<<'MD'
            [site](https://example.com/missing.html) [mail](mailto:a@b.c) [anchor](#top) [other](//cdn.example/x.js)
            `.claude/rules/<name>.md` `src/*.php` `UAMS-Web/uams-2020` `composer.json` `CLAUDE.md` `vendor/bin/pint`
            `node_modules/.bin/gulp` `master`

            <!-- `docs/hidden.md` and `composer test:hidden` live in a comment -->
            MD;

        $this->assertSame([], self::problemsIn('GUIDE.md', $markdown, [], self::root()));
    }
}
