<?php
	/**
	 *  Template Name: Physician Loop - Card layout
	 *  Designed for physicians
	 */
?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php $full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_degree') ? ', ' . get_field('physician_degree') : '');
	      //$profileurl = '/directory/physician/' . $post->post_name .'/';
	?>
	<div class="card">
		<?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?>
		<div class="card-body">
				<h3 class="card-title">
					<span class="name"><?php echo $full_name; ?></span>
					<?php 
					if(! empty( get_field('physician_clinical_title') ) || ! empty( get_field('physician_department') ) ){
						echo '<span class="subtitle">';
						echo (get_field('physician_clinical_title') ? get_field('physician_clinical_title')->name : '');
						echo ((! empty( get_field('physician_clinical_title') )) && (! empty( get_field('physician_department') ) ) ? ', ' : '' );
						echo (get_field('physician_department') ? get_field('physician_department')->name : '');
						echo '</span>';
					}
					?>
				</h3>
			<a href="<?php the_permalink(); ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $full_name; ?>">View Profile</a>
		</div>
	</div>
	<?php endwhile; endif; ?>