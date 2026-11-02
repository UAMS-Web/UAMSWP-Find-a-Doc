#!/usr/bin/env node
//
// check-no-emoji.mjs: sweep a repository's durable prose for the glyphs the
// no-emoji-in-durable-records rule bans (UAMS-Web/uams-claude-skills#156).
//
// Scope is the path classes the rule names, from the repository root:
//     .claude/   every file under it, except .claude/worktrees/ (ephemeral checkouts)
//     docs/      every file under it
//     README.md, AGENTS.md, and CLAUDE.md where the repository has one
// A path that is absent is announced and skipped. Third-party and fixture directories
// (node_modules, vendor, .git) are never read, as the rule's "What this does not cover" says.
//
// THE EXPRESSION IS READ OUT OF THE RULE, NEVER RESTATED HERE, through glyphs.mjs beside
// this file: the rule defines the character class once, and a second copy would drift.
//
// THREE CONTROLS RUN ON EVERY INVOCATION, BEFORE THE SWEEP, AND A FAILING ONE IS EXIT 2:
//     1. every bound of every range in the class must match;
//     2. clean ASCII prose must not match;
//     3. a multi-line buffer of known UTF-8 BYTES goes through the same decode-and-scan path
//        the files take and must yield the exact code points on the exact lines. That is the
//        layer a correct pattern fails at when its input is read as Latin-1: no error, exit 0,
//        and a count that looks right.
//
// THE FILE COUNT IS PRINTED BEFORE ANYTHING IS SCANNED. A crash after the count leaves a
// visibly incomplete run; a count printed after the scan dies with it and reads as clean.
//
// FINDINGS ARE CODE POINTS, NEVER THE CHARACTER. Echoing the glyph crashes on the legacy
// code page the rule exists to protect, and reports the crash as a short result.
//
// EXIT CODES
//     0  swept, controls held, no glyph found
//     1  at least one glyph found; findings printed as path:line: U+XXXX
//     2  the instrument is broken: rule unreadable or not understood, a control failed, a
//        file could not be read, or nothing was read
//
// This sweeps the tree. It does not read a draft that is not in it; the writing-comments
// skill has the one-file check for that.
//
// USAGE
//     node .claude/skills/writing-comments/scripts/check-no-emoji.mjs [--root <dir>]

import { existsSync, readdirSync, readFileSync, realpathSync, statSync } from 'node:fs'
import { join, relative, resolve, sep } from 'node:path'
import { fileURLToPath } from 'node:url'

import { buildPattern, codePointName, DEFAULT_RULE_PATH, extractGlyphClass, parseCodePointClass, runControlArms, scanText } from './glyphs.mjs'

export const DIRECTORIES = ['.claude', 'docs']
export const FILES = ['README.md', 'AGENTS.md', 'CLAUDE.md']

const SKIP_DIRECTORIES = new Set(['.git', 'node_modules', 'vendor'])
// Ephemeral worktrees sit under .claude/worktrees/ and hold whole other checkouts.
const SKIP_PATHS = new Set(['.claude/worktrees'])

// UTF-8 bytes for U+26A0 U+FE0F on line 2 and U+1F680 on line 4 of an otherwise clean buffer.
const CONTROL_BYTES = Buffer.from([
    ...Buffer.from('clean first line\r\n'),
    0xe2, 0x9a, 0xa0, 0xef, 0xb8, 0x8f, ...Buffer.from(' marked\n'),
    ...Buffer.from('clean third line\n'),
    0xf0, 0x9f, 0x9a, 0x80, ...Buffer.from(' marked\n'),
])
const CONTROL_EXPECTED = [
    { line: 2, codePoints: ['U+26A0', 'U+FE0F'] },
    { line: 4, codePoints: ['U+1F680'] },
]

/** The one path every file takes: bytes, decoded as UTF-8, split by line, scanned. */
export function sweepBuffer(buffer, pattern) {
    return scanText(buffer.toString('utf8'), pattern)
}

/** Control 3, as failure messages: empty when the byte-level path finds exactly the expected. */
export function runByteControl(pattern) {
    const found = sweepBuffer(CONTROL_BYTES, pattern)
    const render = (list) => list.map(({ line, codePoints }) => `line ${line}: ${codePoints.join(' ')}`).join('; ') || 'nothing'
    return render(found) === render(CONTROL_EXPECTED) ? [] : [`byte arm: known UTF-8 bytes scanned as ${render(found)}, expected ${render(CONTROL_EXPECTED)}`]
}

const display = (root, file) => relative(root, file).split(sep).join('/')

function collect(root, directory, files) {
    for (const entry of readdirSync(directory, { withFileTypes: true })) {
        const path = join(directory, entry.name)
        if (entry.isDirectory()) {
            if (!SKIP_DIRECTORIES.has(entry.name) && !SKIP_PATHS.has(display(root, path))) collect(root, path, files)
        } else if (entry.isFile()) {
            files.push(path)
        }
    }
}

export function main(argv = process.argv.slice(2), { cwd = process.cwd(), log = console.log, error = console.error, rulePath = DEFAULT_RULE_PATH } = {}) {
    const rootFlag = argv.indexOf('--root')
    if (rootFlag !== -1 && argv[rootFlag + 1] === undefined) {
        error('glyph sweep cannot run: --root needs a directory.')
        return 2
    }
    const root = rootFlag === -1 ? cwd : resolve(cwd, argv[rootFlag + 1])

    let ranges
    let pattern
    try {
        ranges = parseCodePointClass(extractGlyphClass(readFileSync(rulePath, 'utf8'), rulePath))
        pattern = buildPattern(ranges)
    } catch (failure) {
        error(`glyph sweep cannot run: ${failure.message}`)
        return 2
    }

    const controlFailures = [...runControlArms(ranges, pattern), ...runByteControl(pattern)]
    if (controlFailures.length > 0) {
        error('glyph sweep control arms FAILED, refusing to report on the tree:')
        controlFailures.forEach((failure) => error(`  ${failure}`))
        return 2
    }
    log(`Controls held: ${ranges.length} range(s) matched at both bounds, clean prose unmatched, known bytes found on the right lines.`)

    const files = []
    const absent = []
    try {
        for (const name of DIRECTORIES) {
            const path = resolve(root, name)
            if (existsSync(path) && statSync(path).isDirectory()) collect(root, path, files)
            else absent.push(name)
        }
        for (const name of FILES) {
            const path = resolve(root, name)
            if (existsSync(path) && statSync(path).isFile()) files.push(path)
            else absent.push(name)
        }
    } catch (failure) {
        error(`glyph sweep cannot run: could not list files under ${root}: ${failure.message}`)
        return 2
    }
    absent.forEach((name) => log(`Not present, skipped: ${name}`))

    // The denominator, before any file is read.
    log(`Files to sweep: ${files.length}`)
    if (files.length === 0) {
        error(`glyph sweep cannot run: none of ${[...DIRECTORIES, ...FILES].join(', ')} exists under ${root}, so nothing would be read.`)
        return 2
    }

    const findings = []
    const skipped = []
    for (const file of files) {
        let buffer
        try {
            buffer = readFileSync(file)
        } catch (failure) {
            error(`glyph sweep cannot run: could not read ${display(root, file)}: ${failure.message}`)
            return 2
        }
        // Announced, never silent: a quiet skip is how a real finding disappears.
        if (buffer.includes(0)) {
            skipped.push(display(root, file))
            continue
        }
        for (const { line, codePoints } of sweepBuffer(buffer, pattern)) findings.push(`${display(root, file)}:${line}: ${codePoints.join(' ')}`)
    }
    skipped.forEach((file) => log(`Skipped (contains a NUL byte, not text): ${file}`))

    if (files.length === skipped.length) {
        error('glyph sweep cannot run: every file was skipped as binary, so nothing was read as text.')
        return 2
    }
    log(`Files scanned: ${files.length - skipped.length}`)

    if (findings.length > 0) {
        error('')
        error('Glyphs found; see .claude/rules/no-emoji-in-durable-records.md:')
        findings.forEach((finding) => error(`  ${finding}`))
        error('Replace each with the sentence it was abbreviating. Code points are printed rather than the characters, per the rule.')
        return 1
    }
    log('No glyphs found.')
    return 0
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
