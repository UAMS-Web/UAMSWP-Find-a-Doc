<?php 
/**
 * Template Name: Clinical Resource Loop - List Item layout
 * 
 * Description: A template part that displays a clinical resource list item to be 
 * included in a list of clinical resources associated with the current page.
 * 
 * Must be used inside a loop
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$page_id // int // ID of the current page
 * 	$clinical_resource_single_name // string // System setting for Clinical Resources single item name
 */

$resource_title = get_the_title($page_id);
$resource_title_attr = uamswp_attr_conversion($resource_title);

$resource_label = 'View ' . $clinical_resource_single_name . ' page for ' . $resource_title_attr;

$resource_type = get_field('clinical_resource_type', $page_id);
$resource_type_value = $resource_type['value'];
$resource_type_label = $resource_type['label'];

$resource_excerpt = get_the_excerpt($page_id) ? get_the_excerpt($page_id) : wp_strip_all_tags( get_the_content($page_id) );
$resource_excerpt_len = strlen($resource_excerpt);
if ( $resource_excerpt_len > 160 ) {
	$resource_excerpt = wp_trim_words( $resource_excerpt, 23, ' &hellip;' );
}
?>
<li class="item">
	<div class="text-container">
		<h3 class="h5"><a href="<?php echo get_permalink($page_id); ?>" aria-label="<?php echo get_permalink($resource_label); ?>"><?php echo get_the_title($page_id); ?></a> <span class="subtitle"><span class="sr-only">(</span><?php echo esc_html($resource_type_label); ?><span class="sr-only">)</span></span></h3>
		<p><?php echo $resource_excerpt; ?></p>
	</div>
</li>