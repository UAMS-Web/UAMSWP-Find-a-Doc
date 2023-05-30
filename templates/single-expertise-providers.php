<?php
/*
 * Template Name: Fake Area of Expertise Providers Subpage
 */

// Set general variables
$page_id = get_the_ID();
$page_title = get_the_title(); // Title of Area of Expertise
$page_title_attr = uamswp_attr_conversion($page_title);
$fpage_name = 'Providers'; // Fake subpage name
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $page_title . ' ' . $fpage_name; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$page_url = get_permalink();
$expertise_archive_title = get_field('expertise_archive_headline', 'option') ?: 'Areas of Expertise';
$expertise_archive_title_attr = uamswp_attr_conversion($expertise_archive_title);
$expertise_single_name = get_field('expertise_archive_headline', 'option') ?: 'Area of Expertise';
$expertise_single_name_attr = uamswp_attr_conversion($expertise_single_name);

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type

// Get site header and site nav values for ontology subsections
uamswp_fad_ontology_site_values();

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_providers_query();

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_locations_query();

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_descendants_query();

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_related_query();

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_resources_query();

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_conditions_query();

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_treatments_query();

// Override theme's method of defining the meta page title
add_filter('seopress_titles_title', 'uamswp_fad_fpage_title', 15, 2);

// Add meta keywords
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );
$keywords = get_field('expertise_alternate_names');

// Modify site header

	// Remove the site header set by the theme
	remove_action( 'genesis_header', 'uamswp_site_image', 5 );

	// Add ontology subsection site header
	add_action( 'genesis_header', 'uamswp_fad_ontology_header', 5 );

// Modify primary navigation

	// Remove the primary navigation set by the theme
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

	// Add ontology subsection primary navigation
	add_action( 'genesis_after_header', 'uamswp_fad_ontology_nav_menu', 5 );

// Add page template class to body element's classes
add_filter( 'body_class', 'uams_default_page_body_class' );
function uams_default_page_body_class( $classes ) {
	$classes[] = 'page-template-default';
	return $classes;
}

// Override theme's method of defining the breadcrumbs
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_breadcrumbs_expertise');
function uamswp_breadcrumbs_expertise($crumbs) {
	$crumbs[] = array('Providers', '');
	return $crumbs;
}

// Add extra class to entry
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}

// Modify Entry Title

	// Remove Genesis-standard post title
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

	// Add post title for ontology subsection fake subpages
	add_action( 'genesis_entry_header', 'uamswp_fad_fpage_post_title' );

// Remove the post info (byline) from the entry header and the entry footer
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php

// Remove the entry meta (tags) from the entry footer, including markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// Construct page content

	// Remove content
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

	// Display ontology page content
	add_action( 'genesis_after_entry', 'uamswp_expertise_physicians', 20 );
	function uamswp_expertise_physicians() {
		global $show_providers_section;
		//global $postsCountClass;
		global $physicians_query;
		//global $postsPerPage;
		global $physicians;
		global $provider_ids;
	
		if($show_providers_section) {
	
			// Get available regions - All available, since no titles set on initial load
			$region_IDs = array();
			while ($physicians_query->have_posts()) : $physicians_query->the_post();
				$id = get_the_ID();
				$region_IDs = array_merge($region_IDs, get_field('physician_region', $id));
			endwhile;
			$region_IDs = array_unique($region_IDs);
			$region_list = array();
			foreach ($region_IDs as $region_ID){
				$region_list[] = get_term_by( 'ID', $region_ID, 'region' )->slug;
			}
	
			// if cookie is set, run modified physician query
			if ( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {		
	
				$provider_region = '';
				if( isset($_COOKIE['wp_filter_region']) || isset($_GET['_filter_region']) ) {
					$provider_region = isset($_GET['_filter_region']) ? $_GET['_filter_region'] : $_COOKIE['wp_filter_region'];
				}
	
				$tax_query = array();
				if(!empty($provider_region)) {
					$tax_query[] = array(
						'taxonomy' => 'region',
						'field' => 'slug',
						'terms' => $provider_region
					);
				}
				$args = array(
					"post_type" => "provider",
					"post_status" => "publish",
					"posts_per_page" => -1,
					"orderby" => "title",
					"order" => "ASC",
					"fields" => "ids",
					"post__in" => $physicians,
					'tax_query' => $tax_query
				);
				$physicians_query = New WP_Query( $args );
			}
			$provider_count = count($physicians_query->posts);
			?>
			<section class="uams-module bg-auto" id="providers">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title"><span class="title">Providers</span></h2>
							<?php echo do_shortcode( '[uamswp_provider_ajax_filter providers="'. implode(",", $provider_ids) .'"]' ); ?>
							<div class="card-list-container">
								<div class="card-list card-list-doctors">
									<?php 
										if($provider_count > 0){
											$title_list = array();
											while ($physicians_query->have_posts()) : $physicians_query->the_post();
												$id = get_the_ID();
												include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' );
												$title_list[] = get_field('physician_title', $id);
											endwhile;
											echo '<data id="provider_ids" data-postids="'. implode(',', $physicians_query->posts) .'," data-regions="'. implode(',', $region_list) .'," data-titles="'. implode(',', array_unique($title_list)) .',"></data>';
										} else {
											echo '<span class="no-results">Sorry, there are no providers matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
										}
										wp_reset_postdata();
									?>
								</div>
							</div>
							<!-- <div class="more" style="<?php //echo ($postsPerPage < $provider_count) ? '' : 'display:none;' ; ?>">
								<button class="loadmore btn btn-primary <?php //echo $provider_count; ?>" data-ppp="<?php //echo $postsPerPage; ?>" aria-label="Load more providers">Load More</button>
							</div> -->
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all providers">Load All</button>
							</div>
						</div>
					</div>
				</div>
				<?php if ( isset($_GET['_filter_region']) ) { ?>
					<script type="text/javascript">
						// Set cookie to expire at end of session
						document.cookie = "wp_filter_region=<?php echo htmlspecialchars($_GET['_filter_region']); ?>; path=/; domain="+window.location.hostname;
					</script>
				<?php } ?>
			</section>
		<?php }
	}

	// Display appointment information
	add_action( 'genesis_after_entry', 'uamswp_fad_ontology_appointment', 26 );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.

genesis();