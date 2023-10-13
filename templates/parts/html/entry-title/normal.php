<?php
/*
 * Template Name: Entry Title in the Normal Style
 *
 * Description: A template part that displays a page header using the Normal style.
 *
 * The page header displays a page title.
 *
 * Optional elements include the following:
 * 	* A supertitle prepended to the page title
 * 	* A subtitle appended to the page title
 *
 * Required vars:
 * 	$entry_title_text // Regular title
 *
 * Optional vars:
 * 	$entry_title_text_supertitle // Optional supertitle, placed above the regular title
 * 	$entry_title_text_subtitle // Optional subtitle, placed below the regular title
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
 * Regular title ($entry_title_text)
 * 	- Character limits: 62 characters
 *
 * Supertitle ($entry_title_text_supertitle)
 * 	- Character limits: N/A
 *
 * Subtitle ($entry_title_text_subtitle)
 * 	- Character limits: N/A
 */

// Check/define variables

	$entry_title_text = ( isset($entry_title_text) && !empty($entry_title_text) ) ? $entry_title_text : get_the_title();
	$entry_title_text_supertitle = isset($entry_title_text_supertitle) ? $entry_title_text_supertitle : '';
	$entry_title_text_subtitle = isset($entry_title_text_subtitle) ? $entry_title_text_subtitle : '';

?>
<header class="entry-header">
	<div class="text-container">
		<h1 class="entry-title" itemprop="headline"><?php

			if ( $entry_title_text_supertitle ) {
				?><span class="supertitle"><?php echo $entry_title_text_supertitle; ?></span><span class="sr-only">: </span><?php
			}

			echo $entry_title_text;

			if ( $entry_title_text_subtitle ) {
				?><span class="subtitle"><?php echo $entry_title_text_subtitle; ?></span><?php
			}

		?></h1>
	</div>
</header>
