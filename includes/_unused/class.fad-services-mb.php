<?php

/*
 *  Meta Box Fields for UAMS-2016
 */

//register_activation_hook( __FILE__, 'prefix_create_table' );

global $wpdb;
add_filter( 'rwmb_meta_boxes', 'uams_services_register_meta_boxes' );

function uams_services_register_meta_boxes( $meta_boxes ) {

    global $wpdb;
    $post_id         = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
    if ( ! $post_id ) {
        $post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );
    }
    
    if ( $post_id ) {
        $default_content = get_post_field( 'excerpt', $post_id );
    }

    $meta_boxes[] =  [
      'id' => 'services',
      'title' => 'Medical Service Information',
      'post_types' =>    [
         'services',
      ],
      // 'storage_type' => 'custom_table',    // Important
      // 'table' => "{$wpdb->prefix}uams_services", // Your custom table name
      'context' => 'normal',
      'priority' => 'high',
      'autosave' => true,
      'fields' =>    [
         [
          'id' => 'service_lines',
          'type' => 'taxonomy',
          'name' => 'Service Line',
          'desc' => 'Is this part of a service line?',
          'taxonomy' => 'service-line',
          //'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => [
            'width' => '100%',
          ],
        ],
         [
          'id' => 'medical_specialties',
          'type' => 'taxonomy',
          'name' => 'Medical Specialties Offered',
          'taxonomy' => 'specialty',
          //'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => [
            'width' => '100%',
          ],
          'hidden' => [ 'parent_id', '!=', '' ],
        ],
         [
          'id' => 'medical_terms',
          'type' => 'taxonomy',
          'name' => 'Medical Terms (Tags)',
          'taxonomy' => 'medical_terms',
          //'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => [
            'width' => '100%',
          ],
          'hidden' => [ 'parent_id', '!=', '' ],
        ],
         [
          'id' => 'excerpt', // This is the must! Replace default Excerpt
          'name' =>'Short Description',
          'label_description' => 'Recommended between 180-320 characters',
          'type' => 'textarea',
          'desc' => 'Short description used for lists',
        ],
        [
          'id'   => 'action_bar_active',
          'name' => 'Action Bar',
          'type' => 'checkbox',
          'std'  => 0, // 0 or 1
          'label_description' => 'Add Action Bar to the page',
          'desc' => 'Show "Action Bar" Menu',
        ],
        [
          'id'     => 'action_menu',
          'name'   => 'Action Menu Items',
          'type'   => 'group',
          'collapsible' => true,
          'clone'  => true,
          'sort_clone'    => true,
          'max_clone' => 6,
          'group_title'   => 'Action Item',
          'hidden' => [ 'action_bar_active', '!=', true ],
          // List of sub-fields
          'fields' => [
              [
                  'name' => 'Link Title',
                  'id'   => 'action_link_title',
                  'type' => 'text',
              ],
              [
                  'name' => 'Link Icon',
                  'id'   => 'action_link_icon',
                  'type' => 'text',
              ],
              [
                'name' => 'URL',
                'id'   => 'action_link_url',
                'type' => 'text',
              ],
          ],
      ],
         [
          'type' => 'custom_html',
          'std' => '<style>#postexcerpt{display:none;}</style>',
        ],
      ],
      // 'validation' => array(
		  //   'rules'  => array(
		  //       'location_address_1' => array(
		  //           'required'  => true,
		  //       ),
		  //   ),
		    // Optional override of default error messages
		    // 'messages' => array(
		    //     'field_id' => array(
		    //         'required'  => 'Password is required',
		    //         'minlength' => 'Password must be at least 7 characters',
		    //     ),
		    // )
		  // ),
    ];

    $meta_boxes[] = [
      'title'      => 'Images',
      'post_types' => 'services',
      'context'    => 'side',
      'priority'   => 'low',
      'fields' => [
          [
              'name' => 'Featured Image',
              'id'   => '_thumbnail_id', // This is the must! Replace default Featured Image
              'label_description' => 'Recommended size 720 x 480 px',
              'desc' => 'Used to display with on list pages',
              'type' => 'image_advanced',
              'max_file_uploads' => 1,
              'max_status' => false,
          ],
          [
            'name' => 'Header Image',
            'id'   => 'service_header_image',
            'label_description' => 'Recommended size 1600 x 450 px',
            'desc' => 'Hero image on service page',
            'type' => 'image_advanced',
            'max_file_uploads' => 1,
            'max_status' => false,
          ],
          [
            'name' => 'Header Mobile Image',
            'id'   => 'service_header_mobile_image',
            'label_description' => 'Recommended size 750 x 450 px',
            'desc' => 'Mobile Hero image on service page',
            'type' => 'image_advanced',
            'max_file_uploads' => 1,
            'max_status' => false,
          ],
          [
            'id'   => 'header_dark_text',
            'name' => 'Dark Header Text',
            'type' => 'checkbox',
            'std'  => 0, // 0 or 1
            'desc' => 'Dark Text',
            'label_description' => 'Use dark text for a light colored background',
            'hidden' =>  [ 'service_header_image', 0 ],
          ],
           [
            'type' => 'custom_html',
            'std' => '<style>#postimagediv{display:none;}</style>',
          ],
      ],
    ];

    return $meta_boxes;

}