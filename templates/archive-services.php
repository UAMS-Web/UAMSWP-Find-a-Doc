<?php get_header();

  add_filter( 'facetwp_template_use_archive', '__return_true' );

  // get_template_part( 'header', 'image' ); ?>

  <div class="container uams-body">

    <div class="row">

      <div class="col-md-12 uams-content" role='main'>

        <?php // uams_site_title(); ?>

      <?php // Hard coded breadcrumbs ?>
      <nav class="uams-breadcrumbs" role="navigation" aria-label="breadcrumbs">
        <ul>
          <li><a href="http://www.uams.edu" title="University of Arkansas for Medical Sciences">Home</a></li>
          <li><a href="/" title="<?php echo str_replace('   ', ' ', get_bloginfo('title')); ?>"><?php echo str_replace('   ', ' ', get_bloginfo('title')); ?></a></li>
          <li class="current" title="Services"><span>Services</span></li>
        </ul>
      </nav>

        <div id='main_content' class="uams-body-copy" tabindex="-1">

          <h1>Clinical Services</h1><hr>
          <?php
            // Start the Loop.
            $service_posts = new WP_Query( array(
              'posts_per_page' => -1,
              'post_type' => 'services',
              'post_parent' => 0, // required
              'orderby' => 'title',
              'order' => 'ASC',
              // 'paged' => $paged,
              // 'post__not_in' => $do_not_duplicate 
            ) );

            if ( $service_posts->have_posts() ) : while ( $service_posts->have_posts() ) : $service_posts->the_post();
                if ( get_post_meta( get_the_ID(), 'show_image_single', true ) && is_single() && get_post_thumbnail_id() ) { ?>
                    <p class="featured-image">
                    <a href="<?php echo get_the_post_thumbnail_url( get_the_ID(),'full' ); ?>" title="<?php echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>" data-title="<?php echo get_post_field( 'post_title', get_post_thumbnail_id() ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', get_post_thumbnail_id() ); ?>">
                        <span class="screen-reader-text"><?php esc_attr_e( 'View Larger Image', 'UAMS' ); ?></span>
                    <?php the_post_thumbnail( 'post-image' ); ?>
                    <?php if ( get_post_field('post_excerpt', get_post_thumbnail_id() )) { ?>
                        <br/><span class="wp-caption-text"><?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?></span>
                        <?php } ?>
                    </a>
                    </p>
                <?php
                }
                echo '<h3><a href="'. get_post_permalink() .'">'. get_the_title() .'</a></h3>';
                the_post_thumbnail( array(130, 130), array( 'class' => 'attachment-post-thumbnail blogroll-img' ) );
                the_excerpt();
                echo "<hr>";
            endwhile;
            endif;
          ?>
        <!-- <div class="row">
              <div class="col-md-4">
                <?php //echo do_shortcode( '[wpdreams_ajaxsearchpro id=1]' ); ?>
                <?php //echo do_shortcode( '[accordion]
                        //       [section title="Advanced Filter" active="true"]     
                        //       <div class="fwp-filter">[facetwp facet="specialties"]</div>
                        //     <button onclick="FWP.reset()">Reset</button>
                        //     [/section]
                        //   [/accordion]' ); ?>
              </div>
          <div class="col-md-8 people">
            <?php //echo facetwp_display( 'facet', 'alpha' ); ?>
            <div class="row">
              <div class="col-md-8 text-center fwp-counts">
                <?php //echo facetwp_display( 'counts' ); ?> Locations
              </div>
              <div class="col-md-4 text-right">
                <?php //echo facetwp_display( 'sort' ); ?>
              </div>
            </div>
            <hr>
            <?php //echo facetwp_display( 'template', 'locations' ); ?> -->
            <?php //get_template_part( 'templates/physician-loop' ); ?>
            <?php //echo facetwp_display( 'pager' ); ?>
            <?php //echo do_shortcode('[facetwp load_more="true" label="Load more"]'); ?>
          <!--</div> .col -->
        <!--</div> .row -->

        </div><!-- main_content -->

      </div><!-- uams-content -->
    <div id="sidebar"></div>

  </div>

</div>

<?php get_footer(); ?>