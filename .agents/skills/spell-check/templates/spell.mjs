#!/usr/bin/env node
/**
 * Run cspell over the repository and refuse a run that checked no file.
 *
 * Shipped by the shared `spell-check` skill (UAMS-Web/uams-claude-skills#173). Copy it to
 * `scripts/spell.mjs` and add `"spell": "node scripts/spell.mjs"` to package.json. The local CI runner's
 * `spell` job and the `spell` job in `.github/workflows/ci.yml` both run `npm run spell`.
 *
 * A glob that stops matching makes cspell report "Files checked: 0" and, depending on its version and
 * flags, exit 0. The check then passes over nothing, and a misspelling reaches `main` with nothing red.
 * The count is therefore read from cspell's own summary line: zero, or no summary at all, is a failure
 * whatever cspell's exit status says.
 *
 * Exit codes:
 *   0  cspell passed and checked at least MINIMUM_FILES files
 *   1  cspell reported issues (its own exit passed through)
 *   2  the run is not trustworthy: cspell is not installed, printed no count, or checked too few files
 *
 * Usage:  node scripts/spell.mjs          (via `npm run spell`)
 */
import { spawnSync } from 'node:child_process'
import { readFileSync } from 'node:fs'
import { dirname, join, resolve } from 'node:path'
import { fileURLToPath, pathToFileURL } from 'node:url'

/** What cspell reads. One brace glob, so "matched nothing" is one answer rather than one per pattern. */
export const GLOB = '**/*.{md,php,js,mjs,ts,vue,yml,yaml}'

/**
 * The fewest files a trustworthy run checks. One is the floor that catches a glob matching nothing; raise
 * it to a figure well below the repository's real count to catch a glob that stopped matching most of it.
 * Tuned too tight it fails on every ordinary deletion, which teaches people to ignore it.
 */
export const MINIMUM_FILES = 1

/** Parse `CSpell: Files checked: N, ...` out of cspell's output, or null when there is no such line. */
export function filesChecked(output) {
    const match = /Files checked:\s*(\d+)/.exec(String(output))
    return match ? Number(match[1]) : null
}

/**
 * Why this run is not trustworthy, or null when it is. Separate from the spawn so the failing arm,
 * which a healthy repository never reaches, can be tested without running cspell.
 */
export function coverageRefusal(count, minimum = MINIMUM_FILES) {
    if (count === null) {
        return 'spell: cspell printed no "Files checked" line, so it cannot be shown that anything was checked.\n'
            + 'A result that cannot be parsed is not a pass: cspell may have changed its summary format.'
    }
    if (count < minimum) {
        return `spell: cspell checked ${count} file(s), below the floor of ${minimum}.\n`
            + `A run that matches nothing reports success having checked nothing. Check GLOB in this file (${GLOB}).`
    }
    return null
}

/** The cspell entry point `npm ci` installed under `root`, or null when it is not installed. */
export function cspellBin(root) {
    try {
        const manifestPath = join(root, 'node_modules', 'cspell', 'package.json')
        const { bin } = JSON.parse(readFileSync(manifestPath, 'utf8'))
        return join(dirname(manifestPath), typeof bin === 'string' ? bin : bin.cspell)
    } catch {
        return null
    }
}

/** Run the check in `root`. Returns `{status, output}`; the caller prints and exits. */
export function runSpell(root = process.cwd()) {
    const bin = cspellBin(root)
    if (bin === null) return { status: 2, output: 'spell: cspell is not installed. Run `npm ci` at the repository root first.\n' }

    const result = spawnSync(process.execPath, [bin, 'lint', '--dot', '--no-progress', GLOB], { cwd: root, encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
    const output = `${result.stdout ?? ''}${result.stderr ?? ''}${result.error ? `\n${result.error.message}\n` : ''}`

    const refusal = coverageRefusal(filesChecked(output))
    if (refusal !== null) return { status: 2, output: `${output}\n${refusal}\n` }
    return { status: result.status === 0 ? 0 : 1, output }
}

// Run only when invoked as a script, so a test can import the helpers above.
if (process.argv[1] && import.meta.url === pathToFileURL(resolve(process.argv[1])).href) {
    const { status, output } = runSpell()
    process.stdout.write(output)
    process.exit(status)
}
