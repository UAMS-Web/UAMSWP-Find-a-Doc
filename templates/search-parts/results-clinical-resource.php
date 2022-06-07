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
        <!-- Health book icon -->
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAAChElEQVR4nO3dQW7TQBTG8X8rkjNAaQ/EFRAHKGoXlbga5QZABS1HYMUCsUElVVdIsIgjYrNwnHnx+zz+ftIsWqnuzDy9pP7qaMDMzMzMzGy6lsAlcAM8AH8qHw/AR+CiWbuUE+AL+ZuUNe6AZ8W7GGTJvIuxGbfAonAvQ1ySvxkq43XhXoa4oT2pt8Dz1BmN4xS4pr32D6kzaqxoT+o0dzqjOqO99l+lFzwqvUAzkehrTkno+o9LftjiuSBiXBAxLogYF0SMCyLGBRHjgohxQcTsU5BXwA/+xQVdm+9/BV7sPzXbxQJ4ZPf081vONEfVXXORoR1yHPFLLdZL4Dv93TGXl6zQDplbMnsITntr9iTgGnP/f0god4iYiA4Z29A3zkl1rDtEjAsixgURs09B3gD36GRZR50xK0+B3+RmWX13xqF3zgHzGWSfDvHLnJgr4Cd5WVbVHTLF19y+ZGDs5MBZVs2cZYlxh4hRyLIO/cY7qezLHSLGBRHjglSg+1xW6Y3h2Ddy0b8/df6HeC7LBdni57IqEP1cljtki8Jddeki+rKs0uv1cZZVM2dZYtwhYhSyrNLX7Ojrp3KHiHFBxLggYvwZw4lT+Ixh1U+dOMuqQPZnDKvukEn9jd5Quw9xllUzZ1li3CFiFLKsoaruQHeIGBdEjAsixlnWxClkWWqcZVlbdpalZvZZlhpnWTVzliXGHSLGBRHjgohxQcS4IGJcEDEuiJiIgqw6X8/tYMlt3b1I0T169Zp5FOUMeEd77RJHr16wexxf+zgv3MsQS9bniWdvRvb4hMjx3bA+3P2W/E3JGp8ROuB+Y8H6PPH3/H+CdI1j1az1HKHOMDMzMzMzG+ovypgsjhAVL+AAAAAASUVORK5CYII="/>
    </div>
    <div class="col-10">
        <p><?php echo $post_link; ?></p>
        <p><?php echo $excerpt; ?></p>
        <p><?php echo $post_type_name; ?></p>
    </div>
</div>
</article>