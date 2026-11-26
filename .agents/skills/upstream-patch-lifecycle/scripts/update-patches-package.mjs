#!/usr/bin/env node
// update-patches-package.mjs: move one consumer to a named release of a `uams-web/patches-*`
// package (UAMS-Web/uams-claude-skills#883).
//
// WHY. Adopting a package release by hand is a sequence, and a step missed fails late: a patch the
// release added and nobody registered is never applied, and a patch file whose content changed
// under the same name fails the install with a hash mismatch until the lock is rewritten. The
// procedure is written in the skill's *Moving a consumer to the package*; this script performs it.
//
// WHAT IT DOES, in order (the skill's order):
//   1. Reads the release's declared patches (`extra.patches` of the package's composer.json at the
//      release tag), in the package's order.
//   2. Where the package sits in `require-dev`, registers each declared patch the root
//      `patches.json` lacks, leaving every other entry in place (a package in `require` is applied
//      by the plugin from its own declaration, so nothing is registered there).
//   3. Moves the package to the release: `composer update <package> --with-dependencies
//      --no-plugins --no-scripts --with <package>:<version>`.
//   4. `composer patches-relock`, then `composer patches-repatch`.
//   5. Runs `check-registered-patches.mjs` and fails when it does.
//
// A DESCRIPTION IS A KEY. Entries of one target are keyed by description, so a new entry whose
// description is already taken cannot sit beside the old one. When the taken key points at a file of
// this package that the release no longer declares (the release renamed the file), the entry is
// pointed at the new file in place. When it points anywhere else (a patch the consumer carries under
// that description), nothing is written and no command runs: the conflict is named, exit 1.
//
// WHERE A NEW ENTRY GOES. After the last entry of the same target package that already points into
// `vendor/<package>/`, so a patch the consumer keeps itself stays after the shared set; with none
// there yet, before every other entry of that target.
//
// WHAT IT DOES NOT DO. It does not retire an entry the release no longer declares: it names each one
// (a locked entry whose file is gone fails the install) and leaves it for a person. It does not
// commit, and it never changes `composer.json` (it reads it to find where the package is required). `composer patches-repatch` exits 0 even
// when a patch fails to apply (cweagans/composer-patches 2.0.0), so this script's last step proves
// registration, not application: run a `composer install` from a deleted `vendor/` to prove that.
//
// PRINT-ONLY. `--dry-run` reads the release and the consumer, prints the plan, runs no composer
// command and writes no file.
//
// EXIT CODES
//     0  the consumer is on the release and the check passed (or, with --dry-run, the plan printed)
//     1  a step failed, or the final check reported an unregistered patch
//     2  the script could not answer: bad arguments, an unreadable file, or a failing control
//
// USAGE
//     node .claude/skills/upstream-patch-lifecycle/scripts/update-patches-package.mjs \
//         --package uams-web/patches-<name> --release vX.Y.Z [--root <dir>] [--dry-run] \
//         [--manifest <file>]
//
//     --manifest <file>  read the package's composer.json from a local file instead of GitHub
//                        (the default reads it over REST at the release tag with `gh api`)

import { spawnSync } from 'node:child_process'
import { existsSync, readFileSync, writeFileSync } from 'node:fs'
import { join, resolve } from 'node:path'
import { pathToFileURL } from 'node:url'

import { checkRoot, PACKAGE_PREFIX, runControls as runCheckControls } from './check-registered-patches.mjs'

const normalize = (path) => String(path).replace(/\\/g, '/').replace(/^\.\//, '')

/** The package's declared patches as `{ target, description, file }`, in the order declared. */
export function declaredFrom(manifest) {
    const patches = manifest?.extra?.patches
    const found = []
    if (patches === null || typeof patches !== 'object') {
        return found
    }
    for (const [target, entries] of Object.entries(patches)) {
        const pairs = Array.isArray(entries)
            ? entries.map((value, index) => [typeof value === 'object' && value !== null ? (value.description ?? `patch ${index + 1}`) : `patch ${index + 1}`, value])
            : entries !== null && typeof entries === 'object'
              ? Object.entries(entries)
              : []
        for (const [description, value] of pairs) {
            const file = typeof value === 'string' ? value : value?.url
            if (typeof file === 'string' && file !== '') {
                found.push({ target, description, file: normalize(file) })
            }
        }
    }
    return found
}

/**
 * Register the declared patches a root `patches.json` lacks.
 *
 * @param {object} patchesJson parsed root patches.json (or `{}` for none)
 * @param {Array<{target:string, description:string, file:string}>} declared the release's patches, in order
 * @param {string} pkg the package name, whose `vendor/<pkg>/` path marks an entry as shared
 * @returns {{ result: object, added: Array, updated: Array, conflicts: Array, stale: Array }} the new document;
 *   the entries added; the entries pointed at a renamed file of the package, in place; the declared patches
 *   whose description is already taken by another file (left unwritten); and the registered entries
 *   pointing into the package that the release no longer declares
 */
export function register(patchesJson, declared, pkg) {
    const document = { ...patchesJson, patches: { ...(patchesJson.patches ?? {}) } }
    const prefix = `vendor/${pkg}/`
    const fileOf = (value) => normalize(typeof value === 'string' ? value : (value?.url ?? ''))
    const declaredFiles = new Set(declared.map((patch) => patch.file))
    const have = new Set(Object.values(document.patches).flatMap((entries) => Object.values(entries ?? {}).map(fileOf)))
    const added = []
    const updated = []
    const conflicts = []
    for (const target of [...new Set(declared.map((patch) => patch.target))]) {
        const missing = declared.filter((patch) => patch.target === target && !have.has(patch.file))
        if (missing.length === 0) {
            continue
        }
        let entries = Object.entries(document.patches[target] ?? {})
        const adding = []
        for (const patch of missing) {
            const taken = entries.findIndex(([description]) => description === patch.description)
            if (taken === -1) {
                adding.push(patch)
                continue
            }
            const existing = fileOf(entries[taken][1])
            if (existing.startsWith(prefix) && !declaredFiles.has(existing)) {
                entries[taken] = [patch.description, patch.file]
                updated.push({ ...patch, from: existing })
            } else {
                conflicts.push({ ...patch, existing })
            }
        }
        let after = -1
        entries.forEach(([, value], index) => {
            if (fileOf(value).startsWith(prefix)) {
                after = index
            }
        })
        entries = [...entries.slice(0, after + 1), ...adding.map((patch) => [patch.description, patch.file]), ...entries.slice(after + 1)]
        document.patches[target] = Object.fromEntries(entries)
        added.push(...adding)
    }
    const stale = Object.entries(document.patches).flatMap(([target, entries]) =>
        Object.entries(entries ?? {})
            .map(([description, value]) => ({ target, description, file: fileOf(value) }))
            .filter((entry) => entry.file.startsWith(prefix) && !declaredFiles.has(entry.file)),
    )
    return { result: document, added, updated, conflicts, stale }
}

/** Where the package is required: `require`, `require-dev`, or `none`. */
export function requireSection(composerJson, pkg) {
    if (composerJson?.require?.[pkg] !== undefined) {
        return 'require'
    }
    if (composerJson?.['require-dev']?.[pkg] !== undefined) {
        return 'require-dev'
    }
    return 'none'
}

/** The locked version of the package, or undefined. */
export function lockedVersion(lock, pkg) {
    const all = [...(lock?.packages ?? []), ...(lock?.['packages-dev'] ?? [])]
    return all.find((entry) => String(entry.name).toLowerCase() === pkg)?.version
}

const readJson = (path, label) => {
    try {
        return JSON.parse(readFileSync(path, 'utf8'))
    } catch (error) {
        throw new Error(`${label} (${path}) cannot be read as JSON: ${error.message}`)
    }
}

/** The package's composer.json at a release tag, read over REST. */
export function fetchManifest(pkg, release) {
    const repo = `UAMS-Web/${pkg.split('/')[1]}`
    const r = spawnSync('gh', ['api', `repos/${repo}/contents/composer.json?ref=${release}`, '--jq', '.content'], { encoding: 'utf8', shell: process.platform === 'win32' })
    if (r.status !== 0) {
        throw new Error(`could not read composer.json of ${repo} at ${release}: ${(r.stderr || r.stdout || '').trim() || `exit ${r.status}`}`)
    }
    try {
        return JSON.parse(Buffer.from(r.stdout.trim(), 'base64').toString('utf8'))
    } catch (error) {
        throw new Error(`composer.json of ${repo} at ${release} is not JSON: ${error.message}`)
    }
}

/** Run composer in a directory; returns the exit status. */
export function runComposer(args, cwd) {
    const r = spawnSync('composer', args, { cwd, stdio: 'inherit', shell: process.platform === 'win32' })
    return r.status ?? 1
}

const stringify = (value) => `${JSON.stringify(value, null, 4)}\n`

/**
 * Plan and perform the move. `deps` injects the effects so a test runs with no network and no composer.
 *
 * @returns {{ code: number, lines: string[] }}
 */
export function update({ root, pkg, release, dryRun, manifest, deps = {} }) {
    const composer = deps.composer ?? runComposer
    const lines = []
    const version = release.replace(/^v/, '')
    const composerJsonPath = join(root, 'composer.json')
    const lockPath = join(root, 'composer.lock')
    const patchesPath = join(root, 'patches.json')
    for (const [path, label] of [[composerJsonPath, 'composer.json'], [lockPath, 'composer.lock']]) {
        if (!existsSync(path)) {
            return { code: 2, lines: [`update-patches-package: no ${label} at ${path}.`] }
        }
    }
    let composerJson
    let lock
    let patchesJson = {}
    let declared
    try {
        composerJson = readJson(composerJsonPath, 'composer.json')
        lock = readJson(lockPath, 'composer.lock')
        if (existsSync(patchesPath)) {
            patchesJson = readJson(patchesPath, 'patches.json')
        }
        declared = declaredFrom(manifest ?? (deps.fetchManifest ?? fetchManifest)(pkg, release))
    } catch (error) {
        return { code: 2, lines: [`update-patches-package: ${error.message}`] }
    }
    if (declared.length === 0) {
        return { code: 2, lines: [`update-patches-package: ${pkg} ${release} declares no patch, so there is nothing to register or relock. Check the release name.`] }
    }

    const section = requireSection(composerJson, pkg)
    if (section === 'none') {
        return { code: 2, lines: [`update-patches-package: ${pkg} is in neither require nor require-dev of composer.json. Move a consumer that already requires the package; the first adoption is the skill's *Moving a consumer to the package*.`] }
    }
    const listForm = section === 'require-dev' ? [...new Set(declared.map((patch) => patch.target))].filter((target) => Array.isArray(patchesJson.patches?.[target])) : []
    if (listForm.length > 0 || (section === 'require-dev' && Array.isArray(patchesJson.patches))) {
        return { code: 2, lines: [`update-patches-package: patches.json lists the patches of ${listForm.join(', ') || 'its patches key'} as an array. This script reads and writes the object form (description: file). Convert it by hand, or register the patches by hand.`] }
    }
    const before = lockedVersion(lock, pkg)
    const registration = section === 'require-dev' ? register(patchesJson, declared, pkg) : { result: patchesJson, added: [], updated: [], conflicts: [], stale: [] }

    lines.push(`update-patches-package: ${pkg} ${before ?? '(not locked)'} -> ${release}, required in ${section}, ${declared.length} patch(es) declared by the release.`)
    if (section === 'require') {
        lines.push('  register: none (the package is in require, so the plugin applies its own declaration).')
    } else if (registration.added.length + registration.updated.length + registration.conflicts.length === 0) {
        lines.push('  register: none missing from patches.json.')
    } else {
        lines.push(`  register: ${registration.added.length} added, ${registration.updated.length} updated, ${registration.conflicts.length} in conflict:`)
        for (const patch of registration.added) {
            lines.push(`    + ${patch.file}  (patches ${patch.target})`)
        }
        for (const patch of registration.updated) {
            lines.push(`    ~ ${patch.from} -> ${patch.file}  (patches ${patch.target}, same description: the release renamed the file)`)
        }
        for (const patch of registration.conflicts) {
            lines.push(`    ! CONFLICT: ${patch.file} is declared as "${patch.description}" for ${patch.target}, but patches.json already uses that description for ${patch.existing}`)
        }
    }
    if (registration.conflicts.length > 0) {
        lines.push('update-patches-package: FAIL: a description in patches.json is already taken by a file the release does not own. Nothing was written and no command ran. Rename that description in patches.json (or retire that patch), then run this again.')
        return { code: 1, lines }
    }
    for (const patch of registration.stale) {
        lines.push(`  WARNING: patches.json registers ${patch.file}, which ${release} no longer declares; remove it by hand, or the install fails on a missing file.`)
    }
    const commands = [
        ['update', pkg, '--with-dependencies', '--no-plugins', '--no-scripts', '--no-interaction', '--with', `${pkg}:${version}`],
        ['patches-relock', '--no-interaction'],
        ['patches-repatch', '--no-interaction'],
    ]
    for (const command of commands) {
        lines.push(`  ${dryRun ? 'would run' : 'run'}: composer ${command.join(' ')}`)
    }
    lines.push(`  ${dryRun ? 'would run' : 'run'}: check-registered-patches`)

    if (dryRun) {
        lines.push('  print-only: no file written and no command run.')
        return { code: 0, lines }
    }

    const changedPatches = registration.added.length + registration.updated.length > 0
    if (changedPatches) {
        writeFileSync(patchesPath, stringify(registration.result))
    }
    const ran = []
    for (const command of commands) {
        const status = composer(command, root)
        if (status !== 0) {
            const touched = [...(changedPatches ? ['patches.json was rewritten'] : []), ...(ran.length > 0 ? [`composer ${ran.join(', composer ')} already ran, so composer.lock, patches.lock.json or vendor/ may have changed`] : [])]
            lines.push(`update-patches-package: FAIL: composer ${command[0]} exited ${status}. ${touched.length > 0 ? `${touched.join('; ')}: review git diff before running anything else.` : 'Before this command the script had changed no file.'}`)
            return { code: 1, lines }
        }
        ran.push(command[0])
    }
    const check = checkRoot(root)
    lines.push(...check.lines)
    if (check.code !== 0) {
        lines.push(`update-patches-package: FAIL: check-registered-patches reported ${check.code === 1 ? 'an unregistered patch' : 'that it could not answer'}.`)
        return { code: check.code === 1 ? 1 : 2, lines }
    }
    lines.push(`update-patches-package: done. Prove it applies: composer install from a deleted vendor/, then commit composer.lock, patches.json (if changed) and patches.lock.json together.`)
    return { code: 0, lines }
}

/** Parse argv; returns `{ options }` or `{ error }`. */
export function parseArgs(argv) {
    const options = { root: process.cwd(), dryRun: false }
    for (let i = 0; i < argv.length; i++) {
        const arg = argv[i]
        const value = () => (argv[i + 1] !== undefined && !argv[i + 1].startsWith('--') ? argv[++i] : undefined)
        if (arg === '--dry-run') {
            options.dryRun = true
        } else if (arg === '--package') {
            options.pkg = value()
        } else if (arg === '--release') {
            options.release = value()
        } else if (arg === '--root') {
            const dir = value()
            options.root = dir === undefined ? undefined : resolve(dir)
        } else if (arg === '--manifest') {
            options.manifestPath = value()
        } else {
            return { error: `unknown argument ${arg}` }
        }
    }
    if (options.root === undefined) {
        return { error: '--root needs a directory' }
    }
    if (typeof options.pkg !== 'string' || !options.pkg.toLowerCase().startsWith(PACKAGE_PREFIX)) {
        return { error: `--package must name a ${PACKAGE_PREFIX}* package` }
    }
    options.pkg = options.pkg.toLowerCase()
    if (typeof options.release !== 'string' || !/^v\d+\.\d+\.\d+$/.test(options.release)) {
        return { error: '--release must be a tag such as v1.5.1' }
    }
    return { options }
}

export function main(argv = process.argv.slice(2), deps = {}) {
    const { options, error } = parseArgs(argv)
    if (error !== undefined) {
        console.error(`update-patches-package: ${error}`)
        return 2
    }
    const failed = runCheckControls()
    if (failed.length > 0) {
        console.error(`update-patches-package: a check control failed, so the check is not trusted: ${failed.join('; ')}`)
        return 2
    }
    let manifest
    if (options.manifestPath !== undefined) {
        try {
            manifest = readJson(options.manifestPath, '--manifest')
        } catch (e) {
            console.error(`update-patches-package: ${e.message}`)
            return 2
        }
    }
    const { code, lines } = update({ root: options.root, pkg: options.pkg, release: options.release, dryRun: options.dryRun, manifest, deps })
    for (const line of lines) {
        ;(code === 0 ? console.log : console.error)(line)
    }
    return code
}

if (import.meta.url === pathToFileURL(process.argv[1] ?? '').href) {
    process.exitCode = main()
}
