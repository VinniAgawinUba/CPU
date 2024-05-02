<?php
$host = "mysql"; // Docker Compose service name
$port = 3306; // MySQL port
$username = "root"; // MySQL root username
$password = "password"; // MySQL root password
$database = "cpu_db"; // MySQL database name

// Create connection
$con = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>