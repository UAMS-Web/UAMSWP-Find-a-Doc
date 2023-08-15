<?php

// Event

	/*
	 * Thing > Event
	 * 
	 * 
	 */

	function uamswp_fad_schema_event(
		$schema, // array // Main schema array
		// Event
			$foo, // foo
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

			// Properties from Event (Thing > Event)

				$foo = ( isset($foo) && !empty($foo) ) ? $foo : '';

		// Add values to the schema array

			// Inherited properties

				$schema = uamswp_fad_schema_thing(
					$schema, // array // Main schema array
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

			// Properties from Event

				// foo

					/* 
					 * Expected Type:
					 *     bar
					 * 
					 * 
					 */

					$schema['foo'] = $foo;

		// Remove any empty values from the schema array

			$schema = array_filter($schema);

		return $schema;

	}

	// BusinessEvent

		/*
		 * Thing > Event > BusinessEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_businessevent(
			
		) {
			
		}

	// ChildrensEvent

		/*
		 * Thing > Event > ChildrensEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_childrensevent(
			
		) {
			
		}

	// ComedyEvent

		/*
		 * Thing > Event > ComedyEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_comedyevent(
			
		) {
			
		}

	// CourseInstance

		/*
		 * Thing > Event > CourseInstance
		 * 
		 * 
		 */

		function uamswp_fad_schema_courseinstance(
			
		) {
			
		}

	// DanceEvent

		/*
		 * Thing > Event > DanceEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_danceevent(
			
		) {
			
		}

	// DeliveryEvent

		/*
		 * Thing > Event > DeliveryEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_deliveryevent(
			
		) {
			
		}

	// EducationEvent

		/*
		 * Thing > Event > EducationEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_educationevent(
			
		) {
			
		}

	// EventSeries

		/*
		 * Thing > Event > EventSeries
		 * 
		 * 
		 */

		function uamswp_fad_schema_eventseries(
			
		) {
			
		}

	// ExhibitionEvent

		/*
		 * Thing > Event > ExhibitionEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_exhibitionevent(
			
		) {
			
		}

	// Festival

		/*
		 * Thing > Event > Festival
		 * 
		 * 
		 */

		function uamswp_fad_schema_festival(
			
		) {
			
		}

	// FoodEvent

		/*
		 * Thing > Event > FoodEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_foodevent(
			
		) {
			
		}

	// Hackathon

		/*
		 * Thing > Event > Hackathon
		 * 
		 * 
		 */

		function uamswp_fad_schema_hackathon(
			
		) {
			
		}

	// LiteraryEvent

		/*
		 * Thing > Event > LiteraryEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_literaryevent(
			
		) {
			
		}

	// MusicEvent

		/*
		 * Thing > Event > MusicEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_musicevent(
			
		) {
			
		}

	// PublicationEvent

		/*
		 * Thing > Event > PublicationEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_publicationevent(
			
		) {
			
		}

		// BroadcastEvent

			/*
			 * Thing > Event > PublicationEvent > BroadcastEvent
			 * 
			 * 
			 */

			function uamswp_fad_schema_broadcastevent(
				
			) {
				
			}

		// OnDemandEvent

			/*
			 * Thing > Event > PublicationEvent > OnDemandEvent
			 * 
			 * 
			 */

			function uamswp_fad_schema_ondemandevent(
				
			) {
				
			}

	// SaleEvent

		/*
		 * Thing > Event > SaleEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_saleevent(
			
		) {
			
		}

	// ScreeningEvent

		/*
		 * Thing > Event > ScreeningEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_screeningevent(
			
		) {
			
		}

	// SocialEvent

		/*
		 * Thing > Event > SocialEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_socialevent(
			
		) {
			
		}

	// SportsEvent

		/*
		 * Thing > Event > SportsEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_sportsevent(
			
		) {
			
		}

	// TheaterEvent

		/*
		 * Thing > Event > TheaterEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_theaterevent(
			
		) {
			
		}

	// UserInteraction

		/*
		 * Thing > Event > UserInteraction
		 * 
		 * 
		 */

		function uamswp_fad_schema_userinteraction(
			
		) {
			
		}

		// UserBlocks

			/*
			 * Thing > Event > UserInteraction > UserBlocks
			 * 
			 * 
			 */

			function uamswp_fad_schema_userblocks(
				
			) {
				
			}

		// UserCheckins

			/*
			 * Thing > Event > UserInteraction > UserCheckins
			 * 
			 * 
			 */

			function uamswp_fad_schema_usercheckins(
				
			) {
				
			}

		// UserComments

			/*
			 * Thing > Event > UserInteraction > UserComments
			 * 
			 * 
			 */

			function uamswp_fad_schema_usercomments(
				
			) {
				
			}

		// UserDownloads

			/*
			 * Thing > Event > UserInteraction > UserDownloads
			 * 
			 * 
			 */

			function uamswp_fad_schema_userdownloads(
				
			) {
				
			}

		// UserLikes

			/*
			 * Thing > Event > UserInteraction > UserLikes
			 * 
			 * 
			 */

			function uamswp_fad_schema_userlikes(
				
			) {
				
			}

		// UserPageVisits

			/*
			 * Thing > Event > UserInteraction > UserPageVisits
			 * 
			 * 
			 */

			function uamswp_fad_schema_userpagevisits(
				
			) {
				
			}

		// UserPlays

			/*
			 * Thing > Event > UserInteraction > UserPlays
			 * 
			 * 
			 */

			function uamswp_fad_schema_userplays(
				
			) {
				
			}

		// UserPlusOnes

			/*
			 * Thing > Event > UserInteraction > UserPlusOnes
			 * 
			 * 
			 */

			function uamswp_fad_schema_userplusones(
				
			) {
				
			}

		// UserTweets

			/*
			 * Thing > Event > UserInteraction > UserTweets
			 * 
			 * 
			 */

			function uamswp_fad_schema_usertweets(
				
			) {
				
			}

	// VisualArtsEvent

		/*
		 * Thing > Event > VisualArtsEvent
		 * 
		 * 
		 */

		function uamswp_fad_schema_visualartsevent(
			
		) {
			
		}