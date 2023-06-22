<?php 
/**
 * Template Name: Treatments Loop
 * 
 * Description: A template part that displays a section with a list of treatments 
 * associated with the current page using a foreach loop.
 * 
 * This template part uses the treatment custom post type rather than the taxonomy.
 * 
 * This template part lists the treatments with links.
 * 
 * Must be used inside a loop
 * 
 * Required var:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$treatment_single_name_attr // Attribute value friendly version of system setting for Treatments single item name
 * 	$treatment_plural_name // System setting for Treatments plural item name
 * 	$treatment_cpt_query
 * 	$treatment_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$treatment_heading_related_name
 */

$treatment_heading = $treatment_plural_name;
$treatment_disclaimer = false;
$treatment_disclaimer_text = 'UAMS Health ' . strtolower($provider_plural_name) . ' perform and prescribe a broad range of ' . strtolower($treatment_plural_name) . ', some of which may not be listed below.';

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
				<div class="list-container list-container-rows">
					<ul class="list">
					<?php foreach( $treatment_cpt_query->posts as $treatment ): ?>
						<li>
							<a href="<?php echo get_the_permalink( $treatment->ID ); ?>" aria-label="Go to <?php echo $treatment_single_name_attr; ?> page for <?php echo $treatment->post_title; ?>" class="btn btn-outline-primary"><?php echo $treatment->post_title; ?></a>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>