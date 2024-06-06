<?php
/*
 *
 *
 *
 */
function uamswp_add_gmb_export_page() {
    add_submenu_page( 'fad-settings', 'UAMSWP CSV Export', 'CSV Export', 'manage_options', 'uamswp-gmb-export', 'uamswp_fad_gmb_export_page', 90 );
}
add_action( 'admin_menu', 'uamswp_add_gmb_export_page', 105 );

function uamswp_fad_gmb_export_page() {
    ?>
    <h1><?php echo $GLOBALS['title'] ?></h1>
    <p>These will take a little time to generate, please be patient.</p>
    <h2 id="csv-header-doximity">U.S. News Doctor Finder Profiles and Doximity Profiles</h2>
    <p>Generate a spreadsheet for updating U.S. News Doctor Finder Profiles and Doximity Profiles.</p>
    <p>Copy the data from the generated CSV into the <a href="https://app.box.com/s/sufb1jpvhzcw1stzez0ch0t7i88teovi" title="Doximity Template" target="_blank" download>U.S. News & Doximity upload template</a>. Email that populated template file to <a href="mailto:hospitals@doximity.com">hospitals@doximity.com</a> to have the profiles updated.</p>
    <p><a aria-describedby="csv-header-doximity" class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_doximity_csv&_wpnonce=<?php echo wp_create_nonce( 'download_doximity_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2 id="csv-header-gmb-providers">Google My Business Profiles &mdash; UAMS Clinical Providers</h2>
    <p>Generate a spreadsheet for importing published providers as business profiles in Google My Business, both to create new business profiles and to update existing business profiles. A unique business profile will be created for each provider at each of their practice locations.</p>
    <p>Each URL in the spreadsheet is automatically tagged using a UTM structure crafted to indicate that the web traffic came from a particular link within the specific provider's business profile for a specific practice location.</p>
    <p>For help with bulk upload spreadsheets for Business Profiles, visit <a href="https://support.google.com/business/topic/4596653" title="Google Business Profile Help, Add 10 or more businesses" target="_blank">https://support.google.com/business/topic/4596653</a>.</p>
    <p><a aria-describedby="csv-header-gmb-providers" class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_gmb_provider_csv&_wpnonce=<?php echo wp_create_nonce( 'download_gmb_provider_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2 id="csv-header-gmb-locations">Google My Business Profiles &mdash; UAMS Clinical Locations</h2>
    <p>Generate a spreadsheet for importing published locations as business profiles in Google My Business, both to create new business profiles and to update existing business profiles. A unique business profile will be created for each location, whether it is a top-level location or a child location.</p>
    <p>Each URL in the spreadsheet is automatically tagged using a UTM structure crafted to indicate that the web traffic came from a particular link within the specific location's business profile.</p>
    <p>For help with bulk upload spreadsheets for Business Profiles, visit <a href="https://support.google.com/business/topic/4596653" title="Google Business Profile Help, Add 10 or more businesses" target="_blank">https://support.google.com/business/topic/4596653</a>.</p>
    <p><a aria-describedby="csv-header-gmb-locations" class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_gmb_location_csv&_wpnonce=<?php echo wp_create_nonce( 'download_gmb_location_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2 id="csv-header-mychart">MyChart Provider List</h2>
    <p>Generate a spreadsheet for the MyChart team to use when associating provider profile URLs with the relevant providers in Epic/MyChart, so that users can click through to the provider's profile from within MyChart.</p>
    <p>Each URL is automatically tagged using a UTM structure crafted to indicate that the web traffic came from the specific provider's record within MyChart without passing through any protected health information (PHI).</p>
    <p><a aria-describedby="csv-header-mychart" class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_mychart_csv&_wpnonce=<?php echo wp_create_nonce( 'download_mychart_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <?php
}

// Add action hook only if action=download_doximity_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_doximity_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'doximity_csv_export' );
}

// Add action hook only if action=download_gmb_provider_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_gmb_provider_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'gmb_provider_csv_export' );
}

// Add action hook only if action=download_gmb_provider_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_gmb_location_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'gmb_location_csv_export' );
}

// Add action hook only if action=download_mychart_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_mychart_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'mychart_csv_export' );
}

function doximity_csv_export() {
    // Check for current user privileges
    if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_doximity_csv' ) ) {
        die( 'Security check error' );
    }

    ob_start();

    // Custom WP_Query args
    $args = array(
        "post_type" => "provider",
        "post_status" => "publish",
        "posts_per_page" => "-1", // Set for all
        "orderby" => "title",
        "order" => "ASC",
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        $table_head = array();
        $table_head[0] = "NPI Number";
        $table_head[1] = "First Name";
        $table_head[2] = "Last Name";
        $table_head[3] = "Credentials (MD or DO)";
        $table_head[4] = "Email Address";
        $table_head[5] = "Facility Name";
        $table_head[6] = "Office Address 1";
        $table_head[7] = "Office Address 2";
        $table_head[8] = "Office City";
        $table_head[9] = "Office State";
        $table_head[10] = "Office Zip";
        $table_head[11] = "Phone";
        $table_head[12] = "Fax";
        $table_head[13] = "Specialty";
        $table_head[14] = "Sub-Specialty";

        $table_body = array();
        $row = array();
        while( $query->have_posts() ) : $query->the_post();
            $post_id = get_the_ID();

            // First, check if provider has desired degree
            $degree_md = array( // list valid versions of MD
                'M.D.'
            );
            $degree_do = array( // list valid versions of DO
                'D.O.'
            );
            $degree_np = array( // list valid versions of NP
                'CNP',
                'FNP-C'
            );
            $degree_pa = array( // list valid versions of PA
                'PA'
            );
            $degrees = get_field('physician_degree',$post_id);
            $degree_valid = '';
            $d = 1;
            $npi = get_field('physician_npi',$post_id);
            $npi_valid = false;
            if ( !empty($npi) && '0' != $npi ) {
                $npi_valid = true;
            }
            if ( $degrees ) {
                foreach( $degrees as $degree ):
                    $degree_term = get_term( $degree, 'degree');
                    $degree_name = $degree_term->name;
                    if ( ( 2 > $d ) && ( in_array($degree_name, $degree_md) || in_array($degree_name, $degree_do) || in_array($degree_name, $degree_np) || in_array($degree_name, $degree_pa) ) ) {
                        $degree_valid = $degree_name;
                        if (in_array($degree_valid, $degree_md)) {
                            $degree_valid = 'MD';
                        } elseif (in_array($degree_valid, $degree_do)) {
                            $degree_valid = 'DO';
                        } elseif (in_array($degree_valid, $degree_np)) {
                            $degree_valid = 'NP';
                        } elseif (in_array($degree_valid, $degree_pa)) {
                            $degree_valid = 'PA';
                        } else {
                            $degree_valid = '';
                        }
                        $d++;
                    }
                endforeach;
            }
            // Check if there is a hospital affiliation
            $affiliation_valid = false;
            $affiliations = get_field('physician_affiliation',$post_id);
            if ( !empty($affiliations) ) {
                $affiliation_valid = true;
            }
            // Check for valid locations
            $locations = get_field('physician_locations',$post_id);
            $location_valid = false;
            foreach( $locations as $location ) {
                if ( get_post_status ( $location ) == 'publish' ) {
                    $location_valid = true;
                    $break;
                }
            }
            // Create the table
            if ( $degree_valid && $npi_valid && $affiliation_valid && $location_valid ) {
                // NPI Number field
                    $row[0] = $npi; // only display value if value is not empty or zero

                // First Name field
                    $first_name = get_field('physician_first_name',$post_id);
                    $row[1] = html_entity_decode($first_name);

                // Last Name field
                    $last_name = get_field('physician_last_name',$post_id);
                    $row[2] = html_entity_decode($last_name);

                // Credentials (MD or DO) field
                    $row[3] = $degree_valid;

                // Email Address field
                    $e = 1;
                    $contact_type = '';
                    $contact_info = '';
                    $email_list = '';
                    if( have_rows('physician_contact_information',$post_id) ):
                        while ( have_rows('physician_contact_information',$post_id) ) : the_row();
                            $contact_type = get_sub_field('type');
                            $contact_info = get_sub_field('information');
                            if ( $contact_type == 'email' && 2 > $e ) { // Only display the first instance of an email row
                                $email_list = $contact_info;
                                $e++;
                            }
                        endwhile;
                    endif;
                    $row[4] = $email_list;

                // Facility Name field
                    $affiliation_list = '';
                    $i = 1;
                    if ( $affiliations ) {
                        foreach( $affiliations as $affiliation ):
                            $affiliation_object = get_term( $affiliation, 'affiliation');
                            $affiliation_aha_name = get_field('affiliation_name', $affiliation_object) ?: $affiliation_object->name;
                            $affiliation_aha_id = get_field('affiliation_id', $affiliation_object);
                            $affiliation_list .= $affiliation_aha_name . ( $affiliation_aha_id ? ' (AHA ID: ' . $affiliation_aha_id . ')' : '');
                            if( count($affiliations) > $i ) {
                                $affiliation_list .= ', ';
                            }
                            $i++;
                        endforeach;
                    }
                    $row[5] = $affiliation_list ? $affiliation_list : '';

                // Office Address 1 field

                    // Get primary appointment location information
                    $l = 1;
                    $primary_appointment_address_1 = '';
                    $primary_appointment_address_2 = '';
                    $primary_appointment_city = '';
                    $primary_appointment_state = '';
                    $primary_appointment_zip = '';
                    $primary_appointment_phone = '';
                    $primary_appointment_fax = '';
                    if( $locations && $location_valid ) {
                        foreach( $locations as $location ) {
                            if ( 2 > $l ){
                                if ( get_post_status ( $location ) == 'publish' ) {
                                    $primary_appointment_address_1 = get_field( 'location_address_1', $location ); // Get the street address

                                    // Construct the value for Address 2
                                    $primary_appointment_building = get_field('location_building', $location ); // Get building taxonomy input
                                    if ($primary_appointment_building) {
                                        $building = get_term($primary_appointment_building, "building"); // Get building object
                                        $building_slug = $building->slug; // Get the building slug
                                        $building_name = $building->name; // Get the building name
                                    }
                                    $primary_appointment_floor_object = get_field_object('location_building_floor', $location ); // Get floor object from input
                                        $primary_appointment_floor_value = $primary_appointment_floor_object['value']; // Get the floor selection value
                                        $primary_appointment_floor_label = $primary_appointment_floor_value != "0" ? $primary_appointment_floor_object['choices'][ $primary_appointment_floor_value ] : ''; // If the floor value is not 0, get the floor selection label
                                    $primary_appointment_suite = get_field('location_suite', $location ); // Get the suite input
                                    $primary_appointment_address_2_arr = Array(); // Create empty array for constructing Address 2 value
                                    if ( $primary_appointment_building && $building_slug != '_none' && isset($building_name) && !empty($building_name) ) {
                                        // If the building input has a value
                                        // and if the chosen building isn't 'None'
                                        // and if the building's name exists...
                                        $primary_appointment_address_2_arr[] = $building_name; // Add the building name to the Address 2 list
                                    }
                                    if ( $primary_appointment_floor_value != "0" && isset($primary_appointment_floor_label) & !empty($primary_appointment_floor_label) ) {
                                        // If the building floor isn't set to 'Single-Story Building'
                                        // and if the floor's label exists...
                                        $primary_appointment_address_2_arr[] = $primary_appointment_floor_label; // Add the building floor to the Address 2 list
                                    }
                                    if ( isset($primary_appointment_suite) & !empty($primary_appointment_suite) ) {
                                        // If the suite exists...
                                        $primary_appointment_address_2_arr[] = $primary_appointment_suite; // Add the suite to the Address 2 list
                                    }
                                    $primary_appointment_address_2 = implode(', ', $primary_appointment_address_2_arr); // Create a comma-separated list from the array
                                    $primary_appointment_address_2_deprecated = get_field('location_address_2', $location ); // Get the deprecated Address 2 input
                                    if ( !$primary_appointment_address_2 ) {
                                        // If the non-deprecated Address 2 value doesn't exist...
                                        $primary_appointment_address_2 = $primary_appointment_address_2_deprecated; // Set the Address 2 value using the deprecated input value
                                    }

                                    // Get remaining address values
                                    $primary_appointment_city = get_field( 'location_city', $location ); // Get the city
                                    $primary_appointment_state = get_field( 'location_state', $location ); // Get the state
                                    $primary_appointment_zip = get_field( 'location_zip', $location ); // Get the ZIP code
                                    $primary_appointment_phone = get_field( 'location_phone', $location ); // Get the general clinic phone number
                                    $primary_appointment_fax = get_field( 'location_fax', $location ); // Get the clinic fax number
                                    $l++;
                                }
                            }
                        } // endforeach
                    }
                    $row[6] = $primary_appointment_address_1 ? html_entity_decode($primary_appointment_address_1) : '';

                // Office Address 2 field
                    $row[7] = $primary_appointment_address_2 ? html_entity_decode($primary_appointment_address_2) : '';

                // Office City field
                    $row[8] = $primary_appointment_city ? $primary_appointment_city : '';

                // Office State field
                    $row[9] = $primary_appointment_state ? $primary_appointment_state : '';

                // Office Zip field
                    $row[10] = $primary_appointment_zip ? $primary_appointment_zip : '';

                // Phone field
                    $row[11] = $primary_appointment_phone ? $primary_appointment_phone : '';

                // Fax field
                    $row[12] = $primary_appointment_fax ? $primary_appointment_fax : '';

                // Specialty field
                    // Intentionally left blank
                    $row[13] = '';

                // Sub-Specialty field
                    // Intentionally left blank
                    $row[14] = '';

            }
        $table_body[] = $row;
        endwhile;
    endif;

    ob_end_clean ();
    $filename = 'Doximity_List_' . time() . '.csv';
    $delimiter=",";
    $fh = @fopen( 'php://output', 'w' );
    fputs( $fh, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' ); // tells browser to download
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Pragma: no-cache' ); // no cache
    header( 'Expires: 0' ); // expire date
    fputcsv( $fh, $table_head, $delimiter );
    foreach ( $table_body as $data_row )
    {
        fputcsv( $fh, $data_row, $delimiter );
    }


    exit();

}

function gmb_provider_csv_export() {

    // Check for current user privileges
    if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_gmb_provider_csv' ) ) {
        die( 'Security check error' );
    }

    ob_start();

    // Custom WP_Query args
    $args = array(
        "post_type" => "provider",
        "post_status" => "publish",
        "posts_per_page" => "-1", // -1 => Set for all
        "orderby" => "title",
        "order" => "ASC",
        // 'paged' => get_query_var( 'paged' ),
    );

    global $wp_query;
    $wp_query = new WP_Query( $args );

    if ( $wp_query->have_posts() ) :
        $table_head = array();
        $table_head[0]    =  'Store code';
        $table_head[1]    =  'Business name';
        $table_head[2]    =  'Address line 1';
        $table_head[3]    =  'Address line 2';
        $table_head[4]    =  'Address line 3';
        $table_head[5]    =  'Address line 4';
        $table_head[6]    =  'Address line 5';
        // $table_head[]    =  'Sub-locality'; // Intentionally left blank
        $table_head[7]    =  'Locality';
        $table_head[8]    =  'Administrative area';
        $table_head[9]   =  'Country / Region';
        $table_head[10]   =  'Postal code';
        $table_head[11]   =  'Latitude';
        $table_head[12]   =  'Longitude';
        $table_head[13]   =  'Primary phone';
        // $table_head[]   =  'Additional phones'; // Intentionally left blank
        $table_head[14]   =  'Website';
        $table_head[15]   =  'Primary category';
        $table_head[16]   =  'Additional categories';
        // $table_head[]   =  'Sunday hours'; // Intentionally left blank
        // $table_head[]   =  'Monday hours'; // Intentionally left blank
        // $table_head[]   =  'Tuesday hours'; // Intentionally left blank
        // $table_head[]   =  'Wednesday hours'; // Intentionally left blank
        // $table_head[]   =  'Thursday hours'; // Intentionally left blank
        // $table_head[]   =  'Friday hours'; // Intentionally left blank
        // $table_head[]   =  'Saturday hours'; // Intentionally left blank
        // $table_head[]   =  'Special hours'; // Intentionally left blank
        $table_head[17]   =  'From the business';
        // $table_head[]   =  'Opening date'; // Intentionally left blank
        $table_head[18]   =  'Logo photo';
        $table_head[19]   =  'Cover photo';
        // $table_head[]   =  'Other photos'; // Intentionally left blank
        $table_head[20]   =  'Labels';
        // $table_head[]   =  'AdWords location extensions phone'; // Intentionally left blank
        // $table_head[]   =  'Accessibility: Assisted listening devices (has_assisted_listening_devices)';
        // $table_head[]   =  'Accessibility: Assistive hearing loop (has_hearing_loop)';
        // $table_head[]   =  'Accessibility: Passenger loading area (has_passenger_loading_area)';
        $table_head[21]   =  'Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)';
        $table_head[22]   =  'Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)';
        // $table_head[]   =  'Accessibility: Wheelchair accessible parking lot (has_wheelchair_accessible_parking)';
        $table_head[23]   =  'Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)';
        // $table_head[]   =  'Accessibility: Wheelchair accessible seating (has_wheelchair_accessible_seating)';
        // $table_head[]   =  'Accessibility: Wheelchair rental (wheelchair_rental_offerings): Motorized (motorized_wheelchairs)';
        // $table_head[]   =  'Accessibility: Wheelchair rental (wheelchair_rental_offerings): Non-motorized (non_motorized_wheelchairs)';
        // $table_head[]   =  'Activities: Accepting food donations (accepts_non_monetary_donations)';
        // $table_head[]   =  'Activities: Accepting monetary donations (accepts_monetary_donations)';
        // $table_head[]   =  'Activities: Accepting new volunteers (accepts_new_volunteers)';
        // $table_head[]   =  'Activities: Bicycle rental (has_bicycles_for_rent)';
        // $table_head[]   =  'Amenities: Air conditioning (has_air_conditioning)
        // $table_head[]   =  'Amenities: Airport shuttle (has_airport_shuttle)';
        // $table_head[]   =  'Amenities: All-inclusive (has_all_inclusive)';
        // $table_head[]   =  'Amenities: Baggage storage (has_baggage_storage)';
        // $table_head[]   =  'Amenities: Bar onsite (has_bar_onsite)';
        // $table_head[]   =  'Amenities: Beach access (has_beach_access)';
        // $table_head[]   =  'Amenities: Business center (has_business_center)';
        // $table_head[]   =  'Amenities: Cellular service (has_cellular_service)';
        // $table_head[]   =  'Amenities: Child care (has_child_care)';
        // $table_head[]   =  'Amenities: Concierge (has_concierge)';
        // $table_head[]   =  'Amenities: Convenience store (has_onsite_convenience_store)';
        // $table_head[]   =  'Amenities: Currency exchange (has_currency_exchange)';
        // $table_head[]   =  'Amenities: Dogs allowed (welcomes_dogs)';
        // $table_head[]   =  'Amenities: Fitness center (has_fitness_center)';
        // $table_head[]   =  'Amenities: Free breakfast (has_free_breakfast)';
        // $table_head[]   =  'Amenities: Gender-neutral restroom (has_restroom_unisex)';
        // $table_head[]   =  'Amenities: Golf course (has_golf_course)';
        // $table_head[]   =  'Amenities: Good for kids (welcomes_children)';
        // $table_head[]   =  'Amenities: High chairs (has_high_chairs)';
        // $table_head[]   =  'Amenities: Hot tub (has_hot_tub)';
        // $table_head[]   =  'Amenities: Laundry service (has_laundry_service)';
        // $table_head[]   =  'Amenities: Mechanic (has_mechanic)';
        // $table_head[]   =  'Amenities: Parking (parking_offerings): Free (free_parking)';
        // $table_head[]   =  'Amenities: Parking (parking_offerings): Paid (paid_parking)';
        // $table_head[]   =  'Amenities: Pets welcome (welcomes_pets)';
        // $table_head[]   =  'Amenities: Picnic tables (has_picnic_tables)';
        // $table_head[]   =  'Amenities: Public restroom (has_restroom_public)';
        // $table_head[]   =  'Amenities: Restaurant (has_restaurant)';
        $table_head[24]   =  'Amenities: Restroom (has_restroom)';
        // $table_head[]   =  'Amenities: Room service (has_room_service)';
        // $table_head[]   =  'Amenities: Sauna (has_sauna)';
        // $table_head[]   =  'Amenities: Slides (has_playground_slides)';
        // $table_head[]   =  'Amenities: Smoke-free place (is_smoke_free_property)';
        // $table_head[]   =  'Amenities: Spa (has_spa)';
        // $table_head[]   =  'Amenities: Stadium seating (has_stadium_seating)';
        // $table_head[]   =  'Amenities: Swimming pool (swimming_pool_offerings): Indoor (indoor_pool)';
        // $table_head[]   =  'Amenities: Swimming pool (swimming_pool_offerings): Outdoor (outdoor_pool)';
        // $table_head[]   =  'Amenities: Swings (has_playground_swings)';
        // $table_head[]   =  'Crowd: Family-friendly (welcomes_families)';
        // $table_head[]   =  'Crowd: LGBTQ+ friendly (welcomes_lgbtq)';
        // $table_head[]   =  'Crowd: Transgender safespace (is_transgender_safespace)';
        // $table_head[]   =  'Deities Represented: Brahma (has_deity_brahma_represented)';
        // $table_head[]   =  'Deities Represented: Durga (has_deity_durga_represented)';
        // $table_head[]   =  'Deities Represented: Hanuman (has_deity_hanuman_represented)';
        // $table_head[]   =  'Deities Represented: Krishna (has_deity_krishna_represented)';
        // $table_head[]   =  'Deities Represented: Lakshmi (has_deity_lakshmi_represented)';
        // $table_head[]   =  'Deities Represented: Mahavira (has_deity_mahavira_represented)';
        // $table_head[]   =  'Deities Represented: Neminatha (has_deity_neminatha_represented)';
        // $table_head[]   =  'Deities Represented: Parshvanatha (has_deity_parshvanatha_represented)';
        // $table_head[]   =  'Deities Represented: Rama (has_deity_rama_represented)';
        // $table_head[]   =  'Deities Represented: Rishabhanatha (has_deity_rishabhanatha_represented)';
        // $table_head[]   =  'Deities Represented: Sai Baba (has_deity_sai_baba_represented)';
        // $table_head[]   =  'Deities Represented: Shiva (has_deity_shiva_represented)';
        // $table_head[]   =  'Deities Represented: Vishnu (has_deity_vishnu_represented)';
        // $table_head[]   =  'Dining options: Breakfast (serves_breakfast)';
        // $table_head[]   =  'Dining options: Brunch (serves_brunch)';
        // $table_head[]   =  'Dining options: Catering (has_catering)';
        // $table_head[]   =  'Dining options: Counter service (has_counter_service)';
        // $table_head[]   =  'Dining options: Dessert (serves_dessert)';
        // $table_head[]   =  'Dining options: Dinner (serves_dinner)';
        // $table_head[]   =  'Dining options: Lunch (serves_lunch)';
        // $table_head[]   =  'Dining options: Outside food allowed (allows_outside_food)';
        // $table_head[]   =  'Dining options: Pay ahead (has_order_and_pay_ahead)';
        // $table_head[]   =  'Dining options: Seating (has_seating)';
        // $table_head[]   =  'Emergency help: Accepts donations (accepts_donations_during_crisis)';
        // $table_head[]   =  'Emergency help: Employs refugees (offers_employment_during_crisis)';
        // $table_head[]   =  'Emergency help: Needs volunteers (needs_volunteers_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers accommodation for refugees (offers_refuge_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers free legal help (offers_free_legal_help_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers free products or services (offers_free_products_services_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers transportation of goods or people (offers_transportation_during_crisis)';
        // $table_head[]   =  'Exams: CAT (offers_cat_exam_prep)';
        // $table_head[]   =  'Exams: CBSE Board (offers_cbse_board_exam_prep)';
        // $table_head[]   =  'Exams: CTET (offers_ctet_exam_prep)';
        // $table_head[]   =  'Exams: Civil Services (offers_civil_services_exam_prep)';
        // $table_head[]   =  'Exams: GATE (offers_gate_exam_prep)';
        // $table_head[]   =  'Exams: IBPS Clerk (offers_ibps_clerk_exam_prep)';
        // $table_head[]   =  'Exams: IBPS RBB (offers_ibps_rrb_exam_prep)';
        // $table_head[]   =  'Exams: ICSE Board (offers_icse_board_exam_prep)';
        // $table_head[]   =  'Exams: JEE (offers_jee_exam_prep)';
        // $table_head[]   =  'Exams: JNVST (offers_jnvst_exam_prep)';
        // $table_head[]   =  'Exams: LIC AAO (offers_lic_aao_exam_prep)';
        // $table_head[]   =  'Exams: NEET (offers_neet_exam_prep)';
        // $table_head[]   =  'Exams: SBI Clerk (offers_sbi_clerk_exam_prep)';
        // $table_head[]   =  'Exams: SSC CGL (offers_ssc_cgl_exam_prep)';
        // $table_head[]   =  'Exams: SSC Government (offers_ssc_government_exam_prep)';
        // $table_head[]   =  'Exams: UGC Net (offers_ugc_net_exam_prep)';
        // $table_head[]   =  'Exams: UPSC Government (offers_upsc_government_exam_prep)';
        // $table_head[]   =  'From the business: Identifies as Asian-owned (is_owned_by_asian)';
        // $table_head[]   =  'From the business: Identifies as Black-owned (is_black_owned)'; // Intentionally left blank
        // $table_head[]   =  'From the business: Identifies as LGBTQ+ owned (is_owned_by_lgbtq)';
        // $table_head[]   =  'From the business: Identifies as Latino-owned (is_owned_by_latinx)';
        // $table_head[]   =  'From the business: Identifies as veteran-led (is_owned_by_veterans)'; // Intentionally left blank
        // $table_head[]   =  'From the business: Identifies as women-led (is_owned_by_women)'; // Intentionally left blank
        // $table_head[]   =  'Getting here: 24-hour transit available (has_transit_24_hours)';
        // $table_head[]   =  'Highlights: 3D movies (has_movies_3D)';
        // $table_head[]   =  'Highlights: Active military discounts (has_discounts_for_active_military)';
        // $table_head[]   =  'Highlights: All you can drink (has_all_you_can_drink)';
        // $table_head[]   =  'Highlights: Bar games (has_bar_games)';
        // $table_head[]   =  'Highlights: COVID-19 testing center (is_covid_19_bool_1)';
        // $table_head[]   =  'Highlights: Cabaret (has_cabaret)';
        // $table_head[]   =  'Highlights: Fireplace (has_fireplace)';
        // $table_head[]   =  'Highlights: Karaoke (has_karaoke_nights)';
        // $table_head[]   =  'Highlights: Live music (has_live_music)';
        // $table_head[]   =  'Highlights: Live performances (has_live_performances)';
        // $table_head[]   =  'Highlights: Play area (has_area_play)';
        // $table_head[]   =  'Highlights: Rooftop seating (has_seating_rooftop)';
        // $table_head[]   =  'Highlights: Serves local specialty (local_specialty)';
        // $table_head[]   =  'Highlights: Showing the World Cup (offers_fifa_world_cup_broadcast)';
        // $table_head[]   =  'Highlights: Sports (suitable_for_watching_sports)';
        // $table_head[]   =  'Highlights: Trivia night (has_trivia_night)';
        // $table_head[]   =  'Lodging options: Family rooms (has_family_rooms)';
        // $table_head[]   =  'Offerings: Alcohol (serves_alcohol)';
        // $table_head[]   =  'Offerings: All you can eat (has_all_you_can_eat_always)';
        // $table_head[]   =  'Offerings: Assembly service (has_service_assembly)';
        // $table_head[]   =  'Offerings: Beer (serves_beer)';
        // $table_head[]   =  'Offerings: Bike storage (has_secure_bicycle_storage)';
        // $table_head[]   =  'Offerings: Braille menu (has_braille_menu)';
        // $table_head[]   =  'Offerings: Buys used goods (buys_goods_used)';
        // $table_head[]   =  'Offerings: Car rental (has_car_rental)';
        // $table_head[]   =  'Offerings: Car wash (has_car_wash)';
        // $table_head[]   =  'Offerings: Cash advance (has_cash_advance)';
        // $table_head[]   =  'Offerings: Check cashing (has_check_cashing)';
        // $table_head[]   =  'Offerings: Cocktails (serves_cocktails)';
        // $table_head[]   =  'Offerings: Coffee (serves_coffee)';
        // $table_head[]   =  'Offerings: Comfort food (serves_comfort_food)';
        // $table_head[]   =  'Offerings: Dancing (has_dancing)';
        // $table_head[]   =  'Offerings: Diesel gas (sells_gas_diesel)';
        // $table_head[]   =  'Offerings: Drive-through (has_drive_through_covid_19_testing)';
        // $table_head[]   =  'Offerings: Ethanol-free gas (sells_gas_ethanol_free)';
        // $table_head[]   =  'Offerings: Food (serves_food)';
        // $table_head[]   =  'Offerings: Food at bar (serves_food_at_bar)';
        // $table_head[]   =  'Offerings: Free air (has_free_air)';
        // $table_head[]   =  'Offerings: Free water refills (has_free_water_refills)';
        // $table_head[]   =  'Offerings: Full service gas (has_full_service_gas)';
        // $table_head[]   =  'Offerings: Grilling (allows_grilling)';
        // $table_head[]   =  'Offerings: Halal food (serves_halal_food)';
        // $table_head[]   =  'Offerings: Happy hour drinks (serves_happy_hour_drinks)';
        // $table_head[]   =  'Offerings: Happy hour food (serves_happy_hour_food)';
        // $table_head[]   =  'Offerings: Hard liquor (serves_liquor)';
        // $table_head[]   =  'Offerings: Kids' menu (has_childrens_menu)';
        // $table_head[]   =  'Offerings: Kids' shoes (sells_shoes_for_children)';
        // $table_head[]   =  'Offerings: Kids' tours (has_tours_for_children)';
        // $table_head[]   =  'Offerings: Kids' toys (sells_toys_for_children)';
        // $table_head[]   =  'Offerings: Late-night food (serves_late_night_food)';
        // $table_head[]   =  'Offerings: Matinees (has_matinees)';
        // $table_head[]   =  'Offerings: Men's clothing (sells_clothing_for_men)';
        // $table_head[]   =  'Offerings: Men's shoes (sells_shoes_for_men)';
        // $table_head[]   =  'Offerings: Oil change (has_oil_change)';
        // $table_head[]   =  'Offerings: Organic dishes (serves_organic)';
        // $table_head[]   =  'Offerings: Organic products (sells_organic_products)';
        // $table_head[]   =  'Offerings: Passport photos (has_onsite_passport_photos)';
        // $table_head[]   =  'Offerings: Prepared foods (sells_food_prepared)';
        // $table_head[]   =  'Offerings: Repair services (has_service_repair)';
        // $table_head[]   =  'Offerings: Salad bar (has_salad_bar)';
        // $table_head[]   =  'Offerings: Service guarantee (has_service_guarantee)';
        // $table_head[]   =  'Offerings: Sing-along screenings (has_sing_alongs)';
        // $table_head[]   =  'Offerings: Small plates (serves_small_plates)';
        // $table_head[]   =  'Offerings: Tent camping (allows_camping_tent)';
        // $table_head[]   =  'Offerings: Used goods (sells_goods_used)';
        // $table_head[]   =  'Offerings: Vegan options (serves_vegan)';
        // $table_head[]   =  'Offerings: Vegetarian options (serves_vegetarian)';
        // $table_head[]   =  'Offerings: Wine (serves_wine)';
        // $table_head[]   =  'Offerings: Women's clothing (sells_clothing_for_women)';
        // $table_head[]   =  'Offerings: Women's shoes (sells_shoes_for_women)';
        // $table_head[]   =  'Payments: Cash-only (requires_cash_only)';
        // $table_head[]   =  'Payments: Checks (pay_check)';
        // $table_head[]   =  'Payments: Cheque Apetiz (accepts_cheque_apetiz_meal_voucher)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)';
        // $table_head[]   =  'Payments: Debit cards (pay_debit_card)';
        // $table_head[]   =  'Payments: Google Pay (pay_mobile_tez)';
        // $table_head[]   =  'Payments: Meal coupons (accepts_meal_coupons)';
        // $table_head[]   =  'Payments: NFC mobile payments (pay_mobile_nfc)';
        // $table_head[]   =  'Payments: Ticket Restaurant (accepts_ticket_restaurant_meal_voucher)';
        // $table_head[]   =  'Payments: VR (accepts_vr_meal_voucher)';
        // $table_head[]   =  'Planning: Accepts new patients (accepts_new_patients)';
        // $table_head[]   =  'Planning: Accepts reservations (accepts_reservations)';
        $table_head[25]   =  'Planning: Appointment required (requires_appointments)';
        // $table_head[]   =  'Planning: Appointment required for Covid Test (is_appointment_required_covid_19_testing)';
        // $table_head[]   =  'Planning: Eligibility requirement (requires_eligibility_verification)';
        // $table_head[]   =  'Planning: Membership required (requires_membership)';
        // $table_head[]   =  'Planning: Referral required (is_prescription_required_covid_19_testing)';
        // $table_head[]   =  'Planning: Reservations required (requires_reservations)';
        // $table_head[]   =  'Planning: Tests limited to certain patients (has_covid_19_testing_patient_restrictions)';
        // $table_head[]   =  'Popular for: Good for working on laptop (suitable_for_working_on_laptop)';
        // $table_head[]   =  'Recycling: Batteries (has_recycling_batteries)';
        // $table_head[]   =  'Recycling: Clothing (has_recycling_clothing)';
        // $table_head[]   =  'Recycling: Electronics (has_recycling_electronics)';
        // $table_head[]   =  'Recycling: Glass bottles (has_recycling_glass_bottles)';
        // $table_head[]   =  'Recycling: Hazardous household materials (has_recycling_household_hazardous_waste)';
        // $table_head[]   =  'Recycling: Ink cartridges (has_recycling_ink_cartridges)';
        // $table_head[]   =  'Recycling: Light bulbs (has_recycling_light_bulbs)';
        // $table_head[]   =  'Recycling: Metal cans (has_recycling_metal_cans)';
        // $table_head[]   =  'Recycling: Plastic bags (has_recycling_plastic_bags)';
        // $table_head[]   =  'Recycling: Plastic bottles (has_recycling_plastic_bottles)';
        // $table_head[]   =  'Recycling: Plastic foam (has_recycling_plastic_foam)';
        // $table_head[]   =  'Service options: Curbside pickup (has_curbside_pickup)';
        // $table_head[]   =  'Service options: Delivery (has_delivery)';
        // $table_head[]   =  'Service options: Dine-in (serves_dine_in)';
        // $table_head[]   =  'Service options: Drive-through (has_drive_through)';
        // $table_head[]   =  'Service options: Grocery pickup (has_grocery_pickup)';
        // $table_head[]   =  'Service options: In-store pickup (has_in_store_pickup)';
        // $table_head[]   =  'Service options: In-store shopping (has_in_store_shopping)';
        $table_head[50]   =  'Service options: Has online care (has_video_visits)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): American Sign Language (american_sign_language_used)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Arabic (arabic_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Cantonese (cantonese_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Filipino (filipino_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): French (french_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): German (german_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Haitian Creole (haitian_creole_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Hindi (hindi_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Italian (italian_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Korean (korean_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Mandarin (mandarin_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Portuguese (portuguese_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Russian (russian_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Spanish (spanish_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Vietnamese (vietnamese_spoken)';
        // $table_head[]   =  'Service options: Meal service (has_meal_service)';
        // $table_head[]   =  'Service options: No-contact delivery (has_no_contact_delivery)';
        // $table_head[]   =  'Service options: Online appointments (offers_online_appointments)';
        // $table_head[]   =  'Service options: Online classes (offers_online_classes)';
        // $table_head[]   =  'Service options: Online estimates (offers_online_estimates)';
        // $table_head[]   =  'Service options: Onsite services (has_onsite_services)';
        // $table_head[]   =  'Service options: Outdoor seating (has_seating_outdoors)';
        // $table_head[]   =  'Service options: Outdoor services (has_outdoor_services)';
        // $table_head[]   =  'Service options: Same-day delivery (has_delivery_same_day)';
        // $table_head[]   =  'Service options: Takeout (has_takeout)';
        // $table_head[]   =  'Place page URLs: Appointment links (url_appointment)';
        $table_head[42]   =  'Place page URLs: COVID-19 info link (url_covid_19_info_page)';
        // $table_head[]   =  'Place page URLs: Inventory search URL (url_inventory_search)';
        // $table_head[]   =  'Place page URLs: Menu link (url_menu)';
        // $table_head[]   =  'Place page URLs: Order ahead links (url_order_ahead)';
        // $table_head[]   =  'Place page URLs: Reservations links (url_reservations)';
        $table_head[44]   =  'Place page URLs: Virtual care link (url_facility_telemedicine_page)';
        // $table_head[]   =  'Amenities: In-room kitchens (kitchen_in_room)';
        // $table_head[]   =  'Amenities: Wi-Fi (wi_fi)';

        $table_body = array();
        while( $wp_query->have_posts() ) : $wp_query->the_post();
            $post_id = get_the_ID();

            // COVID-19 Restrictions
            // Set to true if COVID-19 restrictions are still in place.
            $covid19 = true;

            // Get slug
            $profile_slug = get_post_field( 'post_name', $post_id );

            // List degrees
            $degrees = get_field('physician_degree',$post_id);
            $degree_list = '';
            $d = 1;
            if ( $degrees ) {
                foreach( $degrees as $degree ):
                    $degree_name = get_term( $degree, 'degree');
                    $degree_list .= $degree_name->name;
                    if( count($degrees) > $d ) {
                        $degree_list .= ", ";
                    }
                    $d++;
                endforeach;
            }

            // Check for valid locations
            $locations = get_field('physician_locations', $post_id);
            $location_valid = false;
            if ( $locations ) {
                foreach( $locations as $location ) {
                    if ( get_post_status ( $location ) == 'publish' ) {
                        $location_valid = true;
                        $break;
                    }
                }
            }

            // Create location variables
            $l = 1;
            $location_title = '';
            $location_address_1 = '';
            $location_address_2 = '';
            $location_city = '';
            $location_state = '';
            $location_zip = '';
            $location_phone = '';
            $location_fax = '';
            $location_telemed_query = '';

            // Create the description variables
            $prefix = get_field('physician_prefix',$post_id);
            $full_name = get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id) . (get_field('physician_pedigree',$post_id) ? '&nbsp;' . get_field('physician_pedigree',$post_id) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
            $medium_name = ($prefix ? $prefix .' ' : '') . get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id);
            $short_name = $prefix ? $prefix .'&nbsp;' .get_field('physician_last_name',$post_id) : get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id) . (get_field('physician_pedigree',$post_id) ? '&nbsp;' . get_field('physician_pedigree',$post_id) : '');
            $resident = get_field('physician_resident',$post_id);
            $phys_title = get_field('physician_title',$post_id);
            $phys_title_name = get_term( $phys_title, 'clinical_title' )->name;
            $vowels = array('a','e','i','o','u');
            if (in_array(strtolower($phys_title_name)[0], $vowels)) { // Defines a or an, based on whether clinical title starts with vowel
                $phys_title_indef_article = 'an';
            } else {
                $phys_title_indef_article = 'a';
            }

            $provider_gmb_exclude = get_field( 'physician_gmb_exclude', $post_id );
            $provider_gmb_cats = get_field( 'physician_gmb_cat', $post_id );
            $provider_gmb_cat_primary_name = 'Doctor';
            $provider_gmb_cat_additional_names = '';
            $c = 1;
            if( $provider_gmb_cats ) {
                foreach( $provider_gmb_cats as $provider_gmb_cat ) {
                    $provider_gmb_cat_term = get_term($provider_gmb_cat, "gmb_cat_provider");
                    if ( 2 > $c ){
                        $provider_gmb_cat_primary_name = esc_html( $provider_gmb_cat_term->name );
                    } elseif ( 2 == $c ) {
                        $provider_gmb_cat_additional_names = esc_html( $provider_gmb_cat_term->name );
                    } elseif ( 11 > $c ) {
                        $provider_gmb_cat_additional_names .= ', ' . esc_html( $provider_gmb_cat_term->name );
                    }
                    $c++;
                } // endforeach
            }

            // Create the table
            if ( $locations && $location_valid && !$resident && !$provider_gmb_exclude ) {
                $row = array();

                // Create row for each valid location
                foreach( $locations as $location ) {
                    if ( get_post_status ( $location ) == 'publish' ) {

                        // Store code
                            $location_slug = get_post_field( 'post_name', $location );
                            $store_code = $profile_slug . '_' . $location_slug;

                            $row[0] = $store_code; // . '-' . implode(",",$locations) . '-' . $location;

                        // Business name
                            $row[1] = 'UAMS Health - ' . html_entity_decode($full_name);

                        // Address line 1

                            // Parent Location
                            $location_post_id = $location;
                            $location_child_id = $location;
                            $location_has_parent = get_field('location_parent',$location_post_id);
                            $location_parent_id = get_field('location_parent_id',$location_post_id);
                            $location_parent_title = ''; // Eliminate PHP errors
                            $location_parent_url = ''; // Eliminate PHP errors
                            $location_parent_location = ''; // Eliminate PHP errors
                            if ($location_has_parent && $location_parent_id) {
                                $location_parent_location = get_post( $location_parent_id );
                            }
                            // Get Post ID for Address & Image fields
                            if ($location_parent_location) {
                                $location_post_id = $location_parent_location->ID;
                                $location_parent_title = $location_parent_location->post_title;
                                $location_parent_url = get_permalink( $location_post_id );
                            }

                            // Create location variables
                            $location_title = get_the_title( $location_child_id );
                            $location_address_1 = get_field( 'location_address_1', $location_post_id );
                            $location_building = get_field('location_building', $location_post_id );
                            if ($location_building) {
                                $building = get_term($location_building, "building");
                                $building_slug = $building->slug;
                                $building_name = $building->name;
                            }
                            $location_floor = get_field_object('location_building_floor', $location_post_id );
                                $location_floor_value = '';
                                $location_floor_label = '';
                                if ( $location_floor ) {
                                    $location_floor_value = $location_floor['value'];
                                    $location_floor_label = $location_floor['choices'][ $location_floor_value ];
                                }
                            $location_suite = get_field('location_suite', $location_post_id );

                                // Option 1:
                                // Address Line 1 = Street address (covered above)
                                // Address Line 2+ = Cascading options based on presence of values...
                                //   Building Name
                                //   Building Floor Number
                                //   Suite Number

                                // $location_addresses = [];
                                // if ( $location_building && $building_slug != '_none' ) {
                                //     array_push($location_addresses, $building_name);
                                // }
                                // if ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ) {
                                //     array_push($location_addresses, $location_floor_label);
                                // }
                                // if ( $location_suite && !empty($location_suite) ) {
                                //     array_push($location_addresses, $location_suite);
                                // }
                                // $location_address_2 = $location_addresses[0];
                                // $location_address_3 = $location_addresses[1];
                                // $location_address_4 = $location_addresses[2];
                                // $location_address_5 = $location_addresses[3];
                                // $location_address_2_deprecated = get_field('location_address_2', $location_post_id );
                                // if (!$location_address_2) {
                                //     $location_address_2 = $location_address_2_deprecated;
                                // }

                                // Option 2:
                                // Address Line 1 = Street address (covered above)
                                // Address Line 2+ = Cascading options based on presence of values...
                                //   Building Name
                                //   Top-Level Location Name
                                //   Child Location Name

                                $location_addresses = [];
                                if ( $location_building && $building_slug != '_none' ) {
                                    array_push($location_addresses, $building_name);
                                }
                                if ( !$location_has_parent ) {
                                    array_push($location_addresses, $location_title);
                                } else {
                                    array_push($location_addresses, $location_parent_title, $location_title);
                                }

                                $location_address_2 = array_key_exists(0, $location_addresses) ? $location_addresses[0] : '';
                                $location_address_3 = array_key_exists(1, $location_addresses) ? $location_addresses[1] : '';
                                $location_address_4 = array_key_exists(2, $location_addresses) ? $location_addresses[2] : '';
                                $location_address_5 = array_key_exists(3, $location_addresses) ? $location_addresses[3] : '';

	                            $location_city = get_field( 'location_city', $location_post_id );
	                            $location_state = get_field( 'location_state', $location_post_id );
	                            $location_zip = get_field( 'location_zip', $location_post_id );
	                            $location_phone = get_field( 'location_phone', $location_child_id );
	                            $location_fax = get_field( 'location_fax', $location_child_id );
	                            $location_hours_group = get_field('location_hours_group', $location_child_id );
	                            $location_telemed_query = $location_hours_group['location_telemed_query'];

	                            $location_gmb_wheelchair_elevator = get_field( 'has_wheelchair_accessible_elevator', $location_post_id );
	                            $location_gmb_wheelchair_elevator = ( $location_gmb_wheelchair_elevator == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_elevator;
	                            $location_gmb_wheelchair_entrance = get_field( 'has_wheelchair_accessible_entrance', $location_post_id );
	                            $location_gmb_wheelchair_entrance = ( $location_gmb_wheelchair_entrance == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_entrance;
	                            $location_gmb_wheelchair_restroom = get_field( 'has_wheelchair_accessible_restroom', $location_post_id );
	                            $location_gmb_wheelchair_restroom = ( $location_gmb_wheelchair_restroom == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_restroom;
	                            $location_gmb_restroom = get_field( 'has_restroom', $location_post_id );
	                            $location_gmb_restroom = ( $location_gmb_restroom == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_restroom;
	                            $location_gmb_appointments = get_field( 'requires_appointments', $location_post_id );
	                            $location_gmb_appointments = ( $location_gmb_appointments == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_appointments;
	                            $location_gmb_temp_customers = get_field( 'requires_temperature_check_customers', $location_post_id );
	                            $location_gmb_temp_customers = ( $location_gmb_temp_customers == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_temp_customers;
	                            $location_gmb_masks_customers = get_field( 'requires_masks_customers', $location_post_id );
	                            $location_gmb_masks_customers = ( $location_gmb_masks_customers == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_masks_customers;
	                            $location_gmb_temp_staff = get_field( 'requires_temperature_check_staff', $location_post_id );
	                            $location_gmb_temp_staff = ( $location_gmb_temp_staff == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_temp_staff;
	                            $location_gmb_masks_staff = get_field( 'requires_masks_staff', $location_post_id );
	                            $location_gmb_masks_staff = ( $location_gmb_masks_staff == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_masks_staff;
	                            $location_gmb_sanitizing = get_field( 'is_sanitizing_between_customers', $location_post_id );
	                            $location_gmb_sanitizing = ( $location_gmb_sanitizing == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_sanitizing;
	                            $location_map = get_field( 'location_map', $location_post_id );
                                $location_latitude = '';
                                $location_longitude = '';
                                if ( $location_map ) {
                                    $location_latitude = $location_map['lat'];
                                    $location_longitude = $location_map['lng'];
                                }

                            // Address line 1
                                $row[2] = $location_address_1 ? html_entity_decode($location_address_1) : '';

                            // Address line 2
                                $row[3] = ( $location_address_2 && !empty($location_address_2) ) ? html_entity_decode($location_address_2) : '';

                            // Address line 3
                                $row[4] = ( $location_address_3 && !empty($location_address_3) ) ? html_entity_decode($location_address_3) : '';

                            // Address line 4
                                $row[5] = ( $location_address_4 && !empty($location_address_4) ) ? html_entity_decode($location_address_4) : '';

                            // Address line 5
                                $row[6] = ( $location_address_5 && !empty($location_address_5) ) ? html_entity_decode($location_address_5) : '';

                            // Sub-locality
                            // Intentionally left blank
                                // $row[7] = '';

                            // Locality
                                $row[7] = $location_city ? $location_city : '';

                            // Administrative area
                                $row[8] = $location_state ? $location_state : '';

                            // Country / Region
                                $row[9] = 'US';

                            // Postal code
                                $row[10] = $location_zip ? $location_zip : '';

                            // Latitude
                                $row[11] = $location_latitude ? $location_latitude : '';

                            // Longitude
                                $row[12] = $location_longitude ? $location_longitude : '';

                            // Primary phone
                                $row[13] = $location_phone ? $location_phone : '';

                            // Additional phones
                            // Intentionally left blank
                                // $row[15] = '';

                            // Website
                                $row[14] = 'https://uamshealth.com/provider/' . $profile_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=profile&utm_specs=' . $store_code;

                            // Primary category
                                $row[15] = $provider_gmb_cat_primary_name;

                            // Additional categories
                                $row[16] = $provider_gmb_cat_additional_names;

                            // Sunday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[19] = '';

                            // Monday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[20] = '';

                            // Tuesday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[21] = '';

                            // Wednesday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[22] = '';

                            // Thursday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[23] = '';

                            // Friday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[24] = '';

                            // Saturday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                // $row[25] = '';

                            // Special hours
                            // Intentionally left blank for now
                            // Format = 2021-12-31: 05:00-23:00, 2022-01-01: x
                                // $row[26] = '';

                            // From the business
                                $excerpt = '';
                                $bio = get_field('physician_clinical_bio',$post_id); // Get the clinical bio
                                $bio = wp_strip_all_tags($bio); // Strip all HTML tags
                                $bio = str_replace(array("\n", "\r"), ' ', $bio); // The double quotes around the carriage-return and newline codes are important. Using single quotes won't yield the proper result.
                                $bio = mb_strimwidth($bio, 0, 747, '...'); // Truncate the string
                                $bio_short = get_field('physician_short_clinical_bio',$post_id); // Strip all HTML tags
                                $bio_short = wp_strip_all_tags($bio_short); // Get the short clinical bio
                                $bio_short = str_replace(array("\n", "\r"), ' ', $bio_short); // The double quotes around the carriage-return and newline codes are important. Using single quotes won't yield the proper result.
                                $bio_short = mb_strimwidth($bio_short, 0, 747, '...'); // Truncate the string

                                if (empty($excerpt)){
                                    if ($bio_short){
                                        $excerpt = $bio_short;
                                    } elseif ($bio) {
                                        $excerpt = $bio;
                                    } else {
                                        $fallback_desc = $medium_name . ' is ' . ($phys_title ? $phys_title_indef_article . ' ' . strtolower($phys_title_name) : 'a health care provider' ) . ($location_title ? ' at ' . $location_title : '') .  ' employed by UAMS Health.';
                                        $excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 747, '...');
                                    }
                                }
                                $row[17] = html_entity_decode($excerpt);

                            // Opening date
                            // Intentionally left blank
                                // $row[28] = '';

                            // Logo photo
                                $provider_gmb_logo_photo = UAMS_FAD_ROOT_URL . 'assets/img/uams-health-1024x1024.png';
                                $row[18] = $provider_gmb_logo_photo;

                            // Cover photo
                                $provider_image_wide = get_field( 'physician_image_wide', $post_id );
                                if ( function_exists( 'fly_add_image_size' ) && !empty($provider_image_wide) ) {
                                    $provider_gmb_cover_photo = image_sizer($provider_image_wide, 2120, 1192, 'center', 'center'); // Google My Business cover photo minimum size: 480x270; maximum size: 2120x1192
                                } else {
                                    $provider_gmb_cover_photo = wp_get_attachment_image_url($provider_image_wide, 'large');
                                }
                                $row[19] = $provider_gmb_cover_photo ?: '';

                            // Other photos
                            // Intentionally left blank
                                // $row[31] = '';

                            // Labels
                                $service_line = '';
                                $service_line = get_field('physician_service_line',$post_id);
                                $service_line_name = $service_line ? get_term( $service_line, 'service_line' )->name : '';

                                $row[20] = $service_line_name;

                            // AdWords location extensions phone
                            // Intentionally left blank
                                // $row[33] = '';

                            // Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
                                if (!empty($location_gmb_wheelchair_elevator)) {
                                    $row[21] = $location_gmb_wheelchair_elevator;
                                } else {
                                    $row[21] = 'Yes';
                                }

                            // Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
                                if (!empty($location_gmb_wheelchair_entrance)) {
                                    $row[22] = $location_gmb_wheelchair_entrance;
                                } else {
                                    $row[22] = 'Yes';
                                }

                            // Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
                                if (!empty($location_gmb_wheelchair_restroom)) {
                                    $row[23] = $location_gmb_wheelchair_restroom;
                                } else {
                                    $row[23] = 'Yes';
                                }

                            // Amenities: Restroom (has_restroom)
                                if (!empty($location_gmb_restroom)) {
                                    $row[24] = $location_gmb_restroom;
                                } else {
                                    $row[24] = 'Yes';
                                }

                            // Planning: Appointment required (requires_appointments)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_appointments)) {
                                        $row[25] =  $location_gmb_appointments;
                                    } else {
                                        $row[25] =  '';
                                    }
                                } else {
                                    $row[25] =  '';
                                }

                            // Service options: Has online care (has_video_visits)
                            // Value based on the relevant location profile
                                $row[50] = $location_telemed_query ? 'Yes' : '';

                            // Place page URLs: COVID-19 info link (url_covid_19_info_page)
                                $row[42] = 'https://uamshealth.com/coronavirus/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=covid-19-info-link&utm_specs=' . $store_code;

                            // Place page URLs: Virtual care link (url_facility_telemedicine_page)
                                $row[44] = $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=virtual-care-link&utm_specs=' . $store_code . '#telemedicine-info' : '';

                        $l++;
                    } else {
	                    continue;
                    }
                    $table_body[] = $row;
                } // endforeach
            }
//             $table_body[] = $row;
        endwhile;
    endif;
    ob_end_clean ();
    $filename = 'GMB_Providers_' . time() . '.csv';
    $delimiter=",";
    $fh = @fopen( 'php://output', 'w' );
    fputs( $fh, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' ); // tells browser to download
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Pragma: no-cache' ); // no cache
    header( 'Expires: 0' ); // expire date
    fputcsv( $fh, $table_head, $delimiter );
    foreach ( $table_body as $data_row )
    {
        fputcsv( $fh, $data_row, $delimiter );
    }


    exit();
}

function gmb_location_csv_export() {
    // Check for current user privileges
    if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_gmb_location_csv' ) ) {
        die( 'Security check error' );
    }

    ob_start();

    // Custom WP_Query args
    $args = array(
        "post_type" => "location",
        "post_status" => "publish",
        "posts_per_page" => "-1", // Set for all
        "orderby" => "title",
        "order" => "ASC",
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        $table_head = array();
        $table_head[0]    =  'Store code';
        $table_head[1]    =  'Business name';
        $table_head[2]    =  'Address line 1';
        $table_head[3]    =  'Address line 2';
        $table_head[4]    =  'Address line 3';
        $table_head[5]    =  'Address line 4';
        $table_head[6]    =  'Address line 5';
        // $table_head[7]    =  'Sub-locality'; // Intentionally left blank
        $table_head[7]    =  'Locality';
        $table_head[8]    =  'Administrative area';
        $table_head[9]   =  'Country / Region';
        $table_head[10]   =  'Postal code';
        $table_head[11]   =  'Latitude';
        $table_head[12]   =  'Longitude';
        $table_head[13]   =  'Primary phone';
        // $table_head[15]   =  'Additional phones'; // Intentionally left blank
        $table_head[14]   =  'Website';
        $table_head[15]   =  'Primary category';
        $table_head[16]   =  'Additional categories';
        // $table_head[19]   =  'Sunday hours'; // Intentionally left blank
        // $table_head[20]   =  'Monday hours'; // Intentionally left blank
        // $table_head[21]   =  'Tuesday hours'; // Intentionally left blank
        // $table_head[22]   =  'Wednesday hours'; // Intentionally left blank
        // $table_head[23]   =  'Thursday hours'; // Intentionally left blank
        // $table_head[24]   =  'Friday hours'; // Intentionally left blank
        // $table_head[25]   =  'Saturday hours'; // Intentionally left blank
        // $table_head[26]   =  'Special hours'; // Intentionally left blank
        $table_head[17]   =  'From the business';
        // $table_head[28]   =  'Opening date'; // Intentionally left blank
        $table_head[18]   =  'Logo photo';
        $table_head[19]   =  'Cover photo';
        $table_head[20]   =  'Other photos';
        $table_head[21]   =  'Labels';
        // $table_head[33]   =  'AdWords location extensions phone'; // Intentionally left blank
        // $table_head[]   =  'Accessibility: Assisted listening devices (has_assisted_listening_devices)';
        // $table_head[]   =  'Accessibility: Assistive hearing loop (has_hearing_loop)';
        // $table_head[]   =  'Accessibility: Passenger loading area (has_passenger_loading_area)';
        $table_head[22]   =  'Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)';
        $table_head[23]   =  'Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)';
        // $table_head[]   =  'Accessibility: Wheelchair accessible parking lot (has_wheelchair_accessible_parking)';
        $table_head[24]   =  'Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)';
        // $table_head[]   =  'Accessibility: Wheelchair accessible seating (has_wheelchair_accessible_seating)';
        // $table_head[]   =  'Accessibility: Wheelchair rental (wheelchair_rental_offerings): Motorized (motorized_wheelchairs)';
        // $table_head[]   =  'Accessibility: Wheelchair rental (wheelchair_rental_offerings): Non-motorized (non_motorized_wheelchairs)';
        // $table_head[]   =  'Activities: Accepting food donations (accepts_non_monetary_donations)';
        // $table_head[]   =  'Activities: Accepting monetary donations (accepts_monetary_donations)';
        // $table_head[]   =  'Activities: Accepting new volunteers (accepts_new_volunteers)';
        // $table_head[]   =  'Activities: Bicycle rental (has_bicycles_for_rent)';
        // $table_head[]   =  'Amenities: Air conditioning (has_air_conditioning)
        // $table_head[]   =  'Amenities: Airport shuttle (has_airport_shuttle)';
        // $table_head[]   =  'Amenities: All-inclusive (has_all_inclusive)';
        // $table_head[]   =  'Amenities: Baggage storage (has_baggage_storage)';
        // $table_head[]   =  'Amenities: Bar onsite (has_bar_onsite)';
        // $table_head[]   =  'Amenities: Beach access (has_beach_access)';
        // $table_head[]   =  'Amenities: Business center (has_business_center)';
        // $table_head[]   =  'Amenities: Cellular service (has_cellular_service)';
        // $table_head[]   =  'Amenities: Child care (has_child_care)';
        // $table_head[]   =  'Amenities: Concierge (has_concierge)';
        // $table_head[]   =  'Amenities: Convenience store (has_onsite_convenience_store)';
        // $table_head[]   =  'Amenities: Currency exchange (has_currency_exchange)';
        // $table_head[]   =  'Amenities: Dogs allowed (welcomes_dogs)';
        // $table_head[]   =  'Amenities: Fitness center (has_fitness_center)';
        // $table_head[]   =  'Amenities: Free breakfast (has_free_breakfast)';
        // $table_head[]   =  'Amenities: Gender-neutral restroom (has_restroom_unisex)';
        // $table_head[]   =  'Amenities: Golf course (has_golf_course)';
        // $table_head[]   =  'Amenities: Good for kids (welcomes_children)';
        // $table_head[]   =  'Amenities: High chairs (has_high_chairs)';
        // $table_head[]   =  'Amenities: Hot tub (has_hot_tub)';
        // $table_head[]   =  'Amenities: Laundry service (has_laundry_service)';
        // $table_head[]   =  'Amenities: Mechanic (has_mechanic)';
        // $table_head[]   =  'Amenities: Parking (parking_offerings): Free (free_parking)';
        // $table_head[]   =  'Amenities: Parking (parking_offerings): Paid (paid_parking)';
        // $table_head[]   =  'Amenities: Pets welcome (welcomes_pets)';
        // $table_head[]   =  'Amenities: Picnic tables (has_picnic_tables)';
        // $table_head[]   =  'Amenities: Public restroom (has_restroom_public)';
        // $table_head[]   =  'Amenities: Restaurant (has_restaurant)';
        $table_head[25]   =  'Amenities: Restroom (has_restroom)';
        // $table_head[]   =  'Amenities: Room service (has_room_service)';
        // $table_head[]   =  'Amenities: Sauna (has_sauna)';
        // $table_head[]   =  'Amenities: Slides (has_playground_slides)';
        // $table_head[]   =  'Amenities: Smoke-free place (is_smoke_free_property)';
        // $table_head[]   =  'Amenities: Spa (has_spa)';
        // $table_head[]   =  'Amenities: Stadium seating (has_stadium_seating)';
        // $table_head[]   =  'Amenities: Swimming pool (swimming_pool_offerings): Indoor (indoor_pool)';
        // $table_head[]   =  'Amenities: Swimming pool (swimming_pool_offerings): Outdoor (outdoor_pool)';
        // $table_head[]   =  'Amenities: Swings (has_playground_swings)';
        // $table_head[]   =  'Crowd: Family-friendly (welcomes_families)';
        // $table_head[]   =  'Crowd: LGBTQ+ friendly (welcomes_lgbtq)';
        // $table_head[]   =  'Crowd: Transgender safespace (is_transgender_safespace)';
        // $table_head[]   =  'Deities Represented: Brahma (has_deity_brahma_represented)';
        // $table_head[]   =  'Deities Represented: Durga (has_deity_durga_represented)';
        // $table_head[]   =  'Deities Represented: Hanuman (has_deity_hanuman_represented)';
        // $table_head[]   =  'Deities Represented: Krishna (has_deity_krishna_represented)';
        // $table_head[]   =  'Deities Represented: Lakshmi (has_deity_lakshmi_represented)';
        // $table_head[]   =  'Deities Represented: Mahavira (has_deity_mahavira_represented)';
        // $table_head[]   =  'Deities Represented: Neminatha (has_deity_neminatha_represented)';
        // $table_head[]   =  'Deities Represented: Parshvanatha (has_deity_parshvanatha_represented)';
        // $table_head[]   =  'Deities Represented: Rama (has_deity_rama_represented)';
        // $table_head[]   =  'Deities Represented: Rishabhanatha (has_deity_rishabhanatha_represented)';
        // $table_head[]   =  'Deities Represented: Sai Baba (has_deity_sai_baba_represented)';
        // $table_head[]   =  'Deities Represented: Shiva (has_deity_shiva_represented)';
        // $table_head[]   =  'Deities Represented: Vishnu (has_deity_vishnu_represented)';
        // $table_head[]   =  'Dining options: Breakfast (serves_breakfast)';
        // $table_head[]   =  'Dining options: Brunch (serves_brunch)';
        // $table_head[]   =  'Dining options: Catering (has_catering)';
        // $table_head[]   =  'Dining options: Counter service (has_counter_service)';
        // $table_head[]   =  'Dining options: Dessert (serves_dessert)';
        // $table_head[]   =  'Dining options: Dinner (serves_dinner)';
        // $table_head[]   =  'Dining options: Lunch (serves_lunch)';
        // $table_head[]   =  'Dining options: Outside food allowed (allows_outside_food)';
        // $table_head[]   =  'Dining options: Pay ahead (has_order_and_pay_ahead)';
        // $table_head[]   =  'Dining options: Seating (has_seating)';
        // $table_head[]   =  'Emergency help: Accepts donations (accepts_donations_during_crisis)';
        // $table_head[]   =  'Emergency help: Employs refugees (offers_employment_during_crisis)';
        // $table_head[]   =  'Emergency help: Needs volunteers (needs_volunteers_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers accommodation for refugees (offers_refuge_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers free legal help (offers_free_legal_help_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers free products or services (offers_free_products_services_during_crisis)';
        // $table_head[]   =  'Emergency help: Offers transportation of goods or people (offers_transportation_during_crisis)';
        // $table_head[]   =  'Exams: CAT (offers_cat_exam_prep)';
        // $table_head[]   =  'Exams: CBSE Board (offers_cbse_board_exam_prep)';
        // $table_head[]   =  'Exams: CTET (offers_ctet_exam_prep)';
        // $table_head[]   =  'Exams: Civil Services (offers_civil_services_exam_prep)';
        // $table_head[]   =  'Exams: GATE (offers_gate_exam_prep)';
        // $table_head[]   =  'Exams: IBPS Clerk (offers_ibps_clerk_exam_prep)';
        // $table_head[]   =  'Exams: IBPS RBB (offers_ibps_rrb_exam_prep)';
        // $table_head[]   =  'Exams: ICSE Board (offers_icse_board_exam_prep)';
        // $table_head[]   =  'Exams: JEE (offers_jee_exam_prep)';
        // $table_head[]   =  'Exams: JNVST (offers_jnvst_exam_prep)';
        // $table_head[]   =  'Exams: LIC AAO (offers_lic_aao_exam_prep)';
        // $table_head[]   =  'Exams: NEET (offers_neet_exam_prep)';
        // $table_head[]   =  'Exams: SBI Clerk (offers_sbi_clerk_exam_prep)';
        // $table_head[]   =  'Exams: SSC CGL (offers_ssc_cgl_exam_prep)';
        // $table_head[]   =  'Exams: SSC Government (offers_ssc_government_exam_prep)';
        // $table_head[]   =  'Exams: UGC Net (offers_ugc_net_exam_prep)';
        // $table_head[]   =  'Exams: UPSC Government (offers_upsc_government_exam_prep)';
        // $table_head[]   =  'From the business: Identifies as Asian-owned (is_owned_by_asian)';
        // $table_head[]   =  'From the business: Identifies as Black-owned (is_black_owned)'; // Intentionally left blank
        // $table_head[]   =  'From the business: Identifies as LGBTQ+ owned (is_owned_by_lgbtq)';
        // $table_head[]   =  'From the business: Identifies as Latino-owned (is_owned_by_latinx)';
        // $table_head[]   =  'From the business: Identifies as veteran-led (is_owned_by_veterans)'; // Intentionally left blank
        // $table_head[]   =  'From the business: Identifies as women-led (is_owned_by_women)'; // Intentionally left blank
        // $table_head[]   =  'Getting here: 24-hour transit available (has_transit_24_hours)';
        // $table_head[]   =  'Highlights: 3D movies (has_movies_3D)';
        // $table_head[]   =  'Highlights: Active military discounts (has_discounts_for_active_military)';
        // $table_head[]   =  'Highlights: All you can drink (has_all_you_can_drink)';
        // $table_head[]   =  'Highlights: Bar games (has_bar_games)';
        // $table_head[]   =  'Highlights: COVID-19 testing center (is_covid_19_bool_1)';
        // $table_head[]   =  'Highlights: Cabaret (has_cabaret)';
        // $table_head[]   =  'Highlights: Fireplace (has_fireplace)';
        // $table_head[]   =  'Highlights: Karaoke (has_karaoke_nights)';
        // $table_head[]   =  'Highlights: Live music (has_live_music)';
        // $table_head[]   =  'Highlights: Live performances (has_live_performances)';
        // $table_head[]   =  'Highlights: Play area (has_area_play)';
        // $table_head[]   =  'Highlights: Rooftop seating (has_seating_rooftop)';
        // $table_head[]   =  'Highlights: Serves local specialty (local_specialty)';
        // $table_head[]   =  'Highlights: Showing the World Cup (offers_fifa_world_cup_broadcast)';
        // $table_head[]   =  'Highlights: Sports (suitable_for_watching_sports)';
        // $table_head[]   =  'Highlights: Trivia night (has_trivia_night)';
        // $table_head[]   =  'Lodging options: Family rooms (has_family_rooms)';
        // $table_head[]   =  'Offerings: Alcohol (serves_alcohol)';
        // $table_head[]   =  'Offerings: All you can eat (has_all_you_can_eat_always)';
        // $table_head[]   =  'Offerings: Assembly service (has_service_assembly)';
        // $table_head[]   =  'Offerings: Beer (serves_beer)';
        // $table_head[]   =  'Offerings: Bike storage (has_secure_bicycle_storage)';
        // $table_head[]   =  'Offerings: Braille menu (has_braille_menu)';
        // $table_head[]   =  'Offerings: Buys used goods (buys_goods_used)';
        // $table_head[]   =  'Offerings: Car rental (has_car_rental)';
        // $table_head[]   =  'Offerings: Car wash (has_car_wash)';
        // $table_head[]   =  'Offerings: Cash advance (has_cash_advance)';
        // $table_head[]   =  'Offerings: Check cashing (has_check_cashing)';
        // $table_head[]   =  'Offerings: Cocktails (serves_cocktails)';
        // $table_head[]   =  'Offerings: Coffee (serves_coffee)';
        // $table_head[]   =  'Offerings: Comfort food (serves_comfort_food)';
        // $table_head[]   =  'Offerings: Dancing (has_dancing)';
        // $table_head[]   =  'Offerings: Diesel gas (sells_gas_diesel)';
        // $table_head[]   =  'Offerings: Drive-through (has_drive_through_covid_19_testing)';
        // $table_head[]   =  'Offerings: Ethanol-free gas (sells_gas_ethanol_free)';
        // $table_head[]   =  'Offerings: Food (serves_food)';
        // $table_head[]   =  'Offerings: Food at bar (serves_food_at_bar)';
        // $table_head[]   =  'Offerings: Free air (has_free_air)';
        // $table_head[]   =  'Offerings: Free water refills (has_free_water_refills)';
        // $table_head[]   =  'Offerings: Full service gas (has_full_service_gas)';
        // $table_head[]   =  'Offerings: Grilling (allows_grilling)';
        // $table_head[]   =  'Offerings: Halal food (serves_halal_food)';
        // $table_head[]   =  'Offerings: Happy hour drinks (serves_happy_hour_drinks)';
        // $table_head[]   =  'Offerings: Happy hour food (serves_happy_hour_food)';
        // $table_head[]   =  'Offerings: Hard liquor (serves_liquor)';
        // $table_head[]   =  'Offerings: Kids' menu (has_childrens_menu)';
        // $table_head[]   =  'Offerings: Kids' shoes (sells_shoes_for_children)';
        // $table_head[]   =  'Offerings: Kids' tours (has_tours_for_children)';
        // $table_head[]   =  'Offerings: Kids' toys (sells_toys_for_children)';
        // $table_head[]   =  'Offerings: Late-night food (serves_late_night_food)';
        // $table_head[]   =  'Offerings: Matinees (has_matinees)';
        // $table_head[]   =  'Offerings: Men's clothing (sells_clothing_for_men)';
        // $table_head[]   =  'Offerings: Men's shoes (sells_shoes_for_men)';
        // $table_head[]   =  'Offerings: Oil change (has_oil_change)';
        // $table_head[]   =  'Offerings: Organic dishes (serves_organic)';
        // $table_head[]   =  'Offerings: Organic products (sells_organic_products)';
        // $table_head[]   =  'Offerings: Passport photos (has_onsite_passport_photos)';
        // $table_head[]   =  'Offerings: Prepared foods (sells_food_prepared)';
        // $table_head[]   =  'Offerings: Repair services (has_service_repair)';
        // $table_head[]   =  'Offerings: Salad bar (has_salad_bar)';
        // $table_head[]   =  'Offerings: Service guarantee (has_service_guarantee)';
        // $table_head[]   =  'Offerings: Sing-along screenings (has_sing_alongs)';
        // $table_head[]   =  'Offerings: Small plates (serves_small_plates)';
        // $table_head[]   =  'Offerings: Tent camping (allows_camping_tent)';
        // $table_head[]   =  'Offerings: Used goods (sells_goods_used)';
        // $table_head[]   =  'Offerings: Vegan options (serves_vegan)';
        // $table_head[]   =  'Offerings: Vegetarian options (serves_vegetarian)';
        // $table_head[]   =  'Offerings: Wine (serves_wine)';
        // $table_head[]   =  'Offerings: Women's clothing (sells_clothing_for_women)';
        // $table_head[]   =  'Offerings: Women's shoes (sells_shoes_for_women)';
        // $table_head[]   =  'Payments: Cash-only (requires_cash_only)';
        // $table_head[]   =  'Payments: Checks (pay_check)';
        // $table_head[]   =  'Payments: Cheque Apetiz (accepts_cheque_apetiz_meal_voucher)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)';
        // $table_head[]   =  'Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)';
        // $table_head[]   =  'Payments: Debit cards (pay_debit_card)';
        // $table_head[]   =  'Payments: Google Pay (pay_mobile_tez)';
        // $table_head[]   =  'Payments: Meal coupons (accepts_meal_coupons)';
        // $table_head[]   =  'Payments: NFC mobile payments (pay_mobile_nfc)';
        // $table_head[]   =  'Payments: Ticket Restaurant (accepts_ticket_restaurant_meal_voucher)';
        // $table_head[]   =  'Payments: VR (accepts_vr_meal_voucher)';
        // $table_head[]   =  'Planning: Accepts new patients (accepts_new_patients)';
        // $table_head[]   =  'Planning: Accepts reservations (accepts_reservations)';
        $table_head[26]   =  'Planning: Appointment required (requires_appointments)';
        // $table_head[]   =  'Planning: Appointment required for Covid Test (is_appointment_required_covid_19_testing)';
        // $table_head[]   =  'Planning: Eligibility requirement (requires_eligibility_verification)';
        // $table_head[]   =  'Planning: Membership required (requires_membership)';
        // $table_head[]   =  'Planning: Referral required (is_prescription_required_covid_19_testing)';
        // $table_head[]   =  'Planning: Reservations required (requires_reservations)';
        // $table_head[]   =  'Planning: Tests limited to certain patients (has_covid_19_testing_patient_restrictions)';
        // $table_head[]   =  'Popular for: Good for working on laptop (suitable_for_working_on_laptop)';
        // $table_head[]   =  'Recycling: Batteries (has_recycling_batteries)';
        // $table_head[]   =  'Recycling: Clothing (has_recycling_clothing)';
        // $table_head[]   =  'Recycling: Electronics (has_recycling_electronics)';
        // $table_head[]   =  'Recycling: Glass bottles (has_recycling_glass_bottles)';
        // $table_head[]   =  'Recycling: Hazardous household materials (has_recycling_household_hazardous_waste)';
        // $table_head[]   =  'Recycling: Ink cartridges (has_recycling_ink_cartridges)';
        // $table_head[]   =  'Recycling: Light bulbs (has_recycling_light_bulbs)';
        // $table_head[]   =  'Recycling: Metal cans (has_recycling_metal_cans)';
        // $table_head[]   =  'Recycling: Plastic bags (has_recycling_plastic_bags)';
        // $table_head[]   =  'Recycling: Plastic bottles (has_recycling_plastic_bottles)';
        // $table_head[]   =  'Recycling: Plastic foam (has_recycling_plastic_foam)';
        // $table_head[]   =  'Service options: Curbside pickup (has_curbside_pickup)';
        // $table_head[]   =  'Service options: Delivery (has_delivery)';
        // $table_head[]   =  'Service options: Dine-in (serves_dine_in)';
        // $table_head[]   =  'Service options: Drive-through (has_drive_through)';
        // $table_head[]   =  'Service options: Grocery pickup (has_grocery_pickup)';
        // $table_head[]   =  'Service options: In-store pickup (has_in_store_pickup)';
        // $table_head[]   =  'Service options: In-store shopping (has_in_store_shopping)';
        $table_head[52]   =  'Service options: Has online care (has_video_visits)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): American Sign Language (american_sign_language_used)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Arabic (arabic_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Cantonese (cantonese_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Filipino (filipino_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): French (french_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): German (german_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Haitian Creole (haitian_creole_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Hindi (hindi_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Italian (italian_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Korean (korean_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Mandarin (mandarin_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Portuguese (portuguese_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Russian (russian_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Spanish (spanish_spoken)';
        // $table_head[]   =  'Service options: Language assistance (languages_spoken): Vietnamese (vietnamese_spoken)';
        // $table_head[]   =  'Service options: Meal service (has_meal_service)';
        // $table_head[]   =  'Service options: No-contact delivery (has_no_contact_delivery)';
        // $table_head[]   =  'Service options: Online appointments (offers_online_appointments)';
        // $table_head[]   =  'Service options: Online classes (offers_online_classes)';
        // $table_head[]   =  'Service options: Online estimates (offers_online_estimates)';
        // $table_head[]   =  'Service options: Onsite services (has_onsite_services)';
        // $table_head[]   =  'Service options: Outdoor seating (has_seating_outdoors)';
        // $table_head[]   =  'Service options: Outdoor services (has_outdoor_services)';
        // $table_head[]   =  'Service options: Same-day delivery (has_delivery_same_day)';
        // $table_head[]   =  'Service options: Takeout (has_takeout)';
        // $table_head[]   =  'Place page URLs: Appointment links (url_appointment)';
        $table_head[44]   =  'Place page URLs: COVID-19 info link (url_covid_19_info_page)';
        // $table_head[]   =  'Place page URLs: Inventory search URL (url_inventory_search)';
        // $table_head[]   =  'Place page URLs: Menu link (url_menu)';
        // $table_head[]   =  'Place page URLs: Order ahead links (url_order_ahead)';
        // $table_head[]   =  'Place page URLs: Reservations links (url_reservations)';
        $table_head[46]   =  'Place page URLs: Virtual care link (url_facility_telemedicine_page)';
        // $table_head[]   =  'Amenities: In-room kitchens (kitchen_in_room)';
        // $table_head[]   =  'Amenities: Wi-Fi (wi_fi)';

        $table_body = array();
        while( $query->have_posts() ) : $query->the_post();
            // Parent Location
            $location_post_id = get_the_ID();
            $location_child_id = get_the_ID();
            $location_has_parent = get_field('location_parent',$location_post_id);
            $location_parent_id = get_field('location_parent_id',$location_post_id);
            $location_parent_title = ''; // Eliminate PHP errors
            $location_parent_url = ''; // Eliminate PHP errors
            $location_parent_location = ''; // Eliminate PHP errors
            if ($location_has_parent && $location_parent_id) {
                $location_parent_location = get_post( $location_parent_id );
            }
            // Get Post ID for Address & Image fields
            if ($location_parent_location) {
                $location_post_id = $location_parent_location->ID;
                $location_parent_title = $location_parent_location->post_title;
                $location_parent_url = get_permalink( $location_post_id );
            }

            // COVID-19 Restrictions
            // Set to true if COVID-19 restrictions are still in place.
            $covid19 = true;

            // Get slug
            $store_code = ( $location_parent_location ? get_post_field( 'post_name', $location_post_id ) . '_' : '' ) . get_post_field( 'post_name', $location_child_id );
            $location_slug = ( $location_parent_location ? get_post_field( 'post_name', $location_post_id ) . '/' : '' ) . get_post_field( 'post_name', $location_child_id );

            // Create location variables
            $location_title = get_the_title( $location_child_id );
            $location_address_1 = get_field( 'location_address_1', $location_post_id );
            $location_building = get_field('location_building', $location_post_id );
            if ($location_building) {
                $building = get_term($location_building, "building");
                $building_slug = $building->slug;
                $building_name = $building->name;
            }
            $location_floor = get_field_object('location_building_floor', $location_post_id );
                $location_floor_value = '';
                $location_floor_label = '';
                if ( $location_floor ) {
                    $location_floor_value = $location_floor['value'];
                    $location_floor_label = $location_floor['choices'][ $location_floor_value ];
                }
            $location_suite = get_field('location_suite', $location_post_id );

                // Option 1:
                // Address Line 1 = Street address (covered above)
                // Address Line 2+ = Cascading options based on presence of values...
                //   Building Name
                //   Building Floor Number
                //   Suite Number

                $location_addresses = [];
                if ( $location_building && $building_slug != '_none' ) {
                    array_push($location_addresses, $building_name);
                }
                if ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ) {
                    array_push($location_addresses, $location_floor_label);
                }
                if ( $location_suite && !empty($location_suite) ) {
                    array_push($location_addresses, $location_suite);
                }
                $location_address_2 = array_key_exists(0, $location_addresses) ? $location_addresses[0] : '';
                $location_address_3 = array_key_exists(1, $location_addresses) ? $location_addresses[1] : '';
                $location_address_4 = array_key_exists(2, $location_addresses) ? $location_addresses[2] : '';
                $location_address_5 = array_key_exists(3, $location_addresses) ? $location_addresses[3] : '';
                $location_address_2_deprecated = get_field('location_address_2', $location_post_id );
                if (!$location_address_2) {
                    $location_address_2 = $location_address_2_deprecated;
                }

                // Option 2:
                // Address Line 1 = Street address (covered above)
                // Address Line 2+ = Cascading options based on presence of values...
                //   Building Name
                //   Top-Level Location Name
                //   Child Location Name

                // $location_addresses = [];
                // if ( $location_building && $building_slug != '_none' ) {
                //     array_push($location_addresses, $building_name);
                // }
                // if ( !$location_has_parent ) {
                //     array_push($location_addresses, $location_title);
                // } else {
                //     array_push($location_addresses, $location_parent_title, $location_title);
                // }
                // $location_address_2 = $location_addresses[0];
                // $location_address_3 = $location_addresses[1];
                // $location_address_4 = $location_addresses[2];
                // $location_address_5 = $location_addresses[3];

            $location_city = get_field( 'location_city', $location_post_id );
            $location_state = get_field( 'location_state', $location_post_id );
            $location_zip = get_field( 'location_zip', $location_post_id );
            $location_phone = get_field( 'location_phone', $location_child_id );
            $location_fax = get_field( 'location_fax', $location_child_id );
            $location_hours_group = get_field('location_hours_group', $location_child_id );
            $location_telemed_query = $location_hours_group['location_telemed_query'];

            $location_gmb_cats = get_field( 'location_gmb_cat', $location_child_id );
            $location_gmb_cat_primary_name = 'Medical Clinic';
            $location_gmb_cat_additional_names = '';
            $c = 1;
            if( $location_gmb_cats ) {
                foreach( $location_gmb_cats as $location_gmb_cat ) {
                    $location_gmb_cat_term = get_term($location_gmb_cat, "gmb_cat_location");
                    if ( 2 > $c ){
                        $location_gmb_cat_primary_name = esc_html( $location_gmb_cat_term->name );
                    } elseif ( 2 == $c ) {
                        $location_gmb_cat_additional_names = esc_html( $location_gmb_cat_term->name );
                    } elseif ( 11 > $c ) {
                        $location_gmb_cat_additional_names .= ', ' . esc_html( $location_gmb_cat_term->name );
                    }
                    $c++;
                } // endforeach
            }

            $location_gmb_wheelchair_elevator = get_field( 'has_wheelchair_accessible_elevator', $location_post_id );
            $location_gmb_wheelchair_elevator = ( $location_gmb_wheelchair_elevator == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_elevator;
            $location_gmb_wheelchair_entrance = get_field( 'has_wheelchair_accessible_entrance', $location_post_id );
            $location_gmb_wheelchair_entrance = ( $location_gmb_wheelchair_entrance == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_entrance;
            $location_gmb_wheelchair_restroom = get_field( 'has_wheelchair_accessible_restroom', $location_post_id );
            $location_gmb_wheelchair_restroom = ( $location_gmb_wheelchair_restroom == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_wheelchair_restroom;
            $location_gmb_restroom = get_field( 'has_restroom', $location_post_id );
            $location_gmb_restroom = ( $location_gmb_restroom == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_restroom;
            $location_gmb_appointments = get_field( 'requires_appointments', $location_post_id );
            $location_gmb_appointments = ( $location_gmb_appointments == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_appointments;
            $location_gmb_temp_customers = get_field( 'requires_temperature_check_customers', $location_post_id );
            $location_gmb_temp_customers = ( $location_gmb_temp_customers == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_temp_customers;
            $location_gmb_masks_customers = get_field( 'requires_masks_customers', $location_post_id );
            $location_gmb_masks_customers = ( $location_gmb_masks_customers == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_masks_customers;
            $location_gmb_temp_staff = get_field( 'requires_temperature_check_staff', $location_post_id );
            $location_gmb_temp_staff = ( $location_gmb_temp_staff == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_temp_staff;
            $location_gmb_masks_staff = get_field( 'requires_masks_staff', $location_post_id );
            $location_gmb_masks_staff = ( $location_gmb_masks_staff == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_masks_staff;
            $location_gmb_sanitizing = get_field( 'is_sanitizing_between_customers', $location_post_id );
            $location_gmb_sanitizing = ( $location_gmb_sanitizing == 'Not Applicable' ) ? '[NOT APPLICABLE]' : $location_gmb_sanitizing;
            $location_map = get_field( 'location_map', $location_post_id );
                $location_latitude = '';
                $location_longitude = '';
                if ( $location_map ) {
                    $location_latitude = $location_map['lat'];
                    $location_longitude = $location_map['lng'];
                }
            $location_gmb_exclude = get_field( 'location_gmb_exclude', $location_post_id );
            $location_gmb_prefix = get_field( 'location_gmb_prefix', $location_post_id );

            // Create the table
            if ( true !== $location_gmb_exclude ) {

                // Start table row

                // Store code
                    // Option 1: Provider slug plus numeral
                    $row[0] = $store_code;

                // Business name
                    $row[1] =  ($location_gmb_prefix ? 'UAMS Health - ' : '') . html_entity_decode($location_title);

                // Address line 1
                    $row[2] =  $location_address_1 ? html_entity_decode($location_address_1) : '';

                // Address line 2
                    $row[3] =  ( $location_address_2 && !empty($location_address_2) ) ? html_entity_decode($location_address_2) : '';

                // Address line 3
                    $row[4] =  ( $location_address_3 && !empty($location_address_3) ) ? html_entity_decode($location_address_3) : '';

                // Address line 4
                    $row[5] =  ( $location_address_4 && !empty($location_address_4) ) ? html_entity_decode($location_address_4) : '';

                // Address line 5
                    $row[6] =  ( $location_address_5 && !empty($location_address_5) ) ? html_entity_decode($location_address_5) : '';

                // Sub-locality
                // Intentionally left blank
                    // $row[7] =  '';

                // Locality
                    $row[7] =  $location_city ? $location_city : '';

                // Administrative area
                    $row[8] =  $location_state ? $location_state : '';

                // Country / Region
                    $row[9] = 'US';

                // Postal code
                    $row[10] =  $location_zip ? $location_zip : '';

                // Latitude
                    $row[11] =  $location_latitude ? $location_latitude : '';

                // Longitude
                    $row[12] =  $location_longitude ? $location_longitude : '';

                // Primary phone
                    $row[13] =  $location_phone ? $location_phone : '';

                // Additional phones
                // Intentionally left blank
                    // $row[15] =  '';

                // Website
                    $row[14] =  'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=location&utm_content=profile&utm_specs=' . $store_code;

                // Primary category
                    $row[15] =  $location_gmb_cat_primary_name;

                // Additional categories
                    $row[16] =  $location_gmb_cat_additional_names;

                // Sunday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[19] =  '';

                // Monday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[20] =  '';

                // Tuesday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[21] =  '';

                // Wednesday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[22] =  '';

                // Thursday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[23] =  '';

                // Friday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[24] =  '';

                // Saturday hours
                // Intentionally left blank for now
                // Format = 08:00-16:30
                    // $row[25] =  '';

                // Special hours
                // Intentionally left blank for now
                // Format = 2021-12-31: 05:00-23:00, 2022-01-01: x
                    // $row[26] =  '';

                // From the business
                    $excerpt = '';
                    $descr = get_field('location_about',$location_post_id); // Get the description
                    $descr = wp_strip_all_tags($descr); // Strip all HTML tags
                    $descr = str_replace(array("\n", "\r"), ' ', $descr); // The double quotes around the carriage-return and newline codes are important. Using single quotes won't yield the proper result.
                    $descr = mb_strimwidth($descr, 0, 747, '...'); // Truncate the string
                    $descr_short = get_field('location_short_desc',$location_post_id); // Strip all HTML tags
                    $descr_short = wp_strip_all_tags($descr_short); // Get the short description
                    $descr_short = str_replace(array("\n", "\r"), ' ', $descr_short); // The double quotes around the carriage-return and newline codes are important. Using single quotes won't yield the proper result.
                    $descr_short = mb_strimwidth($descr_short, 0, 747, '...'); // Truncate the string

                    if (empty($excerpt)){
                        if ($descr_short){
                            $excerpt = $descr_short;
                        } elseif ($descr) {
                            $excerpt = $descr;
                        } else {
                            $excerpt = '';
                        }
                    }
                    $row[17] =  html_entity_decode($excerpt);

                // Opening date
                // Intentionally left blank
                    // $row[28] =  '';

                // Logo photo
                    $location_gmb_logo_photo = UAMS_FAD_ROOT_URL . 'assets/img/uams-health-1024x1024.png';
                    $row[18] = $location_gmb_logo_photo;

                // Cover photo

                    // Image values
                    $featured_image = get_post_thumbnail_id($location_post_id); // Get the ID of location's featured image
                    $featured_img_url = get_the_post_thumbnail_url($location_post_id,'full'); // Get the URL of location's featured image
                    $override_parent_photo = get_field('location_image_override_parent',$location_child_id); // Get toggle value of "Override Parent Images?"
                    $override_parent_photo_featured = get_field('location_image_override_parent_featured',$location_child_id); // Get toggle value of "Override Parent Featured Image?"
                    $override_parent_photo_wayfinding = get_field('location_image_override_parent_wayfinding',$location_child_id); // Get toggle value of "Override Parent Wayfinding Photo?"
                    $override_parent_photo_gallery = get_field('location_image_override_parent_gallery',$location_child_id); // Get toggle value of "Override Parent Photo Gallery?"
                    // Set image for featured image
                    if ($override_parent_photo && $location_parent_location && $override_parent_photo_featured) { // If child location & override is true
                        $featured_image = get_post_thumbnail_id($location_child_id);
                    } else { // Use parent/standard image
                        $featured_image = get_post_thumbnail_id($location_post_id);
                    }
                    // Set image for wayfinding image
                    if ($override_parent_photo && $location_parent_location && $override_parent_photo_wayfinding) {
                        $wayfinding_photo = get_field('location_wayfinding_photo',$location_child_id);
                    } else { // Use parent/standard image
                        $wayfinding_photo = get_field('location_wayfinding_photo',$location_post_id);
                    }
                    // Set image(s) for gallery image(s)
                    if ($override_parent_photo && $location_parent_location && $override_parent_photo_gallery) {
                        $photo_gallery = get_field('location_photo_gallery',$location_child_id);
                    } else { // Use parent/standard image(s)
                        $photo_gallery = get_field('location_photo_gallery',$location_post_id);
                    }

                    $location_images = array(); // Create empty array for location images
                    if ($featured_image && !empty($featured_image)) {
                        // If featured image exists...
                        $location_images[] = $featured_image; // add it to the array for location images
                    }
                    if ($wayfinding_photo && !empty($wayfinding_photo)) {
                        // If featured image exists...
                        $location_images[] = $wayfinding_photo; // add it to the array for location images
                    }
                    if ($photo_gallery && !empty($photo_gallery)) {
                        // If image gallery values exist...
                        foreach( $photo_gallery as $photo_gallery_image ) {
                            $location_images[] = $photo_gallery_image; // add them to the array for location images
                        }
                    }
                    $location_images = array_unique($location_images); // Remove duplicate values from the array for location images
                    $location_images_count = count($location_images); // Count how many images are in the array for location images
                    if ( $location_images_count > 0 ) {
                        $p = 1;
                        $location_gmb_other_photos = '';
                        foreach( $location_images as $location_images_item ) {
                            if ( $p == 1 ) {
                                if ( function_exists( 'fly_add_image_size' ) ) {
                                    $location_gmb_cover_photo = image_sizer($location_images_item, 2120, 1192, 'center', 'center'); // Google My Business cover photo minimum size: 480x270; maximum size: 2120x1192
                                } else {
                                    $location_gmb_cover_photo =  wp_get_attachment_image_url($location_images_item, 'large');
                                }
                            } else {
                                if ( function_exists( 'fly_add_image_size' ) ) {
                                    $location_gmb_other_photos .= image_sizer($location_images_item, 2120, 1192, 'center', 'center'); // Google My Business cover photo minimum size: 480x270; maximum size: 2120x1192
                                } else {
                                    $location_gmb_other_photos .=  wp_get_attachment_image_url($location_images_item, 'large');
                                }
                                $location_gmb_other_photos .=  $p < $location_images_count ? ',' : '';
                            }
                            $p++;
                        } // endforeach
                    }
                    $row[19] = $location_gmb_cover_photo ?: '';

                // Other photos
                    $row[20] = $location_gmb_other_photos ?: '';

                // Labels
                    $region = get_term( get_field('location_region',$location_post_id), 'region' )->name;
                    $row[21] =  $region ? $region : '';

                // AdWords location extensions phone
                // Intentionally left blank
                    // $row[33] =  '';

                // Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
                    if (!empty($location_gmb_wheelchair_elevator)) {
                        $row[22] =  $location_gmb_wheelchair_elevator;
                    } else {
                        $row[22] =  'Yes';
                    }

                // Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
                    if (!empty($location_gmb_wheelchair_entrance)) {
                        $row[23] =  $location_gmb_wheelchair_entrance;
                    } else {
                        $row[23] =  'Yes';
                    }

                // Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
                    if (!empty($location_gmb_wheelchair_restroom)) {
                        $row[24] =  $location_gmb_wheelchair_restroom;
                    } else {
                        $row[24] =  'Yes';
                    }

                // Amenities: Restroom (has_restroom)
                    if (!empty($location_gmb_restroom)) {
                        $row[25] =  $location_gmb_restroom;
                    } else {
                        $row[25] =  'Yes';
                    }

                // Planning: Appointment required (requires_appointments)
                    if ( $covid19 ) {
                        if (!empty($location_gmb_appointments)) {
                            $row[26] =  $location_gmb_appointments;
                        } else {
                            $row[26] =  '';
                        }
                    } else {
                        $row[26] =  '';
                    }

                // Service options: Has online care (has_video_visits)
                // Value based on the relevant location profile
                    $row[52] = $location_telemed_query ? 'Yes' : '';

                // Place page URLs: COVID-19 info link (url_covid_19_info_page)
                    $row[44] =  'https://uamshealth.com/coronavirus/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=location&utm_content=covid-19-info-link&utm_specs=' . $store_code;

                // Place page URLs: Virtual care link (url_facility_telemedicine_page)
                    $row[46] =  $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=location&utm_content=virtual-care-link&utm_specs=' . $store_code . '#telemedicine-info' : '';

            } // endif
            $table_body[] = $row;

        endwhile;

    endif;

    ob_end_clean ();
    $filename = 'GMB_Locations_' . time() . '.csv';
    $delimiter=",";
    $fh = @fopen( 'php://output', 'w' );
    fputs( $fh, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' ); // tells browser to download
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Pragma: no-cache' ); // no cache
    header( 'Expires: 0' ); // expire date
    fputcsv( $fh, $table_head, $delimiter );
    foreach ( $table_body as $data_row )
    {
        fputcsv( $fh, $data_row, $delimiter );
    }

    exit();

}

function mychart_csv_export() {
    // Check for current user privileges
    if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_mychart_csv' ) ) {
        die( 'Security check error' );
    }

    ob_start();

    // Custom WP_Query args
    $args = array(
        "post_type" => "provider",
        "post_status" => "publish",
        "posts_per_page" => "-1", // Set for all
        "orderby" => "title",
        "order" => "ASC",
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        $table_head = array();
        $table_head[0] = 'SER ID';
        $table_head[1] = 'Provider Name';
        $table_head[2] = 'Provider Profile URL';
        $table_head[3] = 'Provider Photo URL';

        $table_body = array();
        $row = array();
        while( $query->have_posts() ) : $query->the_post();
            $post_id = get_the_ID();

            // Create the Name variables
            $ser_id = get_field('physician_pid',$post_id);
            $ser_id = ( $ser_id == 0 ) ? '' : $ser_id;
            $sort_name = get_the_title($post_id);
            $profile_url = get_the_permalink($post_id);

            // Get slug
            $profile_slug = get_post_field( 'post_name', $post_id );

            // Get featured image
            // Epic restricts images to a maximum of 1000px per side.
            // One image is used in all placements, with no server-side crop happening.
            // Most placements are background images inside a circular container no larger than 122x122.
            // The details window for a provider displays the full image, restricted only by the max-width of the window and Epic's 1000px maximum on image size.
            // Defining hot spots within the media library, placing them between the provider's front teeth, yields the best result.
            $provider_mychart_photo = wp_get_attachment_image_url(get_post_thumbnail_id($post_id), 'thumbnail'); // 150x150

            $resident = get_field('physician_resident',$post_id);

            // Create the table
            if ( !$resident ) {

                // Start table row

                // PID
                    $row[0] = $ser_id;

                // Provider Name
                    $row[1] = html_entity_decode($sort_name);

                // Provider Profile URL
                    $row[2] = $profile_url . '?utm_source=mychart&utm_medium=link&utm_campaign=clinical_service&utm_term=provider&utm_content=' . $profile_slug . '&utm_specs=' . $ser_id;

                // Provider Photo URL
                    $row[3] = $provider_mychart_photo ?: '';

            } // endif !$resident

            $table_body[] = $row;
        endwhile;

    endif;

    ob_end_clean ();
    $filename = 'MyChart_List_' . time() . '.csv';
    $delimiter=",";
    $fh = @fopen( 'php://output', 'w' );
    fputs( $fh, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' ); // tells browser to download
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Pragma: no-cache' ); // no cache
    header( 'Expires: 0' ); // expire date
    fputcsv( $fh, $table_head, $delimiter );
    foreach ( $table_body as $data_row )
    {
        fputcsv( $fh, $data_row, $delimiter );
    }


    exit();

}