<?php 
/**
 * Template Name: Conditions Loop
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required vars:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$conditions_plural_name // System setting for Conditions plural item name
 * 	$conditions_cpt_query
 * 	$condition_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$condition_heading_related_name
 */

	$condition_heading = $conditions_plural_name;
	$condition_disclaimer = false;
	$condition_disclaimer_text = 'UAMS Health ' . strtolower($provider_plural_name) . ' care for a broad range of ' . strtolower($conditions_plural_name) . ', some of which may not be listed below.';

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
				<div class="">
					<ul class="list" style="column-count:3; list-style:none;">
					<?php foreach( $conditions_cpt_query->posts as $condition ): ?>
						<li>
								<?php 
									echo $condition->post_title;
								?>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>