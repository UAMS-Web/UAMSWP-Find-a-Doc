---
name: pest-testing
description: >-
  How to write tests with Pest. Trigger whenever any test is being written, edited, fixed,
  reviewed or refactored, including fixing tests that broke after a code change, adding
  assertions or datasets, converting PHPUnit to Pest, TDD workflows, and deciding which suite or
  process a new test belongs in. Covers test()/it()/expect() syntax, datasets, architecture
  tests, which expectations take a failure message, and how to read a mutation run and record a
  survivor. In a Laravel application also mocking, RefreshDatabase, Livewire component tests,
  browser testing (visit/click/fill) and smoke testing pages for JS errors. In a WordPress plugin
  also the Unit process that runs with no WordPress, the Feature process over wp-phpunit,
  dispatching every REST test through rest_do_request(), the typed helpers that keep tests
  analyzable, and the global state WordPress leaks between tests. Do not use for factories,
  seeders, migrations, controllers, models, or non-test PHP code.
license: MIT
metadata:
  author: laravel
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/pest-testing/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): pest.
-->
<!-- cspell:ignore killable Livewire nikic Stache viewports -->

# Pest Testing

All tests are written with Pest. Read the installed Pest major from `composer.lock` before reaching for a feature: the repositories that share this skill sit on different majors, and a flag or helper one major has, another lacks.

Without an application framework there is no `artisan`, no service container, no `RefreshDatabase`, no `Pest\Laravel\*` helpers and no browser testing. Guidance written for a Laravel project does not transfer; when a Pest example found elsewhere reaches for a framework helper, it does not exist here.

## Basic Usage

### Creating Tests

There is no generator command. Create the file.

Name files `*Test.php`. The testsuite suffix is `Test.php`, so a file named otherwise is silently never run, which reads exactly like a passing suite.

### Test Organization

- Do NOT remove tests without approval; these are core application code.

#### Processes, not suites

A WordPress plugin's suite runs as separate PHP processes. The split is a technical boundary in each case, never a naming convention:

| Process | Bootstrap | May touch |
| --- | --- | --- |
| Unit | `vendor/autoload.php` only | Pure PHP. **No WordPress function, no `WP_*` class, no database.** |
| Feature | `wp-phpunit` (boots WordPress against a real test database) | Everything: core functions, the REST server, the database, multisite where the test install is one. |
| One more process per constant state | the Feature bootstrap, plus a `define()`d constant | The branch that only exists once the constant is defined, or only while it is not. |

**Unit runs in its own process so the boundary enforces itself.** The bootstrap returns before loading WordPress when it sees `--testsuite=Unit`, so a unit test that reaches for a WordPress function dies with an undefined-function error instead of quietly passing against a core that happened to be loaded. If the two suites shared a process, that property would be gone and the boundary would be honor-system only.

**A separate process exists wherever `define()` decides a branch, because `define()` is permanent.** A code path reachable only while a constant stays undefined (an unset secret must authorize nothing, however plausible the presented header) cannot be tested in the same process as the one that defines the constant. Each test declares which process it belongs to with `->skip(...)` on an **eagerly evaluated** condition:

```php
it('authorizes nothing when no secret is configured', function (): void {
    // …
})->skip(defined('MY_PLUGIN_SECRET'), 'Runs in the process where no secret is configured.');
```

Pass a boolean, not a closure. On Pest 3 the closure form fails with an unrelated-looking `call_user_func()` TypeError from inside whoops; and the constant is set at bootstrap, long before test files load, so eager evaluation is correct anyway.

### Basic Test Structure

Pest supports both `test()` and `it()` functions. Before writing new tests, check existing test files in the same directory to match the project's convention. Use `test()` if existing tests use `test()`, or `it()` if they use `it()`. Match what is already there in the rest of the file too: `function (): void` closures, `expect()`, and `declare(strict_types=1);`.

<!-- Basic Pest Test Example -->
```php
it('is true', function () {
    expect(true)->toBeTrue();
});
```

Helper functions declared at the top of a test file are global. Keep their names specific (`indexAsSuperAdmin`, `requestWithSecret`); a generic one collides across files in the same process.

### Running Tests

Run the suite through the repository's `composer test` script rather than a bare `vendor/bin/pest`: the script selects the testsuites and processes that can run here, and a bare run picks up whatever the testsuite configuration lists. Scope a single run with `vendor/bin/pest --testsuite=<name> --filter=<test name>` or a file path.

## Not every expectation takes a failure message

`toBe()`, `toBeTrue()`, `toBeFalse()` and `toBeGreaterThan()` accept a message as their last argument. **`toContain()` and `toHaveKey()` do not**, and passing one does not error; it silently changes what is asserted:

- **`toContain(...$values)` is variadic.** `expect($haystack)->not->toContain('nf_sub', 'why this matters')` reads as *"contains neither of these"*, which is true whenever the message string is absent. It **passes with `nf_sub` sitting in the array.**
- **`toHaveKey($key, $value)`** takes an expected *value* second. `expect($map)->toHaveKey($key, 'the API did not serve it')` asserts the value equals that sentence.

Both were written in `uamswp-migration-api` and both were caught only by a negative control: the first turned a guard into a no-op, the second failed loudly for a reason that looked like a data bug. When an assertion needs a message, reach for a form that has one:

```php
// Inert: the message is read as a second value to look for.
expect($postTypes)->not->toContain($type, 'submission storage, not content');

// Bites.
expect(in_array($type, $postTypes, true))->toBeFalse('submission storage, not content');
```

This is the specific reason the [mutation discipline](#mutation-testing) matters for *guards* as much as for production code: a guard that has quietly stopped asserting is green forever, and looks exactly like a guard with nothing to complain about.

## Datasets

Use datasets for repetitive tests (validation rules, etc.):

<!-- Pest Dataset Example -->
```php
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
```

## Pest Features

### Architecture Testing

Architecture tests enforce code conventions:

<!-- Architecture Test Example -->
```php
arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->toHaveSuffix('Controller');
```

### Test Sharding

Split tests across parallel processes for faster CI runs.

## Every REST test goes through `rest_do_request()`

This is the load-bearing rule of the Feature suite. Calling a controller method directly proves almost nothing, because the mechanisms that make a plugin correct (authentication, site scoping) usually hang off `rest_pre_dispatch`, which core applies inside `WP_REST_Server::dispatch()`. A direct `$controller->index($request)` call fires none of them; the test then runs unauthenticated, against whatever site happens to be current, and passes.

```php
$request = new WP_REST_Request('GET', '/<namespace>/v1/');
$request->set_param('site', $second);

expect(rest_do_request($request)->get_status())->toBe(200);
```

That shape is the only one that catches the class of bug a REST suite exists for, and two such bugs are already on record:

- **The namespace index.** WordPress auto-generates a namespace index route with **no permission callback**, so `/<namespace>/v1` answers anonymously and discloses the full route table. Per-route callbacks structurally cannot cover it.
- **The unrestored blog.** `rest_post_dispatch` fires only in `serve_request()`. `rest_do_request()` is `dispatch()`, which never reaches it, so an internal sub-request can return with the site still switched.

**Mind the trailing slash.** `/<namespace>/v1/` is your own index controller. `/<namespace>/v1` (no slash) is core's generated index. They are different routes one character apart, and a test meaning to cover the second while hitting the first looks entirely green.

## Reach WordPress through typed helpers

Everything WordPress and the REST API hand back is `mixed`: the factories return `mixed`, `WP_REST_Response::get_data()` returns `mixed`. Where `tests/` is under static analysis, wrap the reads in typed helpers that narrow with `assert()`, which fails loudly and names the fixture that broke, where an inline cast papers over a genuinely wrong shape. Adding a helper is preferred over an inline cast.

**Do not reach for `$this->factory()` in a test closure.** PHPStan resolves `$this` inside a Pest closure to `PHPUnit\Framework\TestCase`, where `factory()` does not exist, and everything downstream degrades to `mixed`. Widen core's `protected static factory()` to public in the plugin's own test case so the helpers can call it as an ordinary typed static call; use the helpers, or add one.

## WordPress leaks global state between tests

`WP_UnitTestCase` wraps each test in a database transaction and rolls it back, so **rows** are clean. Nothing else is:

- **Hooks.** `add_filter()` inside a test stays registered for every later test in the process. Remove what you add in a `finally`, with the *same* callable identity (WordPress matches by identity, so a fresh closure will not remove anything). See the first-class-callable trap in [`php-coding-standards`](../php-coding-standards/SKILL.md).
- **The current user.** `wp_set_current_user()` persists. Set it explicitly in each test that cares rather than inheriting whatever ran last.
- **Constants.** Never `define()` inside a test: the first test to run wins and the rest silently exercise a different branch. Configure the process instead, as the secret process above does.
- **Registered post types and taxonomies.** `register_post_type()` outlives the test. Unregister in a `finally`.

## Never run two suites at once

The Feature suite installs into one WordPress test database. Two concurrent runs collide, and what comes back is a spray of failures (`siteid_exists`, missing fixtures) indistinguishable from real regressions. This costs an afternoon of bisecting an innocent diff, and it is easy to do by accident: a mutation run in the background plus an ordinary `composer test` in the foreground is enough.

This is also why review agents must not execute the suite while a run is in flight: give them `isolation: "worktree"` or forbid test runs in the prompt (see [`adversarial-review`](../rule-adversarial-review/SKILL.md)).

## Tests are under static analysis too

Where `phpstan.neon.dist` lists `tests` among its paths, a broken mock, a wrong-typed fixture or an assertion calling a method that no longer exists is a real analysis failure, and a test-only diff still has to pass static analysis.

## The suite runs on the deployment target

Run the suite on the PHP version production runs. That is not a substitute for `phpVersion` in `phpstan.neon.dist`, which remains the only thing that reports a construct the older PHP cannot *parse*: PHPStan parses with `nikic/php-parser` regardless of the interpreter, and a parse error never reaches the test runner. Keep both.

## Parallel runs: what a test may share

`pest --parallel` runs several worker processes against one filesystem. A test that passes alone can fail there in two ways, and the isolation convention is the answer to each:

- **Shared mutable state.** A test that writes a fixed path, faked disk or shared cache entry races with the same test in another worker, or in a second run on the same machine. Make the path unique per process (the worker token from `TEST_TOKEN` plus the process id, so two concurrent runs cannot share a worker index), or quarantine the file into a `serial` group that runs in one process after the parallel band. Prefer the unique path where the cause is known, and keep the `serial` group small and named.
- **Global symbols in namespace-less files.** Pest files carry no namespace, so a top-level `const` or `function` is global, and a bare `use X;` names a class that is already global. A name declared in two files fatals the moment both load into one worker, and the no-op import is a compile-time warning that a framework error handler may promote to an exception before the first test runs. Both pass when the file runs alone.

[`scripts/check-global-test-symbols.php`](scripts/check-global-test-symbols.php) enforces the second. `php .claude/skills/pest-testing/scripts/check-global-test-symbols.php tests` reads every PHP file under the given paths as tokens (so a match in a comment or string is ignored), prints how many files it read before any finding, and exits 0 clean, 1 on a finding, 2 when it could not see (a missing path, or no PHP file). Pass the whole suite in one run, because the redeclaration rule compares across files. Function names are compared case-insensitively, as PHP does (`helper()` in one file and `Helper()` in another collide, and the message names both spellings), while constant names stay case-sensitive. The contents of an unnamed `namespace { }` block count as global, so a function, constant or import declared there is read like one in a file with no namespace. The `code-quality` CI template's `pest` job runs it, and marks the step optional: a repository that never runs `--parallel` deletes it, since both defects show on the first serial run.

### Which rules are shared, and which stay with the application

`uams-statamic` keeps a larger guard, `scripts/check-parallel-safe-tests.php`, with five rules. Each was classified for whether it holds outside that application. Read at `UAMS-Web/uams-statamic` `main`, commit `e823826b8a78e002d896589c8e2c315d659f4153`, file SHA-256 `0bec900bab07c637c05a83018842049ccc578ff950d74d755fe02df03597236f` (35,744 bytes), 2026-10-15.

| Rule | What it flags | Verdict | Reason |
| --- | --- | --- | --- |
| A | `Storage::fake(` outside a `serial` file | application-specific | Laravel only, and its premise does not hold on the installed version: `laravel/framework` v13.35.0 (the version `uams-statamic`'s lock pins) appends `_test_{token}` to a faked disk root when `TEST_TOKEN` is set, so workers do not share the root. What remains is two concurrent runs sharing a worker index, which is that application's `ThrowawayRoot` concern. |
| B | `storage_path('...framework/testing...')` with no uniqueness token | application-specific | Hardcodes Laravel's `framework/testing` layout and recognizes that application's `ThrowawayRoot` helper by class name. |
| C | A global `const` or `function` declared in more than one file | shared | Pure PHP token analysis, no framework or layout assumption. Shipped. |
| D | A single-segment `use X;` in a file with no named namespace | shared | Pure PHP token analysis. The warning is PHP's; whether it becomes fatal depends on the error handler, which is why it is a parallel-run defect in Laravel. Shipped. |
| E | `assert()` or `\assert()` anywhere under `tests/` | application-specific | A typing policy rather than parallel safety, and its remedy names that application's `TypeNarrowing` helpers. A repository that wants the policy writes its own rule. |

**Decision: ship C and D, keep A, B and E with the application.** The shipped script carries a test per rule, a negative case for each neighboring shape it must leave alone, and a clean control. Each rule was also exercised against a real tree: the script read all 1,137 files of the `uams-statamic` suite at the commit above with no finding, then reported both rules on a copy with one duplicate function and one no-op import planted.

Of the three consumers, only `uams-statamic` runs `--parallel` (`aether-wordpress-importer` and `aether-wordpress-migration-api` run their suites serially), so the shared script has one parallel consumer today. Adopting it there means replacing rules C and D in its private guard, which is that repository's change. Revisit the split when a second repository adopts `--parallel` and meets a shared-state defect that Rule A or B would have caught outside Laravel.

## Mutation testing

Pest's `--mutate` mutates the source and requires the suite to notice. **A surviving mutant is a false-green test**: the code changed and nothing failed. The coverage driver it needs, the invocation forms that silently do nothing, and the checks that show a run executed at all are in [`pcov-setup`](../pcov-setup/SKILL.md); read that before trusting any score, including a perfect one. This section is how to read a run that did execute, and how to record what you decide about each survivor.

**The criterion belongs to [`adversarial-review`](../rule-adversarial-review/SKILL.md)**: no unexplained survivors, never a score. It is not restated here.

- **Every survivor ends in one of three states: killed, deleted, or annotated.** A survivor is a test to write, dead code to delete (mutation testing is the best dead-code finder you have), or an equivalent mutant annotated with the reason it cannot be killed. Kill, delete, annotate stay exhaustive; there is no fourth disposition where a survivor is simply left unexplained. If a survivor can be neither killed nor safely annotated (the node it would protect has no start line to stand above without also suppressing a sibling), restructure so it does, or delete the code the mutant targets.
- **Don't chase a literal 100%.** Every *killable* mutant dead is the goal; the last few percent are usually **equivalent mutants** (no input distinguishes them) whose only "kill" is an annotation in production source or deleting a defensive guard. Categorize every survivor; most fall into four buckets:
  1. **Real coverage gap** → add a dataset row that exercises that branch (an untested boundary `>=`, an untested fallback, positional vs named params, a plural/singular branch).
  2. **Inert data-table mutant** (`RemoveArrayItem` over a big alias/lookup map) → under `--covered-only` these are spawned simply because a legitimate test *executed* the data-returning method; killing each needs one near-circular assertion per entry. Default: accept and document the rationale in the test's docblock, unless the goal is 0 survivors, in which case generate an exhaustive dataset (see [`phpunit-to-pest`](../phpunit-to-pest/SKILL.md)).
  3. **Dead code** (the value is never read: a computed-then-`unset` local) → delete the dead code.
  4. **Equivalent mutant** (no input can change observable output: a `(int)`/`(string)` cast on a value already typed `int`/`string`, an unreachable defensive guard, a default that a clamp maps back to itself) → either remove the redundancy *legitimately* (tighten an `int|string` array shape to `array{divisor: positive-int, …}` so the casts become genuinely removable and static analysis stays green) or, for code that must stay defensive, annotate it as below. Judge each one; killing an equivalent mutant means contorting a test to assert something that cannot differ.
- **Record the judgment on the line rather than carrying it in your head. Annotate an accepted survivor standalone, on the line above the node, never trailing on the node's own line.** Bare `// @pest-mutate-ignore` skips every mutator on that node; `// @pest-mutate-ignore:MutatorName,OtherMutator` scopes it to the ones named. Put the reason on its own line above the annotation, never after the mutator name on the same line: the plugin splits the tail on `,` and compares each part to a mutator name with `===`, so any prose after the mutator makes the whole annotation match nothing and the survivor stays uncounted. The mutator name is not optional: a bare marker blanket-suppresses the node and every child, which raises the apparent result rather than showing up as a survivor.
- **A standalone annotation works through the comment scan alone, and a trailing one is a different hazard.** Two mechanisms exist. A comment-attachment scan skips the children of the node the comment attaches to, so a marker above a node protects everything nested inside it. A line map, keyed on the marker's own line and looked up with each node's `getStartLine()`, protects only a node that starts on that same line, so it never reaches the statement under a standalone marker (that is why the next bullet holds). Both need the marker at or before the node's first line: on a multi-line construct's *closing* line it is inert, since the scan only reaches forward and the map matches only the marker's own line. Trailing on the node's own line is worse than inert: php-parser attaches a trailing comment to the *next* node, so the marker silently suppresses the following statement. The line map covers the node itself only where `explode(PHP_EOL, ...)` splits the file; where PHP runs on Windows (`PHP_EOL` is `\r\n`) and the file has LF endings, `explode(PHP_EOL, ...)` finds no separator, every marker maps to line 1, and a trailing marker protects nothing, which is the case `check-mutate-ignore.php` reports.
- **A marker above a statement cannot suppress a mutator that targets that statement.** The plugin skips the annotated node's children but still runs its mutation step on the node itself, and a comment above a statement attaches to the statement node (`Stmt\Expression`, `Stmt\Return_`). So `// @pest-mutate-ignore:RemoveFunctionCall` above `sort($a);` leaves the `RemoveFunctionCall` mutant in the run, as it does for `RemoveMethodCall` and the other mutators whose target is the statement, while a marker above a `for` loop does suppress a comparison mutator, because the comparison is a child. Read at `pest-plugin-mutate` v5.0.1 (`ca1e6e640232`) and `nikic/php-parser` v5.9.0 (`9e33da9553fe`). Treat such a survivor as unsuppressed: kill it, or record why it cannot be killed in a comment beside the code, and expect it in the score. The reading is from source at those revisions only: it was not run against each mutator, and other versions are not read.
- **A bare trailing annotation can also poison every annotation after it in the file.** The line-to-mutator map is built once per file and never reset between entries, so a bare marker landing on a trailing line inherits whatever mutator list the previous colon-form annotation set, instead of defaulting to "all". This is a second, independent reason trailing placement is unsafe; it is not rescued by choosing a "safer" trailing spot.
- **A run cannot tell a silently-failed annotation from a wrong reason**: both read as an ordinary surviving mutant. Treat a survivor as suspect until the annotation is confirmed standalone, on or above the node's own line, with nothing but the mutator name after the colon.
- **Run [`scripts/check-mutate-ignore.php`](scripts/check-mutate-ignore.php) instead of confirming by eye.** From the project root, `php .claude/skills/pest-testing/scripts/check-mutate-ignore.php [path ...]` (default: the existing ones of `app`, `src` and `tests`; a path may be a file, and a directory walk does not enter a nested `vendor/` or `node_modules/`, so a theme can pass `.` or name its root files) reports a bare marker, a tail that names no known mutator, prose after a mutator list, and a trailing marker where the line map is dead, each with file and line. It prints how many files and annotations it read before any finding, and the `pest-plugin-mutate` revision its parsing model was measured against (`v5.0.2`) beside the installed one. Exit 0 is clean, 1 is a finding, 2 is a run that could not read what it needed. The `code-quality` CI template has a `mutate-ignore` job that runs it.
- **A clean run of that check says nothing about breadth.** It reads each marker's text, so it cannot see an annotation that suppresses more than the survivor it was written for, or one that suppresses nothing. An annotation should remove exactly one mutant for each mutator it names: one `IncrementInteger` annotation that removes two `IncrementInteger` mutants has hidden a second mutant nobody judged. Before opening a pull request, run [`scripts/check-mutate-ignore-breadth.php`](scripts/check-mutate-ignore-breadth.php), which measures it. From the project root, `php .claude/skills/pest-testing/scripts/check-mutate-ignore-breadth.php [path ...]` (default: the existing ones of `app`, `src`, `includes` and `tests`) counts, with the installed plugin, the mutants each annotation removes for each mutator it names, by measuring the file with and without that one marker. A search does not enter a `vendor/` or `node_modules/` directory below the path it searches, at any depth, so a second Composer install such as `tests/Integration/vendor/` is not read as the project's code; a path named on the command line is read even when it is such a directory or inside one. It flags an annotation that removes more than one mutant of a named mutator, one that removes none, and a mention of the marker outside an annotation (a trailing comment that names it, a string) that removes mutants through the plugin's line map, each with file and line. It first measures planted cases with known answers, through comment attachment, the line map and an inherited name, and refuses to read the project when any reading is wrong. Findings that predate the check go in `tests/mutate-ignore-breadth.baseline.json`, written with `--write-baseline` and read whenever it exists: a finding in the baseline does not fail the run, a new one does, and so does an entry that no longer occurs in a scanned file or names a file that is gone, so the baseline only shrinks. Exit 0 is clean, 1 is a finding or a stale entry, 2 is a run that could not measure. The `local-ci` skill's `mutate-ignore-breadth` job runs it.
- **The config-level `ignore()` and `except()` are not substitutes for it.** They silence a whole path, or a mutator across the repository, so neither can carry a per-survivor justification and neither is visible at the line someone is reading.
- **The annotation mechanics above were measured in `uamswp-migration-api` against `pestphp/pest-plugin-mutate` v3.0.5 (`e10dbdc9`), `pestphp/pest` v3.8.7 (`f108313b`) and `nikic/php-parser` v5.8.0 (`044a6a39`)**; re-check before relying on this if any of the three has moved since.
- **Whenever survivors are accepted as equivalents, the test file's docblock must state which survive and why, ending on "every killable mutant is dead" (or the equivalent)**, so a reader and the next adversarial pass know the residual score is equivalents, not an untested gap.
- **Name the platform the score was taken on, in the same line as the score.** A mutation score is the claim where omitting the machine costs the most: `100%` is simultaneously the house standard and the precise signature of a run that executed nothing, so a corpus of unattributed scores cannot audit itself in either direction. **The platform also decides whether a score can close a coverage ticket: only a Linux run does** (the `Bar` section of the [`tests`](../tests/SKILL.md#bar) skill, UAMS-Web/uams-claude-skills#543). A Windows or macOS score is working evidence.
- **Where the Unit bootstrap loads Patchwork, name the test file(s) in a `--mutate` run** (`pest tests/Unit/SettingsTest.php --mutate --covered-only --path=<source file>`). A run with no test named makes each mutant child scan `tests/Unit`, which Patchwork's stream wrapper and the plugin's cannot do together, so every child dies and is scored as a kill: 100.00% on a suite that kills nothing. [`pcov-setup`](../pcov-setup/SKILL.md) has the mechanism, and its instrument check reports it.
- **Never pass `--filter` to a `--mutate` run; scope it with `--path`**, and read a reported timeout as unverified, never as a kill. Why is in [`pcov-setup`](../pcov-setup/SKILL.md).
- **`0 Mutations for 0 Files created` is a setup failure, not a pass.** Usually a missing coverage driver or a path the finder could not resolve (see [`pcov-setup`](../pcov-setup/SKILL.md)).
- **Run [`scripts/check-mutation-run.php`](scripts/check-mutation-run.php) on the output rather than reading a score off it.** `php .claude/skills/pest-testing/scripts/check-mutation-run.php mutate.log [--min=<score>]` fails (exit 1) on a run that created no mutants, on any untested, uncovered, pending or timed-out mutant, and on a score under the minimum (default 100). It prints the counts it read before any finding, and exits 2 when the log cannot be read. The `code-quality` CI template's `mutate` job runs it after `composer mutate -- --min=100`.
- **Pass-3 / inventory harden is single-flight.** One `--mutate` per worktree at a time. While it runs, prep the next eligible class but do not remutate it; commit the current class when it is mutation-clean (or every survivor is explained), then kick the next narrow mutate. Full rule and safe overlap list: [`pcov-setup`](../pcov-setup/SKILL.md) (three-pass walk).
- **Clearing `storage/framework/testing/` while a `--mutate` run is live orphans that run.** [`scripts/clear-testing-litter.mjs`](scripts/clear-testing-litter.mjs) refuses, exiting 2 and changing nothing, when a `storage/logs/mutate-*.status` file has no `exit=` line and names a running process (`child=`, `runner=` or `pid=`). A status file left by a killed run names a process that is gone and does not block. When it blocks wrongly (a recycled process id), read the file, confirm the run is not yours, and pass `--force`, which overrides this guard and nothing else (a root that is not an application, and a symbolic link on the path, are still refused). `--skip-artisan` removes the litter and restores the placeholder without running `cache:clear` or `statamic:stache:clear`, for a machine without PHP or an application that cannot boot. `--root=<dir>` acts on that application root instead of the working directory.

## JUnit checks: skipped tests and the costliest class

A run that reports zero failures can still have tested less than it should (tests skipped because a fixture was missing) or have quietly become expensive (one class growing until it bounds a parallel run). Two small PHP scripts in this skill's `scripts/` folder read the JUnit report that Pest already writes and turn each case into a failing exit code. Both are PHP so a CI job that sets up PHP and nothing else can run them. Neither names an application path: every file is passed as an argument.

Write the report with `--log-junit`, then run the checks:

```bash
vendor/bin/pest --log-junit=junit.xml
php .claude/skills/pest-testing/scripts/check-skip-baseline.php --report=junit.xml --baseline=tests/skip-baseline.json
php .claude/skills/pest-testing/scripts/check-slowest-class.php --report=junit.xml --share-ceiling=25 --ceiling=900
```

Each script exits 0 when the report is within bounds and 1 otherwise. An empty or truncated report, or one that cannot be parsed, a missing file, and a report with no usable data are also failures, never passes, because a run that produced nothing to check has not been verified.

### CI step

In a GitHub Actions job that already sets up PHP and installs dependencies, replace the plain test step with the three below; the `pest` job in the `code-quality` skill's `ci.yml` template carries exactly these. `composer test` forwards the extra argument to Pest, so a repository whose test script is something else changes only the first command. The checks run after the tests and not on a cancelled run, so a test failure is reported by the tests and a missing report is reported by the checks:

```yaml
      - name: Pest
        run: composer test -- --log-junit=junit.xml
      - name: Skip baseline
        if: ${{ !cancelled() }}
        run: php .claude/skills/pest-testing/scripts/check-skip-baseline.php --report=junit.xml --baseline=tests/skip-baseline.json
      - name: Slowest class
        if: ${{ !cancelled() }}
        run: php .claude/skills/pest-testing/scripts/check-slowest-class.php --report=junit.xml --share-ceiling=25 --ceiling=900
```

### Skip baseline

`check-skip-baseline.php` fails when the number of skipped tests exceeds the number recorded for the run, and prints both numbers (`skipped X, baseline Y (+Z)`) followed by the skipped tests. Fewer skips than the baseline passes, because fewer skips means more tests ran.

The baseline is a JSON file the repository keeps (the example path above is a convention, not a default; `--baseline` is required):

```json
{
    "bands": {
        "suite": { "expected": 3, "why": "Three cases skip when the content submodule is absent." },
        "serial": { "expected": 0, "why": "The serial tail skips nothing." }
    }
}
```

- A **band** is one independently run group of tests with its own report, such as a parallel run and a serial run. `--band=<name>` picks the entry and defaults to `suite`.
- `expected` is a non-negative integer. Anything else, a missing band, or invalid JSON is a failure.
- `why` is free text for reviewers and is not read by the script. A top-level `_comment` is ignored.
- **Raising `expected` is a reviewable change to this file.** The pull request should say why each new skip is correct. The first assumption on a failure is a real regression (a missing submodule, a starved worker, a guard firing on input that should have resolved), not a stale number.

### Costliest class

`check-slowest-class.php` sums the `time` of every test case per class, then fails when the costliest class takes more than `--share-ceiling` percent of the summed class time, or more than `--ceiling` seconds. Both thresholds are checked and each failure is reported separately. Defaults are 25 percent and 900 seconds; `--band` only labels the output. `--share-floor=<seconds>` skips the share check when the summed class time is under that many seconds, and the output then names the summed time and the floor; the backstop is still checked. Its default, 0, never skips.

- **The share is the main check.** It is a ratio, so machine load that slows every class equally cancels out of it. It is also what decides whether a class binds a parallel run: paratest distributes whole classes, so no run finishes sooner than its largest class, and a class starts costing wall-clock once its share passes 1 divided by the worker count.
- **The seconds ceiling is the backstop.** It catches growth that hits every class at once, such as a per-test boot cost, which moves no share. Set it several times above the worst quiet reading so load alone does not reach it.
- **The defaults are loose starting points, not measurements of any suite.** Measure the costliest class's share and time on several quiet and several busy runs, then set the share ceiling a margin above the worst share observed and below 100 divided by the workers, and pass both as flags from the CI step so the numbers sit beside the command that uses them.
- **A serial run needs a looser share ceiling.** A load spike confined to the window where the costliest class runs is not cancelled by a ratio, and a serial run has one class per window.
- **A small suite needs `--share-ceiling=100`.** With few classes, one class takes a large share whatever its size (at least 100 divided by the class count), and in a suite of a few seconds the wall time it bounds is too small to be worth splitting for. `UAMS-Web/statamic-redirects`, with 24 tests over 0.3 seconds, had one class at 72 percent, so the default 25 failed a run with nothing to fix (UAMS-Web/statamic-redirects#9). Set the share ceiling to 100 there, which leaves only the backstop (a suite of one class passes at 100 too), and lower it once the suite runs long enough for its costliest class to cost real wall time. A repository on the reusable workflow does not need to: its `Slowest class` step passes `--share-floor` from the `share-floor-seconds` input, default 10, so a suite summing to under 10 seconds skips the share check and a larger one is judged at 25 percent (UAMS-Web/uams-claude-skills#505). The floor needs the synced copy of this script that reads `--share-floor`: an older copy ignores the flag and judges the share as before.
- When it fails, split the class rather than raising a ceiling.

### What is not generalized

The per-band measured ceilings, the backstop values and the skip counts that came with the original scripts describe one application's suite and are not shipped. Each repository records its own: the baseline file for the skip counts, and the flag values in its CI step for the cost thresholds.
## Changed tests against base

[`scripts/changed-tests-against-base.mjs`](scripts/changed-tests-against-base.mjs) answers the question a coverage or bug-fix pull request leaves open: do the tests it adds or changes already pass against the source as it was? The pull request's own run cannot say, because it runs the new tests against the new source (UAMS-Web/uams-claude-skills#357, #391).

It builds a scratch git worktree of the pull request, outside the checkout, and reverts every change the pull request makes outside `tests/` to the base branch's version. Then it installs dependencies there, runs only the changed test files, and reports each one:

| Report | Meaning |
| --- | --- |
| passed | the behavior the test describes was already true on the base |
| failed | the base behaves differently, which a bug fix is expected to show; the failing cases are named, and the pull request body says which failures are expected |
| needs the pull request's source | every failure is a class, method, function, interface, trait, enum or constant the base does not have **and the pull request's changed source declares**, so the test calls something the pull request added; this is not a behavior change. A missing symbol the pull request does not declare, such as a typo'd call the pull request fixes, is reported as failed |
| skipped | every case skipped or marked todo, so nothing was compared; skipped cases are never counted as passing |
| did not run | the file aborted against the base for another reason, and the job fails |

- **It reports and never gates.** A failure against the base is information for the reviewer, so the job succeeds once the report exists. It fails only when it cannot do the comparison: the base does not resolve, the scratch tree cannot be built or installed, the runner writes no results, or a test file did not run.
- **The test side is laid over the base whole.** Changed support files under `tests/` (`tests/Pest.php`, fixtures, helpers) keep the pull request's version beside the changed tests, because they are part of what the tests say: a helper a new test calls is not a behavior of the source. The report lists every support file laid over and every source file left at base, so a reader can see where the line was drawn.
- **Dependencies are installed in the scratch tree** (`composer install` by default; `--install <command>` or `--install none`), never linked from the checkout. A linked `vendor/` makes Pest load the donor tree's bootstrap, the trap the `worktrees` rule describes.
- **Cases are attributed by the file Pest records on each case**, so `describe` blocks and datasets, which Pest logs in nested suites without a `file`, count toward their file. A class-based file is matched by the class it declares.
- **A run that is interrupted** (SIGINT or SIGTERM) removes its scratch worktree before exiting; a SIGKILL cannot be caught, and `git worktree prune` clears what one leaves.
- **The base** is `--base <ref>`, else `origin/$GITHUB_BASE_REF` on a pull request in Actions, else `origin/main`. The comparison is against the merge base of that ref and `HEAD`. A push or any other Actions event is reported as nothing to compare.

How to wire it into `ci.yml` and `local-ci.json` is in the `code-quality` skill, *Adding the changed-tests report*.

Measured on macOS (Darwin 27.2.0) with PHP 8.4.27 and Pest 4.7.8, on a branch that planted a behavior change. A test describing the new behavior was reported failed against the base and passed against the branch. A test calling a method the branch added was reported as needing its source. A test of unchanged behavior passed. The run exited 0 and left the checkout unchanged. Windows is covered by the script's tests only.

## Common Pitfalls

- Deleting tests without approval.
- Forgetting datasets for repetitive validation tests.
- Passing a message to `toContain()` or `toHaveKey()`, which silently changes the assertion.
- Reading `0 Mutations for 0 Files created` as a pass.
- Passing `--filter` to a `--mutate` run, or reading its timeouts as kills.
- Starting a second `--mutate` (or overlapping `--parallel` / heavy Pest) in the same worktree while one is still running.
- Annotating an accepted survivor on the node's own line, or with prose after the mutator name.

- Calling a controller method directly instead of dispatching, so neither auth nor site scoping runs.
- Testing `/<namespace>/v1/` when you meant core's generated `/<namespace>/v1`.
- A Feature test that needs nothing from WordPress: put it in the Unit process and keep it fast.
- `$this->factory()` instead of a typed helper, which fails static analysis.
- A closure passed to `->skip()`.
- `define()` inside a test.
- Adding a filter without removing it, or removing it with a different closure.
- Running the suite while another run is in flight.
