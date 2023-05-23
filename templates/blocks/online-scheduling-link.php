<?php
/**
 * 	Template Name: Online Scheduling Information
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 * 		$show_scheduling_mychart_book_section // Check if appointment booking section should be displayed
 * 		$scheduling_mychart_book_group_sys // ACF field containing the inputs relevant to Appointment Booking
 * 		$scheduling_mychart_type // Which Type(s) of MyChart Open Scheduling?
 * 		$show_scheduling_request_section // Check if link(s) to appointment request form(s) should be displayed
 * 		$scheduling_request_forms // Appointment Request Form(s)
 * 		$scheduling_request_btn_style // Define whether the appointment request button is solid or outline
 * 		$show_scheduling_mychart_preregister_section
 * 		$scheduling_mychart_preregister_group_sys // ACF field containing the inputs relevant to Visit Pre-Registration
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 		$page_slug
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 		$scheduling_mychart_preregister_options // MyChart Open Scheduling Widget Option(s) for Visit Pre-Registration
 * 	
 * 	Required vars from single provider template:
 * 		$scheduling_mychart_preregister_visit_type
 */

// Check variables
$parent_slug = isset($parent_slug) ? $parent_slug : '';
$scheduling_mychart_book_options = isset($scheduling_mychart_book_options) ? $scheduling_mychart_book_options : '';
$scheduling_mychart_preregister_options = isset($scheduling_mychart_preregister_options) ? $scheduling_mychart_preregister_options : '';
$scheduling_mychart_preregister_visit_type = isset($scheduling_mychart_preregister_visit_type) ? $scheduling_mychart_preregister_visit_type : '';


// Begin Appointments Section

$scheduling_appointments_count = $show_scheduling_mychart_book_section + $show_scheduling_request_section; // Count number of appointment subsections

$scheduling_heading_system = get_field('scheduling_heading_system', 'option'); // Heading for Online Scheduling Section

if ( $scheduling_template == 'single-location' ) {

	// Begin Location Profile Appointment Information Content
	
	if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsections 
		// Display the general Appointments heading ?>
		<h2 class="h4"><?php echo $scheduling_heading_system; ?></h2>
	<?php }

	// Begin Appointment Booking Subsection

	include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' );

	// End Appointment Booking Subsection


	// Begin Appointment Request Subsection

	include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' );

	// End Appointment Request Subsection

	// End Appointments Section


	// Begin Visit Pre-Registration Section

	include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister.php' );

	// End Visit Pre-Registration Section

	// End Location Profile Appointment Information Content
	
} elseif ( $scheduling_template == 'single-provider' ) {
	
	// Begin Provider Profile Appointment Information Content

	// Begin Appointment Booking Subsection

	?>
	<div class="col-12 col-lg-6">
		<?php
			if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsections 
				// Display the general Appointments heading ?>
				<h2 class="h5"><?php echo $scheduling_heading_system; ?></h2>
			<?php }
			include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' );
		?>
	</div>
	<?php

		// Original structure
		if ( true == false ) { 
			$appointments_heading = 'Appointments';
			?>
			<div class="col-12 col-lg-6">
				<h2 class="h5"><?php echo $appointments_heading; ?></h2>
				<p>Book an appointment with this provider online.</p>
				<div class="btn-container">
					<div class="inner-container">
						<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book-button.php' ); ?>
					</div>
				</div>
			</div>
		<?php }

	// End Appointment Booking Subsection


	// Begin Appointment Request Subsection

	?>
	<div class="col-12 col-lg-6">
		<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' ); ?>
	</div>
	<?php

		// Original structure
		if ( true == false ) {
			$scheduling_request_form_count = count($scheduling_request_forms);
			
			$scheduling_request_heading = 'Specialized Care Appointments';
			$scheduling_request_heading_standalone = $appointments_heading;
			$scheduling_request_intro = 'Some appointments for specialized care with this provider cannot be scheduled online. For those, submit a request for an appointment.';
			$scheduling_request_intro_standalone = 'Appointments for specialized care with this provider cannot be scheduled online. However, you can submit a request for an appointment.';
			$scheduling_request_button_text = 'Request an Appointment';
			?>
			<div class="col-12 col-lg-6">
				<?php
				if ( $show_scheduling_mychart_section ) {
					echo '<h3 class="h5">' . $scheduling_request_heading . '</h3>';
					echo '<p>' . $scheduling_request_intro . '</p>';
				} else {
					echo '<h2 class="h4">' . $scheduling_request_heading_standalone . '</h2>';
					echo '<p>' . $scheduling_request_intro_standalone . '</p>';
				}
				
				$scheduling_request_utm_medium_val = 'single-provider';
				$scheduling_request_utm_content_val = $page_slug; // page slug
				?>
				<div class="btn-container">
					<div class="inner-container">
						<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request-button.php' ); ?>
					</div>
				</div>
			</div>
		<?php }

	// End Appointment Request Subsection

	// End Appointments Section


	// Begin Visit Pre-Registration Section

		// include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister.php' );

	// End Visit Pre-Registration Section

	// End Provider Profile Appointment Information Content

}

?>