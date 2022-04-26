<?php
	/**
	 *  Template Name: GMB Provider List
	 */

// Remove the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' ); 

// Remove header
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

// Remove primary nav
remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

// Remove Footer Widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

// Remove Footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

// Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove Breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'sp_breadcrumb_after_header' );

// Remove Skip Links from a template
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

// UAMS modifications
remove_action ( 'genesis_header', 'uamswp_site_image', 5 );
remove_action ( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action ( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action ( 'genesis_before_header', 'uams_toggle_search', 12);
remove_action ( 'genesis_before_header', 'uamswp_skip_links', 5 );

// Dequeue Skip Links Script
add_action( 'wp_enqueue_scripts','child_dequeue_skip_links' );
function child_dequeue_skip_links() {
	wp_dequeue_script( 'skip-links' );
}

// Remove GTM container
remove_action( 'wp_head', 'uamswp_gtm_1' );
remove_action( 'genesis_before', 'uamswp_gtm_2' );

add_filter ( 'wp_nav_menu', '__return_false' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'display_provider_image' );
function display_provider_image() {
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

        // Define which columns to display
        $gmb_show_store_code = true;
        $gmb_show_business_name = true;
        $gmb_show_address_line_1 = true;
        $gmb_show_address_line_2 = true;
        $gmb_show_address_line_3 = true;
        $gmb_show_address_line_4 = true;
        $gmb_show_address_line_5 = true;
        $gmb_show_sub_locality = true;
        $gmb_show_locality = true;
        $gmb_show_administrative_area = true;
        $gmb_show_country_region = true;
        $gmb_show_postal_code = true;
        $gmb_show_latitude = true;
        $gmb_show_longitude = true;
        $gmb_show_primary_phone = true;
        $gmb_show_additional_phones = true;
        $gmb_show_website = true;
        $gmb_show_primary_category = true;
        $gmb_show_additional_categories = true;
        $gmb_show_sunday_hours = true;
        $gmb_show_monday_hours = true;
        $gmb_show_tuesday_hours = true;
        $gmb_show_wednesday_hours = true;
        $gmb_show_thursday_hours = true;
        $gmb_show_friday_hours = true;
        $gmb_show_saturday_hours = true;
        $gmb_show_special_hours = true;
        $gmb_show_from_the_business = true;
        $gmb_show_opening_date = true;
        $gmb_show_logo_photo = true;
        $gmb_show_cover_photo = true;
        $gmb_show_other_photos = true;
        $gmb_show_labels = true;
        $gmb_show_adwords_location_extensions_phone = true;
        $gmb_show_has_wheelchair_accessible_elevator = true;
        $gmb_show_has_wheelchair_accessible_entrance = true;
        $gmb_show_has_wheelchair_accessible_restroom = true;
        $gmb_show_has_restroom = true;
        $gmb_show_is_black_owned = true;
        $gmb_show_is_owned_by_veterans = true;
        $gmb_show_is_owned_by_women = true;
        $gmb_show_requires_appointments = true;
        $gmb_show_requires_masks_customers = true;
        $gmb_show_has_plexiglass_at_checkout = true;
        $gmb_show_requires_temperature_check_staff = true;
        $gmb_show_is_sanitizing_between_customers = true;
        $gmb_show_requires_masks_staff = true;
        $gmb_show_requires_temperature_check_customers = true;
        $gmb_show_has_onsite_passport_photos = true;
        $gmb_show_requires_cash_only = true;
        $gmb_show_pay_check = true;
        $gmb_show_pay_credit_card_types_accepted__american_express = true;
        $gmb_show_pay_credit_card_types_accepted__china_union_pay = true;
        $gmb_show_pay_credit_card_types_accepted__diners_club = true;
        $gmb_show_pay_credit_card_types_accepted__discover = true;
        $gmb_show_pay_credit_card_types_accepted__jcb = true;
        $gmb_show_pay_credit_card_types_accepted__mastercard = true;
        $gmb_show_pay_credit_card_types_accepted__visa = true;
        $gmb_show_pay_debit_card = true;
        $gmb_show_pay_mobile_nfc = true;
        $gmb_show_url_appointment = true;
        $gmb_show_url_covid_19_info_page = true;
        $gmb_show_url_menu = true;
        $gmb_show_url_facility_telemedicine_page = true;
        $gmb_show_welcomes_lgbtq = true;
        $gmb_show_is_transgender_safespace = true;
        $gmb_show_has_curbside_pickup = true;
        $gmb_show_has_delivery = true;
        $gmb_show_has_drive_through = true;
        $gmb_show_has_in_store_pickup = true;
        $gmb_show_has_in_store_shopping = true;
        $gmb_show_has_video_visits = true;
        $gmb_show_has_delivery_same_day = true;
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php if ( $gmb_show_store_code ) { ?>
                            <th class="no-break">Store code</th>
                        <?php }
                        if ( $gmb_show_business_name ) { ?>
                            <th class="no-break">Business name</th>
                        <?php }
                        if( $gmb_show_address_line_1 ) { ?>
                            <th class="no-break">Address line 1</th>
                        <?php }
                        if( $gmb_show_address_line_2 ) { ?>
                            <th class="no-break">Address line 2</th>
                        <?php }
                        if( $gmb_show_address_line_3 ) { ?>
                            <th class="no-break">Address line 3</th>
                        <?php }
                        if( $gmb_show_address_line_4 ) { ?>
                            <th class="no-break">Address line 4</th>
                        <?php }
                        if( $gmb_show_address_line_5 ) { ?>
                            <th class="no-break">Address line 5</th>
                        <?php }
                        if( $gmb_show_sub_locality ) { ?>
                            <th class="no-break">Sub-locality</th>
                        <?php }
                        if( $gmb_show_locality ) { ?>
                            <th class="no-break">Locality</th>
                        <?php }
                        if( $gmb_show_administrative_area ) { ?>
                            <th class="no-break">Administrative area</th>
                        <?php }
                        if( $gmb_show_country_region ) { ?>
                            <th class="no-break">Country / Region</th>
                        <?php }
                        if( $gmb_show_postal_code ) { ?>
                            <th class="no-break">Postal code</th>
                        <?php }
                        if( $gmb_show_latitude ) { ?>
                            <th class="no-break">Latitude</th>
                        <?php }
                        if( $gmb_show_longitude ) { ?>
                            <th class="no-break">Longitude</th>
                        <?php }
                        if( $gmb_show_primary_phone ) { ?>
                            <th class="no-break">Primary phone</th>
                        <?php }
                        if( $gmb_show_additional_phones ) { ?>
                            <th class="no-break">Additional phones</th>
                        <?php }
                        if( $gmb_show_website ) { ?>
                            <th class="no-break">Website</th>
                        <?php }
                        if( $gmb_show_primary_category ) { ?>
                            <th class="no-break">Primary category</th>
                        <?php }
                        if( $gmb_show_additional_categories ) { ?>
                            <th class="no-break">Additional categories</th>
                        <?php }
                        if( $gmb_show_sunday_hours ) { ?>
                            <th class="no-break">Sunday hours</th>
                        <?php }
                        if( $gmb_show_monday_hours ) { ?>
                            <th class="no-break">Monday hours</th>
                        <?php }
                        if( $gmb_show_tuesday_hours ) { ?>
                            <th class="no-break">Tuesday hours</th>
                        <?php }
                        if( $gmb_show_wednesday_hours ) { ?>
                            <th class="no-break">Wednesday hours</th>
                        <?php }
                        if( $gmb_show_thursday_hours ) { ?>
                            <th class="no-break">Thursday hours</th>
                        <?php }
                        if( $gmb_show_friday_hours ) { ?>
                            <th class="no-break">Friday hours</th>
                        <?php }
                        if( $gmb_show_saturday_hours ) { ?>
                            <th class="no-break">Saturday hours</th>
                        <?php }
                        if( $gmb_show_special_hours ) { ?>
                            <th class="no-break">Special hours</th>
                        <?php }
                        if( $gmb_show_from_the_business ) { ?>
                            <th class="no-break">From the business</th>
                        <?php }
                        if( $gmb_show_opening_date ) { ?>
                            <th class="no-break">Opening date</th>
                        <?php }
                        if( $gmb_show_logo_photo ) { ?>
                            <th class="no-break">Logo photo</th>
                        <?php }
                        if( $gmb_show_cover_photo ) { ?>
                            <th class="no-break">Cover photo</th>
                        <?php }
                        if( $gmb_show_other_photos ) { ?>
                            <th class="no-break">Other photos</th>
                        <?php }
                        if( $gmb_show_labels ) { ?>
                            <th class="no-break">Labels</th>
                        <?php }
                        if( $gmb_show_adwords_location_extensions_phone ) { ?>
                            <th class="no-break">AdWords location extensions phone</th>
                        <?php }
                        if( $gmb_show_has_wheelchair_accessible_elevator ) { ?>
                            <th class="no-break">Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)</th>
                        <?php }
                        if( $gmb_show_has_wheelchair_accessible_entrance ) { ?>
                            <th class="no-break">Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)</th>
                        <?php }
                        if( $gmb_show_has_wheelchair_accessible_restroom ) { ?>
                            <th class="no-break">Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)</th>
                        <?php }
                        if( $gmb_show_has_restroom ) { ?>
                            <th class="no-break">Amenities: Restroom (has_restroom)</th>
                        <?php }
                        if( $gmb_show_is_black_owned ) { ?>
                            <th class="no-break">From the business: Identifies as Black-owned (is_black_owned)</th>
                        <?php }
                        if( $gmb_show_is_owned_by_veterans ) { ?>
                            <th class="no-break">From the business: Identifies as veteran-led (is_owned_by_veterans)</th>
                        <?php }
                        if( $gmb_show_is_owned_by_women ) { ?>
                            <th class="no-break">From the business: Identifies as women-led (is_owned_by_women)</th>
                        <?php }
                        if( $gmb_show_requires_appointments ) { ?>
                            <th class="no-break">Health &amp; safety: Appointment required (requires_appointments)</th>
                        <?php }
                        if( $gmb_show_requires_masks_customers ) { ?>
                            <th class="no-break">Health &amp; safety: Mask required (requires_masks_customers)</th>
                        <?php }
                        if( $gmb_show_has_plexiglass_at_checkout ) { ?>
                            <th class="no-break">Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)</th>
                        <?php }
                        if( $gmb_show_requires_temperature_check_staff ) { ?>
                            <th class="no-break">Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)</th>
                        <?php }
                        if( $gmb_show_is_sanitizing_between_customers ) { ?>
                            <th class="no-break">Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)</th>
                        <?php }
                        if( $gmb_show_requires_masks_staff ) { ?>
                            <th class="no-break">Health &amp; safety: Staff wear masks (requires_masks_staff)</th>
                        <?php }
                        if( $gmb_show_requires_temperature_check_customers ) { ?>
                            <th class="no-break">Health &amp; safety: Temperature check required (requires_temperature_check_customers)</th>
                        <?php }
                        if( $gmb_show_has_onsite_passport_photos ) { ?>
                            <th class="no-break">Offerings: Passport photos (has_onsite_passport_photos)</th>
                        <?php }
                        if( $gmb_show_requires_cash_only ) { ?>
                            <th class="no-break">Payments: Cash-only (requires_cash_only)</th>
                        <?php }
                        if( $gmb_show_pay_check ) { ?>
                            <th class="no-break">Payments: Checks (pay_check)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__american_express ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__china_union_pay ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__diners_club ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__discover ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__jcb ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__mastercard ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)</th>
                        <?php }
                        if( $gmb_show_pay_credit_card_types_accepted__visa ) { ?>
                            <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)</th>
                        <?php }
                        if( $gmb_show_pay_debit_card ) { ?>
                            <th class="no-break">Payments: Debit cards (pay_debit_card)</th>
                        <?php }
                        if( $gmb_show_pay_mobile_nfc ) { ?>
                            <th class="no-break">Payments: NFC mobile payments (pay_mobile_nfc)</th>
                        <?php }
                        if( $gmb_show_url_appointment ) { ?>
                            <th class="no-break">Place page URLs: Appointment links (url_appointment)</th>
                        <?php }
                        if( $gmb_show_url_covid_19_info_page ) { ?>
                            <th class="no-break">Place page URLs: COVID-19 info link (url_covid_19_info_page)</th>
                        <?php }
                        if( $gmb_show_url_menu ) { ?>
                            <th class="no-break">Place page URLs: Menu link (url_menu)</th>
                        <?php }
                        if( $gmb_show_url_facility_telemedicine_page ) { ?>
                            <th class="no-break">Place page URLs: Virtual care link (url_facility_telemedicine_page)</th>
                        <?php }
                        if( $gmb_show_welcomes_lgbtq ) { ?>
                            <th class="no-break">Planning: LGBTQ friendly (welcomes_lgbtq)</th>
                        <?php }
                        if( $gmb_show_is_transgender_safespace ) { ?>
                            <th class="no-break">Planning: Transgender safespace (is_transgender_safespace)</th>
                        <?php }
                        if( $gmb_show_has_curbside_pickup ) { ?>
                            <th class="no-break">Service options: Curbside pickup (has_curbside_pickup)</th>
                        <?php }
                        if( $gmb_show_has_delivery ) { ?>
                            <th class="no-break">Service options: Delivery (has_delivery)</th>
                        <?php }
                        if( $gmb_show_has_drive_through ) { ?>
                            <th class="no-break">Service options: Drive-through (has_drive_through)</th>
                        <?php }
                        if( $gmb_show_has_in_store_pickup ) { ?>
                            <th class="no-break">Service options: In-store pickup (has_in_store_pickup)</th>
                        <?php }
                        if( $gmb_show_has_in_store_shopping ) { ?>
                            <th class="no-break">Service options: In-store shopping (has_in_store_shopping)</th>
                        <?php }
                        if( $gmb_show_has_video_visits ) { ?>
                            <th class="no-break">Service options: Online care (has_video_visits)</th>
                        <?php }
                        if( $gmb_show_has_delivery_same_day ) { ?>
                            <th class="no-break">Service options: Same-day delivery (has_delivery_same_day)</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php 
                while( $query->have_posts() ) : $query->the_post();
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

                    // Create the image variables
                    $provider_gmb_logo_photo = UAMS_FAD_ROOT_URL . 'assets/img/uams-health-1024x1024.png';
                    $provider_gmb_cover_photo = wp_get_attachment_image_url(get_field( 'physician_image_wide', $post_id ), 'full');

                    // Create the table
                    if ( $locations && $location_valid && !$resident && !$provider_gmb_exclude ) {

                        // Create row for each valid location
                        foreach( $locations as $location ) {
                            if ( get_post_status ( $location ) == 'publish' ) {

                                    // Start table row
                                    echo '<tr>';

                                    // Store code
                                        $location_slug = get_post_field( 'post_name', $location );
                                        $store_code = $profile_slug . '_' . $location_slug;

                                        if ( $gmb_show_store_code ) {
                                            echo '<td data-gmb-column="Store code" class="no-break">';
                                            echo $store_code;
                                            echo '</td>';
                                        }

                                    // Business name
                                        if ( $gmb_show_business_name ) {
                                            echo '<td data-gmb-column="Business name" class="no-break">UAMS Health - ' . $full_name . '</td>';
                                        }

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

                                        if ( $gmb_show_address_line_1 ) {
                                            echo '<td data-gmb-column="Address line 1" class="no-break">';
                                            echo $location_address_1 ? $location_address_1 : '';
                                            echo '</td>';
                                        }

                                    // Address line 2
                                        if ( $gmb_show_address_line_2 ) {
                                            echo '<td data-gmb-column="Address line 2" class="no-break">';
                                            echo ( $location_address_2 && !empty($location_address_2) ) ? $location_address_2 : '';
                                            echo '</td>';
                                        }
            
                                    // Address line 3
                                        if ( $gmb_show_address_line_3 ) {
                                            echo '<td data-gmb-column="Address line 3" class="no-break">';
                                            echo ( $location_address_3 && !empty($location_address_3) ) ? $location_address_3 : '';
                                            echo '</td>';
                                        }
            
                                    // Address line 4
                                        if ( $gmb_show_address_line_4 ) {
                                            echo '<td data-gmb-column="Address line 4" class="no-break">';
                                            echo ( $location_address_4 && !empty($location_address_4) ) ? $location_address_4 : '';
                                            echo '</td>';
                                        }
            
                                    // Address line 5
                                        if ( $gmb_show_address_line_5 ) {
                                            echo '<td data-gmb-column="Address line 5" class="no-break">';
                                            echo ( $location_address_5 && !empty($location_address_5) ) ? $location_address_5 : '';
                                            echo '</td>';
                                        }

                                    // Sub-locality
                                    // Intentionally left blank
                                        if ( $gmb_show_sub_locality ) {
                                            echo '<td data-gmb-column="Sub-locality" class="no-break"></td>';
                                        }

                                    // Locality
                                        if ( $gmb_show_locality ) {
                                            echo '<td data-gmb-column="Locality" class="no-break">';
                                            echo $location_city ? $location_city : '';
                                            echo '</td>';
                                        }

                                    // Administrative area
                                        if ( $gmb_show_administrative_area ) {
                                            echo '<td data-gmb-column="Administrative area" class="no-break">';
                                            echo $location_state ? $location_state : '';
                                            echo '</td>';
                                        }

                                    // Country / Region
                                        if ( $gmb_show_country_region ) {
                                            echo '<td data-gmb-column="Country / Region" class="no-break">US</td>';
                                        }

                                    // Postal code
                                        if ( $gmb_show_postal_code ) {
                                            echo '<td data-gmb-column="Postal code" class="no-break">';
                                            echo $location_zip ? $location_zip : '';
                                            echo '</td>';
                                        }

                                    // Latitude
                                        if ( $gmb_show_latitude ) {
                                            echo '<td data-gmb-column="Latitude" class="no-break">';
                                            echo $location_latitude ? $location_latitude : '';
                                            echo '</td>';
                                        }

                                    // Longitude
                                        if ( $gmb_show_longitude ) {
                                            echo '<td data-gmb-column="Longitude" class="no-break">';
                                            echo $location_longitude ? $location_longitude : '';
                                            echo '</td>';
                                        }

                                    // Primary phone
                                        if ( $gmb_show_primary_phone ) {
                                            echo '<td data-gmb-column="Primary phone" class="no-break">';
                                            echo $location_phone ? $location_phone : '';
                                            echo '</td>';
                                        }

                                    // Additional phones
                                    // Intentionally left blank
                                        if ( $gmb_show_additional_phones ) {
                                            echo '<td data-gmb-column="Additional phones" class="no-break"></td>';
                                        }

                                    // Website
                                        if ( $gmb_show_website ) {
                                            echo '<td data-gmb-column="Website" class="no-break">';
                                            echo 'https://uamshealth.com/provider/' . $profile_slug . '/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=profile&amp;utm_specs=' . $store_code;
                                            echo '</td>';
                                        }

                                    // Primary category
                                        if ( $gmb_show_primary_category ) {
                                            echo '<td data-gmb-column="Primary category" class="no-break">';
                                            echo $provider_gmb_cat_primary_name;
                                            echo '</td>';
                                        }
            
                                    // Additional categories
                                        if ( $gmb_show_additional_categories ) {
                                            echo '<td data-gmb-column="Additional categories" class="no-break">';
                                            echo $provider_gmb_cat_additional_names;
                                            echo '</td>';
                                        }

                                    // Sunday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_sunday_hours ) {
                                            echo '<td data-gmb-column="Sunday hours" class="no-break"></td>';
                                        }

                                    // Monday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_monday_hours ) {
                                            echo '<td data-gmb-column="Monday hours" class="no-break"></td>';
                                        }

                                    // Tuesday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_tuesday_hours ) {
                                            echo '<td data-gmb-column="Tuesday hours" class="no-break"></td>';
                                        }

                                    // Wednesday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_wednesday_hours ) {
                                            echo '<td data-gmb-column="Wednesday hours" class="no-break"></td>';
                                        }

                                    // Thursday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_thursday_hours ) {
                                            echo '<td data-gmb-column="Thursday hours" class="no-break"></td>';
                                        }

                                    // Friday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_friday_hours ) {
                                            echo '<td data-gmb-column="Friday hours" class="no-break"></td>';
                                        }

                                    // Saturday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        if ( $gmb_show_saturday_hours ) {
                                            echo '<td data-gmb-column="Saturday hours" class="no-break"></td>';
                                        }

                                    // Special hours
                                    // Intentionally left blank for now
                                    // Format = 2021-12-31: 05:00-23:00, 2022-01-01: x
                                        if ( $gmb_show_special_hours ) {
                                            echo '<td data-gmb-column="Special hours" class="no-break"></td>';
                                        }

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
                                        if ( $gmb_show_from_the_business ) {
                                            echo '<td data-gmb-column="From the business"><span style="display: block; width: 19.6875em"></span>';
                                            echo $excerpt;
                                            echo '</td>';
                                        }

                                    // Opening date
                                    // Intentionally left blank
                                        if ( $gmb_show_opening_date ) {
                                            echo '<td data-gmb-column="Opening date" class="no-break"></td>';
                                        }

                                    // Logo photo
                                        if ( $gmb_show_logo_photo ) {
                                            echo '<td data-gmb-column="Logo photo" class="no-break">';
                                            echo $provider_gmb_logo_photo ?: '';
                                            echo '</td>';
                                        }

                                    // Cover photo
                                        if ( $gmb_show_cover_photo ) {
                                            echo '<td data-gmb-column="Cover photo" class="no-break">';
                                            echo $provider_gmb_cover_photo ?: '';
                                            echo '</td>';
                                        }

                                    // Other photos
                                    // Intentionally left blank
                                        if ( $gmb_show_other_photos ) {
                                            echo '<td data-gmb-column="Other photos" class="no-break"></td>';
                                        }

                                    // Labels
                                        $service_line = '';
                                        $service_line = get_field('physician_service_line',$post_id);
                                        $service_line_name = $service_line ? get_term( $service_line, 'service_line' )->name : '';
    
                                        if ( $gmb_show_labels ) {
                                            echo '<td data-gmb-column="Labels" class="no-break">';
                                            echo $service_line_name;
                                            echo '</td>';
                                        }

                                    // AdWords location extensions phone
                                    // Intentionally left blank
                                        if ( $gmb_show_adwords_location_extensions_phone ) {
                                            echo '<td data-gmb-column="AdWords location extensions phone" class="no-break"></td>';
                                        }

                                    // Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
                                        if ( $gmb_show_has_wheelchair_accessible_elevator ) {
                                            echo '<td data-gmb-column="Accessibility: Wheelchair accessible elevat  or (has_wheelchair_accessible_elevator)" class="no-break">';
                                            if (!empty($location_gmb_wheelchair_elevator)) {
                                                echo $location_gmb_wheelchair_elevator;
                                            } else {
                                                echo 'Yes';
                                            }
                                            echo '</td>';
                                        }

                                    // Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
                                        if ( $gmb_show_has_wheelchair_accessible_entrance ) {
                                            echo '<td data-gmb-column="Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)" class="no-break">';
                                            if (!empty($location_gmb_wheelchair_entrance)) {
                                                echo $location_gmb_wheelchair_entrance;
                                            } else {
                                                echo 'Yes';
                                            }
                                            echo '</td>';
                                        }

                                    // Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
                                        if ( $gmb_show_has_wheelchair_accessible_restroom ) {
                                            echo '<td data-gmb-column="Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)" class="no-break">';
                                            if (!empty($location_gmb_wheelchair_restroom)) {
                                                echo $location_gmb_wheelchair_restroom;
                                            } else {
                                                echo 'Yes';
                                            }
                                            echo '</td>';
                                        }

                                    // Amenities: Restroom (has_restroom)
                                        if ( $gmb_show_has_restroom ) {
                                            echo '<td data-gmb-column="Amenities: Restroom (has_restroom)" class="no-break">';
                                            if (!empty($location_gmb_restroom)) {
                                                echo $location_gmb_restroom;
                                            } else {
                                                echo 'Yes';
                                            }
                                            echo '</td>';
                                        }
                                    
                                    // From the business: Identifies as Black-owned (is_black_owned)
                                    // Intentionally left blank
                                        if ( $gmb_show_is_black_owned ) {
                                            echo '<td data-gmb-column="From the business: Identifies as Black-owned (is_black_owned)" class="no-break"></td>';
                                        }

                                    // From the business: Identifies as veteran-led (is_owned_by_veterans)
                                    // Intentionally left blank
                                        if ( $gmb_show_is_owned_by_veterans ) {
                                            echo '<td data-gmb-column="From the business: Identifies as veteran-led (is_owned_by_veterans)" class="no-break"></td>';
                                        }

                                    // From the business: Identifies as women-led (is_owned_by_women)
                                    // Intentionally left blank
                                        if ( $gmb_show_is_owned_by_women ) {
                                            echo '<td data-gmb-column="From the business: Identifies as women-led (is_owned_by_women)" class="no-break"></td>';
                                        }

                                    // Health &amp; safety: Appointment required (requires_appointments)
                                        if ( $gmb_show_requires_appointments ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Appointment required (requires_appointments)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_appointments)) {
                                                    echo $location_gmb_appointments;
                                                } else {
                                                    echo '';
                                                }
                                            } else {
                                                echo '';
                                            }
                                            echo '</td>';
                                        }

                                    // Health &amp; safety: Mask required (requires_masks_customers)
                                        if ( $gmb_show_requires_masks_customers ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Mask required (requires_masks_customers)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_masks_customers)) {
                                                    echo $location_gmb_masks_customers;
                                                } else {
                                                    echo 'Yes';
                                                }
                                            } else {
                                                echo 'No';
                                            }
                                            echo '</td>';
                                        }

                                    // Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)
                                        if ( $gmb_show_has_plexiglass_at_checkout ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)
                                        if ( $gmb_show_requires_temperature_check_staff ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_temp_staff)) {
                                                    echo $location_gmb_temp_staff;
                                                } else {
                                                    echo 'Yes';
                                                }
                                            } else {
                                                echo 'No';
                                            }
                                            echo '</td>';
                                        }

                                    // Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)
                                        if ( $gmb_show_is_sanitizing_between_customers ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_sanitizing)) {
                                                    echo $location_gmb_sanitizing;
                                                } else {
                                                    echo 'Yes';
                                                }
                                            } else {
                                                echo 'No';
                                            }
                                            echo '</td>';
                                        }

                                    // Health &amp; safety: Staff wear masks (requires_masks_staff)
                                        if ( $gmb_show_requires_masks_staff ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Staff wear masks (requires_masks_staff)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_masks_staff)) {
                                                    echo $location_gmb_masks_staff;
                                                } else {
                                                    echo 'Yes';
                                                }
                                            } else {
                                                echo 'No';
                                            }
                                            echo '</td>';
                                        }

                                    // Health &amp; safety: Temperature check required (requires_temperature_check_customers)
                                        if ( $gmb_show_requires_temperature_check_customers ) {
                                            echo '<td data-gmb-column="Health &amp; safety: Temperature check required (requires_temperature_check_customers)" class="no-break">';
                                            if ( $covid19 ) {
                                                if (!empty($location_gmb_temp_customers)) {
                                                    echo $location_gmb_temp_customers;
                                                } else {
                                                    echo 'Yes';
                                                }
                                            } else {
                                                echo 'No';
                                            }
                                            echo '</td>';
                                        }

                                    // Offerings: Passport photos (has_onsite_passport_photos)
                                        if ( $gmb_show_has_onsite_passport_photos ) {
                                            echo '<td data-gmb-column="Offerings: Passport photos (has_onsite_passport_photos)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Cash-only (requires_cash_only)
                                        if ( $gmb_show_requires_cash_only ) {
                                            echo '<td data-gmb-column="Payments: Cash-only (requires_cash_only)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Checks (pay_check)
                                        if ( $gmb_show_pay_check ) {
                                            echo '<td data-gmb-column="Payments: Checks (pay_check)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)
                                        if ( $gmb_show_pay_credit_card_types_accepted__american_express ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)
                                        if ( $gmb_show_pay_credit_card_types_accepted__china_union_pay ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)
                                        if ( $gmb_show_pay_credit_card_types_accepted__diners_club ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)
                                        if ( $gmb_show_storgmb_show_pay_credit_card_types_accepted__discovere_code ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)
                                        if ( $gmb_show_pay_credit_card_types_accepted__jcb ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)
                                        if ( $gmb_show_pay_credit_card_types_accepted__mastercard ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)
                                        if ( $gmb_show_pay_credit_card_types_accepted__visa ) {
                                            echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: Debit cards (pay_debit_card)
                                        if ( $gmb_show_pay_debit_card ) {
                                            echo '<td data-gmb-column="Payments: Debit cards (pay_debit_card)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Payments: NFC mobile payments (pay_mobile_nfc)
                                        if ( $gmb_show_pay_mobile_nfc ) {
                                            echo '<td data-gmb-column="Payments: NFC mobile payments (pay_mobile_nfc)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Place page URLs: Appointment links (url_appointment)
                                    // Intentionally left blank
                                        if ( $gmb_show_url_appointment ) {
                                            echo '<td data-gmb-column="Place page URLs: Appointment links (url_appointment)" class="no-break"></td>';
                                        }

                                    // Place page URLs: COVID-19 info link (url_covid_19_info_page)
                                        if ( $gmb_show_url_covid_19_info_page ) {
                                            echo '<td data-gmb-column="Place page URLs: COVID-19 info link (url_covid_19_info_page)" class="no-break">';
                                            echo 'https://uamshealth.com/coronavirus/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=covid-19-info-link&amp;utm_specs=' . $store_code;
                                            echo '</td>';
                                        }

                                    // Place page URLs: Menu link (url_menu)
                                        if ( $gmb_show_url_menu ) {
                                            echo '<td data-gmb-column="Place page URLs: Menu link (url_menu)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Place page URLs: Virtual care link (url_facility_telemedicine_page)
                                        if ( $gmb_show_url_facility_telemedicine_page ) {
                                            echo '<td data-gmb-column="Place page URLs: Virtual care link (url_facility_telemedicine_page)" class="no-break">';
                                            echo $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=virtual-care-link&amp;utm_specs=' . $store_code . '#telemedicine-info' : '';
                                            echo '</td>';
                                        }

                                    // Planning: LGBTQ friendly (welcomes_lgbtq)
                                        if ( $gmb_show_welcomes_lgbtq ) {
                                            echo '<td data-gmb-column="Planning: LGBTQ friendly (welcomes_lgbtq)" class="no-break">';
                                            echo '';
                                            echo '</td>';
                                        }

                                    // Planning: Transgender safespace (is_transgender_safespace)
                                        if ( $gmb_show_is_transgender_safespace ) {
                                            echo '<td data-gmb-column="Planning: Transgender safespace (is_transgender_safespace)" class="no-break">';
                                            echo '';
                                            echo '</td>';
                                        }

                                    // Service options: Curbside pickup (has_curbside_pickup)
                                        if ( $gmb_show_has_curbside_pickup ) {
                                            echo '<td data-gmb-column="Service options: Curbside pickup (has_curbside_pickup)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Service options: Delivery (has_delivery)
                                        if ( $gmb_show_has_delivery ) {
                                            echo '<td data-gmb-column="Service options: Delivery (has_delivery)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Service options: Drive-through (has_drive_through)
                                        if ( $gmb_show_has_drive_through ) {
                                            echo '<td data-gmb-column="Service options: Drive-through (has_drive_through)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Service options: In-store pickup (has_in_store_pickup)
                                        if ( $gmb_show_has_in_store_pickup ) {
                                            echo '<td data-gmb-column="Service options: In-store pickup (has_in_store_pickup)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Service options: In-store shopping (has_in_store_shopping)
                                        if ( $gmb_show_has_in_store_shopping ) {
                                            echo '<td data-gmb-column="Service options: In-store shopping (has_in_store_shopping)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    // Service options: Online care (has_video_visits)
                                    // Value based on the relevant location profile
                                        if ( $gmb_show_has_video_visits ) {
                                            echo '<td data-gmb-column="Service options: Online care (has_video_visits)" class="no-break">';
                                            echo $location_telemed_query ? 'Yes' : '';
                                            echo '</td>';
                                        }

                                    // Service options: Same-day delivery (has_delivery_same_day)
                                        if ( $gmb_show_has_delivery_same_day ) {
                                            echo '<td data-gmb-column="Service options: Same-day delivery (has_delivery_same_day)" class="no-break">[NOT APPLICABLE]</td>';
                                        }

                                    echo '</tr>';

                                $l++;
                            }
                        } // endforeach
                    }
                        
                endwhile;
                ?>
                </tbody>
            </table>
        </div>
    <?php
        else :
        echo 'No providers found';
    endif;

}

genesis();