<?php get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

  $service_title = get_field('service_archive_headline', 'option');
  $service_text = get_field('service_archive_intro_text', 'option');

 ?>

<div class="content-sidebar-wrap">
    <main id="genesis-content">
        <section class="archive-description">
            <header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><?php echo ($service_title ? $service_title : 'Areas of Expertise' ); ?></h1>
            </header>
            <?php echo ($service_text ? '<div class="entry-content clearfix" itemprop="text">' . $service_text . '</div>' : '' ); ?>
        </section>
        <section class="uams-module">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="module-title sr-only">List of Areas of Expertise</h2>
                        <div class="card-list-container">
                            <div class="card-list">
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
                    });
                })(jQuery);
            </script>
        </section>
    </main>
</div>
<?php get_footer(); ?>