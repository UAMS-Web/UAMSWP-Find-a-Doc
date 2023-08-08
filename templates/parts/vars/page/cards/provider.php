<?php
/*
 * Template Name: Define variables for a provider card
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in a provider card
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

// Call the function

	$provider_card_fields_vars = ( isset($provider_card_fields_vars) && !empty($provider_card_fields_vars) ) ? $provider_card_fields_vars : uamswp_fad_provider_card_fields( $page_id );

// Create a variable for each item in the array

	foreach ( $provider_card_fields_vars as $key => $value ) {

		${$key} = $value; // Create a variable for each item in the array

	}