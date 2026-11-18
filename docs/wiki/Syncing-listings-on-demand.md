The queue runs a sync a couple of minutes after a profile is saved. To sync now, to check a silo without waiting, or to run the closing scan, use the WP-CLI command on the site.

Written against v2.5.0.

## Steps

1. **Sync one provider or location:**

   ```bash
   wp uamswp gbp sync --post=<post id>
   ```

   A location also covers its published child locations and the providers at them.

2. **Or sync every published provider and location:**

   ```bash
   wp uamswp gbp sync --all
   ```

3. **Narrow or rehearse the run when needed.** `--silo=uams`, `--silo=arkansas-childrens` or `--silo=baptist-health` limits the run to one silo. `--dry-run` runs every silo that is not Off as a dry run, even a Live silo on the production site.

4. **Read the output.** One line per listing, then a count per action:

   ```text
   <store code>  uams  dry-run  update  Dry run, no write made
   Done: 1 update
   ```

   The columns are the store code, silo, mode, action and reason. The command exits `1` when any listing ends in review or error, when a create is held back by the daily budget (it prints when creates resume), when Google rate limits the run, when the credentials are missing, when another sync holds the run lock, when `--silo` names an unknown silo, when there is nothing to sync, when the post is not a published provider or location, when Google refuses the request (`Stopped: <message>`), or when neither or both of `--post` and `--all` is given (`Pass exactly one of --post=<id> or --all.`). Otherwise it exits `0`.

5. **Run the closing scan now, if needed:**

   ```bash
   wp uamswp gbp sync --orphans --dry-run
   ```

   It reports every orphaned listing and how many days it has left; without `--dry-run`, due listings in a Live silo are closed. `--post`, `--all` and `--silo` do not apply to it.

The command does not use the change check the queue relies on, so it always compares each listing with Google, even when the profile has not changed since its last sync.

## Two more commands

- `wp uamswp gbp backfill-ids` matches posts to their listings by store code, then by Google's customer ID, and prints what it would store. Add `--apply` to store the matches.
- `wp uamswp gbp cid-report` lists, as `NO LISTING` lines, the providers marked as having listings on Google whose customer IDs match no listing, and, as `NO CID` lines, the listings whose customer ID is on no provider or location. It changes nothing.

## Reference

- [Business Profile sync](Business-Profile-sync.md): silos and modes, exclusions, the daily create budget, the activity log and the closing scan.
- [Troubleshooting](Troubleshooting.md): what each reason in the output means.
