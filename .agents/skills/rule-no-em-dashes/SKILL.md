---
name: rule-no-em-dashes
description: "No em dashes in prose, docs, comments, or strings."
disable-model-invocation: true
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/no-em-dashes.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — no em dashes in prose, docs, comments, or strings

**Do not use the em dash** (Unicode `U+2014`) as punctuation in prose. Not in issue or pull-request titles, bodies, or comments; not in commit messages; not in docs, skill or rule prose (outside the carve-outs below), code comments, or string literals. Introduce with a colon, set off an aside with commas or parentheses, or split into two sentences.

## Carve-outs

Two title forms keep a single em dash with a space on either side. Do not spread the character into the body under the title.

1. **Shared rule H1s:** `# Rule — <imperative title>` (see [`shared/README.md`](../../../README.md)). The sync strips the `Rule —` prefix to build the Cursor/Codex description.
2. **GitHub Release titles:** `vX.Y.Z — Theme` (see [`writing-release-notes`](../writing-release-notes/SKILL.md)). Examples and generator output that follow that form may contain `U+2014` in the title only.

## Why this is a standing order

**Three repositories already treat a whole-tree ban as non-negotiable** (`uamswp-mcp`, `roll-call`, `business-card-portal`), and the shared skills sync into every target. Shared prose that used em dashes as clause punctuation made the first sync fail whole-tree greps (for example `uamswp-form-marshal-policy`'s CI) and contradicted those house rules wherever the copies landed. One shared rule keeps ordinary prose clean while preserving the title conventions the repositories already ship.

**The character is easy to type and hard to search for.** Editors and models insert it as "smart" punctuation; grepping for a hyphen does not find it. Naming the code point (`U+2014`) keeps references exact when you are not writing one of the carved-out titles.

## How to apply

1. **Prefer plain ASCII punctuation in prose.** A colon for an elaboration (`Title: detail`). Commas or parentheses for an aside. A period and a new sentence when the break is hard.
2. **Rule H1s keep the em dash:** `# Rule — finish a ticket where it is visible, not just where it builds`.
3. **Release titles keep the em dash:** `vX.Y.Z — Theme`. Never a hyphen or a colon in that slot.
4. **When identifying the ban outside those titles, name the code point** (`U+2014`) rather than pasting the character into ordinary prose.
5. **Audit with a positive and a negative control** before trusting a clean sweep of prose (allow listed title examples when sweeping rules or `writing-release-notes`):

   ```bash
   EM=$'\u2014'
   count() { perl -CSD -ne '$n++ if /\x{2014}/; END{print $n+0, "\n"}'; }

   printf 'x %s y\n' "$EM" | count   # must print 1
   printf 'plain text\n' | count     # must print 0
   # then sweep the files under review
   ```

6. **Existing first-party prose in a target that still carries `U+2014` outside these titles is not yours to rewrite on sight.** Clearing it is a deliberate change on that repository's ticket. Synced shared files must stay clean except for these carve-outs.

## What this is not

This is not a ban on the hyphen-minus (`-`), the en dash (`U+2013`) in numeric ranges where a repository already uses them, or ASCII `--` in flags and Conventional Commit footers. It is specifically the em dash `U+2014` used as clause punctuation. The two title forms above are the only approved uses in shared and synced text.
