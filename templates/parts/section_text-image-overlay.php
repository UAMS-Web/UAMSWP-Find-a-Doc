<?php
/*
 * Template Name: UAMS Text & Image Overlay Block
 * 
 * Description: A template part that displays a UAMS Text & Image Overlay Block 
 * from the UAMS 2020 theme.
 * 
 * When this template part is needed for a hook, use the 
 * uamswp_section_text_image_overlay() function.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$text_image_overlay_id // Section ID attribute value // string
 * 	$text_image_overlay_row_0 // Values for the first item // arr
 * 		['heading'] // Heading text, limited to 32 characters // str
 * 		['body'] // Body text, limited to 280 characters // str
 * 		['button_text'] // Link text, limited to 27 characters // str
 * 		['button_url'] // Full URL // str
 * 		['button_target'] // Query on whether to open the link in a new window/tab // bool
 * 		['button_desc'] // Link ARIA label text // str (default: ['button_text'] . ', ' . ['heading'] )
 * 		['background_color'] // Background color value // str (default: 'blue')
 * 		['image'] // Background image ID // int
 * 	$text_image_overlay_row_1 // Values for the second item // arr
 * 		['heading'] // Heading text, limited to 32 characters // str
 * 		['body'] // Body text, limited to 280 characters // str
 * 		['button_text'] // Link text, limited to 27 characters // str
 * 		['button_url'] // Full URL // str
 * 		['button_target'] // Query on whether to open the link in a new window/tab // bool
 * 		['button_desc'] // Link ARIA label text // str (default: ['button_text'] . ', ' . ['heading'] )
 * 		['background_color'] // Background color value // str (default: 'green')
 * 		['image'] // Background image ID // int
 * 
 * Return:
 * 	html <section />
 */

// Check/define variables
$text_image_overlay_color = array( 'bg-red', 'bg-black', 'bg-blue', 'bg-green', 'bg-teal', 'bg-eggplant', 'bg-orange' );
$text_image_overlay_color_auto = array( 'bg-blue', 'bg-green' );
$text_image_overlay_id = ( isset($text_image_overlay_id) && !empty($text_image_overlay_id) ) ? sanitize_title_with_dashes($text_image_overlay_id) : '';
$text_image_overlay_rows = array();
$text_image_overlay_row_0 = ( isset($text_image_overlay_row_0) && !empty($text_image_overlay_row_0) ) ? $text_image_overlay_row_0 : '';
if ( $text_image_overlay_row_0 ) {
	$text_image_overlay_row_0['heading'] = ( isset($text_image_overlay_row_0['heading']) && !empty($text_image_overlay_row_0['heading']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_0['heading']) : '';
	$text_image_overlay_row_0['body'] = ( isset($text_image_overlay_row_0['body']) && !empty($text_image_overlay_row_0['body']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_0['body']) : '';
	$text_image_overlay_row_0['button_text'] = ( isset($text_image_overlay_row_0['button_text']) && !empty($text_image_overlay_row_0['button_text']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_0['button_text']) : '';
	$text_image_overlay_row_0['button_url'] = ( isset($text_image_overlay_row_0['button_url']) && !empty($text_image_overlay_row_0['button_url']) ) ? $text_image_overlay_row_0['button_url'] : '';
	$text_image_overlay_row_0['button_target'] = ( isset($text_image_overlay_row_0['button_target']) && !empty($text_image_overlay_row_0['button_target']) ) ? $text_image_overlay_row_0['button_target'] : '';
	$text_image_overlay_row_0['button_desc'] = ( isset($text_image_overlay_row_0['button_desc']) && !empty($text_image_overlay_row_0['button_desc']) ) ? uamswp_attr_conversion(uamswp_fad_fpage_text_replace($text_image_overlay_row_0['button_desc'])) : '';
	$text_image_overlay_row_0['background_color'] = ( isset($text_image_overlay_row_0['background_color']) && !empty($text_image_overlay_row_0['background_color']) && in_array( $text_image_overlay_row_0['background_color'], $text_image_overlay_color ) ) ? $text_image_overlay_row_0['background_color'] : '';
	$text_image_overlay_row_0['image'] = ( isset($text_image_overlay_row_0['image']) && !empty($text_image_overlay_row_0['image']) ) ? $text_image_overlay_row_0['image'] : '';
	$text_image_overlay_rows[] = $text_image_overlay_row_0;
}
$text_image_overlay_row_1 = ( isset($text_image_overlay_row_1) && !empty($text_image_overlay_row_1) ) ? $text_image_overlay_row_1 : '';
if ( $text_image_overlay_row_1 ) {
	$text_image_overlay_row_1['heading'] = ( isset($text_image_overlay_row_1['heading']) && !empty($text_image_overlay_row_1['heading']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_1['heading']) : '';
	$text_image_overlay_row_1['body'] = ( isset($text_image_overlay_row_1['body']) && !empty($text_image_overlay_row_1['body']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_1['body']) : '';
	$text_image_overlay_row_1['button_text'] = ( isset($text_image_overlay_row_1['button_text']) && !empty($text_image_overlay_row_1['button_text']) ) ? uamswp_fad_fpage_text_replace($text_image_overlay_row_1['button_text']) : '';
	$text_image_overlay_row_1['button_url'] = ( isset($text_image_overlay_row_1['button_url']) && !empty($text_image_overlay_row_1['button_url']) ) ? $text_image_overlay_row_1['button_url'] : '';
	$text_image_overlay_row_1['button_target'] = ( isset($text_image_overlay_row_1['button_target']) && !empty($text_image_overlay_row_1['button_target']) ) ? $text_image_overlay_row_1['button_target'] : '';
	$text_image_overlay_row_1['button_desc'] = ( isset($text_image_overlay_row_1['button_desc']) && !empty($text_image_overlay_row_1['button_desc']) ) ? uamswp_attr_conversion(uamswp_fad_fpage_text_replace($text_image_overlay_row_1['button_desc'])) : '';
	$text_image_overlay_row_1['background_color'] = ( isset($text_image_overlay_row_1['background_color']) && !empty($text_image_overlay_row_1['background_color']) && in_array( $text_image_overlay_row_1['background_color'], $text_image_overlay_color ) ) ? $text_image_overlay_row_1['background_color'] : '';
	$text_image_overlay_row_1['image'] = ( isset($text_image_overlay_row_1['image']) && !empty($text_image_overlay_row_1['image']) ) ? $text_image_overlay_row_1['image'] : '';
	$text_image_overlay_rows[] = $text_image_overlay_row_1;
}

if ( $text_image_overlay_rows ) :
	$text_image_overlay_row_count = count($text_image_overlay_rows);

?>
<div class="uams-module no-padding text-image-overlay"<?php echo $text_image_overlay_id ? ' id="' . $text_image_overlay_id . '"' : ''; ?>>
    <div class="container-fluid">
        <div class="row">
			<?php 
			$index = 1;
			foreach ( $text_image_overlay_rows as $row ) { 
				// Load values and adding defaults.
				$heading = $row['heading'] ? substr( $row['heading'], 0, 32 ) : 'Lorem ipsum dolor sit amet, cons';
				$heading_attr = uamswp_attr_conversion($heading);
				$body = $row['body'] ? substr( $row['body'], 0, 280 ) : 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat mas';
				$button_text = $row['button_text'] ? substr( $row['button_text'], 0, 27 ) : 'Learn More';
				$button_url = $row['button_url'] ?: get_home_url();
				$button_target = $row['button_target'] ?: false;
				$button_desc = $row['button_desc'] ?: $button_text . ', ' . $heading;
				$background_color = $row['background_color'] ?: $text_image_overlay_color_auto[$index - 1];
				$image = $row['image'] ?: 3566;
				?>
				<section class="col-12<?php echo $row_count > 1 ? ' col-sm-6' : ''; ?> item bg-image item-<?php echo $index; ?> <?php echo $background_color; ?>"<?php echo $text_image_overlay_id ? ' aria-labelledby="' . $text_image_overlay_id . '-item-' . $index . '"' : ''; ?>>
					<?php if ( $row_count > 1 && function_exists( 'fly_add_image_size' ) ) { // Background styles for two tiles in one row with Fly plugin ?>
					<style>
						<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
							background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
						}

						/* XXS Breakpoint, retina */
						@media (-webkit-min-device-pixel-ratio: 2),
						(min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
							}
						}

						/* XS Breakpoint */
						@media (min-width: 576px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
							}
						}

						/* XS Breakpoint, retina */
						@media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 576px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
							}
						}

						/* SM Breakpoint */
						@media (min-width: 768px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 496, 372, 'center', 'center'); ?>");
							}
						}

						/* SM Breakpoint, retina */
						@media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 768px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
							}
						}

						/* MD Breakpoint */
						@media (min-width: 992px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 600, 450, 'center', 'center'); ?>");
							}
						}

						/* MD Breakpoint, retina */
						@media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 992px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
							}
						}

						/* LG Breakpoint */
						@media (min-width: 1200px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 750, 563, 'center', 'center'); ?>");
							}
						}

						/* LG Breakpoint, retina */
						@media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1200px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
							}
						}

						/* XL Breakpoint */
						@media (min-width: 1500px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center'); ?>");
							}
						}

						/* XL Breakpoint, retina */
						@media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1500px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
							}
						}

						/* XXL Breakpoint */
						@media (min-width: 1921px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center'); ?>");
							}
						}

						/* XXL Breakpoint, retina */
						@media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1921px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
							}
						}
					</style>
					<?php } elseif ( function_exists( 'fly_add_image_size' ) ) { // Background styles for one tile in one row with Fly plugin ?>
					<style>
						<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
							background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
						}

						/* XS Breakpoint, retina */
						@media (-webkit-min-device-pixel-ratio: 2),
						(min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
							}
						}

						/* XS Breakpoint */
						@media (min-width: 576px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
							}
						}

						/* XS Breakpoint, retina */
						@media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 576px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
							}
						}

						/* SM Breakpoint */
						@media (min-width: 768px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
							}
						}

						/* SM Breakpoint, retina */
						@media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 768px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1984, 1488, 'center', 'center'); ?>");
							}
						}

						/* MD Breakpoint */
						@media (min-width: 992px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
							}
						}

						/* MD Breakpoint, retina */
						@media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 992px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 2400, 1800, 'center', 'center'); ?>");
							}
						}

						/* LG Breakpoint */
						@media (min-width: 1200px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
							}
						}

						/* LG Breakpoint, retina */
						@media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1200px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 3000, 2250, 'center', 'center'); ?>");
							}
						}

						/* XL Breakpoint */
						@media (min-width: 1500px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
							}
						}

						/* XL Breakpoint, retina */
						@media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1500px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 3842, 2882, 'center', 'center'); ?>");
							}
						}

						/* XXL Breakpoint */
						@media (min-width: 1921px) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
							}
						}

						/* XXL Breakpoint, retina */
						@media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
						(min-width: 1921px) and (min-resolution: 192dpi) {
							<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo image_sizer($image, 5120, 3840, 'center', 'center'); ?>");
							}
						}
					</style>
					<?php } else { // Background styles for no Fly plugin ?>
					<style>
						<?php echo $text_image_overlay_id ? '#' . $text_image_overlay_id . ' ' : '' ; ?>.item-<?php echo $index; ?>:before {
								background-image: url("<?php echo wp_get_attachment_image_url( $image, 'aspect-4-3' ); ?>");
						}
					</style>
					<?php } //endif ?>
					<div class="text-container">
						<h2<?php echo $text_image_overlay_id ? ' id="' . $text_image_overlay_id . '-item-' . $index  . '"' : ''; ?>><?php echo $heading; ?></h2>
						<p><?php echo $body; ?></p>
						<a href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>" class="btn btn-white"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?> data-itemtitle="<?php echo $heading_attr; ?>"><?php echo $button_text; ?></a>
					</div>
				</section>
				<?php
				$index++;
			} // endforeach ( $text_image_overlay_rows as $row )
			?>
		</div>
	</div>
</div>
<?php endif; // ( $text_image_overlay_rows )