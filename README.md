# UAMSWP-Find-a-Doc

## Editor groups

Doc Profile Editors (`doc_editor`) can edit a provider or location only when they are its author or a member of one of its **editor groups**. Editor groups are a private taxonomy on providers and locations (Providers > Editor Groups). Each group has a primary editor and a list of editors, all with the same rights: edit, hide, or unpublish the records in the group. Publishing stays with administrators, and so does deleting: Doc Profile Admins (`doc_admin`) can edit any provider or location but cannot delete one.

- **Assign a group to a record:** administrators and `doc_admin` tick it in the Editor Groups box on the provider or location edit screen (or in Quick Edit). `doc_editor` accounts see the box but cannot change it.
- **Groups for a service line:** edit the service line term (Providers > Service Lines) and pick its editor group. Every provider and location in that service line picks the group up when it is saved, and follows if its service line changes; groups added by hand on the record are kept. The Editor Groups screen has an "Apply service line mapping" button that does this for every existing record at once.
- **Groups without a service line** (department sections, stand-alone teams) are plain terms that are assigned by hand.
