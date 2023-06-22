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
 */

// Check if mobile image exists
$entry_title_image_mobile = $entry_title_image_mobile ?: $entry_title_image_desktop; // Use desktop image if no mobile image exists

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
