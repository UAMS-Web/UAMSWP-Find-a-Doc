'use strict';

/**
 * Golden-file test for the gulp style build.
 *
 * Runs gulpfile.js, unchanged, against the locked npm packages and compares the compiled
 * CSS with tests/build/app.golden.css. A dependency update that changes the output shows up
 * as a diff in the golden file to review.
 *
 * The Sass entry imports the uams-2020 theme (`../../../../themes/uams-2020/...`), so the
 * build is staged in a temporary wp-content tree: a copy of the gulpfile and the Sass,
 * node_modules linked from this checkout, and the theme linked from UAMS_2020_THEME. The
 * checkout's assets/css/app.css is never written.
 *
 * Run: UAMS_2020_THEME=/path/to/uams-2020 npm test
 * Update the golden file deliberately: UPDATE_GOLDEN=1 UAMS_2020_THEME=... npm test
 */

const assert = require('node:assert/strict');
const { spawnSync } = require('node:child_process');
const crypto = require('node:crypto');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const { test } = require('node:test');

const root = path.resolve(__dirname, '..', '..');
const goldenPath = path.join(__dirname, 'app.golden.css');
const builtCss = path.join(root, 'assets', 'css', 'app.css');

const sha256 = (file) => crypto.createHash('sha256').update(fs.readFileSync(file)).digest('hex');

test('the style task compiles the golden CSS', () => {
    const theme = process.env.UAMS_2020_THEME;

    assert.ok(
        theme && fs.existsSync(path.join(theme, 'assets', 'scss', 'supporting')),
        'Set UAMS_2020_THEME to a UAMS-Web/uams-2020 checkout with its node_modules/bootstrap installed.'
    );
    assert.ok(
        fs.existsSync(path.join(theme, 'node_modules', 'bootstrap', 'scss', '_functions.scss')),
        `No node_modules/bootstrap in ${theme}; install the theme's packages.`
    );

    const builtBefore = sha256(builtCss);
    const stage = fs.mkdtempSync(path.join(os.tmpdir(), 'fad-style-'));

    try {
        const plugin = path.join(stage, 'wp-content', 'plugins', 'uamswp-find-a-doc');
        const themes = path.join(stage, 'wp-content', 'themes');

        fs.mkdirSync(path.join(plugin, 'assets'), { recursive: true });
        fs.mkdirSync(themes, { recursive: true });
        fs.copyFileSync(path.join(root, 'gulpfile.js'), path.join(plugin, 'gulpfile.js'));
        fs.cpSync(path.join(root, 'assets', 'scss'), path.join(plugin, 'assets', 'scss'), { recursive: true });
        fs.symlinkSync(path.join(root, 'node_modules'), path.join(plugin, 'node_modules'), 'dir');
        fs.symlinkSync(path.resolve(theme), path.join(themes, 'uams-2020'), 'dir');

        const run = spawnSync(
            process.execPath,
            [path.join(root, 'node_modules', 'gulp', 'bin', 'gulp.js'), '--cwd', plugin],
            { encoding: 'utf8' }
        );

        assert.equal(run.status, 0, `gulp exited ${run.status}\n${run.stdout}\n${run.stderr}`);

        const output = path.join(plugin, 'assets', 'css', 'app.css');

        assert.ok(fs.existsSync(output), 'The style task wrote no app.css');

        const actual = fs.readFileSync(output, 'utf8');

        assert.ok(actual.length > 1000, `The compiled CSS is implausibly small (${actual.length} bytes)`);

        if (process.env.UPDATE_GOLDEN === '1') {
            fs.writeFileSync(goldenPath, actual);
            return;
        }

        const expected = fs.readFileSync(goldenPath, 'utf8');

        if (actual !== expected) {
            const a = actual.split('}');
            const e = expected.split('}');
            const at = a.findIndex((rule, i) => rule !== e[i]);

            assert.fail(
                `Compiled CSS differs from tests/build/app.golden.css (${actual.length} vs ${expected.length} bytes).\n`
                + `First differing rule (#${at}):\n  now:    ${(a[at] ?? '<none>').slice(0, 300)}\n  golden: ${(e[at] ?? '<none>').slice(0, 300)}\n`
                + 'If the change is intended, rerun with UPDATE_GOLDEN=1 and review the golden-file diff.'
            );
        }
    } finally {
        fs.rmSync(stage, { recursive: true, force: true });
        assert.equal(sha256(builtCss), builtBefore, 'assets/css/app.css was modified');
    }
});
