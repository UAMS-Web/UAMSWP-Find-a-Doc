<?php 
    /**
     *  Template Name: Clinical Resource Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */

    $resource_title = get_the_title($id);
    $resource_title_attr = str_replace('"', '\'', $resource_title);
    $resource_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($resource_title_attr, null, 'utf-8')));

    // Parent Location 
    $resource_parent_id = wp_get_post_parent_id($id);
    $resource_has_parent = $resource_parent_id ? true : false;
    $parent_resource = '';
    $parent_id = '';
    $parent_title = '';
    $parent_url = '';

    if ($resource_has_parent && $resource_parent_id) { 
        $parent_resource = get_post( $resource_parent_id );
    }
    // Get Post ID for Address & Image fields
    if ($parent_resource) {
        $parent_id = $parent_resource->ID;
        $parent_title = $parent_resource->post_title;
        $parent_title_attr = str_replace('"', '\'', $parent_title);
        $parent_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($parent_title_attr, null, 'utf-8')));
        $parent_url = get_permalink( $parent_id );
    }

    $resource_label = 'Go to the Clinical Resource page for' . $resource_title_attr;
    
    $resource_excerpt = get_the_excerpt($id) ? get_the_excerpt($id) : wp_strip_all_tags( get_the_content($id) );
    $resource_excerpt_len = strlen($resource_excerpt);
    if ( $resource_excerpt_len > 160 ) {
        $resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
    }

?>
<div class="card">
    <a href="<?php echo get_permalink($id); ?>" target="_self" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $resource_title_attr; ?>">
        <?php if ( has_post_thumbnail($id) ) { ?>
        <?php echo get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']); ?>
        <?php } else { ?>
        <picture>
            <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
            <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" />
        </picture>
        <?php } ?>
    </a>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $resource_title_attr; ?>"><?php echo $resource_title; ?></a></span>
            <?php if ( $parent_resource ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" aria-label="Go to Clinical Resource page for <?php echo $parent_title_attr; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $resource_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif ?>
        </h3>
        <p class="card-text"><?php echo $resource_excerpt; ?></p>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="<?php echo $resource_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $resource_title_attr; ?>">View Clinical Resource</a>
        </div>
    </div>
</div><!-- .card --> 