---
name: github-wiki
description: >-
  How to plan, write and publish a GitHub wiki for a repository: what belongs in the wiki
  rather than the README, the platform's verified constraints (flat page names, one global
  sidebar, no pull requests, no search indexing below 500 stars), publishing pages from a
  folder in the repository with github-wiki-action and what that action deletes, the
  sidebar, Home, footer and page shapes that well-kept wikis use, and the behaviors still to
  test on a first publish. Activate when planning, structuring, writing or publishing wiki
  pages, when moving documentation between the README and the wiki, or when adding the
  wiki-publishing workflow.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/github-wiki/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): core.
-->
# GitHub wiki

<!-- cspell:ignore Archi Hystrix Sniffnet -->

A GitHub wiki is a separate git repository (`<repo>.wiki.git`) rendered under the repository's Wiki tab. This skill collects what was verified about it: GitHub's own documentation, the publishing action's source, Stack Overflow answers, and a set of well-regarded example wikis. The sources and the date they were read are listed at the end. Every statement about the publishing action names the release or commit it was read at; a repository that pins another revision reads the action's `action.yml` and `cli.ts` at that revision before relying on it. Where a behavior was reported but not verified, it is listed under *Still open* rather than stated as fact.

## README or wiki: overview in the README, the rest in the wiki

GitHub's guidance is that the README says what the project does and how to get started, and the wiki holds long-form documentation. The example wikis follow the same split. So:

- **The README holds the overview and getting started:** what the project does, its status, requirements, installation, a short quick start, and a link to the wiki. Development notes and the license stay there too.
- **The wiki holds everything long-form, reference included:** configuration, tags, commands, permissions and API parameters, alongside the walkthrough, task guides, troubleshooting and extending.
- **Where the wiki needs something the README covers, it links instead of copying**, labeled so the reader knows they are leaving the wiki: `Installation (README)`. Installation is the usual case.
- **Know the trade-off:** search engines index a wiki only when the repository has at least 500 stars and public editing is restricted, so reference moved to the wiki of a smaller repository cannot be found through a web search. Weigh it, and record the choice where the repository keeps its decisions. **GitHub Pages** is the alternative when documentation must be searchable and too large for a README; GitHub recommends it for that case.

## Platform facts

Verified against GitHub's documentation:

- **The first page has to be created in the browser** (Wiki tab, *Create the first page*, *Save Page*). Until then the wiki repository does not exist, so it cannot be cloned or pushed to.
- **The file name is the page title**, with hyphens shown as spaces, and the extension picks the markup (`.md` for Markdown). `Home.md` is the home page.
- **These characters are not allowed in a file name:** `\ / : * ? " < > |`. A page titled `Setup: Windows` needs a file name without the colon, for example `Setup-Windows.md`.
- **Page URLs are flat.** Folders in the wiki repository are not part of the URL, so every page name must be unique across the whole wiki. With two files of the same name, one page is shown and which one is not defined.
- **One `_Sidebar.md` and one `_Footer.md` serve the whole wiki.** Without a custom sidebar, GitHub shows a generated alphabetical page list.
- **Only the default branch is published**, and a wiki takes no pull requests. Every edit is a commit, with history, a side-by-side comparison of two revisions, and a revert.
- **Unsupported markup:** transclusion, definition lists, indentation and a table-of-contents directive.
- **Images:** PNG, JPEG or GIF, referenced by URL. `<img src="..." width="400">` sets a size, which Markdown image syntax cannot (reported on Stack Overflow).
- **Editing permission:** by default only people with write access can edit. On a public repository, a checkbox under *Settings*, *Features* controls whether anyone with an account can, and it is the restriction the search-indexing rule asks for. Its label depends on who owns the repository: *Restrict editing to collaborators only* on a personal account, *Restrict editing to users in teams with push access only* on an organization (seen checked on `UAMS-Web/statamic-multisite-forms`, 2026-10-13). Its state cannot be read through the REST API, so check it in the browser.
- **Disabling the wiki hides its pages without deleting them**, and enabling it again restores them.
- **Soft limit:** 5,000 files.

## Publishing from the repository with github-wiki-action

A wiki edited in the browser has no review, no checks and no tie to a release. Keeping the pages in a folder of the repository and publishing that folder from CI gives them pull-request review and the repository's checks. [Andrew-Chen-Wang/github-wiki-action](https://github.com/Andrew-Chen-Wang/github-wiki-action) does the publishing. What follows was read from its source at **v5.0.6** (commit `1bbb428`, the commit the `v5` tag points at), not from its README: the README on the default branch describes eight unreleased commits.

**What each publish does at v5.0.6:**

1. Clones the wiki repository.
2. **Deletes every file in the clone except `.git`** (`cli.ts`, lines 75 to 81), then copies the folder in. Any page not in the folder is gone after the publish, including pages created or edited in the browser.
3. With `preprocess` (the default), renames `README.md` to `Home.md` and rewrites links to Markdown pages as bare page names: `[Install](Install.md#requirements)` becomes `[Install](Install#requirements)`, so the heading fragment survives. **A link to `README.md` becomes `Home` only when it carries nothing after the extension**: `cli.ts` line 115 compares the whole rewritten URL with `README`, so `README.md#setup` is published as `README#setup`, a page that does not exist. Every page is also re-serialized by remark, so the published Markdown differs in formatting from the source (`-` bullets become `*   `). Only pages directly in the folder are rewritten; v5.0.6 reads the folder without descending into nested folders.
4. Commits and runs `git push -f origin master`. It force-pushes even with the default `clone` strategy, so a browser edit made between two publishes is overwritten without a merge.

**What that requires:**

- **The browser editor is not used.** Say so on the wiki itself: the footer is the place (see below).
- **The folder stays flat.** Subfolder pages are copied but their links are not rewritten in v5.0.6. Keep images in an `images/` subfolder, since images need no rewriting.
- **Link between pages with relative Markdown links that carry the extension** (`[Install](Install.md)`). They work when the folder is browsed in the repository and become wiki links on publish.
- **The workflow needs `contents: write`** (the push is `git push -f origin master`, read at v5.0.6). The default `github.token` is enough for the repository's own wiki; another repository's wiki needs a personal access token.
- **Pin the action to the commit** (`1bbb428...`, v5.0.6), not the moving `v5` tag, and update the pin deliberately after reading what changed.
- **Name the home page's source `Home.md`, not `README.md`,** and link it as `Home.md` or `Home.md#heading`. Both are rewritten by the general rule and also work when the folder is browsed in the repository.
- **Link issues and pull requests in full.** A bare `#123` on a wiki page is plain text, not a link.
- **Create the first page in the browser before the first run.** The publish replaces it.

Released v5.0.6 (`1bbb428`) does not rewrite links to files elsewhere in the repository. A link from a wiki page to `docs/DECISIONS.md` has to be a full `https://github.com/<owner>/<repo>/blob/main/...` URL. The action's default branch, read on 2026-10-13 when it was eight commits ahead of v5.0.6, adds that rewriting and a `direction: pull` mode that copies browser edits back into the folder; neither is available at the v5.0.6 commit.

**A workflow that publishes on each release**, so the wiki describes what people install rather than unreleased work:

```yaml
name: Publish wiki
on:
  release:
    types: [published]
  workflow_dispatch:
concurrency:
  group: publish-wiki
  cancel-in-progress: true
permissions:
  contents: write
jobs:
  publish-wiki:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@<pinned commit>
      - uses: Andrew-Chen-Wang/github-wiki-action@1bbb4280446f9630e8e21a18012cbacf3b0f992e # v5.0.6
        with:
          path: docs/wiki
          disable-empty-commits: true
```

The workflow is an illustration: each repository chooses its own trigger, and this skill ships no workflow file. Take inputs from the pinned commit's `action.yml`, not from the default-branch README, which documents unreleased ones. A workflow uses the hyphenated names `action.yml` declares (`disable-empty-commits`); its composite step passes each one on as the environment variable `cli.ts` reads (`INPUT_DISABLE_EMPTY_COMMITS`), so the underscored spelling in `cli.ts` is not a workflow input name.

Publishing on every push to `main` instead keeps the wiki current with the default branch, which suits a repository whose wiki documents unreleased work. Whichever trigger is chosen, the footer says which release the pages were written against.

## Structure

These patterns come from the example wikis that read well (NullAway, Sniffnet) and the problems of those that do not.

- **Group the sidebar by what the reader is trying to do, one level deep.** A bold label per group and a flat list under it: *Getting started*, *Guides* (split by audience when there is more than one), *Reference*, *Troubleshooting*, *Project*. A deep or ungrouped sidebar leaves the generated page list as the only usable navigation.
- **Make Home a map:** one short paragraph on what the wiki covers and what the README keeps, then the sidebar's groups with a one-line description per link. Use the same labels as the sidebar, so the two lists cannot drift apart.
- **Add a footer** that repeats the top-level groups as a one-line menu, links the README and the repository, names the release the pages were written against, and says where to edit: `Edit these pages in docs/wiki/ through a pull request; edits made here are overwritten.`
- **Name pages in sentence case with hyphens and no prefixes:** `Your-first-report.md`, `Showing-a-report-to-some-users-only.md`. The sidebar does the grouping, so a prefix only lengthens the title.
- **Model the narrative on the upstream project's docs** when the repository extends one, and link to the matching upstream page wherever the repository changes that page's behavior.

## Page shapes

- **Walkthrough:** what the reader will have at the end, then short numbered steps, each one an action and its visible result. Mark cautions as a bold *Note* or *Warning* paragraph. End with links to the guides that go further.
- **Task guide:** the situation in a sentence, the steps, then *Reference* links to the wiki's reference pages for every setting the guide used.
- **Reference page:** one page per area (configuration, a tag, a command, permissions, an API parameter), each opening with what it is for, then a table or list of every option with its type, default and effect, and an example. An overview, examples, then a table of parameters or variables.
- **Troubleshooting:** one heading per symptom, written as the reader meets it: the exact log line or error text, or the visible behavior (`A submit answers 404`). Under it: the cause, an example, and the fix. Give the page a short list of links to its headings at the top, since it is the page readers scan.
- **Version marking:** begin a page that depends on a release with `Written against vX.Y.Z.`, and mark a behavior added later with `Since vX.Y.Z`. When a problem is fixed, move its troubleshooting entry to a *Fixed* section naming the release instead of deleting it (AWS ParallelCluster's known-issues pages do this). Without a marker, stale pages read as current: Hystrix's (2017) and Guava's (2016) do.
- **Bump the markers before the tag, not after it.** When the wiki publishes on a release, the release republishes whatever `main` says, so a footer still naming the previous release goes live with the new one. Before cutting `vX.Y.Z`, merge a change that sets the footer to `Written against vX.Y.Z.`, and does the same on each page the release's changes leave accurate: a page whose subject the release changed keeps its old marker until it is rewritten. A release has gone out with every page and the footer still naming the previous one, and needed a follow-up change and a republish by hand (observed on `UAMS-Web/statamic-multisite-forms`, v0.5.0 published over v0.4.2 markers).

## Before a page merges

The pages are durable records in the repository, so the repository's own rules apply to them as to any document:

- [`american-english-prose`](../../rules/american-english-prose.md), [`no-em-dashes`](../../rules/no-em-dashes.md) and [`no-emoji-in-durable-records`](../../rules/no-emoji-in-durable-records.md), with the sweeps those rules give.
- The private-network-address check from [`writing-issues`](../writing-issues/SKILL.md), since a wiki on a public repository is public.
- Every link between pages points at a file that exists in the folder, with its extension. Check this in the folder, not on the published wiki: a missing wiki page answers `302` to the wiki's Home, the same as a broken link, so a link checker that reads HTTP status reports every broken wiki link as working, and a browser following one with a heading fragment lands on Home looking deliberate.
- The sidebar, Home and footer list the same pages under the same labels. A page in the folder that none of them links is an orphan.

## Settled on a first publish, and still open

Measured on `UAMS-Web/statamic-multisite-forms`'s wiki, 2026-10-13, from the published wiki repository and the server-rendered page (run `38491805944`, action v5.0.6):

- **`#123` is not autolinked** on a wiki page; it renders as plain text.
- **A custom `_Sidebar.md` appears beside the generated page list**, not in place of it.
- **Headings get anchors** (`id="user-content-<slug>"`, with the slug GitHub uses for READMEs), so `Page#heading` links have a target. A `#heading` link scrolls to it: the link names `#<slug>` and GitHub's script maps it to the prefixed anchor. Observed on mobile, on Troubleshooting's list of links, 2026-10-13; desktop and links from another page are presumed to behave the same. A browser-automation tab whose page is hidden cannot test this: it does not scroll even on a README anchor.
- **`README.md#heading` is not rewritten to Home** (see step 3 above), and the missing page it names redirects to Home.
- **A second repository confirmed the `302` check and the workflow.** `UAMS-Web/statamic-alpine-csp-forms`, 2026-10-15: the wiki's first page had been created in the browser, the workflow ran by hand on the merge commit (run `38760308933`, `workflow_dispatch`, v5.0.6, `success`), the wiki's latest commit was `Update wiki <merge sha>` holding every page in the folder, and `/wiki/Installation` answered `200` against `302` for a page that does not exist.

Still open, to settle on the first page that needs each and record here:

1. Whether GitHub shows an outline of a page's headings. Nothing in the server HTML shows one; it may be drawn by script. If it exists, only Troubleshooting needs its own list of links.
2. Whether images under `images/` render through a relative link.
3. Whether GitHub alert blocks (`> [!NOTE]`) render on wiki pages; until then the page shapes use bold *Note* and *Warning* paragraphs.

## Avoid

- **Keeping the same content in the README and the wiki.** Two copies drift. Move it to one and link from the other.
- **Editing pages in the browser once publishing runs from the repository.** The next publish deletes the edit.
- **Translations as page copies.** ArchiSteamFarm's locale twins grew its wiki to 1,312 pages.
- **Undated pages and orphan pages,** which read as current and complete when they are neither.
- **AI-generated wiki tools.** They produce code summaries, which duplicate the reference rather than guide a reader.

## Sources

Read on 2026-10-13, apart from the second publish record under *Settled on a first publish* (2026-10-15).

- GitHub Docs, [Documenting your project with wikis](https://docs.github.com/en/communities/documenting-your-project-with-wikis) and the articles it links.
- [Andrew-Chen-Wang/github-wiki-action](https://github.com/Andrew-Chen-Wang/github-wiki-action): `action.yml` and `cli.ts` at v5.0.6 (`1bbb428`); the README on the default branch, which is eight commits ahead.
- [MyHoneyBadger/awesome-github-wiki](https://github.com/MyHoneyBadger/awesome-github-wiki): its Stack Overflow questions (84 answers, read through the Stack Exchange API), the guides it lists that loaded, and its example wikis, among them Guava, Hystrix, NullAway, ArchiSteamFarm, Sniffnet and AWS ParallelCluster.
