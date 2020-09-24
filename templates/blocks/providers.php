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

$id = 'providers-' . $id;  
    
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
if ( empty($count) )
    $count = get_field('block_fad_providers_count');

$post_ids = array();
$filter_id = get_field('block_fad_providers_filter_ids') ?: array();
$filter_aoe = get_field('block_fad_providers_filter_aoe') ?: array();
$filter_region = get_field('block_fad_providers_filter_region');
$filter_location = get_field('block_fad_providers_filter_location');

$tax_query = array();
if (!empty($filter_region))
{
    $tax_query[] =  array(
        'taxonomy' => 'region',
        'terms' => $filter_region
    );
}

// $meta_query = array();
if (!empty($filter_location))
{   
        // Declare the global wpdb variable
        global $wpdb;

        $custom_table = $wpdb->prefix."uamswp_physicians";

        $filter_where ='';        
        $i = 1;
        foreach ($filter_location as $value) {
            if ($i > 1) {
                $filter_where .= " OR ";
            }
            $filter_where .= "`physician_locations` LIKE '%" . $value . "%'";
            $i++;
        }
    
        // Write our custom query. In this query, we're only selecting the post_id field of each row that matches our set of
        // conditions. Note the %s placeholders – these are dynamic and indicate that we'll be injecting strings in their place.
        $SQL = "SELECT `post_id` FROM `" . $custom_table . "`
                WHERE $filter_where
                ORDER BY `post_id` ASC;";
    
        // Query the database with our SQL statement, fetching the first column of the matched rows. In our case, we
        // only queried the post_id field of each row so we know that the post_id fields will be the first column. The result
        // here is an array of post_ids (provided we have a match)
        $post_ids = $wpdb->get_col( $SQL );
}
if (!empty($filter_aoe))
{   
        // Declare the global wpdb variable
        global $wpdb;

        $custom_table = $wpdb->prefix."uamswp_physicians";

        $filter_where ='';        
        $i = 1;
        foreach ($filter_aoe as $value) {
            if ($i > 1) {
                $filter_where .= " OR ";
            }
            $filter_where .= "`physician_expertise` LIKE '%" . $value . "%'";
            $i++;
        }
    
        // Write our custom query. In this query, we're only selecting the post_id field of each row that matches our set of
        // conditions. Note the %s placeholders – these are dynamic and indicate that we'll be injecting strings in their place.
        $SQL = "SELECT `post_id` FROM `" . $custom_table . "`
                WHERE $filter_where
                ORDER BY `post_id` ASC;";
    
        // Query the database with our prepared SQL statement, fetching the first column of the matched rows. In our case, we
        // only queried the post_id field of each row so we know that the post_id fields will be the first column. The result
        // here is an array of post_ids (provided we have a match)
        $aoe_ids = $wpdb->get_col( $SQL );
}
if(!empty($filter_id) || !empty($filter_location) || !empty($filter_aoe)) {
    $filter_id = array_unique( array_merge( $filter_id, $post_ids, $aoe_ids ) );
}

if($filter_id || $filter_region || $filter_location) {
    $args = (array(
        'post_type' => "provider",
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => $filter_id,
        'tax_query' => $tax_query,
    ));
    $provider_query = new WP_Query( $args ); 

    echo '<pre>'; print_r($SQL); echo '</pre>';
    // echo '<pre>'; print_r($filter_id); echo '</pre>';
    
    if ( $provider_query->have_posts() ) : ?>
        <section class="uams-module provider-list  alignfull <?php echo $background_color ? $background_color : 'bg-auto'; ?>" id="<?php echo $id; ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
                        <div class="module-body text-center"><p><?php echo $content_block ? $content_block : ''; ?></p></div>
                        <div class="card-list-container">
                            <div class="card-list card-list-doctors card-list-doctors-count-<?php echo $count; ?>">
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