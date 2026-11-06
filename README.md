# UAMSWP-Find-a-Doc

## Amp orbs

On Debian 12 orbs, executable `.agents/setup` installs PHP 8.4 with the test extensions and PCOV, PHP 8.2 for floor linting, Composer 2.11.1, MariaDB, and Node 22 (matching CI). PHP and MariaDB use the available package patch releases; Node uses the available Node 22 patch release on the first install. Both Composer trees install from their lockfiles. npm installs with scripts disabled so the `preinstall` hook cannot rewrite the lockfile. No tracked dependency versions are changed.

Setup prepares WordPress in `/tmp/find-a-doc-wordpress` at the locked `wp-phpunit/wp-phpunit` version and the theme in `/tmp/find-a-doc-theme` at the commit pinned in CI. The theme receives Bootstrap 4.5.2, matching the style test. Login shells in this repository or its worktrees receive the `WP_TESTS_*` and `UAMS_2020_THEME` variables and a repository-scoped Node 22 path.

After activation and on wake, `.agents/resume` ensures the non-public `find-a-doc-db` service from `.amp/services.yaml` is running and checks its test connection. It never installs dependencies. The service binds only to loopback, creates the disposable `uamswp_find_a_doc_tests` database and passwordless `findadoc` user if absent, and grants that user access only to that database. Integration tests rebuild its tables: never use it for retained data, and do not run integration suites concurrently against it. Re-running setup does not drop the database.

Run `.agents/setup` to refresh dependencies or repair missing fixtures, then `.agents/resume` to check the database. In a new repository login shell, `composer test:unit`, `composer test:integration`, and `npm test` need no additional environment arguments. This provisions test prerequisites, not a populated WordPress site or a public preview.

## Editor groups

Doc Profile Editors (`doc_editor`) can edit a provider or location only when they are its author or a member of one of its **editor groups**. Editor groups are a private taxonomy on providers and locations (Providers > Editor Groups). Each group has a primary editor and a list of editors, all with the same rights: edit, hide, or unpublish the records in the group. Deleting and publishing stay with `doc_admin` and administrators.

- **Assign a group to a record:** administrators and `doc_admin` tick it in the Editor Groups box on the provider or location edit screen (or in Quick Edit). `doc_editor` accounts see the box but cannot change it.
- **Groups for a service line:** edit the service line term (Providers > Service Lines) and pick its editor group. Every provider and location in that service line picks the group up when it is saved, and follows if its service line changes; groups added by hand on the record are kept. The Editor Groups screen has an "Apply service line mapping" button that does this for every existing record at once.
- **Groups without a service line** (department sections, stand-alone teams) are plain terms that are assigned by hand.
