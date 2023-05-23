<?php
/**
 * 	Template Name: Online Scheduling Information, Appointment Booking
 * 	Designed for UAMS Find-a-Doc
 * 
 * 	Required vars
 * 		$show_scheduling_mychart_book_section // Check if appointment booking section should be displayed
 * 		$scheduling_appointments_count // Count number of appointment subsections
 * 		$scheduling_mychart_book_group_sys // ACF field containing the inputs relevant to Appointment Booking
 * 		$scheduling_mychart_type // Which Type(s) of MyChart Open Scheduling?
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 
 * 	Required vars from single location template:
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 
 * 	Required vars from single provider template:
 * 		$scheduling_mychart_book_visit_type // Visit Type(s) from UAMS Health Epic for Appointment Booking
 */

 if ( $show_scheduling_mychart_book_section ) { // $show_scheduling_mychart_section is defined in /templates/blocks/online-scheduling-check.php

	// Get values from from Find-a-Doc Settings input group labeled "Appointment Booking"
	$scheduling_mychart_book_group_sys = get_field('mychart_scheduling_book_group', 'option'); // ACF field containing the inputs relevant to Appointment Booking
	$scheduling_mychart_book_heading_standalone = $scheduling_mychart_book_group_sys['mychart_scheduling_book_heading_system'] ?: 'Appointments'; // Standalone Heading for Appointment Booking
	$scheduling_mychart_book_descr = $scheduling_mychart_book_group_sys['mychart_scheduling_book_descr_system'] ?: 'Find a provider at this location to book an appointment online.'; // Standalone Description for Appointment Booking
	$scheduling_mychart_book_heading_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_heading_nested_system'] ?: ''; // Nested Heading for Appointment Booking (Optional)
	$scheduling_mychart_book_descr_nested = $scheduling_mychart_book_group_sys['mychart_scheduling_book_descr_nested_system'] ?: $scheduling_mychart_book_descr; // Nested Description for Appointment Booking (Optional)

	// Begin content
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
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book-button.php' ); ?>
		</div>
	</div>
<?php } // endif $show_scheduling_mychart_section

?>