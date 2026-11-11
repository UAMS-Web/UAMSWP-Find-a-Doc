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
  or changing a static-analysis level, wiring a committed pre-commit hook into every clone from
  Composer, or writing a repository's code-quality adoption ticket.
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

The `.editorconfig` template pins the same style in the editor, so a save never produces a change that Pint or `eol=lf` then reverts: UTF-8, LF, four spaces for PHP and JSON, two for Markdown and YAML, a final newline and trimmed trailing whitespace, except in Markdown, where two trailing spaces are a line break. It carries no front-end rules; a repository with HTML, Vue or SCSS adds its own sections. UAMS-Web/uams-claude-skills checks that the template parses, sets `root = true`, and agrees with the `.gitattributes` and `pint.json` templates.

Pint's `laravel` preset is the one style across the organization's PHP, WordPress code included. Pint has no WordPress preset (its presets are `laravel`, `per`, `psr12`, `symfony` and `empty`), and a second style in WordPress code was rejected: `aether-wordpress-migration-api` already formats WordPress code with the `laravel` preset (its `docs/DECISIONS.md` D-014), and the alternatives, a custom WordPress-style configuration on the `empty` preset or Pint outside WordPress only, mean custom rules to maintain or two styles.

The templates beside this skill are the shared starting configurations. Each repository keeps its own copy with its own paths, PHP version and current static-analysis level.

| Template | Copy to | For |
|---|---|---|
| `templates/.gitattributes` | repository root (or merge its `* text=auto eol=lf` line into an existing file) | every repository |
| `templates/.editorconfig` | repository root (or merge its `[*]`, `[*.md]` and `[*.{yml,yaml}]` sections into an existing file, keeping its front-end sections) | every repository |
| `templates/rector.php.dist` and `templates/rector-sweep.php.dist` | repository root as `rector.php` / `rector-sweep.php` (drop `.dist`) | every repository |
| `templates/phpstan.neon.dist` | `phpstan.neon.dist` | PHP that is neither Laravel nor WordPress |
| `templates/phpstan-wordpress.neon.dist` | `phpstan.neon.dist` | WordPress plugins, must-use plugins and themes |
| `templates/phpstan-larastan.neon.dist` | `phpstan.neon.dist` | Laravel and Statamic applications |
| `templates/pint.json` | repository root | every repository |
| `templates/phpcs.xml.dist` | repository root | only a repository that keeps WordPressCS |
| `templates/.git-blame-ignore-revs` | repository root | a repository at its first reformat commit; omitted until one exists (see [The reformat commit](#the-reformat-commit)). Under a squash merge, the hash recorded is the squash commit's on the default branch, not the branch commit's |
| `templates/setup-git-hooks.php` | `scripts/setup-git-hooks.php` | a Composer repository that commits a `.githooks/pre-commit` |
| `templates/pre-commit` | `.githooks/pre-commit`, executable | a Composer repository that commits a pre-commit hook |

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

Pin `php-stubs/wordpress-stubs` to 6.x (`^6.8`, as `aether-wordpress-migration-api` does). A bare `composer require php-stubs/wordpress-stubs` can resolve 7.1, which breaks `szepeviktor/phpstan-wordpress` (and `php-stubs/wp-cli-stubs`, which also needs stubs 6.x). When the plugin or theme calls WP-CLI, require `php-stubs/wp-cli-stubs` and uncomment the `scanFiles` block in `templates/phpstan-wordpress.neon.dist`.

Current majors: Rector 2 and PHPStan 2 run on PHP 7.4 or later; Larastan 3 needs PHP 8.2 and Laravel 11.15 or later; Pint needs PHP 8.3 or later; `szepeviktor/phpstan-wordpress` 2.x needs PHPStan 2.x (all from the packages' `composer.json` and READMEs, 2026-10-02). A repository on PHPStan 1.x upgrades PHPStan and the WordPress extension together.

Do not pin `config.platform.php` to the supported floor to make Composer resolve for it: that stops the current Pint installing on a PHP 7.x or 8.2 plugin. The floor goes into `rector.php` (`withPhpVersion()`) and `phpstan.neon.dist` (`phpVersion`), which is what the tools read. A WordPress repository pins `config.platform.php` to the development PHP instead, the latest 8.4 patch (`8.4.27` today, raised on each 8.4 security release; never below `8.4.1`, which Pest 5 needs); that pin is the PHP the tools and tests run on, never the floor (the [`tests`](../tests/SKILL.md) skill, UAMS-Web/uams-claude-skills#124). A repository that already pins the platform for another reason, as `aether-wordpress-migration-api` does at `8.2.30` for its test toolchain (D-019), keeps that pin until its adoption ticket moves it; Rector and PHPStan both read it when nothing more specific is set.

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

- **Paths.** The directories and files holding first-party PHP, in `rector.php` (`withPaths()`, plus `withRootFiles()` for a plugin whose header file sits at the root), `phpstan.neon.dist` (`paths`) and, for a fork, `pint.json` (`exclude`). Include `tests/`: test bugs are real bugs, and the two repositories that analyze tests at the same level as source (uams-statamic, aether-wordpress-migration-api) both say so in their configs. Never a vendor directory. In a WordPress repository on the `tests` skill's layout (Pest for the Unit suite, and the Integration suite on wp-phpunit in its own Composer setup under `tests/Integration`), uncomment what `templates/phpstan-wordpress.neon.dist` marks for that case: the Pest extension, the `excludePaths` and `scanDirectories` blocks, and the two Pest entries, which sit commented inside the `ignoreErrors` list, so removing each line's leading `# ` is enough. Uncomment the `tests/Integration/vendor` skip in `rector.php` too. Without them PHPStan and Rector analyze the Integration suite's `vendor/` as the repository's own code, and PHPStan reports WordPress' test classes as undefined. Pint and the `phpcs.xml.dist` template already leave that `vendor/` alone. Measured on the `tests` skill's plugin template, 2026-10-07, Windows 11: with them, Pint, Rector, PHPStan at level 0 and phpcs each run clean.
- **PHP version.** `withPhpVersion(PhpVersion::PHP_xx)` in `rector.php` is the lowest supported version, and Rector skips any rule that needs a newer PHP; its order of precedence for that check is this call, then `composer.json`'s `php` require, then `config.platform.php`, then the running PHP (getrector.com/documentation/php-version-features, 2026-10-02). `withPhpSets()` with no argument, which applies every PHP upgrade set up to the floor and no further, does not read `withPhpVersion()`: it reads `composer.json`'s `require.php`, then `config.platform.php`, and fails the run when neither is set (Rector 2.6.7, `RectorConfigBuilder::withPhpSets()`, 2026-10-02). So `composer.json` carries `require.php` at the same floor, as the dev-only `composer.json` above does. `phpVersion` in `phpstan.neon.dist` is a range: `min` the same floor, `max` the developer's PHP. Left out, PHPStan infers one version from `composer.json` and does not report constructs that are unavailable at the floor; in `uamswp-migration-api` a typed class constant (PHP 8.3+) analyzed clean that way and would have been a parse error on the PHP 8.2 it ships to. The range form needs PHPStan 2.0 or later (phpstan.org/config-reference, 2026-10-02).
- **Level.** See "Finding the starting static-analysis level" below. The template ships at `0`.

Add the cache directory the template names to `.gitignore` (`/.phpstan/`, or `/storage/phpstan` for the Larastan template). The `tmpDir` comment in each template explains why the cache is checkout-scoped; keep it.

The shared `templates/pint.json` turns on `declare_strict_types`. That is not only a formatting choice: after the first full Pint run, files gain `declare(strict_types=1);`, and built-in PHP functions then reject bad argument types with `TypeError` instead of coercing. PHPStan will also start reporting untyped arguments passed to those built-ins (often a large set at higher levels). Adopters must know this before the first repository-wide Pint run; budget for the runtime and analyzer fallout in the adoption ticket, or temporarily leave the rule off in that repository's `pint.json` only with a recorded reason (and expect the shared template to keep it on).

For Laravel and Statamic, uncomment the `withComposerBased()` call in `rector.php` (Rector 2.6.5 or later; it no longer needs a `withSetProviders()` line, and a repository on Rector 2.6.4 or earlier keeps that line). For WordPress, uncomment the `\Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector::class` line in the sweep-only list of `rector.php`. It is fully qualified, so it needs no `use`; a short name without one makes Rector stop with `These rules from "$rectorConfig->skip()" do not exist`. WordPress matches hook callbacks by identity, so a first-class callable silently breaks `remove_action()` (D-016).

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

`stan`, `refactor`, `refactor:sweep`, `test:refactor` and `test:refactor:sweep` are the names uams-statamic and aether-wordpress-migration-api already use. `refactor` applies the gating profile, `test:refactor` previews it; the `:sweep` pair does the same for the sweep profile, which is run deliberately and reviewed, never as the gate. `pint` formats; `test:pint` is the check the runner uses. While working, run `vendor/bin/pint --dirty` on what you touched, not `--test`.

Rector often needs more than one apply pass before a dry-run is clean: after `composer refactor` (or `refactor:sweep`), run the matching `test:refactor` / `test:refactor:sweep` again and repeat apply until the dry-run reports no further changes.

The gating and sweep profiles share one Rector cache. A dry-run of one profile right after the other can look like a no-op unless the cache is cleared. The `:sweep` scripts above pass `--clear-cache`; when switching the other way (sweep, then gate), run the gating command with `--clear-cache` as well (for example `vendor/bin/rector --clear-cache --dry-run`), or clear once before the first run of the new profile.

### Git hooks

A hook that each developer has to wire by hand is not a gate, as the `worktrees` rule says of its refresh gate. The two hook templates make the commit-time gate active in every clone that runs `composer install`, and in every linked worktree of it, because `core.hooksPath` lives in the clone's shared configuration (UAMS-Web/uams-claude-skills#162).

Copy `templates/setup-git-hooks.php` to `scripts/setup-git-hooks.php` and `templates/pre-commit` to `.githooks/pre-commit`, make the hook executable (`chmod +x .githooks/pre-commit`; on Windows, `git update-index --chmod=+x .githooks/pre-commit` after staging it), commit both, and add the installer to the `scripts` section of `composer.json`:

```json
"scripts": {
    "post-install-cmd": [
        "@php scripts/setup-git-hooks.php"
    ],
    "post-update-cmd": [
        "@php scripts/setup-git-hooks.php"
    ]
}
```

Where a repository already has either key, add the one line to the end of its array. Both are Composer's own events, so no other script entry is needed.

**What the installer does.** It runs `git config core.hooksPath .githooks` for the project the script sits in, and prints one line when it changed the setting. It is quiet when the setting is already right, and it names the old value when it replaces another one. It never fails a Composer run: the exit status is always 0, and the only output it can add besides that line is a one-line warning on stderr when git refused the write. It does nothing in four cases, each of which is a clone where the setting would be wrong or pointless:

- the project is not in a git work tree (a dist or tarball install);
- the project is a subdirectory of another repository, since the setting would land in that repository's configuration;
- `.githooks/pre-commit` is not tracked, so there is no hook to run;
- the run is under GitHub Actions, where nobody commits from the checkout.

**It agrees with the local CI runner on the command and the path.** The `local-ci` skill's runner prints one warning line when `.githooks/pre-commit` is tracked and `core.hooksPath` does not resolve to `.githooks`, and the line names `git config core.hooksPath .githooks`. The installer runs that command, on that path, under that same condition (a tracked hook, outside GitHub Actions), so a clone that ran `composer install` is a clone the warning stays silent for. A change to either side changes the other: do not rename the directory or the hook file in one place. `composer install --no-scripts` skips the installer, and the warning is what catches that clone.

**What the hook does.** The generic gate runs `composer validate --no-check-publish --no-check-all` (the chain's `validate` job runs the same flags) when `composer.json` or `composer.lock` is staged at the repository root, and refuses the commit when it fails; a lock file that no longer matches `composer.json` is the usual cause. It warns, and lets the commit through, when `composer` is not on `PATH`. Every gate runs even after one fails, so one commit attempt reports every problem. The hook reads the working tree, as the commands it runs do.

**Repository gates** go in the marked place near the end of the hook, one block each, setting `status=1` instead of exiting so the blocks after it still run. Keep a gate there only if it reads the index and is quick, since the hook runs on every commit; a gate that runs the test suite belongs in the local CI runner. A gate that only one repository needs (keeping two mirrored files changing together, for one) stays in that repository's copy of the hook and is not proposed for the template.

**When the repository also uses the `local-ci` skill's pre-commit guard against secret and personal-data files,** both want `.githooks/pre-commit`. Keep this template's hook there, save the guard's hook as `.githooks/guard-deny`, make it executable, and call it from a block of its own, as the comment at the end of the template shows. The guard reads `local-ci.json` from the working tree root, which is where hooks run.

`git commit --no-verify` skips every gate, and a missing `vendor/` is not a reason to: bootstrap the clone instead, as the `worktrees` rule says.

### Local CI jobs

Three jobs, one per tool, run before a pull request where remote CI does not run them (see *Before a pull request*):

| Job | Command | Gated by |
|---|---|---|
| `pint` | `vendor/bin/pint --test` | any `.php` in the diff |
| `rector` | `vendor/bin/rector --dry-run` (the gating profile) | any `.php` under the paths `rector.php` names |
| `phpstan` | `composer stan` | any `.php` in the diff, tests included, because `phpstan.neon.dist` lists `tests` |

This repository is public, so Actions minutes are free: run the three commands as steps of its CI workflow, and locally before pushing. A private repository without the local-ci runner yet does the same three commands locally before every push (or as workflow steps if minutes are acceptable); adopt local-ci when that skill is available in the checkout so gating stops depending on Actions.

### The CI workflow template

`templates/ci.yml` is the GitHub Actions form of the gate, with `templates/setup-action.yml` as the composite action it shares. It is a caller (UAMS-Web/uams-claude-skills#486): one job, `checks`, calls the shared workflow `UAMS-Web/uams-ci-workflows/.github/workflows/ci.yml` pinned to a release tag (`vX.Y.Z`) or a full commit hash, never a branch, and the `CI` job needs it with `if: always()`. The job chain itself lives in the shared workflow, so a change to it reaches a repository by moving the pin. Copy the two templates once, the first to `.github/workflows/ci.yml` and the second to `.github/actions/setup/action.yml`, set the PHP version in the action, and set the inputs the repository needs on the call (see *Using the reusable workflow* below). The sync never writes `.github/`, so an existing `ci.yml` is never overwritten: a repository that already has one keeps it, and adopts the template's properties (below) by editing its own file. A ruleset requires the aggregate `CI` job rather than the individual jobs (see *Requiring the checks* below), so a job can be renamed or added without touching the ruleset, as long as it stays in the aggregate's `needs:`.

What the template sets, and what to keep when adapting it:

- In the shared workflow, `composer-validate` and `synced-files` run first and cheapest; `pint` follows them, `rector` and `phpstan` follow `pint`, and `pest` follows both, so a pull request that fails a cheap check does not spend minutes on the dear ones. The `synced-files` job runs the `synced-files` skill's check, and is the workflow form of the `synced-files` job in `local-ci.json`. The `no-emoji` job stands outside that chain on purpose: it runs the `writing-comments` skill's `check-no-emoji.mjs` with Node alone, so it can be dropped or moved without touching another job.
- `timeout-minutes` on every job the workflow runs itself (GitHub rejects the key on a job that calls a workflow, so the `checks` call carries none), so a hung step is cut off rather than billed until the platform limit.
- `concurrency` keyed on the workflow and ref for a pull request and on the workflow and commit for every other run, cancelling in-progress runs for pull requests only, so a push replaces a stale pull request run and no `main` run is cancelled, not even by the next merge (a shared push group would cancel the pending run, #473).
- PHP, the Composer cache and the locked install live in the composite action, which `dependabot.yml` also covers (see the `update-dependencies-ticket` skill's `templates/dependabot.yml`).

- The `mutate` job runs the mutation score for a repository that meets the Full coverage bar. It is the one job that installs a coverage driver: it passes `coverage: pcov` to the composite action, whose `coverage` input defaults to `none` so every other job keeps the faster, driver-free PHP. See *Adopting the `mutate` job* below.

#### Which jobs run on which operating system

The shared workflow (`templates/ci-reusable.yml`, published as `UAMS-Web/uams-ci-workflows`) runs a job as a matrix over `ubuntu-latest`, `windows-latest` and `macos-latest` only when its result can depend on the platform, and on `ubuntu-latest` alone otherwise (UAMS-Web/uams-claude-skills#438, #454). The legs exist to catch what one operating system shows: path handling, command-line length limits, BSD tools, case-insensitive file systems, line endings. A job that reads the same files the same way everywhere gains nothing from running three times, and on the `uams-statamic` pilot (run 41440485266, pull request 514) the platform-independent job types cost about 1,065 seconds of Windows and macOS runner time per run.

**The rule: a job with no recorded evidence runs on all three.** Narrowing needs a reason on the record, and `scripts/check-templates.mjs` in the source repository fails the shared workflow's template when a job is unclassified, runs on the wrong legs, or is a matrix job without `fail-fast: false`. A matrix job reports one result that is `success` only when every leg succeeded, so `CI` needs it once and a failed leg still fails the aggregate. Each job in the shared workflow carries a `# Legs:` line with its reason, so that workflow is the one place the classification lives; a caller's `ci.yml` holds no table of its own.

| Job type | Legs | Reason or evidence |
| --- | --- | --- |
| `composer-validate` (`composer validate`) | Linux only | Compares `composer.json` and `composer.lock`; no platform input. |
| `synced-files` | Linux only | Hashes tracked files against the sync manifest; the repository pins line endings (`* text=auto eol=lf`), so the bytes match on every platform. |
| `no-emoji` | Linux only | Sweeps tracked prose for banned code points with Node alone. `check-no-emoji.mjs` has no platform branch. |
| `aggregate-needs` | Linux only | Parses the workflow files and the allow-list. `check-aggregate-needs.mjs` has no platform branch. |
| `pint`, `rector`, `phpstan` | Linux only | They read PHP source and their own configuration. |
| Spell check, submodule pointer guard, test isolation guard (pilot job types) | Linux only | Decided 2026-11-02: they read the same files the same way on any platform. |
| Browser smoke (`CP browser smoke (Pest + Playwright)`, pilot job type) | Linux only | Decided 2026-11-02: no test under `tests/Browser` branches on the operating system. |
| `guard`, `dev-lint` (the optional repository jobs, UAMS-Web/uams-claude-skills#620) | Linux only | Decided on #620 from what each job reads, with no seeded fault run: the guard matches the names `git ls-files` prints against patterns, and `dev-lint` runs `php -l` over source under one PHP version. A caller that wants a Windows or macOS leg names the job in `all-platform-jobs`. |
| `floor-lint`, `download-archive`, `phpcs` (the plugin jobs, UAMS-Web/uams-claude-skills#607) | Linux only | Decided on #607 from what each job reads, with no seeded fault run: the floor lint parses shipped PHP under one pinned PHP version, the download check runs `git archive` on `HEAD` over a repository that pins line endings, and phpcs reads PHP source and its own ruleset. The plugin template ran all three on `ubuntu-latest` only before the chain carried them, so no coverage is lost. A caller that wants a Windows or macOS leg names the job in `all-platform-jobs`. |
| `pest` (the suite, and the pilot's parallel band shards and serial group) | All three | The suite is what runs on the platforms the code ships to. |
| `mutate-ignore` | All three | `check-mutate-ignore.php` has a branch for a CRLF runtime (its docblock names a trailing marker on one), and a Windows checkout can be CRLF. |
| `mutate` | Ubuntu only (pinned) | Mutation tooling has needed Windows-only patches in `uams-statamic`, and the PCOV driver is installed per platform. The job is pinned to `ubuntu-latest` whatever the inputs say, in the reusable workflow and in the classification `scripts/check-templates.mjs` holds for any `ci.yml`-shaped chain (UAMS-Web/uams-ci-workflows#13, UAMS-Web/uams-claude-skills#549): on 2026-11-05 a Windows leg scored 100.00% over mutants that `ubuntu-latest` and `macos-latest` reported untested, and the tests do not detect them on any platform. |

The four pilot job types that needed a closer look were each checked with a seeded fault in `UAMS-Web/uams-statamic` (UAMS-Web/uams-statamic#551, recorded on UAMS-Web/uams-claude-skills#478): one fault per job on a throwaway branch, one control on a branch with the same tree as `main`, each run read in full (62 of 62 jobs per run). Every faulted job failed on its fault on all three platforms and passed in the control. That is the ticket's test for platform independence, and it decides them as follows:

| Job type | Legs | Evidence |
| --- | --- | --- |
| Encoding guard (Windows-1252 double-encoding) | Linux only | Failed on its seeded sample on all three platforms, passed in the control. Only the first of its six steps was faulted; the other five were not exercised. |
| Vite build | Linux only | An unresolvable import failed the build on all three platforms. |
| JS unit tests (Vitest) | All three | The verdict matched, but the suite is platform-gated: Windows ran `1277 passed, 10 skipped`, Linux and macOS `1282 passed, 5 skipped`. A defect in a Windows-only branch fails only on Windows. |
| Mutation tooling smoke | All three | The verdict matched and a mutant child launched and was scored on Windows too (`8 Mutations for 1 Files created`, `87.50%` on all three). The tool's past defects were Windows-only, so one seeded fault does not cover it. |

Per-leg wall-clock time of those jobs in the control run (`uams-statamic`, run 41666638111, one run per cell, seconds):

| Job type | `ubuntu-latest` | `windows-latest` | `macos-latest` |
| --- | --- | --- | --- |
| Encoding guard | 319 | 485 | 341 |
| Vite build | 36 | 160 | 77 |
| JS unit tests (Vitest) | 48 | 132 | 82 |
| Mutation tooling smoke | 34 | 120 | 53 |

Narrowing the encoding guard and the Vite build to Linux removes 826 and 237 seconds of Windows and macOS runner time per run (485 + 341, and 160 + 77), 1,063 seconds in all. The mutation smoke and Vitest keep their three legs. The 1,065-second figure above is a different measurement, from the pilot run named there.

**Measured on the narrowed template (UAMS-Web/uams-claude-skills#477).** `uams-statamic` pull request 623, a scratch branch since closed, switched its own encoding guard and Vite build to the shared workflow's `encoding-guard` and `vite-build` inputs (the six steps of the old encoding job as `encoding-guard-command`, `npm run build && npm run cp:build` as `vite-build-command`, `npm-version: ^11.10.0`). Run 42417275597 (head `3f33b811b`, branched from `main` at `9ec032320`, one run) gives the runner time of each leg in seconds, from each job's `started_at` and `completed_at`:

| Job type | `ubuntu-latest` | `windows-latest` | `macos-latest` |
| --- | --- | --- | --- |
| Encoding guard | 156 | not run | not run |
| Vite build | 41 | not run | not run |

The two job types took 197 seconds of Linux runner time and no Windows or macOS time. The control run above (41666638111, the same two job types on all three legs) took 1,418 seconds: 355 on Linux and 1,063 on Windows and macOS. The saving is the 1,063 seconds of Windows and macOS runner time, and the Linux time falls from 355 to 197 seconds; the 1,065-second pilot figure is not re-derived, because its job set was not re-run. Parity basis: same repository and the same commands, but a later commit base than the control run, and the template's `.github/actions/setup` in place of the old jobs' inline setup steps, so the Linux difference mixes both and is not a measure of the narrowing. One run per figure; the spread between runs is not measured. That run's `CI` job failed, on the repository's own `local-ci.chain.test.mjs` (it checks the job list in `ci.yml`, which the scratch branch changed) and so on the Vitest job on all three legs, not on a narrowed job; those Vitest times are not used here.

**Wall-clock time of each leg in the first all-platform chain (UAMS-Web/uams-statamic#348, merged as pull request 494).** Before any job was narrowed, every job of the `uams-statamic` chain except `submodule-access` and the aggregate ran on all three legs. Run 41372088216 (head `ea8025f3c`, 59 of 59 jobs `success`) gives the longest job on each leg, in minutes, from each job's `started_at` and `completed_at`:

| Leg | Longest job | Minutes |
| --- | --- | --- |
| `ubuntu-latest` | CP browser smoke | 13.9 |
| `macos-latest` | CP browser smoke | 13.0 |
| `windows-latest` | Pest band, whole | 58.6 |
| Whole workflow | | 59.4 |

The Windows leg bound the run: its Pest band ran whole in one job (3,515 seconds), because `pest --shard` puts the class list on each worker's command line, past the Windows limit. Windows also took 1,725 seconds for the browser smoke against 836 on Linux and 777 on macOS. The same Windows band took 71 minutes (4,261 seconds) on run 41364357633, which failed on a Windows-only defect, so it is the upper figure for a Windows band that did not finish clean. One run per figure; the spread between runs is not measured. A caller deciding whether to keep the Windows leg of `pest` reads this as the cost: a pull request waits about an hour for Windows against about 14 minutes on Linux and macOS.

**Keeping or narrowing a job in a repository.** A repository keeps a Linux-only job on all three legs with the `all-platform-jobs` input, gives a job its Windows leg without a macOS leg with the `windows-jobs` input (UAMS-Web/uams-claude-skills#529), or narrows every job with `linux-only`, on its call of the shared workflow. `ci.yml` is copied once and then owned by the repository, and the sync does not write `.github/workflows/ci.yml`, so there is no manifest key for it and nothing to leave alone.

**Using the reusable workflow.** `templates/ci-reusable.yml` is the job chain as a `workflow_call` workflow (UAMS-Web/uams-claude-skills#485), which the `templates/ci.yml` caller calls (#486). It is published in `UAMS-Web/uams-ci-workflows` as `.github/workflows/ci.yml` so that a public repository can call it. The caller job is named `checks`, and the `CI` job needs it with `if: always()`, so the required context stays `CI`; `scripts/check-templates.mjs` fails the caller when the call is missing, when `CI` does not need it, or when the pinned reference is not a release tag or a full commit hash. The inputs `linux-only` (all jobs on Linux), `all-platform-jobs` (space-separated ids kept on all three) and `windows-jobs` (space-separated ids on Linux and Windows with no macOS leg; a job in both lists keeps all three) set the legs (never the `mutate` job's: it runs on `ubuntu-latest` only), and `encoding-guard`, `vitest`, `vite-build` and `mutation-smoke` switch the four optional jobs on, each with a command input; `floor-lint`, `download-archive` and `phpcs` switch on the three jobs a WordPress plugin runs (see *A WordPress plugin caller* below), and `pest-command` names the command the `pest` job runs. The `mutate` job mutates only the changed PHP files on a pull request and on a push, and everything `composer mutate` covers on a `schedule` or `workflow_dispatch` run, which takes the longer `mutate-full-timeout-minutes` limit (default 180, against 45 for the others); a caller adds the schedule to its own `ci.yml` (UAMS-Web/uams-claude-skills#532). The `mutate-ignore` job scans whichever of `app`, `src` and `tests` exist unless the caller names paths in `mutate-ignore-paths`, which a WordPress plugin sets to its source directory (#534) and a theme sets to its root files, since a path may be a file and a walk skips a nested `vendor/` (#627); it runs only when `mutate` is on, and `mutate: false` skips it with the `mutate` job, so a result with `mutate` off accepts a skipped `mutate-ignore` (#612). A called job reports as `<caller job id> / <job name> (<os>)`. The caller must carry `.github/actions/setup`, the synced `.claude/skills` scripts and the Composer scripts the jobs run, because every job checks out the caller's repository. `ci-reusable.yml` here is the canonical source and the published file is a byte copy.

**A WordPress plugin caller (UAMS-Web/uams-claude-skills#607).** A plugin calls the same chain instead of keeping a copy of it. Its `with:` block is `linux-only: true`, `mutate: false` (the plugin template has no mutation run; a plugin whose source a mutator changes leaves it on), `floor-lint: true` with `floor-lint-php` set to the supported floor (default `8.2`) and `floor-lint-command` where the script is not `php scripts/lint-floor.php`, `download-archive: true` (default command `node scripts/check-download-archive.mjs`) and `phpcs: true` (default command `composer test:phpcs`). A plugin whose unit suite is its own Composer script sets `pest-command: composer test:unit`. With the default `composer test` the `pest` job writes a JUnit report and runs the skip-baseline and slowest-class checks that read it; with any other command it runs the command as given and those two checks are skipped, since no report exists. `mutate-ignore-paths` names the plugin's source directory (`includes`). A plugin that does not receive the `pest-testing` skill passes `mutate: false`, which skips the `mutate-ignore` job, and `global-test-symbols: false` (default `true`), which skips the `pest` job's Global test symbols step and runs the rest of that job; both steps run scripts under `.claude/skills/pest-testing/` (#612). **The WordPress integration matrix stays in the caller as a plain job beside the call**: it needs a MySQL service, a WordPress download and a single and multisite matrix, which this chain does not run (out of scope on #607). The caller lists it in the `needs:` of its `CI` job next to `checks`, as `templates/ci.yml` allows, so `CI` fails when it does. **Two more optional jobs (UAMS-Web/uams-claude-skills#620).** `guard: true` runs the `guard` job, which fails, naming each path, when `git ls-files` lists a file matching `wp-config.php`, `.sql`, `.sql.gz`, `.csv`, `.tsv`, `.xlsx`, `.xls`, `.env`, `.pem` or `.key`; `guard-patterns` adds extended regular expressions (separated by spaces or newlines) to those and never replaces them, so a repository with its own secret file name passes it there. `dev-lint: true` runs the `dev-lint` job, which runs `php -l` on every PHP file under the development PHP (the minor of `config.platform.php`, or 8.4 when it names none), skipping `vendor/` and `node_modules/` always and the space-separated paths in `dev-lint-exclude` (relative to the repository root) besides; `floor-lint` covers only the files the download holds, on the supported floor. Both are off until their input is `true`, are listed in the `result` job like the plugin jobs, and fail rather than pass when they find nothing to check (no tracked files, no PHP file) or when a pattern cannot be read. A plugin that ran its own copy of either drops it on adopting the caller.

The three plugin jobs are off until their input is `true`, are each listed in the `result` job's `needs:` and switched-off list, and a skipped one passes `result` only when its input turned it off, so a failing command in a switched-on job fails the chain. The three command inputs fail on an empty value rather than passing.

**Keeping the published copy identical (UAMS-Web/uams-claude-skills#490).** The published copy is `.github/workflows/ci.yml` in `UAMS-Web/uams-ci-workflows`, and a caller runs it at the release tag or commit its `uses:` line pins. Edit in this order: change `templates/ci-reusable.yml` here and merge it, copy the merged file byte for byte to the published repository, merge that, tag it, then move the pin in the callers. `scripts/check-published-workflow.mjs` compares the two. Run it from a checkout that carries the skill:

```bash
node .claude/skills/code-quality/scripts/check-published-workflow.mjs
```

It compares bytes, so a byte order mark or a changed line ending is a difference. It takes the ref from `--ref <tag-or-commit>`, or from the pin in `templates/ci.yml` when that is a release tag or a full commit hash (a caller pinned to a branch is refused). It fetches the published file from `raw.githubusercontent.com` (no token, because the repository is public) and exits 0 when the two are identical, 1 when they differ, naming both files and the first differing line, and 2 when it cannot run: a control failed, the ref, repository or path could not be used in the address, the pin or the published file cannot be read, or the answer is empty. Run it twice in the order of the edits. After the published copy merges and before tagging, pass `--ref <merge-commit>` (the pin still names the old tag, whose file differs from the new template). After the pin moves, run it with no arguments, so it checks the file the callers now run. A branch ref is served from a cache for a few minutes, so a run right after a merge may read the old file; use the commit. `--published-file <path>` compares against a local clone of the published repository instead.

**Probe run.** A throwaway caller in `UAMS-Web/statamic-addon-template` (branch `485-probe-reusable-ci`, run 41675483937, 36 of 36 jobs read) called the workflow once with default inputs and once with `linux-only: true`. With defaults, the jobs classified for all three ran on `ubuntu-latest`, `windows-latest` and `macos-latest` (`default / Pest (windows-latest)`, `default / Mutation annotations (none inert) (macos-latest)`) and the Linux-class jobs ran on `ubuntu-latest` only. With `linux-only: true`, every job ran on `ubuntu-latest` only. The four optional jobs reported `skipped` in both, and a skipped optional job shows its name unresolved as `(${{ matrix.os }})`. Some jobs failed in that repository for reasons of its own (no `CI` job for `Aggregate needs`, a test-symbol check in Pest, a lock file Composer rejected on Windows), and `Chain result` failed with them, as it must. The wall-clock figures from that run are therefore not leg timings for a full suite; the control-run table above is.

#### Requiring the checks

`templates/required-checks-ruleset.json` is the ruleset that makes the CI workflow a merge gate. It requires exactly three status check contexts, the same in every repository: `CI` (the aggregate job), `Attribution footer` and `Closing keywords` (the two pull-request-body workflows from the `writing-pull-requests` skill). It holds no per-repository list, so nothing in it changes when a job is renamed. It carries only the `required_status_checks` rule and applies to the default branch of the one repository it is posted to; rulesets layer, so a pull request requirement or a deletion block stays with the ruleset that already holds it.

`scripts/apply-required-checks-ruleset.mjs` applies it and reads the result back:

```bash
node .claude/skills/code-quality/scripts/apply-required-checks-ruleset.mjs <owner>/<repo>            # reads the effective rules and prints the plan
node .claude/skills/code-quality/scripts/apply-required-checks-ruleset.mjs <owner>/<repo> --apply    # posts the ruleset, then reads rules/branches/main back
```

Applying a ruleset is a settings change, so the default run only reads. With `--apply` the script POSTs the template to `repos/<owner>/<repo>/rulesets`, reads `repos/<owner>/<repo>/rules/branches/main`, and exits 1 unless `required_status_checks` now names all three contexts. It lists any other context a different ruleset also requires, because every layered ruleset must pass. Both calls are REST. Repository administration is enough; the organization form of a ruleset (one that lists repositories by id) needs the `admin:org` scope, which the operator adds with `gh auth refresh -h github.com -s admin:org`. Without it, `GET orgs/{org}/rulesets` answers 404 with a message naming the scope, which reads like a missing ruleset.

Apply it only after the workflows have run green once on the default branch, so the three names exist as check runs (`gh api repos/<owner>/<repo>/commits/<sha>/check-runs --jq '.check_runs[].name'`). A required context no job reports leaves every pull request waiting. The `pre-merge-check` rule covers reading the rules before and after, and the check that a required check actually blocks: a pull request with a required check red reads `mergeable_state` `blocked`.

`scripts/check-aggregate-needs.mjs` is the check that the aggregate stays complete. The `aggregate-needs` job of the shared workflow runs it on every pull request: it fails, naming the job, when a job in the workflow is missing from the `needs:` list of `CI`, when a `needs:` entry names no job, or when one of the three fixed names is reported by no job. A job that is deliberately not aggregated is listed with a reason in `.github/aggregate-needs-allow.json`. It reads workflow files only, so it needs no token able to read rules.

A repository already inside a shared organization ruleset that requires per-job names keeps that ruleset until it migrates; applying this one adds three requirements on top of those names rather than replacing them. The names stop being needed once the repository leaves the shared ruleset's list, which is an organization-level edit.

A spell-check job is not in the template. The `spell-check` skill (profile `spell`) ships the `cspell.json` and `project-words.txt` seeds, the `spell` script and a `spell` job snippet for a repository that wants one.

#### Adopting the `mutate` job

The job is for a repository that has Pest, the patched `pest-plugin-mutate` and a `mutate` Composer script (a Statamic add-on's is `pest --mutate --path=src`). The shared workflow has no input that switches the job off, so a repository without them is not yet served by the caller; a switch is a follow-up to this slice.

1. The `mutate` job is part of the shared workflow, so it is not copied. Copy the composite action's `coverage` input into `.github/actions/setup/action.yml`. An existing `.github/actions/setup/action.yml` gains the input by editing it; the default stays `none`, so the other jobs are unchanged.
2. The shared workflow's job keeps `coverage: pcov` on its setup step. `--mutate` needs a coverage driver, and without one a run either stops with `Mutation testing requires code coverage to be enabled` or reports `0 Mutations for 0 Files created`; the [`pcov-setup`](../pcov-setup/SKILL.md) skill covers both and the per-machine install for a local run. PCOV is the driver here because it is purpose-built for coverage and much faster than Xdebug.
3. Set the `mutate-paths` input on the call of the shared workflow (it feeds `MUTATE_PATHS` on the *Select the files to mutate* step) to the paths the `mutate` script passes to `--path` (`src` for a Statamic add-on), space-separated.
   - **On a pull request**, the step lists the PHP files under those paths that the pull request adds, changes or renames. It reads them from GitHub's merge commit against its first parent, which is why the checkout fetches two commits, so a later change on the base branch is not counted. The run step passes the list as `--path`, which replaces the script's own. A pull request that changes no such file skips the run and the check, and the job passes with the reason printed.
   - **On a push**, the step selects everything and the job runs the full score.
4. The job's run step and check step stay as the shared workflow has them. The run step runs `composer mutate -- --min=100`, with `--path` on a pull request, and keeps the output in `mutate.log`; Pest fails the step on any survivor. The check step runs the `pest-testing` skill's `check-mutation-run.php` against that log, and fails a run that created no mutants, or that left any mutant untested, uncovered, pending or timed out, even when the run step passed. It runs with `if: always()` unless nothing was selected, so a failed run step still prints the log's own account.

   One case passes with zero mutants: changed files that, mutated with PCOV loaded, produce none, such as an interface. Those files exist in the checkout and the coverage driver is there, which rules out the two setup failures zero mutants otherwise means, so the run step passes `--ignore-min-score-on-zero-mutations` on changed files and the check step accepts the zero with the reason printed. Zero mutants on a full run, or without PCOV, still fails.
5. Fix a failure the way the `pest-testing` skill's mutation section describes: a survivor is killed with a test, deleted as dead code, or annotated with `@pest-mutate-ignore:<Mutator>` and a reason on the line above. An annotation that suppresses nothing, or everything, is the `mutate-ignore` job's business.
6. A repository that gates its Actions jobs on `RUN_REMOTE_CI` adds the `if:` to the `checks` call, which gates this job with the rest. It is the slowest job in the chain, hence its 45-minute timeout; raise it when a suite needs longer, and do not remove it.

   **Measure it before relying on it.** Every run first runs the whole suite under PCOV for coverage, then runs tests once per mutant, so the time grows with the suite as well as with the change. On `UAMS-Web/aether-wordpress-importer` (UAMS-Web/uams-claude-skills#406, 10,257 tests), one standard runner did not finish a pull request that changed one file:
   - the coverage pass took 8.3 minutes and created 324 mutants;
   - the 45-minute limit stopped it with 302 done;
   - with a 120-minute limit, the runner lost contact at 47 minutes.

   Pest's `--parallel` ran a different test workload there, so it is no remedy. A repository that large keeps its mutation run local until it has a runner that fits.

The aggregate `CI` job lists `mutate` in its `needs:` like every other job, so the ruleset needs no entry for it.

#### Adding the changed-tests report

The `pest-testing` skill's `scripts/changed-tests-against-base.mjs` runs a pull request's changed test files against the base branch's source and reports per file (that skill's *Changed tests against base* says what the report means). It is not in the template: a repository adopts it in its own change. In `ci.yml`, beside the `checks` call, add a plain job such as the one below and list it in the `needs:` of `CI` (the `aggregate-needs` check fails otherwise):

```yaml
  changed-tests:
    name: Changed tests against base
    runs-on: ubuntu-latest
    timeout-minutes: 20
    steps:
      - uses: actions/checkout@v7
        with:
          fetch-depth: 0
      - uses: ./.github/actions/setup
      - name: Changed tests against base
        run: node .claude/skills/pest-testing/scripts/changed-tests-against-base.mjs
```

- `fetch-depth: 0` is what makes the base branch present; without it the script fails, naming that cause.
- **No `if:` on the job.** It reads `GITHUB_BASE_REF` and `GITHUB_EVENT_NAME` itself and reports a push as nothing to compare, so it succeeds on `main`. A job skipped by `if:` would count as a failure in the aggregate.
- **No `${{ }}` expression in `run:`.** The base branch's name reaches the script through the environment, not through the command.
- The job goes in the aggregate `CI` job's `needs:` like every other job. It always succeeds once its report is written, so it gates nothing but a comparison that could not run.
- The report is written to the job summary as well as the log.

In `local-ci.json`, where the runner gates jobs on the diff:

```json
{ "name": "changed-tests", "run": "node .claude/skills/pest-testing/scripts/changed-tests-against-base.mjs --base origin/main", "paths": ["tests/**"] }
```

`--base` names the same branch as `local-ci.json`'s `base`. The runner passes the PHP it resolved as `LOCAL_CI_PHP`, which the script uses. It installs dependencies in a scratch worktree on each run, so it is not quick: give it `paths` rather than `always`.

## Finding the starting static-analysis level

Each repository adopts Larastan or PHPStan at the highest level its code passes today, then rises one level per change until it reaches the shared target, which is `max`.

1. With `level: 0` in `phpstan.neon.dist`, run `composer stan`. Fix what it reports if there is little; otherwise note the count.
2. Raise `level` by one and run again. Repeat until a level reports errors you are not fixing in this change.
3. Set `level` to the last level that passed, and commit the configuration. That is the gate from now on.
4. Each later change may raise the level by one once the findings at the next level are fixed. Raise it in the same pull request as the fixes, never ahead of them.

When finding that starting level, do not count on `ignoreErrors` entries that paper over existing debt (identifier/path pins added so a higher level looks green). The starting level is the highest level the code passes with only the ignores the template already documents for harness noise. The WordPress template's Pest entries (`reportUnmatched: false`, scoped to `tests/`) are that kind of harness noise and may stay uncommented when the suite needs them; they do not invent a higher starting level. New pins for real code debt land only after the starting level is set, each with a comment naming the cause.

Where the three configured repositories stand (from their `phpstan.neon.dist`, 2026-10-02): `uams-statamic` is at `level: 10`, climbing one level per commit toward `max` (UAMS-Web/uams-statamic-archive#914); `aether-wordpress-importer` is at `max`; `aether-wordpress-migration-api` opened at `max` with `phpVersion` 8.2 to 8.4, because it was a thousand lines old when the analyzer arrived and had no debt to pay down. PHPStan's levels run 0 to 10, and `max` is an alias for the highest, so a repository at `max` picks up the next level automatically when PHPStan adds one (phpstan.org/user-guide/rule-levels, 2026-10-02).

A baseline file, which PHPStan offers as the other way to adopt a high level by recording every existing error and suppressing it, is not used. Issue #10 weighed it against starting low and rising and chose the latter: with a baseline the level in the configuration says nothing about the code, since the suppressed list can hold any number of errors at that level, and that list is a second backlog to drain with no step at which the whole codebase is known to pass. Starting at the level the code passes means every level the configuration has ever named is one the entire codebase passed. The other alternative, fixing every finding before adopting, delays adoption until the whole codebase is clean.

## The reformat commit

The first Pint run over a repository touches most files. Land it as one commit holding only Pint's changes, so `git blame` can skip it:

**A repository that adopts Pint at creation has no reformat commit, and omits `.git-blame-ignore-revs` until it has one.** Its code is formatted on the way in, so step 4 below has no hash to record, and the template's `PLACEHOLDER` line cannot be filled honestly. The file is created at the first real bulk reformat: a later Pint upgrade or rule change that rewrites existing code. Measured in `UAMS-Web/statamic-multisite-forms` on 2026-10-12: one commit created every PHP file with the tooling in place, and no reformat-only commit exists. The template as shipped is comment lines only, which `git blame` accepts (a comment-only `blame.ignoreRevsFile` exits 0 on git 2.32, Windows), but a non-comment placeholder line fails with `fatal: invalid object name`, so do not commit the file with a placeholder entry.

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

   Limits of this step: Herd may not ship an older binary (for example no `php74` on a machine that only has 8.x). A newer binary's `php -l` (such as `php82 -l` on a PHP 7.4 plugin) is not a substitute: it accepts syntax the floor rejects and rejects nothing the floor would accept for 7.x-only parse issues. When the floor binary is missing locally, parse-check on a Docker image or CI matrix job that runs that PHP version, or on any environment that still has the supported floor installed. Skipping the floor parse check is not an option for a repository that still claims that floor. A WordPress repository on the shared runner has this as a standing job, `lint-floor` (the [`tests`](../tests/SKILL.md) skill), so it runs on every pull request, not only at adoption.

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

In a public repository whose CI workflow runs the three tools, that run on a draft pull request is the pre-PR check. Before each push, run the three tools on the changed files with the commands in *Local CI jobs* below (`vendor/bin/pint --test` and `vendor/bin/rector --dry-run` with the changed PHP files as arguments, and `composer stan`; a repository without one of the tools skips it), then open the pull request as a draft, fix what the workflow reports by pushing to the same branch, and flip it to ready once every remote check is green. The [`writing-pull-requests`](../writing-pull-requests/SKILL.md) skill's *Draft state and merge order* owns this and names the cases that still run locally. Where the tools have no remote run:

Run the three commands on the branch, and fix what they report before asking for review.

A finding you disagree with is argued in the configuration, with a reason, scoped as narrowly as the tool allows. Never a blanket suppression.

- **Rector.** A rule whose suggestion is a judgment call rather than a mechanical one moves to `$sweepOnlyRules` in `rector.php` with a comment saying why, where it stays active under the sweep profile; the seven rules the template ships there (readonly class and property promotion, static-to-non-static demotion, two docblock removals, and `StrictArrayParamDimFetchRector` / `AddParamFromDimFetchKeyUseRector`, which turn bad input from a notice into a fatal) are the shared starting set. A rule that is wrong for one file is skipped for that file alone, `RuleClass::class => [__DIR__.'/path/File.php']` in `withSkip()`, with the reason above it. A rule is never dropped from a prepared set wholesale.
- **PHPStan and Larastan.** An `ignoreErrors` entry pinned by `identifier` and `path`, with `message` added when the path has more than one site, and a comment naming the cause (for WordPress code, the core function responsible). Never a message-only pattern over a namespace, and never `@phpstan-ignore` without a comment. `reportUnmatchedIgnoredErrors` defaults to true, so an entry whose error is fixed fails the run until it is removed; that is the point. An entry gets `reportUnmatched: false` only where whether it matches depends on the level rather than on the code, as the WordPress template's Pest entries do, with a comment saying so.
- **Pint.** A rule disagreement is a change to the shared `templates/pint.json` in UAMS-Web/uams-claude-skills, proposed there, because the preset and its rules are one style for every repository; a per-repository rule change is not an option. A file Pint must not touch (generated code, a fork's upstream) goes under `exclude` or `notPath`.

What the tools enforce in day-to-day code, and the conventions they encode, are in the [php-coding-standards skill](../php-coding-standards/SKILL.md).
