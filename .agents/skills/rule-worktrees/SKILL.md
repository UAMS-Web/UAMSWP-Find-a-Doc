---
name: rule-worktrees
description: "Work from stable, pre-bootstrapped worktree slots (branch work never happens in the primary checkout)."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/worktrees.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore analyse backgrounded deinit Deinit fatals pasteable pathspec rebuildable reflog -->
# Rule — work from stable, pre-bootstrapped worktree slots (branch work never happens in the primary checkout)

Branch work happens in a **fixed, small set of long-lived worktrees** ("slots"), bootstrapped once and kept current, never in a per-ticket throwaway. The primary **holds `main`**, current with `origin/main`, is the diff base, and takes no branch work. Why: two sessions in one tree collide, and a fresh worktree costs a bootstrap, reopens the traps below, and starts the prompt cache cold.

## The slots

The primary and work slots **A** and **B**, plus at most one tree for a specific job. **Slot names must not begin with a digit**: Pest builds a namespace from the path, so every test fails (`Unable to create test case`).

A local CI runner validates the checkout it runs from: run it in the slot that holds the branch.

## Which tree does the work

- **All branch work goes to a slot**, solo or concurrent. Claim, work, release.
- **The primary stays on `main`**; a branch there means drift.
- **Churn means isolate first**: a slot changing under you is a live second session; take the other slot or an ephemeral worktree.

## A slot at rest, and how occupancy is read

**At rest a slot is detached at `origin/main` with a clean tree.** `main` can live only in the primary. The slot's branch is the occupancy signal, but `git worktree list` cannot see uncommitted work, so read both:

```bash
git worktree list
git -C <slot-path> status --porcelain --untracked-files=all
```

Free means detached **and** the second prints nothing. A slot on a branch is held.

- Leave a slot detached when done; commit or clear your work before stepping away.
- **A detached, dirty slot is someone's: leave it, ask whose it is, do not clean it or take it.**

## Claiming and releasing a slot

Take **A**, then **B**. Both held means two live sessions: never evict, take an ephemeral worktree. **Claim by branching from `origin/main`**, never from what the slot sits on:

```bash
cd ../<repo>-a
git fetch origin
git checkout -b <N>-<slug> origin/main
```

**Release when a ticket ships**, or the next ticket silently branches off the last tip and carries its scratch:

```bash
cd ../<repo>-a
git status --porcelain          # must be empty
git fetch origin
git checkout --detach origin/main
```

`git status` misses empty directories: look for litter after an interrupted run. **Never delete the branch**, and never tear the slot down.

## Keeping a slot current: the refresh gate

A stale slot passes **silently** against the wrong dependencies. **Whenever a tree's base moves** (release, or a sync per [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md)), refresh it before trusting a check, the primary included:

```bash
cd ../<repo>-a
composer install    # no-op when current, so always run it
npm ci              # only when package-lock.json moved; never npm install
```

Refresh whatever else the bootstrap installed too. A gate that depends on remembering commands is not a gate: where a tree-checking script exists, it is the gate (exit 1 on drift).

- **Read a guard's status before any pipe**: `--check | tail; echo $?` reports `tail`. Use `>/dev/null 2>&1; echo $?` or `set -o pipefail`.
- **A guard inside the tree it checks misses checks newer than its copy** and reports clean. When it must be right, ask git (`git fetch origin` first):

```bash
TREE=../<repo>-a
git -C "$TREE" rev-list --count HEAD..origin/main   # 0 = current, N = behind
```

- **In a pasteable recipe, put a file-like path in a variable**, never inline `<tree>` (a redirect). In prose that may reach a shell, write `→` or quote the construction, never `>` or `->`.

## Provisioning a slot (one time, and once only)

1. **Layout:** `<repo>-a` and `<repo>-b`, **siblings** of the primary. Where the host app scans that directory (a WordPress plugin), dot-prefix them, inside it if tests locate the host relatively.
2. **Create at rest:** `git fetch origin && git worktree add --detach ../<repo>-a origin/main`.
3. **Bootstrap in place:** `composer install`, `npm ci` with a lockfile, and whatever else the checks and app need. All of it must be ignored or a submodule: confirm a clean `git status`.
4. **Never junction or symlink `vendor/` from another tree**: Pest then loads the donor's `tests/Pest.php` (tell: `Call to undefined function` for a new helper). A `node_modules/` junction is safe.
5. **Ignore `.claude/worktrees/` in the tracked `.gitignore`.**

## Ephemeral worktrees

Only when both slots are held, for file-disjoint fan-out, or for subagents (`isolation: "worktree"`). Use `EnterWorktree` (this rule authorizes it):

- `.claude/worktrees/` is ignored by the tracked `.gitignore`; confirm with `git check-ignore -v .claude/worktrees/x`.
- **Name it `t<N>-<slug>`**, never digit-first.
- **Prefer `EnterWorktree` to `git worktree add`** (session metadata); for a branch name, `git checkout -b` inside it. Go manual only for a pre-existing branch you must not re-create.

## Committing and pushing needs nothing extra

A fresh worktree can commit and push at once. A push failing on a missing `vendor/`: **bootstrap, don't `--no-verify`**.

## File-tool vs. shell-cwd hazards (harness)

- **File tools act on the absolute path you pass.** Pass the slot's path and `Read` that copy first. Misdirected work: `cp` it over, then `git -C <wrong-tree> checkout -- <file>`.
- **The Bash cwd silently reverts** (to the primary or the other slot): `cd` in every command, edit with file tools, prove the tree in long runs:

```bash
cd ../<repo>-a && echo "CWD=$(pwd) HEAD=$(git rev-parse --short HEAD)" && <the real command>
```

- **`git checkout -- <file>` discards all its uncommitted work**: use it only where the file should match HEAD; undo a temporary edit with its inverse or a saved copy. A never-committed file lost this way can often be rebuilt from the framework's compiled view (Laravel: `storage/framework/views/`), the session transcript's `Read`/`Edit` results and HEAD; re-verify it before trusting it.
- **Shared read-modify-write surfaces** (the stash, project memory, an issue or PR body): append, never rewrite. Every slot shares memory: **never write a memory file whole**; assert your anchor occurs once and replace only it. That guard misses other edits, so delete only what you wrote, still as you wrote it.
- **Never use the stash to set work aside or stage a temporary revert**: every worktree shares it, and a failed `push -- <path>` makes the `pop` take someone else's. Use a WIP commit or a copy. If you must: `git stash push -u -m "<tag>"`, capture the SHA (`git stash list --format='%H %gs'`), `git stash apply <sha>`, never bare `pop`. Recover a wrong pop:

```bash
git fsck --unreachable --no-progress | awk '/commit/ {print $3}' |
  while read c; do git log -1 --format="$c %s" "$c"; done | grep -E ' (On|WIP on) '
git stash store -m "<the original message>" <sha>
```

  Then take the popped files back out of your tree (`git restore --staged` and `rm` for its untracked adds, `git checkout --` for tracked ones) and confirm with `git stash list` that the stack is the depth it was.

## Rebuilding a slot, and removing an ephemeral worktree

Rebuild a slot only when refresh cannot fix it. `git worktree remove --force` works with submodules initialized. Before `git worktree move`, run `git submodule deinit -f --all`; or rename and `git worktree repair <new-path>`.

When an OS handle blocks removal, `rm -rf <path>` then `git worktree prune`:

1. Move every shell's cwd out; afterward call a runner by absolute path.
2. Stop watchers run from it (`composer dev`, `npm run dev`, `artisan serve`).
3. A denied rename on Windows is usually one transient folder handle: delete the contents and prune.
4. Save untracked scratch first.
5. Delete a junction as a link (`cmd //c rmdir <path>`, or `[System.IO.Directory]::Delete($path, $false)` in PowerShell) first, then confirm its target survives.

After a half-delete, `git worktree prune --expire=now` frees the branch. If PHPStan names a deleted tree's phar, use a config that `includes:` `phpstan.neon.dist` with a private `tmpDir` (`clear-result-cache` does not help; never wipe the shared system-temp cache).

## The DRY line

This file owns worktree lifecycle. `EnterWorktree` mechanics stay in its description; branch currency is [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md); process attribution is [`long-running-commands`](../rule-long-running-commands/SKILL.md).

Evidence, measurements and history: [`worktrees`](../evidence-behind-rules/worktrees.md).
