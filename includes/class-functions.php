<?php
// All settings
if( (get_option( 'tbx_general')['tbx_all_settings']) == 'on' ){
	
	function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
   }
   add_action('admin_menu', 'all_settings_link');
	
}

//