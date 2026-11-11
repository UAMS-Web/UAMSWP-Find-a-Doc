// glyphs.mjs: the banned-glyph scanner for the no-emoji-in-durable-records rule,
// shared by the tree sweep (`check-no-emoji.mjs`, beside it), the pull request watcher
// (`local-ci/watch-github.mjs --glyphs`) and any repository script that sweeps files with
// the same rule. It lives in the writing-comments skill, which every target receives, rather
// than in local-ci, which two targets keep their own copy of and exclude.
//
// THE EXPRESSION IS READ OUT OF THE RULE, NEVER RESTATED HERE. The rule defines the
// character class once, as the `GLYPH='...'` assignment in its audit block; a second
// copy here would drift from it, and when the two disagree nobody can tell which is
// wrong.
//
// THE CONTROL ARMS RUN BEFORE ANY VERDICT. An expression that cannot match and a body
// with nothing in it print the same nothing, so both bounds of every parsed range are
// probed with a character the pattern must match, and clean ASCII prose is probed
// against a match it must not produce. `createGlyphScanner` throws when either fails.
//
// FINDINGS ARE CODE POINTS, NEVER THE CHARACTER. Echoing the glyph crashes on the
// legacy code page the rule exists to protect, and reports the crash as a short result.

import { existsSync, readFileSync } from 'node:fs'
import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

export const RULE_FILE = 'no-emoji-in-durable-records.md'

// Where the rule sits relative to a copy of this skill (`skillDir`, the directory that
// holds SKILL.md and `scripts/`), in order:
// - `.claude/skills/writing-comments/` beside `.claude/rules/` in a target, and
//   `shared/.claude/skills/writing-comments/` beside `shared/.claude/rules/` in the source;
// - `.agents/skills/writing-comments/`, the second copy the sync delivers for Codex and
//   Cursor, which has no `.agents/rules/`: the rule is the `.claude/rules/` one.
// Relative to this file, not to the working directory, so a caller finds it from
// wherever it is started.
export function ruleCandidates(skillDir) {
    return [
        join(skillDir, '..', '..', 'rules', RULE_FILE),
        join(skillDir, '..', '..', '..', '.claude', 'rules', RULE_FILE),
    ]
}

/** The first candidate that exists, else the first, so the read fails naming it. */
export function resolveRulePath(skillDir) {
    const candidates = ruleCandidates(skillDir)
    return candidates.find((path) => existsSync(path)) ?? candidates[0]
}

export const DEFAULT_RULE_PATH = resolveRulePath(join(dirname(fileURLToPath(import.meta.url)), '..'))

// Clean prose the negative arm asserts produces no match. ASCII only: anything above it
// risks being inside a banned range and inverting the arm's meaning.
const NEGATIVE_CONTROL = 'plain ASCII prose, punctuated normally: no pictographs here.'

/** The character class from the rule's one `GLYPH='...'` assignment; zero or several is an error. */
export function extractGlyphClass(ruleText, ruleName = RULE_FILE) {
    const matches = [...ruleText.matchAll(/^\s*GLYPH='([^']*)'/gm)]
    if (matches.length !== 1) {
        throw new Error(`expected exactly one GLYPH='...' assignment in ${ruleName}, found ${matches.length}. The check reads its expression from the rule; it cannot run without it.`)
    }
    return matches[0][1]
}

/**
 * The rule's Perl character class as explicit code-point ranges. Parsed rather than
 * string-substituted, so anything the class picks up beyond `\x{...}` code points and
 * ranges is an error instead of a silently mistranslated pattern.
 */
export function parseCodePointClass(source) {
    if (!source.startsWith('[') || !source.endsWith(']')) throw new Error(`expected a bracketed character class, got: ${source}`)
    const body = source.slice(1, -1)
    const token = /\\x\{([0-9A-Fa-f]{1,6})\}(?:-\\x\{([0-9A-Fa-f]{1,6})\})?/y
    const ranges = []
    let index = 0
    while (index < body.length) {
        token.lastIndex = index
        const match = token.exec(body)
        if (match === null) {
            throw new Error(`unsupported construct at offset ${index} of the rule's character class: ${body.slice(index)}. Only \\x{...} code points and \\x{...}-\\x{...} ranges are understood.`)
        }
        const lo = Number.parseInt(match[1], 16)
        const hi = match[2] === undefined ? lo : Number.parseInt(match[2], 16)
        if (hi < lo) throw new Error(`inverted range in the rule's character class: U+${match[1]} to U+${match[2]}`)
        ranges.push({ lo, hi })
        index = token.lastIndex
    }
    if (ranges.length === 0) throw new Error("the rule's character class parsed to no code points at all")
    return ranges
}

const escapeCodePoint = (codePoint) => `\\u{${codePoint.toString(16).toUpperCase()}}`

export const codePointName = (codePoint) => `U+${codePoint.toString(16).toUpperCase().padStart(4, '0')}`

export function buildPattern(ranges) {
    const body = ranges.map(({ lo, hi }) => (lo === hi ? escapeCodePoint(lo) : `${escapeCodePoint(lo)}-${escapeCodePoint(hi)}`)).join('')
    return new RegExp(`[${body}]`, 'gu')
}

/** Both bounds of every range must match, and clean prose must not. */
export function runControlArms(ranges, pattern) {
    const failures = []
    for (const { lo, hi } of ranges) {
        for (const codePoint of new Set([lo, hi])) {
            pattern.lastIndex = 0
            if (!pattern.test(String.fromCodePoint(codePoint))) failures.push(`positive arm: ${codePointName(codePoint)} is in the rule's class but the built pattern does not match it`)
        }
    }
    pattern.lastIndex = 0
    if (pattern.test(NEGATIVE_CONTROL)) failures.push('negative arm: the built pattern matches clean ASCII prose, so it matches everything')
    return failures
}

/**
 * Findings for one text, as code points with their line numbers. The whole text is
 * split, never only its first line: a body is multi-line, and a sweep that read
 * bodies a line at a time once reported zero against a true ninety-one.
 */
export function scanText(text, pattern) {
    const findings = []
    text.split(/\r?\n/).forEach((line, offset) => {
        pattern.lastIndex = 0
        const codePoints = [...line.matchAll(pattern)].map(([character]) => codePointName(character.codePointAt(0)))
        if (codePoints.length > 0) findings.push({ line: offset + 1, codePoints })
    })
    return findings
}

/**
 * A scanner built from the rule at `rulePath`, after both control arms have held.
 * Throws when the rule cannot be read or parsed, or when an arm fails, so a scanner
 * that exists is one that has been shown it can tell a glyph from clean prose.
 */
export function createGlyphScanner(rulePath = DEFAULT_RULE_PATH) {
    const ranges = parseCodePointClass(extractGlyphClass(readFileSync(rulePath, 'utf8'), rulePath))
    const pattern = buildPattern(ranges)
    const failures = runControlArms(ranges, pattern)
    if (failures.length > 0) {
        throw new Error(`control arms FAILED, refusing to report: ${failures.join('; ')}`)
    }
    const scan = (text) => scanText(text ?? '', pattern)
    scan.ranges = ranges
    return scan
}

/** One finding list as `line 3: U+26A0 U+FE0F; line 7: U+2705`, or null when clean. */
export function glyphDetail(scan, text) {
    const findings = scan(text)
    if (findings.length === 0) return null
    return findings.map(({ line, codePoints }) => `line ${line}: ${codePoints.join(' ')}`).join('; ')
}
