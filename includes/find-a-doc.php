<?php

declare(strict_types=1);

namespace UAMS\Find_a_Doc;

add_action('plugins_loaded', 'UAMS\Find_a_Doc\bootstrap');

/**
 * The class files the plugin loads, in load order, by name within `includes/`.
 *
 * @return list<string>
 */
function files(): array
{
    return [
        'class.fad-physicians.php',
        'class.fad-custom-post.php',
        'class.fad-helper-functions.php',
        'class.fad-location-address.php',
        'class.fad-settings.php',
        // 'class.fad-expertise-attributes-meta-box.php', // Not used in 2020
        'class.fad-templates.php',
        'class.fad-template-helpers.php', // Callbacks several page templates share
        'class.fad-template-titles.php', // Page title filters of the page templates
        'class.fad-acf-functions.php', // ACF specific functions
        'class.fad-acf-table-map-notice.php', // Admin notice when the custom table cache disagrees with the table JSON
        'class.fad-alpha-version-notice.php', // Admin notice when FacetWP - Alpha for UAMS is missing or older than 1.3.5.2
        'class.fad-provider-nullable-fields.php', // Keep unanswered provider fields empty in the custom table
        'class.fad-provider-preview.php', // Read a provider's saved fields during a preview
        'class.fad-delete-cleanup.php', // Remove a deleted provider or location from linked records and custom tables
        'class.fad-dead-link-cleanup.php', // Admin screen: remove dead links and orphan custom-table rows left by earlier deletes
        'class.fad-fwp-functions.php', // FacetWP specific functions
        'class.fad-functions.php',
        'class.fad-single-expertise.php', // Callbacks of the single-expertise template
        'class.fad-single-clinical-resource.php', // Callbacks of the single-clinical-resource template
        'class.fad-settings-pages.php',
        'class.fad-acf-blocks.php',
        'class.fad-gmb-settings-page.php',
        'class.fad-editor-groups.php', // Editor groups: who may edit which providers and locations
        'class.fad-edit-flow-status.php', // Edit Flow: status dropdown on published providers and locations
        'class.fad-gbp-logic.php', // Business Profile sync: field mapping, diff, silos
        'class.fad-gbp-client.php', // Business Profile sync: Google API client
        'class.fad-gbp-sync.php', // Business Profile sync: update, create and skip decisions
        'class.fad-gbp-runtime.php', // Business Profile sync: WordPress wiring
        'class.fad-gbp-settings.php', // Business Profile sync: settings screen
        'class.fad-gbp-cli.php', // Business Profile sync: on-demand WP-CLI command
        'class.fad-gbp-dashboard.php', // Business Profile sync: Dashboard status widget
        'class.fad-gbp-orphans.php', // Business Profile sync: orphaned listings report and close
    ];
}

/**
 * The functions that register the Business Profile sync hooks, in the order they run.
 *
 * @return list<callable-string>
 */
function registrations(): array
{
    return [
        'uamswp_fad_gbp_register_hooks',
        'uamswp_fad_gbp_register_settings_hooks',
        'uamswp_fad_gbp_register_dashboard_hooks',
        'uamswp_fad_gbp_register_orphan_hooks',
        'uamswp_fad_gbp_register_cli',
    ];
}

/**
 * Loads the UAMSWP Find-a-Doc
 *
 * @since 1.0.0
 */
function bootstrap(): void
{
    // Loading every class file collides with the functions the Unit tests stub, so the Integration suite runs this
    // loop (PluginLoadTest) and the Unit suite pins the list it reads.
    // @pest-mutate-ignore: ForeachEmptyIterable
    foreach (files() as $file) {
        include_once implode('/', [__DIR__, $file]);
    }

    // The registrations live in the class files the loop above loads, so the Integration suite runs them too.
    // @pest-mutate-ignore: ForeachEmptyIterable
    foreach (registrations() as $register) {
        $register();
    }
}
