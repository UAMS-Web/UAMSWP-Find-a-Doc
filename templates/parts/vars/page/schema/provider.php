<?php

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common_base.php' );

$schema_provider = $schema_common_base;

// Get Values

	// Provider URL
	$provider_url = 'https://uamshealth.com/provider/foo/'; // Replace with relevant URL

	// Provider Schema JSON Node IDs

		// Provider as MedicalWebPage
		$schema_provider_MedicalWebPage_id = $provider_url . '#MedicalWebPage';

		// BreadcrumbList
		$schema_provider_BreadcrumbList_id = $provider_url . '#BreadcrumbList';

		// Provider as Physician
		$schema_provider_Physician_id = $provider_url . '#Physician';

		// Provider as Person
		$schema_provider_Person_id = $provider_url . '#Person';

		// Provider name
		$schema_provider_name_id = $provider_url . '#Name';

		// Provider URL
		$schema_provider_url_id = $provider_url . '#URL';

	// Provider Date Modified
	$provider_date_modified = '2022-09-27'; // Replace with relevant date value in ISO 8601 date format.

	// Provider Date Published
	$provider_date_published = '2022-09-02'; // Replace with relevant date value in ISO 8601 date format.

	// Provider Excerpt / Description
	$provider_excerpt = 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin.'; // Replace 'foo' with excerpt / short description

	// Related ontology items as schema arrays

		// Related Locations

			$provider_related_location = array(
				'@id' => 'MedicalClinic', // or 'Hospital'
				'foo' => 'bar'
			);

		// Related Areas of Expertise

			$provider_related_expertise = array(
				'@id' => 'MedicalEntity',
				'foo' => 'bar'
			);

		// Related Clinical Resources

			$provider_related_clinical_resource = array(
				'@id' => 'Article', // or 'ImageObject' or 'DigitalDocument' or 'VideoObject'
				'foo' => 'bar'
			);

		// Related Conditions

			$provider_related_condition = array(
				'@id' => 'MedicalCondition', // or subtypes
				'foo' => 'bar'
			);

		// Related Treatments

			$provider_related_treatment = array(
				'@id' => 'MedicalProcedure', // or 'MedicalTest' or their subtypes
				'foo' => 'bar'
			);

	// Provider mentions

		// Base

			$provider_mentions = array(
				array(
					'@id' => $schema_provider_Physician_id
				),
				array(
					'@id' => $schema_provider_Person_id
				)
			);

		// Related Locations

			$provider_mentions[] = $provider_related_location;

		// Related Areas of Expertise

			$provider_mentions[] = $provider_related_expertise;

		// Related Clinical Resources

			$provider_mentions[] = $provider_related_clinical_resource;

		// Related Conditions

			$provider_mentions[] = $provider_related_condition;

		// Related Treatments

			$provider_mentions[] = $provider_related_treatment;

		// Remove empty items from the array

			$provider_mentions = array_filter($provider_mentions);

	// Provider name
	$provider_name = 'foo';

	// Provider portrait

		// Image ID
		$provider_portrait = 'foo';

		// Image Encoding Format
		$provider_encoding_format = get_post_mime_type( $provider_portrait ); // e.g., 'image/jpeg'

		// Image Values

			// 1:1 Aspect Ratio

				$provider_portrait_1_1 = wp_get_attachment_image_src( $provider_portrait, 'aspect-1-1' );
				$provider_portrait_1_1_url = $provider_portrait_1_1[0];
				$provider_portrait_1_1_width = $provider_portrait_1_1[1];
				$provider_portrait_1_1_height = $provider_portrait_1_1[2];
				$provider_portrait_1_1_size = '';

			// 3:4 Aspect Ratio

				$provider_portrait_3_4 = wp_get_attachment_image_src( $provider_portrait, 'aspect-3-4' );
				$provider_portrait_3_4_url = $provider_portrait_3_4[0];
				$provider_portrait_3_4_width = $provider_portrait_3_4[1];
				$provider_portrait_3_4_height = $provider_portrait_3_4[2];
				$provider_portrait_3_4_size = '';

			// 4:3 Aspect Ratio

				$provider_portrait_4_3 = wp_get_attachment_image_src( $provider_portrait, 'aspect-4-3' );
				$provider_portrait_4_3_url = $provider_portrait_4_3[0];
				$provider_portrait_4_3_width = $provider_portrait_4_3[1];
				$provider_portrait_4_3_height = $provider_portrait_4_3[2];
				$provider_portrait_4_3_size = '';

			// 16:9 Aspect Ratio

				$provider_portrait_16_9 = wp_get_attachment_image_src( $provider_portrait, 'aspect-16-9' );
				$provider_portrait_16_9_url = $provider_portrait_16_9[0];
				$provider_portrait_16_9_width = $provider_portrait_16_9[1];
				$provider_portrait_16_9_height = $provider_portrait_16_9[2];
				$provider_portrait_16_9_size = '';

		// Image Objects

			// Base object

				$provider_portrait_image_object_base = array(
					'@type' => 'ImageObject',
					'caption' => $provider_name,
					'encodingFormat' => $provider_encoding_format,
					'representativeOfPage' => true,
				);

			// 1:1 Aspect Ratio

				$provider_portrait_image_object[] = ksort(
					array_filter(
						array_merge(
							$provider_portrait_image_object_base,
							array(
								'contentSize' => $provider_portrait_1_1_size,
								'contentUrl' => $provider_portrait_url_1_1,
								'height' => $provider_portrait_width_1_1,
								'width' => $provider_portrait_height_1_1
							)
						)
					)
				);

			// 3:4 Aspect Ratio

				$provider_portrait_image_object[] = ksort(
					array_filter(
						array_merge(
							$provider_portrait_image_object_base,
							array(
								'contentSize' => $provider_portrait_3_4_size,
								'contentUrl' => $provider_portrait_url_3_4,
								'height' => $provider_portrait_width_3_4,
								'width' => $provider_portrait_height_3_4
							)
						)
					)
				);

			// 4:3 Aspect Ratio

				$provider_portrait_image_object[] = ksort(
					array_filter(
						array_merge(
							$provider_portrait_image_object_base,
							array(
								'contentSize' => $provider_portrait_4_3_size,
								'contentUrl' => $provider_portrait_url_4_3,
								'height' => $provider_portrait_width_4_3,
								'width' => $provider_portrait_height_4_3
							)
						)
					)
				);

			// 16:9 Aspect Ratio

				$provider_portrait_image_object[] = ksort(
					array_filter(
						array_merge(
							$provider_portrait_image_object_base,
							array(
								'contentSize' => $provider_portrait_16_9_size,
								'contentUrl' => $provider_portrait_url_16_9,
								'height' => $provider_portrait_width_16_9,
								'width' => $provider_portrait_height_16_9
							)
						)
					)
				);

	// Provider featured video
	$provider_video = 'foo';

	// Provider ratings and reviews

		$provider_aggregateRating_itemReviewed = array(
			'@id' => $schema_provider_BreadcrumbList_id
		);
		$provider_aggregateRating_ratingCount = 'foo'; // Replace 'foo' with relevant value // Integer (Data Type)
		$provider_aggregateRating_reviewCount = 'foo'; // Replace 'foo' with relevant value // Integer (Data Type)


// Schema JSON Item Arrays

	// Provider as MedicalWebPage

		$schema_provider_MedicalWebPage = array(
			'@type' => 'MedicalWebPage',
			'@id' => $schema_provider_MedicalWebPage_id,
			'name' => array(
				'@id' => $schema_provider_name_id
			),
			'headline' => array(
				'@id' => $schema_provider_name_id
			),
			'about' => array(
				'@id' => $schema_provider_Physician_id,
				'@id' => $schema_provider_Person_id
			),
			'breadcrumb' => array(
				'@id' => $schema_provider_BreadcrumbList_id
			),
			'creator' => array(
				'@id' => $schema_base_org_uams_id
			),
			'dateModified' => $provider_date_modified,
			'datePublished' => $provider_date_published,
			'description' => $provider_excerpt,
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => $schema_base_website_uams_health_id
			),
			'mainEntity' => array(
				'@id' => $schema_provider_Person_id
			),
			'maintainer' => array(
				'@id' => $schema_base_org_uams_id
			),
			'medicalAudience' => array(
				array(
					'@type' => 'Patient',
					'geographicArea' => 'Arkansas'
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			),
			'mentions' => $provider_mentions,
			'primaryImageOfPage' => $provider_portrait_image_object,
			'significantLink' => array(
				'foo' // Replace 'foo' with URLs to related ontology items, repeating as necessary
			),
			'sourceOrganization' => array(
				'@id' => $schema_base_org_uams_health_id
			),
			'url' => array(
				'@id' => $schema_provider_url_id,
				$provider_url
			),
			'video' => $provider_video // Replace 'foo' with URL to featured video
		);

	// BreadcrumbList

		$schema_provider_BreadcrumbList = array(
			'@type' => 'BreadcrumbList',
			'@id' => $schema_provider_BreadcrumbList_id,
			'itemListElement' => array(
				array(
					'@type' => 'ListItem',
					'position' => 1,
					'item' => array(
						'@type' => 'WebPage',
						'@id' => 'https://uamshealth.com',
						'url' => 'https://uamshealth.com',
						'name' => 'UAMS Health'
					)
				),
				array(
					'@type' => 'ListItem',
					'position' => 2,
					'item' => array(
						'@type' => 'WebPage',
						'@id' => 'https://uamshealth.com/provider/',
						'url' => 'https://uamshealth.com/provider/',
						'name' => 'Providers'
					)
					),
				array(
					'@type' => 'ListItem',
					'position' => 3,
					'item' => array(
						'@id' => $schema_provider_MedicalWebPage_id
					)
				)
			)
		);

	// Provider as Physician

		$schema_provider_Physician = (
			'@type' => 'Physician',
			'@id' => $schema_provider_Physician_id,
			'name' => array(
				'@id' => $schema_provider_name_id
			),
			'aggregateRating' => array(
				array(
					'@type' => 'AggregateRating',
					'itemReviewed' => $provider_aggregateRating_itemReviewed,
					'ratingCount' => $provider_aggregateRating_ratingCount,
					'reviewCount' => $provider_aggregateRating_reviewCount
				)
			),
			'availableService' => array(
				array( // Repeat as necessary
					'@type' => 'MedicalProcedure', // Replace 'MedicalProcedure' with more specific type if relevant
					'name' => 'foo', // Replace 'foo' with name of associated procedure
					'foo' => 'bar' // Replace 'foo' and 'bar' with necessary property/value pairs, adding more if necessary
				),
				array( // Repeat as necessary
					'@type' => 'MedicalTest', // Replace 'MedicalProcedure' with more specific type if relevant
					'name' => 'foo', // Replace 'foo' with name of associated test
					'foo' => 'bar' // Replace 'foo' and 'bar' with necessary property/value pairs, adding more if necessary
				)
			),
			'brand' => array(
				'@id' => $schema_base_org_uams_health_id
			),
			'hospitalAffiliation' => array(
				array( // Repeat for all associated locations
					'@type' => 'Hospital',
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'State',
						'name' => 'Arkansas',
						'sameAs' => 'https://www.wikidata.org/wiki/Q1612'
					),
					'brand' => array(
						'@id' => $schema_base_org_uams_health_id // Replace array with relevant Organization (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => '23:59',
							'dayOfWeek' => array(
								'Monday',
								'Tuesday',
								'Wednesday',
								'Thursday',
								'Friday',
								'Saturday',
								'Sunday'
							),
							'opens' => '00:00'
						)
					),
					'parentOrganization' => array(
						'@id' => $schema_base_org_uams_health_id // Replace array with relevant Organization (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with the image's alt text
							'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
							'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
							'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
							'height' => 'foo', // Replace 'foo' with the image's height
							'representativeOfPage' => false,
							'width' => 'foo' // Replace 'foo' with the image's width
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'isAcceptingNewPatients' => 'foo', // Boolean (Data Type)
			'location' => array(
				array( // Repeat for all associated locations
					'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'State',
						'name' => 'Arkansas',
						'sameAs' => 'https://www.wikidata.org/wiki/Q1612'
					),
					'brand' => array( // Keep if a UAMS location
						'@id' => $schema_base_org_uams_health_id
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'parentOrganization' => array( // Keep if a UAMS location
						'@id' => $schema_base_org_uams_health_id
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with the image's alt text
							'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
							'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
							'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
							'height' => 'foo', // Replace 'foo' with the image's height
							'representativeOfPage' => false,
							'width' => 'foo' // Replace 'foo' with the image's width
						)
					),
					'specialOpeningHoursSpecification' => array( // The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'medicalSpecialty' => array(
				'foo' // Replace 'foo' with MedicalSpecialty (Enumeration Type) associated with the related area of expertise // Repeat as necessary
			),
			'parentOrganization' => array(
				'@id' => $schema_base_org_uams_health_id
			),
			'review' => array( // Include if NRC API allows for the content of the review to be loaded into this schema
				array(
					'@type' => 'Review',
					'foo' => 'bar' // Replace 'foo' and 'bar' with relevant property/value pairs as necessary
				)
			),
			'subjectOf' => array(
				'@id' => $schema_provider_MedicalWebPage_id
			),
			'url' => array(
				'@id' => $schema_provider_url_id
			),
		);

	// Provider as Person

		$schema_provider_Person = (
			'@type' => 'Person',
			'additionalType' => 'https://www.wikidata.org/wiki/Q11974939', // health professional (Q11974939)
			'@id' => $schema_provider_Person_id,
			'name' => array(
				'@id' => $schema_provider_name_id,
				'foo', // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
			)
			'affiliation' => array(
				'@id' => $schema_base_org_uams_health_id
			),
			'alumniOf' => array(
				array( // Repeat as necessary
					'@type' => 'EducationalOrganization', // Replace with more specific type as relevant
					'name' => 'foo' // Replace 'foo' with name of the organization from which the provider received education/training
				)
			),
			'brand' => array(
				'@id' => $schema_base_org_uams_health_id
			),
			'description' => 'foo', // Replace 'foo' with provider's clinical short bio // Text (Data Type)
			'familyName' => 'foo', // Replace 'foo' with provider's last name // Text (Data Type)
			'gender' => 'foo', // Replace 'foo' with provider's gender // Text (Data Type)
			'givenName' 'foo', // Replace 'foo' with provider's first name // Text (Data Type)
			'hasCredential' => array(
				array( // Repeat as necessary
					'@type' => 'EducationalOccupationalCredential',
					'name' => 'foo' // Full name of degree or credential (e.g., 'Doctor of Medicine')
				)
			),
			'honorificPrefix' => 'foo', // Replace 'foo' with 'Dr.' if relevant // Text (Data Type)
			'honorificSuffix' => 'foo', // Replace 'foo' with provider's degree list // Text (Data Type)
			'image' => array( // Provider headshot
				array( // Provider headshot (3:4 aspect ratio)
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				),
				array( // Provider headshot (1:1 aspect ratio)
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				),
				array( // Provider wide image (if available)
					'@type' => 'ImageObject',
					'caption' => 'foo', // Replace 'foo' with the image's alt text
					'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
					'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
					'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
					'height' => 'foo', // Replace 'foo' with the image's height
					'representativeOfPage' => true,
					'width' => 'foo' // Replace 'foo' with the image's width
				)
			),
			'jobTitle' => array(
				'foo' // Replace 'foo' with provider's clinical title (e.g., 'Orthopaedic surgeon') // Repeat as necessary // Text (Data Type)
			),
			'knowsLanguage' => array(
				array( // Repeat as necessary
					'@type' => 'Language',
					'name' => 'foo' // Replace 'foo' with provider's language
				)
			),
			'mainEntityOfPage' => array(
				'@id' => $schema_provider_MedicalWebPage_id
			),
			'memberOf' => array(
				array( // Repeat as necessary
					'@type' => 'Organization',
					'name' => 'foo' // Replace 'foo' with provider's association organization
				)
			),
			'subjectOf' => array(
				'@id' => $schema_provider_MedicalWebPage_id
			),
			'url' => array(
				'@id' => $schema_provider_url_id
			),
			'workLocation' => array(
				array( // Repeat for all associated locations
					'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
					'name' => 'foo', // Replace 'foo' with location name
					'address' => array(
						'@type' => 'PostalAddress',
						'addressCountry' = 'USA',
						'addressLocality' = 'foo', // Replace 'foo' with city
						'addressRegion' = 'Arkansas',
						'postalCode' = 'foo', // Replace 'foo' with ZIP code
						'streetAddress' = 'foo' // Replace 'foo' with street address
					),
					'areaServed' => array(
						'@type' => 'State',
						'name' => 'Arkansas',
						'sameAs' => 'https://www.wikidata.org/wiki/Q1612'
					),
					'brand' => array( // Keep if a UAMS location
						'@id' => $schema_base_org_uams_health_id
					),
					'contactPoint' => array(
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'General information',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Appointments for new and existing patients',
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'foo', // Replace 'foo' with additional phone number label
							'telephone' => 'foo' // Replace 'foo' with phone number
						),
						array(
							'@type' => 'ContactPoint',
							'contactType' => 'Fax',
							'faxNumber' => 'foo' // Replace 'foo' with fax number
						),
					),
					'description' => 'foo', // Replace 'foo' with location description
					'geo' => array(
						'@type' => 'GeoCoordinates',
						'latitude' => 'foo', // Replace 'foo' with latitude
						'longitude' => 'foo' // Replace 'foo' with longitude
					),
					'openingHoursSpecification' => array( // The opening hours of a certain place.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'parentOrganization' => array( // Keep if a UAMS location
						'@id' => $schema_base_org_uams_health_id
					),
					'photo' => array(
						array( // Repeat for all photos include in location profile
							'@type' => 'ImageObject',
							'caption' => 'foo', // Replace 'foo' with the image's alt text
							'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
							'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
							'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
							'height' => 'foo', // Replace 'foo' with the image's height
							'representativeOfPage' => true,
							'width' => 'foo' // Replace 'foo' with the image's width
						)
					),
					'specialOpeningHoursSpecification' => array( // The special opening hours of a certain place. Use this to explicitly override general opening hours brought in scope by openingHoursSpecification or openingHours.
						array( // Repeat as necessary
							'@type' => 'OpeningHoursSpecification',
							'closes' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'dayOfWeek' => 'foo', // Replace 'foo' necessary value // DayOfWeek (Enumeration Type)
							'opens' => 'foo', // Replace 'foo' necessary value // Time (Data Type)
							'validFrom' => 'foo', // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
							'validThrough' => 'foo' // Replace 'foo' necessary value // Date (Data Type) or DateTime (Data Type)
						)
					),
					'url' => 'foo' // Replace 'foo' with location profile URL
				)
			),
			'worksFor' => array(
				'@id' => $schema_base_org_uams_health_id
			)
		);

// Add Provider Schema Arrays to Base Array

	// Provider as MedicalWebPage
	$schema_provider['@graph'][] = $schema_provider_MedicalWebPage;

	// BreadcrumbList
	$schema_provider['@graph'][] = $schema_provider_BreadcrumbList;

	// Provider as Physician
	$schema_provider['@graph'][] = $schema_provider_Physician;

	// Provider as Person
	$schema_provider['@graph'][] = $schema_provider_Person;