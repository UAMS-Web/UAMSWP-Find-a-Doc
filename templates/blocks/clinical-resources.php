<?php
    /**
     *  Template Name: Clinical Resources loop / text block
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $resource_query
     *      $resource_more
     *      $resource_more_suppress
     *      $resource_heading_related_pre
     *      $resource_heading_related_post
     *      $resource_heading_related_name
     */

    $resource_heading = 'Resources';
    if ( $resource_heading_related_pre ) {
        $resource_heading = 'Related ' . $resource_heading;
    }
    if ( $resource_heading_related_post ) {
        $resource_heading = $resource_heading . ' Related to ' . $resource_heading_related_name;
    }
    $more_text = 'Want to find more resources related to ' . $resource_heading_related_name . '?';
    $more_button_url = '/clinical-resource/';
    $more_button_description = 'View the full list of clinical resources related to ' . $resource_heading_related_name;
    $more_button_description_attr = str_replace('"', '\'', $more_button_description);
    $more_button_description_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($more_button_description_attr, null, 'utf-8')));
    $more_button_target = '_blank';
    $more_button_text = 'View the Full List';
?>
<section class="uams-module resource-list bg-auto" id="related-resources" aria-labelledby="related-resources-title">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title" id="related-resources-title"><?php echo $resource_heading; ?></h2>
                <div class="card-list-container">
                    <div class="card-list card-list-resource">
                    <?php 
                    while ($resource_query->have_posts()) : $resource_query->the_post();
                        $id = get_the_ID();
                        include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <?php if ( $resource_more ) { ?>
                    <div class="more">
                        <p class="lead"><?php echo $more_text; ?></p>
                        <div class="cta-container">
                            <a href="<?php echo $more_button_url; ?>" class="btn btn-outline-primary" aria-label="<?php echo $more_button_description_attr; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>