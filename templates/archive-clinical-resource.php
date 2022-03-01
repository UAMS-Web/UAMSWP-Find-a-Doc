<?php /* Template Name: Clinical Resources Archive */

// Override theme's method of defining the page title
function uamswp_fad_title($html) { 
	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = 'Resources | ' . get_bloginfo( "name" );
	return $html;
}
// add_filter('seopress_titles_title', 'uamswp_fad_title', 15, 2);

get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

  $resource_title = get_field('clinical_resource_archive_headline', 'option');
  $resource_text = get_field('clinical_resource_archive_intro_text', 'option');

 ?>

<div class="content-sidebar-wrap">
    <main class="container-fluid resource-list" id="genesis-content">
        <h1 class="sr-only" itemprop="headline"><?php echo ($resource_title ? $resource_title : 'Clinical Resources' ); ?></h1>
        <div class="row">
            <div class="col-12 col-sm filter-col collapse">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="h4">Search Clinical Resources</h2>
                <?php  echo do_shortcode( '[wpdreams_ajaxsearchpro id=3]' ); ?>
                <h2 class="h4">Filters</h2>
                <fieldset>
                    <legend class="sr-only">Filter by...</legend>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_provider' ); ?></div>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_locations' ); ?></div>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_type' ); ?></div>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_aoe' ); ?></div>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_conditions' ); ?></div>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'resource_treatments' ); ?></div>
                    <button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
                </fieldset>
            </div>
        <!-- <section class="archive-description">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo ($resource_title ? $resource_title : 'Clinical Resources' ); ?></h1>
            </header>
            <?php echo ($resource_text ? '<div class="entry-content clearfix" itemprop="text">' . $resource_text . '</div>' : '' ); ?>
        </section> -->
            <div class="col-12 col-sm list-col">
                <h2 class="module-title sr-only">List of Clinical Resources</h2>
                <div class="row list-col-header">
                    <div class="col result-status">
                        <span class="result-count"><?php echo facetwp_display( 'counts' ); ?> Clinical Resources</span>
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