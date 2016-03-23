<?php

namespace WFA\Auth_legacy;

/**
* This class handles the Login Form Creation and Validation.
*/
class Login {

	/**
	* Function used to initiate the login form for the Authentication Module.
	*
	* @param connection_object $conn
	*
	* @param string $table_name
	*/
	function __construct($conn, $table_name) {
		$this->CreateLoginForm("POST","src/Auth/loginscript.php");
	}

	/**
	* Function used to generate the login form in HTML.
	*
	* @param string $formMethod 
	*  Defines the form method like GET, POST, etc.
	*
	* @param string $formAction
	*/
	protected function CreateLoginForm($formMethod, $formAction){
		
		echo 
		"<!DOCTYPE html>
		 <html>
		 <head>
		 	<title>Login Page</title>
		 </head>
		 <body>
		 	<h1>LOGIN PAGE</h1>
		 	<form action=\"$formAction\" method=\"$formMethod\">
				<label>Webmail:</label><input type=\"text\" name=\"webmail\"><br>
				<label>Password:</label><input type=\"password\" name=\"password\"><br><br>
				<input type=\"submit\" value=\"Submit\">
			</form>
		 </body>
		 </html>";
	}

}

?>
