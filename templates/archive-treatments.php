<?php
/*
 * Template Name: Treatments Archive
 */

// Define variables

	// Variable name slug

		$variable_slug = 'treatment';
		$post_type = 'treatment';

	// Get system settings for ontology item labels

		// Get system settings for treatment labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

	// Get system settings for treatment archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/treatment.php' );

	// Define variables common to all archive pages
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/vars.php' );

	// Define variables common to all archive pages with the list layout
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/list-layout/vars.php' );

// Construct HEAD elements

	// Archive Template Common HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-head.php' );

	// Archive Template List Layout HEAD Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/list-layout/construct-head.php' );

// Construct BODY elements

	// Archive Template Common BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/common/construct-body.php' );

	// Archive Template List Layout BODY Elements
	include( UAMS_FAD_PATH . '/templates/parts/construction/archive/list-layout/construct-body.php' );

?>