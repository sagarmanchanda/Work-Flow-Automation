<?php

namespace WFA\Auth_legacy;

/**
* This class handles the Connection of Database for User-Authentication.
*/
class ConnectDatabase {

	/**
	* It stores the hostname required to connect to MySQL.
	*
	* @var string
	*/
	protected $db_hostname = 'localhost';

	/**
	* It stores the username required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	protected $db_username = 'root';

	/**
	* It stores the password required to connect to MySQL. The coder is expected to changes
	* here, if required.
	*
	* @var string
	*/
	protected $db_password = '';

	/**
	* It stores the name of the database specified by the coder.
	*
	* @var string
	*/
	protected $db_name;

	/**
	* It stores the name of the table within database specified by the coder.
	*
	* @var string
	*/
	protected $table_name;

	/**
	* It stores the name of the column corresponding to username in table.
	*
	* @var string
	*/
	protected $user_column;

	/**
	* It stores the name of the column corresponding to password in table.
	*
	* @var string
	*/
	protected $pass_column;

	/**
	* It stores encryption-type of password. 0 for plain-text and 1 for md-5.
	*
	* @var int
	*/
	protected $hash_type;

	/**
	* Stores an object representing the connection to the MySQL server.
	*
	* @var connection_object
	*/
	protected $conn;

	/**
	* Function to set the values of the variables required for the purpose of accessing User-Auth database
	* and further setup the login page.
	*
	* @param string $db_name
	* 
	* @param string $table_name
	* 
	* @param string $user_column
	*
	* @param string $pass_column
	*
	* @param int $hash_type
	*/
	function __construct($db_name, $table_name, $user_column, $pass_column, $hash_type) {
		$this->db_name = $db_name;
		$this->table_name = $table_name;
		$this->user_column = $user_column;
		$this->pass_column = $pass_column;
		$this->hash_type = $hash_type;
		$this->connectDB();
		$Log = new \WFA\Auth\Login($this->conn, $this->table_name);
	}

	/**
	* Function to connect to the database.
	*/
	public function connectDB() {
		$this->conn = mysqli_connect($this->db_hostname, $this->db_username, $this->db_password, $this->db_name);
		if ($this->conn->connect_error) {
			die("Connection Failed: ".$this->conn->connect_error);
		}
	}

	/**
	* Function to disconnect from the database.
	*/
	public function disconnectDB() {
		mysqli_close($this->conn);
	}


}


?>
