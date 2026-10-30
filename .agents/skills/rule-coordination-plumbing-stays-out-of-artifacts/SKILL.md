---
name: rule-coordination-plumbing-stays-out-of-artifacts
description: "Keep session-coordination plumbing out of GitHub artifacts and documentation."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/coordination-plumbing-stays-out-of-artifacts.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — keep session-coordination plumbing out of GitHub artifacts and documentation

Concurrent sessions coordinate over an out-of-band channel. **Nothing about that channel belongs in a pull request, an issue, a comment, a commit message, a rule, a skill, or anything under `docs/`, with one exception for operating instructions (see *What this does not cover*).**

**What counts as plumbing:** the coordination channel and the tooling that drives it, named or described; machine aliases; per-session or per-lane labels; and the liveness vocabulary that tooling uses: presence, roster, heartbeat, beat, stale, active, takeover, claim threads.

## Why this is a standing order

An artifact is read by people and tools that were not present when it was written, and that have no access to the coordination channel at all. A finding whose evidence is expressed in that channel's terms is therefore **unverifiable to its own audience**: the reader cannot inspect a roster, cannot resolve a session label, and cannot tell whether the claim was ever true. A machine alias is worse than useless: it identifies a person's box and buys the reader nothing.

## How to apply

1. **Restate the observation in the artifact's own vocabulary.** Describe what a process, a session, or a tree was *doing*, not what the coordination tooling called it. "A second concurrent session" carries the whole meaning; its label carries none of it to this reader.

2. **Argue consequences from harms already documented in this repo.** If the only way to explain why something matters is to explain the coordination channel first, the explanation is in the wrong artifact.

3. **Keep the evidence; drop the proper nouns.** This rule removes provenance vocabulary, never data. Process ids, paths, counts, dates, commands, error strings and the platform a measurement was taken on all stay. An artifact that loses its numbers along with its labels is worse than the one it replaced.

4. **Audit the draft before publishing**, because this leaks in while writing rather than as a deliberate choice:

   ```bash
   grep -niE -e '<machine-alias-pattern>|--session|\blanes?\b|presence|roster|heartbeats?|\bstale(ness|s)?\b|takeovers?' body.md
   ```

   Substitute the alias pattern your machines actually use. Any hit is a sentence to rewrite, not a word to delete.

   **The pattern follows `-e` so that it can never be read as an option.** A reader with no machine-alias pattern drops the first alternative, which leaves a pattern beginning `--session`; passed bare, some `grep` builds read that as an option, refuse to run, and print nothing to stdout, which looks like a clean draft. Read the exit status to tell the two apart: a refused sweep exits `2` with a usage message on stderr, a sweep that ran and found nothing exits `1`, and a sweep with hits exits `0`. Measured on Windows 11 under Git Bash, GNU grep 3.0, over a fixture carrying `--session` and `roster`: bare, exit `2` and nothing on stdout; after `-e`, exit `0` with both lines; after `-e` on clean prose, exit `1`. Measured on macOS (Darwin 25.6.0) with `ugrep 7.8.4` in `UAMS-Web/uams-claude-skills#340`: the same three outcomes. Other `grep` builds are not measured.

   **Two terms are `\b`-anchored and four are not, deliberately. Do not tidy them into consistency.** `lane` and `stale` are short enough to appear inside unrelated words (`plane`, `stalemate`), so they need boundaries; `presence`, `roster`, `heartbeat` and `takeover` are distinctive enough not to. The cost of anchoring is that a bare `\blane\b` cannot match its own plural, which is why both anchored terms carry their derivative forms explicitly. **The plurals and the word boundaries are load-bearing, and each one was paid for.** `lane` misses *lanes* and reports a real leak clean; `stale` misses *staleness*. An earlier version omitted them and **under-reported**: a session could run the documented sweep, get a clean result, publish on that basis, and still have leaked, having done everything this rule asked (`UAMS-Web/wordpress-importer-archive#882`).

   **`claim` is deliberately absent from the expression above.** Bare `claim` matches *reclaimed*, and even `claims?` is wrong: [`adversarial-review`](../rule-adversarial-review/SKILL.md) uses *claim* as ordinary vocabulary for the thing being reviewed, so the word appears throughout legitimate bodies. Measured over two recently-published bodies in `uams-statamic`, adding it produced **5 hits, none of them plumbing**. A false-hit rate that teaches the next reader to dismiss hits, which is worse than the miss it was meant to prevent.

   **A hit inside an allowed operating instruction is read, not fixed.** The exception under *What this does not cover* lets a skill or rule tell a session when and how to report to the channel. A hit there is correct when three things hold: it sits in an instruction addressed to the session running the skill (a step it performs, in the imperative), it names a call or message the session itself sends, and it is not offered as support for any claim. Anything else with the same words is a leak: a sentence that says what the roster showed, who held a claim, or what a session reported, used as evidence or provenance. The test is the sentence's job, not its vocabulary. Read each hit against it and keep the correct ones; a hit that fails any of the three is rewritten as step 1 describes. The exception does not reach an issue, a pull request, a comment, a commit message or anything under `docs/`, so a hit in those is always a leak.

   **Validate the expression before trusting an empty result**: run it once against a body you know carries plumbing and confirm it fires. Left as written, or substituted wrongly, the placeholder cannot match an alias and looks identical to clean. An expression that cannot match is indistinguishable from a clean draft. The general form of that check is [`an-empty-result-is-not-evidence`](../rule-an-empty-result-is-not-evidence/SKILL.md).

5. **For a PULL-REQUEST body, audit again at the merge. Publishing is not the last chance, it is the first one.** Merging composes the body into a commit message, and `main` is not rewritten, so a correction after the merge fixes the page and leaves the original wording on `main` permanently. [`github-api-budget`](../rule-github-api-budget/SKILL.md) carries the measurement of that composing behavior and [`pre-merge-check`](../rule-pre-merge-check/SKILL.md) carries the merge-gate step.

## What this does not cover

- **Vocabulary the project owns independently.** A word is not plumbing because it also appears in the coordination tooling. The test is whether the term names coordination infrastructure in the sentence it appears in, not whether it pattern-matches.

- **An operating instruction for reporting to the channel, in a skill or rule.** A skill or rule may tell the session running it when and how to report its own progress to the coordination channel (for example, that a session which posts a hand-back verdict then reports it), naming the channel's calls and messages where the instruction needs them. The reader of such an instruction is the session performing it, and that session is connected to the channel, so the reason for the ban (a reader who cannot inspect the evidence) does not apply. Without the exception the instruction lives outside every repository, in text relayed to each session, and a relayed instruction can be lost; the cost was hand-back verdicts that went unreported to the channel, leaving pull requests waiting up to 90 minutes before anyone acted on them (`UAMS-Web/uams-claude-skills#122`).

  **The exception is exactly that and no wider.** The ban stands in full for channel vocabulary used as evidence or provenance: in an issue, a pull request, a comment, a commit message and anything under `docs/`, and in a skill or rule anywhere outside an operating instruction of this kind. An instruction is allowed to say what to send; it is never allowed to cite what the channel showed or said as support for a claim. The audit in step 4 says how to tell the two apart.

- **The coordination channel's own traffic.** Messages on the channel are the one place these details belong, and they are not durable records. This rule governs what leaves that channel, not what happens inside it.

- **This file.** A rule that forbids naming something must name it, and describing the prohibition is the one legitimate use. Note that this file deliberately *describes* the channel and the alias pattern rather than reproducing either, so a repo-wide sweep stays clean and the rule that forbids the leak is not its own only hit.

## The DRY line

This file is the standing statement on **provenance vocabulary in outward-facing artifacts**. It composes with [`impersonal-voice-in-github-artifacts`](../rule-impersonal-voice-in-github-artifacts/SKILL.md), which governs a different leak in the same sentences: that rule removes the *narrator*, this one removes the *plumbing*, and a sentence can easily need both. What goes *in* a body belongs to the [`writing-issues`](../writing-issues/SKILL.md) and [`writing-pull-requests`](../writing-pull-requests/SKILL.md) skills, and subject/body mechanics to [`writing-commits`](../writing-commits/SKILL.md); each points here rather than restating it. **When** the audit runs for a pull-request body is shared with [`pre-merge-check`](../rule-pre-merge-check/SKILL.md), whose merge-gate step requires this sweep against the body about to be composed, and whose closing-keyword step carries the composing behavior that makes the timing matter. **Named rather than numbered, deliberately:** a step number cited from another file goes stale silently when that file renumbers, and nothing in the chain compares a count or an ordinal in one file against another; the measurement behind that behavior is [`github-api-budget`](../rule-github-api-budget/SKILL.md)'s.
