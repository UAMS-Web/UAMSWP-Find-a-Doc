---
name: rule-adversarial-review
description: "Adversarially verify before it reaches anyone else."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/adversarial-review.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore phpdbg preg -->
# Rule — adversarially verify before it reaches anyone else

Two things reach other people: **a change that ships, and a claim that is published.** Do not release either on the strength of your own checking alone. First run an **adversarial review** (an independent, skeptical pass whose job is to *break* the thing, not confirm it), then triage its findings, fix the real defects, re-run whatever gate applies, and only then ship it or post it.

- **A change that ships.** You have built something substantive and the gate is green. Review the diff, then commit / open the PR / merge.
- **A claim that is published.** You have measured, counted, or verified something and are about to put the result where others will build on it: a figure in an issue or pull-request body, a number posted to a shared channel, a verification line in a test plan, a merge finding. Review the claim *and the instrument that produced it*, then post.

**Why this is a standing order, not a nicety.** The author and the author's tests share the same blind spots; an adversary with a fresh, hostile frame does not. A green suite is necessary, never sufficient: it passes on every slice while structurally *cannot* catch the defects it wasn't written to look for: a false-green test that stays green with its target deleted, a regex over-match in a parser, a pre-existing bug a new slice silently reuses, an authorization gate that covers every route it knows about and misses one it did not register, or an SSRF / stored-XSS that the happy-path fixtures never exercise. "Tests pass" earns the build; adversarial review earns the ship.

**And the published case is the worse of the two, which is why it is named rather than left implied.** A defect in a diff is caught by a gate *before* it reaches anyone. A defect in a published claim is **already in someone else's reasoning by the time it is found** (they have quoted it, built on it, or stood down because of it). There is no gate between you and them; the post is the gate.

## How to apply

1. **Trigger: two of them, and you are in one of them more often than you think.** Apply to any non-trivial, load-bearing, or correctness-/security-sensitive **change before it ships**; and to any **claim before it is published** where someone else could act on it and you checked it with an instrument you also chose: a merge finding, an audit reporting clean, a measurement another ticket will rest on. The second is the easier one to miss, so match against your own activity rather than against the word "review": *am I about to state a number, a count, a pass/fail, or an absence, that a reader has no way to re-derive?* If yes, that is the trigger (there is no diff and it still fires). **An absence is the sharpest case**: "I searched and found nothing" is a claim about your instrument at least as much as about the world. Skip only for genuinely trivial mechanical edits (a doc typo, a pure rename) or work already independently verified.

   **The claim half is not a second discipline, it is the same one applied to a sentence.** A finding is one of the artifacts a repository produces most often: [`pre-merge-check`](../rule-pre-merge-check/SKILL.md) requires one before every merge, and it decides whether a merge proceeds, so it earns the scrutiny the diff gets. What discharges it for a claim about text you produced is **execution rather than reading**, and the reason a pattern will not do it is that the probe and the material share an author: both encode the same assumption, so a second pattern reproduces the blind spot of the first. That argument and its method belong to [`reading-exit-status`](../rule-reading-exit-status/SKILL.md) and are not repeated here.

   **A claim about a dependency's code carries the revision it was measured at.** "Present in `<package>`" is incomplete the way an absence without a search scope is incomplete. A pinned or vendored copy can report the correct version, and the correct source reference, while containing different code: so a version name is not a revision, and the two diverge exactly when it matters. Name the commit, tag or lock entry the reading came from, and say which tree was read: the installed copy, or the upstream default branch, which are routinely not the same thing. A reading taken from one and reported as the other is a true measurement attached to the wrong subject.

   **Bounded, so ordinary prose does not acquire a review requirement.** The trigger reaches a claim that something was *checked* and found clean, or that a number is *this*. It does not reach description, argument, or a statement of intent: a pull-request body explaining what a change does needs no review pass; the same body asserting that a sweep found nothing does. Under **Ultracode** (the session-level opt-in to multi-agent orchestration), this is the default for every substantive slice; lean toward applying it whenever in doubt.

2. **Sequence it: build → review → fix → *then* the gating run.** The review changes the diff, so any acceptance evidence gathered before it is evidence for code that is no longer shipping. Do not start the expensive validation (a suite band, a soak, a timing run) until the review's findings are triaged and fixed. Prepare the PR and docs while the review runs; that is what "it gates the *ship*, not the *build*" means. The one thing you may run early is anything that would change *what you build* (and that is the review itself).

3. **Keep the review agents out of the tree being validated.** Prompt them read-only, and mean it about *writes of every kind*: running the suite is a write, because tests create files, rewrite caches, and share databases or parallel-run tokens with any concurrent run (so an agent "just checking whether the test passes" silently corrupts a run in the same worktree, and the scattered failures it produces read as real regressions. Either forbid test execution in the prompt, give the agents `isolation: "worktree"`, or (simplest) do not have a validation run in flight at all, which the sequencing above already gives you. Committing the diff first is still required so agents review a stable tree.

4. **Mechanism: a multi-agent `Workflow` review** when orchestration is available (the Ultracode opt-in, or the user asked for a workflow). Spawn **N independent reviewer agents over the diff**, each with a **distinct lens chosen for the change's actual risk surface** (e).g. *regression-safety*, *spec/acceptance-criteria correctness*, *edge-cases & test-rigor*, and a dedicated *security / attacker* lens for the repository's high-risk surfaces.

   Prompt each agent to **refute** (find the break), default to skeptical, and pin the context it must **not** relitigate (settled decisions, the chosen format). Force **structured, schema-validated findings** (severity + a worked example + a wrong-vs-right outcome verified against the code). Launch it in the background, under the sequencing in step 2. Without orchestration, scale down to the same adversarial frame: `/code-review` for general correctness, `/security-review` for a security-sensitive diff, or a focused self-review hunting your own blind spots.

5. **Make the review real, not theatrical.** Every finding must be **verified against the code** (ideally reproduced by running it), never speculated: a claimed defect shows the concrete input and the wrong vs. right behavior. A finding argued only from reading the source is a hypothesis. Tune the lens count and depth to the risk: a few finders + single-vote for "any bugs?"; more finders + multi-vote adversarial verification for "audit this" / security-critical surfaces. A finding is also **confirmed by someone other than its finder** before it is reported; see *Confirm a finding before reporting it* below.

6. **Triage and close the loop.** Fix every **blocker / major** and every cheap, high-confidence **minor**. For a real bug fixed, add a regression test and **mutation-verify** it (disable the fix → the test must go red; re-enable → green). **Revert by copying the file aside, never with the stash:** `cp path/to/Foo.php "$SCRATCH/Foo.fixed.php"`, `git show HEAD:path/to/Foo.php > path/to/Foo.php`, run, restore from the copy. The stash stack is shared with every other worktree and a failed `stash push` followed by `stash pop` applies somebody else's entry (see [`worktrees`](../rule-worktrees/SKILL.md)); and `git checkout --` throws away uncommitted work. Read the *reason* the test went red, not just that it did: a negative control that fails for the wrong reason proves nothing, and a mutation applied with a shell one-liner can corrupt the file instead of editing it, which reads as a very convincing red. A **pre-existing** bug a new slice merely *touches or reuses* is in scope; fix it. **Document** accepted gaps and deferred residue honestly (named, never silent). **Re-run the repository's gate** after the fixes. Only then ship.

7. **Record the verdict.** A closed design fork → on the issue and per the [`design-decision-forks`](../rule-design-decision-forks/SKILL.md) rule. A new open question or unspecified detail the review surfaced → a follow-up GitHub issue per the [`writing-issues`](../writing-issues/SKILL.md) skill (named, not silent). Otherwise a docblock / PR-body note.

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

This repository does not run Pest, so it has no mutation-testing instrument yet: Pest's `--mutate` arrives with Pest, and the [`tests`](../tests/SKILL.md) skill says whether and how the repository can move to it. Until then, answer the test-gap question by hand: for each test the diff relies on, name the line whose removal it would catch, and treat a test that names none as one that cannot fail.

### Property-based / round-trip tests

For the pure cores (parsers and field mappers, process/preProcess round-trips, serializers, pagination bounds, filters) assert the load-bearing **invariant** over many generated inputs instead of three examples (`decode(encode(x)) == x`, "every mapped field survives the import", "no page boundary drops or repeats an ID", "no input slips past the sanitizer"). Use a fixed seed: deterministic, non-flaky, still broad.

Both **raise the floor cheaply and forever**; neither invents the threat model. The discipline is *both* (tooling for the mechanical classes, the adversarial review for the novel ones).

## The DRY line

This file is the standing statement of the discipline, and since this rule fires on a published claim as well as a shipping change, the boundary with its neighbors is worth stating precisely. **This file owns whether a thing has been adversarially checked before it reaches anyone: diff or claim.** [`long-running-commands`](../rule-long-running-commands/SKILL.md) owns bounding and attributing a process. Neither is widened by this rule and neither widens it.

The `Workflow` tool description holds the orchestration *mechanics* (the review-pattern examples (adversarial verify, perspective-diverse lenses, loop-until-dry)) don't restate it. It composes with [`design-decision-forks`](../rule-design-decision-forks/SKILL.md) (surface genuine forks *before* building); the build→ship sequence itself (TDD, a green gate, branch, PR) lives in [`AGENTS.md`](../../../AGENTS.md).
