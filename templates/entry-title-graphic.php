<?php
/*
 * Entry Title in the Graphic Style
 */

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
