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

// Get site header values for ontology subsections
uamswp_fad_ontology_header();

// Override theme's method of defining the meta page title
add_filter('seopress_titles_title', 'uamswp_fad_fpage_title', 15, 2);

// Override theme's method of defining the breadcrumbs
function uamswp_breadcrumbs_expertise($crumbs) {
	$crumbs[] = array('Providers', '');
	return $crumbs;
}
add_filter('seopress_pro_breadcrumbs_crumbs', 'uamswp_breadcrumbs_expertise');

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_info', 9 ); // Added from uams-2020/page.php
// Removes entry meta from entry footer incl. markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

function uams_default_page_body_class( $classes ) {

	$classes[] = 'page-template-default';
	return $classes;
}
add_filter( 'body_class', 'uams_default_page_body_class' );

// Add extra class to entry
function uamswp_add_entry_class( $attributes ) {
	$attributes['class'] = $attributes['class']. ' bg-white';
	return $attributes;
}
add_filter( 'genesis_attr_entry', 'uamswp_add_entry_class' );

// Modify Entry Title
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_header', 'uamswp_fad_fpage_post_title' );

add_action( 'genesis_after_entry', 'uamswp_expertise_physicians', 20 );
add_action( 'wp_head', 'uamswp_expertise_header_metadata' );

// Remove content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

// Set logic for displaying jump links and sections
$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?
$jump_link_count = 0;

// Check if Podcast section should be displayed
$podcast_name = get_field('expertise_podcast_name');
$podcast_name_attr = uamswp_attr_conversion($podcast_name);
if ($podcast_name) {
	$show_podcast_section = true;
	$jump_link_count++;
} else {
	$show_podcast_section = false;
}

// Queries for whether each of the associated ontology content sections should be displayed on ontology pages/subsections

	// Query for whether associated providers content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_providers_query();

	// Query for whether associated locations content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_locations_query();

	// Query for whether descendant ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_descendants_query();
	// if (
	// 	!( get_post_meta( $page_id, 'hide_sub_areas_of_expertise', true) ) 
	// 	&& ( 0 != count( get_pages( array( 'child_of' => $page_id, 'post_type' => 'expertise' ) ) ) ) 
	// ) {
	// 	$show_child_aoe_section = true;
	// 	$jump_link_count++;
	// } else {
	// 	$show_child_aoe_section = false; // If it's suppressed or none available, set to false
	// }

	// Query for whether related ontology items (of the same post type) content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_related_query();

	// Query for whether associated clinical resources content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_resources_query();

	// Query for whether associated conditions content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_conditions_query();

	// Query for whether associated treatments content section should be displayed on ontology pages/subsections
	uamswp_fad_ontology_treatments_query();

// Check if Make an Appointment section should be displayed
// It should always be displayed.
$show_appointment_section = true;
$jump_link_count++;

// Check if Jump Links section should be displayed
if ( $jump_link_count >= $jump_link_count_min ) {
	$show_jump_links_section = true;
} else {
	$show_jump_links_section = false;
}

// Remove the primary navigation set by the theme
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );
// Add ontology subsection navigation
add_action( 'genesis_after_header', 'custom_expertise_nav_menu', 5 );
function custom_expertise_nav_menu() {
	global $show_providers_section;
	global $show_locations_section;
	global $show_related_aoe_section;
	global $show_related_resource_section;
	global $child_pages;
	global $show_child_aoe_section;
	global $show_child_content_nav;
	global $childnav;
	global $post;
	global $page_title;
	global $page_id;

	include( UAMS_FAD_PATH . '/templates/single-expertise-nav.php');
}

remove_action( 'genesis_header', 'uamswp_site_image', 5 );
add_action( 'genesis_header', 'uamswp_expertise_header', 5 );
function uamswp_expertise_header() {
	global $navbar_subbrand_title;
	global $navbar_subbrand_title_url;
	global $navbar_subbrand_parent;
	global $navbar_subbrand_parent_url;

	include( UAMS_FAD_PATH . '/templates/single-expertise-header.php');
}

function uamswp_expertise_cta() {
	$cta_repeater = get_field('expertise_cta');
	if( $cta_repeater ): 
		$i = 1;
		foreach( $cta_repeater as $cta ) { 
			$cta_heading = $cta['cta_bar_heading'];
			$cta_body = $cta['cta_bar_body'];
			$cta_action_type = $cta['cta_bar_action_type'];

			$cta_button_text = '';
			$cta_button_url = '';
			$cta_button_target = '';
			$cta_button_desc = '';
			if ( $cta_action_type == 'url' ) {
				$cta_button_text = $cta['cta_bar_button_text'];
				$cta_button_url = $cta['cta_bar_button_url'];
				if ( $cta_button_url ) {
					$cta_button_target = $cta_button_url['target'];
				}
				$cta_button_desc = $cta['cta_bar_button_description'];
			}

			$cta_phone_prepend = '';
			$cta_phone = '';
			$cta_phone_link = '';
			if ( $cta_action_type == 'phone' ) {
				$cta_phone_prepend = $cta['cta_bar_phone_prepend'] ? $cta['cta_bar_phone_prepend'] : 'Call';
				$cta_phone = $cta['cta_bar_phone'];
				$cta_phone_link = '<a href="tel:' . format_phone_dash( $cta_phone ) . '">' . format_phone_us( $cta_phone ) . '</a>';
			}

			$cta_layout = 'cta-bar-centered';
			$cta_size = 'normal';
			$cta_use_image = false;
			$cta_image = '';
			$cta_background_color = 'bg-auto';
			$cta_btn_color = 'primary';

			$cta_className = '';
			$cta_className .= ' ' . $cta_layout;
			$cta_className .= ' ' . $cta_background_color;
			$cta_className .= $cta_use_image ? ' bg-image' : '';
			if ( $cta_size == 'small' ) {
				$cta_className .= ' cta-bar-sm';
			} elseif ( $cta_size == 'large' ) {
				$cta_className .= ' extra-padding cta-bar-lg';
			}
			if ( $cta_action_type == 'none' ) {
				$cta_className .= ' no-link';
			}

			echo '<section class="uams-module cta-bar' . $cta_className . '" id="cta-bar-' . $i . '" aria-label="' . $cta_heading . '">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="inner-container">
								<div class="cta-heading">
									<h2>' . $cta_heading . '</h2>
								</div>
								<div class="cta-body">
									<div class="text-container">
										' . $cta_body . '
									</div>';
									echo $cta_action_type == 'url' ?
									'<div class="btn-container">
										<a href="' . $cta_button_url['url'] . '" aria-label="' . $cta_button_desc . '" class=" btn btn-' . $cta_btn_color . ( $cta_size == 'large' ? ' btn-lg' : '' ) . '"' . ( $cta_button_target ? ' target="'. $cta_button_target . '"' : '' ) . ' data-moduletitle="' . $cta_heading . '">' . $cta_button_text . '</a>
									</div>'
									: '';
									echo $cta_action_type == 'phone' ?
									'<div class="btn-container">
										<a href="tel:' . $cta_phone . '" data-moduletitle="' . $cta_heading . '">' . $cta_phone_prepend . ' <span class="no-break">' . $cta_phone . '</span></a>
									</div>'
									: '';
								echo '</div>
							</div>
						</div>
					</div>
				</div>
			</section>';
			$i++;
		}
	endif;
}

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
function uamswp_expertise_header_metadata() { 
	$keywords = get_field('expertise_alternate_names');
	if( $keywords ): 
		$i = 1;
		$keyword_text = '';
		foreach( $keywords as $keyword ) { 
			if ( 1 < $i ) {
				$keyword_text .= ', ';
			}
			$keyword_text .= str_replace(",", "", $keyword['text']);
			$i++;
		}
		echo '<meta name="keywords" content="'. $keyword_text .'" />';
	endif;
}
function uamswp_list_child_expertise() {
	global $page_id;
	global $page_title;
	global $show_child_aoe_section;
	if ( $show_child_aoe_section ) { // If it's suppressed or none available, set to false
		$args = array(
			"post_type" => "expertise",
			"post_status" => "publish",
			"post_parent" => $page_id,
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1, // We do not want to limit the post count
			'meta_query' => array(
				"relation" => "AND",
				array(
					"key" => "hide_from_sub_menu",
					"value" => "1",
					"compare" => "!=",
				),
				array(
					"key" => "expertise_type",
					"value" => "0",
					"compare" => "!=",
				),
			),
		);
		$pages = New WP_Query ( $args );
		if ( $pages->have_posts() ) { ?>
			<section class="uams-module expertise-list bg-auto" id="sub-expertise" aria-labelledby="sub-expertise-title" >
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<h2 class="module-title" id="sub-expertise-title"><span class="title">Areas Within <?php echo $page_title; ?></span></h2>
							<div class="card-list-container">
								<div class="card-list card-list-expertise">
							<?php
								while ( $pages->have_posts() ) : $pages->the_post();
									$id = get_the_ID();
									$child_expertise_list = true; // Indicate that this is a list of child Areas of Expertise within this Area of Expertise
									include( UAMS_FAD_PATH . '/templates/loops/expertise-card.php' );
								endwhile;
								wp_reset_postdata(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php
		}
	}
}
genesis();