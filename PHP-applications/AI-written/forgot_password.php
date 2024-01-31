<?php
session_start();
include "db_connect.php";

// If the form is submitted
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(32));

        // Update the user's token in the database
        $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $sql);

        // Send a password reset email
        // ... (Implement email sending logic here)
        $_SESSION['reset_link'] = "<a href='check_reset_token.php?token=$token'>Reset Password</a>";

        $_SESSION['success'] = "Password reset link sent to your email. Please check your inbox to continue.";
    } else {
        $_SESSION['error'] = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
        body{ font: 14px sans-serif; width: 660px; padding: 20px; margin: 0 auto; padding-top: 100px; background-color: #262626; }
        .wrapper{ width: 360px; padding: 20px; color: #FFFFFFFF; border:3px solid #ffbb00;}
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
<form method="post" action="forgot_password.php">
    <label for="email">Enter your email address:</label>
    <input type="email" name="email" id="email" required class="form-control"> <br><br>
    <button type="submit" name="submit" class="btn btn-warning">Submit</button><br><br>
</form>
</div>
<div class="wrapper">
<?php if (isset($_SESSION['reset_link'])) {echo "<p>Your inbox</p>"; echo $_SESSION['reset_link']; unset($_SESSION['reset_link']);}?>
</div>
</body>
</html>

