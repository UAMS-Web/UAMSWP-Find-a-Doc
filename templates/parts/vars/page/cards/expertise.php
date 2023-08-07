<?php
/*
 * Template Name: Define variables for an area of expertise card
 * 
 * Description: A template part that defines a series of variables for the fields 
 * needed in an area of expertise card
 * 
 * Required vars:
 * 	$page_id // int // ID of the profile
 */

$expertise_card_fields_vars = uamswp_fad_expertise_card_fields( $page_id );

foreach ( $expertise_card_fields_vars as $key => $value ) {

	${$key} = $value; // Create a variable for each item in the array

}