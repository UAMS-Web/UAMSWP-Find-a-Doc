#!/usr/bin/env node
//
// check-workflow-copies.mjs: fail when a repository's copy of a shared workflow differs
// from the template the owning skill ships (UAMS-Web/uams-claude-skills#155).
//
// The sync delivers the templates under .claude/ and .agents/ and does not create a copy
// under .github/, so each repository copies a workflow once, through its own reviewed pull
// request. The sync then rewrites an existing copy when its template changes
// (UAMS-Web/uams-claude-skills#431), but a hand edit to the copy, or a copy the sync could
// not push, goes unnoticed. This check is what notices.
//
// THE WHOLE FILE
//     The template owns every line of the copy, the comment block between the `name:` and
//     `on:` lines included, so a header correction made in a template reaches every copy
//     (UAMS-Web/uams-claude-skills#448). Line endings are compared as LF.
//
// WHICH COPIES
//     A copy that does not exist is skipped, because a repository adopts the workflows it
//     wants. Pass --require <file> (repeatable, the file name under .github/workflows/) to
//     fail on a missing copy of a workflow the repository has committed to carrying. A
//     template this skill's own copy cannot find (a profile that does not deliver its skill)
//     is skipped as well.
//
// EXIT CODES
//     0  every copy that exists matches its template
//     1  a copy differs, or a required copy is missing; the file and first line are named
//     2  the check could not run: a usage error, or no copy and no template was found at all
//
// USAGE
//     node .claude/skills/synced-files/scripts/check-workflow-copies.mjs [--root <dir>] [--require <file>]...

import { spawnSync } from 'node:child_process'
import { existsSync, readFileSync, realpathSync } from 'node:fs'
import { dirname, join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

// The skills directory this script sits in, in whichever tree runs it: shared/.claude/skills
// in the source repository, .claude/skills in a target.
export const SKILLS_DIR = join(dirname(fileURLToPath(import.meta.url)), '..', '..')

/** The workflows with a template, as [file under .github/workflows/, owning skill]. */
export const WORKFLOWS = [
    ['assign-author.yml', 'writing-pull-requests'],
    ['check-attribution-footer.yml', 'writing-pull-requests'],
    ['check-closing-keyword-across-lines.yml', 'writing-pull-requests'],
    ['clear-handed-back.yml', 'handed-back-label'],
]

const asLf = (text) => text.replace(/\r\n/g, '\n')
const LINE_BREAK = String.fromCharCode(10)

/** Where a template lives, for a workflow file and its owning skill. */
export function templatePath(file, skill, skillsDir = SKILLS_DIR) {
    return join(skillsDir, skill, 'templates', file)
}

/**
 * Compare one copy with its template. Returns `{ status, line }`: 0 matches, 1 differs
 * or is unreadable, 'skip' when there is nothing to compare.
 */
export function compareCopy({ root, file, skill, skillsDir = SKILLS_DIR, required = false }) {
    const template = templatePath(file, skill, skillsDir)
    const copy = join(root, '.github', 'workflows', file)
    if (!existsSync(template)) return { status: 'skip', line: `${file}: the template ${template} is not delivered here, skipped.` }
    if (!existsSync(copy)) {
        return required
            ? { status: 1, line: `${copy} is missing. Copy the template to it: ${template}` }
            : { status: 'skip', line: `${file}: no copy at .github/workflows/${file}, skipped.` }
    }

    let want
    let have
    try {
        want = asLf(readFileSync(template, 'utf8')).split(LINE_BREAK)
        have = asLf(readFileSync(copy, 'utf8')).split(LINE_BREAK)
    } catch (failure) {
        return { status: 1, line: `${file}: could not be read: ${failure.message}` }
    }

    let index = 0
    while (index < want.length && index < have.length && want[index] === have[index]) index += 1
    if (index === want.length && index === have.length) return { status: 0, line: `${file}: matches the template.` }

    return {
        status: 1,
        line: `${copy} differs from the template ${template}, first near line ${index + 1}. Change the template in UAMS-Web/uams-claude-skills, under shared/, and copy it here; do not edit the copy.`,
    }
}

/**
 * Check every copy. `required` lists workflow file names that must exist.
 * Returns `{ status, lines }`.
 */
export function checkCopies({ root, required = [], skillsDir = SKILLS_DIR, workflows = WORKFLOWS }) {
    const known = workflows.map(([file]) => file)
    const unknown = required.filter((file) => !known.includes(file))
    if (unknown.length > 0) return { status: 2, lines: [`unknown workflow in --require: ${unknown.join(', ')}. Known: ${known.join(', ')}.`] }

    const results = workflows.map(([file, skill]) => compareCopy({ root, file, skill, skillsDir, required: required.includes(file) }))
    if (results.every((result) => result.status === 'skip')) {
        return { status: 2, lines: ['workflow copy check cannot run: no template was found and no copy exists.', ...results.map((result) => result.line)] }
    }
    return { status: results.some((result) => result.status === 1) ? 1 : 0, lines: results.map((result) => result.line) }
}

function repoRoot(cwd) {
    const top = spawnSync('git', ['rev-parse', '--show-toplevel'], { cwd, encoding: 'utf8' })
    return top.status === 0 && top.stdout.trim() !== '' ? top.stdout.trim() : cwd
}

export function main(argv = process.argv.slice(2), { cwd = process.cwd(), log = console.log, error = console.error, skillsDir = SKILLS_DIR } = {}) {
    let root = null
    const required = []
    for (let i = 0; i < argv.length; i += 1) {
        if (argv[i] === '--root' && argv[i + 1]) root = resolve(cwd, argv[++i])
        else if (argv[i] === '--require' && argv[i + 1]) required.push(argv[++i])
        else {
            error('usage: node check-workflow-copies.mjs [--root <dir>] [--require <file>]...')
            return 2
        }
    }

    const { status, lines } = checkCopies({ root: root ?? repoRoot(cwd), required, skillsDir })
    lines.forEach((line) => (status === 0 ? log : error)(line))
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
