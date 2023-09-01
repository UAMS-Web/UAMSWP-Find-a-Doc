<?php

// Common property values

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

// area of expertise-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'@type' => 'MedicalWebPage',
			'@id' => 'https://uamshealth.com/expertise/foo/#MedicalWebPage', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo' // Replace 'foo' with the title of the page
			),
			'headline' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#Name' // Replace URL up to the hash with relevant URL
			),
			'about' => array(
				'foo' // Replace 'foo' with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
				array( // Populate values for related condition items, repeating as necessary
					'@type' => 'MedicalCondition',
					'foo' => 'bar'
				),
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#BreadcrumbList' // Replace URL up to the hash with relevant URL
			),
			'creator' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'dateModified' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'datePublished' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'description' => 'foo', // Replace 'foo' with excerpt / short description
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/#WebSite'
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'medicalAudience' => $schema_common_medicalAudience,
			'primaryImageOfPage' => array( // Header background image
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => true,
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'specialty' => array(
				'foo' // Replace 'foo' with MedicalSpecialty (Enumeration Type) associated with the area of expertise // Repeat as necessary
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/expertise/foo/#URL', // Replace URL up to the hash with relevant URL
				'https://uamshealth.com/provider/foo/' // Replace 'foo' with provider profile slug
			)
		),
		// BreadcrumbList
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/expertise/foo/#BreadcrumbList', // Replace URL up to the hash with relevant URL
			'itemListElement' => array(
				array(
					'@type' => 'ListItem',
					'position' => 1,
					'item' => array(
						'@type' => 'MedicalWebPage',
						'@id' => 'https://uamshealth.com',
						'url' => 'https://uamshealth.com',
						'name' => 'UAMS Health'
					)
				),
				array(
					'@type' => 'ListItem',
					'position' => 2,
					'item' => array(
						'@type' => 'MedicalWebPage',
						'@id' => 'https://uamshealth.com/provider/',
						'url' => 'https://uamshealth.com/provider/',
						'name' => 'Providers'
					)
				),
				array( // Repeat as necessary for a descendant item
					'@type' => 'ListItem',
					'position' => 3, // Adjust position integer as necessary for a descendant item
					'item' => array(
						'@id' => 'https://uamshealth.com/expertise/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
					)
				)
			)
		)
	);