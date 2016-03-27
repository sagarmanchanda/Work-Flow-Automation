<?php

namespace WFA\FormBuilder;

/**
* This class handles all the form building operations.
*/
class Form
{
	/**
	 * Form submit method.
	 *
	 * @var string
	 */	
	protected $method;

	/**
	 * Title of the form.
	 *
	 * @var string
	 */
	protected $title;

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
		$this->title = $title;
		$this->method = $method;
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
		echo "<!DOCTYPE html>
		<html>
		<head>
			<title>$this->title</title>
		</head>
		<body>
		<form method=\"$this->method\">";
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

	/**
	 * Last function called for finally outputting the form as html into Template folder.
	 */
	public function buildForm_html() {
		$formTemplate = fopen("src/Templates/formTemplate.php", "w") or die("Unable to create form template!");
		$formHtml = "<!DOCTYPE html>
		<html>
		<head>
			<title>".$this->title."</title>
		</head>
		<body>
		<form method=\"".$this->method."\" action=\"../FormBuilder/submitRequest.php\" >";

		foreach ($this->_inputs as $key => $input) {
			// Check if email validation is required.
			if (isset($input['rule']) && in_array('email', $input['rule'])) {
				$input['inputType'] = 'email';
			}

			$formHtml .= "<label>".$input['label']."</label><input type=\"".$input['inputType']."\" id=\"".$input['name']."\" name=\"".$input['name']."\" value=\"".$input['defaultValue']."\"";

			// Check if field was required.
			if (isset($input['rule']) && in_array('required', $input['rule'])) {
				$formHtml .= " required";
			}

			$formHtml .= "><br>";
		}

		$formHtml .= "</form>
		</body>
		</html>";

		fwrite($formTemplate, $formHtml);
		fclose($formTemplate);
	}

	/**
	 * function called to create a database with table for request handling. 
	 */
	public function buildDatabase($db_name = "requestDB", $table_name = "requestHandling") {
		$hostname = "localhost";
		$db_username = "root";
		$db_password = "";
		$conn = new \mysqli($hostname, $db_username, $db_password);
		if ($conn->connect_error) {
			die("Connection failed: ".$conn->connect_error);
		}

		// Creating Database
		$sql = "CREATE DATABASE ".$db_name;
		if ($conn->query($sql) === TRUE) {
			$conn->close();
		}
		else {
			die("Unable to create database ".$conn->error);
		}

		$conn = new \mysqli($hostname, $db_username, $db_password, $db_name);
		if ($conn->connect_error) {
			die("Connection failed: ".$conn->connect_error);
		}

		// Create Table 
		$sql = "CREATE TABLE ".$table_name."(
		requestID VARCHAR(34) PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
		requestDate TIMESTAMP, ";
		foreach($this->_inputs as $key => $input) {
			if ($input['inputType'] == "submit") {
				continue;
			}
			else if ($input['inputType'] == "text") {
				$inputType_mysql = " VARCHAR(1000)";
			}
			else if ($input['inputType'] == "radio") {
				$inputType_mysql = " BOOL";
			}
			$sql .= $input['name'].$inputType_mysql.", ";
		}
		$sql .= "requestStatus INT(2) NOT NULL
		)";

		if ($conn->query($sql) === TRUE) {
			$conn->close();
		}
		else {
			die("Unable to create Table ".$conn->error);
		}

	}

	/**
	 * function called to create a table which stores the $_inputs array which is
	 * used later for the pupose of form-handling.
	 * helps to save the state of $_inputs to database.
	 *
	 */
	public function buildFormEntriesTable($db_name = "requestDB", $table_name = "FormEntries") {
		$hostname = "localhost";
		$db_username = "root";
		$db_password = "";
		$conn = new \mysqli($hostname, $db_username, $db_password, $db_name);
		if ($conn->connect_error) {
			die("Connection failed: ".$conn->connect_error);
		}

		// Create table
		$sql = "CREATE TABLE ".$table_name."(
		inputType VARCHAR(50) NOT NULL,
		name VARCHAR(50) NOT NULL,
		label VARCHAR(50),
		defaultValue VARCHAR(50)
		)";

		if ($conn->query($sql) === TRUE) {
			$conn->close();
		}
		else {
			die("Unable to create Table ".$conn->error);
		}

		// Add Records to the above created Table
		$conn = new \mysqli($hostname, $db_username, $db_password, $db_name);
		if ($conn->connect_error) {
			die("Connection failed: ".$conn->connect_error);
		}

		foreach($this->_inputs as $key => $input) {
			if ($input['inputType'] == "submit"){
				continue;
			}
			$sql = "INSERT INTO ".$table_name."(inputType, name, label, defaultValue) 
			VALUES (\"".$input['inputType']."\", \"".$input['name']."\", \"".$input['label']."\", \"".$input['defaultValue']."\")";
			if ($conn->query($sql) === FALSE){
				die("Unable to add entries to FormEntries table ".$conn->error);
			}
		}
		$conn->close();

	}

}
