<?php

if ( session_id() == "" ){
	session_start();
}

$hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "requestDB";
$conn = new \mysqli($hostname, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
	die("Connection failed: ".$conn->connect_error);
}

$sql = "SELECT name FROM FormEntries";
$result = $conn->query($sql) or die("Unable to fetch data from FormEntries Table".$conn->error);

$inputNames = array();
$index = 0;
while ($row = mysqli_fetch_assoc($result)){
	$inputNames[] = $row;
	$index++;
}

$fields = "";
$values = "";

for ($i=0 ; $i<$index ; $i++) {
	$fields .= $inputNames[$i]['name'];
	$values .= "\"".$_POST[$inputNames[$i]['name']]."\"";
	$fields .= ", ";
	$values .= ", ";
}

$fields .= "username, ";
$values .= "\"".$_SESSION["user_name"]."\", ";

$timestamp = date('Y-m-d H:i:s');
$timestamp .= $_SESSION["user_name"];

$fields .= "requestID";
$values .= "\"".md5($timestamp)."\"";

$sql = "INSERT INTO requestHandling (".$fields.") VALUES (".$values.")";
if ($conn->query($sql) === TRUE) {
	$conn->close();
	header("Location:../Templates/submit_success.php");
}
else {
	header("Location:../Templates/submit_failure.php");
}


?> 