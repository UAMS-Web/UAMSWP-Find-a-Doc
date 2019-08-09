<?php

/*
 *  Meta Box Fields for UAMS-2016
 */

//register_activation_hook( __FILE__, 'prefix_create_table' );

global $wpdb;
//$table_name = $wpdb->prefix.'uams_locations';
if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}uams_locations'") != "{$wpdb->prefix}uams_locations") {
  add_action( 'init', 'location_create_table' );
  function location_create_table() {

      global $wpdb;

      if ( ! class_exists( 'MB_Custom_Table_API' ) ) {
          return;
      }
      MB_Custom_Table_API::create( "{$wpdb->prefix}uams_locations", array(
          'location_abbreviation' => 'VARCHAR(25) NOT NULL',
          'location_address_1'   => 'VARCHAR(65) NOT NULL',
          'location_address_2'   => 'VARCHAR(85) NOT NULL',
          'location_city'   => 'VARCHAR(50) NOT NULL',
          'location_state'   => 'VARCHAR(2) NOT NULL',
          'location_zip'   => 'VARCHAR(10) NOT NULL',
          'location_map'   => 'TEXT NOT NULL',
          'location_short_desc' => 'VARCHAR(255) NOT NULL',
          'location_phone'   => 'VARCHAR(30) NOT NULL',
          'location_appointments'   => 'VARCHAR(255) NOT NULL',
          'location_fax'   => 'VARCHAR(30) NOT NULL',
          'location_email'   => 'VARCHAR(100) NOT NULL',
          'location_web_name'   => 'VARCHAR(85) NOT NULL',
          'location_url'   => 'VARCHAR(100) NOT NULL',
          'location_24_7'  => 'TINYINT(1) NOT NULL',
          'location_mon_open'  => 'VARCHAR(12) NOT NULL',
          'location_mon_close'  => 'VARCHAR(12) NOT NULL',
          'location_tues_open'  => 'VARCHAR(12) NOT NULL',
          'location_tues_close'  => 'VARCHAR(12) NOT NULL',
          'location_wed_open'  => 'VARCHAR(12) NOT NULL',
          'location_wed_close'  => 'VARCHAR(12) NOT NULL',
          'location_thurs_open'  => 'VARCHAR(12) NOT NULL',
          'location_thurs_close'  => 'VARCHAR(12) NOT NULL',
          'location_fri_open'  => 'VARCHAR(12) NOT NULL',
          'location_fri_close'  => 'VARCHAR(12) NOT NULL',
          'location_sat_open'  => 'VARCHAR(12) NOT NULL',
          'location_sat_close'  => 'VARCHAR(12) NOT NULL',
          'location_sun_open'  => 'VARCHAR(12) NOT NULL',
          'location_sun_close'  => 'VARCHAR(12) NOT NULL',
          'location_details'   => 'VARCHAR(100) NOT NULL',
          'location_parking'   => 'TEXT NOT NULL',
          'location_directions'   => 'TEXT NOT NULL',
          'location_clinic'  => 'TINYINT(1) NOT NULL',
          'location_facility'  => 'TINYINT(1) NOT NULL',
      ) );
  }
}

// if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}uams_services'") != "{$wpdb->prefix}uams_locations") {
//   add_action( 'init', 'location_create_table' );
//   function location_create_table() {

//       global $wpdb;

//       if ( ! class_exists( 'MB_Custom_Table_API' ) ) {
//           return;
//       }
//       MB_Custom_Table_API::create( "{$wpdb->prefix}uams_services", array(
//           'location_abbreviation' => 'VARCHAR(25) NOT NULL',
//           'location_address_1'   => 'VARCHAR(50) NOT NULL',
//           'location_address_2'   => 'VARCHAR(65) NOT NULL',
//           'location_city'   => 'VARCHAR(50) NOT NULL',
//           'location_state'   => 'VARCHAR(2) NOT NULL',
//           'location_zip'   => 'VARCHAR(10) NOT NULL',
//           'location_map'   => 'TEXT NOT NULL',
//           'location_phone'   => 'VARCHAR(30) NOT NULL',
//           'location_appointments'   => 'VARCHAR(255) NOT NULL',
//           'location_fax'   => 'VARCHAR(30) NOT NULL',
//           'location_email'   => 'VARCHAR(100) NOT NULL',
//           'location_web_name'   => 'VARCHAR(50) NOT NULL',
//           'location_url'   => 'VARCHAR(100) NOT NULL',
//           'location_24_7'  => 'TINYINT(1) NOT NULL',
//           'location_mon_open'  => 'VARCHAR(12) NOT NULL',
//           'location_mon_close'  => 'VARCHAR(12) NOT NULL',
//           'location_tues_open'  => 'VARCHAR(12) NOT NULL',
//           'location_tues_close'  => 'VARCHAR(12) NOT NULL',
//           'location_wed_open'  => 'VARCHAR(12) NOT NULL',
//           'location_wed_close'  => 'VARCHAR(12) NOT NULL',
//           'location_thurs_open'  => 'VARCHAR(12) NOT NULL',
//           'location_thurs_close'  => 'VARCHAR(12) NOT NULL',
//           'location_fri_open'  => 'VARCHAR(12) NOT NULL',
//           'location_fri_close'  => 'VARCHAR(12) NOT NULL',
//           'location_sat_open'  => 'VARCHAR(12) NOT NULL',
//           'location_sat_close'  => 'VARCHAR(12) NOT NULL',
//           'location_sun_open'  => 'VARCHAR(12) NOT NULL',
//           'location_sun_close'  => 'VARCHAR(12) NOT NULL',
//           'location_details'   => 'VARCHAR(100) NOT NULL',
//           'location_parking'   => 'TEXT NOT NULL',
//           'location_directions'   => 'TEXT NOT NULL',
//       ) );
//   }
// }

add_filter( 'rwmb_meta_boxes', 'uams_locations_register_meta_boxes' );

function uams_locations_register_meta_boxes( $meta_boxes ) {

    global $wpdb;

    $location_excerpt = '';
    // Get the current post content and set as the default value for the wysiwyg field.
    $post_id         = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
    if ( ! $post_id ) {
        $post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );
    }
    if ( $post_id ) {
        $location_excerpt = get_post_field( 'post_excerpt', $post_id );
    }

    $meta_boxes[] = array (
      'id' => 'locations',
      'title' => 'Location Information',
      'post_types' =>   array (
         'locations',
      ),
       'storage_type' => 'custom_table',    // Important
       'table' => "{$wpdb->prefix}uams_locations", // Your custom table name
      'context' => 'after_title',
      'priority' => 'high',
      'autosave' => true,
      'tabs' =>   array (
        'tab_address' =>     array (
          'label' => 'Address',
          'icon' => 'dashicons-location-alt',
        ),
        'tab_location_details' =>     array (
          'label' => 'Location Details',
          'icon' => 'dashicons-location',
        ),
        'tab_location_hours' =>     array (
          'label' => 'Hours of Operation',
          'icon' => 'dashicons-clock',
        ),
        'tab_location_medical' =>     array (
          'label' => 'Medical Info',
          'icon' => 'dashicons-heart',
        ),
      ),
      'fields' =>   array (

        array (
          'id' => 'location_abbreviation',
          'type' => 'text',
          'name' => 'Abbreviation',
          'tab' => 'tab_address',
          'columns'    => 12,
        ),

        array (
          'id' => 'location_description',
          'type' => 'text',
          'name' => 'Location Description',
          'tab' => 'tab_address',
          'columns'    => 12,
        ),

        array (
          'id' => 'location_address_1',
          'type' => 'text',
          'name' => 'Address',
          'tab' => 'tab_address',
          'columns'    => 12,
        ),

        array (
          'id' => 'location_address_2',
          'type' => 'text',
          'name' => 'Address (2)',
          'tab' => 'tab_address',
          'columns'    => 12,
        ),

        array (
          'id' => 'location_city',
          'type' => 'text',
          'name' => 'City',
          'tab' => 'tab_address',
          'columns'    => 12,
        ),

        array (
          'id' => 'location_state',
          'name' => 'State',
          'tab' => 'tab_address',
          'type' => 'select',
          'columns'    => 12,
          'placeholder' => 'Select an Item',
          'options' =>       array (
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
          ),
          'std' =>       array (
             'AR',
          ),
        ),

        array (
          'id' => 'location_zip',
          'type' => 'text',
          'name' => 'Zip',
          'placeholder' => '72205',
          'size' => '30',
          'columns'    => 12,
          'tab' => 'tab_address',
        ),

        array (
          'id' => 'location_map',
          'type' => 'osm',
          'name' => 'Map',
          'std' => '34.7492719,-92.3198281,14',
          'address_field' => 'location_address_1,location_city,location_state,location_zip',
          'columns'    => 12,
          'tab' => 'tab_address',
        ),

        array (
          'id' => 'location_parking',
          'type' => 'wysiwyg',
          'name' => 'Parking Instructions',
          'tab' => 'tab_address',
          'columns'    => 12,
          'options' => array(
            'textarea_rows' => 3,
            'media_buttons' => false,
            'teeny'         => true,
          ),
        ),

        array (
          'id' => 'location_direction',
          'type' => 'wysiwyg',
          'name' => 'Directions (Written)',
          'tab' => 'tab_address',
          'columns'    => 12,
          'options' => array(
            'textarea_rows' => 3,
            'media_buttons' => false,
            'teeny'         => true,
          ),
        ),

/*
        array (
          'type' => 'custom_html',
          'std' => '<style>#postexcerpt{display:none;}</style>' . ($location_excerpt ? '<div class="rwmb-label"><label>Short Description: </label></div><div class="rwmb-input">' . $location_excerpt . '</div>' : ''),
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),
*/

        array (
          'id' => 'location_short_desc',
          'type' => 'textarea',
          'name' => 'Short Description (excerpt)',
          'label_description' => 'Limit of 30 words. Preferred length is approx 18 words.',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array (
          'id' => 'location_phone',
          'type' => 'text',
          'name' => 'Phone',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array (
          'id' => 'location_fax',
          'type' => 'text',
          'name' => 'Fax',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array (
          'id' => 'location_appointments',
          'type' => 'fieldset_text',
          'name' => 'Additional Phone Numbers',
          'label_description' => 'Example: <br/>New Patients: ###-###-####  ',
          'columns'    => 12,
          'tab' => 'tab_location_details',
          'options' => array(
            'text'  => 'Text',
            'number'  => 'Phone #',
            'after'  => 'Additional Text',
          ),
          'clone'  => true,
        ),

        array (
          'id' => 'location_email',
          'name' => 'Email',
          'type' => 'email',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array (
          'id' => 'location_web_name',
          'type' => 'text',
          'name' => 'Website Name',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array (
          'id' => 'location_url',
          'type' => 'url',
          'name' => 'URL',
          'columns'    => 12,
          'tab' => 'tab_location_details',
        ),

        array(
          'type' => 'heading',
          'name' => 'Hours of Operation',
          'desc' => 'Set the time for each day or 24/7. Leave time blank for closed.',
          'columns'    => 12,
          'tab' => 'tab_location_hours',
        ),

        array(
          'name' => 'Open 24/7',
          'id'   => 'location_24_7',
          'type' => 'switch',
          'std'  => false, // 0 or 1
          'columns'    => 12,
          'tab' => 'tab_location_hours',
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '&nbsp;',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Open</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Close</h4>',
          'columns' => 6,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Sunday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '!=', '' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_sun_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
              'stepMinute'      => 15,
              'timeFormat'      => 'h:mm tt',
              'showButtonPanel' => true,
              'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_sun_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'class'  => 'inline',
          'js_options' => array(
              'stepMinute'      => 15,
              'timeFormat'      => 'h:mm tt',
              'showButtonPanel' => true,
              'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Monday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_mon_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_mon_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Tuesday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_tues_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_tues_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Wednesday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_wed_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_wed_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Thursday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_thurs_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_thurs_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Friday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_fri_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_fri_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'type' => 'custom_html',
          // HTML content
          'std'  => '<h4>Saturday Hours</h4>',
          'columns' => 3,
          'tab' => 'tab_location_hours',
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_sat_open',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 3,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array(
          'name'       => ' ',
          'id'         => 'location_sat_close',
          'type'       => 'time',
          'size'       => 8,
          'columns'    => 6,
          'tab'        => 'tab_location_hours',
          'js_options' => array(
            'stepMinute'      => 15,
            'timeFormat'      => 'h:mm tt',
            'showButtonPanel' => true,
            'oneLine'         => true,
          ),
          'inline'     => false,
          'hidden' => array( 'location_24_7', '=', '1' ),
        ),

        array (
          'id' => 'location_medical_specialties',
          'type' => 'taxonomy',
          'name' => 'Medical Specialties Offered',
          'tab' => 'tab_location_medical',
          'taxonomy' => 'specialty',
          'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'columns'    => 12,
          'js_options'      => array(
            'width' => '100%',
          ),
        ),
        array (
          'id' => 'location_medical_terms',
          'type' => 'taxonomy',
          'name' => 'Medical Terms',
          'tab' => 'tab_location_medical',
          'taxonomy' => 'medical_terms',
          'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'columns'    => 12,
          'js_options'      => array(
            'width' => '100%',
          ),
        ),
        array(
          'name' => 'Clinic',
          'id'   => 'location_clinic',
          'type' => 'switch',
          'std'  => 0, // 0 or 1
          'columns'    => 6,
          'tab' => 'tab_location_medical',
        ),
        array(
          'name' => 'Facility',
          'id'   => 'location_facility',
          'type' => 'switch',
          'std'  => 0, // 0 or 1
          'columns'    => 6,
          'tab' => 'tab_location_medical',
        ),
      ),
      'validation' => array(
		    'rules'  => array(
		        'location_address_1' => array(
		            'required'  => true,
		        ),
		        'location_city' => array(
		            'required'  => true,
		        ),
		        'location_state' => array(
		            'required'  => true,
		        ),
		        'location_zip' => array(
		            'required'  => true,
		            'zipcodeUS' => true,
		            'maxlength' => 10,
          			'minlength' => 5,
		        ),
		        'location_map' => array(
		            'required'  => true,
		        ),
		        'location_phone' => array(
		            'required'  => true,
		            'phoneUS' => true,
		        ),
		    ),
		    // Optional override of default error messages
		    // 'messages' => array(
		    //     'field_id' => array(
		    //         'required'  => 'Password is required',
		    //         'minlength' => 'Password must be at least 7 characters',
		    //     ),
		    // )
		  ),
    );

    return $meta_boxes;

}

add_action('rwmb_locations_after_save_post', function( $post_id )
{

  $short_bio = $_POST['location_short_desc'];

  // Get the post ID
  $pid = get_the_ID();

  global $wpdb;
   $wpdb->update($wpdb->prefix."posts", array(
	   		'post_excerpt' => $short_bio,
   		),
   		array( 'id' => $pid )
   	);
} );