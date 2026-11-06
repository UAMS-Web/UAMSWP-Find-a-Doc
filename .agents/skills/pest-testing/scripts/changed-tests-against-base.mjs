#!/usr/bin/env node
// cspell:ignore apos
//
// changed-tests-against-base.mjs: run a pull request's changed test files against the base
// branch's copy of the source, and report per test file (UAMS-Web/uams-claude-skills#391,
// decided in #357).
//
// A pull request that edits a class and adds tests runs those tests against its own source, which
// passes whether or not the edit changed behavior. This answers the other question: do the tests
// the pull request adds or changes already pass against the source as it was? It reports, and it
// does not judge: a test failing against base is what a bug fix is expected to show.
//
// THE TREE. A scratch git worktree of HEAD, outside the checkout, so the caller's tree is never
// touched. Every change the pull request makes outside the test directories is reverted there to
// the base branch's version (a modified file restored, an added file deleted, a deleted file put
// back, a rename undone), so the tree is the base branch with the pull request's test side laid
// over it. The test side is everything under the test directories (`tests/` by default): the
// changed test files and the support files beside them (`tests/Pest.php`, fixtures, helpers),
// because those are part of what the tests say, and a helper a new test calls is not a behavior
// of the source. The report lists every support file laid over, and every source file left at
// base. Dependencies are installed in the scratch tree (`composer install` by default), never
// linked from the checkout, since a linked `vendor/` makes Pest load the donor tree's bootstrap.
//
// THE BASE. `--base <ref>`, else `origin/$GITHUB_BASE_REF` on a pull request in Actions, else
// `origin/main`. The comparison is against the merge base of that ref and HEAD, which is the base
// branch's tip when HEAD is the merge commit Actions checks out. A ref that does not resolve is
// an error naming the likely cause (a shallow clone: check out with `fetch-depth: 0`).
//
// WHAT RUNS. Each added, modified, renamed or copied file under a test directory whose name ends
// in `Test.php`, together in one run of `<php> vendor/bin/pest <files> --log-junit <file>`. When
// that run aborts before writing results (a file that does not load), each file runs alone, so
// the abort is attributed to the file that caused it.
//
// THE REPORT, per test file, written to stdout and to $GITHUB_STEP_SUMMARY when it is set:
//     passed against base
//     failed against base       the failing cases, named
//     needs the pull request's source
//                               every failure is a class, method, function, interface, trait, enum
//                               or constant the base does not have and the pull request's changed
//                               source declares, so the test calls something the pull request
//                               added; that is not a behavior change. A missing symbol the pull
//                               request does not declare (the bug it fixes, say) is a failure
//     skipped                   every case skipped, so nothing was compared
//     did not run               the file aborted against base for another reason
//
// EXIT CODES
//     0  the report was produced (whatever it says), or there was nothing to compare
//     2  the comparison could not run: the base did not resolve, the tree could not be built or
//        installed, the runner wrote no results, or a test file did not run against base
//
// USAGE, from the repository root:
//     node .claude/skills/pest-testing/scripts/changed-tests-against-base.mjs [--base <ref>]
//         [--tests <dir>]... [--install <command>|none] [--php <binary>] [--bin <runner>]

import { spawnSync } from 'node:child_process'
import { appendFileSync, existsSync, mkdtempSync, readFileSync, realpathSync, rmSync } from 'node:fs'
import { tmpdir } from 'node:os'
import { join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

export const DEFAULT_TEST_DIRS = ['tests/']
const TEST_FILE = /Test\.php$/

/** Failure messages that mean the base lacks something the test calls, not that behavior differs. */
export const NEEDS_SOURCE = /Call to undefined (?:method|function)\b|(?:Class|Interface|Trait|Enum) "[^"]+" not found|Undefined constant\b/

/**
 * `git diff --name-status -M -z` output as entries: {status, path, from}. `from` is set for a
 * rename or copy (R or C, which carry a score) and names the old path.
 */
export function parseNameStatus(text) {
    const parts = text.split('\0').filter((part) => part !== '')
    const entries = []
    for (let i = 0; i < parts.length; ) {
        const code = parts[i++]
        const status = code[0]
        if (status === 'R' || status === 'C') {
            const from = parts[i++]
            const path = parts[i++]
            entries.push({ status, path, from })
        } else {
            entries.push({ status, path: parts[i++], from: null })
        }
    }
    return entries
}

const underAny = (path, dirs) => dirs.some((dir) => path.startsWith(dir.endsWith('/') ? dir : `${dir}/`))

/**
 * Sort the diff into what the comparison needs:
 *   run          test files to run (added, modified, renamed to, copied to)
 *   support      other changed files under a test directory, laid over the base tree as changed
 *   deleted      test files the pull request deletes; nothing to run
 *   renamed      test files the pull request moves, as {from, to}
 *   source       entries outside the test directories, each reverted to the base in the tree
 */
export function selectChanges(entries, testDirs = DEFAULT_TEST_DIRS) {
    const out = { run: [], support: [], deleted: [], renamed: [], source: [] }
    for (const entry of entries) {
        const testSide = underAny(entry.path, testDirs)
        const fromTestSide = entry.from !== null && underAny(entry.from, testDirs)
        if (!testSide && !fromTestSide) {
            out.source.push(entry)
            continue
        }
        if (entry.status === 'R' && testSide !== fromTestSide) {
            // Moved across the boundary: the half outside the test side is source, reverted.
            out.source.push(testSide ? { status: 'D', path: entry.from, from: null } : { status: 'A', path: entry.path, from: null })
            if (!testSide) continue
        }
        if (entry.status === 'D') {
            if (TEST_FILE.test(entry.path)) out.deleted.push(entry.path)
            else out.support.push(entry.path)
            continue
        }
        if (entry.status === 'R' && fromTestSide && TEST_FILE.test(entry.path)) out.renamed.push({ from: entry.from, to: entry.path })
        if (TEST_FILE.test(entry.path)) out.run.push(entry.path)
        else out.support.push(entry.path)
    }
    out.run.sort()
    out.support.sort()
    return out
}

/**
 * What to do in a tree checked out at HEAD to put each source change back to the base:
 * `{op: 'restore', path}` writes the base's version, `{op: 'delete', path}` removes the file.
 */
export function overlayPlan(source) {
    const plan = []
    for (const entry of source) {
        if (entry.status === 'A' || entry.status === 'C') plan.push({ op: 'delete', path: entry.path })
        else if (entry.status === 'D') plan.push({ op: 'restore', path: entry.path })
        else if (entry.status === 'R') plan.push({ op: 'delete', path: entry.path }, { op: 'restore', path: entry.from })
        else plan.push({ op: 'restore', path: entry.path })
    }
    return plan
}

const ENTITIES = { quot: '"', amp: '&', lt: '<', gt: '>', apos: "'" }
const decode = (text) => text.replace(/&(#x[0-9a-f]+|#\d+|\w+);/gi, (whole, ref) => {
    if (ref[0] === '#') return String.fromCodePoint(ref[1] === 'x' || ref[1] === 'X' ? parseInt(ref.slice(2), 16) : Number(ref.slice(1)))
    return ENTITIES[ref] ?? whole
})
const attr = (tag, name) => {
    const m = tag.match(new RegExp(`\\s${name}="([^"]*)"`))
    return m ? decode(m[1]) : null
}
// Pest writes backslashes into the `file` attributes on Windows; the paths read from a diff use slashes.
const slashes = (file) => (file === null ? null : file.replaceAll('\\', '/'))

/**
 * Every test case of a JUnit log, each `{file, className, name, problem, skipped}`. `file` is the
 * path before `::` in the case's own `file` attribute, or the nearest enclosing suite's `file`;
 * Pest nests a `describe` block or a dataset in a suite of its own with no `file`, so a case is
 * never matched by its innermost suite alone. `className` is the case's `class` attribute, which
 * is the declared class for a class-based file (Pest logs those under the class, not the path).
 * `problem` is the failure or error message, or null; `skipped` marks a skipped or todo case.
 * Null when the text is not a JUnit log at all, which is how an aborted run shows itself.
 */
export function parseJunit(xml) {
    if (!/<testsuites[\s>]/.test(xml)) return null
    const cases = []
    const stack = []
    const tagRe = /<testsuite\b([^>]*?)(\/?)>|<\/testsuite>|<testcase\b([^>]*?)(?:\/>|>([\s\S]*?)<\/testcase>)/g
    for (const m of xml.matchAll(tagRe)) {
        if (m[0].startsWith('</testsuite')) {
            stack.pop()
            continue
        }
        if (m[0].startsWith('<testsuite')) {
            if (m[2] !== '/') stack.push(slashes(attr(`<x${m[1]}>`, 'file')))
            continue
        }
        const tag = `<x${m[3]}>`
        const inner = m[4] ?? ''
        const own = slashes(attr(tag, 'file'))
        const suiteFile = [...stack].reverse().find((file) => file !== null) ?? null
        const problem = inner.match(/<(?:failure|error)\b[^>]*>([\s\S]*?)<\/(?:failure|error)>/)
        cases.push({
            file: own !== null && own.includes('::') ? own.slice(0, own.indexOf('::')) : (own ?? suiteFile),
            suiteFile,
            className: attr(tag, 'class'),
            name: attr(tag, 'name'),
            problem: problem ? decode(problem[1]).trim() : null,
            skipped: /<skipped\b/.test(inner),
        })
    }
    return cases
}

/** The fully qualified class a class-based test file declares, or null for a Pest function file. */
export function declaredClass(source) {
    const cls = source.match(/^\s*(?:(?:final|abstract|readonly)\s+)*class\s+(\w+)/m)
    if (!cls) return null
    const ns = source.match(/^\s*namespace\s+([^;\s]+)\s*;/m)
    return ns ? `${ns[1]}\\${cls[1]}` : cls[1]
}

/**
 * The symbol a "does not exist" error names, as `{kind, name}`, or null for any other message.
 */
export function missingSymbol(message) {
    let m = message.match(/Call to undefined method [\w\\]+::(\w+)\(\)/)
    if (m) return { kind: 'function', name: m[1] }
    m = message.match(/Call to undefined function (?:[\w\\]*\\)?(\w+)\(\)/)
    if (m) return { kind: 'function', name: m[1] }
    m = message.match(/(?:Class|Interface|Trait|Enum) "(?:[\w\\]*\\)?(\w+)" not found/)
    if (m) return { kind: 'type', name: m[1] }
    m = message.match(/Undefined constant "?(?:[\w\\]*(?:\\|::))?(\w+)"?/)
    if (m) return { kind: 'constant', name: m[1] }
    return null
}

/**
 * Whether the pull request's changed source declares the symbol: the test calls something the
 * pull request added. A symbol it does not declare is missing from the base for another reason,
 * such as the very bug the pull request fixes, and that is a behavior change.
 */
export function declaredBy(source, symbol) {
    if (!symbol || !source) return false
    const name = symbol.name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    if (symbol.kind === 'function') return new RegExp(`\\bfunction\\s+&?${name}\\s*\\(`, 'i').test(source)
    if (symbol.kind === 'type') return new RegExp(`\\b(?:class|interface|trait|enum)\\s+${name}\\b`, 'i').test(source)
    return new RegExp(`\\bconst\\s+(?:\\w+\\s+)?${name}\\b|\\bdefine\\(\\s*['"]${name}['"]`).test(source)
}

/** Whether a failure is the pull request's new source being absent from the base. */
export function needsSource(message, prSource) {
    return NEEDS_SOURCE.test(message) && declaredBy(prSource, missingSymbol(message))
}

/**
 * One test file's result from the parsed log: its cases are those whose file is the path, or,
 * for a class-based file, whose class is the class the file declares. `prSource` is the text of
 * the pull request's changed source files, which decides whether a missing symbol is one it added.
 */
export function classify(path, cases, fqcn = null, prSource = '') {
    const mine = cases.filter((c) => c.file === path || c.suiteFile === path || (fqcn !== null && c.className === fqcn))
    if (mine.length === 0) return { status: 'not-run', detail: 'no result for this file in the runner log' }
    const failing = mine.filter((c) => c.problem !== null)
    const skipped = mine.filter((c) => c.problem === null && c.skipped).length
    const ran = mine.length - skipped
    if (failing.length === 0) return ran === 0 ? { status: 'skipped', cases: skipped } : { status: 'passed', cases: ran, skipped }
    const named = failing.map((c) => ({ name: c.name, message: firstLine(c.problem, c.name) }))
    if (failing.every((c) => needsSource(c.problem, prSource))) return { status: 'needs-source', failing: named }
    return { status: 'failed', failing: named, of: mine.length }
}

/** The first line of a failure message, without the case name Pest prefixes to it. */
function firstLine(message, name) {
    const line = message.split('\n')[0]
    return name && line.startsWith(name) ? line.slice(name.length).trim() : line.trim()
}

/** A file whose lone run aborted: needs the pull request's source when the output names a symbol the pull request declares. */
export function classifyAbort(output, prSource = '') {
    const reason = output.split('\n').map((line) => line.trim()).find((line) => needsSource(line, prSource))
    if (reason) return { status: 'needs-source', failing: [{ name: '(file did not load)', message: reason }] }
    const lines = output.split('\n').map((line) => line.trim()).filter((line) => line !== '' && !/^[\d\u279C\u2595\s]+$/.test(line))
    return { status: 'not-run', detail: lines.slice(0, 3).join(' ') || 'the runner aborted with no output' }
}

const code = (text) => `\`${String(text).replace(/`/g, "'")}\``

/** The Markdown report. */
export function renderReport({ base, selection, results }) {
    const lines = ['## Changed tests against base', '']
    if (selection.run.length === 0) {
        lines.push('No test file is added or changed, so there is nothing to compare.')
        if (selection.deleted.length) lines.push('', `Deleted test files: ${selection.deleted.map(code).join(', ')}.`)
        return `${lines.join('\n')}\n`
    }
    lines.push(`The changed test files, run against the source at ${code(base)} with the pull request's test side laid over it. A failure against base is information for the reviewer, not a defect: a bug fix is expected to show one, and the pull request body says which.`, '')
    lines.push('| Test file | Against base | Detail |', '| --- | --- | --- |')
    const label = { passed: 'passed', failed: 'failed', skipped: 'skipped', 'needs-source': "needs the pull request's source", 'not-run': 'did not run' }
    for (const path of selection.run) {
        const r = results.get(path)
        let detail = ''
        if (r.status === 'passed') detail = `${r.cases} case${r.cases === 1 ? '' : 's'}${r.skipped ? `, ${r.skipped} skipped` : ''}`
        else if (r.status === 'skipped') detail = `every case skipped (${r.cases}), so nothing was compared`
        else if (r.status === 'not-run') detail = r.detail
        else detail = r.failing.map((f) => `${f.name}: ${f.message}`).join('; ')
        const cell = (text) => text.replace(/\|/g, '\\|').replace(/\n/g, ' ')
        lines.push(`| ${cell(code(path))} | ${label[r.status]} | ${cell(detail)} |`)
    }
    lines.push('')
    lines.push(selection.source.length
        ? `Source files left at base: ${[...new Set(selection.source.map((e) => e.from ?? e.path))].sort().map(code).join(', ')}.`
        : 'The pull request changes no file outside the test directories, so the source is the same on both sides.')
    if (selection.support.length) lines.push(`Test support files laid over the base: ${selection.support.map(code).join(', ')}.`)
    if (selection.renamed.length) lines.push(`Renamed test files: ${selection.renamed.map((r) => `${code(r.from)} to ${code(r.to)}`).join(', ')}.`)
    if (selection.deleted.length) lines.push(`Deleted test files, not run: ${selection.deleted.map(code).join(', ')}.`)
    return `${lines.join('\n')}\n`
}

// --- the run ----------------------------------------------------------------

export function parseArgs(argv, env = process.env) {
    const opts = { base: null, tests: [], install: 'composer install --no-interaction --no-progress', php: env.LOCAL_CI_PHP || 'php', bin: 'vendor/bin/pest', help: false }
    for (let i = 0; i < argv.length; i++) {
        const a = argv[i]
        const value = () => {
            const v = argv[++i]
            if (v === undefined || v === '') throw new Error(`${a} needs a value`)
            return v
        }
        if (a === '--base') opts.base = value()
        else if (a === '--tests') opts.tests.push(value())
        else if (a === '--install') opts.install = value()
        else if (a === '--php') opts.php = value()
        else if (a === '--bin') opts.bin = value()
        else if (a === '--help' || a === '-h') opts.help = true
        else throw new Error(`unknown argument ${a}`)
    }
    if (opts.tests.length === 0) opts.tests = [...DEFAULT_TEST_DIRS]
    return opts
}

class CannotRun extends Error {}

function git(cwd, ...args) {
    const r = spawnSync('git', args, { cwd, encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
    if (r.status !== 0) throw new CannotRun(`git ${args.join(' ')} failed: ${(r.stderr || r.stdout || '').trim()}`)
    return r.stdout
}

/** The base ref to compare against, or null when this is an Actions run that is not a pull request. */
export function baseRef(opts, env = process.env) {
    if (opts.base) return opts.base
    if (env.GITHUB_BASE_REF) return `origin/${env.GITHUB_BASE_REF}`
    if (env.GITHUB_EVENT_NAME && !/^pull_request/.test(env.GITHUB_EVENT_NAME)) return null
    return 'origin/main'
}

function runTests(tree, opts, files, junit) {
    rmSync(junit, { force: true })
    const r = spawnSync(opts.php, [opts.bin, ...files, '--log-junit', junit, '--colors=never'], { cwd: tree, encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
    if (r.error) throw new CannotRun(`could not start ${opts.php} ${opts.bin}: ${r.error.message}`)
    const xml = existsSync(junit) ? readFileSync(junit, 'utf8') : ''
    return { suites: parseJunit(xml), output: `${r.stdout ?? ''}\n${r.stderr ?? ''}` }
}

export function main(argv = process.argv.slice(2), { cwd = process.cwd(), env = process.env, log = console.log, error = console.error } = {}) {
    let opts
    try {
        opts = parseArgs(argv, env)
    } catch (e) {
        error(`changed-tests-against-base: ${e.message}`)
        return 2
    }
    if (opts.help) {
        log('Usage: node .claude/skills/pest-testing/scripts/changed-tests-against-base.mjs [--base <ref>] [--tests <dir>]... [--install <command>|none] [--php <binary>] [--bin <runner>]')
        return 0
    }
    const ref = baseRef(opts, env)
    if (ref === null) {
        log(`changed-tests-against-base: ${env.GITHUB_EVENT_NAME} is not a pull request, so there is nothing to compare.`)
        return 0
    }
    let tree = null
    try {
        const root = git(cwd, 'rev-parse', '--show-toplevel').trim()
        const resolved = spawnSync('git', ['rev-parse', '--verify', '--quiet', `${ref}^{commit}`], { cwd: root, encoding: 'utf8' })
        if (resolved.status !== 0) throw new CannotRun(`the base ${ref} does not resolve. In Actions, check out with fetch-depth: 0 so the base branch is present; locally, fetch it or pass --base.`)
        const base = git(root, 'merge-base', ref, 'HEAD').trim()
        const selection = selectChanges(parseNameStatus(git(root, 'diff', '--name-status', '-M', '-z', base, 'HEAD')), opts.tests)
        const shortBase = `${ref} (${base.slice(0, 9)})`
        if (selection.run.length === 0) {
            emit(renderReport({ base: shortBase, selection, results: new Map() }), env, log)
            return 0
        }

        tree = mkdtempSync(join(tmpdir(), 'changed-tests-'))
        rmSync(tree, { recursive: true, force: true })
        // A signal skips `finally`, so the scratch worktree is removed on one as well.
        const scratchTree = tree
        for (const signal of ['SIGINT', 'SIGTERM']) {
            onSignal.set(signal, () => {
                removeTree(cwd, scratchTree)
                process.exit(signal === 'SIGINT' ? 130 : 143)
            })
            process.once(signal, onSignal.get(signal))
        }
        git(root, 'worktree', 'add', '--detach', '--quiet', tree, 'HEAD')
        // The pull request's own version of each changed source file, read before the overlay
        // reverts it, so a missing symbol can be checked against what the pull request declares.
        const prSource = selection.source.filter((e) => e.status !== 'D').map((e) => {
            const r = spawnSync('git', ['show', `HEAD:${e.path}`], { cwd: root, encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
            return r.status === 0 ? r.stdout : ''
        }).join('\n')
        for (const step of overlayPlan(selection.source)) {
            if (step.op === 'delete') rmSync(join(tree, step.path), { recursive: true, force: true })
            else git(tree, 'checkout', base, '--', step.path)
        }
        if (opts.install !== 'none') {
            const r = spawnSync(opts.install, { cwd: tree, shell: true, encoding: 'utf8', maxBuffer: 64 * 1024 * 1024 })
            if (r.status !== 0) throw new CannotRun(`the install in the base tree failed (exit ${r.status}): ${(r.stderr || r.stdout || '').trim().split('\n').slice(-5).join(' ')}`)
        }

        const junit = join(tree, '.changed-tests-junit.xml')
        const fqcn = new Map(selection.run.map((path) => [path, declaredClass(readFileSync(join(tree, path), 'utf8'))]))
        const results = new Map()
        const together = runTests(tree, opts, selection.run, junit)
        if (together.suites !== null) {
            for (const path of selection.run) results.set(path, classify(path, together.suites, fqcn.get(path), prSource))
        }
        // An aborted run, or a file the combined log does not account for, runs again alone.
        for (const path of selection.run) {
            if (results.has(path) && results.get(path).status !== 'not-run') continue
            const alone = runTests(tree, opts, [path], junit)
            results.set(path, alone.suites === null ? classifyAbort(alone.output, prSource) : classify(path, alone.suites, fqcn.get(path), prSource))
        }
        emit(renderReport({ base: shortBase, selection, results }), env, log)
        const notRun = selection.run.filter((path) => results.get(path).status === 'not-run')
        if (notRun.length) {
            error(`changed-tests-against-base: ${notRun.length} test file(s) did not run against base: ${notRun.join(', ')}`)
            return 2
        }
        return 0
    } catch (e) {
        error(`changed-tests-against-base: ${e instanceof CannotRun ? e.message : `${e.code ? `${e.code}: ` : ''}${e.message}`}`)
        return 2
    } finally {
        if (tree !== null) removeTree(cwd, tree)
        for (const [signal, handler] of onSignal) process.removeListener(signal, handler)
        onSignal.clear()
    }
}

const onSignal = new Map()

function removeTree(cwd, tree) {
    spawnSync('git', ['worktree', 'remove', '--force', tree], { cwd, encoding: 'utf8' })
    rmSync(tree, { recursive: true, force: true })
    spawnSync('git', ['worktree', 'prune'], { cwd, encoding: 'utf8' })
}

function emit(report, env, log) {
    log(report)
    if (env.GITHUB_STEP_SUMMARY) appendFileSync(env.GITHUB_STEP_SUMMARY, report)
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

