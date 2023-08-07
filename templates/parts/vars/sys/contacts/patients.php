<?php
/*
 * Template Name: System settings for general patient appointment information
 * 
 * Description: A template part that defines a series of variables related to the 
 * phone numbers and URLs used for patients to make an appointment.
 */

if (
	!isset($appointment_patients_phone_number_new) || empty($appointment_patients_phone_number_new)
	||
	!isset($appointment_patients_phone_label_new) || empty($appointment_patients_phone_label_new)
	||
	!isset($appointment_patients_phone_label_new_attr) || empty($appointment_patients_phone_label_new_attr)
	||
	!isset($appointment_patients_phone_info_new) || empty($appointment_patients_phone_info_new)
	||
	!isset($appointment_patients_phone_number_existing) || empty($appointment_patients_phone_number_existing)
	||
	!isset($appointment_patients_phone_label_existing) || empty($appointment_patients_phone_label_existing)
	||
	!isset($appointment_patients_phone_label_existing_attr) || empty($appointment_patients_phone_label_existing_attr)
	||
	!isset($appointment_patients_phone_info_existing) || empty($appointment_patients_phone_info_existing)
	||
	!isset($appointment_patients_phone_number_both) || empty($appointment_patients_phone_number_both)
	||
	!isset($appointment_patients_phone_label_both) || empty($appointment_patients_phone_label_both)
	||
	!isset($appointment_patients_phone_label_both_attr) || empty($appointment_patients_phone_label_both_attr)
	||
	!isset($appointment_patients_phone_info_both) || empty($appointment_patients_phone_info_both)
	||
	!isset($appointment_patients_web_url_new) || empty($appointment_patients_web_url_new)
	||
	!isset($appointment_patients_web_label_new) || empty($appointment_patients_web_label_new)
	||
	!isset($appointment_patients_web_label_new_attr) || empty($appointment_patients_web_label_new_attr)
	||
	!isset($appointment_patients_web_info_new) || empty($appointment_patients_web_info_new)
	||
	!isset($appointment_patients_web_url_existing) || empty($appointment_patients_web_url_existing)
	||
	!isset($appointment_patients_web_label_existing) || empty($appointment_patients_web_label_existing)
	||
	!isset($appointment_patients_web_label_existing_attr) || empty($appointment_patients_web_label_existing_attr)
	||
	!isset($appointment_patients_web_info_existing) || empty($appointment_patients_web_info_existing)
	||
	!isset($appointment_patients_web_url_both) || empty($appointment_patients_web_url_both)
	||
	!isset($appointment_patients_web_label_both) || empty($appointment_patients_web_label_both)
	||
	!isset($appointment_patients_web_label_both_attr) || empty($appointment_patients_web_label_both_attr)
	||
	!isset($appointment_patients_web_info_both) || empty($appointment_patients_web_info_both)
) {

	$appointment_patients_vars = isset($appointment_patients_vars) ? $appointment_patients_vars : uamswp_fad_appointment_patients();
		$appointment_patients_phone_number_new = $appointment_patients_vars['appointment_patients_phone_number_new'];  // string
		$appointment_patients_phone_label_new = $appointment_patients_vars['appointment_patients_phone_label_new'];  // string
		$appointment_patients_phone_label_new_attr = $appointment_patients_vars['appointment_patients_phone_label_new_attr'];  // string
		$appointment_patients_phone_info_new = $appointment_patients_vars['appointment_patients_phone_info_new'];  // string
		$appointment_patients_phone_number_existing = $appointment_patients_vars['appointment_patients_phone_number_existing'];  // string
		$appointment_patients_phone_label_existing = $appointment_patients_vars['appointment_patients_phone_label_existing'];  // string
		$appointment_patients_phone_label_existing_attr = $appointment_patients_vars['appointment_patients_phone_label_existing_attr'];  // string
		$appointment_patients_phone_info_existing = $appointment_patients_vars['appointment_patients_phone_info_existing'];  // string
		$appointment_patients_phone_number_both = $appointment_patients_vars['appointment_patients_phone_number_both'];  // string
		$appointment_patients_phone_label_both = $appointment_patients_vars['appointment_patients_phone_label_both'];  // string
		$appointment_patients_phone_label_both_attr = $appointment_patients_vars['appointment_patients_phone_label_both_attr'];  // string
		$appointment_patients_phone_info_both = $appointment_patients_vars['appointment_patients_phone_info_both'];  // string
		$appointment_patients_web_url_new = $appointment_patients_vars['appointment_patients_web_url_new'];  // string
		$appointment_patients_web_label_new = $appointment_patients_vars['appointment_patients_web_label_new'];  // string
		$appointment_patients_web_label_new_attr = $appointment_patients_vars['appointment_patients_web_label_new_attr'];  // string
		$appointment_patients_web_info_new = $appointment_patients_vars['appointment_patients_web_info_new'];  // string
		$appointment_patients_web_url_existing = $appointment_patients_vars['appointment_patients_web_url_existing'];  // string
		$appointment_patients_web_label_existing = $appointment_patients_vars['appointment_patients_web_label_existing'];  // string
		$appointment_patients_web_label_existing_attr = $appointment_patients_vars['appointment_patients_web_label_existing_attr'];  // string
		$appointment_patients_web_info_existing = $appointment_patients_vars['appointment_patients_web_info_existing'];  // string
		$appointment_patients_web_url_both = $appointment_patients_vars['appointment_patients_web_url_both'];  // string
		$appointment_patients_web_label_both = $appointment_patients_vars['appointment_patients_web_label_both'];  // string
		$appointment_patients_web_label_both_attr = $appointment_patients_vars['appointment_patients_web_label_both_attr'];  // string
		$appointment_patients_web_info_both = $appointment_patients_vars['appointment_patients_web_info_both'];  // string

}