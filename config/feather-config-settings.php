<?php

/*---------------------------------------------------------------------------*
 * Feather Framework Settings
 *---------------------------------------------------------------------------*/


/*---------------------------------------------------------------------------*
 * General Tab
 *---------------------------------------------------------------------------*/

/* ---- Theme Support ------------------------------------------------------ */

$setting['theme_support']=array(
	'title'=>'Theme Support',
	'desc'=>'Register support of theme features. To enable a feature select '.
		'the appropriate checkbox below.',
	'tab'=>'general',
	'fields'=>array(
		// Automatic feed links
		'auto_feed_links'=>array(
			'label'=>'Automatic Feed Links',
			'type'=>'checkbox',
			'choices'=>array(
				'auto_feed_links'=>'Enable support for feed links'
			)
		),
		// Post formats
		'post_formats'=>array(
			'label'=>'Post Formats',
			'type'=>'checkbox',
			'choices'=>array(
				'post_formats'=>'Enable support for post formats'
			)
		),
		// Post thumbnails
		'post_thumbnails'=>array(
			'label'=>'Post Thumbnails',
			'type'=>'checkbox',
			'choices'=>array(
				'post_thumbnails'=>'Enable support for post thumbnails'
			)
		),
	)
);

/* ---- Post Formats ------------------------------------------------------- */

$setting['post_formats']=array(
	'title'=>'Post Formats',
	'desc'=>'Post formats are used to customize the presentation of a post. It '.
		'gives the theme a tumblog functionality.',
	'tab'=>'general',
	'fields'=>array(
		// Post Formats
		'post_formats'=>array(
			'label'=>'Enable Post Formats',
			'type'=>'checkbox',
			'choices'=>array(
				'post_format_aside'=>'Aside',
				'post_format_audio'=>'Audio',
				'post_format_chat'=>'Chat',
				'post_format_gallery'=>'Gallery',
				'post_format_image'=>'Image',
				'post_format_link'=>'Link',
				'post_format_quote'=>'Quote',
				'post_format_status'=>'Status',
				'post_format_video'=>'Video',
			)
		)
	)
);

/* ---- Maintenance Mode --------------------------------------------------- */

$setting['maintenance']=array(
	'title'=>'Maintenance Mode',
	'desc'=>'Disable access to your site except for administrators. This '.
		'option should only be enabled short-term (less than a day).',
	'tab'=>'general',
	'fields'=>array(
		// Maintenance
		'maintenance'=>array(
			'label'=>'Disable Public Site Access',
			'type'=>'checkbox',
			'choices'=>array(
				'maintenance'=>'Enable Maintenance Mode'
			)
		)
	)
);


/*---------------------------------------------------------------------------*
 * Sidebar Tab
 *---------------------------------------------------------------------------*/

/* ---- Widgets ------------------------------------------------------------ */

$setting['disable_wp_widgets']=array(
	'title'=>'Disable WordPress Widgets',
	'desc'=>'Unregister default widgets by selecting the checkboxes below.',
	'tab'=>'sidebar',
	'fields'=>array(
		// Disable widgets
		'disable_widgets'=>array(
			'label'=>'Disable Widgets',
			'type'=>'checkbox',
			'choices'=>array(
				'widget_wp_archives'=>'Archives',
				'widget_wp_calendar'=>'Calendar',
				'widget_wp_categories'=>'Categories',
				'widget_wp_custom_menu'=>'Custom Menu',
				'widget_wp_links'=>'Links',
				'widget_wp_meta'=>'Meta',
				'widget_wp_pages'=>'Pages',
				'widget_wp_recent_comments'=>'Recent Comments',
				'widget_wp_recent_posts'=>'Recent Posts',
				'widget_wp_rss'=>'RSS',
				'widget_wp_search'=>'Search',
				'widget_wp_tag_cloud'=>'Tag Cloud',
				'widget_wp_text'=>'Text'
			)
		)
	)
);


/*---------------------------------------------------------------------------*
 * Advanced Tab
 *---------------------------------------------------------------------------*/

/* ---- Cleanup <head> ----------------------------------------------------- */

$setting['cleanup_head']=array(
	'title'=>'Cleanup &lt;head&gt;',
	'desc'=>'Several link elements are automatically added to the &lt;head&gt; '.
		'of your theme. Remove elements by selecting the appropriate checkboxes.',
	'tab'=>'advanced',
	'fields'=>array(
		// Remove link wlements
		'cleanup_head'=>array(
			'label'=>'Remove Link Element(s)',
			'type'=>'checkbox',
			'choices'=>array(
				'l10n.js'=>'l10n.js',
				'feed_links_extra'=>'feed_links_extra',
				'rsd_link'=>'rsd_link',
				'wlwmanifest_link'=>'wlwmanifest_link',
				'index_rel_link'=>'index_rel_link',
				'parent_post_rel_link'=>'parent_post_rel_link',
				'start_post_rel_link'=>'start_post_rel_link',
				'adjacent_posts_rel_link_wp_head'=>'adjacent_posts_rel_link_wp_head',
				'wp_shortlink_wp_head'=>'wp_shortlink_wp_head'
			)
		)
	)
);

/* ---- Comment Reply Javascript ------------------------------------------- */

$setting['commentreplyjs']=array(
	'title'=>'Comment Reply Javascript',
	'desc'=>'This script moves the comment form just below the comment when '.
		'replying to a comment. Check the box below to disable this script.',
	'tab'=>'advanced',
	'fields'=>array(
		// Disable comment-reply.js
		'commentreplyjs'=>array(
			'label'=>'Disable Script',
			'type'=>'checkbox',
			'choices'=>array(
				'commentreply_js'=>'Disable comment-reply.js'
			)
		)
	)
);
