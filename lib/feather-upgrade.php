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
		@version 1.2.7
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
			case '1.2.5':
				self::upgrade_127();
				break;
			default:
				self::upgrade_127();
		}
	}

	/**
		Upgrade to 1.2.7
			@private
	**/
	private static function upgrade_127() {
		self::$option['version']='1.2.7';
		update_option('feather',self::$option);
	}

}
