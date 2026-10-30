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

Branch work happens in one of a **fixed, small set of long-lived worktrees** ("slots") that are bootstrapped once and kept current, never in a worktree created for the ticket and thrown away after it. The primary checkout (the repo root of your main clone; its absolute path differs per machine and per platform, so this rule never names one) is the reference tree: it **holds the `main` branch**, current with `origin/main`, it is the diff base, and branch work does not happen there.

**Why slots rather than a worktree per ticket.** Two sessions on one working copy collide by construction: a branch checkout in one swaps the tree out from under the other, one session's `git stash` gets popped by the other, a stray half-finished file rides along in the other's commit. So work is isolated per tree; the unit is a long-lived slot rather than a fresh worktree, because a fresh worktree is not free:

- **It costs a bootstrap** (`composer install` and whatever else the repository's bootstrap needs (see *Provisioning a slot*)) every time, for work that is usually sequential anyway.
- **It re-opens every bootstrap failure mode this file documents**, and those are the expensive part. A `vendor/` junction silently loads the donor worktree's Pest bootstrap; a directory name starting with a digit fatals every test before it runs. Each detour costs far more than the install it was trying to skip.
- **It changes the session's working-directory string**, which is part of the cached prompt prefix, so a never-before-seen path starts every session cold. A fixed set of paths lets that prefix recur. (Secondary: the cache TTL is an hour, so this pays within a working day, not across days. The bootstrap and the failure modes above are the dominant cost.)

A slot is bootstrapped once, so solo work has no cost reason to stay in the primary either.

## The slots

The set is fixed and small: the primary and two work slots (**A** and **B**). A repository may add one more tree for a specific job. Where the slots sit is decided per repository, because it depends on what scans the directory the primary lives in. A repository with no slots yet sets them up as *Provisioning a slot* describes.

A local CI runner, where the repository has one, has no tree of its own: it validates the checkout it is run from.

**Slot names must not begin with a digit**: Pest builds its generated test class's namespace from the file path with separators stripped, so a path segment starting with a digit is an invalid PHP namespace and every test in the tree dies with `Unable to create test case for test file at …` before it runs. Names of the form `<repo>-a` satisfy this by construction; the constraint only bites when you name an *ephemeral* worktree after a ticket number (see below).

## Which tree does the work

- **All branch work → a slot.** Claim one, work in it, release it. This holds for a solo session as much as a concurrent one: a session that never touches the primary cannot collide with one that does, and the slot is already bootstrapped so there is no reason to prefer the primary.
- **The primary → `main`, and left there.** It is the tree to diff against. Leaving it on a ticket branch also destroys the oldest drift signal there is: "the primary is on a branch I did not set" no longer means anything if you routinely put it there yourself.

- **Local CI → the slot that holds the branch.** Where the repository has the shared local CI runner, run it in the slot you are working in; it validates that checkout and nothing else.

**Detected churn still means isolate first.** If you observe a slot moving under you: the branch changed, untracked files you did not create appear, `git worktree list` shows a slot on a branch you did not set: treat it as a live second session on that slot and take the other one, or an ephemeral worktree if both are held.

## A slot at rest, and how occupancy is read

**A slot at rest is detached at `origin/main`, with a clean tree: both halves, not one.** Git allows a branch to be checked out in exactly one worktree, so `main` can only ever live in one tree: the primary. Two slots both "on `main`" is not a state that exists; `git worktree add` refuses it outright (`fatal: 'main' is already used by worktree at …`). Detaching sidesteps the constraint entirely and costs nothing, because a slot at rest is not a place work happens.

There is no lock file and there does not need to be one: **the slot's own branch is the occupancy signal.** A slot holding a branch is a slot in use, and `git worktree list` answers that half. But it reports the checked-out ref alone; it says nothing about the index or the working tree, so a slot that is detached and carries **uncommitted work** reads exactly like a genuinely free one to that command. Read both:

```bash
git worktree list
git -C <slot-path> status --porcelain --untracked-files=all
```

A slot is free only when it is detached **and** that second command prints nothing. A slot on a `<N>-<slug>` branch is **held**: by you earlier, or by another session right now.

- **Leave a slot detached when you finish with it**: one left on a merged branch reads as occupied to everyone else.
- **Commit or clear your own work before stepping away, and always run the tree check before taking a slot that looks free.**
- **If you find a slot detached and dirty, leave it and ask the other sessions whose it is: do not clean it up, and do not take it.** Cleaning up looks like the tidy move and is the one that loses somebody's patch.

## Claiming and releasing a slot

Take **A** first, **B** second. If both are held and you did not hold either, you have two live sessions; do not evict one, take an ephemeral worktree (see *Ephemeral worktrees*).

**Claim a slot by branching from `origin/main`**, never from whatever the slot happens to be sitting on:

```bash
cd ../<repo>-a
git fetch origin
git checkout -b <N>-<slug> origin/main     # or: gh issue develop <N>, then check it out
```

**Releasing a slot is a real step, not a formality.** When a ticket ships, return the slot to rest:

```bash
cd ../<repo>-a
git status --porcelain                     # MUST be empty, see the litter note below
git fetch origin
git checkout --detach origin/main
```

Three things go wrong when this is skipped, and all three are silent:

- **The next ticket branches off the last ticket's tip** instead of current `main`, so its PR carries commits that belong to another ticket and its diff against `main` is wrong. This is the single largest new hazard the slot model introduces, and it has no loud failure: everything builds, the branch is just wrong.
- **Untracked scratch from the previous ticket rides into the next commit.** A slot accretes import XML, generated fixtures, notes. Check before you branch, not after you commit.
- **`git status` is blind to empty directories**, so test litter left by a run killed mid-write does not show up there at all. Look for it directly when a slot has hosted an interrupted run.

**Never delete the branch**: releasing the slot moves the tree off it, and `git checkout` does not touch the branch. The slot itself is never torn down.

## Keeping a slot current: the refresh gate

This is the risk the slot model **introduces**, and it is worse than the one it removes. A fresh worktree fails *loudly*: no `vendor/`, hard error, you notice. A stale slot fails *silently*: the tests run, they pass, and they pass against the wrong dependency versions. The checks invoke the repo-pinned `vendor/bin/{pint,phpstan,pest}`, so a slot whose `vendor/` predates its branch's `composer.lock` produces a green that means nothing. This is not hypothetical: in `uams-statamic` the primary checkout's `vendor/` sat at Pest 4.7.5 while `composer.lock` pinned 5.0.1, and nothing said so.

**So this is a gate, not advice.** Whenever a slot's base moves: you just released it to a newer `main`, or you synced a branch per the [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md) rule: refresh **before** running any check whose result you intend to trust. The minimum, in any repository, and the whole of it where nothing else is bootstrapped:

```bash
cd ../<repo>-a
composer install                                  # no-op when vendor/ matches composer.lock
npm ci                                            # ONLY if package-lock.json moved, see below
```

- **`composer install` is a no-op when current**, so there is no reason to reason about whether it is needed: just run it. It prints `Nothing to install, update or remove` when the slot was already right.
- **`npm ci`, not `npm install`**: `install` will happily reconcile a drifted tree against the manifest and leave `package-lock.json` modified; `ci` installs exactly the lock. Only needed when the lockfile actually moved.
- Whatever else the repository's bootstrap put in place (submodules, indexes, built assets) has its own refresh step.

**The primary needs this as much as any slot does**: the Pest 4.7.5-against-a-5.0.1-lock drift above was found *there*.

**A gate that depends on remembering commands is not a gate**, which is why a repository that carries a tree-checking script makes the script the gate: a check that exits 1 when a tree has drifted works as a shell guard ahead of a long run. What such a check looks at differs per repository, because a guard is only as good as what it looks at. Each tree is judged against its **own kind** (an ephemeral worktree has different expectations from a work slot) because a guard that can never go green is not a guard; it is a line people learn to skip.

**A pipe replaces a guard's exit code with the pager's, so read the status before any pipe.** `… --check 2>&1 | tail -6; echo $?` reports whether `tail` succeeded, which it always does; the guard's `1` is gone and a `0` beside a clean-looking table reads exactly like a pass. On 2026-09-06, in `wordpress-importer`, five false greens were published in one evening by four concurrent sessions on exactly that shape, two of them by sessions that already knew about the trap; one had merged a change about it hours earlier and ran the piped form anyway, and the instances came from both macOS and Windows (Git Bash) sessions: the shape is POSIX pipeline semantics rather than anything platform-specific. Use `>/dev/null 2>&1; echo $?`, or `set -o pipefail`, or capture the status before piping. The exit code is the contract; a verdict line a guard prints is a second channel for the case where the code has already been thrown away, never a replacement.

**A guard shipped inside the artifact it guards cannot report on states that predate the guard, and the reason is structural rather than a defect in it.** The script lives *in the tree it inspects*, so a tree far enough behind is running a copy that predates a check: it reports no drift of that kind, `--check` exits `0`, and that is the value a shell guard reads as permission to proceed. The two kinds of staleness fail differently, and the second is worse:

| What is stale | What happens |
| --- | --- |
| `vendor/` | the check runs, against the wrong dependency versions |
| the tree-checking script itself | the check does not run, and its output does not look truncated: it looks complete |

Anything added to a tree-checking script later (or to any tool that ships in the tree it validates) has the same blind spot at the same place: it is silent exactly where the artifact is most out of date, and silence from it is indistinguishable from health.

**One probe cannot be degraded by any copy's age, because it lives outside the tree:**

```bash
TREE=../<repo>-a
git -C "$TREE" rev-list --count HEAD..origin/main   # 0 = current, N = N commits behind
```

Measured in `wordpress-importer` on macOS (Darwin 25.6.0) against all four trees: it returned `0` for three and `1` for the slot that was genuinely behind. Reach for it when the answer has to be right and you cannot vouch for which copy is executing. It does not fetch (a command that reaches the network on every invocation is one people route around) so a stale remote-tracking ref under-reports; `git fetch origin` before trusting a zero.

**The path is assigned on its own line rather than written `<tree>` inline, and that is deliberate.** An angle-bracket placeholder is this corpus's convention and reads correctly as prose, but in a pasteable fenced recipe it sits where the shell expects a redirection: `<tree>` is an input redirect from a file named `tree`, and `>` before `rev-list` an output redirect to a file named `rev-list`. Pasted with no such file present it aborts harmlessly on the missing input; pasted in a directory that happens to contain a file named `tree`, it runs git on mangled arguments and **creates a file called `rev-list`**. Measured both ways in `wordpress-importer`, macOS and Git Bash on Windows 11, with identical results, and `bash -n` reports the line as syntactically clean either way, so nothing warns a reader who copies it. This is not a call to sweep the other placeholders: `<N>`, `<slug>`, `<branch>` and the rest name no plausible file, so the input redirect fails and the command aborts before any output redirect applies, and inline code inside prose is read as an illustration rather than copied as a command. A placeholder is exposed only when both halves hold: a pasteable fenced recipe and a name that is a plausible file. **Prose has no such protection: every word in it is a valid filename**, so a bare `>` between two words is a redirect wherever it appears, including inside narration that reaches a shell. Measured in the `wordpress-importer` primary on 2026-09-06: four empty files, `ci.yml`, `cspell`, `four,` and `still`, one mtime to the second, from a single line of explanatory text. Write the arrow as `→` (U+2192), which this corpus already uses for version transitions, or quote the whole construction. An ASCII arrow does not help and is the trap it looks like a fix for: measured in `wordpress-importer` on macOS (Darwin 25.6.0), a hyphen followed by the redirect character still redirects, because the hyphen is parsed as an argument and the file is created regardless.

## Provisioning a slot (one time, and once only)

A slot is created once and kept current by the refresh gate from then on; it is never torn down and recreated per ticket. A repository adopting this model with no slots yet needs the following, and nothing this rule has not already said:

1. **Choose the layout.** Two work slots named after the repository: `<repo>-a` and `<repo>-b`. Make them **siblings** of the primary: they sit outside the repo, so no ignore rule has to hold for them, and a search from the primary cannot descend into them. **If the primary sits in a directory the host application scans**: a WordPress plugin under `wp-content/plugins/`: a plain sibling is a second copy of the plugin on the plugins screen; dot-prefix the slot names (`.<repo>-a`) so the host skips them before opening them, and keep them inside that directory if the test bootstrap locates the host by relative position. Slot names must not begin with a digit (see *The slots*).
2. **Create each slot at rest**: detached, because `main` belongs to the primary:

   ```bash
   git fetch origin
   git worktree add --detach ../<repo>-a origin/main
   ```

3. **Bootstrap it in place.** `composer install`, then `npm ci` where the repository has a `package-lock.json`, then whatever else the repository's checks and its served app need before the slot behaves like the primary: submodules, an `.env`, local databases, built assets. Everything provisioning writes must be git-ignored or a submodule, so none of it can ride a commit: confirm with a clean `git status` afterwards.
4. **Never junction or symlink `vendor/` from another tree to skip `composer install`.** PHP resolves `__DIR__` *through* the link to the real path, so `vendor/bin/pest` reports the **donor** tree . Pest derives its root from that and loads the **donor's** `tests/Pest.php` instead of yours. Your test *files* run; somebody else's bootstrap configures them. The tell is a helper you just added being `Call to undefined function` while pre-existing helpers resolve fine, and a stack trace naming the other tree's vendor path. It cuts both ways: a bootstrap regression passes, and a bootstrap fix appears not to work. A `node_modules/` junction is safe: cspell and the JS tooling do no `__DIR__`-based root detection.
5. **Make `.claude/worktrees/` ignored by the tracked `.gitignore`**, so an ephemeral worktree can never be staged from any checkout (see *Ephemeral worktrees*).

## Ephemeral worktrees

Slots cover ordinary work. A throwaway worktree is still right in exactly three cases:

- **Both slots are held** and you need a third tree now.
- **Genuinely file-disjoint parallel fan-out**: independent tickets built at the same time, one tree each, when neither slot is free for the second.
- **Subagents**: the `Agent` tool's `isolation: "worktree"`, which is a different mechanism aimed at a different problem and is unaffected by any of this.

For those, the harness `EnterWorktree` tool creates a worktree under `.claude/worktrees/<name>/` on a fresh branch from `origin/<default>` and switches the session into it. It is gated to fire only on a user request *or a project instruction*: this rule is that instruction. Three caveats:

- **`.claude/worktrees/` must be ignored by the tracked `.gitignore`**, not by a per-machine `.git/info/exclude`, so that "can never be staged by construction" holds on every clone. Confirm with `git check-ignore -v .claude/worktrees/x` if you ever doubt it.
- **Name it `t<N>-<slug>`, not `<N>-<slug>`**, for the digit/namespace reason above. The branch name is unaffected and still comes from `gh issue develop`. Verified in `uams-statamic` against the pinned Pest with a clean negative control: a `tests/Feature/9999Probe/` directory errors while an identical `tests/Feature/t9999Probe/` passes.
- **Prefer `EnterWorktree` over `git worktree add` + entering it.** Entering a worktree the harness did not create is recorded as `"enteredExisting": true` in the session's `worktreeSession` metadata and carries no `originalBranch` / `originalHeadCommit`, so the session shows no worktree marker in Claude Code's session history, and its transcript is filed under the *worktree's* project slug rather than the repo's. Wanting a specific branch name is not a reason to take the manual path: create with `EnterWorktree`, then `git checkout -b <branch>` inside it; the metadata is written on entry, so whatever branch you end up on afterwards does not affect it. Reserve the manual path for what `EnterWorktree` cannot express: adding a worktree for a **pre-existing** branch you must not re-create.

## Committing and pushing needs nothing extra

A fresh worktree can branch, commit, and push immediately, even though `vendor/` is git-ignored and therefore absent from it. A worktree runs whatever hooks the repository configures; in `uams-statamic` and `wordpress-importer` no pre-commit hook runs tests, Pint or PHPStan, and neither has a pre-push hook. Running the checks *does* need a bootstrap, which is what provisioning is for. A push that fails on a missing `vendor/` is an un-bootstrapped *environment*, not a real defect: **bootstrap, don't `--no-verify`**.

## File-tool vs. shell-cwd hazards (harness)

These matter **more** with long-lived slots, not less: every slot is always present, always on some branch, and always a plausible destination for a misdirected write.

- **`EnterWorktree` switches the shell cwd, but `Edit`/`Write`/`Read` act on the absolute path you pass.** Keep passing a primary-checkout path and your edits land on the primary, on `main`. Always pass the **slot's** absolute path (in your platform's own form) to the file tools, and `Read` the slot's copy before editing it: read-state is tracked per absolute path. If work lands on the wrong tree: `cp` the changed files where they belonged, then `git -C <wrong-tree> checkout -- <file>` (or delete the stray untracked file) to restore it.
- **The Bash session cwd silently reverts between calls.** Sometimes it prints `Shell cwd was reset to …`; sometimes it is simply back at the primary several calls later with nothing said. A backgrounded command inherits whatever the cwd is at launch. The failure is a **false green**: a verification run meant to exercise your branch instead exercises `main` and passes, proving nothing. Put an explicit `cd` in **every** command, and bake a guard into anything long or backgrounded so the output proves which tree ran:

  ```bash
  cd ../<repo>-a && echo "CWD=$(pwd) HEAD=$(git rev-parse --short HEAD)" && <the real command>
  ```

  With slots this guard matters more than with per-ticket trees, because slots do not differ by name: `<repo>-a` and `<repo>-b` look alike, and only `HEAD` distinguishes them.

  **It is not only the primary, and it is not only runs.** The stale cwd is just as likely to point at the *other slot*, so "did it land on `main`?" is the wrong check; "which tree did it land on?" is the right one. And it corrupts **writes**, not just verification: an in-place shell edit (`sed -i`, a `python` heredoc, `cat >`) with no `cd` of its own silently rewrites the other tree's file, leaving the one you meant to edit untouched and green. Prefer the file tools with **absolute** paths for edits; when a shell edit is genuinely easier, give that command its own `cd`.
- **`git checkout -- <file>` reverts the file to HEAD: it discards *all* uncommitted work on it, not just your last change.** Use it ONLY where the file *should* match HEAD (restoring a tree you wrote to by mistake). **Never** reach for it to "undo" a *temporary* edit on a file that holds uncommitted work you want to keep: e.g. hand-mutation-verifying a template change (revert the fix → expect-red → restore), where the file is template-only (so `composer mutate` cannot help) and the work is not committed yet. Restore the temporary mutation with its **inverse** edit, or `cp` the file aside first. If it happens anyway, a never-committed file is often rebuildable by cross-checking three sources: the still-on-disk **compiled view** where the framework keeps one (Laravel: `storage/framework/views/<hash>.php`, the exact last-rendered state), the session **transcript**'s `Read`/`Edit` results, and **HEAD** for the unchanged scaffolding: then re-verified green before trusting it.
- **The stash stack is one instance of a class this corpus had never named: a SHARED READ-MODIFY-WRITE SURFACE.** Something two actors can read, change and write back, with no locking and no arbitration, so the last writer wins and the loser is never told.

  | Surface | Shared between | Safe operation | Destructive operation |
  | --- | --- | --- | --- |
  | git stash stack | every worktree of one repo | a WIP commit, or `cp` the file aside | `stash push` / bare `pop` |
  | project memory | every session on a machine | append a new file, one fact per file | rewriting a shared file whole |
  | GitHub issue / PR body | everyone, over the network | post a comment | editing the body |

  **Append cannot clobber; rewrite can.** That is mechanical and does not depend on anyone remembering to be careful, which matters, because the losses observed here happened *between sessions that were actively coordinating with each other at the time*.

  **Worktree slots do NOT partition project memory.** `~/.claude/projects/<slug>/memory/` resolves to one directory per repository (keyed to the primary checkout's slug, not to the worktree) so slot A, slot B and any other concurrent session on a machine read and write the same files, and no per-slot directory exists. Isolation that stops at the repo boundary still reads as isolation, which is what makes it dangerous. Observed in `wordpress-importer` on 2026-09-05: two sessions rewrote the same memory file wholesale within a minute; one write landed and the other was lost, recoverable only because a copy had been taken outside the directory. **Never write a memory file whole.** Anchor on exact text you expect to be there, assert it appears exactly once, and replace only that:

  ```python
  old = "<exact text I expect to be there>"
  assert s.count(old) == 1, "anchor moved - someone else edited this"
  s = s.replace(old, new)
  ```

  The assert turns a silent clobber into an aborted edit, and catches the opposite fault too: an anchor appearing twice, where `replace` would hit both. **The protection is in the FORM of the edit, not the care of the editor:** a session that used this pattern seven times in one evening still made one truncating rewrite among them, which would have destroyed a concurrent append just as a whole-file write does.

  **An assert-then-replace guard is necessary and NOT sufficient, and it will be proposed by anyone who has not watched it fail.** Confirming your anchor is still present before writing proves *this* edit lands where intended. It proves nothing about a concurrent edit **elsewhere in the same artifact**, and that is the common case rather than the edge, because two writers usually append to different sections.

  **A guard that assumes it is the only writer detects only the races that start after it does.** A base-hash check compares the remote against your copy immediately before writing, so it catches an edit landing *between* your read and your write. An edit that landed *before* your read is already in your copy and the hashes match honestly. Nothing in the output distinguishes "no race" from "a race I was too late to see", which is worse than no guard, because it licenses confidence.

  **Delete only what you wrote, and confirm it is still what you wrote.** Not a mechanical check, which is why it needs stating: an editor removing a block it authored must verify the block is still its own content, not merely that something sits where it left it. The observed clobber replaced a section whose owner had altered it between the read and the write. The same hazard on a shared *remote* artifact (an issue or pull-request body) belongs to [`filing-defects-across-repos`](../rule-filing-defects-across-repos/SKILL.md); this bullet is the local half.

- **The stash stack belongs to the *repository*, not the worktree, so `git stash pop` in your slot pops whatever another session pushed.** Every linked worktree shares one stack, and two mistakes chain into losing someone else's work. First, `git stash push -- <paths>` **fails outright** if any named path is untracked (`error: pathspec '…' did not match any file(s) known to git`) and stashes **nothing**: easy to miss inside a `&&` chain, or when you only read the last line. Then the paired `pop` applies the entry that was already on top: another session's, or an ancient GitHub Desktop one. **Never use the stash to set work aside or to stage a temporary revert**: prefer a WIP commit, or `cp` the file aside (see the negative-control note in [`adversarial-review`](../rule-adversarial-review/SKILL.md)). If you must stash: `git stash push -u -m "<unique-tag>"`, capture the SHA from `git stash list --format='%H %gs'`, and `git stash apply <sha>`: never bare `pop`.
  - **Recovery, if it happens anyway.** `pop` drops the reflog entry but the commit is merely unreachable, so it can be put back:

    ```bash
    git fsck --unreachable --no-progress | awk '/commit/ {print $3}' |
      while read c; do git log -1 --format="$c %s" "$c"; done | grep -E ' (On|WIP on) '
    git stash store -m "<the original message>" <sha>       # back on top of the stack
    ```

    Then revert the popped files out of your tree: `git restore --staged` + `rm` for its untracked adds, `git checkout --` for tracked ones (safe *here*, per the bullet above, because they legitimately should match HEAD). Confirm with `git stash list` that the stack is the depth it was.

## Rebuilding a slot, and removing an ephemeral worktree

A slot is normally never removed: that is the point. Rebuild one only when its bootstrap is beyond refreshing (a corrupted `vendor/`, a wedged submodule). The removal procedure below is the same one an ephemeral worktree needs.

**`git worktree remove --force` is the normal path and it works, and the old folklore about submodules blocking it is wrong.** Measured directly in `wordpress-importer` (git 2.39.5 Apple Git-154, macOS) on a slot with the exports submodule fully initialized, and in `uams-statamic` (git 2.32.0.windows.2, Windows) with `content` initialized: exit 0, tree deleted, deregistered. The long-standing claim that an initialized submodule makes removal refuse **did not reproduce**, and has been removed rather than softened.

**`git worktree move` is init-gated, not declaration-gated.** With no submodule initialized it succeeds; with any one left it fails `fatal: working trees containing submodules cannot be moved or removed`, exit 128, and the shared error message naming both operations is precisely why the folklore stuck. Deinit every initialized submodule before a move: a repository declaring **four** has to `git submodule deinit -f --all`, because deinit-ing only one leaves three and the move still refuses: exactly the trap hit in `uams-statamic`, where deinit-ing only `content` was mistaken for proof that deinit does not help. A repository declaring one needs only that one. So a slot *can* be relocated: deinit, `git worktree move`, re-init if needed. The alternative is an OS-level rename plus `git worktree repair <new-path>` to fix the linkage.

Reach for **`rm -rf <path>` then `git worktree prune`** only when something outside git blocks removal: an OS handle, or untracked scratch you have already triaged. The ways that happens, most of them observed on Windows:

1. **Get every shell's cwd *out* of the tree first.** On Windows a shell whose working directory sits inside it **holds a lock**: `rm -rf` then fails `Device or resource busy` and `git worktree remove` cannot delete it. (Corollary: after a removal, invoke a local CI runner by **absolute path**: a cwd stranded in a deleted tree makes the relative path resolve to the gone copy, and the runner never starts.)
2. **Kill any watcher started in it**: `composer dev` / `npm run dev` (Vite) / `php artisan serve` holds handles the same way.
3. **An OS-level rename can itself be denied** (`Permission denied` from `mv`, `Access to the path … is denied` from `Rename-Item`) while a handle is open on the directory. Observed in `uams-statamic` on Windows, and worth knowing in this order: the `readlink /proc/*/cwd` sweep from [`long-running-commands`](../rule-long-running-commands/SKILL.md) came back **empty**, so an empty sweep does not mean unlocked: it only sees MSYS processes, and `Win32_Process` exposes no working directory at all. **Localize the lock before reacting to it.** Writing a file inside the directory and renaming one of its *subdirectories* both succeeding, while renaming the top level fails, means a single handle on the folder itself: most often a shell that recently `cd`'d there, an editor window rooted on it, or Herd watching it as a parked site. **A top-level lock does not block the delete**, which is what makes this a non-event: `rm -rf` removes the contents regardless and fails only on the final `rmdir`, leaving an empty directory that `git worktree prune` still deregisters. These handles are also often transient: the same directory that refused two rename attempts deleted cleanly, top level included, a few minutes later. So do not go hunting the holder, and do not reboot: delete the contents, prune, and let the tool recreate the worktree under the name you wanted.
4. **Untracked scratch also blocks removal.** *Look before you delete*: a worktree accretes stray files (import XML, generated fixtures); copy anything worth keeping to the scratchpad first.
5. **Delete any junction as its own step, before `rm -rf`**: remove the *link*, never the target: `[System.IO.Directory]::Delete($path, $false)` in PowerShell, or `cmd //c rmdir <path>`. Then confirm the target still exists. A recursive delete that follows a reparse point takes the primary's `node_modules` (or another tree's submodule) with it, and nothing tells you until the next command fails.

The branch is preserved by every path above. **Recovery from a half-delete:** if `rm -rf` removed the `.git` file but stalled on a locked one, `git worktree remove --force` then errors (`'…/.git' does not exist`) and the branch stays pinned to the dead tree (`git worktree list` shows it `prunable`). `git worktree prune --expire=now` drops the stale admin entry and **frees the branch**: enough to reuse it even while leftover files linger; clear those once the lock releases, usually the instant a stranded shell cwd moves off it (step 1).

**Machine-global tool caches outlive a removed tree and record its paths.** Where PHPStan's `tmpDir` is the default, its container/stub cache in the system temp directory (`%TEMP%\phpstan` on Windows, PHP's `sys_get_temp_dir()` elsewhere) pins the absolute phar path of whichever checkout built it, so after a tree is deleted PHPStan can fail from *any* checkout with `"phar://…/<deleted-tree>/vendor/phpstan/phpstan.phar/…" is not a file` (observed in `uams-statamic`). `clear-result-cache` does **not** fix this; it clears a different file. Point the run at a private cache rather than wiping a shared directory a concurrent session may be mid-analysis on: write a throwaway config that `includes:` the repo's `phpstan.neon.dist` and sets `parameters: tmpDir:`, then `phpstan analyse -c <that config>`. There is no `--tmp-dir` flag. A repository whose `phpstan.neon.dist` pins `tmpDir` to a path relative to the config file avoids this entirely: each tree gets its own cache inside itself and no shared system-temp directory is involved; what remains there is ordinary reuse staleness in a long-lived tree, which `clear-result-cache` addresses and deleting the cache directory in that tree is the blunt form of. Stable slots make the machine-global case rare either way: it is triggered by paths *dying*, which is exactly what they stop doing.

## Where it lands / the DRY line

This file is the standing statement on **worktree lifecycle**: the slot model, claiming and releasing, the refresh gate, provisioning, and removal. `AGENTS.md` carries a one-line pointer here; the `EnterWorktree` / `ExitWorktree` *mechanics* live in those tools' own descriptions: don't restate them. It composes with the branch → PR → merge flow in `AGENTS.md` (a slot changes *where* you branch, not *how* you ship), with [`sync-pr-branch`](../rule-sync-pr-branch/SKILL.md) (which owns bringing a *branch* current: this rule owns refreshing the *tree* that branch sits in), and with [`long-running-commands`](../rule-long-running-commands/SKILL.md) (which owns bounding a run and attributing a process to the tree that started it, and not trampling one somebody else owns once isolated).

