<?php 
/**
 * Template Name: Treatments Loop
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required vars:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$treatments_plural_name // System setting for Treatments plural item name
 * 	$treatments_cpt_query
 * 	$treatment_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 
 * Optional vars:
 * 	$treatment_heading
 * 	$treatment_heading_related_name
 */

// Treatment heading: Set it if it does not already exist
if ( !isset($treatment_heading) || empty($treatment_heading) ) {
	if ( $treatment_context == 'single-provider' ) {
		$treatment_heading = $treatment_heading_related_name ? $treatments_plural_name . ' Performed or Prescribed by ' . $treatment_heading_related_name : $treatments_plural_name;
	} elseif ( $treatment_context == 'single-location' ) {
		$treatment_heading = $treatment_heading_related_name ? $treatments_plural_name . ' Performed or Prescribed at ' . $treatment_heading_related_name : $treatments_plural_name;
	} elseif ( $treatment_context == 'single-condition' ) {
		$treatment_heading = $treatment_heading_related_name ? $treatments_plural_name . ' Related to ' . $treatment_heading_related_name : $treatments_plural_name;
	} elseif (
		$treatment_context == 'single-treatment'
		||
		$treatment_context == 'single-expertise'
		||
		$treatment_context == 'single-resource'
	) {
		$treatment_heading = 'Related ' . $treatments_plural_name;
	} else {
		$treatment_heading = $treatments_plural_name;
	}
}

// Treatment disclaimer
if (
	$condition_context == 'single-provider'
	||
	$condition_context == 'single-location'
	||
	$condition_context == 'single-expertise'
	||
	$condition_context == 'single-treatment'
) {
	$treatment_disclaimer = 'UAMS Health ' . strtolower($provider_plural_name) . ' perform and prescribe a broad range of ' . strtolower($treatments_plural_name) . ', some of which may not be listed below.';
} else {
	$treatment_disclaimer = '';
}
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title"><?php echo $treatment_heading; ?></span></h2>
				<?php if ( $treatment_disclaimer ) { ?>
					<p class="note"><?php echo $treatment_disclaimer; ?></p>
				<?php } ?>
				<div class="">
					<ul class="list" style="column-count:3; list-style:none;">
					<?php foreach( $treatments_cpt_query->posts as $treatment ): ?>
						<li>
							<?php echo $treatment->post_title; ?>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>