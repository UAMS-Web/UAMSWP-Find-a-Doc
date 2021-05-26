<?php 
    /**
     *  Template Name: Areas of Expertise Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */

    $expertise_title = get_the_title($id);
    $expertise_title_attr = str_replace('"', '\'', $expertise_title);
    $expertise_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($expertise_title_attr, null, 'utf-8')));

    // Parent Location 
    $expertise_parent_id = wp_get_post_parent_id($id);
    $expertise_has_parent = $expertise_parent_id ? true : false;
    $parent_expertise = '';
    $parent_id = '';
    $parent_title = '';
    $parent_url = '';

    if ($expertise_has_parent && $expertise_parent_id) { 
        $parent_expertise = get_post( $expertise_parent_id );
    }
    // Get Post ID for Address & Image fields
    if ($parent_expertise) {
        $parent_id = $parent_expertise->ID;
        $parent_title = $parent_expertise->post_title;
        $parent_title_attr = str_replace('"', '\'', $parent_title);
        $parent_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($parent_title_attr, null, 'utf-8')));
        $parent_url = get_permalink( $parent_id );
    }

    $expertise_label = 'Go to the Area of Expertise page for' . $expertise_title_attr;
    
?>
<div class="card">
    <a href="<?php echo get_permalink($id); ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $expertise_title_attr; ?>">
        <?php if ( has_post_thumbnail($id) ) { ?>
        <?php echo get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']); ?>
        <?php } else { ?>
        <picture>
            <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
            <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" />
        </picture>
        <?php } ?>
    </a>
    <?php $excerpt = get_the_excerpt($id); ?>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $expertise_title; ?></a></span>
            <?php if ( $parent_expertise ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" aria-label="Go to Area of Expertise page for <?php echo $parent_title_attr; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif ?>
        </h3>
        <p class="card-text"><?php echo ( $excerpt ? wp_trim_words( $excerpt, 30, ' &hellip;' ) : wp_trim_words( wp_strip_all_tags( get_the_content($id), 30, ' &hellip;' ) ) ); ?></p>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="View Area of Expertise" data-itemtitle="<?php echo $expertise_title_attr; ?>">View Area of Expertise</a>
        </div>
    </div>
</div><!-- .card --> 