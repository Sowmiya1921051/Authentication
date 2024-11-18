<?php
// Database configuration
$host = 'localhost';        // Database host (usually 'localhost')
$dbname = 'nms';            // Name of your database
$user = 'root';             // Database username
$pass = '';                 // Database password

// Establish a connection to the database
$con = mysqli_connect($host, $user, $pass, $dbname);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
