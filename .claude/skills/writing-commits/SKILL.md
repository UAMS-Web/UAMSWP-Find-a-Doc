---
name: writing-commits
description: >-
  Project commit message conventions: Conventional Commits prefix (`feat:`, `fix:`, `docs:`,
  `refactor:`, `perf:`, `style:`, `test:`, `build:`, `ci:`), 50-character subject limit
  including the prefix, no forced body line wrapping, single blank line between paragraphs,
  unordered `-` bullets with nested indented bullets, inline code markup for technical terms,
  and the final commit message presented in a Markdown code block. Also covers what to stage: 
  explicit paths, never `git add -A`. Activate whenever drafting, rewriting, or critiquing a
  commit message, including in-place edits, PR descriptions derived from commit messages, or
  rewording an existing commit: and whenever staging changes for a commit.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/writing-commits/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Writing Commits

This skill defines the standards for commit subject and body so messages stay consistent and
readable across the project. It covers a COMMIT MESSAGE only. What an issue or pull-request body
is held to belongs to [`writing-issues`](../writing-issues/SKILL.md) and
[`writing-pull-requests`](../writing-pull-requests/SKILL.md); what a COMMENT is held to, and the
check to run on a draft before posting it, is [`writing-comments`](../writing-comments/SKILL.md).

## Output format

When proposing a commit message, **present the final message inside a Markdown code block** so
the user can copy it cleanly.

## Subject line (first line)

- Follows **Conventional Commits** (https://www.conventionalcommits.org/en/v1.0.0/).
- Single line, **≤50 characters total**, *including* the Conventional Commits prefix.
- Succinctly describes the change.

### Allowed prefixes

| Prefix | When |
| --- | --- |
| `build` | Changes that affect the build system or external dependencies (e.g. gulp, broccoli, npm). |
| `ci` | Changes to CI configuration files and scripts (e.g. Travis, Circle, BrowserStack, SauceLabs). |
| `docs` | Documentation-only changes. |
| `feat` | A new feature. |
| `fix` | A bug fix. |
| `perf` | A code change that improves performance. |
| `refactor` | A code change that neither fixes a bug nor adds a feature. |
| `style` | Changes that don't affect code meaning (whitespace, formatting, missing semicolons, etc.). |
| `test` | Adding missing tests or correcting existing tests. |

## Body

- **Do not wrap** body text or list items with forced line breaks. Let each paragraph or bullet
  run as one continuous line unless a newline is required for structure (between subject and
  body, between paragraphs, or before a new list).
- Separate paragraphs with a **single blank line**.
- **Unordered lists** with `-` for bullet points, detailing specific changes. Nest sub-items
  with indented `-` bullets directly under their parent. **No blank line** between consecutive
  list items at the same level, and no blank line between a parent and its child.
- Use **inline code markup** (`` `code` ``) for technical terms: class names, field handles,
  file paths, method names.
- Clear, readable explanation of the change, its impact, and any relevant context.
- **No trailing newlines** or unnecessary spacing.
- **Never let a line END in a closing keyword when the next line BEGINS with an issue reference.** A newline is whitespace to GitHub's closing-keyword parser, so the two are read together as a directive nobody wrote, and merging the commit closes that issue. The keyword does not have to be a verb anyone chose: a **status-column table** puts a bare `closed` at the end of one row and the next row's `#N` label directly beneath it:

  ```
  #999998  write the patch                       closed
  #999999  file the bug upstream                 retitled, action-flavor added
  ```

  That is `f12ba37e4` in `uams-statamic`, whose body opens `Refs #2241. Deliberately closes nothing`. It closed `#2241`. **Neither established check sees it**: a body grep cannot match a string that only exists after the join, and `closingIssuesReferences` reads empty for that pull request. Break the adjacency: reorder so the status cell is not last, or write the cell as something other than a bare keyword (`now closed`, `done`). The mechanism, the two blind checks, and why the **pull-request body** rather than the branch commits is the load-bearing input are in [`writing-pull-requests`](../writing-pull-requests/SKILL.md).

## Tone and voice

- **Already impersonal, and stays that way**: an imperative subject and a `-` bullet body carry no narrator, so commits satisfy the [`impersonal-voice-in-github-artifacts`](../../rules/impersonal-voice-in-github-artifacts.md) rule by construction. Keep it that way rather than treating it as exempt.

## Example shape

````markdown
```
feat: add audio-set figure_class hoisting

- Hoist `figure_class` so `wp:audio` plays nicely with the new `align` map
- Update `AudioSetProcessor::toPageBuilderBlock()` to preserve column hints
    - Pulls `align` from the parent block when present
    - Falls back to the previously imported default when not
- Add regression tests under `tests/Feature/AudioSet/` covering both paths
```
````

(Subject ≤50 chars including `feat:`. Body uses `-` bullets, nested children directly under
their parent without blank lines, inline code markup for handles and class names, and (note)
no forced line wrapping: each bullet runs as one continuous line however long it gets.)

## Staging: name the paths, never `git add -A`

**Stage explicitly: `git add <path> <path>`.** `git add -A` / `git add .` / `git commit -a` are wrong here, not as a style preference, but because a working checkout reliably holds files that are not your change, and a blanket add sweeps them into your commit where they are easy to miss in review and hard to attribute later.

What a working checkout tends to hold:

- **`vendor/` and `node_modules/`**: gitignored, but a `composer install` or `npm install` run mid-ticket changes `composer.lock` / `package-lock.json`, which are tracked. Commit those deliberately, not incidentally.
- **Untracked scratch**: a checkout accretes import XML, sample payloads, generated fixtures, and notes over a ticket's life.

So the sequence before every commit:

```bash
git status --short              # read it; know why each line is there
git add <the paths you changed>
git diff --cached --stat        # confirm the staged set is exactly your change
```

The same discipline applies in a sibling repository when a change needs a companion commit there: stage the named files, never the whole tree. (The cross-repo companion-PR flow itself lives in the [`writing-pull-requests`](../writing-pull-requests/SKILL.md) skill.)

