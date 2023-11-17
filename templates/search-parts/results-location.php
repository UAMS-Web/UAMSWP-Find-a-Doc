<?php
/**
 * Location archive partial
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
// Reset variables
$featured_image = '';
$address_id = $post->ID;
$child_location_list = isset($child_location_list) ? $child_location_list : false;

$location_title = get_the_title($post_id);
$location_title_attr = str_replace('"', '\'', $location_title);
$location_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($location_title_attr, null, 'utf-8')));
// Parent Location 
$location_has_parent = get_field('location_parent', $post_id);
$location_parent_id = get_field('location_parent_id', $post_id);
$parent_location = '';
$parent_id = '';
$parent_title = '';
$parent_url = '';
$override_parent_photo = '';
$override_parent_photo_featured = '';

if ($location_has_parent && $location_parent_id) { 
    $parent_location = get_post( $location_parent_id );
}
// Get Post ID for Address & Image fields
if ($parent_location) {
    $parent_id = $parent_location->ID;
    $parent_title = $parent_location->post_title;
    $parent_title_attr = str_replace('"', '\'', $parent_title);
    $parent_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($parent_title_attr, null, 'utf-8')));
    $parent_url = get_permalink( $parent_id );
    $featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
    $address_id = $parent_id;

    $override_parent_photo = get_field('location_image_override_parent', $post_id);
    $override_parent_photo_featured = get_field('location_image_override_parent_featured', $post_id);
    
    // Set featured image
    if ( $override_parent_photo && $override_parent_photo_featured ) {
        $featured_image = get_the_post_thumbnail($post_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
    }
} else {
    // Set featured image
    if ( has_post_thumbnail($post_id) ) {
        $featured_image = get_the_post_thumbnail($post_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
    }
}
                                        
$location_address_1 = get_field('location_address_1', $address_id );
$location_building = get_field('location_building', $address_id );
if ($location_building) {
    $building = get_term($location_building, "building");
    $building_slug = $building->slug;
    $building_name = $building->name;
}
$location_floor = get_field_object('location_building_floor', $address_id );
    $location_floor_value = '';
    $location_floor_label = '';
    if ( $location_floor && is_object($location_floor) ) {
        $location_floor_value = $location_floor['value'];
        $location_floor_label = $location_floor['choices'][ $location_floor_value ];
    }
$location_suite = get_field('location_suite', $address_id );
$location_address_2 =
    ( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? '<br />' : '' ) : '' )
    . ( $location_floor && !empty($location_floor_value) && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ', ' : '' ) : '' )
    . ( $location_suite ? $location_suite : '' );
$location_address_2_schema =
    ( ( $location_building && $building_slug != '_none' ) ? $building_name . ( ( ($location_floor && $location_floor_value) || $location_suite ) ? ' ' : '' ) : '' )
    . ( $location_floor && $location_floor_value != "0" ? $location_floor_label . ( ( $location_suite ) ? ' ' : '' ) : '' )
    . ( $location_suite ? $location_suite : '' );

$location_address_2_deprecated = get_field('location_address_2', $address_id );
if (!$location_address_2) {
    $location_address_2 = $location_address_2_deprecated;
    $location_address_2_schema = $location_address_2_deprecated;
}

$location_city = get_field('location_city', $address_id);
$location_state = get_field('location_state', $address_id);
$location_zip = get_field('location_zip', $address_id);
$location_phone = get_field('location_phone', $post_id);
$location_phone_format_dash = format_phone_dash( $location_phone );
?>
<article class="post-summary type-location">
<h3 class="h4"><span class="name"><a href="<?php echo $post_link ; ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span></h3>
<!-- <p><?php echo 'Post ID: ' .$post->ID . ' Blog ID: ' . $blog_id; ?></p> -->
<div class="row">
        <div class="col-2">
<?php if ( $featured_image ) {
        echo $featured_image;
    } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
    </picture>
    <?php } ?>
    </div>
    <div class="col-10">
<p><?php echo $post_link; ?></p>
<p><?php if ( $parent_location && !$child_location_list ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif ?></p>
            <p class="card-text"><?php echo $location_address_1; ?><br/>
            <?php echo $location_address_2 ? $location_address_2 . '<br/>' : ''; ?>
            <?php echo $location_city . ', ' . $location_state . ' ' . $location_zip; ?>
        </p>
        <p><?php echo $location_phone_format_dash; ?></p>
<p><?php echo $post_type_name; ?></p>
</div>
</div>
</article>