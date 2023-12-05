<?php
    /**
     *  Template Name: Provider Loop - Card layout
     *  Designed for UAMS Find-a-Doc
     *
     *  Must be used inside a loop
     *  Required var: $id
     */
?>
<?php
	$degrees = get_field('physician_degree', $id);
	$degree_list = '';
	$i = 1;
	if ( $degrees ) {
		foreach( $degrees as $degree ):
			$degree_name = get_term( $degree, 'degree');
			if( is_object($degree_name) ) {
				$degree_list .= $degree_name->name;
				if( count($degrees) > $i ) {
					$degree_list .= ", ";
				}
			}
			$i++;
		endforeach;
	}
	?>
	<?php
		$full_name = get_field('physician_first_name', $id) .' ' .(get_field('physician_middle_name', $id) ? get_field('physician_middle_name', $id) . ' ' : '') . get_field('physician_last_name', $id) . (get_field('physician_pedigree', $id) ? '&nbsp;' . get_field('physician_pedigree', $id) : '') .  ( $degree_list ? ', ' . $degree_list : '' );
		$full_name_attr = $full_name;
		$full_name_attr = str_replace('"', '\'', $full_name_attr); // Replace double quotes with single quote
		$full_name_attr = str_replace('&#8217;', '\'', $full_name_attr); // Replace right single quote with single quote
		$full_name_attr = htmlentities($full_name_attr, null, 'UTF-8'); // Convert all applicable characters to HTML entities
		$full_name_attr = str_replace('&nbsp;', ' ', $full_name_attr); // Convert non-breaking space with normal space
		$full_name_attr = html_entity_decode($full_name_attr); // Convert HTML entities to their corresponding characters

		// Get resident values

			$physician_resident = get_field('physician_resident',$post->ID);
			$physician_resident_title_name = 'Resident Physician';

		// Get clinical specialty and occupation title values

			// Eliminate PHP errors

				$provider_specialty = '';
				$provider_specialty_term = '';
				$provider_specialty_name = '';
				$provider_occupation_title = '';

			if ( $physician_resident ) {

				// Clinical Occupation Title

					$provider_occupation_title = $resident_title_name;

			} else {

				// Clinical Specialty

					$provider_specialty = get_field('physician_title',$post->ID);

				// Clinical Occupation Title

					if ( $provider_specialty ) {

						$provider_specialty_term = get_term($provider_specialty, 'clinical_title');

						if ( is_object($provider_specialty_term) ) {

							// Get term name

								$provider_specialty_name = $provider_specialty_term->name;

							// Get occupational title field from term

								$provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term) ?? null;

							// Set occupational title from term name as a fallback

								if ( !$provider_occupation_title ) {

									$provider_occupation_title = $provider_specialty_name;

								}

						}

					}

			}

	?>
	<div class="card">
		<picture>
			<?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
				<source srcset="<?php echo image_sizer(get_post_thumbnail_id($id), 253, 337, 'center', 'center'); ?>"
					media="(min-width: 1px)">
				<img src="<?php echo image_sizer(get_post_thumbnail_id(), 253, 337, 'center', 'center'); ?>" itemprop="image" class="card-img-top" alt="<?php echo $full_name_attr; ?>" loading="lazy" />
			<?php } elseif ( has_post_thumbnail() ) { ?>
				<?php echo get_the_post_thumbnail( $id, 'medium',  array( 'itemprop' => 'image', 'class' => 'card-img-top', 'loading' => 'lazy' ) ); ?>
			<?php } else { ?>
				<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
				<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />
			<?php } ?>
		</picture>
		<div class="card-body">
				<h3 class="card-title h6">
					<span class="name"><?php echo $full_name; ?></span>
					<?php

					if (
						$provider_occupation_title
						&&
						!empty($provider_occupation_title)
					) {
						?>
						<span class="subtitle"><?php echo $provider_occupation_title; ?></span>
						<?php

					}

					?>
				</h3>
		</div>
		<div class="btn-container">
			<div class="inner-container">
				<a href="<?php the_permalink($id); ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $full_name_attr; ?>" data-itemtitle="<?php echo $full_name_attr; ?>">View Profile</a>
			</div>
		</div>
	</div>