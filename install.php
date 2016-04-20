<?php
require_once('vendor/autoload.php');

$databaseHostname = $argv[1];
$databaseUsername = $argv[2];
$databasePassword = $argv[3];

\WFA\Settings::saveAuthInfo($databaseHostname, $databaseUsername, $databasePassword);
echo \WFA\Settings::setUpDatabase($databaseHostname, $databaseUsername, $databasePassword);
