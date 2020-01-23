<?php
function uamswp_title($html) { 

	//you can add here all your conditions as if is_page(), is_category() etc.. 
	$html = 'Locations | ' . get_bloginfo( "name" );
	return $html;
}
add_filter('pre_get_document_title', 'uamswp_title');
get_header();

    add_filter( 'facetwp_template_use_archive', '__return_true' );

 ?>

<div class="content-sidebar-wrap">
    <main id="genesis-content">
        <section class="archive-description">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline">Locations</h1>
            </header>
        </section>
        <section class="uams-module bg-white location-list" id="locations">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Locations</h2>
                        <div class="card-list-container location-card-list-container">
                            <?php echo facetwp_display( 'template', 'locations' ); ?>
                        </div>
                        <div class="row list-pagination">
                            <div class="col">
                                <?php echo facetwp_display( 'pager' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
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

<?php get_footer(); ?>