<?php
/*
 * SEOPress Functions
 */

// Override the theme's method of defining the meta title

	// Variable definition function
	// Call before setting $meta_title on template
	function uamswp_fad_meta_title_vars(
		$page_title_attr, // string
		$meta_title_base_addition = '', // string // Optional // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		$meta_title_base_order = '', // array // Optional // Pre-defined array for name order of base meta title // Expects one value but will accommodate any number
		$meta_title_enhanced_addition = '', // string // Optional // Word or phrase to inject into base meta title to form enhanced meta title level 1
		$meta_title_enhanced_order = '', // array // Optional // Pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
		$meta_title_enhanced_x2_addition = '', // string // Optional // Second word or phrase to inject into base meta title to form enhanced meta title level 2
		$meta_title_enhanced_x2_order = '', // array // Optional // Pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
		$meta_title_enhanced_x3_addition = '', // string // Optional // Third word or phrase to inject into base meta title to form enhanced meta title level 3
		$meta_title_enhanced_x3_order = '' // array // Optional // Pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number
	) {

		// Check/define variables

			// Meta title
			$meta_title_base_addition = ( isset($meta_title_base_addition) && !empty($meta_title_base_addition) ) ? $meta_title_base_addition : $page_title_attr;
			$meta_title_enhanced_addition = ( isset($meta_title_enhanced_addition) && !empty($meta_title_enhanced_addition) ) ? $meta_title_enhanced_addition : '';
			$meta_title_enhanced_x2_addition = ( isset($meta_title_enhanced_x2_addition) && !empty($meta_title_enhanced_x2_addition) ) ? $meta_title_enhanced_x2_addition : '';
			$meta_title_enhanced_x3_addition = ( isset($meta_title_enhanced_x3_addition) && !empty($meta_title_enhanced_x3_addition) ) ? $meta_title_enhanced_x3_addition : '';
			$meta_title_base_order = ( isset($meta_title_base_order) && !empty($meta_title_base_order) ) ? $meta_title_base_order : array( $meta_title_base_addition );
			$meta_title_enhanced_order = ( isset($meta_title_enhanced_order) && !empty($meta_title_enhanced_order) ) ? $meta_title_enhanced_order : array( $meta_title_base_addition, $meta_title_enhanced_addition );
			$meta_title_enhanced_x2_order = ( isset($meta_title_enhanced_x2_order) && !empty($meta_title_enhanced_x2_order) ) ? $meta_title_enhanced_x2_order : array( $meta_title_base_addition, $meta_title_enhanced_addition, $meta_title_enhanced_x2_addition );
			$meta_title_enhanced_x3_order = ( isset($meta_title_enhanced_x3_order) && !empty($meta_title_enhanced_x3_order) ) ? $meta_title_enhanced_x3_order : array( $meta_title_base_addition, $meta_title_enhanced_addition, $meta_title_enhanced_x2_addition, $meta_title_enhanced_x3_addition );

			// Get the site title
			// "UAMS Health"
			$meta_title_site_name = get_bloginfo( "name" );

			// Set the maximum number of characters for the meta title
			// The recommended length for meta titles is 50-60 characters.
			$meta_title_chars_max = 60;

			// Set the string that separates components of the meta title
			$meta_title_separator = ' | ';

		// Construct base meta title value
		// Example: "Leonard H. McCoy, M.D. | UAMS Health"
		// Example: "Medical Oncology Clinic | UAMS Health"
		// Example: "Cancer Care | UAMS Health"
		// Example: "Breastfeeding Law and Policy | UAMS Health"
		if (
			$meta_title_base_addition
			&&
			$meta_title_base_order
		) {
			$meta_title_base_order_count = count($meta_title_base_order);
			$meta_title_base = '';
			$i = 1;
			foreach( $meta_title_base_order as $name ) {
				$meta_title_base .= $name . $meta_title_separator;
				if ( $i == $meta_title_base_order_count ) {
					$meta_title_base .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// Set the base meta title value as the meta title
			$meta_title = $meta_title_base;
		} else {
			$meta_title_base = $meta_title_base_addition . $meta_title_separator . $meta_title_site_name;
		}

		// Set the base meta title as the meta title
		$meta_title = $meta_title_base;

		// Construct enhanced meta title level 1 value
		// Example: "Leonard H. McCoy, M.D. | Internist | UAMS Health"
		// Example: "Medical Oncology Clinic | Little Rock | UAMS Health"
		// Example: "Cancer Care | Area of Expertise | UAMS Health"
		// Example: "Breastfeeding Law and Policy | Clinical Resource | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_order
		) {
			$meta_title_enhanced_order_count = count($meta_title_enhanced_order);
			$meta_title_enhanced = '';
			$i = 1;
			foreach( $meta_title_enhanced_order as $name ) {
				$meta_title_enhanced .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_order_count ) {
					$meta_title_enhanced .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 1 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced) <= $meta_title_chars_max ) ? $meta_title_enhanced : $meta_title;
		}

		// Construct enhanced meta title level 2 value
		// Example: "Leonard H. McCoy, M.D. | Internist | Little Rock | UAMS Health"
		// Example: "Medical Oncology Clinic | Cancer Care | Little Rock | UAMS Health"
		// Example: "Breastfeeding Law and Policy | Video | Clinical Resource | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_x2_addition
			&&
			$meta_title_enhanced_x2_order
		) {
			$meta_title_enhanced_x2_order_count = count($meta_title_enhanced_x2_order);
			$meta_title_enhanced_x2 = '';
			$i = 1;
			foreach( $meta_title_enhanced_x2_order as $name ) {
				$meta_title_enhanced_x2 .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_x2_order_count ) {
					$meta_title_enhanced_x2 .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 2 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced_x2) <= $meta_title_chars_max ) ? $meta_title_enhanced_x2 : $meta_title;
		}

		// Construct enhanced meta title level 3 value
		// Example: "Leonard H. McCoy, M.D. | Internist | Primary Care | Little Rock | UAMS Health"
		if (
			$meta_title_enhanced_addition
			&&
			$meta_title_enhanced_x2_addition
			&&
			$meta_title_enhanced_x3_addition
			&&
			$meta_title_enhanced_x3_order
		) {
			$meta_title_enhanced_x3_order_count = count($meta_title_enhanced_x3_order);
			$meta_title_enhanced_x3 = '';
			$i = 1;
			foreach( $meta_title_enhanced_x3_order as $name ) {
				$meta_title_enhanced_x3 .= $name . $meta_title_separator;
				if ( $i == $meta_title_enhanced_x3_order_count ) {
					$meta_title_enhanced_x3 .= $meta_title_site_name;
				} else {
					$i++;
				}
			}

			// If the enhanced meta title level 3 value is within the character limit, set it as the meta title
			// Otherwise, use the previously-defined value
			$meta_title = ( strlen($meta_title_enhanced_x3) <= $meta_title_chars_max ) ? $meta_title_enhanced_x3 : $meta_title;
		}

		// Create an array to be used on the templates and template parts

			$meta_title_vars = array(
				'meta_title'	=> $meta_title, // string
			);

		// Return the variable
		return $meta_title_vars;

	}

// Remove meta boxes

	// Content Analysis

		/*

			Source: https://www.seopress.org/support/guides/disable-content-analysis-metabox/

		*/

		function uamswp_fad_metabox_seopress_content_analysis($seopress_get_post_types) {

			/*

				Define a post types array for which post types on which to display the
				SEOPress Content Analysis meta box (e.g., post and page).

				The documentation at
				https://www.seopress.org/support/guides/disable-content-analysis-metabox/
				says "Hook to remove the content analysis metabox by post type" which implies
				that the array should contain the post types for which to remove the meta box.
				However, in practice, it is the opposite.

				Notice the array_flip to convert values to keys.

			*/

			$seopress_get_post_types = array_flip(
				array(
					'post',
					'page'
				)
			);

			return $seopress_get_post_types;

		}
		add_filter('seopress_metaboxe_content_analysis', 'uamswp_fad_metabox_seopress_content_analysis');

	// SEO

		/*

			Based on documentation for removing Content Analysis meta box (found at
			https://www.seopress.org/support/guides/disable-content-analysis-metabox/)
			and the code in the wp-seopress-public GitHub repository at
			https://github.com/wp-seopress/wp-seopress-public/blob/master/inc/admin/metaboxes/admin-metaboxes.php

		*/

		function uamswp_fad_metabox_seopress_cpt($seopress_get_post_types) {

			/*

				Define a post types array for which post types on which to display the
				SEOPress SEO meta box (e.g., post and page).

			*/

			$seopress_get_post_types = array_flip(
				array(
					'post',
					'page'
				)
			);

			return $seopress_get_post_types;

		}
		add_filter('seopress_metaboxe_seo', 'uamswp_fad_metabox_seopress_cpt');