<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/measurement-parity.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — mirror the *job*, not the command, before comparing to a baseline

Before you compare any measurement against a prior number (a suite time, a boot time, a bench figure quoted in an issue), **read the harness that produced the baseline and reproduce its whole job**, not just its headline command. A number measured under different setup is not a comparison, and nothing in the output will tell you so: both runs finish, both print a plausible duration, and the delta you report is an artifact of your own setup.

## How to apply

1. **Open the baseline's harness and read it.** If a number came from a script, that script is the specification: a scratch `*.sh`, the repository's local CI runner, a CI job in its workflow file. Reconstructing the command from memory or from an issue comment is how setup steps go missing; they live in the harness, not in the sentence describing it.

2. **Enumerate the parity checklist explicitly, and log it in the run.** At minimum:
   - The same worktree, the same commit base, and the same runner flags.
   - The same setup steps. A baseline's harness often includes framework setup before the timed command (a route or config cache, a seeded snapshot, a warmed cache), and **omitting one silently changes the regime**. These are the steps most often missed, because they live in the harness rather than in the sentence describing the number.
   - The same machine quiet-state, checked per [`long-running-commands`](long-running-commands.md) (`Get-CimInstance Win32_Process` on Windows, since MSYS `ps` undercounts, and `pgrep`/`lsof` on macOS).

   Print each step's exit code into the run log. A parity step that silently failed is indistinguishable from one you never wrote.

   **A suite's own `beforeEach` is part of the harness.** Before profiling any code path *through a test*, read what the suite switches off: a test that disables a cache to keep itself deterministic has changed the regime you are about to measure, and nothing in the run says so. **Name the regime the suite runs in alongside the number.**

3. **State the parity basis wherever you publish the number.** "Same worktree, same commit base, byte-identical command, setup steps run" is part of the result. A speedup quoted without it cannot be audited or reproduced later.

   **When the basis includes a dependency, name the revision: a version string does not identify code.** "Same commit base" covers what git tracks, and `vendor/` is git-ignored, so a measurement can be byte-identical in everything the basis names and still have run against different code. What gets published as clearance, `installed version == composer.lock version -> no vendor drift`, is not a check; `shasum -a 256 <the file you call into>` is. The parity step that corrects it is **`composer reinstall <package>`**, not `composer install`, which treats the version as satisfied and leaves the drifted files in place.

   **This generalizes past dependencies to every arm of a comparison: hold the code constant, not its label.** Any pipeline that can produce two contents under one tag (a vendor install, a build step, a plugin cache, a container image) makes "same version" an assumption under test rather than a held variable. It fails in the reassuring direction: a matching label tells the reader to stop checking, which is the one thing a mismatched label would not have done.

4. **Confirm the workload is genuinely identical.** Compare the *result line*, not just the duration: the **test count** is the invariant that must match exactly, and the **assertion count** corroborates it. Treat a *large* assertion delta as a different workload, but not a tiny one: a handful of guards assert conditionally on per-worker bootstrap state, so the total can legitimately drift by a few between otherwise identical bands. Chase a delta of tens; note a delta of ones. Also check the baseline commit has not drifted: `git log <base>..origin/main -- tests/` should be empty, or the comparison is measuring somebody else's change too.

5. **Know which flags change the workload rather than observe it.** A profiler is not a stopwatch. A flag that makes the runner behave differently in order to measure it has changed the thing measured, so never quote its wall time as a band time.

6. **Distrust a harness's own verdict field; re-derive from the raw output.** Strip ANSI before parsing, and when a verdict disagrees with the raw log, believe the log.

7. **Prefer more parity over more samples.** Repeat runs bound *variance*; they do nothing about *systematic* error, which is the failure this rule addresses. A verified parity checklist adds much more than another sample. Spend the time there first.

## The DRY line

This file is the standing statement on **making a measurement comparable**, which includes holding the code constant rather than its label. It composes with [`filing-defects-across-repos`](filing-defects-across-repos.md) (whose step 7 applies the revision-citing half to a report crossing a repository boundary, and points here for the evidence) and with [`long-running-commands`](long-running-commands.md) (which owns *bounding* a run and not trampling a concurrent one; this rule owns whether the number it produces means anything). Per-suite timing facts, such as what the band costs or what the serial tail costs, belong in the issues that measured them, not here.

