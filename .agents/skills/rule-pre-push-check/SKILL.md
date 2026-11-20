---
name: rule-pre-push-check
description: "Run the fast local checks before every push."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/pre-push-check.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
# Rule — run the fast local checks before every push

**Before every push to a pull-request branch, run the checks below that the repository carries, and push only when all of them pass.** They take seconds, none of them needs Pest, and each one reports exactly what a remote job would report minutes later. This is the author-side counterpart of [`pre-merge-check`](../rule-pre-merge-check/SKILL.md): that rule re-verifies at the merge, and this one keeps a branch from reaching the remote with a failure its author could have seen first.

## Why this is a standing order

On 2026-11-19 the failed checks on eight open `UAMS-Web/uams-statamic` pull requests were read from their job logs, and four failures could have been caught before the push without running Pest (UAMS-Web/uams-claude-skills#854):

- `#1140`: cspell reported an unknown word in `.github/workflows/ci.yml`, and `check-mutate-ignore-breadth` flagged one `@pest-mutate-ignore` marker that removes 85 mutants.
- `#1133`: `check-mutate-ignore-breadth` flagged five markers, four of which remove no mutant.
- `#1122`: `check-parallel-safe-tests` flagged a test that is not safe to run in parallel.

**The job name did not point at the cause.** Both scripts ran inside jobs labelled "Encoding guard" and "Test isolation guard", so each failure cost a round trip through a log before anyone knew which check had failed. The project owner set this list as a standing instruction the same day; written here, it reaches a session that was not running when it was given.

## The checks

Run each one the repository carries, with the repository's own command (its `composer` or `npm` script, or `vendor/bin/<tool>` where it has none). A check the repository does not carry is skipped, and that is the only reason a check is skipped.

| Check | Run it | What it catches before the remote does |
| --- | --- | --- |
| Pint | in check mode (`--test`) | formatting |
| PHPStan or Larastan | the repository's analysis script, at its configured level | type and call errors |
| Rector | as a dry run (`--dry-run`) | a refactor the configuration would apply |
| cspell | over the changed files, with the repository's own configuration and word lists | an unknown word, in prose or in a workflow file |
| `check-mutate-ignore-breadth` | `php .claude/skills/pest-testing/scripts/check-mutate-ignore-breadth.php` | a `@pest-mutate-ignore` marker that removes no mutant, or more than one |
| `check-parallel-safe-tests` | `php scripts/check-parallel-safe-tests.php`, where the repository has that script | a test that is not safe to run in parallel |

The commands, levels and configuration for Pint, PHPStan, Larastan and Rector are the [`code-quality`](../code-quality/SKILL.md) skill's. What `check-mutate-ignore-breadth` measures, its baseline and its exit statuses are the [`pest-testing`](../pest-testing/SKILL.md) skill's.

## How to apply

1. **After the last edit, before the push.** A check run before a later edit vouches for a tree that is not the one being pushed. An amend, a rebase or a merge of `main` is an edit.
2. **Push only when every check the repository carries passes.** Fix a failure first, then run that check again. A failure is not waived because the remote will report it too: the round trip it costs is the reason this rule exists.
3. **Read each result, not only its exit status.** A check that printed nothing may not have run, and a pipeline reports its last command ([`reading-exit-status`](../rule-reading-exit-status/SKILL.md)). A redirect with nothing after it keeps the status the check's own.

## What this does not cover

- **Pest and mutation runs.** They still run as each ticket and the CI chain require, and their evidence is the ticket's. This list is the fast set that needs neither.
- **What may be pushed.** This rule adds checks before a push; it does not change who may push, merge or install anything.
- **A git `pre-push` hook.** Nothing here installs or requires one, and the CI chain's own jobs are unchanged.

## The DRY line

This file owns **which fast checks run before a push**. The commands and configuration are the [`code-quality`](../code-quality/SKILL.md) and [`pest-testing`](../pest-testing/SKILL.md) skills'; the decision to merge is [`pre-merge-check`](../rule-pre-merge-check/SKILL.md)'s; bringing a branch current before its gating run is [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md)'s. None of them is restated here.
