<?php

// EndorsementRating

	/*
	 * Thing > Intangible > Rating > EndorsementRating
	 * 
	 * An EndorsementRating is a rating that expresses some level of endorsement, for 
	 * example inclusion in a "critic's pick" blog, a "Like" or "+1" on a social 
	 * network. It can be considered the result of an EndorseAction in which the 
	 * object of the action is rated positively by some agent. As is common elsewhere 
	 * in schema.org, it is sometimes more useful to describe the results of such an 
	 * action without explicitly describing the Action.
	 * 
	 * An EndorsementRating may be part of a numeric scale or organized system, but 
	 * this is not required: having an explicit type for indicating a positive, 
	 * endorsement rating is particularly useful in the absence of numeric scales as 
	 * it helps consumers understand that the rating is broadly positive.
	 */

	function uamswp_fad_schema_endorsementrating(
		$schema, // array // Main schema array
		// EndorsementRating (no property vars)
		// Rating
			$author = '', // author
			$bestRating = '', // bestRating
			$ratingExplanation = '', // ratingExplanation
			$ratingValue = '', // ratingValue
			$reviewAspect = '', // reviewAspect
			$worstRating = '', // worstRating
		// Intangible (no property vars)
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

			// Inherited properties from Thing

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

			// Inherited properties from Intangible (Thing > Intangible)

				// Do nothing (no property vars)

			// Properties from Rating (Thing > Intangible > Rating)

				$author = ( isset($author) && !empty($author) ) ? $author : '';
				$bestRating = ( isset($bestRating) && !empty($bestRating) ) ? $bestRating : '';
				$ratingExplanation = ( isset($ratingExplanation) && !empty($ratingExplanation) ) ? $ratingExplanation : '';
				$ratingValue = ( isset($ratingValue) && !empty($ratingValue) ) ? $ratingValue : '';
				$reviewAspect = ( isset($reviewAspect) && !empty($reviewAspect) ) ? $reviewAspect : '';
				$worstRating = ( isset($worstRating) && !empty($worstRating) ) ? $worstRating : '';

			// Properties from EndorsementRating (Thing > Intangible > Rating > EndorsementRating)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_rating(
					$schema, // array // Main schema array
					// Rating
						$author, // author
						$bestRating, // bestRating
						$ratingExplanation, // ratingExplanation
						$ratingValue, // ratingValue
						$reviewAspect, // reviewAspect
						$worstRating, // worstRating
					// Intangible (no property vars)
					// Thing
						$additionalType, // additionalType
						$alternateName, // alternateName
						$description, // description
						$disambiguatingDescription, // disambiguatingDescription
						$identifier, // identifier
						$image, // image
						$mainEntityOfPage, // mainEntityOfPage
						$name, // name
						$potentialAction, // potentialAction
						$sameAs, // sameAs
						$subjectOf, // subjectOf
						$url // url
				);

			// Properties from EndorsementRating (Thing > Intangible > Rating > EndorsementRating)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);
			$schema = array_unique($schema, SORT_REGULAR);

		return $schema;

	}