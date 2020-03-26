<?php 
if ( have_posts() ) : 
    // Generate a table
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Location Name</th>
                <th scope="col">Location Phone Number</th>
                <th scope="col">Location Appointment Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php  
                while ( have_posts() ) : the_post();

                $id = get_the_ID();
                // include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
                echo '<tr>';
                    echo '<th scope="row"><a href="' . get_permalink() . '">'. get_the_title() .'</a></th>';
                    if (get_field('location_phone', $id)) { ?>
                        <td>
                            <a href="tel:<?php echo format_phone_dash( get_field('location_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone', $id) ); ?></a>
                        </td>
                    <?php } else { echo '<td>&nbsp;</td>'; } ?>
                    <?php if (get_field('location_new_appointments_phone', $id) && get_field('location_clinic_phone_query', $id)) { ?>
                        <td>
                            <dl>
                                <dt><?php echo get_field('field_location_appointment_phone_query', $id) ? 'New Patients' : 'New and Returning Patients'; ?></dt>
                                <dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone', $id) ); ?></a></dd>
                                <?php if (get_field('location_return_appointments_phone', $id) && get_field('field_location_appointment_phone_query', $id)) { ?>
                                    <dt>Returning Patients</dt>
                                    <dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone', $id) ); ?></a></dd>
                                <?php }
                            echo '</dl>';
                        echo '</td>';
                    } else {
                        echo '<td>&nbsp;</td>';
                    }
                echo '</tr>';

            endwhile; ?>
        </tbody>
    </table>
<?php else : ?>
	<p><?php _e( 'Sorry, no locations matched your criteria.' ); ?></p>
<?php endif; ?>