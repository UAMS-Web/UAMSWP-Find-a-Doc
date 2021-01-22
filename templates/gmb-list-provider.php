<?php
	/**
	 *  Template Name: Full Screem
	 */

    // $image = (isset($wp->query_vars['provider'])) ? ' highlight="' . $wp->query_vars['marker'] . '"' : '';

// Remove the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' ); 

// Remove header
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

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

// Remove Skip Links from a template
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

// UAMS modifications
remove_action ( 'genesis_header', 'uamswp_site_image', 5 );
remove_action ( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action ( 'genesis_entry_header', 'genesis_do_post_title' );

// Dequeue Skip Links Script
add_action( 'wp_enqueue_scripts','child_dequeue_skip_links' );
function child_dequeue_skip_links() {
	wp_dequeue_script( 'skip-links' );
}

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

    if ( $query->have_posts() ) : ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-break">Store code</th>
                        <th class="no-break">Business name</th>
                        <th class="no-break">Address line 1</th>
                        <th class="no-break">Address line 2</th>
                        <th class="no-break">Address line 3</th>
                        <th class="no-break">Address line 4</th>
                        <th class="no-break">Address line 5</th>
                        <th class="no-break">Sub-locality</th>
                        <th class="no-break">Locality</th>
                        <th class="no-break">Administrative area</th>
                        <th class="no-break">Country / Region</th>
                        <th class="no-break">Postal code</th>
                        <th class="no-break">Primary phone</th>
                        <th class="no-break">Additional phones</th>
                        <th class="no-break">Website</th>
                        <!-- <th class="no-break">Primary category</th>
                        <th class="no-break">Additional categories</th> -->
                        <th class="no-break">Sunday hours</th>
                        <th class="no-break">Monday hours</th>
                        <th class="no-break">Tuesday hours</th>
                        <th class="no-break">Wednesday hours</th>
                        <th class="no-break">Thursday hours</th>
                        <th class="no-break">Friday hours</th>
                        <th class="no-break">Saturday hours</th>
                        <th class="no-break">Special hours</th>
                        <th class="no-break">From the business</th>
                        <th class="no-break">Opening date</th>
                        <th class="no-break">Labels</th>
                        <th class="no-break">AdWords location extensions phone</th>
                        <th class="no-break">Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)</th>
                        <th class="no-break">Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)</th>
                        <th class="no-break">Accessibility: Wheelchair accessible parking lot (has_wheelchair_accessible_parking)</th>
                        <th class="no-break">Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)</th>
                        <th class="no-break">Amenities: Restroom (has_restroom)</th>
                        <th class="no-break">Amenities: Wi-Fi (wi_fi)</th>
                        <th class="no-break">From the business: Identifies as Black-owned (is_black_owned)</th>
                        <th class="no-break">From the business: Identifies as veteran-led (is_owned_by_veterans)</th>
                        <th class="no-break">From the business: Identifies as women-led (is_owned_by_women)</th>
                        <th class="no-break">Health &amp; safety: Appointment required (requires_appointments)</th>
                        <th class="no-break">Health &amp; safety: Mask required (requires_masks_customers)</th>
                        <th class="no-break">Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)</th>
                        <th class="no-break">Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)</th>
                        <th class="no-break">Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)</th>
                        <th class="no-break">Health &amp; safety: Staff wear masks (requires_masks_staff)</th>
                        <th class="no-break">Health &amp; safety: Temperature check required (requires_temperature_check_customers)</th>
                        <th class="no-break">Offerings: Passport photos (has_onsite_passport_photos)</th>
                        <th class="no-break">Payments: Cash-only (requires_cash_only)</th>
                        <th class="no-break">Payments: Checks (pay_check)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)</th>
                        <th class="no-break">Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)</th>
                        <th class="no-break">Payments: Debit cards (pay_debit_card)</th>
                        <th class="no-break">Payments: NFC mobile payments (pay_mobile_nfc)</th>
                        <th class="no-break">Place page URLs: Appointment links (url_appointment)</th>
                        <th class="no-break">Place page URLs: COVID-19 info link (url_covid_19_info_page)</th>
                        <th class="no-break">Place page URLs: Menu link (url_menu)</th>
                        <th class="no-break">Place page URLs: Virtual care link (url_facility_telemedicine_page)</th>
                        <th class="no-break">Planning: LGBTQ friendly (welcomes_lgbtq)</th>
                        <th class="no-break">Planning: Transgender safespace (is_transgender_safespace)</th>
                        <th class="no-break">Service options: Curbside pickup (has_curbside_pickup)</th>
                        <th class="no-break">Service options: Delivery (has_delivery)</th>
                        <th class="no-break">Service options: Drive-through (has_drive_through)</th>
                        <th class="no-break">Service options: In-store pickup (has_in_store_pickup)</th>
                        <th class="no-break">Service options: In-store shopping (has_in_store_shopping)</th>
                        <th class="no-break">Service options: Online care (has_video_visits)</th>
                        <th class="no-break">Service options: Same-day delivery (has_delivery_same_day)</th>
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
                    foreach( $locations as $location ) {
                        if ( get_post_status ( $location ) == 'publish' ) {
                            $location_valid = true;
                            $break;
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
                    $phys_title_name = $resident ? $resident_title_name : get_term( $phys_title, 'clinical_title' )->name;

                    // Create the table
                    if ( $locations && $location_valid && !$resident ) {

                        // Create row for each valid location
                        foreach( $locations as $location ) {
                            if ( get_post_status ( $location ) == 'publish' ) {

                                    // Start table row
                                    echo '<tr>';

                                    // Store code
                                        $location_slug = get_post_field( 'post_name', $location );

                                        // Option 1: Provider slug plus numeral
                                        echo '<td data-gmb-column="Store code" class="no-break">';
                                        echo $profile_slug;
                                        echo $l > 1 ? '-' . $l : '';
                                        echo '</td>';

                                        // Option 2: Provider slug plus Location slug
                                        // echo '<td data-gmb-column="Store code" class="no-break">';
                                        // echo $profile_slug . '_' . $location_slug;
                                        // echo '</td>';

                                    // Business name
                                        echo '<td data-gmb-column="Business name" class="no-break">UAMS - ' . $full_name . '</td>';

                                    // Address line 1

                                        $location_title = get_the_title( $location );
                                        $location_address_1 = get_field( 'location_address_1', $location );
                                        $location_address_2 = get_field( 'location_address_2', $location );
                                        $location_city = get_field( 'location_city', $location );
                                        $location_state = get_field( 'location_state', $location );
                                        $location_zip = get_field( 'location_zip', $location );
                                        $location_phone = get_field( 'location_phone', $location );
                                        $location_fax = get_field( 'location_fax', $location );
                                        $location_hours_group = get_field('location_hours_group', $location );
                                        $location_telemed_query = $location_hours_group['location_telemed_query'];

                                        echo '<td data-gmb-column="Address line 1" class="no-break">';
                                        echo $location_address_1 ? $location_address_1 : '';
                                        echo '</td>';

                                    // Address line 2
                                    // Intentionally left blank for now. Line 2 isn't separated with a comma when displayed in Google.
                                        echo '<td data-gmb-column="Address line 2" class="no-break">';
                                        //echo $location_address_2 ? $location_address_2 : '';
                                        echo '</td>';

                                    // Address line 3
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Address line 3" class="no-break"></td>';

                                    // Address line 4
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Address line 4" class="no-break"></td>';

                                    // Address line 5
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Address line 5" class="no-break"></td>';

                                    // Sub-locality
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Sub-locality" class="no-break"></td>';

                                    // Locality
                                        echo '<td data-gmb-column="Locality" class="no-break">';
                                        echo $location_city ? $location_city : '';
                                        echo '</td>';

                                    // Administrative area
                                        echo '<td data-gmb-column="Administrative area" class="no-break">';
                                        echo $location_state ? $location_state : '';
                                        echo '</td>';

                                    // Country / Region
                                        echo '<td data-gmb-column="Country / Region" class="no-break">US</td>';

                                    // Postal code
                                        echo '<td data-gmb-column="Postal code" class="no-break">';
                                        echo $location_zip ? $location_zip : '';
                                        echo '</td>';

                                    // Primary phone
                                        echo '<td data-gmb-column="Primary phone" class="no-break">';
                                        echo $location_phone ? $location_phone : '';
                                        echo '</td>';

                                    // Additional phones
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Additional phones" class="no-break"></td>';

                                    // Website
                                        echo '<td data-gmb-column="Website" class="no-break">';
                                        echo 'https://uamshealth.com/provider/' . $profile_slug . '/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=profile';
                                        echo '</td>';

                                    // Primary category
                                    // Hiding this column so that we don't overwrite existing data. Will need to instead download/reimport data from GMB to update category in bulk.
                                    //    echo '<td data-gmb-column="Primary category" class="no-break">Doctor</td>';

                                    // Additional categories
                                    // Hiding this column so that we don't overwrite existing data. Will need to instead download/reimport data from GMB to update category in bulk.
                                    //    echo '<td data-gmb-column="Additional categories" class="no-break"></td>';

                                    // Sunday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Sunday hours" class="no-break"></td>';

                                    // Monday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Monday hours" class="no-break"></td>';

                                    // Tuesday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Tuesday hours" class="no-break"></td>';

                                    // Wednesday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Wednesday hours" class="no-break"></td>';

                                    // Thursday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Thursday hours" class="no-break"></td>';

                                    // Friday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Friday hours" class="no-break"></td>';

                                    // Saturday hours
                                    // Intentionally left blank for now
                                    // Format = 08:00-16:30
                                        echo '<td data-gmb-column="Saturday hours" class="no-break"></td>';

                                    // Special hours
                                    // Intentionally left blank for now
                                    // Format = 2021-12-31: 05:00-23:00, 2022-01-01: x
                                        echo '<td data-gmb-column="Special hours" class="no-break"></td>';

                                    // From the business
                                        $excerpt = get_field('physician_short_clinical_bio',$post_id);
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
                                        echo '<td data-gmb-column="From the business"><span style="display: block; width: 19.6875em"></span>';
                                        echo $excerpt;
                                        echo '</td>';

                                    // Opening date
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Opening date" class="no-break"></td>';

                                    // Labels
                                    // Intentionally left blank
                                        $service_line = '';
                                        $service_line = get_term( get_field('physician_service_line'), 'service_line' )->name;
    
                                        echo '<td data-gmb-column="Labels" class="no-break">';
                                        echo $service_line ? $service_line : '';
                                        echo '</td>';

                                    // AdWords location extensions phone
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="AdWords location extensions phone" class="no-break"></td>';

                                    // Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
                                        echo '<td data-gmb-column="Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)" class="no-break">Yes</td>';

                                    // Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
                                        echo '<td data-gmb-column="Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)" class="no-break">Yes</td>';

                                    // Accessibility: Wheelchair accessible parking lot (has_wheelchair_accessible_parking)
                                        echo '<td data-gmb-column="Accessibility: Wheelchair accessible parking lot (has_wheelchair_accessible_parking)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
                                        echo '<td data-gmb-column="Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)" class="no-break">Yes</td>';

                                    // Amenities: Restroom (has_restroom)
                                        echo '<td data-gmb-column="Amenities: Restroom (has_restroom)" class="no-break">Yes</td>';

                                    // Amenities: Wi-Fi (wi_fi)
                                        echo '<td data-gmb-column="Amenities: Wi-Fi (wi_fi)" class="no-break">[NOT APPLICABLE]</td>';

                                    // From the business: Identifies as Black-owned (is_black_owned)
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="From the business: Identifies as Black-owned (is_black_owned)" class="no-break"></td>';

                                    // From the business: Identifies as veteran-led (is_owned_by_veterans)
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="From the business: Identifies as veteran-led (is_owned_by_veterans)" class="no-break"></td>';

                                    // From the business: Identifies as women-led (is_owned_by_women)
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="From the business: Identifies as women-led (is_owned_by_women)" class="no-break"></td>';

                                    // Health &amp; safety: Appointment required (requires_appointments)
                                    // Intentionally left blank for now
                                        echo '<td data-gmb-column="Health &amp; safety: Appointment required (requires_appointments)" class="no-break">';
                                        //echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Health &amp; safety: Mask required (requires_masks_customers)
                                        echo '<td data-gmb-column="Health &amp; safety: Mask required (requires_masks_customers)" class="no-break">';
                                        echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)
                                        echo '<td data-gmb-column="Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)
                                        echo '<td data-gmb-column="Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)" class="no-break">';
                                        echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)
                                        echo '<td data-gmb-column="Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)" class="no-break">';
                                        echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Health &amp; safety: Staff wear masks (requires_masks_staff)
                                        echo '<td data-gmb-column="Health &amp; safety: Staff wear masks (requires_masks_staff)" class="no-break">';
                                        echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Health &amp; safety: Temperature check required (requires_temperature_check_customers)
                                        echo '<td data-gmb-column="Health &amp; safety: Temperature check required (requires_temperature_check_customers)" class="no-break">';
                                        echo $covid19 ?  'Yes' : '';
                                        echo '</td>';

                                    // Offerings: Passport photos (has_onsite_passport_photos)
                                        echo '<td data-gmb-column="Offerings: Passport photos (has_onsite_passport_photos)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Cash-only (requires_cash_only)
                                        echo '<td data-gmb-column="Payments: Cash-only (requires_cash_only)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Checks (pay_check)
                                        echo '<td data-gmb-column="Payments: Checks (pay_check)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): American Express (american_express)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): China Union Pay (china_union_pay)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): Diners Club (diners_club)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): Discover (discover)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): JCB (jcb)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): MasterCard (mastercard)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)
                                        echo '<td data-gmb-column="Payments: Credit cards (pay_credit_card_types_accepted): VISA (visa)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: Debit cards (pay_debit_card)
                                        echo '<td data-gmb-column="Payments: Debit cards (pay_debit_card)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Payments: NFC mobile payments (pay_mobile_nfc)
                                        echo '<td data-gmb-column="Payments: NFC mobile payments (pay_mobile_nfc)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Place page URLs: Appointment links (url_appointment)
                                    // Intentionally left blank
                                        echo '<td data-gmb-column="Place page URLs: Appointment links (url_appointment)" class="no-break"></td>';

                                    // Place page URLs: COVID-19 info link (url_covid_19_info_page)
                                        echo '<td data-gmb-column="Place page URLs: COVID-19 info link (url_covid_19_info_page)" class="no-break">';
                                        echo 'https://uamshealth.com/coronavirus/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=covid-19-info-link';
                                        echo '</td>';

                                    // Place page URLs: Menu link (url_menu)
                                        echo '<td data-gmb-column="Place page URLs: Menu link (url_menu)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Place page URLs: Virtual care link (url_facility_telemedicine_page)
                                        echo '<td data-gmb-column="Place page URLs: Virtual care link (url_facility_telemedicine_page)" class="no-break">';
                                        echo $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=virtual-care-link#telemedicine-info' : '';
                                        echo '</td>';

                                    // Planning: LGBTQ friendly (welcomes_lgbtq)
                                        echo '<td data-gmb-column="Planning: LGBTQ friendly (welcomes_lgbtq)" class="no-break">';
                                        echo '';
                                        echo '</td>';

                                    // Planning: Transgender safespace (is_transgender_safespace)
                                        echo '<td data-gmb-column="Planning: Transgender safespace (is_transgender_safespace)" class="no-break">';
                                        echo '';
                                        echo '</td>';

                                    // Service options: Curbside pickup (has_curbside_pickup)
                                        echo '<td data-gmb-column="Service options: Curbside pickup (has_curbside_pickup)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Service options: Delivery (has_delivery)
                                        echo '<td data-gmb-column="Service options: Delivery (has_delivery)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Service options: Drive-through (has_drive_through)
                                        echo '<td data-gmb-column="Service options: Drive-through (has_drive_through)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Service options: In-store pickup (has_in_store_pickup)
                                        echo '<td data-gmb-column="Service options: In-store pickup (has_in_store_pickup)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Service options: In-store shopping (has_in_store_shopping)
                                        echo '<td data-gmb-column="Service options: In-store shopping (has_in_store_shopping)" class="no-break">[NOT APPLICABLE]</td>';

                                    // Service options: Online care (has_video_visits)
                                    // Value based on the relevant location profile
                                        echo '<td data-gmb-column="Service options: Online care (has_video_visits)" class="no-break">';
                                        echo $location_telemed_query ? 'Yes' : '';
                                        echo '</td>';

                                    // Service options: Same-day delivery (has_delivery_same_day)
                                        echo '<td data-gmb-column="Service options: Same-day delivery (has_delivery_same_day)" class="no-break">[NOT APPLICABLE]</td>';

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