<?php
/*
 * Template Name: Define variables for a clinical resource card
 *
 * Description: A template part that defines a series of variables for the fields
 * needed in a clinical resource card
 *
 * Required vars:
 * 	$page_id // int // ID of the profile
 *
 * Optional vars:
 * 	$clinical_resource_card_style // string enum('basic', 'detailed') // Clinical resource card style
 */

// Check/define optional variables
$clinical_resource_card_style = isset($clinical_resource_card_style) ? $clinical_resource_card_style : '';

// Call the function

	$clinical_resource_card_fields_vars = ( isset($clinical_resource_card_fields_vars) && !empty($clinical_resource_card_fields_vars) ) ? $clinical_resource_card_fields_vars : uamswp_fad_clinical_resource_card_fields(
		$page_id,
		$clinical_resource_card_style
	);

// Create a variable for each item in the array

	foreach ( $clinical_resource_card_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}