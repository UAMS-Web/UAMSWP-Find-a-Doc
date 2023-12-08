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

						// Check if time span for modified hours is active now or in the near future

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
								 * If start date for modified hours is today or earlier
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

	$location_hours_modified_text = '';
	$location_hours_modified_active_start = '';
	$location_hours_modified_active_end = '';

	if ( !$location_hours_variable_query ) {

		/**
		 * If the location's hours do not vary...
		 */

		// Begin Modified Hours Logic

			if ( $location_hours_modified_query ) {

				/**
				 * If there are upcoming modified hours...
				 */

				$item_day = ''; // Previous Day
				$item_comment = ''; // Comment on previous day
				$i = 1;

				$today = strtotime("today");
				$today_30 = strtotime("+30 days");

				// OpeningHoursSpecification Schema Data

					// Check/define schema data variables

						$schema_openingHoursSpecification = ( isset($schema_openingHoursSpecification) && is_array($schema_openingHoursSpecification) && !empty($schema_openingHoursSpecification) ) ? $schema_openingHoursSpecification : array(); // Main OpeningHoursSpecification schema array
						$schema_dayOfWeek = array(); // The day of the week for which these opening hours are valid.
						$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
						$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
						$schema_validFrom = $location_hours_modified_start_date; // The date when the item becomes valid.
						$schema_validThrough = ( $location_hours_modified_end_query && $location_hours_modified_end_date) ? $location_hours_modified_end_date : null; // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.

				// Display the modified hours if they have started or if they start within 30 days

					if (
						strtotime($location_hours_modified_start_date) <= $today_30 // If the modified hours start date is less or equal to 30 days in the future...
						&&
						(
							strtotime($location_hours_modified_end_date) >= $today // If the modified hours end date is greater than or equal to today
							||
							!$location_hours_modified_end_query // If there is no end date for the modified hours
						)
					) {

						$location_hours_modified_text .= $location_hours_modified_reason;
						$location_hours_modified_text .= '<p class="small font-italic">These modified hours start on ' . $location_hours_modified_start_date . ', ';
						$location_hours_modified_text .= $location_hours_modified_end_query && $location_hours_modified_end_date ? 'and are scheduled to end after ' . $location_hours_modified_end_date . '.' : 'and will remain in effect until further notice.';
						$location_hours_modified_text .= '</p>';

						if ( $location_hours_modified ) {

							/**
							 * If the Modified Hours repeater has at least one row...
							 */

							// Loop through the Modified Hours repeater rows

								foreach ( $location_hours_modified as $item ) {

									// Get data from fields in the Modified Hours repeater

										$item_title = $item['location_modified_hours_title']; // Title (in Modified Hours repeater; in Modified Hours tab) // string
										$item_info = $item['location_modified_hours_information']; // Information (in Modified Hours repeater; in Modified Hours tab) // string (wysiwyg)
										$item_times = $item['location_modified_hours_times']; // Hours (in Modified Hours repeater; in Modified Hours tab) // repeater
										$item_24_7_query = $item['location_modified_hours_24_7']; // Is this location available 24/7 during these modified hours? (in Modified Hours repeater; in Modified Hours tab) // bool

									$location_hours_modified_text .= $item_title ? '<h3 class="h4">'. $item_title . '</h3>' : '';
									$location_hours_modified_text .= $item_info ? $item_info : '';

									// OpeningHoursSpecification Schema Data

										// Reset schema data variables

											$schema_dayOfWeek = array(); // The day of the week for which these opening hours are valid.
											$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
											$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

									// Get the earliest (most past) modified hours start date from all the rows in the Modified hours repeater

										if (
											$location_hours_modified_active_start > strtotime($location_hours_modified_start_date) // If previous loop's modified hours start date is greater than the current loop's modified hours start date
											||
											'' == $location_hours_modified_active_start // Or if there is no modified hours start date from a previous loop
										) {

											$location_hours_modified_active_start = strtotime($location_hours_modified_start_date); // Store the modified hours start date for comparison in future loops

										} // endif ( $location_hours_modified_active_start > strtotime($location_hours_modified_start_date) || '' == $location_hours_modified_active_start )

									// Get the latest (most future) modified hours end date from all the rows in the Modified hours repeater

										if (
											$location_hours_modified_active_end <= strtotime($location_hours_modified_end_date) // If previous loop's modified hours end date is less than or equal to the current loop's modified hours end date
											||
											'' == $location_hours_modified_active_start // Or if there is no modified hours end date from a previous loop
											||
											!$location_hours_modified_end_query // Or if the current loop has no modified hours end date
										) {

											if ( !$location_hours_modified_end_query ) {

												/**
												 * If the current loop has no modified hours end date...
												 */

												$location_hours_modified_active_end = 'TBD';

											} else {

												/**
												 * Else if the current loop has a modified hours end date...
												 */

												$location_hours_modified_active_end = strtotime($location_hours_modified_end_date);

											} // endif ( !$location_hours_modified_end_query ) else

										} // endif ( $location_hours_modified_active_end <= strtotime($location_hours_modified_end_date) || !$location_hours_modified_end_query )

										if ( $item_24_7_query ) {

											/**
											 * If the modified hours are 24/7...
											 */

											$location_hours_modified_text .= '<strong>Open 24/7</strong>';

										// OpeningHoursSpecification Schema Data for Modified Hours That Are 24/7

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
												// 		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
												// 		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
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
															$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
															$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
															$schema_validFrom, // string // Optional // The date when the item becomes valid.
															$schema_validThrough, // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
															$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
														);

													}

									} else {

										/**
										 * If the modified hours are not 24/7...
										 */

										if (
											is_array($item_times)
											||
											is_object($item_times)
										) {

											$location_hours_modified_text .= '<dl class="hours">';

											// Loop through all the Hours repeater rows (in Modified Hours repeater; in Modified Hours tab)

												foreach ( $item_times as $item_time ) {

													$location_hours_modified_text .= $item_day !== $item_time['location_modified_hours_day'] ? '<dt>'. $item_time['location_modified_hours_day'] .'</dt> ' : '';
													$location_hours_modified_text .= '<dd>';

													// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

														// Reset/define variables

															$schema_dayOfWeek = array();

													if (
														'Mon - Fri' == $item_time['location_modified_hours_day']
														&&
														!$item_time['location_modified_hours_closed']
													) {

														// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

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

														// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

															$schema_dayOfWeek[] = $item_time['location_modified_hours_day']; // The day of the week for which these opening hours are valid.

													} // endif ( 'Mon - Fri' == $item_time['location_modified_hours_day'] && !$item_time['location_modified_hours_closed'] ) else

													if ( $item_time['location_modified_hours_closed'] ) {

														// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

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

													// OpeningHoursSpecification Schema Data for Modified Hours That Are Not 24/7

														// Add this location's details to the main OpeningHoursSpecification schema array

															// // Schema.org method: Add all days as an array under the dayOfWeek property
															//
															// 	/**
															// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
															// 	 */
															//
															// 	$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
															// 		$schema_dayOfWeek, // array|string // Optional // The day of the week for which these opening hours are valid.
															// 		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
															// 		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
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
																		$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
																		$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
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

					} // endif ( strtotime($location_hours_modified_start_date) <= $today_30 && ( strtotime($location_hours_modified_end_date) >= $today || !$location_hours_modified_end_query ) )

					echo $location_hours_modified_text ? '<h2>Modified Hours</h2>' . $location_hours_modified_text: '';

			} // endif ( $location_hours_modified_query )

		// Begin Typical Hours Logic

			if (
				(
					$location_hours_modified_active_start != '' // If there is a modified hours start date
					&&
					$location_hours_modified_active_start <= $today // And if that modified hours start date is today or earlier
				)
				&&
				(
					$location_hours_modified_active_end > $today_30 // If the modified hours end date is after 30 days in the future
					||
					$location_hours_modified_active_end == 'TBD' // Or if there is no modified hours end date
				)
			) {

				/**
				 * If the modified hours are the current hours for at least the next 30 days...
				 *
				 * Do not display the typical hours
				 */

			} else {

				/**
				 * If the modified hours end within 30 days or if they haven't started yet...
				 *
				 * Display the typical hours
				 */

				// Schema Data

					// // Schema.org method: openingHours Schema Data
					//
					// 	/**
					// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
					// 	 */
					//
					// 	// Check/define/reset schema data variables
					//
					// 		$schema_openingHours = ( isset($schema_openingHours) && is_array($schema_openingHours) && !empty($schema_openingHours) ) ? $schema_openingHours : array(); // Main openingHours schema array
					// 		$schema_dayOfWeek = ''; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
					// 		$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
					// 		$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

					// Google method: OpeningHoursSpecification Schema Data

						/**
						 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
						 */

						// Check/define/reset schema data variables

							$schema_openingHoursSpecification = ( isset($schema_openingHoursSpecification) && is_array($schema_openingHoursSpecification) && !empty($schema_openingHoursSpecification) ) ? $schema_openingHoursSpecification : array(); // Main OpeningHoursSpecification schema array
							$schema_dayOfWeek = ''; // The day of the week for which these opening hours are valid. // Days are specified using their full name (e.g., Sunday)
							$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
							$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

				if (
					$location_hours_24_7_query // The location is typically available 24/7
					||
					$location_hours_repeater[0]['day'] // Typical daily hours have been set
				) {

					/**
					 * If the location is typically available 24/7
					 * or if typical daily hours have been set...
					 */

					?>
					<h2><?php echo $location_hours_modified_text ? 'Typical ' : ''; ?>Hours</h2>
					<?php

					if ( $location_hours_24_7_query ) {

						/**
						 * If the location is typically available 24/7...
						 */

						echo '<strong>Open 24/7</strong>';

						// Schema Data

							// // Schema.org method: openingHours Schema Data
							//
							// 	/**
							// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
							// 	 */
							//
							// 	// Define schema data variables
							//
							// 		$schema_dayOfWeek = 'Mo-Su'; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
							// 		$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
							// 		$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
							//
							// 	// Add this location's details to the main openingHours schema array
							//
							// 		$schema_openingHours = uamswp_fad_schema_openinghours(
							// 			$schema_dayOfWeek, // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
							// 			$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
							// 			$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
							// 			$schema_openingHours // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
							// 		);

							// Google method: OpeningHoursSpecification Schema Data

								/**
								 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
								 */

								// Define schema data variables

									$schema_dayOfWeek = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ); // The day of the week for which these opening hours are valid.
									$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
									$schema_closes = '23:59'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

								// Loop through all the days in the array separately

									foreach ( $schema_dayOfWeek as $day) {

										$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
											$day, // array|string // Optional // The day of the week for which these opening hours are valid.
											$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
											$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
											'', // string // Optional // The date when the item becomes valid.
											'', // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
											$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
										);

									}

					} else {

						/**
						 * If typical daily hours have been set...
						 */

						echo '<dl class="hours">';

						if ( $location_hours_repeater ) {

							/**
							 * If the Typical Hours repeater has at least one row...
							 */

							$hours_text = ''; // Definition term and definition description tag set
							$day = ''; // Previous Day
							$comment = ''; // Comment on previous day
							$i = 1;

							// Loop through the Typical Hours repeater

								foreach ( $location_hours_repeater as $hour ) {

									/**
									 * openingHours Schema Data for Typical Hours That Are 24/7
									 */

									// Schema Data

										// // Schema.org method: openingHours Schema Data
										//
										// 	/**
										// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
										// 	 */
										//
										// 	// Define/reset schema data variables
										//
										// 		$schema_dayOfWeek = ''; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
										// 		$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
										// 		$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

										// Google method: OpeningHoursSpecification Schema Data

											/**
											 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
											 */

											// Define/reset schema data variables

											$schema_dayOfWeek = array(); // The day of the week for which these opening hours are valid.
											$schema_opens = ''; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
											$schema_closes = ''; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

									if ( $day !== $hour['day'] ) {

										/**
										 * If the current repeater row's day does not match the previous repeater row's day...
										 */

										// Definition term and definition description tag set

											/**
											 * Write a new definition term element with the current repeater row's day
											 */

											$hours_text .= '<dt>'. $hour['day'] .'</dt> ';

									}

									// Definition term and definition description tag set

										/**
										 * Open a definition description tag
										 */

										$hours_text .= '<dd>';

									// Schema Data

										if (
											'Mon - Fri' == $hour['day'] // The current repeater row's day is set as 'Mon - Fri'
											&&
											$hour['closed'] // And the current repeater row is marked as closed
										) {

											// // Schema.org method: openingHours Schema Data
											//
											// 	/**
											// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
											// 	 *
											// 	 * Do nothing
											// 	 */

											// Google method: OpeningHoursSpecification Schema Data

												/**
												 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
												 */

												// Define schema data variables

													$schema_dayOfWeek = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ); // The day of the week for which these opening hours are valid.
													$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
													$schema_closes = '00:00'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

										} elseif (
											'Mon - Fri' == $hour['day'] // The current repeater row's day is set as 'Mon - Fri'
											// And the current repeater row is not marked as closed
										) {

											// // Schema.org method: openingHours Schema Data
											//
											// 	/**
											// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
											// 	 */
											//
											// 	// Define schema data variables
											//
											// 		$schema_dayOfWeek = 'Mo-Fr'; // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)

											// Google method: OpeningHoursSpecification Schema Data

												/**
												 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
												 */

												// Define schema data variables

													$schema_dayOfWeek = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ); // The day of the week for which these opening hours are valid.
													$schema_opens = $hour['open']; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
													$schema_closes = $hour['close']; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

										} elseif (
											// The current repeater row's day is not set as 'Mon - Fri'
											$hour['closed'] // And the current repeater row is marked as closed
										) {

											// // Schema.org method: openingHours Schema Data
											//
											// 	/**
											// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
											// 	 *
											// 	 * Do nothing
											// 	 */

											// Google method: OpeningHoursSpecification Schema Data

												/**
												 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
												 */

												// Define schema data variables

													$schema_dayOfWeek[] = $hour['day']; // The day of the week for which these opening hours are valid.
													$schema_opens = '00:00'; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
													$schema_closes = '00:00'; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

										} else {

											/**
											 * If the current repeater row's day is not set as 'Mon - Fri'
											 *  And the current repeater row is not marked as closed
											 */

											// // Schema.org method: openingHours Schema Data
											//
											// 	/**
											// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
											// 	 */
											//
											// 	// Define schema data variables
											//
											// 		$schema_dayOfWeek = substr( $hour['day'], 0, 2 ); // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
											// 		$schema_opens = $hour['open']; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
											// 		$schema_closes = $hour['close']; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

											// Google method: OpeningHoursSpecification Schema Data

												/**
												 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
												 */

												// Define schema data variables

													$schema_dayOfWeek[] = $hour['day']; // The day of the week for which these opening hours are valid.
													$schema_opens = $hour['open']; // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
													$schema_closes = $hour['close']; // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.

										}

										// // Schema.org method: openingHours Schema Data
										//
										// 	/**
										// 	 * As documented by Schema.org at https://schema.org/OpeningHoursSpecification (https://archive.is/LSxMP)
										// 	 */
										//
										// 	// Add this location's details to the main openingHours schema array
										//
										// 		$schema_openingHours = uamswp_fad_schema_opening_hours(
										// 			$schema_dayOfWeek, // string|array // Required // The day of the week for which these opening hours are valid. // Days are specified using their first two letters (e.g., Su)
										// 			$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
										// 			$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
										// 			$schema_openingHours // mixed // Optional // Pre-existing list array for openingHours to which to add additional items
										// 		);

										// Google method: OpeningHoursSpecification Schema Data

											/**
											 * As documented by Google at https://developers.google.com/search/docs/appearance/structured-data/local-business (https://archive.is/pncpy)
											 */

											// Loop through all the days in the array separately

												foreach ( $schema_dayOfWeek as $day) {

													$schema_openingHoursSpecification = uamswp_fad_schema_openinghoursspecification(
														$day, // array|string // Optional // The day of the week for which these opening hours are valid.
														$schema_opens, // string // Optional // The opening hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
														$schema_closes, // string // Optional // The closing hour of the place or service on the given day(s) of the week. // Times are specified using 24:00 format.
														'', // string // Optional // The date when the item becomes valid.
														'', // string // Optional // The date after when the item is not valid. For example the end of an offer, salary period, or a period of opening hours.
														$schema_openingHoursSpecification // array // Optional // Pre-existing list array for OpeningHoursSpecification to which to add additional items
													);

												}

									// Set the text for the day or time span (closed or hours open)

										if ( $hour['closed'] ) {

											/**
											 * If the location is closed on this day or time span...
											 */

											// Definition term and definition description tag set

												/**
												 * Set the text for the day or time span (closed)
												 */

												$hours_text .= 'Closed ';

										} else {

											/**
											 * Else if the location is open on this day or time span...
											 */

											// Definition term and definition description tag set

												/**
												 *  Set the text for the day or time span (hours open)
												 */

												$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] ) ? '' . ap_time_span( strtotime($hour['open']), strtotime($hour['close']) ) . '' : '' );

										}

									// Set the comment for the day or time span

										if ( $hour['comment'] ) {

											/**
											 * If a comment exists for this day or time span...
											 */

											// Definition term and definition description tag set

												$hours_text .= ' <br /><span class="subtitle">' .$hour['comment'] . '</span>';

											// Store comment for comparison on next repeater row

												$comment = $hour['comment'];

										} else {

											/**
											 * Else if no comment exists for this day or time span...
											 */

											$comment = '';

										} // if ( $hour['comment'] ) else

									// Definition term and definition description tag set

										/**
										 * Close the definition description tag
										 */

										$hours_text .= '</dd>';

									// Store day for comparison on next repeater row

										$day = $hour['day'];

								} // endforeach ( $location_hours_repeater as $hour )

							// Definition term and definition description tag set

								echo $hours_text;

						} else {

							// Write a definition term tag

								echo '<dt>No information</dt>';

						} // endif ( $location_hours_repeater ) else

						// Close the definition list tag

							echo '</dl>';

					} // endif ( $location_hours_24_7_query ) else

					// $holidayhours = get_field('location_holiday_hours'); // Holiday Hours // repeater
					//
					// if ( $holidayhours ) {
					//
					// 	// If the Holiday Hours repeater has at least one row
					//
					// 	/**
					// 	 * Sort by date
					// 	 * if current date is before date & within 30 days
					// 	 * Display results
					// 	 */
					//
					// 	$order = array();
					//
					// 	// populate order
					// 	foreach ( $holidayhours as $i => $row ) {
					//
					// 		$order[ $i ] = $row['date'];
					//
					// 	} // endforeach ( $holidayhours as $i => $row )
					//
					// 	// multisort
					// 	array_multisort( $order, SORT_ASC, $holidayhours );
					//
					// 	$i = 0;
					//
					// 	foreach ( $holidayhours as $row ) {
					//
					// 		$holidayDate = $row['date']; // Text
					// 		$holidayDateTime = DateTime::createFromFormat('m/d/Y', $holidayDate); // Date for evaluation
					// 		$dateNow = new DateTime("now", new DateTimeZone('America/Chicago') );
					//
					//		if (
					//			( $dateNow < $holidayDateTime )
					//			&&
					//			( $holidayDateTime->diff($dateNow)->days < 30 )
					//		) {
					//
					// 			if ( 0 == $i ) {
					// 				echo '<h3>Upcoming Holiday Hours</h3>';
					// 				echo '<dl class="hours">';
					// 				$i++;
					// 			}
					//
					// 			echo '<dt>'. $row['label'] . '<br />' . $holidayDate . '<br/>';
					// 			echo '</dt>' . '<dd>';
					//
					// 			if ( $row['closed'] ) {
					//
					// 				echo $row['closed'] ? 'Closed</dd>': '';
					//
					// 			} else {
					//
					// 				echo ( ( $hour['open'] && '00:00:00' != $row['open'] ) ? '' . ap_time_span( strtotime($row['open']), strtotime($row['close']) ) . ' ' : '' );
					//
					// 			}
					// 		}
					//
					// 	} // endforeach ( $holidayhours as $row )
					//
					// 	if ( 0 < $i ) {
					//
					// 		echo '</dl>';
					//
					// 	} // endif ( 0 < $i )
					//
					// } // endif ( $holidayhours )

				} // endif ( $location_hours_24_7_query || $location_hours_repeater[0]['day'] )

			} // endif ( ( $location_hours_modified_active_start != '' && $location_hours_modified_active_start <= $today) && ( $location_hours_modified_active_end > $today_30 || $location_hours_modified_active_end == 'TBD' ) ) else

	} // endif ( $location_hours_variable_query )

// Display After Hours Information

	if ( $location_after_hours ) {

		?>
		<h2>After Hours</h2>
		<?php

		echo $location_after_hours;

	} // endif ( $location_after_hours )