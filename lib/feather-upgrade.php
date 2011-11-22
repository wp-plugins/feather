<?php

/**
	Upgrade Library for the Feather Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Marée

		@package FeatherForm
		@version 1.1
**/

//! Upgrade Feather
class FeatherUpgrade extends FeatherBase {

	/**
		Initialize Upgrade
			@public
	**/
	static function init($version) {
		switch($version) {
			case '1.1':
				self::upgrade_11();
				break;
		}
	}

	/**
		Upgrade to 1.1
			@private
	**/
	private static function upgrade_11() {
		self::$option['version']='1.1';
		update_option('feather',self::$option);
	}

}