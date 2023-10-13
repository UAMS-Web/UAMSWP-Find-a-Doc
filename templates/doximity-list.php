<?php
/**
 * Template Name: Doximity List
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
		'post_type' => 'provider',
		'post_status' => 'publish',
		'posts_per_page' => '-1', // Set for all
		'orderby' => 'title',
		'order' => 'ASC',
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
					$npi = str_pad($npi, 10, '0', STR_PAD_LEFT); // Add enough leading zeroes to reach 10 digits
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
						echo '<tr>';
						// NPI Number field
							// $npi = get_field('physician_npi',$post_id); // Added above
							// $npi = str_pad($npi, 10, '0', STR_PAD_LEFT); // Add enough leading zeroes to reach 10 digits // Added above
							echo '<td class="no-break">';
							echo $npi; // only display value if value is not empty or zero
							echo '</td>';

						// First Name field
							$first_name = get_field('physician_first_name',$post_id);
							echo '<td class="no-break">' . $first_name . '</td>';

						// Last Name field
							$last_name = get_field('physician_last_name',$post_id);
							echo '<td class="no-break">' . $last_name . '</td>';

						// Credentials (MD or DO) field
							echo '<td class="no-break">'. $degree_valid . '</td>';

						// Email Address field
							$e = 1;
							$contact_type = '';
							$contact_info = '';
							echo '<td class="no-break">';
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
							$affiliation_list = '';
							$i = 1;
							if ( $affiliations ) {
								foreach( $affiliations as $affiliation ):
									$affiliation_name = get_term( $affiliation, 'affiliation');
									$affiliation_list .= '<span class="no-break">' . $affiliation_name->name . '</span>';
									if( count($affiliations) > $i ) {
										$affiliation_list .= ', ';
									}
									$i++;
								endforeach;
							}
							echo '<td>';
							echo $affiliation_list ? $affiliation_list : '';
							echo '</td>';

						// Office Address 1 field

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
											$primary_appointment_building = get_field('location_building', $location );
											if ($primary_appointment_building) {
												$building = get_term($primary_appointment_building, "building");
												$building_slug = $building->slug;
												$building_name = $building->name;
											}
											$primary_appointment_floor = get_field_object('location_building_floor', $location );
												$primary_appointment_floor_value = $primary_appointment_floor['value'];
												$primary_appointment_floor_label = $primary_appointment_floor['choices'][ $primary_appointment_floor_value ];
											$primary_appointment_suite = get_field('location_suite', $location );
											$primary_appointment_address_2 =
												( ( $primary_appointment_building && $building_slug != '_none' ) ? $building_name . ( ( ($primary_appointment_floor && $primary_appointment_floor_value) || $primary_appointment_suite ) ? '<br />' : '' ) : '' )
												. ( $primary_appointment_floor && !empty($primary_appointment_floor_value) && $primary_appointment_floor_value != "0" ? $primary_appointment_floor_label . ( ( $primary_appointment_suite ) ? '<br />' : '' ) : '' )
												. ( $primary_appointment_suite ? $primary_appointment_suite : '' );
											$primary_appointment_address_2_deprecated = get_field('location_address_2', $location );
											if (!$primary_appointment_address_2) {
												$primary_appointment_address_2 = $primary_appointment_address_2_deprecated;
											}
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
							echo '<td class="no-break">';
							echo $primary_appointment_address_1 ? $primary_appointment_address_1 : '';
							echo '</td>';

						// Office Address 2 field
							echo '<td class="no-break">';
							echo $primary_appointment_address_2 ? $primary_appointment_address_2 : '';
							echo '</td>';

						// Office City field
							echo '<td class="no-break">';
							echo $primary_appointment_city ? $primary_appointment_city : '';
							echo '</td>';

						// Office State field
							echo '<td class="no-break">';
							echo $primary_appointment_state ? $primary_appointment_state : '';
							echo '</td>';

						// Office Zip field
							echo '<td class="no-break">';
							echo $primary_appointment_zip ? $primary_appointment_zip : '';
							echo '</td>';

						// Phone field
							echo '<td class="no-break">';
							echo $primary_appointment_phone ? $primary_appointment_phone : '';
							echo '</td>';

						// Fax field
							echo '<td class="no-break">';
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