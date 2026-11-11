#!/usr/bin/env node
// check-decision-log-order.mjs: hold a decision log in oldest-first order, with a blank
// line before every entry heading.
//
// An entry heading is `## YYYY-MM-DD` followed by anything. Appending is the natural way
// to add an entry, so the convention is oldest first and the check asserts that the
// natural action is also the correct one. Two things are checked, and reported
// separately because their remedies differ:
//
//   separator  an entry heading whose previous line is not blank. A `merge=union`
//              driver (see SKILL.md) keeps both sides of a concurrent append and drops
//              the blank line at the seam, so headings weld to the entry above.
//   order      two ADJACENT entries where the later one is dated earlier. Both are
//              named with their lines, because in a long log "the file is unsorted" does
//              not say what to move.
//
// Fenced blocks are skipped: the log documents its own entry shape inside a fence, and a
// template is not an entry.
//
// EXIT CODES
//     0  ordered and separated
//     1  an order or separator finding
//     2  the check could not answer: unreadable file, no entries, or a usage error. A
//        log with no entries is a failure, not a pass, so a heading format that stops
//        matching cannot turn this into a check that passes on everything.
//
// USAGE
//     node .claude/skills/decision-log/scripts/check-decision-log-order.mjs [--file <path>]
//
// The file defaults to docs/DECISIONS.md under the working directory. Every outcome names
// the file it read.

import { readFileSync } from 'node:fs'
import { resolve } from 'node:path'
import { pathToFileURL } from 'node:url'

export const DEFAULT_LOG = 'docs/DECISIONS.md'

const HEADING = /^## (\d{4}-\d{2}-\d{2})(.*)$/
const FENCE = /^\s*(```|~~~)/

/**
 * Every entry heading outside a fence, with the line above it.
 */
export function parseEntries(source) {
    const lines = source.split(/\r?\n/)
    const entries = []
    let inFence = false

    lines.forEach((line, index) => {
        if (FENCE.test(line)) {
            inFence = !inFence
            return
        }
        if (inFence) {
            return
        }
        const match = HEADING.exec(line)
        if (match) {
            entries.push({
                line: index + 1,
                date: match[1],
                title: match[2].trim(),
                previous: index > 0 ? lines[index - 1] : null,
            })
        }
    })

    return entries
}

/**
 * Entries whose heading is not preceded by a blank line. An entry on line 1 has no
 * previous line and is not reported.
 */
export function findWelded(entries) {
    return entries.filter((entry) => entry.previous !== null && entry.previous.trim() !== '')
}

/**
 * Adjacent pairs where the later entry is dated earlier. A pair rather than a sort, so the
 * report names the two entries to reconcile.
 */
export function findOrderBreaks(entries) {
    const breaks = []
    for (let i = 1; i < entries.length; i += 1) {
        if (entries[i].date < entries[i - 1].date) {
            breaks.push({ before: entries[i - 1], after: entries[i] })
        }
    }
    return breaks
}

function parseArgs(argv) {
    let file = null
    for (let i = 0; i < argv.length; i += 1) {
        const arg = argv[i]
        let value
        if (arg === '--file') {
            i += 1
            value = argv[i]
        } else if (arg.startsWith('--file=')) {
            value = arg.slice('--file='.length)
        } else {
            throw new Error(`unrecognized argument "${arg}"`)
        }
        if (value === undefined || value === '') {
            throw new Error('--file needs a path')
        }
        if (file !== null) {
            throw new Error('--file given more than once')
        }
        file = value
    }
    return { file }
}

export function main(argv, cwd, out = console.log, err = console.error) {
    let opts
    try {
        opts = parseArgs(argv)
    } catch (error) {
        err(`check-decision-log-order: ${error.message}`)
        err(`Usage: node check-decision-log-order.mjs [--file <path>]  (default ${DEFAULT_LOG})`)
        return 2
    }

    const path = resolve(cwd, opts.file ?? DEFAULT_LOG)
    let source
    try {
        source = readFileSync(path, 'utf8')
    } catch (error) {
        err(`check-decision-log-order: cannot read ${path}: ${error.message}`)
        return 2
    }

    const entries = parseEntries(source)
    if (entries.length === 0) {
        err(`check-decision-log-order: no "## YYYY-MM-DD" entries found in ${path}`)
        err('An empty result here means the heading shape changed or the log has no entries yet, not that the log is ordered.')
        return 2
    }

    const welded = findWelded(entries)
    const breaks = findOrderBreaks(entries)

    if (welded.length > 0) {
        err(`check-decision-log-order: ${path}: ${welded.length} entry heading${welded.length === 1 ? ' is' : 's are'} not preceded by a blank line.`)
        for (const entry of welded) {
            err(`  line ${entry.line}  ${entry.date}  ${entry.title.slice(0, 66)}`)
            err(`    preceded directly by: ${entry.previous.trim().slice(0, 72)}`)
        }
        err('Insert one blank line above each heading listed. A merge=union append drops it at the seam; it does not affect ordering.')
    }

    if (breaks.length > 0) {
        err(`check-decision-log-order: ${path} is not in oldest-first order (${breaks.length} break${breaks.length === 1 ? '' : 's'}).`)
        for (const { before, after } of breaks) {
            err(`  line ${after.line}  ${after.date}  is OLDER than`)
            err(`  line ${before.line}  ${before.date}  which precedes it`)
            err(`    ${after.title.slice(0, 88)}`)
        }
        err('A new entry is appended to the end of the file. Move the entries above into date order.')
    }

    if (welded.length > 0 || breaks.length > 0) {
        return 1
    }

    out(`check-decision-log-order: ${path}: ${entries.length} entries, oldest-first and separated, ${entries[0].date} to ${entries[entries.length - 1].date}.`)
    return 0
}

if (process.argv[1] && import.meta.url === pathToFileURL(process.argv[1]).href) {
    process.exitCode = main(process.argv.slice(2), process.cwd())
}
