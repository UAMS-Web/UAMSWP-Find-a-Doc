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
 * 	$schema_provider_languages
 * 	$featured_image
 * 	$headshot_wide
 * 	$provider_associations_values
 * 
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base.php' );

$schema_provider = $schema_common_base;

/*

TODO List

 * Create means of defining organization schema for third-party institutions (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
 * Create means of associating third-party institutions with provider
 * Get ISCO-08 values from Clinical Specialization taxonomy items
 * Get O*Net-SOC values from Clinical Specialization taxonomy items
 * Define schema for related locations
 * Define array of just URLs from related locations
 * Bring locations schema and URLs into relevant properties of provider's schema
 * Define schema for related areas of expertise
 * Define array of just URLs from related areas of expertise
 * Bring areas of expertise schema and URLs into relevant properties of provider's schema
 * Define schema for related clinical resources
 * Define array of just URLs from related clinical resources
 * Bring clinical resources schema and URLs into relevant properties of provider's schema
 * Define schema for related conditions
 * Define array of just URLs from related conditions
 * Bring conditions schema and URLs into relevant properties of provider's schema
 * Define schema for related treatments
 * Define array of just URLs from related treatments
 * Bring treatments schema and URLs into relevant properties of provider's schema
 * Define schema for affiliated hospital(s)
 * Remove irrelevant metaboxes from taxonomy items (e.g., SEO; __ Archive Settings; Layout Settings)
 * Add fields to Education and Training Organization taxonomy, integrate them into this schema
	 * Required — Query for whether the organization is a College/University
	 * Optional — Alternate Name (repeater)
	 * Required — URL
	 * Optional — Street Address
	 * Required — City / Locality (required)
	 * Required — State / Appropriate first-level Administrative division — https://en.wikipedia.org/wiki/List_of_administrative_divisions_by_country
	 * Required — Country (required) — two-letter ISO 3166-1 alpha-2 country code — https://en.wikipedia.org/wiki/ISO_3166-1#Officially_assigned_code_elements
	 * Optional — Postal Code
 * Add labels and definitions to Credential Transparency Description Language values map array ($ctdl_values)
 * Replace common schema fields with clone fields referencing field in 'assets\json\acf-json\group_uamswp_schema.json'

*/

// Get Values

	// Provider URL

		$schema_provider_url = user_trailingslashit($page_url);

	// Related ontology items as schema arrays

		// Related Locations (MedicalWebPage[mentions], Physician[location], Person[workLocation])

			$provider_related_location = array();

			// Define values array for each associated location // Repeat for all associated locations

				$provider_related_location[] = array(
					'@id' => $schema_provider_url . '#Location1', // Increase integer by one each iteration
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
							'representativeOfPage' => 'False',
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
				);

			// Define reference to each value/row in this property

				$schema_provider_location_ref = array();

				foreach ( $provider_related_location as $item ) {

					if (
						isset($item['@id'])
						&&
						!empty($item['@id'])
					) {

						$schema_provider_location_ref[]['@id'] = $item['@id'];

					}

				}

			// If there is only one item, flatten the multi-dimensional array by one step

				if ( !empty($provider_related_location) ) {

					$provider_related_location = count($provider_related_location) == 1 ? reset($provider_related_location) : $provider_related_location;

				}

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
				array( // Repeat as necessary
					'@id' => $schema_provider_url . '#MedicalEntity1', // Increase integer by one each iteration
					'@type' => 'MedicalEntity', // Replace 'MedicalEntity' with 'MedicalTest' or 'MedicalProcedure' (or more specific type if relevant)
					'name' => 'foo', // Replace 'foo' with name of associated entity
					'foo' => 'bar' // Replace 'foo' and 'bar' with necessary property/value pairs, adding more if necessary
				)
			);

			// Define reference to each value/row in this property

				$schema_provider_treatment_ref = array();

				foreach ( $provider_related_treatment as $item ) {

					if (
						isset($item['@id'])
						&&
						!empty($item['@id'])
					) {

						$schema_provider_treatment_ref[]['@id'] = $item['@id'];

					}

				}

			// If there is only one item, flatten the multi-dimensional array by one step

				if ( !empty($provider_related_treatment) ) {

					$provider_related_treatment = count($provider_related_treatment) == 1 ? reset($provider_related_treatment) : $provider_related_treatment;

				}

	// Provider Clinical Specialization and Associated Values (medicalSpecialty, et al.)

		// Eliminate PHP errors

			$schema_provider_medicalSpecialty = '';

		if ( $provider_specialty_term ) {

			// Schema.org MedicalSpecialty Enumeration Member

				$schema_provider_medicalSpecialty = get_field('schema_medicalspecialty_single', $provider_specialty_term);
				$schema_provider_medicalSpecialty = uamswp_attr_conversion($schema_provider_medicalSpecialty);

			// Health Care Provider Taxonomy Code Set (https://taxonomy.nucc.org/)

				// Specialization Taxonomy Code

					$schema_provider_nucc_code = get_field('clinical_specialization_code', $provider_specialty_term);
					$schema_provider_nucc_code = uamswp_attr_conversion($schema_provider_nucc_code);

				// Specialization Name

					$schema_provider_nucc_name = get_field('clinical_specialty_name', $provider_specialty_term);
					$schema_provider_nucc_name = uamswp_attr_conversion($schema_provider_nucc_name);

				// Specialization Display Name

					$schema_provider_nucc_name_display = get_field('clinical_specialization_name_display', $provider_specialty_term);
					$schema_provider_nucc_name_display = uamswp_attr_conversion($schema_provider_nucc_name_display);

				// Specialization Definition

					$schema_provider_nucc_definition = get_field('clinical_specialization_definition', $provider_specialty_term);
					$schema_provider_nucc_definition = uamswp_attr_conversion($schema_provider_nucc_definition);

				// Source of Specialization Definition

					$schema_provider_nucc_definition_source = get_field('clinical_specialization_definition_source', $provider_specialty_term);
					$schema_provider_nucc_definition_source = uamswp_attr_conversion($schema_provider_nucc_definition_source);

			// Occupation alternateName

				$schema_provider_Occupation_alternateName = array();

				// Add Specialization Display Name

					$schema_provider_Occupation_alternateName[] = ( isset($schema_provider_nucc_name_display) && !empty($schema_provider_nucc_name_display) ) ? $schema_provider_nucc_name_display : '';

				// Add Alternate Occupational Titles

					// Get Alternate Occupational Titles

						$provider_occupation_title_alt_repeater = get_field('clinical_specialization_title_alt', $provider_specialty_term);
						$provider_occupation_title_alt = array();

						if ( $provider_occupation_title_alt_repeater ) {

							foreach( $provider_occupation_title_alt_repeater as $title ) { 

								$provider_occupation_title_alt[] = $title['clinical_specialization_title_alt_text'];

							}

						}

					$schema_provider_Occupation_alternateName = array_merge(
						$schema_provider_Occupation_alternateName,
						$provider_occupation_title_alt
					);

				// Remove empty items

					if ( is_array($schema_provider_Occupation_alternateName) ) {

						$schema_provider_Occupation_alternateName = array_filter($schema_provider_Occupation_alternateName);

					}

				// Remove duplicate items

					if ( is_array($schema_provider_Occupation_alternateName) ) {

						$schema_provider_Occupation_alternateName = array_unique($schema_provider_Occupation_alternateName);

					}

				// If there is only one item, flatten the multi-dimensional array by one step

					if ( !empty($schema_provider_Occupation_alternateName) ) {

						$schema_provider_Occupation_alternateName = count($schema_provider_Occupation_alternateName) == 1 ? reset($schema_provider_Occupation_alternateName) : $schema_provider_Occupation_alternateName;

					}

				// Sort array

					if ( is_array($schema_provider_Occupation_alternateName) ) {

						sort($schema_provider_Occupation_alternateName);

					}

			// Centers for Medicare & Medicaid Services (CMS) Specialty Code

				// CMS Specialty Code

					$schema_provider_cms_code = get_field('clinical_specialization_cms_code', $provider_specialty_term);
					$schema_provider_cms_code = uamswp_attr_conversion($schema_provider_cms_code);

				// CMS Provider/Supplier Type

					$schema_provider_cms_code_type = get_field('clinical_specialization_cms_code_type', $provider_specialty_term);
					$schema_provider_cms_code_type = uamswp_attr_conversion($schema_provider_cms_code_type);

			// occupationalCategory

				// O*Net-SOC

					// Occupation Code

						$schema_provider_onetsoc_code = get_field('clinical_specialization_onetsoc_code', $provider_specialty_term);
						$schema_provider_onetsoc_code = uamswp_attr_conversion($schema_provider_onetsoc_code);

					// Occupation Name

						$schema_provider_onetsoc_code_name = get_field('clinical_specialization_onetsoc_code_name', $provider_specialty_term);
						$schema_provider_onetsoc_code_name = uamswp_attr_conversion($schema_provider_onetsoc_code_name);

					// Array

						$schema_provider_onetsoc = array();

						if ( $schema_provider_onetsoc_code ) {

							$schema_provider_onetsoc = array(
								'codeValue' => $schema_provider_onetsoc_code, // O*Net-SOC code value from Specialty item
								'@type' => 'CategoryCode',
								'inCodeSet' => array(
									'@type' => 'CategoryCodeSet',
									'name' => 'O*Net-SOC',
									'dateModified' => '2019',
									'url' => 'https://www.onetonline.org/'
								),
								'name' => isset($schema_provider_onetsoc_code_name) ? $schema_provider_onetsoc_code_name : '', // O*Net-SOC name from Specialty item
								'url' => 'https://www.onetonline.org/link/summary/' . $schema_provider_onetsoc_code // O*Net-SOC URL from Specialty item
							);

							// Remove empty properties

								if ( is_array($schema_provider_onetsoc) ) {

									ksort($schema_provider_onetsoc);

								}

							// Sort the array

								if ( is_array($schema_provider_onetsoc) ) {

									ksort($schema_provider_onetsoc);

								}

						}

				// ISCO-08 Code

					$schema_provider_isco08_code = get_field('clinical_specialization_isco08_code', $provider_specialty_term);

					// Make value attribute-friendly, add it to an array if it is not already

						if ( is_array($schema_provider_isco08_code)) {

							foreach ($schema_provider_isco08_code as $code ) {

								$code = uamswp_attr_conversion($code);

							}

						} else {

							$schema_provider_isco08_code = array(uamswp_attr_conversion($schema_provider_isco08_code));

						}

					// Values map

						$isco08_values = array(
							'2' => array(
								'name' => 'Professionals',
								'description' => 'Professionals increase the existing stock of knowledge; apply scientific or artistic concepts and theories; teach about the foregoing in a systematic manner; or engage in any combination of these activities. Competent performance in most occupations in this major group requires skills at the fourth ISCO skill level.',
								'sameAs' => array()
							),
							'22' => array(
								'name' => 'Health Professionals',
								'description' => 'Health professionals conduct research; improve or develop concepts, theories and operational methods; and apply scientific knowledge relating to medicine, nursing, dentistry, veterinary medicine, pharmacy, and promotion of health.  Competent performance in most occupations in this sub-major group requires skills at the fourth ISCO skill level.',
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

					// // (Optional) Expand ISCO-8 code to include list of ancestors
					// 
					// 	if ( $schema_provider_isco08_code ) {
					// 
					// 		$schema_provider_isco08_code = array_merge(
					// 			$schema_provider_isco08_code,
					// 			$isco08_values[$schema_provider_isco08_code[0]]['sameAs']
					// 		);
					// 
					// 		sort($schema_provider_isco08_code);
					// 
					// 	}

					// Array

						$schema_provider_isco08 = array();

						if ( $schema_provider_isco08_code ) {

							foreach ( $schema_provider_isco08_code as $code ) {

								$schema_provider_isco08_array = array(
									'codeValue' => $code, // ISCO-08 code value from Specialty item
									'@type' => 'CategoryCode',
									'description' => isset($isco08_values[$code]['description']) ? $isco08_values[$code]['description'] : '', // ISCO-08 description from Specialty item (called "Lead Statement" in "Draft ISCO-08 Group Definitions: Occupations in Health")
									'inCodeSet' => array(
										'@type' => 'CategoryCodeSet',
										'dateModified' => '2016',
										'name' => 'ISCO-08',
										'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/isco08/'
									),
									'name' => isset($isco08_values[$code]['name']) ? $isco08_values[$code]['name'] : '', // ISCO-08 name from Specialty item
									'url' => 'https://www.ilo.org/public/english/bureau/stat/isco/docs/health.pdf'
								);

								// Remove empty properties

									if ( is_array($schema_provider_isco08_array) ) {

										$schema_provider_isco08_array = array_filter($schema_provider_isco08_array);

									}

								// Sort the array

									if ( is_array($schema_provider_isco08_array) ) {

										ksort($schema_provider_isco08_array);

									}

								$schema_provider_isco08[] = $schema_provider_isco08_array;

							}

						}

				// occupationalCategory Value Array

					$schema_provider_occupationalCategory = array_merge(
						$schema_provider_isco08,
						array($schema_provider_onetsoc)
					);

					// If there is only one item, flatten the multi-dimensional array by one step

						if (
							isset($schema_provider_occupationalCategory)
							&&
							!empty($schema_provider_occupationalCategory)
						) {

							$schema_provider_occupationalCategory = count($schema_provider_occupationalCategory) == 1 ? reset($schema_provider_occupationalCategory) : $schema_provider_occupationalCategory;

						}

			// Wikidata

				// Wikidata Item URL For the Occupation

					$schema_provider_wikidata_occupation = get_field('clinical_specialization_wikidata_url_occupation', $provider_specialty_term);
					$schema_provider_wikidata_occupation = uamswp_attr_conversion($schema_provider_wikidata_occupation);

				// Wikidata Item URL For the Specialty / Field

					$schema_provider_wikidata_field = get_field('clinical_specialization_wikidata_url_field', $provider_specialty_term);
					$schema_provider_wikidata_field = uamswp_attr_conversion($schema_provider_wikidata_field);

		}


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
						'representativeOfPage' => 'False',
						'width' => 'foo' // Replace 'foo' with the image's width
					)
				),
				'url' => 'foo' // Replace 'foo' with location profile URL
			)
		);

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
				array(
					'@type' => 'MedicalAudienceType',
					'name' => 'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
				)
			);

		// mentions

			// Base array

				$schema_provider_MedicalWebPage['mentions'] = array();

			// Related Locations

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					$schema_provider_location_ref
				);

			// Related Areas of Expertise

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					$provider_related_expertise
				);

			// Related Clinical Resources

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					$provider_related_clinical_resource
				);

			// Related Conditions

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					$provider_related_condition
				);

			// Related Treatments

				$schema_provider_MedicalWebPage['mentions'] = array_merge(
					$schema_provider_MedicalWebPage['mentions'],
					$schema_provider_treatment_ref
				);

			// Remove any empty items from the array

				$schema_provider_MedicalWebPage['mentions'] = array_filter($schema_provider_MedicalWebPage['mentions']);

				if ( empty($schema_provider_MedicalWebPage['mentions']) ) {

					$schema_provider_MedicalWebPage['mentions'];

				}

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

			// Define the value of 'mainEntity' of 'MedicalWebPage' with this 'Physician' reference

				$schema_provider_MedicalWebPage['mainEntity'] = $schema_provider_Physician_ref;

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

			$schema_provider_Physician['availableService'] = $provider_related_treatment;

		// brand

			$schema_provider_brand = array();

			// Add UAMS Health

				$schema_provider_brand[] = $schema_base_org_uams_health_ref;

			// Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)

			// If there is only one item, flatten the multi-dimensional array by one step

				$schema_provider_brand = count($schema_provider_brand) == 1 ? reset($schema_provider_brand) : $schema_provider_brand;

			// Add to schema

				if ( $schema_provider_brand ) {

					$schema_provider_Physician['brand'] = $schema_provider_brand;

				}

		// description

			$schema_provider_Physician['description'] = array(); // Defined later

		// hospitalAffiliation

			$schema_provider_Physician['hospitalAffiliation'] = $schema_provider_hospitalAffiliation;

		// isAcceptingNewPatients

			$schema_provider_Physician['isAcceptingNewPatients'] = $accept_new ? 'True' : 'False';

		// location

			$schema_provider_Physician['location'] = $provider_related_location;

		// mainEntityOfPage

			$schema_provider_Physician['mainEntityOfPage'] = $schema_provider_MedicalWebPage_ref;

		// medicalSpecialty

			$schema_provider_Physician['medicalSpecialty'] = $schema_provider_medicalSpecialty;

		// parentOrganization

			$schema_provider_Physician['parentOrganization'] = array( // Append arrays with relevant Organization if necessary (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
				$schema_base_org_uams_health_ref
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

			$schema_provider_Person['additionalType'] = ( isset($schema_provider_wikidata_occupation) && !empty($schema_provider_wikidata_occupation) ) ? $schema_provider_wikidata_occupation : 'https://www.wikidata.org/wiki/Q11974939'; // Get Wikidata item URL for occupation from Specialty // Default to health professional (Q11974939)

		// @id

			$schema_provider_Person['@id'] = $schema_provider_url . '#' . $schema_provider_Person['@type'];

			// Define reference to this 'Person' item

				$schema_provider_Person_ref['@id'] = $schema_provider_Person['@id'] ?: '';

			// Define a value of 'about' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['about'][] = $schema_provider_Person_ref;

			// Define a value of 'mentions' of 'MedicalWebPage' with this 'Person' reference

				$schema_provider_MedicalWebPage['mentions'][] = $schema_provider_Person_ref;

		// name

			$schema_provider_Person['name'] = array(
				'@id' => $schema_provider_url . '#Name',
				'@type' => 'Name',
				'name' => $full_name_attr, // Replace 'foo' with long provider name (e.g., "Leonard H. McCoy Jr., M.D.")
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

			// Get list of education and training organizations

				$provider_education_organizations = array();
				$schema_provider_alumniOf = array();

				if (
					isset($education)
					&&
					!empty($education)
					&&
					is_array($education)
				) {

					foreach ( $education as $item ) {

						$provider_education_organizations[] = get_term( $item['school'], 'school')->name;

					}

					// Remove empty items

						if ( is_array($provider_education_organizations) ) {

							$provider_education_organizations = array_filter($provider_education_organizations);

						}

					// Remove duplicate items

						if ( is_array($provider_education_organizations) ) {

							$provider_education_organizations = array_unique($provider_education_organizations);

						}

					// Sort array

						if ( is_array($provider_education_organizations) ) {

							sort($provider_education_organizations);

						}

				}

			// Build alumniOf value

				if ( !empty($provider_education_organizations) ) {

					foreach ( $provider_education_organizations as $item ) {

						$schema_provider_alumniOf[] = array(
							'@type' => 'EducationalOrganization',
							'name' => $item
						);

					}

				}

			// If there is only one item, flatten the multi-dimensional array by one step

				if ( !empty($schema_provider_alumniOf) ) {

					$schema_provider_alumniOf = count($schema_provider_alumniOf) == 1 ? reset($schema_provider_alumniOf) : $schema_provider_alumniOf;

				}

			// Add to the schema

				if ( !empty($schema_provider_alumniOf) ) {

					$schema_provider_Person['alumniOf'] = $schema_provider_alumniOf;

				}

		// brand

			if ( $schema_provider_brand ) {

				$schema_provider_Person['brand'] = $schema_provider_brand;

			}

		// description

			$schema_provider_Person['description'] = array(
				'@id' => $schema_provider_url . '#Description',
				'@type' => 'Text',
				'description' => $excerpt_attr
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

			// Capitalize first letter of value

				$schema_provider_gender = ucfirst($gender_attr);

			// Use GenderType if Male/Female

				if (
					$schema_provider_gender == 'Female'
					||
					$schema_provider_gender == 'Male'
				) {

					$schema_provider_gender = array(
						'@type' => 'GenderType',
						'valueReference' => $schema_provider_gender
					);

				}

			// Add to schema

				$schema_provider_Person['gender'] = $schema_provider_gender; 

		// givenName

			$schema_provider_Person['givenName'] = $first_name_attr;

		// hasCredential

			$schema_provider_degrees = array_unique($degrees);
			$schema_provider_hasCredential = array();
			$schema_provider_credential = array();

			// Credential Transparency Description Language Values Map

				$ctdl_values = array(
					'ApprenticeshipCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'Assessment' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfAppliedArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfAppliedScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'AssociateOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'BachelorOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'Badge' => array(
						'label' => '',
						'definition' => ''
					),
					'Certificate' => array(
						'label' => '',
						'definition' => ''
					),
					'CertificateOfCompletion' => array(
						'label' => '',
						'definition' => ''
					),
					'Certification' => array(
						'label' => '',
						'definition' => ''
					),
					'Course' => array(
						'label' => '',
						'definition' => ''
					),
					'Credential' => array(
						'label' => '',
						'definition' => ''
					),
					'Degree' => array(
						'label' => '',
						'definition' => ''
					),
					'DigitalBadge' => array(
						'label' => '',
						'definition' => ''
					),
					'Diploma' => array(
						'label' => '',
						'definition' => ''
					),
					'DoctoralDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'GeneralEducationDevelopment' => array(
						'label' => '',
						'definition' => ''
					),
					'JourneymanCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'LearningProgram' => array(
						'label' => '',
						'definition' => ''
					),
					'License' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterCertificate' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterOfArtsDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MasterOfScienceDegree' => array(
						'label' => '',
						'definition' => ''
					),
					'MicroCredential' => array(
						'label' => '',
						'definition' => ''
					),
					'ProfessionalDoctorate' => array(
						'label' => 'Professional Doctorate',
						'definition' => 'Doctoral degree conferred upon completion of a program providing the knowledge and skills for the recognition, credential, or license required for professional practice.'
					),
					'QualityAssuranceCredential' => array(
						'label' => '',
						'definition' => ''
					),
					'ResearchDoctorate' => array(
						'label' => '',
						'definition' => ''
					),
					'SecondarySchoolDiploma' => array(
						'label' => '',
						'definition' => ''
					),
					'SpecialistDegree' => array(
						'label' => '',
						'definition' => ''
					)
				);


			foreach ( $schema_provider_degrees as $item ) {

				$schema_provider_credential = array(); // reset array
				$item_term = get_term( $item, 'degree');
				$item_name = get_field( 'degree_name', $item_term );
				$item_abbreviation = $item_term->name;
				$item_ctdl = get_field( 'degree_ctdl', $item_term );

				// Build value for individual degree or credential

					if ( $item_name ) {

						$schema_provider_credential = array(
							'@type' => 'EducationalOccupationalCredential',
							'alternateName' => $item_abbreviation, // Abbreviation of degree or credential (e.g., 'M.D.')
							'name' => $item_name, // Full name of degree or credential (e.g., 'Doctor of Medicine')
						);

						if ( $item_ctdl ) {

							$schema_provider_credential['credentialCategory'] = array(
								'@type' => 'DefinedTerm',
								'description' => isset($ctdl_values[$item_ctdl]['definition']) ? $ctdl_values[$item_ctdl]['definition'] : '', // Credential Transparency Description Language term definition
								'inDefinedTermSet' => array(
									'@type' => 'DefinedTermSet',
									'name' => 'Credential Transparency Description Language',
									'url' => 'http://purl.org/ctdl/terms/'
								),
								'name' => isset($ctdl_values[$item_ctdl]['label']) ? $ctdl_values[$item_ctdl]['label'] : '', // Credential Transparency Description Language term label
								'termCode' => $item_ctdl, // Credential Transparency Description Language term definition
								'url' => 'https://purl.org/ctdl/terms/' . $item_ctdl // Credential Transparency Description Language term URI
							);

							// Remove empty rows

								$schema_provider_credential['credentialCategory'] = array_filter($schema_provider_credential['credentialCategory']);

						}

						// Sort array

							if ( is_array($schema_provider_credential) ) {

								ksort($schema_provider_credential);

							}

					}

				// Add value to list

					if ( $schema_provider_credential ) {

						$schema_provider_hasCredential[] = $schema_provider_credential;

					}

			}

			// Add list to schema

				if ( $schema_provider_hasCredential ) {

					$schema_provider_Person['hasCredential'] = $schema_provider_hasCredential;

					// If there is only one item, flatten the multi-dimensional array by one step

						$schema_provider_Person['hasCredential'] = count($schema_provider_Person['hasCredential']) == 1 ? reset($schema_provider_Person['hasCredential']) : $schema_provider_Person['hasCredential'];

				}

		// hasOccupation

			if (
				isset($provider_occupation_title)
				&&
				!empty($provider_occupation_title)
			) {

				$schema_provider_Person['hasOccupation'] = array( // Replace values with values relevant to provider // Repeat as necessary
					'@type' => 'Occupation',
					'name' => $provider_occupation_title, // Clinical occupation title value from Specialty item
					'alternateName' => ( isset($schema_provider_Occupation_alternateName) && !empty($schema_provider_Occupation_alternateName) ) ? $schema_provider_Occupation_alternateName : '', // Alternate name value from Specialty item
					'description' => ( isset($schema_provider_nucc_definition) && !empty($schema_provider_nucc_definition) ) ? $schema_provider_nucc_definition : '', // Description value from Specialty item
					'occupationalCategory' => ( isset($schema_provider_occupationalCategory) && !empty($schema_provider_occupationalCategory) ) ? $schema_provider_occupationalCategory : '',
					'sameAs' => ( isset($schema_provider_wikidata_occupation) && !empty($schema_provider_wikidata_occupation) ) ? $schema_provider_wikidata_occupation : '' // Wikidata URL from Specialty item
				);

			}

		// honorificPrefix

			if ( $prefix_attr ) {

				$schema_provider_Person['honorificPrefix'] = $prefix_attr;

			}

		// honorificSuffix

			// Eliminate PHP errors

				$schema_provider_honorificSuffix = '';

			if ( $degree_attr_array ) {

				// If there is only one item, flatten the multi-dimensional array by one step

					$schema_provider_honorificSuffix = count($degree_attr_array) == 1 ? reset($degree_attr_array) : $degree_attr_array;

				$schema_provider_Person['honorificSuffix'] = $schema_provider_honorificSuffix;

			}

		// identifier

			// National Provider Identifier (NPI)

				if ( $npi ) {

					$schema_provider_Person['identifier'][] = array(
						'@type' => 'PropertyValue',
						'name' => 'National Provider Identifier',
						'propertyID' => 'https://www.wikidata.org/wiki/Q6975101', // Wikidata item page for National Provider Identifier
						'url' => 'https://npiregistry.cms.hhs.gov/provider-view/' . $npi, // Provider information on NPPES NPI Registry
						'value' => $npi // NPI value
					);

				}

			// If there is only one item, flatten the multi-dimensional array by one step

				if ( !empty($schema_provider_Person['identifier']) ) {

					$schema_provider_Person['identifier'] = count($schema_provider_Person['identifier']) == 1 ? reset($schema_provider_Person['identifier']) : $schema_provider_Person['identifier'];

				}

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

						// Pre-define common property values for the ImageObject value arrays

							$provider_portrait_image_object_base = array(
								'@type' => 'ImageObject',
								'caption' => $full_name_attr,
								'encodingFormat' => $provider_encoding_format,
								'representativeOfPage' => 'True',
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

							// Sort array

								if ( is_array($schema_provider_Person_image_1_1) ) {

									ksort( $schema_provider_Person_image_1_1 );

								}

							// Add to schema

								$schema_provider_Person['image'][] = $schema_provider_Person_image_1_1;

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

							// Sort array

								if ( is_array($schema_provider_Person_image_3_4) ) {

									ksort( $schema_provider_Person_image_3_4 );

								}

							// Add to schema

								$schema_provider_Person['image'][] = $schema_provider_Person_image_3_4;

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

							// Sort array

								if ( is_array($schema_provider_Person_image_4_3) ) {

									ksort( $schema_provider_Person_image_4_3 );

								}

							// Add to schema

								$schema_provider_Person['image'][] = $schema_provider_Person_image_4_3;

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
						// 	// Sort array
						// 
						// 		if ( is_array($schema_provider_Person_image_16_9) ) {
						// 
						// 			ksort( $schema_provider_Person_image_16_9 );
						// 
						// 		}
						// 
						// 	// Add to schema
						// 
						// 		$schema_provider_Person['image'][] = $schema_provider_Person_image_16_9;

						// Define a reference to the standard portrait values

							$schema_provider_Person_image_ref[]['@id'] = $schema_provider_url . '#Portrait-1-1';
							$schema_provider_Person_image_ref[]['@id'] = $schema_provider_url . '#Portrait-3-4';
							$schema_provider_Person_image_ref[]['@id'] = $schema_provider_url . '#Portrait-4-3';
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
								'representativeOfPage' => 'True',
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

							// Sort array

								if ( is_array($schema_provider_Person_image_wide_16_9) ) {

									ksort( $schema_provider_Person_image_wide_16_9 );

								}

							// Add to schema

								$schema_provider_Person['image'][] = $schema_provider_Person_image_wide_16_9;

				}



		// jobTitle

			if ( $provider_occupation_title_attr ) {

				$schema_provider_Person['jobTitle'] = $provider_occupation_title_attr;

			}

		// knowsLanguage

			// Eliminate PHP errors

				$schema_provider_language = '';
				$schema_provider_language_alternateName = '';

			if ( $schema_provider_languages ) {

				// Add each language to the schema

					foreach ( $schema_provider_languages as $key => $value ) {

						$schema_provider_language = array( // Repeat as necessary
							'@type' => 'Language',
							'name' => $key,
						);

						if ( !empty($schema_provider_languages[$key]['alternateName']) ) {

							// If there is only one item, flatten the multi-dimensional array by one step

								$schema_provider_language_alternateName = $schema_provider_languages[$key]['alternateName'];

								$schema_provider_language_alternateName = count($schema_provider_language_alternateName) == 1 ? reset($schema_provider_language_alternateName) : $schema_provider_language_alternateName;

							$schema_provider_language['alternateName'] = $schema_provider_language_alternateName;

						}

						if ( $schema_provider_language ) {

							$schema_provider_Person['knowsLanguage'][] = $schema_provider_language;

						}

					}

				// If there is only one item, flatten the multi-dimensional array by one step

					if ( isset($schema_provider_Person['knowsLanguage']) ) {

						$schema_provider_Person['knowsLanguage'] = count($schema_provider_Person['knowsLanguage']) == 1 ? reset($schema_provider_Person['knowsLanguage']) : $schema_provider_Person['knowsLanguage'];

					}

			}

		// memberOf

			// Clean up array

				if (
					isset($provider_associations_values)
					&&
					is_array($provider_associations_values)
					&&
					!empty($provider_associations_values)
				) {

					// Sort array

						ksort($provider_associations_values);

				}

			// Add each item to schema value array

				// Eliminate PHP errors

					$schema_provider_memberOf = array();

				foreach ( $provider_associations_values as $item ) {

					$schema_provider_memberOf[] = array_merge(
						array( '@type' => 'Organization' ),
						array_filter($item)
					);

				}

			// Add to schema

				if ( !empty($schema_provider_memberOf) ) {

					$schema_provider_Person['memberOf'] = $schema_provider_memberOf;

					// If there is only one item, flatten the multi-dimensional array by one step

						$schema_provider_Person['memberOf'] = count($schema_provider_Person['memberOf']) == 1 ? reset($schema_provider_Person['memberOf']) : $schema_provider_Person['memberOf'];

				}

		// sameAs

			// NPPES NPI Registry

				if ( $npi ) {

					$schema_provider_Person['sameAs'][] = 'https://npiregistry.cms.hhs.gov/provider-view/' . $npi;

				}

			// If there is only one item, flatten the multi-dimensional array by one step

				$schema_provider_Person['sameAs'] = count($schema_provider_Person['sameAs']) == 1 ? reset($schema_provider_Person['sameAs']) : $schema_provider_Person['sameAs'];

		// subjectOf

			$schema_provider_Person['subjectOf'] = $schema_provider_MedicalWebPage_ref;

		// url

			$schema_provider_Person['url'] = $schema_provider_MedicalWebPage_url_ref;

		// workLocation

			$schema_provider_Person['workLocation'] = $schema_provider_location_ref;

		// worksFor

			if ( $schema_provider_brand ) {

				$schema_provider_Person['worksFor'] = $schema_provider_brand;

			}

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