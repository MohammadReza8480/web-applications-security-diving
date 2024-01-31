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
        
        // Optionally, update the token in the database for security
        // (prevents reuse of stolen cookies):
        $new_token = bin2hex(random_bytes(32));
        $sql = "UPDATE users SET remember_token='$new_token' WHERE username='{$_SESSION['username']}'";
        mysqli_query($conn, $sql);

        // Set the new token in the cookie
        setcookie('remember_token', $new_token, time() + 60 * 60 * 24 * 7, '/');
    }
}
?>