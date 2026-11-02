#!/usr/bin/env node
// check-aggregate-needs.mjs: fail when a job in the CI workflow is not covered by the
// aggregate `CI` job, or when one of the three fixed required names is reported by no job
// (UAMS-Web/uams-claude-skills#170, re-scoped by #169).
//
// A repository's ruleset requires three names: `CI`, `Attribution footer` and `Closing keywords`.
// `CI` is the aggregate job of the ci.yml template; it `needs:` every other job in its workflow
// and fails unless all of them succeeded. The names in the ruleset therefore never change when a
// job is renamed. The drift that remains is inside one file: a job missing from the aggregate's
// `needs:` list is required by nothing, which reads as a gate and is not one. This check finds it
// from the workflow files alone, so it needs no token able to read rules.
//
// WHAT IT READS. Every workflow under .github/workflows that runs on `pull_request`. The aggregate
// is the job whose `name:` is `CI` (or, with no name, whose id is `CI`). It reports
//   - each other job in the aggregate's workflow that its `needs:` list omits,
//   - each `needs:` entry that names no job in that workflow,
//   - each fixed name that no pull_request workflow's job reports (a job's `name:`, else its id),
//   - a repository with no aggregate, or more than one.
//
// THE ALLOW-LIST. A job that is deliberately not aggregated is named in
// `.github/aggregate-needs-allow.json`, an object of job id to a non-empty reason:
//     { "nightly-audit": "scheduled only; not a pull request gate" }
// An entry with no reason, or naming a job that does not exist, is reported.
//
// THE PARSER. Dependency-free, because a target repository has no YAML package. It reads the
// shapes GitHub workflows use for `on:`, `jobs:`, `name:` and `needs:` (scalar, inline list, block
// list) and nothing else. The controls below exercise it on every run.
//
// CONTROLS, run before any workflow is read: a planted missing job must be reported, a complete
// list must pass, a block-list `needs:` must parse, and an unknown allow-list entry must be
// reported. Any control misbehaving is exit 2.
//
// NOT SHIPPED: a live comparison against `GET repos/{o}/{r}/rules/branches/main`. Whether a
// workflow's default token can read the required-checks list is not measured, and with fixed
// names in the ruleset the comparison buys little, so it is dropped rather than left unmeasured.
//
// Usage, from the repository root:
//     node .claude/skills/code-quality/scripts/check-aggregate-needs.mjs [--root <dir>]
//
// EXIT CODES
//     0  workflows scanned, controls held, nothing drifted
//     1  at least one finding; each is printed
//     2  the check did not run: a control failed, no pull_request workflow, or an unreadable file

import { existsSync, readdirSync, readFileSync } from 'node:fs'
import { join } from 'node:path'
import { fileURLToPath } from 'node:url'

export const FIXED_NAMES = ['CI', 'Attribution footer', 'Closing keywords']
export const ALLOW_LIST_PATH = '.github/aggregate-needs-allow.json'

const unquote = (value) => value.trim().replace(/^(['"])(.*)\1$/, '$2')
const stripComment = (line) => line.replace(/^\s*#.*$/, '').replace(/\s+#.*$/, '')
const indentOf = (line) => line.length - line.trimStart().length

/** Entries of a scalar or inline list (`[a, b]`); an empty value gives an empty array. */
function inlineValues(value) {
    const text = value.trim()
    if (text === '') return []
    if (text.startsWith('[')) return text.replace(/^\[|\]$/g, '').split(',').map(unquote).filter((entry) => entry !== '')
    return [unquote(text)]
}

/**
 * Read one workflow: whether it runs on pull_request, and each job's name and needs.
 * Returns { pullRequest, jobs: { id: { name, needs } } }.
 */
export function parseWorkflow(text) {
    const lines = text.replace(/\r\n/g, '\n').split('\n').map(stripComment)
    const result = { pullRequest: false, jobs: {} }
    let section = null
    let jobId = null
    let jobIndent = null
    let listKey = null
    for (const line of lines) {
        if (line.trim() === '') continue
        const indent = indentOf(line)
        const body = line.trim()
        if (indent === 0) {
            section = body.replace(/:.*$/, '')
            jobId = null
            listKey = null
            if (section === 'on' || section === 'true') {
                section = 'on'
                if (inlineValues(body.slice(body.indexOf(':') + 1)).includes('pull_request')) result.pullRequest = true
            }
            continue
        }
        if (section === 'on') {
            if (/^-?\s*pull_request\b/.test(body)) result.pullRequest = true
            continue
        }
        if (section !== 'jobs') continue
        if (jobIndent === null) jobIndent = indent
        if (indent === jobIndent) {
            const match = body.match(/^([\w-]+):\s*$/)
            jobId = match === null ? null : match[1]
            if (jobId !== null) result.jobs[jobId] = { name: undefined, needs: [] }
            listKey = null
            continue
        }
        if (jobId === null) continue
        if (indent === jobIndent + 2) {
            listKey = null
            const pair = body.match(/^([\w-]+):\s*(.*)$/)
            if (pair === null) continue
            if (pair[1] === 'name') result.jobs[jobId].name = unquote(pair[2])
            else if (pair[1] === 'needs') {
                if (pair[2].trim() === '') listKey = 'needs'
                else result.jobs[jobId].needs = inlineValues(pair[2])
            }
        } else if (listKey === 'needs' && body.startsWith('-')) {
            result.jobs[jobId].needs.push(unquote(body.slice(1)))
        }
    }
    return result
}

/** The name a job reports as a check run: its `name:`, else its id. */
const reportedName = (id, job) => job.name ?? id

/**
 * Findings for parsed workflows ({ file, pullRequest, jobs }) and an allow-list ({ jobId: reason }).
 */
export function compare(workflows, allow = {}) {
    const findings = []
    const gated = workflows.filter((workflow) => workflow.pullRequest)
    const aggregates = []
    for (const workflow of gated) {
        for (const [id, job] of Object.entries(workflow.jobs)) if (reportedName(id, job) === 'CI') aggregates.push({ workflow, id, job })
    }
    if (aggregates.length === 0) findings.push('no job reports the name `CI`, the aggregate a ruleset requires')
    if (aggregates.length > 1) findings.push(`${aggregates.length} jobs report the name \`CI\` (${aggregates.map((entry) => `${entry.workflow.file}:${entry.id}`).join(', ')}); a ruleset cannot tell them apart`)

    const everyJob = new Set(gated.flatMap((workflow) => Object.keys(workflow.jobs)))
    for (const [id, reason] of Object.entries(allow)) {
        if (typeof reason !== 'string' || reason.trim() === '') findings.push(`allow-list entry \`${id}\` has no reason`)
        if (!everyJob.has(id)) findings.push(`allow-list entry \`${id}\` names no job in a pull_request workflow`)
    }

    if (aggregates.length === 1) {
        const { workflow, id: aggregateId, job: aggregate } = aggregates[0]
        const needs = new Set(aggregate.needs)
        for (const id of Object.keys(workflow.jobs)) {
            if (id === aggregateId || needs.has(id) || allow[id] !== undefined) continue
            findings.push(`${workflow.file}: job \`${id}\` is not in the \`needs:\` list of the \`CI\` job, so nothing requires it`)
        }
        for (const entry of needs) if (workflow.jobs[entry] === undefined) findings.push(`${workflow.file}: the \`CI\` job needs \`${entry}\`, which is not a job in that workflow`)
    }

    const reported = new Set(gated.flatMap((workflow) => Object.entries(workflow.jobs).map(([id, job]) => reportedName(id, job))))
    for (const name of FIXED_NAMES) if (!reported.has(name)) findings.push(`no pull_request workflow has a job reporting the required name \`${name}\``)
    return findings
}

const CONTROL_WORKFLOW = `name: Probe
on:
  pull_request:
jobs:
  alpha:
    runs-on: ubuntu-latest
  beta:
    runs-on: ubuntu-latest
    needs:
      - alpha
  ci:
    name: CI
    needs: [alpha, beta]
    if: always()
    runs-on: ubuntu-latest
`

const CONTROL_BODY_WORKFLOWS = `name: Body
on:
  pull_request:
jobs:
  footer:
    name: Attribution footer
    runs-on: ubuntu-latest
  keywords:
    name: Closing keywords
    runs-on: ubuntu-latest
`

/** Known inputs with known answers. Returns a description of each that behaved wrongly. */
export function controlFailures() {
    const failed = []
    const parse = (text, file = 'probe.yml') => ({ file, ...parseWorkflow(text) })
    const body = parse(CONTROL_BODY_WORKFLOWS, 'body.yml')
    const clean = parse(CONTROL_WORKFLOW)
    if (!clean.pullRequest) failed.push('a pull_request workflow was not recognized')
    if (JSON.stringify(clean.jobs.beta?.needs) !== '["alpha"]') failed.push('a block-list needs: was not parsed')
    if (JSON.stringify(clean.jobs.ci?.needs) !== '["alpha","beta"]') failed.push('an inline needs: was not parsed')
    if (compare([clean, body]).length > 0) failed.push('a complete aggregate was flagged')
    const missing = parse(CONTROL_WORKFLOW.replace('needs: [alpha, beta]', 'needs: [alpha]'))
    if (!compare([missing, body]).some((finding) => finding.includes('`beta`'))) failed.push('a planted job missing from needs: was not reported')
    const renamed = parse(CONTROL_BODY_WORKFLOWS.replace('name: Closing keywords', 'name: Keywords'), 'body.yml')
    if (!compare([clean, renamed]).some((finding) => finding.includes('Closing keywords'))) failed.push('a missing fixed name was not reported')
    if (compare([missing, body], { beta: 'deliberate' }).length > 0) failed.push('an allow-listed job was still reported')
    if (!compare([clean, body], { ghost: 'x' }).some((finding) => finding.includes('ghost'))) failed.push('an unknown allow-list entry was not reported')
    if (!compare([clean, body], { alpha: '' }).some((finding) => finding.includes('no reason'))) failed.push('an allow-list entry with no reason was not reported')
    if (parse(CONTROL_WORKFLOW.replace('  pull_request:', '  push:')).pullRequest) failed.push('a push-only workflow was treated as a pull_request workflow')
    return failed
}

/** Read every workflow under `root`. Returns { workflows, allow } or { error }. */
export function readRepository(root) {
    const dir = join(root, '.github', 'workflows')
    if (!existsSync(dir)) return { error: `${dir} does not exist` }
    const workflows = readdirSync(dir)
        .filter((file) => /\.ya?ml$/.test(file))
        .sort()
        .map((file) => ({ file: `.github/workflows/${file}`, ...parseWorkflow(readFileSync(join(dir, file), 'utf8')) }))
    let allow = {}
    const allowPath = join(root, ALLOW_LIST_PATH)
    if (existsSync(allowPath)) {
        try {
            allow = JSON.parse(readFileSync(allowPath, 'utf8'))
        } catch (error) {
            return { error: `${ALLOW_LIST_PATH} is not JSON: ${error.message}` }
        }
        if (allow === null || typeof allow !== 'object' || Array.isArray(allow)) return { error: `${ALLOW_LIST_PATH} must be an object of job id to reason` }
    }
    return { workflows, allow }
}

export function main(argv, out = (line) => console.log(line)) {
    const rootIndex = argv.indexOf('--root')
    const root = rootIndex === -1 ? '.' : argv[rootIndex + 1]
    const broken = controlFailures()
    if (broken.length > 0) {
        out(`check-aggregate-needs: BROKEN INSTRUMENT, a control failed: ${broken.join('; ')}`)
        return 2
    }
    const repository = readRepository(root)
    if (repository.error !== undefined) {
        out(`check-aggregate-needs: ${repository.error}`)
        return 2
    }
    const gated = repository.workflows.filter((workflow) => workflow.pullRequest)
    out(`check-aggregate-needs: ${repository.workflows.length} workflows, ${gated.length} on pull_request, ${gated.reduce((sum, workflow) => sum + Object.keys(workflow.jobs).length, 0)} jobs`)
    if (gated.length === 0) {
        out('check-aggregate-needs: no workflow runs on pull_request, so there is nothing to compare')
        return 2
    }
    const findings = compare(repository.workflows, repository.allow)
    for (const finding of findings) out(`FAIL: ${finding}`)
    if (findings.length === 0) out('check-aggregate-needs: clean (every job is aggregated or allow-listed, and the three required names are reported)')
    return findings.length === 0 ? 0 : 1
}

if (process.argv[1] !== undefined && fileURLToPath(import.meta.url) === process.argv[1]) process.exitCode = main(process.argv.slice(2))
