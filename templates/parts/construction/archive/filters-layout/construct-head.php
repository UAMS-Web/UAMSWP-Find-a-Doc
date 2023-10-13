<?php
/*
 * Template Name: Archive Template Filters Layout HEAD Elements
 *
 * Description: A template part that constructs the HEAD elements common to all
 * archive pages using the filters layout
 */

// Region Cookie

	if (
		isset($archive_construct_args['filters-layout']['region-cookie'])
		&&
		!empty($archive_construct_args['filters-layout']['region-cookie'])
	) {

		if ( isset( $_COOKIE['wp_filter_region'] ) && !isset( $_GET[$archive_construct_args['filters-layout']['region-cookie']] ) ) {
			$region = $_COOKIE['wp_filter_region'];
			$url = $_SERVER["REQUEST_URI"];
			$url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?').$archive_construct_args['filters-layout']['region-cookie']. $region;
			header("Location: ". $url);
			exit();
		}

	}