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
                    echo '<tr>';
                    
                    // NPI Number field
                    // First Name field
                    // Last Name field
                    // Credentials (MD or DO) field
                    $degrees = get_field('physician_degree',$post_id);
                    $degree_list = '';
                    $i = 1;
                    if ( $degrees ) {
                        foreach( $degrees as $degree ):
                            $degree_name = get_term( $degree, 'degree');
                            $degree_list .= $degree_name->name;
                            if( count($degrees) > $i ) {
                                $degree_list .= ", ";
                            }
                            $i++;
                        endforeach;
                    } 
                    echo '<td>'. $degree_list .'</td>';
                    // Email Address field
                    // Facility Name field
                    // Office Address 1 field
                    // Office Address 2 field
                    // Office City field
                    // Office State field
                    // Office Zip field
                    // Phone field
                    // Fax field
                    // Specialty field
                    // Sub-Specialty field
                    // Example of repeater list
                    echo '</tr>';
                        
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