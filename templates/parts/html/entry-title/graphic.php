<?php
/*
 * Template Name: Entry Title in the Graphic Style
 * 
 * Description: A template part that displays a page header using the Graphic style.
 * 
 * The page header displays a page title.
 * 
 * Optional elements include the following:
 * 	* A supertitle prepended to the page title
 * 	* A subtitle appended to the page title
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
 * 	$entry_title_text_supertitle // Optional supertitle, placed above the regular title
 * 	$entry_title_text_subtitle // Optional subtitle, placed below the regular title
 * 	$entry_title_text_body // Optional lead paragraph, placed below the entry title
 * 	$entry_title_image_desktop // Desktop breakpoint image ID
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
 * Required Entry header style ($entry_header_style)
 * 	- Values: 'graphic'
 * 	- Character limit: no limit
 * 
 * Optional Supertitle ($entry_title_text_supertitle)
 * 	- Character limits: no limit
 * 
 * Optional Subtitle ($entry_title_text_subtitle)
 * 	- Character limits: no limit
 * 
 * Optional Lead paragraph ($entry_title_text_body)
 * 	- Character limits: 500 characters
 * 
 * Optional Desktop breakpoint image ID ($entry_title_text_body)
 * 	- Minimum dimensions: 1920x720
 */

// Check/define variables

	$entry_title_text = ( isset($entry_title_text) && !empty($entry_title_text) ) ? $entry_title_text : get_the_title();
	$entry_title_text_supertitle = isset($entry_title_text_supertitle) ? $entry_title_text_supertitle : '';
	$entry_title_text_subtitle = isset($entry_title_text_subtitle) ? $entry_title_text_subtitle : '';
	$entry_title_text_body = isset($entry_title_text_body) ? $entry_title_text_body : '';
	$entry_title_image_desktop = isset($entry_title_image_desktop) ? $entry_title_image_desktop : '';

?>
<div class="col-12">
	<?php

	if ($entry_title_image_desktop && function_exists( 'fly_add_image_size' ) ) {

	?><style>
		.entry-header:before {
			background-image: url("<?php echo image_sizer($entry_title_image_desktop, 566, 216, 'center', 'center'); ?>");
		}

		/* XXS Breakpoint, retina */
		@media (-webkit-min-device-pixel-ratio: 2),
		(min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1152, 432, 'center', 'center'); ?>");
			}
		}

		/* XS Breakpoint */
		@media (min-width: 576px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 768, 288, 'center', 'center'); ?>");
			}
		}

		/* XS Breakpoint, retina */
		@media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 576px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1536, 576, 'center', 'center'); ?>");
			}
		}

		/* SM Breakpoint */
		@media (min-width: 768px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 992, 372, 'center', 'center'); ?>");
			}
		}

		/* SM Breakpoint, retina */
		@media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 768px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1984, 744, 'center', 'center'); ?>");
			}
		}

		/* MD Breakpoint */
		@media (min-width: 992px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1200, 450, 'center', 'center'); ?>");
			}
		}

		/* MD Breakpoint, retina */
		@media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 992px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 2400, 900, 'center', 'center'); ?>");
			}
		}

		/* LG Breakpoint */
		@media (min-width: 1200px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1500, 563, 'center', 'center'); ?>");
			}
		}

		/* LG Breakpoint, retina */
		@media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 1200px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 3000, 1125, 'center', 'center'); ?>");
			}
		}

		/* XL Breakpoint */
		@media (min-width: 1500px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 1921, 720, 'center', 'center'); ?>");
			}
		}

		/* XL Breakpoint, retina */
		@media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 1500px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 3842, 1441, 'center', 'center'); ?>");
			}
		}

		/* XXL Breakpoint */
		@media (min-width: 1921px) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 2560, 960, 'center', 'center'); ?>");
			}
		}

		/* XXL Breakpoint, retina */
		@media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
		(min-width: 1921px) and (min-resolution: 192dpi) {
			.entry-header:before {
				background-image: url("<?php echo image_sizer($entry_title_image_desktop, 5120, 1920, 'center', 'center'); ?>");
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
	?><header class="entry-header uams-module extra-padding graphic-title<?php echo $entry_title_image_desktop ? ' bg-image' : ''; ?> bg-red">
		<div class="text-container">
			<div class="graphic-title-heading">
				<h1 class="entry-title" itemprop="headline"><?php 

					if ( $entry_title_text_supertitle ) {
						?><span class="supertitle" style="color: inherit; text-transform: none;"><?php echo $entry_title_text_supertitle; ?></span><span class="sr-only">: </span><?php 
					}

					echo $entry_title_text;

					if ( $entry_title_text_subtitle ) {
						?><span class="subtitle" style="color: inherit; text-transform: none;"><?php echo $entry_title_text_subtitle; ?></span><?php 
					}

				?></h1>
			</div>
			<?php if ( $entry_title_text_body ) { ?>
				<div class="graphic-title-body">
					<p><?php echo $entry_title_text_body; ?></p>
				</div>
			<?php } ?>
		</div>
	</header>
</div>
