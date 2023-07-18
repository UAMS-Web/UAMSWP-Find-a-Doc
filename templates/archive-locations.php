<?php
/*
 * Template Name: Locations Archive
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

	// // Get system settings for Condition labels
	// uamswp_fad_labels_condition();

	// // Get system settings for Treatment labels
	// uamswp_fad_labels_treatment();

// Get system settings for Location archive page text
uamswp_fad_archive_text_location();

// // Get the page ID
// $page_id = get_the_ID(); // int

// Get the page title
$page_title = $location_archive_headline; // string
// $page_title_attr = uamswp_attr_conversion($page_title);

// // Get the page URL
// $page_url = get_permalink();

// Get system settings for the featured image of a Location archive page
uamswp_fad_archive_image_location();

// Get the featured image
$page_image_id = $location_archive_image; // Image ID // int

// Override theme's method of defining the meta page title
$meta_title_base_addition = $location_plural_name_attr; // Word or phrase to use to form base meta title
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// // Override theme's method of defining the meta description
// $excerpt = '';
// add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// // Construct the meta keywords element
// $keywords = '';
// add_action('wp_head','uamswp_keyword_hook_header');

// Override the theme's method of defining the social meta tags

	// Crop and resize images for Open Graph and Twitter
	uamswp_meta_image_resize();

	// Open Graph meta tags
	add_filter('seopress_social_og_thumb', 'uamswp_sp_social_og_thumb'); // Filter Open Graph thumbnail (og:image)

	// Twitter Card meta tags
	add_filter('seopress_social_twitter_card_thumb', 'uamswp_sp_social_twitter_card_thumb'); // Filter Twitter Card thumbnail (twitter:image:src)

if ( isset( $_COOKIE['wp_filter_region']) && !isset($_GET['_location_region'])) {
	$region = $_COOKIE['wp_filter_region'];
	$url = $_SERVER["REQUEST_URI"];
	$url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?').'_location_region='. $region;
	header("Location: ". $url);
	exit();
}
get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>

<div class="content-sidebar-wrap">
	<main class="container-fluid location-list" id="genesis-content">
		<h1 class="sr-only" itemprop="headline"><?php echo $page_title; ?></h1>
		<div class="row">
			<div class="col-12 col-sm filter-col collapse">
				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="h4">Search <?php echo $location_plural_name; ?></h2>
				<?php echo do_shortcode( '[wpdreams_ajaxsearchpro id=2]' ); ?>
				<h2 class="h4">Filters</h2>
				<fieldset>
					<legend class="sr-only">Filter by...</legend>
					<?php // When adding facets, make sure relevant uamswp_fad_labels_*() function is also added to template ?>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_type' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_aoe' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_region' ); ?></div>
					<button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
				</fieldset>
			</div>
			<div class="col-12 col-sm list-col">
				<h2 class="sr-only">List of <?php echo $location_plural_name; ?></h2>
				<div class="alert alert-danger text-center" role="alert">
					If you think you are experiencing a medical emergency, call 911 immediately.
				</div>
				<div class="row list-col-header">
					<div class="col result-status">
						<span class="result-count"><?php echo facetwp_display( 'counts' ); ?> <?php echo $location_plural_name; ?></span>
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
				<?php echo facetwp_display( 'template', 'locations' ); ?>
				<div class="row list-pagination">
					<div class="col">
						<?php echo facetwp_display( 'pager' ); ?>
					</div>
				</div>
				<script>
					(function($) {
						$(document).on('facetwp-loaded', function() {
							if (9 >= FWP.settings.pager.total_rows ) {
								$('.list-pagination').hide()
							}
						});
					})(jQuery);
				</script>
			</div>
		</div>
	</main>
</div>

<?php get_footer(); ?>