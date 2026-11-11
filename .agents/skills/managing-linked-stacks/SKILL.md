---
name: managing-linked-stacks
description: >-
  How to operate a GitHub linked stack (stacked pull requests, `gh stack`) in a UAMS-Web
  repository: creating a stack from existing pull requests, extending it, relinking a pull
  request whose base must change, restacking after a lower branch moves, unstacking, and
  merging part or all of it with the asynchronous merge API. Covers rooting a stack at `dev`
  in a repository that promotes through `dev` and `staging`, where the `staging` twins go and
  why they must be re-read after every stack merge, the base read-back to run after every
  link, relink and unstack (a bundled script that prints a `clean` or `MISMATCH` line), what
  the server does to the pull requests above when the bottom merges by merge commit, squash
  or rebase (measured on a scratch repository), and the limits that trial confirmed or
  corrected.
  Activate when creating, extending, relinking, restacking, unstacking or merging a linked
  stack, when a stacked pull request refuses a base change, or when reading which pull
  request is the bottom of a stack.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/managing-linked-stacks/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore restack restacking restacked unstack unstacking unstacked retarget retargets retargeted unpushed -->

# Managing linked stacks

A **linked stack** is a server-side object: an ordered list of pull requests in one
repository, bottom first, where each pull request's base is the head branch of the one
below it and the bottom one targets the stack's **base** (its trunk). GitHub then owns
those bases, shows the stack on each pull request, and merges it from the bottom up. A
chain of pull requests whose bases merely happen to line up is not a stack until it is
linked.

The `gh stack` extension (`github/gh-stack`, read at tag `v0.3.0`) is one client for it.
Everything it does on the server is a REST call, and GraphQL can only read a stack, so a
session without the extension can do all of this through `gh api`. Installing the
extension (`gh extension install github/gh-stack`) is the operator's decision, like any
other install.

The behavior below is marked by its evidence. **Measured** means read from a real stack's
API record, with the repository and date. **Documented** means stated in the extension's
docs or source at `v0.3.0` and not yet reproduced. **Unconfirmed** means neither.

**The trial.** The merge-method and limit behavior was measured on 2026-11-05 on the private
scratch repository `UAMS-Web/stack-trial-scratch` (API timestamps 2026-11-06 01:11 to 01:19
UTC), in 9 throwaway stacks (8 of three pull requests on a `dev` branch, and a twin stack of two on `staging`).
Each layer added its own file, so the rebases the server runs had nothing to conflict on,
except in three trials named where they appear: same-file edits, a lower branch merged into
the upper ones, and a conflicting change on the trunk. The read-back below printed `clean`
on a freshly built stack before any merge, which is the control that it can report a stack
at all. What was not tried: a stack larger than three, a repository with required checks or a
merge queue, and any pull request with review state.

## The API

| Operation | Call | Notes |
| --- | --- | --- |
| Read the stack holding a pull request | `gh api 'repos/{o}/{r}/stacks?pull_request=N'` | A list; empty when the pull request is in no stack |
| Read one stack | `gh api repos/{o}/{r}/stacks/{number}` | `base.ref`, `open`, and `pull_requests` bottom first |
| Read a pull request's place | `gh api repos/{o}/{r}/pulls/N --jq .stack` | `{number, position, size, base}`, or `null` outside a stack |
| Create | `gh api -X POST repos/{o}/{r}/stacks --input body.json` with `{"pull_requests": [bottom, ..., top]}` | 2 to 100 pull requests whose bases already chain |
| Extend at the top | `gh api -X POST repos/{o}/{r}/stacks/{number}/add --input body.json` | The first added pull request's base must be the current top's head |
| Unstack | `gh api -X POST repos/{o}/{r}/stacks/{number}/unstack` | Merged and queued members stay in the stack; the rest come out |
| Merge | `gh api -X PUT repos/{o}/{r}/pulls/N/merge-async --input body.json`, then poll `.../merge-async/{uuid}` | See *Merging* |

There is no call to insert a pull request below the bottom or into the middle, to reorder,
or to delete a stack. Measured 2026-11-05 on the scratch repository: `stacks/{number}/add`
with a pull request that targets the trunk returned `422` ("Pull requests must form a stack,
where each PR's base ref is the previous PR's head ref"), and creating a new stack from
that pull request plus the existing bottom returned `422` ("Pull request #17 is already part
of a stack"). The stack endpoints return `404` in a repository where stacked pull requests
are not enabled (documented).

**Which pull request is the bottom.** `position == 1` is the original bottom, which may have
merged. The **lowest open** pull request is the one whose `base.ref` equals `stack.base.ref`.
The top is the one whose `position == size`.

## The base read-back, after every link, relink and unstack

Read every base back from the API after each step that can move one, and read the printed
line, not the exit status:

```bash
node .claude/skills/managing-linked-stacks/scripts/stack-read-back.mjs --repo UAMS-Web/<repo> --pr <any member> --base dev
```

It prints one row per pull request (number, state, base, head, stack position) and then
exactly one verdict:

```
stack read-back: clean (stack #629 on `dev`, 10 pull requests read, 9 open).
stack read-back: MISMATCH: #623 targets `dev`, expected `617-add-pest-harness`.
```

The lowest open pull request must target the stack's base, each open one above must target
the head of the one below, merged members are passed over, and a closed, unmerged member is
a mismatch because it blocks everything above it. `--base` also requires the stack itself to
be on that branch.

**`clean` is about bases, not history.** It does not show that each branch contains the tip
of the one below, which a stack also needs before it can merge (see *Restacking*). Where that
matters, read it per pull request: `gh api repos/{o}/{r}/compare/<base>...<head> --jq .behind_by`
must print `0`.

For a set of pull requests that is not (or no longer) a stack, pass them bottom first:

```bash
node .claude/skills/managing-linked-stacks/scripts/stack-read-back.mjs --repo UAMS-Web/<repo> --chain 621,623,625 --base dev
node .claude/skills/managing-linked-stacks/scripts/stack-read-back.mjs --repo UAMS-Web/<repo> --chain 621,623,625 --base dev --unstacked
```

The first is the check before creating a stack. The second, after an unstack, also requires
that none of them is still in a stack. Exit 0 is clean, 1 a mismatch, 2 a read-back that
could not run (no `gh`, a refused call, a response that is not a stack). Measured on
2026-11-03 against `UAMS-Web/uams-2020` stack #629 (clean, exit 0), the same pull requests
read with `--unstacked` while still stacked (three mismatches, exit 1), and the stack read
with the wrong `--base` (exit 1).

## Creating a stack from existing pull requests

1. **Open each pull request with its base already chained**: the bottom against the trunk,
   each one above against the head branch of the one below. The writing conventions are
   [`writing-pull-requests`](../writing-pull-requests/SKILL.md)'s.
2. **Read the chain back** with `--chain ... --base <trunk>` and require `clean`.
3. **Create the stack** with the REST call, listing the pull requests bottom first:

   ```bash
   printf '{"pull_requests": [621, 623, 625]}' > body.json
   gh api -X POST repos/UAMS-Web/<repo>/stacks --input body.json
   ```

   The documented requirements are 2 to 100 pull requests whose bases chain; the extension
   also reports the server refusing a pull request that is already in a stack
   (`cmd/submit.go` at `v0.3.0`). The exact status codes are not documented.
4. **Read the stack back** with `--pr <any member> --base <trunk>`.

**Prefer the REST call to `gh stack link` for pull requests that already exist.** `link`
retargets every pull request it is given, the bottom one to the **default branch as the
local clone records it** (`origin/HEAD`, falling back to `main` or `master`) unless `--base`
is passed, and a retarget that fails is only a warning before it
goes on to create the stack (documented: `cmd/link.go` at `v0.3.0`). That is the observed
"linking changed the bases to the default branch": confirmed as the tool's behavior, and
avoided by building the chain first and creating the stack directly. Where `link` is used,
always pass `--base <trunk>`, then run the read-back.

## Extending, relinking and the limits around the bottom

- **Extend at the top only**, with `stacks/{number}/add`, after opening the new pull request
  against the current top's head branch. Read back afterwards.
- **A pull request cannot be added below the bottom or between two members.** No call does
  it. To put a new pull request lower (a sync pull request that the stack should sit on, for
  one), unstack, retarget, and create a new stack. Observed in a UAMS-Web repository: a sync
  pull request could not be put at the bottom of an existing stack. That was a limit on
  inserting, not on sync pull requests: `UAMS-Web/uams-2020` stack #629 and
  `UAMS-Web/UAMSWP-shortcodes-ui` stack #55 each have a sync pull request at the bottom
  (measured 2026-11-03).
- **A stacked pull request's base cannot be changed while it is stacked.** Measured
  2026-11-05 on the scratch repository: `PATCH .../pulls/N -f base=dev` on the second member
  and `-f base=staging` on the bottom member each returned `422` with the message "Cannot
  change the base branch because the pull request is part of a stack." and left the base as
  it was. The extension refuses it before calling the server too ("Stack API owns base
  relationships", `cmd/submit.go` at `v0.3.0`). To relink: unstack, `PATCH` the bases, read
  back with `--chain`, create the stack again, read back with `--pr`.
- **Unstacking a stack that has merged members leaves those members in it** (documented:
  merged and queued pull requests stay). The ones that come out keep their bases: measured
  2026-11-03 on `UAMS-Web/UAMSWP-Find-a-Doc` #919 and #923, whose `removed_from_stack`
  events have no base change beside them. So a relink after a partial merge is a new stack of
  the open pull requests, rooted at the trunk.
- **One pull request is in at most one stack.** Measured 2026-11-05: the server refuses a
  stack that includes a pull request already in one (`422`, "Pull request #17 is already part
  of a stack"). The refusal is per pull request, not per branch: see the `staging` twins
  below.

## A repository that promotes through `dev` and `staging`

- **Root the stack at `dev`.** A stack's base can be any branch: the bottom pull request's
  base sets it, or `--base` with `gh stack`. Measured 2026-11-03: `UAMS-Web/uams-2020` #629,
  `UAMS-Web/uams-2016` #67 and `UAMS-Web/UAMSWP-Find-a-Doc` #969 are all rooted at `dev` in
  repositories whose default branch is `master`. The observed "a stack is rooted at the
  default branch" is the `gh stack link` default above, not a server limit.
- **Keep the `staging` twins out of the `dev` stack**, as drafts against `staging` with the
  same head branch. The server does not require it: measured 2026-11-05 on the scratch
  repository, a draft against `staging` whose head branch (`t1`) is the bottom of a stack on
  `dev` was accepted as the bottom of a second stack on `staging` (with one more pull request
  above it whose base is that branch), and the read-back printed `clean` for the twin stack. The
  extension refuses a branch in two stacks locally, so only the REST create makes one. After
  the `dev` stack's bottom merged by squash, the twin and the second stack stayed open and
  unchanged (head and base the same, `mergeable_state: clean`), while the `dev` stack's next
  pull request was retargeted and rebased. Not measured: a merge of the twin stack, and a
  twin stack whose branch the server has rewritten.
- **Re-read every twin's diff against `staging` after any change to its head branch.** The
  twins share the stack's branches, so whatever brings `dev` into a branch (the server's
  rebase after a stack merge, or the author merging `dev` forward) brings it into the twin
  too. Measured 2026-11-03 on `UAMS-Web/UAMSWP-Find-a-Doc` stack #969: the twin #998 sat on
  `dev`'s tip (0 commits behind `dev`), and its 114 commits against `staging` included the
  `dev` merge commits for #891, #893 and #896, which reached its branch through merge-forward
  commits (`Merge origin/dev into 915-gbp-sync-stacked` and the merges above it). Any work on
  `dev` that was never meant for `staging` arrives with it. A twin
  in that state is not promoted as it stands: it is rebuilt from a branch cut from `staging`
  carrying only that layer's commits, and opened as a new twin.

## Merging

**A stacked pull request merges through `PUT .../pulls/N/merge-async`.** The synchronous
`PUT .../pulls/N/merge`, which the [`pre-merge-check`](../rule-pre-merge-check/SKILL.md)
and [`github-api-budget`](../rule-github-api-budget/SKILL.md) rules name for ordinary pull
requests, cannot merge one. Measured 2026-11-05 on the scratch repository: it returned `403`
("Merging stacked PRs via this endpoint is not supported. Use the asynchronous merge endpoint
instead.") for the bottom member and for the second member, and merged nothing. The body takes
`merge_method` (`merge`, `squash` or `rebase`), `merge_action` (`default`, `direct_merge` or
`merge_queue`) and `sha`. Pass `sha` with the reviewed head of the pull request being
merged; it pins only that head, not the heads of the pull requests below it that land with
it, so read those back from the stack just before the call:

```bash
printf '{"merge_method": "squash", "merge_action": "default", "sha": "%s"}' "$SHA" > merge.json
gh api -X PUT repos/UAMS-Web/<repo>/pulls/<n>/merge-async --input merge.json
gh api repos/UAMS-Web/<repo>/pulls/<n>/merge-async/<uuid>
```

Poll until `status` is no longer `pending`. Who may merge is unchanged by this skill.

**Merging a pull request merges every unmerged one below it, in one atomic step.** Measured
2026-11-03 on `UAMS-Web/UAMSWP-Find-a-Doc` stack #969: merging #893 landed it and the 15
pull requests below it, all 16 recording the same merge commit; #891, then the lowest open
member, merged on its own; and merging #896 landed it and the 7 below it under one merge
commit. No pull request in the middle merges without the ones below it. Measured 2026-11-05
on the scratch repository: `merge-async` on the **second** member of a fresh stack, with the
bottom one untouched, was accepted (`202`) and merged both, as two commits on the trunk with
the bottom one first (`u layer 1 (#25)`, then `u layer 2 (#26)`), and the third member was
retargeted to the trunk. So the call on a middle member lands everything below it; read the
stack before merging a member that is not the lowest open one. Admin bypass and auto-merge
are not available for a stacked pull request (documented).

**What happens to the pull requests above.** The server retargets the lowest open pull
request to the stack's base and rebases the branches above it onto the new trunk tip. Measured
2026-11-05 on the scratch repository, with the bottom merged by each method in turn (squash,
merge commit, rebase): in all three the next pull request was retargeted
(`automatic_base_change_succeeded`) and its branch, and the one above it, was force-pushed
(`head_ref_force_pushed`) by the time the state was read 6 to 10 seconds after the call,
leaving the second pull request 1 commit ahead of the trunk and 0 behind; the
next merge needed nothing from the author, and a stack of three merged bottom up in three
calls under each method. A control with every layer editing the same file behaved the same
under squash, and so did a stack whose upper branches had the lower branch merged into them
(the merge commits were gone from the rebased branch: only the layer's own commit remained
ahead of the trunk). The exception measured is a conflict: with a commit pushed to the trunk
that changed a file the second layer also changed, squash-merging the bottom retargeted the
second pull request to the trunk and then did **not** rewrite its branch or the one above it
(no `head_ref_force_pushed`); the second pull request read `mergeable_state: dirty`, 2 ahead
and 2 behind. That author-side rebase is the step in *After a stack merge* below. The earlier
real-stack measurement is narrower. On `UAMS-Web/UAMSWP-Find-a-Doc` stack #969 on
2026-11-03, after #893 merged (merge commit), the next pull request (#891) was retargeted to
`dev` within 3 seconds (`automatic_base_change_succeeded`), and the branches at positions 17
to 25 (#891 to #896), which held no merge commits, were force-pushed within 9 seconds; after
#891 merged, positions 18 to 25 were force-pushed again. The branches from #917 (position 26)
up, which held merge-forward commits, were retargeted when their turn came and were never
rewritten; after #896 merged, nothing was force-pushed at all. The cause of the real stack's branches that were not rewritten was not established (the scratch
trial reproduced it only with a conflict). **So do not assume the branches above were
rebased: read each one's `behind_by` against its base (above) after every stack merge.**

| Method | Result on the base | The pull requests above | Evidence |
| --- | --- | --- | --- |
| Merge commit | One merge commit per call (two parents), then the layer's own commit | Next one retargeted; it and the one above rebased and force-pushed; stack carried forward with no author step | **Measured** 2026-11-05, `UAMS-Web/stack-trial-scratch`; on 2026-11-03, `UAMS-Web/UAMSWP-Find-a-Doc` stack #969 left branches with merge commits not rewritten |
| Squash | One squashed commit per merged pull request (`<title> (#N)`) | Same as above, including a same-file control | **Measured** 2026-11-05, `UAMS-Web/stack-trial-scratch` |
| Rebase | Each pull request's commits replayed onto the base (the commits keep their messages, new hashes) | Same as above | **Measured** 2026-11-05, `UAMS-Web/stack-trial-scratch` |

All three leave a clean stack mergeable and carried forward, and none needs an author step
unless the server's rebase conflicts, in which case the branches above are left as they were
and need the rebase below. None of the three is unsupported. Not measured: required checks,
a merge queue (`merge_action: merge_queue`), a stack larger than three, and review state.

**After a stack merge**, in any local clone of a branch above:

1. `git fetch origin`. Where the server rewrote the branch, a push from the old local tip is
   refused as a non-fast-forward, which is the protection working. `origin/<branch>@{1}` is
   now the remote tip before the rewrite.
2. Where every local commit was already on the old remote tip, reset to the rewritten one.
   Check that first; `git status --porcelain` only shows uncommitted changes, not unpushed
   commits:

   ```bash
   git merge-base --is-ancestor <branch> 'origin/<branch>@{1}' && echo "no unpushed commits"
   git checkout <branch> && git reset --hard origin/<branch>
   ```

   Run the reset only when the first line printed `no unpushed commits` and
   `git status --porcelain` printed nothing.
3. Otherwise carry only the local commits across, then push with the lease pinned as below:
   `git rebase --onto origin/<branch> 'origin/<branch>@{1}' <branch>`.
4. Run the read-back with `--pr`, read each branch's `behind_by`, and re-read every `staging`
   twin's diff (above).

## Restacking when a lower branch moves

A stack needs a linear chain: each branch must contain the tip of the branch below. A new
commit pushed to a lower branch breaks that for everything above, and the stack cannot merge
until it is restored (documented).

- **Rebase and force-push with a lease pinned to the tip you rebased from**, one branch at a
  time from the bottom up:

  ```bash
  git fetch origin
  test "$(git rev-parse <upper>)" = "$(git rev-parse origin/<upper>)" && echo "local matches remote"
  OLD=$(git rev-parse <upper>)
  git rebase --onto origin/<lower> <old lower tip> <upper>
  git push --force-with-lease=refs/heads/<upper>:"$OLD" origin <upper>
  ```

  Go on past the second line only when it printed `local matches remote`; otherwise someone
  else's commits are on the remote branch, and they are brought in first. The lease is then
  pinned to the tip that was rebased, so the push fails if the remote branch moved since. A
  bare `--force-with-lease` compares against whatever was last fetched, so a fetch between
  the rebase and the push makes it a plain force push; the pinned form does not. `gh stack
  rebase` then `gh stack push` does the same for the whole stack, with one pinned lease per
  branch in a single atomic push (documented). The **Rebase Stack** button in the merge box
  does it on the server, and its commits are not signed (documented).
- **Merging the lower branch in instead** keeps the lower tip in the upper branch's history
  and needs no force-push. The docs require "a **fully linear history** between each of the
  branches" for a stack to merge (`faq.md`) and contrast that with merge commits
  (`guides/workflows.md`), so this is the riskier path. Measured 2026-11-03: `UAMS-Web/UAMSWP-Find-a-Doc`
  #917, whose branch carries merge-forward commits, reads `mergeable_state: clean`, but it
  has not been merged. Measured 2026-11-05 on `UAMS-Web/stack-trial-scratch`: a three-layer
  stack whose upper branches each had the lower branch merged in (no rebase) read `clean`
  under the base read-back, and merged bottom up by squash with the server rebasing the
  branches above (the merge commits dropped from them). So the server accepted that history
  where it had no conflict; whether it accepts it with a conflict is not measured, and the
  docs' "fully linear" requirement still reads as the safe default. Rebase where the rebase
  applies, and say in the pull request when a merge was used instead.

## What the ticket observed, and where each now stands

| Observed | Status |
| --- | --- |
| Linking existing pull requests changed their bases to the default branch | Confirmed as `gh stack link`'s default; avoided with `--base` or the REST create |
| A stacked pull request cannot have its base changed (`422`) | Confirmed 2026-11-05, `UAMS-Web/stack-trial-scratch`: `422` "Cannot change the base branch because the pull request is part of a stack." for the bottom and a middle member |
| A stack is rooted at the default branch | Corrected: measured stacks are rooted at `dev`, and the scratch trial rooted all of them there |
| A `staging` twin sharing a head branch stays out of the stack | Corrected: the server accepts such a twin as the bottom of a second stack rooted at `staging` (measured 2026-11-05); keeping it out remains the practice, not a server rule |
| A sync pull request cannot sit at the bottom | Corrected: it cannot be inserted below an existing stack (`422`, confirmed 2026-11-05); measured stacks have one at the bottom |
| After a lower merge, the branches above need a rebase or a merge of the base | Corrected: under squash, merge commit and rebase the server retargets the next pull request and rebases the branches above, and the author does nothing unless that rebase conflicts, when the branches are left as they were and `behind_by` shows it (measured 2026-11-05); read `behind_by` after every merge |
| The synchronous merge endpoint refuses a stacked pull request | Confirmed 2026-11-05: `403`, use `merge-async` |
| A merge of a middle member | Added 2026-11-05: merges every member below it too |
