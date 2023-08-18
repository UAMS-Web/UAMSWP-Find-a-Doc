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
			'associatedMedia' => 'foo', // Replace 'foo' with ___
			'audience' => 'foo', // Replace 'foo' with ___
			'audio' => 'foo', // Replace 'foo' with ___
			'author' => 'foo', // Replace 'foo' with ___
			'award' => 'foo', // Replace 'foo' with ___
			'awards' => 'foo', // Replace 'foo' with ___
			'character' => 'foo', // Replace 'foo' with ___
			'citation' => 'foo', // Replace 'foo' with ___
			'comment' => 'foo', // Replace 'foo' with ___
			'commentCount' => 'foo', // Replace 'foo' with ___
			'conditionsOfAccess' => 'foo', // Replace 'foo' with ___
			'contentLocation' => 'foo', // Replace 'foo' with ___
			'contentRating' => 'foo', // Replace 'foo' with ___
			'contentReferenceTime' => 'foo', // Replace 'foo' with ___
			'contributor' => 'foo', // Replace 'foo' with ___
			'copyrightHolder' => 'foo', // Replace 'foo' with ___
			'copyrightNotice' => 'foo', // Replace 'foo' with ___
			'copyrightYear' => 'foo', // Replace 'foo' with ___
			'correction' => 'foo', // Replace 'foo' with ___
			'countryOfOrigin' => 'foo', // Replace 'foo' with ___
			'creativeWorkStatus' => 'foo', // Replace 'foo' with ___
			'creator' => 'foo', // Replace 'foo' with ___
			'creditText' => 'foo', // Replace 'foo' with ___
			'dateCreated' => 'foo', // Replace 'foo' with ___
			'dateModified' => 'foo', // Replace 'foo' with ___
			'datePublished' => 'foo', // Replace 'foo' with ___
			'description' => 'foo', // Replace 'foo' with ___
			'disambiguatingDescription' => 'foo', // Replace 'foo' with ___
			'discussionUrl' => 'foo', // Replace 'foo' with ___
			'editEIDR' => 'foo', // Replace 'foo' with ___
			'editor' => 'foo', // Replace 'foo' with ___
			'educationalAlignment' => 'foo', // Replace 'foo' with ___
			'educationalLevel' => 'foo', // Replace 'foo' with ___
			'educationalUse' => 'foo', // Replace 'foo' with ___
			'encoding' => 'foo', // Replace 'foo' with ___
			'encodingFormat' => 'foo', // Replace 'foo' with ___
			'encodings' => 'foo', // Replace 'foo' with ___
			'exampleOfWork' => 'foo', // Replace 'foo' with ___
			'expires' => 'foo', // Replace 'foo' with ___
			'fileFormat' => 'foo', // Replace 'foo' with ___
			'funder' => 'foo', // Replace 'foo' with ___
			'funding' => 'foo', // Replace 'foo' with ___
			'genre' => 'foo', // Replace 'foo' with ___
			'hasPart' => 'foo', // Replace 'foo' with ___
			'headline' => 'foo', // Replace 'foo' with ___
			'identifier' => 'foo', // Replace 'foo' with ___
			'image' => 'foo', // Replace 'foo' with ___
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
			'timeRequired' => 'foo', // Replace 'foo' with ___
			'translationOfWork' => 'foo', // Replace 'foo' with ___
			'translator' => 'foo', // Replace 'foo' with ___
			'typicalAgeRange' => 'foo', // Replace 'foo' with ___
			'url' => 'foo', // Replace 'foo' with ___
			'usageInfo' => 'foo', // Replace 'foo' with ___
			'version' => 'foo', // Replace 'foo' with ___
			'video' => 'foo', // Replace 'foo' with ___
			'workExample' => 'foo', // Replace 'foo' with ___
			'workTranslation' => 'foo', // Replace 'foo' with ___
			// Article
			'articleBody' => 'foo', // Replace 'foo' with content of the article text
			'speakable' => array(
				'@type' => 'SpeakableSpecification',
				'cssSelector' => array(
					'#foo' // Replace 'foo' with ID of element containing the article text
				)
			),
			'wordCount' => 'foo', // Replace 'foo' with word count of the article text // Integer (Data Type)
			// ImageObject
			'embeddedTextCaption' => 'foo', // Replace 'foo' with content of infographic transcript input
			'representativeOfPage' => true,
			// VideoObject
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