<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/evidence-behind-rules/adversarial-review.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore phpdbg preg -->
# `adversarial-review`: the full text and its evidence

This is the full text of the [`adversarial-review`](../../rules/adversarial-review.md) rule ("adversarially verify before it reaches anyone else") as it stood before it was condensed in UAMS-Web/uams-claude-skills#356. It keeps the measurements, incidents and worked examples the condensed rule cites. The rule is the instruction: where the two differ, the rule governs.

Two things reach other people: **a change that ships, and a claim that is published.** Do not release either on the strength of your own checking alone. First run an **adversarial review** (an independent, skeptical pass whose job is to *break* the thing, not confirm it), then triage its findings, fix the real defects, re-run whatever gate applies, and only then ship it or post it.

- **A change that ships.** You have built something substantive and the gate is green. Review the diff, then commit / open the PR / merge.
- **A claim that is published.** You have measured, counted, or verified something and are about to put the result where others will build on it: a figure in an issue or pull-request body, a number posted to a shared channel, a verification line in a test plan, a merge finding. Review the claim *and the instrument that produced it*, then post.

**Why this is a standing order, not a nicety.** The author and the author's tests share the same blind spots; an adversary with a fresh, hostile frame does not. A green suite is necessary, never sufficient: it passes on every slice while structurally *cannot* catch the defects it wasn't written to look for: a false-green test that stays green with its target deleted, a regex over-match in a parser, a pre-existing bug a new slice silently reuses, an authorization gate that covers every route it knows about and misses one it did not register, or an SSRF / stored-XSS that the happy-path fixtures never exercise. "Tests pass" earns the build; adversarial review earns the ship.

**And the published case is the worse of the two, which is why it is named rather than left implied.** A defect in a diff is caught by a gate *before* it reaches anyone. A defect in a published claim is **already in someone else's reasoning by the time it is found** (they have quoted it, built on it, or stood down because of it). There is no gate between you and them; the post is the gate.

## How to apply

1. **Trigger: two of them, and you are in one of them more often than you think.** Apply to any non-trivial, load-bearing, or correctness-/security-sensitive **change before it ships**; and to any **claim before it is published** where someone else could act on it and you checked it with an instrument you also chose: a merge finding, an audit reporting clean, a measurement another ticket will rest on. The second is the easier one to miss, so match against your own activity rather than against the word "review": *am I about to state a number, a count, a pass/fail, or an absence, that a reader has no way to re-derive?* If yes, that is the trigger (there is no diff and it still fires). **An absence is the sharpest case**: "I searched and found nothing" is a claim about your instrument at least as much as about the world. Skip only for genuinely trivial mechanical edits (a doc typo, a pure rename) or work already independently verified.

   **The claim half is not a second discipline, it is the same one applied to a sentence.** A finding is one of the artifacts a repository produces most often: [`pre-merge-check`](../../rules/pre-merge-check.md) requires one before every merge, and it decides whether a merge proceeds, so it earns the scrutiny the diff gets. What discharges it for a claim about text you produced is **execution rather than reading**, and the reason a pattern will not do it is that the probe and the material share an author: both encode the same assumption, so a second pattern reproduces the blind spot of the first. That argument and its method belong to [`reading-exit-status`](../../rules/reading-exit-status.md) and are not repeated here.

   **A claim about a dependency's code carries the revision it was measured at.** "Present in `<package>`" is incomplete the way an absence without a search scope is incomplete. A pinned or vendored copy can report the correct version, and the correct source reference, while containing different code: so a version name is not a revision, and the two diverge exactly when it matters. Name the commit, tag or lock entry the reading came from, and say which tree was read: the installed copy, or the upstream default branch, which are routinely not the same thing. A reading taken from one and reported as the other is a true measurement attached to the wrong subject.

   **Bounded, so ordinary prose does not acquire a review requirement.** The trigger reaches a claim that something was *checked* and found clean, or that a number is *this*. It does not reach description, argument, or a statement of intent: a pull-request body explaining what a change does needs no review pass; the same body asserting that a sweep found nothing does. Under **Ultracode** (the session-level opt-in to multi-agent orchestration), this is the default for every substantive slice; lean toward applying it whenever in doubt.

2. **Sequence it: build → review → fix → *then* the gating run.** The review changes the diff, so any acceptance evidence gathered before it is evidence for code that is no longer shipping. Do not start the expensive validation (a suite band, a soak, a timing run) until the review's findings are triaged and fixed. Prepare the PR and docs while the review runs; that is what "it gates the *ship*, not the *build*" means. The one thing you may run early is anything that would change *what you build* (and that is the review itself).

3. **Keep the review agents out of the tree being validated.** Prompt them read-only, and mean it about *writes of every kind*: running the suite is a write, because tests create files, rewrite caches, and share databases or parallel-run tokens with any concurrent run (so an agent "just checking whether the test passes" silently corrupts a run in the same worktree, and the scattered failures it produces read as real regressions. Either forbid test execution in the prompt, give the agents `isolation: "worktree"`, or (simplest) do not have a validation run in flight at all, which the sequencing above already gives you. Committing the diff first is still required so agents review a stable tree.

4. **Mechanism: a multi-agent `Workflow` review** when orchestration is available (the Ultracode opt-in, or the user asked for a workflow). Spawn **N independent reviewer agents over the diff**, each with a **distinct lens chosen for the change's actual risk surface** (e).g. *regression-safety*, *spec/acceptance-criteria correctness*, *edge-cases & test-rigor*, and a dedicated *security / attacker* lens for the repository's high-risk surfaces.

   Prompt each agent to **refute** (find the break), default to skeptical, and pin the context it must **not** relitigate (settled decisions, the chosen format). Force **structured, schema-validated findings** (severity + a worked example + a wrong-vs-right outcome verified against the code). Launch it in the background, under the sequencing in step 2. Without orchestration, scale down to the same adversarial frame: `/code-review` for general correctness, `/security-review` for a security-sensitive diff, or a focused self-review hunting your own blind spots.

5. **Make the review real, not theatrical.** Every finding must be **verified against the code** (ideally reproduced by running it), never speculated: a claimed defect shows the concrete input and the wrong vs. right behavior. A finding argued only from reading the source is a hypothesis. Tune the lens count and depth to the risk: a few finders + single-vote for "any bugs?"; more finders + multi-vote adversarial verification for "audit this" / security-critical surfaces. A finding is also **confirmed by someone other than its finder** before it is reported; see *Confirm a finding before reporting it* below.

6. **Triage and close the loop.** Fix every **blocker / major** and every cheap, high-confidence **minor**. For a real bug fixed, add a regression test and **mutation-verify** it (disable the fix → the test must go red; re-enable → green). **Revert by copying the file aside, never with the stash:** `cp path/to/Foo.php "$SCRATCH/Foo.fixed.php"`, `git show HEAD:path/to/Foo.php > path/to/Foo.php`, run, restore from the copy. The stash stack is shared with every other worktree and a failed `stash push` followed by `stash pop` applies somebody else's entry (see [`worktrees`](../../rules/worktrees.md)); and `git checkout --` throws away uncommitted work. Read the *reason* the test went red, not just that it did: a negative control that fails for the wrong reason proves nothing, and a mutation applied with a shell one-liner can corrupt the file instead of editing it, which reads as a very convincing red. A **pre-existing** bug a new slice merely *touches or reuses* is in scope; fix it. **Document** accepted gaps and deferred residue honestly (named, never silent). **Re-run the repository's gate** after the fixes. Only then ship.

   Mutation-verify with the tooling too (Pest's native mutation testing automates this across the changed surface: `vendor/bin/pest --mutate --class="<Namespace>\\…\\<ChangedClass>"` flags any *surviving mutant* (a mutation no test kills = a false-green gap). It needs a coverage driver (PCOV) fast, coverage-only (or Xdebug); a per-machine install that does **not** travel via git). See *Tooling that raises the floor* below.

7. **Record the verdict.** A closed design fork → on the issue and per the [`design-decision-forks`](../../rules/design-decision-forks.md) rule. A new open question or unspecified detail the review surfaced → a follow-up GitHub issue per the [`writing-issues`](../writing-issues/SKILL.md) skill (named, not silent). Otherwise a docblock / PR-body note.

## Review questions

The lenses in step 4 say where to look. These questions say what to ask once you are there. Pick the ones the diff calls for: the error-handling questions when a `try`, a `catch`, a default or a fallback changed; the test-gap question whenever behavior changed; the comment check whenever a comment or docblock was added or edited. Every answer that reports a defect still owes step 5's worked example.

### Error handling: does any failure go unnoticed?

Find every place the diff handles a failure: each `try`/`catch`, each error object returned instead of thrown, each default applied when a read comes back empty, each fallback path, each log call followed by more work. Then ask of each one:

- **Broad catches.** What does this `catch` catch besides the failure it was written for? `catch (\Throwable)` or `catch (\Exception)` around a whole block also swallows a typo'd method, a type error and a failed write. Name the exceptions it could hide. If the answer is "anything", it should catch the specific class, or cover fewer lines.
- **Silent fallbacks.** When the fallback runs, does the caller know? Returning `null`, `false` or `[]` from a failed read hands the next line a value that looks like an empty result rather than an error, and a consumer downstream will treat it as one. Ask whether the fallback is something the ticket or the code's contract asks for, or a way to make the error stop showing. A stub, fake or canned value used outside tests is always a finding.
- **Masking defaults.** Does a default stand in for a value that failed to load? `??`, `?:` and `?->`, the `@` operator, an array read with a default, `rescue()` and `optional()` all turn "missing because it failed" into "missing because it is empty". So do unchecked failure returns such as `json_decode()` returning `null`, `unserialize()` returning `false` and `file_get_contents()` returning `false`, and any read that returns the same empty value for an absent node and an empty one. Ask what the default hides, and whether anything downstream can tell the difference.
- **Log and continue.** After a failure is logged, does the code carry on as if it had succeeded? If so, is carrying on correct, is the log at a level someone will see, and does it name the operation, the input and the identifiers needed to find the case again? A log line nobody reads is a silent failure with a paper trail.
- **Where it should be handled.** Should this failure propagate to a caller that can do something about it, rather than stop here? Does catching it here skip cleanup, such as a lock, a temporary file or a half-written record?
- **What the person sees.** Where a failure reaches a user, an editor, an operator or a consuming system, does the message say what went wrong and what to do next? A generic "Something went wrong", or a warning that names no item, is a finding.

### Test gaps: what regression would the missing test catch?

For each behavior the diff adds or changes that no test covers, name the regression a test would catch, and rate the gap by what that regression would cost rather than by lines left uncovered:

- **Must add:** data loss, a disclosure or other security hole, wrong or stale content published or served as if correct, or a record silently dropped or repeated.
- **Should add:** an error a user, an editor, an operator or a consuming system would see and act on.
- **Consider:** an edge case that would confuse someone without breaking anything.
- **Skip:** coverage for completeness only, such as a trivial accessor.

Then ask of the tests that do exist: would each fail if the behavior broke, or only if the implementation changed shape? Does each error path and each rejected input have a negative case? A test that cannot fail is no test, and mutation testing (below) is the instrument that shows it.

### Comment accuracy: is every claim in a comment still true?

For every comment and docblock the diff adds or edits, check each statement against the code it describes:

- Do the documented parameters, types and return values match the signature?
- Does the described behavior match what the code does, including the edge cases it says it handles?
- Does every class, method, file, config key, decision and issue number it names still exist?
- Is a measured claim, such as a count, a timing or "verified on" a platform, still true of the code as it now stands?
- Does it restate what the code plainly says, or explain why? A comment that only restates is a candidate for removal. A "TODO" whose work is done is stale.

A wrong comment is a finding just as a wrong line of code is: the next reader trusts it.

### Confirm a finding before reporting it

**No finding is reported until someone other than its finder has confirmed it.** Give the checker the finding, the location, and the change's stated intent: the ticket and the PR description. The checker's job is to show the finding is real, by reproducing it or by reading the code and establishing that the claimed input produces the claimed wrong behavior. In a multi-agent review the checker is a separate agent. Without orchestration it is a fresh pass that starts from the finding rather than from the diff.

**A finding that is not confirmed is dropped from the report.** It is not softened into a "possible issue". False positives cost the author time and teach them to discount the next finding, which is how a real one gets ignored. A suspicion that could not be confirmed but still looks worth pursuing becomes a question on the ticket, labeled as a question, and never a finding.

**Confirmation narrows what is reported, never what is looked for.** It filters findings after they are raised; it does not license skipping a lens, a pre-existing bug the diff touches (step 6 keeps those in scope), or a security question because it depends on input.

## Tooling that raises the floor (so the review can reach the ceiling)

The human review is irreplaceable for *novel* reasoning (a threat model, "is this structure sound?"), but two cheap, durable tools catch the **mechanical** half of what review keeps finding: run them so the review's attention goes to what only a human can see.

### Mutation testing

**Pest's native `--mutate`** (`pestphp/pest-plugin-mutate`, bundled with `pestphp/pest`) mutates your code and demands a test fail; a **surviving mutant is a false-green test** (exactly the class ("a test that stays green with its target deleted") review catches repeatedly. Run it **diff-scoped** in the verify step: `vendor/bin/pest --mutate --class="<Namespace>\\…\\<ChangedClass>"`: read the survivors, and close each. **`0 Mutations for 0 Files created` means it found nothing to mutate, not that you passed**) treat that as a setup failure, never a green.

**The criterion is *no unexplained survivors*, not a score.** A bare percentage aggregates the two survivor classes it most needs to separate: an unexamined survivor and a provably equivalent one are the same number, and raising the bar makes it worse: the last few percent are usually **equivalent mutants**, so a high threshold converts "explain this survivor" into "delete a defensive guard to move the number". A `min_score` gate is deliberately not part of this and is not to be reintroduced. Every survivor gets one of three dispositions: **killed** (a survivor is a test to write), **deleted** (or dead code: mutation testing is the best dead-code finder you have), or **annotated with the reason it cannot be killed**. Only the third needs writing down, and it is written where the survivor is.

**The instrument is `// @pest-mutate-ignore`, per line and per mutator** (`// @pest-mutate-ignore: InstanceOfToTrue` scopes it to named mutators, and the bare form skips every mutator on that node *and its entire subtree* (`Support/NodeVisitor.php`). **Not** the config-level `ignore(...$paths)` or `except(...$mutators)` in `Contracts/Configuration.php`: those silence a whole file or a mutator repo-wide, so neither can carry a per-survivor justification and `except()` would blind a mutator everywhere to explain one line.

**Put it standalone on the line above the node, with the reason on a line before that: never on the marker's line, and never trailing on the node's own line.** The parser is `preg_match('/@pest-mutate-ignore(.*)/', ...)`; `.` does not cross a newline, so the captured tail is the rest of *that line*. It is then `explode(',')`d, each part `trim`med of `' */:'`, and compared against **mutator names**. Prose is never a place the parser looks, so prose after the marker matches no mutator and **suppresses nothing**, while a **bare** marker is the empty-tail case and suppresses everything. Those fail in opposite directions: an inert annotation surfaces as an unexplained survivor and this criterion catches it, but a blanket one silently removes mutants and **raises** the apparent result, which nothing catches. Measured in `uams-statamic` against `pest-plugin-mutate` `v5.0.2` on a four-mutant probe:

| form (on its own line, above the node) | mutants left |
| --- | --- |
| *no annotation* | 4 |
| `// @pest-mutate-ignore` | **0**: empty tail suppresses every mutator |
| `// @pest-mutate-ignore: GreaterToGreaterOrEqual` | 3: suppresses that one |
| `// @pest-mutate-ignore: GreaterToGreaterOrEqual because phpstan needs it` | **4: suppresses NOTHING** |
| `// @pest-mutate-ignore: GreaterToGreaterOrEqual, because phpstan needs it` | 3: the **comma** makes the first element match again |

That last row is why this is worse than "prose does not work": the failure is **punctuation-sensitive**, so the same sentence works or does not depending on a comma. **A single trailing space inverts it on an unpatched plugin, which makes this a formatting concern**: on stock `v5.0.2`, `// @pest-mutate-ignore` with one trailing space left **4** mutants (tail is `' '`, non-empty, matches no mutator); with the space removed, **0** (tail empty, suppresses everything). `pint`'s `no_trailing_whitespace` strips it: so a **formatting-only commit can silently change mutation semantics in either direction**. A trailing marker on the node's own line is silently inert or worse (it can suppress the wrong statement instead), and a run cannot tell that from a wrong reason: both look like an ordinary surviving mutant.

**[`check-mutate-ignore.php`](../pest-testing/scripts/check-mutate-ignore.php) finds the inert and the blanket forms, so they are checked by a script rather than by eye.** The `pest-testing` skill ships it; a repository runs it as a CI job (the `code-quality` CI template carries one). It flags, with file and line, a bare marker, a tail that names no known mutator (prose, or a separator with nothing after it), prose after a mutator list, and a trailing marker where the plugin's line map is dead. It prints the plugin revision its parsing model was measured against, and says when the installed plugin differs. A clean run means no annotation is inert for those reasons, never that every annotation is effective; which mutants may be annotated at all stays this rule's judgment.

```php
// phpstan mandates this guard, so InstanceOfToTrue cannot be killed without
// failing the other gate.
// @pest-mutate-ignore: InstanceOfToTrue
if ($x instanceof Foo) {
```

**That criterion is survivor-shaped, so it cannot see a false kill, and the asymmetry is the whole point.** A false **survivor** leaves something to explain, so the criterion catches it. A false **kill** leaves **nothing to explain**, so the criterion is *satisfied* by it: the instrument's output is wrong in the direction the audit reads as success. **A 100% score is the opposite false green and this criterion does not catch it**: a run that counts mutants without executing them produces no survivors, so *no unexplained survivors* is satisfied by a run that tested nothing. Each mutant runs in a child process, and any non-zero exit is read as *the test failed*, so a child that could not be **launched** is recorded as a kill: every mutant then reports killed, the run prints `100.00%`, and it exits `0` with no warning. **The criterion is DISAGREEMENT, not speed** (zero survivors on a class nobody has hardened is a *null* result, because a run that executes nothing cannot disagree with itself).

**So a mutation run must carry its own evidence that it executed, and that evidence is read before its score is trusted.** This is a per-run obligation rather than a per-platform one: a machine answers it for itself out of the run in front of it. Four readings, from cheapest to most decisive:

- **Survivors are the proof, and they need no baseline.** An unexecuted mutant is reported killed, never survived, so any survivor establishes that execution happened. Whenever the score is below 100, that is the whole check.
- **When the score IS 100, compare the two phases inside the same run.** `--mutate` prints a `Duration:` for the base test phase and a second for the mutation phase; they are separate rather than nested, so the comparison needs no external reference and no second machine. Measured in `wordpress-importer` on macOS (Darwin 25.6.0) at `b46c0ef`, its XML string-helper class scoped to one test file with `--covered-only` under PCOV, four consecutive runs with **identical verdicts** (21 untested, 32 tested, 60.38%), so every row is a demonstrably healthy run:

  | run | base phase | per test | mutation phase | per mutant | ratio |
  | --- | --- | ---: | --- | ---: | ---: |
  | 1, cold | 1.16s / 5 | 232ms | 7.86s / 53 | 148ms | **0.64** |
  | 2 | 0.34s / 5 | 68ms | 11.57s / 53 | 218ms | 3.20 |
  | 3 | 0.34s / 5 | 68ms | 12.54s / 53 | 237ms | 3.48 |
  | 4 | 0.35s / 5 | 70ms | 17.31s / 53 | 327ms | 4.67 |

  **So do not write this control as "the mutation phase cannot be faster than the base phase".** Row 1 is healthy (it carries 21 survivors) and violates that inequality outright: a **cold** base phase cost 3.4x the warm figure and pulled the ratio under 1. A session's first run is the one most likely to be cold, and it is also the one a reader is most likely to act on. **The discriminating signal is an order of magnitude, not a direction.** The reported failures sit at **0.03 and 0.08** (a Windows seat: 209 mutations, 209 tested, 0 survivors, 100% in 7.63s: roughly 36ms per mutant against a 470ms test); the healthy band measured here spans **0.64 to 4.67**. Treat a ratio near 1 as unresolved rather than as either verdict, and re-run before reading it.

  **The ratio is readable only when the base phase measures the tests.** Under `--mutate` the base phase can be dominated by a fixed cost that has nothing to do with test time, and a ratio computed from a padded base phase carries no information in either direction (UAMS-Web/uams-claude-skills#376). Measured on macOS (Darwin 25.6.0), PHP 8.4.27, Pest `v5.2.1`, `pest-plugin-mutate` `v5.0.2`, against `uams-statamic` at `201acad1f`, mutating 30 classes under `app/Services/SchemaTemplates/Mergers` with no `--covered-only` (the run gated on `UAMS-Web/uams-statamic#404`):

  | basis for "per test" | per test | per mutant | ratio | reading |
  | --- | ---: | ---: | ---: | --- |
  | the run's own base phase, 1,261.54s / 63 | 20.02s | 158ms | **0.0079** | below the false-kill figures |
  | the same suite without `--mutate`, 2.28s / 65 | **35ms** | 158ms | **4.5** | inside the healthy band |

  The same run on a second machine reported a base phase of 1,261.29s, a quarter of a second from the first across twenty-one minutes, which is a fixed cost rather than compute: roughly 20s of padding on each process's first test under `--mutate`. The padded base phase was about **553x** the suite's real duration. The run was genuine by the two instruments that do not read the ratio: `check-mutation-instrument.php` exited 0, and the hand-replicated child below exited 0 on a byte-identical copy and 1 on a real mutation.

  - **Compare the base phase against the same tests run without `--mutate`**, same file selection, same machine. Where the two are within the cold-to-warm spread above, the base phase is test time and the ratio stands as computed.
  - **Where they differ by orders of magnitude, the base phase is padding.** Recompute the ratio against the duration without `--mutate`, or set the ratio aside and decide from the launch check and the hand-built child, which do not depend on it.
  - **Never dismiss a low ratio as padding without that comparison.** A false kill also produces a low ratio, so "it is probably padding" read off the ratio alone turns the control into one that cannot detect the failure it exists for. The comparison is what tells the two apart: padding shows a base phase far above the tests' own duration, while a false kill shows a base phase close to it and a mutation phase far below.

  Applied to the figures on record: the #404 run recomputes to 4.5, healthy. The Windows failure above had a base phase of 470ms per test, an ordinary test's cost rather than tens of seconds of padding, so the ratio stands only once a run without `--mutate` lands near that figure. If it does, the reading is still a false kill at 0.08.
- **Read the mutator names a 100% run claims to have killed.** A single class reported as killing `AlwaysReturnNull`, `RemoveEarlyReturn`, `ForeachEmptyIterable`, `TrueToFalse` and `ConcatSwitchSides` outright is claiming a suite stronger than almost any real one: the healthy run above killed 32 of 53 and left 21 standing. Implausible perfection across diverse mutators is the same signal as the timing gap, available by looking rather than by measuring.
- **All three read the run's own output, so all three can be inconclusive at once, and a small, well-tested class is where that happens.** Measured in `wordpress-importer` on Windows: a 5-mutant run reporting `100.00%` in `0.17s` against a `1.30s` base phase, killing `CoalesceRemoveLeft`, `AlwaysReturnNull`, `BooleanOrToBooleanAnd`, `AlwaysReturnEmptyArray`: all plausible for a class whose unit test asserts each. That run was healthy, and none of the three readings could establish it. **So when they are inconclusive, construct a mutant whose result is REQUIRED rather than inferred.** Replicate one child by hand with the plugin's own two environment variables, and run it twice:

  ```
  PEST_MUTATION_TESTING=<absolute path of the source file>
  PEST_MUTATION_FILE=<path of the replacement file>
  vendor/bin/pest <test file> --bail --filter="<Class>::(.*)"
  ```

  | `PEST_MUTATION_FILE` points at | Required result |
  | --- | --- |
  | a **byte-identical copy** of the source | exit **0**: a change of nothing must survive |
  | the source with **one real mutation** | exit **non-zero**, with a named failing assertion |

  **Give the child the test selection the run under test gives its children, not a narrower one.** `<test file>` above stands for what the run names. Where the run names none, the child scans the suite directory, and where the Unit bootstrap loads Patchwork that scan dies before any test (`readdir()` in Patchwork's stream wrapper); a control scoped to one test file passes on the same machine and says nothing about the run (UAMS-Web/uams-claude-skills#131).

  **The identical-copy row is the one that carries it, and it is the cheaper of the two.** A mutation that changes nothing *must* survive; if that child exits non-zero it is failing for a reason unrelated to the mutation, and the plugin scores every such failure as a kill (the false green above, reproduced deliberately). Measured in `wordpress-importer`: `exit 0, 7 passed` against `exit 1, FAILED … Failed asserting that two arrays are identical`. It needs no survivor, no ratio, no baseline, and **no modification to anything under version control**; the replacement is a copy in a temporary directory. **Assert the copy is byte-identical before reading its result**, for the same reason a hand-rolled mutation must be asserted to have applied: a copy that silently differs inverts the conclusion. Note what that command is not: it carries no `--mutate` and does not go through the plugin's spawn, which is precisely why it can arbitrate.

  The equivalent control on the tool, where a single covering test exists: skip that test, confirm it is genuinely skipped, and require the mutant to flip to survivor. A mutant that stays killed with the only test that can kill it not running is evidence about the instrument rather than about the code.

**A hand-rolled mutation has the matching trap in the other direction.** When mutating by hand (a `sed`/`perl` edit, then re-run), **assert the edit actually changed the file before reading the result** (a substitution whose escaping never matched leaves the file untouched, so the test passes and reports as a *surviving mutant*). That reads as a gap in your tests and is not one. Observed on `wordpress-importer-archive#739`: two "survivors" were `perl` substitutions whose escaping never matched; re-run with an exact replacement that asserts it matched once, both were killed.

**Coverage driver.** `--mutate` needs a coverage driver: **PCOV** (recommended: fast, coverage-only) or Xdebug; *not* phpdbg (the mutate engine rejects it). It is a per-machine install that does **not** travel via git, so it is set up once per developer rather than checked in.

**Run `php .claude/skills/pcov-setup/scripts/check-mutation-instrument.php` before trusting a score.** Each mutant child is built from `$_SERVER['argv']` with **argv[0] reused verbatim as the executable**, so a child that could not launch records as a kill. The same check runs an identical-copy child through the run's own test selection, because a child that launches can still die before any test (Patchwork's stream wrapper and the plugin's cannot read a directory together). **The identical-copy control above uses the same selection as the run it checks**: a control scoped to one named test file passes where an unscoped run crashes, and passing it says nothing about the run. The mechanism and the residual it does not close are in [`pcov-setup`](../pcov-setup/SKILL.md). For a full-coverage ticket, track the three passes (touched → line % → mutation-clean) with `build-coverage-scope-csv.php`, and keep that helper current in UAMS-Web/uams-claude-skills.

### Property-based / round-trip tests

For the pure cores (parsers and field mappers, process/preProcess round-trips, serializers, pagination bounds, filters) assert the load-bearing **invariant** over many generated inputs instead of three examples (`decode(encode(x)) == x`, "every mapped field survives the import", "no page boundary drops or repeats an ID", "no input slips past the sanitizer"). Use a fixed seed: deterministic, non-flaky, still broad.

Both **raise the floor cheaply and forever**; neither invents the threat model. The discipline is *both* (tooling for the mechanical classes, the adversarial review for the novel ones).

## The DRY line

This file is the standing statement of the discipline, and since this rule fires on a published claim as well as a shipping change, the boundary with its neighbors is worth stating precisely. **This file owns whether a thing has been adversarially checked before it reaches anyone: diff or claim.** [`long-running-commands`](../../rules/long-running-commands.md) owns bounding and attributing a process. Neither is widened by this rule and neither widens it.

The `Workflow` tool description holds the orchestration *mechanics* (the review-pattern examples (adversarial verify, perspective-diverse lenses, loop-until-dry)) don't restate it. It composes with [`design-decision-forks`](../../rules/design-decision-forks.md) (surface genuine forks *before* building); the build→ship sequence itself (TDD, a green gate, branch, PR) lives in [`AGENTS.md`](../../../AGENTS.md).
