<?php
/*
 *
 * UAMS Find-a-Doc Providers Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'content-' . $id;  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) )
    $heading = get_field('block_fad_providers_heading');
if ( empty($content_block) )
    $content_block = get_field('block_fad_providers_description');
if ( empty($background_color) )
    $background_color = get_field('block_fad_providers_background_color');

$filter_id = get_field('block_fad_providers_filter_ids');

if($filter_id) {
    $args = (array(
        'post_type' => "provider",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => $filter_id,
    ));
    $provider_query = new WP_Query( $args );

    if ( $provider_query->have_posts() ) : ?>
        <section class="uams-module provider-list alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="doctors">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
                        <div class="module-body text-center"><p><?php echo $content_block ? $content_block : ''; ?></p></div>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors">
                                <div class="card-list">
                                <?php while ( $provider_query->have_posts() ) : $provider_query->the_post();
                                    $id = get_the_ID(); 
                                    include( UAMS_FAD_PATH . '/templates/loops/physician-card.php' ); 
                                endwhile; 
                                wp_reset_postdata();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endif;
}