<?php

// add_filter('seopress_titles_title', 'uamswp_fad_title_archive_locations', 15, 2);

$region_redirect = uamswp_fad_region_redirect_url('_location_region', $_COOKIE, $_GET, $_SERVER);
if ($region_redirect !== null) {
    header('Location: '.$region_redirect);
    exit();
}
get_header();

add_filter('facetwp_template_use_archive', '__return_true');

?>

<div class="content-sidebar-wrap">
    <main class="container-fluid location-list" id="genesis-content">
        <h1 class="sr-only" itemprop="headline">Locations</h1>
        <div class="row">
            <div class="col-12 col-sm filter-col collapse">
                <button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h2 class="h4">Search Locations</h2>
                <?php echo do_shortcode('[wpdreams_ajaxsearchpro id=2]'); ?>
                <h2 class="h4">Filters</h2>
                <fieldset>
                    <legend class="sr-only">Filter by...</legend>
                    <div class="fwp-filter"><?php echo uamswp_fad_str(facetwp_display('facet', 'location_type')); ?></div>
                    <div class="fwp-filter"><?php echo uamswp_fad_str(facetwp_display('facet', 'location_aoe')); ?></div>
                    <div class="fwp-filter"><?php echo uamswp_fad_str(facetwp_display('facet', 'location_region')); ?></div>
                    <button class="btn btn-outline-primary" id="filter-reset" onclick="FWP.reset()">Reset</button>
                </fieldset>
            </div>
            <div class="col-12 col-sm list-col">
                <h2 class="sr-only">List of Locations</h2>
                <div class="alert alert-danger text-center" role="alert">
					If you think you are experiencing a medical emergency, call 911 immediately.
				</div>
                <div class="row list-col-header">
                    <div class="col result-status">
                        <span class="result-count"><?php echo uamswp_fad_str(facetwp_display('counts')); ?> Locations</span>
                        <?php echo uamswp_fad_str(facetwp_display('selections')); ?>
                    </div>
                    <div class="col filter-toggle-container">
						<!-- When button is active, add "active" class. -->
						<button title="Toggle Filter Tray" class="filter-toggle"><span class="sr-only">Toggle Filter Tray</span><span class="fas fa-filter"></span></button>
					</div>
					<div class="col sort-select">
						<?php echo uamswp_fad_str(facetwp_display('sort')); ?>
					</div>
                </div>
                <?php echo uamswp_fad_str(facetwp_display('template', 'locations')); ?>
                <div class="row list-pagination">
					<div class="col">
						<?php echo uamswp_fad_str(facetwp_display('pager')); ?>
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