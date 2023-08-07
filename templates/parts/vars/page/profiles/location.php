<?php
/*
 * Template Name: Define variables for a location profile
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in a location profile
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

$location_profile_fields_vars = uamswp_fad_location_profile_fields( $page_id );

foreach ( $location_profile_fields_vars as $key => $value ) {

	${$key} = $value; // Create a variable for each item in the array

}