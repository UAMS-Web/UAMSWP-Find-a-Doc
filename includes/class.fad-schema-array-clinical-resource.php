<?php

/**
 * Functions for Schema.org Schema and Google Local Business structured data
 *
 * Generate schema array of Clinical Resource ontology page type (MedicalWebPage; CreativeWork)
 */

function uamswp_fad_schema_clinical_resource(
	array $repeater, // List of IDs of the clinical resource items
	string $page_url, // Page URL
	array &$node_identifier_list = array(), // array // Optional // List of node identifiers (@id) already defined in the schema
	int $nesting_level = 1, // Nesting level within the main schema
	int $MedicalWebPage_i = 1, // Iteration counter for clinical resource-as-MedicalWebPage
	int $CreativeWork_i = 1, // Iteration counter for clinical resource-as-CreativeWork
	array $clinical_resource_fields = array(), // Pre-existing field values array so duplicate calls can be avoided
	array $MedicalWebPage_list = array(), // Pre-existing list array for clinical resource-as-MedicalWebPage to which to add additional items
	array $CreativeWork_list = array(), // Pre-existing list array for clinical resource-as-CreativeWork to which to add additional items
	array $clinical_resource_list = array() // Pre-existing list array for combined clinical resource schema to which to add additional items
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

				$clinical_resource_valid_types = array(
					'Article',
					'CreativeWork',
					'ImageObject',
					'VideoObject'
				);

			// List of Schema.org types for which to get the subtypes

				$clinical_resource_valid_types_plus_subtypes = array(
					'DigitalDocument',
					'MedicalWebPage'
				);

			// Base array for schema.org type URLs

				$clinical_resource_valid_types_url = array();

			// Get a list of schema.org subtypes and URLs

				uamswp_fad_schema_subtypes_and_urls(
					$clinical_resource_valid_types, // array // Required // List of Schema.org types for which to not get the subtypes
					$clinical_resource_valid_types_plus_subtypes, // array // Optional // List of Schema.org types for which to get the subtypes
					$clinical_resource_valid_types_url // string|array // Optional // Pre-existing list of schema.org URLs to which to add additional items
				);

		// List of valid properties for each type

			// Base array

				$clinical_resource_properties_map = array();

			// Get list of valid properties from Schema.org type list

				foreach ( $clinical_resource_valid_types as $item ) {

					$clinical_resource_properties_map[$item]['properties'] = $schema_org_types[$item]['properties'] ?? array();
					$clinical_resource_properties_map[$item]['properties'] = is_array($clinical_resource_properties_map[$item]['properties']) ? $clinical_resource_properties_map[$item]['properties'] : array($clinical_resource_properties_map[$item]['properties']);

				}

		// Loop through each clinical resource to add values

			foreach ( $repeater as $entity ) {

				if ( !$entity ) {

					continue;

				}

				// Retrieve the value of the item transient

					uamswp_fad_get_transient(
						'item_' . $entity . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
						$clinical_resource_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
						__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
					);

				if (
					!empty( $clinical_resource_item )
					&&
					(
						(
							isset($clinical_resource_item['MedicalWebPage'])
							&&
							!empty($clinical_resource_item['MedicalWebPage'])
						)
						||
						(
							isset($clinical_resource_item['CreativeWork'])
							&&
							!empty($clinical_resource_item['CreativeWork'])
						)
					)
				) {

					/**
					 * The transient exists.
					 * Return the variable.
					 */

					// Add to lists of clinical resources

						// Add to list of MedicalWebPage items

							if (
								isset($clinical_resource_item['MedicalWebPage'])
								&&
								!empty($clinical_resource_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $clinical_resource_item['MedicalWebPage'];

							}

						// Add to list of CreativeWork items

							if (
								isset($clinical_resource_item['CreativeWork'])
								&&
								!empty($clinical_resource_item['CreativeWork'])
							) {

								$clinical_resource_list[] = $clinical_resource_item['CreativeWork'];

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

						$clinical_resource_item = array(); // Base array
						$clinical_resource_item_MedicalWebPage = in_array( 'MedicalWebPage', $clinical_resource_valid_types ) ? array() : null; // Base MedicalWebPage array
						$clinical_resource_item_CreativeWork = in_array( 'CreativeWork', $clinical_resource_valid_types ) ? array() : null; // Base CreativeWork array
						$clinical_resource_item_CreativeWork_common = in_array( 'CreativeWork', $clinical_resource_valid_types ) ? array() : null; // Base array for properties common to all CreativeWork items
						$clinical_resource_abstract = '';
						$clinical_resource_additionalType = '';
						$clinical_resource_alternateName = '';
						$clinical_resource_articleBody = '';
						$clinical_resource_articleBody_count = '';
						$clinical_resource_asset = array();
						$clinical_resource_asset_alternateName = '';
						$clinical_resource_asset_bitrate = '';
						$clinical_resource_asset_caption_query = '';
						$clinical_resource_asset_contentSize = '';
						$clinical_resource_asset_contentUrl = '';
						$clinical_resource_asset_datePublished = '';
						$clinical_resource_asset_description = '';
						$clinical_resource_asset_duration = '';
						$clinical_resource_asset_embedUrl = '';
						$clinical_resource_asset_encodingFormat = '';
						$clinical_resource_asset_height = '';
						$clinical_resource_asset_id = array();
						$clinical_resource_asset_info = '';
						$clinical_resource_asset_name = '';
						$clinical_resource_asset_nodeid = ''
						$clinical_resource_asset_parsed = '';
						$clinical_resource_asset_path = '';
						$clinical_resource_asset_resized = null;
						$clinical_resource_asset_sameAs = '';
						$clinical_resource_asset_thumbnail = '';
						$clinical_resource_asset_thumbnailUrl = '';
						$clinical_resource_asset_url = '';
						$clinical_resource_asset_videoFrameSize = '';
						$clinical_resource_asset_videoQuality = '';
						$clinical_resource_asset_width = '';
						$clinical_resource_contentUrl = '';
						$clinical_resource_creator = '';
						$clinical_resource_dateModified = '';
						$clinical_resource_datePublished = '';
						$clinical_resource_description = '';
						$clinical_resource_description_ref = '';
						$clinical_resource_duration = '';
						$clinical_resource_embeddedTextCaption = '';
						$clinical_resource_embeddedTextCaption_count = '';
						$clinical_resource_embedUrl = '';
						$clinical_resource_expertise = null;
						$clinical_resource_featured_image_1_1_size = '';
						$clinical_resource_featured_image_1_1_src = array();
						$clinical_resource_featured_image_1_1_url = '';
						$clinical_resource_featured_image_1_1_width = '';
						$clinical_resource_featured_image_16_9_height = '';
						$clinical_resource_featured_image_16_9_size = '';
						$clinical_resource_featured_image_16_9_src = array();
						$clinical_resource_featured_image_16_9_url = '';
						$clinical_resource_featured_image_16_9_width = '';
						$clinical_resource_featured_image_3_4_height = '';
						$clinical_resource_featured_image_3_4_size = '';
						$clinical_resource_featured_image_3_4_src = array();
						$clinical_resource_featured_image_3_4_url = '';
						$clinical_resource_featured_image_3_4_width = '';
						$clinical_resource_featured_image_4_3_height = '';
						$clinical_resource_featured_image_4_3_size = '';
						$clinical_resource_featured_image_4_3_src = array();
						$clinical_resource_featured_image_4_3_url = '';
						$clinical_resource_featured_image_4_3_width = '';
						$clinical_resource_featured_image_caption = '';
						$clinical_resource_featured_image_encodingFormat = '';
						$clinical_resource_featured_image_id = '';
						$clinical_resource_featured_image_ImageObject = array();
						$clinical_resource_featured_image_ImageObject_base = array();
						$clinical_resource_featured_image_square_encodingFormat = '';
						$clinical_resource_featured_image_square_id = '';
						$clinical_resource_hasDigitalDocumentPermission = '';
						$clinical_resource_headline = '';
						$clinical_resource_height = '';
						$clinical_resource_introduction = null;
						$clinical_resource_introduction_count = null;
						$clinical_resource_location = null;
						$CreativeWork_id = '';
						$CreativeWork_subtype = null;
						$CreativeWork_type = null;
						$clinical_resource_image = '';
						$clinical_resource_isAccessibleForFree = '';
						$clinical_resource_isPartOf = '';
						$clinical_resource_mainEntityOfPage = '';
						$clinical_resource_name = '';
						$clinical_resource_nci_query = '';
						$clinical_resource_related_clinical_resource = null;
						$clinical_resource_representativeOfPage = '';
						$clinical_resource_sameAs = '';
						$clinical_resource_sourceOrganization = '';
						$clinical_resource_speakable = '';
						$clinical_resource_subjectOf = '';
						$clinical_resource_syndication_org = null;
						$clinical_resource_syndication_org_name = null;
						$clinical_resource_syndication_query = '';
						$clinical_resource_syndication_URL = '';
						$clinical_resource_thumbnail = array();
						$clinical_resource_timeRequired = '';
						$clinical_resource_timeRequired_seconds = '';
						$clinical_resource_transcript = '';
						$clinical_resource_transcript_count = '';
						$clinical_resource_video = '';
						$clinical_resource_videoFrameSize = '';
						$clinical_resource_videoQuality = '';
						$clinical_resource_width = '';
						$clinical_resource_word_count = 0;
						$clinical_resource_wordCount = '';
						$clinical_resource_featured_image_1_1_height = '';
						$MedicalCondition_i = 1;
						$MedicalWebPage_type = null;
						$Service_i = 1;

						// Reused variables

							$clinical_resource_audience = $clinical_resource_audience ?? '';

					// Load variables from pre-existing field values array

						if (
							isset($clinical_resource_fields[$entity])
							&&
							!empty($clinical_resource_fields[$entity])
						) {

							foreach ( $clinical_resource_fields[$entity] as $key => $value ) {

								${$key} = $value; // Create a variable for each item in the array

							}

						}

					// Get ontology type

						if ( !isset($clinical_resource_ontology_type) ) {

							$clinical_resource_ontology_type = true;

						}

					// If the page is not an ontology type, skip to the next iteration

						if ( !$clinical_resource_ontology_type ) {

							continue;

						}

					// Fake subpage query and get fake subpage slug

						if (
							$clinical_resource_ontology_type
							&&
							$nesting_level == 0
						) {

							if ( !isset($clinical_resource_current_fpage) ) {

								$clinical_resource_current_fpage = get_query_var( 'fpage' ) ?? ''; // Fake subpage slug

							}

							if ( !isset($clinical_resource_fpage_query) ) {

								$clinical_resource_fpage_query = $clinical_resource_current_fpage ? true : false;

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
							 */

							// Get values

								if ( !isset($clinical_resource_url) ) {

									$clinical_resource_url = get_permalink($entity);
									$clinical_resource_url = $clinical_resource_url ? user_trailingslashit( $clinical_resource_url ) : '';

								}

							// Pass the values to common schema properties template part

								$schema_common_url = $clinical_resource_url;

							// Add to item values

								// MedicalWebPage

									if (
										isset($clinical_resource_item_MedicalWebPage)
										&&
										$clinical_resource_url
									) {

										$clinical_resource_item_MedicalWebPage['url'] = $clinical_resource_url;

									}

								// CreativeWork

									if (
										isset($clinical_resource_item_CreativeWork_common)
										&&
										$clinical_resource_url
									) {

										$clinical_resource_item_CreativeWork_common['url'] = $clinical_resource_url;

									}

						// @type

							// MedicalWebPage type

								if ( isset($clinical_resource_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_type = 'MedicalWebPage';

										// Add to item values

											if ( $MedicalWebPage_type ) {

												$clinical_resource_item_MedicalWebPage['@type'] = $MedicalWebPage_type;

											}

								}

							// CreativeWork type

								if ( isset($clinical_resource_item_CreativeWork) ) {

									// Get values

										// Base value

											$CreativeWork_type = 'CreativeWork';

										// Get resource type

											$clinical_resource_resource_type = get_field( 'clinical_resource_type', $entity )['value'] ?? '';

										// Get relevant schema type based on resource type

											$clinical_resource_resource_type_map = array(
												'text' => 'Article',
												'infographic' => 'ImageObject',
												'video' => 'VideoObject',
												'doc' => 'DigitalDocument'
											);

											if ( $clinical_resource_resource_type ) {

												$CreativeWork_type = $clinical_resource_resource_type_map[$clinical_resource_resource_type] ?? 'CreativeWork';

											}

											// Get relevant schema type based on DigitalDocument subtype

												if ( $CreativeWork_type == 'DigitalDocument' ) {

													// Get the field value

														$CreativeWork_subtype = get_field( 'clinical_resource_document_subtype', $entity ) ?? null;

												}

										// Add to item values

											if ( $CreativeWork_subtype ) {

												$clinical_resource_item_CreativeWork_common['@type'] = $CreativeWork_subtype;

											} elseif ( $CreativeWork_type ) {

												$clinical_resource_item_CreativeWork_common['@type'] = $CreativeWork_type;

											}

								}
						// name (common)

							// Post title

								$clinical_resource_post_title = get_the_title($entity) ?? '';

						// Main media asset(s) info (common)

							// List of properties that reference the main image asset

								$clinical_resource_main_image_common = array(
									'alternateName',
									'bitrate',
									'contentSize',
									'contentUrl',
									'datePublished',
									'description',
									'duration',
									'embedUrl',
									'encodingFormat',
									'height',
									'name',
									'sameAs',
									'thumbnail',
									'thumbnailUrl',
									'videoFrameSize',
									'videoQuality',
									'url',
									'videoFrameSize',
									'videoQuality',
									'width'
								);

							if (
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									array_intersect(
										$clinical_resource_properties_map[$CreativeWork_type]['properties'],
										$clinical_resource_main_image_common
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get main WordPress asset ID(s) and external media URL

									if ( $clinical_resource_resource_type == 'infographic' ) {

										// Infographic image ID

											$clinical_resource_asset_id = get_field( 'clinical_resource_infographic', $entity ) ?? $clinical_resource_asset_id;

									} elseif ($clinical_resource_resource_type == 'video' ) {

										// Video URL

											$clinical_resource_video = get_field( 'clinical_resource_video', $entity ) ?? $clinical_resource_video;

									} elseif ($clinical_resource_resource_type == 'doc' ) {

										// Document ID(s)

											$clinical_resource_asset_id = get_field( 'clinical_resource_document', $entity ) ?? $clinical_resource_asset_id;

									}

									// Format the WordPress asset ID value(s)

										if ( $clinical_resource_asset_id ) {

											if (
												$clinical_resource_asset_id
												&&
												!is_array($clinical_resource_asset_id)
											) {

												// Convert the value to an array

													$clinical_resource_asset_id = array($clinical_resource_asset_id);

											} elseif ( $clinical_resource_asset_id )  {

												// Clean up the array

													$clinical_resource_asset_id = array_unique($clinical_resource_asset_id);
													$clinical_resource_asset_id = array_filter($clinical_resource_asset_id);
													$clinical_resource_asset_id = $clinical_resource_asset_id ? array_values($clinical_resource_asset_id) : array();
											}

										}

								// Get attributes of the main media asset(s)

									// Base asset info array

										$clinical_resource_asset = array();

									// Base asset item array

										$clinical_resource_asset_item = array(
											'i' => $clinical_resource_asset_i, // int // Instance of CreativeWork item in the list
											'@id' => $clinical_resource_asset_nodeid, // int // Instance of CreativeWork item in the list
											'id' => $clinical_resource_asset_id,
											'alternateName' => $clinical_resource_asset_alternateName,
											'bitrate' => $clinical_resource_asset_bitrate,
											'caption_query' => $clinical_resource_asset_caption_query,
											'contentSize' => $clinical_resource_asset_contentSize,
											'contentUrl' => $clinical_resource_asset_contentUrl,
											'datePublished' => $clinical_resource_asset_datePublished,
											'description' => $clinical_resource_asset_description,
											'duration' => $clinical_resource_asset_duration,
											'embedUrl' => $clinical_resource_asset_embedUrl,
											'encodingFormat' => $clinical_resource_asset_encodingFormat,
											'height' => $clinical_resource_asset_height, // int // Media height in pixels
											'name' => $clinical_resource_asset_name, // string // The post title or linked media title
											'path' => $clinical_resource_asset_path, // string // The file path to where the attached file should be
											'resized' => $clinical_resource_asset_resized, // bool // Whether the image is a resized image
											'sameAs' => $clinical_resource_asset_sameAs,
											'thumbnail' => $clinical_resource_asset_thumbnail,
											'thumbnailUrl' => $clinical_resource_asset_thumbnailUrl,
											'url' => $clinical_resource_asset_url, // string // Attachment source URL
											'videoFrameSize' => $clinical_resource_asset_videoFrameSize,
											'videoQuality' => $clinical_resource_asset_videoQuality,
											'width' => $clinical_resource_asset_width // int // Media width in pixels
										);

									// WordPress assets

										if ( $clinical_resource_asset_id ) {

											foreach ( $clinical_resource_asset_id as $asset_id ) {

												if ( $asset_id ) {

													// Define/reset variables

														$asset_i = !isset($asset_i) ? 0 : $asset_i++;
														$asset_nodeid = $clinical_resource_url . '#' . $CreativeWork_type . '_' . $asset_i;
														$asset_item = $clinical_resource_asset_item; // Base asset item array
														$asset_info = $clinical_resource_asset_info;
														$asset_alternateName = $clinical_resource_asset_alternateName;
														$asset_bitrate = $clinical_resource_asset_bitrate;
														$asset_contentSize = $clinical_resource_asset_contentSize;
														$asset_contentUrl = $clinical_resource_asset_contentUrl;
														$asset_datePublished = $clinical_resource_asset_datePublished;
														$asset_description = $clinical_resource_asset_description;
														$asset_duration = $clinical_resource_asset_duration;
														$asset_embedUrl = $clinical_resource_asset_embedUrl;
														$asset_encodingFormat = $clinical_resource_asset_encodingFormat;
														$asset_height = $clinical_resource_asset_height;
														$asset_name = $clinical_resource_asset_name;
														$asset_path = $clinical_resource_asset_path;
														$asset_resized = $clinical_resource_asset_resized;
														$asset_sameAs = $clinical_resource_asset_sameAs;
														$asset_thumbnail = $clinical_resource_asset_thumbnail;
														$asset_thumbnailUrl = $clinical_resource_asset_thumbnailUrl;
														$asset_url = $clinical_resource_asset_url;
														$asset_videoFrameSize = $clinical_resource_asset_videoFrameSize;
														$asset_videoQuality = $clinical_resource_asset_videoQuality;
														$asset_width = $clinical_resource_asset_width;

													// Instance of CreativeWork item in the list

														$asset_item['i'] = $asset_i; // int

													// @id

														$asset_item['@id'] = $asset_nodeid; // int

													// ID

														$asset_item['id'] = $asset_id ?? $clinical_resource_asset_id; // int // The ID of the current item

													// name

														$asset_name = get_the_title(
															$post // int|WP_Post // optional // Post ID or WP_Post object. Default is global $post.
														) ?? $clinical_resource_post_title; // string // The post title
														$asset_item['name'] = $asset_name;

													// alternateName

														$asset_item['alternateName'] = ( $asset_name != $clinical_resource_post_title) ? $clinical_resource_post_title : $clinical_resource_asset_alternateName; // string // The post title

													// URL, width, height

														if ( $clinical_resource_resource_type == 'infographic' ) {

															// $clinical_resource_asset_info = wp_get_attachment_image_src( $asset_id, 'full' ) ?: $clinical_resource_asset_info;
															$asset_info = wp_get_attachment_image_src(
																$asset_id, // int // Required // Image attachment ID
																'full' // string|int[] // optional // Image size. Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default 'thumbnail'.
															) ?: $asset_info; // array|false // Array of image data; or boolean false if no image is available

															if ( $asset_info ) {

																// URL

																	$asset_item['url'] = $asset_info[0] ?? $asset_url; // string // Image source URL

																// Width

																	$asset_item['width'] = $asset_info[1] ?? $asset_width; // int // Image width in pixels

																// Height

																	$asset_item['height'] = $asset_info[2] ?? $asset_height; // int // Image height in pixels

																// Resized image query

																	$asset_item['resized'] = $asset_info[3] ?? $asset_resized; // bool // Whether the image is a resized image

															}

														} elseif ( $clinical_resource_resource_type == 'doc' ) {

															// URL

																$asset_attachment_url = wp_get_attachment_url(
																	$asset_id // int // optional // Attachment post ID. Defaults to global $post.
																) ?: null; // string|false // Attachment URL; otherwise false

																$asset_item['url'] = $asset_attachment_url ?: $asset_url;

														}

													// contentSize

														/**
														 * File size in (mega/kilo)bytes.
														 *
														 * Values expected to be one of these types:
														 *
														 *      - Text
														 */

														// Get the asset's file path

															/**
															 * This value is needed for the needed for the wp_filesize() function
															 */

															$asset_path = get_attached_file(
																$asset_id // int // Required // Attachment ID
															) ?: $asset_path; // string|false // The file path to where the attached file should be; otherwise false
															$asset_item['path'] = $asset_path;

														// Get the asset's file size in bytes

															if ( $asset_path ) {

																$asset_contentSize = wp_filesize(
																	$asset_path // string // required // Path to the file
																) ?: $asset_contentSize; // int // The size of the file in bytes, or 0 in the event of an error

															}

														// Convert the file size from the number of bytes to the largest unit the bytes will fit into

															if ( $asset_contentSize ) {

																$asset_item['contentSize'] = uamswp_size_format(
																	$clinical_resource_asset_contentSize, // int // required // Number of bytes
																	2 // int // optional // Precision of number of decimal places (Default: 0)
																) ?? $asset_contentSize; // string // Number string on success

															}

													// Encoding Format (mime type)

														$asset_item['encodingFormat'] = get_post_mime_type( $clinical_resource_asset_id ) ?: $clinical_resource_asset_encodingFormat; // e.g., 'image/jpeg'

													// bitrate [WIP]

														/*

															WIP: Determine if the bitrate value can be retrieved for the infographic
															and/or document assets.

														*/

													// contentUrl (file URL) [WIP]

														if ( $clinical_resource_resource_type == 'infographic' ) {

															$asset_item['contentUrl'] = $asset_info[0] ?? null; // string // Image source URL

														} elseif ( $clinical_resource_resource_type == 'doc' ) {

															$asset_item['contentUrl'] = $asset_attachment_url ?? null; // string // Document source URL

														}

													// Add item to asset info array

														$clinical_resource_asset[$asset_i] = $asset_item;

													// Create an item in the base CreativeWork array

														$clinical_resource_item_CreativeWork[$asset_i] = array();

												}

											}

										}

									// External assets

										// Video info

											if ( $clinical_resource_video ) {

												$asset_item = $clinical_resource_asset_item; // Base asset item array
												$asset_i = !isset($asset_i) ? 0 : $asset_i++;
												$asset_nodeid = $clinical_resource_url . '#' . $CreativeWork_type . '_' . $asset_i;

												// Instance of CreativeWork item in the list

													$asset_item['i'] = $asset_i; // int

												// @id

													$asset_item['@id'] = $asset_nodeid; // int

												// URL

													$asset_item['url'] = $clinical_resource_video;

												// Parse the URL and return its components

													$clinical_resource_asset_parsed = parse_url(
														$clinical_resource_video // string // required // The URL to parse
													);

														/*

															On seriously malformed URLs, parse_url() may return false.

															If the component parameter is omitted, an associative array is returned. At
															least one element will be present within the array. Potential keys within this
															array are:

																'scheme' (e.g. http)
																'host'
																'port'
																'user'
																'pass'
																'path'
																'query' (after the question mark ?)
																'fragment' (after the hashmark #)

															If the component parameter is specified, parse_url() returns a string (or an
															int, in the case of PHP_URL_PORT) instead of an array. If the requested
															component doesn't exist within the given URL, null will be returned. As of
															PHP 8.0.0, parse_url() distinguishes absent and empty queries and fragments:

																http://example.com/foo → query = null, fragment = null
																http://example.com/foo? → query = "",   fragment = null
																http://example.com/foo# → query = null, fragment = ""
																http://example.com/foo?# → query = "",   fragment = ""

															Previously all cases resulted in query and fragment being null.

															Note that control characters (cf. ctype_cntrl()) in the components are replaced
															with underscores (_).

														*/

													// Parse the query string into variables

														if ( isset($clinical_resource_asset_parsed['query']) ) {

															parse_str(
																$clinical_resource_asset_parsed['query'], // string // required // The input string
																$clinical_resource_asset_parsed['query'] // array // required // If the second parameter result is present, variables are stored in this variable as array elements instead
															); // No value is returned

														}

												if (
													isset($clinical_resource_asset_parsed['host'])
													&&
													(
														str_contains( $clinical_resource_asset_parsed['host'], 'youtube' )
														||
														str_contains( $clinical_resource_asset_parsed['host'], 'youtu.be' )
													)
												) {

													// If YouTube

														// Embed URL

															if (
																isset($clinical_resource_asset_parsed['query'])
																&&
																isset($clinical_resource_asset_parsed['query']['v'])
																&&
																$clinical_resource_asset_parsed['query']['v']
															) {

																// $clinical_resource_asset_embedUrl = 'https://www.youtube.com/embed/' . $clinical_resource_asset_parsed['query']['v'];
																$asset_item['embedUrl'] = 'https://www.youtube.com/embed/' . $clinical_resource_asset_parsed['query']['v'];

															}

														// Get info from video

															$clinical_resource_asset_info = uamswp_fad_youtube_info(
																$clinical_resource_video // string // required // YouTube video URL
															) ?? array();

															if ( $clinical_resource_asset_info ) {

																// Name (snippet.title)

																	// $clinical_resource_asset_name = $clinical_resource_asset_info['name'] ?? $clinical_resource_post_title;
																	$asset_name = $clinical_resource_asset_info['name'] ?? $clinical_resource_post_title;
																	$asset_item['name'] = $asset_name;

																// Thumbnail URL

																	// MaxRes Thumbnail URL, 1280x720 (snippet.thumbnails.maxres.url)

																		// $clinical_resource_asset_thumbnail = $clinical_resource_asset_info['HQthumbUrl'] ?? $clinical_resource_asset_thumbnail;
																		$asset_item['thumbnail'] = $clinical_resource_asset_info['HQthumbUrl'] ?? $clinical_resource_asset_thumbnail;

																	// Fallback value: High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																		if ( !$asset_item['thumbnail'] ) {

																			// $clinical_resource_asset_thumbnail = $clinical_resource_asset_info['thumbUrl'] ?? $clinical_resource_asset_thumbnail; // High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)
																			$asset_item['thumbnail'] = $clinical_resource_asset_info['thumbUrl'] ?? $clinical_resource_asset_thumbnail; // High Thumbnail URL, 480x360 (snippet.thumbnails.high.url)

																		}

																// Published date and time (snippet.publishedAt)

																	// $clinical_resource_asset_datePublished = $clinical_resource_asset_info['dateField'] ?? $clinical_resource_asset_datePublished;
																	$asset_item['datePublished'] = $clinical_resource_asset_info['dateField'] ?? $clinical_resource_asset_datePublished;

																// Duration (contentDetails.duration)

																	// $clinical_resource_asset_duration = $clinical_resource_asset_info['duration'] ?? $clinical_resource_asset_duration;
																	$asset_item['duration'] = $clinical_resource_asset_info['duration'] ?? $clinical_resource_asset_duration;

																// Description (snippet.description)

																	// $clinical_resource_asset_description = $clinical_resource_asset_info['description'] ?? $clinical_resource_asset_description;
																	$asset_item['description'] = $clinical_resource_asset_info['description'] ?? $clinical_resource_asset_description;

																// Whether captions are available for the video (contentDetails.caption)

																	// $clinical_resource_asset_caption_query = $clinical_resource_asset_info['captions_data'] ?? $clinical_resource_asset_caption_query;
																	$asset_item['caption_query'] = $clinical_resource_asset_info['captions_data'] ?? $clinical_resource_asset_caption_query;
																	// $clinical_resource_asset_caption_query = ( $clinical_resource_asset_caption_query == 'true' ) ? true : false;
																	$asset_item['caption_query'] = ( $asset_item['caption_query'] == 'true' ) ? true : false;

																// Video quality: high definition (hd) or standard definition (sd) (contentDetails.definition) [WIP]

																	/*

																		WIP: The current version of the function does not return this value.

																	*/

																// Frame size [WIP]

																	/*

																		WIP: The current version of the function does not return this value.

																	*/

																// bitrate [WIP]

																	/*

																		WIP: The current version of the function does not return this value.

																	*/

																// File URL (contentUrl) [WIP]

																	/*

																		WIP: The current version of the function does not return this value.

																	*/

															}

														// alternateName

															$asset_item['alternateName'] = ( $asset_name != $clinical_resource_post_title) ? $clinical_resource_post_title : $clinical_resource_asset_alternateName; // string // The post title

												} elseif (
													isset($clinical_resource_asset_parsed['host'])
													&&
													str_contains(
														$clinical_resource_asset_parsed['host'],
														'vimeo'
													)
												) {

													// If Vimeo [WIP]

														/*

															WIP: Get values from the Vimeo and then set the specific property values with
															those.

														*/

														// name [WIP]

															$asset_name = $clinical_resource_post_title;
															$asset_item['name'] = $asset_name;

														// alternateName

															$asset_item['alternateName'] = ( $asset_name != $clinical_resource_post_title) ? $clinical_resource_post_title : $clinical_resource_asset_alternateName; // string // The post title

														// Embed URL

															if (
																isset($clinical_resource_asset_parsed['path'])
																&&
																$clinical_resource_asset_parsed['path']
															) {

																// $clinical_resource_asset_embedUrl = 'https://player.vimeo.com/video/' . $clinical_resource_asset_parsed['path'];
																$asset_item['embedUrl'] = 'https://player.vimeo.com/video/' . $clinical_resource_asset_parsed['path'];

															}

												}

												// Add item to asset info array

													$clinical_resource_asset[$asset_i] = $asset_item;

												// Create an item in the base CreativeWork array

													$clinical_resource_item_CreativeWork[$asset_i] = array();

											}

							}

						// @id

							// MedicalWebPage

								if ( isset($clinical_resource_item_MedicalWebPage) ) {

									// Get values

										$MedicalWebPage_id = $clinical_resource_url . '#' . $MedicalWebPage_type;
										// $MedicalWebPage_id .= $MedicalWebPage_i;
										// $MedicalWebPage_i++;

									// Add to item values

										if ( $MedicalWebPage_id ) {

											$clinical_resource_item_MedicalWebPage['@id'] = $MedicalWebPage_id;
											$node_identifier_list[] = $clinical_resource_item_MedicalWebPage['@id']; // Add to the list of existing node identifiers

										}

								}

							// CreativeWork

								if (
									isset($clinical_resource_item_CreativeWork)
									&&
									isset($clinical_resource_asset)
									&&
									$clinical_resource_asset
								) {

									// Loop through the main media asset(s) info array to get the @id value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get @id value

													$asset_nodeid = $value['@id'] ?? null;

												// Add @id property/value to the asset's CreativeWork schema item

													if ( $asset_nodeid ) {

														$clinical_resource_item_CreativeWork[$key]['@id'] = $asset_nodeid;
														$node_identifier_list[] = $asset_nodeid; // Add to the list of existing node identifiers

													}

											}

										}

								}

						// Add common properties

							// Pass variables to template part

								$schema_common_item_MedicalWebPage = $clinical_resource_item_MedicalWebPage; // MedicalWebPage item array
								$schema_common_item_mainEntity = $clinical_resource_item_CreativeWork_common ?? null; // item array for the main entity of the MedicalWebPage
								$schema_common_item_about = $clinical_resource_item_CreativeWork_common ?? null; // all major entities of the MedicalWebPage

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
													$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											// CreativeWork

												// Loop through the base CreativeWork array and add the properties/values to each item

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															$key, // string // Required // Name of schema property
															$value, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

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
													$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
													$key, // string // Required // Name of schema property
													$value, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
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

											// CreativeWork

												// Loop through the base CreativeWork array and add the properties/values to each item

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															$key, // string // Required // Name of schema property
															$value, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

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

											// CreativeWork

												// Loop through the base CreativeWork array and add the properties/values to each item

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															$key, // string // Required // Name of schema property
															$value, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

									}

								}

						// Associated ontology items (e.g., providers, areas of expertise, clinical resources, conditions, treatments)

							// Associated providers

								// List of properties that reference associated providers

									$clinical_resource_provider_common = array(
										'about',
										'keywords',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_provider_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_provider_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										// Get list of associated providers

											if ( !isset($clinical_resource_provider_ids) ) {

												$clinical_resource_provider_ids = array();

												$providers = get_field( 'clinical_resource_providers', $entity );
												$page_id_temp = $page_id ?? null;
												$page_id = $entity;
												include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/provider.php' );
												$clinical_resource_provider_ids = $provider_ids;

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

											if ( $clinical_resource_provider_ids ) {

												$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

												if ( function_exists('uamswp_fad_schema_provider') ) {

													$clinical_resource_provider = uamswp_fad_schema_provider(
														$clinical_resource_provider_ids, // array // Required // List of IDs of the provider items
														$clinical_resource_url, // string // Required // Page URL
														$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
														($nesting_level + 1), // int // Optional // Nesting level within the main schema
														array( 'MedicalBusiness', 'MedicalWebPage', 'Person' ) // array // Optional // List of the schema types to output
													) ?? null;

												} else {

													$clinical_resource_provider = null;

												}

												if ( isset($clinical_resource_provider) ) {

													// Create a list of all major entities of the ontology type's MedicalWebPages

														$clinical_resource_provider_about = array(); // Base array

														foreach ( $clinical_resource_provider as $key => $value ) {

															if (
																$value
																&&
																$key != 'MedicalWebPage'
															) {

																$clinical_resource_provider_about = array_merge(
																	$clinical_resource_provider_about,
																	$value
																);

															}

														}

													// MedicalWebPage

														$clinical_resource_provider_MedicalWebPage = $clinical_resource_provider['MedicalWebPage'] ?? null;

														// Get URLs for significantLink property

															if ( $clinical_resource_provider_MedicalWebPage ) {

																$clinical_resource_provider_MedicalWebPagesignificantLink = uamswp_fad_schema_property_values(
																	$clinical_resource_provider_MedicalWebPage, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															}

													// MedicalBusiness and subtypes

														$clinical_resource_provider_MedicalBusiness = $clinical_resource_provider['MedicalBusiness'] ?? null;

														if ( $clinical_resource_provider_MedicalBusiness ) {

															// Get URLs for significantLink property

																$clinical_resource_provider_MedicalBusiness_significantLink = uamswp_fad_schema_property_values(
																	$clinical_resource_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$clinical_resource_provider_MedicalBusiness_keywords = uamswp_fad_schema_property_values(
																	$clinical_resource_provider_MedicalBusiness, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

													// Person

														$clinical_resource_provider_Person = $clinical_resource_provider['Person'] ?? null;
														$clinical_resource_provider_mainEntity = $clinical_resource_provider_Person; // item array for the main entity of the ontology type's MedicalWebPages

														if ( $clinical_resource_provider_Person ) {

															// Get URLs for significantLink property

																$clinical_resource_provider_Person_significantLink = uamswp_fad_schema_property_values(
																	$clinical_resource_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'url' ) // mixed // Required // List of properties from which to collect values
																);

															// Get names for keywords property

																$clinical_resource_provider_Person_keywords = uamswp_fad_schema_property_values(
																	$clinical_resource_provider_Person, // array // Required // Property values from which to extract specific values
																	array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
																);

														}

												}

											}

								}

							// Associated locations

								// List of properties that reference locations

									$clinical_resource_location_common = array(
										'about',
										'keywords',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_location_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_location_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										if ( !isset($clinical_resource_location_array) ) {

											$clinical_resource_location_array = get_field( 'clinical_resource_locations', $entity ) ?? array(); // array

											// Clean up the array

												$clinical_resource_location_array = $clinical_resource_location_array ? array_filter($clinical_resource_location_array) : array();
												$clinical_resource_location_array = $clinical_resource_location_array ? array_values($clinical_resource_location_array) : array();

										}

									// Format values

										if ( $clinical_resource_location_array ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_location') ) {

												$clinical_resource_location = uamswp_fad_schema_location(
													$clinical_resource_location_array, // List of IDs of the location items
													$clinical_resource_url, // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												);

											} else {

												$clinical_resource_location = null;

											}

											if ( isset($clinical_resource_location) ) {

												// Create a list of all major entities of the ontology type's MedicalWebPages

													$clinical_resource_location_about = array(); // Base array

													foreach ( $clinical_resource_location as $key => $value ) {

														if (
															$value
															&&
															$key != 'MedicalWebPage'
														) {

															$clinical_resource_location_about = array_merge(
																$clinical_resource_location_about,
																$value
															);

														}

													}

												// MedicalWebPage

													$clinical_resource_location_MedicalWebPage = $clinical_resource_location['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $clinical_resource_location_MedicalWebPage ) {

															$clinical_resource_location_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_location_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// LocalBusiness and subtypes

													$clinical_resource_location_LocalBusiness = $clinical_resource_location['LocalBusiness'] ?? null;
													$clinical_resource_location_mainEntity = $clinical_resource_location_LocalBusiness; // item array for the main entity of the ontology type's MedicalWebPages

													if ( $clinical_resource_location_LocalBusiness ) {

														// Get URLs for significantLink property

															$clinical_resource_location_LocalBusiness_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$clinical_resource_location_LocalBusiness_keywords = uamswp_fad_schema_property_values(
																$clinical_resource_location_LocalBusiness, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated areas of expertise

								// List of properties that reference areas of expertise

									$clinical_resource_expertise_common = array(
										'about',
										'keywords',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_expertise_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_expertise_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related areas of expertise

										if ( !isset($clinical_resource_expertise_list) ) {

											$clinical_resource_expertise_list = get_field( 'clinical_resource_aoe', $entity ) ?? array();

											// Clean up the array

												$clinical_resource_expertise_list = $clinical_resource_expertise_list ? array_filter($clinical_resource_expertise_list) : array();
												$clinical_resource_expertise_list = $clinical_resource_expertise_list ? array_values($clinical_resource_expertise_list) : array();

										}

									// Format values

										if ( $clinical_resource_expertise_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_expertise') ) {

												$clinical_resource_expertise = uamswp_fad_schema_expertise(
													$clinical_resource_expertise_list, // List of IDs of the area of expertise items
													'', // string // Required // Page or fake subpage URL
													true, // bool // Required // Query for the ontology type of the post (true is ontology type, false is content type)
													'', // string // Required // Fake subpage slug
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ) // Nesting level within the main schema
												) ?? null;

											} else {

												$clinical_resource_expertise = null;

											}

											if ( isset($clinical_resource_expertise) ) {

												// Create a list of all major entities of the ontology type's MedicalWebPages

													$clinical_resource_expertise_about = array(); // Base array

													foreach ( $clinical_resource_expertise as $key => $value ) {

														if (
															$value
															&&
															$key != 'MedicalWebPage'
														) {

															$clinical_resource_expertise_about = array_merge(
																$clinical_resource_expertise_about,
																$value
															);

														}

													}

												// MedicalWebPage

													$clinical_resource_expertise_MedicalWebPage = $clinical_resource_expertise['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $clinical_resource_expertise_MedicalWebPage ) {

															$clinical_resource_expertise_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_expertise_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// MedicalEntity and subtypes

													$clinical_resource_expertise_MedicalEntity = $clinical_resource_expertise['MedicalEntity'] ?? null;
													$clinical_resource_expertise_mainEntity = $clinical_resource_expertise_MedicalEntity; // item array for the main entity of the ontology type's MedicalWebPages

													if ( $clinical_resource_expertise_MedicalEntity ) {

														// Get URLs for significantLink property

															$clinical_resource_expertise_MedicalEntity_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$clinical_resource_expertise_MedicalEntity_keywords = uamswp_fad_schema_property_values(
																$clinical_resource_expertise_MedicalEntity, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated clinical resources

								// List of properties that reference clinical resources

									$clinical_resource_related_clinical_resource_common = array(
										'about',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_related_clinical_resource_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_related_clinical_resource_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related clinical resources

										if ( !isset($clinical_resource_related_clinical_resource_list) ) {

											$clinical_resource_related_clinical_resource_list = get_field( 'clinical_resource_related', $entity ) ?? array();

										}

										if ( !isset($clinical_resource_related_clinical_resource_list_max) ) {

											include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
											$clinical_resource_related_clinical_resource_list_max = $clinical_resource_posts_per_page_section;

										}

									// Format values

										if ( $clinical_resource_related_clinical_resource_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											$clinical_resource_related_clinical_resource = uamswp_fad_schema_clinical_resource(
												$clinical_resource_related_clinical_resource_list, // List of IDs of the clinical resource items
												$clinical_resource_url, // Page URL
												$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
												( $nesting_level + 1 ) // Nesting level within the main schema
											) ?? null;

											if ( isset($clinical_resource_related_clinical_resource) ) {

												// Create a list of all major entities of the ontology type's MedicalWebPages

													$clinical_resource_related_clinical_resource_about = array(); // Base array

													foreach ( $clinical_resource_related_clinical_resource as $key => $value ) {

														if (
															$value
															&&
															$key != 'MedicalWebPage'
														) {

															$clinical_resource_related_clinical_resource_about = array_merge(
																$clinical_resource_related_clinical_resource_about,
																$value
															);

														}

													}

												// MedicalWebPage

													$clinical_resource_related_clinical_resource_MedicalWebPage = $clinical_resource_related_clinical_resource['MedicalWebPage'] ?? null;

													// Get URLs for significantLink property

														if ( $clinical_resource_related_clinical_resource_MedicalWebPage ) {

															$clinical_resource_related_clinical_resource_MedicalWebPage_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_related_clinical_resource_MedicalWebPage, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														}

												// CreativeWork and subtypes

													$clinical_resource_related_clinical_resource_CreativeWork = $clinical_resource_related_clinical_resource['CreativeWork'] ?? null;
													$clinical_resource_related_clinical_resource_mainEntity = $clinical_resource_related_clinical_resource_CreativeWork; // item array for the main entity of the ontology type's MedicalWebPages

													if ( $clinical_resource_related_clinical_resource_CreativeWork ) {

														// Get URLs for significantLink property

															$clinical_resource_related_clinical_resource_CreativeWork_significantLink = uamswp_fad_schema_property_values(
																$clinical_resource_related_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'url' ) // mixed // Required // List of properties from which to collect values
															);

														// Get names for keywords property

															$clinical_resource_related_clinical_resource_CreativeWork_keywords = uamswp_fad_schema_property_values(
																$clinical_resource_related_clinical_resource_CreativeWork, // array // Required // Property values from which to extract specific values
																array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
															);

													}

											}

										}

								}

							// Associated conditions

								// List of properties that reference conditions

									$clinical_resource_condition_common = array(
										'about',
										'keywords',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_condition_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_condition_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related conditions

										if ( !isset($clinical_resource_condition_list) ) {

											$clinical_resource_condition_list = get_field( 'clinical_resource_conditions', $entity ) ?? array();

											// Clean up the array

												$clinical_resource_condition_list = $clinical_resource_condition_list ? array_filter($clinical_resource_condition_list) : array();
												$clinical_resource_condition_list = $clinical_resource_condition_list ? array_values($clinical_resource_condition_list) : array();

										}

									// Format values

										if ( $clinical_resource_condition_list ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_condition') ) {

												$clinical_resource_condition = uamswp_fad_schema_condition(
													$clinical_resource_condition_list, // array // Required // List of IDs of the MedicalCondition items
													$clinical_resource_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$MedicalCondition_i, // int // Optional // Iteration counter for condition-as-MedicalCondition
													$Service_i // int // Optional // Iteration counter for treatment-as-Service
												) ?? null;

											} else {

												$clinical_resource_condition = null;

											}

											if (
												isset($clinical_resource_condition)
												&&
												$clinical_resource_condition
											) {

												// Get names for keywords property

													$clinical_resource_condition_keywords = uamswp_fad_schema_property_values(
														$clinical_resource_condition, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

							// Associated treatments and procedures

								// List of properties that reference treatments and procedures

									$clinical_resource_treatment_common = array(
										'about',
										'keywords',
										'mentions',
										'relatedLink',
										'significantLink'
									);

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
												$clinical_resource_treatment_common
											)
										)
										||
										(
											isset($clinical_resource_item_MedicalEntity)
											&&
											array_intersect(
												$clinical_resource_properties_map[$MedicalEntity_type]['properties'],
												$clinical_resource_treatment_common
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get related treatments

										if ( !isset($clinical_resource_treatment) ) {

											$clinical_resource_treatment = get_field( 'clinical_resource_treatments', $entity ) ?? array();

											// Clean up the array

												$clinical_resource_treatment = $clinical_resource_treatment ? array_filter($clinical_resource_treatment) : array();
												$clinical_resource_treatment = $clinical_resource_treatment ? array_values($clinical_resource_treatment) : array();

										}

									// Format values

										if ( $clinical_resource_treatment ) {

											$node_identifier_list_temp = array(); // Temporary array that will not impact the main list of node identifiers already identified in the schema

											if ( function_exists('uamswp_fad_schema_treatment') ) {

												$clinical_resource_availableService = uamswp_fad_schema_treatment(
													$clinical_resource_treatment, // array // Required // List of IDs of the service items
													$clinical_resource_url, // string // Required // Page URL
													$node_identifier_list_temp, // array // Optional // List of node identifiers (@id) already defined in the schema
													( $nesting_level + 1 ), // int // Optional // Nesting level within the main schema
													$Service_i, // int // Optional // Iteration counter for treatment-as-Service
													$MedicalCondition_i // int // Optional // Iteration counter for condition-as-MedicalCondition
												) ?? null;

											} else {

												$clinical_resource_availableService = null;

											}

											if (
												isset($clinical_resource_availableService)
												&&
												$clinical_resource_availableService
											) {

												// Get names for keywords property

													$clinical_resource_availableService_keywords = uamswp_fad_schema_property_values(
														$clinical_resource_availableService, // array // Required // Property values from which to extract specific values
														array( 'name', 'alternateName' ) // mixed // Required // List of properties from which to collect values
													);

											}

										}

								}

						// Featured image asset info (common)

							// List of properties that reference the featured image asset

								$clinical_resource_featured_image_common = array(
									'image',
									'thumbnail'
								);

							if (
								(
									isset($clinical_resource_item_MedicalWebPage)
									&&
									array_intersect(
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
										$clinical_resource_featured_image_common
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									array_intersect(
										$clinical_resource_properties_map[$CreativeWork_type]['properties'],
										$clinical_resource_featured_image_common
									)
								)
							) {

								// Get featured image values

									// 16:9 aspect ratio source image

										$clinical_resource_featured_image_id = get_field( '_thumbnail_id', $entity ) ?? 0;

									// 1:1 aspect ratio source image

										$clinical_resource_featured_image_square_id = get_field( 'clinical_resource_image_square', $entity ) ?? $clinical_resource_featured_image_id;

								// Create ImageObject values arrays

									if ( $clinical_resource_featured_image_id ) {

										$clinical_resource_image = uamswp_fad_schema_imageobject_thumbnails(
											$clinical_resource_url, // URL of entity with which the image is associated
											( $nesting_level + 1 ), // Nesting level within the main schema
											'16:9', // Aspect ratio to use if only one image is included // enum('1:1', '3:4', '4:3', '16:9')
											'Image', // Base fragment identifier
											$clinical_resource_featured_image_square_id, // ID of image to use for 1:1 aspect ratio
											0, // ID of image to use for 3:4 aspect ratio
											$clinical_resource_featured_image_id, // ID of image to use for 4:3 aspect ratio
											$clinical_resource_featured_image_id, // ID of image to use for 16:9 aspect ratio
											0 // ID of image to use for full image
										) ?? array();

									}

									$clinical_resource_thumbnail = $clinical_resource_image;

							}

						// introduction (common)

							/**
							 * If the common schema template part did not generate a value, get the
							 * non-excerpt fallback value specific to this entity
							 */

							// List of properties that reference treatments and procedures

							$clinical_resource_introduction_common = array(
								'abstract',
								'description',
								'timeRequired'
							);

							if (
								(
									isset($clinical_resource_item_MedicalWebPage)
									&&
									array_intersect(
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties'],
										$clinical_resource_introduction_common
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									array_intersect(
										$clinical_resource_properties_map[$CreativeWork_type]['properties'],
										$clinical_resource_introduction_common
									)
								)
							) {

								// Get the value

									if ( $clinical_resource_resource_type == 'text' ) {

										// Article

											/* Do nothing */

									} elseif ( $clinical_resource_resource_type == 'infographic' ) {

										// Infographic

											$clinical_resource_introduction = get_field( 'clinical_resource_infographic_descr', $entity ) ?? null;

									} elseif ( $clinical_resource_resource_type == 'video' ) {

										// Video

											$clinical_resource_introduction = get_field( 'clinical_resource_video_descr', $entity ) ?? null;

									} elseif ( $clinical_resource_resource_type == 'doc' ) {

										// Document

											$clinical_resource_introduction = get_field( 'clinical_resource_document_descr', $entity ) ?? null;

									}

								// Clean up the value

									if ( $clinical_resource_introduction ) {

										$clinical_resource_introduction = wp_strip_all_tags($clinical_resource_introduction);
										$clinical_resource_introduction = str_replace("\n", ' ', $clinical_resource_introduction); // Strip line breaks
										$clinical_resource_introduction = strlen($clinical_resource_introduction) > 160 ? mb_strimwidth($clinical_resource_introduction, 0, 156, '...') : $clinical_resource_introduction; // Limit to 160 characters
										$clinical_resource_introduction = uamswp_attr_conversion($clinical_resource_introduction);

									}

							}

						// name (specific property)

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
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         The title of the video
							 */

							if (
								(
									isset($clinical_resource_item_MedicalWebPage)
									&&
									in_array(
										'name',
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'name',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
							) {

								// MedicalWebPage

									// Get values

										$clinical_resource_name = $clinical_resource_post_title ?? $clinical_resource_name;

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'name', // string // Required // Name of schema property
												$clinical_resource_name, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								// CreativeWork (asset-specific)

									// Loop through the main media asset(s) info array to get the 'name' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'name' value

													$asset_name = $value['name'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'name', // string // Required // Name of schema property
														$asset_name, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

							}

						// about

							/**
							 * The subject matter of the content.
							 *
							 * Inverse-property: subjectOf
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Thing
							 *
							 * Used on these types:
							 *
							 *      - Certification
							 *      - CommunicateAction
							 *      - CreativeWork
							 *      - Event
							 *
							 * Sub-properties:
							 *
							 *      - mainEntity
							 *
							 */

							if (
								isset($clinical_resource_item_CreativeWork)
								&&
								in_array(
									'about',
									$clinical_resource_properties_map[$CreativeWork_type]['properties']
								)
							) {

								// MedicalWebPage

									/*

										The value for this property is already being defined for the 'MedicalWebPage'
										type in the common schema properties
										(templates/parts/vars/page/schema/common/properties.php)

									*/

								// CreativeWork (asset-agnostic)

									// Get values [WIP]

										/*

											For CreativeWork, set the value as something like the associated areas of
											expertise, conditions and treatments.

										*/

										// Base array

											$clinical_resource_about_CreativeWork = array();

										// Merge in the associated providers

											if (
												isset($clinical_resource_provider_about)
												&&
												$clinical_resource_provider_about
											) {

												$clinical_resource_about_CreativeWork = array_merge(
													$clinical_resource_about_CreativeWork,
													$clinical_resource_provider_about
												);

											}

										// Merge in the associated locations

											if (
												isset($clinical_resource_location_about)
												&&
												$clinical_resource_location_about
											) {

												$clinical_resource_about_CreativeWork = array_merge(
													$clinical_resource_about_CreativeWork,
													$clinical_resource_location_about
												);

											}

										// Merge in the associated areas of expertise

											if (
												isset($clinical_resource_expertise_about)
												&&
												$clinical_resource_expertise_about
											) {

												$clinical_resource_about_CreativeWork = array_merge(
													$clinical_resource_about_CreativeWork,
													$clinical_resource_expertise_about
												);

											}

										// Merge in the associated conditions

											if (
												isset($clinical_resource_condition)
												&&
												$clinical_resource_condition
											) {

												$clinical_resource_about_CreativeWork = array_merge(
													$clinical_resource_about_CreativeWork,
													$clinical_resource_condition
												);

											}

										// Merge in the associated treatments

											if (
												isset($clinical_resource_treatment)
												&&
												$clinical_resource_treatment
											) {

												$clinical_resource_about_CreativeWork = array_merge(
													$clinical_resource_about_CreativeWork,
													$clinical_resource_treatment
												);

											}

									// Add to item values (CreativeWork)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'about', // string // Required // Name of schema property
												$clinical_resource_about_CreativeWork, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// abstract

							/**
							 * An abstract is a short description that summarizes a CreativeWork.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 *
							 * If there is an introduction, use that to override the excerpt value as the
							 * value of this property.
							 */

							if (
								$clinical_resource_introduction
								&&
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'abstract',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'abstract',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'abstract', // string // Required // Name of schema property
											$clinical_resource_introduction, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'abstract', // string // Required // Name of schema property
												$clinical_resource_introduction, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// accessMode [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessModeSufficient [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessibilityAPI [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessibilityControl [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessibilityFeature [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessibilityHazard [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accessibilitySummary [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// accountablePerson [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// acquireLicensePage [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// actor [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// actors [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

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
									isset($clinical_resource_item_MedicalWebPage)
									&&
									in_array(
										'additionalType',
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'additionalType',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
							) {

								// Get values

									// Base array

										$clinical_resource_additionalType = array();

									// Set the infographic value

										if ( $clinical_resource_resource_type == 'infographic' ) {

											$clinical_resource_additionalType[] = 'https://www.wikidata.org/wiki/Q845734'; // Wikidata entry for 'infographic'

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'additionalType', // string // Required // Name of schema property
											$clinical_resource_additionalType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'additionalType', // string // Required // Name of schema property
												$clinical_resource_additionalType, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// aggregateRating [excluded]

							/**
							 * The overall rating, based on a collection of reviews or ratings, of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - AggregateRating
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

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
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'alternateName',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'alternateName',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// MedicalWebPage

									// Get values

										// Base array

											$clinical_resource_alternateName_MedicalWebPage = array();

										// Add the media asset title

											// Loop through the main media asset(s) info array to get the name value of each asset

												foreach ( $clinical_resource_asset as $asset ) {

													if ( $asset ) {

														// Get 'name' value

															$asset_name = $asset['name'] ?? null;

														// If name value is different than the post title, add it to the alternateName value

															if (
																$asset_name
																&&
																$asset_name != $clinical_resource_post_title
															) {

																$clinical_resource_alternateName_MedicalWebPage[] = $asset_name;

															}

													}

												}

									// Add to item values (MedicalWebPage)

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'alternateName', // string // Required // Name of schema property
											$clinical_resource_alternateName_MedicalWebPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// CreativeWork (asset-specific)

									// Loop through the main media asset(s) info array to get the 'alternateName' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'alternateName' value

													$asset_alternateName = $value['alternateName'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'alternateName', // string // Required // Name of schema property
														$asset_alternateName, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

							}

						// alternativeHeadline [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// archivedAt [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// articleBody

							/**
							 * The actual body of the article.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types
							 *
							 *      - Article
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'articleBody',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'articleBody',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Get NCI syndication query

										$clinical_resource_nci_query = get_field( 'clinical_resource_text_nci_query', $entity ) ?? false;

									// If the article is not syndicated from NCI, add the body text

										if ( !$clinical_resource_nci_query ) {

											$clinical_resource_articleBody = get_field( 'clinical_resource_text', $entity )  ?? '';

										}

								// Clean up values

									if ( $clinical_resource_articleBody ) {

										$clinical_resource_articleBody = wp_strip_all_tags($clinical_resource_articleBody);
										$clinical_resource_articleBody = str_replace("\n", ' ', $clinical_resource_articleBody); // Strip line breaks
										$clinical_resource_articleBody = strlen($clinical_resource_articleBody) > 160 ? mb_strimwidth($clinical_resource_articleBody, 0, 156, '...') : $clinical_resource_articleBody; // Limit to 160 characters
										$clinical_resource_articleBody = uamswp_attr_conversion($clinical_resource_articleBody);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'articleBody', // string // Required // Name of schema property
											$clinical_resource_articleBody, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'articleBody', // string // Required // Name of schema property
												$clinical_resource_articleBody, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// articleSection [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// aspect [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// assembly [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// assemblyVersion [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// assesses [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// associatedArticle [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// associatedMedia [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// audience [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// audio [excluded]

							/**
							 * An embedded audio object.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - AudioObject
							 *      - Clip
							 *      - MusicRecording
							 *
							 * This schema property is not relevant to UAMSHealth.com webpages and will not be
							 * included for the MedicalWebPage schema type.
							 *
							 * This schema property is not currently relevant to clinical resoures and will
							 * not be included for the MedicalWebPage schema type.
							 */

						// author [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// award [excluded]

							/**
							 * An award won by or for this item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// awards [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// backstory [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// bitrate [WIP]

							/**
							 * The bitrate of the media object.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *     - MediaObject
							 */

							if (
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'bitrate',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
								&&
								$nesting_level == 0
							) {

								// CreativeWork (asset-specific)

									// Loop through the main media asset(s) info array to get the 'bitrate' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'bitrate' value

													$asset_bitrate = $value['bitrate'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'bitrate', // string // Required // Name of schema property
														$asset_bitrate, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

							}

						// breadcrumb [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// caption

							/**
							 * The caption for this object.
							 *
							 * For downloadable machine formats (e.g., closed caption and subtitles), use
							 * MediaObject and indicate the encodingFormat.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - MediaObject
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *     - AudioObject
							 *     - ImageObject
							 *     - VideoObject
							 *
							 * Note: The documentation on schema.org is not helpful for clearly distinguishing
							 * the different intended uses of the 'name', 'caption' and 'description'
							 * properties when defining schema for the AudioObject, ImageObject and
							 * VideoObject types. It is likely that they are all intended to have unique
							 * values. However, until clarity is found, the 'caption' property value will be
							 * defined using the same value that is used for the 'description' property value.
							 */

							if (
								$clinical_resource_introduction
								&&
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'caption',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'caption',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
							) {

								// MedicalWebPage

									// Add to item values

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'caption', // string // Required // Name of schema property
											$clinical_resource_introduction, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

								// CreativeWork (asset-specific)

									// Loop through the main media asset(s) info array to get the 'caption' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'description' value

													$asset_caption = $value['description'] ?? $clinical_resource_introduction;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'caption', // string // Required // Name of schema property
														$asset_caption, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

							}

						// character [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// citation [excluded]

							/**
							 * A citation or reference to another creative work, such as another publication,
							 * web page, scholarly article, etc.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - Text
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// comment [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// commentCount [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// conditionsOfAccess [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// contentLocation [excluded; irrelevant]

							/**
							 * The location depicted or described in the content. For example, the location in
							 * a photograph or painting.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Place
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 *
							 * Sub-properties:
							 *
							 *      - spatialCoverage
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// contentRating [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// contentReferenceTime [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// contentSize

							/**
							 * File size in (mega/kilo)bytes.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - MediaObject
							 */

							// CreativeWork (asset-specific)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'contentSize',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Loop through the main media asset(s) info array to get the 'contentSize' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'contentSize' value

													$asset_contentSize = $value['contentSize'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'contentSize', // string // Required // Name of schema property
														$asset_contentSize, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

								}

						// contentUrl

							/**
							 * Actual bytes of the media object, for example the image file or video file.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - MediaObject
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         A URL pointing to the actual video media file, in one of the supported encoding
							 *         formats [https://developers.google.com/search/docs/appearance/video#supported-video-encodings].
							 *         Don't link to the page where the video lives; this must be the URL of the
							 *         video media file itself.
							 *
							 *         We recommend that your provide the contentUrl property, if possible. This is
							 *         the most effective way for Google to fetch your video content files. If
							 *         contentUrl isn't available, provide embedUrl as an alternative.
							 */

							// CreativeWork (asset-specific)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'contentUrl',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Loop through the main media asset(s) info array to get the 'contentUrl' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'contentUrl' value

													$asset_contentUrl = $value['contentUrl'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'contentUrl', // string // Required // Name of schema property
														$asset_contentUrl, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

								}

						// contributor [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// copyrightHolder [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// copyrightNotice [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// copyrightYear [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// correction [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// countryOfOrigin [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// coverageEndTime [excluded; irrelevant]

							/**
							 * The time when the live blog will stop covering the Event. Note that coverage
							 * may continue after the Event concludes.
							 *
							 * Values expected to be one of these types:
							 *
							 *     - DateTime
							 *
							 * Used on these types:
							 *
							 *     - LiveBlogPosting (Thing > CreativeWork > Article > SocialMediaPosting > BlogPosting > LiveBlogPosting)
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// coverageStartTime [excluded; irrelevant]

							/**
							 * The time when the live blog will begin covering the Event. Note that coverage
							 * may begin before the Event's start time. The LiveBlogPosting may also be
							 * created before coverage begins.
							 *
							 * Values expected to be one of these types:
							 *
							 *     - DateTime
							 *
							 * Used on these types:
							 *
							 *     - LiveBlogPosting (Thing > CreativeWork > Article > SocialMediaPosting > BlogPosting > LiveBlogPosting)
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// creativeWorkStatus [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// creator [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// creditText [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// dateCreated [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// dateModified [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// datePublished [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// dateline [excluded; irrelevant]

							/**
							 * A dateline is a brief piece of text included in news articles that describes
							 * where and when the story was written or filed though the date is often omitted.
							 * Sometimes only a placename is provided.
							 *
							 * Structured representations of dateline-related information can also be
							 * expressed more explicitly using locationCreated (which represents where a work
							 * was created, e.g. where a news report was written). For location depicted or
							 * described in the content, use contentLocation.
							 *
							 * Dateline summaries are oriented more towards human readers than towards
							 * automated processing, and can vary substantially.
							 *
							 * Some examples:
							 *
							 *      - "BEIRUT, Lebanon, June 2."
							 *      - "Paris, France"
							 *      - "December 19, 2017 11:43AM Reporting from Washington"
							 *      - "Beijing/Moscow"
							 *      - "QUEZON CITY, Philippines"
							 *
							 * Values expected to be one of these types:
							 *
							 *     - Text
							 *
							 * Used on these types:
							 *
							 *     - NewsArticle (Thing > CreativeWork > Article > NewsArticle)
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// dependencies [excluded; irrelevant]

							/**
							 * Prerequisites needed to fulfill steps in article.
							 *
							 * Values expected to be one of these types:
							 *
							 *     - Text
							 *
							 * Used on these types:
							 *
							 *     - TechArticle (Thing > CreativeWork > Article > TechArticle)
							 */

						// description

							/**
							 * A description of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text (preferred by Google)
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
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         Text
							 *
							 *         The description of the video. HTML tags are ignored.
							 *
							 * Notes:
							 *
							 *      * For CreativeWork:
							 *             * For video:
							 *                    * If this is a video, use the video description from the video platform
							 *                      (e.g., YouTube).
							 *                    * If there is no video description from the video platform, use the introduction
							 *                      from the clinical resource.
							 *                    * If there is no introduction from the clinical resource, use the excerpt from
							 *                      the clinical resource.
							 *      * For MedicalWebPage:
							 *             * If there is an introduction, use that.
							 *             * If there is no introduction, use the excerpt.
							 */

							if (
								$clinical_resource_introduction
								&&
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'description',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'description',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
							) {

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'description', // string // Required // Name of schema property
											$clinical_resource_introduction, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'description', // string // Required // Name of schema property
												$clinical_resource_introduction, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// digitalSourceType [WIP]

							/**
							 * Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the
							 * digital source(s) for some CreativeWork.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - IPTCDigitalSourceEnumeration (Enumeration Type)
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 *
							 * As of 23 Apr 2024, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'digitalSourceType',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'digitalSourceType',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_digitalSourceType = get_field( 'schema_iptcdigitalsourceenumeration_multiple', $entity ) ?? null;

									// Clean up values

										$clinical_resource_digitalSourceType = $clinical_resource_digitalSourceType ? array_filter($clinical_resource_digitalSourceType) : null;
										$clinical_resource_digitalSourceType = $clinical_resource_digitalSourceType ? array_values($clinical_resource_digitalSourceType) : null;

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'digitalSourceType', // string // Required // Name of schema property
											$clinical_resource_digitalSourceType, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'digitalSourceType', // string // Required // Name of schema property
												$clinical_resource_digitalSourceType, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// director [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// directors [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// disambiguatingDescription [excluded]

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
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// discussionUrl [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// duration

							/**
							 * The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date
							 * format.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Duration
							 *
							 * Used on these types:
							 *
							 *      - Audiobook (Thing > CreativeWork > MediaObject > AudioObject > Audiobook; Thing > CreativeWork > Book > Audiobook)
							 *      - Episode
							 *      - Event
							 *      - MediaObject (Thing > CreativeWork > MediaObject)
							 *      - Movie
							 *      - MusicRecording
							 *      - MusicRelease
							 *      - QuantitativeValueDistribution
							 *      - Schedule
							 *
							 * Sub-properties:
							 *
							 *      - loanTerm
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         The duration of the video in ISO 8601 format
							 *         [https://en.wikipedia.org/wiki/ISO_8601#Durations]. For example, PT00H30M5S represents
							 *         a duration of "thirty minutes and five seconds".
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'duration',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'duration',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
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
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'duration', // string // Required // Name of schema property
											$clinical_resource_asset_duration, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'duration', // string // Required // Name of schema property
												$clinical_resource_asset_duration, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// editEIDR [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// editor [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// educationalAlignment [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// educationalLevel [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// educationalUse [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// embedUrl

							/**
							 * A URL pointing to a player for a specific video. In general, this is the
							 * information in the src element of an embed tag and should not be the same as
							 * the content of the loc tag.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - MediaObject
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         A URL pointing to a player for the specific video. Don't link to the page where
							 *         the video lives; this must be the URL of the video player itself. Usually this
							 *         is the information in the src attribute of an <embed> tag.
							 *
							 *         We recommend that your provide the contentUrl property, if possible. This is
							 *         the most effective way for Google to fetch your video content files. If
							 *         contentUrl isn't available, provide embedUrl as an alternative.
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'embedUrl',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'embedUrl',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
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
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'embedUrl', // string // Required // Name of schema property
											$clinical_resource_asset_embedUrl, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'embedUrl', // string // Required // Name of schema property
												$clinical_resource_asset_embedUrl, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// embeddedTextCaption

							/**
							 * Represents textual captioning from a MediaObject (e.g., text of a 'meme').
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - AudioObject
							 *      - ImageObject
							 *      - VideoObject
							 *
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'embeddedTextCaption',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'embeddedTextCaption',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_embeddedTextCaption = get_field( 'clinical_resource_infographic_transcript', $entity ) ?: '';

								// Clean up values

									if ( $clinical_resource_embeddedTextCaption ) {

										$clinical_resource_embeddedTextCaption = wp_strip_all_tags($clinical_resource_embeddedTextCaption);
										$clinical_resource_embeddedTextCaption = str_replace("\n", ' ', $clinical_resource_embeddedTextCaption); // Strip line breaks
										$clinical_resource_embeddedTextCaption = uamswp_attr_conversion($clinical_resource_embeddedTextCaption);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'embeddedTextCaption', // string // Required // Name of schema property
											$clinical_resource_embeddedTextCaption, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'embeddedTextCaption', // string // Required // Name of schema property
												$clinical_resource_embeddedTextCaption, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// encodesCreativeWork [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// encoding [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// encodingFormat

							/**
							 * Media type typically expressed using a MIME format (see IANA site and MDN
							 * reference) (e.g., application/zip for a SoftwareApplication binary, audio/mpeg
							 * for .mp3).
							 *
							 * In cases where a CreativeWork has several media type representations, encoding
							 * can be used to indicate each MediaObject alongside particular encodingFormat
							 * information.
							 *
							 * Unregistered or niche encoding and file formats can be indicated instead via
							 * the most appropriate URL (e.g., defining Web page or a Wikipedia/Wikidata
							 * entry).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *      - URL
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - MediaObject
							 */

							// CreativeWork (asset-specific)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'encodingFormat',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Loop through the main media asset(s) info array to get the 'encodingFormat' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'encodingFormat' value

													$asset_encodingFormat = $value['encodingFormat'] ?? null;

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'encodingFormat', // string // Required // Name of schema property
														$asset_encodingFormat, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

								}

						// encodings [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// endTime [excluded; irrelevant]

							/**
							 * The endTime of something. For a reserved event or service
							 * (e.g., FoodEstablishmentReservation), the time that it is expected to end.
							 *
							 * For actions that span a period of time, when the action was performed
							 * (e.g., John wrote a book from January to December).
							 *
							 * For media, including audio  and video, it's the time offset of the end of a
							 * clip within a larger file.
							 *
							 * Note that Event uses startDate/endDate instead of startTime/endTime, even when
							 * describing dates with times. This situation may be clarified in future
							 * revisions.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - DateTime
							 *      - Time
							 *
							 * Used on these types:
							 *
							 *      - Action
							 *      - FoodEstablishmentReservation
							 *      - InteractionCounter
							 *      - MediaObject
							 *      - Schedule
							 *
							 * Note: Since this property would only be relevant to a clip within a larger
							 * MediaObject (video or audio), it is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// exampleOfWork [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// executableLibraryName [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// exifData [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// expires (CreativeWork [video] only) [WIP]

							/**
							 * Date the content expires and is no longer useful or available. For example a
							 * VideoObject or NewsArticle whose availability or relevance is time-limited, a
							 * ClaimReview fact check whose publisher wants to indicate that it may no longer be
							 * relevant (or helpful to highlight) after some date, or a Certification the validity has
							 * expired.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Date
							 *      - DateTime (preferred by Google)
							 *
							 * Used on these types:
							 *
							 *      - Certification
							 *      - CreativeWork
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         If applicable, the date and time after which the video will no longer be
							 *         available, in ISO 8601 format [https://en.wikipedia.org/wiki/ISO_8601]. Don't
							 *         supply this information if your video does not expire. We recommend that you
							 *         provide timezone information; otherwise, we will default to the timezone used
							 *         by Googlebot
							 *         [https://developers.google.com/search/docs/crawling-indexing/googlebot#timezone].
							 */

						// fileFormat (CreativeWork only) [WIP]

							/**
							 * Media type, typically MIME format (see IANA site
							 * [http://www.iana.org/assignments/media-types/media-types.xhtml]) of the content
							 * (e.g., application/zip of a SoftwareApplication binary). In cases where a
							 * CreativeWork has several media type representations, 'encoding' can be used to
							 * indicate each MediaObject alongside particular fileFormat information.
							 * Unregistered or niche file formats can be indicated instead via the most
							 * appropriate URL (e.g., defining Web page or a Wikipedia entry).
							 *
							 * Values expected to be one of these types
							 *
							 *      - Text
							 *      - URL
							 *
							 * Used on these types
							 *
							 *      - CreativeWork
							 *
							 * SupersededBy
							 *      - encodingFormat
							 */

						// funder [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// funding [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// genre [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// hasDigitalDocumentPermission

							/**
							 * A permission related to the access to this document (e.g., permission to read
							 * or  write an electronic document). For a public document, specify a grantee
							 * with an  Audience with audienceType equal to "public".
							 *
							 * Values expected to be one of these types:
							 *
							 *      - DigitalDocumentPermission
							 *
							 * Used on these types:
							 *
							 *      - DigitalDocument
							 */

							// CreativeWork (asset-agnostic)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'hasDigitalDocumentPermission',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										$clinical_resource_hasDigitalDocumentPermission = array(
											'@type' => 'DigitalDocumentPermission',
											'permissionType' => 'ReadPermission', // Thing > Intangible > Enumeration > DigitalDocumentPermissionType
											'grantee' => array(
												'@type' => 'Audience',
												'audienceType' => 'public'
											)
										);

									// Add to item values

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'hasDigitalDocumentPermission', // string // Required // Name of schema property
												$clinical_resource_hasDigitalDocumentPermission, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

								}

						// hasPart

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
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         If your video has important segments, nest the required Clip properties in your
							 *         VideoObject. For example:
							 *
							 *             "@type": "Clip",
							 *             "name": "Cat jumps",
							 *             "startOffset": 30,
							 *             "url": "https://www.example.com/example?t=30"
							 */

							// MedicalWebPage

								if (
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'hasPart',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

											$clinical_resource_hasPart_MedicalWebPage = $clinical_resource_item_CreativeWork_common ?? null;

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'hasPart', // string // Required // Name of schema property
												$clinical_resource_hasPart_MedicalWebPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

								}

						// headline [WIP]

							/**
							 * Headline of the article.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 */

							// CreativeWork

								if (
									(
										(
											isset($clinical_resource_item_MedicalWebPage)
											&&
											in_array(
												'headline',
												$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
											)
										)
										||
										(
											isset($clinical_resource_item_CreativeWork)
											&&
											in_array(
												'headline',
												$clinical_resource_properties_map[$CreativeWork_type]['properties']
											)
										)
									)
									&&
									$nesting_level == 0
								) {

									// Get values

										if ( $clinical_resource_resource_type == 'text' ) {

											// Article type

												/**
												 * Define for the 'Article' type clinical resources by using the post title.
												 */

												$clinical_resource_headline = null;

										} elseif ( $clinical_resource_resource_type == 'infographic' ) {

											// Infographic type

												/**
												 * Define for the 'Infographic' type clinical resources by using the media asset
												 * title from within WordPress.
												 */

												$clinical_resource_headline = null;

										} elseif ( $clinical_resource_resource_type == 'video' ) {

											// Video type

												/**
												 * Define for the 'Video' type clinical resources by using the video title from
												 * the video platform (e.g., the name of the video from the YouTube API).
												 */

												$clinical_resource_headline = null;

										} elseif ( $clinical_resource_resource_type == 'doc' ) {

											// Document type

												/**
												 * Define for the 'Document' type clinical resources by using the media asset
												 * title from within WordPress.
												 */

												$clinical_resource_headline = null;

										}

									// Add to item values

										// MedicalWebPage

											uamswp_fad_schema_add_to_item_values(
												$MedicalWebPage_type, // string // Required // The @type value for the schema item
												$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
												'headline', // string // Required // Name of schema property
												$clinical_resource_headline, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										// CreativeWork (asset-agnostic)

											foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

												uamswp_fad_schema_add_to_item_values(
													$CreativeWork_type, // string // Required // The @type value for the schema item
													$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
													'headline', // string // Required // Name of schema property
													$clinical_resource_headline, // mixed // Required // Variable to add as the property value
													$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
													$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
													($nesting_level + 1) // int // Required // Current nesting level value
												);

											}

								}

						// height

							/**
							 * The height of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Distance
							 *      - QuantitativeValue
							 */

							// CreativeWork (asset-specific)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'height',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Loop through the main media asset(s) info array to get the 'height' value of each asset

										foreach ( $clinical_resource_asset as $key => $value ) {

											if ( $value ) {

												// Get the 'height' value

													$asset_height = $value['height'] ?? null;
													$asset_height = $asset_height ? $asset_height . ' px' : '';

												// Add to item values (CreativeWork)

													uamswp_fad_schema_add_to_item_values(
														$CreativeWork_type, // string // Required // The @type value for the schema item
														$clinical_resource_item_CreativeWork[$key], // array // Required // The list array for the schema item to which to add the property value
														'height', // string // Required // Name of schema property
														$asset_height, // mixed // Required // Variable to add as the property value
														$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
														$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
														($nesting_level + 1) // int // Required // Current nesting level value
													);

											} // endif ( $value )

										} // endforeach ( $clinical_resource_asset as $key => $value )

								}

						// identifier [excluded; irrelevant]

							/**
							 * The identifier property represents any kind of identifier for any kind of
							 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated
							 * properties for representing many of these, either as textual strings or as URL
							 * (URI) links. See background notes for more details.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - PropertyValue
							 *      - Text
							 *      - URL
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// image (specific property)

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
									isset($clinical_resource_item_MedicalWebPage)
									&&
									in_array(
										'image',
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'image',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
							) {

								// Get values

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'image', // string // Required // Name of schema property
											$clinical_resource_image, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'image', // string // Required // Name of schema property
												$clinical_resource_image, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// ineligibleRegion [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// inLanguage (CreativeWork only) [WIP]

							/**
							 * The language of the content or performance or used in an action. Please use one
							 * of the language codes from the IETF BCP 47 standard.
							 *
							 * See also availableLanguage.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Language
							 *      - Text
							 */

							/*

								Add input to clinical resources to indicate the language of the content.

								Add a facet to the clinical resource archive for the language of the content.

								If there is a value (or if the value is not English), override the 'inLanguage'
								schema property value for the CreativeWork item.

							*/

						// interactionStatistic [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         The number of times the video has been watched. For example:
							 *
							 *             "@type": "InteractionCounter",
							 *             "interactionType": { "@type": "WatchAction" },
							 *             "userInteractionCount": 12345
							 *
							 *         Starting October 2019, we changed our documentation to recommend
							 *         interactionStatistic instead of interactionCount. While we continue to support
							 *         interactionCount, we recommend interactionStatistic moving forward.
							 */

						// interactivityType [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// interpretedAsClaim [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// isAccessibleForFree (CreativeWork only)

							/**
							 * A flag to signal that the item, event, or place is accessible for free.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Boolean
							 */

							if (
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'isAccessibleForFree',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_isAccessibleForFree = 'True';

								// Add to item values

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'isAccessibleForFree', // string // Required // Name of schema property
												$clinical_resource_isAccessibleForFree, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// isBasedOn (CreativeWork only) [WIP]

							/**
							 * A resource from which this work is derived or from which it is a modification
							 * or adaptation.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - Product
							 *      - URL
							 */

							/*

								Add input to clinical resources to indicate a resource from which each clinical
								resource's content is derived or from which it is a modification or adaptation.

								If there is a value, set the schema property value for the CreativeWork item.

							*/

						// isBasedOnUrl [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// isFamilyFriendly [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// isPartOf (CreativeWork only) [WIP]

							/**
							 * Indicates an item or CreativeWork that this item, or CreativeWork (in some
							 * sense), is part of.
							 *
							 * Inverse-property: hasPart
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *      - URL
							 */

							if (
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'isPartOf',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$clinical_resource_isPartOf = array();

									// Merge in the MedicalWebPage item values

										$clinical_resource_item_MedicalWebPage = $clinical_resource_item_MedicalWebPage ?? null;

										if ( $clinical_resource_item_MedicalWebPage ) {

											$clinical_resource_isPartOf = uamswp_fad_schema_merge_values(
												$clinical_resource_isPartOf, // mixed // Required // Initial schema item property value
												$clinical_resource_item_MedicalWebPage // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'isPartOf', // string // Required // Name of schema property
												$clinical_resource_isPartOf, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// keywords [WIP]

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

						// lastReviewed [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// learningResourceType [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// license [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// liveBlogUpdate [excluded; irrelevant]

							/**
							 * An update to the LiveBlog.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - BlogPosting
							 *
							 * Used on these types:
							 *
							 *      - LiveBlogPosting (Thing > CreativeWork > Article > SocialMediaPosting > BlogPosting > LiveBlogPosting)
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// locationCreated [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

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
							*     - WebPage
							*
							* Supersedes:
							*
							*     - aspect
							*/

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'mainContentOfPage',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'mainContentOfPage',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level <= 1
							) {

								// Get values

									$clinical_resource_mainContentOfPage = array(
										'@type' => 'WebPageElement',
										'cssSelector' => '.clinical-resource-item'
									);

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'mainContentOfPage', // string // Required // Name of schema property
											$clinical_resource_mainContentOfPage, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'mainContentOfPage', // string // Required // Name of schema property
												$clinical_resource_mainContentOfPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

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

						// maintainer [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// material [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// materialExtent [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// medicalAudience [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// mentions [WIP]

							/**
							 * Indicates that the CreativeWork contains a reference to, but is not necessarily
							 * about a concept.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Thing
							 */

						// musicBy [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

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
							 * Values expected to be one of these types:
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

						// pageEnd [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// pageStart [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// pagination [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// pattern [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// playerType (CreativeWork [video] only) [WIP]

							/**
							 * Player type required—for example, Flash or Silverlight.
							 *
							 * Values expected to be one of these types:
							 *
							 *     - Text
							 *
							 * Used on these types:
							 *
							 *     - MediaObject
							 */

						// position [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// potentialAction [WIP]

							/**
							 * Indicates a potential Action, which describes an idealized action in which this
							 * thing would play an 'object' role.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Action
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

								Define this property in the MedicalWebPage schema for the 'Infographic' type
								clinical resources by using the infographic asset.

							*/

						// printColumn [excluded; irrelevant]

							/**
							 * The number of the column in which the NewsArticle appears in the print edition.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - NewsArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// printEdition [excluded; irrelevant]

							/**
							 * The edition of the print product in which the NewsArticle appears.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - NewsArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// printPage [excluded; irrelevant]

							/**
							 * If this NewsArticle appears in print, this field indicates the name of the page
							 * on which the article is found. Please note that this field is intended for the
							 * exact page name (e.g., A5, B18).
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - NewsArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// printSection [excluded; irrelevant]

							/**
							 * If this NewsArticle appears in print, this field indicates the print section in
							 * which the article appeared.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - NewsArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// producer [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// productionCompany [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// proficiencyLevel [excluded; irrelevant]

							/**
							 * Proficiency needed for this content.
							 *
							 * Expected values:
							 *
							 *      - 'Beginner'
							 *      - 'Expert'
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - TechArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// programmingModel [excluded; irrelevant]

							/**
							 * Indicates whether API is managed or unmanaged.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - APIReference (Thing > CreativeWork > Article > TechArticle > APIReference)
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// provider [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// publication [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php).
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         If your video is happening live and you want to be eligible for the LIVE badge,
							 *         nest the BroadcastEvent properties in your VideoObject. For example:
							 *
							 *             "@type": "BroadcastEvent",
							 *             "name": "First scheduled broadcast",
							 *             "isLiveBroadcast": true,
							 *             "startDate": "2018-10-27T14:00:00+00:00",
							 *             "endDate": "2018-10-27T14:37:14+00:00"
							 */

						// publicationType [excluded; common properties]

							/**
							 * The type of the medical article, taken from the US NLM MeSH publication type
							 * catalog.
							 *
							 * See also MeSH documentation [https://www.nlm.nih.gov/mesh/pubtypes.html].
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - MedicalScholarlyArticle
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// publisher [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// publisherImprint [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// publishingPrinciples [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// recordedAt [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// regionsAllowed [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php).
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         The regions where the video is allowed. If not specified, then Google assumes
							 *         the video is allowed everywhere. Specify the countries in ISO 3166 format
							 *         [https://en.wikipedia.org/wiki/ISO_3166]. For multiple values, use a space or
							 *         comma as a delimiter.
							 */

						// releasedEvent [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// reportNumber [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// relatedLink [WIP]

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

						// representativeOfPage (CreativeWork only)

							/**
							 * Indicates whether this image is representative of the content of the page.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Boolean
							 *
							 * Used on these types:
							 *
							 *      - ImageObject
							 */

							// CreativeWork (asset-agnostic)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'representativeOfPage',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								) {

									// Get values

										$clinical_resource_representativeOfPage = ( $nesting_level == 0 ) ? 'True' : 'False';

									// Add to item values

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'representativeOfPage', // string // Required // Name of schema property
												$clinical_resource_representativeOfPage, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

								}

						// requiresSubscription [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// review [excluded]

							/**
							 * A review of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Review
							 *
							 * This schema property is not relevant to clinical resources or their webpages,
							 * and so it will not be included.
							 */

						// reviewedBy [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// reviews [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
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
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'sameAs',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'sameAs',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$clinical_resource_sameAs = array();

									// Merge in the video URL value

										$clinical_resource_resource_type = $clinical_resource_resource_type ?? null;

										if ( $clinical_resource_resource_type == 'video' ) {

											$clinical_resource_sameAs = uamswp_fad_schema_merge_values(
												$clinical_resource_sameAs, // mixed // Required // Initial schema item property value
												$clinical_resource_video // mixed // Required // Incoming schema item property value
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'sameAs', // string // Required // Name of schema property
											$clinical_resource_sameAs, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'sameAs', // string // Required // Name of schema property
												$clinical_resource_sameAs, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// schemaVersion [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// sdDatePublished [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// sdLicense [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// sdPublisher [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// sha256 [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// sharedContent [excluded; irrelevant]

							/**
							 * A CreativeWork such as an image, video, or audio clip shared as part of this
							 * posting.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - CreativeWork
							 *
							 * Used on these types:
							 *
							 *      - Comment (Thing > CreativeWork > Comment)
							 *      - SocialMediaPosting (Thing > CreativeWork > Article > SocialMediaPosting)
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// significantLink [WIP]

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

						// significantLinks [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// size [excluded]

							/**
							 * A standardized size of a product or creative work, specified either through a
							 * simple textual string (for example 'XL', '32Wx34L'), a QuantitativeValue with a
							 * unitCode, or a comprehensive and structured SizeSpecification; in other cases,
							 * the width, height, depth and weight properties may be more applicable.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - [insert type(s) here]
							 *
							 * As of 1 Sep 2023, this term is in the "new" area of Schema.org. Implementation
							 * feedback and adoption from applications and websites can help improve their
							 * definitions.
							 *
							 * Standardized sizes are not relevant to clinical resources or their webpages and
							 * so this schema property will not be included.
							 */

						// sourceOrganization [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// spatial [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// spatialCoverage [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// speakable

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
							 *     - Article
							 *     - WebPage
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'speakable',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'speakable',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Base array

										$clinical_resource_speakable = array();

									// Introduction / Description

										if (
											$clinical_resource_resource_type == 'infographic'
											||
											$clinical_resource_resource_type == 'video'
											||
											$clinical_resource_resource_type == 'doc'
										) {

											$clinical_resource_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-description-body'
											);

										}

									// Content

										if ( $clinical_resource_resource_type == 'text' ) {

											$clinical_resource_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-content-body'
											);

										}

									// Transcript

										if (
											$clinical_resource_resource_type == 'infographic'
											||
											$clinical_resource_resource_type == 'video'
										) {

											$clinical_resource_speakable[] = array(
												'@type' => 'SpeakableSpecification',
												'cssSelector' => '#resource-transcript-body'
											);

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'speakable', // string // Required // Name of schema property
											$clinical_resource_speakable, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'speakable', // string // Required // Name of schema property
												$clinical_resource_speakable, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// specialty [WIP]

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

						// sponsor [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// startTime [excluded; irrelevant]

							/**
							 * The startTime of something. For a reserved event or service
							 * (e.g., FoodEstablishmentReservation), the time that it is expected to start.
							 *
							 * For actions that span a period of time, when the action was performed
							 * (e.g., John wrote a book from January to December).
							 *
							 * For media, including audio  and video, it's the time offset of the start of a
							 * clip within a larger file.
							 *
							 * Note that Event uses startDate/endDate instead of startTime/endTime, even when
							 * describing dates with times. This situation may be clarified in future
							 * revisions.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - DateTime
							 *      - Time
							 *
							 * Used on these types:
							 *
							 *      - Action (Thing > Action)
							 *      - FoodEstablishmentReservation (Thing > Intangible > Reservation > FoodEstablishmentReservation)
							 *      - InteractionCounter (Thing > Intangible > StructuredValue > InteractionCounter)
							 *      - MediaObject (Thing > CreativeWork > MediaObject)
							 *      - Schedule (Thing > Intangible > Schedule)
							 *
							 * Note: Since this property would only be relevant to a clip within a larger
							 * MediaObject (video or audio), it is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// subjectOf [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// targetPlatform [excluded; irrelevant]

							/**
							 * Type of app development: phone, Metro style, desktop, XBox, etc.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - APIReference (Thing > CreativeWork > Article > TechArticle > APIReference)
							 *
							 * Note: This schema property is not relevant to clinical resources or their
							 * webpages, and so it will not be included.
							 */

						// teaches [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// temporal [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// temporalCoverage [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
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

						// thumbnail

							/**
							 * Thumbnail image for an image or video.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - ImageObject
							 *
							 * Used on these types:
							 *
							 *     - CreativeWork
							 */

							if (
								(
									isset($clinical_resource_item_MedicalWebPage)
									&&
									in_array(
										'thumbnail',
										$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
									)
								)
								||
								(
									isset($clinical_resource_item_CreativeWork)
									&&
									in_array(
										'thumbnail',
										$clinical_resource_properties_map[$CreativeWork_type]['properties']
									)
								)
							) {

								// Get values

									$clinical_resource_thumbnail = $clinical_resource_asset_thumbnail ?: $clinical_resource_thumbnail;

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'thumbnail', // string // Required // Name of schema property
											$clinical_resource_thumbnail, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'thumbnail', // string // Required // Name of schema property
												$clinical_resource_thumbnail, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// thumbnailUrl [WIP]

							/**
							 * A thumbnail image relevant to the Thing.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - URL
							 *
							 * Used on these types:
							 *
							 *      - CreativeWork
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         A URL pointing to the video thumbnail image file. Follow the thumbnail image
							 *         guidelines
							 *         [https://developers.google.com/search/docs/appearance/video#video-thumbnail].
							 */

							/*

								Check if fallback value is needed for each clinical resource type if the post
								thumbnail does not exist.

							*/

						// timeRequired

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

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'timeRequired',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'timeRequired',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									// Count words

										// Base value

											$clinical_resource_word_count = 0;

										// Introduction / Description

											if ( $clinical_resource_introduction ) {

												$clinical_resource_introduction_count = str_word_count($clinical_resource_introduction);
												$clinical_resource_word_count = $clinical_resource_word_count + $clinical_resource_introduction_count;

											}

										// Article body

											$clinical_resource_articleBody_count = str_word_count($clinical_resource_articleBody);
											$clinical_resource_word_count = $clinical_resource_word_count + $clinical_resource_articleBody_count;

										// Video transcript

											$clinical_resource_transcript_count = str_word_count($clinical_resource_transcript);
											$clinical_resource_word_count = $clinical_resource_word_count + $clinical_resource_transcript_count;

										// Infographic transcript

											$clinical_resource_embeddedTextCaption_count = str_word_count($clinical_resource_embeddedTextCaption);
											$clinical_resource_word_count = $clinical_resource_word_count + $clinical_resource_embeddedTextCaption_count;

									// Calculate time to read all words

										$wpm = 214; // National average for optimal silent reading rate for 9th grade, as words per minute (Hasbrouck & Tindal, 2006)
										$wps = $wps ?? $wpm / 60; // National average for optimal silent reading rate for 9th grade, as words per second (Hasbrouck & Tindal, 2006)

										$clinical_resource_timeRequired_seconds = $clinical_resource_word_count ? ( $clinical_resource_word_count / $wps ) : '';
										$clinical_resource_timeRequired = $clinical_resource_timeRequired_seconds ? uamswp_fad_iso8601_duration($clinical_resource_timeRequired_seconds) : '';

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'timeRequired', // string // Required // Name of schema property
											$clinical_resource_timeRequired, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'timeRequired', // string // Required // Name of schema property
												$clinical_resource_timeRequired, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// transcript

							/**
							 * If this MediaObject is an AudioObject or VideoObject, the transcript of that
							 * object.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - AudioObject
							 *      - VideoObject
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'transcript',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'transcript',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_transcript = get_field( 'clinical_resource_video_transcript', $entity ) ?? '';

								// Clean up values

									if ( $clinical_resource_transcript ) {

										$clinical_resource_transcript = wp_strip_all_tags($clinical_resource_transcript);
										$clinical_resource_transcript = str_replace("\n", ' ', $clinical_resource_transcript); // Strip line breaks
										$clinical_resource_transcript = uamswp_attr_conversion($clinical_resource_transcript);

									}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'transcript', // string // Required // Name of schema property
											$clinical_resource_transcript, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'transcript', // string // Required // Name of schema property
												$clinical_resource_transcript, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// translationOfWork [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// translator [excluded]

							/**
							 * Organization or person who adapts a creative work to different languages,
							 * regional differences and technical requirements of a target market, or that
							 * translates during some event.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Organization
							 *      - Person
							 *
							 * We will not be identifying either the organization or the person who translates
							 * the clinical resources into different languages and so this schema property
							 * will not be included.
							 */

						// typicalAgeRange [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// uploadDate (CreativeWork only) [WIP]

							/**
							 * Date when this media object was uploaded to this site.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Date
							 *      - DateTime (preferred by Google)
							 *
							 * Used on these types:
							 *
							 *      - MediaObject
							 *
							 * Google Search Central documentation:
							 *
							 *     Video (VideoObject, Clip, BroadcastEvent) structured data:
							 *
							 *         DateTime
							 *
							 *         The date and time the video was first published, in ISO 8601 format
							 *         [https://en.wikipedia.org/wiki/ISO_8601]. We recommend that you provide timezone
							 *         information; otherwise, we will default to the timezone used by Googlebot
							 *         [https://developers.google.com/search/docs/crawling-indexing/googlebot#timezone].
							 */

							/*

								Use get_post_time( 'c', false, $post ), where $post is the ID of the clinical
								resource's media asset (i.e., infographic image, digital document file).

							*/

						// usageInfo [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// version [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// video (MedicalWebPage only) [WIP]

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

							/*

								If the clinical resource is the 'Video' type, set the property value using that
								clinical resource's VideoObject schema item.

							*/

						// videoFrameSize

							/**
							 * The frame size of the video.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - VideoObject
							 */

							// CreativeWork (asset-agnostic)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'videoFrameSize',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Add to item values

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'videoFrameSize', // string // Required // Name of schema property
												$clinical_resource_asset_videoFrameSize, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

								}

						// videoQuality

							/**
							 * The quality of the video.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Text
							 *
							 * Used on these types:
							 *
							 *      - VideoObject
							 */

							// CreativeWork (asset-agnostic)

								if (
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'videoQuality',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
									&&
									$nesting_level == 0
								) {

									// Add to item values

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'videoQuality', // string // Required // Name of schema property
												$clinical_resource_asset_videoQuality, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

								}

						// width

							/**
							 * The width of the item.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Distance
							 *      - QuantitativeValue
							 *
							 * Used on these types:
							 *
							 *      - MediaObject
							 *      - OfferShippingDetails
							 *      - Product
							 *      - VisualArtwork
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'foo',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'foo',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_width = $clinical_resource_asset_width ?? '';
									$clinical_resource_width = $clinical_resource_asset_width ? $clinical_resource_asset_width . ' px' : '';

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'foo', // string // Required // Name of schema property
											$clinical_resource_foo, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'foo', // string // Required // Name of schema property
												$clinical_resource_foo, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// wordCount

							/**
							 * The number of words in the text of the Article.
							 *
							 * Values expected to be one of these types:
							 *
							 *      - Integer
							 *
							 * Used on these types:
							 *
							 *      - Article
							 */

							if (
								(
									(
										isset($clinical_resource_item_MedicalWebPage)
										&&
										in_array(
											'wordCount',
											$clinical_resource_properties_map[$MedicalWebPage_type]['properties']
										)
									)
									||
									(
										isset($clinical_resource_item_CreativeWork)
										&&
										in_array(
											'wordCount',
											$clinical_resource_properties_map[$CreativeWork_type]['properties']
										)
									)
								)
								&&
								$nesting_level == 0
							) {

								// Get values

									$clinical_resource_wordCount = $clinical_resource_articleBody_count ?? '';

									// Fallback value

										if ( !$clinical_resource_wordCount ) {

											$clinical_resource_wordCount = ( isset($clinical_resource_articleBody) && !empty($clinical_resource_articleBody) ) ? str_word_count($clinical_resource_articleBody) : '';

										}

								// Add to item values

									// MedicalWebPage

										uamswp_fad_schema_add_to_item_values(
											$MedicalWebPage_type, // string // Required // The @type value for the schema item
											$clinical_resource_item_MedicalWebPage, // array // Required // The list array for the schema item to which to add the property value
											'wordCount', // string // Required // Name of schema property
											$clinical_resource_wordCount, // mixed // Required // Variable to add as the property value
											$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
											$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
											($nesting_level + 1) // int // Required // Current nesting level value
										);

									// CreativeWork (asset-agnostic)

										foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

											uamswp_fad_schema_add_to_item_values(
												$CreativeWork_type, // string // Required // The @type value for the schema item
												$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
												'wordCount', // string // Required // Name of schema property
												$clinical_resource_wordCount, // mixed // Required // Variable to add as the property value
												$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
												$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
												($nesting_level + 1) // int // Required // Current nesting level value
											);

										}

							}

						// workExample [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// workTranslation [excluded; common properties]

							/**
							 * Note: The value for this property is already being defined in the common schema
							 * properties (templates/parts/vars/page/schema/common/properties.php)
							 */

						// Value overrides for syndicated clinical resource content as CreativeWork (or its subtypes)

							// Query for whether or not the content is syndicated

								if ( !isset($clinical_resource_syndication_query) ) {

									$clinical_resource_syndication_query = get_field( 'clinical_resource_syndicated', $entity ) ?? false;

								}

							// Query for whether or not the content is syndicated from the National Cancer Institute

								if (
									$clinical_resource_syndication_query
									&&
									!isset($clinical_resource_nci_query)
								) {

									$clinical_resource_nci_query = get_field( 'clinical_resource_text_nci_query', $entity ) ?? false;

								}

							// Get the syndication source URL

								if (
									$clinical_resource_syndication_query
									&&
									!isset($clinical_resource_syndication_URL)
								) {

									$clinical_resource_syndication_URL = get_field( 'clinical_resource_syndication_url', $entity ) ?? null;

								}

							// Define the syndication source organization

								if ( $clinical_resource_syndication_query ) {

									$clinical_resource_syndication_Organization = array();
									$clinical_resource_syndication_author = array();
									$clinical_resource_syndication_contributor = array();
									$clinical_resource_syndication_copyrightHolder = array();
									$clinical_resource_syndication_copyrightNotice = array();
									$clinical_resource_syndication_copyrightYear = array();
									$clinical_resource_syndication_countryOfOrigin = array();
									$clinical_resource_syndication_creator = array();
									$clinical_resource_syndication_creditText = array();
									$clinical_resource_syndication_dateModified = array();
									$clinical_resource_syndication_datePublished = array();
									$clinical_resource_syndication_director = array();
									$clinical_resource_syndication_editor = array();
									$clinical_resource_syndication_funder = array();
									$clinical_resource_syndication_funding = array();
									$clinical_resource_syndication_maintainer = array();
									$clinical_resource_syndication_musicBy = array();
									$clinical_resource_syndication_producer = array();
									$clinical_resource_syndication_productionCompany = array();
									$clinical_resource_syndication_provider = array();
									$clinical_resource_syndication_publisher = array();
									$clinical_resource_syndication_publisherImprint = array();
									$clinical_resource_syndication_publishingPrinciples = array();
									$clinical_resource_syndication_recordedAt = array();
									$clinical_resource_syndication_sourceOrganization = array();
									$clinical_resource_syndication_sponsor = array();
									$clinical_resource_syndication_translator = array();

									// National Cancer Institute

										if ( $clinical_resource_nci_query ) {

											$clinical_resource_syndication_Organization = array(
												'@type' => 'ResearchOrganization',
												'name' => 'National Cancer Institute',
												'sameAs' => array(
													'http://id.loc.gov/authorities/names/n79107940', // Library of Congress Name Authority File entry for 'National Cancer Institute (U.S.)'
													'https://www.wikidata.org/wiki/Q664846' // Wikidata entry for 'National Cancer Institute'
												),
												'url' => 'https://www.cancer.gov/'
											);

											$clinical_resource_syndication_sourceOrganization = $clinical_resource_syndication_Organization;

										}

								}

							// Define the property values

								if ( $clinical_resource_syndication_query ) {

									// author (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'author',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'author', // string // Required // Name of schema property
															$clinical_resource_syndication_author, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// contributor (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'contributor',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'contributor', // string // Required // Name of schema property
															$clinical_resource_syndication_contributor, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// copyrightHolder (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'copyrightHolder',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'copyrightHolder', // string // Required // Name of schema property
															$clinical_resource_syndication_copyrightHolder, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// copyrightNotice (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'copyrightNotice',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'copyrightNotice', // string // Required // Name of schema property
															$clinical_resource_syndication_copyrightNotice, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// copyrightYear (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'copyrightYear',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'copyrightYear', // string // Required // Name of schema property
															$clinical_resource_syndication_copyrightYear, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// countryOfOrigin (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'countryOfOrigin',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'countryOfOrigin', // string // Required // Name of schema property
															$clinical_resource_syndication_countryOfOrigin, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// creator (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'creator',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'creator', // string // Required // Name of schema property
															$clinical_resource_syndication_creator, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// creditText (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'creditText',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'creditText', // string // Required // Name of schema property
															$clinical_resource_syndication_creditText, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// dateModified (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'dateModified',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'dateModified', // string // Required // Name of schema property
															$clinical_resource_syndication_dateModified, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// datePublished (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'datePublished',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'datePublished', // string // Required // Name of schema property
															$clinical_resource_syndication_datePublished, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// director (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'director',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'director', // string // Required // Name of schema property
															$clinical_resource_syndication_director, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// editor (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'editor',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'editor', // string // Required // Name of schema property
															$clinical_resource_syndication_editor, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// funder (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'funder',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'funder', // string // Required // Name of schema property
															$clinical_resource_syndication_funder, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// funding (CreativeWork, syndicated only)

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
										 */

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'funding',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'funding', // string // Required // Name of schema property
															$clinical_resource_syndication_funding, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// maintainer (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'maintainer',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'maintainer', // string // Required // Name of schema property
															$clinical_resource_syndication_maintainer, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// musicBy (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'musicBy',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'musicBy', // string // Required // Name of schema property
															$clinical_resource_syndication_musicBy, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// producer (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'producer',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'producer', // string // Required // Name of schema property
															$clinical_resource_syndication_producer, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// productionCompany (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'productionCompany',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'productionCompany', // string // Required // Name of schema property
															$clinical_resource_syndication_productionCompany, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// provider (CreativeWork, syndicated only)

										/**
										 * The service provider, service operator, or service performer; the goods
										 * producer.
										 *
										 * Another party (a seller) may offer those services or goods on behalf of the
										 * provider.
										 *
										 * A provider may also serve as the seller.
										 *
											* Values expected to be one of these types:
											*
											*      - Organization
											*      - Person
											*/

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'provider',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'provider', // string // Required // Name of schema property
															$clinical_resource_syndication_provider, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// publisher (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'publisher',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'publisher', // string // Required // Name of schema property
															$clinical_resource_syndication_publisher, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// publisherImprint (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'publisherImprint',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'publisherImprint', // string // Required // Name of schema property
															$clinical_resource_syndication_publisherImprint, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// publishingPrinciples (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'publishingPrinciples',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'publishingPrinciples', // string // Required // Name of schema property
															$clinical_resource_syndication_publishingPrinciples, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// recordedAt (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'recordedAt',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'recordedAt', // string // Required // Name of schema property
															$clinical_resource_syndication_recordedAt, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// sameAs (CreativeWork, syndicated only)

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
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'sameAs',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Get values

												// Existing/base array

													$clinical_resource_sameAs = $clinical_resource_sameAs ?? array();

												// Merge in the syndication URL value

													$clinical_resource_syndication_URL = $clinical_resource_syndication_URL ?? null;

													if ( $clinical_resource_syndication_URL ) {

														$clinical_resource_sameAs = uamswp_fad_schema_merge_values(
															$clinical_resource_sameAs, // mixed // Required // Initial schema item property value
															$clinical_resource_syndication_URL // mixed // Required // Incoming schema item property value
														);

													}

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'sameAs', // string // Required // Name of schema property
															$clinical_resource_sameAs, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// sourceOrganization (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'sourceOrganization',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'sourceOrganization', // string // Required // Name of schema property
															$clinical_resource_syndication_sourceOrganization, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// sponsor (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'sponsor',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'sponsor', // string // Required // Name of schema property
															$clinical_resource_syndication_sponsor, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

									// translator (CreativeWork, syndicated only)

										if (
											(
												isset($clinical_resource_item_CreativeWork)
												&&
												in_array(
													'translator',
													$clinical_resource_properties_map[$CreativeWork_type]['properties']
												)
											)
										) {

											// Add to item values

												// CreativeWork (asset-agnostic)

													foreach ( $clinical_resource_item_CreativeWork as $CreativeWork ) {

														uamswp_fad_schema_add_to_item_values(
															$CreativeWork_type, // string // Required // The @type value for the schema item
															$CreativeWork, // array // Required // The list array for the schema item to which to add the property value
															'translator', // string // Required // Name of schema property
															$clinical_resource_syndication_translator, // mixed // Required // Variable to add as the property value
															$node_identifier_list, // array // Required // List of node identifiers (@id) already defined in the schema
															$clinical_resource_properties_map, // array // Required // Map array to match schema types with allowed properties
															($nesting_level + 1) // int // Required // Current nesting level value
														);

													}

										}

								}

					// Sort and combine the arrays

						if ( isset($clinical_resource_item_MedicalWebPage) ) {

							ksort( $clinical_resource_item_MedicalWebPage, SORT_NATURAL | SORT_FLAG_CASE );
							$clinical_resource_item['MedicalWebPage'] = $clinical_resource_item_MedicalWebPage;

						}

						if ( isset($clinical_resource_item_CreativeWork) ) {

							ksort( $clinical_resource_item_CreativeWork, SORT_NATURAL | SORT_FLAG_CASE );
							$clinical_resource_item['CreativeWork'] = $clinical_resource_item_CreativeWork;

						}

					// Set/update the value of the item transient

						uamswp_fad_set_transient(
							'item_' . $entity . ( $nesting_level ? '_nested-level-' . $nesting_level : '_root' ), // Required // String added to transient name for disambiguation.
							$clinical_resource_item, // Required // Transient value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
							__FUNCTION__ // Optional // Function name added to transient name for disambiguation.
						);

					// Add to list of clinical resources

						// Add to list of MedicalWebPage items

							if (
								isset($clinical_resource_item['MedicalWebPage'])
								&&
								!empty($clinical_resource_item['MedicalWebPage'])
							) {

								$MedicalWebPage_list[] = $clinical_resource_item['MedicalWebPage'];

							}

						// Add to list of CreativeWork items

							if (
								isset($clinical_resource_item['CreativeWork'])
								&&
								!empty($clinical_resource_item['CreativeWork'])
							) {

								$CreativeWork_list[] = $clinical_resource_item['CreativeWork'];

							}

				}

			} // endforeach ( $repeater as $entity )

		// Clean up list arrays

			// MedicalWebPage

				$MedicalWebPage_list = array_filter($MedicalWebPage_list);
				$MedicalWebPage_list = array_values($MedicalWebPage_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($MedicalWebPage_list);

			// CreativeWork

				$CreativeWork_list = array_filter($CreativeWork_list);
				$CreativeWork_list = array_values($CreativeWork_list);

				// If there is only one item, flatten the multi-dimensional array by one step

					uamswp_fad_flatten_multidimensional_array($CreativeWork_list);

		// Combine lists for return

			// MedicalWebPage

				if ( $MedicalWebPage_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($clinical_resource_list['MedicalWebPage'])
							&&
							!empty($clinical_resource_list['MedicalWebPage'])
						) {

							$clinical_resource_list['MedicalWebPage'] = array_is_list($clinical_resource_list['MedicalWebPage']) ? $clinical_resource_list['MedicalWebPage'] : array($clinical_resource_list['MedicalWebPage']);

						}

					$clinical_resource_list['MedicalWebPage'] = $MedicalWebPage_list;

				}

			// CreativeWork

				if ( $CreativeWork_list ) {

					// Check if pre-existing list is an indexed array

						if (
							isset($clinical_resource_list['CreativeWork'])
							&&
							!empty($clinical_resource_list['CreativeWork'])
						) {

							$clinical_resource_list['CreativeWork'] = array_is_list($clinical_resource_list['CreativeWork']) ? $clinical_resource_list['CreativeWork'] : array($clinical_resource_list['CreativeWork']);

						}

					$clinical_resource_list['CreativeWork'] = $CreativeWork_list;

				} else {

					$clinical_resource_list['CreativeWork'] = null;

				}

	} // endif ( !empty($repeater) )

	return $clinical_resource_list;

}