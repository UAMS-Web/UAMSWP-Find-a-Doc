#!/usr/bin/env python3
"""Tests for `gen_release_notes.py`.

Run explicitly: `unittest` discovery never reaches this file, because
`.claude` is a dot-directory and `gen_release_notes.test` is not a valid
module name:

    python3 .claude/skills/writing-release-notes/gen_release_notes.test.py

Every test is offline. The script runs over `HEAD..HEAD`, a deliberately empty
range, so no commit is resolved and no `gh` call is made; the routing, casing
and rendering tests import the module and drive its functions with fake
readers. The one temporary git repository built here exercises the first-parent
diff read without reaching GitHub.

The encoding cases pin a platform-specific bug with a platform-independent
reproduction (UAMS-Web/wordpress-importer#527): Windows sizes stdout to the
console code page, and `PYTHONIOENCODING=cp1252` puts any interpreter in
exactly that state. Against the unfixed script, any case carrying a codepoint
outside `cp1252` dies with `UnicodeEncodeError: 'charmap' codec can't encode`.
"""
import importlib.util
import json
import os
import re
import pathlib
import subprocess
import sys
import tempfile
import unittest

# No bytecode cache beside the imported module, so a run leaves the tree as it found it
# (UAMS-Web/wordpress-importer#1207): an untracked `__pycache__/` is staged by a broad
# `git add`.
sys.dont_write_bytecode = True

SCRIPT = pathlib.Path(__file__).resolve().parent / "gen_release_notes.py"
REPO_ROOT = SCRIPT.parents[3]
REPO = "UAMS-Web/example"


def _load_generator():
    """Import the generator by path.

    It cannot be imported normally: `.claude` is a dot-directory and `gen_release_notes` is
    reached through a path, not a package. Loaded once here rather than per test.
    """
    spec = importlib.util.spec_from_file_location("gen_release_notes", SCRIPT)
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)

    return mod


GEN = _load_generator()

# The Windows console's default code page. It maps the em dash but not U+26A0.
CP1252_ENV = {**os.environ, "PYTHONIOENCODING": "cp1252", "PYTHONDONTWRITEBYTECODE": "1"}

# A codepoint outside `cp1252` and outside the emoji blocks: U+2192 RIGHTWARDS ARROW.
# The callout no longer carries emoji (`no-emoji-in-durable-records`), so the encoding
# is now exercised through interpolated content, which is where the remaining risk is.
NON_CP1252 = "→"
REPLACEMENT_CHAR = "�"


def run(*args, stdout=None, repo=REPO):
    """Run the generator over an empty ref range with stdout forced to cp1252."""
    argv = [sys.executable, str(SCRIPT), "HEAD", "HEAD", *args]
    if repo is not None:
        argv += ["--repo", repo]
    return subprocess.run(
        argv,
        cwd=str(REPO_ROOT),
        env=CP1252_ENV,
        stdout=stdout if stdout is not None else subprocess.PIPE,
        stderr=subprocess.PIPE,
    )


def opts(**overrides):
    """Parsed options for render(), as parse_args() would produce them."""
    base = GEN.parse_args(["v0.1.0", "v0.2.0", "--repo", REPO])
    for key, value in overrides.items():
        setattr(base, key, value)
    return base


class FakeGitHub:
    """A reader stand-in for render(), answering from plain dicts and recording every read."""

    def __init__(self, pulls=None):
        self.pulls = pulls or {}
        self.read = []
        self.primed = None

    def prime(self, nums):
        self.primed = list(nums)

    def pull(self, num):
        self.read.append(num)
        return self.pulls.get(num)


def no_signals(_sha):
    return [], 0, 0


class ForcedUtf8OutputTest(unittest.TestCase):
    """The `--breaking` callout must survive a non-UTF-8 console."""

    def test_breaking_run_exits_zero_under_a_cp1252_console(self):
        """The shape that regressed: `--breaking` on a cp1252 stdout."""
        result = run("--lead", "Milestone lead.", "--breaking", "re-import affected content.")

        self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))
        self.assertNotIn(b"UnicodeEncodeError", result.stderr)

    def test_breaking_callout_reaches_stdout_intact(self):
        """The callout is emitted in words, with no marker glyph."""
        result = run("--lead", "Milestone lead.", "--breaking", "re-import affected content.")
        body = result.stdout.decode("utf-8")

        self.assertIn("**Breaking change**: re-import affected content.", body)
        self.assertNotIn(REPLACEMENT_CHAR, body)
        # The marker this callout used to carry must not come back.
        self.assertNotIn("\u26a0", body)

    def test_redirect_round_trips_content_outside_cp1252(self):
        """A non-cp1252 codepoint in interpolated text survives the redirect.

        This is the case the forcing still exists for. The generator no longer
        emits anything outside `cp1252` itself, but `--lead` and PR titles reach
        the notes verbatim, so the next failure would arrive through content.
        """
        lead = f"Milestone lead {NON_CP1252} with content outside cp1252."
        with tempfile.TemporaryDirectory() as tmp:
            notes = pathlib.Path(tmp) / "notes.md"
            with notes.open("wb") as handle:
                result = run("--lead", lead, "--breaking", "re-import.", stdout=handle)

            self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))
            body = notes.read_text(encoding="utf-8")

        self.assertIn(NON_CP1252, body)
        self.assertNotIn(REPLACEMENT_CHAR, body)
        self.assertNotIn("Traceback", body)

    def test_em_dash_and_inline_code_survive_the_same_run(self):
        """The characters that always worked must keep working."""
        result = run(
            "--lead", "Milestone lead: with `inline code`.",
            "--breaking", "re-import affected content.",
            "--footer", "Tagged retroactively: see `docs/DECISIONS.md`.",
        )
        body = result.stdout.decode("utf-8")

        self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))
        self.assertIn("Milestone lead: with `inline code`.", body)
        self.assertIn("_Tagged retroactively: see `docs/DECISIONS.md`._", body)

    def test_plain_run_without_breaking_still_exits_zero(self):
        """The path that never failed is not broken by forcing the encoding."""
        result = run("--lead", "Milestone lead.")

        self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))
        self.assertEqual(result.stdout.decode("utf-8").strip(), "Milestone lead.")

    def test_help_renders_under_a_cp1252_console(self):
        """`--help` writes before anything else, so the forcing has to come first."""
        result = run("--help")

        self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))
        self.assertIn(b"--repo", result.stdout)


class RepoFlagTest(unittest.TestCase):
    """`--repo` is required, and decides every link (UAMS-Web/uamswp-migration-api#309).

    The generator this merges defaulted to its own repository in two of three copies, and
    run from a sibling without the flag it linked every bullet to that repository's pull
    requests: real numbers, unrelated changes, no error, and output that looked right.
    """

    def test_a_run_without_repo_is_refused_before_any_notes_are_written(self):
        result = run("--lead", "x", repo=None)

        self.assertEqual(result.returncode, 2)
        self.assertIn(b"--repo", result.stderr)
        self.assertEqual(result.stdout, b"", "a refused run must write no notes, or a redirect captures a partial body")

    def test_a_repo_that_is_not_owner_slash_name_is_refused(self):
        result = run(repo="example")

        self.assertEqual(result.returncode, 2)
        self.assertIn(b"OWNER/NAME", result.stderr)

    def test_the_same_arguments_with_the_flag_run(self):
        """The control for the refusals above: a parser that refused everything would pass them."""
        result = run("--lead", "x")

        self.assertEqual(result.returncode, 0, result.stderr.decode("utf-8", "replace"))

    def test_a_range_git_cannot_read_fails_instead_of_rendering_an_empty_release(self):
        """A mistyped tag must not read as a release with nothing in it.

        `scripts/gen-release-notes.mjs` in UAMS-Web/uamswp-migration-api exits 1 here; this
        script used to read the failure as an empty range and exit 0 with a well-formed body.
        """
        argv = [sys.executable, str(SCRIPT), "no-such-tag-xyz", "HEAD", "--repo", REPO]
        result = subprocess.run(argv, cwd=str(REPO_ROOT), env=CP1252_ENV,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)

        self.assertEqual(result.returncode, 1)
        self.assertIn(b"git log failed", result.stderr)
        self.assertEqual(result.stdout, b"")

    def test_repo_decides_every_link_and_no_other_repository_appears(self):
        body = GEN.render(
            [("a" * 40, "Serve a thing (#12)"), ("b" * 40, "A direct commit")],
            opts(repo="Some/Other"),
            FakeGitHub({12: {"title": "Serve a thing", "body": "", "labels": [], "closing": []}}),
            signals=no_signals,
        )
        links = re.findall(r"https://github\.com/([^/]+/[^/]+)/", body)

        self.assertEqual(len(links), 2)
        self.assertEqual(set(links), {"Some/Other"})


class RoutingTest(unittest.TestCase):
    """Routing keys on labels, paths, the diff and title verbs: never on a Conventional-Commit prefix.

    These import `bucket()` directly rather than driving the script, so they are offline and
    make no `gh` call. The prefix cases are the point: `writing-pull-requests` and
    `writing-issues` both forbid those prefixes on titles, and a squash-merging repository's
    commit subject IS the PR title. Measured in UAMS-Web/wordpress-importer over
    `v0.17.0..v0.18.0`, 0 of 20 subjects carried one (#1093): the branches that used to
    route on them could never fire.
    """

    @staticmethod
    def _bucket(*args, **kwargs):
        return GEN.bucket(*args, **kwargs)

    def test_a_legacy_prefix_changes_nothing(self):
        """A title carrying `chore:` routes exactly as the same title without it."""
        paths = [".claude/skills/writing-release-notes/SKILL.md"]

        with_prefix = self._bucket("chore: tidy the release skill", "tidy the release skill", [], paths)
        without = self._bucket("tidy the release skill", "tidy the release skill", [], paths)

        self.assertEqual(with_prefix, without)
        self.assertEqual(with_prefix, "maint")

    def test_a_prefix_cannot_drag_product_work_into_maintenance(self):
        """The inverse, which is the fault that shipped: `docs:` used to force `maint`."""
        self.assertEqual(
            self._bucket("docs: add a WXR shortcode parser", "Add a WXR shortcode parser",
                         [], ["src/Services/WordPressImport/Parsers/Shortcode.php"]),
            "new",
        )

    def test_paths_confined_to_tooling_route_to_maintenance(self):
        self.assertEqual(
            self._bucket("Record the measured zsh case", "Record the measured zsh case",
                         [], ["CLAUDE.md"]),
            "maint",
        )

    def test_product_paths_stay_out_of_maintenance(self):
        self.assertEqual(
            self._bucket("Emit progress while materializing", "Emit progress while materializing",
                         [], ["src/Services/WordPressImport/MigrationApi/WxrMaterializer.php"]),
            "new",
        )

    def test_a_label_routes_without_any_path_signal(self):
        self.assertEqual(
            self._bucket("Bring the runner to parity", "Bring the runner to parity",
                         ["build"], []),
            "maint",
        )

    def test_a_repair_is_a_fix_even_in_prose_paths(self):
        """Ordering: the fix verb runs BEFORE the path rule, or a README repair reads as tooling."""
        self.assertEqual(
            self._bucket("Stop instructing a host app", "Stop instructing a host app",
                         [], ["README.md", "docs/runbooks/migration-api-secret.md"]),
            "fix",
        )

    def test_a_tooling_label_outranks_a_repair_verb(self):
        """Ordering: a label is a deliberate signal and the verb a heuristic.

        The two UAMS-Web/uams-statamic cases from the 2026-09-14 range: maintenance changes
        opening with a fix verb, routed right only because the label is consulted first.
        """
        for title in ("Correct the dictionary overrides", "Stop two dictionary words drifting"):
            with self.subTest(title=title):
                self.assertEqual(self._bucket(title, title, ["documentation"], ["app/X.php"]), "maint")
                # The control: the same title with no label is a fix.
                self.assertEqual(self._bucket(title, title, [], ["app/X.php"]), "fix")

    def test_security_item_forces_a_bullet_nothing_else_can_reach(self):
        """UAMS-Web/wordpress-importer#1080: no label, no keyword, and paths that are prose."""
        subject = "Read the key at the verification step rather than assigning it"
        paths = ["README.md", "project-words.txt"]

        self.assertNotEqual(self._bucket(subject, subject, [], paths), "sec")
        self.assertEqual(self._bucket(subject, subject, [], paths, forced_sec=True), "sec")

    def test_the_security_label_routes_without_the_flag(self):
        self.assertEqual(
            self._bucket("Read the key at the verification step", "Read the key at the verification step",
                         ["security"], ["README.md"], forced_sec=False),
            "sec",
        )

    def test_secret_and_credential_no_longer_route_to_security(self):
        """UAMS-Web/uamswp-migration-api#214: a note about a shell profile landed in Security.

        Both halves asserted: the words are out of the default list, and `--security-word`
        puts one back for a repository that wants it.
        """
        title = "Name the condition the credential remedy depends on"

        self.assertNotEqual(self._bucket(title, title, [], ["includes/X.php"]), "sec")
        self.assertEqual(
            self._bucket(title, title, [], ["includes/X.php"],
                         routing=GEN.Routing(security_words=["credential"])),
            "sec",
        )

    def test_a_security_phrase_routes(self):
        for title in ("Reject unauthenticated reads of the options route",
                      "Tighten the auth gate on every route",
                      "Add the option denylist"):
            with self.subTest(title=title):
                self.assertEqual(self._bucket(title, title, [], ["includes/X.php"]), "sec")

    def test_a_change_with_no_signal_at_all_still_appears(self):
        """No label, no recognizable path, no verb: it lands in the documented default."""
        self.assertEqual(self._bucket("Something entirely novel", "Something entirely novel", [], []), "new")

    def test_an_empty_path_list_does_not_read_as_tooling(self):
        """A failed read returns no paths; that must degrade to no signal, not to `maint`."""
        self.assertFalse(GEN.paths_are_tooling([]))

    def test_a_change_confined_to_tests_is_tooling_by_default(self):
        """Nothing under `tests/` ships, so a tests-only change changes no behavior."""
        self.assertEqual(
            self._bucket("Date the verification", "Date the verification",
                         [], ["tests/Unit/SpellJobZeroFileTest.php"]),
            "maint",
        )

    def test_product_path_tests_makes_a_tests_only_change_product_work(self):
        """The importer's stance: a change touching `src/` or `tests/` is product work."""
        routing = GEN.Routing(product_paths=["tests"])

        self.assertFalse(GEN.paths_are_tooling(["tests/Unit/XTest.php"], routing))
        self.assertEqual(
            self._bucket("Date the verification", "Date the verification",
                         [], ["tests/Unit/XTest.php"], routing=routing),
            "new",
        )

    def test_a_change_touching_what_ships_is_never_tooling(self):
        for path in ("includes/Auth.php", "config/defaults.php", "uamswp-migration-api.php", "src/X.php"):
            with self.subTest(path=path):
                self.assertFalse(GEN.paths_are_tooling(["tests/Feature/AuthTest.php", path]))

    def test_tooling_path_extends_the_built_in_set(self):
        self.assertFalse(GEN.paths_are_tooling(["tooling/x.sh"]))
        self.assertTrue(GEN.paths_are_tooling(["tooling/x.sh"], GEN.Routing(tooling_paths=["tooling/"])))

    def test_a_test_dominant_diff_is_maintenance(self):
        """The production edit is incidental to the coverage it enables."""
        self.assertEqual(
            self._bucket("Cover the entry mapper", "Cover the entry mapper", [],
                         ["app/Mapper.php", "tests/Unit/MapperTest.php"], test_lines=200, other_lines=2),
            "maint",
        )
        # The control: the same diff the other way round is product work.
        self.assertEqual(
            self._bucket("Cover the entry mapper", "Cover the entry mapper", [],
                         ["app/Mapper.php", "tests/Unit/MapperTest.php"], test_lines=2, other_lines=200),
            "new",
        )

    def test_a_product_path_disables_the_test_dominant_rule(self):
        """A user-facing surface makes it a product change regardless of how test-heavy it is."""
        routing = GEN.Routing(product_paths=["resources", "routes"])
        paths = ["resources/views/nav.antlers.html", "tests/Feature/NavTest.php"]

        self.assertEqual(
            self._bucket("Show the nav", "Show the nav", [], paths, test_lines=200, other_lines=2),
            "maint",
        )
        self.assertEqual(
            self._bucket("Show the nav", "Show the nav", [], paths, test_lines=200, other_lines=2,
                         routing=routing),
            "new",
        )

    def test_tests_as_product_disables_the_test_dominant_rule(self):
        self.assertEqual(
            self._bucket("Cover the entry mapper", "Cover the entry mapper", [],
                         ["src/Mapper.php", "tests/Unit/MapperTest.php"], test_lines=200, other_lines=2,
                         routing=GEN.Routing(product_paths=["tests"])),
            "new",
        )

    def test_maintenance_word_is_consulted_last(self):
        """`rule` is not in the default list; added, it still yields to the issue type."""
        title = "Add a rule for the cost figure"

        self.assertEqual(self._bucket(title, title, [], ["includes/X.php"]), "new")
        routing = GEN.Routing(maintenance_words=["rule"])
        self.assertEqual(self._bucket(title, title, [], ["includes/X.php"], routing=routing), "maint")
        self.assertEqual(
            self._bucket(title, title, [], ["includes/X.php"], issue_type="Feature", routing=routing),
            "new",
        )

    def test_record_and_port_are_maintenance_verbs(self):
        for title in ("Record the public repository's name", "Port the generator to Python"):
            with self.subTest(title=title):
                self.assertEqual(self._bucket(title, title, [], ["includes/X.php"]), "maint")


class AcronymCasingTest(unittest.TestCase):
    """Acronym casing applies to prose and leaves identifiers alone.

    Backticked text is something a reader copies: a package name, a path, a handle: so
    rewriting its case corrupts it (UAMS-Web/wordpress-importer#1110). So is a word joined to
    a larger token (UAMS-Web/uams-statamic#2589, UAMS-Web/uamswp-migration-api#318). Both
    halves are asserted deliberately: a test that only checked identifiers survive would pass
    against a `fix_acro()` that had been deleted outright.
    """

    def test_a_backticked_package_name_survives_byte_identical(self):
        for title in (
            "Pin `uams-web/wordpress-importer` to ^0.18",
            "Bump `statamic/cms` for the Statamic 6 upgrade",
            "Run `phpstan analyse` under `config/statamic/search.php`",
        ):
            with self.subTest(title=title):
                self.assertEqual(GEN.fix_acro(title), title)

    def test_prose_outside_backticks_is_still_cased(self):
        """The narrowing must not become a removal."""
        self.assertEqual(
            GEN.fix_acro("Update the wordpress importer docs and the mcp notes"),
            "Update the WordPress importer docs and the MCP notes",
        )

    def test_a_title_mixing_both_is_handled_in_one_pass(self):
        """An identifier, prose needing correction, and an unquoted joined name together."""
        self.assertEqual(
            GEN.fix_acro("Bump `statamic/cms` and the wordpress-importer pin for statamic 6"),
            "Bump `statamic/cms` and the wordpress-importer pin for Statamic 6",
        )

    def test_several_spans_do_not_merge_into_one(self):
        """A greedy span would swallow the prose between two identifiers and leave it uncased."""
        self.assertEqual(
            GEN.fix_acro("Use `statamic/cms` with statamic and `wordpress` together"),
            "Use `statamic/cms` with Statamic and `wordpress` together",
        )

    def test_an_unclosed_backtick_is_prose(self):
        """Deliberate: one backtick opens no span, so the text after it is ordinary prose.

        Treating it as a span would silently stop casing the remainder of every title that
        contains a stray backtick.
        """
        self.assertEqual(
            GEN.fix_acro("unclosed `backtick with wordpress inside"),
            "unclosed `backtick with WordPress inside",
        )

    def test_an_empty_span_is_left_alone_and_does_not_break_the_split(self):
        self.assertEqual(GEN.fix_acro("empty `` span with wordpress"), "empty `` span with WordPress")

    def test_a_word_joined_to_a_larger_token_is_left_byte_identical(self):
        for title in (
            "Prefix the mutate script with @php, so its children launch",
            "Run ci:local before merging",
            "Pin uams-web/wordpress-importer and php-stubs/wordpress-stubs",
            "Read config/api.php",
            "Stop WordPress-importer from drifting",
        ):
            with self.subTest(title=title):
                self.assertEqual(GEN.fix_acro(title), title)

    def test_the_same_words_in_plain_prose_are_cased_including_at_a_sentence_end(self):
        self.assertEqual(GEN.fix_acro("Run php against wordpress through the api"),
                         "Run PHP against WordPress through the API")
        self.assertEqual(GEN.fix_acro("Serve it through the api."), "Serve it through the API.")
        self.assertEqual(GEN.fix_acro("The api, the cli and php: all cased"),
                         "The API, the CLI and PHP: all cased")
        self.assertEqual(GEN.fix_acro("Emit json-ld and plain json"), "Emit JSON-LD and plain JSON")

    def test_tool_names_are_cased_as_the_tools_spell_them(self):
        self.assertEqual(GEN.fix_acro("Raise phpstan and larastan to level 6"),
                         "Raise PHPStan and Larastan to level 6")

    def test_prose_rest_is_not_an_acronym(self):
        self.assertEqual(GEN.fix_acro("Serve the rest of the fields"), "Serve the rest of the fields")

    def test_clean_title_strips_a_prefix_and_a_trailing_reference(self):
        self.assertEqual(GEN.clean_title("fix: serve a thing (#12)"), "Serve a thing")
        self.assertEqual(GEN.clean_title("Serve a thing (merge after #11) (#12, #13)"), "Serve a thing")

    def test_an_ampersand_becomes_and_with_an_oxford_comma_when_it_closes_a_list(self):
        self.assertEqual(GEN.noamp("Posts & terms"), "Posts and terms")
        self.assertEqual(GEN.noamp("Posts, terms & options"), "Posts, terms, and options")


class ClosingIssueTypeTest(unittest.TestCase):
    """Fix-versus-feature routes on the closing issue's type, not a title verb list (UAMS-Web/wordpress-importer#1112).

    The verb list cannot cover every title: `writing-issues` asks for an imperative naming
    the OUTCOME, so a repair is routinely titled as the corrected behavior: "Select the
    row", "Normalize both representations", "Honour `--network`": and none of those is a
    repair word. The issue type is set at filing and says what the work is.
    """

    def test_a_bug_routes_to_fixed_despite_a_non_repair_verb(self):
        """The real case: #1108's title opens with `Select` and it is a bug fix."""
        self.assertEqual(
            GEN.bucket("Select the MinervaKB settings row instead of hardcoding the v1 one",
                       "Select the MinervaKB settings row instead of hardcoding the v1 one",
                       ["development"], ["src/Services/X.php", "tests/Integration/XTest.php"],
                       issue_type="Bug"),
            "fix",
        )

    def test_a_feature_routes_to_new_against_a_topic_keyword(self):
        """The title carries `coverage`, which the topic heuristics read as tooling.

        Asserted against a case the type actually DECIDES. A Feature whose title also looks
        like a feature falls through to the default bucket and passes whether the branch
        exists or not: measured, that version left a surviving mutant.
        """
        title = "Add coverage for imported entry metadata"

        self.assertEqual(
            GEN.bucket(title, title, [], ["src/Services/X.php"], issue_type=None),
            "maint",
        )
        self.assertEqual(
            GEN.bucket(title, title, [], ["src/Services/X.php"], issue_type="Feature"),
            "new",
        )

    def test_a_repair_verb_still_outranks_the_type(self):
        """Recorded as the CURRENT precedence rather than asserted as correct.

        The fix-verb test runs above the type branch, so a title opening with a repair word
        wins even against a `Feature`. No case of that conflict appears in any measured range,
        so there is no evidence for inverting it: this test pins today's behavior so a future
        change to it is deliberate rather than accidental.
        """
        title = "Restore support for importing multi-site attachments"

        self.assertEqual(
            GEN.bucket(title, title, [], ["src/Services/X.php"], issue_type="Feature"),
            "fix",
        )

    def test_tooling_outranks_the_issue_type(self):
        """Ordering, and it is the half a first build got wrong.

        A bug fixed in a script or a skill is still tooling. Placing the type branch above the
        label and path rules moved #1084 and #1086 out of `Maintenance and tooling`, against
        the bucketing #1093 produced for `v0.19.0` that nobody corrected. What the change is
        IN outranks what the change IS.
        """
        self.assertEqual(
            GEN.bucket("Gate the parity differences the verdict counted and discarded",
                       "Gate the parity differences the verdict counted and discarded",
                       [], ["scripts/local-ci.mjs"], issue_type="Bug"),
            "maint",
        )

    def test_no_type_falls_back_to_the_title_heuristics(self):
        """An absent type must not become a default bucket.

        Both arms asserted, because a fallback that always returned `new` would pass the first
        alone while silently discarding the verb list.
        """
        self.assertEqual(
            GEN.bucket("Resolve a cross-site news-block category", "Resolve a cross-site news-block category",
                       [], ["src/X.php"], issue_type=None),
            "fix",
        )
        self.assertEqual(
            GEN.bucket("Emit progress while materializing", "Emit progress while materializing",
                       [], ["src/X.php"], issue_type=None),
            "new",
        )

    def test_an_unrecognized_type_is_ignored_rather_than_guessed(self):
        """`Task` names no section, so it must not claim one."""
        self.assertEqual(
            GEN.bucket("Emit progress while materializing", "Emit progress while materializing",
                       [], ["src/X.php"], issue_type="Task"),
            "new",
        )
        self.assertEqual(
            GEN.bucket("Resolve a cross-site news-block category", "Resolve a cross-site news-block category",
                       [], ["src/X.php"], issue_type="Task"),
            "fix",
        )

    def test_security_still_outranks_the_issue_type(self):
        """A Bug-typed security fix belongs under Security, not What's fixed."""
        self.assertEqual(
            GEN.bucket("Stop an SSRF in the remote fetch", "Stop an SSRF in the remote fetch",
                       [], ["src/X.php"], issue_type="Bug"),
            "sec",
        )


class ClosingIssueParseTest(unittest.TestCase):
    """The body keyword that names the closing issue (UAMS-Web/wordpress-importer#1112)."""

    def test_every_github_closing_keyword_is_recognized(self):
        for body in ("Closes #1107.", "closes #1107", "Fixes #1107", "fixed #1107",
                     "Resolves #1107", "resolve #1107"):
            with self.subTest(body=body):
                self.assertIsNotNone(GEN._CLOSES_RE.search(body))

    def test_a_bare_reference_is_not_a_closing_reference(self):
        """`Part of #695` and `See #810` must not be read as closing them."""
        for body in ("Part of #695.", "See #810 for context", "#1107 is related"):
            with self.subTest(body=body):
                self.assertIsNone(GEN._CLOSES_RE.search(body))

    def test_the_pull_request_a_subject_names(self):
        self.assertEqual(GEN.subject_pull("Serve a thing (#197)"), 197)
        self.assertEqual(GEN.subject_pull("Merge pull request #5 from UAMS-Web/x"), 5)
        self.assertIsNone(GEN.subject_pull("A direct commit"))


class GitHubReaderTest(unittest.TestCase):
    """Both readers return one shape, and neither trusts the exit code more than the data."""

    def test_the_rest_reader_takes_labels_and_type_from_the_closing_issue_as_well(self):
        answers = {
            "repos/UAMS-Web/example/pulls/12": (0, json.dumps(
                {"title": "Serve a field", "body": "Closes #7.\n\nPart of #3.", "labels": ["development"]})),
            "repos/UAMS-Web/example/issues/7": (0, json.dumps({"type": "Bug", "labels": ["documentation"]})),
        }
        calls = []

        def runner(args):
            calls.append(args)
            return next((v for k, v in answers.items() if k in args), (1, ""))

        reader = GEN.RestGitHub(REPO, runner)
        pull = reader.pull(12)

        self.assertEqual(pull["title"], "Serve a field")
        self.assertEqual(pull["labels"], ["development"])
        self.assertEqual(pull["closing"], [{"number": 7, "type": "Bug", "labels": ["documentation"]}])
        # `Part of #3` is not a closing reference, so issue 3 is never read.
        self.assertFalse(any("issues/3" in a for args in calls for a in args))
        # A second read is served from the cache.
        reader.pull(12)
        self.assertEqual(sum(1 for args in calls if "repos/UAMS-Web/example/pulls/12" in args), 1)

    def test_the_rest_reader_returns_none_for_a_number_that_is_not_a_pull_request(self):
        reader = GEN.RestGitHub(REPO, lambda args: (1, '{"message":"Not Found"}'))

        self.assertIsNone(reader.pull(99))

    def test_a_null_issue_type_is_none(self):
        reader = GEN.RestGitHub(REPO, lambda args: (0, json.dumps({"type": None, "labels": []})))

        self.assertIsNone(reader.issue(7)["type"])

    def test_the_graphql_reader_parses_partial_data_regardless_of_the_exit_code(self):
        """One alias that is not a pull request makes `gh` exit non-zero; the rest is still data.

        Gating on the exit code discarded 195 good titles over one bad reference in
        UAMS-Web/uams-statamic and emitted a full set of bullets with no links.
        """
        response = {
            "data": {"repository": {
                "p12": {"title": "Serve a field", "body": "", "labels": {"nodes": [{"name": "development"}]},
                        "closingIssuesReferences": {"nodes": [
                            {"number": 7, "issueType": {"name": "Bug"}, "labels": {"nodes": [{"name": "documentation"}]}},
                            {"number": 8, "issueType": None, "labels": {"nodes": []}},
                        ]}},
                "p13": None,
            }},
            "errors": [{"type": "NOT_FOUND", "path": ["repository", "p13"]}],
        }
        calls = []

        def runner(args):
            calls.append(args)
            return 1, json.dumps(response)

        reader = GEN.GraphqlGitHub(REPO, runner)
        reader.prime([12, 13, 12])

        self.assertEqual(len(calls), 1, "one batched query for every pull request")
        self.assertIn("p12: pullRequest(number:12)", calls[0][-1])
        self.assertIn("issueType{name}", calls[0][-1])
        self.assertEqual(reader.pull(12)["labels"], ["development"])
        self.assertEqual(reader.pull(12)["closing"], [
            {"number": 7, "type": "Bug", "labels": ["documentation"]},
            {"number": 8, "type": None, "labels": []},
        ])
        self.assertIsNone(reader.pull(13))
        self.assertEqual(len(calls), 1, "primed numbers are not queried again")

    def test_the_graphql_reader_survives_a_response_that_is_not_json(self):
        reader = GEN.GraphqlGitHub(REPO, lambda args: (1, "gh: GraphQL: API rate limit already exceeded"))

        self.assertIsNone(reader.pull(12))


class DiffSignalsTest(unittest.TestCase):
    """Paths come from git, against the first parent, so a merge commit yields the pull request's diff."""

    @classmethod
    def setUpClass(cls):
        cls.tmp = tempfile.TemporaryDirectory()
        cls.root = cls.tmp.name
        env = {**os.environ, "GIT_AUTHOR_NAME": "t", "GIT_AUTHOR_EMAIL": "t@example.invalid",
               "GIT_COMMITTER_NAME": "t", "GIT_COMMITTER_EMAIL": "t@example.invalid"}

        def git(*args):
            r = subprocess.run(["git", *args], cwd=cls.root, env=env, capture_output=True, text=True)
            if r.returncode != 0:
                raise RuntimeError(r.stderr)
            return r.stdout.strip()

        def write(rel, lines):
            path = pathlib.Path(cls.root) / rel
            path.parent.mkdir(parents=True, exist_ok=True)
            path.write_text("".join(f"line {i}\n" for i in range(lines)))

        git("init", "-q", "-b", "main")
        write("README.md", 1)
        git("add", "-A")
        git("commit", "-q", "-m", "Root commit")
        cls.root_sha = git("rev-parse", "HEAD")

        git("checkout", "-q", "-b", "feature")
        write("app/Mapper.php", 2)
        write("tests/Unit/MapperTest.php", 40)
        git("add", "-A")
        git("commit", "-q", "-m", "Cover the mapper")
        git("checkout", "-q", "main")
        git("merge", "-q", "--no-ff", "--no-edit", "feature")
        cls.merge_sha = git("rev-parse", "HEAD")

        write("src/X.php", 5)
        git("add", "-A")
        git("commit", "-q", "-m", "Serve a thing (#12)")
        cls.plain_sha = git("rev-parse", "HEAD")
        cls.cwd = os.getcwd()
        os.chdir(cls.root)

    @classmethod
    def tearDownClass(cls):
        os.chdir(cls.cwd)
        cls.tmp.cleanup()

    def test_a_merge_commit_yields_the_branch_diff_and_counts_test_lines(self):
        paths, test_lines, other_lines = GEN.diff_signals(self.merge_sha)

        self.assertEqual(sorted(paths), ["app/Mapper.php", "tests/Unit/MapperTest.php"])
        self.assertEqual((test_lines, other_lines), (40, 2))

    def test_a_plain_commit_yields_its_own_diff(self):
        self.assertEqual(GEN.diff_signals(self.plain_sha), (["src/X.php"], 0, 5))

    def test_the_root_commit_yields_its_files(self):
        self.assertEqual(GEN.diff_signals(self.root_sha), (["README.md"], 0, 1))

    def test_read_log_walks_the_first_parent_only(self):
        log = GEN.read_log(self.root_sha, self.plain_sha)

        self.assertEqual([s for _sha, s in log], ["Serve a thing (#12)", "Merge branch 'feature'"])
        self.assertEqual(len(GEN.read_log("-", self.plain_sha)), 3)


class BodyTest(unittest.TestCase):
    """The rendered shape, with every repository's optional section."""

    log = [
        ("a" * 40, "Serve a field (#12)"),
        ("b" * 40, "Document the gate (#13)"),
        ("c" * 40, "Merge branch main into x"),
    ]
    pulls = {
        12: {"title": "Serve a field", "body": "Closes #1.", "labels": [],
             "closing": [{"number": 1, "type": "Feature", "labels": []}]},
        13: {"title": "Document the gate", "body": "", "labels": [], "closing": []},
    }

    @staticmethod
    def signals(sha):
        return {"a" * 40: (["includes/X.php"], 0, 3), "b" * 40: ([".claude/rules/x.md"], 0, 3)}.get(sha, ([], 0, 0))

    def test_the_default_shape_has_only_the_lead_and_the_buckets(self):
        body = GEN.render(self.log, opts(lead="A lead."), FakeGitHub(self.pulls), self.signals)

        self.assertEqual(body, "\n".join([
            "A lead.",
            "",
            "## What's new",
            f"- Serve a field [#12](https://github.com/{REPO}/pull/12)",
            "",
            "## Maintenance and tooling",
            f"- Document the gate [#13](https://github.com/{REPO}/pull/13)",
            "",
        ]))

    def test_the_optional_sections_keep_the_migration_api_shape(self):
        note = "Marked pre-release. Nothing pins this repository."
        body = GEN.render(
            self.log,
            opts(lead="A lead.", note=note, verification="Green on abc.", known_gaps="See `AGENTS.md`."),
            FakeGitHub(self.pulls), self.signals)

        self.assertEqual(body, "\n".join([
            "A lead.",
            "",
            note,
            "",
            "## What's new",
            f"- Serve a field [#12](https://github.com/{REPO}/pull/12)",
            "",
            "## Maintenance and tooling",
            f"- Document the gate [#13](https://github.com/{REPO}/pull/13)",
            "",
            "## Verification at this tag",
            "Green on abc.",
            "",
            "## Known gaps",
            "See `AGENTS.md`.",
            "",
        ]))

    def test_the_breaking_callout_is_its_own_paragraph_and_an_itemized_pull_request_costs_no_read(self):
        github = FakeGitHub(self.pulls)
        body = GEN.render(
            self.log,
            opts(lead="A lead.", breaking="the importer must read `id` as a string.",
                 breaking_item=[f"Change `id` to a string [#12](https://github.com/{REPO}/pull/12)"]),
            github, self.signals)

        self.assertTrue(body.startswith("A lead.\n\n**Breaking change**: the importer must read `id` as a string.\n\n"))
        self.assertIn("## Breaking changes\n- Change `id` to a string", body)
        self.assertEqual(body.count("pull/12)"), 1, "#12 must appear once, in the breaking item only")
        self.assertNotIn(12, github.read)
        self.assertNotIn(12, github.primed)

    def test_an_excluded_pull_request_is_left_out_before_it_is_read(self):
        github = FakeGitHub(self.pulls)
        body = GEN.render(self.log, opts(exclude=[13]), github, self.signals)

        self.assertNotIn("pull/13", body)
        self.assertEqual(github.read, [12])

    def test_a_security_item_lands_under_security(self):
        body = GEN.render(self.log, opts(security_item=[13]), FakeGitHub(self.pulls), self.signals)

        self.assertIn("## Security\n- Document the gate", body)

    def test_a_direct_commit_is_linked_by_its_short_sha(self):
        body = GEN.render([("d" * 40, "A direct commit")], opts(), FakeGitHub(), no_signals)

        self.assertIn(f"- A direct commit [`ddddddd`](https://github.com/{REPO}/commit/{'d' * 40})", body)

    def test_an_unresolved_pull_request_number_falls_back_to_the_subject(self):
        body = GEN.render([("e" * 40, "Serve a thing (#99)")], opts(), FakeGitHub(), no_signals)

        self.assertIn(f"- Serve a thing [`eeeeeee`](https://github.com/{REPO}/commit/{'e' * 40})", body)

    def test_a_missing_lead_is_a_todo_rather_than_an_invention(self):
        body = GEN.render([], opts(), FakeGitHub(), no_signals)

        self.assertEqual(body, "TODO: one-sentence milestone lead.\n")

    def test_a_skip_pattern_drops_a_subject_and_the_merge_noise_is_dropped_by_default(self):
        log = [("f" * 40, "Iteration planning for sprint 9"), ("g" * 40, "Merge branch 'x'"),
               ("h" * 40, "Update subproject commit"), ("i" * 40, "Serve a thing")]

        body = GEN.render(log, opts(), FakeGitHub(), no_signals)
        self.assertIn("Iteration planning", body)
        self.assertNotIn("Merge branch", body)
        self.assertNotIn("subproject", body)

        body = GEN.render(log, opts(skip=[r"iteration[- ]planning"]), FakeGitHub(), no_signals)
        self.assertNotIn("Iteration planning", body)
        self.assertIn("Serve a thing", body)

    def test_the_ampersand_rule_applies_to_the_lead_the_callout_and_every_bullet(self):
        body = GEN.render(
            [("j" * 40, "Posts, terms & options")],
            opts(lead="Maps & launch prep.", breaking="re-import posts, terms & options."),
            FakeGitHub(), no_signals)

        self.assertIn("Maps and launch prep.", body)
        self.assertIn("re-import posts, terms, and options.", body)
        self.assertIn("- Posts, terms, and options", body)

    def test_the_routing_options_reach_the_bucketing(self):
        log = [("k" * 40, "Date the verification (#14)")]
        pulls = {14: {"title": "Date the verification", "body": "", "labels": [], "closing": []}}

        def signals(_sha):
            return ["tests/Unit/XTest.php"], 10, 0

        self.assertIn("## Maintenance and tooling", GEN.render(log, opts(), FakeGitHub(pulls), signals))
        self.assertIn("## What's new", GEN.render(log, opts(product_path=["tests"]), FakeGitHub(pulls), signals))

    def test_labels_from_the_closing_issue_route_as_the_pull_requests_own_do(self):
        log = [("m" * 40, "Correct the dictionary (#15)")]
        pulls = {15: {"title": "Correct the dictionary", "body": "Closes #2.", "labels": [],
                      "closing": [{"number": 2, "type": None, "labels": ["documentation"]}]}}

        body = GEN.render(log, opts(), FakeGitHub(pulls), lambda sha: (["app/X.php"], 0, 3))

        self.assertIn("## Maintenance and tooling\n- Correct the dictionary", body)


if __name__ == "__main__":
    unittest.main()

# cspell:ignore codepoint Materializer
