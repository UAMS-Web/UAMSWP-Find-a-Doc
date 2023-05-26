<?php

class FacetWP_Facet_Alpha_Addon extends FacetWP_Facet
{

    function __construct() {
        $this->label = __( 'Alphabet', 'fwp' );
    }


    /**
     * Generate the facet HTML
     */
    function render( $params ) {
        global $wpdb;

        $output = '';
        $facet = $params['facet'];
        $selected_values = (array) $params['selected_values'];

        // if ( '' !== $selected_values ) {
        // 	// "Any" selected value = false
        // 	$any_selected = ' selected';
        // 	$any_aria_value = 'true';
        // } else {
        // "Any" selected value = true
        $any_selected = ' selected';
        $any_aria_value = 'true';
        // }

        // Simulate "OR" mode (ignore this facet's selection)
        if ( isset( FWP()->or_values ) && ( 1 < count( FWP()->or_values ) || ! isset( FWP()->or_values[ $facet['name'] ] ) ) ) {
            $post_ids = array();
            $or_values = FWP()->or_values; // Preserve the original
            unset( $or_values[ $facet['name'] ] );

            $counter = 0;
            foreach ( $or_values as $name => $vals ) {
                $post_ids = ( 0 == $counter ) ? $vals : array_intersect( $post_ids, $vals );
                $counter++;
            }

            // Return only applicable results
            $post_ids = array_intersect( $post_ids, FWP()->unfiltered_post_ids );
            // "Any" selected value = false
            $any_selected = '  selected';
            $any_aria_value = 'true';
        }
        else {
            $post_ids = FWP()->unfiltered_post_ids;
            $any_selected = ' selected';
            $any_aria_value = 'true';
        }


		// Value is selected, set "Any" = false
        if ( isset( FWP()->or_values[ $facet['name'] ] )) {
	            $any_selected = '';
				$any_aria_value = 'false';
        }

        $post_ids = empty( $post_ids ) ? array( 0 ) : $post_ids;
        $where_clause = ' AND post_id IN (' . implode( ',', $post_ids ) . ')';

        $sql = "
        SELECT DISTINCT UPPER(LEFT(facet_display_value, 1)) AS letter
        FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}' $where_clause
        ORDER BY letter";
        $results = $wpdb->get_col( $sql );

        // Remove accents
        if ( ! empty( $results ) ) {
            $results = array_map( 'remove_accents', $results );
            $results = array_unique( $results );
        }

        $available_chars = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G',
            'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );

        $output .= '<a class="facetwp-alpha available'. $any_selected .'"  role="link" aria-label="Any" aria-selected="'. $any_aria_value .'" tabindex="0" data-id="">' . __( 'Any', 'fwp' ) . '</a>';

        foreach ( $available_chars as $char ) {
            $match = false;
            $active = in_array( $char, $selected_values );

            if ( '#' == $char ) {
                foreach ( array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ) as $num ) {
                    if ( in_array( (string) $num, $results ) ) {
                        $match = true;
                        break;
                    }
                }
            }
            elseif ( in_array( $char, $results ) ) {
                $match = true;
            }

            if ( $active ) {
                $output .= '<a class="facetwp-alpha selected" role="link" aria-label="' . $char . '" aria-selected="true" tabindex="0" data-id="' . $char . '">' . $char . '</a>';
            }
            elseif ( $match ) {
                $output .= '<a class="facetwp-alpha available" role="link" aria-label="' . $char . '" aria-selected="false" tabindex="0" data-id="' . $char . '">' . $char . '</a>';
            }
            else {
                $output .= '<a class="facetwp-alpha disabled" aria-disabled=“true” role="link" aria-label="' . $char . '" tabindex="-1" data-id="' . $char . '">' . $char . '</a>';
            }
        }

        return $output;
    }

    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $selected_values = $params['selected_values'];
        $selected_values = is_array( $selected_values ) ? $selected_values[0] : $selected_values;

        // The "#" character is an alias for all numbers
        if ( '#' == $selected_values ) {
            $selected_values = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );
            $selected_values = implode( "','", $selected_values );
        }

        $sql = "
        SELECT DISTINCT post_id FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}' AND UPPER(SUBSTR(facet_display_value, 1, 1)) IN ('$selected_values')";
        return $wpdb->get_col( $sql );
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
        FWP()->display->assets['alpha-front.css'] = plugins_url( '', __FILE__ ) . '/assets/css/front.css';
        FWP()->display->assets['alpha-front.js'] = plugins_url( '', __FILE__ ) . '/assets/js/front.js';
    }
}
