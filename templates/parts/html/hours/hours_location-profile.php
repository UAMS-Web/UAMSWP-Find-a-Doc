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

	// Get Hours Group

		$location_hours_group = $location_hours_group ?? get_field('location_hours_group');

	// Get In-Person Hours Values

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

					// Set default value for whether typical hours are active now or in the near future

						$location_hours_typical_active = true;

					// Is the location typically open 24/7? (bool)

						$location_hours_24_7_query = !$location_hours_variable_query ? $location_hours_group['location_24_7'] : null;

					// Typical Days and Hours for In-Person Operation (repeater)

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

				// Modified In-Person Hours

					// Are there any upcoming modified in-person hours of operation? (bool)

						$location_hours_modified_query = $location_hours_group['location_modified_hours'];

					if ( $location_hours_modified_query ) {

						// Get today's date as a Unix timestamp

							$today = strtotime("today");

						// Get a near-future date as a Unix timestamp

							$today_30 = strtotime("+30 days");

						// Reason for Modified In-Person Hours of Operation (string [WYSIWYG])

							$location_hours_modified_reason = $location_hours_group['location_modified_hours_reason'];

						// Start Date For the Modified In-Person Hours of Operation (string [F j, Y])

							$location_hours_modified_start_date = $location_hours_group['location_modified_hours_start_date'];

							// Convert value into a Unix timestamp

								$location_hours_modified_start_date_unix = strtotime($location_hours_modified_start_date);

						// Is there an end date for the modified in-person hours of operation? (bool)

							$location_hours_modified_end_query = $location_hours_group['location_modified_hours_end'];

						// End Date For the Modified In-Person Hours of Operation (string [F j, Y])

							$location_hours_modified_end_date = $location_hours_modified_end_query ? $location_hours_group['location_modified_hours_end_date'] : null;

							// Convert value into a Unix timestamp

								$location_hours_modified_end_date_unix = strtotime($location_hours_modified_end_date);

						// Check if time span for special hours is active now or in the near future

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

						// Check if time span for typical hours is inactive now or in the near future

							if (
								$location_hours_modified_start_date_unix
								&&
								$location_hours_modified_start_date_unix <= $today
							) {

								/**
								 * If start date for special hours is today or earlier
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

						// Individual Modified In-Person Hours of Operation (repeater)

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

// Display Variable Hours Information

	if ( $location_hours_variable_query ) {

		/**
		 * If the location's hours vary...
		 */

		?>
		<h2>Hours Vary</h2>
		<?php

		echo $location_hours_variable_info;

	}

// Display Static Hours Information

	// Set the timezone from the server

		date_default_timezone_set( wp_timezone_string() );

	// Base item array for adding to the hours list arrays

		$location_hours_list_item_array = array(
			'times' => array(
				'opens' => array(
					'unix' => null, // Unix timestamp
					'24_hour' => null, // 24:00 format ('H:i')
					'iso_8601' => null // ISO 8601 ('H:i:sP')
				),
				'closes' => array(
					'unix' => null, // Unix timestamp
					'24_hour' => null, // 24:00 format ('H:i')
					'iso_8601' => null // ISO 8601 ('H:i:sP')
				)
			),
			'time_span' => null, // Time span to display, formatted for AP Style
			'description' => null, // Description of the time span to display
			'valid' => array(
				'from' => null, // The date when the item becomes valid
				'to' => null // The date after when the item is not valid
			)
		);

	// Special Hours

		// Base typical hours list array

			$location_hours_modified_list = array();

		if ( $location_hours_modified_v2 ) {

			foreach ( $location_hours_modified_v2 as $item ) {

				// Base output array

					$item_output = array(
						'title' => null,
						'information' => null,
						'dates' => null
					);

				// Base individual time span output array

					$item_time_span_output = $location_hours_list_item_array;

				// Base item date list array

					/**
					 * Use this to collect all dates from this set of special hours of operation.
					 * Format each date as a Unix timestamp.
					 */

					$item_date_list = array();

				// Get the common values for this set of special hours of operation

					$item_title = $item['title'] ?? null; // Title / heading for this set of special hours // string (text)
					$item_information = $item['information'] ?? null; // Overview of this set of special in-person hours of operation // string (wysiwyg)
					$item_dates = $item['dates'] ?? null; // Dates of the special in-person hours of operation // repeater

				// Add the common values for this set of special hours of operation to the output array

					$item_output['title'] = $item_title;
					$item_output['information'] = $item_information;

				// Loop through the dates of the special in-person hours of operation repeater

					if ( $item_dates ) {

						foreach ( $item_dates as $item_date_row ) {

							// Base individual date output array

								$item_date_output = array(
									'date' => null,
									'closed_query' => null,
									'24_query' => null,
									'time_spans' => null
								);

							// Get the common values for this time span

								$item_date = $item_date_row['date'] ?? null; // Individual date for the special in-person hours of operation // string ('F j, Y')
								$item_date_closed_query = $item_date_row['closed_query'] ?? null; //  Will this location be closed on this date? // bool
								$item_date_24_query = $item_date_row['24_query'] ?? null; // Will this location be open 24 hours on this date? // bool
								$item_date_time_spans = $item_date_row['time_span'] ?? null; // Time span // repeater

							// Add the common values for this set of special hours of operation to the output array

								$item_date_output['date'] = $item_date;
								$item_date_output['closed_query'] = $item_date_closed_query;
								$item_date_output['24_query'] = $item_date_24_query;
								$item_date_output['time_spans'] = $item_date_time_spans;

							// Add the date to the item date list array as a Unix timestamp

								if ( $item_date ) {

									$item_date_list[] = strtotime($item_date);

								}

							// Loop through the time span repeater

								if ( $item_date_time_spans ) {

									foreach ( $item_date_time_spans as $item_date_time_span ) {

										$item_date_time_span_opens = $item_date_time_span['opens'] ?? null; // Opens // string ('g:i a')
										$item_date_time_span_closes = $item_date_time_span['closes'] ?? null; // Closes // string ('g:i a')
										$item_date_time_span_comment = $item_date_time_span['comment'] ?? null; // Comment // string (text)

									}

								}

						}

					}

				// Check if time span for this set of special hours of operation is active now or in the near future

					if (
						$item_date_list
						&&
						min($item_date_list) <= $today_30
						&&
						max($item_date_list) >= $today
					) {

						$location_hours_modified_active = true;

					}

				// Check if time span for typical hours is inactive now or in the near future

					/**
					 * Set the typical hours as inactive...
					 * if the start date for this set of special hours of operation is today or earlier...
					 * and if the end date is in the distant future
					 */

					if (
						$item_date_list
						&&
						min($item_date_list) <= $today
						&&
						max($item_date_list) >= $today_30
					) {

						$location_hours_typical_active = false;

					}

			}

		}

		$location_hours_modified_text = '';
		$location_hours_modified_active_start = '';
		$location_hours_modified_active_end = '';
		$item_day = ''; // Previous Day
		$item_comment = ''; // Comment on previous day
		$i = 1;

		if ( $location_hours_modified_active ) {

			// Check/define OpeningHoursSpecification Schema Data variables

				$schema_openingHoursSpecification = ( isset($schema_openingHoursSpecification) && is_array($schema_openingHoursSpecification) && !empty($schema_openingHoursSpecification) ) ? $schema_openingHoursSpecification : array(); // Main OpeningHoursSpecification schema array
				$schema_dayOfWeek = array(); // The day of the week for which these opening hours are valid.
				$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
				$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
				$schema_validFrom = $location_hours_modified_start_date; // The date when the item becomes valid.
				$schema_validThrough = $location_hours_modified_end_date; // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.

			$location_hours_modified_text .= $location_hours_modified_reason;
			$location_hours_modified_text .= '<p class="small font-italic">These special hours start on ' . $location_hours_modified_start_date . ', ';
			$location_hours_modified_text .= $location_hours_modified_end_query && $location_hours_modified_end_date ? 'and are scheduled to end after ' . $location_hours_modified_end_date . '.' : 'and will remain in effect until further notice.';
			$location_hours_modified_text .= '</p>';

			// Construct information from special hours repeater

				if ( $location_hours_modified ) {

					/**
					 * If the Special Hours repeater has at least one row...
					 */

					// Loop through the Special Hours repeater rows

						foreach ( $location_hours_modified as $item ) {

							// Get data from fields in the Special Hours repeater

								$item_title = $item['location_modified_hours_title']; // Title (in Special Hours repeater; in Special Hours tab) // string
								$item_info = $item['location_modified_hours_information']; // Information (in Special Hours repeater; in Special Hours tab) // string (wysiwyg)
								$item_times = $item['location_modified_hours_times']; // Hours (in Special Hours repeater; in Special Hours tab) // repeater
								$item_24_7_query = $item['location_modified_hours_24_7']; // Is this location available 24/7 during these special hours? (in Special Hours repeater; in Special Hours tab) // bool

							$location_hours_modified_text .= $item_title ? '<h3 class="h4">'. $item_title . '</h3>' : '';
							$location_hours_modified_text .= $item_info ? $item_info : '';

							// OpeningHoursSpecification Schema Data

								// Reset schema data variables

									$schema_dayOfWeek = array(); // The day of the week for which these opening hours are valid.
									$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
									$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

							// Get the earliest (most past) special hours start date from all the rows in the Modified hours repeater

								if (
									$location_hours_modified_active_start > strtotime($location_hours_modified_start_date) // If previous loop's special hours start date is greater than the current loop's special hours start date
									||
									'' == $location_hours_modified_active_start // Or if there is no special hours start date from a previous loop
								) {

									$location_hours_modified_active_start = strtotime($location_hours_modified_start_date); // Store the special hours start date for comparison in future loops

								} // endif ( $location_hours_modified_active_start > strtotime($location_hours_modified_start_date) || '' == $location_hours_modified_active_start )

							// Get the latest (most future) special hours end date from all the rows in the Modified hours repeater

								if (
									$location_hours_modified_active_end <= strtotime($location_hours_modified_end_date) // If previous loop's special hours end date is less than or equal to the current loop's special hours end date
									||
									'' == $location_hours_modified_active_start // Or if there is no special hours end date from a previous loop
									||
									!$location_hours_modified_end_query // Or if the current loop has no special hours end date
								) {

									if ( !$location_hours_modified_end_query ) {

										/**
										 * If the current loop has no special hours end date...
										 */

										$location_hours_modified_active_end = 'TBD';

									} else {

										/**
										 * Else if the current loop has a special hours end date...
										 */

										$location_hours_modified_active_end = strtotime($location_hours_modified_end_date);

									} // endif ( !$location_hours_modified_end_query ) else

								} // endif ( $location_hours_modified_active_end <= strtotime($location_hours_modified_end_date) || !$location_hours_modified_end_query )

								if ( $item_24_7_query ) {

									/**
									 * If the special hours are 24/7...
									 */

									$location_hours_modified_text .= '<strong>Open 24/7</strong>';

								// OpeningHoursSpecification Schema Data for Special Hours That Are 24/7

									$schema_dayOfWeek = array(
										'Monday',
										'Tuesday',
										'Wednesday',
										'Thursday',
										'Friday',
										'Saturday',
										'Sunday'
									); // The day of the week for which these opening hours are valid.
									$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
									$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

									// Add this location's details to the main OpeningHoursSpecification schema array

										// // Schema.org method: Add all days as an array under the dayOfWeek property
										//
										// 	/**
										// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
										// 	 */
										//
										// 	$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
										// 		$schema_dayOfWeek, // array|string // Optional // The day of the week for which these opening hours are valid.
										// 		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
										// 		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
										// 		$schema_validFrom, // string // Optional // The date when the item becomes valid.
										// 		$schema_validThrough, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
										// 		$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
										// 	);

										// Google method: Loop through all the days defined in the current Hours repeater row separately

											/**
											 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
											 */

											foreach ( $schema_dayOfWeek as $day ) {

												$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
													$day, // array|string // Optional // The day of the week for which these opening hours are valid.
													$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
													$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
													$schema_validFrom, // string // Optional // The date when the item becomes valid.
													$schema_validThrough, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
													$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
												);

											}

							} else {

								/**
								 * If the special hours are not 24/7...
								 */

								if (
									is_array($item_times)
									||
									is_object($item_times)
								) {

									$location_hours_modified_text .= '<dl class="hours">';

									// Loop through all the Hours repeater rows (in Special Hours repeater; in Special Hours tab)

										foreach ( $item_times as $item_time ) {

											$location_hours_modified_text .= $item_day !== $item_time['location_modified_hours_day'] ? '<dt>'. $item_time['location_modified_hours_day'] .'</dt> ' : '';
											$location_hours_modified_text .= '<dd>';

											// OpeningHoursSpecification Schema Data for Special Hours That Are Not 24/7

												// Reset/define variables

													$schema_dayOfWeek = array();

											if (
												'Mon - Fri' == $item_time['location_modified_hours_day']
												&&
												!$item_time['location_modified_hours_closed']
											) {

												// OpeningHoursSpecification Schema Data for Special Hours That Are Not 24/7

													$schema_dayOfWeek = array_merge(
														$schema_dayOfWeek,
														array(
															'Monday',
															'Tuesday',
															'Wednesday',
															'Thursday',
															'Friday'
														)
													); // The day of the week for which these opening hours are valid.

											} else {

												// OpeningHoursSpecification Schema Data for Special Hours That Are Not 24/7

													$schema_dayOfWeek[] = $item_time['location_modified_hours_day']; // The day of the week for which these opening hours are valid.

											} // endif ( 'Mon - Fri' == $item_time['location_modified_hours_day'] && !$item_time['location_modified_hours_closed'] ) else

											if ( $item_time['location_modified_hours_closed'] ) {

												// OpeningHoursSpecification Schema Data for Special Hours That Are Not 24/7

													$schema_opens = '00:00'; // string // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
													$schema_closes = '00:00'; // string // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

												$location_hours_modified_text .= 'Closed ';

											} else {

												$location_hours_modified_text .= ( ( $item_time['location_modified_hours_open'] && '00:00:00' != $item_time['location_modified_hours_open'] ) ? '' . ap_time_span( strtotime($item_time['location_modified_hours_open']), strtotime($item_time['location_modified_hours_close']) ). '' : '' );

												$schema_opens = $item_time['location_modified_hours_open']; // string // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
												$schema_closes = $item_time['location_modified_hours_close']; // string // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

											} // endif ( $item_time['location_modified_hours_closed'] ) else

											if ( $item_time['location_modified_hours_comment'] ) {

												$location_hours_modified_text .= ' <br /><span class="subtitle">' .$item_time['location_modified_hours_comment'] . '</span>';
												$item_comment = $item_time['location_modified_hours_comment'];

											} else {

												$item_comment = '';

											} // endif ( $item_time['location_modified_hours_comment'] ) else

											$location_hours_modified_text .= '</dd>';
											$item_day = $item_time['location_modified_hours_day']; // Reset the day

											// OpeningHoursSpecification Schema Data for Special Hours That Are Not 24/7

												// Add this location's details to the main OpeningHoursSpecification schema array

													// // Schema.org method: Add all days as an array under the dayOfWeek property
													//
													// 	/**
													// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
													// 	 */
													//
													// 	$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
													// 		$schema_dayOfWeek, // array|string // Optional // The day of the week for which these opening hours are valid.
													// 		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
													// 		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
													// 		$schema_validFrom, // string // Optional // The date when the item becomes valid.
													// 		$schema_validThrough, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
													// 		$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
													// 	);

													// Google method: Loop through all the days defined in the current Hours repeater row separately

														/**
														 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
														 */

														foreach ( $schema_dayOfWeek as $day) {

															$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
																$day, // array|string // Optional // The day of the week for which these opening hours are valid.
																$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
																$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
																$schema_validFrom, // string // Optional // The date when the item becomes valid.
																$schema_validThrough, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
																$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
															);

														}

											$i++;

										} // endforeach ( $item_times as $item_time )

									$location_hours_modified_text .= '</dl>';

								} // endif ( is_array($item_times) || is_object($item_times) )

							} // endif ( $item_24_7_query ) else

						} // endforeach ( $location_hours_modified as $item )

				} // endif ( $location_hours_modified )

			echo $location_hours_modified_text ? '<h2>Special Hours</h2>' . $location_hours_modified_text: '';

		}

	// Typical Hours

		if ( $location_hours_typical_active ) {

			// Base typical hours list array

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

					// Loop through all seven days of the week, adding values to the typical hours list array for each

						foreach ( array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ) as $item_day_row ) {

							// Base output array

								$item_output = $location_hours_list_item_array;

							// Get the date of the next instance of the day of the week (including today)

								$item_day_row_DateTime_class = $DateTime_yesterday_class; // Yesterday
								$item_day_row_DateTime_class->modify( 'next ' . $item_day_row ); // Next [day] from yesterday
								$item_day_row_DateTime_string = $item_day_row_DateTime_class->format('F j, Y');

								// Get the date of the day after that

									$item_day_row_DateTime_next_class = $item_day_row_DateTime_class;
									$item_day_row_DateTime_next_class->modify( 'tomorrow' ); // Tomorrow
									$item_day_row_DateTime_next_string = $item_day_row_DateTime_next_class->format('F j, Y');

							// Set the time values

								/**
								 * Set both the opening and closing times using the date of the next occurrence of
								 * the relevant day of the week (including today)
								 */

								// Opening time

									$item_day_row_time_opens_DateTime_string = $item_day_row_DateTime_string . ', midnight'; // string
									$item_day_row_time_opens_DateTime_unix = strtotime( $item_day_row_time_opens_DateTime_string ); // Unix timestamp

								// Closing time

									$item_day_row_time_closes_DateTime_string = $item_day_row_DateTime_string . ', 11:59:59 pm'; // string
									$item_day_row_time_closes_DateTime_unix = strtotime( $item_day_row_time_closes_DateTime_string ); // Unix timestamp

							// Set the time span text

								$item_day_row_time_span_text = 'Open 24 Hours';

							// Add the time values to the output array

								// Opening time

									$item_output['times']['opens']['unix'] = $item_day_row_time_opens_DateTime_unix;
									$item_output['times']['opens']['24_hour'] = date ('H:i', $item_day_row_time_opens_DateTime_unix );
									$item_output['times']['opens']['iso_8601'] = date ('H:i:sP', $item_day_row_time_opens_DateTime_unix );

								// Closing time

									$item_output['times']['closes']['unix'] = $item_day_row_time_closes_DateTime_unix;
									$item_output['times']['closes']['24_hour'] = date ('H:i', $item_day_row_time_closes_DateTime_unix );
									$item_output['times']['closes']['iso_8601'] = date ('H:i:sP', $item_day_row_time_closes_DateTime_unix );

							// Add the text value to the output array

								$item_output['time_span'] = $item_day_row_time_span_text;

							// Set the description value and add it to the output array

								if (
									isset($item['comment'])
									&&
									!empty($item['comment'])
								) {

									/**
									 * If a comment exists for this day or time span...
									 */

									$item_output['description'] = $item['comment'];

								}

							// Add the output array to the typical hours list array

								/**
								 * Add the output array as a row nested within the row for the day of the week
								 */

								$location_hours_typical_list[$item_day_row][] = $item_output;

						}

				}

			// The location is not typically available 24/7

				if ( !$location_hours_24_7_query ) {

					/**
					 * If the location is not typically available 24/7
					 */

					// Construct the the description terms and description details elements

						if ( $location_hours_repeater ) {

							/**
							 * If the Typical Hours repeater has at least one row...
							 */

							// Loop through the Typical Hours repeater, adding values to the typical hours list array

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

											// Get the date of the next instance of the day of the week (including today)

												$item_day_row_DateTime_class = $DateTime_yesterday_class; // Yesterday
												$item_day_row_DateTime_class->modify( 'next ' . $item_day_row ); // Next [day] from yesterday
												$item_day_row_DateTime_string = $item_day_row_DateTime_class->format('F j, Y');

												// Get the date of the day after that

													$item_day_row_DateTime_next_class = $item_day_row_DateTime_class;
													$item_day_row_DateTime_next_class->modify( 'tomorrow' ); // Tomorrow
													$item_day_row_DateTime_next_string = $item_day_row_DateTime_next_class->format('F j, Y');

											// Closed Status

												if ( $item['closed'] ) {

													/**
													 * If the location is closed on this day or time span...
													 */

													// Set the time values

														// Opening time

															$item_day_row_time_opens_DateTime_string = $item_day_row_DateTime_string . ', midnight';
															$item_day_row_time_opens_DateTime_unix = strtotime( $item_day_row_time_opens_DateTime_string );

														// Closing time

															$item_day_row_time_closes_DateTime_string = $item_day_row_time_opens_DateTime_string;
															$item_day_row_time_closes_DateTime_unix = $item_day_row_time_opens_DateTime_unix;

													// Set the time span text

														$item_day_row_time_span_text = 'Closed';

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

															$item_day_row_time_opens_DateTime_string = $item_day_row_DateTime_string . ',' . $item['open']; // string
															$item_day_row_time_opens_DateTime_unix = strtotime( $item_day_row_time_opens_DateTime_string ); // Unix timestamp

														// Closing time

															if ( strtotime($item['open']) >= strtotime($item['close']) ) {

																/**
																 * If the closing time is later than the opening time, then continue normally
																 */

																$item_day_row_time_closes_DateTime_string = $item_day_row_DateTime_string . ',' . $item['close']; // string

															} else {

																/**
																 * If the closing time is earlier than the opening time, then the time span must
																 * cross over into the next day, so use the date after that day instead
																 */

																$item_day_row_time_closes_DateTime_string = $item_day_row_DateTime_next_string . ',' . $item['close']; // string

															}

															$item_day_row_time_closes_DateTime_unix = strtotime( $item_day_row_time_closes_DateTime_string ); // Unix timestamp

													// Set the time span text

														$item_day_row_time_span_text = ap_time_span( strtotime($item['open']), strtotime($item['close']) );

												}

											// Add the time values to the output array

												// Opening time

													$item_output['times']['opens']['unix'] = $item_day_row_time_opens_DateTime_unix;
													$item_output['times']['opens']['24_hour'] = date ('H:i', $item_day_row_time_opens_DateTime_unix );
													$item_output['times']['opens']['iso_8601'] = date ('H:i:sP', $item_day_row_time_opens_DateTime_unix );

												// Closing time

													$item_output['times']['closes']['unix'] = $item_day_row_time_closes_DateTime_unix;
													$item_output['times']['closes']['24_hour'] = date ('H:i', $item_day_row_time_closes_DateTime_unix );
													$item_output['times']['closes']['iso_8601'] = date ('H:i:sP', $item_day_row_time_closes_DateTime_unix );

											// Add the text value to the output array

												$item_output['time_span'] = $item_day_row_time_span_text;

											// Set the description value and add it to the output array

												if (
													isset($item['comment'])
													&&
													!empty($item['comment'])
												) {

													/**
													 * If a comment exists for this day or time span...
													 */

													$item_output['description'] = $item['comment'];

												}

											// Add the output array to the typical hours list array

												/**
												 * Add the output array as a row nested within the row for the day of the week
												 */

												$location_hours_typical_list[$item_day_row][] = $item_output;

										}

								} // endforeach ( $location_hours_repeater as $item )

						}

				}

			// Construct the typical hours description list

				if ( $location_hours_typical_list ) {

					// Define the section heading

						$location_hours_typical_heading_text = $location_hours_modified_active ? 'Typical Hours' : 'Hours';
						$location_hours_typical_heading = '<h2>' . $location_hours_typical_heading_text . '</h2>';

					// Display the section heading

						echo $location_hours_typical_heading;

					// Display the time information

						// Open the description list element

							echo '<dl class="hours">';

						// Add a set of description term/details for each day row

							foreach ( $location_hours_typical_list as $day_name => $day_rows ) {

								if ( $day_name ) {

									// Construct the description term element

										echo '<dt>' . $day_name . '</dt>';

									// Construct the description details element(s)

										foreach ( $day_rows as $day_row ) {

											// Open the description details element

												echo '<dd>';

											// Add the time span

												echo $day_row['time_span'];

											// Add the time span description

												if ( $day_row['description'] ) {

													echo '<br /><span class="subtitle">' . $day_row['description'] . '</span>';

												}

											// Close the description details element

												echo '</dd>';

											// Add the the values to the schema data

												// openingHours

													$schema_openingHours = $schema_openingHours ?? array();

													$schema_openingHours = uamswp_fad_schema_openinghours(
														$day_name, // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
														$day_row['times']['opens']['iso_8601'], // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
														$day_row['times']['closes']['iso_8601'], // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
														$schema_openingHours // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
													);

												// openingHoursSpecification

													$schema_openingHoursSpecification = $schema_openingHoursSpecification ?? array();

													$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
														$day_name, // array|string // Optional // The day of the week for which these opening hours are valid.
														$day_row['times']['opens']['iso_8601'], // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
														$day_row['times']['closes']['iso_8601'], // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using the ISO 8601 time format (hh:mm:ss[Z|(+|-)hh:mm]).
														'', // string // Optional // The date when the item becomes valid.
														'', // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
														$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
													);

										}

								}

							}

						// Close the description list element

							echo '</dl>';

				}

		}

// Display After Hours Information

	if ( $location_after_hours ) {

		?>
		<h2>After Hours</h2>
		<?php

		echo $location_after_hours;

	} // endif ( $location_after_hours )