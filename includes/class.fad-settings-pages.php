<?php

// Add Find-a-Doc Settings options page to the admin menu

	if ( function_exists('acf_add_options_page') ) {

		acf_add_options_page(array(
			'page_title' => 'Find-a-Doc Settings',
			'menu_title' => 'Find-a-Doc Settings',
			'menu_slug' => 'fad-settings',
			'capability' => 'edit_posts',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Clinical Providers Options',
			'menu_title' => 'Clinical Providers',
			'menu_slug' => 'uamswp-fad-providers',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Clinical Locations Options',
			'menu_title' => 'Clinical Locations',
			'menu_slug' => 'uamswp-fad-locations',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Areas of Expertise Options',
			'menu_title' => 'Clinical Areas of Expertise',
			'menu_slug' => 'uamswp-fad-expertise',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Clinical Resource Options',
			'menu_title' => 'Clinical Resources',
			'menu_slug' => 'uamswp-fad-resources',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Conditions and Treatments Options',
			'menu_title' => 'Conditions and Treatments',
			'menu_slug' => 'uamswp-fad-tax',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Appointment and Referral Information',
			'menu_title' => 'Appointment and Referral Information',
			'menu_slug' => 'uamswp-fad-appointment',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'MyChart Open Scheduling Options',
			'menu_title' => 'MyChart Open Scheduling',
			'menu_slug' => 'uamswp-fad-mychart',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Remove Medical Ontology Options',
			'menu_title' => 'Remove Medical Ontology',
			'menu_slug' => 'uamswp-fad-remove-ontology',
			'parent_slug' => 'fad-settings',
			'redirect' => false
		));

	}

// Add field group to Find-a-Doc Settings options page

	if ( function_exists('acf_add_local_field_group') ) {

		acf_add_local_field_group(array(
			'key' => 'group_uams_fad_settings',
			'title' => 'General Settings',
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
					'key' => 'field_fad_jump_links_title',
					'label' => 'Jump Links Title',
					'name' => 'fad_jump_links_title',
					'type' => 'text',
					'instructions' => 'When a page has a list of jump links (a.k.a. anchor links), this title defines the text used in the list\'s heading. If you leave this blank, the default title will be used.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'Content',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				)
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
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'active' => true,
		));

	}

// Specify a Google Maps API authentication key in ACF

	function my_acf_google_key() {

		$key = get_field('fad_google_key', 'option');

		if ($key) {

			acf_update_setting('google_api_key', $key);

		}

		// echo "<script> console.log('PHP: ".$key ."');</script>";

	}
	add_action('acf/init', 'my_acf_google_key');