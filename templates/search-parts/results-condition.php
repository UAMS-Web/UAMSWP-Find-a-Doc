<?php
/**
 * Condition archive partial
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
        <!-- Condition icon -->
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAAD+UlEQVR4nO2dzU7VQBSAP4ygGxPDDoUIC8JLuPEdNCbqA0DgFfRdWCkE4kZMXJAQE5UouNYXIKhg5EIiBgIu5ja51rltp52ZnrbnS2ZBbu/pTL+eOz+lLSiK0hxmgXWgB1wGLj1gBbgdpWUNZBY4JLyIdPmBSrGyTnwZSXkRoX2FGKm7AgP0gBs17fsIuFnTvr0xBiwB28AJ/s/a0Piu7wnwAVjEHJuo3AI+l6x4W4UMll1gIkIbAGM/tIymC7kEdoDRCO1gKUJj2iDkEliI0A62Uzt9hZ9hY91CqjIJbKRivvcQN5fj1E4nPcVtuhCAqVTMnmuAMsPedOV9DZ1DxY29v0pxr3iqhOKJqwFixvi5CUHZenvNZM0QYYTIEF80NdMqoRkijBhCRgqWGcxSeGy+A9MO9RRH3vi9yvh+GrMUHuO6yCHwHLjjWMeQ7Q8yD4k9n4hN0PZrHyIMFSIMFSIMFSIMFSIMCTN1aTPyWkeFmiHCUCHCUCHCkNCHtG0mXwnNEGGoEGGoEGGoEGGoEGFIGGXpTH0AzRBhqBBhqBBhSOhDdKY+gGaIMFSIMFSIMFSIMFSIMCSMsqrO1Fs1StMMEYYKEYYKEYaEPqRVfUBVNEOEoUKEoUKEoUKEoUKEoUIMU8Aa5mExPeAlMFdrjRwIehdqDUxhv+v3Z/+zNOLaX0eFQp7Bawy/dXrVsn3nhbiewa5kPbT5yLJ954W4nsGu7GfE/2XZvvNCXM9gF8aBvYz4K5bvdF7IgSVmUg4qxB0DtnJi2x5f2GkhD4EzS8yknPW3cWUEWLbEO8Vk3QrDnyXZWSEPyJaRlHPgiWPsZ5Y4b4FrBb7bSSG2zDjDjLYOh3xWNFMeARep73/F9CdF6JwQW2acA48Htrk/ZJu8TLkL/E597wDzqoyidEpIERkJrlJmgG+p7f8A9xzr2BkhLjISikoZB76ktruwbFeETggpIyMhT8oosGmp19OCdUvTSiGDa1NVO2nIHgScWuq0TPlr+a0TMmxtqqyMhLw5S1K2qPbCldYJyVqbKisjIU/KHsWHt8NonZCstakqSyEJWUst+x7iBxUi7Yqhj/8Ty/o5uu4hflDqELKZ8dmbBsQXR9WUncNcXLL9XPl4OUzo+K3rQ8CMtFYxK6t5q6tlCBk/qBB9srU7QdsvrVPvPCpEGCpEGCpEGCpEGDHuoIoyFm8LmiHCUCHCUCHCCNGHtG1mHhXNEGGUEXKc+tvnomDTSd8ekT5WQUi/4H4DlQJGxmv+PTZRXnC/yP9LzFrsZb7kMXZiDNitoXFNKx8x/xMWhQlgJ3CDmlw+9Y9RVEaBBeAdpvOq+yDUXY77x2KeiJmhKIqiKAl/AW5B9qAn0qYtAAAAAElFTkSuQmCC"/>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p><?php echo $post_type_name; ?></p>
    </div>
</div>
</article>