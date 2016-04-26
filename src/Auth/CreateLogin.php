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

namespace WFA\Auth;

/**
* This class is responsible for construction of a basic login page.
* Login details are obtained from config.php
*/
class CreateLogin
{
	/**
	 * @param $table_name
	 * @param $db_name
	 * @param $user_column
	 * @param $pass_column
	 */	
	function __construct($table_name, $db_name, $user_column, $pass_column)
	{
		session_start();
		$_SESSION["db_name"]=$db_name;
		$_SESSION["db_tab_name"]=$table_name;				
		$_SESSION["db_user_column"]=$user_column;
		$_SESSION["db_pass_column"]=$pass_column;
		$_SESSION["host_name"]="localhost";
		$_SESSION["db_username"]="root";
		$_SESSION["db_password"]="";


		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
			$uri = 'https://';
		} else {
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'];
		header('Location:src/Templates/redirect.php');
		exit;
	}
}
	
?>