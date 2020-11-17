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

add_filter('acf/load_value/key=field_location_region', 'set_default_region', 20, 3);
function set_default_region($value, $post_id, $field) {
    // Only add default content for empty fields
    if ( $value !== null ) {
        return $value;
    }
    
    $term = get_term_by('slug', 'central', 'region');
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

// Fires before saving data to post - only updates ACF data
add_action('acf/save_post', 'physician_save_post', 5); 
function physician_save_post( $post_id ) {
	$post_type = get_post_type($post_id);

	// Bail early if no data sent.
	if( empty($_POST['acf']) || ($post_type != 'provider')) {
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

	$expertises = $_POST['acf']['field_physician_expertise'];
	$conditions = $_POST['acf']['field_physician_conditions_cpt'];
	$treatments = $_POST['acf']['field_physician_treatments_cpt'];

	
	if ( $expertises ) {
		$i = 1;
		foreach( $expertises as $expertise ):
			$expertise_name = get_the_title( $expertise );
			$expertise_list .= $expertise_name;
			if( count($expertises) > $i ) {
				$expertise_list .= ", ";
			}
			$i++;
		endforeach;
	}

	if ( $conditions ) {
		$i = 1;
		foreach( $conditions as $condition ):
			$condition_name = get_the_title( $condition );
			$condition_list .= $condition_name;
			if( count($conditions) > $i ) {
				$condition_list .= ", ";
			}
			$i++;
		endforeach;
	}

	if ( $treatments ) {
		$i = 1;
		foreach( $treatments as $treatment ):
			$treatment_name = get_the_title( $treatment );
			$treatment_list .= $treatment_name;
			if( count($treatments) > $i ) {
				$treatment_list .= ", ";
			}
			$i++;
		endforeach;
	}

	$filter_list = $expertise_list . ', ' . $condition_list . ', ' . $treatment_list;
	$_POST['acf']['field_physician_asp_filter'] = $filter_list;

	// Add region
	$locations = $_POST['acf']['field_physician_locations'];
	if ( $locations ) {
		foreach( $locations as $location ):
			$region = get_field( 'location_region', $location);
			$portal = get_field( 'location_portal', $location);
			// break loop after first iteration = primary location
			break; 
		endforeach;
	}

	$_POST['acf']['field_physician_region'] = $region;
	$_POST['acf']['field_physician_portal'] = $portal;

}

add_action( 'acf/save_post', 'update_facetwp_index');
function update_facetwp_index( $post_id ) {
    if ( function_exists( 'FWP' ) ) {
        FWP()->indexer->index( $post_id );
    }
}

// Fires before saving data to post - only updates ACF data
add_action('acf/save_post', 'location_save_post', 7); 
function location_save_post( $post_id ) {
	$post_type = get_post_type($post_id);

	// Bail early if no data sent or not location post type
	if( empty($_POST['acf']) || ($post_type != 'location') ) {
		return;
	}

	// Create full name to store in 'physician_full_name' field
	$featured_image = $_POST['acf']['field_location_featured_image'];
	$wayfinding_image = $_POST['acf']['field_location_wayfinding_photo'];

	// If featured image is set & wayfinding is empty, set wayfinding image to featured image
	if ($featured_image && empty($wayfinding_image)) {
		$_POST['acf']['field_location_wayfinding_photo'] = $featured_image;
	}
	// If wayfinding image is set & featured image is empty, set featured image to wayfinding image
	if (empty($featured_image) && $wayfinding_image) {
		$_POST['acf']['field_location_featured_image'] = $wayfinding_image;
	}

}
// Fires after saving data to post - change post data 
add_action('acf/save_post', 'location_save_post_after', 20);
function location_save_post_after( $post_id ) {
	$post_type = get_post_type($post_id);
	if ($post_type != 'location') {
		return;
	}
	$post = get_post($post_id);
	$location_has_parent = get_field('location_parent');
	$location_parent_id = get_field('location_parent_id');
	
	// If location has parent & parent id set, set parent id
	if ($location_has_parent && $location_parent_id) {
		$post->post_parent = $location_parent_id;
	} else { // clear the parent data
		$post->post_parent = 0;
	}
	// remove this filter to prevent infinite loop
	remove_filter('acf/save_post', 'save_location_parent');
	wp_update_post($post);

}

add_action('acf/save_post', 'uamswp_sync_acf_save_post', 5);
function uamswp_sync_acf_save_post( $post_id ) {
	// Setup the variables
	$post_type = get_post_type( $post_id );
	$values = $_POST['acf'];

	if ('location' == $post_type ) {
		$field_name = 'physician_locations';
		$field_key = 'field_location_physicians';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

		$field_name = 'location_expertise';
		$field_key = 'field_location_expertise';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );
	}

	if ('provider' == $post_type ) {
		$field_name = 'physician_locations';
		$field_key = 'field_physician_locations';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

		$field_name = 'physician_expertise';
		$field_key = 'field_physician_expertise';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );
	}

	if ('expertise' == $post_type ) {
		$field_name = 'location_expertise';
		$field_key = 'field_expertise_locations';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

		$field_name = 'physician_expertise';
		$field_key = 'field_expertise_physicians';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

		$field_name = 'expertise_associated';
		$field_key = 'field_expertise_associated';
		// Get submitted values.
		$value = $values[$field_key];
		bidirectional_acf_update( $field_name, $field_key, $value, $post_id );
	}
}
/*
 * Function for Bidirectional ACF
 * Req:
 * $field_name = ACF field name
 * $field_key = ACF field key of field with new value
 * $value = incoming/new value
 * $post_id = $post_id being updated
 *
 */
function bidirectional_acf_update( $field_name, $field_key, $value, $post_id ){
	// Get previous values.
	$old_value = get_field($field_name, $post_id, false);

	if( isset($value) && is_array($value) ) {

		foreach( $value as $post_id2new ) {
			// load existing related posts
			$value2new = get_field($field_name, $post_id2new, false);

			// allow for selected posts to not contain a value
			if( empty($value2new) ) {
				$value2new = array();
			}
			// write_log('New Values for ' . $post_id2new . ': '. print_r($value2new, true));
			// bail early if the current $post_id is already found in selected post's $value2new
			if( in_array($post_id, $value2new) ) continue;

			// append the current $post_id to the selected post's 'related_posts' value
			$value2new[] = $post_id;

			// update the selected post's value (use field's key for performance)
			update_field($field_key, $value2new, $post_id2new);
		}
	}

	if( isset($old_value) && is_array($old_value) ) {

		foreach( $old_value as $post_id2old ) {
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2old, $value) ) continue;

			// load existing related posts
			$value2old = get_field($field_name, $post_id2old, false);

			// bail early if no value
			if( empty($value2old) ) continue;

			// find the position of $post_id within $value2old so we can remove it
			$pos = array_search($post_id, $value2old);

			// remove
			unset( $value2old[$pos] );

			// update the un-selected post's value (use field's key for performance)
			update_field($field_key, $value2old, $post_id2old);
		}
	}

}

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

// Custom Blocks
if( function_exists('acf_register_block_type') ):

	acf_register_block_type(array(
		'name' => 'uamswp_fad_facetwp_cards',
		'title' => 'FacetWP Cards',
		'description' => '',
		'category' => 'common',
		'keywords' => array(
			0 => 'provider',
			1 => 'location',
			2 => 'facetwp',
		),
		'mode' => 'auto',
		'align' => '',
		'render_template' => '',
		'render_callback' => 'fad_facetwp_cards_callback',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => 'id',
		'supports' => array(
			'align' => array('full'),
			'mode' => true,
			'multiple' => true,
		),
	));
	acf_register_block_type(array(
		'name' => 'uamswp_fad_facetwp_blocks',
		'title' => 'FacetWP Block',
		'description' => '',
		'category' => 'common',
		'keywords' => array(
			0 => 'facetwp',
			1 => 'shortcode',
		),
		'mode' => 'auto',
		'align' => '',
		'render_template' => '',
		'render_callback' => 'fad_facetwp_blocks_callback',
		'enqueue_style' => '',
		'enqueue_script' => '',
		'enqueue_assets' => '',
		'icon' => 'list-view',
		'supports' => array(
			'align' => true,
			'mode' => true,
			'multiple' => true,
		),
	));
	
endif;


/**
 * FacetWP Cards Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function fad_facetwp_cards_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

    // Create id attribute allowing for custom "anchor" value.
    $id = 'facetwp-cards-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'facetwp-cards';
    if( !empty($block['className']) ) {
        $className .= ' ' . $block['className'];
    }
    if( !empty($block['align']) ) {
        $className .= ' align' . $block['align'];
    }

    // Load values and assing defaults.
    $heading = get_field('facetwp_heading') ?: 'Cards List';
    $template = get_field('facetwp_template_name');
    $background_color = get_field('facetwp_background_color') ?: 'bg-white';

    ?>
	<section class="uams-module container-fluid p-8 p-sm-10 <?php echo $className; ?> <?php echo $background_color; ?>">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title"><?php echo $heading; ?></h2>
				<div class="card-list-container">
					<?php echo facetwp_display( 'template', $template ); ?>
				</div>
			</div>
		</div>
	</section>
    <?php
}

/**
 * FacetWP Cards Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function fad_facetwp_blocks_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

    // Create id attribute allowing for custom "anchor" value.
    $id = 'facetwp-block-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'facetwp-blocks';
    if( !empty($block['className']) ) {
        $className .= ' ' . $block['className'];
    }
    if( !empty($block['align']) ) {
        $className .= ' align' . $block['align'];
    }

    // Load values and assing defaults.
	$heading = get_field('facetwp_block_heading') ?: 'Cards List';
	$hideheading = get_field('facetwp_block_hide_heading');
	$prefacets = get_field('facetwp_block_pre_template_facets');
	$template = get_field('facetwp_block_facet_template');
	$postfacets = get_field('facetwp_block_post_template_facets');
	$pager = get_field('facetwp_block_include_pager');
    $background_color = get_field('facetwp_block_background_color') ?: 'bg-white';

    ?>
	<section class="uams-module <?php echo $className; ?> <?php echo $background_color; ?>">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo ('1' == $hideheading ? ' sr-only': ''); ?>" ><?php echo $heading; ?></h2>
					<div class="">
						<?php 
						if ($prefacets) {
							foreach ($prefacets as $prefacet) {
								echo '<div class="text-'. $prefacet['alignment'] .'">'. facetwp_display( 'facet', $prefacet['facet_name'] ) .'</div>';
							}
						} 
						?>
						<?php echo facetwp_display( 'template', $template ); ?>
						<?php 
						if ($postfacets) {
							foreach ($postfacets as $postfacet) {
								echo '<div class="text-'. $postfacet['alignment'] .'">'. facetwp_display( 'facet', $postfacet['facet_name'] ) .'</div>';
							}
						} 
						echo ($pager ? facetwp_display( 'pager' ) : '');
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
    <?php
}
add_action('acf/render_field/name=location_current_alert', 'location_current_alert_message');
function location_current_alert_message(){

	$alert_title = get_field('location_alert_heading_system', 'option');
	$alert_body = get_field('location_alert_body_system', 'option');
	$alert_color = get_field('location_alert_color_system', 'option');


	if (!empty($alert_title) && !empty($alert_body)) {

		$alert_txt = '<blockquote class="notice notice-warning">';
		$alert_txt .=  '<h3 class="notice-title">'. $alert_title .'</h3>';
		$alert_txt .= $alert_body;
		$alert_txt .= '<hr />';
		$alert_txt .= '<p><strong>Alert color:</strong> '. ucfirst(str_replace( 'alert-', '', $alert_color)) .'</p>';
		$alert_txt .= '</blockquote>';

		echo $alert_txt;

	} else {
		echo 'None active';
	}  
    
}
add_action('acf/render_field/name=location_current_prescription_clinic', 'location_current_prescription_clinic_message');
function location_current_prescription_clinic_message(){

	$prescription_clinic_sys = get_field('location_prescription_clinic_system', 'option');


	if (!empty($prescription_clinic_sys)) {

		$prescr_txt = '<blockquote class="notice notice-info">';
		$prescr_txt .= $prescription_clinic_sys;
		$prescr_txt .= '</blockquote>';

		echo $prescr_txt;

	} else {
		echo 'None active';
	}  
    
}
add_action('acf/render_field/name=location_current_prescription_pharm', 'location_current_prescription_pharm_message');
function location_current_prescription_pharm_message(){

	$prescription_pharm_sys = get_field('location_prescription_pharm_system', 'option');


	if (!empty($prescription_pharm_sys)) {

		$prescr_txt = '<blockquote class="notice notice-info">';
		$prescr_txt .= $prescription_pharm_sys;
		$prescr_txt .= '</blockquote>';

		echo $prescr_txt;

	} else {
		echo 'None active';
	}  
    
}

add_filter('acf/fields/post_object/query/key=field_location_parent_id', 'limit_post_top_level', 10, 3);

function limit_post_top_level( $args, $field, $post ) {

    $args['post_parent'] = 0;
    // $args['sort_order'] = 'ASC';
    // $args['orderby'] = 'title';
    // $args['order'] = 'ASC';
    $args['post_status'] = 'publish';

    return $args;
}