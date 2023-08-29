<?php

/*
 *
 * Required vars:
 * 	$full_name_attr
 * 	$last_name_attr
 * 	$first_name_attr
 * 	$prefix_attr
 * 	$degree_array
 * 	$gender_attr
 * 	$excerpt_attr
 * 	$rating_valid
 * 	$accept_new
 * 	$review_count
 * 	$avg_rating
 * 	$comment_count
 * 	$accept_new
 * 	$video
 * 	$language_attr_array
 * 	$featured_image
 * 	$headshot_wide
 * 
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base.php' );

$schema_provider = $schema_common_base;

// Get Values

	// ISCO-08

		$isco_08 = array(
			'2' => array(
				'name' => 'Professionals',
				'description' => 'Professionals increase the existing stock of knowledge; apply scientific or artistic concepts and theories; teach about the foregoing in a systematic manner; or engage in any combination of these activities. Competent performance in most occupations in this major group requires skills at the fourth ISCO skill level.',
				'sameAs' => array()
			),
			'22' => array(
				'name' => 'Health Professionals',
				'description' => 'Health professionals conduct research,; improve or develop concepts, theories and operational methods; and apply scientific knowledge relating to medicine, nursing, dentistry, veterinary medicine, pharmacy, and promotion of health.  Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
				'sameAs' => array(
					'2'
				)
			),
			'221' => array(
				'name' => 'Medical Doctors',
				'description' => 'Medical doctors (physicians) study, diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans through the application of the principles and procedures of modern medicine. They plan, supervise and evaluate the implementation of care and treatment plans by other health care providers, and conduct medical education and research activities.',
				'sameAs' => array(
					'2',
					'22'
				)
			),
			'2211' => array(
				'name' => 'Generalist Medical Practitioners',
				'description' => 'Generalist medical practitioners (including family and primary care doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments and maintain general health in humans through application of the principles and procedures of modern medicine.  They do not limit their practice to certain disease categories or methods of treatment, and may assume responsibility for the provision of continuing and comprehensive medical care to individuals, families and communities.',
				'sameAs' => array(
					'2',
					'22',
					'221'
				)
			),
			'2212' => array(
				'name' => 'Specialist Medical Practitioners',
				'description' => 'Specialist medical practitioners (medical doctors) diagnose, treat and prevent illness, disease, injury and other physical and mental impairments in humans, using specialized testing, diagnostic, medical, surgical, physical and psychiatric techniques through application of the principles and procedures of modern medicine. They specialize in certain disease categories, types of patient or methods of treatment and may conduct medical education and research in their chosen areas of specialization.',
				'sameAs' => array(
					'2',
					'22',
					'221'
				)
			),
			'222' => array(
				'name' => 'Nursing and Midwifery Professionals',
				'description' => 'Nursing and midwifery professionals provide treatment and care services for people who are physically or mentally ill, disabled or infirm, and others in need of care due to potential risks to health including before, during and after childbirth. They assume responsibility for the planning, management and evaluation of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures. ',
				'sameAs' => array(
					'2',
					'22'
				)
			),
			'2221' => array(
				'name' => 'Nursing Professionals',
				'description' => 'Nursing professionals provide treatment, support and care services for people who are in need of nursing care due to the effects of ageing, injury, illness or other physical or mental impairment, or potential risks to health. They assume responsibility for the planning and management of the care of patients, including the supervision of other health care workers, working autonomously or in teams with medical doctors and others in the practical application of preventive and curative measures.',
				'sameAs' => array(
					'2',
					'22',
					'222'
				)
			),
			'2222' => array(
				'name' => 'Midwifery Professionals',
				'description' => 'Midwifery professionals plan, manage, provide and evaluate midwifery care services before, during and after pregnancy and childbirth. They provide delivery care for reducing health risks to women and newborn children, working autonomously or in teams with other health care providers.',
				'sameAs' => array(
					'2',
					'22',
					'222'
				)
			),
			'223' => array(
				'name' => 'Traditional and Complementary Medicine Professionals',
				'description' => 'Traditional and complementary medicine professionals examine patients; prevent and treat illness, disease, injury and other physical and mental impairments; and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences originating in specific cultures.',
				'sameAs' => array(
					'2',
					'22'
				)
			),
			'2230' => array(
				'name' => 'Traditional and Complementary Medicine Professionals',
				'description' => 'Traditional and complementary medicine professionals examine patients, prevent and treat illness, disease, injury and other physical and mental impairments and maintain general health in humans by applying knowledge, skills and practices acquired through extensive study of  the theories, beliefs and experiences, originating in specific cultures.',
				'sameAs' => array(
					'2',
					'22',
					'223'
				)
			),
			'224' => array(
				'name' => 'Paramedical Practitioners',
				'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
				'sameAs' => array(
					'2',
					'22'
				)
			),
			'2240' => array(
				'name' => 'Paramedical Practitioners',
				'description' => 'Paramedical practitioners provide advisory, diagnostic, curative and preventive medical services more limited in scope and complexity than those carried out by medical doctors. They work autonomously, or with limited supervision of medical doctors, and apply advanced clinical procedures for treating and preventing diseases, injuries and other physical or mental impairments common to specific communities.',
				'sameAs' => array(
					'2',
					'22',
					'224'
				)
			),
			'225' => array(
				'name' => 'Veterinarians',
				'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals; specialize in the treatment of a particular animal group or in a particular area of specialization; or provide professional services to commercial firms producing biological and pharmaceutical products.',
				'sameAs' => array(
					'2',
					'22'
				)
			),
			'2250' => array(
				'name' => 'Veterinarians',
				'description' => 'Veterinarians diagnose, prevent and treat diseases, injuries and dysfunctions of animals. They may provide care to a wide range of animals or specialize in the treatment of a particular animal group or in a particular specialty area, or provide professional services to commercial firms producing biological and pharmaceutical products.',
				'sameAs' => array(
					'2',
					'22',
					'225'
				)
			),
			'226' => array(
				'name' => 'Other Health Professionals',
				'description' => 'Other health professionals provide health services related to dentistry, pharmacy, environmental health and hygiene, occupational health and safety, physiotherapy, nutrition, hearing, speech, vision and rehabilitation therapies.  This minor group includes all human health professionals except doctors, traditional and complementary medicine practitioners, nurses, midwives and paramedical professionals.',
				'sameAs' => array(
					'2',)
			),
			'2261' => array(
				'name' => 'Dentists',
				'description' => 'Dentists diagnose, treat and prevent diseases, injuries and abnormalities of the teeth, mouth, jaws and associated tissues by applying the principles and procedures of modern dentistry. They use a broad range of specialized diagnostic, surgical and other techniques to promote and restore oral health.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2262' => array(
				'name' => 'Pharmacists',
				'description' => 'Pharmacists store, preserve, compound and dispense medicinal products and counsel on the proper use and adverse effects of drugs and medicines following prescriptions issued by medical doctors and other health professionals. They contribute to researching, testing, preparing, prescribing and monitoring medicinal therapies for optimizing human health.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2263' => array(
				'name' => 'Environmental and Occupational Health and Hygiene Professionals',
				'description' => 'Environmental and occupational health and hygiene professionals assess, plan and implement programmes to recognize, monitor and control environmental factors that can potentially affect human health, to ensure safe and healthy working conditions and to prevent disease or injury caused by chemical, physical, radiological and biological agents or ergonomic factors.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2264' => array(
				'name' => 'Physiotherapists',
				'description' => 'Physiotherapists assess, plan and implement rehabilitative programmes that improve or restore human motor functions, maximize movement ability, relieve pain syndromes, and treat or prevent physical challenges associated with injuries, diseases and other impairments. They apply a broad range of physical therapies and techniques such as movement, ultrasound, heating, laser and other techniques. ',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2265' => array(
				'name' => 'Dieticians and Nutritionists',
				'description' => 'Dieticians and nutritionists assess, plan and implement programmes to enhance the impact of food and nutrition on human health.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2266' => array(
				'name' => 'Audiologists and Speech Therapists',
				'description' => 'Audiologists and speech therapists evaluate, manage and treat physical disorders affecting human hearing, speech, communication and swallowing. They prescribe corrective devices or rehabilitative therapies for hearing loss, speech disorders and related sensory and neural problems, and provide counselling on hearing safety and communication performance.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2267' => array(
				'name' => 'Optometrists and Ophthalmic Opticians',
				'description' => 'Optometrists and ophthalmic opticians provide diagnosis, management and treatment services for disorders of the eyes and visual system. They counsel on eye care and prescribe optical aids or other therapies for visual disturbance.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			),
			'2269' => array(
				'name' => 'Health Professionals Not Elsewhere Classified ',
				'description' => 'This unit group covers health professionals not classified elsewhere in Sub-major Group 22: Health Professionals. For instance, the group includes occupations such as podiatrist, occupational therapist, recreational therapist, chiropractor, osteopath and other professionals providing diagnostic, preventive, curative and rehabilitative health services.',
				'sameAs' => array(
					'2',
					'22',
					'226'
				)
			)
		);


	// Provider URL

		$schema_provider_url = user_trailingslashit($page_url);

	// Related ontology items as schema arrays

		// Related Locations

			$provider_related_location = array(
				array(
					'@type' => 'MedicalClinic', // or 'Hospital'
					'foo' => 'bar'
				)
			);

		// Related Areas of Expertise

			$provider_related_expertise = array(
				array(
					'@type' => 'MedicalEntity',
					'foo' => 'bar'
				)
			);

		// Related Clinical Resources

			$provider_related_clinical_resource = array(
				array(
					'@type' => 'Article', // or 'ImageObject' or 'DigitalDocument' or 'VideoObject'
					'foo' => 'bar'
				)
			);

		// Related Conditions

			$provider_related_condition = array(
				array(
					'@type' => 'MedicalCondition', // or subtypes
					'foo' => 'bar'
				)
			);

		// Related Treatments

			$provider_related_treatment = array(
				array(
					'@type' => 'MedicalProcedure', // or 'MedicalTest' or their subtypes
					'foo' => 'bar'
				)
			);

	// Provider Medical Specialty (medicalSpecialty)

		$schema_provider_medicalSpecialty = array(
			'foo',
			'bar'
		);



	// Provider Hospital Affiliation (hospitalAffiliation)

		$schema_provider_hospitalAffiliation = array(
			array( // Repeat for all associated locations
				'@type' => 'Hospital',
				'name' => 'foo', // Replace 'foo' with location name
				'address' => array(
					'@type' => 'PostalAddress',
					'addressCountry' => 'USA',
					'addressLocality' => 'foo', // Replace 'foo' with city
					'addressRegion' => 'Arkansas',
					'postalCode' => 'foo', // Replace 'foo' with ZIP code
					'streetAddress' => 'foo' // Replace 'foo' with street address
				),
				'areaServed' => $schema_arkansas,
				'brand' => array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					$schema_base_org_uams_health_ref
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
				'parentOrganization' => array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					$schema_base_org_uams_health_ref
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
		);

	// Provider locations (location; workLocation)

		$schema_provider_location = array(
			array( // Repeat for all associated locations
				'@type' => 'MedicalClinic', // Replace 'MedicalClinic' with 'Hospital' if necessary
				'name' => 'foo', // Replace 'foo' with location name
				'address' => array(
					'@type' => 'PostalAddress',
					'addressCountry' => 'USA',
					'addressLocality' => 'foo', // Replace 'foo' with city
					'addressRegion' => 'Arkansas',
					'postalCode' => 'foo', // Replace 'foo' with ZIP code
					'streetAddress' => 'foo' // Replace 'foo' with street address
				),
				'areaServed' => $schema_arkansas,
				'brand' => array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					$schema_base_org_uams_health_ref
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
				'parentOrganization' => array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
					$schema_base_org_uams_health_ref
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
		);

		$schema_provider_workLocation = $schema_provider_location;


// Schema JSON Item Arrays

	// Provider as MedicalWebPage

		// Base array

			$schema_provider_MedicalWebPage = array(
				'@type' => 'MedicalWebPage'
			);

		// @id

			$schema_provider_MedicalWebPage['@id'] = $schema_provider_url . '#' . $schema_provider_MedicalWebPage['@type'];

			// Define reference to this 'MedicalWebPage' item

				$schema_provider_MedicalWebPage_ref['@id'] = $schema_provider_MedicalWebPage['@id'] ?: '';

		// name

			$schema_provider_MedicalWebPage['name'] = array(); // Defined later

		// headline

			$schema_provider_MedicalWebPage['headline'] = array(); // Defined later

		// about

			$schema_provider_MedicalWebPage['about'] = array(); // Defined later

		// breadcrumb

			$schema_provider_MedicalWebPage['breadcrumb'] = array(); // Defined after 'BreadcrumbList' item is defined

		// creator

			$schema_provider_MedicalWebPage['creator'] = $schema_base_org_uams_ref;

		// dateModified

			$schema_provider_MedicalWebPage['dateModified'] = get_the_modified_date( 'c', $page_id ); // ISO 8601 date format

		// datePublished

			$schema_provider_MedicalWebPage['datePublished'] = get_the_date( 'c', $page_id ); // ISO 8601 date format

		// description

			$schema_provider_MedicalWebPage['description'] = array(); // Defined later

		// inLanguage

			$schema_provider_MedicalWebPage['inLanguage'] = $schema_base_website_uams_health_inLanguage_ref;

		// isPartOf

			$schema_provider_MedicalWebPage['isPartOf'] = $schema_base_website_uams_health_ref;

		// mainEntity

			$schema_provider_MedicalWebPage['mainEntity'] = array(); // Defined later

		// maintainer

			$schema_provider_MedicalWebPage['maintainer'] = $schema_base_org_uams_ref;

		// medicalAudience

			$schema_provider_MedicalWebPage['medicalAudience'] = array(
				array(
					'@type' => 'Patient',
					'geographicArea' => $schema_arkansas
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			);

		// mentions

			// Related Locations

				$schema_provider_MedicalWebPage['mentions'] = $provider_related_location;

			// Related Areas of Expertise

				$schema_provider_MedicalWebPage['mentions'] = $provider_related_expertise;

			// Related Clinical Resources

				$schema_provider_MedicalWebPage['mentions'] = $provider_related_clinical_resource;

			// Related Conditions

				$schema_provider_MedicalWebPage['mentions'] = $provider_related_condition;

			// Related Treatments

				$schema_provider_MedicalWebPage['mentions'] = $provider_related_treatment;

			// Remove any empty items from the array

				$schema_provider_MedicalWebPage['mentions'] = array_filter($schema_provider_MedicalWebPage['mentions']);

		// primaryImageOfPage

			$schema_provider_MedicalWebPage['primaryImageOfPage'] = array(); // Defined later

		// significantLink

			$schema_provider_MedicalWebPage['significantLink'] = array(); // Add URLs of related ontology items, repeating as necessary

		// sourceOrganization

			$schema_provider_MedicalWebPage['sourceOrganization'] = $schema_base_org_uams_health_ref;

		// url

			$schema_provider_MedicalWebPage['url'] = array(
				'@id' => $schema_provider_url . '#URL',
				'url' => $schema_provider_url
			);

			// Define reference to this 'url' property

				$schema_provider_MedicalWebPage_url_ref['@id'] = $schema_provider_MedicalWebPage['url']['@id'] ?: '';

		// video

			$schema_provider_MedicalWebPage['video'] = $video;

	// BreadcrumbList

		$schema_provider_BreadcrumbList = array(
			'@type' => 'BreadcrumbList'
		);


		$schema_provider_BreadcrumbList['@id'] = $schema_provider_url . '#' . $schema_provider_BreadcrumbList['@type'];

		$schema_provider_BreadcrumbList['itemListElement'] = array(
			array(
				'@type' => 'ListItem',
				'position' => 1,
				'item' => array(
					'@type' => 'WebPage',
					'@id' => $schema_base_org_uams_health_url . '#ListItem',
					'url' => $schema_base_org_uams_health_url,
					'name' => 'UAMS Health'
				)
			),
			array(
				'@type' => 'ListItem',
				'position' => 2,
				'item' => array(
					'@type' => 'WebPage',
					'@id' => $schema_base_org_uams_health_url_trailingslashit . user_trailingslashit('provider') . '#ListItem',
					'url' => $schema_base_org_uams_health_url_trailingslashit . user_trailingslashit('provider'),
					'name' => 'Providers'
				)
				),
			array(
				'@type' => 'ListItem',
				'position' => 3,
				'item' => $schema_provider_MedicalWebPage_ref
			)
		);

		// Define 'BreadcrumbList' reference

			$schema_provider_BreadcrumbList_ref['@id'] = $schema_provider_BreadcrumbList['@id'] ?: '';

		// Set value of 'breadcrumb' property of 'MedicalWebPage' item with 'BreadcrumbList' reference

			$schema_provider_MedicalWebPage['breadcrumb'] = $schema_provider_BreadcrumbList_ref ?: '';

	// Provider as Physician

		// Base array
		
			$schema_provider_Physician = array(
				'@type' => 'Physician'
			);

		// @id

			$schema_provider_Physician['@id'] = $schema_provider_url . '#' . $schema_provider_Physician['@type'];

			// Define reference to this 'Physician' item

				$schema_provider_Physician_ref['@id'] = $schema_provider_Physician['@id'] ?: '';

			// Define a value of 'about' of 'MedicalWebPage' with this 'Physician' reference

				$schema_provider_MedicalWebPage['about'][] = $schema_provider_Physician_ref;

			// Define a value of 'mentions' of 'MedicalWebPage' with this 'Physician' reference

				$schema_provider_MedicalWebPage['mentions'][] = $schema_provider_Physician_ref;

		// name

			$schema_provider_Physician['name'] = array(); // Defined later

		// aggregateRating

			if ($rating_valid) {

				$schema_provider_Physician['aggregateRating'] = array(
					'@type' => 'AggregateRating',
					'description' => '', // Get description of the rating/review concept from Patient Experience.
					'itemReviewed' => $schema_provider_Physician_ref,
					'ratingCount' => $review_count,
					'ratingValue' => $avg_rating,
					'reviewAspect' => '', // Get info from Patient Experience about which facets of the provider is rated/reviewed.
					'reviewCount' => $comment_count
				);

			}

		// availableService

			$schema_provider_Physician['availableService'] = array(
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
			);

		// brand

			$schema_provider_Physician['brand'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref
			);

		// description

			$schema_provider_Physician['description'] = array(); // Defined later

		// hospitalAffiliation

			$schema_provider_Physician['hospitalAffiliation'] = $schema_provider_hospitalAffiliation;

		// isAcceptingNewPatients

			$schema_provider_Physician['isAcceptingNewPatients'] = $accept_new;

		// location

			$schema_provider_Physician['location'] = $schema_provider_location;

		// medicalSpecialty

			$schema_provider_Physician['medicalSpecialty'] = $schema_provider_medicalSpecialty;

		// parentOrganization

			$schema_provider_Physician['parentOrganization'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref
			);

		// review

			$schema_provider_Physician['review'] = array( // Include if NRC API allows for the content of the review to be loaded into this schema
				array(
					'@type' => 'Review',
					'foo' => 'bar' // Replace 'foo' and 'bar' with relevant property/value pairs as necessary
				)
			);

		// subjectOf

			$schema_provider_Physician['subjectOf'] = $schema_provider_MedicalWebPage_ref;

		// url

			$schema_provider_Physician['url'] = $schema_provider_MedicalWebPage_url_ref;

	// Provider as Person

		// Base array
		
			$schema_provider_Person = array(
				'@type' => 'Person'
			);

		// additionalType

			$schema_provider_Person['additionalType'] = 'https://www.wikidata.org/wiki/Q11974939'; // Mirror Wikidata value from Specialty // Default to health professional (Q11974939)

		// @id

			$schema_provider_Person['@id'] = $schema_provider_url . '#' . $schema_provider_Person['@type'];

			// Define reference to this 'Person' item

				$schema_provider_Person_ref['@id'] = $schema_provider_Person['@id'] ?: '';

			// Define the value of 'mainEntity' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['mainEntity'] = $schema_provider_Person_ref;
			
			// Define a value of 'about' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['about'][] = $schema_provider_Person_ref;

			// Define a value of 'mentions' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['mentions'][] = $schema_provider_Person_ref;

		// name

			$schema_provider_Person['name'] = array(
				'@id' => $schema_provider_url . '#Name',
				$full_name_attr, // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
			);

			// Define reference to this 'name' property

				$schema_provider_Person_name_ref['@id'] = $schema_provider_Person['name']['@id'] ?: '';

			// Define value of other 'name' properties with this 'name' reference

				// MedicalWebPage
				
					$schema_provider_MedicalWebPage['name'] = $schema_provider_Person_name_ref;

				// Physician
				
					$schema_provider_Physician['name'] = $schema_provider_Person_name_ref;

			// Define value of 'headline' of 'MedicalWebPage' with this 'name' reference
			
				$schema_provider_MedicalWebPage['headline'] = $schema_provider_Person_name_ref;

		// affiliation

			$schema_provider_Person['affiliation'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref,
				$schema_provider_hospitalAffiliation
			);
		
		// alumniOf

			$schema_provider_Person['alumniOf'] = array(
				array( // Repeat as necessary
					'@type' => 'EducationalOrganization', // Replace with more specific type as relevant
					'name' => 'foo' // Replace 'foo' with name of the organization from which the provider received education/training
				)
			);
		
		// brand

			$schema_provider_Person['brand'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref
			);
		
		// description

			$schema_provider_Person['description'] = array(
				'@id' => $schema_provider_url . '#Description',
				$excerpt_attr
			);

			// Define reference to this 'description' property

				$schema_provider_Person_description_ref['@id'] = $schema_provider_Person['description']['@id'] ?: '';

			// Define value of 'description' of 'Physician' with this 'description' reference

				$schema_provider_Physician['description'] = $schema_provider_Person_description_ref;

			// Define value of 'description' of 'MedicalWebPage' with this 'description' reference

				$schema_provider_MedicalWebPage['description'] = $schema_provider_Person_description_ref;

		// familyName

			$schema_provider_Person['familyName'] = $last_name_attr;

		// gender

			$schema_provider_Person['gender'] = $gender_attr; // Replace 'foo' with provider's gender // Text (Data Type)

		// givenName

			$schema_provider_Person['givenName'] = $first_name_attr;

		// hasCredential

			$schema_provider_Person['hasCredential'] = array(
				array( // Repeat as necessary
					'@type' => 'EducationalOccupationalCredential',
					'name' => 'foo' // Full name of degree or credential (e.g., 'Doctor of Medicine')
				)
			);

		// hasOccupation

			$schema_provider_Person['hasOccupation'] = array( // Occupation
				array( // Replace values with values relevant to provider // Repeat as necessary
					'@type' => 'Occupation',
					'name' => 'foo', // Clinical occupation title value from Specialty item
					'alternateName' => 'foo', // Alternate name value from Specialty item
					'description' => 'foo', // Description value from Specialty item
					'occupationalCategory' => array( // Replace values with relevant values attached to the Specialty
						array(
							'@type' => 'CategoryCode',
							'inCodeSet' => array(
								'@type' => 'CategoryCodeSet',
								'name' => 'O*Net-SOC',
								'dateModified' => '2019',
								'url' => 'https://www.onetonline.org/'
							),
							'codeValue' => '29-1242.00', // O*Net-SOC code value from Specialty item
							'name' => 'Orthopedic Surgeons, Except Pediatric', // O*Net-SOC name from Specialty item
							'url' => 'https://www.onetonline.org/link/summary/29-1242.00' // O*Net-SOC URL from Specialty item
						),
						array(
							'@type' => 'CategoryCode',
							'inCodeSet' => array(
								'@type' => 'CategoryCodeSet',
								'name' => 'ISCO-08',
								'dateModified' => '2016',
								'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/isco08/'
							),
							'codeValue' => '2212', // ISCO-08 code value from Specialty item
							'description' => 'Specialist medical practitioners diagnose and treat human physical and mental illnesses, disorders and injuries using specialized testing, diagnostic, medical and surgical techniques based on the scientific principles of modern medicine. They specialize in certain disease categories, types of patient or methods of treatment and may conduct research in their chosen areas of specialization.', // ISCO-08 description from Specialty item (called "Lead Statement" in "Draft ISCO-08 Group Definitions: Occupations in Health")
							'name' => 'Specialist medical practitioners', // ISCO-08 name from Specialty item
							'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/docs/health.pdf'
						)
					),
					'sameAs' => 'https://www.wikidata.org/wiki/Q16030727' // Wikidata URL from Specialty item
				)
			);

		// honorificPrefix

			$schema_provider_Person['honorificPrefix'] = $prefix_attr;

		// honorificSuffix

			$schema_provider_Person['honorificSuffix'] = $degree_attr_array;

		// identifier

			$schema_provider_Person['identifier'] = array(
				'@type' => 'PropertyValue',
				'name' => 'National Provider Identifier',
				'description' => 'The National Provider Identifier is a Health Insurance Portability and Accountability Act Administrative Simplification Standard. The NPI is a unique identification number for covered health care providers. Covered health care providers and all health plans and health care clearinghouses must use the NPIs in the administrative and financial transactions adopted under HIPAA. The NPI is a 10-position, intelligence-free numeric identifier (10-digit number). This means that the numbers do not carry other information about health care providers, such as the state in which they live or their medical specialty. The NPI must be used in lieu of legacy provider identifiers in the HIPAA standards transactions. As outlined in the Federal Regulation, The Health Insurance Portability and Accountability Act of 1996, covered providers must also share their NPI with other providers, health plans, clearinghouses, and any entity that may need it for billing purposes.',
				'propertyID' => 'https://www.wikidata.org/wiki/Q6975101',
				'url' => 'https://npiregistry.cms.hhs.gov/provider-view/foo', // Replace 'foo' with NPI
				'value' => 'foo' // Replace 'foo' with NPI
			);
	
		// image

			// Provider standard portrait

				// Image ID
				$provider_portrait = $featured_image;

				if ( $provider_portrait ) {
						
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
								'caption' => $full_name_attr,
								'encodingFormat' => $provider_encoding_format,
								'representativeOfPage' => true,
							);

						// 1:1 Aspect Ratio

							$schema_provider_Person_image_1_1 = array_filter(
								array_merge(
									$provider_portrait_image_object_base,
									array(
										'@id' => $schema_provider_url . '#Portrait-1-1',
										'contentSize' => $provider_portrait_1_1_size,
										'contentUrl' => $provider_portrait_1_1_url,
										'height' => $provider_portrait_1_1_height,
										'width' => $provider_portrait_1_1_width
									)
								)
							);

							$schema_provider_Person['image'][] = ksort( $schema_provider_Person_image_1_1 );

						// 3:4 Aspect Ratio

							$schema_provider_Person_image_3_4 = array_filter(
								array_merge(
									$provider_portrait_image_object_base,
									array(
										'@id' => $schema_provider_url . '#Portrait-3-4',
										'contentSize' => $provider_portrait_3_4_size,
										'contentUrl' => $provider_portrait_3_4_url,
										'height' => $provider_portrait_3_4_height,
										'width' => $provider_portrait_3_4_width
									)
								)
							);

							$schema_provider_Person['image'][] = ksort( $schema_provider_Person_image_3_4 );

						// 4:3 Aspect Ratio

							$schema_provider_Person_image_4_3 = array_filter(
								array_merge(
									$provider_portrait_image_object_base,
									array(
										'@id' => $schema_provider_url . '#Portrait-4-3',
										'contentSize' => $provider_portrait_4_3_size,
										'contentUrl' => $provider_portrait_4_3_url,
										'height' => $provider_portrait_4_3_height,
										'width' => $provider_portrait_4_3_width
									)
								)
							);

							$schema_provider_Person['image'][] = ksort( $schema_provider_Person_image_4_3 );

						// // 16:9 Aspect Ratio
						// 
						// 	$schema_provider_Person_image_16_9 = array_filter(
						// 		array_merge(
						// 			$provider_portrait_image_object_base,
						// 			array(
						// 				'@id' => $schema_provider_url . '#Portrait-16-9',
						// 				'contentSize' => $provider_portrait_16_9_size,
						// 				'contentUrl' => $provider_portrait_16_9_url,
						// 				'height' => $provider_portrait_16_9_height,
						// 				'width' => $provider_portrait_16_9_width
						// 			)
						// 		)
						// 	);
						// 
						// 	$schema_provider_Person['image'][] = ksort( $schema_provider_Person_image_16_9 );

						// Define a reference to the standard portrait values

							$schema_provider_Person_image_ref[0][]['@id'] = $schema_provider_url . '#Portrait-1-1';
							$schema_provider_Person_image_ref[0][]['@id'] = $schema_provider_url . '#Portrait-3-4';
							$schema_provider_Person_image_ref[0][]['@id'] = $schema_provider_url . '#Portrait-4-3';
							// $schema_provider_Person_image_ref[0][]['@id'] = $schema_provider_url . '#Portrait-1-1';

						// Define value of 'primaryImageOfPage' of 'MedicalWebPage' with this series of 'image' references

							$schema_provider_MedicalWebPage['primaryImageOfPage'] = $schema_provider_Person_image_ref;

				}

			// Provider wide portrait

				// Image ID
				$provider_wide_portrait = $headshot_wide;

				if ( $provider_wide_portrait ) {

					// Image Encoding Format
					$provider_wide_portrait_encoding_format = get_post_mime_type( $provider_wide_portrait ); // e.g., 'image/jpeg'

					// Image Values

						// 16:9 Aspect Ratio

							$provider_wide_portrait_16_9 = wp_get_attachment_image_src( $provider_portrait, 'aspect-16-9' );
							$provider_wide_portrait_16_9_url = $provider_portrait_16_9[0];
							$provider_wide_portrait_16_9_width = $provider_portrait_16_9[1];
							$provider_wide_portrait_16_9_height = $provider_portrait_16_9[2];
							$provider_wide_portrait_16_9_size = '';

					// Image Objects

						// Base object

							$provider_wide_portrait_image_object_base = array(
								'@type' => 'ImageObject',
								'caption' => $full_name_attr,
								'encodingFormat' => $provider_wide_portrait_encoding_format,
								'representativeOfPage' => true,
							);

						// 16:9 Aspect Ratio

							$schema_provider_Person_image_wide_16_9 = array_filter(
								array_merge(
									$provider_wide_portrait_image_object_base,
									array(
										'@id' => $schema_provider_url . '#Wide-Portrait-16-9',
										'contentSize' => $provider_wide_portrait_16_9_size,
										'contentUrl' => $provider_wide_portrait_16_9_url,
										'height' => $provider_wide_portrait_16_9_height,
										'width' => $provider_wide_portrait_16_9_width
									)
								)
							);

							$schema_provider_Person['image'][] = ksort( $schema_provider_Person_image_wide_16_9 );

				}



		// jobTitle

			$schema_provider_Person['jobTitle'] = array(
				'foo' // Replace 'foo' with provider's clinical occupation title (e.g., 'Orthopaedic surgeon') // Repeat as necessary // Text (Data Type)
			);

		// knowsLanguage

			foreach ( $language_attr_array as $item ) {

				$schema_provider_Person['knowsLanguage'][] = array( // Repeat as necessary
					'@type' => 'Language',
					'name' => $item
				);

			}

		// mainEntityOfPage

			$schema_provider_Person['mainEntityOfPage'] = $schema_provider_MedicalWebPage_ref;

		// memberOf

			$schema_provider_Person['memberOf'] = array(
				array( // Repeat as necessary
					'@type' => 'Organization',
					'name' => 'foo' // Replace 'foo' with provider's association organization
				)
			);

		// sameAs

			$schema_provider_Person['sameAs'] = 'https://npiregistry.cms.hhs.gov/provider-view/foo'; // Replace 'foo' with NPI

		// subjectOf

			$schema_provider_Person['subjectOf'] = $schema_provider_MedicalWebPage_ref;

		// url

			$schema_provider_Person['url'] = $schema_provider_MedicalWebPage_url_ref;

		// workLocation

			$schema_provider_Person['workLocation'] = $schema_provider_workLocation;

		// worksFor

			$schema_provider_Person['worksFor'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref
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

// Construct the schema JSON script tag
uamswp_fad_schema_construct($schema_provider);

// Display array as development testing

	echo '<pre>'; // test
	echo print_r($schema_provider); // test
	echo '</pre>'; // test