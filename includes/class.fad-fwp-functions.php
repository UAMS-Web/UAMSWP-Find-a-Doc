<?php
/*
 * FacetWP functions
 */
// Filter to fix facetwp hash error
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    // if ( 'provider' == $query->get( 'post_type' ) ) {
		$is_main_query = false;
    // }
    return $is_main_query;
}, 10, 2 );

add_filter( 'facetwp_shortcode_html', function( $output, $atts) {
	if ( !empty( $atts['template'] ) && ( 'physician' == $atts['template'] || 'clinical_resources' == $atts['template']) ) { // replace 'example' with name of your template
        /** modify replacement as needed, make sure you keep the facetwp-template class **/
        $output = str_replace( 'facetwp-template', 'facetwp-template row list', $output );
	}
	 if ( !empty( $atts['template'] ) && ('locations' == $atts['template'] || 'expertise' == $atts['template']) ) {
        $output = str_replace( 'facetwp-template', 'facetwp-template card-list', $output );
    }
	return $output;
}, 10, 2 );

function fwp_disable_auto_refresh() {
    if ( is_post_type_archive( 'provider' ) || is_post_type_archive( 'location' ) || is_post_type_archive( 'clinical-resource' ) ) {
	?>
	<script>
	// (function($) {
	// 	$(function() {
	// 		if ('undefined' !== typeof FWP) {
	// 			FWP.auto_refresh = false;
	// 		}
	// 	});
	// })(jQuery);
    (function($) {
        $(document).on('change', '.facetwp-sort .facetwp-sort-select', function() {
            FWP.extras.sort = $(this).val();
            FWP.soft_refresh = true;
            FWP.autoload();
            FWP.refresh();
        });
    })(jQuery);
	</script>
<?php
    }
}
add_action( 'wp_footer', 'fwp_disable_auto_refresh', 100 );

// FacetWP scripts
function fwp_facet_scripts() {
    $classes = get_body_class();

	if ( is_post_type_archive( 'provider' ) || is_post_type_archive( 'location' ) || is_post_type_archive( 'clinical-resource' ) ) { ?>
        <script>
            (function($) {
                document.addEventListener('facetwp-loaded', function() {
                    $('.facetwp-facet').each(function() {
                        var facet_name = $(this).attr('data-name');
                        var facet_label = FWP.settings.labels[facet_name];
                        if ($('.facet-label[data-for="' + facet_name + '"]').length < 1) {
                            $(this).before('<h3 class="facet-label h6" id="facet_' + facet_name + '" data-for="' + facet_name + '">' + facet_label + '</h3>');
                        }
                    });
                    $('.fs-dropdown .fs-search input').each(function() {
                        $(this).attr('aria-label', "Search");
                    });
                    $('.facetwp-sort-select, .facetwp-dropdown').each(function() {
                        $(this).attr('aria-labelledby', "facet_" + $(this).closest('.facetwp-facet').attr('data-name') );
                    });
                    $('select.facetwp-sort-select').each(function() {
                        $(this).attr('title', "Choose sort order" );
                        $(this).removeAttr('aria-labelledby');
                    });
                });
            })(jQuery);
        </script>
<?php }

    if ( is_post_type_archive( 'provider' ) || is_post_type_archive( 'location' ) ) {
    $taxonomy_slug = isset(get_queried_object()->slug) ? get_queried_object()->slug : '';
?>
<script>
(function($) {
    document.addEventListener('facetwp-loaded', function() {
        $.each(FWP.settings.num_choices, function(key, val) {
            var $parent = $('.facetwp-facet-' + key).closest('.fwp-filter');
            (0 === val) ? $parent.hide() : $parent.show();
        });
        if ($('body').hasClass('tax-specialty')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-specialty_checkbox .facetwp-checkbox[data-value="<?php echo $taxonomy_slug; ?>"]').click();
				$('.specialty-filter').hide();
			}
        }
        if ($('body').hasClass('tax-medical_terms')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-terms_checkbox .facetwp-checkbox[data-value="<?php echo $taxonomy_slug; ?>"]').click();
				$('.terms-filter').hide();
			}
        }
        if ($('body').hasClass('tax-medical_procedures')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-procedures_checkbox .facetwp-checkbox[data-value="<?php echo $taxonomy_slug; ?>"]').click();
				$('.procedures-filter').hide();
			}
        }
        if ($('body').hasClass('tax-condition')) {
        	if (! FWP.loaded) {
	        	$('.facetwp-facet-condition_checkbox .facetwp-checkbox[data-value="<?php echo $taxonomy_slug; ?>"]').click();
				$('.condition-filter').hide();
			}
        }
        /*
        After FacetWP reloads, store any updates into a cookie
        */
        // var date = new Date();
        var facets = window.location.search;
        const params = new URLSearchParams(facets);
        locationregion = params.get("_location_region");
        providerregion = params.get("_provider_region");
        var region = '';
        // Is location region set
        if (null != locationregion && '' != locationregion) {
            region = locationregion;
        }
        // Is provider region set
        if (null != providerregion && '' != providerregion) {
            region = providerregion;
        }
        // If region is set, then write the cookie
        if (region && 'all' != region) {
            document.cookie = "wp_filter_region="+region+"; path=/; domain="+window.location.hostname;
        } else {
            document.cookie = 'wp_filter_region=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain='+window.location.hostname;
        }
    });
    $(document).on('facetwp-refresh', function() {
        if (! FWP.loaded) {
            //FWP.set_hash = function() { /* empty function */ }
            if ($('body').hasClass('tax-specialty')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.specialty-filter').hide();
            }
            if ($('body').hasClass('tax-medical_terms')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.terms-filter').hide();
            }
            if ($('body').hasClass('tax-medical_procedures')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.procedures-filter').hide();
            }
            if ($('body').hasClass('tax-condition')) {
            	FWP.set_hash = function() { /* empty function */ } // Exclude hash function
            	$('.condition-filter').hide();
            }
           /*
            When FacetWP first initializes, look for the "facetdata" cookie
            If it exists, set window.location.search= facetdata
            */
            var facets = window.location.search;
            const params = new URLSearchParams(facets);
            var region = '';
            var regionname = '';
        <?php if (is_post_type_archive( 'location' )) { ?>
            locationregion = params.get('_location_region');
            if (null != locationregion && '' != locationregion) {
                region = locationregion;
            }
            regionname = "_location_region";
        <?php } elseif (is_post_type_archive( 'provider' )) { ?>
            providerregion = params.get('_provider_region');
            if (null != providerregion && '' != providerregion) {
                region = providerregion;
            }
            regionname = "_provider_region";
        <?php } ?>
            var regiondata = readCookie('wp_filter_region');
            var fwpregionname = regionname.substring(1); // Remove _ from regionname
            if ('all' == region || 'all' == regiondata){
	            FWP.facets[fwpregionname] = []; // Remove regionname
	            FWP.setHash(); // set the new URL
	            document.cookie = 'wp_filter_region=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain='+window.location.hostname;
            }
            // No qs and cookie has value
            if ( 'all' != region && !region && null != regiondata && '' != regiondata ) {
	            FWP.facets[fwpregionname] = [regiondata];
                FWP.setHash();
                document.cookie = 'wp_filter_region=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain='+window.location.hostname;
                params.set(regionname, regiondata);
                window.location.search = `?${params}`;
            }
            // qs and cookie has value
            // else if ( region && null != regiondata && '' != regiondata ) {
            //     // FWP.facets[regionname] = [region];
            //     // FWP.setHash();
            //     // FWP.fetchData();
            //     document.cookie = 'wp_filter_region=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain='+window.location.hostname;
            //     // window.location.search = '_location_region='+regiondata;
            //     params.append(regionname, regiondata);
            //     // window.history.replaceState({}, '', `${location.pathname}?${params}`)
            //     window.location.search = `?${params}`;
            // }
            // QS & no location set
            else if ( facets && region && regiondata && region != regiondata ) {
                document.cookie = 'wp_filter_region=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain='+window.location.hostname;
            }
        }
    });
	$(function() {
        FWP.hooks.addAction('facetwp/refresh/alpha', function($this, facet_name) {
            FWP.facets[facet_name] = $this.find('.facetwp-alpha.selected').attr('data-id') || '';
        });
    });

    $(document).on('click', '.facetwp-alpha.available', function() {
        $parent = $(this).closest('.facetwp-facet');
        $parent.find('.facetwp-alpha').removeClass('selected');
        var facet_name = $parent.attr('data-name');
        $(this).addClass('selected');

        if ('' !== $(this).attr('data-id')) {
            FWP.frozen_facets[facet_name] = 'soft';
        }
        FWP.refresh();
    });
    /*
    Cookie handler
    */
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
})(jQuery);
</script>
<?php
    } elseif ( is_post_type_archive( 'clinical-resource' ) ) {
        ?>
<script>
(function($) {
   document.addEventListener('facetwp-loaded', function() {
        $('.facetwp-facet').each(function() {
            var facet_name = $(this).attr('data-name');
            var facet_label = FWP.settings.labels[facet_name];
            if ($('.facet-label[data-for="' + facet_name + '"]').length < 1) {
                $(this).before('<h3 class="facet-label h6" id="facet_' + facet_name + '" data-for="' + facet_name + '">' + facet_label + '</h3>');
            }
        });
    });
})(jQuery);
</script>
<?php
    }
}
add_action( 'wp_footer', 'fwp_facet_scripts', 100 );

// fix facets for 'all'
add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
	// Remove provider_region if 'all'
    if ( 'provider' == FWP()->helper->get_uri() ) {
        if ( !empty( $url_vars['provider_region'] ) && ['all'] == $url_vars['provider_region'] ) {
            unset($url_vars['provider_region']);
        }
    }
    // Remove location_region if 'all'
    if ( 'provider' == FWP()->helper->get_uri() ) {
        if ( !empty( $url_vars['location_region'] ) && ['all'] == $url_vars['location_region'] ) {
            unset($url_vars['location_region']);
        }
    }

    return $url_vars;
} );

// FacetWP Sort
add_filter( 'facetwp_sort_options', function( $options, $params ) {
    unset( $options['date_desc'] );
    unset( $options['date_asc'] );
	if ( is_post_type_archive( 'provider' ) || is_singular( 'provider' ) ) {
		$params = array(
		    'template_name' => 'physicians',
		);
        $options = [
            'default' => [
                'label' => __( 'Sort by', 'fwp' ),
                'query_args' => []
            ],
            'title_asc' => [
                'label' => __( 'Name (A-Z)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'ASC',
                ]
            ],
            'title_desc' => [
                'label' => __( 'Name (Z-A)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'DESC',
                ]
            ]
        ];
        // unset( $options['date_desc'] );
        // unset( $options['date_asc'] );
	} elseif ( is_post_type_archive( 'location' ) || is_singular( 'location' ) ) {
	 	$params = array(
		    'template_name' => 'locations',
		);
        $options = [
            'default' => [
                'label' => __( 'Sort by', 'fwp' ),
                'query_args' => []
            ],
            'title_asc' => [
                'label' => __( 'Name (A-Z)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'ASC',
                ]
            ],
            'title_desc' => [
                'label' => __( 'Name (Z-A)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'DESC',
                ]
            ]
        ];
        // unset( $options['date_desc'] );
        // unset( $options['date_asc'] );
	} elseif ( is_post_type_archive( 'clinical-resource' ) || is_singular( 'clinical-resource' ) ) {
        $params = array(
           'template_name' => 'clinical-resources',
        );
        $options = [
            'default' => [
                'label' => __( 'Sort by', 'fwp' ),
                'query_args' => []
            ],
            'date_desc' => [
                'label' => __( 'Date Added (Newest)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'date',
                    'order' => 'DESC',
                ]
            ],
            'date_asc' => [
                'label' => __( 'Date Added (Oldest)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'date',
                    'order' => 'ASC',
                ]
            ],
            'modified_desc' => [
                'label' => __( 'Date Modified (Newest)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'modified',
                    'order' => 'DESC',
                ]
            ],
            'modified_asc' => [
                'label' => __( 'Date Modified (Oldest)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'modified',
                    'order' => 'ASC',
                ]
            ],
            'title_asc' => [
                'label' => __( 'Title (A-Z)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'ASC',
                ]
            ],
            'title_desc' => [
                'label' => __( 'Title (Z-A)', 'fwp' ),
                'query_args' => [
                    'orderby' => 'title',
                    'order' => 'DESC',
                ]
            ]
        ];
   }
    //);
    return $options;
}, 10, 2 );

add_filter( 'facetwp_pager_html', function( $output, $params ) {
    $output = '';
    $page = $params['page'];
    $total_pages = $params['total_pages'];

    if ( 1 < $total_pages ) {

		$output .= '<nav aria-label="list pagination"><ul class="pagination">';

        // First Page
        if ( 3 < $page ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link first-page" title="First Page" data-page="1"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>';
        }

        // Previous page (NEW)
        if ( $page > 1 ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Previous Page" data-page="' . ($page - 1) . '"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>';
        }

        if ( 1 < ( $page - 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Page ' . ($page - 10) . '" data-page="' . ($page - 10) . '">' . ($page - 10) . '</a></li>';
        }
        for ( $i = 2; $i > 0; $i-- ) {
            if ( 0 < ( $page - $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Page ' . ($page - $i) . '" data-page="' . ($page - $i) . '">' . ($page - $i) . '</a></li>';
            }
        }

        // Current page
        $output .= '<li class="page-item"><a class="facetwp-page page-link active" title="Page ' . $page . '" data-page="' . $page . '">' . $page . '</a></li>';

        for ( $i = 1; $i <= 2; $i++ ) {
            if ( $total_pages >= ( $page + $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Page ' . ($page + $i) . '" data-page="' . ($page + $i) . '">' . ($page + $i) . '</a></li>';
            }
        }
        if ( $total_pages > ( $page + 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Page ' . ($page + 10) . '" data-page="' . ($page + 10) . '">' . ($page + 10) . '</a></li>';
        }

        // Next page (NEW)
        if ( $page < $total_pages ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" title="Next Page" data-page="' . ($page + 1) . '"><span class="fas fa-angle-right" aria-hidden="true"></span></a>';
        }

        // Last Page
        if ( $total_pages > ( $page + 2 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link last-page" title="Last Page" data-page="' . $total_pages . '"><span class="fas fa-fast-forward aria-hidden="true"></span></a></li>';
        }

		$output .= '</ul></nav>';

    }

    return $output;
}, 10, 2 );

// Show only Yes values
add_filter( 'facetwp_index_row', function( $params, $class ) {
    if ( 'primary_care' == $params['facet_name'] ) {
        $included_terms = array( 'Yes' );
        if ( ! in_array( $params['facet_display_value'], $included_terms ) ) {
            return false;
        }
    }
    return $params;
}, 10, 2 );
// Turn on FWP Accessibility features
add_filter( 'facetwp_assets', function( $assets ) {
    $assets['accessibility.js'] = FACETWP_URL . '/assets/js/src/accessibility.js';
    if ( !is_post_type_archive( 'provider' ) && !is_post_type_archive( 'location' ) && !is_post_type_archive( 'clinical-resource' ) ) {
        $assets['fwp-pager-scroll.js'] = UAMS_FAD_ROOT_URL . 'assets/js/fwp-pager-scroll.js';
    }
    return $assets;
});
add_filter( 'facetwp_load_a11y', '__return_true' );

add_filter( 'facetwp_index_row', function( $params, $class ) {
    if ($params){
        if ( 'resource_provider' == $params['facet_name'] ) {
            if ( ! empty( $params['facet_value'] ) ) {
                $post = get_post( (int) $params['facet_value'] );
                $post_id = $post->ID;
                $lastname = get_field( 'physician_last_name', $post_id );
                $firstname = get_field( 'physician_first_name', $post_id );
                $middlename = get_field( 'physician_middle_name', $post_id );
                $params['facet_value'] = sanitize_title_with_dashes( $lastname . ' ' . $firstname . ' ' . $middlename ); //$post->post_name;
                $params['facet_display_value'] = get_field( 'physician_full_name', $post_id );
            }
        } elseif ( 'resource_locations' == $params['facet_name'] ||
                'resource_aoe' == $params['facet_name'] ||
                'resource_conditions' == $params['facet_name'] ||
                'resource_treatments' == $params['facet_name'] ) {
            if ( ! empty( $params['facet_value'] ) ) {
                $post = get_post( (int) $params['facet_value'] );
                $params['facet_value'] = $post->post_name;
            }
        }
    }
    return $params;
}, 10, 2 );

/** Cron Indexer **/
function fwp_cron_index() {
    FWP()->indexer->index();
}
add_action( 'fwp_indexer', 'fwp_cron_index' );
// FacetWP Cron //
// Add function to register event to WordPress init
add_action( 'init', 'register_hourly_fwp_indexer');

// Function which will register the event
function register_hourly_fwp_indexer() {
	// Make sure this event hasn't been scheduled
	if( !wp_next_scheduled( 'fwp_indexer' ) ) {
		// Schedule the event
		wp_schedule_event( time(), 'hourly', 'fwp_indexer' );
	}
}
