#!/usr/bin/env python3
"""
Generate a GitHub Release note body per the `writing-release-notes` skill:
em-dash-title-ready, milestone lead + optional breaking-change callout, a closed
heading vocabulary, and one linked bullet per change (a `[#N]` PR link, or a
backticked short SHA for a direct commit).

One script, shared by every UAMS-Web repository that carries the skill. Nothing in
it names a repository: `--repo` is required, and the routing roots, word lists and
body sections that differ between repositories are options (see the skill).

It reads first-parent git history for a ref range and resolves each change to a
clean, bucketed bullet, taking each pull request's title, body and labels live from
the GitHub API (via `gh`), and the paths it touched from git, so it depends on
nothing but `git`, `gh`, and Python 3.

Because PR titles are enforced to house style by the `writing-pull-requests`
skill, the live titles are already clean; this tool only strips residual noise
(Conventional-Commit prefixes, `[skip ci]` litter, merge-order hints, redundant
`(#NNN)` refs), fixes acronym casing, drops `&`, and applies the Oxford comma.

Usage:
  gen_release_notes.py [options] <prev-ref> <new-ref> --repo OWNER/NAME

Example (cut v0.27.0 from the previous tag):
  python3 .claude/skills/writing-release-notes/gen_release_notes.py \\
      v0.26.0 v0.27.0 --repo UAMS-Web/<repo> \\
      --lead "One-sentence milestone theme." \\
      --breaking "update templates and re-import content after the handle renames." \\
      --breaking-item "Rename \\`foo\\` -> \\`bar\\` [#123](...)." \\
      > body.md
  gh release create v0.27.0 --title 'v0.27.0 — Theme' --notes-file body.md --prerelease --verify-tag

`--help` lists every option. The editorial ones (`--lead`, `--breaking`,
`--breaking-item`, `--exclude`, `--security-item`, `--note`, `--verification`,
`--known-gaps`, `--footer`) supply what the generator cannot infer; the routing
ones (`--tooling-path`, `--product-path`, `--security-word`,
`--maintenance-word`, `--skip`, `--api`) adapt it to a repository.
"""
import argparse
import json
import re
import subprocess
import sys


def force_utf8_stdout():
    """Emit UTF-8 regardless of the platform's active code page.

    Windows sizes stdout to the console code page (`cp1252`). It has no
    mapping for U+26A0 U+FE0F, which the breaking-change callout used to be
    prefixed with a warning glyph, so a `--breaking` run died with a UnicodeEncodeError
    *after* doing all its work, leaving a traceback in the redirect where the
    notes should be (UAMS-Web/wordpress-importer#527). The em dash the title
    format mandates is in `cp1252` and always survived, which is why a run
    without the callout looked healthy.

    That prefix was removed under the `no-emoji-in-durable-records` rule, so
    nothing this script emits is outside `cp1252` today. The forcing stays
    regardless: PR titles are interpolated into the notes verbatim and can
    carry any codepoint, so the next failure would arrive through content
    rather than through formatting.

    Reconfiguring here keeps the fix with the script instead of with every
    caller's `PYTHONIOENCODING`. A stdout that is not a real text stream (a
    test harness swapping in `io.StringIO`) has no `reconfigure` and needs
    none, since it holds `str` and never encodes.
    """
    reconfigure = getattr(sys.stdout, "reconfigure", None)
    if reconfigure is not None:
        # `errors` is passed explicitly: reconfigure() resets it to "strict" whenever
        # `encoding` is given without it, so naming it keeps the handler from changing
        # as a side effect. UTF-8 encodes every code point, so "strict" never fires.
        reconfigure(encoding="utf-8", errors="strict")


def sh(args):
    return subprocess.run(args, capture_output=True, text=True).stdout


def gh(args):
    """Run `gh` with `args` and return (returncode, stdout). Injectable for tests."""
    r = subprocess.run(["gh", *args], capture_output=True, text=True)
    return r.returncode, r.stdout


# ---- prose helpers -------------------------------------------------------

def noamp(s):
    """Replace '&' with 'and'; add an Oxford comma when it closes a comma-list."""
    def repl(m):
        return ", and " if "," in s[:m.start()] else " and "
    return re.sub(r"\s*&\s*", repl, s)


SKIPCI = re.compile(r"\s*\[\s*(?:skip[\s-]*ci|ci[\s-]*skip|no[\s-]*ci)\s*\]", re.I)


def scrub(s):
    """Strip build-directive litter like '[skip ci]' and collapse whitespace."""
    return re.sub(r"\s{2,}", " ", SKIPCI.sub("", s)).strip()


# No `rest`: it would turn "the rest of" into "the REST of" in prose. `json-ld` is
# listed before `json` so the longer key is tried first.
_ACRO = {"wordpress": "WordPress", "composer": "Composer", "json-ld": "JSON-LD", "json": "JSON",
         "cli": "CLI", "php": "PHP", "css": "CSS", "scss": "SCSS", "ci": "CI", "api": "API",
         "pcov": "PCOV", "mcp": "MCP", "statamic": "Statamic", "laravel": "Laravel",
         "antlers": "Antlers", "phpstan": "PHPStan", "larastan": "Larastan", "rector": "Rector",
         "vite": "Vite", "ssr": "SSR", "ssg": "SSG", "seo": "SEO", "acf": "ACF", "wxr": "WXR",
         "graphql": "GraphQL", "url": "URL"}
# A word joined to a larger token is part of something a reader types -- the Composer
# directive `@php`, the script `ci:local`, the package `uams-web/wordpress-importer`, the
# path `config/statamic`, the extension `.php` -- so it keeps its case, exactly as a code
# span does. Joined means preceded by a word character or `@ : / . -`, or followed by a
# word character, `-` or `/`, or by `:` or `.` with a word character straight after. A
# trailing `:` or `.` before a space is punctuation, so "the api." and "php: all" are
# still cased. `\b` alone treated those joins as word boundaries
# (UAMS-Web/uams-statamic#2589, UAMS-Web/uamswp-migration-api#318).
_ACRO_RE = re.compile(
    r"(?<![\w@:/.-])(" + "|".join(re.escape(k) for k in _ACRO) + r")(?![\w/-]|[:.]\w)", re.I
)

# A backticked span. Non-greedy so adjacent spans do not merge into one, and it matches the
# backticks too, so a replacement can put the span back byte-identical.
_CODE_SPAN_RE = re.compile(r"`[^`]*`")


def fix_acro(s):
    """Apply acronym casing to prose, leaving code spans and joined names byte-identical.

    Two guards:

    - Backticked spans are skipped whole (UAMS-Web/wordpress-importer#1110). A span is an
      identifier a reader copies, so changing its case corrupts it: `uams-web/wordpress-importer`
      became `uams-web/WordPress-importer` and `statamic/cms` became `Statamic/cms`, both real
      Composer package names, neither of which resolves.
    - A word joined to a larger token keeps its case even outside a span
      (UAMS-Web/uams-statamic#2589, UAMS-Web/uamswp-migration-api#318). Without it, the
      unquoted `wordpress-importer` in a title became `WordPress-importer`.

    This is a NARROWING, not a removal: the same substitution still applies to every
    standalone word, which is what the function is for, so a title mixing both is correct
    in one pass.
    """
    def cased(text):
        return _ACRO_RE.sub(lambda m: _ACRO.get(m.group(0).lower(), m.group(0)), text)

    out = []
    last = 0

    for span in _CODE_SPAN_RE.finditer(s):
        out.append(cased(s[last:span.start()]))
        # The span verbatim, backticks included.
        out.append(span.group(0))
        last = span.end()

    out.append(cased(s[last:]))

    return "".join(out)


def clean_title(t):
    """Normalize a raw commit subject into a house-style bullet title."""
    t = re.sub(r"^(feat|fix|perf|chore|docs|build|ci|test|style|refactor)(\([^)]*\))?:\s*", "", t)
    t = re.sub(r"\s*\((?:merge (?:after|before) #\d+)\)", "", t, flags=re.I)
    t = re.sub(r"\s*\(#\d+(?:\s*[,&–-]\s*#?\d+)*\)", "", t)
    t = fix_acro(t.strip())
    return (t[0].upper() + t[1:]) if t and t[0].islower() else t


# ---- routing (which bucket) ---------------------------------------------

# The security vocabulary. `secret` and `credential` are deliberately absent: documentation
# mentions both, and UAMS-Web/uamswp-migration-api#214, a note about a shell profile, landed
# in Security until they were removed. A repository that wants them back passes
# `--security-word`.
SEC = re.compile(
    r"\b(xss|ssrf|csrf|csp|hsts|xxe|redos|egress|nonce|impersonat\w*|sanitiz\w*|clickjack\w*|"
    r"denylist)\b", re.I)
SEC_PHRASES = ("ssl verif", "security header", "x-powered-by", "password protection",
               "internal-network", "internal network", "auth gate", "unauthenticated")

# A Conventional-Commit prefix is STRIPPED, never routed on. The title conventions in
# `writing-pull-requests` forbid these outright, so a prefix here is legacy litter -- and
# routing on it meant no title the conventions produce could ever match
# (UAMS-Web/uams-statamic#2465, UAMS-Web/wordpress-importer#1093).
CC_PREFIX = re.compile(
    r"^(?:feat|fix|docs|build|ci|test|chore|style|refactor|perf|revert)(?:\([^)]*\))?!?:\s*", re.I)

# Labels that name a bucket outright. `development` is deliberately absent: it is the default
# on code work and names no section. Labels are read from the pull request AND from the issue
# it closes, because one repository labels issues and another labels pull requests.
MAINT_LABELS = {"build", "documentation", "local-ci", "phpstan-cleanup", "test-flake"}
SEC_LABELS = {"security"}

# A change confined to these top-level paths (a directory or a file at the repository root)
# is tooling, tests or prose whatever its title says. The set is the union of the three
# repositories' lists. `tests` is in it because nothing under it ships; a repository whose
# tests are product work passes `--product-path tests`.
TOOLING_PATHS = {
    ".github", ".githooks", ".claude", ".ai", ".cursor", "scripts", "docs", "patches", "tests",
    "AGENTS.md", "CLAUDE.md", "README.md", "cspell.json", "project-words.txt",
    "composer.json", "composer.lock", "package.json", "package-lock.json", "patches.lock.json",
    "phpstan.neon.dist", "phpunit.xml", "rector.php", "rector-sweep.php", "pint.json",
    ".gitattributes", ".gitignore", ".editorconfig",
}

# The root whose added lines count as test lines for the test-dominant rule.
TEST_ROOT = "tests"

FIX_VERBS = re.compile(r"^(Fix|Resolve|Repair|Prevent|Guard|Restore|Correct|Harden|Stop|Avoid)\b")
MAINT_VERBS = re.compile(
    r"^(Migrate|Document|Adopt|Refactor|Refresh|Rework|Bump|Reformat|Consolidate|Deduplicate|"
    r"Record|Port)\b")
# `rule` is deliberately absent: it names `.claude/rules` in one repository and a validation
# rule in a Laravel one. A repository that wants it passes `--maintenance-word rule`.
MAINT_WORDS = re.compile(
    r"\btests?\b|test-lint|paratest|\bcspell\b|spell check|test hygiene|test isolation|"
    r"ci parity|local-ci|\bcoverage\b|mutation|\bmutants?\b|pcov|coverage driver|\bskill\b|worktree",
    re.I)


def _root(path):
    return path.strip().strip("/").split("/")[0]


class Routing:
    """The per-repository routing configuration, from the `--tooling-path`, `--product-path`,
    `--security-word` and `--maintenance-word` options. The defaults route a repository that
    passes none of them."""

    def __init__(self, tooling_paths=(), product_paths=(), security_words=(), maintenance_words=()):
        self.product = {_root(p) for p in product_paths}
        self.tooling = (TOOLING_PATHS | {_root(p) for p in tooling_paths}) - self.product
        self.security_words = [re.compile(r"\b" + re.escape(w) + r"\b", re.I) for w in security_words]
        self.maintenance_words = [re.compile(r"\b" + re.escape(w) + r"\b", re.I) for w in maintenance_words]

    @property
    def tests_are_tooling(self):
        return TEST_ROOT in self.tooling


DEFAULT_ROUTING = Routing()


def strip_cc_prefix(s):
    """Remove a legacy Conventional-Commit prefix so it cannot affect routing."""
    return CC_PREFIX.sub("", s.strip(), count=1)


def paths_are_tooling(paths, routing=DEFAULT_ROUTING):
    """True when every path touched sits under a tooling or prose root."""
    roots = {_root(p) for p in paths if p and p.strip()}

    return bool(roots) and roots <= routing.tooling


def touches_product_path(paths, routing=DEFAULT_ROUTING):
    """True when any path touched sits under a `--product-path` root."""
    return any(_root(p) in routing.product for p in paths if p and p.strip())


def bucket(subject, title, labels=(), paths=(), forced_sec=False, issue_type=None,
           test_lines=0, other_lines=0, routing=DEFAULT_ROUTING):
    """Route a change to one of: sec | maint | fix | new.

    A cascade, and the ORDER carries the correctness. Routing keys on signals the
    conventions actually produce -- an explicit flag, labels, touched paths, the diff's
    shape, the closing issue's type and the title's own verb. It does NOT consult a
    Conventional-Commit prefix: `writing-pull-requests` and `writing-issues` both forbid
    those on titles. Measured in UAMS-Web/wordpress-importer over `v0.17.0..v0.18.0`, 0 of
    20 subjects carried one -- the prefix branches that used to sit here could never fire,
    and every tooling change fell through to `new`.
    """
    labels = {str(l).lower() for l in labels}
    t = strip_cc_prefix(title)
    combo = strip_cc_prefix(subject) + " || " + t
    low = combo.lower()

    # 1. An explicit `--security-item`. Nothing infers a security fix whose title carries no
    #    keyword and whose paths are prose -- UAMS-Web/wordpress-importer#1080 was exactly
    #    that and had to be moved by hand, so the human judgement gets a supported input
    #    rather than an edit afterwards.
    if forced_sec:
        return "sec"

    # 2. The label, when someone applied one.
    if labels & SEC_LABELS:
        return "sec"

    # 3. The keyword match, kept as a fallback: it catches real cases that carry no label,
    #    and it is what routed security correctly before labels were used at all.
    if (SEC.search(combo) or any(p in low for p in SEC_PHRASES)
            or (("escap" in low) and re.search(r"script|antlers|json-ld|xss|html", low))
            or any(w.search(combo) for w in routing.security_words)):
        return "sec"

    # 4. A label that names tooling outright. This runs BEFORE the repair verb: a label is a
    #    deliberate human signal and the verb is a heuristic. Two maintenance changes in
    #    UAMS-Web/uams-statamic's 2026-09-14 range open with a fix verb (`Correct the ...`,
    #    `Stop two dictionary ...`) and are routed only because their label is consulted first.
    if labels & MAINT_LABELS:
        return "maint"

    # 5. A repair reads as a fix wherever its files live. This runs BEFORE the path rule
    #    deliberately: a correction to a runbook or a README is still a fix, and putting
    #    paths first would file it under tooling.
    if FIX_VERBS.match(t):
        return "fix"

    # 6. Paths confined to tooling, prose or dependency manifests. The signal that works on
    #    history: labels are mandatory going forward but were absent on all 20 pull requests
    #    in the range above.
    if paths_are_tooling(paths, routing):
        return "maint"

    # 7. Test-dominant diff: the production edit is incidental to the coverage it enables.
    #    Safe only here, below the path rule, and only while tests count as tooling. A
    #    change touching a `--product-path` is product work however test-heavy it is.
    if (routing.tests_are_tooling and test_lines > other_lines
            and not touches_product_path(paths, routing)):
        return "maint"

    # 8. The closing issue's TYPE, which beats the title heuristics below because it is set at
    #    filing and says what the work IS (UAMS-Web/wordpress-importer#1112). The verb list
    #    cannot cover every title: `writing-issues` asks for an imperative naming the outcome,
    #    so a repair is titled "Select the row" or "Normalize both representations" rather
    #    than "Fix ...".
    #
    #    **Deliberately AFTER the label and path rules, not before.** A bug fixed in a script
    #    or a skill is still tooling -- measured, placing this branch above them moved #1084
    #    and #1086 out of `Maintenance and tooling` into `What's fixed`, disagreeing with the
    #    bucketing #1093 produced for `v0.19.0` and nobody corrected. What the change is IN
    #    outranks what the change IS.
    if issue_type == "Bug":
        return "fix"

    if issue_type == "Feature":
        return "new"

    # 9. Title-verb and topic heuristics, as the last resort before the default.
    if MAINT_VERBS.match(t) or "update dependencies" in t.lower() or MAINT_WORDS.search(t):
        return "maint"
    if any(w.search(t) for w in routing.maintenance_words):
        return "maint"

    return "new"


# Changes with no changelog value -- never rendered. Only the generic ones are here; a
# repository's own noise (iteration-planning commits, audit notes) is passed as `--skip`,
# because a skip that misfires drops a bullet without a trace.
GLOBAL_SKIP = [
    re.compile(r"^Merge branch ", re.I), re.compile(r"^Merge remote-tracking", re.I),
    re.compile(r"subproject commit reference", re.I), re.compile(r"content gitlink", re.I),
    re.compile(r"^chore.*update .*submodule", re.I), re.compile(r"update subproject commit", re.I),
    re.compile(r"^Update subproject commit"),
]

# `Closes #12`, `Fixes #12`, `Resolved #12` -- GitHub's own closing keywords, which
# `writing-pull-requests` requires in the body.
_CLOSES_RE = re.compile(
    r"\b(?:close[sd]?|fix(?:e[sd])?|resolve[sd]?)\s+#(\d+)\b", re.I)


# ---- the GitHub reads ------------------------------------------------------
#
# Both readers return the same shape from `pull(num)`:
#
#     {"title": str, "body": str, "labels": [str],
#      "closing": [{"number": int, "type": str|None, "labels": [str]}]}
#
# or None when the number is not a pull request or could not be read. A failed read
# degrades to no signal rather than to a wrong one: the bullet falls back to the commit
# subject and a SHA link, and routing falls through to the title heuristics.

class RestGitHub:
    """REST, not `gh pr view`. That command is GraphQL-backed, so it fails outright in a
    Claude Code cloud session (where the credential serves only a pinned set of PR-review
    operations) and any time the GraphQL quota is exhausted -- while REST sits untouched on
    its own budget. See `.claude/rules/github-api-budget.md`.

    The trade is that the closing issue is read from the BODY's keyword rather than from the
    link GitHub maintains, so a pull request that closes an issue through the sidebar alone
    yields no issue here and falls back to the title heuristics.
    """

    def __init__(self, repo, runner=gh):
        self.repo = repo
        self.run = runner
        self._pulls = {}
        self._issues = {}

    def prime(self, nums):
        """Nothing to batch over REST; reads happen on demand."""

    def _json(self, args):
        code, out = self.run(args)
        if code != 0:
            return None
        try:
            return json.loads(out)
        except (json.JSONDecodeError, TypeError):
            return None

    def issue(self, num):
        if num not in self._issues:
            data = self._json(["api", f"repos/{self.repo}/issues/{num}", "--jq",
                               '{type: .type.name, labels: [.labels[].name]}'])
            self._issues[num] = {
                "number": num,
                "type": (data or {}).get("type") or None,
                "labels": list((data or {}).get("labels") or []),
            }
        return self._issues[num]

    def pull(self, num):
        if num in self._pulls:
            return self._pulls[num]
        data = self._json(["api", f"repos/{self.repo}/pulls/{num}", "--jq",
                           '{title, body, labels: [.labels[].name]}'])
        if not data or not data.get("title"):
            self._pulls[num] = None
            return None
        body = data.get("body") or ""
        closing = [self.issue(int(n)) for n in dict.fromkeys(_CLOSES_RE.findall(body))]
        self._pulls[num] = {"title": data["title"], "body": body,
                            "labels": list(data.get("labels") or []), "closing": closing}
        return self._pulls[num]


_PR_BATCH = 50


class GraphqlGitHub:
    """One batched query for every pull request in the range, before any bullet is built.

    It replaces N round trips with one and reads the closing issues from the link GitHub
    maintains (`closingIssuesReferences`) rather than from a body keyword, so a sidebar-only
    link is seen. It spends the GraphQL quota, which `github-api-budget` reserves for board
    work, and it is unavailable in a Claude Code cloud session, which is why it is opt-in
    (`--api graphql`) rather than the default.
    """

    def __init__(self, repo, runner=gh):
        self.repo = repo
        self.run = runner
        self._pulls = {}

    def prime(self, nums):
        nums = [n for n in dict.fromkeys(nums) if n not in self._pulls]
        if not nums:
            return
        owner, name = self.repo.split("/", 1)

        # Chunked, and the partial-data handling below is the load-bearing half. A subject's
        # `#N` is not always a pull request -- an issue reference resolves to nothing -- and
        # ONE such alias makes the whole query exit non-zero. GraphQL still returns `data`
        # with that alias null and the rest populated, so the response is parsed regardless
        # of the exit code. Gating on `returncode == 0` discarded 195 good titles over one
        # bad reference and emitted a full set of bullets with no links, which looks like a
        # complete release note.
        for start in range(0, len(nums), _PR_BATCH):
            batch = nums[start:start + _PR_BATCH]
            fields = " ".join(
                f'p{n}: pullRequest(number:{n}){{title body labels(first:20){{nodes{{name}}}} '
                f'closingIssuesReferences(first:5){{nodes{{number issueType{{name}} '
                f'labels(first:20){{nodes{{name}}}}}}}}}}'
                for n in batch)
            q = f'query {{repository(owner:"{owner}",name:"{name}"){{{fields}}}}}'
            _code, out = self.run(["api", "graphql", "-f", f"query={q}"])
            try:
                data = (json.loads(out).get("data") or {}).get("repository") or {}
            except (json.JSONDecodeError, AttributeError, TypeError):
                data = {}
            for n in batch:
                node = data.get(f"p{n}")
                if not node or not node.get("title"):
                    # Not a pull request, or unreachable: the caller falls back to the subject.
                    self._pulls[n] = None
                    continue
                closing = [
                    {"number": iss.get("number"),
                     "type": (iss.get("issueType") or {}).get("name") or None,
                     "labels": [l["name"] for l in (iss.get("labels") or {}).get("nodes", [])]}
                    for iss in (node.get("closingIssuesReferences") or {}).get("nodes", [])]
                self._pulls[n] = {
                    "title": node["title"], "body": node.get("body") or "",
                    "labels": [l["name"] for l in (node.get("labels") or {}).get("nodes", [])],
                    "closing": closing,
                }

    def pull(self, num):
        if num not in self._pulls:
            self.prime([num])
        return self._pulls.get(num)


READERS = {"rest": RestGitHub, "graphql": GraphqlGitHub}


# ---- the git reads ------------------------------------------------------

def diff_signals(sha):
    """(paths, test_lines, other_lines) for a commit -- from git, costing no API call.

    The diff is against the FIRST parent, so a merge commit yields the pull request's own
    changes. `git show` on a merge defaults to a combined diff, which lists only files that
    differ from every parent -- for a clean merge, nothing -- so the path signal was silent
    for every merge-commit pull request until this read it the first-parent way.
    """
    parents = sh(["git", "rev-list", "--parents", "-n", "1", sha]).split()
    if len(parents) > 1:
        out = sh(["git", "diff", "--numstat", parents[1], sha])
    else:
        out = sh(["git", "show", "--numstat", "--format=", sha])
    paths, test_lines, other_lines = [], 0, 0
    for line in out.splitlines():
        parts = line.split("\t")
        if len(parts) != 3:
            continue
        added, _removed, path = parts
        paths.append(path)
        n = int(added) if added.isdigit() else 0
        if _root(path) == TEST_ROOT:
            test_lines += n
        else:
            other_lines += n
    return paths, test_lines, other_lines


class LogError(Exception):
    """`git log` refused the range: a mistyped tag, a ref not fetched, or no repository."""


def read_log(prev, new):
    """First-parent (sha, subject) pairs for the range, newest first as git prints them.

    A failed `git log` raises rather than reading as an empty range. An empty range renders
    a well-formed body with no bullets and exits 0, so a mistyped tag would otherwise look
    like a release with nothing in it.
    """
    rng = new if prev in ("-", "", "root") else f"{prev}..{new}"
    r = subprocess.run(["git", "log", rng, "--first-parent", "--format=%H%x1f%s"],
                       capture_output=True, text=True)
    if r.returncode != 0:
        raise LogError(f"git log failed for {rng}: {r.stderr.strip()}")
    return [tuple(line.split("\x1f", 1)) for line in r.stdout.splitlines() if "\x1f" in line]


def subject_pull(subject):
    """The pull request a subject names: `Merge pull request #N`, else the last `#N`."""
    m = re.match(r"Merge pull request #(\d+)", subject)
    if m:
        return int(m.group(1))
    nums = re.findall(r"#(\d+)", subject)
    return int(nums[-1]) if nums else None


# ---- rendering -----------------------------------------------------------

def render(log, a, github, signals=diff_signals):
    """Render the body from a log of (sha, subject), the parsed options and a GitHub reader."""
    routing = Routing(a.tooling_path, a.product_path, a.security_word, a.maintenance_word)
    skips = GLOBAL_SKIP + [re.compile(p, re.I) for p in a.skip]

    # PRs itemized in a breaking bullet (or explicitly excluded) must not also auto-list in a bucket.
    excl = set(a.exclude) | {int(n) for item in a.breaking_item for n in re.findall(r"#(\d+)", item)}
    forced = set(a.security_item)

    # One batched query for every pull request in the range, before any bullet is built
    # (a no-op over REST).
    github.prime([n for _sha, s in log for n in [subject_pull(s.strip())] if n and n not in excl])

    buckets = {"new": [], "fix": [], "sec": [], "maint": []}
    for sha, s in log:
        s = s.strip()
        if not s or any(r.search(s) for r in skips):
            continue
        pr = subject_pull(s)
        # Before the read, so an excluded pull request costs no API call.
        if pr is not None and pr in excl:
            continue
        pull = github.pull(pr) if pr is not None else None
        if pull:
            title = pull["title"]
            link = f"[#{pr}](https://github.com/{a.repo}/pull/{pr})"
            labels = list(pull["labels"]) + [l for iss in pull["closing"] for l in iss["labels"]]
            issue_type = next((iss["type"] for iss in pull["closing"] if iss["type"]), None)
        else:
            # Not a pull request, or unreachable: the subject, linked by its short SHA.
            pr = None
            title = re.sub(r"\s*\(#\d+[^)]*\)\s*$", "", s).strip()
            link = f"[`{sha[:7]}`](https://github.com/{a.repo}/commit/{sha})"
            labels, issue_type = [], None
        disp = scrub(clean_title(title))
        if not disp:
            continue
        paths, test_lines, other_lines = signals(sha)
        b = bucket(s, title, labels, paths, forced_sec=(pr is not None and pr in forced),
                   issue_type=issue_type, test_lines=test_lines, other_lines=other_lines,
                   routing=routing)
        bullet = f"- {noamp(disp)} {link}".rstrip()
        if bullet not in buckets[b]:
            buckets[b].append(bullet)

    parts = [noamp(a.lead) if a.lead else "TODO: one-sentence milestone lead."]
    if a.breaking:
        parts += ["", noamp(f"**Breaking change**: {a.breaking}")]
    if a.note:
        parts += ["", a.note]

    def section(title, items):
        if items:
            parts.extend(["", f"## {title}", *items])

    section("Breaking changes", [f"- {noamp(b)}" for b in a.breaking_item])
    section("What's new", buckets["new"])
    section("What's fixed", buckets["fix"])
    section("Security", buckets["sec"])
    section("Maintenance and tooling", buckets["maint"])
    if a.verification:
        parts += ["", "## Verification at this tag", a.verification]
    if a.known_gaps:
        parts += ["", "## Known gaps", a.known_gaps]
    if a.footer:
        parts += ["", f"_{a.footer}_"]

    return "\n".join(parts).rstrip() + "\n"


# ---- arguments -----------------------------------------------------------

REPO_RE = re.compile(r"^[\w.-]+/[\w.-]+$")


def parse_args(argv=None):
    ap = argparse.ArgumentParser(
        description="Generate a release-note body (writing-release-notes skill).",
        epilog="Routing options adapt the generator to a repository; the skill lists each "
               "repository's invocation.")
    ap.add_argument("prev", help="previous ref/tag (use '-' for repo root)")
    ap.add_argument("new", help="new ref/tag being released")
    ap.add_argument("--repo", required=True, metavar="OWNER/NAME",
                    help="REQUIRED. The repository every bullet links into. There is no "
                         "default: a wrong one links every bullet to another repository's "
                         "pull requests without any error.")

    ed = ap.add_argument_group("editorial (what the generator cannot infer)")
    ed.add_argument("--lead", default=None, help="one-sentence milestone lead (else a TODO placeholder)")
    ed.add_argument("--breaking", default=None, help="impact/action for the breaking-change callout")
    ed.add_argument("--breaking-item", action="append", default=[], help="a '## Breaking changes' bullet (repeatable)")
    ed.add_argument("--exclude", action="append", default=[], type=int, metavar="N",
                    help="PR number to omit from the auto-buckets (already covered elsewhere; repeatable)")
    ed.add_argument("--security-item", type=int, action="append", default=[], metavar="N",
                    help="PR number to force into ## Security (repeatable). For a security fix "
                         "whose title carries no keyword and whose paths are prose, which "
                         "nothing can infer -- mirrors --breaking-item.")
    ed.add_argument("--note", default=None,
                    help="a fixed paragraph after the lead and callout (a standing pre-release note)")
    ed.add_argument("--verification", default=None,
                    help="text for a '## Verification at this tag' section (omitted when absent)")
    ed.add_argument("--known-gaps", default=None,
                    help="text for a '## Known gaps' section (omitted when absent)")
    ed.add_argument("--footer", default=None, help="trailing italic footer line (e.g. a retroactive-tag note)")

    rt = ap.add_argument_group("routing (how this repository differs)")
    rt.add_argument("--tooling-path", action="append", default=[], metavar="ROOT",
                    help="a top-level directory or file whose changes are tooling (repeatable; "
                         "extends the built-in set)")
    rt.add_argument("--product-path", action="append", default=[], metavar="ROOT",
                    help="a top-level directory or file that is never tooling, and whose "
                         "presence disables the test-dominant rule (repeatable; e.g. 'tests' "
                         "where tests are product work, 'resources' for templates)")
    rt.add_argument("--security-word", action="append", default=[], metavar="WORD",
                    help="an extra word or phrase that routes a title to ## Security (repeatable)")
    rt.add_argument("--maintenance-word", action="append", default=[], metavar="WORD",
                    help="an extra word or phrase that routes a title to ## Maintenance and tooling "
                         "(repeatable; consulted after labels, paths and issue type)")
    rt.add_argument("--skip", action="append", default=[], metavar="REGEX",
                    help="a case-insensitive pattern; a commit subject matching it is never "
                         "rendered (repeatable)")
    rt.add_argument("--api", choices=sorted(READERS), default="rest",
                    help="how pull requests are read: 'rest' (default; works on an exhausted "
                         "GraphQL budget and in a cloud session) or 'graphql' (one batched "
                         "query, sees sidebar-linked closing issues)")
    a = ap.parse_args(argv)
    if not REPO_RE.match(a.repo):
        ap.error(f"--repo must be OWNER/NAME, got {a.repo!r}")
    return a


def main(argv=None):
    # Guarantee UTF-8 on stdout before anything writes to it, including argparse's own --help.
    force_utf8_stdout()
    a = parse_args(argv)
    try:
        log = read_log(a.prev, a.new)
    except LogError as e:
        sys.stderr.write(f"gen_release_notes: {e}\n")
        sys.exit(1)
    sys.stdout.write(render(log, a, READERS[a.api](a.repo)))


if __name__ == "__main__":
    main()

# cspell:ignore codepoint
