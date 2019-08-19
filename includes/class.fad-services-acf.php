<?php

/*
 *  Meta Box Fields for UAMS-2016
 */

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_5d433a5fcb211',
    'title' => 'Medical Service Information',
    'fields' => array(
      array(
        'key' => 'field_5d433a750c039',
        'label' => 'Medical Specialties Offered',
        'name' => 'medical_specialties',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'acfe_permissions' => '',
        'taxonomy' => 'category',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'acfe_bidirectional' => array(
          'acfe_bidirectional_enabled' => '0',
        ),
        'acfe_validate' => '',
        'acfe_update' => '',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_5d433aa80c03a',
        'label' => 'Medical Terms (Tags)',
        'name' => 'medical_terms',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'acfe_permissions' => '',
        'taxonomy' => 'category',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 1,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'acfe_bidirectional' => array(
          'acfe_bidirectional_enabled' => '0',
        ),
        'acfe_validate' => '',
        'acfe_update' => '',
        'multiple' => 0,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'services',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_permissions' => '',
    'acfe_note' => '',
    'acfe_meta' => '',
  ));
  
  endif;

add_filter('acf/get_field_group', 'extend_header_field_group_function');
function extend_header_field_group_function($group) {

	if ($group['key'] != 'group_header_options') {
    // not our field group
    return $group;
	
	} else {

		// add an OR rule to existing location rules for a specific field group
		$group['location'] = array(
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'services',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			)
		);
		return $group;

		
	}
	
}