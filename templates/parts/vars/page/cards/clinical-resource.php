<?php
/*
 * Template Name: Define variables for a clinical resource card
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in a clinical resource card
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

$clinical_resource_card_fields_vars = uamswp_fad_clinical_resource_card_fields( $page_id );

foreach ( $clinical_resource_card_fields_vars as $key => $value ) {

	${$key} = $value; // Create a variable for each item in the array

}