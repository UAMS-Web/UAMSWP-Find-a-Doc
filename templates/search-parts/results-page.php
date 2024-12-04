<?php
/**
 * Page archive partial
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
if(has_post_thumbnail( $post_id )) {
    $post_thumb = get_the_post_thumbnail( $post_id );
}
if ( has_excerpt( $post->ID ) && !empty( trim($post->post_excerpt) ) ) {
    $excerpt = get_the_excerpt( $post->ID );
    // $strCount = strlen($post->post_excerpt);
} else {
    // apply_filters( 'excerpt_more', ' ' . '... <a class="more-link" href="' . $post_link . '">Continue Reading</a>' );
    // $excerpt = wp_trim_excerpt( "", $post->ID );
    $text = strip_shortcodes( $post->post_content );
    $text = apply_filters( 'the_content', $text );
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt = wp_trim_words( $text, 20, '... <a class="more-link" href="' . $post_link . '">Continue Reading</a>' );
} 
?>
<article class="post-summary type-page">
<h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
<!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
<div class="row">
    <div class="col-2">
    <?php if ($post_thumb) {
            echo $post_thumb;
        } else { ?>
            <!-- Page icon -->
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAABtElEQVR4nO3dTUvDQBRG4VfR3yl25f/fatV9ahDyMXPuzTmQRRcpyTydFm4LTczMzMb3U/T4TPJ2wnpMb/bC7jm+k3wcvyRzm72ooiyavaCiLFreHL32KF1A2qBUB7n/8fg27eoOqDrIex5RSu+U6iBJM5QOIEkjlC4gSROUTiBJA5RuIElxlI4gSWGUriBJUZTOIElBlO4gSTGUK4AkhVCuApIUQbkSSFIAZe8NLs8ffWwJjXJFkASMclWQBIpyZZAEiHL0DZ7dKOh7dvzu62nriXlE2PNcIxr5ovlK8rrlREHOa9N6PB99Fbavl9kXMLCzd/AhO9AdAksQWILAEgSWILAEgSUILEFgCQJLEFiCwJo5y1qb/fw3cxo1tZ0yvXaHwBIEliCwZn6GbH2Ppn8zuSt3CCxBYAkCSxBYgsASBJYgsJxlrecsywTBJQgsZ1mw3CGwBIElCCxBYAkCSxBYgsBylrWesywTBJcgsJxlwXKHwBIEliCwBIElCCxBYAkCSxBYgsASBJYgsASBJQgsQWAJAksQWILAEgSWILCO/E69wn+I4HOHwBIEliBmZmZN+gV4G22luyj3PAAAAABJRU5ErkJggg=="/>
        <?php } ?>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p>Page</p>
        <p><?php echo $post->post_excerpt; ?></p>
    </div>
</div>
</article>