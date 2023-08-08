<?php
/**
 * Template Name: Appointments loop / text block
 * 
 * Description: A template part that displays an appointment information section 
 * on pages other than provider profiles.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$appointment_section_show // bool
 * 
 * Optional vars:
 * 	$location_section_show // bool
 */

if ( $appointment_section_show ) {

	// Get system settings for general patient appointment information
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/contacts/patients.php' );

	// Set appointment link URL

		// Check/define variables
		$location_section_show = isset($location_section_show) ? $location_section_show : false;

		if ( $location_section_show ) {

			$appointment_location_url = '#locations';
			// $appointment_location_label = 'Go to the list of relevant locations';

		} else {

			$appointment_location_url = user_trailingslashit('/location/');
			// $appointment_location_label = 'View a list of UAMS Health locations';

		} // endif ( $location_section_show ) else

	// Construct section

		if (
			$appointment_location_url
			||
			$appointment_patients_phone_number_both
		) {

			// Get system settings for location labels
			include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

			?>
			<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2>Make an Appointment</h2>
							<?php

							if ( $appointment_location_url && !$appointment_patients_phone_number_both ) {

								?><p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a>.</p><?php

							} elseif ( !$appointment_location_url && $appointment_patients_phone_number_both  ) {

								?><p>Request an appointment by calling the UAMS&nbsp;Health appointment line at <a href="tel:<?php echo $appointment_patients_phone_number_both; ?>" class="no-break" data-itemtitle="Call the UAMS Health appointment line"><?php echo $appointment_patients_phone_number_both; ?></a>.</p><?php

							} else {

								?><p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:<?php echo $appointment_patients_phone_number_both; ?>" class="no-break" data-itemtitle="Call the UAMS Health appointment line"><?php echo $appointment_patients_phone_number_both; ?></a>.</p><?php

							} // endif

							?>
						</div>
					</div>
				</div>
			</section>
			<?php

		} // endif ( $appointment_location_url || $appointment_patients_phone_number_both )

} // endif ( $appointment_section_show )