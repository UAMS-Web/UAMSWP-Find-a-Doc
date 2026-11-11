# UAMSWP-Find-a-Doc

## Amp orbs

On Debian 12 orbs, executable `.agents/setup` installs PHP 8.4 with the test extensions and PCOV, PHP 8.2 for floor linting, Composer 2.11.1, MariaDB, and Node 22 (matching CI). PHP and MariaDB use the available package patch releases; Node uses the available Node 22 patch release on the first install. Both Composer trees install from their lockfiles. npm installs with scripts disabled so the `preinstall` hook cannot rewrite the lockfile. No tracked dependency versions are changed.

Setup prepares WordPress in `/tmp/find-a-doc-wordpress` at the locked `wp-phpunit/wp-phpunit` version and the theme in `/tmp/find-a-doc-theme` at the commit pinned in CI. The theme receives Bootstrap 4.5.2, matching the style test. Login shells in this repository or its worktrees receive the `WP_TESTS_*` and `UAMS_2020_THEME` variables and a repository-scoped Node 22 path.

After activation and on wake, `.agents/resume` ensures the non-public `find-a-doc-db` service from `.amp/services.yaml` is running and checks its test connection. It never installs dependencies. The service binds only to loopback, creates the disposable `uamswp_find_a_doc_tests` database and passwordless `findadoc` user if absent, and grants that user access only to that database. Integration tests rebuild its tables: never use it for retained data, and do not run integration suites concurrently against it. Re-running setup does not drop the database.

Run `.agents/setup` to refresh dependencies or repair missing fixtures, then `.agents/resume` to check the database. In a new repository login shell, `composer test:unit`, `composer test:integration`, and `npm test` need no additional environment arguments. This provisions test prerequisites, not a populated WordPress site or a public preview.

## Editor groups

Doc Profile Editors (`doc_editor`) can edit a provider or location only when they are its author or a member of one of its **editor groups**. Editor groups are a private taxonomy on providers and locations (Providers > Editor Groups). Each group has a primary editor and a list of editors, all with the same rights: edit, hide, or unpublish the records in the group. Publishing stays with administrators, and so does deleting: Doc Profile Admins (`doc_admin`) can edit any provider or location but cannot delete one.

- **Assign a group to a record:** administrators and `doc_admin` tick it in the Editor Groups box on the provider or location edit screen (or in Quick Edit). `doc_editor` accounts see the box but cannot change it.
- **Groups for a service line:** edit the service line term (Providers > Service Lines) and pick its editor group. Every provider and location in that service line picks the group up when it is saved, and follows if its service line changes; groups added by hand on the record are kept. The Editor Groups screen has an "Apply service line mapping" button that does this for every existing record at once.
- **Groups without a service line** (department sections, stand-alone teams) are plain terms that are assigned by hand.

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

Saving a provider or a location queues an update of the matching Google Business Profile listings: a provider's own listings, and a location's own listing plus those of the providers who practice there. A listing that exists is updated, and one that does not exist is created. The settings screen is under Find-a-Doc Settings > Business Profile Sync.

**Credentials.** Define these three constants in `wp-config.php`, or set them as environment variables of the same name. They are never stored in the database or shown on the settings screen.

```php
define('GOOGLE_BUSINESS_PROFILE_CLIENT_ID', '...');
define('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', '...');
define('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN', '...');
```

The refresh token needs the `https://www.googleapis.com/auth/business.manage` scope, and the Google Cloud project needs the My Business Account Management and My Business Business Information APIs enabled.

**Silos.** A location's brand fields (`schema_brandorg_query` and `schema_brandorg`) decide its silo: UAMS, Arkansas Children's or Baptist Health. Each silo maps to its own Google account group, and each is set to Off, Dry run or Live on the settings screen. Every silo starts in dry run, which logs what would change and writes nothing. Live writes only on production (`uamshealth.com`); anywhere else it runs as a dry run.

**What it writes.** Business name, address, coordinates, phone, website, categories and description, and for a location five true/false attributes (wheelchair accessible entrance and restroom, restroom, appointment required, online care), each only when it differs from the live listing and only when the site has a Yes or No answer. It never writes hours, photos, posts or Q&A, and never deletes a listing. A listing is updated only when it is in its silo's account group. A store code that exists anywhere else, including the closed, duplicate and non-clinical groups, is logged for review and never recreated.

**Exclusions.** A provider is left out when its own "excluded from bulk imports and updates" field is Yes. A location has two separate fields: one for the location's own listing, and one ("Should providers at this location be excluded...") that keeps every provider listing at that location from being created or updated. The second defaults to No, so existing locations behave as before.

**On demand.** `wp uamswp gbp sync --post=<id>` syncs one published provider or location now (a location also covers its child locations and the providers at them), and `wp uamswp gbp sync --all` syncs every one. `--silo=<name>` limits a run to `uams`, `arkansas-childrens` or `baptist-health`, and `--dry-run` runs every silo that is not off as a dry run, even a live silo on production. The command prints one line per listing (store code, silo, mode, action, reason) and a count per action, and exits 1 when any listing ends in error or review, when Google rate limits the run, or when the queued sync holds the run lock. It does not read or store the fingerprint the queue skips on.

**Activity log.** The settings screen keeps the 300 newest entries (listings already in sync are not logged) and shows 100. Entries for a listing left for review or that failed are also kept in a separate list, one per store code, up to 1,000, that routine entries cannot push out. A later run for the same store code that settles (nothing to change, skipped, or really created or updated) removes it. Both lists filter by action and silo.

**Category names** come from the `gmb_cat_provider` and `gmb_cat_location` taxonomies and are matched to Google's categories without regard to case. A name Google has no category for is left off the listing and shown in the log.

## Credits

Developed and maintained by UAMS Web Services.

Developers:

- Josh Daugherty
- Todd McKee
- Brent Passmore
