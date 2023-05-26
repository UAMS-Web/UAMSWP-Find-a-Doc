<?php

/*
 * Meta Box Fields for UAMS-2016
 */

//register_activation_hook( __FILE__, 'prefix_create_table' );

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_uamswp_colleges',
		'title' => 'Additional Information',
		'fields' => array(
			array(
				'key' => 'field_college_url',
				'label' => 'Website URL',
				'name' => 'college_url',
				'type' => 'link',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_permissions' => '',
				'return_format' => 'array',
				'acfe_validate' => '',
				'acfe_update' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'academic_colleges',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
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

	acf_add_local_field_group(array(
		'key' => 'group_uamswp_specialty',
		'title' => 'Additional Information',
		'fields' => array(
			array(
				'key' => 'field_specialty_url',
				'label' => 'Link to Specialty Page',
				'name' => 'specialty_url',
				'type' => 'link',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'acfe_permissions' => '',
				'return_format' => 'array',
				'acfe_validate' => '',
				'acfe_update' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'specialty',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'left',
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