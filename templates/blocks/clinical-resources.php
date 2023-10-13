<?php
/**
 * Template Name: Clinical Resources loop / text block
 *
 * Description: A template part that displays a list of clinical resource items
 * associated with the current page.
 *
 * Two layouts are available: card layout and vertical text list layout.
 *
 * If the list is limited to the four most recently published items, a card layout
 * will be used (i.e., UAMS Stacked Image & Text Block). If there is no limit on
 * the number of items to be displayed, a vertical text list layout will be  used
 * (i.e., UAMS Link List Block).
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$clinical_resources // int[]
 * 	$clinical_resource_posts_per_page // int
 * 	$resource_more_suppress // bool
 * 	$clinical_resource_section_more_link_key // string
 * 	$clinical_resource_section_more_link_value // string
 *
 * Optional var:
 * 	$resource_heading_related_name // string // To what is it related?
 * 	$resource_heading // string
 * 	$resource_intro // string
 */

// Check/define variables

	if ( !isset($clinical_resource_posts_per_page) ) {

		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/posts-per-page/clinical-resource.php' ); // General maximum number of clinical resource items to display on a fake subpage (or section)
		$clinical_resource_posts_per_page = $clinical_resource_posts_per_page_section;

	}

	// Jump link count
	$jump_link_count = isset($jump_link_count) ? $jump_link_count : 0;

	// Related Clinical Resources Section Query
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/queries/clinical-resource.php' );

if ( $clinical_resource_section_show ) {

	// Check/define variables

		// Get system settings for clinical resource labels
		include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

		$resource_heading_related_name = isset($resource_heading_related_name) ? $resource_heading_related_name : '';

		if ( !isset($resource_heading) || empty($resource_heading) ) {
			if ( $resource_heading_related_name ) {
				$resource_heading = $clinical_resource_plural_name . ' Related to ' . $resource_heading_related_name;
			} else {
				$resource_heading = 'Related ' . $clinical_resource_plural_name;
			}
		}

		$resource_intro = isset($resource_intro) ? $resource_intro : '';

		if ( $clinical_resource_count > 4 && $clinical_resource_posts_per_page == -1 ) {
			$resource_layout = 'list';
		} else {
			$resource_layout = 'card';
		}
		$resource_more = ( $resource_layout == 'card' && $clinical_resource_count > $clinical_resource_posts_per_page && ( $clinical_resource_section_more_link_key && !empty($clinical_resource_section_more_link_key) && $clinical_resource_section_more_link_value && !empty($clinical_resource_section_more_link_value) ) ) ? true : false;
		if ( $resource_more_suppress ) {
			$resource_more = false;
		}
		$more_text = 'Want to find more ' . strtolower($clinical_resource_plural_name) . ( $resource_heading_related_name ? ' related to ' . $resource_heading_related_name : '') . '?';
		$clinical_resource_section_more_link_url = '/clinical-resource/?' . $clinical_resource_section_more_link_key . '=' . $clinical_resource_section_more_link_value;
		$more_button_description = 'View the full list of ' . strtolower($clinical_resource_plural_name) . ( $resource_heading_related_name ? ' related to ' . $resource_heading_related_name : '');
		$more_button_description_attr = uamswp_attr_conversion($more_button_description);
		$clinical_resource_section_more_link_target = '_blank';
		$more_button_text = 'View the Full List';

	if ( $resource_layout == 'card') {

		?>
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
								include( UAMS_FAD_PATH . '/templates/parts/html/cards/clinical-resource.php' );
							} // endwhile
							wp_reset_postdata();
							?>
						</div>
					</div>
					<?php if ( $resource_more ) { ?>
						<div class="col-12 more">
							<p class="lead"><?php echo $more_text; ?></p>
							<div class="cta-container">
								<a href="<?php echo $clinical_resource_section_more_link_url; ?>" class="btn btn-outline-primary" aria-label="<?php echo $more_button_description_attr; ?>"<?php $clinical_resource_section_more_link_target ? ' target="'. $clinical_resource_section_more_link_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
							</div>
						</div>
					<?php } ?>
				</div><!-- End .row -->
			</div><!-- End .container-fluid -->
		</section>
		<?php

	} else {

		?>
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
								$page_id = get_the_ID();
								include( UAMS_FAD_PATH . '/templates/loops/resource-list-item.php' );
							endwhile;
							wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<?php

	} // endif ( $resource_layout == 'card') else

} // endif ( $clinical_resource_section_show )

?>