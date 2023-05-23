<?php
/**
 * 	Template Name: Online Scheduling Information
 * 	Designed for UAMS Find-a-Doc
 * 	
 * 	Required vars:
 * 		$show_scheduling_mychart_book_section
 * 		$show_scheduling_request_section
 * 		$show_scheduling_mychart_preregister_section
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 		$page_slug
 * 	
 * 	Required vars from single location template:
 * 		$parent_slug
 * 	
 * 	Required vars from single provider template:
 * 		
 * 	
 * 	Optional vars from single location template:
 * 		
 * 	
 * 	Optional vars from single provider template:
 * 	
 */

// Check variables
$parent_slug = isset($parent_slug) ? $parent_slug : '';


// Begin Appointments Section

$scheduling_appointments_count = $show_scheduling_mychart_book_section + $show_scheduling_request_section; // Count number of appointment subsections

$scheduling_heading_system = get_field('scheduling_heading_system', 'option'); // Heading for Online Scheduling Section

if ( $scheduling_appointments_count > 1 ) { // If there are multiple Appointment subsections 
	// Display the general Appointments heading ?>
	<h2 class="h4"><?php echo $scheduling_heading_system; ?></h2>
<?php }


// Begin Appointment Booking Subsection

include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-book.php' );

// End Appointment Booking Subsection


// Begin Appointment Request Subsection

include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-request.php' );

// End Appointment Request Subsection

// End Appointments Section


// Begin Visit Pre-Registration Section

if ( $show_scheduling_mychart_preregister_section ) { // $show_scheduling_mychart_preregister_section is defined in /templates/blocks/online-scheduling-check.php
	$scheduling_mychart_preregister_group_sys = get_field('mychart_scheduling_preregister_group', 'option'); // ACF field containing the inputs relevant to Visit Pre-Registration
	$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_heading_system'] ?: 'Immediate Care';
	$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_descr_system'] ?: 'Spend less time waiting and get home faster by choosing an available time.';
	?>
	<h2 class="h4"><?php echo $scheduling_mychart_preregister_heading_standalone; ?></h2>
	<p><?php echo $scheduling_mychart_preregister_descr_standalone; ?></p>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister-button.php' ); ?>
		</div>
	</div>
<?php } // endif ( $show_scheduling_mychart_preregister_section )

// End Visit Pre-Registration Section

?>