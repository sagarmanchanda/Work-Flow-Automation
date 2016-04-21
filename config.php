<?php

	/**
	* It stores the hostname required to connect to MySQL.
	*
	* @var string
	*/
	$databaseHostname = 'localhost';

	/**
	* It stores the username required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	$databaseUsername = 'root';

	/**
	* It stores the password required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	$databasePassword = '';

	/**
	* It stores the hostname required to connect to MySQL.
	*
	* @var string
	*/
	$loginDatabaseHostname = 'localhost';

	/**
	* It stores the username required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	$loginDatabaseUsername = 'root';

	/**
	* It stores the password required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	$loginDatabasePassword = '';

	/**
	* It stores the name of the database which has the login credentials.
	*
	* @var string
	*/
	$loginDatabaseName = 'WFA';	

	/**
	* It stores the name of the database which has the login credentials.
	*
	* @var string
	*/
	$loginTableName = 'Login';	

	return array(
		'databaseHostname' => $databaseHostname,
		'databaseUsername' => $databaseUsername,
		'databasePassword' => $databasePassword,
		'loginDatabaseHostname' => $loginDatabaseHostname,
		'loginDatabaseUsername' => $loginDatabaseUsername,
		'loginDatabasePassword' => $loginDatabasePassword,
		'loginDatabaseName' => $loginDatabaseName,
		'loginTableName' => $loginTableName
	);

?>
