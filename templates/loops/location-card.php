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

    // Parent Location 
    $location_has_parent = get_field('location_parent', $id);
    $location_parent_id = get_field('location_parent_id', $id);
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
        $parent_url = get_permalink( $parent_id );
        $featured_image = get_the_post_thumbnail($parent_id, 'aspect-16-9-small', ['class' => 'card-img-top']);
        $address_id = $parent_id;

        $override_parent_photo = get_field('location_image_override_parent', $id);
        $override_parent_photo_featured = get_field('location_image_override_parent_featured', $id);
        
        // Set featured image
        if ( $override_parent_photo && $override_parent_photo_featured ) {
            $featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']);
        }
    } else {
        // Set featured image
        if ( has_post_thumbnail($id) ) {
            $featured_image = get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']);
        }
    }
?>
<div class="card">
    <?php if ( $featured_image ) {
        echo $featured_image;
    } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" />
    </picture>
    <?php } ?>
    <div class="card-body">
        <h3 class="card-title h5">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self"><?php echo get_the_title($id); ?></a></span>
            <?php if ( $parent_location ) { ?>
                <span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
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
                $alert_label = 'Learn more about the closure.';
            } elseif ($location_modified_hours_display) {
                $alert_label = 'Learn more about the modified hours.';
            }
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
                <p><a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo $alert_label; ?>" class="alert-link">Learn more</a></p>
            </div>
        <?php } // endif ?>
        <?php $map = get_field('location_map', $address_id); ?>
        <p class="card-text"><?php echo get_field('location_address_1', $address_id ); ?><br/>
            <?php echo ( get_field('location_address_2', $address_id ) ? get_field('location_address_2', $address_id ) . '<br/>' : ''); ?>
            <?php echo get_field('location_city', $address_id ); ?>, <?php echo get_field('location_state', $address_id ); ?> <?php echo get_field('location_zip', $address_id); ?>
        </p>
        <?php if (get_field('location_phone')) { ?>
            <dl>
                <dt>Appointment Phone Number<?php echo get_field('field_location_appointment_phone_query') ? 's' : ''; ?></dt>
                <?php if (get_field('location_new_appointments_phone') && get_field('location_clinic_phone_query')) { ?>
                    <dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone') ); ?></a><?php echo get_field('field_location_appointment_phone_query') ? '<br/><span class="subtitle">New Patients</span>' : '<br/><span class="subtitle">New and Returning Patients</span>'; ?></dd>
                    <?php if (get_field('location_return_appointments_phone') && get_field('field_location_appointment_phone_query')) { ?>
                        <dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone') ); ?></a><br/><span class="subtitle">Returning Patients</span></dd>
                    <?php } ?>
                <?php } else { ?>
                    <dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone') ); ?></a><br/><span class="subtitle">New and Returning Patients</span></dd>
                <?php } ?>
            </dl>
        <?php } ?>
    </div><!-- .card-body -->
    <div class="btn-container">
        <div class="inner-container">
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="Go to location page for <?php echo get_the_title($id); ?>">View Location</a>
            <?php if ($map) { ?>
            <a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get Directions to <?php echo get_the_title($id); ?>">Get Directions</a>
            <?php } ?>
        </div>
    </div>
</div><!-- .card --> 