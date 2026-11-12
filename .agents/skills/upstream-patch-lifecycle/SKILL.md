---
name: upstream-patch-lifecycle
description: >-
  The lifecycle a local `cweagans/composer-patches` vendor patch moves through: write it, report
  it upstream, offer the fix, then retire it once a fixed release ships, and the four-ticket
  GitHub chain that tracks it. Covers the two registration forms (`extra.patches` in
  `composer.json` for an application; a root-only `patches.json` for a package another repository
  installs, so dev patches never leak into the consumer), the mirrored-patch case where the chain
  lives in another repository, the shared patch package that replaces copies once two
  repositories carry the same patch (layout, versioning, the `vcs` entry it is read through,
  `require` in an application and `require-dev` plus a root `patches.json` in a package,
  migration, `patches.lock.json`, retirement), the `afk` mode of each ticket and the cross-repo REST recipe for
  making an *upstream* issue a blocker on our removal ticket, the removal procedure in its
  load-bearing dependency order (the patch comes out BEFORE the package moves, or
  `composer install` fails for everyone), when `patches.lock.json` alone is enough and when
  `composer.lock` must be relocked too, the tags-not-Releases release check, and the paid-for
  traps (a reused `vendor/` needing the package reinstalled before re-patching, a stale `vendor/`
  faking `outdated` and `audit`, CRLF/LF vendor line endings, `internalClass` PHPStan pins for
  `@internal` vendor classes). Activate when carrying a NEW vendor patch, filing or wiring the
  ticket chain for one, checking whether an upstream fix has shipped, retiring a patch, or editing
  `patches/`, `extra.patches`, `patches.json`, `patches.lock.json`, or an upstream watch row. Also
  covers the upstream-REQUEST variant, where no local patch exists: activate when an `upstream`
  ticket's last open criterion waits on a maintainer's answer to a feature request, a
  documentation request, or a pull request.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/upstream-patch-lifecycle/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
<!-- cspell:ignore lockfiles Packagist retargeted -->

# Carry and retire a vendor Composer patch

A local Composer patch is a debt with a defined payoff schedule. This skill is that schedule: the
four tickets that track it, and the repo-side mechanics of each installment. Every step below has
been paid for once, in `uams-statamic`'s `pest-plugin-mutate` run (UAMS-Web/uams-statamic-archive#1728 → UAMS-Web/uams-statamic-archive#1729 →
[`pestphp/pest-plugin-mutate#38`](https://github.com/pestphp/pest-plugin-mutate/pull/38) → UAMS-Web/uams-statamic-archive#1898),
and each was re-derived from scratch along the way. Follow the checklist instead of re-deriving
it.

**The one-line shape.** Patch locally so we are not blocked → report upstream with the tested
fix in hand → offer that fix as a PR → drop the patch when a release carries it.

**Why patch at all.** Waiting on a maintainer blocks us; patching does not. But a patch resolves
nothing; it needs maintaining, it diverges from upstream every release, and its documentation
keeps describing a problem for as long as we carry it. The only thing that retires a patch is an
upstream fix, and **nothing starts that clock unless we file**.

---

## Where a patch is registered: two forms, and the repository's shape decides

`cweagans/composer-patches` reads patches from either `extra.patches` in `composer.json` or a
separate root-only patches file (`patches.json` is the plugin's default `patches-file`, so no key
is needed to use it). Which one a repository uses is not a style choice:

- **An application, or a plugin nobody installs as a Composer dependency, registers in
  `extra.patches`.** Everything is in one manifest, and `composer validate` guards the lock.
- **A package that another repository installs registers in a root-only `patches.json`, never in
  `extra.patches`** (the one exception is a shared patch package, whose `extra.patches` is its
  purpose: see *Shared patches* below). Composer copies the whole `extra` block verbatim into a consumer's
  `composer.lock`, and the plugin's `Dependencies` resolver reads `extra.patches` out of that lock
  data for **every** package in `packages`, so an inline declaration would leak the package's
  *dev* patches into its consumer and apply them against the consumer's vendor tree. Reading a
  dependency's patches **file** is an unimplemented TODO in that resolver, so the separate-file
  form stays correctly root-only.

The two forms differ in one mechanic: how many lock files a change touches, which *The relock*
below spells out. Everything else in this skill applies to both.

---

## The four-ticket chain

File all four **up front, as a chain**, when the decision to carry a patch is made. Each links
to the next by GitHub's native `blocked_by` dependency, so the tracker itself encodes what is
waiting on what.

| # | Ticket | Mode | Blocked by |
| --- | --- | --- | --- |
| 1 | Write the Composer patch that diagnoses and fixes the bug for us | `afk` | (none) |
| 2 | File the bug upstream | `afk` (a human approves the post) | 1 |
| 3 | Open the upstream PR that closes that upstream issue | `afk` (a human approves the post) | 2 |
| 4 | Update the package and remove our patch | `afk` | 3 |

Tickets 2 and 3 are `afk`, like 1 and 4. An agent does all the preparation: checking the
upstream tracker for an existing report, drafting the issue or pull-request body, and preparing
and testing the branch. The one step it does not take alone is the post itself, because the post
appears on a third-party public tracker under a maintainer's own identity: the agent asks a human
to approve it, and posts only after that approval. That approval is a step inside the ticket, not a
reason to label the whole ticket `hitl`, which would leave the preparation waiting for a person
too (`UAMS-Web/uams-statamic-archive#2772`). There is no decision to make either, so the
[`design-decision-forks`](../rule-design-decision-forks/SKILL.md) rule does not apply to them.
Tickets 1 and 4 are ordinary in-repo builds. Ticket 4's package update often arrives as a
Dependabot pull request: then the patch retires on that pull request's branch, per the
[`update-dependencies-ticket`](../update-dependencies-ticket/SKILL.md) skill, and that pull
request closes ticket 4.

**Tickets 2 and 3 verify the draft before the approval is requested.** The
[`verify-claims-before-a-third-party-post`](../rule-verify-claims-before-a-third-party-post/SKILL.md)
rule owns the requirement: every claim in the draft is checked three ways, each by an
independent method, and the claims and the checks are recorded on the filing ticket. This chain
owns only the timing. The checks run **after the local patch from ticket 1 is merged and
installed**, so they exercise the shipped fix rather than a working copy, and **after the last
edit to the draft**. A draft written before the patch existed is re-verified at that point, not
trusted. Two things the rule's claim list names are specific to a patch chain and are easy to
leave stale in a draft written early: the revision of the upstream package the reproduction was
run against, and any "no existing report" statement, which is re-searched at that point because
the upstream tracker has moved since the draft was written.

**Why the patch comes first, not the report.** Filing after the patch means the report ships a
**tested fix** rather than a suggestion, which is more useful to the maintainer and more likely
to land. On `uams-statamic`'s `pest-plugin-mutate` run the maintainer took the PR exactly as
offered, and the release followed 84 seconds after the merge.

### The two transitions that carry the subtlety

Both are easy to miss, and missing either leaves the chain silently broken.

- **Filing the upstream issue satisfies and closes ticket 2, *and* that upstream issue must
  also be added as a blocker on ticket 4.** Closing ticket 2 otherwise disconnects the thing we
  are waiting on from the work that waits on it, and ticket 4 sits looking unblocked while
  nothing upstream has happened.
- **Opening the upstream PR satisfies and closes ticket 3.** Ticket 4 then stays blocked until
  the fix is both **merged** and **tagged**, which are separate events. A merged PR on a branch
  we cannot install retires nothing.

### The cross-repo `blocked_by` recipe

`blocked_by` resolves against a **public repo we do not own**: verified 2026-08-04 in
`uams-statamic` by adding [`pestphp/pest#1790`](https://github.com/pestphp/pest/issues/1790) as a
blocker on its #1898, where it is still readable. The endpoint takes the issue's **numeric database
`id`**, not its issue number:

```bash
# 1. Resolve the upstream issue's DB id. For pestphp/pest#1790 this is 5014035150: NOT 1790.
id=$(gh api repos/pestphp/pest/issues/1790 --jq '.id')

# 2. Add it as a blocker on OUR removal ticket.
gh api -X POST repos/<owner>/<repo>/issues/<removal-ticket>/dependencies/blocked_by -F issue_id=$id

# 3. Read it back: always confirm, the POST is easy to aim at the wrong issue.
gh api repos/<owner>/<repo>/issues/<removal-ticket>/dependencies/blocked_by \
  --jq '.[] | "\(.repository.full_name)#\(.number)"'
```

Passing the issue *number* where the id belongs either 404s or, worse, silently links whatever
unrelated issue happens to carry that database id. The endpoint's REST-only status and the
budget it protects are the [`github-api-budget`](../rule-github-api-budget/SKILL.md) rule.

### Where to file when the upstream tracker is not where you think

Check `has_issues` before drafting anything, and check **only** that field.
`pestphp/pest-plugin-mutate` has issues **disabled**, so UAMS-Web/uams-statamic-archive#1729 had to be
retargeted to Pest core mid-flight. Its `open_issues_count` is actively misleading, with issues
off, GitHub still counts pull requests there, so the repo reads as a live tracker with a handful
of open reports (7 when #1729 checked it, 4 on a later check, never once an issue):

```bash
gh api repos/pestphp/pest-plugin-mutate --jq '{has_issues, open_issues_count}'
# {"has_issues": false, "open_issues_count": 4}   <- every one of those is a PR
```

When a plugin's issues are off, the **parent repo is the intake**: that is where its bugs are
filed in practice. Match the parent's issue form (Pest core uses `bug_report.yml`, requiring
What Happened / How to Reproduce / Pest Version / PHP Version / Operating System) and shape the
draft to it before filing. The PR still goes to the plugin repo; only the issue moves.

### Mirrored patches: don't duplicate the chain

Repositories that share a test harness get bitten by the *same* upstream bug. When a patch here
is a mirror of one another repository already carries, **the chain lives where it was first
filed**: filing a parallel four-ticket chain duplicates work and splits the upstream
conversation. This repo then needs only **ticket 4**, its removal ticket, blocked by the
originating repo's chain (or directly by the upstream issue, via the cross-repo edge above).

The cost is drift, and it is real: a mirrored patch outliving its original is a silent debt
nobody is tracking. So the standing rule: **when the originating repository retires its copy,
check the mirror in the same week.** A mirror that was re-targeted to a different version of the
package (a hunk rewritten for the pinned release) cannot be checked with a byte compare; check
its removal criterion instead.

### Shared patches: one package per patched upstream package

Once a second repository needs the same patch, the copies stop being mirrors and become drift.
On 2026-11-05 thirteen repositories carried seven `pest-plugin-mutate` patches, and two of the
seven existed in three and four different versions (`UAMS-Web/uams-claude-skills#548`). The
remedy decided there is **one shared package per patched upstream package**, for example
`uams-web/patches-pest-plugin-mutate`, which every consumer requires in place of its own copies.
A patch is then changed once and released once, and it retires everywhere in one release.

**A patch only one repository needs stays in that repository's own registration.** The package
holds what two or more consumers share. Creating the package repository and tagging and
releasing it are the operator's acts.

**A package is read from its GitHub repository through a `vcs` entry, not from Packagist**
(decided 2026-11-11, `UAMS-Web/uams-claude-skills#696`). The `vcs` driver resolves a package's
versions from the repository's tags, so a release is a pushed tag and nothing is registered
anywhere. The packages are public repositories, so the driver reads them without credentials. A
private package repository would need GitHub authentication on every machine and CI runner that
installs it, and this skill does not describe one.

#### The package

```text
patches-pest-plugin-mutate/
  composer.json
  patches/<basename>.patch      the patch files, unchanged in form
  README.md                     per-patch narrative: problem, fix, tracking ticket, retirement condition
```

```json
{
    "name": "uams-web/patches-pest-plugin-mutate",
    "description": "Patches UAMS-Web repositories apply to pestphp/pest-plugin-mutate",
    "type": "library",
    "require": {
        "pestphp/pest-plugin-mutate": "5.0.2"
    },
    "extra": {
        "patches": {
            "pestphp/pest-plugin-mutate": {
                "<what it fixes> (temporary; see <ticket>)": "vendor/uams-web/patches-pest-plugin-mutate/patches/<basename>.patch"
            }
        }
    }
}
```

One detail in that file is chosen and two are measured. The measurements are in
`UAMS-Web/uams-claude-skills#548`'s Findings: `cweagans/composer-patches` 2.0.0, PHP 8.4.27 and
Composer 2.11.1, on Windows 11 and on macOS 27.3, against path repositories carrying one patch.
Resolution through a `vcs` entry, with eight patches in one package consumer, is measured on a real
consumer under *Moving a consumer to the package*. Several patch packages in one consumer, the
application form through a `vcs` entry, and an application whose plugin sits in `require-dev` are
not yet measured.

- **The upstream requirement is an exact version (chosen).** The patches are written against one
  release, so the pin is meant to make a version mismatch fail at resolution, by Composer's own
  rules, rather than at patch time. A mismatch was not part of the measurement.
- **The patch path is relative to the consumer's root**, not to the package: the plugin resolves
  a dependency's path against the root project, so it must start
  `vendor/uams-web/patches-<package>/`. A consumer with a non-default `vendor-dir` is not covered
  by that path.
- **Patches are declared in `extra.patches`, not a `patches.json`.** The plugin reads a
  dependency's `extra.patches` from the consumer's `composer.lock`, and reading a dependency's
  patches file is unimplemented. This is the leak *Where a patch is registered* warns about, used
  deliberately: here the dependency's patches are the point.

**The listed order is the order the plugin collects the patches in** (2.0.0, source read; in the
eight-patch install measured under *Moving a consumer to the package*, the patches applied in
`patches.json`'s listed order). Where a later patch touches lines an earlier one
edits, it is cut against the earlier one's output, so a patch is never reordered without re-cutting
the ones after it. Verify a set by applying it in that order to the pinned upstream tag with
`git apply`, and show that applying one a second time fails.

#### Versioning

The package carries its own semantic version, and each release pins exactly one upstream version:

| Change | Release |
| --- | --- |
| A patch's content changes, same upstream pin | patch (`1.0.1`) |
| A patch is added or retired, or the pin moves within the upstream major | minor (`1.1.0`) |
| The pin moves to a new upstream major | major (`2.0.0`) |

A consumer requires the package with a caret range (`^1.0`) and updates it with
`composer update uams-web/patches-<package> --with-dependencies`, so the upstream package can move
with it. A targeted update without `--with-dependencies` holds the upstream package at its locked
version, and Composer then stays on the newest package release pinned to that version without
saying so. Where the consumer's other constraints hold the upstream package back, the same
selection keeps it on an older package release.

#### How a consumer requires it: the repository's shape decides, as for its own patches

The same split as *Where a patch is registered*, for the same reason (decided on
`UAMS-Web/uams-claude-skills#548`, option B, and measured there):

- **An application requires the package in `require`.** The plugin applies the package's own
  `extra.patches`, and only from `packages` in `composer.lock`: in `require-dev` the package is
  installed and silently not applied.
- **A package that another repository installs requires it in `require-dev`, and lists the
  package's patch files in its own root `patches.json` by the same `vendor/` path.** Its root
  `patches.json` applies when it is the root project; downstream, neither the patch package nor
  the patches are installed, so a dev tool's patches never reach the application's production
  install. The package's own declaration is skipped in that consumer, because it sits in
  `packages-dev`, so nothing applies twice.

**Either form needs the `vcs` entry in the consumer's root `composer.json`**, an application's and
a package's alike, because the package is not on Packagist. Composer reads `repositories` only from
the root project, so an entry in a dependency's `composer.json` is ignored, and a package
consumer's own consumers do not inherit its entry. They need none: the patch package sits in that
package's `require-dev` and never reaches them. Add the entry with
`composer config repositories.patches-<package> vcs https://github.com/UAMS-Web/patches-<package>.git`,
which (Composer 2.11.1) writes:

```json
{
    "repositories": [
        {
            "name": "patches-pest-plugin-mutate",
            "type": "vcs",
            "url": "https://github.com/UAMS-Web/patches-pest-plugin-mutate.git"
        }
    ]
}
```

Where `repositories` is already present,
add the entry beside the others.

The cost of the package form is a list kept in step: when a release adds or retires a patch, a
package consumer edits its `patches.json` entries in the change that adopts the release, keeping
the package's order and description keys. The patch files themselves stay in one place.

#### Moving a consumer to the package

1. **Take out the copies the package now ships**: their entries in `extra.patches` or
   `patches.json`, and their files under `patches/`. A patch only this repository carries stays.
2. **Add the `vcs` entry before the require** (*How a consumer requires it*), or Composer looks for
   the package on Packagist:
   `composer config repositories.patches-pest-plugin-mutate vcs https://github.com/UAMS-Web/patches-pest-plugin-mutate.git`.
3. **Require the package with plugins off**, so the first resolution cannot run before the files
   exist: `composer require --no-plugins uams-web/patches-pest-plugin-mutate:^1.0` in an
   application, with `--dev` added in a package.
4. **In a package, add the package's patches to the root `patches.json`**, by
   `vendor/uams-web/patches-<package>/patches/<basename>.patch`, in the package's order, with its
   description keys.
5. **`composer patches-relock`, then `composer patches-repatch`**, and `composer update --lock`
   where `composer.json` changed (*The relock* below).
6. **Place a patch this repository keeps on the same upstream package, and prove it applies
   there.** In a package, list it after the shared entries in `patches.json`, and it applies after
   them: prove it with `git apply` of the set in order, then this one, on the pinned upstream tag,
   and re-cut it against the set if it does not apply. In an application, the plugin collects the
   root `extra.patches` before a dependency's (resolver order in 2.0.0, source read), so a kept
   patch applies *before* the shared set: cut it against the bare pinned tag, and prove the set
   still applies after it, or move it into the package. One real install has run a kept patch
   *ahead* of the shared set in a package (the run below): it applied cleanly first and the set
   applied after it. The after order in a package and the application order are not measured in a
   real install (`UAMS-Web/uams-claude-skills#548`, Findings).
7. **Confirm each patch applied** (its description appears in the `composer install -v` output;
   without `-v` the install prints only `Patching <package>`, and with a warm patch cache one
   `Found cached patch` line per patch) and
   run the gate that proves the patched tool works (*Proving it worked*). Commit `composer.json`,
   `composer.lock`, `patches.json` where it changed, and `patches.lock.json` together.

**Steps 2, 3 and 5 were run in this order on one real package consumer**, 2026-11-11 on macOS 27.3,
PHP 8.4.27, Composer 2.11.1, `cweagans/composer-patches` 2.0.0, against
`uams-web/patches-pest-plugin-mutate` v1.0.0. The consumer was a scratch clone of
`UAMS-Web/aether-wordpress-importer-cleanup` at `436ca68`, with the package taken out of
`require-dev` by hand to stand in for one not yet moved. Steps 1 and 4 were already in place
there: its `patches.json` listed the seven shared patches by `vendor/` path, after its own one
patch (`pest-class-constant-coverage.patch`), which therefore applied first:

| Step | Result |
| --- | --- |
| `composer config repositories.patches-pest-plugin-mutate vcs <url>` | exit 0, the entry above |
| `composer require --dev --no-plugins -vvv uams-web/patches-pest-plugin-mutate:^1.0` | exit 0; `Locking uams-web/patches-pest-plugin-mutate (v1.0.0) from vcs repo`, and `Reading composer.json of uams-web/patches-pest-plugin-mutate` printed 2 times |
| `composer patches-relock`, `composer patches-repatch` | exit 0 each; `patches.lock.json` lists 8 patches, each `patches-file:patches.json` |
| `vendor/` moved aside, `composer install` | exit 0, all 8 patches applied from a warm patch cache |
| `vendor/` moved aside again, `composer install -v` | exit 0, the 8 descriptions printed in `patches.json`'s order |

The `Reading composer.json of uams-web/patches-<package>` line is what tells the `vcs` driver from
Packagist, and only while Composer resolves at `-vvv` (a `require`, an `update`, or an install
with no `composer.lock`). A `composer install` from a lock prints it 0 times either way: both
installs in the table did. The control for a resolution is recorded on
`UAMS-Web/aether-wordpress-importer-cleanup#71`, which adds the same entry: `composer install -vvv`
with `composer.lock` removed prints the line 2 times on its branch, and 0 times from `main`, which
has no entry and resolves from Packagist.
The application form, and a cold patch cache through the `vcs` entry, are not yet measured. Where
`require` misbehaves, the fallback is the measurement's original bootstrap, scoped to the package
(`composer update uams-web/patches-<package> --no-plugins`).

#### `patches.lock.json` in a consumer

The consumer's `patches.lock.json` records each shared patch with its resolved `url` and its
`sha256`, and a `provenance` of `dependency:<upstream package>` in an application or
`patches-file:patches.json` in a package. It **must be committed**: the first resolution runs
before the package is installed, so with no committed `patches.lock.json` a plain
`composer update` aborts with `The "vendor/..." file could not be downloaded`, which is why the
migration steps above require with plugins off and then relock and repatch. Once both lock files
are committed, a `composer install` from a deleted `vendor/` patches cleanly, with a warm or a cold
patch cache.

**A committed `patches.lock.json` is read, never re-resolved**, so adopting a new package release
is not an ordinary dependency update. In 2.0.0 (source read) the plugin loads the patch list from
the lock whenever the lock exists (`loadLockedPatches()` in `src/Plugin/Patches.php`), never checks
it for freshness, and fetches each patch against its locked `sha256`. After a release that changes
a patch, a machine with the old patch cached applies the old text and one without it fails on a
hash mismatch; after a release that retires a patch, the lock still names it against the moved
upstream package. So each adoption repeats the bootstrap: in a package, edit `patches.json` to
match the release first; then
`composer update uams-web/patches-<package> --with-dependencies --no-plugins`,
`composer patches-relock`, `composer patches-repatch`, and a `composer install` from a deleted
`vendor/` to show it applies cold. **A Dependabot pull request for the package needs those steps
on its branch before it merges.** The end-to-end behavior is not yet measured.

#### Retirement

A patch retires from the package, not from each consumer. When an upstream release carries a fix,
the package releases without that patch and with its pin moved to that upstream version, in one
release, so inside the package the patch comes out in the same step the upstream package moves.
**In each consumer the order of *Removing a patch* below still applies**: the retired entry
leaves `patches.json` (in a package) and `patches.lock.json` is relocked in the same change that
moves the package and the upstream package, by the adoption steps under *`patches.lock.json` in a
consumer*. When the last patch retires, each consumer removes the package in one change, requiring
the upstream package directly only if it did before (`pest-plugin-mutate` arrives through
`pestphp/pest`). Where no other patch remains, the consumer also removes `patches.json`,
`patches.lock.json` and `cweagans/composer-patches`, as *Removing a patch* says. The package
repository is then archived.

**The four-ticket chain lives once, where the package does.** Consumers carry no removal ticket of
their own for a patch the package ships; a consumer's open item is adopting the release that
retires it.

---

## The mechanics

### Where the moving parts live

| Thing | Path |
| --- | --- |
| Patch files | `patches/<basename>.patch` |
| Registration | `extra.patches` in `composer.json`, or a root-only `patches.json` (see above |
| Patch SHA-256s and the set `_hash` | `patches.lock.json` |
| Composer's own manifest hash | `composer.lock` → `content-hash` |
| The plugin itself | `cweagans/composer-patches ^2.0` in `require-dev` + `allow-plugins` |
| Per-patch narrative + removal checklist | `patches/README.md` where the repository has one; otherwise the entry's description key and the decision log (annotated on retirement, never pruned; removal step 12 expects that hit) |
| PHPStan pins for vendor-guard tests | `internalClass` `ignoreErrors` in `phpstan.neon.dist` |

### Adding a patch

Every patch here is **hand-written**: edit the `.patch` directly. There is no snippet pair and no
generator command.

Then, in every case:

1. Register the path, under `extra.patches.<vendor/package>` in `composer.json`, or under
   `patches.<vendor/package>` in `patches.json`. **The key is the description**, and it must name
   the temporariness and the tracking ticket, matching the existing entries:
   `"… (temporary; see #1814)"`, and, where there is no `patches/README.md` to hold it, the
   removal criterion too: `"… (temporary; drop once <upstream> ships a fix; tracked in #N)"`.
2. `composer patches-relock --no-interaction`
3. `composer update --lock --no-interaction` **if `composer.json` changed**: see *The relock*
   below. With `extra.patches` it always did; with `patches.json` only adding the plugin itself
   changes it.
4. `composer validate --no-check-publish`: expect no `# Lock file errors` section. ("No license
   specified" is a pre-existing general warning in some repositories.)
5. `composer install -v` and confirm the patch applies: the patch's description appears in the
   output (without `-v` it does not).
6. Document it: the `patches/README.md` section where the repository has one (problem, local fix,
   **the retirement condition**, and a removal checklist naming every file that dies with the
   patch: the guard test, its PHPStan pins, any generator command and snippet pair); otherwise the
   description key, the `_comment` array in `patches.json`, or the decision log.
7. Name the gate that proves the patched tool works (see *Proving it worked*.
8. `vendor/bin/pint --dirty`.

### The relock, and when it is two lockfiles

`composer patches-relock` rewrites **`patches.lock.json`** (the per-patch SHA-256s and the set
`_hash`). It does **not** touch **`composer.lock`**.

Composer folds the whole `extra` block into `composer.lock`'s `content-hash`. So **with
`extra.patches`, adding, editing, or removing an entry leaves that hash stale: the removal
direction is exactly as much of a change as the addition**: and every patch change is a two-step
relock: `composer patches-relock`, then `composer update --lock --no-interaction`, which recomputes
the hash and moves no dependency.

**With a separate `patches.json`, the relock is usually one step**: Composer never hashes that
file, so editing it leaves `composer.lock` correct. `composer update --lock` is needed only when
`composer.json` itself changes, most often when the last patch retires and
`cweagans/composer-patches` comes out of `require-dev`.

Skip the second step when it is due and the symptom is every `composer install` on every machine
warning *"The lock file is not up to date with the latest changes in composer.json"* forever. The
same applies to any other `composer.json` key feeding the hash: `name`, `require*`, `autoload*`,
`config`, `extra`, not just patches. Where the repository's CI or local runner has a
`composer validate` job, that job is the gate for this; where it does not,
`composer validate --no-check-publish` is a manual step, so run it.

### Checking whether the fix shipped

**Do not consult the Releases feed.** `pest-plugin-mutate` publishes **tags, not GitHub
Releases**: its newest Release is `v2.0.0-beta.5` from 2024-08-05, while `v5.0.1` shipped
2026-08-04. Watching Releases waits forever. The tag is the release, and Packagist serves it.

Three checks, in order:

```bash
# 1. Is there a tag past what we have? (Packagist is the authority on installability.)
curl -sS https://repo.packagist.org/p2/pestphp/pest-plugin-mutate.json \
  | jq -r '[.packages["pestphp/pest-plugin-mutate"][].version] | .[0:5][]'

# 2. Does that tag actually contain the fix? `identical` means the tag is at the branch tip.
gh api repos/pestphp/pest-plugin-mutate/compare/v5.0.1...5.x --jq '.status'

# 3. What do we have locked?
jq -r '[.packages[], ."packages-dev"[]] | map(select(.name == "pestphp/pest-plugin-mutate")) | .[0].version' composer.lock
```

Without `jq`, `php -r` reads the same JSON:

```bash
php -r '$l=json_decode(file_get_contents("composer.lock"),true);
  foreach(array_merge($l["packages"],$l["packages-dev"]) as $p){
    if($p["name"]==="pestphp/pest-plugin-mutate"){echo $p["version"],"\n";}}'
```

A merge is not a release, and a release on the wrong major line is not a release *for us*.
Upstream PRs `pestphp/pest#1742` and `#1757` implemented a parallel-merge fix `uams-statamic`
once carried as a patch, but both were based on `4.x`, so neither could reach the 5.x line that
repository runs even if merged. The patch stayed until `pestphp/pest` `v5.2.0` shipped an
equivalent fix on 5.x (`UAMS-Web/uams-statamic-archive#2414`).

**No upstream watcher runs here until the repository adopts one.** The skill ships one in
[`templates/`](templates/), and the sync never writes a target's `.github/`, so adoption is a
copy, done once:

| Copy this template | To |
| --- | --- |
| `templates/watch-upstream.yml` | `.github/workflows/watch-upstream.yml` |
| `templates/load-upstream-watches.mjs` | `scripts/load-upstream-watches.mjs` |
| `templates/check-upstream-release.mjs` | `scripts/check-upstream-release.mjs` |
| `templates/upstream-watches-README.md` | `.github/upstream-watches/README.md` |

Add a row file per upstream signal to `.github/upstream-watches/` when the chain reaches ticket 2
or 3 (the README gives the format). The sentinel is **per-signal**, so one tracker can carry both
an issue row and a PR row and each notifies once. A row naming `composer` also polls Packagist for
a release past the pin in `composer.lock`, which is the condition that retires the patch; a `pr`
row with `released: true` also comments once when the newest stable tag on the pull request's
base-branch line contains the merge commit. Closing the tracking issue turns its watch off. Each
row is its own file, so two pull requests that add rows never conflict. A directory holding only
the README is valid and the run does nothing, while a missing directory or a malformed row fails
the run, so a wrong path never reads as nothing to watch. To tag a person on a notification, set
the repository variable `UPSTREAM_WATCH_NOTIFY` (for example `@example-user`).

Dependabot stays the second hook: it opens a pull request when the patched package has a new
release, and a bump of the patched package is exactly what obsoletes a patch. The
[`update-dependencies-ticket`](../update-dependencies-ticket/SKILL.md) skill re-confirms the patch
stack on every Dependabot pull request that changes `composer.lock`, for every patched package; check it there.

### Removing a patch: the order is load-bearing

**The patch comes out before the package moves.** An upstream fix usually rewrites the same
hunk the patch does, so update the package first and the patch no longer applies, and a patch
that fails to apply fails `composer install` **for everyone, including a fresh CI checkout**.
Nothing in the tooling hints at the ordering; you find out by breaking the build. Whether or not
the repository sets `composer-exit-on-patch-failure`, treat a failed apply as fatal and keep the
ordering: a patch that silently fails to apply while still registered is a green run validating
unpatched code.

1. Delete `patches/<basename>.patch`.
2. Drop the entry from the registration (`extra.patches` or `patches.json`). Remove the whole
   package key only if this was its last patch. If it was the last patch **overall** and nothing
   else needs the plugin, also drop `patches.json` (where used), `patches.lock.json`, and
   `cweagans/composer-patches` from `require-dev`, that last step touches `composer.json`.
3. `composer patches-relock --no-interaction`
4. `composer update --lock --no-interaction`; always with `extra.patches`; with `patches.json`
   only if step 2 touched `composer.json`.
5. **Only now:** `composer update <vendor/package>`. Targeted, not a bare `composer update`:
   every other package moves through its own Dependabot pull request. A transitive package
   (`pest-plugin-mutate` is pulled in by `pestphp/pest`, not listed in `require-dev`) is still
   targeted by name. **When the update is a Dependabot pull request**, the package has already
   moved on its branch and that branch is unmerged, so nothing reaches `main` out of order: do
   steps 1 to 4 and 6 to 12 on that branch, and this step is the bump it already carries (the
   [`update-dependencies-ticket`](../update-dependencies-ticket/SKILL.md) skill).
6. Delete the patch's own **guard test**: the one asserting the patch is present in `vendor/`,
   along with the `internalClass` `ignoreErrors` entries in `phpstan.neon.dist` pinned to its
   path. **Do not leave it behind:** a guard goes red the moment the patch retires, which is the
   property that makes it useful and the reason it cannot outlive the patch. A shared
   *regression* gate is a different thing and stays, that is what step 11 runs to prove the stock
   package still works.
7. Remove the patch's narrative: its `patches/README.md` section, or its `_comment` entry.
8. Retire any upstream watch row and its note, where the repository has a watcher.
9. `composer validate --no-check-publish` exits 0.
10. Cold verify: `rm -rf vendor && composer install` exits 0 and every **remaining** patch still
    applies.
11. Run the gate that exercises the now-unpatched tool, and **see it green**: see *Proving it
    worked* below. Assuming it is fine is how a silent regression ships.
12. Nothing outside the historical record still names the patch. A retirement annotates the
    repository's decision log (`docs/DECISIONS.md` where it has one) rather than deleting the
    entry, because a decision log records what was decided and deleting an entry falsifies it;
    that annotated entry is the one place the basename should survive, and step 7 has already
    removed the narrative everywhere else. Check both halves, so the sweep is shown to tell the
    two apart:

    ```bash
    git grep -n <basename> -- . ':(exclude)docs/DECISIONS.md'   # must print nothing
    git grep -n <basename> -- docs/DECISIONS.md                 # must still find the annotated entry
    ```

    The first is the criterion. The second is its control: if it also prints nothing, either the
    decision log was pruned, which this step forbids, or the basename is spelled differently from
    what was searched, and the first command's silence proves nothing. In a repository with no
    decision log, the first command without its `:(exclude)` argument is the whole check.

A patch can also retire without any of this, by silently ceasing to apply because upstream fixed
it on their own. Drop it the same way and record *why*, in the narrative where the repository
has one, otherwise in the commit.

### Proving it worked

Name the gate that exercises the patched tool, and run it, before and after.

- **When a CI job already exercises it, that job is the regression check.** For a
  `pest-plugin-mutate` patch that is a mutation run asserting a **non-zero mutation count *and* a
  100% score**, because Pest exits `0` on `0 Mutations for 0 Files created`: an exit code alone
  passes straight through the exact false green such a patch exists to prevent. Read the summary,
  don't trust `$?`. No new test is needed, but it must be **run and seen green**, not assumed.
- **When no such gate exists, write a vendor-assertion test** that goes red the moment the patch
  retires.
- **When the patch is about a bound actually firing, assert behavior, not source.** A source match
  proves a line is present, not that a timeout fires.
- **Run a negative control.** Revert the fix in `vendor/` by hand and confirm the gate goes red
  for the *right reason*, then restore and confirm green. **Copy the file aside first:
  `cp vendor/.../File.php "$SCRATCH/File.php"`; never `git stash`**, which is shared across every
  worktree ([`worktrees`](../rule-worktrees/SKILL.md)). Verify the restore with `cmp -s`. Per the
  [`adversarial-review`](../rule-adversarial-review/SKILL.md) rule, a green result that would have
  been green either way proves nothing.

The mutation run needs a coverage driver.

### The traps already paid for

- **A reused `vendor/` does not re-apply a changed patch.** On a warm tree `composer install`
  reports "Nothing to install, update or remove", so no package-install event fires and
  `cweagans/composer-patches` never applies a newly added or edited patch. The run then
  validates the **previous** patch set and reports green. Fix it by removing the patched
  packages and reinstalling: `rm -rf vendor/<vendor>/<package>` then `composer install`.
  **Not `composer patches-repatch`**: it runs its inner install through
  `$application->run()` and then `return 0` unconditionally, discarding that call's exit code
  (`cweagans/composer-patches` 2.0.0, `RepatchCommand.php:57-62`), so a patch that fails to
  apply still exits 0. Remote CI installs cold, so it re-patches every time; **your warm local
  tree is the one that lies**, unless the local runner has its own safety net.
- **`patch --dry-run` without `-N` cannot say whether a patch is applied, so its exit status
  means nothing here.** Used to decide "is this patch already in `vendor/`?", the bare probe
  answers on a platform-specific prompt rather than on the file. Measured on macOS
  (Darwin 25.6.0, the BSD `patch`) against `vendor/statamic/cms`, both arms run unflagged:
  `patch -p1 --dry-run` and `patch -p1 -R --dry-run` each exit `0`, and the forward arm prints
  `Reversed (or previously applied) patch detected! Assume -R? [y]` and still exits `0`. Across
  21 patches the unflagged probe reported both-arms-succeed on 20 and both-arms-fail on 1, so it
  never separated an applied patch from an unpatched one. With `-N` (`--forward`) the forward arm
  separates them:

  | tree | `patch -p1 -N --dry-run` | output |
  | --- | --- | --- |
  | patch applied | exit `1` | `Ignoring previously applied (or reversed) patch.` |
  | unpatched upstream (v6.37.0) | exit `0` | `patching file ...` |

  Measured on Windows 11 under Git Bash (GNU patch 2.7.6), against a one-hunk toy file rather
  than `vendor/`, with stdin at EOF and `-i <patch>`: on an applied tree the unflagged forward
  probe exits `1` after printing `Assume -R? [n]` and `Apply anyway? [n]` (the prompt defaults to
  no, where the macOS prompt defaults to yes), and `-N` exits `1` with `Skipping patch`; on an
  unpatched tree both exit `0`. So the two platforms disagree about the unflagged probe, which is
  the reason to rule it out rather than pick whichever reading the local machine gives. With
  stdin on a terminal the unflagged probe also waits at the prompt. **Always pass `-N`, and read
  both arms**: `-N` forward must exit `1` on an applied tree and `0` on an unpatched one, which
  is a two-sided result no single exit status can stand in for. Running only the arm that
  confirms the expected state is the shape [`an-empty-result-is-not-evidence`](../rule-an-empty-result-is-not-evidence/SKILL.md)
  rules out. The `-R --dry-run` arm is not a substitute: on macOS it exits `0` on both trees,
  and on the Windows toy file it exits `0` on an applied tree and `1` on an unpatched one only
  because the prompt defaults to no (`Ignore -R? [n]`).

  **To evidence that a patch is applied, probe for its added line instead of its exit status.**
  Take a line the patch adds (a `+` line in the hunk) and search the patched file for it with a
  fixed-string match (`grep -F`), then run the same search against a file known to be unpatched
  as the control. **The first added line is not always unique to the patch:**
  `patches/statamic-updates-overview-badge.patch` adds a line that already exists upstream, so
  its first added line matches an unpatched file too. Choose an added line that the control
  file lacks, and confirm the control count is `0` before reading the patched count. Any
  `patch --dry-run` in a script or ticket that decides "applied or not" carries `-N` for the
  reason above, and states the platform it was measured on.
  The sibling trap above is the same shape: a status that reports on the wrapper, not the work.
- **A stale `vendor/` also fakes out `composer outdated` and `composer audit`.** Verified
  2026-08-05 in `wordpress-importer`: the primary checkout had `pestphp/pest` `4.7.4` installed
  against a lock holding `v5.0.2`, and `cweagans/composer-patches` was not installed at all. Run
  `composer install` before believing any of those three commands.
- **`composer install` writes vendor files CRLF on Windows and LF on Linux CI.** Any test
  assertion spanning a newline passes on one platform and fails on the other. Assert
  single-line substrings, and where a patch's signature is inherently multi-line, count
  occurrences instead (`substr_count(...)`) rather than matching a multi-line block.
- **Testing `@internal` vendor classes needs path-pinned `internalClass` entries in
  `phpstan.neon.dist`.** `pest-plugin-phpstan`'s `PestInternalClassAccessIgnoreExtension`
  whitelists Pest's *own* internals at their intended call sites and nothing else, so a browser
  plugin class or a PHPUnit exception still errors at `level: max`. Pin by `path:` (or an
  exact-class `message:`) so internal-class misuse anywhere else still fails, and list the pin in
  the patch's removal checklist so it dies with the test.

### API budget

Every issue operation in the chain has a REST equivalent, and the dependency endpoints above are
REST-only anyway. Use them per the [`github-api-budget`](../rule-github-api-budget/SKILL.md) rule:
`gh issue create`, `gh issue edit`, `gh issue close`, and all of `gh project *` are GraphQL, and
the project board has no REST surface at all, so a GraphQL call spent creating a chain ticket
is one the board may not have later.

---

## The upstream-request variant: nothing to retire

Not every `upstream` ticket carries a patch. A feature request, a documentation request, or a
pull request offered without a local patch behind it has no removal ticket and no release to wait
for. What such a ticket usually has left, once the item is posted, is one criterion that only a
maintainer can satisfy: "if upstream answers X, do Y". Left in place, that criterion holds the
ticket open indefinitely for an answer it cannot produce. Six were open in `uams-statamic` on
2026-09-30 on that ground alone.

**The post still carries the claim verification.** A request with no patch behind it goes to
the same third-party tracker under a maintainer's identity, and its claims cost the same when
they are wrong. The
[`verify-claims-before-a-third-party-post`](../rule-verify-claims-before-a-third-party-post/SKILL.md)
rule applies in full. There is no local patch to wait for, so the checks run immediately before
the approval is requested, against the current upstream release named in the draft; a draft
written earlier is re-verified at that point, and the claims and checks are recorded on the
filing ticket first.

**The rule (decided 2026-09-30, `UAMS-Web/uams-statamic-archive#2723`):**

1. **The filing ticket closes once the item is posted.** Its own work, drafting and filing
   the upstream item and recording the link, is done at that point.
2. **"Act on the answer" moves to a follow-up ticket.** File it per
   [`writing-issues`](../writing-issues/SKILL.md), with a native cross-repo `blocked_by` edge
   on the upstream item (the recipe is under *The cross-repo `blocked_by` recipe* above), and
   read the edge back. On the filing ticket, **strike** the moved criterion rather than
   ticking it, and close with a one-line comment naming the follow-up.

   **A pull request cannot be the blocker.** The endpoint refuses one with `Validation failed:
   Target issue may only be an issue` (HTTP 422). When the upstream item is a pull request,
   put the edge on the issue it fixes, so the merge that answers the follow-up also closes
   its blocker. Any watch still tracks the pull request, because that is where the answer
   arrives.
3. **Where the repository has an upstream watcher, its row tracks the follow-up, never the
   filing ticket.** A row tracking the filing ticket would go dead the moment step 1 closes it,
   which is the same reason the patch chain's rows track the removal ticket.

**Order, when the filing ticket is already open and the item already posted:** file the
follow-up and its edge, add any watch, and only then close the filing ticket. Closed first,
nothing would be watching the upstream item in the gap.

**When a follow-up already exists** (a parent feature the request was filed for, already
blocked by the upstream item), it *is* the follow-up: point any watch at it and close the
filing ticket.

**Rejected alternatives, so they are not re-proposed:**

- **Split without a watch.** The follow-up's edge records the dependency, but nothing surfaces
  the maintainer's answer when it arrives; the follow-up is found only by someone who goes
  looking.
- **One umbrella tracker for every pending upstream item.** It collapses independent answers
  onto one ticket, so no answer gets an owner or a close, and every watch row would point at a
  ticket that can never close.

---

## The DRY line

This skill owns the **lifecycle** and the **ticket shape**, and nothing else.

- **Per-patch detail**: what each patch does, why it exists, its retirement condition, and its
  own removal checklist, stays with the patch: `patches/README.md` where the repository has
  one, otherwise the registration's description key, `patches.json`'s `_comment`, or the decision
  log. Add a section there for a new patch; do not restate one here, and do not create a second
  home for it.
- **Issue and PR *style*:** titles, section inventory, acceptance criteria, the `afk`/`hitl`
  labels themselves, stay in the [`writing-issues`](../writing-issues/SKILL.md) and
  [`writing-pull-requests`](../writing-pull-requests/SKILL.md) skills.
- **What is verified before an upstream post, and how** stays in the
  [`verify-claims-before-a-third-party-post`](../rule-verify-claims-before-a-third-party-post/SKILL.md)
  rule; this skill keeps only when the checks run in its chain.
- **Which API surface to spend** stays in the
  [`github-api-budget`](../rule-github-api-budget/SKILL.md) rule.
- **The pre-ship review and the mutation/negative-control discipline** stay in the
  [`adversarial-review`](../rule-adversarial-review/SKILL.md) rule; the coverage driver `--mutate`
  needs is the repository's coverage-driver skill, named under *Proving it worked*.
- **Dependency updates** are Dependabot's. Re-confirming the patch stack on every Dependabot pull
  request that changes `composer.lock` is the
  [`update-dependencies-ticket`](../update-dependencies-ticket/SKILL.md) skill, that is where
  you *notice* a patch has become retirable or broken.
