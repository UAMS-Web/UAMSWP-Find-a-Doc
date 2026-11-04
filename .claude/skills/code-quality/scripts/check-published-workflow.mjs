#!/usr/bin/env node
// check-published-workflow.mjs: fail when the published copy of the reusable CI workflow differs
// from its template (UAMS-Web/uams-claude-skills#490, follow-up to #485).
//
// The reusable workflow is authored at templates/ci-reusable.yml and published as a byte copy at
// .github/workflows/ci.yml in UAMS-Web/uams-ci-workflows, because a public repository can call only
// a public reusable workflow. A caller pins a release tag or commit of that published file, so the
// published copy at that ref is what callers run. Nothing else compares it with the template: an edit
// to either leaves callers running a workflow that differs from the checked and documented one.
//
// WHAT IT READS. The template, the published file at a ref, and (when --ref is not given) the ref
// the caller template (templates/ci.yml) pins in its `uses:` line. The published file comes from
// raw.githubusercontent.com, which needs no token because the repository is public.
//
// WHAT IT REPORTS. A difference names both files, the first differing line, and whether the two
// differ only in line endings. A published file it cannot read (a missing ref, a missing path, a
// network failure, an empty body) is exit 2, never a pass.
//
// CONTROLS, run before anything is compared: identical text must pass, text differing on one line
// must fail and name that line, and text differing only in line endings must fail and say so. A
// control misbehaving is exit 2.
//
// Usage, from the repository root of a checkout that carries the skill:
//     node .claude/skills/code-quality/scripts/check-published-workflow.mjs [--ref <tag-or-commit>]
//         [--repo <owner/name>] [--path <file>] [--template <file>] [--caller <file>]
//         [--published-file <file>]
// --published-file reads the published copy from a local file instead of GitHub (an offline run,
// or a clone of the published repository).
//
// EXIT CODES
//     0  the published copy is identical to the template
//     1  the published copy differs; the finding is printed
//     2  the check did not run: a control failed, the pinned ref is unreadable, or the published file is unreadable

import { readFileSync, realpathSync } from 'node:fs'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

export const PUBLISHED_REPO = 'UAMS-Web/uams-ci-workflows'
export const PUBLISHED_PATH = '.github/workflows/ci.yml'

const HERE = dirname(fileURLToPath(import.meta.url))
const DEFAULT_TEMPLATE = join(HERE, '..', 'templates', 'ci-reusable.yml')
const DEFAULT_CALLER = join(HERE, '..', 'templates', 'ci.yml')

/** True for a release tag (vX.Y.Z) or a full commit hash: the only refs a caller may pin. */
export const isReleaseRef = (ref) => /^v\d+\.\d+\.\d+$/.test(ref) || /^[0-9a-f]{40}$/.test(ref)

/** The ref a caller template pins, or undefined when it has no `uses:` line of the published workflow. */
export function pinnedRef(callerText, repo = PUBLISHED_REPO, path = PUBLISHED_PATH) {
    const escaped = `${repo}/${path}`.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    const line = new RegExp(`^\\s*(?:-\\s*)?uses:\\s*["']?${escaped}@([^\\s"'#]+)`)
    for (const text of callerText.split(/\r?\n/)) {
        const match = text.match(line)
        if (match !== null) return match[1]
    }
    return undefined
}

/**
 * Findings for a published copy against the template, compared as bytes. Each finding names the first
 * differing line, and says when the only difference is the line endings or the bytes alone (a byte
 * order mark, or sequences that are not valid UTF-8 and decode alike).
 */
export function compare(templateInput, publishedInput, names = { template: 'template', published: 'published copy' }) {
    const templateBytes = Buffer.isBuffer(templateInput) ? templateInput : Buffer.from(templateInput, 'utf8')
    const publishedBytes = Buffer.isBuffer(publishedInput) ? publishedInput : Buffer.from(publishedInput, 'utf8')
    if (templateBytes.equals(publishedBytes)) return []
    const templateText = templateBytes.toString('utf8')
    const publishedText = publishedBytes.toString('utf8')
    const where = `${names.published} differs from ${names.template}`
    const withoutBom = (text) => text.replace(/^﻿/, '')
    if (withoutBom(templateText) === withoutBom(publishedText)) return [`${where} in bytes only (a byte order mark or invalid UTF-8), not in any line`]
    const normalize = (text) => text.replace(/\r\n/g, '\n')
    if (normalize(templateText) === normalize(publishedText)) return [`${where} in line endings only (CRLF against LF)`]
    const a = normalize(templateText).split('\n')
    const b = normalize(publishedText).split('\n')
    const count = Math.max(a.length, b.length)
    const show = (line) => (line === undefined ? '(end of file)' : JSON.stringify(line))
    for (let i = 0; i < count; i++) {
        if (a[i] !== b[i]) return [`${where}, first at line ${i + 1}: template has ${show(a[i])}, published has ${show(b[i])}`]
    }
    return [where]
}

/** Control failures; empty when the comparison tells the three cases apart. */
export function controlFailures() {
    const failed = []
    const base = 'name: CI chain\non: workflow_call\njobs: {}\n'
    if (compare(base, base).length !== 0) failed.push('identical text was reported as different')
    const changed = compare(base, base.replace('jobs: {}', 'jobs: { a: {} }'))
    if (changed.length !== 1 || !/first at line 3/.test(changed[0])) failed.push('a one-line difference was not reported at its line')
    const endings = compare(base, base.replace(/\n/g, '\r\n'))
    if (endings.length !== 1 || !/line endings only/.test(endings[0])) failed.push('a line-ending difference was not reported as one')
    const shorter = compare(base, 'name: CI chain\non: workflow_call\n')
    if (shorter.length !== 1) failed.push('a truncated copy was not reported')
    if (compare(base, '\uFEFF' + base).length !== 1 || !/bytes only/.test(compare(base, '\uFEFF' + base)[0])) failed.push('a byte order mark was not reported')
    if (compare(Buffer.from([0x61, 0xff]), Buffer.from([0x61, 0xfe])).length !== 1) failed.push('differing invalid UTF-8 bytes were reported as identical')
    if (!isReleaseRef('v1.2.3') || !isReleaseRef('a'.repeat(40)) || isReleaseRef('main') || isReleaseRef('v1.2') || isReleaseRef('A'.repeat(40))) failed.push('the pin rule accepted or rejected the wrong refs')
    const pin = pinnedRef('jobs:\n  checks:\n    uses: UAMS-Web/uams-ci-workflows/.github/workflows/ci.yml@v1.2.3\n')
    if (pin !== 'v1.2.3') failed.push('the pinned ref was not read from a uses line')
    if (pinnedRef('# uses: UAMS-Web/uams-ci-workflows/.github/workflows/ci.yml@v9.9.9\n') !== undefined) failed.push('a commented uses line was read as a pin')
    if (pinnedRef('x: y # uses: UAMS-Web/uams-ci-workflows/.github/workflows/ci.yml@v9.9.9\n') !== undefined) failed.push('a trailing comment was read as a pin')
    if (pinnedRef('    uses: "UAMS-Web/uams-ci-workflows/.github/workflows/ci.yml@v1.2.3"\n') !== 'v1.2.3') failed.push('a quoted pin was misread')
    return failed
}

/** Why a repository, ref or path cannot be put in the fetch URL as given, or undefined when it can. */
export function unsafeParts({ repo, ref, path }) {
    if (!/^[\w.-]+\/[\w.-]+$/.test(repo)) return `the repository ${JSON.stringify(repo)} is not owner/name`
    if (!/^[A-Za-z0-9._-]+$/.test(ref) || ref.includes('..')) return `the ref ${JSON.stringify(ref)} is not a tag, commit or plain branch name`
    if (!/^[\w./-]+$/.test(path) || path.split('/').includes('..')) return `the path ${JSON.stringify(path)} is not a plain relative path`
    return undefined
}

/** The published bytes at a ref, or an error naming why they could not be read. */
export async function readPublished({ repo, ref, path }, fetchImpl = fetch) {
    const unsafe = unsafeParts({ repo, ref, path })
    if (unsafe !== undefined) return { error: unsafe }
    const url = `https://raw.githubusercontent.com/${repo}/${ref}/${path}`
    let response
    try {
        response = await fetchImpl(url)
    } catch (error) {
        return { error: `could not fetch ${url}: ${error.message}` }
    }
    if (!response.ok) return { error: `${url} answered ${response.status}; the ref or the path does not exist, or the repository is not public` }
    const bytes = Buffer.from(await response.arrayBuffer())
    if (bytes.toString('utf8').trim() === '') return { error: `${url} answered an empty body` }
    return { bytes, url }
}

const option = (argv, name) => {
    const index = argv.indexOf(name)
    return index === -1 ? undefined : argv[index + 1]
}

export async function main(argv, out = (line) => console.log(line), fetchImpl = fetch) {
    const broken = controlFailures()
    if (broken.length > 0) {
        out(`check-published-workflow: BROKEN INSTRUMENT, a control failed: ${broken.join('; ')}`)
        return 2
    }
    const repo = option(argv, '--repo') ?? PUBLISHED_REPO
    const path = option(argv, '--path') ?? PUBLISHED_PATH
    const templatePath = option(argv, '--template') ?? DEFAULT_TEMPLATE
    let templateBytes
    try {
        templateBytes = readFileSync(templatePath)
    } catch (error) {
        out(`check-published-workflow: cannot read the template ${templatePath}: ${error.message}`)
        return 2
    }
    let ref = option(argv, '--ref')
    if (ref === undefined) {
        const callerPath = option(argv, '--caller') ?? DEFAULT_CALLER
        try {
            ref = pinnedRef(readFileSync(callerPath, 'utf8'), repo, path)
        } catch (error) {
            out(`check-published-workflow: cannot read the caller template ${callerPath}: ${error.message}`)
            return 2
        }
        if (ref === undefined) {
            out(`check-published-workflow: ${callerPath} pins no ref of ${repo}/${path}; pass --ref`)
            return 2
        }
        if (!isReleaseRef(ref)) {
            out(`check-published-workflow: ${callerPath} pins ${ref}, which is not a release tag (vX.Y.Z) or a full commit hash; a branch moves under its callers`)
            return 2
        }
    }
    let published
    const local = option(argv, '--published-file')
    if (local !== undefined) {
        try {
            published = { bytes: readFileSync(local), url: local }
        } catch (error) {
            out(`check-published-workflow: cannot read the published file ${local}: ${error.message}`)
            return 2
        }
        if (published.bytes.toString('utf8').trim() === '') {
            out(`check-published-workflow: ${local} is empty`)
            return 2
        }
    } else {
        published = await readPublished({ repo, ref, path }, fetchImpl)
        if (published.error !== undefined) {
            out(`check-published-workflow: ${published.error}`)
            return 2
        }
    }
    out(`check-published-workflow: comparing ${templatePath} with ${repo}/${path}@${ref}`)
    const findings = compare(templateBytes, published.bytes, { template: templatePath, published: `${repo}/${path}@${ref}` })
    for (const finding of findings) out(`FAIL: ${finding}`)
    if (findings.length === 0) out('check-published-workflow: clean (the published copy is identical to the template)')
    return findings.length === 0 ? 0 : 1
}

if (process.argv[1] !== undefined && realpathSync(fileURLToPath(import.meta.url)) === realpathSync(process.argv[1])) process.exitCode = await main(process.argv.slice(2))
