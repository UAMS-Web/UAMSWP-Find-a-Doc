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
 * 	$phone_output
 * 		(
 * 			valid values for $phone_output:
 * 			'location_profile' is for a Location profile's contact information section,
 * 			'associated_locations' is for references to associated locations like location cards and a Provider profile's primary location section
 * 		)
 */

// Telephone Schema Data

	// Check/define the main telephone schema array

		$schema_telephone = ( isset($schema_telephone) && is_array($schema_telephone) && !empty($schema_telephone) ) ? $schema_telephone : array();

// Check if this is an Arkansas Children's location

	$location_ac_query = get_field('location_ac_query', $phone_output_id);

// Check if a patient can schedule an appointment for services rendered at this location

$location_appointments_query = get_field('location_appointments_query', $phone_output_id); // Get the input
	$location_appointments_query = isset($location_appointments_query) ? $location_appointments_query : true; // Fallback value of 'true' for if value is otherwise null

// Data attributes

	$location_phone_data_categorytitle = 'Telephone Number';
	$location_title = get_the_title($phone_output_id);
	$location_title_attr = uamswp_attr_conversion($location_title);
	$location_phone_data_itemtitle = $location_title_attr;

// General information phone number

	$location_phone = get_field('location_phone', $phone_output_id);
	$location_phone_format_dash = format_phone_dash( $location_phone );
	$location_phone_link_data_typetitle = '';

	if (
		$location_appointments_query
	) {

		// IF a patient can schedule an appointment for services rendered at this location...
		// AND IF the output is on references to associated locations like location cards and a Provider profile's primary location section...
		$location_phone_link_data_typetitle = 'Appointment Phone Number for New and Returning Patients';

	} else {

		$location_phone_link_data_typetitle = $location_single_name . ' Phone Number';
	}

	$location_phone_link = '<a href="tel:' . $location_phone_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ($location_phone_link_data_typetitle ? ' data-typetitle="' . $location_phone_link_data_typetitle . '"' : '') . '>' . $location_phone_format_dash . '</a>'; // Build the anchor element for the general information phone number
	$location_clinic_phone_query = false; // Are there main appointment phone numbers other than the general information phone number?

// Arkansas Children's appointment phone numbers

	$location_ac_appointments_query = false; // Does this Arkansas Children's location have separate phone numbers for primary care appointments and specialty care appointments?
	$location_ac_appointments_primary = ''; // Arkansas Children's appointment phone number for primary care
	$location_ac_appointments_primary_format_dash = '';
	$location_ac_appointments_primary_link = '';
	$location_ac_appointments_specialty = ''; // Arkansas Children's appointment phone number for specialty care
	$location_ac_appointments_specialty_format_dash = '';
	$location_ac_appointments_specialty_link = '';

	if (
		$location_ac_query
		&&
		$location_appointments_query
	) {

		// IF this is an Arkansas Children's location...
		// AND IF a patient can schedule an appointment for services rendered at this location...
		$location_clinic_phone_query = true;
		$location_ac_appointments_query = get_field('location_ac_appointments_query', $phone_output_id); // Get the input

		if ( $location_ac_appointments_query ) {

			// IF this Arkansas Children's location has separate phone numbers for primary care appointments and specialty care appointments...
			$location_ac_appointments_primary = get_field('location_ac_appointments_primary', $phone_output_id); // Get the input
			$location_ac_appointments_primary_format_dash = format_phone_dash( $location_ac_appointments_primary );
			$location_ac_appointments_primary_link = '<a href="tel:' . $location_ac_appointments_primary_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Arkansas Children\'s Primary Care Appointments Phone Number">' . $location_ac_appointments_primary_format_dash . '</a>'; // Build the anchor element for the Arkansas Children's primary care appointments phone number
			$location_ac_appointments_specialty = get_field('location_ac_appointments_specialty', $phone_output_id); // Get the input
			$location_ac_appointments_specialty_format_dash = format_phone_dash( $location_ac_appointments_specialty );
			$location_ac_appointments_specialty_link = '<a href="tel:' . $location_ac_appointments_specialty_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Arkansas Children\'s Specialty Care Appointments Phone Number">' . $location_ac_appointments_specialty_format_dash . '</a>'; // Build the anchor element for the Arkansas Children's specialty care appointments phone number

		} // Otherwise, the single appointments phone number is set below

	}

// Appointment phone number for new (or new AND returning) patients

	$location_new_appointments_phone = ''; // Establishing the variable to be used later for the appointment phone number for (new) patients
	$location_new_appointments_phone_format_dash = '';
	$location_new_appointments_phone_link = ''; // Establishing the variable to be used later for building the anchor element for the appointment phone for (new) patients
	$location_appointment_phone_query = false; // Is there a separate phone number for returning patients?

	if (
		!$location_ac_query
		&&
		$location_appointments_query
	) {
		// IF this is not an Arkansas Children's location...
		// AND IF a patient can schedule an appointment for services rendered at this location...
		$location_clinic_phone_query = get_field('location_clinic_phone_query', $phone_output_id); // Get the input value (for locations that aren't Arkansas Children's locations)
	}

	if (
		$location_clinic_phone_query
		&&
		!$location_ac_appointments_query
	) {
		// IF there are main appointment phone numbers other than the general information phone number...
		// AND IF this is not set as an Arkansas Children's location with separate phone numbers for primary care appointments and specialty care appointments...
		$location_new_appointments_phone = get_field('location_new_appointments_phone', $phone_output_id); // Get the appointment phone number for (new) patients?
		$location_new_appointments_phone_format_dash = format_phone_dash( $location_new_appointments_phone );
		$location_new_appointments_phone_link = '<a href="tel:' . $location_new_appointments_phone_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Appointment Phone Number for New' . ($location_appointment_phone_query ? '' : ' and Returning') . ' Patients">' . $location_new_appointments_phone_format_dash . '</a>'; // Build the anchor element for the appointment phone for (new) patients
	}

// Appointment phone number for returning patients

	$location_return_appointments_phone = ''; // Establishing the variable to be used later for the appointment phone number for returning patients
	$location_return_appointments_phone_format_dash = '';
	$location_return_appointments_phone_link = ''; // Establishing the variable to be used later for the anchor element for the appointment phone for returning patients

	if (
		$location_clinic_phone_query
		&&
		!$location_ac_query
	) {

		// IF there is a a separate appointment phone number for (new) patients...
		// AND IF this isn't an Arkansas Children's location...
		$location_appointment_phone_query = get_field('field_location_appointment_phone_query', $phone_output_id); // Check if there is a separate appointment phone number for returning patients

	}

	if ( $location_appointment_phone_query ) {

		// IF there is a a separate appointment phone number for returning patients...
		$location_return_appointments_phone = get_field('location_return_appointments_phone', $phone_output_id); // Get the appointment phone number for returning patients
		$location_return_appointments_phone_format_dash = format_phone_dash( $location_return_appointments_phone );
		$location_return_appointments_phone_link = '<a href="tel:' . $location_return_appointments_phone_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Appointment Phone Number for Returning Patients">' . $location_return_appointments_phone_format_dash . '</a>'; // Build the anchor element for the appointment phone number for returning patients

	}

// Check if there are multiple appointment phone numbers

	$location_phone_appointments_multiple_query = false;

	if ( $location_appointment_phone_query || $location_ac_appointments_query ) {
		$location_phone_appointments_multiple_query = true;
	}

// Fax number

	if ( !$location_ac_query ) {

		// IF this is not an Arkansas Children's location...

		$location_fax = get_field('location_fax', $phone_output_id); // Get the fax number
		$location_fax_format_dash = format_phone_dash( $location_fax );
		$location_fax_link = '<a href="tel:' . $location_fax_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Clinic Fax Number">' . $location_fax_format_dash . '</a>'; // Build the anchor element for the fax number

	} else {

		$location_fax = '';
		$location_fax_format_dash = '';
		$location_fax_link = '';

	}

// Additional phone numbers

	$location_phone_numbers = '';

	// Set variable values only if the output is a Location profile

		if ( $phone_output == 'location_profile' ) {

			if ( !$location_ac_query ) {

				// IF this is not an Arkansas Children's location...
				$location_phone_numbers = get_field('field_location_phone_numbers', $phone_output_id); // Get the repeater for additional phone numbers

			} // endif ( !$location_ac_query )

		} // endif ( $phone_output == 'location_profile' )

// Display phone numbers for associated locations

	if ( $phone_output == 'associated_locations' ) {

		if ( $location_phone ) {

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

	} // endif ( $phone_output == 'associated_locations' )
