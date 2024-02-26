<?php
session_start();
include "db_connect.php";

// If the form is submitted
if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique reset token
        $token = md5($email.time());

        // Update the user's token in the database
        $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $sql);

        // Send a password reset email
        // ... (Implement email sending logic here)
        $reset_link = "http://". $_SERVER['HTTP_HOST'] ."/check_reset_token.php?email=$email&token=$token";

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
<?php if ($reset_link) {echo "<div class='wrapper'> <p>Your inbox</p>"; echo "<a href='". $reset_link ."'>Reset Password</a> </div>";}?>
</body>
</html>

