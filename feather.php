<?php
/*
Plugin Name: Feather
Plugin URI: http://wpbandit.com/feather/
Description: Lightweight & Powerful Theme Framework
Version: 1.0.3
Author: Jermaine Marée of WPBandit
Author URI: http://wpbandit.com
*/

//! Define constants
define('FEATHER_URL',plugin_dir_url(__FILE__));
define('FEATHER_PATH',plugin_dir_path(__FILE__));
define('FEATHER_URL_THEME',get_template_directory_uri().'/feather/');
define('FEATHER_PATH_THEME',get_template_directory().'/feather/');


// Add action to load framework
add_action('setup_theme','load_feather');

//! Load Feather
function load_feather() {
	// Verify feather directory exists
	if(!is_dir(FEATHER_PATH_THEME)) {
		// Display admin error
		if(is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
			add_action('admin_notices','feather_admin_notice_compat');
		}
		return;
	}
	// Load framework
	require_once(FEATHER_PATH.'lib/feather-base.php');
}

//! Compat Admin Notice
function feather_admin_notice_compat() {
	$output='<div class="error fade">';
	$output.='<p>Feather : The current theme is incompatible with '.
		'the framework. Activate a compatible theme or disable the plugin.</p>';
	$output.='</div>';
	// Display error
	echo $output;
}
