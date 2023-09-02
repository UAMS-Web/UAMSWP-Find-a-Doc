<?php

// Common property values

	include( UAMS_FAD_PATH . '/templates/parts/vars/page/schema/common/property_values.php' );

// Clinical resource-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'@type' => 'MedicalWebPage',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name' // Replace URL up to the hash with relevant URL
			),
			'headline' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name' // Replace URL up to the hash with relevant URL
			),
			'about' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#CreativeWork' // Replace URL up to the hash with relevant URL // Replace 'CreativeWork' with subtype relevant to the clinical resource item
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#BreadcrumbList' // Replace URL up to the hash with relevant URL
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
			'mainEntity' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#CreativeWork' // Replace URL up to the hash with relevant URL // Replace 'CreativeWork' with subtype relevant to the clinical resource item
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'medicalAudience' => $schema_common_medicalAudience,
			'mentions' => array(
				array(
					'@id' => 'https://uamshealth.com/clinical-resource/foo/#Article' // Replace URL up to the hash with relevant URL
				),
				array( // Populate values for related ontology items, repeating as necessary
					'@id' => 'Thing',
					'foo' => 'bar'
				)
			),
			'primaryImageOfPage' => array( // Keep if infographic clinical resource // Infographic image
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => true,
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'significantLink' => array(
				'foo' // Replace 'foo' with URL to related ontology items // Repeat as necessary
			),
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL', // Replace URL up to the hash with relevant URL
				'https://uamshealth.com/clinical-resource/foo/' // Replace URL with relevant URL
			),
			'video' => 'foo' // Replace 'foo' with URL to video if relevant
		),
		// BreadcrumbList
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#BreadcrumbList', // Replace URL up to the hash with relevant URL
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
				array(
					'@type' => 'ListItem',
					'position' => 3,
					'item' => array(
						'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
					)
				)
			)
		),
		// Article Clinical Resource
		array(
			'@type' => 'Article',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#Article', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo', // Replace 'foo' with title of the clinical resource item
			),
			'abstract' => 'foo', // Replace 'foo' with content of Short Description input
			'articleBody' => 'foo', // Replace 'foo' with content of the article text
			'audience' => $schema_common_audience,
			'creator' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'dateModified' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'datePublished' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'image' => array( // Featured image
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => 'foo', // Replace 'foo' with whatever value we determine to be typical of a featured image // Boolean (Data Type)
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'isAccessibleForFree' => true,
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'sameAs' => 'foo', // Replace 'foo' with the URL of the syndicated source item
			'sourceOrganization' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'speakable' => array(
				'@type' => 'SpeakableSpecification',
				'cssSelector' => array(
					'#foo' // Replace 'foo' with ID of element containing the article text
				)
			),
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'timeRequired' => 'foo', // Replace 'foo' with 9th grade reading speed of article (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL' // Replace URL up to the hash with relevant URL
			),
			'wordCount' => 'foo' // Replace 'foo' with word count of the article text // Integer (Data Type)
		),
		// Infographic Clinical Resource
		array(
			'@type' => 'ImageObject',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#ImageObject', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo', // Replace 'foo' with title of the clinical resource item
			),
			'abstract' => 'foo', // Replace 'foo' with content of Short Description input
			'audience' => $schema_common_audience,
			'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
			'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
			'creator' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'dateModified' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'datePublished' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'embeddedTextCaption' => 'foo', // Replace 'foo' with content of infographic transcript input
			'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
			'height' => 'foo', // Replace 'foo' with the image's height
			'isAccessibleForFree' => true,
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'representativeOfPage' => true,
			'sameAs' => 'foo', // Replace 'foo' with the URL of the syndicated source item
			'sourceOrganization' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'thumbnail' => array( // Featured image
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => true,
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'timeRequired' => 'foo', // Replace 'foo' with combo of 9th grade reading speed of Short Description + Transcript (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL' // Replace URL up to the hash with relevant URL
			),
			'width' => 'foo' // Replace 'foo' with the image's width
		),
		// Video Clinical Resource
		array(
			'@type' => 'VideoObject',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#VideoObject', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo', // Replace 'foo' with title of the clinical resource item
			),
			'abstract' => 'foo', // Replace 'foo' with content of Short Description input
			'audience' => $schema_common_audience,
			'creator' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'dateModified' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'datePublished' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'duration' => 'foo', // Replace 'foo' with the duration of the video (in ISO 8601 duration format) if that info is available from YouTube/Vimeo
			'embedUrl' => 'foo', // Replace 'foo' with the URL pointing to a player for the video. In general, this is the information in the src element of an embed tag.
			'isAccessibleForFree' => true,
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'sameAs' => 'foo', // Replace 'foo' with the URL of the syndicated source item
			'sourceOrganization' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'thumbnail' => array( // Featured image
				'@type' => 'ImageObject',
				'caption' => 'foo', // Replace 'foo' with the image's alt text
				'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
				'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
				'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
				'height' => 'foo', // Replace 'foo' with the image's height
				'representativeOfPage' => true,
				'width' => 'foo' // Replace 'foo' with the image's width
			),
			'timeRequired' => 'foo', // Replace 'foo' with combo of 9th grade reading speed of Short Description + Transcript OR Short Description + video duration, whichever is greater (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'transcript' => 'foo', // Replace 'foo' with content of video transcript input
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL' // Replace URL up to the hash with relevant URL
			),
			'videoFrameSize' => 'foo', // Replace 'foo' with the frame size of the video if that info is available from YouTube/Vimeo
			'videoQuality' => 'foo' // Replace 'foo' with the quality of the video if that info is available from YouTube/Vimeo
		),
		// Document Clinical Resource
		array(
			'@type' => 'DigitalDocument',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#DigitalDocument', // Replace URL up to the hash with relevant URL
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name', // Replace URL up to the hash with relevant URL
				'foo', // Replace 'foo' with title of the clinical resource item
			),
			'abstract' => 'foo', // Replace 'foo' with content of Short Description input
			'audience' => $schema_common_audience,
			'creator' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'dateModified' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'datePublished' => 'foo', // Replace 'foo' with the date value in ISO 8601 date format
			'hasDigitalDocumentPermission' => array(
				'@type' => 'DigitalDocumentPermission',
				'permissionType' => 'ReadPermission',
				'grantee' => array(
					'@type' => 'Audience',
					'audienceType' => 'public'
				)
			),
			'isAccessibleForFree' => true,
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'mainEntityOfPage' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'sameAs' => 'foo', // Replace 'foo' with the URL of the syndicated source item
			'sourceOrganization' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'subjectOf' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace URL up to the hash with relevant URL
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL' // Replace URL up to the hash with relevant URL
			)
		)
	);