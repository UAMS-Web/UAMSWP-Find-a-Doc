#!/usr/bin/env node
//
// check-attribution-footer.mjs: fail when a pull-request body carries the harness
// attribution footer, which the no-emoji-in-durable-records rule forbids in pull-request
// and issue bodies (UAMS-Web/uams-claude-skills#155).
//
// The harness tells authors to add the footer, so it arrives on pull request after pull
// request and gets removed by hand at the gate. A tree sweep cannot see it: a body is not
// on the filesystem. The check-attribution-footer.yml workflow template reads the body
// from the API into a file and runs this script on it.
//
// The footer arrives in three forms: a marker line carrying an emoji, a bare harness session
// URL carrying no emoji at all, or both together. So a hit is EITHER marker; a check keyed
// on the emoji alone, or on the URL alone, misses real specimens.
//
// The emoji test is the character class the no-emoji-in-durable-records rule's audit uses,
// written out here so the script has no dependency on a repository file. The URL half
// matches the path shape, never a particular session identifier, because one identifier
// names the authoring session and recurs across pull requests.
//
// EXIT CODES
//     0  the body is clean
//     1  a footer marker was found; each is reported by line and code point
//     2  the check could not run: no file, an unreadable file, or the self-check failed
//
// USAGE
//     node check-attribution-footer.mjs <body-file>

import { readFileSync, realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

// The no-emoji rule's GLYPH class: U+1F000-1FAFF, U+2600-27BF, U+2B00-2BFF and U+FE0F.
export const isGlyph = (codePoint) =>
    (codePoint >= 0x1f000 && codePoint <= 0x1faff)
    || (codePoint >= 0x2600 && codePoint <= 0x27bf)
    || (codePoint >= 0x2b00 && codePoint <= 0x2bff)
    || codePoint === 0xfe0f

/**
 * The path shape of a harness session URL, written as two halves so this file does not
 * carry the literal it looks for.
 */
export const SESSION_URL = new RegExp(['claude\\.ai/code', '/session_[A-Za-z0-9]+'].join(''))

const label = (codePoint) => `U+${codePoint.toString(16).toUpperCase().padStart(4, '0')}`

/**
 * Every footer marker in a body, one entry per hit. `marker` is a code-point label or
 * `session URL`; the URL itself is never echoed, so a report cannot republish what it flags.
 */
export function findFooterMarkers(body) {
    const hits = []
    body.split('\n').forEach((text, index) => {
        const line = index + 1
        for (const char of text) {
            const codePoint = char.codePointAt(0)
            if (isGlyph(codePoint)) hits.push({ line, marker: label(codePoint) })
        }
        if (SESSION_URL.test(text)) hits.push({ line, marker: 'session URL' })
    })
    return hits
}

/**
 * Check one body file. Returns `{ status, lines }` so a test can run it without ending the
 * process. The self-check runs first, through the same function the body goes through.
 */
export function checkBody(path, { read = (file) => readFileSync(file, 'utf8') } = {}) {
    if (!path) return { status: 2, lines: ['usage: node check-attribution-footer.mjs <body-file>'] }

    const control = [
        `Generated with ${String.fromCodePoint(0x1f916)} a tool`,
        ['https://', 'claude.ai/code', '/session_', 'Control01'].join(''),
        'A clean line.',
    ].join('\n')
    const found = findFooterMarkers(control).map((hit) => `${hit.line}:${hit.marker}`).join(' ')
    if (found !== '1:U+1F916 2:session URL') {
        return {
            status: 2,
            lines: [`attribution-footer check: SELF-CHECK FAILED, it cannot see what it is looking for (expected "1:U+1F916 2:session URL", got "${found}").`],
        }
    }

    let body
    try {
        body = read(path)
    } catch (failure) {
        return { status: 2, lines: [`attribution-footer check: ${path} could not be read: ${failure.message}`] }
    }

    const hits = findFooterMarkers(body)
    if (hits.length === 0) {
        return { status: 0, lines: [`attribution-footer check: clean (${body.split('\n').length} lines read, self-check passed).`] }
    }
    return {
        status: 1,
        lines: hits.map((hit) => `::error::Line ${hit.line} of the pull-request body carries ${hit.marker}. The no-emoji-in-durable-records rule forbids the harness attribution footer and emoji in pull-request bodies.`),
    }
}

export function main(argv = process.argv.slice(2), { log = console.log, error = console.error } = {}) {
    const { status, lines } = checkBody(argv[0])
    lines.forEach((line) => (status === 2 ? error : log)(line))
    return status
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
