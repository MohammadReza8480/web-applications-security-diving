<?php
session_start();
// Database connection
include("db_connect.php");

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    // Validate token against database
    $sql = "SELECT * FROM users WHERE remember_token='$token'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Token is valid, log the user in automatically
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        // ... (set other session variables as needed)
    }
}
?>