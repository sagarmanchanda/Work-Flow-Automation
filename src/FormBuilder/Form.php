<?php

namespace WFA\FormBuilder;

/**
* This class handles all the form building operations.
*/
class Form
{

	/**
	 * The array containing all inputs.
	 *
	 * @var array
	 */
	protected $_inputs;

	/**
	 * Index of the inputs.
	 *
	 * @var int
	 */
	protected $index = 0;
	
	/**
	 * Contructor function, initializes the form.
	 *
	 * @param string $method
	 *	The method with which form will be submitted.
	 *
	 * @param string $title
	 *	The title of the web-form.
	 */
	function __construct($method = 'POST', $title = 'WFA Form')
	{
		echo "<!DOCTYPE html>
		<html>
		<head>
			<title>$title</title>
		</head>
		<body>
		<form method=\"$method\">";
	}

	/**
	 * Function to add elements to the form. Just adds them to the _inputs class array.
	 *
	 * @param string $inputType
	 * 
	 * @param string $name
	 * 
	 * @param string $label
	 *
	 * @param string $defaultValue
	 */
	public function addElement($inputType, $name, $label = NULL, $defaultValue = NULL) {
		if (!isset($inputType) || !in_array($inputType, ['text', 'radio', 'submit'])) {
			die("Input type is not correct for field \"$name\". Please check your usage of addElement function.");
		}

		$this->_inputs[$this->index] = array(
			'inputType' => $inputType,
			'name' => $name,
			'label' => $label,
			'defaultValue' => $defaultValue
			);
		$this->index++;

		// echo "<label>$label</label><input type=\"$inputType\" id=\"$name\" name=\"$name\" value=\"$defaultValue\"><br>";
	}

	/**
	 * Wrapper function to add rules to form elements. Just searches through the _inputs array and adds a rule field.
	 *
	 * @param string $name
	 * 	The field to which the rule should be added.
	 *
	 * @param string $rule
	 */
	public function addRule($name, $rule) {
		$this->checkRule($name, $rule);

		foreach ($this->_inputs as $key => $input) {
			if ($input['name'] == $name) {
				$this->_inputs[$key]['rule'] = $rule;
			}
		}
	}

	/**
	 * This function checks if the rule being added is formatted properly.
	 *
	 * @param string $name
	 *
	 * @param string $rule
	 */
	protected function checkRule($name, $rule) {
		if (empty($name)) {
			die("<b>addRule not used properly. Please specify a name of input field.</b><br>");
		}

		$isCorrectName = FALSE;
		foreach ($this->_inputs as $key => $input) {
			if ($input['name'] == $name) {
				$isCorrectName = TRUE;
			}
		}
		if (!$isCorrectName) {
			die("<b>addRule not used properly. Please specify correct name of input field.</b><br>");
		}


		if (empty($rule) || !in_array($rule, ['required'])) {
			die("<b>addRule not used properly. Please specify correct rule for $name.</b><br>");
		}
	}

	/**
	 * Last function called for finally outputting the form.
	 */

	public function buildForm() {
		foreach ($this->_inputs as $key => $input) {
			echo "<label>".$this->_inputs[$key]['label']."</label><input type=\"".$this->_inputs[$key]['inputType']."\" id=\"".$this->_inputs[$key]['name']."\" name=\"".$this->_inputs[$key]['name']."\" value=\"".$this->_inputs[$key]['defaultValue']."\"><br>";
		}
	}
}
