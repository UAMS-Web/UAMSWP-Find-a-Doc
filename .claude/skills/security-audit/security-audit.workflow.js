export const meta = {
  name: 'security-audit',
  description: 'Fan out security finders across domains for the repository\'s first-party code, adversarially verify each finding against the framework trust model, then dedup and severity-rank the confirmed set.',
  phases: [
    { title: 'Find', detail: 'one agent per security domain sweeps the scope' },
    { title: 'Verify', detail: 'two adversarial lenses (sink + reachability) refute each finding' },
    { title: 'Triage', detail: 'dedup, re-rank, completeness critic' },
  ],
}

// ---------------------------------------------------------------------------
// Scope (passed by the /security-audit skill via Workflow args)
//   args.mode    : 'full' | 'paths' | 'diff'
//   args.files   : string[] of first-party paths to focus on (paths/diff modes)
//   args.baseRef : git ref the diff was taken against (diff mode, for the prose)
//   args.firstParty : string[] of first-party directories for the full sweep; the
//                     skill passes the repository's own list, and the default below
//                     is the layout of a Laravel app
//   args.statamic : true or false to say whether the repository uses Statamic. When it
//                   is omitted, one read-only agent reads composer.json and decides from
//                   whether `require` names `statamic/cms`, so a repository without
//                   Statamic skips the Statamic steps by default. True keeps the Statamic
//                   trust model, the Statamic/Antlers/Bard guidance and the
//                   `uams-statamic` anchors; false replaces them with a framework-neutral
//                   trust model and generic sweep anchors, and sends no Statamic text.
// ---------------------------------------------------------------------------
const mode = (args && args.mode) || 'full'
const STATAMIC_ARG = args && args.statamic !== undefined && args.statamic !== null
  ? !(args.statamic === false || args.statamic === 'false')
  : null
const STATAMIC = STATAMIC_ARG !== null
  ? STATAMIC_ARG
  : await detectStatamic()

async function detectStatamic() {
  const answer = await agent(
    'Read the file composer.json at the repository root with the Read tool. Answer statamic: true when its "require" object (not "require-dev") has the key "statamic/cms", and false otherwise, including when the file does not exist. Do not run commands or modify anything.',
    {
      label: 'detect:statamic',
      schema: { type: 'object', properties: { statamic: { type: 'boolean' } }, required: ['statamic'] },
    },
  )
  // Keep the Statamic trust model if detection could not run: it is the superset of the two.
  return answer && typeof answer.statamic === 'boolean' ? answer.statamic : true
}
const files = (args && args.files) || []
const baseRef = (args && args.baseRef) || 'main'

const DEFAULT_FIRST_PARTY = [
  'app/',
  'routes/',
  'config/',
  'resources/views/',
  'resources/js/',
  '.github/workflows/',
]
const FIRST_PARTY = args && Array.isArray(args.firstParty) && args.firstParty.length ? args.firstParty : DEFAULT_FIRST_PARTY

const scopeDescription =
  mode === 'diff'
    ? `Files changed on this branch vs \`${baseRef}\` (first-party only). Audit these and follow their data flow into related first-party code:\n${files.map((f) => '- ' + f).join('\n')}`
    : mode === 'paths'
      ? `Restrict the audit to these paths (and code they call into):\n${files.map((f) => '- ' + f).join('\n')}`
      : STATAMIC
        ? `Full first-party sweep. In scope:\n${FIRST_PARTY.map((f) => '- ' + f).join('\n')}\nExplicitly OUT of scope: third-party \`vendor/*\` (except \`vendor/uams-web/*\`), \`node_modules/\`, \`.claude/worktrees/\`, generated/build assets.`
        : `Full first-party sweep. In scope:\n${FIRST_PARTY.map((f) => '- ' + f).join('\n')}\nExplicitly OUT of scope: third-party \`vendor/*\`, \`node_modules/\`, \`.claude/worktrees/\`, generated/build assets.`

// ---------------------------------------------------------------------------
// Shared context: the quality lever. Every finder and verifier reasons from
// this trust model so we surface real issues and reject framework false-positives.
// ---------------------------------------------------------------------------
const STATAMIC_CONTEXT = `APP UNDER TEST: Statamic 6 (flat-file CMS on Laravel 13, PHP 8.4). Also runs \`statamic/eloquent-driver\` (so a real SQL database backs some content). Frontend templates are Antlers. The control panel (CP) uses stock Statamic auth: \`CpAuthGuard\` / \`Authorize\` / \`BootPermissions\` middleware and permission policies.

TRUST BOUNDARIES: decide WHO controls each input before you rate severity:
- ANONYMOUS WEB VISITOR: query strings, request headers, public form submissions, uploaded files on public forms, public URLs. Highest concern. An issue reachable here is the real deal.
- AUTHENTICATED CONTENT EDITOR (CP user, NOT super admin): entry/term/global field values, form configs, blueprints, uploaded assets. Can inject into Antlers-rendered content and stored data → stored-XSS-to-visitor, insider/compromised-account risk. Medium-high concern.
- SUPER ADMIN / CONSOLE / CI: the WordPress importer (admin-supplied WXR), the artisan enum generators, \`assets:sync-from-yaml\`. Reachable only by trusted operators. Lower DIRECT concern, but still audit for XXE / SSRF-to-internal-network / zip-slip / RCE that a malicious data file could pivot through; state the trust assumption explicitly rather than ignoring it.

FRAMEWORK FALSE-POSITIVE TRAPS: do NOT report these unless you prove the protection is bypassed:
- Antlers does NOT auto-escape: \`{{ value }}\` prints the value RAW (verified against vendor/statamic/cms: escaping requires the \`sanitize\`/\`escape\` modifier, or a fieldtype that augments to sanitized HTML such as Bard via BardContentSanitizer). So a plain \`{{ field }}\` that renders editor- or request-controlled data into an HTML, attribute, or \`<script>\`/JS context IS a live stored/reflected-XSS sink: \`| raw\`, triple \`{{{ }}}\`, and \`| noparse\` only suppress Antlers *parsing*, they are not the dividing line for HTML escaping. Do NOT treat a value as safe just because it prints with plain \`{{ }}\`. The genuine false-positive test is whether THAT field is sanitized (a \`sanitize\`/\`escape\` modifier, \`htmlspecialchars\`/\`e()\`, or a sanitizing fieldtype augmentation) before the sink: if it is, refute; if not, it is exploitable.
- Eloquent / the query builder parameter-bind by default. SQL injection needs \`DB::raw\`, \`whereRaw\`, \`->raw(\`, or string concatenation into a query.
- Tag PARAMETERS (\`{{ tag handle="x" }}\`) are set by TEMPLATE AUTHORS, not anonymous visitors UNLESS a param is bound to request input (e.g. \`handle="{{ get_param }}"\`). Classify path-traversal / SSTI risk by who really controls the value.
- CP routes/controllers sit behind \`CpAuthGuard\` + permission policies. "Missing validation" on a CP-only endpoint is an editor-level issue, not an anonymous one.
- KNOWN-NUANCE anchors (already checked): \`POST /!/trigger/activate\` requires CP auth (see \`routes/web.php\`); not anonymous. \`GET /dev/news-release-pdf-html/{id}\` is registered only when \`app()->environment('local')\`.

METHOD: read whole functions and trace data flow source to sink; don't judge from a single line. Use Grep/Glob to sweep the scope for OTHER instances of the same class beyond the named anchors. Work entirely through the Read / Grep / Glob tools (they behave identically on Windows and macOS, and forward-slash paths work on both): do NOT depend on OS-specific shell commands (\`grep\`, \`find\`, \`Select-String\`), since the host OS varies. READ-ONLY: do not modify files, run the app, or execute the importer.`

// The same trust model for a repository that does not use Statamic (args.statamic: false).
const OTHER_CONTEXT = `APP UNDER TEST: the repository's own first-party PHP code. Read \`composer.json\` (and, for a WordPress plugin, its main plugin file) for the framework and the versions in use, and audit against those.

TRUST BOUNDARIES: decide WHO controls each input before you rate severity:
- ANONYMOUS VISITOR / UNAUTHENTICATED CALLER: query strings, request headers, request bodies, public form submissions, uploaded files on public endpoints, public URLs. Highest concern. An issue reachable here is the real deal.
- AUTHENTICATED USER WITHOUT ADMINISTRATIVE RIGHTS: values they can store that are later rendered or processed. Stored-XSS-to-visitor, insider/compromised-account risk. Medium-high concern.
- ADMINISTRATOR / CONSOLE / CI: admin-only screens, command-line entry points, CI workflows, data files an operator supplies. Reachable only by trusted operators. Lower DIRECT concern, but still audit for XXE / SSRF-to-internal-network / zip-slip / RCE that a malicious data file could pivot through; state the trust assumption explicitly rather than ignoring it.

FRAMEWORK FALSE-POSITIVE TRAPS: do NOT report these unless you prove the protection is bypassed:
- Establish how the output layer in use escapes before calling a print raw or safe, and verify it against the installed source rather than assuming: Blade \`{{ }}\` escapes while \`{!! !!}\` does not, and a plain PHP \`echo\` escapes nothing. The false-positive test is whether THAT value is escaped or sanitized for its context before the sink.
- A query that binds its parameters is not SQL injection (Eloquent and the Laravel query builder by default, a prepared statement, \`$wpdb->prepare()\`). Injection needs a raw fragment (\`DB::raw\`, \`whereRaw\`, \`->raw(\`) or input concatenated or interpolated into the query string.
- An endpoint behind an authentication and permission check is not anonymous. "Missing validation" there is an issue at the level of the role that can reach it.

METHOD: read whole functions and trace data flow source to sink; don't judge from a single line. Use Grep/Glob to sweep the scope for OTHER instances of the same class beyond the named anchors. Work entirely through the Read / Grep / Glob tools (they behave identically on Windows and macOS, and forward-slash paths work on both): do NOT depend on OS-specific shell commands (\`grep\`, \`find\`, \`Select-String\`), since the host OS varies. READ-ONLY: do not modify files, run the app, or run any import or command that writes.`

const SHARED_CONTEXT = STATAMIC ? STATAMIC_CONTEXT : OTHER_CONTEXT

// A domain's guidance and each of its hotspots is either a string, sent in every run, or
// an object: \`statamic\` is sent only when STATAMIC is true and \`other\` only when it is
// false, and either may be absent. Order is preserved, so a Statamic run sends exactly
// the text it always did.
const pick = (x) => (typeof x === 'string' ? x : STATAMIC ? x.statamic : x.other)
const picked = (list) => list.map(pick).filter((x) => typeof x === 'string')

// ---------------------------------------------------------------------------
// Security domains. Each finder starts from concrete anchors, then sweeps.
// ---------------------------------------------------------------------------
const DOMAINS = [
  {
    key: 'xss',
    title: 'Cross-site scripting & output encoding',
    cwe: 'CWE-79 / CWE-116',
    guidance:
      'Find request/stored data that reaches an UNescaped HTML, attribute, or JS sink. Map every raw-output sink first, then trace what flows into it.',
    hotspots: [
      { statamic: 'app/Tags/GetParam.php:39: returns `request()->query()` raw; real only if a template prints it unescaped' },
      { statamic: 'app/Tags/Referrer.php / app/Tags/Ip.php: client-controlled headers (check the e()/escaping)' },
      { statamic: 'app/Tags/AllFields.php: emits an HTML table from form values (check htmlspecialchars coverage on every field)' },
      { statamic: 'app/Services/SchemaTemplates/SchemaOutputService.php: builds `<script type="application/ld+json">`; check entry data is json_encode-escaped, never string-concatenated into the script' },
      { statamic: 'resources/views/**/*.antlers.html: every `| raw`, `{{{ }}}`, `| noparse`, and `<script>`/attribute interpolation of entry/request data (grep the whole view tree)' },
      'resources/views/**/*.blade.php: every `{!! !!}` (unescaped Blade output); `{{ }}` escapes in Blade, so only `{!! !!}` and `@php echo` are raw',
      {
        statamic:
          'resources/js/** and Vue CP components: client-side HTML sinks: Alpine `x-html`, Vue `v-html`, `.innerHTML =`, `.outerHTML =`, `.insertAdjacentHTML(`, `document.write(`; trace whether the value can carry editor- or request-controlled markup',
        other:
          'first-party JavaScript: client-side HTML sinks: Alpine `x-html`, Vue `v-html`, `.innerHTML =`, `.outerHTML =`, `.insertAdjacentHTML(`, `document.write(`; trace whether the value can carry user- or request-controlled markup',
      },
      'resources/views/**: `<script src="https://…">` loading a third-party host without `integrity=` (Subresource Integrity); a compromised CDN then runs script on every page that includes it',
      { other: 'every first-party template and every PHP file that prints markup: `echo`, `print`, `<?=` and `printf` of a variable with no escaping function for its context; WordPress code escapes with `esc_html()`, `esc_attr()`, `esc_url()` or `wp_kses()`' },
    ],
  },
  {
    key: 'ssti',
    title: 'Server-side template injection & dynamic rendering',
    cwe: 'CWE-1336 / CWE-94',
    guidance:
      {
        statamic:
          'Find places where user/editor-controlled strings are parsed as Antlers/Blade or rendered into a headless-browser context.',
        other:
          'Find places where user- or request-controlled strings are parsed as a template (Blade or any other engine) or rendered into a headless-browser context.',
      },
    hotspots: [
      { statamic: 'app/Tags/GetGlobal.php: parses a global field value as Antlers when the field has `antlers: true`; check who can edit that global' },
      { statamic: 'app/Services/NewsRelease/NewsReleasePdfGenerator.php: renders an Antlers template into Browsershot (headless Chrome); attacker-controlled `file://`/internal URLs in entry content = LFI/SSRF' },
      { statamic: 'app/Providers/BardMutatorServiceProvider.php + app/Services/BardContentSanitizer.php Bard HTML mutation/sanitization; check the sanitizer is not bypassable' },
      {
        statamic:
          'grep for Antlers `Parse::`/`->parse(`, Blade::render, eval-of-template across app/ and the importer',
        other:
          'grep for `Blade::render`, `eval`-of-template and any template engine handed a non-constant string across first-party code',
      },
    ],
  },
  {
    key: 'path-traversal',
    title: 'Path traversal & arbitrary file read/write',
    cwe: 'CWE-22 / CWE-23',
    guidance:
      'Find filesystem paths built from input without canonicalization or an allowlist, plus archive extraction (zip-slip).',
    hotspots: [
      { statamic: 'app/Tags/FormData.php:52: `config(statamic.forms.forms)."/{$handle}.yaml"` from a tag param; traversal only if the handle is bound to request input: verify call sites' },
      { statamic: 'app/Imports/SpreadsheetImport.php: uploaded spreadsheet handling' },
      { statamic: 'app/Console/Commands/AssetsSyncFromYaml.php: globs/reads YAML by name' },
      { statamic: 'vendor/uams-web/wordpress-importer/src/AssetDownloader.php: writes downloaded assets to disk; check the destination path is derived safely (zip-slip / `../`)' },
      { statamic: 'app/Fieldtypes/Signature.php / app/Fieldtypes/Assets.php: stored/attached file paths' },
      { other: 'grep for filesystem calls whose path is built from input: `file_get_contents(`, `fopen(`, `file_put_contents(`, `unlink(`, `readfile(`, `include`/`require` of a variable; check for canonicalization (`realpath()` against an allowed root) or an allowlist' },
      { other: 'archive extraction (`ZipArchive::extractTo(`, `PharData`) and uploaded file names: zip-slip and `../` in an entry or file name' },
    ],
  },
  {
    key: 'ssrf',
    title: 'SSRF & unsafe outbound fetch',
    cwe: 'CWE-918',
    guidance:
      'Find outbound HTTP/file fetches whose URL is influenced by import data or input, with no scheme/host allowlist and no block on internal addresses.',
    hotspots: [
      { statamic: 'vendor/uams-web/wordpress-importer/src/AssetDownloader.php: downloads asset URLs taken from the WXR file (attacker-authored); check scheme/host validation and internal-network blocking' },
      { statamic: 'vendor/uams-web/wordpress-importer/src/**/*EnumGenerator*.php: `file_get_contents`/Http against registry URLs; check the URLs are hardcoded/allowlisted, not derived from input' },
      { statamic: 'app/Services/NewsRelease/NewsReleasePdfGenerator.php Browsershot fetches resources referenced in the rendered HTML' },
      {
        statamic:
          'grep for `Http::`, `file_get_contents(`, `Guzzle`, `curl_` with a non-constant URL across app/ and the importer',
        other:
          'grep for `Http::`, `file_get_contents(`, `Guzzle`, `curl_`, `wp_remote_get(` / `wp_remote_post(` / `wp_remote_request(` with a non-constant URL across first-party code; check for a scheme/host allowlist and a block on internal addresses',
      },
    ],
  },
  {
    key: 'xxe-dos',
    title: 'XML external entities & parser / decompression DoS',
    cwe: 'CWE-611 / CWE-776 / CWE-409',
    guidance:
      'Audit every XML/PDF/spreadsheet/archive parser for external-entity loading and for unbounded expansion (billion-laughs, zip/decompression bombs).',
    hotspots: [
      { statamic: 'vendor/uams-web/wordpress-importer/src/XmlParser.php: `simplexml_load_file` flags; confirm no LIBXML_NOENT/DTDLOAD' },
      { statamic: 'vendor/uams-web/wordpress-importer/src/XmlStreamParser.php: `XMLReader::open` with LIBXML_PARSEHUGE (entity-expansion / huge-tree DoS)' },
      { statamic: 'vendor/uams-web/wordpress-importer/src/ImportWordPress.php: reader flag combinations' },
      { statamic: 'app/Services/Assets/PdfDocumentMetadataReader.php: smalot/pdfparser on uploaded PDFs (malformed-PDF DoS)' },
      { statamic: 'app/Imports/SpreadsheetImport.php: maatwebsite/excel on uploaded sheets (zip-based formats → decompression bomb / formula concerns)' },
      { other: 'grep for `simplexml_load_string(` / `simplexml_load_file(`, `DOMDocument::loadXML(` / `::load(`, `XMLReader::open(` / `::XML(` and their `LIBXML_*` flags (NOENT and DTDLOAD enable external entities; PARSEHUGE lifts the expansion limits)' },
      { other: 'any parser of uploaded or fetched documents and archives (PDF, spreadsheet, zip): unbounded expansion, decompression bombs, malformed-input DoS' },
    ],
  },
  {
    key: 'authz',
    title: 'Authentication, authorization & access control',
    cwe: 'CWE-285 / CWE-639 / CWE-862',
    guidance:
      {
        statamic:
          'Find missing/incorrect permission checks, IDOR, privilege boundaries, and weak auth flows. Map every custom CP controller and route to its guard/policy.',
        other:
          'Find missing/incorrect permission checks, IDOR, privilege boundaries, and weak auth flows. Map every route, controller and endpoint to its authentication and authorization check.',
      },
    hotspots: [
      { statamic: 'app/Http/Controllers/CP/Collections/LocalizeEntryController.php: multi-site localize authz (the recent "validate target site" fix lives here; check for sibling gaps)' },
      { statamic: 'app/Http/Controllers/CP/**/*.php: every custom CP controller: is there a policy/permission check before the mutation?' },
      { statamic: 'app/Http/Middleware/ProtectEntry.php: entry-password gate: timing-safe compare, session handling, bypass via direct view/route' },
      { statamic: 'app/Policies/CustomUserPolicy.php: super-user edit/password rules; check for gaps' },
      { statamic: 'routes/web.php: confirm the trigger route guard is correct and the dev route stays local-only' },
      { other: 'every route or endpoint registration in scope: is there an authentication and authorization check before the handler reads or mutates data (Laravel middleware, policies and gates; WordPress `permission_callback` and `current_user_can()`)? A callback that returns true, or none at all, is anonymous' },
      { other: 'every lookup by an id taken from the request: is the record checked against what the caller may access (IDOR), including across sites or tenants?' },
    ],
  },
  {
    key: 'injection',
    title: 'SQL / command / regex / XPath injection',
    cwe: 'CWE-89 / CWE-78 / CWE-1333 / CWE-643',
    guidance:
      'Find input concatenated into SQL, shell commands, regex patterns, or XPath/CSS-selector queries.',
    hotspots: [
      {
        statamic:
          'grep for `DB::raw`, `->whereRaw`, `->orderByRaw`, `->selectRaw`, `DB::statement` with interpolation (eloquent-driver content queries, scopes in app/Scopes/*)',
        other:
          'grep for `DB::raw`, `->whereRaw`, `->orderByRaw`, `->selectRaw`, `DB::statement`, `$wpdb->query(` / `->get_results(` and any other raw SQL built with interpolation or concatenation',
      },
      'grep for `proc_open`, `exec(`, `shell_exec`, `passthru`, `system(`, backticks, Symfony `Process`, and Browsershot custom args',
      'shell-interpreted process calls: Laravel `Process::run()` / `Process::start()` given a STRING, and Symfony `Process::fromShellCommandline()`, run through a shell, so input in the string is command injection; the array form (`Process::run([\'cmd\', $arg])`) passes arguments without a shell. `escapeshellarg()` on every interpolated value is the minimum for the string form. In the repository\'s Node scripts, `spawnSync(cmd, args)` with an array is the safe form, and a Windows route that joins a string for `shell: true` must quote every element through `winQuote` (see `scripts/spawn-helpers.mjs`)',
      '.github/workflows/*.yml: `${{ github.event.* }}` / `${{ github.head_ref }}` expressions interpolated into a `run:` script or a checkout `ref:`; issue titles and bodies, PR titles, branch names, commit messages and comment bodies are set by whoever opens them. Safe form: pass the value through `env:` and quote the shell variable',
      { statamic: 'app/Rules/IdentifierRegex.php: pattern compiled from constructor (ReDoS if the pattern is attacker-influenced)' },
      { statamic: 'app/Rules/XpathSyntax.php / app/Rules/CssSelectorSyntax.php: query/selector built from the validated value' },
      { other: 'grep for `preg_match(` / `preg_replace(` with a pattern built from input (ReDoS, or `/e`-style evaluation on old PHP) and for XPath or CSS-selector queries built from input' },
    ],
  },
  {
    key: 'secrets',
    title: 'Secrets exposure & information disclosure',
    cwe: 'CWE-200 / CWE-532 / CWE-209',
    guidance:
      'Find secrets/PII leaking to responses, views, JS, or logs, and over-verbose errors or debug surfaces.',
    hotspots: [
      { statamic: 'app/Tags/AdminEmail.php:25: prints `config(mail.from.address)` to any visitor (intended GF parity? confirm and rate as info-disclosure)' },
      { statamic: 'app/Tags/GetConfig.php + config/get_config.php: the allowlist is the only thing between a template and arbitrary config; audit the allowlist patterns for over-permissiveness (e.g. leaking app key, DB creds, API tokens)' },
      { statamic: 'routes/web.php trigger handler + app/Tags/Log.php: `form_values`/messages written to logs (PII / secret leakage)' },
      'grep for `env(` outside config/, `dd(`/`dump(`/`ray(`, debugbar in non-local, and APP_DEBUG-dependent error verbosity',
      { other: 'responses, logs and error output: values written to a log or returned in an error that carry request data, credentials or personal data' },
      {
        statamic:
          'hardcoded secrets: API keys, tokens, passwords and private keys as string literals in code, config, routes, scripts, front-end JS and tests; a real value as the default of `env(\'KEY\', \'...\')` in config; a committed `.env*` other than `.env.example`. In `wordpress-importer` the migration API key is pasted at run time and held nowhere (UAMS-Web/wordpress-importer#982), so a committed value for it is a finding. Check the value is live (a placeholder or a test fixture that authenticates nowhere is info at most)',
        other:
          'hardcoded secrets: API keys, tokens, passwords and private keys as string literals in code, config, routes, scripts, front-end JS and tests; a real value as the default of `env(\'KEY\', \'...\')` in config; a committed `.env*` other than `.env.example`. Check the value is live (a placeholder or a test fixture that authenticates nowhere is info at most)',
      },
    ],
  },
  {
    key: 'deserialization',
    title: 'Unsafe deserialization & dynamic dispatch',
    cwe: 'CWE-502 / CWE-470',
    guidance:
      'Find unserialize/eval on input and variable method/property/class dispatch driven by input.',
    hotspots: [
      'grep for `unserialize(`, `eval(`, `call_user_func`, `call_user_func_array`, variable-variables `$$`, `{$...}()` method dispatch, and `__call`/`__get` magic',
      {
        statamic:
          '`unserialize()` without `[\'allowed_classes\' => false]` (or an explicit class list) on data this code did not write; prefer `json_decode()` for data. `wordpress-importer` unserializes WordPress postmeta from the export routinely, and every call there passes `allowed_classes => false`',
        other:
          '`unserialize()` without `[\'allowed_classes\' => false]` (or an explicit class list) on data this code did not write; prefer `json_decode()` for data',
      },
      'YAML parsed with object support: Symfony `Yaml::parse()` with the `Yaml::PARSE_OBJECT` flag rebuilds objects from `!php/object` tags, so it is unsafe on input this code did not write',
      { statamic: 'app/Tags/Log.php: dynamic `Log::{$level}()` (check the level allowlist holds)' },
      'queue/job payloads and cache keys built from input',
    ],
  },
  {
    key: 'crypto-transport',
    title: 'Weak cryptography & disabled transport security',
    cwe: 'CWE-327 / CWE-329 / CWE-295',
    guidance:
      'Find hand-rolled encryption with a weak mode or a missing/static IV, and outbound TLS with certificate or hostname verification switched off.',
    hotspots: [
      'grep for `openssl_encrypt(` / `openssl_decrypt(`: an `-ecb` cipher, an empty or constant IV, or no authentication (no GCM tag, no keyed hash over the encrypted data); Laravel `Crypt::encryptString()` / `encrypt()` is the default to prefer',
      {
        statamic:
          'grep for `Http::withoutVerifying()`, Guzzle `\'verify\' => false`, `CURLOPT_SSL_VERIFYPEER => false`, `CURLOPT_SSL_VERIFYHOST => 0`, and stream contexts with `verify_peer` / `verify_peer_name` => false, including `wordpress-importer`\'s `OutboundUrlGuard`, `GuardedHttpFetcher` and `GuardedRedirectWalk`; a self-signed dev certificate belongs in the trust store, not a disabled check',
        other:
          'grep for `Http::withoutVerifying()`, Guzzle `\'verify\' => false`, `CURLOPT_SSL_VERIFYPEER => false`, `CURLOPT_SSL_VERIFYHOST => 0`, and stream contexts with `verify_peer` / `verify_peer_name` => false; a self-signed dev certificate belongs in the trust store, not a disabled check',
      },
    ],
  },
  {
    key: 'validation-massassign',
    title: 'Input-validation gaps & mass assignment',
    cwe: 'CWE-20 / CWE-915',
    guidance:
      'Find request input that reaches a sink without validation, and writes that accept attacker-chosen keys/fields.',
    hotspots: [
      { statamic: 'routes/web.php: `/!/trigger/activate`: `form_values` is only checked `is_array`; it flows into the `TriggerActivated` event: trace what consumers do with arbitrary keys/values' },
      { statamic: 'app/Events/TriggerActivated.php + app/Listeners/*: what trusts the unvalidated trigger payload' },
      { statamic: 'app/Fieldtypes/*.php `process()`/`preProcess()`: fieldtypes that accept and store unvalidated submitted data (Consent, Signature, Total, FormConditionalLogic, FormEmailRouting)' },
      { statamic: 'app/Fieldtypes/Traits/FieldtypeValidationRules.php: validation wiring gaps' },
      { statamic: 'upload fieldtypes (Assets, Signature) MIME/size/type enforcement on public form submissions' },
      { other: 'every request handler in scope: does input pass validation before it reaches storage or a sink, and does any write accept attacker-chosen keys (`Model::create($request->all())`, an unguarded model, an options or meta array written whole)?' },
      { other: 'upload handling MIME, size and type enforcement on any endpoint that accepts files' },
    ],
  },
]

// ---------------------------------------------------------------------------
// Structured-output schemas
// ---------------------------------------------------------------------------
const SEVERITY = ['critical', 'high', 'medium', 'low', 'info']

const FINDINGS_SCHEMA = {
  type: 'object',
  properties: {
    findings: {
      type: 'array',
      items: {
        type: 'object',
        properties: {
          title: { type: 'string', description: 'Imperative, specific. e.g. "Escape get_param output before printing in attribute context"' },
          file: { type: 'string', description: 'Repo-relative path of the sink' },
          line: { type: 'integer', description: 'Best-effort line of the sink (0 if unknown)' },
          severity: { type: 'string', enum: SEVERITY },
          cwe: { type: 'string' },
          summary: { type: 'string', description: 'What is wrong and why it matters, 1-2 sentences' },
          dataFlow: { type: 'string', description: 'source → ... → sink trace with file:line steps' },
          exploitScenario: { type: 'string', description: 'Concrete attacker steps for a realistic actor' },
          preconditions: { type: 'string', description: 'What must be true: actor role, config, feature enabled' },
          recommendation: { type: 'string', description: 'Fix direction (no need to write the patch)' },
          confidence: { type: 'string', enum: ['high', 'medium', 'low'] },
        },
        required: ['title', 'file', 'severity', 'summary', 'dataFlow', 'exploitScenario', 'recommendation', 'confidence'],
        additionalProperties: false,
      },
    },
  },
  required: ['findings'],
  additionalProperties: false,
}

const VERDICT_SCHEMA = {
  type: 'object',
  properties: {
    status: { type: 'string', enum: ['confirmed', 'refuted', 'uncertain'] },
    adjustedSeverity: { type: 'string', enum: SEVERITY },
    confidence: { type: 'string', enum: ['high', 'medium', 'low'] },
    sinkAnalysis: { type: 'string', description: 'Does the tainted data reach a dangerous sink un-neutralized? Cite the code you read.' },
    reachability: { type: 'string', description: 'Exactly who can trigger this and under what preconditions.' },
    falsePositiveReason: { type: 'string', description: 'If refuted, the specific protection or trust boundary that defeats it.' },
    notes: { type: 'string' },
  },
  required: ['status', 'adjustedSeverity', 'confidence', 'sinkAnalysis', 'reachability'],
  additionalProperties: false,
}

const REPORT_SCHEMA = {
  type: 'object',
  properties: {
    summary: { type: 'string', description: '2-4 sentence executive summary' },
    findings: {
      type: 'array',
      items: {
        type: 'object',
        properties: {
          title: { type: 'string' },
          severity: { type: 'string', enum: SEVERITY },
          status: { type: 'string', enum: ['confirmed', 'uncertain'] },
          domain: { type: 'string' },
          file: { type: 'string' },
          line: { type: 'integer' },
          cwe: { type: 'string' },
          summary: { type: 'string' },
          dataFlow: { type: 'string' },
          exploitScenario: { type: 'string' },
          preconditions: { type: 'string' },
          recommendation: { type: 'string' },
          confidence: { type: 'string', enum: ['high', 'medium', 'low'] },
        },
        required: ['title', 'severity', 'status', 'file', 'summary', 'recommendation'],
        additionalProperties: false,
      },
    },
    gaps: { type: 'array', items: { type: 'string' }, description: 'Surfaces under-covered or needing manual/dynamic testing' },
  },
  required: ['summary', 'findings', 'gaps'],
  additionalProperties: false,
}

// ---------------------------------------------------------------------------
// Prompt builders
// ---------------------------------------------------------------------------
function findPrompt(d) {
  return `You are a senior application security engineer performing a STATIC code audit.

${SHARED_CONTEXT}

SCOPE:
${scopeDescription}

YOUR DOMAIN: ${d.title} (${d.cwe})
${pick(d.guidance)}

ANCHORS to start from (verify each: some are deliberately defended non-issues; also look BEYOND them):
${picked(d.hotspots).map((h) => '- ' + h).join('\n')}

TASK:
1. Read each anchor's full function and trace data flow from an attacker-influenced source to the sink.
2. Sweep the scope with Grep/Glob for OTHER instances of this vulnerability class: anchors are a starting point, not the boundary.
3. Report ONLY findings where you can name (a) a concrete source, (b) a concrete dangerous sink, and (c) a realistic actor from the trust model who reaches it. Rate severity by impact AND trust boundary. Set confidence honestly. Do NOT pad with theoretical or already-defended cases: an empty findings array is a perfectly good answer for a clean domain.

Return via the StructuredOutput schema.`
}

const LENSES = [
  {
    key: 'sink',
    instruction: {
      statamic:
        'SINK ANALYSIS. Open the cited file(s) and determine whether the tainted input actually reaches a dangerous sink UN-neutralized. Account for Antlers being RAW-by-default (a plain `{{ field }}` does NOT escape: only a `sanitize`/`escape` modifier, `htmlspecialchars`/`e()`, or a sanitizing fieldtype augmentation does), Laravel query binding, and upstream validation. If the value is genuinely escaped, bound, or sanitized before the sink, set status=refuted and name the protection; if it only prints through plain `{{ }}` into an HTML/attribute/JS context, that is NOT a protection.',
      other:
        'SINK ANALYSIS. Open the cited file(s) and determine whether the tainted input actually reaches a dangerous sink UN-neutralized. Account for how the output layer in use really escapes (Blade `{{ }}` escapes, `{!! !!}` and a plain PHP `echo` do not; an escaping function must match the context), query parameter binding, and upstream validation. If the value is genuinely escaped, bound, or sanitized before the sink, set status=refuted and name the protection.',
    },
  },
  {
    key: 'reach',
    instruction: {
      statamic:
        'REACHABILITY ANALYSIS. Determine exactly who can trigger this and under what preconditions: anonymous visitor, authenticated editor, super admin, or console/CI only. Check route middleware, policies, gates, and where the Tag/controller is actually used in templates or the CP. If only a trusted operator can reach it by design, set status=refuted or downgrade adjustedSeverity; if a lower-privileged or anonymous actor reaches it, confirm reachability.',
      other:
        'REACHABILITY ANALYSIS. Determine exactly who can trigger this and under what preconditions: anonymous visitor, authenticated user, administrator, or console/CI only. Check route middleware, policies, gates, capability checks and permission callbacks, and where the handler is actually registered and called. If only a trusted operator can reach it by design, set status=refuted or downgrade adjustedSeverity; if a lower-privileged or anonymous actor reaches it, confirm reachability.',
    },
  },
]

function verifyPrompt(f, d, lens) {
  return `You are an ADVERSARIAL security reviewer. Your job is to REFUTE the finding below: assume it is WRONG until the actual code proves otherwise. Default to skepticism; a plausible-sounding finding that the framework already defends is a false positive and must be refuted.

${SHARED_CONTEXT}

FINDING UNDER REVIEW (domain: ${d.title}):
${JSON.stringify(f, null, 2)}

YOUR LENS: ${lens.key}:
${pick(lens.instruction)}

Open the cited file(s) and any callers yourself; do NOT trust the finding's claims. Then return a verdict:
- status=refuted: the data is neutralized before the sink, the sink is not actually dangerous, or no realistic actor reaches it (give the specific reason in falsePositiveReason).
- status=confirmed: the vulnerability is real and exploitable essentially as described.
- status=uncertain: genuinely undecidable by static analysis alone (say what dynamic check would settle it).
Set adjustedSeverity to the REAL impact given the trust boundary (an editor-only stored issue is not an anonymous critical RCE). Return via the schema.`
}

function synthPrompt(reviewed) {
  return `You are the security lead compiling the FINAL audit report for ${STATAMIC ? 'a Statamic 6 / Laravel 13 / PHP 8.4 app' : "this repository's first-party PHP code"}.

${SHARED_CONTEXT}

ADVERSARIALLY-VERIFIED FINDINGS (confirmed + uncertain only; refuted ones were already dropped):
${JSON.stringify(reviewed, null, 2)}

TASKS:
1. DEDUPLICATE: merge findings with the same root cause or same file:line into one; keep the clearest description and the union of their evidence.
2. RE-RANK severity holistically and consistently (impact x exploitability x trust boundary, CVSS-style judgment). An anonymous-reachable issue outranks an equivalent editor-only one.
3. Ensure each final finding carries: title, severity, status (confirmed|uncertain), domain, file, line, cwe, summary, dataFlow, exploitScenario, preconditions, recommendation, confidence.
4. COMPLETENESS CRITIC: in gaps[], list surfaces/domains that look under-covered or that only a manual or dynamic test can settle.
5. Write a 2-4 sentence executive summary.

Order findings severity-first (critical → info). Return via the schema.`
}

// ---------------------------------------------------------------------------
// Verdict consensus across the two lenses
// ---------------------------------------------------------------------------
function consensus(verdicts) {
  const v = verdicts.filter(Boolean)
  if (!v.length) return { status: 'uncertain', adjustedSeverity: 'low', confidence: 'low' }
  // A confident refutation from either lens kills the finding.
  const hardRefute = v.find((x) => x.status === 'refuted' && x.confidence !== 'low')
  if (hardRefute) return hardRefute
  // Otherwise prefer a confirmation; pick the highest-severity confirming verdict.
  const confirmed = v.filter((x) => x.status === 'confirmed')
  if (confirmed.length) {
    return confirmed.sort((a, b) => SEVERITY.indexOf(a.adjustedSeverity) - SEVERITY.indexOf(b.adjustedSeverity))[0]
  }
  // No confirm, no hard refute → uncertain (surface for manual review, don't drop).
  const softRefute = v.find((x) => x.status === 'refuted')
  return softRefute ? { ...softRefute, status: 'uncertain' } : v[0]
}

function slim(f) {
  return {
    title: f.title,
    domain: f.domain,
    file: f.file,
    line: f.line || 0,
    cwe: f.cwe || '',
    severity: f.verdict ? f.verdict.adjustedSeverity : f.severity,
    status: f.verdict ? f.verdict.status : 'confirmed',
    summary: f.summary,
    dataFlow: f.dataFlow,
    exploitScenario: f.exploitScenario,
    preconditions: f.preconditions || '',
    recommendation: f.recommendation,
    confidence: f.verdict ? f.verdict.confidence : f.confidence,
    sinkAnalysis: f.verdict ? f.verdict.sinkAnalysis : '',
    reachability: f.verdict ? f.verdict.reachability : '',
  }
}

// ---------------------------------------------------------------------------
// Run: pipeline find → verify per domain (no barrier), then a single triage pass.
// ---------------------------------------------------------------------------
log(`Security audit: mode=${mode}, ${DOMAINS.length} domains, 2-lens adversarial verification${STATAMIC ? '' : ', generic trust model'}${STATAMIC_ARG === null ? ` (Statamic ${STATAMIC ? 'detected' : 'not detected'} in composer.json)` : ''}`)

const perDomain = await pipeline(
  DOMAINS,
  (d) => agent(findPrompt(d), { label: `find:${d.key}`, phase: 'Find', schema: FINDINGS_SCHEMA }),
  (review, d) => {
    const findings = review && review.findings ? review.findings : []
    if (!findings.length) return []
    log(`${d.key}: ${findings.length} candidate(s) → verifying`)
    return parallel(
      findings.map((f, i) => () =>
        parallel(
          LENSES.map((lens) => () =>
            agent(verifyPrompt(f, d, lens), { label: `verify:${d.key}:${i}:${lens.key}`, phase: 'Verify', schema: VERDICT_SCHEMA }),
          ),
        ).then((verdicts) => ({ ...f, domain: d.key, verdict: consensus(verdicts) })),
      ),
    )
  },
)

const all = perDomain.flat().filter(Boolean)
const confirmed = all.filter((f) => f.verdict.status === 'confirmed')
const uncertain = all.filter((f) => f.verdict.status === 'uncertain')
const refutedCount = all.filter((f) => f.verdict.status === 'refuted').length

log(`Verified: ${confirmed.length} confirmed, ${uncertain.length} uncertain, ${refutedCount} refuted`)

const reviewed = [...confirmed, ...uncertain].map(slim)
const counts = { candidates: all.length, confirmed: confirmed.length, uncertain: uncertain.length, refuted: refutedCount }

// Nothing survived verification: return a clean bill without spending a triage agent.
if (!reviewed.length) {
  return {
    summary: 'No findings survived adversarial verification across the audited scope.',
    findings: [],
    gaps: ['Static-only pass; dynamic testing of auth flows and file uploads is recommended as a follow-up.'],
    counts,
    mode,
  }
}

phase('Triage')
const report = await agent(synthPrompt(reviewed), { label: 'triage:synthesize', phase: 'Triage', schema: REPORT_SCHEMA })

// Defensive fallback if the triage agent dies on a terminal error.
if (!report) {
  return {
    summary: 'Triage agent unavailable; returning verified findings without synthesis.',
    findings: reviewed.map((f) => ({ ...f, status: f.status === 'uncertain' ? 'uncertain' : 'confirmed' })),
    gaps: ['Triage/dedup pass did not run: review findings for duplicates manually.'],
    counts,
    mode,
  }
}

return { ...report, counts, mode }

// cspell:ignore Browsershot bypassable creds debugbar dedup DTDLOAD escapeshellarg exploitability Fieldtype fieldtype Fieldtypes
// cspell:ignore fieldtypes fopen hotspots htmlspecialchars IDOR kses maatwebsite massassign NOENT noparse PARSEHUGE pdfparser
// cspell:ignore preg simplexml smalot SSTI ssti Subresource unserializes unvalidated wpdb
