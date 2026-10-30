---
name: rule-pre-merge-check
description: "Re-verify at the merge, not only at the build."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/pre-merge-check.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore Disjointness oneline -->
# Rule — re-verify at the merge, not only at the build

**A green chain is not a merge decision.** It says the jobs passed on what was run, not that this should land on `main` in the state it will land in. Before any merge, even one you watched go green, work the check below and say what it found.

## Why this is a standing order

A required pull request is not a merge gate. Read what `main` enforces (`gh api repos/{o}/{r}/rulesets`, `branches/main/protection`); never assume it.

**State the enforcement, never the flag** (`protected: true` and a `404` from `branches/main/protection` both mislead under rulesets). **A skipped check is not a passing check.** The gate re-run at the merge is the only gate unless checks are required, and **a pull request that changed after its check gets another one**.

## Making CI a merge gate: required status checks

A required status check is a platform-enforced gate: require one where CI runs on every pull request (a public repository's minutes are free). It proves the jobs passed, not that the merge is right, so the check below still applies.

**Require one aggregate check, not the jobs.** GitHub counts `skipped` and `neutral` as satisfying a required check and an `if:`-skipped job as success, so per-job checks pass with a variable-gated CI off (a workflow that never starts leaves them `Pending`). The shared CI template's final `CI` job `needs:` every job, runs `if: always()`, and fails unless all succeeded. Its behavior under the remote-CI switch is not yet measured.

**1. Read what `main` enforces now:**

```bash
gh api repos/{o}/{r}/rules/branches/main --jq '[.[].type]'
gh api repos/{o}/{r}/rulesets --jq '.[] | [.id, .name, .source_type, .enforcement] | @tsv'
```

An empty first list means no ruleset reaches `main`; first confirm it returns rules where some are known.

**2. Require three names everywhere: `CI`, `Attribution footer`, `Closing keywords`.** A context is the job's `name:`, or its id without one. Read them off a commit whose workflows ran green, never the workflow files, and confirm all three:

```bash
gh api repos/{o}/{r}/commits/<sha>/check-runs --jq '.check_runs[].name'
```

Each name must come from one job only. **`CI`'s `needs:` tracks the jobs; the ruleset never changes**: update `needs:` in the same change that adds, renames or removes a job (one left out gates nothing). The `code-quality` skill's `check-aggregate-needs.mjs` (its `aggregate-needs` job) checks this; state the `needs:` obligation in the workflow's header comment.

**3. Add the checks in a ruleset reaching this repository alone.** If none reaches `main`, create a repository ruleset (repository admin is enough) requiring a pull request and the checks: `gh api -X POST repos/{o}/{r}/rulesets --input ruleset.json` with

```json
{
  "name": "main: pull request and checks required",
  "target": "branch",
  "enforcement": "active",
  "conditions": { "ref_name": { "include": ["~DEFAULT_BRANCH"], "exclude": [] } },
  "rules": [
    { "type": "deletion" }, { "type": "non_fast_forward" },
    { "type": "pull_request", "parameters": { "required_approving_review_count": 0,
      "dismiss_stale_reviews_on_push": false, "require_code_owner_review": false,
      "require_last_push_approval": false, "required_review_thread_resolution": false } },
    { "type": "required_status_checks", "parameters": { "strict_required_status_checks_policy": false,
      "required_status_checks": [{ "context": "CI" }, { "context": "Attribution footer" }, { "context": "Closing keywords" }] } }
  ]
}
```

Inside shared organization rulesets, **never add the checks to a shared one** (it blocks merges wherever those jobs are missing): add a ruleset with only `required_status_checks`, a repository one or an organization one whose `conditions.repository_id.repository_ids` is this repository alone. Organization rulesets need `admin:org` (without it, `404` reads like a missing ruleset); the operator runs `gh auth refresh -h github.com -s admin:org`.

**4. Verify from the rules, not the call**: `rules/branches/main` lists `required_status_checks`, a green pull request reads `clean`, and **one with a required check red or missing reads `blocked`**; until that is seen, enforcement is unknown.

## The check

Work these in order, not starting at the gate: cheap early steps can void later ones.

1. **State.** `mergeable_state` clean, **not a draft** (the author's switch: read `draft` from the API, and read it back after `gh pr ready`, as `PATCH` ignores it; an announcement elsewhere is not the flip, and a draft is never a merge candidate). **Read the full head SHA, never pad a short one**: `gh api repos/{o}/{r}/pulls/{n} --jq '.head.sha'` must equal `git rev-parse HEAD`, or stop. A `409` guards nothing.

2. **Base freshness.** `git rev-list --count origin/<branch>..origin/main`: non-zero, sync per [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md) and re-run; zero, say so.

3. **Trial-merge for real; never read `mergeable`** (`null` while computing). Validate **the merged state**. Where another open pull request overlaps (judged on the inputs jobs read, per [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md)), prove the combined state with a trial merge you abort.

4. **Run the gate on the state that will land** (if `main` moved since step 2, re-sync first). **Read the summary, not the exit code, and name the commit it printed.**

5. **Check each of the issue's acceptance criteria against the diff**, not the body's claims. Explain an unmet one ([`closing-a-ticket`](../rule-closing-a-ticket/SKILL.md)); report an unverifiable one **deferred, never ticked**; rewrite one true without the diff.

6. **Ask what this merge will close, beyond the field.** A squash closes from the body and from the message GitHub composes when `commit_message` is omitted. Compare the **pull request's** `closingIssuesReferences` with the intended set:

   ```bash
   gh api graphql -f query='{repository(owner:"<owner>",name:"<repo>"){
     pullRequest(number:N){closingIssuesReferences(first:10){nodes{number state title}}}}}'
   ```

   **Necessary, not sufficient**: the field parses Markdown, the push-time scan reads plain text, so a directive in a code span, a fence or across lines closes unlisted. Enumerate body and branch commits per [`github-api-budget`](../rule-github-api-budget/SKILL.md). **Rewording is the only remedy**: break the adjacency (number first, or no bare keyword in a status cell), re-read the field *and* re-scan the joined text. `.claude/skills/writing-pull-requests/scripts/check-closing-keyword-across-lines.mjs` finds cross-line pairs in the body (`--range` for branch commits) and exits 2 when its same-line control stops matching; its workflow template runs it on every pull request. It judges nothing: whether a close is intended stays this step's.

7. **Sweep the body for plumbing** ([`coordination-plumbing-stays-out-of-artifacts`](../rule-coordination-plumbing-stays-out-of-artifacts/SKILL.md)): it becomes a permanent commit message. Sweep at publishing too; the merge is the last chance.

8. **Account for companion work**: confirm it exists, state the landing order and what breaks otherwise, or say there is none.

9. **Hold the merge when an open issue places a condition on it.** Find them (REST):

   ```bash
   gh api search/issues'?q=repo:{o}/{r}+is:issue+is:open+%23<n>&per_page=100' --jq '.total_count, (.items | length), (.items[] | [.number, .title] | @tsv)'
   gh api repos/{o}/{r}/issues/<n>/timeline'?per_page=100' --jq '.[] | select(.event == "cross-referenced" and .source.issue.state == "open" and (.source.issue.pull_request | not)) | [.source.issue.repository.full_name, .source.issue.number] | @tsv'
   ```

   A hit holds when it requires something before this merges (`hitl` means read it; a mention is noted in the verdict and holds nothing, as does its own ticket). **When one holds, do not merge**: record `<owner>/<repo>#<number>` and the condition, leave it to its owner, add no label or draft flip, re-check next pass; it is met when its issue closes or says so. The search matches a bare number only by chance, is an index and one page (compare items with `total_count`, walk `&page=N`), and it misses an issue in another repository that names this pull request, which the timeline reaches; so neither clears a miss. Show the search finding something before reading an empty result.

10. **Adversarially review what is actually merging**, see below.

11. **Say what you found, on the pull request**, before any merge decision, green or red: "ready" or "not yet, here is what turned up", never a bare yes. It is a published claim ([`adversarial-review`](../rule-adversarial-review/SKILL.md)); a terminal is no record.

## A finding in the description alone

When the only finding is in the description and the gate measured or read the correct text, the gate **rewrites that passage, states before, after, measurement and command in the verdict, and merges**. Anything else (intent, design, work elsewhere, a guess) is handed back. Read the body just before, change only that passage, read it back ([`filing-defects-across-repos`](../rule-filing-defects-across-repos/SKILL.md)).

A person's description edit clears `handed-back` even for a code hand-back; the re-check, finding the head unchanged, hands back again without re-running.

## What a red verdict does next

On a red (a failing chain, or a review finding) the gate **does not flip the pull request to draft**:

1. **Comment the verdict** (step 11), naming the failing job and output.
2. **Record the validated head SHA** in it.
3. **Dequeue and hold: never re-pick an unmoved head.**

4. **Apply the `handed-back` label** in the same turn as the comment. Both are REST writes (`POST` adds to existing labels), so a drained GraphQL quota cannot strand the state.

Not a draft flip: `draft` is the author's signal and costs GraphQL; the pull request stays ready.

**`handed-back` comes off when the pull request RE-ENTERS the queue (a push to the head) or a person edits its body**, never on an author's "ready", a title edit, a bot edit or a comment; a workflow removes it, never the gate by hand.

- It marks a state: label retroactively, once each finding is confirmed outstanding. Queue depth is `open AND not labelled`.
- **The workflow removes it with `DELETE /repos/{o}/{r}/issues/{n}/labels/handed-back`** under its own token (gate sessions cannot); never gate it on a remote-CI switch.
- **If a push did not clear it, read the run**: ticket a failed one (billing included). A conflicted push runs no workflow; the resolving push clears it. Otherwise `PATCH`, which **replaces the whole set**, from a fresh read:

```bash
keep=$(gh api repos/{o}/{r}/issues/<n> --jq '{labels: [.labels[].name | select(. != "handed-back")]}')
gh api -X PATCH repos/{o}/{r}/issues/<n> --input - <<< "$keep"
gh api repos/{o}/{r}/issues/<n> --jq '[.labels[].name]'   # read back: everything but handed-back
```

- **The author never applies or removes it**; one who thinks it wrong tells the gate. Absent means *not handed back*, not *clean*; present blocks nothing, so push whenever ready. It is a gate marker only.

## Adversarial review at the merge

[`adversarial-review`](../rule-adversarial-review/SKILL.md) owns the frame; its build pass saw the branch, not the branch plus what landed since.

- **Review the diff as it will exist on `main`**, including anything pushed after the build review: since-merged interactions, shared inputs, new conventions. If nothing moved, say so; the build review then discharges this.
- **Distrust the tally**: odd skeptic counts, blockers survive a tie, and **check a mechanically checkable refutation yourself**, whatever the margin; counts are for judgment calls only.

Use a `Workflow` under Ultracode or when asked, otherwise the same frame scaled down; run it against the head commit and triage before merging.

## The DRY line

This file owns **the decision to merge** and what the gate does with a red. Elsewhere: the build gate ([`AGENTS.md`](../../../AGENTS.md)), ship-time duties ([`closing-a-ticket`](../rule-closing-a-ticket/SKILL.md)), branch sync ([`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md)), where you branch ([`worktrees`](../rule-worktrees/SKILL.md)), and the composing behavior behind steps 6 and 7 ([`github-api-budget`](../rule-github-api-budget/SKILL.md)). Don't restate them.

Evidence, measurements and history: [`pre-merge-check`](../evidence-behind-rules/pre-merge-check.md).
