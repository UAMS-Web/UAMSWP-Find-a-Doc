<?php

// Register 'Providers' Custom Post Type
function providers() {

	$labels = array(
		'name'                  => 'Providers',
		'singular_name'         => 'Provider',
		'menu_name'             => 'Providers',
		'name_admin_bar'        => 'Provider',
		'archives'              => 'Provider Archives',
		'attributes'            => 'Provider Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Providers',
		'add_new_item'          => 'Add New Provider',
		'add_new'               => 'Add New',
		'new_item'              => 'New Provider',
		'edit_item'             => 'Edit Provider',
		'update_item'           => 'Update Provider',
		'view_item'             => 'View Provider',
		'view_items'            => 'View Providers',
		'search_items'          => 'Search Providers',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Providers list',
		'items_list_navigation' => 'Providers list navigation',
		'filter_items_list'     => 'Filter Providers list',
	);
	$capabilities = array(
		'edit_post'      		=> "edit_physician",
        'read_post'      		=> "read_physician",
        'delete_post'        	=> "delete_physician",
        'edit_posts'         	=> "edit_physicians",
        'edit_others_posts'  	=> "edit_others_physicians",
        'publish_posts'      	=> "publish_physicians",
        'read_private_posts'    => "read_private_physicians",
        'read'                  => "read",
        'delete_posts'          => "delete_physicians",
        'delete_private_posts'  => "delete_private_physicians",
        'delete_published_posts' => "delete_published_physicians",
        'delete_others_posts'   => "delete_others_physicians",
        'edit_private_posts'    => "edit_private_physicians",
        'edit_published_posts'  => "edit_published_physicians",
	);
	$args = array(
		'label'                 => 'Provider',
		'description'           => 'UAMS Providers for Find-a-Doctor',
		'labels'                => $labels,
		'supports'              => array( 'title', 'author', 'thumbnail', 'revisions' ),
		'taxonomies'            => array( 'department', 'patient_type', 'treatment', 'medical_term', 'condition' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 30,
		'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/physicians-icon.png',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'slug'					=> 'provider',
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capabilities'          => $capabilities,
		'show_in_rest'          => true,
		'rest_base'             => 'provider',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);
	register_post_type( 'provider', $args );

}
add_action( 'init', 'providers', 0 );

if ( ! function_exists('locations') ) {

// Register Custom Post Type
function locations() {

	$labels = array(
		'name'                  => 'Locations',
		'singular_name'         => 'Location',
		'menu_name'             => 'Locations',
		'name_admin_bar'        => 'Location',
		'archives'              => 'Location Archives',
		'attributes'            => 'Location Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Locations',
		'add_new_item'          => 'Add New Location',
		'add_new'               => 'Add New',
		'new_item'              => 'New Location',
		'edit_item'             => 'Edit Location',
		'update_item'           => 'Update Location',
		'view_item'             => 'View Location',
		'view_items'            => 'View Locations',
		'search_items'          => 'Search Locations',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Locations list',
		'items_list_navigation' => 'Locations list navigation',
		'filter_items_list'     => 'Filter Locations list',
	);
	$capabilities = array(
		'edit_post'      		=> "edit_location",
        'read_post'      		=> "read_location",
        'delete_post'        	=> "delete_location",
        'edit_posts'         	=> "edit_locations",
        'edit_others_posts'  	=> "edit_others_locations",
        'publish_posts'      	=> "publish_locations",
        'read_private_posts'    => "read_private_locations",
        'read'                  => "read",
        'delete_posts'          => "delete_locations",
        'delete_private_posts'  => "delete_private_locations",
        'delete_published_posts' => "delete_published_locations",
        'delete_others_posts'   => "delete_others_locations",
        'edit_private_posts'    => "edit_private_locations",
        'edit_published_posts'  => "edit_published_locations",
	);
	$args = array(
		'label'                 => 'Location',
		'labels'                => $labels,
		'supports'              => array( 'title', 'author', 'thumbnail','revisions','page-attributes' ),
		'taxonomies'            => array( 'treatment', 'condition' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 30,
		'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/locations-icon.png',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capabilities'          => $capabilities,
		'show_in_rest'          => true,
		'rest_base'             => 'location',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);
	register_post_type( 'location', $args );

}
add_action( 'init', 'locations', 0 );

}

if ( ! function_exists('expertise_cpt') ) {
	// Register 'Areas of Expertise' Custom Post Type
	function expertise_cpt() {

		$labels = array(
			'name'                  => 'Areas of Expertise',
			'singular_name'         => 'Area of Expertise',
			'menu_name'             => 'Areas of Expertise',
			'name_admin_bar'        => 'Area of Expertise',
			'archives'              => 'Area of Expertise Archives',
			'attributes'            => 'Area of Expertise Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Areas of Expertise',
			'add_new_item'          => 'Add New Area of Expertise',
			'add_new'               => 'Add New',
			'new_item'              => 'New Area of Expertise',
			'edit_item'             => 'Edit Area of Expertise',
			'update_item'           => 'Update Area of Expertise',
			'view_item'             => 'View Area of Expertise',
			'view_items'            => 'View Areas of Expertise',
			'search_items'          => 'Search Areas of Expertise',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Areas of expertise list',
			'items_list_navigation' => 'Areas of expertise list navigation',
			'filter_items_list'     => 'Filter Areas of expertise list',
		);
		$capabilities = array(
			'edit_post'             => 'edit_expertise',
			'read_post'             => 'read_expertise',
			'delete_post'           => 'delete_expertise',
			'edit_posts'            => 'edit_expertises',
			'edit_others_posts'     => 'edit_others_expertises',
			'publish_posts'         => 'publish_expertises',
			'read_private_posts'    => 'read_private_expertises',
		);
		$rewrite = array(
			'slug'                  => 'expertise',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => 'Areas of Expertise',
			'description'           => 'UAMS Areas of Expertise', 
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ),
			'taxonomies'            => array( 'treatment', 'condition' ),
			'hierarchical'          => true,
			'capability_type' 		=> 'page',
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 30,
			'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/services-icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
			'show_in_rest'          => true,
			'rest_base'             => 'expertise',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'               => $rewrite,
		);
		register_post_type( 'expertise', $args );

	}
	add_action( 'init', 'expertise_cpt', 0 );
}

if ( ! function_exists('conditions_cpt') ) {

	// Register Custom Post Type
	function conditions_cpt() {
	
		$labels = array(
			'name'                  => 'Conditions',
			'singular_name'         => 'Condition',
			'menu_name'             => 'Conditions',
			'name_admin_bar'        => 'Condition',
			'archives'              => 'Condition Archives',
			'attributes'            => 'Condition Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Conditions',
			'add_new_item'          => 'Add New Condition',
			'add_new'               => 'Add New',
			'new_item'              => 'New Condition',
			'edit_item'             => 'Edit Condition',
			'update_item'           => 'Update Condition',
			'view_item'             => 'View Condition',
			'view_items'            => 'View Conditions',
			'search_items'          => 'Search Conditions',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Conditions list',
			'items_list_navigation' => 'Conditions list navigation',
			'filter_items_list'     => 'Filter Conditions list',
		);
		$capabilities = array(
			'edit_post'      		=> "edit_condition",
			'read_post'      		=> "read_condition",
			'delete_post'        	=> "delete_condition",
			'edit_posts'         	=> "edit_conditions",
			'edit_others_posts'  	=> "edit_others_conditions",
			'publish_posts'      	=> "publish_conditions",
			'read_private_posts'    => "read_private_conditions",
			'read'                  => "read",
			'delete_posts'          => "delete_conditions",
			'delete_private_posts'  => "delete_private_conditions",
			'delete_published_posts' => "delete_published_conditions",
			'delete_others_posts'   => "delete_others_conditions",
			'edit_private_posts'    => "edit_private_conditions",
			'edit_published_posts'  => "edit_published_conditions",
		);
		$args = array(
			'label'                 => 'Conditions',
			'labels'                => $labels,
			'supports'              => array( 'title', 'author', 'thumbnail','revisions','custom-fields' ),
			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 30,
			'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/locations-icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
			'show_in_rest'          => true,
			'rest_base'             => 'condition',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( 'condition', $args );
	
	}
	add_action( 'init', 'conditions_cpt', 0 );
	
}

if ( ! function_exists('treatments_cpt') ) {

	// Register Custom Post Type
	function treatments_cpt() {
	
		$labels = array(
			'name'                  => 'Treatments & Procedures',
			'singular_name'         => 'Treatment & Procedure',
			'menu_name'             => 'Treatments & Procedures',
			'name_admin_bar'        => 'Treatment & Procedure',
			'archives'              => 'Treatments & Procedure Archives',
			'attributes'            => 'Treatments & Procedure Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Treatments & Procedures',
			'add_new_item'          => 'Add New Treatment & Procedure',
			'add_new'               => 'Add New',
			'new_item'              => 'New Treatment & Procedure',
			'edit_item'             => 'Edit Treatment & Procedure',
			'update_item'           => 'Update Treatment & Procedure',
			'view_item'             => 'View Treatment & Procedure',
			'view_items'            => 'View Treatments & Procedures',
			'search_items'          => 'Search Treatments & Procedures',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Treatments & Procedures list',
			'items_list_navigation' => 'Treatments & Procedures list navigation',
			'filter_items_list'     => 'Filter Treatments & Procedures list',
		);
		$capabilities = array(
			'edit_post'      		=> "edit_treatment",
			'read_post'      		=> "read_treatment",
			'delete_post'        	=> "delete_treatment",
			'edit_posts'         	=> "edit_treatments",
			'edit_others_posts'  	=> "edit_others_treatments",
			'publish_posts'      	=> "publish_treatments",
			'read_private_posts'    => "read_private_treatments",
			'read'                  => "read",
			'delete_posts'          => "delete_treatments",
			'delete_private_posts'  => "delete_private_treatments",
			'delete_published_posts' => "delete_published_treatments",
			'delete_others_posts'   => "delete_others_treatments",
			'edit_private_posts'    => "edit_private_treatments",
			'edit_published_posts'  => "edit_published_treatments",
		);
		$args = array(
			'label'                 => 'Treatments & Procedures',
			'labels'                => $labels,
			'supports'              => array( 'title', 'author', 'thumbnail','revisions','custom-fields' ),
			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 30,
			'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/locations-icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
			'show_in_rest'          => true,
			'rest_base'             => 'treatment',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);
		register_post_type( 'treatment', $args );
	
	}
	add_action( 'init', 'treatments_cpt', 0 );
	
}

/*
 *
 * Register Taxonomies
 * 
 */
// Actions for Taxonomy - place in order for sub-menu
// Provider Taxonomies
// Clinical
// add_action( 'init', 'create_clinical_conditions_taxonomy', 0 );
// add_action( 'init', 'create_clinical_treatments_taxonomy', 0 );

add_action( 'init', 'create_affiliations_taxonomy', 0 );
add_action( 'init', 'create_institute_affiliations_taxonomy', 0 );
add_action( 'init', 'create_clinical_title_taxonomy', 0 );
add_action( 'init', 'create_clinical_admin_title_taxonomy', 0 );
add_action( 'init', 'create_languages_taxonomy', 0 );
add_action( 'init', 'create_departments_taxonomy', 0 );
add_action( 'init', 'create_degrees_taxonomy', 0 );
add_action( 'init', 'create_patient_type_taxonomy', 0 );
add_action( 'init', 'create_portal_taxonomy', 0 );
add_action( 'init', 'create_recognition_taxonomy', 0 );
add_action( 'init', 'create_service_line_taxonomy', 0 );
// add_action( 'init', 'create_medical_specialties_taxonomy', 0 ); // Disabled
// add_action( 'init', 'create_medical_terms_taxonomy', 0 ); // Disabled
// Academic
add_action( 'init', 'create_associations_taxonomy', 0 );
add_action( 'init', 'create_boards_taxonomy', 0 );
add_action( 'init', 'create_academic_departments_taxonomy', 0 );
add_action( 'init', 'create_academic_position_taxonomy', 0 );
add_action( 'init', 'create_academic_title_taxonomy', 0 );
add_action( 'init', 'create_academic_admin_title_taxonomy', 0 );
add_action( 'init', 'create_academic_college_taxonomy', 0 );
add_action( 'init', 'create_education_taxonomy', 0 );
add_action( 'init', 'create_schools_taxonomy', 0 );
// Locations Taxonomies
add_action( 'init', 'create_region_taxonomy', 0 );
add_action( 'init', 'create_location_type_taxonomy', 0 );

/* Taxonomy Functions */
function create_clinical_conditions_taxonomy() {
  	$labels = array(
		'name'                           => 'Conditions',
		'singular_name'                  => 'Condition',
		'search_items'                   => 'Search Conditions',
		'all_items'                      => 'All Conditions',
		'edit_item'                      => 'Edit Condition',
		'update_item'                    => 'Update Condition',
		'add_new_item'                   => 'Add New Condition',
		'new_item_name'                  => 'New Condition',
		'menu_name'                      => 'Conditions',
		'view_item'                      => 'View Condition',
		'popular_items'                  => 'Popular Condition',
		'separate_items_with_commas'     => 'Separate conditions with commas',
		'add_or_remove_items'            => 'Add or remove conditions',
		'choose_from_most_used'          => 'Choose from the most used conditions',
		'not_found'                      => 'No conditions found',
		'parent_item'                	 => 'Parent Condition',
		'parent_item_colon'          	 => 'Parent Condition:',
		'no_terms'                   	 => 'No Conditions',
		'items_list'                 	 => 'Condition list',
		'items_list_navigation'      	 => 'Condition list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'condition',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Conditions' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'condition',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'condition', array( 'provider' ), $args );

}

function create_clinical_treatments_taxonomy() {
  	$labels = array(
		'name'                           => 'Treatments & Procedures',
		'singular_name'                  => 'Treatments & Procedures',
		'search_items'                   => 'Search Treatments & Procedures',
		'all_items'                      => 'All Treatments & Procedures',
		'edit_item'                      => 'Edit Treatment or Procedure',
		'update_item'                    => 'Update Treatment or Procedure',
		'add_new_item'                   => 'Add New Treatment or Procedure',
		'new_item_name'                  => 'New Treatment or Procedure',
		'menu_name'                      => 'Treatments & Procedures',
		'view_item'                      => 'View Treatment or Procedure',
		'popular_items'                  => 'Popular Treatment or Procedure',
		'separate_items_with_commas'     => 'Separate Treatments or Procedures with commas',
		'add_or_remove_items'            => 'Add or remove Treatment or Procedure',
		'choose_from_most_used'          => 'Choose from the most used Treatments or Procedures',
		'not_found'                      => 'No Treatments or Procedures found',
		'parent_item'                	 => 'Parent Treatment or Procedure',
		'parent_item_colon'          	 => 'Parent Treatment or Procedure:',
		'no_terms'                   	 => 'No Treatments or Procedures',
		'items_list'                 	 => 'Treatment & Procedure list',
		'items_list_navigation'      	 => 'Treatment & Procedure list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'treatment',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Treatments' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'treatment',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'treatment', array( 'provider' ), $args );

}

// function create_medical_specialties_taxonomy() {
// 	$labels = array(
// 		'name'                       => 'Medical Specialties',
// 		'singular_name'              => 'Medical Specialty',
// 		'menu_name'                  => 'Medical Specialty',
// 		'all_items'                  => 'All Specialties',
// 		'parent_item'                => 'Parent Specialty',
// 		'parent_item_colon'          => 'Parent Specialty:',
// 		'new_item_name'              => 'New Specialty',
// 		'add_new_item'               => 'Add New Specialty',
// 		'edit_item'                  => 'Edit Specialty',
// 		'update_item'                => 'Update Specialty',
// 		'view_item'                  => 'View Specialty',
// 		'separate_items_with_commas' => 'Separate specialties with commas',
// 		'add_or_remove_items'        => 'Add or remove specialties',
// 		'choose_from_most_used'      => 'Choose from the most used',
// 		'popular_items'              => 'Popular Specialties',
// 		'search_items'               => 'Search Specialties',
// 		'not_found'                  => 'Not Found',
// 		'no_terms'                   => 'No Specialties',
// 		'items_list'                 => 'Specialties list',
// 		'items_list_navigation'      => 'Specialties list navigation',
// 	);
// 	$rewrite = array(
// 		'slug'                       => 'specialties',
// 		'with_front'                 => true,
// 		'hierarchical'               => true,
// 	);
// 	$capabilities = array(
// 		'manage_terms'               => 'manage_options',
// 		'edit_terms'                 => 'manage_options',
// 		'delete_terms'               => 'manage_options',
// 		'assign_terms'               => 'edit_physicians',
// 	);
// 	$args = array(
// 		'label' 					 => __( 'Medical Specialties' ),
// 		'labels'                     => $labels,
// 		'hierarchical'               => true,
// 		'public'                     => true,
// 		'show_ui'                    => true,
// 		'meta_box_cb'				 => false,
// 		'show_admin_column'          => false,
// 		'show_in_nav_menus'          => false,
// 		'show_tagcloud'              => false,
// 		'rewrite'                    => $rewrite,
// 		'capabilities'               => $capabilities,
// 		'show_in_rest'       		 => true,
//   		'rest_base'          		 => 'specialties',
//   		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
// 	);
// 	register_taxonomy( 'specialty', array( 'provider' ), $args );

// }

function create_departments_taxonomy() {
  $labels = array(
		'name'                           => 'Medical Departments',
		'singular_name'                  => 'Medical Departments',
		'search_items'                   => 'Search Departments',
		'all_items'                      => 'All Departments',
		'edit_item'                      => 'Edit Department',
		'update_item'                    => 'Update Department',
		'add_new_item'                   => 'Add New Department',
		'new_item_name'                  => 'New Department',
		'menu_name'                      => 'Medical Departments',
		'view_item'                      => 'View Department',
		'popular_items'                  => 'Popular Department',
		'separate_items_with_commas'     => 'Separate departments with commas',
		'add_or_remove_items'            => 'Add or remove departments',
		'choose_from_most_used'          => 'Choose from the most used departments',
		'not_found'                      => 'No departments found',
		'parent_item'                	 => 'Parent Department',
		'parent_item_colon'          	 => 'Parent Department:',
		'no_terms'                   	 => 'No Medical Departments',
		'items_list'                 	 => 'Medical Departments list',
		'items_list_navigation'      	 => 'Medical Departments list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'department',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Medical Departments' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'medical_department',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'department', array( 'provider' ), $args );

}

function create_service_line_taxonomy() {
  	$labels = array(
		'name'                           => 'Service Lines',
		'singular_name'                  => 'Service Lines',
		'search_items'                   => 'Search Service Lines',
		'all_items'                      => 'All Service Lines',
		'edit_item'                      => 'Edit Service Line',
		'update_item'                    => 'Update Service Line',
		'add_new_item'                   => 'Add New Service Line',
		'new_item_name'                  => 'New Service Line',
		'menu_name'                      => 'Service Lines',
		'view_item'                      => 'View Service Line',
		'popular_items'                  => 'Popular Service Line',
		'separate_items_with_commas'     => 'Separate service lines with commas',
		'add_or_remove_items'            => 'Add or remove service lines',
		'choose_from_most_used'          => 'Choose from the most used service lines',
		'not_found'                      => 'No service lines found',
		'parent_item'                	 => 'Parent Service Line',
		'parent_item_colon'          	 => 'Parent Service Line:',
		'no_terms'                   	 => 'No Service Lines',
		'items_list'                 	 => 'Service Lines list',
		'items_list_navigation'      	 => 'Service Lines list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'service-line',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Service Lines' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'service_line',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'service_line', array( 'provider' ), $args );

}

function create_degrees_taxonomy() {
  $labels = array(
		'name'                           => 'Medical Degrees',
		'singular_name'                  => 'Medical Degrees',
		'search_items'                   => 'Search Degrees',
		'all_items'                      => 'All Degrees',
		'edit_item'                      => 'Edit Degree',
		'update_item'                    => 'Update Degree',
		'add_new_item'                   => 'Add New Degree',
		'new_item_name'                  => 'New Degree',
		'menu_name'                      => 'Medical Degrees',
		'view_item'                      => 'View Degree',
		'popular_items'                  => 'Popular Degree',
		'separate_items_with_commas'     => 'Separate degrees with commas',
		'add_or_remove_items'            => 'Add or remove degrees',
		'choose_from_most_used'          => 'Choose from the most used degrees',
		'not_found'                      => 'No degrees found',
		'parent_item'                	 => 'Parent Degree',
		'parent_item_colon'          	 => 'Parent Degree:',
		'no_terms'                   	 => 'No Medical Degrees',
		'items_list'                 	 => 'Medical degrees list',
		'items_list_navigation'      	 => 'Medical degrees list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'degree',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Medical Degrees' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'medical_degree',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'degree', array( 'provider' ), $args );

}

function create_patient_type_taxonomy() {
  	$labels = array(
		'name'                           => 'Patient Types',
		'singular_name'                  => 'Patient Type',
		'search_items'                   => 'Search Types',
		'all_items'                      => 'All Types',
		'edit_item'                      => 'Edit Type',
		'update_item'                    => 'Update Type',
		'add_new_item'                   => 'Add New Type',
		'new_item_name'                  => 'New Type',
		'menu_name'                      => 'Patient Types',
		'view_item'                      => 'View Type',
		'popular_items'                  => 'Popular Type',
		'separate_items_with_commas'     => 'Separate types with commas',
		'add_or_remove_items'            => 'Add or remove types',
		'choose_from_most_used'          => 'Choose from the most used types',
		'not_found'                      => 'No types found',
		'parent_item'                	 => 'Parent Type',
		'parent_item_colon'          	 => 'Parent Type:',
		'no_terms'                   	 => 'No Patient Types',
		'items_list'                 	 => 'Patient types list',
		'items_list_navigation'      	 => 'Patient types list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'patient_type',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Patient Types' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'patient_type',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'patient_type', array( 'provider' ), $args );

}

function create_clinical_title_taxonomy() {
  	$labels = array(
		'name'                           => 'Clinical Titles',
		'singular_name'                  => 'Clinical Title',
		'search_items'                   => 'Search Titles',
		'all_items'                      => 'All Titles',
		'edit_item'                      => 'Edit Title',
		'update_item'                    => 'Update Title',
		'add_new_item'                   => 'Add New Title',
		'new_item_name'                  => 'New Title',
		'menu_name'                      => 'Clinical Titles',
		'view_item'                      => 'View Title',
		'popular_items'                  => 'Popular Title',
		'separate_items_with_commas'     => 'Separate Titles with commas',
		'add_or_remove_items'            => 'Add or remove Titles',
		'choose_from_most_used'          => 'Choose from the most used Titles',
		'not_found'                      => 'No Titles found',
		'parent_item'                	 => 'Parent Title',
		'parent_item_colon'          	 => 'Parent Title:',
		'no_terms'                   	 => 'No Clinical Titles',
		'items_list'                 	 => 'Clinical Titles list',
		'items_list_navigation'      	 => 'Clinical Titles list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'clinical_title',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Clinical Titles' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'clinical_title',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'clinical_title', array( 'provider' ), $args );

}

function create_clinical_admin_title_taxonomy() {
  	$labels = array(
		'name'                           => 'Clinical Administrative Titles',
		'singular_name'                  => 'Clinical Administrative Title',
		'search_items'                   => 'Search Titles',
		'all_items'                      => 'All Titles',
		'edit_item'                      => 'Edit Title',
		'update_item'                    => 'Update Title',
		'add_new_item'                   => 'Add New Title',
		'new_item_name'                  => 'New Title',
		'menu_name'                      => 'Clinical Administrative Titles',
		'view_item'                      => 'View Title',
		'popular_items'                  => 'Popular Title',
		'separate_items_with_commas'     => 'Separate Titles with commas',
		'add_or_remove_items'            => 'Add or remove Titles',
		'choose_from_most_used'          => 'Choose from the most used Titles',
		'not_found'                      => 'No Titles found',
		'parent_item'                	 => 'Parent Title',
		'parent_item_colon'          	 => 'Parent Title:',
		'no_terms'                   	 => 'No Clinical Administrative Titles',
		'items_list'                 	 => 'Clinical Administrative Titles list',
		'items_list_navigation'      	 => 'Clinical Administrative Titles list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'clinical_admin_title',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Clinical Administrative Titles' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'clinical_admin_title',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'clinical_admin_title', array( 'provider' ), $args );

}

function create_affiliations_taxonomy() {
  $labels = array(
		'name'                           => 'Hospital Affiliations',
		'singular_name'                  => 'Hospital Affiliations',
		'search_items'                   => 'Search Hospital Affiliations',
		'all_items'                      => 'All Hospital Affiliations',
		'edit_item'                      => 'Edit Hospital Affiliation',
		'update_item'                    => 'Update Hospital Affiliation',
		'add_new_item'                   => 'Add New Hospital Affiliation',
		'new_item_name'                  => 'New Hospital Affiliation',
		'menu_name'                      => 'Hospital Affiliations',
		'view_item'                      => 'View Hospital Affiliation',
		'popular_items'                  => 'Popular Hospital Affiliation',
		'separate_items_with_commas'     => 'Separate hospital affiliations with commas',
		'add_or_remove_items'            => 'Add or remove hospital affiliations',
		'choose_from_most_used'          => 'Choose from the most used hospital affiliations',
		'not_found'                      => 'No hospital affiliations found'
	);
  	$rewrite = array(
		'slug'                       => 'affiliation',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 				 	 => __( 'Hospital Affiliations' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true, //make true to add another
		'show_admin_column'          => false,
		'meta_box_cb' 				 => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
 		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'               => true,
		'rest_base'                  => 'affiliation',
		'rest_controller_class'      => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'affiliation', array( 'provider' ), $args );

}

function create_institute_affiliations_taxonomy() {
  $labels = array(
		'name'                           => 'Institute Affiliations',
		'singular_name'                  => 'Institute Affiliations',
		'search_items'                   => 'Search Institute Affiliations',
		'all_items'                      => 'All Institute Affiliations',
		'edit_item'                      => 'Edit Institute Affiliation',
		'update_item'                    => 'Update Institute Affiliation',
		'add_new_item'                   => 'Add New Institute Affiliation',
		'new_item_name'                  => 'New Institute Affiliation',
		'menu_name'                      => 'Institute Affiliations',
		'view_item'                      => 'View Institute Affiliation',
		'popular_items'                  => 'Popular Institute Affiliation',
		'separate_items_with_commas'     => 'Separate institute affiliations with commas',
		'add_or_remove_items'            => 'Add or remove institute affiliations',
		'choose_from_most_used'          => 'Choose from the most used institute affiliations',
		'not_found'                      => 'No institute affiliations found'
	);
  	$rewrite = array(
		'slug'                       => 'institute_affiliation',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 				 	 => __( 'Institute Affiliations' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true, //make true to add another
		'show_admin_column'          => false,
		'meta_box_cb' 				 => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
 		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'               => true,
		'rest_base'                  => 'institute_affiliation',
		'rest_controller_class'      => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'institute_affiliation', array( 'provider' ), $args );

}

function create_languages_taxonomy() {
  	$labels = array(
		'name'                           => 'Languages',
		'singular_name'                  => 'Languages',
		'search_items'                   => 'Search Languages',
		'all_items'                      => 'All Languages',
		'edit_item'                      => 'Edit Language',
		'update_item'                    => 'Update Language',
		'add_new_item'                   => 'Add New Language',
		'new_item_name'                  => 'New Language',
		'menu_name'                      => 'Languages',
		'view_item'                      => 'View Language',
		'popular_items'                  => 'Popular Language',
		'separate_items_with_commas'     => 'Separate languages with commas',
		'add_or_remove_items'            => 'Add or remove languages',
		'choose_from_most_used'          => 'Choose from the most used languages',
		'not_found'                      => 'No languages found'
	);
  	$rewrite = array(
		'slug'                       => 'language',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 				 	 => __( 'Languages' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true, //make true to add another
		'show_admin_column'          => false,
		'meta_box_cb' 				 => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
 		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'               => true,
		'rest_base'                  => 'language',
		'rest_controller_class'      => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'language', array( 'provider' ), $args );

}

// function create_medical_terms_taxonomy() {

// 	$labels = array(
// 		'name'                       => 'Medical Terms',
// 		'singular_name'              => 'Medical Term',
// 		'menu_name'                  => 'Medical Terms',
// 		'all_items'                  => 'All Terms',
// 		'parent_item'                => 'Parent Term',
// 		'parent_item_colon'          => 'Parent Term:',
// 		'new_item_name'              => 'New Term',
// 		'add_new_item'               => 'Add New Term',
// 		'edit_item'                  => 'Edit Term',
// 		'update_item'                => 'Update Term',
// 		'view_item'                  => 'View Term',
// 		'separate_items_with_commas' => 'Separate terms with commas',
// 		'add_or_remove_items'        => 'Add or remove terms',
// 		'choose_from_most_used'      => 'Choose from the most used',
// 		'popular_items'              => 'Popular Terms',
// 		'search_items'               => 'Search Terms',
// 		'not_found'                  => 'Not Found',
// 		'no_terms'                   => 'No Terms',
// 		'items_list'                 => 'Terms list',
// 		'items_list_navigation'      => 'Terms list navigation',
// 	);
// 	$rewrite = array(
// 		'slug'                       => 'medical-term',
// 		'with_front'                 => true,
// 		'hierarchical'               => false,
// 	);
// 	$capabilities = array(
// 		'manage_terms'               => 'manage_options',
// 		'edit_terms'                 => 'manage_options',
// 		'delete_terms'               => 'manage_options',
// 		'assign_terms'               => 'edit_physicians',
// 	);
// 	$args = array(
// 		'labels'                     => $labels,
// 		'hierarchical'               => true,
// 		'public'                     => true,
// 		'show_ui'                    => true,
// 		'show_admin_column'          => false,
// 		'show_in_nav_menus'          => false,
// 		'show_tagcloud'              => false,
// 		'rewrite'                    => $rewrite,
// 		'capabilities'               => $capabilities,
// 	);
// 	register_taxonomy( 'medical_term', array( 'provider' ), $args );

// }

function create_academic_position_taxonomy() {
	$labels = array(
		'name'                       => 'Position Types',
		'singular_name'              => 'Position Type',
		'menu_name'                  => 'Academic Position Types',
		'all_items'                  => 'All Position Types',
		'parent_item'                => 'Parent Position Type',
		'parent_item_colon'          => 'Parent Position Type:',
		'new_item_name'              => 'New Position Type',
		'add_new_item'               => 'Add New Position Type',
		'edit_item'                  => 'Edit Position Type',
		'update_item'                => 'Update Position Type',
		'view_item'                  => 'View Position Type',
		'separate_items_with_commas' => 'Separate position types with commas',
		'add_or_remove_items'        => 'Add or remove position types',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Position Types',
		'search_items'               => 'Search Position Types',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Position Types',
		'items_list'                 => 'Position Types list',
		'items_list_navigation'      => 'Position Types list navigation',
	);
	$rewrite = array(
		'slug'                       => 'academic-position',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,	//Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'academic_position', array( 'provider' ), $args );

}

function create_academic_college_taxonomy() {
	$labels = array(
		'name'                       => 'Colleges',
		'singular_name'              => 'College',
		'menu_name'                  => 'Colleges',
		'all_items'                  => 'All Colleges',
		'parent_item'                => 'Parent College',
		'parent_item_colon'          => 'Parent College:',
		'new_item_name'              => 'New College',
		'add_new_item'               => 'Add New College',
		'edit_item'                  => 'Edit College',
		'update_item'                => 'Update College',
		'view_item'                  => 'View College',
		'separate_items_with_commas' => 'Separate colleges with commas',
		'add_or_remove_items'        => 'Add or remove colleges',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Colleges',
		'search_items'               => 'Search Colleges',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Colleges',
		'items_list'                 => 'Colleges list',
		'items_list_navigation'      => 'Colleges list navigation',
	);
	$rewrite = array(
		'slug'                       => 'college',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'academic_college', array( 'provider' ), $args );

}

function create_schools_taxonomy() {
	$labels = array(
		'name'                       => 'Schools',
		'singular_name'              => 'School',
		'menu_name'                  => 'Schools',
		'all_items'                  => 'All Schools',
		'parent_item'                => 'Parent School',
		'parent_item_colon'          => 'Parent School:',
		'new_item_name'              => 'New School',
		'add_new_item'               => 'Add New School',
		'edit_item'                  => 'Edit School',
		'update_item'                => 'Update School',
		'view_item'                  => 'View School',
		'separate_items_with_commas' => 'Separate school with commas',
		'add_or_remove_items'        => 'Add or remove schools',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Schools',
		'search_items'               => 'Search Schools',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Schools',
		'items_list'                 => 'Schools list',
		'items_list_navigation'      => 'Schools list navigation',
	);
	$rewrite = array(
		'slug'                       => 'school',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'school', array( 'provider' ), $args );

}

function create_academic_departments_taxonomy() {
	$labels = array(
		'name'                           => 'Academic Departments',
		'singular_name'                  => 'Academic Departments',
		'search_items'                   => 'Search Departments',
		'all_items'                      => 'All Departments',
		'edit_item'                      => 'Edit Department',
		'update_item'                    => 'Update Department',
		'add_new_item'                   => 'Add New Department',
		'new_item_name'                  => 'New Department',
		'menu_name'                      => 'Academic Departments',
		'view_item'                      => 'View Department',
		'popular_items'                  => 'Popular Department',
		'separate_items_with_commas'     => 'Separate departments with commas',
		'add_or_remove_items'            => 'Add or remove departments',
		'choose_from_most_used'          => 'Choose from the most used departments',
		'not_found'                      => 'No departments found',
		'parent_item'					 => 'Parent Department',
		'parent_item_colon'				 => 'Parent Department:'
	);
  	$rewrite = array(
		'slug'                       => 'academic_department',
		'with_front'                 => false,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label'						 => __( 'Academic Departments' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'academic_department', array( 'provider' ), $args );

}

function create_boards_taxonomy() {
  	$labels = array(
		'name'                           => 'Boards',
		'singular_name'                  => 'Board',
		'search_items'                   => 'Search Boards',
		'all_items'                      => 'All Boards',
		'edit_item'                      => 'Edit Board',
		'update_item'                    => 'Update Board',
		'add_new_item'                   => 'Add New Board',
		'new_item_name'                  => 'New Board',
		'menu_name'                      => 'Academic Boards',
		'view_item'                      => 'View Board',
		'popular_items'                  => 'Popular Boards',
		'separate_items_with_commas'     => 'Separate boards with commas',
		'add_or_remove_items'            => 'Add or remove boards',
		'choose_from_most_used'          => 'Choose from the most used boards',
		'not_found'                      => 'No boards found'
	);
  	$rewrite = array(
		'slug'                       => 'board',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Boards' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'board', array( 'provider' ), $args );

}

function create_associations_taxonomy() {
  	$labels = array(
		'name'                           => 'Associations',
		'singular_name'                  => 'Association',
		'search_items'                   => 'Search Associations',
		'all_items'                      => 'All Associations',
		'edit_item'                      => 'Edit Association',
		'update_item'                    => 'Update Association',
		'add_new_item'                   => 'Add New Association',
		'new_item_name'                  => 'New Association',
		'menu_name'                      => 'Academic Associations',
		'view_item'                      => 'View Association',
		'popular_items'                  => 'Popular Associations',
		'separate_items_with_commas'     => 'Separate associations with commas',
		'add_or_remove_items'            => 'Add or remove associations',
		'choose_from_most_used'          => 'Choose from the most used associations',
		'not_found'                      => 'No associations found'
	);
  	$rewrite = array(
		'slug'                       => 'association',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Associations' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'association', array( 'provider' ), $args );

}

function create_education_taxonomy() {

	$labels = array(
		'name'                       => 'Education Types',
		'singular_name'              => 'Education Type',
		'menu_name'                  => 'Education Types',
		'all_items'                  => 'All Education Types',
		'parent_item'                => 'Parent Education Type',
		'parent_item_colon'          => 'Parent Education Type:',
		'new_item_name'              => 'New Education Type',
		'add_new_item'               => 'Add New Education Type',
		'edit_item'                  => 'Edit Education Type',
		'update_item'                => 'Update Education Type',
		'view_item'                  => 'View Education Type',
		'separate_items_with_commas' => 'Separate Education Type with commas',
		'add_or_remove_items'        => 'Add or remove Education Types',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Education Types',
		'search_items'               => 'Search Education Types',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Education Types',
		'items_list'                 => 'Education Types list',
		'items_list_navigation'      => 'Education Types list navigation',
	);
	$rewrite = array(
		'slug'                       => 'educationtype',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,  //Made true to add / edit
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'educationtype', array( 'provider' ), $args );

}

function create_portal_taxonomy() {
  	$labels = array(
		'name'                           => 'Portals',
		'singular_name'                  => 'Portal',
		'search_items'                   => 'Search Portals',
		'all_items'                      => 'All Portals',
		'edit_item'                      => 'Edit Portal',
		'update_item'                    => 'Update Portal',
		'add_new_item'                   => 'Add New Portal',
		'new_item_name'                  => 'New Portal',
		'menu_name'                      => 'Portals',
		'view_item'                      => 'View Portal',
		'popular_items'                  => 'Popular Portal',
		'separate_items_with_commas'     => 'Separate portals with commas',
		'add_or_remove_items'            => 'Add or remove portals',
		'choose_from_most_used'          => 'Choose from the most used portals',
		'not_found'                      => 'No portals found',
		'parent_item'                	 => 'Parent Portal',
		'parent_item_colon'          	 => 'Parent Portal:',
		'no_terms'                   	 => 'No Portals',
		'items_list'                 	 => 'Portals list',
		'items_list_navigation'      	 => 'Portals list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'portal',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Portals' ),
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'portal',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'portal', array( 'provider' ), $args );

}

function create_academic_title_taxonomy() {
  	$labels = array(
		'name'                           => 'Faculty Titles',
		'singular_name'                  => 'Faculty Title',
		'search_items'                   => 'Search Titles',
		'all_items'                      => 'All Titles',
		'edit_item'                      => 'Edit Title',
		'update_item'                    => 'Update Title',
		'add_new_item'                   => 'Add New Title',
		'new_item_name'                  => 'New Title',
		'menu_name'                      => 'Faculty Titles',
		'view_item'                      => 'View Title',
		'popular_items'                  => 'Popular Title',
		'separate_items_with_commas'     => 'Separate Titles with commas',
		'add_or_remove_items'            => 'Add or remove Titles',
		'choose_from_most_used'          => 'Choose from the most used Titles',
		'not_found'                      => 'No Titles found',
		'parent_item'                	 => 'Parent Title',
		'parent_item_colon'          	 => 'Parent Title:',
		'no_terms'                   	 => 'No Faculty Titles',
		'items_list'                 	 => 'Faculty Titles list',
		'items_list_navigation'      	 => 'Faculty Titles list navigation',
	);
  	$rewrite = array(
		'slug'                       => 'academic_title',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'label' 					 => __( 'Faculty Titles' ),
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'meta_box_cb'				 => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_rest'       		 => true,
  		'rest_base'          		 => 'academic_title',
  		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
	);
	register_taxonomy( 'academic_title', array( 'provider' ), $args );

}

function create_academic_admin_title_taxonomy() {
	$labels = array(
	  'name'                           => 'Academic Administrative Titles',
	  'singular_name'                  => 'Academic Administrative Title',
	  'search_items'                   => 'Search Titles',
	  'all_items'                      => 'All Titles',
	  'edit_item'                      => 'Edit Title',
	  'update_item'                    => 'Update Title',
	  'add_new_item'                   => 'Add New Title',
	  'new_item_name'                  => 'New Title',
	  'menu_name'                      => 'Academic Administrative Titles',
	  'view_item'                      => 'View Title',
	  'popular_items'                  => 'Popular Title',
	  'separate_items_with_commas'     => 'Separate Titles with commas',
	  'add_or_remove_items'            => 'Add or remove Titles',
	  'choose_from_most_used'          => 'Choose from the most used Titles',
	  'not_found'                      => 'No Titles found',
	  'parent_item'                	 => 'Parent Title',
	  'parent_item_colon'          	 => 'Parent Title:',
	  'no_terms'                   	 => 'No Academic Administrative Titles',
	  'items_list'                 	 => 'Academic Administrative Titles list',
	  'items_list_navigation'      	 => 'Academic Administrative Titles list navigation',
  );
	$rewrite = array(
	  'slug'                       => 'academic_admin_title',
	  'with_front'                 => true,
	  'hierarchical'               => true,
  );
  $capabilities = array(
	  'manage_terms'               => 'manage_options',
	  'edit_terms'                 => 'manage_options',
	  'delete_terms'               => 'manage_options',
	  'assign_terms'               => 'edit_physicians',
  );
  $args = array(
	  'label' 					 => __( 'Academic Administrative Titles' ),
	  'labels'                     => $labels,
	  'hierarchical'               => true,
	  'public'                     => true,
	  'show_ui'                    => true,
	  'meta_box_cb'				 => false,
	  'show_admin_column'          => false,
	  'show_in_nav_menus'          => false,
	  'show_tagcloud'              => false,
	  'rewrite'                    => $rewrite,
	  'capabilities'               => $capabilities,
	  'show_in_rest'       		 => true,
		'rest_base'          		 => 'academic_admin_title',
		'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
  );
  register_taxonomy( 'academic_admin_title', array( 'provider' ), $args );

}

function create_recognition_taxonomy() {

	$labels = array(
		'name'                       => 'Recognition Lists',
		'singular_name'              => 'Recognition List',
		'menu_name'                  => 'Recognition Lists',
		'all_items'                  => 'All Recognitions',
		'parent_item'                => 'Parent Recognition',
		'parent_item_colon'          => 'Parent Recognition:',
		'new_item_name'              => 'New Recognition',
		'add_new_item'               => 'Add New Recognition',
		'edit_item'                  => 'Edit Recognition',
		'update_item'                => 'Update Recognition',
		'view_item'                  => 'View Recognition',
		'separate_items_with_commas' => 'Separate recognitions with commas',
		'add_or_remove_items'        => 'Add or remove recognitions',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Recognitions',
		'search_items'               => 'Search Recognitions',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Recognitions',
		'items_list'                 => 'Recognitions list',
		'items_list_navigation'      => 'Recognitions list navigation',
	);
	$rewrite = array(
		'slug'                       => 'recognition',
		'with_front'                 => false,
		'hierarchical'               => false,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'recognition', array( 'provider' ), $args );

}

function create_region_taxonomy() {

	$labels = array(
		'name'                       => 'Regions',
		'singular_name'              => 'Region',
		'menu_name'                  => 'Regions',
		'all_items'                  => 'All Regions',
		'parent_item'                => 'Parent Region',
		'parent_item_colon'          => 'Parent Region:',
		'new_item_name'              => 'New Region',
		'add_new_item'               => 'Add New Region',
		'edit_item'                  => 'Edit Region',
		'update_item'                => 'Update Region',
		'view_item'                  => 'View Region',
		'separate_items_with_commas' => 'Separate Regions with commas',
		'add_or_remove_items'        => 'Add or remove Regions',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Regions',
		'search_items'               => 'Search Regions',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Regions',
		'items_list'                 => 'Regions list',
		'items_list_navigation'      => 'Regions list navigation',
	);
	$rewrite = array(
		'slug'                       => 'region',
		'with_front'                 => false,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'region', array( 'location' ), $args );

}

function create_location_type_taxonomy() {

	$labels = array(
		'name'                       => 'Location Types',
		'singular_name'              => 'Location Type',
		'menu_name'                  => 'Types',
		'all_items'                  => 'All Types',
		'parent_item'                => 'Parent Type',
		'parent_item_colon'          => 'Parent Type:',
		'new_item_name'              => 'New Type',
		'add_new_item'               => 'Add New Type',
		'edit_item'                  => 'Edit Type',
		'update_item'                => 'Update Type',
		'view_item'                  => 'View Type',
		'separate_items_with_commas' => 'Separate Types with commas',
		'add_or_remove_items'        => 'Add or remove Types',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Types',
		'search_items'               => 'Search Types',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Types',
		'items_list'                 => 'Types list',
		'items_list_navigation'      => 'Types list navigation',
	);
	$rewrite = array(
		'slug'                       => 'location_type',
		'with_front'                 => false,
		'hierarchical'               => true,
	);
	$capabilities = array(
		'manage_terms'               => 'manage_options',
		'edit_terms'                 => 'manage_options',
		'delete_terms'               => 'manage_options',
		'assign_terms'               => 'edit_physicians',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
	);
	register_taxonomy( 'location_type', array( 'location' ), $args );

}

/* Custom Roles */
function add_roles_on_plugin_activation() {
	add_role( 'doc_editor', 'Doc Profile Editor',
			array( 	'read' => true,
					'level_1' => true, // Dropdown as author support
					'read_physician' => true,
					'edit_physician' => true,
					'delete_physician' => true,
					'delete_published_physicians' => false,
					'edit_physicians' => true,
					'edit_published_physicians' => true,
					'edit_others_physicians' => false,
					'publish_physicians' => false,
					'read_private_physicians' => true,
					'read_location' => true,
					'edit_location' => true,
					'delete_location' => true,
					'delete_published_locations' => false,
					'edit_locations' => true,
					'edit_published_locations' => true,
					'edit_others_locations' => false,
					'publish_locations' => false,
					'read_private_locations' => true,
					'upload_files' => true,
					'edit_files' => true,

				)
	 );
	 add_role( 'doc_admin', 'Doc Profile Admin',
			array( 	'read' => true,
					'level_1' => true, // Dropdown as author support
					'read_physician' => true,
					'edit_physician' => true,
					'delete_physician' => true,
					'delete_published_physicians' => false,
					'edit_physicians' => true,
					'edit_published_physicians' => true,
					'edit_others_physicians' => true,
					'publish_physicians' => false,
					'read_private_physicians' => true,
					'read_location' => true,
					'edit_location' => true,
					'delete_location' => true,
					'delete_published_locations' => false,
					'edit_locations' => true,
					'edit_published_locations' => true,
					'edit_others_locations' => true,
					'publish_locations' => false,
					'read_private_locations' => true,
					'upload_files' => true,
					'edit_files' => true,
				)
		);
}
// register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );
add_action( 'init', 'add_roles_on_plugin_activation', 0 );

/* 
// Remove roles, if they need to be reset
function remove_roles_temp(){
	remove_role( 'doc_editor' );
	remove_role( 'doc_admin' );
}
add_action( 'init', 'remove_roles_temp' );
*/

function add_theme_caps() {
	// gets the author role
	$role = get_role( 'administrator' );

	// This only works, because it accesses the class instance.
	// would allow the author to edit others' posts for current theme only
	$role->add_cap( 'edit_others_posts' );
	$role->add_cap( 'edit_physician' );
	$role->add_cap( 'read_physician');
	$role->add_cap( 'delete_physician');
	$role->add_cap( 'delete_published_physicians');
	$role->add_cap( 'edit_physicians');
	$role->add_cap( 'edit_others_physicians');
	$role->add_cap( 'edit_published_physicians');
	$role->add_cap( 'publish_physicians');
	$role->add_cap( 'read_private_physicians');
	$role->add_cap( 'edit_location');
	$role->add_cap( 'read_location');
	$role->add_cap( 'delete_location');
	$role->add_cap( 'edit_locations');
	$role->add_cap( 'edit_others_locations');
	$role->add_cap( 'publish_locations');
	$role->add_cap( 'read_private_locations');
	$role->add_cap( 'edit_expertise');
	$role->add_cap( 'read_expertise');
	$role->add_cap( 'delete_expertise');
	$role->add_cap( 'edit_expertises');
	$role->add_cap( 'edit_others_expertises');
	$role->add_cap( 'publish_expertises');
	$role->add_cap( 'read_private_expertises');

}
add_action( 'admin_init', 'add_theme_caps');

// Remove the taxonomy metabox [slugnamediv]
function remove_provider_meta() {
	remove_meta_box( 'conditiondiv', 'provider', 'side' );
	remove_meta_box( 'specialtydiv', 'provider', 'side' );
	remove_meta_box( 'departmentdiv', 'provider', 'side' );
	remove_meta_box( 'patient_typediv', 'provider', 'side' );
	remove_meta_box( 'tagsdiv-medical_procedures', 'provider', 'side' );
	remove_meta_box( 'medical_termsdiv', 'provider', 'side' );
	remove_meta_box( 'tagsdiv-recognition', 'provider', 'side' );
	remove_meta_box( 'custom-post-type-onomies-locations', 'provider', 'side');
	// Location
	remove_meta_box( 'regiondiv', 'location', 'side' );
	remove_meta_box( 'location_typediv', 'location', 'side' );
}

add_action( 'admin_menu' , 'remove_provider_meta' );

add_action('admin_head', 'acf_hide_title');

function acf_hide_title() {
  echo '<style>
    .acf-field.hide-acf-title {
    border: none;
    padding: 6px 12px;
	}
	.hide-acf-title .acf-label {
	    display: none;
	}
	.acf-field.pbn { padding-bottom:0; }
  </style>';
}

/**
 * Changes strings referencing Featured Images for a post type
 *
 * In this example, the post type in the filter name is "employee"
 * and the new reference in the labels is "headshot".
 *
 * @see 	https://developer.wordpress.org/reference/hooks/post_type_labels_post_type/
 *
 * @param 		object 		$labels 		Current post type labels
 * @return 		object 					Modified post type labels
 */
function change_featured_image_labels_provider( $labels ) {

	$labels->featured_image 	= 'Headshot';
	$labels->set_featured_image 	= 'Set headshot';
	$labels->remove_featured_image 	= 'Remove headshot';
	$labels->use_featured_image 	= 'Use as headshot';

	return $labels;

} // change_featured_image_labels()

add_filter( 'post_type_labels_provider', 'change_featured_image_labels_provider', 10, 1 );


/**
 * Add REST API support to Teams Meta.
 */
function rest_api_provider_meta() {
    register_rest_field('provider', 'provider_meta', array(
            'get_callback' => 'get_provider_meta',
            'update_callback' => null,
            'schema' => null,
        )
	);
	register_rest_field('locations', 'location_meta', array(
			'get_callback' => 'get_location_meta',
			'update_callback' => null,
			'schema' => null,
		)
	);
}
function get_provider_meta($object) {
    $postId = $object['id'];
    //Provider
    $data['physician_first_name'] = get_field('physician_first_name', $postId);
    $data['physician_middle_name'] = get_field( 'physician_middle_name', $postId );
    $data['physician_last_name'] = get_field( 'physician_last_name', $postId );
	$degrees = get_field('physician_degree', $postId );
	$degree_list = '';
	$i = 1;
	if ( $degrees ) {
		foreach( $degrees as $degree ):
			$degree_name = get_term( $degree, 'degree');
			$degree_list .= $degree_name->name;
			if( count($degrees) > $i ) {
				$degree_list .= ", ";
			}
			$i++;
		endforeach; 
	} 
	$full_name = get_field('physician_first_name', $postId) .' ' .(get_field('physician_middle_name', $postId) ? get_field('physician_middle_name', $postId) . ' ' : '') . get_field('physician_last_name', $postId) . (get_field('physician_pedigree', $postId) ? '&nbsp;' . get_field('physician_pedigree', $postId ) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
	$data['physician_full_name'] = $full_name;
	//Physician Data
	// $clinical_title = (get_field('physician_title', $id) ? get_term( get_field('physician_title', $id), 'clinical_title' )->name : '');
	$data['physician_title'] = (get_field('physician_title', $postId) ? get_term( get_field('physician_title', $postId), 'clinical_title' )->name : '');
	// $data['physician_service_line'] = get_field( 'physician_title', $postId );
    $data['physician_clinical_bio'] = get_field( 'physician_clinical_bio', $postId );
    $data['physician_short_clinical_bio'] = wp_trim_words( get_field( 'physician_short_clinical_bio', $postId ), 30, ' &hellip;' );
    // $data['physician_gender'] = get_field( 'physician_gender', $postId );
    // $data['physician_youtube_link'] = get_field( 'physician_youtube_link', $postId );
    // $data['physician_languages'] = get_field( 'physician_languages', $postId );
    //$data['physician_locations_id'] = get_post_meta( $postId, 'physician_locations', true );
    //$data['physician_locations']['link'] = get_permalink( get_post_meta( $postId, 'physician_locations', true ) );
    //$data['physician_locations']['title'] = get_the_title( get_post_meta( $postId, 'physician_locations', true ) );
    //$data['physician_locations']['slug'] = get_post_field( 'post_name', get_post_meta( $postId, 'physician_locations', true ) );
    //Locations
    $i = 1;
	foreach (get_post_meta( $postId, 'physician_locations', true ) as $location) {
		$data['physician_locations'][$location]['link'] = get_permalink( $location );
		$data['physician_locations'][$location]['title'] = get_the_title( $location );
		$data['physician_locations'][$location]['slug'] = get_post_field( 'post_name', $location );
		// $data['physician_locations'][$location]['location_address_1'] = get_post_meta( $location, 'location_address_1', true );
		// $data['physician_locations'][$location]['location_address_2'] = get_post_meta( $location, 'location_address_2', true );
		// $data['physician_locations'][$location]['location_city'] = get_post_meta( $location, 'location_city', true );
		// $data['physician_locations'][$location]['location_state'] = get_post_meta( $location, 'location_state', true );
		// $data['physician_locations'][$location]['location_zip'] =  get_post_meta( $location, 'location_zip', true );
		// $data['physician_locations'][$location]['location_phone'] = get_post_meta( $location, 'location_phone', true );
		// $data['physician_locations'][$location]['location_fax'] = get_post_meta( $location, 'location_fax', true );
		// $data['physician_locations'][$location]['location_email'] = get_post_meta( $location, 'location_email', true );
		// $data['physician_locations'][$location]['location_web_name'] = get_post_meta( $location, 'location_web_name', true );
		// $data['physician_locations'][$location]['location_url'] = get_post_meta( $location, 'location_url', true );
		$map = get_post_meta( $location, 'location_map', true );
		$data['physician_locations'][$location]['location_lat'] = $map['lat'];
		$data['physician_locations'][$location]['location_lng'] = $map['lng'];
		$data['physician_locations'][$location]['location_directions'] = '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/'. $map['lat'] .','. $map['lng'] .'" target="_blank" aria-label="Get Directions to '. get_the_title( $location ) .'">Get Directions</a>';
		// $data['physician_locations'][$location]['map_marker'] = '<div class="marker" data-lat="'. $map['lat'] .'" data-lng="'. $map['lng'] .'" data-label="'. $i .'"></div>';
		$i++;
		//$data['location_link'][$location] = get_post_permalink( $location );
		//$data['location_title'] .= get_the_title( $location ) . ',';
	}
    // $data['physician_affiliation'] = get_post_meta( $postId, 'physician_affiliation', true );
    // $data['physician_appointment_link'] = get_post_meta( $postId, 'physician_appointment_link', true );
    // $data['physician_primary_care'] = get_post_meta( $postId, 'physician_primary_care', true );
    // $data['physician_refferal_required'] = get_post_meta( $postId, 'physician_refferal_required', true );
    // $data['physician_accepting_patients'] = get_post_meta( $postId, 'physician_accepting_patients', true );
    // $data['physician_second_opinion'] = get_post_meta( $postId, 'physician_second_opinion', true );
    // $data['physician_patient_types'] = get_the_terms( $postId, 'patient_type' );
    $data['physician_npi'] = get_post_meta( $postId, 'physician_npi', true );
    // $data['medical_specialties'] = get_the_terms( $postId, 'specialty' );
	// $data['pphoto'] = wp_get_attachment_url( get_post_meta( $postId, 'physician_photo', true ), 'file' );
	$data['physician_photo'] = image_sizer(get_post_thumbnail_id($postId), 253, 337, 'center', 'center');
	$conditions = get_field('physician_conditions', $postId);
	$condition_list = '';
	$i = 1;
	if( $conditions ) {
        $args = (array(
            'taxonomy' => 'condition',
            'hide_empty' => false,
            'term_taxonomy_id' => $conditions
        ));
		$conditions_query = new WP_Term_Query( $args );

		foreach( $conditions_query->get_terms() as $condition ):
			$condition_list .= $condition->name;
			if( count($conditions) > $i ) {
				$condition_list .= ', ';
			}
			$i++;
		 endforeach;
	}
	$data['physician_conditions_list'] = $condition_list;
	$treatments = get_field('physician_treatments', $postId);
	$treatment_list = '';
	$i = 1;
	if( $treatments ) {
		$args = (array(
			'taxonomy' => 'treatment',
			'hide_empty' => false,
			'term_taxonomy_id' => $treatments
		));
		$treatments_query = new WP_Term_Query( $args );
		foreach( $treatments_query->get_terms() as $treatment ):
			$treatment_list .= $treatment->name;
			if( count($treatments) > $i ) {
				$treatment_list .= ', ';
			}
			$i++;
		endforeach;
	}
	$data['physician_treatments_list'] = $treatment_list;
	$data['physician_conditions'] = get_the_terms( $postId, 'condition' );
	$data['physician_treatments'] = get_the_terms( $postId, 'treatment' );
	// $data['physician_boards'] = get_post_meta( $postId, 'physician_boards', true );
	// if( get_post_meta( $postId, 'physician_boards', true ) ) :
	// 	for( $i = 0; $i < get_post_meta( $postId, 'physician_boards', true ); $i++ ){
	// 		$data['physician_board_name'][$i] =  get_post_meta( $postId, 'physician_boards_' . $i .'_physician_board_name', true );
	// 	}
	// endif;
	//Academic Data
	// $data['physician_academic_title'] = get_post_meta( $postId, 'physician_academic_title', true );
	// $data['physician_academic_college'] = get_the_terms( $postId, 'academic_college' );
	// $data['physician_academic_position'] = get_the_terms( $postId, 'academic_position' );
	// $data['physician_academic_bio'] = get_post_meta( $postId, 'physician_academic_bio', true );
	// $data['physician_academic_short_bio'] = wp_trim_words( get_post_meta( $postId, 'physician_academic_short_bio', true ), 30, ' &hellip;' );
	// $data['physician_academic_office'] = get_post_meta( $postId, 'physician_academic_office', true );
	// $data['physician_academic_map'] = get_post_meta( $postId, 'physician_academic_map', true );
	// $data['physician_research_profiles_link'] = get_post_meta( $postId, 'physician_research_profiles_link', true );
	// $data['physician_pubmed_author_id'] = get_post_meta( $postId, 'physician_pubmed_author_id', true );
	// $data['pubmed_author_number'] = get_post_meta( $postId, 'pubmed_author_number', true );
	// if( get_post_meta( $postId, 'physician_publications', true ) ) :
	// 	$i = 0;
	// 	foreach (get_post_meta( $postId, 'physician_publications', true ) as $publication) {
	// 		$data['physician_publication'][$i] = get_post_meta( $postId, 'physician_publications_' . $i .'_publication_pubmed_info', true );
	// 		$i++;
	// 	}
	// endif;
	// if( get_post_meta( $postId, 'physician_contact_infomation', true ) ) :
	// 	for ( $i = 0; $i < get_post_meta( $postId, 'physician_contact_infomation', true ); $i++ ){
	// 		$data['office_full'][$i] = get_post_meta( $postId, 'physician_contact_infomation_' . $i . '_office_contact_type', true ) . ': ' . get_post_meta( $postId, 'physician_contact_infomation_' . $i . '_office_contact_value', true );
	// 		$data['office_contact_type'][$i] = get_post_meta( $postId, 'physician_contact_infomation_' . $i . '_office_contact_type', true );
	// 		$data['office_contact_value'][$i] =  get_post_meta( $postId, 'physician_contact_infomation_' . $i . '_office_contact_value', true );
	// 	}
	// endif;
	// if( get_post_meta( $postId, 'physician_academic_appointment', true ) ) :
	// 	for ( $i = 0; $i < get_post_meta( $postId, 'physician_academic_appointment', true ); $i++ ){
	// 		$data['physician_academic_appointment'][$i] = get_post_meta( $postId, 'physician_academic_appointment_' . $i .'_academic_title', true ) . ': ' . get_post_meta( $postId, 'physician_academic_appointment_' . $i .'_academic_department', true );
	// 		//$data['academic_title'][$i] = get_post_meta( $postId, 'physician_academic_appointment_' . $i .'_academic_title', true );
	// 		//$data['academic_department'][$i] =  get_post_meta( $postId, 'physician_academic_appointment_' . $i .'_academic_department', true );
	// 	}
	// endif;
	// if( get_post_meta( $postId, 'physician_education', true ) ) :
	// 	for ( $i = 0; $i < get_post_meta( $postId, 'physician_education', true ); $i++ ){
	// 		$data['physician_education'][$i] = get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_type', true ) . ': ' . get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_school', true ) . ' ' . get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_description', true );
	// 		//$data['physician_education_type'][$i] = get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_type', true );
	// 		//$data['physician_education_school'][$i] = get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_school', true );
	// 		//$data['physician_education_description'][$i] =  get_post_meta( $postId, 'physician_education_' . $i .'_physician_education_description', true );
	// 	}
	// endif;
	// //Research
	// $data['physician_researcher_bio'] = get_post_meta( $postId, 'physician_researcher_bio', true );
	// $data['physician_research_interests'] = get_post_meta( $postId, 'physician_research_interests', true );
	// //Additional
	// if( get_post_meta( $postId, 'physician_awards', true ) ) :
	// 	for ( $i = 0; $i < get_post_meta( $postId, 'physician_awards', true ); $i++ ){
	// 		$data['physician_awards'][$i] = get_post_meta( $postId, 'physician_awards_' . $i .'_award_title', true ) . ' (' . get_post_meta( $postId, 'physician_awards_' . $i .'_award_year', true ) . ') ' . get_post_meta( $postId, 'physician_awards_' . $i .'_award_infor', true );
	// 		//$data['award_year'][$i] = get_post_meta( $postId, 'physician_awards_' . $i .'_award_year', true );
	// 		//$data['award_title'][$i] = get_post_meta( $postId, 'physician_awards_' . $i .'_award_title', true );
	// 		//$data['award_infor'][$i] =  get_post_meta( $postId, 'physician_awards_' . $i .'_award_infor', true );
	// 	}
	// endif;
	// $data['physician_additional_info'] = get_post_meta( $postId, 'physician_additional_info', true );

    return $data;
}
add_action('rest_api_init', 'rest_api_provider_meta');

function get_location_meta($object) {
	$postId = $object['id'];
	$data['location_title'] = get_the_title( $postId );
	$data['location_link'] = get_permalink($postId );
	$data['location_photo'] = get_the_post_thumbnail($postId, 'aspect-16-9-small', ['class' => 'card-img-top']);
	$map = $map = get_field('location_map', $postId );
	$data['location_lat'] = $map['lat'];
	$data['location_lng'] = $map['lng'];
	$data['location_address_1'] = get_field('location_address_1', $postId );
	$data['location_address_2'] = ( get_field('location_address_2', $postId ) ? get_field('location_address_2', $postId ) . '<br/>' : '');
	$data['location_city'] = get_field('location_city', $postId );
	$data['location_state'] = get_field('location_state', $postId );
	$data['location_zip'] = get_field('location_zip', $postId );
	$data['locations_phone_title'] = 'Appointment Phone Number'. ( get_field('field_location_appointment_phone_query', $postId) ? 's' : '' );
	$data['location_phone'] = get_field( 'location_phone', $postId );
	$data['location_phone_text'] = '<span class="subtitle">New and Returning Patients</span>';
	$dash['location_new_appointments_phone'] = get_field('location_new_appointments_phone', $postId );
	$data['location_new_appointments_phonetext'] = ( get_field('location_new_appointments_phone', $postId ) && get_field( 'field_location_appointment_phone_query', $postId )) ? '<br/><span class="subtitle">New Patients</span>' : '<span class="subtitle">New and Returning Patients</span>';
	$dash['location_return_appointments_phone'] = get_field('location_return_appointments_phone', $postId );
	$data['location_return_appointments_phone_text'] = (get_field('location_return_appointments_phone', $postId ) && get_field('field_location_appointment_phone_query', $postId )) ? '<span class="subtitle">Returning Patients</span>' : '';
	$data['location_directions_link'] = '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/'. $map['lat'] .','. $map['lng'] .'" target="_blank" aria-label="Get Directions to '. get_the_title($postId) .'">Get Directions</a>';

	return $data;

}
// add_action('rest_api_init', 'rest_api_location_meta');

// Add REST API query var filters
add_filter('rest_query_vars', 'provider_add_rest_query_vars');
function provider_add_rest_query_vars($query_vars) {
    $query_vars = array_merge( $query_vars, array('meta_key', 'meta_value', 'meta_compare') );
    return $query_vars;
}