<?php
/**
 * Template Name: Conditions Loop
 *
 * Description: A template part that displays a section with a list of conditions
 * associated with the current page using a foreach loop.
 *
 * This template part uses the condition custom post type rather than the taxonomy.
 *
 * This template part lists the conditions without links.
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$condition_plural_name // System setting for Conditions plural item name
 * 	$condition_cpt_query
 * 	$condition_context = 'single-provider', 'single-location', 'single-condition', 'single-treatment', 'single-expertise', 'single-resource'
 * 	$condition_intro // Intro text
 *
 * Optional vars:
 * 	$condition_heading
 * 	$condition_heading_related_name
 */

// Condition heading: Set it if it does not already exist
if ( !isset($condition_heading) || empty($condition_heading) ) {
	if ( $condition_context == 'single-provider' ) {
		$condition_heading = $condition_heading_related_name ? $condition_plural_name . ' Diagnosed or Treated by ' . $condition_heading_related_name : $condition_plural_name;
	} elseif ( $condition_context == 'single-location' ) {
		$condition_heading = $condition_heading_related_name ? $condition_plural_name . ' Diagnosed or Treated at ' . $condition_heading_related_name : $condition_plural_name;
	} elseif ( $condition_context == 'single-condition' ) {
		$condition_heading = $condition_heading_related_name ? $condition_plural_name . ' Related to ' . $condition_heading_related_name : $condition_plural_name;
	} elseif (
		$condition_context == 'single-treatment'
		||
		$condition_context == 'single-expertise'
		||
		$condition_context == 'single-resource'
	) {
		$condition_heading = 'Related ' . $condition_plural_name;
	} else {
		$condition_heading = $condition_plural_name;
	}
}

// Condition disclaimer
if (
	empty($condition_intro)
	&&
	(
		$condition_context == 'single-provider'
		||
		$condition_context == 'single-location'
		||
		$condition_context == 'single-condition'
	)
) {
	$condition_intro = 'UAMS Health ' . strtolower($provider_plural_name) . ' care for a broad range of ' . strtolower($condition_plural_name) . ', some of which may not be listed below.';
}
?>
<section class="uams-module conditions-treatments bg-auto" id="conditions">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title"><span class="title"><?php echo $condition_heading; ?></span></h2>
				<?php if ( $condition_intro ) { ?>
					<p class="note"><?php echo $condition_intro; ?></p>
				<?php } ?>
				<div class="">
					<ul class="list" style="column-count:3; list-style:none;">
					<?php foreach( $condition_cpt_query->posts as $condition ): ?>
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