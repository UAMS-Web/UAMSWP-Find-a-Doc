#!/usr/bin/env node
//
// check-workflow-copies.mjs: fail when a repository's copy of a shared workflow differs
// from the template the owning skill ships, outside the header (UAMS-Web/uams-claude-skills#155).
//
// The sync delivers the templates under .claude/ and .agents/ and never writes .github/,
// so each repository copies a workflow once, through its own reviewed pull request. Nothing
// then keeps the two together: a hand edit to the copy, or a template change the copy never
// received, goes unnoticed. This check is what notices.
//
// THE HEADER
//     A copy may change the comment block between the `name:` line and the `on:` line, which
//     is where a repository records its own notes. Every other line must match the template.
//     Line endings are compared as LF.
//
// WHICH COPIES
//     A copy that does not exist is skipped, because a repository adopts the workflows it
//     wants. Pass --require <file> (repeatable, the file name under .github/workflows/) to
//     fail on a missing copy of a workflow the repository has committed to carrying. A
//     template this skill's own copy cannot find (a profile that does not deliver its skill)
//     is skipped as well.
//
// EXIT CODES
//     0  every copy that exists matches its template outside the header
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

/**
 * Split a workflow into its header (the lines between `name:` and `on:`, exclusive) and
 * the rest. Returns null when either marker is missing, which the caller reports.
 */
export function splitHeader(text) {
    const lines = asLf(text).split('\n')
    const name = lines.findIndex((line) => /^name:/.test(line))
    const on = lines.findIndex((line) => /^on:/.test(line))
    if (name === -1 || on === -1 || on < name) return null
    return { head: lines.slice(0, name + 1), header: lines.slice(name + 1, on), rest: lines.slice(on) }
}

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
        want = splitHeader(readFileSync(template, 'utf8'))
        have = splitHeader(readFileSync(copy, 'utf8'))
    } catch (failure) {
        return { status: 1, line: `${file}: could not be read: ${failure.message}` }
    }
    if (want === null) return { status: 1, line: `${file}: the template ${template} has no name: and on: lines to split the header at.` }
    if (have === null) return { status: 1, line: `${copy} has no name: line followed by an on: line, so its header cannot be told from the body. Restore it from ${template}.` }

    const wantBody = [...want.head, ...want.rest]
    const haveBody = [...have.head, ...have.rest]
    let index = 0
    while (index < wantBody.length && index < haveBody.length && wantBody[index] === haveBody[index]) index += 1
    if (index === wantBody.length && index === haveBody.length) return { status: 0, line: `${file}: matches the template outside its header.` }

    // Report the line number in the copy, not in the header-stripped body.
    const offset = index < want.head.length ? index : index + have.header.length
    return {
        status: 1,
        line: `${copy} differs from the template ${template} outside its header, first near line ${offset + 1}. Change the template in UAMS-Web/uams-claude-skills, under shared/, and copy it here; do not edit the copy outside its header.`,
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
