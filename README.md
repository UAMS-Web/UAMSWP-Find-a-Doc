# UAMSWP-Find-a-Doc

## Editor groups

Doc Profile Editors (`doc_editor`) can edit a provider or location only when they are its author or a member of one of its **editor groups**. Editor groups are a private taxonomy on providers and locations (Providers > Editor Groups). Each group has a primary editor and a list of editors, all with the same rights: edit, hide, or unpublish the records in the group. Deleting and publishing stay with `doc_admin` and administrators.

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

The supported PHP floor is 8.2 (`require.php` in `composer.json` and `Requires PHP` in the plugin header). Development and tests run on PHP 8.4 (`config.platform.php` is `8.4.27`), which Pest 5 needs. The Integration suite installs separately because WordPress' test suite supports PHPUnit 9 only; see the `tests` skill for the layout and for the environment variables it reads (`WP_TESTS_ABSPATH`, `WP_TESTS_DB_NAME`).

### Style build test

`npm test` runs the gulp style task on the locked npm packages and compares the compiled CSS with `tests/build/app.golden.css`. The Sass imports the `uams-2020` theme, so point `UAMS_2020_THEME` at a checkout of `UAMS-Web/uams-2020` that has `bootstrap@4.5.2` in its `node_modules`. The test builds in a temporary directory and never writes `assets/css/app.css`. After an intended change to the build or its packages, run it once with `UPDATE_GOLDEN=1` and review the golden-file diff. CI pins the theme to the commit the golden file was recorded against.

## Continuous integration

Pull requests are gated by the GitHub Actions workflow in [`.github/workflows/ci.yml`](.github/workflows/ci.yml). It runs Composer validation, the synced-files, emoji and aggregate-needs checks, a lint of every shipped file on PHP 8.2, Pint, Rector (dry-run), PHPStan and the Pest Unit suite on every pull request and on pushes to `master`, `dev` and `staging`. The final `CI` job needs every other job, so a red or skipped job fails the workflow. The `Attribution footer` and `Closing keywords` pull-request-body checks come from their own workflows.
