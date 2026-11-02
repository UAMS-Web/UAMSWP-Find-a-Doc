<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/rules/cutting-releases.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# Rule — cut a release each business day at 10:00 Central when there is unreleased work

Releases are **retrospective semver tags on `main`**: a changelog checkpoint over "everything merged since the last tag," not a deploy gate or a release branch. The trunk-based flow is unchanged: vetted PRs merge to `main` continuously. This rule fixes the one thing that flow leaves open (**when to cut the tag**) so the release line neither drifts to zero nor turns into per-PR noise.

**Why this is a standing order.** Left undefined, the cadence decays, and releases are only useful as a changelog if they actually get cut on a rhythm. A fixed daily time gives every repository the same predictable rhythm, keeps each release to at most one day's work, and makes "cut the release" a checklist item someone owns rather than a thing everyone assumes someone else did.

## How to apply

1. **The cadence: one release a day, at 10:00 Central, on business days, when there is unreleased work.** At 10:00 in `America/Chicago` time, Monday to Friday, check whether `main` carries any commit since the last tag (`git fetch origin && git rev-list --count <last-tag>..origin/main`). If it does, tag `main` at its tip and publish the release; if it does not, cut nothing that day. Skip weekends, and skip U.S. federal holidays where possible (where the run can tell the date is one); work that merges on a skipped day goes out at the next business day's cut. **Bump the minor** (`0.x.0`) if the batch shipped any feature or structural work, **the patch** (`0.x.y`) if it was refinement-only. The same cadence holds in every repository; what differs between them is only how the cut is made (item 4).

2. **A breaking change ships at the next daily cut, and its notes call it out.** There is no separate release for one. When a change that would be a major bump at 1.0+ merges (a framework or `statamic/cms` major upgrade, a content-model handle rename or a mapping-contract rename a consumer depends on, an importer, addon or service restructure), it goes out with the rest of that day's work at the next 10:00 cut, and the release notes lead with its `**Breaking change**: <impact and required action>` callout so a reader cannot miss it among the other changes. (The breaking-change classes and the callout format live in [`writing-release-notes`](../skills/writing-release-notes/SKILL.md).) Those examples are illustrations of a test, not the test itself.

3. **The version line is the repository's decision, recorded with its other decisions, not this rule's.** This rule only fixes when a tag is cut on whatever line the repository is on.

4. **Mechanics are not this rule's job.** *How* to write the notes, generate the body, tag, and publish lives in the [`writing-release-notes`](../skills/writing-release-notes/SKILL.md) skill. This rule is only the *when*.

5. **Who may cut it is not this rule's job either, and is not implied by it.** A release due under the triggers above is still cut only on the operator's authorization (given in the session's own chat or recorded as a standing grant, and nothing else) as stated under *Who may cut it* in the [`writing-release-notes`](../skills/writing-release-notes/SKILL.md) skill. A scheduled 10:00 run needs a standing grant that covers it.

6. **Cut it from a local checkout. A Claude Code cloud session cannot tag or publish at all.** `git push <tag>`, `POST git/tags`, `POST git/refs` and `POST /releases` are each refused `403`, and it is the **session type** rather than the token, so a broader credential does not lift it. Everything *up to* the tag (deriving the version, generating the notes, checking whether a release is due) works fine there; only the last step must move. Read the refusal as the environment, not as a defect to debug, before automating a release.

## The DRY line

This file is the standing statement of **when** to cut a release. The **how** (title format, heading vocabulary, `gh` mechanics) is the [`writing-release-notes`](../skills/writing-release-notes/SKILL.md) skill; the **versioning rationale** (which line the repository is on, minor/patch, what the next major means, breaking-change classes) is the repository's own decisions record. It sits inside the branch → PR → merge flow in `AGENTS.md`.

