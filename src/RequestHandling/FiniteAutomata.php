<?php

namespace WFA\RequestHandling;

/**
* This class helps build the heirarchy for request-handling making use of a finite
* state Automata/Machine.
*/
class FiniteAutomata
{
	
	/**
	 * Array storing all the state names along with their numeric id.
	 *
	 * @var array
	 */	
	protected $_states = [];

	/**
	 * Number of states in Automata. Please make sure to add the FINISHED/COMPLETED state.
	 *
	 * @var int
	 */		
	protected $stateIndex;

	/**
	 * Number of transitions in Automata.
	 *
	 * @var int
	 */		
	protected $transitionIndex;

	/**
	 * Array storing all the transition functions.
	 *
	 * @var array
	 */	
	protected $_transitions = [];

	/**
	 * Contructor function, initializes the automata.
	 *
	 */
	function __construct()
	{	
		$this->stateIndex = 0;
		$this->transitionIndex = 0;
	}

	/**
	 * Helps to create and add new states.
	 *
	 * @param string $stateName
	 *	Name of the state.
	 *
	 * @param int $stateID
	 *	ID of the state which will appear in RequestStatus.
	 */
	public function addState($stateName, $stateID) {
		$error = $this->validateState($stateName, $stateID);
		if (!empty($error)) {
			die($error);
		}
		$this->_states[$this->stateIndex] = array(
			'stateName' => $stateName,
			'stateID' => $stateID
			);
		$this->stateIndex++;
	}

	/**
	 * Checks if the given addition of state is valid or not.
	 *
	 * @param string $stateName
	 *	Name of the state.
	 *
	 * @param int $stateID
	 *	ID of the state which will appear in RequestStatus.
	 */
	protected function validateState($stateName, $stateID) {
		if (!isset($stateName)) {
			return "State name cannot be empty";
		}
		foreach ($this->_states as $key => $state) {
			if ($state['stateName'] == $stateName) {
				return "STATENAME CONFLICT: \"".$stateName."\" state name already in use. Please use some other state name.";
			}
		}
		foreach ($this->_states as $key => $state) {
			if ($state['stateID'] == $stateID) {
				return "STATEID CONFLICT: \"".$stateID."\" state id already in use. Please use some other state id.";
			}
		}
	}

	/**
	 * Helps to add/define Transitions .
	 *
	* @param string $transitionName
	 *	Name of the transition.
	 *
	 * @param string $presentState
	 *	
	 * @param string $nextState
	 *
	 * @param string $response
	 * Transition takes place on the basis of $response.
	 *
	 */
	public function addTransition($transitionName, $presentState, $nextState, $response) {
		$error = $this->validateTransition($transitionName, $presentState, $nextState, $response);
		if (!empty($error)) {
			die($error);
		}
		$this->_transitions[$this->transitionIndex] = array(
			'transitionName' => $transitionName,
			'presentState' => $presentState,
			'nextState' => $nextState,
			'response' => $response
			);
		$this->transitionIndex++;
	}

	/**
	 * Checks if the given addition of transition is valid or not.
	 *
	 * @param string $transitionName
	 *	Name of the transition.
	 *
	 * @param string $presentState
	 *	
	 * @param string $nextState
	 *
	 * @param string $response
	 * Transition takes place on the basis of $response.
	 *
	 */
	protected function validateTransition($transitionName, $presentState, $nextState, $response) {
		if (!isset($transitionName) || !isset($presentState) || !isset($nextState) || !isset($response)) {
			return "Variables cannot be NULL, please give a valid non-empty value to each variable.";
		}
		// Takes care that the automata stays deterministic.
		foreach ($this->_transitions as $key => $transition) {
			if ($transition['presentState'] == $presentState && $transition['response'] == $response) {
				return "You have already defined a transition from \"".$transition['presentState']."\" to \"".$transition['nextState']."\" on \"".$transition['response']."\". Please use some other response for transition \"".$transitionName."\".";
			}
		}
		// Checks for valid present state name.
		$isValidState = FALSE;
		foreach ($this->_states as $key => $state) {
			if ($state['stateName'] == $presentState) {
				$isValidState = TRUE;
			}
		}
		if (!$isValidState) {
			return "In transition \"".$transitionName."\" Present state \"".$presentState."\" for the given transition was not defined earlier. Please add the state before using it.";
		}	
		// Checks for valid nest state name.
		$isValidState = FALSE;
		foreach ($this->_states as $key => $state) {
			if ($state['stateName'] == $nextState) {
				$isValidState = TRUE;
			}
		}
		if (!$isValidState) {
			return "In transition \"".$transitionName."\" Next state \"".$nextState."\" for the given transition was not defined earlier. Please add the state before using it.";
		}

	}

	/**
	 * Saves the structure of the Automata in the database for future reference by creating tables for storing
	 * states and transitions.
	 *
	 * @param string $databaseName
	 *	The databse created by API is assumed to be named RequestDB throughout.
	 *
	 * @param string $stateTableName
	 *  The table created is named AutomataStates by default.
	 *	
	 * @param string $transitionTableName
	 *	The table created is named AutomataTransitions by default.
	 *
	 */
	public function saveToDatabase($databaseName = "requestDB", $stateTableName = "AutomataStates", $transitionTableName = "AutomataTransitions") {
		// Takes credentials from config.php and connects to DB.
		$config = include('config.php');
		$databaseHostname = $config['databaseHostname'];
		$databaseUsername = $config['databaseUsername'];
		$databasePassword = $config['databasePassword'];
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}
		// Connects to DB, create if does not exist.
		$sql = "CREATE DATABASE IF NOT EXISTS ".$databaseName;
		if ($conn->query($sql) === TRUE) {
			$conn->close();
		}

		// Creates a table for states and saves the content.
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error: ".$conn->connect_error);
		}

		$sql = "CREATE TABLE IF NOT EXISTS ".$stateTableName." (
		stateName VARCHAR(50) NOT NULL,
		stateID INT(5) PRIMARY KEY 
		)";
		if ($conn->query($sql) === TRUE) {
			// Empty the table, to override the contents.
			$sql = "TRUNCATE TABLE ".$stateTableName;
			$conn->query($sql);
			foreach ($this->_states as $key => $state) {
				$sql = "INSERT INTO ".$stateTableName."(stateName, stateID) VALUES (\"".$state['stateName']."\", \"".$state['stateID']."\")";
				if ($conn->query($sql) === FALSE) {
					die("Unable to add entries to table ".$stateTableName);
				}
			}
		}
		else {
			die("Table ".$stateTableName." Creation Error:".$conn->error);
		}

		// Create a table for transitions and add contents.
		$sql = "CREATE TABLE IF NOT EXISTS ".$transitionTableName." (
		transitionID INT(5) PRIMARY KEY,
		transitionName VARCHAR(50) NOT NULL,
		presentState VARCHAR(50) NOT NULL,
		nextState VARCHAR(50) NOT NULL,
		response VARCHAR(50) NOT NULL 
		)";
		if ($conn->query($sql) === TRUE) {
			// Empty the table, to override the contents.
			$sql = "TRUNCATE TABLE ".$transitionTableName;
			$conn->query($sql);
			foreach ($this->_transitions as $key => $transition) {
				$sql = "INSERT INTO ".$transitionTableName."(transitionID, transitionName, presentState, nextState, response) VALUES (\"".$key."\", \"".$transition['transitionName']."\", \"".$transition['presentState']."\", \"".$transition['nextState']."\", \"".$transition['response']."\")";
				if ($conn->query($sql) === FALSE) {
					die("Unable to add entries to table ".$transitionTableName);
				}
			}
		}
		else {
			die("Table ".$transitionTableName." Creation Error:".$conn->error);
		}

		$conn->close();
	}

}




?>