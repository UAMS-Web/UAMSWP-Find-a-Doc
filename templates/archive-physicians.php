<?php
/*
 * Template Name: Providers Archive
 */

// Add page template class to body element's classes

	// Do nothing

// Filter posts_where

	// Do nothing

// Filter terms_clauses

	// Do nothing

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-expertise.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_labels-treatment.php' );

// Get system settings for provider archive text
include( UAMS_FAD_PATH . '/templates/parts/vars/sys/vars_sys_archive-provider.php' );

// Get the page ID

	// $page_id = get_the_ID(); // int

// Get the page title

	$page_title = $provider_archive_headline; // string
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

// Get the page URL

	// $page_url = user_trailingslashit(get_permalink());

// alpha

	// Do nothing

// Get system settings for this archive page's featured image

	$archive_image_provider_vars = isset($archive_image_provider_vars) ? $archive_image_provider_vars : uamswp_fad_archive_image_provider();
		$provider_archive_image = $archive_image_provider_vars['provider_archive_image']; // int

// Get the featured image

	$featured_image = $provider_archive_image; // Image ID // int

// Override theme's method of defining the meta page title

	// Construct the meta title

	$meta_title_base_addition = $provider_plural_name_attr; // Word or phrase to use to form base meta title
	$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
		$page_title_attr, // string
		$meta_title_base_addition // string (optional) // Word or phrase to use to form base meta title // Defaults to $page_title_attr
	);
		$meta_title = $meta_title_vars['meta_title']; // string

	// Modify SEOPress's standard meta title settings

		add_filter( 'seopress_titles_title', function( $html ) use ( $meta_title ) {

			$html = $meta_title;

			return $html;

		}, 15, 2 );

// Set the schema description and the meta description

	// // Get excerpt
	// 
	// 	$excerpt = get_the_excerpt();
	// 	$excerpt_user = true;
	// 
	// 	if ( empty( $excerpt ) ) {
	// 
	// 		$excerpt_user = false;
	// 
	// 	}
	// 
	// // Set schema description
	// 
	// 	$schema_description = $excerpt; // Used for Schema Data. Should ALWAYS have a value
	// 
	// // Override theme's method of defining the meta description
	// 
	// 	add_filter('seopress_titles_desc', function( $html ) use ( $excerpt ) {
	// 
	// 		$html = $excerpt;
	// 
	// 		return $html;
	// 
	// 	} );

// Construct the meta keywords element

	// $keywords = '';
	// 
	// add_action( 'wp_head', function() use ($keywords) {
	// 	uamswp_keyword_hook_header(
	// 		$keywords // array
	// 	);
	// } );

// Override the theme's method of defining the social media meta tags

	// Filter hooks
	include( UAMS_FAD_PATH . '/templates/parts/page/common/meta/social.php' );

// Region Cookie

	if ( isset( $_COOKIE['wp_filter_region'] ) && !isset( $_GET['_provider_region'] ) ) {
		$region = $_COOKIE['wp_filter_region'];
		$url = $_SERVER["REQUEST_URI"];
		$url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?').'_provider_region='. $region;
		header("Location: ". $url);
		exit();
	}

get_header();

function custom_field_excerpt($title) {
	// Bring in variables from outside of the function
	global $post; // WordPress-specific global variable

	$text = get_field($title);
	if ( '' != $text ) {
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
		$excerpt_length = 35; // 35 words
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}
	return apply_filters('the_excerpt', $text);
}
function wpdocs_custom_excerpt_length( $length ) {
	return 35; // 35 words
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
<div class="content-sidebar-wrap">
	<main class="container-fluid doctor-list" id="genesis-content">
		<h1 class="sr-only" itemprop="headline"><?php echo $page_title; ?></h1>
		<div class="row">
			<div class="col-12 col-sm filter-col collapse">
				<h2 class="h3">Filters</h2>
				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<fieldset>
					<legend class="sr-only">Filter by...</legend>
					<h3 class="h6">Search <?php echo $provider_plural_name; ?></h3>
					<?php echo do_shortcode( '[wpdreams_ajaxsearchpro id=1]' ); ?>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'alpha' ); ?></div>
					<?php // When adding facets, make sure relevant uamswp_fad_labels_*() function is also added to template
					echo do_shortcode( '		<div class="fwp-filter">[facetwp facet="primary_care"]</div>
													<div class="fwp-filter">[facetwp facet="physician_areas_of_expertise"]</div>
													<div class="fwp-filter">[facetwp facet="conditions"]</div>
													<div class="fwp-filter">[facetwp facet="treatments_procedures"]</div>
													<div class="fwp-filter">[facetwp facet="patient_types"]</div>
													<div class="fwp-filter">[facetwp facet="physician_gender"]</div>
													<div class="fwp-filter">[facetwp facet="physician_language"]</div>
													<div class="fwp-filter">[facetwp facet="locations"]</div>
													<div class="fwp-filter">[facetwp facet="provider_region"]</div>
													<button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
												' );
					?>
				</fieldset>
			</div>
			<div class="col-12 col-sm list-col">
				<h2 class="sr-only">List of <?php echo $provider_plural_name; ?></h2>
				<div class="alert alert-danger text-center" role="alert">
					If you think you are experiencing a medical emergency, call 911 immediately.
				</div>
				<div class="row list-col-header">
					<div class="col result-status">
						<span class="result-count"><?php echo facetwp_display( 'counts' ); ?> <?php echo $provider_plural_name; ?></span>
						<?php echo facetwp_display( 'selections' ); ?>
					</div>
					<div class="col filter-toggle-container">
						<!-- When button is active, add "active" class. -->
						<button title="Toggle Filter Tray" class="filter-toggle"><span class="sr-only">Toggle Filter Tray</span><span class="fas fa-filter"></span></button>
					</div>
					<div class="col sort-select">
						<?php echo facetwp_display( 'sort' ); ?>
					</div>
				</div>
				<?php echo facetwp_display( 'template', 'physician' ); ?>
				<div class="row list-pagination">
					<div class="col">
						<?php echo facetwp_display( 'pager' ); ?>
					</div>
				</div>
				<script>
					(function($) {
						$(document).on('facetwp-loaded', function() {
							if (3 >= FWP.settings.pager.total_rows ) {
								$('.list-pagination').hide()
							}
						});
					})(jQuery);
				</script>
				<div id="why_not_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="why-not-title" aria-modal="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="why-not-title">Why Are There No Ratings?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<p>There is no publicly available rating for this <?php echo strtolower($provider_single_name); ?> for one of three reasons:</p>
								<ul>
									<li>The <?php strtolower($provider_single_name); ?> does not see patients</li>
									<li>The <?php strtolower($provider_single_name); ?> sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.</li>
									<li>The <?php strtolower($provider_single_name); ?> is a resident physician.</li>
								</ul>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
<?php get_footer(); ?>
