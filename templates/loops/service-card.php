<?php 
    /**
     *  Template Name: Services Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */
?>
<div class="card">
    <?php if ( has_post_thumbnail() ) { ?>
        <p>
        <?php the_post_thumbnail('aspect-16-9-small', ['class' => 'img-responsive']); ?>
        </p>
    <?php } ?>
    <?php $excerpt = get_the_excerpt(); ?>
    <div class="card-body">
        <h3 class="card-title">
            <span class="name"><a href="<?php echo get_permalink(); ?>" target="_self"><?php the_title(); ?></a></span>
        </h3>
        <p class="card-text"><?php echo ( $excerpt ? wp_trim_words( $excerpt, 30, ' &hellip;' ) : wp_trim_words( wp_strip_all_tags( get_the_content(), 30, ' &hellip;' ) ) ); ?></p>
            <a href="<?php echo get_permalink(); ?>" class="btn btn-primary stretched-link" aria-label="Go to Area of Expertise page for <?php the_title(); ?>">View Area of Expertise</a>
    </div><!-- .card-body -->
</div><!-- .card --> 