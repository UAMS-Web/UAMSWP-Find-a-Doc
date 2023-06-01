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
 * 	$treatments
 * 	$treatments_cpt_query
 * 	$treatment_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$treatment_heading_related_name
 * 	$treatment_disclaimer = 'true', 'false'
 */

$treatment_heading = $treatments_plural_name;
$treatment_disclaimer = false;
$treatment_disclaimer_text = 'UAMS Health ' . strtolower($provider_plural_name) . ' perform and prescribe a broad range of ' . strtolower($treatments_plural_name) . ', some of which may not be listed below.';

if ( $treatment_context == 'single-provider' ) {
	$treatment_heading = $treatment_heading . ' Performed or Prescribed by ' . $treatment_heading_related_name;
	$treatment_disclaimer = true;
} elseif ( $treatment_context == 'single-location' ) {
	$treatment_heading = $treatment_heading . ' Performed or Prescribed at ' . $treatment_heading_related_name;
	$treatment_disclaimer = true;
} elseif ( $treatment_context == 'single-condition' ) {
	$treatment_heading = $treatment_heading . ' Related to ' . $treatment_heading_related_name;
} elseif ( $treatment_context == 'single-treatment' ) {
	$treatment_heading = 'Related ' . $treatment_heading;
	$treatment_disclaimer = true;
} elseif ( $treatment_context == 'single-expertise' ) {
	$treatment_heading = 'Related ' . $treatment_heading;
	$treatment_disclaimer = true;
} elseif ( $treatment_context == 'single-resource' ) {
	$treatment_heading = 'Related ' . $treatment_heading;
}
?>
<section class="uams-module conditions-treatments bg-auto" id="treatments">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title"><?php echo $treatment_heading; ?></span></h2>
				<?php if ( $treatment_disclaimer ) { ?>
					<p class="note"><?php echo $treatment_disclaimer_text; ?></p>
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