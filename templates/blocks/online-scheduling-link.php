<?php
/**
 * 	Template Name: Online Scheduling Information
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$show_scheduling_mychart_book_section
 * 		$show_scheduling_request_section
 * 		$show_scheduling_mychart_preregister_section
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 		$page_slug
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 		
 * 	
 * 	Required vars from single provider template:
 * 		
 * 	
 * 	Optional vars from single location template:
 * 		
 * 	
 * 	Optional vars from single provider template:
 * 	
 */

// Check variables
$parent_slug = isset($parent_slug) ? $parent_slug : '';


// Begin Appointments Section

$scheduling_appointments_count = $show_scheduling_mychart_book_section + $show_scheduling_request_section; // Count number of appointment subsections

$scheduling_heading_system = get_field('scheduling_heading_system', 'option'); // Heading for Online Scheduling Section

if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsiections 
	// Display the general Appointments heading ?>
	<h2 class="h4"><?php echo $scheduling_heading_system; ?></h2>
<?php }


// Begin Appointment Booking Subsection

if ( $show_scheduling_mychart_book_section ) { // $show_scheduling_mychart_section is defined in /templates/blocks/online-scheduling-check.php

	// Get values from from Find-a-Doc Settings input group labeled "Appointment Booking"
	$scheduling_mychart_book_group_sys = get_field('mychart_scheduling_book_group', 'option'); // ACF field containing the inputs relevant to Appointment Booking
	$scheduling_mychart_book_heading_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_heading_system'] ?: 'Appointments'; // Standalone Heading for Appointment Booking
	$scheduling_mychart_book_descr = $scheduling_mychart_book_group_sys['mychart_scheduling_book_descr_system'] ?: 'Find a provider at this location to book an appointment online.'; // Standalone Description for Appointment Booking
	$scheduling_mychart_book_heading_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_heading_nested_system'] ?: ''; // Nested Heading for Appointment Booking (Optional)
	$scheduling_mychart_book_descr_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_descr_nested_system'] ?: $scheduling_mychart_book_descr; // Nested Description for Appointment Booking (Optional)

	if ( $scheduling_appointments_count == 1 ) { // If this is the only appointments section ?>
		<h2 class="h4"><?php echo $scheduling_mychart_book_heading_standalone; ?></h2>
		<p><?php echo $scheduling_mychart_book_descr; ?></p>
	<?php } else { // Otherwise (this is one of multiple appointments sections)
		if ( $scheduling_mychart_book_heading_nested ) { // If Nested Heading for Appointment Booking has a value ?>
			<h3 class="h5"><?php echo $scheduling_mychart_book_heading_nested; ?></h2>
		<?php } // endif ( $scheduling_mychart_book_heading_nested ) ?>
		<p><?php echo $scheduling_mychart_book_descr_nested; ?></p>
	<?php } // endif ( $scheduling_count == 1 ) ?>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' ); ?>
		</div>
	</div>
<?php } // endif $show_scheduling_mychart_section

// End Appointment Booking Subsection


// Begin Appointment Request Subsection

if ( $show_scheduling_request_section ) {
	$scheduling_request_heading_standalone = get_field('appointment_request_heading_standalone_system', 'option') ?: 'Appointments';
	$scheduling_request_intro_standalone = get_field('appointment_request_descr_standalone_system', 'option') ?: 'Appointments for specialized care at this location cannot be scheduled online. For those, submit a request for an appointment.';
	$scheduling_request_heading_nested = get_field('appointment_request_heading_nested_system', 'option') ?: 'Specialized Care';
	$scheduling_request_intro_nested = get_field('appointment_request_descr_nested_system', 'option') ?: 'Some appointments at this location involve specialized care and cannot be scheduled online. For those, submit a request for an appointment.';
	if ( $scheduling_appointments_count > 1 ) {
		echo '<h3 class="h5">' . $scheduling_request_heading_nested . '</h3>';
		echo '<p>' . $scheduling_request_intro_nested . '</p>';
	} else {
		echo '<h2 class="h4">' . $scheduling_request_heading_standalone . '</h2>';
		echo '<p>' . $scheduling_request_intro_standalone . '</p>';
	}

	$scheduling_request_utm_medium_val = $scheduling_template;
	$scheduling_request_utm_content_val = $parent_slug ? $parent_slug . '_' . $page_slug : $page_slug;
	?>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' ); ?>
		</div>
	</div>
<?php }

// End Appointment Request Subsection

// End Appointments Section


// Begin Visit Pre-Registration Section

if ( $show_scheduling_mychart_preregister_section ) { // $show_scheduling_mychart_preregister_section is defined in /templates/blocks/online-scheduling-check.php
	$scheduling_mychart_preregister_group_sys = get_field('mychart_scheduling_preregister_group', 'option'); // ACF field containing the inputs relevant to Visit Pre-Registration
	$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_heading_system'] ?: 'Immediate Care';
	$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_descr_system'] ?: 'Spend less time waiting and get home faster by choosing an available time.';
	?>
	<h2 class="h4"><?php echo $scheduling_mychart_preregister_heading_standalone; ?></h2>
	<p><?php echo $scheduling_mychart_preregister_descr_standalone; ?></p>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister.php' ); ?>
		</div>
	</div>
<?php } // endif ( $show_scheduling_mychart_preregister_section )

// End Visit Pre-Registration Section

?>