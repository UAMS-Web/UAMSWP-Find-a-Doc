<?php
/* 
 * Template Name: Clinical Resources Archive
 */

// Add page template class to body element's classes

	// Do nothing

// Filter posts_where

	// Do nothing

// Filter terms_clauses

	// Do nothing

// Get system settings for ontology item labels

	// Get system settings for provider labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-provider.php' );

	// Get system settings for location labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-location.php' );

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-expertise.php' );

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-clinical-resource.php' );

	// Get system settings for Clinical Resource facet labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_facets-clinical-resource.php' );

	// Get system settings for condition labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-condition.php' );

	// Get system settings for treatment labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_labels-treatment.php' );

// Get system settings for clinical resource archive text
include( UAMS_FAD_PATH . '/templates/parts/vars/vars_sys_archive-clinical-resource.php' );

// Get the page ID

	// $page_id = get_the_ID(); // int

// Get the page title

	$page_title = $clinical_resource_archive_headline; // string
	$page_title_attr = uamswp_attr_conversion($page_title);

	// Array for page titles and section titles

		$page_titles = array(
			'page_title'		=> $page_title,
			'page_title_attr'	=> $page_title_attr
		);

// Get the page URL

	$page_url = user_trailingslashit(get_permalink());

// alpha

	// Do nothing

// Get system settings for this archive page's featured image

	$archive_image_clinical_resource_vars = isset($archive_image_clinical_resource_vars) ? $archive_image_clinical_resource_vars : uamswp_fad_archive_image_clinical_resource();
		$clinical_resource_archive_image = $archive_image_clinical_resource_vars['clinical_resource_archive_image']; // int

// Get the featured image

	$featured_image = $clinical_resource_archive_image; // Image ID // int

// Override theme's method of defining the meta page title

	// Construct the meta title

		$meta_title_base_addition = $clinical_resource_plural_name_attr; // Word or phrase to use to form base meta title // string
		$meta_title_vars = isset($meta_title_vars) ? $meta_title_vars : uamswp_fad_meta_title_vars(
			$page_title, // string
			$page_title_attr, // string (optional)
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
	include( UAMS_FAD_PATH . '/templates/parts/meta_social.php' );

get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>
<div class="content-sidebar-wrap">
	<main class="container-fluid resource-list" id="genesis-content">
		<h1 class="sr-only" itemprop="headline"><?php echo $page_title; ?></h1>
		<div class="row">
			<div class="col-12 col-sm filter-col collapse">
				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="h4">Search <?php echo $clinical_resource_plural_name; ?></h2>
				<?php echo do_shortcode( '[wpdreams_ajaxsearchpro id=3]' ); ?>
				<h2 class="h4">Filters</h2>
				<fieldset>
					<legend class="sr-only">Filter by...</legend>
					<?php // When adding facets, make sure relevant uamswp_fad_labels_*() function is also added to template ?>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_provider' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_locations' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_type' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_aoe' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_conditions' ); ?></div>
					<div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_treatments' ); ?></div>
					<button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
				</fieldset>
			</div>
			<div class="col-12 col-sm list-col">
				<h2 class="module-title sr-only">List of <?php echo $clinical_resource_plural_name; ?></h2>
				<div class="row list-col-header">
					<div class="col result-status">
						<span class="result-count"><?php echo facetwp_display( 'counts' ); ?> <?php echo $clinical_resource_plural_name; ?></span>
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
				<?php echo facetwp_display( 'template', 'clinical_resources' ); ?>
				<?php //get_template_part( 'templates/physician-loop' ); ?>
				<div class="row list-pagination">
					<div class="col">
						<?php echo facetwp_display( 'pager' ); ?>
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
				});
			})(jQuery);
		</script>
	</main>
</div>
<?php get_footer(); ?>