<?php

// Thing

	/*
	 * The most generic type of item.
	 */

	 function uamswp_fad_schema_thing(
		$schema, // array // Main schema array
		// Thing
			$additionalType = '', // additionalType
			$alternateName = '', // alternateName
			$description = '', // description
			$disambiguatingDescription = '', // disambiguatingDescription
			$identifier = '', // identifier
			$image = '', // image
			$mainEntityOfPage = '', // mainEntityOfPage
			$name = '', // name
			$potentialAction = '', // potentialAction
			$sameAs = '', // sameAs
			$subjectOf = '', // subjectOf
			$url = '' // url
	) {

		// Check/define variables

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

			// Properties from Thing

				$additionalType = ( isset($additionalType) && !empty($additionalType) ) ? $additionalType : '';
				$alternateName = ( isset($alternateName) && !empty($alternateName) ) ? $alternateName : '';
				$description = ( isset($description) && !empty($description) ) ? $description : '';
				$disambiguatingDescription = ( isset($disambiguatingDescription) && !empty($disambiguatingDescription) ) ? $disambiguatingDescription : '';
				$identifier = ( isset($identifier) && !empty($identifier) ) ? $identifier : '';
				$image = ( isset($image) && !empty($image) ) ? $image : '';
				$mainEntityOfPage = ( isset($mainEntityOfPage) && !empty($mainEntityOfPage) ) ? $mainEntityOfPage : '';
				$name = ( isset($name) && !empty($name) ) ? $name : '';
				$potentialAction = ( isset($potentialAction) && !empty($potentialAction) ) ? $potentialAction : '';
				$sameAs = ( isset($sameAs) && !empty($sameAs) ) ? $sameAs : '';
				$subjectOf = ( isset($subjectOf) && !empty($subjectOf) ) ? $subjectOf : '';
				$url = ( isset($url) && !empty($url) ) ? $url : '';

		// Add values to the schema array

			// Properties from Thing

				// additionalType

					/* 
					 * Expected Type:
					 *     DataType > Text
					 *     DataType > Text > URL
					 * 
					 * An additional type for the item, typically used for adding more specific types 
					 * from external vocabularies in microdata syntax. This is a relationship between 
					 * something and a class that the thing is in. Typically the value is a 
					 * URI-identified RDF class, and in this case corresponds to the use of rdf:type 
					 * in RDF. Text values can be used sparingly, for cases where useful information 
					 * can be added without their being an appropriate schema to reference. In the 
					 * case of text values, the class label should follow the schema.org style guide.
					 */

					$schema['additionalType'] = $additionalType;

				// alternateName

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * An alias for the item.
					 */

					$schema['alternateName'] = $alternateName;

				// description

					/* 
					 * Expected Type:
					 *     DataType > Text
					 *     Thing > CreativeWork > MediaObject > TextObject
					 * 
					 * A description of the item.
					 */

					$schema['description'] = $description;

				// disambiguatingDescription

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * A sub property of description. A short description of the item used to 
					 * disambiguate from other, similar items. Information from other properties (in 
					 * particular, name) may be necessary for the description to be useful for 
					 * disambiguation.
					 */

					$schema['disambiguatingDescription'] = $disambiguatingDescription;

				// identifier

					/* 
					 * Expected Type:
					 *     Thing > Intangible > StructuredValue > PropertyValue
					 *     DataType > Text
					 *     DataType > Text > URL
					 * 
					 * The identifier property represents any kind of identifier for any kind of 
					 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated 
					 * properties for representing many of these, either as textual strings or as URL 
					 * (URI) links. See background notes for more 
					 * details.
					 */

					$schema['identifier'] = $identifier;

				// image

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork > MediaObject > ImageObject
					 *     DataType > Text > URL
					 * 
					 * An image of the item. This can be a URL or a fully described 
					 * ImageObject.
					 */

					$schema['image'] = $image;

				// mainEntityOfPage

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork
					 *     DataType > Text > URL
					 * 
					 * Indicates a page (or other CreativeWork) for which this thing is the main 
					 * entity being described. See background notes for details. Inverse 
					 * property: mainEntity
					 */

					$schema['mainEntityOfPage'] = $mainEntityOfPage;

				// name

					/* 
					 * Expected Type:
					 *     DataType > Text
					 * 
					 * The name of the item.
					 */

					$schema['name'] = $name;

				// potentialAction

					/* 
					 * Expected Type:
					 *     Thing > Action
					 * 
					 * Indicates a potential Action, which describes an idealized action in which this 
					 * thing would play an 'object' role.
					 */

					$schema['potentialAction'] = $potentialAction;

				// sameAs

					/* 
					 * Expected Type:
					 *     DataType > Text > URL
					 * 
					 * URL of a reference Web page that unambiguously indicates the item's 
					 * identity. (e.g., the URL of the item's Wikipedia page, Wikidata entry, or 
					 * official website).
					 */

					$schema['sameAs'] = $sameAs;

				// subjectOf

					/* 
					 * Expected Type:
					 *     Thing > CreativeWork
					 *     Thing > Event
					 * 
					 * A CreativeWork or Event about this Thing. Inverse property: about
					 */

					$schema['subjectOf'] = $subjectOf;

				// url

					/* 
					 * Expected Type:
					 *     DataType > Text > URL
					 * 
					 * URL of the item.
					 */

					$schema['url'] = $url;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// Action
	include_once __DIR__ . '/Thing/Action.php';

	// BioChemEntity
	include_once __DIR__ . '/Thing/BioChemEntity.php';

	// CreativeWork
	include_once __DIR__ . '/Thing/CreativeWork.php';

	// Event
	include_once __DIR__ . '/Thing/Event.php';

	// Intangible
	include_once __DIR__ . '/Thing/Intangible.php';

	// MedicalEntity
	include_once __DIR__ . '/Thing/MedicalEntity.php';

	// Organization
	include_once __DIR__ . '/Thing/Organization.php';

	// Person
	include_once __DIR__ . '/Thing/Person.php';

	// Place
	include_once __DIR__ . '/Thing/Place.php';

	// Product
	include_once __DIR__ . '/Thing/Product.php';

	// Taxon
	include_once __DIR__ . '/Thing/Taxon.php';