<?php 
	if ( session_id() == "" ){
		session_start();
	}
?>
<script src="http://www.w3schools.com/lib/w3data.js"></script>

<!DOCTYPE html>
<html>
<head>
	<title>User Dashboard</title>
</head>
<body>
	<h1> Welcome 
	<?php 
		echo $_SESSION['user_name']." ";
		echo $_SESSION['stateName'];
	?>
	</h1>
	<br>
	<h2><a href="../Auth/logout.php">Logout</a></h2>

<!-- Generation Form if exists-->
<?php
	$stateName = $_SESSION['stateName'];
	$config = include('../Scripts/config.php');
	$databaseHostname = $config['databaseHostname'];
	$databaseUsername = $config['databaseUsername'];
	$databasePassword = $config['databasePassword'];
	$databaseName = "requestDB";
	$conn = new mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
	if ($conn->connect_error) {
		die("Connection Error:".$conn->connect_error);
	}

	$sql = "SELECT * FROM AutomataStates WHERE stateName=\"".$stateName."\" AND stateType=\"generation\"";
	$result = $conn->query($sql);
	if($result->num_rows == 1) {
		$templateName = $stateName."_generation.php";
		include($templateName);
	}
	$conn->close();
?>

<!-- Generation Request Panel if exists-->
<?php
	$stateName = $_SESSION['stateName'];
	$username = $_SESSION['user_name'];
	$usernameColumn = $_SESSION['db_user_column'];
	$config = include('../Scripts/config.php');
	$databaseHostname = $config['databaseHostname'];
	$databaseUsername = $config['databaseUsername'];
	$databasePassword = $config['databasePassword'];
	$databaseName = "requestDB";
	$loginDatabaseHostname = $config['loginDatabaseHostname'];
	$loginDatabaseUsername = $config['loginDatabaseUsername'];
	$loginDatabasePassword = $config['loginDatabasePassword'];
	$loginDatabaseName = $config['loginDatabaseName'];
	$loginTableName = $config['loginTableName'];
	
	$conn = new mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
	if ($conn->connect_error) {
		die("Connection Error:".$conn->connect_error);
	}
	
	$loginconn = new \mysqli($loginDatabaseHostname, $loginDatabaseUsername, $loginDatabasePassword, $loginDatabaseName);
	if ($loginconn->connect_error) {
		die("Connection Error:".$loginconn->connect_error);
	}

	$sql = "SELECT * FROM AutomataStates WHERE stateName=\"".$stateName."\" AND stateType=\"generation\"";
	$result = $conn->query($sql);
	if($result->num_rows == 1) {

		$sql = "SELECT * FROM ".$stateName."_generation WHERE inputType=\"DATABASE\"";
		$result = $conn->query($sql);
		$columns = [];
		if($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				array_push($columns, array('columnNameinLogin' => $row['label'], 'columnNameinMainRequest' => $row['name']) );
			}
		}
		
		$sql = "SELECT * FROM ".$loginTableName." WHERE ".$usernameColumn."=\"".$username."\"";
		$result = $loginconn->query($sql);
		$columnValues = [];
		if($result->num_rows > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				foreach ($columns as $column) {
					array_push($columnValues, $row[$column['columnNameinLogin']]);
				}
			}
		}
		
		$sql = "SELECT * FROM RequestHandlingMain WHERE ";
		for ($index=0; $index < count($columns)-1; $index++) { 
			$sql .= $columns[$index]['columnNameinMainRequest']."=\"".$columnValues[$index]."\" AND ";
		}
		$sql .= $columns[$index]['columnNameinMainRequest']."=\"".$columnValues[$index]."\"";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			echo "<div> <h3>Your request:</h3> <br>";
			while($row = mysqli_fetch_assoc($result)) {
				$requestID = $row['requestID'];
				$presentState = $row['presentState'];
				echo $requestID."  ".$presentState."<br><br>";
				# echo "<input type=\"hidden\" name=\"requestID\" value=\"".$requestID."\">";
			}
			echo "</div>";
		}
	}

	$conn->close();
	$loginconn->close();
?>

<!-- Translation Request Panel if exists-->
<?php
	$stateName = $_SESSION['stateName'];
	$config = include('../Scripts/config.php');
	$databaseHostname = $config['databaseHostname'];
	$databaseUsername = $config['databaseUsername'];
	$databasePassword = $config['databasePassword'];
	$databaseName = "requestDB";

	$conn = new mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
	if ($conn->connect_error) {
		die("Connection Error:".$conn->connect_error);
	}
	
	$sql = "SELECT * FROM RequestHandlingMain WHERE presentState=\"".$stateName."\"";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		echo "<div><h3>Please verify the following requests...</h3><br>";
		while($row = mysqli_fetch_assoc($result)) {
			echo "<div>";
			$requestID = $row['requestID'];
			echo $requestID."<br>";
			echo "<form method=\"POST\" id=\"".$requestID."\" action=\"../Scripts/translationHandleRequest.php\">";
			include $stateName.'_translation.php';
			echo "<input type=\"hidden\" name=\"requestID\" value=\"".$requestID."\" form=\"".$requestID."\" >";
			echo "</div>";
		}
		echo "</div>";
	}
	$conn->close();

?>

<script>
w3IncludeHTML();
</script>

</body>
</html>
