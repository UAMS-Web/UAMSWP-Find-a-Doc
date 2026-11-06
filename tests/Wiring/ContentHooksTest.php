<?php

/**
 * Fresh-load content hooks, isolated from callback tests by a Pest invocation.
 */

declare(strict_types=1);

namespace UamswpFindADoc\Tests\Wiring;

use UamswpFindADoc\Tests\Support\UnitTestCase;

use function Brain\Monkey\Functions\when;

/**
 * Pin public hook identities, priorities, and accepted filter arguments.
 *
 * Priorities are observable through WordPress hook lookup and removal, even when
 * changing them would leave the final content registry unchanged. This records
 * current registrations, not a claim that every priority mutation breaks core.
 *
 * @command  composer test:wiring
 */
final class ContentHooksTest extends UnitTestCase
{
    /**
     * Observe registrations while the file first loads, not by calling callbacks.
     */
    public function test_fresh_load_registers_content_roles_admin_and_rest_hooks(): void
    {
        // Refuse a shared-process run that could silently skip conditional hooks.
        $this->assertFalse(\function_exists('providers'));
        $this->assertFalse(\function_exists('locations'));

        // Capture filter argument counts at the WordPress registration boundary.
        $filters = [];
        when('add_filter')->alias(function (
            string $hook,
            string $callback,
            int $priority = 10,
            int $accepted_args = 1,
        ) use (&$filters): void {
            $filters[] = [$hook, $callback, $priority, $accepted_args];
        });

        // Load the actual production path so Pest can substitute its mutants.
        require dirname(__DIR__, 2).'/includes/class.fad-custom-post.php';

        // Preserve early registration and stable callback handles for consumers.
        foreach ([
            'providers', 'locations', 'expertise_cpt', 'conditions_cpt',
            'treatments_cpt', 'clinical_resources_cpt',
            'create_affiliations_taxonomy', 'create_institute_affiliations_taxonomy',
            'create_associations_taxonomy', 'create_brand_organization_taxonomy',
            'create_brand_organization_uams_taxonomy', 'create_certifying_body_taxonomy',
            'create_boards_taxonomy', 'create_academic_college_taxonomy',
            'create_clinical_title_taxonomy', 'create_degrees_taxonomy',
            'create_academic_departments_taxonomy', 'create_schools_taxonomy',
            'create_education_taxonomy', 'create_building_taxonomy',
            'create_gmb_cat_provider_taxonomy', 'create_gmb_cat_location_taxonomy',
            'create_languages_taxonomy', 'create_parking_taxonomy',
            'create_patient_type_taxonomy', 'create_portal_taxonomy',
            'create_academic_position_taxonomy', 'create_recognition_taxonomy',
            'create_region_taxonomy', 'create_residency_years_taxonomy',
            'create_service_line_taxonomy', 'create_academic_admin_title_taxonomy',
            'create_clinical_admin_title_taxonomy', 'create_academic_title_taxonomy',
            'create_location_type_taxonomy', 'add_roles_on_plugin_activation',
        ] as $callback) {
            $this->assertTrue(\function_exists($callback), $callback);
            $this->assertSame(0, has_action('init', $callback), $callback);
        }

        // Keep callable legacy taxonomies unregistered, as they are today.
        foreach (['create_clinical_conditions_taxonomy', 'create_clinical_treatments_taxonomy'] as $callback) {
            $this->assertTrue(\function_exists($callback), $callback);
            $this->assertFalse(has_action('init', $callback), $callback);
        }

        // Preserve creation before stored-role repair and the other lifecycle hooks.
        $this->assertSame(1, has_action('init', 'uamswp_fad_sync_doc_role_caps'));
        $this->assertSame(10, has_action('admin_init', 'add_theme_caps'));
        $this->assertSame(10, has_action('admin_menu', 'remove_provider_meta'));
        $this->assertSame(10, has_action('admin_head', 'acf_hide_title'));
        $this->assertSame(10, has_action('rest_api_init', 'rest_api_provider_meta'));

        // Pin the public filter registrations, including legacy REST compatibility.
        $this->assertSame([
            ['post_type_labels_provider', 'change_featured_image_labels_provider', 10, 1],
            ['rest_query_vars', 'provider_add_rest_query_vars', 10, 1],
        ], $filters);
    }
}
