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
<!-- cspell:ignore triaging unrunnable -->
# Rule — spend the REST quota to preserve the GraphQL one

GitHub meters **REST and GraphQL separately**: 5,000 points per hour each, tracked independently. Prefer the **REST** endpoint for anything REST can do, and reserve GraphQL for the work that has no alternative. In practice that means one thing above all: **Projects V2 is GraphQL-only**, so every GraphQL call spent on an issue is a call the board may not have when you need it.

**Why this is a standing order.** The two quotas do not fail together, but `gh`'s ergonomic commands make it look as though they do. `gh issue create`, `gh issue edit`, and even resolving `--assignee @me` are all GraphQL-backed, so when the GraphQL quota is gone every one of them errors identically, while thousands of REST calls sit untouched. Board work genuinely has to wait for the reset; nothing else does.

Issues are tracked on the **shared** organization board (`UAMS-Web/projects/1`), and [`writing-issues`](../writing-issues/SKILL.md) adds every new ticket to it and sets its `Status`, both GraphQL calls. **The quota is metered per user across the whole organization, not per repository**, so it can be gone before you make a single call: exhaustion is a condition you inherit as often as one you cause. Treat the budget as a shared resource you can exhaust for someone else, not just for yourself.

**The commonest way to waste it is verifying writes that already succeeded.** A `gh project item-edit` that exited `0` *is* the confirmation (step 1 says why the exit status can be trusted). Re-reading the board to check is expensive, and the board is organization-level: a board listing returns items from every repository on it, so filtering that result by issue *number* alone silently matches another repository's issue that happens to share the number. Where a read-back is needed, read the one item by its id, as [`writing-issues`](../writing-issues/SKILL.md) shows.

**In a Claude Code cloud session, GraphQL is not merely scarce: it is structurally unavailable.** The session credential serves only a pinned set of PR-review operations; `{viewer{login}}` and every `projectV2` read return `403`. That is not a scope problem and a broader token does not fix it. Board membership therefore **cannot be verified** from a cloud session: classify from the signals you can read and flag anything where board state is decisive, rather than guessing.

## How to apply

1. **Check before a batch, not after it fails, and read the call's own headers, never `rate_limit`.** This applies to REST as much as to GraphQL: the endpoint under-reports both. Any run that will make more than a handful of calls starts by reading the rate-limit headers off a real call:

   ```bash
   gh api graphql -f query='{viewer{login}}' -i 2>&1 | grep -i '^x-ratelimit'
   ```

   ```
   X-Ratelimit-Resource: graphql   which meter this response was billed to
   X-Ratelimit-Used / -Remaining   the reliable exhaustion figures
   X-Ratelimit-Reset               a fixed timestamp that actually arrives
   ```

   **`X-Ratelimit-Resource` is the field that says which pool a set of headers describes**, and it is the one to read before comparing anything. A REST call returns `core`, a GraphQL call returns `graphql`, and a search returns `search`, so the header block answers "which counter is this?" without the caller having to infer it from what was called. The two pools must not be read across each other: `core` headers beside the `graphql` counter would disagree with nothing wrong anywhere.

   **`gh api rate_limit` is INERT for these tokens (in either pool) and inert is worse than wrong.** It answers `used: 0` regardless, so its agreement with reality is a coincidence of a fresh quota rather than a measurement. Measured 2026-09-05 by four sessions across three machines and two credentials during a genuine GraphQL exhaustion; both pools read at one instant on one machine (in `wordpress-importer`), against the same token:

   ```
                    gh api rate_limit                the call's own headers        ground truth
   .resources.graphql   used=0  remaining=5000       Used=5000  Remaining=0        call REFUSED
   .resources.core      used=0  remaining=5000       Used=837   Remaining=4163     calls SUCCEED
   ```

   The `graphql` row is settled by ground truth: a call was refused while the endpoint reported a full quota. The `core` row needs no exhaustion at all (837 requests had been spent and the endpoint reported none of them), which makes it the row a reader can reproduce today.

   **Not "the inverse."** An inverted gauge still carries its information: negate it and proceed. This one carries none. The two are indistinguishable during exhaustion, where `0 / 5000` happens to be the inverse of `5000 / 0`, and that is the only state anyone observes it in; a paired reading with quota restored is what separates them.

   **The reset is a hard wall at a fixed timestamp, and that model is correct, so long as it is read off the HEADER.** The endpoint's `reset` is not a timestamp receding toward you; it is recomputed as `now + 3600` on every read, so it moves away at exactly the rate you approach it (measured in `wordpress-importer`):

   ```
                    sample            +23 seconds
   header   reset   ...430,  45s out  ...430,  22s out   fixed, counts down
   endpoint reset   ...986, 3601s out ...009, 3601s out  always 3601s out
   ```

   So `sleep until now >= reset` terminates in 45 seconds on the header value and **never terminates** on the endpoint's. The instruction that once stood here (poll the endpoint at a sane interval) was not mistimed; it was unrunnable.

   **One alternative is not excluded and should not be silently resolved.** Nothing measured here distinguishes a GitHub-side behavior from a credential served through something that answers `rate_limit` differently from the rest of the API. The remedy is identical either way (the headers are truthful in both), which is exactly the condition under which an alternative nobody ruled out gets quietly dropped. It is recorded rather than settled.

   **Prescribe no recovery duration and no retry loop for a confirmed GraphQL exhaustion. Wait for the header's reset, once, then re-read.** Measured on macOS the same evening (`wordpress-importer`): a single sleep to `Reset + 8s` woke to `X-Ratelimit-Remaining: 4997`, and the board mutation that had been refused then applied and read back correctly. A retry loop fails until that same moment and spends REST quota discovering it. And the header's reset bounds the window it describes, not the next one: the refilled quota is shared and can go quickly: measured the same evening (`uamswp-migration-api`), a window reset, refilled to 4,993, and was exhausted again inside about four minutes. Do not treat one reading as a promise about the time after it, and never retry in a tight loop.

   **The headers are necessary and not sufficient, because the pool is shared.** They are accurate at the instant they are read and can be stale by the instant you act on them: measured the same evening, `X-Ratelimit-Remaining: 3060` licensed a mutation that was refused before it ran, the pool having gone to `0` in eighty seconds with the reset unmoved. Several sessions draw on one bucket, so a pre-check reserves nothing and bounds nothing.

   **So the outcome of a write comes from the write, never from having pre-checked the quota.** A `gh` exit status is a real outcome: `gh` exits `1` on a refusal and prints the error (measured in the same window, `gh api graphql -f query='{viewer{login}}'` exited **1**), so a `&&` chain or a `set -e` script driving `gh` is protected, and a board `item-edit` that exited `0` needs no read-back. What is **not** an outcome is the call having returned: a refused GraphQL mutation still returns, and reporting the intent as the outcome is how a card ends up recorded as moved when it did not move. Two board moves were announced as applied on one evening and had not been, in both cases because the outcome was taken from the call returning rather than from the exit status or a value read back. Where the exit status was lost (through a pipe, or because the HTTP response was read directly) **verify the write by reading it back.** When the read-back is itself refused, report the write as unverified rather than as applied: that is the honest state and it is not the same as failed: the mutation may well have landed.

   **A refused GraphQL call returns `HTTP 200 OK`. The error is in the response body, not the status line.** Not `403`, not `429`. So anything that decides success from the status (a raw `curl`, a webhook, a wrapper, `gh api -i` parsed by hand) records a refusal as a success and continues, and so does any pipeline taking its exit code from the last command in a pipe. Measured on Windows during a genuine exhaustion:

   ```
   HTTP/2.0 200 OK
   X-Ratelimit-Remaining: 0
   X-Ratelimit-Resource: graphql
   {"errors":[{"type":"RATE_LIMIT","code":"graphql_rate_limit","message":"API rate limit already exceeded for user ID …"}]}
   ```

   A success carries the same `200` with `"data"` in place of `"errors"`, and the headers arrive on a refused call exactly as on a served one: they describe the quota, never the request. **So the status line does not distinguish them and the body is the only thing that does.** One probe shows both at once:

   ```bash
   gh api graphql -f query='{viewer{login}}' -i 2>&1 |
     grep -iE '^x-ratelimit-(used|remaining)|"data"|"errors"'
   ```

   A served call answers with `"data"`; a refused one with `"errors"` and `"code":"graphql_rate_limit"`. **Read that probe's output, never its exit status**: the pipeline exits `0` either way, because `grep` matches on both arms and a pipeline reports its last element ([`reading-exit-status`](../rule-reading-exit-status/SKILL.md)). The probe is deliberately shaped to be read rather than tested: a check written around its exit code reproduces, one level in, exactly the mistake this paragraph is about. The stated exposure belongs to whatever reads the HTTP response directly, not to `gh` itself; a rule that told `gh` users their exit-status guard was worthless would be teaching them to ignore the one check that works.

   **A refused request does not increment `Used`**, so a caller retrying on refusal watches a static counter while making no progress, and a `Used` delta (step 9) measures served calls only.

   **A refusal is not automatically exhaustion, and the headers are what tell them apart.** A call refused with `API rate limit already exceeded` while the headers report thousands of requests remaining is a secondary limit (burst or abuse detection) and waiting for the primary reset is the wrong remedy for it. `retry-after` is the discriminator: present, with secondary-limit wording in the body, means a burst limit and a short wait; absent, alongside `X-Ratelimit-Resource: graphql` and `Used` at the limit, means the hour's quota is genuinely gone and only its `reset` helps. Read `X-Ratelimit-Remaining` on the refusal before concluding which one you are in.

   **The refused-call and `Used` readings behind this step are one account on one exhaustion event, 2026-09-05**, not reproduced on a second account or a second window. The two-arm probe above was executed on a healthy quota, so its served arm is verified and its refused arm is quoted from that event rather than reproduced.

2. **Know which `gh` commands are secretly GraphQL: reads as well as writes.** `gh issue create`, `gh issue edit`, `gh issue close`, `gh issue list --search`, `gh pr create`, `gh pr ready`, `gh release create`, `gh release edit`, the `@me` handle lookup, and **all** of `gh project *`. The last has no REST surface at all: when GraphQL is exhausted, board work is blocked until the header's reset: bounded and knowable rather than indefinite, and that is the resource worth protecting.

   **`gh pr list`, `gh pr view`, `gh issue list`, `gh issue view` and `gh search` are on that list too, and this used to read as though only writes were.** A list organized around mutations invites the inference that reading is free, and it is not. The consequence is operational: a session that defers its board work during an exhaustion, expecting to keep triaging, finds triage down as well. **Only an explicit `gh api <rest-path>` keeps working.** Measured on macOS (Darwin 25.6.0) in `wordpress-importer` during a genuine exhaustion on 2026-09-06: one condition under which the test is decisive, because a refused call names the pool it drew on:

   ```
   rc=1  gh pr list --limit 1        GraphQL: API rate limit already exceeded for user ID <id>
   rc=1  gh issue list --limit 1     GraphQL: API rate limit already exceeded for user ID <id>
   rc=1  gh search issues …          GraphQL: API rate limit already exceeded for user ID <id>
   rc=1  gh issue view <n> --json number   GraphQL: API rate limit already exceeded
   rc=0  gh api repos/{owner}/{repo}       OK          <- control: core was untouched throughout
   ```

   **A nonexistent resource number is the other condition, and it is the better one, because it works on a healthy quota and so can be re-checked on demand.** Measured on macOS (Darwin 25.6.0) with `graphql` at `4938` remaining, so none of these is an exhaustion refusal:

   ```
   rc=0  gh pr view 999999 --json number         {"number":999999}
   rc=1  gh issue view 999999 --json number      GraphQL: Could not resolve to an issue or pull request…
   rc=1  gh pr view 999999 --json number,title   GraphQL: Could not resolve to a PullRequest with the…
   ```

   **The error text names the backend directly**, rather than leaving it inferred from which pool happened to be drained, and rows 2 and 1 *demonstrate* the resource asymmetry instead of asserting it: same flag, same shape, same absent number, one calling and one not. **Prefer this form.** A rule whose evidence can only be re-run during an outage is one nobody re-runs, because an outage is exactly when nobody has time to.

   **Take those exit codes without a pipe.** Every row above was first measured through `| head -1`, which makes `$?` report the status of `head` and prints `rc=0` beside six failures: the same trap step 1's probe is shaped around, met here inside a measurement about which commands fail.

   **A `gh` success is not evidence the backend is healthy, because some invocations issue no request at all.** `gh pr view 999999 --json number` returns `{"number":999999}` and `rc=0` for a pull request that does not exist: no `404`, because nothing was contacted; the field is echoed from the argument. Add any other field and it fails like the rest. **This is not `gh` selecting a cheaper backend from the field set**: that reading was published on this evidence and retracted by both sessions that reached it. What varies is whether a request is issued; everything that does reach the network on these subcommands reaches GraphQL. It is also asymmetric between resources: `gh issue view --json number` *does* call, and fails, where the `gh pr view` form does not.

   **So a self-audit of GraphQL spend that counts `gh api graphql` invocations is a floor, not a total.** It misses every ordinary `gh pr view`, `gh issue list` and `gh search`. One audit revised its own footprint from roughly 6 explicit calls to roughly 24 ordinary ones once the outage made the classification testable.

   **`gh release` belongs on that list for a reason that bites harder than the others.** A release is cut rarely and under time pressure, so the quota is usually spent before anyone looks: observed cutting `wordpress-importer` `v0.8.0` on 2026-08-03 with `graphql` fully drained and `core` barely touched, where `gh release create` exited `1` with `GraphQL: API rate limit already exceeded` and the REST form in step 3 published on the same credential the same minute. **The REST form has no `--verify-tag` equivalent and will create a missing tag at the default branch's tip rather than refusing**: push the tag first, then post.

   **A second operation has no REST surface, and unlike the board it sits inside the build flow rather than beside it: flipping an open draft to ready.** `PATCH /repos/{o}/{r}/pulls/{n}` takes `title`, `body`, `state`, `base` and `maintainer_can_modify`, and `draft` is not among them. So REST honors `draft` when it **creates** a pull request and **silently ignores it on update**, returning the unchanged value with no error. **"No REST path" is the wrong summary and produces the wrong instinct**: the field is accepted, changes nothing, and reports success. The mutation that does work is `markPullRequestReadyForReview` (what `gh pr ready` sends), so an exhausted GraphQL quota can strand a finished branch as a draft: building, pushing, opening and merging are all REST and all keep working; the one step between them that does not is making the pull request ready. Measured on this account on 2026-09-05:

   | Call | Payload | Result |
   | --- | --- | --- |
   | `POST …/pulls` | `{"draft": false}` | honoured: the pull request opens ready (Windows) |
   | `POST …/pulls` | `{"draft": true}` | honoured: the pull request opens as a draft (Windows) |
   | `PATCH …/pulls/{n}` | `draft=false` | **ignored**: `200 OK`, and the response echoes `draft: true` (macOS, Darwin 25.6.0) |

   **This is a different failure from a quota instrument reporting a wrong number, and the two should not be collapsed.** There the reading is untrue and the call's own behavior is honest; here the quota reading is irrelevant: the write is accepted, reports success, and performs nothing. A session that has learned to distrust `rate_limit` is still not protected against this one, because the only defense is reading the resource back after writing it.

   **Avoid the transition rather than budgeting for it.** `draft` *is* a field on the REST create call, so a pull request that is already finished when it is opened can be `POST`ed with `"draft": false` and the GraphQL-only flip never arises. That is the difference between shipping inside an exhausted window and waiting an hour for a reset, and both happened on 2026-09-05: one pull request opened ready by REST on a drained quota, another left in draft beside it because the flip had no REST path.

   **Bounds.** The parameter list is GitHub's REST reference for *Update a pull request*, read 2026-09-09. The silent-ignore behavior was measured only for the flip **to** ready: setting `draft: true` on a ready pull request is untested.

3. **Use the REST equivalents for issue work.** These keep working when `gh issue` does not:

   | Task | REST |
   | --- | --- |
   | Create | `gh api -X POST repos/{o}/{r}/issues -F title='…' -F body=@body.md -f 'labels[]=x' -f 'assignees[]=handle'` |
   | Edit body | `gh api -X PATCH repos/{o}/{r}/issues/{n} -F body=@body.md` |
   | Close | `gh api -X PATCH repos/{o}/{r}/issues/{n} -f state=closed -f state_reason=not_planned` (or `completed`) |
   | Comment | `gh api -X POST repos/{o}/{r}/issues/{n}/comments -F body=@c.md` |
   | Label | `gh api -X POST repos/{o}/{r}/issues/{n}/labels -f 'labels[]=x'` |
   | Issue type | `gh api -X PATCH repos/{o}/{r}/issues/{n} -f type=Feature` (`Bug` \| `Task`) |
   | Assign (add) | `gh api -X POST repos/{o}/{r}/issues/{n}/assignees -f 'assignees[]=handle'`: **adds**; pull requests assign through the *issues* endpoint |
   | Assign (replace) | `gh api -X POST repos/{o}/{r}/issues/{n} -f 'assignees[]=handle'`: **replaces** the list, which is what a hand-off to a *different login* needs |
   | Open a PR | `gh api -X POST repos/{o}/{r}/pulls --input -` with a JSON body (`title`, `head`, `base`, `body`, `draft`) |
   | Flip a PR to ready | **No REST equivalent**: GraphQL only, via `gh pr ready <n>`. `PATCH …/pulls/{n}` accepts `draft=false`, returns `200`, and ignores it; see step 2 |
   | Merge a PR | `gh api -X PUT repos/{o}/{r}/pulls/{n}/merge -f merge_method=<method>` (the method the repository allows; see below) |
   | Publish a release | `gh api -X POST repos/{o}/{r}/releases --input rel.json` (`tag_name`, `name`, `body`, `prerelease`, `draft`) |

   **The two assignment endpoints differ by one path segment and do opposite things, and a single-account probe cannot tell them apart.** `POST …/issues/{n}` **replaces** the whole assignee list with what you send; `POST …/issues/{n}/assignees` **adds** to it, leaving anyone already there. They diverge only when a request omits somebody already assigned, so every probe against an unassigned issue, or one assigned to the same person you are sending, returns an identical result from both. Measured 2026-09-03 on macOS (`wordpress-importer`) against the live API, on a throwaway issue carrying **two** assignable logins: the second account is what makes the behaviors separable at all:

   | Call | Payload | Before | After |
   | --- | --- | --- | --- |
   | `POST …/issues/{n}` | `["b"]` | `a`, `b` | `b`: **replaces**; the unnamed login is dropped |
   | `POST …/issues/{n}/assignees` | `["b"]` | `a` | `a`, `b`: **adds**; the unnamed login survives |

   Handing work over is exactly the omitting case, and the endpoint whose name matches the intent is the one that does not do it: a hand-over written against `…/assignees` silently produces **two** assignees rather than a reassignment. The consequence is borne by readers, not by tooling: the person handing off still appears to hold it, and the person taking it on cannot tell from the field that they now do. [`writing-pull-requests`](../writing-pull-requests/SKILL.md) carries the assignment convention this serves; reach for the add form only when work genuinely has a second assignee.

   **The single-login case has its own hazard instead, and it runs the other way.** There is no safe "remove my own assignment": a remove takes whatever is assigned, which is the other party's. Observed 2026-09-09 (`wordpress-importer`), a session correcting a mistaken assignment removed the login and left the pull request unassigned, because there had never been a second assignment to leave behind. Use `POST` to add; do not reach for `DELETE` to undo.

   **These endpoints return pull requests; the `gh` wrappers do not.** `repos/{o}/{r}/issues` and `search/issues` both return pull requests alongside issues, distinguished by a `pull_request` key, while `gh issue list` and `gh search issues` filter them out. The difference is silent, and it defeats the duplicate check [`writing-issues`](../writing-issues/SKILL.md) requires before filing: run through the wrapper, the check reports the ticket and conceals the pull request implementing it.

   **`PUT` is the merge verb.** `gh api -X PUT repos/{o}/{r}/pulls/{n}/merge` is how a pull request merges without spending GraphQL, and it is the path to prefer: `gh pr merge` sends a `mergePullRequest` mutation, which bills GraphQL: the budget this rule exists to protect. `--admin` adds no server-side power on these repos. **Pass the merge method the repository allows**: the call defaults to a merge commit, and a repository that has disabled merge commits refuses it. Read what is allowed with `gh api repos/{o}/{r} --jq '[.allow_squash_merge, .allow_merge_commit, .allow_rebase_merge]'` (REST).

   **Permission rules in a repository's `.claude/settings.json` are case-sensitive literal prefix matches on the raw command text**, so where an allowlist matches `gh api` by prefix, quoting any part of a prefix defeats one silently: `gh api -X POST repos/…` and `gh api "-X" POST repos/…` are different strings to the matcher. When a query string forces quoting, quote only the tail: `gh api repos/{o}/{r}/issues'?state=open&page=1'`. And a rule scoped to the `repos/` namespace permits far more than the table above: `-f private=false`, a `git/refs` force-update, `/merges`, `/hooks`, `/transfer`, `branches/{b}/protection`, while `gh` accepts its method flag anywhere, so `gh api repos/…/git/refs/heads/main -X DELETE` matches a `POST`-shaped prefix and no deny entry; matching does no case folding, so `gh api -x DELETE …` is not caught either, and no arrangement of prefix rules can close that. An allowlist is a convenience, never a security boundary; branch protection is that boundary. The merged policy is also only as narrow as its widest scope: a blanket `Bash(gh api *)` in a developer's own `~/.claude/settings.json` widens it again on that machine, so write procedures against the repository's `.claude/settings.json`, the only portable artifact.

   **Omitting `commit_message` from the merge call makes GitHub compose one, and the composed text is parsed for closing keywords.** So the merge call prescribed above closes issues from a surface that is not the pull-request body, and nothing in the merge response reports which one acted. **What a squash merge composes from is a repository setting.** In `uamswp-migration-api` it is the pull-request **title and body**, not the branch commits: measured across the five most recent squash merges on its `main`, `#198` to `#203`: each body carries `Closes #N`, and the branch commits behind `#199` and `#203` carry 0 closing directives, so an enumeration that reads branch commit messages alone reports clean on merges that close issues, and a clean result from that command is what a branch carrying no directives looks like *and* what that configuration looks like when the directive is in the body. **Check both surfaces, and do not treat a control on either as covering the other:** `-f commit_message=…` replaces the composed text and leaves the body untouched; editing the body leaves an already-composed message untouched. **Which one fired is decidable after the fact from the close event's `commit_id`**: a close driven by a commit carries that commit's id, a close driven by the pull-request link carries `null`.

   **Which route acts is open, and the comfortable answer would be to pick one.** Four closes checked across three repositories on 2026-09-05 all carried `commit_id: null`, the body route. Two older records carry a commit id: `uams-statamic#1992` (2026-08-13) and `wordpress-importer#570` (2026-07-15). Either both routes are live and something not yet isolated decides which fires, or the behavior changed between August and September, or the older records are wrong. Nothing available here separates the three.

   **Enumerate the body: the surface measured to fire.** Substitute the repository and the pull request you are merging:

   ```bash
   gh api repos/{o}/{r}/pulls/{n} --jq '.body' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   **And the branch commits, which a configuration that folds them in will parse**, from a tree holding the branch:

   ```bash
   git log origin/main..HEAD --format='%B' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   `HEAD` rather than a literal `origin/<branch>` placeholder, deliberately: `<` is a redirection, so the placeholder form is not runnable as printed: bash reads a file named `branch` and the pipeline still reports whatever `perl` exited with. For a branch you are not on, substitute its remote-tracking ref for `HEAD`.

   **Prove the pattern can match before trusting that it did not:**

   ```bash
   printf 'Closes #1\ndoes not close #2\nfix: no number here\n' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   That must print `Closes #1` and `close #2`, and nothing for the third line.

   **And a second arm**, a keyword ending one line and a reference beginning the next:

   ```bash
   printf '| #2037 | write the patch | closed\n#2241 | file the bug upstream | retitled\n' |
     perl -0777 -ne 'while (/\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b[\s:]*(?:[\w.-]+\/[\w.-]+)?#\d+/gi) { my $m = $&; $m =~ s/\s+/ /g; print "$m\n" }'
   ```

   That must print exactly `closed #2241`, on one line; with `-ne` in place of `-0777 -ne` it prints nothing, which is why the enumerations slurp. It is `perl` rather than `grep` because the macOS default `grep` is BSD, not GNU: `[[:<:]]` is BSD-only, `\b` is not guaranteed on a strict BSD grep, and a form verified on one machine can silently match nothing on another.

   **Four parsing details defeat a check by eye:**

   - **The keyword must precede each number.** `Closes #80, #81` closes **only** #80 while reading as though it closed both; the pattern matches exactly what GitHub does.
   - **Negation does not disarm it.** `does not close #N` and `not a fix for #N` each carry a keyword immediately followed by a reference, and GitHub does not parse the negation: hence `close #2` in the control's expected output.
   - **A newline is whitespace to GitHub, and a per-line read cannot cross one.** A line ending in a keyword followed by a line beginning `#N` is a live directive and closes the issue, although the pull request's own `closingIssuesReferences` may be empty: the two are different code paths. Measured in `uams-statamic` on 2026-09-14 and filed there as `uams-statamic#2464`.
   - **A code span or fence around the KEYWORD disarms the pull-request LINK, and only the link.** A directive inside a code span or a fenced block is not parsed into `closingIssuesReferences`: the link path reads the body as Markdown. It is **not** disarmed for the push-time scan, which reads the squash commit message as plain text: [`pre-merge-check`](../rule-pre-merge-check/SKILL.md) step 6 records `wordpress-importer#1036` closing its target from inside a code span, one second after the merge, and `uams-statamic#2482` closing an unlisted ticket from inside a fence. So a hit inside a span is not a finding for the link field and is still a live directive for the merge; reword it rather than discounting it. A span disarms only when it encloses **the keyword**, never by being nearby, and a body can close two issues from two plain-prose directives, so "only the first directive counts" is false. Measured 2026-09-11 by reading `closingIssuesReferences` on already-merged pull requests (`uamswp-migration-api#205`, `wordpress-importer#1036`, `wordpress-importer#1009`). So the enumeration over-reports on documentation that quotes directives: **read what the pattern returns; do not count it.**

4. **Dependencies and sub-issues have REST endpoints, and they key on the database id.** Neither is obvious, and both are load-bearing for the `blocked_by` discipline, which decides whether a ticket is buildable:

   ```bash
   id=$(gh api repos/{o}/{r}/issues/<blocker> --jq '.id')      # numeric .id, NOT the issue number
   gh api -X POST repos/{o}/{r}/issues/<blocked>/dependencies/blocked_by -F issue_id=$id
   gh api -X POST repos/{o}/{r}/issues/<parent>/sub_issues       -F sub_issue_id=$id
   ```

   Read them back with `GET …/dependencies/blocked_by` and `GET …/sub_issues`. **An open blocker and a live dependency edge are different claims**: a textual `Blocked by #N` line in a body is prose, not an edge. Verify the one you actually care about.

   **Both read-back endpoints are list endpoints, and an unpaginated call is capped at one page (30 by default) with nothing marking the result short.** A count of exactly 30 is the only signal the cap was hit, one you have to already suspect in order to notice. Ask for a page size, and past 100 walk `&page=N` explicitly for the reason step 6 gives (`--paginate` does the walk on a local session and fails in a cloud one):

   ```bash
   gh api repos/{o}/{r}/issues/<blocked>/dependencies/blocked_by'?per_page=100' --jq 'length'
   gh api repos/{o}/{r}/issues/<parent>/sub_issues'?per_page=100'              --jq 'length'
   ```

   Note the quoting: the query string forces it, so the quote opens at the `?` and the `gh api repos/…` prefix stays bare, as step 3 requires wherever a prefix allowlist is in force.

   **What is measured, and what is not.** `sub_issues` **demonstrably truncates**: on `UAMS-Web/wordpress-importer#358` the bare call returns **30** and `?per_page=100` returns **41**, measured on macOS (Darwin 25.6.0) on 2026-09-06 and reproduced independently from `uams-statamic`. `GET …/dependencies/blocking` truncates the same way: against `wordpress-importer#589`, a bare `GET` returns **30** and the paginated call **32**. `blocked_by` has **not** been tested past the page size anywhere, and is not assumed to match its siblings: `#589` carries three `blocked_by` edges. Pass `per_page` on all three regardless: two are demonstrated, and on the third the cost is a query parameter against a failure that is invisible when it happens.

   **A tracker can only test the page cap on an issue with more edges than a page holds.** Where no issue carries more than 30 `blocked_by` edges, a probe for truncation returns "no truncation observed" whether or not the endpoint truncates. That is an absence, not a clean; the `Link`-header probe below is the test that works at any size.

   **Asking whether an endpoint paginates is cheaper than finding a 31-edge case, and works in any repository**: set a small `per_page` and read the `Link` header:

   ```bash
   # one invocation per endpoint; each prints its own Link header
   gh api repos/{o}/{r}/issues/<n>/dependencies/blocked_by'?per_page=2' --include | grep -i '^link:'
   gh api repos/{o}/{r}/issues/<n>/dependencies/blocking'?per_page=2'   --include | grep -i '^link:'
   gh api repos/{o}/{r}/issues/<n>/sub_issues'?per_page=2'              --include | grep -i '^link:'
   ```

   `rel="last" page=16` at `per_page=2` is 32 items, which confirms a count through a different mechanism than enumerating it.

5. **Always pass Markdown from a file**: `-F body=@body.md`. Issue and PR bodies are dense with backticks, `$`, `!`, and fenced blocks that the shell mangles as an inline argument, and the failure is intermittent enough to look fine until it isn't. This is the same reason [`writing-issues`](../writing-issues/SKILL.md) mandates `--body-file`.

6. **Never trust a `--paginate` count you piped somewhere, and `--paginate` is not available in a cloud session at all.** `gh api --paginate` follows GitHub's `Link: rel="next"`, which GitHub renders in numeric-ID form (`repositories/<numeric-id>/issues?page=2`). A Claude Code cloud session's GitHub proxy refuses that shape: `Numeric-ID repository paths are not supported through this proxy (HTTP 403)`, so `gh` prints page 1, exits 1, and a pipe throws the exit code away:

   ```bash
   gh api --paginate '…/issues?state=open&labels=afk&per_page=100' --jq '…' | wc -l
   # 100: pipeline exit 0, because `wc` succeeded. The real answer was 126.
   ```

   A truncated result is otherwise **indistinguishable from a complete one**. Use `set -o pipefail` so the failure is at least visible, and prefer walking `&page=N` explicitly until a short page: every URL then stays in the `repos/{owner}/{repo}/…` form the proxy accepts, on any machine. Count the **raw** page length, not a filtered one: a `--jq` filter that drops items makes a full page look short and ends the walk early.

   **This is not the same failure as step 4's page cap, and reaching for one fix does not cover the other.** This one is a cloud session refusing GitHub's numeric-ID `Link` URL, with a non-zero exit to catch. That one is the ordinary REST page size on a healthy local session, and it exits 0. Both are fixed by paginating; only one announces itself.

7. **`search/issues` cannot phrase-match, so a quoted pair counts something else.** A quoted two-word query is an **unordered AND over tokens**, not a phrase. Measured on 2026-08-24 (macOS), `q='repo:UAMS-Web/wordpress-importer "my own"'` and `q='… "own my"'` both returned **16**, and `own my` appears nowhere in that repo. The endpoint also indexes **comments**, so a control probe written up in a comment joins its own result set: a nonsense second token returned **0** when first run and **1** on re-run, because the comment recording that probe had been posted in between: writing the control down is what destroyed it. Enumerating candidates and grepping each body is the only way to get a phrase count: for `uams-statamic`, **6 of 26** returned items actually carried the phrase in their body.

   **That remedy inherits a truncation of its own, and this example could not have shown it.** `.items[]` is one page (30 by default) so enumerating it on a query matching more than 30 greps the first 30 and produces a complete-looking answer. The worked example above returned **26** items, below the cap, so its enumeration genuinely was complete; a reader who takes the lesson and applies it to a larger query will not be that lucky. Pass `per_page` and compare the number of items you actually read against `total_count` before treating an enumeration as exhaustive.

   So never quote a `search/issues` figure as a phrase count. It over-counts, and it is **self-incrementing** (the artifact that cites the number joins the result set) so a rising figure is not evidence of a rising problem. Establish that a probe can see the thing before quoting a number or an absence from it, and publish the control with the claim.

   That general requirement is [`an-empty-result-is-not-evidence`](../rule-an-empty-result-is-not-evidence/SKILL.md), which also takes the self-incrementing control above as its worked example of a control that is not independent of what it validates.

   **And it answers from an INDEX, so a zero about a recent artifact is unproven rather than negative.** A search index is a materialized view, not the record of truth, so "not found" means *not found in the index as of this query*. That gap needs no measured delay to be real, and nothing in the response distinguishes a stale view from a genuine absence. A duplicate check is normally run immediately before filing: precisely when the artifact it needs to find is newest. **Treat a zero about a minutes-old artifact as unproven rather than negative.**

   **The positive control above cannot close that gap**: a control is necessarily run against something that already exists, and anything old enough to serve as a control is old enough to be indexed, so the control validates the query shape and the credential, and is silent on whether the index has caught up. What was observed, with each arm carrying a control on the same query shape:

   | Observation | Result | Bears on the stale case? |
   | --- | --- | --- |
   | a comment roughly 2 minutes old, three query shapes | its issue **absent** | **did not reproduce** at the same age, with a control |
   | the same searches roughly 4 minutes later | **present** | yes |
   | a comment roughly 4 minutes old | **present** (Windows 11) | yes |
   | an issue 47 minutes old | **present** (macOS) | **no: an upper bound** |
   | a separate replication attempt | did not reproduce | age unstated |
   | a purpose-made artifact at ~69s and ~130s, controls paired | **present** both times | this is the re-test of the absent row |

   **No lag is claimed here, because none survived reproduction.** The single absent read (2026-09-05) did not reproduce: re-run against a purpose-made artifact at the age where the absence was originally seen, each sample paired with a control phrase known to be indexed, the search returned the artifact every time, and four other observers found their own artifacts present. The absent observation is recorded because an observation nobody could reproduce is not a refuted one, and it is recorded as *not reproduced* so nobody builds a threshold on it. **What the instruction rests on is architectural, not measured**: `search/issues` is eventually consistent by design, which is sufficient on its own. The 47-minute arm bounds the lag from above and speaks to nothing else; no lower bound is established, and nothing here says whether the lag varies by artifact type, repository size, or load.

   **And a control establishes that a probe works, not that its answer is relevant.** Every arm above carried a control, which shows only that the instrument could see what it was pointed at. *Whether the thing it was pointed at bears on the question is a separate judgement, and a table that records controls does not record it.* That is how a well-formed arms table with honest controls still counts an observation which cannot speak to the finding. **Any arms table in this corpus needs the third column above, not just the control.**

   **So the remedy is a disposition, not a wait.** For a duplicate check against something that may have been created minutes ago, read the candidates directly: `GET repos/{o}/{r}/issues/{n}` and `GET repos/{o}/{r}/issues/{n}/comments`, which have no index in the path, are current, and are REST, so they spend nothing this rule is protecting. Reserve `search/issues` for the case it is good at: finding an artifact whose age you are not relying on.

8. **Do not contort a genuinely graph-shaped read into many REST calls.** One GraphQL query that replaces a dozen round-trips is still the right call when you need related data across many objects: the point is to stop spending GraphQL on *single-object issue mutations* that REST handles for free, not to ban it. Board reads remain GraphQL, where GraphQL is reachable at all.

9. **Measure a call's cost as a delta in `X-Ratelimit-Used` between two response headers**: an endpoint delta is always zero and reports every call as free.

## The DRY line

This file is the standing statement on **which API surface to spend**. The project-board *mechanics* (the `Status` field and option ids, the `item-add` / `item-edit` recipe, the read-back by item id) live in [`writing-issues`](../writing-issues/SKILL.md) and are not repeated here; that skill's board section is the thing this rule protects the budget for. Every skill's `gh` usage inherits this as a standing order rather than restating it. It composes with [`long-running-commands`](../rule-long-running-commands/SKILL.md), which owns bounding a wait rather than choosing what to call.
