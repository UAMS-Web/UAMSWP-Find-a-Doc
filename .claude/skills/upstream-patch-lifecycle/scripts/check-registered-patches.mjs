#!/usr/bin/env node
// check-registered-patches.mjs: fail when a locked `uams-web/patches-*` package declares a patch
// that composer-patches did not resolve into `patches.lock.json`
// (UAMS-Web/uams-claude-skills#768, reworked in UAMS-Web/uams-claude-skills#797).
//
// WHY. A patch is applied only when composer-patches resolved it into `patches.lock.json`, which
// records what the plugin will apply. Where a patches package sits in `require-dev`, the plugin
// reads its declared patches only from the root registration, so a pin move that adds a declared
// patch leaves it unapplied until the root list gains it and the lock is relocked (measured on
// UAMS-Web/aether-redirects#134). Where the package sits in `require`, the plugin reads the
// declaration itself and no root registration exists (UAMS-Web/uams-statamic#756). Reading the
// outcome, `patches.lock.json`, answers both layouts with one comparison instead of inferring from
// where the package is required.
//
// WHAT IT READS.
//   composer.lock        every package named `uams-web/patches-*`, from `packages` and
//                        `packages-dev`, and its `extra.patches` (a map of target package to patches).
//   patches.lock.json    the `url` of every patch under its `patches` key, whatever its provenance
//                        (`root`, `patches-file:...`, `dependency:...`).
// A patch is matched by its file path (slashes normalized, a leading `./` dropped), because the
// description is free text and may differ between the package and the lock.
//
// A repository with no declared patch passes. A repository that declares a patch and has no
// `patches.lock.json` fails and names every file: a `--no-dev` relock deletes the file, and a
// missing file must not read as a clean result.
//
// CONTROLS, run before the repository is read: a fixture whose lock omits one declared patch must
// fail and name that file (and so must one omitting a patch declared under a second target package
// key, since a package may patch several packages), a fixture whose lock holds every declared patch
// must pass whether the package is in `packages` or `packages-dev`, and a fixture with no patches
// package must pass. A control misbehaving is exit 2.
//
// EXIT CODES
//     0  every declared patch is in patches.lock.json, or there is no declared patch
//     1  a declared patch is missing from patches.lock.json, or the file is absent; each file is named
//     2  the check could not answer: a control failed, or composer.lock or patches.lock.json is
//        unreadable or is not JSON
//
// USAGE
//     node .claude/skills/upstream-patch-lifecycle/scripts/check-registered-patches.mjs [--root <dir>]

import { existsSync, readFileSync } from 'node:fs'
import { join, resolve } from 'node:path'
import { pathToFileURL } from 'node:url'

export const PACKAGE_PREFIX = 'uams-web/patches-'

const normalize = (path) => String(path).replace(/\\/g, '/').replace(/^\.\//, '')

/**
 * The patch file paths under one `extra.patches` value, as `{ target, file }`. Composer accepts a
 * map of description to path, and a list of strings or of objects with a `url`.
 */
export function listPatches(patches) {
    const found = []
    if (patches === null || typeof patches !== 'object') {
        return found
    }
    for (const [target, entries] of Object.entries(patches)) {
        const values = Array.isArray(entries) ? entries : entries !== null && typeof entries === 'object' ? Object.values(entries) : []
        for (const value of values) {
            const file = typeof value === 'string' ? value : value !== null && typeof value === 'object' ? value.url : undefined
            if (typeof file === 'string' && file !== '') {
                found.push({ target, file: normalize(file) })
            }
        }
    }
    return found
}

/**
 * Every patch declared by a locked `uams-web/patches-*` package, as `{ pkg, version, target, file }`.
 */
export function declaredPatches(lock) {
    const packages = [...(lock.packages ?? []), ...(lock['packages-dev'] ?? [])]
    return packages
        .filter((p) => typeof p.name === 'string' && p.name.toLowerCase().startsWith(PACKAGE_PREFIX))
        .flatMap((p) => listPatches(p.extra?.patches).map((patch) => ({ pkg: p.name, version: p.version ?? '', ...patch })))
}

/**
 * The declared patches whose file is not in the recorded set.
 *
 * @param {object} lock parsed composer.lock
 * @param {Iterable<string>} recorded patch file paths recorded in patches.lock.json
 */
export function findOmitted(lock, recorded) {
    const have = new Set([...recorded].map(normalize))
    return declaredPatches(lock).filter((patch) => !have.has(patch.file))
}

/** The patch file paths a parsed `patches.lock.json` records, whatever the target package or provenance. */
export function lockedFiles(patchesLock) {
    const patches = patchesLock !== null && typeof patchesLock === 'object' ? patchesLock.patches : undefined
    if (patches === null || typeof patches !== 'object') {
        return []
    }
    return Object.values(patches)
        .flatMap((entries) => (Array.isArray(entries) ? entries : []))
        .map((entry) => entry?.url)
        .filter((url) => typeof url === 'string' && url !== '')
        .map(normalize)
}

const FIXTURE_LOCK = {
    packages: [],
    'packages-dev': [
        {
            name: 'uams-web/patches-example',
            version: 'v1.0.0',
            extra: { patches: { 'vendor/target': { 'First (temporary)': 'vendor/uams-web/patches-example/patches/first.patch', 'Second (temporary)': 'vendor/uams-web/patches-example/patches/second.patch' } } },
        },
        { name: 'other/package', version: '1.0.0', extra: { patches: { 'x/y': { 'Not ours': 'vendor/other/package/patches/ignored.patch' } } } },
    ],
}

/** Run the controls; returns the failures. */
export function runControls() {
    const failed = []
    const first = 'vendor/uams-web/patches-example/patches/first.patch'
    const second = 'vendor/uams-web/patches-example/patches/second.patch'
    const lockOf = (...urls) => ({ patches: { 'vendor/target': urls.map((url) => ({ package: 'vendor/target', url })) } })
    const omitted = findOmitted(FIXTURE_LOCK, lockedFiles(lockOf(first)))
    if (omitted.length !== 1 || omitted[0].file !== second) {
        failed.push('a patches.lock.json omitting one declared patch did not report exactly that file')
    }
    // A package declares patches under more than one target key; an omission under the second key must be seen.
    const twoTargets = { packages: [{ name: 'uams-web/patches-two', version: 'v1', extra: { patches: { 'a/one': { x: 'p/one.patch' }, 'b/two': { y: 'p/two.patch' } } } }] }
    const second_key = findOmitted(twoTargets, lockedFiles(lockOf('p/one.patch')))
    if (second_key.length !== 1 || second_key[0].file !== 'p/two.patch' || second_key[0].target !== 'b/two') {
        failed.push('a patches.lock.json omitting a patch declared under a second target package did not report that file')
    }
    // The same full lock passes whether the package sits in `packages` (require) or `packages-dev` (require-dev).
    const full = lockedFiles(lockOf(first, `./${second}`))
    const inRequire = { packages: FIXTURE_LOCK['packages-dev'], 'packages-dev': [] }
    if (findOmitted(FIXTURE_LOCK, full).length !== 0 || findOmitted(inRequire, full).length !== 0) {
        failed.push('a patches.lock.json holding every declared patch was reported')
    }
    if (findOmitted({ packages: [{ name: 'a/b', version: '1' }], 'packages-dev': [] }, []).length !== 0) {
        failed.push('a lock with no patches package was reported')
    }
    return failed
}

function readJson(path, label) {
    try {
        return JSON.parse(readFileSync(path, 'utf8'))
    } catch (error) {
        throw new Error(`${label} (${path}) cannot be read as JSON: ${error.message}`)
    }
}

/** Check a repository root. Returns `{ code, lines }`. */
export function checkRoot(root) {
    const lockPath = join(root, 'composer.lock')
    if (!existsSync(lockPath)) {
        return { code: 0, lines: [`check-registered-patches: no composer.lock at ${lockPath}, so no patches package is locked: nothing to check.`] }
    }
    let lock
    let patchesLock
    const patchesLockPath = join(root, 'patches.lock.json')
    try {
        lock = readJson(lockPath, 'composer.lock')
        patchesLock = existsSync(patchesLockPath) ? readJson(patchesLockPath, 'patches.lock.json') : undefined
    } catch (error) {
        return { code: 2, lines: [`check-registered-patches: ${error.message}`] }
    }

    const declared = declaredPatches(lock)
    if (declared.length === 0) {
        return { code: 0, lines: ['check-registered-patches: no locked uams-web/patches-* package declares a patch: nothing to check.'] }
    }
    if (patchesLock === undefined) {
        return {
            code: 1,
            lines: [
                `check-registered-patches: FAIL: ${declared.length} declared patch(es) and no patches.lock.json at ${patchesLockPath}, so none is recorded as applied (a relock with --no-dev deletes the file):`,
                ...declared.map((p) => `  ${p.file}  (${p.pkg} ${p.version}, patches ${p.target})`),
                'Run composer patches-relock, then composer patches-repatch, and commit patches.lock.json.',
            ],
        }
    }
    const omitted = findOmitted(lock, lockedFiles(patchesLock))
    if (omitted.length === 0) {
        return { code: 0, lines: [`check-registered-patches: clean (${declared.length} declared patch(es), all recorded in patches.lock.json).`] }
    }
    return {
        code: 1,
        lines: [
            `check-registered-patches: FAIL: ${omitted.length} declared patch(es) are not recorded in patches.lock.json, so composer-patches will not apply them:`,
            ...omitted.map((p) => `  ${p.file}  (${p.pkg} ${p.version}, patches ${p.target})`),
            'Register each in the root patches.json (or extra.patches) where the package is in require-dev, then run composer patches-relock and composer patches-repatch.',
        ],
    }
}

export function main(argv = process.argv.slice(2)) {
    let root = process.cwd()
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--root' && argv[i + 1]) {
            root = resolve(argv[++i])
        } else {
            console.error(`check-registered-patches: unknown argument ${argv[i]}`)
            return 2
        }
    }
    const failed = runControls()
    if (failed.length > 0) {
        console.error(`check-registered-patches: a control failed, so the result is not trusted: ${failed.join('; ')}`)
        return 2
    }
    const { code, lines } = checkRoot(root)
    for (const line of lines) {
        ;(code === 0 ? console.log : console.error)(line)
    }
    return code
}

if (import.meta.url === pathToFileURL(process.argv[1] ?? '').href) {
    process.exitCode = main()
}
