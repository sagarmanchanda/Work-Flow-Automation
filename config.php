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
