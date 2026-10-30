---
name: php-coding-standards
description: >-
  PHP coding standards shared across the UAMS-Web PHP repositories: the repository's PHP floor
  as the gate on every version-dependent construct, strict types where the repository enables
  them, full type hints with PHPDoc that agrees with the signature, laundering untyped framework
  input through typed accessors, call and signature formatting (2+ parameters split), PSR-12
  control flow and early returns, arrow functions and first-class callables, string building
  (`sprintf()`, interpolation, concatenation, heredocs), imports (never an inline FQCN, ordered
  groups, leading-backslash native functions), constructors and `readonly`, typed class
  constants, contract vs concrete type hints, avoiding "magic", SRP, DRY, and `unset()` /
  `gc_collect_cycles()` discipline in long-running loops. Carries the Statamic facade/contract
  patterns and the WordPress overrides (class-name resolution inside a namespace, hook callbacks
  matched by identity, `apply_filters()` returning untrusted `mixed`) for the repositories they
  apply to. Activate whenever writing or editing first-party PHP (`app/`, `src/`, `includes/`,
  `config/`, `tests/`, a plugin's main file, `vendor/uams-web/*/src/`), including controllers,
  services, console commands, REST controllers, importers, closures/callables, conditionals, and
  any refactor that touches method signatures, properties, imports, or constants.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/php-coding-standards/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): php.
-->
<!-- cspell:ignore Configurator FQCN FQCNs heredocs Larastan ltrim preg -->

# PHP Coding Standards

Conventions for first-party PHP, so new and refactored code matches what is already there and
passes the repository's quality gate: Pint's `laravel` preset (the organization-wide formatter,
WordPress code included), static analysis (Larastan in Laravel and Statamic applications, PHPStan
everywhere else), and Rector where the repository runs it. The `code-quality` skill covers
installing, configuring and running those tools; this skill covers what to write so they pass.
Run `vendor/bin/pint --dirty` before finalizing PHP changes; it enforces several of these rules
mechanically.

Most of what follows is ordinary modern PHP. Three things vary by repository: the PHP version
floor, the framework (Laravel and Statamic, or WordPress), and which tools run. Where a rule
depends on the PHP version it says "where the repository's PHP constraint allows"; read that
constraint from `composer.json` (and, for a WordPress plugin, the `Requires PHP` header) rather
than from the PHP installed on the machine. The sections marked **WordPress overrides** are the
places where the sensible general rule is actively wrong in a WordPress plugin, and following it
produces code that passes every check and breaks at runtime.

## 1. The PHP floor gates every version-dependent construct

Code must parse and run on the lowest PHP version the repository supports, not on the version the
developer has installed. A developer's local PHP is often newer than production, so a green local
run proves nothing about a construct the floor forbids. Where Rector runs it is configured to the
repository's supported version so it never rewrites code into syntax that version cannot parse,
and PHPStan is told the same version range; do not remove those settings.

Constructs this skill mentions, by the version that introduced them: typed properties (7.4);
constructor property promotion, union types, `match`, `mixed` (8.0); enums, `readonly`
properties, first-class callable syntax (8.1); `readonly` classes (8.2); typed class constants,
`#[\Override]`, `json_validate()`, dynamic class constant fetch (8.3); asymmetric visibility,
property hooks (8.4). Use each only where the repository's PHP constraint allows.

The WordPress plugins span a wide range of floors: `uamswp-migration-api` requires `^8.2`, while
`uamswp-mcp` (`>=7.4`) and `uamswp-restrict-user-enumeration` (`>=7.0`) still support PHP 7.x.
Check the plugin header's `Requires PHP` and `composer.json` before using anything above 7.4, and
treat a typed class constant, an enum, or a first-class callable as unavailable until you have.

## 2. File preamble

Where the repository's `pint.json` enables Pint's `declare_strict_types` rule (`uams-statamic`
and `uamswp-migration-api` do), every first-party PHP file opens the same way: the opening tag, a
file-level docblock where the repository uses them, `declare(strict_types=1);`, then the
namespace.

```php
<?php

/**
 * Authentication boundary.
 */

declare(strict_types=1);

namespace UAMSWP\MigrationAPI;
```

Where the repository does not enable that rule (`wordpress-importer` runs the `laravel` preset
with no `pint.json`), do not introduce the declaration one file at a time; enabling it is a
repository decision made once in `pint.json`, which then adds it everywhere.

The plugin's main file is the exception: it carries the WordPress plugin header comment and, where
the plugin has one, the runtime PHP version guard that turns an unsupported PHP into an admin
notice instead of a fatal error.

## 3. Type hints (always required where the parent allows)

- **Parameters**: type hint every parameter when not blocked by a parent signature.
- **Return types**: always declare, including `: void`.
- **Properties**: type all class/trait properties (PHP 7.4+).
- **Nullable**: use `?Type`; **union** types where the value genuinely varies.
- **PHPDoc must agree with the signature.** If `@param Blueprint $blueprint` is documented, put
  `Blueprint $blueprint` on the parameter and import the class (see §8). At PHPStan `level: max`
  a disagreement is an error, not a style note.
- **Generic array shapes belong in PHPDoc**, since PHP cannot express them: `@return
  list<string>`, `@var array<string, mixed>|null`.
- **Overrides**: check the parent signature first. You may widen (add type hints the parent omits)
  and use covariant return types, but never strip or narrow the parent's contract.
- When the parent forbids a type hint, still add a complete PHPDoc `@param` with description.

```php
/**
 * Make a localization for the given site.
 *
 * @param  string  $site  The site handle.
 * @param  bool  $isDescendantLocalization  Whether this is a descendant localization.
 * @return Entry The localized entry.
 */
public function makeLocalization(
    string $site,
    bool $isDescendantLocalization = false
): Entry {
    // ...
}
```

### Untyped input at the framework boundary

A framework hands you `mixed` constantly: an option store, a filter result, a request parameter.
**Do not cast it and move on.** Launder it through a typed accessor that states the
untrustworthiness in its signature:

```php
public static function int(string $key, int $default): int
```

That fixes the class of problem rather than each instance of it.

## 4. Call and signature formatting (2+ parameters → split it)

When a **definition** takes 2 or more parameters, split it: one parameter per line, indented one
level (4 spaces), with the closing `)`, the return type, and the opening `{` together on one line:
`): Entry {`.

```php
public static function guardNamespace(
    mixed $result,
    WP_REST_Server $server,
    WP_REST_Request $request,
): mixed {
```

Multi-line **calls** follow the same shape, with the closing `)` on its own line aligned with the
method/function name. This applies inside `if` / `elseif` conditions too: split the inner call
from the conditional parens.

```php
$this->siteConfigurator->enableForSite(
    $site,
    $output,
    'people'
);

if (
    preg_match(
        '/^\'([^\']+)\'$/',
        $part,
        $matches
    )
) {
    // ...
}
```

Short calls that read fine inline (`__('…', 'uamswp-migration-api')`, `$entry->get('field',
[])`, `add_action('rest_api_init', …)`) stay inline; the rule bites on definitions and on calls
long enough to wrap. Pint's `method_argument_space: ensure_fully_multiline` normalizes a split
argument list once you have broken the first line, so `vendor/bin/pint --dirty` finishes the job.

## 5. Control flow (PSR-12 § 5.1)

`} elseif {` / `} else {` on the same line as the preceding closing brace; no blank line. Always
use curly braces, even for single-line bodies.

```php
if ($foo == 'bar') {
    // ...
} elseif ($foo == 'baz') {
    // ...
} else {
    // ...
}
```

Prefer an early return to a nested conditional. Where Rector's `earlyReturn` set is on it rewrites
the nested form, so write the early return directly:

```php
if (! str_starts_with(ltrim($request->get_route(), '/'), REST_NAMESPACE)) {
    return $result;
}
```

Note the space after `!`, which Pint's `laravel` preset enforces.

## 6. Arrow functions vs anonymous functions vs first-class callables

- **Prefer `fn`** for single-expression closures with no scope mutation and no complex `use`
  clause.
- **Keep `function (...)`** when you need multiple statements, scope mutation, or when a `use`
  clause materially improves clarity.
- **Prefer first-class callable syntax `$obj->method(...)`** (or `Class::method(...)`), where the
  repository's PHP constraint allows (8.1+), over both `Closure::fromCallable([$obj, 'method'])`
  and `fn ($x) => $obj->method($x)` when the closure is a pure pass-through to a single named
  method. Where Rector runs, `ArrayToFirstClassCallableRector` rewrites the array-callable form to
  this, so write it directly.

```php
// Preferred (Rector-enforced): forwards exactly to the method
$service->handle(...);

// Avoid: redundant wrappers with identical behavior
Closure::fromCallable([$service, 'handle']);
fn ($entry) => $service->handle($entry);
```

Keep an `fn`/`function` when the body does **more** than a straight pass-through: nested-data
reads (`$entry->get('field', [])`), DI lookups (`$this->app->make(...)`), branching, or argument
transforms.

### WordPress overrides: hook callbacks are matched by identity

**Never convert a hook callback to a first-class callable.** WordPress stores the callback and
later matches it *by identity* for `remove_action()` / `remove_filter()` / `has_action()`. A
first-class callable builds a fresh `Closure` on every evaluation, so the handle used to register
can never equal the handle used to remove, and hook removal silently stops working, with nothing
wrong to see at the call site.

```php
// Correct: a stable, matchable handle
add_filter('rest_pre_dispatch', [Site::class, 'switchForRequest'], 10, 3);
remove_filter('rest_pre_dispatch', [Site::class, 'switchForRequest'], 10);

// Wrong: registers, but can never be removed
add_filter('rest_pre_dispatch', Site::switchForRequest(...), 10, 3);
```

Where Rector runs in a WordPress plugin, `ArrayToFirstClassCallableRector` must not be in the
gating profile, or it will rewrite every hook registration. Elsewhere (a closure that is a pure
pass-through and is never registered as a hook), the first-class form is still preferred.

## 7. Building strings: `sprintf()`, interpolation, concatenation, heredocs

This is guidance, not a mandate. Pick the form that reads best for the string at hand. No tool
enforces any form, so an interpolated string and a `sprintf()` call both pass the gate: Rector's
`EncapsedStringsToSprintfRector` once rewrote interpolation to `sprintf()`, but Rector 2.6.1 has
deprecated it, left it out of the `codingStyle` set that `rector.php` enables, and throws if it is
registered (verified in `uams-statamic`).

- **`sprintf()`** when the string needs a format specifier (`%d`), a cast, or several
  placeholders. A literal `\n` lifts to a trailing `PHP_EOL` argument, because a single-quoted
  format can't carry an escape.

```php
$label = sprintf('%d of %d', $current, $total);
return sprintf('prefix_%s_%s', $handle, $hash);
```

- **Double-quoted interpolation** for a simple variable. `.`-concatenation (no surrounding spaces,
  per Pint's `concat_space`) is equally fine.

```php
$msg = "Output file: {$outputPath}";
$key = "user:{$id}:session";
$dir = dirname(__DIR__).'/';
```

- **A heredoc** for generated source, such as an enum generator.

```php
$source = <<<PHP
    enum {$enumName}: string
    {
    {$cases}
    }
    PHP;
```

Existing strings stay as they are: don't convert one form to another as a drive-by change.
`implode()` is still the call for joining a list.

## 8. Imports: FQCN + ordered groups

- **Never** use a fully-qualified class name inline (e.g. `\App\Services\WordPressImport\
  Utilities\ImageProcessor::foo(...)`, or `\WP_Error` in a signature or a function body). Always
  add a `use` statement.
- Pint enforces order via `ordered_imports` → `alpha` algorithm + `['class', 'function', 'const']`
  group order, as the repository's `pint.json` configures it.
- One import per line, alphabetical inside each group.
- Namespaced constants the code defines itself are imported explicitly, with `use const`.
- Use aliases to disambiguate name collisions; never two imports resolving to the same short
  name.
- Where Rector runs, `withImportNames(importShortClasses: false, removeUnusedImports: false)`
  promotes inline FQCNs to `use` statements but deliberately leaves short global class names
  alone, and leaves unused-import removal to Pint, whose `no_unused_imports` is `{@see}`-aware.
  Rector will not import a global class for you; write the import.

```php
use App\Services\WordPressImport\Assets\AssetImporter;
use App\Services\WordPressImport\Utilities\FileProcessor;
use App\Services\WordPressImport\Utilities\ImageProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Statamic\Entries\Entry as StatamicEntry;
use Statamic\Facades\Entry;
use function Laravel\Prompts\confirm;
use const UAMSWP\MigrationAPI\REST_NAMESPACE;
```

### WordPress overrides: the namespace rules that bite

Inside a plugin's namespace, PHP resolves the three symbol kinds differently, and only one of them
is forgiving:

| Symbol | Unqualified reference | Consequence |
| --- | --- | --- |
| **Function** | falls back to global | `apply_filters()` works with no import |
| **Constant** | falls back to global | `ABSPATH` works with no import |
| **Class** | **does not fall back** | `new WP_Error(…)` resolves to `<Namespace>\WP_Error` and **is a fatal error** |

So every WordPress class must be imported:

```php
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
```

Never write a leading-backslash fully-qualified class name (`\WP_Error`) inline; add the import.
Rector's `importShortClasses: false` means it will not do this for you.

### Leading-backslash native function calls

Call **compiler-optimized native functions in the global namespace** with a leading `\`:
`\is_array(...)`, `\is_object(...)`, `\is_string(...)`, `\count(...)`, `\in_array(...)`,
`\strlen(...)`, `\defined(...)`, `\assert(...)`. Inside a namespace an unqualified call costs a
global-scope fallback lookup; the leading `\` lets the engine bind it directly, and the IDE flags
the bare form with hint **PHP6616** ("Special function … should be called in global namespace to
allow compiler optimization").

- **Pint does *not* add these for you:** there is no `native_function_invocation` fixer in the
  Pint configuration, so `pint --dirty` passes either way. Apply the `\` by hand as you write, or
  clear the PHP6616 hints after.
- Only the **compiler-optimized subset** takes the prefix. Everything else stays bare:
  `sprintf()`, `method_exists()`, `json_decode()`, `file_get_contents()`, `file_put_contents()`,
  `array_values()`, `array_keys()`, `array_diff()`, `array_filter()`, `array_merge()`,
  `substr()`, `str_starts_with()`, `is_numeric()`, `is_readable()`, `basename()`, `dirname()`,
  `mkdir()`, `is_dir()`, `hash_equals()`, `version_compare()`, and framework helpers such as
  `storage_path()` / `config()`. When unsure, trust the PHP6616 hint; it fires only on the
  functions that want the prefix.

## 9. Constructors, classes, `readonly`

- Use **constructor property promotion** where the repository's PHP constraint allows (8.0+):
  `public function __construct(public GitHub $github) { }`.
- No empty zero-parameter `__construct()`, unless it's `private` (factory pattern).
- `readonly` (properties 8.1+, classes 8.2+) is worth using where the constraint allows, but
  promoting existing code to `readonly` is a semantic change, not a style one: it interacts badly
  with code that mutates after construction, with cloning, and with mocking in tests. Apply it
  deliberately, with the runtime consequences reviewed, rather than mechanically. Where Rector
  runs, `ReadOnlyClassRector` and `ReadOnlyPropertyRector` are sweep-only for that reason.

## 10. Typed class constants (PHP 8.3+)

Where the repository's PHP constraint allows (8.3+), type literal-valued constants so static
analysis can infer without `@var` workarounds. Skip typing only when the value is genuinely mixed.

```php
private const string CACHE_KEY = 'my_feature_generation';
protected const int DEFAULT_LIMIT = 25;
```

Below 8.3 a typed class constant is a **parse error** in production, and the only safe form is the
untyped one:

```php
// Correct below PHP 8.3
private const SECRET_HEADER = 'X-UAMSWP-Migration-Key';

// Fatal in production below PHP 8.3
private const string SECRET_HEADER = 'X-UAMSWP-Migration-Key';
```

## 11. Contract vs concrete types

Hint **the narrowest type that matches what your code actually calls** and what callers can
supply.

### Choose a contract when

- The method body only calls methods on the contract or a smaller interface.
- You're writing library-style code that should accept test doubles.
- A parent interface already declares the contract type.

### Choose the concrete class when

- The body calls framework- or package-specific methods absent from the contract.
- Analyzers report "unknown method" on a contract-typed variable.
- You've already guarded type/null and the rest of the method assumes the concrete type.

Prefer `assert` + `instanceof` (or `if`/`throw`) over a misleading `@var` on a contract-typed
return.

## 12. Avoid "magic" (macros, `__call`, undocumented helpers)

PHPStan, Psalm, PHPStorm, and Rector only understand **declared** APIs. Prefer patterns that are
typed or documented on the class you're calling, and narrow explicitly at the boundary rather than
letting an untyped value travel.

**Preferred alternatives (in order):**

1. Use a first-class API on the same object.
2. Wrap the behavior in your own small class/trait with real method signatures.
3. Narrow types with `assert($q instanceof ConcreteBuilder)` or an explicit `instanceof` guard
   before calling package-specific methods.
4. Last resort: a dedicated PHPStan/Psalm stub or extension, or a scoped PHPStan ignore, with a
   comment near the call site naming the function responsible and explaining why. Never a blanket
   ignore pattern.

In WordPress the common magic sources are `WP_Post`'s dynamic properties, `$wpdb`'s loose
returns, and `get_option()` / `get_post_meta()` returning `mixed`:

```php
$post = get_post($id);

if (! $post instanceof WP_Post) {
    return null;
}
```

### WordPress overrides: `apply_filters()` returns untrusted `mixed`

A filter can return anything, whatever the docblock promises. The WordPress stubs type
`apply_filters()` as returning its *input* type, which makes a defensive `is_array()` check on the
result read to PHPStan as always-true, so the analyzer will tell you to delete exactly the guard
you need.

Do not delete it, and do not suppress the error. Pass the value through a `mixed`-typed private
helper, which erases the optimistic narrowing and states the untrustworthiness in the signature:

```php
private static function asConfig(mixed $value, array $fallback): array
```

Reach for the same shape whenever a filter result crosses into typed code.

## 13. Read through each plugin's own API, never raw SQL

`acf_get_value()` for ACF, `GFAPI` for Gravity Forms, the `Frm*` models for Formidable,
`get_option()` for options. A plugin's own API is the only version-safe reader of its own schema.

**Never filter underscore-prefixed keys.** ACF field-key references (`_fieldname`,
`_options_<field>`), navigation (`_menu_item_*`) and featured images (`_thumbnail_id`) all live
behind that prefix. A blanket `NOT LIKE '\_%'` silently breaks all three. Core's own network
site-settings screen does exactly this; copying it loses data.

## 14. SRP and DRY

Each class/module has one well-defined purpose: one reason to change, one actor. Split when a
class mixes concerns (e.g. compiling a report **and** printing it → `ReportCompiler` +
`ReportPrinter`). Name classes after their single responsibility.

- Centralize repeated logic, validation rules, configuration, and business rules.
- Use abstractions (functions, classes, modules), but balance against the AHA principle: three
  similar lines beat a premature abstraction or a bad shared helper.
- Maintain a single source of truth for constants and configs.

## 15. `unset()` and `gc_collect_cycles()`: only where memory pressure is real

- **Do not** add end-of-method `unset()` to ordinary controllers, console commands, queued jobs,
  form requests, or small services and helpers. Locals disappear when the method returns; the
  noise diverges from ecosystem style.
- **Do** consider `unset()` inside **tight loops** in long-running processes (large imports,
  batch transforms, streaming pipelines, paginated response assembly) where iterations hold large
  arrays, ORM graphs, or DOM trees not needed in the next iteration.
- Only unset names that exist on the current path. Never unset a variable still used in the
  `return` expression.
- Call `gc_collect_cycles()` only when profiling or domain (mass import) shows retained memory
  or circular graphs in long-running loops; never by default.

When `unset()` has multiple arguments, format multi-line per §4:

```php
unset(
    $largeIntermediate,
    $parsedChunk
);
```

## 16. Verification

- Run `vendor/bin/pint --dirty` before finalizing (`--format agent` keeps the report short):
  **never** use `--test` while working; it reports without fixing.
- Run the repository's static analysis and the affected tests, scoped to a file or `--filter`.
  The `code-quality` skill names each repository's analysis and Rector commands.

