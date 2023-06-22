<?php
/**
 * Template Name: Provider Cards Loop for FacetWP Listings
 * 
 * FacetWP Listing Name and Slug:
 * 	Physician Cards					('physician_cards')
 * 	Physician Search Cards			('physician_search_cards')
 * 	Condition - Physicians			('condition_physicians')
 * 	Physician by Location			('physician_by_location')
 * 	Treatment - Physicians			('treatment_physicians')
 * 	Physicians by Area of Expertise	('physicians_by_expertise')
 * 
 * Designed for physicians
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();
	$id = get_the_ID();
	include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
endwhile; endif; ?>