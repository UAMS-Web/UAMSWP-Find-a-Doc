<?php

/*
 *  Meta Box Fields for UAMS-2016
 */

//register_activation_hook( __FILE__, 'prefix_create_table' );

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_5d433bd8e2794',
    'title' => 'Additional Information',
    'fields' => array(
      array(
        'key' => 'field_5d433bfaf4452',
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
    'key' => 'group_5d433c3148eb5',
    'title' => 'Additional Information',
    'fields' => array(
      array(
        'key' => 'field_5d433c48de6ae',
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
  
  // acf_add_local_field_group(array(
  //   'key' => 'group_5d433c80efcac',
  //   'title' => 'Service Information',
  //   'fields' => array(
  //     array(
  //       'key' => 'field_5d433c96ad3fc',
  //       'label' => 'Featured Content',
  //       'name' => 'service_line_content',
  //       'type' => 'wysiwyg',
  //       'instructions' => '',
  //       'required' => 0,
  //       'conditional_logic' => 0,
  //       'wrapper' => array(
  //         'width' => '',
  //         'class' => '',
  //         'id' => '',
  //       ),
  //       'acfe_permissions' => '',
  //       'default_value' => '',
  //       'tabs' => 'all',
  //       'toolbar' => 'full',
  //       'media_upload' => 1,
  //       'delay' => 0,
  //       'acfe_validate' => '',
  //       'acfe_update' => '',
  //     ),
  //     array(
  //       'key' => 'field_5d433cf5ad3fd',
  //       'label' => 'Featured Image',
  //       'name' => 'service_line_featured_image',
  //       'type' => 'image',
  //       'instructions' => '',
  //       'required' => 0,
  //       'conditional_logic' => 0,
  //       'wrapper' => array(
  //         'width' => '',
  //         'class' => '',
  //         'id' => '',
  //       ),
  //       'acfe_permissions' => '',
  //       'return_format' => 'array',
  //       'preview_size' => 'thumbnail',
  //       'library' => 'all',
  //       'min_width' => '',
  //       'min_height' => '',
  //       'min_size' => '',
  //       'max_width' => '',
  //       'max_height' => '',
  //       'max_size' => '',
  //       'mime_types' => '',
  //       'acfe_thumbnail' => 1,
  //       'acfe_validate' => '',
  //       'acfe_update' => '',
  //     ),
  //     array(
  //       'key' => 'field_5d433d29ad3fe',
  //       'label' => 'Header Image',
  //       'name' => 'service_line_header_image',
  //       'type' => 'image',
  //       'instructions' => '',
  //       'required' => 0,
  //       'conditional_logic' => 0,
  //       'wrapper' => array(
  //         'width' => '',
  //         'class' => '',
  //         'id' => '',
  //       ),
  //       'acfe_permissions' => '',
  //       'return_format' => 'array',
  //       'preview_size' => 'thumbnail',
  //       'library' => 'all',
  //       'min_width' => '',
  //       'min_height' => '',
  //       'min_size' => '',
  //       'max_width' => '',
  //       'max_height' => '',
  //       'max_size' => '',
  //       'mime_types' => '',
  //       'acfe_thumbnail' => 0,
  //       'acfe_validate' => '',
  //       'acfe_update' => '',
  //     ),
  //   ),
  //   'location' => array(
  //     array(
  //       array(
  //         'param' => 'taxonomy',
  //         'operator' => '==',
  //         'value' => 'service-line',
  //       ),
  //     ),
  //   ),
  //   'menu_order' => 0,
  //   'position' => 'normal',
  //   'style' => 'default',
  //   'label_placement' => 'left',
  //   'instruction_placement' => 'label',
  //   'hide_on_screen' => array(
  //     0 => 'the_content',
  //     1 => 'comments',
  //     2 => 'author',
  //     3 => 'format',
  //     4 => 'page_attributes',
  //     5 => 'categories',
  //     6 => 'tags',
  //     7 => 'send-trackbacks',
  //   ),
  //   'active' => true,
  //   'description' => '',
  //   'acfe_display_title' => '',
  //   'acfe_autosync' => '',
  //   'acfe_permissions' => '',
  //   'acfe_note' => '',
  //   'acfe_meta' => '',
  // ));
  
  endif;