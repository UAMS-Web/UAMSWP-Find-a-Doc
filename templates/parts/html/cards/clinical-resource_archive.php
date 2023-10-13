<?php
/**
 * Template Name: Clinical Resource Card for Clinical Resource Archive
 *
 * Description: A template part that displays a clinical resource card to be
 * included in a clinical resource archive.
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
 */

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card

		$clinical_resource_card_fields_vars = ''; // Reset the variables
		$clinical_resource_card_style = 'detailed'; // Clinical resource card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/clinical-resource.php' );

// Check/define variables

	// Get system settings for clinical resource labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/clinical-resource.php' );

$clinical_resource_related_max = 3; // Set how many of each related item type to display

// Construct the card

?>
<div class="col item-container">
	<div class="item">
		<div class="row">
			<div class="col image">
				<a href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>">
					<picture>
						<?php

						if ( $clinical_resource_featured_image_base_url ) {

							foreach ( $clinical_resource_featured_image_srcset as $srcset ) {

								?>
								<source srcset="<?php echo $srcset['url']; ?>" media="(min-width: <?php echo $srcset['media-min-width']; ?>)">
								<?php

							}

							?>
							<img src="<?php echo $clinical_resource_featured_image_base_url; ?>" alt="" role="presentation" loading="lazy" />
							<?php

						} else {

							?>
							<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
							<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" loading="lazy" />
							<?php

						} // endif ( $clinical_resource_featured_image_base_url ) else

						?>
					</picture>
				</a>
			</div>
			<div class="col text">
				<div class="row">
					<div class="col-12 primary">
						<h3 class="h4">
							<a href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>">
								<span class="name"><?php echo $clinical_resource_title; ?></span>
							</a>
							<?php

							if ( $clinical_resource_type_label ) {

								?>
								<span class="subtitle"><span class="sr-only">(</span><?php echo esc_html($clinical_resource_type_label); ?><span class="sr-only">)</span></span>
								<?php

							} // endif ( $clinical_resource_type_label )

							?>
						</h3>
						<?php

						if ( $clinical_resource_excerpt ) {

							?>
							<p class="card-text"><?php echo $clinical_resource_excerpt; ?></p>
							<?php

						} // endif ( $clinical_resource_excerpt )

						?>
						<a class="btn btn-primary" href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>"><?php echo $clinical_resource_link_text; ?></a>
					</div>
					<div class="col-12 secondary">
						<?php

						// Related Content

							?>
							<h4 class="h5">Related Content</h4>
							<dl>
								<?php

								// Providers

									if ( $clinical_resource_provider_list ) {

										?>
										<dt><?php echo $clinical_resource_provider_label; ?></dt>
										<dd><?php echo $clinical_resource_provider_list; ?></dd>
										<?php

									} // endif

								// Locations

									if ( $clinical_resource_location_list ) {

										?>
										<dt><?php echo $clinical_resource_location_label; ?></dt>
										<dd><?php echo $clinical_resource_location_list; ?></dd>
										<?php

									} // endif

								// Areas of Expertise

									if ( $clinical_resource_expertise_list ) {

										?>
										<dt><?php echo $clinical_resource_expertise_label; ?></dt>
										<dd><?php echo $clinical_resource_expertise_list; ?></dd>
										<?php

									} // endif

								// Conditions

									if ( $clinical_resource_condition_list ) {

										?>
										<dt><?php echo $clinical_resource_condition_label; ?></dt>
										<dd><?php echo $clinical_resource_condition_list; ?></dd>
										<?php

									} // endif

								// Treatments

									if ( $clinical_resource_treatment_list ) {

										?>
										<dt><?php echo $clinical_resource_treatment_label; ?></dt>
										<dd><?php echo $clinical_resource_treatment_list; ?></dd>
										<?php

									} // endif

								?>
							</dl>
							<?php

						// End Related Content
						?>
						<a class="btn btn-primary" href="<?php echo $clinical_resource_url; ?>" aria-label="<?php echo $clinical_resource_link_label; ?>" data-categorytitle="View Clinical Resource" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>"><?php echo $clinical_resource_link_text; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>