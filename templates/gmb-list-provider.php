<?php
/**
 * Template Name: GMB Provider List
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
						<th class="no-break">Latitude</th>
						<th class="no-break">Longitude</th>
						<th class="no-break">Primary phone</th>
						<th class="no-break">Additional phones</th>
						<th class="no-break">Website</th>
						<th class="no-break">Primary category</th>
						<th class="no-break">Additional categories</th>
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
						<th class="no-break">Logo photo</th>
						<th class="no-break">Cover photo</th>
						<th class="no-break">Other photos</th>
						<th class="no-break">Labels</th>
						<th class="no-break">AdWords location extensions phone</th>
						<th class="no-break">Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)</th>
						<th class="no-break">Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)</th>
						<th class="no-break">Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)</th>
						<th class="no-break">Amenities: Restroom (has_restroom)</th>
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
					$full_name = get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id) . (get_field('physician_pedigree',$post_id) ? '&nbsp;' . get_field('physician_pedigree',$post_id) : '') . ( $degree_list ? ', ' . $degree_list : '' );
					$medium_name = ($prefix ? $prefix .' ' : '') . get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id);
					$short_name = $prefix ? $prefix .'&nbsp;' .get_field('physician_last_name',$post_id) : get_field('physician_first_name',$post_id) .' ' .(get_field('physician_middle_name',$post_id) ? get_field('physician_middle_name',$post_id) . ' ' : '') . get_field('physician_last_name',$post_id) . (get_field('physician_pedigree',$post_id) ? '&nbsp;' . get_field('physician_pedigree',$post_id) : '');
					$resident = get_field('physician_resident',$post_id);
					$provider_specialty = get_field( 'physician_title', $post_id );
					$provider_specialty_term = get_term($provider_specialty, 'clinical_title');
					$provider_specialty_name = $provider_specialty_term->name;
					$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term);
					$provider_occupation_title = $provider_occupation_title ?: $provider_specialty_name;
					$vowels = array('a','e','i','o','u');
					if (in_array(strtolower($provider_occupation_title)[0], $vowels)) { // Defines a or an, based on whether clinical occupation title starts with vowel
						$provider_occupation_title_indef_article = 'an';
					} else {
						$provider_occupation_title_indef_article = 'a';
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

						// Create row for each valid location
						foreach( $locations as $location ) {
							if ( get_post_status ( $location ) == 'publish' ) {

									// Start table row
									echo '<tr>';

									// Store code
										$location_slug = get_post_field( 'post_name', $location );
										$store_code = $profile_slug . '_' . $location_slug;

										echo '<td data-gmb-column="Store code" class="no-break">';
										echo $store_code;
										echo '</td>';

									// Business name
										echo '<td data-gmb-column="Business name" class="no-break">UAMS - ' . $full_name . '</td>';

									// Address line 1

										// Parent Location

											$location_post_id = $location;
											$location_child_id = $location;
											$location_has_parent = get_field('location_parent',$location_post_id);
											$location_parent_id = get_field('location_parent_id',$location_post_id);
											$location_parent_title = ''; // Eliminate PHP errors
											$location_parent_url = ''; // Eliminate PHP errors
											$location_parent_location = ''; // Eliminate PHP errors

											if (
												$location_has_parent
												&&
												$location_parent_id
											) {

												$location_parent_location = get_post( $location_parent_id );

											}

										// Get Post ID for Address & Image fields

											if ( $location_parent_location ) {

												$location_post_id = $location_parent_location->ID;
												$location_parent_title = $location_parent_location->post_title;
												$location_parent_url = user_trailingslashit(get_permalink( $location_post_id ));

											}

										// Create location variables

											// Location title

												$location_title = get_the_title( $location_child_id );

											// Address 1

												$location_address_1 = get_field( 'location_address_1', $location_post_id );

											// Address 2

												// Building

													// Query: Is this location contained within a larger facility rather than being its own standalone facility?

														$location_building_query = get_field('location_building_query', $location_post_id ) ?? null;

													// Get building selection

														$location_building = null;

														if (
															!isset($location_building_query)
															||
															$location_building_query
														) {

															$location_building = get_field( 'location_building', $location_post_id );

														}

														// Get building term and its values

															$building = $location_building ? get_term( $location_building, 'building' ) : null;
															$building_type = null;
															$building_slug = null;
															$building_name = null;

															if (
																$building
																&&
																is_object($building)
															) {

																$building_type = get_field( 'facility_place_subtype', $building ) ?? null;
																$building_slug = $building->slug;
																$building_name = $building->name;

															}

														// Reset values if building is set to 'None'

															if (
																$building_slug
																&&
																$building_slug == '_none'
															) {

																$location_building = null;
																$building = null;
																$building_type = null;
																$building_slug = null;
																$building_name = null;

															}

													// Building Floor

														$location_floor = null;
														$location_floor_value = null;
														$location_floor_label = null;

														// Get building floor selection

															if (
																!isset($location_building_query)
																||
																$location_building_query
															) {

																$location_floor = get_field_object('location_building_floor', $location_post_id );

															}

															// Get building floor values

																if ( $location_floor ) {

																	$location_floor_value = $location_floor['value'];
																	$location_floor_label = $location_floor['choices'][ $location_floor_value ];

																}

																// Reset values if building is set to 'Single-Story Building'

																	if (
																		isset($location_floor_value)
																		&&
																		!$location_floor_value
																	) {

																		$location_floor = null;
																		$location_floor_value = null;
																		$location_floor_label = null;

																	}

												// Suite

													$location_suite = get_field('location_suite', $location_post_id );

												// Construct Address 2+

													// Base array

														$location_addresses = array();

													// Add building name

														/**
														 * Add the value if the Facility taxonomy term's facility subtype has either not
														 * been set or if it has been set as 'Building'.
														 */

														if (
															!isset($building_type)
															||
															$building_type == 'building'
														) {

															$location_addresses[] = $building_name;

														}

													// Add parent location name

														$location_addresses[] = $location_parent_title;

													// Add location name

														$location_addresses[] = $location_title;

													// Add building floor

														$location_addresses[] = $location_floor_label;

													// Add suite

														$location_addresses[] = $location_suite;

													// Clean up array

														$location_addresses = array_filter($location_address_2_array);
														$location_addresses = array_values($location_address_2_array);

													// Define column values for Address 2 through Address 5

														$location_address_2 = array_key_exists(0, $location_addresses) ? $location_addresses[0] : '';
														$location_address_3 = array_key_exists(1, $location_addresses) ? $location_addresses[1] : '';
														$location_address_4 = array_key_exists(2, $location_addresses) ? $location_addresses[2] : '';
														$location_address_5 = array_key_exists(3, $location_addresses) ? $location_addresses[3] : '';

											// City, State and ZIP Code

												$location_city = get_field( 'location_city', $location_post_id );
												$location_state = get_field( 'location_state', $location_post_id );
												$location_zip = get_field( 'location_zip', $location_post_id );

											$location_phone = get_field( 'location_phone', $location_child_id );
											$location_fax = get_field( 'location_fax', $location_child_id );
											$location_hours_group = get_field('location_hours_group', $location_child_id );
											$location_telemed_query = $location_hours_group['location_telemed_query'];

											// Google My Business Specific Attributes

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

											// Map / GPS

												$location_map = get_field( 'location_map', $location_post_id );
												$location_latitude = '';
												$location_longitude = '';

												if ( $location_map ) {

													$location_latitude = $location_map['lat'];
													$location_longitude = $location_map['lng'];

												}

										echo '<td data-gmb-column="Address line 1" class="no-break">';
										echo $location_address_1 ? $location_address_1 : '';
										echo '</td>';

									// Address line 2
										echo '<td data-gmb-column="Address line 2" class="no-break">';
										echo ( $location_address_2 && !empty($location_address_2) ) ? $location_address_2 : '';
										echo '</td>';

									// Address line 3
										echo '<td data-gmb-column="Address line 3" class="no-break">';
										echo ( $location_address_3 && !empty($location_address_3) ) ? $location_address_3 : '';
										echo '</td>';

									// Address line 4
										echo '<td data-gmb-column="Address line 4" class="no-break">';
										echo ( $location_address_4 && !empty($location_address_4) ) ? $location_address_4 : '';
										echo '</td>';

									// Address line 5
										echo '<td data-gmb-column="Address line 5" class="no-break">';
										echo ( $location_address_5 && !empty($location_address_5) ) ? $location_address_5 : '';
										echo '</td>';

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

									// Latitude
										echo '<td data-gmb-column="Latitude" class="no-break">';
										echo $location_latitude ? $location_latitude : '';
										echo '</td>';

									// Longitude
										echo '<td data-gmb-column="Longitude" class="no-break">';
										echo $location_longitude ? $location_longitude : '';
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
										echo 'https://uamshealth.com/provider/' . $profile_slug . '/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=profile&amp;utm_specs=' . $store_code;
										echo '</td>';

									// Primary category
										echo '<td data-gmb-column="Primary category" class="no-break">';
										echo $provider_gmb_cat_primary_name;
										echo '</td>';

									// Additional categories
										echo '<td data-gmb-column="Additional categories" class="no-break">';
										echo $provider_gmb_cat_additional_names;
										echo '</td>';

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
										$excerpt = '';
										$bio = get_field('physician_clinical_bio',$post_id);
										$bio_short = get_field('physician_short_clinical_bio',$post_id);

										if (empty($excerpt)){
											if ($bio_short){
												$excerpt = mb_strimwidth(wp_strip_all_tags($bio_short), 0, 747, '...');
											} elseif ($bio) {
												$excerpt = mb_strimwidth(wp_strip_all_tags($bio), 0, 747, '...');
											} else {
												$fallback_desc = $medium_name . ' is ' . ($provider_occupation_title ? $provider_occupation_title_indef_article . ' ' . strtolower($provider_occupation_title) : 'a health care provider' ) . ($location_title ? ' at ' . $location_title : '') . ' employed by UAMS Health.';
												$excerpt = mb_strimwidth(wp_strip_all_tags($fallback_desc), 0, 747, '...');
											}
										}
										echo '<td data-gmb-column="From the business"><span style="display: block; width: 19.6875em"></span>';
										echo $excerpt;
										echo '</td>';

									// Opening date
									// Intentionally left blank
										echo '<td data-gmb-column="Opening date" class="no-break"></td>';

									// Logo photo
									// Intentionally left blank
										echo '<td data-gmb-column="Logo photo" class="no-break"></td>';

									// Cover photo
									// Intentionally left blank, for now
										echo '<td data-gmb-column="Cover photo" class="no-break">';
										//echo $featured_img_url;
										echo '</td>';

									// Other photos
									// Intentionally left blank
										echo '<td data-gmb-column="Other photos" class="no-break"></td>';

									// Labels
										$service_line = '';
										$service_line = get_field('physician_service_line',$post_id);
										$service_line_name = $service_line ? get_term( $service_line, 'service_line' )->name : '';

										echo '<td data-gmb-column="Labels" class="no-break">';
										echo $service_line_name;
										echo '</td>';

									// AdWords location extensions phone
									// Intentionally left blank
										echo '<td data-gmb-column="AdWords location extensions phone" class="no-break"></td>';

									// Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)
										echo '<td data-gmb-column="Accessibility: Wheelchair accessible elevator (has_wheelchair_accessible_elevator)" class="no-break">';
										if (!empty($location_gmb_wheelchair_elevator)) {
											echo $location_gmb_wheelchair_elevator;
										} else {
											echo 'Yes';
										}
										echo '</td>';

									// Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)
										echo '<td data-gmb-column="Accessibility: Wheelchair accessible entrance (has_wheelchair_accessible_entrance)" class="no-break">';
										if (!empty($location_gmb_wheelchair_entrance)) {
											echo $location_gmb_wheelchair_entrance;
										} else {
											echo 'Yes';
										}
										echo '</td>';

									// Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)
										echo '<td data-gmb-column="Accessibility: Wheelchair accessible restroom (has_wheelchair_accessible_restroom)" class="no-break">';
										if (!empty($location_gmb_wheelchair_restroom)) {
											echo $location_gmb_wheelchair_restroom;
										} else {
											echo 'Yes';
										}
										echo '</td>';

									// Amenities: Restroom (has_restroom)
										echo '<td data-gmb-column="Amenities: Restroom (has_restroom)" class="no-break">';
										if (!empty($location_gmb_restroom)) {
											echo $location_gmb_restroom;
										} else {
											echo 'Yes';
										}
										echo '</td>';

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

									// Health &amp; safety: Mask required (requires_masks_customers)
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

									// Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)
										echo '<td data-gmb-column="Health &amp; safety: Safety dividers at checkout (has_plexiglass_at_checkout)" class="no-break">[NOT APPLICABLE]</td>';

									// Health &amp; safety: Staff get temperature checks (requires_temperature_check_staff)
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

									// Health &amp; safety: Staff required to disinfect surfaces between visits (is_sanitizing_between_customers)
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

									// Health &amp; safety: Staff wear masks (requires_masks_staff)
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

									// Health &amp; safety: Temperature check required (requires_temperature_check_customers)
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
										echo 'https://uamshealth.com/coronavirus/?utm_source=google&amp;utm_medium=gmb&amp;utm_campaign=clinical&amp;utm_term=provider&amp;utm_content=covid-19-info-link&amp;utm_specs=' . $store_code;
										echo '</td>';

									// Place page URLs: Menu link (url_menu)
										echo '<td data-gmb-column="Place page URLs: Menu link (url_menu)" class="no-break">[NOT APPLICABLE]</td>';

									// Place page URLs: Virtual care link (url_facility_telemedicine_page)
										echo '<td data-gmb-column="Place page URLs: Virtual care link (url_facility_telemedicine_page)" class="no-break">';
										echo $location_telemed_query ? 'https://uamshealth.com/location/' . $location_slug . '/?utm_source=google&utm_medium=gmb&utm_campaign=clinical&utm_term=provider&utm_content=virtual-care-link&amp;utm_specs=' . $store_code . '#telemedicine-info' : '';
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