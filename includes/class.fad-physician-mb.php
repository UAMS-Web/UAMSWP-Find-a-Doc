<?php

/*
 *  Meta Box Fields for UAMS-2016
 */

//register_activation_hook( __FILE__, 'prefix_create_table' );

global $wpdb;

$table_name = $wpdb->prefix.'uams_physicians';
if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}uams_physicians'") != "{$wpdb->prefix}uams_physicians") {
  add_action( 'init', 'physicians_create_table' );
  function physicians_create_table() {

      global $wpdb;

      if ( ! class_exists( 'MB_Custom_Table_API' ) ) {
          return;
      }
      MB_Custom_Table_API::create( "{$wpdb->prefix}uams_physicians", array(
          'physician_first_name' => 'VARCHAR(50) NOT NULL',
          'physician_middle_name' => 'VARCHAR(50) NOT NULL',
          'physician_last_name' => 'VARCHAR(50) NOT NULL',
          'physician_pedigree' => 'VARCHAR(25) NOT NULL',
          'physician_degree' => 'VARCHAR(25) NOT NULL',
					'physician_prefix' => 'VARCHAR(10) NOT NULL',
					'physician_gender' => 'VARCHAR(10) NOT NULL',
					//'physician_active' => 'TINYINT(2) NOT NULL', Not used - switched to status update
					'physician_regional' => 'TINYINT(2) NOT NULL',
					'physician_full_name' => 'VARCHAR(150) NOT NULL',
          'physician_title' => 'VARCHAR(255) NOT NULL',
          'physician_clinical_bio' => 'LONGTEXT NOT NULL',
          'physician_short_clinical_bio' => 'VARCHAR(255) NOT NULL',
          'physician_youtube_link' => 'VARCHAR(255) NOT NULL',
          'physician_languages' => 'LONGTEXT NOT NULL',
          'physician_affiliation' => 'LONGTEXT NOT NULL',
          'physician_appointment_link' => 'VARCHAR(255) NOT NULL',
          'physician_patient_types' => 'LONGTEXT NOT NULL',
          'physician_searchable' => 'TINYINT(2) NOT NULL',
          'physician_primary_care' => 'TINYINT(2) NOT NULL',
          'physician_referral_required' => 'TINYINT(2) NOT NULL',
          'physician_accepting_patients' => 'TINYINT(2) NOT NULL',
          'physician_second_opinion' => 'TINYINT(2) NOT NULL',
          'medical_specialies' => 'LONGTEXT NOT NULL',
          'physician_conditions' => 'LONGTEXT NOT NULL',
          'medical_procedures' => 'LONGTEXT NOT NULL',
          'medical_terms' => 'LONGTEXT NOT NULL',
          'physician_pid' => 'VARCHAR(10) NOT NULL',
          'physician_npi' => 'VARCHAR(10) NOT NULL',
          'physician_academic_title' => 'VARCHAR(255) NOT NULL',
					'physician_academic_college' => 'LONGTEXT NOT NULL',
					'physician_academic_position' => 'LONGTEXT NOT NULL',
          'physician_academic_bio' => 'LONGTEXT NOT NULL',
          'physician_academic_short_bio' => 'VARCHAR(255) NOT NULL',
          'physician_academic_office' => 'VARCHAR(255) NOT NULL',
          'physician_academic_map' => 'VARCHAR(25) NOT NULL',
          'physician_contact_information' => 'LONGTEXT NOT NULL',
          'physician_academic_appointment' => 'LONGTEXT NOT NULL',
					'physician_education' => 'LONGTEXT NOT NULL',
					'physician_boards' => 'LONGTEXT NOT NULL',
          'physician_research_profiles_link' => 'VARCHAR(255) NOT NULL',
          'physician_pubmed_author_id' => 'VARCHAR(10) NOT NULL',
          'pubmed_author_number' => 'TINYINT(2) NOT NULL',
          'physician_select_publications' => 'LONGTEXT NOT NULL',
          'physician_research_bio' => 'LONGTEXT NOT NULL',
          'physician_research_interests' => 'TEXT NOT NULL',
          'physician_awards' => 'LONGTEXT NOT NULL',
          'physician_additional_info' => 'LONGTEXT NOT NULL',
      ) );
  }
}

add_filter( 'rwmb_meta_boxes', 'uams_physicians_register_meta_boxes' );

function uams_physicians_register_meta_boxes( $meta_boxes ) {

    global $wpdb;

    $meta_boxes[] = array (
      'id' => 'physicians',
      'title' => 'Physicians',
      'post_types' =>   array (
         'physicians',
      ),
      'storage_type' => 'custom_table',    // Important
      'table' => "{$wpdb->prefix}uams_physicians", // Your custom table name
      'context' => 'normal',
      'priority' => 'high',
      'autosave' => true,
      'tab_style' => 'box',
      'tab_wrapper' => true,
      'tabs' =>   array (
	        'tab_details' =>     array (
	          'label' => 'Details',
	          'icon' => 'dashicons-admin-users',
	        ),
	        'tab_clin_profile' =>     array (
	          'label' => 'Clinical Profile',
	          'icon' => 'dashicons-id-alt',
	        ),
	        'tab_clin_details' =>     array (
	          'label' => 'Clinical Details',
	          'icon' => 'dashicons-forms',
	        ),
	        'tab_academic' =>     array (
	          'label' => 'Academic Profile',
	          'icon' => 'dashicons-edit',
	        ),
	        'tab_edu' =>     array (
	          'label' => 'Education',
	          'icon' => 'dashicons-book-alt',
	        ),
	        'tab_research' =>     array (
	          'label' => 'Research',
	          'icon' => 'dashicons-clipboard',
	        ),
	        'tab_extra' =>     array (
	          'label' => 'Extra',
	          'icon' => 'dashicons-awards',
	        ),
      ),
      'columns' => array( // Simply define the size of the column (from 1 to 12)
          'column-1' => 6,
          'column-2' => 6,
      ),

      'fields' =>   array (

        array (
          	'id' => 'physician_first_name',
          	'type' => 'text',
          	'name' => 'First Name',
          	'tab' => 'tab_details',
            'columns' => 3,
        ),

        array (
          	'id' => 'physician_middle_name',
          	'type' => 'text',
          	'name' => 'Middle Name',
          	'tab' => 'tab_details',
            'columns' => 2,
        ),

        array (
          'id' => 'physician_last_name',
          'type' => 'text',
          'name' => 'Last Name',
          'tab' => 'tab_details',
          'columns' => 3,
        ),

        array (
          'id' => 'physician_pedigree',
          'type' => 'text',
          'name' => 'Pedigree',
          'tab' => 'tab_details',
          'columns' => 2,
        ),

        array (
          'id' => 'physician_degree',
          'type' => 'text',
          'name' => 'Degree',
          'tab' => 'tab_details',
          'columns' => 2,
        ),

        array (
          'id' => 'physician_prefix',
          'type' => 'text',
          'name' => 'Prefix',
          'tab' => 'tab_details',
          'desc' => 'Example: Dr.',
          'columns' => 3,
        ),

        array (
          'id' => 'physician_gender',
          'name' => 'Gender',
          'type' => 'radio',
          'columns' => 3,
          'options' => array(
            'Male' => 'Male',
            'Female' => 'Female',
          ),
          'inline' => false,
          'tab' => 'tab_details',
        ),
		array (
          'id' => 'physician_regional',
          'type' => 'checkbox',
          'name' => 'Regional Campus',
          'desc' => 'Regional Campus only',
          'label_description' => 'Excluded from main campus results',
          'std'  => 0,
          'tab' => 'tab_details',
          'admin_columns' => array(
            'position' => 'after title',
            'title' => 'Regional',
          ),
          'columns' => 3,
          //'visible' => array( $post_ID, '=', 1 ),
        ),
        array(
            'id'   => 'physician_full_name',
            'type' => 'hidden',
            'tab' => 'tab_details',
            // Hidden field must have predefined value
            'std'  => '',
        ),

        /* Clinical Profile Tab */
        // array (
        //   'id' => 'physician_title',
        //   'type' => 'text',
        //   'name' => 'Title',
        //   'tab' => 'tab_clin_profile',
        //   'label_description' => 'Main Title',
        //   'columns' => 12,
        // ),

        array (
          'id' => 'physician_clinical_title',
          'name' => 'Clinical Job Title',
          'desc' => 'General Title for Patients / Public',
          'type' => 'taxonomy',
          'taxonomy' => 'clinical_title',
          'field_type' => 'select_advanced',
          // 'multiple'    => true,
          'columns' => 6,
          'tab' => 'tab_clin_profile',
          'placeholder' => 'Select Title',
          'js_options'      => array(
            'width' => '100%',
          ),
        ),

        array (
          'id' => 'physician_department',
          'name' => 'Medical Department',
          //'desc' => 'General Title for Patients / Public',
          'type' => 'taxonomy',
          'taxonomy' => 'department',
          'field_type' => 'select_advanced',
          // 'multiple'    => true,
          'columns' => 6,
          'tab' => 'tab_clin_profile',
          // 'std' => '167', // English
          'placeholder' => 'Select Department',
          'js_options'      => array(
            'width' => '100%',
          ),
        ),

        array (
          'id' => 'physician_clinical_bio',
          'name' => 'Clinical Bio',
          'type' => 'wysiwyg',
          'options' => array(
              'textarea_rows' => 16,
              'teeny'         => false,
              'media_buttons' => false,
          ),
          'tab' => 'tab_clin_profile',
          'columns' => 12,
        ),

        array (
          'id' => 'physician_short_clinical_bio',
          'type' => 'textarea',
          'name' => 'Short Bio',
          'tab' => 'tab_clin_profile',
          'label_description' => 'Limit of 30 words. Preferred length is approx 18 words.',
          'columns' => 6,
        ),

        array (
          'id' => 'physician_youtube_link',
          'type' => 'url',
          'name' => 'Youtube Link',
          'label_description' => 'Full URL, including https://',
          'columns' => 6,
          'tab' => 'tab_clin_profile',
        ),

        array (
          'id' => 'physician_languages',
          'name' => 'Language(s)',
          'type' => 'taxonomy',
          'taxonomy' => 'languages',
          'field_type' => 'select_advanced',
          'multiple'    => true,
          'columns' => 6,
          'tab' => 'tab_clin_profile',
          'std' => '167', // English
          'placeholder' => 'Select Language(s)',
          'query_args' => array(
            'orderby' => 'term_id',
          ),
          'js_options'      => array(
            'width' => '100%',
          ),
        ),

        array (
          'id' => 'physician_affiliation',
          'name' => 'Affiliation',
          'type' => 'taxonomy',
          'taxonomy' => 'affiliations',
          'field_type' => 'checkbox_list',
          'columns' => 6,
          'multiple'    => true,
          'std' => '532', // UAMS
          'query_args' => array(
            'orderby' => 'term_id',
          ),
          'tab' => 'tab_clin_profile',
        ),


        /* Clinical Details Tab */
        array (
          'id' => 'clinical_info',
          'type' => 'heading',
          'name' => 'Clinical Info',
          'tab' => 'tab_clin_details',
          'columns' => 12,
        ),

        array (
          'id' => 'physician_appointment_link',
          'type' => 'url',
          'size' => 45,
          'std' => 'https://uamshealth.com/appointments',
          'name' => 'Appointment Link',
          'tab' => 'tab_clin_details',
          'column' => 'column-1',
        ),

        array (
          'id' => 'physician_patient_types',
          'type' => 'taxonomy',
          'name' => 'Patient Types',
          'taxonomy' => 'patient_type',
          'field_type' => 'checkbox_list',
          'multiple'    => true,
          'tab' => 'tab_clin_details',
          'column' => 'column-1',
        ),

        array (
          'id' => 'physician_searchable',
          'name' => 'Searchable',
          'type' => 'checkbox',
          'desc' => 'Display in search results',
          'std'  => 1,
          'tab' => 'tab_clin_details',
          'column' => 'column-1',
        ),

        array (
          'id' => 'physician_primary_care',
          // 'name' => 'Primary Care',
          'type' => 'checkbox',
          'desc' => 'Primary Care Physician?',
          'tab' => 'tab_clin_details',
          'column' => 'column-2',
        ),

        array (
          'id' => 'physician_referral_required',
          // 'name' => 'Referral Required',
          'type' => 'checkbox',
          'desc' => 'Referral required for new patients',
          'tab' => 'tab_clin_details',
          'column' => 'column-2',
        ),

        array (
          'id' => 'physician_accepting_patients',
          // 'name' => 'Accepting New Patients',
          'type' => 'checkbox',
          'desc' => 'Currently accepting new patients',
          'tab' => 'tab_clin_details',
          'column' => 'column-2',
        ),

        array (
          'id' => 'physician_second_opinion',
          // 'name' => 'Provides Second Opinion',
          'type' => 'checkbox',
          'desc' => 'Provides second opinion',
          'tab' => 'tab_clin_details',
          'column' => 'column-2',
        ),

        array (
          'id' => 'medical_specialties',
          'type' => 'taxonomy',
          'name' => 'Medical Specialties',
          'taxonomy' => 'specialty',
          'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => array(
            'width' => '100%',
          ),
          'columns' => 12,
          'tab' => 'tab_clin_details',
        ),

        array (
          'id' => 'physician_conditions',
          'type' => 'taxonomy',
          'name' => 'Conditions Treated',
          'taxonomy' => 'condition',
          'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => array(
            'width' => '100%',
          ),
          'columns' => 12,
          'tab' => 'tab_clin_details',
        ),

        // array (
        //   'id' => 'medical_procedures',
        //   'type' => 'taxonomy',
        //   'name' => 'Medical Procedures',
        //   'label_description' => 'Not used',
        //   'taxonomy' => 'medical_procedures',
        //   'field_type' => 'select_advanced',
        //   'placeholder' => 'Select an Item',
        //   'multiple'    => true,
        //   'js_options'      => array(
        //     'width' => '100%',
        //   ),
        //   'columns' => 12,
        //   'tab' => 'tab_clin_details',
        // ),

        array (
          'id' => 'medical_terms',
          'type' => 'taxonomy',
          'name' => 'Medical Terms (Tags)',
          'taxonomy' => 'medical_terms',
          'field_type' => 'select_advanced',
          'placeholder' => 'Select an Item',
          'multiple'    => true,
          'js_options'      => array(
            'width' => '100%',
          ),
          'columns' => 12,
          'tab' => 'tab_clin_details',

        ),

        array (
          'id' => 'physician_pid',
          'type' => 'text',
          'name' => 'PID',
          'columns' => 6,
          'tab' => 'tab_clin_details',
        ),

        array (
          'id' => 'physician_npi',
          'type' => 'text',
          'name' => 'NPI',
          'columns' => 6,
          'tab' => 'tab_clin_details',
        ),

        /* Academic Profile Tab */
        array (
          'id' => 'profile_info',
          'type' => 'heading',
          'desc' => 'This information is designed for department and public websites.',
          'name' => 'Profile Information',
          'tab' => 'tab_academic',
          'columns' => 12,
        ),

        array (
          'id' => 'physician_academic_title',
          'type' => 'text',
          'name' => 'Academic Title',
          'size' => 45,
          'tab' => 'tab_academic',
          'columns' => 12,
        ),

        array (
          'id' => 'physician_academic_college',
          'type' => 'taxonomy',
          'name' => 'College Affiliation',
          'taxonomy' => 'academic_colleges',
          'field_type' => 'checkbox_list',
          'multiple'    => true,
          'columns' => 6,
          'tab' => 'tab_academic',
        ),

        array (
          'id' => 'physician_academic_position',
          'type' => 'taxonomy',
          'name' => 'Position',
          'taxonomy' => 'academic_positions',
          'field_type' => 'checkbox_list',
          'multiple'    => true,
          'columns' => 6,
          //'placeholder' => 'Select an Item',
          'tab' => 'tab_academic',
        ),

        array (
          'id' => 'physician_academic_bio',
          'name' => 'Academic Bio',
          'type' => 'wysiwyg',
          'columns' => 12,
          'options' => array(
              'textarea_rows' => 16,
              //'teeny'         => false,
              'media_buttons' => false,
          ),
          'tab' => 'tab_academic',
        ),

        array (
          'id' => 'physician_academic_short_bio',
          'type' => 'textarea',
          'name' => 'Short Academic Bio',
          'label_description' => 'Limit of 30 words. Preferred length is approx 18 words.',
          'tab' => 'tab_academic',
          'columns' => 12,
        ),

        // array(
        //     'type' => 'heading',
        //     'name' => 'Office Information',
        //     'desc' => '',
        //     'tab' => 'tab_academic',
        //     'columns' => 12,
        // ),

        array (
          'id' => 'physician_academic_office',
          'type' => 'text',
          'name' => 'Office Location',
          'tab' => 'tab_academic',
          'columns' => 6,
        ),

        array (
          'id' => 'physician_academic_map',
          'name' => 'Building / Map',
          'type' => 'select',
          'columns' => 6,
          'placeholder' => 'Select an Item',
          'options' => array(
            '127' => '12th St. Clinic',
            '116' => 'Administration West (ADMINW)',
            '117' => 'Barton Research (BART)',
            '118' => 'Biomedical Research Center I (BMR1)',
            '119' => 'Biomedical Research Center II (BMR2)',
            '120' => 'Bioventures (BVENT)',
            '121' => 'Boiler House (BH)',
            '122' => 'Central Building (CENT)',
            '123' => 'College of Public Health (COPH)',
            '124' => 'Computer Building (COMP)',
            '125' => 'Cottage 3 (C3)',
            '128' => 'Distribution Center (DIST)',
            '129' => 'Donald W. Reynolds Institute on Aging (RIOA)',
            '126' => 'Ear Nose Throat (ENT)',
            '131' => 'Education Building South (EDS)',
            '130' => 'Education II (EDII)',
            '132' => 'Family Medical Center (FMC)',
            '133' => 'Freeway Medical Tower (FWAY)',
            '134' => 'Harvey and Bernice Jones Eye Institute (JEI)',
            '135' => 'Hospital (HOSP)',
            '136' => 'I. Dodd Wilson Education Building (IDW)',
            '137' => 'Jackson T. Stephens Spine Institute (JTSSI)',
            '138' => 'Magnetic Resonance Imaging (MRI)',
            '139' => 'Mediplex Apartments (1 unit) (MEDPX)',
            '140' => 'Northwest Campus (NWA)',
            '141' => 'Outpatient Center (OPC)',
            '142' => 'Outpatient Diagnostic Center (OPDC)',
            '143' => 'Paint Shop & Flammable Storage (PAINT)',
            '144' => 'PET (PET)',
            '145' => 'Physical Plant (PP)',
            '146' => 'Psychiatric Research Institute (PRI)',
            '147' => 'Radiation Oncology [ROC] (RADONC)',
            '148' => 'Residence Hall Complex (RHC)',
            '149' => 'Ricks Armory',
            '150' => 'Walker Annex (ANNEX)',
            '151' => 'Ward Tower (WARD)',
            '152' => 'West Central Energy Plant (WCEP)',
            '153' => 'Westmark (WESTM)',
            '154' => 'Winston K. Shorey Building (SHOR)',
            '155' => 'Winthrop P. Rockefeller Cancer Institute (WPRCI)',
          ),
          'tab' => 'tab_academic',
        ),

        array(
          'id'     => 'physician_contact_information',
          'group_title' => 'Contact Infomation',
          'type'   => 'group',
          'tab' => 'tab_academic',
	      	'clone'  => true,
	      	'sort_clone' => true,
          'collapsible' => true,
	      	'fields' => array(
	            array(
	                'name' => 'Type',
	                'id'   => 'office_contact_type',
	                'type' => 'select',
                  'columns' => 6,
                  'placeholder' => 'Select an Item',
	                'options' => array(
	                	  'phone' => 'Phone',
          						'fax' => 'Fax',
          						'mobile' => 'Mobile',
          						'email' => 'Email',
          						'sms' => 'Text/SMS',
	                ),
	            ),
	            array(
	                'name' => 'Value',
	                'id'   => 'office_contact_value',
	                'type' => 'text',
                  'columns' => 6,
	            ),
        	),
        ),


        /* Education Tab */
        array(
          'id'     => 'physician_academic_appointment',
          'group_title' => 'Academic Appointment',
          'type'   => 'group',
          'tab' => 'tab_edu',
	      	'clone'  => true,
	      	'sort_clone' => true,
          'collapsible' => true,
          'add_button' => 'Add Academic Appointment',
	      	'fields' => array(
	            array(
	                'name' => 'Academic Title',
	                'id'   => 'academic_title',
	                'type' => 'text',
                  'columns' => 6,
                  'size' => 45,
	            ),
	            array(
                  'id' => 'academic_department',
                  'name' => 'Department',
                  'type' => 'taxonomy',
                  'taxonomy' => 'academic_department',
                  'columns' => 6,
                  'multiple'    => false,
                  //'std' => '532', // UAMS
                  'query_args' => array(
                    'orderby' => 'name',
                  ),
	            ),
        	),
        ),

		    array(
          	'id'     => 'physician_education',
            'group_title' => 'Education',
          	'type'   => 'group',
          	'tab' => 'tab_edu',
            'collapsible' => true,
	      	  'clone'  => true,
	      	  'sort_clone' => true,
            'add_button' => 'Add Education',
	      	  'fields' => array(
	            array(
	                'name' => 'Education Type',
                  'id'   => 'physician_education_type',
                  'type' => 'taxonomy_advanced',
                  'field_type' => 'select_advanced',
	                'taxonomy' => 'educationtype',
                  'columns' => 4,
                  'multiple'    => false,
                  'query_args' => array(
                    'orderby' => 'name',
                  ),
              ),
	            array(
	                'name' => 'School',
	                'id'   => 'physician_education_school',
                  'type' => 'taxonomy',
                  'taxonomy' => 'schools',
                  'columns' => 4,
                  'multiple'    => false,
                  'query_args' => array(
                    'orderby' => 'name',
                  ),
	            ),
	            array(
	                'name'    => 'Desctiption',
	                'id'      => 'physician_education_description',
	                'type'    => 'text',
                  'desc' => 'Description of the Education (if needed)',
                  'columns' => 4,
	            ),
        	),
        ),

		    array(
        	'tab' => 'tab_edu',
          'name' => 'Boards',
          'id'   => 'physician_boards',
          'type' => 'taxonomy',
          'taxonomy' => 'boards',
          'multiple'    => true,
          'js_options'      => array(
            'width' => '95%',
          ),
          'query_args' => array(
            'orderby' => 'name',
          ),
        ),

        array (
          'id' => 'physician_research_profiles_link',
          'type' => 'url',
          'name' => 'Profiles Link',
          'size' => 50,
          'label_description'  => 'Please include the full URL, including https://',
          'tab' => 'tab_edu',
          'columns' => 5,
        ),

        array (
          'id' => 'physician_pubmed_author_id',
          'type' => 'text',
          'name' => 'Pubmed Author ID',
          'tab' => 'tab_edu',
          'desc' => 'Used to link to Pubmed complete list. AuthorID is found at the end of a link URL for Author.',
          'columns' => 4,
        ),

        array (
          'id' => 'pubmed_author_number',
          'name' => 'Number Lastest Articles',
          'type' => 'select',
          'columns' => 3,
          'tab' => 'tab_edu',
          'placeholder' => __( 'Select an option', 'uams-physicians' ),
          'std' => '3',
          'options' => array(
            '1' => '1',
            '3' => '3',
            '5' => '5',
            '10' => '10',
          ),
        ),

        array(
          'id'     => 'physician_select_publications',
          'group_title' => 'Selected Publications',
          'type'   => 'group',
          'tab' => 'tab_edu',
          'add_button' => 'Add Publication',
          'clone'  => true,
          'sort_clone' => true,
          'collapsible' => true,
          'fields' => array(
              array(
                  'name' => 'PubMed ID (PMID)',
                  'id'   => 'publication_pmid',
                  'type' => 'text',
                  'columns' => 3,
              ),
              array(
                  'name' => 'Pubmed Information',
                  'id'   => 'publication_pubmed_info',
                  'type' => 'textarea',
                  'columns' => 9,
              ),
          ),
        ),

        /* Research Tab */
        array (
          'id' => 'physician_research_bio',
          'name' => 'Researcher Bio',
          'type' => 'wysiwyg',
          'options' => array(
              'textarea_rows' => 16,
              'teeny'         => false,
              'media_buttons' => false,
          ),
          'tab' => 'tab_research',
          'columns' => 12,
        ),

        array (
          'id' => 'physician_research_interests',
          'type' => 'textarea',
          'name' => 'Research Interests',
          'tab' => 'tab_research',
        ),

        /* Extra Tab */
        array(
        	'id'     => 'physician_awards',
          'group_title' => 'Award(s)',
        	'type'   => 'group',
        	'tab' => 'tab_extra',
          'collapsible' => true,
	      	'clone'  => true,
          'add_button' => 'Add Award',
	      	'sort_clone' => true,
	      	'fields' => array(
	            array(
	                'name' => 'Year',
	                'id'   => 'award_year',
	                'type' => 'text',
                  'columns' => 6,
	            ),
	            array(
	                'name' => 'Award Title',
	                'id'   => 'award_title',
	                'type' => 'text',
                  'columns' => 6,
	            ),
	            array(
	                'name'    => 'Information',
	                'id'      => 'award_infor',
	                'type'    => 'wysiwyg',
                  'columns' => 12,
                  'options' => array(
                      'textarea_rows' => 6,
                      'teeny'         => true,
                      'media_buttons' => false,
                  ),
	            ),
        	),
          'tab' => 'tab_extra',
        ),

        array (
          'id' => 'physician_additional_info',
          'name' => 'Additional Info',
          'type' => 'wysiwyg',
          'options' => array(
              'textarea_rows' => 16,
              'teeny'         => false,
              'media_buttons' => false,
          ),
          'tab' => 'tab_extra',
        ),
      ),
      'validation' => array(
		    'rules'  => array(
		        'physician_first_name' => array(
		            'required'  => true,
		        ),
            'physician_last_name' => array(
                'required'  => true,
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


add_action( 'rwmb_enqueue_scripts', function ()
{
  global $pagenow;
  global $post_type;
  if (( 'post.php' == $pagenow  ) && ('physicians' == $post_type)) {
    wp_enqueue_script( 'pubmed-update', get_stylesheet_directory_uri() . '/assets/js/mb-pubmed.js', [ 'jquery' ] );
  }
} );

add_action('rwmb_physicians_before_save_post', function( $post_id )
{
  // Create full name to store in 'physician_full_name' field
  $first_name = $_POST['physician_first_name'];
  $middle_name = $_POST['physician_middle_name'];
  $last_name = $_POST['physician_last_name'];

  $full_name = $last_name . ' ' . $first_name . ' ' . $middle_name;

  $_POST['physician_full_name'] = $full_name;

  // Get the ID of the post
  $pid = get_the_ID();

  global $wpdb;
  $table_name  = $wpdb->prefix."uams_physicians";

  // Check if the ID exists in the custom table
  $ID = $wpdb->get_var("SELECT ID FROM $table_name WHERE ID = '$pid'");
  // If the ID doesn't exist, insert a new row with the ID
  if (!$ID) {
     // Insert
     $wpdb->insert( $table_name, array(
       "ID" => get_the_ID()
      ),
      array( '%s' )
    );
  }

} );

add_action('rwmb_physicians_after_save_post', function( $post_id )
{
  // Create full name to store in 'physician_full_name_meta' field in postmeta
  $first_name = $_POST['physician_first_name'];
  $middle_name = $_POST['physician_middle_name'];
  $last_name = $_POST['physician_last_name'];

  $full_name = $last_name . ' ' . $first_name . ' ' . $middle_name;

  $short_bio = $_POST['physician_short_clinical_bio'];

  // Get the post ID
  $pid = get_the_ID();

  global $wpdb;
  $table_name  = $wpdb->prefix."postmeta";

  $ID = $wpdb->get_var("SELECT meta_id FROM $table_name WHERE meta_key='physician_full_name_meta' AND post_id= '$pid'");
   // If the ID exists, update the data
   if ($ID) {
     // Update
     $wpdb->update($table_name, array(
          'meta_key' => 'physician_full_name_meta',
          'meta_value' => $full_name
        ),
        array( 'meta_id' => $ID )
     );
   } else {
     // Insert the data
     $wpdb->insert( $wpdb->postmeta, array(
       "post_id" => get_the_ID(),
       'meta_key' => 'physician_full_name_meta',
       'meta_value' => $full_name
      ),
      array( '%d', '%s', '%s' )
    );
   }
   //
   $wpdb->update($wpdb->prefix."posts", array(
	   		'post_excerpt' => $short_bio,
   		),
   		array( 'id' => $pid )
   	);
} );