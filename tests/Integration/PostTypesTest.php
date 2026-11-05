<?php

/**
 * The plugin's content types and editor features once WordPress has booted.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Integration;

use UamswpFindADoc\Tests\Support\IntegrationTestCase;

/**
 * Proves the Integration suite end to end: the bootstrap boots WordPress, loads the
 * plugin on `muplugins_loaded`, and `init` has run by the time a test starts.
 *
 * @command  composer test:integration -- --filter PostTypesTest
 */
final class PostTypesTest extends IntegrationTestCase
{
    /**
     * The post types the plugin registers at load.
     *
     * @return array<string, array{string, string, bool, array<int, string>}>
     */
    public static function registeredPostTypes(): array
    {
        return [
            'provider' => ['provider', 'provider', false, ['title', 'author', 'thumbnail', 'revisions']],
            'location' => ['location', 'location', true, ['title', 'author', 'thumbnail', 'revisions', 'page-attributes']],
            'expertise' => ['expertise', 'expertise', true, ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions']],
            'condition' => ['condition', 'condition', false, ['title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields']],
            'treatment' => ['treatment', 'treatment', false, ['title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields']],
            'resource' => ['clinical-resource', 'clinical_resource', false, ['title', 'author', 'revisions', 'custom-fields']],
        ];
    }

    /**
     * @dataProvider registeredPostTypes
     *
     * @param  array<int, string>  $supports
     */
    public function test_the_plugin_registers_its_post_type_at_load(string $post_type, string $rest_base, bool $hierarchical, array $supports): void
    {
        $type = get_post_type_object($post_type);

        $this->assertNotNull($type, "The {$post_type} post type is not registered.");
        $this->assertTrue($type->public);
        $this->assertTrue($type->publicly_queryable);
        $this->assertTrue($type->show_in_rest);
        $this->assertSame($rest_base, $type->rest_base);
        $this->assertSame($hierarchical, $type->hierarchical);

        foreach (['title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes'] as $feature) {
            $this->assertSame(in_array($feature, $supports, true), post_type_supports($post_type, $feature), "{$post_type}: {$feature}");
        }
    }

    public function test_a_post_type_the_plugin_does_not_register_is_reported_missing(): void
    {
        $this->assertFalse(post_type_exists('uamswp-find-a-doc-not-a-post-type'));
    }

    public function test_only_providers_receive_headshot_labels_after_registration(): void
    {
        $labels = get_post_type_object('provider')->labels;

        $this->assertSame('Providers', $labels->name);
        $this->assertSame('Add New Provider', $labels->add_new_item);
        $this->assertSame('Headshot', $labels->featured_image);
        $this->assertSame('Set headshot', $labels->set_featured_image);
        $this->assertSame('Remove headshot', $labels->remove_featured_image);
        $this->assertSame('Use as headshot', $labels->use_featured_image);
        $this->assertSame('Featured image', get_post_type_object('location')->labels->featured_image);
    }

    public function test_the_legacy_rest_query_vars_hook_adds_metadata_variables(): void
    {
        $this->assertSame(
            ['author', 'search', 'meta_key', 'meta_value', 'meta_compare'],
            apply_filters('rest_query_vars', ['author', 'search'])
        );
    }

    /**
     * Remove only the configured side metaboxes from the six content editors.
     */
    public function test_taxonomy_metabox_removal_preserves_other_contexts_and_screens(): void
    {
        // Save the complete registry, including an originally unset global.
        $hadRegistry = \array_key_exists('wp_meta_boxes', $GLOBALS);
        global $wp_meta_boxes;

        $original = $wp_meta_boxes;
        $screens = ['provider', 'location', 'expertise', 'clinical-resource', 'condition', 'treatment'];
        $ids = [
            'conditiondiv', 'treatmentdiv', 'specialtydiv', 'departmentdiv',
            'service_linediv', 'degreediv', 'patient_typediv', 'clinical_titlediv',
            'clinical_admin_titlediv', 'affiliationdiv', 'institute_affiliationdiv',
            'languagediv', 'medical_termsdiv', 'medical_termdiv', 'academic_positiondiv',
            'academic_collegediv', 'schooldiv', 'residency_yeardiv', 'academic_departmentdiv',
            'boarddiv', 'certifying_bodydiv', 'associationdiv', 'educationtypediv', 'portaldiv',
            'academic_titlediv', 'academic_admin_titlediv', 'recognitiondiv', 'regiondiv',
            'location_typediv', 'gmb_cat_providerdiv', 'gmb_cat_locationdiv', 'buildingdiv',
            'parkingdiv', 'brand_organizationdiv', 'brand_organization_uamsdiv',
            'tagsdiv-recognition', 'tagsdiv-medical_procedures', 'custom-post-type-onomies-locations',
        ];

        try {
            // Test slots separately: WordPress moves repeated IDs across slots.
            $this->assertSame(10, has_action('admin_menu', 'remove_provider_meta'));
            $this->assertSame(10, has_action('admin_head', 'acf_hide_title'));
            foreach ([['side', 'default'], ['side', 'core'], ['normal', 'default'], ['advanced', 'default']] as [$context, $priority]) {
                $wp_meta_boxes = [];
                foreach ([...$screens, 'post'] as $screen) {
                    foreach ([...$ids, 'keep-custom-box', 'tagsdiv-region'] as $id) {
                        add_meta_box($id, 'Fixture '.$id, '__return_empty_string', $screen, $context, $priority);
                    }
                }

                $seeded = $wp_meta_boxes;

                // Confirm fixtures exist before invoking the actual removal callback.
                foreach ($screens as $screen) {
                    $this->assertCount(40, $seeded[$screen][$context][$priority]);
                }

                remove_provider_meta();
                foreach ($screens as $screen) {
                    if ($context === 'side') {
                        foreach ($ids as $id) {
                            $this->assertFalse($wp_meta_boxes[$screen]['side'][$priority][$id], $screen.': '.$id);
                        }

                        foreach (['keep-custom-box', 'tagsdiv-region'] as $id) {
                            $this->assertSame($seeded[$screen]['side'][$priority][$id], $wp_meta_boxes[$screen]['side'][$priority][$id]);
                        }
                    } else {
                        $this->assertSame($seeded[$screen][$context], $wp_meta_boxes[$screen][$context]);
                    }
                }

                $this->assertSame($seeded['post'], $wp_meta_boxes['post']);
            }
        } finally {
            // Restore the global registry even when a contract assertion fails.
            if ($hadRegistry) {
                $wp_meta_boxes = $original;
            } else {
                unset($GLOBALS['wp_meta_boxes']);
            }
        }
    }

    /**
     * Restore the registered types' capabilities on a real administrator role.
     */
    public function test_administrator_receives_every_registered_content_capability(): void
    {
        // Capture role state so both database and in-memory grants can be restored.
        $this->assertSame(10, has_action('admin_init', 'add_theme_caps'));
        $administrator = get_role('administrator');
        $this->assertNotNull($administrator);
        $original = $administrator->capabilities;
        $roles = wp_roles();
        $originalRegistry = $roles->roles;
        $originalStored = get_option($roles->role_key);

        try {
            // Remove registered grants to prevent WordPress defaults masking omissions.
            $capabilities = [];
            foreach (self::registeredPostTypes() as [$type]) {
                $capabilities = array_merge($capabilities, (array) get_post_type_object($type)->cap);
            }

            $capabilities = array_unique($capabilities);
            foreach ($capabilities as $capability) {
                $administrator->remove_cap($capability);
            }

            $administrator->add_cap('uamswp_test_custom_cap');
            $administrator->add_cap('uamswp_test_custom_denial', false);

            // Invoke the registered callback and inspect actual WordPress role state.
            add_theme_caps();
            foreach ($capabilities as $capability) {
                $this->assertTrue($administrator->has_cap($capability), $capability);
            }

            $this->assertTrue($administrator->has_cap('uamswp_test_custom_cap'));
            $this->assertArrayHasKey('uamswp_test_custom_denial', $administrator->capabilities);
            $this->assertFalse($administrator->capabilities['uamswp_test_custom_denial']);

            // Verify persistence independently of the object the callback just mutated.
            $stored = get_option($roles->role_key);
            foreach ($capabilities as $capability) {
                $this->assertTrue($roles->roles['administrator']['capabilities'][$capability] ?? false, $capability);
                $this->assertTrue($stored['administrator']['capabilities'][$capability] ?? false, $capability);
            }
        } finally {
            // Restore globals as well as stored role data, including on assertion failure.
            foreach (array_keys($administrator->capabilities) as $capability) {
                $administrator->remove_cap($capability);
            }

            foreach ($original as $capability => $grant) {
                $administrator->add_cap($capability, $grant);
            }
        }

        // Confirm cleanup restores every representation, including explicit denials.
        $this->assertSame($original, $administrator->capabilities);
        $this->assertSame($originalRegistry, $roles->roles);
        $this->assertSame($originalStored, get_option($roles->role_key));
    }

    /**
     * Taxonomies actually hooked to init; condition and treatment taxonomies are not enabled.
     *
     * @return array<string, array{string, array<int, string>, bool, string}>
     */
    public static function registeredTaxonomies(): array
    {
        return [
            'service line' => ['service_line', ['location', 'provider'], true, 'service_line'],
            'degree' => ['degree', ['provider'], false, 'medical_degree'],
            'patient type' => ['patient_type', ['location', 'provider'], true, 'patient_type'],
            'clinical title' => ['clinical_title', ['provider'], true, 'clinical_title'],
            'clinical admin title' => ['clinical_admin_title', ['provider'], true, 'clinical_admin_title'],
            'affiliation' => ['affiliation', ['provider'], false, 'affiliation'],
            'institute affiliation' => ['institute_affiliation', ['provider'], false, 'institute_affiliation'],
            'language' => ['language', ['location', 'provider'], false, 'language'],
            'academic position' => ['academic_position', ['provider'], true, ''],
            'academic college' => ['academic_college', ['provider'], true, ''],
            'school' => ['school', ['provider'], false, ''],
            'residency year' => ['residency_year', ['provider'], false, ''],
            'academic department' => ['academic_department', ['provider'], true, ''],
            'board' => ['board', ['provider'], false, ''],
            'certifying body' => ['certifying_body', ['provider'], false, ''],
            'association' => ['association', ['provider'], false, ''],
            'education' => ['educationtype', ['provider'], false, ''],
            'portal' => ['portal', ['location', 'provider'], false, 'portal'],
            'academic title' => ['academic_title', ['provider'], true, 'academic_title'],
            'academic admin title' => ['academic_admin_title', ['provider'], true, 'academic_admin_title'],
            'recognition' => ['recognition', ['provider'], false, ''],
            'region' => ['region', ['location'], true, ''],
            'location type' => ['location_type', ['location'], true, ''],
            'provider Google category' => ['gmb_cat_provider', ['provider'], true, ''],
            'location Google category' => ['gmb_cat_location', ['location'], true, ''],
            'building' => ['building', ['location'], true, ''],
            'parking' => ['parking', ['location'], false, ''],
            'UAMS brand organization' => ['brand_organization_uams', ['location', 'provider'], true, ''],
            'brand organization' => ['brand_organization', ['location', 'provider'], true, ''],
        ];
    }

    /**
     * @dataProvider registeredTaxonomies
     *
     * @param  array<int, string>  $object_types
     */
    public function test_taxonomies_retain_their_content_associations_hierarchy_and_rest_exposure(string $name, array $object_types, bool $hierarchical, string $rest_base): void
    {
        $taxonomy = get_taxonomy($name);

        $this->assertNotFalse($taxonomy, "The {$name} taxonomy is not registered.");
        $this->assertEqualsCanonicalizing($object_types, $taxonomy->object_type);
        $this->assertTrue($taxonomy->public);
        $this->assertTrue($taxonomy->show_ui);
        $this->assertSame($hierarchical, $taxonomy->hierarchical);
        $this->assertSame($rest_base !== '', (bool) $taxonomy->show_in_rest);
        $this->assertSame($rest_base, (string) $taxonomy->rest_base);
    }

    public function test_disabled_legacy_taxonomies_are_not_registered(): void
    {
        foreach (['condition', 'treatment', 'specialty', 'department', 'medical_term'] as $name) {
            $this->assertFalse(taxonomy_exists($name), $name);
        }
    }

    public function test_the_expected_rest_collections_are_registered_and_disabled_taxonomies_stay_unexposed(): void
    {
        $routes = rest_get_server()->get_routes();

        foreach (self::registeredPostTypes() as [$type, $rest_base]) {
            $this->assertArrayHasKey('/wp/v2/'.$rest_base, $routes, $type);
        }

        foreach (self::registeredTaxonomies() as [$name, $object_types, $hierarchical, $rest_base]) {
            if ($rest_base !== '') {
                $this->assertArrayHasKey('/wp/v2/'.$rest_base, $routes, $name);
            } else {
                $this->assertArrayNotHasKey('/wp/v2/'.$name, $routes, $name);
            }
        }
    }
}

// cspell:ignore uamswp
