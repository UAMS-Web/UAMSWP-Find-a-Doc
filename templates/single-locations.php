<?php get_header();

while ( have_posts() ) : the_post(); ?>
<?php $map = get_field('location_map'); ?>
<main class="location-item">
	<section class="container-fluid p-0 p-md-10 location-info bg-white">
		<div class="row mx-0 mx-md-n8">
			<div class="col-12 col-md p-4 p-xs-8 p-sm-10 px-md-8 py-md-0 order-2 text">
			<h1 class="page-title"><?php the_title(); ?></h1>
				<h2 class="sr-only">Address</h2>
				<p><?php echo get_field('location_address_1', $args, get_the_ID() ); ?><br/>
				<?php echo ( get_field('location_address_2', $args ) ? get_field('location_address_2', $args) . '<br/>' : ''); ?>
				<?php echo get_field('location_city', $args); ?>, <?php echo get_field('location_state', $args); ?> <?php echo get_field('location_zip', $args, get_the_ID()); ?></p>
						<a class="btn btn-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Get Directions</a>
				<h2>Contact Information</h2>
				<dl>
					<?php if (get_field('location_phone')) { ?>
					<dt>Clinic Phone Number</dt>
					<dd><a href="tel:<?php echo format_phone_dash( get_field('location_phone') ); ?>" class="icon-phone"><?php echo format_phone_us( get_field('location_phone') ); ?></a></dd>
					<?php } ?>
					
					<!-- <dt>Appointments Phone Number</dt>
					<dd><a href="tel:5555555555">(555) 555-5555</a></dd> -->

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
				<h2>Hours</h2>
				<!-- Use AP Style abbreviations (a.m. / p.m.), don't add zeroes after even hours, separate with en dashes -->
				<?php
					if (get_field('location_24_7', $args)):
						echo 'Open 24/7';
					else:
						echo '<dl class="hours">';
						echo '<dt>Sunday</dt> <dd>' .( get_field('location_sun_open', $args ) && "00:00:00" != get_field('location_sun_open', $args ) ? '' . get_field('location_sun_open', $args) . ' &ndash; ' . get_field('location_sunn_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Monday</dt> <dd>' . ( get_field('location_mon_open', $args ) && "00:00:00" != get_field('location_mon_open', $args ) ? '' . get_field('location_mon_open', $args) . ' &ndash; ' . get_field('location_mon_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Tuesday</dt> <dd>' . ( get_field('location_tues_open', $args ) && "00:00:00" != get_field('location_tues_open', $args ) ? '' . get_field('location_tues_open', $args) . ' &ndash; ' . get_field('location_tues_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Wednesday</dt> <dd>' . ( get_field('location_wed_open', $args ) && "00:00:00" != get_field('location_wed_open', $args ) ? '' . get_field('location_wed_open', $args) . ' &ndash; ' . get_field('location_wed_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Thursday</dt> <dd>' . ( get_field('location_thurs_open', $args ) && "00:00:00" != get_field('location_thurs_open', $args ) ? '' . get_field('location_thurs_open', $args) . ' &ndash; ' . get_field('location_thurs_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Friday</dt> <dd>' . ( get_field('location_fri_open', $args ) && "00:00:00" != get_field('location_fri_open', $args ) ? '' . get_field('location_fri_open', $args) . ' &ndash; ' . get_field('location_fri_close', $args) . '' : 'Closed') . '</dd>';
						echo '<dt>Saturday</dt> <dd>' . ( get_field('location_sat_open', $args ) && "00:00:00" != get_field('location_sat_open', $args ) ? '' . get_field('location_sat_open', $args) . ' &ndash; ' . get_field('location_sat_close', $args) . '' : 'Closed') . '</dd>';
						echo '</dl>';
					endif; ?>				
			</div>
			<div class="col-12 col-md px-0 px-md-8 order-1 image">
				<picture>
					<!-- <source srcset="https://picsum.photos/630/473?image=1040 1x, https://picsum.photos/1260/945?image=1040 2x"
						media="(min-width: 1500px)">
					<source srcset="https://picsum.photos/400/300?image=1040 1x, https://picsum.photos/800/600?image=1040 2x"
						media="(min-width: 992px)">
					<source srcset="https://picsum.photos/992/558?image=1040 1x, https://picsum.photos/1984/1116?image=1040 2x"
						media="(min-width: 768px)">
					<source srcset="https://picsum.photos/768/432?image=1040 1x, https://picsum.photos/1536/864?image=1040 2x"
						media="(min-width: 576px)">  
						<source srcset="https://picsum.photos/576/324?image=1040 1x, https://picsum.photos/1152/648?image=1040 2x"
						media="(min-width: 1px)">
					<img src="https://picsum.photos/544/408?image=1040" alt="C. Lowry Barnes, M.D." /> -->
					<?php if ( has_post_thumbnail() ) { 
						 the_post_thumbnail('medium_large', ['class' => 'img-responsive']); 
						  } ?>
				</picture>
			</div>
		</div>
	</section>
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
	<?php if ( get_field('location_appointment')): ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="module-title">Appointments</h2>
				<div class="module-body">
					<?php echo get_field('location_appointment'); ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<section class="container-fluid p-8 p-sm-10 bg-auto" id="doctors">
		<div class="row">
			<div class="col-12">
				<h2 class="module-title">Doctors at <?php the_title(); ?></h2>

				<div class="list-legend">
					<div class="az-filter">
							<!-- All Filters:
								Disable any checkbox input that does not represent any conditions. Add "disabled" to input.
								
								A-Z Filter:
								Start with "All"/"Any" checkbox input checked. Add "checked" attribute to input.
								If user checks any other checkbox input, remove "checked" attribute from "All"/"Any" checkbox input.
								If user checks "All"/"Any" checkbox input, remove "checked" attribute from checkbox input of other characters.
							-->
							<?php  echo facetwp_display( 'facet', 'alpha' ); ?>
					</div>
				</div>
				<div class="card-list-container">
					<div class="card-list card-list-doctors">
						<?php echo facetwp_display( 'template', 'physician_cards' ); ?>
					</div>
				</div>
				<div class="list-pagination">
					<?php echo facetwp_display( 'pager' ); ?>
				</div>
			</div>
		</div>
	</section>
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
						 	endforeach; ?>
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

<?php

function partition( $list, $p ) {
    $listlen = count( $list );
    $partlen = floor( $listlen / $p );
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice( $list, $mark, $incr );
        $mark += $incr;
    }
    return $partition;
}
?>