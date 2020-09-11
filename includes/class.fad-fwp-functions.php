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
	if ( !empty( $atts['template'] ) && 'physician' == $atts['template'] ) { // replace 'example' with name of your template
        /** modify replacement as needed, make sure you keep the facetwp-template class **/
        $output = str_replace( 'facetwp-template', 'facetwp-template row list', $output );
	}
	 if ( !empty( $atts['template'] ) && ('locations' == $atts['template'] || 'expertise' == $atts['template']) ) {
        $output = str_replace( 'facetwp-template', 'facetwp-template card-list', $output );
    }
	return $output;
}, 10, 2 );

function fwp_disable_auto_refresh() {
    if ( is_post_type_archive( 'provider' ) || is_post_type_archive( 'location' ) ) {
	?>
	<script>
	(function($) {
		$(function() {
			if ('undefined' !== typeof FWP) {
				FWP.auto_refresh = false;
			}
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

	if ( is_post_type_archive( 'provider' ) || is_post_type_archive( 'location' ) ) {
    $taxonomy_slug = isset(get_queried_object()->slug) ? get_queried_object()->slug : '';
?>
<script>
(function($) {
    $(document).on('facetwp-loaded', function() {
        $('.facetwp-facet').each(function() {
            var facet_name = $(this).attr('data-name');
            var facet_label = FWP.settings.labels[facet_name];
            if ($('.facet-label[data-for="' + facet_name + '"]').length < 1) {
                $(this).before('<h3 class="facet-label h6" id="facet_' + facet_name + '" data-for="' + facet_name + '">' + facet_label + '</h3>');
            }
        });
        $('.fs-dropdown .fs-search input').each(function() {
            $(this).attr('aria-labelledby', "facet_" + $(this).closest('.facetwp-facet').attr('data-name') );
        });
        $('select .facetwp-dropdown').each(function() {
            $(this).attr('aria-labelledby', "facet_" + $(this).closest('.facetwp-facet').attr('data-name') );
        });
        $('select.facetwp-sort-select').each(function() {
            $(this).attr('title', "Choose sort order" );
        });
    });
    $(document).on('facetwp-loaded', function() {
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
        if (FWP.loaded) {
            $('html, body').animate({
                scrollTop: $('main').offset().top
            }, 500);
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
        // FWP.refresh();
    });
})(jQuery);
</script>
<?php
    }
}
add_action( 'wp_footer', 'fwp_facet_scripts', 100 );

// FacetWP Sort
add_filter( 'facetwp_sort_options', function( $options, $params ) {
	if ( is_post_type_archive( 'provider' ) || is_singular( 'provider' ) ) {
		$params = array(
		    'template_name' => 'physicians',
		);
	    $options['name_asc'] = array(
	        'label' => __( 'Name (A-Z)', 'fwp' ),
	        'query_args' => array(
	            'orderby' => 'meta_value',
				'meta_key' => 'physician_full_name',
				'order' => 'ASC',
	        )
	    );
	    $options['name_desc'] = array(
	        'label' => __( 'Name (Z-A)', 'fwp' ),
	        'query_args' => array(
	            'orderby' => 'meta_value',
				'meta_key' => 'physician_full_name',
	            'order' => 'DESC',
	        )
	    );
	    unset( $options['title_asc'] );
     	unset( $options['title_desc'] );
	 } elseif ( is_post_type_archive( 'location' ) || is_singular( 'location' ) ) {
	 	$params = array(
		    'template_name' => 'locations',
		);
	 }
    //);
     unset( $options['date_desc'] );
     unset( $options['date_asc'] );
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
            $output .= '<li class="page-item"><a class="facetwp-page page-link first-page" data-page="1"><span class="fas fa-fast-backward" aria-hidden="true"></span></a></li>';
        }
        
        // Previous page (NEW)
        if ( $page > 1 ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - 1) . '"><span class="fas fa-angle-left" aria-hidden="true"></span></a></li>';
        }
        
        if ( 1 < ( $page - 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - 10) . '">' . ($page - 10) . '</a></li>';
        }
        for ( $i = 2; $i > 0; $i-- ) {
            if ( 0 < ( $page - $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page - $i) . '">' . ($page - $i) . '</a></li>';
            }
        }

        // Current page
        $output .= '<li class="page-item"><a class="facetwp-page page-link active" data-page="' . $page . '">' . $page . '</a></li>';

        for ( $i = 1; $i <= 2; $i++ ) {
            if ( $total_pages >= ( $page + $i ) ) {
                $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + $i) . '">' . ($page + $i) . '</a></li>';
            }
        }
        if ( $total_pages > ( $page + 10 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + 10) . '">' . ($page + 10) . '</a></li>';
        }

        // Next page (NEW)
        if ( $page < $total_pages ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link" data-page="' . ($page + 1) . '"><span class="fas fa-angle-right" aria-hidden="true"></span></a>';
        }
        
        // Last Page
        if ( $total_pages > ( $page + 2 ) ) {
            $output .= '<li class="page-item"><a class="facetwp-page page-link last-page" data-page="' . $total_pages . '"><span class="fas fa-fast-forward aria-hidden="true"></span></a></li>';
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
    if ( !is_post_type_archive( 'provider' ) && !is_post_type_archive( 'location' ) ) {
        $assets['fwp-pager-scroll.js'] = UAMS_FAD_ROOT_URL . 'assets/js/fwp-pager-scroll.js';
    }
    return $assets;
});

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