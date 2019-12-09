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
    <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" alt="" class="card-image-top" />
    <?php } ?>
    <div class="card-body">
        <h3 class="card-title">
            <span class="name"><a href="<?php echo get_permalink($id); ?>" target="_self"><?php echo get_the_title($id); ?></a></span>
        </h3>
        <?php $map = get_field('location_map', $id); ?>
        <p class="card-text"><?php echo get_field('location_address_1', $id ); ?><br/>
            <?php echo ( get_field('location_address_2', $id ) ? get_field('location_address_2', $id ) . '<br/>' : ''); ?>
            <?php echo get_field('location_city', $id ); ?>, <?php echo get_field('location_state', $id ); ?> <?php echo get_field('location_zip', $id); ?></p>
            <?php if (get_field('location_phone')) { ?>
            <dt>Clinic Phone Number</dt>
            <dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone') ); ?></a></dd>
            <?php } ?>
            <?php if (get_field('location_new_appointments_phone')) { ?>
            <dt>Appointments Phone Number<?php echo get_field('field_location_appointment_phone_query') ? 's' : ''; ?></dt>
            <dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone') ); ?></a><?php echo get_field('field_location_appointment_phone_query') ? ' (New Patients)' : ''; ?></dd>
            <?php if (get_field('location_return_appointments_phone')) { ?>
            <dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone') ); ?></a> (Returning Patients)</dd>
            <?php } } ?>
            <a href="<?php echo get_permalink($id); ?>" class="btn btn-primary" aria-label="Go to location page for <?php echo get_the_title($id); ?>">View Location</a>
            <?php if ($map) { ?>
            <a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank" aria-label="Get Directions to <?php echo get_the_title($id); ?>">Get Directions</a>
            <?php } ?>
        </p>
    </div><!-- .card-body -->
</div><!-- .card --> 