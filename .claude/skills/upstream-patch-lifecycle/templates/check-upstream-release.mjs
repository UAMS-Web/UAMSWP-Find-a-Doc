#!/usr/bin/env node
/**
 * Decide whether an upstream pull request's merge has shipped in a release.
 *
 * `watch-upstream.yml` calls this for a merged `kind: pr` row that carries
 * `released: true`. A merge alone does not retire a local patch: `composer update` can only
 * drop it once a release carries the fix. So this answers one question: does the newest
 * release on the pull request's base-branch line contain the merge commit?
 *
 * THE LINE IS THE BASE BRANCH, NOT THE CALENDAR. A base of `13.x` means tags `v13.*` and a base
 * of `5.1.x` means tags `v5.1.*`; any other base (`main`, `master`) means every stable tag.
 * Taking the newest release by date, or across lines, reads the wrong tag: `laravel/framework`
 * shipped `v12.69.3` the same day as `v13.34.0`, and a `12.x` tag can never contain a `13.x`
 * fix. Pre-release tags are never a release that retires a patch, so they are skipped.
 *
 * TAGS, NOT GITHUB RELEASES. Some upstreams publish tags only, so the tags come from
 * `git ls-remote --tags`, which also spends no API budget.
 *
 * CONTAINMENT. `gh api repos/<repo>/compare/<tag>...<sha>` reports `behind` or `identical` when
 * the tag contains the commit. `ahead` and `diverged` both mean it does not; a tag cut from a
 * release commit beside the branch reports `diverged` for a merge that landed after it.
 *
 * Usage:  node scripts/check-upstream-release.mjs <owner/repo> <base-branch> <merge-sha>
 * Output: one JSON object: `{"line", "tag", "status", "released"}`. `tag` is null when the
 *         line has no stable tag yet, and `released` is then false.
 * Exit:   0 = answered, 1 = a command failed or the arguments are wrong. A failure must never
 *         read as "not released yet", so it is an exit code rather than a `false`.
 */
import { execFileSync } from 'node:child_process'
import { pathToFileURL } from 'node:url'

const STABLE_TAG = /^v?(\d+)\.(\d+)\.(\d+)$/

/**
 * The release line a base branch names, or null when it names none.
 *
 * @param {string} baseRef The pull request's base branch, such as `13.x`, `5.1.x` or `main`.
 * @returns {{major: number, minor: number|null}|null}
 */
export function releaseLine(baseRef) {
    const match = /^v?(\d+)(?:\.(\d+))?\.x$/.exec(baseRef)

    if (match === null) {
        return null
    }

    return { major: Number(match[1]), minor: match[2] === undefined ? null : Number(match[2]) }
}

/**
 * The newest stable tag on a release line, or null when the line has none.
 *
 * @param {string[]} tags Tag names, in any order.
 * @param {{major: number, minor: number|null}|null} line The line, or null for every tag.
 * @returns {string|null}
 */
export function newestTagOnLine(tags, line) {
    let best = null
    let bestParts = null

    for (const tag of tags) {
        const match = STABLE_TAG.exec(tag)

        if (match === null) {
            continue
        }

        const parts = match.slice(1).map(Number)

        if (line !== null && (parts[0] !== line.major || (line.minor !== null && parts[1] !== line.minor))) {
            continue
        }

        if (bestParts === null || parts[0] > bestParts[0]
            || (parts[0] === bestParts[0] && (parts[1] > bestParts[1]
                || (parts[1] === bestParts[1] && parts[2] > bestParts[2])))) {
            best = tag
            bestParts = parts
        }
    }

    return best
}

/**
 * Whether a compare status from `<tag>...<sha>` means the tag contains the commit.
 *
 * @param {string} status The compare API's `status`.
 * @returns {boolean}
 */
export function tagContains(status) {
    if (!['ahead', 'behind', 'identical', 'diverged'].includes(status)) {
        throw new Error(`unexpected compare status: ${status}`)
    }

    return status === 'behind' || status === 'identical'
}

/**
 * The tag names `git ls-remote --tags --refs` prints.
 *
 * @param {string} output The command's output.
 * @returns {string[]}
 */
export function parseLsRemote(output) {
    return output
        .split('\n')
        .map((line) => line.split('\t')[1] ?? '')
        .filter((ref) => ref.startsWith('refs/tags/'))
        .map((ref) => ref.slice('refs/tags/'.length))
}

/**
 * Answer whether the newest release on the base line contains the merge commit.
 *
 * @param {string} repo `owner/name`.
 * @param {string} baseRef The pull request's base branch.
 * @param {string} sha The merge commit.
 * @param {(command: string, args: string[]) => string} run Runs a command and returns its stdout.
 * @returns {{line: string, tag: string|null, status: string|null, released: boolean}}
 */
export function checkRelease(repo, baseRef, sha, run) {
    const line = releaseLine(baseRef)
    const lineLabel = line === null ? 'all' : `${line.major}.${line.minor === null ? 'x' : `${line.minor}.x`}`
    const tags = parseLsRemote(run('git', ['ls-remote', '--tags', '--refs', `https://github.com/${repo}.git`]))
    const tag = newestTagOnLine(tags, line)

    if (tag === null) {
        return { line: lineLabel, tag: null, status: null, released: false }
    }

    const status = run('gh', ['api', `repos/${repo}/compare/${tag}...${sha}`, '--jq', '.status']).trim()

    return { line: lineLabel, tag, status, released: tagContains(status) }
}

// Run only when invoked as a script, so the tests can import the helpers above.
if (process.argv[1] && import.meta.url === pathToFileURL(process.argv[1]).href) {
    const [repo, baseRef, sha] = process.argv.slice(2)

    if (!/^[\w.-]+\/[\w.-]+$/.test(repo ?? '') || !baseRef || !/^[0-9a-f]{7,40}$/.test(sha ?? '')) {
        process.stderr.write('usage: check-upstream-release.mjs <owner/repo> <base-branch> <merge-sha>\n')
        process.exit(1)
    }

    try {
        const result = checkRelease(repo, baseRef, sha, (command, args) => execFileSync(command, args, { encoding: 'utf8' }))
        process.stdout.write(`${JSON.stringify(result)}\n`)
    } catch (error) {
        process.stderr.write(`::error::release check for ${repo} failed: ${error.message}\n`)
        process.exit(1)
    }
}
