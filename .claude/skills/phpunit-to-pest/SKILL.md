---
name: phpunit-to-pest
description: >-
  The deliberate, one-test-at-a-time PHPUnit-to-Pest migration pipeline. Use whenever
  converting or porting an existing class-based PHPUnit test to Pest function style, when the
  user says "migrate/convert tests to Pest", "port this test", "next test" during a migration,
  or asks to audit or strengthen a test being converted. Encodes the per-test loop: time the
  original, convert and audit (relevance/effectiveness/efficiency), time the Pest version, run
  it, Pint, the repository's static analysis at its configured level, Pest mutation testing,
  an adversarial review, then STOP for the user's review before the next test. Also covers
  Pest's `--drift` converter as the mechanical first pass. For Pest syntax defer to the
  `pest-testing` skill; this skill is the migration process on top of it.
metadata:
  type: process
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/phpunit-to-pest/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): pest.
-->
<!-- cspell:ignore analyse killable Triaging -->

# PHPUnit to Pest migration

A deliberate, **one test at a time** migration of a legacy class-based PHPUnit suite to Pest function style. Pest runs PHPUnit test classes unchanged, so converted files and not-yet-converted class files run side by side under one runner, and the migration can stop and resume at any file.

This skill is the **process**. For Pest syntax (`it`/`test`/`expect`, datasets, mocking, browser tests) defer to the [`pest-testing`](../pest-testing/SKILL.md) skill. For the skeptical pre-ship pass see [`adversarial-review`](../../rules/adversarial-review.md). For the coverage driver and the mutation-run gotchas see [`pcov-setup`](../pcov-setup/SKILL.md). For the formatter and static-analysis tooling and the level each repository runs at, see [`code-quality`](../code-quality/SKILL.md).

## The mechanical first pass: `--drift`

Pest ships a converter, `vendor/bin/pest --drift`, which rewrites class-based PHPUnit tests in the directory into Pest function style in place. It needs `pestphp/pest-plugin-drift` as a development dependency; add it where it is not present.

It is a mechanical rewrite, not step 2. Run it, then do every step of the loop below on its output as if you had converted by hand: the audit, the collapse of repetitive methods into a dataset, and the check that the `uses()` binding it emitted is the one the base class actually needs are judgment calls the converter does not make for you.

## The per-test pipeline (do every step, in order)

1. **Time the original.** Record the PHPUnit class's wall-clock: `vendor/bin/pest <file>` → note `Duration`. (Baseline for the comparison in step 3.)
2. **Convert + audit.** Rewrite as Pest function style **and** audit the test (see *Audit dimensions* below); don't do a blind 1:1 port.
3. **Time the Pest version** and compare to step 1. For pure-unit tests the framework bootstrap dominates, so expect a wash; flag any real regression.
4. **Run it:** `vendor/bin/pest <file>` (must be green).
5. **Pint:** `vendor/bin/pint <file> --format agent` (must report `passed`).
6. **Static analysis at the repository's configured level.** Run the repository's analysis script (`composer stan` where it has one) or `vendor/bin/phpstan analyse <file> --memory-limit=2G --no-progress`. Where `phpstan.neon.dist` does not list `tests` among its paths, passing the test file as a path argument analyzes it anyway. Pest idioms (`it`/`expect`/`with`) do not trip it. The level is the repository's, not this skill's (see [`code-quality`](../code-quality/SKILL.md)).
7. **Mutation test** the class(es) under test (see *Mutation testing* below). Read the survivors; triage each per [`pest-testing`](../pest-testing/SKILL.md#mutation-testing).
8. **Document the accepted mutation equivalents in the test's PHPDoc docblock.** Whenever survivors are accepted as equivalents, the file docblock **must** state which survive and why, ending on "every killable mutant is dead" (or the equivalent). This is mandatory, not optional: a reader (and the next adversarial pass) needs to know the residual score is equivalents, not an untested gap. (Omitting it was a real miss caught on test #2 of the first migration.)
9. **Adversarial review** per [`adversarial-review`](../../rules/adversarial-review.md): a skeptical pass to *break* the conversion (regression-safety vs the original, expectation correctness, effectiveness/mutation-gap). Under Ultracode this is a multi-agent `Workflow`; otherwise a focused self-review or `/code-review`.
10. **STOP for the user's review** before starting the next test, *unless* the user has opted into continuous mode for the run ("continue without stopping for review between each test"), in which case commit each test in its own focused commit and proceed straight to the next. Default is STOP; do not batch-convert on your own initiative.

## Conventions

- **Base class decides the binding.** Pure-unit tests that extend `PHPUnit\Framework\TestCase` need **no `uses()`**: Pest's default base is already `PHPUnit\Framework\TestCase`, identical to the original. Tests that extend an application test case need `uses(<ThatTestCase>::class);` at the top of the file. Do **not** blanket-bind `uses(<ThatTestCase>::class)->in('Unit')`; it would needlessly boot the framework for pure-unit tests.
- **`it()` over `test()`** to match existing Pest files.
- **Collapse repetitive methods into a dataset.** N near-identical methods (validation rules, formatter input→output, alias tables) → one `it(...)->with([...])` with **named** rows. Each row is then independently named in output and individually mutation-checked.
- **`toBe` is the strict (`===`) equivalent of `assertSame`**, type-sensitive. A dataset closure param typed `int|float|string $expected` does **not** coerce a `1.5` literal (stays float), so the strict float-vs-int-vs-string distinction the original `assertSame` guarded is preserved. Use `toBe`, not `toEqual`, when porting `assertSame`.
- **Keep the file docblock** pointing at the source under test, plus the `@command` run line where the suite's docblocks carry one (house style).
- **Never delete a test without approval.** Adding/strengthening rows is fine.

### Audit dimensions (step 2)

- **Relevance:** does each case test real, current behavior? Drop dead cases (with approval); flag tests for code that no longer exists.
- **Effectiveness:** does it test the right thing strictly enough? Mutation testing (step 7) is the objective check.
- **Efficiency:** strip boilerplate the conversion makes redundant: per-method PHPDoc that just restates the method name, narrating inline comments (`// Run the parent setup`), multi-line single-arg calls, `setUp()` that only news-up a stateless SUT (construct inline instead).

## Mutation testing

Pest's native `--mutate` (`pestphp/pest-plugin-mutate`, bundled with Pest) mutates the source and demands a test fail; a surviving (**UNTESTED**) mutant is a false-green gap. A coverage driver is required; if it is missing, or you are stuck on slow `herd coverage`/Xdebug, set it up per [`pcov-setup`](../pcov-setup/SKILL.md), which also holds every invocation gotcha: the relative `--path`, the worktree sub-path, the Windows env-block limit, and the forms that report `100.00%` without running a mutant. `0 Mutations for 0 Files created` means it found nothing (a setup failure), **not** a pass.

Scope the run to the class under test with `--path=<source dir>` and, where the installed Pest major has it, `--class="<Namespace>\<Class>"` with `--covered-only`.

### Triaging survivors

Read them; don't chase a number blindly. The three dispositions (killed, deleted, annotated), the four survivor buckets, the annotation placement that actually suppresses a mutant, and the docblock requirement are in [`pest-testing`](../pest-testing/SKILL.md#mutation-testing) and are not restated here. The criterion is **no unexplained survivors**, never a score; a `min_score` gate was decided against and is not to be reintroduced (see [`adversarial-review`](../../rules/adversarial-review.md)).

### Generating an exhaustive dataset (when the goal is 0 survivors)

Hand-authoring ~100 expected values is error-prone. Write a **one-off** generator (in a scratch directory outside the repository, **not** committed) that `require`s the checkout's autoloader, instantiates the SUT, runs it over a comprehensive input matrix (every tier/alias/synonym/branch), and `var_export`s each row as `'<label>' => [<value>, <params>, <result>],`. Paste the rows in, delete the generator. Note this makes the data-table rows **characterization** tests (regression locks, not correctness specs), fine for killing mutants, and the genuine-correctness rows (boundaries, edges) should still be hand-verified against intent. (Committed verification scripts are discouraged; a one-off authoring aid is acceptable.)

## Worktree setup (if migrating in a worktree)

A new worktree has no `vendor/`: run `composer install` before any test runs. See the `local-ci` skill, where the repository has it, for the worktree gotchas.

