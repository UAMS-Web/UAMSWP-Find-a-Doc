<?php
/**
 * Template Name: Physician Loop - Card layout
 * Designed for physicians
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();
$id =get_the_ID();
include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
endwhile; endif; ?>