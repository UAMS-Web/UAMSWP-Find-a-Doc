#!/usr/bin/env node
// cspell:ignore ustar
/**
 * Fail when a GitHub download of this repository holds anything the plugin does not run.
 *
 * A download (zip or tarball) is `git archive` of the tagged commit, and `.gitattributes`
 * decides what it leaves out. This script runs the same `git archive` on `HEAD`, with
 * `--worktree-attributes` so the keep-list in the working tree is the one read, lists the
 * archive, and exits 1 naming every path that is synced agent configuration, documentation,
 * tests, scripts, a lock file or tool configuration. See the `keep-the-download-to-what-runs`
 * rule. The archive is read in this process (a tar header parser), so the check needs `git`
 * and nothing else, on macOS, Linux and Windows alike.
 *
 * Usage:  node check-download-archive.mjs [--root <dir>] [--allow <path>]...
 *   --root   the repository to check (default: the current directory)
 *   --allow  a path or folder (`docs`, `scripts/build.php`) this repository ships on purpose;
 *            repeatable. Anything allowed is still counted in the archive total.
 * Output: `archive: N file(s)` first, then each offending path, then a verdict line.
 * Exit:   0 = the archive holds nothing that is not the plugin.
 *         1 = it holds at least one offending path.
 *         2 = the check could not tell (not a repository, `git archive` failed, or the archive is empty).
 */
import { spawnSync } from 'node:child_process'
import { resolve } from 'node:path'
import { pathToFileURL } from 'node:url'

/** Top-level folders a download never needs: agent configuration, docs, tests, build tooling. */
export const OFFENDING_DIRECTORIES = [
    '.claude', '.agents', '.cursor', '.github', '.githooks', '.vscode', '.idea',
    'docs', 'tests', 'test', 'scripts', 'node_modules',
]

/** Top-level files a download never needs. A name ending in `*` is a prefix. */
export const OFFENDING_FILES = [
    'AGENTS.md', 'CLAUDE.md', 'composer.lock', 'package.json', 'package-lock.json', 'yarn.lock',
    'pnpm-lock.yaml', 'phpunit.xml', 'phpunit.xml.dist', 'pint.json', 'rector.php', 'phpstan.neon',
    'phpstan.neon.dist', 'local-ci.json', 'cspell.json', 'project-words.txt', 'infection.json5',
    '.gitattributes', '.gitignore', '.editorconfig', '.git-blame-ignore-revs', '.env*',
]

/**
 * The paths of a tar stream, from its headers (ustar, with pax extended headers for long
 * names, which `git archive` writes). Directories are returned too.
 *
 * @param {Buffer} tar
 * @returns {Array<{path: string, directory: boolean}>}
 */
export function tarEntries(tar) {
    const entries = []
    let pendingPath = null
    let offset = 0

    while (offset + 512 <= tar.length) {
        const header = tar.subarray(offset, offset + 512)
        if (header.every((byte) => byte === 0)) break

        const text = (start, length) => header.subarray(start, start + length).toString('utf8').replace(/\0.*$/s, '')
        const size = parseInt(text(124, 12).trim() || '0', 8)
        const type = text(156, 1) || '0'
        const prefix = text(345, 155)
        const name = prefix ? `${prefix}/${text(0, 100)}` : text(0, 100)
        const body = tar.subarray(offset + 512, offset + 512 + size)

        if (type === 'x') {
            // `<length> path=<value>\n` records; only the path matters here.
            for (const record of body.toString('utf8').split('\n')) {
                const match = /^\d+ path=(.*)$/.exec(record)
                if (match) pendingPath = match[1]
            }
        } else if (type === 'g') {
            // The global header carries the commit id, not a path.
        } else {
            const path = pendingPath ?? name
            pendingPath = null
            entries.push({ path: path.replace(/\/$/, ''), directory: type === '5' })
        }

        offset += 512 + Math.ceil(size / 512) * 512
    }

    return entries
}

/**
 * The offending paths among an archive's file paths.
 *
 * @param {Array<string>} paths File paths (directories excluded), repository-relative.
 * @param {Array<string>} allow Paths or folders shipped on purpose.
 * @returns {Array<string>}
 */
export function offendingPaths(paths, allow = []) {
    const allowed = allow.map((entry) => entry.replace(/^\.?\//, '').replace(/\/$/, ''))
    const isAllowed = (path) => allowed.some((entry) => path === entry || path.startsWith(`${entry}/`))

    return paths.filter((path) => {
        if (isAllowed(path)) return false
        const [top] = path.split('/')
        if (path.includes('/') && OFFENDING_DIRECTORIES.includes(top)) return true
        if (path.includes('/')) return false

        return OFFENDING_FILES.some((name) => (name.endsWith('*') ? path.startsWith(name.slice(0, -1)) : path === name))
    })
}

/**
 * @param {string} root
 * @returns {{files: Array<string>}|{error: string}}
 */
export function archiveFiles(root) {
    const result = spawnSync('git', ['-C', root, 'archive', '--worktree-attributes', '--format=tar', 'HEAD'], {
        maxBuffer: 1024 * 1024 * 1024,
    })

    if (result.error || result.status !== 0) {
        return { error: `git archive failed in ${root}: ${(result.error?.message ?? result.stderr?.toString('utf8') ?? '').trim()}` }
    }

    return { files: tarEntries(result.stdout).filter((entry) => !entry.directory).map((entry) => entry.path).sort() }
}

/**
 * @param {Array<string>} argv
 * @returns {{root: string, allow: Array<string>}}
 */
export function parseArgs(argv) {
    const options = { root: process.cwd(), allow: [] }

    for (let i = 0; i < argv.length; i++) {
        const value = argv[i + 1]
        if (argv[i] === '--root' || argv[i] === '--allow') {
            if (value === undefined || value === '' || value.startsWith('-')) throw new Error(`${argv[i]} needs a value`)
            if (argv[i] === '--root') options.root = resolve(value)
            else options.allow.push(value)
            i++
        } else {
            throw new Error(`Unknown argument: ${argv[i]}`)
        }
    }

    return options
}

if (process.argv[1] && import.meta.url === pathToFileURL(process.argv[1]).href) {
    try {
        const { root, allow } = parseArgs(process.argv.slice(2))
        const archive = archiveFiles(root)

        if (archive.error) {
            console.error(`check-download-archive: ${archive.error}`)
            process.exit(2)
        }

        // The denominator comes first: a crash after this line leaves a visibly incomplete run.
        console.log(`archive: ${archive.files.length} file(s)`)

        if (archive.files.length === 0) {
            console.error('check-download-archive: the archive is empty, so it cannot be told apart from a broken keep-list; check .gitattributes')
            process.exit(2)
        }

        const offending = offendingPaths(archive.files, allow)
        for (const path of offending) console.log(`  not for the download: ${path}`)

        if (offending.length > 0) {
            console.log(`FAIL: ${offending.length} of ${archive.files.length} file(s) in the download are not what the plugin runs; add a keep-list to .gitattributes (see the keep-the-download-to-what-runs rule)`)
            process.exit(1)
        }

        console.log(`PASS: nothing in the ${archive.files.length}-file download is synced configuration, docs, tests or build tooling`)
    } catch (error) {
        console.error(`check-download-archive: ${error.message}`)
        process.exit(2)
    }
}
