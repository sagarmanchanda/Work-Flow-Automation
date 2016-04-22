<?php

namespace WFA;

/**
 * This class contains general utility functions.
 */

class Utils
{
	/**
	 * Simple utility function to copy values from source array to final array.
	 *
	 * @param $source
	 */
	public static function copyValues($source) {
		$final = array();
		foreach ($source as $key => $value) {
			$final[$key] = $value;
		}

		return $final;
	}

	/**
	 * Get MySQL configuration from wfa.config.
	 *
	 * @return array|NULL
	 *  The config array returned.
	 */
	public static function getConfig() {
		$wfaConfig = fopen("wfa.config", "r");
		if (!$wfaConfig) {
			// In case wfa.config does not exist.
			return NULL;
		}

		$data = fgetcsv($wfaConfig);

		$databaseHostname = $data[0];
		$databaseUsername = $data[1];
		$databasePassword = $data[2];
		$databaseName = $data[3];

		$config = array(
			'databaseHostname' => $databaseHostname,
			'databaseUsername' => $databaseUsername,
			'databasePassword' => $databasePassword,
			'databaseName' => $databaseName,
		);

		return $config;
	}
	/**
	 * Function used to connect to mysql using given settings.
	 *
	 * @param string $databaseHostname
	 * @param string $databaseUsename
	 * @param string $databasePassword
	 * @param string $databaseName
	 *  Default value NULL because it may or may not be required.
	 *
	 * @return object|bool
	 */
	public static function connectMysql($databaseHostname, $databaseUsername, $databasePassword, $databaseName = NULL) {
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
    		return FALSE;
		} else {
			return $conn;
		}
	}

	/**
	 * Utility function to filter a string. Prevents XSS and SQL injections.
	 *
	 * @param $string
	 */

	public static function filterThis($string) {
		$string = mysql_real_escape_string($string);
		$string = htmlspecialchars($string);

		return $string;
	}

	public static function buildRequestHandlingMainTable($databaseName = "requestDB", $tableName = "RequestHandlingMain") {
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		// First extract all the states declared.
		$states = [];
		$index = 0;
		$sql = "SELECT stateName, stateType FROM AutomataStates" or die("Unable to connect to AutomataStates Tables. Looks like you have not defined states yet!");
		$result = $conn->query($sql);
		while ($row = \mysqli_fetch_assoc($result)) {
			if ($row['stateType'] == "final") {
				continue;
			}
			$states[$index] = $row['stateName']."_".$row['stateType'];
			$index++;
		}
		// Now go to every state table and extract all the required columns.
		$columns = [];
		$index = 0;
		foreach ($states as $state) {
			$sql = "SELECT name FROM ".$state or die("Unable to connect to \"".$state."\" table. Looks like you have not defined form for the state.");
			#echo $sql;
			$result = $conn->query($sql);
			while ($row = \mysqli_fetch_assoc($result)) {
				$columns[$index] = $row['name'];
				$index++;
			}
		}
		// Finally creating the table.
		$sql = "CREATE TABLE IF NOT EXISTS ".$tableName." (
		requestID VARCHAR(36) PRIMARY KEY,
		date TIMESTAMP, ";
		foreach ($columns as $column) {
			$sql .= $column." VARCHAR(1000), ";
		}
		$sql .= "presentState VARCHAR(50) NOT NULL
		)";

		if ($conn->query($sql) === TRUE) {
			$conn->close();
		}
		else {
			die("Unable to create RequestHandlingMain table. You must have done something wrong with respective state table or state declaration.");
		}

	} 
}
