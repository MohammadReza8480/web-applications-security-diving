<?php
session_start();

// Database connection
include("db_connect.php");


// If the form is submitted
if (isset($_POST['submit'])) {

    // Sanitize user input to prevent SQL injection attacks
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    // Verify credentials
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = sha1($_POST['password']); // Assuming password is hashed in the database

        if ($row['password'] == $hashed_password) {
            // Successful login
            if (isset($_POST['remember'])) {
                // Generate a unique token
                $token = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 16);

                $sql = "UPDATE users SET remember_token='$token' WHERE username='$username'";
                mysqli_query($conn, $sql);
                // Set cookie with long expiration time
                setcookie('remember_token', $token, time() + 60 * 60 * 24 * 7, '/'); // Expires in 7 days
            }            
            $_SESSION['username'] = $username;
            header("location: account.php"); // Redirect to a account page
        } else {
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        $_SESSION['error'] = "$username is Invalid username";
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626;}
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; color:#FFFFFF; border:3px solid lightblue;}
    </style>
</head>
<body>
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
        </div>
    <?php unset($_SESSION['error']); } ?>

    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required class="form-control" placeholder="Username" >
                <span class="invalid-feedback"></span>
            </div>    
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required class="form-control" placeholder="********">
                <span class="invalid-feedback"></span>
            </div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-danger" value="Login">
            </div>
            <a href="forgot_password.php">Forgot Password?</a>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            
        </form>
    </div>
</body>
</html>