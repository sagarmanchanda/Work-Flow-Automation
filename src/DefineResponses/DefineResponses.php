<?php

namespace WFA\DefineResponses;

/**
* This class takes Responses from the coder and creates a lookup table for the DFA.
*/
class DefineResponses
{
	/**
	 * Contains set of valid responses
	 *
	 * @var array
	 */
	protected $validResponse = [];

	/**
	 * Constructor Function, initializes a state.
	 */
	function __construct(){		
				
	}
	
	/**
	 *Function to define name of state and variables which cause transition of that state.
	 *
	 * @param string $stateName
	 *  Name of state.
	 *
	 * @param array $elementArray
	 *  Array of variables on which transition from given state depends on.
	 */
	public function defineParameters($stateName,$elementArray){
		$error = $this->validateParameters($stateName,$elementArray);
		if(!empty($error)){
			die($error);
		}
		else{
			$error = $this->createTable($stateName,$elementArray);
			if(!empty($error)){
				die($error);
			}
		}

	}

	/**
	 *Function to add transition from one state to another.
	 *
	 * @param string $stateName
	 * 
	 * @param array $valueArray
	 *  Contains value of variables on which they cause the particular transition from given state
	 *
	 * @param string $response
	 *  Represents the transition.
	 */
	public function addResponses($stateName,$valueArray,$response){
		$error = $this->validateResponses($stateName,$valueArray,$response);
		if(!empty($error)){
			die($error);
		}
		else{
			$error = $this->insertResponses($stateName,$valueArray,$response);
			if(!empty($error)){
				die($error);
			}
			
		}
	}

	protected function validateParameters($stateName,$elementArray){
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$databaseName = "requestDB";
		$tableName = $stateName;
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
				echo $type['inputType'];
				echo "\n";
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
	protected function createTable($stateName,$elementArray){
		$tableName = "lookup_".$stateName;
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
			$sql .= $value." VARCHAR(100) ,";
		}
		$sql .= "response VARCHAR(100))";
		if ($conn->query($sql) === TRUE){
			return "";
		}
		else{
			return "Table ".$stateName." Creation Error:".$conn->error;
		}


	}

	protected function validateResponses($stateName,$valueArray,$response){
		$tableName = "lookup_".$stateName;
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
		$num=mysqli_num_fields($query);
		if (sizeof($valueArray)!=$num-1) {
			return "invalid no of arguements";
		}
		$ind = 0;
		foreach ($valueArray as $key => $value) {
			if ($value=="NULL") {
				$ind++;
				continue;
			}
			else if ( $this->validResponse[$ind]=="STRING"	) {
				$ind++;
				continue;
			}
			else if ( $this->validResponse[$ind]=="RADIO"	) {
				if ($value == "true" || $value=="false") {
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

	protected function insertResponses($stateName,$valueArray,$response){
		$tableName = "lookup_".$stateName;
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
