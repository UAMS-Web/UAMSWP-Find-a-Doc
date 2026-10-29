---
name: rule-github-api-budget
description: "Spend the REST quota to preserve the GraphQL one."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/github-api-budget.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — spend the REST quota to preserve the GraphQL one

GitHub meters **REST and GraphQL separately** (5,000 points per hour each). Use **REST** for anything it can do and keep GraphQL for work with no alternative, above all **Projects V2, which is GraphQL-only**. When GraphQL is gone, `gh issue create`, `gh issue edit` and `--assignee @me` fail too while REST still works; only board work has to wait.

**The quota is per user across the organization**: you can inherit an exhaustion, and yours strands others' board work on `UAMS-Web/projects/1`.

**Do not re-verify writes that succeeded**: an `item-edit` that exited `0` is the confirmation (step 1). Where a read-back is needed, read the one item by id as [`writing-issues`](../writing-issues/SKILL.md) shows; a board listing filtered by number matches other repositories.

**In a Claude Code cloud session GraphQL is unavailable** (`403`, whatever the token): board state cannot be verified there, so classify from what is readable and flag anything where it is decisive.

## How to apply

1. **Before a batch, read the quota from a real call's own headers, never `gh api rate_limit`**, in both pools:

   ```bash
   gh api graphql -f query='{viewer{login}}' -i 2>&1 | grep -iE '^x-ratelimit-(resource|used|remaining|reset)|"data"|"errors"'
   ```

   - **`X-Ratelimit-Resource`** names the pool (`core`, `graphql`, `search`); never compare across pools. `Reset` is a fixed timestamp.
   - **`gh api rate_limit` is inert for these tokens**: `used: 0` whatever was spent, and a `reset` always an hour away.
   - **On a confirmed GraphQL exhaustion, wait once until the header's `Reset`, then re-read.** No retry loop, no prescribed duration. A refused request does not increment `Used`.
   - **The headers reserve nothing** (the pool is shared), so a write's outcome comes from the write: `gh` exits `1` on a refusal, so `&&` or `set -e` protects you, and an exit `0` needs no read-back. A call merely returning is not an outcome. Where the status was lost (a pipe, a raw HTTP read), read the write back; if that is refused, report it **unverified**.
   - **A refused GraphQL call returns `HTTP 200 OK`** with `"errors"` (`graphql_rate_limit`) in the body; a served one has `"data"`. A status-line check records a refusal as success. Read the probe's output, never its exit status ([`reading-exit-status`](../rule-reading-exit-status/SKILL.md)).
   - **A refusal is not always exhaustion**: `retry-after` (with `Remaining` in the thousands) marks a burst limit, so wait briefly; without it, and `Used` at the limit, only `Reset` helps.

2. **Know which `gh` commands are GraphQL, reads as well as writes**: `gh issue create|edit|close|list|view`, `gh pr create|ready|list|view`, `gh search`, `gh release create|edit`, the `@me` lookup, and all of `gh project *` (no REST surface). During an exhaustion only `gh api <rest-path>` works, so triage stops too.
   - **A `gh` success does not prove the backend is healthy** (`gh pr view 999999 --json number` answers without a request), and counting only `gh api graphql` calls gives a floor. Take exit codes without a pipe.
   - **Publish a release through REST** (step 3) on a drained quota; it has no `--verify-tag` and creates a missing tag at the default branch tip, so push the tag first.
   - **A draft-to-ready flip has no working REST path**: `PATCH …/pulls/{n}` accepts `draft=false`, returns `200` and ignores it; only `gh pr ready` works. So **open a finished pull request ready** (`POST …/pulls` with `"draft": false`), and read back any write the API might ignore.

3. **Use the REST equivalents for issue work**:

   | Task | REST |
   | --- | --- |
   | Create | `gh api -X POST repos/{o}/{r}/issues -F title='…' -F body=@body.md -f 'labels[]=x' -f 'assignees[]=handle'` |
   | Edit body | `gh api -X PATCH repos/{o}/{r}/issues/{n} -F body=@body.md` |
   | Close | `gh api -X PATCH repos/{o}/{r}/issues/{n} -f state=closed -f state_reason=not_planned` (or `completed`) |
   | Comment | `gh api -X POST repos/{o}/{r}/issues/{n}/comments -F body=@c.md` |
   | Label | `gh api -X POST repos/{o}/{r}/issues/{n}/labels -f 'labels[]=x'` |
   | Issue type | `gh api -X PATCH repos/{o}/{r}/issues/{n} -f type=Feature` (`Bug` \| `Task`) |
   | Assign (add) | `gh api -X POST repos/{o}/{r}/issues/{n}/assignees -f 'assignees[]=handle'`: **adds**; pull requests assign here too |
   | Assign (replace) | `gh api -X POST repos/{o}/{r}/issues/{n} -f 'assignees[]=handle'`: **replaces** the list (a hand-off) |
   | Open a PR | `gh api -X POST repos/{o}/{r}/pulls --input -` with a JSON body (`title`, `head`, `base`, `body`, `draft`) |
   | Flip a PR to ready | **No REST equivalent**: `gh pr ready <n>` (GraphQL); `PATCH` ignores `draft`, see step 2 |
   | Merge a PR | `gh api -X PUT repos/{o}/{r}/pulls/{n}/merge -f merge_method=<method>` (see below) |
   | Publish a release | `gh api -X POST repos/{o}/{r}/releases --input rel.json` (`tag_name`, `name`, `body`, `prerelease`, `draft`) |

   - **The assignee endpoints do opposite things**: a hand-off uses the replace form, or the old assignee stays; add only for a real second assignee. Do not `DELETE` to undo a mistake: it removes whatever is assigned. Convention: [`writing-pull-requests`](../writing-pull-requests/SKILL.md).
   - **`repos/{o}/{r}/issues` and `search/issues` return pull requests too** (`pull_request` key); `gh issue list` and `gh search issues` drop them, blinding a duplicate check.
   - **`PUT` is the merge verb** (`gh pr merge` bills GraphQL; `--admin` adds nothing). It defaults to a merge commit, so pass the allowed method: `gh api repos/{o}/{r} --jq '[.allow_squash_merge, .allow_merge_commit, .allow_rebase_merge]'`.

   - **`.claude/settings.json` rules are case-sensitive literal prefix matches**, so quoting part of a prefix defeats one (`gh api "-X" POST …`). Quote only the tail: `gh api repos/{o}/{r}/issues'?state=open&page=1'`. An allowlist is never a security boundary (a `repos/` rule admits `/hooks`, `/transfer`, `/merges`, `git/refs`, `branches/{b}/protection`, `private=false`; a method flag after the path or `-x` slips past denies; a user's `Bash(gh api *)` widens it); branch protection is. Write procedures against the repository's settings file.

   - **Before a merge, enumerate closing keywords on both surfaces**: the body, and the branch commits a composed message may fold in (omitting `commit_message` makes GitHub compose one). Both routes fire and the selector is unknown. `-f commit_message=…` replaces the composed text, not the body, and vice versa: check each surface. A close event's `commit_id` is `null` when the body link fired. Use `HEAD` (another branch: its remote-tracking ref), not `origin/<branch>` (`<` redirects):

   ```bash
   gh api repos/{o}/{r}/pulls/{n} --jq '.body' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   git log origin/main..HEAD --format='%B' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   Prove the pattern can match first. This must print `Closes #1`, `close #2`, then `closed #2241` on one line, and nothing for the `fix:` line:

   ```bash
   printf 'Closes #1\ndoes not close #2\nfix: no number here\n| #2037 | write the patch | closed\n#2241 | file the bug upstream | retitled\n' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   Keep `-0777` (slurp) for the cross-line pair; use `perl`, not `grep` (BSD and GNU differ). Four details defeat a check by eye:

   - **The keyword must precede each number**: `Closes #80, #81` closes only #80.
   - **Negation does not disarm it**: `does not close #N` closes #N.
   - **A newline is whitespace**: keyword at a line's end plus `#N` on the next is live. `.claude/skills/writing-pull-requests/scripts/check-closing-keyword-across-lines.mjs` checks it.
   - **A code span or fence around the keyword disarms only the link**, not the squash-message scan ([`pre-merge-check`](../rule-pre-merge-check/SKILL.md) step 6): reword it. A nearby span disarms nothing; every directive counts, not only the first. Read what the pattern returns; do not count it.

4. **Dependencies and sub-issues have REST endpoints keyed on the database id**, not the issue number:

   ```bash
   id=$(gh api repos/{o}/{r}/issues/<blocker> --jq '.id')
   gh api -X POST repos/{o}/{r}/issues/<blocked>/dependencies/blocked_by -F issue_id=$id
   gh api -X POST repos/{o}/{r}/issues/<parent>/sub_issues -F sub_issue_id=$id
   gh api repos/{o}/{r}/issues/<blocked>/dependencies/blocked_by'?per_page=100' --jq 'length'
   gh api repos/{o}/{r}/issues/<parent>/sub_issues'?per_page=100' --jq 'length'
   ```

   A `Blocked by #N` line is prose, not an edge; an open blocker is not a live edge. `sub_issues` and `blocking` silently cap at 30 (`blocked_by` untested); always pass `per_page` and past 100 walk `&page=N` (step 6). `'?per_page=2' --include | grep -i '^link:'` shows `rel="last"` at any size.

5. **Always pass Markdown from a file**: `-F body=@body.md`. The shell mangles backticks, `$` and `!` inline ([`writing-issues`](../writing-issues/SKILL.md) mandates `--body-file`).

6. **Never trust a piped `--paginate` count, and do not use `--paginate` in a cloud session**: the proxy refuses the numeric-ID `Link` URL, `gh` prints page 1 and exits `1`, and a pipe hides it. Use `set -o pipefail` and walk `&page=N` until a short **raw** page (a `--jq` filter ends the walk early). Step 4's cap exits `0`.

7. **`search/issues` cannot phrase-match** (a quoted pair is an unordered AND) and indexes comments, so a written-up probe joins its own results. For a phrase count, enumerate and grep each body, with `per_page`, comparing items read to `total_count`. It answers from an **index**: a zero about a minutes-old artifact is unproven, so read recent candidates directly (`GET repos/{o}/{r}/issues/{n}`, `…/comments`). Never quote it as a phrase count (it over-counts and self-increments); use it only where age does not matter. Publish the control with the claim, and say whether each arm bears on the question, which a control does not show ([`an-empty-result-is-not-evidence`](../rule-an-empty-result-is-not-evidence/SKILL.md)).

8. **Do not contort a graph-shaped read into many REST calls**: one GraphQL query replacing a dozen round-trips is right. Board reads stay GraphQL where it is reachable.

9. **Measure a call's cost as a delta in `X-Ratelimit-Used` between two response headers**: an endpoint delta is always zero.

## The DRY line

This file owns **which API surface to spend**. Board mechanics live in [`writing-issues`](../writing-issues/SKILL.md); bounding a wait is [`long-running-commands`](../rule-long-running-commands/SKILL.md).

Evidence, measurements and history: [`github-api-budget`](../evidence-behind-rules/github-api-budget.md).
