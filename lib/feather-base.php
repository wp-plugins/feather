<?php

/**
	Feather Theme Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Marée

		@package FeatherBase
		@version 1.0.1
**/

//! Base structure
class FeatherBase {

	//@{ Framework details
	const
		TEXT_Framework='Feather',
		TEXT_Version='1.0.1';
	//@}

	//@{ Locale-specific error/exception messages
	const
		TEXT_Config='The configuration file %s was not found',
		TEXT_File='The required file %s was not found',
		TEXT_Widget='The widget file %s was not found',
		TEXT_Method='Undefined method %s';
	//@}

	protected static
		//! Global variables
		$vars,
		//! Framework prefix
		$prefix='feather',
		//! Framework Configuration
		$config,
		//! Framework Settings
		$setting,
		//! Framework Options
		$option,
		//! Theme settings
		$theme_setting,
		//! Theme options,
		$theme_option,
		//! Theme meta
		$theme_meta,
		//! Theme taxonomy
		$theme_taxonomy,
		//! Theme post type
		$theme_type;

	/**
		Get configuration option
			@param $option string
			@protected
	**/
	protected static function get_config($option=NULL) {
		$result=isset(self::$config[$option])?self::$config[$option]:FALSE;
		return $result;
	}

	/**
		Get framework option
			@param $name string
			@public
	**/
	static function get_option($name=NULL) {
		$result=isset(self::$option[$name])?self::$option[$name]:FALSE;
		return $result;
	}

	/**
		Get theme option
			@param $name string
			@public
	**/
	static function get_theme_option($name=NULL) {
		$result=isset(self::$theme_option[$name])?self::$theme_option[$name]:FALSE;
		return $result;
	}

	/**
		Get lang option
			@param $name string
			@public
	**/
	static function get_theme_lang($name=NULL) {
		$result=isset(self::$theme_lang[$name])?self::$theme_lang[$name]:FALSE;
		return $result;
	}

	/**
		Load framework file
			@param $name string
			@param $dir string
			@protected
	**/
	protected static function load_file($filename=NULL,$dir='') {
		$file=FEATHER_PATH.$dir.'/'.self::$prefix.'-'.$filename.'.php';
		return self::file_exists($file);
	}

	/**
		Load theme file
			@param $name string
			@param $dir string
			@protected
	**/
	protected static function load_theme_file($name=NULL,$dir='') {
		$file=FEATHER_PATH_THEME.$dir.'/'.$name.'.php';
		return self::file_exists($file);
	}

	/**
		Load theme widget
			@param $name string
			@protected
	**/
	protected static function load_theme_widget($name=NULL) {
		$file=get_template_directory().'/widgets/'.$name.'.php';
		return self::file_exists($file);
	}

	/**
		Check if file exists
			@param $file string
			@param $config boolean
			@protected
	**/
	protected static function file_exists($file=NULL,$config=FALSE) {
		// Set error
		$error=$config?self::TEXT_Config:self::TEXT_File;
		if(!is_file($file)) {
			// File does not exist, set error message
			$message=sprintf($error,basename($file));
			if(!is_admin())
				trigger_error($message,E_USER_ERROR);
			else
				self::$vars['ERROR']=$message;
			return FALSE;
		}
		// Load file
		if(!$config)
			require($file);
		return TRUE;
	}

	/**
		Intercept calls to undefined methods
			@param $func string
			@param $args array
			@public
	**/
	function __call($func,array $args) {
		$message=sprintf(self::TEXT_Method,get_called_class().'::'.$func);
		if(!headers_sent())
			trigger_error($message,E_USER_ERROR);
		else
			trigger_error($message);
	}

	/**
		Intercept calls to undefined static methods
			@param $func string
			@param $args array
			@public
	**/
	static function __callStatic($func,array $args) {
		$message=sprintf(self::TEXT_Method,get_called_class().'::'.$func);
		if(!headers_sent())
			trigger_error($message,E_USER_ERROR);
		else
			trigger_error($message);
	}

	/**
		Prevent class instantiation
			@private
	**/
	private function __construct() {}

	/**
		Prevent cloning
			@private
	**/
	private function __clone() {}

}

//! Config file loader
class FeatherConfig extends FeatherBase {

	/**
		Framework config file
			@param $name string
			@protected
	**/
	static function framework($name=NULL,$var=NULL) {
		// File path
		$file=FEATHER_PATH.'config/'.self::$prefix.'-config-'.$name.'.php';
		if(!self::file_exists($file,TRUE)) {
			return FALSE;
		} else {
			ob_start();
			require($file);
			ob_end_clean();
		}
		// Return config
		return isset($$var)?$$var:FALSE;
	}

	/**
		Theme config file
			@param $name string
			@protected
	**/
	static function theme($name=NULL,$var=NULL) {
		// File path
		$file=FEATHER_PATH_THEME.'config/'.$name.'.php';
		if(!self::file_exists($file,TRUE)) {
			return FALSE;
		} else {
			ob_start();
			require($file);
			ob_end_clean();
		}
		// Return config
		return isset($$var)?$$var:FALSE;
	}

}

//! Feather Framework
class FeatherCore extends FeatherBase {

	/**
		Start framework
			@public
	**/
	static function boot() {
		// Prevent multiple calls
		if(self::$vars)
			return;
		// Initialize framework
		self::init();
		// Register sidebars
		self::register_sidebars();
		// Initialize widgets
		self::widgets_init();
		// Configure theme
		self::configure_theme();
		// Init admin
		if(is_admin()) { self::init_admin(); }
		// Theme initialization
		if(method_exists('FeatherTheme','init'))
			FeatherTheme::init();
	}

	/**
		Error template
			@public
	**/
	static function error($code,$msg,$file,$line) {
		if(defined('WP_DEBUG') && E_USER_ERROR!=$code)
			return FALSE;
		if(E_USER_ERROR==$code) {
			// Store error message
			self::$vars['ERROR']=$msg;
			// Load error template
			self::load_file('error','tmpl');
			exit(1);
		}
		return TRUE;
	}

	/**
		Maintenance Mode
			@public
	**/
	private static function maintenance() {
		// Exclude login,register pages
		$exclude=array('wp-login.php','wp-register.php');
		if(!in_array(self::$vars['PAGENOW'],$exclude)) {
			$url=substr($_SERVER['REQUEST_URI'],-12);
			// Redirect if not maintenance URL
			if('_maintenance'!=$url) {
				$redirect_url=home_url().'/_maintenance';
				header('Location: '.$redirect_url,TRUE,307);
				exit;
			}
			// Load maintenance tempalte
			self::load_file('maintenance','tmpl');
			if(!isset(self::$vars['ERROR']))
				exit(1);
		}
	}

	/**
		Return runtime performance analytics
			@return array
			@public
	**/
	static function profile() {
		$stats=&self::$vars['STATS'];
		// Compute elapsed time
		$stats['TIME']['elapsed']=microtime(TRUE)-$stats['TIME']['start'];
		// Compute memory consumption
		$stats['MEMORY']['current']=memory_get_usage();
		$stats['MEMORY']['peak']=memory_get_peak_usage();
		return $stats;
	}

	/**
		Initialize framework
			@private
	**/
	private static function init() {
		// Error handler
		if(!is_admin())
			set_error_handler(__CLASS__.'::error');
		// Global $pagenow variable
		global $pagenow;
		// Hydrate framework variables
		self::$vars=array(
			// Last error
			'ERROR'=>NULL,
			// Framework notice
			'NOTICE'=>NULL,
			// Global $pagenow variable
			'PAGENOW'=>$pagenow,
			// Profiler statistics
			'STATS'=>array(
				'MEMORY'=>array('start'=>memory_get_usage()),
				'TIME'=>array('start'=>microtime(TRUE))
			),
			// Framework settings tabs
			'TABS'=>array(
				'general'=>'General',
				'sidebar'=>'Sidebar',
				'advanced'=>'Advanced'
			),
			// Framework version
			'VERSION'=>self::TEXT_Framework.' '.self::TEXT_Version,
			// Post formats
			'WP_POST_FORMATS'=>'aside|audio|chat|gallery|image|link|'.
				'quote|status|video',
			// Default WP Widgets
			'WP_WIDGETS'=>array(
				'widget_wp_archives'=>'WP_Widget_Archives',
				'widget_wp_calendar'=>'WP_Widget_Calendar',
				'widget_wp_categories'=>'WP_Widget_Categories',
				'widget_wp_custom_menu'=>'WP_Nav_Menu_Widget',
				'widget_wp_links'=>'WP_Widget_Links',
				'widget_wp_meta'=>'WP_Widget_Meta',
				'widget_wp_pages'=>'WP_Widget_Pages',
				'widget_wp_recent_comments'=>'WP_Widget_Recent_Comments',
				'widget_wp_recent_posts'=>'WP_Widget_Recent_Posts',
				'widget_wp_rss'=>'WP_Widget_RSS',
				'widget_wp_search'=>'WP_Widget_Search',
				'widget_wp_tag_cloud'=>'WP_Widget_Tag_Cloud',
				'widget_wp_text'=>'WP_Widget_Text'
			)
		);
		// Get feather option
		self::$option=get_option('feather');
		// Check for maintenance mode
		if(!is_admin() && self::get_option('maintenance'))
			if(!current_user_can('administrator'))
				self::maintenance();
		// Load theme library
		if(is_file(FEATHER_PATH_THEME.'lib/feather-theme.php'))
			self::load_theme_file('feather-theme','lib');
		// Load configuration file
		self::$config=FeatherConfig::theme('feather-config','config');
	}

	/**
		Register sidebars
			@private
	**/
	private static function register_sidebars() {
		if(self::get_config('SIDEBARS')) {
			foreach(self::$config['SIDEBARS'] as $sidebar) {
				if(!isset($sidebar['count'])) {
					// Single Sidebar
					register_sidebar($sidebar);
				} else {
					$count=$sidebar['count'];
					unset($sidebar['count']);
					// Multiple Sidebars
					register_sidebars($count,$sidebar);
				}
			}
		}
	}

	/**
		Initialize widgets
			@private
	**/
	private static function widgets_init() {
		if(self::get_config('WIDGETS')) {
			foreach(self::$config['WIDGETS'] as $name=>$class) {
				if(self::load_theme_widget($name))
					add_action('widgets_init',
						create_function('','return register_widget("'.$class.'");'));
			}
		}
		// Add action to unregister widgets
		add_action('widgets_init',__CLASS__.'::unregister_default_wp_widgets',1);
	}

	/**
		Unregister default WP widgets
			@public
	**/
	static function unregister_default_wp_widgets() {
		foreach(self::$vars['WP_WIDGETS'] as $id=>$class)
			if(self::get_option($id))
				unregister_widget($class);
	}

	/**
		Configure theme
			@private
	**/
	private static function configure_theme() {
		// Get theme options
		if(self::get_config('OPTION_NAME'))
			self::$theme_option=get_option(self::$config['OPTION_NAME']);
		// Load theme configuration files
		if(self::get_config('CUSTOM_META'))
			self::$theme_meta=FeatherConfig::theme('config-meta','meta');
		if(self::get_config('CUSTOM_TAXONOMY'))
			self::$theme_taxonomy=FeatherConfig::theme('config-taxonomy','tax');
		if(self::get_config('CUSTOM_TYPE'))
			self::$theme_type=FeatherConfig::theme('config-type','type');
		// Theme modules
		if(self::get_config('MODULES')) {
			foreach(self::$config['MODULES'] as $file=>$class) {
				if(self::load_theme_file($file,'modules'))
					call_user_func(array($class,'init'));
			}
		}
		// Menu Locations
		if(self::get_config('MENUS'))
			register_nav_menus(self::$config['MENUS']);
		// Post Formats
		if(self::get_option('post_formats')) {
			$post_formats=array();
			foreach(explode('|',self::$vars['WP_POST_FORMATS']) as $format)
			 if(self::get_option('post_format_'.$format))
				$post_formats[]=$format;
			if(!empty($post_formats))
				add_theme_support('post-formats',$post_formats);
		}
		// Post Thumbnails
		if(self::get_option('post_thumbnails'))
			add_theme_support('post-thumbnails');
		// Image Sizes
		if(self::get_config('IMAGE_SIZES'))
			foreach(self::$config['IMAGE_SIZES'] as $name=>$image)
				add_image_size($name,$image['width'],$image['height']);
		// Register theme taxonomies
		if(self::$theme_taxonomy) {
			foreach(self::$theme_taxonomy as $name=>$taxonomy) {
				if(!isset($taxonomy['args'])) { $taxonomy['args']=array(); }
				register_taxonomy($name,$taxonomy['type'],$taxonomy['args']);
			}
		}
		// Register theme post types
		if(self::$theme_type) {
			foreach(self::$theme_type as $type=>$args)
				register_post_type($type,$args);
		}

		/* --------------------------------------------------------------------- */

		// Non-admin configuration
		if(!is_admin()) {
			// Add action for theme head
			add_action('template_redirect',__CLASS__.'::theme_head');
		}
	}

	/**
		Theme head
			@public
	**/
	static function theme_head() {
		// Automatic deed Links
		if(self::get_option('auto_feed_links'))
			add_theme_support('automatic-feed-links');
		// Comment Reply JavaScript
		if(!self::get_option('commentreply_js'))
			if(is_singular())
				wp_enqueue_script('comment-reply');
		// Remove items from head
		if(self::get_option('l10n.js'))
			wp_deregister_script('l10n');
		if(self::get_option('feed_links_extra'))
			remove_action('wp_head','feed_links_extra',3);
		if(self::get_option('rsd_link'))
			remove_action('wp_head','rsd_link');
		if(self::get_option('wlwmanifest_link'))
			remove_action('wp_head','wlwmanifest_link');
		if(self::get_option('index_rel_link'))
			remove_action('wp_head','index_rel_link');
		if(self::get_option('parent_post_rel_link'))
			remove_action('wp_head','parent_post_rel_link',10,0);
		if(self::get_option('start_post_rel_link'))
			remove_action('wp_head','start_post_rel_link',10,0);
		if(self::get_option('adjacent_posts_rel_link_wp_head'))
			remove_action('wp_head','adjacent_posts_rel_link_wp_head',10,0);
		if(self::get_option('wp_shortlink_wp_head'))
			remove_action('wp_head','wp_shortlink_wp_head',10,0);
		// Remove WP version
		remove_action('wp_head','wp_generator');
	}

	/**
		Init admin
			@private
	**/
	private static function init_admin() {
		// Admin error notices
		add_action('admin_notices',__CLASS__.'::admin_notices');
		// Load admin library, init framework admin
		if(self::load_file('admin','lib')) { FeatherAdmin::init(); }
	}

	/**
		Generate admin notices
			@public
	**/
	static function admin_notices() {
		// Error notice
		if(isset(self::$vars['ERROR'])) {
			$output='<div id="message" class="error">';
			$output.='<p>Feather : '.self::$vars['ERROR'].'</p>';
			$output.='</div>';
			echo $output;
		}
	}

}

// Boot framework
FeatherCore::boot();
