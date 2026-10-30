---
name: writing-pull-requests
description: >-
  Pull-request title and body conventions for the UAMS-Web repositories. The title is imperative
  verb-first, has no Conventional-Commit prefix and no internal-process references (`Wave N`/
  `Group X`/`Iteration N`/batch/merge-order hints), with correct acronym casing and inline-code
  handles; it renders verbatim into GitHub Release notes. The body: a one-paragraph lede opening
  with `Closes #N.`, optional `## Approach` / `## Commits` / `## Files` / `## Verification` /
  `## The bug` / `## Out of scope` / `## Follow-up` / `## Companion PRs` sections, a mandatory
  `## Test plan` GitHub task-list, `##`/`###` headings, `-` bullets with 2-space nesting, aggressive inline-code markup for paths/handles/packages, linked file paths
  whose target is the absolute branch URL
  (`[`path`](https://github.com/UAMS-Web/<repo>/blob/<branch>/path)`), and a canonical
  `Closes #N.` reference (top or bottom): one keyword per issue, with cross-repo references
  rendered as a backticked org/repo#N link, plus the closing-keyword traps that close a ticket
  nobody meant to close. Also covers the PR lifecycle: open as a draft until the branch is ready
  to validate, flip draft → ready when it is, assign an owner, state the merge order of companion
  PRs in the body, and run the repository's cspell check on new prose and identifiers before
  opening. Activate whenever drafting, rewriting, or critiquing a GitHub pull-request title or
  body in a UAMS-Web repository.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/writing-pull-requests/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore elif esac backticked fieldtype HSTS Invokable Larastan realpath resolv strstr tostring uncast unreviewed unticked WCAG -->

# Writing Pull Requests

This skill captures the house style for PR descriptions in the UAMS-Web repositories. The
audience is another engineer reviewing the change: explain the *why* before the *what*,
prefer specifics over generalities, and bound scope explicitly.

## Start from the template

**This skill is the source of truth; a template carries only the skeleton and a pointer back
here.** When the house style changes, change it here first and reconcile the template, never
the reverse, or the two silently diverge.

Where the repository has a `.github/pull_request_template.md`, it is the skeleton of this skill.
A PR opened in the web UI (or interactively with `gh pr create`, no body flag) gets it
pre-filled; **start from the same file**, so both paths produce the same shape: copy it, fill it
in, strip the `<!-- … -->` guidance, and pass the result to `--body-file`, which bypasses the
template. Where there is no template, build the body from the section inventory below and pass
it via `--body-file`; if a template is added later, reconcile it against this skill rather than
the reverse.

## Output format

When proposing a PR body, **present the final body as a single fenced Markdown code block** so
the user can copy it cleanly. The body uses GitHub-Flavored Markdown, never include an `H1`
(`#`) heading; GitHub renders the PR title separately.

When you create the PR yourself with `gh`, **write the body to a file and pass `--body-file`
from the start**, never inline the Markdown via `--body`. These bodies are dense with
backticks, `$`, `!`, backslashes, and fenced code blocks, every one of which the shell mangles
or errors on as an inline argument (the failure is intermittent, so inline `--body` looks fine
until a body finally trips it). Write it to a scratch file and
`gh pr create --base main --head <branch> --title '…' --body-file pr-body.md`; the same holds
for `gh pr edit <n> --body-file`.

## Draft state and merge order

Two lifecycle rules govern *when* a PR is opened as ready and *how* its merge is sequenced.
"Ready" is keyed on the repository's validation path, never on a green checks panel.

- **Open as draft unless it is ready to validate; flip draft → ready when it becomes ready.** A
  PR whose branch still needs work opens with `gh pr create --draft`; `gh pr ready <n>` flips it
  once the change is genuinely ready to validate. A docs-only PR: no code, test, or build
  surface for the gate to exercise (a `.claude/` skill or rule, a `docs/` edit): is ready on
  creation and opens non-draft. Never present a PR as ready-to-merge before its validation path
  can actually run against it, and never flip to ready while you know the branch is unfinished.
- **When a merge order is required, state it in the body: in the PR that must merge second.**
  File-disjoint PRs need no ordering, but when PRs must merge in sequence: a shared-surface
  branch before a dependent one, a submodule companion before its pointer bump, or a cross-repo
  pair: say so in `## Companion PRs`, naming each PR and the one-line reason it must come
  first. Put it on the *dependent* PR, where whoever is about to merge will read it, rather than
  only on the one that goes first. See **Companion PRs and merge order** below.

## Spell-check new words before opening

Where the repository runs cspell: a `cspell.json`, a `project-words.txt` dictionary, and a `spell` job in its CI chain: a word the checker does not recognize fails validation and forces a *separate* `project-words.txt` PR after the fact. Catch it in the PR that introduces the word instead. This applies to docs-only PRs too: the ones most likely to add new words, and the ones that open non-draft (above), so there is no draft window in which CI would otherwise catch it first.

If you have added or edited any prose or identifiers: comments, docs, test names, anything cspell scans : run the spell check before opening the PR and reconcile every flagged word. For each one, decide : do **not** reflexively allow-list:

- **A genuine misspelling** → fix it in source.
- **A British spelling in first-party prose** → fix it to American, per [`american-english-prose`](../rule-american-english-prose/SKILL.md), which owns the convention, its arbiter, and the reasons that justify overriding it in the dictionary. Keep British only where it is baked into something you do not control: a WordPress core hook name, a third-party option key.
- **A legitimate new term**: an identifier, proper noun, or coined word cspell cannot know: → add it to `project-words.txt`.

Adding to the dictionary is the last resort, not the default: a real misspelling allow-listed once stays wrong forever, and every stale entry weakens the check. **Prefer a reword to an allow-list entry.** `project-words.txt` is for identifiers, proper nouns and standardized data: `realpath` and `strstr` are in on that basis. Invented prose is not: `uncast` was rejected on [`UAMS-Web/uams-statamic#2079`](https://github.com/UAMS-Web/uams-statamic/pull/2079) and rewritten as "a value that was never cast", which cleared the gate and left the sentence better. An allow-list entry is permanent and unreviewed; a reword costs one sentence.

**When a PR body claims the spell check passed, name which command ran.** A repository can carry more than one entry point over different corpora, and a clean run over one is not evidence about the other.

## Assign yourself to every pull request you open

**Every pull request carries an assignee, and it is the developer responsible for landing it.** Not a reviewer, and not a nicety: it is the record that the branch **has** an owner, read by anyone looking at the tracker, independent of who validates it. Unassigned reads as nobody's, and that is the state worth being able to see. Assigning the ticket at take-up does the same for the issue: it puts on the artifact something that otherwise exists only in a channel message, so a reader arriving later can tell the work started without reconstructing it from a timeline.

```bash
gh api -X POST repos/UAMS-Web/<repo>/issues/<pr-number> -f 'assignees[]=<login>'
```

The pull-request number works on the `issues` endpoint: GitHub treats pull requests as issues for labels, assignees and comments, which is also why [`github-api-budget`](../rule-github-api-budget/SKILL.md) lists those under the issue recipes. **That endpoint replaces the assignee list; the sibling `…/issues/{n}/assignees` adds to it.** The two are easy to confuse and diverge only when a request omits somebody already assigned: the mechanics are in [`github-api-budget`](../rule-github-api-budget/SKILL.md).

## Title

The PR title is **public changelog copy**: it is rendered verbatim into the GitHub Release
notes (see [`writing-release-notes`](../writing-release-notes/SKILL.md)), so write it for a
reader skimming "what shipped," not for the branch.

- **Imperative, verb-first**, capitalized first word, **no trailing period**: 
  `Add a GPS fieldtype with a CP map picker`, not `gps fieldtype` or `Added GPS fieldtype.`. A
  bug fix may lead with the symptom or the fix (`Fix …`), consistent with
  [`writing-issues`](../writing-issues/SKILL.md).
- **No Conventional-Commit prefix.** `feat:`, `fix:`, `perf:`, `refactor:`, `chore:`, `docs:`,
  `build:`, `ci:`, `test:`, `style:`, and scoped forms like `feat(kb):`, belong on **commits**
  ([`writing-commits`](../writing-commits/SKILL.md)), never on the PR title; the release-note
  section heading already conveys the change type.
- **No internal-process references.** Strip `Wave N`, `Group X`, `Iteration N`, "disjoint
  group", `(batch N)`, `(core)`/`(remainder)`, merge-order hints (`(merge after #N)`), redundant
  parenthetical issue refs (`(#1054, #1055)`), and bookkeeping like "follow-up to PR #N": state
  the actual change. `Wave 86: replace Peak SEO with app-owned robots.txt + sitemap` →
  `Replace Peak SEO with an app-owned robots.txt and sitemap`.
- **Correct casing** for acronyms and proper nouns: GPS, JSON, JSON-LD, XML, WXR, CSS, SCSS, CP,
  RSS, SEO, SSRF, XSS, CSRF, CSP, HSTS, ReDoS, ACF, SQL, HTTP, HTTPS, URL, ID, PDF, CI, SSG,
  VS Code, Box.com, WordPress, Statamic, Laravel, Antlers, Bard, Vite, Composer, PHP, Pest,
  PHPUnit, PHPStan, Larastan, Rector, Pint, Herd, UAMS.
- **Inline code** for handles, paths, routes, class names, config keys, and packages
  (`all_fields`, `_thumbnail_id`, `POST /!/trigger/activate`, `statamic/cms`).
- **Never use an ampersand (`&`)**: write "and". **Use the Oxford comma.**
- Concise but clear: expand cryptic shorthand where the meaning isn't obvious.

## Opening (lede)

Every PR opens with a bare `Closes #N.` line immediately followed by a 1–3-sentence prose
paragraph: no `## Summary` heading. On larger PRs the paragraph may give way to a short
bullet list, but the close reference still leads.

The lede should answer "what changed, and why now?" in the space of a paragraph. Lead with
the user-visible effect or root cause, not the implementation.

## Sections

Use `##` for top-level sections, `###` for sub-sections inside them. The conventional section
inventory, in order of appearance:

- **`## The bug`**: fix PRs only; explains the root cause before the fix.
- **`## Approach`**: used when a resolution path was chosen over alternatives, or when
  reviewers need to see the design rationale. Often links to the issue-comment URL where the
  decision was hashed out (e.g. `[#726 (comment)](https://github.com/UAMS-Web/<repo>/issues/726#issuecomment-…)`).
- **`## Commits`**: used when the PR's structure maps cleanly to its commit list (esp.
  dep-bumps and "this PR has N commits because…" narratives). One line per commit:
  `` - `<short-sha>` <conventional-commit-subject> ``.
- **`## Files`** / **`## Files changed`** / **`## Files touched`**: itemized list of files
  with a one-line reason each. Standard bullet shape (absolute branch URL, see Inline code
  markup):
  `` - **edit** [`path/to/file.php`](https://github.com/UAMS-Web/<repo>/blob/<branch>/path/to/file.php): <reason>. ``
  (or `**add**`, `**delete**`, `**move**`). Pre-line a one-shot
  `git diff main..HEAD --stat → N files changed, X insertions(+), Y deletions(-).` summary
  when useful.
- **`## Verification`** / **`## Verified`**: empirical evidence collected before opening the
  PR: test output, browser smoke results, `curl` results, tables of measurements. State each
  item's result in the line itself: "all eight jobs passed", "none did", rather than marking
  it. Glyphs are barred from durable records by
  [`no-emoji-in-durable-records`](../rule-no-emoji-in-durable-records/SKILL.md), and a
  verification line that names its result is more useful than one that asserts it with a mark.
- **`## Out of scope`** / **`## What's *not* in this PR`**: first-class deferred-items
  section. Each item should link to the follow-up issue (or note it's worth filing).
- **`## Follow-up`**: items surfaced during the PR that deserve their own issue.
- **`## Rollout plan`**: when shipping behind a flag, in report-only mode, or in stages.
- **`## Companion PRs`**: sibling PRs in other UAMS-Web repositories: a content or assets
  submodule, the importer, the API it consumes. Link each by repo and PR number, and **state the
  merge ordering for every one of them**, not only the ones the branch depends on, since an
  independent companion is the one nothing goes red for. See **Companion PRs and merge order**
  below.
- **`## Test plan`**: universal closing section (see below).

Dep-bump PRs add their own conventional sections: `## Composer`, `## npm`, `## Patch set
changes` (with `### Removed:` / `### Added:` sub-sections), `## Docs`.

## Referencing related GitHub issues

Issue references are first-class and have a specific shape. Every PR should reference at
least the issue it closes; many also link blockers, follow-ups, and design-decision comments.

**Closing references**: the issue(s) this PR resolves:

- **Same repo, primary form:** `Closes #N.`, always a trailing period, always `#` prefix.
  Place it either as the very first line of the body (most common) **or** as the very last
  line. Pick one, not both.
- **Cross-repo close:** backticked org/repo path, linked to the GitHub URL:
  `` Closes [`UAMS-Web/wordpress-importer#9`](https://github.com/UAMS-Web/wordpress-importer/issues/9). ``
- **Multiple closes: repeat the keyword, one per issue.** `Closes #121, closes #122.` on one
  line, or a `Closes #N.` line each. **`Closes #N1, #N2, #N3.` does not work**: GitHub binds
  the keyword to the number immediately after it and to nothing else, so a comma-separated list
  closes the first issue and silently leaves the rest as plain mentions.
  - **Silently is the word that matters.** The body is well formed, every reference is real, the
    pull request merges, and the unclosed issues stay linked from the timeline, so they read as
    work nobody finished rather than as a reference that failed. Nothing on the pull request can
    catch it, because nothing about it is malformed.
  - Measured in `uamswp-migration-api` on #123, which opened `Closes #122, #121.` and merged as
    `972f1bf1f`: #122 closed with the merge, #121 did not and was closed by hand fifty seconds
    later. Both read closed today, so the states no longer show the defect and the timestamps
    are what remain of it (#124).
- `Resolves #N` is accepted as a synonym but `Closes` is the dominant form: prefer it.

**Cross-references**: issues the PR relates to but does not close:

- **Inline parenthetical:** `tracked in #N`, `tracked separately in #N`, `tracked upstream in
  #N`, `blocked by #N`, `dropped follow-up issue is #N (closed not-planned)`.
- **In `## Out of scope` / `## Follow-up` bullets:** end the bullet with `, tracked in #N.`
  or `; file as follow-up.` so reviewers can see at a glance whether the residual work has a
  home.
- **Design-decision links:** when a decision was made in an issue comment, link the comment
  anchor, not just the issue:
  `[#726 (comment)](https://github.com/UAMS-Web/<repo>/issues/726#issuecomment-4549211687)`.
- **Cross-repo refs without closing:** same backticked org/repo#N form as cross-repo closes,
  without the `Closes`/`Resolves` verb.

**Every phrasing above is safe because none of them contains a keyword at all. Preserve that property: do not reach for a negation.**

**Do not** bury close references inside `## Files` bullets or test-plan checkboxes: they
live at the top or bottom of the body where GitHub picks them up for the "linked issues"
sidebar.

### A closing keyword fires on the pair, not the sentence

GitHub parses `close`, `closes`, `closed`, `fix`, `fixes`, `fixed`, `resolve`, `resolves` and `resolved` followed by `#N` as a directive and acts on it at merge. It matches the keyword and the reference and never parses the sentence around them, so negation does not reach it. The sentence written to *prevent* a close performs one:

```
This does not close #999999.        closes it
Do NOT close #999999 with this.     closes it
Superseded; does not fix #999999.   closes it
```

Measured in `uams-statamic` on 2026-09-11: PR `#2384` merged at `00:22:37Z` and `#2113` closed at `00:22:38Z`, both the body and the squash commit opening with that exact sentence. A scan of both for a keyword adjacent to a reference returned one hit and nothing else, so there was no second candidate.

**It has already cost `wordpress-importer` a ticket too.** Verified against the API 2026-09-09:

```
PR #983 merged_at    2026-09-08T14:10:32Z
#702    closed       2026-09-08T14:10:34Z   <- two seconds later
#702    reopened     2026-09-08T14:11:10Z
PR #983 updated_at   2026-09-08T14:12:33Z   <- 83s after the reopen
```

`#702` is `hitl` / `action-flavor`: a deployment across 26 networks that no diff can satisfy, and it was closed by a sentence written to prevent exactly that. Reopening restored it; the board card had to be moved back by hand. **Which route fired is not recoverable**: the PR body was edited after the reopen, so its merge-time text is gone, and the timeline's `closed` event carries `commit_id: null` with the merger as actor, which is what a merge-triggered close looks like and does not discriminate between body and commit. What is certain is that the merge commit `821dfebe` is still in the tree and still carries the negated form.

**It applies to the squash commit message as well as the body.** Both carried the sentence on `uams-statamic#2384`, and a body-only habit catches half of it. The commit message is the half nobody re-reads before merging.

**Use a form with no keyword in it.** Every cross-reference form listed above is already safe: `tracked in #N`, `blocked by #N`, `leaves #N open`, `#N stays open for its remaining criteria`, `refs #N`, `related to #N`. Reach for one of those rather than for a negation, or name the reason without the number:

```
leaves #999999 open                 safe -- no keyword
#999999 stays open for its content  safe -- no keyword
does not close #999999              CLOSES IT
```

**A disclaimer is one of two ways prose closes a ticket by accident.** The other is *describing someone else's merge*. **Never write a closing keyword when describing someone else's merge.** GitHub does not read intent, so a sentence *describing* what another PR shipped closes the issue itself. Observed in `wordpress-importer` 2026-08-03: refresh #537 wrote "…which closes #6 and #461" about **PR #532**, and merging #537 closed **#6**: while #532 was still in draft, having failed local CI on a PHPStan error. Write **shipped**, **covered**, **delivered** or **landed** instead: `PR #532 shipped #6 and #461`. Prose that describes history must never act on it.

**"Resolved" is one of the nine keywords, not a substitute for one.** It is the trap sitting inside the fix: `resolved #6` closes #6 exactly as `closes #6` does, and it reads passive enough to feel safe. Pick a verb with no GitHub meaning at all rather than one that merely sounds like a description.

### Properties of the parse that have surprised people here

- **Only the first number after a keyword fires.** `closed #6 and #461` closes #6 and merely *references* #461, so a sentence can look half-broken and be exactly as dangerous. That is the only reason #461 survived `wordpress-importer#537`.
- **Commit messages compose into the squash body**, so this covers commits, not just the PR body: a squash merge builds its message from the branch's commits unless an explicit `commit_message` is supplied. `wordpress-importer#548` and `#549` each carried live keywords in commits alone.
- **A trailing colon and a cross-repo prefix both still fire.** GitHub accepts `Closes: #10` and `CLOSES: #10`, and closes across repositories on `KEYWORD OWNER/REPOSITORY#N`, so `PR #540 closed UAMS-Web/uams-statamic#1863` is a live directive against the sibling repo. The obvious pattern, `(clos|fix|resolv)\w*\s+#\d+`, matches neither and reports a clean scan on both. It is too narrow, and the widened form below is what to actually run.
- **The keyword must start a word.** Without a left boundary, `prefixes #2222` matched as `fixes #2222`. The `(^|[^[:alpha:]])` at the front of the pattern below closes that. The squash-subject shape `prefixes (#1111)` never matched in the first place, because the parenthesis sits where the pattern needs `#`.
- **The pair can form ACROSS A LINE BREAK, where no author wrote either half next to the other.** A newline is whitespace to the parser, so a line *ending* in a keyword is read together with a line *beginning* with a reference. The keyword need not be a verb anyone chose: in the one real instance it was a **cell value in a status column** and the reference was the **row label of the next row**, and column alignment put them adjacent.

  ```
  #999998  write the patch                       closed
  #999999  file the bug upstream                 retitled, action-flavor added
  ```

  That is `f12ba37e4`, the squash commit for `uams-statamic#2446`, whose body opens `Refs #2241. Deliberately closes nothing`. It closed `#2241` anyway, at `2026-09-15T00:45:32Z`. **This is strictly harder to see than the negated form, and the two established checks both miss it.** There is no keyword adjacent to a reference *on any line*, so the pair exists only in the joined text: inspecting either line alone shows nothing wrong, and a reader looking at the rendered table sees a tidy status column. Grepping the body for `Closes #N` cannot match, because the string is not in the source text; it is created by the join. And **`closingIssuesReferences` is EMPTY** for `#2446`, with `#2461` as a control returning `#2409 CLOSED` correctly: the pull-request-level linkage and the push-time commit scan are separate code paths, **so a pull request can close an issue its own `closingIssuesReferences` does not list.** **A blank line between the two does not help**: it is still only whitespace, and neither does indenting the reference. Run line by line, the prescribed pattern found **0** matches in that body; run on the same body with its newlines joined, it found **1**, `closed #2241`. The control is the same expression on an ordinary body such as `wordpress-importer#1180`'s, with `Closes #1095` on one line, which finds **1** either way (`wordpress-importer#1132`).

### No Markdown construct disarms a keyword, not a code span, not a fenced block

This section previously said a span around the keyword did, and that reading was built from `closingIssuesReferences`, which is the wrong instrument for the question. **Two code paths decide what closes, and they disagree by construction:** the pull-request linkage parses the **body as Markdown**, so it honors spans and fences; the push-time scan reads the **squash commit message as plain text**, where no such construct exists. A repository that squash-merges turns the body *into* that message, and every Markdown defense evaporates at exactly the moment it is needed.

**The table below is preserved because its observations are real, but read it as what the FIELD reports, never as what merging will do:**

| pull request | shape | the field links | what merging did |
| --- | --- | --- | --- |
| `UAMS-Web/wordpress-importer#1036` | line 1 is the keyword inside a span | nothing | **closed its target one second later, same commit** |
| `UAMS-Web/wordpress-importer#1039` | bare keyword | `#1028` | consistent |
| `UAMS-Web/wordpress-importer#1009` | a span on a *different* reference, bare keyword later | `#962` and `#1005` | consistent |
| `UAMS-Web/uamswp-migration-api#205` | 1 directive in prose, 13 in spans or a fence | only the prose one | not re-checked |

**The first row is a counter-example to the claim it was cited for, and it was the only load-bearing one.** Re-measured: `#1036`'s body carries exactly one keyword-adjacent pair, `` `Closes #1013.` `` inside a span on line 1; its squash commit message carries that line verbatim, span included; and `#1013` closed at `2026-09-09T21:19:55Z` by that same commit, one second after the merge. No other reference in the body could account for it. **`#1009` is not discriminating either**: it puts the span on a *reference* with a bare keyword elsewhere, so it never tests the keyword-span case at all.

**The same holds for fences, measured in `uams-statamic`.** `#2482`'s body carried two fenced closing directives naming a ticket it did not intend to close, its `closingIssuesReferences` read one ticket and was *correct about that one*, and merging closed the unlisted second ticket anyway. A populated, accurate-looking field is more dangerous than an empty one, because it closes the question.

**So rewording is the only remedy measured to hold.** Use a form with no keyword in it, or put the number first. Verify at the merge per [`pre-merge-check`](../rule-pre-merge-check/SKILL.md): read the field *and* re-scan the joined text, because neither instrument alone covers both code paths.

**The examples in this skill are protected by `#999999`: a number that does not resolve to an issue, and NOT by the code spans around them.** The spans are formatting. That distinction is the whole correction: a document existing to warn about this trap was relying on a defense that does not work. Where the prose needs a placeholder, write `#N` rather than a real issue number, so quoting or copying out of it cannot arm anything. Do the same wherever the failure is described.

### Scan before opening, and check after merging

Scan both the body and the branch commits before opening the PR, rather than trusting a read-through. **Join the lines first**, which is what makes a split directive visible:

```bash
pattern='(^|[^[:alpha:]])(clos|fix|resolv)[a-z]*:?[[:space:]]+([A-Za-z0-9._-]+/[A-Za-z0-9._-]+)?#[0-9]+'
tr '\n' ' ' < pr-body.md | grep -oEi "$pattern"
git log --format=%B origin/main..HEAD | tr '\n' ' ' | grep -oEi "$pattern"
```

Joining costs line numbers, so `-o` prints each hit instead, and a hit can now span what was a line break. Measured on macOS with both `ugrep 7.8.4` and BSD grep 2.6.0, which agree. Not yet measured under Git Bash on Windows.

Every hit must be a deliberate `Closes #N` naming a ticket this branch actually finishes; read each one. Whoever merges may run the same scan again and pass an explicit `commit_message`, but that is a human-in-the-loop check that has to succeed every time, forever, on the genre most likely to carry the pattern, and it only protects the merge path that session uses. Anyone merging through the GitHub UI gets the composed body and the default behavior.

**Point any check at the pull-request body, not at your branch commits, and that is measured rather than assumed.** `uams-statamic#2446`'s branch carried one substantive commit whose body holds no `#N` row at all, while the table lives in the pull-request body, so a check scanning `origin/main..HEAD` would have run clean over the only case it exists to catch. In a squash-merging repository the body *is* the commit message that lands, and a body is one API read away.

**After the merge, a check with no pattern in it catches what the scan misses.** A close that GitHub makes through the pull request's declared closing references records **no** `commit_id` on the issue's `closed` event. A close caused by keyword text in the merged commit records **that commit**. So any issue the squash body mentions whose `closed` event carries the merge SHA was closed by text, not by declaration:

```bash
repo='UAMS-Web/<repo>'   # quoted, like the SHA below: unquoted, the angle brackets are redirects
sha='<the merge commit SHA>'   # quoted: unquoted, the angle brackets are redirects
if ! sha=$(gh api "repos/$repo/commits/$sha" --jq .sha); then   # the full SHA, which is what the timeline records
  echo "the merge commit could not be read; nothing was checked"
elif ! body=$(gh api "repos/$repo/commits/$sha" --jq .commit.message); then
  echo "the merge commit's message could not be read; nothing was checked"
else
  checked=0; unread=0
  for n in $(printf '%s' "$body" | tr '\n' ' ' | grep -oE '(^|[^A-Za-z0-9._/-])#[0-9]+' | grep -oE '[0-9]+' | sort -un); do
    page=1; read_ok=1
    while :; do   # walk the pages explicitly; see github-api-budget step 6 for why not --paginate
      out=$(gh api "repos/$repo/issues/$n/timeline?per_page=100&page=$page" \
        --jq "(length | tostring), (.[] | select(.event == \"closed\" and .commit_id == \"$sha\") | \"#$n closed by the commit text\")") \
        || { echo "#$n: timeline page $page could not be read"; read_ok=0; break; }
      len=$(printf '%s\n' "$out" | head -n 1)
      case $len in ''|*[!0-9]*) echo "#$n: timeline page $page returned no page length"; read_ok=0; break ;; esac
      printf '%s\n' "$out" | tail -n +2
      [ "$len" -lt 100 ] && break   # raw page length, not the filtered count
      page=$((page + 1))
    done
    if [ "$read_ok" -eq 1 ]; then checked=$((checked + 1)); else unread=$((unread + 1)); fi
  done
  echo "$checked referenced issue(s) checked, $unread could not be read"
fi
```

Measured in both directions. Against `UAMS-Web/uams-statamic` at `f12ba37e4` it prints `#2241` (and `5 referenced issue(s) checked, 0 could not be read`). Against `wordpress-importer#1173`'s merge `893fda254`, which closed #1163 through its declared `Closes #1163.`, it prints only `4 referenced issue(s) checked, 0 could not be read`, and #1163's `closed` event carries `commit_id: null`. Given a SHA that does not exist, it prints `the merge commit could not be read; nothing was checked` (bash and zsh, macOS, 2026-10-02). **That discrimination rests on those two cases**, not on documented GitHub behavior. Any `closed by the commit text` line is an issue to reopen; a `could not be read` line means that issue, or the whole commit, was not checked. The last line counts the issues fully read and, separately, those that could not be, so an empty result is told apart from a run that read nothing, and a partial read is never reported as a complete one. It is an `if` rather than an `exit`, so pasting it into an interactive shell does not close the shell. It reads only same-repository references; a cross-repository mention needs the same loop against that repository.

**Four related shapes, each with its own `wordpress-importer` ticket:** a PR describing past merges (#550), grouping issues after one keyword (#648), a negated keyword still closing (#1029, #1035), and the split and boundary cases above (#1132).

## Companion PRs and merge order

**Cross-repo companion PRs** belong in a dedicated `## Companion PRs` section with a bullet per companion, naming what it does and the order: `` - [`UAMS-Web/<repo>#<n>`](https://github.com/UAMS-Web/<repo>/pull/<n>): <what it changes>; merge <which one> first, because <which side breaks otherwise>. ``

**State the ordering, and state which side breaks if it is ignored.** A change and its consumer are one logical unit split across two repositories, and nothing mechanical enforces the sequence: one repository's gate cannot see the other. So the body is the only place the constraint exists.

> **The dangerous case is the companion nothing forces you to finish.** When the dependent change stands on its own: it tolerates both the old and the new shape, a guard that makes the companion's repair merely *correct* rather than *required*, so no test goes red, nothing prompts the second merge, and a half-landed pair can sit indefinitely looking complete. Write the `## Test plan` item for the second half **whenever there is a companion at all**, dependent or not, and treat the pair as unfinished until both have merged.

## Bullets, headings, tables

- Bullets are `-` only (never `*`). Nest with a 2-space indent.
- **Bold-tag lead-ins** are the standard categorization pattern:
  `- **<noun phrase>:** <prose>`. Use them in `## Files`, `## Summary`, and any list whose
  items fall into clear buckets.
- **No em dashes in body prose.** Use a colon after the bold tag, commas or parentheses for
  asides, or two sentences. See [`no-em-dashes`](../rule-no-em-dashes/SKILL.md); the only
  carved-out em-dash title forms (release titles) live in
  [`writing-release-notes`](../writing-release-notes/SKILL.md).
- **Tables** are used when the data is naturally tabular (job matrices, env-var lists, grep
  audits, dep-bump diffs). Standard pipe-syntax, no fancy alignment beyond `---:` for
  right-aligning numeric columns.
- **Do not force-wrap** prose or bullets: let each paragraph/bullet run as one continuous
  line.

## Inline code markup

Backtick anything a developer would type, paste, or grep for:

- File paths: almost always linked, and the URL is the **absolute branch URL**, never a
  relative path: `` [`path/to/file.php`](https://github.com/UAMS-Web/<repo>/blob/<branch>/path/to/file.php) ``.
  Use `/blob/<branch>/…` for files (`/tree/<branch>/…` for directories), with `<branch>` set to
  the PR's head branch: **even for files already on `main`**. The displayed text stays the bare
  backticked path; only the target is absolute. Vendor and core file refs include line numbers,
  e.g. `…/InvokableValidationRule.php#L68-L76`, `…/class-wp-rest-server.php#L1068-L1084`.
- Class names, method names, function names: `App\Http\Middleware\SecurityHeaders`,
  `InvokableValidationRule::make()`, `Site::switchForRequest()`, `rest_do_request()`.
- Package handles: `statamic/cms`, `el-schneider/statamic-admin-bar`,
  `laravel/framework`, `php-stubs/wordpress-stubs`.
- Version numbers in dep bumps: `` `6.18.1` → `6.19.0` `` (backticked, joined by a literal
  `→` arrow).
- Constant and env var names: `STATAMIC_PHP_MEMORY_LIMIT`, `SECURITY_CSP_REPORT_ONLY`,
  `UAMSWP_MIGRATION_API_SECRET`, `RUN_REMOTE_CI`.
- Config keys, blueprint handles, field handles, hook names, meta keys: `visibility: read_only`,
  `type: person_name`, `weight_kg`, `per_page_max`, `_menu_item_object_id`.
- Antlers tags, JS identifiers, CLI flags, REST parameters: `{{ calculator_layout }}`,
  `--no-scripts`, `--dirty`, `?per_page=100`.
- Short shell commands: `composer audit`, `php artisan test --compact <path>`,
  `composer test --compact <path>`.

Fenced code blocks (with language tags `php`, `antlers`, `yaml`, `json`, `neon`, `bash`, etc.)
are reserved for **context**: vendor or core source being quoted, a response payload, YAML
config excerpts, Antlers template fragments. Do not use `diff`-tagged before/after blocks;
describe code changes in prose instead.

## `## Test plan` checklist

Every PR ends with a `## Test plan` GitHub task list. Conventions:

- `- [x]` = author has already verified locally; `- [ ]` = unverified, deferred to
  reviewer / CI / staging.
- **Always include a gate item** for non-trivial PRs: **and tick it honestly.** `- [x]` asserts *the author verified this*. Write `- [x]` only for a check you actually ran and can name the result of (`- [x] \`vendor/bin/pint --test\` clean on the changed paths.`); where a validating session runs the gate after the flip, the item stays unticked. **Name the commit the runner printed, not the branch**: that is the whole point of it printing one.

  **Say what the gate does not cover.** A docs-only or scripts-only change may have **zero** gate coverage, and a PR that implies otherwise is claiming a green means something it does not.
- Items describe **observable checks**, not implementation steps. Be concrete: name URLs,
  artisan commands, routes and request parameters, DOM observations, env-var settings to flip.
- Imperative mood, often a full sentence or two of context per item.
- Nest sub-checks with 2-space-indented `-` bullets directly under the parent checkbox.
- Include a regression / no-regression item when the change touches shared surface.
- Include a deferred / staging item under `[ ]` for anything the author can't verify locally
  (e.g. "Production / staging: confirm no `.env` has `STATAMIC_PROTECT_PASSWORD=secret`
  carried over", or anything needing a second site in a multisite network or the production
  data set).
- When the PR has a companion at all: a submodule pointer, a fixtures bump, a sibling-repo PR, or a release the consumer needs : include a `[ ]` item for the gated follow-through. Note CI is expected-red until the companion merges **when the branch depends on it**; when it does not, say so explicitly, because then nothing goes red and the item is the only thing left holding the bump. See **Companion PRs and merge order** above.

## Tone and voice

- Technical, prose-driven, detailed. Median body length ~400–700 words; small fixes are
  fine at ~150.
- Lead with the *why*, then the *what*. Fix PRs walk through the root cause before the fix.
- Specific over generic: concrete file paths, line numbers, env vars, URLs, measurements.
- Conversational where it helps (`foot-gun the moment someone flips it on`, `is *not* in this
  PR`) but never breezy. No emoji in prose. No exclamation marks.
- Self-aware about scope. Treat `## Out of scope` / `## Follow-up` as part of writing a good
  PR, not as a failure mode.
- Use `*emphasis*` sparingly, only on words that carry the sentence (`is *not* in this PR`,
  `the *first* slide is what most visitors see`).
- **Impersonal voice throughout**: every artifact posts under one account, so no `I`/`my`/`you`/`your`, and no vouching stance. See [`impersonal-voice-in-github-artifacts`](../rule-impersonal-voice-in-github-artifacts/SKILL.md).
- **No coordination plumbing**: nothing about the out-of-band channel sessions coordinate over: no machine aliases, no per-session labels, no liveness vocabulary. A reader of the tracker cannot check any of it. Keep the evidence and drop the proper nouns: see [`coordination-plumbing-stays-out-of-artifacts`](../rule-coordination-plumbing-stays-out-of-artifacts/SKILL.md) rather than restating it here.
- **No emoji**: not in the title, the body, or any comment. Use words: a bolded clause says what a
  glyph was standing in for, and a glyph breaks downstream where nothing can report it. See
  [`no-emoji-in-durable-records`](../rule-no-emoji-in-durable-records/SKILL.md).
- **No attribution footer.** Pull-request and issue bodies carry none, whatever attribution convention a session's environment supplies; the footer's glyph is one reason, and [`no-emoji-in-durable-records`](../rule-no-emoji-in-durable-records/SKILL.md) records the decision.
- Comments are governed separately. This skill covers a BODY; what a comment is held to, and the check to run on a draft before posting it, is [`writing-comments`](../writing-comments/SKILL.md).

## Example shape

A compact fix-PR template demonstrating the core conventions:

````markdown
```
Closes #734.

Disable Bootstrap auto-cycling on the multi-slide hero (`data-interval="false"`) while keeping `data-ride="carousel"` so indicator dots, arrow controls, and the keyboard handler stay bound. Addresses WCAG 2.2.2 (Pause, Stop, Hide) and the vestibular/cognitive concerns raised in #604.

## Files

- **edit** [`resources/views/page_builder/_hero.antlers.html`](https://github.com/UAMS-Web/uams-statamic/blob/734-hero-autoplay/resources/views/page_builder/_hero.antlers.html): set `data-interval="false"`; drop the unused reading-time math.
- **edit** [`resources/blueprints/page_builder/hero.yaml`](https://github.com/UAMS-Web/uams-statamic/blob/734-hero-autoplay/resources/blueprints/page_builder/hero.yaml): add editor-facing instructions explaining that the hero no longer auto-rotates.

## Test plan

- [ ] Local CI green (Pest, Pint, PHPStan): run by the validating session.
- [x] Headless-browser smoke on the homepage with a temporary second hero slide:
  - Markup renders `data-interval="false"`, `data-ride="carousel"`, both indicators, both arrow controls.
  - After 10s of no interaction, active slide stays at index 0.
  - Clicking the next arrow advances to slide 1.
- [ ] Production / staging: spot-check a page with multiple hero slides once deployed.

## Follow-up

The `<section>` carousel root has no `tabindex`, so keyboard users can't reach the arrow-key handler: `data-keyboard="true"` is effectively dead on the live page. Pre-existing, worth a separate issue.
```
````

(Lede uses the bare `Closes #N.` form. Linked file paths point at the absolute branch URL
(`…/blob/<branch>/path`), shown in full, because an example that demonstrates the opposite of
the rule is how the rule gets ignored. A colon after the bold tag separates label from prose. Test plan mixes
`[x]` for what the author ran and `[ ]` for what the validating session or a deploy will, with a
nested sub-check list; where the author runs the chain before flipping to ready, the gate item is
`[x]` and names the commit the runner printed. A short `## Follow-up` notes a residual gap worth
its own issue.)

