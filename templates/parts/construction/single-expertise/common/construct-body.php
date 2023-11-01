<?php
/*
 * Template Name: Area of Expertise Template BODY Elements
 *
 * Description: A template part that constructs the BODY elements common to all
 * area of expertise overview pages and all fake area of expertise subpages
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 */

// BODY

	// Add page template class to body element's classes

		add_filter( 'body_class', function( $classes ) use ( $template_type ) {

			// Add page template class to body class array

				if ( $template_type ) {

					$classes[] = 'page-template-' . $template_type;

				}

			return $classes;

		} );

	// Header

		// get_header();

		// Site header

			// Remove the site header set by the theme

				remove_action( 'genesis_header', 'uamswp_site_image', 5 );

			// Add ontology subsection site header

				add_action( 'genesis_header', function() use (
					$page_id,
					$ontology_type,
					$page_title,
					$page_url,
					$navbar_subbrand_title,
					$navbar_subbrand_title_attr,
					$navbar_subbrand_title_url,
					$navbar_subbrand_parent,
					$navbar_subbrand_parent_attr,
					$navbar_subbrand_parent_url
				) {

					include( UAMS_FAD_PATH . '/templates/parts/html/site-header/single-expertise.php');

				}, 5 );

		// Primary navigation

			// Remove the primary navigation set by the theme

				remove_action( 'genesis_after_header', 'genesis_do_nav' );
				remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

			// Add ontology subsection primary navigation

				add_action( 'genesis_after_header', function() use (
					$page_id,
					$ontology_type,
					$page_title,
					$page_url,
					$site_nav_id,
					$content_placement,
					$providers,
					$locations,
					$expertises,
					$expertise_descendants,
					$clinical_resources,
					$provider_section_show,
					$location_section_show,
					$expertise_section_show,
					$clinical_resource_section_show,
					$expertise_descendant_section_show,
					$expertise_content_nav_show,
					$expertise_content_nav,
					$provider_plural_name,
					$provider_plural_name_attr,
					$location_plural_name,
					$location_plural_name_attr,
					$expertise_plural_name,
					$expertise_plural_name_attr,
					$expertise_descendant_plural_name,
					$expertise_descendant_plural_name_attr,
					$clinical_resource_plural_name,
					$clinical_resource_plural_name_attr
				) {

					include( UAMS_FAD_PATH . '/templates/parts/html/site-nav/single-expertise.php');

				}, 5 );

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			add_action( 'genesis_before_content', function() use (
				$entry_title_text,
				$entry_header_style,
				$entry_title_text_supertitle,
				$entry_title_text_subtitle,
				$entry_title_text_body,
				$entry_title_image_desktop,
				$entry_title_image_mobile
			) {

				include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/' . $entry_header_style . '.php');

			} );

	// MAIN / ARTICLE

		// Add classes to main element

			add_filter( 'genesis_attr_content', function( $attributes ) {

				$classes = array(
					$attributes['class'],
					'expertise-item'
				);

				$attributes['class'] = implode( ' ', $classes );

				return $attributes;

			} );

		// Add bg-white class to article.entry element

			add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Construct page content

			// Display appointment information

				add_action( 'genesis_after_entry', function() use (
					$appointment_section_show,
					$location_section_show,
					$content_placement,
					$page_url,
					$current_fpage
				) {

					include( UAMS_FAD_PATH . '/templates/parts/html/section/appointment.php' );

				}, 26 );

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );