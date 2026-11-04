# Decision log

The canonical record of why the project is the way it is: the reasoning and the rejected alternatives behind decisions that have closed. It guards against silently reopening a settled decision.

**Consult it before acting.** Before proposing a change, reopening a question or picking an approach, check here first. The path under consideration may already be a decided-and-recorded one, or an explicitly rejected one. If it is, follow the record, or make the case to revisit it.

**When a decision closes, record the verdict in both places.** Capture the what, the why and the rejected alternatives here (the cross-ticket index) and in the GitHub issue's `## Decision` section (the per-ticket record), keeping the two consistent. Put the full reasoning in the relevant `docs/` companion when the decision touches a documented area. The issue tracker stays the system of record for a single ticket's status.

## How to add an entry

Entries run oldest first, one per closed decision, so a new entry is appended to the end of the file. Keep it terse; the companion or the issue carries the depth.

Every entry heading is `## YYYY-MM-DD`, a space, and a short title. Leave exactly one blank line above each heading. The check (`decision-log` skill) fails on a heading dated earlier than the one before it, and on a heading that sits directly under the previous line.

```markdown
## YYYY-MM-DD <short decision title> (<#issue or area>)

**Decision.** <what was decided, in a sentence or two>

**Why.** <the reasoning that settled it>

**Rejected.** <the alternatives considered, and why each lost>

**Companion.** <link to the docs/ companion or the GitHub issue holding the full reasoning>
```

## 2026-11-02 Close and keep a Business Profile listing after 14 days unpublished or orphaned (#930)

**Decision.** Every orphaned listing is marked permanently closed (`openInfo.status = CLOSED_PERMANENTLY`) with its website, description, phone, labels, additional categories and URL attribute cleared. It stays on the account and can be reopened. Nothing is deleted, for providers and locations alike. The sync acts on its own, and only after the listing has stayed unpublished or orphaned for 14 continuous days; republishing, or restoring the association, clears the record and restarts the period. The cases are: a provider or location profile unpublished; a provider removed from a location (that one listing only); and a store code that no longer matches because a slug changed, once listings are matched by a stored Google location ID. Posts already unpublished when this ships start their period at deploy.

**Why.** An unpublish is two different events that look alike to the sync: an editor drafting a profile and republishing an hour later, and an organization retiring a provider or a clinic. Acting on the status change alone closes live listings during routine editing. A grace period separates the two without a person confirming each retirement. Closing keeps the sync's rule that it never deletes, and the closure is reversible. No documented API method releases a listing without deleting or transferring it, and what an API delete leaves on Maps is unmeasured (#990), so providers are closed and kept like locations until it is.

**Rejected.** Flag only: outdated listings stay live indefinitely and cleanup is manual. Delete automatically: final, loses reviews and history, and contradicts the never-deletes rule. Close and strip on the status change alone: closes live listings when an editor sets a draft. A person confirming every retirement: leaves a backlog of unactioned flags.

**Companion.** [UAMS-Web/UAMSWP-Find-a-Doc#930](https://github.com/UAMS-Web/UAMSWP-Find-a-Doc/issues/930); build in #991 (match by stored location ID) and #992 (orphan report and the 14-day close).
