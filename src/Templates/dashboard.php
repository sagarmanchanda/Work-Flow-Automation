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
		session_destroy(); 
	?>
	</h1>
</body>
</html>