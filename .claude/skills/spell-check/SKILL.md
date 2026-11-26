---
name: spell-check
description: >-
  The shared cspell setup for UAMS-Web repositories: a `cspell.json` with US English and a
  `project-words.txt` project dictionary that the sync creates, an `npm run spell` script that fails
  when it checks no file, and a `spell` job for GitHub Actions. Covers taking the `spell` profile,
  adopting the setup in a repository that already has its own spell check, keeping a repository's
  own words apart from the shared ones, and what to do when the check flags a word. Activate when
  adding or changing a repository's spell check, editing `cspell.json` or `project-words.txt`,
  fixing a `spell` job failure, or deciding whether a flagged word belongs in the dictionary.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/spell-check/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): spell, php.
-->
<!-- cspell:ignore cspell -->

# Spell check: cspell, US English, and a project dictionary

Every repository that takes the `spell` profile (UAMS-Web/uams-claude-skills#173) checks its prose and code comments with cspell, against US English and one project dictionary. The [`american-english-prose`](../../rules/american-english-prose.md) rule says which English and when a dictionary entry is justified; this skill says how the setup is delivered and kept.

## What the sync delivers

Two files at the repository root, both **seeded**: the repository owns each once it exists.

| File | Delivered as | After that |
|---|---|---|
| `cspell.json` | created when the repository has none | never touched again; an existing file is left exactly as it is |
| `project-words.txt` | created when absent, with the shared words | a word a later shared list gains is added in sorted position; a word is never removed, and a word the repository deleted stays deleted |

The shared words are the vocabulary every repository uses and the base dictionary lacks (`Statamic`, `UAMS`, `multisite`, tool names). A word one repository needs stays in that repository's own `project-words.txt`, below or among the shared ones, where a later sync leaves it alone. A repository that takes the `spell` profile on top of its own spell check keeps its own `cspell.json` untouched and receives only the shared words it lacks.

A repository that takes neither the `spell` profile nor `php` receives none of this, and nothing else changes for it. The `php` profile carries this skill and both seeds (UAMS-Web/uams-claude-skills#887), so a plugin or theme repository receives them without a `spell` entry of its own. The sync still never writes `package.json`, `scripts/` or a workflow job, so a repository with no `package.json` receives the skill and two root files that nothing reads until it copies the three things below. A repository that takes the profile and sets `"shipSeeds": false` in `manifest.json` receives neither file. The `synced-files` check does not hash either file, since a repository is expected to change both.

## What a repository copies

The sync never writes `package.json`, `scripts/` or `.github/`, so three things are copied once, from `.claude/skills/spell-check/templates/`:

| Template | Copy to | Notes |
|---|---|---|
| `spell.mjs` | `scripts/spell.mjs` | Runs cspell and fails when it checked no file. `MINIMUM_FILES` is 1; raise it to a figure well below the repository's real count. |
| (a `package.json` line) | `package.json` | Add `"spell": "node scripts/spell.mjs"` under `scripts` and `cspell` under `devDependencies`, then `npm install`. |
| `spell-job.yml` | `.github/workflows/ci.yml`, under `jobs:` | A snippet, not a workflow. Needs no PHP, so it stands outside the `needs:` chain. |

The local CI runner's `spell` job (`local-ci.app-jobs.json`) runs the same `npm run spell`, so the local gate and the Actions job agree.

`npm run spell` exits 0 when cspell passed over at least `MINIMUM_FILES` files, 1 when it reported a word, and 2 when it cannot be trusted: cspell is not installed, it printed no count, or it checked too few files. A glob that stops matching makes cspell report `Files checked: 0`, which on some versions and flags exits 0; the script reads the count itself instead of trusting the exit status.

## Adopting it in a repository that has its own spell check

1. A repository that lists `php` already receives the setup. Otherwise add `spell` to its `profiles` in `shared/manifest.json` (a change in UAMS-Web/uams-claude-skills) and sync. An existing `cspell.json` and `project-words.txt` are kept; the shared words the file lacks are added.
2. Compare the repository's `cspell.json` with the template and bring over what it lacks: `"language": "en"`, the `project-words` dictionary with `"addWords": true`, and the `ignorePaths` for `vendor/`, `node_modules/`, built assets, lock files and the three trees the sync writes (`.claude/`, `.agents/`, `.cursor/`). Those three are ignored because their spelling is checked at the source in UAMS-Web/uams-claude-skills; a repository that keeps its own skills or rules there narrows the entry. The template's comments say why each is there.
3. Copy `spell.mjs` and the `package.json` line, unless the repository already has a script that fails on zero files.
4. Run `npm run spell`. Fix each flagged word in the prose before adding it to a dictionary.

## Keeping a repository's words apart from the shared ones

- **Shared words** are for vocabulary every repository writes. They live in `skills/spell-check/templates/project-words.txt` in UAMS-Web/uams-claude-skills and reach a repository through the sync.
- **A repository's own words** go in its own `project-words.txt`, in sorted position. The sync adds shared words around them and never reorders or removes them.
- **A word only some files may use** (a quoted rejected form, a tool's own spelling, an identifier fragment) goes in an `overrides` entry in `cspell.json` keyed to those files, with the reason in a comment, never in `project-words.txt`, which admits it everywhere.
- **A word removed from the shared list** stays in a repository that already received it. Removing it there is that repository's own change.

## When the check flags a word

Reword first. A genuine misspelling is corrected, a British spelling is changed to the American one, and a dictionary entry is the last resort and needs a stated reason (see the `american-english-prose` rule). The `<!-- cspell:ignore word -->` comment at the top of a Markdown file scopes a word to that file when the word is the file's subject.
