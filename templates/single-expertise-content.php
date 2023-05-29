<?php
// Content Sections
// Clinical Resources
$resources = get_field('expertise_clinical_resources', $page_id);
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
if( ( $resources && $resource_query->have_posts() ) && ( "1" == $ontology_type || !isset($ontology_type) ) ) {
	$show_related_resource_section = true;
	$jump_link_count++;
} else {
	$show_related_resource_section = false;
}

// Check if fake subpage for Specialties (Child Areas of Expertise) should be displayed
$child_pages = get_pages( array('child_of' => $page_id, 'post_type' => 'expertise' ) );
if ($child_pages) {
	$childnav = '';
	$children = false;
	foreach ( $child_pages as $child_page ) {
		$hide = get_post_meta($child_page->ID, 'page_hide_from_menu');
		$type = get_field('expertise_type', $child_page->ID);
		if ( isset($hide[0]) && '1' == $hide[0] ) {
			//* Do nothing if there is nothing to show
		} elseif( !isset($type) || '1' == $type ) {
			$children = true;
		} else {
			$childnav .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item menu-item-'. $child_page->ID .' nav-item active"><a title="'. $child_page->post_title .'" href="'. get_permalink( $child_page->ID ) .'" class="nav-link"><span itemprop="name">'. $child_page->post_title .'</span></a></li>';			
		}
	}
	$show_child_aoe_section = $children ? true : false;
	$show_child_content_nav = !empty($childnav) ? true : false;
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
if( ( $conditions_cpt && $conditions_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
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
if( ( $treatments_cpt && $treatments_cpt_query->posts ) && ("1" == $ontology_type || !isset($ontology_type) ) ) {
	$show_treatments_section = true;
	$jump_link_count++;
} else {
	$show_treatments_section = false;
}

// Check if fake subpage for Providers should be displayed
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
	if( ( $physicians_query && $physicians_query->have_posts()) && ( "1" == $ontology_type || !isset( $ontology_type ) ) ) {
		$show_providers_section = true;
		$jump_link_count++;
		$provider_ids = $physicians_query->posts;
	} else {
		$show_providers_section = false;
	}
}

// Check if fake subpage for Locations should be displayed
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
	if( ( $locations && $location_query->have_posts() ) && ( "1" == $ontology_type || !isset( $ontology_type ) ) ) {
		$show_locations_section = true;
		$jump_link_count++;
	} else {
		$show_locations_section = false;
	}
}

// Check if fake subpage for Related Areas of Expertise should be displayed
$expertises = get_field('expertise_associated', $page_id);
$args = (array(
	'post_type' => "expertise",
	'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'post__in'	=> $expertises
));
$expertise_query = new WP_Query( $args );
if( ( $expertises && $expertise_query->have_posts() ) && ( "1" == $ontology_type || !isset( $ontology_type ) ) ) {
	$show_related_aoe_section = true;
	$jump_link_count++;
} else {
	$show_related_aoe_section = false;
}