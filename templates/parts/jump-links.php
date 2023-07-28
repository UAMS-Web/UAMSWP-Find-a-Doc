<?php
/*
 * Template Name: Jump Links Bar
 * 
 * Description: A template part that displays a list of links to sections lower on 
 * the page.
 * 
 * Required vars:
 * 	$jump_link_count // Count of the number of jump links items on the page
 * 
 * Optional vars:
 * 	$*_section_show
 * 	$*_section_submenu
 */

// Check if Jump Links section should be displayed

	$jump_link_count_min = 2; // How many links have to exist before displaying the list of jump links?

	if ( $jump_link_count >= $jump_link_count_min ) {
		$jump_links_section_show = true;
	} else {
		$jump_links_section_show = false;
	}

// Check/define variables

	// Get system settings for jump links (a.k.a. anchor links)

		$labels_jump_links_vars = isset($labels_jump_links_vars) ? $labels_jump_links_vars : uamswp_fad_labels_jump_links();
			$fad_jump_links_title = $labels_jump_links_vars['fad_jump_links_title']; // string

	// // Get labels
	// 
	// 	// Get system settings for provider labels
	// 	if ( !isset($provider_plural_name) || empty($provider_plural_name) ) {
	// 		$labels_provider_vars = isset($labels_provider_vars) ? $labels_provider_vars : uamswp_fad_labels_provider();
	// 			$provider_plural_name = $labels_provider_vars['provider_plural_name']; // string
	// 	}
	// 
	// 	// Get system settings for location labels
	// 	if ( !isset($location_plural_name) || empty($location_plural_name) ) {
	// 		$labels_location_vars = isset($labels_location_vars) ? $labels_location_vars : uamswp_fad_labels_location();
	// 			$location_plural_name = $labels_location_vars['location_plural_name']; // string
	// 	}
	// 
	// 	// Get system settings for location descendant item labels
	// 	if ( !isset($location_descendant_plural_name) || empty($location_descendant_plural_name) ) {
	// 		$labels_location_descendant_vars = isset($labels_location_descendant_vars) ? $labels_location_descendant_vars : uamswp_fad_labels_location_descendant();
	// 			$location_descendant_plural_name = $labels_location_descendant_vars['location_descendant_plural_name']; // string
	// 	}
	// 
	// 	// Get system settings for area of expertise labels
	// 	if ( !isset($expertise_plural_name) || empty($expertise_plural_name) ) {
	// 		$labels_expertise_vars = isset($labels_expertise_vars) ? $labels_expertise_vars : uamswp_fad_labels_expertise();
	// 			$expertise_plural_name = $labels_expertise_vars['expertise_plural_name']; // string
	// 	}
	// 
	// 	// Get system settings for area of expertise descendant item labels
	// 	if ( !isset($expertise_descendant_plural_name) || empty($expertise_descendant_plural_name) ) {
	// 		$labels_expertise_descendant_vars = isset($labels_expertise_descendant_vars) ? $labels_expertise_descendant_vars : uamswp_fad_labels_expertise_descendant();
	// 			$expertise_descendant_plural_name = $labels_expertise_descendant_vars['expertise_descendant_plural_name']; // string
	// 	}
	// 
	// 	// Get system settings for clinical resource labels
	// 	if ( !isset($clinical_resource_plural_name_attr) || empty($clinical_resource_plural_name_attr) ) {
	// 		$labels_clinical_resource_vars = isset($labels_clinical_resource_vars) ? $labels_clinical_resource_vars : uamswp_fad_labels_clinical_resource();
	// 			$clinical_resource_plural_name_attr = $labels_clinical_resource_vars['clinical_resource_plural_name_attr']; // string
	// 	}
	// 
	// 	// Get system settings for combined condition and treatment labels
	// 	if ( !isset($condition_treatment_plural_name) || empty($condition_treatment_plural_name) ) {
	// 		$labels_condition_treatment_vars = isset($labels_condition_treatment_vars) ? $labels_condition_treatment_vars : uamswp_fad_labels_condition_treatment();
	// 			$condition_treatment_plural_name = $labels_condition_treatment_vars['condition_treatment_plural_name']; // string
	// 	}

	// Get post type (or slug of fake subpage)

		$post_type = isset($post_type) ? $post_type : get_post_type();
		$current_fpage = isset($current_fpage) ? $current_fpage : '';
		$jump_links_key = $current_fpage ? $current_fpage : $post_type;

	// Create array for order and values of jump links list items
	
		/*
		 * The top-level keys should match the post type for the page (or the fake subpage 
		 * slug if it is a fake subpage)
		 * 
		 * The second-level keys should match the first part of the $*_section_show 
		 * variable name (e.g., 'clinical_bio' in '$clinical_bio_section_show')
		 * 
		 * The third-level keys must include the following:
		 * 	- 'text' (string)
		 * 	- 'href' (string)
		 * 
		 * The third-level keys may also include the following:
		 * 	- 'label' (string)
		 * 	- 'submenu' (array)
		 * 
		 * The value for the 'text' key should be the desired item link text.
		 * 
		 * The value for the 'href' key should be the ID of the item's associated section 
		 * on the page.
		 * 
		 * The value for the 'label' key should be the desired value of the accessible 
		 * label for the item link.
		 * 
		 * The value for the 'submenu' key should be an array with the same key 
		 * requirements and options as for the third-level keys.
		 */

		if ( $post_type == 'provider' ) {
			$jump_links_item_key = 'provider';
			$jump_links_items = array(
				'appointment' => array(
					'text'	=> 'Make an Appointment',
					'href'	=> 'appointment-info-1',
					'label'	=> ''
				),
				'clinical_bio' => array(
					'text'	=> 'About',
					'href'	=> 'clinical-info',
					'label'	=> ''
				),
				'podcast' => array(
					'text'	=> 'Podcast',
					'href'	=> 'podcast',
					'label'	=> ''
				),
				'clinical_resource' => array(
					'text'	=> 'Related Resources',
					'href'	=> 'related-resources',
					'label'	=> 'Jump to the section of this page about ' . $clinical_resource_plural_name_attr
				),
				'academic' => array(
					'text'	=> 'Academic Background',
					'href'	=> 'academic-info',
					'label'	=> ''
				),
				'research' => array(
					'text'	=> 'Research',
					'href'	=> 'research-info',
					'label'	=> ''
				),
				'condition_treatment' => array(
					'text'	=> $condition_treatment_plural_name,
					'href'	=> 'conditions-treatments',
					'label'	=> ''
				),
				'expertise' => array(
					'text'	=> $expertise_plural_name,
					'href'	=> 'expertise',
					'label'	=> ''
				),
				'location' => array(
					'text'	=> $location_plural_name,
					'href'	=> 'locations',
					'label'	=> ''
				),
				'ratings' => array(
					'text'	=> 'Ratings &amp; Reviews',
					'href'	=> 'ratings',
					'label'	=> ''
				)
			);
		} elseif ( $post_type == 'location' ) {
			$jump_links_item_key = 'location';
			$jump_links_items = array(
				'location_alert' => array(
					'text'	=> $location_alert_title ? $location_alert_title : 'Alert',
					'href'	=> 'location-alert',
					'label'	=> 'Jump to the section of this page with the alert regarding this ' . strtolower($location_single_name_attr)
				),
				'closing' => array(
					'text'	=> 'Closing Information',
					'href'	=> 'closing-info',
					'label'	=> 'Jump to the section of this page with the closing information'
				),
				'location_about' => array(
					'text'	=> $location_about_section_title_short,
					'href'	=> 'description',
					'label'	=> $location_about_section_label,
					'submenu' => array(
						'location_affiliation' => array(
							'text'	=> 'Affiliation',
							'href'	=> 'affiliation',
							'label'	=> 'Jump to the section of this page about Affiliation'
						),
						'prescription' => array(
							'text'	=> 'Prescription Information',
							'href'	=> 'prescription-info',
							'label'	=> 'Jump to the section of this page about Prescription Information'
						)
					)
				),
				'parking' => array(
					'text'	=> 'Parking Information',
					'href'	=> 'parking-info',
					'label'	=> 'Jump to the section of this page about Parking Information'
				),
				'appointment' => array(
					'text'	=> 'Appointment Information',
					'href'	=> 'appointment-info',
					'label'	=> 'Jump to the section of this page about Appointment Information'
				),
				'mychart_scheduling' => array(
					'text'	=> $location_scheduling_title,
					'href'	=> 'scheduling',
					'label'	=> 'Jump to the section of this page about scheduling an appointment in MyChart'
				),
				'telemedicine' => array(
					'text'	=> 'Telemedicine',
					'href'	=> 'telemedicine-info',
					'label'	=> 'Jump to the section of this page about Telemedicine Information'
				),
				'portal' => array(
					'text'	=> 'Telemedicine',
					'href'	=> 'telemedicine-info',
					'label'	=> 'Jump to the section of this page about Telemedicine Information'
				),
				'portal' => array(
					'text'	=> 'Patient Portal',
					'href'	=> 'portal',
					'label'	=> 'Jump to the section of this page about the Patient Portal'
				),
				'provider' => array(
					'text'	=> $provider_plural_name,
					'href'	=> 'providers',
					'label'	=> 'Jump to the section of this page about ' . $provider_plural_name_attr
				),
				'condition_treatment' => array(
					'text'	=> $condition_treatment_plural_name,
					'href'	=> 'conditions-treatments',
					'label'	=> 'Jump to the section of this page about ' . $condition_treatment_plural_name_attr
				),
				'expertise' => array(
					'text'	=> $expertise_plural_name,
					'href'	=> 'expertise',
					'label'	=> 'Jump to the section of this page about ' . $expertise_plural_name_attr
				),
				'location_descendant' => array(
					'text'	=> $location_descendant_plural_name . ' Within This ' . $location_single_name,
					'href'	=> 'sub-clinics',
					'label'	=> 'Jump to the section of this page about ' . $location_descendant_plural_name_attr . ' within this ' . strtolower($location_single_name_attr)
				),
				'clinical_resource' => array(
					'text'	=> $clinical_resource_plural_name,
					'href'	=> 'related-resources',
					'label'	=> 'Jump to the section of this page about ' . $clinical_resource_plural_name_attr
				)
			);
		} elseif ( $post_type == 'clinical-resource' ) {
			$jump_links_item_key = 'clinical_resource';
			$jump_links_items = array(
				'clinical_resource' => array(
					'text'	=> 'Related ' . $clinical_resource_plural_name,
					'href'	=> 'related-resources',
					'label'	=> 'Jump to the section of this page about related ' . $clinical_resource_plural_name_attr
				),
				'condition_treatment' => array(
					'text'	=> $condition_treatment_plural_name,
					'href'	=> 'conditions-treatments',
					'label'	=> 'Jump to the section of this page about related ' . $condition_treatment_plural_name_attr
				),
				'provider' => array(
					'text'	=> $provider_plural_name,
					'href'	=> 'providers',
					'label'	=> 'Jump to the section of this page about related ' . $provider_plural_name_attr
				),
				'location' => array(
					'text'	=> $location_plural_name,
					'href'	=> 'locations',
					'label'	=> 'Jump to the section of this page about related ' . $location_plural_name_attr
				),
				'expertise' => array(
					'text'	=> $expertise_plural_name,
					'href'	=> 'expertise',
					'label'	=> 'Jump to the section of this page about related ' . $expertise_plural_name_attr
				),
				'appointment' => array(
					'text'	=> 'Make an Appointment',
					'href'	=> 'appointment-info',
					'label'	=> 'Jump to the section of this page about making an appointment'
				)
			);
		} else {
			$jump_links_item_key = '';
			$jump_links_items = array();
			$jump_links_section_show = false;
		}

// Construct jump links section

	if ( $jump_links_section_show ) { ?>
		<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">
			<h2><?php echo $fad_jump_links_title; ?></h2>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#jump-link-nav" aria-controls="jump-link-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse inner-container" id="jump-link-nav">
				<ul class="nav navbar-nav">
					<?php

					foreach( $jump_links_items as $key => $item ) {

						$item_text = ( isset($item['text']) && !empty($item['text']) ) ? $item['text'] : '';
						$item_href = ( isset($item['href']) && !empty($item['href']) ) ? '#' . $item['href'] : '';
						$item_label = ( isset($item['label']) && !empty($item['label']) ) ? $item['label'] : '';
						$item_submenu = ( isset($item['submenu']) && is_array($item['submenu']) && !empty($item['submenu']) ) ? $item['submenu'] : array();
						${ $key . '_section_submenu' } = isset(${ $key . '_section_submenu' }) ? ${ $key . '_section_submenu' } : false;

						if (
							${ $key . '_section_show' }
							&& 
							$item_text
							&& 
							$item_href
						) {

							?>
							<li class="nav-item<?php echo $item_submenu ? ' dropdown' : ''; ?>">
								<a class="nav-link" href="<?php echo $item_href; ?>"<?php echo $item_label ? ' title="' . $item_label . '"' : ''; ?>><?php echo $item_text; ?></a><?php
								
								if (
									${ $key . '_section_submenu' }
									&&
									$item_submenu
								) {
									?>
									<ul class="dropdown-menu">
										<?php

										foreach( $item_submenu as $key => $item ) {

											$item_text = ( isset($item['text']) && !empty($item['text']) ) ? $item['text'] : '';
											$item_href = ( isset($item['href']) && !empty($item['href']) ) ? '#' . $item['href'] : '';
											$item_label = ( isset($item['label']) && !empty($item['label']) ) ? $item['label'] : '';
	
											if (
												${ $key . '_section_show' }
												&& 
												$item_text
												&& 
												$item_href
											) {

											}
											?>
											<li class="nav-item">
												<a class="nav-link" href="<?php echo $item_href; ?>"<?php echo $item_label ? ' title="' . $item_label . '"' : ''; ?>><?php echo $item_text; ?></a>
											</li>
										<?php

										} // endforeach
										
										?>
									</ul>
									<?php
								} // endif

								?>
							</li>
							<?php
						} // endif

					} // endforeach
					
					?>
				</ul>
			</div>
		</nav>
	<?php

	} // endif ( $jump_links_section_show )