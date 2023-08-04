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

	// Get the attributes of the current area of expertise item

		// Page ID
		$page_id = get_the_ID();

		// Title

			$expertise_title = get_the_title($page_id);
			$expertise_title_attr = uamswp_attr_conversion($expertise_title);

	// Query on whether this card is in a list of descendant areas of expertise
	$expertise_descendant_list = isset($expertise_descendant_list) ? $expertise_descendant_list : false;

	// Get the attributes of the parent of the current area of expertise item

		// Eliminate PHP errors

			$expertise_parent_id = '';
			$expertise_has_parent = '';
			$parent_expertise = '';
			$parent_id = '';
			$parent_title = '';
			$parent_title_attr = '';
			$parent_url = '';

		if ( !$expertise_descendant_list ) {

			// If this is not a list of descendant areas of expertise...

			// Parent ID
			$expertise_parent_id = wp_get_post_parent_id($page_id);

			// Query on whether the current item has a parent
			$expertise_has_parent = $expertise_parent_id ? true : false;

			// Get the parent post object
			if ( $expertise_has_parent && $expertise_parent_id ) {
				$parent_expertise = get_post($expertise_parent_id);
			}

			if ( $parent_expertise ) {

				// If the parent post object exists...

				// Parent ID
				$parent_id = $parent_expertise->ID;

				// Parent title

					$parent_title = $parent_expertise->post_title;
					$parent_title_attr = uamswp_attr_conversion($parent_title);

				// Parent URL
				$parent_url = get_permalink($parent_id);

			}

		}

	// Find-a-Doc Settings values for area of expertise labels

		if (
			!isset($expertise_single_name) || empty($expertise_single_name)
			||
			!isset($expertise_single_name_attr) || empty($expertise_single_name_attr)
		) {
			$labels_expertise_vars = uamswp_fad_labels_expertise();
				$expertise_single_name = $labels_expertise_vars['expertise_single_name']; // string
				$expertise_single_name_attr = $labels_expertise_vars['expertise_single_name_attr']; // string
		}

	// Define the values of the card elements

		// Link accessible label
		$expertise_label = 'View ' . $expertise_single_name_attr . ' page for ' . $expertise_title_attr;

		// Excerpt

			$expertise_excerpt = get_the_excerpt($page_id) ? get_the_excerpt($page_id) : wp_strip_all_tags( get_the_content($page_id) );

			// Truncate the excerpt if it is greater than 160 characters
			if ( strlen($expertise_excerpt) > 160 ) {
				$expertise_excerpt = wp_trim_words( $expertise_excerpt, 23, ' &hellip;' );
			}

?>
<div class="card">
	<a href="<?php echo get_permalink($page_id); ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Photo" data-itemtitle="<?php echo $expertise_title_attr; ?>">
		<?php

		if ( has_post_thumbnail($page_id) ) {

			echo get_the_post_thumbnail($page_id, 'aspect-16-9-small', ['class' => 'card-img-top', 'loading' => 'lazy']);

		} else {

			?>
			<picture>
				<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
				<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" />
			</picture>
			<?php

		} // endif ( has_post_thumbnail($page_id) ) else

		?>
	</a>
	<div class="card-body">
		<h3 class="card-title h5">
			<span class="name"><a href="<?php echo get_permalink($page_id); ?>" target="_self" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $expertise_title; ?></a></span>
			<?php

			if (
				$parent_expertise
				&&
				!$expertise_descendant_list
			) {

				?>
				<span class="subtitle"><span class="sr-only">(</span>Part of <a href="<?php echo $parent_url; ?>" aria-label="Go to <?php echo $expertise_single_name_attr; ?> page for <?php echo $parent_title_attr; ?>" data-categorytitle="Parent Name" data-itemtitle="<?php echo $expertise_title_attr; ?>"><?php echo $parent_title; ?></a><span class="sr-only">)</span></span>
				<?php

			} // endif

			?>
		</h3>
		<p class="card-text"><?php echo $expertise_excerpt; ?></p>
	</div>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php echo get_permalink($page_id); ?>" class="btn btn-primary" aria-label="<?php echo $expertise_label; ?>" data-categorytitle="View <?php echo $expertise_single_name_attr; ?>" data-itemtitle="<?php echo $expertise_title_attr; ?>">View <?php echo $expertise_single_name; ?></a>
		</div>
	</div>
</div>