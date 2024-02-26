<?php
session_start();
include "db_connect.php";

// Retrieve the token from the URL
$token = $_GET['token'];
$email = $_GET['email'];

// Check if the token exists in the database
$sql = "SELECT * FROM users WHERE reset_token='$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    // Token is valid, proceed to password reset form
    $_SESSION['reset_token'] = $token; // Store token in session for security
    $_SESSION['email'] = $email;
    header("location: reset_password.php");
} else {
    // Token is invalid or expired
    $_SESSION['error'] = "Invalid or expired password reset link. Please try again.";
    header("location: forgot_password.php");
}
?>