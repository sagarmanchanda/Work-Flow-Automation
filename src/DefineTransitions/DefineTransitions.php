<?php

namespace WFA\DefineTransitions;

/**
* This class takes transitions from the coder and creates a lookup table for the DFA.
*/
class DefineTransitions
{
	
	function __construct(){		
				
	}
	public function defineParameters($stateName,$elementArray){
		$error = this->validateParameters($stateName,$elementArray)
		if(!isempty($error)){
			die($error);
		}
		else{
			this->createTable($stateName,$elementArray);
		}

	}

	public function addTransition($valueArray,$response){
		$error = this->validateTransition($valueArray,$response);
		if(!isempty($error)){
			die($error);
		}
		else{
			this->insertTransition($valueArray,$response);
		}
	}


	protected function validateParameters($stateName,$elementArray){
		return "";
	}

	protected function createTable($stateName,$elementArray){
		
	}

	protected function validateTransition($valueArray,$response){
		return "";
	}

	protected function insertTransition($valueArray,$response){
		
	}
}	
