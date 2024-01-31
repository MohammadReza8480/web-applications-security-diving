<?php

// Start a session to store user information
session_start();
include "check_auth.php";
// Connect to your database
include("db_connect.php");

// If the form is submitted
if (isset($_POST['submit'])) {

    // Sanitize user input to prevent security vulnerabilities
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check for empty fields
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header("location: register.php");
        exit();
    }

    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Username already exists. Please choose a different username.";
        header("location: register.php");
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    // Insert user data into database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Registration successful!";
        header("location: login.php"); // Redirect to login page
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626; }
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; color: #FFFFFF; border:3px solid #ffbb00;}
    </style>
</head>
<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
        </div>
    <?php unset($_SESSION['error']); } ?>

    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" placeholder="Username">
                
            </div>    
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="username" required class="form-control" placeholder="********">

            </div>
            <div class="form-group">
                <label for="password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="password" required class="form-control" placeholder="********">

            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-warning" name="submit" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>