<?php

/*
 * Meta Box Fields for UAMS-2016
 */


if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_uamswp_physicians',
		'title' => 'Physicians',
		'fields' => array(
			array(
				'key' => 'field_physician_details_tab',
				'label' => '<i class="dashicons dashicons-admin-users"></i> Details',
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
				'key' => 'field_physician_first_name',
				'label' => 'First Name',
				'name' => 'physician_first_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
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
				'key' => 'field_physician_middle_name',
				'label' => 'Middle Name',
				'name' => 'physician_middle_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '15',
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
				'key' => 'field_physician_last_name',
				'label' => 'Last Name',
				'name' => 'physician_last_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
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
				'key' => 'field_physician_pedigree',
				'label' => 'Pedigree',
				'name' => 'physician_pedigree',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '10',
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
				'key' => 'field_physician_degree',
				'label' => 'Degree(s)',
				'name' => 'physician_degree',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'degree',
				'field_type' => 'multi_select',
				'add_term' => 1,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'multiple' => 1,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_prefix',
				'label' => 'Prefix',
				'name' => 'physician_prefix',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
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
				'key' => 'field_physician_gender',
				'label' => 'Gender',
				'name' => 'physician_gender',
				'type' => 'radio',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'male' => 'Male',
					'female' => 'Female',
					'other' => 'Other',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'default_value' => '',
				'layout' => 'vertical',
				'return_format' => 'value',
				'save_other_choice' => 0,
			),
			array(
				'key' => 'field_physician_regional',
				'label' => 'Regional Campus',
				'name' => 'physician_regional',
				'type' => 'true_false',
				'instructions' => 'Excluded from main campus results',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'message' => 'Regional Campus only',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_physician_full_name',
				'label' => 'Full Name',
				'name' => 'physician_full_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_physician_details_tab',
							'operator' => '==',
							'value' => 'false',
						),
					),
				),
				'wrapper' => array(
					'width' => '25',
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
				'key' => 'field_physician_clinical_tab',
				'label' => '<i class="dashicons dashicons-id-alt"></i> Clinical Profile',
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
				'key' => 'field_physician_title',
				'label' => 'Clinical Job Title',
				'name' => 'physician_title',
				'type' => 'taxonomy',
				'instructions' => 'General Title for Patients / Public',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'clinical_title',
				'field_type' => 'select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_physician_department',
				'label' => 'Medical Department',
				'name' => 'physician_department',
				'type' => 'taxonomy',
				'instructions' => '&nbsp;',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'department',
				'field_type' => 'select',
				'add_term' => 1,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_clinical_bio',
				'label' => 'Clinical Bio',
				'name' => 'physician_clinical_bio',
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
				'key' => 'field_physician_short_clinical_bio',
				'label' => 'Short Bio',
				'name' => 'physician_short_clinical_bio',
				'type' => 'textarea',
				'instructions' => 'Limit of 30 words. Preferred length is approx 18 words.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => 176,
				'rows' => 4,
				'new_lines' => '',
			),
			array(
				'key' => 'field_physician_youtube_link',
				'label' => 'Featured Video',
				'name' => 'physician_youtube_link',
				'type' => 'url',
				'instructions' => 'Select a video from the UAMS YouTube or Vimeo accounts that features this provider and that supports the Patient-focused Clinical Biography. Include the full URL, including https://',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
			array(
				'key' => 'field_physician_languages',
				'label' => 'Language(s)',
				'name' => 'physician_languages',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => array (
					0 => 'English',
				),
				'taxonomy' => 'languages',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array (
				'key' => 'field_physician_locations',
				'label' => 'Locations',
				'name' => 'physician_locations',
				'type' => 'relationship',
				'instructions' => 'Please put primary location first',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'locations',
				),
				'taxonomy' => array (
				),
				'filters' => array (
					0 => 'search',
				),
				'elements' => '',
				'min' => 1,
				'max' => '',
				'return_format' => 'object',
			),
			array(
				'key' => 'field_physician_affiliation',
				'label' => 'Affiliation',
				'name' => 'physician_affiliation',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'affiliations',
				'field_type' => 'checkbox',
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_clinical_details_tab',
				'label' => '<i class="dashicons dashicons-forms"></i> Clinical Details',
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
				'key' => 'field_physician_appointment_link',
				'label' => 'Appointment Link',
				'name' => 'physician_appointment_link',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'https://uamshealth.com/appointments',
				'placeholder' => 'https://uamshealth.com/appointments',
			),
			array(
				'key' => 'field_physician_patient_types',
				'label' => 'Patient Type(s)',
				'name' => 'physician_patient_types',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'patient_type',
				'field_type' => 'checkbox',
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_searchable',
				'label' => 'Searchable',
				'name' => 'physician_searchable',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Display in search results',
				'default_value' => 1,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_physician_primary_care',
				'label' => 'Primary Care Physician',
				'name' => 'physician_primary_care',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hide-acf-title',
					'id' => '',
				),
				'message' => 'Primary Care Physician?',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_physician_referral_required',
				'label' => 'Referral required for new patients',
				'name' => 'physician_referral_required',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hide-acf-title',
					'id' => '',
				),
				'message' => 'Referral required for new patients',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_physician_accepting_patients',
				'label' => 'Accepting new patients',
				'name' => 'physician_accepting_patients',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'hide-acf-title',
					'id' => '',
				),
				'message' => 'Currently accepting new patients',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_physician_second_opinion',
				'label' => 'Provides second opinion',
				'name' => 'physician_second_opinion',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => 'hide-acf-title',
					'id' => '',
				),
				'message' => 'Provides second opinion',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_medical_specialties',
				'label' => 'Medical Specialties',
				'name' => 'physician_medical_specialties',
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
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_physician_conditions',
				'label' => 'Conditions Treated',
				'name' => 'physician_conditions',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'condition',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_physician_treatments',
				'label' => 'Treatments Treated',
				'name' => 'physician_treatments',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'treatment',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_medical_terms',
				'label' => 'Medical Terms (Tags)',
				'name' => 'physician_medical_terms',
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
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_physician_pid',
				'label' => 'PID',
				'name' => 'physician_pid',
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
				'key' => 'field_physician_npi',
				'label' => 'NPI',
				'name' => 'physician_npi',
				'type' => 'text',
				'instructions' => '',
				'required' => 1,
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
				'key' => 'field_physician_academic_profile_tab',
				'label' => '<i class="dashicons dashicons-edit"></i> Academic Profile',
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
				'key' => 'field_profile_message',
				'label' => 'PROFILE INFORMATION',
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
				'message' => 'This information is designed for department and public websites.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_physician_academic_title',
				'label' => 'Academic Title',
				'name' => 'physician_academic_title',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '51',
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
				'key' => 'field_physician_academic_college',
				'label' => 'College Affiliation',
				'name' => 'physician_academic_college',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'academic_colleges',
				'field_type' => 'checkbox',
				'add_term' => 1,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_academic_position',
				'label' => 'Position',
				'name' => 'physician_academic_position',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'academic_positions',
				'field_type' => 'checkbox',
				'add_term' => 1,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_physician_academic_bio',
				'label' => 'Academic Bio',
				'name' => 'physician_academic_bio',
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
				'key' => 'field_physician_academic_short_bio',
				'label' => 'Short Academic Bio',
				'name' => 'physician_academic_short_bio',
				'type' => 'text',
				'instructions' => 'Limit of 30 words. Preferred length is approx 18 words.',
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
				'maxlength' => 176,
			),
			array(
				'key' => 'field_physician_academic_office',
				'label' => 'Office Location',
				'name' => 'physician_academic_office',
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
				'key' => 'field_physician_academic_map',
				'label' => 'Building / Map',
				'name' => 'physician_academic_map',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
				),
				'default_value' => array(
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_physician_contact_information',
				'label' => 'Contact Information',
				'name' => 'physician_contact_information',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_physician_contact_type',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => 'Add More',
				'sub_fields' => array(
					array(
						'key' => 'field_5d4098e905571',
						'label' => 'Type',
						'name' => 'type',
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
						),
						'default_value' => array(
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'return_format' => 'value',
						'ajax' => 0,
						'placeholder' => '',
					),
					array(
						'key' => 'field_physician_contact_info',
						'label' => 'Information',
						'name' => 'information',
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
				'key' => 'field_physician_education_tab',
				'label' => '<i class="dashicons dashicons-book-alt"></i> Education',
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
				'key' => 'field_physician_academic_appointment',
				'label' => 'Academic Appointment',
				'name' => 'physician_academic_appointment',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_physician_appointment_title',
						'label' => 'Academic Title',
						'name' => 'academic_title',
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
						'key' => 'field_5d4099f605575',
						'label' => 'Department',
						'name' => 'department',
						'type' => 'taxonomy',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
								'taxonomy' => 'academic_department',
						'field_type' => 'select',
						'add_term' => 1,
						'save_terms' => 0,
						'load_terms' => 0,
						'return_format' => 'id',
						'acfe_bidirectional' => array(
							'acfe_bidirectional_enabled' => '0',
						),
						'multiple' => 0,
						'allow_null' => 0,
					),
				),
			),
			array(
				'key' => 'field_physician_appointment_education',
				'label' => 'Education',
				'name' => 'physician_education',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_5d409a9e05577',
						'label' => 'Education Type',
						'name' => 'education_type',
						'type' => 'taxonomy',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
								'taxonomy' => 'educationtype',
						'field_type' => 'select',
						'add_term' => 1,
						'save_terms' => 0,
						'load_terms' => 0,
						'return_format' => 'id',
						'acfe_bidirectional' => array(
							'acfe_bidirectional_enabled' => '0',
						),
						'multiple' => 0,
						'allow_null' => 0,
					),
					array(
						'key' => 'field_physician_appointment_school',
						'label' => 'School',
						'name' => 'school',
						'type' => 'taxonomy',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
								'taxonomy' => 'schools',
						'field_type' => 'select',
						'add_term' => 1,
						'save_terms' => 0,
						'load_terms' => 0,
						'return_format' => 'id',
						'acfe_bidirectional' => array(
							'acfe_bidirectional_enabled' => '0',
						),
						'multiple' => 0,
						'allow_null' => 0,
					),
					array(
						'key' => 'field_physician_appointment_description',
						'label' => 'Description',
						'name' => 'description',
						'type' => 'text',
						'instructions' => 'Description of the Education (if needed)',
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
				'key' => 'field_physician_boards',
				'label' => 'Boards',
				'name' => 'physician_boards',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'boards',
				'field_type' => 'multi_select',
				'allow_null' => 0,
				'add_term' => 1,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'acfe_bidirectional' => array(
					'acfe_bidirectional_enabled' => '0',
				),
				'multiple' => 0,
			),
			array(
				'key' => 'field_physician_profiles_link',
				'label' => 'Profiles Link',
				'name' => 'physician_research_profiles_link',
				'type' => 'url',
				'instructions' => 'Please include the full URL, including https://',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
			array(
				'key' => 'field_pubmed_author_id',
				'label' => 'Pubmed Author ID',
				'name' => 'physician_pubmed_author_id',
				'type' => 'text',
				'instructions' => 'Used to link to Pubmed complete list. AuthorID is found at the end of a link URL for Author.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33',
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
				'key' => 'field_physician_author_number',
				'label' => 'Number Latest Articles',
				'name' => 'physician_author_number',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					1 => '1',
					3 => '3',
					5 => '5',
					10 => '10',
				),
				'default_value' => array(
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_physician_select_publications',
				'label' => 'Selected Publications',
				'name' => 'physician_select_publications',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_physician_select_publications_pmid',
						'label' => 'PubMed ID (PMID)',
						'name' => 'pubmed_id_pmid',
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
						'key' => 'field_physician_select_publications_pubmed',
						'label' => 'Pubmed Information',
						'name' => 'pubmed_information',
						'type' => 'textarea',
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
						'maxlength' => '',
						'rows' => '',
						'new_lines' => '',
					),
				),
			),
			array(
				'key' => 'field_physician_research_tab',
				'label' => '<i class="dashicons dashicons-clipboard"></i> Research',
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
				'key' => 'field_physician_research_bio',
				'label' => 'Research Bio',
				'name' => 'physician_research_bio',
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
				'key' => 'field_physician_research_interests',
				'label' => 'Research Interests',
				'name' => 'physician_research_interests',
				'type' => 'textarea',
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
				'maxlength' => '',
				'rows' => '',
				'new_lines' => '',
			),
			array(
				'key' => 'field_physician_extra_tab',
				'label' => '<i class="dashicons dashicons-awards"></i> Extra',
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
				'key' => 'field_physician_awards',
				'label' => 'Award(s)',
				'name' => 'physician_awards',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_5d409d5e05585',
				'min' => 0,
				'max' => 0,
				'layout' => 'table',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_physician_awards_year',
						'label' => 'Year',
						'name' => 'year',
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
						'key' => 'field_physician_awards_title',
						'label' => 'Award Title',
						'name' => 'title',
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
						'key' => 'field_physician_awards_information',
						'label' => 'Information',
						'name' => 'information',
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
				'key' => 'field_physician_additional_info',
				'label' => 'Additional Information',
				'name' => 'physician_additional_info',
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
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'physicians',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'acfcdt_manage_table_definition' => 1,
		'acfcdt_table_name' => 'uamswp_physicians',
		'acfcdt_table_definition_file_name' => 'table_uamswp_physicians',
	));

	endif;


add_action( 'rwmb_enqueue_scripts', function ()
{
	// Bring in variables from outside of the function
	global $pagenow; // WordPress-specific global variable
	global $post_type; // WordPress-specific global variable

	if (( 'post.php' == $pagenow	) && ('physicians' == $post_type)) {
		wp_enqueue_script( 'pubmed-update', get_stylesheet_directory_uri() . '/assets/js/mb-pubmed.js', [ 'jquery' ] );
	}
} );

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

	$full_name = $last_name . ' ' . $first_name . ' ' . $middle_name;

	$_POST['acf']['field_physician_full_name'] = $full_name;

	// $short_bio = $_POST['acf']['field_physician_short_clinical_bio'];
	// if ($short_bio)
	// 	$_POST['post_excerpt'] = $short_bio;

	// Get the ID of the post
	// $pid = get_the_ID();

	// // Bring in variables from outside of the function
	// global $wpdb; // WordPress-specific global variable

	// $table_name = $wpdb->prefix."uams_physicians";

	// // Check if the ID exists in the custom table
	// $ID = $wpdb->get_var("SELECT ID FROM $table_name WHERE ID = '$pid'");
	// // If the ID doesn't exist, insert a new row with the ID
	// if (!$ID) {
	// 		// Insert
	// 		$wpdb->insert( $table_name, array(
	// 			"ID" => get_the_ID()
	// 		),
	// 		array( '%s' )
	// 	);
	// }

}

function physician_update_post_excerpt($post_id) {

	// Set excerpt value from field
	$post_excerpt = get_field('physician_short_clinical_bio',$post_id);

	// Update post options array
	$update_post_excerpt_args = array(
		'ID' => $post_id,
		'post_excerpt' => $post_excerpt,
	);

	// Update the post into the database
	wp_update_post( $update_post_excerpt_args );
}

add_action('acf/save_post', 'physician_update_post_excerpt', 20);

//add_action('rwmb_physicians_after_save_post', function( $post_id )
// {
// 	// Create full name to store in 'physician_full_name_meta' field in postmeta
// 	$first_name = $_POST['physician_first_name'];
// 	$middle_name = $_POST['physician_middle_name'];
// 	$last_name = $_POST['physician_last_name'];

// 	$full_name = $last_name . ' ' . $first_name . ' ' . $middle_name;

// 	$short_bio = $_POST['physician_short_clinical_bio'];

// 	// Get the post ID
// 	$pid = get_the_ID();

// 	// Bring in variables from outside of the function
// 	global $wpdb; // WordPress-specific global variable

// 	$table_name = $wpdb->prefix."postmeta";

// 	$ID = $wpdb->get_var("SELECT meta_id FROM $table_name WHERE meta_key='physician_full_name_meta' AND post_id= '$pid'");
// 	// If the ID exists, update the data
// 	if ($ID) {
// 		// Update
// 		$wpdb->update($table_name, array(
// 				'meta_key' => 'physician_full_name_meta',
// 				'meta_value' => $full_name
// 			),
// 			array( 'meta_id' => $ID )
// 		);
// 	} else {
// 		// Insert the data
// 		$wpdb->insert( $wpdb->postmeta, array(
// 			"post_id" => get_the_ID(),
// 			'meta_key' => 'physician_full_name_meta',
// 			'meta_value' => $full_name
// 		),
// 		array( '%d', '%s', '%s' )
// 	);
// 	}

// 	$wpdb->update($wpdb->prefix."posts", array(
// 			'post_excerpt' => $short_bio,
// 			),
// 			array( 'id' => $pid )
// 		);
// } );