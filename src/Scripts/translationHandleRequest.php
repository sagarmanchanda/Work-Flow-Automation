<?php

if (session_id() == "") {
	session_start();
}

// Get the present state name/ID, username stored in Session variables. 
$stateName = $_SESSION['stateName'];
$stateID = $_SESSION['stateID'];

$username = $_SESSION['user_name'];
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
$databaseName = "requestDB";
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
$sql = "SELECT * FROM ".$stateName."_"."translation";
$result = $conn->query($sql);
while ($row = mysqli_fetch_assoc($result)) {
	$_inputs[$index] = array(
		'name' => $row['name'],
		'inputType' => $row['inputType'],
		'label' => $row['label'],
		'defaultValue' => $row['defaultValue'],
		);
	$index++;
}

// Sanitize the input.

// Now collect the data.
$values = [];
$index = 0;
if (isset($_POST['submit'])) {

	foreach ($_inputs as $key => $input) {
		if ($input['inputType'] == "DATABASE") {
			$sql = "SELECT * FROM ".$loginTableName." WHERE ".$usernameColumn."=\"".$username."\""; 
			$result = $loginconn->query($sql);
			while ($row = mysqli_fetch_assoc($result)) {
				$values[$index] = array(
					'value' => $row[$input['label']],
					'name' => $input['name']
				);
			}
		}
		else {
			if ($input['inputType'] == "radio") {
				if (isset($_POST[$input['name']]) && $_POST[$input['name']] == "TRUE"){
					$values[$index] = array(
						'value' => "TRUE",
						'name' => $input['name']
					);
				}
				else {
					$values[$index] = array(
						'value' => "FALSE",
						'name' => $input['name']
					);
				}
			}
			else {
				$temp = $input['name'];
				$formInputValue = $_POST[$temp];
				$values[$index] = array(
					'value' => $formInputValue,
					'name' => $input['name']
				);
			}
		}
		$index++;
	}
}

// Clean the dict for empty value. replace with false wherever required. and convert to string.
$sql = "SHOW COLUMNS FROM lookup_".$stateName."translation" or die("Unable to fetch column names from response lookup table.");
$result = $conn->query($sql);
$index = 0;
$responseLookupTableColumns = [];
while ($row = mysqli_fetch_assoc($result)) {
	foreach ($values as $key => $value) {
		if($row['Field'] == $value['name']) {
			$responseLookupTableColumns[$index] = array(
				'name' => $value['name'],
				'value' => $value['value']
				);
		}
	}
	$index++;
}








// Close the connections to database.
$conn->close();
$loginconn->close();

?>