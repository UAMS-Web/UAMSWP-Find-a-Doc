<?php get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

 ?>

<main class="container-fluid p-8 p-sm-10 location-list bg-auto" id="locations">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Locations</h1>
            <div class="module-body">
            </div>
            <div class="card-list-container">
                <?php //<div class="card-list"> ?>
                    <?php echo facetwp_display( 'template', 'locations' ); ?>
                    <?php //get_template_part( 'templates/physician-loop' ); ?>
                    <?php //echo facetwp_display( 'pager' ); ?>
                    <?php //echo do_shortcode('[facetwp load_more="true" label="Load more"]'); ?>
                <?php //</div> ?>
            </div>
        </div>
    </div>
</main>



<?php get_footer(); ?>