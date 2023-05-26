<?php 
/**
 * Template Name: Conditions Loop
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required var:
 * 	$conditions
 * 	$conditions_cpt_query
 * 	$condition_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$condition_heading_related_name
 * 	$condition_disclaimer = 'true', 'false'
 */

	$condition_heading = 'Conditions';
	$condition_disclaimer = false;
	$condition_disclaimer_text = 'UAMS Health providers care for a broad range of conditions, some of which may not be listed below.';

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