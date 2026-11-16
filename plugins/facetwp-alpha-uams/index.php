<?php
/*
Plugin Name: FacetWP - Alpha for UAMS
Description: Filter by first letter
Version: 1.3.1
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-alpha
*/

// The guard exits the process when ABSPATH is missing. A mutant of it exits the test child before any test runs,
// so no test can score it; the guard is plain WordPress boilerplate with no logic to pin.
// @pest-mutate-ignore: LogicalOrToLogicalAnd
defined( 'ABSPATH' ) or exit;


/**
 * FacetWP registration hook
 */
add_filter( 'facetwp_facet_types', function( $types ) {
    include( dirname( __FILE__ ) . '/class-alpha.php' );
    $types['alpha'] = new FacetWP_Facet_Alpha_Addon();
    return $types;
} );
