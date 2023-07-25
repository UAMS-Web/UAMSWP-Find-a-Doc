<?php
/*
 * Template Name: Schema Data Script Tag
 * 
 * Description: A template part that displays the schema data script tag
 */

?>
<script type='application/ld+json'>
{
	"@context": "http://www.schema.org",
	"@type": "Physician"<?php

	if ($full_name_attr) { ?>,
	"name": "<?php echo $full_name_attr; ?>"<?php
	} // endif ( $full_name_attr )

	?>,
	"url": "<?php echo get_permalink(); ?>",
	"logo": "<?php echo get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png'; ?>"<?php

	if ($docphoto) { ?>,
	"image": "<?php echo $docphoto; ?>"<?php
	} // endif ( $docphoto )

	if ($schema_description) { ?>,
	"description": "<?php echo $schema_description; ?>"<?php
	} // endif ( $schema_description )

	if ($condition_treatment_schema) { ?>,
	<?php echo $condition_treatment_schema;

	} // endif ( $condition_treatment_schema )

	if ($schema_description) { ?>,
	<?php echo $location_schema;
	} // endif ( $schema_description )

	if ( $rating_valid ) { ?>,
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue": "<?php echo $avg_rating; ?>",
		"ratingCount": "<?php echo $review_count; ?>"<?php
		echo ($comment_count  && '0' != $comment_count) ? ',
		"reviewCount": "'. $comment_count . '"' : ''; ?>
	}<?php
	} // endif ( $rating_valid )

	?>

}
</script>