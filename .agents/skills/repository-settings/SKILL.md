---
name: repository-settings
description: >-
  The baseline GitHub settings for a UAMS-Web repository: merge methods and the squash commit
  defaults, branch deletion and update-branch, auto-merge, the feature toggles, Actions
  permissions, the `RUN_REMOTE_CI` variable's reach, the secrets a pull-request workflow needs in
  both stores, Dependabot, the code security configuration and secret scanning, and the rules on
  the default branch, each with its value for a public and a private repository, where it is set,
  and why. Also the open questions the project owner has not decided, the rows the organization
  could set instead of each repository, a checklist for moving a repository to public, and a
  read-only `check-repository-settings.mjs` that prints every departure from the baseline.
  Activate when creating a repository, moving one to public, changing a repository or
  organization setting, auditing settings across repositories, or when asked why a repository
  is set the way it is.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/repository-settings/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Repository settings

Repository settings are not in any repository's tree, so nothing a pull request reviews shows them, and each one is whatever someone last set by hand. The three moves to a new public repository (`UAMS-Web/uamswp-migration-api-archive#465`, `UAMS-Web/wordpress-importer-archive#1244`, `UAMS-Web/uams-statamic-archive#2649`) each read the same settings off the old repository and recreated them one at a time. This skill writes them down once: a baseline, the questions it leaves open, and a checker that reports where a repository departs from it.

The checker reads and never writes. Changing a setting, in a repository or for the organization, is the project owner's decision, and each departure it reports is one to raise, not one to fix on sight.

## Where a setting lives

| Level | What it holds | Read with an ordinary token |
| --- | --- | --- |
| Organization rulesets | `deletion`, `non_fast_forward` and `pull_request` on the default branch (rulesets `24525022` and `24525074`), and the five Statamic add-ons' required checks (ruleset `24965287`), each for the repositories it lists by id | no: `orgs/UAMS-Web/rulesets` needs `admin:org`; `repos/{o}/{r}/rules/branches/{branch}` shows the effective rules |
| Organization code security configurations | Dependabot alerts and security updates, secret scanning, push protection, non-provider patterns and validity checks | yes: `orgs/UAMS-Web/code-security/configurations` |
| Organization Actions settings | whether Actions runs, which actions are allowed, the default workflow token permissions | no: needs `admin:org`; `repos/{o}/{r}/actions/permissions` and `.../permissions/workflow` show the effective values. Every target reads the same values, which is what an organization default produces; the organization setting itself is not confirmed |
| Organization variables and secrets | `RUN_REMOTE_CI`, scoped to private repositories | no: needs `admin:org`; `repos/{o}/{r}/actions/organization-variables` lists the ones that reach a repository |
| Organization `.github` repository | community health files and the default issue and pull-request templates | not read by the checker |
| The repository | merge methods and their commit defaults, branch deletion, update-branch, auto-merge, the feature toggles, its own secrets and variables, `.github/dependabot.yml`, and any ruleset it carries alone | yes |

The organization defaults for code security, read on 2026-10-28 from `orgs/UAMS-Web/code-security/configurations/defaults`:

- **A new public repository** gets **GitHub recommended** (id 17, not enforced). It turns on secret scanning, push protection, non-provider patterns, validity checks and Dependabot alerts, and leaves security updates `not_set`.
- **A new private repository** gets **Dependabot only** (id 281426, enforced). It turns on Dependabot alerts and security updates and turns off all four secret-scanning settings.

Because that configuration is enforced, a repository carrying it refuses a change to secret scanning with `422 An enforced security configuration prevented modifying secret scanning enablement`. The new `uamswp-migration-api` carried it after being created and made public the same day, and refused until an organization owner attached a configuration that includes secret scanning (`UAMS-Web/uamswp-migration-api-archive#468`).

## The baseline

One row per setting the checker reads, in the order it reads them. *Public* and *Private* are the expected values; a row gives one value where they agree. *Set at* says which level from the table above decides it. Read 2026-10-28 across the 51 sync targets in `shared/manifest.json` (19 public, 32 private).

| Row | Public | Private | Set at | Reason |
| --- | --- | --- | --- | --- |
| `allow_squash_merge` | true | true | repository | Squash is the one merge method: see the next two rows. |
| `allow_merge_commit` | false | false | repository; an organization ruleset can enforce it (Proposals) | Each pull request lands as one commit whose message is its title and body. The `pre-merge-check` rule (step 6) and the `github-api-budget` rule read closing keywords and plumbing from that body because it becomes the message. The three moves recreated it exactly. |
| `allow_rebase_merge` | false | false | repository; an organization ruleset can enforce it | As above. |
| `squash_merge_commit_title` | PR_TITLE | PR_TITLE | repository only | The title is what `writing-pull-requests` writes for the release notes and the history. |
| `squash_merge_commit_message` | PR_BODY | PR_BODY | repository only | The body becomes the commit message, which is what the `pre-merge-check` rule's step 6 enumerates and step 7 sweeps. With `COMMIT_MESSAGES` the branch commits land instead, and the body's `Closes #N` is not in the message. |
| `allow_auto_merge` | false | false | repository only | A merge follows the written check in the `pre-merge-check` rule. Auto-merge would merge on green checks alone. Every target reads false. |
| `delete_branch_on_merge` | open question | open question | repository only | See *Open questions*. |
| `allow_update_branch` | true | open question | repository only | Public: on wherever `main` requires checks, so a pull request opened before the checks existed can be brought current from its page (project owner, 2026-10-27, `UAMS-Web/wordpress-wxr-cleanup#12`). Private: see *Open questions*. |
| `web_commit_signoff_required` | false | false | repository; the organization can require it | Commits carry the `writing-commits` footer, and nothing in the shared conventions asks for a `Signed-off-by` line. Every target reads false. |
| `has_issues` | true | true | repository only | Issues are the tracker (`writing-issues`). |
| `has_projects` | true | true | repository only | Every target reads true. The board is the organization's project 1, and whether turning the repository toggle off affects an issue's card there is not measured, so it stays on. |
| `has_wiki` | open question | open question | repository only | See *Open questions*. |
| `has_discussions` | false | false | repository only | Questions go to issues. Every target reads false. |
| `actions_enabled` | true | true | organization Actions settings | Every target reads true. The local CI runner replaces remote runs in a private repository through `RUN_REMOTE_CI`, not by turning Actions off, so a workflow such as `clear-handed-back.yml` still runs. |
| `allowed_actions` | all | all | organization Actions settings | The shared templates use `actions/checkout`, `actions/setup-node`, `actions/cache` and `shivammathur/setup-php`. Every target reads `all`. |
| `default_workflow_permissions` | write | write | organization Actions settings | Every target reads `write`. Each of the seven shared workflow templates declares its own `permissions:`, so the default reaches only a workflow that declares none. See *Proposals*. |
| `can_approve_pull_request_reviews` | true | true | organization Actions settings | Every target reads true. No ruleset requires an approving review (`pre-merge-check`), so an approval from a workflow gates nothing. See *Proposals*. |
| `run_remote_ci` | not visible | visible | organization variable `RUN_REMOTE_CI`, scoped to private repositories | Private repositories validate with the local CI runner and keep their Actions jobs behind this variable (`local-ci`). It does not reach a public repository, whose remote jobs run on every pull request (`UAMS-Web/uamswp-migration-api#7`). |
| `pull_request_secrets` | not used or in both stores | not used or in both stores | repository secrets, or an organization secret | A pull request Dependabot opens reads the Dependabot secrets store, not the Actions one. So a secret a pull-request workflow reads, `COMPOSER_AUTH_TOKEN` for the private submodules and packages, is set in both, or Dependabot's runs fail where everyone else's pass (`UAMS-Web/uams-statamic-archive#2657`). The checker knows only the names in `PULL_REQUEST_SECRETS`. |
| `vulnerability_alerts` | enabled | enabled | organization code security configuration | Both configurations turn Dependabot alerts on. |
| `automated_security_fixes` | enabled | enabled | organization code security configuration, or the repository | Every target reads enabled, and one public target reads it paused, which the checker reports as `paused`. "Dependabot only" turns them on, and "GitHub recommended" leaves them `not_set`, so on a public repository the repository's own setting decides. |
| `dependabot_version_updates` | open question | open question | the repository's `.github/dependabot.yml` (or `.yaml`) | See *Open questions*. The file copies with the tree. |
| `code_security_configuration` | GitHub recommended | Dependabot only | organization code security configuration | The organization's defaults, above. The public one attached on the switch for `wordpress-importer`, `wordpress-wxr-cleanup` and `uams-statamic`; the new `uamswp-migration-api` kept the private one until an organization owner changed it (`UAMS-Web/uamswp-migration-api-archive#468`). |
| `secret_scanning` | enabled | disabled | organization code security configuration | Public: GitHub recommended turns it on, and every public repository published here was read back with it on. Private: Dependabot only turns it off and is enforced. |
| `secret_scanning_push_protection` | enabled | disabled | organization code security configuration | As above. GitHub's own check on pushes to a public repository refused the migration API's first snapshot push with `GH013` while this setting read off (`UAMS-Web/uamswp-migration-api-archive#468`), so that refusal is not evidence the setting is on. |
| `secret_scanning_non_provider_patterns` | enabled | disabled | organization code security configuration | As above. |
| `secret_scanning_validity_checks` | enabled | disabled | organization code security configuration | As above. |
| `rule_deletion` | present | present | organization ruleset `24525022` (`main: no deletion or force push`) | `pre-merge-check`, *Why this is a standing order* (`UAMS-Web/uams-statamic-archive#2016`). The ruleset lists repositories by id, so a new repository is added to it. |
| `rule_non_fast_forward` | present | present | organization ruleset `24525022` | As above. |
| `rule_pull_request` | present | present | organization ruleset `24525074` (`main: pull request required`) | `pre-merge-check`, *Why this is a standing order*; the `closing-a-ticket` rule's step 1. |
| `rule_required_status_checks` | Attribution footer, CI, Closing keywords | none | organization ruleset `24965287` for the five Statamic add-ons; a repository ruleset for `uamswp-migration-api` (`25835660`) and `wordpress-importer` (`25970115`) | Public: `main` requires the `code-quality` skill's three contexts, which `apply-required-checks-ruleset.mjs` applies (`UAMS-Web/uamswp-migration-api#7`, `UAMS-Web/wordpress-wxr-cleanup#12`, `UAMS-Web/uams-statamic#337`). Private: remote CI is off (`RUN_REMOTE_CI`), so a required check would either block every merge or pass without running, depending on whether `CI` carries the switch (`pre-merge-check`, *Making CI a merge gate*), and the local runner is the gate. |

**What the checker does not read:** labels, topics, the description and homepage, webhooks, deploy keys, collaborators and teams, environments, Pages, autolink references, GitHub App installations, tag rules, and the merge-commit title and message defaults, which matter only where merge commits are allowed. The going-public checklist below covers the ones a move has to recreate.

## Open questions

The current repositories disagree on these, or agree only by default, and no reason is recorded for the other value. The checker prints their values with `--open` and never reports them as departures. Each is the project owner's to decide; once decided, the row takes a value and a reason here.

- **`delete_branch_on_merge`.** Off in the gate repositories, and recorded there: head branches are kept because a pull-request body links its files by the absolute head-branch URL, so a deleted branch breaks every file link in its own pull request (`uamswp-migration-api` D-077, `UAMS-Web/uams-statamic-archive#2177`). On in the five Statamic add-ons, `uams-claude-skills` and `uamswp-mcp`, with no reason recorded. Off in the rest by GitHub's default. Read 2026-10-28: 7 of 51 on.
- **`allow_update_branch` on a private repository.** 31 of 32 private targets read off, GitHub's default, and one reads on. The public decision rests on required checks, which private repositories do not carry.
- **`dependabot_version_updates`.** 10 of 51 targets carry `.github/dependabot.yml`: `uams-statamic`, `wordpress-importer`, `uamswp-migration-api`, `wordpress-wxr-cleanup`, `uams-claude-skills` and the five add-ons. The other 41 carry none, and no decision records that every repository must. The `update-dependencies-ticket` skill ships a `templates/dependabot.yml` for a repository that takes one. Read 2026-10-28.
- **`has_wiki`.** On by default in 47 targets and off in 4 (`uams-statamic`, `uamswp-migration-api` and two private repositories), with no reason recorded for either. `wordpress-importer` kept its wiki on through its move, and `UAMS-Web/wordpress-importer-archive#1299` notes that whether a wiki holds pages cannot be read through the REST API. The `github-wiki` skill covers a repository that publishes one.

## Proposals: rows the organization could set

Each row below is set per repository today and could be set or enforced once for the organization. Moving one is the project owner's act; none is moved by this skill. Rows only a repository can set (the squash commit defaults, branch deletion, update-branch, auto-merge, the feature toggles) stay what the checker mainly watches.

- **Merge methods, through the shared `pull_request` rule.** Ruleset `24525074`'s `pull_request` rule allows `merge`, `squash` and `rebase` (read 2026-10-28 through `rules/branches/main` on the 9 targets it reaches). Setting its `allowed_merge_methods` to `squash` alone would enforce `allow_merge_commit` and `allow_rebase_merge` on every repository the ruleset lists, whatever each repository's own toggles say. All 9 already allow only squash, so it changes nothing there today. It would bind any repository added to the list later.
- **The default branch rules for every repository.** Rulesets `24525022` and `24525074` list repositories by id, so a new repository has none of the three rules until someone with `admin:org` adds it, and 42 of the 51 targets carry none today. Targeting all of the organization's repositories would give every one of them the deletion, force-push and pull-request rules at once, and would require a pull request on `main` in each of those 42. Targeting by custom property is a separate follow-up (#358, *Out of scope*).
- **The default workflow token.** Setting the organization's default workflow permissions to `read`, with *Allow GitHub Actions to create and approve pull requests* off, would change only workflows that declare no `permissions:`. Every shared template declares its own. A repository workflow written without a declaration would lose write access and need one added, so the change is preceded by a read of each repository's workflows.
- **`COMPOSER_AUTH_TOKEN` as an organization secret.** Today it is a repository secret in each store. An organization Actions secret and an organization Dependabot secret, each limited to the repositories that read it, would make a new repository's access one list entry instead of two secrets set by hand. Its value is a fine-grained token whose own repository access is also extended per repository (`UAMS-Web/uams-statamic-archive#2659`, step 7).
- **The required checks.** The five add-ons already share one organization ruleset (`24965287`), while `uamswp-migration-api` and `wordpress-importer` each carry a repository ruleset. The `code-quality` skill warns against adding the checks to a shared ruleset that reaches a repository with no job of those names, which it would block. Listing in `24965287` exactly the public repositories that ship all three would keep the requirement in one place; it would be edited whenever a repository goes public, and it needs `admin:org`.

## Moving a repository to public

What a new repository does not copy from the old one, in the order the three moves needed it. Each item names the move tickets that recorded it: **A** is `UAMS-Web/uamswp-migration-api-archive#465` (creation `#468`, switch `#329`), **I** is `UAMS-Web/wordpress-importer-archive#1244` (`#1299`, `#1251`), and **S** is `UAMS-Web/uams-statamic-archive#2649` (`#2659`, `#2657`). Read every list from the REST API before the old repository is renamed, and read an empty list beside a non-empty one on the same credential, so an empty read is known to be an absence.

Before anything is transferred:

1. **Merge settings**: squash only, `PR_TITLE` and `PR_BODY`, auto-merge off, branch deletion and update-branch as the old repository had them (A, I, S).
2. **Features, topics, description and homepage**: issues, projects, wiki and discussions, the topics, and the description and homepage (S recorded neither). The migration API's topics were empty and were set during its move (A, I, S).
3. **Labels**, name, color and description, copied before any issue is transferred so the moved issues keep theirs (A, I, S).
4. **Actions permissions, variables and secrets.** Secret values cannot be read, so each is set again by its owner. `COMPOSER_AUTH_TOKEN` was set again, and the fine-grained token's repository access was extended to the new repository (S). Organization variables reach the new repository through their own scope (A, I, S).
5. **Dependabot**: alerts and security updates turned back on. `.github/dependabot.yml` copies with the tree. A secret a pull-request workflow reads is also set in the Dependabot store (A, I, S).
6. **Organization rulesets**: the new repository's id added to rulesets `24525022` and `24525074`, which needs `admin:org`, then read back from `repos/{o}/{r}/rules/branches/main` (A, I, S).
7. **GitHub App installations** that select repositories: the new repository added to each (S).
8. **Webhooks, deploy keys, collaborators and teams, environments, Pages and autolink references**: each read, and recreated where one exists (A, I, S).
9. **The sync target**: `shared/manifest.json` marks the repository `"public": true` and records its review in `shared/public-reviews.json` once it is public (S, `UAMS-Web/uams-claude-skills#352`).

Straight after the switch to public:

10. **Read back the code security configuration**: secret scanning, push protection, non-provider patterns and validity checks read `enabled`. On `wordpress-importer`, `wordpress-wxr-cleanup` and `uams-statamic`, the organization's public configuration attached on the switch (S read all four `disabled` five seconds after it and `enabled` 25 seconds after). On the new migration API, which carried the enforced "Dependabot only" configuration, the change was refused with `422` until an organization owner attached one that includes them (A, I, S).
11. **Expect remote CI to run.** `RUN_REMOTE_CI` stops reaching the repository, so every gated job runs on every pull request (`UAMS-Web/uamswp-migration-api#7`, `UAMS-Web/wordpress-importer#163`, `UAMS-Web/uams-statamic#337`).
12. **Require the three checks, and turn update-branch on**, through the `code-quality` skill's ruleset, once the workflows are on `main` (`UAMS-Web/uamswp-migration-api#7`, `UAMS-Web/wordpress-wxr-cleanup#12`, `UAMS-Web/uams-statamic#337`).
13. **Confirm a workflow actually runs**, from a run on a pull request, not from the settings. On the new `wordpress-importer`, Actions ran no workflow on 2026-10-26 although the API and the settings page both read enabled, as recorded in #358's *Why*.
14. **Run the checker** against the new repository: every departure it prints is a setting the move did not recreate.

## Running the checker

From the root of a checkout that holds this skill:

```bash
node .claude/skills/repository-settings/scripts/check-repository-settings.mjs UAMS-Web/<repo>
```

Name as many repositories as needed, or pass `--manifest <path>` to check every target a sync manifest lists (in `UAMS-Web/uams-claude-skills`, `--manifest shared/manifest.json`). With `--manifest` it also reports, as `manifest_public`, a target whose manifest `public` flag disagrees with its visibility: the flag that holds a public target's sync back for review (checklist item 9). `--open` also prints each repository's value for every open-question row. `--from <dir>` reads `<dir>/<repo>.json` snapshots instead of calling the API.

It makes eleven or twelve REST reads per repository, all `GET` (the twelfth looks for `.github/dependabot.yaml` when `.yml` is absent), and passes nothing to `gh api` but a `repos/` path and `-i`, so no flag that writes can reach it. A list read whose `total_count` differs from the items it returned, and an existence read answered with anything but `200`, `204` or `404`, are failed reads (exit 2), never values. It needs only a token that can read the repository; the organization's own settings are read through each repository, which shows the effective value. Before it reads anything, it runs its controls: a public and a private snapshot built to match the baseline must report nothing, a planted merge-commit departure must be the one departure reported, an open-question row must never be reported, and paused security updates must be. It prints how many repositories and rows it is checking before the first result.

| Exit | Meaning |
| --- | --- |
| 0 | Every repository read matches the baseline for its visibility. |
| 1 | At least one departure, each on its own line: `<owner>/<repo> (<visibility>): <row>: expected <value>, found <value>`. |
| 2 | The check did not run, or ran incompletely: a control failed, an argument or the manifest was wrong, or a repository could not be read. |

An archived repository is named and not judged. A departure is a question for the project owner, not a failure of the repository's own gate, so the checker is not a job in a repository's local CI.

## Changing the baseline

The table and the checker's `BASELINE` hold the same rows in the same order, and a test in `UAMS-Web/uams-claude-skills` fails when they disagree. A decided open question, a moved proposal or a new setting changes both in one pull request, with the decision it rests on named in the row's reason.
