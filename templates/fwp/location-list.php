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
echo '<th scope="row">'. get_the_title() .'</th>';
if (get_field('location_phone', $id)) { ?>
    <td><dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone', $id) ); ?></a></dd></td>
<?php } else { echo '<td>&nbsp;</td>'; } ?>
<?php if (get_field('location_new_appointments_phone', $id) && get_field('location_clinic_phone_query', $id)) { ?>
        <td><dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone', $id) ); ?></a><?php echo get_field('field_location_appointment_phone_query', $id) ? '<br/><span class="subtitle">New Patients</span>' : '<br/><span class="subtitle">New and Returning Patients</span>'; ?></dd>
<?php if (get_field('location_return_appointments_phone', $id) && get_field('field_location_appointment_phone_query', $id)) { ?>
        <dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone', $id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone', $id) ); ?></a><br/><span class="subtitle">Returning Patients</span></dd>
    <?php }
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