#!/usr/bin/env node
// check-commit-session-links.mjs: fail when a commit message in a pull request carries a trailer
// whose value is a tool session URL (UAMS-Web/uams-claude-skills#819, deciding
// UAMS-Web/uams-statamic#628).
//
// The no-emoji-in-durable-records rule and check-attribution-footer.mjs forbid a tool session link in
// a pull-request or issue body. Neither reaches a commit message, so a trailer such as
// `Amp-Thread-ID: <thread URL>` passed every check and stayed readable in the branch's commits and the
// pull request's commit list. This reads every commit message of the pull request and reports each
// such trailer by commit and line.
//
// WHAT COUNTS. A trailer is a line `Key: value`. It is a hit when the value holds a tool session URL,
// matched by path shape and never by one identifier: the Claude Code session shape
// check-attribution-footer.mjs already matches, and the Amp thread shape. A `Co-authored-by` trailer
// naming the tool stays allowed: its value is a name and an address, not a link into a conversation.
// Add a shape to SESSION_URL_SHAPES when another tool's session URL turns up. A session URL in a line
// that is not a trailer is not reported, and neither is one in the composed squash message, which this
// check cannot see; the gate reads that message before a merge.
//
// WHAT IT READS. `GET repos/{owner}/{repo}/pulls/{n}` for the commit count, then
// `GET .../pulls/{n}/commits`, a page of 100 at a time. The commits read must equal the pull request's
// own count (the endpoint stops at 250), or the read is refused as truncated and not reported clean.
//
// CONTROLS, run before the pull request is read: a planted trailer of each shape is found, a
// `Co-authored-by` trailer and a clean message are not, and the URL is never echoed. A control
// misbehaving is exit 2.
//
// EXIT CODES
//     0  no commit carries a session URL trailer
//     1  one or more do; each is reported by short SHA and line
//     2  the check did not run: a control failed, a read failed or was truncated, or the arguments were wrong
//
// USAGE
//     node check-commit-session-links.mjs --repo <owner/name> --pr <number>

import { spawnSync } from 'node:child_process'
import { realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

import { SESSION_URL } from './check-attribution-footer.mjs'

export const PAGE_SIZE = 100

/** The path shape of a session URL, one per tool. Written in halves so this file does not carry a literal it looks for. */
export const SESSION_URL_SHAPES = [
    SESSION_URL,
    new RegExp(['ampcode\\.com', '/threads/T-[0-9A-Za-z-]+'].join('')),
]

const TRAILER = /^([A-Za-z][A-Za-z0-9-]*):[ \t]+(\S.*)$/

/** Every session URL trailer in one commit message, as `{ line, key }`. The URL itself is never returned. */
export function findSessionTrailers(message) {
    const hits = []
    String(message).replace(/\r\n/g, '\n').split('\n').forEach((text, index) => {
        const trailer = TRAILER.exec(text)
        if (trailer && SESSION_URL_SHAPES.some((shape) => shape.test(trailer[2]))) hits.push({ line: index + 1, key: trailer[1] })
    })
    return hits
}

/** The report for the commits read: `{ status, lines }`. */
export function report(commits) {
    const lines = []
    for (const commit of commits) {
        for (const { line, key } of findSessionTrailers(commit.commit?.message ?? '')) {
            lines.push(`::error::Commit ${String(commit.sha).slice(0, 7)} line ${line} is a ${key} trailer carrying a tool session URL. Reword the commit to drop it; a Co-authored-by trailer naming the tool is allowed.`)
        }
    }
    if (lines.length === 0) return { status: 0, lines: [`commit-session-links check: clean (${commits.length} commits read, controls passed).`] }
    return { status: 1, lines }
}

/** Controls over `findSessionTrailers`, run before any read. Each failure is a returned message. */
export function controlFailures() {
    const failures = []
    const claude = ['https://', 'claude.ai/code', '/session_', 'Control01'].join('')
    const amp = ['https://', 'ampcode.com', '/threads/T-', 'control-01'].join('')
    const planted = {
        claude: `feat: x\n\nBody.\n\nClaude-Session: ${claude}\n`,
        amp: `feat: x\n\nAmp-Thread-ID: ${amp}\n`,
        coauthor: 'feat: x\n\nCo-authored-by: Claude <noreply@anthropic.com>\n',
        clean: 'feat: x\n\n- a bullet\n',
    }
    if (findSessionTrailers(planted.claude).map((hit) => `${hit.line}:${hit.key}`).join() !== '5:Claude-Session') failures.push('a session trailer of the Claude shape must be found on its line')
    if (findSessionTrailers(planted.amp).map((hit) => `${hit.line}:${hit.key}`).join() !== '3:Amp-Thread-ID') failures.push('a session trailer of the Amp shape must be found on its line')
    if (findSessionTrailers(planted.coauthor).length !== 0) failures.push('a Co-authored-by trailer naming the tool must not be a hit')
    if (findSessionTrailers(planted.clean).length !== 0) failures.push('a clean message must not be a hit')
    if (report([{ sha: 'abcdef0123', commit: { message: planted.amp } }]).lines.join('\n').includes('ampcode')) failures.push('the report must not echo the URL')
    return failures
}

/** One `gh api` read. Throws with gh's message on a failed or unparsable read. */
export function ghRead(path, run = spawnSync) {
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
 * Every commit of the pull request. `read(path)` is injectable. Throws when a page is not a list, or
 * when the commits read are not the number the pull request states, so a partial read is never clean.
 */
export function readCommits(repo, number, read) {
    const pull = read(`repos/${repo}/pulls/${number}`)
    const expected = pull?.commits
    if (!Number.isInteger(expected)) throw new Error('the pull request did not state its commit count')
    const commits = []
    for (let page = 1; commits.length < expected; page++) {
        const batch = read(`repos/${repo}/pulls/${number}/commits?per_page=${PAGE_SIZE}&page=${page}`)
        if (!Array.isArray(batch)) throw new Error(`page ${page} was not a list of commits`)
        if (batch.length === 0) break
        commits.push(...batch)
    }
    if (commits.length !== expected) throw new Error(`read ${commits.length} commits but the pull request has ${expected}: the read is truncated, so no answer is given`)
    return commits
}

/** The command. `read` is injectable for tests. @returns {{status: number, lines: string[]}} */
export function main(argv = process.argv.slice(2), read = (path) => ghRead(path)) {
    let repo = ''
    let number = ''
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--repo') repo = argv[++i] ?? ''
        else if (argv[i] === '--pr') number = argv[++i] ?? ''
        else return { status: 2, lines: [`commit-session-links check: unknown argument ${argv[i]}`] }
    }
    if (!/^[\w.-]+\/[\w.-]+$/.test(repo) || !/^[1-9]\d*$/.test(number)) return { status: 2, lines: ['commit-session-links check: usage: node check-commit-session-links.mjs --repo <owner/name> --pr <number>'] }
    const failures = controlFailures()
    if (failures.length > 0) return { status: 2, lines: ['commit-session-links check: a control failed, so no pull request was read:', ...failures.map((line) => `  ${line}`)] }
    let commits
    try {
        commits = readCommits(repo, number, read)
    } catch (error) {
        return { status: 2, lines: [`commit-session-links check: could not read the commits of ${repo}#${number}: ${error.message}`] }
    }
    return report(commits)
}

function invokedDirectly() {
    if (!process.argv[1]) return false
    try {
        return realpathSync(resolve(process.argv[1])) === realpathSync(fileURLToPath(import.meta.url))
    } catch {
        return false
    }
}

if (invokedDirectly()) {
    const { status, lines } = main()
    for (const line of lines) (status === 2 ? console.error : console.log)(line)
    process.exitCode = status
}
