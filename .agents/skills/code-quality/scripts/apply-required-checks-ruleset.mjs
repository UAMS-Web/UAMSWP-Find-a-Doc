#!/usr/bin/env node
// apply-required-checks-ruleset.mjs: apply the shared required-checks ruleset to ONE
// repository and read the effective rules back (UAMS-Web/uams-claude-skills#291).
//
// The ruleset template (templates/required-checks-ruleset.json) requires exactly three status
// check contexts: `CI`, the aggregate job of the ci.yml template, and the two pull-request-body
// checks, `Attribution footer` and `Closing keywords`. The names are the same in every
// repository, so the template carries no per-repository list to drift. It holds only the
// required_status_checks rule: rulesets layer, so whatever else reaches the default branch
// (a pull request requirement, a deletion block) is left to the rulesets that already carry it.
//
// Applying a ruleset is a settings change, so the default run only READS: it prints the
// effective rules and the call it would make. `--apply` makes the call, then reads
// `rules/branches/<branch>` back and checks that the three contexts are required. Every call
// is REST, so an exhausted GraphQL quota does not block it.
//
// Usage, from the repository root of a checkout that holds the skill:
//     node .claude/skills/code-quality/scripts/apply-required-checks-ruleset.mjs <owner>/<repo> [--apply] [--branch <name>]
//
// Repository administration is enough for the repository form this script applies. The
// organization form (a ruleset that lists repositories by id) needs the `admin:org` scope, which
// an ordinary token lacks; without it `GET orgs/{org}/rulesets` answers 404 with a message naming
// the scope, which reads like a missing ruleset. The operator adds the scope:
//     gh auth refresh -h github.com -s admin:org
//
// EXIT CODES
//     0  plan printed, or applied and read back with all three contexts required
//     1  applied, but the read-back does not require all three contexts
//     2  the instrument is broken: a control failed, the template is invalid, or a call failed

import { spawnSync } from 'node:child_process'
import { readFileSync } from 'node:fs'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

/** The contexts the template must require, and nothing else. */
export const REQUIRED_CONTEXTS = ['CI', 'Attribution footer', 'Closing keywords']

export const TEMPLATE_PATH = join(dirname(fileURLToPath(import.meta.url)), '..', 'templates', 'required-checks-ruleset.json')

/** The contexts named by every required_status_checks rule in a ruleset or in a list of effective rules. */
export function requiredContexts(rules) {
    const contexts = []
    for (const rule of Array.isArray(rules) ? rules : []) {
        if (rule?.type !== 'required_status_checks') continue
        for (const check of rule.parameters?.required_status_checks ?? []) contexts.push(check?.context)
    }
    return contexts
}

/** Findings for the ruleset template: it must require exactly REQUIRED_CONTEXTS, once each, and nothing more. */
export function rulesetFindings(ruleset) {
    const findings = []
    if (ruleset?.target !== 'branch') findings.push('target is not "branch"')
    if (ruleset?.enforcement !== 'active') findings.push('enforcement is not "active"')
    if (!(ruleset?.conditions?.ref_name?.include ?? []).includes('~DEFAULT_BRANCH')) findings.push('conditions.ref_name.include lacks ~DEFAULT_BRANCH')
    const rules = Array.isArray(ruleset?.rules) ? ruleset.rules : []
    const checkRules = rules.filter((rule) => rule?.type === 'required_status_checks')
    if (checkRules.length !== 1) findings.push(`holds ${checkRules.length} required_status_checks rules, not 1`)
    for (const rule of rules) if (rule?.type !== 'required_status_checks') findings.push(`carries a ${rule?.type} rule, which other rulesets own (the template holds only the required checks)`)
    const contexts = requiredContexts(rules)
    for (const context of REQUIRED_CONTEXTS) if (!contexts.includes(context)) findings.push(`does not require "${context}"`)
    for (const context of contexts) if (!REQUIRED_CONTEXTS.includes(context)) findings.push(`requires "${context}", which is not one of ${REQUIRED_CONTEXTS.join(', ')}`)
    if (new Set(contexts).size !== contexts.length) findings.push('lists a context twice')
    return findings
}

/** Known-bad inputs the checks above must flag, and a known-good one they must not. Returns the names of any that behaved wrongly. */
export function controlFailures() {
    const failed = []
    const good = {
        target: 'branch',
        enforcement: 'active',
        conditions: { ref_name: { include: ['~DEFAULT_BRANCH'] } },
        rules: [{ type: 'required_status_checks', parameters: { required_status_checks: REQUIRED_CONTEXTS.map((context) => ({ context })) } }],
    }
    const withContexts = (list) => ({ ...good, rules: [{ type: 'required_status_checks', parameters: { required_status_checks: list.map((context) => ({ context })) } }] })
    if (rulesetFindings(good).length > 0) failed.push('a ruleset requiring the three contexts was flagged')
    if (rulesetFindings(withContexts(['CI', 'Attribution footer'])).length === 0) failed.push('a ruleset missing a context was accepted')
    if (rulesetFindings(withContexts([...REQUIRED_CONTEXTS, 'Pint'])).length === 0) failed.push('a ruleset with a per-job context was accepted')
    if (rulesetFindings(withContexts([...REQUIRED_CONTEXTS, 'CI'])).length === 0) failed.push('a ruleset listing a context twice was accepted')
    if (rulesetFindings({ ...good, rules: [] }).length === 0) failed.push('a ruleset with no rules was accepted')
    if (rulesetFindings({ ...good, enforcement: 'disabled' }).length === 0) failed.push('a disabled ruleset was accepted')
    if (rulesetFindings({ ...good, rules: [...good.rules, { type: 'pull_request' }] }).length === 0) failed.push('a ruleset carrying another rule was accepted')
    if (requiredContexts([{ type: 'deletion' }]).length !== 0) failed.push('a rule that is not required_status_checks yielded contexts')
    if (requiredContexts(good.rules).length !== REQUIRED_CONTEXTS.length) failed.push('requiredContexts missed the contexts of a required_status_checks rule')
    return failed
}

/** `gh api` over REST. Returns { status, stdout, stderr }; `input` is sent as the request body. */
export function ghApi(args, input) {
    const result = spawnSync('gh', ['api', ...args], { encoding: 'utf8', input })
    return { status: result.status ?? 2, stdout: result.stdout ?? '', stderr: result.stderr ?? '' }
}

function parseArgs(argv) {
    const options = { apply: false, branch: 'main', repo: undefined }
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--apply') options.apply = true
        else if (argv[i] === '--branch') options.branch = argv[++i]
        else if (options.repo === undefined) options.repo = argv[i]
        else return { error: `unexpected argument ${argv[i]}` }
    }
    if (!/^[\w.-]+\/[\w.-]+$/.test(options.repo ?? '')) return { error: 'name the repository as <owner>/<repo>' }
    if (!options.branch) return { error: '--branch needs a name' }
    return options
}

/** Read the effective rules of a branch. Returns { rules } or { error }. */
function readRules(gh, repo, branch) {
    const result = gh([`repos/${repo}/rules/branches/${branch}`])
    if (result.status !== 0) return { error: `could not read rules/branches/${branch} for ${repo}: ${result.stderr.trim() || `exit ${result.status}`}` }
    try {
        const rules = JSON.parse(result.stdout)
        if (!Array.isArray(rules)) return { error: 'rules/branches did not return a list' }
        return { rules }
    } catch (error) {
        return { error: `rules/branches did not return JSON: ${error.message}` }
    }
}

const describe = (rules) => (rules.length === 0 ? '(no rules reach this branch)' : [...new Set(rules.map((rule) => rule.type))].join(', '))

/** Run the script. `gh` and `readTemplate` are injectable so the tests never reach the network. */
export function main(argv, { gh = ghApi, readTemplate = () => readFileSync(TEMPLATE_PATH, 'utf8'), out = (line) => console.log(line) } = {}) {
    const options = parseArgs(argv)
    if (options.error !== undefined) {
        out(`apply-required-checks-ruleset: ${options.error}`)
        return 2
    }
    const broken = controlFailures()
    if (broken.length > 0) {
        out(`apply-required-checks-ruleset: BROKEN INSTRUMENT, a control failed: ${broken.join('; ')}`)
        return 2
    }
    let template
    try {
        template = JSON.parse(readTemplate())
    } catch (error) {
        out(`apply-required-checks-ruleset: the template is not JSON: ${error.message}`)
        return 2
    }
    const invalid = rulesetFindings(template)
    if (invalid.length > 0) {
        out(`apply-required-checks-ruleset: the template is invalid: ${invalid.join('; ')}`)
        return 2
    }

    const before = readRules(gh, options.repo, options.branch)
    if (before.error !== undefined) {
        out(`apply-required-checks-ruleset: ${before.error}`)
        return 2
    }
    const present = requiredContexts(before.rules)
    out(`${options.repo} ${options.branch}: effective rules before: ${describe(before.rules)}`)
    out(`${options.repo} ${options.branch}: required contexts before: ${present.length === 0 ? '(none)' : present.join(', ')}`)

    if (!options.apply) {
        out(`plan: POST repos/${options.repo}/rulesets with "${template.name}" requiring ${REQUIRED_CONTEXTS.join(', ')}`)
        out('nothing was changed; add --apply to make the call')
        return 0
    }

    const created = gh(['-X', 'POST', `repos/${options.repo}/rulesets`, '--input', '-'], JSON.stringify(template))
    if (created.status !== 0) {
        out(`apply-required-checks-ruleset: the POST failed: ${created.stderr.trim() || `exit ${created.status}`}`)
        return 2
    }

    const after = readRules(gh, options.repo, options.branch)
    if (after.error !== undefined) {
        out(`apply-required-checks-ruleset: the ruleset was posted but the read-back failed, so it is unverified: ${after.error}`)
        return 2
    }
    const contexts = requiredContexts(after.rules)
    const missing = REQUIRED_CONTEXTS.filter((context) => !contexts.includes(context))
    out(`${options.repo} ${options.branch}: effective rules after: ${describe(after.rules)}`)
    out(`${options.repo} ${options.branch}: required contexts after: ${contexts.join(', ') || '(none)'}`)
    if (missing.length > 0) {
        out(`FAIL: the read-back does not require ${missing.map((context) => `"${context}"`).join(', ')}`)
        return 1
    }
    const extra = contexts.filter((context) => !REQUIRED_CONTEXTS.includes(context))
    if (extra.length > 0) out(`note: other rulesets also require ${[...new Set(extra)].join(', ')}; rulesets layer, so every one of them must pass`)
    if (!after.rules.some((rule) => rule.type === 'pull_request')) out('note: no ruleset requires a pull request on this branch; a required check only gates a pull request that exists')
    out('applied: all three contexts are required')
    return 0
}

if (process.argv[1] !== undefined && fileURLToPath(import.meta.url) === process.argv[1]) process.exitCode = main(process.argv.slice(2))
