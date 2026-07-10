<?php

// AP Style for Dates
if ( !function_exists('apStyleDate') ) {
	function apStyleDate($date){

		$date = strftime("%l:%M %p", strtotime($date));
	
		$date = str_replace(":00", "", $date);
		$date = str_replace("m", ".m.", $date);
	
		return $date;
	
	}
}
/**
 * Format a date as AP style
 * This was modified from https://gist.github.com/tryonegg/d2e07e1d8f4ff8f1219ca639583f97ee
 *
 * @param int     $date The date as a datetime.
 * @param boolean $today Should Today be inserted if it is today?
 * @param boolean $captoday Catapatlize Today?
 * @param boolean $useyear include the year?
 * @param boolean $useweekdaynames include weekday names?
 *
 * @return string
 */
if ( !function_exists('ap_date') ) {
  function ap_date( $date, $today = true, $captoday = true, $useyear = true, $useweekdaynames = true ) {

    // if(false == isDate($date)){
    //   $date = strtotime($date);
    // }

    // Format the weekday name.
    if ( true == $useweekdaynames ) {
      $weekdayname = date( 'l,', $date );
    } else {
      $weekdayname = '';
    }

    // Determine the month and set the AP Style abbreviation.
    if ( date( 'm', $date ) == '01' ) {
      $apmonth = 'Jan. ';
    } elseif ( date( 'm', $date ) == '02' ) {
      $apmonth = 'Feb. ';
    } elseif ( date( 'm', $date ) == '08' ) {
      $apmonth = 'Aug. ';
    } elseif ( date( 'm', $date ) == '09' ) {
      $apmonth = 'Sept. ';
    } elseif ( date( 'm', $date ) == '10' ) {
      $apmonth = 'Oct. ';
    } elseif ( date( 'm', $date ) == '11' ) {
      $apmonth = 'Nov. ';
    } elseif ( date( 'm', $date ) == '12' ) {
      $apmonth = 'Dec. ';
    } else {
      $apmonth = ( date( 'F', $date ) );
    }

    // Determine whether the date is within the current year and set it.
    if ( date( 'Y', $date ) != date( 'Y' ) ) {
        $apyear = ', ' . date( 'Y', $date );
    } else {
      if ( true == $useyear ) {
        $apyear = ', ' . date( 'Y', $date );
      } else {
        $apyear = '';
      }
    }

    // Determine whether the date is the current date and set the final output.
    if ( true == $today && date( 'F j Y', $date ) == date( 'F j Y' ) ) {
      if ( true == $captoday ) {
        $apdate = 'Today';
      } else {
        $apdate = 'today';
      }
    } else {
      $apdate = $weekdayname . ' ' . $apmonth . ' ' . date( 'j', $date ) . '' . $apyear;
    }

    return $apdate;
  }
}

/**
 * Format time for AP style
 * This was modified from http://www.rockmycar.net/ap-style-dates-and-times-plugin/
 *
 * @param int  $time the datetime as a timestamp.
 * @param bool $capnoon Should we capatalize the wood Noon?
 *
 * @return string
 */
if ( !function_exists('ap_time') ) {
	function ap_time($time, $capnoon = true){

      // if(false == isDate($time)){
      //   $time = strtotime($time);
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

/**
 * Takes two datetimes and converts them to an ap style time range string.
 *
 * @param int $start The start date as a timestamp.
 * @param int $end The end date as a timestamp.
 *
 * @return string
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

if ( !function_exists('isDate') ) {
  function isDate($value) {
    if (!$value) {
        return false;
    } else {
        $date = date_parse($value);
        if($date['error_count'] == 0 && $date['warning_count'] == 0){
            return checkdate($date['month'], $date['day'], $date['year']);
        } else {
            return false;
        }
    }
  }
}

if ( !function_exists('apStyleTime') ) {
	function apStyleTime($time, $capnoon = true){

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

// Partition / Split Col function
if ( !function_exists('partition') ) {
    function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice( $list, $mark, $incr );
            $mark += $incr;
        }
        return $partition;
    }
}

/**
 * Return anchor link for a single breadcrumb.
 *
 * @param string      $url     URL for href attribute.
 * @param string      $content Linked content.
 * @param bool|string $sep     Optional. Separator. Default is empty string.
 *
 * @return string HTML markup for anchor link and optional separator.
 */
// function uamswp_get_breadcrumb_link( $url, $content, $sep = '' ) {

//   $itemprop_item = genesis_html5() ? ' itemprop="item"' : '';
//   $itemprop_name = genesis_html5() ? ' itemprop="name"' : '';

//   $link = sprintf(
//     '<a href="%s"%s><span%s>%s</span></a>',
//     esc_attr( $url ),
//     $itemprop_item,
//     $itemprop_name,
//     $content
//   );

//   if ( genesis_html5() ) {
//     $link = sprintf(
//       '<span %s>',
//       genesis_attr( 'breadcrumb-link-wrap' )
//     ) . $link . '</span>';
//   }

//   if ( $sep ) {
//     $link .= $sep;
//   }

//   return $link;

// }

/**
 * Filter the Genesis CPT breadcrumb.
 *
 * @since 2.5.0
 *
 * @param string $crumb HTML markup for the CPT breadcrumb.
 * @param array  $args  Arguments used to generate the breadcrumbs. Documented in Genesis_Breadcrumbs::get_output().
 */
// function uamswp_cpt_breadcrumb( $crumb, $args ) {
//     global $wp_query;
  
//     if ( !is_singular( 'expertise' ) ) {
//       return $crumb;
//     }
  
//     $post = $wp_query->get_queried_object();

//     $crumb = $crumb = '<a href="'. get_post_type_archive_link( 'expertise' ) .'">'. get_post_type_object( 'expertise' )->labels->name .'</a>' . $args['sep'];
  
//     // If this is a top level Page, it's simple to output the breadcrumb.
//     if ( ! $post->post_parent ) {
//       $crumb .= get_the_title();
//     } else {
//       // Get the IDs of all parents and ancestors in an array.
//       $ancestors = get_post_ancestors( $post->ID );
  
//       // Add ancestor breacrumb links to $crumbs array
//       // get_breadcrumb_link() https://gist.github.com/natenault/af861546ab8a3468d12a09e89437ed54
//       $crumbs = array();
//       foreach ( $ancestors as $ancestor ) {
//         array_unshift(
//           $crumbs,
//           uamswp_get_breadcrumb_link( get_permalink( $ancestor ), get_the_title( $ancestor ) )
//         );
//       }
  
//       // Add the current page title.
//       $crumbs[] = get_the_title( $post->ID );
        
//       $crumb .= implode( $args['sep'], $crumbs );
//     }
  
//     return $crumb;
//   }
  // add_filter( 'genesis_cpt_crumb', 'uamswp_cpt_breadcrumb', 10, 2 );

  /**
 * Pass in a taxonomy value that is supported by WP's `get_taxonomy`
 * and you will get back the url to the archive view.
 * @param $taxonomy string|int
 * @return string
 */
function get_taxonomy_archive_link( $taxonomy ) {
  $tax = get_taxonomy( $taxonomy ) ;
  return '/' . $tax->rewrite['slug'];
}

  // function uamswp_add_blog_crumb( $crumb, $args ) {
  //   if ( is_singular( 'condition' ) || is_singular( 'treatment' ) || is_tax( 'condition' ) || is_tax( 'treatment' ) )
  //     return '<a href="' . get_taxonomy_archive_link( get_queried_object()->taxonomy ) . '/">' . get_taxonomy( get_queried_object()->taxonomy )->labels->name .'</a> ' . $args['sep'] . ' ' . $crumb;
  //   else
  //     return $crumb;
  // }
  // add_filter( 'genesis_single_crumb', 'uamswp_add_blog_crumb', 10, 2 );
  // add_filter( 'genesis_archive_crumb', 'uamswp_add_blog_crumb', 10, 2 );
  // add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_fad_taxonomy_breadcrumbs_crumbs');
  function uamswp_fad_taxonomy_breadcrumbs_crumbs($crumbs) {
    if ( is_singular( 'condition' ) || is_singular( 'treatment' ) || is_tax( 'condition' ) || is_tax( 'treatment' ) ) {
      $taxonomy = array(get_taxonomy( get_queried_object()->taxonomy )->labels->name, get_taxonomy_archive_link( get_queried_object()->taxonomy ));
      array_splice($crumbs, -1, 0, array($taxonomy));
    }
    return $crumbs;
  }

  // Query custom table values
  function uamswp_custom_table_query($table_name, $field_name, $query_values) {
    // Declare the global wpdb variable
    global $wpdb;
 
    $custom_table = $wpdb->prefix.$table_name;
 
    $filter_where ='';        
    $i = 1;
    foreach ($query_values as $value) {
        if ($i > 1) {
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
    // injection hacks as the prepare() method santizes the data accordingly. The output is a prepared, sanitized SQL
    // statement ready to be executed.
    // $SQL = $wpdb->prepare( $SQL );
 
    // Query the database with our prepared SQL statement, fetching the first column of the matched rows. In our case, we
    // only queried the post_id field of each row so we know that the post_id fields will be the first column. The result
    // here is an array of post_ids (provided we have a match)
    $post_ids = $wpdb->get_col( $SQL );
    return $post_ids;
 }

/**
 * Shared provider-derived strings.
 *
 * Extracted verbatim from templates/single-physicians.php, which is the canonical
 * runtime construction of these values. The GMB call sites
 * (includes/class.fad-gmb-settings-page.php, templates/gmb-list-provider.php)
 * build superficially similar strings from different sources -- they read the
 * physician_prefix field rather than deriving "Dr." from the degree list, they
 * use the raw clinical_title term name rather than the term's
 * clinical_specialization_title override, and they have no resident special case
 * or indefinite-article exception list. Migrating them onto these helpers would
 * change GMB output, so they are deliberately left alone.
 */

// Construct the variants of a provider's name
if ( !function_exists('uamswp_provider_names') ) {

	/**
	 * @param int $provider_id Provider post ID.
	 * @return array short, medium, full, short_possessive, sort, sort_param, and _attr variants.
	 */
	function uamswp_provider_names( $provider_id ) {

		$empty = array(
			'full'                  => '',
			'full_attr'             => '',
			'medium'                => '',
			'medium_attr'           => '',
			'short'                 => '',
			'short_attr'            => '',
			'short_possessive'      => '',
			'short_possessive_attr' => '',
			'sort'                  => '',
			'sort_param'            => '',
		);

		if ( !$provider_id ) {
			return $empty;
		}

		// Get the elements of the provider's name

			$first_name  = get_field( 'physician_first_name', $provider_id );
			$middle_name = get_field( 'physician_middle_name', $provider_id );
			$last_name   = get_field( 'physician_last_name', $provider_id );

			// Generational suffix (e.g., Jr.)
			$pedigree = get_field( 'physician_pedigree', $provider_id );

			// Degrees and credentials (e.g., M.D., Ph.D.)
			$degrees = get_field( 'physician_degree', $provider_id );

			// Clean up degrees value

				if (
					$degrees
					&&
					is_array($degrees)
				) {

					$degrees = array_filter($degrees);
					$degrees = array_unique($degrees);
					$degrees = array_values($degrees);

				}

			$degree_count      = $degrees ? count($degrees) : 0;
			$degree_list       = '';
			$degree_attr_array = array();
			$i = 1;

			if ( $degrees ) {

				foreach ( $degrees as $degree ) {

					$degree_term = get_term( $degree, 'degree' );

					if ( is_object($degree_term) ) {

						$degree_name = $degree_term->name;
						$degree_list .= $degree_name;
						$degree_attr_array[] = uamswp_attr_conversion($degree_name);

						if ( $degree_count > $i ) {

							$degree_list .= ', ';

						}

						$i++;

					}

				} // endforeach

			} // endif ( $degrees )

			// Remove empty rows, remove duplicate rows, reindex

				$degree_attr_array = array_filter($degree_attr_array);
				$degree_attr_array = array_unique( $degree_attr_array, SORT_REGULAR );
				$degree_attr_array = array_values($degree_attr_array);

		// Set the "Dr." prefix

			// Define list of degrees or credentials needed for the "Dr." prefix
			// (per UAMS Health clinical administration)

				$prefix_degrees = array(
					'M.D.',
					'D.O.'
				);

			$prefix = '';

			if (
				array_intersect(
					$prefix_degrees, // The array with master values to check.
					$degree_attr_array // Arrays to compare values against.
				)
			) {

				$prefix = 'Dr.';

			}

		// Construct the variants of the provider's name

			// Full name (e.g., "Leonard H. McCoy, M.D.")

				$full_name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '') .  ( $degree_list ? ', ' . $degree_list : '' );

			// Medium name (e.g., "Dr. Leonard H. McCoy")

				$medium_name = ($prefix ? $prefix .' ' : '') . $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name;

			// Short name (e.g., "Dr. McCoy")

				$short_name = $prefix ? $prefix .'&nbsp;' .$last_name : $first_name .' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($pedigree ? '&nbsp;' . $pedigree : '');

			// Short name possessive (e.g., "Dr. McCoy's")

				if ( substr($short_name, -1) == 's' ) {

					// If the provider's name ends in "s", use an apostrophe with no "s"
					$short_name_possessive = $short_name . '\'';

				} else {

					$short_name_possessive = $short_name . '\'s';

				}

			// Sort name (e.g., "McCoy, Leonard H.")

				$sort_name = $last_name . ', ' . $first_name . ' ' . $middle_name;

			// Sort name parameter (e.g., "mccoy-leonard-h")

				$sort_name_param_value = sanitize_title_with_dashes($sort_name);

		return array(
			'full'                  => $full_name,
			'full_attr'             => $full_name ? uamswp_attr_conversion($full_name) : '',
			'medium'                => $medium_name,
			'medium_attr'           => $medium_name ? uamswp_attr_conversion($medium_name) : '',
			'short'                 => $short_name,
			'short_attr'            => $short_name ? uamswp_attr_conversion($short_name) : '',
			'short_possessive'      => $short_name_possessive,
			'short_possessive_attr' => $short_name_possessive ? uamswp_attr_conversion($short_name_possessive) : '',
			'sort'                  => $sort_name,
			'sort_param'            => $sort_name_param_value,
		);

	}

}

// Get a provider's clinical occupation title
if ( !function_exists('uamswp_provider_occupation_title') ) {

	/**
	 * Residents get a fixed title. Otherwise the clinical_title term's
	 * clinical_specialization_title field wins, falling back to the term name.
	 *
	 * @param int $provider_id Provider post ID.
	 * @return string Occupation title, or '' when none can be resolved.
	 */
	function uamswp_provider_occupation_title( $provider_id ) {

		if ( !$provider_id ) {
			return '';
		}

		$resident            = get_field( 'physician_resident', $provider_id );
		$resident_title_name = 'Resident Physician';

		$provider_occupation_title = '';

		if ( $resident ) {

			$provider_occupation_title = $resident_title_name;

		} else {

			// Clinical Specialty

				$provider_specialty = get_field( 'physician_title', $provider_id );

			// Clinical Occupation Title

				if ( $provider_specialty ) {

					$provider_specialty_term = get_term($provider_specialty, 'clinical_title');

					if ( is_object($provider_specialty_term) ) {

						// Get term name

							$provider_specialty_name = $provider_specialty_term->name;

						// Get occupational title field from term

							$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term) ?? null;

						// Set occupational title from term name as a fallback

							if ( !$provider_occupation_title ) {

								$provider_occupation_title = $provider_specialty_name;

							}

					}

				}

		}

		return $provider_occupation_title ? $provider_occupation_title : '';

	}

}

// Get a provider's pronouns
if ( !function_exists('uamswp_provider_pronouns') ) {

	/**
	 * Derived from the required physician_gender radio (female|male|other).
	 * "other" -- and an unexpectedly empty value -- map to the singular they,
	 * which takes a plural verb ("they share", not "they shares"); callers that
	 * build prose should branch on the 'plural' key for verb agreement.
	 *
	 * @param int $provider_id Provider post ID.
	 * @return array subject, object, possessive, possessive_pronoun, reflexive, plural.
	 */
	function uamswp_provider_pronouns( $provider_id ) {

		$gender = $provider_id ? get_field( 'physician_gender', $provider_id ) : '';

		switch ( $gender ) {

			case 'female':
				$pronouns = array(
					'subject'            => 'she',
					'object'             => 'her',
					'possessive'         => 'her',
					'possessive_pronoun' => 'hers',
					'reflexive'          => 'herself',
					'plural'             => false,
				);
				break;

			case 'male':
				$pronouns = array(
					'subject'            => 'he',
					'object'             => 'him',
					'possessive'         => 'his',
					'possessive_pronoun' => 'his',
					'reflexive'          => 'himself',
					'plural'             => false,
				);
				break;

			// 'other', and any unexpected or empty value, use the singular they
			default:
				$pronouns = array(
					'subject'            => 'they',
					'object'             => 'them',
					'possessive'         => 'their',
					'possessive_pronoun' => 'theirs',
					'reflexive'          => 'themselves',
					'plural'             => true,
				);
				break;

		}

		return $pronouns;

	}

}

// The generated Short Description for a Provider Spotlight
if ( !function_exists('uamswp_spotlight_default_excerpt') ) {

	/**
	 * Used when an editor leaves the Short Description blank. Written to the
	 * clinical_resource_excerpt field on save so it flows through the existing
	 * custom_excerpt_acf() pipeline into post_excerpt, and from there into the
	 * social sharing description and the resource cards.
	 *
	 * Deliberately pronoun-free, so it is safe for any provider. Plain text,
	 * 160 characters or fewer, matching the field's own maxlength.
	 *
	 * @param int $provider_id Provider post ID.
	 * @return string
	 */
	function uamswp_spotlight_default_excerpt( $provider_id ) {

		if ( !$provider_id ) {

			return '';

		}

		$names = uamswp_provider_names( $provider_id );
		$title = uamswp_provider_occupation_title( $provider_id );

		// The name variants carry a non-breaking space; the excerpt is plain text
		$name = $names['medium_attr'];

		if ( !$name ) {

			return '';

		}

		if ( $title ) {

			$excerpt = sprintf(
				'Meet %1$s, %2$s at UAMS Health, in this Provider Spotlight Q&A.',
				$name,
				$title
			);

		} else {

			$excerpt = sprintf(
				'Meet %1$s of UAMS Health in this Provider Spotlight Q&A.',
				$name
			);

		}

		$excerpt = apply_filters( 'uamswp_spotlight_default_excerpt', $excerpt, $provider_id, $names, $title );

		// Match the truncation used by custom_excerpt_acf()
		$excerpt = strlen($excerpt) > 160 ? mb_strimwidth($excerpt, 0, 156, '...') : $excerpt;

		return $excerpt;

	}

}

// The default set of Provider Spotlight questions
if ( !function_exists('uamswp_spotlight_default_questions') ) {

	/**
	 * Seeded into the Q&A repeater on new clinical resources. Editors may
	 * reorder, edit, add, or remove rows afterwards. This list is expected to
	 * grow, so it lives in one place and is filterable.
	 *
	 * @return array List of question strings.
	 */
	function uamswp_spotlight_default_questions() {

		return apply_filters(
			'uamswp_spotlight_default_questions',
			array(
				'What inspired you to work in healthcare?',
				'What is the best part of your job?',
				'What do you like about working at UAMS?',
				'Who has had the biggest influence on your life, and why?',
				'What book, podcast, TV show, or activity are you enjoying right now, and why?',
				'What is one thing on your bucket list?',
			)
		);

	}

}

// Define the indefinite article to precede a phrase (a or an)
if ( !function_exists('uamswp_indefinite_article') ) {

	/**
	 * Vowel-based, with an exception list for phrases whose written first letter
	 * disagrees with its spoken sound.
	 *
	 * @param string $phrase Phrase the article will precede (e.g. a clinical title).
	 * @return string 'a' or 'an'.
	 */
	function uamswp_indefinite_article( $phrase ) {

		// Guard the empty string: reading offset [0] of '' raises a PHP 8 warning.
		// single-physicians.php never hits this because it always has a title.
		if ( !$phrase ) {
			return 'a';
		}

		if (
			in_array(
				strtolower($phrase)[0],
				array( 'a', 'e', 'i', 'o', 'u' )
			)
		) {

			// If the phrase starts with a vowel, use "an"
			$indef_article = 'an';

		} else {

			// If the phrase does not start with a vowel, use "a"
			$indef_article = 'a';

		}

		// Define a list of exceptions to the vowel-based determination.

			/**
			 * - Use "a" before consonant sounds: a historic event, a one-year term.
			 * - Use "an" before vowel sounds: an honor, an NBA record.
			 * - Write the key as the characters at the beginning of the exception. It can be a complete or incomplete title.
			 * - Write the value as the indefinite article to use in that case ('a' or 'an').
			 */

			$indef_article_exceptions = apply_filters(
				'uamswp_indefinite_article_exceptions',
				array(
					'SNF'     => 'an',
					'Urolog'  => 'a',
					'Uveitis' => 'a'
				)
			);

			if ( !empty($indef_article_exceptions) ) {

				foreach( $indef_article_exceptions as $exception => $article ) {

					if (
						substr(
							strtolower($phrase),
							0,
							strlen($exception)
						) == strtolower($exception)
					) {

						// If the phrase begins with the exception key, use the key's value
						$indef_article = $article;

					}

				}

			}

		return $indef_article;

	}

}