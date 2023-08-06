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

 if (
	!isset($podcast_section_show) || empty($podcast_section_show)
) {

	$jump_link_count = ( isset($jump_link_count) && empty($jump_link_count) ) ? $jump_link_count : '';

	$podcast_query_vars = uamswp_fad_podcast_query(
		$page_id, // int
		$podcast_name, // string
		$jump_link_count // int
	);
		$podcast_section_show = $podcast_query_vars['podcast_section_show']; // bool

}