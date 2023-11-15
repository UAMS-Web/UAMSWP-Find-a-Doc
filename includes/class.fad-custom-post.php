<?php

// Register Custom Post Types

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

	// Register 'Locations' Custom Post Type

		if ( ! function_exists('locations') ) {

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

	// Register 'Areas of Expertise' Custom Post Type

		if ( ! function_exists('expertise_cpt') ) {
			
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

	// Register 'Conditions' Custom Post Type

		if ( ! function_exists('conditions_cpt') ) {

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
					'supports'              => array( 'title', 'editor', 'author', 'thumbnail','revisions','custom-fields' ),
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

	// Register 'Treatments' Custom Post Type

		if ( ! function_exists('treatments_cpt') ) {

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
					'supports'              => array( 'title', 'editor', 'author', 'thumbnail','revisions','custom-fields' ),
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

	// Register 'Clinical Resources' Custom Post Type

		if ( ! function_exists('clinical_resources_cpt') ) {

			function clinical_resources_cpt() {
			
				$labels = array(
					'name'                  => 'Clinical Resources',
					'singular_name'         => 'Clinical Resource',
					'menu_name'             => 'Clinical Resources',
					'name_admin_bar'        => 'Resource',
					'archives'              => 'Resource Archives',
					'attributes'            => 'Resource Attributes',
					'parent_item_colon'     => 'Parent Resource:',
					'all_items'             => 'All Resources',
					'add_new_item'          => 'Add New Resource',
					'add_new'               => 'Add New',
					'new_item'              => 'New Resource',
					'edit_item'             => 'Edit Resource',
					'update_item'           => 'Update Resource',
					'view_item'             => 'View Resource',
					'view_items'            => 'View Resources',
					'search_items'          => 'Search Resources',
					'uploaded_to_this_item' => 'Uploaded to this item',
					'items_list'            => 'Resources list',
					'items_list_navigation' => 'Resources list navigation',
					'filter_items_list'     => 'Filter Resources list',
				);
				$capabilities = array(
					'edit_post'      		=> "edit_clinical_resource",
					'read_post'      		=> "read_clinical_resource",
					'delete_post'        	=> "delete_clinical_resource",
					'edit_posts'         	=> "edit_clinical_resources",
					'edit_others_posts'  	=> "edit_others_clinical_resources",
					'publish_posts'      	=> "publish_clinical_resources",
					'read_private_posts'    => "read_private_clinical_resources",
					'read'                  => "read",
					'delete_posts'          => "delete_clinical_resources",
					'delete_private_posts'  => "delete_private_clinical_resources",
					'delete_published_posts' => "delete_published_clinical_resources",
					'delete_others_posts'   => "delete_others_clinical_resources",
					'edit_private_posts'    => "edit_private_clinical_resources",
					'edit_published_posts'  => "edit_published_clinical_resources",
				);
				$args = array(
					'label'                 => 'Resources',
					'labels'                => $labels,
					'supports'              => array( 'title', 'author', 'revisions','custom-fields' ),
					'taxonomies'            => array(),
					'hierarchical'          => false,
					'public'                => true,
					'show_ui'               => true,
					'show_in_menu'          => true,
					'menu_position'         => 30,
					'menu_icon'             => plugin_dir_url( __FILE__ ) .'../admin/admin-icons/trl-cube.svg',
					'show_in_admin_bar'     => true,
					'show_in_nav_menus'     => true,
					'can_export'            => true,
					'has_archive'           => true,
					'exclude_from_search'   => false,
					'publicly_queryable'    => true,
					'capabilities'          => $capabilities,
					'show_in_rest'          => true,
					'rest_base'             => 'clinical_resource',
					'rest_controller_class' => 'WP_REST_Posts_Controller',
				);
				register_post_type( 'clinical-resource', $args );
			
			}
			
			add_action( 'init', 'clinical_resources_cpt', 0 );
			
		}

// Register Taxonomies

	// Actions for Taxonomy

		/**
		 * Place in order for sub-menu
		 */

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
				add_action( 'init', 'create_gmb_cat_provider_taxonomy', 0 );
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
				add_action( 'init', 'create_residency_years_taxonomy', 0 );

		// Locations Taxonomies

			add_action( 'init', 'create_region_taxonomy', 0 );
			add_action( 'init', 'create_location_type_taxonomy', 0 );
			add_action( 'init', 'create_building_taxonomy', 0 );
			add_action( 'init', 'create_gmb_cat_location_taxonomy', 0 );

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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'academic_college', array( 'provider' ), $args );

}

function create_schools_taxonomy() {
	$labels = array(
		'name'                       => 'Education and Training Organizations',
		'singular_name'              => 'Education and Training Organization',
		'menu_name'                  => 'Education and Training Organizations',
		'all_items'                  => 'All Education and Training Organizations',
		'parent_item'                => 'Parent Education and Training Organization',
		'parent_item_colon'          => 'Parent Education and Training Organization:',
		'new_item_name'              => 'New Education and Training Organization',
		'add_new_item'               => 'Add New Education and Training Organization',
		'edit_item'                  => 'Edit Education and Training Organization',
		'update_item'                => 'Update Education and Training Organization',
		'view_item'                  => 'View Education and Training Organization',
		'separate_items_with_commas' => 'Separate Education and Training Organization with commas',
		'add_or_remove_items'        => 'Add or remove Education and Training Organizations',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Education and Training Organizations',
		'search_items'               => 'Search Education and Training Organizations',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Education and Training Organizations',
		'items_list'                 => 'Education and Training Organizations list',
		'items_list_navigation'      => 'Education and Training Organizations list navigation',
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
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'school', array( 'provider' ), $args );

}


function create_residency_years_taxonomy() {
	$labels = array(
		'name'                       => 'Residency Years',
		'singular_name'              => 'Residency Year',
		'menu_name'                  => 'Residency Years',
		'all_items'                  => 'All Residency Years',
		'parent_item'                => 'Parent Residency Year',
		'parent_item_colon'          => 'Parent Residency Year:',
		'new_item_name'              => 'New Residency Year',
		'add_new_item'               => 'Add New Residency Year',
		'edit_item'                  => 'Edit Residency Year',
		'update_item'                => 'Update Residency Year',
		'view_item'                  => 'View Residency Year',
		'separate_items_with_commas' => 'Separate residency year with commas',
		'add_or_remove_items'        => 'Add or remove residency years',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Residency Year',
		'search_items'               => 'Search Residency Year',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Residency Year',
		'items_list'                 => 'Residency Year list',
		'items_list_navigation'      => 'Residency Year list navigation',
	);
	$rewrite = array(
		'slug'                       => 'residency_year',
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
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'residency_year', array( 'provider' ), $args );

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
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'academic_department', array( 'provider' ), $args );

}

function create_boards_taxonomy() {
  	$labels = array(
		'name'                           => 'Boards and Certifications',
		'singular_name'                  => 'Board or Certification',
		'search_items'                   => 'Search Boards and Certifications',
		'all_items'                      => 'All Boards and Certifications',
		'edit_item'                      => 'Edit Board or Certification',
		'update_item'                    => 'Update Board or Certification',
		'add_new_item'                   => 'Add New Board or Certification',
		'new_item_name'                  => 'New Board or Certification',
		'menu_name'                      => 'Boards and Certifications',
		'view_item'                      => 'View Board or Certification',
		'popular_items'                  => 'Popular Boards and Certifications',
		'separate_items_with_commas'     => 'Separate boards and certifications with commas',
		'add_or_remove_items'            => 'Add or remove boards and certifications',
		'choose_from_most_used'          => 'Choose from the most used boards and certifications',
		'not_found'                      => 'No boards or certifications found'
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
		'label' 					 => __( 'Boards and Certifications' ),
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'association', array( 'provider' ), $args );

}

function create_education_taxonomy() {

	$labels = array(
		'name'                       => 'Education and Training Types',
		'singular_name'              => 'Education and Training Type',
		'menu_name'                  => 'Education and Training Types',
		'all_items'                  => 'All Education and Training Types',
		'parent_item'                => 'Parent Education and Training Type',
		'parent_item_colon'          => 'Parent Education and Training Type:',
		'new_item_name'              => 'New Education and Training Type',
		'add_new_item'               => 'Add New Education and Training Type',
		'edit_item'                  => 'Edit Education and Training Type',
		'update_item'                => 'Update Education and Training Type',
		'view_item'                  => 'View Education and Training Type',
		'separate_items_with_commas' => 'Separate Education and Training Type with commas',
		'add_or_remove_items'        => 'Add or remove Education and Training Types',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Education and Training Types',
		'search_items'               => 'Search Education and Training Types',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Education and Training Types',
		'items_list'                 => 'Education and Training Types list',
		'items_list_navigation'      => 'Education and Training Types list navigation',
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
	  'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => false,
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
		'show_in_quick_edit'         => true,
	);
	register_taxonomy( 'location_type', array( 'location' ), $args );

}

function create_gmb_cat_provider_taxonomy() {

	$labels = array(
		'name'                       => 'Google My Business Categories for Providers',
		'singular_name'              => 'Google My Business Category for Providers',
		'menu_name'                  => 'Google My Business Categories',
		'all_items'                  => 'All Categories',
		'parent_item'                => 'Parent Category',
		'parent_item_colon'          => 'Parent Category:',
		'new_item_name'              => 'New Category',
		'add_new_item'               => 'Add New Category',
		'edit_item'                  => 'Edit Category',
		'update_item'                => 'Update Category',
		'view_item'                  => 'View Category',
		'separate_items_with_commas' => 'Separate Categories with commas',
		'add_or_remove_items'        => 'Add or remove Categories',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Categories',
		'search_items'               => 'Search Categories',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Categories',
		'items_list'                 => 'Categories list',
		'items_list_navigation'      => 'Categories list navigation',
	);
	$rewrite = array(
		'slug'                       => 'gmb_cat_provider',
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
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_quick_edit'         => true,
	);
	register_taxonomy( 'gmb_cat_provider', array( 'provider' ), $args );

}

function create_gmb_cat_location_taxonomy() {

	$labels = array(
		'name'                       => 'Google My Business Categories for Locations',
		'singular_name'              => 'Google My Business Category for Locations',
		'menu_name'                  => 'Google My Business Categories',
		'all_items'                  => 'All Categories',
		'parent_item'                => 'Parent Category',
		'parent_item_colon'          => 'Parent Category:',
		'new_item_name'              => 'New Category',
		'add_new_item'               => 'Add New Category',
		'edit_item'                  => 'Edit Category',
		'update_item'                => 'Update Category',
		'view_item'                  => 'View Category',
		'separate_items_with_commas' => 'Separate Categories with commas',
		'add_or_remove_items'        => 'Add or remove Categories',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Categories',
		'search_items'               => 'Search Categories',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Categories',
		'items_list'                 => 'Categories list',
		'items_list_navigation'      => 'Categories list navigation',
	);
	$rewrite = array(
		'slug'                       => 'gmb_cat_location',
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
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_quick_edit'         => true,
	);
	register_taxonomy( 'gmb_cat_location', array( 'location' ), $args );

}

function create_building_taxonomy() {
	// Plugin assumes there is a 'None' taxonomy item with slug '_none'

	$labels = array(
		'name'                       => 'Buildings',
		'singular_name'              => 'Building',
		'menu_name'                  => 'Buildings',
		'all_items'                  => 'All Buildings',
		'parent_item'                => 'Parent Building',
		'parent_item_colon'          => 'Parent Building:',
		'new_item_name'              => 'New Building',
		'add_new_item'               => 'Add New Building',
		'edit_item'                  => 'Edit Building',
		'update_item'                => 'Update Building',
		'view_item'                  => 'View Building',
		'separate_items_with_commas' => 'Separate Buildings with commas',
		'add_or_remove_items'        => 'Add or remove Buildings',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Buildings',
		'search_items'               => 'Search Buildings',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No Buildings',
		'items_list'                 => 'Buildings list',
		'items_list_navigation'      => 'Buildings list navigation',
	);
	$rewrite = array(
		'slug'                       => 'building',
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
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
		'capabilities'               => $capabilities,
		'show_in_quick_edit'         => false,
	);
	register_taxonomy( 'building', array( 'location' ), $args );

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
	$role->add_cap( 'edit_condition');
	$role->add_cap( 'read_condition');
	$role->add_cap( 'delete_condition');
	$role->add_cap( 'edit_conditions');
	$role->add_cap( 'edit_others_conditions');
	$role->add_cap( 'publish_conditions');
	$role->add_cap( 'read_private_conditions');
	$role->add_cap( 'edit_treatment');
	$role->add_cap( 'read_treatment');
	$role->add_cap( 'delete_treatment');
	$role->add_cap( 'edit_treatments');
	$role->add_cap( 'edit_others_treatments');
	$role->add_cap( 'publish_treatments');
	$role->add_cap( 'read_private_treatments');
	$role->add_cap( 'edit_clinical-resource');
	$role->add_cap( 'read_clinical-resource');
	$role->add_cap( 'delete_clinical-resource');
	$role->add_cap( 'edit_clinical-resources');
	$role->add_cap( 'edit_others_clinical-resources');
	$role->add_cap( 'publish_clinical-resources');
	$role->add_cap( 'read_private_clinical-resources');
	$role->add_cap( 'edit_clinical_resource');
	$role->add_cap( 'read_clinical_resource');
	$role->add_cap( 'delete_clinical_resource');
	$role->add_cap( 'edit_clinical_resources');
	$role->add_cap( 'edit_others_clinical_resources');
	$role->add_cap( 'publish_clinical_resources');
	$role->add_cap( 'read_private_clinical_resources');
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
	remove_meta_box( 'gmb_cat_providerdiv', 'provider', 'side' );
	// Location
	remove_meta_box( 'regiondiv', 'location', 'side' );
	remove_meta_box( 'location_typediv', 'location', 'side' );
	remove_meta_box( 'buildingdiv', 'location', 'side' );
	remove_meta_box( 'gmb_cat_locationdiv', 'location', 'side' );
}

add_action( 'admin_menu' , 'remove_provider_meta' );

add_action('admin_head', 'acf_hide_title');

function acf_hide_title() {
  echo '<style>
    .acf-field.hide-acf-title {
    border: none;
    padding: 6px 12px;
	}
    .acf-field.hide-acf-border,
	.acf-field[data-width]+.acf-field[data-width].hide-acf-border {
    border-color: transparent;
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
	register_rest_field('location', 'location_meta', array(
			'get_callback' => 'get_location_meta',
			'update_callback' => null,
			'schema' => null,
		)
	);
	register_rest_field('expertise', 'expertise_meta', array(
			'get_callback' => 'get_expertise_meta',
			'update_callback' => null,
			'schema' => null,
		)
	);
	register_rest_field('condition', 'condition_meta', array(
			'get_callback' => 'get_condition_meta',
			'update_callback' => null,
			'schema' => null,
		)
	);
	register_rest_field('treatment', 'treatment_meta', array(
			'get_callback' => 'get_treatment_meta',
			'update_callback' => null,
			'schema' => null,
		)
	);
	register_rest_field('clinical-resource', 'resource_meta', array(
			'get_callback' => 'get_resource_meta',
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
	if ( $degrees ) {
		foreach( $degrees as $degree ):
			$degree_name = get_term( $degree, 'degree');
			$degree_list .= $degree_list != '' ? ', ' : '';
			$degree_list .= $degree_name->name;
		endforeach; 
	} 
	$full_name = get_field('physician_first_name', $postId) .' ' .(get_field('physician_middle_name', $postId) ? get_field('physician_middle_name', $postId) . ' ' : '') . get_field('physician_last_name', $postId) . (get_field('physician_pedigree', $postId) ? '&nbsp;' . get_field('physician_pedigree', $postId ) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
	$physician_resident = get_field('physician_resident', $postId);
	$physician_resident_name = 'Resident Physician';
	$physician_title = get_field('physician_title', $postId);
	$physician_title_name = $physician_resident ? $physician_resident_name : get_term( $physician_title, 'clinical_title' )->name;
	$physician_service_line = get_field('physician_service_line', $postId);
	$resident_profile_group = get_field('physician_resident_profile_group',$postId);
	$resident_academic_department = $resident_profile_group['physician_resident_academic_department'];
    $resident_academic_department_name = get_term( $resident_academic_department, 'academic_department' )->name;
    $resident_academic_chief = $resident_profile_group['physician_resident_academic_chief'];
    $resident_academic_chief_name = $resident_academic_chief ? 'Chief Resident' : '';
    $resident_academic_year = $resident_profile_group['physician_resident_academic_year'];
    $resident_academic_year_name = get_term( $resident_academic_year, 'residency_year' )->name;
    $resident_academic_name = $resident_academic_chief ? $resident_academic_chief_name : $resident_academic_year_name;
	$data['physician_full_name'] = $full_name;
	//Physician Data
	$data['physician_title'] = $physician_title_name; //(get_field('physician_title', $postId) ? get_term( get_field('physician_title', $postId), 'clinical_title' )->name : '');
	$data['physician_service_line'] = $physician_service_line ? get_term( $physician_service_line, 'service_line' )->name : '';
    $data['physician_clinical_bio'] = get_field( 'physician_clinical_bio', $postId );
    $data['physician_short_clinical_bio'] = get_field('physician_short_clinical_bio', $postId) ? get_field( 'physician_short_clinical_bio', $postId) : wp_trim_words( get_field( 'physician_clinical_bio', $postId ), 30, ' &hellip;' );
    $data['physician_gender'] = get_field( 'physician_gender', $postId );
	$data['physician_accepting_new_patients'] = get_post_meta( $postId, 'physician_accepting_patients', true );
    $data['physician_second_opinion'] = get_field( 'physician_second_opinion', $postId );
    $patients = get_field('physician_patient_types',$postId);
    $patient_list = '';
    if( $patients ) {
	    foreach( $patients as $patient ):
        	$patient_name = get_term( $patient, 'patient_type');
        	$patient_list .= $patient_list != '' ? ', ' : '';
        	$patient_list .= $patient_name->name;
        endforeach;
    }
    $data['physician_patient_types'] = $patient_list;
    $data['physician_npi'] = get_field( 'physician_npi', $postId );
    $data['physician_youtube_link'] = get_field( 'physician_youtube_link', $postId );
	$data['physician_hidden'] = get_field( 'physician_hidden', $postId );
	$podcast_name = get_field('physician_podcast_name',$postId);
	$data['provider_podcast'] = '<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
	</script>
	<script type="text/javascript">
		radiomd_embedded_filtered_doctor("uams","radiomd-embedded-filtered-doctor",303,1837,"' . $podcast_name . '");
	</script>
	<style type="text/css">
		#radiomd-embedded-filtered-tag iframe {
		width: 100%;
		border: none;
	}
	</style>
	<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>';
	$languages = get_field('physician_languages',$postId);
	$language_list = '';
	if ( $languages ) {
		foreach( $languages as $language ):
			$language_name = get_term( $language, 'language');
			$language_list .= $language_list != '' ? ', ' : '';
			$language_list .= $language_name->name;
		endforeach;
	}
    $data['physician_languages'] = $language_list;
	$data['physician_eligible_appointments'] = $physician_resident ? 0 : get_field('physician_eligible_appointments',$postId);
	$data['physician_thumbnail'] = image_sizer(get_post_thumbnail_id($postId), 253, 337, 'center', 'center');
	$data['physician_photo'] = image_sizer(get_post_thumbnail_id(), 778, 1038, 'center', 'center');
	$data['physician_referral_required'] = get_field('physician_referral_required', $postId);
	$physician_portal = get_field('physician_portal', $postId);
	$portal = get_term($physician_portal, "portal");
	$data['physician_portal']['name'] = $portal->name;
	$data['physician_portal']['content'] = get_field('portal_content', $portal);
	$data['physician_portal']['url'] = get_field('portal_url', $portal);
    //$data['physician_clinical_admin_title'] = get_field('physician_clinical_admin_title', $postId);
    $data['physician_clinical_focus'] = get_field('physician_clinical_focus',$postId);
    //$data['physician_awards'] = get_field('physician_awards',$postId);
    //$data['physician_additional_info'] = get_field('physician_additional_info',$postId);
	// Academic
	//$data['physician_college_affiliation'] = get_field('physician_academic_college',$postId);
    $data['physician_academic_bio'] = get_field('physician_academic_bio',$postId);
	$educations = get_field('physician_education',$postId);
	if( ! empty( $educations ) ){
		$i = 0;
		foreach ($educations as $education) {
			$data['physician_education'][$i]['type'] = get_term( $education['education_type'], 'educationtype')->name;
			$data['physician_education'][$i]['school'] = get_term( $education['school'], 'school')->name;
			$data['physician_education'][$i]['description'] = $education['description'];
			$i++;
		}
	}
    $academic_appointments = get_field('physician_academic_appointment',$postId);
	if( ! empty( $academic_appointments ) ){
		$i = 0;
		foreach ($academic_appointments as $academic_appointment) {
			$data['physician_faculty_appointment'][$i]['department'] = get_term( $academic_appointment['department'], 'academic_department')->name;
			$data['physician_faculty_appointment'][$i]['title'] = get_term( $academic_appointment['academic_title_tax'], 'academic_title')->name;
			$i++;
		}
	}
    $academic_admin_titles = get_field('physician_academic_admin_title',$postId);
	if( ! empty( $academic_admin_titles ) ){
		$i = 0;
		foreach ($academic_admin_titles as $academic_admin_title) {
			$data['physician_academic_admin_role'][$i]['department'] = get_term( $academic_admin_title['department'], 'academic_department')->name;
			$data['physician_academic_admin_role'][$i]['title'] = get_term( $academic_admin_title['academic_admin_title_tax'], 'academic_admin_title')->name;
			$i++;
		}
	}
	$data['physician_residency_program'] = $physician_resident ? $resident_academic_department_name .', '. $resident_academic_name : '';
    $boards = get_field('physician_boards',$postId);
	$data['physician_boards'] ='';
	if( ! empty( $boards ) ):
		foreach ( $boards as $board ) :
			$board_name = get_term( $board, 'board');
			$data['physician_boards'] .= $data['physician_boards'] != '' ? ',' : '';
			$data['physician_boards'] .= $board_name->name;
		endforeach;
	endif;
    $associations = get_field('physician_associations',$postId);
	$data['physician_associations'] ='';
	if( ! empty( $associations ) ):
		foreach ( $associations as $association ) :
			$association_name = get_term( $association, 'association');
			$data['physician_associations'] .= $data['physician_associations'] != '' ? ',' : '';
			$data['physician_associations'] .= $association_name->name;
		endforeach;
	endif;
    // Locations
	$i = 1;
	$locations = get_field('physician_locations', $postId);
	foreach ($locations as $location) {
		$data['physician_locations'][$location]['link'] = get_permalink( $location );
		$data['physician_locations'][$location]['title'] = get_the_title( $location );
		$data['physician_locations'][$location]['slug'] = get_post_field( 'post_name', $location );
		// $data['physician_locations'][$location]['location_address_1'] = get_post_meta( $location, 'location_address_1', true );
		// $data['physician_locations'][$location]['location_address_2'] = get_post_meta( $location, 'location_address_2', true );
		// $data['physician_locations'][$location]['location_city'] = get_post_meta( $location, 'location_city', true );
		// $data['physician_locations'][$location]['location_state'] = get_post_meta( $location, 'location_state', true );
		// $data['physician_locations'][$location]['location_zip'] =  get_post_meta( $location, 'location_zip', true );
		$data['physician_locations'][$location]['locations_phone_title'] = 'Clinic Phone Number';
		$data['physician_locations'][$location]['location_clinic_phone'] = get_field( 'location_phone', $location );
		$location_clinic_phone_query = get_field('location_clinic_phone_query', $location ); // separate number for (new) appointments?
		if ($location_clinic_phone_query) {
			$location_new_appointments_phone = get_field('location_new_appointments_phone', $location ); // phone number for (new) appointments
			$location_appointment_phone_query = get_field('field_location_appointment_phone_query', $location ); // separate number for existing appointments?
		} else {
			$location_new_appointments_phone = '';
			$location_appointment_phone_query = '0';
		}
		if ($location_appointment_phone_query) {
			$location_return_appointments_phone = get_field('location_return_appointments_phone', $location ); // phone number for existing appointments
		} else {
			$location_return_appointments_phone = '';
		}
		$data['physician_locations'][$location]['location_phone_text'] = 'Appointment Phone Number'. ( $location_clinic_phone_query ? 's' : '' );
		$data['physician_locations'][$location]['location_new_appointments_phone'] = $location_new_appointments_phone;
		$data['physician_locations'][$location]['location_new_appointments_phonetext'] = ( $location_new_appointments_phone && $location_clinic_phone_query) ? 'New Patients' : 'New and Returning Patients';
		$data['physician_locations'][$location]['location_return_appointments_phone'] = $location_return_appointments_phone;
		$data['physician_locations'][$location]['location_return_appointments_phone_text'] = ($location_return_appointments_phone && $location_clinic_phone_query) ? 'Returning Patients' : '';
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
		// $data['location_link'][$location] = get_post_permalink( $location );
		// $data['location_title'] .= get_the_title( $location ) . ',';
	}
	// Conditions
	$conditions_cpt = get_field('physician_conditions_cpt', $postId);
	$condition_list = '';
	$i = 1;
	if( $conditions_cpt ) {
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );

		if( $conditions_cpt && $conditions_cpt_query->posts ):

			foreach( $conditions_cpt_query->posts as $condition ):
				$data['physician_conditions'][$condition->ID]['link'] = get_permalink( $condition->ID );
				$data['physician_conditions'][$condition->ID]['title'] = $condition->post_title;
				$data['physician_conditions'][$condition->ID]['slug'] = $condition->post_name;
				$condition_list .= $condition->post_title;
				if( count($conditions_cpt) > $i ) {
					$condition_list .= ', ';
				}
				$i++;
			endforeach;
		endif; 
	}
	$data['physician_conditions_list'] = $condition_list;
	// Treatments
	$treatments_cpt = get_field('physician_treatments_cpt', $postId);
	$treatment_list = '';
	$i = 1;
	if( $treatments_cpt ) {
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
		if( $treatments_cpt && $treatments_cpt_query->posts ):

			foreach( $treatments_cpt_query->posts as $treatment ):
				$data['physician_treatments'][$treatment->ID]['link'] = get_permalink( $treatment->ID );
				$data['physician_treatments'][$treatment->ID]['title'] = $treatment->post_title;
				$data['physician_treatments'][$treatment->ID]['slug'] = $treatment->post_name;
				$treatment_list .= $treatment->post_title;
				if( count($treatments_cpt) > $i ) {
					$treatment_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['physician_treatments_list'] = $treatment_list;
	// Expertise
	$expertises = get_field('physician_expertise', $postId);
	$expertise_list = '';
	$i = 1;
	if( $expertises ) {
		$args = (array(
			'post_type' => "expertise",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $expertises
		));
		$expertise_query = new WP_Query( $args );
		if( $expertises && $expertise_query->posts ):

			foreach( $expertise_query->posts as $expertise ):
				$data['physician_expertise'][$expertise->ID]['link'] = get_permalink( $expertise->ID );
				$data['physician_expertise'][$expertise->ID]['title'] = $expertise->post_title;
				$data['physician_expertise'][$expertise->ID]['slug'] = $expertise->post_name;
				$expertise_list .= $expertise->post_title;
				if( count($expertises) > $i ) {
					$expertise_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['physician_expertise_list'] = $expertise_list;

	// Research
    $data['physician_research_bio'] = get_field('physician_research_bio',$postId);
    $data['physician_research_interests'] = get_field('physician_research_interests',$postId);
    $data['physician_research_profiles_link'] = get_field('physician_research_profiles_link',$postId);
	$publications = get_field('physician_select_publications',$postId);
	foreach( $publications as $publication) {
		$data['physician_pubmed'][$i]['information'] = $publication['pubmed_information'];
	}
    $data['physician_pubmed_author_id'] = get_field('physician_pubmed_author_id',$postId);
	$pubmed_author_number = get_field('physician_author_number',$postId);
    $data['physician_pubmed_count'] = $pubmed_author_number ? $pubmed_author_number : '3';

    return $data;
}
add_action('rest_api_init', 'rest_api_provider_meta');

function get_location_meta($object) {
	$postId = $object['id'];
	$data['location_title'] = get_the_title( $postId );
	$data['location_link'] = get_permalink($postId );
	// Parent Location 
	$location_has_parent = get_field('location_parent',$postId);
	$location_parent_id = get_field('location_parent_id',$postId);
	$parent_title = ''; // Eliminate PHP errors
	$parent_url = ''; // Eliminate PHP errors
	$parent_location = ''; // Eliminate PHP errors
	if ($location_has_parent && $location_parent_id) { 
		$parent_location = get_post( $location_parent_id );
	}
	// Get Post ID for Address & Image fields
	if ($parent_location) {
		$post_id = $parent_location->ID;
		$parent_title = $parent_location->post_title;
		$parent_url = get_permalink( $post_id );
	} else {
		$post_id = $postId;
	}
	// Parent Location
	$data['location_parent']['id'] = $location_parent_id;
	$data['location_parent']['title'] = $parent_title;
	$data['location_parent']['url'] = $parent_url;
	// Image values
	$override_parent_photo = get_field('location_image_override_parent', $postId);
	$override_parent_photo_featured = get_field('location_image_override_parent_featured', $postId);
	$override_parent_photo_wayfinding = get_field('location_image_override_parent_wayfinding', $postId);
	$override_parent_photo_gallery = get_field('location_image_override_parent_gallery', $postId);
	// if ($override_parent_photo && $parent_location) { // If child location & override is true
	if ($override_parent_photo && $parent_location && $override_parent_photo_wayfinding) {
		$wayfinding_photo = get_field('location_wayfinding_photo', $postId);
	} else { // Use parent/standard images
		$wayfinding_photo = get_field('location_wayfinding_photo', $post_id);
	}
	if ($override_parent_photo && $parent_location && $override_parent_photo_gallery) {
		$photo_gallery = get_field('location_photo_gallery', $postId);
	} else { // Use parent/standard images
		$photo_gallery = get_field('location_photo_gallery', $post_id);
	}

	$location_images = array();
	if ($wayfinding_photo && !empty($wayfinding_photo)) {
		$location_images[] = $wayfinding_photo;
	}
	if ($photo_gallery && !empty($photo_gallery)) {
		foreach( $photo_gallery as $photo_gallery_image ) {
			$location_images[] = $photo_gallery_image;
		}
	}
	if( ! empty( $location_images ) ){
		$i = 0;
		foreach ($location_images as $location_images_item) {
			$data['location_photo'][$i]['thumb'] = image_sizer($location_images_item, 60, 45, 'center', 'center');
			$data['location_photo'][$i]['sml'] = image_sizer($location_images_item, 576, 324, 'center', 'center');
			$data['location_photo'][$i]['med'] = image_sizer($location_images_item, 630, 473, 'center', 'center');
			$data['location_photo'][$i]['lrg'] = image_sizer($location_images_item, 992, 558, 'center', 'center');
			$i++;
		}
	}	
	//$data['location_photo'] = get_the_post_thumbnail($postId, 'aspect-16-9-small', ['class' => 'card-img-top']);
	$map = get_field('location_map', $postId );
	$location_floor = get_field_object('location_building_floor', $postId );
		$location_floor_value = '';
		$location_floor_label = '';
		if ( $location_floor ) {
			$location_floor_value = $location_floor['value'];
			$location_floor_label = $location_floor['choices'][ $location_floor_value ];
		}
	$data['location_lat'] = $map['lat'];
	$data['location_lng'] = $map['lng'];
	$data['location_address_1'] = get_field('location_address_1', $postId );
	$data['location_address_2'] = ( get_field('location_address_2', $postId ) ? get_field('location_address_2', $postId ) . '<br/>' : '');
	$location_building = get_field('location_building', $postId );
	if ($location_building) {
		$building = get_term($location_building, "building");
		$building_slug = $building->slug;
		$building_name = $building->name;
	}
	$data['location_building'] = $building_name;
	$data['location_building_floor'] = $location_floor_label;
	$data['location_suite'] = get_field('location_suite', $postId );
	$data['location_city'] = get_field('location_city', $postId );
	$data['location_state'] = get_field('location_state', $postId );
	$data['location_zip'] = get_field('location_zip', $postId );
	$location_region = get_field('location_region', $postId );
	$data['location_hidden'] = get_field( 'location_hidden', $postId );
	$data['location_region'] = get_term($location_region, "region")->slug;
	$types = get_field('location_type',$postId);
	$data['location_types'] ='';
	if( ! empty( $types ) ):
		foreach ( $types as $type ) :
			$type_name = get_term( $type, 'location_type');
			$data['location_types'] .= $data['location_types'] != '' ? ',' : '';
			$data['location_types'] .= $type_name->name;
		endforeach;
	endif;
	$data['location_direction'] = get_field('location_direction', $postId);
	$location_phone = get_field('location_phone', $postId );
	$location_phone_link = '<a href="tel:' . format_phone_dash( $location_phone ) . '" class="icon-phone">' . format_phone_us( $location_phone ) . '</a>';
	$location_clinic_phone_query = get_field('location_clinic_phone_query', $postId ); // separate number for (new) appointments?
	if ($location_clinic_phone_query) {
		$location_new_appointments_phone = get_field('location_new_appointments_phone', $postId ); // phone number for (new) appointments
		$location_new_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_new_appointments_phone ) . '" class="icon-phone">' . format_phone_us( $location_new_appointments_phone ) . '</a>';
		$location_appointment_phone_query = get_field('field_location_appointment_phone_query', $postId ); // separate number for existing appointments?
	} else {
		$location_new_appointments_phone = '';
		$location_appointment_phone_query = '0';
	}
	if ($location_appointment_phone_query) {
		$location_return_appointments_phone = get_field('location_return_appointments_phone', $postId ); // phone number for existing appointments
		$location_return_appointments_phone_link = '<a href="tel:' . format_phone_dash( $location_return_appointments_phone ) . '" class="icon-phone">' . format_phone_us( $location_return_appointments_phone ) . '</a>';
	} else {
		$location_return_appointments_phone = '';
	}
	$data['locations_phone_title'] = 'Clinic Phone Number';
	$data['location_phone'] = $location_phone;
	$data['location_phone_text'] = 'Appointment Phone Number'. ( $location_clinic_phone_query ? 's' : '' );
	$data['location_new_appointments_phone'] = $location_new_appointments_phone;
	$data['location_new_appointments_phonetext'] = ( $location_new_appointments_phone && $location_clinic_phone_query) ? 'New Patients' : 'New and Returning Patients';
	$data['location_return_appointments_phone'] = $location_return_appointments_phone;
	$data['location_return_appointments_phone_text'] = ($location_return_appointments_phone && $location_clinic_phone_query) ? 'Returning Patients' : '';
	$data['location_fax'] = get_field('location_fax', $postId);
	$additional_phones = get_field('location_phone_numbers', $postId);
	$i=0;
	foreach ($additional_phones as $additional_phone) {
		$data['location_additional_phone_numbers'][$i]['text'] = $additional_phone['location_appointments_text'];
		$data['location_additional_phone_numbers'][$i]['phone'] = $additional_phone['location_appointments_phone'];
		$data['location_additional_phone_numbers'][$i]['additional_text'] = $additional_phone['location_appointments_additional_text'];
		$i++;
	}
	// Hours
	$location_hours_group = get_field('location_hours_group', $postId);
	$data['location_hours_variable'] = $location_hours_group['location_hours_variable'];
	$data['location_hours_variable_info'] = $location_hours_group['location_hours_variable_info'];
	$data['location_24_7'] = $location_hours_group['location_24_7'];
	$data['location_modified'] = $location_hours_group['location_modified_hours'];
	$data['location_modified_reason'] = $location_hours_group['location_modified_hours_reason'];
	$data['location_modified_start_date'] = $location_hours_group['location_modified_hours_start_date'];
	$data['location_modified_end'] = $location_hours_group['location_modified_hours_end'];
	$data['location_modified_end_date'] = $location_hours_group['location_modified_hours_end_date'];
	$modified_hours = $location_hours_group['location_modified_hours_group'];
	$i=0;
	foreach ($modified_hours as $modified_hour) {
		$data['location_modified_hours'][$i]['title'] = $modified_hour['location_modified_hours_title'];
		$data['location_modified_hours'][$i]['information'] = $modified_hour['location_modified_hours_information'];
		$data['location_modified_hours'][$i]['times'] = $modified_hour['location_modified_hours_times'];
		$data['location_modified_hours'][$i]['24_7'] = $modified_hour['location_modified_hours_24_7'];
		$i++;
	}
	$hours = $location_hours_group['location_hours'];
	$i=0;
	foreach ($hours as $hour) :
		$data['location_hours'][$i]['day'] = $hour['day'];
		$data['location_hours'][$i]['closed'] = $hour['closed'];
		$data['location_hours'][$i]['open'] = $hour['open'];
		$data['location_hours'][$i]['close'] = $hour['close'];
		$data['location_hours'][$i]['comment'] = $hour['comment'];
		$i++;
	endforeach;
	// Holiday Hours - Deprecated for Modified Hours
	// $holidayhours = get_field('location_holiday_hours',$postId);
	// if ($holidayhours):
	// 	$order = array();
	// 	// populate order
	// 	foreach( $holidayhours as $i => $row ) {	
	// 		$order[ $i ] = $row['date'];
	// 	}
	// 	// multisort
	// 	array_multisort( $order, SORT_ASC, $holidayhours );
	// 	$i = 1;
	// 	foreach( $holidayhours as $holidayhour ):
	// 		$data['location_holiday_hours'][$i]['day'] = $holidayhour['date'];
	// 		$data['location_holiday_hours'][$i]['label'] = $holidayhour['label'];
	// 		$data['location_holiday_hours'][$i]['closed'] = $holidayhour['closed'];
	// 		$data['location_holiday_hours'][$i]['open'] = $holidayhour['open'];
	// 		$data['location_holiday_hours'][$i]['close'] = $holidayhour['close'];
	// 	endforeach;
	// endif;
	
	$data['location_after_hours'] = $location_hours_group['location_after_hours'];

	$data['location_telemed_query'] = $location_hours_group['location_telemed_query']; // Is there telemedicine?
	$data['location_telemed_patients'] = $location_hours_group['location_telemed_patients']; // New patients, existing or both?
	$data['location_telemed_hours247'] = $location_hours_group['location_telemed_24_7']; // typically 24/7?
	$data['location_telemed_hours'] = $location_hours_group['location_telemed_hours']; // telemedicine hours repeater
	$data['location_telemed_modified'] = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
	$data['location_telemed_modified_reason'] = $location_hours_group['location_telemed_modified_hours_reason']; // Why are there modified hours for telemedicine?
	$data['location_telemed_modified_start'] = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
	$data['location_telemed_modified_end'] = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
	$data['location_telemed_modified_end_date'] = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?
	$data['location_telemed_modified_hours247'] = $location_hours_group['location_telemed_modified_hours_24_7'];
	$data['location_telemed_info'] = get_field('location_telemed_descr_system', 'option');

	$data['location_closing'] = get_field('location_closing',$postId); // true or false
	$data['location_closing_date'] = get_field('location_closing_date',$postId);
	$data['location_closing_length'] = get_field('location_closing_length',$postId);
	$data['location_reopen_known'] = get_field('location_reopen_known',$postId);
	$data['location_reopen_date'] = get_field('location_reopen_date',$postId);
	$data['location_closing_info'] = get_field('location_closing_info',$postId);
	$data['location_closing_telemed'] = get_field('location_closing_telemed',$postId);

	$data['location_prescription_query'] = get_field('location_prescription_query',$postId);
	$data['location_prescription_type'] = get_field('location_prescription_type',$postId);
	$data['location_prescription'] = get_field('location_prescription',$postId);
	
	$data['location_about'] = get_field('location_about', $postId);
	$data['location_appointment'] = get_field('location_appointment',$postId);
	$data['location_appointment_bring'] = get_field('location_appointment_bring',$postId);
	$location_portal = get_field('location_portal', $postId);
	$portal = get_term($location_portal, "portal");
	$data['location_portal']['name'] = $portal->name;
	$data['location_portal']['content'] = get_field('portal_content', $portal);
	$data['location_portal']['url'] = get_field('portal_url', $portal);

	// Alert with logic
	$location_alert_title_sys = get_field('location_alert_heading_system', 'option');
	$location_alert_text_sys = get_field('location_alert_body_system', 'option');
	$location_alert_color_sys = get_field('location_alert_color_system', 'option');

	$location_alert_suppress = get_field('location_alert_suppress',$postId);
	$location_alert_modification = get_field('location_alert_modification',$postId);

	$location_alert_title_local = get_field('location_alert_heading',$postId);
	$location_alert_text_local = get_field('location_alert_body',$postId);
	$location_alert_color_local = get_field('location_alert_color',$postId);

	$location_alert_title = $location_alert_title_sys;
	if ( !empty($location_alert_title_local) && $location_alert_modification == 'override' ) {
		$location_alert_title = $location_alert_title_local;
	}
	$location_alert_color = $location_alert_color_sys;
	if ( $location_alert_modification == 'override' && $location_alert_color_local != 'inherit' ) {
		$location_alert_color = $location_alert_color_local;
	}
	$location_alert_text = $location_alert_text_sys;
	if ( $location_alert_modification == 'override' && !empty($location_alert_text_local) ) {
		$location_alert_text = $location_alert_text_local;
	} elseif ( $location_alert_modification == 'prepend' && !empty($location_alert_text_local) ) {
		$location_alert_text = $location_alert_text_local . $location_alert_text_sys;
	} elseif ( $location_alert_modification == 'append' && !empty($location_alert_text_local) ) {
		$location_alert_text = $location_alert_text_sys . $location_alert_text_local;
	}
	if ( $location_alert_modification == 'suppress' ) {
		$location_alert_suppress = true;
		$location_alert_title = '';
		$location_alert_text = '';
	}
	$data['location_alert_title'] = $location_alert_title ? $location_alert_title : '';
	$data['location_alert_text'] = $location_alert_text ? $location_alert_text : '';
	$data['location_alert_color'] = $location_alert_color ? $location_alert_color : 'alert-warning';

	$providers = get_field('physician_locations', $postId);
	$provider_list = '';
	$i = 1;
	if( $providers ) {
		$args = (array(
			'post_type' => "provider",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $providers
		));
		$provider_query = new WP_Query( $args );
		if( $providers && $provider_query->posts ):

			foreach( $provider_query->posts as $provider ):
				$data['location_provider'][$provider->ID]['link'] = get_permalink( $provider->ID );
				$data['location_provider'][$provider->ID]['title'] = get_field('physician_full_name', $provider->ID);
				$data['location_provider'][$provider->ID]['slug'] = $provider->post_name;
				$provider_list .= get_field('physician_full_name', $provider->ID);
				if( count($providers) > $i ) {
					$provider_list .= ' | ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['location_provider_list'] = $provider_list;
	$expertises =  get_field('location_expertise', $postId);
	$expertise_list = '';
	$i = 1;
	if( $expertises ) {
		$args = (array(
			'post_type' => "expertise",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $expertises
		));
		$expertise_query = new WP_Query( $args );
		if( $expertises && $expertise_query->posts ):

			foreach( $expertise_query->posts as $expertise ):
				$data['location_expertise'][$expertise->ID]['link'] = get_permalink( $expertise->ID );
				$data['location_expertise'][$expertise->ID]['title'] = $expertise->post_title;
				$data['location_expertise'][$expertise->ID]['slug'] = $expertise->post_name;
				$expertise_list .= $expertise->post_title;
				if( count($expertises) > $i ) {
					$expertise_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['location_expertise_list'] = $expertise_list;
	$conditions_cpt = get_field('location_conditions_cpt', $postId);
	$condition_list = '';
	$i = 1;
	if( $conditions_cpt ) {
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );

		if( $conditions_cpt && $conditions_cpt_query->posts ):

			foreach( $conditions_cpt_query->posts as $condition ):
				$data['location_conditions'][$condition->ID]['link'] = get_permalink( $condition->ID );
				$data['location_conditions'][$condition->ID]['title'] = $condition->post_title;
				$data['location_conditions'][$condition->ID]['slug'] = $condition->post_name;
				$condition_list .= $condition->post_title;
				if( count($conditions_cpt) > $i ) {
					$condition_list .= ', ';
				}
				$i++;
			endforeach;
		endif; 
	}
	$data['location_conditions_list'] = $condition_list;
	// Treatments
	$treatments_cpt = get_field('location_treatments_cpt', $postId);
	$treatment_list = '';
	$i = 1;
	if( $treatments_cpt ) {
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
		if( $treatments_cpt && $treatments_cpt_query->posts ):

			foreach( $treatments_cpt_query->posts as $treatment ):
				$data['location_treatments'][$treatment->ID]['link'] = get_permalink( $treatment->ID );
				$data['location_treatments'][$treatment->ID]['title'] = $treatment->post_title;
				$data['location_treatments'][$treatment->ID]['slug'] = $treatment->post_name;
				$treatment_list .= $treatment->post_title;
				if( count($treatments_cpt) > $i ) {
					$treatment_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['location_treatments_list'] = $treatment_list;
	$data['location_parking'] = get_field('location_parking', $postId);
	$parking_map = get_field('location_parking_map', $postId);
	$data['location_parking_link'] = '<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/'. $parking_map['lat'] .','. $parking_map['lng'] .'" target="_blank" aria-label="Get directions to the parking area">Get Directions</a>';
	$data['location_directions_link'] = '<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/'. $map['lat'] .','. $map['lng'] .'" target="_blank" aria-label="Get Directions to '. get_the_title($postId) .'">Get Directions</a>';

	return $data;

}
// add_action('rest_api_init', 'rest_api_location_meta');

function get_expertise_meta($object) {
	$postId = $object['id'];
	$data['expertise_title'] = get_the_title( $postId );
	$data['expertise_link'] = get_permalink($postId );
	$podcast_name = get_field('expertise_podcast_name', $postId);
	$data['expertise_podcast'] = '<script type="text/javascript" src="https://radiomd.com/widget/easyXDM.js">
	</script>
	<script type="text/javascript">
		radiomd_embedded_filtered_tag("uams","radiomd-embedded-filtered-tag",303,"' . $podcast_name . '");
	</script>
	<style type="text/css">
		#radiomd-embedded-filtered-tag iframe {
		width: 100%;
		border: none;
	}
	</style>
	<div class="content-width mt-8" id="radiomd-embedded-filtered-tag"></div>';
	// Conditions
	$conditions_cpt = get_field('expertise_conditions_cpt', $postId);
	$condition_list = '';
	$i = 1;
	if( $conditions_cpt ) {
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );

		if( $conditions_cpt && $conditions_cpt_query->posts ):

			foreach( $conditions_cpt_query->posts as $condition ):
				$data['expertise_conditions'][$condition->ID]['link'] = get_permalink( $condition->ID );
				$data['expertise_conditions'][$condition->ID]['title'] = $condition->post_title;
				$data['expertise_conditions'][$condition->ID]['slug'] = $condition->post_name;
				$condition_list .= $condition->post_title;
				if( count($conditions_cpt) > $i ) {
					$condition_list .= ', ';
				}
				$i++;
			endforeach;
		endif; 
	}
	$data['expertise_conditions_list'] = $condition_list;
	// Treatments
	$treatments_cpt = get_field('expertise_treatments_cpt', $postId);
	$treatment_list = '';
	$i = 1;
	if( $treatments_cpt ) {
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
		if( $treatments_cpt && $treatments_cpt_query->posts ):

			foreach( $treatments_cpt_query->posts as $treatment ):
				$data['expertise_treatments'][$treatment->ID]['link'] = get_permalink( $treatment->ID );
				$data['expertise_treatments'][$treatment->ID]['title'] = $treatment->post_title;
				$data['expertise_treatments'][$treatment->ID]['slug'] = $treatment->post_name;
				$treatment_list .= $treatment->post_title;
				if( count($treatments_cpt) > $i ) {
					$treatment_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['expertise_treatments_list'] = $treatment_list;
	// Providers
	$providers = get_field('physician_expertise', $postId);
	$provider_list = '';
	$i = 1;
	if( $providers ) {
		$args = (array(
			'post_type' => "provider",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $providers
		));
		$provider_query = new WP_Query( $args );
		if( $providers && $provider_query->posts ):

			foreach( $provider_query->posts as $provider ):
				$data['expertise_provider'][$provider->ID]['link'] = get_permalink( $provider->ID );
				$data['expertise_provider'][$provider->ID]['title'] = get_field('physician_full_name', $provider->ID);
				$data['expertise_provider'][$provider->ID]['slug'] = $provider->post_name;
				$provider_list .= get_field('physician_full_name', $provider->ID);
				if( count($providers) > $i ) {
					$provider_list .= ' | ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['expertise_provider_list'] = $provider_list;
	// Locations
	$locations = get_field('location_expertise', $postId);
	$location_list = '';
	$i = 1;
	if( $locations ) {
		$args = (array(
			'post_type' => "location",
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in'	=> $locations
		));
		$location_query = new WP_Query( $args );
		if( $locations && $location_query->posts ):

			foreach( $location_query->posts as $location ):
				$data['expertise_location'][$location->ID]['link'] = get_permalink( $location->ID );
				$data['expertise_location'][$location->ID]['title'] = $location->post_title;
				$data['expertise_location'][$location->ID]['slug'] = $location->post_name;
				$location_list .= $location->post_title;
				if( count($locations) > $i ) {
					$location_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['expertise_location_list'] = $location_list;

	return $data;

}
// add_action('rest_api_init', 'rest_api_expertise_meta');

function get_condition_meta($object) {
	$postId = $object['id'];
	// Expertise
	$expertises =  get_field('condition_expertise', $postId);
	$expertise_list = '';
	$i = 1;
	if( $expertises ) {
		$args = (array(
			'post_type' => "expertise",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $expertises
		));
		$expertise_query = new WP_Query( $args );
		if( $expertises && $expertise_query->posts ):

			foreach( $expertise_query->posts as $expertise ):
				$data['condition_expertise'][$expertise->ID]['link'] = get_permalink( $expertise->ID );
				$data['condition_expertise'][$expertise->ID]['title'] = $expertise->post_title;
				$data['condition_expertise'][$expertise->ID]['slug'] = $expertise->post_name;
				$expertise_list .= $expertise->post_title;
				if( count($expertises) > $i ) {
					$expertise_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['condition_expertise_list'] = $expertise_list;
	// Treatments
	$treatments_cpt = get_field('condition_treatments', $postId);
	$treatment_list = '';
	$i = 1;
	if( $treatments_cpt ) {
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
		if( $treatments_cpt && $treatments_cpt_query->posts ):

			foreach( $treatments_cpt_query->posts as $treatment ):
				$data['condition_treatments'][$treatment->ID]['link'] = get_permalink( $treatment->ID );
				$data['condition_treatments'][$treatment->ID]['title'] = $treatment->post_title;
				$data['condition_treatments'][$treatment->ID]['slug'] = $treatment->post_name;
				$treatment_list .= $treatment->post_title;
				if( count($treatments_cpt) > $i ) {
					$treatment_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['condition_treatments_list'] = $treatment_list;
	// Providers
	$providers = get_field('condition_physicians', $postId);
	$provider_list = '';
	$i = 1;
	if( $providers ) {
		$args = (array(
			'post_type' => "provider",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $providers
		));
		$provider_query = new WP_Query( $args );
		if( $providers && $provider_query->posts ):

			foreach( $provider_query->posts as $provider ):
				$data['condition_provider'][$provider->ID]['link'] = get_permalink( $provider->ID );
				$data['condition_provider'][$provider->ID]['title'] = get_field('physician_full_name', $provider->ID);
				$data['condition_provider'][$provider->ID]['slug'] = $provider->post_name;
				$provider_list .= get_field('physician_full_name', $provider->ID);
				if( count($providers) > $i ) {
					$provider_list .= ' | ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['condition_provider_list'] = $provider_list;
	// Locations
	$locations = get_field('condition_locations', $postId);
	$location_list = '';
	$i = 1;
	if( $locations ) {
		$args = (array(
			'post_type' => "location",
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in'	=> $locations
		));
		$location_query = new WP_Query( $args );
		if( $locations && $location_query->posts ):

			foreach( $location_query->posts as $location ):
				$data['condition_location'][$location->ID]['link'] = get_permalink( $location->ID );
				$data['condition_location'][$location->ID]['title'] = $location->post_title;
				$data['condition_location'][$location->ID]['slug'] = $location->post_name;
				$location_list .= $location->post_title;
				if( count($locations) > $i ) {
					$location_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['condition_location_list'] = $location_list;

	return $data;

}
// add_action('rest_api_init', 'rest_api_condition_meta');
// Treatments API
function get_treatment_meta($object) {
	$postId = $object['id'];
	// Expertise
	$expertises =  get_field('treatment_procedure_expertise', $postId);
	$expertise_list = '';
	$i = 1;
	if( $expertises ) {
		$args = (array(
			'post_type' => "expertise",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $expertises
		));
		$expertise_query = new WP_Query( $args );
		if( $expertises && $expertise_query->posts ):

			foreach( $expertise_query->posts as $expertise ):
				$data['treatment_expertise'][$expertise->ID]['link'] = get_permalink( $expertise->ID );
				$data['treatment_expertise'][$expertise->ID]['title'] = $expertise->post_title;
				$data['treatment_expertise'][$expertise->ID]['slug'] = $expertise->post_name;
				$expertise_list .= $expertise->post_title;
				if( count($expertises) > $i ) {
					$expertise_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['treatment_expertise_list'] = $expertise_list;
	// Conditions
	$conditions_cpt = get_field('treatment_conditions', $postId);
	$condition_list = '';
	$i = 1;
	if( $conditions_cpt ) {
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );
		if( $conditions_cpt && $conditions_cpt_query->posts ):

			foreach( $conditions_cpt_query->posts as $condition ):
				$data['treatment_conditions'][$condition->ID]['link'] = get_permalink( $condition->ID );
				$data['treatment_conditions'][$condition->ID]['title'] = $condition->post_title;
				$data['treatment_conditions'][$condition->ID]['slug'] = $condition->post_name;
				$condition_list .= $condition->post_title;
				if( count($conditions_cpt) > $i ) {
					$condition_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['treatment_conditions_list'] = $condition_list;
	// Providers
	$providers = get_field('treatment_procedure_physicians', $postId);
	$provider_list = '';
	$i = 1;
	if( $providers ) {
		$args = (array(
			'post_type' => "provider",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $providers
		));
		$provider_query = new WP_Query( $args );
		if( $providers && $provider_query->posts ):

			foreach( $provider_query->posts as $provider ):
				$data['treatment_provider'][$provider->ID]['link'] = get_permalink( $provider->ID );
				$data['treatment_provider'][$provider->ID]['title'] = get_field('physician_full_name', $provider->ID);
				$data['treatment_provider'][$provider->ID]['slug'] = $provider->post_name;
				$provider_list .= get_field('physician_full_name', $provider->ID);
				if( count($providers) > $i ) {
					$provider_list .= ' | ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['treatment_provider_list'] = $provider_list;
	// Locations
	$locations = get_field('treatment_procedure_locations', $postId);
	$location_list = '';
	$i = 1;
	if( $locations ) {
		$args = (array(
			'post_type' => "location",
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in'	=> $locations
		));
		$location_query = new WP_Query( $args );
		if( $locations && $location_query->posts ):

			foreach( $location_query->posts as $location ):
				$data['treatment_location'][$location->ID]['link'] = get_permalink( $location->ID );
				$data['treatment_location'][$location->ID]['title'] = $location->post_title;
				$data['treatment_location'][$location->ID]['slug'] = $location->post_name;
				$location_list .= $location->post_title;
				if( count($locations) > $i ) {
					$location_list .= ', ';
				}
				$i++;
			endforeach;
		endif;
	}
	$data['treatment_location_list'] = $location_list;

	return $data;

}
// Clinical Resources API
function get_resource_meta($object) {
	$postId = $object['id'];
	$resource_image = get_the_post_thumbnail_url( $postId, 'large' );
	$resource_type = get_field('clinical_resource_type', $postId);
	$resource_type_value = $resource_type['value'];
	$resource_type_label = $resource_type['label'];
	// Conditions
	$conditions_cpt = get_field('clinical_resource_conditions', $postId);
	if($conditions_cpt) {
		$args = (array(
			'post_type' => "condition",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $conditions_cpt
		));
		$conditions_cpt_query = new WP_Query( $args );
	}
	// Treatments
	$treatments_cpt = get_field('clinical_resource_treatments', $postId);
	if($treatments_cpt) {
		$args = (array(
			'post_type' => "treatment",
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'post__in' => $treatments_cpt
		));
		$treatments_cpt_query = new WP_Query( $args );
	}
	// Physicians
	$providers = get_field( 'clinical_resource_providers', $postId );
	if($providers) {
		$args = array(
			"post_type" => "provider",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"orderby" => "title",
			"order" => "ASC",
			"post__in" => $providers
		);
		$provider_query = New WP_Query( $args );
	}
	// Locations
	$locations = get_field('clinical_resource_locations', $postId);
	if($locations) {
		$args = (array(
			'post_type' => "location",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in'	=> $locations
		));
		$location_query = new WP_Query( $args );
	}
	// Expertise
	$expertises =  get_field('clinical_resource_aoe', $postId);
	if($expertises) {
		$args = (array(
			'post_type' => "expertise",
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in'	=> $expertises
		));
		$expertise_query = new WP_Query( $args );
	}
	// Related Resources
	$resources =  get_field('clinical_resource_related', $postId);
	if($resources) {
		$args = (array(
			'post_type' => "clinical-resource",
			'order' => 'DESC',
			'orderby' => 'post_date',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post__in'	=> $resources
		));
		$resource_query = new WP_Query( $args );
	}

	$text = get_field('clinical_resource_text', $postId);
	$nci_query = get_field('clinical_resource_text_nci_query', $postId);
    $nci_embed = get_field('clinical_resource_nci_embed', $postId);

	$infographic = get_field('clinical_resource_infographic', $postId);
    $infographic_descr = get_field('clinical_resource_infographic_descr', $postId);
    $infographic_transcript = get_field('clinical_resource_infographic_transcript', $postId);

	$document_descr = get_field('clinical_resource_document_descr', $postId);
    $document = get_field('clinical_resource_document', $postId);

	$video = get_field('clinical_resource_video', $postId);
    $video_descr = get_field('clinical_resource_video_descr', $postId);
    $video_transcript = get_field('clinical_resource_video_transcript', $postId);

	$data['clinical_resource_title'] = get_the_title( $postId );
	$data['clinical_resource_link'] = get_permalink($postId );
	$data['clinical_resource_excerpt'] = get_field( 'clinical_resource_excerpt', $postId);
	$data['clinical_resource_image'] = $resource_image;
	$data['clinical_resource_type'] = $resource_type_label;
	$data['clinical_resource_text'] = $text;
	$data['clinical_resource_text_nci_query'] = $nci_query;
	$data['clinical_resource_nci_embed'] = $nci_embed;
	$data['clinical_resource_infographic'] = $infographic;
	$data['clinical_resource_infographic_descr'] = $infographic_descr;
	$data['clinical_resource_infographic_transcript'] = $infographic_transcript;
	$data['clinical_resource_video'] = $video;
	$data['clinical_resource_video_descr'] = $video_descr;
	$data['clinical_resource_video_transcript'] = $video_transcript;
	// $data['clinical_resource_document'] = $document;
	$data['clinical_resource_document_descr'] = $document_descr;
	$i = 0;
	while( have_rows($document) ): the_row();
		$document_title = get_sub_field('document_title');
		$document_file = get_sub_field('document_file');
		$document_url = $document_file['url'];
		$data['clinical_resource_document'][$i]['title'] = $document_title;
		$data['clinical_resource_document'][$i]['url'] = $document_title;
		$i++;
	endwhile;
	if( $resources && $resource_query->posts ):
		foreach( $resource_query->posts as $resource ):
			$data['clinical_resource_associated'][$resource->ID]['link'] = get_permalink( $resource->ID );
			$data['clinical_resource_associated'][$resource->ID]['title'] = $resource->post_title;
			$data['clinical_resource_associated'][$resource->ID]['slug'] = $resource->post_name;
		endforeach;
	endif;
	$condition_list = '';
	$i = 1;
	if( $conditions_cpt && $conditions_cpt_query->posts ):
		foreach( $conditions_cpt_query->posts as $condition ):
			$data['clinical_resource_conditions'][$condition->ID]['link'] = get_permalink( $condition->ID );
			$data['clinical_resource_conditions'][$condition->ID]['title'] = $condition->post_title;
			$data['clinical_resource_conditions'][$condition->ID]['slug'] = $condition->post_name;
			$condition_list .= $condition->post_title;
			if( count($conditions_cpt) > $i ) {
				$condition_list .= ', ';
			}
			$i++;
		endforeach;
	endif;
	$data['clinical_resource_conditions_list'] = $condition_list;
	$treatment_list = '';
	$i = 1;
	if( $treatments_cpt && $treatments_cpt_query->posts ):
		foreach( $treatments_cpt_query->posts as $treatment ):
			$data['clinical_resource_treatments'][$treatment->ID]['link'] = get_permalink( $treatment->ID );
			$data['clinical_resource_treatments'][$treatment->ID]['title'] = $treatment->post_title;
			$data['clinical_resource_treatments'][$treatment->ID]['slug'] = $treatment->post_name;
			$treatment_list .= $treatment->post_title;
			if( count($treatments_cpt) > $i ) {
				$treatment_list .= ', ';
			}
			$i++;
		endforeach;
	endif;
	$data['clinical_resource_treatments_list'] = $treatment_list;
	$provider_list = '';
	$i = 1;
	if( $providers && $provider_query->posts ):

		foreach( $provider_query->posts as $provider ):
			$data['clinical_resource_provider'][$provider->ID]['link'] = get_permalink( $provider->ID );
			$data['clinical_resource_provider'][$provider->ID]['title'] = get_field('physician_full_name', $provider->ID);
			$data['clinical_resource_provider'][$provider->ID]['slug'] = $provider->post_name;
			$provider_list .= get_field('physician_full_name', $provider->ID);
			if( count($providers) > $i ) {
				$provider_list .= ' | ';
			}
			$i++;
		endforeach;
	endif;
	$data['clinical_resource_provider_list'] = $provider_list;
	$location_list = '';
	$i = 1;
	if( $locations && $location_query->posts ):

		foreach( $location_query->posts as $location ):
			$data['clinical_resource_location'][$location->ID]['link'] = get_permalink( $location->ID );
			$data['clinical_resource_location'][$location->ID]['title'] = $location->post_title;
			$data['clinical_resource_location'][$location->ID]['slug'] = $location->post_name;
			$location_list .= $location->post_title;
			if( count($locations) > $i ) {
				$location_list .= ', ';
			}
			$i++;
		endforeach;
	endif;
	$data['clinical_resource_location_list'] = $location_list;
	$expertise_list = '';
	$i = 1;
	if( $expertises && $expertise_query->posts ):

		foreach( $expertise_query->posts as $expertise ):
			$data['clinical_resource_expertise'][$expertise->ID]['link'] = get_permalink( $expertise->ID );
			$data['clinical_resource_expertise'][$expertise->ID]['title'] = $expertise->post_title;
			$data['clinical_resource_expertise'][$expertise->ID]['slug'] = $expertise->post_name;
			$expertise_list .= $expertise->post_title;
			if( count($expertises) > $i ) {
				$expertise_list .= ', ';
			}
			$i++;
		endforeach;
	endif;
	$data['clinical_resource_expertise_list'] = $expertise_list;

	return $data;
}

// Add REST API query var filters
add_filter('rest_query_vars', 'provider_add_rest_query_vars');
function provider_add_rest_query_vars($query_vars) {
    $query_vars = array_merge( $query_vars, array('meta_key', 'meta_value', 'meta_compare') );
    return $query_vars;
}