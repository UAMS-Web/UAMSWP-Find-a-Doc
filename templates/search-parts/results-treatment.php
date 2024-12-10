<?php
/**
 * Treatment archive partial
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
}
?>
<article class="post-summary type-<?php echo $post_type; ?> <?php echo $post_type; ?>-<?php echo $post_id; ?> entry">
<h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
<!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
<div class="row">
    <div class="col-2">
        <!-- Treatment icon -->
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAACDUlEQVR4nO3azU4CMRSG4YPx7iSywxsyxJVekcA1CXtczIbUEApzOn0H3yfpggQ7nfnsz8mwiP5OvQdQWPS8+FPPi+svA4ExkBlbRcQ+Io4xrPut2tRa3ssxInYR8Zo96I/GA3/UQM7bJmvAqwkH/ciBnCJimTHg/cSDntqU97bNGPCh6HSd0ekZWiCZ1kXfP9f+oKYIKgeZXTi17r/39W/q32MvjIHAPCf21WP9b+He+0hZ6pwhMAYCYyAwmXtIqXZNLdfs3nvRveNO4QyBMRAYA4FpuYfU6voOm8YZAmMgMAYCYyAwBgJDOGVly6qgu5z+nCEwBgJjIDDEPWTsjw5mXfk7Q2AMBMZAYAwExkBgep6yaivqS9+7dJqyUlceA4ExEJiee0jtHmClrn4MBMZAYAwExkBgiO9Dxp6SrNSVx0BgDASGuIeMZaWuPAYCYyAwBgJjIDBzeKfei5W6DATHQGCI79T/NWcIjIHAGAiMgcAYCIyV+mVW6jIQHAOBsVKHcYbAGAiMgcAYCIyBwFipX2alLgPBMRAYK3UYZwiMgcAYCIyBwBgITMtTFr0SR3KGwBgIjIHAZO4hVt4JnCEwBgJjIDAGAlMTyLH4vG4xkAf1Vnw+ZHS6i6Hqto1v39ceds0M+ar4jup8ZnW0if7/XXNv7zc/9SuWEbGNYR3sfXNzaYcYlqmX2of8CweH8BE2o6NIAAAAAElFTkSuQmCC"/>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p><?php echo $post_type_name; ?></p>
    </div>
</div>
</article>