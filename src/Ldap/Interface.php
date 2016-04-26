<?php
/*
 +--------------------------------------------------------------------+
 | WFA version 1.0                                                    |
 +--------------------------------------------------------------------+
 | Copyright 3S1J (c) 2016                                            |
 +--------------------------------------------------------------------+
 | This file is a part of WFA.                                        |
 |                                                                    |
 | Web framework for workflow automation (WFA) is free software;      |
 | you can copy, modify, and distribute it under the terms of         |
 | MIT License.                                                       |
 |                                                                    |
 | WFA is distributed in the hope that it will be useful, but         |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the MIT License in the LICENSE document for more details.      |
 |                                                                    |
 | You should have received a copy of the MIT License along with      |
 | this program; if not, contact WFA at wfa.cs243[AT]gmail[DOT]com.   |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package WFA
 * @copyright 3S1J (c) 2016
 */

namespace WFA\Ldap;

/**
 * This class is responsible for authenticating from LDAP server.
 */

class Interface {

	/**
	 * Function used to authenticate user against his/her Ldap account.
	 *
	 * @param $username
	 * @param $password
	 *
	 * @return bool
	 *  Whether Ldap login succeeded or not.
	 */
	public static function authenticate($username, $password) {
		$ds = self::connect();
		$dc = self::getDc();

		$user = "cn=".$username.",cn=users,".$dc;

		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		$bind = ldap_bind($ds, $username, $password);

		if ($bind) {
			return true;
		} else {
			return false;
		}
	}

	private static function connect() {
		$ldapconfig = fopen("../wfa.ldap", "r");

		$ldap = fgets($ldapconfig);

		$ds = ldap_connect($ldap);

		if ($ds) {
			return $ds;
		} else {
			return NULL;
		}

		fclose($ldapconfig);
	}

	private static function getDc() {
		$ldapconfig = fopen("../wfa.ldap", "r");
		fgets($ldapconfig);

		$dc = fgets($ldapconfig);

		fclose($ldapconfig);
		return $dc;
	}
}
