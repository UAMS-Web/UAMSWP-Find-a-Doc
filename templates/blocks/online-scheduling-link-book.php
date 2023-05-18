<?php
/**
 * Template Name: Online Scheduling, MyChart Open Scheduling Widget Modal Toggles
 * Designed for UAMS Find-a-Doc
 * 
 * Required vars
 * 		$scheduling_mychart_type // Which Type(s) of MyChart Open Scheduling?
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 
 * Required vars from single location template:
 * 		$scheduling_mychart_book_options // MyChart Open Scheduling Widget Option(s) for Appointment Booking
 * 		$scheduling_mychart_type_dropdown // Display the Single Visit Type in a Dropdown?
 * 
 * Required vars from single provider template:
 * 		$scheduling_mychart_book_visit_type // Visit Type(s) from UAMS Health Epic for Appointment Booking
 * 	
 */

// Set the variable that does not exist in the single provider template
if ( $scheduling_template == 'single-provider' ) {
	$scheduling_mychart_type_dropdown = true;
} else {
	$scheduling_mychart_type_dropdown = isset($scheduling_mychart_type_dropdown) ? $scheduling_mychart_type_dropdown : true;
}

if (
	$scheduling_mychart_book_options // MyChart open scheduling widget options for Appointment Booking is not empty
	||
	$scheduling_mychart_book_visit_type // At least one Visit Type has been selected
) {
?>
<div class="dropdown">
	<button class="btn btn-primary dropdown-toggle" type="button" id="mychart_scheduling_dropdown" data-toggle="dropdown" aria-expanded="false">Book an Appointment</button>
	<div class="dropdown-menu" aria-labelledby="mychart_scheduling_dropdown">
		<?php 
		$i = 0;
		// Loop through repeater rows.
		$options = $scheduling_mychart_book_options ?: $scheduling_mychart_book_visit_type;
		foreach( $options as $option ) {
			// Load sub field value.
			$visit_type = $option['location_scheduling_vt'] ?: $option;
			$visit_type_object = get_term_by( 'id', $visit_type, 'mychart_visit_type');
			
			// Do something...
			if ( $visit_type_object ) {
				$visit_type_link_text = get_field('mychart_visit_type_link_text', $visit_type_object);
				?>
				<a class="dropdown-item" href="#" data-toggle="modal" data-target="#mychart-scheduling_<?php echo $i; ?>"><?php echo $visit_type_link_text; ?></a>
			<?php }
			$i++;
		} // end foreach ?>
	</div>
</div>
<?php } // endif ?>