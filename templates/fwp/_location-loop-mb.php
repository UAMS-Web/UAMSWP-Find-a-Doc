<?php $i = 0; ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php $class = ($i%2 == 0)? 'whiteBackground': 'grayBackground'; ?>
	<div class="<?php echo $class; ?> archive-box">
	    <div class="row">
	    	<div class="col-md-3">
	    		<?php if ( has_post_thumbnail() ) { ?>
	            	<p>
				    <a href="<?php echo get_permalink(); ?>" target="_self"><?php the_post_thumbnail('medium', ['class' => 'img-responsive']); ?></a>
					</p>
				<?php } ?>
	    	</div>
	    	<div class="col-md-9" class="margin-top-none margin-bottom-none">
	    		<a href="<?php echo get_permalink(); ?>" target="_self"><h2><?php the_title(); ?></h2></a>
				<?php $map = rwmb_get_value('location_map'); ?>
		        <p><?php echo rwmb_meta('location_address_1', $args, get_the_ID() ); ?><br/>
		            <?php echo ( rwmb_meta('location_address_2', $args ) ? rwmb_meta('location_address_2', $args) . '<br/>' : ''); ?>
		            <?php echo rwmb_meta('location_city', $args); ?>, <?php echo rwmb_meta('location_state', $args); ?> <?php echo rwmb_meta('location_zip', $args, get_the_ID()); ?><p/>
		        <p><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['latitude'] ?>,<?php echo $map['longitude'] ?>" target="_blank">Get Directions</a>
				</p>
	    	</div><!-- .col-9 -->
	    </div><!-- .row -->
	</div><!-- .color -->   
	<?php $i++; ?>
	<?php endwhile; else : ?>
	<?php  ?>
		<p><?php _e( 'Sorry, no physicians matched your criteria.' ); ?></p>
	<?php endif; ?>