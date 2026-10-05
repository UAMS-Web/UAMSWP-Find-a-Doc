---
name: update-dependencies-ticket
description: >-
  Re-confirms the `cweagans/composer-patches` stack on every Dependabot pull request that
  changes `composer.lock`: check out its branch in a slot, confirm every patch the repository
  carries still applies and is still needed (whether or not its own package moved, directly or
  transitively), retire a patch whose bug upstream
  has fixed (in the order the `upstream-patch-lifecycle` skill sets), re-roll one that no longer
  applies but is still needed, validate with the repository's own gate, and record the outcome
  on the pull request. Dependabot does the dependency updates themselves; there is no periodic
  sweep and no update-dependencies ticket. Activate when a Dependabot pull request touches
  `composer.lock` in a repository that has `patches/`, `extra.patches` or `patches.json`, or when
  asked whether a Dependabot bump affects a patch.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/update-dependencies-ticket/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
<!-- cspell:ignore Dependabot dependabot gitsubmodule -->

# Re-confirm composer patches on a Dependabot bump

**Dependabot handles dependency updates across the organization.** It opens the pull requests
that move Composer (and npm) packages, so there is no periodic dependency sweep and no recurring
update-dependencies ticket to file or work. This skill has one job: **when a Dependabot pull
request changes `composer.lock`, re-confirm every composer patch the repository carries still
applies and is still needed, and retire any whose bug upstream fixed.** Not only the patches on
packages the pull request names: a patched package can move transitively, without its name in
the title, and a patch can stop applying or stop mattering when something it touches moves. So
the check covers every patched package, direct or not, and rides on each pull request that
changes the lock rather than waiting for a patched package itself to be the one bumped.

The lifecycle of a patch, its registration forms, and the removal procedure are the
[`upstream-patch-lifecycle`](../upstream-patch-lifecycle/SKILL.md) skill. This skill is where a
patch is *noticed* to have become retirable or broken; that skill says what to do about it.

## When this applies

Every Dependabot pull request that changes `composer.lock` applies, in a repository that
carries composer patches. **Read the lock, not the title:** a grouped pull request moves several
packages, and a patched package can move **transitively** (`pestphp/pest-plugin-mutate` is pulled
in by `pestphp/pest`, so a Pest bump can move it without naming it; Dependabot's Composer updates
cover direct dependencies, so it never opens a pull request for such a package on its own). From
a tree holding the pull request's branch, list each patched package with its version on `main`
and on the branch (needs `jq`):

```bash
git fetch origin
for p in $( { jq -r '.extra.patches // {} | keys[]' composer.json
              [ -f patches.json ] && jq -r '.patches // {} | keys[]' patches.json; } ); do
  printf '%s  %s -> %s\n' "$p" \
    "$(git show origin/main:composer.lock | jq -r --arg p "$p" '[.packages[], .["packages-dev"][]] | map(select(.name == $p)) | .[0].version')" \
    "$(jq -r --arg p "$p" '[.packages[], .["packages-dev"][]] | map(select(.name == $p)) | .[0].version' composer.lock)"
done
```

`extra.patches` in `composer.json` is the application form and `patches.json` the package form
(see the lifecycle skill). **Every patched package goes through the steps below**, whether its two
versions differ or not; a line whose versions differ says which patches are most at risk, and
those are checked first. **No line printed means the repository registers no patch**, not that
nothing moved; check that the registration is where the loop looks before trusting an empty
result. Only in a repository with no composer patches does this skill not apply.

## Step 1: check the branch out in a slot

The check runs `composer install` against the pull request's lock, which swaps out `vendor/`, so
it runs in a **slot** per the [`worktrees`](../../rules/worktrees.md) rule, never the primary
checkout. Check the Dependabot branch out there (`git fetch origin`, then check out
`dependabot/composer/…`), refresh or bootstrap the slot, and confirm `git status` is clean. Pass
the **slot's** path to all file tools from here on.

## Step 2: does each patch still apply?

Run `composer install` on the branch's lock and read the patch names in its output. Every
registered patch must apply. **Treat a failed apply as fatal** whether or not the repository sets
`composer-exit-on-patch-failure`: a patch that silently fails to apply while still registered is a
green run validating unpatched code.

A patch that fails to apply means the patched upstream file moved. Either the bump **is** the
upstream fix (Step 3), or the fix has not shipped and the patch needs re-rolling against the new
version: refresh the `.patch`, relock (`composer patches-relock`, and `composer.lock` too where the
lifecycle skill's *The relock* says so), and run `composer install` again.

## Step 3: is each patch still needed?

For every patch, check its **removal criterion** (the upstream fix it waits
for) against the version the branch now installs, the way the lifecycle skill's *Checking whether
the fix shipped* does it (a tag past what was locked, and that tag containing the fix). A patch
that still applies is not thereby still needed: an upstream fix can land in a different hunk.

- **Still needed:** leave it. Note it on the pull request (Step 5).
- **Fixed upstream:** retire it on this branch, following the lifecycle skill's *Removing a
  patch*. Dependabot has already moved the package, so that procedure's targeted
  `composer update <vendor/package>` step is the bump this pull request carries; do the rest in
  its order: the patch file, its registration, the relock, the guard test and `phpstan.neon.dist`
  entries, the narrative, any watch row, the cold `composer install`, the gate run, and the final
  `git grep`. If it was the last patch, retire `patches.lock.json` (and `patches.json` where used)
  and the `cweagans/composer-patches` dev dependency with it. Where the patch had a ticket chain,
  this pull request closes its removal ticket (ticket 4).

Commit `patches.lock.json` with any change to the stack: its `_hash` and per-patch `sha256` change
with it. Use the `build:` prefix ([`writing-commits`](../writing-commits/SKILL.md)).

**Pushing to a Dependabot branch.** Commits pushed to the pull request's own branch keep the bump
and its patch change in one reviewable unit. Once someone else has pushed to it, Dependabot stops
rebasing that pull request; do not then comment `@dependabot rebase` or `@dependabot recreate`,
either of which can rebuild the branch from Dependabot's own commit and drop yours. Bring the
branch current per [`sync-pr-branch`](../../rules/sync-pr-branch.md) instead.

## Step 4: validate with the repository's own gate

Remote CI is off in the private repositories (the `RUN_REMOTE_CI` kill-switch), so validation is
local. Run the repository's gate, not another repository's: `composer audit`, then the Composer
`ci` script where the repository defines one (`composer ci`), then the local CI runner for full
job parity. Where a patch retired, the gate must exercise the now-unpatched tool and be **seen**
green (the lifecycle skill's *Proving it worked*).

Those runs can be multi-minute. Bound them per the
[`long-running-commands`](../../rules/long-running-commands.md) rule, and note **macOS ships no
`timeout(1)`**, so on a Mac use that rule's perl-alarm fallback.

## Step 5: record the outcome on the pull request

Say on the pull request, per patch, which of three outcomes it had: **still
applies and still needed**, **re-rolled** (and why the old hunk stopped applying), or **retired**
(naming the upstream commit or release that fixed it). Put it in the body's `## Out of scope` or a
`## Patch set changes` section per [`writing-pull-requests`](../writing-pull-requests/SKILL.md),
written to a file and passed with `--body-file` or `-F body=@…`. A reviewer reading a bump to a
patched package should not have to infer whether anybody looked.

Then **release the slot** per the [`worktrees`](../../rules/worktrees.md) rule and refresh it, so
its `vendor/` returns to `main`'s lock files.

## The DRY line

This file owns one check: re-confirming the composer patch stack on a Dependabot bump. The patch
lifecycle, its registration forms and the removal order →
[`upstream-patch-lifecycle`](../upstream-patch-lifecycle/SKILL.md); worktree lifecycle →
[`worktrees`](../../rules/worktrees.md); REST-vs-GraphQL →
[`github-api-budget`](../../rules/github-api-budget.md).

