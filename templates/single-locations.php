<?php 
/*
 * Template Name: Single Location
 * 
 * Get ACF fields to use for meta data
 * Add description from location short description or full description
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for location descendant item labels
	uamswp_fad_labels_location_descendant();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for area of expertise descendant item labels
	// uamswp_fad_labels_expertise_descendant();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for condition labels
	uamswp_fad_labels_condition();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatment();

// Get system settings for location archive page text
// uamswp_fad_archive_location();

// Get the page ID
$page_id = get_the_ID();

// Get the page title for the location
$page_title = get_the_title(); // Title of the location
$page_title_attr = uamswp_attr_conversion($page_title);
$page_title_phrase = ( get_field('location_prepend_the') ? 'the ' : '' ) . $page_title; // Conditionally prepend "the" to the page title for use in phrases
$page_title_phrase_attr = uamswp_attr_conversion($page_title_phrase);

// Get the page slug for the location
$page_slug = $post->post_name;

// Get system settings for fake subpage or section text elements on Location subsection or profile
uamswp_fad_fpage_text_location();

// Get system settings for jump links (a.k.a. anchor links)
uamswp_fad_labels_jump_links();

$excerpt = get_field('location_short_desc');
$about_loc = get_field('location_about');
if (empty($excerpt)){
	if ($about_loc){
		$excerpt = mb_strimwidth(wp_strip_all_tags($about_loc), 0, 155, '...');
	}
}

// Parent Location 

$location_has_parent = get_field('location_parent');
$location_parent_id = get_field('location_parent_id');
$parent_title = ''; // Eliminate PHP errors
$parent_title_attr = ''; // Eliminate PHP errors
$parent_url = ''; // Eliminate PHP errors
$parent_location = ''; // Eliminate PHP errors
if ($location_has_parent && $location_parent_id) { 
	$parent_location = get_post( $location_parent_id );
}
// Get Post ID for Address & Image fields
if ($parent_location) {
	$post_id = $parent_location->ID;
	$parent_title = $parent_location->post_title;
	$parent_title_attr = uamswp_attr_conversion($parent_title);
	$parent_url = get_permalink( $post_id );
} else {
	$post_id = get_the_ID();
}


// Image values
$override_parent_photo = get_field('location_image_override_parent');
$override_parent_photo_featured = get_field('location_image_override_parent_featured');
$override_parent_photo_wayfinding = get_field('location_image_override_parent_wayfinding');
$override_parent_photo_gallery = get_field('location_image_override_parent_gallery');
// if ($override_parent_photo && $parent_location) { // If child location & override is true
if ($override_parent_photo && $parent_location && $override_parent_photo_wayfinding) {
	$wayfinding_photo = get_field('location_wayfinding_photo');
} else { // Use parent/standard images
	$wayfinding_photo = get_field('location_wayfinding_photo', $post_id);
}
if ($override_parent_photo && $parent_location && $override_parent_photo_gallery) {
	$photo_gallery = get_field('location_photo_gallery');
} else { // Use parent/standard images
	$photo_gallery = get_field('location_photo_gallery', $post_id);
}

$location_images = array();
if ($wayfinding_photo && !empty($wayfinding_photo)) {
	$location_images[] = $wayfinding_photo;
}
if ($photo_gallery && !empty($photo_gallery)) {
	foreach( $photo_gallery as $photo_gallery_image ) {
		$location_images[] = $photo_gallery_image;
	}
}
$location_images_count = count($location_images);

// Set image for schema
if ($override_parent_photo && $parent_location && $override_parent_photo_featured) { // If child location & override is true
	$featured_image = get_post_thumbnail_id();
} else { // Use parent/standard images
	$featured_image = get_post_thumbnail_id($post_id);
}

$schema_image = '';
if ($featured_image) {
	$schema_image = $featured_image;
} elseif ($location_images) {
	$schema_image = $location_images[0];
}
if ( function_exists( 'fly_add_image_size' ) && !empty($schema_image) ) {
	$locationphoto = image_sizer($schema_image, 640, 480, 'center', 'center');
} else {
	$locationphoto = wp_get_attachment_image_src($schema_image, 'large');
}

// Set telemedicine values
// Original Set
// $telemedicine_query = get_field('field_location_telemed_query'); // Is there telemedicine?
// $telemedicine_patients = get_field('field_location_telemed_patients'); // New patients, existing or both?
// $telemedicine_hours247 = get_field('field_location_telemed_24_7'); // typically 24/7?
// $telemedicine_hours = get_field('location_telemed_hours'); // telemedicine hours repeater
// $telemedicine_modified = get_field('field_location_telemed_modified_hours_query'); // Are there modified hours for telemedicine?
// $telemedicine_modified_reason = get_field('field_location_telemed_modified_hours_reason'); // Why are there modified hours for telemedicine?
// $telemedicine_modified_start = get_field('field_location_telemed_modified_hours_start_date'); // When do the modified telemedicine hours start?
// $telemedicine_modified_end = get_field('field_location_telemed_modified_hours_end'); // Do we know when the modified telemedicine hours end?
// $telemedicine_modified_end_date = get_field('field_location_telemed_modified_hours_end_date'); // When do the modified telemedicine hours end?
// $telemedicine_modified_hours = get_field('field_location_telemed_modified_hours_group'); // modified telemedicine hours repeater
// Hours Grouping
$location_hours_group = get_field('location_hours_group');

$telemedicine_query = $location_hours_group['location_telemed_query']; // Is there telemedicine?
$telemedicine_patients = $location_hours_group['location_telemed_patients']; // New patients, existing or both?
$telemedicine_hours247 = $location_hours_group['location_telemed_24_7']; // typically 24/7?
$telemedicine_hours = $location_hours_group['location_telemed_hours']; // telemedicine hours repeater
$telemedicine_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
$telemedicine_modified_reason = $location_hours_group['location_telemed_modified_hours_reason']; // Why are there modified hours for telemedicine?
$telemedicine_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
$telemedicine_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
$telemedicine_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?
$telemedicine_modified_hours247 = $location_hours_group['location_telemed_modified_hours_24_7'];
// $telemedicine_modified_hours = $location_hours_group['location_telemed_modified_hours_group']; // modified telemedicine hours repeater
$telemedicine_info = get_field('location_telemed_descr_system', 'option'); // System-wide information about telemedicine at locations

$afterhours_system = get_field('location_afterhours_descr_system', 'option'); // System-wide information about telemedicine at locations
$afterhours_system = ( isset($afterhours_system) && !empty($afterhours_system) ) ? $afterhours_system : '<p>If you are in need of urgent or emergency care, call 911 or go to your nearest emergency department at your local hospital.</p>'; // System-wide information about telemedicine at locations

// Set alert values

$location_alert_title_sys = get_field('location_alert_heading_system', 'option');
$location_alert_text_sys = get_field('location_alert_body_system', 'option');
$location_alert_color_sys = get_field('location_alert_color_system', 'option');

$location_alert_suppress = get_field('location_alert_suppress');
$location_alert_modification = get_field('location_alert_modification');

$location_alert_title_local = get_field('location_alert_heading');
$location_alert_text_local = get_field('location_alert_body');
$location_alert_color_local = get_field('location_alert_color');

$location_alert_title = $location_alert_title_sys;

if ( !empty($location_alert_title_local) && $location_alert_modification == 'override' ) {
	$location_alert_title = $location_alert_title_local;
}

$location_alert_color = $location_alert_color_sys;

if ( $location_alert_modification == 'override' && $location_alert_color_local != 'inherit' ) {
	$location_alert_color = $location_alert_color_local;
}

$location_alert_text = $location_alert_text_sys;

if ( $location_alert_modification == 'override' && !empty($location_alert_text_local) ) {
	$location_alert_text = $location_alert_text_local;
} elseif ( $location_alert_modification == 'prepend' && !empty($location_alert_text_local) ) {
	$location_alert_text = $location_alert_text_local . $location_alert_text_sys;
} elseif ( $location_alert_modification == 'append' && !empty($location_alert_text_local) ) {
	$location_alert_text = $location_alert_text_sys . $location_alert_text_local;
}

if ( $location_alert_modification == 'suppress' ) {
	$location_alert_suppress = true;
	$location_alert_title = '';
	$location_alert_text = '';
}

$location_closing = get_field('location_closing'); // true or false
$location_closing_date = '';
$location_closing_date_past = false;
$location_closing_length = '';
$location_reopen_known = '';
$location_reopen_date = '';
$location_reopen_date_past = false;
$location_closing_info = '';
$location_closing_telemed = '';
$location_closing_display = false;

if ( $location_closing ) {
	$location_closing_date = get_field('location_closing_date'); // F j, Y
	if (new DateTime() >= new DateTime($location_closing_date)) {
		$location_closing_date_past = true;
	}
	$location_closing_length = get_field('location_closing_length');
	$location_reopen_known = get_field('location_reopen_known');
	$location_reopen_date = get_field('location_reopen_date'); // F j, Y
	if (new DateTime() >= new DateTime($location_reopen_date)) {
		$location_reopen_date_past = true;
	}
	$location_closing_info = get_field('location_closing_info');
	if ($location_closing_length == 'temporary') {
		$location_closing_telemed = get_field('location_closing_telemed'); // Will telemedicine be available during closure?
	}
	if (
		$location_closing_length == 'permanent'
		|| ($location_closing_length == 'temporary' && !$location_reopen_date_past)
		) {
		$location_closing_display = true;
	}
}

// Set prescription values

$prescription_query = get_field('location_prescription_query'); // Display prescription information
$prescription_clinic_sys = get_field('location_prescription_clinic_system', 'option'); // Text from Find-a-Doc settings (a.k.a. system) for calling clinic
$prescription_pharm_sys = get_field('location_prescription_pharm_system', 'option'); // Text from Find-a-Doc settings (a.k.a. system) for calling pharmacy
$prescription = ''; // Eliminate PHP errors

if ($prescription_query) {
	$prescription_info_type = get_field('location_prescription_type'); // Which preset or custom text?
	if ( $prescription_info_type == 'clinic' ) {
		$prescription = $prescription_clinic_sys; // Text from location (a.k.a. local)
	} elseif ( $prescription_info_type == 'pharm' ) {
		$prescription = $prescription_pharm_sys; // Text from location (a.k.a. local)
	} else {
		$prescription = get_field('location_prescription'); // Text from location (a.k.a. local)
	}
	if ($prescription_query && !$prescription) { // If no prescription text
		$prescription_query = false; // Deactivate prescription section
	}
}

// Conditionally suppress sections based on Find-a-Doc Settings configuration
$regions = get_field('location_region',$post->ID);
$service_lines = get_field('location_service_line',$post->ID);
uamswp_fad_ontology_hide();

// Override theme's method of defining the meta description
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// Override theme's method of defining the meta page title
$location_city = get_field('location_city', $post_id); // Get the location's city
$location_city_attr = uamswp_attr_conversion($location_city);
$meta_title_enhanced_addition = $location_city_attr; // Word or phrase to inject into base meta title to form enhanced meta title
if ( $parent_title_attr ) {
	$meta_title_enhanced_addition = $parent_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title
	$meta_title_enhanced_x2_addition = $location_city_attr; // Second word or phrase to inject into base meta title to form enhanced meta title level 2
} else {
	$meta_title_enhanced_addition = $location_city_attr; // Word or phrase to inject into base meta title to form enhanced meta title
}
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

get_header();

while ( have_posts() ) : the_post(); ?>
<?php 
	$map = get_field('location_map', $post_id );
	$location_address_1 = get_field('location_address_1', $post_id );
	$location_building = get_field('location_building', $post_id );
	if ($location_building) {
		$building = get_term($location_building, "building");
		$building_slug = $building->slug;
		$building_name = $building->name;
	}
	$location_floor = get_field_object('location_building_floor', $post_id );
		$location_floor_value = '';
		$location_floor_label = '';
		if ( $location_floor ) {
			$location_floor_value = $location_floor['value'];
			$location_floor_label = $location_floor['choices'][ $location_floor_value ];
		}
	$location_suite = get_field('location_suite', $post_id );
	$location_address_2 =
		( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
		. ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
		. ( $location_suite ? $location_suite : '' );
	$location_address_2_schema =
		( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
		. ( $location_floor && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
		. ( $location_suite ? $location_suite : '' );

	$location_address_2_deprecated = get_field('location_address_2', $post_id );
	if (!$location_address_2) {
		$location_address_2 = $location_address_2_deprecated;
		$location_address_2_schema = $location_address_2_deprecated;
	}

	$location_city = get_field('location_city', $post_id);
	$location_state = get_field('location_state', $post_id);
	$location_zip = get_field('location_zip', $post_id);
	$location_web_name = get_field('location_web_name');
	$location_url = get_field('location_url');

	// Set logic for displaying jump links and sections
	$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
	$jump_link_count = 0;

		// Check if Location Alert section should be displayed
		if (
			(
				($location_closing && !$location_closing_date_past) // If location closing is toggled, but closing start date is future
				||
				($location_closing && $location_reopen_date_past) // If location closing is toggled, but reopening date is past (or is TBD)
				||
				!$location_closing // If location closing is not toggled
			)
			&& 
			($location_alert_title || $location_alert_text) // If location title or description has value
		 ) {
			$location_alert_section_show = true;
			$jump_link_count++;
		} else {
			$location_alert_section_show = false;
		}

		// Check if Closing Information section should be displayed
		if ( $location_closing_display && !empty($location_closing_info) ) {
			$closing_section_show = true;
			$jump_link_count++;
		} else {
			$closing_section_show = false;
		}

		// Check if About section should be displayed
		$location_about = get_field('location_about');
		$location_affiliation = get_field('location_affiliation');
		$location_youtube_link = get_field('location_youtube_link');
		$about_section_title = '';
		$about_section_title_short = '';
		$about_section_submenu = false;
		$about_section_label = 'Jump to the section of this page with the ' . strtolower($location_single_name) . ' description';

		if ( $location_about || $location_affiliation || $prescription ) {
			$about_section_show = true;
			$jump_link_count++;
			if ( $location_about || $location_youtube_link || ( !$location_about && $location_affiliation && $prescription ) ) {
				$about_section_title = 'About ' . $page_title_phrase;
				$about_section_title_short = 'About';

				if ($location_affiliation || $prescription) {
					$about_section_submenu = true;
				}
			} elseif ( $location_affiliation ) {
				$about_section_title = 'Affiliation';
				$about_section_title_short = $about_section_title;
				$about_section_label = 'Jump to the section of this page about ' . $about_section_title;
			} elseif ( $prescription ) {
				$about_section_title = 'Prescription Information';
				$about_section_title_short = $about_section_title;
				$about_section_label = 'Jump to the section of this page about ' . $about_section_title;
			}
		} else {
			$about_section_show = false;
		}

		// Check if Parking and Directions section should be displayed
		$location_parking = get_field('location_parking', $post_id);
		$location_direction = get_field('location_direction', $post_id);
		$parking_map = get_field('location_parking_map', $post_id);

		if ( $location_parking || $location_direction || $parking_map ) {
			$parking_section_show = true;
			$jump_link_count++;
		} else {
			$parking_section_show = false;
		}

		// Check if Appointment Information section should be displayed
		$location_appointment = get_field('location_appointment');
		$location_appointment_bring = get_field('location_appointment_bring');
		$location_appointment_expect = get_field('location_appointment_expect');

		if ( $location_appointment || $location_appointment_bring || $location_appointment_expect ) {
			$appointment_section_show = true;
			$jump_link_count++;
		} else {
			$appointment_section_show = false;
		}

		// Check if Appointment Scheduling section should be displayed
		$mychart_scheduling_query_system = get_field('mychart_scheduling_query_system', 'option');
		$location_scheduling_query = get_field('location_scheduling_query');
		$location_scheduling_options = get_field('location_scheduling_options');

			// Set main appointment scheduling section title
			$location_scheduling_title_default = 'Schedule an Appointment Online'; // Default value for appointment section title
			$location_scheduling_title_general = get_field('location_scheduling_title_general'); // Get input for general appointment section title
			$location_scheduling_title = ( isset($location_scheduling_title_general) && !empty($location_scheduling_title_general) ) ? $location_scheduling_title_general : $location_scheduling_title_default; // Set main title from general title input. If general title value is empty, set to default value.

			// Set main appointment scheduling section intro
			$location_scheduling_intro_default = 'Use your UAMS Health MyChart account to schedule an appointment at this ' . strtolower($location_single_name) . '. If you are not a MyChart user, you can continue as a guest.'; // Default value for appointment section intro
			$location_scheduling_intro_general = get_field('location_scheduling_intro_general'); // Get input for general appointment section intro
			$location_scheduling_intro = ( isset($location_scheduling_intro_general) && !empty($location_scheduling_intro_general) ) ? $location_scheduling_intro_general : $location_scheduling_intro_default; // Set main intro from general intro input. If general intro value is empty, set to default value.

			// Change main appointment scheduling section title and intro if only one scheduling widget
			if ($location_scheduling_query && (count((array)$location_scheduling_options) < 2)) {
				$row = $location_scheduling_options[0];
				$location_scheduling_item_title_main = $row['location_scheduling_title']; // Get input for specific appointment section standalone title
				$location_scheduling_title = ( isset($location_scheduling_item_title_main) && !empty($location_scheduling_item_title_main) ) ? $location_scheduling_item_title_main : $location_scheduling_title; // If input for specific appointment section title exists, use that. Otherwise, keep original value.
				$location_scheduling_item_intro_main = $row['location_scheduling_intro']; // Get input for specific appointment section standalone intro
				$location_scheduling_intro = ( isset($location_scheduling_item_intro_main) && !empty($location_scheduling_item_intro_main) ) ? $location_scheduling_item_intro_main : $location_scheduling_intro; // If input for specific appointment section intro exists, use that. Otherwise, keep original value.
			}


		$mychart_scheduling_domain = get_field('mychart_scheduling_domain', 'option');
		$mychart_scheduling_instance = get_field('mychart_scheduling_instance', 'option');
		$mychart_scheduling_linksource = get_field('mychart_scheduling_linksource', 'option');
		$mychart_scheduling_linksource = ( isset($mychart_scheduling_linksource) && !empty($mychart_scheduling_linksource) ) ? $mychart_scheduling_linksource : 'uamshealth.com';

		if ( $mychart_scheduling_query_system && $location_scheduling_query ) {
			$mychart_scheduling_section_show = true;
		} else {
			$mychart_scheduling_section_show = false;
		}

		// Check if Telemedicine Information section should be displayed
		if ( $telemedicine_query ) {
			$telemedicine_section_show = true;
			$jump_link_count++;
		} else {
			$telemedicine_section_show = false;
		}

		// Check if Portal Information section should be displayed
		$location_portal = get_field('location_portal');

		if ( $location_portal ) {
			$portal = get_term($location_portal, "portal");
			$portal_slug = $portal->slug;
			$portal_name = $portal->name;
			$portal_name_attr = uamswp_attr_conversion($portal_name);
			$portal_content = get_field('portal_content', $portal);
			$portal_link = get_field('portal_url', $portal);
			if ($portal_link) {
				$portal_url = $portal_link['url'];
				$portal_link_title = $portal_link['title'];
			}
		}
		if ($portal && $portal_slug !== "_none") {
			$portal_section_show = true;
			$jump_link_count++;
		} else {
			$portal_section_show = false;
		}

		// Query for whether related providers content section should be displayed on ontology pages/subsections
		$providers = get_field('physician_locations');
		uamswp_fad_provider_query();

		// Query for whether related descendant locations content section should be displayed on a page
		$current_id = get_the_ID();
		$location_descendants = get_pages(
			array(
				'child_of' => $current_id,
				'post_type' => 'location'
			)
		);
		uamswp_fad_location_descendant_query();
		$location_query = $location_descendant_query;
		$location_section_show = $location_descendant_section_show;
		$location_ids = $location_descendant_ids;
		$location_count = $location_descendant_count;
		$location_valid = $location_descendant_valid;

		// Query for whether related areas of expertise content section should be displayed on a page
		$expertises = get_field('location_expertise');
		uamswp_fad_expertise_query();

		// Query for whether related clinical resources content section should be displayed on ontology pages/subsections
		$clinical_resources = get_field('location_clinical_resources');
		uamswp_fad_clinical_resource_query();

		// Query for whether related conditions content section should be displayed on ontology pages/subsections
		// $conditions = get_field('location_conditions');
		$conditions_cpt = get_field('location_conditions_cpt');
		uamswp_fad_condition_query();

		// Query for whether related treatments content section should be displayed on ontology pages/subsections
		$treatments_cpt = get_field('location_treatments_cpt');
		uamswp_fad_treatment_query();

		// Check if Jump Links section should be displayed
		if ( $jump_link_count >= $jump_link_count_min ) {
			$jump_links_section_show = true;
		} else {
			$jump_links_section_show = false;
		}
?>
<div class="content-sidebar-wrap">
<main class="location-item" id="genesis-content">
	<section class="container-fluid p-0 p-md-10 location-info bg-white">
		<div class="row mx-0 mx-md-n8">
			<div class="col-12 col-md text">
				<div class="content-width">
					<h1 class="page-title"><?php echo $page_title; ?>
					<?php if ($parent_location) { ?>
					<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
					<?php } // endif ?>
					</h1>
					<?php if ($location_closing_display) { ?>
						<div class="alert alert-warning" role="alert">
							<p>
							<?php if ($location_closing_date_past) { ?>
								This <?php echo strtolower($location_single_name); ?> is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
							<?php } else { ?>
								This <?php echo strtolower($location_single_name); ?> will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
							<?php } // endif
							if (
								$location_closing_length == 'temporary' 
								&& $location_reopen_known == 'date' 
								&& !empty($location_reopen_date)
								&& (new DateTime($location_reopen_date) >= new DateTime($location_closing_date))
								) { ?>
								It is scheduled to reopen on <?php echo $location_reopen_date; ?>.
							<?php } elseif (
								$location_closing_length == 'temporary' 
								&& $location_reopen_known == 'tbd' 
							) { ?>
								It will remain closed until further notice.
							<?php } // endif
							if (!empty($location_closing_info)) { ?>
								<a href="#closing-info" class="alert-link no-break" aria-label="Learn more information about the closure of this <?php echo strtolower($location_single_name_attr); ?>">Learn more</a>.
							<?php } // endif ?>
							</p>
							<?php if ($location_closing_telemed) { ?>
								<p>Telemedicine will still be available. <a href="#telemedicine-info" class="alert-link no-break" aria-label="Learn more information about telemedicine at this <?php echo strtolower($location_single_name_attr); ?>">Learn more</a>.</p>
							<?php } ?>
						</div>
					<?php } // endif ?>
					<h2 class="sr-only">Address</h2>
					<p><?php echo $location_address_1; ?><br/>
					<?php echo ( $location_address_2 ? $location_address_2 . '<br/>' : ( $location_address_2_deprecated ? $location_address_2_deprecated . '<br/>' : '')); ?>
					<?php echo $location_city; ?>, <?php echo $location_state; ?> <?php echo $location_zip; ?></p>
						<div class="btn-container">
							<div class="inner-container">
								<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>
								<?php if ($parking_section_show) { ?>
									<a class="btn btn-outline-primary" href="#parking-info" aria-label="Parking instructions for <?php echo $page_title_phrase; ?>" data-typetitle="Parking instructions for the clinic">Parking Instructions</a>
								<?php } // endif $parking_section_show ?>

							</div>
						</div>
						<?php if( $location_web_name && $location_url ){ ?>
							<p><a class="btn btn-secondary" href="<?php echo $location_url['url']; ?>" target="_blank" data-categorytitle="External Link"><?php echo $location_web_name; ?> <span class="far fa-external-link-alt"></span></span></a></p>
					<?php }
						// Schema data
						$location_schema = '"address": {
						"@type": "PostalAddress",
						"streetAddress": "'. $location_address_1 . ' '. $location_address_2_schema .'",
						"addressLocality": "'. $location_city .'",
						"addressRegion": "'. $location_state .'",
						"postalCode": "'. $location_zip .'"
						},
						';
						$phone_schema = '';
					?>
					<h2>Contact Information</h2>
					<?php 
					// Phone values
					$phone_output_id = $id;
					$phone_output = 'location_profile';
					include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );

					// Hours values
					$hoursvary = $location_hours_group['location_hours_variable'];
					$hoursvary_info = $location_hours_group['location_hours_variable_info'];
					$hours247 = $location_hours_group['location_24_7'];
					$modified = $location_hours_group['location_modified_hours'];
					$modified_reason = $location_hours_group['location_modified_hours_reason'];
					$modified_start = $location_hours_group['location_modified_hours_start_date'];
					$modified_end = $location_hours_group['location_modified_hours_end'];
					$modified_end_date = $location_hours_group['location_modified_hours_end_date'];
					$modified_hours = $location_hours_group['location_modified_hours_group'];
					$modified_hours_schema ='';
					$modified_text = '';
					$active_start = '';
					$active_end = '';

					if ( $hoursvary ) {
						echo '<h2>Hours Vary</h2>';
						echo $hoursvary_info;
					} else {
						if ($modified) : 
						?>
						<?php 

							$modified_day = ''; // Previous Day
							$modified_comment = ''; // Comment on previous day
							// $modified_hours_schema .= 
							$i = 1;

							$today = strtotime("today");
							$today_30 = strtotime("+30 days");

							if( strtotime($modified_start) <= $today_30 && ( strtotime($modified_end_date) >= $today || !$modified_end ) ){
								$modified_text .= $modified_reason;
								$modified_text .= '<p class="small font-italic">These modified hours start on ' . $modified_start . ', ';
								$modified_text .= $modified_end && $modified_end_date ? 'and are scheduled to end after ' . $modified_end_date . '.' : 'and will remain in effect until further notice.';
								$modified_text .= '</p>';

								if ( $modified_hours ) {

									foreach ($modified_hours as $modified_hour) {

										$modified_title = $modified_hour['location_modified_hours_title'];
										$modified_text .= $modified_title ? '<h3 class="h4">'.$modified_title.'</h3>' : '';
										$modified_info = $modified_hour['location_modified_hours_information'];
										$modified_text .= $modified_info ? $modified_info : '';
										$modified_times = $modified_hour['location_modified_hours_times'];
										$modified_hours247 = $modified_hour['location_modified_hours_24_7'];

										if ($active_start > strtotime($modified_start) || '' == $active_start) {
											$active_start = strtotime($modified_start);
										}
										if ( $active_end <= strtotime($modified_end_date) || !$modified_end ) {
											if (!$modified_end) {
												$active_end = 'TBD';
											} else {
												$active_end = strtotime($modified_end_date);
											}
										}
										if ($modified_hours247):
											$modified_text .= '<strong>Open 24/7</strong>';
											$modified_hours_schema = '"dayOfWeek": [
												"Monday",
												"Tuesday",
												"Wednesday",
												"Thursday",
												"Friday",
												"Saturday",
												"Sunday"
											],
											"opens": "00:00",
											"closes": "23:59"	
											';
										else :
											if (is_array($modified_times) || is_object($modified_times)) {
												$modified_text .= '<dl class="hours">';
												foreach ( $modified_times as $modified_time ) {

													$modified_text .= $modified_day !== $modified_time['location_modified_hours_day'] ? '<dt>'. $modified_time['location_modified_hours_day'] .'</dt> ' : '';
													$modified_text .= '<dd>';

													if (1 != $i) {
														$modified_hours_schema .= '},
														';
													}

													$modified_hours_schema .= '{
														';
													$modified_hours_schema .= '"@type": "OpeningHoursSpecification",
													';
													$modified_hours_schema .= '"validFrom": "'. date("Y-m-d", strtotime($modified_start)) .'",
													';
													$modified_hours_schema .= $modified_end && $modified_end_date ? '"validThrough": "'. date("Y-m-d", strtotime($modified_end_date)) .'",
														' : '';

													if ('Mon - Fri' == $modified_time['location_modified_hours_day'] && !$modified_time['location_modified_hours_closed']) {
														$modified_hours_schema .= '"dayOfWeek": [
															"Monday",
															"Tuesday",
															"Wednesday",
															"Thursday",
															"Friday"
														],
														';
													} else {
														$modified_hours_schema .= '"dayOfWeek": "'. $modified_time['location_modified_hours_day'] .'",
														'; //substr($modified_time['location_modified_hours_day'], 0, 2);
													}

													if ( $modified_time['location_modified_hours_closed'] ) {
														$modified_text .= 'Closed ';
														$modified_hours_schema .= '"opens": "00:00",
														"closes": "00:00"
														';
													} else {
														$modified_text .= ( ( $modified_time['location_modified_hours_open'] && '00:00:00' != $modified_time['location_modified_hours_open'] ) ? '' . ap_time_span( strtotime($modified_time['location_modified_hours_open']), strtotime($modified_time['location_modified_hours_close']) ). '' : '' );
														$modified_hours_schema .= '"opens": "' . date('H:i', strtotime($modified_time['location_modified_hours_open'])) . '"';
														$modified_hours_schema .= ',
														"closes": "' . date('H:i', strtotime($modified_time['location_modified_hours_close'])) . '"
														';
													}
													if ( $modified_time['location_modified_hours_comment'] ) {
														$modified_text .= ' <br /><span class="subtitle">' .$modified_time['location_modified_hours_comment'] . '</span>';
														$modified_comment = $modified_time['location_modified_hours_comment'];
													} else {
														$modified_comment = '';
													}
													$modified_text .= '</dd>';
													$modified_day = $modified_time['location_modified_hours_day']; // Reset the day
													$i++;

												} // endforeach
												$modified_text .= '</dl>';
											} // End if (array)
										endif;
									}
								}
							}

							echo $modified_text ? '<h2>Modified Hours</h2>' . $modified_text: '';

						endif; // End Modified Hours
						if ('' != $modified_hours_schema) {
							$modified_hours_schema ='"openingHoursSpecification": [
								' . $modified_hours_schema . '}
								],
								';
						}
						if (($active_start != '' && $active_start <= $today) && ( $active_end > $today_30 || $active_end == 'TBD' ) ) {
							// Do Nothing;
							// Future Option
						} else {
							$hours = $location_hours_group['location_hours'];
							$hours_schema = '';
							if ( $hours247 || $hours[0]['day'] ) : ?>
							<h2><?php echo $modified_text ? 'Typical ' : ''; ?>Hours</h2>
							<?php
								if ($hours247):
									echo '<strong>Open 24/7</strong>';
									$hours_schema = '"openingHours": "Mo-Su",';
								else :
									echo '<dl class="hours">';
									if( $hours ) {
										$hours_text = '';
										$day = ''; // Previous Day
										$comment = ''; // Comment on previous day
										$hours_schema = '"openingHours": [';
										$i = 1;
										foreach ($hours as $hour) :
											// if( $day !== $hour['day'] || $comment ) { // change for single day
												$hours_text .= $day !== $hour['day'] ? '<dt>'. $hour['day'] .'</dt> ' : '';
												$hours_text .= '<dd>';
												if (!$hour['closed']) {
													if (1 != $i) {
														$hours_schema .= ', ';
													}
													$hours_schema .= '"';
												}
												if ('Mon - Fri' == $hour['day'] && !$hour['closed']) {
													$hours_schema .= 'Mo-Fr';
												} elseif ( !$hour['closed'] ) {
													$hours_schema .= substr($hour['day'], 0, 2);
												}
											// } else { // Changed for single day
											// 	$hours_text .= ', ';
											// }
											if ( $hour['closed'] ) {
												$hours_text .= 'Closed ';
											} else {
												$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] ) ? '' . ap_time_span( strtotime($hour['open']), strtotime($hour['close']) ) . '' : '' );
												$hours_schema .= ' ' . date('H:i', strtotime($hour['open'])) . '-' . date('H:i', strtotime($hour['close']));
											}
											if ( $hour['comment'] ) {
												$hours_text .= ' <br /><span class="subtitle">' .$hour['comment'] . '</span>';
												$comment = $hour['comment'];
											} else {
												$comment = '';
											}
											// if( $day !== $hour['day'] && $comment ) { // change for single day
												$hours_text .= '</dd>';
											// }
											$day = $hour['day']; // Reset the day
											if (!$hour['closed']) {
												$hours_schema .= '"';
											}
											if (!$hour['closed']) {
											$i++;
											}
										endforeach;
										echo $hours_text;
										$hours_schema .= '],';
									} else {
										echo '<dt>No information</dt>';
									}
									echo '</dl>';
								endif;
							$holidayhours = get_field('location_holiday_hours');
							if ($holidayhours):
								/**
								 * Sort by date
								 * if current date is before date & within 30 days
								 * Display results
								 */

								$order = array();
								// populate order
								foreach( $holidayhours as $i => $row ) {
									$order[ $i ] = $row['date'];
								}

								// multisort
								array_multisort( $order, SORT_ASC, $holidayhours );

								$i = 0;
								foreach( $holidayhours as $row ):
									$holidayDate = $row['date']; // Text
									$holidayDateTime = DateTime::createFromFormat('m/d/Y', $holidayDate); // Date for evaluation
									$dateNow = new DateTime("now", new DateTimeZone('America/Chicago') );
									if (($dateNow < $holidayDateTime) && ($holidayDateTime->diff($dateNow)->days < 30)) {
										if ( 0 == $i ) {
											echo '<h3>Upcoming Holiday Hours</h3>';
											echo '<dl class="hours">';
											$i++;
										}
										echo '<dt>'. $row['label'] . '<br />' . $holidayDate . '<br/>';
										echo '</dt>' . '<dd>';
										if ( $row['closed'] ) {
											echo $row['closed'] ? 'Closed</dd>': '';
										} else {
											echo ( ( $hour['open'] && '00:00:00' != $row['open'] ) ? '' . ap_time_span( strtotime($row['open']), strtotime($row['close']) ) . ' ' : '' );
										}
									}
								endforeach;
								if ( 0 < $i ) {
									echo '</dl>';
								}
							endif; ?>


						<?php endif;
						} // endif
					} // endif ( if $hoursvary )
					if ($location_hours_group['location_after_hours'] && !$location_hours_group['location_24_7']) { ?>
						<h2>After Hours</h2>
						<?php echo $location_hours_group['location_after_hours']; ?>
					<?php } elseif (!$location_hours_group['location_24_7']) { ?>
						<h2>After Hours</h2>
						<?php echo $afterhours_system; ?>
					<?php } // endif (after hours) ?>
				</div>
			</div>
			<?php if ( $location_images_count ) { ?>
			<div class="col-12 col-md image">
				<div class="content-width">
					<?php if ( $location_images_count == 1 ) { ?>
						<picture>
							<?php if ( function_exists( 'fly_add_image_size' ) && !empty($location_images[0]) ) { ?>
								<source srcset="<?php echo image_sizer($location_images[0], 630, 473, 'center', 'center'); ?>"
									media="(min-width: 1350px)">
								<source srcset="<?php echo image_sizer($location_images[0], 572, 429, 'center', 'center'); ?>"
									media="(min-width: 992px)">
								<source srcset="<?php echo image_sizer($location_images[0], 992, 558, 'center', 'center'); ?>"
									media="(min-width: 768px)">
								<source srcset="<?php echo image_sizer($location_images[0], 768, 432, 'center', 'center'); ?>"
									media="(min-width: 576px)">
								<source srcset="<?php echo image_sizer($location_images[0], 576, 324, 'center', 'center'); ?>"
									media="(min-width: 1px)">
								<img src="<?php echo image_sizer($location_images[0], 630, 473, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images[0], '_wp_attachment_image_alt', true ); ?>" class="single-image" />
							<?php } else { ?>
								<img src="<?php echo wp_get_attachment_image_url($location_images[0], 'large'); ?>" class="single-image">
							<?php } //endif ?>
						</picture>
					<?php } else { ?>
						<div class="carousel slide carousel-thumbnails" id="location-info-carousel" data-interval="false" data-ride="carousel">
							<div class="carousel-inner">
								<?php 
								$location_carousel_slide = 1;
								foreach( $location_images as $location_images_item ) { ?>
									<div class="carousel-item<?php echo ($location_carousel_slide == 1) ? ' active' : '' ?>">
										<picture>
											<?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
												<source srcset="<?php echo image_sizer($location_images_item, 630, 473, 'center', 'center'); ?>"
													media="(min-width: 1350px)">
												<source srcset="<?php echo image_sizer($location_images_item, 572, 429, 'center', 'center'); ?>"
													media="(min-width: 992px)">
												<source srcset="<?php echo image_sizer($location_images_item, 992, 558, 'center', 'center'); ?>"
													media="(min-width: 768px)">
												<source srcset="<?php echo image_sizer($location_images_item, 768, 432, 'center', 'center'); ?>"
													media="(min-width: 576px)">
												<source srcset="<?php echo image_sizer($location_images_item, 576, 324, 'center', 'center'); ?>"
													media="(min-width: 1px)">
												<img src="<?php echo image_sizer($location_images_item, 630, 473, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images_item, '_wp_attachment_image_alt', true ); ?>" />
											<?php } else { ?>
												<img src="<?php echo wp_get_attachment_image_url($location_images_item, 'large'); ?>">
											<?php } //endif ?>
										</picture>
									</div>
									<?php
										$location_carousel_slide++;
									} // endforeach ?>
							</div>
							<a class="carousel-control-prev" href="#location-info-carousel" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#location-info-carousel" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
							<ol class="carousel-indicators">
								<?php for ($i = 0; $i < $location_images_count; $i++) { ?>
									<li data-target="#location-info-carousel" data-slide-to="<?php echo $i; ?>" <?php echo (0 == $i ? 'class="active"' : ''); ?>>
										<?php if ( function_exists( 'fly_add_image_size' )) { ?>
											<img src="<?php echo image_sizer($location_images[$i], 60, 45, 'center', 'center'); ?>" alt="<?php echo get_post_meta( $location_images[$i], '_wp_attachment_image_alt', true ); ?>" />
										<?php } else { ?>
											<img src="<?php echo wp_get_attachment_image_url($location_images[$i], 'small'); ?>" alt="<?php echo get_post_meta( $location_images[$i], '_wp_attachment_image_alt', true ); ?>">
										<?php } //endif ?>
									</li>
								<?php } ?>
							</ol>
						</div>
					<?php } //endif ?>
				</div>
			</div>
			<?php } //endif ?>
		</div>
	</section>

	<?php // Begin Jump Links Section
	if ( $jump_links_section_show ) { ?>
		<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
			<h2><?php echo $fad_jump_links_title; ?></h2>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
				<ul class="nav navbar-nav">
					<?php if ( $location_alert_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#location-alert" title="Jump to the section of this page with the alert regarding this <?php echo strtolower($location_single_name_attr); ?>"><?php echo $location_alert_title ? $location_alert_title : 'Alert'; ?></a>
						</li>
					<?php } ?>
					<?php if ( $closing_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#closing-info" title="Jump to the section of this page with the closing information">Closing Information</a>
						</li>
					<?php } ?>
					<?php if ( $about_section_show ) { ?>
						<li class="nav-item<?php echo $about_section_submenu ? ' dropdown' : '' ?>">
							<a class="nav-link" href="#description" title="<?php echo $about_section_label; ?>"><?php echo $about_section_title_short; ?></a>
							<?php if ( $about_section_submenu ) { ?>
								<ul class="dropdown-menu">
								<?php if ( $location_affiliation ) { ?>
									<li class="nav-item">
										<a class="nav-link" href="#affiliation" title="Jump to the section of this page about Affiliation">Affiliation</a>
									</li>
								<?php }
								if ( $prescription ) { ?>
									<li class="Prescription Information">
										<a class="nav-link" href="#prescription-info" title="Jump to the section of this page about Prescription Information">Prescription Information</a>
									</li>
								<?php } ?>
								</ul>
							<?php }?>
						</li>
					<?php } ?>
					<?php if ( $parking_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#parking-info" title="Jump to the section of this page about Parking Information">Parking Information</a>
						</li>
					<?php } ?>
					<?php if ( $appointment_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#appointment-info" title="Jump to the section of this page about Appointment Information">Appointment Information</a>
						</li>
					<?php } ?>
					<?php if ( $mychart_scheduling_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#scheduling" title="Jump to the section of this page about scheduling an appointment in MyChart"><?php echo $location_scheduling_title; ?></a>
						</li>
					<?php } ?>
					<?php if ( $telemedicine_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#telemedicine-info" title="Jump to the section of this page about Telemedicine Information">Telemedicine</a>
						</li>
					<?php } ?>
					<?php if ( $portal_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#portal-info" title="Jump to the section of this page about the Patient Portal">Patient Portal</a>
						</li>
					<?php } ?>
					<?php if ( $provider_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#providers" title="Jump to the section of this page about <?php echo $provider_plural_name_attr; ?>"><?php echo $provider_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $condition_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#conditions" title="Jump to the section of this page about <?php echo $condition_plural_name_attr; ?>"><?php echo $condition_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $treatment_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#treatments" title="Jump to the section of this page about <?php echo strtolower($treatment_plural_name_attr); ?>"><?php echo $treatment_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $expertise_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#expertise" title="Jump to the section of this page about <?php echo $expertise_plural_name_attr; ?>"><?php echo $expertise_plural_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $location_descendant_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#sub-clinics" title="Jump to the section of this page about <?php echo strtolower($location_descendant_plural_name_attr); ?> within this <?php echo strtolower($location_single_name_attr); ?>"><?php echo $location_descendant_plural_name; ?> Within This <?php echo $location_single_name; ?></a>
						</li>
					<?php } ?>
					<?php if ( $clinical_resource_section_show ) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#related-resources" title="Jump to the section of this page about <?php echo $clinical_resource_plural_name_attr; ?>"><?php echo $clinical_resource_plural_name; ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	<?php } // endif
	// End Jump Links Section

	// Begin Location Alert Section
	if ( $location_alert_section_show ) { ?>
	<section class="uams-module location-alert location-<?php echo $location_alert_color ? $location_alert_color : 'alert-warning'; ?>" id="location-alert">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="module-title<?php echo empty($location_alert_title) ? ' sr-only' : ''; ?>"><?php echo $location_alert_title ? $location_alert_title : 'Alert'; ?></h2>
					<?php echo $location_alert_text ? '<div class="module-body"><p>' . $location_alert_text . '</p></div>' : ''; ?>
				</div>
			</div>
		</div>
	</section>
	<?php } // endif
	// End Location Alert Section

	// Beginning Closing Information Section
	if ( $closing_section_show ) { ?>
		<section class="uams-module location-alert location-alert-warning" id="closing-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title"><span class="title">Closing Information</span></h2>
						<div class="module-body">
							<?php echo $location_closing_info; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif
	// End Closing Information Section

	// Begin About Section
	if ( $about_section_show ) {
	?>
		<section class="uams-module bg-auto" id="description">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title"><span class="title"><?php echo $about_section_title; ?></span></h2>
						<div class="module-body">
							<?php echo $location_about ? $location_about : ''; ?>
							<?php if($location_youtube_link) { ?>
								<?php if(function_exists('lyte_preparse')) {
									echo '<div class="alignwide">';
									echo lyte_parse( str_replace( ['https:', 'http:'], 'httpv:', $location_youtube_link ) );
									echo '</div>';
								} else {
									echo '<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">';
									echo wp_oembed_get( $location_youtube_link );
									echo '</div>';
								} ?>
							<?php }
							if ( $location_affiliation) { 
								if ( $location_about || $prescription ) { 
									echo '<h3 id="affiliation">Affiliation</h3>';
								}
								echo $location_affiliation;
							}
							if ( $prescription) { 
								if ( $location_about || $location_affiliation ) { 
									echo '<h3 id="prescription-info">Prescription Information</h3>';
								}
								echo $prescription;
							} ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif
	// End About Section

	// Begin Parking and Directions Section
	if ( $parking_section_show ) { ?>
		<section class="uams-module bg-auto" id="parking-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12<?php echo $parking_map ? ' col-md-6' : '' ?>">
						<?php if ($parking_map) { ?>
							<div class="module-body">
							<h2><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
						<?php } else { ?>
							<h2 class="module-title"><span class="title"><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></span></h2>
							<div class="module-body">
						<?php } // endif ?>
							<?php echo $location_parking; ?>
							<?php if ( $parking_map ) { ?>
								<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>
							<?php } // endif ?>
							<?php echo ( $location_parking && $location_direction ? '<h3>Directions From the Parking Area</h3>' : ''); // Display the directions heading here if there is a value for parking. ?>
							<?php echo $location_direction; ?>
						</div>
					</div>
					<?php if ( $parking_map ) { ?>
						<div class="col-xs-12 col-md-6 parking-map-container">
							<div class="embed-responsive embed-responsive-16by9" id="map"></div>
							<script type='text/javascript'>
								/*-- Function to create encode SVG --*/
								/* colors need to be hex code without # */
								// createSVGIcon("9d2235", "222", "whitetext", "1");
								var createSVGIcon = function(fillColor,strokeColor,labelClass,labelText) {
									var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 27.77" aria-labelledby="pinTitle" role="img"><title id="mapTitle">Basic Map Pin</title><path d="M9.5,26.26l.57-.65c.29-.4,7.93-9.54,7.93-15.67A8.75,8.75,0,0,0,9.5,1,8.75,8.75,0,0,0,1,9.94c0,6,7.54,15.27,7.93,15.67l.57.65Z" fill="#'+ fillColor +'" stroke="#'+ strokeColor +'" stroke-miterlimit="10" stroke-width="1"/></svg>';
									var encoded = window.btoa(svg);
									var backgroundImage = "background-image: url(data:image/svg+xml;base64,"+encoded+")";
									return '<div style="'+ backgroundImage +'" class="'+ labelClass +'">'+ labelText +'</div>';
								}
								/* Function to create divIcon for leaflet map */
								// createLabelIcon("leaflet-icon","A");
								var createLabelIcon = function(labelClass,labelText){
									return L.divIcon({
										className: labelClass,
										html: labelText,
										iconSize: new L.Point(28, 41),
										iconAnchor: new L.Point(14, 43),
										popupAnchor: [0, -43]
									})
								}
								var map = new L.Map('map', {center: new L.LatLng(<?php echo $parking_map['lat']; ?>, <?php echo $parking_map['lng'] ?>), zoom: 16 });
								map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
								// for all possible values and explanations see "Template Parameters" in https://msdn.microsoft.com/en-us/library/ff701716.aspx
								var imagerySet = "Road"; // AerialWithLabels | Birdseye | BirdseyeWithLabels | Road
								var bing = new L.BingLayer("AnCRy0VpPMDzYT6rOBqqqCNvNbUWvdSOv8zrQloRCGFnJZU28JK3c6cQkCMAHtCd", {type: imagerySet});
								map.addLayer(bing);
								/* [lat, lon, fillColor, strokeColor, labelClass, iconText, popupText] */
								var markers = [
									// example [ 34.74376029995541, -92.31828863640054, "00F","000","white","A","I am a blue icon." ],
									[ <?php echo $map['lat']; ?>, <?php echo $map['lng'] ?>, "9d2235","222", "transparentwhite", '1', 'Clinic<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>' ],
									[ <?php echo $parking_map['lat']; ?>, <?php echo $parking_map['lng'] ?>, "9d2235","222", "transparentwhite", '2', 'Parking<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>' ]
								]
								//Loop through the markers array
								var markerArray = [];
								for (var i=0; i<markers.length; i++) {
									var lat = markers[i][0];
									var lon = markers[i][1];
									var fillColor = markers[i][2];
									var strokeColor = markers[i][3];
									var labelClass = markers[i][4];
									var iconText = markers[i][5];
									var popupText = markers[i][6];
									var markerLocation = new L.LatLng(lat, lon);
									marker = new L.marker([lat, lon], { icon: createLabelIcon("leaflet-icon", createSVGIcon(fillColor,strokeColor,labelClass,iconText))});
									if (popupText)
										marker.bindPopup(popupText, { maxWidth: '240' });
									marker.addTo(map);
									markerArray.push(markerLocation);
								}
								group = new L.LatLngBounds(markerArray);
								if (markers.length > 1){
									map.fitBounds(group, {padding: [100, 75]});
								}
							</script>
							<div class="map-legend bg-info" aria-label="Legend for map">
								<ol data-categorytitle="Directions">
									<li>Clinic (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo $page_title_phrase; ?>" data-typetitle="Get directions to the clinic">Get Directions</a>)</li>
									<li>Parking (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area" data-typetitle="Get directions to the parking area">Get Directions</a>)</li>
								</ol>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php } // endif
	// End Parking and Directions Section

	// Begin Appointment Information Section
	if ( $appointment_section_show ) {
		$location_appointment_heading = 'Appointment Information';
		$location_appointment_bring_heading = 'What to Bring to Your Appointment';
		$location_appointment_expect_heading = 'What to Expect at Your Appointment';
		?>
		<section class="uams-module bg-auto" id="appointment-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<?php if ( $location_appointment ) { ?>
							<h2 class="module-title"><span class="title"><?php echo $location_appointment_heading; ?></span></h2>
							<div class="module-body">
								<?php echo $location_appointment; ?>
								<?php if ( $location_appointment_bring ) { ?>
									<h3><?php echo $location_appointment_bring_heading; ?></h3>
									<?php echo $location_appointment_bring; ?>
								<?php } // endif ?>
								<?php if ( $location_appointment_expect ) { ?>
									<h3><?php echo $location_appointment_expect_heading; ?></h3>
									<?php echo $location_appointment_expect; ?>
								<?php } // endif ?>
							</div>

						<?php } elseif ( $location_appointment_bring && $location_appointment_expect ) { ?>
							<h2 class="module-title"><span class="title"><?php echo $location_appointment_heading; ?></span></h2>
							<div class="module-body">
								<h3><?php echo $location_appointment_bring_heading; ?></h3>
								<?php echo $location_appointment_bring; ?>
								<h3><?php echo $location_appointment_expect_heading; ?></h3>
								<?php echo $location_appointment_expect; ?>
							</div>
						<?php } elseif ( $location_appointment_bring ) { ?>
							<h2 class="module-title"><span class="title"><?php echo $location_appointment_bring_heading; ?></span></h2>
							<div class="module-body">
								<?php echo $location_appointment_bring; ?>
							</div>
						<?php } elseif ( $location_appointment_expect ) { ?>
							<h2 class="module-title"><span class="title"><?php echo $location_appointment_expect_heading; ?></span></h2>
							<div class="module-body">
								<?php echo $location_appointment_expect; ?>
							</div>
						<?php } // endif ?>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif
	// End Appointment Information Section

	// Begin MyChart Scheduling Section
	if ( $mychart_scheduling_section_show ) { ?>
		<section class="uams-module mychart-scheduling-module bg-auto" id="scheduling">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title"><span class="title"><?php echo $location_scheduling_title; ?></span></h2>
						<?php if ( $location_scheduling_intro && !empty($location_scheduling_intro) ) { ?>
							<p class="note"><?php echo $location_scheduling_intro; ?></p>
						<?php } ?>
						<div class="module-body">
							<?php if ($location_scheduling_query && (count((array)$location_scheduling_options) > 1)) { ?>
								<form action="" method="get" class="mychart-scheduling-select">
									<div class="form-group">
										<label for="schedule_options" class="lead">Available Services</label>
										<select name="schedule_options" id="schedule_options" class="form-control">
											<option value="">Select an option</option>
											<?php foreach($location_scheduling_options as $key => $title) : 
												$location_scheduling_item_title_nested = $title['location_scheduling_item_title_nested'];
												$location_scheduling_item_title_nested = ( isset($location_scheduling_item_title_nested) && !empty($location_scheduling_item_title_nested) ) ? $location_scheduling_item_title_nested : 'Schedule an Appointment Online';
												?>
												<option value="<?= $key; ?>"<?php //echo ($key == $provider_title) ? ' selected' : ''; ?>><? echo $location_scheduling_item_title_nested; ?></option>
											<?php endforeach; ?>
										</select>
										<input type="hidden" id="pid" name="pid" value="<?php echo get_the_id(); ?>">
									</div>
								</form>
								<div class="mychart-scheduling"></div>
								<?php //var_dump($location_scheduling_options); ?>
							<?php } else {
								$row = $location_scheduling_options[0];
								$location_scheduling_ser = $row['location_scheduling_ser'];
								$location_scheduling_dep = $row['location_scheduling_dep'];
								$location_scheduling_vt = $row['location_scheduling_vt'];
								$location_scheduling_fallback = $row['location_scheduling_fallback'];
							?>
								<div id="scheduleContainer">
									<iframe id="openSchedulingFrame" class="widgetframe" scrolling="no" src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/SignupAndSchedule/EmbeddedSchedule?id=<?php echo $location_scheduling_ser; ?>&dept=<?php echo $location_scheduling_dep; ?>&vt=<?php echo $location_scheduling_vt; ?>&linksource=<?php echo $mychart_scheduling_linksource; ?>"></iframe>
								</div>

								<!-- <link href="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidget.css" rel="stylesheet" type="text/css"> -->

								<script src="https://<?php echo $mychart_scheduling_domain; ?>/<?php echo $mychart_scheduling_instance; ?>/Content/EmbeddedWidgetController.js" type="text/javascript"></script>

								<script type="text/javascript">
								var EWC = new EmbeddedWidgetController({

									// Replace with the hostname of your Open Scheduling site
									'hostname':'https://<?php echo $mychart_scheduling_domain; ?>',

									// Must equal media query in EpicWP.css + any left/right margin of the host page. Should also change in EmbeddedWidget.css
									'matchMediaString':'(max-width: 991.98px)',

									//Show a button on top of the widget that lets the user see the slots in fullscreen.
									'showToggleBtn':true,

									//The toggle button’s help text for screen reader.
									'toggleBtnExpandHelpText': 'Expand to see the slots in fullscreen',
									'toggleBtnCollapseHelpText': 'Exit fullscreen',
								});
								</script>
								<?php if ( $location_scheduling_fallback && !empty($location_scheduling_fallback) ) { ?>
									<div class="more">
										<?php echo $location_scheduling_fallback; ?>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php }
	// End MyChart Scheduling Section

	// Begin Telemedicine Information Section
	if ( $telemedicine_section_show ) { ?>
		<section class="uams-module bg-auto" aria-label="Telemedicine Information" id="telemedicine-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title">Telemedicine Information</span></h2>
						<?php if ($location_closing_display && !$location_closing_telemed) { ?>
							<div class="module-body">
								<p class="text-center"><strong>
									<?php if ($location_closing_date_past) { ?>
										Telemedicine is not available while this <?php echo strtolower($location_single_name); ?> is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
									<?php } else { ?>
										Telemedicine will not be available after this <?php echo strtolower($location_single_name); ?> closes <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
									<?php } // endif ?>
								</strong></p>
							</div>
						<?php } else { ?>
							<div class="row content-split-lg">
								<div class="col-xs-12 col-lg-7">
									<div class="content-width">
										<?php echo $telemedicine_info ? $telemedicine_info : '' ?>
										<p>
											<?php // Declare which patients can use the service.
											if ($telemedicine_patients == 'all') { ?>
												This service is available to both new and existing patients.
											<?php } elseif ($telemedicine_patients == 'new') { ?>
												This service is available to new patients only.
											<?php } elseif ($telemedicine_patients == 'existing') { ?>
												This service is available to existing patients only.
											<?php } // endif

											// Declare which phone number should be called.

												if (!$location_clinic_phone_query) { // If there is only one phone number ?>
													Patients should call <?php echo $location_phone_link; ?> to schedule a telemedicine appointment.
												<?php } elseif ($location_clinic_phone_query && !$location_appointment_phone_query) { // If there is only one appointment number ?>
													Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
												<?php } else { // If there are two appointment numbers (one for new, one for existing)
													if ($telemedicine_patients == 'all') { ?>
														New patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment, while existing patients should call <?php echo $location_return_appointments_phone_link; ?>.
													<?php } elseif ($telemedicine_patients == 'new') { ?>
														Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
													<?php } elseif ($telemedicine_patients == 'existing') { ?>
														Patients should call <?php echo $location_return_appointments_phone_link; ?> to schedule a telemedicine appointment.
													<?php }
												} // endif ?>
										</p>
									</div>
								</div>
								<div class="col-xs-12 col-lg-5">
									<div class="content-width">
									<?php
									$telemedicine_modified_text = '';
									$telemedicine_active_start = '';
									$telemedicine_active_end = '';
									if ($telemedicine_modified) : 
									?>
									<?php 

										$telemedicine_modified_day = ''; // Previous Day
										$telemedicine_modified_comment = ''; // Comment on previous day
										$i = 1;

										$telemedicine_today = strtotime("today");
										$telemedicine_today_30 = strtotime("+30 days");

										if( strtotime($telemedicine_modified_start) <= $telemedicine_today_30 && ( strtotime($telemedicine_modified_end_date) >= $telemedicine_today || !$telemedicine_modified_end ) ){
											$telemedicine_modified_text .= $telemedicine_modified_reason;
											$telemedicine_modified_text .= '<p class="small font-italic">These modified hours start on ' . $telemedicine_modified_start . ', ';
											$telemedicine_modified_text .= $telemedicine_modified_end && $telemedicine_modified_end_date ? 'and are scheduled to end after ' . $telemedicine_modified_end_date . '.' : 'and will remain in effect until further notice.';
											$telemedicine_modified_text .= '</p>';

											if ($telemedicine_modified_hours247):
												$telemedicine_modified_text .= '<strong>Open 24/7</strong>';
											else :
												$telemedicine_modified_times = $location_hours_group['location_telemed_modified_hours_times'];
												if ($telemedicine_active_start > strtotime($telemedicine_modified_start) || '' == $telemedicine_active_start) {
													$telemedicine_active_start = strtotime($telemedicine_modified_start);
												}
												if ( $telemedicine_active_end <= strtotime($telemedicine_modified_end_date) || !$telemedicine_modified_end ) {
													if (!$telemedicine_modified_end) {
														$telemedicine_active_end = 'TBD';
													} else {
														$telemedicine_active_end = strtotime($telemedicine_modified_end_date);
													}
												}

												if (is_array($telemedicine_modified_times) || is_object($telemedicine_modified_times)) {
													$telemedicine_modified_text .= '<dl class="hours">';
													foreach ( $telemedicine_modified_times as $telemedicine_modified_time ) {

														$telemedicine_modified_text .= $telemedicine_modified_day !== $telemedicine_modified_time['location_telemed_modified_hours_day'] ? '<dt>'. $telemedicine_modified_time['location_telemed_modified_hours_day'] .'</dt> ' : '';
														$telemedicine_modified_text .= '<dd>';

														if ( $telemedicine_modified_time['location_telemed_modified_hours_closed'] ) {
															$telemedicine_modified_text .= 'Closed ';
														} else {
															$telemedicine_modified_text .= ( ( $telemedicine_modified_time['location_telemed_modified_hours_open'] && '00:00:00' != $telemedicine_modified_time['location_telemed_modified_hours_open'] ) ? '' . ap_time_span( strtotime($telemedicine_modified_time['location_telemed_modified_hours_open']), strtotime($telemedicine_modified_time['location_telemed_modified_hours_close']) ) . '' : '' );
														}
														if ( $telemedicine_modified_time['location_telemed_modified_hours_comment'] ) {
															$telemedicine_modified_text .= ' <br /><span class="subtitle">' .$telemedicine_modified_time['location_telemed_modified_hours_comment'] . '</span>';
															$telemedicine_modified_comment = $telemedicine_modified_time['location_telemed_modified_hours_comment'];
														} else {
															$telemedicine_modified_comment = '';
														}
														$telemedicine_modified_text .= '</dd>';
														$telemedicine_modified_day = $telemedicine_modified_time['location_telemed_modified_hours_day']; // Reset the day
														$i++;

													} // endforeach
													$telemedicine_modified_text .= '</dl>';

												} // End if (array)
											endif;
										}

										echo $telemedicine_modified_text ? '<h3>Modified Hours</h3>' . $telemedicine_modified_text: '';

									endif; // End Modified Hours
									if (($telemedicine_active_start != '' && $telemedicine_active_start <= $telemedicine_today) && ( strtotime($telemedicine_active_end) > $telemedicine_today || $telemedicine_active_end == 'TBD' ) ) {
										// Do Nothing;
										// Future Option
									} else {
										if ( $telemedicine_hours247 || $telemedicine_hours[0]['day'] ) : ?>
										<h3><?php echo $telemedicine_modified_text ? 'Typical ' : ''; ?>Hours</h3>
										<?php
											if ($telemedicine_hours247):
												echo '<strong>Open 24/7</strong>';
											else :
												echo '<dl class="hours">';
												if( $telemedicine_hours ) {
													$telemedicine_hours_text = '';
													$telemedicine_day = ''; // Previous Day
													$telemedicine_comment = ''; // Comment on previous day
													$i = 1;
													foreach ($telemedicine_hours as $telemedicine_hour) :
														$telemedicine_hours_text .= $telemedicine_day !== $telemedicine_hour['day'] ? '<dt>'. $telemedicine_hour['day'] .'</dt> ' : '';
														$telemedicine_hours_text .= '<dd>';

														if ( $telemedicine_hour['closed'] ) {
															$telemedicine_hours_text .= 'Closed ';
														} else {
															$telemedicine_hours_text .= ( ( $telemedicine_hour['open'] && '00:00:00' != $telemedicine_hour['open'] ) ? '' . ap_time_span( strtotime($telemedicine_hour['open']), strtotime($telemedicine_hour['close']) ) . '' : '' );
														}
														if ( $telemedicine_hour['comment'] ) {
															$telemedicine_hours_text .= ' <br /><span class="subtitle">' .$telemedicine_hour['comment'] . '</span>';
															$telemedicine_comment = $telemedicine_hour['comment'];
														} else {
															$telemedicine_comment = '';
														}
														$telemedicine_hours_text .= '</dd>';
														$telemedicine_day = $telemedicine_hour['day']; // Reset the day
														if (!$telemedicine_hour['closed']) {
														$i++;
														}
													endforeach;
													echo $telemedicine_hours_text;
												} else {
													echo '<dt>No information</dt>';
												}
												echo '</dl>';
											endif;
										endif;
										}
										?>
									</div>
								</div>
							</div>
						<?php } // endif ?>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif
	// End Telemedicine Information Section

	// Begin Portal Section
	if ( $portal_section_show ) { ?>
		<section class="uams-module cta-bar cta-bar-weighted bg-blue" aria-label="Patient Portal" id="portal-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="inner-container">
							<div class="cta-heading">
								<h2><?php echo $portal_name; ?></h2>
							</div>
							<?php if ( $portal_content || $portal_link ) { ?>
							<div class="cta-body">
								<?php if ( $portal_content ) { ?>
								<div class="text-container">
									<?php echo $portal_content; ?>
								</div>
								<?php }
								if ( $portal_content ) { ?>
								<div class="btn-container">
									<a href="<?php echo $portal_url; ?>" aria-label="Log in to <?php echo $portal_name_attr; ?> to view your patient information and medical records" class="btn btn-white" target="_blank" data-moduletitle="<?php echo $portal_name_attr; ?>"><?php echo $portal_link_title ? $portal_link_title : 'Log in to '. $portal_name; ?></a>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif
	// End Portal Section

	// Begin Providers Section
	$provider_section_title = $provider_fpage_title_location; // Text to use for the section title
	$provider_section_intro = $provider_fpage_intro_location; // Text to use for the section intro text
	$provider_section_filter_region = false; // Query whether to add region filter
	include( UAMS_FAD_PATH . '/templates/parts/section-list-provider.php' );
	// End Providers Section

	// Begin Conditions Section
	$condition_section_title = $condition_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for condition section title in a general placement)
	$condition_section_intro = $condition_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for condition section intro text in a general placement)
	include( UAMS_FAD_PATH . '/templates/parts/section-list-condition.php' );
	// End Conditions Section

	// Begin Treatments Section
	$treatment_section_title = $treatment_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for treatment section title in a general placement)
	$treatment_section_intro = $treatment_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for treatment section intro text in a general placement)
	include( UAMS_FAD_PATH . '/templates/parts/section-list-treatment.php' );
	// End Treatments Section

	// Begin Areas of Expertise Section
	$expertise_section_title = $expertise_fpage_title_location;
	$expertise_section_intro = $expertise_fpage_intro_location;
	include( UAMS_FAD_PATH . '/templates/parts/section-list-expertise.php' );
	// End Areas of Expertise Section

	// Begin Descendant Locations Section
	$location_section_title = $location_descendant_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for locations section title in a general placement)
	$location_section_intro = $location_descendant_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for locations section intro text in a general placement)
	$location_section_filter = false; // Query whether to add filter(s) // bool (default: true)
	$location_descendant_list = true; // Query whether this is a list of child locations within a location // bool (default: false)
	include( UAMS_FAD_PATH . '/templates/parts/section-list-location.php' );
	// End Descendant Locations Section

	// Begin Clinical Resources Section
	$clinical_resource_section_more_link_key = '_resource_locations';
	$clinical_resource_section_more_link_value = $page_slug;
	$clinical_resource_section_title = $clinical_resource_fpage_title_location; // Text to use for the section title // string (default: Find-a-Doc Settings value for areas of clinical_resource section title in a general placement)
	$clinical_resource_section_intro = $clinical_resource_fpage_intro_location; // Text to use for the section intro text // string (default: Find-a-Doc Settings value for areas of clinical_resource section intro text in a general placement)
	$clinical_resource_section_more_text = $clinical_resource_fpage_more_text_location;
	$clinical_resource_section_more_link_text = $clinical_resource_fpage_more_link_text_location;
	$clinical_resource_section_more_link_descr = $clinical_resource_fpage_more_link_descr_location;
	uamswp_fad_section_clinical_resource();
	// End Clinical Resources Section

	// Begin News Section
	if ( true == false ) { ?>
		<!-- Latest News -->
		<!-- <section class="uams-module news-list bg-auto" id="news">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><span class="title">Latest News for <?php echo $page_title_phrase; ?></span></h2>
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
	<? }
	// End News Section
	?>
</main>
</div>

<?php // Schema Data ?>
<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "MedicalClinic",
  "name": "<?php echo $page_title; ?>",
  "url": "<?php echo get_permalink(); ?>",
  "image": "<?php echo $locationphoto; ?>",
  <?php echo $condition_schema; ?>
  <?php echo $treatment_schema; ?>
  <?php echo $location_schema; ?>
  <?php echo $modified_hours_schema; ?>
  <?php echo $hoursvary ? '' : $hours_schema; ?>
  <?php echo $phone_schema; ?>
  "logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>"
}
</script>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
