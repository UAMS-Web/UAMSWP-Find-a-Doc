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