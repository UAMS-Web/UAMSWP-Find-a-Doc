<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/long-running-commands.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore ALRM cmdline esac plainband ppid setpgrp SIGALRM tasklist undercounts waitpid -->
# Rule — bound every long-running command, and resolve what you kill

A command that may run for minutes gets an **explicit bound inside the command**, and a process you are about to kill gets **identified first**. The Bash tool's `timeout` and `run_in_background` **detach work, they do not stop it**: you get `exit 143` while the process runs on, contending with your next run.

## How to apply

1. **Bound it in the command, with the cascade:**

   ```bash
   bound() {
       if command -v timeout  >/dev/null 2>&1; then timeout  "$@"; return $?; fi
       if command -v gtimeout >/dev/null 2>&1; then gtimeout "$@"; return $?; fi
       perl -e '
           my $limit = shift;
           my $pid = fork // die "fork: $!\n";
           if ($pid == 0) { setpgrp(0, 0); exec @ARGV or die "exec: $!\n"; }
           $SIG{ALRM} = sub { kill("-TERM", $pid); sleep 2; kill("-KILL", $pid); exit 124 };
           alarm $limit;
           waitpid($pid, 0);
           alarm 0;
           my $st = $?;
           exit($st & 127 ? 128 + ($st & 127) : $st >> 8);
       ' "$@"
   }

   bound 600 php -d memory_limit=4G vendor/bin/pest tests/Feature/SomeTest.php
   ```

   - **Git Bash and Claude Code cloud sessions have GNU `timeout`; use it. macOS has neither `timeout` nor `gtimeout`**, so the fallback fires there; a bare `timeout` is `command not found`, which in a pipeline can read as `0`.
   - **Never use the one-liner `perl -e 'alarm shift; exec @ARGV'`**: it signals only its direct child (paratest workers survive), and under Git Bash it is a silent no-op. The fallback kills the process group.
   - **Expiry is `124` on either branch**; otherwise the command's status returns. **Expect `t + 2` seconds** (TERM, 2s grace, KILL); do not tighten for it.
   - **Trust the exit code, not the message**: PHP reports an external `SIGALRM` as `Maximum execution time of 0 seconds exceeded` (exit `255`, or `1` under Pest).

2. **Bound above the high end of this suite's own observed spread**, never one measurement or another suite's: a fired bound reads as a failure. Treat any recorded figure as a lower bound. **A fired bound destroys an artifact emitted only at the end** (coverage, a mutation summary), so size for the worst case; to let a nearly done run finish, kill **only the `timeout` wrapper** by PID.

3. **Resolve a process before you kill it.** `pgrep -f`/`pkill -f` match the full command line, including the searching command. List candidates with their cwd and kill only yours, by PID. macOS has no `/proc`; use `lsof`:

   ```bash
   for pid in $(pgrep -f 'vendor/bin/pes''t'); do
     printf '%s  %s\n' "$pid" "$(lsof -a -p "$pid" -d cwd -Fn | grep '^n' | cut -c2-)"
   done
   ```

   `pgrep` excludes itself (a `ps | grep` form must exclude its own pid), the split literal keeps a `bash -c` wrapper from matching, and each read is by pid after the set is closed: do not fold it into one pipeline. **On Windows use `Win32_Process`** (MSYS `ps` undercounts); a `-like` filter matches your own invocation, so list, then kill confirmed PIDs:

   ```bash
   powershell.exe -NoProfile -Command "Get-CimInstance Win32_Process -Filter \"Name='php.exe'\" |
     Select-Object ProcessId,@{n='Cmd';e={\$_.CommandLine}} | Format-List"
   powershell.exe -NoProfile -Command "Stop-Process -Id 1234,5678 -Force"
   ```

   Filter on what cannot appear in the querying command (a worktree path), never the script's filename.

4. **Never kill a process whose cwd is not yours, and cwd alone does not tell you that.** The [`worktrees`](worktrees.md) slots look alike; only the resolved path separates them.

   - **Allow-list your own tree, anchored on the separator; never denylist others**, which kills unrecognized trees by omission.
   - **`MY_TREE` comes from what the invocation carries** (an argument, a launch-time variable, a `cd` in the same command), **never `$(pwd)`**: the session cwd reverts between calls, and the guard then fails open, silently.
   - **cwd cannot separate sessions sharing a checkout** (it fails open). **Attribute a long-lived process by a session label in its `argv`** (the environment is invisible to `ps`); cwd is the coarse filter, then the label decides:

   ```
   cwd outside your tree                      not yours          leave it
   cwd inside your tree, marker is YOURS      yours              safe to kill
   cwd inside your tree, marker is a PEER's   theirs             leave it, and tell that session
   cwd inside your tree, NO marker            NOT ATTRIBUTABLE   leave it
   ```

   An unmarked process is not "probably mine". Read the label as one field, by PID:

   ```bash
   for pid in $(pgrep -f '<your-to''ol>'); do
     printf '%s  %s\n' "$pid" "$(ps -ww -p "$pid" -o command= | sed -n 's/.*--session \([^ ]*\).*/\1/p')"
   done
   ```

   - **Resolve `MY_TREE` with `pwd -P`** (`TREE=$(cd "$MY_TREE" && pwd -P)`): `lsof` reports the physical path; the logical one from `pwd` or `git rev-parse --show-toplevel` matches nothing.
   - **That loop is a kill-list, not a census**: wrapper shells carrying the label match too, so count resolved, attributed PIDs (read `argv0` and `ps -o ppid=`, so a wrapper and its child count once), never matched lines. Write the sweep for the POSIX branch: only a Windows name filter excludes wrappers, and on macOS and Linux every wrapper matches.
   - **Arm every long-lived process with your label in its `argv`**, even inert; otherwise no other session can clear it, and on Windows the label is the only discriminator:

   ```bash
   bash -c 'echo "session=<your-label>" >/dev/null; sleep 600; <the real command>'
   ```

5. **Kill the parent loop first, or the run restarts itself**, then surviving children, then **re-list and confirm zero**. **`TaskStop` stops the task, not the tree.** On Windows kill each `cmd.exe` → `php.exe` pair, and never include `bash.exe`, your own shell, in a pattern kill set.

   - **If a second sweep finds it back, stop and ask the other session**: a peer re-arming looks like your own loop.
   - **A vanished PID is not a stopped run**: re-list by cwd, argv or label.
   - **Tool-name patterns miss an orphaned bare `sleep`**; do not widen them. A `bash -c 'sleep N; <command>'` whose parent is gone runs nothing. Resolve the parent in the same call; clearing an orphan clears an iteration, not a loop.

6. **Confirm the run ended before you measure or trust anything**, after any ending, including a failure (`exit 1`, `exit 2`, a harness's "command failed"), which detaches the child just as a timeout does. Reap a run's waiter by PID when the run ends, not at session end, and re-check that zero remain. Put an aborting check in any script whose output you will trust. Git Bash (no `pgrep` or `lsof`) and Linux:

   ```bash
   MY_TREE=${1:?pass the tree this run belongs to, absolute and with no trailing slash}
   strays=0
   for p in /proc/*/cwd; do
     pid=$(basename "$(dirname "$p")")
     target=$(readlink "$p" 2>/dev/null) || continue
     case "$target" in "$MY_TREE"|"$MY_TREE"/*) ;; *) continue ;; esac
     cmd=$(tr '\0' ' ' < "/proc/$pid/cmdline" 2>/dev/null)
     case "$cmd" in *"bin/pest"*|*artisan*) echo "stray pid=$pid :: ${cmd:0:80}"; strays=$((strays + 1)) ;; esac
   done
   [ "$strays" -eq 0 ] || { echo "ABORTED: $strays competing run(s) in this worktree"; exit 3; }
   ```

   macOS:

   ```bash
   MY_TREE=${1:?pass the tree this run belongs to, absolute and with no trailing slash}
   strays=0
   for pid in $(pgrep -f 'bin/pes''t|artisan' | grep -vx "$$"); do
     cwd=$(lsof -a -p "$pid" -d cwd -Fn 2>/dev/null | sed -n 's/^n//p')
     case "$cwd" in "$MY_TREE"|"$MY_TREE"/*) echo "stray pid=$pid :: $cwd"; strays=$((strays + 1)) ;; esac
   done
   [ "$strays" -eq 0 ] || { echo "ABORTED: $strays competing run(s) in this worktree"; exit 3; }
   ```

   - **`MY_TREE` is the tree the work runs in**, not the session's. **Keep the anchoring** (or compare resolved paths for equality): sibling slots share the primary's path as a prefix.
   - **One session can hold processes in several directories**, so no path matching attributes it: match long-lived processes on the label.
   - **Show the control visible first**, in the same enumeration: `bash -c 'sleep 12; true' 'vendor/bin/pest'` (with one statement bash may exec-replace and drop the marker).
   - **Name the platform each branch was verified on** ([`supported-platforms`](supported-platforms.md)).

## Validate the sweep before you trust its silence

> **Show the sweep seeing a process known to exist, before reading its empty result as an absence.**

The general form is [`an-empty-result-is-not-evidence`](an-empty-result-is-not-evidence.md). Sweeps that return clean while blind:

- **macOS `ps` without `-A`, `-a` or `-e`** lists only the terminal's processes; `-ww` is width.
- **Windows rows with a null `CommandLine`** are unread: count them.
- **A process-name filter misses ad-hoc waiters** (`sleep.exe`, `bash.exe`).
- **`grep -v grep` drops every watcher that pipes through `grep`**: never use it.
- **BSD `pgrep` lacks GNU flags**: `pgrep -fc … 2>/dev/null || echo 0` prints `0` on a usage error; count with `pgrep -f '<pattern>' | grep -c .`. `cmd || echo "(none)"` cannot tell "nothing" from "did not run": read the status ([`reading-exit-status`](reading-exit-status.md)).
- **A predicate on the wrong surface**: a shell running a script shows only its path.

**Control the whole pipeline**, and show the inputs were non-empty and intended, with a negative control showing a failed or empty fetch. **A control shows detection, not coverage**: count what went unread, and check the predicate against the surface the behavior lives on.

## The same blindness on an outward-facing write

- **Never sent**: read the status before any pipe (`cmd > out 2>&1; rc=$?`).
- **Sent, wrong body**: an older file at the path went out, exit `0`. Only a read-back catches it, so **write the body and send it in one command**, or with a tool that cannot be refused.

A server id you read in the success line proves the post landed. **A read-back needs a positive control**, and tells a symmetric rendering change (`**` shown as `*`) from a real loss.

## The DRY line

This file owns **bounding, attributing and killing local processes**. Slots are [`worktrees`](worktrees.md)'s; tool mechanics are in the tools' descriptions; reading a status is [`reading-exit-status`](reading-exit-status.md)'s; choosing what to call is [`github-api-budget`](github-api-budget.md)'s.

Evidence, measurements and history: [`long-running-commands`](../skills/evidence-behind-rules/long-running-commands.md).
