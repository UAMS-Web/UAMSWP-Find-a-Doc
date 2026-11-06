#!/usr/bin/env node
// apply-repository-settings.mjs: bring ONE new add-on or plugin repository, public or private, to the
// settings standard, and read every change back (UAMS-Web/uams-claude-skills#565).
//
// This is `apply-addon-settings.mjs` with the kind of repository named, so one command serves a
// Statamic add-on and a WordPress plugin. `--profile` is required: `addon` or `plugin`. The
// profile changes only the steps printed for a person (the manifest entry, the first release). The settings it
// writes are the same for both, and follow the repository's visibility, which is read from the
// repository and never chosen: a private repository takes the default-branch rules and no
// `required_checks` value, because remote CI is off there and no job reports those checks.
// `apply-addon-settings.mjs` keeps working unchanged for existing callers.
//
// Usage, from the repository root of the new repository's checkout:
//     node .claude/skills/repository-settings/scripts/apply-repository-settings.mjs --profile addon|plugin <owner>/<repo> [--apply] [--yes]
//
// The exit codes, the owner's yes and the write guard are those of `apply-addon-settings.mjs`.

import { fileURLToPath } from 'node:url'

import { PROFILES, main } from './apply-addon-settings.mjs'

/** Split `--profile <value>` from the arguments the shared command reads. Returns { profile, rest } or { error }. */
export function splitProfile(argv) {
    const index = argv.indexOf('--profile')
    if (index === -1) return { error: `name the kind of repository: --profile ${PROFILES.join('|')}` }
    const profile = argv[index + 1]
    if (!PROFILES.includes(profile)) return { error: `unknown profile ${String(profile)}; use ${PROFILES.join(' or ')}` }
    return { profile, rest: [...argv.slice(0, index), ...argv.slice(index + 2)] }
}

/** Run the command: the shared main with the profile and this command's name. */
export async function run(argv, options = {}) {
    const split = splitProfile(argv)
    if (split.error !== undefined) {
        ;(options.out ?? ((line) => console.log(line)))(`apply-repository-settings: ${split.error}`)
        return 2
    }
    return main(split.rest, { ...options, name: 'apply-repository-settings', profile: split.profile })
}

if (process.argv[1] !== undefined && fileURLToPath(import.meta.url) === process.argv[1]) process.exitCode = await run(process.argv.slice(2))
