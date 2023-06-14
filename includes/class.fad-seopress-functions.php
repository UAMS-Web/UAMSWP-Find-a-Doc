<?php
/*
 * SEOPress Functions
 */

// Override theme's method of defining the meta page title

	// Variable definition function
	// Call before setting $meta_title on template
	function uamswp_fad_title_vars() {
		// Bring in variables from outside of the function
		global $page_title;
		global $page_title_attr;
		global $meta_title; // Optional pre-defined meta title // If pre-defined, make it attribute friendly
		global $meta_title_base_addition; // Word or phrase to use to form base meta title // Defaults to $page_title_attr
		global $meta_title_base_order; // Optional pre-defined array for name order of base meta title // Expects one value but will accommodate any number
		global $meta_title_enhanced_addition; // Word or phrase to inject into base meta title to form enhanced meta title level 1
		global $meta_title_enhanced_order; // Optional pre-defined array for name order of enhanced meta title level 1 // Expects two values but will accommodate any number
		global $meta_title_enhanced_x2_addition; // Second word or phrase to inject into base meta title to form enhanced meta title level 2
		global $meta_title_enhanced_x2_order; // Optional pre-defined array for name order of enhanced meta title level 2 // Expects three values but will accommodate any number
		global $meta_title_enhanced_x3_addition; // Third word or phrase to inject into base meta title to form enhanced meta title level 3
		global $meta_title_enhanced_x3_order; // Optional pre-defined array for name order of enhanced meta title level 3 // Expects four values but will accommodate any number

		// Make variables available outside of the function
		global $meta_title;

		// Check/define variables
		$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : get_the_title();
		$page_title_attr = ( isset($page_title_attr) && !empty($page_title_attr) ) ? $page_title_attr : ( function_exists('uamswp_attr_conversion') ? uamswp_attr_conversion($page_title) : $page_title);
		$meta_title = ( isset($meta_title) && !empty($meta_title) ) ? $meta_title : '';
		$meta_title_base_addition = ( isset($meta_title_base_addition) && !empty($meta_title_base_addition) ) ? $meta_title_base_addition : $page_title_attr;
		$meta_title_base_order = ( isset($meta_title_enhanced_order) && !empty($meta_title_enhanced_order) ) ? $meta_title_enhanced_order : array( $meta_title_base_addition );
		$meta_title_enhanced_addition = ( isset($meta_title_enhanced_addition) && !empty($meta_title_enhanced_addition) ) ? $meta_title_enhanced_addition : '';
		$meta_title_enhanced_order = ( isset($meta_title_enhanced_order) && !empty($meta_title_enhanced_order) ) ? $meta_title_enhanced_order : array( $meta_title_base_addition, $meta_title_enhanced_addition );
		$meta_title_enhanced_x2_addition = ( isset($meta_title_enhanced_x2_addition) && !empty($meta_title_enhanced_x2_addition) ) ? $meta_title_enhanced_x2_addition : '';
		$meta_title_enhanced_x2_order = ( isset($meta_title_enhanced_x2_order) && !empty($meta_title_enhanced_x2_order) ) ? $meta_title_enhanced_x2_order : array( $meta_title_base_addition, $meta_title_enhanced_addition, $meta_title_enhanced_x2_addition );
		$meta_title_enhanced_x3_addition = ( isset($meta_title_enhanced_x3_addition) && !empty($meta_title_enhanced_x3_addition) ) ? $meta_title_enhanced_x3_addition : '';
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
		// "Leonard H. McCoy, M.D. | UAMS Health"
		// "Medical Oncology Clinic | UAMS Health"
		// "Cancer Care | UAMS Health"
		// "Breastfeeding Law and Policy | UAMS Health"
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
		// "Leonard H. McCoy, M.D. | Internist | UAMS Health"
		// "Medical Oncology Clinic | Little Rock | UAMS Health"
		// "Cancer Care | Area of Expertise | UAMS Health"
		// "Breastfeeding Law and Policy | Clinical Resource | UAMS Health"
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
		// "Leonard H. McCoy, M.D. | Internist | Little Rock | UAMS Health"
		// "Medical Oncology Clinic | Cancer Care | Little Rock | UAMS Health"
		// "Breastfeeding Law and Policy | Video | Clinical Resource | UAMS Health"
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
		// "Leonard H. McCoy, M.D. | Internist | Primary Care | Little Rock | UAMS Health"
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
	}

	// Filter hook function for 'seopress_titles_title'
	function uamswp_fad_title($html) {
		// Bring in variables from outside of the function
		global $meta_title; // Defined in uamswp_fad_labels_clinical_resource()

		//you can add here all your conditions as if is_page(), is_category() etc.. 
		$html = $meta_title;

		return $html;
	}