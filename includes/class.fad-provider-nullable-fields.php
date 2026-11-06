<?php

declare(strict_types=1);
/**
 * Keep an unanswered provider field empty in the provider custom table.
 *
 * The provider table (assets/json/acf-tables/table_uamswp_physicians.json)
 * stores these fields in integer columns with the "%d" format. WordPress turns
 * an empty string into 0 under that format, so a provider saved as a Draft,
 * Pending Review or Inactive with nothing entered got "No" (0) or an NPI and
 * SER ID of 0, which looks like a real answer.
 *
 * Handing the table a null instead writes NULL, which the columns accept.
 * A value that is not an empty string, including a real 0 ("No"), is left
 * alone.
 *
 * This file registers no hooks and calls no WordPress function; the hook is
 * added in class.fad-acf-functions.php.
 */

/**
 * Names of the provider fields stored in an integer column whose empty value
 * must stay empty.
 *
 * @return string[]
 */
function uamswp_provider_nullable_field_names(): array
{

    return [
        'physician_eligible_appointments',
        'physician_primary_care',
        'physician_referral_required',
        'physician_accepting_patients',
        'physician_second_opinion',
        'physician_pid',
        'physician_npi',
    ];

}

/**
 * Turn an empty string into null for the provider fields in
 * uamswp_provider_nullable_field_names() before the custom table stores it.
 *
 * Hooked to acfcdt/filter_value_before_update.
 *
 * @param  mixed  $value  The value about to be stored.
 * @param  mixed  $selector  The ACF selector (the post, in practice).
 * @param  mixed  $field_array  The ACF field the value belongs to.
 * @return mixed
 */
function uamswp_provider_null_empty_value($value, $selector = null, $field_array = [])
{

    if ($value !== '') {
        return $value;
    }

    if (! is_array($field_array) || ! isset($field_array['name'])) {
        return $value;
    }

    if (! in_array($field_array['name'], uamswp_provider_nullable_field_names(), true)) {
        return $value;
    }

    return null;

}
