---
name: php-documentation
description: >-
  PHP documentation discipline: PHPDoc coverage (every class/interface/trait/enum and each
  top-level constant, property, method, enum case), `@param` / `@var` / `@return` tag spacing and
  wrapping rules (two spaces for `@param`, single spaces for `@var` and `@return`, no bare
  `@return void`), per-action single-line `//` comments before each logical step in multi-step
  code, the "only revise docs for impacted code" rule with the 80-char wrap exception, and log
  level selection (PSR-3 levels emergency/alert/critical/error/warning/notice/info/debug, as
  Laravel's `Illuminate\Support\Facades\Log` exposes them). Activate when adding or revising
  PHPDoc, docblocks, inline comments, or log calls in any PHP, JS, TS, or Vue source.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/php-documentation/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
<!-- cspell:ignore Fieldtype postconditions -->

# PHP Documentation (PHPDoc, Inline Comments, Logging)

This skill covers everything about commenting and documenting code in this project: PHPDoc
coverage rules, tag formatting, per-action inline comments, the revision-scope rule, and how to
pick a log level.

## 1. When to touch documentation (revision scope)

For each set of code revisions, add or update comprehensive documentation **only for the code
segments directly impacted by the current revisions**. Do not revise unrelated PHPDoc, docblocks,
inline comments, code, or log messages; preserve them verbatim, even if they could be rephrased.

Only revise existing documentation for impacted methods/segments when:

1. The documentation is **incorrect** due to the code changes (fix only the incorrect parts).
2. The documentation exceeds **80 characters per line** (counting from the space after `*` or
   `//`).

For multi-step logic, follow the per-action style in §3. Add new log messages only when the
revision intent calls for it.

**Exception to the 80-char wrap rule:** `@param`, `@return`, `@var`, and similar reference tags
follow §2 below: 80 chars are measured from where the documentation text starts, and `@var`
uses single spaces.

## 2. PHPDoc coverage and tag formatting

### 2.1 Where a class-level PHPDoc is required

Every `class`, `interface`, `trait`, and `enum` (including backed enums) must have a PHPDoc
block immediately above it with at least a **summary line** explaining what the type is for or
how it fits the surrounding layer.

A `class` or `enum` **nested inside another class** is itself a top-level member of the outer
class: it needs its own class-level PHPDoc and its own members follow the same table.

**Test classes** (and, for closure-style Pest files, the file-level docblock) additionally carry
a `@command` tag (two spaces after the tag, matching `@param`) giving the exact command that runs
that file with the repository's test runner. An individual test method may carry its own
method-level `@command` with a `--filter` when it's worth pinpointing.

### 2.2 Where a member-level PHPDoc is required

| Member | Requirement |
| --- | --- |
| **Class constants** | Summary describing meaning, units, or allowed values. Add `@var` (single-space form) when the type/shape isn't obvious from the expression. |
| **Properties** (incl. `static`, `readonly`) | Summary + `@var` with description. |
| **Methods** (incl. `__construct`, magic methods, private helpers) | Summary + `@param` / `@return` / `@throws` as applicable. |
| **Enum cases** | At least a one-line summary; expand for non-obvious backing values or attributes. |

**Constructor property promotion:** documenting each promoted parameter with a `@param` line in
the constructor's PHPDoc satisfies the property requirement, **provided** the description covers
the property's role. Add or extend a property docblock only when the parameter description
doesn't cover everything.

**Exceptions:** anonymous classes (optional unless large/non-obvious); vendor/generated sources
you don't own; legacy parents that forbid a particular doc shape.

### 2.3 Tag spacing

| Tag | Format | Spaces |
| --- | --- | --- |
| `@param` | `@param  {type}  ${name}  {documentation}` | **Two** spaces after keyword, type, and name. |
| `@var` | `@var {type} {documentation}` (or with `${name}`) | **Single** spaces between every part. |
| `@return` | `@return {type} {documentation}` | **Single** space after keyword and after type. |

**No bare `@return void`:** omit `@return` entirely when the method returns `void` and there's
nothing to describe. Use `@return void <description>` only when side effects or postconditions
actually need to be documented.

### 2.4 Line length, wrapping, and continuation indentation

- Documentation text on `@param`/`@var`/`@return` may extend up to **80 characters from the
  point where the documentation begins** on that line, measured from the description's first
  character, not from the start of the docblock line.
- When the description wraps, **continuation lines align under the first character of the
  description** on the first line:
  - `@var` → 4 (`@var`) + 1 = **5 spaces** of indent for continuations (when no `$name`).
  - `@param` → 6 (`@param`) + 1 = **7 spaces** of indent for continuations.
  - `@return` → 7 (`@return`) + 1 = **8 spaces** of indent for continuations.
- Non-tag PHPDoc prose (summary lines, paragraphs) keeps the standard 80-char limit measured
  from the space after `*`.

### 2.5 Don't use inline `{@see ...}` in descriptions

Refer to symbols in plain prose inside summaries, `@param`/`@return` descriptions, or other
narrative parts: `self::siteOrigin()`, `Auth::guardNamespace()`, a class name such as
`WP_REST_Request` or `Statamic\Rules\DateFieldtype`, "delegates to parent `process()`".
Standalone `@see` tags on their own lines are fine when a formal cross-reference is needed.

### 2.6 Example

```php
/**
 * Create a new command instance with its dependencies.
 *
 * @param  XmlParser  $xmlParser  Reads the WordPress export one item at a time, so a large
 *                                export is never held in memory whole.
 * @param  SiteSelector  $siteSelector  Short description.
 * @param  ContentTypeSelector  $contentTypeSelector  Short description.
 * @param  EntryCreator  $entryCreator  Short description.
 */
public function __construct(
    XmlParser $xmlParser,
    SiteSelector $siteSelector,
    ContentTypeSelector $contentTypeSelector,
    EntryCreator $entryCreator,
) {
    // ...
}

/**
 * Queue connection name used when dispatching jobs from this worker.
 *
 * @var string Name of the Redis connection used for outbound jobs.
 */
private string $connection = 'redis';

/**
 * Resolve and return the hub payload.
 *
 * @return InertiaResponse Inertia page payload for the hub.
 */
public function show(): InertiaResponse
{
    // ...
}
```

The same rules in a WordPress plugin, with a wrapped `@param` description, a wrapped `@return`
description (8-space continuation), and a constant carrying `@var`:

```php
/**
 * Gate the entire namespace before dispatch.
 *
 * @param  mixed  $result  Pre-dispatch short-circuit value. A non-null value
 *                         means another filter has already answered the
 *                         request, and it is returned untouched.
 * @param  WP_REST_Server  $server  REST server instance. Unused; present
 *                                  because the filter signature requires it.
 * @param  WP_REST_Request<array<string, mixed>>  $request  Incoming request.
 * @return mixed Unchanged $result, or WP_Error when unauthorized.
 */
public static function guardNamespace(
    mixed $result,
    WP_REST_Server $server,
    WP_REST_Request $request,
): mixed {
    // ...
}

/**
 * Header carrying the shared secret.
 *
 * @var string Case-insensitive per RFC 9110; WP_REST_Request normalizes it.
 */
private const SECRET_HEADER = 'X-UAMSWP-Migration-Key';

/**
 * Capability required for user-authenticated access.
 *
 * @return string A network-level capability on multisite, since the API
 *                exposes drafts and raw options across the whole network.
 */
public static function capability(): string
{
    // ...
}
```

## 3. Per-action inline comments (multi-step logic)

Applies to PHP, JS, CJS, MJS, TS, TSX, JSX, and Vue script blocks (shell `#` comments when you
touch shell files in the same change set). Skip prose-only files (plain Markdown) unless
embedding a runnable snippet.

When writing or revising **multi-step logic** (initializations, conditionals with nested
blocks, loops, event handlers, data pipelines, any sequence of related statements), add a brief
**single-line comment immediately before each logical action** describing the **next** step. The
file should read like a checklist of named steps.

### Rules

1. **One comment per logical action:** placed immediately before the statement or block it
   describes (never only after the fact).
2. **Imperative, intent-focused phrasing**:
   - "Initialize X with Y"
   - "Check if X" / "Check whether X"
   - "Find the X" / "Extract the X"
   - "Update X with Y"
   - "Attempt to X" / "Build X"
   - "Add X if Y" / "Dispatch X" / "Subscribe to X"
3. **Section banners stay separate:** keep existing `// ---` banners or short block comments
   that explain **why** a region exists. Add per-action comments **inside** the region for
   **what** each step does.
4. **Conditionals/guards:** comment the **intent** in plain language ("Check if the attachment
   by URL was found" before `if ($attachmentByUrl !== null)`).
5. **Loops:** a comment before the loop may summarize the iteration goal; inside the body,
   comment non-trivial steps the same way.
6. **No redundancy:** describe purpose/outcome, not the verbatim code ("Update the URL for
   import with the attachment URL", not "Set `$urlForImport` to `$attachmentData['url']`").
7. **Single statements / trivial one-liners:** comment is optional. Use this style whenever a
   block contains two or more logical steps or nested conditionals.

### Example (PHP)

```php
// ---
// If fullSizeUrl is still the img src (e.g., short/rewrite URL), resolve to
// the attachment's canonical URL so we try the full path from the XML.
// ---

// Initialize URL for import with the full-size URL
$urlForImport = $fullSizeUrl;

// Check if the full-size URL is the same as the source URL and attachments are available
if (
    $isSameSite
    && $fullSizeUrl === $src
    && ! empty($attachments)
) {
    // Find the attachment by URL
    $attachmentByUrl = XpathHelper::findAttachmentByUrl(
        $attachments,
        $fullSizeUrl
    );

    // Check if the attachment by URL was found
    if ($attachmentByUrl !== null) {
        // Extract the attachment data
        $attachmentData = XpathHelper::extractAttachmentData(
            $attachmentByUrl
        );

        // Check if the attachment URL is not empty
        if (! empty($attachmentData['url'])) {
            // Update the URL for import with the attachment URL
            $urlForImport = $attachmentData['url'];
        }
    }
}

// Attempt to import image using the full-size or canonical URL
$imagePath = $assetImporter->importFile(
    $urlForImport,
    $site,
    $output,
    'images',
    SameSitePolicy::ALWAYS_IMPORT
);
```

### Example (JavaScript)

```javascript
// Normalize the incoming payload to a plain object
const body = typeof raw === 'string' ? JSON.parse(raw) : raw;

// Check if the request includes a usable identifier
if (body?.id != null && String(body.id).trim() !== '') {
    // Fetch the remote record for that identifier
    const record = await client.fetchRecord(body.id);

    // Check if the upstream service returned a row
    if (record != null) {
        // Merge server fields into the local draft
        mergeDraft(state.draft, record);
    }
}

// Persist the merged draft to session storage
saveDraftToSession(state.draft);
```

## 4. Logging levels

Pick the level that matches the severity and operational response. Wrong levels create alert
fatigue or hide real failures. The levels are the PSR-3 set; use them wherever the repository has
a leveled logger.

| Level | When to use | Example |
| --- | --- | --- |
| `emergency` | Critical system failure rendering it unusable. Immediate intervention to restore service. | Complete system crash, loss of a primary service. |
| `alert` | Severe condition requiring immediate action. System may still be partially functional. | Loss of a critical system database. |
| `critical` | Critical condition that may lead to system failure if not addressed. | Hard device errors, critical application failure. |
| `error` | Error condition that affects some operations. System still functions but the error must be investigated. | Application errors, failed requests, inability to access a resource. |
| `warning` | Potential issue that may lead to errors if not addressed. Monitor; not necessarily immediate. | Low disk space, deprecated API usage, exceeding resource thresholds. |
| `notice` | Normal but significant condition that may require monitoring. | Unusual but normal events; auditing/tracking. |
| `info` | Informational messages about normal operation. | Startup, user login, successful task completion. |
| `debug` | Detailed debugging info for development/testing. Typically off in production. | Variable values, function calls, execution flow. |

When adding a log, also follow §1: only revise existing log messages when directly impacted by
the current code revisions.
