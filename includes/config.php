<?php

$db_host = "localhost";
$db_username = "root";
$db_passwd = "";

$conn = mysqli_connect($db_host, $db_username, $db_passwd) or die("Could not connect!\n");

// echo "Connection established.\n";
$db_name = "fumo_store";
mysqli_select_db($conn, $db_name) or die("Could not select the database $dbname!\n". mysqli_error($conn));

// Base URL for assets
$baseUrl = '/fumo_store2/';

// Base path for PHP includes (project root, not the includes folder)
define('BASE_PATH', __DIR__ . '/../'); 
// __DIR__ here is fumo_store2/config, so BASE_PATH = fumo_store2/
?>