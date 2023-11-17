<?php
/**
 * Post archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
**/
// global $post;
// if ( !$post_id ) {
    $post_id = $data['post_id'];
    $blog_id = $data['blog_id'];
// }
if ($blog_id !== get_current_blog_id()) {
    switch_to_blog( $blog_id );
}
$post = get_post( $post_id );
$post_title = get_the_title( $post_id );
$post_link = get_permalink($post_id);
$post_thumb = '';
$excerpt = '';
if ( has_excerpt( $post->ID ) && !empty( trim($post->post_excerpt) ) ) {
    $excerpt = get_the_excerpt( $post->ID );
    // $strCount = strlen($post->post_excerpt);
} else {
    /* Make a custom excerpt */
    /* Needed to handle the blog switching */
    $text = strip_shortcodes( $post->post_content );
    $text = apply_filters( 'the_content', $text );
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt = wp_trim_words( $text, 20, '... <a class="more-link" href="' . $post_link . '">Continue Reading</a>' );
} 
?>
<article class="post-summary type-post entry">
<h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
<!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
<div class="row">
    <div class="col-2">
    <?php if ($post_thumb) {
            echo $post_thumb;
        } else { ?>
            <!-- News icon -->
            <img src="data:image/png;base64,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"/>
        <?php } ?>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p>Post</p>
        <p><?php echo get_the_date('F j, Y', $post_id); ?></p>
    </div>
</div>
</article>