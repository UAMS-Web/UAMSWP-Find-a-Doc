<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/supported-platforms.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — macOS and Windows are both supported; name the platform, write paths repo-relative

**Both macOS and Windows are live development platforms for this repository, and both stay in use.** Nothing in the rules, skills, or docs corpus may assume either is the only one, so a platform-conditional branch is never dead documentation to be deleted: each one is live guidance that has to be correct on the platform it names.

## The two obligations

1. **Name the platform a branch was verified on.** An unqualified claim reads as portable. Write "measured on git 2.39.5 (Apple Git-154), macOS" or "verified under Git Bash on Windows", and where a claim has been checked on only one platform, say that the other is **not yet measured** rather than leaving the gap invisible. A recorded gap invites a measurement; an unqualified claim invites a wrong action.

2. **Write paths repo-relative, never as one machine's absolute path.** `scripts/<script>.mjs`, not `/Users/<user>/<checkout>/scripts/<script>.mjs`. An absolute path from the original checkout resolves on exactly one machine: it is wrong on a second Mac just as surely as it is on Windows, so **a second machine's absolute path is not the fix**. Where a path must be shown relative to a tree, anchor it on the tree rather than the disk: `../<repo>-a`, not a drive letter.

## What this does not cover

**Illustrative paths inside code and prose are not machine paths, and must not be "fixed" by a later sweep.** A docblock may carry a home-directory path with a space in it as a worked **example** of the input a class exists to handle. That names no real machine, and it can be the class's whole subject. Leave it: a sweep will match it, and that match is expected.

The test is whether the path is *load-bearing as a location*. A path a reader is expected to `cd` into must be repo-relative; a path quoted to show the shape of a string is prose.

## The DRY line

This file is the standing statement on **platform parity and path portability**. The specific per-platform mechanics live where they are used, and are not restated here: bounding a command and the Git Bash perl-alarm no-op belong to [`long-running-commands`](long-running-commands.md); the CRLF/Pint trap, the slot measurements, and the worktree/submodule behaviors belong to [`worktrees`](worktrees.md); the PHP/Composer discovery differences belong to the repository's local CI runner.

This rule only requires that each of them says which platform it was measured on.
