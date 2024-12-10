<?php
/**
 * Provider archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
**/
$post_id = $data['post_id'];
$blog_id = $data['blog_id'];

if ($blog_id !== get_current_blog_id()) {
    switch_to_blog( $blog_id );
}
$post = get_post( $post_id );
$post_type = $post->post_type;
$post_type_name = get_post_type_object($post_type)->labels->singular_name;
$post_link = get_permalink($post_id);
$degrees = get_field('physician_degree', $post_id);
$degree_list = '';
$i = 1;
if ( $degrees ) {
    foreach( $degrees as $degree ):
        $degree_name = get_term( $degree, 'degree');
        if( is_object($degree_name) ) {
            $degree_list .= $degree_name->name;
            if( count($degrees) > $i ) {
                $degree_list .= ", ";
            }
        }
        $i++;
    endforeach; 
}
$full_name = get_field('physician_first_name', $post_id) .' ' .(get_field('physician_middle_name', $post_id) ? get_field('physician_middle_name', $post_id) . ' ' : '') . get_field('physician_last_name', $post_id) . (get_field('physician_pedigree', $post_id) ? '&nbsp;' . get_field('physician_pedigree', $post_id) : '') .  ( $degree_list ? ', ' . $degree_list : '' ); 
$full_name_attr = str_replace('"', '\'', $full_name);
$full_name_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($full_name_attr, null, 'utf-8')));
$physician_resident = get_field('physician_resident', $post_id);
$physician_resident_name = 'Resident Physician';
$physician_title = get_field('physician_title', $post_id);
$physician_title_name = $physician_resident ? $physician_resident_name : get_term( $physician_title, 'clinical_title' )->name;
$physician_service_line = get_field('physician_service_line', $post_id); 
$physician_bio = ( get_field('physician_short_clinical_bio', $post_id) ? get_field( 'physician_short_clinical_bio', $post_id) : wp_trim_words( get_field( 'physician_clinical_bio', $post_id ), 30, ' &hellip;' ) );
?>
<article class="post-summary type-provider">
    <h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $full_name; ?></a></h3>
    <!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
    <div class="row">
        <div class="col-2">
<picture>
    <?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
        <source srcset="<?php echo image_sizer(get_post_thumbnail_id($post_id), 253, 337, 'center', 'center'); ?>"
            media="(min-width: 1px)">
        <img src="<?php echo image_sizer(get_post_thumbnail_id(), 253, 337, 'center', 'center'); ?>" itemprop="image" class="card-img-top" alt="<?php echo $full_name_attr; ?>" loading="lazy" />
    <?php } elseif ( has_post_thumbnail() ) { ?>
        <?php echo get_the_post_thumbnail( $post_id, 'medium',  array( 'itemprop' => 'image', 'class' => 'card-img-top', 'loading' => 'lazy' ) ); ?>
    <?php } else { ?>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />
    <?php } ?>
</picture>
</div>
<div class="col-10">
<p><?php echo $post_link; ?></p>
<p><?php 
    if(! empty( $physician_title_name ) || ! empty( $physician_service_line ) ){
        echo '<span class="subtitle">';
        echo ($physician_title_name ? $physician_title_name : '');
        echo '</span>';
    }
    ?></p>
<p><?php echo $physician_bio; ?></p>
<p><?php echo $post_type_name; ?></p>
</div>
</div>
</article>