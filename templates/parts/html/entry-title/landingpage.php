<?php
/*
 * Template Name: Entry Title in the Marketing Landing Page Style
 *
 * Description: A template part that displays a page header using the Marketing
 * Landing Page style.
 *
 * The page header displays a page title.
 *
 * Optional elements include the following:
 * 	* A lead paragraph placed after the page title
 * 	* A background image
 *
 * The template part expects the "Fly Dynamic Image Resizer" plugin by Junaid
 * Bhura to be installed.
 *
 * Required vars:
 * 	$entry_title_text // Regular title
 *
 * Optional vars:
 * 	$entry_title_text_body // Optional lead paragraph, placed below the entry title
 * 	$entry_title_image_desktop // Desktop breakpoint image ID
 * 	$entry_title_image_mobile // Optional mobile breakpoint image ID
 *
 * ----------
 *
 * Add the following to the relevant template to remove Genesis-standard post title and markup:
 * 	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
 * 	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
 * 	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
 *
 * Add the following to the relevant template to add this header style
 * 	add_action( 'genesis_before_content', 'uamswp_fad_post_title' );
 *
 * ----------
 *
 * Required Regular title ($entry_title_text)
 * 	- Character limits: 62 characters
 *
 * Optional Lead paragraph ($entry_title_text_body)
 * 	- Character limits: 117 characters
 *
 * Required Desktop breakpoint image ID ($entry_title_text_body)
 * 	- Minimum dimensions: 1920x600
 *
 * Optional Mobile breakpoint image ID ($entry_title_text_body)
 * 	- Minimum dimensions: 992x806
 */

// Check/define variables

	$entry_title_text = ( isset($entry_title_text) && !empty($entry_title_text) ) ? $entry_title_text : get_the_title();
	$entry_title_text_body = isset($entry_title_text_body) ? $entry_title_text_body : '';
	$entry_title_image_desktop = isset($entry_title_image_desktop) ? $entry_title_image_desktop : '';
	$entry_title_image_mobile = isset($entry_title_image_mobile) ? $entry_title_image_mobile : $entry_title_image_desktop;

if (
	empty($entry_title_text)
	||
	empty($entry_title_image_desktop)
) {

	// If the required fields are empty, render the graphic style entry title
	include( UAMS_FAD_PATH . '/templates/parts/html/entry-title/graphic.php');

} else {

	// Otherwise, proceed

	?>
	<div class="col-12">
		<?php
		if ($entry_title_image_desktop && function_exists( 'fly_add_image_size' ) ) {
		?><style>
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_mobile, 576, 468, 'center', 'center'); ?>");
			}

			/* XXS Breakpoint, retina */
			@media (-webkit-min-device-pixel-ratio: 2),
			(min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_mobile, 1152, 936, 'center', 'center'); ?>");
				}
			}

			/* XS Breakpoint */
			@media (min-width: 576px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_mobile, 768, 624, 'center', 'center'); ?>");
				}
			}

			/* XS Breakpoint, retina */
			@media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 576px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_mobile, 1536, 1248, 'center', 'center'); ?>");
				}
			}

			/* SM Breakpoint */
			@media (min-width: 768px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_mobile, 992, 806, 'center', 'center'); ?>");
				}
			}

			/* SM Breakpoint, retina */
			@media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 768px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_mobile, 1984, 1612, 'center', 'center'); ?>");
				}
			}

			/* MD Breakpoint */
			@media (min-width: 992px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1200, 375, 'center', 'center'); ?>");
				}
			}

			/* MD Breakpoint, retina */
			@media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 992px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 2400, 750, 'center', 'center'); ?>");
				}
			}

			/* LG Breakpoint */
			@media (min-width: 1200px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1500, 469, 'center', 'center'); ?>");
				}
			}

			/* LG Breakpoint, retina */
			@media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 1200px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 3000, 938, 'center', 'center'); ?>");
				}
			}

			/* XL Breakpoint */
			@media (min-width: 1500px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1921, 601, 'center', 'center'); ?>");
				}
			}

			/* XL Breakpoint, retina */
			@media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 1500px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 3842, 1201, 'center', 'center'); ?>");
				}
			}

			/* XXL Breakpoint */
			@media (min-width: 1921px) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 2560, 800, 'center', 'center'); ?>");
				}
			}

			/* XXL Breakpoint, retina */
			@media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
			(min-width: 1921px) and (min-resolution: 192dpi) {
				.entry-header:before {
					background-image: url("<?php echo image_sizer($entry_title_image_desktop, 5120, 1600, 'center', 'center'); ?>");
				}
			}
		</style><?php
		} elseif ( $entry_title_image_desktop ) {
		?><style>
			.entry-header:before {
				background-image: url("<?php echo wp_get_attachment_url( $entry_title_image_desktop, 'full' ); ?>");
			}
		</style><?php
		}
		?><header class="entry-header uams-module extra-padding landing-page-title<?php echo $entry_title_image_desktop ? ' bg-image' : ''; ?>">
			<div class="text-container">
				<div class="landing-page-title-heading">
					<h1 class="entry-title" itemprop="headline"><?php echo $entry_title_text; ?></h1>
				</div>
				<?php if ( $entry_title_text_body ) { ?>
					<div class="landing-page-title-body">
						<p><?php echo $entry_title_text_body; ?></p>
					</div>
				<?php } ?>
			</div>
		</header>
	</div>
	<?php

} // endif