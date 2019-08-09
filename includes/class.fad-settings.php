<?php
// these are UAMSPhysicians settings.

class UAMSPhysicians_Settings
{

    function __construct(){
        add_action('admin_menu', array($this, 'setup_sections'));
        add_action('admin_init', array($this, 'setup_options'));
    }

    function setup_sections() {
        $this->make_setting_pages();
        $this->add_setting_sections();

    }

    function setup_options() {
        $this->register_settings();
        $this->add_settings_fields();
    }

    function make_setting_pages(){
        //no pages atm
    }

    function add_setting_sections() {
        //no sections atm
    }

    function register_settings() {
        register_setting( 'general', 'ajax_search_pro_id' );
    }

    function add_settings_fields() {
        add_settings_field('ajax_search_pro_id', 'Ajax Search Pro ID for Physicians:', array($this, 'ajax_search_pro_id_callback'), 'general');
    }

    function ajax_search_pro_id_callback() {
        echo "<input name='ajax_search_pro_id' type='text' size='20' value='" . get_option('ajax_search_pro_id') . "' />";
    }
}
new UAMSPhysicians_Settings;