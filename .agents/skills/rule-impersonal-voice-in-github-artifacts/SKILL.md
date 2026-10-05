---
name: rule-impersonal-voice-in-github-artifacts
description: "Write GitHub artifacts in an impersonal voice."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/impersonal-voice-in-github-artifacts.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — write GitHub artifacts in an impersonal voice

Every pull request, issue, and comment in this repo posts under **one account**. The same account authors the change, files the ticket, reviews the branch, and posts the validation comment. So **first person reads as that person narrating their own work, and second person reads as them addressing themselves.** Neither is what the sentence means.

Write the artifact as a statement about runs, files, and tickets. There is no narrator in it.

## Why this is a standing order

The mechanism is structural rather than stylistic: under one account the grammar does not mean what it says. "I verified this" is that account telling itself what it just did, and "you found the reason I did not" addresses nobody.

The rule then sharpened, and the sharpening is the half worth keeping. **Deleting the pronoun is not enough. The vouching stance has to go with it.** "All three defects are confirmed from use" contains no pronoun and still reads as the author confirming their own PR. A rule that stops at `I`/`you` catches the easy half and leaves the sentence that actually misleads.

**Do not reach for a live count of the residue.** `search/issues` cannot phrase-match, so any `"my own"` figure over-counts; and because that endpoint indexes comments, quoting this rule's own example *raises* the number. The mechanics are in the [`github-api-budget`](../rule-github-api-budget/SKILL.md) rule.

## How to apply

### 1. Scope: PR bodies, issue bodies, and PR/issue comments

Bodies are covered by [`writing-pull-requests`](../writing-pull-requests/SKILL.md) and [`writing-issues`](../writing-issues/SKILL.md); comments, the largest class by volume, by [`writing-comments`](../writing-comments/SKILL.md). Each carries a pointer here and owns no rule of its own.

### 2. No `I`, `my`, `you`, or `your`: state the finding as a fact about a run or an artifact

| Instead of | Write |
| --- | --- |
| "I verified X" | "Verified: X" |
| "my #605 run" | "the #605 validation run" |
| "you found the reason I did not" | "the reason recorded here is stronger than the one raised on #707" |

The replacement is almost always shorter, because the pronoun was carrying no information the sentence needed.

### 3. The harder half: remove the confirming narrator, not just the pronoun

**The test: if a sentence implies a party who checked, corroborated, agreed, or was persuaded, it is wrong.** Replace the vouch with the evidence it was standing in for.

| Instead of | Write |
| --- | --- |
| "All three are confirmed from use" | "Each has a matching failure on record: …" |
| "Verified independently" | "Verified: #613 open, #359 closed" |
| "confirms the analysis" | state the fact that does the confirming |

**Praise is the same defect wearing different clothes.** "The sharpest sentence in the PR" reads, under one account, as self-congratulation; and it tells a reader nothing. State what the sentence *establishes* instead. The same goes for "good catch", "excellent point", and any assessment of a contribution's quality. None of them survive the single-account reading, and none of them is load-bearing.

### 4. Impersonal does not mean vague

This rule removes a narrator, never a fact. **Keep every number, path, ticket reference, command, and error string.** "Verified: X" is impersonal; "this was checked" is the same claim with its source removed as well as its pronoun, and it is worse than what it replaced. If deleting the narrator leaves nothing behind, the sentence had no content. Cut it or go find the evidence.

### 5. Two carve-outs, and they are real

- **Repo prose deliberately addresses a reader as "you".** `.claude/**`, `docs/**` and `README.md` are instructions to whoever reads them next, and second person is correct there. **A sweep must not strip it.** This file does it in the sentence you are reading.
- **Verbatim quotations keep their original wording, including "you".** A quote is evidence; altering it to satisfy the rule is a worse defect than the quote. Quote it as written and let the surrounding prose carry the impersonal voice.

## Commit messages are already impersonal

The [`writing-commits`](../writing-commits/SKILL.md) convention (a Conventional-Commits subject in the imperative, a body of `-` bullets describing the change) produces impersonal prose by construction. Nothing needs to change there. **Keep it that way**: a body bullet that starts "I moved…" or "as you can see…" is the same defect arriving through a door nobody was watching.

## The DRY line

This file is the standing statement on **voice in GitHub artifacts**. What a COMMENT is held to, and the check to run on one before posting it, is [`writing-comments`](../writing-comments/SKILL.md). What goes *in* a body (the section inventory, `Closes #N.`, the `## Test plan`, the archetype templates, the label taxonomy, the board fields) belongs to [`writing-pull-requests`](../writing-pull-requests/SKILL.md) and [`writing-issues`](../writing-issues/SKILL.md), and subject/body/staging mechanics to [`writing-commits`](../writing-commits/SKILL.md); each points here rather than restating this. It composes with [`adversarial-review`](../rule-adversarial-review/SKILL.md), whose findings are exactly the sentences most tempted into a vouching stance, and with [`pre-merge-check`](../rule-pre-merge-check/SKILL.md), which requires a written finding before every merge. The sentence where the vouching stance most often creeps in.

