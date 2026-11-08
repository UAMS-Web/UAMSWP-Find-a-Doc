# Contributing to UAMSWP-Find-a-Doc

<!-- uams-claude-skills:begin -->
## Branches and environments

Every change starts as an issue, is built on a branch linked to that issue, and reaches production through `dev`, then `staging`, then `master`. This section covers that path and what each pull request's test plan has to cover.

| Branch | Role | Where its test plan runs |
| --- | --- | --- |
| `dev` | First integration branch | The local install |
| `staging` | Pre-production | The staging site (uamshealth.dev) |
| `master` | Default branch, production (uamshealth.com) | No test plan: it is reached through the release pull request |

## From issue to merge

1. **File or pick up an issue.** The work is described there, with acceptance criteria.
2. **Create a branch from `master`, linked to the issue.** Use the issue's **Create a branch** link, or:

   ```sh
   gh issue develop <number> --base master --name <number>-<short-slug> --checkout
   ```

   The link shows the branch, and later its pull requests, on the issue.
3. **Open a ready pull request from the branch to `dev`.** It carries the full description: what changed, why, and how it was verified. Its test plan is run on the local install, and its boxes are checked before review.
4. **Open a draft pull request from the same branch to `staging`.** It points back to the `dev` pull request rather than repeating it. It stays a draft until the `dev` pull request merges. Its test plan is run on the staging site (uamshealth.dev) once the change is deployed there, so its boxes start unchecked.
5. **Release.** `staging` reaches `master` through its own pull request, from `staging` to `master`.

Both pull requests come from the same branch, so a fix found in review is pushed once and shows up in both.

## Test plans

Every pull request ends with a `## Test plan` checklist. Its first line names the environment it was run on, and every item is something a reviewer can check there:

- **Pull request to `dev`:** "Run on the local install." Items are things you checked yourself there: a page, a role, a save, a command. Check a box only for a check you actually ran.
- **Pull request to `staging`:** "Run on the staging site (uamshealth.dev) once this branch is deployed there." Items are the checks that matter on a shared site: real content, real user roles, production-matching settings. They start unchecked and are checked by whoever runs them there.

The two lists usually overlap but are not copies. A check that only makes sense on one environment belongs only on that one.

The [pull request template](.github/pull_request_template.md) fills in the skeleton when a pull request is opened on GitHub.

## Changing code that has no tests

Before changing code that has no tests, add a test that captures what the code does now, whether or not that is what it should do, and see it pass. Commit that test on its own, then make the change. A test that now fails shows a behavior that changed: update the test if the change was intended, and fix the code if it was not.

The [`tests` skill](.claude/skills/tests/SKILL.md#pinning-legacy-behavior-before-a-change) has the detail: what to capture, how to show a test catches a change, and how to name it.

## Agents

Coding agents follow the same path. Their instructions are in [`AGENTS.md`](AGENTS.md) and the shared skills and rules under `.claude/`, which are synced from `UAMS-Web/uams-claude-skills`.
<!-- uams-claude-skills:end -->
