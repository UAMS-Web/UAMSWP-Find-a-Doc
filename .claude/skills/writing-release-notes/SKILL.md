---
name: writing-release-notes
description: >-
  Project GitHub Release conventions for the UAMS-Web repositories: the em-dash release title
  (`vX.Y.Z — Theme`), a one-sentence milestone lead with an optional `**Breaking change** …`
  would-be-major-at-1.0 callout, who may cut a release (only with the operator's authorization,
  given in the session's own chat or recorded as a standing grant, and within its bounds), and a
  CLOSED, ordered heading vocabulary (`## Breaking changes`, `## What's new`, `## What's fixed`,
  `## Security`, `## Maintenance and tooling`) with one bullet per change formatted as
  `- <PR title> [#N](…/pull/N)`: using the API PR title (never the branch-name merge-commit
  subject), no `by @author`, inline code preserved. Covers the label-and-path routing rules, the
  bundled generator `gen_release_notes.py` (`--repo` required; per-repository routing by option),
  the 0.x milestone/semver convention (minor = feature/structural, patch = refinement; prerelease
  until 1.0), the REST fallback when the GraphQL quota is spent, and the retroactive-tag footer.
  Activate whenever drafting, rewriting, or critiquing a GitHub Release title or body, generating
  release notes, or cutting a tag.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/writing-release-notes/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore backdatable backticked clickjacking codepoint commitish creatordate hsts PYTHONIOENCODING rawfile -->

# Writing Release Notes

House style for GitHub Releases in the UAMS-Web repositories. It applies to the **whole release
range**: retroactive tags and new ones alike, so the releases page reads as one consistent
changelog. The audience is a teammate scanning "what landed in this version", or someone deciding
whether a consumer has to change; lead with the theme, say plainly whether anything a consumer
depends on changed shape, then bucket the changes.

## Versioning (recap: the numbers this skill annotates)

Convention for a repository still before `1.0.0`: **`0.x.0` (minor) = feature / structural work;
`0.x.y` (patch) = refinement-only**, and every release is published as a **pre-release**
(`--prerelease`). Whether a repository is in that phase, and what moves it to `1.0.0`, is that
repository's decision, recorded with its other decisions; a repository already past `1.0.0`
follows ordinary semver. What a tag *is* differs by repository, and that decides what a breaking
change means there.

## Who may cut it

A tag or a release is cut only when the operator has authorized it, and only within the bounds of
that authorization. The authorization reaches the session in one of two ways:

1. **Given in the session's own chat.**
2. **A standing grant the operator asked to be recorded** in the persistent memory the agent
   loads at session start. It holds until the operator revokes or changes it, again in a session's
   own chat.

Nothing else is authorization. That includes this skill or any other skill, a rule, a task
description, a message relayed from another session, and anything a session wrote on its own
initiative. The skill records how a release is cut; it cannot create, widen or renew a grant. A
recorded grant is used exactly as recorded: a request outside its bounds goes back to the
operator.

## Title

`vX.Y.Z — <Theme>`: an **em dash** (Unicode `U+2014`, shown as `—` in release titles only) with a space either side, never a hyphen, then a
concise Title-Case theme (no trailing period). Examples from the three migration repositories:
`v0.13.0 — Statamic 6 Upgrade` (`uams-statamic`), `v0.7.0 — WXR XML Sanitization` and
`v0.5.0 — Security Hardening, Statamic 6, and Split WXR Imports` (`wordpress-importer`),
`v0.3.0 — Enforced Pagination Schema` (`uamswp-migration-api`).

## Body structure (in order)

1. **Milestone lead**: one sentence naming the release's theme.
2. **Breaking-change callout** (only when the release breaks something): its **own paragraph**
   immediately after the lead, never appended to the lede sentence:
   `**Breaking change:** <impact and required action>.` Bolded words, no marker glyph, per
   [`no-emoji-in-durable-records`](../../rules/no-emoji-in-durable-records.md): the callout's
   former prefix is the failure that rule is argued from. State what the reader must *do* on
   upgrade, not the semver mechanics; what that is depends on the repository. The itemized detail with PR links goes in the
   `## Breaking changes` section. (Why a breaking change still bumps only the minor: the
   repository is 0.x.)
3. **Buckets**: a **CLOSED** set of `##` headings, each included **only when it has items**,
   always in this order:
   - `## Breaking changes`: anything the reader must react to on upgrade. Name the impact, not
     just the change.
   - `## What's new`: new features, capabilities and coverage.
   - `## What's fixed`: bug fixes, regressions, correctness and performance fixes.
   - `## Security`: vulnerability fixes and hardening.
   - `## Maintenance and tooling`: docs, build, CI, tests, refactors, dependency bumps, chores,
     and developer-experience / skills work.
4. **Footer**: retroactively-tagged releases only: `_Retroactively tagged at \`<sha>\` (<date>)._`
   Real-time releases omit it.

Do **not** invent headings outside this closed set. If something doesn't obviously fit, it is
*New*, *Fixed*, or *Maintenance and tooling*: decide by dominant intent. A repository may carry
fixed sections of its own around the buckets (`--note`, `--verification` and `--known-gaps` in the
generator below), and the generator emits each only when its option is passed.

## Line format

- One bullet per change: `- <PR title> [#N](https://github.com/UAMS-Web/<repo>/pull/N)`.
- **Use the PR title from the GitHub API**, **never the merge-commit subject**: those are branch
  slugs (`Merge pull request #N from UAMS-Web/…`), not titles. For a change pushed straight to
  `main` with no PR, use the commit subject (strip any Conventional-Commit prefix and `[skip ci]`
  litter) and link the **short commit SHA** instead of a PR number:
  ``- <title> [`a1b2c3d`](https://github.com/UAMS-Web/<repo>/commit/<sha>)``. Every bullet is
  linked: `[#N]` for a PR, a backticked short SHA for a direct commit.
- **No `by @author`.** These repositories are effectively single-author and everything posts
  under one account, per
  [`impersonal-voice-in-github-artifacts`](../../rules/impersonal-voice-in-github-artifacts.md),
  so attribution is noise (Statamic includes it for community credit; we don't need it).
- Preserve inline code in titles (`handles`, `paths`, package names).
- Don't restate the lead inside a bucket; don't add an `H1`.

## Prose style (titles, leads, and bullets)

- **Never use an ampersand (`&`)**: write "and". This applies to release titles, the lead, and
  every bullet (`Maps, Areas of Expertise, and Launch Prep`, not `Maps … & Launch Prep`).
- **Use the Oxford comma**: `redirects, nav visibility, and a render guard`, not
  `redirects, nav visibility and a render guard`.

## Routing (which bucket): labels, paths and the diff, never a title prefix

**A Conventional-Commit prefix is not consulted at all.** It cannot be: this skill and
[`writing-pull-requests`](../writing-pull-requests/SKILL.md) both forbid those prefixes on titles,
and a squash-merging repository's commit subject *is* the pull-request title. Measured in
`wordpress-importer` over `v0.17.0..v0.18.0` (`wordpress-importer#1093`), **0 of 20** subjects
carried one, so the branches that used to route on them never fired, and every tooling change fell
through to *What's new*. The same was found in `uams-statamic` (`uams-statamic#2465`). Routing
keys on signals the conventions actually produce.

Precedence, first match wins:

1. **`--security-item <N>`**: an explicit force. See below.
2. **A `security` label**, on the pull request or on the issue it closes. Labels are read from
   both, because one repository labels its issues and another its pull requests.
3. **The security word list**: `xss`, `ssrf`, `csrf`, `csp`, `hsts`, `xxe`, `redos`, `egress`,
   `nonce`, impersonation, sanitizing, clickjacking, `denylist`, SSL verification, security header,
   `X-Powered-By`, password protection, internal-network, "auth gate", "unauthenticated", or an
   "escape" of a script/Antlers/JSON-LD/HTML sink. Kept as a fallback for anything carrying no
   label. **It deliberately omits `secret` and `credential`**: documentation mentions both, and
   `uamswp-migration-api#214`, a note about a shell profile, landed in *Security* until they were
   removed. `--security-word` adds a word for a repository that wants one.
4. **A `build` / `documentation` / `local-ci` / `phpstan-cleanup` / `test-flake` label**, on the
   pull request or its closing issue. **This runs before the repair verb deliberately**: a label is
   a deliberate human signal and the verb is a heuristic. Two maintenance changes in
   `uams-statamic`'s 2026-09-14 range open with a fix verb (`Correct the ...`, `Stop two dictionary
   ...`) and are routed only because their label is consulted first.
5. **A repair verb opening the title**: `Fix`, `Resolve`, `Repair`, `Prevent`, `Guard`,
   `Restore`, `Correct`, `Harden`, `Stop`, `Avoid`. **This runs before paths deliberately**: a
   repair to a runbook or a README is still a fix, and consulting paths first would file it as
   tooling.
6. **Paths confined to tooling or prose.** The paths come from git, against the commit's first
   parent, so a merge commit yields the pull request's own diff and no API call is spent. The
   built-in set is the union of the three repositories' lists: `.claude/`, `.github/`,
   `.githooks/`, `.ai/`, `.cursor/`, `scripts/`, `docs/`, `patches/`, `tests/`, `AGENTS.md`,
   `CLAUDE.md`, `README.md`, `cspell.json`, `project-words.txt`, the Composer and npm manifests and
   lock files, `phpstan.neon.dist`, `phpunit.xml`, `rector.php`, `rector-sweep.php`, `pint.json`,
   and the dotfiles beside them. A change touching anything else is product work and is never
   claimed here. **`tests/` is in that set** because nothing under it ships; a repository whose
   tests are product work takes it out with `--product-path tests`, and `--tooling-path` adds a
   root. Paths are the signal that works on **history**: labels are mandatory going forward but
   were absent on all 20 pull requests in the range above.
7. **A test-dominant diff**: more lines added under `tests/` than anywhere else: is maintenance:
   the production edit is incidental to the coverage it enables. Safe only here, below the path
   rule, and only while tests count as tooling; a change touching a `--product-path` (a template
   or route directory, say) is product work however test-heavy it is.
8. **The closing issue's type**: `Bug` routes to *What's fixed*, `Feature` to *What's new*
   (`wordpress-importer#1112`). Over REST it is read from the `Closes #N` keyword in the
   pull-request body, so a pull request that closes an issue only through the sidebar yields
   nothing here and falls through; over GraphQL (`--api graphql`) it comes from the link GitHub
   maintains. A type that names no section, such as `Task`, is ignored rather than guessed at.
9. **Title-verb and topic heuristics** (`Migrate`, `Document`, `Adopt`, `Refactor`, `Refresh`,
   `Rework`, `Bump`, `Reformat`, `Consolidate`, `Deduplicate`, `Record`, `Port`; "update
   dependencies"; tests, cspell, coverage, mutation, local-ci, skills, worktrees;
   `--maintenance-word` adds one), then the default bucket, **`What's new`**.

**Why the type sits at 8 and not higher.** It says what the work *is*; steps 4, 6 and 7 say what
it is *in*, and what it is in wins. A bug fixed in a script is still tooling: measured in
`wordpress-importer`, moving this rule above them pulled two `v0.19.0` bullets out of *Maintenance
and tooling*. Bucketing by hand, apply it the same way: reach for the issue type only once the
labels and paths have said nothing.

**And a repair verb still outranks it**, because step 5 runs first. No release range measured so
far contains a `Feature` whose title opens with a repair word, so that order is pinned by a test
rather than chosen on evidence; if you meet one by hand, the type is the better signal.

A change with no signal at all still appears: in `What's new`: rather than being dropped.

### Forcing a security bullet: `--security-item`

**Nothing can infer a security fix whose title carries no keyword and whose files are prose.**
`wordpress-importer#1080` was exactly that: no label, and `README.md` plus `project-words.txt`, so
both the keyword list and the path rule say *not security*. It had to be moved by hand when
`v0.18.0` was cut, and there was no supported way to say so.

```
python3 .claude/skills/writing-release-notes/gen_release_notes.py v0.17.0 v0.18.0 \
    --repo UAMS-Web/<repo> \
    --lead "One-sentence milestone theme." \
    --security-item 1080
```

Repeatable, and it mirrors `--breaking-item`, which exists for the same reason. **Prefer applying
the `security` label at pull-request time**; the flag is for a range already merged.

## Generating the body: [`gen_release_notes.py`](gen_release_notes.py)

Don't hand-assemble the buckets: run the bundled generator. It is the same script in every
repository that carries this skill, at `.claude/skills/writing-release-notes/gen_release_notes.py`,
and it is run from the repository root. It reads first-parent git history for a ref range, pulls
each PR's title, body and labels **live from the GitHub API** (`gh`, REST by default), reads the
paths each change touched from git, and applies every rule above: prefix/`[skip ci]`/merge-hint
stripping, acronym casing (leaving backticked spans and joined names such as
`uams-web/wordpress-importer` or `ci:local` byte-identical), the routing cascade, `&`→and with the
Oxford comma, `[#N]` PR links, and backticked-short-SHA links for direct commits. It depends only
on `git`, `gh`, and Python 3: no other setup.

```
python3 .claude/skills/writing-release-notes/gen_release_notes.py <prev-tag> <new-tag> \
    --repo UAMS-Web/<repo> \
    --lead "One-sentence milestone theme." \
    --breaking "<impact and required action>." \
    --breaking-item "Rename \`foo\` -> \`bar\` [#123](https://github.com/UAMS-Web/<repo>/pull/123)." \
    > body.md
```

**`--repo` is required, and the generator refuses to run without it.** Two earlier copies of this
script each defaulted to their own repository, and run from a sibling without the flag they linked
every bullet to that repository's pull requests: real numbers, unrelated changes, no error, and
output that looked right. A default would fail the same way for whichever repository it did not
name, so there is none. Check the result anyway, because a mistyped `--repo` that is still
`OWNER/NAME` is accepted:

```bash
grep -oE 'https://github.com/[^)]+' body.md | grep -vc 'UAMS-Web/<repo>/'   # must be 0
grep -c 'UAMS-Web/<repo>/pull/' body.md                                   # must be > 0
```

The **editorial** parts it can't infer are flags: the one-sentence `--lead`, the `--breaking`
callout impact, any `--breaking-item` bullets (a pull request itemized there is left out of the
buckets automatically, and `--exclude N` does the same for any other), `--security-item`, and the
fixed sections some repositories carry: `--note` (a paragraph after the callout),
`--verification` (`## Verification at this tag`) and `--known-gaps` (`## Known gaps`), each
emitted only when passed, and `--footer`. `--help` lists all flags; `<prev-tag>` may be `-` for
the repo root (first release).

The **routing** parts that differ between repositories are flags too, so the script carries no
per-repository code: `--tooling-path ROOT` and `--product-path ROOT` adjust the path rule,
`--security-word` and `--maintenance-word` extend the word lists, `--skip REGEX` drops commit
subjects with no changelog value (only the generic merge and submodule noise is skipped by
default, because a skip that misfires drops a bullet without a trace), and `--api graphql` swaps
the per-pull-request REST reads for one batched GraphQL query that also sees sidebar-linked
closing issues. REST is the default because `gh`'s GraphQL path fails on an exhausted GraphQL
budget and is structurally unavailable in a Claude Code cloud session, while REST sits on its own
quota: see [`github-api-budget`](../../rules/github-api-budget.md).

Then review `body.md` before publishing it: the routing is a heuristic, and moving a bullet by
hand is expected. Create or edit the release with it (below).

**After touching the generator, run its tests**, from the repository root:

```bash
python3 .claude/skills/writing-release-notes/gen_release_notes.test.py
```

They are offline: the script runs over an empty range, and the routing, casing, rendering and
GitHub-reader cases drive the module directly, and they pin the output encoding
(`wordpress-importer#527`): the script forces UTF-8 on stdout, because Windows otherwise sizes it
to the console code page, and any codepoint outside it dies with a `UnicodeEncodeError`: after
doing all its work, leaving a traceback in the redirect where the notes should be. The tests
reproduce that on any platform with `PYTHONIOENCODING=cp1252`.

## Creating / editing releases with `gh`

Write the body to a file and pass `--notes-file` (never inline `--notes`: the bodies are dense
with backticks, `#`, and `U+2014` that the shell mangles). Keep `--prerelease` while the repository is before `1.0.0`.

```
gh release create vX.Y.Z --title 'vX.Y.Z — <Theme>' --notes-file notes.md --prerelease --verify-tag
gh release edit   vX.Y.Z --title 'vX.Y.Z — <Theme>' --notes-file notes.md
```

### `gh release create` is GraphQL-backed: reach for REST when that quota is gone

Both commands above die with the GraphQL budget, and **the two quotas fail independently**: board
work drains GraphQL across the organization, so the documented publish path can be dead at the
exact moment a release is due while 4,900+ REST calls sit idle. Observed cutting
`wordpress-importer` `v0.8.0` on 2026-08-03 with the GraphQL budget fully spent (0 of 5,000
remaining) while `core` still had 4,967:

```
GraphQL: API rate limit already exceeded for user ID 15675878.
```

Exit `1`, no release created. The REST equivalent published immediately, same credential, same
minute:

```bash
# Build the payload from the notes file rather than inline.
jq -n --arg tag vX.Y.Z --arg name 'vX.Y.Z — <Theme>' --rawfile body notes.md \
  '{tag_name:$tag, name:$name, body:$body, prerelease:true, draft:false}' > rel.json

gh api --method POST repos/{owner}/{repo}/releases --input rel.json
# {"tag_name":"vX.Y.Z","prerelease":true}
```

Pass the body **from a file** here too: `--input` takes JSON, so the body is a JSON string field,
and building it inline invites the same backtick/`#`/`U+2014` mangling `--notes-file` exists to avoid.

**REST has no `--verify-tag`, and the difference is destructive.** `gh release create
--verify-tag` refuses when the tag does not exist yet; `POST /releases` **creates** a missing tag
instead, pointing it at the default branch's current tip. So a typo'd or not-yet-pushed `tag_name`
silently produces a release tagged at whatever `main` happens to be, rather than failing. Push the
annotated tag first and confirm it resolves: `git rev-parse 'vX.Y.Z^{commit}'`: before posting.

**Confirm the result by reading it back** rather than trusting the write response: that is how
the `v0.8.0` failure was caught:

```bash
gh api repos/{owner}/{repo}/releases/tags/vX.Y.Z --jq '{name, prerelease, target_commitish}'
```

Keep `gh release create` as the ergonomic default; this is the fallback whenever the GraphQL
budget is spent. Note it does **not** rescue a **cloud session**: GraphQL is structurally
unavailable there, but so is release publishing by any route, REST included. See
[`github-api-budget`](../../rules/github-api-budget.md).

Retroactive tags: create an **annotated** tag stamped with the target commit's date so
`git tag --sort=creatordate` orders correctly: 
`GIT_COMMITTER_DATE="$(git log -1 --format=%cI <sha>)" git tag -a vX.Y.Z <sha> -m '…'`. GitHub's
`published_at` is still the publish time (not backdatable via any API); the API's `created_at`
already reflects the tagged commit's date.

## The DRY line

This is the standing statement of Release conventions: what a release says, and how its body is
generated and published. It composes with [`writing-commits`](../writing-commits/SKILL.md) and
[`writing-pull-requests`](../writing-pull-requests/SKILL.md) (the PR titles this skill renders
come from those), and with [`github-api-budget`](../../rules/github-api-budget.md), which owns
which API surface to spend. Whether a change is right, and which breaking classes exist, stays
with the repository's own decisions record. The `gh` mechanics live in that tool; don't restate
them. *When* to cut: each business day at 10:00 Central when there is unreleased work, a breaking
change included (it ships at that cut and leads the notes): is the
[`cutting-releases`](../../rules/cutting-releases.md) rule.

