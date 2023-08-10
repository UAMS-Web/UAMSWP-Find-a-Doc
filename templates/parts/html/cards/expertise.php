<?php 
/**
 * Template Name: Areas of Expertise Loop - Card layout
 * 
 * Description: A template part that displays an area of expertise card to be 
 * included in a list of areas of expertise associated with the current page.
 * 
 * Must be used inside a loop
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Optional vars:
 * 	$expertise_descendant_list // Query for whether this is a list of child areas of expertise within an area of expertise // bool (default: false)
 */

// Check/define variables

	// Page ID
	$page_id = get_the_ID();

	// Get the field values for the card
	$expertise_card_fields_vars = ''; // Reset the variables
	include( UAMS_FAD_PATH . '/templates/parts/vars/page/cards/expertise.php' );

	// Query on whether this card is in a list of descendant areas of expertise
	$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false;

	// Get system settings for area of expertise labels
	include( UAMS_FAD_PATH . '/templates/parts/vars/sys/labels/expertise.php' );

	// Link accessible label
	$expertise_label = 'View ' . $expertise_single_name_attr . ' page for ' . $expertise_title_attr;

// Construct the card

?>
<div class="card">
	<a href="<?php echo $expertise_url; ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $expertise_title_attr; ?>">
		<?php

		if ( $expertise_featured_image ) {

			?>
			<img src="<?php echo $expertise_featured_image_url; ?>" alt="" role="presentation" class="card-img-top" loading="lazy" />
			<?php

		} else {

			?>
			<picture>
				<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
				<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" />
			</picture>
			<?php

		} // endif ( $expertise_featured_image ) else

		?>
	</a>
	<div class="card-body">
		<h3 class="card-title h5">
			<span class="name"><a href="<?php echo $expertise_url; ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $expertise_title; ?></a></span>
			<?php

			if (
				$expertise_parent_object
				&&
				!$expertise_descendant_list
			) {

				?>
				<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $expertise_parent_url; ?>" aria-label="Go to <?php echo $expertise_single_name_attr; ?> page for <?php echo $expertise_parent_title_attr; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $expertise_parent_title; ?></a><span class="sr-only">)</span></span>
				<?php

			} // endif

			?>
		</h3>
		<?php

		if ( $expertise_excerpt ) {

			?>
			<p class="card-text"><?php echo $expertise_excerpt; ?></p>
			<?php

		} // endif ( $expertise_excerpt )

		?>
	</div>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo $expertise_url; ?>" class="btn btn-primary" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="View <?php echo $expertise_single_name_attr; ?>" data-itemtitle="<?php echo $expertise_title_attr; ?>">View <?php echo $expertise_single_name; ?></a>
		</div>
	</div>
</div>