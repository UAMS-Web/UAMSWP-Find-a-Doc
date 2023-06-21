<?php
/**
 * Template Name: Clinical Resources loop / text block
 * Designed for UAMS Find-a-Doc
 * 
 * Required vars:
 * 	$provider_single_name // System setting for Providers single item name
 * 	$provider_plural_name // System setting for Providers plural item name
 * 	$location_single_name // System setting for Locations single item name
 * 	$location_plural_name // System setting for Locations plural item name
 * 	$expertise_single_name // System setting for Areas of Expertise single item name
 * 	$expertise_plural_name // System setting for Areas of Expertise plural item name
 * 	$clinical_resource_single_name // System setting for Clinical Resources single item name
 * 	$clinical_resource_plural_name // System setting for Clinical Resources Plural Item Name
 * 	$condition_single_name // System setting for Conditions single item name
 * 	$condition_plural_name // System setting for Conditions plural item name
 * 	$treatment_single_name // System setting for Treatments single item name
 * 	$treatment_plural_name // System setting for Treatments plural item name
 * 	$clinical_resources
 * 	$clinical_resource_query
 * 	$resource_postsPerPage
 * 	$resource_more_suppress
 * 	$resource_more_key
 * 	$resource_more_value
 * 
 * Optional var:
 * 	$resource_page = 'single' or 'archive' (default to 'single')
 * 	$resource_heading
 * 	$resource_heading_related_name // To what is it related?
 * 	$resource_intro
 * 
 * List layout intended to either display all items or display a set number with no link to more.
 */

// Check optional variables
$resource_heading_related_name = ( isset($resource_heading_related_name) || !empty($resource_heading_related_name) ) ? $resource_heading_related_name : '';
if ( !isset($resource_heading) || empty($resource_heading) ) {
	if ( $resource_heading_related_name ) {
		$resource_heading = $clinical_resource_plural_name . ' Related to ' . $resource_heading_related_name;
	} else {
		$resource_heading = 'Related ' . $clinical_resource_plural_name;
	}
}
$resource_intro = ( isset($resource_intro) || !empty($resource_intro) ) ? $resource_intro : '';

// Count valid resources
//$resource_count = count($clinical_resources);
$resource_count = 0;
if ( $clinical_resources && $clinical_resource_query->have_posts() ) {
	foreach( $clinical_resources as $resource ) {
		if ( get_post_status ( $resource ) == 'publish' ) {
			$resource_count++;
		}
	}
}

if ( $resource_count > 4 && $resource_postsPerPage == -1 ) {
	$resource_layout = 'list';
} else {
	$resource_layout = 'card';
}
$resource_more = ( $resource_layout == 'card' && $resource_count > $resource_postsPerPage && ( $resource_more_key && !empty($resource_more_key) && $resource_more_value && !empty($resource_more_value) ) ) ? true : false;
if ( $resource_more_suppress ) {
	$resource_more = false;
}
$more_text = 'Want to find more ' . strtolower($clinical_resource_plural_name) . ( $resource_heading_related_name ? ' related to ' . $resource_heading_related_name : '') . '?';
$more_button_url = '/clinical-resource/?' . $resource_more_key . '=' . $resource_more_value;
$more_button_description = 'View the full list of ' . strtolower($clinical_resource_plural_name) . ( $resource_heading_related_name ? ' related to ' . $resource_heading_related_name : '');
$more_button_description_attr = uamswp_attr_conversion($more_button_description);
$more_button_target = '_blank';
$more_button_text = 'View the Full List';

if ( $resource_layout == 'card') { ?>
	<section class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title" id="related-resources-title"><span class="title"><?php echo $resource_heading; ?></span></h2>
					<?php echo $resource_intro ? '<p class="note">' . $resource_intro . '</p>' : ''; ?>
				</div>
				<div class="col-12">
					<div class="card-list card-list-left">
						<?php 
						while ($clinical_resource_query->have_posts()) {
							$clinical_resource_query->the_post();
							$id = get_the_ID();
							include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );
						} // endwhile
						wp_reset_postdata();
						?>
					</div>
				</div>
				<?php if ( $resource_more ) { ?>
					<div class="col-12 more">
						<p class="lead"><?php echo $more_text; ?></p>
						<div class="cta-container">
							<a href="<?php echo $more_button_url; ?>" class="btn btn-outline-primary" aria-label="<?php echo $more_button_description_attr; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
						</div>
					</div>
				<?php } ?>
			</div><!-- End .row -->
		</div><!-- End .container-fluid -->
	</section>
<?php } else { ?>
	<section class="uams-module link-list link-list-layout-split bg-auto" id="related-resources" aria-labelledby="related-resources-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-6 heading">
					<div class="text-container">
						<h2 class="module-title" id="related-resources-title"><span class="title"><?php echo $resource_heading; ?></span></h2>
					</div>
				</div>
				<div class="col-12 col-md-6 list">
					<ul>
						<?php
						while ($clinical_resource_query->have_posts()) : $clinical_resource_query->the_post();
							$id = get_the_ID();
							include( UAMS_FAD_PATH . '/templates/loops/resource-list-item.php' );
						endwhile;
						wp_reset_postdata(); ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
<?php } ?>