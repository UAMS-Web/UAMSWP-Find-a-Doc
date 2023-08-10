<?php
/*
 * Template Name: Define variables for a location card
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in a location card
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 * 
 * Optional vars:
 * 	$location_card_style // string enum('basic', 'detailed') // Location card style
 * 	$schema_telephone // array // Schema telephone data
 * 	$schema_fax_number // array // Schema fax number data
 * 	$location_descendant_list // bool // Query on whether this card is in a list of descendant locations
 */

// Check/define optional variables

	$location_card_style = isset($location_card_style) ? $location_card_style : '';
	$schema_telephone = isset($schema_telephone) ? $schema_telephone : '';
	$schema_fax_number = isset($schema_fax_number) ? $schema_fax_number : '';
	$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : '';

// Call the function

	$location_card_fields_vars = ( isset($location_card_fields_vars) && !empty($location_card_fields_vars) ) ? $location_card_fields_vars : uamswp_fad_location_card_fields(
		$page_id,
		$location_card_style,
		$schema_telephone,
		$schema_fax_number,
		$location_descendant_list
	);

// Create a variable for each item in the array

	foreach ( $location_card_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}