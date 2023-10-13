<?php
/*
 * Template Name: Fake Area of Expertise Subpage Template HEAD Elements
 *
 * Description: A template part that constructs the HEAD elements common to all
 * fake area of expertise subpages
 */

// HEAD

	// Canonical URL

		// Override SEOPress's standard canonical URL settings

			add_filter( 'seopress_titles_canonical', function( $html ) use ( $canonical_url ) {

				if ( $canonical_url ) {

					$html = '<link rel="canonical" href="' . $canonical_url . '" />';

				}

				return $html;

			} );