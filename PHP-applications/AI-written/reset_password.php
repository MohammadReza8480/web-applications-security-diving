<?php
session_start();
include "db_connect.php";

// Check if the token exists in the session
if (!isset($_SESSION['reset_token'])) {
    header("location: forgot_password.php"); // Redirect if no token
    exit();
}

// Retrieve the token from the session
$token = $_SESSION['reset_token'];

// Retrieve new password from the form (if submitted)
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (isset($_POST['submit'])) {
    // Check for empty fields
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please enter all fields.";
    } else if ($new_password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {

        // Update password in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
        mysqli_query($conn, $sql);

        // Redirect to login page
        unset($_SESSION['reset_token']); // Remove token from session
        header("location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password</title>
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
            <form method="post" action="reset_password.php">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required class="form-control" placeholder="********"><br><br>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required class="form-control" placeholder="********"><br><br>

                <button type="submit" name="submit" class="btn btn-success">Reset Password</button>
            </form>
        </div>
    </body>
</html>