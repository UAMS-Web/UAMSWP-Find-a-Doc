<?php

declare(strict_types=1);
/*
 *
 *  Template Name: Clinical Resources
 *  Designed for clinical resources single
 *
 */
// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title();
$page_title_attr = $page_title;
$page_title_attr = str_replace('"', "'", $page_title_attr); // Replace double quotes with single quote
$page_title_attr = str_replace('&nbsp;', ' ', $page_title_attr); // Convert non-breaking space entity text to a normal space before it is encoded
$page_title_attr = htmlentities($page_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$page_title_attr = str_replace('&nbsp;', ' ', $page_title_attr); // Convert non-breaking space with normal space
$page_title_attr = html_entity_decode($page_title_attr); // Convert HTML entities to their corresponding characters
$resource_archive_title_system = get_field('clinical_resource_archive_headline', 'option');
$resource_archive_title = $resource_archive_title_system ?: 'Clinical Resource';
$resource_archive_title_attr = $resource_archive_title;
$resource_archive_title_attr = str_replace('"', "'", $resource_archive_title_attr); // Replace double quotes with single quote
$resource_archive_title_attr = str_replace('&nbsp;', ' ', $resource_archive_title_attr); // Convert non-breaking space entity text to a normal space before it is encoded
$resource_archive_title_attr = htmlentities($resource_archive_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$resource_archive_title_attr = str_replace('&nbsp;', ' ', $resource_archive_title_attr); // Convert non-breaking space with normal space
$resource_archive_title_attr = html_entity_decode($resource_archive_title_attr); // Convert HTML entities to their corresponding characters

add_filter('seopress_titles_title', 'uamswp_fad_title_single_clinical_resource', 15, 2);

$syndicated = get_field('clinical_resource_syndicated');
$syndication_url = get_field('clinical_resource_syndication_url');
add_filter('seopress_titles_canonical', 'uamswp_fad_canonical');
remove_action('genesis_entry_header', 'genesis_post_info', 12);
remove_action('genesis_entry_footer', 'genesis_post_info', 9); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
remove_action('genesis_entry_footer', 'genesis_post_meta');
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);

add_filter('body_class', 'uams_default_page_body_class');

// Add extra class to entry
add_filter('genesis_attr_entry', 'uamswp_add_entry_class');

// Modify Entry Title

remove_action('genesis_entry_header', 'genesis_do_post_title');
add_action('genesis_entry_header', 'uamswp_resource_post_title');

add_action('genesis_entry_content', 'uamswp_resource_text', 8);
add_action('genesis_entry_content', 'uamswp_resource_provider_spotlight', 9);
add_action('genesis_entry_content', 'uamswp_resource_infographic', 10);
add_action('genesis_entry_content', 'uamswp_resource_video', 12);
add_action('genesis_entry_content', 'uamswp_resource_document', 14);
add_action('genesis_after_entry', 'uamswp_resource_jump_links', 8);
add_action('genesis_after_entry', 'uamswp_resource_associated', 10);
add_action('genesis_after_entry', 'uamswp_resource_conditions_cpt', 12);
add_action('genesis_after_entry', 'uamswp_resource_treatments_cpt', 14);
add_action('genesis_after_entry', 'uamswp_resource_physicians', 16);
add_action('genesis_after_entry', 'uamswp_resource_locations', 18);
add_action('genesis_after_entry', 'uamswp_resource_expertise', 20);
add_action('genesis_after_entry', 'uamswp_resource_appointment', 22);

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

$resource_type = get_field('clinical_resource_type');
$resource_type_value = $resource_type['value'] ?? null;
$resource_type_label = $resource_type['label'] ?? null;

// Provider Spotlight
// The select value is a string, or null when unset, so a loose and a strict comparison agree.
// @pest-mutate-ignore: EqualToIdentical
$is_provider_spotlight = ($resource_type_value == 'provider_spotlight');
$spotlight_provider = $is_provider_spotlight ? (int) get_field('clinical_resource_spotlight_provider') : 0;

// Require the featured provider to still be published. A provider can be
// unpublished, trashed, or deleted after selection, which would otherwise
// render a spotlight with a dead profile link and, when the provider is gone,
// name-less generated prose. Suppress the spotlight body in that case.
if ($spotlight_provider && get_post_status($spotlight_provider) !== 'publish') {
    $spotlight_provider = 0;
}

// Check if Conditions section should be displayed
// load all 'conditions' terms for the post
$conditions_cpt = get_field('clinical_resource_conditions');
// Conditions CPT
$args = ([
    'post_type' => 'condition',
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__in' => $conditions_cpt,
]);
$conditions_cpt_query = new WP_Query($args);
if ($conditions_cpt && $conditions_cpt_query->posts) {
    $show_conditions_section = true;
    $jump_link_count++;
} else {
    $show_conditions_section = false;
}

// Check if Treatments and Procedures section should be displayed
$treatments_cpt = get_field('clinical_resource_treatments');
// Treatments CPT
$args = ([
    'post_type' => 'treatment',
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__in' => $treatments_cpt,
]);
$treatments_cpt_query = new WP_Query($args);
if ($treatments_cpt && $treatments_cpt_query->posts) {
    $show_treatments_section = true;
    $jump_link_count++;
} else {
    $show_treatments_section = false;
}

// Check if Providers section should be displayed
$physicians = get_field('clinical_resource_providers');
if ($physicians) {
    $postsPerPage = 12; // Set this value to preferred value (4, 6, 8, 10, 12). If you change the value, update the instruction text in the editor's JSON file.
    $postsCutoff = 18; // Set cutoff value. If you change the value, update the instruction text in the editor's JSON file.
    $postsCountClass = $postsPerPage;
    if (count($physicians) <= $postsCutoff) {
        $postsPerPage = -1;
    }

    $args = [
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => $postsPerPage,
        'orderby' => 'title',
        'order' => 'ASC',
        'post__in' => $physicians,
    ];
    $physicians_query = new WP_Query($args);
    if ($physicians_query->have_posts()) {
        $show_providers_section = true;
        $jump_link_count++;
    } else {
        $show_providers_section = false;
    }
}

// Check if Locations section should be displayed
$locations = get_field('clinical_resource_locations');
if ($locations) {
    $args = ([
        'post_type' => 'location',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => $locations,
    ]);
    $location_query = new WP_Query($args);
    if ($location_query->have_posts()) {
        $show_locations_section = true;
        $jump_link_count++;
    } else {
        $show_locations_section = false;
    }
}

// Check if Expertise section should be displayed
$expertises = get_field('clinical_resource_aoe');
$args = ([
    'post_type' => 'expertise',
    'order' => 'ASC',
    'orderby' => 'title',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post__in' => $expertises,
]);
$expertise_query = new WP_Query($args);
if ($expertises && $expertise_query->have_posts()) {
    $show_aoe_section = true;
    $jump_link_count++;
} else {
    $show_aoe_section = false;
}

// Clinical Resources
$resources = get_field('clinical_resource_related');
$resource_postsPerPage = -1; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
$resource_more = false;
$args = ([
    'post_type' => 'clinical-resource',
    'order' => 'DESC',
    'orderby' => 'post_date',
    'posts_per_page' => $resource_postsPerPage,
    'post_status' => 'publish',
    'post__in' => $resources,
]);
$resource_query = new WP_Query($args);

// Check if Clinical Resources section should be displayed
if ($resources && $resource_query->have_posts()) {
    $show_related_resource_section = true;
    $resource_count = count($resources);
    $jump_link_count++;
} else {
    $show_related_resource_section = false;
}

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$show_appointment_section = true;
$jump_link_count++;

// Suppress every "Related *" section on a Provider Spotlight
//
// A spotlight is a self-contained feature about one provider: its own
// call to action carries the only outbound link, and a one-card grid of the
// page's own subject would be redundant. This is presentation only. The
// relationship fields stay editable, and the featured provider is still
// mirrored into clinical_resource_providers, so Ajax Search Pro and FacetWP
// continue to index this resource under that provider.
//
// The generic "Make an Appointment" module is not a "Related" section and is
// kept. With everything else gone the jump-link count falls to one, below the
// minimum, so the jump-link nav hides itself; each section function is also
// guarded on its own flag.
if ($is_provider_spotlight) {
    $show_conditions_section = false;
    $show_treatments_section = false;
    $show_providers_section = false;
    $show_locations_section = false;
    $show_aoe_section = false;
    $show_related_resource_section = false;

    $jump_link_count = 1; // the appointment module only
}

// Check if Jump Links section should be displayed
if ($jump_link_count >= $jump_link_count_min) {
    $show_jump_links_section = true;
} else {
    $show_jump_links_section = false;
}

/**
 * Provider Spotlight
 *
 * The introduction followed by the Q&A block, which floats the provider's
 * portrait as its first child. Renders nothing for any other resource type. The
 * call to action is not rendered here; it renders in the site appointment slot
 * via uamswp_resource_appointment() (see the note at the end of this function).
 *
 * Heading levels run h1 (post title) -> h2 (section) -> h3 (question), with no
 * level skipped; the intro and Q&A h2s are screen-reader-only. The portrait
 * identifies the provider, so its alt text is the provider's name rather than
 * being marked decorative.
 *
 * Structured data stays at parity with the other resource types: the inherited
 * Genesis entry microdata and itemprop="image" on the portrait, and nothing
 * more. No JSON-LD, and no about/mainEntity link to the provider.
 */
genesis();
