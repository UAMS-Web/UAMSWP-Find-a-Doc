---
name: security-audit
description: >-
  Run a multi-agent security audit of a repository's first-party code (application or package
  PHP, templates, scripts, configuration and the GitHub Actions workflows). Fans out one finder
  agent per security domain (XSS, SSTI, path traversal, SSRF, XXE/DoS, authz, injection, secrets,
  deserialization, weak crypto and disabled TLS verification, validation/mass-assignment),
  adversarially verifies every candidate against the repository's trust model
  (who-controls-the-input, where auth sits, which output is raw by default), then writes a
  severity-ranked report. After you review it, it files approved findings as GitHub issues
  following the project `writing-issues` conventions. Also holds the checklist of insecure code
  patterns every pass and every diff review searches for: unescaped output, shell execution, code
  evaluation, unsafe deserialization, committed secrets, weak encryption, disabled TLS
  verification, and Actions expression injection. Activate when the user asks to "run a security
  audit", "audit the codebase for vulnerabilities", "find security bugs", "turn Claude loose on
  security", "scan for XSS/SSRF/injection", invokes `/security-audit`, reviews a diff for
  security, or needs the security lens of an adversarial review. This is a broader, whole-repo
  complement to the diff-scoped `/security-review` command.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/security-audit/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
<!-- cspell:ignore escapeshellarg IDOR popen SSTI Subresource -->

# Security audit (multi-agent, report-first)

A repo-tailored security audit. It runs a bundled multi-agent **workflow** that finds →
adversarially verifies → triages, hands you a report to review, and only then files issues. The
verification step is the point: most generic framework "findings" are false positives (admin
surfaces sit behind auth and many flagged sinks turn out sanitized or editor-only), so every
candidate is checked against the repository's real trust boundaries before it reaches you.

## Patterns every pass checks

The workflow's domain finders sweep for these, and a `--quick` pass or a reviewer reading a diff
checks them by hand. Each entry is the form the code actually takes in PHP, in the repository's
scripts, in its templates or in the browser, so it can be searched for; whether a hit is a finding
still depends on who controls the input, per the trust model. A value read from the application's
own storage is not the same as a value from the request, and neither is the same as a value from
a pull request's title.

- **Unescaped output.** A sink is a finding when editor- or request-controlled markup can reach
  it without being sanitized first. In JavaScript: Alpine `x-html`, Vue `v-html`, and assignments
  to `.innerHTML`, `.outerHTML`, `.insertAdjacentHTML()` and `document.write()`.
  On an admin page that echoes markup directly, every value printed goes through `esc_html()`,
  `esc_attr()` or `esc_url()` for its context, or `wp_kses()` when markup is intended. An `echo`
  of a variable without one is the sink. REST responses are JSON-encoded by WordPress and are not
  an HTML sink, so a value in a payload is not this finding.
- **Third-party scripts without Subresource Integrity.** `<script src="https://…">` from another
  host with no `integrity="sha384-…"` attribute. A compromised CDN then runs script on every page
  that includes it.
- **Shell execution.** `exec()`, `shell_exec()`, `system()`, `passthru()`, `proc_open()`,
  `popen()` and backticks. Any interpolated value in a string command needs `escapeshellarg()` at
  minimum. In the repository's Node scripts: `spawnSync(cmd, args)` with an argument array passes
  arguments without a shell. Where the repository has `scripts/spawn-helpers.mjs`, the one place
  a shell is used is a Windows `.bat` or `.cmd` executable, where `needsShell()` turns it on and
  every argument goes through `winQuote()`. A new `shell: true`, `exec()` or `execSync()` with a
  built string, or a shelled call whose arguments skip `winQuote()`, is the finding.
- **Code evaluation.** `eval()`, `assert()` with a string argument on PHP below 8.0, and
  `create_function()`, which PHP 8.0 removed. Also rendering a string as a template when the
  string is not the application's own.
- **Unsafe deserialization.** `unserialize()` on data this code did not write, unless it passes
  `['allowed_classes' => false]` or an explicit class list; `json_decode()` is the safe choice for
  data. A class list that includes a class with a magic method is a finding. Symfony
  `Yaml::parse()` with the `Yaml::PARSE_OBJECT` flag is the same class: it rebuilds objects from
  `!php/object` tags.
  `maybe_unserialize()` is the same sink. WordPress decodes serialized options and meta itself in
  `get_option()`, `get_post_meta()` and their siblings, so a direct call on a request parameter, a
  header, or a file a user supplied is the finding.
- **Hardcoded secrets.** API keys, tokens, passwords and private keys as string literals in code,
  config, scripts, tests or a workflow; a real value given as a fallback where a secret is read
  from the environment or a constant; a committed `.env*` file other than `.env.example`. Check
  the value is live before rating it: a placeholder, or a fixture that authenticates nowhere, is
  informational at most.
- **Weak encryption.** `openssl_encrypt()` with an `-ecb` cipher, an empty or constant IV, or no
  authentication (no GCM tag and no keyed hash over the encrypted data). Hashing a high-entropy
  token and comparing in constant time is not encryption and is not this finding.
  If encryption is ever needed, PHP's sodium extension (`sodium_crypto_secretbox()`) is the
  default to prefer over hand-rolled OpenSSL.
- **TLS verification switched off.** `CURLOPT_SSL_VERIFYPEER => false`,
  `CURLOPT_SSL_VERIFYHOST => 0`, and stream contexts setting `verify_peer` or `verify_peer_name`
  to false. A self-signed development certificate belongs in the trust store, not in a disabled
  check.
  Also `'sslverify' => false` on `wp_remote_get()`, `wp_remote_post()` or `wp_remote_request()`.
- **GitHub Actions expression injection.** A `${{ github.event.* }}` or `${{ github.head_ref }}`
  expression inside a workflow's `run:` script or a checkout `ref:`. Issue and pull-request titles
  and bodies, branch names, commit messages and comment bodies are chosen by whoever opens them.
  Pass the value through `env:` and quote the shell variable instead. A step output is the same
  class when the step built it from such a value: `${{ steps.<id>.outputs.<name> }}` in a `run:`
  carries whatever went into it, such as file names from the pull request's diff. A one-line
  `run:` counts as much as a `run: |` block. An expression whose value the repository's own
  settings fix, such as `github.event_name`, is not attacker-chosen.

Injection into SQL or XPath, path traversal, SSRF, XXE and parser DoS, access control (including
IDOR) and mass assignment have their own domains in the workflow.

## Invocation

| Command | Scope |
| --- | --- |
| `/security-audit` | Full first-party sweep (default). |
| `/security-audit <path> [<path> …]` | Restrict to the given paths (and code they call into), e.g. `app/Tags app/Http` in a Laravel app or `includes/REST` in a WordPress plugin. |
| `/security-audit --since main` | Audit only what changed vs a ref (broader than `/security-review`). |
| `/security-audit --quick` | Skip the workflow; do a single-agent pass (cheaper, shallower). |

## Procedure

### 1. Resolve scope → build `args` for the workflow

First decide what counts as **first-party** in this repository: the directories holding its own
PHP, templates, configuration, scripts and `.github/workflows/`, and nothing under `vendor/` or
`node_modules/` unless the repository vendors its own packages there. Pass that list as
`firstParty` whenever it differs from the workflow's built-in default, which is the layout of a
Laravel app (`app/`, `routes/`, `config/`, `resources/views/`, `resources/js/`,
`.github/workflows/`).

Whether the repository uses Statamic is decided for you: when `statamic` is omitted, the workflow
reads `composer.json` and treats the repository as Statamic only when `require` names
`statamic/cms`. Without it, the workflow drops its Statamic, Antlers and Bard guidance and its
`uams-statamic` anchors, and audits against a framework-neutral trust model with generic sweep
anchors instead; its first log line says which it detected. Pass `statamic: true` or `false` only
to override that, for example when `composer.json` is not at the repository root. If the
detection cannot run, the workflow keeps the Statamic behavior, the superset of the two.

- **Default / no args** → `{ mode: 'full', firstParty: [<first-party directories>] }`.
- **Path args** → `{ mode: 'paths', files: [<those paths>] }`.
- **`--since <ref>`** → run `git diff --name-only <ref>...HEAD`, keep only first-party paths, and
  pass `{ mode: 'diff', files: [<changed first-party files>], baseRef: '<ref>' }`.
  If the diff is empty, say so and stop. (`git` takes the same command and prints forward-slash paths on both
  Windows and macOS; keep the slashes as-is and pass them through unchanged.)

### 2. Run the workflow

Invoke the bundled script (this skill's instruction to call `Workflow` is the opt-in; you do
**not** need to ask the user again):

```
Workflow({ scriptPath: ".claude/skills/security-audit/security-audit.workflow.js", args: <from step 1> })
```

It fans out 11 domain finders, runs a 2-lens adversarial verifier (sink-analysis + reachability)
on each candidate, drops anything a skeptic confidently refutes, keeps genuinely-undecidable items
as `uncertain`, then returns a triaged `{ summary, findings[], gaps[], counts }` object. Watch live
progress with `/workflows`.

In a repository that uses Statamic (detected, or `statamic: true`), the script's trust model and
the anchors each finder starts from were written for the `uams-statamic` app (Statamic on Laravel,
Antlers templates, CP auth); the finders are told the anchors are a starting point and to sweep the
scope for other instances, so in another Statamic repository they still audit the code they are
pointed at, but read the report knowing the anchors did not name that repository's files. In a
repository without Statamic (detected, or `statamic: false`) no anchor names a file: each is a
generic sweep instruction.

> `--quick` mode: skip the Workflow call. Instead read the domain list and trust-model section
> from [security-audit.workflow.js](security-audit.workflow.js) and do a single read-only pass
> yourself, producing the same report shape. Use only when the user explicitly wants it cheap.
> In a repository without `statamic/cms`, read `OTHER_CONTEXT` and each entry's `other` text, and
> skip the entries marked `statamic` only.

### 3. Write the report

Write `.claude/notes/security-audit-<today>.md`, where `<today>` is the current date from the
session context (e.g. `2026-06-12`); read it from context, don't shell out for it (`date` and
`Get-Date` differ by OS). The workflow can't stamp dates itself.

Structure:

- **Title + meta line:** date, scope/mode, and `counts` (candidates / confirmed / uncertain / refuted).
- **Executive summary:** the workflow's `summary`.
- **Findings** grouped by severity (`## Critical` → `## Info`). For each:
  `### <title>` then a bullet list: **File** (`[path:line](path#Lline)`), **CWE**, **Severity**
  (+ `(was X)` if the verifier adjusted it), **Confidence**, **Status**, **Data flow**,
  **Exploit scenario**, **Preconditions**, **Recommendation**.
- **Needs manual review:** the `uncertain` findings, with the dynamic check each one needs.
- **Coverage gaps:** the workflow's `gaps[]`.

Use clickable `[path](path#Lline)` links (per the VSCode-extension convention), not backticks, for
file references.

**Escape finding text.** Security findings routinely contain literal `<script>`, `</script>`, and
other tags in their titles, summaries, data-flow traces, and exploit payloads. Markdown renderers
pass raw HTML through, so an unescaped `<script>` in a heading opens a real script element and
swallows everything until the next `</script>`, breaking the rendered report. HTML-escape `<` → `&lt;`,
`>` → `&gt;`, and `&` → `&amp;` in every agent-supplied text field before writing it (do **not** escape
the markdown you author yourself: links, headings, bullets).

### 4. Summarize and offer to file

Print a compact severity table (title · severity · file:line · confidence) and the gaps. Then ask
which findings to file as issues; **do not file anything without explicit approval.** Offer the
natural groupings: "all confirmed", "confirmed high+", "let me pick", or "none for now".

### 5. File approved findings → GitHub issues

For each approved finding, follow the project **`writing-issues`** skill conventions exactly
(invoke it), filing into this repository:

- **Title:** imperative, fix-oriented, with inline-code markup, no trailing period
  (e.g. ``Escape `get_param` output before printing into HTML``). For a clear vuln use the bug
  archetype (symptom-first ok); for an `uncertain` item use the research-spike archetype.
- **Body:** lede (often `Spun off from the security audit on <date>.`), then `## The bug` /
  `## Root cause`, `## Reproduction` (the exploit scenario), `## Proposed fix`, and a mandatory
  `## Acceptance criteria` task-list of observable outcomes **ending in a test item** (a regression
  test that fails on the vuln and passes after the fix). Add `## References` linking the
  absolute-branch file path.
- **Labels / triage:** apply a severity label and route through the **`triage`** skill's state
  machine so it lands correctly on the project board. Severity lives in the label; the board takes
  only membership and `Status` (see [`writing-issues`](../writing-issues/SKILL.md)).
- **NEVER** add the "generated by AI during triage" disclaimer (standing project rule).

**Check for an existing issue first.** Run `writing-issues`' "Check for an existing issue first" step
before filing each finding: search by *concept*, not title, and include closed issues. For security
findings, vary the search terms across the vulnerability class / CWE (`SSRF`, `XSS`, `CWE-79`), the
affected file / symbol, and a symptom phrase. Link/skip any match instead of opening a duplicate.

## Guardrails

- **Read-only.** This audit performs static analysis only. Do not modify source, run the app, or
  execute an import to "prove" a finding. Dynamic confirmation belongs in the issue's
  reproduction steps, run deliberately by a human or a follow-up task.
- **Trust-boundary first.** Severity always reflects *who controls the input*: anonymous visitor
  vs. authenticated editor vs. super-admin/console. The workflow enforces this; preserve it in the
  report and issues. Don't inflate an editor-only stored issue into an anonymous critical.
- **Cost.** The full workflow spawns dozens of agents. For a quick look at one file, prefer
  `--quick` or a scoped path. The diff mode (`--since`) is the cheapest comprehensive option.
- **Scope.** First-party only. Third-party `vendor/*` is out of scope; for dependency CVEs use
  `composer audit` / `npm audit` instead, and mention that if the user asks about third-party
  risk.
- **Cross-platform (Windows + macOS).** This skill works identically on both: every path here and in
  the workflow uses forward slashes (valid on Windows and macOS), the `scriptPath` is repo-relative,
  and the only shell-outs are `git` and `gh` (same commands, same forward-slash output on both). The
  workflow script runs in the harness JS sandbox: no Node/OS dependency, no `Date.now()`/
  `Math.random()`, no filesystem access from the script itself. Keep paths forward-slash even on
  Windows; don't convert to backslashes and don't invoke OS-specific shell commands.
