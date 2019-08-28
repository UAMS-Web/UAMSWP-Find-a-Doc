<?php 

function my_acf_google_key() {
	acf_update_setting('google_api_key', '### Insert Key ###');
}
add_action('acf/init', 'my_acf_google_key');