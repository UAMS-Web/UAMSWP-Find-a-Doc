---
name: disposable-wordpress-site
description: >-
  Provision a throwaway WordPress site around one plugin checkout, answer a request against it
  (an AJAX action, a rendered page), and remove it, with one script that never touches a working
  site. Activate when a test-plan item cannot be an integration test (a rendered page, a click
  path, an AJAX round trip) and the reviewing check needs somewhere safe to run it, when a
  reviewer must exercise a plugin checkout that sits inside a working Herd site, or when asked to
  "spin up a WordPress" for a plugin.
---
<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/disposable-wordpress-site/SKILL.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): wordpress-plugin.
-->
<!-- cspell:ignore innodb nopriv referer -->

# A disposable WordPress site for one plugin checkout

## When a reviewer uses it

A test-plan item that needs a running WordPress (an AJAX action, a rendered page) and has no
integration test behind it. Run it from the reviewing seat against the plugin checkout under
review. **Never point a check at the working Herd site the checkouts sit inside**: this script
builds its own site and removes it.

## Run it

From the repository root (Node 20 or newer; the first run downloads the pinned CLI through
`npx`, which needs network access):

```bash
node .claude/skills/disposable-wordpress-site/scripts/disposable-site.mjs <plugin-checkout> --ajax <action>
```

It mounts the checkout into a new site, activates the plugin, posts the action to
`wp-admin/admin-ajax.php`, prints the answer, and removes the site. `--ajax-data key=value`
adds form fields; `--hold` keeps the site up (the URL is printed) until Ctrl+C. Exit `0` means
provisioned, answered with HTTP 200 and removed; `1` the site did not come up, the plugin did
not activate or the request failed; `2` bad usage; `3` the port was still listening after the
stop.

The request is posted without logging in, so `--ajax` reaches only an action the plugin registers
with `wp_ajax_nopriv_`. An action that checks a nonce (`check_ajax_referer`) answers `403` with
`-1`, and the script then exits `1`: that is the plugin's own handler refusing the request, not a
site failure, and a nonce cannot be supplied from outside. An action nobody registered answers
`400` with `0`, which tells the two apart. A real plugin checkout (`UAMSWP-People-Showcase`, no
`vendor/`) activated and answered `403 -1` on macOS. A plugin that loads classes through a
Composer autoloader has not been run through the script, so whether it needs `composer install
--no-dev` in the checkout before mounting is not measured.

## What it does and does not touch

- The site is WordPress Playground (`@wp-playground/cli`, pinned in the script), which runs on
  Node with SQLite. Its files live in a directory it creates under the operating system's
  temporary directory, so the site is never inside a `wp-content/plugins` of a working site.
  Playground removes it when stopped with SIGTERM (macOS). On Windows the stop is a forced kill, so
  the script removes the one directory its own run created, by the exact path Playground printed.
- The plugin checkout is the only host path the site sees. It is mounted read-write, so a
  plugin that writes into its own folder writes into the checkout.
- The site has no MySQL, no mail and no cron daemon. A check that needs MySQL-specific behavior
  is not covered by this site, and a plugin whose activation requires MySQL (`uamswp-search`
  refuses to activate without `innodb_ft_min_token_size=3`) cannot be exercised on it.

## Which option, and why

`wp-env` (needs Docker) and Playground (needs Node) were the two candidates. Measurements, with
the platform of each run, are recorded on the ticket that added this skill. Playground was chosen
for Node-only prerequisites. `wp-env` was not measured (Docker is not installed on the measuring
machine, macOS). A one-file probe plugin was provisioned, activated, answered with HTTP 200 and
removed on macOS (Node v26.12.1) and on Windows 11 (Node v22.22.3). The Windows teardown left
Playground's site directory behind (182 MB) until the script began removing it by exact path; a
Windows run after that change left no site directory in the temporary directory, and a macOS run at
the same revision left none either. Node below 24.18.0 prints a warning, because the
dependencies of the pinned CLI declare that engine; the Windows run on Node 22 worked regardless.
