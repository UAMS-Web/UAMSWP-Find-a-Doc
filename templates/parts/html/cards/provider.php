<?php
/**
 * Template Name: Provider Loop - Card layout
 *
 * Description: A template part that displays a provider card to be included in a
 * list of providers associated with the current page.
 *
 * Must be used inside a loop
 *
 * Designed for UAMS Health Find-a-Doc
*/

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card

		$provider_card_fields_vars = ''; // Reset the variables
		include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/provider.php' );

?>
<div class="card">
	<picture>
		<?php

		if ( $provider_headshot_url ) {

			?>
			<img src="<?php echo $provider_headshot_url; ?>" itemprop="image" class="card-img-top" alt="<?php echo $provider_full_name_attr; ?>" loading="lazy" />
			<?php

		} else {

			?>
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg"
				media="(min-width: 1px)">
			<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />
			<?php

		}

		?>
	</picture>
	<div class="card-body">
		<h3 class="card-title h6">
			<span class="name"><?php echo $provider_full_name; ?></span>
			<?php

			// Add subtitle for clinical title

				if ( $provider_title_list ) {

					?>
					<span class="subtitle"><?php echo $provider_title_list; ?></span>
					<?php
				} // endif ( $provider_title_list )

			?>
		</h3>
	</div>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo $provider_url; ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $provider_full_name_attr; ?>" data-itemtitle="<?php echo $provider_full_name_attr; ?>">View Profile</a>
		</div>
	</div>
</div>