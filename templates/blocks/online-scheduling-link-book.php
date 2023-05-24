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
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 
 * 	Required vars from single location template:
 * 		$scheduling_group // ACF field containing the inputs relevant to MyChart open scheduling and appointment request forms
 */

 if ( $show_scheduling_mychart_book_section ) { // $show_scheduling_mychart_section is defined in /templates/blocks/online-scheduling-check.php

	// Begin content
	if ( $scheduling_appointments_count > 1 ) { // Otherwise (this is one of multiple appointments sections)
		if ( $scheduling_mychart_book_heading_nested ) { // If Nested Heading for Appointment Booking has a value ?>
			<h3 class="<?php echo $scheduling_mychart_book_heading_class; ?>"><?php echo $scheduling_mychart_book_heading_nested; ?></h2>
		<?php } else { ?>
			<h2 class="<?php echo $scheduling_general_heading_class; ?>"><?php echo $scheduling_heading_system; ?></h2>
		<?php } // endif ( $scheduling_mychart_book_heading_nested ) ?>
		<p><?php echo $scheduling_mychart_book_descr_nested; ?></p>
	<?php } else { // If this is the only appointments section ?>
		<h2 class="<?php echo $scheduling_mychart_book_heading_class; ?>"><?php echo $scheduling_mychart_book_heading_standalone; ?></h2>
		<p><?php echo $scheduling_mychart_book_descr_standalone; ?></p>
	<?php } // endif ( $scheduling_appointments_count > 1 ) ?>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book-button.php' ); ?>
		</div>
	</div>
<?php } // endif $show_scheduling_mychart_section

?>