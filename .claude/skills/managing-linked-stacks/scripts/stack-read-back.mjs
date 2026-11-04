#!/usr/bin/env node
// stack-read-back.mjs: print each pull request's base and head in a linked stack, or in a
// chain about to become one, and say on one line whether every base is the one intended
// (UAMS-Web/uams-claude-skills#498).
// cspell:ignore retarget retargets
//
// A link, relink or unstack can leave a pull request on a base nobody asked for: `gh stack
// link` retargets every listed pull request, the bottom one to the default branch unless
// `--base` is passed, and a failed retarget is only a warning. Nothing reports the result, so
// it is read back from the API after every such step.
//
// Usage, from the repository root:
//     node .claude/skills/managing-linked-stacks/scripts/stack-read-back.mjs --repo <owner>/<repo> --pr <n> [--base <ref>]
//     node .claude/skills/managing-linked-stacks/scripts/stack-read-back.mjs --repo <owner>/<repo> --chain <n>,<n>,... --base <ref> [--unstacked]
//
// The first form reads the stack that holds pull request <n> and checks that the lowest
// open pull request targets the stack's base, and each open one above it targets the head
// branch of the one below. With --base, the stack's base must also be that branch.
//
// The second form reads the listed pull requests, bottom first, and checks that they are
// open and chain from --base. Run it before creating a stack (the server refuses a set
// whose bases do not chain) and after an unstack, with --unstacked, which also requires
// that none of them is still in a stack.
//
// Exit 0 when every base is as intended, 1 when one is not, 2 when the read-back could not
// run (bad arguments, `gh` missing or refused, a response that is not a stack). A pass is
// the printed "stack read-back: clean" line, not the exit status alone.
//
// Reads only, through REST (`gh api`), one call for the stack and one per pull request.
// Shipped by the shared sync from UAMS-Web/uams-claude-skills; its tests live there
// (scripts/stack-read-back.test.mjs) and are not synced.

import { execFileSync } from 'node:child_process'
import { realpathSync } from 'node:fs'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

/**
 * Check pull requests in stack order, bottom first.
 *
 * Each entry is `{number, state, merged, base, head, stack}`, where `stack` is the
 * pull request's own `stack` object from the API (or null). A merged pull request is
 * passed over: once it has landed, its base no longer matters, and the lowest pull request
 * still open targets the stack's base. A closed, unmerged one blocks every pull request
 * above it from merging, so it is a mismatch.
 *
 * @param {Array<object>} prs
 * @param {string} base the branch the lowest open pull request must target
 * @param {{unstacked?: boolean, stackNumber?: number|null}} options
 * @returns {string[]} one message per mismatch; empty when every base is as intended
 */
export function checkChain(prs, base, { unstacked = false, stackNumber = null } = {}) {
    const problems = []
    let expected = base
    let open = 0
    for (const pr of prs) {
        if (pr.merged) continue
        if (pr.state !== 'open') {
            problems.push(`#${pr.number} is ${pr.state} and not merged, which blocks every pull request above it`)
            expected = pr.head
            continue
        }
        open++
        if (pr.base !== expected) {
            problems.push(`#${pr.number} targets \`${pr.base}\`, expected \`${expected}\``)
        }
        if (unstacked && pr.stack) {
            problems.push(`#${pr.number} is still in stack #${pr.stack.number}`)
        }
        if (stackNumber !== null && pr.stack?.number !== stackNumber) {
            problems.push(`#${pr.number} reports stack ${pr.stack ? `#${pr.stack.number}` : 'none'}, expected #${stackNumber}`)
        }
        expected = pr.head
    }
    if (open === 0) problems.push('no open pull request to check')
    return problems
}

const plural = (n, word) => `${n} ${word}${n === 1 ? '' : 's'}`

/** One printed row per pull request. */
export function formatRow(pr) {
    const state = pr.merged ? 'merged' : pr.state
    const stack = pr.stack ? `stack #${pr.stack.number} position ${pr.stack.position}/${pr.stack.size}` : 'no stack'
    return `  #${pr.number}  ${state.padEnd(6)}  base ${pr.base}  head ${pr.head}  (${stack})`
}

/** `gh api <path>`, parsed. Throws with the command's own message on a refusal. */
export function ghApi(path, run = execFileSync) {
    const out = run('gh', ['api', path], { encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] })
    return JSON.parse(out)
}

function readPr(repo, number, api) {
    const pr = api(`repos/${repo}/pulls/${number}`)
    if (!pr || typeof pr.number !== 'number' || !pr.base || !pr.head) throw new Error(`repos/${repo}/pulls/${number} did not return a pull request`)
    return { number: pr.number, state: pr.state, merged: Boolean(pr.merged_at), base: pr.base.ref, head: pr.head.ref, stack: pr.stack ?? null }
}

export function parseArgs(argv) {
    const args = { repo: null, pr: null, chain: null, base: null, unstacked: false }
    for (let i = 0; i < argv.length; i++) {
        const a = argv[i]
        if (a === '--unstacked') args.unstacked = true
        else if (['--repo', '--pr', '--chain', '--base'].includes(a)) {
            const value = argv[++i]
            if (value === undefined || value.startsWith('--')) throw new Error(`${a} needs a value`)
            args[a.slice(2)] = value
        } else throw new Error(`unknown argument ${a}`)
    }
    if (!args.repo || !/^[\w.-]+\/[\w.-]+$/.test(args.repo)) throw new Error('--repo <owner>/<repo> is required')
    if ((args.pr === null) === (args.chain === null)) throw new Error('pass exactly one of --pr <n> or --chain <n>,<n>,...')
    if (args.pr !== null && !/^\d+$/.test(args.pr)) throw new Error('--pr takes a pull request number')
    if (args.chain !== null) {
        if (!/^\d+(,\d+)*$/.test(args.chain)) throw new Error('--chain takes pull request numbers separated by commas, bottom first')
        if (!args.base) throw new Error('--chain needs --base <ref>, the branch the bottom pull request targets')
    }
    if (args.unstacked && args.chain === null) throw new Error('--unstacked goes with --chain')
    return args
}

export function main(argv, { api = ghApi, log = console.log, error = console.error } = {}) {
    let args
    try {
        args = parseArgs(argv)
    } catch (e) {
        error(`stack read-back: ${e.message}. Nothing was read.`)
        return 2
    }

    let prs
    let base
    let label
    let stackNumber = null
    try {
        if (args.pr !== null) {
            const stacks = api(`repos/${args.repo}/stacks?pull_request=${args.pr}`)
            if (!Array.isArray(stacks)) throw new Error('the stacks endpoint did not return a list')
            if (stacks.length === 0) {
                log(`stack read-back: MISMATCH: #${args.pr} is in no stack in ${args.repo} (or is not a pull request there).`)
                return 1
            }
            const stack = stacks[0]
            if (!Array.isArray(stack.pull_requests) || !stack.base?.ref) throw new Error('the stack has no pull_requests list or base')
            stackNumber = stack.number
            base = stack.base.ref
            label = `stack #${stack.number} on \`${base}\``
            prs = stack.pull_requests.map((p) => readPr(args.repo, p.number, api))
            if (args.base && args.base !== base) {
                for (const pr of prs) log(formatRow(pr))
                log(`stack read-back: MISMATCH: ${label}, expected the stack on \`${args.base}\`.`)
                return 1
            }
        } else {
            base = args.base
            label = `chain on \`${base}\``
            prs = args.chain.split(',').map((n) => readPr(args.repo, Number(n), api))
        }
    } catch (e) {
        const detail = String(e.stderr || e.message).trim().split('\n')[0]
        error(`stack read-back: could not read ${args.repo}: ${detail}. Nothing was checked.`)
        return 2
    }

    for (const pr of prs) log(formatRow(pr))
    const problems = checkChain(prs, base, { unstacked: args.unstacked, stackNumber })
    const open = prs.filter((p) => !p.merged && p.state === 'open').length
    if (problems.length === 0) {
        log(`stack read-back: clean (${label}, ${plural(prs.length, 'pull request')} read, ${open} open${args.unstacked ? ', none in a stack' : ''}).`)
        return 0
    }
    for (const p of problems) log(`stack read-back: MISMATCH: ${p}.`)
    return 1
}

// import.meta.url is the real path, so argv[1] is resolved through any symlink before the
// comparison; otherwise a run through a symlinked directory would skip main and exit 0.
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
