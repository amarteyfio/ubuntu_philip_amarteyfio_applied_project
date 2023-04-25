<?php
/**
 * Database credentials
 *
 * This script defines the database server, username, password, and name.
*/
define('URL',parse_url(getenv("CLEARDB_DATABASE_URL")));

// Define the database server.
define('DB_SERVER', URL['host']);

// Define the database username.
define('DB_USERNAME', URL['user']);

// Define the database password.
define('DB_PASSWORD', URL['pass']);

// Define the database name.
define('DB_NAME', substr(URL['path'],1));

$active_group = 'default';
$query_builder = TRUE;
?>




