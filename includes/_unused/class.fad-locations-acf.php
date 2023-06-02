<?php

/*
 * Meta Box Fields for UAMS-2016
 */

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_uamswp_locations',
		'title' => 'Location Information',
		'fields' => array(
			array(
				'key' => 'field_location_address_tab',
				'label' => '<i class="dashicons-location-alt dashicons"></i> Address',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_location_abbreviation',
				'label' => 'Abbreviation',
				'name' => 'location_abbreviation',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_description',
				'label' => 'Location Description',
				'name' => 'location_description',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_address_1',
				'label' => 'Address',
				'name' => 'location_address_1',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_address_2',
				'label' => 'Address (2)',
				'name' => 'location_address_2',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_city',
				'label' => 'City',
				'name' => 'location_city',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_state',
				'label' => 'State',
				'name' => 'location_state',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '30',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
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
				'default_value' => array(
					0 => 'AR',
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_location_zip',
				'label' => 'Zip',
				'name' => 'location_zip',
				'type' => 'maskfield',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '20',
					'class' => '',
					'id' => '',
				),
				'input_mask' => '99999[-999]',
				'default_value' => 72205,
				'placeholder' => 72205,
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_map',
				'label' => 'Map',
				'name' => 'location_map',
				'type' => 'google_map',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'center_lat' => '34.7492719',
				'center_lng' => '-92.3198281',
				'zoom' => 14,
				'height' => 400,
			),
			array(
				'key' => 'field_location_parking',
				'label' => 'Parking Instructions',
				'name' => 'location_parking',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_location_direction',
				'label' => 'Directions From Parking Area',
				'name' => 'location_direction',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_location_details_tab',
				'label' => '<i class="dashicons-location dashicons"></i> Location Details',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_location_about',
				'label' => 'About / Description',
				'name' => 'location_about',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_location_short_desc',
				'label' => 'Short Description (excerpt)',
				'name' => 'location_short_desc',
				'type' => 'textarea',
				'instructions' => 'Limit of 30 words. Preferred length is approx 18 words.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => 4,
				'new_lines' => '',
			),
			array(
				'key' => 'field_location_phone',
				'label' => 'Phone',
				'name' => 'location_phone',
				'type' => 'maskfield',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'input_mask' => '(999) 999-9999',
			),
			array(
				'key' => 'field_location_phone_numbers',
				'label' => 'Additional Phone Numbers',
				'name' => 'location_phone_numbers',
				'type' => 'repeater',
				'instructions' => 'Example: <br/>New Patients: ###-###-####',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_location_appointments_text',
				'min' => 1,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_location_appointments_text',
						'label' => 'Text',
						'name' => 'location_appointments_text',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
								'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_location_appointments_phone',
						'label' => 'Phone #',
						'name' => 'location_appointments_phone',
						'type' => 'maskfield',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'input_mask' => '(999) 999-9999',
					),
					array(
						'key' => 'field_location_appointments_additional_text',
						'label' => 'Additional Text',
						'name' => 'location_appointments_additional_text',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
			),
			array(
				'key' => 'field_location_appointment',
				'label' => 'Appointments Information',
				'name' => 'location_appointment',
				'type' => 'wysiwyg',
				'instructions' => 'Information on setting up an appointment',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_location_appointment_bring',
				'label' => 'What to Bring to Your Appointment',
				'name' => 'location_appointment_bring',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_portal',
				'label' => 'Portal (Optional)',
				'name' => 'location_portal',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '30',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'mychart' => 'Epic/MyChart',
					'fam_med' => 'Family Medical Centers portal',
					'' => 'None',
				),
				'default_value' => array(
					0 => '',
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_link_message',
				'label' => 'External Sites',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '<p>Include Title and link to external sites</p>',
				'new_lines' => '',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_location_web_name',
				'label' => 'Website Name',
				'name' => 'location_web_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_location_url',
				'label' => 'URL',
				'name' => 'location_url',
				'type' => 'link',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_details_deprecated_open',
				'label' => 'Deprecated',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_location_email',
				'label' => 'Email',
				'name' => 'location_email',
				'type' => 'maskfield',
				'instructions' => '[Deprecated]',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '60',
					'class' => '',
					'id' => '',
				),
				'input_mask' => 'email',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'acfe_validate' => array(
					'row-5d43116652d09' => array(
						'acfe_validate_rules_and' => array(
							'row-5d43116852d0a' => array(
								'acfe_validate_function' => 'email_exists',
								'acfe_validate_operator' => '==',
								'acfe_validate_match' => 'true',
							),
						),
						'acfe_validate_error' => '',
					),
				),
				'acfe_update' => '',
			),
			array(
				'key' => 'field_details_deprecated_end',
				'label' => 'Deprecated End',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 1,
			),
			array(
				'key' => 'field_location_hours_tab',
				'label' => '<i class="dashicons-clock dashicons"></i> Hours of Operation',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_hours_message',
				'label' => 'Hours of Operation',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '<h4>Hours of Operation</h4>
				<p>Set the time for each day or 24/7. Leave time blank for closed.</p>',
				'new_lines' => '',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_location_24_7',
				'label' => 'Open 24/7',
				'name' => 'location_24_7',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_location_hours',
				'label' => 'Typical Hours',
				'name' => 'location_hours',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_location_24_7',
							'operator' => '!=',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_hours_day',
				'min' => 1,
				'max' => 0,
				'layout' => 'table',
				'button_label' => 'Add Day / Time',
				'sub_fields' => array(
					array(
						'key' => 'field_hours_day',
						'label' => 'Day',
						'name' => 'day',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'Monday' => 'Monday',
							'Tuesday' => 'Tuesday',
							'Wednesday' => 'Wednesday',
							'Thursday' => 'Thursday',
							'Friday' => 'Friday',
							'Saturday' => 'Saturday',
							'Sunday' => 'Sunday',
							'Mon - Fri' => 'Mon - Fri',
						),
						'default_value' => array(
						),
						'allow_null' => 1,
						'multiple' => 0,
						'ui' => 0,
						'return_format' => 'value',
						'ajax' => 0,
						'placeholder' => '',
					),
					array(
						'key' => 'field_hours_closed',
						'label' => 'Closed',
						'name' => 'closed',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => 'Closed',
						'default_value' => 0,
						'ui' => 0,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array(
						'key' => 'field_hours_open',
						'label' => 'Open',
						'name' => 'open',
						'type' => 'time_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_location_24_7',
									'operator' => '!=',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'g:i a',
						'return_format' => 'g:i a',
					),
					array(
						'key' => 'field_hours_close',
						'label' => 'Close',
						'name' => 'close',
						'type' => 'time_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_location_24_7',
									'operator' => '!=',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'g:i a',
						'return_format' => 'g:i a',
					),
					array(
						'key' => 'field_hours_comment',
						'label' => 'Comment',
						'name' => 'comment',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_location_24_7',
									'operator' => '!=',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
			),
			array(
				'key' => 'field_after_hours',
				'label' => 'After Hours Information',
				'name' => 'location_after_hours',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 0,
				'delay' => 0,
			),
			array(
				'key' => 'field_location_holiday_hours',
				'label' => 'Holiday Hours',
				'name' => 'location_holiday_hours',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_holiday_day',
				'min' => 1,
				'max' => 0,
				'layout' => 'table',
				'button_label' => 'Add Day',
				'sub_fields' => array(
					array(
						'key' => 'field_holiday_date',
						'label' => 'Date',
						'name' => 'date',
						'type' => 'date_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'm/d/Y',
						'return_format' => 'm/d/Y',
						'first_day' => 0,
					),
					array(
						'key' => 'field_holiday_label',
						'label' => 'Holiday Text',
						'name' => 'label',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_holiday_closed',
						'label' => 'Closed',
						'name' => 'closed',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => 'Closed',
						'default_value' => 0,
						'ui' => 0,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array(
						'key' => 'field_holiday_open',
						'label' => 'Open',
						'name' => 'open',
						'type' => 'time_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'g:i a',
						'return_format' => 'g:i a',
					),
					array(
						'key' => 'field_holiday_close',
						'label' => 'Close',
						'name' => 'close',
						'type' => 'time_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'g:i a',
						'return_format' => 'g:i a',
					),
				),
			),
			array(
				'key' => 'field_location_medical_tab',
				'label' => '<i class="dashicons-heart dashicons"></i> Medical Info',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_location_medical_specialties',
				'label' => 'Medical Specialties Offered',
				'name' => 'location_medical_specialties',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'specialty',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 1,
				'load_terms' => 1,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_location_medical_terms',
				'label' => 'Medical Terms',
				'name' => 'location_medical_terms',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'medical_terms',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 1,
				'load_terms' => 1,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_info_depreciated_open',
				'label' => 'Deprecated',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_location_clinic',
				'label' => 'Clinic',
				'name' => 'location_clinic',
				'type' => 'true_false',
				'instructions' => '[Deprecated]',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_location_facility',
				'label' => 'Facility',
				'name' => 'location_facility',
				'type' => 'true_false',
				'instructions' => '[Deprecated]',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_info_depreciated_end',
				'label' => 'Deprecated End',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'locations',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'the_content',
			1 => 'excerpt',
			2 => 'discussion',
			3 => 'comments',
			4 => 'author',
			5 => 'format',
			6 => 'page_attributes',
			7 => 'categories',
			8 => 'tags',
			9 => 'send-trackbacks',
		),
		'active' => true,
		'description' => '',
	));

endif;
// add_action('acf/init', 'my_acf_add_locations_field_groups');


add_action('rwmb_locations_after_save_post', function( $post_id )
{

	$short_bio = $_POST['location_short_desc'];

	// Get the post ID
	$pid = get_the_ID();

	// Bring in variables from outside of the function
	global $wpdb; // WordPress-specific global variable

	$wpdb->update($wpdb->prefix."posts", array(
				'post_excerpt' => $short_bio,
			),
			array( 'id' => $pid )
		);
} );