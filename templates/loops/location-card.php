<?php 
    /**
     *  Template Name: Location Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     * 
     *  Must be used inside a loop
     *  Required var: $id
     */
?>
<div class="card">
    <?php if ( has_post_thumbnail($id) ) { ?>
    <?php echo get_the_post_thumbnail($id, 'aspect-16-9-small', ['class' => 'card-img-top']); ?>
    <?php } else { ?>
    <picture>
        <source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
        <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" />
    </picture>
    <?php } ?>
    <div class="card-body">
        <h3 class="card-title">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self"><?php echo get_the_title($id); ?></a></span>
        </h3>
        <?php 
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
        
        if ($location_closing_display) { ?>
            <div class="alert alert-warning" role="alert">
                <?php if ($location_closing_date_past) { ?>
                    This location is <?php echo $location_closing_length == 'temporary' ? 'temporarily' : 'permanently' ; ?> closed.
                <?php } else { ?>
                    This location will be closing <?php echo $location_closing_length == 'temporary' ? 'temporarily beginning' : 'permanently' ; ?> on <?php echo $location_closing_date; ?>.
                <?php } // endif
                if (
                    $location_closing_length == 'temporary' 
                    && $location_reopen_known == 'date' 
                    && !empty($location_reopen_date)
                    && (new DateTime($location_reopen_date) >= new DateTime($location_closing_date))
                ) { ?>
                    It is scheduled to reopen on <?php echo $location_reopen_date; ?>.
                <?php } elseif (
                    $location_closing_length == 'temporary' 
                    && $location_reopen_known == 'tbd' 
                ) { ?>
                    It will remain closed until further notice.
                <?php } // endif ?>
            </div>
        <?php } // endif ?>
        <?php $map = get_field('location_map', $id); ?>
        <p class="card-text"><?php echo get_field('location_address_1', $id ); ?><br/>
            <?php echo ( get_field('location_address_2', $id ) ? get_field('location_address_2', $id ) . '<br/>' : ''); ?>
            <?php echo get_field('location_city', $id ); ?>, <?php echo get_field('location_state', $id ); ?> <?php echo get_field('location_zip', $id); ?>
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