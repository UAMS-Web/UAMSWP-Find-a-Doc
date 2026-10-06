---
name: synced-files
description: >-
  The rules and skills under `.claude/`, `.agents/` and `.cursor/` that UAMS-Web/uams-claude-skills
  syncs into this repository are not edited here: each one's sha256 is recorded in
  `.claude/sync-manifest.jsonc`, and the bundled `check-synced-files.mjs` fails, naming the file,
  when a copy no longer matches. Covers which files are managed, where a change to one belongs,
  how to run the check, and what each exit status means. Activate before editing any file under
  `.claude/rules/`, `.claude/skills/`, `.agents/skills/` or `.cursor/rules/`, when the
  synced-files check fails, or when asked why a hand edit to a rule was reverted.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/synced-files/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Synced files

The shared sync from UAMS-Web/uams-claude-skills writes the same rules and skills into this repository two or three times: `.claude/rules/`, `.cursor/rules/*.mdc`, `.agents/skills/rule-*`, and `.agents/skills/` beside `.claude/skills/`. Every harness reads its own copy, so a copy edited by hand makes them read different instructions, and the next sync pull request quietly reverts the edit.

## Which files are managed

Every path listed under `files` in `.claude/sync-manifest.jsonc`. The sync records a sha256 for each one under `hashes`. A file the manifest does not list belongs to this repository, even inside `.claude/`, and the sync leaves it alone. So does `AGENTS.md` outside its generated section.

## Where a change belongs

Make it in UAMS-Web/uams-claude-skills, under `shared/`. The next sync pull request delivers it here, to every copy at once. A rule this repository needs and no other does goes in a `when: repo=` block in the shared source, or in a file of this repository's own that the manifest does not list.

## Running the check

From the repository root:

```sh
node .claude/skills/synced-files/scripts/check-synced-files.mjs
```

`--root <dir>` checks another checkout.

Where the repository runs the shared `local-ci` runner, its `local-ci.json` should carry a `synced-files` job that runs this command on every run. The template the `local-ci` skill ships includes it. A `local-ci.json` copied before the job was added does not get it from a sync, because the sync never writes that file: copy the job in from the template.

| Exit | Meaning |
|------|---------|
| 0 | Every managed file matches its hash. The output says how many were checked. |
| 1 | At least one managed file was changed, is missing, or is not a regular file. Each is named. |
| 2 | The check did not run: no manifest, a manifest that is not JSON, or one with no hashes. |

A pass is the printed "clean" line, not the exit status alone.

Line endings are normalized before hashing, so a Windows checkout with `core.autocrlf` passes. A change made only of line endings is not reported.

## When it fails

Do not fix it by regenerating anything here. Restore the named file from git to drop the edit, and carry the change to UAMS-Web/uams-claude-skills if it is still wanted. A failure naming `.claude/sync-manifest.jsonc` itself means the manifest was edited; restore it the same way.
