<?php 
/*
 * Template Name: Single Provider
 * 
 * Get ACF fields to use for meta data
 * Add description from provider short description or full description
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	$labels_provider = uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider['placeholder_provider_short_name_possessive']; // string

	// Get system settings for location labels
	$labels_location = uamswp_fad_labels_location();
		$location_single_name = $labels_location['location_single_name']; // string
		$location_single_name_attr = $labels_location['location_single_name_attr']; // string
		$location_plural_name = $labels_location['location_plural_name']; // string
		$location_plural_name_attr = $labels_location['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location['placeholder_location_page_title_phrase']; // string

	// // Get system settings for location descendant item labels
	// $labels_location_descendant = uamswp_fad_labels_location_descendant();
	// 	$location_descendant_single_name = $labels_location_descendant['location_descendant_single_name']; // string
	// 	$location_descendant_single_name_attr = $labels_location_descendant['location_descendant_single_name_attr']; // string
	// 	$location_descendant_plural_name = $labels_location_descendant['location_descendant_plural_name']; // string
	// 	$location_descendant_plural_name_attr = $labels_location_descendant['location_descendant_plural_name_attr']; // string
	// 	$placeholder_location_descendant_single_name = $labels_location_descendant['placeholder_location_descendant_single_name']; // string
	// 	$placeholder_location_descendant_plural_name = $labels_location_descendant['placeholder_location_descendant_plural_name']; // string

	// Get system settings for area of expertise labels
	$labels_expertise = uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise['placeholder_expertise_page_title']; // string

	// // Get system settings for area of expertise descendant item labels
	// $labels_expertise_descendant = uamswp_fad_labels_expertise_descendant();
	// 	$expertise_descendant_single_name = $labels_expertise_descendant['expertise_descendant_single_name']; // string
	// 	$expertise_descendant_single_name_attr = $labels_expertise_descendant['expertise_descendant_single_name_attr']; // string
	// 	$expertise_descendant_plural_name = $labels_expertise_descendant['expertise_descendant_plural_name']; // string
	// 	$expertise_descendant_plural_name_attr = $labels_expertise_descendant['expertise_descendant_plural_name_attr']; // string
	// 	$placeholder_expertise_descendant_single_name = $labels_expertise_descendant['placeholder_expertise_descendant_single_name']; // string
	// 	$placeholder_expertise_descendant_plural_name = $labels_expertise_descendant['placeholder_expertise_descendant_plural_name']; // string

	// Get system settings for clinical resource labels
	$labels_clinical_resource = uamswp_fad_labels_clinical_resource();
		$clinical_resource_single_name = $labels_clinical_resource['clinical_resource_single_name']; // string
		$clinical_resource_single_name_attr = $labels_clinical_resource['clinical_resource_single_name_attr']; // string
		$clinical_resource_plural_name = $labels_clinical_resource['clinical_resource_plural_name']; // string
		$clinical_resource_plural_name_attr = $labels_clinical_resource['clinical_resource_plural_name_attr']; // string
		$placeholder_clinical_resource_single_name = $labels_clinical_resource['placeholder_clinical_resource_single_name']; // string
		$placeholder_clinical_resource_plural_name = $labels_clinical_resource['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for combined condition and treatment labels
	$labels_condition_treatment = uamswp_fad_labels_condition_treatment();
		$condition_treatment_single_name = $labels_condition_treatment['condition_treatment_single_name']; // string
		$condition_treatment_single_name_attr = $labels_condition_treatment['condition_treatment_single_name_attr']; // string
		$condition_treatment_plural_name = $labels_condition_treatment['condition_treatment_plural_name']; // string
		$condition_treatment_plural_name_attr = $labels_condition_treatment['condition_treatment_plural_name_attr']; // string
		$placeholder_condition_treatment_single_name = $labels_condition_treatment['placeholder_condition_treatment_single_name']; // string
		$placeholder_condition_treatment_plural_name = $labels_condition_treatment['placeholder_condition_treatment_plural_name']; // string

	// Get system settings for condition labels
	$labels_condition = uamswp_fad_labels_condition();
		$condition_single_name = $labels_condition['condition_single_name']; // string
		$condition_single_name_attr = $labels_condition['condition_single_name_attr']; // string
		$condition_plural_name = $labels_condition['condition_plural_name']; // string
		$condition_plural_name_attr = $labels_condition['condition_plural_name_attr']; // string
		$placeholder_condition_single_name = $labels_condition['placeholder_condition_single_name']; // string
		$placeholder_condition_plural_name = $labels_condition['placeholder_condition_plural_name']; // string

	// Get system settings for treatment labels
	$labels_treatment = uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment['treatment_single_name']; // string
		$treatment_single_name_attr = $labels_treatment['treatment_single_name_attr']; // string
		$treatment_plural_name = $labels_treatment['treatment_plural_name']; // string
		$treatment_plural_name_attr = $labels_treatment['treatment_plural_name_attr']; // string
		$placeholder_treatment_single_name = $labels_treatment['placeholder_treatment_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment['placeholder_treatment_plural_name']; // string

// // Get system settings for provider archive page text
// $archive_text_provider = uamswp_fad_archive_text_provider();
// 	$provider_archive_headline = $archive_text_provider['provider_archive_headline']; // string
// 	$provider_archive_headline_attr = $archive_text_provider['provider_archive_headline_attr']; // string
// 	$placeholder_provider_archive_headline = $archive_text_provider['placeholder_provider_archive_headline']; // string

// Get the page ID
$page_id = get_the_ID();

// Construct name values for the provider

	// Construct a list of the provider's degrees (e.g., "M.D., Ph.D.")
	$degrees = get_field('physician_degree',$post->ID);
	$degree_list = '';
	$i = 1;
	if ( $degrees ) {
		foreach( $degrees as $degree ):
			$degree_name = get_term( $degree, 'degree');
			$degree_list .= $degree_name->name;
			if( count($degrees) > $i ) {
				$degree_list .= ", ";
			}
			$i++;
		endforeach;
	}

	// Construct a list of the provider's languages (e.g., "English, Spanish")
	$languages = get_field('physician_languages',$post->ID);
	$language_count = 0;
	if ($languages) {
		$language_count = count($languages);
	}
	$language_list = '';
	$i = 1;
	if ( $languages ) {
		foreach( $languages as $language ):
			$language_name = get_term_by( 'id', $language, 'language');
			if( is_object($language_name) ) {
				$language_list .= $language_name->name;
				if( $language_count > $i ) {
					$language_list .= ", ";
				}
			}
			$i++;
		endforeach;
	}

	// Get the provider's prefix (e.g., "Dr.")
	$prefix = get_field('physician_prefix',$post->ID);

	// Get the elements of the provider's name
	$first_name = get_field('physician_first_name',$post->ID); // Get the provider's first name
	$first_name_attr = uamswp_attr_conversion($first_name);
	$middle_name = get_field('physician_middle_name',$post->ID); // Get the provider's middle name
	$last_name = get_field('physician_last_name',$post->ID); // Get the provider's last name
	$last_name_attr = uamswp_attr_conversion($last_name);
	$pedigree = get_field('physician_pedigree',$post->ID); // Get the provider's generational suffix (e.g., "Jr.")

	// Construct the variants of the provider's name

	// Full name (e.g., "Leonard H. McCoy, M.D.")
	$full_name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '') . ( $degree_list ? ', ' . $degree_list : '' );
	$full_name_attr = uamswp_attr_conversion($full_name);
	$page_title = $full_name;
	$page_title_attr = $full_name_attr;

	// Medium name (e.g., "Dr. Leonard H. McCoy")
	$medium_name = ($prefix ? $prefix .' ' : '') . $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;
	$medium_name_attr = uamswp_attr_conversion($medium_name);

	// Short name (e.g., "Dr. McCoy")
	$short_name = $prefix ? $prefix .'&nbsp;' .$last_name : $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '');
	$short_name_attr = uamswp_attr_conversion($short_name);

	// Short name possessive (e.g., "Dr. McCoy's")
	if ( substr($short_name, -1) == 's' ) { // If the provider's name ends in "s"...
		$short_name_possessive = $short_name . '\''; // Use an apostrophe with no "s" when indicating the possessive form
	} else {
		$short_name_possessive = $short_name . '\'s'; // Use an apostrophe with an "s" when indicating the possessive form
	}

	// Sort name (e.g., "McCoy, Leonard H.")
	$sort_name = $last_name . ', ' . $first_name . ' ' . $middle_name;

	// Sort name parameter (e.g., "mccoy-leonard-h")
	$sort_name_param_value = sanitize_title_with_dashes($sort_name);

// Get the provider's gender
$gender = get_field('physician_gender',$post->ID);
$gender_attr = uamswp_attr_conversion($gender);

// Get system settings for fake subpage (or section) text elements in an Provider subsection (or profile)
$fpage_text_provider = uamswp_fad_fpage_text_provider();
	$location_fpage_title_provider = $fpage_text_provider['location_fpage_title_provider']; // string
	$location_fpage_intro_provider = $fpage_text_provider['location_fpage_intro_provider']; // string
	$location_fpage_ref_main_title_provider = $fpage_text_provider['location_fpage_ref_main_title_provider']; // string
	$location_fpage_ref_main_intro_provider = $fpage_text_provider['location_fpage_ref_main_intro_provider']; // string
	$location_fpage_ref_main_link_provider = $fpage_text_provider['location_fpage_ref_main_link_provider']; // string
	$expertise_fpage_title_provider = $fpage_text_provider['expertise_fpage_title_provider']; // string
	$expertise_fpage_intro_provider = $fpage_text_provider['expertise_fpage_intro_provider']; // string
	$expertise_fpage_ref_main_title_provider = $fpage_text_provider['expertise_fpage_ref_main_title_provider']; // string
	$expertise_fpage_ref_main_intro_provider = $fpage_text_provider['expertise_fpage_ref_main_intro_provider']; // string
	$expertise_fpage_ref_main_link_provider = $fpage_text_provider['expertise_fpage_ref_main_link_provider']; // string
	$clinical_resource_fpage_title_provider = $fpage_text_provider['clinical_resource_fpage_title_provider']; // string
	$clinical_resource_fpage_intro_provider = $fpage_text_provider['clinical_resource_fpage_intro_provider']; // string
	$clinical_resource_fpage_ref_main_title_provider = $fpage_text_provider['clinical_resource_fpage_ref_main_title_provider']; // string
	$clinical_resource_fpage_ref_main_intro_provider = $fpage_text_provider['clinical_resource_fpage_ref_main_intro_provider']; // string
	$clinical_resource_fpage_ref_main_link_provider = $fpage_text_provider['clinical_resource_fpage_ref_main_link_provider']; // string
	$clinical_resource_fpage_more_text_provider = $fpage_text_provider['clinical_resource_fpage_more_text_provider']; // string
	$clinical_resource_fpage_more_link_text_provider = $fpage_text_provider['clinical_resource_fpage_more_link_text_provider']; // string
	$clinical_resource_fpage_more_link_descr_provider = $fpage_text_provider['clinical_resource_fpage_more_link_descr_provider']; // string
	$condition_fpage_title_provider = $fpage_text_provider['condition_fpage_title_provider']; // string
	$condition_fpage_intro_provider = $fpage_text_provider['condition_fpage_intro_provider']; // string
	$treatment_fpage_title_provider = $fpage_text_provider['treatment_fpage_title_provider']; // string
	$treatment_fpage_intro_provider = $fpage_text_provider['treatment_fpage_intro_provider']; // string
	$condition_treatment_fpage_title_provider = $fpage_text_provider['condition_treatment_fpage_title_provider']; // string
	$condition_treatment_fpage_intro_provider = $fpage_text_provider['condition_treatment_fpage_intro_provider']; // string

// Get system settings for jump links (a.k.a. anchor links)
$labels_jump_links = uamswp_fad_labels_jump_links();
	$fad_jump_links_title = $labels_jump_links['fad_jump_links_title']; // string

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Get resident values
$resident = get_field('physician_resident',$post->ID);
$resident_title_name = 'Resident Physician';

// Get clinical title values
$phys_title = get_field('physician_title',$post->ID);
$phys_title_name = $resident ? $resident_title_name : get_term( $phys_title, 'clinical_title' )->name;
$phys_title_name_attr = uamswp_attr_conversion($phys_title_name);
$vowels = array('a','e','i','o','u'); // Define a list of variables for use in determining which indefinite article to use (a vs. an)
if (in_array(strtolower($phys_title_name)[0], $vowels)) { // Defines a or an, based on whether clinical title starts with vowel
	$phys_title_indef_article = 'an'; // If the clinical title starts with a vowel, use "an"
} else {
	$phys_title_indef_article = 'a'; // If the clinical title does not start with a vowel, use "a"
}
// Define a list of exceptions to the vowel-based determination of which indefinite article to use.
// Use "a" before consonant sounds: a historic event, a one-year term.
// Use "an" before vowel sounds: an honor, an NBA record.
// Write the key as the characters at the beginning of the exception. It can be a complete or incomplete title.
// Write the value as the indefinite article to use in that case ('a' or 'an').
$phys_title_indef_article_exceptions = array(
	'SNF' => 'an',
	'Urolog' => 'a',
	'Uveitis' => 'a'
);
if ( !empty($phys_title_indef_article_exceptions) ) {
	foreach( $phys_title_indef_article_exceptions as $exception => $indef_article ) {
		$exception_length = strlen($exception); // Get the charactter length of the exception key
		if (substr(strtolower($phys_title_name), 0, $exception_length) == strtolower($exception)) { // If the clinical title begins with the exception key...
			$phys_title_indef_article = $indef_article; // Use the key's value as the indefinite article
		}
	}
}

// Check if the provider sees patients via appointments
$eligible_appt = $resident ? 0 : get_field('physician_eligible_appointments',$post->ID);

// Query for whether related locations content section should be displayed on a page
$locations = get_field('physician_locations',$post->ID); // Get the provider's location values
$location_query_function = uamswp_fad_location_query( $locations );
	$location_query = $location_query_function['location_query']; // WP_Post[]
	$location_section_show = $location_query_function['location_section_show']; // bool
	$location_ids = $location_query_function['location_ids']; // int[]
	$location_count = $location_query_function['location_count']; // int
	$location_valid = $location_query_function['location_valid']; // bool

// Get the name of the provider's primary location
if( $location_section_show ) {
	foreach ( $locations as $location ) {
		if ( get_post_status ( $location ) == 'publish' ) {
			$primary_appointment_title = get_the_title( $location );
			$primary_appointment_title_attr = uamswp_attr_conversion($primary_appointment_title);
			$primary_appointment_url = get_the_permalink( $location );
			$primary_appointment_city = get_field('location_city', $location);
			$primary_appointment_city_attr = uamswp_attr_conversion($primary_appointment_city);
			break;
		}
	}
}

// Query for whether related areas of expertise content section should be displayed on a page
$expertises = get_field('physician_expertise',$post->ID);
$expertise_query_function = uamswp_fad_expertise_query( $expertises );
	$expertise_query = $expertise_query_function['expertise_query']; // WP_Post[]
	$expertise_section_show = $expertise_query_function['expertise_section_show']; // bool
	$expertise_ids = $expertise_query_function['expertise_ids']; // int[]
	$expertise_count = $expertise_query_function['expertise_count']; // int
if ( $expertise_section_show ) {
	foreach ( $expertises as $expertise ) {
		if ( get_post_status ( $expertise ) == 'publish' ) {
			$expertise_primary_name = get_the_title($expertise);
			$expertise_primary_name_attr = uamswp_attr_conversion($expertise_primary_name);
			break;
		}
	}
}

// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
$clinical_resources = get_field('physician_clinical_resources');
$clinical_resource_query_function = uamswp_fad_clinical_resource_query( $clinical_resources );
	$clinical_resource_query = $clinical_resource_query_function['clinical_resource_query']; // WP_Post[]
	$clinical_resource_section_show = $clinical_resource_query_function['clinical_resource_section_show']; // bool
	$clinical_resource_ids = $clinical_resource_query_function['clinical_resource_ids']; // int[]
	$clinical_resource_count = $clinical_resource_query_function['clinical_resource_count']; // int

// Query for whether related conditions content section should be displayed on ontology pages/subsections
$conditions = get_field('physician_conditions');
$conditions_cpt = get_field('physician_conditions_cpt');
$condition_query_function = uamswp_fad_condition_query( $conditions_cpt );
	$condition_cpt_query = $condition_query_function['condition_cpt_query']; // WP_Post[]
	$condition_section_show = $condition_query_function['condition_section_show']; // bool
	$condition_treatment_section_show = $condition_query_function['condition_treatment_section_show']; // bool
	$condition_ids = $condition_query_function['condition_ids']; // int[]
	$condition_count = $condition_query_function['condition_count']; // int
	$condition_schema = $condition_query_function['condition_schema']; // string

// Query for whether related treatments content section should be displayed on ontology pages/subsections
$treatments = get_field('physician_treatments');
$treatments_cpt = get_field('physician_treatments_cpt');
$treatment_query_function = uamswp_fad_treatment_query( $treatments_cpt );
	$treatment_cpt_query = $treatment_query_function['treatment_cpt_query']; // WP_Post[]
	$treatment_section_show = $treatment_query_function['treatment_section_show']; // bool
	$condition_treatment_section_show = $treatment_query_function['condition_treatment_section_show']; // bool
	$treatment_ids = $treatment_query_function['treatment_ids']; // int[]
	$treatment_count = $treatment_query_function['treatment_count']; // int
	$treatment_schema = $treatment_query_function['treatment_schema']; // string

// Conditionally suppress sections based on Find-a-Doc Settings configuration
$regions = get_field('physician_region',$post->ID);
$service_lines = get_field('physician_service_line',$post->ID);
$ontology_hide = uamswp_fad_ontology_hide();
	$hide_medical_ontology = $ontology_hide['hide_medical_ontology']; // bool

// Set the schema description and the meta description

	// Get excerpt
	$excerpt = get_field('physician_short_clinical_bio',$post->ID);

	// Get clinical bio
	$bio = get_field('physician_clinical_bio',$post->ID);

	// Create excerpt if none exists
	if ( empty( $excerpt ) ) {
		if ( $bio ) {
			$excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 155, '...');
		} else {
			$fallback_desc = $medium_name_attr . ' is ' . ($phys_title ? $phys_title_indef_article . ' ' . strtolower($phys_title_name) : 'a health care provider' ) . ($primary_appointment_title_attr ? ' at ' . $primary_appointment_title_attr : '') . ' employed by UAMS Health.';
			$excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 155, '...');
		}
	}

	// Set schema description
	$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value

	// Override theme's method of defining the meta description
	add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// Override theme's method of defining the meta page title
$meta_title_enhanced_addition = $phys_title_name_attr; // Word or phrase to inject into base meta title to form enhanced meta title
$meta_title_enhanced_x2_addition = $primary_appointment_city_attr; // Second word or phrase to inject into base meta title to form enhanced meta title
$meta_title_enhanced_x3_addition = $expertise_primary_name_attr; // Third word or phrase to inject into base meta title to form enhanced meta title
$meta_title_enhanced_x3_order = array( $page_title_attr, $meta_title_enhanced_addition, $meta_title_enhanced_x3_addition, $meta_title_enhanced_x2_addition ); // Optional pre-defined array for name order of enhanced meta title level 3 // Expects four values
$meta_title_vars = uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
// add_filter('seopress_titles_title', 'uamswp_fad_title', 20, 2);
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override the theme's method of defining the social meta tags
$meta_og_type = 'profile';
$meta_profile_first_name = $first_name_attr; // string // A name normally given to an individual by a parent or self-chosen.
$meta_profile_last_name = $last_name_attr; // string // A name inherited from a family or marriage and by which the individual is commonly known.
$meta_profile_gender = strtolower($gender_attr); // enum(male, female) // Their gender.
$meta_profile_gender = ( $meta_profile_gender == 'male' || $meta_profile_gender == 'female' ) ? $meta_profile_gender : ''; // Check against enum(male, female)

function be_remove_title_from_single_crumb( $crumb, $args ) { // Because BE is the man
	// Bring in variables from outside of the function
	global $full_name; // Defined on the template

	return substr( $crumb, 0, strrpos( $crumb, $args['sep'] ) ) . $args['sep'] . $full_name;
}
add_filter( 'genesis_single_crumb', 'be_remove_title_from_single_crumb', 10, 2 );

// SEOPress Breadcrumbs Fix
function sp_change_title_from_provider_crumb( $crumbs ) { // SEOPress
	// Bring in variables from outside of the function
	global $full_name; // Defined on the template

	$crumb = array_pop($crumbs);
	$provider_name = array($full_name, get_permalink());
	array_push($crumbs, $provider_name);
	return $crumbs;
}
add_filter('seopress_pro_breadcrumbs_crumbs', 'sp_change_title_from_provider_crumb', 20);

get_header();

while ( have_posts() ) : the_post();
	// ACF Fields - get_fields
	$service_line = get_field('physician_service_line');
	$npi = get_field('physician_npi');
	$bio_short = get_field('physician_short_clinical_bio');
	$video = get_field('physician_youtube_link');
	$affiliation = get_field('physician_affiliation');
	$hidden = get_field('physician_hidden');
	if ($resident) {
		$resident_profile_group = get_field('physician_resident_profile_group');
		if($resident_profile_group) {
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
	$boards = get_field( 'physician_boards' );
	$second_opinion = get_field('physician_second_opinion');
	$patients = get_field('physician_patient_types');
	$refer_req = get_field('physician_referral_required');
	$accept_new = get_field('physician_accepting_patients');
	$provider_portal = get_field('physician_portal');
	$provider_clinical_bio = get_field('physician_clinical_bio');
	// $provider_youtube_link = get_field('physician_youtube_link');
	$provider_clinical_admin_title = get_field('physician_clinical_admin_title');
	$provider_clinical_focus = get_field('physician_clinical_focus');
	$provider_awards = get_field('physician_awards');
	$provider_additional_info = get_field('physician_additional_info');
	$associations = get_field( 'physician_associations' );
	$publications = get_field('physician_select_publications');
	$pubmed_author_id = get_field('physician_pubmed_author_id');
	$pubmed_author_number = get_field('physician_author_number');
	$education = get_field('physician_education');
	$academic_bio = get_field('physician_academic_bio');
	$academic_appointment = get_field('physician_academic_appointment');
	$academic_admin_title = get_field('physician_academic_admin_title');
	$research_bio = get_field('physician_research_bio');
	$research_interests = get_field('physician_research_interests');
	$research_profiles_link = get_field('physician_research_profiles_link');

	// Query for whether UAMS Health Talk podcast section should be displayed on ontology pages/subsections
	$podcast_name = get_field('physician_podcast_name');
	$podcast_query_function = uamswp_fad_podcast_query( $podcast_name, $jump_link_count ); // Defines universal variables related to podcast
		$podcast_section_show = $podcast_query_function['podcast_section_show']; // bool
		$jump_link_count = $podcast_query_function['jump_link_count']; // int

	// Classes for indicating presence of content
	$provider_field_classes = '';
	if ($degrees && !empty($degrees)) { $provider_field_classes = $provider_field_classes . ' has-degrees'; }
	if ($prefix && !empty($prefix)) { $provider_field_classes = $provider_field_classes . ' has-prefix'; }
	if (has_post_thumbnail()) {
		$provider_field_classes = $provider_field_classes . ' has-image';
		$image_age = date('Y') - get_the_date( 'Y', get_post_thumbnail_id() ); // How old the provider image is in years
		$image_age_threshold = 10; // Set the threshold for how old a provider image can be before a new photo is needed
		if ( $image_age >= $image_age_threshold ) {
			$image_age = $image_age_threshold . '+'; // Cap attribute value at the threshold
		}
	}
	if ($service_line && !empty($service_line)) { $provider_field_classes = $provider_field_classes . ' has-service-line'; }
	if ($npi && !empty($npi)) { $provider_field_classes = $provider_field_classes . ' has-npi'; }
	if ($bio && !empty($bio)) { $provider_field_classes = $provider_field_classes . ' has-clinical-bio'; }
	if ($bio_short && !empty($bio_short)) { $provider_field_classes = $provider_field_classes . ' has-short-clinical-bio'; }
	if ($video && !empty($video)) { $provider_field_classes = $provider_field_classes . ' has-video'; }
	if ($condition_section_show) { $provider_field_classes = $provider_field_classes . ' has-condition'; }
	if ($treatment_section_show) { $provider_field_classes = $provider_field_classes . ' has-treatment'; }
	if ($location_section_show) { $provider_field_classes = $provider_field_classes . ' has-location'; }
	if ($clinical_resource_section_show) { $provider_field_classes = $provider_field_classes . ' has-clinical-resource'; }
	if ($affiliation && !empty($affiliation)) { $provider_field_classes = $provider_field_classes . ' has-affiliation'; }
	if ($expertise_section_show) { $provider_field_classes = $provider_field_classes . ' has-expertise'; }
	if ($hidden && !empty($hidden)) { $provider_field_classes = $provider_field_classes . ' has-hidden'; }
	// Add one instance of a class (' has-academic-appt') if there is a physician_academic_appointment row with a value in either/both of the fields.
	// Add one instance of a class (' has-empty-academic-title') if there is an empty academic title field in any of the physician_academic_appointment rows.
	// Add one instance of a class (' has-empty-academic-dept') if there is an empty academic department field in any of the physician_academic_appointment rows.
	if ($college_affiliation && !empty($college_affiliation)) { $provider_field_classes = $provider_field_classes . ' has-college-affiliation'; }
	if ($position && !empty($position)) { $provider_field_classes = $provider_field_classes . ' has-position'; }
	if ($bio_academic && !empty($bio_academic)) { $provider_field_classes = $provider_field_classes . ' has-academic-bio'; }
	if ($bio_academic_short && !empty($bio_academic_short)) { $provider_field_classes = $provider_field_classes . ' has-short-academic-bio'; }
	if ($office_location && !empty($office_location)) { $provider_field_classes = $provider_field_classes . ' has-office-location'; }
	if ($office_building && !empty($office_building)) { $provider_field_classes = $provider_field_classes . ' has-office-building'; }
	// Add one instance of a class (' has-contact-info') if there is a physician_contact_information row with a value in both of the fields.
	// Add one instance of a class (' has-empty-contact-info') if there is an empty information field in any of the physician_contact_information rows.
	// Add one instance of a class (' has-education') if there is a physician_education row with a value in either education_type or school.
	// Add one instance of a class (' has-empty-education-type') if there is an empty education_type field in any of the physician_education rows.
	if ($education && !empty($education)) { $provider_field_classes = $provider_field_classes . ' has-education'; }
	// Add one instance of a class (' has-empty-education-school') if there is an empty school field in any of the physician_education rows.
	if ($boards && !empty($boards)) { $provider_field_classes = $provider_field_classes . ' has-boards'; }
	if ($associations && !empty($associations)) { $provider_field_classes = $provider_field_classes . ' has-associations'; }
	if ($bio_research && !empty($bio_research)) { $provider_field_classes = $provider_field_classes . ' has-research-bio'; }
	if ($research_interests && !empty($research_interests)) { $provider_field_classes = $provider_field_classes . ' has-research-interests'; }
	if ($research_profile && !empty($research_profile)) { $provider_field_classes = $provider_field_classes . ' has-research-profile'; }
	if ($pubmed_author_id && !empty($pubmed_author_id)) { $provider_field_classes = $provider_field_classes . ' has-pubmed-id'; }
	// Add one instance of a class (' has-selected-pubs') if there is a physician_select_publications row with a value in either/both of the pubmed_id_pmid and pubmed_information fields.
	// Add one instance of a class (' has-empty-selected-pub-id') if there is an empty pubmed_id_pmid field in any of the physician_select_publications rows.
	// Add one instance of a class (' has-empty-selected-pub-info') if there is an empty pubmed_information field in any of the physician_select_publications rows.
	// Add one instance of a class (' has-awards') if there is a physician_awards row with a value in either/both of the year and title fields.
	// Add one instance of a class (' has-empty-selected-pub-id') if there is an empty year field in any of the physician_awards rows.
	// Add one instance of a class (' has-empty-selected-pub-info') if there is an empty title field in any of the physician_awards rows.
	if ($additional_info && !empty($additional_info)) { $provider_field_classes = $provider_field_classes . ' has-additional-info'; }
	if ($resident && !empty($resident)) { $provider_field_classes = $provider_field_classes . ' is-resident'; }
	if ($podcast_section_show) { $provider_field_classes = $provider_field_classes . ' has-podcast'; }

	// Set Ratings variables
	$rating_request = '';
	$rating_data = '';
	$rating_valid = '';
	if ( $npi ) {
		$rating_request = wp_nrc_cached_api( $npi );
		$rating_data = json_decode( $rating_request );
		if ( !empty( $rating_data ) ) {
			$rating_valid = $rating_data->valid;
		}
	}
	if ($rating_valid) { $provider_field_classes = $provider_field_classes . ' has-ratings'; }

	// Check if Make an Appointment section should be displayed
	if ( $eligible_appt ) {
		$appointment_section_show = true;
		$jump_link_count++;
	} else {
		$appointment_section_show = false;
	}

	// Check if Clinical Bio section should be displayed
	if ( $provider_clinical_bio || !empty ($video) ) {
		$clinical_bio_section_show = true;
	} else {
		$clinical_bio_section_show = false;
	}

	// Check if Academic Background section should be displayed
	if ( $resident || $academic_bio || $academic_appointment || $academic_admin_title || $education || $boards ) {
		$academic_section_show = true;
		$jump_link_count++;
	} else {
		$academic_section_show = false;
	}

	// Check if Research section should be displayed
	if ( !empty($research_bio) || !empty($research_interests) || !empty ( $publications ) || $pubmed_author_id || $research_profiles_link ) {
		$research_section_show = true;
		$jump_link_count++;
	} else {
		$research_section_show = false;
	}

	// Check if Ratings section should be displayed
	if ( $rating_valid ) {
		$ratings_section_show = true;
		$jump_link_count++;
	} else {
		$ratings_section_show = false;
	}

	// Check if Jump Links section should be displayed
	if ( $jump_link_count >= $jump_link_count_min ) {
		$jump_links_section_show = true;
	} else {
		$jump_links_section_show = false;
	}
?>

<div class="content-sidebar-wrap">
	<main class="doctor-item<?php echo $provider_field_classes; ?>" id="genesis-content"<?php echo (has_post_thumbnail() ? ' data-image-age="' . $image_age . '"' : ''); ?><?php echo ($service_line ? ' data-service-line="' . get_term( $service_line, 'service_line' )->name . '"' : ''); ?>>
		<section class="container-fluid p-0 p-xs-8 p-sm-10 doctor-info bg-white">
			<div class="row mx-0 mx-xs-n4 mx-sm-n8">
				<div class="col-12 col-xs p-4 py-xs-0 px-xs-4 px-sm-8 order-2 text">
					<h1 class="page-title">
						<span class="name"><?php echo $full_name; ?></span>
						<?php 

						if ($phys_title_name && !empty($phys_title_name)) { ?>
							<span class="subtitle"><?php echo ($phys_title_name ? $phys_title_name : ''); ?></span>
						<?php
						} ?>
					</h1>
					<?php 
					$l = 1;
					if ( $locations && $location_valid ) { ?>
						<div data-sectiontitle="Primary Location">
							<?php
							if ($eligible_appt) { ?>
								<h2 class="h3">Primary Appointment <?php echo $location_single_name; ?></h2>
							<?php
							} else { ?>
								<h2 class="h3">Primary <?php echo $location_single_name; ?></h2>
							<?php
							} // endif
							foreach ( $locations as $location ) {
								if ( 2 > $l ) {
									if ( get_post_status ( $location ) == 'publish' ) {

										// Reset variables
										$address_id = $location;

										// Parent Location 
										$location_has_parent = get_field('location_parent', $location);
										$location_parent_id = get_field('location_parent_id', $location);
										$parent_location = '';
										$parent_id = '';
										$parent_title = '';
										$parent_url = '';

										if ($location_has_parent && $location_parent_id) { 
											$parent_location = get_post( $location_parent_id );
										} // endif ($location_has_parent && $location_parent_id)
										// Get Post ID for Address & Image fields
										if ($parent_location) {
											$parent_id = $parent_location->ID;
											$parent_title = $parent_location->post_title;
											$parent_url = get_permalink( $parent_id );
											$address_id = $parent_id;
										} // endif ($parent_location)

										$location_address_1 = get_field('location_address_1', $address_id );
										$location_building = get_field('location_building', $address_id );
										if ($location_building) {
											$building = get_term($location_building, "building");
											$building_slug = $building->slug;
											$building_name = $building->name;
										} //endif ($location_building)
										$location_floor = get_field_object('location_building_floor', $address_id );
											$location_floor_value = '';
											$location_floor_label = '';
											if ( $location_floor && is_object($location_floor) ) {
												$location_floor_value = $location_floor['value'];
												$location_floor_label = $location_floor['choices'][ $location_floor_value ];
											} // endif ( $location_floor && is_object($location_floor) )
										$location_suite = get_field('location_suite', $address_id );
										$location_address_2 =
											( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
											. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
											. ( $location_suite ? $location_suite : '' );
										$location_address_2_schema =
											( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
											. ( $location_floor && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
											. ( $location_suite ? $location_suite : '' );

										$location_address_2_deprecated = get_field('location_address_2', $address_id );
										if (!$location_address_2) {
											$location_address_2 = $location_address_2_deprecated;
											$location_address_2_schema = $location_address_2_deprecated;
										} // endif (!$location_address_2)

										$location_city = get_field('location_city', $address_id);
										$location_state = get_field('location_state', $address_id);
										$location_zip = get_field('location_zip', $address_id);

										?>
										<p><strong><?php echo $primary_appointment_title; ?></strong><br />
										<?php
										if ( $parent_location ) { ?>
											(Part of <a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name"><?php echo $parent_title; ?></a>)<br />
										<?php
										} // endif ( $parent_location )
										echo $location_address_1; ?><br/>
										<?php echo $location_address_2 ? $location_address_2 . '<br/>' : '';
										echo $location_city . ', ' . $location_state . ' ' . $location_zip;
										$map = get_field( 'location_map', $address_id ); ?>
										<!-- <br /><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Directions</a> -->
										</p>
										<?php
										// Phone values
										$phone_output_id = $location;
										$phone_output = 'associated_locations';
										include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );
										?>
										<div class="btn-container">
											<a class="btn btn-primary" href="<?php echo get_the_permalink( $location ); ?>" data-itemtitle="<?php echo $primary_appointment_title_attr; ?>" data-categorytitle="View <?php echo $location_single_name_attr; ?>">
												View <?php echo $location_single_name; ?>
											</a>
											<?php if (1 < $location_count) { ?>
												<a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of <?php echo strtolower($location_plural_name_attr); ?> for this <?php echo strtolower($provider_single_name_attr); ?>" data-categorytitle="View All <?php echo $location_plural_name_attr; ?>">
													View All <?php echo $location_plural_name; ?>
												</a>
											<?php
											} // endif (1 < $location_count) ?>
										</div>
										<?php
										$l++;
									} // endif ( get_post_status ( $location ) == 'publish' )
								} // if ( 2 > $l )
							} // endforeach ( $locations as $location )
							// wp_reset_postdata(); ?>
						</div>
					<?php
					} // endif ( $locations && $location_valid ) ?> 
					<h2 class="h3">Overview</h2>
					<dl data-sectiontitle="Overview">
						<?php
						
						// Display area(s) of expertise
						if ( $expertise_section_show && !$hide_medical_ontology ) { ?>
							<dt><?php echo ( count($expertises) > 1 ? $expertise_plural_name : $expertise_single_name ); ?></dt>
							<?php
							foreach ( $expertises as $expertise ) {
								if ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 ) {
									echo '<dd><a href="' . get_permalink($expertise) . '" target="_self" data-sectiontitle="Overview" data-categorytitle="View ' . $expertise_single_name_attr . '">' . get_the_title($expertise) . '</a></dd>';
								} // endif ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 )
							} // endforeach( $expertises as $expertise )
						} // if ( $expertise_section_show && !$hide_medical_ontology )
					
						// Display if they accept new patients
						if ( $eligible_appt ) { ?>
							<dt>Accepting New Patients</dt>
							<?php 
							if ( $accept_new ) {
								// Display if they require referrals for new patients
								if ( $refer_req ) { ?>
									<dd>Yes (Referral Required)</dd>
								<?php
								} else { ?>
									<dd>Yes</dd>
								<?php 
								}
							} else { ?>
								<dd>No</dd>
							<?php
							} // endif
						} // endif

						// Display if they will provide second opinions
						if ( $second_opinion ) { ?>
							<dt>Provides Second Opinion</dt>
							<dd>Yes</dd>
						<?php
						}

						// Display all patient types
						if ( $patients ) { ?>
							<dt>Patient Type<?php echo( count($patients) > 1 ? 's' : '' );?></dt>
							<?php
							foreach( $patients as $patient ) {
								$patient_name = get_term( $patient, 'patient_type');
								echo '<dd>' . $patient_name->name . '</dd>';
								
							} // endforeach
						} // endif ( $patients )

						// Display all languages
						if( $languages && $language_list == 'English') {  ?>
							<dt class="sr-only">Language</dt>
							<?php echo '<dd class="sr-only">' . $language_list . '</dd>';
						} else { ?>
							<dt>Language<?php echo( $language_count > 1 ? 's' : '' );?></dt>
							<?php echo '<dd>' . $language_list . '</dd>';?>
						<?php
						} //endif ?>
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
						} else { ?>
							<p class="small"><em>Patient ratings are not available for this <?php echo strtolower($provider_single_name); ?>. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" aria-label="Learn why ratings are not available for this <?php echo strtolower($provider_single_name_attr); ?>" data-sectiontitle="Overview"><span aria-hidden="true">Why not?</span></a></em></p> 
						<?php
						} // endif ( $rating_valid ) else ?>
					</div>
					<?php
					if ( !$rating_valid ) { ?>
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
					} // endif ( !$rating_valid ) ?>
				</div>
				<?php 
				$docphoto = '/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg';
				if ( has_post_thumbnail() ) { ?>
					<div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image">
						<picture>
							<?php
							if ( function_exists( 'fly_add_image_size' ) ) { ?>
								<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 389, 519, 'center', 'center'); ?>"
									media="(min-width: 1200px)">
								<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 306, 408, 'center', 'center'); ?>"
									media="(min-width: 992px)">
								<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 182, 243, 'center', 'center'); ?>"
									media="(min-width: 768px)">
								<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 86, 115, 'center', 'center'); ?>"
									media="(min-width: 576px)">
								<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 380, 507, 'center', 'center'); ?>"
									media="(min-width: 1px)">
								<img src="<?php echo image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center'); ?>" alt="<?php echo $full_name_attr; ?>" />
								<?php
								$docphoto = image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center');
							} else {
								the_post_thumbnail( 'large', array( 'itemprop' => 'image' ) );
								$docphoto = get_the_post_thumbnail( 'large');
							} // endif ( function_exists( 'fly_add_image_size' ) ) else ?>
						</picture>
					</div>
				<?php
				} // endif ( has_post_thumbnail() ) ?>
			</div>
		</section>
		<?php
		// Begin Jump Links Section
		if ( $jump_links_section_show ) { ?>
			<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
				<h2><?php echo $fad_jump_links_title; ?></h2>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
					<ul class="nav navbar-nav">
						<?php
						if ( $appointment_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#appointment-info-1">Make an Appointment</a>
							</li>
						<?php
						} // endif
						if ( $clinical_bio_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#clinical-info">About</a>
							</li>
						<?php
						} // endif 
						if ( $podcast_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#podcast">Podcast</a>
							</li>
						<?php
						} // endif 
						if ( $clinical_resource_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#related-resources" title="Jump to the section of this page about <?php echo $clinical_resource_plural_name_attr; ?>"><?php echo $clinical_resource_plural_name; ?></a>
							</li>
						<?php
						} // endif 
						if ($academic_section_show) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#academic-info">Academic Background</a>
							</li>
						<?php
						} // endif 
						if ($research_section_show) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#research-info">Research</a>
							</li>
						<?php
						} // endif 
						if ( $condition_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#conditions"><?php echo $condition_plural_name; ?></a>
							</li>
						<?php
						} // endif 
						if ( $treatment_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#treatments"><?php echo $treatment_plural_name; ?></a>
							</li>
						<?php
						} // endif 
						if ( $expertise_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#expertise"><?php echo $expertise_plural_name; ?></a>
							</li>
						<?php
						} // endif 
						if ( $location_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#locations"><?php echo $location_plural_name; ?></a>
							</li>
						<?php
						} // endif 
						if ( $ratings_section_show ) { ?>
							<li class="nav-item">
								<a class="nav-link" href="#ratings">Ratings &amp; Reviews</a>
							</li>
						<?php
						} // endif ?>
					</ul>
				</div>
			</nav>
		<?php
		} // endif
		// End Jump Links Section

		if ( $appointment_section_show ) {
			$appointment_block_instance = 1;
			include( UAMS_FAD_PATH . '/templates/blocks/appointment-provider.php' );
		}
		
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

		// Display section for Clinical Bio, Clinical Video, Clinical Administrative Title(s), Clinical Focus ... only if there is a bio or video.
		if ( $clinical_bio_section_show ) { ?>
			<section class="uams-module clinical-info bg-auto" id="clinical-info">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="module-title"><span class="title">About <?php echo $short_name; ?></span></h2>
							<?php if ( $provider_clinical_split ) {
								// If there is a bio or video AND at least one of the other clinical things, visually split the layout ?>
								<div class="row content-split-lg">
									<div class="col-xs-12 col-lg-7">
										<div class="content-width">
							<?php
							} else { ?>
								<div class="module-body">
							<?php
							} // endif

							if ( $provider_clinical_bio ) { ?>
								<h3 class="sr-only">Clinical Biography</h3>
								<?php echo $provider_clinical_bio; ?>
							<?php
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

							if ( $provider_clinical_split ) { ?>
										</div>
									</div>
									<div class="col-xs-12 col-lg-5">
										<div class="content-width">
							<?php
							} // endif
							// Section for displaying clinical admin title
							if (true == false) { // Remove this if statement if we decide to display clinical admin title later.
								if ( have_rows('physician_clinical_admin_title') ) { ?>
									<h3 class="h4">Administrative Roles</h3>
									<dl>
										<?php while( have_rows('physician_clinical_admin_title') ) {
											the_row();
											$department = get_term( get_sub_field('physician_clinical_admin_area'), 'service_line' );
											$clinical_admin_title_tax = get_term( get_sub_field('clinical_admin_title_tax'), 'clinical_admin_title' );
											?>
											<dt><?php echo $department->name; ?></dt>
											<dd><?php echo $clinical_admin_title_tax->name; ?></dd>
										<?php
										} // endwhile ?>
									</dl>
								<?php
								} // endif ( have_rows('physician_clinical_admin_title') )
							} // endif

							if ( $provider_clinical_focus ) { ?>
								<h3 class="h4">Clinical Focus</h3>
								<?php echo $provider_clinical_focus;
							} // endif

							if ( $provider_clinical_split ) { ?>
										</div>
									</div>
								</div>
							<?php
							} else { ?>
								</div>
							<?php
							} // endif ?>
						</div>
					</div>
				</div>
			</section>
		<?php 
		} // endif ( $clinical_bio_section_show )

		// Construct UAMS Health Talk podcast section
		$podcast_filter = 'doctor';
		$podcast_subject = $short_name;
		uamswp_fad_podcast( $podcast_name, $podcast_subject, $podcast_filter );

		// Begin Clinical Resources Section
		$clinical_resource_section_more_link_key = '_resource_provider';
		$clinical_resource_section_more_link_value = $sort_name_param_value;
		$clinical_resource_section_title = $clinical_resource_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
		$clinical_resource_section_intro = $clinical_resource_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
		$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_provider;
		$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_provider;
		$clinical_resource_section_more_link_descr = $clinical_resource_fpage_more_link_descr_provider;
		include( UAMS_FAD_PATH . '/templates/parts/section-list-clinical-resource.php' );
		// End Clinical Resources Section

		// Begin Academic Bio Section
		$provider_academic_split = false;
		if ( $academic_bio && ( $academic_appointment || $academic_admin_title || $education || $boards ) ) {
			$provider_academic_split = true;
		}

		if ( $academic_section_show ) { ?>
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
							if ( !empty( $associations ) ) { ?>
								<h3 class="h4">Associations</h3>
								<ul>
									<?php foreach ( $associations as $association ) {
										$association_name = get_term( $association, 'association'); ?>
										<li><?php echo $association_name->name; ?></li>
									<?php
									} // endforeach; ?>
								</ul>
							<?php
							} // endif ( !empty( $associations ) )
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
		// End Academic Bio Section

		// Begin Research Bio Section
		if ( $research_section_show ) { ?>
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
		// End Research Bio Section

		// Begin Combined Conditions and Treatments Section
		$condition_treatment_section_title = $condition_treatment_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for combined condition/treatment section title in a general placement)
		$condition_treatment_section_intro = $condition_treatment_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for combined condition/treatment section intro text in a general placement)
		$condition_section_title = $condition_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
		$condition_section_intro = $condition_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
		$treatment_section_title = $treatment_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
		$treatment_section_intro = $treatment_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
		include( UAMS_FAD_PATH . '/templates/parts/section-list-condition-treatment.php' );
		// End Combined Conditions and Treatments Section

		// // Begin Conditions Section
		// $condition_section_title = $condition_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
		// $condition_section_intro = $condition_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
		// include( UAMS_FAD_PATH . '/templates/parts/section-list-condition.php' );
		// // End Conditions Section

		// // Begin Treatments Section
		// $treatment_section_title = $treatment_fpage_title_provider; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
		// $treatment_section_intro = $treatment_fpage_intro_provider; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
		// include( UAMS_FAD_PATH . '/templates/parts/section-list-treatment.php' );
		// // End Treatments Section

		// Begin Areas of Expertise Section
		$expertise_section_title = $expertise_fpage_title_provider;
		$expertise_section_intro = $expertise_fpage_intro_provider;
		include( UAMS_FAD_PATH . '/templates/parts/section-list-expertise.php' );
		// End Areas of Expertise Section

		// Begin Location Section
		$location_section_title = $location_fpage_title_provider; // Text to use for the section title
		$location_section_intro = $location_fpage_intro_provider; // Text to use for the section intro text
		$location_section_schema_query = true; // Query for whether to add locations to schema
		include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );
		// End Location Section

		if ( $ratings_section_show ) { ?>
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
			include( UAMS_FAD_PATH . '/templates/blocks/appointment-provider.php' );
		} ?>
	</main>
</div>
<?php
// Schema Data ?>
<script type='application/ld+json'>
{
	"@context": "http://www.schema.org",
	"@type": "Physician"<?php

	if ($full_name_attr) { ?>,
	"name": "<?php echo $full_name_attr; ?>"<?php
	} // endif ( $full_name_attr )
	
	?>,
	"url": "<?php echo get_permalink(); ?>",
	"logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>"<?php

	if ($docphoto) { ?>,
	"image": "<?php echo $docphoto; ?>"<?php
	} // endif ( $docphoto )

	if ($schema_description) { ?>,
	"description": "<?php echo $schema_description; ?>"<?php
	} // endif ( $schema_description )
	
	if ($condition_treatment_schema) { ?>,
	<?php echo $condition_treatment_schema;
	
	} // endif ( $condition_treatment_schema )

	if ($schema_description) { ?>,
	<?php echo $location_schema;
	} // endif ( $schema_description )

	if ( $rating_valid ) { ?>,
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue": "<?php echo $avg_rating; ?>",
		"ratingCount": "<?php echo $review_count; ?>"<?php
		echo ($comment_count  && '0' != $comment_count) ? ',
		"reviewCount": "'. $comment_count . '"' : ''; ?>
	}<?php
	} // endif ( $rating_valid )
	
	?>

}
</script>
<?php
endwhile; // end of the loop.

get_footer(); ?>