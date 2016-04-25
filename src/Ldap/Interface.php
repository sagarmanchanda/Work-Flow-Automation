<?php

namespace WFA\Ldap;

/**
 * This class is responsible for authenticating from LDAP server.
 */

class Interface {
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
		$ldapconfig = fopen("wfa.ldap", "r");

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
