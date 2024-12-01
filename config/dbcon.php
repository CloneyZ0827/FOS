<?php

// Check if constants are not already defined to avoid redefinition
if (!defined('DB_SERVER')) {
    define('DB_SERVER', "localhost");
}
if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', "root");
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', "");
}
if (!defined('DB_DATABASE')) {
    define('DB_DATABASE', "urbandaybreak");
}

// Establish the connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

?>
