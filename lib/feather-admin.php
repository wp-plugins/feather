<?php

/**
	Admin Library for the Feather Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Marée

		@package FeatherAdmin
		@version 1.0.2
**/

class FeatherAdmin extends FeatherBase {

	/**
		Admin methods and actions
			@private
	**/
	static function init() {
		// Load form library
		self::load_file('form','lib');
		// Load settings library
		self::load_file('settings','lib');
		// Admin menu
		add_action('admin_menu',__CLASS__.'::admin_menu');
		// Admin init
		if(in_array(self::$vars['PAGENOW'],
			array('options-general.php','options.php')))
			add_action('admin_init',__CLASS__.'::admin_init');
		// Theme Meta
		if(in_array(self::$vars['PAGENOW'],array('post.php','post-new.php')))
			if(self::$theme_meta)
				add_action('admin_init',__CLASS__.'::theme_meta');
	}

	/**
		Admin menu
			@public
	**/
	static function admin_menu() {
		// Add page to the Settings menu
		add_options_page('Feather','Feather','manage_options',
			'feather',__CLASS__.'::options_page');
	}

	/**
		Admin settings page
			@public
	**/
	static function options_page() {
		if(!self::load_file('options-page','tmpl')) {
			// Could not load options page, print error
			$message=sprintf(self::TEXT_File,self::$prefix.'-'.$file.'.php');
			echo '<div class="wrap"><div id="message" class="error"><p>'.
				'Feather : '.$message.'</p></div></div>';
		}
	}

	/**
		Print options page tabs
			@param $page string
			@public
	**/
	static function print_options_page_tabs($page='theme') {
		// Set tabs
		switch($page) {
			case 'feather':
				$tabs=self::$vars['TABS'];
				break;
			case 'theme':
				$tabs=self::$config['OPTION_TABS'];
				break;
		}
		// Get current tab
		$current=self::get_current_options_page_tab($page);
		// Build tab links
		$links=array();
		foreach($tabs as $tab=>$name) {
			$active=($tab==$current)?' nav-tab-active':'';
			$links[]='<a class="nav-tab'.$active.'" href="?page=feather&tab='.
				$tab.'">'.$name.'</a>';
		}
		// Create html for tabs
		if(function_exists('get_screen_icon')) {
			$output=get_screen_icon().'<h2 class="nav-tab-wrapper">';
		} else {
			$output='<div id="icon-themes" class="icon32"><br /></div>'.
				'<h2 class="nav-tab-wrapper">';
		}
		// Add tab links to output
		foreach($links as $link)
			$output.=$link;
		$output.='</h2>';
		// Output html
		echo $output;
	}

	/**
		Get current options page tab
			@param $tab string
			@public
	**/
	static function get_current_options_page_tab($page='theme') {
		// Set tabs
		switch($page) {
			case 'feather':
				$tabs=self::$vars['TABS'];
				break;
			case 'theme':
				$tabs=self::$config['OPTION_TABS'];
				break;
		}
		reset($tabs);
		$tab=isset($_GET['tab'])?esc_attr($_GET['tab']):key($tabs);
		return $tab;
	}

	/**
		Admin init
			@public
	**/
	static function admin_init() {
		// Register setting
		register_setting('feather-settings','feather',
			__CLASS__.'::validate_settings');
		// Initialize framework settings
		self::settings_init();
		// Stylesheets
		add_action('admin_print_styles-settings_page_feather',
			__CLASS__.'::stylesheets');
		/* Javascript - future use
		add_action('admin_print_scripts-settings_page_feather',
			__CLASS__.'::javascript');*/
	}

	/**
		Validate settings
			@public
	**/
	static function validate_settings($input) {
		// Get tab
		$tab=$input['tab'];
		// Unset tab option
		unset($input['tab']);
		// Get current options
		$valid=self::$option?self::$option:array();

		// Validate settings
		switch($tab) {

			// General Tab
			case 'general':
				// Define checkbox options
				$checkboxes='auto_feed_links|post_formats|post_thumbnails|maintenance|'.
					'post_format_aside|post_format_audio|post_format_chat|'.
					'post_format_gallery|post_format_image|post_format_link|'.
					'post_format_quote|post_format_status|post_format_video';
				// Validate options
				foreach(explode('|',$checkboxes) as $option) {
					// Check for required settings
					if(isset(self::$config['OPTION_REQUIRED'][$option])) {
						// Set required value
						$valid[$option]=self::$config['OPTION_REQUIRED'][$option];
					} else {
						// Set input value
						$valid[$option]=isset($input[$option])?'1':'0';
					}
				}
				break;

			// Sidebar Tab
			case 'sidebar':
				// Define checkbox options
				$checkboxes='widget_wp_archives|widget_wp_calendar|widget_wp_categories|'.
					'widget_wp_custom_menu|widget_wp_links|widget_wp_meta|widget_wp_pages|'.
					'widget_wp_recent_comments|widget_wp_recent_posts|widget_wp_rss|'.
					'widget_wp_search|widget_wp_tag_cloud|widget_wp_text';
				// Validate options
				foreach(explode('|',$checkboxes) as $option) {
					// Check for required settings
					if(isset(self::$config['OPTION_REQUIRED'][$option])) {
						// Set required value
						$valid[$option]=self::$config['OPTION_REQUIRED'][$option];
					} else {
						// Set input value
						$valid[$option]=isset($input[$option])?'1':'0';
					}
				}
				break;

			// Advanced Tab
			case 'advanced':
				// Define checkbox options
				$checkboxes='maintenance|l10n.js|feed_links_extra|rsd_link|'.
					'wlwmanifest_link|index_rel_link|parent_post_rel_link|'.
					'start_post_rel_link|adjacent_posts_rel_link_wp_head|'.
					'wp_shortlink_wp_head|commentreply_js';
				// Validate options
				foreach(explode('|',$checkboxes) as $option) {
					// Check for required settings
					if(isset(self::$config['OPTION_REQUIRED'][$option])) {
						// Set required value
						$valid[$option]=self::$config['OPTION_REQUIRED'][$option];
					} else {
						// Set input value
						$valid[$option]=isset($input[$option])?'1':'0';
					}
				}
				break;

		}
		return $valid;
	}

	/**
		Settings init
			@private
	**/
	private static function settings_init() {
		// Framework settings
		self::$setting=FeatherConfig::framework('settings','setting');
		// Check if post formats enabled
		if(!self::get_option('post_formats'))
			unset(self::$setting['post_formats']);
		// Process settings
		self::process_settings(self::$setting,'feather');
	}

	/**
		Process settings
			@public
	**/
	static function process_settings(array $settings,$option,$optionfunc=NULL) {
		// Add settings sections
		foreach($settings as $id=>$section) {
			// Set section id
			$section['id']=self::$prefix.'_'.$id;
			// Set section callback
			$section['callback']=$id;
			// Add section
			FeatherSettings::add_section($section,$option);
			// Add settings fields
			foreach($section['fields'] as $fid=>$field) {
				// Set field id
				$field['id']=$fid;
				// Set field tab
				$field['tab']=$section['tab'];
				// Set field setting
				$field['setting']=$option;
				// Set field section
				$field['section']=$section['id'];
				// Set option function
				if(isset($optionfunc))
					$field['optionfunc']=$optionfunc;
				// Add field
				FeatherSettings::add_field($field,$option);	
			}
		}
	}

	/**
		Stylesheets
			@public
	**/
	static function stylesheets() {
		wp_enqueue_style('feather-admin-css',
			FEATHER_URL.'assets/css/feather-admin.css',FALSE,20110915);
	}

	/**
		Javascript
			@public
	**/
	static function javascript() {
		wp_enqueue_script('feather-admin-js',
			FEATHER_URL.'assets/js/feather-admin.js',FALSE,20110915);
	}

	/**
		Theme meta
			@public
	**/
	static function theme_meta() {
		// Load Meta library
		self::load_file('meta','lib');
		// Meta library name
		$meta_lib='FeatherMeta';
		// Process meta fields
		foreach(self::$theme_meta as $mid=>$meta) {
			self::process_meta_field($mid,$meta);
		}
		// Add action to save meta
		add_action('save_post',$meta_lib.'::save_meta');
	}

	/**
		Process meta field
			@public
	**/
	static function process_meta_field($mid,array $fields) {
		// Extract fields
		extract($fields);

		// Callback
		if(!isset($callback))
			$callback='FeatherMeta::create_meta_box';
		// Context
		if(!isset($context)) { $context='advanced'; }
		// Priority
		if(!isset($priority)) { $priority='low'; }
		// Args
		if(!isset($args)) { $args=array(); }

		// Non-template meta box
		if(!isset($template))
			add_meta_box($mid,$title,$callback,$page,$context,$priority,$args);

		// Template specific meta box
		if(isset($template)) {
			// Get post id
			if(isset($_GET['post'])) { $post_id=esc_attr($_GET['post']); }
			if(isset($_POST['post_ID'])) { $post_id=esc_attr($_POST['post_ID']); }
			// Get template
			if(isset($post_id))
				$page_template=get_post_meta($post_id,'_wp_page_template',TRUE);
			// Create meta box, if template matches
			if(isset($page_template) && in_array($page_template,$template))
				add_meta_box($mid,$title,$callback,$page,$context,$priority,$args);
		}
	}

}
