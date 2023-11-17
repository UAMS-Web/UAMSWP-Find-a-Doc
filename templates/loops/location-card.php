<?php 
    /**
     *  Template Name: Location Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */

    // Reset variables
    $featured_image = '';
    $address_id = $id;

    // $child_location_list indicates whether this is a list of child locations within this location
    // Check if $child_location_list is set. Otherwise create the variable and set its value to false.
    $child_location_list = isset($child_location_list) ? $child_location_list : false;

    $location_title = get_the_title($id);
    $location_title_attr = str_replace('"', '\'', $location_title);
    $location_title_attr = html_entity_decode(str_replace('&nbsp;', ' ', htmlentities($location_title_attr, null, 'utf-8')));

    // Parent Location 
    $location_has_parent = get_field('location_parent', $id);
    $location_parent_id = get_field('location_parent_id', $id);
    $parent_location = ''; // Eliminate PHP errors
    $parent_id = ''; // Eliminate PHP errors
    $parent_title = ''; // Eliminate PHP errors
    $parent_url = ''; // Eliminate PHP errors
    $parent_location_prepend_the = ''; // Eliminate PHP errors
    $parent_title_prepend = ''; // Eliminate PHP errors
    $parent_title_phrase = ''; // Eliminate PHP errors
    $override_parent_photo = ''; // Eliminate PHP errors
    $override_parent_photo_featured = ''; // Eliminate PHP errors

    if ($location_has_parent && $location_parent_id) { 
        $parent_location = get_post( $location_parent_id );
    }
    // Get Post ID for Address & Image fields
    if ($parent_location) {
        $parent_id = $parent_location->ID;
        $parent_title = $parent_location->post_title;
        $parent_title_attr = $parent_title;
        $parent_title_attr = str_replace('"', '\'', $parent_title_attr); // Replace double quotes with single quote
        $parent_title_attr = str_replace('&#8217;', '\'', $parent_title_attr); // Replace right single quote with single quote
        $parent_title_attr = htmlentities($parent_title_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
        $parent_title_attr = str_replace('&nbsp;', ' ', $parent_title_attr); // Convert non-breaking space with normal space
        $parent_title_attr = html_entity_decode($parent_title_attr); // Convert HTML entities to their corresponding characters
        $parent_url = get_permalink( $parent_id );
        $featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
        $address_id = $parent_id;
        $parent_location_prepend_the = get_field('location_prepend_the', $parent_id);
        $parent_title_prepend = $parent_location_prepend_the ? 'the ' : '';
        $parent_title_phrase = $parent_title_prepend . $parent_title;

        $override_parent_photo = get_field('location_image_override_parent', $id);
        $override_parent_photo_featured = get_field('location_image_override_parent_featured', $id);
        
        // Set featured image
        if ( $override_parent_photo && $override_parent_photo_featured ) {
            $featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
        }
    } else {
        // Set featured image
        if ( has_post_thumbnail($id) ) {
            $featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', [ 'class' => 'card-img-top', 'data-categorytitle' => 'Photo', 'data-itemtitle' => $location_title_attr , 'loading' => 'lazy' ]);
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


?>
<div class="card">
    <?php if ( $featured_image ) {
        echo $featured_image;
    } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="<?php echo $location_title_attr; ?>" loading="lazy" />
    </picture>
    <?php } ?>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self" data-categorytitle="Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $location_title; ?></a></span>
            <?php if ( $parent_location && !$child_location_list ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <?php echo $parent_title_prepend; ?><a href="<?php echo $parent_url; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $location_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
            <?php } // endif ?>
            <?php  if ( isset($l) && 1 == $l ) { ?>
                <span class="subtitle"><span class="sr-only">, </span>Primary Location</span>
            <?php } ?>
        </h3>
        <?php 
        // Check for if we should display a closure alert
        
        $location_closing = get_field('location_closing', $id); // true or false
        $location_closing_date = get_field('location_closing_date', $id); // F j, Y
        $location_closing_date_past = false;
        if (new DateTime() >= new DateTime($location_closing_date)) {
            $location_closing_date_past = true;
        }
        $location_closing_length = get_field('location_closing_length', $id);
        $location_reopen_known = get_field('location_reopen_known', $id);
        $location_reopen_date = get_field('location_reopen_date', $id); // F j, Y
        $location_reopen_date_past = false;
        if (new DateTime() >= new DateTime($location_reopen_date)) {
            $location_reopen_date_past = true;
        }
        $location_closing_info = get_field('location_closing_info', $id);
        $location_closing_display = false;
        if (
            $location_closing && (
                $location_closing_length == 'permanent'
                || ($location_closing_length == 'temporary' && !$location_reopen_date_past)
                )
            ) {
            $location_closing_display = true;
        }

        // Check for if we should display a modified hours alert

        $location_hours_group = get_field('location_hours_group', $id);

        $modified = $location_hours_group['location_modified_hours'];
        $modified_start = $location_hours_group['location_modified_hours_start_date'];
        $modified_end = $location_hours_group['location_modified_hours_end'];
        $modified_end_date = $location_hours_group['location_modified_hours_end_date'];

        $today = strtotime("today");
        $today_30 = strtotime("+30 days");

        $telemed_modified = $location_hours_group['location_telemed_modified_hours_query']; // Are there modified hours for telemedicine?
        $telemed_modified_start = $location_hours_group['location_telemed_modified_hours_start_date']; // When do the modified telemedicine hours start?
        $telemed_modified_end = $location_hours_group['location_telemed_modified_hours_end']; // Do we know when the modified telemedicine hours end?
        $telemed_modified_end_date = $location_hours_group['location_telemed_modified_hours_end_date']; // When do the modified telemedicine hours end?

        $telemed_today = $today;
        $telemed_today_30 = $today_30;

        if ( 
            ( $modified && strtotime($modified_start) <= $today_30 && ( strtotime($modified_end_date) >= $today || !$modified_end ) ) ||
            ( $telemed_modified && strtotime($telemed_modified_start) <= $telemed_today_30 && ( strtotime($telemed_modified_end_date) >= $telemed_today || !$telemed_modified_end ) )
        ) {
            $location_modified_hours_display = true;
        } else {
            $location_modified_hours_display = false;
        }

        //// Set start of modified hours based on the earliest of the two (clinic and telemedicine)

        if ($location_modified_hours_display) {
            if ( ($modified && $modified_start) && ($telemed_modified && $telemed_modified_start) ) {
                if ( strtotime($modified_start) <= strtotime($telemed_modified_start) ) {
                    $location_modified_hours_start = $modified_start;
                } else {
                    $location_modified_hours_start = $telemed_modified_start;
                }
            } elseif ($modified_start) {
                $location_modified_hours_start = $modified_start;
            } elseif ($telemed_modified_start) {
                $location_modified_hours_start = $telemed_modified_start;
            }

            if ( strtotime($location_modified_hours_start) <= $today ) {
                $location_modified_hours_date_past = true;
            } else {
                $location_modified_hours_date_past = false;
            }
        }   

        // Create the alert
        
        if ( $location_closing_display || $location_modified_hours_display ) { 
            $alert_label = '';
            if ($location_closing_display) {
                $alert_label = 'Learn more about the closure of ' . $location_title_attr . '.';
            } elseif ($location_modified_hours_display) {
                $alert_label = 'Learn more about the modified hours.';
            }
            $alert_label_attr = $alert_label;
            $alert_label_attr = str_replace('"', '\'', $alert_label_attr); // Replace double quotes with single quote
            $alert_label_attr = str_replace('&#8217;', '\'', $alert_label_attr); // Replace right single quote with single quote
            $alert_label_attr = htmlentities($alert_label_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
            $alert_label_attr = str_replace('&nbsp;', ' ', $alert_label_attr); // Convert non-breaking space with normal space
            $alert_label_attr = html_entity_decode($alert_label_attr); // Convert HTML entities to their corresponding characters
            ?>
            <div class="alert alert-warning" role="alert">
                <?php if ($location_closing_display) {
                    if ($location_closing_date_past) { ?>
                        This location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
                    <?php } else { ?>
                        This location will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
                    <?php } // endif
                } elseif ($location_modified_hours_display) {
                    if ($location_modified_hours_date_past) { ?>
                        This location's hours have been temporarily modified.
                    <?php } else { ?>
                        This location's hours will be temporarily modified beginning on <?php echo $location_modified_hours_start; ?>.
                    <?php } // endif
                } // endif ?>
                <p><a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $alert_label_attr; ?>" class="alert-link" data-categorytitle="Alert" data-itemtitle="<?php echo $location_title_attr; ?>">Learn more</a></p>
            </div>
        <?php } // endif ?>
        <?php $map = get_field('location_map', $address_id); ?>
        <p class="card-text"><?php echo $location_address_1; ?><br/>
            <?php echo $location_address_2 ? $location_address_2 . '<br/>' : ''; ?>
            <?php echo $location_city . ', ' . $location_state . ' ' . $location_zip; ?>
        </p>
        <?php 
        // Phone values
        $phone_output_id = $id;
        $phone_output = 'associated_locations';
        include( UAMS_FAD_PATH . '/templates/blocks/locations-phone.php' );
        ?>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="View location page for <?php echo $location_title_attr; ?>" data-categorytitle="View Location" data-itemtitle="<?php echo $location_title_attr; ?>">View Location</a>
            <?php if ($map) { ?>
            <a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get Directions to <?php echo $location_title; ?>" data-categorytitle="Get Directions" data-itemtitle="<?php echo $location_title_attr; ?>">Get Directions</a>
            <?php } ?>
        </div>
    </div>
</div><!-- .card --> 