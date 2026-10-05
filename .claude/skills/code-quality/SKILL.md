---
name: code-quality
description: >-
  The shared code-quality standard for UAMS-Web PHP repositories: Rector for automated
  refactoring and upgrades, Larastan (Laravel and Statamic) or PHPStan (everywhere else, with the
  WordPress extension and stubs for WordPress code) for static analysis, and Pint with the
  `laravel` preset for formatting. Covers adding the three tools to a repository as development
  dependencies, copying and filling in the shared templates, finding the static-analysis level to
  start at and raising it, the one-commit Pint reformat and `.git-blame-ignore-revs`, keeping
  WordPressCS's security sniffs beside Pint, restricting the tools to our modifications in a fork
  of a vendor plugin, and what to do with a finding you disagree with. Activate when adding or
  running Rector, PHPStan, Larastan or Pint in a PHP repository, fixing what they report, deciding
  or changing a static-analysis level, or writing a repository's code-quality adoption ticket.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/code-quality/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): quality.
-->
<!-- cspell:ignore analyse driftingly getrector Larastan larastan phpcbf phpcs szepeviktor -->

# Code quality: Rector, Larastan or PHPStan, and Pint

## The standard

Every PHP repository in UAMS-Web runs the same three tools wherever each can run (UAMS-Web/uams-claude-skills#10):

| Tool | Does | Package |
|---|---|---|
| Rector | Automated refactoring and PHP upgrades, in two profiles: a gating profile that is mandatory and a sweep profile run deliberately | `rector/rector`, plus `driftingly/rector-laravel` in Laravel and Statamic |
| Larastan or PHPStan | Static analysis. Larastan in Laravel and Statamic applications; PHPStan everywhere else, with `szepeviktor/phpstan-wordpress` and `php-stubs/wordpress-stubs` (`^6.8`) for WordPress code | `larastan/larastan`, or `phpstan/phpstan` |
| Pint | Formatting, with the `laravel` preset | `laravel/pint` |

The tools run on the developer's PHP, not on the version the code supports. A plugin that still supports PHP 7.x can run all three as development dependencies; Rector is configured to the repository's supported PHP version, so it never rewrites code into syntax that version cannot parse, and PHPStan is told the same version range. A repository that cannot run a tool records why on its adoption ticket, and what would let it.

Every repository also pins line endings in `.gitattributes` with `* text=auto eol=lf`. Without that line, Windows `core.autocrlf=true` rewrites a checkout to CRLF and the damage shows up as something else: Pint `line_ending` reports, YAML or skill frontmatter that fails to parse (`---` vs `---\r`), or line-based tests matching a trailing `\r`. A committed `eol` attribute outranks `core.autocrlf`, so the working tree stays LF on every platform. `* text=auto` alone is not enough: it normalizes on commit but still checks out CRLF when autocrlf is on. Copy `templates/.gitattributes` when the file is missing, or add that one line at the top when the file already exists for `export-ignore` or other rules. Adding the attribute does not rewrite bytes already committed as CRLF; confirm with `git ls-files --eol` and renormalize only when the index half shows `i/crlf` (see the line-endings section of the [`worktrees`](../../rules/worktrees.md) rule where a repository carries it).

Pint's `laravel` preset is the one style across the organization's PHP, WordPress code included. Pint has no WordPress preset (its presets are `laravel`, `per`, `psr12`, `symfony` and `empty`), and a second style in WordPress code was rejected: `uamswp-migration-api` already formats WordPress code with the `laravel` preset (its `docs/DECISIONS.md` D-014), and the alternatives, a custom WordPress-style configuration on the `empty` preset or Pint outside WordPress only, mean custom rules to maintain or two styles.

The templates beside this skill are the shared starting configurations. Each repository keeps its own copy with its own paths, PHP version and current static-analysis level.

| Template | Copy to | For |
|---|---|---|
| `templates/.gitattributes` | repository root (or merge its `* text=auto eol=lf` line into an existing file) | every repository |
| `templates/rector.php.dist` and `templates/rector-sweep.php.dist` | repository root as `rector.php` / `rector-sweep.php` (drop `.dist`) | every repository |
| `templates/phpstan.neon.dist` | `phpstan.neon.dist` | PHP that is neither Laravel nor WordPress |
| `templates/phpstan-wordpress.neon.dist` | `phpstan.neon.dist` | WordPress plugins, must-use plugins and themes |
| `templates/phpstan-larastan.neon.dist` | `phpstan.neon.dist` | Laravel and Statamic applications |
| `templates/pint.json` | repository root | every repository |
| `templates/phpcs.xml.dist` | repository root | only a repository that keeps WordPressCS |
| `templates/.git-blame-ignore-revs` | repository root | every repository, at the reformat commit |

## Adding the tools to a repository

### Development dependencies

```sh
composer require --dev rector/rector laravel/pint
composer require --dev larastan/larastan              # Laravel and Statamic
composer require --dev driftingly/rector-laravel      # Laravel and Statamic
composer require --dev phpstan/phpstan                # everywhere else
composer require --dev szepeviktor/phpstan-wordpress "php-stubs/wordpress-stubs:^6.8"   # WordPress code
composer require --dev php-stubs/wp-cli-stubs   # only when the code uses WP-CLI
```

Pin `php-stubs/wordpress-stubs` to 6.x (`^6.8`, as `uamswp-migration-api` does). A bare `composer require php-stubs/wordpress-stubs` can resolve 7.1, which breaks `szepeviktor/phpstan-wordpress` (and `php-stubs/wp-cli-stubs`, which also needs stubs 6.x). When the plugin or theme calls WP-CLI, require `php-stubs/wp-cli-stubs` and uncomment the `scanFiles` block in `templates/phpstan-wordpress.neon.dist`.

Current majors: Rector 2 and PHPStan 2 run on PHP 7.4 or later; Larastan 3 needs PHP 8.2 and Laravel 11.15 or later; Pint needs PHP 8.3 or later; `szepeviktor/phpstan-wordpress` 2.x needs PHPStan 2.x (all from the packages' `composer.json` and READMEs, 2026-10-02). A repository on PHPStan 1.x upgrades PHPStan and the WordPress extension together.

Do not pin `config.platform.php` to the supported floor to make Composer resolve for it: that stops the current Pint installing on a PHP 7.x or 8.2 plugin. The floor goes into `rector.php` (`withPhpVersion()`) and `phpstan.neon.dist` (`phpVersion`), which is what the tools read. A repository that already pins the platform for another reason, as `uamswp-migration-api` does at `8.2.30` for its test toolchain (D-019), keeps that pin; Rector and PHPStan both read it when nothing more specific is set.

A repository with no `composer.json` gets one holding only development dependencies:

```json
{
    "require": {
        "php": ">=7.4"
    },
    "require-dev": {
        "laravel/pint": "^1.13",
        "phpstan/phpstan": "^2.1",
        "rector/rector": "^2.5"
    },
    "config": {
        "sort-packages": true
    }
}
```

`require.php` is the lowest version the code supports, taken from the plugin header's `Requires PHP` or the README. It is required, not optional: Rector's `withPhpSets()` reads its floor from there and fails without it, and PHPStan infers its version from it until `phpVersion` is filled in. Nothing in `require` beyond `php`, so the deployed code gains no autoloader and no runtime dependency. Keep the tooling out of the deployed artifact: `vendor/` is git-ignored, and the server never runs `composer install`. For packaging:

- Mark `composer.json`, `composer.lock`, `rector.php`, `rector-sweep.php`, `phpstan.neon.dist`, `pint.json`, `phpcs.xml.dist`, `vendor/` and `tests/` `export-ignore` in `.gitattributes` so `git archive` omits them.
- `export-ignore` does not help a release built with `zip -r` over the working tree, or a site that installs the plugin by `git clone`: both still carry `vendor/` (and the rest) unless the build excludes those paths explicitly. Prefer `git archive`, or a release script that copies only the runtime paths into the zip; never ship a clone that has had `composer install` run.

### Copy the templates and fill in the placeholders

Every line to change is marked `PLACEHOLDER` in the templates. Replace each placeholder with the real value and remove or rewrite the `PLACEHOLDER` marker itself so it does not remain as a TODO. Three things to fill in:

- **Paths.** The directories and files holding first-party PHP, in `rector.php` (`withPaths()`, plus `withRootFiles()` for a plugin whose header file sits at the root), `phpstan.neon.dist` (`paths`) and, for a fork, `pint.json` (`exclude`). Include `tests/`: test bugs are real bugs, and the two repositories that analyze tests at the same level as source (uams-statamic, uamswp-migration-api) both say so in their configs. Never a vendor directory. In a WordPress repository whose tests run on Pest and wp-phpunit, uncomment what `templates/phpstan-wordpress.neon.dist` marks for that case (the Pest extension, the `scanDirectories` entry and the three Pest entries, which sit commented inside the `ignoreErrors` list, so removing each line's leading `# ` is enough): without them the `tests` skill's plugin template fails at level 0.
- **PHP version.** `withPhpVersion(PhpVersion::PHP_xx)` in `rector.php` is the lowest supported version, and Rector skips any rule that needs a newer PHP; its order of precedence for that check is this call, then `composer.json`'s `php` require, then `config.platform.php`, then the running PHP (getrector.com/documentation/php-version-features, 2026-10-02). `withPhpSets()` with no argument, which applies every PHP upgrade set up to the floor and no further, does not read `withPhpVersion()`: it reads `composer.json`'s `require.php`, then `config.platform.php`, and fails the run when neither is set (Rector 2.6.7, `RectorConfigBuilder::withPhpSets()`, 2026-10-02). So `composer.json` carries `require.php` at the same floor, as the dev-only `composer.json` above does. `phpVersion` in `phpstan.neon.dist` is a range: `min` the same floor, `max` the developer's PHP. Left out, PHPStan infers one version from `composer.json` and does not report constructs that are unavailable at the floor; in `uamswp-migration-api` a typed class constant (PHP 8.3+) analyzed clean that way and would have been a parse error on the PHP 8.2 it ships to. The range form needs PHPStan 2.0 or later (phpstan.org/config-reference, 2026-10-02).
- **Level.** See "Finding the starting static-analysis level" below. The template ships at `0`.

Add the cache directory the template names to `.gitignore` (`/.phpstan/`, or `/storage/phpstan` for the Larastan template). The `tmpDir` comment in each template explains why the cache is checkout-scoped; keep it.

The shared `templates/pint.json` turns on `declare_strict_types`. That is not only a formatting choice: after the first full Pint run, files gain `declare(strict_types=1);`, and built-in PHP functions then reject bad argument types with `TypeError` instead of coercing. PHPStan will also start reporting untyped arguments passed to those built-ins (often a large set at higher levels). Adopters must know this before the first repository-wide Pint run; budget for the runtime and analyzer fallout in the adoption ticket, or temporarily leave the rule off in that repository's `pint.json` only with a recorded reason (and expect the shared template to keep it on).

For Laravel and Statamic, uncomment the `LaravelSetProvider` block in `rector.php`. For WordPress, uncomment `ArrayToFirstClassCallableRector::class` in the sweep-only list and the matching `use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;` at the top of `rector.php` (never inside the array: a `use` there is a parse error). WordPress matches hook callbacks by identity, so a first-class callable silently breaks `remove_action()` (D-016).

WordPress PHPStan notes (see comments in `templates/phpstan-wordpress.neon.dist`):

- Constants defined from `plugin_dir_url()`, `plugin_dir_path()`, or similar function calls are often unknown to PHPStan. Define stand-ins in a `bootstrapFiles` bootstrap, or list the names under `dynamicConstantNames` when only "defined, value varies" is needed.
- `bleedingEdge.neon` is included by default. It adds no Composer dependency, but it can break WordPress stub conditional return types (for example `get_sites()`). Keep it while analysis is clean; if a failure appears only with that include, drop the include or add a narrow `ignoreErrors` entry naming the stub function.

### Composer scripts

```json
"scripts": {
    "stan": "phpstan analyse --no-progress --memory-limit=2G",
    "refactor": "rector",
    "refactor:sweep": "rector --clear-cache --config=rector-sweep.php",
    "test:refactor": "rector --dry-run",
    "test:refactor:sweep": "rector --dry-run --clear-cache --config=rector-sweep.php",
    "pint": "pint",
    "test:pint": "pint --test"
}
```

`stan`, `refactor`, `refactor:sweep`, `test:refactor` and `test:refactor:sweep` are the names uams-statamic and uamswp-migration-api already use. `refactor` applies the gating profile, `test:refactor` previews it; the `:sweep` pair does the same for the sweep profile, which is run deliberately and reviewed, never as the gate. `pint` formats; `test:pint` is the check the runner uses. While working, run `vendor/bin/pint --dirty` on what you touched, not `--test`.

Rector often needs more than one apply pass before a dry-run is clean: after `composer refactor` (or `refactor:sweep`), run the matching `test:refactor` / `test:refactor:sweep` again and repeat apply until the dry-run reports no further changes.

The gating and sweep profiles share one Rector cache. A dry-run of one profile right after the other can look like a no-op unless the cache is cleared. The `:sweep` scripts above pass `--clear-cache`; when switching the other way (sweep, then gate), run the gating command with `--clear-cache` as well (for example `vendor/bin/rector --clear-cache --dry-run`), or clear once before the first run of the new profile.

### Local CI jobs

Three jobs, one per tool, run before a pull request:

| Job | Command | Gated by |
|---|---|---|
| `pint` | `vendor/bin/pint --test` | any `.php` in the diff |
| `rector` | `vendor/bin/rector --dry-run` (the gating profile) | any `.php` under the paths `rector.php` names |
| `phpstan` | `composer stan` | any `.php` in the diff, tests included, because `phpstan.neon.dist` lists `tests` |

This repository is public, so Actions minutes are free: run the three commands as steps of its CI workflow, and locally before pushing. A private repository without the local-ci runner yet does the same three commands locally before every push (or as workflow steps if minutes are acceptable); adopt local-ci when that skill is available in the checkout so gating stops depending on Actions.

## Finding the starting static-analysis level

Each repository adopts Larastan or PHPStan at the highest level its code passes today, then rises one level per change until it reaches the shared target, which is `max`.

1. With `level: 0` in `phpstan.neon.dist`, run `composer stan`. Fix what it reports if there is little; otherwise note the count.
2. Raise `level` by one and run again. Repeat until a level reports errors you are not fixing in this change.
3. Set `level` to the last level that passed, and commit the configuration. That is the gate from now on.
4. Each later change may raise the level by one once the findings at the next level are fixed. Raise it in the same pull request as the fixes, never ahead of them.

When finding that starting level, do not count on `ignoreErrors` entries that paper over existing debt (identifier/path pins added so a higher level looks green). The starting level is the highest level the code passes with only the ignores the template already documents for harness noise. The WordPress template's Pest entries (`reportUnmatched: false`, scoped to `tests/`) are that kind of harness noise and may stay uncommented when the suite needs them; they do not invent a higher starting level. New pins for real code debt land only after the starting level is set, each with a comment naming the cause.

Where the three configured repositories stand (from their `phpstan.neon.dist`, 2026-10-02): `uams-statamic` is at `level: 10`, climbing one level per commit toward `max` (its #914); `wordpress-importer` is at `max`; `uamswp-migration-api` opened at `max` with `phpVersion` 8.2 to 8.4, because it was a thousand lines old when the analyzer arrived and had no debt to pay down. PHPStan's levels run 0 to 10, and `max` is an alias for the highest, so a repository at `max` picks up the next level automatically when PHPStan adds one (phpstan.org/user-guide/rule-levels, 2026-10-02).

A baseline file, which PHPStan offers as the other way to adopt a high level by recording every existing error and suppressing it, is not used. Issue #10 weighed it against starting low and rising and chose the latter: with a baseline the level in the configuration says nothing about the code, since the suppressed list can hold any number of errors at that level, and that list is a second backlog to drain with no step at which the whole codebase is known to pass. Starting at the level the code passes means every level the configuration has ever named is one the entire codebase passed. The other alternative, fixing every finding before adopting, delays adoption until the whole codebase is clean.

## The reformat commit

The first Pint run over a repository touches most files. Land it as one commit holding only Pint's changes, so `git blame` can skip it:

1. Commit the tooling configuration first (`pint.json`, `rector.php`, `phpstan.neon.dist`, Composer changes, and the rest of the adoption configs). Then open a branch whose only remaining work is the reformat (or run the reformat on a branch that already has those configs on `main`). Do not mix config adoption and the mass reformat in one commit.
2. On that reformat-only branch, run `vendor/bin/pint` (no `--dirty`, the whole repository) and read the diff.
3. Commit only what Pint changed, with a message saying so. Nothing else goes in this commit.
4. Copy `templates/.git-blame-ignore-revs` to the repository root and put the commit's full hash in it, under the comment that says what the commit was (replace the `PLACEHOLDER` marker when you do).
5. Commit that file, then tell `git blame` to read it, once per clone:

   ```sh
   git config blame.ignoreRevsFile .git-blame-ignore-revs
   ```

   The setting is per clone and not committed, so every developer runs it; put the line in the repository's README or setup script.
6. Before the pull request, prove the result still parses on the lowest PHP the repository supports. Run `php -l` (Herd ships versioned binaries such as `php82`) over every file Pint changed and every file Rector changed in the adoption. The acceptance criterion for #10 is that no adoption makes the code fail to run on the version it supports, and only that floor's parser proves it.

   Limits of this step: Herd may not ship an older binary (for example no `php74` on a machine that only has 8.x). A newer binary's `php -l` (such as `php82 -l` on a PHP 7.4 plugin) is not a substitute: it accepts syntax the floor rejects and rejects nothing the floor would accept for 7.x-only parse issues. When the floor binary is missing locally, parse-check on a Docker image or CI matrix job that runs that PHP version, or on any environment that still has the supported floor installed. Skipping the floor parse check is not an option for a repository that still claims that floor.

The hash recorded in `.git-blame-ignore-revs` is the reformat commit as it exists on the default branch after merge. A merge commit (or rebase that keeps the same commit object) preserves that hash. A squash merge creates a new commit with a different hash, so the file committed on the feature branch then points at a commit that never landed; update `.git-blame-ignore-revs` on the default branch to the squash commit's full hash after the merge.

The known costs in WordPress code (issue #10 and D-014): Yoda conditions are unwound, arrays become short arrays, and `@package` tags are stripped. All three go against the WordPress Coding Standards and were accepted deliberately; do not relitigate them in a reformat pull request. The `declare_strict_types` rule in the shared `pint.json` is a further cost: see "Copy the templates" above.

Where a later Pint upgrade or rule change reformats the repository again, that is another single commit and another line in `.git-blame-ignore-revs`.

## Repositories running WordPressCS

Where a repository runs WordPressCS today (`uamswp-mcp`, `uamswp-restrict-user-enumeration`), its formatting sniffs are switched off so phpcs and Pint do not fight, and its security sniffs stay: output escaping, nonce verification, input validation and sanitization, safe redirects, prepared SQL. Pint has no equivalent of those.

Replace the repository's `phpcs.xml.dist` with `templates/phpcs.xml.dist`. It references the whole `WordPress.Security` category (`EscapeOutput`, `NonceVerification`, `ValidatedSanitizedInput`, `SafeRedirect`, `PluginMenuSlug`) and the two prepared-SQL sniffs under `WordPress.DB`, and nothing from `WordPress-Core` or `WordPress-Extra` (sniff names from the WordPressCS repository, 3.4.1, 2026-10-02). Carry over any repository-specific sniff properties worth keeping, such as `WordPress.WP.Capabilities` with its custom capabilities in `uamswp-mcp`. Drop the `lint:fix` (`phpcbf`) script: with no formatting sniffs there is nothing for it to fix, and running it beside Pint is the fight the template avoids. Keep `lint` (`phpcs`) as a fourth local CI job for that repository.

## Forks of vendor plugins

In a fork of a vendor plugin (`uamswp-seopress`, `uamswp-seopress-pro`, `UAMSWP-minervakb`) the tools run only on our own modifications, selected by path, so an upstream update merges without conflicts caused by our tooling:

- `rector.php`: `withPaths()` lists only the directories holding our code; nothing from upstream, and no `withRootFiles()` if the root is upstream's.
- `phpstan.neon.dist`: `paths` likewise. Upstream directories that our code calls into go under `scanDirectories`, so their symbols are known without their code being analyzed.
- `pint.json`: Pint inspects every `.php` file except `vendor/` by default, so list every upstream directory under `exclude` (and single files under `notPath`), and have the `pint` Composer script name our paths explicitly: `"pint": "pint our-dir another-dir"`. A bare `vendor/bin/pint` must not be able to touch upstream code.
- `phpcs.xml.dist`, where kept: `<file>` entries for our paths only.

Never reformat vendor code, and never let the reformat commit include it: if Pint's diff touches an upstream file, the paths are wrong. Where our modifications are edits inside upstream files rather than files of our own, the tools cannot be scoped to them; record that on the adoption ticket as the reason the tool cannot run, and what would let it (moving the modification into a file of our own, for example).

## Before a pull request

Run the three commands on the branch, and fix what they report before asking for review. The CI workflow runs them again on the pull request, and a reviewer should see that run's result for the commit under review.

A finding you disagree with is argued in the configuration, with a reason, scoped as narrowly as the tool allows. Never a blanket suppression.

- **Rector.** A rule whose suggestion is a judgment call rather than a mechanical one moves to `$sweepOnlyRules` in `rector.php` with a comment saying why, where it stays active under the sweep profile; the seven rules the template ships there (readonly class and property promotion, static-to-non-static demotion, two docblock removals, and `StrictArrayParamDimFetchRector` / `AddParamFromDimFetchKeyUseRector`, which turn bad input from a notice into a fatal) are the shared starting set. A rule that is wrong for one file is skipped for that file alone, `RuleClass::class => [__DIR__.'/path/File.php']` in `withSkip()`, with the reason above it. A rule is never dropped from a prepared set wholesale.
- **PHPStan and Larastan.** An `ignoreErrors` entry pinned by `identifier` and `path`, with `message` added when the path has more than one site, and a comment naming the cause (for WordPress code, the core function responsible). Never a message-only pattern over a namespace, and never `@phpstan-ignore` without a comment. `reportUnmatchedIgnoredErrors` defaults to true, so an entry whose error is fixed fails the run until it is removed; that is the point. An entry gets `reportUnmatched: false` only where whether it matches depends on the level rather than on the code, as the WordPress template's Pest entries do, with a comment saying so.
- **Pint.** A rule disagreement is a change to the shared `templates/pint.json` in UAMS-Web/uams-claude-skills, proposed there, because the preset and its rules are one style for every repository; a per-repository rule change is not an option. A file Pint must not touch (generated code, a fork's upstream) goes under `exclude` or `notPath`.

What the tools enforce in day-to-day code, and the conventions they encode, are in the [php-coding-standards skill](../php-coding-standards/SKILL.md).
