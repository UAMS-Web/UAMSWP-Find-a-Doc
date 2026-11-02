<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/writing-pull-requests/templates/pull_request_template.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
<!--
  Skeleton only: .claude/skills/writing-pull-requests/SKILL.md is the source of truth.
  Delete the sections you don't need, and delete these comments before submitting.

  Title: it renders verbatim into the GitHub Release notes, so write it for someone
  skimming "what shipped": imperative, verb-first, no trailing period, no Conventional-Commit
  prefix, and no internal-process references (no `Wave N`, `Group X`, `Iteration N`, batch or
  merge-order hints). Correct acronym casing; "and", never "&".

  Before opening: in a public repository, open this as a draft and let its remote checks run,
  then mark it ready once every one is green. Elsewhere, run the repository's spell check if
  you touched prose or identifiers, and run its gate. Never add an attribution footer or a
  session link to the body.

  Closing multiple issues? Repeat the keyword: `Closes #1, Closes #2.` A comma-separated
  list links only the FIRST issue.
-->

Closes #

<!-- One to three sentences: what changed, and why now. Lead with the user-visible effect or
     the root cause, not the implementation. -->

## The bug

<!-- Fix PRs only. Walk the root cause before the fix. -->

## Approach

<!-- When a path was chosen over alternatives, or reviewers need the design rationale. -->

## Files

<!-- - **edit** [`path/to/file`](https://github.com/OWNER/REPO/blob/BRANCH/path/to/file): reason. -->

## Verification

<!-- Evidence gathered before opening. If something couldn't be verified, say so plainly. -->

## Companion PRs

<!-- Sibling PRs in other repositories. When this depends on a companion, state the merge
     order and which side breaks if the order is ignored. -->

## Out of scope

## Follow-up

## Test plan

<!-- Mandatory, and always last. `- [x]` = verified locally; `- [ ]` = deferred to CI, review,
     or staging. Name the command, URL, or observation. -->

- [ ] The repository's gate passed on the head commit.
