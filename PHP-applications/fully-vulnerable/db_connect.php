<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$database = "vultestapp";
$username = "test";
$password = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}