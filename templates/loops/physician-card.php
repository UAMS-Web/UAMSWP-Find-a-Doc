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

$page_id = get_the_ID();

// Get the list of degrees for the current provider

	$degrees = get_field( 'physician_degree', $page_id );
	$degree_list = '';
	$i = 1;

	if ( $degrees ) {

		foreach ( $degrees as $degree ) {

			$degree_name = get_term( $degree, 'degree' );

			if ( is_object( $degree_name ) ) {

				$degree_list .= $degree_name->name;

				if ( count( $degrees ) > $i ) {

					$degree_list .= ", ";

				}

			}

			$i++;

		} // endforeach

	} // endif

$full_name = get_field( 'physician_first_name', $page_id ) .' ' . ( get_field( 'physician_middle_name', $page_id ) ? get_field( 'physician_middle_name', $page_id ) . ' ' : '' ) . get_field( 'physician_last_name', $page_id ) . ( get_field( 'physician_pedigree', $page_id ) ? '&nbsp;' . get_field( 'physician_pedigree', $page_id ) : '' ) . ( $degree_list ? ', ' . $degree_list : '' );
$full_name_attr = uamswp_attr_conversion( $full_name );
$provider_resident = get_field( 'physician_resident', $page_id );
$provider_resident_name = 'Resident Physician';
$provider_title = get_field( 'physician_title', $page_id );
$provider_title_name = $provider_resident ? $provider_resident_name : get_term( $provider_title, 'clinical_title' )->name;
$provider_service_line = get_field( 'physician_service_line', $page_id );

?>
<div class="card">
	<picture>
		<?php

		if (
			has_post_thumbnail()
			&&
			function_exists( 'fly_add_image_size' )
		) {

			?>
			<source srcset="<?php echo image_sizer( get_post_thumbnail_id( $page_id ), 253, 337, 'center', 'center' ); ?>"
				media="(min-width: 1px)">
			<img src="<?php echo image_sizer( get_post_thumbnail_id( ), 253, 337, 'center', 'center' ); ?>" itemprop="image" class="card-img-top" alt="<?php echo $full_name_attr; ?>" loading="lazy" />
			<?php

		} elseif ( has_post_thumbnail() ) {

			echo get_the_post_thumbnail( $page_id, 'medium', array( 'itemprop' => 'image', 'class' => 'card-img-top', 'loading' => 'lazy' ) );

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
				<span class="name"><?php echo $full_name; ?></span>
				<?php
				if (
					!empty( $provider_title_name )
					||
					!empty( $provider_service_line )
				) {
					echo '<span class="subtitle">';
					echo ( $provider_title_name ? $provider_title_name : '' );
					echo '</span>';
				}
				?>
			</h3>
	</div>
	<div class="btn-container">
		<div class="inner-container">
			<a href="<?php the_permalink( $page_id ); ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $full_name_attr; ?>" data-itemtitle="<?php echo $full_name_attr; ?>">View Profile</a>
		</div>
	</div>
</div>