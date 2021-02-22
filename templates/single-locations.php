<?php 
/*
 *  Get ACF fields to use for meta data
 *  Add description from location short description or full description * 
 */
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
$parent_url = ''; // Eliminate PHP errors
$parent_location = ''; // Eliminate PHP errors
if ($location_has_parent && $location_parent_id) { 
	$parent_location = get_post( $location_parent_id );
}
// Get Post ID for Address & Image fields
if ($parent_location) {
	$post_id = $parent_location->ID;
	$parent_title = $parent_location->post_title;
	$parent_url = get_permalink( $post_id );
} else {
	$post_id = get_the_ID();
}

// Phone values

$location_phone = get_field('location_phone');
$location_phone_link = '<a href="tel:' . format_phone_dash( $location_phone ) . '" class="icon-phone" data-categorytitle="Clinic Phone Number">' . format_phone_us( $location_phone ) . '</a>';
$location_clinic_phone_query = get_field('location_clinic_phone_query'); // separate number for (new) appointments?
if ($location_clinic_phone_query) {
	$location_new_appointments_phone = get_field('location_new_appointments_phone'); // phone number for (new) appointments
	$location_appointment_phone_query = get_field('field_location_appointment_phone_query'); // separate number for existing appointments?
	$location_new_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_new_appointments_phone ) . '" class="icon-phone" data-categorytitle="Appointment Phone Number for New' . ($location_appointment_phone_query ? '' : ' and Returning') . ' Appointments">' . format_phone_us( $location_new_appointments_phone ) . '</a>';
} else {
	$location_new_appointments_phone = '';
	$location_appointment_phone_query = '0';
}
if ($location_appointment_phone_query) {
	$location_return_appointments_phone = get_field('location_return_appointments_phone'); // phone number for existing appointments
	$location_return_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_return_appointments_phone ) . '" class="icon-phone" data-categorytitle="Appointment Phone Number for Returning Patients">' . format_phone_us( $location_return_appointments_phone ) . '</a>';
} else {
	$location_return_appointments_phone = '';
}
$location_fax = get_field('location_fax');
$location_fax_link = '<a href="tel:' . format_phone_dash( $location_fax ) . '" class="icon-phone" data-categorytitle="Clinic Fax Number">' . format_phone_us( $location_fax ) . '</a>';
$location_phone_numbers = get_field('field_location_phone_numbers');

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
// $telemed_query = get_field('field_location_telemed_query'); // Is there telemedicine?
// $telemed_patients = get_field('field_location_telemed_patients'); // New patients, existing or both?
// $telemed_hours247 = get_field('field_location_telemed_24_7'); // typically 24/7?
// $telemed_hours = get_field('location_telemed_hours'); // telemedicine hours repeater
// $telemed_modified = get_field('field_location_telemed_modified_hours_query'); // Are there modified hours for telemedicine?
// $telemed_modified_reason = get_field('field_location_telemed_modified_hours_reason'); // Why are there modified hours for telemedicine?
// $telemed_modified_start = get_field('field_location_telemed_modified_hours_start_date'); // When do the modified telemedicine hours start?
// $telemed_modified_end = get_field('field_location_telemed_modified_hours_end'); // Do we know when the modified telemedicine hours end?
// $telemed_modified_end_date = get_field('field_location_telemed_modified_hours_end_date'); // When do the modified telemedicine hours end?
// $telemed_modified_hours = get_field('field_location_telemed_modified_hours_group'); // modified telemedicine hours repeater
// Hours Grouping
$location_hours_group = get_field('location_hours_group');

$telemed_query = $location_hours_group['location_telemed_query']; // Is there telemedicine?
$telemed_patients = $location_hours_group['location_telemed_patients']; // New patients, existing or both?
$telemed_hours247 = $location_hours_group['location_telemed_24_7']; // typically 24/7?
$telemed_hours = $location_hours_group['location_telemed_hours']; // telemedicine hours repeater
$telemed_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
$telemed_modified_reason = $location_hours_group['location_telemed_modified_hours_reason']; // Why are there modified hours for telemedicine?
$telemed_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
$telemed_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
$telemed_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?
$telemed_modified_hours247 = $location_hours_group['location_telemed_modified_hours_24_7'];
// $telemed_modified_hours = $location_hours_group['location_telemed_modified_hours_group']; // modified telemedicine hours repeater
$telemed_info = get_field('location_telemed_descr_system', 'option'); // System-wide information about telemedicine at locations

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
	$prescription_info_type =  get_field('location_prescription_type'); // Which preset or custom text?
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

function sp_titles_desc($html) {
    global $excerpt;
	$html = $excerpt; 
	return $html;
}
add_filter('seopress_titles_desc', 'sp_titles_desc');

function uamswp_fad_title($html) { 

	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = get_the_title() . ' | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('pre_get_document_title', 'uamswp_fad_title', 15, 2);

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
		$location_floor_value = $location_floor['value'];
		$location_floor_label = $location_floor['choices'][ $location_floor_value ];
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
	$location_web_name = get_field('location_web_name', $post_id);
	$location_url = get_field('location_url', $post_id);
?>
<div class="content-sidebar-wrap">
<main class="location-item" id="genesis-content">
	<section class="container-fluid p-0 p-md-10 location-info bg-white">
		<div class="row mx-0 mx-md-n8">
			<div class="col-12 col-md text">
				<div class="content-width">
					<h1 class="page-title"><?php the_title(); ?>
					<?php if ($parent_location) { ?>
					<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
					<?php } // endif ?>
					</h1>
					<?php if ($location_closing_display) { ?>
						<div class="alert alert-warning" role="alert">
							<p>
							<?php if ($location_closing_date_past) { ?>
								This location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
							<?php } else { ?>
								This location will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
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
								<a href="#closing-info" class="alert-link no-break" aria-label="Learn more information about the closure of this location">Learn more</a>.
							<?php } // endif ?>
							</p>
							<?php if ($location_closing_telemed) { ?>
								<p>Telemedicine will still be available. <a href="#telemedicine-info" class="alert-link no-break" aria-label="Learn more information about telemedicine at this location">Learn more</a>.</p>
							<?php } ?>
						</div>
					<?php } // endif ?>
					<h2 class="sr-only">Address</h2>
					<p><?php echo $location_address_1; ?><br/>
					<?php echo ( $location_address_2 ? $location_address_2 . '<br/>' : ( $location_address_2_deprecated ? $location_address_2_deprecated . '<br/>' : '')); ?>
					<?php echo $location_city; ?>, <?php echo $location_state; ?> <?php echo $location_zip; ?></p>
						<p><a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php echo get_the_title($post_id); ?>" data-categorytitle="Get Directions">Get Directions</a></p>
						<?php if( $location_web_name && $location_url ){ ?>
							<p><a class="btn btn-secondary" href="<?php echo $location_url['url']; ?>"><?php echo $location_web_name; ?> <span class="far fa-external-link-alt"></span></span></a></p>
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
					<dl>
						<?php if ($location_phone) { ?>
						<dt>Clinic Phone Number</dt>
						<dd><?php echo $location_phone_link; ?></dd>
						<?php $phone_schema .= '"telephone": ["'. format_phone_dash( $location_phone ) .'"
						'; ?>
						<?php } ?>
						<?php if ($location_new_appointments_phone && $location_clinic_phone_query) { ?>
							<dt>Appointment Phone Number<?php echo $location_appointment_phone_query ? 's' : ''; ?></dt>
							<dd><?php echo $location_new_appointments_phone_link; ?><?php echo $location_appointment_phone_query ? '<br/><span class="subtitle">New Patients</span>' : '<br/><span class="subtitle">New and Returning Patients</span>'; ?></dd>
							<?php $phone_schema .= ', "'. format_phone_dash( $location_new_appointments_phone ) .'"
							'; ?>
							<?php if ($location_return_appointments_phone && $location_appointment_phone_query) { ?>
								<dd><?php echo $location_return_appointments_phone_link; ?><br/><span class="subtitle">Returning Patients</span></dd>
								<?php $phone_schema .= ', "'. format_phone_dash( $location_return_appointments_phone ) .'"
							'; ?>
							<?php } ?>
						<?php } ?>
						<?php if ($location_fax) { ?>
						<dt>Clinic Fax Number</dt>
						<dd><?php echo $location_fax_link; ?></dd>
						<?php } ?>
						<?php if ( $location_phone_numbers ) { 
							$phone_numbers = $location_phone_numbers;
							while( have_rows('field_location_phone_numbers') ): the_row(); 
								$title = get_sub_field('location_appointments_text');
								$phone = get_sub_field('location_appointments_phone');
								$text = get_sub_field('location_appointments_additional_text');
						?>
						<dt><?php echo $title; ?></dt>
						<dd><a href="tel:<?php echo format_phone_dash( $phone ); ?>" data-categorytitle="Additional Phone Number: <?php echo $title; ?>"><?php echo format_phone_us( $phone ); ?></a><?php echo ($text ? '<br/><span class="subtitle">'. $text .'</span>' : ''); ?></dd>
						<?php if ('' != $phone){
							$phone_schema .= ', "'. format_phone_dash( $phone ) .'"
							'; 
							}?>
						<?php endwhile; 
							} ?>
						<?php
							$phone_numbers = get_field('location_appointments');
							if ( ! empty( $phone_numbers ) && ! empty( $phone_numbers[0]['number'] ) ) {
								foreach ( $phone_numbers as $phone_number ) {
									if (! empty($phone_number['text']) && ! empty($phone_number['number']) ) {
										echo '<dt>' . $phone_number['text'] . '</dt>';
										echo '<dd><a href="tel:'. $phone_number['number'] .'" class="icon-phone">'. $phone_number['number'] .'</a> ' . $phone_number['after'] .'</dd>'; // Display sub-field value
									}
								}
							}
						?>
					</dl>
					<?php
					$phone_schema .= '],';
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
						if ($modified && $modified_hours) : 
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

								foreach ($modified_hours as $modified_hour) {
		
									$modified_title = $modified_hour['location_modified_hours_title'];
									$modified_info = $modified_hour['location_modified_hours_information'];
									$modified_times = $modified_hour['location_modified_hours_times'];
									$modified_hours247 = $modified_hour['location_modified_hours_24_7'];
									$modified_text .= $modified_title ? '<h3 class="h4">'.$modified_title.'</h3>' : '';
									$modified_text .= $modified_info ? $modified_info : '';
		
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
										echo '<strong>Open 24/7</strong>';
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
													$modified_text .= ( ( $modified_time['location_modified_hours_open'] && '00:00:00' != $modified_time['location_modified_hours_open'] )  ? '' . apStyleDate( $modified_time['location_modified_hours_open'] ) . ' &ndash; ' . apStyleDate( $modified_time['location_modified_hours_close'] ) . '' : '' );
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
						
							echo $modified_text ? '<h2>Modified Hours</h2>' . $modified_text: '';
							
						endif; // End Modified Hours
						if ('' != $modified_hours_schema) {
							$modified_hours_schema ='"openingHoursSpecification": [
								' . $modified_hours_schema . '}
								],
								';
						}
						if (($active_start != '' && $active_start <= $today) && ( strtotime($active_end) > $today || $active_end == 'TBD' ) ) {
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
												$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] )  ? '' . apStyleDate( $hour['open'] ) . ' &ndash; ' . apStyleDate( $hour['close'] ) . '' : '' );
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
											echo ( ( $hour['open'] && '00:00:00' != $row['open'] )  ? '' . apStyleDate( $row['open'] ) . ' &ndash; ' . apStyleDate( $row['close'] ) . ' ' : '' );
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
						<p>If you are in need of urgent or emergency care call 911 or go to your nearest emergency department at your local hospital.</p>
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
							<?php } else {  ?>
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
											<?php } else {  ?>
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
										<?php } else {  ?>
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
	<?php if (
		(
			($location_closing && !$location_closing_date_past) // If location closing is toggled, but closing start date is future
			||
			($location_closing && $location_reopen_date_past) // If location closing is toggled, but reopening date is past (or is TBD)
			||
			!$location_closing // If location closing is not toggled
		)
		&& 
		($location_alert_title || $location_alert_text) // If location title or description has value
	 ) { ?>
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
	if ($location_closing_display && !empty($location_closing_info)) { ?>
		<section class="uams-module location-alert location-alert-warning" id="closing-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title">Closing Information</h2>
						<div class="module-body">
							<?php echo $location_closing_info; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } // endif ?>
	<?php 
		$location_about = get_field('location_about');
		$location_affiliation = get_field('location_affiliation');
		$location_youtube_link = get_field('location_youtube_link');
	
		if ( $location_about || $location_affiliation || $prescription ) { 
		?>
		<section class="uams-module bg-auto" id="description">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<?php if ( $location_about || $location_youtube_link || ( !$location_about && $location_affiliation && $prescription ) ) { ?>
						<h2 class="module-title">About <?php the_title(); ?></h2>
						<?php } elseif ( $location_affiliation ) {
							echo '<h2 class="module-title">Affiliation</h2>';
						} elseif ( $prescription ) {
							echo '<h2 class="module-title">Prescription Information</h2>';
						} ?>
						<div class="module-body">
							<?php echo $location_about ? $location_about : ''; ?>
							<?php if($location_youtube_link) { ?>
                            <div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
                                <?php echo wp_oembed_get( $location_youtube_link ); ?>
                            </div>
							<?php }
							if ( $location_affiliation) { 
								if ( $location_about || $prescription ) { 
									echo '<h3>Affiliation</h3>';
								}
								echo $location_affiliation;
							}
							if ( $prescription) { 
								if ( $location_about || $location_affiliation ) { 
									echo '<h3>Prescription Information</h3>';
								}
								echo $prescription;
							} ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>
	<?php 
		$location_parking = get_field('location_parking', $post_id);
		$location_direction = get_field('location_direction', $post_id);
		$parking_map = get_field('location_parking_map', $post_id);
	
		if ( $location_parking || $location_direction || $parking_map ) : ?>
		<section class="uams-module bg-auto" id="directions">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12<?php echo $parking_map ? ' col-md-6' : ''  ?>">
						<?php if ($parking_map) { ?>
							<div class="module-body">
							<h2><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
						<?php } else { ?>
							<h2 class="module-title"><?php echo ( $location_parking ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
							<div class="module-body">
						<?php } // endif ?>
							<?php echo $location_parking; ?>
							<?php if ( $parking_map ) { ?>
								<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>
							<?php } // endif ?>
							<?php echo ( $location_parking && $location_direction ? '<h3>Directions From the Parking Area</h3>' : ''); // Display the directions heading here if there is a value for parking. ?>
							<?php echo $location_direction; ?>
						</div>
					</div>
					<?php if ( $parking_map ) { ?>
						<div class="col-xs-12 col-md-6 parking-map-container">
							<div class="embed-responsive embed-responsive-16by9" id="map"></div>
							<script type='text/javascript'>
								/*-- Function to create encode SVG  --*/
								/* colors needd to be hex code without # */
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
									[ <?php echo $map['lat']; ?>, <?php echo $map['lng'] ?>, "9d2235","222", "transparentwhite", '1', 'Clinic<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php the_title(); ?>">Get Directions</a>' ],
									[ <?php echo $parking_map['lat']; ?>, <?php echo $parking_map['lng'] ?>, "9d2235","222", "transparentwhite", '2', 'Parking<br/><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>' ]
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
								<ol>
									<li>Clinic (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php the_title(); ?>">Get Directions</a>)</li>
									<li>Parking (<a href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>)</li>
								</ol>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
		$location_appointment = get_field('location_appointment');
		$location_appointment_bring = get_field('location_appointment_bring');

		if ( $location_appointment || $location_appointment_bring): ?>
		<section class="uams-module bg-auto" id="appointment-info">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<?php if ( $location_appointment && $location_appointment_bring ) { ?>
							<h2 class="module-title">Appointment Information</h2>
							<div class="module-body">
								<?php echo $location_appointment; ?>
								<h3>What to Bring to Your Appointment</h3>
								<?php echo $location_appointment_bring; ?>
							</div>

						<?php } elseif ( $location_appointment ) { ?>
							<h2 class="module-title">Appointments</h2>
							<div class="module-body">
								<?php echo $location_appointment; ?>
							</div>

						<?php } elseif ( $location_appointment_bring ) { ?>
							<h2 class="module-title">What to Bring to Your Appointment</h2>
							<div class="module-body">
								<?php echo $location_appointment_bring; ?>
							</div>
						<?php } // endif ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php // Telemedicine
		if ($telemed_query) { ?>
			<section class="uams-module bg-auto" aria-label="Telemedicine Information" id="telemedicine-info">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title">Telemedicine Information</h2>
							<?php if ($location_closing_display && !$location_closing_telemed) { ?>
								<div class="module-body">
									<p class="text-center"><strong>
										<?php if ($location_closing_date_past) { ?>
											Telemedicine is not available while this location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
										<?php } else { ?>
											Telemedicine will not be available after this location closes <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
										<?php } // endif ?>
									</strong></p>
								</div>
							<?php } else { ?>
								<div class="row content-split-lg">
									<div class="col-xs-12 col-lg-7">
										<div class="content-width">
											<?php echo $telemed_info ? $telemed_info : '' ?>
											<p>
												<?php // Declare which patients can use the service.
												if ($telemed_patients == 'all') { ?>
													This service is available to both new and existing patients.
												<?php } elseif ($telemed_patients == 'new') { ?>
													This service is available to new patients only.
												<?php } elseif ($telemed_patients == 'existing') { ?>
													This service is available to existing patients only.
												<?php } // endif

												// Declare which phone number should be called.
													
													if (!$location_clinic_phone_query) { // If there is only one phone number ?>
														Patients should call <?php echo $location_phone_link; ?> to schedule a telemedicine appointment.
													<?php } elseif ($location_clinic_phone_query && !$location_appointment_phone_query) { // If there is only one appointment number ?>
														Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
													<?php } else { // If there are two appointment numbers (one for new, one for existing)
														if ($telemed_patients == 'all') { ?>
															New patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment, while existing patients should call <?php echo $location_return_appointments_phone_link; ?>.
														<?php } elseif ($telemed_patients == 'new') { ?>
															Patients should call <?php echo $location_new_appointments_phone_link; ?> to schedule a telemedicine appointment.
														<?php } elseif ($telemed_patients == 'existing') { ?>
															Patients should call <?php echo $location_return_appointments_phone_link; ?> to schedule a telemedicine appointment.
														<?php }
													} // endif ?>
											</p>
										</div>
									</div>
									<div class="col-xs-12 col-lg-5">
										<div class="content-width">
										<?php
										$telemed_modified_text = '';
										$telemed_active_start = '';
										$telemed_active_end = '';
										if ($telemed_modified) : 
										?>
										<?php 
											
											$telemed_modified_day = ''; // Previous Day
											$telemed_modified_comment = ''; // Comment on previous day
											$i = 1;

											$telemed_today = strtotime("today");
											$telemed_today_30 = strtotime("+30 days");

											if( strtotime($telemed_modified_start) <= $telemed_today_30 && ( strtotime($telemed_modified_end_date) >= $telemed_today || !$telemed_modified_end ) ){
												$telemed_modified_text .= $telemed_modified_reason;
												$telemed_modified_text .= '<p class="small font-italic">These modified hours start on ' . $telemed_modified_start . ', ';
												$telemed_modified_text .= $telemed_modified_end && $telemed_modified_end_date ? 'and are scheduled to end after ' . $telemed_modified_end_date . '.' : 'and will remain in effect until further notice.';
												$telemed_modified_text .= '</p>';

												if ($telemed_modified_hours247):
													$telemed_modified_text .= '<strong>Open 24/7</strong>';
												else :
													$telemed_modified_times = $location_hours_group['location_telemed_modified_hours_times'];
													if ($telemed_active_start > strtotime($telemed_modified_start) || '' == $telemed_active_start) {
														$telemed_active_start = strtotime($telemed_modified_start);
													}
													if ( $telemed_active_end <= strtotime($telemed_modified_end_date) || !$telemed_modified_end ) {
														if (!$telemed_modified_end) {
															$telemed_active_end = 'TBD';
														} else {
															$telemed_active_end = strtotime($telemed_modified_end_date);
														}
													}
											
													if (is_array($telemed_modified_times) || is_object($telemed_modified_times)) {
														$telemed_modified_text .= '<dl class="hours">';
														foreach ( $telemed_modified_times as $telemed_modified_time ) {
															
															$telemed_modified_text .= $telemed_modified_day !== $telemed_modified_time['location_telemed_modified_hours_day'] ? '<dt>'. $telemed_modified_time['location_telemed_modified_hours_day'] .'</dt> ' : '';
															$telemed_modified_text .= '<dd>';
						
															if ( $telemed_modified_time['location_telemed_modified_hours_closed'] ) {
																$telemed_modified_text .= 'Closed ';
															} else {
																$telemed_modified_text .= ( ( $telemed_modified_time['location_telemed_modified_hours_open'] && '00:00:00' != $telemed_modified_time['location_telemed_modified_hours_open'] )  ? '' . apStyleDate( $telemed_modified_time['location_telemed_modified_hours_open'] ) . ' &ndash; ' . apStyleDate( $telemed_modified_time['location_telemed_modified_hours_close'] ) . '' : '' );
															}
															if ( $telemed_modified_time['location_telemed_modified_hours_comment'] ) {
																$telemed_modified_text .= ' <br /><span class="subtitle">' .$telemed_modified_time['location_telemed_modified_hours_comment'] . '</span>';
																$telemed_modified_comment = $telemed_modified_time['location_telemed_modified_hours_comment'];
															} else {
																$telemed_modified_comment = '';
															}
															$telemed_modified_text .= '</dd>';
															$telemed_modified_day = $telemed_modified_time['location_telemed_modified_hours_day']; // Reset the day
															$i++;
															
														} // endforeach
														$telemed_modified_text .= '</dl>';
													
													} // End if (array)
												endif;
											}
										
											echo $telemed_modified_text ? '<h3>Modified Hours</h3>' . $telemed_modified_text: '';
											
										endif; // End Modified Hours
										if (($telemed_active_start != '' && $telemed_active_start <= $telemed_today) && ( strtotime($telemed_active_end) > $telemed_today || $telemed_active_end == 'TBD' ) ) {
											// Do Nothing;
											// Future Option
										} else {
											if ( $telemed_hours247 || $telemed_hours[0]['day'] ) : ?>
											<h3><?php echo $telemed_modified_text ? 'Typical ' : ''; ?>Hours</h3>
											<?php
												if ($telemed_hours247):
													echo '<strong>Open 24/7</strong>';
												else :
													echo '<dl class="hours">';
													if( $telemed_hours ) {
														$telemed_hours_text = '';
														$telemed_day = ''; // Previous Day
														$telemed_comment = ''; // Comment on previous day
														$i = 1;
														foreach ($telemed_hours as $telemed_hour) :
															$telemed_hours_text .= $telemed_day !== $telemed_hour['day'] ? '<dt>'. $telemed_hour['day'] .'</dt> ' : '';
															$telemed_hours_text .= '<dd>';

															if ( $telemed_hour['closed'] ) {
																$telemed_hours_text .= 'Closed ';
															} else {
																$telemed_hours_text .= ( ( $telemed_hour['open'] && '00:00:00' != $telemed_hour['open'] )  ? '' . apStyleDate( $telemed_hour['open'] ) . ' &ndash; ' . apStyleDate( $telemed_hour['close'] ) . '' : '' );
															}
															if ( $telemed_hour['comment'] ) {
																$telemed_hours_text .= ' <br /><span class="subtitle">' .$telemed_hour['comment'] . '</span>';
																$telemed_comment = $telemed_hour['comment'];
															} else {
																$telemed_comment = '';
															}
															$telemed_hours_text .= '</dd>';
															$telemed_day = $telemed_hour['day']; // Reset the day
															if (!$telemed_hour['closed']) {
															$i++;
															}
														endforeach;
														echo $telemed_hours_text;
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
	// End Telemedicine ?>
	<?php // Portal
		$location_portal = get_field('location_portal');
		
		if ( $location_portal ) :
			$portal = get_term($location_portal, "portal");
			$portal_slug = $portal->slug;
			$portal_name = $portal->name;
			$portal_content = get_field('portal_content', $portal);
			$portal_link = get_field('portal_url', $portal);
			if ($portal_link) {
				$portal_url = $portal_link['url'];
				$portal_link_title = $portal_link['title'];
			}

			if ($portal && $portal_slug !== "_none") {
	?>
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
									<a href="<?php echo $portal_url; ?>" aria-label="Access <?php echo $portal_name; ?>&nbsp;to view your patient information and medical records" class="btn btn-white" target="_blank" data-moduletitle="<?php echo $portal_name; ?>"><?php echo $portal_link_title ? $portal_link_title : 'Log in to '. $portal_name; ?></a>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php }
	endif; ?>
	<?php
	$physicians = get_field( 'physician_locations' );
	if($physicians) {
		$postsPerPage = 6; // Set this value to preferred value (4, 6, 8, 10, 12). If you change the value, update the instruction text in the editor's JSON file.
        $postsCutoff = 9; // Set cutoff value. If you change the value, update the instruction text in the editor's JSON file.
		$postsCountClass = $postsPerPage;
		if(count($physicians) <= $postsCutoff ) {
			$postsPerPage = -1;
		}
		$args = array(
			"post_type" => "provider",
			"post_status" => "publish",
			"posts_per_page" => $postsPerPage,
			"orderby" => "title",
			"order" => "ASC",
			"post__in" => $physicians
		);
		$physicians_query = New WP_Query( $args );
		if( $physicians_query->have_posts() ) { 
		?>
			<section class="uams-module bg-auto" id="doctors">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title">Providers at <?php the_title(); ?></h2>
							<div class="card-list-container">
								<div class="card-list card-list-doctors card-list-doctors-count-<?php echo $postsCountClass; ?>">
									<?php 
										while ($physicians_query->have_posts()) : $physicians_query->the_post();
											$id = get_the_ID();
											include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
										endwhile;
										wp_reset_postdata();
									?>
								</div>
							</div>
							<?php if ($postsPerPage !== -1) { ?>
							<div class="more">
								<button class="loadmore btn btn-primary" data-postids="<?php echo(implode(',', $physicians)); ?>" data-ppp="<?php echo $postsPerPage; ?>" data-postcount="<?php echo $physicians_query->found_posts; ?>" aria-label="Load more providers">Load More</button>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</section>
		<?php
		}
	}
	?>
	<?php // load all 'conditions' terms for the post
	$title_append = ' at ' . get_the_title();
	$conditions_cpt = get_field('location_conditions_cpt');
	$condition_schema = '';
	// Conditions CPT
	$args = (array(
		'post_type' => "condition",
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'post__in' => $conditions_cpt
	));
	$conditions_cpt_query = new WP_Query( $args );
	// $condition_schema = '';
	// we will use the first term to load ACF data from
	if( $conditions_cpt && $conditions_cpt_query->posts ):
		include( UAMS_FAD_PATH . '/templates/loops/conditions-cpt-loop.php' );
		$condition_schema .= ',"medicalSpecialty": [';
		foreach( $conditions_cpt_query->posts as $condition ):
			$condition_schema .= '{
			"@type": "MedicalSpecialty",
			"name": "'. $condition->post_title .'",
			"url":"'. get_the_permalink( $condition->ID ) .'"
			},';
		endforeach;
		$condition_schema .= '"" ]';
	endif;
	$treatments_cpt = get_field('location_treatments_cpt');
	$treatment_schema = '';
	// Treatments CPT
	$args = (array(
		'post_type' => "treatment",
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'post__in' => $treatments_cpt
	));
	$treatments_cpt_query = new WP_Query( $args );
	if( $treatments_cpt && $treatments_cpt_query->posts ):
		include( UAMS_FAD_PATH . '/templates/loops/treatments-cpt-loop.php' );
		$treatment_schema .= ',"medicalSpecialty": [';
		foreach( $treatments_cpt_query->posts as $treatment ):
			$treatment_schema .= '{
			"@type": "MedicalSpecialty",
			"name": "'. $treatment->post_title .'",
			"url":"'. get_the_permalink( $treatment->ID ) .'"
			},';
		endforeach;
		$treatment_schema .= '"" ]';
	endif; 
	$expertises =  get_field('location_expertise');
	$args = (array(
        'post_type' => "expertise",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in'	=> $expertises
    ));
    $expertise_query = new WP_Query( $args );
	if( $expertises && $expertise_query->have_posts() ): ?>
		<section class="uams-module expertise-list bg-auto" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title">Areas of Expertise Represented at <?php the_title(); ?></h2>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
							<?php 
							while ($expertise_query->have_posts()) : $expertise_query->the_post();
								$id = get_the_ID();
								include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>
        </section>
	<?php 
	endif;
	// Child locations
	$current_id = get_the_ID();
    if ( ( 0 != count( get_pages( array( 'child_of' => $current_id, 'post_type' => 'location' ) ) ) ) ) { // If none available, set to false
        $args =  array(
            "post_type" => "location",
            "post_status" => "publish",
			"post_parent" => $current_id,
			'order' => 'ASC',
			'orderby' => 'title',
			'meta_query' => array(
				array(
					'key' => 'location_hidden',
					'value' => '1',
					'compare' => '!=',
				)
			),
        );
        $children = New WP_Query ( $args );
        if ( $children->have_posts() ) { ?>
            <section class="uams-module location-list bg-auto" id="sub-clinics" aria-labelledby="sub-location-title" >
                <div class="container-fluid">
                    <div class="row">
						<div class="col-12">
							<h2 class="module-title" id="sub-location-title"><span class="title">Additional Clinics Within <?php echo get_the_title(); ?></span></h2>
							<div class="card-list-container">
                                <div class="card-list">
                            <?php
                                while ( $children->have_posts() ) : $children->the_post();
                                    $id = get_the_ID(); 
                                    include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
                                endwhile;
                                wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
            </section>
        <?php
        }
    }
	?>
	<!-- Latest News -->
	<!-- <section class="uams-module news-list bg-auto" id="news">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title">Latest News for <?php the_title(); ?></h2>
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
</main>
</div>

<?php // Schema Data ?>
<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "MedicalClinic",
  "name": "<?php echo get_the_title(); ?>",
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