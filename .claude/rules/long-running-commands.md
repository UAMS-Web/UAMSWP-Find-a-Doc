<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/long-running-commands.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore ALRM cmdline esac plainband ppid setpgrp SIGALRM tasklist undercounts waitpid -->
# Rule — bound every long-running command, and resolve what you kill

A command that may run for minutes gets an **explicit bound inside the command itself**, and any process you are about to kill gets **identified first**. Neither the Bash tool's `timeout` parameter nor `run_in_background` stops work: **they detach it.** When the tool timeout fires you get `exit 143` and a clean-looking prompt, while the process you started keeps burning cores in the background.

**Why this is a standing order.** A detached multi-minute run does not sit still. It contends with every run you start next, so the next thing you time comes back slower and you draw the wrong conclusion from it; and it holds the files, caches and database the next run wants. Nothing warns you: the tool result says the command ended, and it is telling the truth about the *wrapper*, not about the work.

## How to apply

1. **Bound it in the command, with the mechanism that actually works on your platform.** Prefer GNU `timeout` whenever it is present, and fall back to the forking perl idiom only where it is not. Write the cascade rather than the fallback, so a machine that later gains coreutils starts using it without anyone revisiting this file:

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

   - **Windows / Git Bash has GNU `timeout`** at `/usr/bin/timeout` (coreutils 8.32), verified on Windows to both bound the process *and* reap its children. The cascade reaches it before the fallback is ever considered.
   - **Claude Code cloud sessions (Ubuntu 24.04) have GNU `timeout`** as well; use it rather than the fallback.
   - **macOS ships neither `timeout` nor `gtimeout`.** Verified on **macOS 26.4 (arm64)** in `uams-statamic`, and on the `uamswp-migration-api` development machine: both absent, because `gtimeout` arrives only with Homebrew coreutils, which is not a given. So the fallback is the branch that actually fires there. A bare `timeout` fails with `command not found`, and inside a pipeline that failure can read as a `0` exit, which is why the detection above is a real check and not a comment.
   - **The fallback forks and kills the process GROUP, and that is the load-bearing part.** The older one-liner (`perl -e 'alarm shift; exec @ARGV'`) does bound its direct child (POSIX preserves a pending `alarm` across `exec`, so the bound travels with the work). But `alarm` signals *only* that one process, and a paratest run's workers are not it. Measured on macOS 26.4 in `uams-statamic`, a 25s bound on `pest --parallel tests/Unit`: the parent died on schedule while **one `pest/bin/worker.php` survived every settle point out to +15s**: reproduced twice from a clean slot. Under the forking form, zero survivors. Measured in `uamswp-migration-api` on `sh -c 'sleep 30 & sleep 30'`: the forking form exits `124` with `0` children alive afterwards, the one-liner exits `142` with `2`.
   - **Expiry is `124`, whichever branch runs**, which is what makes a fired bound readable; a command that finishes inside the bound returns its own status, propagated rather than swallowed (verified in `uamswp-migration-api`: a bounded `sleep 10` and a bounded `php -r sleep(10)` both `124`; `sh -c 'exit 7'` returns `7`; on macOS 26.4 in `uams-statamic`, `0`, `7` and `3` all arrive intact).
   - **Expect `t + 2` seconds, not `t`.** The handler sends `TERM`, waits 2s for a graceful exit, then sends `KILL`, so a 5s bound returns at 7s and a 20s bound at 22s. That is the grace period, not drift; do not tighten a bound to compensate for it.
   - **PHP masks the alarm as its own fatal, so trust the exit code, not the message.** Under the old one-liner `/bin/sleep` exits **142** (`Alarm clock`, 128+SIGALRM), but PHP installs its own `SIGALRM` handler for `max_execution_time`, so an external one becomes `Fatal error: Maximum execution time of 0 seconds exceeded` and exit `255`; under Pest that surfaces as `Pest\Exceptions\FatalException` and **exit 1**, which is indistinguishable from a test failure and sends you debugging the suite. The forking form exits **124** like GNU `timeout`.

   > **Warning: the one-liner `perl -e 'alarm …'` form is a silent NO-OP under Git Bash.** Its Perl has no real `exec`, so the pending alarm dies with the emulated one and the command runs unbounded. Verified on Windows: a **10s alarm on a 30s sleep ran the full 31s**, and a soak band "bounded" at 5400s ran **6670s**. The failure is invisible: you get no error, just an unbounded run. Never write a bare `perl -e 'alarm …'` bound on Windows. The forking form is a different matter: it has been run under Git Bash: 30 controlled trials with exact-command-line filtering and 0 survivors, recorded as `UAMS-Web/uamswp-migration-api#215`, which was filed as a Windows defect in that fallback and refuted by its own re-measurement (the two survivors it reported were unrelated recurring `sleep.exe` processes caught by an unfiltered `tasklist` count). **Use `timeout` on Windows anyway, and the ground is availability rather than failure**: it is already there, so the fallback is simply unnecessary.

2. **Size the bound above the observed *spread*, not to a single measurement and not to your patience.** A bound that fires reads exactly like a failure and invites you to go debug a passing command. Measure the real runtime, then bound generously above it, and above the **high** end, because a bound derived from the fastest run fires on the slowest. **Do not calibrate off one suite's spread**: the tightest and the widest can differ by a large factor, so a margin that is generous for one suite is not generous for another. Treat any single figure written down earlier as a lower bound rather than an estimate.

   **A bound that fires can destroy the artifact you were after.** When a run's whole purpose is something emitted only at the end (a coverage report, a mutation summary, a regenerated catalog file) a kill partway produces nothing, and the hour is spent for nothing. Size the bound for the worst case, not the expected one. If a bound is about to fire on a nearly-complete run, killing **only the `timeout` wrapper** (by PID) orphans the child and lets it finish: the child keeps its inherited stdout redirect, so its output still lands.

3. **Resolve a process before you kill it.** `pgrep -f` / `pkill -f` match on the full command line, which is wider than you think, including **the command doing the searching**: a `pkill -9 -f "vendor/bin/pest"` once killed a session's *own* monitor, because the monitor's command line contained the pattern it was searching for. List candidates with their working directory and kill only what is yours. **macOS has no `/proc`**, so `readlink /proc/<pid>/cwd` is not available; `lsof` is the instrument:

   ```bash
   for pid in $(pgrep -f 'vendor/bin/pes''t'); do
     printf '%s  %s\n' "$pid" "$(lsof -a -p "$pid" -d cwd -Fn | grep '^n' | cut -c2-)"
   done
   ```

   **Two properties make this safe, and the split string `'vendor/bin/pes''t'` is only one of them: read them before collapsing this into a one-liner.** First, `pgrep` **excludes its own process** from its results. The shell joins `'pes''t'` to `pest` *before* exec, so the joined literal is in `pgrep`'s own argv, and it is the self-exclusion rather than the splitting that keeps `pgrep` from matching itself. Verified in `uamswp-migration-api`: a `ps -Aww -o command= | grep 'PROBE''TOKEN'` matches its own `grep`, while `pgrep -f 'PROBE''TOKEN'` matches nothing, so in a `ps | grep` form you must exclude your own pid explicitly; the split string will not do it for you. What the split string does keep clean is the **wrapper shell**: a `bash -c '<command>'` carries the whole command as one argument, so an unsplit literal there matches `pgrep -f` exactly as the real process does. Second, the pid set is **closed before any extractor runs**: `pgrep` finishes, then each read is addressed **by pid**, which cannot match anything by text. Fold this into one pipeline and every literal in it: in an `awk` body, a `sed` expression, a filter script's own filename: becomes a self-match candidate, not just the one in the selector.

   **The same trap exists in PowerShell, which is where process work on Windows ends up** (MSYS `ps` undercounts `php.exe` badly: it saw 9 where PowerShell saw 43, so `Get-CimInstance Win32_Process` is the real instrument there). `Where-Object { $_.CommandLine -like '*foo.sh*' }` matches **your own invocation** if the string `foo.sh` appears anywhere in it, including inside an unrelated `cat`, `Write-Output`, or the filter itself. Observed: a `Stop-Process` filtered on `*plainband.sh*` killed the very shell issuing it, because the command also wrote a file by that name. **Resolve first, then kill by explicit PID:**

   ```bash
   # 1. LIST and eyeball: never kill straight out of a -like filter
   powershell.exe -NoProfile -Command "Get-CimInstance Win32_Process -Filter \"Name='php.exe'\" |
     Select-Object ProcessId,@{n='Cmd';e={\$_.CommandLine}} | Format-List"
   # 2. Kill the PIDs you confirmed, one by one
   powershell.exe -NoProfile -Command "Stop-Process -Id 1234,5678 -Force"
   ```

   Filter on something that identifies **your** run and cannot appear in the querying command: a worktree path is a good discriminator; the script's own filename is the worst one.

4. **Never kill a process whose cwd is not yours, and cwd alone does not tell you that.** The resolved working directory is what distinguishes your run from a concurrent session's, and under the [`worktrees`](worktrees.md) rule's slot model that distinction is permanent rather than occasional: several standing trees: the primary and the slots: each of which can hold a live `vendor/bin/pest`, `vendor/bin/phpstan`, or local CI run. **The slots look alike**, so the resolved path is the only thing separating them; a name is not. Killing one silently reds somebody else's validation run.

   **Allow-list your own cwd; never denylist theirs.** A denylist ("kill anything not under `-ci`") defaults every *unrecognized* path to "mine", so a slot you have not heard of, a sibling checkout added since, or a subagent's ephemeral worktree is killed by omission. Fail closed instead: resolve each candidate's cwd and kill only those **at your tree or beneath it**, anchored on the separator: never a bare prefix, for the reason step 6 works through. Anything you cannot positively attribute to yourself is somebody else's.

   **`MY_TREE` must come from something the invocation CARRIES, never from ambient cwd.** An explicit argument, an environment variable set when the process was launched, or a `cd` inside the same command: anything whose value cannot change between one tool call and the next. `$(pwd)` is the obvious default and is the one form that must not be used: [`worktrees`](worktrees.md) records that the Bash session cwd silently reverts to the primary between calls, sometimes announcing it and sometimes not. That does not merely make the guard wrong, it makes it **unrepeatable**: the same guard, unchanged, is correct on one invocation and wrong on the next, and the exit code is the only thing that differs. A `clear` from an ambient-cwd guard cannot be trusted even after you have watched it work. **That failure is silent and fails OPEN**, which is the opposite of everything else in this step: the abort never fires and the poisoned run proceeds looking clean. The over-claiming failures at least announce themselves by killing something.

   **And cwd cannot separate sessions that share a checkout at all.** It separates runs **across** checkouts: a second clone, a linked worktree, another agent in a different install. Several concurrent sessions in one tree produce byte-identical working directories: a process launched *without* an explicit `cd` inherits the session's directory, which is exactly what a long-lived process looks like, and several sessions run from the shared primary. No anchoring helps; this is not the sibling-prefix trap, it is the same path. **The failure direction is what makes this urgent.** For the case cwd does handle, the test fails closed and leaves a stranger's process alone. For a same-checkout peer it fails **open**: the peer's cwd *is* prefixed by yours, so the test returns true and authorizes the kill, and the killing session gets no signal, because every check it was told to run passed.

   **The precondition this step rests on, stated because it is otherwise invisible: cwd attributes a session only where each session has its own checkout.** Where every session works in its own pre-provisioned slot, cwd separates them by construction. None of these repositories guarantees that (the primary is shared by construction, and sessions do run from it), which is why the collision occurs. A reader on a one-session-per-tree repository will find cwd works perfectly and can reasonably carry that conclusion somewhere it does not hold. The observation is about these repositories; the *condition* is what travels.

   **So attribute a long-lived process by its per-session identifier rather than by its directory, and cwd is only the coarse filter.** A label passed on the process's own command line (`--session <label>` is the convention the worked example below keys on) stays there for the process's whole lifetime and is unique per session when each session picks its own, which is the property cwd has lost. **Put it in `argv`, not the environment.** Measured in `uamswp-migration-api`: a marker set with `exec -a` is readable straight out of `ps -o command=`, while a variable exported into a child's environment is invisible: it is absent from `argv`, and `ps eww -p <pid>` prints no environment on macOS even for your own process. A convention that hides the marker from the reader who needs it is no convention at all.

   ```bash
   for pid in $(pgrep -f '<your-to''ol>'); do
     printf '%s  %s\n' "$pid" "$(ps -ww -p "$pid" -o command= | sed -n 's/.*--session \([^ ]*\).*/\1/p')"
   done
   ```

   **Then the allow-list keys on the marker, with cwd narrowing first:**

   ```
   cwd outside your tree                      not yours          leave it
   cwd inside your tree, marker is YOURS      yours              safe to kill
   cwd inside your tree, marker is a PEER's   theirs             leave it, and tell that session
   cwd inside your tree, NO marker            NOT ATTRIBUTABLE   leave it
   ```

   **An unmarked process is not "probably mine".** Treating it as yours is the same defaulting mistake as a denylist, one level in: it restores fail-open behavior for exactly the case the marker was added to close. Fail closed: anything you cannot positively attribute to yourself belongs to somebody else: the rule is unchanged, only the field it reads.

   **The same-checkout case, worked.** Two sessions in one tree, so every candidate passes the cwd test and only the marker separates them:

   ```bash
   MINE='my-session-label'   # this session's own label

   # MY_TREE is CARRIED (above). Resolve it with -P, because `lsof` reports the PHYSICAL
   # path. Compare a logical path against it and every candidate fails the prefix test,
   # `continue` fires for all of them, and the sweep prints a confident nothing.
   # Measured: lsof said /private/tmp where pwd said /tmp, and the classifier below
   # silently classified NOTHING.
   TREE=$(cd "$MY_TREE" && pwd -P)

   for pid in $(pgrep -f 'vendor/bin/pes''t'); do
       cwd=$(lsof -a -p "$pid" -d cwd -Fn | grep '^n' | cut -c2-)
       cmd=$(ps -o command= -p "$pid")          # BY PID -- cannot self-match
       parent=$(ps -o ppid= -p "$pid" | tr -d ' ')

       [[ $cwd == "$TREE" || $cwd == "$TREE"/* ]] || continue   # coarse filter first, anchored

       if [[ $cmd == *"$MINE"* ]]; then
           echo "$pid  MINE            (parent $parent)"
           continue
       fi

       if [[ $cmd == *--session* ]]; then
           echo "$pid  A PEER'S        leave it, ask its session"
           continue
       fi

       echo "$pid  UNATTRIBUTABLE  leave it"
   done
   ```

   **Keep the `-P`.** `lsof` reports the physical path; `pwd` and `git rev-parse --show-toplevel` report the logical one, which differs wherever any component is a symlink: `/tmp` against `/private/tmp` on macOS is the case you will meet first. Compared against a logical one, every candidate fails the prefix test and the sweep reports a confident nothing: this file's own subject arriving in its own worked example: a filter that rejects everything looks exactly like a tree with nothing to find.

   **A match is not always the process you think it matched.** A wrapper shell carrying the whole command as one argument matches every pattern its child would: counting one run as two. Read `argv0` and the parent-process link (`ps -o ppid=`) rather than the match alone, so a wrapper and its child count as one run, not two. A process armed as `bash -lc '<command>'` carries `--session <label>` as a distinct token exactly as the real process does, and `pgrep -f` matches both. So that loop is a **kill-list and not a census**: reading the output as a count reports more sessions than exist, while killing by resolved id stays correct, which is why the defect hides, because the guidance around it is about killing, and killing is the safe reading.

   **The inflation is `+N`, not `+1`, and `N` is not a property of the machine: it is however many wrapper processes happen to carry the label at that moment.** Measured in `uams-statamic` on one label, Windows 11 / Git Bash, across a deliberately constructed overlap ninety seconds wide:

   ```
                          matched by the command line   real long-lived processes
   before a second armed              6                            1
   during the overlap                 8                            2
   after the first was stopped        4                            1
   ```

   Over-counts of `+5`, `+6` and `+3` in ninety seconds, on a label that never held more than two real processes. A macOS sample taken the same evening, across ten labelled processes each holding exactly one, ranged `+0` to `+2` with a mode of `+1`. **The `+0` cases are the ones worth noticing: a process armed without a wrapper carrying the label is unaffected, so the inflation looks intermittent rather than constant and a single clean count proves nothing about the next one.** On Windows `Get-CimInstance Win32_Process -Filter "Name='node.exe'"` selects on the executable, so wrapper shells are excluded before the command line is ever matched: the same overlap that produced `6 / 8 / 4` above produced `1 / 2 / 1` through that filter. `ps -eo command=` has no equivalent: it lists every process, so every wrapper whose argv carries the label matches. That is a Windows-only exemption and does not travel: write the sweep as though the POSIX branch is the one running, because on macOS and Linux it is.

   **And if you ARM a long-lived process, put your session label on its command line.** The paragraphs above are written entirely from the reader's side: they tell you to match a field that nothing tells anyone to write. The watchers this step was first written against carried `--session` only because they happened to take that flag, not because a rule asked for one, so every *ad-hoc* background process, which is most of them, is unattributable by construction. The obligation is cheap and has no parser on the other end. Anything that survives the tool call that started it carries the label somewhere in its own argv, even inert:

   ```bash
   # a bare echo is enough; nothing reads it, and `ps` shows it forever
   bash -c 'echo "session=<your-label>" >/dev/null; sleep 600; <the real command>'
   ```

   **Attribution is needed to CLEAR a process, not only to kill one, and the rule above only covers killing.** "Not yours by default" is the right refusal, and it has no matching path to exoneration: an unlabeled process can be left alone forever and never cleared. So an unlabeled long-lived process is a standing tax on every other session that sweeps, not a risk its owner carries alone. That asymmetry is the reason the arming side is an obligation rather than a courtesy. **On Windows this is not a degradation, it is total.** The platform note in step 6 records that `Win32_Process` exposes a command line but no working directory, so PowerShell cannot make the cwd attribution at all. There the session label is not the better discriminator: it is the only one, and an unlabeled process is unattributable by every method this rule describes. On macOS the cwd method at least narrows the field. State the severity rather than averaging it: same omission, different outcome per platform.

   **This does not contradict step 3, and the apparent conflict is worth resolving explicitly.** Step 3 warns that matching the command line makes a query match *itself*. That warning is about **substring-searching the whole command line for your tool's name**. Reading `--session` is a different operation: it extracts **one named field**, and the querying command does not carry that flag, so it cannot match itself. The two rules compose: *do not grep the command line for your tool's name*, and *do read the one field that names the session*, and the split-string trick from step 3 still applies to whatever pattern selects the candidates.

5. **Kill the parent loop first, or the run restarts itself.** A soak or validation harness runs its bands in a loop (`for i in $(seq 1 10)`), one child per iteration. Killing the **child** (the `php` process, or the `perl`/`timeout` wrapper around it) only ends the current iteration: the parent shell sees it exit and immediately launches the next, so the sweep reports success while the work continues. Kill the parent scripts first, then any surviving children, then **re-list and confirm zero**: one sweep is not proof, because a `pest --parallel` parent starts replacement workers while you are killing them.

   The harness **`TaskStop` stops the *task*, not the tree**: it kills the wrapper it tracks, so a detached loop keeps advancing and can start the next iteration. And on Windows each paratest worker is a **`cmd.exe` → `php.exe` pair**, so both must go. Never let a pattern-matched kill set include `bash.exe`: your own tool shell matches the same worktree path, so you kill the shell issuing the command.

   **Confirm-zero assumes you are the only actor, and a sweep cannot see whether that holds.** It converges only against *your own* parent loop, which stops restarting once you kill it. Against a peer that keeps re-arming in the same tree it never converges, and the process table cannot tell you which you are in: a process restarted by your own parent loop and one re-armed by a peer show the same argv, the same cwd and a new pid. Same evidence, opposite meanings, opposite remedies: the first needs the parent stopped first, the second needs a message to a person. **If a second sweep finds it back, stop sweeping and ask the other session.** Sweeping again is correct for the first case and an infinite loop for the second. If a process reappears under a new pid after you killed it, that is a question about *who else is acting*, not only about parent loops.

   **A vanished pid does not establish that the work stopped.** A waiter observed as gone by pid was running again seconds later under a new pid with identical argv and cwd. Re-list by cwd and argv (or by the marker above) never by the pid you were watching.

   **The candidate-selection examples in steps 3 and 4 cannot see a bare orphaned `sleep`, and neither can confirm-zero.** Every pattern in this rule greps for a tool name (`bin/pest`, `artisan`, `<your-tool>`) so `sleep 600` left behind when its wrapper died matches none of them and survives a sweep that reports zero. This is stated rather than fixed: widening the patterns to catch `sleep` would match every unrelated one on the machine. **That orphan is also the one path from "cannot attribute" to "can bound", which is worth more than it sounds.** A waiter of the shape `bash -c 'sleep N; <command>'` whose parent shell is gone will finish counting and run **nothing**: the follow-on died with the shell. So an orphan of that form is inert *by construction*, not by inspection and not by trusting its owner. Determined on two separate instances in `uams-statamic`. It does not tell you whose it is; it tells you it cannot act, which is usually the question a sweeping session actually has. Three limits, and the third is the one that reads like a clean audit:

   - **A stale orphan is indistinguishable from an armed waiter without resolving its parent.** One matched every `sleep` sweep run against a machine, on a live pid with a plausible command line, and proved to be 18.5 days old and from an unrelated repository.
   - **Resolve the parent in the same call as the child.** Split across two calls, the process can exit in between and return `already exited`, which reads as a determination and is a lost race, indistinguishable from "never there".
   - **Clearing an orphan clears an ITERATION, not a loop.**

6. **Confirm the run actually ended before you measure anything, and make the harness confirm it instead of you.** Re-check for strays after **any** reported ending, not only after a bound fires or a command returns `143`. A benchmark taken alongside a stranded run is not a benchmark, and neither is a *correctness* result, which is the worse case: a suite sharing a machine with a competing run comes back with failures indistinguishable from real regressions.

   **A reported FAILURE detaches the child exactly as thoroughly as a timeout does, and it is the case most often skipped.** `143` invites suspicion; `exit code 1` invites a fix and a re-run, so the natural response to a failure is to change something and start again, which is precisely when a surviving child is still holding the shared test database the next run wants. Treat `exit 1`, `exit 2` and a harness's own "command failed" line as saying nothing whatsoever about whether the work stopped.

   **Reap a run's waiter when that run ends, not at session end.** A stranded `until … done` waiter polls a log that will never change again; they accumulate silently. `kill` by explicit PID once the run is reported, and re-check that zero remain.

   Checking by hand only works on the runs you remember to check, so put the check inside any script whose output you intend to trust, and make it **abort** rather than emit a poisoned result:

   ```bash
   # Fail closed: resolve each candidate's cwd, and count only what is ours. (Git Bash / Linux: /proc)
   # MY_TREE is CARRIED, never $(pwd): an explicit argument, an env var set at launch, or a
   # `cd` in this same command. It is the tree the WORK runs in: the directory the run was
   # `cd`-ed into, and NOT the session's own directory. It must have no trailing slash. The
   # `|` arm matches the tree root itself; the `/*` arm matches anything beneath it.
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

   macOS has no `/proc`; resolve cwd with `lsof` instead (same contract, same exit code). Git Bash has **neither** `pgrep` nor `lsof` (verified on Windows 11), so this block cannot run there at all: use the `/proc` form above:

   ```bash
   # macOS: cwd via lsof. Same carried MY_TREE, same anchoring.
   MY_TREE=${1:?pass the tree this run belongs to, absolute and with no trailing slash}
   strays=0
   for pid in $(pgrep -f 'bin/pes''t|artisan' | grep -vx "$$"); do
     cwd=$(lsof -a -p "$pid" -d cwd -Fn 2>/dev/null | sed -n 's/^n//p')
     case "$cwd" in
       "$MY_TREE"|"$MY_TREE"/*) echo "stray pid=$pid :: $cwd"; strays=$((strays + 1)) ;;
     esac
   done
   [ "$strays" -eq 0 ] || { echo "ABORTED: $strays competing run(s) in this worktree"; exit 3; }
   ```

   **The sibling-slot layout makes a bare prefix match wrong, and it fails in the dangerous direction.** Where every slot is a *sibling named after the primary* (`…/<repo>`, `…/<repo>-a`, `…/<repo>-ci`) the primary's path is a **string prefix of every slot's**. A naive `case "$target" in "$MY_TREE"*)` run from the primary therefore claims every slot's processes as its own, and a "kill my strays" sweep built on it kills every other session's run while reporting that it only touched yours. The unanchored **substring** form this guard used to carry (`*"$MY_TREE"*`) was looser still: it admits siblings *and* any path where the tree name appears in the middle: a temp directory, a nested checkout, a backup copy. Anchor on the separator (`"$MY_TREE"|"$MY_TREE"/*`), or compare fully-resolved paths for equality.

   **The obvious default for `MY_TREE` fails in the opposite direction, and that one is silent.** `$(pwd)` reads the *session's* directory, which is not where the work runs. A session whose shell sits in the primary while its gate processes run in a slot anchors on the primary, matches nothing, and the guard reports a clear tree:

   ```
   session cwd           …/<repo>          (the primary)
   gate processes' cwd   …/<repo>-ci       (the runner cd's into the slot)
   anchored match        primary vs -ci  →  NO MATCH
   guard reports         clear: no competing run, exit 0
   ```

   **And a session can hold processes in more than one directory at once, which is what rules out the whole family of cwd refinements.** Measured on one machine, one session, in `uams-statamic`:

   ```
   59518  one session   …/Herd/uams-statamic      <- the primary
   59520  same session  …/Herd/uams-statamic-a    <- its slot
   59538  same session  …/Herd/uams-statamic-a    <- its slot
   ```

   The shape that produces this is an **outer process that never executed the `cd`**, with an inner shell and its children below it; a nested launch is the ordinary way it arises. Together with the several-sessions-at-one-path case in step 4, cwd is therefore **neither unique per session nor single-valued per session**. **It is not `cd <slot> && <command>`**, the idiom [`worktrees`](worktrees.md) mandates for every command. That form runs the `cd` *in* the process it prefixes, so that process and its children share one directory, which is the whole reason the mandate exists. A three-process, two-directory observation cannot come from a two-process shape, and attributing it to that one would tell a reader that following the mandate is what produces the hazard, leaving the two rules irreconcilable. **Do not carry "compare fully-resolved paths for equality" forward as a rescue for this.** That remedy is real and it fixes the *prefix* trap, which is a matching defect over a value that does distinguish: anchoring recovers the discriminator. This is not that: no separator rule, resolution step, or allow-list shape recovers a discriminator from a value that has neither property. The prefix trap is fixable by matching better; this one is only fixable by matching on something else.

   **A per-label COUNT taken this way is inflated by wrapper processes, and this step is where that matters most.** The sweeps above abort on a non-zero total, so the number is not decoration: it is the thing the guard reads. A count inflated by wrappers fails in the safe direction here (an abort that should not have fired costs a re-run, not a corrupted one), but the same output read the other way round: "two processes hold this label, so a peer is live": is wrong, and step 4 measures the magnitude as `+N` per moment rather than a constant. **Count resolved process ids you have positively attributed, never lines matched.**

   **Assert the control is visible before believing the guard's answer.** A guard tested against a control that is not actually there reports `clear` and reads as broken when it is right, or reads as working when it has never matched anything. The control has to be confirmed present by the same enumeration the guard uses, in the same run, before its result means anything. The way a control goes missing is not obvious. `bash -c 'sleep 12' 'vendor/bin/pest'` passes the marker as `$0`, and bash may exec-replace itself with `sleep`, taking the string off the command line. Adding a second statement: `bash -c 'sleep 12; true' 'vendor/bin/pest'`: leaves bash in place and the marker present. **How much this costs is platform-dependent, so measure it rather than assuming the macOS behavior.** Measured in `wordpress-importer` on Windows 11, Git Bash, with a cwd filter excluding the querying shell:

   ```
   bash -c 'sleep 14'        marker visible on 1 process
   bash -c 'sleep 14; true'  marker visible on 3 processes
   ```

   Here the optimized form is *reduced*, not erased: the marker survives on one process. On macOS it has been reported to disappear entirely, so a control built the first way can vanish completely there while merely thinning on Windows. Either way the two-statement form is the one to write. That trap is live for the *control count* as much as for the guard: a plain scan for the marker string matched **15** processes there, all of them the querying command itself, while the cwd-filtered guard correctly reported zero.

   Two further details carry the weight. Match on the **resolved cwd** rather than on the command line *as a whole*: that is what stops the check matching its own invocation (step 3). That instruction used to read "never the command line", and step 4 now qualifies it: resolved cwd separates a **build** process, launched with an explicit `cd`; it does **not** separate a **long-lived** one, which inherits the session's directory and so collapses onto whatever checkout the session sits in. For those, read the **per-session identifier** as a single field out of the command line, which is not a substring search and so cannot match the querying command. And note the platform at the point of use: `lsof` is the macOS instrument and `readlink /proc/*/cwd` the Git Bash one, while **`Win32_Process` exposes a command line but no working directory**, so PowerShell cannot make this attribution at all. Do not write this sweep as though it were portable; name the platform each branch was verified on.

## Validate the sweep before you trust its silence

**An empty result from a blind sweep is byte-identical to an empty result from a clean machine.** Every failure below returned "nothing found" about a process that independent observers had alive seconds earlier. So the discipline this corpus already applies to a negative control applies to the instrument itself:

> **Show the sweep seeing a process known to exist, before reading its empty result as an absence.**

This is the process-sweep instance of a general principle (prove the probe could have returned something else before trusting that it did not) stated once, with its remedy and the other places it has bitten, in [`an-empty-result-is-not-evidence`](an-empty-result-is-not-evidence.md). What follows is the fullest treatment of the process-sweep case and is not superseded by that file; go there when your empty result is not a process sweep.

Six causes were measured in one evening in `uams-statamic`. Only the first is a selection error, and the last two are not detection failures at all:

| Cause | Measured | Why it returns clean |
| --- | --- | --- |
| `ps` without `-A`/`-a`/`-e` (macOS) | 6 of 634 processes | Lists only the terminal's own processes. `-ww` sets **width**, not selection. |
| `Win32_Process` `CommandLine` null (Windows) | 151 of 712 unreadable | A permissions boundary: no rewrite of the filter reaches it. |
| Filtering on `$_.Name` (Windows) | 4 against 16 | An ad-hoc waiter is `sleep.exe` or `bash.exe`, so a name filter for `node`/`gh`/`php` cannot match it. |
| `grep -v grep` (all platforms) | 4 matches → 0 | See below. It preferentially blinds you to the class you are hunting. |
| A GNU flag on BSD (macOS) | published `0`, actual `3` | See below. The probe never ran. |
| The predicate matched the wrong surface | a live foreign process in the output | A shell running a script carries only the script's *path*; the behavior lived inside the file. |

**`ps` without a selecting flag lists only the current terminal's processes.** Measured in `uamswp-migration-api`, same instant:

```
ps -A  -o pid= | wc -l    1123    the real table
ps -axo pid= | wc -l      1123    a selecting form
ps -ww -o pid= | wc -l      29    this session's own shell chain only
```

`-ww` sets output **width**, not selection: the natural thing to reach for when a command line is being truncated, and the resulting sweep searches twenty-nine processes out of eleven hundred. It reports the same clean nothing a complete sweep does.

**`grep -v grep` is unsafe for this and should not be used.** It exists to drop the sweep's own `grep` from its results, and it drops **any** process whose command line legitimately contains `grep`, which a watcher, poller or waiter very often does, because they pipe through it. A process that waits on a rate limit *must* carry `grep` in its own command line, because that is how it reads the header, so the idiom conventionally added to drop the searching process drops every process being searched for, and returns a confident empty. Step 3 already documents the correct fix for the self-match problem: `pgrep`'s self-exclusion, the split-string idiom for the wrapper shell, and reads addressed by pid, which filters nothing real.

**GNU `pgrep` flags are absent from BSD `pgrep`, and the usual fallback converts that into a clean zero.** `pgrep -c` does not exist on macOS: it exits `2` with a usage message. Written as `pgrep -fc '<pattern>' 2>/dev/null || echo 0`, the redirect swallows the usage error and the `||` supplies a reassuring `0`: measured publishing `0` while `pgrep -f '<pattern>' | grep -c .` returned `3` at the same instant. **The failure is in the error handler rather than the enumeration**, which is why no amount of reading the pattern finds it. This rule prescribes `pgrep` and `lsof` for macOS; a sweep copied from a Linux example runs green and empty on every Mac in this fleet. In the same family, **`cmd || echo "(none)"` cannot tell "ran and found nothing" from "did not run"**: an invalid pattern exits non-zero, the fallback fires, and the transcript reads clean apart from a stderr line that scrolls past. Where an absence is load-bearing, read the exit status rather than absorbing it.

[`reading-exit-status`](reading-exit-status.md) owns the mechanics of reading a command's status.

**Control the whole pipeline, not its first stage.** A control that proves `ps` enumerates every process says nothing about the filter behind it: a sweep whose selector was validated and whose `sed` stage was blind is fully blind and looks verified. Point the control at the same command you are about to trust, end to end. **And a control on the expression is only half of it.** It establishes the pattern can match; it is silent on whether the inputs were the ones you meant. Establish both: that the expression finds a string it must find, **and** that the inputs were non-empty and were the intended ones: with a negative control showing what a failed or empty fetch looks like, so a wrong empty and a real one are distinguishable. A run over zero inputs produces the same clean output as a complete run with nothing to find.

**A passing control validates DETECTION, not COVERAGE, and this is the limit of the remedy above.** So the control has a **second half**, because the first passes on three of the six causes above:

- **Count what the sweep could not read**, not only what it matched. The Windows `CommandLine`-null case passes a positive control while 151 processes go unread.
- **Check the predicate against the surface the behavior actually lives on.** A complete enumeration with a readable field and a correct-looking pattern still returned clean while a live foreign process sat in its output.

## The same blindness on an outward-facing write

Everything above is about reading a process table. **The identical failure reaches a command that WRITES somewhere other people read**: a message posted to a shared channel, an issue body, a pull-request comment. The difference is where the cost lands: a mis-read sweep wastes your own time, and a bad outward-facing write is on the permanent record under your name before anyone notices.

**Two failures, and they look alike from the shell.**

| | how it fails | what it costs | what detects it |
| --- | --- | --- | --- |
| **A. never sent** | the tool crashed before sending. Through `\| tail -1` the last line is `Node.js v22.22.3`, which reads as tool chatter rather than the tail of a stack trace | nothing durable: the absence is eventually noticed | read the status **before any pipe**: `cmd > out 2>&1; rc=$?` |
| **B. sent, wrong body** | the write that should have produced the body never ran (a refused compound command, a wrong path) so an **older file at the same path** was sent instead. Exit `0`, success line printed, and from the tool's side nothing went wrong | a claim on the record that nobody meant to make | **nothing but a read-back** of what was actually sent, compared against the intended text |

**B is the one worth designing against, and no amount of exit-code hygiene reaches it.** Every check in this file asks *did the command succeed*. B succeeds.

**So write the body and send it in ONE command, or write it with a tool that cannot be refused.** Where body and send are a single command a refusal fails closed: nothing is written and nothing is sent. Split across two, the send publishes whatever was at that path, which is the previous body.

**The nuance that explains why four sessions did it unnoticed:** whether the pipe costs you the verdict depends on whether the tool's *output* can substitute for its exit code.

```
distinctive success line   the pipe costs the exit code, NOT the verdict
generic or quiet output    the pipe costs the verdict entirely
```

A poster that prints `Posted to <channel> … - ts <id>` on success gives a content-based second channel, so piped posts land fine and the habit survives. **That is why "the pipe is always fatal" is the wrong lesson and invites an accurate objection.** A server-assigned identifier in that line is the strongest form: a crashed run cannot fabricate one, so a `ts` you actually read is proof that post landed, whatever the pipe truncated around it. It proves nothing about a post whose output you never read.

**And a read-back needs its own controls, because it can return a false clean.** A fetch that quietly failed reports no mismatches: indistinguishable from a message that matched. Pair it with a positive control, and tolerate known symmetric transformations: one such check found 18 of 20 lines present, the two misses being `**` rendered as `*` by the channel's own markdown conversion. That is a transformation, not damage. **Keep the two apart: a symmetric change has an inverse and is not a defect; an asymmetric one: a character the shell ate: never comes back, and a read-back that treats them alike either dismisses a real corruption or chases a rendering artifact.**

## The DRY line

This file is the standing statement on **bounding local processes, attributing them, and killing them**. It composes with the [`worktrees`](worktrees.md) rule (which owns the *slot lifecycle* and why two sessions can share one checkout: this rule owns attributing a running process to the tree that started it, and not trampling one somebody else owns once isolated). The per-run *tool* mechanics: `run_in_background`, `timeout`, `Monitor`: live in those tools' own descriptions; don't restate them here.

The general form of the sweep section is [`an-empty-result-is-not-evidence`](an-empty-result-is-not-evidence.md)'s; reading a command's status is [`reading-exit-status`](reading-exit-status.md)'s; choosing what to call is [`github-api-budget`](github-api-budget.md)'s.
