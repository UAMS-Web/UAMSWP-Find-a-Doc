<?php 
/*
 *  Get ACF fields to use for meta data
 *  Add description from location short description or full description * 
 */
$excerpt = get_field('location_short_desc');
$about_loc = get_field('location_about');
if (empty($excerpt)){
    if ($about_loc){
        $excerpt = mb_strimwidth(wp_strip_all_tags($about_loc), 0, 155, '...');
    }
}
function sp_titles_desc($html) {
    global $excerpt;
	$html = $excerpt; 
	return $html;
}
add_filter('seopress_titles_desc', 'sp_titles_desc');

get_header();

while ( have_posts() ) : the_post(); ?>
<?php $map = get_field('location_map'); ?>
<main class="location-item">
	<section class="container-fluid p-0 p-md-10 location-info bg-white">
		<div class="row mx-0 mx-md-n8">
			<div class="col-12 col-md p-4 p-xs-8 p-sm-10 px-md-8 py-md-0 order-2 text">
			<h1 class="page-title"><?php the_title(); ?></h1>
				<h2 class="sr-only">Address</h2>
				<p><?php echo get_field('location_address_1', get_the_ID() ); ?><br/>
				<?php echo ( get_field('location_address_2' ) ? get_field('location_address_2') . '<br/>' : ''); ?>
				<?php echo get_field('location_city'); ?>, <?php echo get_field('location_state'); ?> <?php echo get_field('location_zip', get_the_ID()); ?></p>
					<p><a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Get Directions</a></p>
				<?php if(get_field('location_web_name') && get_field('location_url')){ ?>
					<p><a class="btn btn-secondary" href="<?php echo get_field('location_url')['url']; ?>"><?php echo get_field('location_web_name'); ?> <span class="far fa-external-link-alt"></span></span></a></p>
				<?php } ?>
				<h2>Contact Information</h2>
				<dl>
					<?php if (get_field('location_phone')) { ?>
					<dt>Clinic Phone Number</dt>
					<dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone') ); ?></a></dd>
					<?php } ?>
					<?php if ( get_field('field_location_phone_numbers') ) { 
						$phone_numbers = get_field('field_location_phone_numbers');
						while( have_rows('field_location_phone_numbers') ): the_row(); 
							$title = get_sub_field('location_appointments_text');
							$phone = get_sub_field('location_appointments_phone');
							$text = get_sub_field('location_appointments_additional_text');
					?>
					<dt><?php echo $title; ?></dt>
					<dd><a href="tel:<?php echo format_phone_dash( $phone ); ?>"><?php echo format_phone_us( $phone ); ?></a><?php echo ($text ? '<br/><small>'. $text .'</small>' : ''); ?></dd>
					<?php endwhile; 
						} ?>
					<?php
						$phone_numbers = get_field('location_appointments');
						if ( ! empty( $phone_numbers ) && ! empty( $phone_numbers[0]['number'] ) ) {
							foreach ( $phone_numbers as $phone_number ) {
								if (! empty($phone_number['text']) && ! empty($phone_number['number']) ) {
									echo '<dt>' . $phone_number['text'] . '</dt>';
									echo '<dd><a href="tel:'. $phone_number['number'] .'" class="icon-phone">'. $phone_number['number'] .'</a> ' . $phone_number['after'] .'</dd>'; // Display sub-field value
								}
							}
						}
					?>
				</dl>
				<?php
				$hours247 = get_field('location_24_7');
				$hours = get_field('location_hours');
				if ( $hours247 || $hours[0]['day'] ) : ?>
				<h2>Hours</h2>
				<?php
					if ($hours247):
						echo 'Open 24/7';
					else :
						echo '<dl class="hours">';
						if( $hours ) {
							$hours_text = '';
							$day = ''; // Previous Day
							$comment = ''; // Comment on previous day
							foreach ($hours as $hour) :
								if( $day !== $hour['day'] || $comment ) {
									$hours_text .= '<dt>'. $hour['day'] .'</dt> ';
									$hours_text .= '<dd>';
								} else {
									$hours_text .= ', ';
								}
								if ( $hour['closed'] ) {
									$hours_text .= 'Closed ';
								} else {
									$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] )  ? '' . apStyleDate( $hour['open'] ) . ' &ndash; ' . apStyleDate( $hour['close'] ) . '' : '' );
									if ( $hour['comment'] ) {
										$hours_text .= ' ' .$hour['comment'];
										$comment = $hour['comment'];
									} else {
										$comment = '';
									}
								}
								if( $day !== $hour['day'] && $comment ) {
									$hours_text .= '</dd>';
								}
								$day = $hour['day']; // Reset the day
							endforeach;
							echo $hours_text;
						} else {
							echo '<dt>No information</dt>';
						}
						echo '</dl>';
					endif;
					$holidayhours = get_field('location_holiday_hours');
					if ($holidayhours):
						/**
						 * Sort by date
						 * if current date is before date & within 30 days
						 * Display results
						 */

						$order = array();
						// populate order
						foreach( $holidayhours as $i => $row ) {	
							$order[ $i ] = $row['date'];
						}
						
						// multisort
						array_multisort( $order, SORT_ASC, $holidayhours );

						$i = 0;
						foreach( $holidayhours as $row ):
							$holidayDate = $row['date']; // Text
							$holidayDateTime = DateTime::createFromFormat('m/d/Y', $holidayDate); // Date for evaluation
							$dateNow = new DateTime("now", new DateTimeZone('America/Chicago') );
							if (($dateNow < $holidayDateTime) && ($holidayDateTime->diff($dateNow)->days < 30)) {
								if ( 0 == $i ) {
									echo '<h2>Upcoming Holiday Hours</h2>';
									echo '<dl class="hours">';
									$i++;
								}
								echo '<dt>'.$holidayDate . '<br/>';
								echo '</dt>' . '<dd>';
								if ( $row['closed'] ) {
									echo $row['closed'] ? 'Closed ' .$row['label'] .'</dd>': '';
								} else {
									echo ( ( $hour['open'] && '00:00:00' != $row['open'] )  ? '' . apStyleDate( $row['open'] ) . ' &ndash; ' . apStyleDate( $row['close'] ) . ' ' : '' );
									echo $row['label'] .'</dd>';
								}
							}	
						endforeach;
						if ( 0 < $i ) {
							echo '</dl>';
						}
						// echo '<h2>Holiday Hours</h2>';
						// echo '<dl class="hours">';
						// if( $hours ) {
						// 	$hours_text = '';
						// 	$day = ''; // Previous Day
						// 	$comment = ''; // Comment on previous day
						// 	foreach ($hours as $hour) :
						// 		if( $day !== $hour['day'] || $comment ) {
						// 			$hours_text .= '<dt>'. $hour['day'] .'</dt> ';
						// 			$hours_text .= '<dd>';
						// 		} else {
						// 			$hours_text .= ', ';
						// 		}
						// 		if ( $hour['closed'] ) {
						// 			$hours_text .= 'Closed ';
						// 		} else {
						// 			$hours_text .= ( ( $hour['open'] && '00:00:00' != $hour['open'] )  ? '' . apStyleDate( $hour['open'] ) . ' &ndash; ' . apStyleDate( $hour['close'] ) . '' : '' );
						// 			if ( $hour['comment'] ) {
						// 				$hours_text .= ' ' .$hour['comment'];
						// 				$comment = $hour['comment'];
						// 			} else {
						// 				$comment = '';
						// 			}
						// 		}
						// 		if( $day !== $hour['day'] && $comment ) {
						// 			$hours_text .= '</dd>';
						// 		}
						// 		$day = $hour['day']; // Reset the day
						// 	endforeach;
						// 	echo $hours_text;
						// } else {
						// 	echo '<dt>No information</dt>';
						// }
						// echo '</dl>';
					endif; ?>
				<?php if (get_field('location_after_hours') && !get_field('location_24_7')) { ?>
				<h2>After Hours</h2>
				<?php echo get_field('location_after_hours'); ?>
				<?php } ?>
			</div>
			<?php endif; ?>
			<div class="col-12 col-md px-0 px-md-8 order-1 image">
				<picture>
					<?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
						<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 1260, 945, 'center', 'center'); ?>" media="(min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1200px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 630, 473, 'center', 'center'); ?>"  media="(min-width: 1500px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 800, 600, 'center', 'center'); ?>" media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 992px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 400, 300, 'center', 'center'); ?>" media="(min-width: 992px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 1984, 1116, 'center', 'center'); ?>" media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 768px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 992, 558, 'center', 'center'); ?>" media="(min-width: 768px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 1536, 864, 'center', 'center'); ?>" media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 576px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 768, 432, 'center', 'center'); ?>" media="(min-width: 576px)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 1152, 648, 'center', 'center'); ?>" media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 576, 324, 'center', 'center'); ?>" media="(min-width: 1px)">
					<!-- Original Image SrcSet -->
					<!-- <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 630, 473, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 1260, 945, 'center', 'center'); ?> 2x"
						media="(min-width: 1500px)">
					<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 400, 300, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 800, 600, 'center', 'center'); ?> 2x"
						media="(min-width: 992px)">
					<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 992, 558, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 1984, 1116, 'center', 'center'); ?> 2x"
						media="(min-width: 768px)">
					<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 768, 432, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 1536, 864, 'center', 'center'); ?> 2x"
						media="(min-width: 576px)">  
						<source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 576, 324, 'center', 'center'); ?> 1x, <?php echo image_sizer(get_post_thumbnail_id(), 1152, 648, 'center', 'center'); ?> 2x"
						media="(min-width: 1px)"> -->
					<?php } // endif ?>
					<?php if ( has_post_thumbnail() ) { 
						 the_post_thumbnail('medium_large', ['class' => 'img-responsive']); 
						  } ?>
				</picture>
			</div>
		</div>
	</section>
	<?php if ( get_field('location_about')) { ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title">About This Location</h2>
				<div class="module-body">
					<?php echo get_field('location_about'); ?>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
	<section class="container-fluid p-8 p-sm-10 location-directions bg-auto" id="directions">
		<div class="row mx-md-n8">
			<?php if (get_field('location_parking') || get_field('location_direction')) : ?>
			<div class="col-12 col-md-6 pt-8 pt-sm-10 pt-md-0 px-md-8 order-2 order-md-1 text">
				<div class="module-body">
					<?php if (get_field('location_parking')) { ?>
					<h2>Parking</h2>
					<?php echo get_field('location_parking'); ?>
					<?php } // End Parking 
					if (get_field('location_direction')) { ?>
					<h2>Directions</h2>
					<?php echo get_field('location_direction'); ?>
					<? } // End Directions ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if( !empty($map) ): ?>
			<div class="col-12 col-md-6 order-1 order-md-2 map">
				<div class="embed-responsive embed-responsive-16by9" id="map"></div>
				<script type='text/javascript'>
						/*-- Function to create encode SVG  --*/
						/* colors needd to be hex code without # */
						// createSVGIcon("9d2235", "222", "whitetext", "1");
						var createSVGIcon = function(fillColor,strokeColor,labelClass,labelText) {
							var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 27.77" aria-labelledby="pinTitle" role="img"><title id="mapTitle">Basic Map Pin</title><path d="M9.5,26.26l.57-.65c.29-.4,7.93-9.54,7.93-15.67A8.75,8.75,0,0,0,9.5,1,8.75,8.75,0,0,0,1,9.94c0,6,7.54,15.27,7.93,15.67l.57.65Z" fill="#'+ fillColor +'" stroke="#'+ strokeColor +'" stroke-miterlimit="10" stroke-width="1"/></svg>';
							var encoded = window.btoa(svg);
							var backgroundImage = "background-image: url(data:image/svg+xml;base64,"+encoded+")";
							return '<div style="'+ backgroundImage +'" class="'+ labelClass +'">'+ labelText +'</div>';
						}
						/* Function to create divIcon for leaflet map */
						// createLabelIcon("leaflet-icon","A");
						var createLabelIcon = function(labelClass,labelText){
							return L.divIcon({
								className: labelClass,
								html: labelText,
								iconSize: new L.Point(28, 41),
								iconAnchor: new L.Point(14, 43),
								popupAnchor: [0, -43]
							})
						}
						var map = new L.Map('map', {center: new L.LatLng(<?php echo $map['lat']; ?>, <?php echo $map['lng'] ?>), zoom: 16 });
						map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
						// for all possible values and explanations see "Template Parameters" in https://msdn.microsoft.com/en-us/library/ff701716.aspx
						var imagerySet = "Road"; // AerialWithLabels | Birdseye | BirdseyeWithLabels | Road
						var bing = new L.BingLayer("AnCRy0VpPMDzYT6rOBqqqCNvNbUWvdSOv8zrQloRCGFnJZU28JK3c6cQkCMAHtCd", {type: imagerySet});
						map.addLayer(bing);
						/* [lat, lon, fillColor, strokeColor, labelClass, iconText, popupText] */
						var markers = [
							// example [ 34.74376029995541, -92.31828863640054, "00F","000","white","A","I am a blue icon." ],
							[ <?php echo $map['lat']; ?>, <?php echo $map['lng'] ?>, "9d2235","222", "transparentwhite", '<i class="fas fa-circle fa-sm"></i>', "" ]
						]
						//Loop through the markers array
						var markerArray = [];
						for (var i=0; i<markers.length; i++) {

							var lat = markers[i][0];
							var lon = markers[i][1];
							var fillColor = markers[i][2];
							var strokeColor = markers[i][3];
							var labelClass = markers[i][4];
							var iconText = markers[i][5];
							var popupText = markers[i][6];

							var markerLocation = new L.LatLng(lat, lon);
							marker = new L.marker([lat, lon], { icon: createLabelIcon("leaflet-icon", createSVGIcon(fillColor,strokeColor,labelClass,iconText))});
							if (popupText)
								marker.bindPopup(popupText, { maxWidth: '240' });
							marker.addTo(map);

							markerArray.push(markerLocation);
						}

						group = new L.LatLngBounds(markerArray);
						if (markers.length > 1){
							map.fitBounds(group, {padding: [100, 75]});
						}
					</script>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php if ( get_field('location_appointment') || get_field('location_appointment_bring')): ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<?php if ( get_field('location_appointment') ) { ?>
				<h2 class="module-title">Appointments</h2>
				<div class="module-body">
					<?php echo get_field('location_appointment'); ?>
				</div>
				<?php } 
				if ( get_field('location_appointment_bring') ) { 
				?>
				<h3 class="module-title">What to Bring to Your Appointment</h3>
				<div class="module-body">
					<?php echo get_field('location_appointment_bring'); ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<?php
	$physicians = get_field( 'location_physicians' );
	if( $physicians ): ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Doctors at <?php the_title(); ?></h2>

				<!-- <div class="list-legend">
					<div class="az-filter">
							<?php 
								/* 
								All Filters:
								Disable any checkbox input that does not represent any conditions. Add "disabled" to input.
								
								A-Z Filter:
								Start with "All"/"Any" checkbox input checked. Add "checked" attribute to input.
								If user checks any other checkbox input, remove "checked" attribute from "All"/"Any" checkbox input.
								If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
								*/
							?>
							<?php  //echo facetwp_display( 'facet', 'location_doctors_cards' ); ?>
				 	</div>
				</div> -->
				<div class="card-list-container">
					<div class="card-list card-list-doctors facetwp-template">
						<?php echo facetwp_display( 'template', 'physician_by_location' ); ?>
					</div>
				</div>
				<div class="list-pagination">
					<?php echo facetwp_display( 'pager' ); ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<?php $specialties = get_field('medical_specialties');
		if( $specialties ): ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<?php $specialtiescols = partition( $specialties, 3 ); ?>
					<h2 class="module-title">Specialties at <?php the_title(); ?></h2>
					<div class="module-body">
						<?php for ( $i = 0 ; $i < 3 ; $i++ ) { ?>
			    		<div class="col-md-4">
						<?php
						 	foreach( $specialtiescols[$i] as $specialty ):
							 $specialty_name = get_term( $specialty, 'specialty');
								echo $specialty_name->name . '<br/>';
							 endforeach; 
						?>
			    		</div>
			    		<?php } // endfor?>
			    	</div>
			    
			</div>
		</div>
	</section>
	<?php endif; ?>
	<section class="container-fluid p-8 p-sm-10 news-list bg-auto">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Latest News for [Location Item Name]</h2>
				<div class="card-list-container">
					<div class="card-list">
						<div class="card">
							<img srcset="https://picsum.photos/434/244?image=1066 1x, https://picsum.photos/868/488?image=1066 2x" src="https://picsum.photos/434/244?image=1066" class="card-img-top" alt="Image description or Story title">
							<div class="card-body">
								<h3 class="card-title">
									<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
								</h3>
								<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
								<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
							</div>
						</div>
						<div class="card">
							<img srcset="https://picsum.photos/434/244?image=348 1x, https://picsum.photos/868/488?image=348 2x" src="https://picsum.photos/434/244?image=348" class="card-img-top" alt="Image description or Story title">
							<div class="card-body">
								<h3 class="card-title">
									<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
								</h3>
								<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
								<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
							</div>
						</div>
						<div class="card">
							<img srcset="https://picsum.photos/434/244?image=823 1x, https://picsum.photos/868/488?image=823 2x" src="https://picsum.photos/434/244?image=823" class="card-img-top" alt="Image description or Story title">
							<div class="card-body">
								<h3 class="card-title">
									<span class="name">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</span>
								</h3>
								<p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.&nbsp;...</p>
								<a href="javascript:void(0)" class="btn btn-primary stretched-link" aria-label="Story title">Read Full Story</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="container-fluid p-8 p-sm-10 cta-bar cta-bar-1 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2>Make an Appointment</h2>
				<p>Request an appointment directly with <a href="javascript:void(0)">your clinic</a>, <a href="javascript:void(0)">your doctor</a>, <span class="no-break">or call <a href="javascript:void(0)">501-555-5555</a>.</span></p>
			</div>
		</div>
	</section>
</main>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>