<?php 
    /**
     *  Template Name: Physician Loop - Card layout
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
			$degree_list .= $degree_name->name;
			if( count($degrees) > $i ) {
				$degree_list .= ", ";
			}
			$i++;
		endforeach; 
	} 
	?>
	<?php $full_name = get_field('physician_first_name', $id) .' ' .(get_field('physician_middle_name', $id) ? get_field('physician_middle_name', $id) . ' ' : '') . get_field('physician_last_name', $id) .  ( $degree_list ? ', ' . $degree_list : '' ); ?>
	<div class="card">
		<picture>
			<?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
			<source srcset="<?php echo image_sizer(get_post_thumbnail_id($id), 510, 680, 'center', 'center'); ?>"
				media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
				(min-width: 1px) and (min-resolution: 192dpi)">
			<source srcset="<?php echo image_sizer(get_post_thumbnail_id($id), 255, 340, 'center', 'center'); ?>"
				media="(min-width: 1px)">
			<img src="<?php echo image_sizer(get_post_thumbnail_id($id), 255, 340, 'center', 'center'); ?>" class="card-img-top" alt="<?php echo $full_name; ?>" />
			<?php } else { ?>
			<?php echo get_the_post_thumbnail( $id, 'medium',  array( 'itemprop' => 'image', 'class' => 'card-img-top' ) ); ?>
			<?php } //endif ?>
		</picture>
		<div class="card-body">
				<h3 class="card-title">
					<span class="name"><?php echo $full_name; ?></span>
					<?php 
					if(! empty( get_field('physician_title', $id) ) || ! empty( get_field('physician_department', $id) ) ){
						echo '<span class="subtitle">';
						echo (get_field('physician_title', $id) ? get_term( get_field('physician_title', $id), 'clinical_title' )->name : '');
						echo ((! empty( get_field('physician_title', $id) )) && (! empty( get_field('physician_department', $id) ) ) ? ',<br/>' : '' );
						echo (get_field('physician_department', $id) ? get_term( get_field('physician_department', $id), 'department' )->name : '');
						echo '</span>';
					}
					?>
				</h3>
			<a href="<?php the_permalink($id); ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $full_name; ?>">View Profile</a>
		</div>
	</div>