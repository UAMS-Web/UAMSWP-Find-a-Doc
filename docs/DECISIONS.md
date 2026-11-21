# Decision log

The canonical record of why the project is the way it is: the reasoning and the rejected alternatives behind decisions that have closed. It guards against silently reopening a settled decision.

**Consult it before acting.** Before proposing a change, reopening a question or picking an approach, check here first. The path under consideration may already be a decided-and-recorded one, or an explicitly rejected one. If it is, follow the record, or make the case to revisit it.

**When a decision closes, record the verdict in both places.** Capture the what, the why and the rejected alternatives here (the cross-ticket index) and in the GitHub issue's `## Decision` section (the per-ticket record), keeping the two consistent. Put the full reasoning in the relevant `docs/` companion when the decision touches a documented area. The issue tracker stays the system of record for a single ticket's status.

## How to add an entry

Entries run oldest first, one per closed decision, so a new entry is appended to the end of the file. Keep it terse; the companion or the issue carries the depth.

Every entry heading is `## YYYY-MM-DD`, a space, and a short title. Leave exactly one blank line above each heading. The check (`decision-log` skill) fails on a heading dated earlier than the one before it, and on a heading that sits directly under the previous line.

```markdown
## YYYY-MM-DD <short decision title> (<#issue or area>)

**Decision.** <what was decided, in a sentence or two>

**Why.** <the reasoning that settled it>

**Rejected.** <the alternatives considered, and why each lost>

**Companion.** <link to the docs/ companion or the GitHub issue holding the full reasoning>
```

## 2026-11-02 Close and keep a Business Profile listing after 14 days unpublished or orphaned (#930)

**Decision.** Every orphaned listing is marked permanently closed (`openInfo.status = CLOSED_PERMANENTLY`) with its website, description, phone, labels, additional categories and URL attribute cleared. It stays on the account and can be reopened. Nothing is deleted, for providers and locations alike. The sync acts on its own, and only after the listing has stayed unpublished or orphaned for 14 continuous days; republishing, or restoring the association, clears the record and restarts the period. The cases are: a provider or location profile unpublished; a provider removed from a location (that one listing only); and a store code that no longer matches because a slug changed, once listings are matched by a stored Google location ID. Posts already unpublished when this ships start their period at deploy.

**Why.** An unpublish is two different events that look alike to the sync: an editor drafting a profile and republishing an hour later, and an organization retiring a provider or a clinic. Acting on the status change alone closes live listings during routine editing. A grace period separates the two without a person confirming each retirement. Closing keeps the sync's rule that it never deletes, and the closure is reversible. No documented API method releases a listing without deleting or transferring it, and what an API delete leaves on Maps is unmeasured (#990), so providers are closed and kept like locations until it is.

**Rejected.** Flag only: outdated listings stay live indefinitely and cleanup is manual. Delete automatically: final, loses reviews and history, and contradicts the never-deletes rule. Close and strip on the status change alone: closes live listings when an editor sets a draft. A person confirming every retirement: leaves a backlog of unactioned flags.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#930](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/930); build in #991 (match by stored location ID) and #992 (orphan report and the 14-day close).

## 2026-11-03 Dismiss the decode-uri-component alert instead of replacing the media-query step (#1017)

**Decision.** Dependabot alert 63 on `decode-uri-component` is dismissed as not used. `gulp-group-css-media-queries` and the `css` chain stay for now. A later replacement of the media-query step is its own deliberate change, bundled with the browser comparison of #963, and the golden CSS file is then stored one rule per line (minified CSS with a newline after each closing brace, applied the same way to the test's output).

**Why.** A real `gulp` run loads neither `source-map-resolve` nor `decode-uri-component` (0 requires, with `sass` loaded once as the control), because `group-css-media-queries` calls the stringifier without the sourcemap option, and the build's only input is this repository's own SCSS. An `overrides` entry to 0.5.x breaks `source-map-resolve` (the fixed releases are ES modules only) without failing any test. Each replacement plugin changes the cascade order of the compiled CSS, since the current step moves every `@media` block after all plain rules.

**Rejected.** An npm override to 0.5.x: breaks the call. `postcss-combine-media-query`: breaks the mobile-first order (2 certain flips, 52 candidates). Removing the grouping step: 1 certain flip and 300 candidates against the golden file. `postcss-sort-media-queries` now: 3 certain flips (`.image` `flex-basis` at 1200 to 1294px), 6 unchecked candidates, deferred to the deliberate change. A formatter-based or one-line golden file: a new dependency, or unreadable diffs.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1017](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1017).

## 2026-11-06 Use schema-valid inputs for metadata mutation contracts (#904)

**Decision.** Use the committed ACF schema's valid values, including ordinary absent-field results, as the input boundary for provider and location metadata mutation contracts. Audit the three discarded external calls before proposing exclusions. This decision authorizes neither mutation ignores nor production deletions and does not establish that discarded calls are effect-free.

**Why.** Tests should preserve the supported editing model rather than turn malformed-value coercion into a requirement. Input validity and externally observable effects are separate questions, so a discarded return value is not sufficient evidence of equivalence.

**Rejected.** Treating arbitrary filter-altered types as required metadata inputs, pinning accidental loose-comparison behavior, and excluding every surviving discarded expression without examining its dependencies.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#904](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/904).

## 2026-11-11 Apply the remove-ontology rule when any of a provider's regions is listed (#1132)

**Decision.** The remove-ontology rule applies to a provider when at least one of the provider's regions is in the rule's region list, even if the provider also has a region that is not listed. `remove_service_lines` keeps its current check, since a provider carries a single service line. The implementation is #1185.

**Why.** This is the behavior the rule was meant to have: a provider associated with a listed region is a provider the rule is about. The current helper matches only when the provider has exactly one distinct region, so a provider in a listed and an unlisted region shows the ontology the rule hides for everyone else in that region.

**Rejected.** All of the provider's regions must be listed: it keeps the current non-match for a provider in a listed and an unlisted region. Only the primary (first) location's region: it ignores every other location the provider has, so a provider in a listed region at a non-primary location would still show the ontology.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1132](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1132); build in [UAMS-Web/UAMSWP-Find-a-Doc#1185](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1185).

## 2026-11-13 Annotate the two discarded phone formatter calls as equivalent (#904)

**Decision.** The discarded `format_phone_us($location_phone)` and `format_phone_dash($location_phone)` calls in the location payload builder in `includes/class.fad-custom-post.php` are annotated with `@pest-mutate-ignore: RemoveFunctionCall`, citing the effect-free finding on #904. Production code is unchanged. The scope is those two calls only; the discarded `get_field('location_image_override_parent_featured', ...)` read stays a mutation candidate.

**Why.** The audit on #904 found that the theme implementations of both formatters only transform a local value: no output, no application hooks, no persistence and no change to their input, across eleven controls on PHP 8.4. With the results discarded, removing either call cannot change what the payload holds. Those two calls were the last survivors in the file.

**Rejected.** Deleting the two calls: a production deletion that #904's decisions did not authorize. Leaving them as recorded survivors in the closing record: the bar is no unexplained survivors, and the finding explains them.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#904](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/904); [UAMS-Web/UAMSWP-Find-a-Doc#1355](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/pull/1355).

## 2026-11-14 Build no field-group-against-table-JSON check before the custom tables retire (#1133)

**Decision.** No check of the ACF field groups against the table definition files (link 1) is built while ACF Custom Database Tables is on its way out (#1063). The drift (provider 73 columns implied against 53 committed, location 94 against 57) and the missing `acfcdt_manage_table_definition` on `group_uamswp_locations.json` are recorded as accepted. The link 2 check, hook and notice from #814 stay as they are. If the retirement slips far enough that the drift starts to matter, option 1 (a check that runs inside WordPress, as a WP-CLI command and a CI job) is the one to build.

**Why.** The check guards a storage layer that #1063 removes, and the drift has mostly left values readable: on a local install `location_appointments_query` has no column in `wp_uamswp_locations` and still reads correctly from postmeta. Building a WordPress-booting CI job (none runs the Integration suite today) for a layer on its way out costs more than it protects.

**Rejected.** Option 1 now, a check inside WordPress only: always agrees with the plugin, but it needs a new CI job and surfaces drift that has not caused a failure. Option 2, an approximation in the standalone script with a hardcoded type list: it can run in a hook but drifts from the plugin, and a hook that blocks on it would fail on the existing drift from the first commit.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1133](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1133); the retirement in [UAMS-Web/UAMSWP-Find-a-Doc#1063](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1063).

## 2026-11-14 Revert the appointments default and pre-select Yes only for a new location (#1383)

**Decision.** The `default_value` of `location_appointments_query` in `group_uamswp_locations.json` goes back to `""`, in its own small pull request. Pre-selecting Yes is done only for a new location, through an `acf/prepare_field` filter tracked as its own ticket (#1384), which has no read-time effect and does not depend on #1380 or #1063. #1380 stays open as a content decision only: which value each never-stored published location should hold.

**Why.** With a default of `1`, ACF applies it on read for a location with no stored value while ACF Custom Database Tables is active and does not when it is off, so the phone block for a never-stored published location changes at deploy and again when CDT is retired. The edit screen also pre-selects Yes on existing never-stored locations, so a save would write `1` unchosen. An empty default keeps output at deploy equal to production in both states.

**Rejected.** Keeping the default and settling #1380 first: it writes an answer to every never-stored location before deploy, a content decision this fix should not force. Re-adding the JSON default in #1063: it brings back the pre-select on existing locations.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1383](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1383) (its `## Decision` section); the new-location filter in [UAMS-Web/UAMSWP-Find-a-Doc#1384](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1384).

## 2026-11-14 Turn on mutation testing in the shared caller with the two patch packages (#1171)

**Decision.** One pull request adopts `uams-web/patches-pest-plugin-mutate` (v1.4.0) and `uams-web/patches-patchwork` (v1.0.0), removes `mutate: false` from the shared caller, sets `mutate-paths` to the first-party PHP, and adds a `mutate` Composer script the chain runs. The packages are required in `require`, because `cweagans/composer-patches` applies a dependency's patches only from `packages` in the lock, and `cweagans/composer-patches` joins `require-dev` with its `allow-plugins` entry, since neither patch package requires it. The scope ruling of 2026-11-14 on #1171 named the two patch packages; the plugin and its helper package (`cweagans/composer-configurable-plugin`) were added with the owner's agreement, and the lock otherwise moves no version.

**Why.** The 2026-11-13 decision left the job off because a whole-suite `composer mutate` scored a mutant child that died with `DatasetMissing` as killed. The patches (UAMS-Web/patches-pest-plugin-mutate#31) fix both defects, and with them the whole-suite form reports a planted survivor: `1 untested, 192 tested` on `templates/single-clinical-resource.php` with its `EqualToIdentical` annotation removed, against `192 tested` and 100.00% with it in place.

**Rejected.** A per-file map of source files to covering tests (the option the 2026-11-13 decision rejected): it needs upkeep that the whole-suite form does not. Holding the job off: it leaves pull requests ungated by mutation.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1171](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1171).

## 2026-11-14 Ship the interim `location` preview fix now and remove it at retirement (#1064)

**Decision.** `location` is added to `uamswp_fad_preview_saved_post_types()` now (#1391), so a Draft location previews with its saved fields until ACF Custom Database Tables (CDT) is retired. The filter and the list are removed in #1069, not kept.

**Why.** A Draft location previewed with `preview=true` renders empty for the same reason a Draft provider did (#1001), because `location` stores its values in a custom table (#1046). Kept after retirement, the filter would hand back the saved post's ID for every revision of a listed post type and undo ACF's own swap, so an unsaved edit would never show in a preview. That is read from `includes/class.fad-provider-preview.php`, not measured; #1069 records the check.

**Rejected.** Leaving `location` previews empty until the cutover: editors would work around it by editing the URL for an unknown time, since the cutover has no date. Shipping the fix and keeping the filter as a harmless no-op: it is not one.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1064](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1064) (its `## Decision` section); the fix in [UAMS-Web/UAMSWP-Find-a-Doc#1391](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1391) and its removal in [UAMS-Web/UAMSWP-Find-a-Doc#1069](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1069).

## 2026-11-17 Keep the 64-character store code guard, verify the limit, then decide ID-based codes (#1425)

**Decision.** The store code length guard (`STORE_CODE_MAX`, 64) stays as it is. Whether Google rejects a longer code is verified first, by one `validateOnly` create of a 65-character code in a non-production silo with the response recorded (#1426). Store codes built from provider and location post IDs instead of slugs are decided on that result and blocked by it (#1427); existing listings keep matching by their stored Google ID.

**Why.** On staging (plugin 2.5.0), 1 of 1,594 provider rows is over 64 characters (maximum 68) and no location row is, so the guard affects one listing. Google's reference states no maximum length for `storeCode`, and the 64 in #928 cites no Google document or recorded response, so a change built on it rests on an unverified figure.

**Rejected.** Shortening the slugs on the website: it changes public URLs and every code built from the slug for one affected row, and a later rename moves the code again. Building ID-based codes now: the limit it works around is not verified.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1425](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1425) (its `## Decision` section); the follow-ups [UAMS-Web/UAMSWP-Find-a-Doc#1426](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1426) and [UAMS-Web/UAMSWP-Find-a-Doc#1427](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1427).

## 2026-11-21 Build an ID-based store code only for a listing whose slug code is over 64 characters (#1427)

**Decision.** A provider or location store code built from slugs stays as it is when it is 64 characters or fewer. When it would be over 64, the code is built from the provider and location post IDs instead, in a form with no underscore so it cannot equal a slug code. No existing listing's code, matching or `websiteUri` changes. The build is #1496.

**Why.** Google rejects a store code over 64 characters with `STRING_TOO_LONG` and accepts 64 (#1426), and on staging 1 of 1,594 provider rows is over the limit, so the guard keeps one listing from ever being created. No update path sends `storeCode`, so a listing keeps the code it was created with, and the stored Google ID already carries matching across a slug change. Changing only the over-limit code fixes that listing and touches nothing else.

**Rejected.** Building nothing: the over-limit listing stays unlisted. ID-based codes for every listing: it changes the `utm_specs` value and so the `websiteUri` of every listing, breaks analytics continuity unless `utm_specs` is separated from the code, and a listing with no stored Google ID would be created again unless a backfill ran first with the old builder.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#1427](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1427) (its `## Decision` section); the build in [UAMS-Web/UAMSWP-Find-a-Doc#1496](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/1496).
