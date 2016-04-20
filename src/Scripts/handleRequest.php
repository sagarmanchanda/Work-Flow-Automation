<?php

if (session_id() == "") {
	session_start();
}

// Get the present state name/ID, username stored in Session variables. 
$presentStateName = $_SESSION['stateName'];
$presentStateID = $_SESSION['stateID'];
$username = $_SESSION['userName'];
$usernameColumn = $_SESSION['db_user_column']; //Remove this wierd variable name.

// Get the credentials to connect to Databse from config file.
$config = include('config.php');
$databaseHostname = $config['databaseHostname'];
$databaseUsername = $config['databaseUsername'];
$databasePassword = $config['databasePassword'];
$loginDatabaseHostname = $config['loginDatabaseHostname'];
$loginDatabaseUsername = $config['loginDatabaseUsername'];
$loginDatabasePassword = $config['loginDatabasePassword'];
$loginDatabaseName = $config['loginDatabaseName'];
$loginTableName = $config['loginTableName'];

// Connecting to Database.
$conn = new mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
if ($conn->connect_error) {
	die("Connection Error:".$conn->connect_error);
}

$loginconn = new \mysqli($loginDatabaseHostname, $loginDatabaseUsername, $loginDatabasePassword, $loginDatabaseName);
if ($loginconn->connect_error) {
	die("Connection Error:".$loginconn->connect_error);
}


// Now see what you should expect to be coming from the form and save it in $_inputs.
$_inputs = [];
$index = 0;
$sql = "SELECT names, inputType, label  FROM ".$presentStateName;
$result = $conn->query($sql);
while ($row = mysqli_fetch_assoc($result)) {
	$_inputs[$index] = array(
		'name' => $row['name'],
		'inputType' => $row['inputType'],
		'label' => $row['label'],
		'defaultValue' => $row['defaultValue'];
		); 
}

// Now collect the data coming.
foreach ($_inputs as $key => $input) {
	if ($input['inputType'] == "DATABASE") {
		$sql = "SELECT * FROM ".$loginTableName." WHERE ".$usernameColumn."=\"".$userName."\""; 
		$result = $loginconn->query($sql);
		$input['value'] = $result[$input['label']];
	}
	else {
		$formInputValue = $_POST[$input['name']];
		$input['value'] = $formInputValue;
	}
}

// Clean the dict for empty value. replace with false wherever required. and convert to string.
foreach ($_inputs as $key => $input) {
	if ($input['inputType'] == "radio" || $input['value'] == "") {
		$input['value'] = "FALSE";
	}
	if (!is_string($input['value'])) {
		$temp = (string)$input['value'];
		$input['value'] = $temp;
	}
}



// Calculate response from lookup table
// Calculate next state from lookup table
// Update DB data + presentState of request which will be different for state=0/1.



// Close the connections to database.
$conn->close();
$loginconn->close();

?>
