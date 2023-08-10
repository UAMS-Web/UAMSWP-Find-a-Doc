<?php
/**
 * Template Name: Location Phone Numbers Variable Definitions
 * 
 * Description: A template part that displays a list of phone numbers using a 
 * definition list element.
 * 
 * Two output options are available: location profile and associated locations.
 * 
 * The location profile output is intended to be used in the contact information 
 * section of a location profile.
 * 
 * The associated locations output is intended to be used in secondary references 
 * to a location such as a location card or the primary location section of a 
 * provider profile.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$location_single_name // System setting for Locations Plural Item Name
 * 	$phone_output_id (ID of location)
 */

// Display phone numbers for associated locations

	if ( $location_phone_link ) {

		?>
		<dl <?php echo $location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '' ?>>
			<?php

			if ( $location_appointments_query ) {

				// IF a patient can schedule an appointment for services rendered at this location...

					?>
					<dt>Appointment Phone Number<?php echo $location_phone_appointments_multiple_query ? 's' : ''; ?></dt>
					<?php

					if (
						$location_new_appointments_phone
						&&
						$location_clinic_phone_query
					) {

						// UAMS location appointments

							?>
							<dd>
								<?php echo $location_new_appointments_phone_link; ?><br/>
								<span class="subtitle"><?php echo $location_appointment_phone_query ? 'New Patients' : 'New and Returning Patients'; ?></span>
							</dd>
							<?php

							if (
								$location_return_appointments_phone
								&&
								$location_appointment_phone_query
							) {

								?>
								<dd>
									<?php echo $location_return_appointments_phone_link; ?><br/>
									<span class="subtitle">Returning Patients</span>
								</dd>
								<?php

							} // endif

					} elseif ( $location_ac_appointments_query ) {

						// Arkansas Children's Primary Care and Specialty Care Appointments

							?>
							<dd>
								<?php echo $location_ac_appointments_primary_link; ?><br/>
								<span class="subtitle">Primary Care</span>
							</dd>
							<dd>
								<?php echo $location_ac_appointments_specialty_link; ?><br/>
								<span class="subtitle">Specialty Care</span>
							</dd>
							<?php

					} else {

						// Display general information number as the appointments number

							?>
							<dd>
								<?php echo $location_phone_link; ?><br/>
								<span class="subtitle">New and Returning Patients</span>
							</dd>
							<?php

					} // endif

			} else {

				// IF a patient cannot schedule an appointment for services rendered at this location...

					?>
					<dt>General Information</dt>
					<dd><?php echo $location_phone_link; ?></dd>
					<?php

			} // endif

			?>
		</dl>
		<?php

	} // endif $location_phone
