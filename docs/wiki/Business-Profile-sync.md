The Business Profile sync keeps Google Business Profile listings in step with the site's providers and locations. This page is the reference for what it writes, when it runs, how silos and exclusions decide what it touches, what the activity log keeps, and how it closes listings for retired profiles. To turn it on for the first time, follow [Setting up Business Profile sync](Setting-up-Business-Profile-sync.md); for the command line, see [Syncing listings on demand](Syncing-listings-on-demand.md).

Written against v2.5.0. Behavior marked *Unreleased* is on `master` after v2.5.0.

## What a sync covers

- **A provider** covers its own listings, one per published location it practices at.
- **A location** covers its own listing, its published child locations, and the published providers at any of them.
- **A listing that exists is updated, and one that does not exist is created.** Nothing is ever deleted.

## When it runs

- **On save.** Saving a provider or location, or publishing one by any route, queues a sync of everything that post covers. The first job runs about two minutes later, and each further post in the same save follows 30 seconds after the one before.
- **Only when it can do something:** the credentials are set, at least one silo is not Off, and the post is published.
- **A post with nothing new is not sent again.** Each job compares the post with what it last sent and makes no Google call when nothing changed.
- **On request.** *Queue a sync for all providers and locations* on the settings screen schedules every post, 15 seconds apart, and makes each one send again. *Unreleased:* it leaves out posts that have no listing in a silo that is on.
- **From WP-CLI.** `wp uamswp gbp sync` runs a sync at once; see [Syncing listings on demand](Syncing-listings-on-demand.md).
- **One run at a time.** Queued jobs, the command and the daily closing scan share one lock, held for up to five minutes. A queued job that finds the lock held tries again a minute later.

## Credentials

The sync reads three values, each from a constant in `wp-config.php` or, when no constant is defined, from an environment variable of the same name. They are never stored in the database and never shown on screen; the settings screen shows only whether each is set.

```php
define('GOOGLE_BUSINESS_PROFILE_CLIENT_ID', '<client id>');
define('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', '<client secret>');
define('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN', '<refresh token>');
```

The refresh token needs the `https://www.googleapis.com/auth/business.manage` scope. The sync calls the My Business Account Management and My Business Business Information APIs, and the older My Business API for photos, so the Google Cloud project needs each of them enabled.

## Silos and modes

A location's brand decides its silo, and a provider listing takes the silo of the location it is for.

| Brand field on the location | Silo |
| --- | --- |
| *Primary Brand Organization for This Entity* is the default UAMS brand | UAMS (`uams`) |
| Third-party brand organization is Arkansas Children's | Arkansas Children's (`arkansas-childrens`) |
| Third-party brand organization is Baptist Health | Baptist Health (`baptist-health`) |
| Any other third-party brand | none: logged as *No silo for this location's brand* |

Each silo maps to its own Google account groups, one for provider listings and one for location listings, and is set to one mode on the settings screen:

| Mode | Effect |
| --- | --- |
| Off | Nothing is sent. *Unreleased:* its skips are not written to the activity log. |
| Dry run (default) | Logs what would change and writes nothing. |
| Live | Writes to Google, on the production site only. Anywhere else a Live silo runs as a dry run, and the settings screen says which kind of site it is. |

A silo with no saved mode, or with a value that is not one of the three, is in dry run.

## What it writes

For every listing, each field only when it differs from the live listing (phone numbers are compared by digits, coordinates to five decimal places, categories by name):

| Field | Source |
| --- | --- |
| Business name | Provider: `UAMS Health - <provider> - <location>`. Location: its name, with the `UAMS Health - ` prefix when the location asks for it. |
| Address and coordinates | The location's address and map position. |
| Phone | The primary phone for the listing. |
| Website | The profile page on the site, with tracking parameters. |
| Categories | The primary and additional categories from the *Google Business Profile Categories* taxonomies. A provider with none gets `Doctor`. |
| Description | The profile's description. |

**Category names** are matched to Google's categories without regard to case. A name Google has no category for is left off and named in the activity log as *No Google category for: ...*.

**Photos.** The logo, the cover photo and, for a location, its additional photos are added to the listing. Each photo address is sent once per listing and remembered, so a sync does not send it again. Photos are only added: the sync never replaces or removes one.

**Location attributes.** Five true or false attributes, each sent only when it is missing from the listing or differs from it:

| Attribute | Location field | When the field is empty |
| --- | --- | --- |
| Wheelchair accessible entrance | *Accessibility: Wheelchair accessible entrance (for patients)* | sent as Yes |
| Wheelchair accessible restroom | *Accessibility: Wheelchair accessible restroom (for patients)* | sent as Yes |
| Restroom | *Amenities: Restroom (for patients)* | sent as Yes |
| Appointment required | *Health and safety: Appointment required* | not sent |
| Online care | *Does this location offer a telemedicine option?* | not sent; a location without telemedicine is never sent as No |

An answer of Not applicable is not sent. Provider listings get no attributes.

**Never written:** hours, posts, questions and answers, additional phone numbers and the opening date. Labels are written only when a listing is closed (see below).

## Which listings it touches

- **A listing is updated only when it is in its silo's account group** for its kind (provider or location).
- **A store code found anywhere else**, in the silo's other group, another silo's group or any other group on the account, is logged for review and never created again.
- **A store code on more than one listing**, or on a different listing from the one the post has stored, is logged for review.
- **A store code over 64 characters** is logged for review and nothing is sent.
- **A listing that cannot be created** because it has no name, an incomplete address (lines, city, state and ZIP are all needed) or no primary category Google knows is skipped with *Cannot create, missing ...*.

## Exclusions

| Field | Where | Effect |
| --- | --- | --- |
| *Should this provider be excluded from any bulk imports and updates in Google Business Profiles?* | provider | Yes: none of the provider's listings is created or updated. |
| *Should this location be excluded from any bulk imports and updates in Google Business Profiles?* | location | Yes: the location's own listing is left alone. |
| *Should providers at this location be excluded from any bulk imports and updates in Google Business Profiles?* | location | Yes: no provider listing at this location is created or updated. |

Each defaults to No. Residents, and providers with no published location, are also left out.

## Daily create budget

Google limits how many listings an account can create in a day, so the sync sends at most 80 creates per Pacific day. When the budget is used, or Google refuses a create for its daily limit, creates pause until Pacific midnight and the post is queued again after the reset. Updates are not paused.

**Rate limits.** A refusal with status 429 or 503 is a rate limit: the job retries after 60 seconds, doubling the wait each time up to an hour, and gives up after five attempts with *Gave up on post ... after 5 rate-limited attempts*.

## The activity log

The settings screen shows two lists, each filtered by action (review, error, create, update, skip) and by silo, with the columns Time, Store code, Silo, Mode, Action and Detail.

- **Recent activity** keeps the 300 newest entries and shows up to 100 of them. A listing that already matches is not logged.
- **Needs attention** keeps one entry per store code for a listing left for review or that failed, up to 1,000, and routine entries cannot push them out. A later run for the same store code that settles it removes the entry: nothing to change, skipped, created, updated or photos sent. A dry run does not settle an entry.

## The dashboard widget

*Business Profile Sync* on the WordPress Dashboard, for administrators, shows:

- **Status:** Off, Not configured, Running, Creates paused, Backed off (rate limit), Dry run or Idle.
- **Creates sent today**, out of the daily budget, with the time it resets and a chart of the last seven days. This is the site's own count; Google's quota figure is in the Google Cloud console.
- **Queue:** how many jobs are scheduled, due now, and waiting for the reset, with a chart of the next 24 hours.
- **Last 24 hours:** links to the created, updated, review and error entries, counted from the 300 newest log entries.

*Unreleased:* the counts under each chart are written out in words, and each chart is labeled at its ends.

## Closing listings for retired profiles

A listing whose profile has gone is not deleted. Once a day the sync looks for orphaned listings and, after 14 continuous days, marks each one permanently closed. The decision behind this is recorded in the [decision log](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/blob/master/docs/DECISIONS.md).

A listing is orphaned when:

- **its provider or location is unpublished** (draft, pending, private, scheduled or in the trash) and no published post stores the same listing; or
- **a provider no longer lists the location** the listing is for.

The count starts on the day the profile was unpublished or the association was removed. Republishing the profile, or adding the location back to the provider, clears it.

| Days orphaned | What happens |
| --- | --- |
| Under 14 | A review entry is logged when 14, 7, 3 or 1 days are left. |
| 14 or more, silo Off | A review entry: *Due to close ... but silo ... is off*. |
| 14 or more, dry run | Logged as *Dry run, would close ...*. Nothing is written. |
| 14 or more, Live | The listing is marked permanently closed; its website, description, phone numbers, labels and additional categories are cleared. Its name, address and primary category stay. |

A listing already closed on Google, or no longer on Google, is left alone. The scan can also be run at once with `wp uamswp gbp sync --orphans`.

**Note:** a profile moved to the Edit Flow *Inactive* status is not yet found by the daily scan, so its listing is not closed. See [UAMS-Web/UAMSWP-Find-a-Doc issues](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues) for the follow-up.
