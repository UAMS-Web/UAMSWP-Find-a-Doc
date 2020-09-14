<?php

/*
 *
 * Custom ACF Blocks
 * 
 */

add_action('acf/init', 'uams_fad_register_blocks');
function uams_fad_register_blocks() {

    // check function exists.
    if( function_exists('acf_register_block_type') ) {
        acf_register_block_type(array(
            'name'              => 'fad-locations',
            'title'             => __('UAMS Find-a-Doc Locations'),
            'description'       => __('Filtered Clinical Locations'),
            'category'          => 'common',
            'icon'              => 'location',
            'keywords'          => array('uams', 'clinical', 'locations', 'clinics'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => UAMS_FAD_PATH . '/templates/blocks/locations.php',
        ));
        acf_register_block_type(array(
            'name'              => 'fad-providers',
            'title'             => __('UAMS Find-a-Doc Providers'),
            'description'       => __('Filtered Providers'),
            'category'          => 'common',
            'icon'              => 'id',
            'keywords'          => array('uams', 'providers', 'doctors', 'physicians'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => UAMS_FAD_PATH . 'templates/blocks/providers.php',
        ));
    }
}

if( function_exists('acf_add_local_field_group') ):

    // Add local field group for UAMS Location Block
    acf_add_local_field_group(array(
        'key' => 'group_block_fad_locations',
        'title' => 'Block: UAMS FaD Locations',
        'fields' => array(
            array(
                'key' => 'field_block_fad_locations_intro',
                'label' => '',
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
                'message' => '<h2>UAMS Clinical Locations</h2>',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_block_fad_locations_heading',
                'label' => 'Heading',
                'name' => 'block_fad_locations_heading',
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
                'key' => 'field_block_fad_locations_description',
                'label' => 'Body',
                'name' => 'block_fad_locations_description',
                'type' => 'textarea',
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
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_block_fad_locations_background_color',
                'label' => 'Background Color',
                'name' => 'block_fad_locations_background_color',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'bg-white' => 'White',
                    'bg-gray' => 'Gray',
                ),
                'default_value' => array(
                    0 => 'bg-white',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_block_fad_locations_filter_type',
                'label' => 'Location Type(s)',
                'name' => 'block_fad_locations_filter_type',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'location_type',
                'field_type' => 'multi_select',
                'allow_null' => 0,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'id',
                'multiple' => 0,
            ),
            // array(
            //     'key' => 'field_block_fad_locations_filter_aoe',
            //     'label' => 'Areas of Expertise',
            //     'name' => 'block_fad_locations_filter_aoe',
            //     'type' => 'post_object',
            //     'instructions' => '',
            //     'required' => 1,
            //     'conditional_logic' => 0,
            //     'wrapper' => array(
            //         'width' => '',
            //         'class' => '',
            //         'id' => '',
            //     ),
            //     'taxonomy' => '',
            //     'post_type' => array(
            //         0 => 'expertise',
            //     ),
            //     'field_type' => 'multi_select',
            //     'allow_null' => 0,
            //     'add_term' => 0,
            //     'save_terms' => 0,
            //     'load_terms' => 0,
            //     'return_format' => 'id',
            //     'multiple' => 1,
            //     'ui' => 1,
            // ),
            array(
                'key' => 'field_block_fad_locations_filter_region',
                'label' => 'Region(s)',
                'name' => 'block_fad_locations_filter_region',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'region',
                'field_type' => 'multi_select',
                'allow_null' => 0,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'id',
                'multiple' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/fad-locations',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Provider Block
    acf_add_local_field_group(array(
        'key' => 'group_block_fad_providers',
        'title' => 'Block: UAMS FaD Providers',
        'fields' => array(
            array(
                'key' => 'field_block_fad_providers_intro',
                'label' => '',
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
                'message' => '<h2>UAMS Clinical Providers</h2>',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_block_fad_providers_heading',
                'label' => 'Heading',
                'name' => 'block_fad_providers_heading',
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
                'key' => 'field_block_fad_providers_description',
                'label' => 'Body',
                'name' => 'block_fad_providers_description',
                'type' => 'textarea',
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
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_block_fad_providers_background_color',
                'label' => 'Background Color',
                'name' => 'block_fad_providers_background_color',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'bg-white' => 'White',
                    'bg-gray' => 'Gray',
                ),
                'default_value' => array(
                    0 => 'bg-white',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_block_fad_providers_filter_ids',
                'label' => 'Providers',
                'name' => 'block_fad_providers_filter_ids',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'provider',
                ),
                'taxonomy' => '',
                'filters' => array(
                    0 => 'search',
                ),
                'elements' => '',
                'min' => '',
                'max' => '',
                'return_format' => 'id',
            ),
            array(
                'key' => 'field_block_fad_providers_count',
                'label' => 'Cards Per Row',
                'name' => 'block_fad_providers_count',
                'type' => 'select',
                'instructions' => 'Set the maximum number of provider cards per row at the largest viewport size.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    '4' => '4',
                    '6' => '6',
                    '8' => '8',
                    '10' => '10',
                    '12' => '12',
                ),
                'default_value' => array(
                    0 => '12',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            // array(
            //     'key' => 'field_block_fad_providers_filter_type',
            //     'label' => 'Provider Type(s)',
            //     'name' => 'block_fad_providers_filter_type',
            //     'type' => 'taxonomy',
            //     'instructions' => '',
            //     'required' => 1,
            //     'conditional_logic' => 0,
            //     'wrapper' => array(
            //         'width' => '',
            //         'class' => '',
            //         'id' => '',
            //     ),
            //     'taxonomy' => 'provider_type',
            //     'field_type' => 'multi_select',
            //     'allow_null' => 0,
            //     'add_term' => 0,
            //     'save_terms' => 0,
            //     'load_terms' => 0,
            //     'return_format' => 'id',
            //     'multiple' => 0,
            // ),
            // array(
            //     'key' => 'field_block_fad_providers_filter_aoe',
            //     'label' => 'Areas of Expertise',
            //     'name' => 'block_fad_providers_filter_aoe',
            //     'type' => 'post_object',
            //     'instructions' => '',
            //     'required' => 1,
            //     'conditional_logic' => 0,
            //     'wrapper' => array(
            //         'width' => '',
            //         'class' => '',
            //         'id' => '',
            //     ),
            //     'taxonomy' => '',
            //     'post_type' => array(
            //         0 => 'expertise',
            //     ),
            //     'field_type' => 'multi_select',
            //     'allow_null' => 0,
            //     'add_term' => 0,
            //     'save_terms' => 0,
            //     'load_terms' => 0,
            //     'return_format' => 'id',
            //     'multiple' => 1,
            //     'ui' => 1,
            // ),
            // array(
            //     'key' => 'field_block_fad_providers_filter_region',
            //     'label' => 'Region(s)',
            //     'name' => 'block_fad_providers_filter_region',
            //     'type' => 'taxonomy',
            //     'instructions' => '',
            //     'required' => 1,
            //     'conditional_logic' => 0,
            //     'wrapper' => array(
            //         'width' => '',
            //         'class' => '',
            //         'id' => '',
            //     ),
            //     'taxonomy' => 'region',
            //     'field_type' => 'multi_select',
            //     'allow_null' => 0,
            //     'add_term' => 0,
            //     'save_terms' => 0,
            //     'load_terms' => 0,
            //     'return_format' => 'id',
            //     'multiple' => 0,
            // ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/fad-providers',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

endif;