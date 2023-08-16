<?php

// EmployerAggregateRating
	/*
	 * Thing > Intangible > Rating > AggregateRating > EmployerAggregateRating
	 * 
	 * An aggregate rating of an Organization related to its role as an employer.
	 */

	function uamswp_fad_schema_employeraggregaterating(
		$schema, // array // Main schema array
		// EmployerAggregateRating (no property vars)
		// AggregateRating
			$itemReviewed = '', // itemReviewed
			$ratingCount = '', // ratingCount
			$reviewCount = '', // reviewCount
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

			// Inherited properties from Rating (Thing > Intangible > Rating)

				$author = ( isset($author) && !empty($author) ) ? $author : '';
				$bestRating = ( isset($bestRating) && !empty($bestRating) ) ? $bestRating : '';
				$ratingExplanation = ( isset($ratingExplanation) && !empty($ratingExplanation) ) ? $ratingExplanation : '';
				$ratingValue = ( isset($ratingValue) && !empty($ratingValue) ) ? $ratingValue : '';
				$reviewAspect = ( isset($reviewAspect) && !empty($reviewAspect) ) ? $reviewAspect : '';
				$worstRating = ( isset($worstRating) && !empty($worstRating) ) ? $worstRating : '';

			// Inherited properties from AggregateRating (Thing > Intangible > Rating > AggregateRating)

				$itemReviewed = ( isset($itemReviewed) && !empty($itemReviewed) ) ? $itemReviewed : '';
				$ratingCount = ( isset($ratingCount) && !empty($ratingCount) ) ? $ratingCount : '';
				$reviewCount = ( isset($reviewCount) && !empty($reviewCount) ) ? $reviewCount : '';

			// Properties from EmployerAggregateRating (Thing > Intangible > Rating > AggregateRating > EmployerAggregateRating)

				// Do nothing (no property vars)

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_rating(
					$schema, // array // Main schema array
					// AggregateRating
						$itemReviewed, // itemReviewed
						$ratingCount, // ratingCount
						$reviewCount, // reviewCount
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

			// Properties from EmployerAggregateRating (Thing > Intangible > Rating > AggregateRating > EmployerAggregateRating)

				// Do nothing (no property vars)

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}