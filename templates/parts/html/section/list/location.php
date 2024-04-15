<?php
/*
 * Template Name: Location List Section
 *
 * Description: A template part that displays a list of locations associated with
 * the current page.
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$locations // int[] // Value of the related locations input (or $location_descendants, List of this location item's descendant items)
 * 	$page_titles // associative array with one or more of the following keys: 'page_title', 'page_title_phrase', 'short_name', 'short_name_possessive'
 * 	$location_section_show // bool // Query for whether to show the location section (or $location_descendant_section_show, Query for whether to show the descendant location section)
 * 	$ontology_type // bool // Query for whether item is ontology type vs. content type
 *
 * Optional vars:
 * 	$location_section_schema_query // bool // Query for whether to add locations to schema
 * 	$location_descendant_list // bool // Query for whether this is a list of child locations within a location
 * 	$location_section_title // string // Text to use for the section title
 * 	$location_section_intro // string // Text to use for the section intro text
 * 	$location_section_show_header // bool // Query for whether to display the section header
 * 	$location_section_filter // bool // Query for whether to add filter(s)
 * 	$location_section_filter_region // bool // Query for whether to add region filter
 * 	$location_section_filter_title // bool // Query for whether to add title filter
 * 	$location_section_collapse_list // bool // Query for whether to collapse the list of locations in the locations section
 * 	$location_single_name // string
 * 	$location_single_name_attr // string
 * 	$location_plural_name // string
 * 	$location_plural_name_attr // string
 * 	$location_fpage_title_general // string
 * 	$location_fpage_intro_general // string
 * 	$location_section_show // bool
 * 	$location_query // WP_Post[]
 * 	$location_ids // int[]
 * 	$location_count // int
 *
 * Return:
 * 	html <section />
 */

// Do something

if ( $location_section_show ) {

	// Check/define variables

		// Query for whether to add locations to schema

			$location_section_schema_query = isset($location_section_schema_query) ? $location_section_schema_query : false;

		// Query for whether item is ontology type vs. content type

			$ontology_type = isset($ontology_type) ? $ontology_type : true;

		// Query for whether this is a list of child locations within a location

			$location_descendant_list = isset($location_descendant_list) ? $location_descendant_list : false;

		// Text to use for the section title

			if ( !isset($location_section_title) ) {

				// Set the section title using the system settings for the section title in a general placement

					// Get the system settings for general placement of location item text elements

						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

					$location_section_title = $location_fpage_title_general;
			}

		// Text to use for the section intro text

			if ( !isset($location_section_intro) ) {

				// Set the section title using the system settings for the section title in a general placement

					// Get the system settings for general placement of location item text elements

						include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

					$location_section_intro = $location_fpage_intro_general;
			}

		// Query for whether to display the section header

			$location_section_show_header = isset($location_section_show_header) ? $location_section_show_header : true;

		// Query for whether to add filter(s)

			$location_section_filter = isset($location_section_filter) ? $location_section_filter : true;

		// Query for whether to add region filter

			$location_section_filter_region = isset($location_section_filter_region) ? $location_section_filter_region : true;

		// Query for whether to add title filter

			$location_section_filter_title = isset($location_section_filter_title) ? $location_section_filter_title : false;

		// Revisit filter queries

			$location_section_filter_region = $location_section_filter ? $location_section_filter_region : false;
			$location_section_filter_title = $location_section_filter ? $location_section_filter_title : false;
			$location_section_filter = ( $location_section_filter && ( $location_section_filter_region || $location_section_filter_title ) ) ? $location_section_filter : false; // Set as false if neither of the filter types is true

		// Query for whether to collapse the list of locations in the locations section

			$location_section_collapse_list = isset($location_section_collapse_list) ? $location_section_collapse_list : false;

		// Other variables

			// Get system settings for location labels

				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/location.php' );

			// Get the system settings for general placement of location item text elements

				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/text-elements/single/general-placement/location.php' );

			// Get the ontology subsection values

				include( UAMS_FAD_PATH . '/templates/parts/vars/sys/ontology-subsection.php' );

			// Related Locations Section Query

				include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/location.php' );

	// Filter details

		if ( $location_section_filter ) {

			// Set the AJAX filter shortcode name

				if (
					$location_section_filter_region // Filter by region
					&&
					$location_section_filter_title // Filter by title
				) {

					$location_section_filter_ajax = '';
					$location_section_filter = false;

				} elseif (
					$location_section_filter_region // Filter by region
					&&
					!$location_section_filter_title // Do not filter by title
				) {

					$location_section_filter_ajax = 'uamswp_location_ajax_filter';

				} else {

					$location_section_filter_ajax = '';
					$location_section_filter = false;

				}

				$location_section_filter = $location_section_filter_ajax ? $location_section_filter : false; // If no AJAX filter shortcode name, then set disable filtering

			// Region filter details

				if ( $location_section_filter_region ) {

					// Get all available regions (all available, since no titles set on initial load)

						// Get the list of region IDs from the locations

							$location_region_IDs = array();

							while ( $location_query->have_posts() ) {

								$location_query->the_post();
								$page_id = get_the_ID();
								$location_region_return = get_field('location_region', $page_id);

								if ( is_array( $location_region_return ) ) {

									$location_region_IDs = array_merge($location_region_IDs, $location_region_return);

								} else {

									$location_region_IDs[] = $location_region_return;

								}

							} // endwhile

							$location_region_IDs = array_unique($location_region_IDs); // Remove duplicate values from an array

						// Get the list of region slugs from the region IDs

							$location_region_list = array();

							foreach ( $location_region_IDs as $location_region_ID ) {

								$location_region_list[] = get_term_by( 'ID', $location_region_ID, 'region' )->slug;

							}

					// If region cookie is set, run a modified query for locations

						if (
							isset($_COOKIE['wp_filter_region'])
							||
							isset($_GET['_filter_region'])
						) {

							$location_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];

							// Construct the tax_query array

								$tax_query = array();

								if ( !empty($location_region) ) {

									$tax_query[] = array(
										'taxonomy' => 'region',
										'field' => 'slug',
										'terms' => $location_region
									);

								}

							// Construct the query arguments

								$args = array(
									'post_type' => 'location',
									'post_status' => 'publish',
									'posts_per_page' => -1,
									'orderby' => 'title',
									'order' => 'ASC',
									'fields' => 'ids',
									'no_found_rows' => true, // counts posts, remove if pagination required
									'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
									'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
									'post__in' => $locations,
									'tax_query' => $tax_query
								);

							// The Query

								$location_query = New WP_Query( $args );

							// Get a new list of location IDs

								$location_ids = $location_query->posts;

						} // endif isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region'])

				} // endif ( $location_section_filter_region )

			// Count the number of locations in the query

				$location_count = count($location_query->posts);

		} // endif ( $location_section_filter )

	wp_reset_postdata();

	?>
	<section class="uams-module location-list bg-auto<?php echo $location_section_collapse_list ? ' collapse-list' : ''; ?>" id="locations">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title<?php echo !$location_section_show_header ? ' sr-only' : ''; ?>"><span class="title"><?php echo $location_section_title; ?></span></h2>
					<?php

					if ( $location_section_intro ) {

						?>
						<p class="note<?php echo !$location_section_show_header ? ' sr-only' : ''; ?>"><?php echo $location_section_intro; ?></p>
						<?php

					} // endif ( $location_section_intro )

					if ( $location_section_filter ) {

						echo do_shortcode( '[' . $location_section_filter_ajax . ' locations="'. implode(",", $location_ids) .'"]' );

					} // endif ( $location_section_filter )

					?>
					<div class="card-list-container location-card-list-container">
						<div class="card-list card-list-locations">
							<?php

								if ( $location_count > 0 ) {

									$title_list = $location_section_filter_title ? array() : '';

									while ( $location_query->have_posts() ) {

										$location_query->the_post();

										include( UAMS_FAD_PATH . '/templates/parts/html/cards/location.php' );

										if ( $location_section_filter_title ) {

											$title_list[] = get_field('location_title', $page_id);

										}

									} // endwhile

									if ( $location_section_filter ) {

										echo '<data id="location_ids" data-postids="' . implode(',', $location_query->posts) . ',"' . ( $location_section_filter_region ? ' data-regions="' . implode(',', $location_region_list) . ',"' : '' ) . ( $location_section_filter_title ? ' data-titles="' . implode(',', array_unique($title_list)) . ',"' : '' ) . '></data>';
									}

								} else {

									if ( $location_section_filter ) {

										echo '<span class="no-results">Sorry, there are no ' . strtolower($location_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';

									}

								} // endif ( $location_count > 0 )

								wp_reset_postdata();

							?>
						</div>
						<?php

						if ($location_section_collapse_list ) {

							?>
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($location_plural_name_attr); ?>">Load All</button>
							</div>
							<?php

						} // endif ( $location_section_collapse_list )

						?>
					</div>
				</div>
			</div>
		</div>
		<?php

		if (
			$location_section_filter_region
			&&
			isset($_GET['_filter_region'])
		) {

			?>
			<script type="text/javascript">
				// Set cookie to expire at end of session
				document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
			</script>
			<?php

		}

		?>
	</section>
	<?php

} // endif ( $location_section_show )

?>