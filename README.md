# UAMSWP-Find-a-Doc

## Editor groups

Doc Profile Editors (`doc_editor`) can edit a provider or location only when they are its author or a member of one of its **editor groups**. Editor groups are a private taxonomy on providers and locations (Providers > Editor Groups). Each group has a primary editor and a list of editors, all with the same rights: edit, hide, or unpublish the records in the group. Deleting and publishing stay with `doc_admin` and administrators.

- **Assign a group to a record:** administrators and `doc_admin` tick it in the Editor Groups box on the provider or location edit screen (or in Quick Edit). `doc_editor` accounts see the box but cannot change it.
- **Groups for a service line:** edit the service line term (Providers > Service Lines) and pick its editor group. Every provider and location in that service line picks the group up when it is saved, and follows if its service line changes; groups added by hand on the record are kept. The Editor Groups screen has an "Apply service line mapping" button that does this for every existing record at once.
- **Groups without a service line** (department sections, stand-alone teams) are plain terms that are assigned by hand.

## Tests

```bash
composer install
composer test:unit
```

The supported PHP floor is 7.4, so this repository uses PHPUnit 9 from the shared `tests` skill templates (Pest requires PHP 8.2+).

## Continuous integration

Pull requests are gated by the GitHub Actions workflow in [`.github/workflows/ci.yml`](.github/workflows/ci.yml). It runs Pint, Rector (dry-run), PHPStan, and the Unit suite on every push and pull request. A red check fails the workflow.

## Google Business Profile sync

Saving a provider, or a location that providers practice at, queues an update of the matching Google Business Profile listings. A listing that exists is updated, and one that does not exist is created. The settings screen is under Find-a-Doc Settings > Business Profile Sync.

**Credentials.** Define these three constants in `wp-config.php`, or set them as environment variables of the same name. They are never stored in the database or shown on the settings screen.

```php
define('GOOGLE_BUSINESS_PROFILE_CLIENT_ID', '...');
define('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', '...');
define('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN', '...');
```

The refresh token needs the `https://www.googleapis.com/auth/business.manage` scope, and the Google Cloud project needs the My Business Account Management and My Business Business Information APIs enabled.

**Silos.** A location's brand fields (`schema_brandorg_query` and `schema_brandorg`) decide its silo: UAMS, Arkansas Children's or Baptist Health. Each silo maps to its own Google account group, and each is set to Off, Dry run or Live on the settings screen. Every silo starts in dry run, which logs what would change and writes nothing. Live writes only on production (`uamshealth.com`); anywhere else it runs as a dry run.

**What it writes.** Business name, address, coordinates, phone, website, categories and description. It never writes hours, photos, posts, Q&A or attributes, and never deletes a listing. A listing is updated only when it is in its silo's account group. A store code that exists anywhere else, including the closed, duplicate and non-clinical groups, is logged for review and never recreated.

**Category names** come from the `gmb_cat_provider` taxonomy and are matched to Google's categories without regard to case. A name Google has no category for is left off the listing and shown in the log.
