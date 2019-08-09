<?php
	/**
	 *  Template Name: People
	 *  Designed for people single
	 */


	get_header();
	$sidebar = get_post_meta($post->ID, "sidebar");
	$breadcrumbs = get_post_meta($post->ID, "breadcrumb");
 ?>
	<?php //get_template_part( 'header', 'image' ); ?>

	<div class="container uams-body">

	  <div class="row">

	    <div class="col-md-12 uams-content" role='main'>

	      <?php //uams_page_title(); ?>

	      <?php //get_template_part( 'menu', 'mobile' ); ?>

	      <?php
		      if((!isset($breadcrumbs[0]) || $breadcrumbs[0]!="on")) {
		      	//get_template_part( 'breadcrumbs' );
		      }
		  ?>

	      <div id='main_content' class="uams-body-copy" tabindex="-1">
		    <div itemscope itemtype="http://schema.org/Physician">
			<div class="margin-bottom-one search-box-lg"><?php echo do_shortcode( '[wpdreams_ajaxsearchpro id=1]' ); ?></div>
			<div class="row">
				<div class="col-md-9 col-sm-12 col-xs-12" style="float:right;">
					<div class="row">
						<div class="col-md-8">
							<h2 class="title-heading-left margin-top-none"><?php echo get_field('physician_first_name'); ?> <?php echo (get_field('physician_middle_name') ? get_field('physician_middle_name') : ''); ?> <?php echo get_field('physician_last_name'); ?><?php echo (get_field('physician_degree') ? ', ' . get_field('physician_degree') : ''); ?></h2>
								<?php //echo (get_field('physician_clinical_title') ? '<h4>' . get_field('physician_clinical_title') .'</h4>' : ''); ?>
								<?php
									if(! empty( get_field('physician_clinical_title') ) || ! empty( get_field('physician_department') ) ){
										echo '<h4>';
										echo (get_field('physician_clinical_title') ? get_field('physician_clinical_title')->name : '');
										echo ((! empty( get_field('physician_clinical_title') )) && (! empty( get_field('physician_department') ) ) ? ', ' : '' );
										echo (get_field('physician_department') ? get_field('physician_department')->name : '');
										echo '</h4>';
									}
								?>
						</div>
						<div class="col-md-4 margin-bottom-two">
							<div>
								<a class="uams-btn btn-md" target="_self" title="Make an Appointment" href="<?php echo get_field('physician_appointment_link'); ?>">Make an Appointment</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="row">
						<div class="col-sm-4 col-md-12 margin-bottom-one">
							<span  itemprop="name" class="hidden"><?php echo get_field('physician_first_name'); ?> <?php echo (get_field('physician_middle_name') ? get_field('physician_middle_name') : ''); ?> <?php echo get_field('physician_last_name'); ?><?php echo (get_field('physician_degree') ? ', ' . get_field('physician_degree') : ''); ?></span>
	                    	<?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image' ) ); ?>
							<div class="margin-top-one">
							<?php if(get_field('physician_youtube_link')) { ?>
								<a class="uams-btn btn-red btn-play btn-md" target="_self" title="Watch Video" href="<?php echo get_field('physician_youtube_link'); ?>">Watch Video</a>
							<?php } ?>							
								<a class="uams-btn btn-blue btn-plus btn-md" target="_self" title="Visit MyChart" href="https://mychart.uamshealth.com/">MyChart</a>
							</div>
						</div>
						<div class="col-sm-8 col-md-12">
	                	<?php
			                if(get_field('physician_npi')) {

								$npi =  get_field( 'physician_npi' );
								$request = wp_nrc_cached_api( $npi );

								$data = json_decode( $request );

								if( ! empty( $data ) ) {

									$rating_valid = $data->valid;

									if ( $rating_valid ){
	 									echo '<div class="star-rating" itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating">';
										// echo '<div class="ds-title">Patient Rating</div>';
										echo '<div><span class="ds-stars ds-stars'. $data->profile->averageStarRatingStr .'"></span> <span class="ds-average" itemprop="ratingValue"> '. $data->profile->averageRatingStr .'</span><span class="ds-average-max">out of 5</span></div>';
										// echo '<div class="ds-xofy"></div>';
										echo '<div class="ds-ratings"><a href="#tab-overview" title="Patient Ratings" class="js-link-to-tab"><span class="ds-ratingcount" itemprop="ratingCount">'. $data->profile->reviewcount .'</span> Patient Satisfaction Ratings</a></div>';
										echo '<div class="ds-comments2"><a href="#tab-overview" class="js-link-to-tab" title="Patient Ratings"><span class="ds-commentcount" itemprop="reviewCount">'. $data->profile->bodycount .'</span> Patient Comments</a></div>';
	 									echo '</div>';
										//echo '<a href="' . esc_url( $rating->info->link ) . '">' . $product->info->title . '</a>';
									} else { ?>
										<div class="star-rating">
										<!-- <div class="ds-title">Patient Rating</div> -->
										<div><span class="ds-stars ds-stars0"></span> No ratings</div>
										<div class="small bold">0 Patient Satisfaction Ratings<br/>0 Patient Comments</div>
										<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?">Why Not?</a></div>
										</div>
									<?php
									}
								}
							} else { ?>
								<div class="star-rating">
								<!-- <div class="ds-title">Patient Rating</div> -->
								<div><span class="ds-stars ds-stars0"></span> No ratings</div>
								<div class="small"><strong>0</strong> Patient Satisfaction Ratings<br/><strong>0</strong> Patient Comments</div>
								<div><a href="#" class="js-modal" data-modal-close-text="Close" data-modal-close-title="Close this window" data-modal-content-id="why_not_modal" data-modal-title="Why Not?">Why Not?</a></div>
								</div>
						<?php }

						$location = new WP_Query( array(
						    'relationship' => array(
						        'id'   => 'physicians_to_locations',
						        'from' => get_the_ID(), // You can pass object ID or full object
							),
							'posts_per_page' => 2,
						    //'nopaging'     => true,
						) );
						$l = 1;
						if( $location ): ?>
						<h5><i class="fa fa-medkit"></i> Primary Location</h5>
							<?php while ( $location->have_posts() && ($l < 2) ) : $location->the_post(); ?>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								    <p><?php echo get_field('location_address_1', $args, get_the_ID() ); ?><br/>
								    <?php echo ( get_field('location_address_2', $args ) ? get_field('location_address_2', $args) . '<br/>' : ''); ?>
									<?php echo get_field('location_city', $args); ?>, <?php echo get_field('location_state', $args); ?> <?php echo get_field('location_zip', $args, get_the_ID()); ?>
									<?php $map = get_field('location_map'); ?>
									<br /><a class="uams-btn btn-red btn-sm btn-external" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['latitude'] ?>,<?php echo $map['longitude'] ?>" target="_blank">Directions</a>
									<?php $l++; ?>
							<?php endwhile;
								wp_reset_postdata(); ?>
						<?php endif; ?>
						</div>
					</div>
	            </div>
				<div class="col-md-9">
	                <div class="js-tabs tabs__uams">
		                <ul class="js-tablist tabs__uams_ul" data-hx="h2">
			                <?php if(get_field('medical_specialties')||get_field('physician_conditions')||(get_field('physician_npi') && $rating_valid)): ?>
	                    	<li class="js-tablist__item tabs__uams__li">
	                    		<a href="#tab-overview" id="label_tab-overview" class="js-tablist__link tabs__uams__a" data-toggle="tab">
	                            	Overview
	                            </a>
	                        </li>
	                        <?php endif; ?>
							<?php if(get_field('physician_clinical_bio')): ?>
	                    	<li class="js-tablist__item tabs__uams__li">
	                    		<a href="#tab-about" id="label_tab-about" class="js-tablist__link tabs__uams__a" data-toggle="tab">
	                            	About
	                            </a>
	                        </li>
	                        <?php endif; ?>
	                        <?php if(get_field('physician_academic_appointment')||get_field('physician_education')||get_field('physician_boards')||get_field('physician_publications')||get_field('physician_pubmed_author_id')||get_field('physician_research_profiles_link')): ?>
	                        <li class="js-tablist__item tabs__uams__li">
	                            <a href="#tab-academics" id="label_tab-academics" class="js-tablist__link tabs__uams__a" data-toggle="tab">
		                            Academics
		                        </a>
	                        </li>
	                        <?php endif; ?>
	                        <li class="js-tablist__item tabs__uams__li">
	                            <a href="#tab-location" id="label_tab-location" class="js-tablist__link tabs__uams__a" data-toggle="tab">
		                            Location<?php echo($l > 1 ? 's' : '');?>
		                        </a>
	                        </li>
	                    <?php if( !empty(get_field('physician_research_bio')) || !empty(get_field('physician_research_interests'))): ?>
	                    	<li class="js-tablist__item tabs__uams__li">
	                            <a href="#tab-research" id="label_tab-research" class="js-tablist__link tabs__uams__a" data-toggle="tab">
		                            Research
		                        </a>
	                        </li>
	                    <?php endif; ?>
                        </ul>
                        <div class="uams-tab-content">
	                        <?php if(get_field('physician_clinical_bio')||get_field('medical_specialties')||get_field('physician_conditions')||(get_field('physician_npi') && $rating_valid)): ?>
	                        <div id="tab-overview" class="js-tabcontent tabs__uams__tabcontent">
								<?php echo (get_field('physician_department') ? '<p><strong>Medical Department</strong>: ' . get_field('physician_department')->name . '</p>' : ''); ?>
								<?php echo ('<p><strong>Primary Care</strong>: '. (get_field('physician_primary_care') ? 'Yes' : 'No') . '</p>'); ?>
								<?php // load all 'specialties' terms for the post
									$patients = get_field('physician_patient_types');
									if( $patients ): 
										$numItems = count($patients);
										$i = 0;
									?>
									<p><strong>Patient Type<?php echo($numItems > 1 ? 's' : '');?></strong>: 
										<?php foreach( $patients as $patient ): ?>
											<?php $patient_name = get_term( $patient, 'patient_type');
												echo $patient_name->name;
												if(++$i !== $numItems) {
													echo ", ";
												}
											?>
										<?php endforeach; ?>
									</p>
								<?php endif; ?>
								<?php // load all 'specialties' terms for the post
									$languages = get_field('physician_languages');
									if( $languages ): 
										$numItems = count($languages);
										$i = 0;
									?>
									<p><strong>Language<?php echo($numItems > 1 ? 's' : '');?></strong>: 
										<?php foreach( $languages as $language ): ?>
											<?php $language_name = get_term( $language, 'languages');
												echo $language_name->name;
												if(++$i !== $numItems) {
													echo ", ";
												}
											?>
										<?php endforeach; ?>
									</p>
								<?php endif; ?>
			                    <?php // load all 'specialties' terms for the post
									$specialties = get_field('medical_specialties');

									// we will use the first term to load ACF data from
									if( $specialties ): ?>
									<h3>Specialties</h3>
										<ul>
											<?php foreach( $specialties as $specialty ): ?>
												<li>
													<a href="<?php echo get_term_link( $specialty ); ?>">
													<?php $specialty_name = get_term( $specialty, 'specialty');
														echo $specialty_name->name;
													?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul>
								<?php endif; ?>
								<?php // load all 'specialties' terms for the post
									$conditions = get_field('physician_conditions');

									// we will use the first term to load ACF data from
									if( $conditions ): ?>
								<h3>Conditions &amp; Treatments</h3>
									<p><em>UAMS doctors treat a broad range of conditions some of which may not be listed below.</em></p>
										<ul>
											<?php foreach( $conditions as $condition ): ?>
												<li>
													<?php $condition_name = get_term( $condition, 'condition');
														echo $condition_name->name;
													?>
												</li>
											<?php endforeach; ?>
										</ul>
								<?php endif; ?>
								<?php // load all 'specialties' terms for the post
									//$procedures = get_field('medical_procedures');

									// we will use the first term to load ACF data from
									//if( $procedures ): ?>
<!--
									<h3>Tests/Procedures Performed</h3>
									<p><em>The following list represents some, but not all, of the procedures offered by this doctor.</em></p>
										<ul>
											<?php foreach( $procedures as $procedure ): ?>
												<li>
													<?php $procedure_name = get_term( $procedure, 'medical_procedures');
														echo $procedure_name->name;
													?>
												</li>
											<?php endforeach; ?>
										</ul>
-->
								<?php //endif; ?>
								<?php if(get_field('physician_npi') && $rating_valid) { ?>
								<div id="div_PatientRatings">
                                	<h2 id="PatientRatings">Patient Ratings &amp; Reviews</h2>
									<div class="ds-breakdown"></div>
									<div class="ds-comments" data-ds-pagesize="10"></div>
                            	</div>
								<?php } ?>
							</div>
						<?php endif; ?>
						<?php if(get_field('physician_clinical_bio')||!empty (get_field('physician_awards')) || get_field('physician_additional_info')): ?>
	                        <div id="tab-about" class="js-tabcontent tabs__uams__tabcontent">
			                    <?php echo get_field('physician_clinical_bio'); ?>
							<?php	
								$awards = get_field('physician_awards');
								if(! empty( $awards ) ): ?>
	                            	<h3>Awards</h3>
								    <ul>
								    <?php foreach ( $awards as $award ) { ?>
								        <li><?php echo $award['award_title']; ?> (<?php echo $award['award_year']; ?>)<?php echo ($award['award_infor'] ? '<br/>' . $award['award_infor'] : ''); ?></li>
								    <?php } ?>
								    </ul>
								<?php endif;
									if(get_field('physician_additional_info'))
									{
										echo get_field('physician_additional_info');
									}
								?>
							</div>
						<?php endif; ?>
						<?php if(get_field('physician_academic_appointment')||get_field('physician_education')||get_field('physician_boards')||get_field('physician_publications')||get_field('physician_pubmed_author_id')||get_field('physician_research_profiles_link')): ?>
	                        <div id="tab-academics" class="js-tabcontent tabs__uams__tabcontent">
	                            <?php
	                            	$academic_appointments = get_field('physician_academic_appointment');
	                            	if( !empty ( $academic_appointments ) ): ?>
	                            	<h3>Academic Appointments</h3>
								    <ul>
								    <?php foreach( $academic_appointments as $academic_appointment ):
								    		$academic_department_name = get_term($academic_appointment['academic_department'], 'academic_department');
								    	?>
								        <li><?php echo $academic_appointment['academic_title']; ?>, <?php echo $academic_department_name->name; ?></li>
								    <?php endforeach; ?>
								    </ul>
								<?php endif; ?>
								<?php
									$schools = get_field('physician_education');
								 	if( !empty ( $schools ) ): ?>
	                            	<h3>Education</h3>
								    <ul>
								    <?php foreach( $schools as $school ):
								    	$school_name = get_term( $school['physician_education_school'], 'schools');
								    	$education_type = get_term($school['physician_education_type'], 'educationtype');
								    ?>
								        <li><?php echo $education_type->name; ?> - <?php echo ($school['physician_education_description'] ? '' . $school['physician_education_description'] .'<br/>' : ''); ?><?php echo $school_name->name; ?></li>
								    <?php endforeach; ?>
								    </ul>
								<?php //endif; ?>
								<?php
								// $specialties = get_field('medical_specialties');

									// we will use the first term to load ACF data from
									// if( $specialties ): ?>
									<!-- <h3>Specialties</h3>
										<ul>
											<?php foreach( $specialties as $specialty ): ?>
												<li>
													<a href="<?php echo get_term_link( $specialty ); ?>">
													<?php $specialty_name = get_term( $specialty, 'specialty');
														echo $specialty_name->name;
													?>
													</a>
												</li>
											<?php endforeach; ?>
										</ul> -->
								<?php endif;
									$boards = get_field( 'physician_boards' );
									if( ! empty( $boards ) ): ?>
	                            	<h3>Professional Certifications</h3>
								    <ul>
								    <?php foreach ( $boards as $board ) :
								    		$board_name = get_term( $board, 'boards'); ?>
								        		<li><?php echo $board_name->name; ?></li>
								        	<?php // }; ?>
								    <?php endforeach; ?>
								    </ul>
								<?php endif; ?>
								<?php
									$publications = get_field('physician_publications');
									if( !empty ( $publications ) ): ?>
	                            	<h3>Selected Publications</h3>
								    <ul>
								    <?php foreach( $publications as $publication ): ?>
								        <li><?php echo $publication['publication_pubmed_info']; ?></li>
								    <?php endforeach; ?>
								    </ul>
								<?php endif; ?>
								<?php if( get_field('physician_pubmed_author_id') ): ?>
									<?php
										$pubmedid = trim(get_field('physician_pubmed_author_id'));
										$pubmedcount = (get_field('pubmed_author_number') ? get_field('pubmed_author_number') : '3')

									?>
	                            	<h3>Latest Publications</h3>
								    <?php echo do_shortcode( '[pubmed terms="' . urlencode($pubmedid) .'%5BAuthor%5D" count="' . $pubmedcount .'"]' ); ?>
								<?php endif; ?>
								<?php if( get_field('physician_research_profiles_link') ): ?>
	                            	More information is available on <a href="<?php echo get_field('physician_research_profiles_link'); ?>">UAMS Profile Page</a>
								<?php endif; ?>
	                        </div>
	                    <?php endif; ?>
	                        <div id="tab-location" class="js-tabcontent tabs__uams__tabcontent">
	                            <?php

	                            $physician_locations = new WP_Query( array(
								    'relationship' => array(
								        'id'   => 'physicians_to_locations',
								        'from' => get_the_ID(), // You can pass object ID or full object
								    ),
								    'nopaging'     => true,
								) );

								if( !empty($physician_locations) ): ?>
									<div style="width:100%; height:480px;" id="map"></div>
									<script type='text/javascript'>
										/*-- Function to create encode SVG  --*/
										/* colors needd to be hex code without # */
										// createSVGIcon("9d2235", "222", "transparentwhite", "1");
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
										var markers = [
											// example [ 34.74376029995541, -92.31828863640054, "00F","000","transparentwhite","A","I am a blue icon." ],
											<?php $i = 1; ?>
											<?php while ( $physician_locations->have_posts() ) : $physician_locations->the_post(); // variable must be called $post (IMPORTANT) ?>
											<?php $map = get_field('location_map'); ?>
											[ <?php echo $map['latitude']; ?>, <?php echo $map['longitude'] ?>, "9d2235","222", "transparentwhite", "<?php echo $i ?>", '<strong><?php the_title() ?></strong><br /><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map["latitude"] ?>,<?php echo $map["longitude"] ?>" target="_blank">Directions</a>' ],
											<?php $i++; ?>
											<?php endwhile; ?>
										]
										var map = new L.Map('map', {center: new L.LatLng(<?php echo $map['latitude']; ?>, <?php echo $map['longitude'] ?>), zoom: 16 });
										<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
										map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
										// for all possible values and explanations see "Template Parameters" in https://msdn.microsoft.com/en-us/library/ff701716.aspx
										var imagerySet = "Road"; // AerialWithLabels | Birdseye | BirdseyeWithLabels | Road
										var bing = new L.BingLayer("AnCRy0VpPMDzYT6rOBqqqCNvNbUWvdSOv8zrQloRCGFnJZU28JK3c6cQkCMAHtCd", {type: imagerySet});
										map.addLayer(bing);
										/* [lat, lon, fillColor, strokeColor, labelClass, iconText, popupText] */

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
                                    <ol>
                                        <?php while ( $physician_locations->have_posts() ) : $physician_locations->the_post(); ?>
                                        <li itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
								        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								            <p><span itemprop="streetAddress"><?php echo get_field('location_address_1', $args, get_the_ID() ); ?><br/>
								            <?php echo ( get_field('location_address_2', $args ) ? get_field('location_address_2', $args) . '<br/>' : ''); ?></span>
								            <span itemprop="addressLocality"><?php echo get_field('location_city', $args); ?></span>, <span itemprop="addressRegion"><?php echo get_field('location_state', $args); ?></span> <span itemprop="postalCode"><?php echo get_field('location_zip', $args, get_the_ID()); ?></span><br/>
								            <span itemprop="telephone"><?php echo get_field('location_phone', $args); ?></span>
								            <?php echo ( get_field('location_fax', $args) ? '<br/>Fax: ' . get_field('location_fax', $args) . '' : ''); ?>
								            <?php echo ( get_field('location_email', $args ) ? '<br/><a href="mailto:"' . get_field('location_email', $args ) . '">' . get_field('location_email', $args ) . '</a>' : ''); ?>
								            <?php echo ( get_field('location_web_name', $args ) ? '<br/><a href="' . get_field( 'location_url', $args ) . '">' . get_field('location_web_name', $args ) . '</a>' : ''); ?>
								            <br /><a href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['latitude'] ?>,<?php echo $map['longitude'] ?>" target="_blank">Directions</a> [Opens in New Window]
								        </p>
                                        </li>
								    <?php endwhile; ?>
                                    </ol>
								    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
								<?php endif; ?>
	                        </div>
	                        <?php if( !empty(get_field('physician_research_bio')) || !empty(get_field('physician_research_interests')) ): ?>
	                        <div id="tab-research" class="js-tabcontent tabs__uams__tabcontent">
	                            <?php
		                            if(get_field('physician_research_bio'))
									{
										echo get_field('physician_research_bio');
									}
		                        ?>
								<?php
									if(get_field('physician_research_interests'))
									{ ?>
									<h3>Research Interests</h3>
								<?php
										echo get_field('physician_research_interests');
									}
								?>
								<?php
									if(get_field('physician_research_profiles_link'))
									{ ?>
										<a href="<?php echo get_field('physician_research_profiles_link'); ?>" target="_blank">UAMS TRI Profiles</a>
								<?php }
								?>
	                        </div>
	                    <?php endif; ?>
	            		</div><!-- uams-tab-content -->
	                </div><!-- js-tabs -->
	    		</div><!-- col-md-9 -->
	    		<?php if( get_field('physician_npi') && $rating_valid ) { ?>
	    		<script src="https://www.docscores.com/widget/v2/uams/npi/<?php echo get_field( 'physician_npi' ); ?>/lotw.js" async=""></script>
	    		<?php } ?>
	    		<script src="<?php echo get_template_directory_uri(); ?>/js/uams.tabs.min.js" type="text/javascript"></script>
	    		<script type="text/javascript">
						(function ($, window) {

						var intervals = {};
						var removeListener = function(selector) {

							if (intervals[selector]) {

								window.clearInterval(intervals[selector]);
								intervals[selector] = null;
							}
						};
						var found = 'waitUntilExists.found';

						/**
						 * @function
						 * @property {object} jQuery plugin which runs handler function once specified
						 *           element is inserted into the DOM
						 * @param {function|string} handler
						 *            A function to execute at the time when the element is inserted or
						 *            string "remove" to remove the listener from the given selector
						 * @param {bool} shouldRunHandlerOnce
						 *            Optional: if true, handler is unbound after its first invocation
						 * @example jQuery(selector).waitUntilExists(function);
						 */

						$.fn.waitUntilExists = function(handler, shouldRunHandlerOnce, isChild) {

							var selector = this.selector;
							var $this = $(selector);
							var $elements = $this.not(function() { return $(this).data(found); });

							if (handler === 'remove') {

								// Hijack and remove interval immediately if the code requests
								removeListener(selector);
							}
							else {

								// Run the handler on all found elements and mark as found
								$elements.each(handler).data(found, true);

								if (shouldRunHandlerOnce && $this.length) {

									// Element was found, implying the handler already ran for all
									// matched elements
									removeListener(selector);
								}
								else if (!isChild) {

									// If this is a recurring search or if the target has not yet been
									// found, create an interval to continue searching for the target
									intervals[selector] = window.setInterval(function () {

										$this.waitUntilExists(handler, shouldRunHandlerOnce, true);
									}, 500);
								}
							}

							return $this;
						};

						}(jQuery, window));
					(function($) {
						$(document).ready(function(){
							$('.ds-average').waitUntilExists( function(){

								$('.ds-summary').attr('itemtype', 'http://schema.org/AggregateRating');
								$('.ds-summary').attr('itemprop', 'aggregateRating');
								$('.ds-summary').attr('itemscope', '');
								$('.ds-average').attr('itemprop', 'ratingValue');
								$('.ds-ratingcount').attr('itemprop', 'ratingCount');
								// $('.ds-comments').wrapInner('<a href="#PatientRatings"></a>');
							});
						});
					})(jQuery);
		    	</script>
		    	<link rel="stylesheet" type="text/css" href="https://www.docscores.com/resources/css/docscores-lotw.v1330-2018121714.css" />
				<?php wp_reset_query(); ?>
			</div>

		    </div><!-- #Physician Schema -->
			<?php if ( ! get_field('physician_npi') || ! $rating_valid ) { ?>
			<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-modal.min.js"></script>
			<div id="why_not_modal" class="hidden">
				There is no publicly available rating for this medical professional for one of two reasons: 1) he or she does not see patients or 2) he or she sees patients but has not yet received the minimum number of Patient Satisfaction Reviews. To be eligible for display, we require a minimum of 30 surveys. This ensures that the rating is statistically reliable and a true reflection of patient satisfaction.
			</div>
			<?php } ?>
		  </div><!-- #main_content -->

    	</div><!-- uams-content -->

		<div id="sidebar"></div>

  </div><!-- row -->

</div>

<?php get_footer(); ?>
