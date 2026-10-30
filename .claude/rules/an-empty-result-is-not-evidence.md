<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/an-empty-result-is-not-evidence.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore metacharacter metacharacters PATHCONV -->
# Rule — a result is not evidence until the instrument has been shown to tell the two answers apart

**A check that returns nothing has told you about the check, not about the world.** Before reading silence as an absence, show the same instrument, unchanged, finding something it is known to be able to find, and **state that control and its non-zero result alongside the claim**.

**The same holds for a result that is not empty**: a plausible, well-formed answer can be wrong. One requirement covers both: **do not read an instrument's output until it has been shown to distinguish the two answers it is being asked to choose between.** Read *absence* or *zero* below as any result; what is validated is the instrument, never the answer.

Which corpus was searched is a separate question; this rule is the prior one: **did the search work at all?**

## Why this is a standing order

The two outcomes are indistinguishable by construction, and a broken check fails in the reassuring direction: it reports clean and is believed. A plausible number is worse: it closes the question.

### Probes that silently could not see

- **Validate with the tool you will cite.** `git grep -E` has no `\b` (use `-P`) while a system `grep -E` may.
- **A pattern copied from source code is made of metacharacters, and any one left unbracketed is a silent zero.** `$`, `(`, `)`, `.`, `[`, `*`, `+`, `?`, `|` are ordinary in PHP and special in a regex. Under ERE `$` anchors anywhere and `()` groups, so `grep -E 'f($x)'` cannot match, and BRE greps disagree on `$`. **Use `grep -F` by default; bracket every metacharacter only where part must stay a regex**; bracketing only the `$` reproduces the defect through the fix. Under Windows Git Bash without a UTF-8 locale, GNU grep 3.1 aborts on `-iF` and refuses `-P` ([evidence](../skills/evidence-behind-rules/an-empty-result-is-not-evidence.md#probes-that-silently-could-not-see)).
- **A phrase reconstructed from memory is not a probe.** Copy the text you search for, even a sentence you wrote.

### The prescribed control is the thing that breaks, on two platforms, for two unrelated reasons

- **Git Bash on Windows: set `MSYS_NO_PATHCONV=1` for `<rev>:<path>` when the path starts with a dot** (`.claude/…`, `.gitignore`). MSYS rewrites it and git's exit 128 reads as the file being absent; `README.md` and a leading `./` are unaffected, so the obvious sanity test passes. Every `<rev>:<path>` subcommand is hit (measured: `show`, `cat-file`, `rev-parse`, `diff`); `cat-file -e`, read from its exit status alone, is the dangerous case.
- **zsh: brace the expansion, `"${SHA}:<path>"`.** Unbraced, zsh reads `:c`, `:s`, `:t` and other modifier letters after `$SHA` and mangles the argument. A re-test with a variable after the colon (`$SHA:$path`) cannot reproduce the trap and reports clean. bash is unaffected. Neither remedy fixes the other trap.

## How to apply

1. **Run the positive control before you read the result.** Same command, flags and quoting, against a case it must find; for a source sweep, the same expression against a revision where the target still exists (`git grep -c <expr> origin/main`). An empty control means the instrument is blind, not that the answer is no.
2. **State what the control could have failed on.** A control exercising a different variable than the claim is decoration.
3. **Prefer a control the instrument cannot pass by accident, and did not get from you.** Take a real instance from the corpus and read it to confirm it is one; where the corpus's emptiness is the question, sample a comparable one (a prior revision, a sibling directory, an artifact of the same kind). A known-bad case cited from memory may have been repaired; a hand-written control matches what its author pictured, so both fail together. Where no real instance exists, a synthetic fixture of exactly the target is the fallback, and step 7 says to say so.
4. **Distinguish "found nothing" from "could not run".** Check the exit status deliberately or print a count; a pipe reports on the pipe ([`reading-exit-status`](reading-exit-status.md)).
5. **Confirm a flag or pattern exists before treating its silence as evidence.** An unrecognized flag is a no-op where a filter would be.
6. **Publish the control with the claim.** "0 here, against 9 on `origin/main` with the same expression" is a finding; "0 occurrences" is unverified, even to its author.
7. **Not discretionary.** Where no control is possible, say so: the absence then rests on the expression alone.
8. **The control must be independent of what it validates.** A token the sweep's own artifacts contain is not a control: writing it into an indexed comment changes the next result.
9. **Make the instrument return a DIFFERENT answer from the one under test.** Feed it an input whose known answer differs from the claim: a hit when claiming an absence, a miss when claiming a hit. An instrument that has only ever returned one value has been used, not tested.
   - **A timeline `cross-referenced` event cannot tell a closing link from a mention**: it fires for both, and for a `Refs #N` that closes nothing. Ask `closingIssuesReferences`, and check that the keyword sits next to the reference.
   - **`closingIssuesReferences` reports what a pull request DECLARES, not what its merge does.** The field parses the body as Markdown; the push-time scan reads the squash message as plain text, so a fenced or cross-line directive can act on an issue the field omits.
   - **"Adjacent" spans a newline**: a keyword ending one line pairs with `#N` starting the next, so a line-by-line check answers a narrower question. Read across lines, on the text that will merge.
   - **`grep -c` counts matching lines**, so a phrase across a line break cannot match. In hard-wrapped text, **normalize whitespace before a phrase search, and pair every removal check with a positive check for the replacement.**

10. **A passing control proves detection, not coverage**: report the bound, not the reassurance. **Compare what you read against the total the source states** (a header total, `total_count`, `rel="last"`, a page count); where it states none, say so beside the claim. Records missing from one end of an ordering, or **a count equal to a page size**, signal truncation. GitHub's instances are in [`github-api-budget`](github-api-budget.md).
11. **A working instrument pointed at the wrong thing looks the same as one pointed at the right thing.** A failing probe announces itself; a succeeding one does not, so state a positive result's provenance (tree, revision, file) as explicitly as a zero's control. A proxy covers only what was compared. Reading the wrong subject is [`adversarial-review`](adversarial-review.md#how-to-apply)'s.
12. **A filter you add can remove the target with the noise.** A filter dropping an expected match (the definition, the test file, the vendor copy) drops everything sharing that property, and step 1's control runs before it. Run without the filter too and account for every line it removed; filter on the one known line, not its file. A symbol with no callers and heavy use in its own file is implausible: read the filtered-out region.
13. **Scale the rigor to what the answer would license.** A cheap check that retires a ticket, stands a colleague down, or feeds someone else's decision earns a control; one that only informs your next step may not.
14. **Read the object, not an aggregate computed over it.** A count, a diff stat, a `total_count` or a filter-derived boolean discards the detail that would show the answer wrong. Worked instances: [evidence](../skills/evidence-behind-rules/an-empty-result-is-not-evidence.md#worked-instances-of-a-well-formed-wrong-answer).

## A second shape: the instrument answers a narrower question than the one asked

A local clone answers "as of my last fetch"; one branch answers for that branch; a contents-API read with no `?ref=` answers for the default branch. **Name the scope in the same sentence as the result** ("absent on `master` as of this fetch"): an unstated boundary is assumed to be the widest.

## A number asserted without ever being measured is not a weak version of this

A figure no command produced is no instrument at all, and no control rescues it. Say so plainly.

## Where this has bitten, so the fourth reader recognizes their case

Process sweeps: [`long-running-commands`](long-running-commands.md). API queries (`gh api --jq` takes only the filter; a stray `--arg` prints nothing, so echo the status or print a count): [`github-api-budget`](github-api-budget.md). Pipelines: [`reading-exit-status`](reading-exit-status.md). Self-audits: [`adversarial-review`](adversarial-review.md).

## What this does not cover

- **Nothing, for being a positive result.** A count proves nothing when the instrument, not the subject, produced it.
- **Whether the right corpus was searched**, or **whether a measurement is still current**: separate disciplines.
- **A true measurement attached to the wrong subject**: [`adversarial-review`](adversarial-review.md)'s.

## The DRY line

This file owns the **general form and its remedy**, and nothing else. Sweep causes belong to [`long-running-commands`](long-running-commands.md); query guidance and GitHub partial reads to [`github-api-budget`](github-api-budget.md); pipeline mechanics to [`reading-exit-status`](reading-exit-status.md); the two-sided sweep control for durable records to [`no-emoji-in-durable-records`](no-emoji-in-durable-records.md); an independent checker and the correct subject to [`adversarial-review`](adversarial-review.md), whose shared-blind-spot argument step 3 applies to a single expression.

Evidence, measurements and history: [`an-empty-result-is-not-evidence`](../skills/evidence-behind-rules/an-empty-result-is-not-evidence.md).
