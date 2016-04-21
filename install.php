<?php
require_once('vendor/autoload.php');

$databaseHostname = $argv[1];
$databaseUsername = $argv[2];
if (isset($argv[3])) {
	$databasePassword = $argv[3];
} else {
	$databasePassword = "";
}

// Set up database and save the config file.
echo \WFA\Settings::setUpDatabase($databaseHostname, $databaseUsername, $databasePassword);
