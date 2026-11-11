#!/usr/bin/env node
// cspell:ignore Stache stache
// clear-testing-litter.mjs: clear the throwaway test state a killed Pest or mutate run
// leaves under `storage/framework/testing`, then clear the application cache and the Stache
// (UAMS-Web/uams-claude-skills#118).
//
// The directory's tracked `.gitignore` is `*`, so the litter never shows in `git status`,
// and an isolated-Stache suite reading it fails every case with `Call to a member function
// handle() on null`. This script:
//
//   1. removes every entry under `storage/framework/testing` except `.gitignore`, printing
//      each one it removes;
//   2. restores the tracked `.gitignore` with `git checkout` when it is missing;
//   3. runs `php artisan cache:clear` and `php artisan statamic:stache:clear`.
//
// On a tree whose directory holds only `.gitignore` it removes nothing, restores nothing,
// still clears both caches, and exits 0, so it is safe to run before any suite.
//
// Usage, from the repository root (macOS, Linux and Windows; Node 20 or newer, no
// dependencies):
//     node .claude/skills/pest-testing/scripts/clear-testing-litter.mjs
//     node .claude/skills/pest-testing/scripts/clear-testing-litter.mjs --root=<dir>
//     node .claude/skills/pest-testing/scripts/clear-testing-litter.mjs --skip-artisan
//     node .claude/skills/pest-testing/scripts/clear-testing-litter.mjs --force
//
// `--root=<dir>` acts on that application root instead of the working directory (a slot
// reached from elsewhere). `--skip-artisan` removes the litter and restores the placeholder
// but runs neither artisan command (no PHP on the machine, or the application cannot boot).
// `--force` overrides the live-run guard below and nothing else.
//
// It also refuses, changing nothing, while a mutation run is live: clearing the directory
// under a running `--mutate` orphans that run's throwaway Stache and content mirror. A run is
// live when a `storage/logs/mutate-*.status` file has no `exit=` line and names a process
// (`child=`, `runner=` or `pid=`) that is still running. A status file left by a run that was
// killed names a process that is gone and does not block. When it blocks and the file is
// wrong anyway (a recycled process id, for one), read it, confirm the run is not yours, and
// pass `--force`.
//
// It refuses, changing nothing, when the working directory has no `artisan` file or no
// `storage/framework/testing` directory, and when that directory, or a directory above it
// inside the repository, is a symbolic link: the removal never leaves the directory. A link
// inside it is removed as a link; its target is not touched.
//
// A failing command's own output (stderr, else stdout) is quoted in the failure message, under
// the command and its exit status; a command that cannot start says so, with the system's
// reason. A command that succeeds has its output replayed unchanged.
//
// Exit 0 done, 1 a step failed (a removal, the restore or an artisan command), 2 refused
// before anything changed (not an application root, a symbolic link, or a live mutation run).
//
// Shipped by the shared sync from UAMS-Web/uams-claude-skills, with its tests beside it.

import { spawnSync } from 'node:child_process'
import { existsSync, lstatSync, readdirSync, readFileSync, realpathSync, rmSync, statSync } from 'node:fs'
import { join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

export const TESTING_DIR = 'storage/framework/testing'
export const PLACEHOLDER = '.gitignore'
export const ARTISAN_COMMANDS = ['php artisan cache:clear', 'php artisan statamic:stache:clear']

/** Why `root` cannot be acted on, or null when it can. */
export function refusal(root) {
    const artisan = join(root, 'artisan')
    if (!existsSync(artisan) || !statSync(artisan).isFile()) {
        return `no artisan file in ${root}; run this from the root of a Laravel application`
    }
    const dir = join(root, ...TESTING_DIR.split('/'))
    let stat
    try {
        stat = lstatSync(dir)
    } catch {
        return `no ${TESTING_DIR} directory in ${root}`
    }
    if (stat.isSymbolicLink()) return `${TESTING_DIR} is a symbolic link; refusing to remove anything through it`
    if (!stat.isDirectory()) return `${TESTING_DIR} is not a directory`
    // A link on any directory above it (storage/, storage/framework/) would take the removal
    // out of the repository; the resolved path must be the literal one under the resolved root.
    if (realpathSync(dir) !== join(realpathSync(root), ...TESTING_DIR.split('/'))) {
        return `${TESTING_DIR} resolves outside ${root} through a symbolic link; refusing to remove anything through it`
    }
    return null
}

/** True when a process with this id is running (one we may not signal still counts). */
function pidAlive(pid) {
    if (!Number.isInteger(pid) || pid <= 0) return false
    try {
        process.kill(pid, 0)
        return true
    } catch (e) {
        return e.code === 'EPERM'
    }
}

/**
 * The status file of a live mutation run, or null when none is. A file with an `exit=` line
 * is finished; otherwise it is live when any process it names is running.
 */
export function liveMutateStatusPath(root) {
    const logs = join(root, 'storage', 'logs')
    let names
    try {
        names = readdirSync(logs).sort()
    } catch {
        return null
    }
    for (const name of names) {
        if (!/^mutate-.+\.status$/u.test(name)) continue
        const path = join(logs, name)
        let raw
        try {
            raw = readFileSync(path, 'utf8')
        } catch {
            continue
        }
        if (/^exit=/mu.test(raw)) continue
        const ids = [/\bchild=(\d+)/u, /\brunner=(\d+)/u, /\bpid=(\d+)/u].flatMap((pattern) => {
            const match = raw.match(pattern)
            return match ? [Number(match[1])] : []
        })
        if (ids.some(pidAlive)) return path
    }
    return null
}

/**
 * Run one fixed command line in `root`. The output is captured rather than inherited so a
 * failure can quote it; a run that succeeds replays it, so the quiet path prints what it
 * always did. `status` is null when the command could not start, and then `error` says why.
 */
function run(command, root, spawn = spawnSync) {
    // A fixed command string through the shell, so Windows' `.bat` shims (Herd's `php`) run;
    // nothing from outside the script reaches the string.
    const result = spawn(command, { cwd: root, shell: true, encoding: 'utf8' })
    const captured = {
        status: result.error ? null : result.status,
        error: result.error ?? null,
        signal: result.signal ?? null,
        stdout: result.stdout ?? '',
        stderr: result.stderr ?? '',
    }
    if (captured.status === 0) {
        process.stdout.write(captured.stdout)
        process.stderr.write(captured.stderr)
    }
    return captured
}

/**
 * The parenthesized outcome of a failed command plus the reason it gave: `(exit 3)` followed
 * by its trimmed stderr (stdout when stderr is empty), indented under the message; or, when
 * the command never started, `(could not start: <message>)`, or `(ended by <signal>)` when a
 * signal stopped it.
 */
export function failureDetail({ status, error, signal = null, stdout, stderr }) {
    if (status === null && signal) return `(ended by ${signal})`
    if (status === null) return `(could not start: ${error ? error.message : 'no exit status was reported'})`
    const reason = (stderr.trim() || stdout.trim()).replace(/\r?\n/gu, '\n    ')
    return reason ? `(exit ${status})\n    ${reason}` : `(exit ${status})`
}

/** Flags from the command line: `--force`, `--skip-artisan`, `--root=<dir>`. */
export function parseArgs(argv) {
    const options = { force: false, skipArtisan: false, root: null, unknown: [] }
    for (const arg of argv) {
        if (arg === '--force') options.force = true
        else if (arg === '--skip-artisan') options.skipArtisan = true
        else if (arg.startsWith('--root=') && arg.length > '--root='.length) options.root = arg.slice('--root='.length)
        else options.unknown.push(arg)
    }
    return options
}

export function main(root = process.cwd(), out = console.log, err = console.error, { force = false, skipArtisan = false, spawn = spawnSync } = {}) {
    const why = refusal(root)
    if (why) {
        err(`clear-testing-litter: refused: ${why}. Nothing was changed.`)
        return 2
    }
    if (!force) {
        const live = liveMutateStatusPath(root)
        if (live) {
            err(`clear-testing-litter: refused: ${live} has no exit= line and names a running process, so a mutation run looks live and clearing ${TESTING_DIR} would orphan it. Wait for the run to finish. If the file is stale (the run was killed and its process id has since been reused), read it, confirm the run is not yours, and pass --force. Nothing was changed.`)
            return 2
        }
    }

    const dir = join(root, ...TESTING_DIR.split('/'))
    let failed = false
    let removed = 0
    let keptPlaceholder = false
    for (const name of readdirSync(dir).sort()) {
        const path = join(dir, name)
        if (name === PLACEHOLDER && lstatSync(path).isFile()) {
            keptPlaceholder = true
            continue
        }
        try {
            // rmSync does not follow links: a link, at this level or deeper, is unlinked and
            // its target is left alone.
            rmSync(path, { recursive: true, force: true })
            removed++
            out(`removed ${TESTING_DIR}/${name}`)
        } catch (e) {
            failed = true
            err(`clear-testing-litter: could not remove ${TESTING_DIR}/${name}: ${e.message}`)
        }
    }
    if (removed === 0 && !failed) out(`nothing to remove under ${TESTING_DIR}`)

    if (!keptPlaceholder) {
        const command = `git checkout -- ${TESTING_DIR}/${PLACEHOLDER}`
        const result = run(command, root, spawn)
        if (result.status !== 0 || !existsSync(join(dir, PLACEHOLDER))) {
            err(`clear-testing-litter: \`${command}\` did not restore ${TESTING_DIR}/${PLACEHOLDER} ${failureDetail(result)}`)
            return 1
        }
        out(`restored ${TESTING_DIR}/${PLACEHOLDER}`)
    }

    for (const command of skipArtisan ? [] : ARTISAN_COMMANDS) {
        const result = run(command, root, spawn)
        if (result.status !== 0) {
            err(`clear-testing-litter: \`${command}\` failed ${failureDetail(result)}`)
            return 1
        }
    }

    if (failed) return 1
    out('clear-testing-litter: done')
    return 0
}

if (process.argv[1] && realpathSync(process.argv[1]) === realpathSync(fileURLToPath(import.meta.url))) {
    const options = parseArgs(process.argv.slice(2))
    if (options.unknown.length) {
        console.error(`clear-testing-litter: unknown argument ${options.unknown.join(' ')}; accepted: --root=<dir>, --skip-artisan, --force`)
        process.exitCode = 2
    } else {
        process.exitCode = main(resolve(options.root ?? process.cwd()), console.log, console.error, options)
    }
}
