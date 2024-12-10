<?php
    /**
     *  Template Name: Areas of Expertise Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     *
     *  Must be used inside a loop
     *  Required var: $id
     */

    // $child_expertise_list indicates whether this is a list of child Areas of Expertise within this Area of Expertise
    // Check if $child_expertise_list is set. Otherwise create the variable and set its value to false.
    $child_expertise_list = isset($child_expertise_list) ? $child_expertise_list : false;

    $expertise_title = get_the_title($id);
    $expertise_title_attr = $expertise_title;
    $expertise_title_attr = str_replace('"', '\'', $expertise_title_attr); // Replace double quotes with single quote
    $expertise_title_attr = str_replace('&#8217;', '\'', $expertise_title_attr); // Replace right single quote with single quote
    $expertise_title_attr = htmlentities($expertise_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
    $expertise_title_attr = str_replace('&nbsp;', ' ', $expertise_title_attr); // Convert non-breaking space with normal space
    $expertise_title_attr = html_entity_decode($expertise_title_attr); // Convert HTML entities to their corresponding characters

    // Parent Area of Expertise
    $expertise_parent_id = wp_get_post_parent_id($id);
    $expertise_has_parent = $expertise_parent_id ? true : false;
    $parent_expertise = '';
    $parent_id = '';
    $parent_title = '';
    $parent_url = '';

    if ($expertise_has_parent && $expertise_parent_id) {
        $parent_expertise = get_post( $expertise_parent_id );
    }
    // Get attributes of parent Area of Expertise
    if ($parent_expertise) {
        $parent_id = $parent_expertise->ID;
        $parent_title = $parent_expertise->post_title;
        $parent_title_attr = $parent_title;
        $parent_title_attr = str_replace('"', '\'', $parent_title_attr); // Replace double quotes with single quote
        $parent_title_attr = str_replace('&#8217;', '\'', $parent_title_attr); // Replace right single quote with single quote
        $parent_title_attr = htmlentities($parent_title_attr, ENT_HTML401, 'UTF-8'); // Convert all applicable characters to HTML entities
        $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space with normal space
        $parent_title_attr = html_entity_decode($parent_title_attr); // Convert HTML entities to their corresponding characters
        $parent_url = get_permalink( $parent_id );
    }

    $expertise_label = 'View Area of Expertise page for' . $expertise_title_attr;
    $expertise_excerpt = get_the_excerpt($id) ? get_the_excerpt($id) : wp_strip_all_tags( get_the_content($id) );
    $expertise_excerpt_len = strlen($expertise_excerpt);
    if ( $expertise_excerpt_len > 160 ) {
        $expertise_excerpt = wp_trim_words( $expertise_excerpt, 23, ' &hellip;' );
    }

?>
<div class="card">
	<?php if ( has_post_thumbnail($id) ) { ?>
	<?php echo get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top', 'loading' => 'lazy']); ?>
	<?php } else { ?>
	<picture>
		<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
		<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" />
	</picture>
	<?php } ?>
    <?php $excerpt = get_the_excerpt($id); ?>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $expertise_title; ?></a></span>
            <?php if ( $parent_expertise && !$child_expertise_list ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" aria-label="Go to Area of Expertise page for <?php echo $parent_title_attr; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif ?>
        </h3>
        <p class="card-text"><?php echo $expertise_excerpt; ?></p>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="View Area of Expertise" data-itemtitle="<?php echo $expertise_title_attr; ?>">View Area of Expertise</a>
        </div>
    </div>
</div><!-- .card -->