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
