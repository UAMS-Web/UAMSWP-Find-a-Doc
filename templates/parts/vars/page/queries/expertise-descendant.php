<?php
/*
 * Template Name: Descendant Areas of Expertise Section Query
 *
 * Description: A query for whether the section which lists descendants of the
 * current area of expertise item should be displayed on the profile/subsection.
 * It also returns the Query, an array of IDs and a count of the IDs.
 *
 * Required vars:
 * 	$page_id // int
 * 	$expertise_descendants // int[]
 *
 * Optional vars:
 * 	$content_placement // string
 * 	$site_nav_id // int
 * 	$jump_link_count // int
 * 	hide_medical_ontology // bool
 */

// Check/define optional variables

	$content_placement = ( isset($content_placement) && !empty($content_placement) ) ? $content_placement : '';
	$site_nav_id = ( isset($site_nav_id) && !empty($site_nav_id) ) ? $site_nav_id : '';
	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';
	$hide_medical_ontology = ( isset($hide_medical_ontology) && !empty($hide_medical_ontology) ) ? $hide_medical_ontology : '';

// Call the function

	$expertise_descendant_query_vars = ( isset($expertise_descendant_query_vars) && !empty($expertise_descendant_query_vars) ) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
		$page_id, // int
		$expertise_descendants, // int[]
		$content_placement, // string
		$site_nav_id, // int
		$jump_link_count, // int
		$hide_medical_ontology // bool
	);

// Create a variable for each item in the array

	foreach ( $expertise_descendant_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}