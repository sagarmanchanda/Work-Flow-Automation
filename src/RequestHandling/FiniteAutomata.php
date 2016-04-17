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
	 * Number of states in Automata. Please make sure to add the FINISHED/COMPLETED state.
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
	 *	Name of the sate.
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
	 *	Name of the sate.
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



}




?>