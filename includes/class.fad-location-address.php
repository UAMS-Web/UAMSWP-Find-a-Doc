<?php

declare(strict_types=1);

/**
 * Address line 2 of a location: building, floor and suite.
 *
 * These functions hold the expressions that the location templates, the
 * Doximity list, the GMB lists and the GMB export used to repeat. They take
 * plain values so a test can run them with no WordPress loaded. Each one
 * keeps the exact behavior of the inline expression it replaced, including
 * the quirks, so that a later change to the address lines has one place to
 * edit and a test to move with it.
 *
 * This file registers no hooks and calls no WordPress function.
 */

/**
 * Build address line 2 as one string: building, floor label, then suite.
 *
 * The separators differ by caller:
 * - Page and card display: '<br />' after the building, ', ' after the floor.
 * - Schema markup: ' ' after both.
 * - Doximity export: '<br />' after both.
 *
 * @param  bool  $has_building  Whether the location has a building other than the '_none' term.
 * @param  string  $building_name  Name of the building. Read only when $has_building is true.
 * @param  array|false  $floor  The floor field object, or a falsy value when there is none.
 * @param  mixed  $floor_value  The selected floor value ('' when there is none).
 * @param  string  $floor_label  The label of the selected floor ('' when there is none).
 * @param  string|false  $suite  The suite text, or a falsy value when there is none.
 * @param  string  $building_separator  Text between the building and what follows it.
 * @param  string  $floor_separator  Text between the floor label and the suite.
 * @param  bool  $require_floor_value  Whether the floor label needs a non-empty floor value to print. The
 *                                     schema markup did not require one; the display did.
 * @return string
 */
function uamswp_location_address_2(
    $has_building,
    $building_name,
    $floor,
    $floor_value,
    $floor_label,
    $suite,
    $building_separator = '<br />',
    $floor_separator = ', ',
    $require_floor_value = true
) {
    $show_floor = $floor && $floor_value != '0' && (! $require_floor_value || ! empty($floor_value));

    return ($has_building ? $building_name.((($floor && $floor_value) || $suite) ? $building_separator : '') : '')
        .($show_floor ? $floor_label.($suite ? $floor_separator : '') : '')
        .($suite ? $suite : '');
}

/**
 * Build the address lines after the street address as a list: building,
 * floor label, then suite, each present only when it has a value.
 *
 * The GMB lists and the GMB export place the entries of this list on address
 * lines 2 to 5.
 *
 * @param  bool  $has_building  Whether the location has a building other than the '_none' term.
 * @param  string  $building_name  Name of the building. Read only when $has_building is true.
 * @param  array|false  $floor  The floor field object, or a falsy value when there is none.
 * @param  mixed  $floor_value  The selected floor value ('' when there is none).
 * @param  string  $floor_label  The label of the selected floor ('' when there is none).
 * @param  string|false  $suite  The suite text, or a falsy value when there is none.
 * @return string[]
 */
function uamswp_location_address_parts(
    $has_building,
    $building_name,
    $floor,
    $floor_value,
    $floor_label,
    $suite
) {
    $parts = [];

    if ($has_building) {
        $parts[] = $building_name;
    }
    if ($floor && ! empty($floor_value) && $floor_value != '0') {
        $parts[] = $floor_label;
    }
    if ($suite && ! empty($suite)) {
        $parts[] = $suite;
    }

    return $parts;
}
