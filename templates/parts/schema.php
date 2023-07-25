<?php
/*
 * Template Name: Schema Data Script Tag
 * 
 * Description: A template part that displays the schema data script tag
 * 
 * Required vars:
 * 	$schema_type // string
 * 	$schema_name // string
 * 	$schema_url // string
 * 	$schema_image // string
 * 	$schema_description // string
 * 
 * Optional vars:
 * 	$schema_medical_specialty // array
 * 	$schema_address // array
 * 	$schema_aggregate_rating; // bool (optional)
 * 	$schema_aggregate_rating_value; // string (optional)
 * 	$schema_aggregate_rating_count; // int (optional)
 * 	$schema_aggregate_rating_review_count; // int (optional)
 */

// Check/define variables

	$schema_type = isset($schema_type) ? $schema_type : '';
	$schema_name = isset($schema_name) ? $schema_name : '';
	$schema_url = isset($schema_url) ? $schema_url : '';
	$schema_image = isset($schema_image) ? $schema_image : '';
	$schema_description = isset($schema_description) ? $schema_description : '';
	$schema_medical_specialty = isset($schema_medical_specialty) ? $schema_medical_specialty : '';
	$schema_address = isset($schema_address) ? $schema_address : '';
	$schema_aggregate_rating_value = isset($schema_aggregate_rating_value) ? $schema_aggregate_rating_value : '';
	$schema_aggregate_rating_count = isset($schema_aggregate_rating_count) ? $schema_aggregate_rating_count : '';
	$schema_aggregate_rating_review_count = ( isset($schema_aggregate_rating_review_count) || 0 != $schema_aggregate_rating_review_count || '0' != $schema_aggregate_rating_review_count ) ? $schema_aggregate_rating_review_count : '';
	$schema_aggregate_rating = ( isset($schema_aggregate_rating) && ( $schema_aggregate_rating_value || $schema_aggregate_rating_count || $schema_aggregate_rating_review_count) ) ? $schema_aggregate_rating : false;
	$schema_line_break = "\n"; // the double quotes are important

// Open script tag
	echo $schema_line_break;
	echo '<script type="application/ld+json">' . $schema_line_break .  '{' . $schema_line_break;

// @context

	echo '"@context": "http://www.schema.org",';
	echo $schema_line_break;

// @type

	if ( $schema_type ) {
		echo '"@type": "' . $schema_type . '",';
		echo $schema_line_break;
	}

// name

	if ( $schema_name ) {
		echo '"name": "' . $schema_name . '",';
		echo $schema_line_break;
	}

// url

	if ( $schema_url ) {
		echo '"url": "' . $schema_url . '",';
		echo $schema_line_break;
	}

// logo

	echo '"logo": "' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png' . '",';
	echo $schema_line_break;

// image

	if ( $schema_image ) {
		echo '"image": "' . $schema_image . '",';
		echo $schema_line_break;
	}

// description

	if ( $schema_description ) {
		echo '"description": "' . $schema_description . '",';
		echo $schema_line_break;
	}

// medicalSpecialty

	if ( $schema_medical_specialty ) {
		echo '"medicalSpecialty": ' . $schema_medical_specialty . ',';
		echo $schema_line_break;
	}

// aggregateRating

	if ( $schema_aggregate_rating ) {

		echo '"aggregateRating": {';
		echo $schema_line_break;

		// @type

			echo '"@type": "AggregateRating",' . $schema_line_break;

		// ratingValue

			if ( $schema_aggregate_rating_value ) {
				echo '"ratingValue": "' . $schema_aggregate_rating_value . '",';
				echo $schema_line_break;
			}

		// ratingCount

			if ( $schema_aggregate_rating_count ) {
				echo '"ratingCount": "' . $schema_aggregate_rating_count . '",';
				echo $schema_line_break;
			}

		// reviewCount

			if ( $schema_aggregate_rating_review_count ) {
				echo '"reviewCount": "' . $schema_aggregate_rating_review_count . '",';
				echo $schema_line_break;
			}

		echo '}';
		echo $schema_line_break;
	}

// Close script tag
	echo '}' . $schema_line_break . '</script>';
	echo $schema_line_break;