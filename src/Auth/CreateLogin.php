<?php

namespace WFA\Auth;

/**
* 
*/
class CreateLogin
{
	
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
		header('Location: '.$uri.'/team13cs243/src/Templates/redirect.php');
		exit;
	}
}
	
?>