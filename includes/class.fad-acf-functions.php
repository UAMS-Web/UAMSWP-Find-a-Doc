<?php
/**
 * Advanced Custom Fields Functions
 */

// ACF Custom Tables

	// Change the ACF Custom Database Tables JSON directory

		/**
		 * Changes the ACF Custom Database Tables JSON directory.
		 * This needs to run before the 'plugins_loaded' action hook, so
		 * you need to put this in a plugin or in your wp-config.php file.
		 */

		define( 'ACFCDT_JSON_DIR', WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-tables' );

	// Disable storing of meta data values in core meta tables

		/**
		 * Disables storing of meta data values in core meta tables where a custom
		 * database table has been defined for fields. Any fields that aren't mapped
		 * to a custom database table will still be stored in the core meta tables.
		 */

		add_filter( 'acfcdt/settings/store_acf_values_in_core_meta', '__return_false' );

	// Disable storing of ACF field key references in core meta tables

		/**
		 * Disables storing of ACF field key references in core meta tables where a custom
		 * database table has been defined for fields. Any fields that aren't mapped to a
		 * custom database table will still have their key references stored in the core
		 * meta tables.
		 */

		// add_filter( 'acfcdt/settings/store_acf_keys_in_core_meta', '__return_false' );

// Add a new load point for ACF to look in for local JSON

	/**
	 * Advanced Custom Fields documentation: https://www.advancedcustomfields.com/resources/local-json/#loading-explained
	 */

	add_filter('acf/settings/load_json', 'uamswp_fad_json_load_point');

	function uamswp_fad_json_load_point( $paths ) {

		// Remove the original path (optional)

			// unset($paths[0]);

		// Append the new path

			$paths[] = WP_PLUGIN_DIR .'/'. basename(dirname(dirname(__FILE__))) . '/assets/json/acf-json';

		// Return

			return $paths;

	}

// Set default values for taxonomy fields used in profiles

	// Patient portal

		/**
		 * Set 'UAMS Health MyChart' as the default patient portal for provider profiles
		 * and location profiles.
		 *
		 * The slug for 'UAMS Health MyChart' must be set as 'uams-mychart'.
		 */

		add_filter('acf/prepare_field/key=field_physician_portal', 'set_default_portal', 20, 3);
		add_filter('acf/prepare_field/key=field_location_portal', 'set_default_portal', 20, 3);

		function set_default_portal( $field ) {

			/**
			 * Only add default content if no value has been set
			 */

			if ( empty( $field['value'] ) ) {

				$term = get_term_by('slug', 'uams-mychart', 'portal');
				$term_id = $term->term_id;
				$default = array($term_id);

				// Set field to default value

					$field[ 'value' ] = $default;

			}

			return $field;

		}

	// Language

		/**
		 * Set 'English' as the default language for provider profiles.
		 *
		 * The slug for 'English' must be set as 'english'.
		 */

		add_filter('acf/load_value/key=field_physician_languages', 'set_default_language', 20, 3);

		function set_default_language($value, $post_id, $field) {

			// Only add default content for new posts

				if ( $value !== null ) {

					return $value;

				}

			$term = get_term_by('slug', 'english', 'language');
			$term_id = $term->term_id;
			$value = array($term_id);

			return $value;

		}

	// Region

		/**
		 * Set 'Central Arkansas' as the default region for location profiles.
		 *
		 * The slug for 'Central Arkansas' must be set as 'central'.
		 */

		add_filter('acf/load_value/key=field_location_region', 'set_default_region', 20, 3);

		function set_default_region($value, $post_id, $field) {

			// Only add default content for empty fields

				if ( $value !== null ) {

					return $value;

				}

			$term = get_term_by('slug', 'central', 'region');
			$term_id = $term->term_id;
			$value = array($term_id);

			return $value;

		}

// Order for Portal

	/**
	 * 'None' slug set to '_none'
	 */

	add_filter('acf/fields/taxonomy/wp_list_categories/key=field_location_portal', 'my_taxonomy_query', 10, 2);
	add_filter('acf/fields/taxonomy/wp_list_categories/key=field_physician_portal', 'my_taxonomy_query', 10, 2);

	function my_taxonomy_query( $args, $field ) {

		// modify args

			$args['orderby'] = 'slug';
			$args['order'] = 'ASC';

		// return

			return $args;

	}

// Trigger FacetWP to re-index a single post when saving the submitted $_POST data

	/**
	 * FacetWP documentation: https://facetwp.com/help-center/indexing/#how-to-trigger-the-indexer-programmatically
	 *
	 * Advanced Custom Fields documentation: https://www.advancedcustomfields.com/resources/acf-save_post/
	 */

	add_action( 'acf/save_post', 'update_facetwp_index');

	function update_facetwp_index( $post_id ) {

		if ( function_exists( 'FWP' ) ) {

			FWP()->indexer->index( $post_id );

		}

	}

// Update post data / ACF data for profiles

	// Provider profile

		// Fire before saving data to post (by using a priority less than 10)

			/**
			 * Only updates ACF data
			 */

			add_action('acf/save_post', 'physician_save_post', 5);

			function physician_save_post( $post_id ) {

				$post_type = get_post_type($post_id);

				// Bail early if no data sent or not provider post type

					if (
						empty( $_POST['acf'] )
						||
						( $post_type != 'provider' )
					) {

						return;

					}

				// Create provider name combinations to store in fields

					$first_name = $_POST['acf']['field_physician_first_name'];
					$middle_name = $_POST['acf']['field_physician_middle_name'];
					$last_name = $_POST['acf']['field_physician_last_name'];
					$pedigree = $_POST['acf']['field_physician_pedigree'];

					// Create full name to store in 'physician_full_name' field (e.g., "Leonard H. McCoy, M.D.")

						$degrees = $_POST['acf']['field_physician_degree'];

						$i = 1;

						if ( $degrees ) {

							foreach ( $degrees as $degree ) {

								$degree_name = get_term( $degree, 'degree');
								$degree_list .= $degree_name->name;

								if ( count($degrees) > $i ) {

									$degree_list .= ", ";

								}

								$i++;

							} // endforeach

						}

						$full_name = $first_name . ' ' . ( $middle_name ? $middle_name . ' ' : '' ) . $last_name . ( $pedigree ? '&nbsp;' . $pedigree : '' ) . ( $degree_list ? ', ' . $degree_list : '' );

						$_POST['acf']['field_physician_full_name'] = $full_name;

				// Create list of related ontology items to store in fields

					/**
					 * The purpose is to have a hidden field populated by a string of titles of the
					 * related ontology items that can then be searched by Ajax Search Pro.
					 */

					// Create list of related areas of expertise

						// Get related areas of expertise

							$expertises = $_POST['acf']['field_physician_expertise'];

						// Loop through the areas of expertise and add each item's title to a list

							if ( $expertises ) {

								$i = 1;

								foreach ( $expertises as $expertise ) {

									$expertise_name = get_the_title( $expertise );
									$expertise_list .= $expertise_name;

									if ( count($expertises) > $i ) {

										$expertise_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Create list of related conditions

						// Get related conditions

							$conditions = $_POST['acf']['field_physician_conditions_cpt'];

						// Loop through the conditions and add each item's title to a list

							if ( $conditions ) {

								$i = 1;

								foreach ( $conditions as $condition ) {

									$condition_name = get_the_title( $condition );
									$condition_list .= $condition_name;

									if ( count($conditions) > $i ) {

										$condition_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Create list of related treatments

						// Get related treatments

							$treatments = $_POST['acf']['field_physician_treatments_cpt'];

						// Loop through the treatments and add each item's title to a list

							if ( $treatments ) {

								$i = 1;

								foreach ( $treatments as $treatment ) {

									$treatment_name = get_the_title( $treatment );
									$treatment_list .= $treatment_name;

									if ( count($treatments) > $i ) {

										$treatment_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Combine the lists

						$filter_list = $expertise_list . ', ' . $condition_list . ', ' . $treatment_list;

					// Store the value of the combined list in a field

						$_POST['acf']['field_physician_asp_filter'] = $filter_list;

				// Add values from the associated locations

					// Get the list of locations associated with the provider

						$locations = $_POST['acf']['field_physician_locations'];

					// Get the desired values from each associated location

						if ( $locations ) {

							// Base arrays

								$region = array();
								$portal = array();

							foreach ( $locations as $location ) {

								// Get the values

									// Region

										$location_region = get_field( 'location_region', $location ) ?? null;

										if ( $location_region ) {

											$region[] = $location_region;

										}

									// Portal

										$location_portal = get_field( 'location_portal', $location ) ?? null;

										if ( $location_portal ) {

											$portal[] = $location_portal;

										}

								// Break the loop after first iteration (optional)

									/**
									 * The first location in the list of the provider's associated locations should be
									 * the provider's primary location.
									 *
									 * If the relevant values of the provider's primary location are all that
									 * matter, break the loop here.
									 */

									// break;

							} // endforeach

						}

					// Set the desired values from each associated location

						// Region

							$_POST['acf']['field_physician_region'] = $region;

						// Portal

							/**
							 * Use the first portal only.
							 *
							 * The first portal value should be the portal value of the provider's primary
							 * location.
							 */

							$_POST['acf']['field_physician_portal'] = $portal[0];

			}

	// Clinical resource profile

		// Fire before saving data to post (by using a priority less than 10)

			add_action('acf/save_post', 'resources_save_post', 6);

			function resources_save_post( $post_id ) {

				$post_type = get_post_type($post_id);

				// Bail early if no data sent or not clinical resource post type

					if (
						empty( $_POST['acf'] )
						||
						( $post_type != 'clinical-resource' )
					) {

						return;

					}

				// Create list of related ontology items to store in fields

					/**
					 * The purpose is to have a hidden field populated by a string of titles of the
					 * related ontology items that can then be searched by Ajax Search Pro.
					 */

					// Create list of related providers

						// Get related providers

							$providers = $_POST['acf']['field_clinical_resource_providers'];

						// Loop through the providers and add each item's title to a list

							if ( $providers ) {

								$i = 1;

								foreach( $providers as $provider ) {

									$provider_name = get_the_title( $provider );
									$provider_list .= $provider_name;

									if ( count($providers) > $i ) {

										$provider_list .= ", ";

									}

									$i++;

								} // endforeach
							}

					// Create list of related locations

						// Get related locations

							$locations = $_POST['acf']['field_clinical_resource_locations'];

						// Loop through the locations and add each item's title to a list

							if ( $locations ) {

								$i = 1;

								foreach( $locations as $location ) {

									$location_name = get_the_title( $location );
									$location_list .= $location_name;

									if ( count($locations) > $i ) {

										$location_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Create list of related areas of expertise

						// Get related areas of expertise

							$expertises = $_POST['acf']['field_clinical_resource_aoe'];

						// Loop through the areas of expertise and add each item's title to a list

							if ( $expertises ) {

								$i = 1;

								foreach ( $expertises as $expertise ) {

									$expertise_name = get_the_title( $expertise );
									$expertise_list .= $expertise_name;

									if ( count($expertises) > $i ) {

										$expertise_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Create list of related conditions

						// Get related conditions

							$conditions = $_POST['acf']['field_clinical_resource_conditions'];

						// Loop through the conditions and add each item's title to a list

							if ( $conditions ) {

								$i = 1;

								foreach ( $conditions as $condition ) {

									$condition_name = get_the_title( $condition );
									$condition_list .= $condition_name;

									if ( count($conditions) > $i ) {

										$condition_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Create list of related treatments

						// Get related treatments

							$treatments = $_POST['acf']['field_clinical_resource_treatments'];

						// Loop through the treatments and add each item's title to a list

							if ( $treatments ) {

								$i = 1;

								foreach( $treatments as $treatment ):

									$treatment_name = get_the_title( $treatment );
									$treatment_list .= $treatment_name;

									if( count($treatments) > $i ) {

										$treatment_list .= ", ";

									}

									$i++;

								endforeach;

							}

					// Create list of related clinical resources

						// Get related clinical resources

							$clinical_resources = $_POST['acf']['field_clinical_resource_related'];

						// Loop through the clinical resources and add each item's title to a list

							if ( $clinical_resources ) {

								$i = 1;

								foreach ( $clinical_resources as $resource ) {

									$resource_name = get_the_title( $resource );
									$resource_list .= $resource_name;

									if ( count($clinical_resources) > $i ) {

										$resource_list .= ", ";

									}

									$i++;

								} // endforeach

							}

					// Combine the lists

						$filter_list = $provider_list . ', ' . $location_list . ', ' . $expertise_list . ', ' . $condition_list . ', ' . $treatment_list . ', ' . $resource_list;

					// Store the value of the combined list in a field

						$_POST['acf']['field_clinical_resource_asp_filter'] = $filter_list;

			}

	// Location profile

		// Fire before saving data to post (by using a priority less than 10)

			/**
			 * Only updates ACF data
			 */

			add_action('acf/save_post', 'location_save_post', 7);

			function location_save_post( $post_id ) {

				$post_type = get_post_type($post_id);

				// Bail early if no data sent or not location post type

					if (
						empty( $_POST['acf'] )
						||
						( $post_type != 'location' )
					) {

						return;

					}

				// Create full name to store in 'physician_full_name' field

					$featured_image = $_POST['acf']['field_location_featured_image'];
					$wayfinding_image = $_POST['acf']['field_location_wayfinding_photo'];

				// If featured image is set & wayfinding is empty, set wayfinding image to featured image

					if (
						$featured_image
						&&
						empty($wayfinding_image)
					) {

						$_POST['acf']['field_location_wayfinding_photo'] = $featured_image;

					}

				// If wayfinding image is set & featured image is empty, set featured image to wayfinding image

					if (
						empty($featured_image)
						&&
						$wayfinding_image
					) {

						$_POST['acf']['field_location_featured_image'] = $wayfinding_image;

					}

				$has_parent = $_POST['acf']['field_location_parent'];
				$location_parent = $_POST['acf']['field_location_parent_id'];

				if (
					$has_parent
					&&
					!empty($location_parent)
				) {

					$region = array();
					$region[] = get_field( 'location_region', $location_parent);

					$_POST['acf']['field_location_region'] = $region;

				}

			}

		// Fire after saving data to post

			/**
			 * Change post data
			 */

			add_action('acf/save_post', 'location_save_post_after', 20);

			function location_save_post_after( $post_id ) {

				$post_type = get_post_type($post_id);

				// Bail early if not location post type

					if ( $post_type != 'location' ) {

						return;

					}

				$post = get_post($post_id);
				$location_has_parent = get_field('location_parent');
				$location_parent_id = get_field('location_parent_id');

				// If the location has a parent and parent ID is set, set the parent D

					if (
						$location_has_parent
						&&
						$location_parent_id
					) {

						$post->post_parent = $location_parent_id;

					} else { // clear the parent data

						$post->post_parent = 0;

					}

				// Remove this filter to prevent an infinite loop

					remove_filter('acf/save_post', 'save_location_parent');
					wp_update_post($post);

			}

	// Clinical Specialization custom taxonomy

		// Fire before saving data to post (by using a priority less than 10)

			/**
			 * Only updates ACF data
			 */

			 add_action( 'acf/save_post', 'clinical_title_save_post', 5 );

			 function clinical_title_save_post( $post_id ) {

				 // Taxonomy slug

					 $taxonomy = 'clinical_title';

				 // Format the $post_id value to get the integer term ID

					 /**
					  * ACF assigns the $post_id parameter by using term ID prefixed with "term_".
					  */

					 $term_id = str_replace('term_', '', $post_id);

				 // Bail early if no data sent

					 if ( empty( $_POST['acf'] ) ) {

						 return;

					 }

				 // Get term from ID

					 $term = get_term_by( 'id', $term_id, $taxonomy ) ?? null;

				 // Bail early if the term is not a valid object

					 if (
						 !$term
						 ||
						 !is_object($term)
					 ) {

						 return;

					 }

				 // Inherit certain field values from the parent term

					 // Get the term's parent

						 $parent_term_id = $term->parent;
						 $parent_term = $parent_term_id ? ( get_term_by( 'id', $parent_term_id, $taxonomy ) ?? null ) : null;
						 $parent_term = ( !$parent_term || !is_object($parent_term) ) ? null : $parent_term;

					 // If parent term exists, modify term's fields

						 if ( $parent_term ) {

							 // CMS Specialty Code

								 // Get the inheritance query and the parent's field value

									 $cms_code_inherit_query = $_POST['acf']['field_clinical_specialization_cms_code_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $cms_code_inherit_query ) {

										 $cms_code_parent = get_field( 'clinical_specialization_cms_code', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_cms_code'] = $cms_code_parent;

										 $cms_code_parent = get_field( 'clinical_specialization_cms_code_type', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_cms_code_type'] = $cms_code_parent;

									 }

							 // MedicalSpecialty Enumeration Member

								 // Get the inheritance query and the parent's field value

									 $medicalspecialty_member_inherit_query = $_POST['acf']['field_clinical_specialization_medicalspecialty_member_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $medicalspecialty_member_inherit_query ) {

										 $medicalspecialty_member_parent = get_field( 'clinical_specialization_medicalspecialty_member', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_medicalspecialty_member'] = $medicalspecialty_member_parent;

									 }

							 // O*Net-SOC Occupation Code

								 // Get the inheritance query and the parent's field value

									 $onetsoc_code_inherit_query = $_POST['acf']['field_clinical_specialization_onetsoc_code_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $onetsoc_code_inherit_query ) {

										 $onetsoc_code_parent = get_field( 'clinical_specialization_onetsoc_code', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_onetsoc_code'] = $onetsoc_code_parent;

										 $onetsoc_code_parent = get_field( 'clinical_specialization_onetsoc_code_name', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_onetsoc_code_name'] = $onetsoc_code_parent;

									 }

							 // ISCO-08 Code

								 // Get the inheritance query and the parent's field value

									 $isco08_code_inherit_query = $_POST['acf']['field_clinical_specialization_isco08_code_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $isco08_code_inherit_query ) {

										 $isco08_code_parent = get_field( 'clinical_specialization_isco08_code', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_isco08_code'] = $isco08_code_parent;

									 }

							 // Reference Webpages About the Occupation

								 // Get the inheritance query and the parent's field value

									 $sameas_occupation_inherit_query = $_POST['acf']['field_clinical_specialization_sameas_occupation_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $sameas_occupation_inherit_query ) {

										 $sameas_occupation_parent = get_field( 'clinical_specialization_sameas_occupation', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_sameas_occupation'] = $sameas_occupation_parent;

									 }

							 // Reference Webpages About the Specialty / Field

								 // Get the inheritance query and the parent's field value

									 $sameas_field_inherit_query = $_POST['acf']['field_clinical_specialization_sameas_field_inherit_query'];

								 // Conditionally set the field value using the parent term's field value

									 if ( $sameas_field_inherit_query ) {

										 $sameas_field_parent = get_field( 'clinical_specialization_sameas_field', $parent_term) ?? null;
										 $_POST['acf']['field_clinical_specialization_sameas_field'] = $sameas_field_parent;

									 }

						 }

			 }

// Bidirectionally update ACF data

	// Fire before saving data to post (by using a priority less than 10)

		add_action('acf/save_post', 'uamswp_sync_acf_save_post', 5);

		function uamswp_sync_acf_save_post( $post_id ) {

			// Set up the variables

				$post_type = get_post_type( $post_id );
				$values = $_POST['acf'];

			if ( 'location' == $post_type ) {

				$field_name = 'physician_locations';
				$field_key = 'field_location_physicians';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

				$field_name = 'location_expertise';
				$field_key = 'field_location_expertise';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

			}

			if ( 'provider' == $post_type ) {

				$field_name = 'physician_locations';
				$field_key = 'field_physician_locations';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

				$field_name = 'physician_expertise';
				$field_key = 'field_physician_expertise';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

			}

			if ( 'expertise' == $post_type ) {

				$field_name = 'location_expertise';
				$field_key = 'field_expertise_locations';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

				$field_name = 'physician_expertise';
				$field_key = 'field_expertise_physicians';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

				$field_name = 'expertise_associated';
				$field_key = 'field_expertise_associated';

				// Get submitted values

					$value = $values[$field_key];
					bidirectional_acf_update( $field_name, $field_key, $value, $post_id );

			}

		}

	// Function for Bidirectional ACF

		/**
		* Req:
		* $field_name = ACF field name
		* $field_key = ACF field key of field with new value
		* $value = incoming/new value
		* $post_id = $post_id being updated
		 */

		function bidirectional_acf_update(
			$field_name, // Required // ACF field name
			$field_key, // Required // ACF field key of field with new value
			$value, // Required // Incoming/new value
			$post_id // Required // ID of post being updated
		) {

			// Get previous values.

				$old_value = get_field( $field_name, $post_id, false );

			if (
				isset($value)
				&&
				is_array($value)
			) {

				foreach ( $value as $post_id2new ) {

					// load existing related posts

						$value2new = get_field( $field_name, $post_id2new, false );

					// allow for selected posts to not contain a value

						if ( empty($value2new) ) {

							$value2new = array();

						}

					// write_log('New Values for ' . $post_id2new . ': '. print_r($value2new, true));

					// bail early if the current $post_id is already found in selected post's $value2new

						if ( in_array( $post_id, $value2new ) ) continue;

					// append the current $post_id to the selected post's 'related_posts' value

						$value2new[] = $post_id;

					// update the selected post's value (use field's key for performance)

						update_field( $field_key, $value2new, $post_id2new );

				}

			}

			if (
				isset($old_value)
				&&
				is_array($old_value)
			) {

				foreach ( $old_value as $post_id2old ) {

					// bail early if this value has not been removed

						if ( is_array($value) && in_array( $post_id2old, $value ) ) continue;

					// load existing related posts

						$value2old = get_field( $field_name, $post_id2old, false );

					// bail early if no value

						if ( empty($value2old) ) continue;

					// find the position of $post_id within $value2old so we can remove it

						$pos = array_search( $post_id, $value2old );

					// remove

						unset( $value2old[$pos] );

					// update the un-selected post's value (use field's key for performance)

						update_field( $field_key, $value2old, $post_id2old );

				}

			}

		}

// Set the post excerpt from ACF fields

	// Fire after saving data to post

		add_action('acf/save_post', 'custom_excerpt_acf', 50);

		function custom_excerpt_acf() {

			// Bring in variables from outside of the function

				global $post; // WordPress-specific global variable

			// Get the current post ID

				$post_id = ( $post->ID );

			// Get the post type of the current page/post

				$post_type = get_post_type( $post_id );

			// 1. Add post types (key) and corresponding field names (value) to be used to set the excerpt

				$excerpt_field_name = array(
					'provider' => 'physician_short_clinical_bio',
					'location' => 'location_short_desc',
					'expertise' => 'post_excerpt',
					'clinical-resource' => 'clinical_resource_excerpt',
				);

			// 2. Add post types (key) and corresponding field names (value) to be used as a fallback to set the excerpt if the initial fields do not have a value

				$excerpt_fallback_field_name = array(
					'provider' => 'physician_clinical_bio',
					'location' => 'location_about',
				);

			// Set the post excerpt value

				if (
					!empty($post_id) // If the post ID is not empty ...
					&&
					$excerpt_field_name // and if the $excerpt_field_name array has a value ...
					&&
					array_key_exists( $post_type, $excerpt_field_name )//  and if a key for the current post type exists in the array...
				) {

					// Get field value relevant to the current post type

						$post_excerpt = get_field( $excerpt_field_name[$post_type], $post_id ) ?? null;

					// If excerpt is empty, get fallback field value relevant to the current post type

						if (
							!$post_excerpt
							&&
							$excerpt_fallback_field_name // and if the $excerpt_fallback_field_name array has a value ...
							&&
							array_key_exists( $post_type, $excerpt_fallback_field_name )//  and if a key for the current post type exists in the array...
						) {

							$post_excerpt = get_field( $excerpt_fallback_field_name[$post_type], $post_id ) ?? null;

							if ( $post_excerpt ) {

								$post_excerpt = wp_strip_all_tags($post_excerpt);
								$post_excerpt = str_replace("\n", ' ', $post_excerpt); // Strip line breaks
								$post_excerpt = strlen($post_excerpt) > 160 ? mb_strimwidth($post_excerpt, 0, 156, '...') : $post_excerpt; // Limit to 160 characters

							}

						}

				} else {

					$post_excerpt = '';

				}

			// Update the post with the new excerpt value

				/**
				 * Source: https://support.advancedcustomfields.com/forums/topic/set-wordpress-excerpt-and-post-thumbnail-based-on-custom-field/#post-49965
				 */

				if (
					!empty($post_id) // If the post ID is not empty ...
					&&
					$post_excerpt // and if the post excerpt value exists ...
				) {

					// Define an array of elements that make up a post to update or insert.

						$post_array = array(
							'ID' => $post_id,
							'post_excerpt' => $post_excerpt
						);

					// Unhook this function so it doesn't loop infinitely

						remove_action( 'save_post', 'custom_excerpt_acf', 50 );

					// Update the post with new post data

						wp_update_post(
							$post_array // array|object // Optional // Post data. Arrays are expected to be escaped, objects are not. See wp_insert_post() for accepted arguments. // Default array()
						);

					// Re-hook this function

						add_action( 'save_post', 'custom_excerpt_acf', 50 );

				}

		}

// Register Custom Blocks

	if ( function_exists('acf_register_block_type') ) {

		// Register "FacetWP Cards" block

			acf_register_block_type(array(
				'name' => 'uamswp_fad_facetwp_cards',
				'title' => 'FacetWP Cards',
				'description' => '',
				'category' => 'common',
				'keywords' => array(
					0 => 'provider',
					1 => 'location',
					2 => 'facetwp',
				),
				'mode' => 'auto',
				'align' => '',
				'render_template' => '',
				'render_callback' => 'fad_facetwp_cards_callback',
				'enqueue_style' => '',
				'enqueue_script' => '',
				'enqueue_assets' => '',
				'icon' => 'id',
				'supports' => array(
					'align' => array('full'),
					'mode' => true,
					'multiple' => true,
				),
			));

		// Register "FacetWP Block" block

			acf_register_block_type(array(
				'name' => 'uamswp_fad_facetwp_blocks',
				'title' => 'FacetWP Block',
				'description' => '',
				'category' => 'common',
				'keywords' => array(
					0 => 'facetwp',
					1 => 'shortcode',
				),
				'mode' => 'auto',
				'align' => '',
				'render_template' => '',
				'render_callback' => 'fad_facetwp_blocks_callback',
				'enqueue_style' => '',
				'enqueue_script' => '',
				'enqueue_assets' => '',
				'icon' => 'list-view',
				'supports' => array(
					'align' => true,
					'mode' => true,
					'multiple' => true,
				),
			));

	} // endif

	// FacetWP Cards Callback Function

		/**
		 * @param	array $block The block settings and attributes.
		 * @param	string $content The block inner HTML (empty).
		 * @param	bool $is_preview True during AJAX preview.
		 * @param	(int|string) $post_id The post ID this block is saved to.
		 */

		function fad_facetwp_cards_callback(
			$block, // array // Required // The block settings and attributes.
			$content = '', // string // Optional // The block inner HTML (empty).
			$is_preview = false, // bool // Optional // True during AJAX preview.
			$post_id = 0 // (int|string) // Optional // The post ID this block is saved to.
		) {

			// Create id attribute allowing for custom "anchor" value.

				$id = 'facetwp-cards-' . $block['id'];

				if ( !empty($block['anchor']) ) {

					$id = $block['anchor'];

				}

			// Create class attribute allowing for custom "className" and "align" values.

				$className = 'facetwp-cards';

				if ( !empty($block['className']) ) {

					$className .= ' ' . $block['className'];

				}

				if ( !empty($block['align']) ) {

					$className .= ' align' . $block['align'];

				}

			// Load values and assign defaults.

				$heading = get_field('facetwp_heading') ?: 'Cards List';
				$template = get_field('facetwp_template_name');
				$background_color = get_field('facetwp_background_color') ?: 'bg-white';

			?>
			<section class="uams-module container-fluid p-8 p-sm-10 <?php echo $className; ?> <?php echo $background_color; ?>">
				<div class="row">
					<div class="col-12">
						<h2 class="module-title"><?php echo $heading; ?></h2>
						<div class="card-list-container">
							<?php echo facetwp_display( 'template', $template ); ?>
						</div>
					</div>
				</div>
			</section>
			<?php

		}

	// FacetWP Block Callback Function

		/**
		 * @param	array $block The block settings and attributes.
		 * @param	string $content The block inner HTML (empty).
		 * @param	bool $is_preview True during AJAX preview.
		 * @param	(int|string) $post_id The post ID this block is saved to.
		 */

		function fad_facetwp_blocks_callback(
			$block, // array // RequiredThe block settings and attributes.
			$content = '', // string // Optional // The block inner HTML (empty).
			$is_preview = false, // bool // Optional // True during AJAX preview.
			$post_id = 0 // (int|string) // Optional // The post ID this block is saved to.
		) {

			// Create id attribute allowing for custom "anchor" value.

				$id = 'facetwp-block-' . $block['id'];

				if ( !empty($block['anchor']) ) {

					$id = $block['anchor'];

				}

			// Create class attribute allowing for custom "className" and "align" values.

				$className = 'facetwp-blocks';

				if ( !empty($block['className']) ) {

					$className .= ' ' . $block['className'];

				}

				if ( !empty($block['align']) ) {

					$className .= ' align' . $block['align'];

				}

			// Load values and assign defaults.

				$heading = get_field('facetwp_block_heading') ?: 'Cards List';
				$hideheading = get_field('facetwp_block_hide_heading');
				$prefacets = get_field('facetwp_block_pre_template_facets');
				$template = get_field('facetwp_block_facet_template');
				$postfacets = get_field('facetwp_block_post_template_facets');
				$pager = get_field('facetwp_block_include_pager');
				$background_color = get_field('facetwp_block_background_color') ?: 'bg-white';

			?>
			<section class="uams-module <?php echo $className; ?> <?php echo $background_color; ?>">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title<?php echo ('1' == $hideheading ? ' sr-only': ''); ?>" ><?php echo $heading; ?></h2>
							<div class="">
								<?php
								if ($prefacets) {
									foreach ($prefacets as $prefacet) {
										echo '<div class="text-'. $prefacet['alignment'] .'">'. facetwp_display( 'facet', $prefacet['facet_name'] ) .'</div>';
									}
								}
								?>
								<?php echo facetwp_display( 'template', $template ); ?>
								<?php
								if ($postfacets) {
									foreach ($postfacets as $postfacet) {
										echo '<div class="text-'. $postfacet['alignment'] .'">'. facetwp_display( 'facet', $postfacet['facet_name'] ) .'</div>';
									}
								}
								echo ($pager ? facetwp_display( 'pager' ) : '');
								?>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php

		}

// Populate ACFE dynamic message fields with current value(s) defined in Find-a-Doc Settings

	// Location alert

		add_action('acf/render_field/name=location_current_alert', 'location_current_alert_message');

		function location_current_alert_message() {

			$alert_title = get_field('location_alert_heading_system', 'option');
			$alert_body = get_field('location_alert_body_system', 'option');
			$alert_color = get_field('location_alert_color_system', 'option');

			if (
				!empty($alert_title)
				&&
				!empty($alert_body)
			) {

				$alert_txt = '<blockquote class="notice notice-warning">';
				$alert_txt .= '<h3 class="notice-title">'. $alert_title .'</h3>';
				$alert_txt .= $alert_body;
				$alert_txt .= '<hr />';
				$alert_txt .= '<p><strong>Alert color:</strong> '. ucfirst(str_replace( 'alert-', '', $alert_color)) .'</p>';
				$alert_txt .= '</blockquote>';

				echo $alert_txt;

			} else {

				echo 'None active';

			}

		}

	// Location prescription information

		// Information instructing patients to call the clinic for prescriptions

			add_action('acf/render_field/name=location_current_prescription_clinic', 'location_current_prescription_clinic_message');

			function location_current_prescription_clinic_message(){

				$prescription_clinic_sys = get_field('location_prescription_clinic_system', 'option');

				if ( !empty($prescription_clinic_sys) ) {

					$prescr_txt = '<blockquote class="notice notice-info">';
					$prescr_txt .= $prescription_clinic_sys;
					$prescr_txt .= '</blockquote>';

					echo $prescr_txt;

				} else {

					echo 'None active';

				}

			}

		// Information instructing patients to call their pharmacy for prescriptions

			add_action('acf/render_field/name=location_current_prescription_pharm', 'location_current_prescription_pharm_message');

			function location_current_prescription_pharm_message(){

				$prescription_pharm_sys = get_field('location_prescription_pharm_system', 'option');

				if ( !empty($prescription_pharm_sys) ) {

					$prescr_txt = '<blockquote class="notice notice-info">';
					$prescr_txt .= $prescription_pharm_sys;
					$prescr_txt .= '</blockquote>';

					echo $prescr_txt;

				} else {

					echo 'None active';

				}

			}

// Limit ACF field results to top-level pages/posts that are published

	add_filter('acf/fields/post_object/query/key=field_location_parent_id', 'limit_post_top_level', 10, 3);

	function limit_post_top_level( $args, $field, $post ) {

		$args['post_parent'] = 0;
		// $args['sort_order'] = 'ASC';
		// $args['orderby'] = 'title';
		// $args['order'] = 'ASC';
		$args['post_status'] = 'publish';

		return $args;

	}

// ACF Image Field Image Aspect Ratio Validation

	/**
	 * Adds a field setting to ACF Image fields and validates images
	 * to ensure that they meet image aspect ratio requirement
	 *
	 * This also serves as an example of how to add multiple settings
	 * to a single row when adding settings to an ACF field type
	 *
	 * side note: after implementing this code clear your browser cache
	 * to ensure the needed JS and WP media window is refreshed
	 *
	 * What is "Margin"?
	 *
	 * Let's say that you set an aspect ratio of 1:1 with a margin of 10%
	 * If the width of the image is 100 pixels, this means that the
	 * height of the image can be from 90 pixels to 110 pixels
	 * 100 +/- 10% (10px)
	 *
	 * If the aspect ration is set to 4:3 and the margin at 1%
	 * if the width of the uploaded image is 800 pixels
	 * then the height can be 594 to 606 pixels
	 * 600 +/- 1% (6px)
	 */

	// Add new settings for aspect ratio to image field

		add_filter('acf/render_field_settings/type=image', 'acf_image_aspect_ratio_settings', 20);

		function acf_image_aspect_ratio_settings($field) {

			/**
			 * The technique used for adding multiple fields to a single setting is copied
			 * directly from the ACF Image field code. Anything that ACF does can be
			 * replicated, you just need to look at how Elliot does it also, any ACF field
			 * type can be used as a setting field for other field types.
			 */

			$args = array(
				'name' => 'ratio_width',
				'type' => 'number',
				'label' => __('Aspect Ratio'),
				'instructions' => __('Restrict which images can be uploaded'),
				'default_value' => 0,
				'min' => 0,
				'step' => 1,
				'prepend' => __('Width'),
			);

			acf_render_field_setting($field, $args);

			$args = array(
				'name' => 'ratio_height',
				'type' => 'number',
				// notice that there's no label when appending a setting
				'label' => '',
				'default_value' => 0,
				'min' => 0,
				'step' => 1,
				'prepend' => __('Height'),
				// this how we append a setting to the previous one
				'wrapper' => array(
					'data-append' => 'ratio_width',
					'width' => '',
					'class' => '',
					'id' => ''
				)
			);

			acf_render_field_setting($field, $args);

			$args = array(
				'name' => 'ratio_margin',
				'type' => 'number',
				'label' => '',
				'default_value' => 0,
				'min' => 0,
				'step' => .5,
				'prepend' => __('&plusmn;'),
				'append' => __('%'),
				'wrapper' => array(
					'data-append' => 'ratio_width',
					'width' => '',
					'class' => '',
					'id' => ''
				)
			);

			acf_render_field_setting($field, $args);

		} // end function acf_image_aspect_ratio_settings

	// Add filter to validate images to ratio

		add_filter('acf/validate_attachment/type=image', 'acf_image_aspect_ratio_validate', 10, 5);

		function acf_image_aspect_ratio_validate($errors, $file, $attachment, $field, $content) {

			// Check to make sure everything has a value

				if (
					empty($field['ratio_width'])
					||
					empty($field['ratio_height'])
					||
					empty($file['width'])
					||
					empty($file['height'])
				) {

					/**
					 * The values we need are not set or otherwise empty.
					 *
					 * Bail early.
					 */

					return $errors;

				}

			// Make sure all values are numbers. You never know.

				$ratio_width = intval($field['ratio_width']);
				$ratio_height = intval($field['ratio_height']);

			// Make sure we don't try to divide by 0

				if (
					!$ratio_width
					||
					!$ratio_height
				) {

					/**
					 * You cannot do calculations if something is 0.
					 *
					 * Bail early.
					 */

					return $errors;

				}

			$width = intval($file['width']);
			$height = intval($file['height']);

			// Do simple ratio math to see how tall the image is allowed to be based on width

				$allowed_height = $width/$ratio_width*$ratio_height;

			// Get the margin and calculate min/max

				$margin = 0;

				if ( !empty($field['ratio_margin']) ) {

					$margin = floatval($field['ratio_margin']);

				}

				$margin = $margin/100; // convert % to decimal
				$min = round($allowed_height - ($allowed_height*$margin));
				$max = round($allowed_height + ($allowed_height*$margin));

				if (
					$height < $min
					||
					$height > $max
				) {

					/**
					 * It does not meet the requirement.
					 *
					 * Generate an error.
					 */

					$errors['aspect_ratio'] = __('Image does not meet Aspect Ratio Requirements of ').
						$ratio_width.__(':').$ratio_height.__(' (±').($margin*100).__('%)');

				}

			// Return the errors

				return $errors;

		} // end function acf_image_aspect_ratio_validate

// Render shortcode(s) in provider editor's Pubmed Information (HTML) field

	function pubmed_information_format_value( $value, $post_id, $field ) {

		// Render shortcodes in all textarea values.

			return html_entity_decode( $value ); // Convert HTML entities to their corresponding characters

	}

	add_filter('acf/format_value/key=field_physician_select_publications_pubmed', 'pubmed_information_format_value', 10, 3);

// Modify ACF Relationship fields

	/**
	 * Field type documentation: https://www.advancedcustomfields.com/resources/relationship/
	 */

	// Exclude current post/page from ACF Relationship field results

		/**
		 * Filter documentation: https://www.advancedcustomfields.com/resources/acf-fields-relationship-query/
		 */

		// 1. Add a filter for each specific relationship field with key=[NAME_OF_RELATIONSHIP_FIELD].

			add_filter('acf/fields/relationship/query/key=field_clinical_resource_related', 'relationship_exclude_id', 10, 3);
			add_filter('acf/fields/relationship/query/key=field_expertise_associated', 'relationship_exclude_id', 10, 3);

		// 2. Add the $field and $post arguments.

			function relationship_exclude_id ( $args, $field, $post_id ) {

				// 3. $post argument passed in from the query hook is the $post_id.

					$args['post__not_in'] = array( $post_id );

				return $args;

			}

// Conditional Logic to check if Find-a-Doc Settings are set to allow MyChart Scheduling

	function uamswp_mychart_scheduling_query($field) {

		/**
		 * Set to field name for option
		 */

		if ( get_field( 'mychart_scheduling_query_system', 'option' ) ) {

			return $field;

		} else {

			return;

		}

	}

	/**
	 * Make sure to use correct field key for tab
	 */

	add_filter('acf/prepare_field/key=field_location_scheduling_tab', 'uamswp_mychart_scheduling_query', 20);

// Populate ACF field choices with terms from custom taxonomies

	// UAMS Brand Organizations

		add_filter( 'acf/load_field/key=field_fad_default_brandorg_clinical', 'uamswp_fad_acf_load_brandorg_uams' ); // Default Clinical UAMS Brand Organization
		add_filter( 'acf/load_field/key=field_fad_default_brandorg_affiliation', 'uamswp_fad_acf_load_brandorg_uams' ); // Default UAMS Brand Organizations as Affiliations
		add_filter( 'acf/load_field/key=field_fad_default_brandorg_credit', 'uamswp_fad_acf_load_brandorg_uams' ); // Default UAMS Brand Organizations to Credit
		add_filter( 'acf/load_field/key=field_fad_default_brandorg_locationcreated', 'uamswp_fad_acf_load_brandorg_uams' ); // Default UAMS Brand Organizations as Location Created
		add_filter( 'acf/load_field/key=field_fad_default_brandorg_copyright', 'uamswp_fad_acf_load_brandorg_uams' ); // Default UAMS Brand Organizations as Copyright Holder

		function uamswp_fad_acf_load_brandorg_uams ( $field ) {

			// Get all taxonomy terms

				$brand_organizations = get_terms(
					array(
						'taxonomy' => 'brand_organization_uams',
						'hide_empty' => false
					)
				);

			// Add each term to the choices array.

				/*

					Example: $field['choices']['review'] = Review

				*/

				foreach ( $brand_organizations as $item ) {

					$field['choices'][$item->slug] = $item->name;

				}

			return $field;

		}

	// Third-Party Brand Organizations

		add_filter( 'acf/load_field/key=field_schema_brandorg', 'uamswp_fad_acf_load_brandorg_3p' );
		add_filter( 'acf/load_field/key=field_schema_brandorg_multiple', 'uamswp_fad_acf_load_brandorg_3p' );

		function uamswp_fad_acf_load_brandorg_3p ( $field ) {

			// Get all taxonomy terms

				$brand_organizations = get_terms(
					array(
						'taxonomy' => 'brand_organization',
						'hide_empty' => false
					)
				);

			// Add each term to the choices array.

				/*

					Example: $field['choices']['review'] = Review

				*/

				foreach ( $brand_organizations as $item ) {

					$field['choices'][$item->slug] = $item->name;

				}

			return $field;

		}