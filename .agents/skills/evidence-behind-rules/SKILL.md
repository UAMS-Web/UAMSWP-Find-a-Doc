---
name: evidence-behind-rules
description: >-
  The full text of the largest shared rules as it stood before they were condensed, with the
  measurements, incidents, platform readings and worked examples each rule's directives rest
  on. The rules themselves load in every session and keep only the directives; these files
  load only when read. Activate when a rule links here, when checking why a rule says what it
  says, when a directive seems not to fit a case and the evidence behind it decides, or when
  amending a rule and its record.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/evidence-behind-rules/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# The evidence behind the shared rules

The shared rules load at the start of every session and after every compaction, so they carry the directives and little else. What a directive rests on (the measurement, the incident, the platform it was seen on, the worked example) is kept here, one file per rule, as the full text the rule had before it was condensed (UAMS-Web/uams-claude-skills#356). Nothing here loads until it is read.

| Rule | Its full text and evidence |
| --- | --- |
| [`adversarial-review`](../rule-adversarial-review/SKILL.md) | [`adversarial-review.md`](adversarial-review.md) |
| [`an-empty-result-is-not-evidence`](../rule-an-empty-result-is-not-evidence/SKILL.md) | [`an-empty-result-is-not-evidence.md`](an-empty-result-is-not-evidence.md) |
| [`github-api-budget`](../rule-github-api-budget/SKILL.md) | [`github-api-budget.md`](github-api-budget.md) |
| [`long-running-commands`](../rule-long-running-commands/SKILL.md) | [`long-running-commands.md`](long-running-commands.md) |
| [`pre-merge-check`](../rule-pre-merge-check/SKILL.md) | [`pre-merge-check.md`](pre-merge-check.md) |
| [`worktrees`](../rule-worktrees/SKILL.md) | [`worktrees.md`](worktrees.md) |

## How to read these files

- **The rule governs.** Each file is the rule's earlier full text. Where it and the condensed rule differ, the condensed rule is the instruction and the file is the record behind it.
- **The measurements are dated.** Each names the repository, the platform and the version it was taken on. A reading is evidence for the case it measured; re-measure before relying on it for a different version or platform.
- **The links resolve from here.** A link to another rule goes to the condensed rule, and a link to a repository's `.claude/history/` goes to that repository's own record.

## Changing a rule

Edit the rule under `shared/.claude/rules/` in UAMS-Web/uams-claude-skills, as for any shared rule. A new measurement or incident that supports a directive is added to that rule's file here, in the section it supports, so the rule can cite it with a link rather than carry it.
