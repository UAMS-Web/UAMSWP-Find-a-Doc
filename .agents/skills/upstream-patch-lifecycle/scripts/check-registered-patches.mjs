#!/usr/bin/env node
// check-registered-patches.mjs: fail when a locked `uams-web/patches-*` package declares a patch
// that the root registration omits (UAMS-Web/uams-claude-skills#768).
//
// WHY. `cweagans/composer-patches` v2 reads a dependency's own declared patches only from the
// lock's `packages`, never `packages-dev`. The `uams-web/patches-*` packages sit in `require-dev`
// in a consumer, so the root registration is the only thing that applies them. When a release of
// a patches package declares a new patch and the consumer moves its pin, the new patch is silently
// unapplied unless the root list gains it in the same change (measured on
// UAMS-Web/aether-redirects#134).
//
// WHAT IT READS.
//   composer.lock    every package named `uams-web/patches-*`, from `packages` and `packages-dev`,
//                    and its `extra.patches` (a map of target package to patches).
//   registration     the root `patches.json` (its `patches` key), plus the root `composer.json`
//                    `extra.patches`, plus the file `extra.patches-file` names when it is set.
// A patch is matched by its file path (slashes normalized, a leading `./` dropped), because the
// description is free text and may differ between the package and the root.
//
// A repository with no `uams-web/patches-*` package passes, and so does one that registers every
// declared patch. A repository with such a package and no registration at all fails and names
// every file.
//
// CONTROLS, run before the repository is read: a fixture whose root list omits one declared patch
// must fail and name that file (and so must one omitting a patch declared under a second target
// package key, since a package may patch several packages), a fixture registering every declared patch must pass, and a
// fixture with no patches package must pass. A control misbehaving is exit 2.
//
// EXIT CODES
//     0  every declared patch is registered, or there is no patches package
//     1  a declared patch is omitted from the root registration; each file is named
//     2  the check could not answer: a control failed, or composer.lock or a registration file is
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
 * The declared patches whose file is not in the registered set.
 *
 * @param {object} lock parsed composer.lock
 * @param {Iterable<string>} registered patch file paths registered at the root
 */
export function findOmitted(lock, registered) {
    const have = new Set([...registered].map(normalize))
    return declaredPatches(lock).filter((patch) => !have.has(patch.file))
}

/** The patch file paths registered at the root, from the files the root carries. */
export function registeredFiles({ patchesJson, composerJson }) {
    const files = []
    if (patchesJson) {
        files.push(...listPatches(patchesJson.patches))
    }
    if (composerJson?.extra?.patches) {
        files.push(...listPatches(composerJson.extra.patches))
    }
    return files.map((patch) => patch.file)
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

/** Run the three controls; returns the failures. */
export function runControls() {
    const failed = []
    const first = 'vendor/uams-web/patches-example/patches/first.patch'
    const second = 'vendor/uams-web/patches-example/patches/second.patch'
    const omitted = findOmitted(FIXTURE_LOCK, [first])
    if (omitted.length !== 1 || omitted[0].file !== second) {
        failed.push('a root list omitting one declared patch did not report exactly that file')
    }
    // A package declares patches under more than one target key; an omission under the second key must be seen.
    const twoTargets = { packages: [{ name: 'uams-web/patches-two', version: 'v1', extra: { patches: { 'a/one': { x: 'p/one.patch' }, 'b/two': { y: 'p/two.patch' } } } }] }
    const second_key = findOmitted(twoTargets, ['p/one.patch'])
    if (second_key.length !== 1 || second_key[0].file !== 'p/two.patch' || second_key[0].target !== 'b/two') {
        failed.push('a root list omitting a patch declared under a second target package did not report that file')
    }
    if (findOmitted(FIXTURE_LOCK, [first, `./${second}`]).length !== 0) {
        failed.push('a root list registering every declared patch was reported')
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
    let composerJson
    let patchesJson
    try {
        lock = readJson(lockPath, 'composer.lock')
        composerJson = existsSync(join(root, 'composer.json')) ? readJson(join(root, 'composer.json'), 'composer.json') : undefined
        const named = composerJson?.extra?.['patches-file']
        const patchesPath = join(root, typeof named === 'string' && named !== '' ? named : 'patches.json')
        patchesJson = existsSync(patchesPath) ? readJson(patchesPath, 'patches file') : undefined
    } catch (error) {
        return { code: 2, lines: [`check-registered-patches: ${error.message}`] }
    }

    const declared = declaredPatches(lock)
    if (declared.length === 0) {
        return { code: 0, lines: ['check-registered-patches: no locked uams-web/patches-* package declares a patch: nothing to check.'] }
    }
    const omitted = findOmitted(lock, registeredFiles({ patchesJson, composerJson }))
    if (omitted.length === 0) {
        return { code: 0, lines: [`check-registered-patches: clean (${declared.length} declared patch(es), all registered at the root).`] }
    }
    return {
        code: 1,
        lines: [
            `check-registered-patches: FAIL: ${omitted.length} declared patch(es) are not registered at the root, so composer-patches will not apply them:`,
            ...omitted.map((p) => `  ${p.file}  (${p.pkg} ${p.version}, patches ${p.target})`),
            'Add each to the root patches.json (or extra.patches) under its target package, then run composer update to refresh patches.lock.json.',
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
