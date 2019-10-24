<?php defined( 'WPINC' ) or die('Nothing to see here.'); return array (
  'tables' => 
  array (
    'uamswp_locations' => 
    array (
      'name' => 'uamswp_locations',
      'relationship' => 
      array (
        'type' => 'post',
        'post_type' => 'locations',
      ),
      'primary_key' => 
      array (
        0 => 'id',
      ),
      'keys' => 
      array (
        0 => 
        array (
          'name' => 'post_id',
          'columns' => 
          array (
            0 => 'post_id',
          ),
          'unique' => true,
        ),
      ),
      'columns' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'format' => '%d',
          'null' => false,
          'auto_increment' => true,
          'unsigned' => true,
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'id',
          ),
        ),
        1 => 
        array (
          'name' => 'post_id',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'post_id',
          ),
        ),
        2 => 
        array (
          'name' => 'location_address_1',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_address_1',
          ),
          'format' => '%s',
        ),
        3 => 
        array (
          'name' => 'location_address_2',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_address_2',
          ),
          'format' => '%s',
        ),
        4 => 
        array (
          'name' => 'location_city',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_city',
          ),
          'format' => '%s',
        ),
        5 => 
        array (
          'name' => 'location_state',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_state',
          ),
          'format' => '%s',
        ),
        6 => 
        array (
          'name' => 'location_zip',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_zip',
          ),
          'format' => '%s',
        ),
        7 => 
        array (
          'name' => 'location_map',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_map',
          ),
          'format' => '%s',
        ),
        8 => 
        array (
          'name' => 'location_parking',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_parking',
          ),
          'format' => '%s',
        ),
        9 => 
        array (
          'name' => 'location_direction',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_direction',
          ),
          'format' => '%s',
        ),
        10 => 
        array (
          'name' => 'location_about',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_about',
          ),
          'format' => '%s',
        ),
        11 => 
        array (
          'name' => 'location_short_desc',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_short_desc',
          ),
          'format' => '%s',
        ),
        12 => 
        array (
          'name' => 'location_phone',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_phone',
          ),
          'format' => '%s',
        ),
        13 => 
        array (
          'name' => 'location_phone_numbers',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_phone_numbers',
          ),
          'format' => '%s',
        ),
        14 => 
        array (
          'name' => 'location_appointment',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_appointment',
          ),
          'format' => '%s',
        ),
        15 => 
        array (
          'name' => 'location_appointment_bring',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_appointment_bring',
          ),
          'format' => '%s',
        ),
        16 => 
        array (
          'name' => 'location_portal',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_portal',
          ),
          'format' => '%s',
        ),
        17 => 
        array (
          'name' => 'location_web_name',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_web_name',
          ),
          'format' => '%s',
        ),
        18 => 
        array (
          'name' => 'location_url',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_url',
          ),
          'format' => '%s',
        ),
        19 => 
        array (
          'name' => 'location_abbreviation',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_abbreviation',
          ),
          'format' => '%s',
        ),
        20 => 
        array (
          'name' => 'location_description',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_description',
          ),
          'format' => '%s',
        ),
        21 => 
        array (
          'name' => 'location_email',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_email',
          ),
          'format' => '%s',
        ),
        22 => 
        array (
          'name' => 'location_24_7',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_24_7',
          ),
        ),
        23 => 
        array (
          'name' => 'location_hours',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_hours',
          ),
          'format' => '%s',
        ),
        24 => 
        array (
          'name' => 'location_after_hours',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_after_hours',
          ),
          'format' => '%s',
        ),
        25 => 
        array (
          'name' => 'location_holiday_hours',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_holiday_hours',
          ),
          'format' => '%s',
        ),
        26 => 
        array (
          'name' => 'location_conditions',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_conditions',
          ),
          'format' => '%s',
        ),
        27 => 
        array (
          'name' => 'location_treatments',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_treatments',
          ),
          'format' => '%s',
        ),
        28 => 
        array (
          'name' => 'location_expertise',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_expertise',
          ),
          'format' => '%s',
        ),
        29 => 
        array (
          'name' => 'location_medical_specialties',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_medical_specialties',
          ),
          'format' => '%s',
        ),
        30 => 
        array (
          'name' => 'location_medical_terms',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_medical_terms',
          ),
          'format' => '%s',
        ),
        31 => 
        array (
          'name' => 'location_clinic',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_clinic',
          ),
        ),
        32 => 
        array (
          'name' => 'location_facility',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_facility',
          ),
        ),
        33 => 
        array (
          'name' => 'location_physicians',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'location_physicians',
          ),
          'format' => '%s',
        ),
      ),
      'hash' => 'c89b987d38635e87c06696a8e97e40dd',
      'modified' => 1566591623,
      'type' => 'meta',
    ),
    'uamswp_physicians' => 
    array (
      'name' => 'uamswp_physicians',
      'relationship' => 
      array (
        'type' => 'post',
        'post_type' => 'physicians',
      ),
      'primary_key' => 
      array (
        0 => 'id',
      ),
      'keys' => 
      array (
        0 => 
        array (
          'name' => 'post_id',
          'columns' => 
          array (
            0 => 'post_id',
          ),
          'unique' => true,
        ),
      ),
      'columns' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'format' => '%d',
          'null' => false,
          'auto_increment' => true,
          'unsigned' => true,
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'id',
          ),
        ),
        1 => 
        array (
          'name' => 'post_id',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'post_id',
          ),
        ),
        2 => 
        array (
          'name' => 'physician_first_name',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_first_name',
          ),
          'format' => '%s',
        ),
        3 => 
        array (
          'name' => 'physician_middle_name',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_middle_name',
          ),
          'format' => '%s',
        ),
        4 => 
        array (
          'name' => 'physician_last_name',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_last_name',
          ),
          'format' => '%s',
        ),
        5 => 
        array (
          'name' => 'physician_pedigree',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_pedigree',
          ),
          'format' => '%s',
        ),
        6 => 
        array (
          'name' => 'physician_degree',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_degree',
          ),
          'format' => '%s',
        ),
        7 => 
        array (
          'name' => 'physician_prefix',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_prefix',
          ),
          'format' => '%s',
        ),
        8 => 
        array (
          'name' => 'physician_gender',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_gender',
          ),
          'format' => '%s',
        ),
        9 => 
        array (
          'name' => 'physician_searchable',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_searchable',
          ),
        ),
        10 => 
        array (
          'name' => 'physician_full_name',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_full_name',
          ),
          'format' => '%s',
        ),
        11 => 
        array (
          'name' => 'physician_title',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_clinical_title',
          ),
          'format' => '%s',
        ),
        12 => 
        array (
          'name' => 'physician_department',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_department',
          ),
          'format' => '%s',
        ),
        13 => 
        array (
          'name' => 'physician_clinical_bio',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_clinical_bio',
          ),
          'format' => '%s',
        ),
        14 => 
        array (
          'name' => 'physician_short_clinical_bio',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_short_clinical_bio',
          ),
          'format' => '%s',
        ),
        15 => 
        array (
          'name' => 'physician_youtube_link',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_youtube_link',
          ),
          'format' => '%s',
        ),
        16 => 
        array (
          'name' => 'physician_languages',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_languages',
          ),
          'format' => '%s',
        ),
        17 => 
        array (
          'name' => 'physician_locations',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_locations',
          ),
          'format' => '%s',
        ),
        18 => 
        array (
          'name' => 'physician_affiliation',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_affiliation',
          ),
          'format' => '%s',
        ),
        19 => 
        array (
          'name' => 'physician_patient_types',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_patient_types',
          ),
          'format' => '%s',
        ),
        20 => 
        array (
          'name' => 'physician_appointment_link',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_appointment_link',
          ),
          'format' => '%s',
        ),
        21 => 
        array (
          'name' => 'physician_primary_care',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_primary_care',
          ),
        ),
        22 => 
        array (
          'name' => 'physician_referral_required',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_referral_required',
          ),
        ),
        23 => 
        array (
          'name' => 'physician_accepting_patients',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_accepting_patients',
          ),
        ),
        24 => 
        array (
          'name' => 'physician_second_opinion',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_second_opinion',
          ),
        ),
        25 => 
        array (
          'name' => 'physician_expertise',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_expertise',
          ),
          'format' => '%s',
        ),
        26 => 
        array (
          'name' => 'physician_medical_specialties',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_medical_specialties',
          ),
          'format' => '%s',
        ),
        27 => 
        array (
          'name' => 'physician_conditions',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_conditions',
          ),
          'format' => '%s',
        ),
        28 => 
        array (
          'name' => 'physician_treatments',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_treatments',
          ),
          'format' => '%s',
        ),
        29 => 
        array (
          'name' => 'physician_medical_terms',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_medical_terms',
          ),
          'format' => '%s',
        ),
        30 => 
        array (
          'name' => 'physician_pid',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_pid',
          ),
        ),
        31 => 
        array (
          'name' => 'physician_npi',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_npi',
          ),
        ),
        32 => 
        array (
          'name' => 'physician_academic_title',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_title',
          ),
          'format' => '%s',
        ),
        33 => 
        array (
          'name' => 'physician_academic_appointment',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_appointment',
          ),
          'format' => '%s',
        ),
        34 => 
        array (
          'name' => 'physician_academic_college',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_college',
          ),
          'format' => '%s',
        ),
        35 => 
        array (
          'name' => 'physician_academic_position',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_position',
          ),
          'format' => '%s',
        ),
        36 => 
        array (
          'name' => 'physician_academic_bio',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_bio',
          ),
          'format' => '%s',
        ),
        37 => 
        array (
          'name' => 'physician_academic_short_bio',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_short_bio',
          ),
          'format' => '%s',
        ),
        38 => 
        array (
          'name' => 'physician_academic_office',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_office',
          ),
          'format' => '%s',
        ),
        39 => 
        array (
          'name' => 'physician_academic_map',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_academic_map',
          ),
          'format' => '%s',
        ),
        40 => 
        array (
          'name' => 'physician_contact_information',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_contact_information',
          ),
          'format' => '%s',
        ),
        41 => 
        array (
          'name' => 'physician_education',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_education',
          ),
          'format' => '%s',
        ),
        42 => 
        array (
          'name' => 'physician_boards',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_boards',
          ),
          'format' => '%s',
        ),
        43 => 
        array (
          'name' => 'physician_research_profiles_link',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_research_profiles_link',
          ),
          'format' => '%s',
        ),
        44 => 
        array (
          'name' => 'physician_pubmed_author_id',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_pubmed_author_id',
          ),
          'format' => '%s',
        ),
        45 => 
        array (
          'name' => 'physician_author_number',
          'format' => '%d',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_author_number',
          ),
        ),
        46 => 
        array (
          'name' => 'physician_select_publications',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_select_publications',
          ),
          'format' => '%s',
        ),
        47 => 
        array (
          'name' => 'physician_researcher_bio',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_researcher_bio',
          ),
          'format' => '%s',
        ),
        48 => 
        array (
          'name' => 'physician_research_interests',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_research_interests',
          ),
          'format' => '%s',
        ),
        49 => 
        array (
          'name' => 'physician_awards',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_awards',
          ),
          'format' => '%s',
        ),
        50 => 
        array (
          'name' => 'physician_additional_info',
          'map' => 
          array (
            'type' => 'acf_field_name',
            'identifier' => 'physician_additional_info',
          ),
          'format' => '%s',
        ),
      ),
      'hash' => 'c89b987d38635e87c06696a8e97e40dd',
      'modified' => 1566591623,
      'type' => 'meta',
    ),
  ),
  'table_names' => 
  array (
    0 => 'uamswp_locations',
    1 => 'uamswp_physicians',
  ),
  'types' => 
  array (
    'post' => 
    array (
      0 => 0,
      1 => 1,
    ),
  ),
  'post_types' => 
  array (
    'locations' => 
    array (
      0 => 0,
    ),
    'physicians' => 
    array (
      0 => 1,
    ),
  ),
  'join_tables' => 
  array (
  ),
  'sub_tables' => 
  array (
  ),
  'meta_tables' => 
  array (
  ),
  'acf_field_names' => 
  array (
    'post:locations' => 
    array (
      'id' => 
      array (
        0 => 0,
      ),
      'post_id' => 
      array (
        0 => 0,
      ),
      'location_address_1' => 
      array (
        0 => 0,
      ),
      'location_address_2' => 
      array (
        0 => 0,
      ),
      'location_city' => 
      array (
        0 => 0,
      ),
      'location_state' => 
      array (
        0 => 0,
      ),
      'location_zip' => 
      array (
        0 => 0,
      ),
      'location_map' => 
      array (
        0 => 0,
      ),
      'location_parking' => 
      array (
        0 => 0,
      ),
      'location_direction' => 
      array (
        0 => 0,
      ),
      'location_about' => 
      array (
        0 => 0,
      ),
      'location_short_desc' => 
      array (
        0 => 0,
      ),
      'location_phone' => 
      array (
        0 => 0,
      ),
      'location_phone_numbers' => 
      array (
        0 => 0,
      ),
      'location_appointment' => 
      array (
        0 => 0,
      ),
      'location_appointment_bring' => 
      array (
        0 => 0,
      ),
      'location_portal' => 
      array (
        0 => 0,
      ),
      'location_web_name' => 
      array (
        0 => 0,
      ),
      'location_url' => 
      array (
        0 => 0,
      ),
      'location_abbreviation' => 
      array (
        0 => 0,
      ),
      'location_description' => 
      array (
        0 => 0,
      ),
      'location_email' => 
      array (
        0 => 0,
      ),
      'location_24_7' => 
      array (
        0 => 0,
      ),
      'location_hours' => 
      array (
        0 => 0,
      ),
      'location_after_hours' => 
      array (
        0 => 0,
      ),
      'location_holiday_hours' => 
      array (
        0 => 0,
      ),
      'location_conditions' => 
      array (
        0 => 0,
      ),
      'location_treatments' => 
      array (
        0 => 0,
      ),
      'location_expertise' => 
      array (
        0 => 0,
      ),
      'location_medical_specialties' => 
      array (
        0 => 0,
      ),
      'location_medical_terms' => 
      array (
        0 => 0,
      ),
      'location_clinic' => 
      array (
        0 => 0,
      ),
      'location_facility' => 
      array (
        0 => 0,
      ),
      'location_physicians' => 
      array (
        0 => 0,
      ),
    ),
    'post:physicians' => 
    array (
      'id' => 
      array (
        0 => 1,
      ),
      'post_id' => 
      array (
        0 => 1,
      ),
      'physician_first_name' => 
      array (
        0 => 1,
      ),
      'physician_middle_name' => 
      array (
        0 => 1,
      ),
      'physician_last_name' => 
      array (
        0 => 1,
      ),
      'physician_pedigree' => 
      array (
        0 => 1,
      ),
      'physician_degree' => 
      array (
        0 => 1,
      ),
      'physician_prefix' => 
      array (
        0 => 1,
      ),
      'physician_gender' => 
      array (
        0 => 1,
      ),
      'physician_searchable' => 
      array (
        0 => 1,
      ),
      'physician_full_name' => 
      array (
        0 => 1,
      ),
      'physician_clinical_title' => 
      array (
        0 => 1,
      ),
      'physician_department' => 
      array (
        0 => 1,
      ),
      'physician_clinical_bio' => 
      array (
        0 => 1,
      ),
      'physician_short_clinical_bio' => 
      array (
        0 => 1,
      ),
      'physician_youtube_link' => 
      array (
        0 => 1,
      ),
      'physician_languages' => 
      array (
        0 => 1,
      ),
      'physician_locations' => 
      array (
        0 => 1,
      ),
      'physician_affiliation' => 
      array (
        0 => 1,
      ),
      'physician_patient_types' => 
      array (
        0 => 1,
      ),
      'physician_appointment_link' => 
      array (
        0 => 1,
      ),
      'physician_primary_care' => 
      array (
        0 => 1,
      ),
      'physician_referral_required' => 
      array (
        0 => 1,
      ),
      'physician_accepting_patients' => 
      array (
        0 => 1,
      ),
      'physician_second_opinion' => 
      array (
        0 => 1,
      ),
      'physician_expertise' => 
      array (
        0 => 1,
      ),
      'physician_medical_specialties' => 
      array (
        0 => 1,
      ),
      'physician_conditions' => 
      array (
        0 => 1,
      ),
      'physician_treatments' => 
      array (
        0 => 1,
      ),
      'physician_medical_terms' => 
      array (
        0 => 1,
      ),
      'physician_pid' => 
      array (
        0 => 1,
      ),
      'physician_npi' => 
      array (
        0 => 1,
      ),
      'physician_academic_title' => 
      array (
        0 => 1,
      ),
      'physician_academic_appointment' => 
      array (
        0 => 1,
      ),
      'physician_academic_college' => 
      array (
        0 => 1,
      ),
      'physician_academic_position' => 
      array (
        0 => 1,
      ),
      'physician_academic_bio' => 
      array (
        0 => 1,
      ),
      'physician_academic_short_bio' => 
      array (
        0 => 1,
      ),
      'physician_academic_office' => 
      array (
        0 => 1,
      ),
      'physician_academic_map' => 
      array (
        0 => 1,
      ),
      'physician_contact_information' => 
      array (
        0 => 1,
      ),
      'physician_education' => 
      array (
        0 => 1,
      ),
      'physician_boards' => 
      array (
        0 => 1,
      ),
      'physician_research_profiles_link' => 
      array (
        0 => 1,
      ),
      'physician_pubmed_author_id' => 
      array (
        0 => 1,
      ),
      'physician_author_number' => 
      array (
        0 => 1,
      ),
      'physician_select_publications' => 
      array (
        0 => 1,
      ),
      'physician_researcher_bio' => 
      array (
        0 => 1,
      ),
      'physician_research_interests' => 
      array (
        0 => 1,
      ),
      'physician_awards' => 
      array (
        0 => 1,
      ),
      'physician_additional_info' => 
      array (
        0 => 1,
      ),
    ),
  ),
  'acf_sub_table_owners' => 
  array (
  ),
  'acf_field_name_patterns' => 
  array (
  ),
  'acf_field_column_names' => 
  array (
    'uamswp_physicians.physician_clinical_title' => 'physician_title',
  ),
  'acf_field_column_name_patterns' => 
  array (
  ),
);