<?php
/**
 * Template Name: Location Hours of Operation
 *
 * Description: A template part that displays a the hours of operation for a
 * location on its profile
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	__
 */

// Get Values

	// Get Hours of Operation Group

		$location_hours_group = $location_hours_group ?? get_field('location_hours_group');

	// Get In-Person Hours of Operation Values

		// Variable Hours

			// Do the location's typical in-person hours of operation vary? (bool)

				$location_hours_variable_query = $location_hours_group['location_hours_variable'];

			// Information about the Location's Variable Hours of Operation (string [WYSIWYG])

				$location_hours_variable_info = $location_hours_variable_query ? $location_hours_group['location_hours_variable_info'] : null;

		// Static Hours

			$location_hours_24_7_query = null;
			$location_hours_repeater = null;
			$location_hours_typical_active = null;
			$location_hours_modified_query = null;
			$location_hours_modified_reason = null;
			$location_hours_modified_start_date = null;
			$location_hours_modified_start_date_unix = null;
			$location_hours_modified_end_query = null;
			$location_hours_modified_end_date = null;
			$location_hours_modified_end_date_unix = null;
			$location_hours_modified = null;
			$location_hours_modified_v2 = null;
			$location_hours_modified_active = null;

			if ( !$location_hours_variable_query ) {

				// Typical In-Person Hours

					// Set default value for whether typical hours of operation are active now or in the near future

						$location_hours_typical_active = true;

					// Is the location typically open 24/7? (bool)

						$location_hours_24_7_query = !$location_hours_variable_query ? $location_hours_group['location_24_7'] : null;

					// Typical Days and Hours of Operation for In-Person Operation (repeater)

						$location_hours_repeater = ( !$location_hours_variable_query && !$location_hours_24_7_query ) ? $location_hours_group['location_hours'] : null;

					// Check repeater for values

						if ( !$location_hours_24_7_query ) {

							if ( !$location_hours_repeater ) {

								$location_hours_typical_active = false;

							}

							// Check repeater for row

								if (
									!isset($location_hours_repeater[0])
									||
									empty($location_hours_repeater[0])
								) {

									$location_hours_typical_active = false;

								}

							// Check repeater for day value

								if (
									!$location_hours_repeater
									||
									!isset($location_hours_repeater[0]['day'])
									||
									empty($location_hours_repeater[0]['day'])
								) {

									$location_hours_typical_active = false;

								}

						}

				// Special In-Person Hours

					// Are there any upcoming modified in-person hours of operation? (bool)

						$location_hours_modified_query = $location_hours_group['location_modified_hours'];

					if ( $location_hours_modified_query ) {

						// Get common date values

							// Get today's date as a Unix timestamp

								$today = strtotime("today");

							// Get a near-future date as a Unix timestamp

								$today_30 = strtotime("+30 days");

							// Get the current year

								$current_year = date( 'Y', $today );

						// Reason for Special In-Person Hours of Operation (string [WYSIWYG])

							$location_hours_modified_reason = $location_hours_group['location_modified_hours_reason'];

						// Start Date For the Special In-Person Hours of Operation (string [F j, Y])

							$location_hours_modified_start_date = $location_hours_group['location_modified_hours_start_date'];

							// Convert value into a Unix timestamp

								$location_hours_modified_start_date_unix = strtotime($location_hours_modified_start_date);

						// Is there an end date for the modified in-person hours of operation? (bool)

							$location_hours_modified_end_query = $location_hours_group['location_modified_hours_end'];

						// End Date For the Special In-Person Hours of Operation (string [F j, Y])

							$location_hours_modified_end_date = $location_hours_modified_end_query ? $location_hours_group['location_modified_hours_end_date'] : null;

							// Convert value into a Unix timestamp

								$location_hours_modified_end_date_unix = strtotime($location_hours_modified_end_date);

						// Check if time span for special hours of operation is active now or in the near future

							if (
								$location_hours_modified_start_date_unix
								&&
								$location_hours_modified_start_date_unix <= $today_30
							) {

								/**
								 * If start date is in the past or is in the near future
								 */

								if ( !$location_hours_modified_end_query ) {

									/**
									 * If there is no end date
									 */

									$location_hours_modified_active = true;

								} elseif (
									$location_hours_modified_end_date_unix
									&&
									$location_hours_modified_end_date_unix >= $today
								) {

									/**
									 * If the end date is not in the past
									 */

									$location_hours_modified_active = true;

								}

							}

						// Check if time span for typical hours of operation is inactive now or in the near future

							if (
								$location_hours_modified_start_date_unix
								&&
								$location_hours_modified_start_date_unix <= $today
							) {

								/**
								 * If start date for special hours of operation is today or earlier
								 */

								if ( !$location_hours_modified_end_query ) {

									/**
									 * If there is no end date
									 */

									$location_hours_typical_active = false;

								} elseif (
									$location_hours_modified_end_date_unix
									&&
									$location_hours_modified_end_date_unix >= $today_30
								) {

									/**
									 * If the end date is in the distant future
									 */

									$location_hours_typical_active = false;

								}

							}

						// Individual Special In-Person Hours of Operation (repeater)

							$location_hours_modified = $location_hours_modified_active ? $location_hours_group['location_modified_hours_group'] : null;
							$location_hours_modified_v2 = $location_hours_modified_active ? $location_hours_group['location_modified_hours_group_v2'] : null;

					}

			}

	// Get After Hours Information Values

		$location_after_hours = null;

		if ( !$location_hours_24_7_query ) {

			// Get value from the location profile

				$location_after_hours = $location_hours_group['location_after_hours'] ?? null;

			// Get fallback value from the system

				if ( !$location_after_hours ) {

					$location_after_hours = get_field('location_afterhours_descr_system', 'option') ?? null;

				}

			// Set fallback value with static string

				if ( !$location_after_hours ) {

					$location_after_hours = '<p>If you are in need of urgent or emergency care, call 911 or go to your nearest emergency department at your local hospital.</p>';

				}

		}

// Display Variable Hours of Operation Information

	if ( $location_hours_variable_query ) {

		/**
		 * If the location's hours of operation vary...
		 */

		?>
		<h2>Hours Vary</h2>
		<?php

		echo $location_hours_variable_info;

	}

// Display Static Hours of Operation Information

	// Set the timezone from the server

		date_default_timezone_set( wp_timezone_string() );

	// Base / standard values

		// Base item array for adding to the hours of operation list arrays

			$location_hours_list_item_array = array(
				'times' => array(
					'opens' => array(
						'acf' => null, // Value from ACF field ('g:i a')
						'unix' => null, // Unix timestamp
						'24_hour' => null, // 24:00 format ('H:i')
						'iso_8601' => null // ISO 8601 format for time only ('H:i:sP')
					),
					'closes' => array(
						'acf' => null, // Value from ACF field ('g:i a')
						'unix' => null, // Unix timestamp
						'24_hour' => null, // 24:00 format ('H:i')
						'iso_8601' => null // ISO 8601 format for time only ('H:i:sP')
					)
				),
				'time_span' => null, // Time span to display, formatted for AP Style
				'description' => null, // Description of the time span to display
				'valid' => array(
					'from' => array(
						'acf' => null, // Value from ACF field ('F j, Y')
						'unix' => null, // Unix timestamp
						'iso_8601' => null // ISO 8601 format for date only ('Y-m-d')
					), // The date when the item becomes valid
					'through' => array(
						'acf' => null, // Value from ACF field ('F j, Y')
						'unix' => null, // Unix timestamp
						'iso_8601' => null // ISO 8601 format for date only ('Y-m-d')
					) // The date after when the item is not valid
				)
			);

		// Base string values

				/**
				 * Format the value using AP Style.
				 */

			// Section headings

				$location_hours_text_heading_common = 'Hours';
				$location_hours_text_heading_special = 'Special Hours';
				$location_hours_text_heading_typical = 'Regular Hours';

			// Time span text for display

				$location_hours_text_24_hours = 'Open 24 hours';
				$location_hours_text_closed = 'Closed';

	// Special Hours

		// Get the values

			// Base special hours of operation list array

				$location_hours_modified_list = array();

			// Loop through the Individual Special In-Person Hours of Operation repeater

				if ( $location_hours_modified_v2 ) {

					foreach ( $location_hours_modified_v2 as $item ) {

						if ( $item ) {

							// Define/reset the base special hours of operation set output array

								$item_output = array(
									'title' => null, // string
									'information' => null, // string
									'date_range_text' => null, // string
									'dates' => null // array populated by individual date output arrays
								);

							// Define/reset the base item date list array

								/**
								 * Use this to collect all dates from this set of special hours of operation.
								 * Format each date as a Unix timestamp.
								 */

								$item_date_list = array();

							// Get the common values for this set of special hours of operation

								$item_output['title'] = $item['title'] ?? null; // Title / heading for this set of special hours of operation // string (text)
								$item_output['information'] = $item['information'] ?? null; // Overview of this set of special in-person hours of operation // string (wysiwyg)

							// Loop through the dates of the special in-person hours of operation repeater

								/**
								 * Each row in the special in-person hours of operation repeater is a specific
								 * date. If the values were entered correctly, the dates for each row should be an
								 * uninterrupted series of dates (e.g., January 1, January 2, January 3).
								 */

								if ( $item['dates'] ) {

									foreach ( $item['dates'] as $item_date_row ) {

										/**
										 * Expected structure of $item_date_row:
										 *
										 *     $item_date_row = array(
										 *         'date' => 'January 1, 1970', // Individual Date For the Special In-Person Hours of Operation // string ('F j, Y')
										 *         'closed_query' => 0, // Status // bool
										 *         '24_query' => 0, // Will this location be open 24 hours on this date? // bool
										 *         'time_span' => array(
										 *             array(
										 *                 'opens' => '8:00 am', // Opens time // string ('g:i a')
										 *                 'closes' => '5:00 pm', // Closes time // string ('g:i a')
										 *                 'comment' => '', // Comment // string
										 *             ),
										 *             array( ... )
										 *         )
										 *     );
										 */

										// Define/reset the individual date output array

											$item_date_output = array(
												'day' => null, // A full textual representation of the day of the week ('l')
												'date' => array(
													'acf' => null, // Value from ACF field ('F j, Y')
													'unix' => null, // Unix timestamp
													'iso_8601' => null, // ISO 8601 format for date only ('Y-m-d')
													'long' => null // Long-form date value (e.g., 'Thursday, January 1, 1970') ('l, F j, Y')
												),
												'date_after' => array(
													'acf' => null, // Value from ACF field ('F j, Y')
													'unix' => null, // Unix timestamp
													'iso_8601' => null, // ISO 8601 format for date only ('Y-m-d')
													'long' => null // Long-form date value (e.g., 'Thursday, January 1, 1970') ('l, F j, Y')
												),
												'closed_query' => null, // bool
												'24_query' => null, // bool
												'time_spans' => null // array populated by individual time span output arrays
											);

										// Get the relevant dates

											/**
											 * $item_date_output['date'] and $item_date_output['date_after']
											 */

											// Individual date for the special in-person hours of operation

												/**
												 * $item_date_output['date']
												 */

												// Date value from ACF field // string ('F j, Y')

													$item_date_output['date']['acf'] = $item_date_row['date'] ?? null;

												// Convert the Date value to other formats

													// Get the Unix timestamp

														$item_date_output['date']['unix'] = strtotime( $item_date_output['date']['acf'] . ', midnight' ) ?? null;

													// Convert the Unix timestamp to other formats

														// ISO 8601 format for date only ('Y-m-d')

															$item_date_output['date']['iso_8601'] = date( 'Y-m-d', $item_date_output['date']['unix'] ) ?? null;

														// Long-form date value (e.g., 'Thursday, January 1, 1970') ('l, F j, Y')

															$item_date_output['date']['long'] = date( 'l, F j, Y', $item_date_output['date']['unix'] ) ?? null;

														// Long-form date value, formatted for AP Style

															$item_date_output['date']['long_ap_style'] = ap_date(
																$item_date_output['date']['unix'], // int // Required // The date as a Unix timestamp
																false, // bool // Optional // Query for whether or not to output 'today' if the date is today
																false, // bool // Optional // Query for whether or not to capitalize 'today'
																false, // bool // Optional // Query for whether or not to include the year when the date is within the current year
																true, // bool // Optional // Query for whether or not to include weekday names
																false, // bool // Optional // Query for whether or not to include a trailing comma
																true, // bool // Optional // Query for whether or not to include non-breaking spaces within the date
															);

											// Day after the individual date for the special in-person hours of operation

												/**
												 * $item_date_output['date_after']
												 */

												// Get the Unix timestamp

													$item_date_output['date_after']['unix'] = strtotime( $item_date_output['date']['acf'] . ' + 1 day, midnight' ) ?? null;

												// Convert the Unix timestamp to other formats

													// Date value as if from ACF field // string ('F j, Y')

														$item_date_output['date_after']['acf'] = date( 'F j, Y', $item_date_output['date_after']['unix'] ) ?? null;

													// ISO 8601 format for date only ('Y-m-d')

														$item_date_output['date_after']['iso_8601'] = date( 'Y-m-d', $item_date_output['date_after']['unix'] ) ?? null;

										// 'date' // Convert the Date value's Unix timestamp to a full textual representation of the day of the week ('l')

											$item_date_output['day'] = date( 'l', $item_date_output['date']['unix'] ) ?? null;

										// Will this location be closed on this date?

											/**
											 * $item_date_output['closed_query']
											 */

											if (
												!isset($item_date_output['closed_query'])
												||
												$item_date_output['closed_query'] == false
											) {

												$item_date_output['closed_query'] = ( $item_date_row['closed_query'] ? 1 : 0 ) ?? 0;

											}

										// Will this location be open 24 hours on this date?

											/**
											 * $item_date_output['24_query']
											 */

											if (
												!isset($item_date_output['24_query'])
												||
												$item_date_output['24_query'] == false
											) {

												$item_date_output['24_query'] = ( $item_date_row['24_query'] ? 1 : 0 ) ?? 0;

											}

										// Get the valid date(s)

											/**
											 * Store them in a variable to be added to the output array later.
											 */

											$item_date_valid = $location_hours_list_item_array['valid'];

											// Valid From

												/**
												 * Mirror the date values from the individual date for the special in-person hours of operation
												 */

												$item_date_valid['from'] = $item_date_output['date'];

											// Valid Through

												/**
												 * Mirror the date values from Valid From array
												 */

												$item_date_valid['through'] = $item_date_valid['from'];

										// Add the date to the item date list array as a Unix timestamp

											if ( $item_date_output['date']['unix'] ) {

												$item_date_list[] = $item_date_output['date']['unix'];

											}

										// Set the time spans

											// Closed Status

												if ( $item_date_output['closed_query'] ) {

													/**
													 * If the location is closed on this date...
													 */

													// Get the values

														// Define/reset the base individual time span output array

															$item_time_span_output = $location_hours_list_item_array;

														// 'times' // Set the time values

															/**
															 * Set the opening and closing times both as midnight (12:00:00 a.m.) on the set date
															 */

															// 'opens' // Opening time

																/**
																 * Set the opening time as midnight (12:00:00 a.m.) on the set date
																 */

																// 'unix' // Get the Unix timestamp for the relevant date and time

																	$item_time_span_output['times']['opens']['unix'] = $item_date_output['date']['unix'] ?? null; // Get the Unix timestamp 12:00:00 a.m. on the set date // string

																// 'acf' // Convert the Unix timestamp value to the ACF field value format ('g:i a')

																	$item_time_span_output['times']['opens']['acf'] = date( 'g:i a', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																// '24_hour' // Convert the Unix timestamp value to the 24-hour format ('H:i')

																	$item_time_span_output['times']['opens']['24_hour'] = date( 'H:i', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																// 'iso_8601' // Convert the Unix timestamp value to the ISO 8601 format for time only ('H:i:sP')

																	$item_time_span_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_time_span_output['times']['opens']['unix'] ) ?? null;

															// 'closes' // Closing time

																/**
																 * Mirror the time values from the opening time array
																 */

																$item_time_span_output['times']['closes'] = $item_time_span_output['times']['opens'];

														// 'time_span' // Set/construct the time span text for display

															/**
															 * Format the value using AP Style.
															 */

															$item_time_span_output['time_span'] = $location_hours_text_closed;

														// 'description' // Description of the time span to display

															$item_time_span_output['description'] = '';

														// 'valid' // The dates these hours are valid

															$item_time_span_output['valid'] = $item_date_valid;

													// Clean up the individual time span output array

														/**
														 * Empty the individual time span output array if certain key values are not set
														 */

															if (
															!isset($item_time_span_output['times']['opens']['unix']) // If there is no opens time value
															||
															!isset($item_time_span_output['times']['closes']['unix']) // If there is no closes time value
															||
															!isset($item_time_span_output['time_span']) // If there is time span text
															||
															!isset($item_time_span_output['valid']['from']['unix']) // If there is no valid from date value
															||
															!isset($item_time_span_output['valid']['through']['unix']) // If there is no valid through date value
														) {

															$item_time_span_output = null;

														}

													// Add the values to the output array

														if ( $item_time_span_output ) {

															$item_date_output['time_spans'][] = $item_time_span_output;

														}

												}

											// Open Status

												if ( !$item_date_output['closed_query'] ) {

													/**
													 * If the location is open on this date...
													 */

													// Open 24 hours

														if ( $item_date_output['24_query'] ) {

															/**
															 * If the location is open 24 hours on this date or time span...
															 */

															// Get the values

																// Define/reset the base individual time span output array

																	$item_time_span_output = $location_hours_list_item_array;

																// 'times' // Set the time values

																	// 'opens' // Opening time

																		/**
																		 * Set the opening time as midnight (12:00:00 a.m.) on the set date
																		 */

																		// 'unix' // Get the Unix timestamp for the relevant date and time

																			$item_time_span_output['times']['opens']['unix'] = $item_date_output['date']['unix'] ?? null;

																		// 'acf' // Convert the Unix timestamp to the ACF field value format ('g:i a')

																			$item_time_span_output['times']['opens']['acf'] = date( 'g:i a', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																		// '24_hour' // Convert the Unix timestamp to the 24-hour format ('H:i')

																			$item_time_span_output['times']['opens']['24_hour'] = date( 'H:i', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																		// 'iso_8601' // Convert the Unix timestamp to the ISO 8601 format for time only ('H:i:sP')

																			$item_time_span_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																	// 'closes' // Closing time

																		/**
																		 * Set the closing time as 11:59:59 p.m. on the set date
																		 */

																		// 'unix' // Get the Unix timestamp for the relevant date and time

																			$item_time_span_output['times']['closes']['unix'] = strtotime( $item_date_output['date']['acf'] . ', 11:59:59 pm' ) ?? null; // Get the Unix timestamp for 11:59:59 p.m. on the set date

																		// 'acf' // Convert the Unix timestamp to the ACF field value format ('g:i a')

																			$item_time_span_output['times']['closes']['acf'] = date( 'g:i a', $item_time_span_output['times']['closes']['unix'] ) ?? null; // Unix timestamp converted to match ACF field value format ('g:i a')

																		// '24_hour' // Convert the Unix timestamp to the 24-hour format ('H:i')

																			$item_time_span_output['times']['closes']['24_hour'] = date( 'H:i', $item_time_span_output['times']['closes']['unix'] ) ?? null; // Unix timestamp converted to match 24-hour format ('H:i')

																		// 'iso_8601' // Convert the Unix timestamp to the ISO 8601 format for time only ('H:i:sP')

																			$item_time_span_output['times']['closes']['iso_8601'] = date( 'H:i:sP', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																// 'time_span' // Set/construct the time span text for display

																	/**
																	 * Format the value using AP Style.
																	 */

																	$item_time_span_output['time_span'] = $location_hours_text_24_hours;

																// 'description' // Set the description value for the time span

																	$item_time_span_output['description'] = '';

																// 'valid' // The dates these hours are valid

																	$item_time_span_output['valid'] = $item_date_valid;

															// Clean up the individual time span output array

																/**
																 * Empty the individual time span output array if certain key values are not set
																 */

																	if (
																	!isset($item_time_span_output['times']['opens']['unix']) // If there is no opens time value
																	||
																	!isset($item_time_span_output['times']['closes']['unix']) // If there is no closes time value
																	||
																	!isset($item_time_span_output['time_span']) // If there is time span text
																	||
																	!isset($item_time_span_output['valid']['from']['unix']) // If there is no valid from date value
																	||
																	!isset($item_time_span_output['valid']['through']['unix']) // If there is no valid through date value
																) {

																	$item_time_span_output = null;

																}

															// Add the values to the output array

																if ( $item_time_span_output ) {

																	$item_date_output['time_spans'][] = $item_time_span_output;

																}

														}

													// Not open 24 hours

														if ( !$item_date_output['24_query'] ) {

															// Loop through the time span repeater

																if ( $item_date_row['time_span'] ) {

																	foreach ( $item_date_row['time_span'] as $item_date_time_span ) {

																		if ( $item_date_time_span ) {

																			// Get the values

																				// Define/reset the base individual time span output array

																					$item_time_span_output = $location_hours_list_item_array;

																				// 'times' // Set the time values

																					// 'opens' // Opening time

																						// 'acf' // Get the ACF DateTime value ('g:i a')

																							$item_time_span_output['times']['opens']['acf'] = $item_date_time_span['opens'] ?? null;

																						// 'unix' // Use the ACF value to get the Unix timestamp for the relevant time on the set date

																							$item_time_span_output['times']['opens']['unix'] = strtotime( $item_date_output['date']['acf'] . ', ' . $item_time_span_output['times']['opens']['acf'] ) ?? null;

																						// '24_hour' // Convert the Unix timestamp value to the 24-hour format ('H:i')

																								$item_time_span_output['times']['opens']['24_hour'] = date( 'H:i', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																						// 'iso_8601' // Convert the Unix timestamp value to the ISO 8601 format for time only ('H:i:sP')

																							$item_time_span_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_time_span_output['times']['opens']['unix'] ) ?? null;

																					// 'closes' // Closing time

																						// 'acf' // Get the ACF DateTime value ('g:i a')

																							$item_time_span_output['times']['closes']['acf'] = $item_date_time_span['closes'] ?? null; // string

																						// 'unix' // Use the ACF value to get the Unix timestamp for the relevant time on the set date

																							/**
																							 * Check if the closing time crosses into the next day
																							 */

																							if ( strtotime($item_time_span_output['times']['opens']['acf']) >= strtotime($item_time_span_output['times']['closes']['acf']) ) {

																								/**
																								 * If the closing time is later than the opening time, then continue normally
																								 */

																								$item_time_span_output['times']['closes']['unix'] = strtotime( $item_date_output['date']['acf'] . ', ' . $item_time_span_output['times']['closes']['acf'] ) ?? null;

																							} else {

																								/**
																								 * If the closing time is earlier than the opening time, then the time span must
																								 * cross over into the next day, so use the date after that day instead
																								 */

																								$item_time_span_output['times']['closes']['unix'] = strtotime( $item_date_output['date_after']['acf'] . ', ' . $item_time_span_output['times']['closes']['acf'] ) ?? null;

																							}

																						// '24_hour' // Convert the Unix timestamp value to the 24-hour format ('H:i')

																							$item_time_span_output['times']['closes']['24_hour'] = date( 'H:i', $item_time_span_output['times']['closes']['unix'] ) ?? null;

																						// 'iso_8601' // Convert the Unix timestamp value to the ISO 8601 format for time only ('H:i:sP')

																							$item_time_span_output['times']['closes']['iso_8601'] = date( 'H:i:sP', $item_time_span_output['times']['closes']['unix'] ) ?? null;

																				// 'time_span' // Set/construct the time span text for display

																					/**
																					 * Format the value using AP Style.
																					 */

																					$item_time_span_output['time_span'] = ap_time_span(
																						$item_time_span_output['times']['opens']['unix'],
																						$item_time_span_output['times']['closes']['unix']
																					) ?? null;

																				// 'description' // Set the description value for the time span

																					$item_time_span_output['description'] = ( isset($item_date_time_span['comment']) && !empty($item_date_time_span['comment']) ) ? $item_date_time_span['comment'] : null;

																				// 'valid' // The dates these hours are valid

																					$item_time_span_output['valid'] = $item_date_valid;

																			// Clean up the individual time span output array

																				/**
																				 * Empty the individual time span output array if certain key values are not set
																				 */

																				if (
																					!isset($item_time_span_output['times']['opens']['unix']) // If there is no opens time value
																					||
																					!isset($item_time_span_output['times']['closes']['unix']) // If there is no closes time value
																					||
																					!isset($item_time_span_output['time_span']) // If there is time span text
																					||
																					!isset($item_time_span_output['valid']['from']['unix']) // If there is no valid from date value
																					||
																					!isset($item_time_span_output['valid']['through']['unix']) // If there is no valid through date value
																				) {

																					$item_time_span_output = null;

																				}

																			// Add the values to the output array

																				if ( $item_time_span_output ) {

																					$item_date_output['time_spans'][] = $item_time_span_output;

																				}

																		} // endif ( !$item_date_output['closed_query'] )

																	} // endforeach ( $item_date_row['time_span'] as $item_date_time_span )

																} // endif ( $item_date_row['time_span'] )

														}

												} // endif ( !$item_date_output['closed_query'] )

										// Clean up the individual date output array

											/**
											 * Empty the individual date output array if certain key values are not set
											 */

											if (
												!isset($item_date_output['date']['unix']) // If there is no date value
												||
												!isset($item_date_output['time_spans']) // If there is no time spans value
											) {

												$item_date_output = null;

											}

										// Add the individual date output array to the individual special hours of operation set output array

											if ( $item_date_output ) {

												$item_output['dates'][$item_date_output['date']['long_ap_style']] = $item_date_output;

											}

									} // endforeach ( $item['dates'] as $item_date_row )

								} // endif ( $item['dates'] )

							// Set the date range text

								$item_output['date_range_text'] = 'These special hours start on ';
								$item_output['date_range_text'] .= ap_date(
									min($item_date_list), // int // Required // The date as a Unix timestamp
									false, // bool // Optional // Query for whether or not to output 'today' if the date is today
									false, // bool // Optional // Query for whether or not to capitalize 'today'
									false, // bool // Optional // Query for whether or not to include the year when the date is within the current year
									true, // bool // Optional // Query for whether or not to include weekday names
									true, // bool // Optional // Query for whether or not to include a trailing comma
									true // bool // Optional // Query for whether or not to include non-breaking spaces within the date
								);
								$item_output['date_range_text'] .= ' and are scheduled to end after ';
								$item_output['date_range_text'] .= ap_date(
									max($item_date_list), // int // Required // The date as a Unix timestamp
									false, // bool // Optional // Query for whether or not to output 'today' if the date is today
									false, // bool // Optional // Query for whether or not to capitalize 'today'
									false, // bool // Optional // Query for whether or not to include the year when the date is within the current year
									true, // bool // Optional // Query for whether or not to include weekday names
									false, // bool // Optional // Query for whether or not to include a trailing comma
									true // bool // Optional // Query for whether or not to include non-breaking spaces within the date
								);
								$item_output['date_range_text'] .= '.';

							// Clean up the individual special hours of operation set output array

								/**
								 * Empty certain arrays if certain key values are not set.
								 */

								if (
									!isset($item_output['title']) // If there is no title value
									// ||
									// !isset($item_output['information']) // If there is no information value
									||
									!isset($item_output['dates']) // If there is dates value
								) {

									// Empty the individual special hours of operation set output array

										$item_output = null;

									// Empty the special hours of operation set date list array

										$item_date_list = null;

								}

							// Check if this set of special hours of operation should be active

								/**
								 * Take action if the following criteria are true:
								 *
								 *     * If the start date for this set of special hours of operation is before today, is today, or is within the next 30 days
								 *     * If the end date for this set of special hours of operation is today or is after today
								 */

								if (
									$item_date_list
									&&
									min($item_date_list) <= $today_30
									&&
									max($item_date_list) >= $today
								) {

									// Add the individual special hours of operation set output array to the special hours of operation list array

										if ( $item_output ) {

											$location_hours_modified_list[] = $item_output;

										}

									// Set modified hours of operation as active

										$location_hours_modified_active = true;

								}

							// Check if typical hours of operation should be inactive

								/**
								 * Take action if the following criteria are true:
								 *
								 *     * If the start date for this set of special hours of operation is today or earlier
								 *     * If the end date for this set of special hours of operation beyond the next 30 days
								 */

								if (
									$item_date_list
									&&
									min($item_date_list) <= $today
									&&
									max($item_date_list) >= $today_30
								) {

									// Set typical hours of operation as inactive

										$location_hours_typical_active = false;

								}

						}

					}

				}

	// Typical Hours

		if ( $location_hours_typical_active ) {

			// Get the values

				// Base typical hours of operation list array

					$location_hours_typical_list = array();

				// Get yesterday's date as DateTime class

					/**
					 * This will be used in the upcoming foreach loop when defining the timestamp
					 */

					$DateTime_yesterday_class = new DateTime();
					$DateTime_yesterday_class->modify( 'yesterday' );

				// The location is typically available 24/7

					if ( $location_hours_24_7_query ) {

						/**
						 * If the location is typically available 24/7
						 */

						// Loop through all seven days of the week, adding values to the typical hours of operation list array for each

							foreach ( array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ) as $item_day_row ) {

								// Base output array

									$item_output = $location_hours_list_item_array;

								// Get the common values for this day to the output array

									// Get the relevant dates

										if (
											!isset($location_hours_typical_list[$item_day_row]['date']['unix'])
											||
											!isset($location_hours_typical_list[$item_day_row]['date_after']['unix'])
										) {

											// Get the date of the next instance of the day of the week (including today)

												$item_day_row_DateTime_class = $DateTime_yesterday_class; // Yesterday
												$item_day_row_DateTime_class->modify( 'next ' . $item_day_row ); // Next [day] from yesterday
												$item_day_row_DateTime_string = $item_day_row_DateTime_class->format('F j, Y');

												// Add that date value to the typical hours of operation list array

													// Value as if from ACF field ('F j, Y')

														$location_hours_typical_list[$item_day_row]['date']['acf'] = $item_day_row_DateTime_string;

													// Get the Unix timestamp

														$location_hours_typical_list[$item_day_row]['date']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date']['acf'] . ', midnight' ) ?? null;

													// Convert the Unix timestamp to ISO 8601 format for date only ('Y-m-d')

														$location_hours_typical_list[$item_day_row]['date']['iso_8601'] = date( 'Y-m-d', $location_hours_typical_list[$item_day_row]['date']['unix'] ) ?? null;

													// Convert the Unix timestamp to a long-form date value (e.g., 'Thursday, January 1, 1970') ('l, F j, Y')

														$location_hours_typical_list[$item_day_row]['date']['long'] = date( 'l, F j, Y', $location_hours_typical_list[$item_day_row]['date']['unix'] ) ?? null;

											// Get the date of the day after that

												$item_day_row_DateTime_next_class = $item_day_row_DateTime_class;
												$item_day_row_DateTime_next_class->modify( 'tomorrow' ); // Tomorrow
												$item_day_row_DateTime_next_string = $item_day_row_DateTime_next_class->format('F j, Y');

												// Add that date value to the typical hours of operation list array

													// Value as if from ACF field ('F j, Y')

														$location_hours_typical_list[$item_day_row]['date_after']['acf'] = $item_day_row_DateTime_next_string;

													// Get the Unix timestamp

														$location_hours_typical_list[$item_day_row]['date_after']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date_after']['acf'] . ', midnight' ) ?? null;

													// Convert the Unix timestamp to ISO 8601 format for date only ('Y-m-d')

														$location_hours_typical_list[$item_day_row]['date_after']['iso_8601'] = date( 'Y-m-d', $location_hours_typical_list[$item_day_row]['date_after']['unix'] ) ?? null;

										}

									// Will this location be closed on this date? // bool

										if (
											!isset($location_hours_typical_list[$item_day_row]['closed_query'])
											||
											$location_hours_typical_list[$item_day_row]['closed_query'] == false
										) {

											$location_hours_typical_list[$item_day_row]['closed_query'] = 0;

										}

									// Will this location be open 24 hours on this date? // bool

										if (
											!isset($location_hours_typical_list[$item_day_row]['24_query'])
											||
											$location_hours_typical_list[$item_day_row]['24_query'] == false
										) {

												$location_hours_typical_list[$item_day_row]['24_query'] = 1;

											}

								// Set the time values

									/**
									 * Set both the opening and closing times using the date of the next occurrence of
									 * the relevant day of the week (including today)
									 */

									// Opening time

										/**
										 * Set the opening time as midnight (12:00:00 a.m.) on the next instance of the set day of the week (including today)
										 */

										// Get the Unix timestamp for the relevant date and time

											$item_output['times']['opens']['unix'] = $location_hours_typical_list[$item_day_row]['date']['unix'];

										// Convert the Unix timestamp value to other formats

											// ACF field value format ('g:i a')

												$item_output['times']['opens']['acf'] = date( 'g:i a', $item_output['times']['opens']['unix'] ) ?? null;

											// 24-hour format ('H:i')

												$item_output['times']['opens']['24_hour'] = date( 'H:i', $item_output['times']['opens']['unix'] ) ?? null;

											// ISO 8601 format for time only ('H:i:sP')

												$item_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_output['times']['opens']['unix'] ) ?? null;

									// Closing time

										/**
										 * Set the closing time as midnight (12:00:00 a.m.) on the next instance of the set day of the week (including today)
										 */

										// Get the Unix timestamp for the relevant date and time

											$item_output['times']['closes']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date']['acf'] . ', 11:59:59 pm' ) ?? null;

										// Convert the Unix timestamp value to other formats

											// ACF field value format ('g:i a')

												$item_output['times']['closes']['acf'] = date( 'g:i a', $item_output['times']['closes']['unix'] ) ?? null;

											// 24-hour format ('H:i')

												$item_output['times']['closes']['24_hour'] = date( 'H:i', $item_output['times']['closes']['unix'] ) ?? null;

											// ISO 8601 format for time only ('H:i:sP')

												$item_output['times']['closes']['iso_8601'] = date( 'H:i:sP', $item_output['times']['closes']['unix'] ) ?? null;

								// Set/construct the time span text for display

									/**
									 * Format the value using AP Style.
									 */

									$item_output['time_span'] = $location_hours_text_24_hours;

								// Set the description value for the time span

									$item_output['description'] = ( isset($item['comment']) && !empty($item['comment']) ) ? $item['comment'] : null;

								// Clean up the individual special hours of operation set output array

									/**
									 * Empty the output array if certain key values are not set.
									 */

									if (
										!isset($item_output['times']['opens']['unix']) // If there is no opens time value
										||
										!isset($item_output['times']['closes']['unix']) // If there is no closes time value
										||
										!isset($item_output['time_span']) // If there is no time span value
									) {

										$item_output = null;

									} // endif

								// Add the output array to the typical hours of operation list array

									if ( $item_output ) {

										/**
										 * Add the output array as a row nested within the row for the day of the week
										 */

										$location_hours_typical_list[$item_day_row]['time_spans'][] = $item_output;

									} // endif ( $item_output )

							} // endforeach

					}

				// The location is not typically available 24/7

					if ( !$location_hours_24_7_query ) {

						/**
						 * If the location is not typically available 24/7
						 */

						// Construct the the description terms and description details elements

							if ( $location_hours_repeater ) {

								/**
								 * If the Typical Hours of Operation repeater has at least one row...
								 */

								// Loop through the Typical Hours of Operation repeater, adding values to the typical hours of operation list array

									foreach ( $location_hours_repeater as $item ) {

										// Base output array

											$item_output = $location_hours_list_item_array;

										// Define an array to list the day value(s)

											if ( 'Mon - Fri' == $item['day'] ) {

												$item_day = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' );

											} else {

												$item_day = array( $item['day'] );

											}

										// Loop through the day value array

											foreach ( $item_day as $item_day_row ) {

												// Get the relevant dates

													if (
														!isset($location_hours_typical_list[$item_day_row]['date']['unix'])
														||
														!isset($location_hours_typical_list[$item_day_row]['date_after']['unix'])
													) {

														// Get the date of the next instance of the day of the week (including today)

															$item_day_row_DateTime_class = $DateTime_yesterday_class; // Yesterday
															$item_day_row_DateTime_class->modify( 'next ' . $item_day_row ); // Next [day] from yesterday
															$item_day_row_DateTime_string = $item_day_row_DateTime_class->format('F j, Y');

															// Add that date value to the typical hours of operation list array

																// Value as if from ACF field ('F j, Y')

																	$location_hours_typical_list[$item_day_row]['date']['acf'] = $item_day_row_DateTime_string;

																// Get the Unix timestamp

																	$location_hours_typical_list[$item_day_row]['date']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date']['acf'] . ', midnight' ) ?? null;

																// Convert the Unix timestamp to ISO 8601 format for date only ('Y-m-d')

																	$location_hours_typical_list[$item_day_row]['date']['iso_8601'] = date( 'Y-m-d', $location_hours_typical_list[$item_day_row]['date']['unix'] ) ?? null;

																// Convert the Unix timestamp to a long-form date value (e.g., 'Thursday, January 1, 1970') ('l, F j, Y')

																	$location_hours_typical_list[$item_day_row]['date']['long'] = date( 'l, F j, Y', $location_hours_typical_list[$item_day_row]['date']['unix'] ) ?? null;

														// Get the date of the day after that

															$item_day_row_DateTime_next_class = $item_day_row_DateTime_class;
															$item_day_row_DateTime_next_class->modify( 'tomorrow' ); // Tomorrow
															$item_day_row_DateTime_next_string = $item_day_row_DateTime_next_class->format('F j, Y');

															// Add that date value to the typical hours of operation list array

																// Value as if from ACF field ('F j, Y')

																	$location_hours_typical_list[$item_day_row]['date_after']['acf'] = $item_day_row_DateTime_next_string;

																// Get the Unix timestamp

																	$location_hours_typical_list[$item_day_row]['date_after']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date_after']['acf'] . ', midnight' ) ?? null;

																// Convert the Unix timestamp to ISO 8601 format for date only ('Y-m-d')

																	$location_hours_typical_list[$item_day_row]['date_after']['iso_8601'] = date( 'Y-m-d', $location_hours_typical_list[$item_day_row]['date_after']['unix'] ) ?? null;

													}

												// Closed Status

													if ( $item['closed'] ) {

														/**
														 * If the location is closed on this day or time span...
														 */

														// Set the time values

															// Opening time

																/**
																 * Set the opening time as midnight (12:00:00 a.m.) on the next instance of the set day of the week (including today)
																 */

																// Get the Unix timestamp for the relevant date and time

																	$item_output['times']['opens']['unix'] = $location_hours_typical_list[$item_day_row]['date']['unix'] ?? null;

																// Convert the Unix timestamp value to other formats

																	// ACF field value format ('g:i a')

																		$item_output['times']['opens']['acf'] = date( 'g:i a', $item_output['times']['opens']['unix'] ) ?? null;

																	// 24-hour format ('H:i')

																		$item_output['times']['opens']['24_hour'] = date( 'H:i', $item_output['times']['opens']['unix'] ) ?? null;

																	// ISO 8601 format for time only ('H:i:sP')

																		$item_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_output['times']['opens']['unix'] ) ?? null;

															// Closing time

																/**
																 * Mirror the time values from the opening time array
																 */

																$item_output['times']['closes'] = $item_output['times']['opens'];

														// Set/construct the time span text for display

															/**
															 * Format the value using AP Style.
															 */

															// $item_day_row_time_span_text = $location_hours_text_closed;
															$item_output['time_span'] = $location_hours_text_closed;

													}

												// Open Status

													if ( !$item['closed'] ) {

														/**
														 * If the location is not closed on this day or time span...
														 */

														// Set the time values

															/**
															 * Set both the opening and closing times using the date of the next occurrence of
															 * the relevant day of the week (including today)
															 */

															// Opening time

																/**
																 * Set the opening time using the date of the next instance of the set day of the week (including today)
																 */

																// Get the Unix timestamp for the relevant date and time

																	$item_output['times']['opens']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date']['acf'] . ', ' . $item['open'] ) ?? null;

																// Convert the Unix timestamp value to other formats

																	// ACF field value format ('g:i a')

																		$item_output['times']['opens']['acf'] = date( 'g:i a', $item_output['times']['opens']['unix'] ) ?? null;

																	// 24-hour format ('H:i')

																		$item_output['times']['opens']['24_hour'] = date( 'H:i', $item_output['times']['opens']['unix'] ) ?? null;

																	// ISO 8601 format for time only ('H:i:sP')

																		$item_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_output['times']['opens']['unix'] ) ?? null;

															// Closing time

																/**
																 * If the closing time is later than the opening time, then set the closing time using the date of the next instance of the set day of the week (including today).
																 *
																 * However, if the closing time is earlier than the opening time (meaning the time span must cross over into the next day), then set the closing time using the date after the next instance of the set day of the week.
																 */

																// Get the Unix timestamp for the relevant date and time

																	if ( strtotime($item['open']) >= strtotime($item['close']) ) {

																		/**
																		 * If the closing time is later than the opening time, then continue normally
																		 */

																		$item_output['times']['closes']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date']['acf'] . ', ' . $item['close'] ) ?? null;

																	} else {

																		/**
																		 * If the closing time is earlier than the opening time (meaning the time span must cross over into the next day), then set the closing time using the date after the next instance of the set day of the week
																		 */

																		$item_output['times']['closes']['unix'] = strtotime( $location_hours_typical_list[$item_day_row]['date_after']['acf'] . ', ' . $item['close'] ) ?? null;

																	}

																	// $item_day_row_time_closes_DateTime_unix = strtotime( $item_day_row_time_closes_DateTime_string ); // Unix timestamp

																// Convert the Unix timestamp value to other formats

																	// ACF field value format ('g:i a')

																		$item_output['times']['closes']['acf'] = date( 'g:i a', $item_output['times']['closes']['unix'] ) ?? null;

																	// 24-hour format ('H:i')

																		$item_output['times']['closes']['24_hour'] = date( 'H:i', $item_output['times']['closes']['unix'] ) ?? null;

																	// ISO 8601 format for time only ('H:i:sP')

																		$item_output['times']['closes']['iso_8601'] = date( 'H:i:sP', $item_output['times']['closes']['unix'] ) ?? null;

														// Set/construct the time span text for display

															/**
															 * Format the value using AP Style.
															 */

															$item_output['time_span'] = ap_time_span(
																strtotime($item['open']),
																strtotime($item['close'])
															) ?? null;

													}

												// Convert the Unix timestamp values to other formats

													// Opening time

														// ACF field value format ('g:i a')

															$item_output['times']['opens']['acf'] = date( 'g:i a', $item_output['times']['opens']['unix'] ) ?? null;

														// 24-hour format ('H:i')

															$item_output['times']['opens']['24_hour'] = date( 'H:i', $item_output['times']['opens']['unix'] ) ?? null;

														// ISO 8601 format for time only ('H:i:sP')

															$item_output['times']['opens']['iso_8601'] = date( 'H:i:sP', $item_output['times']['opens']['unix'] ) ?? null;

													// Closing time

														// ACF field value format ('g:i a')

															$item_output['times']['closes']['acf'] = date( 'g:i a', $item_output['times']['closes']['unix'] ) ?? null;

														// 24-hour format ('H:i')

															$item_output['times']['closes']['24_hour'] = date( 'H:i', $item_output['times']['closes']['unix'] ) ?? null;

														// ISO 8601 format for time only ('H:i:sP')

															$item_output['times']['closes']['iso_8601'] = date( 'H:i:sP', $item_output['times']['closes']['unix'] ) ?? null;

												// Set the description value for the time span

													$item_output['description'] = ( isset($item['comment']) && !empty($item['comment']) ) ? $item['comment'] : null;

												// Clean up the individual special hours of operation set output array

													/**
													 * Empty the output array if certain key values are not set.
													 */

													if (
														!isset($item_output['times']['opens']['unix']) // If there is no opens time value
														||
														!isset($item_output['times']['closes']['unix']) // If there is no closes time value
														||
														!isset($item_output['time_span']) // If there is no time span value
													) {

														$item_output = null;

													}

												// Add the output array to the typical hours of operation list array

													if ( $item_output ) {

														/**
														 * Add the output array as a row nested within the row for the day of the week
														 */

														$location_hours_typical_list[$item_day_row]['time_spans'][] = $item_output;

													}

											}

									} // endforeach ( $location_hours_repeater as $item )

							}

					}

		}

	// Display the static hours of operation

		if (
			(
				$location_hours_modified_active
				&&
				$location_hours_modified_list
			)
			||
			(
				$location_hours_typical_active
				&&
				$location_hours_typical_list
			)
		) {

			// Check/define schema value arrays

				$schema_openingHours = $schema_openingHours ?? array();
				$schema_openingHoursSpecification = $schema_openingHoursSpecification ?? array();
				$schema_specialOpeningHoursSpecification = $schema_specialOpeningHoursSpecification ?? array();

			// Common section heading

				echo '<h2>' . $location_hours_text_heading_common . '</h2>';

			// Modified Hours

				if ( $location_hours_modified_active ) {

					// Display special hours of operation section heading if typical hours of operation are also active

						if ( $location_hours_typical_active ) {

							echo '<h3 class="sr-only">' .  $location_hours_text_heading_special . '</h3>';

						}

					// Loop through the sets of special hours of operation

						foreach ( $location_hours_modified_list as $item ) {

							// Display the section heading for the set of special hours of operation

								if ( $location_hours_modified_active ) {

									echo '<h4 class="h4">' .  $item['title'] . '</h4>';

								}

							// Display the information paragraph for the set of special hours of operation

								if ( $location_hours_modified_active ) {

									echo '<p>' .  $item['information'] . '</p>';

								}

							// Display the small date range explanation paragraph for the set of special hours of operation

								if ( $location_hours_modified_active ) {

									echo '<p class="small">' . $item['date_range_text'] . '</p>';

								}

							// Display the time information and construct the schema data

								uamswp_hours_description_list(
									$item['dates'], // array // Required // Associative array of the hours of operation for each day in a series
									$schema_openingHours, // array // Required // Pre-existing list array for openingHours to which to add additional items
									$schema_openingHoursSpecification, // array // Required // Pre-existing list array for openingHoursSpecification to which to add additional items
									$schema_specialOpeningHoursSpecification, // array // Required // Pre-existing list array for specialOpeningHoursSpecification to which to add additional items
									$location_schema_fields[$page_id] //array // Required // Pre-existing field values array so duplicate calls can be avoided
								);

						}

				}

			// Typical Hours

				if ( $location_hours_typical_active ) {

					// Display typical hours of operation section heading if special hours of operation are also active

						if ( $location_hours_modified_active ) {

							echo '<h3 class="h4">' .  $location_hours_text_heading_typical . '</h3>';

						}

					// Display the time information and construct the schema data

						uamswp_hours_description_list(
							$location_hours_typical_list, // array // Required // Associative array of the hours of operation for each day in a series
							$schema_openingHours, // array // Required // Pre-existing list array for openingHours to which to add additional items
							$schema_openingHoursSpecification, // array // Required // Pre-existing list array for openingHoursSpecification to which to add additional items
							$schema_specialOpeningHoursSpecification, // array // Required // Pre-existing list array for specialOpeningHoursSpecification to which to add additional items
							$location_schema_fields[$page_id] //array // Required // Pre-existing field values array so duplicate calls can be avoided
						);

				}

		}

// Display After Hours Information

	if ( $location_after_hours ) {

		?>
		<h2>After Hours</h2>
		<?php

		echo $location_after_hours;

	} // endif ( $location_after_hours )