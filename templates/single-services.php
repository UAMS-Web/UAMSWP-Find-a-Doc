<?php
/*
 *
 *  Template Name: Services
 *  Designed for services single
 *
 */
// add_action( 'genesis_after_header', 'page_options', 5 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

function uams_default_page_body_class( $classes ) {

    $classes[] = 'page-template-default';
    return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );
genesis();