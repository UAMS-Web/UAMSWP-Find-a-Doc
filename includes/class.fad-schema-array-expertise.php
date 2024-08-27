<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Area of Expertise ontology page type (MedicalWebPage; MedicalEntity)
 */

function uamswp_fad_schema_expertise(
	array $repeater, // array // Required // List of IDs of the area of expertise items
	string $expertise_url, // string // Required // Page or fake subpage URL
	bool $ontology_type, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
	string $current_fpage, // string // Required // Fake subpage slug
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // int // Optional // Nesting level within the main schema
	int $MedicalWebPage_i = 1, // int // Optional //  Iteration counter for area of expertise-as-MedicalWebPage
	int $MedicalEntity_i = 1, // int // Optional //  Iteration counter for area of expertise-as-MedicalEntity
	array $expertise_fields = array(), // array // Optional // Pre-existing field values array so duplicate calls can be avoided
	array $MedicalWebPage_list = array(), // array // Optional // Pre-existing list array for area of expertise-as-MedicalWebPage to which to add additional items
	array $MedicalEntity_list = array(), // array // Optional // Pre-existing list array for area of expertise-as-MedicalEntity to which to add additional items
	array $expertise_list = array() // array // Optional // Pre-existing list array for combined area of expertise schema to which to add additional items
) {

	if ( !empty($repeater) ) {

		// Base schema function variables

			include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base_function.php' );

		// List of valid types

			/**
			 * Define the list of high-level types that are considered valid. The list may be
			 * expanded later to include the subtypes of these high-level types.
			 *
			 * Exclude 'MedicalEntity' from the initial list so that its subtypes are not
			 * added to the list of valid types.
			 */

			// List of Schema.org types for which to not get the subtypes

				$expertise_valid_types = array(
					'MedicalEntity'
				);

			// List of Schema.org types for which to get the subtypes

				$expertise_valid_types_plus_subtypes = array(
					'MedicalWebPage'
				);

			// Base array for schema.org type URLs

				$expertise_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$expertise_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$expertise_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$expertise_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$expertise_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $expertise_valid_types as $item ) {

					$expertise_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$expertise_properties_map[$item]['properties'] = is_array($expertise_properties_map[$item]['properties']) ? $expertise_properties_map[$item]['properties'] : array($expertise_properties_map[$item]['properties']);

				}

		// Loop through each area of expertise to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity . ( $current_fpage ? '_' . $current_fpage : '' ) . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
						$expertise_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if (
					!empty( $expertise_item )
					&&
					(
						(
							isset($expertise_item['MedicalWebPage'])
							&&
							!empty($expertise_item['MedicalWebPage'])
						)
						||
						(
							isset($expertise_item['MedicalEntity'])
							&&
							!empty($expertise_item['MedicalEntity'])
						)
					)
				) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to lists of areas of expertise

						// Add to list of MedicalWebPage items

							if (
								isset($expertise_item['MedicalWebPage'])
								&&
								!empty($expertise_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $expertise_item['MedicalWebPage'];

							}

						// Add to list of MedicalEntity items

							if (
								isset($expertise_item['MedicalEntity'])
								&&
								!empty($expertise_item['MedicalEntity'])
							) {

								$MedicalEntity_list[] = $expertise_item['MedicalEntity'];

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

						$expertise_item = array(); // Base array
						$expertise_item_MedicalWebPage = in_array( 'MedicalWebPage', $expertise_valid_types ) ? array() : null; // Base MedicalWebPage array
						$expertise_item_MedicalEntity = in_array( 'MedicalEntity', $expertise_valid_types ) ? array() : null; // Base MedicalEntity array
						$current_fpage = null;
						$expertise_additionalType = null;
						$expertise_alternateName = null;
						$expertise_alternateName_array = null;
						$expertise_alternateName_repeater = null;
						$expertise_code = null;
						$expertise_code_repeater = null;
						$expertise_description = null;
						$expertise_description_TextObject = null;
						$expertise_featured_image = null;
						$expertise_grant = null;
						$expertise_id = null;
						$expertise_image = null;
						$expertise_image_id = null;
						$expertise_level = null;
						$expertise_mainEntityOfPage = '';
						$expertise_medicineSystem = null;
						$expertise_medicineSystem_select = null;
						$expertise_name = null;
						$expertise_nucc_array = null;
						$expertise_potentialAction = null;
						$expertise_recognizingAuthority = null;
						$expertise_relevantSpecialty = null;
						$expertise_sameAs = null;
						$expertise_sameAs_repeater = null;
						$expertise_study = null;
						$expertise_subjectOf = null;
						$expertise_url = null;
						$fpage_query = null;
						$MedicalCondition_i = 1;
						$MedicalEntity_type = null;
						$MedicalWebPage_id = null;
						$MedicalWebPage_type = null;
						$ontology_type = null;
						$Service_i = 1;

					// Load variables from pre-existing field values array

						if (
							isset($expertise_fields[$entity])
							&&
							!empty($expertise_fields[$entity])
						) {

							foreach ( $expertise_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($ontology_type) ) {

							$ontology_type = get_field( 'expertise_type', $entity ) ?? true; // Check if 'expertise_type' is not null, and if so, set value to true

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($current_fpage) ) {

								$current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($fpage_query) ) {

								$fpage_query = $current_fpage ? true : false;

							}

						}

					// Add property values

						/**
						 * The following properties are either beyond the scope of what is being included
						 * in the facility item schema; irrelevant to the facility item schema; are
						 * superseded by another property; or are already being defined in the common
						 * schema properties (templates/parts/vars/page/schema/common/properties.php) and
						 * so they will not be included here:
						 *
						 *      * about
						 *      * abstract
						 *      * accessibilityAPI
						 *      * accessibilityControl
						 *      * accessibilityFeature
						 *      * accessibilityHazard
						 *      * accessibilitySummary
						 *      * accessMode
						 *      * accessModeSufficient
						 *      * accountablePerson
						 *      * acquireLicensePage
						 *      * aggregateRating
						 *      * alternativeHeadline
						 *      * archivedAt
						 *      * aspect
						 *      * assesses
						 *      * associatedMedia
						 *      * audience
						 *      * audio
						 *      * author
						 *      * award
						 *      * awards
						 *      * breadcrumb
						 *      * character
						 *      * comment
						 *      * commentCount
						 *      * conditionsOfAccess
						 *      * contentLocation
						 *      * contentRating
						 *      * contentReferenceTime
						 *      * contributor
						 *      * copyrightHolder
						 *      * copyrightNotice
						 *      * copyrightYear
						 *      * correction
						 *      * countryOfOrigin
						 *      * creativeWorkStatus
						 *      * creator
						 *      * creditText
						 *      * dateCreated
						 *      * dateModified
						 *      * datePublished
						 *      * description
						 *      * digitalSourceType
						 *      * discussionUrl
						 *      * editEIDR
						 *      * editor
						 *      * educationalAlignment
						 *      * educationalLevel
						 *      * educationalUse
						 *      * encoding
						 *      * encodingFormat
						 *      * encodings
						 *      * exampleOfWork
						 *      * expires
						 *      * fileFormat
						 *      * funder
						 *      * funding
						 *      * genre
						 *      * headline
						 *      * identifier
						 *      * inLanguage
						 *      * interactionStatistic
						 *      * interactivityType
						 *      * interpretedAsClaim
						 *      * isAccessibleForFree
						 *      * isBasedOn
						 *      * isBasedOnUrl
						 *      * isFamilyFriendly
						 *      * isPartOf
						 *      * lastReviewed
						 *      * learningResourceType
						 *      * legalStatus
						 *      * license
						 *      * locationCreated
						 *      * mainEntity
						 *      * mainEntityOfPage
						 *      * maintainer
						 *      * material
						 *      * materialExtent
						 *      * medicalAudience
						 *      * pattern
						 *      * position
						 *      * producer
						 *      * provider
						 *      * publication
						 *      * publisher
						 *      * publisherImprint
						 *      * publishingPrinciples
						 *      * recordedAt
						 *      * releasedEvent
						 *      * review
						 *      * reviewedBy
						 *      * reviews
						 *      * schemaVersion
						 *      * sdDatePublished
						 *      * sdLicense
						 *      * sdPublisher
						 *      * significantLinks
						 *      * size
						 *      * sourceOrganization
						 *      * spatial
						 *      * spatialCoverage
						 *      * sponsor
						 *      * subjectOf
						 *      * teaches
						 *      * temporal
						 *      * temporalCoverage
						 *      * thumbnail
						 *      * thumbnailUrl
						 *      * translationOfWork
						 *      * translator
						 *      * typicalAgeRange
						 *      * usageInfo
						 *      * version
						 *      * workExample
						 *      * workTranslation
						 */

						// url

							/**
							 * URL of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 */

							// Get values

								if (
									!isset($expertise_url)
									||
									empty($expertise_url)
								) {

									$expertise_url = get_permalink($entity);
									$expertise_url = $expertise_url ? user_trailingslashit( $expertise_url ) : '';

									if ( $fpage_query ) {

										if ( !isset($fpage_url) ) {

											$fpage_url = !empty($current_fpage) ? trailingslashit($expertise_url) . user_trailingslashit($current_fpage) : $expertise_url; // Fake subpage URL

										}

										$expertise_url = $fpage_url;

									}

								}

							// Pass the values to common schema properties template part

								$schema_common_url = $expertise_url;

							// Add to item values

								// MedicalWebPage

									if (
										isset($expertise_item_MedicalWebPage)
										&&
										$expertise_url
									) {

										$expertise_item_MedicalWebPage['url'] = $expertise_url;

									}

								// MedicalEntity

									if (
										isset($expertise_item_MedicalEntity)
										&&
										$expertise_url
									) {

										$expertise_item_MedicalEntity['url'] = $expertise_url;

									}

						// @type

							// MedicalWebPage type

								// Get values

									$MedicalWebPage_type = 'MedicalWebPage';

								// Add to item values

									if (
										isset($expertise_item_MedicalWebPage)
										&&
										$MedicalWebPage_type
									) {

										$expertise_item_MedicalWebPage['@type'] = $MedicalWebPage_type;

									}

							// MedicalEntity type

								// Get values

									$MedicalEntity_type = 'MedicalEntity';

								// Add to item values

									if (
										isset($expertise_item_MedicalEntity)
										&&
										$MedicalEntity_type
									) {

										$expertise_item_MedicalEntity['@type'] = $MedicalEntity_type;

									}

						// @id

							// MedicalWebPage

								// Get values

									$MedicalWebPage_id = $expertise_url . '#' . $MedicalWebPage_type;
									// $MedicalWebPage_id .= $MedicalWebPage_i;
									// $MedicalWebPage_i++;

								// Add to item values

									if (
										isset($expertise_item_MedicalWebPage)
										&&
										$MedicalWebPage_id
									) {

										$expertise_item_MedicalWebPage['@id'] = $MedicalWebPage_id;
										$node_identifier_list[] = $expertise_item_MedicalWebPage['@id']; // Add to the list of existing node identifiers

									}

							// MedicalEntity

								// Get values

									$MedicalEntity_id = $expertise_url . '#' . $MedicalEntity_type;
									// $MedicalEntity_id .= $MedicalEntity_i;
									// $MedicalEntity_i++;

								// Add to item values

									if (
										isset($expertise_item_MedicalEntity)
										&&
										$MedicalEntity_id
									) {

										$expertise_item_MedicalEntity['@id'] = $MedicalEntity_id;
										$node_identifier_list[] = $expertise_item_MedicalEntity['@id']; // Add to the list of existing node identifiers

									}

						// Specific Clinical Organizations

							/*

								e.g., Arkansas Children's, Baptist Health, Central Arkansas Veterans Healthcare System

							*/

							// List of properties that reference organizations (i.e., 'Organization')

								$expertise_organization_common = array(
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
										isset($expertise_item_MedicalWebPage)
										&&
										array_intersect(
											$expertise_properties_map[$MedicalWebPage_type]['properties'],
											$expertise_organization_common
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										array_intersect(
											$expertise_properties_map[$MedicalEntity_type]['properties'],
											$expertise_organization_common
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Query: Whether to override the default clinical brand organization for this entity

										$expertise_specific_clinical_organization_override = false;

									// Get list of Third-Party Brand Organizations

										// Base array

											$expertise_specific_clinical_organization = array();

										$expertise_specific_clinical_organization = uamswp_fad_schema_brand_organization_list(
											$entity, // int|WP_Term // Required // Post ID or term object
											$expertise_specific_clinical_organization // array // Optional // Pre-existing list array for brand organizations to which to add additional items
										);

								// Pass the values to common schema properties template part

									$schema_common_specific_brand_organization_override = $expertise_specific_clinical_organization_override; // Query for whether to override common clinical organization(s) with those specific to the current entity
									$schema_common_specific_brand_organization = $expertise_specific_clinical_organization; // Clinical organization(s) specific to the current entity

							}

						// Add common properties

							// Pass variables to template part

								$schema_common_item_MedicalWebPage = $expertise_item_MedicalWebPage; // MedicalWebPage item array
								$schema_common_item_mainEntity = $expertise_item_MedicalEntity ?? null; // item array for the main entity of the MedicalWebPage
								$schema_common_item_about = $expertise_item_MedicalEntity ?? null; // all major entities of the MedicalWebPage

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
													$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// MedicalEntity

												uamswp_fad_schema_add_to_item_values(
													$MedicalEntity_type, // string // Required // The @type value for the schema item
													$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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
													$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// MedicalEntity

												uamswp_fad_schema_add_to_item_values(
													$MedicalEntity_type, // string // Required // The @type value for the schema item
													$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// MedicalEntity

												uamswp_fad_schema_add_to_item_values(
													$MedicalEntity_type, // string // Required // The @type value for the schema item
													$location_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$location_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

									}

								}

						// Associated ontology items (e.g., providers, areas of expertise, clinical resources, conditions, treatments)

							// Associated providers

								// List of properties that reference associated providers

									$expertise_provider_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_provider_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_provider_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										// Get list of associated providers

											if ( !isset($expertise_provider_ids) ) {

												$expertise_provider_ids = array();

												$providers = get_field( 'physician_expertise', $entity );
												$page_id_temp = $page_id ?? null;
												$page_id = $entity;
												include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );
												$expertise_provider_ids = $provider_ids;

												// Reset variables from Related Providers Section Query template part

													$page_id = $page_id_temp;
													$providers = null;
													$provider_query = null;
													$provider_section_show = null;
													$provider_ids = null;
													$provider_count = null;
													$jump_link_count = null;

											}

										// Format values

											if ( $expertise_provider_ids ) {

												$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

												if ( function_exists('uamswp_fad_schema_provider') ) {

													$expertise_provider = uamswp_fad_schema_provider(
														$expertise_provider_ids, // array // Required // List of IDs of the provider items
														$expertise_url, // string // Required // Page URL
														$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
														($nesting_level + 1), // int // Optional // Nesting level within the main schema
														array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ) // array // Optional // List of the schema types to output
													) ?? null;

												} else {

													$expertise_provider = null;

												}

												if ( isset($expertise_provider) ) {

													$expertise_provider_about = array(); // all major entities of the ontology type's MedicalWebPages

													// MedicalWebPage

														$expertise_provider_MedicalWebPage = $expertise_provider['MedicalWebPage'] ?? null;

														// Get URLs for significantLink property

															if ( $expertise_provider_MedicalWebPage ) {

																$expertise_provider_MedicalWebPagesignificantLink = uamswp_fad_schema_property_values(
																	$expertise_provider_MedicalWebPage, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															}

													// MedicalBusiness and subtypes

														$expertise_provider_MedicalBusiness = $expertise_provider['MedicalBusiness'] ?? null;
														if ( isset($expertise_provider_MedicalBusiness) ) { $expertise_provider_about[] = $expertise_provider_MedicalBusiness; } // Add to the list of all major entities of the ontology type's MedicalWebPages

														if ( $expertise_provider_MedicalBusiness ) {

															// Get URLs for significantLink property

																$expertise_provider_MedicalBusiness_significantLink = uamswp_fad_schema_property_values(
																	$expertise_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$expertise_provider_MedicalBusiness_keywords = uamswp_fad_schema_property_values(
																	$expertise_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

													// Person

														$expertise_provider_Person = $expertise_provider['Person'] ?? null;
														$expertise_provider_mainEntity = $expertise_provider_Person; // item array for the main entity of the ontology type's MedicalWebPages
														if ( isset($expertise_provider_Person) ) { $expertise_provider_about[] = $expertise_provider_Person; } // Add to the list of all major entities of the ontology type's MedicalWebPages

														if ( $expertise_provider_Person ) {

															// Get URLs for significantLink property

																$expertise_provider_Person_significantLink = uamswp_fad_schema_property_values(
																	$expertise_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$expertise_provider_Person_keywords = uamswp_fad_schema_property_values(
																	$expertise_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

												}

											}

								}

							// Associated Locations

								// List of properties that reference locations

									$expertise_location_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_location_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_location_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										if ( !isset($expertise_location_array) ) {

											$expertise_location_array = get_field( 'location_expertise', $entity ) ?? array(); // array

											// Clean up the array

												$expertise_location_array = $expertise_location_array ? array_filter($expertise_location_array) : array();
												$expertise_location_array = $expertise_location_array ? array_values($expertise_location_array) : array();

										}

									// Format values

										if ( $expertise_location_array ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_location') ) {

												$expertise_location = uamswp_fad_schema_location(
													$expertise_location_array, // List of IDs of the location items
													$expertise_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$expertise_location = null;

											}

											if ( isset($expertise_location) ) {

												$expertise_location_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$expertise_location_MedicalWebPage = $expertise_location['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $expertise_location_MedicalWebPage ) {

															$expertise_location_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$expertise_location_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// LocalBusiness and subtypes

													$expertise_location_LocalBusiness = $expertise_location['LocalBusiness'] ?? null;
													$expertise_location_mainEntity = $expertise_location_LocalBusiness; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($expertise_location_LocalBusiness) ) { $expertise_location_about[] = $expertise_location_LocalBusiness; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $expertise_location_LocalBusiness ) {

														// Get URLs for significantLink property

															$expertise_location_LocalBusiness_significantLink = uamswp_fad_schema_property_values(
																$expertise_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$expertise_location_LocalBusiness_keywords = uamswp_fad_schema_property_values(
																$expertise_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Descendant areas of expertise

								// List of properties that reference areas of expertise

									$expertise_descendant_expertise_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_descendant_expertise_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_descendant_expertise_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get descendant areas of expertise

										if ( !isset($expertise_descendant_expertise_list) ) {

											$expertise_descendant_expertise_list = get_children(
												array(
													'post_parent' => $entity,
													'post_type'=> 'expertise',
													'posts_per_page' => -1,
													'post_status' => 'publish'
												), // mixed // Optional // User defined arguments for replacing the defaults (Default: '')
												ARRAY_A // string // Optional // The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond to a WP_Post object, an associative array, or a numeric array, respectively. (Default: '')
											);

											if ( $expertise_descendant_expertise_list ) {

												$expertise_descendant_expertise_list_temp = array();

												foreach ( $expertise_descendant_expertise_list as $item ) {

													if (
														isset($item['ID'])
														&&
														$item['ID']
													) {

														$expertise_descendant_expertise_list_temp[] = $item['ID'];

													}

												}

												$expertise_descendant_expertise_list = $expertise_descendant_expertise_list_temp;

											} // endif ( $expertise_descendant_expertise_list )

										} // endif ( !isset($expertise_descendant_expertise_list) )

									// Format values

										if ( $expertise_descendant_expertise_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$expertise_descendant_expertise = uamswp_fad_schema_expertise(
												$expertise_descendant_expertise_list, // List of IDs of the area of expertise items
												'', // string // Required // Page or fake subpage URL
												true, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
												'', // string // Required // Fake subpage slug
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ) // Nesting level within the main schema
											) ?? null;

											if ( isset($expertise_descendant_expertise) ) {

												$expertise_descendant_expertise_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$expertise_descendant_expertise_MedicalWebPage = $expertise_descendant_expertise['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $expertise_descendant_expertise_MedicalWebPage ) {

															$expertise_descendant_expertise_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$expertise_descendant_expertise_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// MedicalEntity and subtypes

													$expertise_descendant_expertise_MedicalEntity = $expertise_descendant_expertise['MedicalEntity'] ?? null;
													$expertise_descendant_expertise_mainEntity = $expertise_descendant_expertise_MedicalEntity; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($expertise_descendant_expertise_MedicalEntity) ) { $expertise_descendant_expertise_about[] = $expertise_descendant_expertise_MedicalEntity; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $expertise_descendant_expertise_MedicalEntity ) {

														// Get URLs for significantLink property

															$expertise_descendant_expertise_MedicalEntity_significantLink = uamswp_fad_schema_property_values(
																$expertise_descendant_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$expertise_descendant_expertise_MedicalEntity_keywords = uamswp_fad_schema_property_values(
																$expertise_descendant_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Related areas of expertise

								// List of properties that reference areas of expertise

									$expertise_related_expertise_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_related_expertise_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_related_expertise_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related areas of expertise

										if ( !isset($expertise_related_expertise_list) ) {

											$expertise_related_expertise_list = get_field( 'expertise_associated', $entity ) ?? array();

											// Clean up the array

												$expertise_related_expertise_list = $expertise_related_expertise_list ? array_filter($expertise_related_expertise_list) : array();
												$expertise_related_expertise_list = $expertise_related_expertise_list ? array_values($expertise_related_expertise_list) : array();

										}

									// Format values

										if ( $expertise_related_expertise_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$expertise_related_expertise = uamswp_fad_schema_expertise(
												$expertise_related_expertise_list, // List of IDs of the area of expertise items
												'', // string // Required // Page or fake subpage URL
												true, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
												'', // string // Required // Fake subpage slug
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ) // Nesting level within the main schema
											) ?? null;

											if ( isset($expertise_related_expertise) ) {

												$expertise_related_expertise_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$expertise_related_expertise_MedicalWebPage = $expertise_related_expertise['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $expertise_related_expertise_MedicalWebPage ) {

															$expertise_related_expertise_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$expertise_related_expertise_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// MedicalEntity and subtypes

													$expertise_related_expertise_MedicalEntity = $expertise_related_expertise['MedicalEntity'] ?? null;
													$expertise_related_expertise_mainEntity = $expertise_related_expertise_MedicalEntity; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($expertise_related_expertise_MedicalEntity) ) { $expertise_related_expertise_about[] = $expertise_related_expertise_MedicalEntity; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $expertise_related_expertise_MedicalEntity ) {

														// Get URLs for significantLink property

															$expertise_related_expertise_MedicalEntity_significantLink = uamswp_fad_schema_property_values(
																$expertise_related_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$expertise_related_expertise_MedicalEntity_keywords = uamswp_fad_schema_property_values(
																$expertise_related_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated clinical resources

								// List of properties that reference clinical resources

									$expertise_clinical_resource_common = array(
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_clinical_resource_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_clinical_resource_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related clinical resources

										if ( !isset($expertise_clinical_resource_list) ) {

											$expertise_clinical_resource_list = get_field( 'expertise_clinical_resources', $entity ) ?? array();

										}

										if ( !isset($expertise_clinical_resource_list_max) ) {

											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
											$expertise_clinical_resource_list_max = $clinical_resource_posts_per_page_section;

										}

									// Format values

										if ( $expertise_clinical_resource_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_clinical_resource') ) {

												$expertise_clinical_resource = uamswp_fad_schema_clinical_resource(
													$expertise_clinical_resource_list, // List of IDs of the clinical resource items
													$expertise_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$expertise_clinical_resource = null;

											}

											if ( isset($expertise_clinical_resource) ) {

												$expertise_clinical_resource_about = array(); // Base array for all major entities of the ontology type's MedicalWebPages

												// MedicalWebPage

													$expertise_clinical_resource_MedicalWebPage = $expertise_clinical_resource['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $expertise_clinical_resource_MedicalWebPage ) {

															$expertise_clinical_resource_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$expertise_clinical_resource_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// CreativeWork and subtypes

													$expertise_clinical_resource_CreativeWork = $expertise_clinical_resource['CreativeWork'] ?? null;
													$expertise_clinical_resource_mainEntity = $expertise_clinical_resource_CreativeWork; // item array for the main entity of the ontology type's MedicalWebPages
													if ( isset($expertise_clinical_resource_CreativeWork) ) { $expertise_clinical_resource_about[] = $expertise_clinical_resource_CreativeWork; } // Add to the list of all major entities of the ontology type's MedicalWebPages

													if ( $expertise_clinical_resource_CreativeWork ) {

														// Get URLs for significantLink property

															$expertise_clinical_resource_CreativeWork_significantLink = uamswp_fad_schema_property_values(
																$expertise_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$expertise_clinical_resource_CreativeWork_keywords = uamswp_fad_schema_property_values(
																$expertise_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated conditions

								// List of properties that reference conditions

									$expertise_condition_common = array(
										'mentions'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_condition_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_condition_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related conditions

										if ( !isset($expertise_condition_list) ) {

											$expertise_condition_list = get_field( 'expertise_conditions_cpt', $entity ) ?? array();

											// Clean up the array

												$expertise_condition_list = $expertise_condition_list ? array_filter($expertise_condition_list) : array();
												$expertise_condition_list = $expertise_condition_list ? array_values($expertise_condition_list) : array();

										}

									// Format values

										if ( $expertise_condition_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_condition') ) {

												$expertise_condition = uamswp_fad_schema_condition(
													$expertise_condition_list, // array // Required // List of IDs of the MedicalCondition items
													$expertise_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$MedicalCondition_i, // int // Optional // Iteration counter for condition-as-MedicalCondition
													$Service_i // int // Optional // Iteration counter for treatment-as-Service
												) ?? null;

											} else {

												$expertise_condition = null;

											}

											if (
												isset($expertise_condition)
												&&
												$expertise_condition
											) {

												// Get names for keywords property

													$expertise_condition_keywords = uamswp_fad_schema_property_values(
														$expertise_condition, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

							// Associated treatments and procedures

								// List of properties that reference treatments and procedures

									$expertise_treatment_common = array(
										'mentions'
									);

								if (
									(
										(
											isset($expertise_item_MedicalWebPage)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalWebPage_type]['properties'],
												$expertise_treatment_common
											)
										)
										||
										(
											isset($expertise_item_MedicalEntity)
											&&
											array_intersect(
												$expertise_properties_map[$MedicalEntity_type]['properties'],
												$expertise_treatment_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related treatments

										if ( !isset($expertise_treatment) ) {

											$expertise_treatment = get_field( 'expertise_treatments_cpt', $entity ) ?? array();

											// Clean up the array

												$expertise_treatment = $expertise_treatment ? array_filter($expertise_treatment) : array();
												$expertise_treatment = $expertise_treatment ? array_values($expertise_treatment) : array();

										}

									// Format values

										if ( $expertise_treatment ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$expertise_availableService = uamswp_fad_schema_treatment(
													$expertise_treatment, // array // Required // List of IDs of the service items
													$expertise_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												) ?? null;

											} else {

												$expertise_availableService = null;

											}

											if (
												isset($expertise_availableService)
												&&
												$expertise_availableService
											) {

												// Get names for keywords property

													$expertise_availableService_keywords = uamswp_fad_schema_property_values(
														$expertise_availableService, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

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
							 */

							if (
								(
									isset($expertise_item_MedicalWebPage)
									&&
									in_array(
										'name',
										$expertise_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									in_array(
										'name',
										$expertise_properties_map[$MedicalEntity_type]['properties']
									)
								)
							) {

								// Get values

									if ( !isset($expertise_name) ) {

										$expertise_name = uamswp_attr_conversion(get_the_title($entity)) ?? '';

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$expertise_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'name', // string // Required // Name of schema property
											$expertise_name, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// MedicalSpecialty (common use)

							// List of properties that reference the MedicalSpecialty enumeration

								$expertise_MedicalSpecialty_common = array(
									'relevantSpecialty',
									'specialty'
								);

							if (
								(
									isset($expertise_item_MedicalWebPage)
									&&
									array_intersect(
										$expertise_properties_map[$MedicalWebPage_type]['properties'],
										$expertise_MedicalSpecialty_common
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									array_intersect(
										$expertise_properties_map[$MedicalEntity_type]['properties'],
										$expertise_MedicalSpecialty_common
									)
								)
							) {

								// Get multi-select field value

									$expertise_MedicalSpecialty = get_field( 'schema_medicalspecialty_multiple', $entity ) ?? array();

								// Clean up multi-select field value array

									$expertise_MedicalSpecialty = array_filter($expertise_MedicalSpecialty);
									sort($expertise_MedicalSpecialty);
									$expertise_MedicalSpecialty = array_values($expertise_MedicalSpecialty);

									// If there is only one item, flatten the multi-dimensional array by one step

										uamswp_fad_flatten_multidimensional_array($expertise_MedicalSpecialty);

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
									isset($expertise_item_MedicalWebPage)
									&&
									in_array(
										'additionalType',
										$expertise_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									in_array(
										'additionalType',
										$expertise_properties_map[$MedicalEntity_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$expertise_additionalType = array();

									// Get level of the item within the area of expertise page hierarchy

										$expertise_level = 1 + count(
											get_ancestors(
												$entity, // $object_id  // int // Optional // The ID of the object // Default: 0
												'expertise', // $object_type // string // Optional // The type of object for which we'll be retrieving ancestors. Accepts a post type or a taxonomy name. // Default: ''
												'post_type' // $resource_type // string // Optional // Type of resource $object_type is. Accepts 'post_type' or 'taxonomy'. // Default: ''
											)
										) ?? '';

									// Set value relevant to level

										if ( $expertise_level ) {

											if ( $expertise_level == 1 ) {

												$expertise_additionalType[] = 'https://www.wikidata.org/wiki/Q930752'; // Wikidata entry for 'medical specialty'

											} else {

												$expertise_additionalType[] = 'https://www.wikidata.org/wiki/Q7632042'; // Wikidata entry for 'subspecialty'

											}

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'additionalType', // string // Required // Name of schema property
											$expertise_additionalType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'additionalType', // string // Required // Name of schema property
											$expertise_additionalType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// alternateName

							/**
							 * An alias for the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 */

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'alternateName',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'alternateName',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									if ( !isset($expertise_alternateName) ) {

										// Get repeater field value

											if ( !isset($expertise_alternateName_repeater) ) {

												$expertise_alternateName_repeater = get_field( 'expertise_alternate_names', $entity ) ?? array();

											}

										// Get item values

											if ( $expertise_alternateName_repeater ) {

												$expertise_alternateName = uamswp_fad_schema_alternatename(
													$expertise_alternateName_repeater, // array // Required // alternateName repeater field
													'alternate_text' // string // Optional // alternateName item field name
												);

											}

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$expertise_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$expertise_alternateName, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// citation [WIP]

							/**
							 * A citation or reference to another creative work, such as another publication,
							 * web page, scholarly article, etc.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 */

						// code

							/**
							 * A medical code for the entity, taken from a controlled vocabulary or ontology
							 * such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalCode
							 *
							 * Used on these types:
							 *
							 *      - MedicalEntity
							 */

							if (
								(
									isset($expertise_item_MedicalWebPage)
									&&
									in_array(
										'code',
										$expertise_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									in_array(
										'code',
										$expertise_properties_map[$MedicalEntity_type]['properties']
									)
								)
							) {

								// Get values

									// Code repeater

										if ( !isset($expertise_code_repeater) ) {

											$expertise_code_repeater = get_field( 'schema_medicalcode', $entity ) ?? array();

										}

									// Health Care Provider Taxonomy Code Set taxonomy field

										if ( !isset($expertise_nucc_array) ) {

											$expertise_nucc_array = get_field( 'schema_nucc_multiple', $entity ) ?? array();

										}

									// Get item values

										if (
											$expertise_code_repeater
											||
											$expertise_nucc_array
										) {

											$expertise_code = uamswp_fad_schema_code(
												'code', // enum('code', 'identifier') // Required // Schema property format to output
												( $expertise_code_repeater ?: array() ), // array // Optional // code repeater field
												( $expertise_nucc_array ?: array() ) // array // Optional // Health Care Provider Taxonomy Code Set taxonomy field
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'code', // string // Required // Name of schema property
											$expertise_code, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'code', // string // Required // Name of schema property
											$expertise_code, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// Get names for keywords property

									$expertise_code = $expertise_code ?? null;

									if ( $expertise_code ) {

										$expertise_code_keywords = uamswp_fad_schema_property_values(
											$expertise_code, // array // Required // Property values from which to extract specific values
											array( 'name', 'alternateName', 'codeValue' ) // mixed // Required // List of properties from which to collect values
										);

									}

									// Merge gender keywords value into keywords

										$expertise_code_keywords = $expertise_code_keywords ?? null;

										if ( $expertise_code_keywords ) {

											$expertise_keywords = uamswp_fad_schema_merge_values(
												$expertise_keywords, // mixed // Required // Initial schema item property value
												$expertise_code_keywords // mixed // Required // Incoming schema item property value
											);

										}

							}

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

						// guideline [WIP]

							/**
							 * A medical guideline related to this entity.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicalGuideline
							 *
							 * Used on these types:
							 *
							 *      - MedicalEntity
							 */

						// hasPart [WIP]

							/**
							 * Indicates an item or CreativeWork that is part of this item, or CreativeWork
							 * (in some sense).
							 *
							 * Inverse-property: isPartOf
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 *
							 * Sub-properties:
							 *
							 *      - containsSeason
							 *      - episode
							 *      - tocEntry
							 */

						// image

							/**
							 * An image of the item. This can be a URL or a fully described ImageObject.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ImageObject
							 *      - URL
							 */

							if (
								(
									isset($expertise_item_MedicalWebPage)
									&&
									in_array(
										'image',
										$expertise_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									in_array(
										'image',
										$expertise_properties_map[$MedicalEntity_type]['properties']
									)
								)
							) {

								// Get values

									if ( !isset($expertise_image) ) {

										// Get featured image ID

											if ( !$fpage_query ) {

												/* Overview page */

												$expertise_image_id = get_field( '_thumbnail_id', $entity ) ?? '';

											} elseif ( $current_fpage == 'providers' ) {

												/* Fake subpage for related providers */

												$expertise_image_id = get_field( 'expertise_providers_fpage_featured_image', $entity ) ?? '';

											} elseif ( $current_fpage == 'locations' ) {

												/* Fake subpage for related locations */

												$expertise_image_id = get_field( 'expertise_locations_fpage_featured_image', $entity ) ?? '';

											} elseif ( $current_fpage == 'specialties' ) {

												/* Fake subpage for descendant areas of expertise */

												$expertise_image_id = get_field( 'expertise_descendant_fpage_featured_image', $entity ) ?? '';

											} elseif ( $current_fpage == 'related' ) {

												/* Fake subpage for related areas of expertise */

												$expertise_image_id = get_field( 'expertise_associated_fpage_featured_image', $entity ) ?? '';

											} elseif ( $current_fpage == 'resources' ) {

												/* Fake subpage for related clinical resources */

												$expertise_image_id = get_field( 'expertise_clinical_resources_fpage_featured_image', $entity ) ?? '';

											}

										// Create ImageObject values array

											if ( $expertise_image_id ) {

												$expertise_image = uamswp_fad_schema_imageobject_thumbnails(
													$expertise_url, // URL of entity with which the image is associated
													( $nesting_level + 1 ), // Nesting level within the main schema
													'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
													'Image', // Base fragment identifier
													$expertise_image_id, // ID of image to use for 1:1 aspect ratio
													0, // ID of image to use for 3:4 aspect ratio
													$expertise_image_id, // ID of image to use for 4:3 aspect ratio
													$expertise_image_id, // ID of image to use for 16:9 aspect ratio
													0 // ID of image to use for full image
												) ?? array();

											}

										// Clean up values array

											if (
												is_array($expertise_image)
												&&
												!empty($expertise_image)
											) {

												$expertise_image = array_filter($expertise_image);
												$expertise_image = array_unique( $expertise_image, SORT_REGULAR );
												$expertise_image = array_values($expertise_image);

												// If there is only one item, flatten the multi-dimensional array by one step

													uamswp_fad_flatten_multidimensional_array( $expertise_image );

											}

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$expertise_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$expertise_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// mainContentOfPage

							/**
							 * Indicates if this web page element is the main subject of the page.
							 *
							 * Values expected to be one of these types:
							 *
							 *     - WebPageElement
							 *
							 * Used on these types:
							 *
							 *      - WebPage
							 */

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'mainContentOfPage',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'mainContentOfPage',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									$expertise_mainContentOfPage = array(
										'@type' => 'WebPageElement',
										'cssSelector' => '.expertise-item'
									);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$expertise_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$expertise_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// medicineSystem

							/**
							 * The system of medicine that includes this MedicalEntity
							 * (e.g., 'evidence-based,' 'homeopathic,' 'chiropractic').
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MedicineSystem
							 */

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'medicineSystem',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'medicineSystem',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Get select field value

										$expertise_medicineSystem_select = get_field( 'schema_medicinesystem', $entity ) ?? array();

									// Add each item to the list array

										$expertise_medicineSystem = uamswp_fad_schema_medicinesystem(
											$expertise_medicineSystem_select // array of MedicineSystem values
										);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'medicineSystem', // string // Required // Name of schema property
											$expertise_medicineSystem, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'medicineSystem', // string // Required // Name of schema property
											$expertise_medicineSystem, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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

							/*

								Use marketing landing page header background image, if it exists.

							*/

						// recognizingAuthority [WIP]

							/**
								* If applicable, the organization that officially recognizes this entity as part
								* of its endorsed system of medicine.
								*
								* Values expected to be one of these types:
								*
								*      - Organization
								*
								* Used on these types:
								*
								*      - MedicalEntity
								*/

						// relevantSpecialty

							/**
								* If applicable, a medical specialty in which this entity is relevant.
								*
								* Values expected to be one of these types:
								*
								*      - MedicalSpecialty (enumeration type)
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'relevantSpecialty',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'relevantSpecialty',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								$expertise_MedicalSpecialty = $expertise_MedicalSpecialty ?? null;

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'relevantSpecialty', // string // Required // Name of schema property
											$expertise_MedicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'relevantSpecialty', // string // Required // Name of schema property
											$expertise_MedicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// sameAs

							/**
								* URL of a reference Web page that unambiguously indicates the item's identity
								* (e.g., the URL of the item's Wikipedia page, Wikidata entry, or official
								* website).
								*
								* Values expected to be one of these types:
								*
								*      - URL
								*/

							if (
								(
									isset($expertise_item_MedicalWebPage)
									&&
									in_array(
										'sameAs',
										$expertise_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($expertise_item_MedicalEntity)
									&&
									in_array(
										'sameAs',
										$expertise_properties_map[$MedicalEntity_type]['properties']
									)
								)
							) {

								// Get values

									// Get repeater field value

										$expertise_sameAs_repeater = get_field( 'schema_sameas', $entity ) ?? array();

									// Add each row to the list array

										if ( $expertise_sameAs_repeater ) {

											$expertise_sameAs = uamswp_fad_schema_sameas(
												$expertise_sameAs_repeater, // sameAs repeater field
												'schema_sameas_url' // sameAs item field name
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$expertise_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$expertise_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

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
								*
								* Information from Google Search Central documentation
								* (https://developers.google.com/search/docs/appearance/structured-data/speakable):
								*
								*     The speakable schema.org property identifies sections within an article or
								*     webpage that are best suited for audio playback using text-to-speech (TTS).
								*     Adding markup allows search engines and other applications to identify content
								*     to read aloud on Google Assistant-enabled devices using TTS. Web pages with
								*     speakable structured data can use the Google Assistant to distribute the
								*     content through new channels and reach a wider base of users.
								*
								*     The Google Assistant uses speakable structured data to answer topical news
								*     queries on smart speaker devices. When users ask for news about a specific
								*     topic, the Google Assistant returns up to three articles from around the web
								*     and supports audio playback using TTS for sections in the article with
								*     speakable structured data. When the Google Assistant reads aloud a speakable
								*     section, it attributes the source and sends the full article URL to the user's
								*     mobile device through the Google Assistant app.
								*
								*     Technical guidelines
								*
								*         Follow these guidelines when implementing speakable markup for Google Assistant.
								*
								*              - Don't add speakable structured data to content that may sound confusing in
								*                voice-only and voice-forward situations, like datelines (location where the
								*                story was reported), photo captions, or source attributions.
								*
								*              - Rather than highlighting an entire article with speakable structured data,
								*                focus on key points. This allows listeners to get an idea of the story and not
								*                have the TTS readout cut off important details.
								*
								*     Content guidelines
								*
								*         Follow these guidelines when writing content that you intend to mark up with
								*         speakable structured data.
								*
								*              - Content indicated by speakable structured data must have concise headlines
								*                and/or summaries that provide users with comprehensible and useful information.
								*
								*              - If you include the top of the story in speakable structured data, we suggest
								*                that you rewrite the top of the story to break up information into individual
								*                sentences so that it reads more clearly for TTS.
								*
								*              - For optimal audio user experiences, we recommend around 20-30 seconds of
								*                content per section of speakable structured data, or roughly two to three
								*                sentences.
								*
								*     Structured data type definitions
								*
								*         Speakable is used by the Article or Webpage object. The full definition of
								*         speakable is available at schema.org/speakable. You must include the required
								*         properties for your content to be eligible for this feature.
								*
								*         The speakable property can be repeated an arbitrary number of times, with two
								*         kinds of possible content-locator values: CSS selectors and xPaths.
								*
								*         Use either cssSelector or xPath; don't use both.
								*/

							/*

								Identify all of the blocks on the page where there is a heading followed by
								paragraphs (written using complete sentences) — or just the paragraphs — where
								the combined text will never exceed 550 characters.

								Wrap those text elements in an element (e.g., div) that is exclusive to those
								text elements if there is not already a wrapping element exclusive to those
								text elements.

								If the text elements in those blocks have a wrapping element exclusive to them,
								add a unique [id] element to that wrapping element if it doesn't already have
								one.

							*/

						// specialty

							/**
								* One of the domain specialities to which this web page's content applies.
								*
								* Values expected to be one of these types:
								*
								*      - Specialty (enumeration type with no enumeration members)
								*             - MedicalSpecialty (enumeration type)
								*
								* Used on these types:
								*
								*      - WebPage
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'specialty',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'specialty',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								$expertise_MedicalSpecialty = $expertise_MedicalSpecialty ?? null;

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'specialty', // string // Required // Name of schema property
											$expertise_MedicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'specialty', // string // Required // Name of schema property
											$expertise_MedicalSpecialty, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

						// study [WIP]

							/**
								* A medical study or trial related to this entity.
								*
								* Values expected to be one of these types:
								*
								*      - MedicalStudy
								*
								* Used on these types:
								*
								*      - MedicalEntity
								*/

						// text [WIP]

							/**
								* The textual content of this CreativeWork.
								*
								* Values expected to be one of these types:
								*
								*      - Text
								*
								* Used on these types:
								*
								*      - CreativeWork
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

						// video [WIP]

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

						// mentions

							/**
								* Indicates that the CreativeWork contains a reference to, but is not necessarily
								* about a concept.
								*
								* Values expected to be one of these types:
								*
								*      - Thing
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'mentions',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'mentions',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$expertise_mentions = array();

									// Merge in related providers (MedicalBusiness) value

										$expertise_provider_MedicalBusiness = $expertise_provider_MedicalBusiness ?? null;

										if ( $expertise_provider_MedicalBusiness ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_provider_MedicalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge related providers (MedicalBusiness) significantLink value into significantLink

											$expertise_provider_MedicalBusiness_significantLink = $expertise_provider_MedicalBusiness_significantLink ?? null;

											if ( $expertise_provider_MedicalBusiness_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_provider_MedicalBusiness_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge related providers (MedicalBusiness) keywords value into keywords

											$expertise_provider_MedicalBusiness_keywords = $expertise_provider_MedicalBusiness_keywords ?? null;

											if ( $expertise_provider_MedicalBusiness_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_provider_MedicalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related providers (Person) value

										$expertise_provider_Person = $expertise_provider_Person ?? null;

										if ( $expertise_provider_Person ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_provider_Person // mixed // Required // Incoming schema item property value
											);

										}

										// Merge providers (Person) significantLink value into significantLink

											$expertise_provider_MedicalWebPage_significantLink = $expertise_provider_MedicalWebPage_significantLink ?? null;

											if ( $expertise_provider_MedicalWebPage_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_provider_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge providers (Person) keywords value into keywords

											$expertise_provider_Person_keywords = $expertise_provider_Person_keywords ?? null;

											if ( $expertise_provider_Person_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_provider_Person_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related locations value

										$expertise_location_LocalBusiness = $expertise_location_LocalBusiness ?? null;

										if ( $expertise_location_LocalBusiness ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_location_LocalBusiness // mixed // Required // Incoming schema item property value
											);

										}

										// Merge location significantLink value into significantLink

											$expertise_location_MedicalWebPage_significantLink = $expertise_location_MedicalWebPage_significantLink ?? null;

											if ( $expertise_location_MedicalWebPage_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_location_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge location keywords value into keywords

											$expertise_location_LocalBusiness_keywords = $expertise_location_LocalBusiness_keywords ?? null;

											if ( $expertise_location_LocalBusiness_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_location_LocalBusiness_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in descendant areas of expertise value

										$expertise_descendant_expertise_MedicalEntity = $expertise_descendant_expertise_MedicalEntity ?? null;

										if ( $expertise_descendant_expertise_MedicalEntity ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_descendant_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge areas of expertise significantLink value into significantLink

											$expertise_descendant_expertise_MedicalWebPage_significantLink = $expertise_descendant_expertise_MedicalWebPage_significantLink ?? null;

											if ( $expertise_descendant_expertise_MedicalWebPage_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_descendant_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge areas of expertise keywords value into keywords

											$expertise_descendant_expertise_MedicalEntity_keywords = $expertise_descendant_expertise_MedicalEntity_keywords ?? null;

											if ( $expertise_descendant_expertise_MedicalEntity_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_descendant_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related areas of expertise value

										$expertise_related_expertise_MedicalEntity = $expertise_related_expertise_MedicalEntity ?? null;

										if ( $expertise_related_expertise_MedicalEntity ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_related_expertise_MedicalEntity // mixed // Required // Incoming schema item property value
											);

										}

										// Merge areas of expertise significantLink value into significantLink

											$expertise_related_expertise_MedicalWebPage_significantLink = $expertise_related_expertise_MedicalWebPage_significantLink ?? null;

											if ( $expertise_related_expertise_MedicalWebPage_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_related_expertise_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge areas of expertise keywords value into keywords

											$expertise_related_expertise_MedicalEntity_keywords = $expertise_related_expertise_MedicalEntity_keywords ?? null;

											if ( $expertise_related_expertise_MedicalEntity_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_related_expertise_MedicalEntity_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related clinical resources value

										$expertise_clinical_resource_CreativeWork = $expertise_clinical_resource_CreativeWork ?? null;

										if ( $expertise_clinical_resource_CreativeWork ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_clinical_resource_CreativeWork // mixed // Required // Incoming schema item property value
											);

										}

										// Merge clinical resources significantLink value into significantLink

											$expertise_clinical_resource_MedicalWebPage_significantLink = $expertise_clinical_resource_MedicalWebPage_significantLink ?? null;

											if ( $expertise_clinical_resource_MedicalWebPage_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_clinical_resource_MedicalWebPage_significantLink // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related conditions value

										$expertise_condition = $expertise_condition ?? null;

										if ( $expertise_condition ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_condition // mixed // Required // Incoming schema item property value
											);

										}

										// Merge conditions significantLink value into significantLink

											$expertise_condition_significantLink = $expertise_condition_significantLink ?? null;

											if ( $expertise_condition_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_condition_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge conditions keywords value into keywords

											$expertise_condition_keywords = $expertise_condition_keywords ?? null;

											if ( $expertise_condition_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_condition_keywords // mixed // Required // Incoming schema item property value
												);

											}

									// Merge in related treatments value

										$expertise_availableService = $expertise_availableService ?? null;

										if ( $expertise_availableService ) {

											$expertise_mentions = uamswp_fad_schema_merge_values(
												$expertise_mentions, // mixed // Required // Initial schema item property value
												$expertise_availableService // mixed // Required // Incoming schema item property value
											);

										}

										// Merge availableService significantLink value into significantLink

											$expertise_availableService_significantLink = $expertise_availableService_significantLink ?? null;

											if ( $expertise_availableService_significantLink ) {

												$expertise_significantLink = uamswp_fad_schema_merge_values(
													$expertise_significantLink, // mixed // Required // Initial schema item property value
													$expertise_availableService_significantLink // mixed // Required // Incoming schema item property value
												);

											}

										// Merge availableService keywords value into keywords

											$expertise_availableService_keywords = $expertise_availableService_keywords ?? null;

											if ( $expertise_availableService_keywords ) {

												$expertise_keywords = uamswp_fad_schema_merge_values(
													$expertise_keywords, // mixed // Required // Initial schema item property value
													$expertise_availableService_keywords // mixed // Required // Incoming schema item property value
												);

											}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$expertise_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'mentions', // string // Required // Name of schema property
											$expertise_mentions, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'relatedLink',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'relatedLink',
											$expertise_properties_map[$MedicalEntity_type]['properties']
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
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'relatedLink', // string // Required // Name of schema property
											$expertise_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'relatedLink', // string // Required // Name of schema property
											$expertise_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'significantLink',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'significantLink',
											$expertise_properties_map[$MedicalEntity_type]['properties']
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
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$expertise_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'significantLink', // string // Required // Name of schema property
											$expertise_significantLink, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
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
								*/

							if (
								(
									(
										isset($expertise_item_MedicalWebPage)
										&&
										in_array(
											'keywords',
											$expertise_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($expertise_item_MedicalEntity)
										&&
										in_array(
											'keywords',
											$expertise_properties_map[$MedicalEntity_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$expertise_keywords = $expertise_keywords ?? array();

									// Common values

										$expertise_keywords[] = 'area of expertise';
										$expertise_keywords[] = 'specialty';
										$expertise_keywords[] = 'specialization';
										$expertise_keywords[] = 'type of care';

									// Clean up list array

										if ( $expertise_keywords ) {

											$expertise_keywords = array_filter($expertise_keywords);
											$expertise_keywords = array_unique( $expertise_keywords, SORT_REGULAR );
											$expertise_keywords = array_values($expertise_keywords);
											uamswp_fad_flatten_multidimensional_array($expertise_keywords);

											if ( is_array($expertise_keywords) ) {

												sort( $expertise_keywords, SORT_NATURAL | SORT_FLAG_CASE );

											}

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$expertise_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// MedicalEntity

										uamswp_fad_schema_add_to_item_values(
											$MedicalEntity_type, // string // Required // The @type value for the schema item
											$expertise_item_MedicalEntity, // array // Required // The list array for the schema item to which to add the property value
											'keywords', // string // Required // Name of schema property
											$expertise_keywords, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$expertise_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

							}

					// Sort and combine the arrays

						if ( isset($expertise_item_MedicalWebPage) ) {

							ksort( $expertise_item_MedicalWebPage, SORT_NATURAL | SORT_FLAG_CASE );
							$expertise_item['MedicalWebPage'] = $expertise_item_MedicalWebPage;

						}

						if ( isset($expertise_item_MedicalEntity) ) {

							ksort( $expertise_item_MedicalEntity, SORT_NATURAL | SORT_FLAG_CASE );
							$expertise_item['MedicalEntity'] = $expertise_item_MedicalEntity;

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity . ( $current_fpage ? '_' . $current_fpage : '' ) . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
							$expertise_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to lists of areas of expertise

						// Add to list of MedicalWebPage items

							if (
								isset($expertise_item['MedicalWebPage'])
								&&
								!empty($expertise_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $expertise_item['MedicalWebPage'];

							}

						// Add to list of MedicalEntity items

							if (
								isset($expertise_item['MedicalEntity'])
								&&
								!empty($expertise_item['MedicalEntity'])
							) {

								$MedicalEntity_list[] = $expertise_item['MedicalEntity'];

							}

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list arrays

			// MedicalWebPage

				$MedicalWebPage_list = array_filter($MedicalWebPage_list);
				$MedicalWebPage_list = array_values($MedicalWebPage_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalWebPage_list);

			// MedicalEntity

				$MedicalEntity_list = array_filter($MedicalEntity_list);
				$MedicalEntity_list = array_values($MedicalEntity_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalEntity_list);

		// Combine lists for return

			// MedicalWebPage

				if ( $MedicalWebPage_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($expertise_list['MedicalWebPage'])
							&&
							!empty($expertise_list['MedicalWebPage'])
						) {

							$expertise_list['MedicalWebPage'] = array_is_list($expertise_list['MedicalWebPage']) ? $expertise_list['MedicalWebPage'] : array($expertise_list['MedicalWebPage']);

						}

					$expertise_list['MedicalWebPage'] = $MedicalWebPage_list;

				}

			// MedicalEntity

				if ( $MedicalEntity_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($expertise_list['MedicalEntity'])
							&&
							!empty($expertise_list['MedicalEntity'])
						) {

							$expertise_list['MedicalEntity'] = array_is_list($expertise_list['MedicalEntity']) ? $expertise_list['MedicalEntity'] : array($expertise_list['MedicalEntity']);

						}

					$expertise_list['MedicalEntity'] = $MedicalEntity_list;

				}

	} // endif ( !empty($repeater) )

	return $expertise_list;

}