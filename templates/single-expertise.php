<?php

declare(strict_types=1);
/*
 *
 *  Template Name: Services
 *  Designed for services single
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
$expertise_archive_title = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
$expertise_archive_title_attr = $expertise_archive_title;
$expertise_archive_title_attr = str_replace('"', "'", $expertise_archive_title_attr); // Replace double quotes with single quote
$expertise_archive_title_attr = str_replace('&nbsp;', ' ', $expertise_archive_title_attr); // Convert non-breaking space entity text to a normal space before it is encoded
$expertise_archive_title_attr = htmlentities($expertise_archive_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$expertise_archive_title_attr = str_replace('&nbsp;', ' ', $expertise_archive_title_attr); // Convert non-breaking space with normal space
$expertise_archive_title_attr = html_entity_decode($expertise_archive_title_attr); // Convert HTML entities to their corresponding characters
$expertise_single_name = get_field('expertise_archive_headline', 'option') ?: 'Area of Expertise';
$expertise_single_name_attr = $expertise_single_name;
$expertise_single_name_attr = str_replace('"', "'", $expertise_single_name_attr); // Replace double quotes with single quote
$expertise_single_name_attr = str_replace('&nbsp;', ' ', $expertise_single_name_attr); // Convert non-breaking space entity text to a normal space before it is encoded
$expertise_single_name_attr = htmlentities($expertise_single_name_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
$expertise_single_name_attr = str_replace('&nbsp;', ' ', $expertise_single_name_attr); // Convert non-breaking space with normal space
$expertise_single_name_attr = html_entity_decode($expertise_single_name_attr); // Convert HTML entities to their corresponding characters

// Parent Area of Expertise
$expertise_parent_id = wp_get_post_parent_id($page_id);
$expertise_has_parent = (bool) $expertise_parent_id;
$parent_expertise = '';
$parent_id = '';
$parent_title = '';
$parent_url = '';

if ($expertise_parent_id) {
    $parent_expertise = get_post($expertise_parent_id);
}

// Get attributes of parent Area of Expertise
if ($parent_expertise) {
    $parent_id = $parent_expertise->ID;
    $parent_title = $parent_expertise->post_title;
    $parent_title_attr = $parent_title;
    $parent_title_attr = str_replace('"', "'", $parent_title_attr); // Replace double quotes with single quote
    $parent_title_attr = str_replace('&#8217;', "'", $parent_title_attr); // Replace right single quote with single quote
    $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space entity text to a normal space before it is encoded
    $parent_title_attr = htmlentities($parent_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
    $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space with normal space
    $parent_title_attr = html_entity_decode($parent_title_attr); // Convert HTML entities to their corresponding characters

    $parent_url = get_permalink($parent_id);
}

add_filter('seopress_titles_title', 'uamswp_fad_title_single_expertise', 15, 2);

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
add_action('genesis_entry_header', 'uamswp_expertise_post_title');

add_filter('genesis_entry_content', 'uamswp_expertise_keywords', 8);
add_action('genesis_entry_content', 'uamswp_expertise_youtube', 12);
add_action('genesis_after_entry', 'uamswp_expertise_cta', 6);
add_action('genesis_after_entry', 'uamswp_expertise_jump_links', 8);
add_action('genesis_after_entry', 'uamswp_expertise_podcast', 10);
add_action('genesis_after_entry', 'uamswp_list_child_expertise', 12);
add_action('genesis_after_entry', 'uamswp_expertise_resource', 14);
add_action('genesis_after_entry', 'uamswp_expertise_conditions_cpt', 16);
add_action('genesis_after_entry', 'uamswp_expertise_treatments_cpt', 18);
add_action('genesis_after_entry', 'uamswp_expertise_physicians', 20);
add_action('genesis_after_entry', 'uamswp_expertise_locations', 22);
add_action('genesis_after_entry', 'uamswp_expertise_associated', 24);
add_action('genesis_after_entry', 'uamswp_expertise_appointment', 26);
add_action('wp_head', 'uamswp_expertise_header_metadata');

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Check if Podcast section should be displayed
$podcast_name = get_field('expertise_podcast_name');
if ($podcast_name) {
    $show_podcast_section = true;
    $jump_link_count++;
} else {
    $show_podcast_section = false;
}

// Clinical Resources
$resources = get_field('expertise_clinical_resources');
$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
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
    $jump_link_count++;
} else {
    $show_related_resource_section = false;
}

// Check if Child Areas of Expertise section should be displayed
// The count and 0 are both ints, so a loose and a strict comparison agree.
// @pest-mutate-ignore: NotEqualToNotIdentical
if (
    ! (get_post_meta($page_id, 'hide_sub_areas_of_expertise', true))
    && (count(get_pages(['child_of' => $page_id, 'post_type' => 'expertise'])) != 0)
) {
    $show_child_aoe_section = true;
    $jump_link_count++;
} else {
    $show_child_aoe_section = false; // If it's suppressed or none available, set to false
}

// Check if Conditions section should be displayed
// load all 'conditions' terms for the post
$conditions_cpt = get_field('expertise_conditions_cpt');
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
$treatments_cpt = get_field('expertise_treatments_cpt');
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
$physicians = get_field('physician_expertise');
if ($physicians) {
    $args = [
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'fields' => 'ids',
        // 'no_found_rows' => true, // counts posts, remove if pagination required
        'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
        'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
        'post__in' => $physicians,
    ];
    $physicians_query = new WP_Query($args);
    if ($physicians_query->have_posts()) {
        $show_providers_section = true;
        $jump_link_count++;
        $provider_ids = $physicians_query->posts;
    } else {
        $show_providers_section = false;
    }
}

// Check if Locations section should be displayed
$locations = get_field('location_expertise');
if ($locations) {
    $args = ([
        'post_type' => 'location',
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true, // counts posts, remove if pagination required
        'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
        'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
        'post__in' => $locations,
    ]);
    $location_query = new WP_Query($args);
    if ($locations && $location_query->have_posts()) {
        $show_locations_section = true;
        $jump_link_count++;
    } else {
        $show_locations_section = false;
    }
}

// Check if Locations section should be displayed
$expertises = get_field('expertise_associated');
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
    $show_related_aoe_section = true;
    $jump_link_count++;
} else {
    $show_related_aoe_section = false;
}

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$show_appointment_section = true;
$jump_link_count++;

// Check if Jump Links section should be displayed
if ($jump_link_count >= $jump_link_count_min) {
    $show_jump_links_section = true;
} else {
    $show_jump_links_section = false;
}

genesis();
