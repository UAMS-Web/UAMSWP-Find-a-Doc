<?php

declare(strict_types=1);
// these are UAMSPhysicians settings.

class UAMSPhysicians_Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_options']);
    }

    public function setup_sections(): void
    {
        $this->make_setting_pages();
        $this->add_setting_sections();

    }

    public function setup_options(): void
    {
        $this->register_settings();
        $this->add_settings_fields();
    }

    public function make_setting_pages(): void
    {
        // no pages atm
    }

    public function add_setting_sections(): void
    {
        // no sections atm
    }

    public function register_settings(): void
    {
        register_setting('general', 'ajax_search_pro_id');
    }

    public function add_settings_fields(): void
    {
        add_settings_field('ajax_search_pro_id', 'Ajax Search Pro ID for Providers:', [$this, 'ajax_search_pro_id_callback'], 'general');
    }

    public function ajax_search_pro_id_callback(): void
    {
        echo "<input name='ajax_search_pro_id' type='text' size='20' value='".get_option('ajax_search_pro_id')."' />";
    }
}

new UAMSPhysicians_Settings;
