<?php

declare(strict_types=1);

// AP Style for Dates
if (! function_exists('apStyleDate')) {
    /**
     * @param  mixed  $date
     */
    function apStyleDate($date): string
    {

        $date = (string) strftime('%l:%M %p', (int) strtotime(uamswp_fad_str($date)));

        $date = str_replace(':00', '', $date);

        return str_replace('m', '.m.', $date);

    }
}

/**
 * Format a date as AP style
 * This was modified from https://gist.github.com/tryonegg/d2e07e1d8f4ff8f1219ca639583f97ee
 *
 * @param  int  $date  The date as a datetime.
 * @param  bool  $today  Should Today be inserted if it is today?
 * @param  bool  $captoday  Catapatlize Today?
 * @param  bool  $useyear  include the year?
 * @param  bool  $useweekdaynames  include weekday names?
 * @return string
 */
if (! function_exists('ap_date')) {
    /**
     * @param  mixed  $date
     * @param  mixed  $today
     * @param  mixed  $captoday
     * @param  mixed  $useyear
     * @param  mixed  $useweekdaynames
     */
    function ap_date($date, $today = true, $captoday = true, $useyear = true, $useweekdaynames = true): string
    {

        // if(false == isDate($date)){
        //   $date = strtotime($date);
        // }

        // Format the weekday name.
        if ($useweekdaynames == true) {
            $weekdayname = date('l,', uamswp_fad_int($date));
        } else {
            $weekdayname = '';
        }

        // Determine the month and set the AP Style abbreviation.
        if (date('m', uamswp_fad_int($date)) === '01') {
            $apmonth = 'Jan. ';
        } elseif (date('m', uamswp_fad_int($date)) === '02') {
            $apmonth = 'Feb. ';
        } elseif (date('m', uamswp_fad_int($date)) === '08') {
            $apmonth = 'Aug. ';
        } elseif (date('m', uamswp_fad_int($date)) === '09') {
            $apmonth = 'Sept. ';
        } elseif (date('m', uamswp_fad_int($date)) === '10') {
            $apmonth = 'Oct. ';
        } elseif (date('m', uamswp_fad_int($date)) === '11') {
            $apmonth = 'Nov. ';
        } elseif (date('m', uamswp_fad_int($date)) === '12') {
            $apmonth = 'Dec. ';
        } else {
            $apmonth = (date('F', uamswp_fad_int($date)));
        }

        // Determine whether the date is within the current year and set it.
        if (date('Y', uamswp_fad_int($date)) !== date('Y')) {
            $apyear = ', '.date('Y', uamswp_fad_int($date));
        } else {
            if ($useyear == true) {
                $apyear = ', '.date('Y', uamswp_fad_int($date));
            } else {
                $apyear = '';
            }
        }

        // Determine whether the date is the current date and set the final output.
        if ($today == true && date('F j Y', uamswp_fad_int($date)) === date('F j Y')) {
            if ($captoday == true) {
                $apdate = 'Today';
            } else {
                $apdate = 'today';
            }
        } else {
            $apdate = $weekdayname.' '.$apmonth.' '.date('j', uamswp_fad_int($date)).$apyear;
        }

        return $apdate;
    }
}

/**
 * Format time for AP style
 * This was modified from http://www.rockmycar.net/ap-style-dates-and-times-plugin/
 *
 * @param  int  $time  the datetime as a timestamp.
 * @param  bool  $capnoon  Should we capatalize the wood Noon?
 * @return string
 */
if (! function_exists('ap_time')) {
    /**
     * @param  mixed  $time
     * @param  mixed  $capnoon
     */
    function ap_time($time, $capnoon = true): string
    {

        // if(false == isDate($time)){
        //   $time = strtotime($time);
        // }

        // Format am and pm to AP Style abbreviations.
        if (date('a', uamswp_fad_int($time)) === 'am') {
            $meridian = 'a.m.';
        } else {
            // `a` formats as `am` or `pm` and nothing else.
            $meridian = 'p.m.';
        }

        // Reformat 12:00 and 00:00 to noon and midnight.
        if (date('H:i', uamswp_fad_int($time)) === '00:00') {
            if ($capnoon == true) {
                $aptime = 'Midnight';
            } else {
                $aptime = 'midnight';
            }
        } elseif (date('H:i', uamswp_fad_int($time)) === '12:00') {
            if ($capnoon == true) {
                $aptime = 'Noon';
            } else {
                $aptime = 'noon';
            }

            // Eliminate trailing zeroes from times at the top of the hour and set final output.
        } elseif (date('i', uamswp_fad_int($time)) === '00') {
            $aptime = date('g', uamswp_fad_int($time)).' '.$meridian;
        } else {
            $aptime = date('g:i', uamswp_fad_int($time)).' '.$meridian;
        }

        return $aptime;

    }
}

/**
 * Takes two datetimes and converts them to an ap style time range string.
 *
 * @param  int  $start  The start date as a timestamp.
 * @param  int  $end  The end date as a timestamp.
 * @return string
 */
if (! function_exists('ap_time_span')) {
    /**
     * @param  mixed  $start
     * @param  mixed  $end
     */
    function ap_time_span($start, $end): string
    {

        if (date('a', uamswp_fad_int($start)) === date('a', uamswp_fad_int($end))) {
            $starttime = str_replace('p.m.', '', ap_time($start));
            $starttime = str_replace('a.m.', '', $starttime);

            return trim($starttime).' &ndash; '.ap_time($end);
        }

        return ap_time($start).' &ndash; '.ap_time($end);
    }
}

if (! function_exists('isDate')) {
    /**
     * @param  mixed  $value
     */
    function isDate($value): bool
    {
        if (! $value) {
            return false;
        }

        $date = date_parse(uamswp_fad_str($value));
        if ($date['error_count'] === 0 && $date['warning_count'] === 0) {
            return checkdate(uamswp_fad_int($date['month']), uamswp_fad_int($date['day']), uamswp_fad_int($date['year']));
        }

        return false;
    }
}

if (! function_exists('apStyleTime')) {
    /**
     * @param  mixed  $time
     * @param  mixed  $capnoon
     */
    function apStyleTime($time, $capnoon = true): string
    {

        $time = (int) strtotime(uamswp_fad_str($time));

        // Format am and pm to AP Style abbreviations.
        if (date('a', $time) === 'am') {
            $meridian = 'a.m.';
        } else {
            // `a` formats as `am` or `pm` and nothing else.
            $meridian = 'p.m.';
        }

        // Reformat 12:00 and 00:00 to noon and midnight.
        if (date('H:i', $time) === '00:00') {
            if ($capnoon == true) {
                $aptime = 'Midnight';
            } else {
                $aptime = 'midnight';
            }
        } elseif (date('H:i', $time) === '12:00') {
            if ($capnoon == true) {
                $aptime = 'Noon';
            } else {
                $aptime = 'noon';
            }

            // Eliminate trailing zeroes from times at the top of the hour and set final output.
        } elseif (date('i', $time) === '00') {
            $aptime = date('g', $time).' '.$meridian;
        } else {
            $aptime = date('g:i', $time).' '.$meridian;
        }

        return $aptime;

    }
}

// Partition / Split Col function
if (! function_exists('partition')) {
    /**
     * @param  array<mixed>  $list
     * @param  int  $p
     * @return mixed[][]
     */
    function partition($list, $p): array
    {
        $listlen = count($list);
        $partlen = (int) floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = [];
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice(uamswp_fad_arr($list), $mark, $incr);
            $mark += $incr;
        }

        return $partition;
    }
}

/**
 * Return anchor link for a single breadcrumb.
 *
 * @param  string  $url  URL for href attribute.
 * @param  string  $content  Linked content.
 * @param  bool|string  $sep  Optional. Separator. Default is empty string.
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
 * @param  string  $crumb  HTML markup for the CPT breadcrumb.
 * @param  array  $args  Arguments used to generate the breadcrumbs. Documented in Genesis_Breadcrumbs::get_output().
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
 *
 * @param  string|int  $taxonomy
 */
function get_taxonomy_archive_link($taxonomy): string
{
    $tax = get_taxonomy((string) $taxonomy);
    // Without a taxonomy the rewrite is not an array either way, so true and false give the same link.
    // @pest-mutate-ignore: FalseToTrue
    $rewrite = $tax instanceof WP_Taxonomy ? $tax->rewrite : false;

    return '/'.(is_array($rewrite) ? uamswp_fad_str(($rewrite['slug'] ?? '')) : '');
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
/**
 * @param  array<int, mixed>  $crumbs
 * @return array<int, mixed>
 */
function uamswp_fad_taxonomy_breadcrumbs_crumbs($crumbs)
{
    if (is_singular('condition') || is_singular('treatment') || is_tax('condition') || is_tax('treatment')) {
        $taxonomy_name = uamswp_fad_str((get_queried_object()->taxonomy ?? ''));
        $tax = get_taxonomy($taxonomy_name);

        if ($tax instanceof WP_Taxonomy) {
            $taxonomy = [$tax->labels->name, get_taxonomy_archive_link($taxonomy_name)];
            $crumbs = [...array_slice($crumbs, 0, -1), $taxonomy, ...array_slice($crumbs, -1)];
        }
    }

    return $crumbs;
}

// Query custom table values
/**
 * @param  string  $table_name
 * @param  string  $field_name
 * @param  array<mixed>  $query_values
 * @return mixed
 */
function uamswp_custom_table_query($table_name, $field_name, $query_values)
{
    // Declare the global wpdb variable
    /** @var wpdb $wpdb */
    global $wpdb;

    $custom_table = $wpdb->prefix.$table_name;

    $filter_where = '';
    $i = 1;
    foreach ($query_values as $value) {
        if ($i > 1) {
            $filter_where .= ' OR ';
        }

        $filter_where .= "`$field_name` LIKE '%".uamswp_fad_str($value)."%'";
        $i++;
    }

    // Write our custom query. In this query, we're only selecting the post_id field of each row that matches our set of
    // conditions. Note the %s placeholders – these are dynamic and indicate that we'll be injecting strings in their place.
    $SQL = 'SELECT `post_id` FROM `'.$custom_table."`
            WHERE $filter_where
            ORDER BY `post_id` ASC;";

    // Use $wpdb's prepare() method to replace the placeholders with our actual data. Doing it this way protects against
    // injection hacks as the prepare() method santizes the data accordingly. The output is a prepared, sanitized SQL
    // statement ready to be executed.
    // $SQL = $wpdb->prepare( $SQL );

    // Query the database with our prepared SQL statement, fetching the first column of the matched rows. In our case, we
    // only queried the post_id field of each row so we know that the post_id fields will be the first column. The result
    // here is an array of post_ids (provided we have a match)
    $post_ids = $wpdb->get_col($SQL);

    return $post_ids;
}

// Escape a provider name variant for an HTML text context
if (! function_exists('uamswp_provider_name_html')) {

    /**
     * The name variants returned by uamswp_provider_names() are built by
     * concatenating plain-text ACF fields (physician_first_name, _last_name,
     * _pedigree, ...). Those fields receive no kses filtering on save, so a name
     * containing markup persists verbatim and would execute if echoed raw. Escape
     * it here for any text or heading context.
     *
     * The short, possessive, and full variants embed a literal non-breaking-space
     * entity between a prefix or pedigree and the surname; esc_html() would turn
     * that into a visible "&amp;nbsp;", so restore it after escaping.
     *
     * @param  string  $name  A provider name variant.
     * @return string Escaped for HTML text output, with &nbsp; preserved.
     */
    function uamswp_provider_name_html($name): string
    {

        return str_replace('&amp;nbsp;', '&nbsp;', esc_html((string) $name));

    }

}

/**
 * Shared provider-derived strings.
 *
 * Extracted verbatim from templates/single-physicians.php, which is the canonical
 * runtime construction of these values. The GMB call site
 * (includes/class.fad-gmb-settings-page.php)
 * builds superficially similar strings from different sources -- it uses the
 * raw clinical_title term name rather than the term's
 * clinical_specialization_title override, and it has no resident special case
 * or indefinite-article exception list. Migrating it onto these helpers would
 * change GMB output, so it is deliberately left alone, except for the "Dr."
 * prefix: it and the clinical resource card take that from the 'prefix' value
 * below, the degree rule, not from the physician_prefix field.
 */

// Construct the variants of a provider's name
if (! function_exists('uamswp_provider_names')) {

    /**
     * @param  int  $provider_id  Provider post ID.
     * @return array<mixed> prefix ("Dr." for an M.D. or D.O., otherwise empty), short, medium, medium_no_prefix, full,
     *                      short_possessive, sort, sort_param, and _attr variants.
     */
    function uamswp_provider_names($provider_id): array
    {

        $empty = [
            'full' => '',
            'full_attr' => '',
            'prefix' => '',
            'medium' => '',
            'medium_attr' => '',
            'medium_no_prefix' => '',
            'medium_no_prefix_attr' => '',
            'short' => '',
            'short_attr' => '',
            'short_possessive' => '',
            'short_possessive_attr' => '',
            'sort' => '',
            'sort_param' => '',
        ];

        if (! $provider_id) {
            return $empty;
        }

        // Get the elements of the provider's name

        $first_name = uamswp_fad_str(get_field('physician_first_name', $provider_id));

        $middle_name = uamswp_fad_str(get_field('physician_middle_name', $provider_id));

        $last_name = uamswp_fad_str(get_field('physician_last_name', $provider_id));

        // Generational suffix (e.g., Jr.)
        $pedigree = uamswp_fad_str(get_field('physician_pedigree', $provider_id));

        // Degrees and credentials (e.g., M.D., Ph.D.)
        $degrees = get_field('physician_degree', $provider_id);

        // Clean up degrees value

        if (
            $degrees
            &&
            is_array($degrees)
        ) {

            $degrees = array_filter($degrees);
            $degrees = array_unique($degrees, SORT_REGULAR);
            // The degrees are read with foreach and count(), which ignore the keys.
            // @pest-mutate-ignore: UnwrapArrayValues
            $degrees = array_values($degrees);

        }

        // The count is read only when there are degrees, so the fallback value never shows.
        // @pest-mutate-ignore: DecrementInteger,IncrementInteger
        $degree_count = $degrees ? count(uamswp_fad_arr($degrees)) : 0;
        $degree_list = '';
        $degree_attr_array = [];
        $i = 1;

        if ($degrees) {

            foreach (uamswp_fad_arr($degrees) as $degree) {

                $degree_term = get_term(uamswp_fad_int($degree), 'degree');

                if (is_object($degree_term)) {

                    $degree_name = ($degree_term->name ?? '');
                    $degree_list .= uamswp_fad_str($degree_name);
                    $degree_attr_array[] = uamswp_attr_conversion($degree_name);

                    if ($degree_count > $i) {

                        $degree_list .= ', ';

                    }

                    $i++;

                }

            } // endforeach

        } // endif ( $degrees )

        // Set the "Dr." prefix

        // Define list of degrees or credentials needed for the "Dr." prefix
        // (per UAMS Health clinical administration)

        $prefix_degrees = [
            'M.D.',
            'D.O.',
        ];

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

        $full_name = $first_name.' '.($middle_name ? $middle_name.' ' : '').$last_name.($pedigree ? '&nbsp;'.$pedigree : '').($degree_list ? ', '.$degree_list : '');

        // Medium name (e.g., "Dr. Leonard H. McCoy")

        $medium_name = ($prefix ? $prefix.' ' : '').$first_name.' '.($middle_name ? $middle_name.' ' : '').$last_name;

        // Medium name without the "Dr." prefix or any degrees (e.g.,
        // "Leonard H. McCoy"). Useful where the prefix and credentials get
        // in the way -- e.g. building a clean URL slug.

        $medium_no_prefix_name = trim($first_name.' '.($middle_name ? $middle_name.' ' : '').$last_name);

        // Short name (e.g., "Dr. McCoy")

        $short_name = $prefix ? $prefix.'&nbsp;'.$last_name : $first_name.' '.($middle_name ? $middle_name.' ' : '').$last_name.($pedigree ? '&nbsp;'.$pedigree : '');

        // Short name possessive (e.g., "Dr. McCoy's")

        if (str_ends_with($short_name, 's')) {

            // If the provider's name ends in "s", use an apostrophe with no "s"
            $short_name_possessive = $short_name."'";

        } else {

            $short_name_possessive = $short_name."'s";

        }

        // Sort name (e.g., "McCoy, Leonard H.")

        $sort_name = $last_name.', '.$first_name.' '.$middle_name;

        // Sort name parameter (e.g., "mccoy-leonard-h")

        $sort_name_param_value = sanitize_title_with_dashes($sort_name);

        return [
            'full' => $full_name,
            'full_attr' => uamswp_attr_conversion($full_name),
            'prefix' => $prefix,
            'medium' => $medium_name,
            'medium_attr' => uamswp_attr_conversion($medium_name),
            'medium_no_prefix' => $medium_no_prefix_name,
            'medium_no_prefix_attr' => $medium_no_prefix_name ? uamswp_attr_conversion($medium_no_prefix_name) : '',
            'short' => $short_name,
            'short_attr' => uamswp_attr_conversion($short_name),
            'short_possessive' => $short_name_possessive,
            'short_possessive_attr' => uamswp_attr_conversion($short_name_possessive),
            'sort' => $sort_name,
            'sort_param' => $sort_name_param_value,
        ];

    }

}

// Get a provider's clinical occupation title
if (! function_exists('uamswp_provider_occupation_title')) {

    /**
     * Residents get a fixed title. Otherwise the clinical_title term's
     * clinical_specialization_title field wins, falling back to the term name.
     *
     * @param  int  $provider_id  Provider post ID.
     * @return string Occupation title, or '' when none can be resolved.
     */
    function uamswp_provider_occupation_title($provider_id): string
    {

        if (! $provider_id) {
            return '';
        }

        $resident = get_field('physician_resident', $provider_id);
        $resident_title_name = 'Resident Physician';

        $provider_occupation_title = '';

        if ($resident) {

            $provider_occupation_title = $resident_title_name;

        } else {

            // Clinical Specialty

            $provider_specialty = get_field('physician_title', $provider_id);

            // Clinical Occupation Title

            if ($provider_specialty) {

                $provider_specialty_term = get_term(uamswp_fad_int($provider_specialty), 'clinical_title');

                if (is_object($provider_specialty_term)) {

                    // Get term name

                    $provider_specialty_name = ($provider_specialty_term->name ?? '');

                    // Get occupational title field from term

                    $provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term);

                    // Set occupational title from term name as a fallback

                    if (! $provider_occupation_title) {

                        $provider_occupation_title = $provider_specialty_name;

                    }

                }

            }

        }

        return uamswp_fad_str($provider_occupation_title) ?: '';

    }

}

// Get a provider's pronouns
if (! function_exists('uamswp_provider_pronouns')) {

    /**
     * Derived from the required physician_gender radio (female|male|other).
     * "other" -- and an unexpectedly empty value -- map to the singular they,
     * which takes a plural verb ("they share", not "they shares"); callers that
     * build prose should branch on the 'plural' key for verb agreement.
     *
     * @param  int  $provider_id  Provider post ID.
     * @return array<mixed> subject, object, possessive, possessive_pronoun, reflexive, plural.
     */
    function uamswp_provider_pronouns($provider_id): array
    {

        // Without a provider any value that is not female or male reaches the same default arm.
        // @pest-mutate-ignore: EmptyStringToNotEmpty
        $gender = $provider_id ? get_field('physician_gender', $provider_id) : '';

        return match ($gender) {
            'female' => [
                'subject' => 'she',
                'object' => 'her',
                'possessive' => 'her',
                'possessive_pronoun' => 'hers',
                'reflexive' => 'herself',
                'plural' => false,
            ],
            'male' => [
                'subject' => 'he',
                'object' => 'him',
                'possessive' => 'his',
                'possessive_pronoun' => 'his',
                'reflexive' => 'himself',
                'plural' => false,
            ],
            default => [
                'subject' => 'they',
                'object' => 'them',
                'possessive' => 'their',
                'possessive_pronoun' => 'theirs',
                'reflexive' => 'themselves',
                'plural' => true,
            ],
        };

    }

}

// The generated Short Description for a Provider Spotlight
if (! function_exists('uamswp_spotlight_default_excerpt')) {

    /**
     * Used when an editor leaves the Short Description blank. Written to the
     * clinical_resource_excerpt field on save so it flows through the existing
     * custom_excerpt_acf() pipeline into post_excerpt, and from there into the
     * social sharing description and the resource cards.
     *
     * Deliberately pronoun-free, so it is safe for any provider. Plain text,
     * 160 characters or fewer, matching the field's own maxlength.
     *
     * @param  int  $provider_id  Provider post ID.
     * @return string
     */
    function uamswp_spotlight_default_excerpt($provider_id)
    {

        if (! $provider_id) {

            return '';

        }

        $names = uamswp_provider_names($provider_id);

        // Lowercase the clinical title for the generated prose (e.g. "optometrist")
        $title = mb_strtolower(uamswp_provider_occupation_title($provider_id));

        // The name variants carry a non-breaking space; the excerpt is plain text
        $name = $names['medium_attr'];

        // Guard the trimmed value: the name is concatenated, so an all-empty
        // provider (e.g. deleted post) yields spaces rather than an empty string.
        if (trim(uamswp_fad_str($name)) === '') {

            return '';

        }

        if ($title) {

            $excerpt = sprintf(
                'Meet %1$s, %2$s at UAMS Health, in this provider spotlight Q&A.',
                uamswp_fad_str($name),
                $title
            );

        } else {

            $excerpt = sprintf(
                'Meet %1$s of UAMS Health in this provider spotlight Q&A.',
                uamswp_fad_str($name)
            );

        }

        $excerpt = apply_filters('uamswp_spotlight_default_excerpt', $excerpt, $provider_id, $names, $title);

        // Neutralize any markup a provider name might carry: the excerpt is stored
        // in post_excerpt and echoed as plain text in resource cards.
        $excerpt = wp_strip_all_tags(uamswp_fad_str($excerpt));

        // Match the truncation used by custom_excerpt_acf()
        $excerpt = strlen($excerpt) > 160 ? mb_strimwidth($excerpt, 0, 156, '...') : $excerpt;

        return $excerpt;

    }

}

// The generated introduction for a Provider Spotlight
if (! function_exists('uamswp_spotlight_default_intro')) {

    /**
     * Used when an editor leaves the Introduction blank. Built at render time
     * rather than persisted on save, so it always reflects the provider's
     * current name and clinical title.
     *
     * The provider's name is the subject of the second sentence, so it takes a
     * singular verb regardless of pronouns; only the possessive pronoun varies.
     *
     * @param  int  $provider_id  Provider post ID.
     * @return string HTML.
     */
    function uamswp_spotlight_default_intro($provider_id): string
    {

        if (! $provider_id) {

            return '';

        }

        $names = uamswp_provider_names($provider_id);

        // Lowercase the clinical title for the generated prose (e.g. "an optometrist")
        $title = mb_strtolower(uamswp_provider_occupation_title($provider_id));
        $pronouns = uamswp_provider_pronouns($provider_id);

        // Guard the trimmed value: the medium variant is built by concatenation,
        // so a provider with every name part empty (e.g. deleted post) yields a
        // string of spaces, not an empty string.
        if (trim(uamswp_fad_str($names['medium'])) === '') {

            return '';

        }

        // Escape the name and title parts: they come from plain-text ACF fields
        // and taxonomy term names, none kses-filtered, and land in an HTML string.
        $medium_html = uamswp_provider_name_html(uamswp_fad_str($names['medium']));
        $short_html = uamswp_provider_name_html(uamswp_fad_str($names['short']));

        if ($title) {

            $opening = sprintf(
                'Get to know %1$s, %2$s %3$s at UAMS Health.',
                $medium_html,
                uamswp_indefinite_article($title),
                esc_html($title)
            );

        } else {

            $opening = sprintf(
                'Get to know %1$s of UAMS Health.',
                uamswp_fad_str($medium_html)
            );

        }

        $intro = sprintf(
            '<p>%1$s In the Q&amp;A below, %2$s shares what inspired %3$s work in healthcare, %3$s life outside of medicine, and more.</p>',
            uamswp_fad_str($opening),
            uamswp_fad_str($short_html),
            uamswp_fad_str($pronouns['possessive'])
        );

        return uamswp_fad_str(apply_filters('uamswp_spotlight_intro', $intro, $provider_id, $names, $title, $pronouns));

    }

}

// The default set of Provider Spotlight questions
if (! function_exists('uamswp_spotlight_default_questions')) {

    /**
     * Seeded into the Q&A repeater on new clinical resources. Editors may
     * reorder, edit, add, or remove rows afterwards. This list is expected to
     * grow, so it lives in one place and is filterable.
     *
     * @return array<mixed> List of question strings.
     */
    function uamswp_spotlight_default_questions(): array
    {

        return uamswp_fad_arr(apply_filters(
            'uamswp_spotlight_default_questions',
            [
                'What inspired you to work in healthcare?',
                'What is the best part of your job?',
                'What do you like about working at UAMS?',
                'Who has had the biggest influence on your life, and why?',
                'What book, podcast, TV show, or activity are you enjoying right now, and why?',
                'What is one thing on your bucket list?',
            ]
        ));

    }

}

// The provider-specific appointment CTA for a Provider Spotlight
if (! function_exists('uamswp_spotlight_appointment_cta')) {

    /**
     * Builds the Provider Spotlight call-to-action band. On a spotlight clinical
     * resource this renders in the site appointment slot (#appointment-info) in
     * place of the generic "Make an Appointment" module, so it mirrors that
     * module's markup (cta-bar cta-bar-1 bg-auto) and carries the same
     * data-itemtitle attributes on its links.
     *
     * Scheduling prose appears only when the provider is bookable (not a
     * resident, sees patients via appointments, and accepting new patients); a
     * referral note is added when a referral is required. Otherwise the band
     * suppresses scheduling prose and simply invites the reader to the profile.
     * When bookable with a scheduling phone set on the clinical resource, a
     * call-to-schedule action is blended in; the tel: link stays plain (no
     * itemprop) to hold the no-net-new-schema decision.
     *
     * @param  int  $provider_id  Published provider post ID.
     * @return string Section markup, filterable via 'uamswp_spotlight_cta'.
     */
    function uamswp_spotlight_appointment_cta($provider_id): string
    {

        $names = uamswp_provider_names($provider_id);

        $medium_name_attr = $names['medium_attr'];

        // Escaped for HTML text context. The name variants come from plain-text
        // ACF fields with no kses filtering, so they must not be echoed raw.
        $medium_name = uamswp_provider_name_html(uamswp_fad_str($names['medium']));
        $short_possessive = uamswp_provider_name_html(uamswp_fad_str($names['short_possessive']));

        $provider_url = get_permalink($provider_id);

        // Appointment eligibility, mirroring the provider profile's appointment
        // block (templates/blocks/appointment-provider.php): residents never take
        // appointments, and scheduling prose shows only when the provider both
        // sees patients via appointments and is accepting new patients. ACF
        // returns "1"/"0"/"" here, so plain truthy checks match that block.
        $resident = get_field('physician_resident', $provider_id);
        $eligible_appt = $resident ? false : get_field('physician_eligible_appointments', $provider_id);
        $accept_new = get_field('physician_accepting_patients', $provider_id);
        $refer_req = get_field('physician_referral_required', $provider_id);
        $can_schedule = $eligible_appt && $accept_new;

        // Reusable links; both carry the same data-itemtitle as the generic band.
        $name_link = sprintf(
            '<a href="%1$s" data-itemtitle="Learn more about %2$s">%3$s</a>',
            esc_url((string) $provider_url),
            esc_attr(uamswp_fad_str($medium_name_attr)),
            $medium_name
        );
        $profile_link = sprintf(
            '<a href="%1$s" data-itemtitle="Learn more about %2$s">visit %3$s provider profile</a>',
            esc_url((string) $provider_url),
            esc_attr(uamswp_fad_str($medium_name_attr)),
            $short_possessive
        );

        if ($can_schedule) {

            $cta_heading = 'Make an Appointment';

            // New patients need a referral: surface the same note the provider
            // profile uses.
            $referral = $refer_req ? 'Appointments for new patients are by referral only. ' : '';

            $phone = get_field('clinical_resource_spotlight_phone');
            // Dashed format for both the tel: href and the visible text (e.g. 501-686-8000)
            // The dashed phone is read only when there is a phone, so the fallback value never shows.
            // @pest-mutate-ignore: EmptyStringToNotEmpty
            $phone_dash = $phone ? format_phone_dash($phone) : '';

            if ($phone && $phone_dash) {

                $tel_link = sprintf(
                    '<a href="tel:%1$s" class="no-break" data-itemtitle="Call to schedule with %2$s">%3$s</a>',
                    esc_attr(uamswp_fad_str($phone_dash)),
                    esc_attr(uamswp_fad_str($medium_name_attr)),
                    esc_html(uamswp_fad_str($phone_dash))
                );

                $cta_body = $referral.sprintf(
                    'Ready to schedule an appointment with %1$s? Call&nbsp;%2$s to request an appointment, or %3$s to learn more.',
                    $name_link,
                    $tel_link,
                    $profile_link
                );

            } else {

                $cta_body = $referral.sprintf(
                    'To schedule an appointment with %1$s, %2$s.',
                    $name_link,
                    $profile_link
                );

            }

        } else {

            // Not bookable: no scheduling prose and no appointment heading. One
            // invitation, with the link on the profile it points to. It does not
            // restate the page's opening "Get to know ..." line, so it needs no
            // occupation title and reads the same with or without one.
            $cta_heading = 'Learn More';
            $phone = ''; // no scheduling number is surfaced when not bookable

            $profile_noun_link = sprintf(
                '<a href="%1$s" data-itemtitle="Learn more about %2$s">%3$s provider profile</a>',
                esc_url((string) $provider_url),
                esc_attr(uamswp_fad_str($medium_name_attr)),
                $short_possessive
            );

            $cta_body = sprintf(
                'Learn more about %1$s, including background, specialties and locations, on %2$s.',
                $medium_name,
                $profile_noun_link
            );

        }

        $cta = sprintf(
            '<section class="uams-module cta-bar cta-bar-1 bg-auto extra-padding cta-bar-lg" id="appointment-info" aria-labelledby="spotlight-cta-title">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<h2 id="spotlight-cta-title">%1$s</h2>
							<p>%2$s</p>
						</div>
					</div>
				</div>
			</section>',
            $cta_heading,
            $cta_body
        );

        return uamswp_fad_str(apply_filters('uamswp_spotlight_cta', $cta, $provider_id, $phone, $names));

    }

}

// Define the indefinite article to precede a phrase (a or an)
if (! function_exists('uamswp_indefinite_article')) {

    /**
     * Vowel-based, with an exception list for phrases whose written first letter
     * disagrees with its spoken sound.
     *
     * @param  string  $phrase  Phrase the article will precede (e.g. a clinical title).
     * @return string 'a' or 'an'.
     */
    function uamswp_indefinite_article($phrase): string
    {

        // Guard the empty string: reading offset [0] of '' raises a PHP 8 warning.
        // single-physicians.php never hits this because it always has a title.
        if (! $phrase) {
            return 'a';
        }

        if (
            in_array(
                strtolower($phrase)[0],
                ['a', 'e', 'i', 'o', 'u'],
                true
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
            [
                'SNF' => 'an',
                'Urolog' => 'a',
                'Uveitis' => 'a',
            ]
        );

        if (! empty($indef_article_exceptions)) {

            foreach (uamswp_fad_arr($indef_article_exceptions) as $exception => $article) {

                if (
                    substr(
                        strtolower($phrase),
                        0,
                        strlen(uamswp_fad_str($exception))
                    ) === strtolower(uamswp_fad_str($exception))
                ) {

                    // If the phrase begins with the exception key, use the key's value
                    $indef_article = $article;

                }

            }

        }

        return uamswp_fad_str($indef_article);

    }

}

/**
 * The IDs of a list returned by `get_posts()` or `get_terms()` with `fields` set to an ID field.
 *
 * Those functions are typed as returning objects or IDs whichever `fields` says, so a caller that
 * only wants integers passes the result through here instead of casting each item.
 *
 * @param  array<int|string|WP_Post|WP_Term>  $items  The posts, terms or IDs to read.
 * @return list<int>
 */
function uamswp_fad_ids(array $items): array
{
    $ids = [];

    foreach ($items as $item) {
        if ($item instanceof WP_Post) {
            $ids[] = $item->ID;
        } elseif ($item instanceof WP_Term) {
            $ids[] = $item->term_id;
        } else {
            $ids[] = (int) $item;
        }
    }

    return $ids;
}

/**
 * The posts a query found, as post objects.
 *
 * `WP_Query::$posts` is typed as post objects or IDs because `fields` can ask for IDs. A caller
 * that reads post properties passes the query through here, which keeps the objects.
 *
 * @return list<WP_Post>
 */
function uamswp_fad_query_posts(WP_Query $query): array
{
    return array_values(array_filter($query->posts, static fn ($post): bool => $post instanceof WP_Post));
}

/**
 * The post type of a post ID that ACF or WordPress may pass as a non-ID.
 *
 * ACF hands `'options'`, `'term_5'` and `'user_3'` to the same hooks as a post ID; none of those is a post.
 */
function uamswp_fad_post_type_of(mixed $id): string|false
{
    return is_numeric($id) ? get_post_type((int) $id) : false;
}

/**
 * A value read from post meta, an ACF field, a request or an option, as an array.
 *
 * An array comes back as it is; anything else gives an empty array.
 *
 * @return array<mixed>
 */
function uamswp_fad_arr(mixed $value): array
{
    return is_array($value) ? $value : [];
}

/**
 * A value read from post meta, an ACF field, a request or an option, as an array keyed by string.
 *
 * An array comes back with its keys read as strings; anything else gives an empty array.
 *
 * @return array<string, mixed>
 */
function uamswp_fad_assoc(mixed $value): array
{
    $out = [];
    foreach (uamswp_fad_arr($value) as $key => $item) {
        // PHP turns a numeric string key back into an integer, so the cast changes nothing.
        // @pest-mutate-ignore: RemoveStringCast
        $out[(string) $key] = $item;
    }

    return $out;
}

/**
 * One submitted ACF field value, read from `$_POST['acf']` by its field key.
 */
function uamswp_fad_posted(string $key): mixed
{
    $acf = uamswp_fad_arr($_POST['acf'] ?? null);

    return $acf[$key] ?? null;
}

/**
 * Whether the submitted ACF values carry a field key.
 */
function uamswp_fad_posted_has(string $key): bool
{
    return array_key_exists($key, uamswp_fad_arr($_POST['acf'] ?? null));
}

/**
 * Set one submitted ACF field value in `$_POST['acf']` by its field key.
 */
function uamswp_fad_post_set(string $key, mixed $value): void
{
    $acf = uamswp_fad_arr($_POST['acf'] ?? null);
    $acf[$key] = $value;
    $_POST['acf'] = $acf;
}

/**
 * A value read from post meta, an ACF field, a request or an option, as a string.
 *
 * Scalars and null convert as a `(string)` cast does; an array or an object, which a cast would
 * turn into a notice and the text "Array", gives an empty string.
 */
function uamswp_fad_str(mixed $value): string
{
    return is_scalar($value) ? (string) $value : '';
}

/**
 * A value read from post meta, an ACF field, a request or an option, as an integer.
 *
 * Scalars and null convert as an `(int)` cast does; an array or an object gives 0.
 */
function uamswp_fad_int(mixed $value): int
{
    return is_scalar($value) ? (int) $value : 0;
}

/**
 * A value read from post meta, an ACF field, a request or an option, as a float.
 *
 * Scalars and null convert as a `(float)` cast does; an array or an object gives 0.0.
 */
function uamswp_fad_float(mixed $value): float
{
    return is_scalar($value) ? (float) $value : 0.0;
}
