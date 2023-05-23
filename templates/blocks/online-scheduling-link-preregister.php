<?php
/**
 * 	Template Name: Online Scheduling Information, Visit Pre-Registration
 * 	Designed for UAMS Find-a-Doc
 * 
 * 	Required vars:
 * 		$show_scheduling_mychart_preregister_section
 * 		$scheduling_mychart_preregister_group_sys // ACF field containing the inputs relevant to Visit Pre-Registration
 * 		$scheduling_template
 * 			(
 * 				'single-location'
 * 				'single-provider'
 * 			)
 * 	
 * 	Required vars from single location template:
 * 		$scheduling_mychart_preregister_options // MyChart Open Scheduling Widget Option(s) for Visit Pre-Registration
 * 	
 * 	Required vars from single provider template:
 * 		$scheduling_mychart_preregister_visit_type
 */

 if ( $show_scheduling_mychart_preregister_section ) {

	// Get values from from Find-a-Doc Settings for Visit Pre-Registration
	$scheduling_mychart_preregister_group_sys = get_field('mychart_scheduling_preregister_group', 'option'); // ACF field containing the inputs relevant to Visit Pre-Registration
	if ( $scheduling_template == 'single-location' ) {
		$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_heading_standalone_system'];
		$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_descr_standalone_system'];
		$scheduling_mychart_preregister_heading_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_heading_nested_system'];
		$scheduling_mychart_preregister_descr_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_location_descr_nested_system'];
	} elseif ( $scheduling_template == 'single-provider' ) {
		$scheduling_mychart_preregister_heading_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_heading_standalone_system'];
		$scheduling_mychart_preregister_descr_standalone = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_descr_standalone_system'];
		$scheduling_mychart_preregister_heading_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_heading_nested_system'];
		$scheduling_mychart_preregister_descr_nested = $scheduling_mychart_preregister_group_sys['mychart_scheduling_preregister_provider_descr_nested_system'];
	}
	$scheduling_mychart_preregister_heading_standalone = isset($scheduling_mychart_preregister_heading_standalone) ? $scheduling_mychart_preregister_heading_standalone : 'Immediate Care';
	$scheduling_mychart_preregister_descr_standalone = isset($scheduling_mychart_preregister_descr_standalone) ? $scheduling_mychart_preregister_descr_standalone : 'Spend less time waiting and get home faster by choosing an available time.';
	$scheduling_mychart_preregister_heading_nested = isset($scheduling_mychart_preregister_heading_nested) ? $scheduling_mychart_preregister_heading_nested : $scheduling_mychart_preregister_heading_standalone;
	$scheduling_mychart_preregister_descr_nested = isset($scheduling_mychart_preregister_descr_nested) ? $scheduling_mychart_preregister_descr_nested : $scheduling_mychart_preregister_descr_standalone;

	// Begin Content
	?>
	<h2 class="h4"><?php echo $scheduling_mychart_preregister_heading_standalone; ?></h2>
	<p><?php echo $scheduling_mychart_preregister_descr_standalone; ?></p>
	<div class="btn-container">
		<div class="inner-container">
			<?php include( UAMS_FAD_PATH . '/templates/blocks/online-scheduling-link-preregister-button.php' ); ?>
		</div>
	</div>
<?php } // endif ( $show_scheduling_mychart_preregister_section )

?>