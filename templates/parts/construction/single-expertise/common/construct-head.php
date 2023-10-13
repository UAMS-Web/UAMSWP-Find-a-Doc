<?php
/*
 * Template Name: Area of Expertise Template HEAD Elements
 *
 * Description: A template part that constructs the HEAD elements common to all
 * area of expertise overview pages and all fake area of expertise subpages
 */

// Title tag

	// Override SEOPress's standard title tag settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			if ( $meta_title ) {

				$html = $meta_title;

			}

			return $html;

		}, 15, 2 );

// Meta Description and Schema Description

	// Override theme's method of defining the meta description

		add_filter('seopress_titles_desc', function( $html ) use ( $excerpt_attr ) {

			if ( $excerpt_attr ) {

				$html = $excerpt_attr;

			}

			return $html;

		} );

// Meta Keywords

	// Override theme's standard meta keywords settings

		add_action( 'wp_head', function() use ( $keywords ) {

			uamswp_keyword_hook_header(

				$keywords // array

			);

		} );

// Meta Social Media Tags

	// Filter hooks
	include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );