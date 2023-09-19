<?php

/*
 * Required vars:
 * 
 * 	$schema_common_base
 * 	$page_id
 */

include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/base.php' );

$schema_provider = $schema_common_base;

// Band-aid to resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
$page_id = get_the_ID();

/*

TODO List

 * Provider
	 * Create means of defining organization schema for third-party institutions (e.g., Arkansas Children's, Central Arkansas Veterans Healthcare System)
	 * Create means of associating third-party institutions with provider
	 * Define schema for affiliated hospital(s)
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
	 * Define Provider-as-Dentist type array
	 * Define Provider-as-Optician type array
 * Related ontology items
	 * Related locations
		 * Apply the location schema function to the single location template
		 * Add values for remaining properties:
			 * Properties with pending questions @ https://uamsweb.atlassian.net/browse/FD20-3482
				 * aggregateRating
				 * areaServed
				 * contactPoint
					 * email
				 * currenciesAccepted
				 * diversityPolicy
				 * diversityStaffingReport
				 * ethicsPolicy
				 * funding
				 * identifier
					 * duns
					 * globalLocationNumber
					 * isicV4
					 * iso6523Code
					 * leiCode
					 * naics
					 * taxID
					 * vatID
				 * isAcceptingNewPatients
				 * isAccessibleForFree
				 * knowsLanguage
				 * legalName
				 * logo
				 * maximumAttendeeCapacity
				 * memberOf
				 * nonprofitStatus
				 * numberOfEmployees
				 * paymentAccepted
			 * Needing new data inputs
				 * award
					 * Create taxonomy similar to provider's 'Recognition List', but for locations
						 * Add a section to the location profile / subsection to display said information
				 * brand
				 * event
					 * Find means of populating values from relevant LiveWhale calendar events
				 * foundingDate
					 * Add input for date the location first opened (regardless of physical location)
				 * hasCredential
				 * hasDriveThroughService
				 * potentialAction
				 * publicAccess
				 * review
			 * Other properties
				 * contactPoint
					 * faxNumber
					 * telephone
				 * containedInPlace
					 * For descendant locations, add parent location as the 'Place'
					 * For top-level locations with a building value, add info on its building
						 * Add additional inputs to 'Building' taxonomy for 'Place' schema
							 * Include Google CID
				 * containsPlace
					 * Include descendant locations
					 * Include provider as 'Physician' type 
					 * Include provider as 'Dentist' type 
					 * Include provider as 'Optician' type 
				 * employee
					 * Include each provider associated with the location
				 * keywords
				 * knowsAbout
				 * mainEntityOfPage
				 * makesOffer
				 * openingHours
				 * openingHoursSpecification
				 * parentOrganization
					 * For descendant locations, add the parent location as the 'Organization'
					 * For top-level locations, add UAMS Health as the 'Organization'
				 * photo
					 * Amend values from ImageObject to accommodate properties particular to 'Photograph' type
				 * specialOpeningHoursSpecification
	 * Related areas of expertise
		 * Apply the areas of expertise schema function to the single area of expertise template
		 * Adjust the areas of expertise schema function (or create new one) to support the information on area of expertise fake subpages.
			 * Apply the areas of expertise schema function to the area of expertise fake subpages templates
		 * Add system fallback images to schema if relevant featured image is blank
		 * Add system fallback text to schema if relevant text is blank
	 * Related clinical resources
		 * Apply the clinical resources schema function to the single clinical resource template
		 * Separate resource types (e.g., ImageObject) into separate functions, calling them in the clinical resource schema function
			 * Apply VideoObject schema function to video property value within Provider schema function
		 * Get more info from YouTube API through YouTube Lyte plugin
			 * videoQuality
			 * videoFrameSize
		 * Get info from Vimeo API
			 * duration
			 * thumbnail
			 * videoQuality
			 * videoFrameSize
	 * Provider
		 * Make function for provider schema
		 * Make adjustments to convert 'Physician' type to 'MedicalBusiness' or a subtype of 'MedicalBusiness' relevant to the particular provider
			 * A dentist -> 'Dentist'
			 * An optician -> 'Optician'
			 * A women's health nurse practitioner -> 'Gynecologic'
		 * Provider as 'MedicalBusiness' type and its subtypes
			 * Find a way to validate whether a provider is an optician so the Optician type can be used in place of MedicalBusiness
			 * 'employee' property
				 * Add Provider as 'Person' type
		 * Consider adding 'ProfilePage' as an additional type on one of the items in @graph
		 * Add new set of inputs for name
			 * Message instructing editors to fully populate all fields and to not use initials
			 * Required inputs:
				 * Full first name
				 * Button group to confirm presence/absence of middle name (default: null)
				 * Full middle name
				 * Button group to confirm presence/absence of nickname (default: null)
				 * Nickname
				 * Full last name
				 * Button group to confirm presence/absence of generational suffix (default: null)
				 * Generational suffix
				 * Name display format selector with a message instructing editors to mirror the external name value defined in Epic (default: 'First Middle Last')
					 * First Middle Last
					 * First Middle "Nickname" Last
					 * First M. Last
					 * First M. "Nickname" Last
					 * F. Middle Last
					 * F. Middle "Nickname" Last
					 * F. M. Last
					 * F. M. "Nickname" Last
			 * Optional inputs
				 * Alternate name repeater (e.g., former names, variant names)
			 * Populate a hidden field in the provider data that is populated by all the name variants and is queried by provider search and site search
		 * Add values for remaining properties:
			 * Properties with pending questions @ https://uamsweb.atlassian.net/browse/FD20-3482
				 * areaServed
				 * currenciesAccepted
				 * identifiers
					 * duns
					 * globalLocationNumber
					 * isicV4
					 * leiCode
					 * naics
					 * taxID
					 * vatID
					 * iso6523Code
				 * isAccessibleForFree
				 * paymentAccepted
			 * Needing new data inputs
				 * alternateName
				 * award
				 * brand
				 * makesOffer
				 * potentialAction
				 * review
				 * sameAs
				 * worksFor
			 * Other properties
				 * additionalName
				 * affiliation
				 * containedInPlace
				 * description
				 * familyName
				 * givenName
				 * hasCredential
				 * hasOccupation
				 * hasMap
				 * honorificPrefix
				 * honorificSuffix
				 * hospitalAffiliation
				 * identifier
					 * the NPI value
					 * the taxonomy code value(s)?
				 * image
				 * isAcceptingNewPatients
				 * jobTitle
				 * keywords
				 * knowsAbout
				 * knowsLanguage
				 * legalName
				 * location
				 * mainEntityOfPage
				 * memberOf
				 * name
				 * parentOrganization
				 * photo
				 * smokingAllowed
				 * subjectOf
				 * workLocation
 * General
	 * Remove irrelevant metaboxes from taxonomy items (e.g., SEO; __ Archive Settings; Layout Settings)
	 * Replace common schema fields with clone fields referencing field in 'assets\json\acf-json\group_uamswp_schema.json'
	 * Resolve overzealous variable definitions in uamswp_fad_ontology_site_values function (e.g., $conditions_cpt) that are leaking out of the location card template parts, et al.
	 * Consider shifting to the use of the 'Thing > CreativeWork > WebContent > HealthTopicContent' type in place of 'MedicalWebPage' and/or the 'CreativeWork' subtypes used for clinical resources.
	 * 'Organization' values (for properties like 'brand', 'parentOrganization' and 'worksFor')
		 * Create method of defining 'Organization' property values for UAMS Health and UAMS
		 * Create method of defining 'Organization' property values for third-party organizations (e.g., Arkansas Children's)
		 * Create method of associating additional 'Organization' options with each ontology item type (e.g., location, provider)
 * Filter ACF fields
	 * Fields referencing MedicalTest type
		 * Just treatments with MedicalTest type or its subtypes
		 * Exclude current item
	 * Fields referencing MedicalTherapy type
		 * Just treatments with MedicalTherapy type or its subtypes
		 * Exclude current item

*/

// Define Schema JSON item arrays for provider profile as MedicalWebPage, MedicalBusiness and Person

	$schema_provider_combined = uamswp_fad_schema_provider(
		array($page_id), // List of IDs of the provider items
		$page_url, // Page URL
		0, // Nesting level within the main schema
		1, // Iteration counter for provider-as-MedicalWebPage
		1, // Iteration counter for provider-as-MedicalBusiness
		1, // Iteration counter for provider-as-Person
		$provider_schema_fields // Pre-existing field values array so duplicate calls can be avoided
	);

// Add provider schema arrays to the base schema array

	// Provider profile as MedicalWebPage

		$schema_provider['@graph'][] = $schema_provider_combined['MedicalWebPage'];

	// Provider profile as MedicalBusiness

		$schema_provider['@graph'][] = $schema_provider_combined['MedicalBusiness'];

	// Provider profile as Person

		$schema_provider['@graph'][] = $schema_provider_combined['Person'];

// Construct the schema JSON script tag

	uamswp_fad_schema_construct($schema_provider);

// Display array as development testing

	echo '<pre>'; // test

	// Full
	echo print_r($schema_provider); // test

	// // UAMS
	// echo print_r($schema_provider['@graph'][0]); // test

	// // UAMS Health
	// echo print_r($schema_provider['@graph'][1]); // test

	// // UAMSHealth.com
	// echo print_r($schema_provider['@graph'][1]); // test

	// // MedicalWebPage
	// echo print_r($schema_provider['@graph'][3]); // test

		// // MedicalWebPage[mentions]
		// echo print_r($schema_provider['@graph'][3]['mentions']); // test

	// // BreadcrumbList
	// echo print_r($schema_provider['@graph'][4]); // test

	// // Physician
	// echo print_r($schema_provider['@graph'][5]); // test

		// // Physician
		// echo print_r($schema_provider['@graph'][5]['containedInPlace']); // test

		// // Physician
		// echo print_r($schema_provider['@graph'][5]['location']); // test

	// // Person
	// echo print_r($schema_provider['@graph'][6]); // test

	// // Specific variable
	// echo print_r($schema_provider_location_ref); // test

	echo '</pre>'; // test

// Reusable test display lines

	// echo '<p>$foo = ' . ( is_array($foo) ? 'Array' : ( is_object($foo) ? 'Object' : ( is_null($foo) ? 'Null' : ( $foo ) ) ) ) . '</p>'; // test
	// if ( is_array($foo) || is_object($foo) ) { echo '<pre>'; print_r($foo); echo '</pre>'; } // test
