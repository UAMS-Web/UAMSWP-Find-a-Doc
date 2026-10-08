# Contributing to UAMSWP-Find-a-Doc

Every change starts as an issue, is built on a branch linked to that issue, and reaches production through `dev`, then `staging`, then `master`. This page covers that path and what each pull request's test plan has to cover.

## Branches and environments

| Branch | Role | Where its test plan runs |
| --- | --- | --- |
| `dev` | First integration branch | The local environment: your own WordPress install with this plugin checked out |
| `staging` | Pre-production | The staging environment, uamshealth.dev |
| `master` | Default branch, production | uamshealth.com |

## From issue to merge

1. **File or pick up an issue.** The work is described there, with acceptance criteria.
2. **Create a branch from `master`, linked to the issue.** Use the issue's **Create a branch** link, or:

   ```sh
   gh issue develop <number> --base master --name <number>-<short-slug> --checkout
   ```

   The link shows the branch, and later its pull requests, on the issue.
3. **Open a ready pull request from the branch to `dev`.** It carries the full description: what changed, why, and how it was verified. Its test plan is run in the local environment, and its boxes are checked before review.
4. **Open a draft pull request from the same branch to `staging`.** It points back to the `dev` pull request rather than repeating it. It stays a draft until the `dev` pull request merges. Its test plan is run on uamshealth.dev once the change is deployed there, so its boxes start unchecked.
5. **Release.** `staging` reaches `master` through its own pull request, from `staging` to `master`.

Both pull requests come from the same branch, so a fix found in review is pushed once and shows up in both.

## Test plans

Every pull request ends with a `## Test plan` checklist. Its first line names the environment it was run on, and every item is something a reviewer can check there:

- **Pull request to `dev`:** "Run on the local install." Items are things you checked on your own install: a page, a role, a save, a command. Check a box only for a check you actually ran.
- **Pull request to `staging`:** "Run on uamshealth.dev once this branch is deployed there." Items are the checks that matter on a shared site: real content, real user roles, production-matching plugin settings. They start unchecked and are checked by whoever runs them there.

The two lists usually overlap but are not copies. A check that only makes sense on one environment belongs only on that one.

The [pull request template](.github/pull_request_template.md) fills in the skeleton when a pull request is opened on GitHub.

## Agents

Coding agents follow the same path. Their instructions are in [`AGENTS.md`](AGENTS.md) and the shared skills and rules under `.claude/`, which are synced from `UAMS-Web/uams-claude-skills`.
