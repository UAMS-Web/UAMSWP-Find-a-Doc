<?php
/*
 * Template Name: System settings for general patient referral information
 * 
 * Description: A template part that defines a series of variables related to the 
 * phone numbers and URLs used for physicians to submit appointment referrals on 
 * the behalf of patients.
 */

if (
	!isset($appointment_refer_phone_number) || empty($appointment_refer_phone_number)
	||
	!isset($appointment_refer_phone_label) || empty($appointment_refer_phone_label)
	||
	!isset($appointment_refer_phone_label_attr) || empty($appointment_refer_phone_label_attr)
	||
	!isset($appointment_refer_phone_info) || empty($appointment_refer_phone_info)
	||
	!isset($appointment_refer_fax_number) || empty($appointment_refer_fax_number)
	||
	!isset($appointment_refer_fax_label) || empty($appointment_refer_fax_label)
	||
	!isset($appointment_refer_fax_label_attr) || empty($appointment_refer_fax_label_attr)
	||
	!isset($appointment_refer_fax_info) || empty($appointment_refer_fax_info)
	||
	!isset($appointment_refer_web_url) || empty($appointment_refer_web_url)
	||
	!isset($appointment_refer_web_label) || empty($appointment_refer_web_label)
	||
	!isset($appointment_refer_web_label_attr) || empty($appointment_refer_web_label_attr)
	||
	!isset($appointment_refer_web_info) || empty($appointment_refer_web_info)
) {

	$appointment_refer_vars = isset($appointment_refer_vars) ? $appointment_refer_vars : uamswp_fad_appointment_refer();
		$appointment_refer_phone_number = $appointment_refer_vars['appointment_refer_phone_number'];  // string
		$appointment_refer_phone_label = $appointment_refer_vars['appointment_refer_phone_label'];  // string
		$appointment_refer_phone_label_attr = $appointment_refer_vars['appointment_refer_phone_label_attr'];  // string
		$appointment_refer_phone_info = $appointment_refer_vars['appointment_refer_phone_info'];  // string
		$appointment_refer_fax_number = $appointment_refer_vars['appointment_refer_fax_number'];  // string
		$appointment_refer_fax_label = $appointment_refer_vars['appointment_refer_fax_label'];  // string
		$appointment_refer_fax_label_attr = $appointment_refer_vars['appointment_refer_fax_label_attr'];  // string
		$appointment_refer_fax_info = $appointment_refer_vars['appointment_refer_fax_info'];  // string
		$appointment_refer_web_url = $appointment_refer_vars['appointment_refer_web_url'];  // string
		$appointment_refer_web_label = $appointment_refer_vars['appointment_refer_web_label'];  // string
		$appointment_refer_web_label_attr = $appointment_refer_vars['appointment_refer_web_label_attr'];  // string
		$appointment_refer_web_info = $appointment_refer_vars['appointment_refer_web_info'];  // string

}