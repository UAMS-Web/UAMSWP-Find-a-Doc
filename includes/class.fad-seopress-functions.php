<?php
/*
 * SEOPress Functions
 */

// Override the theme's method of defining the meta title

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

	// Filter meta title
	// add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);
	function uamswp_fad_title($html) {
		// Bring in variables from outside of the function
		global $meta_title; // Defined in uamswp_fad_labels_clinical_resource()

		// Do stuff
		$html = $meta_title;

		return $html;
	}

// Override the theme's method of defining the Open Graph type

	// Filter Open Graph type
	// Filter hook function for 'seopress_social_og_type'
	// add_filter('seopress_social_og_type','uamswp_sp_social_og_type');
	function uamswp_sp_social_og_type($html) {
		// Bring in variables from outside of the function

		// Do stuff
		// default: <meta property="og:type" content="website" />

		return $html;
	}

// Override the theme's method of defining the title used in Oembed

	// Filter title used in Oembed
	// Filter hook function for 'seopress_oembed_title'
	// add_filter('seopress_oembed_title','uamswp_sp_oembed_title');
	function uamswp_sp_oembed_title($title) {
		// Bring in variables from outside of the function

		// Do stuff
		// default title: Custom Open Graph Title set from SEOPress metabox, else post title
		// $title = "My custom title for LinkedIn";

		return $title;
	}

// Override the theme's method of defining the post thumbnail size used in Oembed

	// Filter post thumbnail size used in Oembed
	// Filter hook function for 'seopress_oembed_thumbnail_size'
	// add_filter('seopress_oembed_thumbnail_size','uamswp_sp_oembed_thumbnail_size');
	function uamswp_sp_oembed_thumbnail_size($size) {
		// Bring in variables from outside of the function

		// Do stuff
		// default size: full
		// $size = "large";

		return $size;
	}

// Override the theme's method of defining the post thumbnail in Oembed

	// Filter post thumbnail in Oembed
	// Filter hook function for 'seopress_oembed_thumbnail'
	// add_filter('seopress_oembed_thumbnail','uamswp_sp_oembed_thumbnail');
	function uamswp_sp_oembed_thumbnail($thumbnail) {
		// Bring in variables from outside of the function

		// Do stuff
		// $thumbnail = ['url' => 'https://example.com/my-post-thumbnail-url', 'width' => '1920', 'height' => '1080'];

		return $thumbnail;
	}

// Override the theme's method of defining the OG updated time meta

	// Filter OG updated time meta
	// Filter hook function for 'seopress_titles_og_updated_time'
	// add_filter('seopress_titles_og_updated_time', 'uamswp_sp_titles_og_updated_time');
	function uamswp_sp_titles_og_updated_time($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta property="og:updated_time" content="2019-05-06T19:18:27+00:00" />';

		return $html;
	}

// Override the theme's method of defining the Open Graph Author / Publisher

	// Filter Open Graph Author / Publisher
	// Filter hook function for 'seopress_social_og_author'
	// add_filter('seopress_social_og_author', 'uamswp_sp_social_og_author');
	function uamswp_sp_social_og_author($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta property="article:author" content="https://www.facebook.com/seopresspro/" />'; 
		// $html .= '<meta property="article:publisher" content="https://www.facebook.com/seopresspro/" />'; 

		return $html;
	}

// Override the theme's method of defining the Twitter Card summary

	// Filter Twitter Card summary
	// Filter hook function for 'seopress_social_twitter_card_summary'
	// add_filter('seopress_social_twitter_card_summary', 'uamswp_sp_social_twitter_card_summary');
	function uamswp_sp_social_twitter_card_summary($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:card" content="summary_large_image">'; 

		return $html;
	}

// Override the theme's method of defining the Twitter Card site

	// Filter Twitter Card site
	// Filter hook function for 'seopress_social_twitter_card_site'
	// add_filter('seopress_social_twitter_card_site', 'uamswp_sp_social_twitter_card_site');
	function uamswp_sp_social_twitter_card_site($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:site" content="@wp_seopress" />'; 

		return $html;
	}

// Override the theme's method of defining the Twitter Card creator

	// Filter Twitter Card creator
	// Filter hook function for 'seopress_social_twitter_card_creator'
	// add_filter('seopress_social_twitter_card_creator', 'uamswp_sp_social_twitter_card_creator');
	function uamswp_sp_social_twitter_card_creator($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:creator" content="@wp_seopress" />'; 

		return $html;
	}

// Override the theme's method of defining the locale Open Graph

	// Filter locale Open Graph
	// Filter hook function for 'seopress_social_og_locale'
	// add_filter('seopress_social_og_locale', 'uamswp_sp_social_og_locale');
	function uamswp_sp_social_og_locale($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta property="og:locale" content="en_US" />';

		return $html;
	}

// Override the theme's method of defining the Twitter Card title

	// Filter Twitter Card title
	// Filter hook function for 'seopress_social_twitter_card_title'
	// add_filter('seopress_social_twitter_card_title', 'uamswp_sp_social_twitter_card_title');
	function uamswp_sp_social_twitter_card_title($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:title" content="my awesome title" />'; 

		return $html;
	}

// Override the theme's method of defining the Twitter Card thumbnail

	// Filter Twitter Card thumbnail
	// Filter hook function for 'seopress_social_twitter_card_thumb'
	// add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb');
	function uamswp_sp_social_twitter_card_thumb($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:image:src" content="https://www.seopress.org/wp-content/uploads/2016/12/cropped-ico-logo-seopress-256x256.png" />'; 

		return $html;
	}

// Override the theme's method of defining the Twitter Card description

	// Filter Twitter Card description
	// Filter hook function for 'seopress_social_twitter_card_desc'
	// add_filter('seopress_social_twitter_card_desc', 'uamswp_sp_social_twitter_card_desc');
	function uamswp_sp_social_twitter_card_desc($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta name="twitter:description" content="my awesome description" />'; 

		return $html;
	}

// Override the theme's method of defining the Open Graph URL

	// Filter Open Graph URL
	// Filter hook function for 'seopress_social_og_url'
	// add_filter('seopress_social_og_url', 'uamswp_sp_social_og_url');
	function uamswp_sp_social_og_url($html) {
		// Do stuff
		// $html = '<meta property="og:url" content="https://www.seopress.org/support/hooks/filter-canonical-url/" />'; 

		return $html;
	}

// Override the theme's method of defining the Open Graph title

	// Filter Open Graph title
	// Filter hook function for 'seopress_social_og_title'
	// add_filter('seopress_social_og_title', 'uamswp_sp_social_og_title');
	function uamswp_sp_social_og_title($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta property="og:title" content="my amazing og:title" />'; 

		return $html;
	}

// Override the theme's method of defining the Open Graph thumbnail

	// Filter Open Graph thumbnail
	// Filter hook function for 'seopress_social_og_thumb'
	// add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb');
	function uamswp_sp_social_og_thumb($html) { 
		// Bring in variables from outside of the function
		global $page_image; // image ID

		// you can add here all your conditions like if is_page(), is_category() etc.. 
		$html = '<meta property="og:image" content="' . $page_image . '" />'; 

		return $html;
	}

// Override the theme's method of defining the Open Graph sitename

	// Filter Open Graph sitename
	// Filter hook function for 'seopress_social_og_site_name'
	// add_filter('seopress_social_og_site_name', 'uamswp_sp_social_og_site_name');
	function uamswp_sp_social_og_site_name($html) { 
		// Bring in variables from outside of the function

		// Do stuff
		// $html = '<meta property="og:site_name" content="SEOPress" />'; 

		return $html;
	}

// Override the theme's method of defining the Open Graph description

	// Filter Open Graph description
	// Filter hook function for 'seopress_social_og_desc'
	// add_filter('seopress_social_og_desc', 'uamswp_sp_social_og_desc');
	function uamswp_sp_social_og_desc($html) { 
		// Bring in variables from outside of the function

		// you can add here all your conditions like if is_page(), is_category() etc.. 
		// $html = '<meta property="og:description" content="my amazing og:description" />'; 

		return $html;
	}