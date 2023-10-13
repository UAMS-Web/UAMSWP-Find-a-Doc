<?php
/*
 * Template Name: UAMS Health Talk Podcast Section Query
 *
 * Description: A query for whether the section which lists any episodes from the
 * UAMS Health Talk podcast which are related to the content of the current page
 * should be displayed on the profile/subsection.
 *
 * Required vars:
 * 	$page_id // int
 * 	$podcast_name // string
 *
 * Optional vars:
 * 	$jump_link_count // int
 */

// Check/define optional variables

	$jump_link_count = ( isset($jump_link_count) && !empty($jump_link_count) ) ? $jump_link_count : '';

// Call the function

	$podcast_query_vars = ( isset($podcast_query_vars) && !empty($podcast_query_vars) ) ? $podcast_query_vars : uamswp_fad_podcast_query(
		$page_id, // int
		$podcast_name, // string
		$jump_link_count // int
	);

// Create a variable for each item in the array

	foreach ( $podcast_query_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}