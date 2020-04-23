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

// Phone values

$location_phone = get_field('location_phone');
$location_phone_link = '<a href="tel:' . format_phone_dash( $location_phone ) . '" class="icon-phone">' . format_phone_us( $location_phone ) . '</a>';
$location_clinic_phone_query = get_field('location_clinic_phone_query'); // separate number for (new) appointments?
if ($location_clinic_phone_query) {
	$location_new_appointments_phone = get_field('location_new_appointments_phone'); // phone number for (new) appointments
	$location_new_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_new_appointments_phone ) . '" class="icon-phone">' . format_phone_us( $location_new_appointments_phone ) . '</a>';
	$location_appointment_phone_query = get_field('field_location_appointment_phone_query'); // separate number for existing appointments?
} else {
	$location_new_appointments_phone = '';
	$location_appointment_phone_query = '0';
}
if ($location_appointment_phone_query) {
	$location_return_appointments_phone = get_field('location_return_appointments_phone'); // phone number for existing appointments
	$location_return_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_return_appointments_phone ) . '" class="icon-phone">' . format_phone_us( $location_return_appointments_phone ) . '</a>';
} else {
	$location_return_appointments_phone = '';
}
$location_fax = get_field('location_fax');
$location_fax_link = '<a href="tel:' . format_phone_dash( $location_fax ) . '" class="icon-phone">' . format_phone_us( $location_fax ) . '</a>';
$location_phone_numbers = get_field('field_location_phone_numbers');

// Image values

$wayfinding_photo = get_field('location_wayfinding_photo');
$photo_gallery = get_field('location_photo_gallery');
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
$featured_image = get_post_thumbnail_id();
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
$telemed_modified_hours = $location_hours_group['location_telemed_modified_hours_group']; // modified telemedicine hours repeater

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
<?php $map = get_field('location_map'); ?>
<div class="content-sidebar-wrap">
<main class="location-item" id="genesis-content">
	<section class="container-fluid p-0 p-md-10 location-info bg-white">
		<div class="row mx-0 mx-md-n8">
			<div class="col-12 col-md text">
				<div class="content-width">
					<h1 class="page-title"><?php the_title(); ?></h1>
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
					<p><?php echo get_field('location_address_1', get_the_ID() ); ?><br/>
					<?php echo ( get_field('location_address_2' ) ? get_field('location_address_2') . '<br/>' : ''); ?>
					<?php echo get_field('location_city'); ?>, <?php echo get_field('location_state'); ?> <?php echo get_field('location_zip', get_the_ID()); ?></p>
						<p><a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get directions to <?php the_title(); ?>">Get Directions</a></p>
					<?php if(get_field('location_web_name') && get_field('location_url')){ ?>
						<p><a class="btn btn-secondary" href="<?php echo get_field('location_url')['url']; ?>"><?php echo get_field('location_web_name'); ?> <span class="far fa-external-link-alt"></span></span></a></p>
					<?php } 
						// Schema data
						$location_schema = '"address": {
						"@type": "PostalAddress",
						"streetAddress": "'. get_field('location_address_1' ) . ' '. get_field('location_address_2' ) .'",
						"addressLocality": "'. get_field('location_city') .'",
						"addressRegion": "'. get_field('location_state' ) .'",
						"postalCode": "'. get_field('location_zip') .'"
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
						<dd><a href="tel:<?php echo format_phone_dash( $phone ); ?>"><?php echo format_phone_us( $phone ); ?></a><?php echo ($text ? '<br/><span class="subtitle">'. $text .'</span>' : ''); ?></dd>
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
							$modified_text .= '<p class="small font-italic">These modified hours start on ' . date("l, F j, Y", strtotime($modified_start)) . ', ';
							$modified_text .= $modified_end && $modified_end_date ? 'and are scheduled to end after ' . date("l, F j, Y", strtotime($modified_end_date)) . '.' : 'and will remain in effect until further notice.';
							$modified_text .= '</p>';

							foreach ($modified_hours as $modified_hour) {
	
								$modified_title = $modified_hour['location_modified_hours_title'];
								$modified_info = $modified_hour['location_modified_hours_information'];
								$modified_times = $modified_hour['location_modified_hours_times'];
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
								if (is_array($modified_times) || is_object($modified_times)) {
									$modified_text .= '<dl class="hours">';
									foreach ( $modified_times as $modified_time ) {
										
										// if( $modified_day !== $modified_time['location_modified_hours_day'] || $modified_comment ) { // change for single day
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
											// if (!$modified_time['location_modified_hours_closed']) {
											// 	if (1 != $i) {
											// 		$modified_hours_schema .= ', 
											// 		';
											// 	}
											// 	// $modified_hours_schema .= '';
											// }
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
	
										// } else { // change for single day
										// 	$modified_text .= ', ';
										// }
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
											if ( $modified_time['location_modified_hours_comment'] ) {
												$modified_text .= ' <br /><span class="subtitle">' .$modified_time['location_modified_hours_comment'] . '</span>';
												$modified_comment = $modified_time['location_modified_hours_comment'];
											} else {
												$modified_comment = '';
											}
										}
										// if( $modified_day !== $modified_time['location_modified_hours_day'] || $modified_comment ) { // change for single day
											$modified_text .= '</dd>';
										// }
										$modified_day = $modified_time['location_modified_hours_day']; // Reset the day
										// if (!$modified_time['location_modified_hours_closed']) {
										// 	$modified_hours_schema .= '';
										// }
										// if (!$modified_time['location_modified_hours_closed']) {
											$i++;
										// }
										
									} // endforeach
									$modified_text .= '</dl>';
								} // End if (array)
								
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
								echo 'Open 24/7';
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
											if ( $hour['comment'] ) {
												$hours_text .= ' <br /><span class="subtitle">' .$hour['comment'] . '</span>';
												$comment = $hour['comment'];
											} else {
												$comment = '';
											}
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
					<?php if ($location_hours_group['location_after_hours'] && !$location_hours_group['location_24_7']) { ?>
					<h2>After Hours</h2>
					<?php echo $location_hours_group['location_after_hours']; ?>
					<?php } elseif (!$location_hours_group['location_24_7']) { ?>
					<h2>After Hours</h2>
					<p>If you are in need of urgent or emergency care call 911 or go to your nearest emergency department at your local hospital.</p>
					<?php } endif;
					} ?>
				</div>
			</div>
			<?php if ( $location_images_count ) { ?>
			<div class="col-12 col-md image">
				<div class="content-width">
					<?php if ( $location_images_count == 1 ) { ?>
						<picture>
							<?php if ( function_exists( 'fly_add_image_size' ) && !empty($location_images[0]) ) { ?>
								<source srcset="<?php echo image_sizer($location_images[0], 630, 473, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images[0], 1260, 946, 'center', 'center'); ?> 2x"
									media="(min-width: 1350px)">
								<source srcset="<?php echo image_sizer($location_images[0], 572, 429, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images[0], 1144, 858, 'center', 'center'); ?> 2x"
									media="(min-width: 992px)">
								<source srcset="<?php echo image_sizer($location_images[0], 992, 558, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images[0], 1984, 1116, 'center', 'center'); ?> 2x"
									media="(min-width: 768px)">
								<source srcset="<?php echo image_sizer($location_images[0], 768, 432, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images[0], 1536, 864, 'center', 'center'); ?> 2x"
									media="(min-width: 576px)">
								<source srcset="<?php echo image_sizer($location_images[0], 576, 324, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images[0], 1152, 648, 'center', 'center'); ?> 2x"
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
												<source srcset="<?php echo image_sizer($location_images_item, 630, 473, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images_item, 1260, 946, 'center', 'center'); ?> 2x"
													media="(min-width: 1350px)">
												<source srcset="<?php echo image_sizer($location_images_item, 572, 429, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images_item, 1144, 858, 'center', 'center'); ?> 2x"
													media="(min-width: 992px)">
												<source srcset="<?php echo image_sizer($location_images_item, 992, 558, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images_item, 1984, 1116, 'center', 'center'); ?> 2x"
													media="(min-width: 768px)">
												<source srcset="<?php echo image_sizer($location_images_item, 768, 432, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images_item, 1536, 864, 'center', 'center'); ?> 2x"
													media="(min-width: 576px)">
												<source srcset="<?php echo image_sizer($location_images_item, 576, 324, 'center', 'center'); ?> 1x, <?php echo image_sizer($location_images_item, 1152, 648, 'center', 'center'); ?> 2x"
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
	<?php if ($location_alert_title && !$location_closing_date_past || $location_alert_text && !$location_closing_date_past) { ?>
	<section class="uams-module location-alert location-<?php echo $location_alert_color ? $location_alert_color : 'alert-warning'; ?>">
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
	<?php if ( get_field('location_about') || get_field('location_affiliation') ) { 
			$about = get_field('location_about');
			$affiliation = get_field('location_affiliation');
			$youtube_link = get_field('location_youtube_link');
		?>
		<section class="uams-module bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<?php if ( $about || $youtube_link ) { ?>
						<h2 class="module-title">About <?php the_title(); ?></h2>
						<?php } else { // Must be Affiliation
							echo '<h2 class="module-title">Affiliation</h2>';
						} ?>
						<div class="module-body">
							<?php echo $about ? $about : ''; ?>
							<?php if($youtube_link) { ?>
                            <div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9">
                                <?php echo wp_oembed_get( $youtube_link ); ?>
                            </div>
                            <?php } ?>
							<?php if ( $affiliation) { 
								if ( !empty( $about ) ) { 
									echo '<h3>Affiliation</h3>';
								}
								 echo $affiliation; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>
	<?php if (get_field('location_parking') || get_field('location_direction') || get_field('location_parking_map')) : ?>
	<?php $parking_map = get_field('location_parking_map'); ?>
		<section class="uams-module bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12<?php echo $parking_map ? ' col-md-6' : ''  ?>">
						<?php if ($parking_map) { ?>
							<div class="module-body">
							<h2><?php echo ( get_field('location_parking' ) ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
						<?php } else { ?>
							<h2 class="module-title"><?php echo ( get_field('location_parking' ) ? 'Parking Information' : 'Directions From the Parking Area'); // Display parking heading if parking has value. Otherwise, display directions heading. ?></h2>
							<div class="module-body">
						<?php } // endif ?>
							<?php echo get_field('location_parking'); ?>
							<?php if ( $parking_map ) { ?>
								<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $parking_map['lat'] ?>,<?php echo $parking_map['lng'] ?>" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>
							<?php } // endif ?>
							<?php echo ( get_field('location_parking') && get_field('location_direction') ? '<h3>Directions From the Parking Area</h3>' : ''); // Display the directions heading here if there is a value for parking. ?>
							<?php echo get_field('location_direction'); ?>
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
	<?php if ( get_field('location_appointment') || get_field('location_appointment_bring')): ?>
		<section class="uams-module bg-auto">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<?php if ( get_field('location_appointment') && get_field('location_appointment_bring') ) { ?>
							<h2 class="module-title">Appointment Information</h2>
							<div class="module-body">
								<?php echo get_field('location_appointment'); ?>
								<h3>What to Bring to Your Appointment</h3>
								<?php echo get_field('location_appointment_bring'); ?>
							</div>

						<?php } elseif ( get_field('location_appointment') ) { ?>
							<h2 class="module-title">Appointments</h2>
							<div class="module-body">
								<?php echo get_field('location_appointment'); ?>
							</div>

						<?php } elseif ( get_field('location_appointment_bring') ) { ?>
							<h2 class="module-title">What to Bring to Your Appointment</h2>
							<div class="module-body">
								<?php echo get_field('location_appointment_bring'); ?>
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
											<p>Insert generic information about telemedicine here.</p>
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
										if ($telemed_modified && $telemed_modified_hours) : 
										?>
										<?php 
											
											$telemed_modified_day = ''; // Previous Day
											$telemed_modified_comment = ''; // Comment on previous day
											$i = 1;

											$telemed_today = strtotime("today");
											$telemed_today_30 = strtotime("+30 days");

											if( strtotime($telemed_modified_start) <= $telemed_today_30 && ( strtotime($telemed_modified_end_date) >= $telemed_today || !$telemed_modified_end ) ){
												$telemed_modified_text .= $telemed_modified_reason;
												$telemed_modified_text .= '<p class="small font-italic">These modified hours start on ' . date("l, F j, Y", strtotime($telemed_modified_start)) . ', ';
												$telemed_modified_text .= $telemed_modified_end && $telemed_modified_end_date ? 'and are scheduled to end after ' . date("l, F j, Y", strtotime($telemed_modified_end_date)) . '.' : 'and will remain in effect until further notice.';
												$telemed_modified_text .= '</p>';

												foreach ($telemed_modified_hours as $telemed_modified_hour) {
						
													$telemed_modified_title = $telemed_modified_hour['location_telemed_modified_hours_title'];
													$telemed_modified_info = $telemed_modified_hour['location_telemed_modified_hours_information'];
													$telemed_modified_times = $telemed_modified_hour['location_telemed_modified_hours_times'];
													$telemed_modified_text .= $telemed_modified_title ? '<h3 class="h4">'.$telemed_modified_title.'</h3>' : '';
													$telemed_modified_text .= $telemed_modified_info ? $telemed_modified_info : '';
						
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
															
															// if( $telemed_modified_day !== $telemed_modified_time['location_telemed_modified_hours_day'] || $telemed_modified_comment ) { // change for single day
																$telemed_modified_text .= $telemed_modified_day !== $telemed_modified_time['location_telemed_modified_hours_day'] ? '<dt>'. $telemed_modified_time['location_telemed_modified_hours_day'] .'</dt> ' : '';
																$telemed_modified_text .= '<dd>';
						
															// } else { // change for single day
															// 	// $telemed_modified_text .= ', ';
															// }
															if ( $telemed_modified_time['location_telemed_modified_hours_closed'] ) {
																$telemed_modified_text .= 'Closed ';
															} else {
																$telemed_modified_text .= ( ( $telemed_modified_time['location_telemed_modified_hours_open'] && '00:00:00' != $telemed_modified_time['location_telemed_modified_hours_open'] )  ? '' . apStyleDate( $telemed_modified_time['location_telemed_modified_hours_open'] ) . ' &ndash; ' . apStyleDate( $telemed_modified_time['location_telemed_modified_hours_close'] ) . '' : '' );
																if ( $telemed_modified_time['location_telemed_modified_hours_comment'] ) {
																	$telemed_modified_text .= ' <br /><span class="subtitle">' .$telemed_modified_time['location_telemed_modified_hours_comment'] . '</span>';
																	$telemed_modified_comment = $telemed_modified_time['location_telemed_modified_hours_comment'];
																} else {
																	$telemed_modified_comment = '';
																}
															}
															// if( $telemed_modified_day !== $telemed_modified_time['location_telemed_modified_hours_day'] || $telemed_modified_comment ) { // change for single day
																$telemed_modified_text .= '</dd>';
															// }
															$telemed_modified_day = $telemed_modified_time['location_telemed_modified_hours_day']; // Reset the day
															$i++;
															
														} // endforeach
														$telemed_modified_text .= '</dl>';
													} // End if (array)
													
												}
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
													echo 'Open 24/7';
												else :
													echo '<dl class="hours">';
													if( $telemed_hours ) {
														$telemed_hours_text = '';
														$telemed_day = ''; // Previous Day
														$telemed_comment = ''; // Comment on previous day
														$i = 1;
														foreach ($telemed_hours as $telemed_hour) :
															// if( $telemed_day !== $telemed_hour['day'] || $telemed_comment ) { // change for single day
																$telemed_hours_text .= $telemed_day !== $telemed_hour['day'] ? '<dt>'. $telemed_hour['day'] .'</dt> ' : '';
																$telemed_hours_text .= '<dd>';
															// } else { // change for single day
															// 	$telemed_hours_text .= ', ';
															// }
															if ( $telemed_hour['closed'] ) {
																$telemed_hours_text .= 'Closed ';
															} else {
																$telemed_hours_text .= ( ( $telemed_hour['open'] && '00:00:00' != $telemed_hour['open'] )  ? '' . apStyleDate( $telemed_hour['open'] ) . ' &ndash; ' . apStyleDate( $telemed_hour['close'] ) . '' : '' );
																if ( $telemed_hour['comment'] ) {
																	$telemed_hours_text .= ' <br /><span class="subtitle">' .$telemed_hour['comment'] . '</span>';
																	$telemed_comment = $telemed_hour['comment'];
																} else {
																	$telemed_comment = '';
																}
															}
															// if( $telemed_day !== $telemed_hour['day'] && $telemed_comment ) { // change for single day
																$telemed_hours_text .= '</dd>';
															// }
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
		if ( get_field('location_portal') ) :
			$portal = get_term(get_field('location_portal'), "portal");
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
		<section class="uams-module cta-bar cta-bar-weighted bg-blue" aria-label="Patient Portal">
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
	$conditions = get_field('location_conditions');
	$condition_schema = '';
	$args = (array(
		'taxonomy' => "condition",
        'order' => 'ASC',
        'orderby' => 'name',
        'hide_empty' => false,
        'term_taxonomy_id' => $conditions
    ));
    $conditions_query = new WP_Term_Query( $args );
	if( $conditions && !empty( $conditions_query->terms ) ):
		include( UAMS_FAD_PATH . '/templates/loops/conditions-loop.php' );
		$condition_schema .= '"medicalSpecialty": [';
		foreach( $conditions as $condition ):
			$condition_schema .= '{
			"@type": "MedicalSpecialty",
			"name": "'. get_term( $condition, 'condition' )->name .'",
			"url":"'. get_term_link($condition) .'"
			},';
		endforeach;
		$condition_schema .= '"" ],';
    endif;
	$treatments = get_field('location_treatments');
	$args = (array(
		'taxonomy' => "treatment",
        'order' => 'ASC',
        'orderby' => 'name',
        'hide_empty' => false,
        'term_taxonomy_id' => $treatments
    ));
    $treatments_query = new WP_Term_Query( $args );
	if( $treatments && !empty( $treatments_query->terms ) ): 
		include( UAMS_FAD_PATH . '/templates/loops/treatments-loop.php' );
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
							<div class="card-list">
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
	?>
	<!-- Latest News -->
	<!-- <section class="uams-module news-list bg-auto">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title">Latest News for <?php the_title(); ?></h2>
					<div class="card-list-container">
						<div class="card-list">
							<div class="card">
								<img srcset="https://picsum.photos/434/244?image=1066 1x, https://picsum.photos/868/488?image=1066 2x" src="https://picsum.photos/434/244?image=1066" class="card-img-top" alt="Image description or Story title">
								<div class="card-body">
									<h3 class="card-title">
										<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
									</h3>
									<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
									<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
								</div>
							</div>
							<div class="card">
								<img srcset="https://picsum.photos/434/244?image=348 1x, https://picsum.photos/868/488?image=348 2x" src="https://picsum.photos/434/244?image=348" class="card-img-top" alt="Image description or Story title">
								<div class="card-body">
									<h3 class="card-title">
										<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
									</h3>
									<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
									<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
								</div>
							</div>
							<div class="card">
								<img srcset="https://picsum.photos/434/244?image=823 1x, https://picsum.photos/868/488?image=823 2x" src="https://picsum.photos/434/244?image=823" class="card-img-top" alt="Image description or Story title">
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
  <?php echo $location_schema; ?>
  <?php echo $modified_hours_schema; ?>
  <?php echo $hours_schema; ?>
  <?php echo $phone_schema; ?>
  "logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>"
}
</script>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>