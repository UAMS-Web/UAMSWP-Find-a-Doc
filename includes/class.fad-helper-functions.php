<?php

// Check if a string is a date

	/**
	 * This was modified from https://stackoverflow.com/a/49796755
	 *
	 * @param string	$value	Date/time in format accepted by DateTimeImmutable::__construct().
	 *
	 * @return	bool
	 */

	 if ( !function_exists('isDate') ) {

		function isDate( $value ) {

			if ( !$value ) {

				return false;

			} else {

				$date = date_parse($value);

				if ( $date['error_count'] == 0 && $date['warning_count'] == 0 ) {

					return checkdate( $date['month'], $date['day'], $date['year'] );

				} else {

					return false;

				}

			}

		}

	}

// Format values for AP Style

	// Format time values for AP Style

		// Format a single time for AP Style (v1)

			/**
			 * @param string	$date	The time value as a string
			 *
			 * @return	string
			 */

			if ( !function_exists('apStyleDate') ) {

				function apStyleDate( $date ){

					$date = strftime("%l:%M %p", strtotime($date));

					$date = str_replace(":00", "", $date);
					$date = str_replace("m", ".m.", $date);

					return $date;

				}

			}

		// Format a single time for AP Style (v2)

			/**
			 * This was modified from http://www.rockmycar.net/ap-style-dates-and-times-plugin/
			 *
			 * @param int		$time		The time value as a Unix timestamp
			 * @param boolean	$capnoon	Should the words 'noon' or 'midnight' be capitalized?
			 *
			 * @return	string
			 */

			if ( !function_exists('ap_time') ) {

				function ap_time( $time, $capnoon = true ){

					// if(false == isDate($time)){
					//
					// 	$time = strtotime($time);
					//
					// }

					// Format am and pm to AP Style abbreviations.

						if ( date( 'a', $time ) == 'am' ) {

							$meridian = 'a.m.';

						} elseif ( date( 'a', $time ) == 'pm' ) {

							$meridian = 'p.m.';

						}

					// Reformat 12:00 and 00:00 to noon and midnight.

						if ( date( 'H:i', $time ) == '00:00' ) {

							if ( true == $capnoon ) {

								$aptime = 'Midnight';

							} else {

								$aptime = 'midnight';

							}

						} elseif ( date( 'H:i', $time ) == '12:00' ) {

							if ( true == $capnoon ) {

								$aptime = 'Noon';

							} else {

								$aptime = 'noon';

							}

							// Eliminate trailing zeroes from times at the top of the hour and set final output.

						} elseif ( date( 'i', $time ) == '00' ) {

							$aptime = date( 'g', $time ) . ' ' . $meridian;

						} else {

							$aptime = date( 'g:i', $time ) . ' ' . $meridian;

						}

					return $aptime;

				}

			}

		// Format a single time for AP Style (v3)

			/**
			 * @param string	$time		The time value as a Unix timestamp.
			 * @param boolean	$capnoon	Should the words 'noon' or 'midnight' be capitalized?
			 *
			 * @return	string
			 */

			if ( !function_exists('apStyleTime') ) {

				function apStyleTime( $time, $capnoon = true ) {

					$time = strtotime($time);

					// Format am and pm to AP Style abbreviations.

						if ( date( 'a', $time ) == 'am' ) {

							$meridian = 'a.m.';

						} elseif ( date( 'a', $time ) == 'pm' ) {

							$meridian = 'p.m.';

						}

					// Reformat 12:00 and 00:00 to noon and midnight.

						if ( date( 'H:i', $time ) == '00:00' ) {

							if ( true == $capnoon ) {

								$aptime = 'Midnight';

							} else {

								$aptime = 'midnight';

							}

						} elseif ( date( 'H:i', $time ) == '12:00' ) {

							if ( true == $capnoon ) {

								$aptime = 'Noon';

							} else {

								$aptime = 'noon';

							}

							// Eliminate trailing zeroes from times at the top of the hour and set final output.

						} elseif ( date( 'i', $time ) == '00' ) {

							$aptime = date( 'g', $time ) . ' ' . $meridian;

						} else {

							$aptime = date( 'g:i', $time ) . ' ' . $meridian;

						}

					return $aptime;

				}

			}

		// Format a time range for AP Style

			/**
			 * Takes two DateTime values and converts them to an AP Style time range string.
			 *
			 * @param int	$start	The start date as a Unix timestamp
			 * @param int	$end	The end date as a Unix timestamp
			 *
			 * @return	string
			 */

			if ( !function_exists('ap_time_span') ) {

				function ap_time_span( $start, $end ) {

					if ( date( 'a', $start ) == date( 'a', $end ) ) {

						$starttime = str_replace( 'p.m.', '', ap_time( $start ) );
						$starttime = str_replace( 'a.m.', '', $starttime );

						return trim( $starttime ) . ' &ndash; ' . ap_time( $end );

					} else {

						return ap_time( $start ) . ' &ndash; ' . ap_time( $end );

					}

				}

			}

	// Format date values for AP Style

		// Format a single date for AP Style

			/**
			 * This was modified from https://gist.github.com/tryonegg/d2e07e1d8f4ff8f1219ca639583f97ee
			 *
			 * @param int		$date					The date as a Unix timestamp
			 * @param boolean	$use_today				Query for whether or not to output 'today' if the date is today
			 * @param boolean	$cap_today				Query for whether or not to capitalize 'today'
			 * @param boolean	$include_current_year	Query for whether or not to include the year when the date is within the current year
			 * @param boolean	$include_weekday		Query for whether or not to include weekday names
			 * @param boolean	$trailing_comma			Query for whether or not to include a trailing comma
			 * @param boolean	$nonbreaking_spaces		Query for whether or not to include non-breaking spaces within the date
			 *
			 * @return	string
			 */

			if ( !function_exists('ap_date') ) {

				function ap_date(
					int $date,
					bool $use_today = true,
					bool $cap_today = true,
					bool $include_current_year = true,
					bool $include_weekday = true,
					bool $trailing_comma = false,
					bool $nonbreaking_spaces = true
				) {

					// if (false == isDate($date) ){
					//
					// 	$date = strtotime($date);
					//
					// }

					// Base output value

						$output = '';

					// Date is Today

						/**
						 * If the date matches the current date, set the output as 'today'.
						 * Otherwise, construct the full date string.
						 */

						 if (
							$use_today
							&&
							date( 'F j Y', $date ) == date( 'F j Y' )
						) {

							$date_is_today = true;
							$output = $cap_today ? 'Today' : 'today';

						} else {

							$date_is_today = false;

						}

					// Dates Other Than Today

						/**
						 * If the date does not match the current date, construct the full date string.
						 */

						if ( !$date_is_today ) {

							// Weekday Name

								/**
								 * Include the weekday name at the beginning of the string when the
								 * $include_weekday argument is set to true.
								 *
								 * e.g., 'Thursday, ' in 'Thursday, Jan. 1, 1970'
								 */

								if ( $include_weekday ) {

									// Get the value

										$weekdayname = date( 'l', $date );

									// Add the value to the output

										$output .= $weekdayname . ', ';

								}

							// Month Name and Abbreviation

								/**
								 * Determine the month and abbreviate it according to AP Style.
								 *
								 *     * January as 'Jan.'
								 *     * February as 'Feb.'
								 *     * August as 'Aug.'
								 *     * September as 'Sept.'
								 *     * October as 'Oct.'
								 *     * November as 'Nov.'
								 *     * December as 'Dec.'
								 */

								// Get the value

									if ( date( 'm', $date ) == '01' ) {

										$ap_month = 'Jan.';

									} elseif ( date( 'm', $date ) == '02' ) {

										$ap_month = 'Feb.';

									} elseif ( date( 'm', $date ) == '08' ) {

										$ap_month = 'Aug.';

									} elseif ( date( 'm', $date ) == '09' ) {

										$ap_month = 'Sept.';

									} elseif ( date( 'm', $date ) == '10' ) {

										$ap_month = 'Oct.';

									} elseif ( date( 'm', $date ) == '11' ) {

										$ap_month = 'Nov.';

									} elseif ( date( 'm', $date ) == '12' ) {

										$ap_month = 'Dec.';

									} else {

										$ap_month = ( date( 'F', $date ) );

									}

								// Add the value to the output

									$output .= $ap_month . ( $nonbreaking_spaces ? '&nbsp;' : ' ' );

							// Day

								// Get the value

									$ap_day = date( 'j', $date );

								// Add the value to the output

									$output .= $ap_day;

							// Year

								/**
								 * Include the year if the date is not within the current year.
								 *
								 * If the $include_current_year argument is set as true, then include the year when the date is
								 * within the current year.
								 */

								if (
									date( 'Y', $date ) != date( 'Y' )
									||
									$include_current_year
								) {

									// Get the value

										$ap_year = date( 'Y', $date );

									// Add the value to the output

										$output .= ',' . ( $nonbreaking_spaces ? '&nbsp;' : ' ' ) . $ap_year;
										$output .= $trailing_comma ? ',' : ''; // Include a trailing comma if the $trailing_comma argument is set as true

								}

							// Trailing Comma

								if ( $trailing_comma ) {

									// Add the value to the output

										$output .= ',';

								}

						}

					return $output;

				}

			}

// Partition / Split Col function

	if ( !function_exists('partition') ) {

		function partition( $list, $p ) {

			$listlen = count( $list );
			$partlen = floor( $listlen / $p );
			$partrem = $listlen % $p;
			$partition = array();
			$mark = 0;

			for ( $px = 0; $px < $p; $px++ ) {

				$incr = ($px < $partrem) ? $partlen + 1 : $partlen;
				$partition[$px] = array_slice( $list, $mark, $incr );
				$mark += $incr;

			}

			return $partition;
		}

	}

// Return anchor link for a single breadcrumb

	/**
	 * @param string		$url		URL for href attribute.
	 * @param string		$content	Linked content.
	 * @param bool|string	$sep		Optional. Separator. Default is empty string.
	 *
	 * @return	string	HTML markup for anchor link and optional separator.
	 */

	// function uamswp_get_breadcrumb_link( $url, $content, $sep = '' ) {
	//
	// 	$itemprop_item = genesis_html5() ? ' itemprop="item"' : '';
	// 	$itemprop_name = genesis_html5() ? ' itemprop="name"' : '';
	//
	// 	$link = sprintf(
	// 		'<a href="%s"%s><span%s>%s</span></a>',
	// 		esc_attr( $url ),
	// 		$itemprop_item,
	// 		$itemprop_name,
	// 		$content
	// 	);
	//
	// 	if ( genesis_html5() ) {
	//
	// 		$link = sprintf(
	// 		'<span %s>',
	// 		genesis_attr( 'breadcrumb-link-wrap' )
	// 		) . $link . '</span>';
	//
	// 	}
	//
	// 	if ( $sep ) {
	//
	// 		$link .= $sep;
	//
	// 	}
	//
	// 	return $link;
	//
	// }

// Filter the Genesis CPT breadcrumb.

	/**
	 * @since 2.5.0
	 *
	 * @param string	$crumb	HTML markup for the CPT breadcrumb.
	 * @param array		$args	Arguments used to generate the breadcrumbs. Documented in Genesis_Breadcrumbs::get_output().
	 */

	// function uamswp_cpt_breadcrumb( $crumb, $args ) {
	//
	// 	// Bring in variables from outside of the function
	//
	// 		global $wp_query; // WordPress-specific global variable
	//
	// 	if ( !is_singular( 'expertise' ) ) {
	//
	// 		return $crumb;
	//
	// 	}
	//
	// 	$post = $wp_query->get_queried_object();
	//
	// 	$crumb = $crumb = '<a href="'. get_post_type_archive_link( 'expertise' ) .'">'. get_post_type_object( 'expertise' )->labels->name .'</a>' . $args['sep'];
	//
	// 	// If this is a top level Page, it's simple to output the breadcrumb.
	//
	// 	if ( ! $post->post_parent ) {
	//
	// 		$crumb .= get_the_title();
	//
	// 	} else {
	//
	// 		// Get the IDs of all parents and ancestors in an array.
	//
	// 			$ancestors = get_post_ancestors( $post->ID );
	//
	// 		// Add ancestor breadcrumb links to $crumbs array
	//
	// 			// get_breadcrumb_link() https://gist.github.com/natenault/af861546ab8a3468d12a09e89437ed54
	// 			$crumbs = array();
	//
	// 			foreach ( $ancestors as $ancestor ) {
	//
	// 				array_unshift(
	// 					$crumbs,
	// 					uamswp_get_breadcrumb_link( get_permalink( $ancestor ), get_the_title( $ancestor ) )
	// 				);
	//
	// 			}
	//
	// 		// Add the current page title.
	//
	// 			$crumbs[] = get_the_title( $post->ID );
	//
	// 		$crumb .= implode( $args['sep'], $crumbs );
	//
	// 	}
	//
	// 	return $crumb;
	//
	// }
	//
	// add_filter( 'genesis_cpt_crumb', 'uamswp_cpt_breadcrumb', 10, 2 );

// Get taxonomy archive link

	/**
	 * Pass in a taxonomy value that is supported by WP's `get_taxonomy`
	 * and you will get back the url to the archive view.
	 *
	 * @param	string|int	$taxonomy
	 *
	 * @return	string
	 */

	function get_taxonomy_archive_link( $taxonomy ) {

		$tax = get_taxonomy( $taxonomy ) ;

		return '/' . $tax->rewrite['slug'];

	}

// uamswp_add_blog_crumb

	// function uamswp_add_blog_crumb( $crumb, $args ) {
	//
	// 	if ( is_singular( 'condition' ) || is_singular( 'treatment' ) || is_tax( 'condition' ) || is_tax( 'treatment' ) )
	// 		return '<a href="' . get_taxonomy_archive_link( get_queried_object()->taxonomy ) . '/">' . get_taxonomy( get_queried_object()->taxonomy )->labels->name .'</a> ' . $args['sep'] . ' ' . $crumb;
	// 	else
	// 		return $crumb;
	//
	// }
	//
	// add_filter( 'genesis_single_crumb', 'uamswp_add_blog_crumb', 10, 2 );
	// add_filter( 'genesis_archive_crumb', 'uamswp_add_blog_crumb', 10, 2 );
	// add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_fad_taxonomy_breadcrumbs_crumbs');

// uamswp_fad_taxonomy_breadcrumbs_crumbs

	function uamswp_fad_taxonomy_breadcrumbs_crumbs( $crumbs ) {

		if ( is_singular( 'condition' ) || is_singular( 'treatment' ) || is_tax( 'condition' ) || is_tax( 'treatment' ) ) {

			$taxonomy = array(get_taxonomy( get_queried_object()->taxonomy )->labels->name, get_taxonomy_archive_link( get_queried_object()->taxonomy ));
			array_splice($crumbs, -1, 0, array($taxonomy));

		}

		return $crumbs;

	}

// Query custom table values

	function uamswp_custom_table_query( $table_name, $field_name, $query_values ) {

		// Bring in variables from outside of the function

			global $wpdb; // WordPress-specific global variable

		$custom_table = $wpdb->prefix.$table_name;

		$filter_where ='';
		$i = 1;

		foreach ( $query_values as $value ) {

			if ( $i > 1 ) {

				$filter_where .= " OR ";

			}

			$filter_where .= "`$field_name` LIKE '%" . $value . "%'";
			$i++;

		}

		// Write our custom query. In this query, we're only selecting the post_id field of each row that matches our set of
		// conditions. Note the %s placeholders – these are dynamic and indicate that we'll be injecting strings in their place.
		$SQL = "SELECT `post_id` FROM `" . $custom_table . "`
				WHERE $filter_where
				ORDER BY `post_id` ASC;";


		// Use $wpdb's prepare() method to replace the placeholders with our actual data. Doing it this way protects against
		// injection hacks as the prepare() method sanitizes the data accordingly. The output is a prepared, sanitized SQL
		// statement ready to be executed.
		// $SQL = $wpdb->prepare( $SQL );

		// Query the database with our prepared SQL statement, fetching the first column of the matched rows. In our case, we
		// only queried the post_id field of each row so we know that the post_id fields will be the first column. The result
		// here is an array of post_ids (provided we have a match)
		$post_ids = $wpdb->get_col( $SQL );

		return $post_ids;

	}