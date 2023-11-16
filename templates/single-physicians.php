<?php
/*
 *  Get ACF fields to use for meta data
 *  Add description from provider short description or full description *
 */

// Degrees and credentials (e.g., M.D., Ph.D.)

	$degrees = get_field( 'physician_degree', $post->ID );

	$degree_list = '';
	$i = 1;

	if ( $degrees ) {

		foreach ( $degrees as $degree ) {

			$degree_name = get_term( $degree, 'degree' );
			$degree_list .= $degree_name->name;

			if ( count($degrees) > $i ) {

				$degree_list .= ", ";

			}

			$i++;

		} // endforeach

	}

	$languages = get_field( 'physician_languages', $post->ID );
	$language_count = 0;

	if ($languages) {

		$language_count = count($languages);

	}

	$language_list = '';
	$i = 1;

	if ( $languages ) {

		foreach ( $languages as $language ) {

			$language_name = get_term_by( 'id', $language, 'language' );

			if ( is_object($language_name) ) {

				$language_list .= $language_name->name;

				if ( $language_count > $i ) {

					$language_list .= ", ";

				}

			}

			$i++;

		} // endforeach

	}

$prefix = get_field('physician_prefix',$post->ID);
$first_name = get_field('physician_first_name',$post->ID);
$middle_name = get_field('physician_middle_name',$post->ID);
$last_name = get_field('physician_last_name',$post->ID);
$pedigree = get_field('physician_pedigree',$post->ID);
$full_name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '') .  ( $degree_list ? ', ' . $degree_list : '' );
$full_name_attr = $full_name;
$full_name_attr = str_replace('"', '\'', $full_name_attr); // Replace double quotes with single quote
$full_name_attr = htmlentities($full_name_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
$full_name_attr = str_replace('&nbsp;', ' ', $full_name_attr); // Convert non-breaking space with normal space
$full_name_attr = html_entity_decode($full_name_attr); // Convert HTML entities to their corresponding characters
$medium_name = ($prefix ? $prefix .' ' : '') . $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;
$medium_name_attr = $medium_name;
$medium_name_attr = str_replace('"', '\'', $medium_name_attr); // Replace double quotes with single quote
$medium_name_attr = htmlentities($medium_name_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
$medium_name_attr = str_replace('&nbsp;', ' ', $medium_name_attr); // Convert non-breaking space with normal space
$medium_name_attr = html_entity_decode($medium_name_attr); // Convert HTML entities to their corresponding characters
$short_name = $prefix ? $prefix .'&nbsp;' .$last_name : $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '');
$short_name_attr = $short_name;
$short_name_attr = str_replace('"', '\'', $short_name_attr); // Replace double quotes with single quote
$short_name_attr = htmlentities($short_name_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
$short_name_attr = str_replace('&nbsp;', ' ', $short_name_attr); // Convert non-breaking space with normal space
$short_name_attr = html_entity_decode($short_name_attr); // Convert HTML entities to their corresponding characters
$sort_name = $last_name . ', ' . $first_name . ' ' . $middle_name;
$sort_name_param_value = sanitize_title_with_dashes($sort_name);
$excerpt = get_field('physician_short_clinical_bio',$post->ID);
$resident = get_field('physician_resident',$post->ID);
$resident_title_name = 'Resident Physician';
$phys_title = get_field('physician_title',$post->ID);
$phys_title_name = $resident ? $resident_title_name : get_term( $phys_title, 'clinical_title' )->name;
$phys_title_name_attr = $phys_title_name;
$phys_title_name_attr = str_replace('"', '\'', $phys_title_name_attr); // Replace double quotes with single quote
$phys_title_name_attr = htmlentities($phys_title_name_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
$phys_title_name_attr = str_replace('&nbsp;', ' ', $phys_title_name_attr); // Convert non-breaking space with normal space
$phys_title_name_attr = html_entity_decode($phys_title_name_attr); // Convert HTML entities to their corresponding characters
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
if ( substr($short_name, -1) == 's' ) { // If the provider's name ends in "s"...
    $short_name_possessive = $short_name . '\''; // Use an apostrophe with no "s" when indicating the possessive form
} else {
    $short_name_possessive = $short_name . '\'s'; // Use an apostrophe with an "s" when indicating the possessive form
}
$bio = get_field('physician_clinical_bio',$post->ID);
$eligible_appt = $resident ? 0 : get_field('physician_eligible_appointments',$post->ID);
// Check for valid locations
$locations = get_field('physician_locations',$post->ID);
$location_valid = false;
if ( !empty($locations) ) {
    foreach( $locations as $location ) {
        if ( get_post_status ( $location ) == 'publish' ) {
            $location_valid = true;
            $break;
        }
    }
}
// Get number of valid locations
$location_count = 0;
if( $locations && $location_valid ) {
    foreach( $locations as $location ) {
        if ( get_post_status ( $location ) == 'publish' ) {
            $location_count++;
        }
    } // endforeach
}
// Get primary appointment location name
$l = 1;
if( $locations && $location_valid ) {
    foreach( $locations as $location ) {
        if ( 2 > $l ){
            if ( get_post_status ( $location ) == 'publish' ) {
                $primary_appointment_title = get_the_title( $location );
                $primary_appointment_title_attr = $primary_appointment_title;
                $primary_appointment_title_attr = str_replace('"', '\'', $primary_appointment_title_attr); // Replace double quotes with single quote
                $primary_appointment_title_attr = str_replace('&#8217;', '\'', $primary_appointment_title_attr); // Replace right single quote with single quote
                $primary_appointment_title_attr = htmlentities($primary_appointment_title_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
                $primary_appointment_title_attr = str_replace('&nbsp;', ' ', $primary_appointment_title_attr); // Convert non-breaking space with normal space
                $primary_appointment_title_attr = html_entity_decode($primary_appointment_title_attr); // Convert HTML entities to their corresponding characters
                $primary_appointment_url = get_the_permalink( $location );
                $primary_appointment_city = get_field('location_city', $location);
                $primary_appointment_city_attr = $primary_appointment_city;
                $primary_appointment_city_attr = str_replace('"', '\'', $primary_appointment_city_attr); // Replace double quotes with single quote
                $primary_appointment_city_attr = str_replace('&#8217;', '\'', $primary_appointment_city_attr); // Replace right single quote with single quote
                $primary_appointment_city_attr = htmlentities($primary_appointment_city_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
                $primary_appointment_city_attr = str_replace('&nbsp;', ' ', $primary_appointment_city_attr); // Convert non-breaking space with normal space
                $primary_appointment_city_attr = html_entity_decode($primary_appointment_city_attr); // Convert HTML entities to their corresponding characters

                $l++;
            }
        }
    } // endforeach
}
// Set Areas of Expertise Variables
$expertises =  get_field('physician_expertise',$post->ID);
if ( $expertises ) {
    foreach ( $expertises as $expertise ) {
        if ( get_post_status ( $expertise ) == 'publish' ) {
            $expertise_primary_name = get_the_title($expertise);
            break;
        }
    }
}

// Hide Sections
$hide_medical_ontology = false;
$provider_region = get_field('physician_region',$post->ID);
$provider_service_line = get_field('physician_service_line',$post->ID);
if( have_rows('remove_ontology_criteria', 'option') ):
    while( have_rows('remove_ontology_criteria', 'option') ): the_row();
        $remove_region = get_sub_field('remove_regions', 'option');
        $remove_service_line = get_sub_field('remove_service_lines', 'option');
        if ( (!empty($remove_region) && in_array(implode('',$provider_region), $remove_region)) && empty($remove_service_line) ) {
            $hide_medical_ontology = true;
            break;
        } elseif ( empty($remove_region) && (!empty($remove_service_line) && in_array($provider_service_line, $remove_service_line) ) ) {
            $hide_medical_ontology = true;
            break;
        } elseif( (!empty($remove_region) && in_array(implode('',$provider_region), $remove_region)) && (!empty($remove_service_line) && in_array($provider_service_line, $remove_service_line) ) ) {
            $hide_medical_ontology = true;
            break;
        }
    endwhile;
endif;

// Set meta description
if (empty($excerpt)){
    if ($bio){
        $excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 155, '...');
    } else {
        $fallback_desc = $medium_name_attr . ' is ' . ($phys_title ? $phys_title_indef_article . ' ' . strtolower($phys_title_name) : 'a health care provider' ) . ($primary_appointment_title_attr ? ' at ' . $primary_appointment_title_attr : '') .  ' employed by UAMS Health.';
        $excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 155, '...');
    }
}
$schema_description = $excerpt;  // Used for Schema Data. Should ALWAYS have a value

// Override theme's method of defining the meta description
function sp_titles_desc($html) {
    global $excerpt;
	$html = $excerpt;
	return $html;
}
add_filter('seopress_titles_desc', 'sp_titles_desc');

// Override theme's method of defining the page title
function uamswp_fad_title($html) {
    global $full_name_attr;
    global $phys_title_name_attr;
    global $primary_appointment_city_attr;
    global $expertise_primary_name;

    $meta_title_chars_max = 60; // The recommended length for meta titles is 50-60 characters. Sets the max to 60.
    $meta_title_separator = ' | '; // Characters separating components of the meta title

    // Base meta title ("{Full display name} | UAMS Health")
    $meta_title_base = $full_name_attr . $meta_title_separator . get_bloginfo( "name" ); // Construct the meta title
    $meta_title_base_chars = strlen( $meta_title_base ); // Count the characters in the meta title

    // Base meta title ("{Full display name} | {Clinical title} | UAMS Health")
    $meta_title_enhanced = $full_name_attr . $meta_title_separator . $phys_title_name_attr . $meta_title_separator . get_bloginfo( "name" ); // Construct the meta title
    $meta_title_enhanced_chars = strlen( $meta_title_enhanced ); // Count the characters in the meta title

    // Enhanced meta title level 1 ("{Full display name} | {Clinical title} | {City of primary location} | UAMS Health")
    $meta_title_enhanced_x2 = $full_name_attr . $meta_title_separator . $phys_title_name_attr . $meta_title_separator . $primary_appointment_city_attr . $meta_title_separator . get_bloginfo( "name" ); // Construct the meta title
    $meta_title_enhanced_x2_chars = strlen( $meta_title_enhanced_x2 ); // Count the characters in the meta title

    // Enhanced meta title level 2 ("{Full display name} | {Clinical title} | {Primary area of expertise} | {City of primary location} | UAMS Health")
    $meta_title_enhanced_x3 = $full_name_attr . $meta_title_separator . $phys_title_name_attr . $meta_title_separator . $expertise_primary_name . $meta_title_separator . $primary_appointment_city_attr . $meta_title_separator . get_bloginfo( "name" ); // Construct the meta title
    $meta_title_enhanced_x3_chars = strlen( $meta_title_enhanced_x3 ); // Count the characters in the meta title

    if ( $expertise_primary_name && ( $meta_title_enhanced_x3_chars <= $meta_title_chars_max ) ) {
        $html = $meta_title_enhanced_x3;
    } elseif ( $primary_appointment_city_attr && ( $meta_title_enhanced_x2_chars <= $meta_title_chars_max ) ) {
        $html = $meta_title_enhanced_x2;
    } elseif ( $meta_title_enhanced_chars <= $meta_title_chars_max ) {
        $html = $meta_title_enhanced;
    } else {
        $html = $meta_title_base;
    }
    return $html;
}
// add_filter('seopress_titles_title', 'uamswp_fad_title', 20, 2);
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

function be_remove_title_from_single_crumb( $crumb, $args ) { // Because BE is the man
    global $full_name;
    return substr( $crumb, 0, strrpos( $crumb, $args['sep'] ) ) . $args['sep'] . $full_name;
}
add_filter( 'genesis_single_crumb', 'be_remove_title_from_single_crumb', 10, 2 );

// SEOPress Breadcrumbs Fix
function sp_change_title_from_provider_crumb( $crumbs ) { // SEOPress
    global $full_name;
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
    $npi =  get_field('physician_npi');
    $bio_short = get_field('physician_short_clinical_bio');
    $video = get_field('physician_youtube_link');
    $affiliation = get_field('physician_affiliation');
    $hidden = get_field('physician_hidden');
    $resident_profile_group = get_field('physician_resident_profile_group');
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
    $conditions = get_field('physician_conditions');
    $conditions_cpt = get_field('physician_conditions_cpt');
    $treatments = get_field('physician_treatments');
    $treatments_cpt = get_field('physician_treatments_cpt');
    $second_opinion = get_field('physician_second_opinion');
    $patients = get_field('physician_patient_types');
    $refer_req = get_field('physician_referral_required');
    $accept_new = get_field('physician_accepting_patients');
    $physician_portal = get_field('physician_portal');
    $physician_clinical_bio = get_field('physician_clinical_bio');
    // $physician_youtube_link = get_field('physician_youtube_link');
    $physician_clinical_admin_title = get_field('physician_clinical_admin_title');
    $physician_clinical_focus = get_field('physician_clinical_focus');
    $physician_awards = get_field('physician_awards');
    $physician_additional_info = get_field('physician_additional_info');
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
    $podcast_name = get_field('physician_podcast_name');


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
    // if ($conditions && !empty($conditions)) { $provider_field_classes = $provider_field_classes . ' has-condition'; }
    if ($conditions_cpt && !empty($conditions_cpt)) { $provider_field_classes = $provider_field_classes . ' has-condition'; }
    // if ($treatments && !empty($treatments)) { $provider_field_classes = $provider_field_classes . ' has-treatment'; }
    if ($treatments_cpt && !empty($treatments_cpt)) { $provider_field_classes = $provider_field_classes . ' has-treatment'; }
    if ($locations && $location_valid) { $provider_field_classes = $provider_field_classes . ' has-location'; }
    if ($affiliation && !empty($affiliation)) { $provider_field_classes = $provider_field_classes . ' has-affiliation'; }
    if ($expertises && !empty($expertises)) { $provider_field_classes = $provider_field_classes . ' has-expertise'; }
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

    $title_append = ' by ' . $short_name;

    // Set Conditions variables
    $args = (array(
        'post_type' => "condition",
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
        'post__in' => $conditions_cpt
    ));
    $conditions_cpt_query = new WP_Query( $args );
    $condition_schema = '';

    // Set Treatments variables
    $args = (array(
        'post_type' => "treatment",
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
        'post__in' => $treatments_cpt
    ));
    $treatments_cpt_query = new WP_Query( $args );
    $treatment_schema = '';

    // Set Areas of Expertise Variables
    $expertise_valid = false;
    if ( $expertises ) {
        foreach ( $expertises as $expertise ) {
            if ( get_post_status ( $expertise ) == 'publish' ) {
                $expertise_valid = true;
                break;
            }
        }
    }

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

	// Clinical Resources
	$resources =  get_field('physician_clinical_resources');
    $resource_postsPerPage = 4; // Set this value to preferred value (-1, 4, 6, 8, 10, 12)
    $resource_more = false;
    $args = (array(
        'post_type' => "clinical-resource",
        'order' => 'DESC',
        'orderby' => 'post_date',
        'posts_per_page' => $resource_postsPerPage,
        'post_status' => 'publish',
        'post__in'	=> $resources
    ));
    $resource_query = new WP_Query( $args );

    // Set logic for displaying jump links and sections
    $jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
    $jump_link_count = 0;

        // Check if Make an Appointment section should be displayed
        if ( $eligible_appt ) {
            $show_appointment_section = true;
            $jump_link_count++;
        } else {
            $show_appointment_section = false;
        }

        // Check if Clinical Bio section should be displayed
        if ( $physician_clinical_bio || !empty ($video) ) {
            $show_clinical_bio_section = true;
        } else {
            $show_clinical_bio_section = false;
        }

        // Check if Academic Background section should be displayed
        if ( $resident || $academic_bio || $academic_appointment || $academic_admin_title || $education || $boards ) {
            $show_academic_section = true;
            $jump_link_count++;
        } else {
            $show_academic_section = false;
        }

        // Check if Podcast section should be displayed
        if ( $podcast_name ) {
            $show_podcast_section = true;
            $jump_link_count++;
        } else {
            $show_podcast_section = false;
        }

	    // Check if Clinical Resources section should be displayed
        if( $resources && $resource_query->have_posts() ) {
            $show_related_resource_section = true;
            $jump_link_count++;
        } else {
            $show_related_resource_section = false;
        }

        // Check if Research section should be displayed
        if ( !empty($research_bio) || !empty($esearch_interests) || !empty ( $publications ) || $pubmed_author_id || $research_profiles_link ) {
            $show_research_section = true;
            $jump_link_count++;
        } else {
            $show_research_section = false;
        }

        // Check if Conditions section should be displayed
        if ( $conditions_cpt && $conditions_cpt_query->posts && !$hide_medical_ontology ) {
            $show_conditions_section = true;
            $jump_link_count++;
        } else {
            $show_conditions_section = false;
        }

        // Check if Treatments section should be displayed
        if ( $treatments_cpt && $treatments_cpt_query->posts && !$hide_medical_ontology ) {
            $show_treatments_section = true;
            $jump_link_count++;
        } else {
            $show_treatments_section = false;
        }

        // Check if Areas of Expertise section should be displayed
        if ( $expertise_valid && !$hide_medical_ontology ) {
            $show_aoe_section = true;
            $jump_link_count++;
        } else {
            $show_aoe_section = false;
        }

        // Check if Locations section should be displayed
        if ( $locations && $location_valid ) {
            $show_locations_section = true;
            $jump_link_count++;
        } else {
            $show_locations_section = false;
        }

        // Check if Ratings section should be displayed
        if ( $rating_valid ) {
            $show_ratings_section = true;
            $jump_link_count++;
        } else {
            $show_ratings_section = false;
        }

        // Check if Jump Links section should be displayed
        if ( $jump_link_count >= $jump_link_count_min ) {
            $show_jump_links_section = true;
        } else {
            $show_jump_links_section = false;
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
                        <?php } ?>
                    </h1>
                    <?php
                        $l = 1;
                        if( $locations && $location_valid ): ?>
                        <div data-sectiontitle="Primary Location">
                            <?php if ($eligible_appt) { ?>
                                <h2 class="h3">Primary Appointment Location</h2>
                            <?php } else { ?>
                                <h2 class="h3">Primary Location</h2>
                            <?php } // endif ?>
                            <?php foreach( $locations as $location ):
                                    if ( 2 > $l ){
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
                                            }
                                            // Get Post ID for Address & Image fields
                                            if ($parent_location) {
                                                $parent_id = $parent_location->ID;
                                                $parent_title = $parent_location->post_title;
                                                $parent_url = get_permalink( $parent_id );
                                                $address_id = $parent_id;
                                            }

                                            $location_address_1 = get_field('location_address_1', $address_id );
                                            $location_building = get_field('location_building', $address_id );
                                            if ($location_building) {
                                                $building = get_term($location_building, "building");
                                                $building_slug = $building->slug;
                                                $building_name = $building->name;
                                            }
                                            $location_floor = get_field_object('location_building_floor', $address_id );
                                                $location_floor_value = '';
                                                $location_floor_label = '';
                                                if ( $location_floor && is_object($location_floor) ) {
                                                    $location_floor_value = $location_floor['value'];
                                                    $location_floor_label = $location_floor['choices'][ $location_floor_value ];
                                                }
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
                                            }

                                            $location_city = get_field('location_city', $address_id);
                                            $location_state = get_field('location_state', $address_id);
                                            $location_zip = get_field('location_zip', $address_id);

                                    ?>
                                <p><strong><?php echo $primary_appointment_title; ?></strong><br />
                                <?php if ( $parent_location ) { ?>
                                    (Part of <a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name"><?php echo $parent_title; ?></a>)<br />
                                <?php } // endif ?>
                                <?php echo $location_address_1; ?><br/>
                                <?php echo $location_address_2 ? $location_address_2 . '<br/>' : ''; ?>
                                <?php echo $location_city . ', ' . $location_state . ' ' . $location_zip; ?>
                                <?php $map = get_field( 'location_map', $address_id ); ?>
                                <!-- <br /><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Directions</a> -->
                                </p>
                                <?php
                                    // Phone values
                                    $phone_output_id = $location;
                                    $phone_output = 'associated_locations';
                                    include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );
                                 ?>
                                <div class="btn-container">
                                    <a class="btn btn-primary" href="<?php echo get_the_permalink( $location ); ?>" data-itemtitle="<?php echo $primary_appointment_title_attr; ?>" data-categorytitle="View Location">
                                        View Location
                                    </a>
                                    <?php if (1 < $location_count) { ?>
                                        <a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of locations for this provider" data-categorytitle="View All Locations">
                                            View All Locations
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php $l++;
	                                }
                                } ?>
                            <?php endforeach;
								// wp_reset_postdata(); ?>
                        </div>
						<?php endif; ?>
                    <h2 class="h3">Overview</h2>
                    <dl data-sectiontitle="Overview">
                    <?php // Display area(s) of expertise
                    $expertise_valid = false;
                    if ($expertises && !empty($expertises) && !$hide_medical_ontology) {
                        foreach( $expertises as $expertise ) {
                            if ( get_post_status ( $expertise ) == 'publish' ) {
                               $expertise_valid = true;
                               $break;
                            }
                        }
                        if ( $expertise_valid ) {
                        ?>
                        <dt>Area<?php echo( count($expertises) > 1 ? 's' : '' );?> of Expertise</dt>
                        <?php foreach( $expertises as $expertise ) {
                            if ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 ) {
                                echo '<dd><a href="' . get_permalink($expertise) . '" target="_self" data-sectiontitle="Overview" data-categorytitle="View Area of Expertise">' . get_the_title($expertise) . '</a></dd>';
                            }
                        } ?>
                        <?php }
                    } ?>
                    <?php // Display if they accept new patients
                    if ( $eligible_appt ) { ?>
                        <dt>Accepting New Patients</dt>
                        <?php
                        if ($accept_new) {
                            // Display if they require referrals for new patients
                            if ( $refer_req ) { ?>
                                <dd>Yes (Referral Required)</dd>
                            <?php } else { ?>
                                <dd>Yes</dd>
                            <?php }
                        } else { ?>
                            <dd>No</dd>
                        <?php } // endif
                    } // endif ?>
                    <?php  // Display if they will provide second opinions
                    if ($second_opinion) { ?>
                        <dt>Provides Second Opinion</dt>
                        <dd>Yes</dd>
                    <?php } ?>
                    <?php // Display all patient types
                        if( $patients ) {
                        ?>
                            <dt>Patient Type<?php echo( count($patients) > 1 ? 's' : '' );?></dt>
                            <?php foreach( $patients as $patient ): ?>
                                <?php $patient_name = get_term( $patient, 'patient_type');
                                    echo '<dd>' . $patient_name->name . '</dd>';
                                ?>
                            <?php endforeach; ?>
                    <?php } // endif ?>
                    <?php // Display all languages
                        if( $languages && $language_list == 'English') {
                        ?>
                        <dt class="sr-only">Language</dt>
                        <?php echo '<dd class="sr-only">' . $language_list . '</dd>';?>
                    <?php } else { ?>
                        <dt>Language<?php echo( $language_count > 1 ? 's' : '' );?></dt>
                        <?php echo '<dd>' . $language_list . '</dd>';?>
                    <?php } //endif ?>
                    </dl>
                    <?php
                        echo '<div class="rating" aria-label="Patient Rating">';
                        if ( $rating_valid ){
                            $avg_rating = $rating_data->profile->averageRatingStr;
                            $avg_rating_dec = $rating_data->profile->averageRating;
                            $review_count = $rating_data->profile->reviewcount;
                            $comment_count = $rating_data->profile->bodycount;
                            echo '<div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: '. $avg_rating_dec/5 * 100 .'%;"></div></div>';
                            echo '<div class="ratings-score">'. $avg_rating .'<span class="sr-only"> out of 5</span></div>';
                            echo '<div class="w-100"></div>';
                            echo '<a href="#ratings" aria-label="Jump to Patient Ratings and Reviews" data-sectiontitle="Overview">';
                            echo '<div class="ratings-count-lg" aria-hidden="true">'. $review_count .' Patient Satisfaction Ratings</div>';
                            echo '<div class="ratings-comments-lg" aria-hidden="true">'.  $comment_count .' comments</div>';
                            echo '</a>';
                        } else { ?>
                            <p class="small"><em>Patient ratings are not available for this provider. <a data-toggle="modal" data-target="#why_not_modal" class="no-break" tabindex="0" href="#" aria-label="Learn why ratings are not available for this provider" data-sectiontitle="Overview"><span aria-hidden="true">Why not?</span></a></em></p>
                        <?php
                        }
                        echo '</div>';
                    ?>
                    <?php if( !$rating_valid ) { ?>
                        <div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why_not_modal" aria-modal="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="WhyNotTitle">Why are there no ratings?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>There is no publicly available rating for this medical professional for one of three reasons:</p>
                                        <ul>
                                            <li>He or she does not see patients</li>
                                            <li>He or she sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</li>
                                            <li>He or she is a resident physician.</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
                $docphoto = '/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg';
                if ( has_post_thumbnail() ) { ?>
                <div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image">
                    <picture>
                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
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
                        <?php $docphoto = image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center');
                             } else {
                                the_post_thumbnail( 'large',  array( 'itemprop' => 'image' ) );
                                $docphoto = get_the_post_thumbnail( 'large');
                             } //endif ?>
                    </picture>
                </div>
                <?php } //endif ?>
            </div>
        </section>
        <?php // Begin Jump Links Section
        if ( $show_jump_links_section ) { ?>
            <nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
                <h2>Contents</h2>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse inner-container" id="jump-link-nav">
                    <ul class="nav navbar-nav">
                        <?php if ( $show_appointment_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#appointment-info-1">Make an Appointment</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_clinical_bio_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#clinical-info">About</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_podcast_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#podcast">Podcast</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_related_resource_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#related-resources" title="Jump to the section of this page about Resources">Resources</a>
                            </li>
                        <?php } ?>
                        <?php if ($show_academic_section) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#academic-info">Academic Background</a>
                            </li>
                        <?php } ?>
                        <?php if ($show_research_section) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#research-info">Research</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_conditions_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#conditions">Conditions</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_treatments_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#treatments">Treatments &amp; Procedures</a>
                            </li>
                        <?php } ?>
                        <?php if ( $show_aoe_section ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#expertise">Areas of Expertise</a>
                            </li>
                        <?php } ?>
                        <?php if ($show_locations_section) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#locations">Locations</a>
                            </li>
                        <?php } ?>
                        <?php if ($show_ratings_section) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#ratings">Ratings &amp; Reviews</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        <?php } // endif
        // End Jump Links Section ?>
        <?php if ( $show_appointment_section ) {
            $appointment_block_instance = 1;
			include( UAMS_FAD_PATH . '/templates/blocks/appointment-provider.php' );
		} ?>

        <?php
            $physician_clinical_split = false;
            if (
                ( $show_clinical_bio_section ) // column A stuff
                && ( $physician_clinical_focus ) // column B stuff
                // && ( $physician_clinical_admin_title || $physician_clinical_focus ) // Alternate column B stuff if we decide to display clinical admin title
                ) {
                $physician_clinical_split = true; // If there is stuff for column A and column B, split the section into two columns
            }

            // Display section for Clinical Bio, Clinical Video, Clinical Administrative Title(s), Clinical Focus ... only if there is a bio or video.
            if ( $show_clinical_bio_section ) { ?>
            <section class="uams-module clinical-info bg-auto" id="clinical-info">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="module-title"><span class="title">About <?php echo $short_name; ?></span></h2>


                            <?php if ( $physician_clinical_split ) {
                                // If there is a bio or video AND at least one of the other clinical things, visually split the layout ?>
                                <div class="row content-split-lg">
                                <div class="col-xs-12 col-lg-7">
                                <div class="content-width">
                            <?php } else { ?>
                                <div class="module-body">
                            <?php } // endif
                            if ( $physician_clinical_bio ) { ?>
                                <h3 class="sr-only">Clinical Biography</h3>
                                <?php echo $physician_clinical_bio; ?>
                            <?php } // endif
                            if($video) { ?>
                                <?php if(function_exists('lyte_preparse')) {
                                    echo '<div class="alignwide">';
                                    echo lyte_parse( str_replace(['https:', 'http:'], 'httpv:', $video ) );
                                    echo '</div>';
                                } else {
                                    echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
                                    echo wp_oembed_get( $video );
                                    echo '</div>';
                                } ?>
                            <?php } // endif
                            if ( $physician_clinical_split ) { ?>
                                </div>
                                </div>
                                <div class="col-xs-12 col-lg-5">
                                <div class="content-width">
                            <?php } // endif ?>
                            <?php // Section for displaying clinical admin title
                            if (true == false) { // Remove this if statement if we decide to display clinical admin title later.
                                if( have_rows('physician_clinical_admin_title') ): ?>
                                    <h3 class="h4">Administrative Roles</h3>
                                    <dl>
                                    <?php while( have_rows('physician_clinical_admin_title') ): the_row();
                                        $department = get_term( get_sub_field('physician_clinical_admin_area'), 'service_line' );
                                        $clinical_admin_title_tax = get_term( get_sub_field('clinical_admin_title_tax'), 'clinical_admin_title' );
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $clinical_admin_title_tax->name; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                                <?php endif;
                            } // endif ?>
                            <?php if($physician_clinical_focus) { ?>
                                <h3 class="h4">Clinical Focus</h3>
                                <?php echo $physician_clinical_focus; ?>
                            <?php } // endif
                            if ( $physician_clinical_split ) { ?>
                                </div>
                                </div>
                                </div>
                            <?php } else { ?>
                                </div>
                            <?php } // endif ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php } // endif ?>
        <?php
            // UAMS Health Talk Podcast
            if ($podcast_name) {
        ?>
            <section class="uams-module podcast-list bg-auto" id="podcast">
                <script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
                </script>
                <script type="text/javascript">
                    radiomd_embedded_filtered_doctor("uams","radiomd-embedded-filtered-doctor",303,1837,"<?php echo $podcast_name; ?>"); </script>
                <style type="text/css">
                    #radiomd-embedded-filtered-doctor iframe {
                        width: 100%;
                        border: none;
                    }
                </style>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>
                            <div class="module-body text-center">
                                <p class="lead">In the UAMS Health Talk podcast, experts from UAMS talk about a variety of health topics, providing tips and guidelines to help people lead healthier lives. Listen to the episode(s) featuring <?php echo $short_name; ?>.</p>
                            </div>
                            <div class="content-width mt-8" id="radiomd-embedded-filtered-doctor"></div>
                        </div>
                        <div class="col-12 more">
                            <p class="lead">Find other great episodes on other topics and from other UAMS Health providers.</p>
                            <div class="cta-container">
                                <a href="/podcast/" class="btn btn-primary" aria-label="Listen to more episodes of the UAMS Health Talk podcast">Listen to More Episodes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
        // End UAMS Health Talk Podcast

        // Begin Clinical Resources Section
        $resource_heading_related_pre = false; // "Related Resources"
        $resource_heading_related_post = true; // "Resources Related to __"
        $resource_heading_related_name = $short_name; // To what is it related?
        $resource_more_suppress = false; // Force div.more to not display
        $resource_more_key = '_resource_provider';
        $resource_more_value = $sort_name_param_value;
        if( $show_related_resource_section ) {
            include( UAMS_FAD_PATH . '/templates/blocks/clinical-resources.php' );
        }
        // End Clinical Resources Section

        // Begin Academic Bio Section
        $physician_academic_split = false;
            if ( $academic_bio && ( $academic_appointment || $academic_admin_title || $education || $boards ) ) {
                $physician_academic_split = true;
            }

            if( $show_academic_section ): ?>
        <section class="uams-module academic-info bg-auto" id="academic-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title"><span class="title"><?php echo $short_name_possessive; ?> Academic Background</span></h2>
                        <?php if ( $physician_academic_split ) {
                            // If there is a bio AND at least one of the other academic things, visually split the layout ?>
                            <div class="row content-split-lg">
                            <div class="col-xs-12 col-lg-7">
                            <div class="content-width">
                        <?php } else { ?>
                            <div class="module-body">
                        <?php } // endif
                        if ( $academic_bio ) { ?>
                            <h3 class="sr-only">Academic Biography</h3>
                            <?php echo $academic_bio; ?>
                        <?php } // endif?>
                        <?php if ( $physician_academic_split ) { ?>
                            </div>
                            </div>
                            <div class="col-xs-12 col-lg-5">
                            <div class="content-width">
                        <?php } // endif ?>
                            <?php
                                if( have_rows('physician_academic_admin_title') ): ?>
                                    <h3 class="h4">Administrative Roles</h3>
                                    <dl>
                                    <?php while( have_rows('physician_academic_admin_title') ): the_row(); ?>
                                    <?php
                                        $department = get_term( get_sub_field('department'), 'academic_department' );
                                        $academic_admin_title_tax = get_term( get_sub_field('academic_admin_title_tax'), 'academic_admin_title' );
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $academic_admin_title_tax->name; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif; ?>
                            <?php
                                // $academic_appointments = get_field('physician_academic_appointment');
                                if( have_rows('physician_academic_appointment') ): ?>
                                    <h3 class="h4">Faculty Appointments</h3>
                                    <dl>
                                    <?php while( have_rows('physician_academic_appointment') ): the_row(); ?>
                                    <?php
                                        $department = get_term( get_sub_field('department'), 'academic_department' );
                                        $academic_title_tax = get_term( get_sub_field('academic_title_tax'), 'academic_title' );
                                        if ($academic_title_tax->name) {
                                            $academic_title = $academic_title_tax->name;
                                        } else {
                                            $academic_title = get_sub_field('academic_title');
                                        }
                                    ?>
                                        <dt><?php echo $department->name; ?></dt>
                                        <dd><?php echo $academic_title; ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif; ?>
                            <?php
                                if( $resident ): ?>
                                    <h3 class="h4">Residency Program</h3>
                                    <dl>
                                        <dt><?php echo $resident_academic_department_name; ?></dt>
                                        <dd><?php echo $resident_academic_name; ?></dd>
                                    </dl>
                            <?php endif; ?>
                            <?php
                                if( have_rows('physician_education') ): ?>
                                    <h3 class="h4">Education and Training</h3>
                                    <dl>
                                    <?php while( have_rows('physician_education') ): the_row();
                                        $school_name = get_term( get_sub_field('school'), 'school');
                                        $education_type = get_term( get_sub_field('education_type'), 'educationtype');
                                    ?>
                                        <dt><?php echo $education_type->name; ?></dt>
                                        <dd><?php echo $school_name->name; ?><?php echo (get_sub_field('description') ? '<br /><span class="subtitle">' . get_sub_field('description') .'</span>' : ''); ?></dd>
                                    <?php endwhile; ?>
                                    </dl>
                            <?php endif;

                                if( ! empty( $boards ) ): ?>
                            <h3 class="h4">Professional Certifications</h3>
                            <ul>
                            <?php foreach ( $boards as $board ) :
                                $board_name = get_term( $board, 'board'); ?>
                                <li><?php echo $board_name->name; ?></li>
                                <?php // }; ?>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif;

                                if( ! empty( $associations ) ): ?>
                            <h3 class="h4">Associations</h3>
                            <ul>
                            <?php foreach ( $associations as $association ) :
                                $association_name = get_term( $association, 'association'); ?>
                                <li><?php echo $association_name->name; ?></li>
                                <?php // }; ?>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        <?php if ( $physician_academic_split ) { ?>
                            </div>
                            </div>
                            </div>
                        <?php } else { ?>
                            </div>
                        <?php } // endif ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif;
        // End Academic Bio Section

        // Begin Research Bio Section
        if( $show_research_section ): ?>
        <section class="uams-module research-info bg-auto" id="research-info">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title"><span class="title"><?php echo $short_name_possessive; ?> Research</span></h2>
                        <div class="module-body">
                            <?php
                                if($research_bio)
                                {
                                    echo $research_bio;
                                }
                            ?>
                            <?php
                                if($research_interests)
                                { ?>
                                <h3>Research Interests</h3>
                            <?php
                                    echo $research_interests;
                                }
                            ?>
                            <?php
                                if( !empty ( $publications ) ): ?>
                            <h3>Selected Publications</h3>
                            <ul>
                            <?php foreach( $publications as $publication ): ?>
                                <li><?php echo $publication['pubmed_information']; ?></li>
                            <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                            <?php if( $pubmed_author_id ): ?>
                                <?php
                                    $pubmedid = trim($pubmed_author_id);
                                    $pubmedcount = ($pubmed_author_number ? $pubmed_author_number : '3');
                                ?>
                                <h3>Latest Publications</h3>
                                <p>Publications listed below are automatically derived from MEDLINE/PubMed and other sources, which might result in incorrect or missing publications.</p>
                                <?php echo do_shortcode( '[pubmed terms="' . urlencode($pubmedid) .'%5BAuthor%5D" count="' . $pubmedcount .'"]' ); ?>
                            <?php endif; ?>
                            <?php if( $research_profiles_link ): ?>
                                <h3>UAMS Research Profile</h3>
                                <p>Each UAMS faculty member has a research profile page that includes biographical and contact information, a list of their most recent grant activity and a list of their PubMed publications.</p>
                                <p><a class="btn btn-outline-primary" href="<?php echo $research_profiles_link; ?>" data-categorytitle="View Research Profile">View <?php echo $short_name_possessive; ?> research profile</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif;
        // End Research Bio Section

        // Begin Conditions Section
        // load all 'conditions' terms for the post

            // Conditions CPT
            // we will use the first term to load ACF data from
            if( $show_conditions_section ) {
                $condition_context = 'single-provider';
                $condition_heading_related_name = $short_name; // To what is it related?

                include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
                // $condition_schema .= ',"medicalSpecialty": [';
                $i = 0;
                foreach( $conditions_cpt_query->posts as $condition ) {
                    if ($i > 0) {
                        $condition_schema .= ',
            ';
                    }
                    $condition_schema .= '{
        "@type": "MedicalSpecialty",
        "name": "'. $condition->post_title .'",
        "url":"'. get_the_permalink( $condition->ID ) .'"
        }';
                    $i++;
                } // endforeach;
                // $condition_schema .= '"" ]';
            } // endif;

            // Treatments CPT
            if( $show_treatments_section ) {
                $treatment_context = 'single-provider';
                $treatment_heading_related_name = $short_name; // To what is it related?
                include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
                // $treatment_schema .= ',"medicalSpecialty": [';
                $i = 0;
                foreach( $treatments_cpt_query->posts as $treatment ) {
                    if ($i > 0 || $condition_schema) {
                        $treatment_schema .= ',
            ';
                    }
                    $treatment_schema .= '{
        "@type": "MedicalSpecialty",
        "name": "'. $treatment->post_title .'",
        "url":"'. get_the_permalink( $treatment->ID ) .'"
        }';
                    $i++;
                } // endforeach
                // $treatment_schema .= ']';
            } // endif

        if ( $show_aoe_section && !empty($expertises) ) { ?>
            <section class="uams-module expertise-list bg-auto" id="expertise">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="module-title"><span class="title"><?php echo $short_name_possessive; ?> Areas of Expertise</span></h2>
                            <div class="card-list-container">
                                <div class="card-list card-list-expertise">
                                    <?php foreach( $expertises as $expertise ) {
                                        $id = $expertise;
                                        if ( get_post_status ( $expertise ) == 'publish' && $expertise !== 0 ) {
                                            include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
                                        }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } // endif ?>
        <?php
        if( $show_locations_section && !empty($locations) ): ?>
        <section class="uams-module location-list bg-auto" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Locations Where <?php echo $short_name; ?> Practices</span></h2>
                        <div class="card-list-container location-card-list-container">
                            <div class="card-list">
                            <?php $l = 1;
                                $location_schema = ',
    "address": [';
                            ?>
                            <?php foreach( $locations as $location ):
                                if ( get_post_status ( $location ) == 'publish' ) {

                                    $id = $location;
                                    include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
                                        // Schema data
                                        if ($l > 1){
                                            $location_schema .= ',';
                                        }
                                        $location_schema .= '
                                        {
                                        "@type": "PostalAddress",
                                        "streetAddress": "'. $location_address_1 . ' '. $location_address_2_schema .'",
                                        "addressLocality": "'. $location_city .'",
                                        "addressRegion": "'. $location_state .'",
                                        "postalCode": "'. $location_zip .'",
                                        "telephone": "'. format_phone_dash( $location_phone ) .'"
                                        }
                                        ';
                                    ?>

                                    <?php $l++; ?>
                                <?php } ?>
                            <?php endforeach;
                                $location_schema .= ']
                                ';
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php if ( $show_ratings_section ) : ?>
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
                                                moreUrl:    review.moreUrl
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
        <?php endif; ?>
        <!-- <section class="uams-module news-list bg-auto" id="news">
            <div class="container-fluid"
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title">Latest News for {Name}</span></h2>
                        <div class="card-list-container">
                            <div class="card-list">
                                <div class="card">
                                    <img srcset="https://picsum.photos/434/244?image=1066" src="https://picsum.photos/434/244?image=1066" class="card-img-top" alt="Image description or Story title">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                        </h3>
                                        <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                        <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                    </div>
                                </div>
                                <div class="card">
                                    <img srcset="https://picsum.photos/434/244?image=348" src="https://picsum.photos/434/244?image=348" class="card-img-top" alt="Image description or Story title">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                        </h3>
                                        <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                        <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                    </div>
                                </div>
                                <div class="card">
                                    <img srcset="https://picsum.photos/434/244?image=823" src="https://picsum.photos/434/244?image=823" class="card-img-top" alt="Image description or Story title">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            <span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
                                        </h3>
                                        <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
                                        <a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <?php if (
            $show_appointment_section &&
            (
                $show_clinical_bio_section
                || $show_academic_section
                || $show_podcast_section
                || $show_research_section
                || $show_conditions_section
                || $show_treatments_section
                || $show_aoe_section
                || $show_locations_section
                || $show_ratings_section
            )
        ) {
            $appointment_block_instance = 2;
			include( UAMS_FAD_PATH . '/templates/blocks/appointment-provider.php' );
		} ?>
    </main>
</div>

<?php // Schema Data ?>
<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "Physician",
  "name": "<?php echo $full_name_attr; ?>",
  "url": "<?php echo get_permalink(); ?>",
  "logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>",
  "image": "<?php echo $docphoto; ?>",
  "description": "<?php echo $schema_description; ?>"
  <?php
        if ($condition_schema || $treatment_schema) {
            echo ',';
            echo '"medicalSpecialty": [';
            echo $condition_schema;
            echo $treatment_schema;
            echo ']';
        }
    echo $location_schema; ?>
  <?php if ( $rating_valid ){ ?>
,
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $avg_rating; ?>",
    "ratingCount": "<?php echo $review_count; ?>"
    <?php echo ($comment_count  && '0' != $comment_count) ? ',
"reviewCount": "'. $comment_count . '"' : ''; ?>
   }
  <?php } ?>
}
 </script>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>