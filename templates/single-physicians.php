<?php 
/*
 * Template Name: Single Provider
 */

// Get the page ID

	$page_id = get_the_ID();

// Pass fields to schema function

	// Base array

		$provider_schema_fields = array();
		$provider_schema_fields[$page_id] = array();

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/provider.php' );
	$provider_schema_fields[$page_id]['provider_plural_name_attr'] = $provider_plural_name_attr; // Pass value to schema function

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

	// Get system settings for combined condition and treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition-treatment.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/treatment.php' );

// // Get system settings for this post type's archive page text
// include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/archive/provider.php' );

// Ontology / Content Type

	$ontology_type = true; // Ontology type of the post (true is ontology type, false is content type)

// Get the page title and other name values

	// Get the elements of the provider's name

		// First name

			$first_name = get_field('physician_first_name',$post->ID);
			$first_name_attr = $first_name ? uamswp_attr_conversion($first_name) : '';
			$provider_schema_fields[$page_id]['provider_givenName'] = $first_name_attr; // Pass value to schema function

		// Middle name

			$middle_name = get_field('physician_middle_name',$post->ID);
			$middle_name_attr = $middle_name ? uamswp_attr_conversion($middle_name) : '';
			$provider_schema_fields[$page_id]['provider_additionalName'] = $middle_name_attr; // Pass value to schema function

		// Nickname

			$nickname = null;
			$nickname_attr = $nickname ? uamswp_attr_conversion($nickname) : null;
			$provider_schema_fields[$page_id]['provider_nickname'] = $nickname_attr; // Pass value to schema function

		// Last name

			$last_name = get_field('physician_last_name',$post->ID);
			$last_name_attr = $last_name ? uamswp_attr_conversion($last_name) : '';
			$provider_schema_fields[$page_id]['provider_familyName'] = $last_name_attr; // Pass value to schema function

		// Generational suffix (e.g., Jr.)

			$pedigree = get_field('physician_pedigree',$post->ID);
			$pedigree_attr = $pedigree ? uamswp_attr_conversion($pedigree) : '';
			$provider_schema_fields[$page_id]['provider_generational_suffix'] = $pedigree_attr; // Pass value to schema function

		// Degrees and credentials (e.g., M.D., Ph.D.)

			$degrees = get_field('physician_degree',$post->ID);
			$degrees = array_filter($degrees);
			$degrees = array_unique($degrees);
			$degrees = array_values($degrees);
			$provider_schema_fields[$page_id]['provider_degrees'] = $degrees; // Pass value to schema function
			$degree_count = $degrees ? count($degrees) : 0;
			$degree_list = '';
			$degree_list_attr = '';
			$degree_attr_array = array();
			$i = 1;

			if ( $degrees ) {

				foreach ( $degrees as $degree ) {

					$degree_term = get_term( $degree, 'degree');

					if ( is_object($degree_term) ) {

						$degree_name = $degree_term->name;
						$degree_list .= $degree_name;
						$degree_attr_array[] = uamswp_attr_conversion($degree_name);

						if ( $degree_count > $i ) {

							$degree_list .= ', ';

						} // endif ( count($degrees) > $i )

						$i++;

					}

				} // endforeach

			} // endif ( $degrees )

			if ( $degree_list ) {

				$degree_list_attr = uamswp_attr_conversion($degree_list);

			}

			$provider_schema_fields[$page_id]['provider_degree_array'] = $degree_attr_array; // Pass value to schema function
			$provider_schema_fields[$page_id]['provider_degree_list'] = $degree_list_attr; // Pass value to schema function

			// Remove empty rows

				$degree_attr_array = array_filter($degree_attr_array);

			// Remove duplicate rows

				$degree_attr_array = array_unique( $degree_attr_array, SORT_REGULAR );

			// Reindex array

				$degree_attr_array = array_values($degree_attr_array);

		// Dr. Prefix

			// Define list of degrees or credentials need for "Dr." prefix (per UAMS Health clinical administration)

				$prefix_degrees = array(
					'M.D.',
					'D.O.'
				);

			// Set the "Dr." prefix

				// Eliminate PHP errors

					$prefix = '';
					$prefix_attr = '';

				if ( in_array( $prefix_degrees, $degree_attr_array, true ) ) {

					$prefix = 'Dr.';
					$prefix_attr = uamswp_attr_conversion($prefix);

				}

				$provider_schema_fields[$page_id]['provider_honorificPrefix'] = $prefix_attr; // Pass value to schema function

	// Construct the variants of the provider's name

		// Full name (e.g., "Leonard H. McCoy, M.D.")

			$full_name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '') . ( $degree_list ? ', ' . $degree_list : '' );
			$full_name_attr = $full_name ? uamswp_attr_conversion($full_name) : '';

		// Legal name (for schema)

			$legal_name = null;
			$legal_name_attr = $full_name_attr ? uamswp_attr_conversion($legal_name) : null;
			$provider_schema_fields[$page_id]['provider_legalName'] = $legal_name_attr; // Pass value to schema function

		// Medium name (e.g., "Dr. Leonard H. McCoy")

			$medium_name = ($prefix ? $prefix .' ' : '') . $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;
			$medium_name_attr = $medium_name ? uamswp_attr_conversion($medium_name) : '';

		// Short name (e.g., "Dr. McCoy")

			$short_name = $prefix ? $prefix .'&nbsp;' .$last_name : $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '');
			$short_name_attr = $short_name ? uamswp_attr_conversion($short_name) : '';

		// Short name possessive (e.g., "Dr. McCoy's")

			if ( substr($short_name, -1) == 's' ) {

				// If the provider's name ends in "s"...

				// Use an apostrophe with no "s" when indicating the possessive form
				$short_name_possessive = $short_name . '\'';

			} else {

				// Use an apostrophe with an "s" when indicating the possessive form
				$short_name_possessive = $short_name . '\'s';

			}

			$short_name_possessive_attr = $short_name_possessive ? uamswp_attr_conversion($short_name_possessive) : '';

		// Page title

			$page_title = $full_name;
			$page_title_attr = $full_name_attr;
			$provider_schema_fields[$page_id]['provider_name'] = $page_title_attr; // Pass value to schema function

		// Sort name (e.g., "McCoy, Leonard H.")

			$sort_name = $last_name . ', ' . $first_name . ' ' . $middle_name;
			$sort_name_attr = $sort_name ? uamswp_attr_conversion($sort_name) : '';

		// Sort name parameter (e.g., "mccoy-leonard-h")

			$sort_name_param_value = sanitize_title_with_dashes($sort_name);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'					=> $page_title,
			'page_title_attr'				=> $page_title_attr,
			'full_name'						=> $full_name,
			'full_name_attr'				=> $full_name_attr,
			'legal_name'					=> $legal_name,
			'legal_name_attr'				=> $legal_name_attr,
			'medium_name'					=> $medium_name,
			'medium_name_attr'				=> $medium_name_attr,
			'sort_name'						=> $sort_name,
			'sort_name_attr'				=> $sort_name_attr,
			'sort_name_param_value'			=> $sort_name_param_value,
			'short_name'					=> $short_name,
			'short_name_attr'				=> $short_name_attr,
			'short_name_possessive'			=> $short_name_possessive,
			'short_name_possessive_attr'	=> $short_name_possessive_attr
		);

	// Get system settings for elements of a fake subpage (or section) in an Provider subsection (or profile)

		// Get system settings for text elements in a provider subsection (or profile)
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/specific-placement/provider.php' );

		// Image elements

			// Do nothing

	// Name of ontology item type represented by this fake subpage

		// Do nothing

	// Fake subpage page title

		// Do nothing

	// Fake subpage intro text

		// Do nothing

// Get the page URL and slug

	$page_url = user_trailingslashit(get_permalink());
	$provider_schema_fields[$page_id]['provider_url'] = $page_url; // Pass value to schema function
	$page_slug = $post->post_name;

	// Fake subpage

		// Do nothing

// Get site header and site nav values for ontology subsections

	// Do nothing

// Image values

	// Get the featured image ID

		$featured_image = get_field('_thumbnail_id', $post->ID) ?? 0; // int // Featured image ID
		$provider_schema_fields[$page_id]['provider_image_id'] = $featured_image; // Pass value to schema function

	// Get the wide image ID

		$headshot_wide = get_field('physician_image_wide', $post->ID) ?? 0;
		$provider_schema_fields[$page_id]['provider_image_wide_id'] = $headshot_wide; // Pass value to schema function

	// Schema image

		$schema_image = '';

		if (
			$featured_image
			&&
			function_exists( 'fly_add_image_size' )
		) {

			$schema_image = image_sizer($featured_image, 778, 1038, 'center', 'center');

		} elseif ( $featured_image ) {

			$schema_image = get_the_post_thumbnail( 'large' );

		} // endif ( $featured_image && function_exists( 'fly_add_image_size' ) ) elseif ( $featured_image )

// Define the placement for content

	$content_placement = 'profile'; // Expected values: 'subsection' or 'profile'

// Query for whether to conditionally suppress ontology sections based on based on region and service line

	$regions = get_field('physician_region',$post->ID);
	$service_lines = get_field('physician_service_line',$post->ID);

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/ontology-hide.php' );

// HEAD

	// Title tag

		// Get relevant values

			// Related Locations Section Query

				$locations = get_field( 'physician_locations', $post->ID ) ?? array();
				$provider_schema_fields[$page_id]['provider_location_array'] = $locations; // Pass value to schema function
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

			// Get the name of the provider's primary location

				// Eliminate PHP errors

					$primary_appointment_title = '';
					$primary_appointment_title_attr = '';
					$primary_appointment_url = '';
					$primary_appointment_city = '';
					$primary_appointment_city_attr = '';

				if ( $location_section_show ) {

					foreach ( $locations as $location ) {

						if ( get_post_status ( $location ) == 'publish' ) {

							$primary_appointment_title = get_the_title( $location );
							$primary_appointment_title_attr = uamswp_attr_conversion($primary_appointment_title);
							$primary_appointment_url = user_trailingslashit(get_the_permalink( $location ));
							$primary_appointment_city = get_field('location_city', $location);
							$primary_appointment_city_attr = uamswp_attr_conversion($primary_appointment_city);

							break;

						}

					}

				}

			// Related Areas of Expertise Section Query

				$expertises = get_field('physician_expertise',$post->ID);
				$provider_schema_fields[$page_id]['provider_expertise_list'] = $expertises; // Pass value to schema function
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/expertise.php' );

			// Get the name of the provider's primary area of expertise

				// Eliminate PHP errors

					$expertise_primary_name = '';
					$expertise_primary_name_attr = '';

				if ( $expertise_section_show ) {

					foreach ( $expertises as $expertise ) {

						if ( get_post_status ( $expertise ) == 'publish' ) {

							$expertise_primary_name = get_the_title($expertise);
							$expertise_primary_name_attr = uamswp_attr_conversion($expertise_primary_name);

							break;

						}
					}
				}

			// Get resident values

				$resident = get_field( 'physician_resident', $post->ID ); // bool
				$resident_title_name = 'Resident Physician';

			// Get clinical specialty and occupation title values

				// Eliminate PHP errors

					$provider_specialty = '';
					$provider_specialty_term = '';
					$provider_specialty_name = '';
					$provider_occupation_title = '';
					$provider_schema_fields[$page_id]['provider_clinical_specialization'] = ''; // Pass value to schema function
					$provider_schema_fields[$page_id]['provider_clinical_specialization_term'] = ''; // Pass value to schema function
					$provider_schema_fields[$page_id]['provider_jobTitle'] = ''; // Pass value to schema function

				if ( $resident ) {

					$provider_occupation_title = $resident_title_name;
					$provider_occupation_title_attr = uamswp_attr_conversion($provider_occupation_title);

				} else {

					// Clinical Occupation Title

						$provider_specialty = get_field( 'physician_title', $post->ID );
						$provider_schema_fields[$page_id]['provider_clinical_specialization'] = $provider_specialty; // Pass value to schema function

						if ( $provider_specialty ) {

							$provider_specialty_term = get_term($provider_specialty, 'clinical_title');

							if ( is_object($provider_specialty_term) ) {

								$provider_specialty_name = $provider_specialty_term->name;
								$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term);
								$provider_occupation_title = $provider_occupation_title ?: $provider_specialty_name;
								$provider_occupation_title_attr = uamswp_attr_conversion($provider_occupation_title);

							}

						}

				}

				$provider_specialty_name_attr = uamswp_attr_conversion($provider_specialty_name);
				$provider_occupation_title_attr = uamswp_attr_conversion($provider_occupation_title);
				$provider_schema_fields[$page_id]['provider_clinical_specialization'] = $provider_specialty; // Pass value to schema function
				$provider_schema_fields[$page_id]['provider_clinical_specialization_term'] = $provider_specialty_term; // Pass value to schema function
				$provider_schema_fields[$page_id]['provider_jobTitle'] = $provider_occupation_title_attr; // Pass value to schema function

				// Defines the indefinite article to precede the clinical occupation title (a or an, based on whether clinical occupation title starts with vowel)

					if (
						in_array(
							strtolower($provider_occupation_title)[0],
							array( 'a', 'e', 'i', 'o', 'u' )
						)
					) { 

						// If the clinical occupation title starts with a vowel, use "an"
						$provider_occupation_title_indef_article = 'an'; 

					} else {

						// If the clinical occupation title does not start with a vowel, use "a"
						$provider_occupation_title_indef_article = 'a'; 

					}

				// Define a list of exceptions to the vowel-based determination of which indefinite article to use.

					/*
					 * - Use "a" before consonant sounds: a historic event, a one-year term.
					 * - Use "an" before vowel sounds: an honor, an NBA record.
					 * - Write the key as the characters at the beginning of the exception. It can be a complete or incomplete title.
					 * - Write the value as the indefinite article to use in that case ('a' or 'an').
					 */

					$provider_occupation_title_indef_article_exceptions = array(
						'SNF' => 'an',
						'Urolog' => 'a',
						'Uveitis' => 'a'
					);

					if ( !empty($provider_occupation_title_indef_article_exceptions) ) {

						foreach( $provider_occupation_title_indef_article_exceptions as $exception => $indef_article ) {

							if (
								substr(
									strtolower($provider_occupation_title),
									0,
									strlen($exception)
								) == strtolower($exception)
							) {

								// If the clinical occupation title begins with the exception key...

								// Use the key's value as the indefinite article
								$provider_occupation_title_indef_article = $indef_article; 

							}

						}

					}

		// Construct the title tag value

			$meta_title_enhanced_addition = $provider_occupation_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title
			$meta_title_enhanced_x2_addition = $primary_appointment_city_attr; // Second word or phrase to inject into base meta title to form enhanced meta title
			$meta_title_enhanced_x3_addition = $expertise_primary_name_attr; // Third word or phrase to inject into base meta title to form enhanced meta title
			$meta_title_enhanced_x3_order = array( $page_title_attr, $meta_title_enhanced_addition, $meta_title_enhanced_x3_addition, $meta_title_enhanced_x2_addition ); // Optional pre-defined array for name order of enhanced meta title level 3 // Expects four values
			include( UAMS_FAD_PATH . '/templates/parts/html/meta/title.php' );

		// Override SEOPress's standard title tag settings

			add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

				if ( $meta_title ) {

					$html = $meta_title;

				}

				return $html;

			}, 15, 2 );

	// Meta Description and Schema Description

		// Get excerpt

			$bio_short = get_field( 'physician_short_clinical_bio',$post->ID );
			$excerpt = $bio_short;
			$excerpt_user = true;

		// Get clinical bio

			$bio = get_field('physician_clinical_bio',$post->ID);
			$content = $bio;

		// Create excerpt if none exists

			if ( empty( $excerpt ) ) {

				$excerpt_user = false;

				if ( $content ) {

					$excerpt = wp_strip_all_tags($content);
					$excerpt = str_replace("\n", ' ', $excerpt); // Strip line breaks
					$excerpt = strlen($excerpt) > 160 ? mb_strimwidth($excerpt, 0, 156, '...') : $excerpt; // Limit to 160 characters

				} else {

					$fallback_desc = $medium_name_attr . ' is ' . ($provider_occupation_title ? $provider_occupation_title_indef_article . ' ' . strtolower($provider_occupation_title) : 'a health care provider' ) . ($primary_appointment_title_attr ? ' at ' . $primary_appointment_title_attr : '') . ' employed by UAMS Health.';

					$excerpt = wp_strip_all_tags($fallback_desc);
					$excerpt = str_replace("\n", ' ', $excerpt); // Strip line breaks
					$excerpt = strlen($excerpt) > 160 ? mb_strimwidth($excerpt, 0, 156, '...') : $excerpt; // Limit to 160 characters

				}

			}

		$excerpt_attr = uamswp_attr_conversion($excerpt);
		$provider_schema_fields[$page_id]['provider_description_text'] = $excerpt_attr; // Pass value to schema function

		// Set schema description

			$schema_description = $excerpt_attr; // Used for Schema Data. Should ALWAYS have a value

		// Override theme's method of defining the meta description

			add_filter('seopress_titles_desc', function( $html ) use ( $excerpt_attr ) {

				if ( $excerpt_attr ) {

					$html = $excerpt_attr;

				}
				return $html;

			} );

	// Meta Keywords

		// $keywords = '';
		// 
		// // Override theme's standard meta keywords settings
		// 
		// 	add_action( 'wp_head', function() use ( $keywords ) {
		// 		uamswp_keyword_hook_header(
		// 			$keywords // array
		// 		);
		// 	} );

	// Canonical URL

		// Do nothing

	// Meta Social Media Tags

		// Define Open Graph type (og:type) values

			$meta_og_type = 'profile';

		// Define the Open Graph profile property array (profile:*)

			$meta_og_type_property = array();

			// Profile first name

				if (
					isset($first_name_attr)
					&&
					!empty($first_name_attr)
				) {
					$meta_og_type_property['profile:first_name'] = $first_name_attr; // string // A name normally given to an individual by a parent or self-chosen.
				}

			// Profile last name

				if (
					isset($last_name_attr)
					&&
					!empty($last_name_attr)
				) {
					$meta_og_type_property['profile:last_name'] = $last_name_attr; // string // A name inherited from a family or marriage and by which the individual is commonly known.
				}

			// Profile username

				$meta_og_profile_username = '';
				$meta_og_profile_username_attr = uamswp_attr_conversion($meta_og_profile_username);
				if (
					isset($meta_og_profile_username_attr)
					&&
					!empty($meta_og_profile_username_attr)
				) {
					$meta_og_type_property['profile:username'] = $meta_og_profile_username_attr; // string // A short unique string to identify them.
				}

			// Profile gender

				// Get the provider's gender

					$gender = get_field('physician_gender',$post->ID);
					$gender_attr = uamswp_attr_conversion($gender);
					$provider_schema_fields[$page_id]['provider_gender_value'] = $gender_attr; // Pass value to schema function

				$meta_profile_gender = $gender_attr ? strtolower($gender_attr) : '';
				$meta_profile_gender = ( $meta_profile_gender == 'male' || $meta_profile_gender == 'female' ) ? $meta_profile_gender : ''; // Check against enum(male, female)

				if ( $meta_profile_gender ) {

					$meta_og_type_property['profile:gender'] = $meta_profile_gender; // enum(male, female) // Their gender.

				}

		// Filter hooks
		include( UAMS_FAD_PATH . '/templates/parts/html/meta/social.php' );

// BODY

	// Add page template class to body element's classes

		// $template_type = '';
		// add_filter( 'body_class', function( $classes ) use ( $template_type ) {
		// 
		// 	// Add page template class to body class array
		// 
		// 		if ( $template_type ) {
		// 
		// 			$classes[] = 'page-template-' . $template_type;
		// 
		// 		}
		// 
		// 	return $classes;
		// 
		// } );

	// Header

		get_header();

		// Site header

			// Remove the site header set by the theme

				// remove_action( 'genesis_header', 'uamswp_site_image', 5 );

			// Add ontology subsection site header

				// add_action( 'genesis_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/html/site-header/single-expertise.php');
				// }, 5 );

		// Primary navigation

			// Remove the primary navigation set by the theme

				// remove_action( 'genesis_after_header', 'genesis_do_nav' );
				// remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

			// Add ontology subsection primary navigation

				// add_action( 'genesis_after_header', function() use (
				// 	$page_id,
				// 	$ontology_type,
				// 	$page_title,
				// 	$page_url
				// ) {
				// 	include( UAMS_FAD_PATH . '/templates/parts/html/site-nav/single-expertise.php');
				// }, 5 );

	// Breadcrumbs

		// Override Genesis standard breadcrumbs settings

			add_filter( 'genesis_single_crumb', function( $crumb, $args ) use ( $full_name ) {

				// Replace the last item in the breadcrumbs with the provider name
				return substr( $crumb, 0, strrpos( $crumb, $args['sep'] ) ) . $args['sep'] . $full_name;

			}, 10, 2 );

		// Override SEOPress standard breadcrumbs settings

			add_filter('seopress_pro_breadcrumbs_crumbs', function( $crumbs ) use ( $full_name ) {

				// Get the last value in the breadcrumbs array
				$crumb = array_pop($crumbs);

				// Get the provider name and its URL
				$provider_name = array($full_name, get_permalink());

				// Add the provider details to the breadcrumbs array
				array_push($crumbs, $provider_name);

				return $crumbs;

			}, 20);

	// Page Header (before entry element)

		// Remove Genesis-standard post title and markup

			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
			// remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
			// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

		// Construct non-standard post title

			// $entry_header_style = ''; // Entry header style
			// $entry_title_text = ''; // Regular title
			// $entry_title_text_supertitle = ''; // Optional supertitle, placed above the regular title
			// $entry_title_text_subtitle = ''; // Optional subtitle, placed below the regular title
			// $entry_title_text_body = ''; // Optional lead paragraph, placed below the entry title
			// $entry_title_image_desktop = ''; // Desktop breakpoint image ID
			// $entry_title_image_mobile = ''; // Optional mobile breakpoint image ID
			// 
			// add_action( 'genesis_before_content', function() use (
			// 	$entry_title_text,
			// 	$entry_header_style,
			// 	$entry_title_text_supertitle,
			// 	$entry_title_text_subtitle,
			// 	$entry_title_text_body,
			// 	$entry_title_image_desktop,
			// 	$entry_title_image_mobile
			// ) {
			// 
			// 	// Check/define variables
			// 	$entry_header_style = ( isset($entry_header_style) && !empty($entry_header_style) ) ? $entry_header_style : 'graphic';
			// 
			// 	include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/' . $entry_header_style . '.php');
			// 
			// } );

	// MAIN / ARTICLE

		// Add bg-white class to article.entry element

			// add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

		// Start count for jump links
		$jump_link_count = 0;

		// Queries for whether each of the sections should be displayed

			// Related Clinical Resources Section Query

				$clinical_resources = get_field('physician_clinical_resources');
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
				$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

			// Related Conditions Section Query

				$conditions_cpt = get_field('physician_conditions_cpt');
				$provider_schema_fields[$page_id]['provider_condition_list'] = $conditions_cpt; // Pass value to schema function
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/condition.php' );

			// Related Treatments Section Query

				$treatments_cpt = get_field('physician_treatments_cpt');
				$provider_schema_fields[$page_id]['provider_treatment'] = $treatments_cpt; // Pass value to schema function
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/treatment.php' );

			// Query for whether UAMS Health Talk podcast section should be displayed on ontology pages/subsections

				$podcast_name = get_field('physician_podcast_name');
				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/podcast.php' );

			// Check if Clinical Bio section should be displayed

				$provider_clinical_bio = get_field('physician_clinical_bio');
				$video = get_field('physician_youtube_link');
				$provider_schema_fields[$page_id]['provider_video_url'] = $video; // Pass value to schema function

				if (
					$provider_clinical_bio
					||
					!empty ($video)
				) {

					$clinical_bio_section_show = true;

				} else {

					$clinical_bio_section_show = false;

				}

			// Check if Academic Background section should be displayed

				$academic_bio = get_field('physician_academic_bio');
				$academic_appointment = get_field('physician_academic_appointment');
				$academic_admin_title = get_field('physician_academic_admin_title');
				$education = get_field('physician_education');
				$boards = get_field( 'physician_boards' );

				if (
					$resident
					||
					$academic_bio
					||
					$academic_appointment
					||
					$academic_admin_title
					||
					$education
					||
					$boards
				) {

					$academic_section_show = true;
					$jump_link_count++;

				} else {

					$academic_section_show = false;

				}

			// Check if Research section should be displayed

				$research_bio = get_field('physician_research_bio');
				$research_interests = get_field('physician_research_interests');
				$publications = get_field('physician_select_publications');
				$pubmed_author_id = get_field('physician_pubmed_author_id');
				$research_profiles_link = get_field('physician_research_profiles_link');

				if (
					!empty($research_bio)
					||
					!empty($research_interests)
					||
					!empty($publications)
					||
					$pubmed_author_id
					||
					$research_profiles_link
				) {

					$research_section_show = true;
					$jump_link_count++;

				} else {

					$research_section_show = false;

				}

			// Check if Ratings section should be displayed

				$npi = get_field('physician_npi');
				$npi = $npi ? str_pad($npi, 10, '0', STR_PAD_LEFT) : ''; // Add enough leading zeroes to reach 10 digits
				$provider_schema_fields[$page_id]['provider_npi'] = $npi; // Pass value to schema function

				if ( $npi ) {

					$rating_request = wp_nrc_cached_api( $npi );
					$rating_data = json_decode( $rating_request );
					$rating_valid = !empty($rating_data) ? $rating_data->valid : false;
					$rating_valid = $rating_valid ? true : false;

				} else {

					// Eliminate PHP errors

						$rating_request = '';
						$rating_data = '';
						$rating_valid = false;

				}

				$provider_schema_fields[$page_id]['provider_aggregateRating_api'] = $rating_data; // Pass value to schema function
				$provider_schema_fields[$page_id]['provider_aggregateRating_query'] = $rating_valid; // Pass value to schema function

				if ( $rating_valid ) {

					$ratings_section_show = true;
					$jump_link_count++;

				} else {

					$ratings_section_show = false;

				}

			// Query for whether Make an Appointment section should be displayed

				// Check if the provider sees patients via appointments

					if ( $resident ) {

						$eligible_appt = false;

					} else {

						$eligible_appt = get_field('physician_eligible_appointments',$post->ID) ?? false;

					}

				if ( $eligible_appt ) {

					$appointment_section_show = true;
					$jump_link_count++;

				} else {

					$appointment_section_show = false;

				}

		// Get remaining details about this item

			$service_line = get_field('physician_service_line');
			$affiliation = get_field('physician_affiliation');
			$provider_schema_fields[$page_id]['provider_hospitalAffiliation_multiselect'] = $affiliation; // Pass value to schema function
			$hidden = get_field('physician_hidden');

			if ( $resident ) {

				$resident_profile_group = get_field('physician_resident_profile_group');

				if ( $resident_profile_group ) {

					// $resident_hometown_international = $resident_profile_group['physician_resident_hometown_international'];
					// $resident_hometown_city = $resident_profile_group['physician_resident_hometown_city'];
					// $resident_hometown_state = $resident_profile_group['physician_resident_hometown_state'];
					// $resident_hometown_country = $resident_profile_group['physician_resident_hometown_country'];
					// $resident_medical_school = $resident_profile_group['physician_resident_hometown_country'];
					$resident_academic_department = $resident_profile_group['physician_resident_academic_department'];
					$resident_academic_department_name = $resident_academic_department ? get_term( $resident_academic_department, 'academic_department' )->name : '';
					$resident_academic_chief = $resident_profile_group['physician_resident_academic_chief'];
					$resident_academic_chief_name = $resident_academic_chief ? 'Chief Resident' : '';
					$resident_academic_year = $resident_profile_group['physician_resident_academic_year'];
					$resident_academic_year_name = $resident_academic_year ? get_term( $resident_academic_year, 'residency_year' )->name : '';
					$resident_academic_name = $resident_academic_chief ? $resident_academic_chief_name : $resident_academic_year_name;

				}

			}
			$college_affiliation = get_field('physician_academic_college');
			$position = get_field('physician_academic_position');
			$bio_academic = get_field('physician_academic_bio');
			$bio_academic_short = get_field('physician_academic_short_bio');
			$office_location = get_field('physician_academic_office');
			$office_building = get_field('physician_academic_map');
			$bio_research = get_field('physician_research_bio');
			$research_interests = get_field('physician_research_interests');
			$research_profile = get_field('physician_research_profiles_link');
			$additional_info = get_field('physician_additional_info');
			$second_opinion = get_field('physician_second_opinion') ?? false;
			$patients = get_field('physician_patient_types');
			$refer_req = get_field('physician_referral_required') ?? false;
			$accept_new = get_field('physician_accepting_patients') ?? false;
			$provider_schema_fields[$page_id]['provider_isAcceptingNewPatients'] = $accept_new; // Pass value to schema function
			$provider_portal = get_field('physician_portal');
			// $provider_youtube_link = get_field('physician_youtube_link');
			$provider_clinical_admin_title = get_field('physician_clinical_admin_title');
			$provider_clinical_focus = get_field('physician_clinical_focus');
			$provider_awards = get_field('physician_awards');
			$provider_additional_info = get_field('physician_additional_info');
			$pubmed_author_number = get_field('physician_author_number');

			// Construct a list of the provider's languages (e.g., "English, Spanish")

				$languages = get_field('physician_languages',$post->ID);
				$provider_schema_fields[$page_id]['provider_languages'] = $languages; // Pass value to schema function
				$language_count = $languages ? count($languages) : 0;
				$language_list = '';
				$schema_provider_languages = uamswp_fad_schema_language(
					$languages, // mixed // Required // Language ID values
					$language_list // string // Optional // Pre-existing string variable to populate with a comma-separated list of language names
				);
				$provider_schema_fields[$page_id]['provider_knowsLanguage'] = $schema_provider_languages; // Pass value to schema function

			// Construct a list of the provider's health care professional associations

				// Get association input value

					$provider_associations = get_field('physician_associations') ?? array();
					$provider_schema_fields[$page_id]['provider_associations'] = $provider_associations; // Pass value to schema function

				// Define empty variable to receive association names

					$provider_associations_names = array();

				// Format values

					$provider_schema_fields[$page_id]['provider_memberOf'] = array(); // Pass value to schema function

					if ( $provider_associations ) {

						$provider_schema_fields[$page_id]['provider_memberOf'] = uamswp_fad_schema_associations(
							$provider_associations, // mixed // Required // Health care professional association ID values
							$provider_associations_names // array // Optional // Pre-existing array variable to populate with a list of association names
						); // Add to schema fields

					}

			// Get the age of the provider portrait

				// Eliminate PHP errors

					$image_too_old = false;

				if ( $featured_image ) {

					$image_age = date('Y') - get_the_date( 'Y', $featured_image ); // How old the provider image is in years
					$image_age_threshold = 10; // Set the threshold for how old a provider image can be before a new photo is needed

					if ( $image_age >= $image_age_threshold ) {

						$image_age = $image_age_threshold . '+'; // Cap attribute value at the threshold
						$image_too_old = true;

					}

				}

		// Get remaining details content associated with this item

			// Do nothing

		// Classes for indicating presence of content

			$provider_field_classes = '';
			$provider_field_classes .= ( $degrees && !empty($degrees) ) ? ' has-degrees' : '';
			$provider_field_classes .= ( $prefix && !empty($prefix) ) ? ' has-prefix' : '';
			$provider_field_classes .= ( $featured_image && !empty($featured_image) ) ? ' has-image' : '';
			$provider_field_classes .= ( $image_too_old ) ? ' has-old-image' : '';
			$provider_field_classes .= ( $service_line && !empty($service_line) ) ? ' has-service-line' : '';
			$provider_field_classes .= ( $npi && !empty($npi) ) ? ' has-npi' : '';
			$provider_field_classes .= $rating_valid ? ' has-ratings' : '';
			$provider_field_classes .= ( $bio && !empty($bio) ) ? ' has-clinical-bio' : '';
			$provider_field_classes .= ( $bio_short && !empty($bio_short) ) ? ' has-short-clinical-bio' : '';
			$provider_field_classes .= ( $video && !empty($video) ) ? ' has-video' : '';
			$provider_field_classes .= ( $condition_section_show ) ? ' has-condition' : '';
			$provider_field_classes .= ( $treatment_section_show ) ? ' has-treatment' : '';
			$provider_field_classes .= ( $location_section_show ) ? ' has-location' : '';
			$provider_field_classes .= ( $clinical_resource_section_show ) ? ' has-clinical-resource' : '';
			$provider_field_classes .= ( $affiliation && !empty($affiliation) ) ? ' has-affiliation' : '';
			$provider_field_classes .= ( $expertise_section_show ) ? ' has-expertise' : '';
			$provider_field_classes .= ( $hidden && !empty($hidden) ) ? ' has-hidden' : '';
			// Add one instance of a class (' has-academic-appt') if there is a physician_academic_appointment row with a value in either/both of the fields.
			// Add one instance of a class (' has-empty-academic-title') if there is an empty academic title field in any of the physician_academic_appointment rows.
			// Add one instance of a class (' has-empty-academic-dept') if there is an empty academic department field in any of the physician_academic_appointment rows.
			$provider_field_classes .= ( $college_affiliation && !empty($college_affiliation) ) ? ' has-college-affiliation' : '';
			$provider_field_classes .= ( $position && !empty($position) ) ? ' has-position' : '';
			$provider_field_classes .= ( $bio_academic && !empty($bio_academic) ) ? ' has-academic-bio' : '';
			$provider_field_classes .= ( $bio_academic_short && !empty($bio_academic_short) ) ? ' has-short-academic-bio' : '';
			$provider_field_classes .= ( $office_location && !empty($office_location) ) ? ' has-office-location' : '';
			$provider_field_classes .= ( $office_building && !empty($office_building) ) ? ' has-office-building' : '';
			// Add one instance of a class (' has-contact-info') if there is a physician_contact_information row with a value in both of the fields.
			// Add one instance of a class (' has-empty-contact-info') if there is an empty information field in any of the physician_contact_information rows.
			// Add one instance of a class (' has-education') if there is a physician_education row with a value in either education_type or school.
			// Add one instance of a class (' has-empty-education-type') if there is an empty education_type field in any of the physician_education rows.
			$provider_field_classes .= ( $education && !empty($education) ) ? ' has-education' : '';
			// Add one instance of a class (' has-empty-education-school') if there is an empty school field in any of the physician_education rows.
			$provider_field_classes .= ( $boards && !empty($boards) ) ? ' has-boards' : '';
			$provider_field_classes .= ( $provider_associations && !empty($provider_associations) ) ? ' has-associations' : '';
			$provider_field_classes .= ( $bio_research && !empty($bio_research) ) ? ' has-research-bio' : '';
			$provider_field_classes .= ( $research_interests && !empty($research_interests) ) ? ' has-research-interests' : '';
			$provider_field_classes .= ( $research_profile && !empty($research_profile) ) ? ' has-research-profile' : '';
			$provider_field_classes .= ( $pubmed_author_id && !empty($pubmed_author_id) ) ? ' has-pubmed-id' : '';
			// Add one instance of a class (' has-selected-pubs') if there is a physician_select_publications row with a value in either/both of the pubmed_id_pmid and pubmed_information fields.
			// Add one instance of a class (' has-empty-selected-pub-id') if there is an empty pubmed_id_pmid field in any of the physician_select_publications rows.
			// Add one instance of a class (' has-empty-selected-pub-info') if there is an empty pubmed_information field in any of the physician_select_publications rows.
			// Add one instance of a class (' has-awards') if there is a physician_awards row with a value in either/both of the year and title fields.
			// Add one instance of a class (' has-empty-selected-pub-id') if there is an empty year field in any of the physician_awards rows.
			// Add one instance of a class (' has-empty-selected-pub-info') if there is an empty title field in any of the physician_awards rows.
			$provider_field_classes .= ( $additional_info && !empty($additional_info) ) ? ' has-additional-info' : '';
			$provider_field_classes .= ( $resident && !empty($resident) ) ? ' is-resident' : '';
			$provider_field_classes .= ( $podcast_section_show ) ? ' has-podcast' : '';

		// Remove standard post content

			// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

		// Construct page content

			while ( have_posts() ) {

				the_post();

				?>
				<div class="content-sidebar-wrap">
					<main class="doctor-item<?php echo $provider_field_classes; ?>" id="genesis-content"<?php echo $featured_image ? ' data-image-age="' . $image_age . '"' : ''; ?><?php echo ($service_line ? ' data-service-line="' . get_term( $service_line, 'service_line' )->name . '"' : ''); ?>>
						<?php

						// Construct main info section

							?>
							<section class="container-fluid p-0 p-xs-8 p-sm-10 doctor-info bg-white">
								<div class="row mx-0 mx-xs-n4 mx-sm-n8">
									<div class="col-12 col-xs p-4 py-xs-0 px-xs-4 px-sm-8 order-2 text">
										<h1 class="page-title">
											<span class="name"><?php echo $full_name; ?></span>
											<?php 

											if (
												$provider_occupation_title
												&&
												!empty($provider_occupation_title)
											) {

												?>
												<span class="subtitle"><?php echo $provider_occupation_title ?: ''; ?></span>
												<?php

											}

											?>
										</h1>
										<?php

										// Primary location

											if (
												$location_primary_query
												&&
												$location_primary_query->have_posts()
											) {

												// start of the loop. the_post() sets the global $post variable
												while ( $location_primary_query->have_posts() ) {

													$location_primary_query->the_post();

													include( UAMS_FAD_PATH . '/templates/parts/html/cards/location_primary-location.php' );

												} // endwhile
												// end of the loop

											} // endif

											//reset global post variable. After this point, we are back to the Main Query object
											wp_reset_postdata();

										?> 
										<h2 class="h3">Overview</h2>
										<dl data-sectiontitle="Overview">
											<?php

											// Display area(s) of expertise

												if ( $expertise_section_show && !$hide_medical_ontology ) {

													?>
													<dt><?php echo ( count($expertises) > 1 ? $expertise_plural_name : $expertise_single_name ); ?></dt>
													<?php

													foreach ( $expertises as $expertise ) {

														if ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 ) {

															echo '<dd><a href="' . get_permalink($expertise) . '" target="_self" data-sectiontitle="Overview" data-categorytitle="View ' . $expertise_single_name_attr . '">' . get_the_title($expertise) . '</a></dd>';

														} // endif ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 )

													} // endforeach( $expertises as $expertise )

												} // endif ( $expertise_section_show && !$hide_medical_ontology )

											// Display if they accept new patients

												if ( $eligible_appt ) {

													?>
													<dt>Accepting New Patients</dt>
													<?php 

													if ( $accept_new ) {

														// Display if they require referrals for new patients

														if ( $refer_req ) {

															?>
															<dd>Yes (Referral Required)</dd>
															<?php

														} else {

															?>
															<dd>Yes</dd>
															<?php 

														}

													} else {

														?>
														<dd>No</dd>
														<?php

													} // endif

												} // endif

											// Display if they will provide second opinions

												if ( $second_opinion ) {

													?>
													<dt>Provides Second Opinion</dt>
													<dd>Yes</dd>
													<?php

												} // endif

											// Display all patient types

												if ( $patients ) {

													?>
													<dt>Patient Type<?php echo( count($patients) > 1 ? 's' : '' );?></dt>
													<?php

													foreach ( $patients as $patient ) {

														$patient_name = get_term( $patient, 'patient_type');
														echo '<dd>' . $patient_name->name . '</dd>';

													} // endforeach

												} // endif ( $patients )

											// Display all languages

												if ( $languages && $language_list == 'English') {

													?>
													<dt class="sr-only">Language</dt>
													<?php

													echo '<dd class="sr-only">' . $language_list . '</dd>';

												} else {

													?>
													<dt>Language<?php echo( $language_count > 1 ? 's' : '' );?></dt>
													<?php

													echo '<dd>' . $language_list . '</dd>';

												} //endif

											?>
										</dl>
										<div class="rating" aria-label="Patient Rating">
											<?php

											if ( $rating_valid ) {

												$avg_rating = $rating_data->profile->averageRatingStr;
												$avg_rating_dec = $rating_data->profile->averageRating;
												$review_count = $rating_data->profile->reviewcount;
												$comment_count = $rating_data->profile->bodycount;
												echo '<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: '. $avg_rating_dec/5 * 100 .'%;"></div></div>';
												echo '<div class="ratings-score">'. $avg_rating .'<span class="sr-only"> out of 5</span></div>';
												echo '<div class="w-100"></div>';
												echo '<a href="#ratings" aria-label="Jump to Patient Ratings and Reviews" data-sectiontitle="Overview">';
												echo '<div class="ratings-count-lg" aria-hidden="true">'. $review_count .' Patient Satisfaction Ratings</div>';
												echo '<div class="ratings-comments-lg" aria-hidden="true">'. $comment_count .' comments</div>';
												echo '</a>';

											} else {

												$avg_rating = '';
												$avg_rating_dec = '';
												$review_count = '';
												$comment_count = '';

												?>
												<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>" data-sectiontitle="Overview"><span aria-hidden="true">Why not?</span></a></em></p> 
												<?php

											} // endif ( $rating_valid ) else

											$provider_schema_fields[$page_id]['provider_aggregateRating_ratingCount'] = $review_count; // Pass value to schema function
											$provider_schema_fields[$page_id]['provider_aggregateRating_ratingValue'] = $avg_rating; // Pass value to schema function
											$provider_schema_fields[$page_id]['provider_aggregateRating_reviewCount'] = $comment_count; // Pass value to schema function

											?>
										</div>
										<?php

										if ( !$rating_valid ) {

											?>
											<div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why_not_modal" aria-modal="true">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="WhyNotTitle">Why Are There No Ratings?</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<p>There is no publicly available rating for this <?php echo strtolower($provider_single_name); ?> for one of three reasons:</p>
															<ul>
																<li>The <?php echo strtolower($provider_single_name); ?> does not see patients</li>
																<li>The <?php echo strtolower($provider_single_name); ?> sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</li>
																<li>The <?php echo strtolower($provider_single_name); ?> is a resident physician.</li>
															</ul>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
											<?php

										} // endif ( !$rating_valid )

										?>
									</div>
									<?php

									if ( $featured_image ) {

										?>
										<div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image">
											<picture>
												<?php

												if ( function_exists( 'fly_add_image_size' ) ) {

													?>
													<source srcset="<?php echo image_sizer($featured_image, 389, 519, 'center', 'center'); ?>"
														media="(min-width: 1200px)">
													<source srcset="<?php echo image_sizer($featured_image, 306, 408, 'center', 'center'); ?>"
														media="(min-width: 992px)">
													<source srcset="<?php echo image_sizer($featured_image, 182, 243, 'center', 'center'); ?>"
														media="(min-width: 768px)">
													<source srcset="<?php echo image_sizer($featured_image, 86, 115, 'center', 'center'); ?>"
														media="(min-width: 576px)">
													<source srcset="<?php echo image_sizer($featured_image, 380, 507, 'center', 'center'); ?>"
														media="(min-width: 1px)">
													<img src="<?php echo image_sizer($featured_image, 778, 1038, 'center', 'center'); ?>" alt="<?php echo $full_name_attr; ?>" />
													<?php

												} else {

													the_post_thumbnail( 'large', array( 'itemprop' => 'image' ) );

												} // endif ( function_exists( 'fly_add_image_size' ) ) else

												?>
											</picture>
										</div>
										<?php

									} // endif ( $featured_image )

									?>
								</div>
							</section>
							<?php

						// Construct Jump Links Section

							include( UAMS_FAD_PATH . '/templates/parts/html/section/jump-links.php' );

						// Construct first appointment information section

							if ( $appointment_section_show ) {

								$appointment_block_instance = 1;
								include( UAMS_FAD_PATH . '/templates/parts/html/section/appointment_provider.php' );

							}

						// Construct clinical bio section

							// Query for whether to add a two-column split in the section

								$provider_clinical_split = false;

								if (
									( $clinical_bio_section_show ) // column A stuff
									&&
									( $provider_clinical_focus ) // column B stuff
									// &&
									// ( $provider_clinical_admin_title || $provider_clinical_focus ) // Alternate column B stuff if we decide to display clinical admin title
								) {


									$provider_clinical_split = true; // If there is stuff for column A and column B, split the section into two columns
								}

							if ( $clinical_bio_section_show ) {

								?>
								<section class="uams-module clinical-info bg-auto" id="clinical-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title">About <?php echo $short_name; ?></span></h2>
												<?php

												if ( $provider_clinical_split ) {

													// If there is a bio or video AND at least one of the other clinical things, visually split the layout ?>
													<div class="row content-split-lg">
														<div class="col-xs-12 col-lg-7">
															<div class="content-width">
																<?php

												} else {

													?>
													<div class="module-body">
													<?php

												} // endif

												if ( $provider_clinical_bio ) {

													?>
													<h3 class="sr-only">Clinical Biography</h3>
													<?php

													echo $provider_clinical_bio;

												} // endif

												if ( $video ) {

													if ( function_exists('lyte_preparse') ) {

														echo '<div class="alignwide">';
														echo lyte_parse( str_replace(['https:', 'http:'], 'httpv:', $video ) );
														echo '</div>';

													} else {

														echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
														echo wp_oembed_get( $video );
														echo '</div>';

													} // endif

												} // endif

												if ( $provider_clinical_split ) {

																?>
															</div>
														</div>
														<div class="col-xs-12 col-lg-5">
															<div class="content-width">
																<?php

												} // endif

												// Section for displaying clinical admin title

													if (true == false) { // Remove this if statement if we decide to display clinical admin title later.

														if ( have_rows('physician_clinical_admin_title') ) {

															?>
															<h3 class="h4">Administrative Roles</h3>
															<dl>
																<?php

																while ( have_rows('physician_clinical_admin_title') ) {

																	the_row();
																	$department = get_term( get_sub_field('physician_clinical_admin_area'), 'service_line' );
																	$clinical_admin_title_tax = get_term( get_sub_field('clinical_admin_title_tax'), 'clinical_admin_title' );

																	?>
																	<dt><?php echo $department->name; ?></dt>
																	<dd><?php echo $clinical_admin_title_tax->name; ?></dd>
																	<?php

																} // endwhile

																?>
															</dl>
														<?php

														} // endif ( have_rows('physician_clinical_admin_title') )

													} // endif

												// Clinical focus

													if ( $provider_clinical_focus ) {

														?>
														<h3 class="h4">Clinical Focus</h3>
														<?php

														echo $provider_clinical_focus;

													} // endif

												if ( $provider_clinical_split ) {

																?>
															</div>
														</div>
													</div>
													<?php

												} else {

														?>
													</div>
													<?php

												} // endif

												?>
											</div>
										</div>
									</div>
								</section>
								<?php 

							} // endif ( $clinical_bio_section_show )

						// Construct UAMS Health Talk podcast section

							$podcast_filter = 'doctor';
							$podcast_subject = $short_name;
							include( UAMS_FAD_PATH . '/templates/parts/html/section/podcast.php' );

						// Construct Clinical Resources Section

							$clinical_resource_section_more_link_key = '_resource_provider';
							$clinical_resource_section_more_link_value = $sort_name_param_value;
							$clinical_resource_section_title = $clinical_resource_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
							$clinical_resource_section_intro = $clinical_resource_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
							$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_provider;
							$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_provider;
							$clinical_resource_section_more_link_descr = $clinical_resource_fpage_more_link_descr_provider;
							include( UAMS_FAD_PATH . '/templates/parts/html/section/list/clinical-resource.php' );

						// Construct Academic Bio Section

							$provider_academic_split = false;

							if (
								$academic_bio
								&&
								(
									$academic_appointment
									||
									$academic_admin_title
									||
									$education
									||
									$boards
								)
							) {

								$provider_academic_split = true;

							}

							if ( $academic_section_show ) {

								?>
								<section class="uams-module academic-info bg-auto" id="academic-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title"><?php echo $short_name_possessive; ?> Academic Background</span></h2>
												<?php if ( $provider_academic_split ) {
													// If there is a bio AND at least one of the other academic things, visually split the layout ?>
													<div class="row content-split-lg">
													<div class="col-xs-12 col-lg-7">
													<div class="content-width">
												<?php
												} else { ?>
													<div class="module-body">
												<?php
												} // endif
												if ( $academic_bio ) { ?>
													<h3 class="sr-only">Academic Biography</h3>
													<?php echo $academic_bio; ?>
												<?php
												} // endif
												if ( $provider_academic_split ) { ?>
													</div>
													</div>
													<div class="col-xs-12 col-lg-5">
													<div class="content-width">
												<?php
												} // endif ( $provider_academic_split )]
												if( have_rows('physician_academic_admin_title') ) { ?>
													<h3 class="h4">Administrative Roles</h3>
													<dl>
													<?php while ( have_rows('physician_academic_admin_title') ) {
														the_row();
														$department = get_term( get_sub_field('department'), 'academic_department' );
														$academic_admin_title_tax = get_term( get_sub_field('academic_admin_title_tax'), 'academic_admin_title' );
													?>
														<dt><?php echo $department->name; ?></dt>
														<dd><?php echo $academic_admin_title_tax->name; ?></dd>
													<?php
													} // endwhile ( have_rows('physician_academic_admin_title') ) ?>
													</dl>
												<?php
												} // endif( have_rows('physician_academic_admin_title') )
												// $academic_appointments = get_field('physician_academic_appointment');
												if ( have_rows('physician_academic_appointment') ) { ?>
													<h3 class="h4">Faculty Appointments</h3>
													<dl>
													<?php while ( have_rows('physician_academic_appointment') ) {
														the_row();
														$department = get_term( get_sub_field('department'), 'academic_department' );
														$academic_title_tax = get_term( get_sub_field('academic_title_tax'), 'academic_title' );
														if ($academic_title_tax->name) {
															$academic_title = $academic_title_tax->name;
														} else {
															$academic_title = get_sub_field('academic_title');
														} ?>
														<dt><?php echo $department->name; ?></dt>
														<dd><?php echo $academic_title; ?></dd>
													<?php
													} // endwhile ( have_rows('physician_academic_appointment') ) ?>
													</dl>
												<?php
												} // endif ( have_rows('physician_academic_appointment') )
												if ( $resident ) { ?>
													<h3 class="h4">Residency Program</h3>
													<dl>
														<dt><?php echo $resident_academic_department_name; ?></dt>
														<dd><?php echo $resident_academic_name; ?></dd>
													</dl>
												<?php
												} // endif ( $resident )
												if ( have_rows('physician_education') ) { ?>
													<h3 class="h4">Education and Training</h3>
													<dl>
													<?php while ( have_rows('physician_education') ) {
														the_row();
														$school_name = get_term( get_sub_field('school'), 'school');
														$education_type = get_term( get_sub_field('education_type'), 'educationtype'); ?>
														<dt><?php echo $education_type->name; ?></dt>
														<dd><?php echo $school_name->name; ?><?php echo (get_sub_field('description') ? '<br /><span class="subtitle">' . get_sub_field('description') .'</span>' : ''); ?></dd>
													<?php
													} // endwhile ( have_rows('physician_education') ) ?>
													</dl>
												<?php
												} // endif ( have_rows('physician_education') )
												if ( !empty( $boards ) ) { ?>
													<h3 class="h4">Professional Certifications</h3>
													<ul>
														<?php foreach ( $boards as $board ) {
															$board_name = get_term( $board, 'board'); ?>
															<li><?php echo $board_name->name; ?></li>
														<?php
														} // endforeach ( $boards as $board ) ?>
													</ul>
												<?php
												} // end( !empty( $boards ) )

												if ( !empty( $provider_associations_names ) ) {

													?>
													<h3 class="h4">Associations</h3>
													<ul>
														<?php

														foreach ( $provider_associations_names as $item ) {

															?>
															<li><?php echo $item; ?></li>
															<?php

														} // endforeach

														?>
													</ul>
													<?php

												} // endif ( !empty( $provider_associations_names ) )

												if ( $provider_academic_split ) { ?>
													</div>
													</div>
													</div>
												<?php
												} else { ?>
													</div>
												<?php
												} // endif ( $provider_academic_split ) ?>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif ( $academic_section_show )

						// Construct Research Bio Section

							if ( $research_section_show ) {

								?>
								<section class="uams-module research-info bg-auto" id="research-info">
									<div class="container-fluid">
										<div class="row">
											<div class="col-xs-12">
												<h2 class="module-title"><span class="title"><?php echo $short_name_possessive; ?> Research</span></h2>
												<div class="module-body">
													<?php
													if ($research_bio) {
														echo $research_bio;
													}

													if ( $research_interests ) { ?>
														<h3>Research Interests</h3>
														<?php echo $research_interests;
													}

													if ( !empty ( $publications ) ) { ?>
														<h3>Selected Publications</h3>
														<ul>
															<?php
															foreach( $publications as $publication ) { ?>
																<li><?php echo $publication['pubmed_information']; ?></li>
															<?php
															} // endforeach ?>
														</ul>
													<?php
													} // endif

													if( $pubmed_author_id ) {
														$pubmedid = trim($pubmed_author_id);
														$pubmedcount = ($pubmed_author_number ? $pubmed_author_number : '3');
														?>
														<h3>Latest Publications</h3>
														<p>Publications listed below are automatically derived from MEDLINE/PubMed and other sources, which might result in incorrect or missing publications.</p>
														<?php echo do_shortcode( '[pubmed terms="' . urlencode($pubmedid) .'%5BAuthor%5D" count="' . $pubmedcount .'"]' );
													} // endif

													if( $research_profiles_link ) { ?>
														<h3>UAMS Research Profile</h3>
														<p>Each UAMS faculty member has a research profile page that includes biographical and contact information, a list of their most recent grant activity and a list of their PubMed publications.</p>
														<p><a class="btn btn-outline-primary" href="<?php echo $research_profiles_link; ?>" data-categorytitle="View Research Profile">View <?php echo $short_name_possessive; ?> research profile</a></p>
													<?php
													} // endif ?>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif ( $research_section_show )

						// Construct Combined Conditions and Treatments Section

							$condition_treatment_section_title = $condition_treatment_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
							$condition_treatment_section_intro = $condition_treatment_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
							$condition_section_title = $condition_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
							$condition_section_intro = $condition_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
							$treatment_section_title = $treatment_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
							$treatment_section_intro = $treatment_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
							include( UAMS_FAD_PATH . '/templates/parts/html/section/list/condition-treatment.php' );

						// Construct Areas of Expertise Section

							$expertise_section_title = $expertise_fpage_title_provider;
							$expertise_section_intro = $expertise_fpage_intro_provider;
							include( UAMS_FAD_PATH . '/templates/parts/html/section/list/expertise.php' );

						// Construct Location Section

							$location_section_title = $location_fpage_title_provider; // Text to use for the section title
							$location_section_intro = $location_fpage_intro_provider; // Text to use for the section intro text
							$location_section_schema_query = true; // Query for whether to add locations to schema
							include( UAMS_FAD_PATH . '/templates/parts/html/section/list/location.php' );

						// Construct Ratings and Reviews Section

							if ( $ratings_section_show ) {

								?>
								<section class="uams-module ratings-and-reviews bg-auto" id="ratings">
									<div class="container-fluid">
										<div class="row">
											<div class="col-12">
												<h2 class="module-title"><span class="title">Patient Ratings &amp; Reviews</span></h2>
												<div class="card overall-ratings text-center">
													<div class="card-body">
														<h3 class="sr-only">Average Ratings</h3>
														<dl>
															<?php
															$questionRatings = $rating_data->profile->questionRatings;
															foreach( $questionRatings as $questionRating ): 
																if ($questionRating->questionCount > 0){ ?>
															<dt><?php echo $questionRating->question; ?></dt>
															<dd>
																<div class="rating" aria-label="Patient Rating">
																	<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: <?php echo floatval($questionRating->averageRatingStr)/5 * 100; ?>%;"></div></div>
																	<div class="ratings-score-lg"><?php echo $questionRating->averageRatingStr; ?><span class="sr-only"> out of 5</span></div>
																</div>
															</dd>
															<?php }
															endforeach; ?>
														</dl>
													</div>
													<div class="card-footer bg-transparent text-muted small">
														<p class="h5">Overall: <?php echo $rating_data->profile->averageRatingStr; ?> out of 5</p>
														<p>(<?php echo $rating_data->profile->reviewBodyCountStr; ?>)</p>
													</div>
												</div>
												<?php 
												$reviews = $rating_data->reviews;
												// if ( $reviews ) : ?>
												<?php //print_r($rating_data); ?>
												<h3 class="sr-only">Individual Reviews</h3>
												<div class="card-list-container">
													<div class="card-list">
														<?php foreach( $reviews as $review ): ?>
														<div class="card">
															<div class="card-header bg-transparent">
																<div class="rating rating-center" aria-label="Average Rating">
																	<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: <?php echo floatval($review->rating)/5 * 100; ?>%;"></div></div>
																	<div class="ratings-score-lg" itemprop="ratingValue"><?php echo $review->rating; ?><span class="sr-only"> out of 5</span></div>
																</div>
															</div>
															<div class="card-body">
																<h4 class="sr-only">Comment</h4>
																<p class="card-text"><?php echo $review->bodyForDisplay; ?></p>
															</div>
															<div class="card-footer bg-transparent text-muted small">
																<h4 class="sr-only">Date</h4>
																<?php echo $review->formattedReviewDate; ?>
															</div>
														</div>
														<?php endforeach; ?>
													</div>
												</div>
												<div class="view-more text-center mt-8 mt-sm-10">
													<button class="btn btn-secondary" data-toggle="modal" data-target="#MoreReviews" aria-label="View more individual reviews">View More</button>
												</div>
												<!-- Modal -->
												<div class="modal fade" id="MoreReviews" tabindex="-1" role="dialog" aria-labelledby="more-reviews-title" aria-modal="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="more-reviews-title">More Reviews</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<div class="ds-comments" data-ds-pagesize="10"></div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
														</div>
													</div>
												</div>
												<script>
													/* Custom HTML for the paging controls for the comments list */
													window.DS_OPT = {
														buildCommentsLoadMoreHTML: function(data, ctx){
															// a variable to hold the HTML markup
															var x;
															// make sure we have data and it is valid
															if(data && data.valid){
																// grab the profile data
																var review = data.reviewMeta;
																if(review){
																	// setup the variables that the template will need
																	var templateData = {
																		moreUrl:	review.moreUrl
																	};
																	// build the HTML markup using {{var-name}} for the template variables
																	var template = [
																		'<div class="ds-comments-more ds-comments-more-placeholder">',
																			'<a href="#" class="ds-comments-more-link" data-more-comments-url="{{moreUrl}}">View More</a>',
																			'<span class="ds-comments-more-loading" style="display:none;">Loading...</span>',
																		'</div>'
																	].join('');
																	// apply the variables to the template
																	x = ctx.tmpl(template, templateData);
																}
															}
															return x;
														}
													};
												</script>
												<script src="https://transparency.nrchealth.com/widget/v3/uams/npi/<?php echo $npi; ?>/lotw.js" async></script>
												<?php // endif; ?>
											</div>
										</div>
									</div>
								</section>
								<?php

							} // endif ( $ratings_section_show )

						// Construct second appointment information section

							if (
								$appointment_section_show && 
								( 
									$clinical_bio_section_show
									|| $academic_section_show
									|| $podcast_section_show
									|| $research_section_show
									|| $condition_section_show
									|| $treatment_section_show
									|| $expertise_section_show
									|| $location_section_show
									|| $ratings_section_show
								)
							) {
								$appointment_block_instance = 2;
								include( UAMS_FAD_PATH . '/templates/parts/html/section/appointment_provider.php' );
							}

						?>
					</main>
				</div>
				<?php

				// Schema Data

					// Set the values

						// Required for Google Structured Data
						// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

							// Type: Physician
							$schema_type = 'Physician'; // string

							// Property: address
							$schema_address = isset($schema_address) ? $schema_address : ''; // array

						// Recommended by Google Structured Data
						// as documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)

							// Property: aggregateRating
							$schema_aggregate_rating = $rating_valid; // bool

							if ( $schema_aggregate_rating ) {
								$schema_aggregate_rating_value = $avg_rating; // string
								$schema_aggregate_rating_count = $review_count; // int
								$schema_aggregate_rating_review_count = $comment_count; // int
							}

							// Property: geo
							$schema_geo_coordinates = isset($schema_geo_coordinates) ? $schema_geo_coordinates : '';

						// Additional Selected Properties

							// Property: availableService
							$schema_available_service = ( isset($schema_available_service) && is_array($schema_available_service) && !empty($schema_available_service) ) ? $schema_available_service : array(); // array

							// Property: description
							$schema_description = isset($schema_description) ? $schema_description : ''; // string

							// Property: image
							$schema_image = isset($schema_image) ? $schema_image : ''; // string

							// Property: medicalSpecialty
							$schema_medical_specialty = ( isset($schema_medical_specialty) && is_array($schema_medical_specialty) && !empty($schema_medical_specialty) ) ? $schema_medical_specialty : array(); // array

							// Property: hospitalAffiliation

								$schema_hospital_affiliation = ( isset($schema_hospital_affiliation) && is_array($schema_hospital_affiliation) && !empty($schema_hospital_affiliation) ) ? $schema_hospital_affiliation : array(); // array

								$schema_hospital_affiliation = uamswp_fad_schema_hospital_affiliation(
									$affiliation, // array // Required // Hospital affiliation ID values
									$page_url, // string // Required // Page URL
									1, // int // Optional // Nesting level within the main schema
									$schema_hospital_affiliation // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
								);

					// // Construct the schema script tag
					// 
					// 	include( UAMS_FAD_PATH . '/templates/parts/html/script/schema.php' );

					// Construct the schema script tag (v2)

						include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/provider.php' );

			} // endwhile // end of the loop

	// FOOTER

		// Remove the post-related content and markup from the entry footer

			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
			// remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
			// remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
			// remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

		get_footer();

?>