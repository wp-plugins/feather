<?php

/**
	Feather Upgrade Library

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherUpgrade
		@version 1.2.6
**/

//! Upgrade Feather
class FeatherUpgrade extends FeatherBase {

	/**
		Initialize Upgrade
			@public
	**/
	static function init() {
		$version = self::get_option('version');
		switch($version) {
			case '1.0.6':
				self::upgrade_126();
				break;
			case '1.1':
				self::upgrade_126();
				break;
			case '1.2':
				self::upgrade_126();
				break;
			case '1.2.1':
				self::upgrade_126();
				break;
			case '1.2.2':
				self::upgrade_126();
				break;
			case '1.2.3':
				self::upgrade_126();
				break;
			case '1.2.4':
				self::upgrade_126();
				break;
			case '1.2.5':
				self::upgrade_126();
				break;
		}
	}

	/**
		Upgrade to 1.2.6
			@private
	**/
	private static function upgrade_126() {
		self::$option['version']='1.2.6';
		update_option('feather',self::$option);
	}

}
