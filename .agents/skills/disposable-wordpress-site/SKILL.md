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

## What it does and does not touch

- The site is WordPress Playground (`@wp-playground/cli`, pinned in the script), which runs on
  Node with SQLite. Its files live in a directory it creates under the operating system's
  temporary directory and removes when stopped, so the site is never inside a `wp-content/plugins`
  of a working site.
- The plugin checkout is the only host path the site sees. It is mounted read-write, so a
  plugin that writes into its own folder writes into the checkout.
- The site has no MySQL, no mail and no cron daemon. A check that needs MySQL-specific behavior
  is not covered by this site.

## Which option, and why

`wp-env` (needs Docker) and Playground (needs Node) were the two candidates. Measurements, with
the platform of each run, are recorded on the ticket that added this skill. Playground was chosen
for Node-only prerequisites. `wp-env` was not measured (Docker is not installed on the measuring
machine, macOS). The script ran end to end on macOS only. On Windows 11 (Node v22.22.3) the first
version failed at the mount (`Invalid mount format`, because a drive-letter path breaks the
`host:vfs` form); the script now uses `--mount-dir-before-install`, and a Windows run of that
version has not been made. Node below 24.18.0 prints a warning, because the dependencies of the
pinned CLI declare that engine; whether it blocks the site on Windows is not established.
