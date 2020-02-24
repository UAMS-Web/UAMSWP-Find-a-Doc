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
        "posts_per_page" => "10",
        "orderby" => "title",
        "order" => "ASC",
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) : ?>
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
                        <th>NPI Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Credentials (MD or DO)</th>
                        <th>Email Address</th>
                        <th>Facility Name</th>
                        <th>Office Address 1</th>
                        <th>Office Address 2</th>
                        <th>Office City</th>
                        <th>Office State</th>
                        <th>Office Zip</th>
                        <th>Phone</th>
                        <th>Fax</th>
                        <th>Specialty</th>
                        <th>Sub-Specialty</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
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
                        'FNP-C'
                    );
                    $degree_pa = array( // list valid versions of PA
                        'P.A.'
                    );
                    $degrees = get_field('physician_degree',$post_id);
                    $degree_valid = '';
                    $d = 1;
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
                    if ( $degree_valid ) {
                    
                        // NPI Number field
                            $npi = get_field('physician_npi',$post_id);
                            echo '<td>';
                            echo $npi ? $npi : ''; // only display value if value is not empty or zero
                            echo '</td>';

                        // First Name field
                            $first_name = get_field('physician_first_name',$post_id);
                            echo '<td>' . $first_name . '</td>';

                        // Last Name field
                            $last_name = get_field('physician_last_name',$post_id);
                            echo '<td>' . $last_name . '</td>';

                        // Credentials (MD or DO) field
                            echo '<td>'. $degree_valid . '</td>';

                        // Email Address field
                            $e = 1;
                            $contact_type = '';
                            $contact_info = '';
                            echo '<td>';
                                if( have_rows('physician_contact_information',$post_id) ):
                                    while ( have_rows('physician_contact_information',$post_id) ) : the_row();
                                        $contact_type = get_sub_field('type');
                                        $contact_info = get_sub_field('information');
                                        if ( $contact_type == 'email' && 2 > $e ) { // Only display the first instance of an email row
                                            echo $contact_info;
                                            $e++;
                                        }
                                    endwhile;
                                endif;
                            echo '</td>';

                        // Facility Name field
                        
                            // Check for valid locations
                            $locations = get_field('physician_locations',$post_id);
                            $location_valid = false;
                            foreach( $locations as $location ) {
                                if ( get_post_status ( $location ) == 'publish' ) {
                                    $location_valid = true;
                                    $break;
                                }
                            }
                            // Get primary appointment location name
                            $l = 1;
                            $primary_appointment_title = '';
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
                                            $primary_appointment_title = get_the_title( $location );
                                            $primary_appointment_address_1 = get_field( 'location_address_1', $location );
                                            $primary_appointment_address_2 = get_field( 'location_address_2', $location );
                                            $primary_appointment_city = get_field( 'location_city', $location );
                                            $primary_appointment_state = get_field( 'location_state', $location );
                                            $primary_appointment_zip = get_field( 'location_zip', $location );
                                            $primary_appointment_phone = get_field( 'location_phone', $location );
                                            $primary_appointment_fax = get_field( 'location_fax', $location );
                                            $l++;
                                        }
                                    }
                                } // endforeach
                            }
                            echo '<td>';
                            echo $primary_appointment_title ? $primary_appointment_title : '';
                            echo '</td>';

                        // Office Address 1 field
                            echo '<td>';
                            echo $primary_appointment_address_1 ? $primary_appointment_address_1 : '';
                            echo '</td>';

                        // Office Address 2 field
                            echo '<td>';
                            echo $primary_appointment_address_2 ? $primary_appointment_address_2 : '';
                            echo '</td>';

                        // Office City field
                            echo '<td>';
                            echo $primary_appointment_city ? $primary_appointment_city : '';
                            echo '</td>';

                        // Office State field
                            echo '<td>';
                            echo $primary_appointment_state ? $primary_appointment_state : '';
                            echo '</td>';

                        // Office Zip field
                            echo '<td>';
                            echo $primary_appointment_zip ? $primary_appointment_zip : '';
                            echo '</td>';

                        // Phone field
                            echo '<td>';
                            echo $primary_appointment_phone ? $primary_appointment_phone : '';
                            echo '</td>';

                        // Fax field
                            echo '<td>';
                            echo $primary_appointment_fax ? $primary_appointment_fax : '';
                            echo '</td>';

                        // Specialty field
                            echo '<td>' . '</td>';

                        // Sub-Specialty field
                            echo '<td>' . '</td>';

                        echo '</tr>';
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