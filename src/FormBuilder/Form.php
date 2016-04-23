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
	protected $_inputs=[];

	/**
	 * Index of the inputs.
	 *
	 * @var int
	 */
	protected $index = 0;

	/**
	 * State name for which form would be defined.
	 *
	 * @var string
	 */
	protected $stateName;

	/**
	 * State ID for which form would be defined.
	 *
	 * @var string
	 */
	protected $stateID;

	/**
	 * State Type for which form would be defined.
	 *
	 * @var string
	 */
	protected $stateType;
	
	/**
	 * Contructor function, initializes the form.
	 *
	 * @param string $method
	 *	The method with which form will be submitted.
	 *
	 * @param string $title
	 *	The title of the web-form.
	 */
	function __construct($stateName, $stateID, $stateType)
	{	
		$this->stateName = $stateName;
		$this->stateID = $stateID;
		$this->stateType = $stateType;
		$this->method = "POST";
		$this->title = $stateName." Form";
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
	 * Function to add elements from database to act as identifiers. 'label' contains $columnName and 'defaultValue' contains 
	 * $elementType.
	 *
	 * @param string $name
	 * 
	 * @param string $columnName
	 *	column name corresponding to it in the database. 
	 *
	 * @param string $elementType
	 *	Variable type as in int, text, etc.
	 */
	public function addDatabaseElement($name, $columnName, $elementType) {
		$error = $this->validateAddDatabaseElement($name, $columnName, $elementType);
		if (!empty($error)) {
			die($error);
		}
		$this->_inputs[$this->index] = array(
			'inputType' => "DATABASE",
			'name' => $name,
			'label' => $columnName,
			'defaultValue' => $elementType
			);
		$this->index++;
	}

	/**
	 * Function to validate the addition of Database elements to act as identifiers. 
	 *
	 * @param string $name
	 * 
	 * @param string $columnName
	 *	column name corresponding to it in the database. 
	 *
	 * @param string $elementType
	 *	Variable type as in int, text, etc.
	 */
	private function validateAddDatabaseElement($name, $columnName, $elementType) {
		$config = include('config.php');
		$loginDatabaseHostname = $config['loginDatabaseHostname'];
		$loginDatabaseUsername = $config['loginDatabaseUsername'];
		$loginDatabasePassword = $config['loginDatabasePassword'];
		$loginDatabaseName = $config['loginDatabaseName'];
		$loginTableName = $config['loginTableName'];
		
		$conn = new \mysqli($loginDatabaseHostname, $loginDatabaseUsername, $loginDatabasePassword, $loginDatabaseName);
		if ($conn->connect_error) {
			die("Connection Error:".$conn->connect_error);
		}

		$sql = "SHOW COLUMNS FROM ".$loginTableName or die("Unable to fetch column names from login table.");
		$result = $conn->query($sql);
		$index = 0;
		$columns = [];
		while ($row = \mysqli_fetch_assoc($result)) {
			$columns[$index] = $row['Field'];
			$index++;
		}
		// Checks if the given columnName is indeed present in the table.
		$isValidColumnName = FALSE;
		foreach ($columns as $column) {
			if ($column == $columnName) {
				$isValidColumnName = TRUE;
			}
		}
		if (!$isValidColumnName) {
			return "Such a column \"".$columnName."\" does not exist in login table.";
		}
		// Takes care of repetition.
		foreach ($this->_inputs as $key => $input) {
			if ($input['name'] == $name) {
				return "\"".$name."\" name is already in use. Please use some other name for the element.";
			}
			if ($input['label'] == $columnName) {
				return "You have already included \"".$columnName."\". You cannot do it again.";
			}
		}
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
	 * Creates html equivalent/template of the form described for a particular state. This function should be called at the end
	 * once all the form entries are decided. The template is created in the Templates folder under src.
	 *
	 */
	public function buildFormTemplate() {
		$formTemplatepath = "src/Templates/".$this->stateName."_".$this->stateType.".php";
		$formTemplate = fopen($formTemplatepath, "w") or die ("Unable to create html template for \"".$this->stateName."_".$this->stateType."\" state.");
		$formHtml = "<!DOCTYPE html>
		<html>
		<head>
			<title>".$this->title."</title>
		</head>
		<body>";
		if ($this->stateType == "generation") {
			$formHtml .= "<form method=\"".$this->method."\" action=\"../Scripts/generationHandleRequest.php\" >";
		}
		
		foreach ($this->_inputs as $key => $input) {
			// Skip the Database elements.
			if ($input['inputType'] == "DATABASE") {
				continue;
			}
			// Check if email validation is required.
			if (isset($input['rule']) && in_array('email', $input['rule'])) {
				$input['inputType'] = 'email';
			}

			$formHtml .= "<label>".$input['label']."</label><input type=\"".$input['inputType']."\" id=\"".$input['name']."\" name=\"".$input['name']."\" value=\"".$input['defaultValue']."\"";

			// Check if field was required.
			if (isset($input['rule']) && in_array('required', $input['rule'])) {
				$formHtml .= " required";
			}
			// Makes the html page more readable, i.e. appending with a new line.
			$formHtml .= ">
			<br>";
		}

		$formHtml .= "</form>
		</body>
		</html>";

		fwrite($formTemplate, $formHtml);
		fclose($formTemplate);
	}

	
	/**
	 * function called to create a table which stores the contents of $_inputs array for a particular state in the requestDB
	 * database in the table which is names after the state. This table would be later used for the pupose of deciding response
	 * for a particular input from page and then further deciding the transition.
	 *	
	 * @param $databaseName
	 * 	Alwways use the default set value of the parameter, i.e. "RequestDB".
	 */
	public function buildFormInputFieldTable($databaseName = "requestDB") {
		// take credentials from config.php and connect to database.
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

		// Creates a table with name as stateName for state's form input fields and saves the content.
		$conn = new \mysqli($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
		if ($conn->connect_error) {
			die("Connection Error: ".$conn->connect_error);
		}

		$sql = "CREATE TABLE IF NOT EXISTS ".$this->stateName."_".$this->stateType."(
		inputType VARCHAR(50) NOT NULL,
		name VARCHAR(50) NOT NULL,
		label VARCHAR(50),
		defaultValue VARCHAR(50)
		)";
		if ($conn->query($sql) === TRUE) {
			// Empty the table, to override the contents.
			$sql = "TRUNCATE TABLE ".$this->stateName."_".$this->stateType;
			$conn->query($sql);
			foreach($this->_inputs as $key => $input) {
			// Skip the submit buttons
				if ($input['inputType'] == "submit") {
					continue;
				}
				$sql = "INSERT INTO ".$this->stateName."_".$this->stateType." (inputType, name, label, defaultValue) 
				VALUES (\"".$input['inputType']."\", \"".$input['name']."\", \"".$input['label']."\", \"".$input['defaultValue']."\")";
				if ($conn->query($sql) === FALSE) {
					die("Unable to add entries to FormEntries table ".$conn->error);
				}
		}
		}
		else {
			die("Unable to create Table for state \"".$this->stateName."_".$this->stateType."\": ".$conn->error);			
		}

		
		$conn->close();

	}

	/**
	 * Last function called for finally outputting the form.]
	 * 
	 * @deprecated
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

			echo "><br></form>";
		}
	}

}
