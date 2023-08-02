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
 * 	$expertises // int[] // Value of the related areas of expertise input (or $expertise_descendants, List of this area of expertise item's descendant items)
 * 	$page_titles // array // Associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
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
 * 	$hide_medical_ontology // bool // Query for whether to suppress this ontology section based on Find-a-Doc Settings configuration
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

	if ( $expertise_descendant_list ) {
		$content_placement = ( isset($content_placement) && !empty($content_placement) ) ? $content_placement : 'profile'; // string // Placement of this content // Expected values: 'subsection' or 'profile'
		$site_nav_id = isset($site_nav_id) ? $site_nav_id : ''; // int
	}

	if ( !isset($expertise_section_show) ) {

		if ( $expertise_descendant_list ) {

			if ( !isset($expertise_descendant_section_show) ) {

				$page_id = isset($page_id) ? $page_id : get_the_ID();

				$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
					$expertises, // int[]
					$content_placement, // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
					$site_nav_id // int (optional)
				);
					$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_section_show']; // bool

			}

			$expertise_section_show = $expertise_descendant_section_show;

		} else {

			$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
				$expertises // int[]
			);
				$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool

		}

	}

	if ( !isset($hide_medical_ontology) ) {
		$ontology_hide_vars = isset($ontology_hide_vars) ? $ontology_hide_vars : uamswp_fad_ontology_hide();
			$hide_medical_ontology = $ontology_hide_vars['hide_medical_ontology']; // bool
	}


if ( $expertise_section_show && !$hide_medical_ontology ) {

	// Check/define variables

		// Query for whether item is ontology type vs. content type
		$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Text to use for the section title
		if ( !isset($expertise_section_title) ) {

			// Set the section title using the system settings for the section title in a general placement
			if ( $expertise_descendant_list ) {

				if ( !isset($expertise_descendant_fpage_title_general) ) {
					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string
				}
				$expertise_section_title = $expertise_descendant_fpage_title_general;

			} else {

				if ( !isset($expertise_fpage_title_general) ) {
					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string
				}
				$expertise_section_title = $expertise_fpage_title_general;

			}
		}

		// Text to use for the section intro text
		if ( !isset($expertise_section_intro) ) {

			// Set the section title using the system settings for the section title in a general placement
			if ( $expertise_descendant_list ) {

				if ( !isset($expertise_descendant_fpage_intro_general) ) {
					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string
				}
				$expertise_section_intro = $expertise_descendant_fpage_intro_general;

			} else {

				if ( !isset($expertise_fpage_intro_general) ) {
					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string
				}
				$expertise_section_intro = $expertise_fpage_intro_general;

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

		// Other variables

			if ( !isset($expertise_single_name) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_single_name) ) {
						$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
							$expertise_descendant_single_name = $labels_expertise_descendant_vars['expertise_descendant_single_name']; // string
					}
					$expertise_single_name = $expertise_descendant_single_name;

				} else {

					$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
						$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string

				}
			}

			if ( !isset($expertise_single_name_attr) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_single_name_attr) ) {
						$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
							$expertise_descendant_single_name_attr = $labels_expertise_descendant_vars['expertise_descendant_single_name_attr']; // string
					}
					$expertise_single_name_attr = $expertise_descendant_single_name_attr;

				} else {

					$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
						$expertise_single_name_attr = $labels_expertise_vars['expertise_single_name_attr']; // string

				}
			}

			if ( !isset($expertise_plural_name) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_plural_name) ) {
						$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
							$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
					}
					$expertise_plural_name = $expertise_descendant_plural_name;

				} else {

					$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
						$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string

				}
			}

			if ( !isset($expertise_plural_name_attr) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_plural_name_attr) ) {
						$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
							$expertise_descendant_plural_name_attr = $labels_expertise_descendant_vars['expertise_descendant_plural_name_attr']; // string
					}
					$expertise_plural_name_attr = $expertise_descendant_plural_name_attr;

				} else {

					$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
						$expertise_plural_name_attr = $labels_expertise_vars['expertise_plural_name_attr']; // string

				}
			}

			if ( !isset($expertise_fpage_title_general) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_fpage_title_general) ) {
						$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
							$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						);
							$expertise_descendant_fpage_title_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_title_general']; // string
					}
					$expertise_fpage_title_general = $expertise_descendant_fpage_title_general;

				} else {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_title_general = $fpage_text_expertise_general_vars['expertise_fpage_title_general']; // string

				}
			}

			if ( !isset($expertise_fpage_intro_general) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_fpage_intro_general) ) {
						$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
							$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
						);
							$expertise_descendant_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_descendant_fpage_intro_general']; // string
					}
					$expertise_fpage_intro_general = $expertise_descendant_fpage_intro_general;

				} else {

					$fpage_text_expertise_general_vars = isset($fpage_text_expertise_general_vars) ? $fpage_text_expertise_general_vars : uamswp_fad_fpage_text_expertise_general(
						$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
					);
						$expertise_fpage_intro_general = $fpage_text_expertise_general_vars['expertise_fpage_intro_general']; // string

				}
			}

			if ( !isset($expertise_query) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_query) ) {
						$page_id = isset($page_id) ? $page_id : get_the_ID();

						$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
							$expertise_descendants, // int[]
							$content_placement, // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
							$site_nav_id // int (optional)
						);
							$expertise_descendant_query = $expertise_descendant_query_vars['expertise_descendant_query']; // WP_Post[]
					}
					$expertise_query = $expertise_descendant_query;

				} else {

					$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
						$expertises // int[]
					);
						$expertise_query = $expertise_query_vars['expertise_query']; // WP_Post[]

				}
			}

			if ( !isset($expertise_section_show) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_section_show) ) {
						$page_id = isset($page_id) ? $page_id : get_the_ID();

						$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
							$expertises, // int[]
							$content_placement, // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
							$site_nav_id // int (optional)
						);
							$expertise_descendant_section_show = $expertise_descendant_query_vars['expertise_descendant_section_show']; // bool
					}
					$expertise_section_show = $expertise_descendant_section_show;

				} else {

					$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
						$expertises // int[]
					);
						$expertise_section_show = $expertise_query_vars['expertise_section_show']; // bool

				}
			}

			if ( !isset($expertise_count) ) {

				if ( $expertise_descendant_list ) {

					if ( !isset($expertise_descendant_count) ) {
						$page_id = isset($page_id) ? $page_id : get_the_ID();

						$expertise_descendant_query_vars = isset($expertise_descendant_query_vars) ? $expertise_descendant_query_vars : uamswp_fad_expertise_descendant_query(
							$expertises, // int[]
							$content_placement, // string (optional) // Placement of this content // Expected values: 'subsection' or 'profile'
							$site_nav_id // int (optional)
						);
							$expertise_descendant_count = $expertise_descendant_query_vars['expertise_descendant_count']; // int
					}
					$expertise_count = $expertise_descendant_count;

				} else {

					$expertise_query_vars = isset($expertise_query_vars) ? $expertise_query_vars : uamswp_fad_expertise_query(
						$expertises // int[]
					);
						$expertise_count = $expertise_query_vars['expertise_count']; // int

				}

			}

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