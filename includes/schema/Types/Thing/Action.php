<?php

// Action

	/*
	 * Thing > Action
	 * 
	 * 
	 */

	function uamswp_fad_schema_action(
		array $schema, // Main schema array
		array $input // Array of properties and values for a type and its parent types
	) {

		/* 

			Expected format for the array of properties and values for a type and its 
			parent types:

				$input = array(
					'type'			=> 'Foo',
					'properties'	=> array(
						// Foo
							'baz'	=> '', // baz
							'qux'	=> '' // qux
						// Bar
							$quux	=> '', // quux
							$corge	=> '' // corge
					)
				);

		 */

		// Check/define variables

			// Main schema array

			$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

			// Immediate parent(s) of this type

				/*

					The type name and capitalization must match what is found on Schema.org.

				*/

				$type_parent = array(
					'Thing'
				);

			// Properties from this type (and not from the parent[s] of this type)

				/*

					The type name and capitalization must match what is found on Schema.org.

					Find the expected types of their values on Schema.org page for this type
					(e.g., https://schema.org/Thing).

				*/

				$type_properties = array(
					'foo',
					'bar'
				);

		// Construct schema array

			$schema = uamswp_fad_schema_construct_array(
				$schema, // Main schema array
				$input, // Array of properties and values for the type and its parent types
				$type_properties, // Array of properties available to the type
				$type_parent // Array of the immediate parent(s) of this type
			);

		return $schema;

	}

	// AchieveAction

		/*
		 * Thing > Action > AchieveAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_achieveaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// LoseAction

			/*
			 * Thing > Action > AchieveAction > LoseAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_loseaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// TieAction

			/*
			 * Thing > Action > AchieveAction > TieAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_tieaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// WinAction

			/*
			 * Thing > Action > AchieveAction > WinAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_winaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// AssessAction

		/*
		 * Thing > Action > AssessAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_assessaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// ChooseAction

			/*
			 * Thing > Action > AssessAction > ChooseAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_chooseaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// VoteAction

				/*
				 * Thing > Action > AssessAction > ChooseAction > VoteAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_voteaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

		// IgnoreAction

			/*
			 * Thing > Action > AssessAction > IgnoreAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_ignoreaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ReactAction

			/*
			 * Thing > Action > AssessAction > ReactAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_reactaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// AgreeAction

				/*
				 * Thing > Action > AssessAction > ReactAction > AgreeAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_agreeaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// DisagreeAction

				/*
				 * Thing > Action > AssessAction > ReactAction > DisagreeAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_disagreeaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// DislikeAction

				/*
				 * Thing > Action > AssessAction > ReactAction > DislikeAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_dislikeaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// EndorseAction

				/*
				 * Thing > Action > AssessAction > ReactAction > EndorseAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_endorseaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// LikeAction

				/*
				 * Thing > Action > AssessAction > ReactAction > LikeAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_likeaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// WantAction

				/*
				 * Thing > Action > AssessAction > ReactAction > WantAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_wantaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

		// ReviewAction

			/*
			 * Thing > Action > AssessAction > ReviewAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_reviewaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// ConsumeAction

		/*
		 * Thing > Action > ConsumeAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_consumeaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// DrinkAction

			/*
			 * Thing > Action > ConsumeAction > DrinkAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_drinkaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// EatAction

			/*
			 * Thing > Action > ConsumeAction > EatAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_eataction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// InstallAction

			/*
			 * Thing > Action > ConsumeAction > InstallAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_installaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ListenAction

			/*
			 * Thing > Action > ConsumeAction > ListenAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_listenaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PlayGameAction

			/*
			 * Thing > Action > ConsumeAction > PlayGameAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_playgameaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ReadAction

			/*
			 * Thing > Action > ConsumeAction > ReadAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_readaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// UseAction

			/*
			 * Thing > Action > ConsumeAction > UseAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_useaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// WearAction

				/*
				 * Thing > Action > ConsumeAction > UseAction > WearAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_wearaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

		// ViewAction

			/*
			 * Thing > Action > ConsumeAction > ViewAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_viewaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// WatchAction

			/*
			 * Thing > Action > ConsumeAction > WatchAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_watchaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// ControlAction

		/*
		 * Thing > Action > ControlAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_controlaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// ActivateAction

			/*
			 * Thing > Action > ControlAction > ActivateAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_activateaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DeactivateAction

			/*
			 * Thing > Action > ControlAction > DeactivateAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_deactivateaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ResumeAction

			/*
			 * Thing > Action > ControlAction > ResumeAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_resumeaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// SuspendAction

			/*
			 * Thing > Action > ControlAction > SuspendAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_suspendaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// CreateAction

		/*
		 * Thing > Action > CreateAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_createaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// CookAction

			/*
			 * Thing > Action > CreateAction > CookAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_cookaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DrawAction

			/*
			 * Thing > Action > CreateAction > DrawAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_drawaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// FilmAction

			/*
			 * Thing > Action > CreateAction > FilmAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_filmaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PaintAction

			/*
			 * Thing > Action > CreateAction > PaintAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_paintaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PhotographAction

			/*
			 * Thing > Action > CreateAction > PhotographAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_photographaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// WriteAction

			/*
			 * Thing > Action > CreateAction > WriteAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_writeaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// FindAction

		/*
		 * Thing > Action > FindAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_findaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// CheckAction

			/*
			 * Thing > Action > FindAction > CheckAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_checkaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DiscoverAction

			/*
			 * Thing > Action > FindAction > DiscoverAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_discoveraction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// TrackAction

			/*
			 * Thing > Action > FindAction > TrackAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_trackaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// InteractAction

		/*
		 * Thing > Action > InteractAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_interactaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// BefriendAction

			/*
			 * Thing > Action > InteractAction > BefriendAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_befriendaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// CommunicateAction

			/*
			 * Thing > Action > InteractAction > CommunicateAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_communicateaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// AskAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > AskAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_askaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// CheckInAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > CheckInAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_checkinaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// CheckOutAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > CheckOutAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_checkoutaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// CommentAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > CommentAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_commentaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// InformAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > InformAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_informaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

				// ConfirmAction

					/*
					 * Thing > Action > InteractAction > qux > quux > ConfirmAction
					 * 
					 * 
					 */

					function uamswp_fad_schema_confirmaction(
						array $schema, // Main schema array
						array $input // Array of properties and values for a type and its parent types
					) {

						/* 

							Expected format for the array of properties and values for a type and its 
							parent types:

								$input = array(
									'type'			=> 'Foo',
									'properties'	=> array(
										// Foo
											'baz'	=> '', // baz
											'qux'	=> '' // qux
										// Bar
											$quux	=> '', // quux
											$corge	=> '' // corge
									)
								);

						 */

						// Check/define variables

							// Main schema array

							$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

							// Immediate parent(s) of this type

								/*

									The type name and capitalization must match what is found on Schema.org.

								*/

								$type_parent = array(
									'Thing'
								);

							// Properties from this type (and not from the parent[s] of this type)

								/*

									The type name and capitalization must match what is found on Schema.org.

									Find the expected types of their values on Schema.org page for this type
									(e.g., https://schema.org/Thing).

								*/

								$type_properties = array(
									'foo',
									'bar'
								);

						// Construct schema array

							$schema = uamswp_fad_schema_construct_array(
								$schema, // Main schema array
								$input, // Array of properties and values for the type and its parent types
								$type_properties, // Array of properties available to the type
								$type_parent // Array of the immediate parent(s) of this type
							);

						return $schema;

					}

				// RsvpAction

					/*
					 * Thing > Action > InteractAction > qux > quux > RsvpAction
					 * 
					 * 
					 */

					function uamswp_fad_schema_rsvpaction(
						array $schema, // Main schema array
						array $input // Array of properties and values for a type and its parent types
					) {

						/* 

							Expected format for the array of properties and values for a type and its 
							parent types:

								$input = array(
									'type'			=> 'Foo',
									'properties'	=> array(
										// Foo
											'baz'	=> '', // baz
											'qux'	=> '' // qux
										// Bar
											$quux	=> '', // quux
											$corge	=> '' // corge
									)
								);

						 */

						// Check/define variables

							// Main schema array

							$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

							// Immediate parent(s) of this type

								/*

									The type name and capitalization must match what is found on Schema.org.

								*/

								$type_parent = array(
									'Thing'
								);

							// Properties from this type (and not from the parent[s] of this type)

								/*

									The type name and capitalization must match what is found on Schema.org.

									Find the expected types of their values on Schema.org page for this type
									(e.g., https://schema.org/Thing).

								*/

								$type_properties = array(
									'foo',
									'bar'
								);

						// Construct schema array

							$schema = uamswp_fad_schema_construct_array(
								$schema, // Main schema array
								$input, // Array of properties and values for the type and its parent types
								$type_properties, // Array of properties available to the type
								$type_parent // Array of the immediate parent(s) of this type
							);

						return $schema;

					}

			// InviteAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > InviteAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_inviteaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// ReplyAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > ReplyAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_replyaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// ShareAction

				/*
				 * Thing > Action > InteractAction > CommunicateAction > ShareAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_shareaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

		// FollowAction

			/*
			 * Thing > Action > InteractAction > FollowAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_followaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// JoinAction

			/*
			 * Thing > Action > InteractAction > JoinAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_joinaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// LeaveAction

			/*
			 * Thing > Action > InteractAction > LeaveAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_leaveaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// MarryAction

			/*
			 * Thing > Action > InteractAction > MarryAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_marryaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// RegisterAction

			/*
			 * Thing > Action > InteractAction > RegisterAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_registeraction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// SubscribeAction

			/*
			 * Thing > Action > InteractAction > SubscribeAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_subscribeaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// UnRegisterAction

			/*
			 * Thing > Action > InteractAction > UnRegisterAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_unregisteraction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// MoveAction

		/*
		 * Thing > Action > MoveAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_moveaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// ArriveAction

			/*
			 * Thing > Action > MoveAction > ArriveAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_arriveaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DepartAction

			/*
			 * Thing > Action > MoveAction > DepartAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_departaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// TravelAction

			/*
			 * Thing > Action > MoveAction > TravelAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_travelaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// OrganizeAction

		/*
		 * Thing > Action > OrganizeAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_organizeaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// AllocateAction

			/*
			 * Thing > Action > OrganizeAction > AllocateAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_allocateaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// AcceptAction

				/*
				 * Thing > Action > OrganizeAction > AllocateAction > AcceptAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_acceptaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// AssignAction

				/*
				 * Thing > Action > OrganizeAction > AllocateAction > AssignAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_assignaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// AuthorizeAction

				/*
				 * Thing > Action > OrganizeAction > AllocateAction > AuthorizeAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_authorizeaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// RejectAction

				/*
				 * Thing > Action > OrganizeAction > AllocateAction > RejectAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_rejectaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

		// ApplyAction

			/*
			 * Thing > Action > OrganizeAction > ApplyAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_applyaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// BookmarkAction

			/*
			 * Thing > Action > OrganizeAction > BookmarkAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_bookmarkaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PlanAction

			/*
			 * Thing > Action > OrganizeAction > PlanAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_planaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// CancelAction

				/*
				 * Thing > Action > OrganizeAction > PlanAction > CancelAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_cancelaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// ReserveAction

				/*
				 * Thing > Action > OrganizeAction > PlanAction > ReserveAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_reserveaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

			// ScheduleAction

				/*
				 * Thing > Action > OrganizeAction > PlanAction > ScheduleAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_scheduleaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}


	// PlayAction

		/*
		 * Thing > Action > PlayAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_playaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// ExerciseAction

			/*
			 * Thing > Action > PlayAction > ExerciseAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_exerciseaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PerformAction

			/*
			 * Thing > Action > PlayAction > PerformAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_performaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// SearchAction

		/*
		 * Thing > Action > SearchAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_searchaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// SeekToAction

		/*
		 * Thing > Action > SeekToAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_seektoaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// SolveMathAction

		/*
		 * Thing > Action > SolveMathAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_solvemathaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

	// TradeAction

		/*
		 * Thing > Action > TradeAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_tradeaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// BuyAction

			/*
			 * Thing > Action > TradeAction > BuyAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_buyaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DonateAction

			/*
			 * Thing > Action > TradeAction > DonateAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_donateaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// OrderAction

			/*
			 * Thing > Action > TradeAction > OrderAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_orderaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PayAction

			/*
			 * Thing > Action > TradeAction > PayAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_payaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// PreOrderAction

			/*
			 * Thing > Action > TradeAction > PreOrderAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_preorderaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// QuoteAction

			/*
			 * Thing > Action > TradeAction > QuoteAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_quoteaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// RentAction

			/*
			 * Thing > Action > TradeAction > RentAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_rentaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// SellAction

			/*
			 * Thing > Action > TradeAction > SellAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_sellaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// TipAction

			/*
			 * Thing > Action > TradeAction > TipAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_tipaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// TransferAction

		/*
		 * Thing > Action > TransferAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_transferaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// BorrowAction

			/*
			 * Thing > Action > TransferAction > BorrowAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_borrowaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// DownloadAction

			/*
			 * Thing > Action > TransferAction > DownloadAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_downloadaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// GiveAction

			/*
			 * Thing > Action > TransferAction > GiveAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_giveaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// LendAction

			/*
			 * Thing > Action > TransferAction > LendAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_lendaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// MoneyTransfer

			/*
			 * Thing > Action > TransferAction > MoneyTransfer
			 * 
			 * 
			 */

			function uamswp_fad_schema_moneytransfer(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ReceiveAction

			/*
			 * Thing > Action > TransferAction > ReceiveAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_receiveaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ReturnAction

			/*
			 * Thing > Action > TransferAction > ReturnAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_returnaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// SendAction

			/*
			 * Thing > Action > TransferAction > SendAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_sendaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// TakeAction

			/*
			 * Thing > Action > TransferAction > TakeAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_takeaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

	// UpdateAction

		/*
		 * Thing > Action > UpdateAction
		 * 
		 * 
		 */

		function uamswp_fad_schema_updateaction(
			array $schema, // Main schema array
			array $input // Array of properties and values for a type and its parent types
		) {

			/* 

				Expected format for the array of properties and values for a type and its 
				parent types:

					$input = array(
						'type'			=> 'Foo',
						'properties'	=> array(
							// Foo
								'baz'	=> '', // baz
								'qux'	=> '' // qux
							// Bar
								$quux	=> '', // quux
								$corge	=> '' // corge
						)
					);

			 */

			// Check/define variables

				// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

				// Immediate parent(s) of this type

					/*

						The type name and capitalization must match what is found on Schema.org.

					*/

					$type_parent = array(
						'Thing'
					);

				// Properties from this type (and not from the parent[s] of this type)

					/*

						The type name and capitalization must match what is found on Schema.org.

						Find the expected types of their values on Schema.org page for this type
						(e.g., https://schema.org/Thing).

					*/

					$type_properties = array(
						'foo',
						'bar'
					);

			// Construct schema array

				$schema = uamswp_fad_schema_construct_array(
					$schema, // Main schema array
					$input, // Array of properties and values for the type and its parent types
					$type_properties, // Array of properties available to the type
					$type_parent // Array of the immediate parent(s) of this type
				);

			return $schema;

		}

		// AddAction

			/*
			 * Thing > Action > UpdateAction > AddAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_addaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

			// InsertAction

				/*
				 * Thing > Action > UpdateAction > AddAction > InsertAction
				 * 
				 * 
				 */

				function uamswp_fad_schema_insertaction(
					array $schema, // Main schema array
					array $input // Array of properties and values for a type and its parent types
				) {

					/* 

						Expected format for the array of properties and values for a type and its 
						parent types:

							$input = array(
								'type'			=> 'Foo',
								'properties'	=> array(
									// Foo
										'baz'	=> '', // baz
										'qux'	=> '' // qux
									// Bar
										$quux	=> '', // quux
										$corge	=> '' // corge
								)
							);

					 */

					// Check/define variables

						// Main schema array

						$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

						// Immediate parent(s) of this type

							/*

								The type name and capitalization must match what is found on Schema.org.

							*/

							$type_parent = array(
								'Thing'
							);

						// Properties from this type (and not from the parent[s] of this type)

							/*

								The type name and capitalization must match what is found on Schema.org.

								Find the expected types of their values on Schema.org page for this type
								(e.g., https://schema.org/Thing).

							*/

							$type_properties = array(
								'foo',
								'bar'
							);

					// Construct schema array

						$schema = uamswp_fad_schema_construct_array(
							$schema, // Main schema array
							$input, // Array of properties and values for the type and its parent types
							$type_properties, // Array of properties available to the type
							$type_parent // Array of the immediate parent(s) of this type
						);

					return $schema;

				}

				// AppendAction

					/*
					 * Thing > Action > UpdateAction > AddAction > quux > AppendAction
					 * 
					 * 
					 */

					function uamswp_fad_schema_appendaction(
						array $schema, // Main schema array
						array $input // Array of properties and values for a type and its parent types
					) {

						/* 

							Expected format for the array of properties and values for a type and its 
							parent types:

								$input = array(
									'type'			=> 'Foo',
									'properties'	=> array(
										// Foo
											'baz'	=> '', // baz
											'qux'	=> '' // qux
										// Bar
											$quux	=> '', // quux
											$corge	=> '' // corge
									)
								);

						 */

						// Check/define variables

							// Main schema array

							$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

							// Immediate parent(s) of this type

								/*

									The type name and capitalization must match what is found on Schema.org.

								*/

								$type_parent = array(
									'Thing'
								);

							// Properties from this type (and not from the parent[s] of this type)

								/*

									The type name and capitalization must match what is found on Schema.org.

									Find the expected types of their values on Schema.org page for this type
									(e.g., https://schema.org/Thing).

								*/

								$type_properties = array(
									'foo',
									'bar'
								);

						// Construct schema array

							$schema = uamswp_fad_schema_construct_array(
								$schema, // Main schema array
								$input, // Array of properties and values for the type and its parent types
								$type_properties, // Array of properties available to the type
								$type_parent // Array of the immediate parent(s) of this type
							);

						return $schema;

					}

				// PrependAction

					/*
					 * Thing > Action > UpdateAction > AddAction > quux > PrependAction
					 * 
					 * 
					 */

					function uamswp_fad_schema_prependaction(
						array $schema, // Main schema array
						array $input // Array of properties and values for a type and its parent types
					) {

						/* 

							Expected format for the array of properties and values for a type and its 
							parent types:

								$input = array(
									'type'			=> 'Foo',
									'properties'	=> array(
										// Foo
											'baz'	=> '', // baz
											'qux'	=> '' // qux
										// Bar
											$quux	=> '', // quux
											$corge	=> '' // corge
									)
								);

						 */

						// Check/define variables

							// Main schema array

							$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

							// Immediate parent(s) of this type

								/*

									The type name and capitalization must match what is found on Schema.org.

								*/

								$type_parent = array(
									'Thing'
								);

							// Properties from this type (and not from the parent[s] of this type)

								/*

									The type name and capitalization must match what is found on Schema.org.

									Find the expected types of their values on Schema.org page for this type
									(e.g., https://schema.org/Thing).

								*/

								$type_properties = array(
									'foo',
									'bar'
								);

						// Construct schema array

							$schema = uamswp_fad_schema_construct_array(
								$schema, // Main schema array
								$input, // Array of properties and values for the type and its parent types
								$type_properties, // Array of properties available to the type
								$type_parent // Array of the immediate parent(s) of this type
							);

						return $schema;

					}


		// DeleteAction

			/*
			 * Thing > Action > UpdateAction > DeleteAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_deleteaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}

		// ReplaceAction

			/*
			 * Thing > Action > UpdateAction > ReplaceAction
			 * 
			 * 
			 */

			function uamswp_fad_schema_replaceaction(
				array $schema, // Main schema array
				array $input // Array of properties and values for a type and its parent types
			) {

				/* 

					Expected format for the array of properties and values for a type and its 
					parent types:

						$input = array(
							'type'			=> 'Foo',
							'properties'	=> array(
								// Foo
									'baz'	=> '', // baz
									'qux'	=> '' // qux
								// Bar
									$quux	=> '', // quux
									$corge	=> '' // corge
							)
						);

				 */

				// Check/define variables

					// Main schema array

					$schema = ( isset($schema) && is_array($schema) && !empty($schema) ) ? $schema : array();

					// Immediate parent(s) of this type

						/*

							The type name and capitalization must match what is found on Schema.org.

						*/

						$type_parent = array(
							'Thing'
						);

					// Properties from this type (and not from the parent[s] of this type)

						/*

							The type name and capitalization must match what is found on Schema.org.

							Find the expected types of their values on Schema.org page for this type
							(e.g., https://schema.org/Thing).

						*/

						$type_properties = array(
							'foo',
							'bar'
						);

				// Construct schema array

					$schema = uamswp_fad_schema_construct_array(
						$schema, // Main schema array
						$input, // Array of properties and values for the type and its parent types
						$type_properties, // Array of properties available to the type
						$type_parent // Array of the immediate parent(s) of this type
					);

				return $schema;

			}
