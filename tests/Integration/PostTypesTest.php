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
