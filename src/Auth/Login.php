<?php

namespace WFA\Auth;

/**
* 
*/
class Login {
	
	function __construct($par1, $par2) {
		echo $par2;
		$this->CreateLoginForm("POST","Marja");
	}

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
		 <div> <input type="text" name="username"></div>
		 </form> 
	 </body>
	 </html>";
}


}

?>