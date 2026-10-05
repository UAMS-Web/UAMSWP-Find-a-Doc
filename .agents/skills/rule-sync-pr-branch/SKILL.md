---
name: rule-sync-pr-branch
description: "Sync every PR branch from `origin/main` before the gating run."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/sync-pr-branch.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore disjointness -->
# Rule — sync every PR branch from `origin/main` before the gating run

Before a branch is validated (the gating run) and before its PR is opened, bring it **current with `origin/main`**. This holds for every PR branch.

**Why this is a standing order.** A branch left behind `origin/main` is validated against stale code. When it touches a surface a since-merged change also touched (e.g. a file reformatted by a tree-wide sweep, or a parser a sibling ticket refactored), it can merge to a combined state that was **never tested together**: semantic drift, or un-formatted files slipping past the gate. The failure is silent. The branch's own suite stays green because it never saw the interacting change.

## How to apply

1. **Author side: sync before the gating run and before opening the PR.** When the branch is behind a change that touches the same surface, `git fetch origin && git merge origin/main` (or rebase onto it) *before* running the repository's gate and *before* `gh pr create`. A conflict surfaced here is one caught before review, not after merge.

2. **Validator side: sync before the gating run when the branch is behind an interacting change**, and keep local `main` current so the diff base is right. Diff against the **remote-tracking** `origin/<base>`, never against local `main`: a stale local ref distorts the changed-file set, so a file that needs formatting can go unexamined by a diff-scoped job, and the same stale ref attributes every merge since you last pulled to the other branch, which manufactures an overlap that is not there. But that fixes the *diff base*, a different failure than a *stale branch*: a branch behind an interacting merge still needs the sync above to validate the real combined state.

3. **The exception: genuinely disjoint PRs need not chase every `main` advance. But disjointness is assessed against the inputs a branch's jobs READ, not only the files its diff TOUCHES.** A branch is exempt when it shares neither files *nor job inputs* with the intervening merges; this rule targets branches behind an *interacting* change, and a shared input is an interaction. Verify it rather than assume it: `git diff --name-only origin/main...HEAD` against the same list for the intervening commits, and then the question below.

4. **"No shared file" is not the test. "Nothing the gate reads has moved" is.** Some inputs are consulted for *every* file the chain examines, so two branches can share no filename and still decide each other's result. A changed-file comparison cannot see that by construction: the interaction runs through a file neither branch appears to touch.

   **What to check, as it stands in the repositories that have measured it.** The enumeration is repo-specific and must be measured per repo rather than inherited: a row whose file does not exist in your repository is not a row there.

   | Shared input | Job that reads it |
   | --- | --- |
   | `composer.lock`, `composer.json`, `package-lock.json` | **every job**: the dependency versions it runs against; they are the binaries it invokes |
   | `project-words.txt`, `cspell.json` | `spell` |
   | `phpstan.neon.dist` | `phpstan` |
   | `phpunit.xml` | `pest` |
   | `rector.php` | `rector` |
   | `pint.json` | `pint` |
   | `.github/workflows/ci.yml`, the local runner's script | the chain itself |

   **`composer.lock` belongs at the top.** A `main` advance that moves it changes the binaries every job runs (`vendor/bin/pint`, `vendor/bin/phpstan`, `vendor/bin/pest`), so a branch disjoint from every source file is still not disjoint from its toolchain. [`worktrees`](../rule-worktrees/SKILL.md) documents the sibling hazard: a tree whose `vendor/` predates its lock produces a green that means nothing.

   **`spell` is the sharpest row, because it is the only job that is not diff-scoped, and because adding a word is routine.** It runs `cspell` over the whole tree against a dictionary that is itself a tracked file. `cspell.json` declares `project-words.txt` with no per-path scoping, so the branch's file set is irrelevant to it and intersecting that set with the intervening merges' cannot say anything. A branch that adds or removes an entry is never independent of one whose prose depends on that entry, however disjoint their filenames look. This row is the one that produced `uams-statamic#2228`: a branch file-disjoint from every intervening merge still reported spell issues on its tip and none after merging `origin/main`.

   **The failing direction is specific**, and it is not "the branch is stale" in general. On the author's side: an author uses a word they saw allowed on `main`, and their branch predates the commit that allowed it. That makes the check cheap and per-branch: `git log <base>..origin/main -- project-words.txt` is one read. Across concurrent branches, the direction that bites is **removal or rename**, not addition: an added word can only widen what passes, while an entry a branch's prose depends on disappearing under it turns a green into a red that neither gate saw.

   **Pint is the instructive row**: the *job* is scoped to changed files, and its *ruleset* is not, so scoping the run says nothing about scoping the input. The other rows are shared inputs whose jobs *are* diff-scoped: a smaller and differently-shaped risk than `spell`.

5. **The exception survives, and deliberately so.** For genuinely independent surfaces the rule is right that chasing every advance is waste, and in a repository merging several times an hour it does not converge. What changed is the question: not *do the changed-file sets intersect*, but *did the intervening merge move anything this branch's gate reads*. Answer the second and the first is usually enough to settle it.

## The DRY line

This file is the standing statement of the pre-gate branch sync. It fixes a stale *branch*, which is a different failure from the diff *base* the gate chooses; the branch → PR → merge flow itself lives in [`AGENTS.md`](../../../AGENTS.md) and the [`writing-pull-requests`](../writing-pull-requests/SKILL.md) skill; the gating commands live in the repository's own `composer.json` and `package.json`. It pairs with the [`worktrees`](../rule-worktrees/SKILL.md) rule: a worktree changes *where* you branch, this rule keeps that branch *current*. Don't restate any of them.

