<?php 
	if ( session_id() == "" ){
		session_start();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>User Dashboard</title>
</head>
<body>
	<h1> Welcome 
	<?php 
		echo $_SESSION['user_name'];
	?>
	</h1>
	<br>
	<h2><a href="../Auth/logout.php">Logout</a></h2>
	<iframe src="formTemplate.php"></iframe>
</body>
</html>