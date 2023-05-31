<?php
/**
 * Template Name: Appointments loop / text block
 * Designed for UAMS Find-a-Doc
 */

if ( $locations && $location_valid ) {
	$appointment_location_url = '#locations';
	// $appointment_location_label = 'Go to the list of relevant locations';
} else {
	$appointment_location_url = '/location/';
	// $appointment_location_label = 'View a list of UAMS Health locations';
}

?>
<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title">Make an Appointment</span></h2>
				<p>Request an appointment by <a href="<?php echo $appointment_location_url; ?>" data-itemtitle="Contact a clinic directly">contacting a <?php echo strtolower($location_single_name); ?> directly</a> or by calling the UAMS&nbsp;Health appointment line at <a href="tel:501-686-8000" class="no-break" data-itemtitle="Call the UAMS Health appointment line">(501) 686-8000</a>.</p>
			</div>
		</div>
	</div>
</section>