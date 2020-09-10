<?php
function uamswp_fad_title($html) {

	//you can add here all your conditions as if is_page(), is_category() etc..
	$html = 'Locations | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('pre_get_document_title', 'uamswp_fad_title', 15, 2);
get_header();

    add_filter( 'facetwp_template_use_archive', '__return_true' );

 ?>

<div class="content-sidebar-wrap">
    <main class="container-fluid doctor-list" id="genesis-content">
        <h1 class="sr-only" itemprop="headline">Locations</h1>
        <div class="row">
            <div class="col-12 col-sm filter-col collapse">
                <h2 class="h3">Filters</h2>
                <button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <fieldset>
                    <legend class="sr-only">Filter by...</legend>
                    <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_search' ); ?></div>
                        <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_region' ); ?></div>
                        <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_aoe' ); ?></div>
                        <div class="fwp-filter"><?php echo facetwp_display( 'facet', 'location_type' ); ?></div>
                        <button class="btn btn-primary" id="filter-apply" onclick="FWP.refresh();">Apply</button> <button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
                </fieldset>
            </div>
            <div class="col-12 col-sm list-col">
                <h2 class="sr-only">List of Locations</h2>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
					If you think you are experiencing a medical emergency, call 911 immediately.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
                <div class="row list-col-header">
                    <div class="col result-count"><?php echo facetwp_display( 'counts' ); ?> Locations</div>
                    <div class="col filter-toggle-container">
						<!-- When button is active, add "active" class. -->
						<button title="Toggle Filter Tray" class="filter-toggle"><span class="sr-only">Toggle Filter Tray</span><span class="fas fa-filter"></span></button>
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