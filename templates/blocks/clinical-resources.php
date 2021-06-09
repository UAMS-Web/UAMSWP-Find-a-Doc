<?php
    /**
     *  Template Name: Clinical Resources loop / text block
     *  Designed for UAMS Find-a-Doc
     * 
     *  Required vars:
     *      $resources
     *      $resource_query
     *      $resource_postsPerPage
     *      $resource_more_suppress
     *      $resource_more_key
     *      $resource_more_value
     *      $resource_heading_related_pre
     *      $resource_heading_related_post
     *      $resource_heading_related_name
     * 
     *  List layout intended to either display all items or display a set number with no link to more.
     */

    $resource_heading = 'Resources';
    if ( $resource_heading_related_pre ) {
        $resource_heading = 'Related ' . $resource_heading;
    }
    if ( $resource_heading_related_post ) {
        $resource_heading = $resource_heading . ' Related to ' . $resource_heading_related_name;
    }
    $resource_count = count($resources);
    $resource_more = ( $resource_postsPerPage != -1 && $resource_count > $resource_postsPerPage && ( $resource_more_key && !empty($resource_more_key) && $resource_more_value && !empty($resource_more_value) ) ) ? true : false;
    if ( $resource_more_suppress ) {
        $resource_more = false;
    }
    if ( $resource_count > 4 && $resource_more == false ) {
        $resource_layout = 'list';
    } else {
        $resource_layout = 'card';
    }
    $more_text = 'Want to find more resources related to ' . $resource_heading_related_name . '?';
    $more_button_url = '/clinical-resource/?' . $resource_more_key . '=' . $resource_more_value;
    $more_button_description = 'View the full list of clinical resources related to ' . $resource_heading_related_name;
    $more_button_description_attr = str_replace('"', '\'', $more_button_description);
    $more_button_description_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($more_button_description_attr, null, 'utf-8')));
    $more_button_target = '_blank';
    $more_button_text = 'View the Full List';

    if ( $resource_layout == 'card') { ?>
        <section class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12<?php echo ($hide_heading && empty($description)) ? " sr-only" : ""; ?>">
                        <h2 class="module-title" id="related-resources-title"><span class="title"><?php echo $resource_heading; ?></span></h2>
                    </div>
                    <div class="card-list card-list-left col-12">
                        <?php 
                        while ($resource_query->have_posts()) : $resource_query->the_post();
                            $id = get_the_ID();
                            include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php if ( $resource_more ) { ?>
                        <div class="col-12 more">
                            <p class="lead"><?php echo $more_text; ?></p>
                            <div class="cta-container">
                                <a href="<?php echo $more_button_url; ?>" class="btn btn-outline-primary" aria-label="<?php echo $more_button_description_attr; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div><!-- End .row -->
            </div><!-- End .container-fluid -->
        </section>
    <?php } else { ?>
        <section class="uams-module link-list link-list-layout-split bg-auto" id="related-resources" aria-labelledby="related-resources-title">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6 heading">
						<div class="text-container">
                            <h2 class="module-title" id="related-resources-title"><span class="title"><?php echo $resource_heading; ?></span></h2>
						</div>
            		</div>
            		<div class="col-12 col-md-6 list">
						<ul>
                            <?php
                            while ($resource_query->have_posts()) : $resource_query->the_post();
                                $id = get_the_ID();
                                include( UAMS_FAD_PATH . '/templates/loops/resource-list-item.php' );
                            endwhile;
                            wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
    <?php }
?>