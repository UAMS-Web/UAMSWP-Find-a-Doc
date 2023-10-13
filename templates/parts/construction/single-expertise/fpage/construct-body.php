<?php
/*
 * Template Name: Fake Area of Expertise Subpage Template BODY Elements
 *
 * Description: A template part that constructs the BODY elements common to all
 * fake area of expertise subpages
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 */

// BODY

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			// Do nothing

		// Override SEOPress standard breadcrumbs settings

			add_filter( 'seopress_pro_breadcrumbs_crumbs', function( $crumbs ) use ( $fpage_name ) {

				// $crumbs is a multidimensional array.
				//     First array: key=position,
				//     second array: 0=>page title, 1=>URL, 2=>ID (since version 6.1)

				// Add name of fake subpage to the breadcrumbs array
				$crumbs[] = array($fpage_name, '');

				return $crumbs;

			} );

	// MAIN / ARTICLE

		// Get remaining details about this item

			// Do nothing

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			// Do nothing

		// Remove standard post content

			remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			// Display references to other archive pages

				add_action( 'genesis_entry_content', function() use (
					$page_id,
					$page_titles,
					$current_fpage,
					$ontology_type
				) {

					uamswp_fad_fpage_text_image_overlay(
						$page_id, // int
						$page_titles, // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						$current_fpage, // string (optional) // Fake subpage slug
						$ontology_type // bool (optional)
					);

				}, 25 );
