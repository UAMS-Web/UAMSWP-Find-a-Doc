<?php
/*
 * Template Name: Area of Expertise List Section
 * 
 * Description: A template part that displays a list of areas of expertise 
 * associated with the current page.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$expertises or $expertise_descendants // int[] // Value of the related areas of expertise input (or if $expertise_descendants, List of this area of expertise item's descendant items)
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$hide_medical_ontology // bool // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
 * 
 * Optional vars:
 * 	$site_nav_id // int (default: '') // ID of post that defines the subsection
 * 	$ontology_type // bool (default: true) // Query for whether item is ontology type vs. content type
 * 	$content_placement // enum('subsection', 'profile') (default: 'profile') // Placement of this content
 * 	$expertise_section_show // bool // Query for whether to show the area of expertise section (or $expertise_descendant_section_show, Query for whether to show the descendant area of expertise section)
 * 	$expertise_descendant_list // bool (default: false) // Query for whether this is a list of child areas of expertise within an area of expertise
 * 	$expertise_single_name // string
 * 	$expertise_single_name_attr // string
 * 	$expertise_plural_name // string
 * 	$expertise_plural_name_attr // string
 * 	$expertise_fpage_title_general // string
 * 	$expertise_fpage_intro_general // string
 * 	$expertise_query // WP_Post[]
 * 	$expertise_count // int
 * 	$expertise_descendant_section_show // bool
 * 	$expertise_descendant_query // WP_Post[]
 * 	$expertise_descendant_count // int
 * 	$expertise_section_class // string (default: 'expertise-list') // Section class
 * 	$expertise_section_id // string (default: 'expertise') // Section ID
 * 	$expertise_section_show_header // bool (default: true) // Query for whether to display the section header
 * 	$expertise_section_title // string (default: Find-a-Doc Settings value for areas of expertise section title in a general placement) // Text to use for the section title
 * 	$expertise_section_intro // string (default: Find-a-Doc Settings value for areas of expertise section intro text in a general placement) // Text to use for the section intro text
 * 	$expertise_section_collapse_list // bool (default: false) // Query for whether to collapse the list of locations in the locations section
 * 
 * Return:
 * 	html <section />
 */

// Check/define variables

	// Query for whether this is a list of child areas of expertise within an area of expertise
	$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false;

	// Placement of this content

		if (
			!isset($content_placement) || empty($content_placement)
		) {

			if ( !$expertise_descendant_list ) {

				// Areas of Expertise

				// Do nothing

			} else {

				// Descendant Areas of Expertise

				$content_placement = 'profile'; // string enum('subsection', 'profile')

			}

		}

	// $site_nav_id

		if (
			!isset($site_nav_id) || empty($site_nav_id)
		) {

			if ( !$expertise_descendant_list ) {

				// Areas of Expertise

				// Do nothing

			} else {

				// Descendant Areas of Expertise

				$site_nav_id = $page_id; // int

			}

		}

	// Query for whether to show the section

		if ( $hide_medical_ontology ) {

			$expertise_section_show = false;
			$expertise_count = 0;
			$expertise_query = '';

		} else {

			if (
				!isset($expertise_section_show) || empty($expertise_section_show)
				||
				!isset($expertise_count) || empty($expertise_count)
				||
				!isset($expertise_query) || empty($expertise_query)
			) {

				if ( !$expertise_descendant_list ) {

					// Areas of Expertise

					$expertise_query_vars = uamswp_fad_expertise_query(
						$page_id, // int
						$expertises // int[]
					);
						$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool
						$expertise_count = $expertise_query_vars['expertise_count']; // int
						$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]

				} else {

					// Descendant Areas of Expertise

					if (
						!isset($expertise_descendant_section_show) || empty($expertise_descendant_section_show)
						||
						!isset($expertise_descendant_count) || empty($expertise_descendant_count)
						||
						!isset($expertise_descendant_query) || empty($expertise_descendant_query)
					) {

						$expertise_descendant_query_vars = uamswp_fad_expertise_descendant_query(
							$page_id, // int
							$expertise_descendants, // int[]
							$content_placement, // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
							$site_nav_id // int (optional)
						);
							$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
							$expertise_descendant_count = $expertise_descendant_query_vars['expertise_descendant_count']; // int
							$expertise_descendant_query = $expertise_descendant_query_vars['expertise_descendant_query']; // WP_Post[]

					}

					$expertise_section_show = $expertise_descendant_section_show;
					$expertise_count = $expertise_descendant_count;
					$expertise_query = $expertise_descendant_query;

				}
	
			}	

		}

	// Query for whether item is ontology type vs. content type

		if (
			$expertise_section_show
			&&
			(
				!isset($ontology_type) || empty($ontology_type)
			)
		) {

			$ontology_type = true;

		}

	// Text to use for the section title

		if (
			$expertise_section_show
			&&
			(
				!isset($expertise_section_title) || empty($expertise_section_title)
			)
		) {

			if ( !$expertise_descendant_list ) {

				// Areas of Expertise

				if (
					!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
				) {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string

				}

				$expertise_section_title = $expertise_fpage_title_general;

			} else {

				// Descendant Areas of Expertise

				if (
					!isset($expertise_descendant_fpage_title_general) || empty($expertise_descendant_fpage_title_general)
				) {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string

				}

				$expertise_section_title = $expertise_descendant_fpage_title_general;

			}

		}

	// Text to use for the section intro text

		if (
			$expertise_section_show
			&&
			(
				!isset($expertise_section_intro) || empty($expertise_section_intro)
			)
		) {

			if ( !$expertise_descendant_list ) {

				// Areas of Expertise

				if (
					!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
				) {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string

				}

				$expertise_section_intro = $expertise_fpage_intro_general;

			} else {

				// Descendant Areas of Expertise

				if (
					!isset($expertise_descendant_fpage_intro_general) || empty($expertise_descendant_fpage_intro_general)
				) {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string

				}

				$expertise_section_intro = $expertise_descendant_fpage_intro_general;

			}

		}

	// Query whether to display the section header
	
		$expertise_section_show_header = isset($expertise_section_show_header) ? $expertise_section_show_header : true;

	// Query for whether to collapse the list of locations in the locations section
	
		$expertise_section_collapse_list = isset($expertise_section_collapse_list) ? $expertise_section_collapse_list : false;

	// Section class
	
		$expertise_section_class = isset($expertise_section_class) ? $expertise_section_class : 'expertise-list';

	// Section ID
	
		$expertise_section_id = isset($expertise_section_id) ? $expertise_section_id : 'expertise';

	// Get system settings for area of expertise labels

		if ( $expertise_section_show ) {

			if ( !$expertise_descendant_list ) {

				// Get system settings for area of expertise labels
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

			} else {

				// Get system settings for descendant area of expertise item labels
				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise-descendant.php' );

				// Override area of expertise labels with descendant area of expertise item labels
				$expertise_single_name = $expertise_descendant_single_name; // string
				$expertise_single_name_attr = $expertise_descendant_single_name_attr; // string
				$expertise_plural_name = $expertise_descendant_plural_name; // string
				$expertise_plural_name_attr = $expertise_descendant_plural_name_attr; // string
				$placeholder_expertise_single_name = $placeholder_expertise_descendant_single_name; // string
				$placeholder_expertise_plural_name = $placeholder_expertise_descendant_plural_name; // string

			}

		}

	// uamswp_fad_fpage_text_expertise_general()

		if (
			$expertise_section_show
			&&
			(
				!isset($expertise_fpage_title_general) || empty($expertise_fpage_title_general)
				||
				!isset($expertise_fpage_intro_general) || empty($expertise_fpage_intro_general)
			)
		) {

			if ( !$expertise_descendant_list ) {

				// Areas of Expertise

				$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
					$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
				);
					$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
					$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string

			} else {

				// Descendant Areas of Expertise

				if (
					!isset($expertise_descendant_fpage_title_general) || empty($expertise_descendant_fpage_title_general)
				) {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string
						$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string

				}

				$expertise_fpage_title_general = $expertise_descendant_fpage_title_general;
				$expertise_fpage_intro_general = $expertise_descendant_fpage_intro_general;

			}

		}

if ( $expertise_section_show ) {

	?>
	<section class="uams-module<?php echo $expertise_section_class ? ' ' . $expertise_section_class : ''; ?> bg-auto<?php echo $expertise_section_collapse_list ? ' collapse-list' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '" aria-labelledby="' . $expertise_section_id . '-title"' : ''; ?>>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"<?php echo $expertise_section_id ? ' id="' . $expertise_section_id . '-title"' : ''; ?>><span class="title"><?php echo $expertise_section_title; ?></span></h2>
					<?php if ( $expertise_section_intro ) { ?>
						<p class="note<?php echo !$expertise_section_show_header ? ' sr-only' : ''; ?>"><?php echo $expertise_section_intro; ?></p>
					<?php } // endif ( $expertise_section_intro ) ?>
					<div class="card-list-container">
						<div class="card-list card-list-expertise">
							<?php
								if ( $expertise_count > 0 ) {
									while ( $expertise_query->have_posts() ) {
										$expertise_query->the_post();
										$page_id = get_the_ID();
										include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
									} // endwhile
								} // endif ( $expertise_count > 0 )
								wp_reset_postdata();
							?>
						</div>
						<?php
						if ( $expertise_section_collapse_list ) { ?>
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($expertise_plural_name_attr); ?>">Load All</button>
							</div>
						<?php } // endif ( $expertise_section_collapse_list ) ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php

} // endif ( $expertise_section_show )

?>