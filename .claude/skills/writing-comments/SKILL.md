---
name: writing-comments
description: >-
  Conventions for issue and pull-request COMMENTS: the standing rules a comment has to
  satisfy (no glyphs in durable records, no em dashes, and the impersonal voice every GitHub
  artifact is written in), the check to run on a draft before posting it, and why that check
  prevents rather than enforces. Comments are the largest class of artifact by volume and were
  the last to have no convention at all. Activate when drafting, reviewing, or rewriting any
  comment posted to a GitHub issue or pull request.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/writing-comments/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Writing Comments

Comments are the largest class of artifact a repository produces, and they were the last class to have a convention. The rules below already governed them; nothing pointed a writer at them together.

That is the whole reason this exists. It carries no rules of its own -- each is a standing rule with its own file, and this points at them rather than restating them.

## The rules a comment has to satisfy

- **[`no-emoji-in-durable-records`](../../rules/no-emoji-in-durable-records.md)** -- a comment is durable prose and carries no emoji or pictographic glyphs. A mark becomes the result it stood for: `all nine jobs passed` rather than a glyph asserting the same thing with less information.
- **[`no-em-dashes`](../../rules/no-em-dashes.md)** -- no em dash (`U+2014`). Use a colon, commas, parentheses, or two sentences.
- **[`impersonal-voice-in-github-artifacts`](../../rules/impersonal-voice-in-github-artifacts.md)** -- every artifact posts under one account, so first person reads as that account narrating its own work and second person reads as it addressing itself. Remove the narrator, and the vouching stance with it.

**The glyph ban and the impersonal voice were reached by comments before this file existed**, which is the argument for one document rather than three. `impersonal-voice-in-github-artifacts` recorded comments as the largest class no skill covered; the glyph ban then reached the same class. The em-dash ban (`no-em-dashes`) joins them so synced prose cannot contradict targets that already forbid `U+2014`.

## Check a draft before posting it

Write the comment to a file -- which [`github-api-budget`](../../rules/github-api-budget.md) already requires, since `-F body=@file.md` is the only shape that survives backticks and `$` -- and sweep the file:

```bash
GLYPH='[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE0F}]'

# BOTH arms first. An expression that cannot match and a clean draft read identically.
printf 'x \xe2\x9a\xa0 y\n' | perl -CSD -ne '$n++ if /'"$GLYPH"'/; END{print $n+0, "\n"}'   # must print 1
printf 'plain text\n'       | perl -CSD -ne '$n++ if /'"$GLYPH"'/; END{print $n+0, "\n"}'   # must print 0

perl -CSD -ne 'printf "%s:%d: %s\n", $ARGV, $., join " ",
    map { sprintf "U+%04X", ord } m/'"$GLYPH"'/g if m/'"$GLYPH"'/' comment.md
```

**It is `perl` rather than a repository's own guard script, and that is a limitation rather than a preference.** A guard that sweeps the working tree against the scope the rule names does not read a draft that is not in the tree. Until one grows a way to scan a single file, the expression above is the one that reads a draft.

**Findings are code points, never the character** -- a script that echoes the glyph crashes on exactly the platform the ban exists to protect, and reports the crash as a short result.

## This prevents. It does not enforce.

**Nothing can refuse a comment.** GitHub has no pre-receive hook for one; every comment is written straight to the API by whoever posts it. So a convention is the only thing that acts *before* a comment exists, and it acts only when it is remembered.

**Do not read a clean draft as a guarantee.** The check above reads what you wrote, not what you are about to paste.

## The DRY line

This file owns **the conventions a comment is held to**, and owns no rule of its own. The glyph ban is [`no-emoji-in-durable-records`](../../rules/no-emoji-in-durable-records.md)'s, the em-dash ban is [`no-em-dashes`](../../rules/no-em-dashes.md)'s, and the voice is [`impersonal-voice-in-github-artifacts`](../../rules/impersonal-voice-in-github-artifacts.md)'s; what goes in an issue or pull-request *body* belongs to [`writing-issues`](../writing-issues/SKILL.md) and [`writing-pull-requests`](../writing-pull-requests/SKILL.md), and a commit message to [`writing-commits`](../writing-commits/SKILL.md). Passing a body from a file rather than inline is [`github-api-budget`](../../rules/github-api-budget.md)'s. Don't restate any of them.
