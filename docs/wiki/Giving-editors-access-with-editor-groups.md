A Doc Profile Editor can change only the providers and locations they wrote, unless an editor group gives them more. This guide puts an editor in charge of a set of profiles, either a whole service line or a hand-picked set.

Written against v2.5.0. Creating and assigning groups needs an administrator account.

## Steps

1. **Create the group.** Open **Providers > Editor Groups** (the same screen is under **Locations**). Add a group named for what it covers, for example a department or a team.

2. **Choose its editors.** Edit the group and pick a **Primary editor** and any number of **Editors**. Only users with the Doc Profile Editor or Doc Profile Admin role are offered. Every person listed has the same rights over the group's profiles.

3. **Attach the group to its profiles**, in one of two ways:
   - **A whole service line:** open **Providers > Service Lines**, edit the service line, and choose the group in **Editor group**. Then, on the Editor Groups screen, select **Apply service line mapping** once, so that existing providers and locations pick the group up. From then on, every provider and location in that service line gets the group when it is saved, and follows if its service line changes.
   - **Profiles without a service line** (a department section, a stand-alone team): tick the group in the **Editor Groups** box on each provider or location edit screen, or in Quick Edit.

   A group added by hand stays on a profile when the service line mapping is applied or the profile is saved.

4. **Check what the editor sees.** Signed in as the editor, the Providers and Locations lists show their own profiles and the profiles of their groups, and each of those opens for editing.

**Note:** the editor can edit and unpublish the group's profiles but cannot publish or delete them; those stay with administrators. A group does not reach a profile in the Private status written by someone else.

## Reference

- [Roles and editor groups](Roles-and-editor-groups.md): what each role can do, and how a group changes it.
- [Content types](Content-types.md): the Service Lines taxonomy and the other taxonomies on providers and locations.
