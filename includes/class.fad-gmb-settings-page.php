<?php
/*
 *
 * 
 * 
 */
function uamswp_add_gmb_export_page() {
    add_submenu_page( 'fad-settings', 'UAMSWP GMB Export', 'GMB Export', 'manage_options', 'uamswp-gmb-export', 'uamswp_fad_gmb_export_page', '', 90 );
}
add_action( 'admin_menu', 'uamswp_add_gmb_export_page', 105 );

function uamswp_fad_gmb_export_page() {
    ?>
    <h1><?php echo $GLOBALS['title'] ?></h1>
    <p>These will take a little time to generate, please be patient.</p>
    <h2>Doximity List</h2>
    <p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_doximity_csv&_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2>GMB Provider List</h2>
    <p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_gmb_provider_csv&_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2>GMB Location List</h2>
    <p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_gmb_location_csv&_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <h2>MyChart Provider List</h2>
    <p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=uamswp-gmb-export' ) ?>&action=download_mychart_csv&_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>" class="page-title-action"><?php _e('Export to CSV','uamswp-find-a-doc');?></a></p>
    <?php
}

// Add action hook only if action=download_gmb_provider_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_gmb_provider_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'gmb_csv_export' );
}

function gmb_csv_export() {

    // Check for current user privileges 
    if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'download_csv' ) ) {
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
        $table_head[7]    =  'Sub-locality';
        $table_head[8]    =  'Locality';
        $table_head[9]    =  'Administrative area';
        $table_head[10]   =  'Country / Region';
        $table_head[11]   =  'Postal code';
        $table_head[12]   =  'Latitude';
        $table_head[13]   =  'Longitude';
        $table_head[14]   =  'Primary phone';
        $table_head[15]   =  'Additional phones';
        $table_head[16]   =  'Website';
        $table_head[17]   =  'Primary category';
        $table_head[18]   =  'Additional categories';
        $table_head[19]   =  'Sunday hours';
        $table_head[20]   =  'Monday hours';
        $table_head[21]   =  'Tuesday hours';
        $table_head[22]   =  'Wednesday hours';
        $table_head[23]   =  'Thursday hours';
        $table_head[24]   =  'Friday hours';
        $table_head[25]   =  'Saturday hours';
        $table_head[26]   =  'Special hours';
        $table_head[27]   =  'From the business';
        $table_head[28]   =  'Opening date';
        $table_head[29]   =  'Logo photo';
        $table_head[30]   =  'Cover photo';
        $table_head[31]   =  'Other photos';
        $table_head[32]   =  'Labels';
        $table_head[33]   =  'AdWords location extensions phone';
        $table_head[34]   =  'Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)';
        $table_head[35]   =  'Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)';
        $table_head[36]   =  'Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)';
        $table_head[37]   =  'Amenities: Restroom (has_restroom)';
        $table_head[38]   =  'From the business: Identifies as Black-owned (is_black_owned)';
        $table_head[39]   =  'From the business: Identifies as veteran-led (is_owned_by_veterans)';
        $table_head[40]   =  'From the business: Identifies as women-led (is_owned_by_women)';
        $table_head[41]   =  'Health & safety: Appointment required (requires_appointments)';
        $table_head[42]   =  'Health & safety: Mask required (requires_masks_customers)';
        $table_head[43]   =  'Health & safety: Safety dividers at checkout (has_plexiglass_at_checkout)';
        $table_head[44]   =  'Health & safety: Staff get temperature checks (requires_temperature_check_staff)';
        $table_head[45]   =  'Health & safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)';
        $table_head[46]   =  'Health & safety: Staff wear masks (requires_masks_staff)';
        $table_head[47]   =  'Health & safety: Temperature check required (requires_temperature_check_customers)';
        $table_head[48]   =  'Offerings: Passport photos (has_onsite_passport_photos)';
        $table_head[49]   =  'Payments: Cash-only (requires_cash_only)';
        $table_head[50]   =  'Payments: Checks (pay_check)';
        $table_head[51]   =  'Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)';
        $table_head[52]   =  'Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)';
        $table_head[53]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)';
        $table_head[54]   =  'Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)';
        $table_head[55]   =  'Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)';
        $table_head[56]   =  'Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)';
        $table_head[57]   =  'Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)';
        $table_head[58]   =  'Payments: Debit cards (pay_debit_card)';
        $table_head[59]   =  'Payments: NFC mobile payments (pay_mobile_nfc)';
        $table_head[60]   =  'Place page URLs: Appointment links (url_appointment)';
        $table_head[61]   =  'Place page URLs: COVID-19 info link (url_covid_19_info_page)';
        $table_head[62]   =  'Place page URLs: Menu link (url_menu)';
        $table_head[63]   =  'Place page URLs: Virtual care link (url_facility_telemedicine_page)';
        $table_head[64]   =  'Planning: LGBTQ friendly (welcomes_lgbtq)';
        $table_head[65]   =  'Planning: Transgender safespace (is_transgender_safespace)';
        $table_head[66]   =  'Service options: Curbside pickup (has_curbside_pickup)';
        $table_head[67]   =  'Service options: Delivery (has_delivery)';
        $table_head[68]   =  'Service options: Drive-through (has_drive_through)';
        $table_head[69]   =  'Service options: In-store pickup (has_in_store_pickup)';
        $table_head[70]   =  'Service options: In-store shopping (has_in_store_shopping)';
        $table_head[71]   =  'Service options: Online care (has_video_visits)';
        $table_head[72]   =  'Service options: Same-day delivery (has_delivery_same_day)';

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
            $locations = get_field('physician_locations',$post_id);
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

                            $row[0] = $store_code;

                        // Business name
                            $row[1] = 'UAMS - ' . html_entity_decode($full_name);

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
                                $row[7] = '';

                            // Locality
                                $row[8] = $location_city ? $location_city : '';

                            // Administrative area
                                $row[9] = $location_state ? $location_state : '';

                            // Country / Region
                                $row[10] = 'US';

                            // Postal code
                                $row[11] = $location_zip ? $location_zip : '';

                            // Latitude
                                $row[12] = $location_latitude ? $location_latitude : '';

                            // Longitude
                                $row[13] = $location_longitude ? $location_longitude : '';

                            // Primary phone
                                $row[14] = $location_phone ? $location_phone : '';

                            // Additional phones
                            // Intentionally left blank
                                $row[15] = '';

                            // Website
                                $row[16] = 'https://uamshealth.com/provider/' . $profile_slug . '/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=profile&amp;utm_specs=' . $store_code;

                            // Primary category
                                $row[17] = $provider_gmb_cat_primary_name;
    
                            // Additional categories
                                $row[18] = $provider_gmb_cat_additional_names;

                            // Sunday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[19] = '';

                            // Monday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[20] = '';

                            // Tuesday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[21] = '';

                            // Wednesday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[22] = '';

                            // Thursday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[23] = '';

                            // Friday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[24] = '';

                            // Saturday hours
                            // Intentionally left blank for now
                            // Format = 08:00-16:30
                                $row[25] = '';

                            // Special hours
                            // Intentionally left blank for now
                            // Format = 2021-12-31: 05:00-23:00, 2022-01-01: x
                                $row[26] = '';

                            // From the business
                                $excerpt = '';
                                $bio = get_field('physician_clinical_bio',$post_id);
                                $bio_short = get_field('physician_short_clinical_bio',$post_id);

                                if (empty($excerpt)){
                                    if ($bio_short){
                                        $excerpt = mb_strimwidth(wp_strip_all_tags($bio_short), 0, 747, '...');
                                    } elseif ($bio) {
                                        $excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 747, '...');
                                    } else {
                                        $fallback_desc = $medium_name . ' is ' . ($phys_title ? $phys_title_indef_article . ' ' . strtolower($phys_title_name) : 'a health care provider' ) . ($location_title ? ' at ' . $location_title : '') .  ' employed by UAMS Health.';
                                        $excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 747, '...');
                                    }
                                }
                                $row[27] = html_entity_decode($excerpt);

                            // Opening date
                            // Intentionally left blank
                                $row[28] = '';

                            // Logo photo
                            // Intentionally left blank
                                $row[29] = '';

                            // Cover photo
                            // Intentionally left blank, for now
                                //echo $featured_img_url;
                                $row[30] = '';

                            // Other photos
                            // Intentionally left blank
                                $row[31] = '';

                            // Labels
                                $service_line = '';
                                $service_line = get_field('physician_service_line',$post_id);
                                $service_line_name = $service_line ? get_term( $service_line, 'service_line' )->name : '';

                                $row[32] = $service_line_name;

                            // AdWords location extensions phone
                            // Intentionally left blank
                                $row[33] = '';

                            // Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
                                if (!empty($location_gmb_wheelchair_elevator)) {
                                    $row[34] = $location_gmb_wheelchair_elevator;
                                } else {
                                    $row[34] = 'Yes';
                                }

                            // Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
                                if (!empty($location_gmb_wheelchair_entrance)) {
                                    $row[35] = $location_gmb_wheelchair_entrance;
                                } else {
                                    $row[35] = 'Yes';
                                }

                            // Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
                                if (!empty($location_gmb_wheelchair_restroom)) {
                                    $row[36] = $location_gmb_wheelchair_restroom;
                                } else {
                                    $row[36] = 'Yes';
                                }

                            // Amenities: Restroom (has_restroom)
                                if (!empty($location_gmb_restroom)) {
                                    $row[37] = $location_gmb_restroom;
                                } else {
                                    $row[37] = 'Yes';
                                }
                            
                            // From the business: Identifies as Black-owned (is_black_owned)
                            // Intentionally left blank
                                $row[38] = '';

                            // From the business: Identifies as veteran-led (is_owned_by_veterans)
                            // Intentionally left blank
                                $row[39] = '';

                            // From the business: Identifies as women-led (is_owned_by_women)
                            // Intentionally left blank
                                $row[40] = '';

                            // Health &amp; safety: Appointment required (requires_appointments)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_appointments)) {
                                        $row[41] =  $location_gmb_appointments;
                                    } else {
                                        $row[41] =  '';
                                    }
                                } else {
                                    $row[41] =  '';
                                }

                            // Health &amp; safety: Mask required (requires_masks_customers)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_masks_customers)) {
                                        $row[42] =  $location_gmb_masks_customers;
                                    } else {
                                        $row[42] =  'Yes';
                                    }
                                } else {
                                    $row[42] = 'No';
                                }

                            // Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)
                                $row[43] = '';

                            // Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_temp_staff)) {
                                        $row[44] = $location_gmb_temp_staff;
                                    } else {
                                        $row[44] = 'Yes';
                                    }
                                } else {
                                    $row[44] = 'No';
                                }

                            // Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_sanitizing)) {
                                        $row[45] = $location_gmb_sanitizing;
                                    } else {
                                        $row[45] = 'Yes';
                                    }
                                } else {
                                    $row[45] = 'No';
                                }

                            // Health &amp; safety: Staff wear masks (requires_masks_staff)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_masks_staff)) {
                                        $row[46] = $location_gmb_masks_staff;
                                    } else {
                                        $row[46] = 'Yes';
                                    }
                                } else {
                                    $row[46] = 'No';
                                }

                            // Health &amp; safety: Temperature check required (requires_temperature_check_customers)
                                if ( $covid19 ) {
                                    if (!empty($location_gmb_temp_customers)) {
                                        $row[47] = $location_gmb_temp_customers;
                                    } else {
                                        $row[47] = 'Yes';
                                    }
                                } else {
                                    $row[47] = 'No';
                                }

                            // Offerings: Passport photos (has_onsite_passport_photos)
                                $row[48] = '[NOT APPLICABLE]';

                            // Payments: Cash-only (requires_cash_only)
                                $row[49] = '[NOT APPLICABLE]';

                            // Payments: Checks (pay_check)
                                $row[50] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)
                                $row[51] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)
                                $row[52] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)
                                $row[53] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)
                                $row[54] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)
                                $row[55] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)
                                $row[56] = '[NOT APPLICABLE]';

                            // Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)
                                $row[57] = '[NOT APPLICABLE]';

                            // Payments: Debit cards (pay_debit_card)
                                $row[58] = '[NOT APPLICABLE]';

                            // Payments: NFC mobile payments (pay_mobile_nfc)
                                $row[59] = '[NOT APPLICABLE]';

                            // Place page URLs: Appointment links (url_appointment)
                            // Intentionally left blank
                                $row[60] = '';

                            // Place page URLs: COVID-19 info link (url_covid_19_info_page)
                                $row[61] = 'https://uamshealth.com/coronavirus/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=covid-19-info-link&amp;utm_specs=' . $store_code;

                            // Place page URLs: Menu link (url_menu)
                                $row[62] = '[NOT APPLICABLE]';

                            // Place page URLs: Virtual care link (url_facility_telemedicine_page)
                                $row[63] = $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=virtual-care-link&amp;utm_specs=' . $store_code . '#telemedicine-info' : '';

                            // Planning: LGBTQ friendly (welcomes_lgbtq)
                                $row[64] = '';

                            // Planning: Transgender safespace (is_transgender_safespace)
                                $row[65] = '';

                            // Service options: Curbside pickup (has_curbside_pickup)
                                $row[66] = '[NOT APPLICABLE]';

                            // Service options: Delivery (has_delivery)
                                $row[67] = '[NOT APPLICABLE]';

                            // Service options: Drive-through (has_drive_through)
                                $row[68] = '[NOT APPLICABLE]';

                            // Service options: In-store pickup (has_in_store_pickup)
                                $row[69] = '[NOT APPLICABLE]';

                            // Service options: In-store shopping (has_in_store_shopping)
                                $row[70] = '[NOT APPLICABLE]';

                            // Service options: Online care (has_video_visits)
                            // Value based on the relevant location profile
                                $row[71] = $location_telemed_query ? 'Yes' : '';

                            // Service options: Same-day delivery (has_delivery_same_day)
                                $row[72] = '[NOT APPLICABLE]';

                            // );

                        $l++;
                    }
                } // endforeach
            }
            $table_body[] = $row;       
        endwhile;
    endif;
    ob_end_clean ();
    $filename = 'GMB_Export_' . time() . '.csv';
    $delimiter=";";
    $fh = @fopen( 'php://output', 'w' );
    fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
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