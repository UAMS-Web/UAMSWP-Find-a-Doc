---
name: download-archive-check
description: >-
  Check that a GitHub download of a WordPress plugin, theme, vendor fork or mu-plugin holds only
  what the plugin runs. The bundled check-download-archive.mjs runs git archive on HEAD with
  --worktree-attributes, lists the archive, prints how many files it holds, and exits 1 naming
  every synced agent folder, AGENTS.md, docs, tests, scripts, lock file or tool configuration it
  finds. Covers writing the .gitattributes keep-list, running the check from the local CI runner
  or an Actions workflow, and the exit statuses. Activate when adding a folder to a WordPress
  repository, when editing .gitattributes, when the sync adds files to a WordPress repository,
  or when asked what a plugin download contains.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/download-archive-check/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): wordpress-plugin.
-->
# Download archive check

A WordPress deploy tool installs a plugin from the GitHub download, which is `git archive` of the tagged commit. Everything the repository holds is in it unless `.gitattributes` leaves it out, including the folders the sync writes into every target. The [`keep-the-download-to-what-runs`](../../rules/keep-the-download-to-what-runs.md) rule says why and shows the keep-list form; this skill is the check that the keep-list works.

## Run it

```bash
node .claude/skills/download-archive-check/check-download-archive.mjs
```

It needs `git` and Node 20 or newer, nothing else, and reads the archive itself, so it behaves the same on macOS, Linux and Windows. Output, with the file count first so an empty archive is never read as a clean one:

```
archive: 5 file(s)
  not for the download: .claude/r.md
  not for the download: AGENTS.md
  not for the download: docs/d.md
FAIL: 3 of 5 file(s) in the download are not what the plugin runs; add a keep-list to .gitattributes (see the keep-the-download-to-what-runs rule)
```

| Exit | Meaning |
| --- | --- |
| `0` | The archive holds nothing that is not the plugin. |
| `1` | At least one offending path; each is named. |
| `2` | The check could not tell: not a repository, `git archive` failed, or the archive is empty (an empty archive is what a keep-list with no runtime lines produces, and reads the same as a broken one). Read the status, not only the output. |

**What counts as offending.** At the top level: the folders `.claude`, `.agents`, `.cursor`, `.github`, `.githooks`, `.vscode`, `.idea`, `docs`, `tests`, `test`, `scripts` and `node_modules`; and the files `AGENTS.md`, `CLAUDE.md`, lock files, `package.json`, `phpunit.xml*`, `pint.json`, `rector.php`, `phpstan.neon*`, `local-ci.json`, `cspell.json`, `project-words.txt`, `.gitattributes`, `.gitignore`, `.editorconfig`, `.git-blame-ignore-revs` and `.env*`. A path deeper in the tree is never flagged on its name alone: `includes/docs/` is the plugin's own. `vendor/` is not on the list, because a plugin may ship its Composer autoloader.

**A path shipped on purpose** is named with `--allow <path>` (repeatable, a folder or a file), for example `--allow scripts` for a plugin whose `scripts/` is runtime code. The allowed files still count in the total.

**The keep-list is read from the working tree** (`--worktree-attributes`), so a `.gitattributes` edit is checked before it is committed. The archive is of `HEAD`, so a new runtime file must be committed to appear in it.

## Wire it in

**A private repository** runs the shared local CI runner, and the `download-archive` job is already in the template it copies, `.claude/skills/local-ci/templates/local-ci.json`:

```json
{
  "name": "download-archive",
  "run": "node .claude/skills/download-archive-check/check-download-archive.mjs",
  "always": true,
  "requires": ["bin:node", "bin:git"]
}
```

A repository whose `local-ci.json` predates the job adds the block above. `always` because the keep-list is read from `.gitattributes` and the archive from every file: a change to either, or a new top-level folder, can break it. A repository that has not written its keep-list yet fails the job until it does, which is what the adoption ticket asks for.

**A public repository** runs Actions, not local CI. Copy `.claude/skills/download-archive-check/templates/download-archive.yml` to `.github/workflows/download-archive.yml`. It runs the same script on every pull request and on pushes to `main` or `master`. It is a copied template and not a workflow that other repositories call. The `synced-files` skill's `check-workflow-copies.mjs` fails when the copy differs from the template outside its header.

Both forms are exercised end to end by `scripts/download-archive-job.test.mjs` in UAMS-Web/uams-claude-skills: it syncs a real WordPress target into a scratch checkout, runs the delivered job through the delivered runner, and runs the delivered workflow's step, each against a repository with no keep-list (exit 1) and one whose keep-list leaves only the plugin file (exit 0). The workflow's own run on Actions is not exercised there.

## When it fails

- **A synced or project path is listed:** the keep-list is missing or does not cover it. Write the keep-list from the rule, with one `-export-ignore` line for each path the plugin runs.
- **A runtime path is missing from the download after a keep-list is added** is not caught by this check, which looks for extra files, not missing ones. Install the download on a test site, or list the archive with `git archive --worktree-attributes HEAD | tar -t`, before tagging a release.
- **Exit 2 with an empty archive:** the keep-list has no `-export-ignore` lines for the runtime paths, or they name paths that do not exist at the top level.

## Tests

`check-download-archive.test.mjs`, which runs in UAMS-Web/uams-claude-skills and is not synced to targets, builds fixture repositories in a temporary directory: one with no keep-list (the archive holds `.claude/` and `docs/`), one whose keep-list leaves the plugin file and `includes/`, a deny-list that misses a later folder, an archive path long enough to need a pax extended header, an empty archive and a directory that is not a repository.
