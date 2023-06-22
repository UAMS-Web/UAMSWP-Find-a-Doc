<?php 
/**
 * Template Name: Conditions Loop
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required var:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$condition_single_name_attr // Attribute value friendly version of system setting for Conditions single item name
 * 	$condition_plural_name // System setting for Conditions plural item name
 * 	$condition_cpt_query
 * 	$condition_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$condition_heading_related_name
 */

$condition_heading = $condition_plural_name;
$condition_disclaimer = false;
$condition_disclaimer_text = 'UAMS Health ' . strtolower($provider_plural_name) . ' care for a broad range of ' . strtolower($condition_plural_name) . ', some of which may not be listed below.';

if ( $condition_context == 'single-provider' ) {

	$condition_heading = $condition_heading . ' Diagnosed or Treated by ' . $condition_heading_related_name;
	$condition_disclaimer = true;

} elseif ( $condition_context == 'single-location' ) {

	$condition_heading = $condition_heading . ' Diagnosed or Treated at ' . $condition_heading_related_name;
	$condition_disclaimer = true;

} elseif ( $condition_context == 'single-condition' ) {

	$condition_heading = $condition_heading . ' Related to ' . $condition_heading_related_name;

} elseif ( $condition_context == 'single-treatment' ) {

	$condition_heading = 'Related ' . $condition_heading;

} elseif ( $condition_context == 'single-expertise' ) {

	$condition_heading = 'Related ' . $condition_heading;
	$condition_disclaimer = true;

} elseif ( $condition_context == 'single-resource' ) {

	$condition_heading = 'Related ' . $condition_heading;

}
?>
<section class="uams-module conditions-treatments bg-auto" id="conditions">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title"><?php echo $condition_heading; ?></span></h2>
				<?php if ( $condition_disclaimer ) { ?>
					<p class="note"><?php echo $condition_disclaimer_text; ?></p>
				<?php } ?>
				<div class="list-container list-container-rows">
					<ul class="list">
					<?php foreach( $condition_cpt_query->posts as $condition ): ?>
						<li>
							<a href="<?php echo get_the_permalink( $condition->ID ); ?>" aria-label="Go to <?php echo $condition_single_name_attr; ?> page for <?php echo $condition->post_title; ?>" class="btn btn-outline-primary">
								<?php 
									echo $condition->post_title;
								?>
							</a>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>