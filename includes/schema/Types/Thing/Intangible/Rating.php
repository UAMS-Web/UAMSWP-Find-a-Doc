<?php

// Rating

	/*
	 * Thing > Intangible > Rating
	 * 
	 * A rating is an evaluation on a numeric scale, such as 1 to 5 stars.
	 */

	function uamswp_fad_schema_rating(
		$schema, // array // Main schema array
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

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_intangible(
					$schema, // array // Main schema array
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

			// Properties from Rating (Thing > Intangible > Rating)

				// author

					/* 
					 * Expected Type:
					 *     Organization
					 *     Person
					 * 
					 * The author of this content or rating. Please note that author is special in 
					 * that HTML 5 provides a special mechanism for indicating authorship via the rel 
					 * tag. That is equivalent to this and may be used interchangeably.
					 */

					$schema['author'] = $author;

				// bestRating

					/* 
					 * Expected Type:
					 *     Number
					 *     Text
					 * 
					 * The highest value allowed in this rating system. If bestRating is omitted, 5 is 
					 * assumed.
					 */

					$schema['bestRating'] = $bestRating;

				// ratingExplanation

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * A short explanation (e.g. one to two sentences) providing background context 
					 * and other information that led to the conclusion expressed in the rating. This 
					 * is particularly applicable to ratings associated with "fact check" markup using 
					 * ClaimReview.

					 */

					$schema['ratingExplanation'] = $ratingExplanation;

				// ratingValue

					/* 
					 * Expected Type:
					 *     Number
					 *     Text
					 * 
					 * The rating for the content.
					 * 
					 * Usage guidelines:
					 * 
					 *     - Use values from 0123456789 (Unicode 'DIGIT ZERO' (U+0030) to 'DIGIT NINE' 
					 *       (U+0039)) rather than superficially similar Unicode symbols.
					 * 
					 *     - Use '.' (Unicode 'FULL STOP' (U+002E)) rather than ',' to indicate a 
					 *       decimal point. Avoid using these symbols as a readability separator.

					 */

					$schema['ratingValue'] = $ratingValue;

				// reviewAspect

					/* 
					 * Expected Type:
					 *     Text
					 * 
					 * This Review or Rating is relevant to this part or facet of the itemReviewed.
					 */

					$schema['reviewAspect'] = $reviewAspect;

				// worstRating

					/* 
					 * Expected Type:
					 *     Number
					 *     Text
					 * 
					 * The lowest value allowed in this rating system. If worstRating is omitted, 1 is 
					 * assumed.
					 */

					$schema['worstRating'] = $worstRating;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// AggregateRating
	include_once __DIR__ . '/Rating/AggregateRating.php';

	// EndorsementRating
	include_once __DIR__ . '/Rating/EndorsementRating.php';