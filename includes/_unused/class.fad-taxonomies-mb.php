<?php

declare(strict_types=1);

/*
 *  Meta Box Fields for UAMS-2016
 */

// register_activation_hook( __FILE__, 'prefix_create_table' );

global $wpdb;
add_filter('rwmb_meta_boxes', 'uams_taxonomies_register_meta_boxes');

function uams_taxonomies_register_meta_boxes($meta_boxes)
{

    $meta_boxes[] = [
        'title' => 'Additional Information',
        'taxonomies' => 'academic_colleges', // List of taxonomies. Array or string

        'fields' => [
            [
                'name' => 'Website URL',
                'id' => 'college_url',
                'type' => 'url',
                'size' => 40,
                'columns' => 12,
            ],
        ],
        'validation' => [
            'rules' => [
                'college_url' => [
                    'required' => true,
                ],
            ],
        ],
    ];

    $meta_boxes[] = [
        'title' => '',
        'taxonomies' => 'specialty', // List of taxonomies. Array or string

        'fields' => [
            [
                'name' => 'Link to Specialty Page',
                'id' => 'specialty_url',
                'type' => 'url',
                'size' => 40,
                'columns' => 12,
            ],
        ],
    ];

    $meta_boxes[] = [
        'title' => '',
        'taxonomies' => 'service-line', // List of taxonomies. Array or string

        'fields' => [
            [
                'type' => 'custom_html',
                'std' => '<style>.term-description-wrap{display:none;}#edittag .rwmb-wysiwyg-wrapper .rwmb-input{width:100%;} #addtag .rwmb-meta-box{display:none;}</style>',
            ],
            [
                'name' => 'Featured Content',
                'id' => 'service_line_content',
                'type' => 'wysiwyg',
            ],
            [
                'name' => 'Featured Image',
                'id' => 'service_line_featured_image',
                'type' => 'image_advanced',
                'max_file_uploads' => 1,
            ],
            [
                'name' => 'Header Image',
                'id' => 'service_line_header_image',
                'type' => 'image_advanced',
                'max_file_uploads' => 1,
            ],
        ],
    ];

    return $meta_boxes;

}
