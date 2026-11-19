# UAMSWP-Find-a-Doc

The WordPress plugin behind Find-a-Doc: the provider, location, area of expertise, condition, treatment and clinical resource content types, the templates that display them, the Doc Profile Editor and Doc Profile Admin roles with their editor groups, and the sync that keeps Google Business Profile listings in step with providers and locations.

**Documentation is in the [wiki](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki):** setting up the Business Profile sync, editor groups, the WP-CLI commands, the reference pages and troubleshooting. This README keeps the requirements, installation and development notes.

## Requirements

- WordPress 6.8 or later and PHP 8.2 or later (the plugin header's `Requires at least` and `Requires PHP`).
- Advanced Custom Fields PRO, Advanced Custom Fields: Mask Field, Advanced Custom Fields: Extended and FacetWP. The plugin lists them as required and prompts for any that are missing (`required-plugins.php`).
- FacetWP - Alpha for UAMS ([`UAMS-Web/facetwp-alpha-uams`](https://github.com/UAMS-Web/facetwp-alpha-uams), v1.3.5.2 or later), which provides the alphabet facet on the provider and location lists. It is a private repository, so it is installed through UAMSWP-Pusher rather than from the prompt above, which does not list it.
- The UAMS 2020 theme ([`UAMS-Web/uams-2020`](https://github.com/UAMS-Web/uams-2020)), a Genesis child theme. The templates are written for it, and the style build imports its Sass.

## Installation

Install the plugin folder as `wp-content/plugins/UAMSWP-Find-a-Doc` and activate it under **Plugins**. The GitHub download carries only the files the plugin runs (see `.gitattributes`). To turn on the Business Profile sync afterwards, follow [Setting up Business Profile sync](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Setting-up-Business-Profile-sync) in the wiki.

## Amp orbs

On Debian 12 orbs, executable `.agents/setup` installs PHP 8.4 with the test extensions and PCOV, PHP 8.2 for floor linting, Composer 2.11.1, MariaDB, and Node 22 (matching CI). PHP and MariaDB use the available package patch releases; Node uses the available Node 22 patch release on the first install. Both Composer trees install from their lockfiles. npm installs with scripts disabled so the `preinstall` hook cannot rewrite the lockfile. No tracked dependency versions are changed.

Setup prepares WordPress in `/tmp/find-a-doc-wordpress` at the locked `wp-phpunit/wp-phpunit` version and the theme in `/tmp/find-a-doc-theme` at the commit pinned in CI. The theme receives Bootstrap 4.5.2, matching the style test. Login shells in this repository or its worktrees receive the `WP_TESTS_*` and `UAMS_2020_THEME` variables and a repository-scoped Node 22 path.

After activation and on wake, `.agents/resume` ensures the non-public `find-a-doc-db` service from `.amp/services.yaml` is running and checks its test connection. It never installs dependencies. The service binds only to loopback, creates the disposable `uamswp_find_a_doc_tests` database and passwordless `findadoc` user if absent, and grants that user access only to that database. Integration tests rebuild its tables: never use it for retained data, and do not run integration suites concurrently against it. Re-running setup does not drop the database.

Run `.agents/setup` to refresh dependencies or repair missing fixtures, then `.agents/resume` to check the database. In a new repository login shell, `composer test:unit`, `composer test:integration`, and `npm test` need no additional environment arguments. This provisions test prerequisites, not a populated WordPress site or a public preview.

## Editor groups

Editor groups let a Doc Profile Editor maintain a set of providers and locations. They moved to the wiki: [Giving editors access with editor groups](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Giving-editors-access-with-editor-groups) and [Roles and editor groups](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Roles-and-editor-groups).

## Tests

```bash
composer install
composer setup:integration   # the Integration suite's own Composer setup, in tests/Integration
composer test:unit           # Pest 5 with Brain Monkey: no WordPress, no database
composer test:integration    # PHPUnit 9.6 with wp-phpunit: needs a WordPress core tree and a test database
```

`composer test:unit` first runs `composer test:wiring` in a fresh Pest process,
then runs the callback suite. The wiring test under `tests/Wiring/` must observe
the first load of the content registration file; it cannot share a process with
callback tests that already loaded its global functions. A direct
`vendor/bin/pest` runs only the callback suite under `tests/Unit/`. Additional
arguments to `composer test:unit` apply only to that callback suite; the wiring
test always runs in full. Use `--ci` when validating registration snapshots so
a missing baseline fails instead of being created.

`composer test:integration` needs, before it runs:

- `composer setup:integration`, run once and again whenever `tests/Integration/composer.lock` changes.
- A MySQL server reachable at `127.0.0.1` as `root` with no password, or the `WP_TESTS_DB_HOST`, `WP_TESTS_DB_USER` and `WP_TESTS_DB_PASSWORD` variables set to match.
- An empty database named `uamswp_find_a_doc_tests` (or the name in `WP_TESTS_DB_NAME`), for example `CREATE DATABASE uamswp_find_a_doc_tests`. WordPress' test installer drops every table in it on each run, and the suite refuses to start when the name matches the `DB_NAME` of the WordPress install it sits in.
- A WordPress install five directories above `tests/Integration` (this checkout inside `wp-content/plugins`), or `WP_TESTS_ABSPATH` pointing at one.

The supported PHP floor is 8.2 (`require.php` in `composer.json` and `Requires PHP` in the plugin header). Development and tests run on PHP 8.4 (`config.platform.php` is `8.4.27`), which Pest 5 needs. The Integration suite installs separately because WordPress' test suite supports PHPUnit 9 only; see the `tests` skill for the layout and for the environment variables it reads (`WP_TESTS_ABSPATH`, `WP_TESTS_DB_NAME`).

### Style build test

`npm test` runs the gulp style task on the locked npm packages and compares the compiled CSS with `tests/build/app.golden.css`. The Sass imports the `uams-2020` theme, so point `UAMS_2020_THEME` at a checkout of `UAMS-Web/uams-2020` that has `bootstrap@4.5.2` in its `node_modules`. The test builds in a temporary directory and never writes `assets/css/app.css`. After an intended change to the build or its packages, run it once with `UPDATE_GOLDEN=1` and review the golden-file diff. CI pins the theme to the commit the golden file was recorded against.

## Continuous integration

Pull requests are gated by the GitHub Actions workflow in [`.github/workflows/ci.yml`](.github/workflows/ci.yml). It runs Composer validation, the synced-files, emoji and aggregate-needs checks, a lint of every shipped file on PHP 8.2, Pint, Rector (dry-run), PHPStan and the Pest Unit suite on every pull request and on pushes to `master`, `dev` and `staging`. The final `CI` job needs every other job, so a red or skipped job fails the workflow. The `Attribution footer` and `Closing keywords` pull-request-body checks come from their own workflows.

## Google Business Profile sync

Saving a provider or a location queues an update of its Google Business Profile listings. The documentation moved to the wiki: [Setting up Business Profile sync](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Setting-up-Business-Profile-sync), [Syncing listings on demand](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Syncing-listings-on-demand), the [Business Profile sync reference](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Business-Profile-sync) and [Troubleshooting](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/wiki/Troubleshooting).

## Credits

Developed and maintained by UAMS Web Services.

Developers:

- Josh Daugherty
- Todd McKee
- Brent Passmore
