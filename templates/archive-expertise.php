<?php get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

  $service_title = get_field('service_archive_headline', 'option');
  $service_text = get_field('service_archive_intro_text', 'option');

 ?>

<main class="container-fluid p-8 p-sm-10 area-of-expertise-list bg-auto">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title"><?php echo ($service_title ? $service_title : 'Areas of Expertise' ); ?></h1>
            <?php echo ($service_text ? '<div class="module-body">' . $service_text . '</div>' : '' ); ?>
            <h2 class="sr-only">List of Areas of Expertise</h2>
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
</main>
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
<?php get_footer(); ?>