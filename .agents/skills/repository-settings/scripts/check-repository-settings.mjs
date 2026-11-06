#!/usr/bin/env node
// cspell:ignore Larastan
// check-repository-settings.mjs: report, for each named repository, every setting that departs
// from the baseline in this skill's SKILL.md for the repository's visibility
// (UAMS-Web/uams-claude-skills#358).
//
// It only reads. Every call is `gh api <path>` (a GET) or `gh api -i <path>` (a GET whose status
// line is read), and ghApi() passes on nothing else: it is an allow-list of a `repos/` path and
// `-i`, so no flag that makes `gh api` write reaches it. Organization settings (Actions defaults, variables,
// rulesets) need the `admin:org` scope to read, which an ordinary token lacks, so each row is read
// through the repository's own endpoints, which show the effective value.
//
// Usage, from the repository root of a checkout that holds the skill:
//     node .claude/skills/repository-settings/scripts/check-repository-settings.mjs <owner>/<repo>...
//     node .claude/skills/repository-settings/scripts/check-repository-settings.mjs --manifest shared/manifest.json
//
//   --manifest <path>  check every target a sync manifest lists (its `owner` and `targets[].name`),
//                      and report a target whose `public` flag disagrees with its visibility
//   --from <dir>       read <dir>/<repo>.json snapshots instead of the API (the shape readSnapshot() returns)
//   --open             also print each repository's value for every open-question row
//
// EXIT CODES
//     0  every repository matches the baseline for its visibility
//     1  at least one departure; each is printed on its own line
//     2  the instrument is broken: a control failed, an argument is wrong, or a read failed

import { spawnSync } from 'node:child_process'
import { readFileSync } from 'node:fs'
import { join } from 'node:path'
import { fileURLToPath } from 'node:url'

/** A row whose value the project owner has not decided: reported with --open, never as a departure. */
export const OPEN = 'open question'

/** The status checks a public repository's `main` requires (the `code-quality` skill's ruleset). */
export const REQUIRED_CONTEXTS = ['Attribution footer', 'CI', 'Closing keywords']

/** The organization custom property that names which organization rulesets a repository receives (UAMS-Web/uams-claude-skills#415). */
export const RULESET_PROPERTY = 'ruleset_family'

/** The organization custom property, a multi-select, that names which atomic required-checks rulesets a repository receives (UAMS-Web/uams-claude-skills#471). */
export const CHECKS_PROPERTY = 'required_checks'

/** The values of the `required_checks` property, each with the status check contexts its organization ruleset requires. */
export const ATOMIC_CHECKS = {
    'pr-body': ['Attribution footer', 'Closing keywords'],
    'ci-aggregate': ['CI'],
}

const DEFAULT_BRANCH_RULES = ['deletion', 'non_fast_forward', 'pull_request']

/**
 * The values of the `ruleset_family` property, each with the organization rules a repository holding it receives:
 * the rule types among deletion, non_fast_forward and pull_request, and the required status check contexts.
 */
export const FAMILIES = {
    none: { rules: [], checks: [] },
    base: { rules: DEFAULT_BRANCH_RULES, checks: [] },
}

const signature = (rules, checks) => `${[...new Set(rules)].sort().join(',')}|${[...new Set(checks)].sort().join(',')}`

/** Secrets a pull-request workflow reads, so a Dependabot pull request needs them in the Dependabot store too. */
export const PULL_REQUEST_SECRETS = ['COMPOSER_AUTH_TOKEN']

function status(code, on, off) {
    const value = Number(code)
    return value === 204 || value === 200 ? on : value === 404 ? off : `HTTP ${code}`
}

/** The status codes an existence read answers with. Anything else is a failed read, never a value. */
const ANSWERS = [200, 204, 404]
const names = (list) => (Array.isArray(list?.secrets ?? list?.variables) ? (list.secrets ?? list.variables).map((item) => item.name) : [])
const hasRule = (s, type) => ((s.rules ?? []).some((rule) => rule?.type === type) ? 'present' : 'absent')
const analysis = (s, key) => s.repo?.security_and_analysis?.[key]?.status ?? 'not reported'

function requiredChecks(s) {
    const contexts = []
    for (const rule of s.rules ?? []) {
        if (rule?.type !== 'required_status_checks') continue
        for (const check of rule.parameters?.required_status_checks ?? []) contexts.push(check?.context)
    }
    if (contexts.length === 0) return 'none'
    return [...new Set(contexts)].sort().join(', ')
}

/** The values of the `required_checks` property: an unset or null property is an empty list, and a value outside ATOMIC_CHECKS is returned as is. */
function declaredChecks(s) {
    const value = (s.properties ?? []).find((item) => item?.property_name === CHECKS_PROPERTY)?.value
    if (value === null || value === undefined) return []
    return Array.isArray(value) ? value.map(String) : [String(value)]
}

/** The contexts the organization-sourced required status check rules in effect add up to. */
function organizationContexts(s) {
    return (s.rules ?? [])
        .filter((rule) => rule?.ruleset_source_type === 'Organization' && rule.type === 'required_status_checks')
        .flatMap((rule) => (rule.parameters?.required_status_checks ?? []).map((check) => check?.context))
}

/** The family the organization-sourced rules in effect add up to, or a description when they match none. Contexts in `left` (those a declared atomic value supplies) are not counted. */
function observedFamily(s, left = []) {
    const organization = (s.rules ?? []).filter((rule) => rule?.ruleset_source_type === 'Organization')
    const types = organization.map((rule) => rule.type).filter((type) => DEFAULT_BRANCH_RULES.includes(type))
    const contexts = organization.filter((rule) => rule.type === 'required_status_checks').flatMap((rule) => (rule.parameters?.required_status_checks ?? []).map((check) => check?.context)).filter((context) => !left.includes(context))
    const observed = signature(types, contexts)
    const match = Object.entries(FAMILIES).find(([, family]) => signature(family.rules, family.checks) === observed)
    return match ? match[0] : `no family (rules: ${types.join(', ') || 'none'}; checks: ${[...new Set(contexts)].sort().join(', ') || 'none'})`
}

/** The family the repository's custom property names: an unset property is `none`. */
function declaredFamily(s) {
    const entry = (s.properties ?? []).find((item) => item?.property_name === RULESET_PROPERTY)
    const value = entry?.value ?? 'none'
    return value in FAMILIES ? value : `unknown value ${String(value)}`
}

/** `agrees` when the property value and the organization rules in effect describe the same family. */
function rulesetFamily(s) {
    const declared = declaredFamily(s)
    const observed = observedFamily(s)
    if (declared === observed) return 'agrees'
    // a repository holding atomic values also receives their contexts, which are no part of its family
    const atomic = declaredChecks(s).flatMap((value) => ATOMIC_CHECKS[value] ?? [])
    if (atomic.length > 0 && declared === observedFamily(s, atomic)) return 'agrees'
    return `property ${declared}, rules ${observed}`
}

/**
 * `agrees` when the status check contexts the organization requires are exactly those of the
 * family's checks and the declared `required_checks` values (UAMS-Web/uams-claude-skills#471).
 */
function requiredChecksProperty(s) {
    const values = declaredChecks(s)
    const unknown = values.filter((value) => !(value in ATOMIC_CHECKS))
    if (unknown.length > 0) return `unknown value ${unknown.join(', ')}`
    const family = declaredFamily(s)
    const expected = new Set([...values.flatMap((value) => ATOMIC_CHECKS[value]), ...(FAMILIES[family]?.checks ?? [])])
    const found = new Set(organizationContexts(s))
    const missing = [...expected].filter((context) => !found.has(context)).sort()
    const extra = [...found].filter((context) => !expected.has(context)).sort()
    if (missing.length === 0 && extra.length === 0) return 'agrees'
    return `property ${values.join(', ') || 'none'}${missing.length > 0 ? `, not required: ${missing.join(', ')}` : ''}${extra.length > 0 ? `, required without a value: ${extra.join(', ')}` : ''}`
}

function pullRequestSecrets(s) {
    const actions = names(s.actions_secrets)
    const dependabot = names(s.dependabot_secrets)
    const used = PULL_REQUEST_SECRETS.filter((name) => actions.includes(name))
    if (used.length === 0) return 'not used'
    const missing = used.filter((name) => !dependabot.includes(name))
    return missing.length === 0 ? 'in both stores' : `Actions only: ${missing.join(', ')}`
}

/**
 * The baseline, one entry per row of the SKILL.md table, in the same order. `expect` holds the
 * value for each visibility: a value, a list of accepted values, or OPEN.
 */
export const BASELINE = [
    { id: 'allow_squash_merge', read: (s) => s.repo.allow_squash_merge, expect: { public: true, private: true } },
    { id: 'allow_merge_commit', read: (s) => s.repo.allow_merge_commit, expect: { public: false, private: false } },
    { id: 'allow_rebase_merge', read: (s) => s.repo.allow_rebase_merge, expect: { public: false, private: false } },
    { id: 'squash_merge_commit_title', read: (s) => s.repo.squash_merge_commit_title, expect: { public: 'PR_TITLE', private: 'PR_TITLE' } },
    { id: 'squash_merge_commit_message', read: (s) => s.repo.squash_merge_commit_message, expect: { public: 'PR_BODY', private: 'PR_BODY' } },
    { id: 'allow_auto_merge', read: (s) => s.repo.allow_auto_merge, expect: { public: false, private: false } },
    { id: 'delete_branch_on_merge', read: (s) => s.repo.delete_branch_on_merge, expect: { public: OPEN, private: OPEN } },
    { id: 'allow_update_branch', read: (s) => s.repo.allow_update_branch, expect: { public: true, private: OPEN } },
    { id: 'web_commit_signoff_required', read: (s) => s.repo.web_commit_signoff_required, expect: { public: false, private: false } },
    { id: 'has_issues', read: (s) => s.repo.has_issues, expect: { public: true, private: true } },
    { id: 'has_projects', read: (s) => s.repo.has_projects, expect: { public: true, private: true } },
    { id: 'has_wiki', read: (s) => s.repo.has_wiki, expect: { public: OPEN, private: OPEN } },
    { id: 'has_discussions', read: (s) => s.repo.has_discussions, expect: { public: false, private: false } },
    { id: 'actions_enabled', read: (s) => s.actions?.enabled, expect: { public: true, private: true } },
    { id: 'allowed_actions', read: (s) => s.actions?.allowed_actions, expect: { public: 'all', private: 'all' } },
    { id: 'default_workflow_permissions', read: (s) => s.workflow?.default_workflow_permissions, expect: { public: 'write', private: 'write' } },
    { id: 'can_approve_pull_request_reviews', read: (s) => s.workflow?.can_approve_pull_request_reviews, expect: { public: true, private: true } },
    { id: 'run_remote_ci', read: (s) => (names(s.organization_variables).includes('RUN_REMOTE_CI') ? 'visible' : 'not visible'), expect: { public: 'not visible', private: 'visible' } },
    { id: 'pull_request_secrets', read: pullRequestSecrets, expect: { public: ['not used', 'in both stores'], private: ['not used', 'in both stores'] } },
    { id: 'vulnerability_alerts', read: (s) => status(s.vulnerability_alerts_status, 'enabled', 'disabled'), expect: { public: 'enabled', private: 'enabled' } },
    { id: 'automated_security_fixes', read: (s) => (s.automated_security_fixes?.enabled ? (s.automated_security_fixes.paused ? 'paused' : 'enabled') : 'disabled'), expect: { public: 'enabled', private: 'enabled' } },
    { id: 'dependabot_version_updates', read: (s) => status(s.dependabot_yml_status, 'present', 'absent'), expect: { public: OPEN, private: OPEN } },
    { id: 'code_security_configuration', read: (s) => s.code_security_configuration?.configuration?.name ?? 'none', expect: { public: 'GitHub recommended', private: 'Dependabot only' } },
    { id: 'secret_scanning', read: (s) => analysis(s, 'secret_scanning'), expect: { public: 'enabled', private: 'disabled' } },
    { id: 'secret_scanning_push_protection', read: (s) => analysis(s, 'secret_scanning_push_protection'), expect: { public: 'enabled', private: 'disabled' } },
    { id: 'secret_scanning_non_provider_patterns', read: (s) => analysis(s, 'secret_scanning_non_provider_patterns'), expect: { public: 'enabled', private: 'disabled' } },
    { id: 'secret_scanning_validity_checks', read: (s) => analysis(s, 'secret_scanning_validity_checks'), expect: { public: 'enabled', private: 'disabled' } },
    { id: 'rule_deletion', read: (s) => hasRule(s, 'deletion'), expect: { public: 'present', private: 'present' } },
    { id: 'rule_non_fast_forward', read: (s) => hasRule(s, 'non_fast_forward'), expect: { public: 'present', private: 'present' } },
    { id: 'rule_pull_request', read: (s) => hasRule(s, 'pull_request'), expect: { public: 'present', private: 'present' } },
    { id: 'rule_required_status_checks', read: requiredChecks, expect: { public: REQUIRED_CONTEXTS.join(', '), private: 'none' } },
    { id: 'ruleset_family', read: rulesetFamily, expect: { public: 'agrees', private: 'agrees' } },
    { id: 'required_checks', read: requiredChecksProperty, expect: { public: 'agrees', private: 'agrees' } },
]

/** How an expected value is written, in the output and in the SKILL.md table. */
export function show(expected) {
    return Array.isArray(expected) ? expected.map(String).join(' or ') : String(expected)
}

/** The visibility a snapshot is judged against: `public`, or `private` for private and internal. */
export const visibilityOf = (s) => (s.repo?.visibility === 'public' ? 'public' : 'private')

/** Every departure in one snapshot: [{ id, expected, found }]. Open rows are never departures. */
export function departures(snapshot, baseline = BASELINE) {
    const visibility = visibilityOf(snapshot)
    const found = []
    for (const row of baseline) {
        const expected = row.expect[visibility]
        if (expected === OPEN) continue
        const value = row.read(snapshot)
        const accepted = Array.isArray(expected) ? expected : [expected]
        if (!accepted.some((candidate) => candidate === value)) found.push({ id: row.id, expected: show(expected), found: String(value) })
    }
    return found
}

/** A snapshot that matches the baseline for `visibility`, built by hand rather than from BASELINE. */
export function matchingSnapshot(visibility) {
    const isPublic = visibility === 'public'
    const scanning = { status: isPublic ? 'enabled' : 'disabled' }
    const rules = [{ type: 'deletion' }, { type: 'non_fast_forward' }, { type: 'pull_request' }]
    if (isPublic) rules.push({ type: 'required_status_checks', parameters: { required_status_checks: [{ context: 'CI' }, { context: 'Attribution footer' }, { context: 'Closing keywords' }] } })
    for (const rule of rules) rule.ruleset_source_type = 'Organization'
    return {
        repo: {
            name: `control-${visibility}`, visibility, archived: false,
            allow_squash_merge: true, allow_merge_commit: false, allow_rebase_merge: false,
            squash_merge_commit_title: 'PR_TITLE', squash_merge_commit_message: 'PR_BODY',
            allow_auto_merge: false, delete_branch_on_merge: false, allow_update_branch: true,
            web_commit_signoff_required: false, has_issues: true, has_projects: true, has_wiki: false, has_discussions: false,
            security_and_analysis: {
                secret_scanning: scanning, secret_scanning_push_protection: scanning,
                secret_scanning_non_provider_patterns: scanning, secret_scanning_validity_checks: scanning,
            },
        },
        actions: { enabled: true, allowed_actions: 'all' },
        workflow: { default_workflow_permissions: 'write', can_approve_pull_request_reviews: true },
        vulnerability_alerts_status: 204,
        automated_security_fixes: { enabled: true },
        code_security_configuration: { configuration: { name: isPublic ? 'GitHub recommended' : 'Dependabot only' } },
        actions_secrets: { secrets: [] },
        dependabot_secrets: { secrets: [] },
        organization_variables: { variables: isPublic ? [] : [{ name: 'RUN_REMOTE_CI' }] },
        rules,
        properties: [{ property_name: RULESET_PROPERTY, value: 'base' }, ...(isPublic ? [{ property_name: CHECKS_PROPERTY, value: ['pr-body', 'ci-aggregate'] }] : [])],
        dependabot_yml_status: 200,
    }
}

/** Known inputs the comparison must judge correctly. Returns the names of any it got wrong. */
export function controlFailures(baseline = BASELINE) {
    const failed = []
    for (const visibility of ['public', 'private']) {
        const good = matchingSnapshot(visibility)
        if (departures(good, baseline).length !== 0) failed.push(`a ${visibility} snapshot matching the baseline reported a departure`)
        const planted = structuredClone(good)
        planted.repo.allow_merge_commit = true
        const found = departures(planted, baseline)
        if (found.length !== 1 || found[0].id !== 'allow_merge_commit') failed.push(`a planted merge-commit departure on a ${visibility} snapshot was not the one departure reported`)
        const open = structuredClone(good)
        open.repo.has_wiki = !open.repo.has_wiki
        open.repo.delete_branch_on_merge = !open.repo.delete_branch_on_merge
        if (visibility === 'private') open.repo.allow_update_branch = !open.repo.allow_update_branch
        open.dependabot_yml_status = 404
        if (departures(open, baseline).length !== 0) failed.push(`an open-question row was reported as a departure on a ${visibility} snapshot`)
        const paused = structuredClone(good)
        paused.automated_security_fixes.paused = true
        if (!departures(paused, baseline).some((item) => item.id === 'automated_security_fixes')) failed.push(`paused security updates on a ${visibility} snapshot were not reported`)
    }
    const leak = structuredClone(matchingSnapshot('public'))
    leak.actions_secrets.secrets.push({ name: 'COMPOSER_AUTH_TOKEN' })
    if (!departures(leak, baseline).some((item) => item.id === 'pull_request_secrets')) failed.push('an Actions-only pull-request secret was not reported')
    for (const visibility of ['public', 'private']) {
        const good = matchingSnapshot(visibility)
        const wrongValue = structuredClone(good)
        wrongValue.properties = [{ property_name: RULESET_PROPERTY, value: 'none' }]
        if (!departures(wrongValue, baseline).some((item) => item.id === 'ruleset_family')) failed.push(`a ${visibility} property value that disagrees with the rules in effect was not reported`)
        const unset = structuredClone(good)
        unset.properties = []
        if (!departures(unset, baseline).some((item) => item.id === 'ruleset_family')) failed.push(`an unset property on a ${visibility} repository holding rules was not reported`)
        const repositoryRule = structuredClone(good)
        repositoryRule.rules.push({ type: 'required_status_checks', ruleset_source_type: 'Repository', parameters: { required_status_checks: [{ context: 'Only a repository ruleset requires this' }] } })
        if (departures(repositoryRule, baseline).some((item) => item.id === 'ruleset_family')) failed.push(`a repository-sourced rule changed the ruleset family on a ${visibility} snapshot`)
    }
    for (const visibility of ['public']) {
        const atomic = structuredClone(matchingSnapshot(visibility))
        atomic.properties = [{ property_name: RULESET_PROPERTY, value: 'base' }, { property_name: CHECKS_PROPERTY, value: ['pr-body', 'ci-aggregate'] }]
        atomic.rules = atomic.rules.filter((rule) => rule.type !== 'required_status_checks')
        atomic.rules.push({ type: 'required_status_checks', ruleset_source_type: 'Organization', parameters: { required_status_checks: ['CI', 'Attribution footer', 'Closing keywords'].map((context) => ({ context })) } })
        if (departures(atomic, baseline).length !== 0) failed.push(`a ${visibility} snapshot holding both atomic values with their contexts reported a departure`)
        const lacking = structuredClone(atomic)
        lacking.rules.find((rule) => rule.type === 'required_status_checks').parameters.required_status_checks = [{ context: 'CI' }]
        if (!departures(lacking, baseline).some((item) => item.id === 'required_checks')) failed.push(`a ${visibility} value whose contexts are not required was not reported`)
        const nothing = structuredClone(atomic)
        nothing.properties = [{ property_name: RULESET_PROPERTY, value: 'base' }]
        if (!departures(nothing, baseline).some((item) => item.id === 'required_checks')) failed.push(`a ${visibility} repository requiring contexts with no value was not reported`)
    }
    return failed
}

/** The only arguments ghApi() passes on: `-i`, and a `repos/` path with no `..` segment and an optional query string. */
export const READ_PATH = /^(?!.*\.\.)repos\/[\w.-]+\/[\w.-]+(\/[\w./-]*)?(\?[\w=&]*)?$/

/** Whether `args` is a read ghApi() allows: a path, or `-i` and a path. */
export const isRead = (args) => (args.length === 1 && READ_PATH.test(args[0])) || (args.length === 2 && args[0] === '-i' && READ_PATH.test(args[1]))

/** `gh api` over REST, reads only. An allow-list, so no flag that makes `gh api` write can reach it. Returns { status, stdout, stderr }. */
export function ghApi(args) {
    if (!isRead(args)) throw new Error(`refusing anything but a read: gh api ${args.join(' ')}`)
    const result = spawnSync('gh', ['api', ...args], { encoding: 'utf8' })
    return { status: result.status ?? 2, stdout: result.stdout ?? '', stderr: result.stderr ?? '' }
}

/** Read one repository's settings. Returns { snapshot } or { error }. */
export function readSnapshot(gh, fullName) {
    const base = `repos/${fullName}`
    const json = (path) => {
        const result = gh([path])
        if (result.status !== 0) throw new Error(`GET ${path}: ${result.stderr.trim().split('\n')[0] || `exit ${result.status}`}`)
        return JSON.parse(result.stdout)
    }
    const list = (path, key) => {
        const value = json(path)
        const held = Array.isArray(value?.[key]) ? value[key].length : -1
        if (held < 0 || value.total_count !== held) throw new Error(`GET ${path}: read ${held} of ${value?.total_count} ${key}, so the list is incomplete`)
        return value
    }
    // the property values come back as a bare array, so anything else is a failed read
    const array = (path) => {
        const value = json(path)
        if (!Array.isArray(value)) throw new Error(`GET ${path}: expected a list of property values`)
        return value
    }
    const code = (path) => {
        const result = gh(['-i', path])
        const line = result.stdout.split('\n')[0] ?? ''
        const match = line.match(/^HTTP\/[\d.]+ (\d{3})/)
        if (match === null) throw new Error(`GET ${path}: no status line (${result.stderr.trim().split('\n')[0] || `exit ${result.status}`})`)
        const answer = Number(match[1])
        if (!ANSWERS.includes(answer)) throw new Error(`GET ${path}: HTTP ${answer}, which is not an answer`)
        return answer
    }
    // GitHub reads either spelling of the file name
    const dependabotFile = () => {
        const yml = code(`${base}/contents/.github/dependabot.yml`)
        return yml === 404 ? code(`${base}/contents/.github/dependabot.yaml`) : yml
    }
    try {
        const repo = json(base)
        const branch = repo.default_branch ?? 'main'
        return {
            snapshot: {
                repo,
                actions: json(`${base}/actions/permissions`),
                workflow: json(`${base}/actions/permissions/workflow`),
                vulnerability_alerts_status: code(`${base}/vulnerability-alerts`),
                automated_security_fixes: json(`${base}/automated-security-fixes`),
                code_security_configuration: json(`${base}/code-security-configuration`),
                actions_secrets: list(`${base}/actions/secrets?per_page=100`, 'secrets'),
                dependabot_secrets: list(`${base}/dependabot/secrets?per_page=100`, 'secrets'),
                organization_variables: list(`${base}/actions/organization-variables?per_page=30`, 'variables'),
                rules: json(`${base}/rules/branches/${branch}`),
                properties: array(`${base}/properties/values`),
                dependabot_yml_status: dependabotFile(),
            },
        }
    } catch (error) {
        return { error: error.message }
    }
}

function parseArgs(argv) {
    const options = { repos: [], manifest: undefined, from: undefined, open: false }
    for (let i = 0; i < argv.length; i++) {
        if (argv[i] === '--manifest') options.manifest = argv[++i]
        else if (argv[i] === '--from') options.from = argv[++i]
        else if (argv[i] === '--open') options.open = true
        else if (/^[\w.-]+\/[\w.-]+$/.test(argv[i])) options.repos.push(argv[i])
        else return { error: `unexpected argument ${argv[i]}` }
    }
    if (options.manifest === '' || options.from === '') return { error: '--manifest and --from each need a path' }
    if (options.repos.length === 0 && options.manifest === undefined) return { error: 'name each repository as <owner>/<repo>, or pass --manifest <path>' }
    return options
}

/** Run the check. `gh`, `readFile` and `out` are injectable so the tests never reach the network. */
export function main(argv, { gh = ghApi, readFile = (path) => readFileSync(path, 'utf8'), out = (line) => console.log(line) } = {}) {
    const options = parseArgs(argv)
    if (options.error !== undefined) {
        out(`check-repository-settings: ${options.error}`)
        return 2
    }
    const broken = controlFailures()
    if (broken.length > 0) {
        out(`check-repository-settings: BROKEN INSTRUMENT, a control failed: ${broken.join('; ')}`)
        return 2
    }
    const repos = [...options.repos]
    // a manifest's own `public` flag for each target, compared with the visibility read
    const declared = new Map()
    if (options.manifest !== undefined) {
        try {
            const manifest = JSON.parse(readFile(options.manifest))
            for (const target of manifest.targets ?? []) {
                repos.push(`${manifest.owner}/${target.name}`)
                declared.set(`${manifest.owner}/${target.name}`, target.public === true)
            }
        } catch (error) {
            out(`check-repository-settings: could not read the manifest ${options.manifest}: ${error.message}`)
            return 2
        }
        if (repos.length === 0) {
            out(`check-repository-settings: the manifest ${options.manifest} lists no targets`)
            return 2
        }
    }

    const open = BASELINE.filter((row) => row.expect.public === OPEN || row.expect.private === OPEN)
    out(`check-repository-settings: ${repos.length} repositories, ${BASELINE.length} rows, ${open.length} with an open question`)
    let failedReads = 0
    let departing = 0
    let total = 0
    for (const fullName of repos) {
        let read
        if (options.from !== undefined) {
            try {
                read = { snapshot: JSON.parse(readFile(join(options.from, `${fullName.split('/')[1]}.json`))) }
            } catch (error) {
                read = { error: error.message }
            }
        } else {
            read = readSnapshot(gh, fullName)
        }
        if (read.error !== undefined) {
            out(`${fullName}: could not read: ${read.error}`)
            failedReads++
            continue
        }
        const visibility = visibilityOf(read.snapshot)
        if (read.snapshot.repo?.archived) {
            out(`${fullName} (${visibility}): archived, not judged`)
            continue
        }
        const found = departures(read.snapshot)
        if (declared.has(fullName) && declared.get(fullName) !== (visibility === 'public')) {
            found.push({ id: 'manifest_public', expected: `"public": ${visibility === 'public'} in the manifest`, found: `"public": ${declared.get(fullName)}` })
        }
        for (const item of found) out(`${fullName} (${visibility}): ${item.id}: expected ${item.expected}, found ${item.found}`)
        if (options.open) {
            for (const row of open) if (row.expect[visibility] === OPEN) out(`${fullName} (${visibility}): ${row.id}: open question, reads ${String(row.read(read.snapshot))}`)
        }
        if (found.length > 0) departing++
        total += found.length
    }
    out(`check-repository-settings: ${total} departures in ${departing} of ${repos.length - failedReads} repositories read`)
    if (failedReads > 0) {
        out(`check-repository-settings: ${failedReads} repositories could not be read, so the result is incomplete`)
        return 2
    }
    return total > 0 ? 1 : 0
}

if (process.argv[1] !== undefined && fileURLToPath(import.meta.url) === process.argv[1]) process.exitCode = main(process.argv.slice(2))
