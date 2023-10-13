<?php
/**
 * Template Name: Clinical Resource Card for Clinical Resource Section
 *
 * Description: A template part that displays a clinical resource card to be
 * included in a list of clinical resources associated with the current page.
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
		$clinical_resource_card_style = 'basic'; // Clinical resource card style
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/clinical-resource.php' );

// Construct the card

?>
<div class="item">
	<div class="card">
		<div class="card-img-top">
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
		</div>
		<div class="card-body">
			<h3 class="card-title h5">
				<span class="name"><?php echo $clinical_resource_title; ?></span>
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
			<a href="<?php echo $clinical_resource_url; ?>" class="btn btn-primary stretched-link" aria-label="<?php echo $clinical_resource_link_label; ?>" data-itemtitle="<?php echo $clinical_resource_title_attr; ?>"><?php echo $clinical_resource_link_text; ?></a>
		</div>
	</div>
</div>