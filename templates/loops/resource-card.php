<?php 
    /**
     *  Template Name: Clinical Resource Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var:
     *      $id
     *  Optional var:
     *      $resource_page = 'single' or 'archive' (default to 'single')
     */

    $resource_page = $resource_page ? $resource_page : 'single';

    $resource_title = get_the_title($id);
    $resource_title_attr = str_replace('"', '\'', $resource_title);
    $resource_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($resource_title_attr, null, 'utf-8')));

    $resource_label = 'Go to the Clinical Resource page for' . $resource_title_attr;

    $resource_type = get_field('clinical_resource_type', $id);
    $resource_type_value = $resource_type['value'];
    $resource_type_label = $resource_type['label'];
    
    $resource_excerpt = get_the_excerpt($id) ? get_the_excerpt($id) : wp_strip_all_tags( get_the_content($id) );
    $resource_excerpt_len = strlen($resource_excerpt);
    if ( $resource_excerpt_len > 160 ) {
        $resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
    }

    $resource_related_max = 3; // Set how many of each related item type to display
    

    // Check for valid providers
    $providers = get_field('clinical_resource_providers');
    $provider_valid = false;
    foreach( $providers as $provider ) {
        if ( get_post_status ( $provider ) == 'publish' ) {
            $provider_valid = true;
            $break;
        } // endif
    } // endforeach;
    $provider = '';
    // Count published providers
    $provider_count = 0;
    foreach( $providers as $provider) {
        if ( get_post_status ( $provider ) == 'publish' ) {
            $provider_count++;
        } // endif
    } // endforeach;
    $provider = '';
    $provider_label = $provider_count > 1 ? 'Providers' : 'Provider';
    

    // Check for valid locations
    $locations = get_field('clinical_resource_locations');
    $location_valid = false;
    foreach( $locations as $location ) {
        if ( get_post_status ( $location ) == 'publish' ) {
            $location_valid = true;
            $break;
        } // endif
    } // endforeach;
    $location = '';
    // Count published locations
    $location_count = 0;
    foreach( $locations as $location) {
        if ( get_post_status ( $location ) == 'publish' ) {
            $location_count++;
        } // endif
    } // endforeach;
    $location = '';
    $location_label = $location_count > 1 ? 'Locations' : 'Location';
    

    // Check for valid conditions
    $conditions = get_field('clinical_resource_conditions');
    $condition_valid = false;
    foreach( $conditions as $condition ) {
        if ( get_post_status ( $condition ) == 'publish' ) {
            $condition_valid = true;
            $break;
        } // endif
    } // endforeach;
    $condition = '';
    // Count published conditions
    $condition_count = 0;
    foreach( $conditions as $condition) {
        if ( get_post_status ( $condition ) == 'publish' ) {
            $condition_count++;
        } // endif
    } // endforeach;
    $condition = '';
    $condition_label = $condition_count > 1 ? 'Conditions' : 'Condition';
    

    // Check for valid treatments
    $treatments = get_field('clinical_resource_treatments');
    $treatment_valid = false;
    foreach( $treatments as $treatment ) {
        if ( get_post_status ( $treatment ) == 'publish' ) {
            $treatment_valid = true;
            $break;
        } // endif
    } // endforeach;
    $treatment = '';
    // Count published treatments
    $treatment_count = 0;
    foreach( $treatments as $treatment) {
        if ( get_post_status ( $treatment ) == 'publish' ) {
            $treatment_count++;
        } // endif
    } // endforeach;
    $treatment = '';
    $treatment_label = $treatment_count > 1 ? 'Treatments/Procedures' : 'Treatment/Procedure';
    

    // Check for valid areas of expertise
    $expertises = get_field('clinical_resource_aoe');
    $expertise_valid = false;
    foreach( $expertises as $expertise ) {
        if ( get_post_status ( $expertise ) == 'publish' ) {
            $expertise_valid = true;
            $break;
        } // endif
    } // endforeach;
    $expertise = '';
    // Count areas of expertise
    $expertise_count = 0;
    foreach( $expertises as $expertise) {
        if ( get_post_status ( $expertise ) == 'publish' ) {
            $expertise_count++;
        } // endif
    } // endforeach;
    $expertise = '';
    $expertise_label = $expertise_count > 1 ? 'Areas of Expertise' : 'Area of Expertise';


    if ( $resource_page == 'archive' ) { ?>
        <div class="col item-container">
            <div class="item">
                <div class="row">
                    <div class="col image">
                        <a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $resource_title_attr; ?>">
                            <picture>
                                <?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 243, 'center', 'center'); ?>"
                                        media="(min-width: 2054px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 184, 'center', 'center'); ?>"
                                        media="(min-width: 1784px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 243, 243, 'center', 'center'); ?>"
                                        media="(min-width: 1200px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 184, 184, 'center', 'center'); ?>"
                                        media="(min-width: 930px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 580, 326, 'center', 'center'); ?>"
                                        media="(min-width: 768px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 95, 95, 'center', 'center'); ?>"
                                        media="(min-width: 576px)">
                                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 510, 286, 'center', 'center'); ?>"
                                        media="(min-width: 1px)">
                                    <img src="<?php echo image_sizer(get_post_thumbnail_id(), 510, 286, 'center', 'center'); ?>" alt="" role="presentation" />
                                <?php } elseif ( has_post_thumbnail() ) { ?>
                                    <?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?>
                                <?php } else { ?>
                                    <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
                                    <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" />
                                <?php } ?>
                            </picture>
                        </a>
                    </div>
                    <div class="col text">
                        <div class="row">
                            <div class="col-12 primary">
                                <h3 class="h4">
                                    <a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $resource_title_attr; ?>">
                                        <span class="name"><?php echo $resource_title; ?></span>
                                    </a>
                                    <span class="subtitle"><?php echo esc_html($resource_type_label); ?></span>
                                </h3>
                                <p><?php echo $resource_excerpt; ?></p>
                                <a class="btn btn-primary" href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $resource_title_attr; ?>">View Clinical Resource</a>
                            </div>
                            <div class="col-12 secondary">
								<h4 class="h5">Related Content</h4>
                                <dl>
                                    <?php if( $providers && $provider_valid ) { ?>
                                        <dt><?php echo $provider_label; ?></dt>
                                        <dd>
                                            <?php
                                            $resource_i = 0;
                                            $resource_count = $provider_count;
                                            $associates = $providers;
                                            foreach( $associates as $associate) {
                                                $provider_prefix = get_field('physician_prefix', $associate);
                                                $provider_first_name = get_field('physician_first_name', $associate);
                                                $provider_middle_name = get_field('physician_middle_name', $associate);
                                                $provider_last_name = get_field('physician_last_name', $associate);
                                                $provider_pedigree = get_field('physician_pedigree', $associate);
                                                $provider_medium_name = ($provider_prefix ? $provider_prefix .' ' : '') . $provider_first_name .' ' .($provider_middle_name ? $provider_middle_name . ' ' : '') . $provider_last_name . ($provider_pedigree ? ' ' . $provider_pedigree : '');

                                                if ( get_post_status ( $associate ) == 'publish' ) {
                                                    if ( $resource_i < $resource_related_max ) {
                                                        $associate_title = $provider_medium_name;
                                                        $associate_title_attr = str_replace('"', '\'', $associate_title);
                                                        $associate_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($associate_title_attr, null, 'utf-8')));
        
                                                        echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
                                                        $resource_i++;
                                                        if ( 
                                                            ( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
                                                            || ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
                                                         ) {
                                                            // If there are more items than the max AND this is not the max item
                                                            // OR If there are as many or fewer items than the max AND this is not the penultimate item
                                                            echo ', ';
                                                        } elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {
                                                            // If there are as many or fewer items than the max AND this is the penultimate item
                                                            echo ' and ';
                                                        }
                                                    } elseif ( $resource_i == $resource_related_max ) {
                                                        echo ' and more';
                                                        break;
                                                    }
                                                }
                                            } // endforeach; ?>
                                        </dd>
                                    <?php } // endif
                                        $resource_i = 0;
                                        $resource_count = '';
                                        $associates = '';
                                        $associates = '';
                                    ?> 
                                    <?php if( $locations && $location_valid ) { ?>
                                        <dt><?php echo $location_label; ?></dt>
                                        <dd>
                                            <?php
                                            $resource_i = 0;
                                            $resource_count = $location_count;
                                            $associates = $locations;
                                            foreach( $associates as $associate) {
                                                if ( get_post_status ( $associate ) == 'publish' ) {
                                                    if ( $resource_i < $resource_related_max ) {
                                                        $associate_title = get_the_title( $associate );
                                                        $associate_title_attr = str_replace('"', '\'', $associate_title);
                                                        $associate_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($associate_title_attr, null, 'utf-8')));
        
                                                        echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
                                                        $resource_i++;
                                                        if ( 
                                                            ( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
                                                            || ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
                                                         ) {
                                                            // If there are more items than the max AND this is not the max item
                                                            // OR If there are as many or fewer items than the max AND this is not the penultimate item
                                                            echo ', ';
                                                        } elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {
                                                            // If there are as many or fewer items than the max AND this is the penultimate item
                                                            echo ' and ';
                                                        }
                                                    } elseif ( $resource_i == $resource_related_max ) {
                                                        echo ' and more';
                                                        break;
                                                    }
                                                }
                                            } // endforeach; ?>
                                        </dd>
                                    <?php } // endif
                                        $resource_i = 0;
                                        $resource_count = '';
                                        $associates = '';
                                        $associates = '';
                                    ?> 
                                    <?php if( $conditions && $condition_valid ) { ?>
                                        <dt><?php echo $condition_label; ?></dt>
                                        <dd>
                                            <?php
                                            $resource_i = 0;
                                            $resource_count = $condition_count;
                                            $associates = $conditions;
                                            foreach( $associates as $associate) {
                                                if ( get_post_status ( $associate ) == 'publish' ) {
                                                    if ( $resource_i < $resource_related_max ) {
                                                        $associate_title = get_the_title( $associate );
                                                        $associate_title_attr = str_replace('"', '\'', $associate_title);
                                                        $associate_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($associate_title_attr, null, 'utf-8')));
        
                                                        echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
                                                        $resource_i++;
                                                        if ( 
                                                            ( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
                                                            || ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
                                                         ) {
                                                            // If there are more items than the max AND this is not the max item
                                                            // OR If there are as many or fewer items than the max AND this is not the penultimate item
                                                            echo ', ';
                                                        } elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {
                                                            // If there are as many or fewer items than the max AND this is the penultimate item
                                                            echo ' and ';
                                                        }
                                                    } elseif ( $resource_i == $resource_related_max ) {
                                                        echo ' and more';
                                                        break;
                                                    }
                                                }
                                            } // endforeach; ?>
                                        </dd>
                                    <?php } // endif
                                        $resource_i = 0;
                                        $resource_count = '';
                                        $associates = '';
                                        $associates = '';
                                    ?> 
                                    <?php if( $treatments && $treatment_valid ) { ?>
                                        <dt><?php echo $treatment_label; ?></dt>
                                        <dd>
                                            <?php
                                            $resource_i = 0;
                                            $resource_count = $treatment_count;
                                            $associates = $treatments;
                                            foreach( $associates as $associate) {
                                                if ( get_post_status ( $associate ) == 'publish' ) {
                                                    if ( $resource_i < $resource_related_max ) {
                                                        $associate_title = get_the_title( $associate );
                                                        $associate_title_attr = str_replace('"', '\'', $associate_title);
                                                        $associate_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($associate_title_attr, null, 'utf-8')));
        
                                                        echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
                                                        $resource_i++;
                                                        if ( 
                                                            ( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
                                                            || ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
                                                         ) {
                                                            // If there are more items than the max AND this is not the max item
                                                            // OR If there are as many or fewer items than the max AND this is not the penultimate item
                                                            echo ', ';
                                                        } elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {
                                                            // If there are as many or fewer items than the max AND this is the penultimate item
                                                            echo ' and ';
                                                        }
                                                    } elseif ( $resource_i == $resource_related_max ) {
                                                        echo ' and more';
                                                        break;
                                                    }
                                                }
                                            } // endforeach; ?>
                                        </dd>
                                    <?php } // endif
                                        $resource_i = 0;
                                        $resource_count = '';
                                        $associates = '';
                                        $associates = '';
                                    ?> 
                                    <?php if( $expertises && $expertise_valid ) { ?>
                                        <dt><?php echo $expertise_label; ?></dt>
                                        <dd>
                                            <?php
                                            $resource_i = 0;
                                            $resource_count = $expertise_count;
                                            $associates = $expertises;
                                            foreach( $associates as $associate) {
                                                if ( get_post_status ( $associate ) == 'publish' ) {
                                                    if ( $resource_i < $resource_related_max ) {
                                                        $associate_title = get_the_title( $associate );
                                                        $associate_title_attr = str_replace('"', '\'', $associate_title);
                                                        $associate_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($associate_title_attr, null, 'utf-8')));
        
                                                        echo '<a href="' . get_permalink( $associate ) . '" data-categorytitle="Related Location" data-typetitle="' . $associate_title_attr . '" data-itemtitle="' . $resource_title_attr . '">' . $associate_title . '</a>';
                                                        $resource_i++;
                                                        if ( 
                                                            ( $resource_count > $resource_related_max && $resource_i != $resource_related_max )
                                                            || ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i < ($resource_count - 1) )
                                                         ) {
                                                            // If there are more items than the max AND this is not the max item
                                                            // OR If there are as many or fewer items than the max AND this is not the penultimate item
                                                            echo ', ';
                                                        } elseif ( $resource_count > 1 && $resource_count <= $resource_related_max && $resource_i == ($resource_count - 1) ) {
                                                            // If there are as many or fewer items than the max AND this is the penultimate item
                                                            echo ' and ';
                                                        }
                                                    } elseif ( $resource_i == $resource_related_max ) {
                                                        echo ' and more';
                                                        break;
                                                    }
                                                }
                                            } // endforeach; ?>
                                        </dd>
                                    <?php } // endif
                                        $resource_i = 0;
                                        $resource_count = '';
                                        $associates = '';
                                        $associates = '';
                                    ?> 
                                </dl>
								<a class="btn btn-primary" href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $resource_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $resource_title_attr; ?>">View Clinical Resource</a>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-12 col-sm-6 col-xl-3 item">
            <div class="card">
                <div class="card-img-top">
                    <?php if ( has_post_thumbnail($id) ) { ?>
                        <?php echo get_the_post_thumbnail($id, 'aspect-16-9-small'); ?>
                    <?php } else { ?>
                        <picture>
                            <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
                            <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" />
                        </picture>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <h3 class="card-title h5"><?php echo $resource_title; ?></h3>
                    <p class="card-text"><?php echo $resource_excerpt; ?></p>
                    <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary stretched-link" aria-label="<?php echo $resource_label; ?>" data-itemtitle="<?php echo $resource_title_attr; ?>">View Clinical Resource</a>
                </div>
            </div>
        </div>
    <?php }
?>
