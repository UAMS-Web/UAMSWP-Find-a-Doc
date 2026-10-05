<?php
    /**
     *  Template Name: Location Phone Numbers Variable Definitions
     *  Designed for UAMS Find-a-Doc
     *
     *  Required vars:
     *      $phone_output_id (ID of location)
     *      $phone_output
     *          (
     *              valid values for $phone_output:
     *              'location_profile' is for a Location profile's contact information section,
     *              'associated_locations' is for references to associated locations like location cards and a Provider profile's primary location section
     *          )
     */

// Check if this is an Arkansas Children's location
$location_ac_query = get_field('location_ac_query', $phone_output_id);

// Check if a patient can schedule an appointment for services rendered at this location
$location_appointments_query = get_field('location_appointments_query', $phone_output_id); // Get the input
$location_appointments_query = isset($location_appointments_query) ? $location_appointments_query : true; // Fallback value of 'true' for if value is otherwise null

// Data attributes
$location_phone_data_categorytitle = 'Telephone Number';
$location_title = '';
$location_phone_data_itemtitle = '';
$location_title_attr = '';
if ( $phone_output == 'associated_locations' ) {
    $location_title = get_the_title($phone_output_id);
    $location_title_attr = $location_title;
    $location_title_attr = str_replace('"', '\'', $location_title_attr); // Replace double quotes with single quote
    $location_title_attr = str_replace('&#8217;', '\'', $location_title_attr); // Replace right single quote with single quote
    $location_title_attr = htmlentities($location_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
    $location_title_attr = str_replace('&nbsp;', ' ', $location_title_attr); // Convert non-breaking space with normal space
    $location_title_attr = html_entity_decode($location_title_attr); // Convert HTML entities to their corresponding characters
    $location_phone_data_itemtitle = $location_title_attr;
}

// General information phone number
$location_phone = get_field('location_phone', $phone_output_id);
$location_phone_format_dash = format_phone_dash( $location_phone );
$location_phone_link_data_typetitle = '';
if ( $location_appointments_query && ( $phone_output == 'associated_locations' ) ) {
    // IF a patient can schedule an appointment for services rendered at this location...
    // AND IF the output is on references to associated locations like location cards and a Provider profile's primary location section...
    $location_phone_link_data_typetitle = 'Appointment Phone Number for New and Returning Patients';
} else {
    $location_phone_link_data_typetitle = 'Clinic Phone Number';
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
if ( $location_ac_query && $location_appointments_query ) {
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
if ( !$location_ac_query && $location_appointments_query ) {
	// IF this is not an Arkansas Children's location...
    // AND IF a patient can schedule an appointment for services rendered at this location...
	$location_clinic_phone_query = get_field('location_clinic_phone_query', $phone_output_id); // Get the input value (for locations that aren't Arkansas Children's locations)
}
if ( $location_clinic_phone_query && !$location_ac_appointments_query ) {
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
if ( $location_clinic_phone_query && !$location_ac_query ) {
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

// Set variable values only if the output is a Location profile's contact information section
$location_fax = '';
$location_fax_format_dash = '';
$location_fax_link = '';
$location_phone_numbers = '';
if ( $phone_output == 'location_profile' ) {
    // Fax number
    if ( !$location_ac_query ) {
        // IF this is not an Arkansas Children's location...
        $location_fax = get_field('location_fax', $phone_output_id); // Get the fax number
        $location_fax_format_dash = format_phone_dash( $location_fax );
        $location_fax_link = '<a href="tel:' . $location_fax_format_dash . '" class="icon-phone"' . ($location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '') . ($location_phone_data_itemtitle ? ' data-itemtitle="' . $location_phone_data_itemtitle . '"' : '') . ' data-typetitle="Clinic Fax Number">' . $location_fax_format_dash . '</a>'; // Build the anchor element for the fax number
    }

    // Additional phone numbers
    if ( !$location_ac_query ) {
        // IF this is not an Arkansas Children's location...
        $location_phone_numbers = get_field('field_location_phone_numbers', $phone_output_id); // Get the repeater for additional phone numbers
    }
} else {
    $location_fax = '';
    $location_fax_link = '';
    $location_phone_numbers = '';
}

// Display phone numbers on location profile's contact information section
if ( $phone_output == 'location_profile' ) { ?>
    <dl <?php echo $location_phone_data_categorytitle ? 'data-categorytitle="' . $location_phone_data_categorytitle . '"' : '' ?>>
        <?php if ( !empty($location_phone) ) {
        // General Information
        ?>
            <dt>General Information<?php echo ( $location_clinic_phone_query || !$location_appointments_query ) ? '' : ' and Appointments'; ?></dt>
            <dd><?php echo !empty($location_phone_link) ? $location_phone_link : $location_phone; ?></dd>
            <?php isset($phone_schema) ? $phone_schema .= '"telephone": ["'. $location_phone_format_dash .'"
            ' : ''; ?>
        <?php } ?>
        <?php if ( $location_clinic_phone_query && ( !empty($location_new_appointments_phone) || ( !empty($location_return_appointments_phone) && $location_appointment_phone_query ) ) ) {
        // Appointments
        ?>
            <dt>Appointments</dt>
            <?php if ( !empty($location_new_appointments_phone) ) { ?>
                <dd><?php echo !empty($location_new_appointments_phone_link) ? $location_new_appointments_phone_link : $location_new_appointments_phone; ?><?php echo $location_appointment_phone_query ? '<br/><span class="subtitle">New Patients</span>' : ''; ?></dd>
                <?php isset($phone_schema) ? $phone_schema .= ', "'. $location_new_appointments_phone_format_dash .'"
                ' : ''; ?>
            <?php } ?>
            <?php if ( !empty($location_return_appointments_phone) && $location_appointment_phone_query ) { ?>
                <dd><?php echo !empty($location_return_appointments_phone_link) ? $location_return_appointments_phone_link : $location_return_appointments_phone; ?><br/><span class="subtitle">Returning Patients</span></dd>
                <?php isset($phone_schema) ? $phone_schema .= ', "'. $location_return_appointments_phone_format_dash .'"
                ' : ''; ?>
            <?php } ?>
        <?php } elseif ( $location_ac_appointments_query && ( !empty($location_ac_appointments_primary) || !empty($location_ac_appointments_specialty) ) ) {
        // Arkansas Children's Primary Care and Specialty Care Appointments
        ?>
            <dt>Appointments</dt>
            <?php if ( !empty($location_ac_appointments_primary) ) { ?>
                <dd><?php echo !empty($location_ac_appointments_primary_link) ? $location_ac_appointments_primary_link : $location_ac_appointments_primary; ?><br/><span class="subtitle">Primary Care</span></dd>
                <?php isset($phone_schema) ? $phone_schema .= ', "'. $location_ac_appointments_primary_format_dash .'"
                ' : ''; ?>
            <?php } ?>
            <?php if ( !empty($location_ac_appointments_specialty) ) { ?>
                <dd><?php echo !empty($location_ac_appointments_specialty_link) ? $location_ac_appointments_specialty_link : $location_ac_appointments_specialty; ?><br/><span class="subtitle">Specialty Care</span></dd>
                <?php isset($phone_schema) ? $phone_schema .= ', "'. $location_ac_appointments_specialty_format_dash .'"
                ' : ''; ?>
            <?php } ?>
        <?php } ?>
        <?php if ( !empty($location_fax) ) {
        // Fax
        ?>
            <dt>Fax Number</dt>
            <dd><?php echo $location_fax_format_dash; ?></dd>
        <?php } ?>
        <?php if ( $location_phone_numbers ) {
        // Additional phone numbers

            $phone_numbers = $location_phone_numbers;
            while( have_rows('field_location_phone_numbers') ): the_row();
                $title = get_sub_field('location_appointments_text');
                $title_attr = $title;
                $title_attr = str_replace('"', '\'', $title_attr); // Replace double quotes with single quote
                $title_attr = str_replace('&#8217;', '\'', $title_attr); // Replace right single quote with single quote
                $title_attr = htmlentities($title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
                $title_attr = str_replace('&nbsp;', ' ', $title_attr); // Convert non-breaking space with normal space
                $title_attr = html_entity_decode($title_attr); // Convert HTML entities to their corresponding characters
                $phone = get_sub_field('location_appointments_phone');
                $phone_format_dash = format_phone_dash( $phone );
                $text = get_sub_field('location_appointments_additional_text');
        ?>
            <dt><?php echo $title; ?></dt>
            <dd><a href="tel:<?php echo $phone_format_dash; ?>" data-typetitle="Additional Phone Number: <?php echo $title_attr; ?>"><?php echo $phone_format_dash; ?></a><?php echo ($text ? '<br/><span class="subtitle">'. $text .'</span>' : ''); ?></dd>
            <?php if ('' != $phone){
                isset($phone_schema) ? $phone_schema .= ', "'. $phone_format_dash .'"
                ' : '';
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
    <?php isset($phone_schema) ? $phone_schema .= '],' : ''; ?>
<?php } // endif

// Display phone numbers for associated locations
if ( $phone_output == 'associated_locations' ) { ?>
    <?php if ( $location_phone ) { ?>
        <dl <?php echo $location_phone_data_categorytitle ? 'data-categorytitle="' . $location_phone_data_categorytitle . '"' : '' ?>>
            <?php if ( $location_appointments_query ) {
                // IF a patient can schedule an appointment for services rendered at this location...
                ?>
                <dt>Appointment Phone Number<?php echo $location_phone_appointments_multiple_query ? 's' : ''; ?></dt>
                <?php if ( $location_new_appointments_phone && $location_clinic_phone_query ) {
                    // Appointments
                    ?>
                    <dd><?php echo $location_new_appointments_phone_link; ?><?php echo $location_appointment_phone_query ? '<br/><span class="subtitle">New Patients</span>' : '<br/><span class="subtitle">New and Returning Patients</span>'; ?></dd>
                    <?php if ($location_return_appointments_phone && $location_appointment_phone_query) { ?>
                        <dd><?php echo $location_return_appointments_phone_link; ?><br/><span class="subtitle">Returning Patients</span></dd>
                    <?php } ?>
                <?php } elseif ( $location_ac_appointments_query ) {
                    // Arkansas Children's Primary Care and Specialty Care Appointments
                    ?>
                    <dd><?php echo $location_ac_appointments_primary_link; ?><br/><span class="subtitle">Primary Care</span></dd>
                    <dd><?php echo $location_ac_appointments_specialty_link; ?><br/><span class="subtitle">Specialty Care</span></dd>
                <?php } else {
                    // Display general information number as the appointments number
                    ?>
                    <dd><?php echo $location_phone_link; ?><br/><span class="subtitle">New and Returning Patients</span></dd>
                <?php } ?>
            <?php } else {
                // IF a patient cannot schedule an appointment for services rendered at this location...
                ?>
                <dt>General Information</dt>
                <dd><?php echo $location_phone_link; ?></dd>
            <?php } ?>
        </dl>
    <?php } // endif $location_phone ?>
<?php } // endif $phone_output == 'associated_locations'
?>