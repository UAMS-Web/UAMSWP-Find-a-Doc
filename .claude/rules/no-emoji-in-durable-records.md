<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/no-emoji-in-durable-records.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!-- cspell:ignore charmap -->
# Rule — no emoji in durable records

**Emoji do not appear in anything this repo keeps, and neither do pictographic glyphs used as punctuation.** That covers issue and pull-request titles, bodies and comments; commit messages; release notes; every file under `.claude/` and `docs/`; `README.md` and `AGENTS.md`; and `CLAUDE.md` in a repository that still has one.

## Why this is a standing order

**Emoji encode urgency the reader cannot calibrate.** A warning glyph asserts that a sentence matters more than its neighbors without saying why, and once several of them accumulate the marker stops carrying information at all: the reader learns to skip the glyph and read the sentence, which is what the sentence should have been doing alone. A line that needs a warning marker needs a clause naming what goes wrong.

**And they break, in the places least able to report it.** This has already been paid for once. `UAMS-Web/wordpress-importer`'s release-notes generator carries an explicit UTF-8 forcing on stdout for one reason: on Windows the stream is sized to the console code page, and a `--breaking` run died on the warning glyph in its callout (`U+26A0 U+FE0F`) **after doing all of its work**, leaving a traceback in the redirect where the release notes should have been. That failure is the general case in miniature: the glyph survives the editor it was typed in and fails somewhere downstream, at the moment of least attention.

**The same crash in a *scanning* run produces the opposite of a traceback: nothing.** A generator dies late, after most of its artifact exists, so the wreckage is on disk and impossible to miss. A scanner dies on its **first finding, before printing it**, so stdout is empty, which is exactly what a clean corpus looks like. Redirect stderr and drop the exit code, as any pipeline does, and the run is byte-identical to a pass. Measured in `uams-statamic`:

```
python 3.12, Windows, cp1252 stdout
probe printed the matched LINE alongside the code points
UnicodeEncodeError: 'charmap' codec can't encode character U+274C
stdout at crash   EMPTY   - died on the FIRST hit, before printing it
exit code         1
```

**Know which of the two you are running**, because the reassuring reading is available for one of them and not the other. A generator that returns nothing has plainly failed. A scanner that returns nothing has either found nothing or died before it could say, and those are indistinguishable without the exit code.

Two further costs, both cheap to avoid and expensive to discover. A variation selector, `U+FE0F`, is **invisible**: it survives a naive strip of the character it modifies, so a body that looks clean can still carry it, and any byte comparison against that body disagrees for a reason nothing on screen explains. And a glyph is not searchable: a reader grepping for the warnings in a document finds them only if they can type the glyph.

Nothing here is about tone. Emphasis is welcome; **bold** carries it without a code point that some consumer cannot decode.

## How to apply

1. **Use words.** Bold the clause, or open the sentence with what is at stake. `**Breaking change**: re-run the affected import after upgrading` needs no marker; it already says the thing a marker was standing in for. A check mark in a verification list becomes the result: `all eight jobs passed` rather than a mark that asserts the same thing with less information.

2. **Audit with a two-sided control, or do not trust the answer.** An empty result from a probe that cannot match is identical to an empty result from clean prose:

   ```bash
   GLYPH='[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE0F}]'
   count() { perl -CSD -ne '$n++ if /'"$GLYPH"'/; END{print $n+0, "\n"}'; }

   # BOTH arms, every time. Neither alone is worth anything.
   printf 'x \xe2\x9a\xa0 y\n' | count    # must print 1
   printf 'plain text\n'       | count    # must print 0

   (
     find .claude docs README.md AGENTS.md -type f -print0
     find . -maxdepth 1 -name CLAUDE.md -print0
   ) |
     xargs -0 perl -CSD -ne 'printf "%s:%d: %s\n", $ARGV, $., join " ",
         map { sprintf "U+%04X", ord } m/'"$GLYPH"'/g if m/'"$GLYPH"'/;
       close ARGV if eof;'
   ```

   The first `find` lists its paths literally and on one line: a repository's own glyph check may read its scope from that line, so keep it that shape, and keep `-type f` off the second `find`, since that check counts every `find … -type f` line and refuses to run on two. Where a repository has no `docs/` or no `README.md`, `find` says so on stderr and sweeps the rest; that line means the path was skipped, nothing more. `CLAUDE.md` is looked up by name instead of listed, because the sync removes it from most repositories, and where it is absent the second `find` prints nothing.

   `U+FE0F` is in that class deliberately. Omit it and a body that renders clean still fails a byte comparison.

   The positive arm proves the expression matches. The negative arm proves it is not matching everything. **Neither proves the inputs were the ones you meant**: check that the files you swept are non-empty and are the files you intended, because a sweep over nothing reports clean. A zero from a sweep of prose is not a result until something known-present has been found: count a character the corpus certainly contains, an em dash or a backtick, and confirm it is non-zero before trusting the count you actually care about. A detector that cannot see is indistinguishable from a document that is clean, and the second is what a zero will be read as.

   **Print the denominator BEFORE the scan, never after.** A count printed beside a result is the standard defense against a silently empty sweep, and a crash defeats it, but only in one of the two orders:

   ```
   denominator BEFORE   crash leaves `files=20` with no findings   -> visibly incomplete
   denominator AFTER    dies with everything else                  -> looks clean
   ```

   Same habit, opposite value, decided entirely by line order.

   **It is `perl -CSD` rather than `grep -P`, for the reason [`long-running-commands`](long-running-commands.md) gives about `timeout`: the macOS default is not the GNU one.** `/usr/bin/grep` there is BSD grep, which has no `-P`: it exits 2 and writes nothing to stdout, so a sweep whose stderr is redirected reports exactly the clean result this rule exists to distrust. A shell that resolves `grep` to something else, as some do, hides that from whoever writes the command. `perl` is present on macOS by default and its `\x{...}` escapes need no external engine. The pattern is defined once and reaches both the controls and the sweep, so the arms cannot pass against an expression the sweep does not use.

   **And the `-CSD` is not optional: validate the harness, not only the expression.** The failures here are not wrong patterns; they are correct patterns that never met their input:

   ```
   perl -ne '/[\x{2600}-\x{27BF}]/'        U+26A0 -> 0   the regex is CORRECT
   perl -CSD -ne '<the same regex>'        U+26A0 -> 1
   ```

   Without `-C`, perl reads UTF-8 as separate Latin-1 bytes, so `\xe2\x9a\xa0` never lands in the range, and every `0` that check reported meant *"the detector cannot detect"* rather than *"none present"*. A pattern proven correct, then run through a harness that never decodes its input, is exactly as blind as no check at all, **and the passing control is what makes it feel covered**. A control validates one layer and says nothing whatever about the layer beneath it.

   **So "run it against a known-bad file" is not sufficient on its own**: that remedy inherits the same defect. A fixture written with `printf '\uXXXX'` under a shell that does not emit the character scans **clean**, and reads as the exclusions working. Verify the fixture carries the bytes you think it does, and that the interpreter can read the path at all, before trusting either a hit or a miss:

   ```
   layer 1  fixture contains the character, checked as BYTES   e2 9a a0 ef b8 8f
   layer 2  the path is readable by THIS interpreter           MSYS /tmp is not, to Windows Python
   layer 3  the expression fires on bad, silent on clean
   ```

   **This sweep reads files only.** Commit messages, and issue and pull-request bodies and comments, are in scope and are not on disk, so they need a separate pass, and one written carelessly fails in a way both arms above will miss. A body is multi-line; reading a list of them with `read -r` takes the first line of each and silently discards the rest. Both controls can pass and the denominator can match the count of bodies, because neither control tests the **record boundary**: only the expression. The check to run on a single draft before posting it is in [`writing-comments`](../skills/writing-comments/SKILL.md).

3. **Print code points, never the character.** A script that reports its findings by echoing the glyph crashes on exactly the platform the rule exists to protect, and reports the crash as a short result. An earlier draft of the audit printed the offending character. On Windows (measured in `uams-statamic`) that audit *detected* the glyph and then died printing it: `UnicodeEncodeError: 'charmap' codec can't encode characters in position 16-17: character maps to <undefined>` under a `cp1252` stdout, so the check crashed at the moment it had something to say, which is the failure this rule exists to describe, reproduced by the tool meant to prevent it. A code point written as `U+%04X` is ASCII and cannot fail that way. Naming the code point also follows the same guidance this rule gives for prose below: exact, searchable, and inert.

4. **Existing prose is not yours to sweep.** Rules, skills and docs written before this rule use emoji as their own formatting. Removing them is a separate, deliberate change with its own ticket, not something a passing edit does, and not something to do file-by-file as you touch them, which produces a corpus that is half-converted and consistent nowhere.

## What this does not cover

- **A verbatim quotation of a string that contains one.** A quote is evidence, and altering it to satisfy this rule is a worse defect than the quote. Where the glyph is the thing being identified (a forbidden footer, a callout being deprecated) prefer naming its code point (`U+1F916`) over reproducing it, which keeps the reference exact, searchable, and inert.

- **Anything outside a durable record.** Terminal replies, scratch files, and out-of-band coordination traffic are not covered.

- **Third-party and fixture files.** `vendor/`, and test fixtures that reproduce someone else's data, are not this repository's prose. Do not sweep them, and do not "fix" a fixture: its content is the test.

## The conflict this rule creates, where a release path exists

**Where the repository publishes releases, this section applies and is not to be deleted.**

[`writing-release-notes`](../skills/writing-release-notes/SKILL.md) **mandated** a breaking-change callout prefixed by a warning glyph, and the release-notes generator hard-coded it, so the emoji was emitted by code rather than typed. Published releases carry it.

Adopting this rule therefore required a decision on the release path, and the options were not equivalent: change the skill and the generator and accept that new releases read differently from old ones; or carve the release callout out permanently and say so here, in which case the generator keeps it and nothing needs touching. **Do not resolve this silently in either direction**: a generator edit that changes published-artifact formatting is not a formatting change, and a rule with an unstated exception is one people learn to skip.

**Decided: the glyph is removed from the release path, and this rule takes no exception.** The skill mandates `**Breaking change**: <impact and required action>` and the generator emits it; the callout is words. New releases therefore read differently from published ones, which is accepted rather than mitigated: the alternative was worse, and release notes are not compared side by side for formatting.

**Rejected: carving the release callout out permanently.** It would have cost nothing to implement and it fails on its own terms. A rule with an unstated exception is one people learn to skip, and this exception could not have stayed unstated: it would have had to be repeated wherever the release vocabulary appears. The rule's own rationale is that the glyph is not encodable in `cp1252` and kills tooling that touches it; that argument applies to a generator writing to a redirected stdout at least as strongly as to an author typing prose, so exempting the one place the emission is automatic inverts the reason for the rule. The callout is also this rule's own worked example: the `--breaking` run that died under a Windows code page died on that glyph.

**Already-published release notes keep their glyph.** This decision governs what is written from here; rewriting published artifacts is a separate act and is not licensed by it (`UAMS-Web/uams-statamic#2190`).

## The second conflict: the check mark in a pull-request verification list

[`writing-pull-requests`](../skills/writing-pull-requests/SKILL.md) used to document that items in a `## Verification` or `## Test plan` list "may end with a literal `U+2713` check mark", and pull-request bodies already in the repository use it. This rule forbids emoji in PR bodies, so the two disagreed, and adding this rule's pointer to that skill is what made the disagreement visible in one file.

**The breakage half of the rationale does cover it.** `U+2713` is not encodable in `cp1252`, measured the same way as the warning glyph: it raises `UnicodeEncodeError` on a Windows stdout sized to the console code page, so a generator emitting it dies exactly as the release-notes generator did. It is a text symbol rather than a colored emoji, which is why it reads as exempt and is not.

**Decided: the ban takes no carve-out, and `writing-pull-requests` stops prescribing the check mark.** A verification item carries its result in the line (`all eight jobs passed`), a `[x]` task-list box, or the word `verified`.

**Rejected: carving `U+2713` out of the ban.** It is the arm that preserves existing bodies, and it fails for the reason the release callout failed: an exception written into the rule for the one character people actually reach for is an exception that swallows the rule. The argument for it was that a check mark in a test plan is a data marker rather than decoration; it does not survive contact with what a test plan is for, since every line in one is already a claim of verification, so the mark adds emphasis rather than information, and the line reads better carrying its result instead. A pull-request body is precisely the durable record this rule exists to protect: an exception there would swallow the main case. Also rejected: *scope the exception to test plans only*: the most-copied convention in the corpus is the worst place for a carve-out; and *record the conflict as undecided*: that leaves a corpus contradicting itself, and the question is small enough to answer.

**Already-published PR bodies keep their check marks.** This decision governs what is written from here; rewriting published records is a separate question and nobody is editing anything.

## The third conflict: the attribution footer

**Decided: pull-request and issue bodies carry no attribution footer at all, so the footer's `U+1F916` never reaches a durable record, and this rule takes no carve-out for it.** This overrides any attribution convention supplied from outside the repository, including one a session's own environment provides.

- **No occurrence is permitted.** There is no single-exception carve-out, by name or by code point, in a pull-request body or anywhere else.
- **Do not add it.** Bodies merged before the decision still carry it; the decision is forward-looking, and a retrospective sweep of them is a separate question.
- **An audit expects ZERO.** A superseded form of this guidance told a sweep of pull-request bodies to expect exactly one hit each and to investigate only a *second* one. Once the footer was retired that guidance inverted: it made a body still carrying `U+1F916` the expected case, so a sweep run against it would report clean over the very thing the rule bans. Any occurrence is a violation.

## The DRY line

This file is the standing statement on **emoji in durable records**. It composes with [`impersonal-voice-in-github-artifacts`](impersonal-voice-in-github-artifacts.md) and [`coordination-plumbing-stays-out-of-artifacts`](coordination-plumbing-stays-out-of-artifacts.md), which govern different leaks in the same sentences. [`writing-comments`](../skills/writing-comments/SKILL.md) points here for comments and carries the draft check, since this file's sweep reads files rather than bodies. Where a body's sections and vocabulary come from belongs to the [`writing-issues`](../skills/writing-issues/SKILL.md), [`writing-pull-requests`](../skills/writing-pull-requests/SKILL.md), [`writing-commits`](../skills/writing-commits/SKILL.md) and [`writing-release-notes`](../skills/writing-release-notes/SKILL.md) skills; this rule constrains their output rather than restating them. Bounding a long audit belongs to [`long-running-commands`](long-running-commands.md). The general form of the two-sided control (prove a sweep could have found something before trusting that it found nothing) is [`an-empty-result-is-not-evidence`](an-empty-result-is-not-evidence.md).
