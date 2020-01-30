<?php
/*
 * Advanced Custom Fields Functions
 */
// ACF Custom Tables
/*
 * Changes the ACF Custom Database Tables JSON directory.
 * This needs to run before the 'plugins_loaded' action hook, so 
 * you need to put this in a plugin or in your wp-config.php file.
 */
define( 'ACFCDT_JSON_DIR', WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-tables' );
/*
 * Disables storing of meta data values in core meta tables where a custom 
 * database table has been defined for fields. Any fields that aren't mapped
 * to a custom database table will still be stored in the core meta tables. 
 */
add_filter( 'acfcdt/settings/store_acf_values_in_core_meta', '__return_false' );

/*
 * Disables storing of ACF field key references in core meta tables where a custom 
 * database table has been defined for fields. Any fields that aren't mapped to a 
 * custom database table will still have their key references stored in the core 
 * meta tables. 
 */
// add_filter( 'acfcdt/settings/store_acf_keys_in_core_meta', '__return_false' );

add_filter('acf/settings/load_json', 'uamswp_fad_json_load_point');

function uamswp_fad_json_load_point( $paths ) {
    
    // remove original path (optional)
    // unset($paths[0]);
    
    
    // append path
    $paths[] = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-json';
    
    
    // return
    return $paths;
    
}

add_filter('acf/prepare_field/key=field_physician_portal', 'set_default_portal', 20, 3);
add_filter('acf/prepare_field/key=field_location_portal', 'set_default_portal', 20, 3);
function set_default_portal( $field ) {
    // Only if no value set
    if( empty( $field['value'] ) ){
        $term = get_term_by('slug', 'uams-mychart', 'portal');
        $id = $term->term_id;
        $default = array($id);
        // Set field to default value
        $field[ 'value' ] = $default ;
    }
    return $field;
}
add_filter('acf/load_value/key=field_physician_languages', 'set_default_language', 20, 3);
function set_default_language($value, $post_id, $field) {
    // Only add default content for new posts
    if ( $value !== null ) {
        return $value;
    }
    
    $term = get_term_by('slug', 'english', 'language');
	$id = $term->term_id;
    $value = array($id);
  	return $value;
}

// Order for Portal - None slug set to "_none"
add_filter('acf/fields/taxonomy/wp_list_categories/key=field_location_portal', 'my_taxonomy_query', 10, 2);
add_filter('acf/fields/taxonomy/wp_list_categories/key=field_physician_portal', 'my_taxonomy_query', 10, 2);
function my_taxonomy_query( $args, $field ) {
    
    // modify args
    $args['orderby'] = 'slug';
    $args['order'] = 'ASC';
    
    
    // return
    return $args;
    
}

add_action('acf/save_post', 'physician_save_post', 5); 
function physician_save_post( $post_id ) {

  // Bail early if no data sent.
  if( empty($_POST['acf']) ) {
    return;
  }

  // Create full name to store in 'physician_full_name' field
  $first_name = $_POST['acf']['field_physician_first_name'];
  $middle_name = $_POST['acf']['field_physician_middle_name'];
  $last_name = $_POST['acf']['field_physician_last_name'];
  $pedigree = $_POST['acf']['field_physician_pedigree'];
  $degrees = $_POST['acf']['field_physician_degree'];

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

  $full_name = $first_name .' ' .( $middle_name ? $middle_name . ' ' : '') . $last_name . ( $pedigree ? '&nbsp;' . $pedigree : '') .  ( $degree_list ? ', ' . $degree_list : '' );

  $_POST['acf']['field_physician_full_name'] = $full_name;

}

add_action( 'acf/save_post', 'update_facetwp_index');
function update_facetwp_index( $post_id ) {
    if ( function_exists( 'FWP' ) ) {
        FWP()->indexer->index( $post_id );
    }
}
// Bidrectional for posts/cpts. 
// Field name _MUST_ be the same for each field.
// Keys must different
function bidirectional_acf_update_value( $value, $post_id, $field  ) {
	// vars
	$field_name = $field['name'];
	$field_key = $field['key'];
	$global_name = 'is_updating_' . $field_name;

	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;

	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;

	// loop over selected posts and add this $post_id
	if( is_array($value) ) {

		foreach( $value as $post_id2 ) {
			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);

			// allow for selected posts to not contain a value
			if( empty($value2) ) {
				$value2 = array();
			}

			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;

			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;

			// update the selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
		}
	}

	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);
	if( is_array($old_value) ) {

		foreach( $old_value as $post_id2 ) {
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;

			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);

			// bail early if no value
			if( empty($value2) ) continue;

			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value2);

			// remove
			unset( $value2[ $pos] );

			// update the un-selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
		}
	}
	// reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;
    
	// return
    return $value;
}
// Add filter for each bidirectional field. BOTH sides.
// Physician-to-locations (physician_locations)
add_filter('acf/update_value/key=field_physician_locations', 'bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/key=field_location_physicians', 'bidirectional_acf_update_value', 10, 3);
// Physician-to-expertise (physician_expertise)
add_filter('acf/update_value/key=field_physician_expertise', 'bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/key=field_expertise_physicians', 'bidirectional_acf_update_value', 10, 3);
// Location-to-expertise (location_expertise)
add_filter('acf/update_value/key=field_location_expertise', 'bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/key=field_expertise_locations', 'bidirectional_acf_update_value', 10, 3);
// Expertise-to-expertise
add_filter('acf/update_value/name=expertise_associated', 'bidirectional_acf_update_value', 10, 3);

// Post-to-taxonomy
function acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value ) {
    // vars
	$field_name = $post_field;
	$global_name = 'is_updating_' . $field_name;

	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;

	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;

	// loop over selected posts and add this $post_id
	if( is_array($value) ) {

		foreach( $value as $post_id2 ) {
			// load existing related posts
			$value2 = get_field($taxonomy_field, $taxonomy."_".$post_id2, false);

			// allow for selected posts to not contain a value
			if( empty($value2) ) {
				$value2 = array();
			}

			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;

			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;

			// update the selected post's value (use field's key for performance)
			update_field($taxonomy_field, $value2, $taxonomy."_".$post_id2);
		}
	}

	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);
	if( is_array($old_value) ) {

		foreach( $old_value as $post_id2 ) {
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;

			// load existing related posts
			$value2 = get_field($taxonomy_field, $taxonomy."_".$post_id2, false);

			// bail early if no value
			if( empty($value2) ) continue;

			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value2);

			// remove
			unset( $value2[ $pos] );

			// update the un-selected post's value (use field's key for performance)
			update_field($taxonomy_field, $value2, $taxonomy."_".$post_id2);
		}
	}
	// reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;

	// return
    return $value;
}

function acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value ) {
    // vars
// 	$field_name = $post_field;
	$global_name = 'is_updating_' . $taxonomy_field;
	$id = str_replace( 'term_', '', $post_id ); // Strip tax

	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;

	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;

	// loop over selected posts and add this $post_id
	if( is_array($value) ) {

		foreach( $value as $post_id2 ) {
			// load existing related posts
			$value2 = get_field($post_field, $post_id2, false);

			// allow for selected posts to not contain a value
			if( empty($value2) ) {
				$value2 = array();
			}

			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($id, $value2) ) continue;

			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $id;

			// update the selected post's value (use field's key for performance)
			update_field($post_field, $value2, $post_id2);
// 			update_field('physician_short_clinical_bio', $post_id, $post_id2 );
		}
	}

	// find posts which have been removed
	$old_value = get_field($taxonomy_field, $post_id, false);
	if( is_array($old_value) ) {

		foreach( $old_value as $post_id2 ) {
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;

			// load existing related posts
			$value2 = get_field($post_field, $post_id2, false);

			// bail early if no value
			if( empty($value2) ) continue;

			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($id, $value2);

			// remove
			unset( $value2[ $pos] );

			// update the un-selected post's value (use field's key for performance)
			update_field($post_field, $value2, $post_id2);
		}
	}
	// reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;

	// return
    return $value;
}
// Physician Taxonomies
function bidirectional_physician_conditions( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "condition";
    $taxonomy_field = 'condition_physicians';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_physician_treatments( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_physicians';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}
// Location Taxonomies
function bidirectional_location_conditions( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "condition";
    $taxonomy_field = 'condition_locations';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_location_treatments( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_locations';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}
// Location Taxonomies
function bidirectional_expertise_conditions( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "condition";
    $taxonomy_field = 'condition_expertise';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_expertise_treatments( $value, $post_id, $field  ) {
	// vars
	$post_field = $field['name'];
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_expertise';

    return acf_post_to_taxonomy_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}
// Taxonomy to Physicians
function bidirectional_condition_physicians( $value, $post_id, $field  ) {
    //vars
    $post_field = 'physician_conditions';
	$taxonomy = "condition";
	$taxonomy_field = 'condition_physicians';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_treatment_physicians( $value, $post_id, $field  ) {
    //vars
    $post_field = 'physician_treatments';
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_physicians';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}
// Taxonomy to Locations
function bidirectional_condition_locations( $value, $post_id, $field  ) {
    //vars
    $post_field = 'location_conditions';
	$taxonomy = "condition";
	$taxonomy_field = 'condition_locations';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_treatment_locations( $value, $post_id, $field  ) {
    //vars
    $post_field = 'location_treatments';
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_locations';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}
// Taxonomy to Expertise
function bidirectional_condition_expertise( $value, $post_id, $field  ) {
    //vars
    $post_field = 'expertise_conditions';
	$taxonomy = "condition";
	$taxonomy_field = 'condition_expertise';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

function bidirectional_treatment_expertise( $value, $post_id, $field  ) {
    //vars
    $post_field = 'expertise_treatments';
	$taxonomy = "treatment";
    $taxonomy_field = 'treatment_procedure_expertise';

    return acf_taxonomy_to_post_bidirectional( $post_field, $taxonomy_field, $taxonomy, $post_id, $value );
}

// Post to Taxonomy
add_filter('acf/update_value/name=physician_conditions', 'bidirectional_physician_conditions', 10, 3);
add_filter('acf/update_value/name=physician_treatments', 'bidirectional_physician_treatments', 10, 3);

add_filter('acf/update_value/name=location_conditions', 'bidirectional_location_conditions', 10, 3);
add_filter('acf/update_value/name=location_treatments', 'bidirectional_location_treatments', 10, 3);

add_filter('acf/update_value/name=expertise_conditions', 'bidirectional_expertise_conditions', 10, 3);
add_filter('acf/update_value/name=expertise_treatments', 'bidirectional_expertise_treatments', 10, 3);

add_filter('acf/update_value/name=condition_physicians', 'bidirectional_condition_physicians', 10, 3);
add_filter('acf/update_value/name=treatment_procedure_physicians', 'bidirectional_treatment_physicians', 10, 3);

add_filter('acf/update_value/name=condition_locations', 'bidirectional_condition_locations', 10, 3);
add_filter('acf/update_value/name=treatment_procedure_locations', 'bidirectional_treatment_locations', 10, 3);

add_filter('acf/update_value/name=condition_expertise', 'bidirectional_condition_expertise', 10, 3);
add_filter('acf/update_value/name=treatment_procedure_expertise', 'bidirectional_treatment_expertise', 10, 3);

add_action('acf/save_post', 'custom_excerpt_acf', 50);
function custom_excerpt_acf() {

    global $post;

    $post_id        = ( $post->ID ); // Current post ID
    $post_type      = get_post_type( $post_id ); // Get Post Type

    if ( 'expertise' == $post_type || 'provider' == $post_type || 'location' == $post_type ) {

        if ('expertise' == $post_type ) {
            $post_excerpt   = get_field( 'post_excerpt', $post_id ); // ACF field
        } elseif ( 'provider' == $post_type ) {
            $post_excerpt   = get_field( 'physician_short_clinical_bio', $post_id ); // ACF field
        } elseif ( 'location' == $post_type ) {
            $post_excerpt   = get_field( 'location_short_desc', $post_id ); // ACF field
        }

        if ( ( !empty( $post_id ) ) AND ( $post_excerpt ) ) {

            $post_array     = array(

                'ID'            => $post_id,
                'post_excerpt'	=> $post_excerpt

            );

            remove_action('save_post', 'custom_excerpt_acf', 50); // Unhook this function so it doesn't loop infinitely

            wp_update_post( $post_array );

            add_action( 'save_post', 'custom_excerpt_acf', 50); // Re-hook this function

        }
    
    }

}