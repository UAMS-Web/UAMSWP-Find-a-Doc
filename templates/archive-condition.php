<?php
/*
 * Template Name: Conditions Archive
 */

// Define variables

	// Variable name slug

		$variable_slug = 'condition';

	// Post type

		$post_type = 'condition';

	// Get system settings for ontology item labels

		// Get system settings for condition labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

	// Get system settings for condition archive text
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/condition.php' );

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