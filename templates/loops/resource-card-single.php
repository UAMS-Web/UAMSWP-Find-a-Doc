<?php 
/**
 * Template Name: Clinical Resource Card for Clinical Resource Section
 * 
 * Description: A template part that displays a clinical resource card to be 
 * included in a list of clinical resources associated with the current page.
 * 
 * Must be used inside a loop
 * 
 * Designed for UAMS Health Find-a-Doc
 */

// Check/define variables

	$id = get_the_ID();

	if ( !isset($clinical_resource_single_name) ) {
		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
			$clinical_resource_single_name = $labels_clinical_resource_vars['clinical_resource_single_name']; // string
	}

$resource_title = get_the_title($id);
$resource_title_attr = uamswp_attr_conversion($resource_title);

$resource_type = get_field('clinical_resource_type', $id);
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];

// Build an array of resource type values (keys) with the corresponding button text (values)
$resource_button_text_arr = array(
	'text' => 'Read the Article',
	'infographic' => 'View the Infographic',
	'video' => 'Watch the Video',
	'doc' => 'Read the Document'
);
$resource_button_text = 'View the ' . $clinical_resource_single_name; // Set fallback button text
if ( isset($resource_type) && isset($resource_button_text_arr[$resource_type_value]) ) {
	// IF resource type is set...
	// AND IF the resource type value is in $resource_button_text_arr...
	$resource_button_text = $resource_button_text_arr[$resource_type_value]; // Set the button text from the corresponding value from the array
}
$resource_button_text_attr = uamswp_attr_conversion($resource_button_text);
$resource_label = $resource_button_text_attr . ', ' . $resource_title_attr;

$resource_excerpt = get_the_excerpt($id) ? get_the_excerpt($id) : wp_strip_all_tags( get_the_content($id) );
$resource_excerpt_len = strlen($resource_excerpt);
if ( $resource_excerpt_len > 160 ) {
	$resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
}

$resource_image_wide = get_post_thumbnail_id($id);

/*
 * Required vars for the single card layout:
 * $id
 * $resource_image_wide
 * $resource_title
 * $resource_type_label
 * $resource_excerpt
 * $resource_label
 * $resource_title_attr
 * $resource_button_text
 */

?>
<div class="item">
	<div class="card">
		<div class="card-img-top">
			<picture>
				<?php if ( has_post_thumbnail($id) && function_exists( 'fly_add_image_size' ) ) { ?> 
					<source srcset="<?php echo image_sizer($resource_image_wide, 455, 256, 'center', 'center'); ?>" 
						media="(min-width: 1921px)">
					<source srcset="<?php echo image_sizer($resource_image_wide, 433, 244, 'center', 'center'); ?>" 
						media="(min-width: 1500px)">
					<source srcset="<?php echo image_sizer($resource_image_wide, 455, 256, 'center', 'center'); ?>" 
						media="(min-width: 992px)">
					<source srcset="<?php echo image_sizer($resource_image_wide, 433, 244, 'center', 'center'); ?>" 
						media="(min-width: 768px)">
					<source srcset="<?php echo image_sizer($resource_image_wide, 455, 256, 'center', 'center'); ?>" 
						media="(min-width: 1px)">
					<!-- Fallback -->
					<img src="<?php echo image_sizer($resource_image_wide, 455, 256, 'center', 'center'); ?>" alt="" role="presentation" />
				<?php } elseif ( has_post_thumbnail($id) ) { ?>
					<!-- Fallback -->
					<?php the_post_thumbnail( 'aspect-16-9-small', array( 'alt' => '', 'role' => 'presentation' ) ); ?>
				<?php } else { ?>
					<source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)">
					<img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" />
				<?php } ?>
			</picture>
		</div>
		<div class="card-body">
			<h3 class="card-title h5"><?php echo $resource_title; ?> <span class="subtitle"><span class="sr-only">(</span><?php echo esc_html($resource_type_label); ?><span class="sr-only">)</span></span></h3>
			<p class="card-text"><?php echo $resource_excerpt; ?></p>
			<a href="<?php echo get_permalink($id); ?>" class="btn btn-primary stretched-link" aria-label="<?php echo $resource_label; ?>" data-itemtitle="<?php echo $resource_title_attr; ?>"><?php echo $resource_button_text; ?></a>
		</div>
	</div>
</div>