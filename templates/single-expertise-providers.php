<?php
/*
 * Template Name: Fake Area of Expertise Providers Subpage
 */

// Get system settings for ontology item labels

	// Get system settings for provider labels
	uamswp_fad_labels_provider();

	// Get system settings for location labels
	uamswp_fad_labels_location();

	// Get system settings for area of expertise labels
	uamswp_fad_labels_expertise();

	// Get system settings for area of expertise descendant item labels
	uamswp_fad_labels_expertise_descendant();

	// Get system settings for clinical resource labels
	uamswp_fad_labels_clinical_resource();

	// Get system settings for condition labels
	uamswp_fad_labels_conditions();

	// Get system settings for treatment labels
	uamswp_fad_labels_treatments();

// Get the page ID for the 'parent' area of expertise
$page_id = get_the_ID();

// Get the page title for the 'parent' area of expertise
$page_title = get_the_title(); // Title of the area of expertise
$page_title_attr = uamswp_attr_conversion($page_title);

// Get the page URL for the 'parent' area of expertise
$page_url = get_permalink();

// Area of Expertise Content Type
$ontology_type = get_field('expertise_type'); // True is ontology type, false is content type
$ontology_type = isset($ontology_type) ? $ontology_type : 1; // Check if 'expertise_type' is not null, and if so, set value to true

// Get system settings for fake subpage text elements on Area of Expertise subsection
uamswp_fad_fpage_text_expertise();

// Get the featured image / post thumbnail
$page_image = $provider_fpage_featured_image_expertise;

// Set general variables for fake subpage
$fpage_name = $provider_plural_name; // Name of ontology item type represented by this fake subpage
$fpage_name_attr = uamswp_attr_conversion($fpage_name);
$fpage_title = $provider_fpage_title_expertise; // Fake subpage page title
$fpage_title_attr = uamswp_attr_conversion($fpage_title);
$current_fpage = get_query_var('fpage'); // Fake subpage slug
$fpage_url = !empty($current_fpage) ? $page_url . user_trailingslashit($current_fpage) : $page_url; // Fake subpage URL
$fpage_intro = $provider_fpage_intro_expertise; // Fake subpage intro text
$fpage_intro_attr = uamswp_attr_conversion($fpage_intro); // Attribute-friendly version of fake subpage intro text

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
$meta_title_base_addition = $fpage_name_attr; // Word or phrase to use to form base meta title
$meta_title_enhanced_addition = $page_title_attr; // Word or phrase to inject into base meta title to form enhanced meta title level 1
$meta_title_base_order = array( $meta_title_base_addition, $meta_title_enhanced_addition ); // Override default base meta title structure to force inclusion of $meta_title_enhanced_addition
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override theme's method of defining the meta description
$excerpt = $providers_fpage_short_desc_expertise;
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// Construct the meta keywords element
$keywords = get_field('expertise_alternate_names');
add_action('wp_head','uamswp_keyword_hook_header');

// Set the Open Graph thumbnail
// add_filter('seopress_social_og_thumb', 'uamswp_social_og_thumb');

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
add_filter( 'body_class', 'uamswp_page_body_class' );
$template_type = 'page_landing';

// Add fake subpage to breadcrumbs
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_fad_fpage_breadcrumbs');

// Add bg-white class to article.entry element
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title

	// Remove Genesis-standard post title and markup
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	// Construct non-standard post title
	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
	$entry_header_style = 'graphic'; // Entry header style
	$entry_title_text = $fpage_title; // Regular title
	$entry_title_text_supertitle = ''; // Optional supertitle
	$entry_title_text_subtitle = ''; // Optional subtitle
	$entry_title_text_body = $fpage_intro; // Optional lead paragraph
	$entry_title_image_desktop = ''; // Desktop breakpoint image ID
	$entry_title_image_mobile = ''; // Optional mobile breakpoint image ID

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
	add_action( 'genesis_entry_content', 'uamswp_expertise_physicians' );
	function uamswp_expertise_physicians() {
		// Bring in variables from outside of the function
		global $show_providers_section; // Defined in uamswp_fad_ontology_providers_query()
		//global $postsCountClass;
		global $physicians_query; // Defined in uamswp_fad_ontology_providers_query()
		//global $postsPerPage;
		global $physicians; // Defined in uamswp_fad_ontology_providers_query()
		global $provider_ids; // Defined in uamswp_fad_ontology_providers_query()

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
							<h2 class="module-title"><span class="title"><?php echo $provider_plural_name; ?></span></h2>
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
											echo '<span class="no-results">Sorry, there are no ' . strtolower($provider_plural_name) . ' matching your filter criteria. Please adjust your filter options or reset the filters.</span>';
										}
										wp_reset_postdata();
									?>
								</div>
							</div>
							<!-- <div class="more" style="<?php //echo ($postsPerPage < $provider_count) ? '' : 'display:none;' ; ?>">
								<button class="loadmore btn btn-primary <?php //echo $provider_count; ?>" data-ppp="<?php //echo $postsPerPage; ?>" aria-label="Load more <?php //echo strtolower($provider_plural_name_attr); ?>">Load More</button>
							</div> -->
							<div class="ajax-filter-load-more">
								<button class="btn btn-lg btn-primary" aria-label="Load all <?php echo strtolower($provider_plural_name_attr); ?>">Load All</button>
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
	add_action( 'genesis_entry_content', 'uamswp_fad_ontology_appointment' );
	// Check if Make an Appointment section should be displayed
	$show_appointment_section = true; // It should always be displayed.

genesis();