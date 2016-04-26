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

if ( session_id() == "" ){
	session_start();
}


if(count($_POST)>0) {
	$conn = mysqli_connect($_SESSION["host_name"],$_SESSION["db_username"],$_SESSION["db_password"],$_SESSION["db_name"]);
	
	$sql = "SELECT * FROM ".$_SESSION["db_tab_name"]." WHERE ".$_SESSION["db_user_column"]."='" . $_POST["userName"] . "' and ".$_SESSION["db_pass_column"]." = '". md5($_POST["password"])."'";
	
	$result = mysqli_query($conn,$sql);
	
	$row  = mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if(is_array($row)) {
		$_SESSION["user_name"] = $row['username'];
		$_SESSION['stateName'] = $row['stateName'];
		$_SESSION['stateID'] = $row['stateID'];
	} 
	else {
			header("Location:../Templates/login_failure.php");

	}
}

if(isset($_SESSION["user_name"])) {
	header("Location:../Templates/dashboard.php");
}
?>
