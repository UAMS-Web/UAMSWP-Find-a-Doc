The plugin adds two roles for the people who maintain profiles, and a private taxonomy, editor groups, that widens what a Doc Profile Editor can change. For the steps to set one up, see [Giving editors access with editor groups](Giving-editors-access-with-editor-groups.md).

Written against v2.5.0.

## Roles

| Role | Slug | Can | Cannot |
| --- | --- | --- | --- |
| Doc Profile Editor | `doc_editor` | Edit their own providers and locations and those of their editor groups, including published ones, and so unpublish them. Upload files. Read private profiles. | Edit anyone else's profiles, publish, delete, or edit a private profile they did not write. |
| Doc Profile Admin | `doc_admin` | Edit any provider or location, including published and private ones. Upload files. | Publish or delete. |
| Administrator | `administrator` | Everything on providers, locations, areas of expertise, conditions, treatments and clinical resources, and every Find-a-Doc settings screen. | |

The plugin removes the Doc Profile Admin's delete rights again on every request, so a delete right added to the role by other means does not last.

**Status dropdown.** Edit Flow normally hides the Status dropdown on a published profile from anyone who cannot publish. The plugin brings it back on the provider and location edit screens, so both roles can move a published profile to Inactive or another custom status there instead of only through Quick Edit.

## Editor groups

The **Editor Groups** taxonomy (`editor_group`) is on providers and locations, under both menus. It is private: it has no public pages and is not queried on the front end.

| Field | On | Holds |
| --- | --- | --- |
| Primary editor | an editor group | one Doc Profile Editor or Doc Profile Admin |
| Editors | an editor group | any number of Doc Profile Editors or Doc Profile Admins |
| Editor group | a service line | the editor group every provider and location in that service line gets |

- **A group member edits as if they were the profile's author**, for every provider and location carrying the group. The editor's profile lists show their own profiles and their groups' profiles.
- **Creating and editing groups** needs an administrator. **Assigning a group to a profile** needs the right to edit others' profiles, so administrators and Doc Profile Admins can tick the box; a Doc Profile Editor sees it but cannot change it.
- **Service line mapping.** When a provider or location is saved, the groups that come from its service lines are worked out again. Groups added by hand are kept, and a group that came from a service line the profile no longer has is removed. **Apply service line mapping**, on the Editor Groups screen, does the same for every provider and location at once, in any status except Trash, and reports how many changed.
