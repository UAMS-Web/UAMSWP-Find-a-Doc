One entry per message or symptom, as it appears in the Business Profile Sync activity log, the WP-CLI output or the settings screen. Placeholders in angle brackets stand for the values the message carries.

Written against v2.5.0.

- [A credential shows Missing](#a-credential-shows-missing)
- [A silo set to Live only runs dry runs](#a-silo-set-to-live-only-runs-dry-runs)
- [No silo for this location's brand](#no-silo-for-this-locations-brand)
- [Silo has no provider or location account group](#silo-has-no-provider-or-location-account-group)
- [Cannot create, missing title, storefrontAddress, categories](#cannot-create-missing-title-storefrontaddress-categories)
- [Store code is over Google's limit of 64](#store-code-is-over-googles-limit-of-64)
- [Store code is already on another listing, or on several listings](#store-code-is-already-on-another-listing-or-on-several-listings)
- [Listing is in another group](#listing-is-in-another-group)
- [No Google category for](#no-google-category-for)
- [Creates paused, or daily create budget used](#creates-paused-or-daily-create-budget-used)
- [Gave up on post after 5 rate-limited attempts](#gave-up-on-post-after-5-rate-limited-attempts)
- [Google API failed](#google-api-failed)
- [A sync is already running](#a-sync-is-already-running)
- [A saved profile never reaches Google](#a-saved-profile-never-reaches-google)
- [A retired profile's listing stays open](#a-retired-profiles-listing-stays-open)

## A credential shows Missing

**Cause:** the settings screen cannot read that constant or environment variable. While any is missing, queued syncs do nothing and the dashboard widget says *Not configured*.

**Fix:** define all three in `wp-config.php` or the site's environment, as in [Setting up Business Profile sync](Setting-up-Business-Profile-sync.md), and reload the screen.

## A silo set to Live only runs dry runs

**Cause:** the site is not the production site. The settings screen says *This site is not production. A silo set to Live runs as a dry run here.*

**Fix:** none needed; this keeps test sites from writing to Google. Live writes happen only on production.

## No silo for this location's brand

**Cause:** the location uses a third-party brand organization other than Arkansas Children's or Baptist Health, so no silo owns its listings.

**Fix:** check the location's *Primary Brand Organization for This Entity* and third-party brand fields. A brand without a silo is not synced.

## Silo has no provider or location account group

Shown as *Silo `<silo>` has no provider account group* or *... location account group*.

**Cause:** the silo has no Google account group configured for that kind of listing.

**Fix:** a developer adds the group to the silo configuration; report it as an [issue](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues).

## Cannot create, missing title, storefrontAddress, categories

**Cause:** a new listing needs a name, a full address (street lines, city, state and ZIP) and a primary category Google knows. The message names what is missing.

**Fix:** complete the location's address, or give the profile a category from the *Google Business Profile Categories* taxonomy that matches one of Google's. The next save queues it again.

## Store code is over Google's limit of 64

Shown as *Store code `<code>` is `<n>` characters, over Google's limit of 64; nothing was sent*.

**Cause:** store codes are built from the profile's slugs, and this one is too long for Google.

**Fix:** shorten the provider or location slug, then save the profile.

## Store code is already on another listing, or on several listings

Shown as *Store code `<code>` is already on another listing than the stored `<name>`* or *Store code is on `<n>` listings*.

**Cause:** Google holds more than one listing for the store code, or a different listing from the one this profile has stored. The sync will not guess which one is right.

**Fix:** in Business Profile, decide which listing is the profile's and resolve the duplicate there. Then sync the profile again ([Syncing listings on demand](Syncing-listings-on-demand.md)).

## Listing is in another group

Shown as *Listing is in this silo's other group*, *Listing is in another silo's group (`<silo>`)* or *Listing is parked in `<group>`*.

**Cause:** the store code was found outside the account group the sync writes to for this silo and kind of listing, for example in a group kept for closed or duplicate listings. The sync never moves or recreates such a listing.

**Fix:** if the listing should be synced, move it to the right group in Business Profile. Otherwise leave it; the entry stays in *Needs attention* as a record.

## No Google category for

Shown in the Detail column as *No Google category for: `<names>`*.

**Cause:** a category name in the *Google Business Profile Categories* taxonomy does not match any of Google's categories, even ignoring case. It is left off the listing.

**Fix:** rename the term to Google's category name.

## Creates paused, or daily create budget used

Shown as *Creates paused until `<time>` (daily Google limit)*, *Daily create budget used, creates resume `<time>`* or *Google daily create limit reached, creates paused until `<time>`*.

**Cause:** 80 creates have been sent this Pacific day, or Google refused a create for its own daily limit.

**Fix:** none needed. Creates resume after Pacific midnight and the profiles are queued again automatically. Updates keep running meanwhile.

## Gave up on post after 5 rate-limited attempts

Shown as *Gave up on post `<id>` after 5 rate-limited attempts*, or in WP-CLI as *Stopped: Google is rate limiting this account. Run again later.*

**Cause:** Google answered 429 or 503 five times in a row for this post.

**Fix:** wait, then save the profile again or run [the command](Syncing-listings-on-demand.md) for it.

## Google API failed

Shown as *Google API `<METHOD>` failed (`<status>`): `<message>`*, or *Google token exchange failed (`<status>`): `<error>`*.

**Cause:** Google refused the request. The status and Google's message say why: 401 or a token exchange failure usually means a revoked or wrong refresh token; 403 means the account or API is not permitted; 404 means the listing is gone. A status of 0 means the request never reached Google, and the message is the site's own connection error.

**Fix:** act on Google's message. For a token failure, issue a new refresh token and update the constant.

## A sync is already running

Shown in WP-CLI as *A sync is already running. Try again when it finishes.*

**Cause:** a queued job, another command or the daily closing scan holds the run lock, which lasts at most five minutes.

**Fix:** run the command again in a few minutes. Queued jobs retry on their own.

## A saved profile never reaches Google

**Cause:** one of these:

- every silo is Off, or the credentials are missing, so nothing is queued;
- the profile is not published;
- the profile, or its location for provider listings, is excluded by one of the *excluded from any bulk imports and updates* fields;
- the provider is a resident, or has no published location;
- nothing changed since the last sync, so the job makes no call (the Recent activity list does not log a listing that already matches).

**Fix:** check each in turn. `wp uamswp gbp sync --post=<id> --dry-run` prints what the sync would do for the profile and why.

## A retired profile's listing stays open

**Cause:** the listing closes only after 14 continuous days orphaned, and only in a Live silo. A profile moved to the Edit Flow *Inactive* status is not found by the daily scan yet ([UAMS-Web/UAMSWP-Find-a-Doc#1432](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1432)).

**Fix:** check *Needs attention* for the listing's *days left* entry. For an Inactive profile, set it to Draft until the fix ships. See [Closing listings for retired profiles](Business-Profile-sync.md#closing-listings-for-retired-profiles).
