<?php
// Content Sections
// Clinical Resources
$resources =  get_field('expertise_clinical_resources', $page_id);
$resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
$resource_more = false;
$args = (array(
    'post_type' => "clinical-resource",
    'order' => 'DESC',
    'orderby' => 'post_date',
    'posts_per_page' => $resource_postsPerPage,
    'post_status' => 'publish',
    'post__in'	=> $resources
));
$resource_query = new WP_Query( $args );

// Check if Clinical Resources section should be displayed
if( ( $resources && $resource_query->have_posts() ) && ( "1" == $content_type || !isset($content_type) ) ) {
    $show_related_resource_section = true;
    $jump_link_count++;
} else {
    $show_related_resource_section = false;
}

// Check if Child Areas of Expertise section should be displayed
if (
    !( get_post_meta( $page_id, 'hide_sub_areas_of_expertise', true) ) 
    && ( "0" !== $content_type )
    && ( 0 !== count( get_pages( array( 'child_of' => $page_id, 'post_type' => 'expertise' ) ) ) ) 
    && ( "1" == $content_type || !isset( $content_type ) )
) {
    $show_child_aoe_section = true;
    $jump_link_count++;
} else {
    $show_child_aoe_section = false; // If it's suppressed or none available, set to false
}

// Check if Conditions section should be displayed
// load all 'conditions' terms for the post
$conditions_cpt = get_field('expertise_conditions_cpt', $page_id);
// Conditions CPT
$args = (array(
    'post_type' => "condition",
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__in' => $conditions_cpt
));
$conditions_cpt_query = new WP_Query( $args );
if( ( $conditions_cpt && $conditions_cpt_query->posts ) && ("1" == $content_type || !isset($content_type) ) ) {
    $show_conditions_section = true;
    $jump_link_count++;
} else {
    $show_conditions_section = false;
}

// Check if Treatments and Procedures section should be displayed
$treatments_cpt = get_field('expertise_treatments_cpt', $page_id);
// Treatments CPT
$args = (array(
    'post_type' => "treatment",
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__in' => $treatments_cpt
));
$treatments_cpt_query = new WP_Query( $args );
if( ( $treatments_cpt && $treatments_cpt_query->posts ) && ("1" == $content_type || !isset($content_type) ) ) {
    $show_treatments_section = true;
    $jump_link_count++;
} else {
    $show_treatments_section = false;
}

// Check if Providers section should be displayed
$physicians = get_field( "physician_expertise", $page_id );
if($physicians) {
    $args = array(
        "post_type" => "provider",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "orderby" => "title",
        "order" => "ASC",
        "fields" => "ids",
        // 'no_found_rows' => true, // counts posts, remove if pagination required
        'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
        'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
        "post__in" => $physicians
    );
    $physicians_query = New WP_Query( $args );
    if( ( $physicians_query && $physicians_query->have_posts()) && ( "1" == $content_type || !isset( $content_type ) ) ) {
        $show_providers_section = true;
        $jump_link_count++;
        $provider_ids = $physicians_query->posts;
    } else {
        $show_providers_section = false;
    }
}

// Check if Locations section should be displayed
$locations = get_field('location_expertise', $page_id);
if($locations) {
    $args = (array(
        'post_type' => "location",
		"post_status" => "publish",
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
		'post__in'	=> $locations
    ));
    $location_query = new WP_Query( $args );
    if( ( $locations && $location_query->have_posts() ) && ( "1" == $content_type || !isset( $content_type ) ) ) {
        $show_locations_section = true;
        $jump_link_count++;
    } else {
        $show_locations_section = false;
    }
}

// Check if Locations section should be displayed
$expertises =  get_field('expertise_associated', $page_id);
$args = (array(
    'post_type' => "expertise",
    'order' => 'ASC',
    'orderby' => 'title',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post__in'	=> $expertises
));
$expertise_query = new WP_Query( $args );
if( ( $expertises && $expertise_query->have_posts() ) && ( "1" == $content_type || !isset( $content_type ) ) ) {
    $show_related_aoe_section = true;
    $jump_link_count++;
} else {
    $show_related_aoe_section = false;
}