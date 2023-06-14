<?php
/* 
 * Template Name: Clinical Resources Archive
 */

// Get system settings for provider labels
uamswp_fad_labels_provider();

// Get system settings for location labels
uamswp_fad_labels_location();

// Get system settings for area of expertise labels
uamswp_fad_labels_expertise();

// Get system settings for clinical resource labels
uamswp_fad_labels_clinical_resource();

// Get system settings for clinical resource facet labels
uamswp_fad_labels_clinical_resource_facet();

// Get system settings for condition labels
uamswp_fad_labels_conditions();

// Get system settings for treatment labels
uamswp_fad_labels_treatments();

// Get system settings for clinical resource archive page text
uamswp_fad_archive_clinical_resource();
// $clinical_resource_archive_link = get_post_type_archive_link( get_query_var('post_type') );

// Override theme's method of defining the meta page title
$meta_title_base_addition = $clinical_resource_plural_name_attr; // Word or phrase to use to form base meta title
uamswp_fad_title_vars(); // Defines universal variables related to the setting the meta title
add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

get_header();

add_filter( 'facetwp_template_use_archive', '__return_true' );

?>

<div class="content-sidebar-wrap">
	<main class="container-fluid resource-list" id="genesis-content">
		<h1 class="sr-only" itemprop="headline"><?php echo $clinical_resource_archive_headline; ?></h1>
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