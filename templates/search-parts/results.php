<?php
/**
 * Fallback archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
**/
// From get_template_part function
$post_id = $data['post_id'];
$blog_id = $data['blog_id'];

if ($blog_id !== get_current_blog_id()) {
switch_to_blog( $blog_id );
}
$post = get_post( $post_id );
$post_title = get_the_title( $post_id );
$post_link = get_permalink($post_id);
$post_type = $post->post_type;
$post_type_name = get_post_type_object($post_type)->labels->singular_name;
if(has_post_thumbnail( $post_id )) {
    $post_thumb = get_the_post_thumbnail( $post_id );
}
$excerpt = '';
if ( has_excerpt( $post_id ) ) {
    $excerpt = get_the_excerpt( $post_id );
} else {
    $excerpt = wp_trim_excerpt( "", $post_id );
}
?>
<article class="post-summary type-post entry entry">
<h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
<div class="row">
    <div class="col-2">
        <?php if ($post_thumb) {
            echo $post_thumb;
        } else { ?>
            <!-- Generic icon -->
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAABPklEQVR4nO3dQQrCQBAAwVH8/5f1A4qaLNiJVXdlsRmCO4IzALDNZcdr78tOcU6bPtvr6lOwjyAxgsTcFr7XnufRGSx5ppqQGEFiBIkRJEaQGEFiBIkRJEaQGEFiBIkRJEaQGEFiBIlZuQ/5Vn0n/5P9jgmJESRGkJhfPkP+fQf/lAmJESRGkBhBYgSJESRGkBhBYgSJESRGkBhBYgSJESRGkJh/2Km/27u8OoedOoLkCBJjp945x8yYkBxBYgSJESRGkBhBYgSJOfJd1qrvD+6yeE2QGEFi3GV1zjEzJiRHkBhBYgSJESRGkBhBYo58l/Upv8tiO0FiBIlxl9U5x8yYkBxBYgSJESRGkBhBYgSJESRGkBhBYgSJESRGkBhBYgSJESRGkBhBYgSJWblTr/+n1CGYkBhBYgQBgJN4ANGvCtLWKEzAAAAAAElFTkSuQmCC"/>
        <?php } ?>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p><?php echo $post_type_name; ?></p>
    </div>
</div>
</article>