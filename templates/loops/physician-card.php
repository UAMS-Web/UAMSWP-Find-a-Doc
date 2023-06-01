<?php 
/**
 * Template Name: Provider Loop - Card layout
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required vars:
 * 	$id
 */

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

$full_name = get_field('physician_first_name', $id) .' ' .(get_field('physician_middle_name', $id) ? get_field('physician_middle_name', $id) . ' ' : '') . get_field('physician_last_name', $id) . (get_field('physician_pedigree', $id) ? '&nbsp;' . get_field('physician_pedigree', $id) : '') . ( $degree_list ? ', ' . $degree_list : '' );
$full_name_attr = uamswp_attr_conversion($full_name);
$physician_resident = get_field('physician_resident', $id);
$physician_resident_name = 'Resident Physician';
$physician_title = get_field('physician_title', $id);
$physician_title_name = $physician_resident ? $physician_resident_name : get_term( $physician_title, 'clinical_title' )->name;
$physician_service_line = get_field('physician_service_line', $id);

?>
<div class="card">
	<picture>
		<?php if ( has_post_thumbnail() && function_exists( 'fly_add_image_size' ) ) { ?>
			<source srcset="<?php echo image_sizer(get_post_thumbnail_id($id), 253, 337, 'center', 'center'); ?>"
				media="(min-width: 1px)">
			<img src="<?php echo image_sizer(get_post_thumbnail_id(), 253, 337, 'center', 'center'); ?>" itemprop="image" class="card-img-top" alt="<?php echo $full_name_attr; ?>" loading="lazy" />
		<?php } elseif ( has_post_thumbnail() ) { ?>
			<?php echo get_the_post_thumbnail( $id, 'medium', array( 'itemprop' => 'image', 'class' => 'card-img-top', 'loading' => 'lazy' ) ); ?>
		<?php } else { ?>
			<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.svg" media="(min-width: 1px)">
			<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_3-4.jpg" alt="" role="presentation" loading="lazy" />
		<?php } ?>
	</picture>
	<div class="card-body">
			<h3 class="card-title h6">
				<span class="name"><?php echo $full_name; ?></span>
				<?php 
				if(! empty( $physician_title_name ) || ! empty( $physician_service_line ) ){
					echo '<span class="subtitle">';
					echo ($physician_title_name ? $physician_title_name : '');
					echo '</span>';
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