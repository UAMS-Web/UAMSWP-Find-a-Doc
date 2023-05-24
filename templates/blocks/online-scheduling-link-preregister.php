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