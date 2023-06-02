<?php
/**
 * Template Name: Provider Cards Loop for FacetWP "Physician Cards" and "Physician Search Cards" Templates
 * Template Slugs: physician_cards, physician_search_cards
 * Designed for physicians
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();
	$id = get_the_ID();
	include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
endwhile; endif; ?>