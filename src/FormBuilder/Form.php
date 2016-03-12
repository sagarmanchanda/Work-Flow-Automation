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
				$this->_inputs[$key]['rule'][] = $rule;
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


		if (empty($rule) || !in_array($rule, ['required', 'email'])) {
			die("<b>addRule not used properly. Please specify correct rule for $name.</b><br>");
		}
	}

	/**
	 * In case we need to export input to any other part of the api.
	 *
	 * @param $name
	 *  Name of the input to be exported.
	 */

	public function exportInput($name) {
		foreach ($this->_inputs as $key => $input) {
			if ($input['name'] == $name) {
				$export = array();
				$export = \WFA\Utils::copyValues($input);
			}
		}
		return $export;
	}

	/**
	 * Last function called for finally outputting the form.
	 */
	public function buildForm() {
		foreach ($this->_inputs as $key => $input) {
			// Check if email validation is required.
			if (isset($input['rule']) && in_array('email', $input['rule'])) {
				$input['inputType'] = 'email';
			}

			echo "<label>".$input['label']."</label><input type=\"".$input['inputType']."\" id=\"".$input['name']."\" name=\"".$input['name']."\" value=\"".$input['defaultValue']."\"";

			// Check if field was required.
			if (isset($input['rule']) && in_array('required', $input['rule'])) {
				echo " required";
			}

			echo "><br>";
		}
	}
}
