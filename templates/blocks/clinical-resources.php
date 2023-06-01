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
 * 	$conditions_single_name // System setting for Conditions single item name
 * 	$conditions_plural_name // System setting for Conditions plural item name
 * 	$treatments_single_name // System setting for Treatments single item name
 * 	$treatments_plural_name // System setting for Treatments plural item name
 * 	$resources
 * 	$resource_query
 * 	$resource_postsPerPage
 * 	$resource_more_suppress
 * 	$resource_more_key
 * 	$resource_more_value
 * 	$resource_heading_related_pre
 * 	$resource_heading_related_post
 * 	$resource_heading_related_name
 * 
 * Optional var:
 * 	$resource_page = 'single' or 'archive' (default to 'single')
 * 
 * List layout intended to either display all items or display a set number with no link to more.
 */

$resource_heading = $clinical_resource_plural_name;
if ( $resource_heading_related_pre ) {
	$resource_heading = 'Related ' . $resource_heading;
}
if ( $resource_heading_related_post ) {
	$resource_heading = $resource_heading . ' Related to ' . $resource_heading_related_name;
}

// Count valid resources
//$resource_count = count($resources);
$resource_count = 0;
if ( $resources && $resource_query->have_posts() ) {
	foreach( $resources as $resource ) {
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
$more_text = 'Want to find more ' . strtolower($clinical_resource_plural_name) . ' related to ' . $resource_heading_related_name . '?';
$more_button_url = '/clinical-resource/?' . $resource_more_key . '=' . $resource_more_value;
$more_button_description = 'View the full list of ' . strtolower($clinical_resource_plural_name) . ' related to ' . $resource_heading_related_name;
$more_button_description_attr = uamswp_attr_conversion($more_button_description);
$more_button_target = '_blank';
$more_button_text = 'View the Full List';

if ( $resource_layout == 'card') { ?>
	<section class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h2 class="module-title" id="related-resources-title"><span class="title"><?php echo $resource_heading; ?></span></h2>
				</div>
				<div class="col-12">
					<div class="card-list card-list-left">
						<?php 
						while ($resource_query->have_posts()) : $resource_query->the_post();
							$id = get_the_ID();
							include( UAMS_FAD_PATH . '/templates/loops/resource-card.php' );
						endwhile;
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
						while ($resource_query->have_posts()) : $resource_query->the_post();
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