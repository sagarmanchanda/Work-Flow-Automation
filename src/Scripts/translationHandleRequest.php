<?php

if (session_id() == "") {
	session_start();
}

// Get the present state name/ID, username stored in Session variables. 
$requestID = $_POST['requestID'];
$stateName = $_SESSION['stateName'];
$stateID = $_SESSION['stateID'];
$username = $_SESSION['user_name'];
$usernameColumn = $_SESSION['db_user_column'];

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

// $values has all the names and values coming from page...

// Clean the dict for empty value. replace with false wherever required. and convert to string.
$sql = "SHOW COLUMNS FROM lookup_".$stateName."_translation" or die("Unable to fetch column names from response lookup table.");
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

// $responseLookupTableColumns contains all the names and values that are required to get response.

// Add support for logical statements processing...

// Calculate response from lookup table
$index = 0;
$sql = "SELECT * FROM lookup_".$stateName."_translation"." WHERE ";
foreach ($responseLookupTableColumns as $key => $responseLookupTableColumn) {
	if ($index == count($responseLookupTableColumns)-1) {
		break;
	}
	$sql .= $responseLookupTableColumn['name']." = \"".$responseLookupTableColumn['value']."\" AND ";
	$index++;
}
$sql .= $responseLookupTableColumns[$index]['name']." = \"".$responseLookupTableColumns[$index]['value']."\"";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
	die("No possible response defined for such action. Kindly fill the form properly again.");
}	
else{
	while ($row = mysqli_fetch_assoc($result)) {
		$response = $row['response'];
	}	
}


// Calculate next state from lookup table
$sql = "SELECT * FROM AutomataTransitions WHERE presentState=\"".$stateName."\" AND response=\"".$response."\"";
$result = $conn->query($sql) or die("Unable to connect to transition table to find next state.");
if ($result->num_rows == 0) {
	die("No possible transition defined for such response. Kindly define the transitions of automata properly.");
}
else{
	while ($row = mysqli_fetch_assoc($result)) {
		$nextState = $row['nextState'];
	}
}

// Update the DB....
$sql = "UPDATE RequestHandlingMain SET presentState=\"".$nextState."\", ";
$index = 0;
foreach ($values as $key => $value) {
	if($index == count($values)-1){
		break;
	}
	$sql .= $value['name']."=\"".$value['value']."\", ";
	$index++;
}
$sql .= $values[$index]['name']."=\"".$values[$index]['value']."\" WHERE requestID=\"".$requestID."\"";

if($conn->query($sql) === TRUE) {
	echo "transition successful";
	header("Location:../Templates/dashboard.php");
}
else {
	echo "Something went wrong. Please try again after sometime.";
}




// Close the connections to database.
$conn->close();
$loginconn->close();

?>