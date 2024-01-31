<?php
session_start();
include "check_auth.php";

if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page if not logged in
};

// Database connection (replace placeholders with your details)
include("db_connect.php");

// If the form is submitted
if (isset($_POST['submit'])) {

    // Sanitize user input
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check for empty fields
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please enter all fields.";
    } else if ($new_password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Update password in database
        $username = $_SESSION['username']; // Retrieve username from session
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashed_password' WHERE username='$username'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Password change successful!";
            header("location: account.php"); // Redirect to account page
        } else {
            $_SESSION['error'] = "Password change failed. Please try again.";
        }
    }

}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Change Password</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <style>
        body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626; }
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; color:#FFFFFF; border:3px solid lightgreen;}
    </style>
    <body>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> <?php echo $_SESSION['error']; ?>
            </div>
        <?php unset($_SESSION['error']); } ?>

        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <strong>Success!</strong> <?php echo $_SESSION['success']; ?>
            </div>
        <?php unset($_SESSION['success']); } ?>
        <div class="wrapper">
            <form method="post" action="change_password.php">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required class="form-control" placeholder="********"><br><br>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required class="form-control" placeholder="********"><br><br>

                <button type="submit" name="submit" class="btn btn-success">Change Password</button>
            </form>
        </div>
    </body>
</html>
