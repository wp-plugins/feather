<?php
/*
Plugin Name: Feather
Plugin URI: http://wpbandit.com
Description: Lightweight & Powerful Theme Framework
Version: 1.0
Author: Jermaine Marée of WPBandit
Author URI: http://wpbandit.com
*/

//! Define constants
define('FEATHER_URL',plugin_dir_url(__FILE__));
define('FEATHER_PATH',plugin_dir_path(__FILE__));
define('FEATHER_URL_THEME',get_template_directory_uri().'/feather/');
define('FEATHER_PATH_THEME',get_template_directory().'/feather/');

//! Framework Check
if(version_compare(PHP_VERSION,'5.3.0') >= 0) {
	// Add action to load framework
	add_action('setup_theme','load_feather');
} else {
	// Required PHP version not available
	if(!is_admin() && !in_array($GLOBALS['pagenow'],
		array('wp-login.php','wp-register.php'))) {
		// Display error template
		require_once(FEATHER_PATH.'tmpl/feather-error-init.php');
		exit(1);
	}
	// Display admin notice
	if(is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
		add_action('admin_notices','feather_admin_notice_php');
	}
}

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

//! PHP Admin Notice
function feather_admin_notice_php() {
	$output='<div class="error fade">';
	$output.='<p>Feather : PHP 5.3 or greater required. Please check '.
		'with your web host to see if PHP 5.3 is supported.</p>';
	$output.='</div>';
	// Display error
	echo $output;
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
