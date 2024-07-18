<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Provider ontology page type (MedicalWebPage; MedicalBusiness; Person)
 */

function uamswp_fad_schema_provider(
	array $repeater, // array // Required // List of IDs of the provider items
	string $page_url, // string // Required // Page URL
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // int // Optional // Nesting level within the main schema
	array $output_types = array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ), // array // Optional // List of the schema types to output
	int $MedicalWebPage_i = 1, // int // Optional // Iteration counter for provider-as-MedicalWebPage
	int $MedicalBusiness_i = 1, // int // Optional // Iteration counter for provider-as-MedicalBusiness
	int $Person_i = 1, // int // Optional // Iteration counter for provider-as-Person
	array $provider_fields = array(), // array // Optional // Pre-existing field values array so duplicate calls can be avoided
	array $MedicalWebPage_list = array(), // array // Optional // Pre-existing list array for provider-as-MedicalWebPage to which to add additional items
	array $MedicalBusiness_list = array(), // array // Optional // Pre-existing list array for provider-as-MedicalBusiness to which to add additional items
	array $Person_list = array(), // array // Optional // Pre-existing list array for provider-as-Person to which to add additional items
	array $provider_list = array() // array // Optional // Pre-existing list array for combined provider schema to which to add additional items
) {

	if ( !empty($repeater) ) {

		// Base schema function variables

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_function.php' );

		// List of valid types

			/**
			 * Define the list of high-level types that are considered valid. The list may be
			 * expanded later to include the subtypes of these high-level types.
			 */

			// List of Schema.org types for which to not get the subtypes

				$provider_valid_types = array(
					'Person'
				);

			// List of Schema.org types for which to get the subtypes

				$provider_valid_types_plus_subtypes = array(
					'MedicalBusiness',
					'MedicalWebPage'
				);

			// Check the output list against the original list of valid types

				$output_types = array_intersect(
					array_merge(
						$provider_valid_types,
						$provider_valid_types_plus_subtypes
					),
					$output_types
				);

				// If the output list is empty, bail early

					if ( !$output_types ) {

						return $provider_list;

					}

				// Sort the output list

					sort($output_types);

			// Limit the list of valid types to those in the output list

				$provider_valid_types = array_intersect(
					$output_types,
					$provider_valid_types
				);

				$provider_valid_types_plus_subtypes = array_intersect(
					$output_types,
					$provider_valid_types_plus_subtypes
				);

			// Base array for schema.org type URLs

				$provider_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$provider_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$provider_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$provider_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$provider_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $provider_valid_types as $item ) {

					$provider_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$provider_properties_map[$item]['properties'] = is_array($provider_properties_map[$item]['properties']) ? $provider_properties_map[$item]['properties'] : array($provider_properties_map[$item]['properties']);

				}

		// Add list of degrees for provider as MedicalBusiness subtypes

			// Dentist

				/**
				 * A dentist.
				 */

				if ( isset($provider_properties_map['Dentist']) ) {

					$provider_properties_map['Dentist']['degrees'] = array(
						'D.D.S.',
						'D.M.D.'
					);

				}

			// Optician

				/**
				 * A store that sells reading glasses and similar devices for improving vision.
				 */

				if ( isset($provider_properties_map['Optician']) ) {

					$provider_properties_map['Optician']['degrees'] = array();

				}

			// IndividualPhysician

				/**
				 * An individual medical practitioner. For their official address use
				 * https://schema.org/address, for affiliations to hospitals use
				 * https://schema.org/hospitalAffiliation. The https://schema.org/practicesAt
				 * property can be used to indicate https://schema.org/MedicalOrganization
				 * hospitals, clinics, pharmacies, etc. where this physician practices.
				 */

				if ( isset($provider_properties_map['IndividualPhysician']) ) {

					$provider_properties_map['IndividualPhysician']['degrees'] = array(
						'M.D.',
						'D.O.'
					);

				}

		// Loop through each provider to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity . '_' . implode( '_', $output_types ) . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
						$provider_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if (
					!empty( $provider_item )
					&&
					(
						(
							isset($provider_item['MedicalWebPage'])
							&&
							!empty($provider_item['MedicalWebPage'])
						)
						||
						(
							isset($provider_item['MedicalBusiness'])
							&&
							!empty($provider_item['MedicalBusiness'])
						)
						||
						(
							isset($provider_item['Person'])
							&&
							!empty($provider_item['Person'])
						)
					)
				) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to lists of providers

						// Add to list of MedicalWebPage items

							if (
								isset($provider_item['MedicalWebPage'])
								&&
								!empty($provider_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $provider_item['MedicalWebPage'];

							}

						// Add to list of MedicalBusiness items

							if (
								isset($provider_item['MedicalBusiness'])
								&&
								!empty($provider_item['MedicalBusiness'])
							) {

								$MedicalBusiness_list[] = $provider_item['MedicalBusiness'];

							}

						// Add to list of Person items

							if (
								isset($provider_item['Person'])
								&&
								!empty($provider_item['Person'])
							) {

								$Person_list[] = $provider_item['Person'];

							}

				} else {

					/**
					 * The transient does not exist.
					 * Define the variable again.
					 */

					// If post is not published, skip to the next iteration

						if ( get_post_status($entity) != 'publish' ) {

							continue;

						}

					// Eliminate PHP errors / reset variables

						$provider_item = array(); // Base array
						$provider_item_MedicalWebPage = in_array( 'MedicalWebPage', $provider_valid_types ) ? array() : null; // Base MedicalWebPage array
						$provider_item_MedicalBusiness = in_array( 'MedicalBusiness', $provider_valid_types ) ? array() : null; // Base MedicalBusiness array
						$provider_item_Person = in_array( 'Person', $provider_valid_types ) ? array() : null; // Base Person array
						$Dentist_degree_query = null;
						$isco08_values = null;
						$MedicalBusiness_type = null;
						$MedicalWebPage_type = null;
						$Optician_degree_query = null;
						$Person_type = null;
						$IndividualPhysician_degree_query = null;
						$provider_about = null;
						$provider_abstract = null;
						$provider_accessibilityAPI = null;
						$provider_accessibilityControl = null;
						$provider_accessibilityFeature = null;
						$provider_accessibilityHazard = null;
						$provider_accessibilitySummary = null;
						$provider_accessMode = null;
						$provider_accessModeSufficient = null;
						$provider_accountablePerson = null;
						$provider_acquireLicensePage = null;
						$provider_additionalName = null;
						$provider_additionalName_initial = null;
						$provider_additionalType = null;
						$provider_additionalType_clinical_specialization = null;
						$provider_additionalType_MedicalSpecialty = null;
						$provider_affiliation = null;
						$provider_aggregateRating = null;
						$provider_aggregateRating_api = null;
						$provider_aggregateRating_description = null;
						$provider_aggregateRating_itemReviewed = null;
						$provider_aggregateRating_query = null;
						$provider_aggregateRating_ratingCount = null;
						$provider_aggregateRating_ratingValue = null;
						$provider_aggregateRating_reviewAspect = null;
						$provider_aggregateRating_reviewCount = null;
						$provider_alternateName = null;
						$provider_alternateName_additional = null;
						$provider_alternateName_additional_repeater = null;
						$provider_alternateName_combinations = null;
						$provider_alternateName_options = null;
						$provider_alternateName_variants = null;
						$provider_areaServed = null;
						$provider_associations = null;
						$provider_audience = null;
						$provider_author = null;
						$provider_availableService = null;
						$provider_availableService_keywords = null;
						$provider_availableService_significantLink = null;
						$provider_brand = null;
						$provider_brand_keywords = null;
						$provider_cid = null;
						$provider_clinical_resource = null;
						$provider_clinical_resource_CreativeWork = null;
						$provider_clinical_resource_CreativeWork_keywords = null;
						$provider_clinical_resource_MedicalWebPage = null;
						$provider_clinical_resource_MedicalWebPage_significantLink = null;
						$provider_clinical_specialization = null;
						$provider_clinical_specialization_ancestors = null;
						$provider_clinical_specialization_keywords = null;
						$provider_clinical_specialization_knowsAbout = null;
						$provider_clinical_specialization_name = null;
						$provider_clinical_specialization_term = null;
						$provider_condition = null;
						$provider_condition_keywords = null;
						$provider_condition_list = null;
						$provider_condition_significantLink = null;
						$provider_containedInPlace = null;
						$provider_contributor = null;
						$provider_copyrightHolder = null;
						$provider_copyrightNotice = null;
						$provider_copyrightYear = null;
						$provider_countryOfOrigin = null;
						$provider_creativeWorkStatus = null;
						$provider_creator = null;
						$provider_creditText = null;
						$provider_currenciesAccepted = null;
						$provider_current_fpage = null;
						$provider_dateCreated = null;
						$provider_dateModified = null;
						$provider_datePublished = null;
						$provider_degrees_name_array = null;
						$provider_degrees_id = null;
						$provider_description = null;
						$provider_description_TextObject = null;
						$provider_duns = null;
						$provider_editor = null;
						$provider_employee = null;
						$provider_expertise = null;
						$provider_expertise_MedicalEntity = null;
						$provider_expertise_MedicalEntity_keywords = null;
						$provider_expertise_MedicalWebPage_significantLink = null;
						$provider_familyName = null;
						$provider_forename_combinations = null;
						$provider_forename_list = null;
						$provider_forename_options = null;
						$provider_forename_options_first = null;
						$provider_forename_options_middle = null;
						$provider_forename_options_nickname = null;
						$provider_fpage_query = null;
						$provider_gender = null;
						$provider_gender_keywords = null;
						$provider_generational_suffix = null;
						$provider_givenName = null;
						$provider_givenName_initial = null;
						$provider_globalLocationNumber = null;
						$provider_has_parent = null;
						$provider_hasCertification = null;
						$provider_hasCredential = null;
						$provider_hasMap = null;
						$provider_hasOccupation = null;
						$provider_hasOccupation_keywords = null;
						$provider_honorificPrefix = null;
						$provider_honorificSuffix = null;
						$provider_hospitalAffiliation = null;
						$provider_hospitalAffiliation_keywords = null;
						$provider_hospitalAffiliation_multiselect = null;
						$provider_identifier = null;
						$provider_image = null;
						$provider_image_general = null;
						$provider_image_id = null;
						$provider_image_wide_id = null;
						$provider_inLanguage = null;
						$provider_isAcceptingNewPatients = null;
						$provider_isAccessibleForFree_MedicalBusiness = null;
						$provider_isAccessibleForFree_MedicalWebPage = null;
						$provider_isFamilyFriendly = null;
						$provider_isicV4 = null;
						$provider_iso6523Code = null;
						$provider_isPartOf = null;
						$provider_jobTitle = null;
						$provider_jobTitle_keywords = null;
						$provider_keywords = null;
						$provider_knowsAbout = null;
						$provider_knowsLanguage = null;
						$provider_knowsLanguage_keywords = null;
						$provider_languages = null;
						$provider_lastReviewed = null;
						$provider_legalName = null;
						$provider_leiCode = null;
						$provider_location = null;
						$provider_location_array = null;
						$provider_location_LocalBusiness = null;
						$provider_location_LocalBusiness_keywords = null;
						$provider_location_MedicalWebPage = null;
						$provider_location_MedicalWebPage_significantLink = null;
						$provider_mainContentOfPage = null;
						$provider_mainEntity = null;
						$provider_mainEntityOfPage = null;
						$provider_maintainer = null;
						$provider_medicalAudience = null;
						$provider_medicalSpecialty = null;
						$provider_medicalSpecialty_list = null;
						$provider_memberOf = null;
						$provider_mentions = null;
						$provider_naics = null;
						$provider_name = null;
						$provider_nickname = null;
						$provider_npi = null;
						$provider_occupationalCategory = null;
						$provider_ontology_type = null;
						$provider_organization_common = null;
						$provider_specific_clinical_organization = null;
						$provider_parentOrganization = null;
						$provider_parentOrganization_keywords = null;
						$provider_paymentAccepted = null;
						$provider_photo = null;
						$provider_producer = null;
						$provider_provider = null;
						$provider_publisher = null;
						$provider_reviewedBy = null;
						$provider_sameAs = null;
						$provider_sameAs_repeater = null;
						$provider_significantLink = null;
						$provider_smokingAllowed = null;
						$provider_sourceOrganization = null;
						$provider_specialization_isco08 = null;
						$provider_specialization_isco08_code = null;
						$provider_specialization_isco08_schema = null;
						$provider_specialization_onetsoc = null;
						$provider_specialization_onetsoc_code = null;
						$provider_specialization_onetsoc_code_name = null;
						$provider_specialty = null;
						$provider_subjectOf = null;
						$provider_surname_combinations = null;
						$provider_surname_list = null;
						$provider_surname_options = null;
						$provider_surname_options_last = null;
						$provider_surname_options_suffix = null;
						$provider_taxID = null;
						$provider_taxID_employer = null;
						$provider_taxID_taxpayer = null;
						$provider_thumbnailUrl = null;
						$provider_treatment = null;
						$provider_url = null;
						$provider_vatID = null;
						$provider_video = null;
						$provider_video_caption = null;
						$provider_video_caption_query = null;
						$provider_video_description = null;
						$provider_video_duration = null;
						$provider_video_embedUrl = null;
						$provider_video_info = null;
						$provider_video_parsed = null;
						$provider_video_published = null;
						$provider_video_thumbnail = null;
						$provider_video_title = null;
						$provider_video_videoFrameSize = null;
						$provider_video_videoQuality = null;
						$provider_workLocation = null;
						$provider_worksFor = null;
						$provider_worksFor_keywords = null;
						$MedicalCondition_i = 1;
						$Service_i = 1;
						$uamswp_fad_clinical_specialization_provider = null;

					// Load variables from pre-existing field values array

						if (
							isset($provider_fields[$entity])
							&&
							!empty($provider_fields[$entity])
						) {

							foreach ( $provider_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($provider_ontology_type) ) {

							$provider_ontology_type = true;

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$provider_ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$provider_ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($provider_current_fpage) ) {

								$provider_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($provider_fpage_query) ) {

								$provider_fpage_query = $provider_current_fpage ? true : false;

							}

						}

					// Add property values

						// url

							/**
							 * URL of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							// Get values

								if ( !isset($provider_url) ) {

									$provider_url = get_permalink($entity);
									$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

								}

							// Pass the values to common schema properties template part

								$schema_common_url = $provider_url;

							// Add to item values

								// MedicalWebPage

									if (
										isset($provider_item_MedicalWebPage)
										&&
										$provider_url
									) {

										$provider_item_MedicalWebPage['url'] = $provider_url;

									}

								// MedicalBusiness

									if (
										isset($provider_item_MedicalBusiness)
										&&
										$provider_url
									) {

										$provider_item_MedicalBusiness['url'] = $provider_url;

									}

								// Person

									if (
										isset($provider_item_Person)
										&&
										$provider_url
									) {

										$provider_item_Person['url'] = $provider_url;

									}

						// Degrees/Credentials (common use)

							if (
								!isset($provider_degrees_name_array)
								||
								!isset($provider_degrees_name_string)
							) {

								if ( !isset($provider_degrees_id) ) {

									// Get value from 'Clinical Degrees and Credentials'

										$provider_degrees_id = get_field( 'physician_degree', $entity ); // int[]

										// Clean up values

											if ( $provider_degrees_id ) {

												$provider_degrees_id = array_filter($provider_degrees_id);
												$provider_degrees_id = array_unique( $provider_degrees_id, SORT_REGULAR );
												$provider_degrees_id = array_values($provider_degrees_id);

											}

								}

								// Create degrees name list array

									// Eliminate PHP errors

										$provider_degrees_name_array = array();

									if ( $provider_degrees_id ) {

										// Loop through each item in the 'Clinical Degrees and Credentials' array

											foreach ( $provider_degrees_id as $item ) {

												if ( $item ) {

													// Get the individual degree term

														$item_term = get_term( $item, 'degree' ); // WP_Term|array|WP_Error|null

													if ( is_object($item_term) ) {

														// Get the term name

															$item_name = $item_term->name; // string

														// Add the term name to the degrees name list array

															if ( $item_name ) {

																$provider_degrees_name_array[] = uamswp_attr_conversion($item_name);

															}

													} // endif ( is_object($item_term) )

												}

											} // endforeach ( $provider_degrees_id as $item )

									} // endif ( $provider_degrees_id )

								// Create degrees name list string from the degrees name list array

									$provider_degrees_name_string = $provider_degrees_name_array ? implode(
										uamswp_attr_conversion(', '), // string // glue
										$provider_degrees_name_array // array // pieces
									) : '';

							} // endif

						// @type

							// MedicalWebPage type

								if ( isset($provider_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_type = 'MedicalWebPage';

									// Add to item values

										if ( $MedicalWebPage_type ) {

											$provider_item_MedicalWebPage['@type'] = $MedicalWebPage_type;

										}

								}

							// MedicalBusiness Subtype

								if ( isset($provider_item_MedicalBusiness) ) {

									// Get values

										// Base value

											$MedicalBusiness_type = 'MedicalBusiness';

										// Check the list of degrees against the IndividualPhysician degrees

											if (
												$provider_properties_map['IndividualPhysician']['degrees']
												&&
												$provider_degrees_name_array
											) {

												$IndividualPhysician_degree_query = !empty(
													array_intersect(
														$provider_properties_map['IndividualPhysician']['degrees'],
														$provider_degrees_name_array
													)
												);

												$MedicalBusiness_type = $IndividualPhysician_degree_query ? 'IndividualPhysician' : $MedicalBusiness_type;

											}

										// Check the list of degrees against the Dentist degrees

											if (
												$provider_properties_map['Dentist']['degrees']
												&&
												$provider_degrees_name_array
											) {

												$Dentist_degree_query = !empty(
													array_intersect(
														$provider_properties_map['Dentist']['degrees'],
														$provider_degrees_name_array
													)
												);

												$MedicalBusiness_type = $Dentist_degree_query ? 'Dentist' : $MedicalBusiness_type;

											}

										// Check the list of degrees against the Optician degrees

												if (
													$provider_properties_map['Optician']['degrees']
													&&
													$provider_degrees_name_array
												) {

													$Optician_degree_query = !empty(
														array_intersect(
															$provider_properties_map['Optician']['degrees'],
															$provider_degrees_name_array
														)
													);

													$MedicalBusiness_type = $Optician_degree_query ? 'Optician' : $MedicalBusiness_type;

												}

									// Add to item values

										if ( $MedicalBusiness_type ) {

											$provider_item_MedicalBusiness['@type'] = $MedicalBusiness_type;

										}

								}

							// Person type

								if ( isset($provider_item_Person) ) {

									// Get values

										$Person_type = 'Person';

									// Add to item values

										if ( $Person_type ) {

											$provider_item_Person['@type'] = $Person_type;

										}

								}

						// @id

							// MedicalWebPage

								if ( isset($provider_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_id = $provider_url . '#' . $MedicalWebPage_type;
										// $MedicalWebPage_id .= $MedicalWebPage_i;
										// $MedicalWebPage_i++;

									// Add to item values

										if ( $MedicalWebPage_id ) {

											$provider_item_MedicalWebPage['@id'] = $MedicalWebPage_id;
											$node_identifier_list[] = $provider_item_MedicalWebPage['@id']; // Add to the list of existing node identifiers

										}

								}

							// MedicalBusiness

								if ( isset($provider_item_MedicalBusiness) ) {

									// Get values

										$MedicalBusiness_id = $provider_url . '#' . $MedicalBusiness_type;
										// $MedicalBusiness_id .= $MedicalBusiness_i;
										// $MedicalBusiness_i++;

									// Add to item values

										if ( $MedicalBusiness_id ) {

											$provider_item_MedicalBusiness['@id'] = $MedicalBusiness_id;
											$node_identifier_list[] = $provider_item_MedicalBusiness['@id']; // Add to the list of existing node identifiers

										}

								}

							// Person

								if ( isset($provider_item_Person) ) {

									// Get values

										$Person_id = $provider_url . '#' . $Person_type;
										// $Person_id .= $Person_i;
										// $Person_i++;

									// Add to item values

										if ( $Person_id ) {

											$provider_item_Person['@id'] = $Person_id;
											$node_identifier_list[] = $provider_item_Person['@id']; // Add to the list of existing node identifiers

										}

								}

						// Specific Clinical Organizations

							/*

								e.g., Arkansas Children's, Baptist Health, Central Arkansas Veterans Healthcare System

							*/

							// List of properties that reference organizations (i.e., 'Organization')

								$provider_organization_common = array(
									'affiliation',
									'brand',
									'hospitalAffiliation',
									'memberOf',
									'parentOrganization',
									'worksFor'
								);

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											$provider_organization_common
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											$provider_organization_common
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											$provider_organization_common
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Query: Whether to override the default clinical brand organization for this entity

										$provider_specific_clinical_organization_override = false;

									// Get list of Third-Party Brand Organizations

										// Base array

											$provider_specific_clinical_organization = array();

										$provider_specific_clinical_organization = uamswp_fad_schema_brand_organization_list(
											$entity, // int|WP_Term // Required // Post ID or term object
											$provider_specific_clinical_organization // array // Optional // Pre-existing list array for brand organizations to which to add additional items
										);

								// Pass the values to common schema properties template part

									$schema_common_specific_brand_organization_override = $provider_specific_clinical_organization_override; // Query for whether to override common clinical organization(s) with those specific to the current entity
									$schema_common_specific_brand_organization = $provider_specific_clinical_organization; // Clinical organization(s) specific to the current entity

							}

						// Add common properties

							// Pass variables to template part

								$schema_common_item_MedicalWebPage = $provider_item_MedicalWebPage; // MedicalWebPage item array
								$schema_common_item_mainEntity = $provider_item_Person ?? null; // item array for the main entity of the MedicalWebPage
								$schema_common_item_about = array(); // all major entities of the MedicalWebPage
									if ( isset($provider_item_MedicalBusiness) ) { $schema_common_item_about[] = $provider_item_MedicalBusiness; }
									if ( isset($provider_item_Person) ) { $schema_common_item_about[] = $provider_item_Person; }

							include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/properties.php' );

							// All types

								/*

									Loop through an associative array of properties common to all of our schema
									types, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties)
									&&
									!empty($schema_common_properties)
								) {

									foreach ( $schema_common_properties as $key => $value ) {

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

							// MedicalWebPage only

								/*

									Loop through an associative array of properties specific to the MedicalWebPage
									type, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties_MedicalWebPage)
									&&
									!empty($schema_common_properties_MedicalWebPage)
								) {

									foreach ( $schema_common_properties_MedicalWebPage as $key => $value ) {

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

									// Merge node identifier lists

										if (
											isset($node_identifier_list_MedicalWebPage)
											&&
											!empty($node_identifier_list_MedicalWebPage)
											&&
											is_array($node_identifier_list_MedicalWebPage)
										) {

											$node_identifier_list = array_merge(
												$node_identifier_list,
												$node_identifier_list_MedicalWebPage
											);

										}

										// De-duplicate the node identifier list

											if (
												$node_identifier_list
												&&
												is_array($node_identifier_list)
											) {

												$node_identifier_list = array_unique( $node_identifier_list, SORT_REGULAR );

											}

								}

							// Types other than MedicalWebPage

								/*

									Loop through an associative array of properties specific to the types other
									than the MedicalWebPage type, adding each row to this item's schema when the
									key matches a property valid for the type, replacing full values with only the
									node identifier where appropriate.

								*/

								if (
									isset($schema_common_properties_exclude_MedicalWebPage)
									&&
									!empty($schema_common_properties_exclude_MedicalWebPage)
								) {

									foreach ( $schema_common_properties_exclude_MedicalWebPage as $key => $value ) {

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

							// Main entity type

								/*

									Loop through an associative array of properties specific to the main entity
									type, adding each row to this item's schema when the key matches a property
									valid for the type, replacing full values with only the node identifier where
									appropriate.

								*/

								if (
									isset($schema_common_properties_main_entity)
									&&
									!empty($schema_common_properties_main_entity)
								) {

									foreach ( $schema_common_properties_main_entity as $key => $value ) {

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

						// Certifications (common use)

							// List of properties that reference certifications

								$provider_certification_common = array(
									'hasCertification',
									'hasCredential'
								);

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									array_intersect(
										$provider_properties_map[$MedicalWebPage_type]['properties'],
										$provider_certification_common
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									array_intersect(
										$provider_properties_map[$MedicalBusiness_type]['properties'],
										$provider_certification_common
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									array_intersect(
										$provider_properties_map[$Person_type]['properties'],
										$provider_certification_common
									)
								)
							) {

								if ( !isset($provider_certifications_id) ) {

									// Get value from 'Specialty and Subspecialty Certifications'

										$provider_certifications_id = get_field( 'physician_boards', $entity ); // int[]

										// Clean up values

											if ( $provider_certifications_id ) {

												$provider_certifications_id = array_filter($provider_certifications_id);
												$provider_certifications_id = array_unique( $provider_certifications_id, SORT_REGULAR );
												$provider_certifications_id = array_values($provider_certifications_id);

											}

								}

							}

						// names (common use and specific properties) [WIP]

							// List of properties that reference names

								$provider_name_common = array(
									'additionalName',
									'alternateName',
									'familyName',
									'givenName',
									'honorificPrefix',
									'honorificSuffix',
									'legalName',
									'name'
								);

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									array_intersect(
										$provider_properties_map[$MedicalWebPage_type]['properties'],
										$provider_name_common
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									array_intersect(
										$provider_properties_map[$MedicalBusiness_type]['properties'],
										$provider_name_common
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									array_intersect(
										$provider_properties_map[$Person_type]['properties'],
										$provider_name_common
									)
								)
							) {

								// Get values for name parts [WIP]

									// Prefix

										if ( !isset($provider_honorificPrefix) ) {

											// Define list of degrees or credentials need for "Dr." prefix (per UAMS Health clinical administration)

												$provider_honorificPrefix_degree_valid = array(
													'M.D.',
													'D.O.'
												);

											// Set the "Dr." prefix

												$provider_honorificPrefix = '';

												if (
													array_intersect(
														$provider_honorificPrefix_degree_valid, // The array with master values to check.
														$provider_degrees_name_array // Arrays to compare values against.
													)
												) {

													$provider_honorificPrefix = uamswp_attr_conversion('Dr.');

												}

										}

									// First name

										if ( !isset($provider_givenName) ) {

											$provider_givenName = get_field( 'physician_first_name', $entity ) ?? '';

										}

										// First initial

											if ( $provider_givenName ) {

												$provider_givenName_initial = substr( $provider_givenName, 0, 1 ) . '.';
												$provider_givenName_initial = ( $provider_givenName_initial != $provider_givenName ) ? $provider_givenName_initial : ''; // Check if initial matches full first name value

											}

									// Middle name

										if ( !isset($provider_additionalName) ) {

											$provider_additionalName = get_field( 'physician_middle_name', $entity ) ?? '';

										}

										// First initial

											if ( $provider_additionalName ) {

												$provider_additionalName_initial = substr( $provider_additionalName, 0, 1 ) . '.';
												$provider_additionalName_initial = ( $provider_additionalName_initial != $provider_additionalName ) ? $provider_additionalName_initial : ''; // Check if initial matches full middle name value

											}

									// Nickname [WIP]

										if ( !isset($provider_nickname) ) {

											$provider_nickname = '';

										}

									// Last name

										if ( !isset($provider_familyName) ) {

											$provider_familyName = get_field( 'physician_last_name', $entity ) ?? '';

										}

									// Generational suffix

										if ( !isset($provider_generational_suffix) ) {

											$provider_generational_suffix = get_field( 'physician_pedigree', $entity ) ?? '';

										}

								// givenName

									/**
									 * Given name. In the U.S., the first name of a Person.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Person
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'givenName', // string // Required // Name of schema property
												$provider_givenName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'givenName', // string // Required // Name of schema property
												$provider_givenName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'givenName', // string // Required // Name of schema property
												$provider_givenName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// additionalName

									/**
									 * An additional name for a Person, can be used for a middle name.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Person
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'additionalName', // string // Required // Name of schema property
												$provider_additionalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'additionalName', // string // Required // Name of schema property
												$provider_additionalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'additionalName', // string // Required // Name of schema property
												$provider_additionalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// familyName

									/**
									 * Family name. In the U.S., the last name of a Person.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Person
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'familyName', // string // Required // Name of schema property
												$provider_familyName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'familyName', // string // Required // Name of schema property
												$provider_familyName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'familyName', // string // Required // Name of schema property
												$provider_familyName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// legalName [WIP]

									/**
									 * The official name of the organization (e.g., the registered company name).
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 */

									// Get values [WIP]

										if ( !isset($provider_legalName) ) {

											$provider_legalName = '';

										}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'legalName', // string // Required // Name of schema property
												$provider_legalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'legalName', // string // Required // Name of schema property
												$provider_legalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'legalName', // string // Required // Name of schema property
												$provider_legalName, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// honorificPrefix

									/**
									 * An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Person
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'honorificPrefix', // string // Required // Name of schema property
												$provider_honorificPrefix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'honorificPrefix', // string // Required // Name of schema property
												$provider_honorificPrefix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'honorificPrefix', // string // Required // Name of schema property
												$provider_honorificPrefix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// honorificSuffix

									/**
									 * An honorific suffix following a Person's name such as M.D./PhD/MSCSW.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Person
									 */

									// Get values

										$provider_honorificSuffix = $provider_degrees_name_string;

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'honorificSuffix', // string // Required // Name of schema property
												$provider_honorificSuffix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'honorificSuffix', // string // Required // Name of schema property
												$provider_honorificSuffix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'honorificSuffix', // string // Required // Name of schema property
												$provider_honorificSuffix, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// name

									/**
									 * The name of the item.
									 *
									 * Subproperty of:
									 *
									 *      - rdfs:label
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Thing
									 */

									// Get values

										if ( !isset($provider_name) ) {

											$provider_name_parts = array();

											if ( $provider_givenName ) {

												$provider_name_parts[] = $provider_givenName;

											}

											if ( $provider_additionalName ) {

												$provider_name_parts[] = $provider_additionalName;

											}

											if ( $provider_nickname ) {

												$provider_name_parts[] = '\'' . $provider_nickname . '\'';

											}

											if ( $provider_familyName ) {

												$provider_name_parts[] = $provider_familyName;

											}

											if ( $provider_generational_suffix ) {

												$provider_name_parts[] = $provider_generational_suffix;

											}

											$provider_name = implode(
												' ',
												$provider_name_parts
											);

											if ( $provider_degrees_name_string ) {

												$provider_name .= uamswp_attr_conversion(', ') . $provider_degrees_name_string;

											}

										}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'name', // string // Required // Name of schema property
												$provider_name, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'name', // string // Required // Name of schema property
												$provider_name, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'name', // string // Required // Name of schema property
												$provider_name, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// alternateName [WIP]

									/**
									 * An alias for the item.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Thing
									 */

										// Get values

											// Base arrays

												$provider_alternateName = array();
												$provider_alternateName_options = array();
												$provider_forename_list = array();
												$provider_forename_options = array();
												$provider_surname_list = array();
												$provider_surname_options = array();

											// Create a list of all name variations

												// Name options

													// First

														// Base array

															$provider_forename_options_first = array();

														// Get values

															if ( $provider_givenName ) {

																$provider_forename_options_first[] = $provider_givenName;

															}

															if ( $provider_givenName_initial ) {

																$provider_forename_options_first[] = $provider_givenName_initial;

															}

															$provider_forename_options_first[] = ''; // Empty option

														// Add group to name options list

															if ( $provider_forename_options_first ) {

																$provider_forename_options[] = $provider_forename_options_first;

															}

													// Middle

														// Base array

															$provider_forename_options_middle = array();

														// Get values

															if ( $provider_additionalName ) {

																$provider_forename_options_middle[] = $provider_additionalName;

															}

															if ( $provider_additionalName_initial ) {

																$provider_forename_options_middle[] = $provider_additionalName_initial;

															}

															$provider_forename_options_middle[] = ''; // Empty option

														// Add group to name options list

															if ( $provider_forename_options_middle ) {

																$provider_forename_options[] = $provider_forename_options_middle;

															}

													// Nickname

														// Base array

															$provider_forename_options_nickname = array();

														// Get values

															if ( $provider_nickname ) {

																$provider_forename_options_nickname[] = $provider_nickname;

															}

															$provider_forename_options_nickname[] = ''; // Empty option

														// Add group to name options list

															if ( $provider_forename_options_nickname ) {

																$provider_forename_options[] = $provider_forename_options_nickname;

															}

													// Last

														// Base array

															$provider_surname_options_last = array();

														// Get values

															if ( $provider_familyName ) {

																$provider_surname_options_last[] = $provider_familyName;

															}

														// Add group to name options list

															if ( $provider_surname_options_last ) {

																$provider_surname_options[] = $provider_surname_options_last;

															}

													// Generational Suffix

														// Base array

															$provider_surname_options_suffix = array();

														// Get values

															if ( $provider_generational_suffix ) {

																$provider_surname_options_suffix[] = $provider_generational_suffix;

															}

															$provider_surname_options_suffix[] = ''; // Empty option

														// Add group to name options list

															if ( $provider_surname_options_suffix ) {

																$provider_surname_options[] = $provider_surname_options_suffix;

															}

												// Forename combination list

													$provider_forename_combinations = uamswp_fad_combinations($provider_forename_options);

													foreach ( $provider_forename_combinations as $item ) {

														$provider_forename_list[] = implode(
															' ',
															$item
														);

													}

												// Surname combination list

													$provider_surname_combinations = uamswp_fad_combinations($provider_surname_options);

													foreach ( $provider_surname_combinations as $item ) {

														$provider_surname_list[] = implode(
															' ',
															$item
														);

													}

												// Full name combination list

													$provider_alternateName_options = array(
														$provider_forename_list,
														$provider_surname_list
													);

													$provider_alternateName_combinations = uamswp_fad_combinations($provider_alternateName_options);

													foreach ( $provider_alternateName_combinations as $item ) {

														$provider_alternateName[] = implode(
															' ',
															$item
														);

													}

											// Add additional alternate names from the repeater field [WIP]

												// Get values [WIP]

													// Get the alternateName repeater field value [WIP]

														if ( !isset($provider_alternateName_additional_repeater) ) {

															$provider_alternateName_additional_repeater = array();

														}

													// Get the item values

														if ( $provider_alternateName_additional_repeater ) {

															$provider_alternateName_additional = uamswp_fad_schema_alternatename(
																$provider_alternateName_additional_repeater, // array // Required // alternateName repeater field
																'alternate_text' // string // Optional // alternateName item field name
															);

														}

												// Merge values into alternateName list array

													if ( $provider_alternateName_additional ) {

														$provider_alternateName = array_merge(
															$provider_alternateName,
															$provider_alternateName_additional
														);

													}

											// Create more variants using prefix and degrees

												if ( $provider_alternateName ) {

													$provider_alternateName_variants = array();

													foreach ( $provider_alternateName as $item ) {

														if ( !$item ) {

															continue;

														}

														// Add prefix

															if ( $provider_honorificPrefix ) {

																$provider_alternateName_variants[] = implode(
																	' ',
																	array(
																		$provider_honorificPrefix,
																		$item
																	)
																);

															}

														// Add degrees

															// Add full degrees name string

																if ( $provider_degrees_name_string ) {

																	$provider_alternateName_variants[] = implode(
																		', ',
																		array(
																			$item,
																			$provider_degrees_name_string
																		)
																	);

																}

															// Add each degree name individually

																if ( $provider_degrees_name_array ) {

																	foreach ( $provider_degrees_name_array as $provider_degrees_name_array_item ) {

																		$provider_alternateName_variants[] = implode(
																			', ',
																			array(
																				$item,
																				$provider_degrees_name_array_item
																			)
																		);

																	}

																}

													}

													if ( $provider_alternateName_variants ) {

														$provider_alternateName = array_merge(
															$provider_alternateName,
															$provider_alternateName_variants
														);

													}

												}

											// Clean up values

												if ( $provider_alternateName ) {

													// Remove display name from list

														if (
															array_search(
																$provider_name,
																$provider_alternateName,
																true
															) !== false
														) {
															unset($provider_alternateName[array_search( $provider_name, $provider_alternateName, true )]);
														}

													$provider_alternateName = array_filter($provider_alternateName);
													$provider_alternateName = array_unique( $provider_alternateName, SORT_REGULAR );
													$provider_alternateName = array_values($provider_alternateName);
													sort( $provider_alternateName, SORT_NATURAL | SORT_FLAG_CASE );

													// If there is only one item, flatten the multi-dimensional array by one step

														uamswp_fad_flatten_multidimensional_array($provider_alternateName);

												}

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													'alternateName', // string // Required // Name of schema property
													$provider_alternateName, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1), // int // Required // Current nesting level value
													1, // int // Optional // Max nesting level at which to add the property value // Default: -1 (no limit)
													'<=' // string // Optional // Operator used to compare nesting level with max nesting level. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. // Default: ==
												);

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'alternateName', // string // Required // Name of schema property
													$provider_alternateName, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1), // int // Required // Current nesting level value
													1, // int // Optional // Max nesting level at which to add the property value // Default: -1 (no limit)
													'<=' // string // Optional // Operator used to compare nesting level with max nesting level. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. // Default: ==
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'alternateName', // string // Required // Name of schema property
													$provider_alternateName, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1), // int // Required // Current nesting level value
													1, // int // Optional // Max nesting level at which to add the property value // Default: -1 (no limit)
													'<=' // string // Optional // Operator used to compare nesting level with max nesting level. The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively. // Default: ==
												);

							}

						// Associated ontology items (e.g., locations, areas of expertise, clinical resources, conditions, treatments)

							// Associated Locations

								// List of properties that reference locations

									$provider_location_common = array(
										'containedInPlace',
										'location',
										'mentions',
										'relatedLink',
										'significantLink',
										'workLocation'
									);

								if (
									(
										(
											isset($provider_item_MedicalWebPage)
											&&
											array_intersect(
												$provider_properties_map[$MedicalWebPage_type]['properties'],
												$provider_location_common
											)
										)
										||
										(
											isset($provider_item_MedicalBusiness)
											&&
											array_intersect(
												$provider_properties_map[$MedicalBusiness_type]['properties'],
												$provider_location_common
											)
										)
										||
										(
											isset($provider_item_Person)
											&&
											array_intersect(
												$provider_properties_map[$Person_type]['properties'],
												$provider_location_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										if ( !isset($provider_location_array) ) {

											$provider_location_array = get_field( 'physician_locations', $entity ) ?? array(); // array

										}

									// Format values

										if ( $provider_location_array ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_location') ) {

												$provider_location = uamswp_fad_schema_location(
													$provider_location_array, // List of IDs of the location items
													$provider_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$provider_location = null;

											}

											if ( isset($provider_location) ) {

												$provider_location_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$provider_location_MedicalWebPage = $provider_location['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $provider_location_MedicalWebPage ) {

															$provider_location_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$provider_location_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// LocalBusiness and subtypes

													$provider_location_LocalBusiness = $provider_location['LocalBusiness'] ?? null;
													$provider_location_mainEntity = $provider_location_LocalBusiness; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($provider_location_LocalBusiness) ) { $provider_location_about[] = $provider_location_LocalBusiness; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $provider_location_LocalBusiness ) {

														// Get URLs for significantLink property

															$provider_location_LocalBusiness_significantLink = uamswp_fad_schema_property_values(
																$provider_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$provider_location_LocalBusiness_keywords = uamswp_fad_schema_property_values(
																$provider_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated areas of expertise

								// List of properties that reference areas of expertise

									$provider_expertise_common = array(
										'knowsAbout',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($provider_item_MedicalWebPage)
											&&
											array_intersect(
												$provider_properties_map[$MedicalWebPage_type]['properties'],
												$provider_expertise_common
											)
										)
										||
										(
											isset($provider_item_MedicalBusiness)
											&&
											array_intersect(
												$provider_properties_map[$MedicalBusiness_type]['properties'],
												$provider_expertise_common
											)
										)
										||
										(
											isset($provider_item_Person)
											&&
											array_intersect(
												$provider_properties_map[$Person_type]['properties'],
												$provider_expertise_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related areas of expertise

										if ( !isset($provider_expertise_list) ) {

											$provider_expertise_list = get_field( 'physician_expertise', $entity ) ?? array();

										}

									// Format values

										if ( $provider_expertise_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_expertise') ) {

												$provider_expertise = uamswp_fad_schema_expertise(
													$provider_expertise_list, // List of IDs of the area of expertise items
													'', // string // Required // Page or fake subpage URL
													true, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
													'', // string // Required // Fake subpage slug
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$provider_expertise = null;

											}

											if ( isset($provider_expertise) ) {

												$provider_expertise_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$provider_expertise_MedicalWebPage = $provider_expertise['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $provider_expertise_MedicalWebPage ) {

															$provider_expertise_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$provider_expertise_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// MedicalEntity and subtypes

													$provider_expertise_MedicalEntity = $provider_expertise['MedicalEntity'] ?? null;
													$provider_expertise_mainEntity = $provider_expertise_MedicalEntity; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($provider_expertise_MedicalEntity) ) { $provider_expertise_about[] = $provider_expertise_MedicalEntity; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $provider_expertise_MedicalEntity ) {

														// Get URLs for significantLink property

															$provider_expertise_MedicalEntity_significantLink = uamswp_fad_schema_property_values(
																$provider_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$provider_expertise_MedicalEntity_keywords = uamswp_fad_schema_property_values(
																$provider_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated clinical resources

								// List of properties that reference clinical resources

									$provider_clinical_resource_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($provider_item_MedicalWebPage)
											&&
											array_intersect(
												$provider_properties_map[$MedicalWebPage_type]['properties'],
												$provider_clinical_resource_common
											)
										)
										||
										(
											isset($provider_item_MedicalBusiness)
											&&
											array_intersect(
												$provider_properties_map[$MedicalBusiness_type]['properties'],
												$provider_clinical_resource_common
											)
										)
										||
										(
											isset($provider_item_Person)
											&&
											array_intersect(
												$provider_properties_map[$Person_type]['properties'],
												$provider_clinical_resource_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related clinical resources

										if ( !isset($provider_clinical_resource_list) ) {

											$provider_clinical_resource_list = get_field( 'physician_clinical_resources', $entity ) ?? array();

										}

										if ( !isset($provider_clinical_resource_list_max) ) {

											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
											$provider_clinical_resource_list_max = $clinical_resource_posts_per_page_section;

										}

									// Format values

										if ( $provider_clinical_resource_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$provider_clinical_resource = uamswp_fad_schema_clinical_resource(
												$provider_clinical_resource_list, // List of IDs of the clinical resource items
												$provider_url, // Page URL
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ) // Nesting level within the main schema
											) ?? null;

											if ( isset($provider_clinical_resource) ) {

												$provider_clinical_resource_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$provider_clinical_resource_MedicalWebPage = $provider_clinical_resource['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $provider_clinical_resource_MedicalWebPage ) {

															$provider_clinical_resource_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$provider_clinical_resource_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// CreativeWork and subtypes

													$provider_clinical_resource_CreativeWork = $provider_clinical_resource['CreativeWork'] ?? null;
													$provider_clinical_resource_mainEntity = $provider_clinical_resource_CreativeWork; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($provider_clinical_resource_CreativeWork) ) { $provider_clinical_resource_about[] = $provider_clinical_resource_CreativeWork; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $provider_clinical_resource_CreativeWork ) {

														// Get URLs for significantLink property

															$provider_clinical_resource_CreativeWork_significantLink = uamswp_fad_schema_property_values(
																$provider_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$provider_clinical_resource_CreativeWork_keywords = uamswp_fad_schema_property_values(
																$provider_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated conditions

								// List of properties that reference conditions

									$provider_condition_common = array(
										'knowsAbout',
										'mentions'
									);

								if (
									(
										(
											isset($provider_item_MedicalWebPage)
											&&
											array_intersect(
												$provider_properties_map[$MedicalWebPage_type]['properties'],
												$provider_condition_common
											)
										)
										||
										(
											isset($provider_item_MedicalBusiness)
											&&
											array_intersect(
												$provider_properties_map[$MedicalBusiness_type]['properties'],
												$provider_condition_common
											)
										)
										||
										(
											isset($provider_item_Person)
											&&
											array_intersect(
												$provider_properties_map[$Person_type]['properties'],
												$provider_condition_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related conditions

										if ( !isset($provider_condition_list) ) {

											$provider_condition_list = get_field( 'physician_conditions_cpt', $entity ) ?? array();

										}

									// Format values

										if ( $provider_condition_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_condition') ) {

												$provider_condition = uamswp_fad_schema_condition(
													$provider_condition_list, // array // Required // List of IDs of the MedicalCondition items
													$provider_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$MedicalCondition_i, // int // Optional // Iteration counter for condition-as-MedicalCondition
													$Service_i // int // Optional // Iteration counter for treatment-as-Service
												) ?? null;

											} else {

												$provider_condition = null;

											}

											if (
												isset($provider_condition)
												&&
												$provider_condition
											) {

												// Get names for keywords property

													$provider_condition_keywords = uamswp_fad_schema_property_values(
														$provider_condition, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

							// Associated treatments and procedures

								// List of properties that reference treatments and procedures

									$provider_treatment_common = array(
										'availableService',
										'knowsAbout',
										'mentions'
									);

								if (
									(
										(
											isset($provider_item_MedicalWebPage)
											&&
											array_intersect(
												$provider_properties_map[$MedicalWebPage_type]['properties'],
												$provider_treatment_common
											)
										)
										||
										(
											isset($provider_item_MedicalBusiness)
											&&
											array_intersect(
												$provider_properties_map[$MedicalBusiness_type]['properties'],
												$provider_treatment_common
											)
										)
										||
										(
											isset($provider_item_Person)
											&&
											array_intersect(
												$provider_properties_map[$Person_type]['properties'],
												$provider_treatment_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related treatments

										if ( !isset($provider_treatment) ) {

											$provider_treatment = get_field( 'physician_treatments_cpt', $entity ) ?? array();

										}

									// Format values

										if ( $provider_treatment ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$provider_availableService = uamswp_fad_schema_treatment(
													$provider_treatment, // array // Required // List of IDs of the service items
													$provider_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												) ?? null;

											} else {

												$provider_availableService = null;

											}

											if (
												isset($provider_availableService)
												&&
												$provider_availableService
											) {

												// Get names for keywords property

													$provider_availableService_keywords = uamswp_fad_schema_property_values(
														$provider_availableService, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

						// Identifiers (common use)

							// Google customer ID (CID)

								// List of properties that reference Google customer ID

									$provider_google_cid_common = array(
										'identifier',
										'hasMap'
									);

								if (
									(
										isset($provider_item_MedicalWebPage)
										&&
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											$provider_google_cid_common
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											$provider_google_cid_common
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											$provider_google_cid_common
										)
									)
								) {

									// Get Google customer ID repeater field value

										if ( !isset($provider_google_cid_repeater) ) {

											$provider_google_cid_repeater = get_field( 'schema_google_cid_multiple', $entity ) ?? array();

										}

									// Add each item to Google customer ID value array

										$provider_google_cid = array();

										if ( $provider_google_cid_repeater ) {

											foreach ( $provider_google_cid_repeater as $item ) {

												if ( $item ) {

													$provider_google_cid[] = $item['schema_google_cid_text'];

												}

											}

										}

									// Clean up Google customer ID value array

										if ( $provider_google_cid ) {

											$provider_google_cid = array_filter($provider_google_cid);
											$provider_google_cid = array_unique( $provider_google_cid, SORT_REGULAR );
											$provider_google_cid = array_values($provider_google_cid);

										}

								}

							// National Provider Identifier (NPI)

								// List of properties that reference Google customer ID

									$provider_npi_common = array(
										'aggregateRating',
										'identifier'
									);

								if (
									(
										isset($provider_item_MedicalWebPage)
										&&
										array_intersect(
											$provider_properties_map[$MedicalWebPage_type]['properties'],
											$provider_npi_common
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										array_intersect(
											$provider_properties_map[$MedicalBusiness_type]['properties'],
											$provider_npi_common
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										array_intersect(
											$provider_properties_map[$Person_type]['properties'],
											$provider_npi_common
										)
									)
								) {

									// Get values

										if ( !isset($provider_npi) ) {

											$provider_npi = get_field( 'physician_npi', $entity ) ?? '';
											$provider_npi = $provider_npi ? str_pad($provider_npi, 10, '0', STR_PAD_LEFT) : ''; // Add enough leading zeroes to reach 10 digits

										}

								}

						// about [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// actionableFeedbackPolicy [excluded; out of scope]

							/**
							 * For a NewsMediaOrganization or other news-related Organization, a statement
							 * about public engagement activities (for news media, the newsroom’s), including
							 * involving the public - digitally or otherwise -- in coverage decisions,
							 * reporting and activities after publication.
							 *
							 * Subproperty of:
							 *
							 *      - publishingPrinciples
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// additionalProperty [WIP]

							/**
							 * A property-value pair representing an additional characteristic of the entity
							 * (e.g., a product feature or another characteristic for which there is no
							 * matching property in schema.org).
							 *
							 * Note: Publishers should be aware that applications designed to use specific
							 * schema.org properties (e.g., https://schema.org/width,
							 * https://schema.org/color, https://schema.org/gtin13, ...) will typically expect
							 * such data to be provided using those properties, rather than using the generic
							 * property/value mechanism.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - PropertyValue
							 *
							 * Used on these types:
							 *
							 *      - MerchantReturnPolicy
							 *      - Offer
							 *      - Place
							 *      - Product
							 *      - QualitativeValue
							 *      - QuantitativeValue
							 */

						// medicalSpecialty and specialty

							/**
							 * This section is included out of alphabetical order because it is needed for the
							 * additionalType schema property value.
							 */

							// List of properties that reference medicalSpecialty and specialty

								$provider_specialty_common = array(
									'medicalSpecialty',
									'specialty'
								);

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									array_intersect(
										$provider_properties_map[$MedicalWebPage_type]['properties'],
										$provider_specialty_common
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									array_intersect(
										$provider_properties_map[$MedicalBusiness_type]['properties'],
										$provider_specialty_common
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									array_intersect(
										$provider_properties_map[$Person_type]['properties'],
										$provider_specialty_common
									)
								)
							) {

								// Get MedicalSpecialty value(s) from associated Clinical Specialization item(s)

									if (
										!isset($provider_medicalSpecialty)
										||
										!isset($provider_medicalSpecialty_list)
									) {

										// Get Clinical Specialization value

											if ( !isset($provider_clinical_specialization) ) {

												$provider_clinical_specialization = get_field( 'physician_title', $entity );

											}

										// Get MedicalSpecialty from Clinical Specialization value

											if ( !isset($provider_medicalSpecialty_list) ) {

												if ( $provider_clinical_specialization ) {

													// Simple list of MedicalSpecialty values

														$provider_medicalSpecialty_list = array();

													// Schema property values

														$provider_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_specialization(
															$provider_clinical_specialization, // mixed // Required // Clinical Specialization value(s)
															$provider_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
														);

												}

											}

									}

								// medicalSpecialty

									/**
									 * A medical specialty of the provider.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - MedicalSpecialty
									 *
									 * Used on these types:
									 *
									 *      - Hospital
									 *      - MedicalClinic
									 *      - MedicalOrganization
									 *      - Physician
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// specialty

									/**
									 * One of the domain specialties to which this web page's content applies.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Specialty
									 *
									 * Used on these types:
									 *
									 *      - WebPage
									 */

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'specialty', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'specialty', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'specialty', // string // Required // Name of schema property
												$provider_medicalSpecialty, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

							}

						// additionalType

							/**
							 * An additional type for the item, typically used for adding more specific types
							 * from external vocabularies in microdata syntax. This is a relationship between
							 * something and a class that the thing is in. Typically the value is a
							 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
							 * in RDF. Text values can be used sparingly, for cases where useful information
							 * can be added without their being an appropriate schema to reference. In the
							 * case of text values, the class label should follow the schema.org style guide.
							 *
							 * Subproperty of:
							 *
							 *      - rdf:type
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							// additionalType (MedicalWebPage)

								if (
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'additionalType',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
								) {

									// Get values

										$provider_additionalType_MedicalWebPage = 'https://schema.org/ProfilePage';

									// Clean up additionalType property values array

										if (
											$provider_additionalType_MedicalWebPage
											&&
											is_array($provider_additionalType_MedicalWebPage)
										) {

											// If there is only one item, flatten the multi-dimensional array by one step

												uamswp_fad_flatten_multidimensional_array($provider_additionalType_MedicalWebPage);

										}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_additionalType_MedicalWebPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

							// additionalType (MedicalBusiness; Person)

								/**
								 * An additional type for the item, typically used for adding more specific types
								 * from external vocabularies in microdata syntax. This is a relationship between
								 * something and a class that the thing is in. Typically the value is a
								 * URI-identified RDF class, and in this case corresponds to the use of rdf:type
								 * in RDF. Text values can be used sparingly, for cases where useful information
								 * can be added without their being an appropriate schema to reference. In the
								 * case of text values, the class label should follow the schema.org style guide.
								 *
								 * Subproperty of:
								 *      - rdf:type
								 *
								 * Values expected to be one of these types:
								 *
								 *      - Text
								 *      - URL
								 *
								 * Used on these types:
								 *
								 *      - Thing
								 */

								if (
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'additionalType',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'additionalType',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								) {

									// Get values

										// Base property values array

											$provider_additionalType = array();

										// Get MedicalSpecialty values that match MedicalBusiness subtypes and add to property values

											// Get values

												// Get Clinical Specialization value

													if ( !isset($provider_clinical_specialization) ) {

														$provider_clinical_specialization = get_field( 'physician_title', $entity );

													}

												// Get MedicalSpecialty from Clinical Specialization value

													if ( !isset($provider_medicalSpecialty_list) ) {

														if ( $provider_clinical_specialization ) {

															// Simple list of MedicalSpecialty values

																$provider_medicalSpecialty_list = array();

															// Schema property values

																$provider_medicalSpecialty = uamswp_fad_schema_medicalSpecialty_specialization(
																	$provider_clinical_specialization, // mixed // Required // Clinical Specialization value(s)
																	$provider_medicalSpecialty_list // Optional // Array to populate with the list of MedicalSpecialty values
																);

														}

													}

												// Check medicalSpecialty list against valid schema types

													if ( $provider_medicalSpecialty_list ) {

														$provider_medicalSpecialty_list = is_array($provider_medicalSpecialty_list) ? $provider_medicalSpecialty_list : array($provider_medicalSpecialty_list);

														$provider_additionalType_MedicalSpecialty = array_intersect(
															$provider_valid_types_url,
															$provider_medicalSpecialty_list
														);

													}

											// Merge value into the additionalType property values array

												$provider_additionalType_MedicalSpecialty = $provider_additionalType_MedicalSpecialty ?? null;

												if ( $provider_additionalType_MedicalSpecialty ) {

													$provider_additionalType = uamswp_fad_schema_merge_values(
														$provider_additionalType, // mixed // Required // Initial schema item property value
														$provider_additionalType_MedicalSpecialty // mixed // Required // Incoming schema item property value
													);

												}

										// Get reference webpage(s) for the occupation from associated Clinical Specialization items

											// Get additionalType repeater field value

												if ( !isset($provider_additionalType_clinical_specialization_repeater) ) {

													$provider_additionalType_clinical_specialization_repeater = get_field( 'clinical_specialization_sameas_occupation_schema_sameas', $entity ) ?? null;

												}

											// Add each item to additionalType property values array

												if ( $provider_additionalType_clinical_specialization_repeater ) {

													$provider_additionalType = uamswp_fad_schema_additionaltype(
														$provider_additionalType_clinical_specialization_repeater, // additionalType repeater field
														'schema_sameas_url', // additionalType item field name
														$provider_additionalType // array // Optional // Pre-existing schema array for additionalType to which to add sameAs items
													);

												}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_additionalType, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// MedicalBusiness

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_additionalType, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// Person

											uamswp_fad_schema_add_to_item_values(
												$Person_type, // string // Required // The @type value for the schema item
												$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$provider_additionalType, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

						// address [WIP]

							/**
							 * Physical address of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - PostalAddress
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - GeoCoordinates
							 *      - GeoShape
							 *      - Organization
							 *      - Person
							 *      - Place
							 */

						// affiliation

							/**
							 * An organization that this person is affiliated with. For example, a
							 * school/university, a club, or a team.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Organization
							 *
							 * Used on these types:
							 *
							 *      - Person
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'affiliation',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'affiliation',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'affiliation',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_affiliation = array();

									// Merge in common 'affiliation' value

										if (
											isset($schema_common_affiliation)
											&&
											!empty($schema_common_affiliation)
										) {

											$provider_affiliation = uamswp_fad_schema_merge_values(
												$provider_affiliation, // mixed // Required // Initial schema item property value
												$schema_common_affiliation // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in specific clinical 'Organization' value

										if (
											isset($provider_specific_clinical_organization)
											&&
											!empty($provider_specific_clinical_organization)
										) {

											$provider_affiliation = uamswp_fad_schema_merge_values(
												$provider_affiliation, // mixed // Required // Initial schema item property value
												$provider_specific_clinical_organization // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in hospitalAffiliation value

										if ( !isset($provider_hospitalAffiliation) ) {

											// Get hospital affiliation multi-select field values

												if ( !isset($provider_hospitalAffiliation_multiselect) ) {

													$provider_hospitalAffiliation_multiselect = get_field( 'physician_affiliation', $entity ) ?? '';

												}

												// Add each item to hospitalAffiliation and affiliation property values array

													// Define hospitalAffiliation value

														if ( $provider_hospitalAffiliation_multiselect ) {

															$provider_hospitalAffiliation = uamswp_fad_schema_hospital_affiliation(
																$provider_hospitalAffiliation_multiselect, // array // Required // Hospital affiliation ID values
																( $nesting_level + 1 ), // Nesting level within the main schema
																array() // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
															);


														}
										}

										if (
											isset($provider_hospitalAffiliation)
											&&
											!empty($provider_hospitalAffiliation)
										) {

											$provider_affiliation = uamswp_fad_schema_merge_values(
												$provider_affiliation, // mixed // Required // Initial schema item property value
												$provider_hospitalAffiliation // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'affiliation', // string // Required // Name of schema property
											$provider_affiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'affiliation', // string // Required // Name of schema property
											$provider_affiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'affiliation', // string // Required // Name of schema property
											$provider_affiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// agentInteractionStatistic [excluded; out of scope]

							/**
							 * The number of completed interactions for this entity, in a particular role
							 * (the 'agent'), in a particular action (indicated in the statistic), and in a
							 * particular context (i.e., https://schema.org/interactionService).
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// aggregateRating [WIP]

							/**
							 * The overall rating, based on a collection of reviews or ratings, of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - AggregateRating
							 *
							 * Used on these types:
							 *
							 *      - Brand
							 *      - CreativeWork
							 *      - Event
							 *      - Offer
							 *      - Organization
							 *      - Place
							 *      - Product
							 *      - Service
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'aggregateRating',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'aggregateRating',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'aggregateRating',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values [WIP]

									// Query for whether there are valid ratings ($rating_valid)

										if ( !isset($provider_aggregateRating_query) ) {

											// Get ratings data from NRC JSON API and decode

												if (
													$provider_npi
													&&
													!isset($provider_aggregateRating_api)
												) {

													$provider_aggregateRating_api = json_decode( wp_nrc_cached_api($provider_npi) );

												}

											// Check if ratings data is valid

												if ( !empty($provider_aggregateRating_api) ) {

													$provider_aggregateRating_query = $provider_aggregateRating_api->valid ?? false;
													$provider_aggregateRating_query = $provider_aggregateRating_query ? true : false;

												} else {

													$provider_aggregateRating_query = false;

												}

										}

									// Get values from ratings data

										if (
											$provider_aggregateRating_query
											&&
											$provider_aggregateRating_api
										) {

											// ratingCount

												if ( !isset($provider_aggregateRating_ratingCount) ) {

													$provider_aggregateRating_ratingCount = $provider_aggregateRating_api->profile->reviewcount;

												}

											// ratingValue ($avg_rating)

												if ( !isset($provider_aggregateRating_ratingValue) ) {

													$provider_aggregateRating_ratingValue = $provider_aggregateRating_api->profile->averageRatingStr;

												}

											// reviewCount ($comment_count)

												if ( !isset($provider_aggregateRating_reviewCount) ) {

													$provider_aggregateRating_reviewCount = $provider_aggregateRating_api->profile->bodycount;

												}

										}

									// description [WIP]

										/**
										 * A description of the item.
										 *
										 * Values expected to be one of these types:
										 *
										 *      - Text
										 *      - TextObject
										 *
										 * Used on these types:
										 *
										 *      - Thing
										 *
										 * Sub-properties:
										 *
										 *      - disambiguatingDescription
										 *      - interpretedAsClaim
										 *      - originalMediaContextDescription
										 *      - sha256
										 */

										/*

											Get description of the rating/review concept from Patient Experience.

										*/

										if ( !isset($provider_aggregateRating_description) ) {

											$provider_aggregateRating_description = '';

										}

									// itemReviewed [WIP]

									// reviewAspect [WIP]

										/*

											Get info from Patient Experience about which facets of the provider is rated/reviewed.

										*/

										if ( !isset($provider_aggregateRating_reviewAspect) ) {

											$provider_aggregateRating_reviewAspect = '';

										}

								// Format values

									$provider_aggregateRating = array_filter(
										array(
											'description' => $provider_aggregateRating_description,
											'itemReviewed' => $provider_aggregateRating_itemReviewed,
											'ratingCount' => $provider_aggregateRating_ratingCount,
											'ratingValue' => $provider_aggregateRating_ratingValue,
											'reviewAspect' => $provider_aggregateRating_reviewAspect,
											'reviewCount' => $provider_aggregateRating_reviewCount
										)
									);

									// Add @type

										if ( $provider_aggregateRating ) {

											$provider_aggregateRating = array( '@type' => 'AggregateRating' ) + $provider_aggregateRating;

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'aggregateRating', // string // Required // Name of schema property
											$provider_aggregateRating, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'aggregateRating', // string // Required // Name of schema property
											$provider_aggregateRating, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'aggregateRating', // string // Required // Name of schema property
											$provider_aggregateRating, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// alumni [excluded; irrelevant]

							/**
							 * Alumni of an organization.
							 *
							 * Inverse-property:
							 *
							 *      - alumniOf
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// alumniOf [WIP]

							/**
							 * An organization that the person is an alumni of.
							 *
							 * Inverse property: alumni
							 *
							 * Values expected to be one of these types:
							 *
							 *      - EducationalOrganization
							 *      - Organization
							 *
							 * Used on these types:
							 *
							 *      - Person
							 */

						// amenityFeature [excluded; irrelevant]

							/**
							 * An amenity feature (e.g., a characteristic or service) of the Accommodation.
							 * This generic property does not make a statement about whether the feature is
							 * included in an offer for the main accommodation or available at extra costs.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// areaServed [WIP]

							/**
							 * The geographic area where a service or offered item is provided.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ContactPoint
							 *      - DeliveryChargeSpecification
							 *      - Demand
							 *      - Offer
							 *      - Organization
							 *
							 * Used on these types:
							 *
							 *      - ContactPoint
							 *      - DeliveryChargeSpecification
							 *      - Demand
							 *      - Offer
							 *      - Organization
							 *      - Service
							 */

						// availableService

							/**
							 * A medical service available from this provider.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalProcedure
							 *      - MedicalTest
							 *
							 * Used on these types:
							 *
							 *      - Hospital
							 *      - MedicalClinic
							 *      - Physician
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'availableService',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'availableService',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'availableService',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'availableService', // string // Required // Name of schema property
											$provider_availableService, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'availableService', // string // Required // Name of schema property
											$provider_availableService, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'availableService', // string // Required // Name of schema property
											$provider_availableService, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge availableService significantLink value into significantLink

									$provider_availableService_significantLink = $provider_availableService_significantLink ?? null;

									if ( $provider_availableService_significantLink ) {

										$provider_significantLink = uamswp_fad_schema_merge_values(
											$provider_significantLink, // mixed // Required // Initial schema item property value
											$provider_availableService_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge availableService keywords value into keywords

									$provider_availableService_keywords = $provider_availableService_keywords ?? null;

									if ( $provider_availableService_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_availableService_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// award [WIP]

							/**
							 * An award won by or for this item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 *      - Organization
							 *      - Person
							 *      - Product
							 *      - Service
							 */

						// awards [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/award.
							 */

						// branchCode [WIP]

							/**
							 * A short textual code (also called "store code") that uniquely identifies a
							 * place of business. The code is typically assigned by the parentOrganization and
							 * used in structured URLs.
							 *
							 * For example, in the URL
							 * http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code "3047" is a
							 * branchCode for a particular branch.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Place
							 *
							 * Note: Consider using the UAMS Health Epic SER ID for the provider.
							 */

						// branchOf [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/parentOrganization.
							 */

						// brand

							/**
							 * The brand(s) associated with a product or service, or the brand(s) maintained
							 * by an organization or business person.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Brand
							 *      - Organization
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 *      - Product
							 *      - Service
							 */

							// Get names for keywords property

								// Base array

									$provider_brand_keywords = array();

								// WebSite and MedicalWebPage only

									if (
										$schema_common_brand_MedicalWebPage
										&&
										(
											isset($provider_item_MedicalWebPage)
											&&
											in_array(
												'brand',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
										)
									) {

										$provider_brand_keywords = uamswp_fad_schema_property_values(
											$schema_common_brand_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$provider_brand_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Excluding WebSite and MedicalWebPage

									if (
										$schema_common_brand_exclude_MedicalWebPage
										&&
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'brand',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'brand',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
									) {

										$provider_brand_keywords = uamswp_fad_schema_property_values(
											$schema_common_brand_exclude_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$provider_brand_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Merge brand keywords value into keywords

									$provider_brand_keywords = $provider_brand_keywords ?? null;

									if ( $provider_brand_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_brand_keywords // mixed // Required // Incoming schema item property value
										);

									}

						// contactPoint [WIP]

							/**
							 * A contact point for a person or organization.
							 *
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ContactPoint
							 *
							 * Used on these types:
							 *
							 *      - HealthInsurancePlan
							 *      - Organization
							 *      - Person
							 */

						// contactPoints [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/contactPoint.
							 */

						// containedIn [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/containedInPlace.
							 */

						// containedInPlace

							/**
							 * The basic containment relation between a place and one that contains it.
							 * expected to be one of these types:
							 *
							 *      - Place
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'containedInPlace',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'containedInPlace',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'containedInPlace',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'containedInPlace', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'containedInPlace', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'containedInPlace', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge location significantLink value into significantLink

									$provider_location_MedicalWebPage_significantLink = $provider_location_MedicalWebPage_significantLink ?? null;

									if ( $provider_location_MedicalWebPage_significantLink ) {

										$provider_significantLink = uamswp_fad_schema_merge_values(
											$provider_significantLink, // mixed // Required // Initial schema item property value
											$provider_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge location keywords value into keywords

									$provider_location_LocalBusiness_keywords = $provider_location_LocalBusiness_keywords ?? null;

									if ( $provider_location_LocalBusiness_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// containsPlace [excluded; irrelevant]

							/**
							 * The basic containment relation between a place and another that it contains.
							 *
							 * Inverse-property:
							 *
							 *      - containedInPlace
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// correctionsPolicy [excluded; irrelevant]

							/**
							 * For an Organization (e.g., NewsMediaOrganization), a statement describing (in
							 * news media, the newsroom’s) disclosure and correction policy for errors.
							 *
							 * Subproperty of:
							 *
							 *      - publishingPrinciples
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// currenciesAccepted [excluded; irrelevant]

							/**
							 * The currency accepted.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included in the core schema for them. However, it should be
							 * included on the schema for the associated locations.
							 */

						// department [excluded; irrelevant]

							/**
							 * A relationship between an organization and a department of that organization,
							 * also described as an organization (allowing different urls, logos, opening
							 * hours). For example: a store with a pharmacy, or a bakery with a cafe.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// disambiguatingDescription [WIP]

							/**
							 * A sub property of description. A short description of the item used to
							 * disambiguate from other, similar items. Information from other properties (in
							 * particular, name) may be necessary for the description to be useful for
							 * disambiguation.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

						// dissolutionDate [excluded; irrelevant]

							/**
							 * The date that this organization was dissolved.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// diversityPolicy [excluded; irrelevant]

							/**
							 * Statement on diversity policy by an Organization e.g. a NewsMediaOrganization.
							 * For a NewsMediaOrganization, a statement describing the newsroom’s diversity
							 * policy on both staffing and sources, typically providing staffing data.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// diversityStaffingReport [excluded; irrelevant]

							/**
							 * For an Organization (often but not necessarily a NewsMediaOrganization), a
							 * report on staffing diversity issues. In a news context, this might be, for
							 * example, ASNE or RTDNA (US) reports, or self-reported.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// email [WIP]

							/**
							 * foo
							 *
							 * Inverse property:
							 *
							 *      - foo
							 *
							 * Subproperty of:
							 *
							 *      - foo
							 *
							 * Values expected to be one of these types:
							 *
							 *      - foo
							 *
							 * Used on these types:
							 *
							 *      - foo
							 */

						// employee [excluded; irrelevant]

							/**
							 * Someone working for this organization.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// employees [excluded; superceded]

							/**
							 * Note: This term has been superseded by https://schema.org/employee.
							 */

						// ethicsPolicy [excluded; out of scope]

							/**
							 * Statement about ethics policy (e.g., of a NewsMediaOrganization regarding
							 * journalistic and publishing practices, or of a Restaurant, a page describing
							 * food source policies). In the case of a NewsMediaOrganization, an ethicsPolicy
							 * is typically a statement describing the personal, organizational, and corporate
							 * standards of behavior expected by the organization.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// event [excluded; out of scope]

							/**
							 * Upcoming or past event associated with this place, organization, or action.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// events [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/event.
							 */

						// faxNumber [WIP]

							/**
							 * The fax number.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - ContactPoint
							 *      - Organization
							 *      - Person
							 *      - Place
							 */

						// founder [excluded; irrelevant]

							/**
							 * A person who founded this organization.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// founders [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/founder.
							 */

						// foundingDate [excluded; irrelevant]

							/**
							 * The date that this organization was founded.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// foundingLocation [excluded; irrelevant]

							/**
							 * The place where the Organization was founded.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// funder [excluded; out of scope]

							/**
							 * A person or organization that supports (sponsors) something through some kind
							 * of financial contribution.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// funding [excluded; out of scope]

							/**
							 * A Grant that directly or indirectly provide funding or sponsorship for this
							 * item. See also ownershipFundingInfo.
							 *
							 * Inverse-property: fundedItem
							 *
							 * Grant: https://schema.org/Grant
							 * ownershipFundingInfo: https://schema.org/ownershipFundingInfo
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Grant
							 *
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// gender

							/**
							 * Gender of something, typically a Person, but possibly also fictional
							 * characters, animals, etc. While https://schema.org/Male and
							 * https://schema.org/Female may be used, text strings are also acceptable for
							 * people who do not identify as a binary gender. The gender property can also be
							 * used in an extended sense to cover (e.g., the gender of sports teams). As with
							 * the gender of individuals, we do not try to enumerate all possibilities. A
							 * mixed-gender SportsTeam can be indicated with a text value of "Mixed".
							 *
							 * Values expected to be one of these types:
							 *
							 *      - GenderType
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Person
							 *      - SportsTeam
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'gender',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'gender',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'gender',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($provider_gender_value) ) {

										$provider_gender_value = get_field( 'physician_gender', $entity );

									}

									// Format values

										$provider_gender_value = $provider_gender_value ? ucfirst($provider_gender_value) : '';
										$provider_gender_value_attr = $provider_gender_value ? uamswp_attr_conversion($provider_gender_value) : '';

									// Define list of GenderType enumeration members

										$GenderType_valid = array(
											'Female',
											'Male'
										);

									// Format schema values

										if (
											in_array(
												$provider_gender_value_attr,
												$GenderType_valid
											)
										) {

											$provider_gender = array(
												'@id' => 'https://schema.org/' . $provider_gender_value_attr,
												'@type' => 'GenderType',
												'name' => $provider_gender_value_attr
											);

										} else {

											$provider_gender = strtolower($provider_gender_value_attr);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'gender', // string // Required // Name of schema property
											$provider_gender, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'gender', // string // Required // Name of schema property
											$provider_gender, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'gender', // string // Required // Name of schema property
											$provider_gender, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$provider_gender = $provider_gender ?? null;

									if ( $provider_gender ) {

										$provider_gender_keywords = uamswp_fad_schema_property_values(
											$provider_gender, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge gender keywords value into keywords

										$provider_gender_keywords = $provider_gender_keywords ?? null;

										if ( $provider_gender_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_gender_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// geo [WIP]

							/**
							 * The geo coordinates of the place.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - GeoCoordinates
							 *      - GeoShape
							 *
							 * Used on these types:
							 *
							 *      - Place
							 */

						// geoContains [excluded; irrelevant]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a containing geometry to a contained geometry.
							 * "a contains b iff no points of b lie in the exterior of a, and at least one
							 * point of the interior of b lies in the interior of a". As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoCoveredBy [excluded; irrelevant]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a geometry to another that covers it. As defined in
							 * DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoCovers [excluded; irrelevant]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a covering geometry to a covered geometry. "Every point
							 * of b is a point of (the interior or boundary of) a". As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoCrosses [WIP]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a geometry to another that crosses it: "a crosses b: they
							 * have some but not all interior points in common, and the dimension of the
							 * intersection is less than that of at least one of them". As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoDisjoint [excluded; irrelevant]

							/**
							 * Represents spatial relations in which two geometries (or the places they
							 * represent) are topologically disjoint: "they have no point in common. They
							 * form a set of disconnected geometries." (A symmetric relationship, as defined
							 * in DE-9IM.)
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoEquals [excluded; irrelevant]

							/**
							 * Represents spatial relations in which two geometries (or the places they
							 * represent) are topologically equal, as defined in DE-9IM. "Two geometries are
							 * topologically equal if their interiors intersect and no part of the interior
							 * or boundary of one geometry intersects the exterior of the other" (a symmetric
							 * relationship).
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoIntersects [excluded; irrelevant]

							/**
							 * Represents spatial relations in which two geometries (or the places they
							 * represent) have at least one point in common. As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoOverlaps [excluded; irrelevant]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a geometry to another that geospatially overlaps it
							 * (i.e., they have some but not all points in common). As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoTouches [excluded; irrelevant]

							/**
							 * Represents spatial relations in which two geometries (or the places they
							 * represent) touch: "they have at least one boundary point in common, but no
							 * interior points." (A symmetric relationship, as defined in DE-9IM.)
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// geoWithin [excluded; irrelevant]

							/**
							 * Represents a relationship between two geometries (or the places they
							 * represent), relating a geometry to one that contains it
							 * (i.e., it is inside [i.e., within] its interior). As defined in DE-9IM.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// hasCertification

							/**
							 * Certification information about a product, organization, service, place, or
							 * person.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Certification
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 *      - Place
							 *      - Product
							 *      - Service
							 *
							 * Note: Consider including the specialty and subspecialty certifications in this
							 * property.
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'hasCertification',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'hasCertification',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'hasCertification',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_hasCertification = array();

									// Specialty and Subspecialty Certifications

										// Get IDs of specialty and subspecialty certifications

											if ( !isset($provider_certifications_id) ) {

												$provider_certifications_id = get_field( 'physician_boards', $entity ); // int[]

												// Clean up values

													if ( $provider_certifications_id ) {

														$provider_certifications_id = array_filter($provider_certifications_id);
														$provider_certifications_id = array_unique( $provider_certifications_id, SORT_REGULAR );
														$provider_certifications_id = array_values($provider_certifications_id);

													}

											}

										// Format schema values

											if ( $provider_certifications_id ) {

												$provider_hasCertification = uamswp_fad_schema_hascertification(
													$provider_certifications_id, // mixed // Required // ID values for certifications
													$provider_hasCertification // array // Optional // Pre-existing schema array for hasCertification to which to add credential items
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hasCertification', // string // Required // Name of schema property
											$provider_hasCertification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hasCertification', // string // Required // Name of schema property
											$provider_hasCertification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'hasCertification', // string // Required // Name of schema property
											$provider_hasCertification, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// hasCredential

							/**
							 * A credential awarded to the Person or Organization.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - EducationalOccupationalCredential
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 *
							 * As of 16 Apr 2024, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'hasCredential',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'hasCredential',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'hasCredential',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_hasCredential = array();

									// Degrees and credentials

										// Get IDs of degrees and credentials

											if ( !isset($provider_degrees_id) ) {

												$provider_degrees_id = get_field( 'physician_degree', $entity );

												// Clean up values

													if ( $provider_degrees_id ) {

														$provider_degrees_id = array_filter($provider_degrees_id);
														$provider_degrees_id = array_unique( $provider_degrees_id, SORT_REGULAR );
														$provider_degrees_id = array_values($provider_degrees_id);

													}

											}

										// Format schema values

											if ( $provider_degrees_id ) {

												$provider_hasCredential = uamswp_fad_schema_hascredential(
													$provider_degrees_id, // mixed // Required // ID values for degrees/credentials or certifications
													'degree', // string // Required // Slug of relevant taxonomy (enum: 'degree', 'board')
													'', // mixed // Optional // Manually-defined Credential Transparency Description Language classes
													$provider_hasCredential // array // Optional // Pre-existing schema array for hasCredential to which to add credential items
												);

											}

									// Specialty and Subspecialty Certifications

										// Get IDs of specialty and subspecialty certifications

											if ( !isset($provider_certifications_id) ) {

												$provider_certifications_id = get_field( 'physician_boards', $entity ); // int[]

												// Clean up values

													if ( $provider_certifications_id ) {

														$provider_certifications_id = array_filter($provider_certifications_id);
														$provider_certifications_id = array_unique( $provider_certifications_id, SORT_REGULAR );
														$provider_certifications_id = array_values($provider_certifications_id);

													}

											}

										// Format schema values

											if ( $provider_certifications_id ) {

												$provider_hasCredential = uamswp_fad_schema_hascredential(
													$provider_certifications_id, // mixed // Required // ID values for degrees/credentials or certifications
													'board', // string // Required // Slug of relevant taxonomy (enum: 'degree', 'board')
													array( 'Certification' ), // mixed // Optional // Manually-defined Credential Transparency Description Language classes
													$provider_hasCredential // array // Optional // Pre-existing schema array for hasCredential to which to add credential items
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hasCredential', // string // Required // Name of schema property
											$provider_hasCredential, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hasCredential', // string // Required // Name of schema property
											$provider_hasCredential, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'hasCredential', // string // Required // Name of schema property
											$provider_hasCredential, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// hasDriveThroughService [excluded; irrelevant]

							/**
							 * Indicates whether some facility (e.g., FoodEstablishment, CovidTestingFacility)
							 * offers a service that can be used by driving through in a car. In the case of
							 * CovidTestingFacility such facilities could potentially help with social
							 * distancing from other potentially-infected users.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// hasOccupation

							/**
							 * The Person's occupation. For past professions, use Role for expressing dates.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Occupation
							 *
							 * Used on these types:
							 *
							 *      - Person
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'hasOccupation',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'hasOccupation',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'hasOccupation',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($provider_clinical_specialization) ) {

										$provider_clinical_specialization = get_field( 'physician_title', $entity ) ?? '';

									}

								// Format values

									if ( $provider_clinical_specialization ) {

										$provider_hasOccupation = uamswp_fad_schema_hasoccupation_id(
											$provider_clinical_specialization // mixed // Required // Clinical Specialization ID values
										);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hasOccupation', // string // Required // Name of schema property
											$provider_hasOccupation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hasOccupation', // string // Required // Name of schema property
											$provider_hasOccupation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'hasOccupation', // string // Required // Name of schema property
											$provider_hasOccupation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$provider_hasOccupation = $provider_hasOccupation ?? null;

									if ( $provider_hasOccupation ) {

										$provider_hasOccupation_keywords = uamswp_fad_schema_property_values(
											$provider_hasOccupation, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge hasOccupation keywords value into keywords

										$provider_hasOccupation_keywords = $provider_hasOccupation_keywords ?? null;

										if ( $provider_hasOccupation_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_hasOccupation_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// hasMap

							/**
							 * A URL to a map of the place.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Map
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Place
							 *
							 * Note: The examples on Schema.org indicate that a URL to the location on Google
							 * Maps is acceptable.
							 */

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									in_array(
										'hasMap',
										$provider_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									in_array(
										'hasMap',
										$provider_properties_map[$MedicalBusiness_type]['properties']
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									in_array(
										'hasMap',
										$provider_properties_map[$Person_type]['properties']
									)
								)
							) {

								// Get values

									if ( $provider_google_cid ) {

										// Check / define values

											$provider_google_cid = is_array($provider_google_cid) ? $provider_google_cid : array($provider_google_cid);
											$provider_google_cid = array_is_list($provider_google_cid) ? $provider_google_cid : array($provider_google_cid);

										foreach ( $provider_google_cid as $item ) {

											if ( $item ) {

												$provider_hasMap[] = 'https://www.google.com/maps?cid=' . $item;

											}

										}

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hasMap', // string // Required // Name of schema property
											$provider_hasMap, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hasMap', // string // Required // Name of schema property
											$provider_hasMap, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'hasMap', // string // Required // Name of schema property
											$provider_hasMap, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// hasMerchantReturnPolicy [excluded; irrelevant]

							/**
							 * Specifies a MerchantReturnPolicy that may be applicable.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// hasOfferCatalog [WIP]

							/**
							 * Indicates an OfferCatalog listing for this Organization, Person, or Service.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - OfferCatalog
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 *      - Service
							 */

						// hasPart [excluded; irrelevant]

							/**
							 * Indicates an item or CreativeWork that is part of this item, or CreativeWork
							 * (in some sense).
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// hasPOS [excluded; irrelevant]

							/**
							 * Points-of-Sales operated by the organization or person.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// hasProductReturnPolicy [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/hasMerchantReturnPolicy.
							 */

						// hospitalAffiliation

							/**
							 * A hospital with which the physician or office is affiliated.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Hospital
							 *
							 * Used on these types:
							 *
							 *      - Physician
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'hospitalAffiliation',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'hospitalAffiliation',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'hospitalAffiliation',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($provider_hospitalAffiliation) ) {

										// Get hospital affiliation multi-select field values

											if ( !isset($provider_hospitalAffiliation_multiselect) ) {

												$provider_hospitalAffiliation_multiselect = get_field( 'physician_affiliation', $entity ) ?? '';

											}

											// Add each item to hospitalAffiliation and affiliation property values array

												// Define hospitalAffiliation value

													if ( $provider_hospitalAffiliation_multiselect ) {

														$provider_hospitalAffiliation = uamswp_fad_schema_hospital_affiliation(
															$provider_hospitalAffiliation_multiselect, // array // Required // Hospital affiliation ID values
															( $nesting_level + 1 ), // Nesting level within the main schema
															array() // array // Optional // Pre-existing list array for hospitalAffiliation to which to add additional items
														);


													}
									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'hospitalAffiliation', // string // Required // Name of schema property
											$provider_hospitalAffiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'hospitalAffiliation', // string // Required // Name of schema property
											$provider_hospitalAffiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'hospitalAffiliation', // string // Required // Name of schema property
											$provider_hospitalAffiliation, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$provider_hospitalAffiliation = $provider_hospitalAffiliation ?? null;

									if ( $provider_hospitalAffiliation ) {

										$provider_hospitalAffiliation_keywords = uamswp_fad_schema_property_values(
											$provider_hospitalAffiliation, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge hospitalAffiliation keywords value into keywords

										$provider_hospitalAffiliation_keywords = $provider_hospitalAffiliation_keywords ?? null;

										if ( $provider_hospitalAffiliation_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_hospitalAffiliation_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// identifiers (multiple properties) [WIP]

							if ( $nesting_level == 0 ) {

								// 'duns' property [WIP]

									/**
									 * The Dun & Bradstreet DUNS number for identifying an organization or business
									 * person.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'duns',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'duns',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_duns) ) {

												// Base 'duns' property value array

													$provider_duns = array();

												// Get values

													$provider_duns = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'duns', // string // Required // Name of schema property
													$provider_duns, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'duns', // string // Required // Name of schema property
													$provider_duns, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// globalLocationNumber [WIP]

									/**
									 * The Global Location Number (GLN, sometimes also referred to as International
									 * Location Number or ILN) of the respective organization, person, or place. The
									 * GLN is a 13-digit number used to identify parties and physical locations.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 *      - Place
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'globalLocationNumber',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'globalLocationNumber',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_globalLocationNumber) ) {

												// Base 'globalLocationNumber' property value array

													$provider_globalLocationNumber = array();

												// Get values

													$provider_globalLocationNumber = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'globalLocationNumber', // string // Required // Name of schema property
													$provider_globalLocationNumber, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'globalLocationNumber', // string // Required // Name of schema property
													$provider_globalLocationNumber, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// 'healthPlanNetworkId' property [excluded; irrelevant]

									/**
									 * Name or unique ID of network. (Networks are often reused across different
									 * insurance plans.)
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - HealthPlanNetwork
									 *      - MedicalOrganization
									 *
									 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
									 * Implementation feedback and adoption from applications and websites can help
									 * improve their definitions.
									 *
									 * Note: This schema property is not relevant to providers or their webpages and
									 * will not be included.
									 */

								// isicV4 [WIP]

									/**
									 * The International Standard of Industrial Classification of All Economic
									 * Activities (ISIC), Revision 4 code for a particular organization, business
									 * person, or place.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 *      - Place
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'isicV4',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'isicV4',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_isicV4) ) {

												// Base 'isicV4' property value array

													$provider_isicV4 = array();

												// Get values

													$provider_isicV4 = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'isicV4', // string // Required // Name of schema property
													$provider_isicV4, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'isicV4', // string // Required // Name of schema property
													$provider_isicV4, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// leiCode [WIP]

									/**
									 * An organization identifier that uniquely identifies a legal entity as defined
									 * in ISO 17442.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'leiCode',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'leiCode',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_leiCode) ) {

												// Base 'leiCode' property value array

													$provider_leiCode = array();

												// Get values

													$provider_leiCode = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'leiCode', // string // Required // Name of schema property
													$provider_leiCode, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'leiCode', // string // Required // Name of schema property
													$provider_leiCode, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// naics [WIP]

									/**
									 * The North American Industry Classification System (NAICS) code for a particular
									 * organization or business person.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'naics',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'naics',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_naics) ) {

												// Base 'naics' property value array

													$provider_naics = array();

												// Get values

													$provider_naics = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'naics', // string // Required // Name of schema property
													$provider_naics, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'naics', // string // Required // Name of schema property
													$provider_naics, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// taxID [WIP]

									/**
									 * The Tax / Fiscal ID of the organization or person (e.g., the TIN in the US;
									 * the CIF/NIF in Spain).
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'taxID',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'taxID',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_taxID) ) {

												// Base 'taxID' property value array

													/* https://schema.org/taxID */

													$provider_taxID = array();

												// Get values

													$provider_taxID = array();

											}

										// Taxpayer Identification Number

											// Define values

												if ( !isset($provider_taxID_taxpayer) ) {

													// Base Taxpayer Identification Number value array

														/* https://www.wikidata.org/wiki/Q1444804 */

														$provider_taxID_taxpayer = array();

													// Get values

														$provider_taxID_taxpayer = array();

												}

										// Employer Identification Number

											// Define values

												if ( !isset($provider_taxID_employer) ) {

													// Base Employer Identification Number value array

														/* https://www.wikidata.org/wiki/Q2397748 */

														$provider_taxID_employer = array();

													// Get values

														$provider_taxID_employer = array();

												}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'taxID', // string // Required // Name of schema property
													$provider_taxID, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'taxID', // string // Required // Name of schema property
													$provider_taxID, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// 'usNPI' property (National Provider Identifier [NPI])

									/**
									 * A National Provider Identifier (NPI) is a unique 10-digit identification number
									 * issued to health care providers in the United States by the Centers for
									 * Medicare and Medicaid Services.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Physician
									 */

									if (
										(
											(
												isset($provider_item_MedicalWebPage)
												&&
												in_array(
													'usNPI',
													$provider_properties_map[$MedicalWebPage_type]['properties']
												)
											)
											||
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'usNPI',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'usNPI',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Get values

											if ( !isset($provider_npi) ) {

												$provider_npi = get_field( 'physician_npi', $entity ) ?? '';
												$provider_npi = $provider_npi ? str_pad($provider_npi, 10, '0', STR_PAD_LEFT) : ''; // Add enough leading zeroes to reach 10 digits

											}

										// Add to item values

											// MedicalWebPage

												uamswp_fad_schema_add_to_item_values(
													$MedicalWebPage_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													'usNPI', // string // Required // Name of schema property
													$provider_npi, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'usNPI', // string // Required // Name of schema property
													$provider_npi, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'usNPI', // string // Required // Name of schema property
													$provider_npi, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// vatID [WIP]

									/**
									 * The Value-added Tax ID of the organization or person.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *      - Person
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'vatID',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'vatID',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_vatID) ) {

												// Base 'vatID' property value array

													$provider_vatID = array();

												// Get values

													$provider_vatID = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'vatID', // string // Required // Name of schema property
													$provider_vatID, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'vatID', // string // Required // Name of schema property
													$provider_vatID, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// iso6523Code [WIP]

									/**
									 * An organization identifier as defined in ISO 6523(-1). Note that many existing
									 * organization identifiers such as leiCode, duns and vatID can be expressed as an
									 * ISO 6523 identifier by setting the ICD part of the ISO 6523 identifier
									 * accordingly.
									 *
									 * Values expected to be one of these types:
									 *
									 *      - Text
									 *
									 * Used on these types:
									 *
									 *      - Organization
									 *
									 * As of 16 Apr 2024, this term is in the "new" area of Schema.org. Implementation
									 * feedback and adoption from applications and websites can help improve their
									 * definitions.
									 */

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'iso6523Code',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'iso6523Code',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										// Define values [WIP]

											if ( !isset($provider_iso6523Code) ) {

												// Base 'iso6523Code' property value array

													$provider_iso6523Code = array();

												// Get values

													$provider_iso6523Code = array();

											}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'iso6523Code', // string // Required // Name of schema property
													$provider_iso6523Code, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'iso6523Code', // string // Required // Name of schema property
													$provider_iso6523Code, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								// 'identifier' property

									if (
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'identifier',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'identifier',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
										&&
										$nesting_level == 0
									) {

										/**
										 * The identifier property represents any kind of identifier for any kind of
										 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
										 * properties for representing many of these, either as textual strings or as
										 * URL (URI) links.
										 *
										 * See https://schema.org/docs/datamodel.html#identifierBg for more details.
										 *
										 * Values expected to be one of these types:
										 *
										 *      - PropertyValue
										 *      - Text
										 *      - URL
										 *
										 * Used on these types:
										 *
										 *      - Thing
										 */

										// Base 'identifier' property value array

											$provider_identifier = array();

										// Get values

											// Dun & Bradstreet DUNS number

												if ( $provider_duns ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														array(
															'Data Universal Numbering System (DUNS) number',
															'DUNS number',
															'D-U-N-S number'
														), // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'Data Universal Numbering System number', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q246386', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_duns, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// Global Location Number

												if ( $provider_globalLocationNumber ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														'GLN', // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'Global Location Number', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q1258830', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_globalLocationNumber, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code

												if ( $provider_isicV4 ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														array(
															'ISIC 2008',
															'ISIC Rev 4'
														), // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'International Standard Industrial Classification Rev. 4', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q112111674', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_isicV4, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// Legal Entity Identifier (LEI)

												if ( $provider_leiCode ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														array(
															'Global Legal Entity Identifier',
															'LEI',
															'LEI code',
															'LEI number'
														), // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'Legal Entity Identifier', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q6517388', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_leiCode, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// North American Industry Classification System (NAICS) code

												if ( $provider_naics ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														array(
															'North American Industry Classification System',
															'NAICS code',
															'NAICS',
															'NAICS-SCIAN code',
															'NAICS-SCIAN'
														), // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'North American Industry Classification System code', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q3509282', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_naics, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// Tax / Fiscal ID

												// Taxpayer Identification Number

													if ( $provider_taxID_taxpayer ) {

														$provider_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'TIN',
																'IRS TIN',
																'TIN IRS'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Taxpayer Identification Number', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q1444804', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$provider_taxID_taxpayer, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

												// Employer Identification Number

													if ( $provider_taxID_employer ) {

														$provider_identifier = uamswp_fad_schema_propertyvalue(
															array(
																'Federal Employer Identification Number',
																'EIN'
															), // mixed // Optional // alternateName property value
															null, // string // Optional // description property value
															null, // int // Optional // maxValue property value
															null, // mixed // Optional // measurementMethod property value
															null, // mixed // Optional // measurementTechnique property value
															null, // int // Optional // minValue property value
															'Employer Identification Number', // string // Optional // name property value
															'https://www.wikidata.org/wiki/Q2397748', // string|array // Optional // propertyID property value
															null, // string // Optional // unitCode property value
															null, // string // Optional // unitText property value
															null, // string // Optional // url property value
															$provider_taxID_employer, // string|array // Optional // value property value
															null, // mixed // Optional // valueReference property value
															$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
														);

													}

											// Value-added tax (VAT) identification number

												if ( $provider_vatID ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue(
														array(
															'value-added tax identification number',
															'VAT ID',
															'VATIN'
														), // mixed // Optional // alternateName property value
														null, // string // Optional // description property value
														null, // int // Optional // maxValue property value
														null, // mixed // Optional // measurementMethod property value
														null, // mixed // Optional // measurementTechnique property value
														null, // int // Optional // minValue property value
														'VAT identification number', // string // Optional // name property value
														'https://www.wikidata.org/wiki/Q2319042', // string|array // Optional // propertyID property value
														null, // string // Optional // unitCode property value
														null, // string // Optional // unitText property value
														null, // string // Optional // url property value
														$provider_vatID, // string|array // Optional // value property value
														null, // mixed // Optional // valueReference property value
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// National Provider Identifier (NPI)

												if ( $provider_npi ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue_npi(
														$provider_npi, // string|array // Required // National Provider Identifier
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

											// Google customer ID (CID)

												if ( $provider_google_cid ) {

													$provider_identifier = uamswp_fad_schema_propertyvalue_google_cid(
														$provider_google_cid, // string|array // Required // Google customer ID
														$provider_identifier // array // Optional // Pre-existing list array for PropertyValue to which to add additional items
													);

												}

										// Add to item values

											// MedicalBusiness

												uamswp_fad_schema_add_to_item_values(
													$MedicalBusiness_type, // string // Required // The @type value for the schema item
													$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
													'identifier', // string // Required // Name of schema property
													$provider_identifier, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// Person

												uamswp_fad_schema_add_to_item_values(
													$Person_type, // string // Required // The @type value for the schema item
													$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
													'identifier', // string // Required // Name of schema property
													$provider_identifier, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

							}

						// image (common use)

							// List of properties that reference image

								$provider_image_common = array(
									'image',
									'photo'
								);

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									array_intersect(
										$provider_properties_map[$MedicalWebPage_type]['properties'],
										$provider_image_common
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									array_intersect(
										$provider_properties_map[$MedicalBusiness_type]['properties'],
										$provider_image_common
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									array_intersect(
										$provider_properties_map[$Person_type]['properties'],
										$provider_image_common
									)
								)
							) {

								if ( !isset($provider_image_id) ) {

									// Get the various images

										$provider_image_id = get_field( '_thumbnail_id', $entity ) ?? 0;

								}

								if ( !isset($provider_image_wide_id) ) {

									// Get the various images

										$provider_image_wide_id = get_field( 'physician_image_wide', $entity ) ?? 0;

								}

								// Create ImageObject values array

									if (
										$provider_image_id
										||
										$provider_image_wide_id
									) {

										$provider_image_general = uamswp_fad_schema_imageobject_thumbnails(
											$provider_url, // URL of entity with which the image is associated
											( $nesting_level + 1 ), // Nesting level within the main schema
											'3:4', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
											'Image', // Base fragment identifier
											$provider_image_id, // ID of image to use for 1:1 aspect ratio
											$provider_image_id, // ID of image to use for 3:4 aspect ratio
											$provider_image_wide_id, // ID of image to use for 4:3 aspect ratio
											$provider_image_wide_id, // ID of image to use for 16:9 aspect ratio
											0 // ID of image to use for full image
										);

									}

								// Clean up the array

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($provider_image_general);

							}

						// image (specific property)

							/**
							 * An image of the item. This can be a URL or a fully described ImageObject.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ImageObject
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									in_array(
										'image',
										$provider_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									in_array(
										'image',
										$provider_properties_map[$MedicalBusiness_type]['properties']
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									in_array(
										'image',
										$provider_properties_map[$Person_type]['properties']
									)
								)
							) {

								// Get the values

									$provider_image = $provider_image_general ?? array();

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$provider_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$provider_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$provider_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// interactionStatistic [excluded; out of scope]

							/**
							 * The number of interactions for the CreativeWork using the WebSite or
							 * SoftwareApplication. The most specific child type of InteractionCounter should
							 * be used.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// isAcceptingNewPatients

							/**
							 * Whether the provider is accepting new patients.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Boolean
							 *
							 * Used on these types:
							 *
							 *      - MedicalOrganization
							 *
							 * As of 16 Apr 2024, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'isAcceptingNewPatients',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'isAcceptingNewPatients',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'isAcceptingNewPatients',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($provider_isAcceptingNewPatients) ) {

										$provider_isAcceptingNewPatients = get_field( 'physician_accepting_patients', $entity ) ?? false;

									}

								// Format values

									$provider_isAcceptingNewPatients = $provider_isAcceptingNewPatients ? 'True' : 'False';

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'isAcceptingNewPatients', // string // Required // Name of schema property
											$provider_isAcceptingNewPatients, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'isAcceptingNewPatients', // string // Required // Name of schema property
											$provider_isAcceptingNewPatients, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'isAcceptingNewPatients', // string // Required // Name of schema property
											$provider_isAcceptingNewPatients, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// isAccessibleForFree [WIP]

							/**
							 * A flag to signal that the item, event, or place is accessible for free.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Boolean
							 *
							 * Used on these types:
							 *
							 *      - Product
							 */

							// MedicalWebPage

								/*

									The 'isAccessibleForFree' property for MedicalWebPage has been addressed by the
									common properties.

								*/

							// MedicalBusiness [WIP]

								if (
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'isAccessibleForFree',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values [WIP]

										$provider_isAccessibleForFree_MedicalBusiness = array();

										// Add to item values

											uamswp_fad_schema_add_to_item_values(
												$MedicalBusiness_type, // string // Required // The @type value for the schema item
												$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
												'isAccessibleForFree', // string // Required // Name of schema property
												$provider_isAccessibleForFree_MedicalBusiness, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

						// jobTitle

							/**
							 * The job title of the person (for example, Financial Manager).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - DefinedTerm
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Person
							 */

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									in_array(
										'jobTitle',
										$provider_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									in_array(
										'jobTitle',
										$provider_properties_map[$MedicalBusiness_type]['properties']
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									in_array(
										'jobTitle',
										$provider_properties_map[$Person_type]['properties']
									)
								)
							) {

								// Get values

									// Get Provider Clinical Specialization and Occupational Title

										if ( !isset($uamswp_fad_clinical_specialization_provider) ) {

											$uamswp_fad_clinical_specialization_provider = uamswp_fad_clinical_specialization_provider(
												$entity // int // ID of the provider profile
											);

										}

									// Get Provider Clinical Occupational Title

										$provider_jobTitle = $uamswp_fad_clinical_specialization_provider['title_array'] ?? array();

										// Reindex the array

											$provider_jobTitle = array_values($provider_jobTitle);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'jobTitle', // string // Required // Name of schema property
											$provider_jobTitle, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'jobTitle', // string // Required // Name of schema property
											$provider_jobTitle, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'jobTitle', // string // Required // Name of schema property
											$provider_jobTitle, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									if ( $provider_jobTitle ) {

										if ( is_array($provider_jobTitle) ) {

											$provider_jobTitle_keywords = uamswp_fad_schema_property_values(
												$provider_jobTitle, // array // Required // Property values from which to extract specific values
												array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
											);

										} else {

											$provider_jobTitle_keywords = array($provider_jobTitle);

										}

									}

									// Merge jobTitle keywords value into keywords

										$provider_jobTitle_keywords = $provider_jobTitle_keywords ?? null;

										if ( $provider_jobTitle_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_jobTitle_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// knowsLanguage

							/**
							 * Of a Person, and less typically of an Organization, to indicate a known
							 * language. We do not distinguish skill levels or reading / writing / speaking /
							 * signing here. Use language codes from the IETF BCP 47 standard.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Language
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'knowsLanguage',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'knowsLanguage',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'knowsLanguage',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($provider_knowsLanguage) ) {

										if ( !isset($provider_languages) ) {

											$provider_languages = get_field( 'physician_languages', $entity );

										}

										$provider_knowsLanguage = uamswp_fad_schema_language(
											$languages // mixed // Required // Language ID values
										);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'knowsLanguage', // string // Required // Name of schema property
											$provider_knowsLanguage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'knowsLanguage', // string // Required // Name of schema property
											$provider_knowsLanguage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'knowsLanguage', // string // Required // Name of schema property
											$provider_knowsLanguage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$provider_knowsLanguage = $provider_knowsLanguage ?? null;

									if ( $provider_knowsLanguage ) {

										$provider_knowsLanguage_keywords = uamswp_fad_schema_property_values(
											$provider_knowsLanguage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge knowsLanguage keywords value into keywords

										$provider_knowsLanguage_keywords = $provider_knowsLanguage_keywords ?? null;

										if ( $provider_knowsLanguage_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_knowsLanguage_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// latitude [WIP]

							/**
							 * The latitude of a location. For example 37.42242 (WGS 84).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Number
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - GeoCoordinates
							 *      - Place
							 */

						// location (specific property)

							/**
							 * The location of, for example, where an event is happening, where an
							 * organization is located, or where an action takes place.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Place
							 *      - PostalAddress
							 *      - Text
							 *      - VirtualLocation
							 *
							 * Used on these types:
							 *
							 *      - Action
							 *      - Event
							 *      - InteractionCounter
							 *      - Organization
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'location',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'location',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'location',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'location', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'location', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'location', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge location significantLink value into significantLink

									$provider_location_MedicalWebPage_significantLink = $provider_location_MedicalWebPage_significantLink ?? null;

									if ( $provider_location_MedicalWebPage_significantLink ) {

										$provider_significantLink = uamswp_fad_schema_merge_values(
											$provider_significantLink, // mixed // Required // Initial schema item property value
											$provider_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge location keywords value into keywords

									$provider_location_LocalBusiness_keywords = $provider_location_LocalBusiness_keywords ?? null;

									if ( $provider_location_LocalBusiness_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// logo [excluded; irrelevant]

							/**
							 * An associated logo.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// longitude [WIP]

							/**
							 * The longitude of a location. For example -122.08585 (WGS 84).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Number
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - GeoCoordinates
							 *      - Place
							 */

						// mainContentOfPage

							/**
							 * Indicates if this web page element is the main subject of the page.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - WebPageElement
							 *
							 * Used on these types:
							 *
							 *      - WebPage
							*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'mainContentOfPage',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'mainContentOfPage',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'mainContentOfPage',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									$provider_mainContentOfPage = array(
										'@type' => 'WebPageElement',
										'cssSelector' => '.doctor-item'
									);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$provider_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$provider_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$provider_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// mainEntity [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// mainEntityOfPage [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// makesOffer [WIP]

							/**
							 * A pointer to products or services offered by the organization or person.
							 *
							 * Inverse-property: offeredBy
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Offer
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 */

						// map [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/hasMap.
							 */

						// maps [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/hasMap.
							 */

						// maximumAttendeeCapacity [excluded; irrelevant]

							/**
							 * The total number of individuals that may attend an event or venue.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// member [excluded; irrelevant]

							/**
							 * A member of an Organization or a ProgramMembership. Organizations can be
							 * members of organizations; ProgramMembership is typically for individuals.
							 *
							 * Inverse property:
							 *
							 *      - memberOf
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// memberOf

							/**
							 * An Organization (or ProgramMembership) to which this Person or Organization
							 * belongs.
							 *
							 * Inverse-property: member
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Organization
							 *      - ProgramMembership
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 *      - Person
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'memberOf',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'memberOf',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'memberOf',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_memberOf = array();

									// Get health care professional associations

										// Get health care professional associations input value

											if ( !isset($provider_associations) ) {

												$provider_associations = get_field( 'physician_associations', $entity ) ?? array();

											}

										// Format values

											if ( $provider_associations ) {

												$provider_association_names = array();
												$provider_memberOf = uamswp_fad_schema_associations(
													$provider_associations, // mixed // Required // Health care professional association ID values
													$provider_association_names, // array // Optional // Pre-existing array variable to populate with a list of association names
													$provider_memberOf // array // Optional // Pre-existing schema array for Language to which to add association items
												);

											}

									// Merge in common 'memberOf' value

										if (
											isset($schema_common_memberOf)
											&&
											!empty($schema_common_memberOf)
										) {

											$provider_memberOf = uamswp_fad_schema_merge_values(
												$provider_memberOf, // mixed // Required // Initial schema item property value
												$schema_common_memberOf // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in specific clinical 'Organization' value

										if (
											isset($provider_specific_clinical_organization)
											&&
											!empty($provider_specific_clinical_organization)
										) {

											$provider_memberOf = uamswp_fad_schema_merge_values(
												$provider_memberOf, // mixed // Required // Initial schema item property value
												$provider_specific_clinical_organization // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'memberOf', // string // Required // Name of schema property
											$provider_memberOf, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'memberOf', // string // Required // Name of schema property
											$provider_memberOf, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'memberOf', // string // Required // Name of schema property
											$provider_memberOf, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// members [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/member.
							 */

						// nonprofitStatus [excluded; irrelevant]

							/**
							 * nonprofitStatus indicates the legal status of a non-profit organization in its
							 * primary place of business.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// numberOfEmployees [excluded; irrelevant]

							/**
							 * The number of employees in an organization (e.g., business).
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// occupationalCategory

							/**
							 * A category describing the job, preferably using a term from a taxonomy such as
							 * BLS O*NET-SOC, ISCO-08 or similar, with the property repeated for each
							 * applicable value. Ideally the taxonomy should be identified, and both the
							 * textual label and formal code for the category should be provided.
							 *
							 * Note: for historical reasons, any textual label and formal code provided as a
							 * literal may be assumed to be from O*NET-SOC.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CategoryCode
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - EducationalOccupationalProgram
							 *      - JobPosting
							 *      - Occupation
							 *      - Physician
							 *      - WorkBasedProgram
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'occupationalCategory',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'occupationalCategory',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'occupationalCategory',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_occupationalCategory = array();

									// Get occupationalCategory from clinical specializations

										// Get Provider Clinical Specialization and Occupational Title

											if ( !isset($uamswp_fad_clinical_specialization_provider) ) {

												$uamswp_fad_clinical_specialization_provider = uamswp_fad_clinical_specialization_provider(
													$entity // int // ID of the provider profile
												);

											}

										// Loop through the clinical specializations details to get the occupationalCategory values

											$provider_clinical_specialization_occupationalCategory = array();

											if (
												isset($uamswp_fad_clinical_specialization_provider['detail_array'])
												&&
												$uamswp_fad_clinical_specialization_provider['detail_array']
											) {

												foreach ( $uamswp_fad_clinical_specialization_provider['detail_array'] as $item ) {

													// Term

														if (
															isset($item['schema']['occupationalCategory'])
															&&
															$item['schema']['occupationalCategory']
														) {

																$provider_clinical_specialization_occupationalCategory[] = $item['schema']['occupationalCategory'];

														}

													// Term's Ancestors

														if (
															isset($item['ancestors'])
															&&
															$item['ancestors']
														) {

															foreach ( $item['ancestors'] as $ancestor_item ) {

																if (
																	isset($ancestor_item['schema']['occupationalCategory'])
																	&&
																	$ancestor_item['schema']['occupationalCategory']
																) {

																	$provider_clinical_specialization_occupationalCategory[] = $ancestor_item['schema']['occupationalCategory'];

																}

															}

														}

												}

											}

										// Add to occupationalCategory list array

											$provider_clinical_specialization_occupationalCategory = $provider_clinical_specialization_occupationalCategory ?? null;

											if ( $provider_clinical_specialization_occupationalCategory ) {

												$provider_occupationalCategory = uamswp_fad_schema_merge_values(
													$provider_occupationalCategory, // mixed // Required // Initial schema item property value
													$provider_clinical_specialization_occupationalCategory // mixed // Required // Incoming schema item property value
												);

											}

										// Get values for keywords property

											$provider_clinical_specialization_occupationalCategory = $provider_clinical_specialization_occupationalCategory ?? null;

											if ( $provider_clinical_specialization_occupationalCategory ) {

												$provider_clinical_specialization_occupationalCategory_keywords = uamswp_fad_schema_property_values(
													$provider_clinical_specialization_occupationalCategory, // array // Required // Property values from which to extract specific values
													array( 'name', 'codeValue' ) // mixed // Required // List of properties from which to collect values
												);

											}

											// Merge clinical specializations keywords value into keywords

												$provider_clinical_specialization_occupationalCategory_keywords = $provider_clinical_specialization_occupationalCategory_keywords ?? null;

												if ( $provider_clinical_specialization_occupationalCategory_keywords ) {

													$provider_keywords = uamswp_fad_schema_merge_values(
														$provider_keywords, // mixed // Required // Initial schema item property value
														$provider_clinical_specialization_occupationalCategory_keywords // mixed // Required // Incoming schema item property value
													);

												}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'occupationalCategory', // string // Required // Name of schema property
											$provider_occupationalCategory, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'occupationalCategory', // string // Required // Name of schema property
											$provider_occupationalCategory, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'occupationalCategory', // string // Required // Name of schema property
											$provider_occupationalCategory, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// offers [WIP]

							/**
							 * An offer to provide this item—for example, an offer to sell a product, rent the
							 * DVD of a movie, perform a service, or give away tickets to an event.
							 *
							 * Use businessFunction to indicate the kind of transaction offered
							 * (i.e., sell, lease).
							 *
							 * This property can also be used to describe a Demand.
							 *
							 * While this property is listed as expected on a number of common types, it can
							 * be used in others. In that case, using a second type, such as Product or a
							 * subtype of Product, can clarify the nature of the offer.
							 *
							 * Inverse-property: itemOffered
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Demand
							 *      - Offer
							 *
							 * Used on these types:
							 *
							 *      - AggregateOffer
							 *      - CreativeWork
							 *      - EducationalOccupationalProgram
							 *      - Event
							 *      - MenuItem
							 *      - Product
							 *      - Service
							 *      - Trip
							 */

						// openingHours [excluded; irrelevant]

							/**
							 * The general opening hours for a business. Opening hours can be specified as a
							 * weekly time range, starting with days, then times per day. Multiple days can be
							 * listed with commas ',' separating each day. Day or time ranges are specified
							 * using a hyphen '-'.
							 *
							 *      - Days are specified using the following two-letter combinations:
							 *       Mo, Tu, We, Th, Fr, Sa, Su.
							 *      - Times are specified using 24:00 format. For example,
							 *       3pm is specified as 15:00, 10am as 10:00.
							 *      - Here is an example:
							 *       <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
							 *      - If a business is open 7 days a week, then it can be specified as
							 *       <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.

								* Note: This schema property is not relevant to providers or their webpages and
								* will not be included.
								*/

						// openingHoursSpecification [excluded; irrelevant]

							/**
							 * The opening hours of a certain place.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// ownershipFundingInfo [excluded; irrelevant]

							/**
							 * For an Organization (often but not necessarily a NewsMediaOrganization), a
							 * description of organizational ownership structure; funding and grants. In a
							 * news/media setting, this is with particular reference to editorial
							 * independence. Note that the funder is also available and can be used to make
							 * basic funder information machine-readable.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// owns [excluded; out of scope]

							/**
							 * Products owned by the organization or person.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// parentOrganization

							/**
							 * The larger organization that this organization is a subOrganization of, if any.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Organization
							 *
							 * Used on these types:
							 *
							 *      - Organization
							 */

							// Get names for keywords property

								// Base array

									$provider_parentOrganization_keywords = array();

								// WebSite and MedicalWebPage only

									if (
										$schema_common_parentOrganization_MedicalWebPage
										&&
										(
											isset($provider_item_MedicalWebPage)
											&&
											in_array(
												'parentOrganization',
												$provider_properties_map[$MedicalWebPage_type]['properties']
											)
										)
									) {

										$provider_parentOrganization_keywords = uamswp_fad_schema_property_values(
											$schema_common_parentOrganization_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$provider_parentOrganization_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Excluding WebSite and MedicalWebPage

									if (
										$schema_common_parentOrganization_exclude_MedicalWebPage
										&&
										(
											(
												isset($provider_item_MedicalBusiness)
												&&
												in_array(
													'parentOrganization',
													$provider_properties_map[$MedicalBusiness_type]['properties']
												)
											)
											||
											(
												isset($provider_item_Person)
												&&
												in_array(
													'parentOrganization',
													$provider_properties_map[$Person_type]['properties']
												)
											)
										)
									) {

										$provider_parentOrganization_keywords = uamswp_fad_schema_property_values(
											$schema_common_parentOrganization_exclude_MedicalWebPage, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ), // mixed // Required // List of properties from which to collect values
											$provider_parentOrganization_keywords // mixed // Optional // Pre-existing list to which to add additional items
										);

									}

								// Merge brand keywords value into keywords

									$provider_parentOrganization_keywords = $provider_parentOrganization_keywords ?? null;

									if ( $provider_parentOrganization_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_parentOrganization_keywords // mixed // Required // Incoming schema item property value
										);

									}

						// paymentAccepted [excluded; irrelevant]

							/**
							 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included in the core schema for them. However, it should be
							 * included on the schema for the associated locations.
							 */

						// photo

							/**
							 * A photograph of this place.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ImageObject
							 *      - Photograph
							 *
							 * Used on these types:
							 *
							 *      - Place
							 */

							if (
								(
									isset($provider_item_MedicalWebPage)
									&&
									in_array(
										'photo',
										$provider_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($provider_item_MedicalBusiness)
									&&
									in_array(
										'photo',
										$provider_properties_map[$MedicalBusiness_type]['properties']
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									in_array(
										'photo',
										$provider_properties_map[$Person_type]['properties']
									)
								)
							) {

								// Get values

									$provider_photo = $provider_image_general ?? array();

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'photo', // string // Required // Name of schema property
											$provider_photo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'photo', // string // Required // Name of schema property
											$provider_photo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'photo', // string // Required // Name of schema property
											$provider_photo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// photos [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/photo.
							 */

						// potentialAction [WIP]

							/**
							 * Indicates a potential Action, which describes an idealized action in which this
							 * thing would play an 'object' role.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Action
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							/*

								Create one or more Action arrays, likely 'CreateAction' type

										* Make an appointment, new or existing patient, by phone
										* Make an appointment, new patient, by phone
										* Make an appointment, existing patient, by phone
										* Make an appointment, new or existing patient, online
										* Make an appointment, new patient, online
										* Make an appointment, existing patient, online
										* Refer a patient, by phone
										* Refer a patient, by fax
										* Refer a patient, through Epic thing

								Property descriptions:

										* 'actionStatus'
											* Indicates the current disposition of the Action
										* 'agent'
											* The direct performer or driver of the action — animate or inanimate (e.g., John
											wrote a book)
										* 'endTime'
											* The endTime of something. For a reserved event or service
											(e.g., FoodEstablishmentReservation), the time that it is expected to end. For
											actions that span a period of time, when the action was performed (e.g., John
											wrote a book from January to December). For media, including audio and video,
											it's the time offset of the end of a clip within a larger file. Note that Event
											uses startDate/endDate instead of startTime/endTime, even when describing dates
											with times. This situation may be clarified in future revisions.
										* 'error'
											* For failed actions, more information on the cause of the failure.
										* 'instrument'
											* The object that helped the agent perform the action (e.g., John wrote a book
										with a pen).
										* 'location'
											* The location of, for example, where an event is happening, where an
											organization is located, or where an action takes place.
										* 'object'
											* The object upon which the action is carried out, whose state is kept intact or
											changed. Also known as the semantic roles patient, affected or undergoer —
											which change their state — or theme — which doesn't (e.g., John read a book).
										* 'participant'
											* Other co-agents that participated in the action indirectly (e.g., John wrote a
										book with Steve).
										* 'provider'
											* The service provider, service operator, or service performer; the goods
											producer. Another party (a seller) may offer those services or goods on behalf
											of the provider. A provider may also serve as the seller. Supersedes carrier.
										* 'result'
											* The result produced in the action (e.g., John wrote a book).
										* 'startTime'
											* The startTime of something. For a reserved event or service
											(e.g., FoodEstablishmentReservation), the time that it is expected to start.
											For actions that span a period of time, when the action was performed
											(e.g., John wrote a book from January to December). For media, including audio
											and video, it's the time offset of the start of a clip within a larger file.
											Note that Event uses startDate/endDate instead of startTime/endTime, even when
											describing dates with times. This situation may be clarified in future
											revisions.
										* 'target'
											* Indicates a target EntryPoint, or url, for an Action.

							*/

						// practicesAt

							/**
							 * A MedicalOrganization where the IndividualPhysician practices.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalOrganization
							 *
							 * Used on these types:
							 *
							 *      - IndividualPhysician
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'practicesAt',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'practicesAt',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'practicesAt',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'practicesAt', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'practicesAt', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'practicesAt', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge location significantLink value into significantLink

									$provider_location_MedicalWebPage_significantLink = $provider_location_MedicalWebPage_significantLink ?? null;

									if ( $provider_location_MedicalWebPage_significantLink ) {

										$provider_significantLink = uamswp_fad_schema_merge_values(
											$provider_significantLink, // mixed // Required // Initial schema item property value
											$provider_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge location keywords value into keywords

									$provider_location_LocalBusiness_keywords = $provider_location_LocalBusiness_keywords ?? null;

									if ( $provider_location_LocalBusiness_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// priceRange [excluded; irrelevant]

							/**
							 * The price range of the business, for example $$$.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// primaryImageOfPage [WIP]

							/**
							 * Indicates the main image on the page.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ImageObject
							 *
							 * Used on these types:
							 *
							 *      - WebPage
							 */

						// publicAccess [excluded; irrelevant]

							/**
							 * A flag to signal that the Place is open to public visitors. If this property is
							 * omitted there is no assumed default boolean value.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// publishingPrinciples [excluded; irrelevant]

							/**
							 * The publishingPrinciples property indicates (typically via URL) a document
							 * describing the editorial principles of an Organization
							 * (or individual [e.g., a Person writing a blog]) that relate to their activities
							 * as a publisher (e.g., ethics or diversity policies). When applied to a
							 * CreativeWork (e.g., NewsArticle) the principles are those of the party
							 * primarily responsible for the creation of the CreativeWork.
							 *
							 * While such policies are most typically expressed in natural language, sometimes
							 * related information (e.g., indicating a funder) can be expressed using
							 * schema.org terminology.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// review [WIP]

							/**
							 * A review of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Review
							 *
							 * Used on these types:
							 *
							 *      - Brand
							 *      - CreativeWork
							 *      - Event
							 *      - Offer
							 *      - Organization
							 *      - Place
							 *      - Product
							 *      - Service
							 */

						// reviews [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/review.
							 */

						// sameAs

							/**
							 * URL of a reference Web page that unambiguously indicates the item's identity
							 * (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
							 * website).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

							if (
								(
									isset($provider_item_MedicalBusiness)
									&&
									in_array(
										'sameAs',
										$provider_properties_map[$MedicalBusiness_type]['properties']
									)
								)
								||
								(
									isset($provider_item_Person)
									&&
									in_array(
										'sameAs',
										$provider_properties_map[$Person_type]['properties']
									)
								)
							) {

								// Get sameAs repeater field value

									if ( !isset($provider_sameAs_repeater) ) {

										$provider_sameAs_repeater = get_field( 'schema_sameas', $entity );

									}

									// Add each item to sameAs property values array

										if ( $provider_sameAs_repeater ) {

											$provider_sameAs = uamswp_fad_schema_sameas(
												$provider_sameAs_repeater, // sameAs repeater field
												'schema_sameas_url' // sameAs item field name
											);

										}

								// Add to item values

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$provider_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$provider_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// seeks [excluded; irrelevant]

							/**
							 * A pointer to products or services sought by the organization or person
							 * (demand).
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// serviceArea [excluded; superseded]

							/**
							 * Note: This term has been superseded by https://schema.org/areaServed.
							 */

						// slogan [excluded; irrelevant]

							/**
							 * A slogan or motto associated with the item.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// smokingAllowed [excluded; irrelevant]

							/**
							 * Indicates whether it is allowed to smoke in the place
							 * (e.g., in the restaurant, hotel or hotel room).
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// speakable [WIP]

							/**
							 * Indicates sections of a Web page that are particularly 'speakable' in the sense
							 * of being highlighted as being especially appropriate for text-to-speech
							 * conversion. Other sections of a page may also be usefully spoken in particular
							 * circumstances; the 'speakable' property serves to indicate the parts most
							 * likely to be generally useful for speech.
							 *
							 * The speakable property can be repeated an arbitrary number of times, with three
							 * kinds of possible 'content-locator' values:
							 *
							 *     1.) id-value URL references - uses id-value of an element in the page being
							 * annotated. The simplest use of speakable has (potentially relative) URL values,
							 * referencing identified sections of the document concerned.
							 *
							 *     2.) CSS Selectors - addresses content in the annotated page (e.g., via
							 * class attribute). Use the cssSelector property.
							 *
							 *     3.) XPaths - addresses content via XPaths (assuming an XML view of the
							 * content). Use the xpath property.
							 *
							 * For more sophisticated markup of speakable sections beyond simple ID
							 * references, either CSS selectors or XPath expressions to pick out document
							 * section(s) as speakable. For this we define a supporting type,
							 * SpeakableSpecification which is defined to be a possible value of the speakable
							 * property.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - SpeakableSpecification
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - Article
							 *      - WebPage
							 */

						// specialOpeningHoursSpecification [excluded; irrelevant]

							/**
							 * The special opening hours of a certain place.
							 *
							 * Use this to explicitly override general opening hours brought in scope by
							 * openingHoursSpecification or openingHours.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// sponsor [excluded; out of scope]

							/**
							 * A person or organization that supports a thing through a pledge, promise, or
							 * financial contribution. E.g. a sponsor of a Medical Study or a corporate
							 * sponsor of an event.
							 *
							 * Note: This schema property is outside the scope of what should be included in
							 * Find-a-Doc.
							 */

						// subOrganization [excluded; irrelevant]

							/**
							 * A relationship between two organizations where the first includes the second
							 * (e.g., as a subsidiary). See also: the more specific 'department' property.
							 *
							 * Inverse property:
							 *
							 *      - parentOrganization
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// subjectOf [WIP]

							/**
							 * A CreativeWork or Event about this Thing.
							 *
							 * Inverse property:
							 *
							 *      - about
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - Event
							 *
							 * Used on these types:
							 *
							 *      - Thing
							 */

						// telephone [WIP]

							/**
							 * The telephone number.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - ContactPoint
							 *      - Organization
							 *      - Person
							 *      - Place
							 */

						// timeRequired [WIP]

							/**
							 * Approximate or typical time it usually takes to work with or through the
							 * content of this work for the typical or target audience.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Duration (use ISO 8601 duration format).
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 */

						// tourBookingPage [excluded; irrelevant]

							/**
							 * A page providing information on how to book a tour of some Place, such as an
							 * Accommodation or ApartmentComplex in a real estate setting, as well as other
							 * kinds of tours as appropriate.
							 *
							 * Note: As of 16 Apr 2024, this term is in the "new" area of Schema.org.
							 * Implementation feedback and adoption from applications and websites can help
							 * improve their definitions.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// unnamedSourcesPolicy [excluded; irrelevant]

							/**
							 * For an Organization (typically a NewsMediaOrganization), a statement about
							 * policy on use of unnamed sources and the decision process required.
							 *
							 * Note: This schema property is not relevant to providers or their webpages and
							 * will not be included.
							 */

						// video

							/**
							 * An embedded video object.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Clip
							 *      - VideoObject
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 */

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'video',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'video',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'video',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Video URL

										if ( !isset($provider_video_url) ) {

											$provider_video_url = get_field( 'physician_youtube_link', $entity ) ?? '';

										}

									// Video info

										if ( $provider_video_url ) {

											// Parse the URL and return its components

												$provider_video_parsed = parse_url($provider_video_url);

												// Convert https://youtu.be/* to https://www.youtube.com/watch?v=*

													if (
														isset($provider_video_parsed['host'])
														&&
														$provider_video_parsed['host'] == 'youtu.be'
														&&
														isset($provider_video_parsed['path'])
													) {

														$provider_video_url = 'https://www.youtube.com/watch?v=' . str_replace( '/', '', $provider_video_parsed['path']);
														$provider_video_parsed = parse_url($provider_video_url);

													}

												// Parse the query string into variables

													if ( isset($provider_video_parsed['query']) ) {

														parse_str($provider_video_parsed['query'], $provider_video_parsed['query']);

													}

											if (
												str_contains( $provider_video_parsed['host'], 'youtube' )
												||
												str_contains( $provider_video_parsed['host'], 'youtu.be' )
											) {

												// If YouTube

													// Embed URL

														$provider_video_embedUrl = null;

														if (
															isset($provider_video_parsed['query']['v'])
														) {

															/* Single video  */

															$provider_video_embedUrl = 'https://www.youtube.com/embed/' . $provider_video_parsed['query']['v'];

														} elseif (
															isset($provider_video_parsed['path'])
															&&
															$provider_video_parsed['path'] == '/playlist'
															&&
															isset($provider_video_parsed['query']['list'])
														) {

															/* Playlist  */

															$provider_video_embedUrl = 'https://www.youtube.com/embed/videoseries?list=' . $provider_video_parsed['query']['list'];

														}

													// Get info from video

														$provider_video_info = isset($provider_video_parsed['query']['v']) ? ( uamswp_fad_youtube_info( $provider_video_url ) ?? array() ) : array();

														if ( $provider_video_info ) {

															// Title (snippet.title)

																$provider_video_title = $provider_video_info['title'] ?? '';

															// Thumbnail URL

																// MaxRes Thumbnail URL, 1280x720 (snippet.thumbnails.maxres.url)

																	$provider_video_thumbnail = $provider_video_info['HQthumbUrl'] ?? array();

																// Fallback value: High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																	if ( !$provider_video_thumbnail ) {

																		$provider_video_thumbnail = $provider_video_info['thumbUrl'] ?? array(); // High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																	}

															// Published date and time (snippet.publishedAt)

																$provider_video_published = $provider_video_info['dateField'] ?? '';

															// Duration (contentDetails.duration)

																$provider_video_duration = $provider_video_info['duration'] ?? '';

															// Description (snippet.description)

																$provider_video_description = $provider_video_info['description'] ?? '';

															// Whether captions are available for the video (contentDetails.caption)

																$provider_video_caption_query = $provider_video_info['captions_data'] ?? '';
																$provider_video_caption_query = ( $provider_video_caption_query == 'true' ) ? true : false;

																// Captions text

																	if ( $provider_video_caption_query ) {

																		/* No info on this returned from function */

																		$provider_video_caption = '';

																	}

															// Video quality: high definition (hd) or standard definition (sd) (contentDetails.definition)

																/* No info on this returned from function */

																$provider_video_videoQuality = '';

															// Frame size

																/* No info on this returned from function */

																$provider_video_videoFrameSize = '';

														}

											} elseif ( str_contains( $provider_video_parsed['host'], 'vimeo' ) ) {

												// If Vimeo

													// Embed URL

														$provider_video_embedUrl = $provider_video_parsed['path'] ? 'https://www.youtube.com/embed/' . $provider_video_parsed['path']: '';

											}

										}

								// Format values

									if ( $provider_video_url ) {

										// Base array

											$provider_video = array();

										// @type

											$provider_video['@type'] = 'VideoObject';

										// @id

											if ( !isset($provider_url) ) {

												$provider_url = get_permalink($entity);
												$provider_url = $provider_url ? user_trailingslashit( $provider_url ) : '';

											}

											$provider_video['@id'] = $provider_url . '#' . $provider_video['@type'];

										// Other properties

											$provider_video['abstract'] = '';
											$provider_video['alternateName'] = '';
											$provider_video['audience'] = '';
											$provider_video['creator'] = '';
											$provider_video['dateModified'] = '';
											$provider_video['datePublished'] = $provider_video_published ?? '';
											$provider_video['description'] = $provider_video_description ?? '';
											$provider_video['duration'] = $provider_video_duration ?? '';
											$provider_video['embedUrl'] = $provider_video_embedUrl ?? '';
											$provider_video['isAccessibleForFree'] = 'True';

											// isPartOf [WIP]

												$provider_video['isPartOf'] = null;

											$provider_video['mainEntityOfPage'] = '' ?? '';
											$provider_video['name'] = $provider_video_title ?? '';
											$provider_video['sameAs'] = $provider_video_url ?? '';
											$provider_video['sourceOrganization'] = '';
											$provider_video['speakable'] = '';
											$provider_video['subjectOf'] = '';
											$provider_video['thumbnail'] = $provider_video_thumbnail ?? '';
											$provider_video['timeRequired'] = $provider_video_duration ?? '';
											$provider_video['transcript'] = $provider_video_caption ?? '';
											$provider_video['url'] = $provider_video_url ?? '';
											$provider_video['videoFrameSize'] = $provider_video_videoFrameSize ?? '';
											$provider_video['videoQuality'] = $provider_video_videoQuality ?? '';

										// Clean up the array

											if ( $provider_video ) {

												$provider_video = array_filter($provider_video);

											}
									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'video', // string // Required // Name of schema property
											$provider_video, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'video', // string // Required // Name of schema property
											$provider_video, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'video', // string // Required // Name of schema property
											$provider_video, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// workLocation

							/**
								* A contact location for a person's place of work.
								*
								* Values expected to be one of these types:
								*
								*      - ContactPoint
								*      - Place
								*
								* Used on these types:
								*
								*      - Person
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'workLocation',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'workLocation',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'workLocation',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'workLocation', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'workLocation', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'workLocation', // string // Required // Name of schema property
											$provider_location_LocalBusiness, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Merge location significantLink value into significantLink

									$provider_location_MedicalWebPage_significantLink = $provider_location_MedicalWebPage_significantLink ?? null;

									if ( $provider_location_MedicalWebPage_significantLink ) {

										$provider_significantLink = uamswp_fad_schema_merge_values(
											$provider_significantLink, // mixed // Required // Initial schema item property value
											$provider_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
										);

									}

								// Merge location keywords value into keywords

									$provider_location_LocalBusiness_keywords = $provider_location_LocalBusiness_keywords ?? null;

									if ( $provider_location_LocalBusiness_keywords ) {

										$provider_keywords = uamswp_fad_schema_merge_values(
											$provider_keywords, // mixed // Required // Initial schema item property value
											$provider_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
										);

									}

							}

						// worksFor

							/**
								* Organizations that the person works for.
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*
								* Used on these types:
								*
								*      - Person
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'worksFor',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'worksFor',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'worksFor',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_worksFor = array();

									// Merge in common 'worksFor' value

										if (
											isset($schema_common_worksFor)
											&&
											!empty($schema_common_worksFor)
										) {

											$provider_worksFor = uamswp_fad_schema_merge_values(
												$provider_worksFor, // mixed // Required // Initial schema item property value
												$schema_common_worksFor // mixed // Required // Incoming schema item property value
											);

										}

									// Merge in specific clinical 'Organization' value

										$provider_specific_clinical_organization = $provider_specific_clinical_organization ?? null;

										if ( $provider_specific_clinical_organization ) {

											$provider_worksFor = uamswp_fad_schema_merge_values(
												$provider_worksFor, // mixed // Required // Initial schema item property value
												$provider_specific_clinical_organization // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'workLocation', // string // Required // Name of schema property
											$provider_worksFor, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'workLocation', // string // Required // Name of schema property
											$provider_worksFor, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'worksFor', // string // Required // Name of schema property
											$provider_worksFor, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$provider_worksFor = $provider_worksFor ?? null;

									if ( $provider_worksFor ) {

										$provider_worksFor_keywords = uamswp_fad_schema_property_values(
											$provider_worksFor, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge worksFor keywords value into keywords

										$provider_worksFor_keywords = $provider_worksFor_keywords ?? null;

										if ( $provider_worksFor_keywords ) {

											$provider_keywords = uamswp_fad_schema_merge_values(
												$provider_keywords, // mixed // Required // Initial schema item property value
												$provider_worksFor_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

						// knowsAbout

							/**
								* Of a Person, and less typically of an Organization, to indicate a topic that is
								* known about — suggesting possible expertise but not implying it. We do not
								* distinguish skill levels here, or relate this to educational content, events,
								* objectives or JobPosting descriptions.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*      - Thing
								*      - URL
								*
								* Used on these types:
								*
								*      - Organization
								*      - Person
								*
								* As of 16 Apr 2024, this term is in the "new" area of Schema.org. Implementation
								* feedback and adoption from applications and websites can help improve their
								* definitions.
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'knowsAbout',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'knowsAbout',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'knowsAbout',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_knowsAbout = array();

									// Get MedicalCode from clinical specializations

										// Get Provider Clinical Specialization and Occupational Title

											if ( !isset($uamswp_fad_clinical_specialization_provider) ) {

												$uamswp_fad_clinical_specialization_provider = uamswp_fad_clinical_specialization_provider(
													$entity // int // ID of the provider profile
												);

											}

										// Loop through the clinical specializations details to get the MedicalCode values

											$provider_clinical_specialization_MedicalCode = array();

											if (
												isset($uamswp_fad_clinical_specialization_provider['detail_array'])
												&&
												$uamswp_fad_clinical_specialization_provider['detail_array']
											) {

												foreach ( $uamswp_fad_clinical_specialization_provider['detail_array'] as $item ) {

													// Term

														if (
															isset($item['schema']['MedicalCode'])
															&&
															$item['schema']['MedicalCode']
														) {

																$provider_clinical_specialization_MedicalCode[] = $item['schema']['MedicalCode'];

														}

													// Term's Ancestors

														if (
															isset($item['ancestors'])
															&&
															$item['ancestors']
														) {

															foreach ( $item['ancestors'] as $ancestor_item ) {

																if (
																	isset($ancestor_item['schema']['MedicalCode'])
																	&&
																	$ancestor_item['schema']['MedicalCode']
																) {

																	$provider_clinical_specialization_MedicalCode[] = $ancestor_item['schema']['MedicalCode'];

																}

															}

														}

												}

											}

										// Add to knowsAbout list array

											$provider_clinical_specialization_MedicalCode = $provider_clinical_specialization_MedicalCode ?? null;

											if ( $provider_clinical_specialization_MedicalCode ) {

												$provider_knowsAbout = uamswp_fad_schema_merge_values(
													$provider_knowsAbout, // mixed // Required // Initial schema item property value
													$provider_clinical_specialization_MedicalCode // mixed // Required // Incoming schema item property value
												);

											}

										// Get values for keywords property

											$provider_clinical_specialization_MedicalCode = $provider_clinical_specialization_MedicalCode ?? null;

											if ( $provider_clinical_specialization_MedicalCode ) {

												$provider_clinical_specialization_MedicalCode_keywords = uamswp_fad_schema_property_values(
													$provider_clinical_specialization_MedicalCode, // array // Required // Property values from which to extract specific values
													array( 'name', 'alternateName', 'codeValue' ) // mixed // Required // List of properties from which to collect values
												);

											}

											// Merge clinical specializations keywords value into keywords

												$provider_clinical_specialization_MedicalCode_keywords = $provider_clinical_specialization_MedicalCode_keywords ?? null;

												if ( $provider_clinical_specialization_MedicalCode_keywords ) {

													$provider_keywords = uamswp_fad_schema_merge_values(
														$provider_keywords, // mixed // Required // Initial schema item property value
														$provider_clinical_specialization_MedicalCode_keywords // mixed // Required // Incoming schema item property value
													);

												}

									// Merge in related areas of expertise value

										$provider_expertise_MedicalEntity = $provider_expertise_MedicalEntity ?? null;

										if ( $provider_expertise_MedicalEntity ) {

											$provider_knowsAbout = uamswp_fad_schema_merge_values(
												$provider_knowsAbout, // mixed // Required // Initial schema item property value
												$provider_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge areas of expertise significantLink value into significantLink

											$provider_expertise_MedicalWebPage_significantLink = $provider_expertise_MedicalWebPage_significantLink ?? null;

											if ( $provider_expertise_MedicalWebPage_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge areas of expertise keywords value into keywords

											$provider_expertise_MedicalEntity_keywords = $provider_expertise_MedicalEntity_keywords ?? null;

											if ( $provider_expertise_MedicalEntity_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related conditions value

										$provider_condition = $provider_condition ?? null;

										if ( $provider_condition ) {

											$provider_knowsAbout = uamswp_fad_schema_merge_values(
												$provider_knowsAbout, // mixed // Required // Initial schema item property value
												$provider_condition // mixed // Required // Incoming schema item property value
											);

										}

										// Merge conditions significantLink value into significantLink

											$provider_condition_significantLink = $provider_condition_significantLink ?? null;

											if ( $provider_condition_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_condition_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge conditions keywords value into keywords

											$provider_condition_keywords = $provider_condition_keywords ?? null;

											if ( $provider_condition_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_condition_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related treatments value

										$provider_availableService = $provider_availableService ?? null;

										if ( $provider_availableService ) {

											$provider_knowsAbout = uamswp_fad_schema_merge_values(
												$provider_knowsAbout, // mixed // Required // Initial schema item property value
												$provider_availableService // mixed // Required // Incoming schema item property value
											);

										}

										// Merge availableService significantLink value into significantLink

											$provider_availableService_significantLink = $provider_availableService_significantLink ?? null;

											if ( $provider_availableService_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_availableService_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge availableService keywords value into keywords

											$provider_availableService_keywords = $provider_availableService_keywords ?? null;

											if ( $provider_availableService_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_availableService_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'knowsAbout', // string // Required // Name of schema property
											$provider_knowsAbout, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'knowsAbout', // string // Required // Name of schema property
											$provider_knowsAbout, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'knowsAbout', // string // Required // Name of schema property
											$provider_knowsAbout, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// mentions

							/**
								* Indicates that the CreativeWork contains a reference to, but is not necessarily
								* about a concept.
								*
								* Values expected to be one of these types:
								*
								*      - Thing
								*
								* Used on these types:
								*
								*      - CreativeWork
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'mentions',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'mentions',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'mentions',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_mentions = array();

									// Merge in related locations value

										$provider_location_LocalBusiness = $provider_location_LocalBusiness ?? null;

										if ( $provider_location_LocalBusiness ) {

											$provider_mentions = uamswp_fad_schema_merge_values(
												$provider_mentions, // mixed // Required // Initial schema item property value
												$provider_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge location significantLink value into significantLink

											$provider_location_MedicalWebPage_significantLink = $provider_location_MedicalWebPage_significantLink ?? null;

											if ( $provider_location_MedicalWebPage_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge location keywords value into keywords

											$provider_location_LocalBusiness_keywords = $provider_location_LocalBusiness_keywords ?? null;

											if ( $provider_location_LocalBusiness_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related areas of expertise value

										$provider_expertise_MedicalEntity = $provider_expertise_MedicalEntity ?? null;

										if ( $provider_expertise_MedicalEntity ) {

											$provider_mentions = uamswp_fad_schema_merge_values(
												$provider_mentions, // mixed // Required // Initial schema item property value
												$provider_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge areas of expertise significantLink value into significantLink

											$provider_expertise_MedicalWebPage_significantLink = $provider_expertise_MedicalWebPage_significantLink ?? null;

											if ( $provider_expertise_MedicalWebPage_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge areas of expertise keywords value into keywords

											$provider_expertise_MedicalEntity_keywords = $provider_expertise_MedicalEntity_keywords ?? null;

											if ( $provider_expertise_MedicalEntity_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related clinical resources value

										$provider_clinical_resource_CreativeWork = $provider_clinical_resource_CreativeWork ?? null;

										if ( $provider_clinical_resource_CreativeWork ) {

											$provider_mentions = uamswp_fad_schema_merge_values(
												$provider_mentions, // mixed // Required // Initial schema item property value
												$provider_clinical_resource_CreativeWork // mixed // Required // Incoming schema item property value
											);

										}

										// Merge clinical resources significantLink value into significantLink

											$provider_clinical_resource_MedicalWebPage_significantLink = $provider_clinical_resource_MedicalWebPage_significantLink ?? null;

											if ( $provider_clinical_resource_MedicalWebPage_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_clinical_resource_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related conditions value

										$provider_condition = $provider_condition ?? null;

										if ( $provider_condition ) {

											$provider_mentions = uamswp_fad_schema_merge_values(
												$provider_mentions, // mixed // Required // Initial schema item property value
												$provider_condition // mixed // Required // Incoming schema item property value
											);

										}

										// Merge conditions significantLink value into significantLink

											$provider_condition_significantLink = $provider_condition_significantLink ?? null;

											if ( $provider_condition_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_condition_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge conditions keywords value into keywords

											$provider_condition_keywords = $provider_condition_keywords ?? null;

											if ( $provider_condition_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_condition_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related treatments value

										$provider_availableService = $provider_availableService ?? null;

										if ( $provider_availableService ) {

											$provider_mentions = uamswp_fad_schema_merge_values(
												$provider_mentions, // mixed // Required // Initial schema item property value
												$provider_availableService // mixed // Required // Incoming schema item property value
											);

										}

										// Merge availableService significantLink value into significantLink

											$provider_availableService_significantLink = $provider_availableService_significantLink ?? null;

											if ( $provider_availableService_significantLink ) {

												$provider_significantLink = uamswp_fad_schema_merge_values(
													$provider_significantLink, // mixed // Required // Initial schema item property value
													$provider_availableService_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge availableService keywords value into keywords

											$provider_availableService_keywords = $provider_availableService_keywords ?? null;

											if ( $provider_availableService_keywords ) {

												$provider_keywords = uamswp_fad_schema_merge_values(
													$provider_keywords, // mixed // Required // Initial schema item property value
													$provider_availableService_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$provider_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$provider_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$provider_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// relatedLink

							/**
								* A link related to this web page, for example to other related web pages.
								*
								* Values expected to be one of these types:
								*
								*      - URL
								*
								* Used on these types:
								*
								*      - WebPage
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'relatedLink',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'relatedLink',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'relatedLink',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'relatedLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'relatedLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'relatedLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// significantLink

							/**
								* One of the more significant URLs on the page. Typically, these are the
								* non-navigation links that are clicked on the most.
								*
								* Values expected to be one of these types:
								*
								*      - URL
								*
								* Used on these types:
								*
								*      - WebPage
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'significantLink',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'significantLink',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'significantLink',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$provider_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// keywords

							/**
								* Keywords or tags used to describe some item. Multiple textual entries in a
								* keywords list are typically delimited by commas, or by repeating the property.
								*
								* Values expected to be one of these types:
								*
								*      - DefinedTerm
								*      - Text
								*      - URL
								*
								* Used on these types:
								*
								*      - CreativeWork
								*      - Event
								*      - Organization
								*      - Place
								*      - Product
								*/

							if (
								(
									(
										isset($provider_item_MedicalWebPage)
										&&
										in_array(
											'keywords',
											$provider_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($provider_item_MedicalBusiness)
										&&
										in_array(
											'keywords',
											$provider_properties_map[$MedicalBusiness_type]['properties']
										)
									)
									||
									(
										isset($provider_item_Person)
										&&
										in_array(
											'keywords',
											$provider_properties_map[$Person_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$provider_keywords = $provider_keywords ?? array();

									// Common values

										$provider_keywords[] = 'health care provider';
										$provider_keywords[] = $provider_honorificPrefix ? 'doctor' : '';
										$provider_keywords[] = $provider_honorificPrefix ? 'physician' : '';

									// Clean up list array

										if ( $provider_keywords ) {

											$provider_keywords = array_filter($provider_keywords);
											$provider_keywords = array_unique( $provider_keywords, SORT_REGULAR );
											$provider_keywords = array_values($provider_keywords);
											uamswp_fad_flatten_multidimensional_array($provider_keywords);

											if ( is_array($provider_keywords) ) {

												sort( $provider_keywords, SORT_NATURAL | SORT_FLAG_CASE );

											}

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$provider_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalBusiness

										uamswp_fad_schema_add_to_item_values(
											$MedicalBusiness_type, // string // Required // The @type value for the schema item
											$provider_item_MedicalBusiness, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$provider_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// Person

										uamswp_fad_schema_add_to_item_values(
											$Person_type, // string // Required // The @type value for the schema item
											$provider_item_Person, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$provider_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$provider_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

					// Sort and combine the arrays

						if ( isset($provider_item_MedicalWebPage) ) {

							ksort( $provider_item_MedicalWebPage, SORT_NATURAL | SORT_FLAG_CASE );
							$provider_item['MedicalWebPage'] = $provider_item_MedicalWebPage;

						}

						if ( isset($provider_item_MedicalBusiness) ) {

							ksort( $provider_item_MedicalBusiness, SORT_NATURAL | SORT_FLAG_CASE );
							$provider_item['MedicalBusiness'] = $provider_item_MedicalBusiness;

						}

						if ( isset($provider_item_Person) ) {

							ksort( $provider_item_Person, SORT_NATURAL | SORT_FLAG_CASE );
							$provider_item['Person'] = $provider_item_Person;

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity . '_' . implode( '_', $output_types ) . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
							$provider_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to lists of providers

						// Add to list of MedicalWebPage items

							if (
								isset($provider_item['MedicalWebPage'])
								&&
								!empty($provider_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $provider_item['MedicalWebPage'];

							}

						// Add to list of MedicalBusiness items

							if (
								isset($provider_item['MedicalBusiness'])
								&&
								!empty($provider_item['MedicalBusiness'])
							) {

								$MedicalBusiness_list[] = $provider_item['MedicalBusiness'];

							}

						// Add to list of Person items

							if (
								isset($provider_item['Person'])
								&&
								!empty($provider_item['Person'])
							) {

								$Person_list[] = $provider_item['Person'];

							}

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list arrays

			// MedicalWebPage

				$MedicalWebPage_list = array_filter($MedicalWebPage_list);
				$MedicalWebPage_list = array_values($MedicalWebPage_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalWebPage_list);

			// MedicalBusiness

				$MedicalBusiness_list = array_filter($MedicalBusiness_list);
				$MedicalBusiness_list = array_values($MedicalBusiness_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalBusiness_list);

			// Person

				$Person_list = array_filter($Person_list);
				$Person_list = array_values($Person_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($Person_list);

		// Combine lists for return

			// MedicalWebPage

				if ( $MedicalWebPage_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($provider_list['MedicalWebPage'])
							&&
							!empty($provider_list['MedicalWebPage'])
						) {

							$provider_list['MedicalWebPage'] = array_is_list($provider_list['MedicalWebPage']) ? $provider_list['MedicalWebPage'] : array($provider_list['MedicalWebPage']);

						}

					$provider_list['MedicalWebPage'] = $MedicalWebPage_list;

				}

			// MedicalBusiness

				if ( $MedicalBusiness_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($provider_list['MedicalBusiness'])
							&&
							!empty($provider_list['MedicalBusiness'])
						) {

							$provider_list['MedicalBusiness'] = array_is_list($provider_list['MedicalBusiness']) ? $provider_list['MedicalBusiness'] : array($provider_list['MedicalBusiness']);

						}

					$provider_list['MedicalBusiness'] = $MedicalBusiness_list;

				}

			// Person

				if ( $Person_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($provider_list['Person'])
							&&
							!empty($provider_list['Person'])
						) {

							$provider_list['Person'] = array_is_list($provider_list['Person']) ? $provider_list['Person'] : array($provider_list['Person']);

						}

					$provider_list['Person'] = $Person_list;

				}

	} // endif ( !empty($repeater) )

	return $provider_list;

}