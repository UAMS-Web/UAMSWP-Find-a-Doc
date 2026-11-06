---
name: pcov-setup
description: >-
  How to get a coverage driver (PCOV or Xdebug) working for Pest mutation testing
  (`--mutate`) and code coverage (`--coverage`) under Laravel Herd on macOS and Windows, and
  how to tell a mutation run that executed from one that did not. Activate when `--mutate`
  errors with "Mutation testing requires code coverage to be enabled" or reports "0 Mutations
  for 0 Files created", when a run reports 100% in implausible time, when mutation or coverage
  runs are slow or falling back to Xdebug (`herd coverage`), when every `php` on a Mac dies
  with `Killed: 9`, when setting up a new machine for the adversarial-review mutation step,
  when deciding which PHP to run a mutation pass on, or when the user asks how PCOV was set up
  or how to get fast coverage. Covers the Herd static-PHP constraint, Herd's bundled Xdebug as
  the zero-install route, building PCOV and wiring it into Herd's php config, the macOS
  code-signing failure, the Windows env-block and command-line limits, the invocation and
  plugin defects that score a mutant killed without running it, and the classes a mutation
  run cannot reach.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/pcov-setup/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): pest.
-->
<!-- cspell:ignore codesign CODESIGNING dlopen dyld elif hardlink imagick Mathur's phpversion repoint Shivam Triaging -->

# Coverage driver for Pest mutation testing and coverage

Pest's mutation testing (`--mutate`) and code coverage (`--coverage`) need a **coverage driver** (PCOV or Xdebug) to know which tests cover the line it just mutated. Herd ships only **Xdebug** (`herd coverage` loads it); Xdebug coverage collection is several times slower than **PCOV** (coverage-only, purpose-built). This skill gets a driver working under Herd's PHP so `composer mutate`, `vendor/bin/pest --mutate` and a coverage run execute, and shows how to tell that they did.

The coverage driver is a **per-machine install that does not travel via git**: each developer's machine needs it set up once. A fresh checkout on a fresh machine runs the ordinary suite and gate fine; only `--mutate` and `--coverage` need anything extra.

## Check what you have

**Run this before anything else here, and run it again after any install.** It answers three outcomes rather than two, and it does not pipe `php -m`: a pipeline's exit status belongs to its last element, not to the command you cared about, so `php -m | grep pcov` reports a dying interpreter as a missing extension.

```bash
coverage_driver_state() {
    out=$(php -m 2>&1); rc=$?

    if [ "$rc" -ne 0 ]; then
        printf 'PHP DID NOT RUN (exit %s, %s bytes). The extension is not the question.\n' "$rc" "${#out}"
        return 2
    fi

    printf '%s\n' "$out" | grep -qx 'pcov' && printf 'pcov LOADED\n' && return 0
    printf '%s\n' "$out" | grep -qx 'xdebug' && printf 'xdebug loaded, pcov absent\n' && return 1

    printf 'php runs; neither pcov nor xdebug\n'
    return 1
}

coverage_driver_state
```

**Running it first is not ceremony.** A machine that was already broken before you started will otherwise have its breakage attributed to this procedure, and the recovery you reach for will be the wrong one.

**Exit 2 means stop.** It is the outcome that looks most like a failed install and is least like one: `php -m | grep pcov` prints nothing and exits 1 in *both* cases, which reads as "the extension did not load" and invites a retry of the install that just made every `php` invocation on the machine die. When you see exit 2:

- Do **not** re-run the install, and do not replace or re-sign anything yet.
- Try a version-pinned binary (`php82 -v`, `php83 -v`), since a fault in one build is not a fault in all of them.
- Re-run `coverage_driver_state` after a few minutes before concluding anything is permanent. On 2026-09-09 a replaced extension on a macOS development machine failed every load for roughly fifteen to twenty minutes (the two recorded accounts differ by that much) and then began loading normally **with nothing on disk changed**: same inode, same mtime, same SHA-256, verified against bytes captured while it was failing. By then a permanent fault had been diagnosed and a re-sign was being prepared. **No cause is claimed for that recovery, and you should not infer one.** The write started it and time ended it; nothing observable on disk accounts for the difference. That is unsatisfying and it is what the evidence supports, which is exactly why the instruction is to wait and re-check rather than to apply a remedy derived from a mechanism nobody has established.
- Then read [When `php` dies with no output](#when-php-dies-with-no-output-macos-only).

```bash
php -r 'echo ini_get("pcov.enabled"), "\n";'
```

Herd keeps per-version ini files at `~/Library/Application Support/Herd/config/php/<version>/` on macOS. PCOV on 8.4 is a `pcov.so` and a one-line `pcov.ini` sitting in that directory.

The reason the check captures `$?` before anything else runs is the [`reading-exit-status`](../../rules/reading-exit-status.md) rule.

## Which PHP to run mutation on

Mutation testing measures whether the tests would notice a behavior change, which is a property of the tests, not of the interpreter. Running it on a different PHP minor than production measures exactly the same thing. So run it on whichever installed PHP has a driver, and keep the ordinary suite on the version production runs. If a future change makes mutation results depend on the PHP version (they should not), that is the point to revisit this, not before.

## What a driver buys you (measured)

- **Coverage collection** is the fast part, and PCOV's win shows there and on full `--coverage` runs over large suites. The gap widens with suite size.
- **Mutation wall-clock is not all coverage.** Every mutant re-runs the covering tests in a fresh process, and each of those boots the application or WordPress. That per-mutant boot dominates a small, diff-scoped `--mutate` run, and no coverage driver speeds it up. Set expectations accordingly: PCOV is the right driver, but it is not a 10x win on every `--mutate` invocation, which is also why Herd's bundled Xdebug is usually good enough to start with.

## The Herd constraint (why you can't just `pecl install`)

Herd's PHP is a **statically-compiled build** (`php -v` → "Built by Laravel Herd", NTS, arm64 on Apple Silicon). It ships **no PCOV**, has **no CLI toggle** for one (`herd coverage` only enables Xdebug), and `pecl install` can't target it (no `phpize`/`php-config` dev headers; its `extension_dir` points at a non-existent path).

But Herd **loads external `.so`/`.dll` extensions by absolute path**: that is exactly how it loads its own Xdebug (`zend_extension=/Applications/Herd.app/Contents/Resources/xdebug/xdebug-84-arm64.so`). So there are two routes: load the Xdebug Herd already ships, or **obtain a PCOV binary built for the matching PHP and point Herd's `php.ini` at it.**

**API match is the only hard requirement for a PCOV binary.** A `pcov.so`/`pcov.dll` built for the same PHP **extension API** (PHP 8.4 → `20240924`; PHP 8.2 → `20220829`), same thread-safety (NTS), and same architecture loads into Herd **even across patch releases**: re-verified on macOS 26.4, where Herd's **8.4.23** loads a `.so` built against Homebrew's **8.4.22** (same API `20240924`). A **minor** bump (8.4 → 8.5) changes the API and needs a rebuild.

**On macOS there is a second hard requirement, and it is not about the API at all: the `.so` must carry an ad-hoc code signature.** See the build steps below: skipping it does not fail the load, it kills `php` outright.

## Route 1: Herd's bundled Xdebug, zero install (macOS)

Herd ships an Xdebug build for every PHP version it manages at `/Applications/Herd.app/Contents/Resources/xdebug/xdebug-<version>-arm64.so`. Load it per-invocation:

```bash
php -d zend_extension=/Applications/Herd.app/Contents/Resources/xdebug/xdebug-84-arm64.so \
    -d xdebug.mode=coverage \
    vendor/bin/pest --mutate --covered-only --path=<source dir>/<File>.php
```

Slower at collecting coverage than PCOV, and that is mostly not what a mutation run spends its time on (see the measurements above. This is the right first choice; build PCOV only if Route 1's coverage phase measurably hurts.

**On Windows this example's own outer invocation matters, but not in the way "make it absolute" suggests.** The mutate plugin builds each per-mutant child's command from *this* process's own `argv[0]`, and a forward-slash `argv[0]` (what any bare or hand-typed `vendor/bin/pest` call produces, absolute or not) fails every child launch and fabricates a passing score on an unpatched plugin. A `composer mutate` script carries the fix where it is prefixed `@php`, which is what normalizes the separator; this hand-rolled Xdebug form is not run through Composer, so prefer `composer mutate` or `composer exec -- pest …` on Windows, which already do. See [Before trusting a score](#before-trusting-a-score-the-negative-control) before trusting any single result.

## Route 2: build PCOV (macOS + Herd)

Herd installs via Homebrew. Use a Homebrew **`php@8.4`** purely as the **build toolchain** for `pcov.so`, then load that `.so` into **Herd's** PHP. Herd stays the default `php`; it already has every extension the application boots with (notably **imagick**, which a parallel plain Homebrew PHP lacks, so a Statamic suite won't even boot under it).

**Re-signing the live file in place is the EMERGENCY form, not the procedure.** `codesign --force --sign - "$HERD84/pcov.so"` is what to try on a host that is already broken; it is not how a healthy one is updated, because it has no intact state to fall back on if it fails partway. Step 3 below is the procedure, and it is the one to re-run after a rebuild or a Herd update.

```bash
# 1. Build toolchain (homebrew-core, keg-only). NOTE: the install LINKS php into
#    /opt/homebrew/bin and shadows Herd as the default `php`. Undo that immediately:
brew install php@8.4 && brew unlink php@8.4   # ONE command: a run that stops here cannot
                                              # leave Herd shadowed by the brew build.
                                              # `which php` returns to Herd; invoke brew
                                              # php by full path only

# 2. Build + install pcov against the brew php@8.4. Two gotchas on Homebrew:
#    (a) pcre2.h not found  -> the pcre2 headers are NOT on the default include path
#        and the build hard-fails without them
#    (b) pecl mkdir into the Cellar fails -> pre-create the API-keyed pecl dir
mkdir -p /opt/homebrew/lib/php/pecl/20240924
printf "\n" | CPPFLAGS="-I$(brew --prefix pcre2)/include" \
  /opt/homebrew/opt/php@8.4/bin/pecl install -f pcov
#    -> installs /opt/homebrew/lib/php/pecl/20240924/pcov.so

# 3. Stage the .so in Herd's own config dir (decouples it from the brew keg, so it
#    survives `brew uninstall php@8.4` and php@8.4 patch bumps), SIGN IT THERE, and only
#    then move it into place. Ad-hoc signing is NOT optional on macOS: an unsigned .so at
#    the live path leaves EVERY php on the machine dying with `Killed: 9` and no output.
#    WHY signing helps is NOT established -- it is cheap and non-destructive, so do it
#    regardless. See "When php dies with no output" below.
#
#    ORDER MATTERS AND IS THE WHOLE POINT. `mv` within one filesystem is atomic and is the
#    only step that touches the live extension, so an interruption anywhere leaves the
#    previous working one in place. Copying first and signing afterwards would leave an
#    UNSIGNED .so at the live path in between, and `cp` writes THROUGH an existing file,
#    so there is no moment at which the old one is still intact.
HERD84="$HOME/Library/Application Support/Herd/config/php/84"
cp /opt/homebrew/lib/php/pecl/20240924/pcov.so "$HERD84/pcov.so.new"
codesign --force --sign - "$HERD84/pcov.so.new"
mv "$HERD84/pcov.so.new" "$HERD84/pcov.so"
printf 'extension=%s/pcov.so\n' "$HERD84" > "$HERD84/pcov.ini"

# 4. Verify. Runnable ON ITS OWN at any time: it needs no variable from the steps above,
#    so a partially-applied install is detectable without re-running any of them.
#    Do NOT write this as `php -m | grep pcov`: the pipe replaces php's exit status with
#    grep's, so a dying php reads as "extension missing" rather than as a broken machine.
#    Ask php directly, and let its own exit status stand.
php -r 'echo phpversion("pcov"),"\n";'
coverage_driver_state
```

This procedure was settled in `UAMS-Web/wordpress-importer-archive#1011` (merged as PR `#1014` at `6848b207a`) and adopted by the sibling repositories; the pipe-free verification in step 4 is the one point on which the copies had diverged, and the pipe-free form is kept for the reason the comment states.

**Step 2's `pcre2.h` failure is real and current**: verified on macOS 26.4 by building without `CPPFLAGS`: `…/php_pcre.h:23:10: fatal error: 'pcre2.h' file not found` → `ERROR: 'make' failed`, exit 1, no `.so`. With the flag, `Build process completed successfully`.

**Step 3's signing is the one that will cost you an afternoon if you skip it, because the failure has no error message.** Skip it and every `php` invocation on the machine can start dying outright, in every repository, for every user of that machine, including people who never ran this install:

```
$ php -v
Killed: 9          # exit 137, and NO output whatsoever, same for php -m, php -r, artisan, composer
$ php -n -v
PHP 8.4.23 …       # works, because -n skips the ini that loads pcov
```

There is no `Unable to load dynamic library` warning to go on, every `php` invocation on the machine simply dies. **`php -n` working while plain `php` does not is the tell**, and `codesign --force --sign -` on the `.so` is the step to try. Re-run it any time you rebuild or re-copy the extension. **Why it helps is not established**: see below, where the remedies that have and have not worked are recorded together with the reason none of them demonstrates a cause.

**No `herd restart` is needed for mutation/coverage.** The CLI `php` re-reads the ini scan dir (`…/Herd/config/php/84/`) on **every invocation**, so pcov is live for `composer mutate`, `vendor/bin/pest` and a coverage run the moment the ini is written. A restart only matters for the **served** site (PHP-FPM behind `*.test`), which caches its config at worker startup, and you only need pcov there to collect coverage over real HTTP requests, which mutation/coverage testing doesn't.

### The blog-post one-liner that does *not* fit Herd

The "PCOV in Laravel Herd" posts say `brew install shivammathur/extensions/pcov@8.4` (a prebuilt bottle). That formula **depends on `shivammathur/php/php@8.4`**: a whole parallel PHP toolchain, **not** homebrew-core's `php@8.4` or Herd. Only use it if you already run Shivam Mathur's PHP. On a Herd + homebrew-core machine it pulls in an unwanted second PHP stack, so the build-it-yourself route above is the self-contained one.

### macOS caveats

- **Keep `php@8.4` installed but unlinked**: it is the rebuild toolchain.
- **Herd PHP minor upgrade (8.4 → 8.5) breaks the `.so`** (the extension API changes). Rebuild against a matching brew PHP (`brew install php@8.5`, repeat steps 2–4 with the new API dir). Patch bumps keep the same API and need nothing; Herd moved 8.4.21 → 8.4.23 with no rebuild.
- If a Herd update regenerates `config/php/84/`, re-add `pcov.ini` + `pcov.so`, **and re-run the `codesign` step**, because you are copying the linker-signed build back in.
- **Overhead: leave `pcov.enabled=1` (the default) global.** PCOV only records between the test runner's coverage start/stop, so a loaded-but-idle pcov adds negligible cost to normal CLI/serving. If you want it truly dormant anyway, set `pcov.enabled=0` in `pcov.ini` and run coverage with `-d pcov.enabled=1`.
- **Don't blame pcov for a slow session.** A loaded pcov is a tempting, wrong explanation for a CPU-pinned PHP process. When PHP is pinned, **sample the stack** (`sample <pid>` / `process sample`) and read the actual frames instead of guessing at extensions.

## When `php` dies with no output (macOS only)

**This whole section is macOS-specific.** `codesign` and the hardened runtime do not exist on Windows, where PCOV loads from Herd's `php.ini` with no signing step and none of this applies.

**The tell: `php -n` works and plain `php` does not.** Every `php` invocation on the machine simply dies, which presents as PHP itself being destroyed rather than as an extension problem, and it makes the machine's own diagnostics useless, because `php -v`, `php -m` and `php -i` are each killed in turn. **Reason from the extension, not from PHP.**

**Reach the diagnosis first:** four things cost hours on 2026-09-06, and each is cheap once known.

**1. `codesign --verify` exits 0 on the faulting file, so it reads as an exoneration.** It hashes the file **at rest**; the kill is a validation failure when a page is faulted in **at runtime**. Those are different questions and only the second is the one `Killed: 9` asks, so a passing check excludes nothing. More than one person concluded the signature was innocent from it during a single diagnosis. Record the *flags* when you report the fault; they say what kind of signature it is, but **do not treat them as the discriminator either**:

```
codesign -v <pcov.so>                    exit 0, no output          <- says nothing useful
codesign --display --verbose=4 <pcov.so> flags=0x20002(adhoc,linker-signed)
codesign --display --verbose=4 <php84>   flags=0x10000(runtime)     <- hardened host
```

**Neither the verify status nor the flags predicts the load, and an outage on 2026-09-09 settled it: `php84` SIGKILLed for roughly twenty minutes and then recovered on its own, with the `.so` unchanged throughout**: same inode, same `sha256`, same `flags=0x20002(adhoc,linker-signed)`, in both the failing and the working state. The same file, byte-identical and signature-identical, was observed to kill and to load within the same hour. So no static property of the file separates the two states; only exercising the load does. Note also what that episode did NOT establish: nobody re-signed anything, so recovery cannot be credited to any remedy, and the trigger remains unknown.

**Read `linker-signed` as a TELL, not as the cause.** It is cheap to check and correlates with failing specimens, and that is all it earns: the healthy Homebrew copy carries it too, and loads. A byte-identical copy of a failing specimen (`sha256 2288b4ff64015079a125aca36404025f9b8d5f76c66e39d815da828e1c796260`), still `flags=0x20002(adhoc,linker-signed)`, loads into the same hardened-runtime `php84` with exit 0. The `CodeDirectory` flags look like the discriminator and are not, two sessions arrived at this reading on different machines hours apart and both withdrew it, so a third arrival should be expected:

```
appears broken   flags=0x20002 (adhoc, linker-signed)
appears fixed    flags=0x2     (adhoc)
```

The entitlements mislead the same way: `php84` carries `com.apple.security.cs.disable-library-validation`, so library validation is already off, which reads as ruling a signing cause out, and does not:

```
com.apple.security.cs.disable-library-validation = True
com.apple.security.cs.allow-unsigned-executable-memory = True
com.apple.security.cs.allow-jit = True
com.apple.security.cs.allow-dyld-environment-variables = True
```

**2. `herd php:list` is a casualty, not a diagnosis.** The obvious tool for enumerating interpreters is itself written in PHP and resolves the same broken default:

```
$ herd php:list
Error finding executable PHP. Quitting for safety.
Provided output from find-usable-php.php:
```

That reads as a broken Herd installation and is a symptom. Enumerate the binaries directly and run each one: `ls "$HOME/Library/Application Support/Herd/bin/php"*`.

**3. One interpreter is dead and it presents as the whole machine.** Measured across every Herd version present:

| interpreter | result |
| --- | --- |
| `php74` `php80` `php81` `php82` `php83` `php85` | exit 0 |
| `php84` | exit 137, `Killed: 9` |

Only the version whose `pcov.ini` loads the faulting `.so` dies. The `PATH` shim resolves to that one, so every `php` in every repository fails and the machine looks wholly broken, which is what sends people looking at Herd, at `PATH`, and at the shell rather than at one extension.

**4. The kernel writes a report, and it names the subsystem in one read.** `~/Library/Logs/DiagnosticReports/php84-*.ips` (JSON from line 2 onward):

```
termination  {"namespace": "CODESIGNING", "code": 2, "indicator": "Invalid Page"}
exception    EXC_BAD_ACCESS, SIGKILL (Code Signature Invalid)
```

Read it first when it exists: it converts a silent `Killed: 9` into a named subsystem immediately. **But it may not exist, and its absence proves nothing.** During the 2026-09-09 outage (`php84` SIGKILLing for roughly twenty minutes, reproduced across dozens of invocations), `~/Library/Logs/DiagnosticReports/` contained **no `php84-*.ips` report at all**, and none was written while the outage ran (39 reports present, none mentioning `php`, with the directory readable and non-empty as the control). So a reader who checks here first, finds nothing, and concludes the fault is not code-signing related has learned nothing about the fault and something about the instrument. Treat a missing report as *unavailable*, never as negative evidence; the `php -n` tell is the one that held throughout. A report naming `CODESIGNING` is equally consistent with every hypothesis that survives below, so it cannot discriminate between them.

**A sharper isolation than `php -n`, and the control is what makes it an attribution:**

```
php84 -n -d extension=<path>/pcov.so -r 'echo 1;'   exit 137, Killed: 9
php84 -n -d extension=json           -r 'echo 1;'   exit 0      <- control
```

The `-n` tell establishes only that *some* ini-loaded extension is responsible. This names the file without the ini involved at all, and the control row shows `-d extension=` is not itself fatal, without it the first row is a coincidence rather than an attribution. A further control shows the kill is specific rather than a generic ini failure: `php -n -d extension=<nonexistent>.so -v` returns **rc 0**, because PHP warns on a missing extension instead of dying.

**`-n` is not a workaround for a coverage run.** It restores `php` by skipping the ini, which unloads `pcov`: the extension a coverage or `--mutate` run exists to use. It is a diagnostic, and for the one job this skill is for it is not an escape.

### What to try, and what it does not prove

**The first thing to try is `codesign --force --sign -` on the `.so`** (the emergency form; the staged procedure in Route 2 step 3 is how a healthy host is updated), but read the next paragraphs before concluding it worked, and re-test after a bounded wait **before** re-signing, so a spontaneous recovery is visible instead of being attributed to the remedy.

**Whether re-signing actually repairs this is NOT established, and the evidence is confounded.** On 2026-09-06 a host recovered after `codesign --force` was run. On 2026-09-09 a host recovered with the file **untouched**: inode, mtime, ctime and `sha256` identical across the failing and working states:

```
~08:55   php -v   rc 137, zero bytes     every invocation killed
~09:05   php -v   rc 0, PHP 8.4.23       working again
the file, throughout and never touched:
         ctime      08:55:18      unmoved -- so nothing rewrote it
         inode      380328727     unchanged
         sha256, signature flags   identical while failing and while working
```

**`ctime` is the load-bearing figure.** `codesign --force --sign -` writes the file, so it necessarily moves `ctime`. An unmoved `ctime` is positive evidence that **no rewrite happened**, not merely an absence of evidence for one, so this recovery cannot be credited to any remedy that writes the file, and it leaves the 2026-09-06 recovery consistent with either the command working or enough time having passed. Nothing recorded separates them.

**That matters because the claim is self-confirming exactly where it is read.** Someone whose machine is down runs the command, the host recovers, and the attribution is reinforced whether or not it is true. Run it; it is cheap and it is the only candidate anyone has, and do not record that it worked. **What would establish a remedy, and what nobody has:** a failure that persists across a control interval and then clears only on the command. No such observation exists on either machine.

**What was measured against the live fault on 2026-09-06**: observations, not demonstrated causes, per the bound above:

| approach | works? |
| --- | --- |
| `codesign --force --sign -` | **yes**: the documented step |
| `cp` the `.so` elsewhere + repoint `pcov.ini` | **yes** |
| `mv` the `.so` + repoint `pcov.ini` | **no**: the fault follows the file object |
| rename in place + repoint | **no**, for the same reason |
| removing `pcov.ini` | yes, but it costs coverage machine-wide |

**Do not record relocation as "move it out of `~/Library/Application Support/`".** Written down as a location fix it sends the next reader hunting for a safe directory that does not exist, and it breaks the moment somebody relocates with `mv` or `ln`.

**Do not reason from a stated mechanism, because the ones that look obvious are wrong.** On 2026-09-06 the same bytes loaded fine from a path outside the Herd tree, loaded fine from the same directory under a different filename, and were still killed through a hardlink at a second path. The pair that separates the path from the file object, which neither row can do alone: a copy changes the inode and the path at once, and only the hardlink holds one fixed while varying the other:

```
original             inode 225170398   path A   exit 137   dies
hardlink to it       inode 225170398   path B   exit 137   dies   same object, new path
byte-identical copy  inode 380328717   path C   exit 0     loads  new object, same bytes
```

`ln <so> /tmp/x.so` is the whole step. Note what the `cp` row does *not* show: a `cp` mints a new inode **and** drops whatever the kernel had cached about the old one, so the copy differs in more than one variable. The defensible statement at the time was that *this file object is fatal to `dlopen` while a fresh copy of identical bytes is not*, and **that statement has since been falsified** by the 2026-09-09 self-clearing recovery above. So the file object is **not** fatal; something time-varying is, and it is not visible in the file at rest. Every attempt to reproduce the state synthetically failed: in-place overwrite, overwrite-after-load, rewrite via redirect and re-sign-after-load each produced a loadable `.so`. Four explanations were offered while it was being diagnosed and all four were withdrawn. The observations stand as observations; nothing here should be read as a mechanism.

**Two readings that look exculpatory and are not.** Both were raised and both withdrawn:

- *"The `.so` is already ad-hoc signed."* It is, and so is every specimen. That is the reason the flags are the question rather than the signature's existence.
- *"The `.so` has not been written to in 28 hours."* An unchanged inode and mtime are **consistent with this failure**, not evidence against it. Anyone reading an untouched file as proof that signing is not involved has the argument backwards, and that inference has been published and retracted.

### Asking whether a machine is in the bad state

The failure is silent, machine-wide, and outside any repository, so it strikes people who never ran this install and have no reason to suspect PCOV. Run this rather than reading for symptoms; it needs no knowledge of the machine's history:

```bash
php -n -v >/dev/null 2>&1; without_ini=$?     # capture ONCE, into a variable
php    -v >/dev/null 2>&1; with_ini=$?        # see "capture it once" below

if   [ "$with_ini" -eq 0 ];    then echo "ok"
elif [ "$without_ini" -eq 0 ]; then
    echo "BROKEN: php starts without its ini and dies with it - something it LOADS is"
    echo "  killing it. If pcov was installed here, see .claude/skills/pcov-setup/SKILL.md"
    exit 1
else
    echo "BROKEN: php does not start even with -n - the interpreter itself, not an extension"
    exit 1
fi
```

**The pair is the point, not the `php -v` alone.** A single `php -v` proves the interpreter is dead and not *why*. The `-n` arm costs one extra invocation and separates **"PHP is broken"** from **"something PHP loads is broken"**: which is the distinction that turns a dead machine into a diagnosis, and it needs no prior suspicion of PCOV and no extension path.

**Capture the status once, into a variable.** Two `$?` expansions in one line read *different* things: the second reports the status of the first. On the live specimen, a probe written to demonstrate this very class printed `rc=0` for a process the shell had just reported as `Killed: 9`.

**And use no pipe.** `php -m | grep pcov` replaces `php`'s exit status with `grep`'s, so a **dying interpreter** reports as a **missing extension**: opposite problems needing opposite responses, and it is the check most people write first. Measured against the live fault it returned `0`.

**Do not check the signature instead.** `codesign -d --verbose=4 … | grep linker-signed` false-positives: the healthy Homebrew copy carries that flag too, and loads. `codesign -v` is no better; it exits `0` silently on a file that is about to be killed. A check for this fault has to exercise the failing behavior.

**Verified in both directions.** *Detect:* captured from a live instance of the fault on macOS 26.6.2 / Herd `php84` 8.4.23, before any remedy: `php -v`, `php -m` and `php -r` all `rc 137`, while `php -n -v` returned `rc 0`. That arm was perishable and cannot be re-gathered; the state has never been reproduced on demand. *False-positive:* `ok` on a healthy macOS install, and `ok` on Windows PHP 8.4.20 with **pcov loaded**, which is the case that matters: a control without pcov cannot show the check tolerates a working pcov install. All three branches were exercised. **A check shown only not to false-positive is an untested guard**: which is the defect this command exists to remove, so it is held to the standard it enforces.

It reaches the **served** sites too, on a delay: an FPM worker that restarts while the bad `.so` is in place dies the same way, and the `*.test` host starts answering `500`/`502` after the CLI is already fixed. `herd restart` clears it, verified on macOS 26.4, where a slot went `502` → `200` and the other sites were unaffected because their workers never recycled during the window.

## Windows + Herd

PCOV is set up in Herd's Windows `php84` as a per-machine install (the same shape as macOS: a matching `pcov.dll` loaded via Herd's `php.ini`). Verify it is active with `coverage_driver_state` above. If it is missing, install/point a `pcov.dll` built for the Herd Windows PHP's API/NTS/arch and add the `extension=` line to Herd's Windows `php.ini`.

- **env-block-size limit (mutation only).** The mutate plugin spawns one subprocess **per mutant** via Symfony Process, passing the **full** Windows environment. Over a long session the env grows, and once it exceeds the **32767 UTF-16 code-unit** limit the mutation run dies with `InvalidArgumentException: The environment block size (NNNNN) exceeds the Windows limit` (the ordinary test suite still passes; only `--mutate` aborts). **Workaround: relaunch with a trimmed env** via the Bash tool (`APP_ENV=testing` is for a Laravel application; drop it elsewhere):

  ```bash
  env -i PATH="$HOME/.config/herd/bin/php84" SystemRoot='C:\WINDOWS' \
    TEMP="$TEMP" TMP="$TMP" APP_ENV=testing \
    php vendor/bin/pest --mutate --path=<source dir>/<Subdir> --class="<Namespace>\<Class>"
  ```

  That line passes a forward-slashed `argv[0]`, which on an unpatched plugin is the fabrication form described under [Before trusting a score](#before-trusting-a-score-the-negative-control). Run the check there before trusting its result.

## Running mutation / coverage once a driver is present (cross-platform)

- **Use the repository's `composer mutate` script where it has one.** It bakes in the relative `--path` and, where needed, the `@php` prefix and a lifted Composer process timeout. Where there is none, invoke `vendor/bin/pest --mutate --path=<source dir>` yourself, or `composer exec -- pest --mutate …`, which resolves the binary the same way an `@php`-prefixed script does.
- **Always pass a relative source path** (`--path=app`, `--path=src`, `--path=includes`). The mutate plugin's file finder mishandles **absolute** `<source>` paths, on Windows a drive-letter path isn't seen as absolute (doesn't start with `\`), so it's doubled to a non-existent `…\D:\…\app` → **0 files, 0 mutations**. PHPUnit hands the plugin absolute `<source>` paths from `phpunit.xml`, which trips this; a relative, POSIX-style `--path` sidesteps it.
- **From a linked git worktree**, even a relative `--path=<source dir>` can find 0 files. Scope to a **relative sub-path** of the changed class and bypass the convenience script:

  ```bash
  vendor/bin/pest <test-file> --mutate --path=<source dir>/<Subdir> --class="<Namespace>\<Class>" --covered-only
  ```

  Passing a test file narrows the initial run, which the timeout paragraph below says inflates false timeouts; read any `timeout` from this form as unverified.
- **`0 Mutations for 0 Files created` means it found nothing to mutate: a setup failure, NOT a pass.** Usually a missing driver or a path the finder could not resolve. Treat it as "fix the invocation," never as a green.
- **`--everything` overrides `--class`: never combine them.** With `--everything`, the run mutates every file under `--path` and ignores what `--class` names, so a command that reads as class-scoped takes hours over the whole directory and its score describes files nobody asked about. Measured in `UAMS-Web/aether-wordpress-importer` at `e6ea8a3` on macOS (Darwin 27.2.0), PHP 8.4 with PCOV, `pestphp/pest-plugin-mutate` `v5.0.1`, both runs with `--testsuite=Unit,Integration --path=src/Services/WordPressImport/Utilities`: `--class` naming one class reported `8 Mutations for 1 Files created`, and `--everything` with `--class` naming another class reported `10227 Mutations for 100 Files created`. Read the `Files created` count before letting a run continue. Without `--everything`, measured in the same repository at `018064f` on Windows 11, PHP 8.4.20 with PCOV, `pest-plugin-mutate` `v5.0.1`, same `--testsuite` and `--path`: a comma-separated `--class` list naming two classes reported `10 Mutations for 2 Files created`, so a list scopes a run. `--ignore` naming one of those two classes beside the list changed nothing (still `10 Mutations for 2 Files created`, and both files ran), so `--ignore` given a namespace-qualified class name did not drop it from a run that names classes. `--ignore` naming a sub-namespace with no `--class` printed no `Files created` line in 12 minutes, though the run had reached `Mutating application files...`, and was stopped, so whether it scopes a run is not measured. The `Files created` line prints only after the initial test run, about 6.5 minutes here, so a bound shorter than that sees no count at all.
- **Never pass `--filter` to a `--mutate` run; scope it with `--path`.** The plugin sets every child's timeout from the initial run (its duration plus the larger of 5 seconds and 20%) and scores a timeout as caught (`(tested + timedOut) / total`). Unfiltered, the initial run is the whole suite and each child runs only the tests covering its mutant, so a survivor finishes in time. A narrow `--filter` makes the initial run and a child's tests nearly the same set, and a survivor's child overruns on its own start-up. Anything else that narrows the initial run, such as `--group` or a test-file argument, should do the same; that is inferred from the formula, not measured.
- **Coverage report:** `vendor/bin/pest --coverage-html=build/coverage` (fast under PCOV).

## Coverage population and instrument scripts

PHP helpers ship under [`scripts/`](scripts/) with this skill and sync into every Pest repository (`.claude/skills/pcov-setup/scripts/` and the matching `.agents/skills/` copy). They walk up from their own path for `vendor/autoload.php` or a phpunit config, so they run from a primary checkout or a worktree without editing.

| Script | What it answers |
| --- | --- |
| [`build-coverage-scope-csv.php`](scripts/build-coverage-scope-csv.php) | Reads `<source>` from `phpunit.xml` / `phpunit.xml.dist` and writes the three-pass tracker CSV (`file`, `pass1_touched`, `pass2_line_pct`, `pass3_mutation_clean`). That CSV is the inventory. |
| [`publish-coverage-scope-comment.php`](scripts/publish-coverage-scope-comment.php) | Upserts a Markdown tracker comment on the repository's full-coverage issue from the CSV. It requires `--pass2-min=<N>` and exits 1 without it. It posts to the `origin` repository unless `--repo=<owner>/<name>` names another, and exits 1 when neither resolves. |
| [`check-mutation-instrument.php`](scripts/check-mutation-instrument.php) | Whether a mutant child can actually launch on this machine, and whether an identical-copy child reaches test discovery through the invocation a run uses. Exit `0` only before trusting a mutate score; exit `1` means scores can fabricate kills (see below); exit `2` means the probe measured nothing. |

```bash
php .claude/skills/pcov-setup/scripts/build-coverage-scope-csv.php --pass1
php .claude/skills/pcov-setup/scripts/check-mutation-instrument.php
```

### Three-pass walk of the inventory

Close a full-coverage ticket by walking every row in the CSV, one pass at a time across the whole population (or file-by-file through all three). Keep the CSV current as the tracker; each pass preserves cells the other passes already filled. **Pass 3 closes only on a Linux mutation run** (the `Bar` section of the [`tests`](../tests/SKILL.md#bar) skill, UAMS-Web/uams-claude-skills#543): a score taken on Windows or macOS while walking the rows is working evidence and is repeated on Linux before the ticket closes.

| Pass | Column | Passes when |
| --- | --- | --- |
| 1. File touched | `pass1_touched` | The class FQCN appears in `tests/` (static reference). This is **not** executed-line coverage. Run **once** for the whole inventory. |
| 2. Meaningful line % | `pass2_line_pct` | Line coverage % from a Clover report, recorded for every `pass1=yes` row still empty (`0` if covering tests never hit the file). Batched (~30s). A "meaningful" floor (e.g. ≥80%) is a ticket decision; until set, **pass 2 done** means the cell is non-empty. |
| 3. Mutation-clean | `pass3_mutation_clean` | For every row with pass 2 done (and meeting the meaningful floor once set): Pest can mutate the class, every killable mutant is killed, and every survivor is explained (**no unexplained survivors**, not a literal `100%` score). Record `yes`, or a short note for explained survivors. Batched like pass 2 (~30s; often one class per batch). |

```bash
# Pass 1: whole inventory in one set (static touch check)
php .claude/skills/pcov-setup/scripts/build-coverage-scope-csv.php --pass1

# Pass 2: next N pending pass1=yes files (covering Unit/Feature tests + Clover).
# Target ~30s per batch: start at --batch=1, raise until wall time is ~30s.
# On uams-statamic (Windows / Herd PHP 8.4 / PCOV), --batch=3 averaged ~29s.
php .claude/skills/pcov-setup/scripts/build-coverage-scope-csv.php --pass2 --batch=3

# Publish / refresh the living Markdown table on the coverage ticket
# --pass2-min is this repository's own pass 3 bar (see "The pass 2 floor" below).
php .claude/skills/pcov-setup/scripts/publish-coverage-scope-comment.php --issue=<number> --pass2-min=<N>

# Pass 3: after pass 2: mutation-clean each eligible file (instrument check first)
php .claude/skills/pcov-setup/scripts/check-mutation-instrument.php
# --pass3 selects rows by the floor, so it requires --pass2-min=<N> and exits 1 without it.
php .claude/skills/pcov-setup/scripts/build-coverage-scope-csv.php --pass3 --batch=1 --pass2-min=<N>
# Snowball order (smallest estimated mutant count first): not CSV order:
php scripts/estimate-pass3-mutants.php --print
node scripts/pass3-next.mjs
composer mutate -- --class="App\\…\\Class" --covered-only --no-cache
# then set pass3_mutation_clean on that row and re-publish the issue comment
```

**The pass 2 floor is the repository's own, and the scripts carry no default for it.** `--pass3` in the CSV builder and every run of the publisher need `--pass2-min=<N>` and exit 1 naming it when it is missing; `--pass1`, `--pass2`, `--count` and `--skeleton-only` do not read it. A repository derives its value from its own finished pass 2 column: take the 25th percentile of the non-empty `pass2_line_pct` cells once pass 2 is done, record it on the coverage ticket, and pass that number every time. `uams-statamic` recorded `86.7` this way on `UAMS-Web/uams-statamic#255`; that is its value, not a starting point for another repository, whose bar would otherwise be selected by someone else's distribution and printed in the tracker comment as its own.

**Pass 3 walks the snowball queue, not CSV order.** Eligible rows (`pass1=yes`, `pass2_line_pct` meeting the ticket floor (the `--pass2-min` value; `86.7` on UAMS-Web/uams-statamic#255), empty `pass3_mutation_clean`) are ranked ascending by estimated Pest mutant count via `php scripts/estimate-pass3-mutants.php` (writes `storage/app/pass3-snowball.json`; Pest `MutationGenerator` + default mutator set: a wall-time proxy for `--covered-only`). Pick the head with `node scripts/pass3-next.mjs`. Regenerate when the eligible set changes. When `est_mutants >= 200`, launch with `run-detached-mutate.mjs --parallel=6` (mutant workers inside one job).

**After the snowball pass, confirm every `n/a (0 estimated mutants)` cell.** The estimator can under-count relative to a real `--covered-only` run (and the reverse: `est > 0` with only uncovered const-array items). Revisit each such row with a narrow `vendor/bin/pest … --mutate --covered-only` (or without `--covered-only` when diagnosing) and rewrite the cell to either `narrow yes; full pending`, `n/a (0 covered mutants)`, or `n/a (0 covered; N uncovered …)` with the measured counts; do not leave estimator-only wording as the closeout record.

**Pass 3 is single-flight on a machine.** Run **one** `--mutate` at a time in that worktree (and do not overlap it with a second mutate job or another heavy coverage/mutate job that shares the same tree). A second mutate in the same slot is what orphans mutant children and fabricates or aborts scores, measured on the UAMS-Web/uams-statamic#255 pass-3 wave when a competing parallel harden run killed an in-flight serial Currency mutate. Pest mutate `--parallel` *inside* that one job is allowed and does not violate single-flight.

While that mutate runs, **prep the next snowball head** (read the source, locate or draft its covering suite, note likely equivalent-mutant annotations) but **do not start its narrow mutate yet**. When the current class is mutation-clean, or every survivor is explained, **commit and push that class**, update the CSV / tracker comment, **then** kick the next class's narrow mutate. Safe overlap: docs-only commits, tracker prose, reading the next file, or a disjoint ticket in another free slot that does not share Pest/mutate load with this tree.

**Arm a wake monitor before leaving a long mutate, and keep the status file honest.** Detached runs finish without a human in the loop; an agent that polls once and ends the turn goes idle for the rest of the wall clock (measured on UAMS-Web/uams-statamic#255 Gps: score ready ~90 minutes before the next nudge). Prefer cross-platform Node helpers that heartbeat the status file while the child is alive and always finalize `exit=` (in `uams-statamic`: `node scripts/run-detached-mutate.mjs` + `node scripts/watch-detached-mutate.mjs`, macOS / Linux / Windows). The watcher must **repair** a stalled status when the recorded PIDs are dead, and wake for hang investigation when status+log go silent but a PID is still alive; never treat a silent status file as "keep waiting." Arm Shell `notify_on_output` on the wake token, then end the turn. Do not rely on a one-shot sleep poll or a pure time heartbeat as the only follow-up.

Default `--out` / `--clover` are under `storage/app/` when that directory exists, else the repository root. The issue comment is upserted via a `<!-- coverage-scope-tracker -->` marker (full inventory omitted when it exceeds GitHub's comment size; Progress + directory roll-up always publish). Triage survivors per [`pest-testing`](../pest-testing/SKILL.md); the mutation criterion is restated under [Before trusting a score](#before-trusting-a-score-the-negative-control) and in [`adversarial-review`](../../rules/adversarial-review.md).

## Before trusting a score: the negative control

**`0 Mutations for 0 Files created` is the visible way a mutation run can succeed without doing anything. This is the invisible one, and it is the more dangerous of the two.** Each mutant runs in a child process built from `$_SERVER['argv']`, with **argv[0] reused verbatim as the executable**; `MutationTest::hasFinished()` then reads any non-zero exit as *the test failed*, i.e. the mutant was killed. **A child that could not be launched at all exits non-zero, so it is recorded as a kill.** Every mutant is then "killed", the run prints `100.00%`, and it exits `0`. No warning, no skip, a tick per mutant. The same arithmetic turns anything that makes a child exit non-zero before it runs a test: a refused lock, a broken command line, a failed write, into a false kill.

PHP sets `argv[0]` to the script path **as typed**, so a forward-slashed relative path reaches the child unchanged, and on Windows `cmd.exe` cannot launch one. **Adding `php` in front changes nothing.** Measured on Windows 11 Pro 10.0.26200, PHP 8.4.20 via Herd, `pest-plugin-mutate` v5.0.2, one class in one tree with the mutate cache cleared between arms, in `uams-statamic` (`UAMS-Web/uams-statamic-archive#2322`):

```
vendor/bin/pest        (forward slash)   20 tested,  0 untested   100.00%   fabricated
php vendor/bin/pest    (forward slash)   20 tested,  0 untested   100.00%   fabricated
php vendor\bin\pest    (separator)      14 tested,  6 untested    70.00%   executed
composer mutate        (separator)      14 tested,  6 untested    70.00%   executed
```

Confirmed directly in `uamswp-migration-api`, isolated from Pest and from any real file: a minimal `Process(['vendor/bin/pest', ...])` fails to launch with `'vendor' is not recognized as an internal or external command`; the identical call with backslashes launches correctly. Composer's `@php` handler rewrites the separator before PHP ever sees it, which is what makes `composer mutate` and `composer exec -- pest …` the safe forms. **Never invoke `vendor/bin/pest` bare, or through its `.bat`, for a `--mutate` run on Windows.** A bare invocation can report `100.00%` in a wall-clock time that reads as plausible (each failed launch costs a couple of seconds under `cmd.exe`, so a whole file's worth of failures still takes a convincing minute or two) with every mutant marked killed and none of them actually run.

**A Unit suite whose bootstrap loads Patchwork loses every child that scans a directory.** The `wordpress-plugin` template's `tests/bootstrap.php.dist` requires `vendor/antecedent/patchwork/Patchwork.php` for Brain Monkey. Patchwork and the plugin's mutation stream wrapper both replace `file://`, and a directory read through the pair fails with `readdir(): Argument #1 ($dir_handle) must be of type resource or null, true given` in `Patchwork\CodeManipulation\Stream`. A child that collects tests by scanning `tests/Unit` dies before any test, exits non-zero, and is scored as a kill, so a run with no test file named can report 100.00% on a suite that kills nothing. Naming the test file avoids the scan: `pest tests/Unit/SettingsTest.php --mutate --covered-only --path=<source file>`. The check above runs an identical-copy child through the repository's own invocation (add `--arg=<test file>` to match a run that names one) and exits `1`, naming the cause, when it dies; its control is the same child without the mutation variables, which must list tests. Measured on Windows 11 with Pest 5.3.0, pest-plugin-mutate 5.0.2 and Patchwork 2.2.3 in a one-class scratch project (default invocation 100.00% against 33.33% with the file named, on a test file weakened to leave two mutants alive); the original report was on macOS (UAMS-Web/uams-claude-skills#131). Whether a Feature suite is affected is not measured: it does not load Patchwork in the template.

**A child that launches can still run nothing, and on Windows it did.** Further plugin defects produce false kills even once the child starts:

- **The filter is split at the pipe.** The child's filter is passed as `'--filter="'.implode('|', $filters).'"'`. Through the `vendor\bin\pest` batch launcher, `cmd.exe` reads the bare `|` as a pipe whenever two or more tests cover a mutant; the child exits 255 having run nothing, with `'<TestName>\""' is not recognized as an internal or external command`. In one `Auth.php` pass in `uamswp-migration-api`, 45 of 80 mutants took this route.
- **Every locked write fails.** In a mutant child the plugin's `file` stream wrapper takes `LOCK_SH` for every lock request. On Windows a shared lock denies writes through its own handle, so the first `LOCK_EX` cache write fails with `Permission denied`.
- **A long filter aborts the run.** Symfony moves the quoted argument into an environment variable, and past about 25 KB the environment block exceeds 32,767 units (`The environment block size (36843) exceeds the Windows limit`).
- **A child can also be too long to start.** Every Windows child runs through `cmd.exe`, which accepts at most 8,191 characters, and an unquoted `--filter` names every covering test. A heavily covered mutant can pass that limit; its child exits 1 without running a test, and the plugin scores that as a kill. Measured in `uamswp-migration-api` with a plugin-shaped child: at 8,008 characters it ran 15 tests, at 8,400 it printed nothing; with each child's exit and output logged, every child at 7,383 characters or less ran tests and every one at 8,108 or more printed nothing.

**Read a `timeout` like a survivor, not a kill.** The score counts a timeout as caught. The per-mutant budget is the parent's in-process suite time plus `max(5s, 20%)`, while each child also pays a process boot and routes every file operation through the plugin's PHP-level stream wrapper. So a survivor whose mutant merely makes the code slower can land just past the budget and report as a timeout. Measured in `uams-statamic` on Windows 11 Pro 10.0.26200 / PHP 8.4.20, on `UnpublishExpiredClassifieds` (`UAMS-Web/uams-statamic-archive#2550`): the line 49 mutant, which drops `where('collection', 'classifieds')`, passed every test, took 34.1s in its child with the timeout lifted against a 29s budget, and reported `timeout` in four runs and `untested` in one. Before trusting a timeout, apply that mutant by hand and run its tests.

**Then read the score itself, and the criterion is DISAGREEMENT rather than speed.** A run reporting **zero survivors on a class nobody has hardened is a null result, not a clean one**: a run that executes nothing cannot disagree with itself, so **surviving mutants are positive evidence of execution** in a way that duration is not. Per-mutant wall time is a weaker secondary signal only; fast has innocent explanations, which is why a stale result cache had to be excluded separately (`--no-cache`, and the cache lives at `vendor/pestphp/pest-plugin-mutate/.temp/pest-mutate-cache`, inside `vendor/` and invisible to `git status`) before speed meant anything.

**And a clean check is not a clean instrument.** A child that starts rules out the wholesale failure; it does not make every verdict honest. A genuine run can still misreport a **single** mutant as killed, and a false kill leaves **no survivor to explain**, so the *no unexplained survivors* discipline cannot see it, every mutation discipline here audits survivors and none audits kills. That was measured in `uamswp-migration-api` (`UAMS-Web/uamswp-migration-api-archive#179`, tracked in `uams-statamic` as `UAMS-Web/uams-statamic-archive#2478`): one `RemoveStringCast` mutant kept reporting killed across four invocation forms and `composer mutate`, with real per-mutant timing and 37 other mutants resolving correctly.

### The positive control: make the instrument show a survivor

Run this whenever you have reason to distrust a result, not only when reviewing a stated survivor. Pick a mutant with exactly one covering test. Temporarily add `->skip(true, '<reason>')` to that test, and confirm the skip took with a plain (non-mutate) run showing it skipped rather than run. Re-run `--mutate --covered-only --path=<file>` scoped to that file; the mutant must now show as a survivor (`UNTESTED`), not an unchanged or still-passing score. Revert the test file from a copy made before editing it, never with the stash (see [`adversarial-review`](../../rules/adversarial-review.md)'s revert discipline. Treat any other outcome as a reason to distrust the whole run, not just that one mutant.

**Both defects live in the plugin, so a `vendor/` is patched or it is not, and `composer install` does not re-patch an already-installed package.** A checkout whose `vendor/` predates a patch reports `Nothing to install` and stays unpatched while reporting the same plugin version. Where a repository guards its patches with a test, that test going red is the signal; otherwise re-run the check below.

```bash
php .claude/skills/pcov-setup/scripts/check-mutation-instrument.php     # 0 = a mutant child launches and reaches a test, 1 = scores would be fabricated, 2 = nothing measured
```

## Mutation cannot reach a class that is already loaded

This is the sharpest trap here, because it produces a **0% score that looks like a damning verdict on the tests** and is nothing of the kind.

A mutant only takes effect if the file is loaded *after* the mutation runner is in place. A class the test bootstrap resolves, anything a plugin's `boot()` or an application's service provider calls directly before any test runs, is already in memory, and its mutants never apply. A class referenced as a callable array or instantiated inside a hook or request loads later, and can be mutated.

Confirm it on any repository with one command: the classes it lists are the ones mutation cannot touch:

```bash
php -r '$_SERVER["argv"] = []; ob_start(); require "tests/bootstrap.php"; ob_end_clean();
  print_r(array_values(array_filter(get_declared_classes(),
    fn ($c) => str_starts_with($c, "<YourNamespace>\\"))));'
```

A `composer mutate` script should name the files that *can* be mutated rather than point at the whole source directory, so a run never reports a meaningless 0% on the ones that cannot.

## Never run a mutation pass alongside another suite run

The Feature suite and a mutation pass share one WordPress test database and collide; the result is a spray of `siteid_exists` and missing-fixture failures that read exactly like real regressions. A mutation run in the background plus an ordinary `composer test` in the foreground is enough to do it by accident.

## Triaging survivors

Read them; don't chase a number blindly. How to read a run that did execute: the three dispositions, the four survivor buckets, the annotation placement that actually suppresses a mutant, the docblock that records accepted equivalents, and naming the platform beside the score, is in [`pest-testing`](../pest-testing/SKILL.md#mutation-testing), and is not restated here.

## The DRY line

This skill is the home for **coverage-driver setup, the mutation-run invocation and its instrument checks**: the ways a run reports a result without having produced one. The [`adversarial-review`](../../rules/adversarial-review.md) rule states the *discipline* (mutation-verify fixed bugs, close surviving mutants) and points here for the driver. How to read survivors and record the judgment lives in [`pest-testing`](../pest-testing/SKILL.md); the migration loop that runs mutation per converted test is [`phpunit-to-pest`](../phpunit-to-pest/SKILL.md). Don't restate the install steps in any of them.
