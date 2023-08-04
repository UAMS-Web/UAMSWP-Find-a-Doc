<?php
/*
 * Template Name: Ontology subsection values
 * 
 * Description: A template part that defines a series of variables related to the 
 * the values necessary to create the site header and the site navigation elements 
 * in an ontology subsection
 * 
 * Required vars:
 * 	$page_id // int // ID of the current ontology item
 * 
 * Optional vars:
 * 	$ontology_type // bool (default: true) // Ontology type of the current ontology item (true is ontology type, false is content type)
 * 	$page_title // string (default: '') // Title of the current ontology item
 * 	$page_url // string (default: '') // Permalink of the current ontology item
 */

 if (
	!isset($site_nav_id) || empty($site_nav_id)
	||
	!isset($navbar_subbrand_title) || empty($navbar_subbrand_title)
	||
	!isset($navbar_subbrand_title_attr) || empty($navbar_subbrand_title_attr)
	||
	!isset($navbar_subbrand_title_url) || empty($navbar_subbrand_title_url)
	||
	!isset($navbar_subbrand_parent) || empty($navbar_subbrand_parent)
	||
	!isset($navbar_subbrand_parent_attr) || empty($navbar_subbrand_parent_attr)
	||
	!isset($navbar_subbrand_parent_url) || empty($navbar_subbrand_parent_url)
	||
	!isset($providers) || empty($providers)
	||
	!isset($locations) || empty($locations)
	||
	!isset($expertises) || empty($expertises)
	||
	!isset($expertise_descendants) || empty($expertise_descendants)
	||
	!isset($clinical_resources) || empty($clinical_resources)
	||
	!isset($conditions_cpt) || empty($conditions_cpt)
	||
	!isset($treatments_cpt) || empty($treatments_cpt)
	||
	!isset($ancestors_ontology_farthest) || empty($ancestors_ontology_farthest)
	||
	!isset($page_top_level_query) || empty($page_top_level_query)
) {

	// Check/define optional variables

		$ontology_type = ( isset($ontology_type) && !empty($ontology_type) ) ? $ontology_type : true;
		$page_title = ( isset($page_title) && !empty($page_title) ) ? $page_title : '';
		$page_url = ( isset($page_url) && !empty($page_url) ) ? $page_url : '';

	$ontology_site_values_vars = uamswp_fad_ontology_site_values(
		$page_id, // int // ID of the current ontology item
		$ontology_type, // bool // Ontology type of the current ontology item (true is ontology type, false is content type)
		$page_title, // string // Title of the current ontology item
		$page_url // string // Permalink of the current ontology item
	);
		$site_nav_id = $ontology_site_values_vars['site_nav_id']; // int
		$navbar_subbrand_title = $ontology_site_values_vars['navbar_subbrand']['title']['name']; // string
		$navbar_subbrand_title_attr = $ontology_site_values_vars['navbar_subbrand']['title']['attr']; // string
		$navbar_subbrand_title_url = $ontology_site_values_vars['navbar_subbrand']['title']['url']; // string
		$navbar_subbrand_parent = $ontology_site_values_vars['navbar_subbrand']['parent']['name']; // string
		$navbar_subbrand_parent_attr = $ontology_site_values_vars['navbar_subbrand']['parent']['attr']; // string
		$navbar_subbrand_parent_url = $ontology_site_values_vars['navbar_subbrand']['parent']['url']; // string
		$providers = $ontology_site_values_vars['providers']; // int[]
		$locations = $ontology_site_values_vars['locations']; // int[]
		$expertises = $ontology_site_values_vars['expertises']; // int[]
		$expertise_descendants = $ontology_site_values_vars['expertise_descendants'];
		$clinical_resources = $ontology_site_values_vars['clinical_resources']; // int[]
		$conditions_cpt = $ontology_site_values_vars['conditions_cpt']; // int[]
		$treatments_cpt = $ontology_site_values_vars['treatments_cpt']; // int[]
		$ancestors_ontology_farthest = $ontology_site_values_vars['ancestors_ontology_farthest'];
		$page_top_level_query = $ontology_site_values_vars['page_top_level_query']; // bool

}