<?php 
    /**
     *  Template Name: Areas of Expertise Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */
?>
<div class="card">
    <?php if ( has_post_thumbnail($id) ) { ?>
    <?php echo get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']); ?>
    <?php } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" aria-hidden="true" class="card-img-top" />
    </picture>
    <?php } ?>
    <?php $excerpt = get_the_excerpt($id); ?>
    <div class="card-body">
        <h3 class="card-title">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self"><?php echo get_the_title($id); ?></a></span>
        </h3>
        <p class="card-text"><?php echo ( $excerpt ? wp_trim_words( $excerpt, 30, ' &hellip;' ) : wp_trim_words( wp_strip_all_tags( get_the_content($id), 30, ' &hellip;' ) ) ); ?></p>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary stretched-link" aria-label="Go to Area of Expertise page for <?php echo get_the_title($id); ?>">View Area of Expertise</a>
        </div>
    </div>
</div><!-- .card --> 