---
name: tests
description: >-
  The testing standard for every UAMS-Web PHP repository, and how to bring a repository onto it.
  Use when adding tests to any PHP repository, adding a test harness to a repository that has
  none (WordPress plugin, must-use plugin, WordPress theme, Laravel or Statamic application,
  plain PHP library), deciding whether a repository can run Pest, choosing between a
  Brain Monkey unit test and a wp-phpunit integration test for WordPress code, pinning the
  current behavior of untested legacy code before changing it, moving a PHPUnit suite to Pest,
  scoping tests and coverage to our modifications in a fork of a vendor plugin, adding the test
  job to the local CI runner, or writing a repository's harness, Pest-migration or
  full-coverage ticket. Ships copyable templates for each kind of repository. For Pest syntax
  itself, defer to the pest-testing skill where the repository has it.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/tests/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): tests.
-->
<!-- cspell:ignore absint ABSPATH autoloaded muplugins Packagist packagist Playwright tia wpdb yoast -->

# Tests

## The standard

Every PHP repository in UAMS-Web has tests (UAMS-Web/uams-claude-skills#9). A repository with none is not following a different convention; it is missing them, and work in it adds them as it goes.

- **Pest wherever it can run.** One framework keeps one skill, one set of review habits and one mutation-testing tool (`--mutate`) across every repository. Where Pest cannot run, the repository stays on PHPUnit and its ticket records why and what would let it move (see the next section).
- **WordPress code gets unit tests first.** WordPress functions are mocked with Brain Monkey, so the suite runs on any machine in seconds with no database and no WordPress checkout. Code whose behavior depends on WordPress itself (hooks firing, the database, queries, the REST server) also gets **integration tests** against a real WordPress test install (`wp-phpunit`).
- **Legacy code is pinned before it is changed, then brought to [full coverage](#full-coverage).** Before changing untested code, write tests that pin what it does now, so the change shows up as a difference. Coverage does not stop at the code that happened to change: each repository has its own ticket to reach full coverage.
- **Tests run locally in private repositories.** Actions minutes are billed on private repositories, so the suite runs locally before a pull request (`composer test`, or the shared local CI runner when the repository has it). Public repositories, where Actions is not billed, may also run it in Actions.

Static analysis and formatting are the [`code-quality`](../code-quality/SKILL.md) skill's (UAMS-Web/uams-claude-skills#10).

## Can this repository run Pest

Answer the questions below, in order, and record the answers on the repository's harness or migration ticket. Dependency interrogation (`composer why` / `why-not`) comes **after** development dependencies exist, not before.

**1. Does the repository have a `composer.json`?** If not, add one holding development dependencies only: `name`, `description`, `type`, `license`, a `require` block holding only `php` (the supported floor, so Rector and PHPStan know it; see the [`code-quality`](../code-quality/SKILL.md) skill), `require-dev`, `config` and `scripts`. Nothing in `require` beyond `php`, so nothing new is installed into production. Ignore `vendor/` in `.gitignore`. Keep `vendor/`, `tests/` and `phpunit.xml` out of releases with `.gitattributes` `export-ignore` lines (see [Packaging](#packaging-tests-out-of-releases)); a zip or clone deploy that skips `git archive` still must not ship those paths. A repository with a hand-rolled `tests/` directory of scripts gets the same treatment; its scripts become the first Pest tests.

**2. Is the oldest PHP the repository supports new enough for a maintained Pest?** The supported floor is the lowest of `composer.json`'s `require.php`, a plugin or theme header's `Requires PHP`, and any documented support. Compare it with what each Pest major requires (repo.packagist.org, 2026-10-02):

| Pest | PHP | PHPUnit | Latest release |
| --- | --- | --- | --- |
| 5 | `^8.4` | 13 | v5.3.0, 2026-10-01 |
| 4 | `^8.3` | 12 | v4.7.8, 2026-08-03 |
| 3 | `^8.2` | 11 | v3.8.7, 2026-07-06 |
| 2 | `^8.2` (`^8.1` for the earliest 2.x) | 10 | v2.36.1, 2026-01-28 |
| 1 | `^7.3 \|\| ^8.0` | 9 | v1.23.1, 2023-07-12; no release since |

Pest 1 is not maintained, so **a floor below PHP 8.2 means Pest cannot run**. Composer would still install Pest on a developer's newer PHP, because it resolves development dependencies against the PHP running it, but the suite would then never run on the PHP the repository promises to support. Re-read the table from Packagist before quoting it: a major that stops receiving releases moves the floor.

Do not pin `config.platform.php` to that floor so Composer "resolves for production." Tooling installs on the developer's PHP; the floor belongs in `require.php` (and, on the PHPUnit path, in the PHPUnit major the templates name). Same rule as the [`code-quality`](../code-quality/SKILL.md) skill. Keep an existing platform pin only when the repository already needs one for another reason.

Known pins to plan for when choosing a major (no `composer` commands yet; those run after dependencies are added):

- `yoast/phpunit-polyfills` 4.0.0, which WordPress' test suite requires, supports PHPUnit 7.5 to 9, 11 and 12, not 10 or 13. **A WordPress repository with an integration suite runs Pest 3 or Pest 4, never Pest 2 or 5.**
- `10up/wp_mock` 1.x requires `phpunit/phpunit ^9.6`, which only Pest 1 accepts. This is one reason the unit layer here is Brain Monkey (see [The two WordPress layers](#the-two-wordpress-layers)).

Choose the newest Pest major the floor and those known pins allow. If none does, the repository cannot run Pest yet.

**3. After development dependencies exist: does a dependency pin PHPUnit where Pest cannot follow?** Only once `require-dev` is in `composer.json` and `composer install` has run: use `composer why phpunit/phpunit` to see who constrains it, and `composer why-not pestphp/pest <constraint>` for the major question 2 allows. Adjust the Pest or PHPUnit major if a transitive pin blocks the planned choice. Skip this question on a greenfield harness until step 3 of [Adding a Pest harness](#adding-a-pest-harness-to-an-untested-repository) (or the PHPUnit equivalent) has installed dependencies.

### A repository that cannot run Pest

It still gets a harness, on **PHPUnit 9** when the floor is below PHP 8.2 (`phpunit/phpunit ^9.6`: PHPUnit 10 needs PHP 8.1+, and the polyfills skip 10). Copy [`templates/wordpress-plugin-phpunit/`](templates/wordpress-plugin-phpunit/) for a WordPress plugin (same `tests/Unit` and `tests/Integration` split, PHP 7.4-safe bootstrap, Brain Monkey `UnitTestCase`, `yoast/phpunit-polyfills`). PHPUnit 9 coverage uses `<coverage><include>` in `phpunit.xml`; PHPUnit 10+ renamed that block to `<source><include>` (the Pest templates use `<source>`). When the suite later moves to PHPUnit 10+, also take the adapter in [`templates/wordpress-plugin/tests/Support/IntegrationTestCase.php.dist`](templates/wordpress-plugin/tests/Support/IntegrationTestCase.php.dist) (`expectDeprecated` without removed PHPUnit APIs, empty `checkRequirements`).

Brain Monkey supports PHP 5.6 and later and works with any PHPUnit; on the PHPUnit path its setup is the template's `UnitTestCase` (`Brain\Monkey\setUp()` / `tearDown()`, Mockery's `MockeryPHPUnitIntegration` so expectations count as assertions).

Its ticket names the constraint that blocks Pest and what would lift it: raising the PHP floor to 8.2, upgrading the dependency that pins PHPUnit, or replacing it.

## Adding a Pest harness to an untested repository

1. Answer questions 1 and 2 under [Can this repository run Pest](#can-this-repository-run-pest) and pick the Pest major (or stop and use the PHPUnit templates if Pest cannot run).
2. Copy the template for the kind of repository (below), drop the `.dist` suffix from every `*.php.dist` file when copying into the repository, and fill in its placeholders.
3. Add the development dependencies, `composer install`, run question 3 (`composer why` / `why-not`) to confirm no pin blocks the chosen major, and run the example test to see it pass. Break the code it covers and see it fail; a harness that cannot fail is not a harness.
4. Replace the example test with a first real test of the repository's own code. Write further tests per [`pest-testing`](#further-reading).
5. Add the `test` Composer scripts if the template merge did not already. Run the suite with `composer test` (or `composer test:unit` / `composer test:integration`) before every pull request. When the repository has the shared local CI runner, also wire the test job(s) into `local-ci.json` ([Running the suite before a pull request](#running-the-suite-before-a-pull-request)); if it does not, adopt the shared `local-ci` skill when ready, or keep running the Composer scripts directly until then.
6. Open the repository's full-coverage ticket, blocked by the harness ticket, if it does not exist yet.

Name every test file `*Test.php`. The testsuites select by that suffix, so a file named otherwise never runs, and an empty run looks like a passing one; `failOnEmptyTestSuite` in the Pest templates turns that into a failure (PHPUnit 10+; the PHPUnit 9 templates omit it and rely on the Composer scripts selecting a suite).

### Packaging tests out of releases

Mark development-only paths `export-ignore` in `.gitattributes` so `git archive` and release packaging that honors it leave them out. At minimum:

```gitattributes
/vendor/ export-ignore
/tests/ export-ignore
/phpunit.xml export-ignore
/composer.json export-ignore
/composer.lock export-ignore
```

Add the repository's other tooling files the same way. Pest configuration lives at `tests/Pest.php`, already covered by `/tests/`. A deploy that zips the tree or clones onto the server must apply the same exclusions by its own means; `export-ignore` alone does not help those paths.

### WordPress plugin, including a must-use plugin

**Pest (PHP floor 8.2+):** [`templates/wordpress-plugin/`](templates/wordpress-plugin/). Copy each `*.php.dist` to the same path without `.dist`.

**PHPUnit 9 (Pest cannot run):** [`templates/wordpress-plugin-phpunit/`](templates/wordpress-plugin-phpunit/). Same layout, class-based tests, PHP 7.4-safe bootstrap (`strpos` instead of `str_contains`, no `mixed` parameter types), `<coverage><include>` in `phpunit.xml`, and a thin `IntegrationTestCase` until PHPUnit 10+ needs the Pest template's adapter.

| File (drop `.dist` when copying) | What it is |
| --- | --- |
| `composer.require-dev.jsonc` | The `require-dev`, `config` and `scripts` keys to merge into `composer.json`, with a note on the PHP and PHPUnit constraints. Not a `composer.json` of its own. |
| `phpunit.xml` | Two testsuites, `Unit` and `Integration`, and the paths coverage measures (`<source>` on Pest / PHPUnit 10+; `<coverage>` on the PHPUnit 9 templates). |
| `tests/bootstrap.php.dist` | Composer's autoloader, then either the Unit layer (Patchwork, `ABSPATH`, the plugin's definition files, no WordPress) or the Integration bootstrap, decided by the testsuite the process runs. |
| `tests/Pest.php.dist` | Pest only: Brain Monkey around every Unit test; WordPress' test case bound to every Integration test. |
| `tests/Support/UnitTestCase.php.dist` | PHPUnit path only: Brain Monkey base case with `MockeryPHPUnitIntegration`. |
| `tests/Unit/ExampleTest.php.dist` | Example unit test of `example_plugin_read_more_link()` with `get_permalink()` mocked. |
| `tests/Integration/bootstrap.php.dist` | Locates WordPress, refuses the working install's database, loads the plugin on `muplugins_loaded`, boots `wp-phpunit`. |
| `tests/Integration/wp-tests-config.php.dist` | The test install's configuration, every value from a `WP_TESTS_*` environment variable with a local default. |
| `tests/Support/IntegrationTestCase.php.dist` | Pest / PHPUnit 10+: `WP_UnitTestCase` adapted to PHPUnit 10 and later. PHPUnit 9 templates: thin subclass until that adapter is needed. |
| `tests/Integration/ExampleTest.php.dist` | The same function inside a real WordPress: the hook is registered, and the link points at a real post. |
| `example-plugin.php.dist`, `includes/functions.php.dist` | The smallest plugin the tests exercise, so the templates run as shipped. Not copied into a real plugin. |

Placeholders to replace throughout, consistently: `example-plugin.php` (the plugin's main file), `includes/functions.php` (the files that define the plugin's functions and classes), `EXAMPLE_PLUGIN_TESTS_BOOTSTRAPPED` and `example_plugin_tests_want_wordpress` (the plugin's prefix), `ExamplePlugin\Tests\Support` (the plugin's namespace), `example_plugin_tests` (the test database) and `Example Plugin Tests`. `IntegrationTestCase` is required by the Integration bootstrap rather than autoloaded, so no `autoload-dev` entry is needed.

Run it:

```bash
composer install
mysql -u root -e 'CREATE DATABASE example_plugin_tests'   # once; never a database anything else uses

# Unit suite: no WordPress checkout required
composer test:unit

# Integration suite: needs a WordPress core tree and the test database.
# Default: checkout lives at <wordpress>/wp-content/plugins/<plugin>/ (five levels
# above tests/Integration/). Otherwise point WP_TESTS_ABSPATH at a core copy:
wp core download --path=/tmp/wordpress
WP_TESTS_ABSPATH=/tmp/wordpress composer test:integration

composer test              # test:unit, then test:integration, as two processes
```

**The Unit layer loads definitions, not registrations.** The main plugin file registers hooks at file scope, and Brain Monkey's `add_action()` and `add_filter()` exist only inside a test, so the Unit bootstrap loads the files that define functions and classes and never the main file. A plugin that defines and registers in the same file is split first, as the first change the harness pins (see [Pinning legacy behavior](#pinning-legacy-behavior-before-a-change)); until then, load that file the way the theme template loads `functions.php`: wrap the `require` in one Brain Monkey `setUp()` / `tearDown()` session and stub every WordPress function the file calls at load time. Typical plugin load-time stubs (add only those the file actually calls):

- Paths and URLs: `plugin_dir_path`, `plugin_dir_url`, `plugin_basename`, `trailingslashit`, `untrailingslashit`
- Plugin metadata: `get_plugin_data`, `is_plugin_active` (when the file reads them at include time)
- Translations already covered by `Functions\stubTranslationFunctions()` when the file translates at load time
- Hooks: Brain Monkey defines `add_action` / `add_filter` during `setUp()`; no extra stub unless the file calls something outside that set

Classes autoloaded by Composer need nothing in the bootstrap.

**Core classes in signatures.** A unit-loaded file that type-hints a WordPress core class (`WP_Block_Editor_Context`, `WP_Post`, `WP_REST_Request`, ...) fatals when that class does not exist. Before requiring the file, load a stand-in under `tests/Support/` (or inline in the Unit bootstrap) that declares an empty class of the same name in the global namespace when `class_exists` is false. Keep stand-ins minimal: enough for `instanceof` and type hints, no behavior. Integration tests use the real classes from WordPress.

**Must-use plugins.** WordPress loads every PHP file at the top of `wp-content/mu-plugins/` on each boot, including the test install's boot, and the test install uses the surrounding install's `wp-content`. A must-use plugin checked out there is therefore already loaded before `muplugins_loaded`: drop the `require` from the Integration bootstrap rather than load it twice, or point `WP_TESTS_ABSPATH` at a clean core copy where nothing else loads. The same applies to every other must-use plugin in the surrounding install: it runs in the test process too, so where one interferes, use a clean core copy.

The Pest templates were run end to end on 2026-10-02 under Pest 3.8 (PHPUnit 11) and Pest 4.7 (PHPUnit 12), single site and multisite, against WordPress 7.1; Pest 5 does not resolve beside the polyfills.

### WordPress theme

Templates: [`templates/wordpress-theme/`](templates/wordpress-theme/), which holds only what differs from the plugin. Copy the plugin template's `phpunit.xml`, `tests/Pest.php.dist` (as `tests/Pest.php`), `tests/Support/IntegrationTestCase.php.dist`, `tests/Integration/wp-tests-config.php.dist` and the `composer.require-dev.jsonc` keys, rename `ExamplePlugin` and `example_plugin` to the theme's, and in `phpunit.xml` replace `./includes` and `./example-plugin.php` with the theme's directories and `./functions.php`. Then use the theme's own (each `*.php.dist` drops `.dist` when copied):

- `tests/bootstrap.php.dist`: a theme cannot avoid loading `functions.php`, which usually finds its files through `get_template_directory()` and registers hooks at file scope. The Unit bootstrap loads it inside one Brain Monkey session with those calls stubbed; the functions it defines outlive the session, and the hooks it registered are discarded. Add a stub for each WordPress function the theme's `functions.php` calls at load time.
- `tests/Unit/TemplateTagsTest.php.dist`: a template tag (`inc/template-tags.php`) tested with `get_the_date()` mocked. Template tags that return markup test as strings; one that echoes is tested with `ob_start()` and `ob_get_clean()`, or changed to return what it prints, with the echoing wrapper kept for templates.
- `tests/Integration/bootstrap.php.dist`: instead of requiring a plugin file, makes the checkout the active theme without writing an option, the recipe `wp scaffold theme-tests` generates.
- `tests/Integration/ExampleTest.php.dist`: the theme is active and its `after_setup_theme` callback ran.

Page templates (`single.php`, `archive.php`) are tested in the Integration suite by setting up the query with `$this->go_to()` and capturing the template's output, and only where their logic warrants it; logic worth testing usually belongs in a function the template calls.

### Laravel or Statamic application

Templates: [`templates/laravel/`](templates/laravel/), trimmed to what a new application needs: `phpunit.xml` (Laravel's skeleton configuration, plus the two Statamic variables, commented, that parallel runs need) and `tests/Pest.php.dist` (copy as `tests/Pest.php`; Feature tests extend `Tests\TestCase`; Unit tests do not boot the application).

An application created without Pest moves to it as Pest's installation guide describes: `composer remove phpunit/phpunit`, `composer require pestphp/pest pestphp/pest-plugin-laravel --dev --with-all-dependencies`, then `vendor/bin/pest --init` only if `tests/Pest.php` does not exist yet. Compare `phpunit.xml` with the template rather than replacing it; an existing application's environment variables are there for reasons.

Run it with `php artisan test --compact` or `vendor/bin/pest`. Laravel's own testing guidance is in the [`laravel-best-practices`](#further-reading) skill where the repository has it.

### Optional Laravel harness patterns

The template binds Feature tests once, in `tests/Pest.php`: `pest()->extend(TestCase::class)->in('Feature')`. That stays the starter. Two other shapes are allowed. They are not starter steps, and a small application should not copy them from a large one.

**Per-file Feature binding.** A Feature file may name its own base instead of inheriting the directory binding:

```php
uses(Tests\TestCase::class);
```

Traits go on the same call: `uses(Tests\TestCase::class, SomeTrait::class);`. A file may name a subclass of `Tests\TestCase` when that file needs a narrower case. Use the per-file form when the base class should be visible in the file, when Feature files do not all share one case, or when PHPStan or `pest-plugin-phpstan` should read the file's own `uses()` rather than a binding in `tests/Pest.php`. An application that binds per file does not also call `->in('Feature')` in `tests/Pest.php`.

Keep the directory binding for a new application. One line covers every Feature file, and Unit tests stay unbound so they do not boot the framework.

**A browser suite, only when the application has one.** A `tests/Browser` directory can stay out of the default `phpunit.xml` testsuites, so `php artisan test` does not run it. Bind it on its own and run it with `vendor/bin/pest tests/Browser`:

```php
uses(Tests\TestCase::class)->in('Browser');
```

Playwright timeouts and write-isolation hooks, when the application needs them, belong in that application. Do not copy one application's Control Panel helpers, `require_once` support files, or isolation classes into this template.

**A checkout-local test impact cache, only when Tia is installed.** Pin the cache inside the checkout so two worktrees do not share one key:

```php
pest()->tia()->directory('tests/.pest/tia');
```

`tia()->directory()` needs Pest `^5.1.1`. Skip it when the application does not use Tia. Without the pin the cache lives under `~/.pest/tia/`, outside the checkout; the pin moves it in, so the repository must ignore it itself. Pest adds no ignore rule. Add `/tests/.pest/tia/` to `.gitignore`, or the cache shows up as untracked files and the shared local CI runner reports the tree as dirty. Ignoring all of `tests/.pest/` also hides Pest's snapshots in `tests/.pest/snapshots/`, which Pest's documentation says to commit.

### Plain PHP library

Templates: [`templates/library/`](templates/library/): `phpunit.xml` with one `Unit` testsuite over `tests/Unit` and `src/` as the coverage source, and a `tests/Pest.php.dist` (copy as `tests/Pest.php`) with nothing to bind. Adjust `src` to the package's autoloaded directory. Add `pestphp/pest` as the only development dependency, at the major [Can this repository run Pest](#can-this-repository-run-pest) chose, and the script `"test": "pest"`. A library's tests exercise its public API; a test that needs a private method is usually asking for that method to be its own class.

## The two WordPress layers

| | Unit | Integration |
| --- | --- | --- |
| Boots | Composer's autoloader, Patchwork, the plugin's definition files | WordPress, through `wp-phpunit`, against a real test database |
| WordPress functions | Mocked per test with Brain Monkey | Real |
| Needs | PHP | PHP, MySQL, a WordPress core checkout |
| Speed | Milliseconds per test | The install runs at every process start; then fast |
| Base case | PHPUnit's `TestCase`, with Brain Monkey's `setUp()` and `tearDown()` around each test | `WP_UnitTestCase`, which rolls each test's database writes back |

The two run as separate processes. The bootstrap boots WordPress only for the Integration suite, so a unit test that reaches an unmocked WordPress function dies with an undefined-function error instead of quietly passing against a WordPress that happened to be loaded. The repository the Integration template is modeled on calls this second process `Feature`, and the shared Pest skill uses that name; the boundary is the same.

### Unit: Brain Monkey

`brain/monkey` (2.7.0, PHP 5.6 or later, built on Mockery and Patchwork) defines WordPress' hook functions and a few helpers (`add_action()`, `apply_filters()`, `did_action()`, `absint()`, `is_wp_error()`, `wp_json_encode()` and more) and lets a test mock any other function. The calls a unit test uses most:

```php
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;

Functions\when('get_option')->justReturn('yes');                   // stub: any call returns this
Functions\expect('update_option')->once()->with('my_key', 2);       // expectation: verified at tearDown
Functions\stubEscapeFunctions();                                     // esc_html(), esc_url(), ... return their argument, lightly escaped
Functions\stubTranslationFunctions();                                // __(), esc_html__(), _n(), ... return the text
Filters\expectApplied('my_plugin_label')->once()->andReturn('New');  // the filter fired, and what a callback would return
Actions\expectDone('my_plugin_saved')->once();
expect(has_action('init', 'my_plugin_register'))->toBeTruthy();     // after calling the function that registers it
```

**`with()` without a call-count is not a narrow mock.** `Functions\expect('update_option')->with('my_key', 2)` without `once()`, `twice()`, or `times(n)` also answers later calls to `update_option` that use other arguments. Pair `with(...)` with a call count (`->once()->with(...)`), or use `Functions\when(...)->justReturn(...)` when any arguments are fine. The same applies to `Filters\expectApplied` / `Actions\expectDone`.

**What it can see:** what the code under test does with what WordPress returns. Branches, formatting, escaping being applied, which filters and actions it fires, which hooks it registers, the arguments it passes to WordPress.

**What it cannot see:** what WordPress actually does. Whether the hook fires at the point the code assumes, whether a query returns what the mock returned, whether the REST route is reachable or answers anonymously, whether the option round-trips through the database, whether multisite switching is restored. A mock returns what the test told it to, so a unit test of code whose correctness lives in WordPress' behavior passes against a wrong assumption. That is what the integration layer is for.

Two mechanics the templates already handle: Patchwork is loaded before the plugin's files, so a test can also mock one of the plugin's own functions; and `tests/Pest.php` adds Brain Monkey's expectations to the assertion count, so a test whose only check is `Functions\expect(...)->once()` is not reported as risky. A function mocked in one test stays defined afterwards, as a stub that throws if called unmocked, so one test's mock never answers for another.

**Why not `10up/wp_mock`.** It is the common alternative and does the same job, but WP_Mock 1.1.1 requires `phpunit/phpunit ^9.6`, which only the unmaintained Pest 1 accepts, and PHP 7.4 to 8.x. Brain Monkey requires no PHPUnit at all, so it works under every Pest major and under any PHPUnit a repository that cannot run Pest is on.

### Integration: `wp-phpunit`

`wp-phpunit/wp-phpunit` is WordPress core's own PHPUnit library, published at WordPress' version numbers. It needs:

- **A database** that the test installer **drops and rebuilds on every run**. Create it once (`CREATE DATABASE <plugin>_tests`) and never point it at anything else; the template's bootstrap refuses to run when it matches the surrounding install's `DB_NAME`. Two runs against one database at the same time collide and produce failures that look like regressions, so never run two Integration processes at once.
- **Configuration**, in `tests/Integration/wp-tests-config.php`, every value from an environment variable with a default: `WP_TESTS_DB_NAME`, `WP_TESTS_DB_USER`, `WP_TESTS_DB_PASSWORD`, `WP_TESTS_DB_HOST`, `WP_TESTS_ABSPATH`. Set `WP_MULTISITE=1` to run against a network; code that switches sites needs it, because on a single site that branch never executes.
- **A WordPress core checkout.** By default, the install the checkout sits in (`<wordpress>/wp-content/plugins/<plugin>/`, five levels above `tests/Integration/`). Anywhere else, set `WP_TESTS_ABSPATH` to a core copy, for example one fetched with `wp core download --path=<dir>`. The test install uses that checkout's `wp-content`, so its must-use plugins load too; ordinary plugins load only if the bootstrap requires them. A plugin that depends on another requires that one first, in the same `muplugins_loaded` closure.
- **`yoast/phpunit-polyfills`**, which the WordPress test bootstrap refuses to run without, and which sets the PHPUnit ceiling in [Can this repository run Pest](#can-this-repository-run-pest).
- **An adapted test case.** WordPress' `expectDeprecated()`, which runs in every test's `set_up()`, still calls PHPUnit APIs removed in PHPUnit 10. `tests/Support/IntegrationTestCase.php` replaces it with the same hook wiring minus the annotation parsing, and empties `checkRequirements()`, whose docblock PHPUnit 11 and 12 misread as metadata.

Pin `wp-phpunit/wp-phpunit` to the WordPress minor the sites run (`wp core version`), so the test library matches the core it boots.

### Which layer a piece of code needs

Start with a unit test. Add an integration test when any of these is true, and when in doubt, when a bug in it would be invisible to a mock:

- It reads or writes the database: `WP_Query`, `get_posts()`, `$wpdb`, options, meta, transients whose persistence matters.
- Its correctness depends on hook **order or timing**: it assumes another callback already ran, or registers on a hook that may already have fired.
- It is a REST route, an admin-ajax handler, a shortcode or block render, a rewrite rule, a capability check, or anything WordPress dispatches to, where what matters is how WordPress calls it (permission callbacks, authentication, sanitization).
- It switches sites, users or locale, or otherwise changes global state that must be restored.
- It calls another plugin's API, where the other plugin's behavior is the risk.

A function that only transforms its input, formats output, or decides a branch from values WordPress returns is unit-test territory, even if it calls a dozen WordPress functions. Most code splits: unit tests for its branches, one integration test proving the wiring.

## Pinning legacy behavior before a change

Before changing code that has no tests, write **characterization tests**: tests that assert what the code does now, whether or not that is what it should do. They exist so the change shows up as a difference, intended or not.

1. **Find the observable behavior** of the code about to change: return values, output, what it writes (options, meta, rows, files), what hooks it registers and fires, what it sends. Include the inputs the change could plausibly affect, edges included (empty, missing, zero, the error path).
2. **Take the expected values from the code, not from intent.** Call it and record what it returns. For large or markup-heavy output, Pest's `toMatchSnapshot()` records the current output on the first run and compares against it afterwards; commit the snapshot.
3. **Prove each pin bites.** Change the code under test by hand (flip a condition, alter a string), run the test, see it fail, revert. Where a coverage driver is available, `vendor/bin/pest --mutate --path=<file>` does this systematically. A pin that does not fail when the behavior changes pins nothing.
4. **Name a pin for the behavior it observes**, in the same style as any other test: `it('returns an empty string for a post with no title')`. When the pinned behavior is believed to be wrong, the name says it is the current behavior and the test links the issue that will change it: `it('currently returns the raw title unescaped (#123)')`. Keep pins in the ordinary `tests/Unit` and `tests/Integration` directories; they become the suite, not a separate tier.
5. **Commit the pins before the change**, on their own, green against the unchanged code. The change's diff then shows every expectation it alters, and each altered expectation is either the intended change (updated in the same commit, with the reason in the message) or a regression.
6. **Then the follow-up.** Pinning covers what this change touches. Each repository's [full-coverage](#full-coverage) ticket covers the rest; note on it what this change pinned, and anything found uncovered nearby.

Where untested code cannot be loaded in isolation (definitions and registrations in one file, work done at include time, globals set on load), the smallest change that makes it loadable comes first, as its own commit, and is pinned by an integration test that loads the file the old way.

## Full coverage

"Bring test coverage to full" means the bar below (recorded while scoping UAMS-Web/uams-statamic#2834; UAMS-Web/uams-claude-skills#9). Every repository's coverage follow-up closes against this section. A bare line-% floor or a literal `100%` mutation score is not the bar.

### Population

First-party PHP under the suite's coverage `<source>` in `phpunit.xml` (typically `app/` or the package's `src/`), minus files the repository deliberately excludes from coverage and mutation because they have no hand-written control flow worth mutating (large generated enums, vendored copies of upstream, and similar). That list is the inventory the coverage ticket closes against. In a fork, population is **our modifications** only (see [Forks of vendor plugins](#forks-of-vendor-plugins)).

### Bar

Mutation-clean, not a line-% floor and not a literal `100%`. Line and statement coverage only answer whether a file was *touched*. Once the repository can run Pest with a coverage driver, the closeout bar is **mutation-clean** under Pest's `--mutate`:

1. Preflight the instrument so a fabricated `100.00%` is not mistaken for a pass (see [Preflight](#preflight)).
2. Mutate each class in the population (relative `--path`, class-scoped).
3. Triage every survivor into exactly one disposition:
   - **killed:** a test covers the branch;
   - **deleted:** dead code removed;
   - **equivalent:** no input can kill it; annotate with `@pest-mutate-ignore` and record the reason in the covering test's docblock.

**The criterion is *no unexplained survivors*, not a score.** A percentage collapses "unexamined gap" and "provably equivalent mutant" into one number. Chasing a literal `100%` pushes agents to delete defensive guards or invent circular assertions. Accepted equivalents stay; the test docblock states which survive and why.

Where this repository already has [`adversarial-review`](../rule-adversarial-review/SKILL.md), that file holds the review side of the triage. This section is still the definition of "full."

### Preflight

Before trusting a mutation score:

- `0 Mutations for 0 Files created` is a **setup failure**, not green: fix the invocation (`--path`, worktree sub-path, `covers()`, driver) and re-run.
- A mutant child that never launches can still be scored as killed. Confirm by inspection that mutant processes actually run (log growth, non-fabricated survivor output) before recording a pass.

### Closing record

When the coverage ticket closes, record on it:

- The population list, or how it is generated from that repository's `phpunit.xml` / Pest config (and any deliberate exclusions).
- Per class, or an equivalent rollup: the platform the score was taken on, mutant counts, and that every killable mutant is dead with equivalents explained.
- The standing pin-before-change rule stays in force for the life of the repository; closing the follow-up does not retire it.

### Until Pest can run

Repositories still on PHPUnit (`php-floor-spike`, `tests-move`, or any target that cannot yet run Pest) use this interim bar:

- Every file in the [population](#population) has characterization or production tests that pin observable behavior (the [pinning](#pinning-legacy-behavior-before-a-change) steps).
- Gaps are measured with PHPUnit's coverage driver (`vendor/bin/phpunit --coverage-text` or the repository's coverage script) and worked down in reviewable pull requests.
- The ticket records the population, what remains uncovered, and the constraint that blocks Pest.

The **mutation-clean** bar in [Bar](#bar) applies only after Pest and a coverage driver land; until then, do not claim `--mutate` results.

## Moving a PHPUnit suite to Pest
Pest runs PHPUnit test classes unchanged, so a move is gradual and never a rewrite:

1. Confirm the target major with [Can this repository run Pest](#can-this-repository-run-pest).
2. Add Pest (`composer require pestphp/pest --dev --with-all-dependencies`; remove an explicit `phpunit/phpunit` requirement first if it pins a major Pest cannot use) and add `tests/Pest.php`. Keep the existing `phpunit.xml`.
3. Run `vendor/bin/pest`. Every existing class runs as before; the counts must match the last PHPUnit run. Switch the Composer `test` script, and the job that runs it before a pull request, to Pest.
4. From then on, new tests are written in Pest, and existing classes are converted as they are touched, one at a time.
5. Add `pest` to the repository's profiles in UAMS-Web/uams-claude-skills's `manifest.json`, so the Pest skills arrive with the next sync.

`pestphp/pest-plugin-drift` adds `vendor/bin/pest --drift`, which rewrites class-based tests into Pest function style as a mechanical first pass. Use the plugin major that matches Pest's (`^3.0`, `^4.1` or `^5.0` as of 2026-10-02). Its output still needs the audit a hand conversion gets.

The per-test conversion process (time the original, convert and audit, mutation-test, review, one test at a time) and the Pest syntax guide are the `phpunit-to-pest` and `pest-testing` skills, which this repository receives once its manifest entry carries the `pest` profile.

## Running the suite before a pull request

This repository is public, so Actions minutes are not billed and the suite may run in Actions on every pull request: a workflow that runs `composer install` and the repository's `composer test` script. A WordPress Integration suite there needs a MySQL service container and the `WP_TESTS_*` variables set in the job. Run the suite locally before opening the pull request either way.

## Forks of vendor plugins

In a fork of a vendor plugin (`uamswp-seopress`, `uamswp-seopress-pro`, `UAMSWP-minervakb`), **coverage means our modifications**, not the vendor's code. The repository's `AGENTS.md` project section records which code is the vendor's and which is ours; read it first, and add it there if it is missing.

- **Tests** go in `tests/`, which upstream does not have or does not ship. If upstream ships its own tests, keep ours in a directory of our own and point our testsuites only at it.
- **Coverage** is scoped in `phpunit.xml`: under PHPUnit 10+ / Pest, `<source><include>` lists only the directories and files that hold our code; under PHPUnit 9, the same list lives in `<coverage><include>`. Upstream code our code calls is loaded by the bootstrap but not measured.
- **Mutation testing** is scoped the same way, with `--path` naming our directories.
- **Where our modification is an edit inside an upstream file**, coverage cannot be scoped to it: including the file measures the vendor's lines too. Test the modified function's behavior anyway, and record on the coverage ticket which upstream files carry our edits and that their percentage includes upstream lines. Moving the modification into a file of our own, called from the upstream file through a hook or one line, makes it testable and measurable on its own, and keeps upstream updates mergeable.
- **The harness itself** must survive an upstream update: nothing in `tests/`, `phpunit.xml` or our `composer.json` keys should need re-applying after a merge. Never edit an upstream file to make it testable without recording it as a modification.

## Further reading

- The `pest-testing`, `phpunit-to-pest` and `pcov-setup` skills (Pest syntax, PHPUnit-to-Pest conversion, and the coverage driver that `--coverage` and `--mutate` need) arrive once this repository's manifest entry carries the `pest` profile.

- [`code-quality`](../code-quality/SKILL.md): static analysis, which analyzes `tests/` too.
- [`adversarial-review`](../rule-adversarial-review/SKILL.md): the review a change gets before it ships, which reads its tests.
