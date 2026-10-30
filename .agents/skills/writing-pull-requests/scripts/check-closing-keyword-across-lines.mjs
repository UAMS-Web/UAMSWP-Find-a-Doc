#!/usr/bin/env node
//
// check-closing-keyword-across-lines.mjs: report a closing directive formed ACROSS a line
// break, which nobody wrote (UAMS-Web/uams-claude-skills#157).
//
// A newline is whitespace to GitHub's closing-keyword parser. A line ending in `closed` and
// the next line beginning `#2241` therefore read as the directive `closed #2241`, and merging
// the text closes that issue. In the instance this was written for, the keyword was a cell
// value in a status column and the reference was the row label of the next row:
//
//     #2037  write the patch          closed
//     #2241  file the bug upstream    retitled
//
// WHAT TO POINT IT AT. Where merges are squash-only, the pull-request BODY becomes the commit
// message, so the body is the primary input. The check-closing-keyword-across-lines.yml
// workflow template reads it through the API, not from the event payload, so an edit that
// lands between the event and the run is still the text that is checked. `--range` and
// `--commit` read commit messages for the author who wrote the table into a commit.
//
// TWO CHECKS THAT CANNOT SEE THIS, both of which look like they should: grepping the body for
// `Closes #N` (the pair is created by the join, so the string is on no line) and reading the
// pull request's `closingIssuesReferences` (the link field and the push-time scan are separate
// code paths; the field was empty for the instance above and the merge closed the issue).
// See the pre-merge-check and github-api-budget rules.
//
// THE PATTERN IS THE github-api-budget RULE'S, and is run on every invocation against that
// rule's own controls BEFORE any input is read:
//     arm 1  `Closes #1` / `does not close #2` / `fix: no number here` must yield exactly the
//            directives `Closes #1` and `close #2`, both on one line;
//     arm 2  a keyword ending one line and a reference beginning the next must yield exactly
//            `closed #2241`, across lines.
// A pattern that stops matching the same-line form is not trusted to report the cross-line one.
// Either arm failing is exit 2.
//
// A same-line `Closes #N` is the deliberate form and is never reported. A keyword inside a
// code span or a fence is reported: the push-time scan reads plain text, so it is live.
//
// EXIT CODES
//     0  scanned, controls held, no cross-line directive
//     1  at least one cross-line directive; each names both lines
//     2  the check could not run: a control failed, an input was unreadable, empty or the
//        literal `null`, a range selected nothing, or an option was not understood
//
// USAGE
//     node check-closing-keyword-across-lines.mjs --file <path> [--file <path> ...]
//     gh api repos/{o}/{r}/pulls/{n} --jq '.body // ""' | node ... --stdin
//     node check-closing-keyword-across-lines.mjs --range origin/main..HEAD
//     node check-closing-keyword-across-lines.mjs --commit <sha>
//     (with no source option: --range origin/main..HEAD)

import { spawnSync } from 'node:child_process'
import { readFileSync, realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

const KEYWORD = String.raw`\b(?:close[sd]?|fix(?:es|ed)?|resolve[sd]?)\b`
// A reference is `#N`, `org/repo#N` or a full issue URL (UAMS-Web/uams-claude-skills#339):
// GitHub honors all three after a closing keyword. Only `/issues/N` counts; a link to a pull
// request or a file is not a reference. A trailing slash, query string or anchor follows the
// digits, so the match ends before it. The `www.` host is admitted without having been measured
// against GitHub's parser.
const ISSUE_URL = String.raw`https?:\/\/(?:www\.)?github\.com\/[\w.-]+\/[\w.-]+\/issues\/\d+`
const REFERENCE = String.raw`(?:${ISSUE_URL}|(?:[\w.-]+\/[\w.-]+)?#\d+)`

/** Keyword, the whitespace and colons between, reference: GitHub's parse, newline included. */
export const directivePattern = () => new RegExp(`(${KEYWORD})([\\s:]*)(${REFERENCE})`, 'gi')

/**
 * Every closing directive in a text, with the line the keyword is on and the line the
 * reference is on. `crossLine` is true when the text between them contains a newline.
 *
 * @param  {string}  text
 * @return {Array<{keywordLine: number, referenceLine: number, crossLine: boolean, keyword: string, reference: string, quoted: string}>}
 */
export function findDirectives(text) {
    const found = []
    for (const match of text.matchAll(directivePattern())) {
        const [whole, keyword, gap, reference] = match
        const keywordLine = text.slice(0, match.index).split('\n').length
        const breaks = gap.split('\n').length - 1
        found.push({
            keywordLine,
            referenceLine: keywordLine + breaks,
            crossLine: breaks > 0,
            keyword,
            reference,
            quoted: whole.replace(/\s+/g, ' '),
        })
    }
    return found
}

export const findAcrossLines = (text) => findDirectives(text).filter((directive) => directive.crossLine)

const CONTROL_SAME_LINE = 'Closes #1\ndoes not close #2\nfix: no number here\n'
const CONTROL_ACROSS_LINES = '| #2037 | write the patch | closed\n#2241 | file the bug upstream | retitled\n'

/** Both controls, through the function the inputs go through. Failure messages; empty when both hold. */
export function runControls() {
    const failures = []

    const sameLine = findDirectives(CONTROL_SAME_LINE)
    const sameLineQuoted = sameLine.map((directive) => directive.quoted).join(' | ')
    if (sameLineQuoted !== 'Closes #1 | close #2' || sameLine.some((directive) => directive.crossLine)) {
        failures.push(`same-line arm: expected "Closes #1 | close #2" on single lines, got "${sameLineQuoted || 'nothing'}"`)
    }

    const across = findDirectives(CONTROL_ACROSS_LINES)
    const acrossQuoted = across.map((directive) => `${directive.quoted}@${directive.keywordLine}-${directive.referenceLine}${directive.crossLine ? '' : '!same-line'}`).join(' | ')
    if (acrossQuoted !== 'closed #2241@1-2') {
        failures.push(`cross-line arm: expected "closed #2241@1-2", got "${acrossQuoted || 'nothing'}"`)
    }

    return failures
}

/** One finding as the lines a reader needs: where each half sits and what the parser reads. */
export function describe(source, directive) {
    return [
        `  ${source}`,
        `    reads as:  ${directive.quoted}`,
        `    because "${directive.keyword}" ends line ${directive.keywordLine} and "${directive.reference}" begins line ${directive.referenceLine}`,
    ]
}

/** An empty input, or the literal `null`, is a failure: either is what a failed fetch looks like. */
function unusable(text) {
    const trimmed = text.trim()
    if (trimmed === '') return 'empty'
    if (trimmed === 'null') return 'the literal "null"'
    return null
}

function readRange(range, gitLog) {
    const result = gitLog(range)
    if (result.status !== 0) throw new Error(`git log ${range} failed: ${(result.stderr || '').trim()}`)
    // NUL-separated: a commit body holds newlines, so any textual separator could split a
    // message in the middle of the very shape being looked for.
    return result.stdout
        .split('\0')
        .filter((entry) => entry.trim() !== '')
        .map((entry) => {
            const [sha, ...rest] = entry.replace(/^\r?\n/, '').split('\x1f')
            return { label: `commit ${sha.slice(0, 9)}`, text: rest.join('\x1f') }
        })
}

const defaultGitLog = (range) => spawnSync('git', ['log', '--format=%H%x1f%B%x00', range], { encoding: 'utf8' })

const HELP = [
    'check-closing-keyword-across-lines [--file <path>]... [--stdin] [--commit <sha>] [--range <a..b>]',
    '',
    'Point it at the text that will merge. Where merges squash, that is the pull-request body:',
    '',
    "  gh api repos/{owner}/{repo}/pulls/{n} --jq '.body // \"\"' | node check-closing-keyword-across-lines.mjs --stdin",
    '',
    'With no source option it reads --range origin/main..HEAD.',
]

export function main(argv = process.argv.slice(2), { log = console.log, error = console.error, readText = (path) => readFileSync(path, 'utf8'), readStdin = () => readFileSync(0, 'utf8'), gitLog = defaultGitLog } = {}) {
    const requests = []
    for (let i = 0; i < argv.length; i += 1) {
        const flag = argv[i]
        if (flag === '--help') {
            HELP.forEach((line) => log(line))
            return 0
        }
        if (flag === '--stdin') {
            requests.push({ kind: 'stdin' })
        } else if (['--file', '--commit', '--range'].includes(flag)) {
            const value = argv[i + 1]
            if (value === undefined) {
                error(`check-closing-keyword-across-lines: ${flag} needs a value.`)
                return 2
            }
            requests.push({ kind: flag.slice(2), value })
            i += 1
        } else {
            error(`check-closing-keyword-across-lines: unknown option '${flag}'`)
            error('Known: --file <path> --stdin --commit <sha> --range <a..b> --help')
            return 2
        }
    }
    // Decided on what was asked for, not on what came back: an explicit range that selects
    // nothing must not fall back to the default and answer a question nobody posed.
    if (requests.length === 0) requests.push({ kind: 'range', value: 'origin/main..HEAD' })

    // The controls come first, before any input is read.
    const controlFailures = runControls()
    if (controlFailures.length > 0) {
        error('closing-keyword check: SELF-CHECK FAILED, it cannot see what it is looking for:')
        controlFailures.forEach((failure) => error(`  ${failure}`))
        error('A clean result from this run would be meaningless, so none is reported.')
        return 2
    }

    const sources = []
    try {
        for (const request of requests) {
            if (request.kind === 'stdin' || request.kind === 'file') {
                const label = request.kind === 'stdin' ? 'stdin' : request.value
                const text = request.kind === 'stdin' ? readStdin() : readText(request.value)
                const why = unusable(text)
                if (why !== null) {
                    error(`closing-keyword check: ${label} is ${why}.`)
                    error('That is indistinguishable from a clean body, so it is a failure rather than a pass.')
                    return 2
                }
                sources.push({ label, text })
            } else if (request.kind === 'commit') {
                sources.push(...readRange(`${request.value}^!`, gitLog))
            } else {
                sources.push(...readRange(request.value, gitLog))
            }
        }
    } catch (failure) {
        error(`closing-keyword check: ${failure.message}`)
        return 2
    }

    if (sources.length === 0) {
        error('closing-keyword check: the source(s) named selected no text to scan.')
        error('Reporting that as clean would be a pass this has not earned.')
        return 2
    }

    // Before the findings, so a crash mid-report leaves a visibly truncated run.
    log(`closing-keyword check: controls held, scanned ${sources.length} source(s).`)

    const findings = sources.flatMap((source) => findAcrossLines(source.text).map((directive) => ({ source: source.label, directive })))
    if (findings.length === 0) {
        log('closing-keyword check: no directive crosses a line break.')
        return 0
    }

    error('')
    error(`Found ${findings.length} closing directive(s) formed across a line break:`)
    error('')
    for (const { source, directive } of findings) {
        describe(source, directive).forEach((line) => error(line))
        error('')
    }
    error('Merging this text closes those issues, whatever the rest of it says.')
    error('Break the adjacency: reorder the columns so the status cell is not last, or write the')
    error('cell as something other than a bare keyword ("now closed", "done").')
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
