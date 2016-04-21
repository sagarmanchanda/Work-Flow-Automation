<?php

namespace WFA;

/**
 * PLEASE MAKE SURE THIS FILE IS NOT WEB ACCESSIBLE!
 *
 * This file is responsible for handling the database requirements. The auth details for the database are stored
 * and accessed through this class. 
 *
 * The functions given here are accessible on the global level by using the Settings class.
 */

class Settings
{
	/**
	 * If you're using this function in your code. STOP!
	 * ONLY MEANT TO BE USED DURING INSTALLATION BY THE INSTALL SCRIPT.
	 *
	 * Using this function may result in wormholes and your system suffering
	 * a horrible demise by getting crushed under a roller-coaster.
	 */
	public static function setUpDatabase($databaseHostname, $databaseUsername, $databasePassword) {
		$conn = \WFA\Utils::connectMysql($databaseHostname, $databaseUsername, $databasePassword);
		if (!$conn) {
			// ERROR: Invalid credentials or mysql not running. Please fix.
			return 1;
		}

		// Name of the database we're going to make/use.
		$databaseName = 'requestDB';

		$conn->query("CREATE DATABASE IF NOT EXISTS ".$databaseName.";");

		// Database successfully created and then store credentials using this function.
		// TODO: This is a *bit* hard-coded. Change if possible.
		return self::saveAuthInfo($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
	}

	/**
	 * If you're using this function in your code. STOP!
	 * ONLY MEANT TO BE USED DURING INSTALLATION BY THE INSTALL SCRIPT.
	 *
	 * Using this function may result in wormholes and your system suffering
	 * a horrible demise by getting crushed under a roller-coaster.
	 */
	public static function saveAuthInfo($databaseHostname, $databaseUsername, $databasePassword, $databaseName) {
		$config = fopen("src/wfa.config", "w");
		if (!$config) {
			// ERROR: Unable to write wfa.config. Please check your read write permissions on the WFA module folder.(Example: Use sudo chmod or sudo chown).
			return 2;
		}

		$txt = "$databaseHostname, $databaseUsername, $databasePassword, $databaseName";
		fwrite($config, $txt);
		fclose($config);

		return 0;
	}
}
