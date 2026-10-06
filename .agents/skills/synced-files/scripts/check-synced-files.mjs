#!/usr/bin/env node
// check-synced-files.mjs: fail when a file the shared sync manages has been edited, replaced
// or deleted in this repository (UAMS-Web/uams-claude-skills#30).
//
// The sync from UAMS-Web/uams-claude-skills writes the same rules and skills into
// `.claude/`, `.agents/` and `.cursor/`, and records a sha256 for each file it writes in
// `.claude/sync-manifest.jsonc`. A copy edited by hand makes the harnesses read different
// instructions until the next sync pull request quietly reverts it. This check compares
// every recorded file with its hash.
//
// Line endings are normalized (CRLF to LF) before hashing, so a Windows checkout with
// `core.autocrlf` does not fail; a change made only of line endings is not reported.
//
// Usage, from the repository root:
//     node .claude/skills/synced-files/scripts/check-synced-files.mjs [--root <dir>]
//
// Exit 0 clean, 1 a managed file differs, is missing or is not a regular file, 2 the check
// did not run: no manifest, a manifest that is not JSON, or one with no hashes. A pass is
// the printed "clean" line, not the exit status alone.
//
// Shipped by the shared sync from UAMS-Web/uams-claude-skills; its tests live there
// (scripts/check-synced-files.test.mjs) and are not synced.

import { createHash } from 'node:crypto'
import { lstatSync, readFileSync, realpathSync } from 'node:fs'
import { isAbsolute, join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

export const MANIFEST_PATH = '.claude/sync-manifest.jsonc'
export const SOURCE_REPO = 'UAMS-Web/uams-claude-skills'

const CR = 0x0d
const LF = 0x0a

/** The sha256 (hex) of a file's bytes with every CRLF turned into LF. */
export function hashContent(bytes) {
    const buf = Buffer.isBuffer(bytes) ? bytes : Buffer.from(bytes)
    const out = Buffer.allocUnsafe(buf.length)
    let n = 0
    for (let i = 0; i < buf.length; i++) {
        if (buf[i] === CR && buf[i + 1] === LF) continue
        out[n++] = buf[i]
    }
    return createHash('sha256').update(out.subarray(0, n)).digest('hex')
}

/** A manifest path the check may read: relative, forward slashes, no `..` segment. */
function isSafePath(rel) {
    return typeof rel === 'string' && rel !== '' && !isAbsolute(rel) && !rel.includes('\\') && !rel.split('/').some((part) => part === '..' || part === '')
}

/**
 * Compare every file the manifest records with its hash.
 *
 * @returns {{checked: number, problems: string[]}} One problem per file, naming it.
 */
export function checkSyncedFiles(root, manifest) {
    const problems = []
    const hashes = manifest.hashes
    const listed = Array.isArray(manifest.files) ? manifest.files : []
    for (const rel of listed) {
        if (!Object.hasOwn(hashes, rel)) problems.push(`${rel} is listed in ${MANIFEST_PATH} with no recorded hash; the manifest was edited by hand.`)
    }
    let checked = 0
    for (const [rel, expected] of Object.entries(hashes).sort(([a], [b]) => (a < b ? -1 : a > b ? 1 : 0))) {
        if (!isSafePath(rel)) {
            problems.push(`${JSON.stringify(rel)} in ${MANIFEST_PATH} is not a path inside this repository; the manifest was edited by hand.`)
            continue
        }
        checked++
        const abs = join(root, rel)
        let stat
        try {
            stat = lstatSync(abs)
        } catch {
            problems.push(`${rel} is missing.`)
            continue
        }
        if (!stat.isFile()) {
            problems.push(`${rel} is not a regular file${stat.isSymbolicLink() ? ' (it is a symbolic link)' : ''}.`)
            continue
        }
        if (hashContent(readFileSync(abs)) !== expected) problems.push(`${rel} was changed.`)
    }
    return { checked, problems }
}

function parseArgs(argv) {
    let root = process.cwd()
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--root' && argv[i + 1] !== undefined) root = argv[++i]
        else if (argv[i].startsWith('--root=')) root = argv[i].slice('--root='.length)
        else return { error: `unknown argument ${JSON.stringify(argv[i])}` }
    }
    return { root: resolve(root) }
}

/**
 * Run the check and report. Returns the exit status rather than exiting, so a test can
 * import this module.
 */
export function main(argv = [], { log = console.log, error = console.error } = {}) {
    const args = parseArgs(argv)
    if (args.error) {
        error(`synced-files check: ${args.error}`)
        error('usage: node .claude/skills/synced-files/scripts/check-synced-files.mjs [--root <dir>]')
        return 2
    }

    let text
    try {
        text = readFileSync(join(args.root, MANIFEST_PATH), 'utf8')
    } catch (failure) {
        error(`synced-files check: could not read ${MANIFEST_PATH} under ${args.root} (${failure.code ?? failure.message}). Run it from the repository root, or pass --root. Nothing was checked.`)
        return 2
    }
    let manifest
    try {
        manifest = JSON.parse(text)
    } catch {
        error(`synced-files check: ${MANIFEST_PATH} is not valid JSON. Nothing was checked.`)
        return 2
    }
    const hashes = manifest?.hashes
    if (hashes === null || typeof hashes !== 'object' || Array.isArray(hashes) || Object.keys(hashes).length === 0) {
        error(`synced-files check: ${MANIFEST_PATH} records no hashes. A sync from ${SOURCE_REPO} writes them. Nothing was checked.`)
        return 2
    }

    const { checked, problems } = checkSyncedFiles(args.root, manifest)
    if (problems.length === 0) {
        log(`synced-files check: clean (${checked} files match ${MANIFEST_PATH}).`)
        return 0
    }
    for (const problem of problems) log(`synced-files check: ${problem}`)
    log(`These files are written by the shared sync and must not be edited here. Make the change in ${SOURCE_REPO} (under shared/), and the next sync pull request brings it to this repository. To drop a local edit, restore the file from git.`)
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
    process.exitCode = main(process.argv.slice(2))
}
