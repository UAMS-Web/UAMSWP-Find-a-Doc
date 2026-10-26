<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/keep-the-download-to-what-runs.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): wordpress-plugin.
-->
# Rule — keep a WordPress download to what the plugin runs

A GitHub download of a WordPress plugin or theme (the zip or tarball, which is what a deploy tool such as WP Pusher installs) carries **every file in the repository** unless `.gitattributes` leaves it out. That includes the `.claude/`, `.agents/` and `.cursor/` folders and `AGENTS.md` that the sync writes into this repository, and `docs/`, `tests/`, `scripts/`, lock files and tool configuration. Where a server installs the download and serves the plugin folder for every file type except PHP, those files are public: rule text, internal ticket references, and anything else a folder holds.

## The keep-list

Each repository lists what the plugin runs in `.gitattributes`, as a keep-list rather than a deny-list:

```gitattributes
/* export-ignore
/.* export-ignore
/includes -export-ignore
/admin -export-ignore
/assets -export-ignore
/my-plugin.php -export-ignore
```

The first two lines exclude every top-level entry, dot-entries included. Each `-export-ignore` line after them puts one runtime path back, a folder (everything under it comes with it) or a file. The runtime paths differ per repository (`includes/`, `admin/`, `config/`, `assets/`, `languages/`, `vendor/` where the plugin ships its Composer autoloader), so the sync cannot write the list: the repository owns it.

## Why a deny-list is not enough

A deny-list (`/docs export-ignore`, `/.claude export-ignore`) covers the folders someone thought of. The next folder added, by the sync or by hand, ships in every download until someone notices. A keep-list fails the other way: a new runtime folder is missing from the download, which is a bug that shows on the first install, not a disclosure that shows nowhere.

## The check runs on every change

The keep-list is checked, not trusted. The `download-archive-check` skill's script runs `git archive` on `HEAD` with `--worktree-attributes`, lists what the archive holds, and exits non-zero naming each path that is not the plugin: a synced path, `docs/`, `tests/`, `scripts/`, a lock file, tool configuration. It prints the number of files in the archive first, so an archive that came out empty is not read as a clean one. Run it from the local CI runner where the repository has one, and from an Actions workflow in a public repository (the skill shows both). A runtime folder added later needs its `-export-ignore` line in the same change, and the check is what notices when it does not.

## What this does not cover

- **A `git clone` install** ships the whole working tree whatever `.gitattributes` says. Covering it takes a server rule that refuses non-PHP files in plugin folders, which is a hosting setting outside every repository.
- **A repository that is not WordPress code.** A Statamic add-on installs into `vendor/`, outside the application's web root, so `export-ignore` there would only trim the Composer download.

## The DRY line

This file is the standing statement of **what a WordPress download holds**. How the check runs, its options and the job and workflow snippets are in the [`download-archive-check`](../skills/download-archive-check/SKILL.md) skill. Which synced folders reach a repository is the sync's: [`synced-files`](../skills/synced-files/SKILL.md) covers editing them, and this rule only decides that none of them ships in the download.
