<?php
/*
 * Template Name: Define variables for a location card
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in a location card
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

// Call the function

	$location_card_fields_vars = isset($location_card_fields_vars) ? $location_card_fields_vars : uamswp_fad_location_card_fields( $page_id );

// Create a variable for each item in the array

	foreach ( $location_card_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}