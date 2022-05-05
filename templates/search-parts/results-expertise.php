<?php
/**
 * Expertise archive partial
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
$post_title = $post->post_title;
$post_link = get_permalink($post_id);
$content = get_field($post_type.'_content', $post_type.'_'.$post_id);
$excerpt = '';
if ( has_excerpt( $post_id ) ) {
    $excerpt = get_the_excerpt( $post_id );
} else {
    $excerpt = wp_trim_words($content, 30);
} ?>
<article class="post-summary type-<?php echo $post_type; ?> <?php echo $post_type; ?>-<?php echo $post_id; ?> entry">
    <h3 class="h4"><a href="'<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
    <!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
    <div class="row">
        <div class="col-2">
        <?php if ( has_post_thumbnail($post_id) ) { ?>
        <?php echo get_the_post_thumbnail($post_id, 'aspect-16-9-small', ['class' => 'card-img-top', 'loading' => 'lazy']); ?>
        <?php } else { ?>
        <picture>
            <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
            <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" />
        </picture>
        <?php } ?>
        </div>
        <div class="col-10">
            <p><?php echo $post_link; ?></p>
            <p><?php echo $excerpt; ?></p>
            <p><?php echo $post_type_name; ?></p>
        </div>
    </div>
</article>