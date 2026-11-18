At the end of this walkthrough the Business Profile sync is configured, every silo has been checked in a dry run, and one silo writes live listings. It needs an administrator account on the site and access to the Google Cloud project and the Business Profile accounts the listings belong to.

Written against v2.5.0.

1. **Prepare the Google side.** In the Google Cloud project, enable the My Business Account Management, My Business Business Information and My Business APIs, and create an OAuth client. Issue a refresh token for an account that manages the listings, with the `https://www.googleapis.com/auth/business.manage` scope.

2. **Add the credentials to the site.** Define the three constants in `wp-config.php`, or set environment variables of the same names:

   ```php
   define('GOOGLE_BUSINESS_PROFILE_CLIENT_ID', '<client id>');
   define('GOOGLE_BUSINESS_PROFILE_CLIENT_SECRET', '<client secret>');
   define('GOOGLE_BUSINESS_PROFILE_REFRESH_TOKEN', '<refresh token>');
   ```

   Open **Find-a-Doc Settings > Business Profile Sync**. The credentials table shows each name as *Set*. A name shown as *Missing* is not defined where the site can read it.

3. **Check the site type.** Under *Silos*, the screen says whether this site is production. Only the production site writes to Google; on any other site a silo set to Live runs as a dry run, so a test site is safe to work on.

4. **Leave every silo in Dry run and queue everything.** Every silo starts in Dry run. Select **Queue a sync for all providers and locations**. The notice says how many providers and locations were queued, and the jobs run over the next minutes, 15 seconds apart.

5. **Read the dry run.** Under *Activity*, **Recent activity** lists what each listing would get (*Dry run, no write made* or *Dry run, no listing created*), and **Needs attention** lists every listing left for review or that failed. Filter by silo to take one silo at a time. Resolve each entry before going further; [Troubleshooting](Troubleshooting.md) lists the messages and their fixes.

6. **Set one silo to Live.** On the production site, choose **Live** for that silo and select **Save silo modes**. The notice *Settings saved.* confirms it. From now on, saving a provider or location in that silo updates or creates its listings about two minutes later.

7. **Watch the first live writes.** The *Business Profile Sync* widget on the WordPress Dashboard shows the status, the creates sent today against the daily budget of 80, and the queue. Entries in **Recent activity** now read *Updated*, *Created* or *Photos sent*.

**Note:** a silo set to Live also closes orphaned listings in that silo once a day, 14 days after their profile was unpublished. Read [Closing listings for retired profiles](Business-Profile-sync.md#closing-listings-for-retired-profiles) before switching a silo on.

Next:

- [Syncing listings on demand](Syncing-listings-on-demand.md) to sync one profile at once from WP-CLI.
- [Business Profile sync](Business-Profile-sync.md) for everything the sync writes and every setting.
