<?php

// Clinical resource-specific schema
// Merge into $schema_base['@graph']

	$schema_graph = array(
		// MedicalWebPage
		array(
			'@type' => 'MedicalWebPage',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage', // Replace 'foo' with clinical resource item slug
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name' // Replace 'foo' with clinical resource item slug
			),
			'author' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'breadcrumb' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#BreadcrumbList' // Replace 'foo' with clinical resource item slug
			),
			'creator' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'dateModified' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'datePublished' => 'foo', // Replace 'foo' with date value in ISO 8601 date format.
			'inLanguage' => 'English',
			'isPartOf' => array(
				'@id' => 'https://uamshealth.com/#WebSite'
			),
			'maintainer' => array(
				'@id' => 'https://uams.edu/#CollegeOrUniversity'
			),
			'medicalAudience' => array(
				array(
					'@type' => 'Patient',
					'name' => 'Patient',
					'geographicArea' => 'Arkansas'
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			),
			'mentions' => array(
				array(
					'foo' => 'bar'
				)
			),
			'primaryImageOfPage' => 'foo', // Replace 'foo' with URL of featured image
			'significantLink' => array(
				'foo' // Replace 'foo' with URL to related ontology items // Repeat as necessary
			),
			'sourceOrganization' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'url' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#URL', // Replace 'foo' with clinical resource item slug
				'https://uamshealth.com/provider/foo/' // Replace 'foo' with provider profile slug
			),
			'video' => 'foo' // Replace 'foo' with URL to video if relevant
		),
		// BreadcrumbList
		array(
			'@type' => 'BreadcrumbList',
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#BreadcrumbList', // Replace 'foo' with clinical resource item slug
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
						'@id' => 'https://uamshealth.com/clinical-resource/foo/#MedicalWebPage' // Replace 'foo' with clinical resource item slug
					)
				)
			)
		),
		// CreativeWork
			// Article = 'Article'
			// Infographic = 'ImageObject'
			// Video = 'VideoObject'
			// Document = 'DigitalDocument'
		array(
			'@type' => 'CreativeWork', // Replace 'CreativeWork' with the relevant specific sub-type (i.e., 'Article', 'ImageObject', 'VideoObject', 'DigitalDocument')
			'@id' => 'https://uamshealth.com/clinical-resource/foo/#Physician', // Replace 'foo' with clinical resource item slug
			'name' => array(
				'@id' => 'https://uamshealth.com/clinical-resource/foo/#Name', // Replace 'foo' with clinical resource item slug
				'foo', // Replace 'foo' with title of the clinical resource item
			),
			'about' => array(
				array( // Repeat for each related ontology item (e.g., Provider, Location)
					'@type' => 'Thing', // Replace 'Thing' with appropriate Type
					'foo' => 'bar' // Replace 'foo'/'bar' pair with appropriate property/value pairs as relevant
				)
			),
			'abstract' => 'foo', // Replace 'foo' with content of Short Description input
			'audience' => array(
				array(
					'@type' => 'Patient',
					'name' => 'Patient',
					'geographicArea' => 'Arkansas'
				),
				'Clinician' // MedicalAudienceType (Enumeration Type) :: Clinician (Enumeration Member)
			'audio' => 'foo', // Replace 'foo' with ___
			'author' => array(
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'creator' => array( // Remove or replace this if the item's content is syndicated from another source
				'@id' => 'https://uamshealth.com/#MedicalOrganization'
			),
			'dateModified' => 'foo', // Replace 'foo' with ___
			'datePublished' => 'foo', // Replace 'foo' with ___
			'hasPart' => 'foo', // Replace 'foo' with ___
			'inLanguage' => 'foo', // Replace 'foo' with ___
			'interactionStatistic' => 'foo', // Replace 'foo' with ___
			'interactivityType' => 'foo', // Replace 'foo' with ___
			'interpretedAsClaim' => 'foo', // Replace 'foo' with ___
			'isAccessibleForFree' => 'foo', // Replace 'foo' with ___
			'isBasedOn' => 'foo', // Replace 'foo' with ___
			'isBasedOnUrl' => 'foo', // Replace 'foo' with ___
			'isFamilyFriendly' => 'foo', // Replace 'foo' with ___
			'isPartOf' => 'foo', // Replace 'foo' with ___
			'keywords' => 'foo', // Replace 'foo' with ___
			'learningResourceType' => 'foo', // Replace 'foo' with ___
			'license' => 'foo', // Replace 'foo' with ___
			'locationCreated' => 'foo', // Replace 'foo' with ___
			'mainEntity' => 'foo', // Replace 'foo' with ___
			'mainEntityOfPage' => 'foo', // Replace 'foo' with ___
			'maintainer' => 'foo', // Replace 'foo' with ___
			'material' => 'foo', // Replace 'foo' with ___
			'materialExtent' => 'foo', // Replace 'foo' with ___
			'mentions' => 'foo', // Replace 'foo' with ___
			'offers' => 'foo', // Replace 'foo' with ___
			'pattern' => 'foo', // Replace 'foo' with ___
			'position' => 'foo', // Replace 'foo' with ___
			'potentialAction' => 'foo', // Replace 'foo' with ___
			'producer' => 'foo', // Replace 'foo' with ___
			'provider' => 'foo', // Replace 'foo' with ___
			'publication' => 'foo', // Replace 'foo' with ___
			'publisher' => 'foo', // Replace 'foo' with ___
			'publisherImprint' => 'foo', // Replace 'foo' with ___
			'publishingPrinciples' => 'foo', // Replace 'foo' with ___
			'recordedAt' => 'foo', // Replace 'foo' with ___
			'releasedEvent' => 'foo', // Replace 'foo' with ___
			'review' => 'foo', // Replace 'foo' with ___
			'reviews' => 'foo', // Replace 'foo' with ___
			'sameAs' => 'foo', // Replace 'foo' with ___
			'schemaVersion' => 'foo', // Replace 'foo' with ___
			'sdDatePublished' => 'foo', // Replace 'foo' with ___
			'sdLicense' => 'foo', // Replace 'foo' with ___
			'sdPublisher' => 'foo', // Replace 'foo' with ___
			'size' => 'foo', // Replace 'foo' with ___
			'sourceOrganization' => 'foo', // Replace 'foo' with ___
			'spatial' => 'foo', // Replace 'foo' with ___
			'spatialCoverage' => 'foo', // Replace 'foo' with ___
			'sponsor' => 'foo', // Replace 'foo' with ___
			'subjectOf' => 'foo', // Replace 'foo' with ___
			'teaches' => 'foo', // Replace 'foo' with ___
			'temporal' => 'foo', // Replace 'foo' with ___
			'temporalCoverage' => 'foo', // Replace 'foo' with ___
			'text' => 'foo', // Replace 'foo' with ___
			'thumbnail' => 'foo', // Replace 'foo' with ___
			'thumbnailUrl' => 'foo', // Replace 'foo' with ___
			'url' => 'https://uamshealth.com/clinical-resource/foo/', // Replace 'foo' with clinical resource item slug
			// Article
			'articleBody' => 'foo', // Replace 'foo' with content of the article text
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
			'timeRequired' => 'foo', // Replace 'foo' with 9th grade reading speed of article (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'speakable' => array(
				'@type' => 'SpeakableSpecification',
				'cssSelector' => array(
					'#foo' // Replace 'foo' with ID of element containing the article text
				)
			),
			'wordCount' => 'foo', // Replace 'foo' with word count of the article text // Integer (Data Type)
			// ImageObject
			'contentSize' => 'foo', // Replace 'foo' with the image's file size in (mega/kilo)bytes
			'contentUrl' => 'foo', // Replace 'foo' with the image file's URL
			'embeddedTextCaption' => 'foo', // Replace 'foo' with content of infographic transcript input
			'encodingFormat' => 'foo', // Replace 'foo' with the image's media type expressed using a MIME format (e.g., 'image/jpeg')
			'height' => 'foo', // Replace 'foo' with the image's height
			'representativeOfPage' => true,
			'timeRequired' => 'foo', // Replace 'foo' with combo of 9th grade reading speed of Short Description + Transcript (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'width' => 'foo' // Replace 'foo' with the image's width
			// VideoObject
			'duration' => 'foo', // Replace 'foo' with the duration of the video (in ISO 8601 duration format) if that info is available from YouTube/Vimeo
			'embedUrl' => 'foo', // Replace 'foo' with the URL pointing to a player for the video. In general, this is the information in the src element of an embed tag.
			'timeRequired' => 'foo', // Replace 'foo' with combo of 9th grade reading speed of Short Description + Transcript OR Short Description + video duration, whichever is greater (in ISO 8601 duration format, https://en.wikipedia.org/wiki/ISO_8601#Durations)
			'transcript' => 'foo', // Replace 'foo' with content of video transcript input
			'videoFrameSize' => 'foo', // Replace 'foo' with the frame size of the video if that info is available from YouTube/Vimeo
			'videoQuality' => 'foo', // Replace 'foo' with the quality of the video if that info is available from YouTube/Vimeo
			// DigitalDocument
			'hasDigitalDocumentPermission' => array(
				'@type' => 'DigitalDocumentPermission',
				'permissionType' => 'ReadPermission',
				'grantee' => array(
					'@type' => 'Audience',
					'audienceType' => 'public'
				)
			)

		)
	);