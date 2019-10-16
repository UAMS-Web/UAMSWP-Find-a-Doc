<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="card">
		<?php if ( has_post_thumbnail() ) { ?>
		<?php the_post_thumbnail('aspect-16-9-small', ['class' => 'card-img-top']); ?>
		<?php } else { ?>
		<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" alt="" class="card-image-top" />
		<?php } ?>
		<div class="card-body">
            <h3 class="card-title">
				<span class="name"><a href="<?php echo get_permalink(); ?>" target="_self"><?php the_title(); ?></a></span>
            </h3>
			<?php $map = get_field('location_map'); ?>
			<p class="card-text"><?php echo get_field('location_address_1', get_the_ID() ); ?><br/>
				<?php echo ( get_field('location_address_2' ) ? get_field('location_address_2') . '<br/>' : ''); ?>
				<?php echo get_field('location_city'); ?>, <?php echo get_field('location_state'); ?> <?php echo get_field('location_zip', get_the_ID()); ?><p/>
				<a href="<?php echo get_permalink(); ?>" class="btn btn-primary" aria-label="Go to location page for <?php the_title(); ?>">View Location</a>
				<?php if ($map) { ?>
				<a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Get Directions</a>
				<?php } ?>
			</p>
		</div><!-- .card-body -->
	</div><!-- .card --> 
<?php endwhile; else : ?>
<?php  ?>
	<p><?php _e( 'Sorry, no locations matched your criteria.' ); ?></p>
<?php endif; ?>