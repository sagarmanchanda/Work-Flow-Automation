<?php

namespace WFA\DefineResponses;

/**
* This class takes Responses from the coder and creates a lookup table for the DFA.
*/
class DefineResponses
{
	
	protected $stateName;

	protected $stateID;

	protected $stateType;

	protected $validResponse = [];

	function __construct($stateName, $stateID, $stateType)
	{	
		$error = $this->validateState($stateName, $stateID, $stateType);
		if (!empty($error)) {
			die($error);
		}
		$this->stateName = $stateName;
		$this->stateID = $stateID;
		$this->stateType = $stateType;	
	}

	public function defineParameters($elementArray){
		$error = $this->validateParameters($elementArray);
		if(!empty($error)){
			die($error);
		}
		else{
			$error = $this->createTable($elementArray);
			if(!empty($error)){
				die($error);
			}
		}

	}

	public function addResponses($valueArray, $response){
		$error = $this->validateResponses($valueArray, $response);
		if(!empty($error)){
			die($error);
		}
		else{
			$error = $this->insertResponses($valueArray, $response);
			if(!empty($error)){
				die($error);
			}
			
		}
	}

	protected function validateState($stateName, $stateID, $stateType) {
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}

		$isValidState = FALSE;
		$sql = "SELECT * FROM AutomataStates" or die("Unable to connect to AutomataStates Tables.");
		$result = $conn->query($sql);
		while ($row = \mysqli_fetch_assoc($result)) {
			if ($row['stateName'] == $stateName && $row['stateID'] == (string)$stateID && $row['stateType'] == $stateType) {
				$isValidState = TRUE;
			}
		}
		$conn->close();
		if (!$isValidState) {
			return "Given state parameters do not match any state.";
		}
	}

	protected function validateParameters($elementArray){
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		$tableName = $this->stateName."_".$this->stateType;
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		$ind = 0;
		foreach ($elementArray as $key => $value) {
			$sql = "SELECT inputType FROM ".$tableName." WHERE name ='".$value."'";
			$result = $conn->query($sql);
			$type = \mysqli_fetch_assoc($result);
			if (!empty($type['inputType'])) {
				// echo $type['inputType'];
				// echo "\n";
			}
			else
			{
				return "Fatal error : ".$value." not found in table";
			}
			if ($type['inputType']=="radio") {
				array_push($this->validResponse,"RADIO");
			}
			else if ($type['inputType']=="logic") {
				array_push($this->validResponse,"LOGIC");
			}
			else
			{
				array_push($this->validResponse,"STRING");
			}
			$ind++;
		}
		return "";
	}

	protected function createTable($elementArray){
		$tableName = "lookup_".$this->stateName."_".$this->stateType;
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		//here Database needs to be created already.
		//workflow does not allow Responses to be defined before Responses.
		//Responses (FiniteAutomate class) creates the database.
		//connecting to the database.
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		//creating table with name : "lookup_".$stateName
		$sql = "CREATE TABLE IF NOT EXISTS ".$tableName."( ";
		foreach ($elementArray as $key => $value) {
			$sql .= $value." VARCHAR(1000) ,";
		}
		$sql .= "response VARCHAR(100))";
		if ($conn->query($sql) === TRUE){
			return "";
		}
		else{
			return "Table ".$this->stateName." Creation Error:".$conn->error;
		}


	}

	protected function validateResponses($valueArray, $response){
		$tableName = "lookup_".$this->stateName."_".$this->stateType;
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		$sql="SELECT * FROM ".$tableName;
		$query=$conn->query($sql);    
		$num = \mysqli_num_fields($query);
		if (sizeof($valueArray)!=$num-1) {
			return "invalid no of arguements";
		}
		$ind = 0;
		foreach ($valueArray as $key => $value) {
			if ($value=="NULL") {
				$ind++;
				continue;
			}
			else if ($this->validResponse[$ind]=="STRING") {
				$ind++;
				continue;
			}
			else if ($this->validResponse[$ind]=="RADIO") {
				if ($value == "TRUE" || $value=="FALSE") {
					$ind++;
					continue;
				}
				else{
					return "Error : invalid value ".$value;
				}
				
			}
			// else if ( $this->validResponse["'".$ind."'"]=="LOGIC"	) {
			// 	if (//add valid logic for LOGIC) {
			// 		$ind++;
			// 		continue;
			// 	}
			// 	else{
			// 		return "Error : invalid value ".$value ;
			// 	}
				
			// }
		}
		//logic to validate arguements.
		return "";
	}

	protected function insertResponses($valueArray, $response){
		$tableName = "lookup_".$this->stateName."_".$this->stateType;
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		//here Database needs to be created already.
		//workflow does not allow Responses to be defined before Transitions.
		//Transitions (FiniteAutomate class) creates the database.
		//connecting to the database.
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		$sql = "INSERT INTO ".$tableName." VALUES (";
		foreach ($valueArray as $key => $value) {
			
			$sql .= "'".$value."',";
		}
		$sql .= "'".$response."')";
		if ($conn->query($sql) === TRUE){
			return "";
		}
		else{
			return "Table".$tableName."Insertion Error:".$conn->error;
		}
	}
}	
