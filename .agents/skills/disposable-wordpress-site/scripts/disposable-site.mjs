#!/usr/bin/env node
// cspell:ignore taskkill EBADENGINE
// disposable-site.mjs: provision a throwaway WordPress site around one plugin checkout, optionally
// answer one AJAX request against it, and remove the site.
//
// The site is WordPress Playground (`@wp-playground/cli`, run through `npx`), which needs Node and
// no Docker. Playground keeps its WordPress files in a directory it creates under the operating
// system's temporary directory, so the site is never inside any `wp-content/plugins` of a working
// site and a working site is never read or written. Playground removes that directory itself when
// it is stopped with SIGTERM (macOS, measured). On Windows the stop is `taskkill /t /f`, which
// does not let it (measured: 182 MB left in %TEMP%), so the script removes the one directory its
// own run created, by the exact path Playground printed (`--verbosity debug` prints it), after
// checking the path is a direct child of the temporary directory with Playground's name prefix.
// The plugin checkout is mounted into it at `/wordpress/wp-content/plugins/<slug>`; the checkout
// itself is the only host path the site can see.
//
// Usage (Node 20 or newer; the first run downloads the pinned CLI through npx):
//     node .claude/skills/disposable-wordpress-site/scripts/disposable-site.mjs <plugin-checkout>
//     node .../disposable-site.mjs <plugin-checkout> --ajax <action> [--ajax-data key=value ...]
//     node .../disposable-site.mjs <plugin-checkout> --hold
//
// Without `--hold` it starts the site, activates the plugin, optionally posts the AJAX action to
// `wp-admin/admin-ajax.php`, prints what came back, and removes the site. With `--hold` it prints
// the site's URL and keeps the site until interrupted (Ctrl+C), then removes it.
//
// Exit codes: 0 provisioned, answered and removed; 1 the plugin was not activated, the request
// failed or the site did not come up; 2 bad usage or an unsafe plugin path; 3 the site could not
// be removed (the port is still listening).
//
// Measured with `@wp-playground/cli@3.1.64` and a one-file probe plugin (one AJAX action
// open to visitors who are not logged in): macOS 26.6.2 (arm64), Node v26.12.1, exit 0 with HTTP 200, no `disposable-site-*` or
// `node-playground-cli-site-*` directory left; Windows 11, Node v22.22.3, exit 0 with HTTP 200 and no
// such directory left after the exact-path removal (UAMS-Web/uams-claude-skills#654). Not measured:
// Node 24, a forced failed start on Windows, and a real plugin checkout.

import { spawn, spawnSync } from 'node:child_process';
import { existsSync, mkdtempSync, readFileSync, readdirSync, realpathSync, rmSync, statSync, writeFileSync } from 'node:fs';
import { createServer } from 'node:net';
import { tmpdir } from 'node:os';
import { basename, dirname, join, resolve, sep } from 'node:path';
import { fileURLToPath } from 'node:url';

export const PLAYGROUND_VERSION = '3.1.64';
const READY_TIMEOUT_MS = 180_000;
const STOP_GRACE_MS = 8_000;

/**
 * Find the plugin's main file: the first PHP file in the checkout root whose header holds a
 * `Plugin Name:` line. Returns the file name, or null.
 */
export function findMainFile(dir) {
  for (const name of readdirSync(dir).sort()) {
    if (!name.endsWith('.php')) {
      continue;
    }

    const head = readFileSync(join(dir, name), 'utf8').slice(0, 8192);

    if (/^[ \t/*#@]*Plugin Name:/im.test(head)) {
      return name;
    }
  }

  return null;
}

/** The blueprint that activates the mounted plugin. */
export function buildBlueprint(slug, mainFile) {
  return {
    landingPage: '/',
    steps: [{ step: 'activatePlugin', pluginPath: `${slug}/${mainFile}` }],
  };
}

/**
 * The arguments for `npx`. The plugin is mounted with `--mount-dir-before-install <host> <vfs>`
 * (two values), because the `--mount-before-install <host>:<vfs>` form splits on the first colon
 * and a Windows drive letter (`C:\\plugin`) is rejected as `Invalid mount format`.
 */
export function buildNpxArgs({ port, blueprint, plugin, slug }) {
  return [
    '--yes',
    `@wp-playground/cli@${PLAYGROUND_VERSION}`,
    'server',
    '--verbosity',
    'debug',
    '--port',
    String(port),
    '--blueprint',
    blueprint,
    '--mount-dir-before-install',
    plugin,
    `/wordpress/wp-content/plugins/${slug}`,
  ];
}

/** Quote one argument for `cmd.exe`, which `npx.cmd` needs because Node will not spawn it directly. */
export function quoteForCmd(arg) {
  return /[\s"&|<>^()%!]/.test(arg) ? `"${arg.replace(/"/g, '""')}"` : arg;
}

/** A warning line when the Node version is below what the pinned CLI's dependencies declare, else null. */
export function nodeWarning(version) {
  const [major, minor] = version.replace(/^v/, '').split('.').map(Number);

  if (major > 24 || (major === 24 && minor >= 18)) {
    return null;
  }

  return `Node ${version} is below 24.18.0, which the dependencies of @wp-playground/cli@${PLAYGROUND_VERSION} declare (npm reports EBADENGINE); if the site does not come up, try Node 24.18 or newer`;
}

/** Parse argv into `{ plugin, ajax, ajaxData, hold, port }`, or `{ error }`. */
export function parseArgs(argv) {
  const out = { plugin: null, ajax: null, ajaxData: [], hold: false, port: null };

  for (let i = 0; i < argv.length; i++) {
    const arg = argv[i];

    if (arg === '--hold') {
      out.hold = true;
    } else if (arg === '--ajax' || arg === '--ajax-data' || arg === '--port') {
      const value = argv[++i];

      if (value === undefined || value.startsWith('--')) {
        return { error: `${arg} needs a value` };
      }

      if (arg === '--ajax') {
        out.ajax = value;
      } else if (arg === '--ajax-data') {
        if (!value.includes('=')) {
          return { error: '--ajax-data takes key=value' };
        }

        out.ajaxData.push(value);
      } else {
        out.port = Number(value);

        if (!Number.isInteger(out.port) || out.port < 1024 || out.port > 65535) {
          return { error: '--port takes an integer from 1024 to 65535' };
        }
      }
    } else if (arg.startsWith('--')) {
      return { error: `unknown option ${arg}` };
    } else if (out.plugin === null) {
      out.plugin = arg;
    } else {
      return { error: 'only one plugin checkout may be given' };
    }
  }

  if (out.plugin === null) {
    return { error: 'a plugin checkout path is required' };
  }

  if (out.ajaxData.length > 0 && out.ajax === null) {
    return { error: '--ajax-data needs --ajax' };
  }

  return out;
}

/**
 * Refuse a plugin path that is not a plain checkout directory holding a plugin. Returns an error
 * string, or null when the path is usable.
 */
export function checkPlugin(dir) {
  if (!existsSync(dir) || !statSync(dir).isDirectory()) {
    return `${dir} is not a directory`;
  }

  if (findMainFile(dir) === null) {
    return `${dir} holds no PHP file with a "Plugin Name:" header in its root`;
  }

  return null;
}

function freePort() {
  return new Promise((resolvePort, reject) => {
    const server = createServer();

    server.once('error', reject);
    server.listen(0, '127.0.0.1', () => {
      const { port } = server.address();

      server.close(() => resolvePort(port));
    });
  });
}

async function httpStatus(url, init) {
  try {
    const response = await fetch(url, { ...init, signal: AbortSignal.timeout(10_000) });

    return { status: response.status, body: await response.text() };
  } catch {
    return { status: 0, body: '' };
  }
}

async function listening(port) {
  const { status } = await httpStatus(`http://127.0.0.1:${port}/`);

  return status !== 0;
}

function stopTree(child) {
  if (child.exitCode !== null || child.pid === undefined) {
    return;
  }

  if (process.platform === 'win32') {
    spawnSync('taskkill', ['/pid', String(child.pid), '/t', '/f'], { stdio: 'ignore' });
  } else {
    try {
      process.kill(-child.pid, 'SIGTERM');
    } catch {
      child.kill('SIGTERM');
    }
  }
}

function killTree(child) {
  if (child.exitCode !== null || child.pid === undefined) {
    return;
  }

  if (process.platform !== 'win32') {
    try {
      process.kill(-child.pid, 'SIGKILL');
    } catch {
      child.kill('SIGKILL');
    }
  }
}

const SITE_PREFIX = 'node-playground-cli-site-';

/**
 * The exact site directory Playground created for a run, read from its debug output
 * (`Mount before WP install: /wordpress -> <dir>/wordpress`), or null. The directory is returned
 * only when it is a direct child of the temporary directory `tmp` and carries Playground's name
 * prefix, so no other path can be named by what the process printed.
 */
export function findSiteDir(output, tmp) {
  const match = /Mount before WP install: \/wordpress -> (.+)$/m.exec(output.replace(/\r/g, ''));

  if (match === null) {
    return null;
  }

  const dir = dirname(match[1].trim());

  if (!basename(dir).startsWith(SITE_PREFIX)) {
    return null;
  }

  try {
    return realpathSync.native(dirname(dir)) === realpathSync.native(tmp) ? dir : null;
  } catch {
    return null;
  }
}

/** Remove the scratch directory (the blueprint). Runs on every path out of `run`, failed starts included. */
export function removeScratch(dir, log = console.log) {
  try {
    rmSync(dir, { recursive: true, force: true, maxRetries: 5, retryDelay: 200 });
  } catch (error) {
    log(`disposable-site: could not remove ${dir}: ${error.message}`);
  }
}

/** Run the whole provision, request and removal. Returns the exit code. */
export async function run(argv, log = console.log) {
  const args = parseArgs(argv);

  if (args.error) {
    log(`disposable-site: ${args.error}`);

    return 2;
  }

  const plugin = resolve(args.plugin);
  const problem = checkPlugin(plugin);

  if (problem !== null) {
    log(`disposable-site: ${problem}`);

    return 2;
  }

  const scratch = mkdtempSync(join(tmpdir(), 'disposable-site-'));

  if ((scratch + sep).includes(`${sep}wp-content${sep}`)) {
    rmSync(scratch, { recursive: true, force: true });
    log('disposable-site: the temporary directory is inside a wp-content directory; refusing');

    return 2;
  }

  const slug = basename(plugin);
  const mainFile = findMainFile(plugin);
  const blueprint = join(scratch, 'blueprint.json');
  const port = args.port ?? (await freePort());

  writeFileSync(blueprint, JSON.stringify(buildBlueprint(slug, mainFile)));

  const warning = nodeWarning(process.version);

  if (warning !== null) {
    log(`disposable-site: warning: ${warning}`);
  }

  const windows = process.platform === 'win32';
  const npxArgs = buildNpxArgs({ port, blueprint, plugin, slug });
  const child = spawn(
    windows ? 'npx.cmd' : 'npx',
    windows ? npxArgs.map(quoteForCmd) : npxArgs,
    { cwd: scratch, detached: process.platform !== 'win32', shell: process.platform === 'win32', stdio: ['ignore', 'pipe', 'pipe'] },
  );
  let output = '';

  child.stdout.on('data', (chunk) => (output += chunk));
  child.stderr.on('data', (chunk) => (output += chunk));

  let code = 0;
  let stopping = false;
  const stop = async () => {
    if (stopping) {
      return;
    }

    stopping = true;
    stopTree(child);

    const deadline = Date.now() + STOP_GRACE_MS;

    while (Date.now() < deadline && child.exitCode === null) {
      await new Promise((r) => setTimeout(r, 200));
    }

    killTree(child);
  };

  process.once('SIGINT', () => void stop());
  process.once('SIGTERM', () => void stop());

  try {
    const started = Date.now();
    let up = false;

    while (Date.now() - started < READY_TIMEOUT_MS && child.exitCode === null) {
      if (/Ready! WordPress is running/.test(output) && (await listening(port))) {
        up = true;
        break;
      }

      await new Promise((r) => setTimeout(r, 500));
    }

    if (!up) {
      log(`disposable-site: the site did not come up on port ${port}`);
      log(`--- full Playground output ---\n${output.trim()}\n--- end of output ---`);

      return 1;
    }

    log(`site up on http://127.0.0.1:${port} after ${Math.round((Date.now() - started) / 1000)}s; plugin ${slug}/${mainFile} activated`);

    if (args.ajax !== null) {
      const form = new URLSearchParams({ action: args.ajax });

      for (const pair of args.ajaxData) {
        const at = pair.indexOf('=');

        form.append(pair.slice(0, at), pair.slice(at + 1));
      }

      const answer = await httpStatus(`http://127.0.0.1:${port}/wp-admin/admin-ajax.php`, {
        method: 'POST',
        headers: { 'content-type': 'application/x-www-form-urlencoded' },
        body: form,
      });

      log(`ajax ${args.ajax}: HTTP ${answer.status} ${answer.body.slice(0, 500)}`);

      if (answer.status !== 200) {
        code = 1;
      }
    }

    if (args.hold) {
      log('holding; press Ctrl+C to remove the site');
      await new Promise((r) => child.once('exit', r));
    }
  } finally {
    await stop();
    removeScratch(scratch, log);

    const siteDir = findSiteDir(output, tmpdir());

    if (siteDir !== null && existsSync(siteDir)) {
      removeScratch(siteDir, log);
    }
  }

  const stillUp = await listening(port);

  if (stillUp) {
    log(`disposable-site: port ${port} is still listening after the stop`);

    return 3;
  }

  log(`site removed; port ${port} is closed`);

  return code;
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  process.exitCode = await run(process.argv.slice(2));
}
