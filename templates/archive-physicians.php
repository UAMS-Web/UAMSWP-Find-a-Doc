<?php
/*
 * Template Name: Providers Archive
 */

// Get system settings for ontology item labels

	// Get system settings for Provider labels
	$labels_provider = uamswp_fad_labels_provider();
		$provider_single_name = $labels_provider['provider_single_name']; // string
		$provider_single_name_attr = $labels_provider['provider_single_name_attr']; // string
		$provider_plural_name = $labels_provider['provider_plural_name']; // string
		$provider_plural_name_attr = $labels_provider['provider_plural_name_attr']; // string
		$placeholder_provider_single_name = $labels_provider['placeholder_provider_single_name']; // string
		$placeholder_provider_plural_name = $labels_provider['placeholder_provider_plural_name']; // string
		$placeholder_provider_short_name = $labels_provider['placeholder_provider_short_name']; // string
		$placeholder_provider_short_name_possessive = $labels_provider['placeholder_provider_short_name_possessive']; // string

	// Get system settings for Location labels
	$labels_location = uamswp_fad_labels_location();
		$location_single_name = $labels_location['location_single_name']; // string
		$location_single_name_attr = $labels_location['location_single_name_attr']; // string
		$location_plural_name = $labels_location['location_plural_name']; // string
		$location_plural_name_attr = $labels_location['location_plural_name_attr']; // string
		$placeholder_location_single_name = $labels_location['placeholder_location_single_name']; // string
		$placeholder_location_plural_name = $labels_location['placeholder_location_plural_name']; // string
		$placeholder_location_page_title = $labels_location['placeholder_location_page_title']; // string
		$placeholder_location_page_title_phrase = $labels_location['placeholder_location_page_title_phrase']; // string

	// Get system settings for Area of Expertise labels
	$labels_expertise = uamswp_fad_labels_expertise();
		$expertise_single_name = $labels_expertise['expertise_single_name']; // string
		$expertise_single_name_attr = $labels_expertise['expertise_single_name_attr']; // string
		$expertise_plural_name = $labels_expertise['expertise_plural_name']; // string
		$expertise_plural_name_attr = $labels_expertise['expertise_plural_name_attr']; // string
		$placeholder_expertise_single_name = $labels_expertise['placeholder_expertise_single_name']; // string
		$placeholder_expertise_plural_name = $labels_expertise['placeholder_expertise_plural_name']; // string
		$placeholder_expertise_page_title = $labels_expertise['placeholder_expertise_page_title']; // string

	// // Get system settings for Clinical Resource labels
	// $labels_clinical_resource = uamswp_fad_labels_clinical_resource();
	// 	$clinical_resource_single_name = $labels_clinical_resource['clinical_resource_single_name']; // string
	// 	$clinical_resource_single_name_attr = $labels_clinical_resource['clinical_resource_single_name_attr']; // string
	// 	$clinical_resource_plural_name = $labels_clinical_resource['clinical_resource_plural_name']; // string
	// 	$clinical_resource_plural_name_attr = $labels_clinical_resource['clinical_resource_plural_name_attr']; // string
	// 	$placeholder_clinical_resource_single_name = $labels_clinical_resource['placeholder_clinical_resource_single_name']; // string
	// 	$placeholder_clinical_resource_plural_name = $labels_clinical_resource['placeholder_clinical_resource_plural_name']; // string

	// Get system settings for Condition labels
	$labels_condition = uamswp_fad_labels_condition();
		$condition_single_name = $labels_condition['condition_single_name']; // string
		$condition_single_name_attr = $labels_condition['condition_single_name_attr']; // string
		$condition_plural_name = $labels_condition['condition_plural_name']; // string
		$condition_plural_name_attr = $labels_condition['condition_plural_name_attr']; // string
		$placeholder_condition_single_name = $labels_condition['placeholder_condition_single_name']; // string
		$placeholder_condition_plural_name = $labels_condition['placeholder_condition_plural_name']; // string

	// Get system settings for Treatment labels
	$labels_treatment = uamswp_fad_labels_treatment();
		$treatment_single_name = $labels_treatment['treatment_single_name']; // string
		$treatment_single_name_attr = $labels_treatment['treatment_single_name_attr']; // string
		$treatment_plural_name = $labels_treatment['treatment_plural_name']; // string
		$treatment_plural_name_attr = $labels_treatment['treatment_plural_name_attr']; // string
		$placeholder_treatment_single_name = $labels_treatment['placeholder_treatment_single_name']; // string
		$placeholder_treatment_plural_name = $labels_treatment['placeholder_treatment_plural_name']; // string

// Get system settings for Provider archive page text
$archive_text_provider = uamswp_fad_archive_text_provider();
	$provider_archive_headline = $archive_text_provider['provider_archive_headline']; // string
	$provider_archive_headline_attr = $archive_text_provider['provider_archive_headline_attr']; // string
	$placeholder_provider_archive_headline = $archive_text_provider['placeholder_provider_archive_headline']; // string

// // Get the page ID
// $page_id = get_the_ID(); // int

// Get the page title
$page_title = $provider_archive_headline; // string
// $page_title_attr = uamswp_attr_conversion($page_title);

// // Get the page URL
// $page_url = get_permalink();

// Get system settings for the featured image of a Provider archive page
uamswp_fad_archive_image_provider();

// Get the featured image
$page_image_id = $provider_archive_image; // Image ID // int

// // Override theme's method of defining the meta description
// $excerpt = '';
// add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// // Construct the meta keywords element
// $keywords = '';
// add_action('wp_head','uamswp_keyword_hook_header');

// Override theme's method of defining the meta page title
$meta_title_base_addition = $provider_plural_name_attr; // Word or phrase to use to form base meta title
$meta_title_vars = uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override the theme's method of defining the social meta tags

	// Crop and resize images for Open Graph and Twitter
	$meta_image_resize = uamswp_meta_image_resize( $page_image_id );
		$meta_og_image = $meta_image_resize['meta_og_image']; // string
		$meta_og_image_width = $meta_image_resize['meta_og_image_width']; // int
		$meta_og_image_height = $meta_image_resize['meta_og_image_height']; // int
		$meta_twitter_image = $meta_image_resize['meta_twitter_image']; // string
		$meta_twitter_image_width = $meta_image_resize['meta_twitter_image_width']; // int
		$meta_twitter_image_height = $meta_image_resize['meta_twitter_image_height']; // int

	// Open Graph meta tags
	add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb'); // Filter Open Graph thumbnail (og:image)

	// Twitter Card meta tags
	add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb'); // Filter Twitter Card thumbnail (twitter:image:src)

// Region Cookie
if ( isset($_COOKIE['wp_filter_region']) && !isset($_GET['_provider_region']) ) {
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
