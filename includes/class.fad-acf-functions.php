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
							$provider_list = '';
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
							$location_list = '';
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
							$expertise_list = '';
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
							$condition_list = '';
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
							$treatment_list = '';
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
							$resource_list = '';
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

// Provider Spotlight (clinical resource type "provider_spotlight")

	// Get a value submitted with the current form, by field key
	//
	// Validation runs before anything is written to the database, so the type
	// the editor just picked -- and the sibling fields they just filled in --
	// can only be read back out of the submitted values.

		function uamswp_spotlight_submitted_value( $field_key ) {

			$acf = function_exists('acf_maybe_get_POST') ? acf_maybe_get_POST('acf') : ( $_POST['acf'] ?? null );

			if (
				is_array($acf)
				&&
				isset($acf[$field_key])
			) {

				return $acf[$field_key];

			}

			return null;

		}

	// Resolve the resource type being validated
	//
	// Prefer the submitted value. Fall back to the stored value for saves that
	// do not post the whole field group (REST, programmatic updates), so those
	// are validated against the type the post actually has.

		function uamswp_spotlight_current_type() {

			$submitted = uamswp_spotlight_submitted_value('field_clinical_resource_type');

			if ( $submitted !== null ) {

				return is_array($submitted) ? ( $submitted['value'] ?? '' ) : $submitted;

			}

			$post_id = function_exists('acf_maybe_get_POST') ? acf_maybe_get_POST('post_ID') : ( $_POST['post_ID'] ?? null );

			if ( !$post_id || !is_numeric($post_id) ) {

				return null;

			}

			$stored = get_field( 'clinical_resource_type', $post_id );

			return is_array($stored) ? ( $stored['value'] ?? '' ) : $stored;

		}

		function uamswp_is_spotlight_submission() {

			return 'provider_spotlight' === uamswp_spotlight_current_type();

		}

	// Limit the Featured Provider field to published providers

		add_filter( 'acf/fields/post_object/query/key=field_clinical_resource_spotlight_provider', 'uamswp_spotlight_provider_query', 10, 3 );

		function uamswp_spotlight_provider_query( $args, $field, $post_id ) {

			$args['post_status'] = 'publish';

			return $args;

		}

	// Conditional required-ness
	//
	// The Short Description and Featured Image fields are no longer required in
	// the field group, because a Provider Spotlight generates both from the
	// featured provider on save. Every other resource type must still supply
	// them, which is enforced here rather than by ACF.

		add_filter( 'acf/validate_value/key=field_clinical_resource_excerpt', 'uamswp_spotlight_validate_excerpt', 10, 4 );

		function uamswp_spotlight_validate_excerpt( $valid, $value, $field, $input_name ) {

			if ( $valid !== true ) {

				return $valid;

			}

			// A spotlight generates its Short Description on save
			if ( uamswp_is_spotlight_submission() ) {

				return $valid;

			}

			if ( '' === trim( (string) $value ) ) {

				return 'Short Description is required.';

			}

			return $valid;

		}

		add_filter( 'acf/validate_value/key=field_clinical_resource_featured_image', 'uamswp_spotlight_validate_featured_image', 10, 4 );

		function uamswp_spotlight_validate_featured_image( $valid, $value, $field, $input_name ) {

			if ( $valid !== true ) {

				return $valid;

			}

			// A spotlight resolves its featured image on save, from the override
			// image or the provider's photos. The field is hidden for that type,
			// so ACF does not submit it and this filter should not fire at all --
			// the guard is here in case that changes.
			if ( uamswp_is_spotlight_submission() ) {

				return $valid;

			}

			if ( empty($value) ) {

				return 'Featured Image is required.';

			}

			return $valid;

		}

	// A spotlight must be able to resolve an image for social sharing and cards
	//
	// Precedence on save is: the override image, then the provider's wide
	// portrait, then the provider's headshot. If a provider carries neither
	// photo and no override was uploaded, there is nothing to fall back to, so
	// the save is blocked here -- on the provider field, which is the one the
	// editor can actually see for this type.

		add_filter( 'acf/validate_value/key=field_clinical_resource_spotlight_provider', 'uamswp_spotlight_validate_provider', 10, 4 );

		function uamswp_spotlight_validate_provider( $valid, $value, $field, $input_name ) {

			if ( $valid !== true ) {

				return $valid;

			}

			// An empty value is handled by the field's own required setting
			if ( empty($value) ) {

				return $valid;

			}

			// An override image makes the provider's photos irrelevant
			if ( uamswp_spotlight_submitted_value('field_clinical_resource_spotlight_image_wide') ) {

				return $valid;

			}

			$provider_id = is_array($value) ? ( $value[0] ?? 0 ) : $value;
			$provider_id = (int) $provider_id;

			if ( !$provider_id ) {

				return $valid;

			}

			// Wide portrait, then headshot
			if (
				get_field( 'physician_image_wide', $provider_id )
				||
				get_post_thumbnail_id( $provider_id )
			) {

				return $valid;

			}

			return 'This provider has no wide portrait and no headshot, so there is no image to use for social sharing and cards. Add a Featured Image Override below, or add a photo to the provider.';

		}

	// Guard the Q&A repeater minimum server-side

		add_filter( 'acf/validate_value/key=field_clinical_resource_spotlight_qa', 'uamswp_spotlight_validate_qa', 10, 4 );

		function uamswp_spotlight_validate_qa( $valid, $value, $field, $input_name ) {

			if ( $valid !== true ) {

				return $valid;

			}

			if ( !uamswp_is_spotlight_submission() ) {

				return $valid;

			}

			if (
				empty($value)
				||
				(
					is_array($value)
					&&
					count($value) < 1
				)
			) {

				return 'A Provider Spotlight needs at least one question and answer.';

			}

			return $valid;

		}

	// Seed the default questions on new clinical resources
	//
	// Rows are keyed by sub-field key, which is how the repeater's own
	// load_value() returns them; format_value() re-keys to names afterwards.
	// Only auto-drafts are seeded, so an editor who deliberately removes a
	// question does not get it back, and only in the admin, so an empty
	// repeater never fabricates rows on the front end.

		add_filter( 'acf/load_value/key=field_clinical_resource_spotlight_qa', 'uamswp_spotlight_seed_questions', 10, 3 );

		function uamswp_spotlight_seed_questions( $value, $post_id, $field ) {

			if ( !is_admin() ) {

				return $value;

			}

			if ( !empty($value) ) {

				return $value;

			}

			if ( !$post_id || !is_numeric($post_id) ) {

				return $value;

			}

			if ( 'clinical-resource' !== get_post_type($post_id) ) {

				return $value;

			}

			// Only a brand-new post
			if ( 'auto-draft' !== get_post_status($post_id) ) {

				return $value;

			}

			$rows = array();

			foreach ( uamswp_spotlight_default_questions() as $question ) {

				$rows[] = array(
					'field_clinical_resource_spotlight_qa_question' => $question,
					'field_clinical_resource_spotlight_qa_answer'   => '',
				);

			}

			return $rows;

		}

	// Read the stored resource type of a post, flattening the array return format

		function uamswp_spotlight_stored_type( $post_id ) {

			$stored = get_field( 'clinical_resource_type', $post_id );

			return is_array($stored) ? ( $stored['value'] ?? '' ) : (string) $stored;

		}

	// Normalize a relationship / post_object value to an array of post IDs

		function uamswp_spotlight_to_ids( $value ) {

			if ( empty($value) ) {

				return array();

			}

			$value = is_array($value) ? $value : array( $value );

			return array_values( array_unique( array_filter( array_map('intval', $value) ) ) );

		}

	// Reconcile the featured provider into the shared Related Providers field
	//
	// Runs at priority 5, which is before resources_save_post() at 6 and before
	// ACF writes the submitted values at 10. Both of those matter:
	//
	//  - resources_save_post() builds the Ajax Search Pro filter string by
	//    reading $_POST['acf']['field_clinical_resource_providers'] directly,
	//    not the database, so the featured provider has to be injected into the
	//    submitted values rather than written with update_field(). Otherwise the
	//    spotlight is not findable by the provider's name.
	//  - ACF then saves that same array at priority 10, and the field's
	//    bidirectional partner (physician_clinical_resources) is updated as part
	//    of that write, so the provider gains the spotlight in its own Related
	//    Clinical Resources list with no second mechanism.
	//
	// The featured provider is added to the existing set, never over it: the
	// editor's own Related Providers entries survive. The previously featured
	// provider is dropped when the featured provider changes, or when the
	// resource stops being a spotlight, so the stale bidirectional link is
	// subtracted rather than left behind.

		add_action( 'acf/save_post', 'uamswp_spotlight_reconcile_providers', 5 );

		function uamswp_spotlight_reconcile_providers( $post_id ) {

			// Bail early if no data sent or not clinical resource post type

				if (
					empty( $_POST['acf'] )
					||
					!is_numeric( $post_id )
					||
					'clinical-resource' !== get_post_type( $post_id )
				) {

					return;

				}

			$submitted_type = uamswp_spotlight_current_type();

			// The submitted provider, and the one this resource featured before
			// this save. At priority 5 the database still holds the old value.

				$new_provider = 0;

				if ( isset( $_POST['acf']['field_clinical_resource_spotlight_provider'] ) ) {

					$new_provider_value = $_POST['acf']['field_clinical_resource_spotlight_provider'];
					$new_provider_value = is_array($new_provider_value) ? reset($new_provider_value) : $new_provider_value;
					$new_provider = (int) $new_provider_value;

				}

				$old_provider = (int) get_field( 'clinical_resource_spotlight_provider', $post_id );

			// Nothing to reconcile if this resource has never had a featured
			// provider and is not gaining one now

				if ( !$new_provider && !$old_provider ) {

					return;

				}

			// Start from the submitted Related Providers value. When the field
			// was not submitted at all, ACF will leave the stored value alone,
			// so start from the database instead -- starting from an empty array
			// would wipe the editor's providers.

				$field_submitted = array_key_exists( 'field_clinical_resource_providers', $_POST['acf'] );

				if ( $field_submitted ) {

					$providers = uamswp_spotlight_to_ids( $_POST['acf']['field_clinical_resource_providers'] );

				} else {

					$providers = uamswp_spotlight_to_ids( get_field( 'clinical_resource_providers', $post_id ) );

				}

				$original = $providers;

			// Drop the previously featured provider when it is being replaced,
			// or when this resource is no longer a spotlight -- but only when the
			// Related Providers field was NOT submitted. When it was, its value is
			// the editor's explicit intent: a provider they kept there survives,
			// even if it was the one previously featured. The bidirectional pass
			// still subtracts any provider the editor actually removed, since it
			// will simply be absent from the submitted set.

				if (
					!$field_submitted
					&&
					$old_provider
					&&
					(
						'provider_spotlight' !== $submitted_type
						||
						$new_provider !== $old_provider
					)
				) {

					$providers = array_values( array_diff( $providers, array( $old_provider ) ) );

				}

			// Add the featured provider

				if (
					'provider_spotlight' === $submitted_type
					&&
					$new_provider
					&&
					!in_array( $new_provider, $providers, true )
				) {

					$providers[] = $new_provider;

				}

			// Only rewrite the submitted value when it actually changed

				if ( $providers !== $original ) {

					$_POST['acf']['field_clinical_resource_providers'] = array_map( 'strval', $providers );

				}

		}

	// Keep a spotlight's title and slug in sync with its featured provider
	//
	// The title is derived so it never drifts from the provider profile and
	// never collides with it in search or SEO:
	//
	//  - Title: "Get to Know {full name}" (e.g. "Get to Know W. Ryan Wood,
	//    O.D."), refreshed on every save so a provider rename flows through.
	//  - Slug: built from the prefix- and degree-free name ("W. Ryan Wood" ->
	//    get-to-know-w-ryan-wood) and then frozen once the resource is
	//    published, since changing a live URL breaks inbound links. While the
	//    resource is still a draft the slug tracks the provider.
	//
	// Runs at priority 20, after ACF writes the submitted values at 10, so
	// get_field() returns the values saved in this same request.

		add_action( 'acf/save_post', 'uamswp_spotlight_sync_title', 20 );

		function uamswp_spotlight_sync_title( $post_id ) {

			// Only clinical resources that are (still) a spotlight with a
			// featured provider

				if (
					!is_numeric( $post_id )
					||
					'clinical-resource' !== get_post_type( $post_id )
					||
					'provider_spotlight' !== uamswp_spotlight_stored_type( $post_id )
				) {

					return;

				}

				$provider_id = (int) get_field( 'clinical_resource_spotlight_provider', $post_id );

				if ( !$provider_id ) {

					return;

				}

			$names = uamswp_provider_names( $provider_id );

			// The name variants can carry &nbsp; (for a generational suffix), so
			// normalize it to a regular space for these plain-text fields.

				$full = trim( str_replace( '&nbsp;', ' ', $names['full'] ) );

				// The provider-name ACF fields are plain-text and receive no kses
				// filtering, so strip any markup before it is persisted into the
				// spotlight's post_title (rendered on the public spotlight page).

				$full = wp_strip_all_tags( $full );

				if ( '' === $full ) {

					return;

				}

			$post = get_post( $post_id );

			if ( !$post ) {

				return;

			}

			$update = array();

			// Title: always track the provider

				$new_title = 'Get to Know ' . $full;

				if ( $post->post_title !== $new_title ) {

					$update['post_title'] = $new_title;

				}

			// Slug: derive it once, then freeze so a later provider change or
			// rename never rewrites a live URL. Freezing is tracked with a meta
			// flag set at publish -- not the publish state itself -- because a
			// direct publish (no prior draft save) reaches this hook already
			// published, and gating on !$published would skip derivation entirely,
			// leaving the permalink on a placeholder slug. Drafts keep tracking the
			// provider until the flag is set.

				if ( !get_post_meta( $post_id, '_uamswp_spotlight_slug_locked', true ) ) {

					$slug_name = trim( str_replace( '&nbsp;', ' ', $names['medium_no_prefix'] ) );

					if ( '' !== $slug_name ) {

						$new_slug = sanitize_title( 'Get to Know ' . $slug_name );

						if ( $new_slug && $post->post_name !== $new_slug ) {

							$update['post_name'] = $new_slug;

						}

						// Once published, freeze: the permalink is now live.
						if ( in_array( $post->post_status, array( 'publish', 'future', 'private' ), true ) ) {

							update_post_meta( $post_id, '_uamswp_spotlight_slug_locked', '1' );

						}

					}

				}

			if ( !$update ) {

				return;

			}

			$update['ID'] = $post_id;

			// Unhook around the nested save so this does not re-enter

				remove_action( 'acf/save_post', 'uamswp_spotlight_sync_title', 20 );

				wp_update_post( $update );

				add_action( 'acf/save_post', 'uamswp_spotlight_sync_title', 20 );

		}

	// Resolve the 16:9 image a spotlight uses for social sharing and cards
	//
	// Precedence: the override image, then the provider's wide portrait, then
	// the provider's headshot. Validation (see above) refuses to save a
	// spotlight for which all three are missing.

		function uamswp_spotlight_resolve_image( $post_id, $provider_id ) {

			$override = get_field( 'clinical_resource_spotlight_image_wide', $post_id );

			if ( $override ) {

				return (int) $override;

			}

			$wide = get_field( 'physician_image_wide', $provider_id );

			if ( $wide ) {

				return (int) $wide;

			}

			return (int) get_post_thumbnail_id( $provider_id );

		}

	// Derive a spotlight's featured image and short description on save
	//
	// Runs at priority 20: after ACF has written the submitted values at 10, so
	// the override image and excerpt can be read back, and before
	// custom_excerpt_acf() at 50, so a generated Short Description still flows
	// through into post_excerpt.
	//
	// clinical_resource_image_square is deliberately left empty. resource-card.php
	// falls back to the wide image for its 1:1 crop, so the same source image is
	// used at both aspect ratios with no per-surface code.

		add_action( 'acf/save_post', 'uamswp_spotlight_save', 20 );

		function uamswp_spotlight_save( $post_id ) {

			if (
				!is_numeric( $post_id )
				||
				'clinical-resource' !== get_post_type( $post_id )
				||
				'provider_spotlight' !== uamswp_spotlight_stored_type( $post_id )
			) {

				return;

			}

			$provider_id = (int) get_field( 'clinical_resource_spotlight_provider', $post_id );

			if ( !$provider_id ) {

				return;

			}

			// Featured image, which SEOPress also reads for og:image

				$image_id = uamswp_spotlight_resolve_image( $post_id, $provider_id );

				if (
					$image_id
					&&
					$image_id != get_post_thumbnail_id( $post_id )
				) {

					set_post_thumbnail( $post_id, $image_id );

				}

			// Short description: always regenerated from the provider. The input is
			// hidden for spotlights, so it is fully provider-driven -- overwriting
			// also clears any stale value and picks up changes to the generated
			// prose. custom_excerpt_acf() copies it to post_excerpt at priority 50.

				$generated = uamswp_spotlight_default_excerpt( $provider_id );

				if ( $generated ) {

					update_field( 'clinical_resource_excerpt', $generated, $post_id );

				}

		}

	// Assert or release the link between a spotlight and its featured provider
	//
	// Writing either side of the bidirectional pair updates the other, so these
	// touch one side and let ACF propagate. Both sides are checked first, so a
	// half-broken link heals and an already-correct one costs nothing.

		function uamswp_spotlight_assert_link( $post_id, $provider_id ) {

			$providers = uamswp_spotlight_to_ids( get_field( 'clinical_resource_providers', $post_id ) );

			if ( !in_array( $provider_id, $providers, true ) ) {

				$providers[] = $provider_id;

				update_field( 'clinical_resource_providers', $providers, $post_id );

			}

			$resources = uamswp_spotlight_to_ids( get_field( 'physician_clinical_resources', $provider_id ) );

			if ( !in_array( $post_id, $resources, true ) ) {

				$resources[] = $post_id;

				update_field( 'physician_clinical_resources', $resources, $provider_id );

			}

		}

		function uamswp_spotlight_release_link( $post_id, $provider_id ) {

			$providers = uamswp_spotlight_to_ids( get_field( 'clinical_resource_providers', $post_id ) );

			if ( in_array( $provider_id, $providers, true ) ) {

				update_field( 'clinical_resource_providers', array_values( array_diff( $providers, array( $provider_id ) ) ), $post_id );

			}

			$resources = uamswp_spotlight_to_ids( get_field( 'physician_clinical_resources', $provider_id ) );

			if ( in_array( $post_id, $resources, true ) ) {

				update_field( 'physician_clinical_resources', array_values( array_diff( $resources, array( $post_id ) ) ), $provider_id );

			}

		}

	// Re-assert the featured link when a provider is saved
	//
	// The bidirectional pair is symmetric and the provider's Related Clinical
	// Resources list is editor-editable, so removing a spotlight from the
	// provider's side would otherwise subtract the provider from the spotlight,
	// leaving it out of step with the featured provider it still names.
	//
	// A spotlight owns its featured-provider link, so it wins: this runs at
	// priority 20, after ACF has written the provider's values and applied the
	// bidirectional pass at 10, and puts any published spotlight that names this
	// provider back. A spotlight cannot be unlinked from the provider's generic
	// list, by design.

		add_action( 'acf/save_post', 'uamswp_spotlight_provider_guard', 20 );

		function uamswp_spotlight_provider_guard( $post_id ) {

			if (
				!is_numeric( $post_id )
				||
				'provider' !== get_post_type( $post_id )
			) {

				return;

			}

			$spotlights = uamswp_spotlight_posts_for_provider( (int) $post_id );

			foreach ( $spotlights as $spotlight_id ) {

				uamswp_spotlight_assert_link( $spotlight_id, (int) $post_id );
				uamswp_spotlight_sync_ontology( $spotlight_id, (int) $post_id );

			}

		}

	// Every published spotlight that names the given provider
	//
	// Clinical resources are not mapped to an ACF custom database table, so
	// their values are in post meta and can be queried directly. (Providers and
	// locations are mapped, which is why their fields are only ever written
	// through update_field().)

		function uamswp_spotlight_posts_for_provider( $provider_id ) {

			if ( !$provider_id ) {

				return array();

			}

			return get_posts(
				array(
					'post_type'        => 'clinical-resource',
					'post_status'      => 'publish',
					'numberposts'      => -1,
					'fields'           => 'ids',
					'suppress_filters' => false,
					'meta_query'       => array(
						'relation' => 'AND',
						array(
							'key'   => 'clinical_resource_type',
							'value' => 'provider_spotlight',
						),
						array(
							'key'   => 'clinical_resource_spotlight_provider',
							'value' => $provider_id,
						),
					),
				)
			);

		}

	// Release the provider association when a spotlight leaves publication
	//
	// ACF's bidirectional pass only runs when a post's fields are saved, so
	// trashing or unpublishing a spotlight would otherwise leave it listed on
	// the provider's profile. Re-publishing re-asserts the link, which also
	// covers untrashing and bulk or quick edits, where no ACF values are posted
	// and the save-time reconcile above bails out.

		add_action( 'transition_post_status', 'uamswp_spotlight_status_sync', 10, 3 );

		function uamswp_spotlight_status_sync( $new_status, $old_status, $post ) {

			if (
				empty( $post->ID )
				||
				'clinical-resource' !== $post->post_type
				||
				$new_status === $old_status
				||
				wp_is_post_revision( $post->ID )
				||
				wp_is_post_autosave( $post->ID )
			) {

				return;

			}

			if ( 'provider_spotlight' !== uamswp_spotlight_stored_type( $post->ID ) ) {

				return;

			}

			$provider_id = (int) get_field( 'clinical_resource_spotlight_provider', $post->ID );

			if ( !$provider_id ) {

				return;

			}

			if ( 'publish' === $new_status ) {

				uamswp_spotlight_assert_link( (int) $post->ID, $provider_id );
				uamswp_spotlight_sync_ontology( (int) $post->ID, $provider_id );

			} elseif ( 'publish' === $old_status ) {

				// Unpublished, trashed, or otherwise taken out of publication
				uamswp_spotlight_release_link( (int) $post->ID, $provider_id );
				uamswp_spotlight_sync_ontology( (int) $post->ID, 0 );

			}

		}

	// Release the provider association when a spotlight is deleted
	//
	// transition_post_status does not fire on a permanent delete, so a site with
	// the trash disabled (EMPTY_TRASH_DAYS === 0), or any direct
	// wp_delete_post( $id, true ), would leave the spotlight dangling in the
	// provider's Related Clinical Resources. before_delete_post runs while the
	// post and its meta still exist, so the featured provider is still readable.

		add_action( 'before_delete_post', 'uamswp_spotlight_delete_sync' );

		function uamswp_spotlight_delete_sync( $post_id ) {

			if (
				!is_numeric( $post_id )
				||
				'clinical-resource' !== get_post_type( $post_id )
			) {

				return;

			}

			if ( 'provider_spotlight' !== uamswp_spotlight_stored_type( $post_id ) ) {

				return;

			}

			$provider_id = (int) get_field( 'clinical_resource_spotlight_provider', $post_id );

			if ( !$provider_id ) {

				return;

			}

			uamswp_spotlight_release_link( (int) $post_id, $provider_id );
			uamswp_spotlight_sync_ontology( (int) $post_id, 0 );

		}

	// --- Ontology mirroring: a spotlight inherits its provider's ontology ---
	//
	// Locations, Areas of Expertise, Conditions, and Treatments are copied from
	// the featured provider onto the spotlight and kept in step continuously, so
	// editors never curate them by hand (the fields and their tabs are hidden for
	// spotlights). Each clinical-resource field is bidirectional with its ontology
	// post, so the same machinery behind the provider mirror also carries the
	// spotlight into each location/expertise/condition/treatment post's own
	// Related Clinical Resources. The set is an exact copy of the provider's
	// (overwrite, not union) -- unlike clinical_resource_providers, which unions
	// the featured provider with the editor's own picks.

		// clinical-resource field key => the field name, the provider source
		// field, and the ontology post's reverse-relationship field key.
		function uamswp_spotlight_ontology_map() {

			return array(
				'field_clinical_resource_locations'  => array( 'name' => 'clinical_resource_locations',  'provider' => 'physician_locations',      'reverse' => 'field_location_clinical_resources' ),
				'field_clinical_resource_aoe'        => array( 'name' => 'clinical_resource_aoe',         'provider' => 'physician_expertise',      'reverse' => 'field_expertise_clinical_resources' ),
				'field_clinical_resource_conditions' => array( 'name' => 'clinical_resource_conditions',  'provider' => 'physician_conditions_cpt', 'reverse' => 'field_condition_clinical_resources' ),
				'field_clinical_resource_treatments' => array( 'name' => 'clinical_resource_treatments',  'provider' => 'physician_treatments_cpt', 'reverse' => 'field_treatment_procedure_clinical_resources' ),
			);

		}

	// Overwrite a spotlight's ontology relationships with its provider's set, and
	// keep each ontology post's reverse Related Clinical Resources list in step. A
	// provider of 0 clears the mirror (used when a spotlight leaves publication or
	// stops being a spotlight). Both sides are written through update_field, like
	// uamswp_spotlight_assert_link(): this runs outside a spotlight's own save, so
	// ACF's save-time bidirectional pass is not available to propagate for us.

		function uamswp_spotlight_sync_ontology( $cr_id, $provider_id ) {

			$cr_id = (int) $cr_id;

			// Authorization gate for the cross-post writes below.
			//
			// This function writes into the clinical-resource spotlight and into
			// each location/expertise/condition/treatment ontology post. Those are
			// separate post types the acting user may not be entitled to edit, so a
			// low-privilege doc_editor/doc_admin saving a provider could otherwise
			// force writes into posts they cannot edit (CWE-862).
			//
			// current_user_can() is false when there is NO current user, which would
			// wrongly block the userless WP-Cron propagations that legitimately reach
			// here and must still run: scheduled publish (check_and_publish_future_post
			// -> transition_post_status future->publish) and trash-empty
			// (wp_scheduled_delete -> before_delete_post). So only enforce the
			// capability when a user is actually acting. is_user_logged_in() is true
			// for the logged-in doc_editor/doc_admin exploit (still gated) and false
			// for cron/CLI (trusted system-side propagation preserved). Each write is
			// gated on the acting user's edit_post for its own specific target.
			$gate = is_user_logged_in();

			foreach ( uamswp_spotlight_ontology_map() as $spec ) {

				$target  = $provider_id ? uamswp_spotlight_to_ids( get_field( $spec['provider'], $provider_id ) ) : array();
				$current = uamswp_spotlight_to_ids( get_field( $spec['name'], $cr_id ) );

				$added   = array_values( array_diff( $target, $current ) );
				$removed = array_values( array_diff( $current, $target ) );

				if ( !$added && !$removed ) {

					continue;

				}

				// The spotlight's own ontology field: write only for a userless
				// context or an acting user who can edit the spotlight.
				if ( !$gate || current_user_can( 'edit_post', $cr_id ) ) {

					update_field( $spec['name'], $target, $cr_id );

				}

				// Add the spotlight to each newly associated ontology post
				foreach ( $added as $ontology_id ) {

					// Skip the reverse write into an ontology post an acting user
					// cannot edit; a userless cron/CLI propagation still runs.
					if ( $gate && !current_user_can( 'edit_post', $ontology_id ) ) {

						continue;

					}

					$list = uamswp_spotlight_to_ids( get_field( $spec['reverse'], $ontology_id ) );

					if ( !in_array( $cr_id, $list, true ) ) {

						$list[] = $cr_id;
						update_field( $spec['reverse'], $list, $ontology_id );

					}

				}

				// Remove it from each ontology post it no longer belongs to
				foreach ( $removed as $ontology_id ) {

					// Skip the reverse write into an ontology post an acting user
					// cannot edit; a userless cron/CLI propagation still runs.
					if ( $gate && !current_user_can( 'edit_post', $ontology_id ) ) {

						continue;

					}

					$list = uamswp_spotlight_to_ids( get_field( $spec['reverse'], $ontology_id ) );

					if ( in_array( $cr_id, $list, true ) ) {

						update_field( $spec['reverse'], array_values( array_diff( $list, array( $cr_id ) ) ), $ontology_id );

					}

				}

			}

		}

	// Mirror the ontology into the submitted values when a spotlight is saved
	//
	// Runs at priority 5, before ACF writes the values at 10, so the featured
	// provider's locations/expertise/conditions/treatments are saved as the
	// spotlight's own and flow through ACF's save-time bidirectional pass, FacetWP
	// indexing, and every other consumer exactly as an editor's entries would. The
	// fields are hidden for spotlights, so their value only ever comes from here.

		add_action( 'acf/save_post', 'uamswp_spotlight_reconcile_ontology', 5 );

		function uamswp_spotlight_reconcile_ontology( $post_id ) {

			if (
				empty( $_POST['acf'] )
				||
				!is_numeric( $post_id )
				||
				'clinical-resource' !== get_post_type( $post_id )
			) {

				return;

			}

			$submitted_type = uamswp_spotlight_current_type();

			$new_provider = 0;

			if ( isset( $_POST['acf']['field_clinical_resource_spotlight_provider'] ) ) {

				$value        = $_POST['acf']['field_clinical_resource_spotlight_provider'];
				$value        = is_array( $value ) ? reset( $value ) : $value;
				$new_provider = (int) $value;

			}

			if ( 'provider_spotlight' === $submitted_type && $new_provider ) {

				// Overwrite each ontology field with the provider's current set
				foreach ( uamswp_spotlight_ontology_map() as $cr_field => $spec ) {

					$ids = uamswp_spotlight_to_ids( get_field( $spec['provider'], $new_provider ) );
					$_POST['acf'][ $cr_field ] = array_map( 'strval', $ids );

				}

			} elseif ( 'provider_spotlight' === uamswp_spotlight_stored_type( $post_id ) ) {

				// The resource was a spotlight and is not one now: drop the mirror
				// rather than leave stale associations behind -- but never clobber a
				// value the editor actually submitted (the fields are visible again
				// once the type changes), mirroring uamswp_spotlight_reconcile_providers.
				foreach ( uamswp_spotlight_ontology_map() as $cr_field => $spec ) {

					if ( !array_key_exists( $cr_field, $_POST['acf'] ) ) {

						$_POST['acf'][ $cr_field ] = array();

					}

				}

			}

		}

	// Re-sync spotlights when a Condition, Treatment, or Area of Expertise is saved
	//
	// Those three are bidirectional with the provider, so associating a provider
	// from the ontology side writes the provider's own field with a direct meta
	// update that never fires the provider's save hook. (Locations are not
	// bidirectional, so no location hook is needed.) Only spotlights whose featured
	// provider was added to or removed from THIS ontology post can have a changed
	// mirror, so just those are re-synced -- not the whole population.

		// Ontology post type => the field on it that lists its providers (the
		// bidirectional partner of the matching physician_* field). Empty for any
		// other post type.
		function uamswp_spotlight_ontology_provider_field( $post_type ) {

			$fields = array(
				'expertise' => 'field_expertise_physicians',
				'condition' => 'field_condition_physicians',
				'treatment' => 'field_treatment_procedure_physicians',
			);

			return isset( $fields[ $post_type ] ) ? $fields[ $post_type ] : '';

		}

		// Remember an ontology post's provider list across a single save, so the
		// guard can also re-sync a provider that was just REMOVED (and is therefore
		// absent from the saved value).
		function uamswp_spotlight_ontology_providers_before( $post_id, $set = null ) {

			static $store = array();

			$post_id = (int) $post_id;

			if ( null !== $set ) {

				$store[ $post_id ] = $set;

			}

			return isset( $store[ $post_id ] ) ? $store[ $post_id ] : array();

		}

		// Capture the pre-save provider list at priority 5, before ACF overwrites it
		add_action( 'acf/save_post', 'uamswp_spotlight_capture_ontology_providers', 5 );

		function uamswp_spotlight_capture_ontology_providers( $post_id ) {

			if ( !is_numeric( $post_id ) ) {

				return;

			}

			$field = uamswp_spotlight_ontology_provider_field( get_post_type( $post_id ) );

			if ( !$field ) {

				return;

			}

			uamswp_spotlight_ontology_providers_before( $post_id, uamswp_spotlight_to_ids( get_field( $field, $post_id ) ) );

		}

		add_action( 'acf/save_post', 'uamswp_spotlight_ontology_guard', 20 );

		function uamswp_spotlight_ontology_guard( $post_id ) {

			if ( !is_numeric( $post_id ) ) {

				return;

			}

			$field = uamswp_spotlight_ontology_provider_field( get_post_type( $post_id ) );

			if ( !$field ) {

				return;

			}

			// Providers on this ontology post after the save, plus any removed
			// since before it: every provider whose mirror could have changed.
			$after    = uamswp_spotlight_to_ids( get_field( $field, $post_id ) );
			$before   = uamswp_spotlight_ontology_providers_before( $post_id );
			$affected = array_values( array_unique( array_merge( $after, $before ) ) );

			foreach ( $affected as $provider_id ) {

				foreach ( uamswp_spotlight_posts_for_provider( $provider_id ) as $cr_id ) {

					uamswp_spotlight_sync_ontology( $cr_id, $provider_id );

				}

			}

		}

// UAMS Health Epic DEP IDs on locations
//
// The repeater itself is optional -- a location need not map to any Epic
// department -- but a DEP ID may not appear twice on the same location. The
// duplicate rows would be indistinguishable, and every downstream consumer
// treats the rows as a set: the migration API exposes them, and the importer
// maps them into the Statamic clinical_locations collection, whose
// epic_dep_ids field carries its own DepInDeclaredList rule. ACF has no
// built-in unique-value validation, so the check lives here.
//
// The repeater's own validate_value() passes the whole submitted rows array
// down the filter chain -- rows keyed by row index, sub-values keyed by
// sub-field key -- so this needs no $_POST inspection to see sibling rows. The
// error is attached to the offending row's own input, using the same name
// ACF's repeater builds for its sub-field errors, so it renders against that
// row rather than against the repeater as a whole.
//
// Scope: uniqueness is enforced within a single location only. Uniqueness
// across every location post is deliberately NOT implemented -- see #816.
// Repeater sub-values are stored as one meta row per item
// (location_epic_dep_ids_0_location_epic_dep_id_item, _1_..., and in the
// custom-table case not in postmeta at all), so a cross-post check cannot be a
// key-equality meta_query; it would need a LIKE scan or a maintained flat
// mirror key, and it would add a save-time query whose cost grows with the
// number of location posts. There is no cross-post uniqueness check anywhere
// in this plugin today, and adding the first one is a decision of its own.

	add_filter( 'acf/validate_value/key=field_location_epic_dep_ids', 'uamswp_location_validate_epic_dep_ids', 10, 4 );

	function uamswp_location_validate_epic_dep_ids( $valid, $value, $field, $input_name ) {

		if ( $valid !== true ) {

			return $valid;

		}

		if ( !is_array($value) ) {

			return $valid;

		}

		$sub_field_key = 'field_location_epic_dep_id_item';
		$seen          = array();

		foreach ( $value as $row_index => $row ) {

			// The hidden template row ACF submits to clone for "add row" is not
			// a real row, and neither is one removed from a paginated repeater.
			if ( 'acfcloneindex' === $row_index ) {

				continue;

			}

			if ( is_string($row_index) && false !== strpos($row_index, '_deleted') ) {

				continue;

			}

			if ( !is_array($row) || !isset($row[$sub_field_key]) ) {

				continue;

			}

			$dep_id = trim( (string) $row[$sub_field_key] );

			// A row holding nothing but whitespace has to be rejected here.
			// ACF's own required check does not catch it: acf_validate_value()
			// tests empty($value) && !is_numeric($value), and empty('  ') is
			// false, so a space passes a required text field. This is the same
			// reason uamswp_spotlight_validate_excerpt() above tests
			// '' === trim((string) $value) rather than relying on required.
			if ( '' === $dep_id ) {

				acf_add_validation_error(
					$input_name . '[' . $row_index . '][' . $sub_field_key . ']',
					'A UAMS Health Epic DEP ID is required.'
				);

				continue;

			}

			// Compared case-insensitively: an identifier that differs from
			// another only by letter case is the same identifier, not a second
			// one. Surrounding whitespace is not meaningful in an identifier
			// either, and uamswp_location_trim_epic_dep_id() below strips it on
			// the way to storage, so the compared value and the stored value
			// are the same string.
			$comparison_key = strtolower($dep_id);

			if ( isset($seen[$comparison_key]) ) {

				// esc_html() because ACF returns this message over AJAX and
				// renders it into the admin DOM with .html(). ACF core escapes
				// the submitted value the same way where it echoes one back --
				// see acf_field_email::validate_value().
				acf_add_validation_error(
					$input_name . '[' . $row_index . '][' . $sub_field_key . ']',
					sprintf(
						'UAMS Health Epic DEP ID "%s" is listed more than once. Each DEP ID may appear only once on a location.',
						esc_html($dep_id)
					)
				);

				continue;

			}

			$seen[$comparison_key] = true;

		}

		return $valid;

	}

	// Store a DEP ID trimmed
	//
	// The uniqueness check above compares trimmed values, so without this the
	// compared string and the stored string could differ: " 900 " would be
	// checked as "900" but written to the database with its spaces intact, and
	// the value the migration API exposes would not match the one that was
	// validated. ACF does not trim text fields on the way to storage -- the
	// text field has no update_value() at all -- so it is done here.
	//
	// Surrounding whitespace is never meaningful in an identifier, and a value
	// that is nothing but whitespace is rejected during validation rather than
	// silently stored as an empty string.

	add_filter( 'acf/update_value/key=field_location_epic_dep_id_item', 'uamswp_location_trim_epic_dep_id', 10, 3 );

	function uamswp_location_trim_epic_dep_id( $value, $post_id, $field ) {

		if ( !is_string($value) ) {

			return $value;

		}

		return trim($value);

	}
