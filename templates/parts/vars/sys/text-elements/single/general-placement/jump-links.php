<?php
/*
 * Template Name: System settings for text elements in general placements of 
 * a jump links bar
 * 
 * Description: A template part that defines a series of variables related to the 
 * navigational element containing jump links (or anchor links)
 */

 if (
	!isset($fad_jump_links_title) || empty($fad_jump_links_title)
) {

	$labels_jump_links_vars = uamswp_fad_labels_jump_links();
		$fad_jump_links_title = $labels_jump_links_vars['fad_jump_links_title']; // string

}
