#!/usr/bin/env node
// check-private-network-addresses.mjs: fail when an issue or pull-request body carries a
// private network address (UAMS-Web/uams-claude-skills#29).
//
// A private network address once reached six tracker bodies and comments in
// UAMS-Web/uams-statamic (removed by UAMS-Web/uams-statamic#2763), and nothing flagged
// it. Text posted through `gh` goes through a body file (the writing-issues and
// writing-pull-requests skills), so a check over that file is the one place every post
// passes through.
//
// Flags IPv4 literals in the RFC 1918 ranges 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16,
// wherever they sit: next to letters or underscores, inside a URL, with leading zeros, or
// as four parts of a longer dotted string. Passes documentation-range placeholders
// (RFC 5737), the three RFC 1918 blocks written as themselves (10.0.0.0/8 names the
// range, not a host; a host or a subnet with a prefix is still flagged), and a body carrying
// the opt-out marker on a line of its own outside a code block. A report names the line
// and the range; the matched address is never printed, so the report cannot republish
// what it flags.
//
// Usage, from the repository root:
//     node .claude/skills/writing-issues/scripts/check-private-network-addresses.mjs <body-file>
//
// Exit 0 clean, 1 an address found, 2 the check did not run: no file given, the file
// could not be read or is not UTF-8 text, or the self-check failed. A pass is the printed
// "clean" line, not the exit status alone.
//
// Shipped by the shared sync from UAMS-Web/uams-claude-skills; its tests live there
// (scripts/check-private-network-addresses.test.mjs) and are not synced.

import { readFileSync, realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

/**
 * A body that must discuss a private address carries this HTML comment on a line of its
 * own, outside a code block; it then passes without a scan. Quoting it inline or inside a code
 * block, as a body about this check does, does not opt out.
 */
export const OPT_OUT_MARKER = '<!-- allow-private-network-address -->'

// A dotted quad not preceded or followed by a digit, captured inside a lookahead so that
// overlapping candidates are all tried: in a.b.c.d.e both a.b.c.d and b.c.d.e
// are checked. Letters, underscores and dots around it do not hide it.
const OCTET = '0*(?:25[0-5]|2[0-4]\\d|1?\\d?\\d)'
const IPV4 = new RegExp(`(?<!\\d)(?=((?:${OCTET}\\.){3}${OCTET})(?!\\d))`, 'g')

const octets = (ip) => ip.split('.').map(Number)

/** The RFC 1918 block a dotted quad belongs to, or null outside them. */
export function rfc1918Range(ip) {
    const parts = octets(ip)
    if (parts.length !== 4 || parts.some((o) => !Number.isInteger(o))) return null
    const [a, b] = parts
    if (a === 10) return '10.0.0.0/8'
    if (a === 172 && b >= 16 && b <= 31) return '172.16.0.0/12'
    if (a === 192 && b === 168) return '192.168.0.0/16'
    return null
}

/** Whether a dotted quad is in an RFC 5737 documentation range (192.0.2.0/24, 198.51.100.0/24, 203.0.113.0/24). */
export function isDocumentationRange(ip) {
    const [a, b, c] = octets(ip)
    return (a === 192 && b === 0 && c === 2) || (a === 198 && b === 51 && c === 100) || (a === 203 && b === 0 && c === 113)
}

/** Whether the marker stands on a line of its own outside a fenced code block. */
function hasOptOut(lines) {
    let fence = null
    for (const line of lines) {
        const marker = line.match(/^ {0,3}(`{3,}|~{3,})(.*)$/)
        if (fence === null && marker) fence = marker[1]
        else if (fence !== null && marker && marker[1][0] === fence[0] && marker[1].length >= fence.length && marker[2].trim() === '') fence = null
        else if (fence === null && line.trim() === OPT_OUT_MARKER) return true
    }
    return false
}

/** The three RFC 1918 blocks, written as themselves. */
const RFC1918_BLOCKS = new Set(['10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16'])

/**
 * Whether an address followed by `rest` is one of the RFC 1918 blocks written in CIDR
 * notation, not followed by another digit, a dot or a slash. Only the blocks themselves
 * pass: a host with a prefix (an address /24 or /32) still names the host, a subnet such
 * as a /24 network still maps the internal network, and a URL path such as /2024/report
 * is not a prefix.
 */
function isRfc1918Block(ip, rest) {
    const prefix = rest.match(/^\/(\d{1,2})(?![\d./])/)
    return prefix !== null && RFC1918_BLOCKS.has(`${octets(ip).join('.')}/${prefix[1]}`)
}

/**
 * Every disallowed RFC 1918 address in a body, one entry per hit.
 *
 * @returns {Array<{line: number, range: string}>} `range` is the RFC 1918 block; the
 *          address itself is never returned.
 */
export function findPrivateNetworkAddresses(body) {
    const lines = body.split(/\r?\n/)
    if (hasOptOut(lines)) return []
    const hits = []
    lines.forEach((text, index) => {
        for (const match of text.matchAll(IPV4)) {
            const ip = match[1]
            if (isRfc1918Block(ip, text.slice(match.index + ip.length))) continue
            if (isDocumentationRange(ip)) continue
            const range = rfc1918Range(ip)
            if (range !== null) hits.push({ line: index + 1, range })
        }
    })
    return hits
}

/**
 * Check one body file and report. Returns the exit status rather than exiting, so a
 * test can import this module.
 */
export function main(path, { log = console.log, error = console.error } = {}) {
    if (!path) {
        error('usage: node .claude/skills/writing-issues/scripts/check-private-network-addresses.mjs <body-file>')
        return 2
    }

    // Prove each range can be seen before trusting a clean result, through the same
    // function the body goes through. The addresses are built from octets, so this file
    // never carries a literal private address.
    const control = [
        `ten ${[10, 1, 2, 3].join('.')}`,
        `twelve ${[172, 16, 0, 1].join('.')}`,
        `sixteen ${[192, 168, 1, 1].join('.')}`,
        'clean line',
    ].join('\n')
    const expected = '1:10.0.0.0/8 2:172.16.0.0/12 3:192.168.0.0/16'
    const found = findPrivateNetworkAddresses(control).map((h) => `${h.line}:${h.range}`).join(' ')
    if (found !== expected) {
        error('private-network-address check: SELF-CHECK FAILED, it cannot see what it is looking for.')
        error(`  expected "${expected}", got "${found}"`)
        return 2
    }

    let body
    try {
        body = readFileSync(path, 'utf8')
    } catch (failure) {
        error(`private-network-address check: could not read the body file (${failure.code ?? failure.message}). Nothing was checked.`)
        return 2
    }
    // A UTF-16 file (what Windows PowerShell 5.1 writes with > or Out-File) decodes as
    // text interleaved with NUL characters, which no address pattern matches.
    if (body.includes('\u0000') || body.startsWith('\uFFFD\uFFFD')) {
        error('private-network-address check: the body file is not UTF-8 text (UTF-16?). Nothing was checked; write it as UTF-8.')
        return 2
    }
    const hits = findPrivateNetworkAddresses(body)
    if (hits.length === 0) {
        log(`private-network-address check: clean (${body.split(/\r?\n/).length} lines read, self-check passed).`)
        return 0
    }
    for (const hit of hits) {
        log(`Line ${hit.line} of the body carries a private network address in ${hit.range}. Do not post it: use a documentation-range placeholder (RFC 5737), the RFC 1918 block itself in CIDR notation, or ${OPT_OUT_MARKER} on a line of its own when the body must discuss an address.`)
    }
    return 1
}

// import.meta.url is the real path, so argv[1] is resolved through any symlink before the
// comparison; otherwise a run through a symlinked directory (macOS /tmp, a subst drive)
// would skip main and exit 0 having checked nothing.
function invokedDirectly() {
    if (!process.argv[1]) return false
    try {
        return realpathSync(resolve(process.argv[1])) === realpathSync(fileURLToPath(import.meta.url))
    } catch {
        return false
    }
}

if (invokedDirectly()) {
    process.exitCode = main(process.argv[2])
}
