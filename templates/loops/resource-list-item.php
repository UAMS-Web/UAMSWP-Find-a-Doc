<?php 
/**
 * Template Name: Clinical Resource Loop - Card layout
 * Designed for UAMS Find-a-Doc
 * 
 * Must be used inside a loop
 * 
 * Required var: $id
 */

$resource_title = get_the_title($id);
$resource_title_attr = uamswp_attr_conversion($resource_title);

$resource_label = 'View Clinical Resource page for ' . $resource_title_attr;

$resource_type = get_field('clinical_resource_type', $id);
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];

$resource_excerpt = get_the_excerpt($id) ? get_the_excerpt($id) : wp_strip_all_tags( get_the_content($id) );
$resource_excerpt_len = strlen($resource_excerpt);
if ( $resource_excerpt_len > 160 ) {
	$resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
}
?>
<li class="item">
	<div class="text-container">
		<h3 class="h5"><a href="<?php echo get_permalink($id); ?>" aria-label="<?php echo get_permalink($resource_label); ?>"><?php echo get_the_title($id); ?></a> <span class="subtitle"><span class="sr-only">(</span><?php echo esc_html($resource_type_label); ?><span class="sr-only">)</span></span></h3>
		<p><?php echo $resource_excerpt; ?></p>
	</div>
</li>