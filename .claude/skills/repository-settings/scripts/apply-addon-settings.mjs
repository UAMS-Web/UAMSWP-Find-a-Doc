#!/usr/bin/env node
// apply-addon-settings.mjs: bring ONE new Statamic add-on repository to the settings standard,
// and read every change back (UAMS-Web/uams-claude-skills#460).
//
// A person runs it from the root of the new repository's checkout, after creating the repository
// from the add-on template. It covers the steps the template README lists as living outside the
// repository:
//   1. the merge, security and feature settings the `repository-settings` baseline fixes
//      (squash only, PR_TITLE and PR_BODY, auto-merge off, update-branch on, and the rest);
//   2. the organization default-branch rules (deletion, force push, pull request required): set
//      through the `ruleset_family` property when the organization defines it, otherwise named for
//      the owner, because adding a repository id to an organization ruleset needs `admin:org`;
//   3. the repository ruleset "main: required checks", built from the repository's OWN workflow
//      job names plus `Attribution footer` and `Closing keywords`.
// It then prints what stays with a human: the manifest entry and the sync dispatch, the wiki's
// first page, tags and Packagist.
//
// A settings change needs the owner's yes at run time, so the default run only READS and prints the
// plan. `--apply` asks "Apply N changes? [y/N]" before the first write; `--yes` answers for a
// scripted run the owner has already approved. Every write goes through writeGuard(), an allow-list
// of four calls on the one named repository, so nothing else can be written from here.
//
// Usage, from the repository root of the new add-on's checkout:
//     node .claude/skills/repository-settings/scripts/apply-addon-settings.mjs <owner>/<repo> [--apply] [--yes] [--workflows <dir>]
//
//   --workflows <dir>  the workflow directory the job names are read from (default .github/workflows)
//
// The job names come from `scripts/check-required-checks.mjs` in the checkout (the add-on
// template's own check, which needs its `yaml` dependency installed), so the ruleset requires
// exactly the names that check compares.
//
// EXIT CODES
//     0  nothing to change, a plan printed, or everything applied and read back clean
//     1  applied, but the read-back still shows a departure
//     2  the instrument is broken: a control failed, an argument is wrong, a read or write failed,
//        or the run was not confirmed

import { spawnSync } from 'node:child_process'
import { existsSync } from 'node:fs'
import { createInterface } from 'node:readline'
import { join, resolve } from 'node:path'
import { fileURLToPath, pathToFileURL } from 'node:url'

import {
    BASELINE,
    FAMILIES,
    OPEN,
    RULESET_PROPERTY,
    departures,
    ghApi,
    matchingSnapshot,
    readSnapshot,
    visibilityOf,
} from './check-repository-settings.mjs'

/** The repository ruleset this command owns. */
export const RULESET_NAME = 'main: required checks'

/** The two pull-request-body workflows every repository requires, whatever its own jobs are. */
export const BODY_CHECKS = ['Attribution footer', 'Closing keywords']

/** The value a new add-on takes: the add-on organization rulesets, with its own required checks in a repository ruleset. */
export const NEW_ADDON_FAMILY = 'addon'

/** Baseline rows a PATCH of the repository can set. */
export const SETTINGS_ROWS = [
    'allow_squash_merge', 'allow_merge_commit', 'allow_rebase_merge', 'squash_merge_commit_title', 'squash_merge_commit_message',
    'allow_auto_merge', 'allow_update_branch', 'web_commit_signoff_required', 'has_issues', 'has_projects', 'has_discussions',
]

/** Rows the rulesets steps own: they are judged by the read-back of those steps, not by the settings pass. */
export const RULESET_ROWS = ['rule_deletion', 'rule_non_fast_forward', 'rule_pull_request', 'rule_required_status_checks', 'ruleset_family']

const DEFAULT_RULES = ['deletion', 'non_fast_forward', 'pull_request']
const ORG_SCHEMA_READ = /^orgs\/[\w.-]+\/properties\/schema$/

/** Read-only `gh api`: the checker's allow-list, plus the organization's property schema. */
export function ghRead(args) {
    if (args.length === 1 && ORG_SCHEMA_READ.test(args[0])) {
        const result = spawnSync('gh', ['api', args[0]], { encoding: 'utf8' })
        return { status: result.status ?? 2, stdout: result.stdout ?? '', stderr: result.stderr ?? '' }
    }
    return ghApi(args)
}

/**
 * The only writes this command can make, all on one repository: PATCH of the repository, PATCH of its
 * property values, POST of a ruleset and PUT of a ruleset by id. Returns the call, or throws.
 */
export function writeGuard(target, method, path) {
    const base = `repos/${target}`
    const allowed = [
        ['PATCH', (p) => p === base],
        ['PATCH', (p) => p === `${base}/properties/values`],
        ['POST', (p) => p === `${base}/rulesets`],
        ['PUT', (p) => new RegExp(`^${base.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}/rulesets/\\d+$`).test(p)],
    ]
    if (!/^[\w.-]+\/[\w.-]+$/.test(target) || !allowed.some(([m, test]) => m === method && test(path))) {
        throw new Error(`refusing a write that is not one of the four this command makes: ${method} ${path}`)
    }
    return { method, path }
}

/** A writer bound to one repository: sends the JSON body on stdin so no shell sees it. */
export function makeWriter(target) {
    return (method, path, body) => {
        writeGuard(target, method, path)
        const result = spawnSync('gh', ['api', '-X', method, path, '--input', '-'], { encoding: 'utf8', input: JSON.stringify(body) })
        return { status: result.status ?? 2, stdout: result.stdout ?? '', stderr: result.stderr ?? '' }
    }
}

const sorted = (list) => [...new Set(list)].sort()
const same = (a, b) => JSON.stringify(sorted(a)) === JSON.stringify(sorted(b))

/** The required-check contexts a repository ruleset's rules list. */
export function rulesetContexts(ruleset) {
    return (ruleset?.rules ?? []).filter((rule) => rule?.type === 'required_status_checks').flatMap((rule) => (rule.parameters?.required_status_checks ?? []).map((check) => check?.context))
}

/** The repository ruleset body for a set of job names. */
export function rulesetBody(jobNames) {
    return {
        name: RULESET_NAME,
        target: 'branch',
        enforcement: 'active',
        conditions: { ref_name: { include: ['~DEFAULT_BRANCH'], exclude: [] } },
        rules: [{ type: 'required_status_checks', parameters: { strict_required_status_checks_policy: false, do_not_enforce_on_create: false, required_status_checks: sorted([...jobNames, ...BODY_CHECKS]).map((context) => ({ context })) } }],
    }
}

/** Whether the organization-sourced rules in effect include all three default-branch rule types. */
export function hasOrganizationDefaultRules(snapshot) {
    const types = (snapshot.rules ?? []).filter((rule) => rule?.ruleset_source_type === 'Organization').map((rule) => rule.type)
    return DEFAULT_RULES.every((type) => types.includes(type))
}

/**
 * What to change and what is left for a human, from what was read. `inputs`:
 *   snapshot       the checker's snapshot of the repository
 *   fullName       owner/repo
 *   jobNames       the workflow job names the repository reports on a pull request
 *   rulesets       the repository's rulesets as listed (`repos/{o}/{r}/rulesets`), each with `rules` read in
 *   schemaDefined  true or false when the organization property schema was read, undefined when it could not be
 * Returns { changes: [{ id, say, method, path, body }], human: [string] }.
 */
export function buildPlan({ snapshot, fullName, jobNames, rulesets, schemaDefined }) {
    const changes = []
    const human = []
    const visibility = visibilityOf(snapshot)

    // 1. the settings the baseline fixes and a PATCH can set
    const fields = {}
    for (const item of departures(snapshot)) {
        if (RULESET_ROWS.includes(item.id)) continue
        const row = BASELINE.find((candidate) => candidate.id === item.id)
        if (SETTINGS_ROWS.includes(item.id) && row !== undefined) fields[item.id] = row.expect[visibility]
        else human.push(`${item.id}: expected ${item.expected}, found ${item.found} (this command does not set it)`)
    }
    if (Object.keys(fields).length > 0) {
        changes.push({ id: 'settings', say: `set ${Object.entries(fields).map(([key, value]) => `${key}=${value}`).join(', ')}`, method: 'PATCH', path: `repos/${fullName}`, body: fields })
    }

    // 2. the organization default-branch rules
    if (!hasOrganizationDefaultRules(snapshot)) {
        const current = (snapshot.properties ?? []).find((item) => item?.property_name === RULESET_PROPERTY)?.value
        if (current !== undefined && current !== null && current in FAMILIES && FAMILIES[current].rules.length > 0) {
            human.push(`the ${RULESET_PROPERTY} property already reads ${current} but the organization default-branch rules do not reach this repository: the organization rulesets have not been switched to the property yet (owner)`)
        } else if (schemaDefined === true) {
            changes.push({ id: 'property', say: `set the ${RULESET_PROPERTY} property to ${NEW_ADDON_FAMILY}`, method: 'PATCH', path: `repos/${fullName}/properties/values`, body: { properties: [{ property_name: RULESET_PROPERTY, value: NEW_ADDON_FAMILY }] } })
        } else {
            human.push(`add repository id ${snapshot.repo?.id ?? '<id>'} to organization rulesets 24525022 and 24525074 (needs admin:org), or define the ${RULESET_PROPERTY} property so this command can set it${schemaDefined === undefined ? ' (the property schema could not be read with this credential)' : ''}`)
        }
    }

    // 3. the repository ruleset built from the repository's own job names
    const wanted = sorted([...jobNames, ...BODY_CHECKS])
    const own = (rulesets ?? []).find((ruleset) => ruleset?.name === RULESET_NAME && ruleset?.source_type === 'Repository')
    if (own === undefined) {
        changes.push({ id: 'ruleset', say: `create the repository ruleset "${RULESET_NAME}" requiring ${wanted.length} checks`, method: 'POST', path: `repos/${fullName}/rulesets`, body: rulesetBody(jobNames) })
    } else if (!same(rulesetContexts(own), wanted)) {
        const missing = wanted.filter((name) => !rulesetContexts(own).includes(name))
        const extra = rulesetContexts(own).filter((name) => !wanted.includes(name))
        changes.push({ id: 'ruleset', say: `update the repository ruleset "${RULESET_NAME}" (add: ${missing.join(', ') || 'none'}; remove: ${extra.join(', ') || 'none'})`, method: 'PUT', path: `repos/${fullName}/rulesets/${own.id}`, body: rulesetBody(jobNames) })
    }

    // what stays with a person whatever the run does
    human.push('add the repository to shared/manifest.json in UAMS-Web/uams-claude-skills (public: true, the profiles of the other add-ons), review the files it would receive, then approve the sync dispatch')
    human.push('create the wiki\'s first page, and tag, release and register the package on Packagist')
    return { changes, human }
}

/** The check `check-required-checks.mjs --strict` makes: required names against reported names. */
export function strictDrift(requiredContexts, jobNames) {
    const reported = sorted([...jobNames, ...BODY_CHECKS])
    const required = sorted(requiredContexts)
    return { missing: required.filter((name) => !reported.includes(name)), notRequired: reported.filter((name) => !required.includes(name)) }
}

/** Known inputs the plan must handle. Returns the names of any it got wrong. */
export function controlFailures() {
    const failed = []
    const snapshot = matchingSnapshot('public')
    snapshot.repo.id = 1
    const jobs = ['CI', 'Pint']
    const own = { id: 7, name: RULESET_NAME, source_type: 'Repository', rules: rulesetBody(jobs).rules }
    const base = { snapshot, fullName: 'UAMS-Web/control', jobNames: jobs, rulesets: [own], schemaDefined: true }
    if (buildPlan(base).changes.length !== 0) failed.push('a repository that matches the standard produced a change')
    const planted = structuredClone(snapshot)
    planted.repo.allow_merge_commit = true
    const settings = buildPlan({ ...base, snapshot: planted }).changes
    if (settings.length !== 1 || settings[0].id !== 'settings' || settings[0].body.allow_merge_commit !== false) failed.push('a planted merge-commit departure was not the one settings change')
    if (!buildPlan({ ...base, rulesets: [] }).changes.some((change) => change.id === 'ruleset' && change.method === 'POST')) failed.push('a missing repository ruleset was not created')
    const drifted = { ...own, rules: rulesetBody(['CI']).rules }
    if (!buildPlan({ ...base, rulesets: [drifted] }).changes.some((change) => change.id === 'ruleset' && change.method === 'PUT')) failed.push('a ruleset missing a job name was not updated')
    const bare = structuredClone(snapshot)
    bare.rules = bare.rules.filter((rule) => rule.type === 'required_status_checks')
    bare.properties = []
    if (!buildPlan({ ...base, snapshot: bare }).changes.some((change) => change.id === 'property')) failed.push('missing organization rules with a defined property did not set the property')
    if (!buildPlan({ ...base, snapshot: bare, schemaDefined: false }).human.some((line) => line.includes('admin:org'))) failed.push('missing organization rules with no property was not left to the owner')
    const guarded = [['PATCH', 'repos/UAMS-Web/other'], ['DELETE', 'repos/UAMS-Web/control'], ['PUT', 'repos/UAMS-Web/control/rulesets/abc'], ['POST', 'orgs/UAMS-Web/rulesets']]
    for (const [method, path] of guarded) {
        let refused = false
        try { writeGuard('UAMS-Web/control', method, path) } catch { refused = true }
        if (!refused) failed.push(`the write guard allowed ${method} ${path}`)
    }
    try { writeGuard('UAMS-Web/control', 'PATCH', 'repos/UAMS-Web/control') } catch { failed.push('the write guard refused the repository PATCH') }
    return failed
}

function parseArgs(argv) {
    const options = { repo: undefined, apply: false, yes: false, workflows: '.github/workflows' }
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--apply') options.apply = true
        else if (argv[i] === '--yes') options.yes = true
        else if (argv[i] === '--workflows') options.workflows = argv[++i]
        else if (/^[\w.-]+\/[\w.-]+$/.test(argv[i]) && options.repo === undefined) options.repo = argv[i]
        else return { error: `unexpected argument ${argv[i]}` }
    }
    if (options.repo === undefined) return { error: 'name the repository as <owner>/<repo>' }
    if (!options.workflows) return { error: '--workflows needs a directory' }
    if (options.yes && !options.apply) return { error: '--yes only answers the prompt of --apply' }
    return options
}

/** Job names from the checkout's own `scripts/check-required-checks.mjs`. */
export async function loadJobNames(directory, cwd = process.cwd()) {
    const script = join(cwd, 'scripts', 'check-required-checks.mjs')
    if (!existsSync(script)) throw new Error(`${script} is missing: run this from the root of a repository generated from the add-on template`)
    const module = await import(pathToFileURL(script).href)
    return [...module.collectJobNames(resolve(cwd, directory)).keys()]
}

/** Ask on the terminal. Refuses when there is no terminal, so a pipe cannot answer for the owner. */
export function askOnTerminal(question) {
    if (!process.stdin.isTTY) return Promise.resolve(undefined)
    const rl = createInterface({ input: process.stdin, output: process.stdout })
    return new Promise((done) => rl.question(question, (answer) => { rl.close(); done(/^y(es)?$/i.test(answer.trim())) }))
}

/** Everything the plan reads for one repository. Returns { inputs } or { error }. */
function readInputs(gh, fullName, jobNames) {
    const read = readSnapshot(gh, fullName)
    if (read.error !== undefined) return { error: read.error }
    const json = (path) => {
        const result = gh([path])
        if (result.status !== 0) throw new Error(`GET ${path}: ${result.stderr.trim().split('\n')[0] || `exit ${result.status}`}`)
        return JSON.parse(result.stdout)
    }
    try {
        const listed = json(`repos/${fullName}/rulesets?per_page=100`)
        if (!Array.isArray(listed)) throw new Error(`GET repos/${fullName}/rulesets: expected a list`)
        const rulesets = listed.map((item) => (item.source_type === 'Repository' ? { ...item, ...json(`repos/${fullName}/rulesets/${item.id}`) } : item))
        const schema = gh(['orgs/UAMS-Web/properties/schema'])
        let schemaDefined
        if (schema.status === 0) {
            const parsed = JSON.parse(schema.stdout)
            schemaDefined = Array.isArray(parsed) ? parsed.some((property) => property?.property_name === RULESET_PROPERTY) : undefined
        }
        return { inputs: { snapshot: read.snapshot, fullName, jobNames, rulesets, schemaDefined } }
    } catch (error) {
        return { error: error.message }
    }
}

/** Run the command. `gh`, `write`, `ask`, `jobNames` and `out` are injectable so the tests never reach the network. */
export async function main(argv, { gh = ghRead, write, ask = askOnTerminal, jobNames = (directory) => loadJobNames(directory), out = (line) => console.log(line) } = {}) {
    const options = parseArgs(argv)
    if (options.error !== undefined) {
        out(`apply-addon-settings: ${options.error}`)
        return 2
    }
    const broken = controlFailures()
    if (broken.length > 0) {
        out(`apply-addon-settings: BROKEN INSTRUMENT, a control failed: ${broken.join('; ')}`)
        return 2
    }
    let names
    try {
        names = await jobNames(options.workflows)
    } catch (error) {
        out(`apply-addon-settings: could not read the job names: ${error.message}`)
        return 2
    }
    out(`apply-addon-settings: ${options.repo}, ${names.length} workflow jobs read, ${SETTINGS_ROWS.length} settings rows`)
    const first = readInputs(gh, options.repo, names)
    if (first.error !== undefined) {
        out(`apply-addon-settings: could not read ${options.repo}: ${first.error}`)
        return 2
    }
    const { changes, human } = buildPlan(first.inputs)
    if (changes.length === 0) out('apply-addon-settings: nothing to change')
    for (const change of changes) out(`  will ${change.say}`)
    if (!options.apply) {
        for (const line of human) out(`  left for a person: ${line}`)
        if (changes.length > 0) out('apply-addon-settings: plan only; pass --apply to make these changes (it asks first)')
        return 0
    }
    if (changes.length > 0) {
        const answer = options.yes ? true : await ask(`Apply ${changes.length} change(s) to ${options.repo}? [y/N] `)
        if (answer === undefined) {
            out('apply-addon-settings: no terminal to ask on; the owner\'s yes is needed, or pass --yes after giving it')
            return 2
        }
        if (answer !== true) {
            out('apply-addon-settings: not confirmed, nothing written')
            return 2
        }
        const send = write ?? makeWriter(options.repo)
        for (const change of changes) {
            const result = send(change.method, change.path, change.body)
            if (result.status !== 0) {
                out(`apply-addon-settings: ${change.method} ${change.path} failed: ${(result.stderr || '').trim().split('\n')[0] || `exit ${result.status}`}`)
                return 2
            }
            out(`  done: ${change.say}`)
        }
    }

    // read every change back from the API, not from what was sent
    const second = readInputs(gh, options.repo, names)
    if (second.error !== undefined) {
        out(`apply-addon-settings: could not read ${options.repo} back: ${second.error}`)
        return 2
    }
    let bad = 0
    for (const item of departures(second.inputs.snapshot)) {
        out(`  read back: ${item.id}: expected ${item.expected}, found ${item.found}`)
        bad++
    }
    const own = second.inputs.rulesets.find((ruleset) => ruleset?.name === RULESET_NAME && ruleset?.source_type === 'Repository')
    if (own === undefined || !same(rulesetContexts(own), [...names, ...BODY_CHECKS])) {
        out(`  read back: the repository ruleset "${RULESET_NAME}" does not require exactly the workflow job names and ${BODY_CHECKS.join(' and ')}`)
        bad++
    }
    if (!hasOrganizationDefaultRules(second.inputs.snapshot)) {
        out('  read back: the organization default-branch rules (deletion, force push, pull request required) do not reach this repository yet')
        bad++
    }
    const required = second.inputs.snapshot.rules.filter((rule) => rule?.type === 'required_status_checks').flatMap((rule) => (rule.parameters?.required_status_checks ?? []).map((check) => check?.context))
    const drift = strictDrift(required, names)
    for (const name of drift.missing) out(`  read back: "${name}" is required and no job reports it`)
    for (const name of drift.notRequired) out(`  read back: "${name}" is reported by a job and not required`)
    bad += drift.missing.length + drift.notRequired.length
    for (const line of buildPlan(second.inputs).human) out(`  left for a person: ${line}`)
    out(bad === 0 ? 'apply-addon-settings: read back clean' : `apply-addon-settings: ${bad} departures remain after the read-back`)
    return bad === 0 ? 0 : 1
}

if (process.argv[1] !== undefined && fileURLToPath(import.meta.url) === process.argv[1]) process.exitCode = await main(process.argv.slice(2))
