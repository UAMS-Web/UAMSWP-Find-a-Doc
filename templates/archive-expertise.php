<?php
/*
 * Template Name: Areas of Expertise Archive
 */

// Get system settings for ontology item labels

	// // Get system settings for Provider labels
	// $labels_provider = uamswp_fad_labels_provider();
	// 	$provider_single_name = $labels_provider['provider_single_name']; // string
	// 	$provider_single_name_attr = $labels_provider['provider_single_name_attr']; // string
	// 	$provider_plural_name = $labels_provider['provider_plural_name']; // string
	// 	$provider_plural_name_attr = $labels_provider['provider_plural_name_attr']; // string
	// 	$placeholder_provider_single_name = $labels_provider['placeholder_provider_single_name']; // string
	// 	$placeholder_provider_plural_name = $labels_provider['placeholder_provider_plural_name']; // string
	// 	$placeholder_provider_short_name = $labels_provider['placeholder_provider_short_name']; // string
	// 	$placeholder_provider_short_name_possessive = $labels_provider['placeholder_provider_short_name_possessive']; // string

	// // Get system settings for Location labels
	// $labels_location = uamswp_fad_labels_location();
	// 	$location_single_name = $labels_location['location_single_name']; // string
	// 	$location_single_name_attr = $labels_location['location_single_name_attr']; // string
	// 	$location_plural_name = $labels_location['location_plural_name']; // string
	// 	$location_plural_name_attr = $labels_location['location_plural_name_attr']; // string
	// 	$placeholder_location_single_name = $labels_location['placeholder_location_single_name']; // string
	// 	$placeholder_location_plural_name = $labels_location['placeholder_location_plural_name']; // string
	// 	$placeholder_location_page_title = $labels_location['placeholder_location_page_title']; // string
	// 	$placeholder_location_page_title_phrase = $labels_location['placeholder_location_page_title_phrase']; // string

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
	// $labels_condition = uamswp_fad_labels_condition();
	// 	$condition_single_name = $labels_condition['condition_single_name']; // string
	// 	$condition_single_name_attr = $labels_condition['condition_single_name_attr']; // string
	// 	$condition_plural_name = $labels_condition['condition_plural_name']; // string
	// 	$condition_plural_name_attr = $labels_condition['condition_plural_name_attr']; // string
	// 	$placeholder_condition_single_name = $labels_condition['placeholder_condition_single_name']; // string
	// 	$placeholder_condition_plural_name = $labels_condition['placeholder_condition_plural_name']; // string

	// // Get system settings for Treatment labels
	// $labels_treatment = uamswp_fad_labels_treatment();
	// 	$treatment_single_name = $labels_treatment['treatment_single_name']; // string
	// 	$treatment_single_name_attr = $labels_treatment['treatment_single_name_attr']; // string
	// 	$treatment_plural_name = $labels_treatment['treatment_plural_name']; // string
	// 	$treatment_plural_name_attr = $labels_treatment['treatment_plural_name_attr']; // string
	// 	$placeholder_treatment_single_name = $labels_treatment['placeholder_treatment_single_name']; // string
	// 	$placeholder_treatment_plural_name = $labels_treatment['placeholder_treatment_plural_name']; // string

// Get system settings for Areas of Expertise archive page text
$archive_text_expertise = uamswp_fad_archive_text_expertise();
	$expertise_archive_headline = $archive_text_expertise['expertise_archive_headline']; // string
	$expertise_archive_headline_attr = $archive_text_expertise['expertise_archive_headline_attr']; // string
	$expertise_archive_intro_text = $archive_text_expertise['expertise_archive_intro_text']; // string
	$placeholder_expertise_archive_headline = $archive_text_expertise['placeholder_expertise_archive_headline']; // string
	$placeholder_expertise_archive_intro_text = $archive_text_expertise['placeholder_expertise_archive_intro_text']; // string

// // Get the page ID
// $page_id = get_the_ID(); // int

// Get the page title
$page_title = $expertise_archive_headline; // string
// $page_title_attr = uamswp_attr_conversion($page_title);

// // Get the page URL
// $page_url = get_permalink();

// Get system settings for the featured image of an Area of Expertise archive page
$archive_image_expertise = uamswp_fad_archive_image_expertise();
	$expertise_archive_image = $archive_image_expertise['expertise_archive_image']; // int

// Get the featured image
$page_image_id = $expertise_archive_image; // Image ID // int

// Override theme's method of defining the meta page title
$meta_title_base_addition = $expertise_plural_name_attr; // Word or phrase to use to form base meta title
$meta_title_vars = uamswp_fad_meta_title_vars(); // Defines universal variables related to the setting the meta title
	$meta_title = $meta_title_vars['meta_title']; // string
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

// Override theme's method of defining the meta description
$excerpt = $expertise_archive_intro_text;
add_filter('seopress_titles_desc', 'uamswp_fad_meta_desc');

// // Construct the meta keywords element
// $keywords = '';
// add_action('wp_head','uamswp_keyword_hook_header');

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

get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>

<div class="content-sidebar-wrap">
	<main id="genesis-content">
		<section class="archive-description">
			<header class="entry-header">
				<h1 class="entry-title" itemprop="headline"><?php echo $page_title; ?></h1>
			</header>
			<?php echo ($expertise_archive_intro_text ? '<div class="entry-content clearfix" itemprop="text"><div class="archive-intro">' . $expertise_archive_intro_text . '</div></div>' : '' ); ?>
		</section>
		<section class="uams-module" id="expertise">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="module-title sr-only">List of <?php echo $expertise_plural_name; ?></h2>
						<div class="card-list-container">
							<div class="card-list card-list-expertise">
								<?php echo facetwp_display( 'template', 'expertise' ); ?>
								<?php //get_template_part( 'templates/physician-loop' ); ?>
							</div>
						</div>
						<div class="row list-pagination">
							<div class="col">
								<?php echo facetwp_display( 'pager' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php // FacetWP Hide elements
					// Set # value depending on element
					?>
			<script>
				(function($) {
					$(document).on('facetwp-loaded', function() {
						if (3 >= FWP.settings.pager.total_rows ) {
							$('.list-pagination').hide()
						}
						if (FWP.loaded) {
							$('html, body').animate({
								scrollTop: $('main').offset().top
							}, 500);
						}
					});
				})(jQuery);
			</script>
		</section>
	</main>
</div>
<?php get_footer(); ?>