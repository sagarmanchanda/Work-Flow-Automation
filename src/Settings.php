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
		$conn = self::connectMysql($databaseHostname, $databaseUsername, $databasePassword);
		if (!$conn) {
			// ERROR: Invalid credentials or mysql not running. Please fix.
			return 0;
		}

		// Name of the database we're going to make/use.
		$databaseName = 'requestDB';

		$conn->query("CREATE DATABASE IF NOT EXISTS ".$databaseName.";");

		// Other statements specifying database structure.

		// Database successfully created.
		return 1;

	}

	/*
	 * Function used to connect to mysql using given settings.
	 */
	public static function connectMysql($databaseHostname, $databaseUsername, $databasePassword) {
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword);
		if ($conn->connect_error) {
    		return FALSE;
		} else {
			return $conn;
		}
	}

	/**
	 * If you're using this function in your code. STOP!
	 * ONLY MEANT TO BE USED DURING INSTALLATION BY THE INSTALL SCRIPT.
	 *
	 * Using this function may result in wormholes and your system suffering
	 * a horrible demise by getting crushed under a roller-coaster.
	 */
	public static function saveAuthInfo($databaseHostname, $databaseUsername, $databasePassword) {
		// write this shitwhen you wake up. zzz.
	}
}
