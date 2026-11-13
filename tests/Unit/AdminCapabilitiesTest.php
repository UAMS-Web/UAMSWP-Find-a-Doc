<?php

/**
 * Administrator grants and synchronization of previously stored Doc roles.
 *
 * Two approved equivalents are ignored: the Doc role map holds one role, so
 * `continue` and `break` end the synchronization loop the same way when it is
 * absent; and iterating a post type's capability object reads the same public
 * properties as its array cast.
 *
 * @command  vendor/bin/pest tests/Unit/AdminCapabilitiesTest.php
 */

declare(strict_types=1);

use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    // Load callbacks without running their WordPress registrations.
    when('add_action')->justReturn(true);
    when('add_filter')->justReturn(true);
    require_once dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

    // Model observable role state rather than capability-write call counts.
    $this->role = new class
    {
        /** @var array<string, bool> Current grants and explicit denials. */
        public array $capabilities = ['custom_permission' => true, 'custom_denial' => false];

        /** Grant or explicitly deny a capability. */
        public function add_cap(
            string $capability,
            bool $grant = true,
        ): void {
            $this->capabilities[$capability] = $grant;
        }

        /** Remove an existing capability entry. */
        public function remove_cap(string $capability): void
        {
            unset($this->capabilities[$capability]);
        }
    };
});

test('administrator grants retain legacy names and include registered post-type capabilities', function (string $type): void {
    // Keep one granted primitive and one denied primitive to exercise both paths.
    $this->role->capabilities['registered_existing'] = true;
    $this->role->capabilities['registered_'.$type] = false;
    when('get_role')->alias(fn (string $name): ?object => $name === 'administrator' ? $this->role : null);
    when('get_post_type_object')->alias(fn (string $name): ?object => $name === $type ? (object) [
        'cap' => (object) ['edit_posts' => 'registered_existing', 'edit_private_posts' => 'registered_'.$type],
    ] : null);

    // Pin the historical compatibility names independently of runtime registration.
    $legacy = [
        'edit_others_posts',
        'edit_physician', 'read_physician', 'delete_physician', 'delete_published_physicians',
        'edit_physicians', 'edit_others_physicians', 'edit_published_physicians', 'publish_physicians', 'read_private_physicians',
        'edit_location', 'read_location', 'delete_location', 'edit_locations', 'edit_others_locations', 'publish_locations', 'read_private_locations',
        'edit_expertise', 'read_expertise', 'delete_expertise', 'edit_expertises', 'edit_others_expertises', 'publish_expertises', 'read_private_expertises',
        'edit_condition', 'read_condition', 'delete_condition', 'edit_conditions', 'edit_others_conditions', 'publish_conditions', 'read_private_conditions',
        'edit_treatment', 'read_treatment', 'delete_treatment', 'edit_treatments', 'edit_others_treatments', 'publish_treatments', 'read_private_treatments',
        'edit_clinical-resource', 'read_clinical-resource', 'delete_clinical-resource', 'edit_clinical-resources',
        'edit_others_clinical-resources', 'publish_clinical-resources', 'read_private_clinical-resources',
        'edit_clinical_resource', 'read_clinical_resource', 'delete_clinical_resource', 'edit_clinical_resources',
        'edit_others_clinical_resources', 'publish_clinical_resources', 'read_private_clinical_resources',
    ];
    $expected = ['custom_permission' => true, 'custom_denial' => false, 'registered_existing' => true, 'registered_'.$type => true]
        + array_fill_keys($legacy, true);

    // Compare all resulting permissions, preserving unrelated grants and denials.
    add_theme_caps();
    ksort($expected);
    ksort($this->role->capabilities);
    expect($this->role->capabilities)->toBe($expected);
})->with(['provider', 'location', 'expertise', 'condition', 'treatment', 'clinical-resource']);

test('stored Doc admin grants are repaired without changing unrelated permissions', function (
    ?bool $privateGrant,
    bool $deleteGrant,
): void {
    // Seed obsolete delete grants, with a separate existing-denial case.
    $this->role->capabilities += array_fill_keys([
        'delete_physician', 'delete_location',
        'delete_physicians', 'delete_others_physicians', 'delete_published_physicians', 'delete_private_physicians',
        'delete_locations', 'delete_others_locations', 'delete_published_locations', 'delete_private_locations',
    ], true);
    $this->role->capabilities['delete_private_locations'] = $deleteGrant;

    // Exercise missing, explicitly denied, and already granted private editing.
    if ($privateGrant !== null) {
        $this->role->capabilities['edit_private_physicians'] = $privateGrant;
        $this->role->capabilities['edit_private_locations'] = $privateGrant;
    }

    when('get_role')->alias(fn (string $name): ?object => $name === 'doc_admin' ? $this->role : null);
    $expected = [
        'custom_permission' => true,
        'custom_denial' => false,
        'edit_private_physicians' => true,
        'edit_private_locations' => true,
    ];
    if (! $deleteGrant) {
        $expected['delete_private_locations'] = false;
    }

    ksort($expected);

    // Repeat synchronization to cover both stale and already-correct role state.
    foreach ([1, 2] as $pass) {
        uamswp_fad_sync_doc_role_caps();
        ksort($this->role->capabilities);
        expect($this->role->capabilities)->toBe($expected);
    }
})->with([
    'missing' => [null, true],
    'denied' => [false, true],
    'granted' => [true, true],
    'existing deletion denial' => [false, false],
]);

test('synchronization tolerates an absent Doc admin role', function (): void {
    when('get_role')->justReturn();

    expect(uamswp_fad_sync_doc_role_caps())->toBeNull();
});
