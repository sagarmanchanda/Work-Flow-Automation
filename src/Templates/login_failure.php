<?php 
	if ( session_id() == "" ){
		session_start();
		unset($_SESSION["user_name"]);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Failed</title>
</head>
<body>
	<h1>
		Invalid combination of id and password 
	</h1>
	<br>
	<h2><a href="redirect.php">try again</a></h2>
	
</body>
</html>