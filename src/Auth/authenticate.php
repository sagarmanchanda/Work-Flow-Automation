<?php

if ( session_id() == "" ){
	session_start();
}

$message="";

if(count($_POST)>0) {
	$conn = mysqli_connect($_SESSION["host_name"],$_SESSION["db_username"],$_SESSION["db_password"],$_SESSION["db_name"]);
	
	$sql = "SELECT * FROM ".$_SESSION["db_tab_name"]." WHERE ".$_SESSION["db_user_column"]."='" . $_POST["userName"] . "' and ".$_SESSION["db_pass_column"]." = '". $_POST["password"]."'";
	
	$result = mysqli_query($conn,$sql);
	
	$row  = mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if(is_array($row)) {
		$_SESSION["user_name"] = $row['username'];
	} 
	else {
		$message = "Invalid Username or Password!";
		echo $message;
	}
}

if(isset($_SESSION["user_name"])) {
	header("Location:../Templates/dashboard.php");
}
?>