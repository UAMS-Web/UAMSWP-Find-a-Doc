#!/usr/bin/env node
//
// check-development-links.mjs: report an issue the pull request is linked to for closing
// (its `closingIssuesReferences`) that the body does not close with a keyword
// (UAMS-Web/uams-claude-skills#573).
//
// A branch created with `gh issue develop <n>`, or from an issue's "Create a branch" link, is
// linked to issue `n`, and a pull request opened from it lists `n` in `closingIssuesReferences`
// with no closing keyword anywhere. The body can say "Part of #313; leaves #313 open." and the
// field still lists #313. The check-closing-keyword-across-lines check reads the body text and
// cannot see a link, so this one reads the field.
//
// WHAT COUNTS AS CLOSED BY THE BODY. Any closing directive in the body text that resolves to
// the linked issue: `Closes #N`, `Closes org/repo#N`, `Closes <issue URL>`, same line or across
// a line break. A directive inside a code span or a fence counts too, because the push-time
// scan of a squash message reads plain text and acts on it. The directive grammar is imported
// from check-closing-keyword-across-lines.mjs, so the two checks cannot drift apart. A reference
// wrapped in a Markdown link is read by its label first, here and not in that grammar, because the
// writing-pull-requests skill prescribes the form for a cross-repository close:
// `` Closes [`org/repo#N`](url). ``
//
// THE BASE BRANCH (UAMS-Web/uams-claude-skills#680, decided on #589). A Development link closes its
// issue only when the pull request merges into the repository's default branch, so on a pull request
// to any other base the listing overstates what the merge does. The check takes the pull request's
// base branch (`--base`) and the repository's default branch (`--default-branch`) and compares them,
// case-sensitively, as git does:
//     base equals the default branch   a linked issue the body leaves open is a failure (exit 1);
//     base is another branch           the same issues are reported as a warning, with the
//                                      `::warning::` annotation a workflow shows, and the exit is 0;
//     either value absent or empty     exit 2, never a pass.
// The link is not judged on a non-default base: the issue closes if the branch later reaches the
// default branch, and the warning says so.
//
// INPUT. The raw response of the GraphQL query the workflow template runs, passed as a file
// (`--graphql-file`) or on stdin (`--graphql-stdin`), and the body (`--body-file`). The response
// is checked for shape before it is trusted: an `errors` member, a missing
// `closingIssuesReferences`, or a `totalCount` larger than the nodes returned is exit 2, never a
// pass, because each is what a failed or truncated fetch looks like and an empty list would
// read as "nothing linked".
//
// CONTROLS, run on every invocation before any input is read:
//     arm 1  body "Part of #5." with #5 linked must be reported;
//     arm 2  body "Closes #5." with #5 linked must not be reported;
//     arm 3  body "Closes other/repo#5." with this repository's #5 linked must be reported,
//            so a reference to a same-numbered issue elsewhere does not satisfy the link;
//     arm 4  body "Closes [`#5`](url)." with #5 linked must not be reported.
// Any arm failing is exit 2.
//
// EXIT CODES
//     0  scanned, controls held, every linked issue is closed by the body (or none is linked), or the
//        base is not the default branch and the unclosed links were reported as a warning
//     1  at least one linked issue the body does not close, on a pull request to the default branch
//     2  the check could not run
//
// USAGE
//     node check-development-links.mjs --repo <owner/repo> --base <branch> --default-branch <branch> \
//         --body-file <path> --graphql-file <path>

import { readFileSync, realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

import { findDirectives } from './check-closing-keyword-across-lines.mjs'

/** A directive's reference as `owner/repo#N` (lower case), resolving `#N` and URLs against `repo`. */
export function resolveReference(reference, repo) {
    const url = reference.match(/^https?:\/\/(?:www\.)?github\.com\/([\w.-]+\/[\w.-]+)\/issues\/(\d+)/i)
    if (url) return `${url[1]}#${url[2]}`.toLowerCase()
    const hash = reference.indexOf('#')
    const owner = reference.slice(0, hash)
    return `${owner === '' ? repo : owner}#${reference.slice(hash + 1)}`.toLowerCase()
}

/** The body with each Markdown link whose label is a reference replaced by that label. */
export const unwrapLinkedReferences = (body) => body.replace(/\[`?((?:[\w.-]+\/[\w.-]+)?#\d+)`?\]\([^)\s]*\)/g, '$1')

/**
 * Linked issues the body has no closing directive for.
 *
 * @param  {string}  body
 * @param  {string[]}  linked  `owner/repo#N` strings
 * @param  {string}  repo  `owner/repo` of the pull request
 * @return {string[]}
 */
export function unclosedLinks(body, linked, repo) {
    const closed = new Set(findDirectives(unwrapLinkedReferences(body)).map((directive) => resolveReference(directive.reference, repo)))
    return linked.filter((issue) => !closed.has(issue.toLowerCase()))
}

/** The linked issues in a GraphQL response, or a thrown Error naming why it cannot be trusted. */
export function parseLinked(text) {
    let response
    try {
        response = JSON.parse(text)
    } catch {
        throw new Error('the GraphQL response is not JSON')
    }
    if (response === null || typeof response !== 'object') throw new Error('the GraphQL response is not an object')
    if (response.errors) throw new Error(`the GraphQL response carries errors: ${JSON.stringify(response.errors).slice(0, 300)}`)
    const references = response.data?.repository?.pullRequest?.closingIssuesReferences
    if (!references || !Array.isArray(references.nodes)) throw new Error('the GraphQL response has no closingIssuesReferences.nodes')
    if (typeof references.totalCount !== 'number') throw new Error('the GraphQL response has no totalCount to compare the nodes with')
    if (references.totalCount > references.nodes.length) {
        throw new Error(`totalCount ${references.totalCount} exceeds the ${references.nodes.length} nodes returned: the list is truncated`)
    }
    return references.nodes.map((node) => {
        const name = node?.repository?.nameWithOwner
        if (typeof name !== 'string' || !Number.isInteger(node.number)) throw new Error('a closingIssuesReferences node lacks repository.nameWithOwner or number')
        return `${name}#${node.number}`
    })
}

const CONTROL_REPO = 'o/r'

/**
 * What to do with the linked issues the body leaves open: `pass` when there are none, `fail` when the
 * pull request merges into the default branch, `warn` when it merges into any other base.
 *
 * @param  {string[]}  open  the result of unclosedLinks
 * @param  {string}  base  the pull request's base branch
 * @param  {string}  defaultBranch  the repository's default branch
 * @return {'pass'|'warn'|'fail'}
 */
export function classify(open, base, defaultBranch) {
    if (open.length === 0) return 'pass'
    return base === defaultBranch ? 'fail' : 'warn'
}

export function runControls() {
    const failures = []
    const arms = [
        ['arm 1 (a body that leaves the issue open is reported)', 'Part of #5.\n', ['o/r#5']],
        ['arm 2 (a keyword for the issue is not reported)', 'Closes #5.\n', []],
        ['arm 3 (a same-numbered issue elsewhere does not satisfy the link)', 'Closes other/repo#5.\n', ['o/r#5']],
        ['arm 4 (a keyword before a reference in a Markdown link is not reported)', 'Closes [`#5`](https://github.com/o/r/issues/5).\n', []],
    ]
    for (const [name, body, expected] of arms) {
        const got = unclosedLinks(body, ['o/r#5'], CONTROL_REPO)
        if (got.join(',') !== expected.join(',')) failures.push(`${name}: expected [${expected}], got [${got}]`)
    }
    const outcomes = [
        ['arm 5 (a link left open on the default branch fails)', classify(['o/r#5'], 'main', 'main'), 'fail'],
        ['arm 6 (a link left open on another base is a warning)', classify(['o/r#5'], 'dev', 'main'), 'warn'],
        ['arm 7 (nothing left open passes on any base)', classify([], 'dev', 'main'), 'pass'],
    ]
    for (const [name, got, expected] of outcomes) {
        if (got !== expected) failures.push(`${name}: expected ${expected}, got ${got}`)
    }
    return failures
}

const OPTIONS = ['--repo', '--base', '--default-branch', '--body-file', '--graphql-file']

export function main(argv = process.argv.slice(2), { log = console.log, error = console.error, readText = (path) => readFileSync(path, 'utf8') } = {}) {
    const options = {}
    for (let i = 0; i < argv.length; i += 1) {
        if (!OPTIONS.includes(argv[i]) || argv[i + 1] === undefined) {
            error(`check-development-links: unknown option or missing value at '${argv[i]}'`)
            error(`Known: ${OPTIONS.join(' <value> ')} <value>`)
            return 2
        }
        options[argv[i].slice(2)] = argv[i + 1]
        i += 1
    }
    for (const required of ['repo', 'base', 'default-branch', 'body-file', 'graphql-file']) {
        if (options[required] === undefined || options[required] === '') {
            error(`check-development-links: --${required} is required.`)
            return 2
        }
    }
    if (!/^[\w.-]+\/[\w.-]+$/.test(options.repo)) {
        error(`check-development-links: --repo must be owner/repo, got '${options.repo}'.`)
        return 2
    }

    const controlFailures = runControls()
    if (controlFailures.length > 0) {
        error('development-link check: SELF-CHECK FAILED, it cannot tell a closed link from an open one:')
        controlFailures.forEach((failure) => error(`  ${failure}`))
        error('A clean result from this run would be meaningless, so none is reported.')
        return 2
    }

    let body
    let linked
    try {
        body = readText(options['body-file'])
        const trimmed = body.trim()
        if (trimmed === '' || trimmed === 'null') {
            // A pull request with no body is legitimate, but an empty file is also what a failed
            // fetch leaves, so the workflow writes `.body // ""` and this reads it as nothing
            // closed. With nothing linked that is a pass; with something linked it is a finding.
            body = ''
        }
        linked = parseLinked(readText(options['graphql-file']))
    } catch (failure) {
        error(`development-link check: ${failure.message}`)
        return 2
    }

    log(`development-link check: controls held, ${linked.length} linked issue(s) read.`)
    const open = unclosedLinks(body, linked, options.repo)
    if (open.length === 0) {
        log('development-link check: every linked issue is closed by a keyword in the body.')
        return 0
    }

    if (classify(open, options.base, options['default-branch']) === 'warn') {
        const list = open.join(', ')
        log(`::warning title=Development link closes nothing on this base::${list} is linked for closing and the body has no keyword for it, but this pull request targets '${options.base}', not the default branch '${options['default-branch']}', so merging it closes nothing. The link closes the issue only if the branch later reaches '${options['default-branch']}'.`)
        log(`development-link check: WARNING, ${open.length} linked issue(s) left open by the body on a base that is not the default branch: ${list}`)
        return 0
    }

    error('')
    error(`Found ${open.length} linked issue(s) that the body does not close with a keyword:`)
    for (const issue of open) error(`  ${issue}`)
    error('')
    error('The pull request lists these in closingIssuesReferences, usually through a Development')
    error('link from `gh issue develop` or a "Create a branch" link, and merging may close them.')
    error('If the pull request leaves an issue open, remove it from the Development panel of the')
    error('pull request. If it finishes the issue, add a keyword for it (`Closes #N.`).')
    return 1
}

function invokedDirectly() {
    if (!process.argv[1]) return false
    try {
        return realpathSync(resolve(process.argv[1])) === realpathSync(fileURLToPath(import.meta.url))
    } catch {
        return false
    }
}

if (invokedDirectly()) process.exitCode = main()
