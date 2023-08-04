<?php 
/**
 * Template Name: Locations List Loop for FacetWP Listing
 * 
 * Description: A template part that displays a table of locations and their 
 * contact information using a while loop.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * FacetWP Listing Name and Slug:
 * 	Location List	('location_list')
 */

// Get system settings for location labels
$labels_location_vars = uamswp_fad_labels_location();
	$location_single_name = $labels_location_vars['location_single_name']; // string
	$location_single_name_attr = $labels_location_vars['location_single_name_attr']; // string
	$location_plural_name = $labels_location_vars['location_plural_name']; // string
	$location_plural_name_attr = $labels_location_vars['location_plural_name_attr']; // string
	$placeholder_location_single_name = $labels_location_vars['placeholder_location_single_name']; // string
	$placeholder_location_plural_name = $labels_location_vars['placeholder_location_plural_name']; // string
	$placeholder_location_page_title = $labels_location_vars['placeholder_location_page_title']; // string
	$placeholder_location_page_title_phrase = $labels_location_vars['placeholder_location_page_title_phrase']; // string

if ( have_posts() ) { 
	// Generate a table
	?>
	<table class="table table-striped bg-white">
		<thead>
			<tr>
				<th scope="col"><?php echo $location_single_name; ?> Name</th>
				<th scope="col"><?php echo $location_single_name; ?> Phone Number</th>
				<th scope="col"><?php echo $location_single_name; ?> Appointment Phone Number</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while ( have_posts() ) : the_post();

				$page_id = get_the_ID();
				// include( UAMS_FAD_PATH . '/templates/loops/location-card.php' );
				?>
				<tr>
					<th scope="row"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></th>
					<td>
						<?php if (get_field('location_phone', $page_id)) { ?>
							<a href="tel:<?php echo format_phone_dash( get_field('location_phone', $page_id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone', $page_id) ); ?></a>
						<?php } else { ?>
							&nbsp;
						<?php } ?>
					</td>
					<td>
						<?php if (get_field('location_new_appointments_phone', $page_id) && get_field('location_clinic_phone_query', $page_id)) { ?>
							<dl>
								<dt><?php echo get_field('field_location_appointment_phone_query', $page_id) ? 'New Patients' : 'New and Returning Patients'; ?></dt>
								<dd><a href="tel:<?php echo format_phone_dash( get_field('location_new_appointments_phone', $page_id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_new_appointments_phone', $page_id) ); ?></a></dd>
								<?php if (get_field('location_return_appointments_phone', $page_id) && get_field('field_location_appointment_phone_query', $page_id)) { ?>
									<dt>Returning Patients</dt>
									<dd><a href="tel:<?php echo format_phone_dash( get_field('location_return_appointments_phone', $page_id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_return_appointments_phone', $page_id) ); ?></a></dd>
								<?php } ?>
							</dl>
						<?php } elseif (get_field('location_phone', $page_id)) { ?>
							<dl>
								<dt>New and Returning Patients</dt>
								<dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone', $page_id) ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone', $page_id) ); ?></a></dd>
							</dl>
						<?php } else { ?>
							&nbsp;
						<?php } ?>
					</td>
				</tr>

			<?php endwhile; ?>
		</tbody>
	</table>
<?php } else { ?>
	<p><?php _e( 'Sorry, no ' . strtolower($location_plural_name) . ' matched your criteria.' ); ?></p>
<?php } //endif ?>