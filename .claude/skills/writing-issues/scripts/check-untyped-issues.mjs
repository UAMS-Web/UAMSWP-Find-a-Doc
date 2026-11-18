#!/usr/bin/env node
// check-untyped-issues.mjs: report a repository's issues that have no issue type
// (UAMS-Web/uams-claude-skills#809).
//
// The `writing-issues` skill tells a filer to set Bug, Feature or Task, and agents that file from the
// command line skip the step: UAMS-Web/UAMSWP-Pusher held 59 untyped issues on 2026-11-17, 20 of them
// filed after the practice was written down. An instruction reduces misses and cannot catch one, so
// this reads the repository's issues and lists the ones whose `type` is null. It reports and never
// sets a type, because choosing between Bug, Feature and Task is a judgement.
//
// WHAT IT READS. `GET repos/{owner}/{repo}/issues` over REST, page by page, until a short page. That
// endpoint returns pull requests as issues too; an item with a `pull_request` key is skipped and
// counted, so a pull request (which has no type) is never reported. Open issues by default; `--state
// all` adds the closed ones.
//
// WHAT IT PRINTS. The count it read comes first, then each untyped issue as `#<number>  <title>`, so an
// empty list can be told from a failed read. A pass is the printed "no untyped issues" line.
//
// CONTROLS, run before the repository is read: a planted untyped issue is reported, a typed one is not,
// and an untyped pull request is not. A control misbehaving is exit 2. The read itself is also refused
// as unreadable when it returns issues and none of them carries a `type` key at all, because an API
// that stops returning the field would otherwise read as a clean repository.
//
// Usage:
//     node .claude/skills/writing-issues/scripts/check-untyped-issues.mjs [--repo <owner/name>] [--state open|closed|all]
// With no `--repo`, the repository named by GITHUB_REPOSITORY (set on an Actions run) is read. The token
// comes from `gh`'s own authentication (GH_TOKEN on an Actions run); the workflow needs `issues: read`.
//
// EXIT CODES
//     0  every issue read has a type
//     1  one or more issues have no type; the numbers are printed
//     2  the check did not run: a control failed, a read failed or was truncated, or the arguments were wrong
//
// Shipped by the shared sync from UAMS-Web/uams-claude-skills; its tests live there
// (scripts/check-untyped-issues.test.mjs) and are not synced.

import { spawnSync } from 'node:child_process'
import { realpathSync } from 'node:fs'
import { fileURLToPath } from 'node:url'

export const PAGE_SIZE = 100

/** The most pages walked. 100 pages of 100 is 10,000 items; more than that is refused as truncated, never read as clean. */
export const MAX_PAGES = 100

const STATES = ['open', 'closed', 'all']

/**
 * Split one page of the issues endpoint into issues and pull requests, and pick out the issues with no type.
 * @param {Array<object>} items the raw items of every page read
 * @returns {{issues: number, pullRequests: number, untyped: Array<{number: number, title: string}>, typeKeySeen: boolean}}
 */
export function classify(items) {
    let issues = 0
    let pullRequests = 0
    let typeKeySeen = false
    const untyped = []
    for (const item of items) {
        if (item?.pull_request !== undefined && item.pull_request !== null) {
            pullRequests++
            continue
        }
        issues++
        if (item !== null && typeof item === 'object' && 'type' in item) typeKeySeen = true
        if (item?.type === null || item?.type === undefined) untyped.push({ number: item.number, title: String(item.title ?? '') })
    }
    return { issues, pullRequests, untyped, typeKeySeen }
}

/**
 * Read every page of a listing with `fetchPage(page)`, until a page shorter than PAGE_SIZE. A page that is
 * not an array, or more pages than MAX_PAGES, throws: a partial read is a failure, not a clean result.
 * @param {(page: number) => Array<object>} fetchPage
 */
export function readAll(fetchPage) {
    const items = []
    for (let page = 1; page <= MAX_PAGES; page++) {
        const batch = fetchPage(page)
        if (!Array.isArray(batch)) throw new Error(`page ${page} was not a list of issues`)
        items.push(...batch)
        if (batch.length < PAGE_SIZE) return items
    }
    throw new Error(`more than ${MAX_PAGES * PAGE_SIZE} items: the read is truncated, so no answer is given`)
}

/** The report lines and exit status for the items read. */
export function report(repo, items, state = 'open') {
    const { issues, pullRequests, untyped, typeKeySeen } = classify(items)
    const lines = [`check-untyped-issues: read ${issues} ${state === 'all' ? '' : `${state} `}issues from ${repo} (${pullRequests} pull requests skipped)`]
    if (issues > 0 && !typeKeySeen) {
        lines.push('check-untyped-issues: no issue read carries a `type` field, so whether any has a type cannot be told; the read is refused')
        return { status: 2, lines }
    }
    if (untyped.length === 0) {
        lines.push('check-untyped-issues: no untyped issues')
        return { status: 0, lines }
    }
    lines.push(`check-untyped-issues: ${untyped.length} untyped ${untyped.length === 1 ? 'issue' : 'issues'}:`)
    for (const { number, title } of untyped) lines.push(`  #${number}  ${title}`)
    lines.push('Set Bug, Feature or Task on each (the writing-issues skill says which). This check never sets a type.')
    return { status: 1, lines }
}

/** Controls over `classify`, run before any read. Each failure is a returned message. */
export function controlFailures() {
    const failures = []
    const planted = [
        { number: 1, title: 'typed', type: { name: 'Bug' } },
        { number: 2, title: 'untyped', type: null },
        { number: 3, title: 'untyped pull request', type: null, pull_request: { url: 'x' } },
        { number: 4, title: 'typed pull request', type: { name: 'Task' }, pull_request: { url: 'x' } },
    ]
    const found = classify(planted)
    if (found.untyped.map((issue) => issue.number).join(',') !== '2') failures.push(`planted items: expected only #2 untyped, got ${JSON.stringify(found.untyped)}`)
    if (found.issues !== 2 || found.pullRequests !== 2) failures.push(`planted items: expected 2 issues and 2 pull requests, got ${found.issues} and ${found.pullRequests}`)
    if (report('control/repo', [planted[0]]).status !== 0) failures.push('a typed issue alone must report clean')
    if (report('control/repo', [planted[1]]).status !== 1) failures.push('an untyped issue alone must report a finding')
    if (report('control/repo', [{ number: 9, title: 'no type key' }]).status !== 2) failures.push('issues with no `type` key at all must be refused, not read as clean')
    return failures
}

/** One `gh api` page read. Throws with gh's message on a failed or unparsable read. */
export function ghPage(repo, state, page, run = spawnSync) {
    const path = `repos/${repo}/issues?state=${state}&per_page=${PAGE_SIZE}&page=${page}`
    const result = run('gh', ['api', path], { encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
    if (result.error) throw new Error(`could not run gh: ${result.error.message}`)
    if (result.status !== 0) throw new Error(`gh api ${path} exited ${result.status}: ${String(result.stderr).trim() || 'no message'}`)
    try {
        return JSON.parse(result.stdout)
    } catch (error) {
        throw new Error(`gh api ${path} did not return JSON: ${error.message}`)
    }
}

/**
 * The command. `fetchPage(page)` is injectable for tests; by default it reads through `gh`.
 * @returns {{status: number, lines: string[]}}
 */
export function main(argv = process.argv.slice(2), env = process.env, fetchPageFor = (repo, state) => (page) => ghPage(repo, state, page)) {
    let repo = env.GITHUB_REPOSITORY ?? ''
    let state = 'open'
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--repo') repo = argv[++i] ?? ''
        else if (argv[i] === '--state') state = argv[++i] ?? ''
        else return { status: 2, lines: [`check-untyped-issues: unknown argument ${argv[i]}`] }
    }
    if (!/^[\w.-]+\/[\w.-]+$/.test(repo)) return { status: 2, lines: ['check-untyped-issues: name the repository with --repo <owner/name> (or set GITHUB_REPOSITORY)'] }
    if (!STATES.includes(state)) return { status: 2, lines: [`check-untyped-issues: --state is one of ${STATES.join(', ')}`] }
    const failures = controlFailures()
    if (failures.length > 0) return { status: 2, lines: ['check-untyped-issues: a control failed, so no repository was read:', ...failures.map((line) => `  ${line}`)] }
    let items
    try {
        items = readAll(fetchPageFor(repo, state))
    } catch (error) {
        return { status: 2, lines: [`check-untyped-issues: could not read ${repo}: ${error.message}`] }
    }
    return report(repo, items, state)
}

const isEntry = () => {
    try {
        return realpathSync(process.argv[1]) === realpathSync(fileURLToPath(import.meta.url))
    } catch {
        return false
    }
}

if (isEntry()) {
    const { status, lines } = main()
    for (const line of lines) console.log(line)
    process.exit(status)
}
