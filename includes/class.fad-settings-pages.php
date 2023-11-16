<?php

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Find-a-Doc Settings',
		'menu_title'	=> 'Find-a-Doc Settings',
		'menu_slug' 	=> 'fad-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Clinical Providers Options',
		'menu_title'	=> 'Clinical Providers',
		'menu_slug' 	=> 'uamswp-fad-providers',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Clinical Locations Options',
		'menu_title'	=> 'Clinical Locations',
		'menu_slug' 	=> 'uamswp-fad-locations',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Areas of Expertise Options',
		'menu_title'	=> 'Clinical Areas of Expertise',
		'menu_slug' 	=> 'uamswp-fad-expertise',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Clinical Resource Options',
		'menu_title'	=> 'Clinical Resources',
		'menu_slug' 	=> 'uamswp-fad-resources',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Taxonomy Options',
		'menu_title'	=> 'Taxonomy Options',
		'menu_slug' 	=> 'uamswp-fad-tax',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'MyChart Scheduling Options',
		'menu_title'	=> 'MyChart Scheduling',
		'menu_slug' 	=> 'uamswp-fad-mychart',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Remove Medical Ontology Options',
		'menu_title'	=> 'Remove Medical Ontology',
		'menu_slug' 	=> 'uamswp-fad-remove-ontology',
		'parent_slug'	=> 'fad-settings',
		'redirect'		=> false
	));

}

// Add metaboxes for Settings page
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_uams_fad_settings',
		'title' => 'Theme Settings',
		'fields' => array(
			array(
				'key' => 'field_fad_google_key',
				'label' => 'Google Maps API Key',
				'name' => 'fad_google_key',
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
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'fad-settings',
				),
			),
		),
		'menu_order' => 5,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'left',
		'instruction_placement' => 'label',
		'active' => true,
	));

endif;



function my_acf_google_key() {
	$key = get_field('fad_google_key', 'option');
	if ($key) {
		acf_update_setting('google_api_key', $key);
	}
	// echo "<script> console.log('PHP: ".$key ."');</script>";
}
add_action('acf/init', 'my_acf_google_key');