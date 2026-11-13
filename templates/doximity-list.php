<?php
/**
 *  Template Name: Doximity List
 */

// Remove the primary navigation
remove_action('genesis_after_header', 'genesis_do_nav');

// Remove header
remove_action('genesis_header', 'genesis_do_header');
remove_action('genesis_header', 'genesis_header_markup_open', 5);
remove_action('genesis_header', 'genesis_header_markup_close', 15);

// Remove primary nav
remove_action('genesis_after_header', 'custom_nav_menu', 5);

// Remove Footer Widgets
remove_action('genesis_before_footer', 'genesis_footer_widget_areas');

// Remove Footer
remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

// Force full-width-content layout setting
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

// Remove Breadcrumbs
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
remove_action('genesis_after_header', 'genesis_do_breadcrumbs');
remove_action('genesis_after_header', 'sp_breadcrumb_after_header');

// Remove Skip Links from a template
remove_action('genesis_before_header', 'genesis_skip_links', 5);

// UAMS modifications
remove_action('genesis_header', 'uamswp_site_image', 5);
remove_action('genesis_after_header', 'genesis_do_breadcrumbs');
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_before_header', 'uams_toggle_search', 12);
remove_action('genesis_before_header', 'uamswp_skip_links', 5);

// Dequeue Skip Links Script
add_action('wp_enqueue_scripts', 'child_dequeue_skip_links');
// Remove GTM container
remove_action('wp_head', 'uamswp_gtm_1');
remove_action('genesis_before', 'uamswp_gtm_2');

add_filter('wp_nav_menu', '__return_false');

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'display_provider_image_doximity_list');
function display_provider_image_doximity_list(): void
{
    // Custom WP_Query args
    $args = [
        'post_type' => 'provider',
        'post_status' => 'publish',
        'posts_per_page' => '-1', // Set for all
        'orderby' => 'title',
        'order' => 'ASC',
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) { ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Required</th>
                        <th colspan="4">Basic Information</th>
                        <th colspan="8">Address and Phone</th>
                        <th colspan="2">(Suggested) Expertise</th>
                    </tr>
                    <tr>
                        <th class="no-break">NPI Number</th>
                        <th class="no-break">First Name</th>
                        <th class="no-break">Last Name</th>
                        <th class="no-break">Credentials (MD or DO)</th>
                        <th class="no-break">Email Address</th>
                        <th class="no-break">Facility Name</th>
                        <th class="no-break">Office Address 1</th>
                        <th class="no-break">Office Address 2</th>
                        <th class="no-break">Office City</th>
                        <th class="no-break">Office State</th>
                        <th class="no-break">Office Zip</th>
                        <th class="no-break">Phone</th>
                        <th class="no-break">Fax</th>
                        <th class="no-break">Specialty</th>
                        <th class="no-break">Sub-Specialty</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = uamswp_fad_int(get_the_ID());

                    // First, check if provider has desired degree
                    $degree_md = [ // list valid versions of MD
                        'M.D.',
                    ];
                    $degree_do = [ // list valid versions of DO
                        'D.O.',
                    ];
                    $degree_np = [ // list valid versions of NP
                        'CNP',
                        'FNP-C',
                    ];
                    $degree_pa = [ // list valid versions of PA
                        'PA',
                    ];
                    $degrees = get_field('physician_degree', $post_id);
                    $degree_valid = '';
                    $d = 1;
                    $npi = get_field('physician_npi', $post_id);
                    $npi_valid = false;
                    if (! empty($npi) && $npi != '0') {
                        $npi_valid = true;
                    }
                    if ($degrees) {
                        foreach (uamswp_fad_arr($degrees) as $degree) {
                            $degree_name = uamswp_fad_term_name($degree, 'degree');
                            if (($d < 2) && (in_array($degree_name, $degree_md) || in_array($degree_name, $degree_do) || in_array($degree_name, $degree_np) || in_array($degree_name, $degree_pa))) {
                                $degree_valid = $degree_name;
                                if (in_array($degree_valid, $degree_md)) {
                                    $degree_valid = 'MD';
                                } elseif (in_array($degree_valid, $degree_do)) {
                                    $degree_valid = 'DO';
                                } elseif (in_array($degree_valid, $degree_np)) {
                                    $degree_valid = 'NP';
                                } elseif (! in_array($degree_valid, $degree_pa)) {
                                    $degree_valid = '';
                                }
                                $d++;
                            }
                        }
                    }
                    // Check if there is a hospital affiliation
                    $affiliation_valid = false;
                    $affiliations = get_field('physician_affiliation', $post_id);
                    if (! empty($affiliations)) {
                        $affiliation_valid = true;
                    }
                    // Check for valid locations
                    $locations = uamswp_fad_arr(get_field('physician_locations', $post_id));
                    $location_valid = false;
                    foreach ($locations as $location) {
                        // get_post_status() returns a status string or false, so loose and strict equality agree.
                        // @pest-mutate-ignore: EqualToIdentical
                        if (get_post_status(uamswp_fad_int($location)) == 'publish') {
                            $location_valid = true;
                            break;
                        }
                    }
                    // Create the table
                    if ($degree_valid && $npi_valid && $affiliation_valid && $location_valid) {
                        echo '<tr>';
                        // NPI Number field
                        // $npi = get_field('physician_npi',$post_id); // Added above
                        echo '<td class="no-break">';
                        echo uamswp_fad_str($npi); // only display value if value is not empty or zero
                        echo '</td>';

                        // First Name field
                        $first_name = get_field('physician_first_name', $post_id);
                        echo '<td class="no-break">'.uamswp_fad_str($first_name).'</td>';

                        // Last Name field
                        $last_name = get_field('physician_last_name', $post_id);
                        echo '<td class="no-break">'.uamswp_fad_str($last_name).'</td>';

                        // Credentials (MD or DO) field
                        echo '<td class="no-break">'.$degree_valid.'</td>';

                        // Email Address field
                        $e = 1;
                        echo '<td class="no-break">';
                        if (have_rows('physician_contact_information', $post_id)) {
                            while (have_rows('physician_contact_information', $post_id)) {
                                the_row();
                                /** @var string|false|null $contact_type */
                                $contact_type = get_sub_field('type');
                                /** @var string|false|null $contact_info */
                                $contact_info = get_sub_field('information');
                                // The ACF select sub-field returns a string, so loose and strict equality agree.
                                // @pest-mutate-ignore: EqualToIdentical
                                if ($contact_type == 'email' && $e < 2) { // Only display the first instance of an email row
                                    echo uamswp_fad_str($contact_info);
                                    $e++;
                                }
                            }
                        }
                        echo '</td>';

                        // Facility Name field
                        $affiliation_list = '';
                        $i = 1;
                        // A row needs an affiliation, so the list is never empty.
                        foreach (uamswp_fad_arr($affiliations) as $affiliation) {
                            $affiliation_list .= '<span class="no-break">'.uamswp_fad_term_name($affiliation, 'affiliation').'</span>';
                            if (count(uamswp_fad_arr($affiliations)) > $i) {
                                $affiliation_list .= ', ';
                            }
                            $i++;
                        }
                        echo '<td>';
                        echo $affiliation_list;
                        echo '</td>';

                        // Office Address 1 field

                        // Get primary appointment location name
                        $l = 1;
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_address_1 = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_address_2 = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_city = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_state = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_zip = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_phone = '';
                        // The first published location assigns this, and a row needs one.
                        // @pest-mutate-ignore: EmptyStringToNotEmpty
                        $primary_appointment_fax = '';
                        // A row needs a valid location, so the list is never empty.
                        foreach ($locations as $location) {
                            if ($l < 2) {
                                // get_post_status() returns a status string or false, so loose and strict equality agree.
                                // @pest-mutate-ignore: EqualToIdentical
                                if (get_post_status(uamswp_fad_int($location)) == 'publish') {

                                    /**
                                     * Which location supplies each address component. This
                                     * export read the location's own fields unconditionally
                                     * before this change, so a child location -- whose
                                     * Address tab was suppressed in the Control Panel --
                                     * exported a blank office address.
                                     */
                                    $location_source_ids = uamswp_fad_location_source_ids(uamswp_fad_int($location));

                                    $primary_appointment_address_1 = uamswp_fad_str(get_field('location_address_1', $location_source_ids['street']));
                                    $primary_appointment_building = get_field('location_building', $location_source_ids['facility']);
                                    if ($primary_appointment_building) {
                                        $building = get_term(uamswp_fad_int($primary_appointment_building), 'building');
                                        $building_found = is_object($building) && ! is_wp_error($building);
                                        $building_slug = $building_found ? uamswp_fad_str($building->slug) : '';
                                        $building_name = $building_found ? uamswp_fad_str($building->name) : '';
                                    }
                                    /** @var array<string, mixed>|false $primary_appointment_floor */
                                    $primary_appointment_floor = get_field_object('location_building_floor', $location_source_ids['unit']);
                                    // The initial value is read only when a floor field object exists, and that case assigns it again.
                                    // @pest-mutate-ignore: EmptyStringToNotEmpty
                                    $primary_appointment_floor_value = '';
                                    // The initial value is read only when a floor field object exists, and that case assigns it again.
                                    // @pest-mutate-ignore: EmptyStringToNotEmpty
                                    $primary_appointment_floor_label = '';
                                    if ($primary_appointment_floor) {
                                        $primary_appointment_floor_value = uamswp_fad_str($primary_appointment_floor['value'] ?? null);
                                        $primary_appointment_floor_label = uamswp_fad_str(uamswp_fad_assoc($primary_appointment_floor['choices'] ?? null)[$primary_appointment_floor_value] ?? null);
                                    }
                                    $primary_appointment_suite = uamswp_fad_str(get_field('location_suite', $location_source_ids['unit'])) ?: false;
                                    // The building slug is a string, so loose and strict inequality agree, and the building name is read only when a building exists, which sets it.
                                    // @pest-mutate-ignore: NotEqualToNotIdentical,EmptyStringToNotEmpty
                                    $primary_appointment_address_2 = uamswp_location_address_2(($primary_appointment_building && $building_slug != '_none'), $building_name ?? '', $primary_appointment_floor, $primary_appointment_floor_value, $primary_appointment_floor_label, $primary_appointment_suite, '<br />', '<br />');
                                    $primary_appointment_address_2_deprecated = uamswp_fad_str(get_field('location_address_2', $location_source_ids['street']));
                                    if (! $primary_appointment_address_2) {
                                        $primary_appointment_address_2 = $primary_appointment_address_2_deprecated;
                                    }
                                    $primary_appointment_city = uamswp_fad_str(get_field('location_city', $location_source_ids['street']));
                                    $primary_appointment_state = uamswp_fad_str(get_field('location_state', $location_source_ids['street']));
                                    $primary_appointment_zip = uamswp_fad_str(get_field('location_zip', $location_source_ids['street']));
                                    $primary_appointment_phone = uamswp_fad_str(get_field('location_phone', $location));
                                    $primary_appointment_fax = uamswp_fad_str(get_field('location_fax', $location));
                                    $l++;
                                }
                            }
                        } // endforeach
                        echo '<td class="no-break">';
                        echo $primary_appointment_address_1 ?: '';
                        echo '</td>';

                        // Office Address 2 field
                        echo '<td class="no-break">';
                        echo $primary_appointment_address_2 ?: '';
                        echo '</td>';

                        // Office City field
                        echo '<td class="no-break">';
                        echo $primary_appointment_city ?: '';
                        echo '</td>';

                        // Office State field
                        echo '<td class="no-break">';
                        echo $primary_appointment_state ?: '';
                        echo '</td>';

                        // Office Zip field
                        echo '<td class="no-break">';
                        echo $primary_appointment_zip ?: '';
                        echo '</td>';

                        // Phone field
                        echo '<td class="no-break">';
                        echo $primary_appointment_phone ?: '';
                        echo '</td>';

                        // Fax field
                        echo '<td class="no-break">';
                        echo $primary_appointment_fax ?: '';
                        echo '</td>';

                        // Specialty field
                        echo '<td>'.'</td>';

                        // Sub-Specialty field
                        echo '<td>'.'</td>';

                        echo '</tr>';
                    }

                }
        ?>
            </tbody>
        </table>
    </div>
    <?php
    } else {
        echo 'No providers found';
    }

}

genesis();
