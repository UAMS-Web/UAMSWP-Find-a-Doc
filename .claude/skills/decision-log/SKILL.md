---
name: decision-log
description: >-
  A skeleton `docs/DECISIONS.md` (the cross-ticket decision log the `design-decision-forks`
  rule says a closed decision lands in), an order checker for it, and the `.gitattributes`
  line the log needs. The checker fails, naming both lines, when two adjacent entries are out
  of oldest-first order, and when an entry heading is not preceded by a blank line. Activate
  when a repository adopts `design-decision-forks` and has no decision log, when wiring the
  order check into a pre-merge check, when the check fails, or when a merge of the log
  welded two entries together.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/decision-log/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# The decision log

`design-decision-forks` sends every closed decision to `docs/DECISIONS.md` and describes what an entry says. It does not say what the file looks like. This skill ships that shape, so a repository adopting the rule copies it instead of inventing one.

## Adopting it

1. Copy `.claude/skills/decision-log/templates/DECISIONS.md` to `docs/DECISIONS.md`. The skeleton holds the opening section and the entry format and no entries, and names no repository.
2. Add the `.gitattributes` line below.
3. Wire the check into the pre-merge check, in the same change as the first entry (see Running the check).

The sync never writes `docs/` or `.gitattributes`, so both are the repository's own after the copy.

## The convention the check enforces

- An entry heading is `## YYYY-MM-DD`, a space and a short title.
- Entries run oldest first, so a new entry is appended to the end of the file. Appending is what writing an entry naturally does, and a convention that forbids it drifts: one repository found six entries out of order before anyone looked, then reversed its order to match the action.
- One blank line sits above every entry heading.
- A fenced block is not an entry. The skeleton shows the entry shape inside a fence.

An existing log that is dated newest first, undated, or keyed by number is not migrated by this skill. The checker reads only `## YYYY-MM-DD` headings; a log in another shape keeps its own check.

## The `.gitattributes` line

```
docs/DECISIONS.md merge=union
```

Two branches that each append an entry collide at the end of the file, and neither is wrong. `merge=union` resolves that without a conflict by keeping both sides, ours then theirs. It has two costs, and the check exists for both:

- It does not order entries. Ours-then-theirs can put a newer entry above an older one.
- It drops the blank line at the seam, so the next heading lands directly under the last line of the entry above.

It also does not always apply. Git reads `.gitattributes` from the tree of the checkout performing the merge, so syncing `main` into a branch cut before the line landed runs without the driver and conflicts like any other file. Resolve that conflict by keeping both entries, which is what union would have done. GitHub's server-side merge never honors the attribute, so a collision shows on the forge as a conflicted pull request until the branch is synced locally and pushed. The check runs wherever it is invoked, so it sees the merge result that happened, not the one that was assumed.

## Running the check

```sh
node .claude/skills/decision-log/scripts/check-decision-log-order.mjs
```

It reads `docs/DECISIONS.md` under the working directory. `--file <path>` reads another path, so a revision other than the checkout can be checked from an extracted copy. Any other argument is an error: an ignored argument would read as a report on the file it named. Every outcome names the file it read.

| Exit | Meaning |
|------|---------|
| 0 | Ordered and separated. The output gives the entry count and the date range. |
| 1 | At least one finding. A separator finding names the entry and the line above it; an order finding names both entries and their lines. |
| 2 | The check could not answer: the file is unreadable, it holds no entries, or the arguments were wrong. |

Zero entries is exit 2 and not a pass, because a heading format that stopped matching would otherwise pass on everything. The skeleton alone therefore fails the check, which is why the check is wired in with the first entry.

Read the printed line. A pass says how many entries it read.

## When it fails

- A separator finding: insert one blank line above each heading named. Nothing else changes.
- An order finding: move the named entry, not the dates. An entry's date is when the decision closed. If the entry was recorded late, it keeps its true date and sits where that date puts it.
